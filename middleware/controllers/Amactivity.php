<?php 

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

/**
 * Amactivity Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Amactivity
 * @author	Ts.Ulaankhuu <ulaankhuu@veritech.mn>
 * @link	http://www.veritech.mn/PHPframework/Amactivity/Amactivity
 */
class Amactivity extends Controller {
    
    private static $mainViewPath = 'middleware/views/metadata/';
    private static $viewPath = "middleware/views/amactivity/";

    public function __construct() {
        parent::__construct();
        Session::init();
    }

    public function index() {
        $this->load->model('amactivity', 'middleware/models/');
        $this->view->title = "Төсвийн задаргаа";

        $this->view->activityKeyId = is_null(Input::post('activityKeyId')) ? false : Input::post('activityKeyId');
        if(!$this->view->activityKeyId)
            die('<h3><center>Workspace Menu тохиргоо буруу байна!</center></h3>');
        
        $inputParam = array(
            'activityKeyId' => $this->view->activityKeyId
        );
        $this->view->uniqId = getUID();
        $resultPeriod = $this->model->getPeriodActivityModel($inputParam);
        $this->view->mapDatas = $this->model->getDmRecordMapModel($this->view->activityKeyId);
        $this->view->getAllActivityPeriod = (isset($resultPeriod['getRows']['period'])) ? $resultPeriod['getRows']['period'] : array();
        $this->view->getRowActivityKey = $this->model->getRowActivityKeyModel($this->view->activityKeyId);
        $this->view->activityKey = $this->model->getActivityKeyModel($this->view->activityKeyId);
        $this->view->attachFiles = $this->model->getActivityFilesModel($inputParam);     
        $this->view->getCombo = $this->model->getActivityComboModel($inputParam);
        $this->view->getComboText = $this->model->getActivityComboTextModel($this->view->getCombo);
        $this->activityAssets();
        $this->view->html = 'index';
        
        if (empty($this->view->getRowActivityKey)) {
            echo '<h3><center>Үндсэн үзүүлэлтээ тохируулна уу! :(</center></h3>';
            
            if (Config::getFromCache('CONFIG_MULTI_TAB')) {
                echo Form::button(array('class' => 'btn btn-circle btn-sm blue float-right backFromActivitySheet', 'onclick' => 'backFirstContent(this);', 'value' => '<i class="fa fa-reply"></i> ' . Lang::line('back_btn')));
            } else {
                echo Form::button(array('class' => 'btn btn-circle btn-sm blue float-right backFromActivitySheet', 'onclick' => 'backWindowDataViewFilter();', 'value' => '<i class="fa fa-reply"></i> ' . Lang::line('back_btn')));
            }            
            exit();
        }
        
        $this->view->wfmlog = $this->view->workspaceId = $this->view->selectedRowData = $this->view->dmMetaDataId = $this->view->methodId = $this->view->wfmStatusBtns =  '';
        
        if (Input::postCheck('selectedRow') && Input::isEmpty('selectedRow') === false) {
            $selectedRow = Arr::decode(Input::post('selectedRow'));
            $this->view->wfmlog =  self::getRowWfmStatusForm($selectedRow);
            
            $this->load->model('mdobject', 'middleware/models/');
                
            $this->view->selectedRowData = $selectedRow['dataRow'];
            $this->view->dmMetaDataId = $selectedRow['metaDataId'];
            $this->view->workspaceId = $selectedRow['workspaceId'];
            $this->view->methodId = $selectedRow['refStructureId'];
            $this->view->refStructureId = $selectedRow['refStructureId'];
            
            $this->view->html = (isset($selectedRow['dataRow']['viewtype']) && $selectedRow['dataRow']['viewtype'] == 'v2') ?  'indexv2' : 'index';
            $this->view->wfmStatusBtns =  $this->model->getWorkflowNextStatusModel($this->view->dmMetaDataId, $this->view->selectedRowData, '0');
            
        }
        
        $this->load->model('mdsalary', 'middleware/models/');
        $this->view->lookUpCalc = $this->model->getLookUpCalcModel('AM_ACTIVITY_KEY_List');
        
        if (!is_ajax_request()) {
            die('<h3><center>Төсөв төлөвлөлтийн цэснээс орно уу! :(</center></h3>');
            $this->view->render('header');
            $this->view->render($this->view->html, self::$viewPath);
            $this->view->render('footer');
        } else {
            echo $this->view->renderPrint($this->view->html, self::$viewPath);
        }
        
    }
    
    public function getRowWfmStatusForm($selectedRow) {

        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->isForm = false;
        $this->view->isSee = false;
        $this->view->metaDataId = $selectedRow['metaDataId'];
        $this->view->refStructureId = $selectedRow['refStructureId'];
        
        $this->view->employeeName = Ue::getSessionUserName();
        $this->view->positionName = Ue::getSessionPositionName();
        $this->view->deparmentName = Ue::sessionDepartmentName();
        $this->view->picture = Ue::getSessionPhoto('height="53"');
        
        $this->view->dataRow = $selectedRow['dataRow'];
        
        $this->load->model('mdmeta', 'middleware/models/');
        $this->view->dmetaDataId = $this->model->getMetaDataIdByCodeModel('sysUmUserListWFM');
        
        $this->view->recordId = isset($this->view->dataRow['id']) ? $this->view->dataRow['id'] : '0';
        $this->view->wfmStatusId = isset($this->view->dataRow['wfmstatusid']) ? $this->view->dataRow['wfmstatusid'] : '0';
        $this->view->userKeyId = Ue::sessionUserKeyId();
        
        $this->load->model('mdobject', 'middleware/models/');
        $this->view->wfmStatusLog = $this->model->getRowWfmStatusLogModel($this->view->metaDataId, $this->view->dataRow);

        if (isset($this->view->wfmStatusLog['data']['log']) && $this->view->wfmStatusLog['data']['log']) {
            $this->view->wfmStatusLog['data']['log'] = Arr::sort2d($this->view->wfmStatusLog['data']['log'], 'createddate', 'desc');
            (Array) $item = array();
            foreach ($this->view->wfmStatusLog['data']['log'] as $key => $row) {
                if ($key < 10)
                    array_push($item, $row);
            }

            $this->view->wfmStatusLog['data']['log'] = Arr::sort2d($item, 'createddate');
        }
        $this->view->newWfmStatusName = '';
        $this->view->newWfmStatusColor = '';
            
        
        return $this->view->renderPrint('common/sub/wfmStatusForm', self::$mainViewPath);
    }
    
    public function template($keyId = false) {
        $this->load->model('amactivity', 'middleware/models/');
        $this->view->title = "Загвар төсвийн задаргаа";
        $this->view->activityKeyId = is_null(Input::post('activityKeyId')) ? $keyId : Input::post('activityKeyId');
        if(!$this->view->activityKeyId)
            die('<h3><center>Workspace Menu тохиргоо буруу байна!</center></h3>');
        
        $this->view->getRowActivityKey = $this->model->getRowActivityKeyModel($this->view->activityKeyId);
        $this->activityAssets();
        
        if (!is_ajax_request()) {
            $this->view->render('header', self::$viewPath);
            $this->view->render('template', self::$viewPath);
            $this->view->render('footer');
        } else
            echo $this->view->renderPrint('template', self::$viewPath);
    }

    public function activityAssets() {
        $this->view->css = array(
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/jquery-dialogextend/jquery.dialogextend.min.js',
            'custom/addon/plugins/phpjs/phpjs.min.js'
        );
        $this->view->fullUrlJs = array('middleware/assets/js/amactivity_oop.js');
    }
    
    public function actionActivitySheetCalculate() {
        $result = $this->model->actionActivitySheetCalculateModel();
        echo json_encode($result);
    }
    
    public function deleteActivitySheetCtrl() {
        $result = $this->model->deleteActivitySheetModel();
        echo json_encode($result);
    }
    
    public function deleteActivityTemplateCtrl() {
        $result = $this->model->deleteActivityTemplateDtl();
        echo json_encode($result);
    }
    
    public function deleteActivityTemplate2Ctrl() {
        $result = $this->model->deleteActivityTemplateDtl2();
        echo json_encode($result);
    }
    
    public function deleteActivityTemplate3Ctrl() {
        $result = $this->model->deleteActivityTemplateDtl3();
        echo json_encode($result);
    }
    
    public function deleteActivityTemplate4Ctrl() {
        $result = $this->model->deleteActivityTemplateDtl4();
        echo json_encode($result);
    }
    
    public function getAllActivitySheetCtrl() {
        $result = $this->model->getAllActivitySheetModel();
        echo json_encode($result);
    }
    
    public function getAllActivitySheetCtrlExcelExport() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $result = $this->model->getAllActivitySheetModel();
        $headerData = $result['getRows']['header'];
        $detailData = $result['getRows']['detail']['rows'];

        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Veritech ERP")
                ->setLastModifiedBy("")
                ->setTitle("Office 2007 - Document")
                ->setSubject("Office 2007 - Document")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory("");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();        
        $sheet->setTitle('Төлөвлөлтийн нэгтгэл');

        $i = 2;
        $headerCount = 0;
        $sheet->setCellValue('A1', '№');
        $sheet->setCellValue('B1', $result['getRows']['freeze'][0]['title']);
        foreach ($headerData as $key => $row) {
            $sheet->setCellValue(numToAlpha($key + 3) . '1', $row['title']);
            $headerCount++;
        }

        $style = array();
        $columnAlpha = array();
        
        if(!empty($detailData)) {
            foreach ($detailData as $key => $value) {
                $sheet->setCellValue(numToAlpha(1) . $i, $i - 1);
                $cellValue = isset($value[$result['getRows']['freeze'][0]['field']]) ? $value[$result['getRows']['freeze'][0]['field']] : '';
                $cellValue = strip_tags(str_replace('#', '     ', $cellValue));
                $sheet->setCellValueExplicit("B" . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                    
                foreach ($headerData as $k => $item) {
                    $cellValue = isset($value[$item['field']]) ? (is_numeric($value[$item['field']]) ? sprintf('%.2f', $value[$item['field']]) : $value[$item['field']]) : '';
                    $numToAlpha = numToAlpha($k + 3);
                    
                    if(is_numeric($cellValue))
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);                    						
                    else
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);                    
                }
                $i++;
            }
        }

        $sheet->freezePane('A2');

        $headerDataCount = $headerCount;
        foreach (range(numToAlpha(1), numToAlpha($headerDataCount + 2)) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->getStyle('A1:' . numToAlpha($headerDataCount + 2) . '1')->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
                'borders' => array(
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '74ad42')
                )
            )
        );
//        var_dump("success"); die;
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="Төлөвлөлтийн нэгтгэл - ' . Date::currentDate('YmdHi') . '.xlsx"');
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
            echo $e->getMessage();
            exit();
        }
    }
    
    public function saveActivityCtrl() {
        $result = $this->model->saveActivityModel();
        echo json_encode($result);
    }    
    
    public function saveActivityAccountCtrl() {
        $result = $this->model->saveActivityAccountModel();
        echo json_encode($result);
    }    
    
    public function getBtnActivityCtrl() {
        $result = $this->model->getBtnActivityModel();
        
        if ($result !== false) {
            $result = array(
                'status' => 'success',
                'getRow' => $result
            );
            echo json_encode($result);
        } else
            echo json_encode(array('status' => 'no_btn'));
    }
    
    public function actionActivitySheetNotZeroCtrl() {
        $result = $this->model->actionActivitySheetNotZeroModel();
        echo json_encode($result);
    }    
    
    public function saveActivityPartialCtrl() {
        $result = $this->model->saveActivityPartialModel();
        echo json_encode($result);
    }       
    
    public function saveActivityTemplatePartialCtrl() {
        $result = $this->model->saveActivityTemplatePartialModel();
        echo json_encode($result);
    }       
    
    public function getPeriodActivityCtrl() {
        $result = $this->model->getPeriodActivityModel(Input::postData());
        echo json_encode($result);
    }    
    
    public function updateTemplateActivityCtrl() {
        $this->model->updateTemplateActivityModel(Input::postData());
        echo 'success';
    }    
    
    public function updateTemplateAccount2ActivityCtrl() {
        $this->model->updateTemplateAccount2ActivityModel(Input::postData());
        echo 'success';
    }    
    
    public function reorderActivityCtrl() {
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        $this->load->model('amactivity', 'middleware/models/');
        $this->view->getRows = $this->model->reorderActivityModel();
        
        $response = array(
            'html' => $this->view->renderPrint('sub/reorderList', self::$viewPath),
            'title' => 'Дараалал тохируулах',
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );        
        echo json_encode($response);
    }       
    
    public function dimensionDVCtrl() {
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        $this->load->model('amactivity', 'middleware/models/');
        $this->view->getRows = $_POST['sendRows'];
        
        $response = array(
            'html' => $this->view->renderPrint('sub/dimensionDVlist', self::$viewPath),
            'title' => 'Choose dimension',
            'choose_btn' => Lang::line('choose_btn'),
            'close_btn' => Lang::line('close_btn')
        );        
        echo json_encode($response);
    }       
    
    public function dimensionDimCtrl() {
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        $this->load->model('amactivity', 'middleware/models/');
        $this->view->getRows = $_POST['sendRows'];
        
        $response = array(
            'html' => $this->view->renderPrint('sub/dimensionDimlist', self::$viewPath),
            'title' => 'Dimension',
            'choose_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );        
        echo json_encode($response);
    }       
    
    public function saveActivityTemplateCtrl() {
        $result = $this->model->saveActivityTemplateModel();
        echo json_encode($result);
    }        
    
    public function getAllActivityTemplateCtrl() {
        $result = $this->model->getAllActivityTemplateModel();
        echo json_encode($result);
    }    
    
    public function aggregate() {
        $this->load->model('amactivity', 'middleware/models/');
        $this->view->title = "Төлөвлөлтийн нэгтгэл харах";
        $this->view->activityKeyId = is_null(Input::post('activityKeyId')) ? false : Input::post('activityKeyId');
        
        $inputParam = array(
            'activityKeyId' => $this->view->activityKeyId
        );
        
        $this->view->uniqId = getUID();
        $resultPeriod = $this->model->getPeriodActivityModel($inputParam);
        
        $this->view->getAllActivityPeriod = (isset($resultPeriod['getRows']['period'])) ? $resultPeriod['getRows']['period'] : array();
        $this->view->getRowActivityKey = $this->model->getRowActivityKeyModel($this->view->activityKeyId);
        $this->view->activityKey = $this->model->getActivityKeyModel($this->view->activityKeyId);
        $this->view->attachFiles = $this->model->getActivityFilesModel($inputParam);     
        $this->view->getCombo = $this->model->getActivityComboModel($inputParam);
        $this->view->getComboText = $this->model->getActivityComboTextModel($this->view->getCombo);
        $this->activityAssets();
        
        $this->view->wfmlog = $this->view->workspaceId = $this->view->selectedRowData = $this->view->dmMetaDataId = $this->view->methodId = $this->view->wfmStatusBtns =  '';
        if (Input::postCheck('selectedRow') && Input::isEmpty('selectedRow') === false) {
            $selectedRow = Arr::decode(Input::post('selectedRow'));
            $this->view->wfmlog =  self::getRowWfmStatusForm($selectedRow);
            
            $this->load->model('mdobject', 'middleware/models/');
                
            $this->view->selectedRowData = $selectedRow['dataRow'];
            $this->view->dmMetaDataId = $selectedRow['metaDataId'];
            $this->view->workspaceId = $selectedRow['workspaceId'];
            $this->view->methodId = $selectedRow['refStructureId'];
            $this->view->refStructureId = $selectedRow['refStructureId'];
            $this->view->wfmStatusBtns =  $this->model->getWorkflowNextStatusModel($this->view->dmMetaDataId, $this->view->selectedRowData, '0');
            
        }
        
        $this->load->model('mdsalary', 'middleware/models/');
        $this->view->lookUpCalc = $this->model->getLookUpCalcModel('AM_ACTIVITY_KEY_List');
        
        
        if(!$this->view->activityKeyId) {
            die('<h3><center>Workspace Menu тохиргоо буруу байна!</center></h3>');
        }
        
        if (!is_ajax_request()) {
            die('<h3><center>Төсөв төлөвлөлтийн цэснээс орно уу! :(</center></h3>');
            $this->view->render('header');
            $this->view->render('aggregate', self::$viewPath);
            $this->view->render('footer');
        } else {
            echo $this->view->renderPrint('aggregate', self::$viewPath);
        }
    }    
    
    public function aggregate2() {
        $this->load->model('amactivity', 'middleware/models/');
        $this->view->title = "Төлөвлөлтийн нэгтгэл харах";
        $this->view->activityKeyId = is_null(Input::post('activityKeyId')) ? false : Input::post('activityKeyId');
        
        $inputParam = array(
            'activityKeyId' => $this->view->activityKeyId
        );
        
        $this->view->uniqId = getUID();
        $resultPeriod = $this->model->getPeriodActivityModel($inputParam);
        
        $this->view->getAllActivityPeriod = (isset($resultPeriod['getRows']['period'])) ? $resultPeriod['getRows']['period'] : array();
        $this->view->getRowActivityKey = $this->model->getRowActivityKeyModel($this->view->activityKeyId);
        $this->view->activityKey = $this->model->getActivityKeyModel($this->view->activityKeyId);
        $this->view->attachFiles = $this->model->getActivityFilesModel($inputParam);     
        $this->view->getCombo = $this->model->getActivityComboModel($inputParam);
        $this->view->getComboText = $this->model->getActivityComboTextModel($this->view->getCombo);
        $this->activityAssets();
        
        $this->view->wfmlog = $this->view->workspaceId = $this->view->selectedRowData = $this->view->dmMetaDataId = $this->view->methodId = $this->view->wfmStatusBtns =  '';
        if (Input::postCheck('selectedRow') && Input::isEmpty('selectedRow') === false) {
            $selectedRow = Arr::decode(Input::post('selectedRow'));
            $this->view->wfmlog =  self::getRowWfmStatusForm($selectedRow);
            
            $this->load->model('mdobject', 'middleware/models/');
                
            $this->view->selectedRowData = $selectedRow['dataRow'];
            $this->view->dmMetaDataId = $selectedRow['metaDataId'];
            $this->view->workspaceId = $selectedRow['workspaceId'];
            $this->view->methodId = $selectedRow['refStructureId'];
            $this->view->refStructureId = $selectedRow['refStructureId'];
            $this->view->wfmStatusBtns =  $this->model->getWorkflowNextStatusModel($this->view->dmMetaDataId, $this->view->selectedRowData, '0');
            
        }
        
        $this->load->model('mdsalary', 'middleware/models/');
        $this->view->lookUpCalc = $this->model->getLookUpCalcModel('AM_ACTIVITY_KEY_List');
        
        
        if(!$this->view->activityKeyId) {
            die('<h3><center>Workspace Menu тохиргоо буруу байна!</center></h3>');
        }
        
        if (!is_ajax_request()) {
            die('<h3><center>Төсөв төлөвлөлтийн цэснээс орно уу! :(</center></h3>');
            $this->view->render('header');
            $this->view->render('aggregate2', self::$viewPath);
            $this->view->render('footer');
        } else {
            echo $this->view->renderPrint('aggregate2', self::$viewPath);
        }
    }    
    
    public function getAllPeriodActivityCtrl() {
        $result = $this->model->getAllPeriodActivityModel();
        echo json_encode($result);
    }    
    
    public function getAllPeriodActivity2Ctrl() {
        $result = $this->model->getAllPeriodActivity2Model();
        echo json_encode($result);
    }    
    
    public function reorderActivitySaveCtrl() {
        $result = $this->model->reorderActivitySaveModel();
        echo json_encode($result);
    }    
    
    public function expressionActivitySaveCtrl() {
        $result = $this->model->expressionActivitySaveModel();
        echo json_encode($result);
    }    
    
    public function expressionActivityTemplateSaveCtrl() {
        $result = $this->model->expressionActivityTemplateSaveModel();
        echo json_encode($result);
    }    
    
    public function dataAggregateExcelExport() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $result = $this->model->getAllPeriodActivityModel();
        
        $headerTopData = (isset($result['getRows']['firstHeader']) && $result['getRows']['firstHeader']) ? $result['getRows']['firstHeader'] : '';
        $headerFreezeData = (isset($result['getRows']['freeze']) && $result['getRows']['freeze']) ? $result['getRows']['freeze'] : '';
        
        $headerData = $result['getRows']['header'];
        $detailData = $result['getRows']['detail']['rows'];
        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Veritech ERP")
                ->setLastModifiedBy("")
                ->setTitle("Office 2007 - Document")
                ->setSubject("Office 2007 - Document")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory("");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();        
        $sheet->setTitle('Төлөвлөлтийн нэгтгэл');
        
        $index = ($headerTopData) ? 2 : 1;
        
        $addin = ($headerTopData) ? '2' : '1';
        $i = ($headerTopData) ? 3 : 2;
        $headerCount = 0;
        $headerIndex = '1';
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', '№');
        $indexKey = $sizeofFreeze = 0;
        
        if ($headerFreezeData) {
            $sizeofFreeze = sizeof($headerFreezeData);
            foreach ($headerFreezeData as $key => $row) {
                $numToAlpha = numToAlpha($key + 2);
                
                $sheet->mergeCells($numToAlpha . '1:'. $numToAlpha .'2');
                $sheet->setCellValue($numToAlpha . '1', $row['title']);
                $headerCount++;
            }
            
        } 
        
        if ($headerTopData) {
            foreach ($headerTopData as $k => $item) {
                if($k == 0) {
                        $kstartIndex = $k + $sizeofFreeze + 2;
                        $kendIndex = $k + $sizeofFreeze + $item['colspan'] + 1;
                } else {
                        $kstartIndex = $kendIndex + 1;
                        $kendIndex = $kendIndex + $item['colspan'];
                }

                $sheet->mergeCells(numToAlpha($kstartIndex) . '1:' . numToAlpha($kendIndex) . '1');
                $sheet->setCellValue(numToAlpha($kstartIndex) . '1', $item['title']);				
            }
            $headerIndex = '2';
        }
        
        foreach ($headerData as $key => $row) {
            $sheet->setCellValue(numToAlpha($key + $sizeofFreeze + 2) . $headerIndex, $row['title']);
            $headerCount++;
        }
        $style = array();
        $headerDataCount = $headerCount;  
        foreach (range('B', numToAlpha($kendIndex)) as $columnId) {
            $sheet->getColumnDimension($columnId)->setAutoSize(true);
        }
        /*foreach(range('B','ZZ') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }*/        
        
        if(!empty($detailData)) {
            foreach ($detailData as $key => $value) {
                $sheet->setCellValue(numToAlpha(1) . $i, $index - 1);
                if ($headerFreezeData) {
                    foreach ($headerFreezeData as $k => $item) {
                        $cellValue = '';
                        $numToAlpha = numToAlpha($k + 2);
                        
                        if(isset($value[$item['field']])) {
                            /*if(strpos(html_entity_decode($value[$item['field']], ENT_QUOTES, 'UTF-8'), '<strong>') !== false) {
                                $sheet->getStyle('B'.$k + 3)->getFont()->setBold(true);
                            }*/
                            $cellValue = strip_tags(str_replace('#', '     ', $value[$item['field']]));
                        }
                        
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);                        
                    }
                }
                
                foreach ($headerData as $k => $item) {
                    $cellValue = isset($value[$item['field']]) ? (is_numeric($value[$item['field']]) ? sprintf('%.2f', $value[$item['field']]) : $value[$item['field']]) : '';
                    $numToAlpha = numToAlpha($k + 3);
                    if(is_numeric($cellValue))
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);                    						
                    else
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);    
                }
                
                $i++;
                $index++;
            }
        }
        
        $sheet->getStyle('B1:'.numToAlpha($kendIndex).($index-1))->getNumberFormat()->setFormatCode('#,##0.00');        
        $sheet->freezePane('A2');

        $sheet->getStyle('A1:' . numToAlpha($headerDataCount + 1) . $addin)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
                'borders' => array(
                    'bottom' => array(
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
            header('Content-Disposition: attachment;filename="Төлөвлөлтийн нэгтгэл - ' . Date::currentDate('YmdHi') . '.xlsx"');
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
            echo $e->getMessage();
            exit();
        }
    }    
    
    public function dataAggregateExcelExport2() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $result = $this->model->getAllPeriodActivity2Model();        
        
        $headerTopData = (isset($result['getRows']['firstHeader']) && $result['getRows']['firstHeader']) ? $result['getRows']['firstHeader'] : '';
        $headerFreezeData = (isset($result['getRows']['freeze']) && $result['getRows']['freeze']) ? $result['getRows']['freeze'] : '';
        
        $headerData = $result['getRows']['header'];
        $detailData = $result['getRows']['detail']['rows'];
        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Veritech ERP")
                ->setLastModifiedBy("")
                ->setTitle("Office 2007 - Document")
                ->setSubject("Office 2007 - Document")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory("");
        
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();        
        $sheet->setTitle('Төлөвлөлтийн нэгтгэл');
        
        $index = ($headerTopData) ? 2 : 1;
        
        $addin = ($headerTopData) ? '2' : '1';
        $i = ($headerTopData) ? 3 : 2;
        $headerCount = 0;
        $headerIndex = '1';
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', '№');
        $indexKey = $sizeofFreeze = 0;
        
        if ($headerFreezeData) {
            $sizeofFreeze = sizeof($headerFreezeData);
            foreach ($headerFreezeData as $key => $row) {
                $numToAlpha = numToAlpha($key + 2);
                
                $sheet->mergeCells($numToAlpha . '1:'. $numToAlpha .'2');
                $sheet->setCellValue($numToAlpha . '1', $row['title']);
                $headerCount++;
            }
            
        } 
        
        if ($headerTopData) {
            foreach ($headerTopData as $k => $item) {
                if($k == 0) {
                        $kstartIndex = $k + $sizeofFreeze + 2;
                        $kendIndex = $k + $sizeofFreeze + $item['colspan'] + 1;
                } else {
                        $kstartIndex = $kendIndex + 1;
                        $kendIndex = $kendIndex + $item['colspan'];
                }

                $sheet->mergeCells(numToAlpha($kstartIndex) . '1:' . numToAlpha($kendIndex) . '1');
                $sheet->setCellValue(numToAlpha($kstartIndex) . '1', $item['title']);				
            }
            $headerIndex = '2';
        }
        
        foreach ($headerData as $key => $row) {
            $sheet->setCellValue(numToAlpha($key + $sizeofFreeze + 2) . $headerIndex, $row['title']);
            $headerCount++;
        }
        $style = array();
        $headerDataCount = $headerCount;
        $columnAlpha = array();
        
        foreach(range('B','ZZ') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        if(!empty($detailData)) {
            foreach ($detailData as $key => $value) {
                $sheet->setCellValue(numToAlpha(1) . $i, $index - 1);
                
                if ($headerFreezeData) {
                    foreach ($headerFreezeData as $k => $item) {
                        $cellValue = isset($value[$item['field']]) ? strip_tags(str_replace('#', '     ', $value[$item['field']])) : '';

                        $numToAlpha = numToAlpha($k + 2);
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                }
                
                foreach ($headerData as $k => $item) {
                    $cellValue = isset($value[$item['field']]) ? (is_numeric($value[$item['field']]) ? sprintf('%.2f', $value[$item['field']]) : $value[$item['field']]) : '';
                    //$cellValue = Number::formatMoney($cellValue);
                    $numToAlpha = numToAlpha($k + 3);
                    if(is_numeric($cellValue))
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);                    						
                    else
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);    
                }
                
                $i++;
                $index++;
            }
        }
        
        $sheet->freezePane('A2');
        $sheet->getStyle('B1:'.numToAlpha($kendIndex).($index-1))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('A1:' . numToAlpha($headerDataCount + 1) . $addin)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
                'borders' => array(
                    'bottom' => array(
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
            header('Content-Disposition: attachment;filename="Төлөвлөлтийн нэгтгэл - ' . Date::currentDate('YmdHi') . '.xlsx"');
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
            echo $e->getMessage();
            exit();
        }
    }    
    
    public function dataAggregatePdfExport() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $result = $this->model->getAllPeriodActivityModel();
        $headerData = $result['getRows']['header'];
        $detailData = $result['getRows']['detail']['rows'];
        
        $htmlContent = '';
        if(!empty($detailData)) {
            $htmlContent = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body><table style="border-collapse: collapse">
                    <thead>
                        <tr>
                            <th style="width: 20px; border: 1px solid black;">№</th>
                            <th style="border: 1px solid black;">Тайлбар</th>';
                            foreach ($headerData as $k => $item) {
                                $htmlContent .= "<th style='border: 1px solid black;'>".$item['title']."</th>";
                            }                            
                    $htmlContent .= '</tr>
                    </thead>
                    <tbody>';
                    foreach ($detailData as $key => $value) {
                        $htmlContent .= "<tr>";
                        $htmlContent .= "<td style='border: 1px solid black;'>".++$key."</td>";
                        $cellValue = isset($value[$result['getRows']['freeze'][0]['field']]) ? $value[$result['getRows']['freeze'][0]['field']] : '';
                        $cellValue = strip_tags(str_replace('#', '     ', $cellValue));                       
                        $htmlContent .= "<td style='border: 1px solid black;'>".$cellValue."</td>";
                        foreach ($headerData as $k => $item) {
                            if(isset($value[$item['field']])) {
                                $htmlContent .= "<td style='border: 1px solid black;'>".(is_numeric($value[$item['field']]) ? sprintf('%.2f', $value[$item['field']]) : $value[$item['field']])."</td>";
                            } else
                                $htmlContent .= "<td style='border: 1px solid black;'></td>";
                        }
                        $htmlContent .= "</tr>";              
                    }
            $htmlContent .= '</tbody>
                </table></body></html>';
        }        

        includeLib('PDF/Pdf');
        
        $orientation        = Input::post('orientation');
        $size               = Input::post('size');

        $_POST['isSmartShrinking'] = '1';
        
        $htmlContent = str_replace("\xE2\x80\x8B", '', $htmlContent);   
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        $htmlContent = str_replace('  ', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);
        $htmlContent = str_replace(array('<nobr>', '</nobr>'), '', $htmlContent);
        
        preg_match_all('/([A-Za-zА-Яа-яӨҮөү0-9])(&nbsp;)([A-Za-zА-Яа-яӨҮөү0-9])/u', $htmlContent, $replaceMatches);
        
        if (isset($replaceMatches[0][0])) {
            foreach ($replaceMatches[0] as $replaceMatch) {
                $htmlContent = str_replace($replaceMatch, str_replace('&nbsp;', ' ', $replaceMatch), $htmlContent);
            }
        }
        
        $css = '';
        $_POST['isIgnoreFooter'] = 1;
        
        $reportName       = 'Төлөвлөлтийн нэгтгэл - ' . Date::currentDate('YmdHi');
        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? strtoupper($size) : 'A4'));
        Pdf::setSnappyOutput($pdf, $css . $htmlContent, $reportName);        
    }    
    
    public function amactivityduplicate() {        
        if(is_null(Input::post('version')))
            return;
        
        $result = $this->model->activityDuplicateModel();
        echo json_encode($result);
    }    
    
    public function duplicatetemplate() {
        $result = $this->model->duplicateTemplateModel();
        echo json_encode($result);
    }    
    
    public function copytemplate() {
        if(is_null(Input::post('code')) || is_null(Input::post('description')))
            return;
        
        $result = $this->model->activityTemplateDuplicateModel();
        echo json_encode($result);
    }        
    
    public function processList() {
        echo json_encode($this->model->processListModel());
    }        
    
    public function refreshFileMainHeaderCtrl() {
        $this->load->model('amactivity', 'middleware/models/');
        
        $inputParam = array(
            'activityKeyId' => Input::post('activityKeyId')
        );
        $this->view->attachFiles = $this->model->getActivityFilesModel($inputParam);
        
        echo $this->view->renderPrint('sub/updateFileList', self::$viewPath);
    }    
    
    public function activityExpressionForm() {
        if (!isset($this->view)) {
            $this->view = new View();
        }   
        
        $this->view->uniqId = getUID();
        $this->view->metaCode = Input::post('rowMetaCode');
        $this->view->metaName = Input::post('rowMetaName');
        $expression = Input::post('expression');
        $this->view->metas = $this->model->expressionFunctionListModel();
        $this->view->templateList = $this->model->expressionTemplateListModel();
        
        $this->view->expression = '';
        $this->view->metaList = '';
        
        if ($this->view->metas) {
            
            $search = array('==', '&&', '||'); 
            $replace = array('=', 'and', 'or');
            
            foreach ($this->view->metas as $meta) {
                $this->view->metaList .= '<li data-code="'.$meta['metadatacode'].'" title="'.$meta['metadatacode'].'">'.$meta['metadataname'].'</li>';
                $expression = preg_replace('/\b'.$meta['metadataname'].'\b/u', '<span class="p-exp-meta" contenteditable="false" data-code="'.$meta['metadataname'].'">'.$meta['metadataname'].'<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $expression);
            }
            
            $this->view->expression = str_replace($search, $replace, $expression);
        }
        
        $response = array(
            'html' => $this->view->renderPrint('sub/expressionForm', self::$viewPath)
        );
        echo json_encode($response);
    }
    
    public function factListByTemplateId() {
        $factList = $this->model->factListByTemplateIdModel();
        echo count($factList) > 0 ? json_encode(array('status' => 'success', 'rows' => $factList)) : json_encode(array('status' => 'error', 'text' => ''));
    }
    
    public function commentActivitySheetCtrl() {
        $result = $this->model->commentActivitySheetCtrlModel();
        echo json_encode($result);
    }
    
    public function getCommentActivitySheetCtrl() {
        $result = $this->model->getCommentActivitySheetCtrlModel();
        echo json_encode($result);
    }
    
    public function getDrillParams() {
        echo json_encode($this->model->getDrillParamsModel(Input::post('activityKeyId'), Input::post('dvId')));
    }          
    
    public function getColumnListCtrl() {
        echo json_encode($this->model->getColumnListModel(Input::post('activityKeyId')));
    }          
}
