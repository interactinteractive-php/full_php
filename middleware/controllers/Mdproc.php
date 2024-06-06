<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdproc extends Controller {

    private static $viewPath = 'middleware/views/proc/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index() {
        
        $this->view->title = 'Procurement';
        $this->view->uniqId = getUID();
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->rfId = Input::post('id');
        $this->view->procIsRequiredFile = Config::getFromCache('proc_isrequired_file');
        $this->view->procIsQty = Config::getFromCache('PROC_COMPARISON_IS_QTY');
        
        $this->view->getProcIndicatorList = $this->model->getProcIndicatorListModel();
        $this->view->getProcCustomerList = $this->model->getProcCustomerListModel($this->view->rfId);
        $this->view->getProcCustomerItemList = $this->model->getProcCustomerItemListModel($this->view->rfId);
        
        $this->view->wfmStatusId = '1529927135829413';
        $this->view->proc_required_percent_comparison = Config::getFromCacheDefault('PROC_REQUIRED_PERCENT_COMPARISON', null, '0');

        if (Config::getFromCache('PROC_EXT_COMPARISON_ORDER_TYPE_WORKFLOW') == '1') {
            $this->view->wfmStatusId = $this->model->getProcWfmIdModel($this->view->rfId);
        } elseif (is_array($this->view->getProcCustomerItemList['fromdepartmentdtl']) && count($this->view->getProcCustomerItemList['fromdepartmentdtl']) === 1) {
            $fromDepId = $this->view->getProcCustomerItemList['fromdepartmentdtl'][0]['departmentid'];
            $this->view->wfmStatusId = $this->model->getProcWfmStatusIdModel($fromDepId, $this->view->getProcCustomerItemList);
        }
        
        if (!is_ajax_request()) {
            
            $this->view->isAjax = false;
            $this->view->render('header');
            $this->view->render('index', self::$viewPath); 
            $this->view->render('footer');     
            
        } else {
            $this->view->isAjax = true;
            
            $response = array(
                'Html' => $this->view->renderPrint('index', self::$viewPath),
                'procIsRequiredFile' => $this->view->procIsRequiredFile,
                'Title' => Lang::line('PROC1'),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
            exit;
        }
    }
    
    public function edit() {
        
        $this->view->title = 'Procurement';
        $this->view->uniqId = getUID();
        $this->view->viewType = Input::post('viewType');
        $this->view->id = Input::post('id');
        $this->view->procIsQty = Config::getFromCache('PROC_COMPARISON_IS_QTY');
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->getProcIndicatorList = $this->model->getProcIndicatorListModel();
        $this->view->getRoleList = $this->model->getRolesModel();
        $this->view->getProcCustomerItemList = $this->model->getRowProcModel($this->view->id);
        $this->view->rfId = $this->view->getProcCustomerItemList['rfqid'];
        $this->view->BookNumber = $this->view->getProcCustomerItemList['booknumber'];
        $this->view->createduserid = $this->view->getProcCustomerItemList['createduserid']; 
        $this->view->bookDate = $this->view->getProcCustomerItemList['bookdate']; 
        $this->view->getProcCustomerList = $this->model->getProcCustomerListModel($this->view->getProcCustomerItemList['rfqid']);
        $this->view->getRowsProcFile = $this->model->getRowsProcFileModel($this->view->id);
        $this->view->getProcSupplier = $this->model->getProcSupplierListModel($this->view->id);
   
        if (!is_ajax_request()) {
            
            $this->view->isAjax = false;
            $this->view->render('header');
            $this->view->render('edit', self::$viewPath); 
            $this->view->render('footer');     
            
        } else {
            $this->view->isAjax = true;
            $response = array(
                'Html' => $this->view->renderPrint('edit', self::$viewPath),
                'Title' => Lang::line('PROC1'),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
            exit;            
        }
    }
    
    public function view() {
        
        $this->view->title = 'Procurement';
        $this->view->uniqId = getUID();
        $id = Input::post('id');
        $this->view->id = $id;
        $this->view->procIsQty = Config::getFromCache('PROC_COMPARISON_IS_QTY');
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->getProcCustomerItemList = $this->model->getRowViewProcModel($id);
        $this->view->getProcCustomerList = $this->model->getProcCustomerViewListModel($id);
        $this->view->dmMetaDataId = Input::post('dataViewId');
        $this->view->selectedRowData = Input::post('selectedRow');
        $this->view->wfmStatusParams = array();

        if (Input::isEmpty('selectedRow') === false) {
            $this->load->model('mdobject', 'middleware/models/');
            
            if (issetParam($this->view->selectedRowData['ishidestatus']) != '1') {
                $this->view->wfmStatusParams = $this->model->getWorkflowNextStatusModel($this->view->dmMetaDataId, $this->view->selectedRowData, '0');                
            }
        }        
        
        if (!is_ajax_request()) {
            
            $this->view->isAjax = false;
            $this->view->render('header');
            $this->view->render('view', self::$viewPath); 
            $this->view->render('footer');     
            
        } else {
            $this->view->isAjax = true;
            $response = array(
                'Html' => $this->view->renderPrint('view', self::$viewPath),
                'Title' => Lang::line('PROC1'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
            exit;            
        }
    }
    
    public function save() {
        echo json_encode($this->model->saveModel());
        exit;
    }
    
}
