<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdlayout Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Meta Layout
 * @category	Layout
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdlayout
 */
class Mdlayout extends Controller {

    private static $viewPath = 'middleware/views/layout/';
    private static $defaultRowHeight = "380px";

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index($metaDataId = null, $isHeaderFooter = true, $isReturn = false) {
        $this->load->model('mdlayout', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Нүүр';

        $this->view->css = AssetNew::metaCss();
        $this->view->js = array_unique(array_merge(AssetNew::highchartJs(), AssetNew::metaOtherJs()));
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->fullUrlCss = AssetNew::amChartCss();
        $this->view->isAjax = is_ajax_request();
        
        if (!$metaDataId) {
            $this->view->contentMetas = $this->model->getOnlyContentMetaModel();
        } else {
            $this->view->metaDataId = Input::param($metaDataId);
            $this->view->metaDataId = $this->model->findMetaIdByBoth($this->view->metaDataId);
            if (!$this->view->metaDataId) {
                return;
            }
            $this->view->contentMetas[0]['META_DATA_ID'] = $this->view->metaDataId;
        }
        
        if (!$this->view->isAjax && $isHeaderFooter) {
            $this->view->render('header');
        }
        
        if ($isReturn) {
            return $this->view->renderPrint('index', self::$viewPath);
        } else {
            $this->view->render('index', self::$viewPath);
        }        

        if (!$this->view->isAjax && $isHeaderFooter) {
            $this->view->render('footer');
        }
    }

    public function getLayoutList() {
        $this->load->model('mdlayout', 'middleware/models/');
        $data = $this->model->getLayoutListModel();
        return $data;
    }

    public function setCellLink() {
        $this->load->model('mdlayout', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $layoutId = Input::post('layoutId');
        $contentMetaDataId = Input::post('contentMetaDataId');

        $this->view->layoutId = $layoutId;
        $this->view->metaDataId = $contentMetaDataId;

        $this->view->cellDatas = $this->model->getLayoutCellModel($layoutId);
        $this->view->metaDatas = (new Mdmetadata())->getMetaMetaMapBySrcId($contentMetaDataId);
        $this->view->contentLayout = self::generateLayoutTemplate($layoutId);

        $response = array(
            'Html' => $this->view->renderPrint('setCellLink', self::$viewPath),
            'Title' => 'Cell Links',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response);
    }

    public function setCellLinkSavedRender($layoutId, $metaDataId) {
        if (empty($layoutId)) {
            return;
        }
        $this->load->model('mdlayout', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->layoutId = $layoutId;
        $this->view->metaDataId = $metaDataId;
        $this->view->cellDatas = $this->model->getLayoutCellSavedModel($layoutId, $metaDataId);
        $this->view->metaDatas = (new Mdmetadata())->getMetaMetaMapBySrcId($metaDataId);
        $this->view->contentLayout = self::generateLayoutTemplate($layoutId);

        return $this->view->renderPrint('setCellLinkEditMode', self::$viewPath);
    }

    public function contentRenderById($contentId) {
        $this->load->model('mdlayout', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $metaDataId = Input::param($contentId);
        $row = $this->model->getLayoutMetaDataModel($metaDataId);
        $layoutId = $row['LAYOUT_ID'];

        if ($row['BORDER_WIDTH'] == null || $row['BORDER_WIDTH'] == 0) {
            $this->view->borderNone = true;
        } else {
            $this->view->borderNone = false;
        }

        $this->view->generateLayout = self::generateLayout($layoutId, $metaDataId, $row, false);

        return $this->view->render('contentRender', self::$viewPath);
    }

    public function contentRenderByPost() {
        $this->load->model('mdlayout', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $metaDataId = Input::numeric('metaDataId');
        $row = $this->model->getLayoutMetaDataModel($metaDataId);

        if ($row['BORDER_WIDTH'] == null || $row['BORDER_WIDTH'] == 0) {
            $this->view->borderNone = true;
        } else {
            $this->view->borderNone = false;
        }

        if ($row) {
            echo self::generateLayout($row['LAYOUT_ID'], $metaDataId, $row, false);
        } else {
            echo "No content!";
        }
        exit;
    }

    public function contentRender() {
        $this->load->model('mdlayout', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
            
        $metaDataId = Input::numeric('metaDataId');
        $row = $this->model->getLayoutMetaDataModel($metaDataId);
        $layoutId = $row['LAYOUT_ID'];

        $this->view->generateLayout = self::generateLayout($layoutId, $metaDataId, $row, true);
        
        if ($row['BORDER_WIDTH'] == null || $row['BORDER_WIDTH'] == 0) {
            $this->view->borderNone = true;
        } else {
            $this->view->borderNone = false;
        }
        $response = array(
            'Html' => $this->view->renderPrint('contentRender', self::$viewPath),
            'Title' => $row['META_DATA_NAME'],
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function generateLayoutTemplate($layoutId) {
        $this->load->model('mdlayout', 'middleware/models/');

        $cellData = $this->model->getLayoutAllCellModel($layoutId);
        $rowData = $this->model->getLayoutRowModel($layoutId);
        $colData = $this->model->getLayoutColModel($layoutId);
        $mergeData = $this->model->getLayoutMergeModel($layoutId);
        $mergeCellData = $this->model->getLayoutMergeCellModel($layoutId);

        $table = '<table class="table table-bordered table-content table-content-cell-hover">';
        $table .= '<tbody>';

        foreach ($rowData as $row) {
            $table .= '<tr>';

            foreach ($colData as $col) {
                $cellArr = Arr::multidimensional_search($cellData,
                                array('ROW_ID' => $row['ROW_ID'], 'COL_ID' => $col['COL_ID']));
                $startCellId = Arr::multidimensional_search($mergeData,
                                array('START_CELL_ID' => $cellArr['CELL_ID']));

                if ($startCellId) {
                    $findMerge = Arr::search($mergeCellData,
                                    "MERGE_ID = '" . $startCellId['MERGE_ID'] . "'", 1);
                    $findMergeCount = count($findMerge);

                    $resultRow = self::isSameRow($findMerge);
                    $mergeSpan = '';
                    
                    if ($resultRow == 'colspan') {
                        $mergeSpan = 'colspan="' . $findMergeCount . '"';
                    } else if ($resultRow == 'rowspan') {
                        $mergeSpan = 'rowspan="' . $findMergeCount . '"';
                    } else if ($resultRow == 'both') {
                        $countSpanArray = array();
                        foreach ($findMerge AS $arrayKey => $arrayValue) {
                            array_push($countSpanArray, $arrayValue['ROW_ID']);
                        }
                        $countSpan = array_count_values($countSpanArray);
                        $rowNum = 1;
                        foreach ($countSpan as $value) {
                            if ($value != $countSpan[$row['ROW_ID']]) {
                                $rowNum += $value;
                            }
                        }
                        $mergeSpan = 'colspan="' . $countSpan[$row['ROW_ID']] . '" rowspan="' . $rowNum . '"';
                    }

                    $table .= '<td ' . $mergeSpan . ' class="middle text-center font-weight-bold">';
                    $table .= $col['COL_ID'] . ' : ' . $row['ROW_ID'];
                    $table .= '</td>';
                } else {
                    if (($cellArr['IS_MERGE'] == '0' && $cellArr['IS_USE'] == '1')
                            || ($cellArr['IS_MERGE'] == '1' && $cellArr['IS_USE'] == '1')) {

                        $table .= '<td class="middle text-center font-weight-bold">';
                        $table .= $col['COL_ID'] . ' : ' . $row['ROW_ID'];
                        $table .= '</td>';
                    }
                }
            }

            $table .= '</tr>';
        }

        $table .= '</tbody>';
        $table .= '</table>';

        return $table;
    }

    public function isSameRow($findMerge) {
        $response = '';
        if (isset($findMerge[0])) {
            $rowspan = $colspan = false;
            foreach ($findMerge as $row) {
                foreach ($findMerge as $val) {
                    if ($row['CELL_ID'] != $val['CELL_ID']) {
                        if ($row['ROW_ID'] != $val['ROW_ID']) {
                            $rowspan = true;
                        }
                        if ($row['ROW_ID'] == $val['ROW_ID']) {
                            $colspan = true;
                        }
                    }
                }
            }
            if ($rowspan && $colspan) {
                $response = 'both';
            } else if ($rowspan) {
                $response = 'rowspan';
            } else if ($colspan) {
                $response = 'colspan';
            }
            return $response;
        } else {
            return $response;
        }
    }

    public function generateLayout($layoutId, $metaDataId, $data = array(), $isUpdate = false, $isMetaDataRender = true) {
        $this->load->model('mdlayout', 'middleware/models/');
        
        $cellData = $this->model->getLayoutAllCellDataModel($layoutId, $metaDataId);
        $rowData = $this->model->getLayoutRowModel($layoutId);
        $colData = $this->model->getLayoutColModel($layoutId);
        $mergeData = $this->model->getLayoutMergeModel($layoutId);
        $mergeCellData = $this->model->getLayoutMergeCellModel($layoutId);

        if ($isUpdate) {
            $cellArray = array();
            if (!is_null($cellData)) {
                foreach ($rowData as $row) {
                    foreach ($colData as $col) {
                        foreach ($cellData as $value) {
                            if ($value["ROW_ID"] == $row['ROW_ID'] && $value['COL_ID'] == $col['COL_ID']) {
                                foreach ($value as $key => $val) {
                                    $cellArray['r' . ($row["ROW_ID"] - 1)]['c' . ($col['COL_ID'] - 1)][strtolower($key)] = $val;
                                }
                            }
                        }
                    }
                }
            }
            $this->view->cellArray = $cellArray;
        }

        $cssStyle = '<style type="text/css">';
        if ($data['BG_COLOR'] != "") {
            $cssStyle .= ".content-bg_$layoutId {background-color:" . $data['BG_COLOR'] . " !important; min-height: 500px; padding-top: 10px; border-radius: 4px; -moz-box-shadow: 0px 1px 2px rgba(0,0,0,0.15) !important;
    -webkit-box-shadow: 0px 1px 2px rgba(0,0,0,0.15) !important;
    box-shadow: 0px 1px 2px rgba(0,0,0,0.15) !important;}";
        } else if ($data['BG_IMAGE'] != "") {
            $cssStyle .= ".content-bg_$layoutId {background: url('/storage/uploads/contentui/" . $data['BG_IMAGE'] . "');}";
        } else {
            $cssStyle .= ".content-bg_$layoutId {background-color: #e6e9f2 !important; min-height: 500px; padding-top: 10px; border-radius: 4px;-moz -box-shadow: 0px 1px 2px rgba(0,0,0,0.15) !important;
    -webkit-box-shadow: 0px 1px 2px rgba(0,0,0,0.15) !important;
    box-shadow: 0px 1px 2px rgba(0,0,0,0.15) !important;}";
        }

        $borderSpacing = 1;
        if ($data['BORDER_WIDTH'] != "") {
            $borderSpacing = $data['BORDER_WIDTH'];
        }
        $cssStyle .= "table.layout_$layoutId  {border-collapse: separate; border-spacing: " . $borderSpacing . "px !important;}";

        if (!$isUpdate) {
            $cssStyle .= " .ui-state-default{border:none !important; font-weight: normal;}";
        }

        $cssStyle .= '</style>';
        $header = '';
        
        (Array) $array_temp = array();
        (Array) $arrayMetaDataId   = array();
        (Array) $arrayChartType    = array();
        if ($cellData) {
          
            foreach ($cellData as $ck => $cvalue) {
                $this->load->model('mddashboard', 'middleware/models/');
                if ($cvalue['META_DATA_ID']) {
                    $diagram = $this->model->getMetaDiagramLinkModel($cvalue['META_DATA_ID']);
                    if ($diagram) {
                        array_push($arrayMetaDataId, $cvalue['META_DATA_ID']);
                        array_push($arrayChartType, $diagram['DIAGRAM_TYPE']);

                        $this->load->model('mdobject', 'middleware/models/');
                        $dataViewHeaderData = $this->model->dataViewHeaderDataModel($diagram['PROCESS_META_DATA_ID']);
                        if ($dataViewHeaderData) {
                            foreach ($dataViewHeaderData as $row) {
                                array_push($array_temp, $row);
                            }
                        }
                    }
                }
            }
            
            (Array) $arraySearchTemped = array();
            (Array) $arraySearchNeedle = array();
            if (count($array_temp) > 0) {
                foreach ($array_temp as $value) {
                    if (!in_array($value['META_DATA_CODE'], $arraySearchTemped)) {
                      array_push($arraySearchNeedle, $value);
                      array_push($arraySearchTemped, $value['META_DATA_CODE']);
                    }
                }

                $joinMetaDataId = implode(',', $arrayMetaDataId );
                $joinChartType = implode(',', $arrayChartType );
                
                $header = '<div id="divid_layout_'. $layoutId .'">'
                              . '<div class="dashboard-right-sidebar col-md-3 pl0 pr0" data-status="closed" style="  background: none; box-shadow: none; ">'
                                  . '<div class="dashboard-right-stoggler sidebar-right">'
                                    . '<span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> '
                                    . '<span style="display: block;" class="fa fa-chevron-left">&nbsp;<span style="font-weight: 700;">НЭГДСЭН ХАЙЛТ<span></span>'
                                  . '</div>'
                                  . '<div class="dashboard-right-sidebar-content">'
                                      . '<div class="col-md-12 mt10">'
                                          . '<input type="hidden" id="joinMetaDataId" value = "'. $joinMetaDataId .'" />'
                                          . '<input type="hidden" id="joinChartType" value = "'. $joinChartType .'" />'
                                          . '<form class="form-horizontal" method="post" id="one-default-criteria-form">'
                                              .'<div class="row">';
                                                  foreach ($arraySearchNeedle as $k => $param) {
                                                      
                                                      $header .=  ' <div class="col-md-12 pl30">'
                                                          . ' <div class="panel-group accordion" id="accordion3">'
                                                              . ' <div class="panel panel-default">'
                                                                    . ' <div class="panel-heading">'
                                                                      . ' <h4 class="panel-title">'
                                                                        . '<a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion'. $param['META_DATA_CODE'] . '" href="#collapse_3_'. $param['META_DATA_CODE'] . '" aria-expanded="false">'. $param['META_DATA_NAME'] .'</a>'
                                                                      . ' </h4>'
                                                                    . ' </div>'
                                                                    . ' <div id="collapse_3_'. $param['META_DATA_CODE'] . '" class="panel-collapse collapse" aria-expanded="false">'
                                                                        . ' <div class="panel-body p-0">'
                                                                                . ' <div class="col-md-12 pl0 pr0 divclass_'. $param['META_DATA_CODE'] . '">';
                                                                                      $header .= '<div class="col-md-4 pl0 pr0 mb5"> '
                                                                                          . Form::select(
                                                                                                      array(
                                                                                                          'name' => 'criteriaOperator['.$param['META_DATA_CODE'].']',
                                                                                                          'id' => 'criteriaOperator['.$param['META_DATA_CODE'].']',
                                                                                                          'class' => 'form-control form-control-sm',
                                                                                                          'data' => Info::criteriaCondition(), 
                                                                                                          'op_value' => 'value',
                                                                                                          'op_text' => 'name',
                                                                                                          'text' => '',
                                                                                                          'value' => '=',
                                                                                                          'onchange' => 'changeDashboardFilterOperator(this, \''.$param['META_DATA_CODE'].'\', \''.$param['META_TYPE_CODE'].'\', '.$k.')'
                                                                                                      )
                                                                                              )
                                                                                      . '</div>'
                                                                                      . '<div class="col-md-7 pr0">   '
                                                                                          .'<div id="dashboard-filter-default-input-'. $param['META_DATA_ID'] . '">';
                                                                                          $header .=  Mdwebservice::renderParamControl($param['MAIN_META_DATA_ID'], $param, "param[" . $param['META_DATA_CODE'] . "][]", $param['META_DATA_CODE'], false);
                                                                                          $header .=  '</div>'
                                                                                      . '</div>'
//                                                                                      . '<div class="col-md-1 padding-left-2 pr0" id="">'
//                                                                                          . '<a href="javascript:;" class="btn btn-xs green" onclick="addCriteriaOperator(this, \''. $param['META_DATA_ID'] . '\', \''. $param['META_DATA_CODE'] . '\')" style="padding:2.5px 5px 3px 5px !important"><i class="icon-plus3 font-size-12"></i></a>'
//                                                                                      . '</div>'
                                                                            . ' </div>'
                                                                        . ' </div>'
                                                                    . ' </div>'
                                                              . ' </div>'
                                                          . ' </div>'
                                                      . ' </div>';
                                                  }
                                              $header .=  '</div>'
                                                  .'<div class="row mb10">'
                                                        . '<div class="col-md-12 text-right">'
                                                            . '<button type="button" class="btn btn-sm btn-circle blue-madison oneSearchBtn"><i class="fa fa-search"></i> Шүүх</button>'
                                                            . '<button type="button" class="btn btn-sm btn-circle default reset-oneSearchForm ml5">Цэвэрлэх</button>'
                                                        . '</div>'
                                                  . '</div>'
                                              . '</div>'
                                          . '</form>'
                                      . '</div>'
                                  . '</div>'
                              . '</div>'
                          . '</div>';
                
                $header .= '<script type="text/javascript">'
                              . '$(function() {$("#divid_layout_'. $layoutId .'").parent(".col-md-12").removeClass("col-md-12");});'
                              .'$(".dashboard-right-stoggler", "#divid_layout_'. $layoutId .'").on("click", function () { '
                                  . ' '
                                  . ' var _thisToggler = $(this);'
                                  . ' var dashboardleftsidebar = $(".dashboard-right-sidebar", "#divid_layout_'. $layoutId .'");'
                                  . ' var dashboardleftsidebarstatus = dashboardleftsidebar.attr("data-status");'
                                  . ' if (dashboardleftsidebarstatus === "closed") {'
                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-right").parent().hide();'
                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-left").hide();'

                                      . ' dashboardleftsidebar.find(".dashboard-right-sidebar-content").show();'
                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-right").parent().fadeIn("slow");'
                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-right").fadeIn("slow");'

                                      . ' dashboardleftsidebar.attr("data-status", "opened");'
                                      . ' dashboardleftsidebar.attr("style", "background-color: #FFF; box-shadow: 0 0 20px rgba(0,0,0,0.3);");'
                                      . ' _thisToggler.addClass("sidebar-opened");'
                                  . ' } else {'
                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-right").hide();'
                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-right").parent().hide();'
                                      . ' dashboardleftsidebar.find(".dashboard-right-sidebar-content").hide();'

                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");'
                                      . ' dashboardleftsidebar.find(".glyphicon-chevron-left").fadeIn("slow");'
                                      . ' dashboardleftsidebar.attr("style", "background: none; box-shadow: none;");'
                                      . ' dashboardleftsidebar.attr("data-status", "closed");'
                                      . ' _thisToggler.removeClass("sidebar-opened");'
                                  . ' }'
                              . ' });' 
                              . 'function addCriteriaOperator(element, metaDataId, metaDataCode) {'
                                  . 'console.log(metaDataId, metaDataCode);'
//                                      . 'var html = \''<div class="col-md-4 pl0 pr0 mb5"> '
//                                          . Form::select(
//                                                      array(
//                                                          'name' => 'criteriaOperator['.$param['META_DATA_CODE'].']',
//                                                          'id' => 'criteriaOperator['.$param['META_DATA_CODE'].']',
//                                                          'class' => 'form-control form-control-sm',
//                                                          'data' => Info::criteriaCondition(), 
//                                                          'op_value' => 'value',
//                                                          'op_text' => 'name',
//                                                          'text' => '',
//                                                          'value' => '=',
//                                                          'onchange' => 'changeDashboardFilterOperator(this, \''.$param['META_DATA_CODE'].'\', \''.$param['META_TYPE_CODE'].'\', '.$k.')'
//                                                      )
//                                              )
//                                      . '</div>'
//                                      . '<div class="col-md-7 pr0">   '
//                                          .'<div id="dashboard-filter-default-input-'. $param['META_DATA_ID'] . '">';
//                                            . Mdwebservice::renderParamControl($param['MAIN_META_DATA_ID'], $param, "param[" . $param['META_DATA_CODE'] . "][]", $param['META_DATA_CODE'], false);
//                                          . '</div>'
//                                      . '</div>\''
                              .'}'
                          . '</script>';
                
            }
        }

        $table = '<div class="content-bg_' . $layoutId . '">';
        $table .= '<table id="selectable" class="table table-content ui-selectable layout_' . $layoutId . '">';
        $table .= '<tbody>';
        $tmp = '';
        
        foreach ($rowData as $row) {
            $rowId = 'id="r' . ($row['ROW_ID'] - 1) . '"';
            $table .= '<tr ' . $rowId . '>';
            foreach ($colData as $col) {
                $cellArr = Arr::multidimensional_search($cellData,
                                array('ROW_ID' => $row['ROW_ID'], 'COL_ID' => $col['COL_ID']));
                $colId = 'id="r' . ($row['ROW_ID'] - 1) . '_c' . ($col['COL_ID'] - 1) . '"';
                $rowHeight = (($cellArr['HEIGHT'] != "") ? $cellArr['HEIGHT'] : self::$defaultRowHeight);
                $style = "height: $rowHeight;";
                if ($cellArr['WIDTH'] != "") {
                    $style .= "width: " . $cellArr['WIDTH'] . ";";
                }
                $startCellId = Arr::multidimensional_search($mergeData,
                                array('START_CELL_ID' => $cellArr['CELL_ID']));
                $cellAttr = array(
                    'cellId' => $cellArr['CELL_ID'],
                    'height' => $rowHeight,
                    'width' => $col['WIDTH']
                );


                if ($cellArr['BG_COLOR'] != "") {
                    $style.="background-color: " . $cellArr['BG_COLOR'] . ";";
                } else if ($data['BG_COLOR'] != "") {
//          $style.="background-color: " . $data['BG_COLOR'] . ";";
                } else {
//          $style.="background: #fff;";
                }

                $cellBorderColor = "#ddd;";
                if ($cellArr['BORDER_COLOR'] != "") {
                    $cellBorderColor = $cellArr['BORDER_COLOR'] . ";";
                }

                if ($cellArr['BORDER_TOP'] != '0') {
                    $style .="border-top: " . $cellArr['BORDER_TOP'] . "px solid " . $cellBorderColor . " !important; padding-top: " . $cellArr['BORDER_TOP'] . "px !important;";
                }

                if ($cellArr['BORDER_LEFT'] != '0') {
                    $style .="border-left: " . $cellArr['BORDER_LEFT'] . "px solid " . $cellBorderColor . " !important; padding-left: " . $cellArr['BORDER_TOP'] . "px !important;";
                }

                if ($cellArr['BORDER_BOTTOM'] != '0') {
                    $style .="border-bottom: " . $cellArr['BORDER_BOTTOM'] . "px solid " . $cellBorderColor . " !important; padding-bottom: " . $cellArr['BORDER_TOP'] . "px !important;";
                }

                if ($cellArr['BORDER_RIGHT'] != '0') {
                    $style .="border-right: " . $cellArr['BORDER_RIGHT'] . "px solid " . $cellBorderColor . " !important; padding-right: " . $cellArr['BORDER_TOP'] . "px !important;";
                }
                
                if ($cellArr['IS_USE'] == '1') {
                    if ($startCellId) {
                        $tmp = $startCellId['MERGE_ID'];
                        $findMerge = Arr::search($mergeCellData, "MERGE_ID = '" . $startCellId['MERGE_ID'] . "'", 1);
                        $findMergeCount = count($findMerge);
                        $mergeSpan = '';
                        
                        $resultRow = self::isSameRow($findMerge);
                        if ($resultRow == 'colspan') {
                            $mergeSpan = 'colspan="' . $findMergeCount . '"';
                        } else if ($resultRow == 'rowspan') {
                            $mergeSpan = 'rowspan="' . $findMergeCount . '"';
                        } else if ($resultRow == 'both') {
                            $countSpanArray = array();
                            foreach ($findMerge AS $arrayKey => $arrayValue) {
                                array_push($countSpanArray, $arrayValue['ROW_ID']);
                            }
                            $countSpan = array_count_values($countSpanArray);
                            $rowNum = 1;
                            foreach ($countSpan as $value) {
                                if ($value != $countSpan[$row['ROW_ID']]) {
                                    $rowNum+=$value;
                                }
                            }
                            $mergeSpan = 'colspan="' . $countSpan[$row['ROW_ID']] . '" rowspan="' . $rowNum . '"';
                        }
                        $table .= '<td ' . $colId . ' class="l-content-cell ui-state-default" ' . $mergeSpan . ' data-cellid="' . $cellArr['CELL_ID'] . '" data-metadataid="' . $cellArr['META_DATA_ID'] . '" style="' . $style . '" align="' . $cellArr['ALIGN'] . '" valign="' . $cellArr['VALIGN'] . '">';
                        if (isset($cellArr['CAPTION'])) {
                            if ($cellArr['CAPTION'] != "") {
                                $table .= '<span class="font-weight-bold">' . $cellArr['CAPTION'] . '</span><hr style="margin-top: 2px;">';
                            }
                        }
                        if (!is_null($cellArr['META_DATA_ID']) && $isMetaDataRender) {
                            $this->load->model('mddashboard', 'middleware/models/');
                            $diagramProcess = $this->model->getMetaDiagramLinkModel($cellArr['META_DATA_ID']);
                            $table .= '<div class="cellMetaData process_metaDataId_'.$cellArr['META_DATA_ID'].'" metaDataId="' . $cellArr['META_DATA_ID'] . '" processMetaDataId="' . $diagramProcess['PROCESS_META_DATA_ID'] . '">'
                                    . self::metaDataRender($cellArr['META_DATA_ID'], $cellAttr) . '</div>';
                        } else if ($isUpdate) {
                            $table.= self::getRowSize($cellArr, $rowHeight);
                        }
                        $table .= '</td>';
                    } else {
                        if (($cellArr['IS_MERGE'] == '0') || ($cellArr['IS_MERGE'] == '1' && $cellArr['IS_USE'] == '1')) {
                            
                            $table .= '<td ' . $colId . '  class="l-content-cell ui-state-default" data-cellid="' . $cellArr['CELL_ID'] . '" data-metadataid="' . $cellArr['META_DATA_ID'] . '" style="' . $style . '" align="' . $cellArr['ALIGN'] . '" valign="' . $cellArr['VALIGN'] . '">';
                            if (isset($cellArr['CAPTION'])) {
                                if ($cellArr['CAPTION'] != "") {
                                    $table .= '<span class="font-weight-bold">' . $cellArr['CAPTION'] . '</span><hr style="margin-top: 2px;">';
                                }
                            }
                            if (!is_null($cellArr['META_DATA_ID']) && $isMetaDataRender) {
                                $this->load->model('mddashboard', 'middleware/models/');
                                $diagramProcess = $this->model->getMetaDiagramLinkModel($cellArr['META_DATA_ID']);
                                $table .= '<div class="cellMetaData process_metaDataId_'.$cellArr['META_DATA_ID'].'" metaDataId="' . $cellArr['META_DATA_ID'] . '"  processMetaDataId="' . $diagramProcess['PROCESS_META_DATA_ID'] . '">'
                                        . self::metaDataRender($cellArr['META_DATA_ID'], $cellAttr) . '</div>';
                            } else if ($isUpdate) {
                                $table.= self::getRowSize($cellArr, $rowHeight);
                            }
                            $table .= '</td>';
                        }
                    }
                }
            }

            $table .= '</tr>';
        }

        $table .= '</tbody>';
        $table .= '</table>';
        $table .= '</div>';

        return $header. $table . $cssStyle;
    }

    public function getRowSize($cellArr, $rowHeight) {
        $html = '<div class="cell-width-height">'
                . '<span class="cell-width-text">' . $cellArr['WIDTH'] . 'x</span>'
                . '<span class="cell-height-text">' . $rowHeight . '</span>'
                . '</div>';
        return $html;
    }

    public function metaDataRender($metaDataId, $cellAttr) {
        if (empty($metaDataId)) {
            return;
        }
        $this->load->model('mdlayout', 'middleware/models/');

        $metaTypeId = $this->model->getMetaTypeIdModel($metaDataId);

        if ($metaTypeId === Mdmetadata::$dashboardMetaTypeId) { //Дашбоард
            return self::contentDashboard($metaDataId, $cellAttr);
        }

        if ($metaTypeId === Mdmetadata::$diagramMetaTypeId) { //Дашбоард шинэ
            return self::contentDashboardNew($metaDataId, $cellAttr);
        }

        if ($metaTypeId === Mdmetadata::$bannerMetaTypeId) { //Баннер
            return self::contentBanner($metaDataId, $cellAttr);
        }

        if ($metaTypeId === Mdmetadata::$menuMetaTypeId) { //Меню
            return self::contentMenu($metaDataId, $cellAttr);
        }

        if ($metaTypeId === Mdmetadata::$calendarMetaTypeId) { //Календарь
            return self::contentCalendar($metaDataId, $cellAttr);
        }

        if ($metaTypeId === Mdmetadata::$donutMetaTypeId) { //Donut
            return self::contentDonut($metaDataId, $cellAttr);
        }

        if ($metaTypeId === Mdmetadata::$cardMetaTypeId) { //Donut
            return self::contentCard($metaDataId, $cellAttr);
        }

        return $metaDataId;
    }

    public function contentDashboard($metaDataId, $cellAttr) {
        $this->view->row = (new Mdmetadata())->getDMChartByMeta($metaDataId);

        $this->view->metaDataId = $metaDataId;
        $this->view->cellId = $cellAttr['cellId'];

        return $this->view->renderPrint('link/dashboard/renderChart', self::$viewPath);
    }

    public function contentDashboardNew($metaDataId, $cellAttr) {
        $this->load->model('mddashboard', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->metaDataId = $metaDataId;
        $this->view->diagram = (new Mddashboard())->getMetaDiagramLink($this->view->metaDataId);
        $this->view->diagram['TEXT'] = null;

        $this->view->diagram = (new Mddashboard())->getMetaDiagramLink($this->view->metaDataId);
        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($this->view->diagram['PROCESS_META_DATA_ID']);
        $this->view->defaultCriteria = $this->view->renderPrint('defaultCriteria', "middleware/views/dashboard/");
        
        $this->view->isLayout = 1;
        
        if ($this->view->diagram['DASHBOARD_TYPE'] == 'amchart') {
            $renderPage = 'amcharts/column';
        } else {
            $renderPage = 'renderDashboard';
        }
        
        return $this->view->renderPrint($renderPage, "middleware/views/dashboard/");
    }

    public function contentBanner($metaDataId, $cellAttr) {
        $this->view->cellAttr = $cellAttr;
        $this->view->metaDataId = $metaDataId;
        $this->view->cellId = $cellAttr['cellId'];
        $this->view->bannerList = (new Mdmetadata())->getMetaDataPhotos($metaDataId);

        return $this->view->renderPrint('link/banner/renderBanner', self::$viewPath);
    }

    public function contentMenu($metaDataId, $cellAttr) {
        $this->view->cellAttr = $cellAttr;
        $this->view->metaDataId = $metaDataId;
        $this->view->cellId = $cellAttr['cellId'];
        $this->view->menuHtml = '';
        $this->view->metaRow = (new Mdmeta())->getMenuLink($metaDataId);

        if ($this->view->metaRow) {
            if ($this->view->metaRow['MENU_POSITION'] == 'horizontal') {
                $this->view->menuHtml = $this->model->horizontalMenuRenderModel($this->view->metaDataId, $this->view->metaRow);
            } else {
                $this->view->menuHtml = $this->model->verticalMenuRenderModel($this->view->metaDataId, $this->view->metaRow);
            }
        }
        
        return $this->view->renderPrint('link/menu/renderMenu', self::$viewPath);
    }

    public function contentCalendar($metaDataId, $cellAttr) {
        $this->view->cellAttr = $cellAttr;
        $this->view->metaDataId = $metaDataId;
        $this->view->cellId = $cellAttr['cellId'];
        $this->view->metaRow = (new Mdmetadata())->getMetaCalendarLink($metaDataId);

        if ($this->view->metaRow['VIEW_SIZE'] == 'meta-mini-calendar') {
            $renderPath = 'link/calendar/renderMini';
        } else {
            $renderPath = 'link/calendar/renderBig';
        }

        return $this->view->renderPrint($renderPath, self::$viewPath);
    }

    public function contentDonut($metaDataId, $cellAttr) {
        $this->view->cellAttr = $cellAttr;
        $this->view->metaDataId = $metaDataId;
        $this->view->cellId = $cellAttr['cellId'];
        $this->view->donut = (new Mdmetadata())->getMetaDonutLink($metaDataId);

        return $this->view->renderPrint('link/donut/renderDonut', self::$viewPath);
    }

    public function contentCard($metaDataId, $cellAttr) {
        if (!is_null($cellAttr)) {
            $this->view->cellAttr = $cellAttr;
            $this->view->cellId = $cellAttr['cellId'];
        }

        $this->view->metaDataId = $metaDataId;
        $count = 0;
        $this->view->card = (new Mdmetadata())->getMetaCardLink($metaDataId);
        if ($this->view->card['PROCESS_META_DATA_ID'] != null) {
            $_POST['methodId'] = $this->view->card['PROCESS_META_DATA_ID'];

            // RUN DE WEBSERVICE
            $this->load->model('mdwebservice', 'middleware/models/');
            $postData = Input::postData();
            $metaDataId = Input::param($postData['methodId']);
            $row = $this->model->getMethodIdByMetaDataModel($metaDataId);
            $param = array();
            $postData['param'] = array();
            
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
            }
            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param);

            if ($this->ws->isException()) {
                $result = array('status' => 'error', 'message' => $this->ws->getErrorMessage());
            } else {
                if (isset($result['result'])) {
                    foreach ($result['result'] AS $k => $v) {
                        $this->view->card['TEXT_FROM_SERVICE'] = $v;
                    }
                }
            }

            return $this->view->renderPrint('link/card/renderCard', self::$viewPath);
        }
        if ($this->view->card['IS_SEE'] == '1') {
            if ($this->view->card['VIEW_NAME'] != null) {

                try {
                    $count = $this->db->GetOne("SELECT COUNT(*) FROM ".$this->view->card['VIEW_NAME']);
                } catch (Exception $ex) {

                }
            }
        }
        $this->view->card['ROW_COUNT'] = $count;

        return $this->view->renderPrint('link/card/renderCard', self::$viewPath);
    }
    
    public function generateLayoutData($layoutId, $data = array(), $isUpdate = false, $isMetaDataRender = true) {
        $this->load->model('mdlayout', 'middleware/models/');

        $cellData = $this->model->getLayoutACellDataModel($layoutId);
        $rowData = $this->model->getLayoutRowModel($layoutId);
        $colData = $this->model->getLayoutColModel($layoutId);
        $mergeData = $this->model->getLayoutMergeModel($layoutId);
        $mergeCellData = $this->model->getLayoutMergeCellModel($layoutId);

        if ($isUpdate) {
            $cellArray = array();
            if (!is_null($cellData)) {
                foreach ($rowData as $row) {
                    foreach ($colData as $col) {
                        foreach ($cellData as $value) {
                            if ($value["ROW_ID"] == $row['ROW_ID'] && $value['COL_ID'] == $col['COL_ID']) {
                                foreach ($value as $key => $val) {
                                    $cellArray['r' . ($row["ROW_ID"] - 1)]['c' . ($col['COL_ID'] - 1)][strtolower($key)] = $val;
                                }
                            }
                        }
                    }
                }
            }
            $this->view->cellArray = $cellArray;
        }

        $cssStyle = '<style type="text/css">';
        if ($data['BG_COLOR'] != "") {
            $cssStyle .= ".content-bg_$layoutId {background-color:" . $data['BG_COLOR'] . " !important; min-height: 500px;}";
        } 
        else 
          if ($data['BG_IMAGE'] != "") {
            $cssStyle .= ".content-bg_$layoutId {background: url('/storage/uploads/contentui/" . $data['BG_IMAGE'] . "');}";
        } 
          else {
              $cssStyle .= ".content-bg_$layoutId {background-color: #e6e9f2 !important; min-height: 500px;}";
          }

        $borderSpacing = 1;
        if ($data['BORDER_WIDTH'] != "") {
            $borderSpacing = $data['BORDER_WIDTH'];
        }
        $cssStyle .= "table.layout_$layoutId  {border-collapse: separate; border-spacing: " . $borderSpacing . "px !important;}";

        if (!$isUpdate) {
            $cssStyle .= " .ui-state-default{border:none !important; font-weight: normal;}";
        }
        
        $cssStyle .= '</style>';
        $header = '';
        $table = '<div class="content-bg_' . $layoutId . '">';
        $table .= '<table id="selectable" class="table table-content ui-selectable layout_' . $layoutId . '">';
        $table .= '<tbody>';
        $tmp = '';

        foreach ($rowData as $row) {
            $rowId = 'id="r' . ($row['ROW_ID'] - 1) . '"';
            $table .= '<tr ' . $rowId . '>';
            foreach ($colData as $col) {
                $cellArr = Arr::multidimensional_search($cellData, array('ROW_ID' => $row['ROW_ID'], 'COL_ID' => $col['COL_ID']));
                $colId = 'id="r' . ($row['ROW_ID'] - 1) . '_c' . ($col['COL_ID'] - 1) . '"';
                $rowHeight = (($cellArr['HEIGHT'] != "") ? $cellArr['HEIGHT'] : self::$defaultRowHeight);
                $style = "height: $rowHeight;";
                if ($cellArr['WIDTH'] != "") {
                    $style .= "width: " . $cellArr['WIDTH'] . ";";
                }
                $startCellId = Arr::multidimensional_search($mergeData, array('START_CELL_ID' => $cellArr['CELL_ID']));
                $cellAttr = array(
                    'cellId' => $cellArr['CELL_ID'],
                    'height' => $rowHeight,
                    'width' => $col['WIDTH']
                );


                if ($cellArr['BG_COLOR'] != "") {
                    $style.="background-color: " . $cellArr['BG_COLOR'] . ";";
                } else if ($data['BG_COLOR'] != "") {
//          $style.="background-color: " . $data['BG_COLOR'] . ";";
                } else {
//          $style.="background: #fff;";
                }

                $cellBorderColor = "#ddd;";
                if ($cellArr['BORDER_COLOR'] != "") {
                    $cellBorderColor = $cellArr['BORDER_COLOR'] . ";";
                }

                if ($cellArr['BORDER_TOP'] != '0') {
                    $style .="border-top: " . $cellArr['BORDER_TOP'] . "px solid " . $cellBorderColor . " !important; padding-top: " . $cellArr['BORDER_TOP'] . "px !important;";
                }

                if ($cellArr['BORDER_LEFT'] != '0') {
                    $style .="border-left: " . $cellArr['BORDER_LEFT'] . "px solid " . $cellBorderColor . " !important; padding-left: " . $cellArr['BORDER_TOP'] . "px !important;";
                }

                if ($cellArr['BORDER_BOTTOM'] != '0') {
                    $style .="border-bottom: " . $cellArr['BORDER_BOTTOM'] . "px solid " . $cellBorderColor . " !important; padding-bottom: " . $cellArr['BORDER_TOP'] . "px !important;";
                }

                if ($cellArr['BORDER_RIGHT'] != '0') {
                    $style .="border-right: " . $cellArr['BORDER_RIGHT'] . "px solid " . $cellBorderColor . " !important; padding-right: " . $cellArr['BORDER_TOP'] . "px !important;";
                }

                if ($cellArr['IS_USE'] == '1') {
                    if ($startCellId) {
                        $tmp = $startCellId['MERGE_ID'];
                        $findMerge = Arr::search($mergeCellData,
                                        "MERGE_ID = '" . $startCellId['MERGE_ID'] . "'", 1);
                        $findMergeCount = count($findMerge);

                        $resultRow = self::isSameRow($findMerge);
                        if ($resultRow == 'colspan') {
                            $mergeSpan = 'colspan="' . $findMergeCount . '"';
                        } else if ($resultRow == 'rowspan') {
                            $mergeSpan = 'rowspan="' . $findMergeCount . '"';
                        } else if ($resultRow == 'both') {
                            $countSpanArray = array();
                            foreach ($findMerge AS $arrayKey => $arrayValue) {
                                array_push($countSpanArray, $arrayValue['ROW_ID']);
                            }
                            $countSpan = array_count_values($countSpanArray);
                            $rowNum = 1;
                            foreach ($countSpan as $value) {
                                if ($value != $countSpan[$row['ROW_ID']]) {
                                    $rowNum+=$value;
                                }
                            }
                            $mergeSpan = ' colspan="' . $countSpan[$row['ROW_ID']] . '" rowspan="' . $rowNum . '"';
                        }

                        $table .= '<td ' . $colId . ' class="l-content-cell ui-state-default" ' . $mergeSpan . ' data-cellid="' . $cellArr['CELL_ID'] . '" data-metadataid="' . $cellArr['META_DATA_ID'] . '" style="' . $style . '" align="' . $cellArr['ALIGN'] . '" valign="' . $cellArr['VALIGN'] . '">';
                        if (isset($cellArr['CAPTION'])) {
                            if ($cellArr['CAPTION'] != "") {
                                $table .= '<span class="font-weight-bold">' . $cellArr['CAPTION'] . '</span><hr style="margin-top: 2px;">';
                            }
                        }
                        if (!is_null($cellArr['META_DATA_ID']) && $isMetaDataRender) {
                            $table .= '<div class="cellMetaData" metaDataId="' . $cellArr['META_DATA_ID'] . '">'
                                    . self::metaDataRender($cellArr['META_DATA_ID'], $cellAttr) . '</div>';
                        } else if ($isUpdate) {
                            $table.= self::getRowSize($cellArr, $rowHeight);
                        }
                        $table .= '</td>';
                    } else {
                        if (($cellArr['IS_MERGE'] == '0') || ($cellArr['IS_MERGE'] == '1' && $cellArr['IS_USE'] ==
                                '1')) {
                            $table .= '<td ' . $colId . '  class="l-content-cell ui-state-default" data-cellid="' . $cellArr['CELL_ID'] . '" data-metadataid="' . $cellArr['META_DATA_ID'] . '" style="' . $style . '" align="' . $cellArr['ALIGN'] . '" valign="' . $cellArr['VALIGN'] . '">';
                            if (isset($cellArr['CAPTION'])) {
                                if ($cellArr['CAPTION'] != "") {
                                    $table .= '<span class="font-weight-bold">' . $cellArr['CAPTION'] . '</span><hr style="margin-top: 2px;">';
                                }
                            }
                            if (!is_null($cellArr['META_DATA_ID']) && $isMetaDataRender) {
                                $table .= '<div class="cellMetaData" metaDataId="' . $cellArr['META_DATA_ID'] . '">'
                                        . self::metaDataRender($cellArr['META_DATA_ID'], $cellAttr) . '</div>';
                            } else if ($isUpdate) {
                                $table.= self::getRowSize($cellArr, $rowHeight);
                            }
                            $table .= '</td>';
                        }
                    }
                }
            }

            $table .= '</tr>';
        }

        $table .= '</tbody>';
        $table .= '</table>';
        $table .= '</div>';

        return $header . $table . $cssStyle;
    }
    
    public function dataViewColumnList() {
        $this->load->model('mdobject', 'middleware/models/');
        
        $metaDataId = Input::numeric('metaDataId');
        $dataViewColumnDataFields = $this->model->getDataViewGridAllFieldsModel($metaDataId);

        echo json_encode($dataViewColumnDataFields); exit;
    }    
    
    public function getWidgetHtml() {
        $html = file_get_contents('middleware/views/widget/content/sales-charts.html');

        echo $html; exit;
    }    
    
    public function updateWidgetHtml() {
        $position = Input::post('position');
        $html = file_get_contents('storage/uploads/process_template/sales-charts.html');

        $getData = $this->model->getDataListModel(Input::post('dataViewId'));

        $searchReplace  = array(
            '{CHART_VALUE_' . $position . '}',
            '{CHART_NAME_' . $position . '}',
            '{CHART_DATASOURCE_' . $position . '}',
        );
        $replaced = array(
            Input::post('value'),
            Input::post('name'),
            json_encode($getData),
        );

        $replacedHtml = str_replace($searchReplace, $replaced, $html);
        echo $replacedHtml; exit;
    }    
    
    public function getWidgetParamConfig() {
        $this->load->model('Mdwidget', 'middleware/models/');
        $this->view->layoutParamConfig = $this->model->layoutParamConfigModel(Input::postData());
        
        echo json_encode($this->view->layoutParamConfig); exit;
    }    
    
    public function treeLayout() {
        
        $this->view->uniqId = getUID();
        $this->view->title = '';
        $this->view->isAjax = true;
        $this->view->chartType = '';
        $this->view->configId = '';
        
        $this->view->legendData = $this->view->leftSideBarMenu = array();
        
        if (Input::postCheck('selectedRow') && !Input::isEmpty('selectedRow')) {
            $this->view->selectedRow = json_decode($_POST['selectedRow'],true);
            $this->view->title = $this->view->selectedRow['name'];
            $this->view->templateId = $this->view->selectedRow['id'];
            $this->view->selectedId = issetParam($this->view->selectedRow['selectedId']);
            $this->view->chartType = strtolower($this->view->selectedRow['charttype']);
        }
        
        if ($this->view->chartType === 'treelayout' || $this->view->chartType === 'streelayout') {
            
            $this->view->configId = ($this->view->chartType === 'streelayout') ? '1565570024240' : '1565570019541';
            $this->view->getHeaderLegendData = $this->model->getHeaderLegendDataModel($this->view->templateId, $this->view->configId);
            $ticket = true;

            if (isset($this->view->getHeaderLegendData['templateids']) && $this->view->getHeaderLegendData['templateids']) {
                foreach ($this->view->getHeaderLegendData['templateids'] as $key => $row) {
                    $row['color'] = random_color();
                    array_push($this->view->legendData, $row);

                    if ($row['iscatalogue'] === '1' && $ticket) {
                        $ticket = false;
                        $leftSideBarMenu = $row;
                        $this->view->subTitle = $row['name'];
                    }
                }

                unset($this->view->getHeaderLegendData['templateids']);
            }

            if (isset($leftSideBarMenu['listmetadataid']) && $leftSideBarMenu['listmetadataid']) {
                
                $param = array(
                    'systemMetaGroupId' => $leftSideBarMenu['listmetadataid'],
                    'showQuery' => '0',
                    'ignorePermission' => 1,
                    'criteria' => array()
                );

                $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
                
                if (isset($data['result']) && $data['result']) {
                    unset($data['result']['aggregatecolumns']);
                    $this->view->leftSideBarMenu = $data['result'];
                }
            }
                
            $response = array(
                'Header' => '',
                'Html' => $this->view->renderPrint('ea/content/repository', self::$viewPath),
                'Title' => $this->view->title, 
                'Type' => $this->view->chartType,
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response); exit;
            
        } elseif ($this->view->chartType === 'matrix') {
            
            $this->view->configId = '1565570028122';
            $this->view->data = $this->model->getMatrixDataModel($this->view->configId, array('id' => $this->view->templateId));
            
            $this->view->processId = '1565261227158929';

            $params = array ( 
                'rowTemplateId' => $this->view->data['rowtemplateid'], 
                'rowTemplateCriteria' => $this->view->data['rowtemplatecriteria'] ,
                'columnTemplateId' => $this->view->data['columntemplateid'], 
                'columnTemplateCriteria' => $this->view->data['columntemplatecriteria'] ,
                'expression' => $this->view->data['expression'] 
            );

            $this->view->data = $this->model->getMatrixDataModel($this->view->processId, $params);
            
            $this->view->mainData = isset($this->view->data['result']) ? json_decode($this->view->data['result'], true) : array() ;
            $this->view->dataRow = array();
            
            if ($this->view->mainData) {
                foreach ($this->view->mainData as $key => $row) {
                    if ($key < 10) {
                        $data = Arr::sortBy('value', $row['columns'], 'asc');
                        $row['value'] = $data[sizeof($row['columns'])-1]['value'];
                        array_push($this->view->dataRow, $row);
                    }
                }
            }
            
            $this->view->mainData = $this->view->dataRow;
            
            $response = array(
                'Header' => '',
                'Html' => $this->view->renderPrint('ea/matrix', self::$viewPath),
                'Title' => $this->view->title, 
                'Type' => $this->view->chartType,
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            
            echo json_encode($response); exit;
            
        } elseif ($this->view->chartType === 'footprint') {
            
            $this->footPrint($this->view->templateId);
        }
    }
    
    public function dataBankView($workSpaceParam = null) {
        
        $this->view->smsTypeData = array();
        $this->view->uniqId = getUID();
        $this->view->metaDataId = getUID();
        $this->view->title = 'Basic tree layout';
        $this->view->isAjax = true;
        
        if ($workSpaceParam) {
            parse_str($workSpaceParam, $workSpaceParamArr);
            $this->view->selectedRow = $workSpaceParamArr['workSpaceParam'];
        } else {
            $postData = Input::postData();
            $this->view->selectedRowData = Arr::decode($postData['selectedRow']);
            $this->view->selectedRow = $this->view->selectedRowData['dataRow'];
        }
        
        $processData = $this->db->GetRow("SELECT META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = " . $this->view->selectedRow['trcprocessid']);
        $param = array('branchId' => $this->view->selectedRow['id']);
        
        $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS,  $processData['META_DATA_CODE'], 'return', $param, 'serialize');
        
        if ($workSpaceParam)  {
            $this->view->data = isset($result['result']) ? $result['result'] : array();
            $response = array('Html' => $this->view->renderPrint('databank/view4', "middleware/views/widget/"), '$result' => $result);
            echo json_encode($response); exit;
        } else {
            
            if (!is_ajax_request()) {
                $this->view->isAjax = false;
                $this->view->data = $result['result'];
                $this->view->render('header');
                $this->view->render('databank/view4', "middleware/views/widget/");
                $this->view->render('footer');
            } else {
                $this->view->data = $result['result'];
                $this->view->render('databank/view4', "middleware/views/widget/");
            }
        }
    }
    
    public function footPrint($id) {
        
        $this->view->smsTypeData = array();
        $this->view->uniqId = $this->view->dataViewId = getUID();
        $this->view->title = 'Business Strategy Footprint';
        $this->view->js = array(
            'core/js/plugins/visualization/d3/d3.min.js',
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
        );
        $this->view->isAjax = true;
        
        $this->view->processIdHeader = '1564993094895657';
        $this->view->processIdBody = '1565164294233';
        
        $this->view->templateId = '1560599377133';
        $this->view->metaGroupId = '1564993031312681';
        
        $this->view->legendData = Input::post('legendData');
        $this->view->controlsData = $this->model->getFormDataModel($id);
        
        $this->view->id = is_array($this->view->controlsData['templateids']) ? $this->view->controlsData['templateids'][0]['id'] : '';
        $this->view->dataRow = $this->model->getBodyData1Model($this->view->id, $this->view->legendData, $this->view->processIdBody);
        $this->view->controlsData = $this->view->controlsData['filters'];

        $this->view->searchForm = $this->view->renderPrint('ea/searchForm', self::$viewPath);
        
        if (!is_ajax_request()) {
            
            $this->view->isAjax = false;
            
            $this->view->render('header');
            $this->view->render('ea/footPrint', self::$viewPath);
            $this->view->render('footer');
            
        } else {
            $response = array(
                'Header' => '',
                'Html' => $this->view->renderPrint('ea/footPrint', self::$viewPath),
                'Title' => $this->view->title, 
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response); exit;
        }
    }
    
    public function searchFootPrint() {
        $postData = Input::postData();
        $this->view->uniqId = $this->view->dataViewId = $postData['uniqId'];
        $this->view->processIdBody = '1565164294233';
        $this->view->id = '1560599363603';
        
        $this->view->dataRow = $this->model->getBodyData1Model($this->view->id, '', $this->view->processIdBody, $postData);
        echo $this->view->renderPrint('ea/footPrintSearchView', self::$viewPath); exit;
    }    
    
    public function getTreeJsonData() {
        
        $this->view->uniqId = getUID();
        $this->view->processId = Input::post('processId');// '1564737694730700';
        $this->view->id = Input::post('id');
        $this->view->legendData = Input::post('legendData');
        $addParam = array();
        
        if (!$this->view->id) {
            $addParam['wfmStatusIds'] = array(array('id' => '1563624303723224'));
        } else {
            $addParam = array();
        }
        
        if (Input::postCheck('categoryId') && !Input::isEmpty('categoryId')) {
            $addParam['categoryIds'] = array (0 =>  array ('id' => Input::post('categoryId')));
        }
        
        $this->view->getBodyData = $this->model->getBodyDataModel($this->view->id, $this->view->legendData, $this->view->processId, $addParam);
        
        $response = array('status' => 'success', 'data' => isset($this->view->getBodyData['data']) ? $this->view->getBodyData['data'] : array(), 'type' => 'success', 'message' => isset($this->view->getBodyData['msg']) ? $this->view->getBodyData['msg'] : Lang::line('msg_success'));
        echo json_encode($response); exit;
    }
    
    public function renderContentForm() {
        $this->view->uniqId = getUID();
        $this->view->isAjax = true;
        $this->view->title = '';
        
        $this->view->id = Input::post('menuId');
        $this->view->legendData = Input::post('legendData');
        $this->view->headerLegendData = Input::post('headerLegendData');
        $this->view->chartType = Str::lower($this->view->headerLegendData['charttype']);
        
        if ($this->view->chartType === 'streelayout') {
            $this->view->processId = '1565164294168';
            $this->view->tree = $this->view->renderPrint('ea/treeD3', self::$viewPath);
            $response = array(
                'Html' => $this->view->renderPrint('ea/treeD3Layout', self::$viewPath),
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
        } else {
            $this->view->processId = '1564737694730700';
            $this->view->tree = $this->view->renderPrint('ea/tree', self::$viewPath);
            $response = array(
                'Html' => $this->view->renderPrint('ea/treeLayout', self::$viewPath),
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
        }
        echo json_encode($response); exit;
    }
    
    public function treeTemplate() {
         
        $this->view->smsTypeData = array();
        $this->view->uniqId = getUID();
        $this->view->title = 'ОНТОЛОГИ ГРАФ';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = array_unique(array_merge(array(
            'core/js/plugins/visualization/d3/d3.min.js',
        ), AssetNew::metaOtherJs()));
        $this->view->fullUrlJs = array_unique(array_merge(array(
            'middleware/assets/js/mdtaskflow.js'
            ), AssetNew::amChartJs()
        ));
        
        $this->view->isAjax = is_ajax_request();
        $this->view->id = '';
        $this->view->processId = '1565690427926768';
        $this->view->templateId = '1560599377133';
        $this->view->metaGroupId = '1564993031312681';
        $this->view->fillColor = true;
        
        $this->view->getHeaderLegendData = $this->model->getHeaderLegendDvModel('1565786693276522');
        $this->view->legendData = array();
        
        if (isset($this->view->getHeaderLegendData) && $this->view->getHeaderLegendData) {
            foreach ($this->view->getHeaderLegendData as $key => $row) {
                $row['color'] = random_color();
                $row['criteria'] = '';
                $row['ordernum'] = $key;
                array_push($this->view->legendData, $row);
            }
        }
        
        $this->view->colorPath = Arr::groupByArrayOnlyRow($this->view->legendData, 'id', 'color');
        $this->view->chartType = 'streelayout';
        
        if (!$this->view->isAjax) {
            
            $this->view->tree = $this->view->renderPrint('ea/treeD3', self::$viewPath);

            $this->view->render('header');
            $this->view->render('ea/treeD3Layout', self::$viewPath);
            $this->view->render('footer');
            
        } else {
            
            $this->view->tree = $this->view->renderPrint('ea/treeD3', self::$viewPath);
            $response = array(
                'Header' => '',
                'Html' => $this->view->renderPrint('ea/treeD3Layout', self::$viewPath),
                'Title' => $this->view->title, 
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response); exit;
        }
    }
    
    public function getMenuDataByTemplate() {
        $resultArr = array();
        $param = array(
            'templateId' => Input::post('templateId')
        );
        
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'getEATemplateRelatedService', $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            $resultArr = $data['result'];
            unset($resultArr['aggregatecolumns']);
        }
        
        jsonResponse($resultArr);
    }        
    
    public function relationD3Tree() {
        $postData = Input::postData();
        $response = $this->model->saveRelationD3Tree($postData);
        echo json_encode($response); exit;
    }
    
    public function radarChart() {
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->isAjax = is_ajax_request();
        
        $radarData1 = $this->radarChartData(false);
        
        $this->view->title = $radarData1['title'];
        $this->view->radarChart1 = $radarData1['data'];
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('radar/d3', self::$viewPath);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function radarChartData($isRequest = true) {
        
        $title = 'Radar Chart';
        $criteria = array();
        
        if (Input::post('isWorkFlow') == '1') {
            $selectedRow = Input::post('selectedRow');
            $decodedRow = Arr::decode($selectedRow);
            
            if (is_array($decodedRow) && isset($decodedRow['workspaceId']) && isset($decodedRow['dataRow'])) {
                
                $this->load->model('mdobject', 'middleware/models/');
                $getWorkSpaceDvParamMap = $this->model->getWorkSpaceDvParamMap('1602473547402256', $decodedRow['workspaceId']);
                
                if ($getWorkSpaceDvParamMap) {
                    
                    foreach ($getWorkSpaceDvParamMap as $wsRow) {
                        $lowerKey = strtolower($wsRow['FIELD_PATH']);
                        
                        if (isset($decodedRow['dataRow'][$lowerKey])) {
                            $criteria[$wsRow['PARAM_PATH']][] = array(
                                'operator' => '=',
                                'operand' => $decodedRow['dataRow'][$lowerKey]
                            );
                        }
                    }
                }
            }
        }
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $radarData1 = $this->model->getDataMartDvRowsModel('1587038009909', $criteria);
        
        if (isset($radarData1[0]['title'])) {
            $title = $radarData1[0]['title'];
        }
        
        $arr1 = $arr2 = array();
        $k = 0;
        
        foreach ($radarData1 as $row) {
            
            $name = $row['data'];
            
            if (!isset($arr1[$name])) {
                
                $arr2[$k]['key'] = $name;
                $arr2[$k]['values'][] = $row;
                $arr1[$name] = $k;
                
                $k++;
                
            } else {
                
                $arr2[$arr1[$name]]['key'] = $name;
                $arr2[$arr1[$name]]['values'][] = $row;
            }
        }
        
        if ($isRequest) {
            jsonResponse($arr2);
        } else {
            return array('title' => $title, 'data' => $arr2);
        }
    }

    public function hrmTimesheetLogLoad() {
        
        Auth::handleLogin();
        
        if (Input::postCheck('yearMonth')) {
            $this->view->filterStartDate = Input::post('yearMonth').'-01';
            $this->view->filterEndDate = Input::post('yearMonth').'-'.date('t', strtotime($this->view->filterStartDate));
        } else {
            $this->view->filterStartDate = Date::currentDate('Y-m').'-01';
            $this->view->filterEndDate = Date::currentDate('Y-m').'-'.date('t', strtotime($this->view->filterStartDate));
        }
        
        $criteria = array(
            'sessionEmployeeId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionEmployeeId()
                )
            ), 
            'filterStartDate' => array(
                array(
                    'operator' => '=',
                    'operand' => $this->view->filterStartDate
                )
            ), 
            'filterEndDate' => array(
                array(
                    'operator' => '=',
                    'operand' => $this->view->filterEndDate
                )
            )
        );

        $sidebarDvId = '1525387880376';
        $calendarDvId = '1525387904712';
        
        $this->view->startBgColor = Config::getFromCacheDefault('startBgColor', null, '#199ec7');
        $this->view->endBgColor = Config::getFromCache('endBgColor',null,'#1BBC9B'); 

        $sidebarData = $this->model->loadListModel($sidebarDvId, $criteria);
        $this->view->sidebarLabelName = $this->model->getHeaderLabelNameModel($sidebarDvId);
        $this->view->sidebarData = isset($sidebarData[0]) ? $sidebarData[0] : array();
        
        $this->view->sidebarHtml = $this->view->renderPrint('calendar/hrmTimesheetLogSidebar', self::$viewPath);
        
        $this->view->calendarData = $this->model->loadListModel($calendarDvId, $criteria);
    }

    public function hrmTimesheetLogJson() {
        
        self::hrmTimesheetLogLoad();
        
        $response = array(
            'events' => $this->view->calendarData, 
            'sidebarHtml' => $this->view->sidebarHtml
        );
        
        echo json_encode($response); exit;
    }
    public function getweatherFileIcon($id = '')
    {
        $data = array(
            array('id' => '2', 'name' => 'Цэлмэг', 'filepath' => 'assets/custom/img/weather/weather-01.png',),
            array('id' => '3', 'name' => 'Үүлэрхэг', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '5', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '7', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '9', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png',),
            array('id' => '10', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png',),
            array('id' => '20', 'name' => 'Үүл багаснa', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '23', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '24', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '27', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '28', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '60', 'name' => 'Бага зэргийн бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '61', 'name' => 'Бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '63', 'name' => 'Их бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '65', 'name' => 'Хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '66', 'name' => 'Их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '67', 'name' => 'Аадар их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '68', 'name' => 'Их усархаг бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '71', 'name' => 'Цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '73', 'name' => 'Их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '75', 'name' => 'Аадар их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '80', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '81', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '82', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '83', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '84', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '85', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '86', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '87', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '90', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '91', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '92', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '93', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '94', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '95', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '96', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '97', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',)
        );
        $data = Arr::groupByArrayOnlyRow($data, 'id', false);
        $response = $data;

        if ($id) {
            $response = isset($data[$id]) ? $data[$id]['filepath'] : 'assets/custom/img/weather/weather-01.png';
        }

        return $response;
    }
    public function getForecast5day($cityName = 'Улаанбаатар')
    {
        $currentDate = Date::currentDate('y_m_d');

        $cache = phpFastCache();
        $data = $cache->get('bpForecast5day_' . $currentDate);

        if ($data == null) {

            $url = 'http://tsag-agaar.gov.mn/forecast_xml';
            $result = @file_get_contents($url, false, stream_context_create(array('http' => array('timeout' => 3))));
            
            if ($result !== false) {
                $data = Xml::createArray($result);
            }

            if ($data) {
                $cache->set('bpForecast5day_' . $currentDate, $data, '144000000');
            }
        }

        (array) $mainData = array();
        if (isset($data['xml']['forecast5day'])) {
            foreach ($data['xml']['forecast5day'] as $key => $row) {
                if (isset($row['city']) && $row['city'] === $cityName && isset($row['data']['weather'])) {
                    foreach ($row['data']['weather'] as $row) {
                        $row['filepath'] = self::getweatherFileIcon($row['phenoIdDay']);
                        array_push($mainData, $row);
                    }
                }
            }
        }

        return $mainData;
    }
    
    public function agentdashboard() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $this->view->agent = true;
        
        $this->view->css = array_unique( AssetNew::metaCss());
        
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
        
        $this->view->currentDate = Date::currentDate('Y-m-d');
        $this->view->currentTime = Date::currentDate('H:i');

        if (Input::postCheck('yearMonth')) {
            $this->view->filterStartDate = Input::post('yearMonth').'-01';
            $this->view->filterEndDate = Input::post('yearMonth').'-'.date('t', strtotime($this->view->filterStartDate));
        } else {
            $this->view->filterStartDate = Date::currentDate('Y-m').'-01';
            $this->view->filterEndDate = Date::currentDate('Y-m').'-'.date('t', strtotime($this->view->filterStartDate));
        }
        $this->view->weatherData = self::getForecast5day();

        $this->view->isAjax = is_ajax_request();

        $criteria = array(
            'filterSessionEmployeeId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionEmployeeId()
                )
            ), 
            'filterStartDate' => array(
                array(
                    'operator' => '=',
                    'operand' => $this->view->filterStartDate
                )
            ), 
            'filterEndDate' => array(
                array(
                    'operator' => '=',
                    'operand' => $this->view->filterEndDate
                )
            )
        );

        $sidebarDvId = '1525387880376';
        $calendarDvId = '1564710586209';

        $this->view->layoutPositionArr = $this->model->dashboardLayoutAgentDataModel('', array(), '0', '1');
        $this->view->uniqId = getUID();
        $this->view->startBgColor = '';
        $this->view->endBgColor = '';
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        $this->view->render('/dashboard/index', self::$viewPath);
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }

    }

    public function v2($layoutId) {

        $layoutConfig = $this->prepareLayoutData($layoutId);
        $style = ''; //($layoutId === '17091131987379') ? 'width: 80%; margin: 0 auto;' : '';
        $sectionHtml = '<main class="h-full w-full page-main-layout-'.$layoutId.'" style="background-color:#F3F4F6; '. $style .'">';
        $sectionHtml .= $this->layoutSection($layoutConfig["layout"], issetParam($layoutConfig["className"]), $layoutConfig);
        $sectionHtml .= '</main>';
        
        $this->view->isAjax = is_ajax_request();
        $this->view->sectionHtml = $sectionHtml;
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();        
        $this->view->metaDataId = $layoutId;        
                            
        $moduleId = Input::get('mmid');
        
        $mdmeta = &getInstance();
        $mdmeta->load->model('mdmeta', 'middleware/models/');
        $moduleInfo = $mdmeta->model->getModuleNameModel($layoutId);
        $this->view->title = Lang::line($moduleInfo['META_DATA_NAME']);

        if ($moduleId) {
            $moduleInfo = $mdmeta->model->getModuleNameModel($moduleId);
            $this->view->moduleName = Lang::line($moduleInfo['META_DATA_NAME']);
        } else {
            $this->view->moduleName = '';
        }        

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        if (!$this->view->isAjax) {
            $this->view->render('indexv2', self::$viewPath);
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('indexv2', self::$viewPath),
            );
            echo json_encode($response); exit;        
        }

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }        
    }

    public function layoutSection($section, $customClassName = "", $layoutConfig, $rowConfig = []) {
        $sectionHtml = "<section style='".issetParam($rowConfig["style"])."' class='".($customClassName?$customClassName:"grid grid-cols-12 w-full h-full gap-x-6")."'>";
        
        foreach ($section as $row) {
            if (issetParam($row["children"])) {
                $sectionHtml .= $this->layoutSection($row["children"], issetParam($row["className"]), $layoutConfig, $row);
            } else {
                preg_match('/section(.*)/', $row["sectionCode"], $sectionCode);
                $sectionList = Arr::groupByArray($layoutConfig["meta_bp_layout_section"], "code");
                
                if (isset($sectionList[$sectionCode[1]])) {
                    $sectionCount = count($sectionList[$sectionCode[1]]["rows"]);
                    $secRowClass = json_decode($sectionList[$sectionCode[1]]["row"]["otherattr"], true);
                    $sectionAttr = htmlentities(json_encode(array('layoutConfig' => $sectionList[$sectionCode[1]], 'layoutRow' => $row)), ENT_QUOTES, 'UTF-8');
                    
                    if (empty($secRowClass) || !array_key_exists("meta", $secRowClass)) {
                        $sectionHtml .= "<section data-metadataid='". issetParam($sectionList[$sectionCode[1]]["row"]['metadataid']) ."' data-metatypeid='". issetParam($sectionList[$sectionCode[1]]["row"]['metatypeid']) ."' data-attr='". $sectionAttr ."' data-sectioncode='".$row["sectionCode"]."' class='mb-6 ".issetParam($row["className"])."' style='".issetParam($row["style"])."'>";
                        $sectionHtml .= "<div class='".($sectionCount > 1 ? 'grid grid-cols-12' : '')."' style='".($sectionCount > 1 ? 'gap:1.3rem' : '')."'>";
                        foreach ($sectionList[$sectionCode[1]]["rows"] as $secRow) {
                            $jsonAttr = json_decode($secRow["otherattr"], true);
                            $sectionHtml .= "<div data-widgetcode='".$secRow['widgetcode']."' class='w-full h-full ".issetParam($jsonAttr['className'])."'>";
                            $sectionHtml .= (new Mdwidget())->widgetStandart($secRow, $jsonAttr);
                            $sectionHtml .= "</div>";
                        }
                        $sectionHtml .= "</div>";
                        $sectionHtml .= "</section>";
                    }
                }
            }
        }
        $sectionHtml .= "</section>";        

        return $sectionHtml;
    }

    public function layoutBySection() {
        $postData = Input::postData();
        $sectionConfig = json_decode($postData["config"], true);
        $layoutConfig = $sectionConfig['layoutConfig'];
        $layoutRow = $sectionConfig['layoutRow'];
        $sectionCount = count($sectionConfig['layoutConfig']["rows"]);
        
        $paging = array('offset' => 1, 'pageSize' => 20);
        $criteria = array();
        
        if (issetParam($postData['filterDate'])) {
            $tmp = array(
                'filterDate' => array(
                    array(
                        'operator' => '=',
                        'operand' => $postData['filterDate']
                    )
                )
            ); 
            $criteria = array_merge($criteria, $tmp);
        }

        if (issetParam($postData['filterPage'])) {
            $paging['offset'] = $postData['filterPage']; 
        }

        $html = '';
        if (issetParam($postData['filterPage']) === '') {
            $html .= "<div class='".($sectionCount > 1 ? 'grid grid-cols-12' : '')."' style='".($sectionCount > 1 ? 'gap:1.3rem' : '')."'>";
            
            foreach ($layoutConfig["rows"] as $secRow) {
                $jsonAttr = json_decode($secRow["otherattr"], true);
                $html .= "<div data-widgetcode='".$secRow['widgetcode']."' class='w-full h-full ".issetParam($jsonAttr['className'])."'>";
                $html .= (new Mdwidget())->widgetStandart($secRow, $jsonAttr, $criteria, $paging);
                $html .= "</div>";
            }
            $html .= "</div>";
        } else {
            foreach ($layoutConfig["rows"] as $secRow) {
                $jsonAttr = json_decode($secRow["otherattr"], true);
                $html .= (new Mdwidget())->widgetStandart($secRow, $jsonAttr, $criteria, $paging);
            }
        }
        
        $response = array(
            'status' => 'success', 
            'Html' => $html
        );
        
        convJson($response);
    }

    public function prepareLayoutData($layoutId) {
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'layoutHdr_004', ["filtermetadataid"=>$layoutId]);
        $layoutNemgoo = json_decode($result["result"]["layoutnemgoo"], true);
        $layoutNemgoo["meta_bp_layout_section"] = $result["result"]["meta_bp_layout_section"];
        
        if (isset($layoutNemgoo["master"]) && $layoutNemgoo["master"]["metaid"]) {
            $resultMaster = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'layoutHdr_004', ["filtermetadataid"=>$layoutNemgoo["master"]["metaid"]]);
            $layoutNemgooMaster = str_replace('"body"', '"body", "children":' . json_encode($layoutNemgoo["layout"]), $resultMaster["result"]["layoutnemgoo"]);
            $layoutNemgooMaster = str_replace("'body'", "'body', \"children\":" . json_encode($layoutNemgoo["layout"]), $layoutNemgooMaster);

            $layoutSectionDtl = $layoutNemgoo["meta_bp_layout_section"];
            $layoutNemgoo = json_decode($layoutNemgooMaster, true);
            $layoutNemgoo["meta_bp_layout_section"] = array_merge($layoutSectionDtl, issetParamArray($resultMaster["result"]["meta_bp_layout_section"]));
        }

        return $layoutNemgoo;        
    }
}
