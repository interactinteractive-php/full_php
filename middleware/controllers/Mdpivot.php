<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdpivot Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	PivotGrid
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdpivot
 */

class Mdpivot extends Controller {
    
    private static $viewPath = 'middleware/views/pivot/';
    
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index() {

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = array_unique(array_merge(array(
            'middleware/assets/js/mdtaskflow.js'
            ), AssetNew::amChartJs()
        ));
        
        $this->view->title = 'Pivot Grid';
        
        $this->view->isAjax = true;

        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        }
        
        $this->view->uniqId = getUID();
        $this->view->dmReportModels = $this->model->getDmReportModels();
        $this->view->isDialog = false;
        $this->view->runMode = 'main';
        $this->view->fieldOptions = null;
        $this->view->grid = null;
        $this->view->reportModelId = null;
        
        $this->view->layout = $this->view->renderPrint('layout', self::$viewPath);

        $this->view->render('index', self::$viewPath);

        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }
    
    public function renderFieldOptions($reportModelId) {
        
        $reportModelId = Input::param($reportModelId);
        $this->view->reportModelId = $reportModelId;
        
        $this->view->runMode = Input::post('runMode');
        
        if (Input::postCheck('isDataView')) {
            $this->view->row = null;
            $this->view->dataViewId = $reportModelId;
            $this->view->allFields = $this->model->getDmDataModelFields($reportModelId);
            $this->view->filterFields = null;
            $this->view->columnFields = null;
            $this->view->rowFields = null;
            $this->view->valueFields = null;
        } else {
            $this->view->row = $this->model->getDmReportRow($reportModelId);
            $this->view->dataViewId = $this->view->row['DM_META_DATA_ID'];
            $this->view->allFields = $this->model->getDmReportModelFields($reportModelId);
            $this->view->filterFields = $this->model->getDmReportModelFilterFields($reportModelId);
            $this->view->columnFields = $this->model->getDmReportModelColumnFields($reportModelId);
            $this->view->rowFields = $this->model->getDmReportModelRowFields($reportModelId);
            $this->view->valueFields = $this->model->getDmReportModelValueFields($reportModelId);
            
            if (isset($this->view->row['COMMAND_NAME']) && !empty($this->view->row['COMMAND_NAME'])) {
                $this->view->commandName = $this->view->row['COMMAND_NAME'];
            }
        }
        
        $this->view->filterFieldsHtml = null;
        $this->view->columnFieldsHtml = null;
        $this->view->rowFieldsHtml = null;
        $this->view->valueFieldsHtml = null;
        
        $this->view->columnFieldsGrid = null;
        $this->view->rowFieldsGrid = null;
        $this->view->valueFieldsGrid = null;
        
        $this->view->filterButtons = null;
        
        if ($this->view->filterFields) {
            foreach ($this->view->filterFields as $filter) {
                $filterLabel = Lang::line($filter['META_DATA_NAME']);
                $this->view->filterFieldsHtml .= '<div class="pv-field" data-field-name="'.Str::lower($filter['PARAM_REAL_PATH']).'" data-field-type="'.$filter['META_TYPE_CODE'].'" title="'.$filterLabel.'">
                    <span>'.$filterLabel.'</span>
                </div>';
            }
        }
        
        if ($this->view->columnFields) {
            foreach ($this->view->columnFields as $column) {
                $columnLabel = Lang::line($column['LABEL_NAME']);
                $this->view->columnFieldsHtml .= '<div class="pv-field" data-field-name="'.$column['FIELD_PATH'].'" data-field-type="'.$column['META_TYPE_CODE'].'" title="'.$columnLabel.'">
                    <span>'.$columnLabel.'</span>
                </div>';
                
                $this->view->columnFieldsGrid .= "'".$column['FIELD_PATH']."',";
                
                $this->view->filterButtons .= Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary mr5', 
                        'value' => $columnLabel.' <i class="fa fa-filter"></i>'
                    )
                );
            }
        }
        
        if ($this->view->rowFields) {
            foreach ($this->view->rowFields as $row) {
                $rowLabel = Lang::line($row['LABEL_NAME']);
                $this->view->rowFieldsHtml .= '<div class="pv-field" data-field-name="'.$row['FIELD_PATH'].'" data-field-type="'.$row['META_TYPE_CODE'].'" title="'.$rowLabel.'">
                    <span>'.$rowLabel.'</span>
                </div>';
                
                $this->view->rowFieldsGrid .= "'".$row['FIELD_PATH']."',";
                
                $this->view->filterButtons .= Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary mr5', 
                        'value' => $rowLabel.' <i class="fa fa-filter"></i>'
                    )
                );
            }
        }
        
        if ($this->view->valueFields) {
            foreach ($this->view->valueFields as $value) {
                
                $valueLabel = Lang::line($value['LABEL_NAME']);
                $aggrName = $value['AGGREGATE_NAME'] ? $value['AGGREGATE_NAME'] : 'sum';
                
                $this->view->valueFieldsHtml .= '<div class="pv-field" data-aggr-name="'.$aggrName.'" data-field-name="'.$value['FIELD_PATH'].'" data-field-type="'.$value['META_TYPE_CODE'].'" title="'.$valueLabel.'">
                    <span>'.$valueLabel.'</span><div class="right-button"><i class="fa fa-caret-down"></i></div>
                </div>';
                
                $this->view->valueFieldsGrid .= "{field:'".$value['FIELD_PATH']."',title:'".$valueLabel."',op:'".$aggrName."',datatype:'".$value['META_TYPE_CODE']."'},";        
            }
        }
            
        $this->view->uniqId = getUID();
        
        $this->load->model('mdobject', 'middleware/models/');
        $dvRow = $this->model->getDataViewConfigRowModel($this->view->dataViewId);

        $this->view->calculateProcessId = $dvRow['CALCULATE_PROCESS_ID'];
        
        $response = array(
            'fieldOptions' => $this->view->renderPrint('fieldOptions', self::$viewPath), 
            'grid' => $this->view->renderPrint('grid', self::$viewPath)
        );
        echo json_encode($response); exit;
    }
    
    public function renderPivotGrid() {
        
        $reportModelId = Input::post('dmReportId');
        $this->view->reportModelId = $reportModelId;
        
        $this->view->runMode = Input::post('runMode');
        $this->view->fieldChooserMode = Input::post('fieldChooserMode');
        
        if ($this->view->runMode == 'main' || $this->view->runMode == 'show') {
            
            $this->view->row = $this->model->getDmReportRow($reportModelId);
            $this->view->dataViewId = $this->view->row['DM_META_DATA_ID'];

            $this->view->filterFields = $this->model->getDmReportModelFilterFields($reportModelId, Input::post('filters'));
            
        } elseif ($this->view->runMode == 'create') {
            
            $this->view->row = null;
            $this->view->dataViewId = $reportModelId;

            $this->view->filterFields = $this->model->getDmDataViewModelFilterFields($this->view->dataViewId, Input::post('filters'));
        } else {
            
            $this->view->row = $this->model->getDmReportRow($reportModelId);
            $this->view->dataViewId = $this->view->row['DM_META_DATA_ID'];

            $this->view->filterFields = $this->model->getDmDataViewModelFilterFields($this->view->dataViewId, Input::post('filters'));
        }
        
        if (isset($this->view->row['COMMAND_NAME']) && !empty($this->view->row['COMMAND_NAME'])) {
            $this->view->commandName = $this->view->row['COMMAND_NAME'];
        }
        
        $this->view->columnFieldsGrid = null;
        $this->view->rowFieldsGrid = null;
        $this->view->valueFieldsGrid = null;
        
        $this->view->filterButtons = null;
        
        if (Input::postCheck('columns')) {
            foreach ($_POST['columns'] as $column) {
                $this->view->columnFieldsGrid .= "'".$column['fieldName']."',";
                
                $this->view->filterButtons .= Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary mr5', 
                        'value' => $column['labelName'].' <i class="fa fa-filter"></i>'
                    )
                );
            }
        }
        
        if (Input::postCheck('rows')) {
            foreach ($_POST['rows'] as $row) {
                $this->view->rowFieldsGrid .= "'".$row['fieldName']."',";
                
                $this->view->filterButtons .= Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary mr5', 
                        'value' => $row['labelName'].' <i class="fa fa-filter"></i>'
                    )
                );
            }
        }
        
        if (Input::postCheck('values')) {
            foreach ($_POST['values'] as $value) {
                $this->view->valueFieldsGrid .= "{field:'".$value['fieldName']."',title:'".$value['labelName']."',op:'".$value['aggrName']."',datatype:'".$value['dataType']."'},";        
            }
        }
        
        $this->view->uniqId = getUID();
        
        $this->load->model('mdobject', 'middleware/models/');
        $dvRow = $this->model->getDataViewConfigRowModel($this->view->dataViewId);

        $this->view->calculateProcessId = $dvRow['CALCULATE_PROCESS_ID'];
        
        $response = array(
            'grid' => $this->view->renderPrint('grid', self::$viewPath)
        );
        echo json_encode($response); exit;
    }
    
    public function createPivotGrid() {
        
        $this->view->dataViewList = $this->model->getRMDataViewListModels();
        $this->view->dataViewId = '';
        $this->view->runMode = 'create';
        $this->view->allFields = null;
        $this->view->filterFields = null;
        
        $this->view->filterFieldsHtml = null;
        $this->view->columnFieldsHtml = null;
        $this->view->rowFieldsHtml = null;
        $this->view->valueFieldsHtml = null;
        
        $this->view->columnFieldsGrid = null;
        $this->view->rowFieldsGrid = null;
        $this->view->valueFieldsGrid = null;
        
        $this->view->reportModelId = null;
        $this->view->categoryId = null;
        
        if (Input::isEmpty('param') == false) {
            $paramLower = array_change_key_case(Input::post('param'), CASE_LOWER);
            if (array_key_exists('categoryid', $paramLower)) {
                $this->view->categoryId = $paramLower['categoryid'];
            }
        }
        
        $this->view->uniqId = getUID();
        $this->view->calculateProcessId = null;
        
        $this->view->isDialog = true;
        
        $this->view->fieldOptions = $this->view->renderPrint('fieldOptions', self::$viewPath);
        $this->view->grid = $this->view->renderPrint('grid', self::$viewPath);
        
        $response = array(
            'title' => 'Create PivotGrid', 
            'html' => $this->view->renderPrint('layout', self::$viewPath), 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function createPivotGridSave() {
        $response = $this->model->createPivotGridSaveModel();
        echo json_encode($response); exit;
    }
    
    public function editPivotGrid() {
        
        $selectedRow = Input::post('selectedRow');
        
        $this->view->reportModelId = $selectedRow['id'];
        $this->view->runMode = 'edit';
        
        $this->view->dataViewList = $this->model->getRMDataViewListModels();
        
        $this->view->row = $this->model->getDmReportRow($this->view->reportModelId);
        $this->view->dataViewId = $this->view->row['DM_META_DATA_ID'];
        
        if (isset($this->view->row['COMMAND_NAME']) && !empty($this->view->row['COMMAND_NAME'])) {
            $this->view->commandName = $this->view->row['COMMAND_NAME'];
        }
        
        $this->view->allFields = $this->model->getDmReportModelFields($this->view->reportModelId);
        $this->view->filterFields = $this->model->getDmReportModelFilterFields($this->view->reportModelId);
        $this->view->columnFields = $this->model->getDmReportModelColumnFields($this->view->reportModelId);
        $this->view->rowFields = $this->model->getDmReportModelRowFields($this->view->reportModelId);
        $this->view->valueFields = $this->model->getDmReportModelValueFields($this->view->reportModelId);
        
        $this->view->filterFieldsHtml = null;
        $this->view->columnFieldsHtml = null;
        $this->view->rowFieldsHtml = null;
        $this->view->valueFieldsHtml = null;
        
        $this->view->columnFieldsGrid = null;
        $this->view->rowFieldsGrid = null;
        $this->view->valueFieldsGrid = null;
        
        $this->view->filterButtons = null;
        
        if ($this->view->filterFields) {
            foreach ($this->view->filterFields as $filter) {
                $filterLabel = Lang::line($filter['META_DATA_NAME']);
                $this->view->filterFieldsHtml .= '<div class="pv-field" data-field-name="'.Str::lower($filter['PARAM_REAL_PATH']).'" data-field-type="'.$filter['META_TYPE_CODE'].'" title="'.$filterLabel.'">
                    <span>'.$filterLabel.'</span>
                </div>';
            }
        }
        
        if ($this->view->columnFields) {
            foreach ($this->view->columnFields as $column) {
                $columnLabel = Lang::line($column['LABEL_NAME']);
                $this->view->columnFieldsHtml .= '<div class="pv-field" data-field-name="'.$column['FIELD_PATH'].'" data-field-type="'.$column['META_TYPE_CODE'].'" title="'.$columnLabel.'">
                    <span>'.$columnLabel.'</span>
                </div>';
                
                $this->view->columnFieldsGrid .= "'".$column['FIELD_PATH']."',";
                
                $this->view->filterButtons .= Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary mr5', 
                        'value' => $columnLabel.' <i class="fa fa-filter"></i>'
                    )
                );
            }
        }
        
        if ($this->view->rowFields) {
            foreach ($this->view->rowFields as $row) {
                $rowLabel = Lang::line($row['LABEL_NAME']);
                $this->view->rowFieldsHtml .= '<div class="pv-field" data-field-name="'.$row['FIELD_PATH'].'" data-field-type="'.$row['META_TYPE_CODE'].'" title="'.$rowLabel.'">
                    <span>'.$rowLabel.'</span>
                </div>';
                
                $this->view->rowFieldsGrid .= "'".$row['FIELD_PATH']."',";
                
                $this->view->filterButtons .= Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary mr5', 
                        'value' => $rowLabel.' <i class="fa fa-filter"></i>'
                    )
                );
            }
        }
        
        if ($this->view->valueFields) {
            foreach ($this->view->valueFields as $value) {
                
                $valueLabel = Lang::line($value['LABEL_NAME']);
                $aggrName = $value['AGGREGATE_NAME'] ? $value['AGGREGATE_NAME'] : 'sum';
                
                $this->view->valueFieldsHtml .= '<div class="pv-field" data-aggr-name="'.$aggrName.'" data-field-name="'.$value['FIELD_PATH'].'" data-field-type="'.$value['META_TYPE_CODE'].'" title="'.$valueLabel.'">
                    <span>'.$valueLabel.'</span><div class="right-button"><i class="fa fa-caret-down"></i></div>
                </div>';
                
                $this->view->valueFieldsGrid .= "{field:'".$value['FIELD_PATH']."',title:'".$valueLabel."',op:'".$aggrName."',datatype:'".$value['META_TYPE_CODE']."'},";        
            }
        }
        
        $this->view->categoryId = null;
        
        if (isset($selectedRow['categoryid'])) {
            $this->view->categoryId = $selectedRow['categoryid'];
        }
        
        $this->view->uniqId = getUID();
        
        $this->load->model('mdobject', 'middleware/models/');
        $dvRow = $this->model->getDataViewConfigRowModel($this->view->dataViewId);

        $this->view->calculateProcessId = $dvRow['CALCULATE_PROCESS_ID'];
        
        $this->view->isDialog = true;
        
        $this->view->fieldOptions = $this->view->renderPrint('fieldOptions', self::$viewPath);
        $this->view->grid = $this->view->renderPrint('grid', self::$viewPath);
        
        $response = array(
            'title' => 'Edit PivotGrid', 
            'html' => $this->view->renderPrint('layout', self::$viewPath), 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function editPivotGridSave() {
        $response = $this->model->editPivotGridSaveModel();
        echo json_encode($response); exit;
    }
    
    public function showPivotGrid() {
        
        $this->view->reportModelId = Input::post('reportModelId');
        
        $this->view->uniqId = getUID();
        $this->view->runMode = 'show';
        
        $this->view->row = $this->model->getDmReportRow($this->view->reportModelId);
        $this->view->dataViewId = $this->view->row['DM_META_DATA_ID'];
        
        if (isset($this->view->row['COMMAND_NAME']) && !empty($this->view->row['COMMAND_NAME'])) {
            $this->view->commandName = $this->view->row['COMMAND_NAME'];
        }
            
        $this->view->allFields = $this->model->getDmReportModelFields($this->view->reportModelId);
        $this->view->filterFields = $this->model->getDmReportModelFilterFields($this->view->reportModelId);
        $this->view->columnFields = $this->model->getDmReportModelColumnFields($this->view->reportModelId);
        $this->view->rowFields = $this->model->getDmReportModelRowFields($this->view->reportModelId);
        $this->view->valueFields = $this->model->getDmReportModelValueFields($this->view->reportModelId);
            
        $this->view->filterFieldsHtml = null;
        $this->view->columnFieldsHtml = null;
        $this->view->rowFieldsHtml = null;
        $this->view->valueFieldsHtml = null;
        
        $this->view->columnFieldsGrid = null;
        $this->view->rowFieldsGrid = null;
        $this->view->valueFieldsGrid = null;
        
        $this->view->filterButtons = null;
        
        $this->view->categoryId = null;
        
        if ($this->view->filterFields) {
            foreach ($this->view->filterFields as $filter) {
                $filterLabel = Lang::line($filter['META_DATA_NAME']);
                $this->view->filterFieldsHtml .= '<div class="pv-field" data-field-name="'.Str::lower($filter['PARAM_REAL_PATH']).'" data-field-type="'.$filter['META_TYPE_CODE'].'" title="'.$filterLabel.'">
                    <span>'.$filterLabel.'</span>
                </div>';
            }
        }
        
        if ($this->view->columnFields) {
            foreach ($this->view->columnFields as $column) {
                $columnLabel = Lang::line($column['LABEL_NAME']);
                $this->view->columnFieldsHtml .= '<div class="pv-field" data-field-name="'.$column['FIELD_PATH'].'" data-field-type="'.$column['META_TYPE_CODE'].'" title="'.$columnLabel.'">
                    <span>'.$columnLabel.'</span>
                </div>';
                
                $this->view->columnFieldsGrid .= "'".$column['FIELD_PATH']."',";
                
                $this->view->filterButtons .= '<div class="btn-group">';
                
                    $this->view->filterButtons .= html_tag('button', 
                        array(
                            'type' => 'text', 
                            'class' => 'btn btn-sm btn-secondary dropdown-toggle mr5 pv-filter-button', 
                            'data-type' => 'column', 
                            'data-field-name' => $column['FIELD_PATH'], 
                            'data-uniqid' => $this->view->uniqId, 
                            'data-toggle' => 'dropdown'
                        ), 
                        $columnLabel.' <i class="fa fa-filter"></i>'     
                    );
                
                    $this->view->filterButtons .= '<div class="dropdown-menu pivot-filter-form stop-propagation" role="menu"></div>';
                
                $this->view->filterButtons .= '</div>';
            }
        }
        
        if ($this->view->rowFields) {
            foreach ($this->view->rowFields as $row) {
                $rowLabel = Lang::line($row['LABEL_NAME']);
                $this->view->rowFieldsHtml .= '<div class="pv-field" data-field-name="'.$row['FIELD_PATH'].'" data-field-type="'.$row['META_TYPE_CODE'].'" title="'.$rowLabel.'">
                    <span>'.$rowLabel.'</span>
                </div>';
                
                $this->view->rowFieldsGrid .= "'".$row['FIELD_PATH']."',";
                
                $this->view->filterButtons .= '<div class="btn-group">';
                
                    $this->view->filterButtons .= html_tag('button', 
                        array(
                            'type' => 'text', 
                            'class' => 'btn btn-sm btn-secondary dropdown-toggle mr5 pv-filter-button', 
                            'data-type' => 'row', 
                            'data-field-name' => $row['FIELD_PATH'], 
                            'data-uniqid' => $this->view->uniqId, 
                            'data-toggle' => 'dropdown'
                        ), 
                        $rowLabel.' <i class="fa fa-filter"></i>'     
                    );

                    $this->view->filterButtons .= '<div class="dropdown-menu pivot-filter-form stop-propagation" role="menu"></div>';
                
                $this->view->filterButtons .= '</div>';
            }
        }
        
        if ($this->view->valueFields) {
            foreach ($this->view->valueFields as $value) {
                
                $valueLabel = Lang::line($value['LABEL_NAME']);
                $aggrName = $value['AGGREGATE_NAME'] ? $value['AGGREGATE_NAME'] : 'sum';
                
                $this->view->valueFieldsHtml .= '<div class="pv-field" data-aggr-name="'.$aggrName.'" data-field-name="'.$value['FIELD_PATH'].'" data-field-type="'.$value['META_TYPE_CODE'].'" title="'.$valueLabel.'">
                    <span>'.$valueLabel.'</span><div class="right-button"><i class="fa fa-caret-down"></i></div>
                </div>';
                
                $this->view->valueFieldsGrid .= "{field:'".$value['FIELD_PATH']."',title:'".$valueLabel."',op:'".$aggrName."',datatype:'".$value['META_TYPE_CODE']."'},";        
            }
        }
        
        $this->view->isDialog = false;
        
        $this->load->model('mdobject', 'middleware/models/');
        $dvRow = $this->model->getDataViewConfigRowModel($this->view->dataViewId);

        $this->view->calculateProcessId = $dvRow['CALCULATE_PROCESS_ID'];
        
        $this->view->fieldOptions = $this->view->renderPrint('fieldOptions', self::$viewPath);
        $this->view->grid = $this->view->renderPrint('grid', self::$viewPath);
        
        $response = array(
            'title' => $this->view->row['REPORT_MODEL_NAME'], 
            'html' => $this->view->renderPrint('layout', self::$viewPath), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function chooseAggregate() {
        $this->view->aggrName = Input::post('aggrName');
        
        $response = array(
            'title' => 'Aggregate', 
            'html' => $this->view->renderPrint('chooseAggregate', self::$viewPath), 
            'choose_btn' => Lang::line('choose_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function excelExport() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';
        loadPhpQuery();
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator('Veritech ERP')
                                    ->setLastModifiedBy('')
                                    ->setTitle("Office 2007 - Document")
                                    ->setSubject("Office 2007 - Document")
                                    ->setDescription('')
                                    ->setKeywords('')
                                    ->setCategory('');
        
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle(Str::excelSheetName('PivodGrid'));
        
        $columnHtml = Input::postNonTags('columnHtml');
        $rowHtml = Input::postNonTags('rowHtml');
        $valueHtml = Input::postNonTags('valueHtml');
        
        $columnObj = phpQuery::newDocumentHTML($columnHtml);
        $columnRows = $columnObj['tr'];
        
        $rowObj = phpQuery::newDocumentHTML($rowHtml);
        $rowRows = $rowObj['tbody:eq(0) tr:not(.treegrid-tr-tree)'];
        
        $valueObj = phpQuery::newDocumentHTML($valueHtml);
        $valueRows = $valueObj['tbody:eq(0) tr:not(.treegrid-tr-tree)'];
        
        $columnCount = $columnRows->length();
        
        $sheet->setCellValue('A1', 'Pivot Grid')->mergeCells('A1:A'.$columnCount);
        
        if ($columnRows) {
            
            $r = 1;
            
            foreach ($columnRows as $columnRow) {
                
                $columnCells = pq($columnRow)->find('> td');

                $startCellIndex = 1;
                $endCellIndex = 1;

                foreach ($columnCells as $columnCell) {

                    $columnColSpan = pq($columnCell)->attr('colspan');

                    if ($columnColSpan) {

                        if ($startCellIndex == 1) {
                            
                            $startCellIndex = 2;
                            $columnColSpan = $columnColSpan + 1;
                            $endCellIndex = $columnColSpan;
                            
                        } else {
                            $startCellIndex = $startCellIndex + $columnColSpan;
                            $endCellIndex = $startCellIndex + $columnColSpan - 1;
                        }

                        $sheet->mergeCells(numToAlpha($startCellIndex).$r.':'.numToAlpha($endCellIndex).$r);

                    } else {
                        $startCellIndex++;
                    }

                    $sheet->setCellValue(numToAlpha($startCellIndex) . $r, pq($columnCell)->text());
                }

                $r++;
            }

            $sheet->getStyle('A1:'.numToAlpha($startCellIndex).$columnCount)->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
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

            foreach (range('A', numToAlpha($startCellIndex)) as $columnId) {
                $sheet->getColumnDimension($columnId)->setAutoSize(true);
            }
        }
        
        if ($rowRows) {
            
            $rowIndex = $r;
            
            foreach ($rowRows as $rowRow) {
                $indentCount = pq($rowRow)->find('.tree-indent')->length;
                $sheet->setCellValueExplicit(numToAlpha(1) . $rowIndex, str_repeat('   ', $indentCount).pq($rowRow)->text(), PHPExcel_Cell_DataType::TYPE_STRING);
                $rowIndex++;
            }
        }
        
        if ($valueRows) {
            
            $valueRowIndex = $r;
            
            foreach ($valueRows as $valueRow) {
                
                $valueCells = pq($valueRow)->find('> td');
                $valueCellIndex = 2;
                
                foreach ($valueCells as $valueCell) {
                    
                    $cellValue = pq($valueCell)->text();
                    
                    if ($cellValue) {
                        $cellValue = str_replace(',', '', $cellValue);
                        $sheet->setCellValueExplicit(numToAlpha($valueCellIndex) . $valueRowIndex, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    } else {
                        $sheet->setCellValue(numToAlpha($valueCellIndex) . $valueRowIndex, null);
                    }
                    
                    $valueCellIndex++;
                }

                $valueRowIndex++;
            }
            
            $sheet->getStyle('B'.$r.':'.numToAlpha($startCellIndex).($valueRowIndex-1))->getNumberFormat()->setFormatCode('#,##0.00');
            
            $sheet->freezePane('A'.($columnCount + 1));
            /*$sheet->getStyle('A1:'.numToAlpha($startCellIndex).($valueRowIndex-1))->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );*/
        }
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="PivodGrid - ' . Date::currentDate('YmdHi') . '.xlsx"');
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
    
    public function dataViewByProcess() {
        
        $result = $this->model->dataViewByProcessModel();
        
        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }
    
    public function dataViewPivotView($metaDataId = '', $templateId = '') {
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $metaDataId = Input::param($metaDataId);
        $templateId = Input::param($templateId);
        
        if ($metaDataId && $templateId) {
            
            $_POST['metaDataId'] = $metaDataId;
            $_POST['templateid'] = $templateId;
            $_POST['isignoretemplatelist'] = 1;
            $_POST['readonly'] = 1;
            
            if (Input::get('hiderowtotal') == '1') {
                $_POST['hiderowtotal'] = 1;
            }

            if (Input::get('hidecolumntotal') == '1') {
                $_POST['hidecolumntotal'] = 1;
            }
            
            if (Input::get('collapse') == '1') {
                $_POST['collapse'] = 1;
            }
            
        } elseif ($metaDataId && !$templateId) { 
            
            $_POST['metaDataId'] = $metaDataId;
            
        } elseif (Input::postCheck('metadataid')) {
            
            $_POST['metaDataId'] = Input::numeric('metadataid');
        } 
        
        if (Input::postCheck('indicatorId')) {
            $kpiIndicatorId = Input::numeric('indicatorId');
            
            if (!$kpiIndicatorId) {
                echo json_encode(array('status' => 'error', 'message' => 'Invalid indicatorId!')); exit;
            }
            
        } else {
            $this->view->dataViewId = Input::numeric('metaDataId');
            
            if (!$this->view->dataViewId) {
                echo json_encode(array('status' => 'error', 'message' => 'Invalid metaDataId!')); exit;
            }
        }
        
        $_POST['isShowPivot'] = 1;
        
        if (Input::post('isIgnorePopupSearch') != '1') {
            
            $dataViewSearchData = $this->model->dataViewHeaderDataModel($this->view->dataViewId);
            
            if ($dataViewSearchData) {

                $rowData = $criterias = $this->view->buttonCriterias = array();
                
                foreach ($dataViewSearchData as $criteriaRow) {
                    
                    if ($criteriaRow['LOOKUP_META_DATA_ID'] 
                        && $criteriaRow['LOOKUP_TYPE'] == 'button' 
                        && $criteriaRow['CHOOSE_TYPE'] 
                        && $criteriaRow['DISPLAY_FIELD'] 
                        && $criteriaRow['VALUE_FIELD']) {
                        
                        $this->view->buttonCriterias[] = $criteriaRow;
                        
                        if ($criteriaRow['DEFAULT_VALUE']) {
                            $rowData[$criteriaRow['META_DATA_CODE']] = Mdmetadata::setDefaultValue($criteriaRow['DEFAULT_VALUE']);
                        }
                        
                    } else {
                        $criterias[] = $criteriaRow;
                    }
                }
                
                $this->view->fillParamData = null;
                
                if (Input::postCheck('param')) {
                    $this->view->fillParamData = Input::post('param');
                }
                
                $this->view->dataViewSearchData['visible'] = $criterias;
                $this->view->popupSearch = $this->view->renderPrint('dataview/popupSearch', 'middleware/views/statement/');
                $this->view->buttonFilterWithPlay = $this->view->renderPrint('dataview/buttonFilterWithPlay', 'middleware/views/statement/');
                
                if ($this->view->popupSearch) {
                    
                    loadPhpQuery();
                    $detailHtml = phpQuery::newDocumentHTML($this->view->popupSearch);
                    $detailParam = $detailHtml->find('input, select, textarea')->serializeArray();

                    foreach ($detailParam as $param) {

                        preg_match_all('/param\[(.*?)\]/i', $param['name'], $paramNames);

                        if (isset($paramNames[1][0]) && $paramNames[1][0]) {
                            $value = $param['value'];
                            if ($value != '') {
                                $rowData[$paramNames[1][0]] = $value;
                            }
                        }
                    }
                }
                
                if (!empty($rowData)) {
                    $_POST['uriParams'] = json_encode($rowData, JSON_UNESCAPED_UNICODE);
                }
            }
            
            $this->load->model('mdobject', 'middleware/models/');
            
            $this->view->row = $this->model->getDataViewConfigRowModel($this->view->dataViewId);
            $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
            $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->dataViewId, $this->view->metaDataCode, false);
        }
        
        if (isset($kpiIndicatorId)) {
            
            $this->load->model('mdform', 'middleware/models/');
            $getIndicatorSqlResponse = $this->model->indicatorDataGridModel();
            
            if ($getIndicatorSqlResponse['status'] == 'success') {
                
                $row = $this->model->getKpiIndicatorRowModel($kpiIndicatorId);
                $indicatorColumns = $this->model->getKpiIndicatorColumnsModel($kpiIndicatorId, array('isIgnoreStandardFields' => 1));
        
                $this->load->model('mdpivot', 'middleware/models/');
                $result = $this->model->kpiIndicatorToPivotConfigModel($row, $getIndicatorSqlResponse['sql'], $indicatorColumns);
                
            } else {
                echo json_encode($getIndicatorSqlResponse); exit;
            }
            
        } else {
            unset($_POST['ignoreFirstLoad']);
            $result = $this->model->dataViewDataGridModel(false);
        }
        
        if ($result['status'] == 'success') {
            
            if (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS) {
                
                $this->view->dvId = $result['dvId'];
                
                $pivot = &getInstance();
                $pivot->load->model('mdpivot', 'middleware/models/');
                
                $this->view->uniqId = getUID();
                $this->view->reportId = $result['reportId'];
                $this->view->postWindowHeight = Input::numeric('windowHeight', 1000);
                $this->view->windowHeight = $this->view->postWindowHeight - 105;
                
                if (Input::post('isignoretemplatelist') != '1') {
                    
                    $this->view->templateList = $pivot->model->getRpPivotTemplateListModel($this->view->dvId);

                    if ($this->view->templateList) {
                        
                        $tmpl = &getInstance();
                        $tmpl->load->model('mdtemplate', 'middleware/models/');

                        $userConfig = $tmpl->model->getPrintConfigRowByUserModel($this->view->dvId, Ue::sessionUserKeyId());

                        if (isset($userConfig['PIVOT_TEMPLATE_ID'])) {
                            $this->view->pivotTemplateId = $userConfig['PIVOT_TEMPLATE_ID'];
                        } else {
                            $this->view->pivotTemplateId = null;
                        }

                        $this->view->windowHeight = $this->view->windowHeight - 45;
                    }
                }

                $this->view->iframeUrl = CONFIG_PIVOT_SERVICE_ADDRESS . '?reportId='.$this->view->reportId.'&langCode='.Lang::getCode();
                
                if ($this->view->postWindowHeight) {
                    $this->view->iframeUrl .= '&height=' . $this->view->windowHeight;
                }
                
                if ($sdbid = Session::get(SESSION_PREFIX . 'sdbid')) {
                    $this->view->iframeUrl .= '&dbId=' . $sdbid;
                }
                
                $this->view->iframeUrl .= '&baseUrl=' . URL;
                
                $this->view->iframeMainUrl = $this->view->iframeUrl;
                
                if (isset($this->view->pivotTemplateId)) {
                    $this->view->iframeUrl .= '&templateId=' . $this->view->pivotTemplateId;
                }
                
                if (Input::isEmpty('templateid') == false && !isset($this->view->pivotTemplateId)) {
                    $this->view->pivotTemplateId = Input::numeric('templateid');
                    $this->view->iframeUrl .= '&templateId=' . $this->view->pivotTemplateId;
                }
                
                if (Input::post('readonly') == '1') {
                    $this->view->iframeUrl .= '&readonly=1';
                }
                
                if (Input::post('hiderowtotal') == '1') {
                    $this->view->hideRowTotal = '1';
                    $this->view->iframeUrl .= '&hideRowTotal=1';
                }
                
                if (Input::post('hidecolumntotal') == '1') {
                    $this->view->hideColumnTotal = '1';
                    $this->view->iframeUrl .= '&hideColumnTotal=1';
                }
                
                if (Input::post('collapse') == '1') {
                    $this->view->collapse = '1';
                    $this->view->iframeUrl .= '&Collapse=1';
                }

                $response = array(
                    'status'    => 'success', 
                    'title'     => 'Pivot', 
                    'html'      => $this->view->renderPrint('iframe', self::$viewPath), 
                    'iframeUrl' => $this->view->iframeUrl 
                );
                
            } else {
                $response = array(
                    'status' => 'error', 
                    'title' => 'Pivot', 
                    'html' => html_tag('div', array('class' => 'alert alert-warning'), 'Pivot-ийн тохиргоо хийгдээгүй байна!') 
                );
            }
            
        } else {
            $response = $result;
        }
        
        if (Input::post('resultArray') == 1) {
            return $response;
        }
        
        echo json_encode($response); exit;
    }
    
    public function saveLastChangeTemplateId() {
        $response = $this->model->saveLastChangeTemplateIdModel();
        echo json_encode($response); 
    }
    
    public function processPivotColumns($id) {
        
        $param = array(
            'systemMetaGroupId' => '1547536472633133',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'orderBookId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $id
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']); 
            
            return $result['result'];
            
        } else {
            return null;
        }
    }
    
    public function bpInputParamsData($bpId, $parentId, $where) {
        $this->load->model('mdwebservice', 'middleware/models/');
        $data = $this->model->groupParamsDataModel($bpId, $parentId, $where);
        return $data;
    }
    
}