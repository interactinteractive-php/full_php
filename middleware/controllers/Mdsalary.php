<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdsalary Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Salary 
 * @author	Ts.Ulaankhuu <ulaankhuu@veritech.mn>
 * @link	http://www.interactive.mn/mdsalary
 */
class Mdsalary extends Controller {

    private static $viewPath = 'middleware/views/salary/';

    public function __construct() {
      parent::__construct();
      Auth::handleLogin();    
    }

    public function salary_v3() {

      if (!isset($this->view)) {
          $this->view = new View();
      }

      $this->view->title = "Цалин бодох";
      $this->view->lookUpCalc = $this->model->getLookUpCalcModel('PRL_CALC_DV');            
      $this->view->lookUpCalcType = $this->model->lookUpCalcTypeModel('PRL_CALC_TYPE_TEMPLATE1');    
      $this->view->criteriaTemplate = $this->view->getSuggestionInfo = array();
      
      $this->load->model('mdsalary', 'middleware/models/');
            
      $this->view->lookUpEmployee = $this->model->getLookUpCalcModel('PAYROLL_EMPLOYEE_LIST');
      $this->view->singleEditMode = false;
      $this->view->isChange = '0';
      
      if (Input::postCheck('isChange')) {
          $this->view->isChange = '1';      
      }

      if (Input::postCheck('id')) {
          $salaryBookId = Input::post('id');
          $this->view->salaryBookInfo = $this->model->savedSalaryBookInfoModel($salaryBookId);
          $this->view->salaryBookId = $salaryBookId;
          $this->view->selectedDepsCount = 1;
          $this->view->singleEditMode = true;

      } elseif (Input::postCheck('ids')) {
          $salaryBookId = Input::post('ids');
          $getBook = $this->model->savedSalaryBookInfoPackageModel($salaryBookId);

          $this->view->salaryBookInfo['CALC_ID'] = $getBook[0]['CALC_ID'];
          $this->view->salaryBookInfo['CALC_CODE'] = $getBook[0]['CALC_CODE'];
          $this->view->salaryBookInfo['CALC_NAME'] = $getBook[0]['CALC_NAME'];
          $this->view->salaryBookInfo['CALC_TYPE_ID'] = $getBook[0]['CALC_TYPE_ID'];
          $this->view->salaryBookInfo['CALC_TYPE_CODE'] = $getBook[0]['CALC_TYPE_CODE'];
          $this->view->salaryBookInfo['CALC_TYPE_NAME'] = $getBook[0]['CALC_TYPE_NAME'];
          $this->view->salaryBookInfo['START_DATE'] = $getBook[0]['START_DATE'];
          $this->view->salaryBookInfo['END_DATE'] = $getBook[0]['END_DATE'];
          $this->view->salaryBookInfo['DEPARTMENT_ID'] = '';        
          $this->view->salaryBookInfo['DEPARTMENT_NAME'] = '';        
          $this->view->salaryBookId = $salaryBookId;
          $this->view->selectedDepsCount = count($getBook);
          $this->view->batchNumber = '';
          
          array_walk($getBook, function($val, $key){
              $this->view->salaryBookInfo['DEPARTMENT_ID'] .= $val['DEPARTMENT_ID'] . ',';
              $this->view->salaryBookInfo['DEPARTMENT_NAME'] .= $val['DEPARTMENT_NAME'] . '__';
          });
          
          $this->view->salaryBookInfo['DEPARTMENT_ID'] = rtrim($this->view->salaryBookInfo['DEPARTMENT_ID'], ',');
          $this->view->salaryBookInfo['DEPARTMENT_NAME'] = rtrim($this->view->salaryBookInfo['DEPARTMENT_NAME'], '__');
          
      } else {
          $this->view->salaryBookId = '';
        if ($getSuggestionInfo = $this->model->getSuggestionCalcRowModel()) {
            $this->view->getSuggestionInfo = $getSuggestionInfo;
        }          
      }
      $this->salaryAssets();

      $this->view->isAjax = true;
      $this->view->uniqId = getUID();

      if (!is_ajax_request()) {
          $this->view->isAjax = false;
          $this->view->render('header');
      }

      $this->view->render('salaryV3', self::$viewPath);

      if (!is_ajax_request()) $this->view->render('footer');
    }

    public function salaryAssets() {
      $this->view->css = array(
          'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
          'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
      );
      $this->view->js = array(
          'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
          'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
          'custom/addon/plugins/phpjs/phpjs.min.js'
      );
      $this->view->fullUrlJs = array('middleware/assets/js/salary/salaryV3.js');
    }
    public function getCalcFieldV3List() {
      $data = $this->model->getCalcFieldListV3Model();
      echo json_encode($data); exit;
    }
    
    public function getSalaryListWebservice() {
        $data = $this->model->getSalarySheetWebserviceModel();
        
        if (isset($data['footer'])) {
            unset($data['footer'][0]['picture']);  
        }

        header('content-type: application/json; charset=utf-8');
        echo json_encode($data); exit;
    }    
    
    public function getSalaryAllListWebservice() {
        $data = $this->model->getSalaryAllSheetWebserviceModel();
        
        if (isset($data['footer'])) {
            unset($data['footer'][0]['picture']);  
        }

        header('content-type: application/json; charset=utf-8');
        echo json_encode($data); exit;
    }    

    public function calculateSalaryListWebservice() {
      $data = $this->model->calculateSalarySheetWebserviceModel();
      echo json_encode($data); exit;
    }

    public function copyFieldRowSheetWebservice() {
      $data = $this->model->copyFieldRowSheetWebserviceModel();
      echo json_encode($data); exit;
    }

    public function lockFieldRowSheetWebservice() {
      $data = $this->model->lockFieldRowSheetWebserviceModel();
      echo json_encode($data); exit;
    }

    public function copyMultiFieldRowSheetWebservice() {
        $data = $this->model->copyMultiFieldRowSheetWebserviceModel();
        echo json_encode($data); exit;
    }    

    public function getFilterValuesWebservice() {
      $data = $this->model->getFilterValuesWebserviceModel();
      echo json_encode($data); exit;
    }

    public function saveSalarySheetWebservice() {
      $data = $this->model->saveSalarySheetWebserviceModel();
      echo json_encode($data); exit;
    }

    public function saveChangeSalarySheetWebservice() {
      $data = $this->model->saveChangeSalarySheetWebserviceModel();
      echo json_encode($data); exit;
    }

    public function copyFieldColumnSheetWebservice() {
      $data = $this->model->copyFieldColumnSheetWebserviceModel();
      echo json_encode($data); exit;
    }  

    public function saveCacheSalarySheetWebservice() {
      $data = $this->model->saveCacheSalarySheetWebserviceModel(Input::post('sheetData'), Input::post('dataIndex'));
      $data = is_null($data) ? array('status' => '*** Veritech ERP - SALARY ***') : $data;
      echo json_encode($data); exit;
    }  

    public function appendEmployeeSheetWebservice() {
      $data = $this->model->appendEmployeeSheetWebserviceModel();
      echo json_encode($data); exit;
    }  

    public function deleteEmployeeSheetWebservice() {
      $data = $this->model->deleteEmployeeSheetWebserviceModel();
      echo json_encode($data); exit;
    }  

    public function deleteEmployeesSheetWebservice() {
      $data = $this->model->deleteEmployeesSheetWebserviceModel();
      echo json_encode($data); exit;
    }  

    public function getCalculatedSalarySheetList() {
      $data = $this->model->getCalculatedSalarySheetListModel();
      echo json_encode($data); exit;
    }

    public function saveSalarySheetList() {
      $data = $this->model->saveSalarySheetListModel();
      echo json_encode($data); exit;
    }

    public function addEmployeeForm() {
      $this->view->departmentList = $this->model->getDepartmentList();
      $this->view->uniqId = Input::post('uniqId');
      $this->view->render('/dialog/addEmployee', self::$viewPath);
    }
    public function selectDepartment(){
        $this->view->departmentList = $this->model->getDepartmentList();
        $this->view->uniqId = Input::post('uniqId');
        $this->view->selectDepartments =  $this->model->selectDepartmentModel();
        $this->view->render('/dialog/selectDepartment', self::$viewPath);
    }
    public function confirmSelectedEmployeeRows(){
        $data = $this->model->confirmSelectedEmployeeRowsModel();
        echo json_encode($data); exit;
    }
    public function getEmployeeList() {
      $data = $this->model->getEmployeeListModel();
      echo json_encode($data); exit;
    }

    public function getSelectedEmployeeList() {
      $data = $this->model->getSelectedEmployeeListModel();
      echo json_encode($data); exit;
    }

    public function getSelectedEmployeeRows() {
      $result = $this->model->getSelectedEmployeeRowsModel();
      echo json_encode($result); exit;
    }

    public function getSideBarEmployeeInfo() {
      $this->load->model('mdsalary', 'middleware/models/');
      $data = $this->model->getSideBarEmployeeInfoModel();
      echo json_encode($data); exit;
    }
    
    public function salaryedit() {
      if (!isset($this->view)) {
        $this->view = new View();
      }
      $this->view->title = "Цалин бодох";
      $this->view->departmentList = $this->model->getDepartmentList();
      $this->view->calcTypeList = $this->model->getCalcTypeListModel();
      $salaryBookId = Input::post('id');
      $this->view->salaryBookId = Input::post('id');
      $this->view->salaryBookInfo = $this->model->salaryBookInfoModel($salaryBookId);
      $this->salaryAssets();

      if (!is_ajax_request()) $this->view->render('header');

      $this->view->render('salaryEdit', self::$viewPath);

      if (!is_ajax_request()) $this->view->render('footer');
    }

    public function getFieldExpression() {
      $result = $this->model->getFieldExpressionModel();
      echo json_encode($result); exit;
    }

//  public function calcTypeFieldList() {
//    $result = $this->model->calcTypeFieldListModel();
//    echo json_encode($result);
//  }
  
    public function getDeparmentListJtreeData() {
        $response = $this->model->getDeparmentListJtreeDataModel(Input::get('parentId'), Input::get('parentNode'));
        echo json_encode($response); exit;
    }
    
    public function departmentList() {
        $result = $this->model->getSearchDepartmentListModel();
        echo json_encode($result); exit;
    }
    
    public function getLogInformation() {
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        
        $this->view->getRowsLog = $this->model->getSheetLogListModel();
        
        $response = array(
            'html' => $this->view->renderPrint('dialog/getLogInformation', self::$viewPath),
            'title' => 'Өөрчлөлтийн түүх',
            'close_btn' => Lang::line('close_btn')
        );        
        echo json_encode($response); exit;
    }    
    
    public function setSalaryColumnOrder() {
        $result = $this->model->setSalaryColumnOrderModel();
        echo json_encode($result); exit;
    }    
    
    public function prlCalcTypeDtlByTypeIdList() {
        $result = $this->model->prlCalcTypeDtlByTypeIdModel();
        echo json_encode($result); exit;
    }
    
    public function getTemplateSheetExcelCtrl() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $paramsString = 'calcTypeId='.Input::get('calcTypeId').'&calcId='.Input::get('calcId').'&employeeIds='.Input::get('employeeIds').'&departmentId='.Input::get('departmentId').'&salaryBookId='.Input::get('salaryBookId');
        $_POST['params'] = $paramsString;
        
        $result = $this->model->getCalcFieldListImportExcelModel(); 
        $headerData = $result['fields'];        
        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Veritech ERP")
                ->setLastModifiedBy("")
                ->setTitle("Veritech ERP")
                ->setSubject("Veritech ERP")
                ->setDescription("Veritech ERP")
                ->setKeywords("Veritech ERP")
                ->setCategory("");
        
        $sheet = $objPHPExcel->getActiveSheet();
        
        $sheet->setTitle('Цалин Загвар - Veritech ERP');

        $headerCount = 0;
        $sheet->setCellValue('A1', 'employeecode');
        $sheet->setCellValue('A2', 'Код');
        $sheet->setCellValue('B1', 'lastname');
        $sheet->setCellValue('B2', 'Овог');
        $sheet->setCellValue('C1', 'firstname');
        $sheet->setCellValue('C2', 'Нэр');
        
        foreach ($headerData as $key => $row) {
            $sheet->setCellValue(numToAlpha($key + 4) . '1', $row['META_DATA_CODE']);
            $sheet->setCellValue(numToAlpha($key + 4) . '2', $row['META_DATA_NAME']);
            $headerCount++;
        }
        
        if (Input::get('isEmployeeData') === '1') {
            $_POST['rows'] = 100000;
            $_POST['javaCacheId'] = Input::get('javaCacheId');
            $detailData = $this->model->getSalarySheetWebserviceModel();
            $i = 3;

            if($detailData['status'] === 'success' && count($detailData['rows']) > 0) {
                foreach ($detailData['rows'] as $key => $value) {
                    if(isset($value['employeecode'])) {
                        $cellValue = $value['employeecode'];
                        $numToAlpha = numToAlpha(1);
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    if(isset($value['lastname'])) {
                        $cellValue = $value['lastname'];
                        $numToAlpha = numToAlpha(2);
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                    } 
                    if(isset($value['firstname'])) {
                        $cellValue = $value['firstname'];
                        $numToAlpha = numToAlpha(3);
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                    }

                    foreach ($headerData as $k => $item) {
                        $cellValue = isset($value[$item['META_DATA_CODE']]) ? $value[$item['META_DATA_CODE']] : '';
                        $numToAlpha = numToAlpha($k + 4);
                        
                        if(is_numeric($cellValue))
                            $sheet->setCellValueExplicit($numToAlpha . $i, Number::numberFormat($cellValue, 3), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        else
                            $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    $i++;
                }    
            }
        }

        foreach (range(numToAlpha(1), numToAlpha($headerCount + 3)) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        $sheet->getStyle('A1:' . numToAlpha($headerCount + 3) . '1')->applyFromArray(
            array(
                'font' => array(
                    'bold' => false
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_NONE
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '89c059')
                )
            )
        );
        $sheet->getStyle('A2:' . numToAlpha($headerCount + 3) . '2')->applyFromArray(
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
                    'color' => array('rgb' => '89c059')
                )
            )
        );
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="Цалин Загвар - ' . Date::currentDate('YmdHi') . '.xlsx"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            ob_end_clean();
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
    
    public function salaryDataImportLoadData() {
        if (isset($_FILES['selectedExcelFile'])) {
            
            parse_str(Input::post('params'), $params);
            $importResult = $this->model->salaryDataImportLoadDataCustomFieldModel($_FILES['selectedExcelFile'], $params);
            echo json_encode($importResult); exit;

        } else {
            $response = array('status' => 'warning', 'text' => 'Файлаа сонгоно уу?');
        }
        
        echo json_encode($response); exit;
    }    
    
    public function salaryDataImportLoadCustomData() {
        $response = $this->model->salaryDataImportLoadDataModel();
        echo json_encode($response); exit;
    }    
    
    public function payrollExpressionForm() {
        
        $this->view->uniqId = getUID();
        $this->view->metaCode = Input::post('rowMetaCode');
        $this->view->metaName = Input::post('rowMetaName');
        
        $this->view->expression = '';
        $this->view->metaList = '';
        $this->view->isKpiField = false;
        
        $metas = Input::post('metas');
        $expression = Input::post('expression');
        $isMetaList = Input::post('isMetaList');
        
        $search = array('==', '&&', '||'); 
        $replace = array('=', 'and', 'or');
            
        if ($metas) {
            
            $isBracketExp = (Input::post('isBracketExp') == '1') ? true : false;
            $searchArr = $replaceArr = array();
            
            foreach ($metas as $k => $meta) {
                
                if (!empty($meta['metaCode'])) {
                    
                    $this->view->metaList .= '<li data-code="'.$meta['metaCode'].'" title="'.$meta['metaCode'].'">'.$meta['metaName'].'</li>';
                    
                    $searchArr[] = 'p_' . $k . '_code';
                    $searchArr[] = 'p_' . $k . '_name';
                    
                    $replaceArr[] = $meta['metaCode'];
                    $replaceArr[] = $meta['metaName'];
                    
                    if ($isBracketExp) {
                        $expression = str_replace($meta['metaCode'], '<span class="p-exp-meta" contenteditable="false" data-code="p_'.$k.'_code">p_'.$k.'_name<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $expression);
                    } else {
                        $expression = preg_replace('/\b'.$meta['metaCode'].'\b/u', '<span class="p-exp-meta" contenteditable="false" data-code="p_'.$k.'_code">p_'.$k.'_name<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $expression);
                    }
                }
            }
            
            $expression = str_replace($searchArr, $replaceArr, $expression);
            
            $this->view->expression = str_replace($search, $replace, $expression);
            
        } elseif ($isMetaList == 'false' && Input::isEmpty('rowMetaId') == false) {
            
            $metas = $this->model->getCalcTypeMetaListByIdModel(Input::post('rowMetaId'));

            if ($metas) {
                foreach ($metas as $meta) {
                
                    if (!empty($meta['META_DATA_CODE'])) {
                        
                        $metaGlobeName = Lang::line($meta['META_DATA_CODE']);
                        $this->view->metaList .= '<li data-code="'.$meta['META_DATA_CODE'].'" title="'.$meta['META_DATA_CODE'].'">'.$metaGlobeName.'</li>';

                        $expression = preg_replace('/\b'.$meta['META_DATA_CODE'].'\b/u', '<span class="p-exp-meta" contenteditable="false" data-code="'.$meta['META_DATA_CODE'].'">'.$metaGlobeName.'<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $expression);
                    }
                }
                
                $this->view->expression = str_replace($search, $replace, $expression);
            }
            
        } elseif ($isMetaList == 'false' && Input::post('tagsSource') == 'kpiTemplate') {
            
            $this->load->model('mdform', 'middleware/models/');
            
            $this->view->isKpiField = true;
            $this->view->kpiCalculateFunctions = $this->model->getKpiCalculateFunctionsModel();
            
            if ($expression) {
                preg_match_all('/\[(.*?)\]/i', $expression, $matches);
                
                if ($matches[0]) {
                    
                    foreach ($matches[0] as $ek => $ev) {
                        
                        $codes = explode('.', $matches[1][$ek]);
                        
                        if (count($codes) == 3) {
                            $tmpCode = $codes[0]; $indCode = $codes[1]; $factCode = $codes[2];
                            
                            $tmpRow = $this->model->getKpiTemplateRowByCodeModel($tmpCode);
                            
                            if ($tmpRow) {
                                $indRow = $this->model->getKpiIndicatorRowByCodeModel($tmpRow['ID'], $indCode);
                                
                                if ($indRow) {
                                    $factRow = $this->model->getKpiFactRowByCodeModel($tmpRow['ID'], $indRow['ID'], $factCode);
                                    
                                    if ($factRow) {
                                        $expression = str_replace($ev, '<span class="p-exp-kpifield" contenteditable="false" data-template-id="'.$tmpRow['ID'].'" data-template-code="'.$tmpRow['CODE'].'" data-indicator-id="'.$indRow['ID'].'" data-indicator-code="'.$indRow['CODE'].'" data-fact-id="'.$factRow['ID'].'" data-fact-code="'.$factRow['CODE'].'">'.
                                            '<span class="p-exp-kpifield-tmp" contenteditable="false">'.
                                                '<span class="p-exp-kpifield-title" title="'.$tmpRow['NAME'].'" contenteditable="false">'.$tmpRow['NAME'].'</span> '.
                                                '<span class="p-exp-meta-remove" contenteditable="false">x</span>'.
                                            '</span>'.
                                            '<span class="p-exp-kpifield-ind" contenteditable="false">'.
                                                '<span class="p-exp-kpifield-title" title="'.$indRow['NAME'].'" contenteditable="false">'.$indRow['NAME'].'</span> '.
                                                '<span class="p-exp-meta-remove" contenteditable="false">x</span>'.
                                            '</span>'.
                                            '<span class="p-exp-kpifield-fact" contenteditable="false">'.
                                                '<span class="p-exp-kpifield-title" title="'.$factRow['NAME'].'" contenteditable="false">'.$factRow['NAME'].'</span> '.
                                                '<span class="p-exp-meta-remove" contenteditable="false">x</span>'.
                                            '</span>'.
                                            '<span class="p-exp-meta-remove" contenteditable="false">x</span>'.
                                        '</span>', $expression);
                                    }
                                }
                            }
                        }
                    }
                }
                
                $this->view->expression = $expression;
            }
        }
        
        $response = [
            'html' => $this->view->renderPrint('dialog/payrollExpressionForm', self::$viewPath),
            'title' => 'Томъёо тохируулах', 
            'check_btn' => $this->lang->line('Шалгах')
        ];
        convJson($response);
    }
    
    public function validateExpression() {
        
        loadPhpQuery();
        
        $tagsSource = Input::post('tagsSource');
        $expressionContent = Input::postNonTags('expressionContent');
        
        $htmlObj = phpQuery::newDocumentHTML($expressionContent);  
        
        if ($tagsSource == 'kpiTemplate') {
            
            $matches = $htmlObj->find('span.p-exp-kpifield:not(:empty)');
            
            if ($matches->length) {
                
                foreach ($matches as $tag) {
                    $tmpCode = pq($tag)->attr('data-template-code');
                    $indCode = pq($tag)->attr('data-indicator-code');
                    $factCode = pq($tag)->attr('data-fact-code');
                    
                    pq($tag)->replaceWith("[$tmpCode.$indCode.$factCode]");
                }
                
                $expressionContent = $htmlObj->html();
                
                $_POST['isRun'] = 'hide';
            }
            
        } else {
            
            $matches = $htmlObj->find('span.p-exp-meta:not(:empty)');

            if ($matches->length) {

                foreach ($matches as $tag) {
                    $metaCode = pq($tag)->attr('data-code');
                    pq($tag)->replaceWith($metaCode);
                }

                $expressionContent = $htmlObj->html();
            }
        }
        
        $search  = array('=',  '&nbsp;', '\r\n', '\r', '\n', "\r\n", "\r", "\n");
        $replace = array('==', ' ',       '',     '',   '',   '',     '',   ''); 
            
        $expressionContent = html_entity_decode(trim(str_replace($search, $replace, strip_tags($expressionContent))));
        $expressionContent = str_replace(array("\xC2", "\xA0", '\u00a0', '<==', '>==', '!=='), array(' ', ' ', '', '<=', '>=', '!='), $expressionContent);
        $expressionContent = Str::remove_doublewhitespace(Str::remove_whitespace_feed($expressionContent));
        $expressionContent = trim($expressionContent, "\x20,\xC2,\xA0");
        $expressionContent = preg_replace('/\bor\b/u', '||', $expressionContent);
        $expressionContent = preg_replace('/\bOR\b/u', '||', $expressionContent);
        $expressionContent = preg_replace('/\bOr\b/u', '||', $expressionContent);
        $expressionContent = preg_replace('/\band\b/u', '&&', $expressionContent);
        $expressionContent = preg_replace('/\bAND\b/u', '&&', $expressionContent);
        $expressionContent = preg_replace('/\bAnd\b/u', '&&', $expressionContent);
        
        if (Input::post('isRun') == 'hide') {
            $response = array('status' => 'success', 'message' => 'Success', 'expression' => $expressionContent);
        } else {
            $response = $this->model->validateExpressionModel($expressionContent);
        }
        
        convJson($response);
    }
    
    public function getProcessRunList() {
      $data = $this->model->getProcessRunListModel();
      echo json_encode($data); exit;
    }    
    
    public function getProcessRun() {
      $data = $this->model->getProcessRunModel();
      echo json_encode($data); exit;
    }    
    
    public function getMetaData($metaDataId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $row = $this->model->getMetaDataModel($metaDataId);
        jsonResponse($row);
    }
    
    public function export_excel_v3() {
        $_POST['javaCacheId'] = Input::get('javaCacheId');
        $_POST['rows'] = '10000000';
        $datas = $this->model->getSalarySheetWebserviceModel();
        
        $calcTypeId = Input::get('calcTypeId');
      $getRowsBook = $this->model->getAllBookByDepCalcType(Input::get('departmentId'), Input::get('calcId'), $calcTypeId, Input::get('bookNumber'));
      
      if($datas['status'] === 'error' || !$getRowsBook)
          die('Error Export!!!');

       $datas = $datas['rows'];

      $bookArr = array();
      array_walk($getRowsBook, function($val, $key) use (&$bookArr){
          $bookArr[$key] = $val['ID'];
      });    

      $savedSalBookIds = implode(',', $bookArr);
      $metaDataNameList = ""; 
      $footers['employeecode'] = '';
      $footers['lastname'] = '';
      $footers['firstname'] = '';       
      $footers = array_merge($footers, json_decode($_GET['footers'], true));

      if (strpos(',', $savedSalBookIds) === false) {
          $empSalBookIds = " IN ($savedSalBookIds)";
          $savedSalBookIds = "WHERE PSB.ID IN ($savedSalBookIds)";
      } else {
          $empSalBookIds = " = ".$savedSalBookIds;
          $savedSalBookIds = "WHERE PSB.ID = ".$savedSalBookIds;
      }

      $selectCalcType = "SELECT PCT.CALC_TYPE_NAME, PSB.BATCH_NUMBER
                              FROM PRL_SALARY_BOOK PSB
                              LEFT JOIN PRL_CALC_TYPE PCT ON PSB.CALC_TYPE_ID = PCT.ID $savedSalBookIds";
      $selectCalcType = $this->db->GetRow($selectCalcType);
      $getCalcTypeName = $selectCalcType['CALC_TYPE_NAME'];

      $selectHeaderList = "SELECT MD.META_DATA_CODE, " . $this->db->IfNull("GD.CODE", "MD.META_DATA_NAME") . " AS META_DATA_NAME, PCTD.LABEL_NAME
          FROM PRL_CALC_TYPE PCT
          INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
          INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
          LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE = MD.META_DATA_CODE
          WHERE PCT.ID = $calcTypeId AND PCTD.IS_SHOW = 1 AND (IS_SIDEBAR IS NULL OR IS_SIDEBAR = 0) AND (PCTD.IS_HIDE = 0 OR PCTD.IS_HIDE IS NULL)
          ORDER BY PCTD.ORDER_NUM";
      $getHeaderList = $this->db->GetAll($selectHeaderList);

      $selectHiddenHeaderList = "SELECT MD.META_DATA_CODE
          FROM PRL_CALC_TYPE PCT
          INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
          INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
          WHERE PCT.ID = $calcTypeId
          AND (PCTD.IS_SIDEBAR IS NULL OR PCTD.IS_SIDEBAR = 0)
          AND (PCTD.IS_HIDE = 1)";
      $getHiddenHeaderList = $this->db->GetAll($selectHiddenHeaderList);

      $selectHiddenSidebarList = "SELECT MD.META_DATA_CODE
          FROM PRL_CALC_TYPE PCT
          INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
          INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
          WHERE PCT.ID = $calcTypeId
          AND PCTD.IS_SHOW = 1
          AND PCTD.IS_SIDEBAR = 1";
      $getHiddenSidebarList = $this->db->GetAll($selectHiddenSidebarList);

      $selectCalcTypeDtl = "SELECT
            MD.META_DATA_CODE
            FROM PRL_CALC_TYPE PCT
            INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
            INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
            WHERE 1=1 AND PCTD.IS_SHOW=1 AND (PCTD.IS_SIDEBAR IS NULL OR PCTD.IS_SIDEBAR = '0') AND PCT.ID = $calcTypeId
            ORDER BY PCTD.ORDER_NUM";
      $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);

      $date = Date::currentDate();

      $html = '<html><head><meta http-equiv="Content-type" content="text/html; charset=utf-8"></head><body>';
      $html .= '<table style="border-collapse:collapse;">';
      $html .= '<tbody>';
      $html .= '<tr>';
      $html .= '<td colspan="4">';
      $html .= 'БАТЛАВ.ЗАХИРАЛ';
      $html .= '</td>';
      $html .= '<td colspan="4">';
      $html .= Config::getFromCache('1stPersonName');
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '<tr>';
      $html .= '<td colspan="4">';
      $html .= 'Байгууллагын нэр:';
      $html .= '</td>';
      $html .= '<td colspan="4">';
      
      if (Config::getFromCache('OrganizationNameIsCriteria') == '1') {
        $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
        $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        $html .= Config::get('OrganizationName', 'departmentId='.$departmentId.';');
      } else {
        $html .= Config::get('OrganizationName');  
      }
      
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '<tr>';
      $html .= '<td colspan="4">';
      $html .= 'Байгууллагын цалин тооцсон хугацаа:';
      $html .= '</td>';      
      $html .= '<td colspan="4">';
      $html .= $getCalcTypeName . ' ' . $date;
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '<tr>';
      $html .= '<td colspan="4">';
      $html .= 'Цалингийн түр дансны дугаар:';
      $html .= '</td>';      
      $html .= '<td colspan="4" style="text-align: left">';
      $html .= Config::getFromCache('PayrollTempAccount');
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '</tbody>';
      $html .= '</table>';
      $html .= '<br>';
      $html .= '<table id="table1" style="border-collapse:collapse;">';
      $html .= '<thead>';
      $html .= '<tr>';
  //    style="border: 1px solid black;"
      $html .= '<th style="text-align: center; background: #cdc7c7; border-top:.5pt solid windowtext;
              border-right:.5pt solid windowtext;
              border-bottom:.5pt solid windowtext;
              border-left:.5pt solid windowtext;">№</th>';
                  $html .= '<th style="text-align: center; background: #cdc7c7;
              border-top:.5pt solid windowtext;
              border-right:.5pt solid windowtext;
              border-bottom:.5pt solid windowtext;
              border-left:.5pt solid windowtext;">Код</th>';
                  $html .= '<th style="text-align: center; background: #cdc7c7; border-top:.5pt solid windowtext;
              border-right:.5pt solid windowtext;
              border-bottom:.5pt solid windowtext;
              border-left:.5pt solid windowtext;">Овог</th>';
                  $html .= '<th style="text-align: center; background: #cdc7c7; border-top:.5pt solid windowtext;
              border-right:.5pt solid windowtext;
              border-bottom:.5pt solid windowtext;
              border-left:.5pt solid windowtext;">Нэр</th>';
      $count = 1;
      for ($k = 0; $k < count($getHeaderList); $k++) {
          if(!preg_match("/^f([0-9])+$/", $getHeaderList[$k]['META_DATA_CODE']))
              continue;

        if($getHeaderList[$k]['META_DATA_CODE']=="statusName"){

        } else {
          $html .= '<th style="text-align: center; border-top:.5pt solid windowtext;
          border-right:.5pt solid windowtext;
          border-bottom:.5pt solid windowtext;
          border-left:.5pt solid windowtext; background: #cdc7c7;">' . (empty($getHeaderList[$k]['LABEL_NAME']) ? Lang::line($getHeaderList[$k]['META_DATA_NAME']) : $getHeaderList[$k]['LABEL_NAME']) . '</th>';
        }
      }
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody>';
      $styleTextAlign = '';
      $resetFooterArray = array();
      $getHeaderListCount = count($getHeaderList);

      foreach ($datas as $k => $val) {
        foreach ($getHiddenHeaderList as $hiddenVal) {
          $tmpMetaDataCode = strtoupper($hiddenVal['META_DATA_CODE']);
          unset($val[$tmpMetaDataCode]);
        }
        unset($val['employeekeyid']);
        unset($val['departmentid']);

        $html .= '<tr>';
        $html .= '<td style="border-top:.5pt solid windowtext;
                  border-right:.5pt solid windowtext;
                  border-bottom:.5pt solid windowtext;
                  border-left:.5pt solid windowtext;">' . $count . '</td>';
        
          if($k === 0) {
            foreach ($val as $kkk => $vvv) {
                  $kkk = strtolower($kkk);
                  if(isset($footers[$kkk]))
                      $resetFooterArray[$kkk] = $footers[$kkk];
            }              
          }

          $styleTextAlign = 'text-align: left;';
            $html .= '<td style="border-top:.5pt solid windowtext;
            border-right:.5pt solid windowtext;
            border-bottom:.5pt solid windowtext;
            border-left:.5pt solid windowtext; ' . $styleTextAlign . '">' . $val['employeecode'] . '</td>';
            $html .= '<td style="border-top:.5pt solid windowtext;
            border-right:.5pt solid windowtext;
            border-bottom:.5pt solid windowtext;
            border-left:.5pt solid windowtext; ' . $styleTextAlign . '">' . $val['lastname'] . '</td>';
            $html .= '<td style="border-top:.5pt solid windowtext;
            border-right:.5pt solid windowtext;
            border-bottom:.5pt solid windowtext;
            border-left:.5pt solid windowtext; ' . $styleTextAlign . '">' . $val['firstname'] . '</td>';              

            for ($k = 0; $k < $getHeaderListCount; $k++) {
                if(!preg_match("/^f([0-9])+$/", $getHeaderList[$k]['META_DATA_CODE']))
                    continue;

                if (is_numeric($val[$getHeaderList[$k]['META_DATA_CODE']])) {
                    $convertedValue = number_format($val[$getHeaderList[$k]['META_DATA_CODE']], 2, '.', ',');
                    $styleTextAlign = 'text-align: right;';

                } else {

                    $convertedValue = $val[$getHeaderList[$k]['META_DATA_CODE']];
                    $styleTextAlign = 'text-align: left;';
                }

                $html .= '<td style="border-top:.5pt solid windowtext;
                        border-right:.5pt solid windowtext;
                        border-bottom:.5pt solid windowtext;
                        border-left:.5pt solid windowtext; ' . $styleTextAlign . '">' . $convertedValue . '</td>';
            }

        $count++;
        $html .= '</tr>';
      }
      $html .= '</tbody>';
      $html .= '<tfoot>';
      $html .= '<tr>';

    $html .= '<td style="border-top:.5pt solid windowtext;
          border-right:.5pt solid windowtext;
          border-bottom:.5pt solid windowtext;
          border-left:.5pt solid windowtext; font-weight: bold;"></td>';      
    $html .= '<td style="border-top:.5pt solid windowtext;
          border-right:.5pt solid windowtext;
          border-bottom:.5pt solid windowtext;
          border-left:.5pt solid windowtext; font-weight: bold;">Нийт</td>';      
    $html .= '<td style="border-top:.5pt solid windowtext;
          border-right:.5pt solid windowtext;
          border-bottom:.5pt solid windowtext;
          border-left:.5pt solid windowtext; font-weight: bold;"></td>';      
    $html .= '<td style="border-top:.5pt solid windowtext;
          border-right:.5pt solid windowtext;
          border-bottom:.5pt solid windowtext;
          border-left:.5pt solid windowtext; font-weight: bold;"></td>';      
        
    for ($k = 0; $k < $getHeaderListCount; $k++) {
        if(!preg_match("/^f([0-9])+$/", $getHeaderList[$k]['META_DATA_CODE']))
            continue;

        if (isset($resetFooterArray[$getHeaderList[$k]['META_DATA_CODE']]) && is_numeric($resetFooterArray[$getHeaderList[$k]['META_DATA_CODE']])) {
            $convertedValue = number_format($resetFooterArray[$getHeaderList[$k]['META_DATA_CODE']], 2, '.', ',');
            $styleTextAlign = 'text-align: right;';

        } else {

            $convertedValue = isset($resetFooterArray[$getHeaderList[$k]['META_DATA_CODE']]) ? $resetFooterArray[$getHeaderList[$k]['META_DATA_CODE']] : '';
            $styleTextAlign = 'text-align: left;';
        }

        $html .= '<td style="border-top:.5pt solid windowtext;
                border-right:.5pt solid windowtext;
                border-bottom:.5pt solid windowtext;
                border-left:.5pt solid windowtext; ' . $styleTextAlign . '"><strong>' . $convertedValue . '</strong></td>';
    }

    $html .= '</tr>';
    $html .= '</tfoot>';
    $html .= '</table>';
    $html .= '</body></html>';
    $date = Date::currentDate();
      
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    $reportName = $getCalcTypeName . ' ' . $date;
    $fileName = $reportName.'.xls';

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
        ob_end_clean();

        echo excelHeadTag($html, $reportName); exit();

    } catch (Exception $e) {

        header('Pragma: no-cache');
        header('Expires: 0');
        header('Set-Cookie: fileDownload=false; path=/');

        echo $e->getMessage(); exit();
    }
    }    
    
    public function export_excel_v4() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $paramsString = 'calcTypeId='.Input::get('calcTypeId').'&calcId='.Input::get('calcId').'&employeeIds='.Input::get('employeeIds').'&departmentId='.Input::get('departmentId').'&salaryBookId='.Input::get('salaryBookId');
        $_POST['params'] = $paramsString;
        
        $result = $this->model->getCalcFieldListImportExcelModel(); 
        $headerData = $result['fields'];        
        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Veritech ERP")
                ->setLastModifiedBy("")
                ->setTitle("Veritech ERP")
                ->setSubject("Veritech ERP")
                ->setDescription("Veritech ERP")
                ->setKeywords("Veritech ERP")
                ->setCategory("");
        
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Цалингийн систем - Veritech ERP');
        
        if (Config::get('OrganizationNameIsCriteria') == '1') {
          $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
          $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
          $orgName = Config::get('OrganizationName', 'departmentId='.$departmentId.';', true);
        } else {
          $orgName = Config::get('OrganizationName');  
        }        
        
        $sheet->setCellValue('A1', 'БАТЛАВ.ЗАХИРАЛ')->mergeCells('A1:C1');
        $sheet->setCellValue('D1', Config::getFromCache('1stPersonName'))->mergeCells('D1:F1');
        $sheet->setCellValue('A2', 'Байгууллагын нэр')->mergeCells('A2:C2');
        $sheet->setCellValue('D2', $orgName)->mergeCells('D2:F2');
        $sheet->setCellValue('A3', 'Байгууллагын цалин тооцсон хугацаа')->mergeCells('A3:C3');
        $sheet->setCellValue('D3', Input::get('calcTypeName') . ' ' . Date::currentDate())->mergeCells('D3:F3');
        $sheet->setCellValue('A4', 'Цалингийн түр дансны дугаар')->mergeCells('A4:C4');
        $sheet->setCellValue('D4', Config::getFromCache('PayrollTempAccount'))->mergeCells('D4:F4');

        $headerCount = 0;
        $sheet->setCellValue('A6', '№');
        $sheet->setCellValue('B6', 'Код');
        $sheet->setCellValue('C6', 'Овог');
        $sheet->setCellValue('D6', 'Нэр');
        
        foreach ($headerData as $key => $row) {
            $sheet->setCellValue(numToAlpha($key + 5) . '6', (empty($row['LABEL_NAME']) ? Lang::line($row['META_DATA_NAME']) : $row['LABEL_NAME']));
            $headerCount++;
        }
        
        $_POST['rows'] = 100000;
        $_POST['javaCacheId'] = Input::get('javaCacheId');
        $detailData = $this->model->getSalarySheetWebserviceModel();
        $i = 7;
        
        $footers['employeecode'] = '';
        $footers['lastname'] = '';
        $footers['firstname'] = '';       
        $footers = array_merge($footers, json_decode($_GET['footers'], true));          

        if($detailData['status'] === 'success' && count($detailData['rows']) > 0) {
            foreach ($detailData['rows'] as $key => $value) {
                $numToAlpha = numToAlpha(1);
                $sheet->setCellValueExplicit($numToAlpha . $i, ++$key, PHPExcel_Cell_DataType::TYPE_STRING);
                if(isset($value['employeecode'])) {
                    $cellValue = $value['employeecode'];
                    $numToAlpha = numToAlpha(2);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }
                if(isset($value['lastname'])) {
                    $cellValue = $value['lastname'];
                    $numToAlpha = numToAlpha(3);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                } 
                if(isset($value['firstname'])) {
                    $cellValue = $value['firstname'];
                    $numToAlpha = numToAlpha(4);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }          

                foreach ($headerData as $k => $item) {
                    $cellValue = isset($value[$item['META_DATA_CODE']]) ? $value[$item['META_DATA_CODE']] : '';
                    $numToAlpha = numToAlpha($k + 5);
                    if(is_numeric($cellValue))
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    else
                        $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }
                $i++;
            }    
        } 
        
        foreach ($headerData as $k => $item) {
            $cellValue = isset($footers[$item['META_DATA_CODE']]) ? $footers[$item['META_DATA_CODE']] : '';
            $numToAlpha = numToAlpha($k + 5);
            
            if(is_numeric($cellValue))
                $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            else
                $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
        }    

        foreach (range(numToAlpha(1), numToAlpha($headerCount + 4)) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        $sheet->getStyle('A6:' . numToAlpha($headerCount + 4) . '6')->applyFromArray(
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
                    'color' => array('rgb' => '89c059')
                )
            )
        );
        
        $sheet->getStyle('A'.$i.':' . numToAlpha($headerCount + 4) . $i)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="Цалин - ' . Date::currentDate('YmdHi') . '.xlsx"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            ob_end_clean();
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
    
    public function updateKeyEmployeeWebservice() {
        $data = $this->model->updateKeyEmployeeWebserviceModel();
        $data = is_null($data) ? array('status' => '*** Veritech ERP - SALARY ***') : $data;
        echo json_encode($data); exit;
    }    
    
    public function loadLog() {
        $this->view->uniqId = getUID();
        $jsonString = $this->model->getActionLogModel(Input::numeric('id'));
        
        if (empty($jsonString['JSON_STRING'])) {
            jsonResponse(array(
                'status' => 'error',
                'message' => 'LOG харуулах мэдээлэл хоосон байна.<br> Хадгалах үйлдэл хийсний дараа LOG харах боломжтой'
            )); 
        }
        
        $responseHtml = array(
            'Title' => 'Log information',
            'close_btn' => Lang::line('close_btn'),
            'status' => 'success',
            'uniqId' => $this->view->uniqId
        );        
        
        $jsonString['JSON_STRING'] = Arr::decode($jsonString['JSON_STRING']);
        $this->view->logdata = json_decode($jsonString['JSON_STRING'], true);
        
        if ($jsonString['PROCESS_META_DATA_CODE'] === 'addemployeefromexceltemplate') {
            $responseHtml['Html'] = "<pre>" . json_encode($this->view->logdata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
            $responseHtml['status'] = 'raw';
            jsonResponse($responseHtml);
        }
            
        $this->view->logdata['dataset'] = array(
            'rows' => json_decode($this->view->logdata['rows']),
            'footer' => json_decode($this->view->logdata['footer']),
            'total' => count(json_decode($this->view->logdata['rows']))
        );
        $this->view->logdata['column'] = json_decode($this->view->logdata['column']);
        $this->view->logdata['footer'] = json_decode($this->view->logdata['footer']);
        $this->view->logdata['frozenColumn'] = json_decode($this->view->logdata['frozenColumn']);        
        
        if(!isset($this->view->logdata['rows'])) {
            
            if(is_null($this->view->logdata)) {
                $responseHtml['Html'] = '<p class="mt5"><i class="fa fa-warning" style="color:#ff9e00"></i> <strong>2018-11-16</strong> -аас өмнө үүссэн болон энэ хугацаанаас хойш шинэчлэлт хийгээгүй бол ЛОГ мэдээлэл дараах байдалтай харагдна.</p><pre>'.$jsonString['JSON_STRING'].'</pre>';  
            } else {
                $responseHtml['Html'] = '<p class="mt5"><i class="fa fa-warning" style="color:#ff9e00"></i> <strong>2018-11-16</strong> -аас өмнө үүссэн болон энэ хугацаанаас хойш шинэчлэлт хийгээгүй бол ЛОГ мэдээлэл дараах байдалтай харагдна.</p><pre>'.var_export($this->view->logdata, true).'</pre>';  
            }
            
        } else {
            $responseHtml['Html'] = $this->view->renderPrint('dialog/calculateLogInformation', self::$viewPath);
        }
        
        unset($this->view->logdata['rows']);
        unset($this->view->logdata['footer']);
        $responseHtml['response'] = $this->view->logdata;
        
        jsonResponse($responseHtml);
    }

    public function getCalcTypeDtlCard($id) {
        $data = $this->model->getCalcTypeDtlCardModel($id);
        echo json_encode($data); exit;
    }   

    public function everyRequestSalary() {
        $data = $this->model->everyRequestSalaryModel();

        $response = array(
            'status' => ''
        );        

        if ($data) {
            $response = array(
                'status' => 'success',
                'message' => $data
            );
        }

        jsonResponse($response);
    }

    public function deleteEveryRequestSalary() {
        $this->model->deleteEveryRequestSalaryModel();
    }        
    
    public function getDownloadSheetExcelCtrl() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';        
        
        $empdata = array('employeecode', 'employeecode_globetext', 'lastname', 'lastname_globetext', 'firstname', 'firstname_globetext');
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Veritech ERP")
                ->setLastModifiedBy("")
                ->setTitle("Veritech ERP")
                ->setSubject("Veritech ERP")
                ->setDescription("Veritech ERP")
                ->setKeywords("Veritech ERP")
                ->setCategory("");        
        
        $duplicateEmployee = Input::get('exceldatas_duplicatedemployees');
        if ($duplicateEmployee) {
            $duplicateEmployee = Arr::decode($duplicateEmployee);
            
            $invalidEmployeeTemp = array();
            $invIndex = 0;
            foreach ($duplicateEmployee as $invRow) {
                $invalidEmployeeTemp[$invIndex] = $invRow;
                $invIndex++;
            }
            $duplicateEmployee = $invalidEmployeeTemp;                 

            $sheet = $objPHPExcel->createSheet(0);

            $sheet->setTitle('Давхардсан');

            $headerCount = 0;
            $sheet->setCellValue('A1', 'employeecode');
            $sheet->setCellValue('A2', 'Код');
            $sheet->setCellValue('B1', 'lastname');
            $sheet->setCellValue('B2', 'Овог');
            $sheet->setCellValue('C1', 'firstname');
            $sheet->setCellValue('C2', 'Нэр');

            $indexKey = 0;
            foreach ($duplicateEmployee[0] as $key => $row) {
                if (strpos($key, '_globetext') === false && !in_array($key, $empdata)) {
                    $sheet->setCellValue(numToAlpha($indexKey + 4) . '1', $key);
                    $sheet->setCellValue(numToAlpha($indexKey + 4) . '2', $duplicateEmployee[0][$key.'_globetext']);
                    $headerCount++;
                    $indexKey++;
                }
            }

            $i = 3;
            foreach ($duplicateEmployee as $key => $value) {

                if(isset($value['employeecode'])) {
                    $cellValue = $value['employeecode'];
                    $numToAlpha = numToAlpha(1);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }
                if(isset($value['lastname'])) {
                    $cellValue = $value['lastname'];
                    $numToAlpha = numToAlpha(2);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                } 
                if(isset($value['firstname'])) {
                    $cellValue = $value['firstname'];
                    $numToAlpha = numToAlpha(3);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }

                $indexKey = 0;
                foreach ($duplicateEmployee[0] as $k => $item) {

                    if (strpos($k, '_globetext') === false && !in_array($k, $empdata)) {
                        $cellValue = isset($value[$k]) ? $value[$k] : '';
                        $numToAlpha = numToAlpha($indexKey + 4);

                        if(is_numeric($cellValue))
                            $sheet->setCellValueExplicit($numToAlpha . $i, Number::numberFormat($cellValue, 3), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        else
                            $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                        $indexKey++;
                    }
                }
                $i++;
            }        

            foreach (range(numToAlpha(1), numToAlpha($headerCount + 3)) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }        

            $sheet->getStyle('A1:' . numToAlpha($headerCount + 3) . '1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => false
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_NONE
                        )
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '89c059')
                    )
                )
            );
            $sheet->getStyle('A2:' . numToAlpha($headerCount + 3) . '2')->applyFromArray(
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
                        'color' => array('rgb' => '89c059')
                    )
                )
            );
        }
        
        $notFoundEmployee = Input::get('exceldatas_notfoundemployees');
        if ($notFoundEmployee) {
            $notFoundEmployee = Arr::decode($notFoundEmployee);
            
            $invalidEmployeeTemp = array();
            $invIndex = 0;
            foreach ($notFoundEmployee as $invRow) {
                $invalidEmployeeTemp[$invIndex] = $invRow;
                $invIndex++;
            }
            $notFoundEmployee = $invalidEmployeeTemp;            
            
            if ($duplicateEmployee) {
                $sheet = $objPHPExcel->createSheet(1);
            } else {
                $sheet = $objPHPExcel->createSheet(0);
            }

            $sheet->setTitle('Олдоогүй');

            $headerCount = 0;
            $sheet->setCellValue('A1', 'employeecode');
            $sheet->setCellValue('A2', 'Код');
            $sheet->setCellValue('B1', 'lastname');
            $sheet->setCellValue('B2', 'Овог');
            $sheet->setCellValue('C1', 'firstname');
            $sheet->setCellValue('C2', 'Нэр');

            $indexKey = 0;
            foreach ($notFoundEmployee[0] as $key => $row) {
                if (strpos($key, '_globetext') === false && !in_array($key, $empdata)) {
                    $sheet->setCellValue(numToAlpha($indexKey + 4) . '1', $key);
                    $sheet->setCellValue(numToAlpha($indexKey + 4) . '2', $notFoundEmployee[0][$key.'_globetext']);
                    $headerCount++;
                    $indexKey++;
                }
            }

            $i = 3;
            foreach ($notFoundEmployee as $key => $value) {

                if(isset($value['employeecode'])) {
                    $cellValue = $value['employeecode'];
                    $numToAlpha = numToAlpha(1);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }
                if(isset($value['lastname'])) {
                    $cellValue = $value['lastname'];
                    $numToAlpha = numToAlpha(2);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                } 
                if(isset($value['firstname'])) {
                    $cellValue = $value['firstname'];
                    $numToAlpha = numToAlpha(3);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }

                $indexKey = 0;
                foreach ($notFoundEmployee[0] as $k => $item) {

                    if (strpos($k, '_globetext') === false && !in_array($k, $empdata)) {
                        $cellValue = isset($value[$k]) ? $value[$k] : '';
                        $numToAlpha = numToAlpha($indexKey + 4);

                        if(is_numeric($cellValue))
                            $sheet->setCellValueExplicit($numToAlpha . $i, Number::numberFormat($cellValue, 3), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        else
                            $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                        $indexKey++;
                    }
                }
                $i++;
            }        

            foreach (range(numToAlpha(1), numToAlpha($headerCount + 3)) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }        

            $sheet->getStyle('A1:' . numToAlpha($headerCount + 3) . '1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => false
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_NONE
                        )
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '89c059')
                    )
                )
            );
            $sheet->getStyle('A2:' . numToAlpha($headerCount + 3) . '2')->applyFromArray(
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
                        'color' => array('rgb' => '89c059')
                    )
                )
            );
        }
        
        $invalidEmployee = Input::get('exceldatas_invaliddataemployees');
        if ($invalidEmployee) {
            $invalidEmployee = Arr::decode($invalidEmployee);
            
            $invalidEmployeeTemp = array();
            $invIndex = 0;
            foreach ($invalidEmployee as $invRow) {
                $invalidEmployeeTemp[$invIndex] = $invRow;
                $invIndex++;
            }
            $invalidEmployee = $invalidEmployeeTemp;
            
            if ($duplicateEmployee && $notFoundEmployee) {
                $sheet = $objPHPExcel->createSheet(2);
            } elseif ($duplicateEmployee || $notFoundEmployee) {
                $sheet = $objPHPExcel->createSheet(1);
            } else {
                $sheet = $objPHPExcel->createSheet(0);
            }

            $sheet->setTitle('Алдаатай');

            $headerCount = 0;
            $sheet->setCellValue('A1', 'employeecode');
            $sheet->setCellValue('A2', 'Код');
            $sheet->setCellValue('B1', 'lastname');
            $sheet->setCellValue('B2', 'Овог');
            $sheet->setCellValue('C1', 'firstname');
            $sheet->setCellValue('C2', 'Нэр');

            $indexKey = 0;
            foreach ($invalidEmployee[0] as $key => $row) {
                if (strpos($key, '_globetext') === false && !in_array($key, $empdata)) {
                    $sheet->setCellValue(numToAlpha($indexKey + 4) . '1', $key);
                    $sheet->setCellValue(numToAlpha($indexKey + 4) . '2', $invalidEmployee[0][$key.'_globetext']);
                    $headerCount++;
                    $indexKey++;
                }
            }

            $i = 3;
            foreach ($invalidEmployee as $key => $value) {

                if(isset($value['employeecode'])) {
                    $cellValue = $value['employeecode'];
                    $numToAlpha = numToAlpha(1);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }
                if(isset($value['lastname'])) {
                    $cellValue = $value['lastname'];
                    $numToAlpha = numToAlpha(2);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                } 
                if(isset($value['firstname'])) {
                    $cellValue = $value['firstname'];
                    $numToAlpha = numToAlpha(3);
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }

                $indexKey = 0;
                foreach ($invalidEmployee[0] as $k => $item) {

                    if (strpos($k, '_globetext') === false && !in_array($k, $empdata)) {
                        $cellValue = isset($value[$k]) ? $value[$k] : '';
                        $numToAlpha = numToAlpha($indexKey + 4);

                        if(is_numeric($cellValue))
                            $sheet->setCellValueExplicit($numToAlpha . $i, Number::numberFormat($cellValue, 3), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        else
                            $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                        $indexKey++;
                    }
                }
                $i++;
            }        

            foreach (range(numToAlpha(1), numToAlpha($headerCount + 3)) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }        

            $sheet->getStyle('A1:' . numToAlpha($headerCount + 3) . '1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => false
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_NONE
                        )
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '89c059')
                    )
                )
            );
            $sheet->getStyle('A2:' . numToAlpha($headerCount + 3) . '2')->applyFromArray(
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
                        'color' => array('rgb' => '89c059')
                    )
                )
            );
        }
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="Цалин Загвар - ' . Date::currentDate('YmdHi') . '.xlsx"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            ob_end_clean();
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

    public function getDataviewTemplateData() {
        jsonResponse($this->model->dataviewSavedCriteriaModel());
    }    

    public function getSuggestionCalcRow() {
        jsonResponse($this->model->getSuggestionCalcRowModel());
    }    
    
}
