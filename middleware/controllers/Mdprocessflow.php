<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdprocessflow extends Controller {

    private static $viewPath = 'middleware/views/processflow/';
    public static $doneBpList = array();

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    protected function returnBpId($data, $bpOrder) {
        if ($bpOrder != null) {
            foreach ($data as $key => $value) {
                if ($_POST['bpOrder'][$key] == $bpOrder) {
                    return $_POST['doBpId'][$key];
                }
            }
        } else {
            return '';
        }
    }

    public function drawProcess() {
        $this->load->model('mdprocessflow', 'middleware/models/');

        $visualData = $this->model->getVisualDataListModel(Input::post('mainBpId'));
        $visualDataTrue = false;

        if ($visualData) {
            $visualDataTrue = true;
            $visualData = json_decode(Str::cleanOut($visualData), true);
        }

        $data = Input::post('metaTypeCode');
        $object = array();
        $connect = array();
        $positionLeft = 120;
        $positionTop = 80;

        $isStart = Input::post('isStart');
        array_push($object, array('metaProcessWorkFlowId' => '0', 'id' => 'startObject001', 'title' => '', 'type' => 'circle', 'class' => 'wfIconCircle', 'positionTop' => '20', 'positionLeft' => '470', 'borderColor' => '#f00a0a', 'borderWidth' => '2', 'background' => '#f00a0a', 'width' => '30', 'height' => '30'));
        array_push($object, array('metaProcessWorkFlowId' => '0', 'id' => 'endObject001', 'title' => '', 'type' => 'circle', 'class' => 'wfIconCircle', 'positionTop' => '600', 'positionLeft' => '470', 'borderColor' => '#41a2b7', 'borderWidth' => '2', 'background' => '#41a2b7', 'width' => '30', 'height' => '30'));

        $endToId = "";
        $isEnd = false;
        $i = 0;
        $j = 0;
        foreach ($data as $k => $val) {
            $j++;

            if ($_POST['outputMetaDataId'][$k] == '14359007153593') {
                array_push($object, array('metaProcessWorkFlowId' => $_POST['id'][$k], 'metaTypeCode' => $_POST['metaTypeCode'][$k], 'bpOrder' => $_POST['bpOrder'][$k], 'id' => $_POST['doBpId'][$k], 'title' => $_POST['metaDataName'][$k], 'type' => 'rombo', 'class' => 'wfIconRombo', 'positionTop' => $positionTop, 'positionLeft' => $positionLeft, 'borderColor' => '#474747', 'borderWidth' => '2', 'background' => '#fff', 'width' => '100', 'height' => '100'));
            } else {
                array_push($object, array('metaProcessWorkFlowId' => $_POST['id'][$k], 'metaTypeCode' => $_POST['metaTypeCode'][$k], 'bpOrder' => $_POST['bpOrder'][$k], 'id' => $_POST['doBpId'][$k], 'title' => $_POST['metaDataName'][$k], 'type' => 'rectangle', 'class' => 'wfIconRectangle', 'positionTop' => $positionTop, 'positionLeft' => $positionLeft, 'borderColor' => '#474747', 'borderWidth' => '2', 'background' => '#fff', 'width' => '100', 'height' => '80'));
            }

            if ($isStart == $_POST['doBpId'][$k]) {
                array_push($connect, array('source' => 'startObject001', 'target' => $isStart, 'strokeStyle' => '#61b7cf', 'lineWidth' => 3, 'fillStyle' => '#61b7cf', 'outlineColor' => '#61b7cf', 'width' => 0, 'length' => 0, 'location' => 0));
            }

            $trueTargetId = $this->returnBpId($data, $_POST['trueOrder'][$k]);
            $falseTargetId = $this->returnBpId($data, $_POST['falseOrder'][$k]);

            $boolenType = '-1';

            if ($_POST['doBpId'][$k] != null and ( $_POST['oldTrueOrder'][$k] != '' or $trueTargetId != '')) {

                if ($_POST['outputMetaDataId'][$k] == '14359007153593') {
                    $boolenType = 1;
                }

                array_push($connect, array('source' => $_POST['doBpId'][$k], 'lineBoolenType' => $boolenType, 'target' => $trueTargetId, 'strokeStyle' => '#61b7cf', 'lineWidth' => 3, 'fillStyle' => '#61b7cf', 'outlineColor' => '#61b7cf', 'width' => 0, 'length' => 0, 'location' => 0));
            }

            if ($_POST['doBpId'][$k] != null and ( $_POST['oldFalseOrder'][$k] != '' or $falseTargetId != '')) {
                array_push($connect, array('source' => $_POST['doBpId'][$k], 'lineBoolenType' => 0, 'target' => $falseTargetId, 'strokeStyle' => '#61b7cf', 'lineWidth' => 3, 'fillStyle' => '#61b7cf', 'outlineColor' => '#61b7cf', 'width' => 0, 'length' => 0, 'location' => 0));
            }

            if (!empty($_POST['trueOrder'][$k]) || !empty($_POST['falseOrder'][$k])) {
                $isEnd = true;
                $i++;
            }

            if ($_POST['doBpId'][$k] != '' and ( $trueTargetId == null and $falseTargetId == null)) {
                $endToId = $_POST['doBpId'][$k];
            }
            if ($j == 3) {
                $positionLeft = 0;
                $positionTop = $positionTop + 140;
                $j = 0;
            }
            $positionLeft = $positionLeft + 140;
        }

        if ($visualDataTrue) {
            $objectData = $visualData['object'];
            $object = array();
            foreach ($objectData as $row) {
                array_push($object, array(
                    'metaProcessWorkFlowId' => $row['metaProcessWorkFlowId'],
                    'metaTypeCode' => $row['metaTypeCode'],
                    'bpOrder' => $row['bpOrder'],
                    'id' => $row['id'],
                    'title' => $this->model->getOneMetaDataName($row['id']),
                    'type' => $row['type'],
                    'class' => $row['class'],
                    'positionTop' => $row['positionTop'],
                    'positionLeft' => $row['positionLeft'],
                    'borderColor' => $row['borderColor'],
                    'borderWidth' => $row['borderWidth'],
                    'background' => $row['background'],
                    'width' => $row['width'],
                    'height' => $row['height']
                    )
                );
            }
            $connectData = $visualData['connect'];
            foreach ($connectData as $row) {
                if ($row['pageTargetId'] == 'endObject001') {
                    array_push($connect, array('source' => $row['pageSourceId'], 'target' => 'endObject001', 'strokeStyle' => '#61b7cf', 'lineWidth' => 3, 'fillStyle' => '#61b7cf', 'outlineColor' => '#61b7cf', 'width' => 0, 'length' => 0, 'location' => 0));
                }
            }
        }

        echo json_encode(
            array(
                'object' => $object,
                'connect' => $connect
            )
        ); exit;
    }

    public function getProcessList() {
        $meta = array(
            'object' => array(
                array('id' => 2015061, 'title' => 'Cалбар хэлтэс', 'type' => 'rectangle', 'class' => 'wfIconRectangle', 'positionTop' => '20', 'positionLeft' => '20', 'borderColor' => '#000', 'borderWidth' => '5', 'background' => '#dd00ff', 'width' => '100', 'height' => '80'),
                array('id' => 2015062, 'title' => 'Захирал', 'type' => 'rectangle', 'class' => 'wfIconRectangle', 'positionTop' => '80', 'positionLeft' => '160', 'borderColor' => '#000', 'borderWidth' => '5', 'background' => '#dd00ff', 'width' => '90', 'height' => '70'),
                array('id' => 2015063, 'title' => 'Зарлагын төрөл', 'type' => 'rectangle', 'class' => 'wfIconRectangle', 'positionTop' => '60', 'positionLeft' => '360', 'borderColor' => '#000', 'borderWidth' => '5', 'background' => '#dd00ff', 'width' => '80', 'height' => '60'),
                array('id' => 2015064, 'title' => 'Орлогын төрөл', 'type' => 'rombo', 'class' => 'wfIconRombo', 'positionTop' => '20', 'positionLeft' => '450', 'borderColor' => '#000', 'borderWidth' => '5', 'background' => '#dd00ff', 'width' => '80', 'height' => '80'),
                array('id' => 2015065, 'title' => 'Валют', 'type' => 'circle', 'class' => 'wfIconCircle', 'positionTop' => '220', 'positionLeft' => '500', 'borderColor' => '#000', 'borderWidth' => '5', 'background' => '#dd00ff', 'width' => '100', 'height' => '100')
            ),
            'connect' => array(
                array('source' => '2015061', 'target' => '2015062', 'strokeStyle' => '#000000', 'lineWidth' => 3, 'fillStyle' => 'lightgray', 'outlineColor' => '#000000', 'width' => 12, 'length' => 12, 'location' => 0.67),
                array('source' => '2015063', 'target' => '2015064', 'strokeStyle' => '#000000', 'lineWidth' => 3, 'fillStyle' => 'lightgray', 'outlineColor' => '#000000', 'width' => 12, 'length' => 12, 'location' => 0.67),
            )
        );
        echo json_encode($meta); exit;
    }
    
    public function metaProcessWorkflow($mainBpId = '') {

        $this->view->mainBpId = $mainBpId;
        $this->view->title = $this->lang->line('metadata_process');
        
        $this->view->css = array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss());
        $this->view->js = array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()); 
        $this->view->fullUrlJs = array('middleware/assets/js/mdprocessflow.js');
        $this->view->mainBpData = $this->model->getMetaDataModel($this->view->mainBpId);
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('metaProcessWorkflow/metaProcessWorkflow', self::$viewPath);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function getChildMetaByProcess() {

        $metaDataId = Input::numeric('metaDataId');
        $getClassNameProcess = $this->model->getClassNameProcessModel($metaDataId);

        $response = array('className' => $getClassNameProcess);
        
        if (Input::post('isIgnoreProcessList') != '1') {
            $getChildMetaByProcess = $this->model->getChildMetaByProcessModel($metaDataId);
            $response['bpData'] = $getChildMetaByProcess;
        }
        
        echo json_encode($response); exit;
    }

    public function saveMetaProcess() {
        $result = $this->model->saveMetaProcessModel();
        echo json_encode($result); exit;
    }

    public function getInputMetaParameterByProcess() {

        $this->view->title = 'Процессийн параметр';
        $this->view->mainBpId = Input::numeric('mainBpId');
        $this->view->doProcessid = Input::numeric('doProcessId');   

        $this->view->getInputParameterList = $this->model->getParameterList(0, $this->view->mainBpId, $this->view->doProcessid);
        $this->view->getMetaDoneBpList = Mdprocessflow::$doneBpList;
        $this->view->getMetaTypeProcessList = $this->model->gMetaTypeProcessListModel($this->view->mainBpId.', '.$this->view->doProcessid);
        
        $this->view->mainBpName = '';
        $this->view->doProcessName = '';

        foreach ($this->view->getMetaTypeProcessList as $value) {
            if ($value['META_DATA_ID'] == $this->view->mainBpId) {
                $this->view->mainBpName = $value['META_DATA_CODE'] . ' - ' . $value['META_DATA_NAME'];
            }
            if ($value['META_DATA_ID'] == $this->view->doProcessid) {
                $this->view->doProcessName = $value['META_DATA_CODE'] . ' - ' . $value['META_DATA_NAME'];
            }
        }

        $this->view->render('inputparameter', self::$viewPath);
    }

    public function getInputMetaParameterByProcess2() {

        $this->view->title = 'Процессийн параметр';
        $this->view->mainBpId = $this->model->getBpModel(Input::numeric('mainBpId'));
        $this->view->doProcessid = $this->model->getBpModel(Input::numeric('doProcessId'));   

        $this->view->getInputParameterList = $this->model->getParameterList(0, $this->view->mainBpId, $this->view->doProcessid);
        $this->view->getMetaDoneBpList = Mdprocessflow::$doneBpList;
        $this->view->getMetaTypeProcessList = $this->model->gMetaTypeProcessListModel($this->view->mainBpId.', '.$this->view->doProcessid);
        
        $this->view->mainBpName = '';
        $this->view->doProcessName = '';

        foreach ($this->view->getMetaTypeProcessList as $value) {
            if ($value['META_DATA_ID'] == $this->view->mainBpId) {
                $this->view->mainBpName = $value['META_DATA_CODE'] . ' - ' . $value['META_DATA_NAME'];
            }
            if ($value['META_DATA_ID'] == $this->view->doProcessid) {
                $this->view->doProcessName = $value['META_DATA_CODE'] . ' - ' . $value['META_DATA_NAME'];
            }
        }

        $this->view->render('inputparameter', self::$viewPath);
    }

    public function getInputMetaParameterByProcess3() {

        $this->view->title = 'Процессийн параметр';
        // $this->view->mainBpId = $this->model->getBpModel(Input::numeric('mainBpId'));
        $this->view->mainDomainBpId = Input::numeric('doProcessId');
        $this->view->mainBpId = $this->model->getBpModel(Input::numeric('mainBpId'));
        $this->view->doProcessid = $this->model->getBpModel(Input::numeric('doProcessId'));   

        $this->view->getInputParameterList = $this->model->getParameterList2(0, $this->view->mainBpId, $this->view->doProcessid, 0, $this->view->mainDomainBpId);
        $this->view->getMetaDoneBpList = Mdprocessflow::$doneBpList;
        $this->view->getMetaTypeProcessList = $this->model->gMetaTypeProcessListModel($this->view->mainBpId.', '.$this->view->doProcessid);
        
        $this->view->mainBpName = Input::post("dataModelName");
        $this->view->doProcessName = '';

        foreach ($this->view->getMetaTypeProcessList as $value) {
            if ($value['META_DATA_ID'] == $this->view->doProcessid) {
                $this->view->doProcessName = $value['META_DATA_CODE'] . ' - ' . $value['META_DATA_NAME'];
            }
        }

        $this->view->render('inputparameter2', self::$viewPath);
    }

    public function getInputMetaParameterIndicator() {

        $this->view->title = 'Процессийн параметр';
        // $this->view->mainBpId = $this->model->getBpModel(Input::numeric('mainBpId'));
        $this->view->mainDomainBpId = Input::numeric('doProcessId');
        $this->view->mainIndicatorId = Input::numeric('mainBpId');
        $this->view->mainBpId = $this->model->getBpIndicatorModel(Input::numeric('mainBpId'));
        $this->view->doProcessid = $this->model->getBpIndicatorModel(Input::numeric('doProcessId'));   

        $this->view->getInputParameterList = $this->model->getParameterList2(0, $this->view->mainBpId, $this->view->doProcessid, 0, $this->view->mainDomainBpId);
        $this->view->getMetaDoneBpList = Mdprocessflow::$doneBpList;
        $this->view->getMetaTypeProcessList = $this->model->gMetaTypeProcessListModel($this->view->mainBpId.', '.$this->view->doProcessid);
        
        $this->view->mainBpName = Input::post("dataModelName");
        $this->view->doProcessName = '';

        foreach ($this->view->getMetaTypeProcessList as $value) {
            if ($value['META_DATA_ID'] == $this->view->doProcessid) {
                $this->view->doProcessName = $value['META_DATA_CODE'] . ' - ' . $value['META_DATA_NAME'];
            }
        }

        $this->view->render('inputparameter2', self::$viewPath);
    }

    public function getInputShowHideMetaParameterByProcess() {

        $mainBpId = Input::post('mainBpId');
        $doProcessId = Input::post('doProcessId');
        $isShow = Input::post('isShow');
        $isInputOutput = Input::post('isInputOutput');
        
        echo $this->model->getParameterList($isInputOutput, $mainBpId, $doProcessId, $isShow); exit;
    }

    public function getOutputMetaParameterByProcess() {

        $this->view->title = 'Процессийн параметр';
        $this->view->mainBpId = Input::post('mainBpId');
        $this->view->doProcessid = Input::post('mainBpId');

        $this->view->getOutputParameterList = $this->model->getParameterList(1, $this->view->mainBpId, $this->view->doProcessid, 1);
        $this->view->getParameterListCheck = $this->model->getParameterListWithPathModel($this->view->doProcessid, 0);
        $this->view->getMetaDoneBpList = $this->model->getMetaDoneBpListModel($this->view->mainBpId, $this->view->doProcessid);
        $this->view->getMetaTypeProcessList = $this->model->gMetaTypeProcessListModel($this->view->mainBpId);

        $this->view->render('outputparameter', self::$viewPath);
    }

    public function saveMetaProcessParameter() {
        $response = $this->model->saveMetaProcessParameterModel();
        echo json_encode($response); exit;
    }

    public function saveMetaProcessParameter2() {
        $response = $this->model->saveMetaProcessParameter2Model();
        echo json_encode($response); exit;
    }

    public function saveMetaProcessParameterIndicator() {
        $response = $this->model->saveMetaProcessParameterIndicatorModel();
        echo json_encode($response); exit;
    }

    public function parameterListCheck() {
        $doneBpId = Input::numeric('doneBpId');
        $isCheck = Input::numeric('isCheck');
        $response = $this->model->getParameterListWithPathModel($doneBpId, $isCheck);
        echo json_encode($response); exit;
    }

    public function saveVisualMetaProcess() {
        $this->load->model('mdprocessflow', 'middleware/models/');
        
        $object = json_decode($_POST['objects'], true);
        if (count($object) > 1) {
            $connect = json_decode($_POST['connections'], true);
            $result = $this->model->saveVisualMetaProcessModel($object, $connect);
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах боломжгүй lifeCycle байна');
        }
        echo json_encode($result); exit;
    }

    public function drawSearchMetaDataId($data, $metaDataId) {
        foreach ($data as $key => $value) {
            if ($value == $metaDataId) {
                return true;
            }
        }
        return false;
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

    public function drawProcessHtml($return = false) {
        $this->load->model('mdprocessflow', 'middleware/models/');
        $mainBpId = Input::post('mainBpId');
        $object = $this->model->getObjectPositionListModel($mainBpId);
        if ($return) return $object;
        
        convJson($object);
    }

    public function lastRunTaskFlow() {
        $this->load->model('mdprocessflow', 'middleware/models/');
        $mainBpId = Input::post('mainBpId');
        $recordId = Input::post('recordId');
        $object = $this->model->lastRunRecordTaskFlowModel($mainBpId, $recordId);
        
        echo json_encode($object, JSON_UNESCAPED_UNICODE);
    }

    public function drawProcessHtmlView($return = false) {
        $this->load->model('mdprocessflow', 'middleware/models/');
        $mainBpId = Input::post('mainBpId');
        $recordId = Input::post('recordId');
        $object = $this->model->getObjectPositionListViewModel($mainBpId, $recordId);
        if ($return) return $object;
        
        echo json_encode($object, JSON_UNESCAPED_UNICODE);
    }

    protected function returnVisualBpId($data, $bpOrder) {
        if ($bpOrder != null) {
            foreach ($data as $key => $value) {
                if ($value['BP_ORDER'] == $bpOrder) {
                    return $value['META_DATA_ID'];
                }
            }
        } 
        return '';
    }

    public function deleteProcessParameter() {
        $response = $this->model->deleteProcessParameterModel(Input::post('id'));
        echo json_encode($response); exit;
    }

    public function bpCriteria() {
        
        $this->view->mainBpId = Input::post('mainBpId');
        $this->view->sourceId = Input::post('sourceId');
        $this->view->targetId = Input::post('targetId');
        
        $this->view->criteria = Input::post('criteria');
        $this->view->isScheduled = Input::post('isScheduled');
        $this->view->scheduleDatePath = Input::post('scheduleDatePath');
        
        $response = array(
            'Html' => $this->view->renderPrint('bpCriteria', self::$viewPath),
            'Title' => 'bpCriteria',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveBpCriteria() {
        $response = $this->model->saveBpCriteriaModel();
        echo json_encode($response); exit;
    }
    
    public function deleteArrow() {
        $response = $this->model->deleteArrowModel(Input::post('mainBpId'), Input::post('doBpId'));
        echo json_encode($response); exit;
    }
    
    public function jsplumb() {

        $this->view->title = 'Процесс';

        $this->view->css = array(
            'custom/addon/plugins/jsplumb/jsplumb.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jsplumb/jquery.jsPlumb-1.4.1-all-min.js',
            'custom/addon/plugins/jsplumb/jsplumb-config.js'
        );
        $this->view->render('header');
        $this->view->render('master', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function controlProcess($mainBpId = '', $workFlowId = '', $type = '') {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->load->model('mdprocessflow', 'middleware/models/');
        
        $this->view->uniqId = getUID();
        $this->view->mainBpId = $mainBpId;
        $this->view->workFlowId = $workFlowId;
        $this->view->transId = Input::post('transId');
        $this->view->type = Input::post('type');
        
        $this->view->showFields = ($mainBpId != '' && $workFlowId != '') ? true : false; 
        $this->view->title = 'Ажлын урсгал';
        $this->view->isAjax = is_ajax_request();
        $this->view->isShowOnly = Input::numeric('isShowOnly');
        $this->view->isMainWindow = Input::numeric('isMainWindow');
        $this->view->fromType = $type;
        
        $_POST['metaDataId'] = $mainBpId;
        $getWorkFlow = $this->model->getWorkFlowModel();
        
        if ($getWorkFlow['data']) {
            $this->view->arrowShape = $getWorkFlow['data'][0]['ARROW_SHAPE'];
        } else {
            $this->view->arrowShape = '';
        }
        
        if ($mainBpId == '' && $workFlowId == '') {
            
            if (!$this->view->isAjax) {
                $this->view->css = array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss(), array('middleware/assets/theme/theme4/css/main.css'));
                $this->view->js = array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()); 
                $this->view->fullUrlJs = array('middleware/assets/js/mdworkflowProcess.js');
                $this->view->render('header');
            }
            
            $this->view->getMetaTypeProcessList = $this->model->metaTypeProcessListModel();
            
            $this->view->render('controlProcess', self::$viewPath);
        
            if (!$this->view->isAjax) {
                $this->view->render('footer');
            }
            
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('controlProcess', self::$viewPath)
            );
            echo json_encode($response); exit;
        }
    }
    
    public function renderWorkflow() {
        
        $isWorkFlow = Input::numeric('isWorkFlow');
        
        if ($isWorkFlow == 1) {
            
            parse_str(Input::post('workSpaceParams'), $workSpaceParamArray);
            
            if (isset($workSpaceParamArray['workSpaceParam']['id'])) {
                
                $indicatorId = $workSpaceParamArray['workSpaceParam']['id'];
                
                $_POST['isShowOnly'] = 0;
                $_POST['isMainWindow'] = 1;
                
                $this->controlProcess($indicatorId, '', 'metaverse');
            }
        }
    }
    
    public function controlProcessInteractive($mainBpId = '', $workFlowId = '') {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->load->model('mdmetadata', 'middleware/models/');
        $getRow = $this->model->getMetaGroupLinkModel($mainBpId);        
        $this->view->refStructureId = $getRow['REF_STRUCTURE_ID'];
        $this->view->dataViewId = $mainBpId;
        $mainBpId = $getRow['REF_STRUCTURE_ID'];
        
        $this->view->mainBpId = $mainBpId;
        $this->view->uniqId = getUID();        
        $this->view->workFlowId = $workFlowId;
        $this->view->transId = Input::post('transId');
        $this->view->selectedRow = Input::post('selectedRow');
        $this->view->type = Input::post('type');
        
        $this->view->showFields = ($mainBpId != '' && $workFlowId != '') ? true : false; 
        $this->view->title = 'Ажлын урсгал';
        
        $this->load->model('mdprocessflow', 'middleware/models/');
        $this->view->isAjax = is_ajax_request();
        $_POST['metaDataId'] = $mainBpId;
        $getWorkFlow = $this->model->getWorkFlowModel();
        try {
            $this->view->arrowShape = $getWorkFlow['data'][0]['ARROW_SHAPE'];
        } catch (Exception $ex) {
            $this->view->arrowShape = '';
        }
        
        if ($this->view->selectedRow) {
            $transId = $this->model->getTransitionFromWfmId($this->view->selectedRow['wfmstatusid']);
            if ($transId) {
                $this->view->transId = $transId['ID'];
            }
        }
        
        if ($mainBpId == '' && $workFlowId  == '') {
            
            if (!$this->view->isAjax) {
                $this->view->css = array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss(), array('middleware/assets/theme/theme4/css/main.css'));
                $this->view->js = array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()); 
                $this->view->fullUrlJs = array('middleware/assets/js/mdworkflowProcess.js');
                $this->view->render('header');
            }
            
            $this->view->getMetaTypeProcessList = $this->model->metaTypeProcessListModel();
            
            $this->view->render('controlProcessInteractive', self::$viewPath);
        
            if (!$this->view->isAjax) {
                $this->view->render('footer');
            }
            
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('controlProcessInteractive', self::$viewPath)
            );
            echo json_encode($response); exit;
        }
    }
    
    public function controlProcessPack($mainBpId = '', $workFlowId = '') {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->mainBpId = $mainBpId;
        $this->view->workFlowId = $workFlowId;
        $this->view->transId = Input::post('transId');
        
        $this->view->showFields = ($mainBpId != '' && $workFlowId != '') ? true : false; 
        $this->view->title = 'Ажлын урсгал';
        
        $this->load->model('mdprocessflow', 'middleware/models/');
        $this->view->isAjax = is_ajax_request();
        
        if ($mainBpId == '' && $workFlowId  == '') {
            
            if (!$this->view->isAjax) {
                $this->view->css = array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss(), array('middleware/assets/theme/theme4/css/main.css'));
                $this->view->js = array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()); 
                $this->view->fullUrlJs = array('middleware/assets/js/mdworkflowProcessPack.js');
                $this->view->render('header');
            }
            
            $this->view->getMetaTypeProcessList = $this->model->metaTypeProcessListModel();
            
            $this->view->render('controlProcessPack', self::$viewPath);
        
            if (!$this->view->isAjax) {
                $this->view->render('footer');
            }
            
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('controlProcessPack', self::$viewPath)
            );
            echo json_encode($response); exit;
        }
    }
    
    public function getWorkFlow() {
        $result = $this->model->getWorkFlowModel();
        echo json_encode($result); exit;
    }
    
    public function drawWorkFlowProcess() {
        
        $object = $this->model->getWorkFlowStatusModel();
        $connect = $this->model->getWorkFlowStatusTransitionModel();
        
        echo json_encode(array('object' => $object, 'connect' => $connect)); exit;
    }
    
    public function createWfmWorkFlow() {
        $result = $this->model->createWfmWorkFlowModel();
        echo json_encode($result); exit;
    }
    
    public function createWfmWorkFlowPack() {
        $result = $this->model->createWfmWorkFlowPackModel();
        echo json_encode($result); exit;
    }
    
    public function saveWfmWorkFlow() {
        $result = $this->model->saveWfmWorkFlowModel();
        echo json_encode($result); exit;
    }
    
    public function addWorkFlowStatusForm() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->transitionId = Input::post('transitionId');
        $this->view->colors = Mdcommon::standartColorClass();
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/addWorkFlowStatusForm', self::$viewPath),
            'Title' => 'Төлөв нэмэх',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addWorkFlowFirstStatusForm() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->statusList = $this->model->getWorkFlowStatusArrModel($this->view->metaDataId);
        $this->view->newStatus = (sizeOf($this->view->statusList) == 0) ? '1' : '0';
        $this->view->colors = Mdcommon::standartColorClass();
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/addWorkFlowFirstStatusForm', self::$viewPath),
            'Title' => 'Төлөв нэмэх',
            'newStatus' => $this->view->newStatus,
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function editWorkFlowStatusForm() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaWfmStatusId = Input::numeric('metaWfmStatusId');
        $this->view->wfmStatusDefaultRuleList = $this->model->getwfmStatusDefaultRuleListModel();
        $this->view->metaWfmStatus = $this->model->getMetaWfmStatusDataModel($this->view->metaWfmStatusId);
        $this->view->row = $this->view->metaWfmStatus;
        $this->view->colors = Mdcommon::standartColorClass();
        $this->view->isLock = $this->model->isWfmLockByStatusIdModel($this->view->metaWfmStatusId);
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/editWorkFlowStatusForm', self::$viewPath),
            'Title' => 'Төлөв засах',
            'isLock' => $this->view->isLock, 
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function createWfmStatus() {
        $result = $this->model->createWfmStatusModel();
        echo json_encode($result); exit;
    }
    
    public function createNewWfmStatus() {
        $result = $this->model->createNewWfmStatusModel();
        echo json_encode($result); exit;
    }
    
    public function saveWfmStatus() {
        $result = $this->model->saveWfmStatusModel();
        echo json_encode($result); exit;
    }
    
    public function updateWfmStatus() {
        $result = $this->model->updateWfmStatusModel();
        echo json_encode($result); exit;
    }
    
    public function updateWorkflowStatus() {
        $result = $this->model->updateWorkflowStatusModel();
        echo json_encode($result); exit;
    }
    
    public function updateWorkflowStatusTransition() {
        $result = $this->model->updateWorkflowStatusTransitionModel();
        echo json_encode($result); exit;
    }
    
    public function deleteStatusArrow() {
        $result = $this->model->deleteStatusArrowModel();
        echo json_encode($result); exit;
    }
    
    public function deleteWorkflowStatus() {
        $result = $this->model->deleteWorkflowStatusModel();
        echo json_encode($result); exit;
    }
    
    public function wfmCriteria() {
        
        $this->view->sourceId = Input::post('sourceId');
        $this->view->targetId = Input::post('targetId');
        $this->view->transitionId = Input::post('transitionId');
        $this->view->wfmCriteria = $this->model->wfmCriteriaModel($this->view->targetId, $this->view->sourceId, $this->view->transitionId);
        $metas = $this->model->getWorkFlowWfmFieldModel(Input::post('metaDataId'));
        $this->view->metaList = '';
        $this->view->uniqId = getUID();        

        $searchArr = $replaceArr = array();
        
        foreach ($metas as $k => $meta) {
            $checkLookUp = '';
            
            if (!empty($meta['FIELD_PATH'])) {
                if (!empty($meta['LOOKUP_META_DATA_ID'])) {
                    $checkLookUp = $meta['LOOKUP_META_DATA_ID'];
                }
                
                $this->view->metaList .= '<li class="d-flex" data-code="'.$meta['FIELD_PATH'].'" title="'.$meta['FIELD_PATH'].'"><span style="flex: 50%;">'.$meta['LABEL_NAME'].'</span><i class="icon-arrow-right8 ml8" onclick="wfmCriteriaMore(\''.Input::post('metaDataId').'\', \''.$checkLookUp.'\', \''.$meta['DESCRIPTION'].'\')" style="color:#1BBC9B"></i></li>';
                
                $searchArr[] = 'p_' . $k . '_code';
                $searchArr[] = 'p_' . $k . '_name';
                
                $replaceArr[] = $meta['FIELD_PATH'];
                $replaceArr[] = $meta['LABEL_NAME'];
                
                $this->view->wfmCriteria['CRITERIA'] = preg_replace('/\b'.$meta['FIELD_PATH'].'\b/u', '<span class="p-exp-meta" contenteditable="false" data-code="p_'.$k.'_code">p_'.$k.'_name<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $this->view->wfmCriteria['CRITERIA']);
            }
        }
        
        $this->view->wfmCriteria['CRITERIA'] = str_replace($searchArr, $replaceArr, $this->view->wfmCriteria['CRITERIA']);                
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/wfmCriteria', self::$viewPath),
            'Title' => 'Шалгуур',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function wfmCriteriaMore() {        
        $refId = Input::post('refId');
        $islookup = Input::post('islookup');
        $desc = Input::post('desc');
        $this->load->model('mdmetadata', 'middleware/models/');
        $html = '';

        if (empty($islookup)) {
            $criteria['filterStructureId'][] = array(
                'operator' => '=',
                'operand' => $refId
            );
            $param = array(
                'systemMetaGroupId' => '1596432285578320',
                'showQuery' => 0,
                'ignorePermission' => 1,  
                'criteria' => $criteria
            );
            
            $metas = '';
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
    
            if ($data['status'] == 'success' && isset($data['result'])) {
                
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);            
                $metas = $data['result'];
            }

            if ($metas) {
                $html .= '<table class="table table-bordered">';
                $html .= '<thead>';
                $html .= '<tr><td style="width:200px">Утга</td><td>Тайлбар</td></tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach ($metas as $k => $meta) {        
                    $html .= '<tr><td>'.$meta['fieldvalue'].'</td><td>'.$meta['description'].'</td></tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            }
        } else {
            // $getMeta = $this->model->getMetaDataModel($islookup);
            // $html = 'Тайлбар: <strong>' . $desc . '</strong> <br> Meta data name: <strong>' . $getMeta['META_DATA_NAME'] . '</strong>';
            $param = array(
                'systemMetaGroupId' => $islookup,
                'showQuery' => 0,
                'ignorePermission' => 1,
                'paging' => array(
                    'offset' => 1,
                    'pageSize' => 50
                ),                
            );
            
            $metas = '';
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            $this->load->model('mdobject', 'middleware/models/');
            $mainMetaDataValue = $this->model->getStandartFieldModel($islookup, 'meta_value');
            $mainMetaDataName = $this->model->getStandartFieldModel($islookup, 'meta_value_name');            
    
            if ($data['status'] == 'success' && isset($data['result'])) {
                
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);            
                $metas = $data['result'];
            }

            if ($metas) {
                $html .= '<table class="table table-bordered">';
                $html .= '<thead>';
                $html .= '<tr><td style="width:200px">Утга</td><td>Тайлбар</td></tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach ($metas as $k => $meta) {        
                    $html .= '<tr><td>'.(isset($meta['name']) ? $meta['name'] : $meta[$mainMetaDataName]).'</td><td>'.(isset($meta['description']) ? $meta['description'] : $meta[$mainMetaDataValue]).'</td></tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            }            
        }
        
        $response = array(
            'Html' => $html,
            'Title' => 'Дэлгэрэнгүй',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveWfmCriteria() {
        $response = $this->model->saveWfmCriteriaModel();
        echo json_encode($response); exit;
    }
    
    public function wfmTransitionForm() {

        $this->view->wfmStatusId = Input::post('wfmStatusId');
        $this->view->sourceId = Input::post('sourceId');
        $this->view->targetId = Input::post('targetId');
        $transitionData = $this->model->wfmCriteriaModel($this->view->targetId, $this->view->sourceId);
        $this->view->transitionId  = isset($transitionData['ID']) ? $transitionData['ID'] : '0';
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/wfmTransitionForm', self::$viewPath),
        );
        echo json_encode($response); exit;
    }
    
    public function wfmStatusForm() {

        $this->view->wfmStatusId = Input::post('wfmStatusId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaWfmStatusId = $this->view->wfmStatusId;
        $this->view->fromType = Input::post('fromType');
        
        $this->view->wfmStatusDefaultRuleList = $this->model->getwfmStatusDefaultRuleListModel();
        $this->view->metaWfmStatus = $this->model->getMetaWfmStatusDataModel($this->view->wfmStatusId);
        $this->view->colors = Mdcommon::standartColorClass();
        $this->view->isLock = $this->model->isWfmLockByStatusIdModel($this->view->wfmStatusId);
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/wfmStatusForm', self::$viewPath),
            'wfmStatusHtml' => $this->view->renderPrint('wfmStatus/editWorkFlowStatusForm', self::$viewPath),
            'data' => array(), 
            'isLock' => $this->view->isLock
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function getWfmTransitionUserList() {
        $result = $this->model->getWfmTransitionUserListModel();
        echo json_encode($result); exit;
    }
    
    public function getWfmTransitionRoleList() {
        $result = $this->model->getWfmTransitionRoleListModel();
        echo json_encode($result); exit;
    }
    
    public function getWfmStatusUserList() {
        $result = $this->model->getWfmStatusUserListModel();
        echo json_encode($result); exit;
    }
    
    public function getWfmStatusAssignmentList() {
        $result = $this->model->getWfmStatusAssignmentListModel();
        echo json_encode($result); exit;
    }
    
    public function getWfmStatusRoleList() {
        $result = $this->model->getWfmStatusRoleListModel();
        echo json_encode($result); exit;
    }
    
    public function getWfmStatusUserListByRole() {
        $result = $this->model->getWfmStatusUserListByRoleModel();
        echo json_encode($result); exit;
    }
    
    public function getWfmStatusStatusList() {
        $result = $this->model->getWfmStatusStatusListModel();
        echo json_encode($result); exit;
    }
    
    public function filterUserInfo() {
        $result = $this->model->filterUserInfoModel();
        echo json_encode($result); exit;
    }
    
    public function filterRoleInfo() {
        $result = $this->model->filterRoleInfoModel();
        echo json_encode($result); exit;
    }
    
    public function filterStatusInfo() {
        $result = $this->model->filterStatusInfoModel();
        echo json_encode($result); exit;
    }
    
    public function addTransitionUserPermission() {
        $result = $this->model->addTransitionUserPermissionModel();
        echo json_encode($result); exit;
    }
    
    public function addTransitionRolePermission() {
        $result = $this->model->addTransitionRolePermissionModel();
        echo json_encode($result); exit;
    }
    
    public function addStatusUserPermission() {
        $result = $this->model->addStatusUserPermissionModel();
        echo json_encode($result); exit;
    }
    
    public function addUserAssignment() {
        $result = $this->model->addUserAssignmentModel();
        echo json_encode($result); exit;
    }
    
    public function addStatusRolePermission() {
        $result = $this->model->addStatusRolePermissionModel();
        echo json_encode($result); exit;
    }
    
    public function addStatusStatusPermission() {
        $result = $this->model->addStatusStatusPermissionModel();
        echo json_encode($result); exit;
    }
    
    public function getMetaWfmWorkFlowData() {
        $result = $this->model->getMetaWfmWorkFlowDataModel();
        echo json_encode($result); exit;
    }
    
    public function updateWfmWorkFlow() {
        $result = $this->model->updateWfmWorkFlowModel();
        echo json_encode($result); exit;
    }
    
    public function deleteWfmWorkFlow() {
        $result = $this->model->deleteWfmWorkFlowModel();
        echo json_encode($result); exit;
    }
    
    public function getTransitionListJtreeData() {
        $response = $this->model->getTransitionListJtreeDataModel();
        echo json_encode($response); exit;
    }
    
    public function getTransitionListJtreeDataPack() {
        $response = $this->model->getTransitionListJtreeDataPackModel();
        echo json_encode($response); exit;
    }
    
    public function getTransitionNewListData() {
        
        $transitionId = Input::numeric('transitionId');
        $metaDataId = Input::numeric('metaDataId');
        
        try {
            
            $response = $this->model->getTransitionNewListDataModel($transitionId);
            $wfmStatusIds = (count($response['wfmStatusArr']) == 0) ? '0' : implode(',', $response['wfmStatusArr']);
            $wfmStatusData = $this->model->getTransitionStatusDataModel($wfmStatusIds, $transitionId);
            $workFlowIds = $this->model->getApprovedWorkflowStatusIdsModel($transitionId, $metaDataId);
            $isLock = $this->model->checkWfmLockModel($transitionId);
            
            $result = array(
                'status' => 'success', 
                'connect' => $response['object'], 
                'statusIds' => $response['wfmStatusArr'], 
                'object' => $wfmStatusData, 
                'workFlowStatus' => $workFlowIds, 
                'startWfmStatusId' => $response['startWfmStatusId'], 
                'isLock' => $isLock
            );
            
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        echo json_encode($result); exit;
    }
    
    public function getInteractiveTransitionNewListData() {
        $transitionId = Input::post('transitionId');
        $metaDataId = Input::numeric('metaDataId');
        try {
            $response = $this->model->getTransitionNewListDataModel($transitionId);
            $wfmStatusIds = (count($response['wfmStatusArr']) == 0) ? '0' : implode(',', $response['wfmStatusArr']);
            $wfmStatusData = $this->model->getInteractiveTransitionStatusDataModel($wfmStatusIds, $transitionId);
            $workFlowIds = $this->model->getApprovedWorkflowStatusIdsModel($transitionId, $metaDataId); /* $this->model->getMetaWfmStatusId($wfmStatusIds, Input::numeric('metaDataId'));*/
            echo json_encode(array('connect' => $response['object'], 'statusIds' => $response['wfmStatusArr'], 'object' => $wfmStatusData, 'workFlowStatus' => $workFlowIds, 'startWfmStatusId' => $response['startWfmStatusId'], 'status' => 'success')); exit;
        } catch (Exception $ex) {
            var_dump($ex);
            die;
        }
    }
    
    public function getTransitionNewListDataPack() {
        $transitionId = Input::post('transitionId');
        $metaDataId = Input::numeric('metaDataId');
        
        $response = $this->model->getTransitionNewListDataPackModel($transitionId);
        $wfmStatusIds = (count($response['wfmStatusArr']) == 0) ? '0' : implode(',', $response['wfmStatusArr']);
        $wfmStatusData = $this->model->getTransitionStatusDataPackModel($wfmStatusIds, $transitionId);
        $workFlowIds = $this->model->getApprovedWorkflowStatusIdsModel($transitionId, $metaDataId); /* $this->model->getMetaWfmStatusId($wfmStatusIds, Input::numeric('metaDataId'));*/
        echo json_encode(array('connect' => $response['object'], 'statusIds' => $response['wfmStatusArr'], 'object' => $wfmStatusData, 'workFlowStatus' => $workFlowIds, 'startWfmStatusId' => $response['startWfmStatusId'], 'status' => 'success')); exit;
    }
    
    public function saveVisualMetaStatusData() {
        $response = $this->model->saveVisualMetaStatusDataModel();
        echo json_encode($response); exit;
    }
    
    public function saveVisualMetaStatusDataPack() {
        $response = $this->model->saveVisualMetaStatusDataPackModel();
        echo json_encode($response); exit;
    }
    
    public function editWorkFlowFirstStatusForm() {
        
        $this->view->transitionId = Input::post('transitionId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->statusList = $this->model->getWorkFlowStatusArrModel($this->view->metaDataId, $this->view->transitionId);
        $this->view->data = $this->model->getWorkFlowTransitionModel($this->view->transitionId);;
        $metas = $this->model->getWorkFlowWfmFieldModel($this->view->metaDataId);
        $this->view->newStatus = (count($this->view->statusList) == 0) ? '1' : '0';
        $this->view->colors = Mdcommon::standartColorClass();
        $this->view->metaList = '';
        $this->view->uniqId = getUID();
        
        $searchArr = $replaceArr = array();
        
        foreach ($metas as $k => $meta) {
            
            if (!empty($meta['FIELD_PATH'])) {
                
                $this->view->metaList .= '<li data-code="'.$meta['FIELD_PATH'].'" title="'.$meta['FIELD_PATH'].'">'.$meta['LABEL_NAME'].'</li>';
                
                $searchArr[] = 'p_' . $k . '_code';
                $searchArr[] = 'p_' . $k . '_name';
                
                $replaceArr[] = $meta['FIELD_PATH'];
                $replaceArr[] = $meta['LABEL_NAME'];
                
                $this->view->data['CRITERIA'] = preg_replace('/\b'.$meta['FIELD_PATH'].'\b/u', '<span class="p-exp-meta" contenteditable="false" data-code="p_'.$k.'_code">p_'.$k.'_name<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $this->view->data['CRITERIA']);
            }
        }
        
        $this->view->data['CRITERIA'] = str_replace($searchArr, $replaceArr, $this->view->data['CRITERIA']);        
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/editWorkFlowFirstStatusForm', self::$viewPath),
            'Title' => 'Төлөв нэмэх',
            'newStatus' => $this->view->newStatus,
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function updateWfmWorkFlowTransition() {
        $result = $this->model->updateWfmWorkFlowTransitionModel();
        echo json_encode($result); exit;
    }
    
    public function createWfmWorkFlowFromGlobal() {
        $result = $this->model->createWfmWorkFlowFromGlobalModel();
        echo json_encode($result); exit;
    }
    
    public function deleteWfmTransition() {
        $result = $this->model->deleteWfmTransitionModel();
        echo json_encode($result); exit;
    }
    
    public function deleteWfmStatus() {
        $result = $this->model->deleteWfmStatusModel();
        echo json_encode($result); exit;
    }
    
    public function deleteWfmStatusPermission() {
        $result = $this->model->deleteWfmStatusPermissionModel();
        echo json_encode($result); exit;
    }
    
    public function saveVisualMetaProcessWorkflow() {
        
        $object = json_decode($_POST['objects'], true);

        if (count($object) > 1) {
            $connect = json_decode($_POST['connections'], true);
            $result = $this->model->saveVisualMetaProcessWorkflowModel($object, $connect);
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах боломжгүй lifeCycle байна');
        }
        
        convJson($result);
    }

    public function showBpmn($mainBpId = null) {
        $result = $this->model->showBpmnModel($mainBpId);
        echo $result["ADDON_XML_DATA"]; exit;
    }    

    public function checkBp() {
        $response = ["status"=>"success"];
        $idsArr = Input::post('domainbp');

        $result = $this->model->checkBpModel($idsArr);

        if (empty($result)) {
            echo json_encode(["status"=>"empty"]); exit;
        }

        $result = Arr::groupByArray($result, "SRC_RECORD_ID");

        $idsArrRes = [];
        foreach ($idsArr as $row) {
            if (!array_key_exists($row, $result)) {
                $response = ["status"=>"exist"];
                array_push($idsArrRes, $row);
            }
        }
        if ($response["status"] == "exist") {
            $response["ids"] = $idsArrRes;
        }

        echo json_encode($response); exit;
    }    

    public function checkBpIndicator() {
        $response = ["status"=>"success"];
        $idsArr = Input::post('domainbp');

        $result = $this->model->checkBpIndicatorModel($idsArr);

        if (empty($result)) {
            echo json_encode(["status"=>"empty"]); exit;
        }

        $result = Arr::groupByArray($result, "SRC_RECORD_ID");

        $idsArrRes = [];
        foreach ($idsArr as $row) {
            if (!array_key_exists($row, $result)) {
                $response = ["status"=>"exist"];
                array_push($idsArrRes, $row);
            }
        }
        if ($response["status"] == "exist") {
            $response["ids"] = $idsArrRes;
        }

        echo json_encode($response); exit;
    }    

    public function saveBpmn() {
        $result = $this->model->saveBpmnModel();
        echo json_encode($result); exit;
    }    

    public function saveBpmn2() {
        $result = $this->model->saveBpmn2Model();
        echo json_encode($result); exit;
    }    

    public function saveBpmnIndicator() {
        $result = $this->model->saveBpmnIndicatorModel();
        echo json_encode($result); exit;
    }    

    public function saveBpmnDraft() {
        $result = $this->model->saveBpmnDraftModel();
        echo json_encode($result); exit;
    }    

    public function saveBpmnDraftIndicator() {
        $result = $this->model->saveBpmnIndicatorDraftModel();
        echo json_encode($result); exit;
    }    
    
    public function getUid() {
        echo json_encode(array('ID' => getUID())); exit;
    }
    
    public function insertWfmStatusPack() {
        echo $this->model->insertWfmStatusPackModel(); exit;
    }
    
    public function filterBusinessProcessInfo() {
        $result = $this->model->filterBusinessProcessInfoModel();
        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }
    
    public function iseditStatusUserPermission() {
        $response = $this->model->iseditStatusUserPermissionModel();
        echo json_encode($response); exit;
    }
    
    public function getScheduleConfig() {
        
        $this->view->mainBpId = Input::post('mainBpId');
        $this->view->doProcessId = Input::post('doProcessId');
        $this->view->row = $this->model->getScheduleDataConfigModel($this->view->mainBpId, $this->view->doProcessId);
        
        if ($this->view->row) {
            $response = array(
                'Html' => $this->view->renderPrint('metaProcessWorkflow/scheduleConfig', self::$viewPath),
                'status' => 'success'
            );
        } else {
            $response = array('status' => 'warning', 'message' => 'Хадгалсаны дараа тохируулах боломжтойг анхаарна уу?');
        }
        
        echo json_encode($response); exit;
    }
    
    public function savescheduleConfig() {
        $result = $this->model->savescheduleConfigModel();
        echo json_encode($result); exit;
    }
    
    public function exportWorkflowFull()  {

        $exportData = $this->model->getexportWorkflowFullModel();
        
        if ($exportData['status'] === 'success') {
                
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.$exportData['object'].'-'.date('YmdHis').'.txt"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo $exportData['result'];
        } else {
            print(json_encode($exportData, JSON_PRETTY_PRINT)); die;
        }
        exit;
    }
    
    public function importWorkflowForm() {

        $response = array(
            'Html' => $this->view->renderPrint('import', self::$viewPath),
            'Title' => 'Import',
            'status' => 'success',
            'import_btn' => Lang::line('META_00087'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function exportWorkflowSingle()  {
        $this->load->model('mdprocessflow', 'middleware/models/');
        $exportData = $this->model->exportWorkflowSingleModel();
        
        if ($exportData['status'] === 'success') {
                
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.$exportData['object'].'-'.date('YmdHis').'.txt"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo $exportData['result'];
        }
        exit;
    }
    
    public function importWorkflow() {
        $result = $this->model->importWorkflowModel();
        echo json_encode($result); exit;
    }
    
    public function getWfmStatusLinkList() {
        $result = $this->model->getWfmStatusLinkListModel();
        echo json_encode($result); exit;
    }
    
    public function deleteWfmStatusLink() {
        $result = $this->model->deleteWfmStatusLinkModel();
        echo json_encode($result); exit;
    }
    
    public function saveMetaStatusLinkData() {
        $result = $this->model->saveMetaStatusLinkDataModel();
        echo json_encode($result); exit;
    }
    
    public function copyWfmWorkFlowTransition() {
        $result = $this->model->copyWfmWorkFlowTransitionModel();
        echo json_encode($result); exit;
    }

    public function startWfmCriteria() {
        
        $this->view->wfmCriteria['CRITERIA'] = '';
        $metas = $this->model->getWorkFlowWfmFieldModel(Input::post('metaDataId'));
        $this->view->metaList = '';
        $this->view->uniqId = getUID();        

        $searchArr = $replaceArr = array();
        
        foreach ($metas as $k => $meta) {
            
            if (!empty($meta['FIELD_PATH'])) {
                
                $this->view->metaList .= '<li data-code="'.$meta['FIELD_PATH'].'" title="'.$meta['FIELD_PATH'].'">'.$meta['LABEL_NAME'].'</li>';
                
                $searchArr[] = 'p_' . $k . '_code';
                $searchArr[] = 'p_' . $k . '_name';
                
                $replaceArr[] = $meta['FIELD_PATH'];
                $replaceArr[] = $meta['LABEL_NAME'];
                
                $this->view->wfmCriteria['CRITERIA'] = preg_replace('/\b'.$meta['FIELD_PATH'].'\b/u', '<span class="p-exp-meta" contenteditable="false" data-code="p_'.$k.'_code">p_'.$k.'_name<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $this->view->wfmCriteria['CRITERIA']);
            }
        }
        
        $this->view->wfmCriteria['CRITERIA'] = str_replace($searchArr, $replaceArr, $this->view->wfmCriteria['CRITERIA']);                
        
        $response = array(
            'Html' => $this->view->renderPrint('wfmStatus/startWfmCriteria', self::$viewPath),
            'Title' => 'Expression',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }    

    public function saveStartWfmCriteria() {
        $response = $this->model->saveStartWfmCriteriaModel();
        echo json_encode($response); exit;
    }    
    
    public function lockWfmTransition() {
        $response = $this->model->lockWfmTransitionModel();
        echo json_encode($response); exit;
    }
    
    public function unlockWfmTransition() {
        $response = $this->model->unlockWfmTransitionModel();
        echo json_encode($response); exit;
    }
    
    public function callTaskFlow($response = array()) {
        
        if (!$response) {
            $response = $this->model->callTaskFlowModel();
        }
        
        $isTaskFlowAutoRun = Input::post('isTaskFlowAutoRun');
        
        if ($response['status'] != 'success') {
            
            jsonResponse($response);
            
        } else {
            
            if ($isTaskFlowAutoRun) {
                jsonResponse(['status' => 'success']);
            }
            
            $_POST['metaDataId'] = $response['result']['_taskflowinfo']['doprocessid'];
            $_POST['fillDataParams'] = $response['result'];
            $_POST['isDialog'] = 'true';
            
            if (Input::post('isTaskFlowView') != '1') {
                unset($_POST['oneSelectedRow']);
            } else {
                $_POST['isGetConsolidate'] = 'true';
            }

            (new Mdwebservice())->callMethodByMeta(); exit;
        }
    }
    
    public function changeTaskFlowType() {
        $response = $this->model->changeTaskFlowTypeModel();
        jsonResponse($response);
    }
    
    public function viewTaskFlowLog() {
        
        $response = $this->model->viewTaskFlowLogModel();
        
        if ($response['status'] == 'success') {
            
            $this->view->bpList = $response['data'];
            $this->view->firstBpRender = $response['firstBpRender'];
            
            $response['html'] = $this->view->renderPrint('taskflow/viewTaskFlowLog', self::$viewPath);
        }
        
        jsonResponse($response);
    }
    
    public function taskFlowLogBp($bpId, $logId) {
        
        $renderResponse = $this->model->getTaskflowUIResponseModel($logId);
                
        if ($renderResponse['status'] == 'success') {
            $response = $this->model->taskFlowBpRenderModel($bpId, $renderResponse['data']);
        } else {
            $response = $renderResponse['message'];
        }
        
        echo $response; exit;
    }
    
    public function deleteAllArrowBp() {       
        $this->model->deleteAllArrowBpModel();
        echo 'Success';
    }
    
    public function getRole() {       
        $response = $this->model->allRoleByMetaIdModel();
        jsonResponse($response);
    }
    
    public function getRole2() {       
        $response = $this->model->allRoleByMetaId2Model();
        jsonResponse($response);
    }
    
    public function getTaskFlowType() {
        $response = $this->model->getTaskFlowTypeModel();
        jsonResponse($response);
    }    
    
    public function getProcessflowDescription() {
        $response = $this->model->getProcessflowDescriptionModel();
        convJson($response);
    }
    
    public function updateProcessflowDescription() {
        $response = $this->model->updateProcessflowDescriptionModel();
        convJson($response);
    }
    
}
