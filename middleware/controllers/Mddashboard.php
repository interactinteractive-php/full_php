<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mddashboard Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Dashboard
 * @category	Mddashboard (Санал хүсэлт)
 * @author	B.Bilguun <bilguun@veritech.mn>
 * @link	http://www.veritech.mn/PHPframework/Middleware/Mddashboard
 */
class Mddashboard extends Controller {

    private static $viewPath = 'middleware/views/dashboard/';
    public static $titleSeparator = ',';
    public static $chartVersion = 0;

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function diagramRenderByPost($metaDataId = '') {
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        self::extraExtensions();
        
        $this->view->metaDataId = ($metaDataId != '' ? Input::param($metaDataId) : Input::numeric('metaDataId'));
        $this->view->executeType = Input::post('executeType');
        $this->view->rowIdGmap = Input::post('rowId');
        $this->view->criteriaString = Input::post('defaultCriteriaData');
        
        if ($this->view->metaDataId == 1505127833519) {
            sleep(25);
        }
        
        $this->load->model('mddashboard', 'middleware/models/');
        
        $this->view->diagram = $this->model->getMetaDiagramLinkModel($this->view->metaDataId);
        $this->view->getAddonSettings = json_decode(Arr::get($this->view->diagram, 'ADDON_SETTINGS'), true);

        $this->view->processMetaDataId = $this->view->diagram['PROCESS_META_DATA_ID'];
        $this->view->workSpaceParams = Input::post('workSpaceParams');
        $this->view->workSpaceId = Input::numeric('workSpaceId');
        
        if ($setHeight = Input::numeric('setHeight')) {
            $this->view->diagram['HEIGHT'] = $setHeight;
            $this->view->setHeight = $setHeight;
        }
        
        self::getDiagramProcessMetaDataIdByDiagram($this->view->diagram['PROCESS_META_DATA_ID']);
        $this->view->metaDataId = $this->view->metaDataId . '_' . getUID();
        $renderPage = self::getDiagramTypeByRenderMethod($this->view->diagram);

        $response = array(
            'Title' => Lang::line($this->view->diagram['META_DATA_NAME']),
            'width' => '800',
            'height' => '600',
            'close_btn' => Lang::line('close_btn'),
            'Html' => $this->view->renderPrint($renderPage, self::$viewPath)
        );

        echo json_encode($response); exit;
    }
    
    public function extraExtensions($type = 'index') {
        switch ($type) {
            case "index":
                $this->view->js = AssetNew::highchartJs();
                $this->view->fullUrlJs = AssetNew::amChartJs();
                $this->view->fullUrlCss = AssetNew::amChartCss();
                break;
            case "diagramRender":
                $this->view->fullUrlCss = array('middleware/assets/css/dashboard/dashboardMain.css');
                break;
            case "flot":
                $this->view->js = array(
                    'custom/addon/plugins/jquery-flotchart/jquery.flot.js',
                    'custom/addon/plugins/jquery-flotchart/jquery.flot.tooltip.min.js',
                    'custom/addon/plugins/jquery-flotchart/jquery.flot.resize.js',
                    'custom/addon/plugins/jquery-flotchart/jquery.flot.pie.resize.js',
                    'custom/addon/plugins/jquery-flotchart/jquery.flot.animator.min.js',
                    'custom/addon/plugins/jquery-flotchart/jquery.flot.growraf.js'
                );
                break;
            case "car":
                $this->view->fullUrlCss = array(
                    'middleware/assets/css/card/card.css'
                );
                break;
        }
    }

    public function diagramRender($metaDataId) {

        $this->view->metaDataId = $metaDataId;
        $this->view->diagram = $this->model->getMetaDiagramLinkModel($this->view->metaDataId);
        self::getDiagramProcessMetaDataIdByDiagram($this->view->diagram['PROCESS_META_DATA_ID']);
        self::extraExtensions('diagramRender');
        
        $response = self::getDiagramRenderMethod($this->view->diagram['DASHBOARD_TYPE']);
        
        echo json_encode($response); exit;
    }

    public function getMetaDiagramLink($metaDataId) {
        $this->load->model('mddashboard', 'middleware/models/');
        return $this->model->getMetaDiagramLinkModel($metaDataId);
    }

    public function getColumnDiagramData() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $labelStep = $chartValues['LABEL_STEP'];
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];
        $isLittle = $chartValues['IS_LITTLE'];
        if ($chartValues['PROCESS_META_DATA_ID'] == null) {
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements'];
            $getCategory = $phpArray['elements'];
            foreach ($getCategory as $row) {
                array_push($categoryList, $row['elements'][0]['value']);
                foreach ($row['elements'][1]['elements'] as $rowData) {
                    $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => '[' . $rowData['elements']['1']['value'] . ']');

                    for ($i = 0, $count = count($series); $i < $count; $i++) {
                        if ($series[$i]['name'] === $rowData['elements']['0']['value']) {
                            //                  $series[$i]['data'] = $rowData['elements']['1']['value'];
                            if (is_array($series[$i]['data'])) {
                                array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                            }
                            $check = true;
                        }
                    }
                    if (!$check) {
                        array_push($series, $dataArray);
                    }
                }
            }
        } else {
            $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple);
            $categoryList = $collectData['categoryList'];
            $series = $collectData['series'];
        }
        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'dataLabel' => $dataLabel,
            'labelStep' => $labelStep,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
            'isLittle' => $isLittle
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getColumnOneDiagramData() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;
        $error = null;
        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId); 
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $labelStep = $chartValues['LABEL_STEP'];
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];
        $isLittle = $chartValues['IS_LITTLE'];
        if ($chartValues['PROCESS_META_DATA_ID'] == null) {
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements'];
            $getCategory = $phpArray['elements'];
            foreach ($getCategory as $row) {
                array_push($categoryList, $row['elements'][0]['value']);
                foreach ($row['elements'][1]['elements'] as $rowData) {
                    $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => '[' . $rowData['elements']['1']['value'] . ']');

                    for ($i = 0, $count = count($series); $i < $count; $i++) {
                        if ($series[$i]['name'] === $rowData['elements']['0']['value']) {

                            if (is_array($series[$i]['data'])) {
                                array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                            }
                            $check = true;
                        }
                    }
                    if (!$check) {
                        array_push($series, $dataArray);
                    }
                }
            }
        } else {
            $config = array('xAxis' => $chartValues['XAXIS'], 'yAxis' => $chartValues['YAXIS'], 'xAxisGroup' => $chartValues['XAXISGROUP'], 'yAxisGroup' => $chartValues['YAXISGROUP']);
            $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple, $config);
            $categoryList = $collectData['categoryList'];
            $series = $collectData['series'];
        }
        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'dataLabel' => $dataLabel,
            'labelStep' => $labelStep,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
            'isLittle' => $isLittle,
            'error' => $error
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getDataForAmchart($userId = '1') {
        
        $categoryList = $series = $dataArray = $mainArray = array();
        $check = false;
        $error = null;
        
        $metaDataId = Input::numeric('metaDataId');
        $this->view->metaDataId = $metaDataId;
        
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId, true);
        
        if (Input::numeric('version') == 1) {
            self::$titleSeparator = '|$|';
        }
        
        try {
            switch ($chartValues['DIAGRAM_TYPE']) {
                case 'am_stacked_bar_chart' :
                case 'am_3d_stacked_column_chart_2' :
                case 'am_reversed' : {
                    $mainArray = $this->amReversedChart($chartValues, $metaDataId);
                    break;
                }
                default : {
                    $chartType    = $chartValues['DIAGRAM_TYPE'];
                    $width        = $chartValues['WIDTH'];
                    $height       = $chartValues['HEIGHT'];
                    $chartTitle   = $chartValues['TITLE'];
                    $isTitle      = $chartValues['IS_SHOW_TITLE'];
                    $isuseLegend  = $chartValues['IS_USE_LEGEND'];
                    $isExport     = $chartValues['IS_SHOW_EXPORT'];
                    $isLegend     = $chartValues['IS_SHOW_LABEL'];
                    $dataLabel    = $chartValues['IS_DATA_LABEL'];
                    $labelStep    = $chartValues['LABEL_STEP'];
                    $isInlineLegend = $chartValues['IS_INLINE_LEGEND'];
                    $legendFormat = $chartValues['LEGEND_FORMAT'];
                    $valueAxesMin = $chartValues['MINIMUM_VALUE'];
                    $valueAxesMax = $chartValues['MAXIMUM_VALUE'];
                    $colorField   = $chartValues['COLOR_FIELD'];
                    $isMultiple   = $chartValues['IS_MULTIPLE'];
                    $isXLabel     = $chartValues['IS_X_LABEL'];
                    $isYLabel     = $chartValues['IS_Y_LABEL'];
                    $isBackground = $chartValues['IS_BACKGROUND'];
                    $isLittle = $chartValues['IS_LITTLE'];
                    $xLabelRotation = $chartValues['X_LABEL_ROTATION'];
                    
                    if ($chartValues['PROCESS_META_DATA_ID'] == null) {
                        $data = $chartValues['TEXT'];
                        $data = simplexml_load_string($data);
                        $phpArray = $this->ws->wsObjectToArray($data, 'request');
                        $phpArray = $phpArray['elements']['4']['elements'];
                        $getCategory = $phpArray['elements'];
                        foreach ($getCategory as $row) {
                            array_push($categoryList, $row['elements'][0]['value']);
                            foreach ($row['elements'][1]['elements'] as $rowData) {
                                $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => '[' . $rowData['elements']['1']['value'] . ']');

                                for ($i = 0, $count = count($series); $i < $count; $i++) {
                                    if ($series[$i]['name'] === $rowData['elements']['0']['value']) {
                                        if (is_array($series[$i]['data'])) {
                                            array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                                        }
                                        $check = true;
                                    }
                                }
                                if (!$check) {
                                    array_push($series, $dataArray);
                                }
                            }
                        }
                    } else { 
                        $config = array('xAxis' => $chartValues['XAXIS'], 'yAxis' => $chartValues['YAXIS'], 'xAxisGroup' => $chartValues['XAXISGROUP'], 'yAxisGroup' => $chartValues['YAXISGROUP'], 'colorField' => $chartValues['COLOR_FIELD']);
                        $collectData = self::collectDataNew($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple, $config, $userId = null);
                                
                        $categoryList = $collectData['categoryList'];
                        $series = $collectData['series'];
                        $error = $collectData['error'];
                    }

                    $this->load->model('mdobject', 'middleware/models/');

                    $this->view->processMetaDataId = $chartValues['PROCESS_META_DATA_ID'];
                    
                    if ($getAddonSettings = issetParam($chartValues['ADDON_SETTINGS'])) {
                        $getAddonSettings = json_decode($getAddonSettings, true);
                        if (issetParam($getAddonSettings['criteriaPosition']) == 'topFilterButton') {
                            $this->view->isFilterButton = true;
                        }
                    }
            
                    $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($chartValues['PROCESS_META_DATA_ID']);
                    $defaultCriteria = $this->view->renderPrint('defaultCriteria', self::$viewPath);

                    $categoryList = $collectData['categoryList'];
                    $series = $collectData['series'];
                    $error = $collectData['error'];
                    
                    $mainArray = array(
                        'chartType' => $chartType,
                        'linkedMetaDataId' => $metaDataId,
                        'defaultCriteria' => $defaultCriteria,
                        'isTitle' => (float) $isTitle,
                        'isUseLegend' => $isuseLegend,
                        'title' => Lang::line($chartTitle),
                        'description' => Lang::line($chartValues['DESCRIPTION']),
                        'templateWidth' => $chartValues['TEMPLATE_WIDTH'],
                        'width' => $width,
                        'height' => $height,
                        'categories' => $categoryList,
                        'series' => $series,
                        'isLegend' => (float) $isLegend,
                        'isExport' =>(float)  $isExport,
                        'dataLabel' => (float) $dataLabel,
                        'labelStep' =>(float)  $labelStep,
                        'isInlineLegend' =>(float)  $isInlineLegend,
                        'legendFormat' => $legendFormat,
                        'valueAxesMin' => $valueAxesMin,
                        'valueAxesMax' => $valueAxesMax,
                        'colorField' => $colorField,
                        'isXLabel' =>(float)  $isXLabel,
                        'isYLabel' => (float) $isYLabel,
                        'isBackground' => (float) $isBackground,
                        'isLittle' => (float) $isLittle,
                        'xLabelRotation' => (float) $xLabelRotation,            
                        'error' => $error,
                        'DRILLDOWN' => (float) $chartValues['DRILLDOWN'],
                        'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']), 
                        'valueAxisTitle'    => Lang::line($chartValues['VALUE_AXIS_TITLE']), 
                        'categoryAxisTitle' => Lang::line($chartValues['CATEGORY_AXIS_TITLE']), 
                        'legendPosition'    => $chartValues['LEGEND_POSITION'], 
                        'addonSettings'     => json_decode($chartValues['ADDON_SETTINGS']), 
                        'labelTextSubStr'   => empty($chartValues['LABEL_TEXT_SUBSTR']) ? '10000' : $chartValues['LABEL_TEXT_SUBSTR'],
                        'realLegendPosition'=> $chartValues['REAL_LEGEND_POSITION']
                    );
                    
                    if (isset($collectData['theme'])) {
                        $mainArray['theme'] = $collectData['theme'];
                    }
                    
                    break;
                }
            }
            
        } catch (Exception $ex) {
            $mainArray = array('status' => 'warning', 'message' => $ex->msg, 'error' => 'Хариуцсан ажилтантай холбогдоно уу?');
        }

        echo json_encode($mainArray); 
    }
    
    public function amReversedChart($chartValues, $metaDataId) {
        $mainArray    = array();
        
        $chartType    = $chartValues['DIAGRAM_TYPE'];
        $width        = $chartValues['WIDTH'];
        $isuseLegend  = $chartValues['IS_USE_LEGEND'];
        $height       = $chartValues['HEIGHT'];
        $chartTitle   = $chartValues['TITLE'];
        $isTitle      = $chartValues['IS_SHOW_TITLE'];
        $isExport     = $chartValues['IS_SHOW_EXPORT'];
        $isLegend     = $chartValues['IS_SHOW_LABEL'];
        $dataLabel    = $chartValues['IS_DATA_LABEL'];
        $labelStep    = $chartValues['LABEL_STEP'];
        $isMultiple   = $chartValues['IS_MULTIPLE'];
        $isXLabel     = $chartValues['IS_X_LABEL'];
        $isYLabel     = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];
        $isLittle     = $chartValues['IS_LITTLE'];
        $xLabelRotation = $chartValues['X_LABEL_ROTATION'];
        $colorField   = $chartValues['COLOR_FIELD'];
        
        if ($chartValues['PROCESS_META_DATA_ID'] == null) {
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            
            $phpArray = $phpArray['elements']['4']['elements'];
            $getCategory = $phpArray['elements'];
            foreach ($getCategory as $row) {
                array_push($categoryList, $row['elements'][0]['value']);
                foreach ($row['elements'][1]['elements'] as $rowData) {
                    $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => '[' . $rowData['elements']['1']['value'] . ']');

                    for ($i = 0, $count = count($series); $i < $count; $i++) {
                        if ($series[$i]['name'] === $rowData['elements']['0']['value']) {

                            if (is_array($series[$i]['data'])) {
                                array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                            }
                            $check = true;
                        }
                    }
                    if (!$check) {
                        array_push($series, $dataArray);
                    }
                }
            }
            
        } else {
            
            $config = array(
                'xAxis' => $chartValues['XAXIS'], 
                'yAxis' => $chartValues['YAXIS'], 
                'xAxisGroup' => $chartValues['XAXISGROUP'], 
                'yAxisGroup' => $chartValues['YAXISGROUP'], 
                'yAxisGroup' => $chartValues['YAXISGROUP'], 
                'colorField' => $colorField
            );
            if ($chartValues['ADDON_SETTINGS']) {
                $addonSettings = json_decode($chartValues['ADDON_SETTINGS'], true);

                if ($addonSettings && issetParam($addonSettings['stacky']) && issetParam($addonSettings['stackx'])) {
                    $config['xAxisGroupOrder'] = $addonSettings['stackxorder'];
                }
            }            
            
            $collectData = $this->collectDataNew($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple, $config, $userId = null);
            $collectData2 = array();
            
            if ($chartValues['ADDON_SETTINGS']) {
                $addonSettings = json_decode($chartValues['ADDON_SETTINGS'], true);

                if ($addonSettings && issetParam($addonSettings['stacky']) && issetParam($addonSettings['stackx'])) {
                    $config = array(
                        'xAxis' => $chartValues['XAXIS'], 
                        'yAxis' => $addonSettings['stacky'], 
                        'xAxisGroup' => $addonSettings['stackx'], 
                        'xAxisGroupOrder' => $addonSettings['stackxorder'], 
                        'yAxisGroup' => $chartValues['YAXISGROUP'], 
                        'colorField' => $colorField
                    );
                    
                    $collectData2 = $this->collectDataNew($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple, $config, $userId = null);
                    
                    foreach ($collectData['series']['data'] as $rowCollKey => $rowCollect) {
                        $collectData['series']['data'][$rowCollKey] = array_merge($rowCollect, $collectData2['series']['data'][$rowCollKey]);
                    }
                }
            }
            
            $categoryList = $collectData['categoryList'];
            $series       = $collectData['series'];
            $error        = $collectData['error'];
        }

        $this->load->model('mdobject', 'middleware/models/');

        $this->view->processMetaDataId = $chartValues['PROCESS_META_DATA_ID'];
        $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($chartValues['PROCESS_META_DATA_ID']);
        $defaultCriteria = $this->view->renderPrint('defaultCriteria', self::$viewPath);

        $categoryList = $collectData['categoryList'];
        $series = $collectData['series'];
        $series2 = issetParam($collectData2['series']);
        $error = $collectData['error'];
        
        $mainArray = array(
            'chartType'         => $chartType,
            'linkedMetaDataId'  => $metaDataId,
            'categorieField'    => $collectData['categoryField'],
            'defaultCriteria'   => $defaultCriteria,
            'isTitle'           => $isTitle,
            'isUseLegend'       => $isuseLegend,
            'title'             => Lang::line($chartTitle),
            'width'             => $width,
            'height'            => $height,
            'categories'        => $categoryList,
            'series'            => $series,
            'series2'           => $series2,
            'isLegend'          => $isLegend,
            'isExport'          => $isExport,
            'dataLabel'         => $dataLabel,
            'labelStep'         => $labelStep,
            'labelText'         => issetParam($chartValues['LEGEND_FORMAT']),
            'isXLabel'          => $isXLabel,
            'isYLabel'          => $isYLabel,
            'isBackground'      => $isBackground,
            'isLittle'          => $isLittle,
            'xLabelRotation'    => $xLabelRotation,            
            'error'             => $error,
            'DRILLDOWN'         => $chartValues['DRILLDOWN'],
            'theme'             => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
            'legendPosition'    => $chartValues['LEGEND_POSITION'], 
            'labelTextSubStr'   => empty($chartValues['LABEL_TEXT_SUBSTR']) ? '10000' : $chartValues['LABEL_TEXT_SUBSTR'],
            'realLegendPosition'=> $chartValues['REAL_LEGEND_POSITION'], 
            'valueAxisTitle'    => Lang::line($chartValues['VALUE_AXIS_TITLE']), 
            'categoryAxisTitle' => Lang::line($chartValues['CATEGORY_AXIS_TITLE']), 
            'addonSettings'     => json_decode($chartValues['ADDON_SETTINGS']),
            'colorField'        => $colorField
        );
        
        if (isset($collectData['theme'])) {
            $mainArray['theme'] = $collectData['theme'];
        }
        
        return $mainArray;
    }
    
    public function getBarOneDiagramData() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $labelStep = $chartValues['LABEL_STEP'];
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];
        $isLittle = $chartValues['IS_LITTLE'];
        if ($chartValues['PROCESS_META_DATA_ID'] == null) {
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements'];
            $getCategory = $phpArray['elements'];
            foreach ($getCategory as $row) {
                array_push($categoryList, $row['elements'][0]['value']);
                foreach ($row['elements'][1]['elements'] as $rowData) {
                    $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => '[' . $rowData['elements']['1']['value'] . ']');

                    for ($i = 0, $count = count($series); $i < $count; $i++) {
                        if ($series[$i]['name'] === $rowData['elements']['0']['value']) {

                            if (is_array($series[$i]['data'])) {
                                array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                            }
                            $check = true;
                        }
                    }
                    if (!$check) {
                        array_push($series, $dataArray);
                    }
                }
            }
        } else {
            $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple);
            $categoryList = $collectData['categoryList'];
            $series = $collectData['series'];
        }
        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'dataLabel' => $dataLabel,
            'labelStep' => $labelStep,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
            'isLittle' => $isLittle
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getPieDiagramData() {
        $categoryList = array();
        $seriesData = array();
        $series = array();

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $chartTitle = $chartValues['TITLE'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];
        if ($chartValues['PROCESS_META_DATA_ID'] == null) { // Хэрэв процесс эсвэл Dynamic view тохируулаагүй бол
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements']; // CLEARED
            $title = $phpArray['elements']['0']['value'];
            $data = $phpArray['elements']['1']['elements'];

            foreach ($data AS $row) {
                if ($row['elements']['1']['value'] != null) {
                    $dataArray = array('name' => $row['elements']['0']['value'], 'y' => $row['elements']['1']['value']);
                    array_push($series, $dataArray);
                }
            }
        } else {// Хэрэв процесс эсвэл Dynamic view тохируулсан бол веб сервис дуудана      
            $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple);
            $categoryList = $collectData['categoryList'];
            $series = $collectData['series'];
        }

        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];

//        array_push($categoryList, $title);
        $categoryList = $chartTitle;
//        unset($series[2]);
        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'dataLabel' => $dataLabel,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getLineDiagramData() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $labelStep = $chartValues['LABEL_STEP'];
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];

        if ($chartValues['PROCESS_META_DATA_ID'] == null) {
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements'];
            $getCategory = $phpArray['elements'];
            foreach ($getCategory as $row) {
                array_push($categoryList, $row['elements'][0]['value']);
                foreach ($row['elements'][1]['elements'] as $rowData) {
                    $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => '[' . $rowData['elements']['1']['value'] . ']');

                    for ($i = 0, $count = count($series); $i < $count; $i++) {
                        if ($series[$i]['name'] === $rowData['elements']['0']['value']) {
                            //                  $series[$i]['data'] = $rowData['elements']['1']['value'];
                            if (is_array($series[$i]['data'])) {
                                array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                            }
                            $check = true;
                        }
                    }
                    if (!$check) {
                        array_push($series, $dataArray);
                    }
                }
            }
        } else {
            $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple);
            $categoryList = $collectData['categoryList'];
            $series = $collectData['series'];
        }
        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'dataLabel' => $dataLabel,
            'labelStep' => $labelStep,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getAreaWithNullDiagramData() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $labelStep = $chartValues['LABEL_STEP'];
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];
        if ($chartValues['PROCESS_META_DATA_ID'] == null) {
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements'];
            $getCategory = $phpArray['elements'];
            foreach ($getCategory as $row) {
                array_push($categoryList, $row['elements'][0]['value']);
                foreach ($row['elements'][1]['elements'] as $rowData) {
                    $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => '[' . $rowData['elements']['1']['value'] . ']');

                    for ($i = 0, $count = count($series); $i < $count; $i++) {
                        if ($series[$i]['name'] === $rowData['elements']['0']['value']) {
                            //  $series[$i]['data'] = $rowData['elements']['1']['value'];
                            if (is_array($series[$i]['data'])) {
                                array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                            }
                            $check = true;
                        }
                    }
                    if (!$check) {
                        array_push($series, $dataArray);
                    }
                }
            }
        } else {
            $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple);
            $categoryList = $collectData['categoryList'];
            $series = $collectData['series'];
        }
        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'dataLabel' => $dataLabel,
            'labelStep' => $labelStep,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getStockSingleLineData() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);
        $chartJsonValues = null;
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $labelStep = $chartValues['LABEL_STEP'];
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];

//            $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple);
//            $categoryList = $collectData['categoryList'];
//            $series = $collectData['series'];

        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'data' => $chartJsonValues,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'dataLabel' => $dataLabel,
            'labelStep' => $labelStep,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function callProcessWebservice($proccessMetaDataId) {
        $dashboardResultData = null;

        $_POST['methodId'] = $proccessMetaDataId;
        // call proccess webservice            
        $this->load->model('mdwebservice', 'middleware/models/');
        $postData = Input::postData();
        $metaDataId = Input::param($postData['methodId']);
        $row = $this->model->getMethodIdByMetaDataModel($metaDataId); // collect webservice data from database

        $param = array();
        
        if (isset($postData['param'])) {
            
            $paramData = $postData['param'];
            $paramList = $this->model->groupParamsDataModel($metaDataId, null, ' AND PAL.PARENT_ID IS NULL');

            foreach ($paramList as $input) {
                
                $typeCode = strtolower($input['META_TYPE_CODE']);
                
                if ($typeCode != 'group') {

                    if ($typeCode == 'boolean') {
                        if (isset($paramData[$input['META_DATA_CODE']])) {
                            $param[$input['META_DATA_CODE']] = '1';
                        } else {
                            $param[$input['META_DATA_CODE']] = '0';
                        }
                    } else {
                        if (isset($paramData[$input['META_DATA_CODE']])) {
                            $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType($paramData[$input['META_DATA_CODE']], $typeCode);
                        } else {
                            $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                        }
                    }
                } else {
                    if ($input['IS_SHOW'] == '1') {
                        $param[$input['META_DATA_CODE']] = (new Mdwebservice())->fromPostGenerateArray(
                            $metaDataId, $input['ID'], $input['META_DATA_CODE'], $input['RECORD_TYPE'], $paramData, 0
                        );
                    }
                }
            }

            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param);
            
            if ($this->ws->isException()) {
                $result = array('status' => 'error', 'message' => $this->ws->getErrorMessage());
            } else {
                if (isset($result['result']['result'])) {
                    $dashboardResultData = $result['result']['result'];
                }
            }
        }

        return $dashboardResultData;
    }

    public function getColumnOrder($columnArray) {
        $resultArray = array();
        foreach ($columnArray AS $index => $row) {
            $resultArray[$index]['FIELD_PATH'] = $row['FIELD_PATH'];
            $resultArray[$index]['LABEL_NAME'] = Lang::line($row['LABEL_NAME']);
        }

        return $resultArray;
    }

    public function collectData($metaDataId, $type, $isMultiple = 0, $userId = null) {
        $categoryList = array();
        $series = array();
        $name = $this->model->getMetaDataName($metaDataId);
        $param = array();
        // FILTER
        if (Input::postCheck('defaultCriteriaData')) {
            parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);
            if (Input::post('defaultCriteriaData') != null) {
                $defaultCriteriaParam = $defaultCriteriaData['param'];
                $defaultCriteriaOperator = $defaultCriteriaData['criteriaOperator'];

                (Array) $paramDefaultCriteria = array();

                foreach ($defaultCriteriaParam as $defParam => $defParamVal) {
                    if (!is_array($defParamVal)) {
                        $defParamVal = Input::param(Str::lower(trim($defParamVal)));
                    } else {
                        $defParamVal[0] = Input::param(Str::lower(trim($defParamVal[0])));
                        $defParamVal[1] = isset($defParamVal[1]) ? Input::param(Str::lower(trim($defParamVal[1]))) : '';
                    }

                    if ($defParamVal != "") {

                        if (!is_array($defParamVal)) {
                            $pos = strpos($defParamVal, ".");
                            if ($pos != false) {

//                                $tmpDefParamVal = explode('.', $defParamVal);
//                                $defParamVal = $tmpDefParamVal[0];
                                $defParamVal = str_replace(",", "", $defParamVal);
                            }
                        } else {
                            for ($i = 0; $i < count($defParamVal); $i++) {

                                $pos = strpos($defParamVal[$i], ".");
                                if ($pos != false) {

//                                    $tmpDefParamVal = explode('.', $defParamVal[$i]);
//                                    $defParamVal[$i] = $tmpDefParamVal[0];
                                    $defParamVal[$i] = str_replace(",", "", $defParamVal[$i]);
                                }
                            }
                        }

                        if ($defaultCriteriaOperator[$defParam] == 'BETWEEN') {

                            $paramDefaultCriteria[$defParam][] = array(
                                'operator' => $defaultCriteriaOperator[$defParam],
                                'operand' => "'" . $defParamVal[0] . isset($defParamVal[1]) ? "' AND '" . $defParamVal[1] . "'" : ''
                            );
                        } else {
                            $paramDefaultCriteria[$defParam][] = array(
                                'operator' => $defaultCriteriaOperator[$defParam],
                                'operand' => $defParamVal
                            );
                        }
                    }
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                } else {
                    $param['criteria'] = $paramDefaultCriteria;
                }
            }
        }
        if (!Input::isEmpty('filterJson')) {
                
            $criteria = @json_decode(Str::cp1251_utf8(html_entity_decode($_POST['filterJson'], ENT_QUOTES, 'UTF-8')), true);

            if (is_array($criteria)) {

                foreach ($criteria as $key => $value) {
                    $paramFilter[$key][] = array('operator' => '=', 'operand' => $value);
                }

                if ($paramFilter) {
                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                    } else {
                        $param['criteria'] = $paramFilter;
                    }   
                }
            }
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        $dataGridOptionData = $this->model->getDVGridOptionsModel($metaDataId);
        
        WebService::$isUseReport = true;
        
        switch ($type) {
            case 'columnOne' : {
                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId,
                        'showQuery' => 0
                    ));

                    // вебсервис дуудах
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    }
                    //get columns
                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    // sort columns
                    $columnOrder = $this->getColumnOrder($columnData);
                    $tmpArray = array();                    
                    // Sort data
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    // collecting data
                    foreach ($data['result'] AS $index => $dataRow) {
                        // collect categories
                        if ($dataRow[$columnData[0]['FIELD_PATH']] != null) {
                            array_push($categoryList, $dataRow[$columnData[0]['FIELD_PATH']]);
                            $series['name'] = $name;
                        }

                        // collect series
                        if ($dataRow[$columnData[1]['FIELD_PATH']] != null) {
//                            array_push($series, $dataRow[$columnData[1]['FIELD_PATH']]);
                            $series['data'][] = $dataRow[$columnData[1]['FIELD_PATH']];
                        }
                    }

                    break;
                }
            case 'column' : {
                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId,
                        'showQuery' => 0
                    ));

                    // вебсервис дуудах
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);

                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    }
                    //get columns
                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    // sort columns
                    $columnOrder = $this->getColumnOrder($columnData);
                    $nameArray = array();
                    $tmpArray = array();
                    $categoryArray = array();
                    $dataIndex = 0;
                    $prevName = '';
                    $valueArray = array();
                    // Sort data
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    // collecting data
                    foreach ($data['result'] AS $index => $dataRow) {
                        // collect categories
                        if ($dataRow[$columnData[0]['FIELD_PATH']] != null) {
                            if (!in_array($dataRow[$columnData[0]['FIELD_PATH']], $categoryList)) {
                                array_push($categoryList, $dataRow[$columnData[0]['FIELD_PATH']]);
                                array_push($series, $tmpArray);
                                $nameArray = array();
                            } else {
                                array_push($nameArray, $dataRow[$columnData[1]['FIELD_PATH']]);
                            }
                        }

                        if (!in_array($dataRow[$columnData[1]['FIELD_PATH']], $nameArray)) {
                            $series[$dataRow[$columnData[0]['FIELD_PATH']]] = array();
                            $series[$dataRow[$columnData[0]['FIELD_PATH']]]['name'] = $dataRow[$columnData[1]['FIELD_PATH']];
                            $series[$dataRow[$columnData[0]['FIELD_PATH']]]['data'][] = $dataRow[$columnData[2]['FIELD_PATH']];
                        } else {
                            $series[$dataRow[$columnData[0]['FIELD_PATH']]]['name'] = $dataRow[$columnData[1]['FIELD_PATH']];
                            $series[$dataRow[$columnData[0]['FIELD_PATH']]]['data'][] = $dataRow[$columnData[2]['FIELD_PATH']];
                        }
                    }
                    break;
                }
            case 'pie' : {
                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId, //$chartValues['PROCESS_META_DATA_ID'], 
                        'showQuery' => 0
                    ));

                    // вебсервис дуудах
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    }
                    //get columns
                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    // sort columns
                    $columnOrder = $this->getColumnOrder($columnData);
                    $categoryList = array();
                    $series = array();
                    $tmpArray = array();
                    // Sort data
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    // collecting data
                    foreach ($data['result'] AS $index => $dataRow) {

                        if ($dataRow[$columnData[1]['FIELD_PATH']] == null) {
                            continue;
                        }

                        // collect categories
                        if ($dataRow[$columnData[0]['FIELD_PATH']] != null) {
                            array_push($categoryList, $dataRow[$columnData[0]['FIELD_PATH']]);
                            $tmpArray['name'] = $dataRow[$columnData[0]['FIELD_PATH']];
                        }

                        // collect series
                        if ($dataRow[$columnData[1]['FIELD_PATH']] != null) {
                            $tmpArray['y'] = $dataRow[$columnData[1]['FIELD_PATH']];
                        } else {
                            $tmpArray['y'] = 0;
                        }

                        array_push($series, $tmpArray);
                    }

                    break;
                }
            case 'barOne' : {
                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId,
                        'showQuery' => 0
                    ));

                    // вебсервис дуудах
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    }
                    //get columns
                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    // sort columns
                    $columnOrder = $this->getColumnOrder($columnData);
                    $tmpArray = array();
                    // Sort data
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    // collecting data
                    foreach ($data['result'] AS $index => $dataRow) {
                        // collect categories
                        if ($dataRow[$columnData[0]['FIELD_PATH']] != null) {
                            array_push($categoryList, $dataRow[$columnData[0]['FIELD_PATH']]);
                            $series['name'] = $name;
                        }

                        // collect series
                        if ($dataRow[$columnData[1]['FIELD_PATH']] != null) {
//                            array_push($series, $dataRow[$columnData[1]['FIELD_PATH']]);
                            $series['data'][] = $dataRow[$columnData[1]['FIELD_PATH']];
                        }
                    }

                    $series = '[' . $series . ']';
                    break;
                }
            case 'bar' : {
                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId, //$chartValues['PROCESS_META_DATA_ID'], 
                        'showQuery' => 0
                    ));

                    // вебсервис дуудах
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    }
                    //get columns
                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    // sort columns
                    $columnOrder = $this->getColumnOrder($columnData);
                    $categoryList = array();
                    $series = array();
                    $tmpValueArray = array();
                    // Sort data
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    // collecting data
                    foreach ($data['result'] AS $index => $dataRow) {
                        if ($dataRow[$columnData[1]['FIELD_PATH']] != null) {
                            // collect categories
                            if ($dataRow[$columnData[0]['FIELD_PATH']] != null) {
                                array_push($categoryList, $dataRow[$columnData[0]['FIELD_PATH']]);
//                                $series[$index]['name'] = $dataRow[$columnData[0]['FIELD_PATH']];
//                                $categoryList = $dataRow[$columnData[0]['FIELD_PATH']];
                            }

                            // collect series
                            if ($dataRow[$columnData[1]['FIELD_PATH']] != null) {
                                $series['data'][] = $dataRow[$columnData[1]['FIELD_PATH']];
//                                array_push($tmpValueArray, $dataRow[$columnData[1]['FIELD_PATH']]);
                            }
                        }
                    }
                    $series['name'] = 'title';
//                    $series['data'] = $tmpValueArray;

                    break;
                }
            case 'areaWithNull' : {
                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId,
                        'showQuery' => 0
                    ));

                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    }

                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    $columnOrder = $this->getColumnOrder($columnData);

                    $categoryList = array();
                    $series = array();
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    
                    if (isset($data['result'])) {
                        unset($data['result']['aggregatecolumns']);
                        unset($data['result']['paging']);
                        foreach ($data['result'] AS $index => $dataRow) {
                            if ($dataRow[$columnData[0]['FIELD_PATH']]) {
                                array_push($categoryList, $dataRow[$columnData[0]['FIELD_PATH']]);
                            }
                            array_push($series, $dataRow[$columnData[1]['FIELD_PATH']]);
                        }
                    }


                    break;
                }
            case 'line' : {

                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId, //$chartValues['PROCESS_META_DATA_ID'], 
                        'showQuery' => 0
                    ));

                    // вебсервис дуудах
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    }
                    //get columns
                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    // sort columns
                    $columnOrder = $this->getColumnOrder($columnData);
                    $sortList = array();
                    foreach ($columnOrder AS $sortKey => $sortValue) {
                        $sortList[$sortKey] = $sortValue['LABEL_NAME'];
                    }

                    $categoryList = array();
                    $series = array();
                    $tmpArray = array();
                    $nameArray = array();
                    $nameCounter = 0;
                    $counter = 0;
                    // Sort data
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    if ($isMultiple == 1) {
                        foreach ($data['result'] AS $index => $dataRow) {

                            if (!isset($dataRow[$columnData[1]['FIELD_PATH']])) {
                                continue;
                            }
                            if (!isset($dataRow[$columnData[2]['FIELD_PATH']])) {
                                continue;
                            }

                            if (!in_array($dataRow[$columnData[0]['FIELD_PATH']], $nameArray)) {
                                $counter = $nameCounter;
                                $nameArray[$nameCounter] = $dataRow[$columnData[0]['FIELD_PATH']];
                                $series[$nameCounter]['name'] = $dataRow[$columnData[0]['FIELD_PATH']];

                                $nameCounter++;
                            }

                            if ($dataRow[$columnData[1]['FIELD_PATH']] != null && $dataRow[$columnData[2]['FIELD_PATH']] != null) {
                                if (!isset($series[$counter]['data'])) {
                                    $series[$counter]['data'] = array();
                                }
                                $key = $dataRow[$columnData[1]['FIELD_PATH']];
                                $value = $dataRow[$columnData[2]['FIELD_PATH']];
                                $series[$counter]['data'][] = $value;
                                if (!in_array($key, $categoryList)) {
                                    array_push($categoryList, $key);
                                }
//                                array_push($series[$counter]['data'],    array($dataRow[$columnData[1]['FIELD_PATH']] => $dataRow[$columnData[2]['FIELD_PATH']]));
                            }
                        }
                    } else {
                        $series['name'] = $data['result'][0][$columnData[0]['FIELD_PATH']];
                        // collecting data
                        foreach ($data['result'] AS $index => $dataRow) {
                            // collect categories
                            if ($dataRow[$columnData[0]['FIELD_PATH']] != null) {
                                array_push($categoryList, $dataRow[$columnData[1]['FIELD_PATH']]);
                            }

                            // collect series
                            if ($dataRow[$columnData[1]['FIELD_PATH']] != null) {
                                //                            array_push($series, $dataRow[$columnData[1]['FIELD_PATH']]);
                                $series['data'][] = $dataRow[$columnData[2]['FIELD_PATH']];
                            }
                        }
                        $series = '[' . $series . ']';
                    }

//                    $series = array($series);
                    break;
                }
            case 'dualAxes' : {

                    $param = array_merge($param, array(
                        'systemMetaGroupId' => $metaDataId, //$chartValues['PROCESS_META_DATA_ID'], 
                        'showQuery' => 0
                    ));

                    // вебсервис дуудах
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                    if (!isset($data['result'])) {
                        return array('categoryList' => null, 'series' => null);
                    } else {
                        unset($data['result']['aggregatecolumns']);
                        unset($data['result']['paging']);
                    }
                    //get columns
                    $this->load->model('mdobject', 'middleware/models/');
                    $columnData = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
                    // sort columns
                    $columnOrder = $this->getColumnOrder($columnData);
                    $sortList = array();
                    $series = array();
                    foreach ($columnOrder AS $sortKey => $sortValue) {
                        $sortList[$sortKey] = $sortValue['LABEL_NAME'];
                        // Эхний index бол X тэнхлэг бусад багана нь утгууд учир эхний index - г алгасав.
                        if ($sortKey != 0) {
                            $series[$sortKey - 1]['name'] = $sortValue['LABEL_NAME'];
                            // Хоёрдох хэмжээс баруун талд гарах
                            if (($sortKey - 1) == 0) {
                                $series[$sortKey - 1]['yAxis'] = 1;
                            }
                        };
                    }
                    $categoryList = array();
                    $tmpArray = array();
                    $nameArray = array();
                    $nameCounter = 0;
                    $counter = 0;
                    $series[0]['type'] = 'column';
                    $series[1]['type'] = 'spline';
                    $series[0]['data'] = array();
                    $series[1]['data'] = array();
                    // Sort data
                    $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
                    foreach ($data['result'] AS $index => $dataRow) {

                        if (!isset($dataRow[$columnData[1]['FIELD_PATH']])) {
                            continue;
                        }
                        if (!isset($dataRow[$columnData[2]['FIELD_PATH']])) {
                            continue;
                        }

                        if ($series[0] == null) {
                            $series[0] = array();
                        }
                        if ($series[1] == null) {
                            $series[1] = array();
                        }

                        array_push($series[0]['data'], $dataRow[$columnData[1]['FIELD_PATH']]);
                        array_push($series[1]['data'], $dataRow[$columnData[2]['FIELD_PATH']]);

                        if (!in_array($dataRow[$columnData[1]['FIELD_PATH']], $nameArray)) {
                            array_push($nameArray, $dataRow[$columnData[1]['FIELD_PATH']]);
                            // category
                            array_push($categoryList, $dataRow[$columnData[0]['FIELD_PATH']]);
                        }
                    }
                    break;
                }
            default : {
                    break;
                }
        }

        return array('categoryList' => $categoryList, 'series' => $series);
    }

    public function collectDataNew($metaDataId, $type, $isMultiple = 0, $config, $userId = '1', $searchCriteria = null) {
        $categoryList   = array();
        $categoryField  = array();
        $series         = array();
        $param          = array('criteria' => array());
        $error          = null;
        
        $config['yAxis'] = Str::lower($config['yAxis']);
        $config['xAxis'] = Str::lower($config['xAxis']);
        $config['colorField'] = Str::lower(issetDefaultVal($config['colorField'], null));
        $config['xAxisGroup'] = Str::lower($config['xAxisGroup']);
        $config['yAxisGroup'] = Str::lower($config['yAxisGroup']);
        
        if (Input::postCheck('defaultCriteriaData')) {
            parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);
            
            if (isset($defaultCriteriaData['param'])) {
                $defaultCriteriaParam = $defaultCriteriaData['param'];

                if (isset($defaultCriteriaData['criteriaCondition'])) {
                    $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                    $defaultCondition = '1';
                } else {
                    $defaultCriteriaCondition = 'LIKE';
                    $defaultCondition = '0';
                }

                $paramDefaultCriteria = array();

                foreach ($defaultCriteriaParam as $defParam => $defParamVal) {

                    $fieldLower = strtolower($defParam);
                    $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');

                    if (is_array($defParamVal)) {

                        if ($operator == '!=' || $operator == '=' || $operator == 'LIKE') {

                            $defParamVals = Arr::implode_r(',', $defParamVal, true);

                            if ($defParamVals != '') {
                                $paramDefaultCriteria[$fieldLower][] = array(
                                    'operator' => ($operator == '!=' ? 'NOT IN' : 'IN'),
                                    'operand' => $defParamVals
                                );
                            }
                        } else {
                            foreach ($defParamVal as $paramVal) {
                                if ($paramVal != '') {
                                    $paramDefaultCriteria[$fieldLower][] = array(
                                        'operator' => $operator,
                                        'operand' => $paramVal
                                    );
                                }
                            }
                        }

                    } else {

                        $defParamVal = Input::param(trim($defParamVal));
                        $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                        $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';

                        if ($defParamVal != '' || $mandatoryCriteria === '1') {

                            $defParamValue = (strtolower($operator) === 'like') ? '%'.$defParamVal.'%' : $defParamVal; 

                            $getTypeCode = self::getDataViewGridCriteriaRowModel($metaDataId, $defParam);
                            if ($getTypeCode) {
                                $getTypeCodeLower = strtolower($getTypeCode['META_TYPE_CODE']);
                            } else {
                                $getTypeCodeLower = "";
                            }

                            if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                $defParamVal = str_replace(
                                    array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                );

                                $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                $defParamValue = $defParamVal;

                            } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer' || $getTypeCodeLower == 'number') {

                                $defParamVal = Number::decimal($defParamVal);

                                $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                $defParamValue = $defParamVal;

                            } elseif ($getTypeCodeLower == 'bigdecimal') {

                                $defParamVal = Number::decimal($defParamVal);

                            } elseif ($getTypeCodeLower == 'boolean') {

                                $operator = '=';
                                $defParamValue = $defParamVal;
                            }

                            if ($defParam == 'booktypename') {
                                $operator = ($defaultCondition === '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!='); 
                                $defParamValue = $defParamVal;
                            }

                            if ($defParam == 'accountCode' || $defParam == 'filterAccountCode') {
                                $defParamValue = trim(str_replace('_', '', str_replace('_-_', '', $defParamValue)));
                            }

                            if ($operator == 'start') {
                                $operator = 'like';
                                $defParamValue = $defParamValue.'%';
                            } elseif ($operator == 'end') {
                                $operator = 'like';
                                $defParamValue = '%'.$defParamValue;
                            }

                            if ($defParamValue != 'null' && $defParamValue != '') {
                                $paramDefaultCriteria[$fieldLower][] = array(
                                    'operator' => $operator,
                                    'operand' => ($defParamValue) ? $defParamValue : '0'
                                );
                            }
                        }
                    }   
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                } else {
                    $param['criteria'] = $paramDefaultCriteria;
                }
            }
        }
        
        if (!Input::isEmpty('filterJson')) {
                
            $criteria = @json_decode(Str::cp1251_utf8(html_entity_decode($_POST['filterJson'], ENT_QUOTES, 'UTF-8')), true);

            if (is_array($criteria)) {

                foreach ($criteria as $key => $value) {
                    $paramFilter[$key][] = array('operator' => '=', 'operand' => $value);
                }

                if ($paramFilter) {
                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                    } else {
                        $param['criteria'] = $paramFilter;
                    }   
                }
            }
        }
        
        if (Input::isEmpty('workSpaceId') == false && Input::isEmpty('workSpaceParams') == false) {

            $this->load->model('mdwebservice', 'middleware/models/');

            $workSpaceId = Input::numeric('workSpaceId');
            parse_str(Input::post('workSpaceParams'), $workSpaceParamArray);
            $workSpaceParamArray = Arr::changeKeyLower($workSpaceParamArray);

            $getWorkSpaceParamMap = $this->model->getWorkSpaceParamMap($metaDataId, $workSpaceId);

            if ($getWorkSpaceParamMap) {

                $isParam = false;

                foreach ($getWorkSpaceParamMap as $workSpaceParam) {

                    $fieldPath = strtolower($workSpaceParam['FIELD_PATH']);
                    $paramPath = strtolower($workSpaceParam['PARAM_PATH']);

                    if (isset($workSpaceParamArray['workspaceparam'][$fieldPath]) 
                        && $workSpaceParamArray['workspaceparam'][$fieldPath] != '') {

                        $paramDefaultCriteria[$paramPath][] = array(
                            'operator' => '=',
                            'operand' => $workSpaceParamArray['workspaceparam'][$fieldPath]
                        );
                        $isParam = true;

                    } elseif (isset($workSpaceParamArray[$paramPath]) 
                        && $workSpaceParamArray[$paramPath] != '') {

                        $paramDefaultCriteria[$paramPath][] = array(
                            'operator' => '=',
                            'operand' => $workSpaceParamArray[$paramPath]
                        );
                        $isParam = true;
                    }
                }

                if ($isParam) {
                    $param['criteria'] = $paramDefaultCriteria;
                }
            }
        }        
        
        $this->load->model('mddashboard', 'middleware/models/');
        $metaData = $this->model->getMetaDataModel($metaDataId);
        
        $this->load->model('mdobject', 'middleware/models/');
        $columnData         = $this->model->getDataViewGridHeaderDashboardModel($metaDataId);
        
        $columnOrder        = $this->getColumnOrder($columnData);        
        $dataGridOptionData = $this->model->getDVGridOptionsModel($metaDataId);
        
        $columnArray = array();
        foreach($columnOrder AS $k => $v) {  
            $columnArray[$v['FIELD_PATH']] = $v['LABEL_NAME'];
        }        
        
        $searchCriteria = $this->getFilter($searchCriteria);
        $criteria = array();
        
        if (isset($param['criteria'])) {
            $paramCriteria = Arr::changeKeyLower($param['criteria']);

            if (isset($searchCriteria['criteria'])) {
                $searchCriteriaParam = Arr::changeKeyLower($searchCriteria['criteria']);
                $criteria = array_merge($paramCriteria, $searchCriteriaParam);
            } else {
                $criteria = $param['criteria'];
            }
        }
        
        WebService::$isUseReport = true;
        
        if ($metaData['META_TYPE_ID'] === Mdmetadata::$businessProcessMetaTypeId) {
            
            $outputMetaDataId = $this->model->getMetaDataOutputMetaDatIdModel($metaDataId);

            $param = array(
                'systemMetaGroupId' => $outputMetaDataId,
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'criteria' => $criteria
            );
            
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, $metaData['META_DATA_CODE'], $param);
            
        } else {
            
            $param = array(
                'systemMetaGroupId' => $metaDataId,
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'pagingWithoutAggregate' => 1, 
                'criteria' => $criteria
            );
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
        }
        
        if (!isset($data['result'])) { 
            return array('categoryList' => null, 'series' => null, 'error' => $data['text']);
        }
        
        unset($data['result']['aggregatecolumns']); 
        unset($data['result']['paging']); 
       
        $data['result'] = $this->dataSort($data['result'], $dataGridOptionData['SORTNAME'], $dataGridOptionData['SORTORDER']);
         
        $tmpArray = $yTempArray = $series['data'] = $series['xAxisName'] = array();
        $series['yAxisName'] = '';
        
        try {
            switch ($type) {
                case 'am_bar' : 
                case 'd3_sunburst' :  {
                   
                    foreach ($data['result'] AS $index => $dataRow) {
                        
                        $n = 0;
                        
                        if ($config['yAxis'] != null && isset($dataRow[$config['yAxis']]) && $dataRow[$config['yAxis']] != null) {
                            /*if((float) $dataRow[$config['yAxis']] <= 0) {
                                continue;
                            };*/
                            $yaxisValue = is_numeric($dataRow[$config['yAxis']]) ? (float) $dataRow[$config['yAxis']] : $dataRow[$config['yAxis']];      
                            $yaxisName = $config['yAxis'];
                            
                            $n++;
                        }

                        if ($config['xAxis'] != null && isset($dataRow[$config['xAxis']]) && $dataRow[$config['xAxis']] != null) {
                            $xaxisName = $config['xAxis'];
                            $xaxisValue = is_numeric($dataRow[$config['xAxis']]) ? (float) $dataRow[$config['xAxis']] : $dataRow[$config['xAxis']];
                            $n++;
                        }

                        $colorValue = '';
                        if (isset($config['colorField']) && $config['colorField'] != null && $dataRow[$config['colorField']] != null) {
                            $colorName = $config['colorField'];
                            $colorValue = (string) $dataRow[$config['colorField']];
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }
                        
                        if ($n == 2) {

                            $tmpArray = array(
                                $xaxisName => $xaxisValue,
                                $yaxisName => $yaxisValue
                            );
                            if (isset($colorName)) {
                                $tmpArray[$colorName] = $colorValue;
                            }
                            $tmpArray = array_merge($dataRow, $tmpArray);
                            array_push($series['data'], $tmpArray);
                        }
                    }

                    $series['yAxisName'] = $config['yAxis'];

                    if ($config['xAxisGroup'] != null) {
                        $series = $this->collectDataByGroup($data['result'], $config, $series);
                    } else {
                        $series['xAxisName'] = $config['xAxis'];
                    }
                   
                    break;
                }
                case 'am_column' :  {
                    foreach ($data['result'] AS $index => $dataRow) {

                        if ($config['yAxis'] != null && $dataRow[$config['yAxis']] != null) {
                            /*if((float) $dataRow[$config['yAxis']] <= 0) {
                                continue;
                            };*/
                            $yaxisValue = (float) $dataRow[$config['yAxis']];      
                            $yaxisName = $config['yAxis'];
                        }

                        if ($config['xAxis'] != null && $dataRow[$config['xAxis']] != null) {
                            $xaxisName = $config['xAxis'];
                            $xaxisValue = (string) $dataRow[$config['xAxis']];
                        }

                        $colorValue = '';
                        if (isset($config['colorField']) && $config['colorField'] != null && $dataRow[$config['colorField']] != null) {
                            $colorName = $config['colorField'];
                            $colorValue = (string) $dataRow[$config['colorField']];
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue,
                            $yaxisName => $yaxisValue
                        );
                        if (isset($colorName)) {
                            $tmpArray[$colorName] = $colorValue;
                        }
                        $tmpArray = array_merge($dataRow, $tmpArray);
                        array_push($series['data'], $tmpArray);
                    }

                    $series['yAxisName'] = $config['yAxis'];

                    if ($config['xAxisGroup'] != null) {
                        $series = $this->collectDataByGroup($data['result'], $config, $series);
                    } else {
                        $series['xAxisName'] = $config['xAxis'];
                    }
                    break;
                }
                case 'risk_heatmap' :
                case 'pie_charts_bullets' :  {
                    foreach ($data['result'] AS $index => $dataRow) {

                        if ($config['yAxis'] != null && $dataRow[$config['yAxis']] != null) {
                            /*if((float) $dataRow[$config['yAxis']] <= 0) {
                                continue;
                            };*/
                            $yaxisValue = (float) $dataRow[$config['yAxis']];      
                            $yaxisName = $config['yAxis'];
                        }

                        if ($config['xAxis'] != null && $dataRow[$config['xAxis']] != null) {
                            $xaxisName = $config['xAxis'];
                            $xaxisValue = (string) $dataRow[$config['xAxis']];
                        }

                        $colorValue = '';
                        if (isset($config['colorField']) && $config['colorField'] != null && $dataRow[$config['colorField']] != null) {
                            $colorName = $config['colorField'];
                            $colorValue = (string) $dataRow[$config['colorField']];
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue,
                            $yaxisName => $yaxisValue
                        );
                        if (isset($colorName)) {
                            $tmpArray[$colorName] = $colorValue;
                        }
                        $tmpArray = array_merge($tmpArray, $dataRow);
                        array_push($series['data'], $tmpArray);
                    }

                    $series['yAxisName'] = $config['yAxis'];

                    if ($config['xAxisGroup'] != null) {
                        $seriesGroupData = Arr::groupByArray($data['result'], $config['xAxisGroup']);
                        $seriesData = array();
                        foreach ($seriesGroupData as $serKey => $serRow) {
                            $serRow['row']['pie'] = $serRow['rows'];
                            array_push($seriesData, $serRow['row']);
                        }
                        $series['data'] = $seriesData;
                        $series['xAxisName'] = $config['xAxis'];
                    } else {
                        $series['xAxisName'] = $config['xAxis'];
                    }
                    break;
                }
                case 'animated_xy_bubble' :  {
                    foreach ($data['result'] AS $index => $dataRow) {

                        if ($config['yAxis'] != null && $dataRow[$config['yAxis']] != null) {
                            /*if((float) $dataRow[$config['yAxis']] <= 0) {
                                continue;
                            };*/
                            $yaxisValue = (float) $dataRow[$config['yAxis']];      
                            $yaxisName = $config['yAxis'];
                        }

                        if ($config['xAxis'] != null && $dataRow[$config['xAxis']] != null) {
                            $xaxisName = $config['xAxis'];
                            $xaxisValue = (string) $dataRow[$config['xAxis']];
                        }

                        $colorValue = '';
                        if (isset($config['colorField']) && $config['colorField'] != null && $dataRow[$config['colorField']] != null) {
                            $colorName = $config['colorField'];
                            $colorValue = (string) $dataRow[$config['colorField']];
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue,
                            $yaxisName => $yaxisValue
                        );
                        if (isset($colorName)) {
                            $tmpArray[$colorName] = $colorValue;
                        }
                        $tmpArray = array_merge($tmpArray, $dataRow);
                        array_push($series['data'], $tmpArray);
                    }

                    $series['yAxisName'] = $config['yAxis'];

                    if ($config['xAxisGroup'] != null) {
                        $seriesGroupData = Arr::groupByArray($data['result'], $config['xAxisGroup']);
                        $seriesData = array();                        
                        foreach ($seriesGroupData as $serKey => $serRow) {
                            $seriesData[$serRow['row'][$config['xAxisGroup']]] = $serRow['rows'];
                        }
                        $series['data'] = $seriesData;
                        $series['xAxisName'] = $config['xAxis'];
                    } else {
                        $series['xAxisName'] = $config['xAxis'];
                    }
                    break;
                }
                case 'am_threed_cylinder_chart' : {
                    $this->load->model('mddashboard', 'middleware/models/');
                    $metaDashboardLink = $this->model->getMetaDiagramLinkThemeModel($metaDataId);
                    $diagramTheme = Info::getDashboardColorTheme(issetDefaultVal($metaDashboardLink['DIAGRAM_THEME'], '1'));
                    $diagramTheme = explode(' ', $diagramTheme);
                    
                    foreach ($data['result'] AS $index => $dataRow) {
                        
                        $xaxisName = null; 
                        $xaxisValue = null;
                        $yaxisName = null;
                        $yaxisValue = null;
                        
                        if ($config['yAxis'] != null && $dataRow[$config['yAxis']] != null) {
                            if ((float) $dataRow[$config['yAxis']] <= 0) {
                                continue;
                            }
                            $yaxisValue = (float) $dataRow[$config['yAxis']];      
                            $yaxisName = $config['yAxis'];
                        }

                        if ($config['xAxis'] != null && $dataRow[$config['xAxis']] != null) {
                            $xaxisName = $config['xAxis'];
                            $xaxisValue = (string) $dataRow[$config['xAxis']];
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue,
                            $yaxisName => (float) number_format($yaxisValue, 2, '.', ''),
                            'color'    => isset($diagramTheme[$index]) ? $diagramTheme[$index] : ''
                        );
                        $tmpArray = array_merge($dataRow, $tmpArray);
                        
                        array_push($series['data'], $tmpArray);
                    }

                    $series['yAxisName'] = $config['yAxis'];

                    if ($config['xAxisGroup'] != null) {
                        $series = $this->collectDataByGroup($data['result'], $config, $series);
                    } else {
                        $series['xAxisName'] = $config['xAxis'];
                    }
                    
                    break;
                }
                case 'am_serial' :
                    {
                    foreach ($data['result'] AS $index => $dataRow) {

                        $xaxisName = null; 
                        $xaxisValue = null;
                        $yaxisName = null;
                        $yaxisValue = null;

                        if ($config['xAxis'] != null && $dataRow[$config['xAxis']] != null) {
                            $xaxisValue = $dataRow[$config['xAxis']];
                            $xaxisName = $config['xAxis'];
                        }

                        if ($config['yAxis'] != null && $dataRow[$config['yAxis']] != null) {                          
                            if ((float) $dataRow[$config['yAxis']] <= 0) {
                                continue;
                            }
                            $yaxisValue = (float) $dataRow[$config['yAxis']];
                            $yaxisName = $config['yAxis'];
                        }

                        if ($config['xAxisGroup'] != null) {
                            $xaxisGroupArray[] = $dataRow[$config['xAxisGroup']];
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue,
                            $yaxisName => number_format($yaxisValue, 2, '.', ''),
                        );
                        $tmpArray = array_merge($tmpArray, $dataRow);
                        array_push($series['data'], $tmpArray);
                    }

                    $series['xAxisName'] = $config['xAxis'];
                    $series['yAxisName'] = $config['yAxis'];
                    break;
                }
                case 'am_donut' : {
                    
                    $xaxisName = null; 
                    $xaxisValue = null;
                    $yaxisName = null;
                    $yaxisValue = null;
                    $colorsSet = array();
                    
                    foreach ($data['result'] AS $index => $dataRow) {

                        if ($config['yAxis'] != null) {
                            if ($dataRow[$config['yAxis']] != null) {
                                if((float) $dataRow[$config['yAxis']] <= 0) {
                                    continue;
                                }
                                $yaxisValue = (float) $dataRow[$config['yAxis']];
                            }
                            
                            $yaxisName = $config['yAxis'];
                        }

                        if ($config['xAxis'] != null) {
                            if ($dataRow[$config['xAxis']] != null) {
                                $xaxisValue = (string) $dataRow[$config['xAxis']];
                            }
                            
                            $xaxisName = $config['xAxis'];
                        }

                        $tmpArray = array(
                            $xaxisName => isset($xaxisValue) ? $xaxisValue : 0,
                            $yaxisName => isset($yaxisValue) ? number_format($yaxisValue, 2, '.', '') : 0
                        );
                        $tmpArray = array_merge($tmpArray, $dataRow);
                        array_push($series['data'], $tmpArray);
                        
                        if ($config['colorField'] && !isset($colorsSet[$index]) && isset($dataRow[$config['colorField']]) && $dataRow[$config['colorField']]) {
                            $colorsSet[$index] = $dataRow[$config['colorField']];
                        }
                    }

                    $series['yAxisName'] = $config['yAxis'];                    
                    $series['xAxisName'] = $config['xAxis'];

                    break;
                }
                case 'am_funnel' :
                case 'am_threed_funnel' :
                case 'am_pie' : {
                    foreach ($data['result'] AS $index => $dataRow) {
                        
                        if ($config['yAxis'] != null) {
                            if ($dataRow[$config['yAxis']] != null) {
                                if((float) $dataRow[$config['yAxis']] <= 0) {
                                    continue;
                                }
                                $yaxisValue = (float) $dataRow[$config['yAxis']];
                                $yaxisName = $config['yAxis'];
                            }else{
                                $yaxisName = null;
                                $yaxisValue = null;
                            }
                        } else{
                            $yaxisName = null;
                            $yaxisValue = null;
                        }
                        $xaxisName = null;
                        $xaxisValue = null;
                        if ($config['xAxis'] != null) {
                            if ($dataRow[$config['xAxis']] != null) {
                                $xaxisName = $config['xAxis'];
                                $xaxisValue = (string) $dataRow[$config['xAxis']];
                            }
                        } else {
                            $xaxisName = null;
                            $xaxisValue = null;
                        }
                        try {
                            $tmpArray = array(
                                $xaxisName => $xaxisValue,
                                $yaxisName => number_format($yaxisValue, 2, '.', '')
                            );

                            $tmpArray = array_merge($tmpArray, $dataRow);

                            array_push($series['data'], $tmpArray);
                        } catch (Exception $ex) { }
                    }

                    $series['yAxisName'] = $config['yAxis'];                    
                    $series['xAxisName'] = $config['xAxis'];

                    break;
                }
                case 'durarion_onvalue_axis2' : 
                    foreach ($data['result'] AS $index => $dataRow) {
                        $xaxisName = null;
                        $xaxisValue = null;
                        
                        if ($config['xAxis'] != null && $dataRow[$config['xAxis']] != null) {
                            $xaxisName = $config['xAxis'];
                            $xaxisValue = (string) $dataRow[$config['xAxis']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue
                        );
                        $tmpArray = array_merge($tmpArray, $dataRow);

                        $yAxisName = '';
                        if ($config['yAxis'] != null) {
                            $yArray = explode(",", $config['yAxis']);                            
                            foreach($yArray AS $y) {
                                $yaxisValue = $dataRow[$y];
                                $yaxisName = $columnArray[$y].'_'.Str::lower($y);
                                $yTempArray = array(Str::lower($y) => $yaxisValue);                                    
                                $tmpArray = array_merge($tmpArray, $yTempArray);
                                $yAxisName .= $yaxisName.","; 
                            }
                            $yAxisName = substr($yAxisName, 0, -1);
                        }
                        else{
                            $yaxisName = null;
                            $yaxisValue = null;
                        }
                        array_push($series['data'], $tmpArray);
                    }
                    $series['yAxisName'] = isset($yAxisName) ? $yAxisName : '';                    
                    $series['xAxisName'] = $config['xAxis'];
                    break;                
                case 'am_dual' :
                case 'am_bar_axis' : 
                case 'durarion_onvalue_axis' :                         
                case 'multiple_value_axis' : 
                case 'am_radar_chart' : {
                    
                    foreach ($data['result'] AS $index => $dataRow) {
                        $xaxisName = null;
                        $xaxisValue = null;
                        
                        if ($config['xAxis'] != null && isset($dataRow[$config['xAxis']]) && $dataRow[$config['xAxis']] != null) {
                            $xaxisName = $config['xAxis'];
                            $xaxisValue = (string) $dataRow[$config['xAxis']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue
                        );
                        $tmpArray = array_merge($tmpArray, $dataRow);

                        $yAxisName = '';
                        if ($config['yAxis'] != null) {
                            $yArray = explode(",", $config['yAxis']);                            
                            foreach($yArray AS $y) {
                                if (isset($dataRow[$y]) && $dataRow[$y] != null) {
                                    // $yaxisValue = $dataRow[$y];
                                    // $yaxisName = Str::lower($y);
                                    // $yTempArray = array(Str::lower($y) => $yaxisValue);                                    
                                    // $tmpArray = array_merge($tmpArray, $yTempArray);
                                    // $yAxisName .= $yaxisName.","; 
                                    $yaxisValue = $dataRow[$y];
                                    $yaxisName = $columnArray[$y].'_'.Str::lower($y);
                                    $yTempArray = array(Str::lower($y) => $yaxisValue);                                    
                                    $tmpArray = array_merge($tmpArray, $yTempArray);
                                    $yAxisName .= $yaxisName.",";                                     
                                }
                            }
                            $yAxisName = substr($yAxisName, 0, -1);
                        }
                        else{
                            $yaxisName = null;
                            $yaxisValue = null;
                        }
                        array_push($series['data'], $tmpArray);
                    }
                    $series['yAxisName'] = isset($yAxisName) ? $yAxisName : '';                    
                    $series['xAxisName'] = $config['xAxis'];
                    break;
                }
                case 'am_clustered_column_line' : {
                    $series['data'] = array(
                        'result' => $data['result'], 
                        'xAxisName' => $config['xAxis'], 
                        'columnName' => $config['yAxis'], 
                        'lineName' => $config['xAxisGroup'], 
                        'columnArray' => $columnArray
                    );
                    $series['yAxisName'] = $config['yAxis'];
                    break;
                }
                case 'clustered_bar_chart' : 
                case 'clustered_bar_chart_horizontal' : 
                case 'am_3d_stacked_column_chart' :  {
                    foreach ($data['result'] AS $index => $dataRow) {

                        if ($config['xAxis'] != null) {
                            if ($dataRow[$config['xAxis']] != null) {
                                $xaxisName = $config['xAxis'];
                                $xaxisValue = (string) $dataRow[$config['xAxis']];
                            }
                        } else {
                            $xaxisName = null;
                            $xaxisValue = null;
                        }
                        
                        if (issetParam($xaxisName) && issetParam($xaxisValue)) {
                            $tmpArray = array(
                                $xaxisName => $xaxisValue
                            );
                            
                            $tmpArray = array_merge($tmpArray, $dataRow);

                            $yAxisName = '';
                            if ($config['yAxis'] != null) {
                                $yArray = explode(',', $config['yAxis']);                            
                                foreach($yArray AS $y) {
                                    if ($dataRow[$y] != null) {
                                        $yaxisValue = $dataRow[$y];
                                        $yaxisName = $columnArray[$y].'_'.Str::lower($y);
                                        $yTempArray = array(Str::lower($y) => $yaxisValue);                                    
                                        $tmpArray = array_merge($tmpArray, $yTempArray);
                                        $yAxisName .= $yaxisName.","; 
                                    }
                                }
                                $yAxisName = substr($yAxisName, 0, -1);
                            }
                            else{
                                $yaxisName = null;
                                $yaxisValue = null;
                            }
                            array_push($series['data'], $tmpArray);
                        }
                        
                    }
                    $series['yAxisName'] = isset($yAxisName) ? $yAxisName : '';                    
                    $series['xAxisName'] = $config['xAxis'];
                    break;
                }
                case 'am_stacked_bar_chart' :
                case 'variable_radius_radar' :
                case 'percent_stacked_area_chart' :
                case 'am_3d_stacked_column_chart_2' :
                case 'am_reversed' : {
                    
                    if ($config['xAxisGroup'] != null) {
                        
                        $param = array_merge($this->getFilter($searchCriteria), $param, array(
                            'systemMetaGroupId' => $metaDataId,
                            'showQuery' => 1, 
                            'ignorePermission' => 1, 
                            'pagingWithoutAggregate' => 1
                        ));
                        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
                        $row = array();
                        $xAxisUpper = Str::upper($config['xAxis']);
                        $xAxisGroupUpper = Str::upper($config['xAxisGroup']);
                                        
                        if ($data['status'] == 'success') {
                            if (isset($data['result'])) { 
                                $page = 1;
                                $rows = 60;
                                $offset = ($page - 1 ) * $rows;
                                $pivotArray = $rowData = array();
                                $addString = 1;
                                $selectColumn = '';
                                
                                try {
                                    
                                    $this->db->StartTrans();
                                    $this->db->Execute(Ue::createSessionInfo());
                                    
                                    $getData = $this->db->GetAll($data['result']);
                                    
                                    if ($getData) {
                                        
                                        foreach ($getData as $drow) {
                                            
                                            $xAxisUpperField = $drow[$xAxisUpper];
                                            $xAxisGroupUpperField = $drow[$xAxisGroupUpper];
                                            $xAxisGroupUpperFieldVal = Str::upper($xAxisGroupUpperField);
                                            
                                            if (!isset($rowData[$xAxisUpperField . '_' . $xAxisGroupUpperFieldVal])) {
                                                $rowData[$xAxisUpperField . '_' . $xAxisGroupUpperFieldVal] = Arr::changeKeyLower($drow);
                                            }
                                            
                                            if (!in_array($xAxisGroupUpperField, $pivotArray)) {
                                                array_push($pivotArray, $xAxisGroupUpperField);
                                                $addString++;
                                            }
                                        }
                                    }
                                    
                                    if ($config['colorField']) {
                                        $colorFieldUpper = Str::upper($config['colorField']);
                                        $selectColumn = 'MAX('.$colorFieldUpper.') AS '.$colorFieldUpper.', ';
                                    }
                                    
                                    $sql = "  SELECT *
                                              FROM (
                                                  SELECT 
                                                      SUM(". Str::upper($config['yAxis']) .") AS ". Str::upper($config['yAxis']) .", 
                                                      ". (issetParam($config['xAxisGroupOrder']) ? Str::upper(explode(":", $config['xAxisGroupOrder'])[0]) . "," : "") ."
                                                      ". $xAxisUpper .", 
                                                      $selectColumn 
                                                      ". $xAxisGroupUpper ."
                                                  FROM ( ". $data['result'] ." ) 
                                                  GROUP BY ". $xAxisUpper .", " . (issetParam($config['xAxisGroupOrder']) ? Str::upper(explode(":", $config['xAxisGroupOrder'])[0]) . "," : "") . $xAxisGroupUpper ."
                                              ) 
                                              PIVOT (SUM(". $config['yAxis'] .") AS ". $config['yAxis'] ." ".($selectColumn ? ', '.rtrim($selectColumn, ', ') : '')." FOR (". Security::sanitize($config['xAxisGroup']) .") IN (
                                                ";
                                    $checkString = count($pivotArray)-1;
                                    foreach ($pivotArray as $key => $pivotString) {
                                        if ($checkString == $key) {
                                            $sql .= " '". $pivotString ."' AS A$key ";
                                        } else {
                                            $sql .= " '". $pivotString ."' AS A$key, ";
                                        }
                                    }

                                    $sql .="))";
                                    $sql .= issetParam($config['xAxisGroupOrder']) ? " ORDER BY " . Str::upper(explode(":", $config['xAxisGroupOrder'])[0]) . " " . explode(":", $config['xAxisGroupOrder'])[1] : "";
                                    
                                    $rs   = $this->db->SelectLimit($sql, $rows, $offset);
                                    $row = isset($rs->_array) ? $rs->_array : array();
                                    
                                    $this->db->CompleteTrans();
                                    
                                } catch (ADODB_Exception $ex) {
                                }
                            }
                        }

                        if ($row) {
                            
                            $colorsSet = array();
                            
                            foreach ($row AS $index => $dataRow) {
                                if ($config['xAxis'] != null) {
                                    if ($dataRow[$xAxisUpper] != null) {
                                        $xaxisName = $xAxisUpper;
                                        $xaxisValue = $dataRow[$xAxisUpper];
                                    }
                                } else {
                                    $xaxisName = null;
                                    $xaxisValue = null;
                                }

                                $tmpArray = array($xaxisName => $xaxisValue);

                                $yAxisName = '';

                                if ($config['yAxis'] != null) {
                                    $yArray = explode(',', $config['yAxis']);                            
                                    foreach ($pivotArray as $pkey => $pivotString) {
                                        $yaxisValue = (float) $dataRow['A'.$pkey.'_'.Str::upper($config['yAxis'])];
                                        $yaxisName  = Str::upper($pivotString);
                                        $yTempArray = array($yaxisName => $yaxisValue);                                    
                                        $tmpArray = array_merge($tmpArray, $yTempArray);
                                        $yAxisName .= $yaxisName.self::$titleSeparator; 
                                    }
                                    $yAxisName = rtrim($yAxisName, self::$titleSeparator);
                                } else {
                                    $yaxisName = null;
                                    $yaxisValue = null;
                                }

                                if ($config['colorField']) {
                                    foreach ($pivotArray as $pkey => $pivotString) {
                                        
                                        if (isset($dataRow['A'.$pkey.'_'.$colorFieldUpper]) && !isset($colorsSet[$pkey])) {
                                            $colorsSet[$pkey] = $dataRow['A'.$pkey.'_'.$colorFieldUpper];
                                        }
                                    }
                                }
                                
                                array_push($series['data'], $tmpArray);
                            }
                        }

                        $series['yAxisName'] = isset($yAxisName) ? $yAxisName : '';                    
                        $series['xAxisName'] = $xAxisUpper;
                        
                        if (isset($rowData)) {
                            $series['rowData'] = $rowData;
                        }

                    } else {

                        foreach ($data['result'] AS $index => $dataRow) {
                            $xaxisName = $xaxisValue = $yaxisName = $yaxisValue = null;

                            if (isset($config['xAxis']) && $config['xAxis'] != null) {
                                if ($dataRow[$config['xAxis']] != null) {
                                    $xaxisName = $config['xAxis'];
                                    $xaxisValue = (string) $dataRow[$config['xAxis']];
                                }
                            }

                            $tmpArray = array(
                                $xaxisName => $xaxisValue
                            );
                            $tmpArray = array_merge($tmpArray, $dataRow);

                            $yAxisName = '';
                            $yaxisNameConfig = array();
                            
                            if ($config['yAxis'] != null) {
                                $yArray = explode(',', $config['yAxis']); 
                                foreach($yArray AS $y) {
                                    if ($dataRow[$y] != null) {
                                        $yaxisValue = (float) $dataRow[$y];
                                        $yaxisName = $columnArray[$y];
                                        $yTempArray = array($yaxisName => $yaxisValue); 

                                        $tmpArray = array_merge($tmpArray, $yTempArray);
                                        $yAxisName .= $yaxisName.self::$titleSeparator; 
                                        
                                        $yaxisNameConfig[$yaxisName] = $y;
                                    }
                                }
                                $yAxisName = rtrim($yAxisName, self::$titleSeparator);
                            }
                            
                            $tmpArray['yaxisNameConfig'] = $yaxisNameConfig;

                            array_push($series['data'], $tmpArray);
                        }
                        
                        $series['yAxisName'] = isset($yAxisName) ? $yAxisName : '';                    
                        $series['xAxisName'] = $config['xAxis'];
                    }
                    
                    break;
                }
                case 'am_zoomable_value_axis' : {
                    foreach ($data['result'] AS $index => $dataRow) {
                        $xaxisName  = null; 
                        $xaxisValue = null;
                        $yaxisName  = null;
                        $yaxisValue = null;

                        if ($config['xAxis'] != null) {
                            if ($dataRow[$config['xAxis']] != null) {
                                $xaxisValue = $dataRow[$config['xAxis']];
                                $xaxisName = $config['xAxis'];
                            }
                        }

                        if ($config['yAxis'] != null) {
                            if ($dataRow[$config['yAxis']] != null) {                            
                                if((float) $dataRow[$config['yAxis']] <= 0) {
                                    continue;
                                };
                                $yaxisValue = (float) $dataRow[$config['yAxis']];
                                $yaxisName = $config['yAxis'];
                            }
                        }

                        if ($config['xAxisGroup'] != null) {
                            $xaxisGroupArray[] = $dataRow[$config['xAxisGroup']];
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }

                        $tmpArray = array(
                            $xaxisName => $xaxisValue,
                            $yaxisName => $yaxisValue
                        );
                        array_push($series['data'], $tmpArray);
                    }
                    $series['xAxisName'] = $config['xAxis'];
                    $series['yAxisName'] = $config['yAxis'];
                    break;
                }
                case 'am_trend_lines' : {

                    $finalDate    = '';
                    $finalValue   = '';
                    $initialDate  = '';
                    $initialValue = '';
                    $groupIndex = 1;
                    (Array) $xaxisGroupTemp = array();

                    foreach ($data['result'] AS $index => $dataRow) {
                        $xaxisName  = null; 
                        $xaxisValue = null;
                        $yaxisName  = null;
                        $yaxisValue = null;

                        if ($config['xAxis'] != null) {
                            if ($dataRow[$config['xAxis']] != null) {
                                $xaxisValue = $dataRow[$config['xAxis']];
                                $xaxisName = $config['xAxis'];
                            }
                        }

                        if ($config['yAxis'] != null) {
                            if ($dataRow[$config['yAxis']] != null) {                            
                                if((float) $dataRow[$config['yAxis']] <= 0) {
                                    continue;
                                };
                                $yaxisValue = $dataRow[$config['yAxis']];
                                $yaxisName = $config['yAxis'];
                            }
                        }

                        if ($config['xAxisGroup'] != null) {
                          if ($dataRow[$config['xAxisGroup']] != '' || $dataRow[$config['xAxisGroup']] != '0') {
                              if ($groupIndex == 1) {
                                  $finalDate    = $dataRow[$config['xAxis']];
                                  $finalValue   = $dataRow[$config['yAxis']];
                                  $initialDate  = $dataRow[$config['xAxis']];
                                  $initialValue = $dataRow[$config['yAxis']];
                              }
                              if ($finalDate < $dataRow[$config['xAxis']]) {
                                  $finalDate    = $dataRow[$config['xAxis']];
                                  $finalValue   = $dataRow[$config['yAxis']];
                              }
                              $xaxisGroupValue    = array(
                                  'finalDate'     => $finalDate,
                                  'finalValue'    => $finalValue,
                                  'initialDate'   => $initialDate,
                                  'initialValue'  => $initialValue,
                                  'lineColor'     => '#000',
                              );
                              $groupIndex ++;
                          }
                          else {
                              $groupIndex = 1;
                              if (!in_array($xaxisGroupValue, $xaxisGroupTemp))
                                  array_push($xaxisGroupTemp, $xaxisGroupValue);
                          }
                        }

                        if ($config['yAxisGroup'] != null) {
                            $yaxisGroupArray[] = $dataRow[$config['yAxisGroup']];
                        }

                        $tmpArray = array(
                            $xaxisName  => $xaxisValue,
                            $yaxisName  => $yaxisValue,

                        );
                        array_push($series['data'], $tmpArray);
                    }
                    if ($config['xAxisGroup'] != null) {
                        $series['xAxisGroupValue'] = $xaxisGroupTemp;
                    }

                    $series['xAxisName'] = $config['xAxis'];
                    $series['yAxisName'] = $config['yAxis'];
                    break;
                }
                case 'am_angular_gauge' :
                case 'am_cylinder_gauge' : {
                    foreach ($data['result'] AS $index => $dataRow) {

                        if ($config['yAxis'] != null) {
                            if ($dataRow[$config['yAxis']] != null) {
                                if((float) $dataRow[$config['yAxis']] <= 0) {
                                    continue;
                                }
                                $yaxisValue = (float) $dataRow[$config['yAxis']];
                                $yaxisName = $config['yAxis'];
                            }
                        }else{
                            $yaxisName = null;
                            $yaxisValue = null;
                        }
                        $xaxisName = null;
                        $xaxisValue = null;
                        if ($config['xAxis'] != null) {
                            if ($dataRow[$config['xAxis']] != null) {
                                // X тэнхлэг бүлэглэх эсэх
                                $xaxisName = $config['xAxis'];
                                $xaxisValue = (string) $dataRow[$config['xAxis']];
                            }
                        }else{
                            $xaxisName = null;
                            $xaxisValue = null;
                        }

                        $tmpArray = array(
                            'title'    => '',
                            $xaxisName => $xaxisValue,
                            $yaxisName => $yaxisValue
                        );
                        array_push($series['data'], $tmpArray);
                    }

                    $series['yAxisName'] = $config['yAxis'];                    
                    $series['xAxisName'] = $config['xAxis'];

                    break;
                }
                case 'am_combined_bullet' : {
                    foreach ($data['result'] AS $index => $dataRow) {

                        // X тэнхлэг
                        if ($config['xAxis'] != null) {
                            if ($dataRow[$config['xAxis']] != null) {
                                // X тэнхлэг бүлэглэх эсэх
                                $xaxisName = $config['xAxis'];
                                $xaxisValue = (string) $dataRow[$config['xAxis']];
                            }
                        }else{
                            $xaxisName = null;
                            $xaxisValue = null;
                        }

                        // Тухайн мөр бичлэгийг цуглуулж эхэлж бн.
                        $tmpArray = array(
                            $xaxisName => $xaxisValue
                        );

                        // Y тэнхлэг   
                        $yAxisName = "";
                        if ($config['yAxis'] != null) {
                            $yArray = explode(",", $config['yAxis']);                            
                            foreach($yArray AS $y) {
                                if (isset($dataRow[$y]) && $dataRow[$y] != null) {
                                    $yaxisValue = (float) $dataRow[$y];
                                    $yaxisName = $columnArray[$y];
                                    $yTempArray = array($yaxisName => $yaxisValue);                                    
                                    $tmpArray = array_merge($tmpArray, $yTempArray);
                                    // Шинэ нэрээрээ дашбоард дээр гарна.
                                    $yAxisName .= $yaxisName.","; 
                                }
                            }
                            $yAxisName = substr($yAxisName, 0, -1);
                        }else{
                            $yaxisName = null;
                            $yaxisValue = null;
                        }
                        // data цуглуулах
                        array_push($series['data'], $tmpArray);
                    }
                    // Y тэнхлэгийн нэр
                    $series['yAxisName'] = isset($yAxisName) ? $yAxisName : '';                    
                    $series['xAxisName'] = $config['xAxis'];
                    break;
                }
                case 'am_gantt' : {
                    (Array) $xaxisTempArr = $series = array();
                    
                    if ($config['xAxis']) {
                        $xaxisTempArr = explode(',', $config['xAxis']);
                    }
                    
                    if ($data['result'] && $config['yAxis']) {
                        $dataRows = Arr::groupByArrayOnlyRows($data['result'], $config['yAxis']);
                        if ($dataRows) {
                            foreach ($dataRows AS $index => $subRow) {
                                $xaxisName = null;
                                $xaxisValue = null;
                                (Array) $tmpArray = $array_temp = array();
                                
                                foreach ($subRow as $row) {
                                    if ($xaxisTempArr) {
                                        foreach ($xaxisTempArr as $xRow) {
                                            if ($row[$xRow] != null) {
                                                $xaxisName = $xRow;
                                                $xaxisValue = (string) $row[$xRow];
                                            }
                                            $tmpArray[$xaxisName] = $xaxisValue;
                                        }
                                    }
                                    array_push($array_temp, $tmpArray);
                                }
                                
                                $tempArr =  array(
                                                "category" => $index,
                                                "segments" => $array_temp);
                                array_push($series, $tempArr);
                                
                            }
                        }
                    }
                    break;
                }
                default : {
                    break;
                }
            }
        } catch (Exception $ex) {
            return array('categoryList' => array(), 'series' => array(), 'error' => $error, 'categoryField' => array());
        }
        
        $output = array('categoryList' => $categoryList, 'series' => $series, 'error' => $error, 'categoryField' => $categoryField);
        
        if (isset($colorsSet) && $colorsSet) {
            $output['theme'] = implode(' ', $colorsSet);
        }
        
        return $output;
    }
    
    private function collectDataByGroup($data, $config, $series) {
        
        $series['data'] = $series['xAxisName'] = $tmpArray = $cArray = array();
        $lastCategory = '';
        
        foreach ($data AS $index => $dataRow) {
                        
            // Бүлэглэх эсэхээс хамаарч утгаа өөрөөр цуглуулах учир заавал эхлээд Y тэнхлэгийг цуглуулана.
            // Y тэнхлэг 
            if ($config['yAxis'] != null && $dataRow[$config['yAxis']] != null) {
                /*if ((float) $dataRow[$config['yAxis']] <= 0) {
                    continue;
                }*/
                $yaxisValue = (float) $dataRow[$config['yAxis']];                    
                $yaxisName = $config['yAxis'];
            }

            // X тэнхлэг
            if ($config['xAxis'] != null && $dataRow[$config['xAxis']] != null) {
                // Хэрэв X тэнхлэг бүлэглэх бол                    
                if (!in_array((string) $dataRow[$config['xAxis']], $series['xAxisName'])) {
                    array_push($series['xAxisName'], (string) $dataRow[$config['xAxis']]);
                }
                $yaxisValue = (float) $dataRow[$config['yAxis']];

                if (!isset($tmpArray[$dataRow[$config['xAxisGroup']]])) {
                    $tmpArray[$dataRow[$config['xAxisGroup']]] = array();
                }

                if ($dataRow[$config['xAxisGroup']] != $lastCategory) {
                    if (!isset($cArray[$dataRow[$config['xAxisGroup']]])) {
                        $cArray[$dataRow[$config['xAxisGroup']]] = array();
                    }

                    if ($cArray[$dataRow[$config['xAxisGroup']]] == null) {
                        $cArray[$dataRow[$config['xAxisGroup']]] = array();
                    }
                    $cArray[$dataRow[$config['xAxisGroup']]][(string) $dataRow[$config['xAxis']]] = $yaxisValue;
                    $lastCategory = $dataRow[$config['xAxisGroup']];
                    // X тэнхлэгийн групп утга
                    $cArray[$dataRow[$config['xAxisGroup']]][$config['xAxisGroup']] = $dataRow[$config['xAxisGroup']];
                    
                } else {
                    $cArray[$dataRow[$config['xAxisGroup']]][(string) $dataRow[$config['xAxis']]] = $yaxisValue;
                }                    
            }

            // X тэнхлэгийн групп утга            
            $series['xAxisGroupName'] = (string) $config['xAxisGroup'];
        }
        
        foreach ($cArray AS $key => $value) {
            array_push($series['data'], $value);
        }
        
        return $series;
    }

    /** FLOT CHART **************************************************************** */
    
    public function flotChartLineDiagram() {
        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);

        // параметр цуглуулах        
        $param = array(
            'metaGroupId' => $chartValues['PROCESS_META_DATA_ID'], //$chartValues['PROCESS_META_DATA_ID'], 
            'showQuery' => 0
        );
        $series = array();
        // вебсервис дуудах
        WebService::$isUseReport = true;
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
        
        if ($data['status'] == 'success') {
            if (isset($data['result'])) {
                unset($data['result']['aggregatecolumns']); // aggregatecolumns хасаж байна   
                unset($data['result']['paging']); 
                foreach ($data['result'] AS $row) {
                    $counter = 0;
                    foreach ($row AS $k => $v) {
                        if ($v == null || $v == 0) {
                            continue;
                        }

                        if ($counter == 0) {
                            $dataArray['y'] = $v;
                        } else if ($counter == 1) {
                            $dataArray['name'] = $v;
                        }
                        $counter++;
                    }
                    array_push($series, $dataArray);
                }
            }
        } else {
            $data = null;
        }

        $title = $chartValues['PROCESS_META_DATA_ID'];
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];

        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'series' => $series,
            'isLegend' => $isLegend,
            'isExport' => $isExport
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getDualAxes() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = $this->model->getMetaDiagramLinkModel($metaDataId);
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        $dataLabel = $chartValues['IS_DATA_LABEL'];
        $labelStep = $chartValues['LABEL_STEP'];
        $isMultiple = $chartValues['IS_MULTIPLE'];
        $isXLabel = $chartValues['IS_X_LABEL'];
        $isYLabel = $chartValues['IS_Y_LABEL'];
        $isBackground = $chartValues['IS_BACKGROUND'];

        $collectData = $this->collectData($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple);
        $categoryList = $collectData['categoryList'];
        $series = $collectData['series'];

        $mainArray = array(
            'chartType' => $chartType,
            'isTitle' => $isTitle,
            'title' => $chartTitle,
            'width' => $width,
            'height' => $height,
            'categories' => $categoryList,
            'series' => $series,
            'isLegend' => $isLegend,
            'isExport' => $isExport,
            'dataLabel' => $dataLabel,
            'labelStep' => $labelStep,
            'isXLabel' => $isXLabel,
            'isYLabel' => $isYLabel,
            'isBackground' => $isBackground,
            'DRILLDOWN' => $chartValues['DRILLDOWN'],
            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
        );
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getColumnsAjax() {
        $dataViewId = Input::post('dataViewId');
        if (!is_null($dataViewId)) {
            $metaTypeId = $this->db->GetOne("SELECT  META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = $dataViewId");
            //get columns
            if (Mdmetadata::$businessProcessMetaTypeId === $metaTypeId || Mdmetadata::$expressionMetaTypeId === $metaTypeId) {
                $this->load->model('mddashboard', 'middleware/models/');
                $columnData = $this->model->getBusinessProcessMetadataModel($dataViewId);
            } else {
                $this->load->model('mdobject', 'middleware/models/');
                $columnData = $this->model->getDataViewGridHeaderDashboardModel($dataViewId, '1 = 1', 3);
            }

            // sort columns
            $columnOrder = $this->getColumnOrder($columnData);

            echo json_encode($columnOrder, JSON_NUMERIC_CHECK);
        } else {
            echo json_encode(array());
        }
    }

    private function getFilter($searchCriteria = null) {
        $param = array();
        // FILTER
        if (Input::postCheck('defaultCriteriaData')) {
            
            parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);
            
            if (isset($defaultCriteriaData['param'])) {
                  
                $defaultCriteriaParam = $defaultCriteriaData['param'];
                $defaultCriteriaOperator = isset($defaultCriteriaData['criteriaOperator']) ? $defaultCriteriaData['criteriaOperator'] : '=';

                $paramDefaultCriteria = array();

                foreach ($defaultCriteriaParam as $defParam => $defParamVal) {
                    if (!is_array($defParamVal)) {
                        $defParamVal = Input::param(Str::lower(trim($defParamVal)));
                    } 

                    if ($defParamVal != "") {

                        if (!is_array($defParamVal)) {
                            $pos = strpos($defParamVal, ".");
                            if ($pos != false) {
                                $defParamVal = str_replace(",", "", $defParamVal);
                            }
                        } else {
                            for ($i = 0; $i < count($defParamVal); $i++) {
                                if ($defParamVal[$i] != '') {
                                    $pos = strpos($defParamVal[$i], ".");
                                    if ($pos != false) {
                                        $defParamVal[$i] = str_replace(",", "", $defParamVal[$i]);
                                    }
                                } else {
                                    unset($defParamVal[$i]);
                                }
                            }
							
                            if (count($defParamVal) == 1) {
                                $defParamVal = isset($defParamVal[0]) ? $defParamVal[0] : $defParamVal[1];
                            }
                        }

                        if (isset($defaultCriteriaData['criteriaOperator'])) {
                            if ($defaultCriteriaOperator[$defParam] == 'BETWEEN') {

                                $paramDefaultCriteria[$defParam][] = array(
                                    'operator' => $defaultCriteriaOperator[$defParam],
                                    'operand' => "'" . $defParamVal[0] . isset($defParamVal[1]) ? "' AND '" . $defParamVal[1] . "'" : ''
                                );
                            } else {
                                
                                if (is_array($defParamVal)) {
                                    $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                    if ($defParamVals != '') {
                                        $paramDefaultCriteria[$defParam][] = array(
                                            'operator' => ($defaultCriteriaOperator[$defParam] == '!=' ? 'NOT IN' : 'IN'),
                                            'operand' => $defParamVals
                                        );
                                    }
                                } else {
                                    $paramDefaultCriteria[$defParam][] = array(
                                        'operator' => $defaultCriteriaOperator[$defParam],
                                        'operand' => $defParamVal
                                    );
                                }
                            }

                        } else {
                            
                            if (is_array($defParamVal)) {
                                $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                if ($defParamVals != '') {
                                    $paramDefaultCriteria[$defParam][] = array(
                                        'operator' => ($defaultCriteriaOperator == '!=' ? 'NOT IN' : 'IN'),
                                        'operand' => $defParamVals
                                    );
                                }
                            } else {
                                $paramDefaultCriteria[$defParam][] = array(
                                    'operator' => $defaultCriteriaOperator,
                                    'operand' => $defParamVal
                                );
                            }
                        }                        
                    }
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                } else {
                    $param['criteria'] = $paramDefaultCriteria;
                }
            }
        }
        if ($searchCriteria) {
            if (isset($param['criteria'])) {
                $param['criteria'] = array_merge($param['criteria'], $searchCriteria);
            } else {
                $param['criteria'] = $searchCriteria;
            }
        }

        return $param;
    }
    
    private function dataSort($data, $sortName, $sortOrder) {
        $item = array();
        
        foreach ($data as $key => $value) {
            $param = array();
            
            if (!empty($value)) {
                $param[Str::lower($key)] = $value;
                array_push($item, $value);
            }
        }
        $data = $item;

        // Sort хийх талбар байхгүй бол sort хийхгүй
        if ($sortName == null) {
            return $data;
        }
        // Sort хийх дараалал байхгүй бол sort хийхгүй
        if ($sortOrder == null) {
            return $data;
        }
        
        $cleanArray = array();
        $toSortArray = array();
        
        foreach ($data as $key => $value) {
            $sortNameLower = strtolower($sortName);
            if (array_key_exists($sortNameLower, $value)) {
                $toSortArray[$key] = $value[$sortNameLower];
            }
        }
        
        // Өсөх бол
        if (strtolower($sortOrder) == 'asc') {
            asort($toSortArray);
        } else {// Буурах бол
            rsort($toSortArray);
        }
        
        foreach ($toSortArray as $key => $value) {
            $cleanArray[] = $data[$key];
        }
        
        return $cleanArray;
    }
    
    public function render($metaDataId = '') {
        $this->load->model('mddashboard', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->js = AssetNew::highchartJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->fullUrlCss = AssetNew::amChartCss();
        
        $this->view->metaDataId = ($metaDataId != '' ? Input::param($metaDataId) : Input::numeric('metaDataId'));

        $this->view->diagram = $this->model->getMetaDiagramLinkModel($this->view->metaDataId);
            
        if ($this->view->diagram['PROCESS_META_DATA_ID'] != null) { // Хэрэв тухайн dashboard нь вебсервис дуудах шаардлагатай бол

            $this->view->diagram['TEXT'] = $this->callProcessWebservice($this->view->diagram['PROCESS_META_DATA_ID']);

            // criteria
            $this->load->model('mdobject', 'middleware/models/');
            $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($this->view->diagram['PROCESS_META_DATA_ID']);
            $this->view->defaultCriteria = $this->view->renderPrint('defaultCriteria', self::$viewPath);
        } else {
            $this->view->diagram['TEXT'] = null;
            $this->view->dataViewHeaderData = null;
        }

        if ($this->view->diagram['DASHBOARD_TYPE'] == 'flot') {
            $renderPage = 'flotchart/renderDashboard';
        } else if ($this->view->diagram['DASHBOARD_TYPE'] == 'amchart') {
            switch ($this->view->diagram['DIAGRAM_TYPE']) {
                case "am_serial" : {
                        $renderPage = 'amcharts/column';
                        break;
                    }
                case "am_column" : {
                        $renderPage = 'amcharts/column';
                        break;
                    }
                case "am_threed_cylinder_chart" : {
                        $renderPage = 'amcharts/column';
                        break;
                    }
                case "am_bar" : {
                        $renderPage = 'amcharts/column';
                        break;
                    }
                default : {
                        $renderPage = 'amcharts/column';
                        break;
                    }
            }
        } else {
            $renderPage = 'renderDashboard';
        }
            
        $this->view->render($renderPage, self::$viewPath.'render/');
    }
    
    public function getSubDataForAmchart() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $mainArray = array();
        $check = false;
        $error = null;
        $metaDataId = Input::post('metaDataId');
        $drillField = Input::post('drillField');
        
        if (strpos($metaDataId, '_') !== false) {
            $metaDataIdArr = explode('_', $metaDataId);
            $metaDataId = (int) $metaDataIdArr[0];
        } else {
            $metaDataId = (int) $metaDataId;
        }
        
        $cchartValues = $this->model->getMetaSubDiagramLinkModel($metaDataId, $drillField);
        
        switch (Str::lower($cchartValues['META_TYPE_NAME'])) {
            case 'metagroup':
                $subCriteriaData  = Arr::changeKeyLower($_POST['subCriteriaData']);
                $buildCriteria    = $this->model->buildCriteriaModel($cchartValues['DM_DRILLDOWN_DTL_ID']);
                $searchCriteriaQuery = $drillCriteria = array();
                
                if ($buildCriteria) {
                    $rowData = Input::post('rowData');
                    
                    foreach ($buildCriteria as $criteria) {
                        $criteria['TRG_PARAM'] = strtolower($criteria['TRG_PARAM']);
                        $array[$criteria['TRG_PARAM']] = array();
                        $arrayTemp = array();
                        if (isset($subCriteriaData[Str::lower($criteria['SRC_PARAM'])])) {
                            $arrayTemp = array(
                                'operator' => '=',
                                'operand' => Input::param($subCriteriaData[Str::lower($criteria['SRC_PARAM'])])
                            );
                            array_push($array[$criteria['TRG_PARAM']], $arrayTemp);
                            $searchCriteria[$criteria['TRG_PARAM']] = $array[$criteria['TRG_PARAM']];
                            $searchCriteriaQuery[$criteria['TRG_PARAM']] = $subCriteriaData[Str::lower($criteria['SRC_PARAM'])];
                            $drillCriteria[$criteria['TRG_PARAM']] = $arrayTemp['operand'];
                        } elseif (is_array($rowData) && isset($rowData[$criteria['TRG_PARAM']])) {
                            $searchCriteria[$criteria['TRG_PARAM']][] = array(
                                'operator' => '=',
                                'operand' => Input::param($rowData[$criteria['TRG_PARAM']])
                            );
                        }
                    }

                    if (Input::postCheck('defaultCriteriaData') && Input::isEmpty('defaultCriteriaData') === false) {
            
                        parse_str(Input::post('defaultCriteriaData'), $postParam);
                        
                        foreach ($postParam['criteriaOperator'] as $pp => $pv) {

                            $array[$pp] = array();
                            $arrayTemp = array();
                            
                            if (isset($postParam['param'][$pp])) {
                                $arrayTemp = array(
                                    'operator' => $pv,
                                    'operand' => $postParam['param'][$pp],
                                );
                                array_push($array[$pp], $arrayTemp);
                                $searchCriteria[$pp] = $array[$pp];
                                
                                $drillCriteria[$criteria['TRG_PARAM']] = $arrayTemp['operand'];
                            }                            
                        }
                    }
                }
                
                $this->load->model('mdobject', 'middleware/models/');

                if (isset($searchCriteria)) {
                    $_POST['dashboardDrillDownCriteria'] = $searchCriteria;
                }

                if ($cchartValues['SHOW_TYPE'] === 'tab') {
                    $_POST['uriParams'] = json_encode($searchCriteriaQuery, JSON_UNESCAPED_UNICODE);
                    $_POST['isNeedTitle'] = '1';
                    $dataViewForm = (new Mdobject())->dataview($cchartValues['LINK_META_DATA_ID'], false, 'array');
                    $dataViewForm = $dataViewForm['Html'];
                } else {
                    $dataViewForm = (new Mdobject())->detailDataViewer($cchartValues['LINK_META_DATA_ID'], null, null, null, true, true);
                }
                
                echo json_encode(array(
                    'status' => 'success', 
                    'metaType' => Str::lower($cchartValues['META_TYPE_NAME']), 
                    'data' => $dataViewForm, 
                    'showType' => $cchartValues['SHOW_TYPE'], 
                    'listName' => Lang::line($cchartValues['LIST_NAME']), 
                    'error' => '', 
                    'linkMetaDataId' => $cchartValues['LINK_META_DATA_ID'], 
                    'drillCriteria' => $drillCriteria
                ), JSON_UNESCAPED_UNICODE);
                
                break;
            case 'diagram':
            case 'dashboard diagram':
                $chartValues = $this->model->getMetaDiagramLinkModel($cchartValues['LINK_META_DATA_ID']);
                $chartType = $chartValues['DIAGRAM_TYPE'];
                $width = $chartValues['WIDTH'];
                $height = $chartValues['HEIGHT'];
                $chartTitle = $chartValues['TITLE'];
                $isTitle = $chartValues['IS_SHOW_TITLE'];
                $isExport = $chartValues['IS_SHOW_EXPORT'];
                $isLegend = $chartValues['IS_SHOW_LABEL'];
                $dataLabel = $chartValues['IS_DATA_LABEL'];
                $labelStep = $chartValues['LABEL_STEP'];
                $isMultiple = $chartValues['IS_MULTIPLE'];
                $isXLabel = $chartValues['IS_X_LABEL'];
                $isYLabel = $chartValues['IS_Y_LABEL'];
                $isBackground = $chartValues['IS_BACKGROUND'];
                $isLittle = $chartValues['IS_LITTLE'];
                $xLabelRotation = $chartValues['X_LABEL_ROTATION'];
                if ($chartValues['PROCESS_META_DATA_ID'] == null) {
                    $mainArray = array(
                        'status' => 'error',
                        'error' => null
                    );
                } else {
                    if ($cchartValues['DM_DRILLDOWN_DTL_ID']) {
                        
                        $rowData = Input::post('rowData');
                        $subCriteriaData  = Input::post('subCriteriaData');
                        $buildCriteria    = $this->model->buildCriteriaModel($cchartValues['DM_DRILLDOWN_DTL_ID']);                        
                        $rules = $cchartValues['CRITERIA'];
                        
                        if ($rowData) {
                            $subCriteriaData = array_merge($subCriteriaData, $rowData);
                        }
                        
                        foreach ($subCriteriaData as $sk => $sv) {
                            
                            if (!is_array($sv)) {
                                if (is_string($sv) && strpos($sv, "'") === false) {
                                    $sv = "'".Str::lower($sv)."'";
                                } elseif (is_null($sv)) {
                                    $sv = "''";
                                } 

                                $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                            }
                        }

                        $rules = Mdmetadata::defaultKeywordReplacer($rules);
                        $rules = Mdmetadata::criteriaMethodReplacer($rules);                        
                        if ($rules != '' && !eval(sprintf('return (%s);', $rules))) {
                            $mainArray = array(
                                'status' => 'error',            
                                'message' => 'Нөхцөл тохирохгүй байна.<br>Шалгасан нөхцөл: <strong>'.$cchartValues['CRITERIA'].'</strong>'
                            );         
                            header('Content-Type: application/json');
                            echo json_encode($mainArray); return;                            
                        }
                        $searchCriteria = array();
                        if ($buildCriteria) {
                            foreach ($buildCriteria as $criteria) {
                                $array[$criteria['TRG_PARAM']] = array();
                                $arrayTemp = array();
                                $subCriteriaData = Arr::changeKeyLower($subCriteriaData);
                                
                                if (isset($subCriteriaData[Str::lower($criteria['SRC_PARAM'])])) {
                                    $arrayTemp = array(
                                        'operator' => '=',
                                        'operand' => $subCriteriaData[Str::lower($criteria['SRC_PARAM'])],
                                    );
                                    array_push($array[$criteria['TRG_PARAM']], $arrayTemp);
                                    $searchCriteria[$criteria['TRG_PARAM']] = $array[$criteria['TRG_PARAM']];
                                }
                            }
                        }
                        
                        $config = array('xAxis' => $chartValues['XAXIS'], 'yAxis' => $chartValues['YAXIS'], 'xAxisGroup' => $chartValues['XAXISGROUP'], 'yAxisGroup' => $chartValues['YAXISGROUP']);
                        $collectData = $this->collectDataNew($chartValues['PROCESS_META_DATA_ID'], $chartValues['DIAGRAM_TYPE'], $isMultiple, $config, $userId = null, $searchCriteria);
                        
                        $this->load->model('mdobject', 'middleware/models/');
                        $this->view->metaDataId = $cchartValues['LINK_META_DATA_ID'];
                        $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($chartValues['PROCESS_META_DATA_ID']);
                        $defaultCriteria = $this->view->renderPrint('defaultCriteria', self::$viewPath);

                        $categoryList = $collectData['categoryList'];
                        $series = $collectData['series'];
                        $error = $collectData['error'];
                        
                        $mainArray = array(
                            'chartType' => $chartType,
                            'linkedMetaDataId' => $cchartValues['LINK_META_DATA_ID'],
                            'defaultCriteria' => $defaultCriteria,
                            'isTitle' => $isTitle,
                            'title' => $chartTitle,
                            'width' => $width,
                            'height' => $height,
                            'categories' => $categoryList,
                            'series' => $series,
                            'isLegend' => $isLegend,
                            'isExport' => $isExport,
                            'dataLabel' => $dataLabel,
                            'labelStep' => $labelStep,
                            'isXLabel' => $isXLabel,
                            'isYLabel' => $isYLabel,
                            'isBackground' => $isBackground,
                            'isLittle' => $isLittle,
                            'xLabelRotation' => $xLabelRotation,            
                            'error' => $error,
                            'DRILLDOWN' => $chartValues['DRILLDOWN'],
                            'theme' => Info::getDashboardColorTheme($chartValues['DIAGRAM_THEME']),
                            'legendPosition'    => $chartValues['LEGEND_POSITION'], 
                            'addonSettings'     => json_decode($chartValues['ADDON_SETTINGS']), 
                            'labelTextSubStr'   => empty($chartValues['LABEL_TEXT_SUBSTR']) ? '10000' : $chartValues['LABEL_TEXT_SUBSTR'],
                            'realLegendPosition'=> $chartValues['REAL_LEGEND_POSITION'],
                            'status' => 'success',
                            'valueAxisTitle'    => Lang::line($chartValues['VALUE_AXIS_TITLE']), 
                            'categoryAxisTitle' => Lang::line($chartValues['CATEGORY_AXIS_TITLE']), 
                            'metaType' => Str::lower($cchartValues['META_TYPE_NAME'])
                        );
                        
                    } else {
                        $mainArray = array(
                            'status' => 'error',            
                            'error' => null
                        );
                    }
                }

                header('Content-Type: application/json');
                echo json_encode($mainArray, JSON_UNESCAPED_UNICODE);

                break;
        }
    }
    
    public function getDashboardType() {
        echo json_encode(Info::getDiagramType(Input::post('type')));
    }
    
    public function dashboardValueViewer() {
        $response = array('status' => 'error');
        $this->load->model('mddashboard', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isDelete = true;

        $this->view->isControl = true;
        $this->view->isBack = false;

        $this->view->folderId = null;
        $this->view->rowId = null;
        $this->view->rowType = null;
        $this->view->params = null;
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->dashboardList = $this->model->getDashboardListModel($this->view->metaDataId);
        
        $response = array(
            'status' => 'success',
            'Html' => $this->view->renderPrint('view/dashboardList', self::$viewPath),
        );
        echo json_encode($response);
    }
    
    public function getDiagramTypeByRenderMethod($diagram) {
        
        $returnMethod = '';
        
        if ($diagram['DASHBOARD_TYPE'] == 'flot') {
            
            $returnMethod = 'flotchart/renderDashboard';
            
        } elseif ($diagram['DASHBOARD_TYPE'] == 'custom') {
            
            WebService::$isUseReport = true;
            
            if ($diagram['DIAGRAM_TYPE'] == 'custom_water_gauge') {
                
                $this->load->model('mdobject', 'middleware/models/');
                $this->view->columnData = $this->model->getDataViewGridHeaderDashboardModel($diagram['PROCESS_META_DATA_ID']);

                $param = array(
                    'systemMetaGroupId' => $diagram['PROCESS_META_DATA_ID'],
                    'showQuery' => 0
                );
                $this->view->data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);

                $returnMethod = 'custom/renderDashboard';
                
            } elseif($diagram['DIAGRAM_TYPE'] == 'custom_group_table_view') {
                
                $this->load->model('mdobject', 'middleware/models/');
                $this->view->columnData = $this->model->getDataViewGridHeaderDashboardModel($diagram['PROCESS_META_DATA_ID']);

                $param = array(
                    'systemMetaGroupId' => $diagram['PROCESS_META_DATA_ID'],
                    'showQuery' => 0
                );
                
                $metaDataId = $diagram['PROCESS_META_DATA_ID'];
                
                if (Input::postCheck('defaultCriteriaData')) {
                    
                    parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);

                    if (isset($defaultCriteriaData['param'])) {
                        
                        $defaultCriteriaParam = $defaultCriteriaData['param'];

                        if (isset($defaultCriteriaData['criteriaCondition'])) {
                            $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                            $defaultCondition = '1';
                        } else {
                            $defaultCriteriaCondition = 'LIKE';
                            $defaultCondition = '0';
                        }

                        $paramDefaultCriteria = array();

                        foreach ($defaultCriteriaParam as $defParam => $defParamVal) {

                            $fieldLower = strtolower($defParam);
                            $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');

                            if (is_array($defParamVal)) {

                                if ($operator == '!=' || $operator == '=') {

                                    $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                    if ($defParamVals != '') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => ($operator == '!=' ? 'NOT IN' : 'IN'),
                                            'operand' => $defParamVals
                                        );
                                    }
                                } else {
                                    foreach ($defParamVal as $paramVal) {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => $paramVal
                                        );
                                    }
                                }

                            } else {

                                $defParamVal = Input::param(trim($defParamVal));
                                $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                                $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';

                                if ($defParamVal != '' || $mandatoryCriteria === '1') {

                                    $defParamValue = (strtolower($operator) === 'like') ? '%'.$defParamVal.'%' : $defParamVal; 

                                    $getTypeCode = self::getDataViewGridCriteriaRowModel($metaDataId, $defParam);
                                    $getTypeCodeLower = strtolower($getTypeCode['META_TYPE_CODE']);

                                    if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                        $defParamVal = str_replace(
                                            array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                        );

                                        $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                        $defParamValue = $defParamVal;

                                    } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer' || $getTypeCodeLower == 'number') {

                                        $defParamVal = Number::decimal($defParamVal);

                                        $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                        $defParamValue = $defParamVal;

                                    } elseif ($getTypeCodeLower == 'bigdecimal') {

                                        $defParamVal = Number::decimal($defParamVal);

                                    } elseif ($getTypeCodeLower == 'boolean') {

                                        $operator = '=';
                                        $defParamValue = $defParamVal;
                                    }

                                    if ($defParam == 'booktypename') {
                                        $operator = ($defaultCondition === '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!='); 
                                        $defParamValue = $defParamVal;
                                    }

                                    if ($defParam == 'accountCode' || $defParam == 'filterAccountCode') {
                                        $defParamValue = trim(str_replace('_', '', str_replace('_-_', '', $defParamValue)));
                                    }

                                    if ($operator == 'start') {
                                        $operator = 'like';
                                        $defParamValue = $defParamValue.'%';
                                    } elseif ($operator == 'end') {
                                        $operator = 'like';
                                        $defParamValue = '%'.$defParamValue;
                                    }

                                    if ($defParamValue != 'null') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => ($defParamValue) ? $defParamValue : '0'
                                        );
                                    }
                                }
                            }   
                        }

                        if (isset($param['criteria'])) {
                            $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                        } else {
                            $param['criteria'] = $paramDefaultCriteria;
                        }
                    }
                }                
                
                $this->view->data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);

                $returnMethod = 'custom/renderDashboardTableGroupView';
            } elseif($diagram['DIAGRAM_TYPE'] == 'custom_table_view') {
                
                $this->load->model('mdobject', 'middleware/models/');
                $this->view->columnData = $this->model->getDataViewGridHeaderDashboardModel($diagram['PROCESS_META_DATA_ID']);

                $param = array(
                    'systemMetaGroupId' => $diagram['PROCESS_META_DATA_ID'],
                    'showQuery' => 0,
                    'ignorePermission' => 1
                );
                
                $metaDataId = $diagram['PROCESS_META_DATA_ID'];
                
                if (Input::postCheck('defaultCriteriaData')) {
                    
                    parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);

                    if (isset($defaultCriteriaData['param'])) {
                        
                        $defaultCriteriaParam = $defaultCriteriaData['param'];

                        if (isset($defaultCriteriaData['criteriaCondition'])) {
                            $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                            $defaultCondition = '1';
                        } else {
                            $defaultCriteriaCondition = 'LIKE';
                            $defaultCondition = '0';
                        }

                        $paramDefaultCriteria = array();

                        foreach ($defaultCriteriaParam as $defParam => $defParamVal) {

                            $fieldLower = strtolower($defParam);
                            $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');

                            if (is_array($defParamVal)) {

                                if ($operator == '!=' || $operator == '=') {

                                    $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                    if ($defParamVals != '') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => ($operator == '!=' ? 'NOT IN' : 'IN'),
                                            'operand' => $defParamVals
                                        );
                                    }
                                } else {
                                    foreach ($defParamVal as $paramVal) {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => $paramVal
                                        );
                                    }
                                }

                            } else {

                                $defParamVal = Input::param(trim($defParamVal));
                                $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                                $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';

                                if ($defParamVal != '' || $mandatoryCriteria === '1') {

                                    $defParamValue = (strtolower($operator) === 'like') ? '%'.$defParamVal.'%' : $defParamVal; 

                                    $getTypeCode = self::getDataViewGridCriteriaRowModel($metaDataId, $defParam);
                                    $getTypeCodeLower = strtolower($getTypeCode['META_TYPE_CODE']);

                                    if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                        $defParamVal = str_replace(
                                            array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                        );

                                        $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                        $defParamValue = $defParamVal;

                                    } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer' || $getTypeCodeLower == 'number') {

                                        $defParamVal = Number::decimal($defParamVal);

                                        $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                        $defParamValue = $defParamVal;

                                    } elseif ($getTypeCodeLower == 'bigdecimal') {

                                        $defParamVal = Number::decimal($defParamVal);

                                    } elseif ($getTypeCodeLower == 'boolean') {

                                        $operator = '=';
                                        $defParamValue = $defParamVal;
                                    }

                                    if ($defParam == 'booktypename') {
                                        $operator = ($defaultCondition === '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!='); 
                                        $defParamValue = $defParamVal;
                                    }

                                    if ($defParam == 'accountCode' || $defParam == 'filterAccountCode') {
                                        $defParamValue = trim(str_replace('_', '', str_replace('_-_', '', $defParamValue)));
                                    }

                                    if ($operator == 'start') {
                                        $operator = 'like';
                                        $defParamValue = $defParamValue.'%';
                                    } elseif ($operator == 'end') {
                                        $operator = 'like';
                                        $defParamValue = '%'.$defParamValue;
                                    }

                                    if ($defParamValue != 'null') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => ($defParamValue) ? $defParamValue : '0'
                                        );
                                    }
                                }
                            }   
                        }

                        if (isset($param['criteria'])) {
                            $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                        } else {
                            $param['criteria'] = $paramDefaultCriteria;
                        }
                    }
                }                
                
                $this->view->data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);

                $returnMethod = 'custom/renderDashboardTableView';
            }
            
        } elseif ($diagram['DASHBOARD_TYPE'] == 'd3_sunburst') {
           
            switch ($diagram['DIAGRAM_TYPE']) {
                case "d3_sunburst" : 
                default : {
                    $returnMethod = 'd3/sunburstchart';
                    break;
                }
            }
            
        } else {
            
            if ($diagram['DASHBOARD_TYPE'] == 'amchart') {
                
                switch ($diagram['DIAGRAM_TYPE']) {
                    case "am_serial" : 
                    case "am_column" : 
                    case "am_threed_cylinder_chart" : 
                    case "am_bar" : 
                    default : {
                        $returnMethod = 'amcharts/column';
                        break;
                    }
                }
                
            } else {
                
                if ($diagram['DASHBOARD_TYPE'] === 'card') {
                    
                    $this->load->model('mddashboard', 'middleware/models/');
                    $this->view->dashboardParamMap = $this->model->getDashboardParamMapModel($diagram['PROCESS_META_DATA_ID'], Str::lower($diagram['XAXIS']), Str::lower($diagram['YAXIS']), $diagram['DIAGRAM_TYPE'], $diagram['IS_MULTIPLE_PROCESS'], $diagram['PROCESS_META_DATA_ID2'], $diagram['PROCESS_META_DATA_ID3'], $diagram['PROCESS_META_DATA_ID4']);
        
                    self::extraExtensions('card');
                    $searchReplace = array(
                        '{dashboard-id}',
                        '{hidden-params}'
                    );
                    $replaced = array(
                        $diagram['META_DATA_ID'],
                        ''
                    );
                    $tmpObject = new Mddashboard();
                    $i = 1;
                    
                    foreach ($this->view->dashboardParamMap as $k => $row) {
                        array_push($searchReplace, '{data-position-' . $i++ . '}');
                        array_push($replaced, '<div class="layout-fill" id="layout-' . $k . '">'. $row .'</div>');
                    }
                    
                    $workSpaceContent = file_get_contents(BASEPATH . 'middleware/views/dashboard/theme/theme.html');
                    $this->view->replacedLayoutHtml = str_replace($searchReplace, $replaced, $workSpaceContent);
                    str_replace(array("layout-1", "layout-2"), array("layout 1 html", "layout 2 html"), $workSpaceContent);
                    $returnMethod = 'dashboardCardRender';
                    
                } else {
                    $returnMethod = 'renderDashboard';
                }
            }
        }
        
        return $returnMethod;
    }
    
    public function getDiagramProcessMetaDataIdByDiagram($metaDataId) {
        if ($this->view->diagram['PROCESS_META_DATA_ID'] != null) { // Хэрэв тухайн dashboard нь вебсервис дуудах шаардлагатай бол

            $this->view->diagram['TEXT'] = $this->callProcessWebservice($this->view->diagram['PROCESS_META_DATA_ID']);

            // criteria
            $this->load->model('mdobject', 'middleware/models/');
            $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($this->view->diagram['PROCESS_META_DATA_ID']);
            
            if (Input::postCheck('defaultCriteriaData')) {
                parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);

                if (isset($defaultCriteriaData['param'])) {
                    
                    $this->view->fillPath = array();
                    $defaultCriteriaParam = $defaultCriteriaData['param'];
                    
                    foreach ($defaultCriteriaParam as $key => $val) {
                        $this->view->fillPath[strtolower($key)] = $val;
                    }
                }
            }
            
            if ($getAddonSettings = issetParam($this->view->diagram['ADDON_SETTINGS'])) {
                $getAddonSettings = json_decode($getAddonSettings, true);
                if (issetParam($getAddonSettings['criteriaPosition']) == 'topFilterButton') {
                    $this->view->isFilterButton = true;
                }
            }
            
            $this->view->defaultCriteria = $this->view->renderPrint('defaultCriteria', self::$viewPath);
        } 
        else {
            $this->view->diagram['TEXT'] = null;
            $this->view->dataViewHeaderData = null;
        }
    }
    
    public function getDiagramRenderMethod($diagramTypeId) {
        
        if ($this->view->diagram['DASHBOARD_TYPE'] == 'flot') {
            self::extraExtensions('flot');
            $response = array(
                'Title' => $this->view->diagram['META_DATA_NAME'],
                'width' => $this->view->diagram['WIDTH'],
                'height' => $this->view->diagram['HEIGHT'],
                'close_btn' => Lang::line('close_btn'),
                'Html' => $this->view->renderPrint('flotchart/renderDashboard', self::$viewPath)
            );
        } else {
            $response = array(
                'Title' => $this->view->diagram['META_DATA_NAME'],
                'width' => $this->view->diagram['WIDTH'],
                'height' => $this->view->diagram['HEIGHT'],
                'close_btn' => Lang::line('close_btn'),
                'Html' => $this->view->renderPrint('renderDashboard', self::$viewPath)
            );
        }
        
        return $response;
    }
    
    public function callMetaTypeStatement() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $response = $this->model->getMetaDataModel(Input::numeric('metaDataId'));
        
        echo json_encode($response);
    }
    
    public function getDataViewGridCriteriaRowModel($metaDataId, $fieldPath) {
        $row = $this->db->GetRow("
            SELECT 
                DATA_TYPE AS META_TYPE_CODE 
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = $metaDataId 
                AND LOWER(FIELD_PATH) = '".strtolower($fieldPath)."'");

        return $row;
    }    
    
} 
