<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdtimestable Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Time version 3
 * @author	Ts.Ulaankhuu <ulaankhuu@veritech.mn>
 * @link	http://www.interactive.mn/mdtimestable
 */

class Mdtimestable extends Controller {

    const viewPath = 'middleware/views/time/timeV3/';

    public function __construct() {
        parent::__construct();
        
        $methodCheck = explode('/', strtolower(Input::get('url')));
        if(issetVar($methodCheck[1]) != 'downloadall' && issetVar($methodCheck[1]) != 'downloadallprocedure') {
            Auth::handleLogin();
        }
    }
    
    public function downloadProcedureIO() {
        $this->load->model('mdtimestable', 'middleware/models/');
        
        parse_str($_POST['balanceParam'], $params);
        $startDate = $params['startDate'];
        $endDate = $params['endDate'];
        (String) $join = '';
        (String) $isDep = 'department';
        
        /*$notPlanDepartment = Config::getFromCache('tmsPlanTimeDefaultDepartment');
        $notPlanDepartment = $notPlanDepartmentArr = $notPlanDepartment ? explode(',', $notPlanDepartment) : array();
        $notPlanArr = array();

        if(isset($params['isChild']))
            $params['departmentId'] = $this->model->getAllChildDepartmentModel($params['departmentId']);        

        $params['departmentId'] = explode(',', $params['departmentId']);
        foreach($params['departmentId'] as $kd => $rowDep) {
            if(in_array($rowDep, $notPlanDepartmentArr)) {
                array_push($notPlanArr, $rowDep);
                unset($params['departmentId'][$kd]);
            }
        } */       
        
        if(empty($params['employeesString']) && !isset($params['groupId'])) {
            
            $isChild = issetVar($params['isChild']);
            $departmentIds = $params['newDepartmentId'];
            
            if (is_array($params['newDepartmentId'])) {
                $departmentIds = implode(',' , $params['newDepartmentId']);    
            }
            
            if (empty($departmentIds)) {
                echo json_encode(array(
                    'title' => 'Warning',
                    'status' => 'warning',
                    'message' => 'Салбар нэгж хоосон байна!'
                )); exit;
            }
            
            $join = $this->model->getAllChildDepartmentModel($departmentIds, $isChild);


        } elseif (isset($params['groupId']) && is_array($params['groupId']) && empty($params['employeesString'])) {
            $join = implode(',', $params['groupId']);
            $join = $this->model->concatGroupEmployee($join);
            $isDep = 'employee';
            
        } else {
            $join = $params['employeesString'];
            $isDep = 'employee';
        }
        
        $join = rtrim($join, ',');
        
        $res = $this->model->downloadDataIOServiceModel($startDate, $endDate, $join, $isDep, $params['tmsTemplate']);
        
        echo json_encode($res); exit;
    }      

    public function timebalanceV5($mergeView = false) {

        $this->view->title = 'Цагийн мэдээллийн жагсаалт V5';

        $this->view->isAjax = true;
        $this->view->golomtView = false;
        $this->view->mergeView = $mergeView;

        $this->view->uniqId = getUID();
        $this->view->timeBalanceViewType = '';

        $this->view->sessionDepartmentId = $this->model->getUserDepartmentIdModel();
        $this->view->searchTnaCauseTypeList = $this->model->searchTnaCauseTypeListModel();
        $this->view->tmsTemplateList = $this->model->tmsTemplateListModel();
        $this->load->model('mdtimestable', 'middleware/models/');
        $this->view->searchTnaGroupList = $this->model->searchTnaGroupListDVModel();
        $this->view->isParentDep = Config::getFromCache('tmsParentFilter');

        $this->view->positionList = $this->model->getPositionListModel();
        $this->view->departmentList = $this->model->getDepartmentListModel();
        $this->view->balanceBtn = $this->model->getWfmStatusByBalanceBtnModel($this->view->sessionDepartmentId, $this->view->uniqId);
        $this->view->searchTnaEmployeeStatusList = $this->model->searchTnaEmployeeStatusListModel($this->view->golomtView);
        $this->view->calcList = $this->model->getCalcListModel();

        $this->view->isAdmin = $this->model->getTnaIsApprovedDeparmentModel($this->view->sessionDepartmentId, $this->view->uniqId);
        
        $this->timeBalanceAssets();
        
        $ins = &getInstance();
        $ins->load->model('mdmetadata', 'middleware/models/');        
        $this->view->getMetaDataIdWorkflow = $ins->model->getMetaDataByCodeModel('tmsBalanceWorkflow');         

        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        }
        $this->view->timebalanceMain = $this->view->renderPrint('main/balanceMain5', self::viewPath);
        $this->view->render('timebalanceV3', self::viewPath);
        $this->view->render('main/balanceScript5', self::viewPath);

        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }    
    
    public function timeEmployeePlanV2($isGolomt = '0') {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = "Ажилтны ажиллах цагийн төлөвлөгөө";
        $this->view->isAjax = true;
        $this->view->golomtView = false;

        $this->view->uniqId = getUID();

        self::timeBalanceAssets();

        $resultVerif = $this->model->getVerifEmployeeModel();
        $this->view->departmentList = $this->model->getDepartmentListModel();
        $this->view->calcList = $this->model->getCalcListModel();
        $this->view->searchTnaEmployeeStatusList = $this->model->searchTnaEmployeeStatusListModel($this->view->golomtView);
        $this->view->searchTnaGroupList = $this->model->searchTnaGroupListDVModel();
        $this->view->sessionVerifEmployee = $resultVerif['CHECK'];
        $this->view->sessionDepartmentId = $this->model->getUserDepartmentIdModel();
        $this->view->isParentDep = Config::getFromCache('tmsParentFilter');

        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        }

        //if ($this->view->sessionDepartmentId) {
        $this->view->departmentList = $this->model->getDepartmentListModel();
        $this->view->positionList = $this->model->getPositionListModel();
        //$this->view->isAdmin = $this->model->getTnaIsApprovedDeparmentModel($this->view->sessionDepartmentId, $this->view->uniqId);
        
        
        $this->view->planMain = $this->view->renderPrint('main/planMain', "middleware/views/time/");
        $this->view->render('timePlanV2', "middleware/views/time/");
        $this->view->render('main/planScript', "middleware/views/time/");
        /*} else {
            echo '<h4>Салбар нэгж сонгоогүй байна! :(</h4>';
        }*/        

        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }    
    
    public function getDepartmentGroupList() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->getDepartmentGroupListModel(Input::post('departmentId'))); exit;
    }    
    
    public function balanceListMainDataGridV3() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->balanceListMainDataGridV3Model();
        jsonResponse($data);
    }    
    
    public function balanceListMainDataGridV6() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->balanceListMainDataGridV6Model();
        jsonResponse($data);
    }    
    
    public function balanceListMainDataGridV5() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->balanceListMainDataGridV5Model();
        jsonResponse($data);
    }    
    
    public function existMetaIdBalance() {
        $balanceDVid = Config::getFromCache('tnaTimeBalanceHdrDV');
        $existMetaId = false;
        
        if ($balanceDVid) {
            $existMetaId = (new Mdmetadata())->getMetaData($balanceDVid);

            $mdf = &getInstance();
            $mdf->load->model('mdobject', 'middleware/models/');
            $this->view->dataGridColumnData = $mdf->model->getDataViewGridHeaderModel($existMetaId['META_DATA_ID'], '1 = 1', 1, false, false);

            $columnProp = array();
            $columnFreezeProp = array();
            foreach ($this->view->dataGridColumnData as $key => $row) {

                if ($row['IS_SHOW'] == '1') {
                    $attrs = array(
                        'field' => $row['FIELD_PATH'],
                        'title' => $mdf->model->dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])),
                        'align' => $row['BODY_ALIGN'],
                        'width' => empty($row['COLUMN_WIDTH']) ? '150' : $row['COLUMN_WIDTH'],
                        'halign' => $row['HEADER_ALIGN'],
                        'sortable' => true,
                    );

                    if ($row['META_TYPE_CODE'] === 'datetime') {
                        $attrs['formatter'] = 'formatterMinutToTime';
                    }
                    
                    if ($row['DEFAULT_VALUE'] && $row['DEFAULT_VALUE'] === 'freeze') {
                        array_push($columnFreezeProp, $attrs);
                    } else {
                        array_push($columnProp, $attrs);
                    }
                    
                }
            }
            
            if ($columnFreezeProp) {
                $attrs = array(
                    'field' => '',
                    'title' => '',
                    'align' => 'center',
                    'width' => '25',
                    'halign' => 'center',
                    'checkbox' => true
                );

                array_unshift($columnFreezeProp, $attrs);                    
            } else {
                array_push($columnFreezeProp, array(
                    'field' => '',
                    'title' => '',
                    'align' => 'center',
                    'width' => '25',
                    'halign' => 'center',
                    'checkbox' => true
                ));
                array_unshift($columnProp, $attrs);                    
            }

            $this->view->dataGridColumnData = array();
            $this->view->dataGridColumnData['header'] = $columnProp;
            $this->view->dataGridColumnData['headerFreeze'] = $columnFreezeProp;
        }
        
        if ($existMetaId) {
            jsonResponse($this->view->dataGridColumnData);
        } else {        
            jsonResponse(array());
        }
    }    

    public function existMetaIdPlan() {
        $balanceDVid = Config::getFromCache('tnaTimePlanHdrDV');
        $existMetaId = false;
        
        if ($balanceDVid) {
            $existMetaId = (new Mdmetadata())->getMetaData($balanceDVid);
        }
        
        if ($existMetaId) {
            jsonResponse(array('ok'));
        } else {        
            jsonResponse(array());
        }
    }     
    
    public function downloadData() {
        $result = $this->model->downloadDataModel();
        echo json_encode($result); exit;
    }  

    public function subBalanceListMainDataGridV3() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->subBalanceListMainDataGridV3Model();
        jsonResponse($data);
    }    

    public function subBalanceListMainDataGridV5() { 
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->subBalanceListMainDataGridV5Model();
        jsonResponse($data);
    }    
    
    public function getBalanceDetailListV3() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $this->view->timeBalanceId = Input::post('timeBalanceId');
        $this->view->balanceDate = Input::post('balanceDate');
        $this->view->employeeKeyId = Input::post('employeeKeyId');
        
        $checkEdit = $this->model->getTnaplanIslockModel(Input::post('depId'));
        $this->view->isStartTimeEdit = ((isset($checkEdit['IS_STARTTIME']) && $checkEdit['IS_STARTTIME'] === '1') || !isset($checkEdit['IS_STARTTIME'])) ? 1 : 0;
        $this->view->isEndTimeEdit = ((isset($checkEdit['IS_ENDTIME']) && $checkEdit['IS_ENDTIME'] === '1') || !isset($checkEdit['IS_ENDTIME'])) ? 1 : 0;        
        
        $response = $this->model->getBalanceDetailListV3Model($this->view->timeBalanceId, $this->view->balanceDate, $this->view->employeeKeyId);
        $response['isStartTimeEdit'] = $this->view->isStartTimeEdit;
        $response['isEndTimeEdit'] = $this->view->isEndTimeEdit;

        if (!empty($response['lock']) && Date::formatter($response['lock'], 'Y-m-d') >= Date::currentDate('Y-m-d'))
            echo jsonResponse(array('status' => 'locked'));
        else
            echo jsonResponse($response); 
        
        exit;
    }    
    
    public function planTimeMore() {
        echo json_encode($this->model->planTimeMoreModel(Input::post('employeeId'), Input::post('blanceDate'))); exit;
    }

    public function userSessionIsFull() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->userSessionIsFullModel()); exit;
    }

    public function getEmployeeConfirmDataV3() {
        $result = $this->model->getEmployeeConfirmDataV3Model();
        jsonResponse($result);
    }
    
    public function getEmployeeConfirmDataV5() {
        $result = $this->model->getEmployeeConfirmDataV5Model();
        jsonResponse($result);
    }

    public function getEmployeeCancelStatus() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->getEmployeeCancelStatusModel()); exit;
    }    
    
    public function timeBalanceAssets() {
        $this->view->css = array_merge(array(
            'custom/addon/plugins/tablesorter/css/theme.bootstrap.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css"'
                ), AssetNew::metaCss());

        $this->view->fullUrlCss = array('middleware/assets/css/time/time.css');

        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-mn.js',
            'custom/addon/plugins/phpjs/phpjs.min.js',
            'custom/addon/plugins/tablesorter/js/jquery.tablesorter.min.js',
            'custom/addon/plugins/tablesorter/js/jquery.tablesorter.widgets.min.js',
            'custom/addon/plugins/tablesorter/js/widgets/widget-sortTbodies.js'
        );
    }    
    
    public function multiChangeBalanceV3() {
        $this->view->params = $_POST['balanceHdr'];

        $departmentId = isset($this->view->params['0']['DEPARTMENT_ID']) ? $this->view->params['0']['DEPARTMENT_ID'] : '';
        $checkEdit = $this->model->getTnaplanIslockModel($departmentId);
        
        $this->view->isStartTimeEdit = ((isset($checkEdit['IS_STARTTIME']) && $checkEdit['IS_STARTTIME'] === '1') || !isset($checkEdit['IS_STARTTIME'])) ? 1 : 0;
        $this->view->isEndTimeEdit = ((isset($checkEdit['IS_ENDTIME']) && $checkEdit['IS_ENDTIME'] === '1') || !isset($checkEdit['IS_ENDTIME'])) ? 1 : 0;

        $this->view->isLock = $this->model->getIslockModel($this->view->params);

        $this->view->isHishigArvin = Config::getFromCache('CONFIG_TNA_HISHIGARVIN');
        $this->view->golomtView = false;

        $this->view->tnaCauseType = $this->model->tnaCauseTypeModel();
        $response = array(
            'Title' => 'Цагийн мэдээллийг олноор өөрчлөх',
            'Html' => $this->view->renderPrint('main/sub/employeeSidebarMultiV3', self::viewPath),
            'close_btn' => Lang::line('close_btn'),
            'save_btn' => Lang::line('save_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function multiChangeBalanceQueryV3() {
        echo json_encode($this->model->multiChangeBalanceQueryV3Model()); exit;
    }    
    
    public function saveBalanceDescription() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->saveBalanceDescriptionModel()); exit;
    }    
    
    public function downloadAll($startDate = false, $endDate = false) {
        $this->load->model('mdtimestable', 'middleware/models/');
        $this->model->downloadDataModel(true, $startDate, $endDate);
        echo '<h2 style="color: green;">АМЖИЛТТАЙ</h2>';
    }  
    
    public function balanceGoogleMapView() {
        $this->view->getRow = $this->model->balanceGoogleMapViewModel();
        
        $response = array(
            'Title' => 'Газрын зураг',
            'Html' => is_array($this->view->getRow) ? $this->view->renderPrint('sub/googleMapView', self::viewPath) : '<h3>Координатын мэдээлэл олдсонгүй</h3>',
            'close_btn' => Lang::line('close_btn'),
        );
        echo json_encode($response); exit;
    }    

    public function getDeparmentListJtreeData() {
        $response = $this->model->getDeparmentListJtreeDataModel(null, Input::get('parentNode'));

        if (count($response) === 1) {
            $response[0]['state']['selected'] = true;
        }

        echo json_encode($response); exit;
    }    

    public function isLockPlan() {
        $response = array(
            'Title' => 'Цагийн тооцоолол түгжих',
            'Html' => $this->view->renderPrint('sub/isLockPlan', self::viewPath),
            'close_btn' => Lang::line('close_btn'),
            'save_btn' => Lang::line('save_btn')
        );
        echo json_encode($response); exit;
    }    

    public function isLockBalanceQuery() {
        echo json_encode($this->model->isLockBalanceQueryModel()); exit;
    }    
    
    public function deleteEmployeePlanBalance() {
        $data = $this->model->deleteEmployeeBalanceModel();
        echo jsonResponse($data);
    }    
    
    public function getCauseType() {
        jsonResponse($this->model->tnaCauseTypeGridModel());
    }
    
    public function getCauseTypeHdr() {
        jsonResponse($this->model->tnaCauseTypeHdrGridModel());
    }
    
    public function saveBalanceByProcess() {
        $response = $this->model->saveBalanceByProcessModel();
        echo json_encode($response); exit;
    }
    
    public function employeePlanListMainDataGridNewV2() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->employeePlanListMainDataGridNewV2Model());
    }    
    
    public function employeePlanListMainDataGridNewV3() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->employeePlanListMainDataGridNewV3Model());
    }    
    
    public function employeePlanListMainDataGridNewV4() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->employeePlanListMainDataGridNewV4Model());
    }    
    
    public function getEmptySideBarV2() {
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        $this->load->model('mdmeta', 'middleware/models/');
        
        $metaDataId = $this->model->getMetaDataIdByCodeModel('TmsTimePlanList');
        $processMetaDataId = $this->model->getMetaDataIdByCodeModel('tmsTimePlanDv_001');
        $this->view->proc1 = $this->model->getMetaDataIdByCodeModel('TmsEmployeeAllTimePlan_001');
        $this->view->proc2 = $this->model->getMetaDataIdByCodeModel('TmsShiftEmployeeTimePlan_001');
        
        $this->load->model('mdobject', 'middleware/models/');
        $this->view->isAdd = 'false';
        $this->view->isAdmin = false;
        $this->view->uniqId = Input::post('uniqId');

        $getAccessProcess = $this->model->getAccessProcess($metaDataId, false);
        if ($getAccessProcess) {
            (Array) $dataViewProcess = $this->model->getDataViewProcess($metaDataId, $getAccessProcess, 'AND PRO.PROCESS_META_DATA_ID = ' . $processMetaDataId);
            if ($dataViewProcess) {
                $this->view->isAdd = 'true';
            }
        }
        
        $this->load->model('mdtimestable', 'middleware/models/');

        $this->view->tmsMetaDataId = $processMetaDataId;
        $this->view->sessionDepartmentId = $this->model->getUserDepartmentIdModel();
        $this->view->planBtn = $this->model->getWfmStatusByPlanBtnModel($this->view->sessionDepartmentId);
        $this->view->getArchivList = $this->model->getArchivListV2Model(Input::post('year'), Input::post('month'));
        //$this->view->dataPlan = $this->model->employeePlanListV2Model();        
        
        echo json_encode($this->view->renderPrint('main/sub/getEmptySideBarV2', "middleware/views/time/"));
    }
    
    public function getChildDepartmenIds() {
        $this->view->departmentIds = $params = '';
        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            
            if ($params['newDepartmentId']) {
                $isChild = issetVar($params['isChild']);

                $departmentIds = $this->model->getAllChildDepartmentModel($params['newDepartmentId'], $isChild);

                $this->view->departmentIds = $departmentIds;
            }        
        }       
        $params['departmendId'] = $this->view->departmentIds;
        
        echo json_encode($params);
    }

    public function employeePlanListV2() {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->proc1 = '1496396057895';
        $this->view->proc2 = '1504784738919';
        
        $this->load->model('mdobject', 'middleware/models/');
        $this->view->isAdd = 'false';
        $this->view->isAdmin = false;
        $this->view->uniqId = Input::post('uniqId');

        $this->load->model('mdtimestable', 'middleware/models/');
        $requestType = Input::post('requestType');
        $this->view->tmsMetaDataId = '1494244302484';

        $sidebarInfo = "";

        if (!empty($requestType)) {
            $this->view->getArchivList = $this->model->getArchivListV2Model(Input::post('year'), Input::post('month'));
            $sidebarInfo = $this->view->renderPrint('main/sub/getEmptySideBarV2', "middleware/views/time/");
        }

        $data = $this->model->employeePlanListV2Model();

        echo json_encode(array(
            'employeePlan' => $data,
            'employeeSidebar' => $sidebarInfo,
            'uniqId' => $this->view->uniqId
        ));
    }    
    
    public function saveEmployeePlanV2() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->saveEmployeePlanV2Model();
        echo json_encode($data);
    }    
    
    public function deleteEmployeePlanV2Month() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->deleteEmployeePlanV2MonthModel());
    }    
    
    public function sendArchivForm() {
        $response = array(
            'Title' => Input::post('year') . ' оны ' . Input::post('month') . ' сарын архив үүсгэх',
            'Html' => $this->view->renderPrint('sub/sendArchiv', "middleware/views/time/"),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }    
    
    public function sendArchivV2() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo json_encode($this->model->sendArchivV2Model());
    }    
    
    public function empPlanListMainDataGridV2() {
        $balanceDVid = Config::getFromCache('tnaTimePlanHdrDV');
        $existMetaId = false;
        
        if ($balanceDVid) {
            $existMetaId = (new Mdmetadata())->getMetaData($balanceDVid);
        }
        
        if ($existMetaId) {
            $this->load->model('mdtimestable', 'middleware/models/');
            echo json_encode($this->model->employeePlanListMainDataGridNewV4Model());
        } else {        
            $this->load->model('mdtimestable', 'middleware/models/');
            echo json_encode($this->model->empPlanListMainDataGridV2Model());
        }
    }    
    
    public function getWfmStatusData() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo $this->model->getWfmStatusDataModel();
    }      
    
    public function saveWfmStatusData() {
        $this->load->model('mdtimestable', 'middleware/models/');
        echo $this->model->saveWfmStatusDataModel();
    }        
    
    public function timePlanExport($exportType) {
        $this->load->model('mdtimestable', 'middleware/models/');
        (Array) $cellWords = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
            'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX');
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator(Session::get('1'))
                ->setLastModifiedBy(Session::get('1'))
                ->setTitle('')
                ->setSubject('')
                ->setDescription('')
                ->setKeywords('');
        
        $planYear = Input::post('planYear');
        $planMonth = Input::post('planMonth');
        $params = Input::postData();
        
        $exportType = 2;
        $caclStartDate = '';
        $caclEndDate = '';
        $caclYear = '';
        if (Config::getFromCache('tmsCalcIdCode') == '1') {
            if (empty($params['calcId'])) {
                $response = array('status' => 'error', 'message' => 'Бодолтын дугаараа сонгоно уу!');
                return $response ;
            }        
            $getCalcInfo = $this->model->getCalcListModel($params['calcId']);
            $caclStartDate = Input::post('startDate');
            $caclEndDate = Input::post('endDate');
            $caclYear = Date::formatter($caclStartDate, 'Y');
            // $caclStartDate = Date::formatter($getCalcInfo[0]['startdate']);
            // $caclEndDate = Date::formatter($getCalcInfo[0]['enddate']);
            // $caclYear = $getCalcInfo[0]['year'];
        }

        $selectHoliday = "
            SELECT 
            START_DATE, 
            END_DATE, 
            HOLIDAY_NAME
        FROM LM_HOLIDAY 
        WHERE END_DATE IS NOT NULL";        
        $holidays = $this->db->GetAll($selectHoliday);
        $monStart = (int) Date::formatter($caclStartDate, 'm');
        $monEnd = (int) Date::formatter($caclEndDate, 'm');            

        if ($caclStartDate && $caclEndDate) {
            $days = $this->model->getWorkingTwoDateV3Model(array('planYear'=>$caclYear, 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays, $caclStartDate, $caclEndDate);
        } else {
            $days = $this->model->getWorkingDateV3Model(array('planMonth'=>$params['planMonth'], 'planYear'=>$params['planYear'], 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);
        }        
        
        $result = $this->model->exportTimeEmployeeListV2Model();
        
        (Array) $headerStyles = array(
            'font' => array(
                'bold' => true,
                'size' => '9'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cccccc')
            )
        );
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue($cellWords[0] . '5', "№ ")->getColumnDimension($cellWords[0])->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue($cellWords[1] . '5', "Овог, Нэр (Код)")->getColumnDimension($cellWords[2])->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue($cellWords[2] . '5', "Албан тушаал")->getColumnDimension($cellWords[3])->setAutoSize(true);
        $cc = 3;
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A5:A6')
                ->mergeCells('B5:B6')
                ->mergeCells('C5:C6');

        foreach($days as $k => $drow) {           
            
            if (is_numeric($k)) {
                $objPHPExcel->getActiveSheet()->setCellValue($cellWords[$cc] . '5', $drow['MONTH'].'/'.$k)->getColumnDimension($cellWords[$cc])->setWidth(6);                
                $objPHPExcel->getActiveSheet()->setCellValue($cellWords[$cc] . '6', $drow['SPELL_DAY_SHORT_NAME']);
                $cc++;
            }
        }
        
        $cc = $cc - 1;
        $objPHPExcel->getActiveSheet()->getStyle($cellWords[0] . '5:' . $cellWords[$cc] . '5')->applyFromArray($headerStyles);
        $objPHPExcel->getActiveSheet()->getStyle($cellWords[3] . '6:' . $cellWords[$cc] . '6')->applyFromArray($headerStyles);

        //$objPHPExcel->getActiveSheet()->freezePane('A2');
        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

        $i = $jj = 7;
        $autoNumber = 1;
        $dayCount = $employeeCount = 0;
        (Array) $fontSize = array(
            'font' => array(
                'bold' => true,
                'size' => '9'
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )            
        );
        foreach ($result as $k => $department) {
            $objPHPExcel->getActiveSheet()->mergeCells($cellWords[0] . $jj . ':' . $cellWords[$cc] . $jj)->setCellValue($cellWords[0] . $jj,
                    $department['DEPARTMENT_NAME'])->getStyle($cellWords[0] . $jj . ':' . $cellWords[$cc] . $jj)->applyFromArray($fontSize);
            $jj = $jj + 1;
            
            foreach ($department['EMPLOYEES'] as $j => $employee) {
                $jjj = ($exportType == '1') ? $jj + 1 : $jj;
                $objPHPExcel->getActiveSheet()
                        ->setCellValue($cellWords[0] . $jj, $autoNumber++ . '.')
                        ->setCellValue($cellWords[1] . $jj,
                                mb_substr($employee['row']['lastname'], 0, 1, 'utf-8') . '.' . $employee['row']['firstname'] . ' (' . $employee['row']['code'] . ')')
                        ->setCellValue($cellWords[2] . $jj, $employee['row']['positionname'])
                        ->setCellValue($cellWords[3] . $jj, $employee['row']['statusname']);
                $colIndex = 3;
                $objPHPExcel->setActiveSheetIndex()
                        ->mergeCells($cellWords[0] . $jj . ':' . $cellWords[0] . $jjj)
                        ->mergeCells($cellWords[1] . $jj . ':' . $cellWords[1] . $jjj)
                        ->mergeCells($cellWords[2] . $jj . ':' . $cellWords[2] . $jjj)->getStyle($cellWords[0] . $jj . ':' . $cellWords[$cc] . $jj)->applyFromArray($fontSize);

                    foreach($days as $c => $drow) {         
                        foreach ($employee['rows'] as $kk => $ddepartment) {
                        if (is_numeric($c)) {
                            if ($ddepartment['monthid'] == $days[$c]['MONTH']) {
                                if (isset($ddepartment['color' . $c]) && !empty($ddepartment['color' . $c])) {
                                    $objPHPExcel->getActiveSheet()->getStyle($cellWords[$colIndex] . $jj)->applyFromArray(array('fill' => array(
                                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                            'color' => array('rgb' => str_replace("#", "", $ddepartment['color' . $c]))
                                        )
                                    ));     
                                }
                                
                                // if ($exportType == '1') {
                                //     $objPHPExcel->getActiveSheet()->setCellValue($cellWords[$colIndex] . $jj,
                                //             isset($employee['START_TIME_' . $c]) ? $employee['START_TIME_' . $c] : '')->getStyle($cellWords[0] . $jj . ':' . $cellWords[$cc] . $jj)->applyFromArray($fontSize);
                                //     $objPHPExcel->getActiveSheet()->setCellValue($cellWords[$colIndex] . $jjj,
                                //             isset($employee['END_TIME_' . $c]) ? $employee['END_TIME_' . $c] : '')->getStyle($cellWords[0] . $jjj . ':' . $cellWords[$cc] . $jjj)->applyFromArray($fontSize);
                                    
                                //     if (isset($employee['COLOR_' . $c]) && !empty($employee['COLOR_' . $c])) {            
                                //         $objPHPExcel->getActiveSheet()->getStyle($cellWords[$cc] . $jjj)->applyFromArray(array('fill' => array(
                                //                 'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                //                 'color' => array('rgb' => str_replace("#", "", $employee['COLOR_' . $c]))
                                //             )
                                //         ));                        
                                //     }                        
                                // } else {
                                //     $objPHPExcel->getActiveSheet()->setCellValue($cellWords[$colIndex] . $jj,
                                //             isset($employee['PLAN_TIME_' . $c]) ? $employee['PLAN_TIME_' . $c] : '')->getStyle($cellWords[0] . $jj . ':' . $cellWords[$cc] . $jj)->applyFromArray($fontSize);
                                // }
                                $objPHPExcel->getActiveSheet()->setCellValue($cellWords[$colIndex] . $jj,
                                        isset($ddepartment['plantime' . $c]) ? $ddepartment['plantime' . $c] : '')->getStyle($cellWords[0] . $jj . ':' . $cellWords[$cc] . $jj)->applyFromArray($fontSize);

                                $colIndex++;
                            }
                        }
                    }
                }
                $jj = ($exportType == '1') ? $jj + 2 : $jj + 1;
            }
            $i = ($exportType == '1') ? $i + 2 : $i + 1;
        }

        $allColumnCount = $cc + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells($cellWords[0] . '1:' . $cellWords[$allColumnCount] . '1')
                ->setCellValue($cellWords[0] . '1',
                        'БАТЛАВ : Албаны дарга ..................................................../ .................. /                                             ')
                ->getStyle($cellWords[0] . '1')
                ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));
        $objPHPExcel->getActiveSheet()
                ->mergeCells($cellWords[0] . '2:' . $cellWords[$allColumnCount] . '2')
                ->setCellValue($cellWords[0] . '2', Date::currentDate('Y оны m сарын d өдөр'))
                ->getStyle($cellWords[0] . '2:' . $cellWords[$allColumnCount] . '2')
                ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));

        $footerRow = $jj + 1;

        $objPHPExcel->getActiveSheet()
                ->mergeCells($cellWords[0] . ($footerRow + 1) . ':' . $cellWords[$allColumnCount - 1] . ($footerRow + 1))
                ->setCellValue($cellWords[0] . ($footerRow + 1),
                        'Хуваарь боловсруулсан: ..................................................../ ' . Ue::getSessionPersonWithLastName() . ' / ')
                ->getStyle($cellWords[0] . ($footerRow + 1) . ':' . $cellWords[$allColumnCount - 1] . ($footerRow + 1))
                ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->setTitle('Ажилтны төлөвлөгөө ' . $planYear . '.' . $planMonth);
        $objPHPExcel->setActiveSheetIndex(0);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
        header('Content-Disposition: attachment;filename="Ажилтны цагийн төлөвлөгөөний жагсаалт ' . Input::param($_POST['planYear']) . '/' . Input::param($_POST['planMonth']) . '.xlsx"');
        flush();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }    
    
    public function deleteEmployeePlanV2() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->deleteEmployeePlanV2Model();
        echo json_encode($data);
    }    
    
    public function saveEmployeePlanPasteV2() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->saveEmployeePlanPasteV2Model();
        echo json_encode($data);
    }    
    
    public function getRefMonthList() {
        $this->load->model('mdtimestable', 'middleware/models/');
        $data = $this->model->getRefMonthListModel();
        jsonResponse($data);
    }    
    
    public function getArchivPlanListMainDataGridV2() {
        $data = $this->model->getArchivPlanListMainDataGrid2Model();
        echo json_encode($data); exit;
    }
    
}