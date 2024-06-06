<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Mdreport extends Controller {

    private $viewPath = "middleware/views/report/";

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
//              echo Session::get(SESSION_PREFIX.'username'); die;
        $this->view->title = 'report';

        

        $this->view->tables = $this->model->getDataMartList();
        $this->view->childs = array('username', 'departmentname', 'lastname', 'firstname', 'rolename', 'date');
//        $this->view->childs = array('username' => 'Хэрэглэгчийн нэр', 'departmentname' => 'Хэлтэсийн нэр', 'lastname' => 'Овог', 'firstname' => Lang::line('META_00125'), 'rolename' => 'Албан тушаал', 'date' => 'Огноо');
//        var_dump(json_encode($this->view->childs));die;
//        $childsArrayKey = array_keys($this->view->childs);
//        $childsArrayKey = array('username', 'departmentname', 'lastname', 'firstname', 'rolename', 'date');
//        var_dump($childsArrayKey);die;
        $this->view->childsInfo = json_encode($this->view->childs);
        $this->view->row['modelId'] = 0;
        $this->view->row['modelName'] = "";
        $this->view->row['viewName'] = "";
        $this->view->row['headerHtml'] = "";

        //var_dump($_GET);
        // die();
        if (Input::get('id')) {
            //var_dump(Input::get('id'));
            //die();

            $this->view->row = $this->model->getReport(Input::get('id'));
            //   var_dump( $this->view->row);
            //  die();
        }

        $this->view->css = array(
            'custom/addon/plugins/jquery-tree/jquery.treeview.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css',
            'custom/addon/plugins/jquery-nestable/jquery.nestable.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/orb/deps/bootstrap-3.3.1/css/bootstrap-theme.css',
            'custom/addon/plugins/orb/dist/orb.css',
            'custom/css/fileexplorer.css',
            'middleware/assets/css/main.css'

        );
        
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/jquery-tree/jquery.treeview.js',
            'custom/addon/plugins/jquery-nestable/jquery.nestable.js',
            'admin/pages/scripts/ui-nestable.js',
            'custom/addon/plugins/jsPDF/dist/jspdf.min.js',
            'custom/addon/plugins/html2canvas/html2canvas.js',
            'custom/addon/plugins/jsFiddle/sql-parser.js',
            '../../middleware/assets/js/rmbase.js',
            '../../middleware/assets/js/rmreport.js',
            'custom/addon/plugins/orb/deps/react-0.12.2.js',
            'custom/addon/plugins/orb/dist/orb.js'
        );

        $this->view->fullUrlJs = array(
            'middleware/assets/js/mdmetadata.js'
        );

        $this->view->render('header');
        $this->view->render('index', $this->viewPath);
        $this->view->render('footer');
    }

       public function indexPivot() {

        $this->view->title = 'report';

        $this->view->tables = $this->model->getDataMartList();

        $this->view->row['modelId'] = 0;
        $this->view->row['modelName'] = "";
        $this->view->row['viewName'] = "";
        $this->view->row['headerHtml'] = "";

        //var_dump($_GET);
        // die();
        if (Input::get('id')) {
            //var_dump(Input::get('id'));
            //die();

            $this->view->row = $this->model->getReport(Input::get('id'));
            //   var_dump( $this->view->row);
            //  die();
        }

        $this->view->css = array(
            'custom/addon/plugins/jquery-tree/jquery.treeview.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css',
            'custom/addon/plugins/jquery-nestable/jquery.nestable.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/orb/deps/bootstrap-3.3.1/css/bootstrap.css',
            'custom/addon/plugins/orb/deps/bootstrap-3.3.1/css/bootstrap-theme.css',
            'custom/addon/plugins/orb/dist/orb.css',
            'custom/css/fileexplorer.css'
        );
        
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/jquery-tree/jquery.treeview.js',
            '../../middleware/assets/js/mdmetadata.js',
            'custom/addon/plugins/jquery-nestable/jquery.nestable.js',
            'custom/addon/plugins/ckeditor/4.5.4/ckeditor.js',
            'custom/addon/plugins/ckeditor/4.5.4/adapters/jquery.js',
            'admin/pages/scripts/ui-nestable.js',
            'admin/pages/scripts/ui-nestable.js',
            'custom/addon/plugins/jsPDF/dist/jspdf.min.js',
            'custom/addon/plugins/html2canvas/html2canvas.js',
            'custom/addon/plugins/jsFiddle/sql-parser.js',
            '../../middleware/assets/js/rmbase.js',
            '../../middleware/assets/js/rmreport.js',
            'custom/addon/plugins/orb/deps/react-0.12.2.js',
            'custom/addon/plugins/orb/dist/orb.js'
        );

        $this->view->fullUrlJs = array(
            'middleware/assets/js/mdmetadata.js'
        );

        $this->view->render('header');
        $this->view->render('indexPivot', $this->viewPath);
        $this->view->render('footer');
    }

    
    public function reportList() {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Тайлангийн жагсаалт';

        $this->view->css = array(
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js'
        );

        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isDelete = true;

        $response = array(
            'Html' => $this->view->renderPrint('reportList', $this->viewPath),
            'dialogTitle' => 'report model list',
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function reportModelDataGrid() {
        $data = $this->model->reportDataGridModel();
        echo json_encode($data);
    }

    public function report() {
        $this->view->title = 'report';

        $this->view->css = array('custom/css/fileexplorer.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/bootstrap-toastr/toastr.css');
        $this->view->js = array(
            '../../middleware/assets/js/rmbase.js',
            '../../middleware/assets/js/rmchart.js',
            '../../middleware/assets/js/rmreport.js',
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
            'custom/addon/plugins/bootstrap-toastr/toastr.js'
        );

        $this->view->modelList = $this->model->getReportSourceList();
        $this->view->templateList = $this->model->getReportTemplateList();

        $this->view->row['modelId'] = 0;
        $this->view->row['modelName'] = "-";
        if (Input::postCheck('reportModelId')) {
            $this->view->row['modelId'] = Input::post('reportModelId');

            foreach ($this->view->modelList as $key) {
                if ($key['id'] == $this->view->row['modelId']) {
                    $this->view->row['modelName'] = $key['modelName'];
                }
            }
        }

        if (!is_ajax_request())
            $this->view->render('header');

        $this->view->render('report', $this->viewPath);

        if (!is_ajax_request())
            $this->view->render('footer');
    }

    public function drillDownForm() {

        $rowValues = $_POST['rowValues'];
        $modelId = Input::post('modelId');
        $filters = array();
        parse_str(Input::post('filterValues'), $filters);

//        var_dump($modelId);
//        var_dump($filters);
//        var_dump($rowValues);
//        die();
//        
        $result = $this->model->getReportSource($modelId, $filters, $rowValues);
        $this->view->row = $result;
        $response = array(
            'Html' => $this->view->renderPrint('drillDown', $this->viewPath),
            'dialogTitle' => 'drillDownForm',
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function test() {
        $this->view->title = 'report';

        $this->view->css = array('custom/css/fileexplorer.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css');
        $this->view->js = array(
            '../../middleware/assets/js/rmBase.js',
            '../../middleware/assets/js/rmChart.js',
            '../../middleware/assets/js/rmReport.js',
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/highcharts/js/highcharts.js',
            'custom/addon/plugins/highcharts/js/modules/exporting.js',
            'custom/addon/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js'
        );
        
        if (!is_ajax_request())
            $this->view->render('header');

        $this->view->render('test', $this->viewPath);

        if (!is_ajax_request())
            $this->view->render('footer');
    }

    public function chart() {
        $this->view->title = 'report';

        $this->view->css = array('custom/css/fileexplorer.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css');
        $this->view->js = array(
            '../../middleware/assets/js/rmbase.js',
            '../../middleware/assets/js/rmchart.js',
            '../../middleware/assets/js/rmreport.js',
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/highstock/js/highstock.js',
            'custom/addon/plugins/highstock/js/modules/exporting.js',
            'custom/addon/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js'
        );

        $this->view->modelList = $this->model->getReportSourceList();

        $this->view->chartTemplateList = array();


        $this->view->chartTemplateList[0] = array('code' => 'table', 'name' => 'Хүснэгт', 'icon' => 'assets/core/global/img/meta/file.png');
        $this->view->chartTemplateList[1] = array('code' => 'pie', 'name' => 'Pie', 'icon' => 'middleware/assets/img/chartIcons/pie.png');
        $this->view->chartTemplateList[2] = array('code' => 'area', 'name' => 'Area', 'icon' => 'middleware/assets/img/chartIcons/area.png');
        $this->view->chartTemplateList[3] = array('code' => 'line', 'name' => 'Зураасан', 'icon' => 'middleware/assets/img/chartIcons/line.png');
        $this->view->chartTemplateList[4] = array('code' => 'column', 'name' => 'Баганан', 'icon' => 'middleware/assets/img/chartIcons/column.png');

        $this->view->row['modelId'] = 0;

        $this->view->row['id'] = 0;
        $this->view->row['modelName'] = "-";
        $this->view->row['chartName'] = "";
        $this->view->row['chartType'] = "pie";
        $this->view->row['valueColumnId'] = 0;



        if (Input::postCheck('reportModelId')) {
            $this->view->row['modelId'] = Input::post('reportModelId');

            foreach ($this->view->modelList as $key) {
                if ($key['id'] == $this->view->row['modelId']) {
                    $this->view->row['modelName'] = $key['modelName'];
                }
            }
        }

        if (Input::get('id')) {
            $this->view->row = $this->model->getChart(Input::get('id'));
        }

        if (!is_ajax_request())
            $this->view->render('header');

        $this->view->render('chart', $this->viewPath);

        if (!is_ajax_request())
            $this->view->render('footer');
    }

    public function chartList() {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Тайлангийн жагсаалт';

        $this->view->css = array(
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js'
        );

        $this->view->isEdit = true;
        $this->view->isDelete = true;
//
        $response = array(
            'Html' => $this->view->renderPrint('chartList', $this->viewPath),
            'dialogTitle' => 'chart list',
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function chartDataGrid() {
        $data = $this->model->chartDataGridModel();
        echo json_encode($data);
    }

    public function template() {
        $this->view->title = 'report';

        $this->view->css = array(
            'custom/addon/plugins/jquery-nestable/jquery.nestable.css',
            'custom/css/fileexplorer.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-nestable/jquery.nestable.js',
            'custom/addon/plugins/ckeditor/4.5.4/ckeditor.js',
            'custom/addon/plugins/ckeditor/4.5.4/adapters/jquery.js',
            'admin/pages/scripts/ui-nestable.js',
            'admin/pages/scripts/ui-nestable.js',
            'custom/addon/plugins/jsPDF/dist/jspdf.min.js',
            'custom/addon/plugins/html2canvas/html2canvas.js',
            'custom/addon/plugins/jsFiddle/sql-parser.js',
            '../../middleware/assets/js/rmreport.js'
        );
        $this->view->row['templateId'] = 0;

        $this->view->render('header');
        $this->view->render('template', $this->viewPath);
        $this->view->render('footer');
    }

    public function loadDashboardChart() {

        $this->view->js = array(
            '../../middleware/assets/js/rmBase.js',
            '../../middleware/assets/js/rmChart.js',
            '../../middleware/assets/js/rmReport.js',
//              'custom/addon/plugins/highstock/js/highstock.js',
            'custom/addon/plugins/highstock/js/modules/exporting.js'
        );

       
        if (Input::postCheck('chartId')) {
            $this->view->row = $this->model->getChart(Input::post('chartId'));
            if ($this->view->row != null) {
                $this->view->row['rootElement'] = Input::float('rootElement');
                
                if (Input::post('viewType') == 'clean') {
                    $response = array(
                        'Html' => $this->view->renderPrint('chartCleanView', $this->viewPath)
                    );
                } else {
                    $response = array(
                        'Html' => $this->view->renderPrint('chartView', $this->viewPath)
                    );
                }

                echo json_encode($response);
            }
        }
    }

    public function saveChart() {
        $params = array();
        parse_str(Input::post('values'), $params);

        if ($params['rmchartName'] == '') {
            echo json_encode(array('status' => 'error', 'message' => "Нэр оруулаагүй байна"));
        } else {



            $model = array(
                'chartId' => $params['rmchartId'],
                'chartName' => $params['rmchartName'],
                'chartType' => $params['templateChart'],
                'modelId' => $params['modelId'],
                'valueColumnId' => $params['valueColumns']
            );

            $result = $this->model->saveChart($model);

//var_dump($result);
//die();

            if ($result != null) {
                echo json_encode(array('status' => 'success', 'chartId' => $result, 'message' => Lang::line('msg_save_success')));
            } else {
                echo json_encode(array('status' => 'error', 'message' => Lang::line('msg_save_error')));
            }
        }
    }

    public function saveReport() {
        $params = array();
        parse_str(Input::post('values'), $params);

        if ($params['reportModelName'] == '') {
            echo json_encode(array('status' => 'error', 'message' => "Нэр оруулаагүй байна"));
        } else {
            $rows = array();
            $cols = array();
            $facts = array();
            $filters = array();
            $headerHtml = '';

            $modelId = $params['modelId'];
            if ($modelId == '')
                $modelId = '0';
            $model = array(
                'modelId' => $modelId,
                'tableName' => $params['tableName'],
                'modelName' => $params['reportModelName']);

            foreach ($_POST['rows'] as $key) {
                array_push($rows, $key);
            }

            foreach ($_POST['cols'] as $key) {
                array_push($cols, $key);
            }


            if (array_key_exists('facts', $_POST)) {
                foreach ($_POST['facts'] as $key) {
                    array_push($facts, $key);
                }
            }

//var_dump($_POST['filters']);
//die();

            if (array_key_exists('filters', $_POST)) {
                foreach ($_POST['filters'] as $key) {
                    array_push($filters, $key);
                }
            }

            if (array_key_exists('headerHtml', $_POST)) {
                $headerHtml = $_POST['headerHtml'];
            }
            
            if (array_key_exists('footerHtml', $_POST)) {
                $footerHtml = $_POST['footerHtml'];
            }

              $result = $this->model->saveReport($model, $rows, $cols, $facts, $filters, $headerHtml, $footerHtml);
            if ($result != null) {
                echo json_encode(array('status' => 'success', 'modelId' => $result, 'message' => Lang::line('msg_save_success')));
            } else {
                echo json_encode(array('status' => 'error', 'message' => Lang::line('msg_save_error')));
            }
        }
    }

    public function saveReportTemplate() {
        $params = array();
        parse_str(Input::post('values'), $params);
        if ($params['templateName'] == '') {
            echo json_encode(array('status' => 'error', 'message' => "Нэр оруулаагүй байна"));
        } else {
            $template = array(
                'templateId' => $params['templateId'],
                'templateName' => $params['templateName'],
                'headerHtml' => Input::postNonTags('headerHtml'),
                'footerHtml' => Input::postNonTags('footerHtml'));

            $result = $this->model->saveReportTemplate($template);
            if ($result != null) {
                echo json_encode(array('status' => 'success', 'templateId' => $result, 'message' => Lang::line('msg_save_success')));
            } else {
                echo json_encode(array('status' => 'error', 'message' => Lang::line('msg_save_error')));
            }
        }
    }

    public function getReportSource() {

        $params = array();
        parse_str(Input::post('values'), $params);

        $filters = array();
        parse_str(Input::post('filters'), $filters);
//        var_dump($filters);die;
        $result = $this->model->getReportSource($params['modelId'], $filters, null);
        echo json_encode($result);
    }
    
    public function exportExcel() {
//      $aa = "Z";
//      var_dump(++$aa);die;
//        var_dump($_GET);die;
        $getValues = Input::get('values');
        $getFilters = Input::get('filters');

        $values = '';
        $filters = '';
        
        for($i = 0, $count = count($getValues), $checkCountLast = $count - 1; $i < $count; $i++){
          if($i !== $checkCountLast){
            $values .= $getValues[$i] . '&';
          } else {
            $values .= $getValues[$i];
          }
        }
        
        for($i = 0, $count = count($getFilters), $checkCountLast = $count - 1; $i < $count; $i++){
          if($i !== $checkCountLast){
            $filters .= $getFilters[$i] . '&';
          } else {
            $filters .= $getFilters[$i];
          }
        }
        $params = array();
        parse_str($values, $params);
        $filterz = array();
        $letterArray = array();
        parse_str($filters, $filterz);
        $result = $this->model->getExportExcelSource($params['modelId'], $filterz, null);
//        var_dump($result['headerHtml']);die;  
//        $data = json_encode($result);
          require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
          require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getActiveSheet()->setTitle('Test excel');
          $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', '№')
                  ->setCellValue('B1', 'Регистр')
                  ->setCellValue('C1', 'Эцэг (Эх)-ийн нэр')
                  ->setCellValue('D1', 'Нэр');
          
        if(!is_null($result['data'])){
          $letter = "A";
          $sizeOfData = sizeof($result['data'][0]);
          for( $k = 0; $k < $sizeOfData; $k++){
              ++$letter;
              array_push($letterArray, $letter);
          }
//          var_dump($letterArray); die;
          foreach($letterArray as $k => $v){
            for($i = 0, $count = count($result['data']); $i < $count; $i++){
              $arrayKeys = array_keys($result['data'][$i]);
              $arrayName = $arrayKeys[$k];
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . ($i + 2), $i + 1);
              
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($v. ($i + 2), isset($result['data'][$i][$arrayName]) ? $result['data'][$i][$arrayName] : '')
              ;
            }
          }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
    header('Content-Disposition: attachment;filename="testExcel.xlsx"');
    flush();
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    }
    }

    public function getReport() {
        $result = $this->model->getReport(Input::post('modelId'));
        echo json_encode($result);
    }

    public function getFilterArea() {

        $params = array();
        parse_str(Input::post('values'), $params);

        $cId = "";
        if (array_key_exists('chartId', $_POST)) {
            $cId = Input::post('chartId');
        }

        $filters = $this->model->getReportFilter($params['modelId']);
        $filterHtml = ' <form method="post" action="" id="filterForm' . $cId . '" class="form-horizontal" accept-charset="utf-8">';
        $filterHtml .= '<script>';
        $filterHtml .= 'function inputChange' . $cId . '(elem, metadata){
                            document.getElementById("MD1_' . $cId . '_"+metadata).type = "text";
                            if(elem.value+"" == "between")
                            {
                                document.getElementById("MD1_' . $cId . '_"+metadata).type = "text";
                                tempId = document.getElementById("MD1_' . $cId . '_"+metadata).id;
                                $("#s2id_"+tempId).show();
                                document.getElementById("MD2_' . $cId . '_"+metadata).type = "text";
                                tempId2 = document.getElementById("MD2_' . $cId . '_"+metadata).id;
                                $("#s2id_"+tempId2).show();
                            }
                            else
                            {
                                if(elem.value=="")
                                {
                                    document.getElementById("MD1_' . $cId . '_"+metadata).type = "hidden";
                                    tempId = document.getElementById("MD1_' . $cId . '_"+metadata).id;
                                    $("#s2id_"+tempId).hide();
                                }
                                else
                                {
                                    document.getElementById("MD1_' . $cId . '_"+metadata).type = "text";
                                    tempId = document.getElementById("MD1_' . $cId . '_"+metadata).id;
                                    $("#s2id_"+tempId).show();
                                }
                                document.getElementById("MD2_' . $cId . '_"+metadata).type = "hidden";
                                tempId = document.getElementById("MD2_' . $cId . '_"+metadata).id;
                                $("#s2id_"+tempId).hide();
                            }
                        }';
        $filterHtml .= '</script>';
        $filterTypes = array();
        array_push($filterTypes, array('id' => '=', 'name' => Lang::lineDefault('META_00134', 'Тэнцүү')));
        array_push($filterTypes, array('id' => '<>', 'name' => Lang::lineDefault('META_filter_00134', 'Ялгаатай')));
        array_push($filterTypes, array('id' => '>', 'name' => Lang::lineDefault('META_filter_00135', 'Их')));
        array_push($filterTypes, array('id' => '<', 'name' => Lang::lineDefault('META_filter_00136', 'Бага')));
        array_push($filterTypes, array('id' => 'like', 'name' => 'Агуулсан'));
        array_push($filterTypes, array('id' => 'between', 'name' => 'Хооронд'));
        $counter = 0;

//        if(sizeof($filters) == '0'){
//          
//        }
        $getReportViewName = $this->db->GetOne("SELECT DATA_MART_NAME FROM dm_report_model WHERE REPORT_MODEL_ID = '".$params['modelId']."'");
        $getReportId = $this->db->GetOne("SELECT REPORT_ID FROM rep_report WHERE VIEW_NAME = '".$getReportViewName."'");
        $getReportMonths = $this->db->GetAll("SELECT UPDATED_DATE FROM rep_report_data_update_history WHERE REPORT_ID = '".$getReportId."' ORDER BY UPDATED_DATE DESC");
        $this->reportMonthList = $getReportMonths;
//        var_dump($this->reportMonthList);die;
        $filterHtml .= '<div class="col-md-12"><div class="form-group row fom-row"><label class="col-form-label col-md-4"> 
                            <span>Тайлангийн огноо: </span></label><div class="col-md-8">';
//        $filterHtml .='<div class="col-md-4">';
//                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'reportMonth', 'id' => 'reportMonth', 'data' => $this->reportMonthList, 'op_value' => 'UPDATED_DATE', 'op_text' => 'UPDATED_DATE', 'class' => 'form-control select2me'));
//                          $filterHtml .= '<input class="form-control form-control-inline date-picker m-wrap" id="startDate" name="startDate" data-date-format="yyyy-mm-dd" size="16" type="text" value="">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_date1', 'class' => 'form-control '));
//                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .='</div></div></div></div>';
                          
        foreach ($filters as $key) {
            $filterHtml .= '<div class="col-md-12"><div class="form-group row fom-row"><label class="col-form-label col-md-4"> 
                            <span>' . $key['metadataName'] . '</span></label><div class="col-md-8">';
            $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][filterId]', 'id' => 'MD', 'class' => 'form-control col-md-9', 'value' => $key['filterId']));
            $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][metatype]', 'id' => 'MT', 'class' => 'form-control col-md-9', 'value' => $key['metatype']));
            
            switch ($key['metatype']) {
                case 'TABLE':
                case 'COMBO':
                    $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'value' => '=', 'class' => 'form-control'));
                    $filterHtml .='<div class="col-md-8">';
                    $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $key['filterId'], 'data' => $key['comboSource'], 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control'));
                    $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2', 'class' => 'form-control'));
                    $filterHtml .='</div>';
                    break;
                default:
                    $inputType = "";

                    if ($key['metatype'] == 'NUMBER')
                        $inputType = 'numberInit';

                    if ($key['metatype'] == 'DATE')
                        $inputType = 'dateInit';

                    if ($key['filterId'] == '0') {
                        $filterHtml .='<div class="col-md-0">';
                        $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'value' => '0'));
                        $filterHtml .='</div><div class="col-md-4">';
                        $filterHtml .= Form::text(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                        $filterHtml .='</div><div class="col-md-4">';
                        $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ', 'value' => $key['field']));
                        $filterHtml .='</div>';
                    } else {
                        if($key['field'] === 'PERSON_STATUS_CODE'){
                          $this->personStatusList = $this->model->getPersonStatusList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->personStatusList, 'op_value' => 'PERSON_STATUS_CODE', 'op_text' => 'PERSON_STATUS_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->personStatusList, 'op_value' => 'PERSON_STATUS_CODE', 'op_text' => 'PERSON_STATUS_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'PERSON_STATUS_NAME'){
                          $this->personStatusList = $this->model->getPersonStatusList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->personStatusList, 'op_value' => 'PERSON_STATUS_NAME', 'op_text' => 'PERSON_STATUS_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->personStatusList, 'op_value' => 'PERSON_STATUS_NAME', 'op_text' => 'PERSON_STATUS_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'NATIONALITY_CODE'){
                          $this->nationalityList = $this->model->getNationalityList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->nationalityList, 'op_value' => 'NATIONALITY_CODE', 'op_text' => 'NATIONALITY_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->nationalityList, 'op_value' => 'NATIONALITY_CODE', 'op_text' => 'NATIONALITY_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'NATIONALITY_NAME'){
                          $this->nationalityList = $this->model->getNationalityList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->nationalityList, 'op_value' => 'NATIONALITY_NAME', 'op_text' => 'NATIONALITY_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->nationalityList, 'op_value' => 'NATIONALITY_CODE', 'op_text' => 'NATIONALITY_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'ORIGIN_CODE'){
                          $this->originList = $this->model->getOriginList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->originList, 'op_value' => 'ORIGIN_CODE', 'op_text' => 'ORIGIN_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->originList, 'op_value' => 'ORIGIN_CODE', 'op_text' => 'ORIGIN_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'ORIGIN_NAME'){
                          $this->originList = $this->model->getOriginList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->originList, 'op_value' => 'ORIGIN_NAME', 'op_text' => 'ORIGIN_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->originList, 'op_value' => 'ORIGIN_NAME', 'op_text' => 'ORIGIN_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'GENDER_CODE'){
                          $this->genderList = $this->model->getGenderList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->genderList, 'op_value' => 'GENDER_CODE', 'op_text' => 'GENDER_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->genderList, 'op_value' => 'GENDER_CODE', 'op_text' => 'GENDER_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'GENDER_NAME'){
                          $this->genderList = $this->model->getGenderList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->genderList, 'op_value' => 'GENDER_NAME', 'op_text' => 'GENDER_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->genderList, 'op_value' => 'GENDER_NAME', 'op_text' => 'GENDER_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'HOUSING_CONDITION_CODE'){
                          $this->housingConditionList = $this->model->getHousingConditionList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->housingConditionList, 'op_value' => 'HOUSING_CONDITION_CODE', 'op_text' => 'HOUSING_CONDITION_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->housingConditionList, 'op_value' => 'HOUSING_CONDITION_CODE', 'op_text' => 'HOUSING_CONDITION_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'HOUSING_CONDITION_NAME'){
                          $this->housingConditionList = $this->model->getHousingConditionList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->housingConditionList, 'op_value' => 'HOUSING_CONDITION_NAME', 'op_text' => 'HOUSING_CONDITION_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->housingConditionList, 'op_value' => 'HOUSING_CONDITION_NAME', 'op_text' => 'HOUSING_CONDITION_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'EDUCATION_CODE'){
                          $this->educationList = $this->model->getEducationList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->educationList, 'op_value' => 'EDUCATION_CODE', 'op_text' => 'EDUCATION_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->educationList, 'op_value' => 'EDUCATION_CODE', 'op_text' => 'EDUCATION_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'EDUCATION_NAME'){
                          $this->educationList = $this->model->getEducationList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->educationList, 'op_value' => 'EDUCATION_NAME', 'op_text' => 'EDUCATION_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->educationList, 'op_value' => 'EDUCATION_NAME', 'op_text' => 'EDUCATION_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'SERVICE_CODE'){
                          $this->serviceCodeList = $this->model->getServiceCodeList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->serviceCodeList, 'op_value' => 'SERVICE_CODE', 'op_text' => 'SERVICE_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->serviceCodeList, 'op_value' => 'SERVICE_CODE', 'op_text' => 'SERVICE_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'SERVICE_NAME'){
                          $this->serviceCodeList = $this->model->getServiceCodeList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->serviceCodeList, 'op_value' => 'SERVICE_NAME', 'op_text' => 'SERVICE_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->serviceCodeList, 'op_value' => 'SERVICE_NAME', 'op_text' => 'SERVICE_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'LIVING_STANDARD_CODE'){
                          $this->livingStandardList = $this->model->getLivingStandardList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->livingStandardList, 'op_value' => 'LIVING_STANDARD_CODE', 'op_text' => 'LIVING_STANDARD_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->livingStandardList, 'op_value' => 'LIVING_STANDARD_CODE', 'op_text' => 'LIVING_STANDARD_CODE', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'LIVING_STANDARD_NAME'){
                          $this->livingStandardList = $this->model->getLivingStandardList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->livingStandardList, 'op_value' => 'LIVING_STANDARD_NAME', 'op_text' => 'LIVING_STANDARD_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->livingStandardList, 'op_value' => 'LIVING_STANDARD_NAME', 'op_text' => 'LIVING_STANDARD_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'WFM_STATUS_NAME'){
                          $this->wfmStatusList = $this->model->getWfmStatusList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->wfmStatusList, 'op_value' => 'WFM_STATUS_NAME', 'op_text' => 'WFM_STATUS_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->wfmStatusList, 'op_value' => 'WFM_STATUS_NAME', 'op_text' => 'WFM_STATUS_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else if($key['field'] === 'AIMAG_CITY_NAME'){
                          $this->aimagCityList = $this->model->getAimagCityList();
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
//                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'data' => $this->aimagCityList, 'op_value' => 'AIMAG_CITY_NAME', 'op_text' => 'AIMAG_CITY_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'data' => $this->aimagCityList, 'op_value' => 'AIMAG_CITY_NAME', 'op_text' => 'AIMAG_CITY_NAME', 'class' => 'form-control select2me', 'style'=>'display: none;'));
                          $filterHtml .='</div>';
                        } else {
                          $filterHtml .='<div class="col-md-4">';
                          $filterHtml .= Form::select(array('name' => 'filter[' . $counter . '][type]', 'id' => 'FT', 'data' => $filterTypes, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'onchange' => "inputChange" . $cId . "(this, " . $key['filterId'] . ")"));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value1]', 'id' => 'MD1_' . $cId . '_' . $key['filterId'], 'class' => 'form-control '));
                          $filterHtml .='</div><div class="col-md-4">';
                          $filterHtml .= Form::hidden(array('name' => 'filter[' . $counter . '][value2]', 'id' => 'MD2_' . $cId . '_' . $key['filterId'], 'class' => 'form-control ' . $inputType));
                          $filterHtml .='</div>';
                        }
                    }
//var_dump($key['metatype']);
//var_dump($inputType);
                    break;
            }
            $counter ++;
            $filterHtml .= '</div></div></div>';
        }
//        die();
        $filterHtml.='<div class="row"><div class="col-md-12"><div class="btn-toolbar float-right"><div class="btn-group btn-group-solid">
              <a class="btn btn-sm blue" onclick="reloadModel' . $cId . '()" href="javascript:;"><i class="fa fa-search"></i> Харах</a>
              <a class="btn btn-sm default" onclick="clearInput()" href="javascript:;"> Цэвэрлэх</a>  
                        </div></div></div></div>';
        $filterHtml .= '</form>';
        $response = array(
            'Html' => $filterHtml
        );
        echo json_encode($response);
    }

    public function getColumnList() {
        $params = array();
        parse_str(Input::post('values'), $params);


        $result = $this->model->getColumnList($params['tableName']);
        
        
       
        echo json_encode($result);
    }
       public function getAllList() {

        $params = array();
        parse_str(Input::post('values'), $params);

        $result = $this->model->getAllData($params['tableName']);
        echo json_encode($result);
    }


    public function getPreviewList() {
//      var_dump($_POST['rows']);
        $params = array();
        parse_str(Input::post('values'), $params);

        $groupBy = '-';
        $selectBy = '-';

        foreach ($_POST['rows'] as $key) {
            $groupBy = $groupBy . ', ' . $key['field'];
        }

        foreach ($_POST['cols'] as $key) {

            $selectBy = $selectBy . ', ' . $key['format'] . '(' . $key['field'] . ') AS ' . $key['field'];
        }

        $groupBy = str_replace('-, ', '', $groupBy);
        $selectBy = str_replace('-, ', '', $selectBy);

        $data = array(
            'group' => $groupBy,
            'select' => $groupBy . ', ' . $selectBy,
            'tableName' => $params['tableName']
        );


        $result = $this->model->getPreviewData($data);
        echo json_encode($result);
    }

    public function getReportTemplate() {

        $params = array();
        parse_str(Input::post('values'), $params);
        $result = $this->model->getReportTemplate($params['templateId']);
        echo json_encode($result);
    }

    public function export_excel() {

//        
        $html = '<html><head><meta http-equiv="Content-type" content="text/html; charset=utf-8"></head><body>';
        $html .= post('html');
        $html .= '</body></html>';
        $title = $this->db->GetOne("SELECT REPORT_MODEL_NAME FROM dm_report_model WHERE REPORT_MODEL_ID = ". post('modelId')." ");
        $date = Date::currentDate();
        htmlToExcel($html, $title . ' ' . $date);
    }

    public function export_pdf() {
        ini_set('memory_limit', '-1');
        require_once BASEPATH . LIBS . 'PDF/tcpdf/Tc_pdf.php';
        $pdf = new Tc_pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetHeaderData('', '', 'Нарядын жагсаалт', Date::currentDate('Y-m-d'));
        $pdf->SetHeaderMargin(5);

        $pdf->SetMargins(10, 20, 10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 8);

        $html = post('html');
        $pdf->AddPage('P', 'A4');
        $pdf->writeHTML($html, true, false, false, false, '');

        ob_start();
        $pdf->lastPage();
        $pdf->Output(downloadFileName('report') . '.pdf', 'I');
        ob_end_flush();
    }
    
    public function testFunc(){
      $result = 1;
      echo json_encode($result);
    }
    
    public function repFinConfigCtrl() {

      if (!isset($this->view)) {
          $this->view = new View();
      }

      $this->view->title = "Fin Report Expression";
      $this->view->repFinList = $this->model->repFinListModel();

      $this->view->isAjax = true;
      $this->view->uniqId = getUID();

      if (!is_ajax_request()) {
          $this->view->isAjax = false;
          $this->view->render('header');
      }

      $this->view->render('repFinConfig/index', $this->viewPath);

      if (!is_ajax_request()) $this->view->render('footer');
    }    
    
    public function repFinConfigHeaderCtrl() {
        $result = $this->model->repFinConfigHeaderModel();
        echo json_encode($result);
    }    

}

?>