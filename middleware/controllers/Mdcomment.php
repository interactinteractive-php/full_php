<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdcomment Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Comment
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdcomment
 */

class Mdcomment extends Controller {
    
    private static $viewPath = 'middleware/views/comment/';
    
    public function __construct()
    {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function metaValueRender($metaDataId, $metaValueId)
    {
        $this->view->metaDataId = $metaDataId;
        $this->view->metaValueId = $metaValueId;
                
        return $this->view->renderPrint('renderMetaValue', self::$viewPath);
    }
    
    public function loadMetaProcess()
    {
        $this->view->uniqId = Input::numeric('uniqId');
        $this->view->processId = Input::numeric('processId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaValueId = Input::numeric('metaValueId');
        $this->view->listMetaDataId = Input::numeric('listMetaDataId');
        $this->view->commentStructureId = null;
        $rows = [];
        
        if ($this->view->metaDataId && $this->view->metaValueId) {
            
            if ($this->view->processId) {
                
                $this->load->model('mdwebservice', 'middleware/models/');
                
                $bpRow = $this->model->getMethodIdByMetaDataModel($this->view->processId);
                $this->view->commentStructureId = issetParam($bpRow['COMMENT_STRUCTURE_ID']);
                
                $this->load->model('mdcomment', 'middleware/models/');
            }
            
            $reaction = $this->model->getCommentReactionTypeModel($this->view->metaDataId);
            $rows = $this->model->getCommentMetaProcessRowsModel($this->view->metaDataId, $this->view->metaValueId, $this->view->commentStructureId, $this->view->listMetaDataId);
            
            $replyLabel = $this->lang->line('comment_reply');
            $editLabel = $this->lang->line('edit_btn');
            $deleteLabel = $this->lang->line('delete_btn');
                
            $this->view->commentRows = $this->model->buildCommentList($reaction, $rows, $replyLabel, $editLabel, $deleteLabel, $this->view->commentStructureId);
            
        } else {
            $this->view->commentRows = '';
        }
        
        $response = [
            'total' => count($rows),
            'html' => $this->view->renderPrint('loadMetaProcess', self::$viewPath)
        ];
        jsonResponse($response);
    }
    
    public function saveCommentProcess()
    {
        $response = $this->model->saveCommentProcessModel();
        convJson($response);
    }
    
    public function updateCommentProcess()
    {
        $response = $this->model->updateCommentProcessModel();
        jsonResponse($response);
    }
    
    public function removeCommentProcess()
    {
        $response = $this->model->removeCommentProcessModel();
        jsonResponse($response);
    }
    
    public function saveCommentReaction()
    {
        $response = $this->model->saveCommentReactionModel();
        jsonResponse($response);
    }    
    
}