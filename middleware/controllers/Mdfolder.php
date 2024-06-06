<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdfolder Class 
 * 
 * @package     IA PHPframework
 * @subpackage	PF Folder
 * @category	Mdfolder
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.veritech.mn/PHPframework/Middleware/Mdfolder
 */

class Mdfolder extends Controller {
    
    public static $viewMetaDataPath = 'middleware/views/metadata/';
    private static $viewPath = 'middleware/views/folder/';
    
    public function __construct()
    {
        parent::__construct();
        Auth::handleLogin();
    }
        
    public function deleteFolderDialog() {
        
        $this->view->folderId = Input::numeric('folderId');
        $result = $this->model->isUsedFolderModel($this->view->folderId);
        $this->view->isParent = $result['result'];
        
        $response = array(
            'Html'     => $this->view->renderPrint('deleteFolder', self::$viewPath),
            'Title'    => $this->lang->line('msg_title_confirm'),
            'isParent' => $this->view->isParent, 
            'yes_btn'  => $this->lang->line('yes_btn'),
            'no_btn'   => $this->lang->line('no_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function deleteFolder(){
        $folderId = Input::post('folderId');
        $replaceId = Input::post('moveFolderId');
        echo json_encode($this->model->deleteFolderModel($folderId, $replaceId));
    }
    
    public function folderSelectableGrid() {

        $this->view->chooseType = Input::post('chooseType');
        $this->view->singleSelect = ($this->view->chooseType == 'multi') ? 'false' : 'true';
        $this->view->defaultCriteria = '';
        $this->view->searchParams = '';
        $this->view->isNamedParam = false;
        
        if (Input::postCheck('params')) {
            $requestParams = Input::post('params');
            parse_str($requestParams, $params);
            if (count($params) > 0) {
                foreach ($params as $k => $v) {
                    $this->view->{$k} = $v;
                    if ($k === 'autoSearch' && $v == '1') {
                        $this->view->defaultCriteria = "defaultCriteria: '" . Str::remove_querystring_var($requestParams, 'autoSearch') . "'";
                        $this->view->searchParams = "queryParams: {" . $this->view->defaultCriteria . "},";
                    }
                }
                $this->view->isNamedParam = true; 
            }
        }
        $this->view->searchForm = $this->view->renderPrint('common/sub/searchFolderForm', self::$viewMetaDataPath);

        $response = array(
            'Html' => $this->view->renderPrint('common/sub/selectableFolderGrid', self::$viewMetaDataPath),
            'Title' => $this->lang->line('META_00024'),
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'addbasket_btn' => $this->lang->line('addbasket_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function commonFolderGrid() {
        $result = $this->model->commonFolderGridModel();
        echo json_encode($result); exit;
    }
    
    public function metaFolderGridAutoComplete() {
        
        $type = Input::post('type');
        $q = Input::post('q');
        $result = $this->model->metaFolderGridCompleteModel($type, $q);    
        
        echo json_encode($result); exit;
    }
    
}