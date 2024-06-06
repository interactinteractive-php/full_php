<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlog extends Controller {

    private static $viewPath = 'middleware/views/log/';

    public function __construct() {
        parent::__construct();
    }
    
    public function customerFilterDataView() {
        Auth::handleLogin();
        
        $this->view->uniqId = getUID();
        $this->view->selectedRow = Input::post('selectedRow');
        $this->view->paramData = Input::post('paramData');
        $this->view->filterArr = array();
        
        if (isset($this->view->selectedRow['id']) && isset($this->view->selectedRow['dvmetadataid'])) {
            
            $this->view->metaDataId = $this->view->selectedRow['dvmetadataid'];
            $this->load->model('mdobject', 'middleware/models/');
            $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
            $filterData = $this->db->GetRow("SELECT FILTER_DATA FROM CUSTOMER_DV_FILTER_DATA WHERE ID = '" . $this->view->selectedRow['id'] . "'");

            if ($filterData) {

                $filterArr = json_decode($filterData['FILTER_DATA']);

                if ($filterArr) {

                    foreach ($filterArr as $key => $row) {
                        if (isset($row[0]->operand)) {
                            $operand = str_replace('%', '', $row[0]->operand);
                            if ($operand) {
                                $this->view->filterArr[$key] = $operand;
                            }
                        }
                    }
                }
            }
        }
        
        $response = array(
            'Html' => $this->view->renderPrint('dataview/customerFilterDataView', self::$viewPath),
            'Title' => 'Лог харах',
            'Width' => '1000',
            'Height' => '200',
            'close_btn' => Lang::line('close_btn'),
            'uniqId' => $this->view->uniqId,
        );
        echo json_encode($response); exit;
    }
    
    public function getLogData() {
        
        $data = array(
            'id' => '123456', 
            'code' => '001', 
            'name' => 'Name name name name', 
            'description' => 'Description description description description', 
            'description2' => 'Description description description description', 
            'rowsdtl_1' => array(
                array(
                    'id' => '999999',
                    'code' => 'dsfg sdfgsdfs',
                    'name' => '999sdfg ss999',
                ), 
                array(
                    'id' => '8888888',
                    'code' => 'dsfg sfg s sgsgss',
                    'name' => 'sdf fss fgsd sdfgsfsd sfs',
                ), 
                array(
                    'id' => '7777777',
                    'code' => 'rtyurur rtyr ryjry rytyr',
                    'name' => 'bnmv vnm vbnmbvn mvvb',
                )
            ), 
            'rowsdtl_2' => array(
                array(
                    'id' => '999999',
                    'code' => 'dsfg sdfgsdfs',
                    'name' => '999sdfg ss999',
                    'rowdtl' => array(
                        'title' => 'dfg sfsd sds', 
                        'type' => '132'
                    )
                ), 
                array(
                    'id' => '8888888',
                    'code' => 'dsfg sfg s sgsgss',
                    'name' => 'sdf fss fgsd sdfgsfsd sfs',
                    'rowdtl' => array(
                        'title' => 'dfg sfsd sds', 
                        'type' => '132'
                    )
                ), 
                array(
                    'id' => '7777777',
                    'code' => 'rtyurur rtyr ryjry rytyr',
                    'name' => 'bnmv vnm vbnmbvn mvvb',
                    'rowdtl' => array(
                        'title' => 'dfg sfsd sds', 
                        'type' => '132'
                    )
                )
            ), 
            'rowdtl' => array(
                'code' => '0009', 
                'name' => 'sdfgs sdfsd'
            )
        );
        $response = array('status' => 'success', 'data' => $data);
        
        jsonResponse($response);
    }
    
    public function getRecordLogHistoryList() {
        Auth::handleLogin();
        $response = $this->model->getRecordLogHistoryListModel();
        jsonResponse($response);
    }
    
    public function getRecordLogDetail() {
        Auth::handleLogin();
        $response = $this->model->getRecordLogDetailModel();

        if ($response['status'] == 'success') {
            
            if ($response['processId']) {
                
                $_POST['isDialog'] = 'true';
                $_POST['isSystemMeta'] = 'false';
                $_POST['processActionType'] = 'log';
                $_POST['isFillArrayPostParam'] = 1;
                $_POST['fillJsonParam'] = $response['data'];
                $_POST['metaDataId'] = $response['processId'];

                (new Mdwebservice())->callMethodByMeta(); exit;
                
            } else {
                jsonResponse($response);
            }
            
        } else {
            jsonResponse(array('errorMsg' => $response['message']));
        }
    }
    
     public function getRecordRemovedLogDetail() {
        Auth::handleLogin();
        $response = $this->model->getRecordLogDetailModel();
        jsonResponse($response);
    }
    
    public function renderAddEditLogs() {
        Auth::handleLogin();
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $dvId        = Input::numeric('dvId');
        $selectedRow = Input::post('selectedRow');
        
        $standardFields = $this->model->getCodeNameFieldNameModel($dvId);
        $id = $standardFields['id'] ? $standardFields['id'] : 'id';
        
        if (!isset($selectedRow[$id]) || (isset($selectedRow[$id]) && !$selectedRow[$id])) {
            jsonResponse(array('status' => 'error', 'message' => 'ID талбар тохиргоогүй байна!'));
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->metaDataId = '1604893919544822';
        $this->view->refStructureId = Input::numeric('refStructureId');
        $this->view->recordId = $selectedRow[$id];
        
        $this->view->dataGridHeader = $this->model->renderDataViewGridModel($this->view->metaDataId, 'logList-UPDATE', '', true);
        
        $this->view->columns = ((isset($this->view->dataGridHeader['header'])) ? str_replace(array("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},"), '', $this->view->dataGridHeader['header']) : '');
        $this->view->columns = str_replace(',},]]', "},{field:'id',title:'',sortable:false,width: '115px',halign: 'center',align: 'center',formatter:gridAddEditLogDetailMoreRow}]]", $this->view->columns);
        
        jsonResponse(array('status' => 'success', 'html' => $this->view->renderPrint('renderAddEditLogs', self::$viewPath)));
    }
    
    public function renderRemovedLogs() {
        Auth::handleLogin();
        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->metaDataId = '1604885708370';
        $this->view->dvId = Input::numeric('dvId');
        $this->view->refStructureId = Input::numeric('refStructureId');
        $this->view->isLogRecover = Input::post('islr');
        
        $this->view->dataGridHeader = $this->model->renderDataViewGridModel($this->view->metaDataId, 'logList-DELETE', '', true);
        
        $this->view->columns = ((isset($this->view->dataGridHeader['header'])) ? str_replace(array("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},"), '', $this->view->dataGridHeader['header']) : '');
        $this->view->columns = str_replace(',},]]', "},{field:'id',title:'',sortable:false,width: '115px',halign: 'center',align: 'center',formatter:gridRemoveLogDetailMoreRow}]]", $this->view->columns);
        
        $this->view->render('renderRemovedLogs', self::$viewPath);
    }
    
    public function logRecover() {
        Auth::handleLogin();
        $response = $this->model->logRecoverModel();
        jsonResponse($response);
    }
    
    public function ecmContentViewLog() {
        Auth::handleLogin();
        $response = $this->model->ecmContentViewLogModel();
        echo json_encode($response);
    }
    
    public function metaConfigChangeLog() {
        $response = $this->model->metaConfigChangeLogModel();
        echo json_encode($response);
    }
    
}
