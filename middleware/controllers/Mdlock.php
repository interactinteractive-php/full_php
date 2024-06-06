<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlock extends Controller {

    private static $viewPath = 'middleware/views/lock/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index() {
        
        $this->view->title = 'Процессын түгжээ';   
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            
            $this->view->render('header');
            $this->view->render('index', self::$viewPath); 
            $this->view->render('footer');     
            
        } else {
            $this->view->render('index', self::$viewPath); 
        }
    }
    
    public function treeview() {
        
        $categoryList = $this->model->getTreeLockCategoryListModel();

        $simpletree = new Tree();
        $simpletree->treeId = 'lockCatTreeview';
        $simpletree->rowId = 'ID';
        $simpletree->parentId = 'PARENT_ID';
        $simpletree->rowName = 'NAME';
        $simpletree->rowHtml = '<a href="javascript:;" data-id="#ROW_ID#" onclick="lockTreeCategoryFilter(\'#ROW_ID#\');">#NAME#</a>';
        $this->view->lockCategoryTreeView = $simpletree->treeView($categoryList);
        
        $this->view->render('treeview', self::$viewPath); 
    }
    
    public function lockDataGrid() {
        $data = $this->model->lockDataGridModel();
        echo json_encode($data); exit;
    }
    
    public function lockTempAdd() {
        
        $this->view->categoryId = Input::post('categoryId');
        $this->view->categoryList = $this->model->getLockCategoryListModel();
        
        $response = array(
            'html' => $this->view->renderPrint('lockTempAdd', self::$viewPath),
            'title' => Lang::line('add_btn'), 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function lockTempSave() {
        $response = $this->model->lockTempSaveModel(); 
        echo json_encode($response); exit;
    }
    
    public function deleteLockConfirm() {
        $response = array(
            'html' => $this->view->renderPrint('deleteLockConfirm', self::$viewPath),
            'title' => 'Сануулах', 
            'yes_btn' => Lang::line('yes_btn'), 
            'no_btn' => Lang::line('no_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function deleteLock() {
        $response = $this->model->deleteLockModel(); 
        echo json_encode($response); exit;
    }
    
    public function lockConfirm() {
        $response = array(
            'html' => $this->view->renderPrint('lockConfirm', self::$viewPath),
            'title' => 'Түгжих', 
            'lock_btn' => 'Түгжих', 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveLock() {
        $response = $this->model->saveLockModel(); 
        echo json_encode($response); exit;
    }
    
    public function unlockConfirm() {
        $response = array(
            'html' => $this->view->renderPrint('unlockConfirm', self::$viewPath),
            'title' => 'Түгжээг авах', 
            'lock_btn' => 'Түгжээг авах', 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveUnlock() {
        $response = $this->model->saveUnlockModel(); 
        echo json_encode($response); exit;
    }
    
    public function shareLockForm() {
        
        $this->load->model('mduser', 'middleware/models/');
        
        $this->view->users = $this->model->getSystemActiveUsers();
        
        $response = array(
            'html' => $this->view->renderPrint('sharelock', self::$viewPath),
            'title' => 'Эрх өгөх',
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function shareLockSave() {
        $response = $this->model->shareLockSaveModel(); 
        echo json_encode($response); exit;
    }
    
    public function lockCategoryAdd() {
        
        $this->view->categoryList = $this->model->getLockCategoryListModel();
        $this->view->categoryId = Input::post('categoryId');
        
        $response = array(
            'html' => $this->view->renderPrint('lockCategoryAdd', self::$viewPath),
            'title' => Lang::line('add_btn'), 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function lockCategorySave() {
        $response = $this->model->lockCategorySaveModel(); 
        echo json_encode($response); exit;
    }
    
    public function lockCategoryEdit() {
        
        $this->view->categoryId = Input::post('categoryId');
        $this->view->row = $this->model->getLockCategoryRowModel($this->view->categoryId);
        
        $this->view->categoryList = $this->model->getLockCategoryListModel();
        
        $response = array(
            'html' => $this->view->renderPrint('lockCategoryEdit', self::$viewPath),
            'title' => Lang::line('edit_btn'), 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function lockCategoryEditSave() {
        $response = $this->model->lockCategoryEditSaveModel(); 
        echo json_encode($response); exit;
    }
    
    public function lockCategoryDelete() {
        $response = $this->model->lockCategoryDeleteModel(); 
        echo json_encode($response); exit;
    }
    
    public function lockRowEdit() {
        
        $this->view->categoryList = $this->model->getLockCategoryListModel();
        
        $response = array(
            'html' => $this->view->renderPrint('lockRowEdit', self::$viewPath),
            'title' => Lang::line('edit_btn'), 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function lockEditMetaRowSave() {
        $response = $this->model->lockEditMetaRowSaveModel(); 
        echo json_encode($response); exit;
    }
    
    public function requestEdit() {
        
        $response = array(
            'html' => $this->view->renderPrint('requestEdit', self::$viewPath),
            'title' => Lang::line('request_send'), 
            'send_btn' => Lang::line('send_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function sendRequestEdit() {
        $response = $this->model->sendRequestEditModel(); 
        echo json_encode($response); exit;
    }
    
    public function lockRequests() {
        
        $this->view->requests = $this->model->lockRequestsModel(); 
        
        $response = array(
            'html' => $this->view->renderPrint('requests', self::$viewPath),
            'title' => 'Хүсэлтүүд', 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function requestCount() {
        $response = $this->model->requestCountModel(); 
        echo $response; exit;
    }
    
    public function requestAcceptForm() {
        
        $this->view->id = Input::numeric('id');
        $this->view->row = $this->model->getLockRequestModel($this->view->id); 
        
        $response = array(
            'html' => $this->view->renderPrint('requestAcceptForm', self::$viewPath),
            'title' => 'Хүсэлт зөвшөөрөх', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function requestAccept() {
        $response = $this->model->requestAcceptModel(); 
        echo json_encode($response); exit;
    }
    
    public function lockHistory() {
        $response = $this->model->lockHistoryModel(); 
        echo json_encode($response); exit;
    }
    
}
