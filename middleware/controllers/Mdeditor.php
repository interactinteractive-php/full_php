<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

/**
 * Mdnotification Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Notification
 * @author	B.Munkh-Erdene <munkherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdeditor
 */
class Mdeditor extends Controller {

    private static $viewPath = 'middleware/views/editor/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
        $this->view->metaFormList = $this->model->getMetaFormList();        
        $this->view->metaFormValueList = $this->model->getMetaFormValueList();
        
        $this->view->title = 'Form';
        $this->view->render('header');        
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }
    
    /**
     * Form үүсгэх
     * @param type $metaFormId
     */
    public function build($metaFormId = null) {
    
        if($metaFormId == null){
            $this->view->fileContent = "";
        }else{
            $this->view->fileContent = $this->model->getFormHtml($metaFormId);        
        }
        
        if($metaFormId){
            $this->view->formInfo = $this->model->getFormInfo($metaFormId);        
        }else{
            $this->view->formInfo = null;
        }
        
        // get process list
        $this->view->processMetaList = $this->model->getProcessMetaList();
        
        $this->extraExtensions('build');
        $this->view->title = 'Form';
        $this->view->render('header');        
        $this->view->render('build', self::$viewPath);
        $this->view->render('footer');
    }    
    
    /**
     * Form хадгалах
     */
    public function saveForm()
    {   
        try{
            
            // get params
            $formName = Input::post('formName');
            $metaFormId = Input::post('metaFormId');
            $processMetaDataId = Input::post('processMetaDataId');
            
            // get form html
            $data = $_POST['createForm'];
            $data = str_replace("div", "textarea", $data);
        
            // save to DB
            $metaFormId = $this->model->saveForm($formName, $metaFormId, $data, $processMetaDataId);
            
            
            // save to file
//        $htmlFileName = $metaFormId.".html";
//        $path = $_SERVER['DOCUMENT_ROOT'].'/storage/formTemplates/' . $htmlFileName;
//        $handle = fopen($path, 'w') or die('Cannot open file:  ' . $path);        
//        fwrite($handle, $data);
//        fclose($handle);
            
            // redirect
            Message::add('w', Lang::line('msg_save_success'), URL . 'mdmetadata/system');
        }  catch (Exception $e)
        {
            var_dump($e);die;
            Message::add('w', Lang::line('msg_error'), URL . 'mdmetadata/system');
        }
    }
    
    /**
     * Form нээх
     * @param type $metaFormId
     * @param type $metaFormAttemptId
     */
    public function open($metaFormId = null, $metaFormAttemptId = null)
    {
        $this->view->metaFormId = $metaFormId;
        if($metaFormAttemptId){            
            $this->view->metaFormValues = $this->model->getMetaFormValues($metaFormAttemptId);
            $this->view->metaFormAttemptId = $metaFormAttemptId;
        }else{            
            $this->view->metaFormValues = "[{}]";
            $this->view->metaFormAttemptId = null;
        }
        
        $this->callProcess($metaFormId);        
        $this->view->formHtml = $this->model->getFormHtml($metaFormId);
        $this->extraExtensions('open');
        $this->view->title = 'Form';
        $this->view->render('header');        
        $this->view->render('open', self::$viewPath);
        $this->view->render('footer');
    }
    
    /**
     * Засах
     * @param type $metaFormAttemptId
     */
    public function edit($metaFormAttemptId = null)
    {
        $this->view->metaFormId = $this->model->getMetaFormId($metaFormAttemptId);        
        $this->view->metaFormValues = $this->model->getMetaFormValues($metaFormAttemptId);        
        $this->view->formHtml = $this->model->getFormHtml($this->view->metaFormId);
        $this->view->metaFormAttemptId = $metaFormAttemptId;
        
        $this->view->title = 'Form';
        $this->view->render('header');        
        $this->view->render('open', self::$viewPath);
        $this->view->render('footer');
    }

    public function fillForm()
    {
        $postData = array();
        
        foreach($_POST AS $k => $v)
        {
            if(!in_array($k, array('formId', 'metaFormAttemptId')))
            {
                $postData['data'][$k] = Security::sanitize($v);
            }
        }
        
        if(Input::post('metaFormAttemptId') > 0){
            // Хуучин
            $status = $this->model->updateForm($postData, Input::post('formId'), Input::post('metaFormAttemptId'));
        }else{
            // Шинэ
            $status = $this->model->fillForm($postData, Input::post('formId'));
        }
        
        
        if($status)
        {
            Message::add('w', Lang::line('msg_save_success'), URL . 'mdeditor/formValueList');
        }else{
            Message::add('w', Lang::line('msg_error'), URL . 'mdmetadata/system');
        }
    }
    
    public function getTextMetas()
    {   
        $metaDataGroupId = Input::post('metaDataGroupId');
        $result = $this->model->getTextMetas($metaDataGroupId);
        if($result)
        {
            $response = array(
                'status' => 'success',
                'data' => $result
            );
        }else{
            $response = array(
                'status' => 'error'
            );
        
        }
        
        echo json_encode($response);
    }
    
    public function getGroupList()
    {   
        $result = $this->model->getGroupList();
        if($result)
        {
            $response = array(
                'status' => 'success',
                'data' => $result
            );
        }else{
            $response = array(
                'status' => 'error'
            );
        
        }
        
        echo json_encode($response);
    }
        
    public function deleteForm($metaFormId) {
        $metaFormId = (float) $metaFormId;
        try{
            $this->model->deleteForm($metaFormId);
            Message::add('w', Lang::line('msg_save_success'), URL . 'mdmetadata/system');
        }  catch (Exception $e)
        {
            Message::add('w', Lang::line('msg_error'), URL . 'mdmetadata/system');
        }
        
    }
    
    public function formList(){
        $this->view->title = 'Формын жагсаалт';
        $this->extraExtensions('formList');
        $this->view->render('header');        
        $this->view->render('formList', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function getFormList(){
        echo json_encode($this->model->getFormList());
    }
    
    public function formValueList(){
        $this->view->title = 'Форм утгын жагсаалт';
        $this->extraExtensions('formValueList');
        $this->view->render('header');        
        $this->view->render('formValueList', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function getFormValueList(){
        echo json_encode($this->model->getFormValueList());
    }

    private function extraExtensions($type) {
        switch ($type) {
            case "build":
                $this->view->css = array(                    
                );
                $this->view->fullUrlJs = array(
                    'assets/custom/addon/plugins/ckeditor-form-builder/ckeditor.js',
                );

                $this->view->js = array(
                    
                );
                    
                break;
            case "open":
                $this->view->css = array(                    
                );
                $this->view->fullUrlJs = array(
                    'assets/custom/addon/plugins/ckeditor-form-builder/ckeditor.js',
                );
                    
                break;
            case "formValueList":
                $this->view->js = array(
                    'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js'
                );
                break;
            case "formList":
                $this->view->js = array(
                    'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
                );
                break;
            default:
                
                break;
        }
    }
    
    private function callProcess($metaFormId = null) {
        if($metaFormId == null) {
            return null;
        }
        
        $this->view->getProcessMetaDataId = $this->model->getProcessMetaDataId($metaFormId);
        if($this->view->getProcessMetaDataId){
            $_POST['methodId'] = $this->view->getProcessMetaDataId;

            // RUN DE WEBSERVICE
            $this->load->model('mdwebservice', 'middleware/models/');
            $postData = Input::postData();
            $metaDataId = Input::param($postData['methodId']);
            $row = $this->model->getMethodIdByMetaDataModel($metaDataId);
            $param = array();
            $postData['param'] = array();
            
            if (isset($postData['param'])) {
                
              $paramData = $postData['param'];
              $paramList = $this->model->groupParamsDataModel($metaDataId, null, ' AND PAL.PARENT_ID IS NULL');

              foreach ($paramList as $input) {
                  
                $typeCode = strtolower($input['META_TYPE_CODE']);
                  
                if ($typeCode != 'group') {

                  if ($typeCode == 'boolean') {
                    if (isset($paramData[$input['META_DATA_CODE']])) {
                      $param[$input['META_DATA_CODE']] = '1';
                    } else {
                      $param[$input['META_DATA_CODE']] = '0';
                    }
                  } else {
                    if (isset($paramData[$input['META_DATA_CODE']])) {
                      $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType($paramData[$input['META_DATA_CODE']], $typeCode);
                    } else {
                      $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                    }
                  }
                } else {
                  if ($input['IS_SHOW'] == '1') {
                    $param[$input['META_DATA_CODE']] = (new Mdwebservice())->fromPostGenerateArray(
                        $metaDataId, $input['ID'], $input['META_DATA_CODE'], $input['RECORD_TYPE'], $paramData, 0
                    );
                  }
                }
              }
            }
            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param);

            if ($this->ws->isException()) {
              $result = array('status' => 'error', 'message' => $this->ws->getErrorMessage());
            } else {
              if (isset($result['result'])) {
                foreach ($result['result'] AS $k => $v) {
                  $this->view->card['TEXT_FROM_SERVICE'] = $v;
                }
              }
            }


            $this->view->fullUrlCss = array('middleware/assets/css/card/card.css');
            return $this->view->renderPrint('link/card/renderCard', self::$viewPath);
        }
    }
    
    public function dbt() {
        
        $this->view->title = 'DBT';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlCss = ['assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css'];
        $this->view->fullUrlJs = ['assets/custom/addon/plugins/codemirror/lib/codemirror.min.js'];
        
        $this->view->render('header');  
        $this->view->render('dbt', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function getDbtColumns() {
        $response = $this->model->getDbtColumnsModel();
        convJson($response);
    }
    
    public function getDbtDataGrid() {
        $response = $this->model->getDbtDataGridModel();
        convJson($response);
    }
    
}