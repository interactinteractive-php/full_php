<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Mdtaskflow extends Controller {

    private static $viewPath = "middleware/views/taskflow/";

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
        Message::add("s", "", URL . 'mdtaskflow/metaProcess');
    }

    public function metaProcess($dataModelId = '', $lcBookId = '', $lifeCycleId = '') {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'LifeCycle';

        $this->view->dataModelId = $dataModelId;
        $this->view->lcBookId = $lcBookId;
        $this->view->lifeCycleId = $lifeCycleId;

        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss()));
        $this->view->js = array_unique(array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()));
        
        $this->view->fullUrlJs = array('middleware/assets/js/mdtaskflow.js');

        $this->view->getMetaTypeProcessList = $this->model->metaTypeProcessListModel();

        $this->view->render('header');
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }

    public function businessProcess($mainBpId = '244100839465723', $lcBookId = '1', $sourceId = '7998350') {

        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Процесс';
        $this->view->mainBpId = $mainBpId;

        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss()));
        $this->view->js = array_unique(array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()));
        $this->view->fullUrlJs = array('middleware/assets/js/mdtaskflow.js');

        $this->view->getMetaTypeProcessList = $this->model->metaTypeProcessListModel();

        $this->view->dataModelId = $mainBpId;
        $this->view->lcBookId = $lcBookId;
        $this->view->sourceId = $sourceId;
        $result = $this->model->getDMLifeCycleModel($this->view->lcBookId, $this->view->sourceId);

        $this->view->errorMessage = null;
        if ($result['status'] == "success") {
            $this->view->getMetaLifecycle = $result['result'];
        } else {
            $message = $result['text'];
            $message .= $this->ws->errorReport($result);
            $this->view->errorMessage = $message;
        }

        $this->view->isAjax = true;

        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->headerInfo = (new Mdobject())->getDataModelHeaderData($mainBpId, $sourceId);
            $this->view->render('header');
        }

        $this->view->render('userprocess', self::$viewPath);

        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }

    public function getAdminChildMetaByProcess() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lifeCycleId = Input::post('lifeCycleId');
        $metaDataId = Input::numeric('metaDataId');
        $sourceId = Input::post('sourceId');
        $getChildMetaByProcess = $this->model->getAdminChildMetaByProcessModel($lifeCycleId, $sourceId);
        echo json_encode($getChildMetaByProcess);
    }
    public function getChildMetaByProcess() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lifeCycleId = Input::post('lifeCycleId');
        $metaDataId = Input::numeric('metaDataId');
        $sourceId = Input::post('sourceId');
        $getChildMetaByProcess = $this->model->getChildMetaByProcessModel($lifeCycleId, $sourceId);
        echo json_encode($getChildMetaByProcess);
    }

    public function drawProcessHtml() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $fromDbMetaData = array();
        $visualData = $this->model->getVisualDataListModel(Input::numeric('lifeCycleId'));
        $visualDataTrue = false;
        if ($visualData) {
            $visualDataTrue = true;
            $visualData = json_decode(Str::cleanOut($visualData), true);
        }

        $object = array();
        $connect = array();
        $positionLeft = 120;
        $positionTop = 80;

        $startObjectAddOnData = self::getAddOnDataItem($visualData['object'], 'startObject001');
        $endObjectAddOnData = self::getAddOnDataItem($visualData['object'], 'endObject001');

        array_push($object, array('id' => 'startObject001', 'title' => '', 'type' => 'circle', 'class' => 'wfIconCircle', 'positionTop' => ($startObjectAddOnData == false ? '20' : $startObjectAddOnData['positionTop']), 'positionLeft' => ($startObjectAddOnData == false ? '470' : $startObjectAddOnData['positionLeft']), 'borderColor' => '#f00a0a', 'borderWidth' => '2', 'background' => '#f00a0a', 'width' => '30', 'height' => '30'));
        array_push($object, array('id' => 'endObject001', 'title' => '', 'type' => 'circle', 'class' => 'wfIconCircle', 'positionTop' => ($endObjectAddOnData == false ? '600' : $endObjectAddOnData['positionTop']), 'positionLeft' => ($endObjectAddOnData == false ? '470' : $endObjectAddOnData['positionLeft']), 'borderColor' => '#41a2b7', 'borderWidth' => '2', 'background' => '#41a2b7', 'width' => '30', 'height' => '30'));

        $j = 0;
        $bpOrder = 0;

        if (Input::postCheck('processData')) {
            $data = $_POST['processData'];
            if (isset($data['object'])) {
                foreach ($data['object'] as $row) {
                    $j++;
                    $bpOrder++;
                    $addOnData = self::getAddOnDataItem($visualData['object'], $row['PROCESS_META_DATA_ID']);
                    if ($row['OBJECT_TYPE'] == '') {
                        $row['OBJECT_TYPE'] = 'rectangle';
                    }
                    array_push($object, array(
                        'metaDataCode' => $row['META_DATA_CODE'],
                        'title' => $row['META_DATA_NAME'],
                        'metaTypeCode' => $row['META_TYPE_ID'],
                        'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                        'lifeCycleDtlId' => $row['LIFECYCLE_DTL_ID'],
                        'isSolved' => $row['IS_SOLVED'],
                        'statusId' => $row['WFM_STATUS_ID'],
                        'isNonFlow' => $row['IS_NONFLOW'],
                        'id' => $row['PROCESS_META_DATA_ID'],
                        'type' => $row['OBJECT_TYPE'],
                        'class' => $row['OBJECT_TYPE'],
                        'positionTop' => ($addOnData == false ? $positionTop : $addOnData['positionTop']),
                        'positionLeft' => ($addOnData == false ? $positionLeft : $addOnData['positionLeft'])
                            )
                    );
                    if ($j == 3) {
                        $positionLeft = 0;
                        $positionTop = $positionTop + 140;
                        $j = 0;
                    }
                    $positionLeft = $positionLeft + 140;
                }
            }
            if (isset($data['connect'])) {
                if (count($visualData['connect']) > 0) {
                    foreach ($visualData['connect'] as $row) {
                        array_push($connect, array(
                            'source' => $row['pageSourceId'],
                            'target' => $row['pageTargetId'],
                                )
                        );
                    }
                }
            }
        }
        echo json_encode(
                array(
                    'object' => $object,
                    'connect' => $connect,
                    'addonconnect' => $visualData['connect'],
                    'addonobject' => $visualData['object']
                )
        );
    }

    public function checkNextObject($connect, $nextProcessId) {
        foreach ($connect as $key => $row) {
            if ($row['PREV_PROCESS_ID'] != $nextProcessId) {
                array_push($connect, array(
                    'source' => $row['PREV_PROCESS_ID'],
                    'target' => 'endObject001',
                        )
                );
            }
        }
    }

    public function getAddOnDataItem($object, $metaDataId) {
        if (is_array($object)) {
            foreach ($object as $key => $value) {
                if ($value['id'] == $metaDataId) {
                    return $value;
                }
            }
        }
        return false;
    }

    protected function returnVisualBpId($data, $bpOrder) {
        if ($bpOrder != null) {
            foreach ($data as $key => $value) {
                if ($value['BP_ORDER'] == $bpOrder) {
                    return $value['META_DATA_ID'];
                }
            }
        } else {
            return "";
        }
    }

    public function saveMetaProcess() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $result = $this->model->saveMetaProcessModel();
        echo json_encode($result);
    }

    public function saveVisualMetaProcess() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $object = json_decode($_POST['objects'], true);
        if (count($object) > 1) {
            $connect = json_decode($_POST['connections'], true);
            $result = $this->model->saveVisualMetaProcessModel($object, $connect);
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах боломжгүй lifeCycle байна');
        }
        echo json_encode($result);
    }

    public function getInputOutputMetaData() {
        $this->load->model('mdprocessflow', 'middleware/models/');
        $mainBpId = Input::numeric('mainBpId');
        echo json_encode($this->model->getInputOutputMetaDataIdModel($mainBpId));
    }

    public function getDMLifeCycleList() {
        $dataModelId = Input::numeric('lcBookId');
        echo json_encode($this->model->getDMLifeCycleModel($dataModelId));
    }

    public function getWorkFlowStatusList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        echo json_encode($this->model->getWorkFlowStatusListModel());
    }

    public function getLifeCycleDtl() {
        echo json_encode(getUID());
    }

    public function writeLog() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->writeLogModel());
    }

    public function metaDmPeriodicLimit() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->dataModelId = Input::post('dataModelId');
        $this->view->lcBookId = Input::numeric('lcBookId');
        $this->view->lifeCycleId = Input::post('lifeCycleId');
        $this->view->getMetaDmLifeCycle = $this->model->getMetaDmLifeCycleModel($this->view->lcBookId);
        $this->view->getRefTimeType = $this->model->getRefTimeTypeModel();
        $response = array(
            'Html' => $this->view->renderPrint('metaDmPeriodicLimit', self::$viewPath),
            'Title' => 'Хугацааны тохиргоо',
            'close_btn' => Lang::line('close_btn'),
        );
        echo json_encode($response);
    }

    public function getMetaDmLifeCycleProcess() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lifeCycleId = Input::numeric('lifeCycleId');
        echo json_encode($this->model->getMetaDmLifeCycleProcessModel($lifeCycleId));
    }

    public function getMetaDmLifeCycle() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lcBookId = Input::numeric('lcBookId');
        $lifeCycleId = Input::numeric('lifeCycleId');
        echo json_encode($this->model->getMetaDmLifeCycleModel($lcBookId, $lifeCycleId));
    }

    public function getMetaDmLifeCycleDtl() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lifeCycleId = Input::numeric('lifeCycleId');
        echo json_encode($this->model->getMetaDmLifeCycleDtlModel($lifeCycleId));
    }

    public function metaDmPeriodicLimitList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->metaDmPeriodicLimitListModel());
    }

    public function saveMetaDmPeriodicLimit() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $result = $this->model->saveMetaDmPeriodicLimitModel();
        echo json_encode($result);
    }

    public function metaDmPeriodicLimitDataGrid() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->metaDmPeriodicLimitDataGridModel());
    }

    public function removeMetaDmPeriodicLimit() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->removeMetaDmPeriodicLimitModel());
    }

    public function getChildLifecycle() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $parentId = Input::numeric('parent_id');
        $sourceId = Input::numeric('source_id');
        $result = $this->model->getDMLifeCycleChildModel($parentId, $sourceId);
        echo json_encode($result);
    }

    public function metaDmRepeat() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->getMetaDmLifeCycle = $this->model->getMetaDmLifeCycleModel();
        $this->view->getRefTimeType = $this->model->getRefTimeTypeModel();
        $response = array(
            'Html' => $this->view->renderPrint('metaDmRepeat', self::$viewPath),
            'Title' => 'Давтамжийн тохиргоо',
            'close_btn' => Lang::line('close_btn'),
        );
        echo json_encode($response);
    }

    public function metaDmRepeatDataGrid() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->metaDmRepeatDataGridModel());
    }

    public function saveMetaDmRepeat() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->saveMetaDmRepeatModel());
    }

    public function removeMetaDmRepeat() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->removeMetaDmRepeatModel());
    }

    public function metaDmEnable() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->getMetaDmLifeCycle = $this->model->getMetaDmLifeCycleModel();
        $this->view->getRefTimeType = $this->model->getRefTimeTypeModel();
        $response = array(
            'Html' => $this->view->renderPrint('metaDmEnable', self::$viewPath),
            'Title' => 'Ажиллах дарааллын тохиргоо',
            'close_btn' => Lang::line('close_btn'),
        );
        echo json_encode($response);
    }

    public function metaDmEnabletDataGrid() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->metaDmEnabletDataGridModel());
    }

    public function saveMetaDmEnable() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->saveMetaDmEnableModel());
    }

    public function removeMetaDmEnable() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->removeMetaDmEnableModel());
    }

    public function getProcessList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getProcessListModel(Input::numeric('lifeCycleId'), Input::numeric('mainBpId')));
    }

    public function metaDmBehaviour() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->criteriaDisable = false;
        $this->view->row = array();
        $this->view->row['id'] = Input::numeric('id');
        $this->view->row['inParamCriteria'] = (Input::post('inParamCriteria') === 'null' ? '' : Input::post('inParamCriteria'));
        $this->view->row['outParamCriteria'] = (Input::post('outParamCriteria') === 'null' ? '' : Input::post('outParamCriteria'));
        if ((Input::post('mainLifeCycle') === Input::post('doneLifeCycle')) && (Input::post('mainProcess') === Input::post('doneProcess'))) {
            $this->view->criteriaDisable = true;
        }

        $this->view->srcProcessName = $this->model->getProcessNameModel(Input::numeric('mainProcess'));
        $this->view->trgProcessName = $this->model->getProcessNameModel(Input::numeric('doneProcess'));
        $this->view->getMetaDmLifeCycleDtlList = $this->model->getMetaDmLifeCycleModel(Input::numeric('dataModelId'), Input::numeric('lifeCycleId'));

        $response = array(
            'Html' => $this->view->renderPrint('metaDmBehaviourConfig', self::$viewPath),
            'Title' => 'Үндсэн тохиргоо',
            'close_btn' => Lang::line('close_btn'),
            'save_btn' => Lang::line('save_btn'),
        );
        echo json_encode($response);
    }

    public function saveMetaDmBehaviour() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->saveMetaDmBehaviourModel());
    }

    public function removeMetaDmBehaviour() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->removeMetaDmBehaviourModel(Input::numeric('id')));
    }

    public function getLifeCycleList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getDMLifeCycleModel(Input::numeric('dataModelId')));
    }

    public function getBehaviourDtlList() {

        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getBehaviourDtlListModel(Input::numeric('lifeCycleDtlId')));
    }

    public function saveVisualMetaProcessBehaviourDtl() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->saveVisualMetaProcessBehaviourDtlModel());
    }

    public function getLifeCycleControlList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getDMLifeCycleControlListModel(Input::numeric('dataModelId')));
    }

    public function getMetaDmLcBookList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getMetaDmLcBookListModel(Input::numeric('dataModelId')));
    }

    public function startEndConfig() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->processMetaDataId = Input::numeric('processMetaDataId');
        $this->view->lifeCycleId = Input::numeric('lifeCycleId');
        $this->view->lcBookId = Input::numeric('lcBookId');

        $this->view->getCurrentLifeCycleName = $this->model->getLifeCycleNameModel($this->view->lifeCycleId);
        $this->view->getCurrentProcessName = $this->model->getProcessNameModel($this->view->processMetaDataId);
        $this->view->getDoneMetaDmLifeCycle = $this->model->getDoneMetaDmLifeCycleModel($this->view->lcBookId, $this->view->lifeCycleId);
        $this->view->startEndConfigList = $this->model->startEndConfigListModel($this->view->lcBookId, $this->view->lifeCycleId, $this->view->processMetaDataId);

        $response = array(
            'Html' => $this->view->renderPrint('startEndConfig', self::$viewPath),
            'Title' => 'Эхлэл, төгсгөлийн тохиргоо',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function getDoneMetaDmLifeCycle() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lcBookId = Input::numeric('lcBookId');
        $lifeCycleId = Input::numeric('lifeCycleId');
        echo json_encode($this->model->getDoneMetaDmLifeCycleModel($lcBookId, $lifeCycleId));
    }

    public function getDoneLastProcess() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lifeCycleId = Input::numeric('lifeCycleId');
        echo json_encode($this->model->getDoneLastProcessModel($lifeCycleId));
    }

    public function updateStartEndConfig() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->updateStartEndConfigModel());
    }

    public function removeStartEndConfig() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->removeStartEndConfigModel());
    }

    public function processHistory() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->css = array(
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js'
        );

        $this->view->lifeCycleDtlId = Input::numeric('lifeCycleDtlId');
        $this->view->sourceId = Input::numeric('sourceId');
        $response = array(
            'Html' => $this->view->renderPrint('metaDmHistory', self::$viewPath),
            'Title' => 'History',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function getProcessHistoryList() {
        $lifeCycleDtlId = Input::numeric('lifeCycleDtlId');
        $sourceId = Input::numeric('sourceId');
        echo json_encode($this->model->getProcessHistoryListModel($lifeCycleDtlId, $sourceId));
    }

    public function inputOutputHistory() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->data = '';
        $data = $this->model->getSystemLogDataModel(Input::numeric('id'));
        $type = Input::post('type');

        $arr = array();
        
        if ($type === 'input') {
            
            $convertedArray = Xml::createArray($data['REQUEST_DATA_ELEMENT'], false);
            Arr::convertDeArrayToArray($convertedArray['pDataElement'], $arr);
            $this->view->data = $arr['request']['parameters'];
            
        } else {
            
            $convertedArray = Xml::createArray($data['RESPONSE_DATA_ELEMENT'], false);
            Arr::convertDeArrayToArray($convertedArray['pDataElement'], $arr);
            $this->view->data = $arr['response'];
            
        }

        $response = array(
            'Html' => $this->view->renderPrint('inputOutputHistory', self::$viewPath),
            'Title' => 'History - ' . $type,
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function getDMLifeCycleParentChildId() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getDMLifeCycleParentChildIdModel(Input::numeric('lifeCycleId')));
    }

    public function drawTaskFlowListHtml() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss()));
        $this->view->js = array_unique(array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()));
        $this->view->fullUrlJs = array('middleware/assets/js/mdtaskflow.js');
        $data = Input::postData();
        $this->view->dataModelId = Input::paramNum($data['dataModelId']);
        $this->view->lcBookId = Input::paramNum($data['lcBookId']);
        $this->view->sourceId = Input::paramNum($data['sourceId']);
        $this->view->lifeCycleAllList = self::treeView($this->model->getLifeCycleAllList($this->view->lcBookId, $this->view->sourceId), $this->view->sourceId, $this->view->dataModelId);

        $response = array(
            'html' => $this->view->renderPrint('drawProcessList', self::$viewPath),
            'title' => 'Taskflow - list view',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function treeView($data, $sourceId, $dataModelId) {
        //issolved - true бол бүх criteria зөв ажиллаад дууссан
        //processstatusid - max repeat count-д хүрсэн эсэхийг хэлнэ. {new, inProcess, done}
        $view = '';
        foreach ($data as $k => $row) {
            $runProcess = '';
            $hiddenValue = '<input type=\'hidden\' name=\'lifeCycleId[]\' value=\'' . $row['lifecycleid'] . '\'>'
                    . '<input type=\'hidden\' name=\'wfmStatusId[]\' value=\'' . $row['wfmstatusid'] . '\'>'
                    . '<input type=\'hidden\' name=\'processMetaDataId[]\' value=\'' . $row['processmetadataid'] . '\'>'
                    . '<input type=\'hidden\' name=\'lifeCycleStatusId[]\' value=\'' . $row['lifecyclestatusid'] . '\'>'
                    . '<input type=\'hidden\' name=\'processStatusId[]\' value=\'' . $row['processstatusid'] . '\'>'
                    . '<input type=\'hidden\' name=\'isNonflow[]\' value=\'' . $row['isnonflow'] . '\'>'
                    . '<input type=\'hidden\' name=\'isSolved[]\' value=\'' . $row['issolved'] . '\'>'
                    . '<input type=\'hidden\' name=\'lifeCycleDtlId[]\' value=\'' . $row['lifecycledtlid'] . '\'>'
                    . '<input type=\'hidden\' name=\'sourceId[]\' value=\'' . $sourceId . '\'>'
                    . '<input type=\'hidden\' name=\'dataModelId[]\' value=\'' . $dataModelId . '\'>'
                    . '<input type=\'hidden\' name=\'type[]\' value=\'' . $row['type'] . '\'>'
                    . '<input type=\'hidden\' name=\'lcBookId[]\' value=\'' . $this->view->lcBookId . '\'>';

            $view .= '{';
            if ($row['type'] === 'lifecycle') {
                if ($row['lifecyclestatusid'] === '1') {
                    $view .= '"icon": "fa fa-folder icon-state-done lifecycle-done",';
                    $view .= '"state": {"opened": true},';
                    $view .= '"text": "<span class=\'lifecycle-done ' . $runProcess . '\'><strong>' . $row['name'] . '</strong></span>' . $hiddenValue . '",';
                } else {
                    $view .= '"icon": "fa fa-folder text-orange-400",';
                    $view .= '"state": {"opened": true},';
                    $view .= '"text": "<span class=\'' . $runProcess . '\'>' . $row['name'] . '</span>' . $hiddenValue . '",';
                }
            } else {

                if (($row['processstatusid'] === 'new' or $row['processstatusid'] === 'notFound') and $row['issolved'] === 'true') {
                    $runProcess = ' run-process ';
                    $view .= '"icon": "fa fa-play-circle icon-state-success run-process process-log-view",'; //icon-state-success-before
                    $view .= '"text": "<span class=\'' . $runProcess . ' process-log-view lifecycle-process\'>' . $row['name'] . '</span>' . $hiddenValue . '",';
                }
                if ($row['processstatusid'] === 'inProcess' and $row['issolved'] === 'true') {
                    $runProcess = ' run-process ';
                    $view .= '"icon": "fa fa-play-circle icon-state-success run-process process-log-view",';
                    $view .= '"text": "<span class=\'' . $runProcess . ' process-log-view lifecycle-process\'>' . $row['name'] . '</span>' . $hiddenValue . '",';
                }
                if ($row['processstatusid'] === 'done' and $row['issolved'] === 'true') {
                    $view .= '"icon": "fa fa-play-circle icon-state-done process-log-view lifecycle-process-done",';
                    $view .= '"state": {"disabled": true, "selected": true},';
                    $view .= '"text": "<span class=\'' . $runProcess . ' process-log-view lifecycle-process-done\'>' . $row['name'] . '</span>' . $hiddenValue . '",';
                }
                if ($row['processstatusid'] === 'new' and $row['issolved'] === 'false') {
                    $view .= '"icon": "fa fa-play-circle icon-state-success-before",';
                    $view .= '"state": {"disabled": true},';
                    $view .= '"text": "<span class=\'' . $runProcess . ' lifecycle-process-done\'>' . $row['name'] . '</span>' . $hiddenValue . '",';
                }
                if ($row['processstatusid'] === 'notFound' and $row['issolved'] === 'false') {
                    $view .= '"icon": "fa fa-play-circle icon-state-done process-log-view lifecycle-process-done",';
                    $view .= '"state": {"disabled": true},';
                    $view .= '"text": "<span class=\'lifecycle-process-done\'>' . $row['name'] . '</span>' . $hiddenValue . '",';
                }
            }

            if (isset($row['children'])) {
                if ($row['children']) {
                    $view .= 'children: [';
                    $view .= self::treeView($row['children'], $sourceId, $dataModelId);
                    $view .= '],';
                }
            }
            $view .= '},';
        }
        return $view;
    }

    public function inputOutputParameterConfigList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        $lifeCycleDtlId = Input::numeric('lifeCycleDtlId');
        $this->view->entityId = Input::numeric('entityId');
        $this->view->data = $this->model->getInputParameterLifeCycleProcessModel($lifeCycleDtlId);
        $result = $this->model->inputOutputParameterConfigListModel($this->view->data, $this->view->entityId);
        $this->view->parameterList = $result['data'];
        $this->view->isSaveValue = $result['isSaveValue'];
        $response = array(
            'html' => $this->view->renderPrint('inputOutputConfig', self::$viewPath),
            'title' => 'Процессын оролтын параметр тохируулах',
            'isSaveValue' => $result['isSaveValue'],
            'entityId' => $this->view->entityId,
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }
    
    public function getProcessParameterList() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getProcessParameterListModel(Input::post('isInput'), Input::post('processMetaDataId')));
        
    }
    
    public function saveBpParamLink() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->saveBpParamLinkModel());
    }
    
    public function deleteBpParamLink() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->deleteBpParamLinkModel());
    }
    
    public function getLifeCycleDtlId() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->getLifeCycleDtlIdModel(Input::numeric('doneLifeCycleId'), Input::numeric('doneProcessId')));
    }
    
    public function initDataViewParameters() {
        $this->load->model('mdtaskflow', 'middleware/models/');
        echo json_encode($this->model->initDataViewParametersModel(Input::numeric('entityId')));
    }
    
    public function editMetaSystemParams(){
        var_dump($_POST);
    }
    
}
