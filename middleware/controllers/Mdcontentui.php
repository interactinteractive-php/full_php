<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdcontentui Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Content UI
 * @author	S.Satjan <satjan@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdcontentui
 */
class Mdcontentui extends Controller {

    private static $viewPath = 'middleware/views/contentui/';
    public static $contentHtmlFilePath = 'storage/uploads/content/';
    public static $contentHtmlTypeId = 49; // ECM_CONTENT_TYPE table - н ID Content төрлүүд доторх HMTML төрөл    

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
        self::getContentAssetsForIndex();
        $this->view->render('header');
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }

    public function getContentAssetsForIndex() {
        $this->view->title = 'Динамик контент editor';
        $this->view->css = AssetNew::metaCss();

        $this->view->fullUrlCss = array(
            'middleware/assets/css/contentui/contentUi.css',
            'middleware/assets/plugins/contentui/farbtastic/css/farbtastic.css',
            'middleware/assets/plugins/contentui/spectrum/spectrum.css',
        );

        $this->view->fullUrlJs = array(
            'middleware/assets/js/contentui/contentUi.js',
            'middleware/assets/plugins/contentui/farbtastic/js/farbtastic.js',
            'middleware/assets/plugins/contentui/spectrum/spectrum.js',
        );
    }

    public function getContentAssets() {
        $this->view->title = 'Динамик контент editor';
        $this->view->css = AssetNew::metaCss();

        $this->view->fullUrlCss = array(
            'middleware/assets/css/contentui/contentUi.css',
            'middleware/assets/plugins/contentui/farbtastic/css/farbtastic.css',
            'middleware/assets/plugins/contentui/spectrum/spectrum.css',
            'middleware/assets/css/card/card.css'
        );

        $this->view->js = array_unique(array_merge(AssetNew::metaOtherJs(),
                        array(
            'custom/addon/plugins/highstock/js/highstock.js',
            'custom/addon/plugins/highstock/js/modules/exporting.js',
            'custom/addon/plugins/jquery-flotchart/jquery.flot.js',
            'custom/addon/plugins/jquery-flotchart/jquery.flot.tooltip.min.js',
            'custom/addon/plugins/jquery-flotchart/jquery.flot.resize.js',
            'custom/addon/plugins/jquery-flotchart/jquery.flot.pie.resize.js',
            'custom/addon/plugins/jquery-flotchart/jquery.flot.animator.min.js',
            'custom/addon/plugins/jquery-flotchart/jquery.flot.growraf.js'
        )));

        $this->view->fullUrlJs = array(
            'middleware/assets/js/contentui/contentUi.js',
            'middleware/assets/plugins/contentui/farbtastic/js/farbtastic.js',
            'middleware/assets/plugins/contentui/spectrum/spectrum.js',
        );
    }

    public function update($metaDataId) {
        self::getContentAssetsForIndex();
        //$this->view->metaDataList = (new Mdmetadata())->getMetaMetaMapBySrcId($metaDataId);
        $this->load->model('mdlayout', 'middleware/models/');
        $row = $this->model->getLayoutMetaDataModel($metaDataId);

        if (isset($row['LAYOUT_ID'])) {
            $layoutId = $row['LAYOUT_ID'];
            $this->view->content = $row;
            $this->view->generateLayout = (new Mdlayout())->generateLayout($layoutId, $metaDataId, $row, true, false);
        }

        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->metaDataRow = $this->model->getMetaDataModel($metaDataId, true);
        $this->view->metaBackLink = 'mdmetadata/system';
        
        if (isset($this->view->metaDataRow['FOLDER_ID'])) {
            $this->view->metaBackLink = 'mdmetadata/system#objectType=folder&objectId=' . $this->view->metaDataRow['FOLDER_ID'];
        }

        $this->view->isBackLink = true;
        
        if ($backLink = Config::getFromCache('CONFIG_OBJECT_BACKLINK')) {
            $this->view->isBackLink = $backLink;
        }

        $this->view->render('header');
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }

    public function edit($layoutId) {
        self::getContentAssetsForIndex();
        //$this->view->metaDataList = (new Mdmetadata())->getMetaMetaMapBySrcId($metaDataId);
        $this->load->model('mdlayout', 'middleware/models/');
        $row = $this->model->getLayoutDataModel($layoutId);

        if (isset($row['LAYOUT_ID'])) {
            $layoutId = $row['LAYOUT_ID'];
            $this->view->content = $row;
            $this->view->generateLayout = (new Mdlayout())->generateLayoutData($layoutId, $row, true, false);
        }

        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->metaDataRow = $this->model->getMetaDataModel($layoutId, true);
        $this->view->metaBackLink = 'mdmetadata/system';
        if (isset($this->view->metaDataRow['FOLDER_ID'])) {
            $this->view->metaBackLink = 'mdmetadata/system#objectType=folder&objectId=' . $this->view->metaDataRow['FOLDER_ID'];
        }

        $this->view->isBackLink = true;
        
        if ($backLink = Config::getFromCache('CONFIG_OBJECT_BACKLINK')) {
            $this->view->isBackLink = $backLink;
        }

        $this->view->render('header');
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }

    public function setContentMeta($metaDataId) {
        self::getContentAssets();
        $this->view->metaDataList = (new Mdmetadata())->getMetaMetaMapBySrcId($metaDataId);
//    $this->view->metaDataList = $this->model->getMetaDataList($metaDataId);
        $this->load->model('mdlayout', 'middleware/models/');
        $row = $this->model->getLayoutMetaDataModel($metaDataId);
        if (isset($row['LAYOUT_ID'])) {
            $layoutId = $row['LAYOUT_ID'];
            $this->view->content = $row;
            $this->view->generateLayout = (new Mdlayout())->generateLayout($layoutId, $metaDataId, $row, true);
        }

        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->metaDataRow = $this->model->getMetaDataModel($metaDataId, true);
        $this->view->metaBackLink = 'mdmetadata/system';
        if (isset($this->view->metaDataRow['FOLDER_ID'])) {
            $this->view->metaBackLink = 'mdmetadata/system#objectType=folder&objectId=' . $this->view->metaDataRow['FOLDER_ID'];
        }

        $this->view->isBackLink = true;
        if ($backLink = Config::getFromCache('CONFIG_OBJECT_BACKLINK')) {
            $this->view->isBackLink = $backLink;
        }

        $this->view->render('header');
        $this->view->render('renderContent', self::$viewPath);
        $this->view->render('footer');
    }

    public function findMetaData() {
        $response = $this->model->findMetaData();
        echo json_encode($response);
    }

    public function createLayout() {
        $params = array();
        parse_str($_POST['data'], $params);

        $code = $params['layoutCode'];
        $name = $params['layoutName'];
        $rowCount = $params['rowCount'];
        $colCount = $params['colCount'];
        $bgColor = $params['bgColor'];
        $borderWidth = $params['borderWidth'];

        $bgImage = '';
        if (isset($_FILES[0]['name'])) {
            if ($_FILES[0]['name'] != '') {
                $newName = "bgImage_" . time();
                $ext = substr($_FILES[0]['name'], strrpos($_FILES[0]['name'], '.') + 1);
                $nname = $newName . "." . strtolower($ext);

                Upload::$File = $_FILES[0];
                Upload::$method = 0;
                Upload::$SavePath = UPLOADPATH . "contentui/";
                Upload::$NewName = $newName;
                Upload::$OverWrite = true;
                $imageUploadResult = Upload::UploadFile();
                if ($imageUploadResult == '') {
                    $bgImage = Security::sanitize($nname);
                }
            }
        }

        $cellArrayParam = $params['cellArray'];
        $tmpCellArray = str_replace("&#34;", "\"", $cellArrayParam);


        $cellArray = $this->convertAlltoArray(json_decode($tmpCellArray));

        if (isset($params['layoutId'])) {
            $layoutId = Security::float($params['layoutId']);
            $response = $this->model->updateLayout($layoutId, $code, $name, $bgColor, $borderWidth, $bgImage, $cellArray);
            echo json_encode($response);
            exit;
        } else {
            $response = $this->model->createLayout($code, $name, $rowCount, $colCount, $bgColor, $borderWidth, $bgImage, $cellArray);
        }

        $this->produceResponse($response);
    }

    public function setContentMetaData() {
        $cellArrayParam = Input::post('cellArray');
        $tmpCellArray = str_replace("&#34;", "\"", $cellArrayParam);
        $cellArray = $this->convertAlltoArray(json_decode($tmpCellArray));
        $layoutId = Input::post('layoutId');
        $metaDataId = Input::numeric('metaDataId');

        $response = $this->model->updateCell($layoutId, $cellArray, $metaDataId);
        $this->produceResponse($response);
    }

    private function produceResponse($response) {
        if (is_array($response)) {
            if (isset($response['errorMessage'])) {
                $response = array('status' => 'failed', 'message' => $response['errorMessage']);
            }
        } else if ($response) {
            $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            $response = array('status' => 'failed', 'message' => 'Хадгалж чадсангүй');
        }

        echo json_encode($response);
    }

    private function convertAlltoArray($cellArray) {
        $allArray = array();
        foreach ($cellArray AS $rowIndex => $row) {
            $row = (array) $row;

            foreach ($row AS $colIndex => $col) {
                $tmpColArray[$colIndex] = (array) $col;
                $allArray[$rowIndex] = $tmpColArray;
            }
        }

        return $allArray;
    }

    public function getMetaDataListByName() {
        $result = $this->model->getMetaDataListByName(Input::post('metaName'));
        echo json_encode($result);
    }

    public function renderMetaData() {
        echo (new Mdlayout())->metaDataRender(Input::numeric('metaDataId'), null);
    }

    /** CONTENT HTML BEGIN ************************************** */
    public function createContent() {
        $this->view->content = null;
        $this->view->content['DEFAULT_PATH'] = self::$contentHtmlFilePath;
        $this->view->content['TYPE_ID'] = self::$contentHtmlTypeId;

        $response = array(
            'Title' => Lang::line('content_html_create'),
            'width' => '100%',
            'height' => '100%',
            'close_btn' => Lang::line('close_btn'),
            'save_btn' => Lang::line('save_btn'),
            'html' => $this->view->renderPrint('panel', self::$viewPath . 'contentHtml/')
        );
        echo json_encode($response);
    }

    public function editContent($id = null) {
        if ($id == null) {
            $id = Input::post('id');
        }
        $this->view->content = $this->model->getContentHtmlForRender($id);
        $this->view->content['DEFAULT_PATH'] = self::$contentHtmlFilePath;
        $this->view->content['HTML'] = '';
            
        if ($this->view->content['PHYSICAL_PATH'] != null 
            && $this->view->content['FILE_EXTENSION'] == 'html' 
            && $this->contentFileExists($this->view->content['PHYSICAL_PATH'])) { 

            $this->view->content['HTML'] = file_get_contents($this->view->content['PHYSICAL_PATH'], FILE_USE_INCLUDE_PATH);
        } 

        $response = array(
            'Title' => Lang::line('content_html_edit'),
            'width' => '100%',
            'height' => '100%',
            'close_btn' => Lang::line('close_btn'),
            'save_btn' => Lang::line('save_btn'),
            'html' => $this->view->renderPrint('panel', self::$viewPath . 'contentHtml/')
        );
        echo json_encode($response); exit;
    }

    public function saveContent() {
        $response = $this->model->saveContentHtml();

        echo json_encode($response);
    }

    public function updateContent() {
        $result = $this->model->updateContentHtml();
        if ($result) {
            $response = array(
                'message' => Lang::line('msg_save_success'),
                'status' => 'success'
            );
        } else {
            $response = array(
                'message' => Lang::line('msg_error'),
                'status' => 'error'
            );
        }
        echo json_encode($response);
    }

    public function contentHtmlPopup() {
        $id = Input::post('id');
        $this->view->contentHtml = null;
        if ($id && strlen($id) > 0) {
            $contentData = $this->model->getContentHtmlForRender($id);

            if (isset($contentData['PHYSICAL_PATH'])) {
                if ($contentData['PHYSICAL_PATH'] != null && $this->contentFileExists($contentData['PHYSICAL_PATH'])) {
                    $this->view->contentHtml = file_get_contents($contentData['PHYSICAL_PATH'], FILE_USE_INCLUDE_PATH);
                } else {
                    $this->view->contentHtml = "";
                }
            } else {
                $this->view->contentHtml = "";
            }
        }
        $response = array(
            'Title' => Lang::line('content_html_view'),
            'width' => '100%',
            'height' => '100%',
            'close_btn' => Lang::line('close_btn'),
            'save_btn' => Lang::line('save_btn'),
            'contentHtml' => $this->view->contentHtml,
            'html' => $this->view->renderPrint('panel', self::$viewPath . 'contentHtml/')
        );
        echo json_encode($response);
    }

    public function contentHtmlRenderForView($id = null) {
        if ($id == null) {
            $id = Input::post('id');
        }

        $this->view->contentData = !is_null($id) ? $this->model->getContentHtmlForRender($id) : null;
        $this->view->contentUniqId = getUID();

        $this->view->html = '';

        if (isset($this->view->contentData['PHYSICAL_PATH']) && $this->view->contentData['PHYSICAL_PATH'] != null && $this->contentFileExists($this->view->contentData['PHYSICAL_PATH'])) {
            $this->view->html = file_get_contents($this->view->contentData['PHYSICAL_PATH'], FILE_USE_INCLUDE_PATH);
        }

        $this->view->render('renderForView', self::$viewPath . 'contentHtml/');
    }

    public function contentHtmlRender($contentId = null, $isJson = 1) {
        
        $this->load->model('mdcontentui', 'middleware/models/');
        
        if ($contentId == null) {
            $contentId = Input::post('id');
        }
        
        $this->view->contentId = $contentId;
        $this->view->uniqId = getUID();

        if (Input::getCheck('mmid')) {
            $metaDataId = Input::get('mmid');
            $this->view->isContentUi = $this->model->getContentUIModel($metaDataId);
        }

        $this->view->contentData = !is_null($this->view->contentId) ? $this->model->getContentHtmlForRender($this->view->contentId) : null;
        
        $this->view->contentData['DEFAULT_PATH'] = self::$contentHtmlFilePath;
        $this->view->contentUniqId = getUID();
        $this->view->contentHtmlName = Input::post('contentHtmlName');
        $this->view->srcDataViewId = Input::post('dataViewId');
        $this->view->srcRecordId = Input::post('srcRecordId');
        $this->view->fullPath = $this->view->contentData['PHYSICAL_PATH'];
        $this->view->fileExtension = strtolower(substr($this->view->fullPath, strrpos($this->view->fullPath, '.') + 1));

        $this->view->html = '';

        if (isset($this->view->contentData['PHYSICAL_PATH']) 
            && $this->view->contentData['FILE_EXTENSION'] == 'html' 
            && $this->contentFileExists($this->view->contentData['PHYSICAL_PATH'])) {
            
            $this->view->html = file_get_contents($this->view->contentData['PHYSICAL_PATH'], FILE_USE_INCLUDE_PATH);
        }

        $this->view->contentTitle = isset($this->view->contentData['FILE_NAME']) ? $this->view->contentData['FILE_NAME'] : '';

        if ($contentId == '1') {

            $this->view->processMetaDataId = Input::post('processMetaDataId');
            $this->view->processName = Input::post('processName');
            $this->view->contentData = $this->model->getContentDataModel($this->view->processMetaDataId);
            $this->view->isRender = true;

            if (Input::postCheck('renderHtml') && Input::isEmpty('renderHtml') !== false) {
                $this->view->isRender = false;
            }

            $response = array(
                'Title' => isset($this->view->contentData['FILE_NAME']) ? $this->view->contentData['FILE_NAME'] : '',
                'width' => '100%',
                'uniqid' => $this->view->uniqId,
                'height' => '100%',
                'close_btn' => Lang::line('close_btn'),
                'save_btn' => Lang::line('save_btn'),
                'html' => $this->view->renderPrint('renderKnowledge', self::$viewPath . 'contentHtml/'),
                'contentId' => $this->view->contentId,
            );
            echo json_encode($response); exit;
            
        } elseif (Input::postCheck('isWorkFlow')) {

            if ($this->view->fileExtension == 'pdf') {
                $this->view->render('pdf', self::$viewPath . 'viewer/');
            } else {
                $this->view->render('render', self::$viewPath . 'contentHtml/');
            }
            
        } elseif ($isJson == 1 && !isset($this->view->isContentUi)) {
            
            if ($this->view->fileExtension == 'pdf') {
                
                $renderHtml = $this->view->renderPrint('renderPdf', self::$viewPath . 'contentHtml/');
                
            } elseif ($this->view->fileExtension == 'mp4' 
                        || $this->view->fileExtension == 'ogg' 
                        || $this->view->fileExtension == 'avi' 
                        || $this->view->fileExtension == 'mov' 
                        || $this->view->fileExtension == 'm4p' 
                        || $this->view->fileExtension == 'm4v') {
                
                $renderHtml = $this->view->renderPrint('renderVideo', self::$viewPath . 'contentHtml/');
                
            } else {
                $renderHtml = $this->view->renderPrint('render', self::$viewPath . 'contentHtml/');
            }
            
            $response = array(
                'Title' => isset($this->view->contentData['FILE_NAME']) ? $this->view->contentData['FILE_NAME'] : '',
                'width' => '100%',
                'uniqid' => $this->view->uniqId,
                'height' => '100%',
                'close_btn' => Lang::line('close_btn'),
                'save_btn' => Lang::line('save_btn'),
                'html' => $renderHtml,
                'contentId' => $this->view->contentId,
            );
            echo json_encode($response); exit;
            
        } elseif (isset($this->view->isContentUi)) {

            $this->view->title = 'APP MENU';

            $this->view->css = array_unique(array_merge(array('global/css/megamenu/custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = AssetNew::metaOtherJs();

            $this->view->render('header');
            if ($this->view->fileExtension == 'pdf') {
                $this->view->render('renderPdf', self::$viewPath . 'contentHtml/');
            } else {
                $this->view->render('render', self::$viewPath . 'contentHtml/');
            }
            $this->view->render('footer');
            
        } else {
                        
            if ($this->view->fileExtension == 'pdf') {
                $this->view->render('renderPdf', self::$viewPath . 'contentHtml/');
            } else {
                $this->view->render('render', self::$viewPath . 'contentHtml/');
            }
        }
    }

    public function getContentHtmlList() {
        $this->view->contentName = Input::post('contentName');
        $this->view->contentHtmlList = $this->model->getContentHtmlListModel();

        $response = array(
            'Title' => $this->lang->line('Контентийн жагсаалт'),
            'Html' => $this->view->renderPrint('contentHtmlList', self::$viewPath . 'contentHtml/'), 
            'width' => '100%',
            'height' => '100%',
            'close_btn' => $this->lang->line('close_btn'),
            'save_btn' => $this->lang->line('save_btn')
        );
        echo json_encode($response); exit;
    }

    public function contentFileExists($filename) {

        if (file_exists($filename)) {
            return true;
        }
        return false;
    }

    /** CONTENT HTML END **************************************** */
    // <editor-fold defaultstate="collapsed" desc="ECM CONTENT">
    public function renderEcmContent($renderType = 1) {
        $this->view->renderUniqid = getUID();
        $this->view->renderType = $renderType;
        $this->view->render('render', self::$viewPath . 'ecmContent/');
    }

    public function getEcmContentModal() {
        $this->view->dUniqid = getUID();
        $this->view->dataViewId = Input::numeric('dataViewId');
        $response = array(
            'Title' => Lang::line('MET_99990111'),
            'width' => '100%',
            'height' => '100%',
            'close_btn' => Lang::line('close_btn'),
            'select_file_btn' => Lang::line('select_file_btn'),
            'html' => $this->view->renderPrint('index', self::$viewPath . 'ecmContent/')
        );
        echo json_encode($response);
    }

    public function createEcmContent() {
        $result = $this->model->createEcmContentModel();

        if ($result) {
            $response = array(
                'message' => Lang::line('msg_save_success'),
                'status' => 'success',
                'fileExtension' => $result,
            );
            echo json_encode($response);
        } else {
            $response = array(
                'message' => Lang::line('msg_error'),
                'status' => 'error'
            );
            echo json_encode($response);
        }
    }

    public function moveToFolder() {
        $result = $this->model->moveToFolderModel();

        if ($result) {
            $response = array(
                'message' => Lang::line('msg_save_success'),
                'status' => 'success',
            );
            echo json_encode($response);
        } else {
            $response = array(
                'message' => Lang::line('msg_error'),
                'status' => 'error'
            );
            echo json_encode($response);
        }
    }

    public function renderFolderAction($typeFolderAction = 0) {
        if (Input::postCheck('contentid')) {
            $contentId = Input::post('contentid');
        } else {
            $selectedRow = Input::post('selectedRow');
            $contentId = $selectedRow['id'];
        }
        $this->view->dUniqid = getUID();
        $this->view->typeFolderAction = $typeFolderAction;
        $this->view->contentId = $contentId;
        $this->view->dataViewId = Input::post('dataViewId');
        $this->view->render('actions', self::$viewPath . 'ecmContent/');
    }

    public function copyToFolder() {
        $result = $this->model->copyToFolderModel();
        echo json_encode($result);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="excel template">
    public function saveExcelTemplate() {
        $result = $this->model->saveExcelTemplateModel();
        if ($result) {
            $response = array(
                'message' => Lang::line('msg_save_success'),
                'status' => 'success',
            );
            echo json_encode($response);
        } else {
            $response = array(
                'message' => Lang::line('msg_error'),
                'status' => 'error'
            );
            echo json_encode($response);
        }
    }

    // </editor-fold>
    
    public function htmlEditor() {
        
        $recordId = Input::post('recordid');
        
        if ($recordId && $htmlContent = $this->model->getLastHtmlContentByRecordId($recordId)) {
            
            $this->view->recordId    = $recordId;
            $this->view->contentBody = $htmlContent;

            $response = array(
                'status' => 'success', 
                'title' => 'Editor',
                'html' => $this->view->renderPrint('editor', self::$viewPath . 'ecmContent/'), 
                'save_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
        } else {
            $response = array('status' => 'error', 'message' => 'Undefined record Id or Html content');
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function saveHtmlEditor() {
        $result = $this->model->saveHtmlEditorModel();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    public function ecmContentHtmlDiffViewer() {
        $result = $this->model->ecmContentHtmlDiffViewerModel();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    public function contentVisitorLog() {
        $result = $this->model->contentVisitorLogModel();
        echo $result;
    }
    
    public function contentDurationVisitorLog() {
        $result = $this->model->contentDurationVisitorLogModel();
        echo $result;
    }
    
}