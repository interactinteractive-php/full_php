<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mddoc Class 
 * 
 * @package     IA PHPframework
 * @subpackage  Middleware
 * @category    Document
 * @author  B.Och-Erdene <ocherdene@veritech.mn>
 * @link    http://www.interactive.mn/PHPframework/Middleware/Mddoc
 */

class Mddoc extends Controller {
    
    const viewPath = 'middleware/views/asset/';
    public static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $viewPath = 'middleware/views/document/';
    private static $uploadedFiles = array();
    public static $paramData =  array();
    public static $paramBookData =  array();
    public static $uploadedPath = 'process/';
    public static $erlStructureId = '1529014466094';
    public static $erlCmpServiceStrId = '1540202718478' ;
    public static $erlStructureIdCivil = '1532504449451647';
    public static $erlStructureIdCnt = '1532504449451647' ; /*1539681739648*/

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function contentOpener() {
        
        $selectedRow = Input::post('selectedRow');
        $contentId = Input::param($selectedRow['id']);
        
        $data = array(
            'LOCKED_USER_ID' => Ue::sessionUserKeyId(), 
            'LOCKED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
            'LOCKED_IP_ADDRESS' => '12', 
            'IS_LOCKED' => 1 
        );
        
        $result = $this->db->AutoExecute('ECM_CONTENT', $data, 'UPDATE', 'CONTENT_ID = '.$contentId);
        
        if ($result) {
            echo json_encode(array('status' => 'success', 'message' => 'success')); exit;
        }
    }
    
    public function contentUpload() {
        
        $selectedRow = Input::post('selectedRow');
        $contentId = Input::param($selectedRow['contentid']);
        $physicalPath = Input::param($selectedRow['physicalpath']);
        $filePath = explode('/', $physicalPath);
        $fileName = end($filePath);
        
        if (!$contentId) {
            echo json_encode(array('status' => 'error', 'message' => Lang::line('file_not_found')));
            die();
        }
        
        $data = array(
            'LOCKED_USER_ID' => null, 
            'LOCKED_DATE' => null, 
            'LOCKED_IP_ADDRESS' => null, 
            'IS_LOCKED' => 0 
        );
                
        $result = $this->db->AutoExecute('ECM_CONTENT', $data, 'UPDATE', 'CONTENT_ID = '.$contentId);
        
        if ($result) {
            
            $dir = dirname($physicalPath).'/';
                    
            if (isset($selectedRow['isversion'])) {
                $isVersion = Input::param($selectedRow['isversion']);
            
                if ($isVersion == '1') {

                    $fileNameExplodeArr = explode('.', $fileName);
                    $fileExtension = $fileNameExplodeArr[1];
                    $version = self::getLastVersion($contentId);
                    $newFileName = $fileNameExplodeArr[0].'_v'.$version.'.'.$fileExtension;

                    $versionPath = $dir.$newFileName;

                    copy($physicalPath, $versionPath);

                    $dataVersion = array(
                        'ID' => getUID(), 
                        'CONTENT_ID' => $contentId, 
                        'VERSION_NUMBER' => $version, 
                        'PHYSICAL_PATH' => $versionPath, 
                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                        'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                        'IP_ADDRESS' => '12'
                    );

                    $result = $this->db->AutoExecute('ECM_CONTENT_FILE_VERSION', $dataVersion);
                }
            }
            
            echo json_encode(array('status' => 'success', 'message' => 'success', 'fileName' => $fileName, 'dir' => $dir));
        }
    }
    
    public function docToPdfUpload() {
        
        $selectedRow = Input::post('selectedRow');
        $paramData = Input::post('paramData');
        
        if (issetParam($selectedRow['iscontentmap']) !== '1') {
            if(!isset($selectedRow['contentid']) && empty($selectedRow['contentid'])) {
                echo json_encode(array('status' => 'warning', 'message' => 'Контент ID хоосон байна!'));
                return;
            }
            
            $checkPdf = $this->model->getEcmContentByIdModel($selectedRow['contentid']);
            if ($checkPdf['FILE_EXTENSION'] === 'pdf') {
                echo json_encode(array('status' => 'warning', 'message' => 'PDF үүссэн байна!'));
                return;            
            }

            $contentId = Input::param($selectedRow['contentid']);
        } else {
            $contentId = getUID();
        }

        $physicalPath = Input::param($selectedRow['physicalpath']);
        $filePath = explode('/', $physicalPath);
        $fileName = end($filePath);
        
        $data = array(
            'LOCKED_USER_ID' => null, 
            'LOCKED_DATE' => null, 
            'LOCKED_IP_ADDRESS' => null, 
            'IS_LOCKED' => 0
        );
        
        $htmlFilePath = $this->model->bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
        $htmlFilePath .= 'pdf_file_'.$contentId.'2.pdf';
        
        if (file_put_contents($htmlFilePath, base64_decode(Input::post('encodeData')))) {
            $data['FILE_NAME'] = $htmlFilePath;   
            $data['PHYSICAL_PATH'] = $htmlFilePath;   
            $data['FILE_EXTENSION'] = 'pdf';   
            $data['FILE_SIZE'] = filesize($htmlFilePath);
            
            $where = "T0.RECORD_ID = '". $selectedRow['id'] ."'";
            $fileImg = $this->db->GetAll("SELECT 
                                                t0.RECORD_ID AS BOOK_ID,
                                                t1.PHYSICAL_PATH,
                                                LOWER(t1.FILE_EXTENSION) AS FILE_EXTENSION
                                            FROM ECM_CONTENT_MAP t0
                                            INNER JOIN ECM_CONTENT T1 ON T0.CONTENT_ID = T1.CONTENT_ID
                                            WHERE $where AND T1.IS_VERSION IS NULL ORDER BY t0.ORDER_NUM ");
            
            if ($fileImg) {
                $contentImg = '';
                (Array) $addPdfFiles = array();
                foreach ($fileImg as $key => $row) {
                    if ($row['FILE_EXTENSION'] === 'pdf') {
                        array_push($addPdfFiles, $row['PHYSICAL_PATH']);
                    } else {
                        $contentImg .= '<img src="'. Config::getFromCacheDefault('CONFIG_URL', null, '') . Config::getFromCache('ubegScanLink') .'?scan_id='. $selectedRow['id'] .'&filename='. $row['PHYSICAL_PATH'] .'&uid='. getUID() .'"><div class="pagebreak"></div>';
                    }
                }
                
                includeLib('PDF/merge/libmergepdf/vendor/autoload');
                $mergePdf = new \iio\libmergepdf\Merger(new iio\libmergepdf\Driver\TcpdiDriver);
                $mergePdf->addFile($htmlFilePath);
                
                if ($contentImg) {
                    $_POST['content'] = $contentImg;
                    $_POST['fDownload'] = '1';
                    
                    $pdfFile1 = $this->erlPdfPrint('return');
                    $mergePdf->addFile($pdfFile1);
                }
                                
                if ($addPdfFiles) {
                    foreach ($addPdfFiles as $pfile) {
                        $mergePdf->addFile($pfile);
                    }
                }
                
                $createdPdf = $mergePdf->merge();
                $tfilePath= $this->model->bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
                $tfilePath .= '/file_' . getUID() . '.pdf';
                file_put_contents($tfilePath, $createdPdf);
                $data['PHYSICAL_PATH'] = $tfilePath;
                
            }
            
        }
        
        $stream = fopen($htmlFilePath, "r");
        $contentD = fread ($stream, filesize($htmlFilePath));
        
        $pageCount = 0;
        // Regular Expressions found by Googling (all linked to SO answers):
        $regex  = "/\/Count\s+(\d+)/";
        $regex2 = "/\/Page\W*(\d+)/";
        $regex3 = "/\/N\s+(\d+)/";

        if (preg_match_all($regex, $contentD, $matches)) {
            $pageCount = max($matches);
        }
        
        $data['PAGE_COUNT'] = $pageCount[0];

        if (issetParam($selectedRow['iscontentmap']) === '1') {
            $data['CONTENT_ID'] = $contentId;
            $data['RELATED_ID'] = $selectedRow['id'];
            $data['IS_DEFAULT'] = '1';
            $this->db->AutoExecute('ECM_CONTENT', array('IS_DEFAULT' => '0'), 'UPDATE', "RELATED_ID='" . $selectedRow['id'] . "'");
            $result = $this->db->AutoExecute('ECM_CONTENT', $data);
        } else {
            $result = $this->db->AutoExecute('ECM_CONTENT', $data, 'UPDATE', 'CONTENT_ID = '.$contentId);
        }
        
        if ($result) {
            $dir = dirname($physicalPath).'/';
            $paramData = Arr::changeKeyLower($paramData);
            $data = array(
                'WFM_STATUS_ID' => issetDefaultVal($paramData['ntrwfmstatusid'], '1493712436457277'),
            );

            $this->db->AutoExecute(issetDefaultVal($paramData['tablename'], 'NTR_SERVICE_BOOK'), $data, 'UPDATE', 'ID = '.$selectedRow['id']);
            
            $data = array(  
                    'ID' => getUID(),
                    'REF_STRUCTURE_ID' => issetDefaultVal($paramData['reqstructureid'], '1496910341626'),
                    'RECORD_ID' => $selectedRow['id'],
                    'WFM_STATUS_ID' => issetDefaultVal($paramData['ntrwfmstatusid'], '1493712436457277'),
                    'WFM_DESCRIPTION' => issetDefaultVal($paramData['ntrwfmdescription'], 'Баталгаажуулсан'),
                    'CREATED_DATE' => Date::currentDate(),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId());
            
            $this->db->AutoExecute('META_WFM_LOG', $data);
            
            if (isset($selectedRow['isversion'])) {
                $isVersion = Input::param($selectedRow['isversion']);
            
                if ($isVersion == '1') {

                    $fileNameExplodeArr = explode('.', $fileName);
                    $fileExtension = $fileNameExplodeArr[1];
                    $version = self::getLastVersion($contentId);
                    $newFileName = $fileNameExplodeArr[0].'_v'.$version.'.'.$fileExtension;

                    $versionPath = $dir.$newFileName;

                    rename($physicalPath, $versionPath);

                    $dataVersion = array(
                        'ID' => getUID(), 
                        'CONTENT_ID' => $contentId, 
                        'VERSION_NUMBER' => $version, 
                        'PHYSICAL_PATH' => $versionPath, 
                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                        'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                        'IP_ADDRESS' => '12'
                    );

                    $result = $this->db->AutoExecute('ECM_CONTENT_FILE_VERSION', $dataVersion);
                }
            }
            
            if (issetParam($selectedRow['isconsul']) === '1' && issetParam($selectedRow['requestid']) !== '') {
                $this->db->AutoExecute('REQUEST', array("WFM_STATUS_ID" => issetDefaultVal($paramData['reqwfmstatusid'], '1636339749727630')), "UPDATE", " ID = " . $selectedRow['requestid']);
                $data = array(  
                    'ID' => getUID(),
                    'REF_STRUCTURE_ID' => issetDefaultVal($paramData['reqstructureid'], '1636339531993718'),
                    'RECORD_ID' => $selectedRow['requestid'],
                    'WFM_STATUS_ID' => issetDefaultVal($paramData['reqwfmstatusid'], '1636339749727630'),
                    'WFM_DESCRIPTION' => issetDefaultVal($paramData['reqwfmdescription'], 'Баталгаажуулсан'),
                    'CREATED_DATE' => Date::currentDate(),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId());
            
                $this->db->AutoExecute('META_WFM_LOG', $data);
            }
            
            echo json_encode(array('status' => 'success','status1' => 'success1', 'message' => 'success', 'fileName' => $fileName, 'dir' => $dir));
        }
    }
    
    public function getLastVersion($contentId) {
        return $this->db->GetOne("SELECT (COUNT(ID) + 1) AS LAST_VERSION FROM ECM_CONTENT_FILE_VERSION WHERE CONTENT_ID = $contentId");
    }
    
    public function toArchiveReport() {
        
        $this->view->isContentCode = Config::getFromCache('PF_REPORT_ARCHIVE_REF_CODE');
        $this->view->defaultName = Input::post('defaultName');
        
        if ($this->view->isContentCode) {
        
            $param = ['objectId' => $this->view->isContentCode];
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'CRM_AUTONUMBER_BP', $param);

            $this->view->contentCode = $this->ws->getValue($result['result']);
        }
        
        $response = [
            'html' => $this->view->renderPrint('toArchiveReport', self::$viewPath),
            'title' => 'Архив',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        ];
        convJson($response);
    }
    
    public function toArchiveSave() {
        
        includeLib('PDF/Pdf'); 
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $contentName = Input::post('contentName');
        $directoryId = Input::post('directoryId');
        $orientation = Input::post('orientation');
        $size        = Input::post('size');
        
        $site_url = defined('LOCAL_URL') ? LOCAL_URL : URL;
        $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", Input::postNonTags('content'));
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        $htmlContent = str_replace('  ', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);
        $htmlContent = str_replace(array('<nobr>', '</nobr>'), '', $htmlContent);
        
        preg_match_all('/([A-Za-zА-Яа-яӨҮөү0-9])(&nbsp;)([A-Za-zА-Яа-яӨҮөү0-9])/u', $htmlContent, $replaceMatches);
        
        if (isset($replaceMatches[0][0])) {
            foreach ($replaceMatches[0] as $replaceMatch) {
                $htmlContent = str_replace($replaceMatch, str_replace('&nbsp;', ' ', $replaceMatch), $htmlContent);
            }
        }
        
        $_POST['isSmartShrinking'] = '1';
        
        $fileToSave = UPLOADPATH.Mdwebservice::$uploadedPath.'file_'.getUID();
        
        $css = '<style type="text/css">';
        $css .= Mdtemplate::printCss('return');
        $css .= '</style>';
        
        $_POST['isIgnoreFooter'] = 1;
        
        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? $size : 'letter'));

        Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave);
        
        $fileToSave = $fileToSave.'.pdf';
        
        if (file_exists(BASEPATH.$fileToSave)) {
            
            $this->load->model('mddoc', 'middleware/models/');
            $response = $this->model->toArchiveSaveModel($contentName, $directoryId, $fileToSave);
                    
        } else {
            $response = array(
                'status' => 'error', 
                'message' => 'File write error'
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function toArchiveSaveStatement() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $contentName = Input::post('contentName');
        $directoryId = Input::post('directoryId');
        
        $orientation = Input::post('orientation');
        $size = Input::post('size');
        $width = Input::post('width');
        $height = Input::post('height');
        $top = Input::post('top');
        $left = Input::post('left');
        $right = Input::post('right');
        $bottom = Input::post('bottom');
        $fileId = Input::post('fileId');
        
        $_POST['fontFamily'] = 'arial, helvetica, sans-serif';
        
        if (Input::isEmpty('statementContent')) {
            $ml = &getInstance();
            $ml->load->model('mdstatement', 'middleware/models/');                    
            $htmlContent = $ml->model->readStatementHtmlFile($fileId);
        } else {
            $htmlContent = Input::postNonTags('statementContent');
        }        
        
        $site_url = URL;
        
        $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", $htmlContent);
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        $htmlContent = str_replace('font-size: 11px', 'font-size: 10.4px', $htmlContent);
        $htmlContent = str_replace("\xE2\x80\x8B", '', $htmlContent);
        
        $fileToSave = UPLOADPATH.Mdwebservice::$uploadedPath.'file_'.getUID();
        
        $css = '<style type="text/css">';
            $css .= Mdpreview::printCss('statementPdf');
        $css .= '</style>';        

        includeLib('PDF/Pdf');

        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? $size : 'A4'));

        Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave);
        
        if (file_exists(BASEPATH.$fileToSave.'.pdf')) {
            
            $this->load->model('mddoc', 'middleware/models/');
            $response = $this->model->toArchiveSaveModel($contentName, $directoryId, $fileToSave.'.pdf');
                    
        } else {
            $response = array('status' => 'error', 'message' => 'File write error');
        }
        
        echo json_encode($response); exit;
    }
    
    public function signInfoViewer() {
        
        $selectedRow = Input::post('selectedRow');
        $refStructureId = Input::post('refStructureId');
        $details = $_POST['details'];
        
        $this->view->signedDataList = $this->model->getSignInfoViewModel($details, $refStructureId, $selectedRow);
        
        $response = array(
            'html' => $this->view->renderPrint('signInfoViewer', self::$viewPath),
            'title' => 'Гарын үсэг шалгах',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function checkOcr() {
        
        $selectedRow = Input::post('selectedRow');
        
        $checkOcr = $this->model->isCheckOcrProcessModel();
        
        if ($checkOcr['status'] == 'success') {
            $response = $this->model->ocrProcessModel($selectedRow);
        } else {
            $response = $checkOcr;
        }
        
        echo json_encode($response); exit;
    }
    
    public function addBpTemplate() {
        
        $this->view->folderList = $this->model->getBPFolderModel();
        $this->view->sysKeywords = (new Mdstatement())->sysKeywords();
        
        $response = array(
            'html' => $this->view->renderPrint('bp_template/addBpTemplate', self::$viewPath),
            'title' => 'Темплэйт үүсгэх', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addBpTemplateParamList() {
        $this->load->model('mddoc', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $processId = Input::post('processMetaDataId');
        $this->view->paramList = $this->model->getBPInputParamsModel($processId);
        
        $this->view->render('bp_template/paramList', self::$viewPath);
    }
    
    public function getAllVariablesByJson() {
        $processId = Input::numeric('processId');
        
        $this->load->model('mdstatement', 'middleware/models/');
        
        $sysKeys = $this->model->getSysKeysModel();
        $variables = array();
        
        $this->load->model('mddoc', 'middleware/models/');
        $dataViewColumns = $this->model->getBPInputParamsModel($processId);

        if ($dataViewColumns) {
            $variables = $dataViewColumns;
            foreach ($sysKeys as $row) {
                array_push($variables, $row);
            }
        } else {
            $variables = $sysKeys;
        }
        
        header('Content-type: application/json');
        echo json_encode($variables); exit;
    }
    
    public function addBpTemplateSave() {
        $response = $this->model->addBpTemplateSaveModel();
        echo json_encode($response); exit;
    }
    
    public function editBpTemplate() {
        
        $selectedRow = Input::post('selectedRow');
        $id = $selectedRow['id'];
        
        $this->view->row = $this->model->getBPTemplateByIdModel($id);
        $this->view->widgets = $this->model->getBPTemplateWidgetByIdModel($id);
        $this->view->folderList = $this->model->getBPFolderModel();
        $this->view->paramList = $this->model->getBPInputParamsModel($this->view->row['META_DATA_ID']);
        $this->view->sysKeywords = (new Mdstatement())->sysKeywords();
        
        $response = array(
            'html' => $this->view->renderPrint('bp_template/editBpTemplate', self::$viewPath),
            'title' => 'Темплэйт засах', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function editBpTemplateSave() {
        $response = $this->model->editBpTemplateSaveModel();
        echo json_encode($response); exit;
    }
    
    public function copyBpTemplate() {
        
        $selectedRow = Input::post('selectedRow');
        $id = $selectedRow['id'];
        
        $this->view->row = $this->model->getBPTemplateByIdModel($id);
        
        $response = array(
            'html' => $this->view->renderPrint('bp_template/copyBpTemplate', self::$viewPath),
            'title' => 'Темплэйт хуулах', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function copyBpTemplateSave() {
        $response = $this->model->copyBpTemplateSaveModel();
        echo json_encode($response); exit;
    }
    
    public function checkOcrProcess() {
        $response = $this->model->checkOcrProcessModel();
        echo json_encode($response); exit;
    }
    
    public function contentOcrProcessByImage() {
        
        $selectedRow = Input::post('selectedRow');
        
        $this->view->processList = $_POST['processList'];
        $this->view->imagePath = null;
        $this->view->imageWidth = '';
        $this->view->imageHeigth = '';
        
        if (isset($selectedRow['physicalpath']) && file_exists($selectedRow['physicalpath'])) {
            
            $this->view->imagePath = $selectedRow['physicalpath'];
            
            ini_set('allow_url_fopen', 1);
            list($width, $height, $type, $attr) = getimagesize($this->view->imagePath);
            
            $this->view->imageWidth = $width;
            $this->view->imageHeigth = $height;
        }
        
        $response = array(
            'html' => $this->view->renderPrint('crop/cropper', self::$viewPath),
            'title' => 'OCR Process', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function ocrApi() {
        $response = $this->model->ocrApiProcessModel();
        echo json_encode($response); exit;
    }
    
    public function ocrBusinessProcessParams() {
        
        $processId = Input::post('processMetaDataId');
        $response = $this->model->getBPInputParamsModel($processId);
        
        header("Content-Type: application/json;charset=utf-8");
        echo json_encode($response); exit;
    }
    
    public function bpTemplateAttach($processId, $bpTemplateId, $refStructureId, $sourceId, $isEditMode) {
        $this->load->model('mddoc', 'middleware/models/');
        
        $response = $this->model->bpTemplateAttachModel($processId, $bpTemplateId, $refStructureId, $sourceId, $isEditMode);
        
        return $response;
    }
    
    public function addWordTemplate() {
        
        $this->view->folderList = $this->model->getBPFolderModel();
        $this->view->uniqId = getUID();
        $this->view->filterFolderId = array_change_key_case(json_decode(html_entity_decode(Input::post('addonJsonParam')), true), CASE_LOWER);
        $this->view->processId = Input::param(issetParam($_POST['paramData']['processId']));
        $this->view->serviceId = Input::param(issetParam($_POST['paramData']['serviceId']));
        
        if (isset($this->view->filterFolderId['folderid']))
            $this->view->filterFolderId = $this->view->filterFolderId['folderid'];
        else
            $this->view->filterFolderId = '';
        
        $response = array(
            'html' => $this->view->renderPrint('word_template/addWordTemplate', self::$viewPath),
            'title' => 'Темплэйт үүсгэх', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addWordTemplateSave() {
        $response = $this->model->addWordTemplateSaveModel();
        echo json_encode($response); exit;
    }   
    
    public function configTaxonamyTemplate() {
        
        $this->view->uniqId = getUID();
        
        $this->view->widgetConfigPath = array(
            array('id' => 'widget_party_a', 'text' => 'А тал', 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'widget_party_b', 'text' => 'Б тал', 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'widget_party_c', 'text' => 'В тал', 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'widget_party_d', 'text' => 'Г тал', 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'widget_bank', 'text' => Lang::line('META_00116'), 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'mainWidget', 'text' => 'Үйлчүүлэгч (Unitel)', 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'widget_participant', 'text' => 'Оролцогч', 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'widget_customer', 'text' => 'Үйлчлүүлэгч', 'function' => 'renderPartyPanel', 'ismain' => '1'), 
            array('id' => 'widget_realestate', 'text' => 'Үл хөдлөх хөрөнгө', 'function' => 'appendTaxonamyBodyByTag', 'ismain' => '0'), 
            array('id' => 'widget_organization', 'text' => 'Хуулийн этгээд', 'function' => 'appendTaxonamyBodyByTag', 'ismain' => '0'), 
            array('id' => 'widget_auto', 'text' => 'Тээврийн хэрэгсэл', 'function' => 'appendTaxonamyBodyByTag', 'ismain' => '0'), 
            array('id' => 'widget_firearm', 'text' => 'Галт зэвсэг', 'function' => 'appendTaxonamyBodyByTag', 'ismain' => '0'), 
            array('id' => 'widget_share', 'text' => 'Хувьцаа', 'function' => 'appendTaxonamyBodyByTag', 'ismain' => '0'), 
            array('id' => 'widget_asset', 'text' => 'Хөдлөх, эд хөрөнгө', 'function' => 'appendTaxonamyBodyByTag', 'ismain' => '0'),  
            array('id' => 'widget_none', 'text' => 'None', 'function' => 'appendTaxonamyBodyByTag', 'ismain' => '0'), 
            array('id' => 'widget_default', 'text' => 'Default', 'function' => 'appendTaxonamyBodyByTag', 'isactive' => '0')
        );
        
        $this->view->templateId = Input::numeric('templateId');
        $this->view->taxonomyList = $this->model->getTaxonomyListModel();
        $getTemplate = $this->model->getBusinessProcessTemplateByIdModel($this->view->templateId);
        $this->view->getTaxonomyConfig = $this->model->getTaxonomyConfigModel($this->view->templateId);
        $this->view->getTaxonomyWidget = $this->model->getTaxonomyWidgetExpModel($this->view->templateId);
        
        if (empty($getTemplate['META_DATA_ID'])) {
            echo json_encode(array('status' => 'error', 'message' => 'Процессоо сонгоно уу!'));
            exit;
        }
        
        $response = array(
            'status' => 'success',
            'html' => $this->view->renderPrint('word_template/configTaxonamyTemplate', self::$viewPath),
            'title' => 'Taxonamy тохируулах', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function checkXypNtr() {
        
        $this->view->uniqId = getUID();
        $sessionUserId = Ue::sessionUserId();
        
        $mduser = &getInstance();
        $mduser->load->model('mduser', 'middleware/models/');
        $this->view->operator = $mduser->model->gettokenDataByUserId();
        
        $response = array (
            'status' => 'success',
            'html' => $this->view->renderPrint('word_template/checkXypNtr', self::$viewPath),
            'title' => 'Мэдээлэл шалгах', 
            'uniqId' => $this->view->uniqId, 
            'operator' => $this->view->operator, 
            'save_btn' => Lang::line('save_btn'), 
            'check_btn' => Lang::line('Шалгах'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function configProcessWordTemplateSave() {
        $response = $this->model->configProcessWordTemplateSaveModel();
        echo json_encode($response); exit;
    }    
    
    public function configProcessUpdateWordTemplate() {
        
        $selectedRow = Input::post('selectedRow');
        $id = $selectedRow['templateid'];
        
        $this->view->row = $this->model->getBusinessProcessTemplateByIdWithItemModel($id);
        
        $response = array(
            'html' => $this->view->renderPrint('word_template/updateWordTemplate', self::$viewPath),
            'title' => 'Темплэйт засах', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function configUpdateWordTemplate() {
        $response = $this->model->updateWordTemplateModel();
        echo json_encode($response); exit;
    }
    
    public function configTaxonamySaveTemplate() {
        $response = $this->model->configTaxonamyTemplateSaveModel();
        echo json_encode($response); exit;
    }    
    
    public function configProcessBpTemplate() {
        
        $this->view->temlapteId = Input::post('templateId');
        $this->view->row = $this->model->getBusinessProcessTemplateByIdWithMetaModel($this->view->temlapteId);
        
        $response = array(
            'status' => 'success',
            'html' => $this->view->renderPrint('word_template/configProcessWordTemplate', self::$viewPath),
            'title' => 'Процесс тохируулах', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }     
    
    public function addWordTemplateCheckUpdate() {
        $response = $this->model->addWordTemplateCheckUpdateModel();
        echo json_encode($response); exit;
    }    
    
    public function addWordTemplateUpdate() {
        $response = $this->model->addWordTemplateUpdateModel();
        echo json_encode($response); exit;
    }    
    
    public function templatePreview() {
        try {

            $templateId = Input::post('templateId');
            $getTemplateProcess = $this->model->getBusinessProcessTemplateByIdModel($templateId);

            $contentFile = $getTemplateProcess['HTML_FILE_PATH'];
            $proccesId = $getTemplateProcess['META_DATA_ID'];

            if ($contentFile === null) {
                throw new Exception("Файл хоосон байна!"); 
            }

            if ($proccesId === null) {
                throw new Exception("Процесс хоосон байна!"); 
            }

            $htmlTemplate = file_get_contents($contentFile);
            $htmlTemplate = preg_replace("/<img[^>]+\>/i", "", $htmlTemplate);                

            $_POST['metaDataId'] = $proccesId;
            $_POST['isSystemMeta'] = 'false';
            $_POST['isDialog'] = false;
            $_POST['valuePackageId'] = '';
            $_POST['wordHtmlTemplate'] = $htmlTemplate;

            $mdWebserviceCtrl = new Mdwebservice();
            $mdWebserviceCtrl->callMethodByMeta();
            
        } catch (Exception $e) {
            echo json_encode(array('status' => 'warning', 'message' => $e->getMessage()));
        }
        
    }    
    
    public function ntrEntrustmentEditForm() {
        
        $this->view->uniqId = $this->view->methodId = getUID();
        $this->view->title = 'Эрх засах';
        
        $selectedRow = Arr::decode(Input::post('selectedRow'));
        $dataRow = $selectedRow['dataRow'];
        unset($_POST);
        
        $_POST = array (
                        'metaDataId' => $dataRow['editprocessid'],
                        'dmMetaDataId' => $dataRow['dmmetadataid'],
                        'oneSelectedRow' => array (
                                                'id' => $dataRow['id'],
                                                'bookid' => $dataRow['id'],
                                                'parentid' => $dataRow['parentid'],
                                                'bptemplateid' => $dataRow['bptemplateid'],
                                            ),
                        'bpTemplateId' => $dataRow['bptemplateid'],
                        'isEditNtrTrust' => '1',
                    );
        
        $this->load->model('mdwebservice', 'middleware/models/');
        
        $mdWebserviceCtrl = new Mdwebservice();
        $mdWebserviceCtrl->callMethodByMeta();
        
    }
    
    public function editWordTemplate() {
        
        $selectedRow = Input::post('selectedRow');
        $id = $selectedRow['templateid'];
        
        $this->view->row = $this->model->getBusinessProcessTemplateByIdWithItemModel($id);
        
        $response = array(
            'html' => $this->view->renderPrint('word_template/editWordTemplate', self::$viewPath),
            'title' => 'Темплэйт засах', 
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function editWordTemplateSave() {
        $response = $this->model->addWordTemplateSaveModel();
        echo json_encode($response);
    }    
    
    public function viewWordTemplate() {
        $selectedRow = Input::post('selectedRow');
        echo json_encode($this->model->viewWordTemplateModel($selectedRow));
    }  
    
    public function viewWordTemplatePdf() {
        $selectedRow = Input::post('selectedRow');
        echo json_encode($this->model->viewWordTemplatePdfModel($selectedRow));
    }  
    
    public function QRCodeSave() {  
        echo json_encode($this->model->QRCodeSaveModel());
    }  
    
    public function previewProcess() {

        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->title = 'Preview process';
        $this->view->isAjax = true;
        
        $this->view->css = array(
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/phpjs/phpjs.min.js'
        );
        
        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        }
        
        $this->view->converthtml = $this->model->getAllDataByPostData();
        $this->view->render('common/previewProcess', self::$viewPath);
        
        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('footer');
        }
    }
    
    public function confirmNtrServicePdf() {
        if (Input::post('bookDateCustome') && $id = Input::numeric('id')) {
            $this->db->AutoExecute('NTR_SERVICE_BOOK', array('BOOK_DATE' => Input::post('bookDateCustome')), 'UPDATE', "ID = " . $id);
        }
        $response = $this->model->confirmNtrServicePdfModel();
        echo json_encode($response);
    }
    
    public function docToPdfUploadConfirm() {
        $serviceBookId = Input::post('serviceBookId');
        $postData = Input::postData();
        $selectedRow = Input::post('selectedRow');

        if (Input::postCheck('confirmType') && !Input::isEmpty('confirmType') && Input::post('confirmType') === '2') {
            $getContent = $this->model->getContentContract($serviceBookId);
        } else {
            $getContent = $this->model->getContentServiceBook($serviceBookId);
        }
        
        if (!isset($getContent['MAIN_CONTENTID']) && empty($getContent['MAIN_CONTENTID'])) {
            echo json_encode(array('status' => 'warning', 'message' => 'Контент ID хоосон байна!'));
            return;
        }
        
        $checkPdf = $this->model->getEcmContentByIdModel($getContent['MAIN_CONTENTID']);
        
        if ($checkPdf['FILE_EXTENSION'] === 'pdf') {
            echo json_encode(array('status' => 'warning', 'message' => 'PDF үүссэн байна!'));
            return;            
        }
        
        $contentId = Input::param($getContent['MAIN_CONTENTID']);
        $physicalPath = Input::param($getContent['PHYSICAL_PATH']);
        $filePath = explode('/', $physicalPath);
        $fileName = end($filePath);
        
        $data = array(
            'LOCKED_USER_ID' => null, 
            'LOCKED_DATE' => null, 
            'LOCKED_IP_ADDRESS' => null, 
            'IS_LOCKED' => 0
        );
        
        $htmlFilePath = $this->model->bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/'); 
        $htmlFilePath .= 'pdf_file_'.$contentId.'.pdf';

        if (file_put_contents($htmlFilePath, base64_decode(Input::post('encodeData')))) {
            $data['FILE_NAME'] = $htmlFilePath;   
            $data['PHYSICAL_PATH'] = $htmlFilePath;   
            $data['FILE_EXTENSION'] = 'pdf';   
            $data['FILE_SIZE'] = filesize($htmlFilePath);
        }
                
        $result = $this->db->AutoExecute('ECM_CONTENT', $data, 'UPDATE', 'CONTENT_ID = '.$contentId);
        
        if ($result) {
            $dir = dirname($physicalPath).'/';
            if (Input::postCheck('confirmType') && !Input::isEmpty('confirmType') && Input::post('confirmType') === '2') {} else {
                
                $data = array(
                    'WFM_STATUS_ID' => issetDefaultVal($selectedRow['ntrwfmstatusid'], '1493712436457277'),
                );
                $this->db->AutoExecute('NTR_SERVICE_BOOK', $data, 'UPDATE', 'ID = '.$serviceBookId);
                
                $data = array(  
                        'ID' => getUID(),
                        'REF_STRUCTURE_ID' => '1496910341626',
                        'RECORD_ID' => $serviceBookId,
                        'WFM_STATUS_ID' => issetDefaultVal($selectedRow['ntrwfmstatusid'], '1493712436457277'),
                        'WFM_DESCRIPTION' => issetDefaultVal($selectedRow['ntrwfmdescription'], 'Баталгаажуулсан'),
                        'CREATED_DATE' => Date::currentDate(),
                        'CREATED_USER_ID' => Ue::sessionUserKeyId());
                
                $this->db->AutoExecute('META_WFM_LOG', $data);
            }    
            
            echo json_encode(array('status' => 'success', 'message' => 'success', 'fileName' => $fileName, 'dir' => $dir));
        }
    }
    
    public function bpAttachFiles($fillParamData, $serviceBookId, $ntrFileUniqId) {
        $this->load->model('mddoc', 'middleware/models/');
        $html = '';
        
        $fileAttach = $this->model->getServiceBookContentModel($serviceBookId);
        if ($fileAttach) {
            foreach ($fileAttach as $row) {
                $html .= '<div class="fileSidebarRows">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.mainRowCount][]" class="form-control form-control-sm longInit" placeholder="">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.id][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.id" value = "'. $row['ID'] .'">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.bookId][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.bookId" value = "'. $row['BOOK_ID'] .'">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.contentId][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.contentId" value = "'. $row['CONTENT_ID'] .'">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.id][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.id" value = "'. $row['ID'] .'">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileName][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileName" value = "'. $row['FILE_NAME'] .'">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileSize][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileSize" value = "'. $row['FILE_SIZE'] .'">'
                            . '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileExtension][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileExtension" value = "'. $row['FILE_EXTENSION'] .'">'
                            . '<span class="btn btn-xs btn-success fileinput-button mb5">' 
                                . '<span>Файл сонгох</span>'
                                . '<input type="file" data-path="NTR_CONTENT_DV.physicalPath" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.physicalPath][0][]" style="width:100%">'
                            . '</span>'
                            . '<span data-path="physicalPath" class="word-wrap-service" style="margin-left: 2px;" title="'. $row['FILE_NAME'] .'">'. $row['FILE_NAME'] .'</span>'
                            . '<input class="form-control" name="param[TNR_SERVICE_CONTENT_DV.description][0][]" type="text" style="max-width: 250px;" data-path="NTR_SERVICE_CONTENT_DV.description" placeholder="'.Lang::line('META_00007').'" value = "'. $row['DESCRIPTION'] .'">'
                            . '<a href="javascript:;" class="btn btn-xs btn-danger float-right ml5" title="'.Lang::line('META_00002').'" onclick="removeNotarityFile(this);"><i class="icon-cross2 font-size-12"></i></a>'
                            . '<a href="javascript:;" class="btn btn-xs btn-success float-right ml5" title="Сканнер" onclick="personNtrScanner_'. $ntrFileUniqId .'(this);">'
                                . '<i class="fa fa-print"></i>'
                            . '</a>'
                            . '<a href="javascript:;" class="btn btn-xs btn-success float-right" title="Вэбкамер" onclick="personWebNtrCamera_'. $ntrFileUniqId .'this);">'
                                . '<i class="fa fa-camera"></i>'
                            . '</a>'
                        . '</div>';
            }
        }
        return $html;
    }
    
    public function callNtrBusinessProcessTemplate() {
        $this->view->srcMetaDataId = Input::post('srcMetaDataId');
        $this->view->trgMetaDataId = Input::post('trgMetaDataId');
        $this->view->bpTemplateId = Input::post('bpTemplateId');
        $this->view->uniqId = Input::post('uniqId');
        $splitTrgMetaDataId = ($this->view->trgMetaDataId) ? implode(',', $this->view->trgMetaDataId) : '0';
        
        $this->load->model('mdwebservice', 'middleware/models/');
        $this->view->bpTemplateMapData = $this->model->getBpTemplateMapData($this->view->bpTemplateId, $splitTrgMetaDataId);
        
        $this->load->model('mddoc', 'middleware/models/');
        
        $response = array(
            'Html' => $this->view->renderPrint('word_template/bpTemplateForm', self::$viewPath),
            'Title' => 'Бусад загвар нэмэх',
            'choose_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn'),
            'Data' => count($this->view->bpTemplateMapData),
        );
        echo json_encode($response); exit;
    }
    
    public function getLookupParams() {
        $response = $paramCriteria = $array = $sRow = array();

        if (Input::postCheck('metaDataId')) {

            $metaDataId = Input::post('metaDataId');
            $getData = Input::postData();
            
            unset($getData['url']);
            unset($getData['metaDataId']);
            unset($getData['id']);
            $parent = Input::post('parentId') ? false : true;
            $isedit = Input::post('isedit') ? true : false;
            
            $this->load->model('mdobject', 'middleware/models/');
            
            $sRow['idField'] 	= $this->model->getStandartFieldModel($metaDataId, 'meta_value_id');
            $sRow['codeField'] 	= $this->model->getStandartFieldModel($metaDataId, 'meta_value_code');
            $sRow['nameField'] 	= $this->model->getStandartFieldModel($metaDataId, 'meta_value_name');
            $sRow['parentField'] = $this->model->getStandartFieldModel($metaDataId, 'parent_id');
            
            $param = array(
                'systemMetaGroupId' => $metaDataId,
                'showQuery' => 1
            );
            
            if (!$parent) {
                unset($getData[$sRow['idField']]);
            }
            
            $cacheName = 'bpLookupParams_';
            foreach ($getData as $key => $row) {
                if ($row) {
                    $cacheName .= $row.'_';
                    if ($key !== 'parentid') {
                        $paramCriteria[$key][] = !empty($row) ?
                            array(
                                'operator' => '=',
                                'operand' => $row
                            ) :
                            array(
                                'operator' => 'IS NULL',
                                'operand' => $row
                        );
                    }
                }
            }
            
            $param['criteria'] = $paramCriteria;
            
            if ($isedit) {

                (Array) $response = array();
                (Array) $temp = array();
                    
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
                $res = $this->db->GetAll($result['result']);
                
                $parentField = Str::upper($sRow['parentField']);
                $nameField = Str::upper($sRow['nameField']);
                $idField = Str::upper($sRow['idField']);
                
                foreach ($res as $key => $row) {
                    array_push($temp, array(
                                'text' => $row[$nameField],
                                'id' => $row[$idField],
                                'parentfield' => issetParam($row[$parentField]),
                                'icon' => 'fa fa-folder text-orange-400',
                                'state' => array(
                                    'selected' => issetParam($row['ISSELECTED']) !== '' ? true : false,
                                    'loaded' => true,
                                    'disabled' => false,
                                    'opened' => true
                                ),
                                'children' => array()));
                }
                
                $response = self::buildTree($temp);

            } else {
                
                $cache = phpFastCache();
                $response = $cache->get($cacheName . $metaDataId);

                if ($response == null) {
                    
                    (Array) $response = array();
                    (Array) $temp = array();

                    $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
                    $res 	= $this->db->GetAll($result['result']);
                    
                    
                    $parentField= Str::upper($sRow['parentField']);
                    $nameField 	= Str::upper($sRow['nameField']);
                    $idField 	= Str::upper($sRow['idField']);
                    
                    foreach ($res as $key => $row) {
                        array_push($temp, array(
                                    'text' => $row[$nameField],
                                    'id' => $row[$idField],
                                    'parentfield' => issetParam($row[$parentField]),
                                    'icon' => 'fa fa-folder text-orange-400',
                                    'state' => array(
                                        'selected' => false,
                                        'loaded' => true,
                                        'disabled' => false,
                                        'opened' => true
                                    ),
                                    'children' => array()));
                    }
                    
                    $response = self::buildTree($temp);
                    $cache->set($cacheName . $metaDataId, $response, Mdwebservice::$expressionCacheTime);
                    
                }
            }
        }
        
        echo jsonResponse($response);
    }
    
    function buildTree(array &$elements, $parentId = 0) {
        $branch = array();
    
        foreach ($elements as $element) {
            if ($element['parentfield'] == $parentId) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                
                array_push($branch, $element);
                unset($elements[$element['id']]);
            }
        }
        return $branch;
    }
    
    public function renderBpDocumentHtml() {
        $paramData = array();
        $postData = Input::postData();
        
        parse_str($postData['param'], $param);
        $mainParam = $param['param'];
        
        print_r($mainParam['NTR_CUSTOMER_A_DV.line1']);
        print_r($mainParam['NTR_CUSTOMER_A_DV.NTR_CUSTOMER_OTHER_DV.line1']);
        print_r($mainParam['NTR_CUSTOMER_A_DV.NTR_CUSTOMER_OTHER_DV.NTR_CUSTOMER_THIRD_DV.line1']);

        die;
        
        $index = 0;
        
        try {
            for( $index = 0; $index < 6; $index++) {
                $paramData[$index] = '';
                if (isset($mainParam['NTR_CUSTOMER_A_DV.line1'][$index])) {
                    foreach ($mainParam['NTR_CUSTOMER_A_DV.line1'][$index] as $fkey => $fRow) {
                        if ($fRow) {
                            $paramData[$index] = $fRow;

                            if (isset($mainParam['NTR_CUSTOMER_A_DV.NTR_CUSTOMER_OTHER_DV.line1'][$index])) {
                                foreach ($mainParam['NTR_CUSTOMER_A_DV.NTR_CUSTOMER_OTHER_DV.line1'][$index] as $skey => $sRow) {
                                    if (isset($sRow) && $sRow) {
                                        echo($index . ' = '. $sRow. '; <pre>');
                                        $paramData[$index] = $sRow;
                                        
                                        if (isset($mainParam['NTR_CUSTOMER_A_DV.NTR_CUSTOMER_OTHER_DV.NTR_CUSTOMER_THIRD_DV.line1'][$index])) {
                                            foreach ($mainParam['NTR_CUSTOMER_A_DV.NTR_CUSTOMER_OTHER_DV.NTR_CUSTOMER_THIRD_DV.line1'][$index] as $tkey => $tRow) {
                                                if (isset($tRow) && $tRow) {
                                                    foreach ($tRow as $tskey => $tsRow) {
                                                        if (isset($tsRow) && $tsRow) {
                                                            $paramData[$index] = $tsRow;
                                                            $index++;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $index++;
                                    }
                                }
                            }
                        }
                    }
                }
            };  
            
            print_r($paramData);
            print_r($index);
            
        } catch (Exception $ex) {
            print_r($ex);
        }

        die;
    }
    
    public function pregMatchAllInTaxonamy() {
        $taxonamyBody = Input::post('rowText');
        
        preg_match_all('/(\#)(.*?)(\#)/', $taxonamyBody, $parsePathRow);
        
        if (isset($parsePathRow[2])) {
            echo json_encode($parsePathRow[2]);
        } else {
            echo json_encode(array());
        }
    }
    
    public function runProcessSocialView() {
        
        $param = $paramFiles = array();
        if (isset($_FILES['physicalPath']['name'])) {
            foreach ($_FILES['physicalPath']['name'] as $key => $rows) {
                $paramFile['id'] = getUID();
                $paramFile['contentId'] = '';
                $paramFile['refStructureId'] = '';
                $paramFile['recordId'] = '';
                $paramFile['createdDate'] = '';
                $paramFile['createdUserId'] = '';
                $paramFile['NTR_CONTENT_DV'] = array();

                $newName = "file_" . $key . "_" . getUID();
                $ext = substr($_FILES['physicalPath']['name'][$key], strrpos($_FILES['physicalPath']['name'][$key], '.') + 1);
                $nname = $newName . "." . strtolower($ext);

                $file_name = $_FILES['physicalPath']['name'][$key];
                $file_size = $_FILES['physicalPath']['size'][$key];
                FileUpload::SetFileName($nname);
                FileUpload::SetTempName($_FILES['physicalPath']['tmp_name'][$key]);
                FileUpload::SetUploadDirectory(UPLOADPATH . self::$uploadedPath);
                FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));

                FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
                $uploadResult = FileUpload::UploadFile();

                if ($uploadResult) {

                    $paramFile['NTR_CONTENT_DV']['id'] = '';
                    $paramFile['NTR_CONTENT_DV']['fileSize'] = $file_size;
                    $paramFile['NTR_CONTENT_DV']['fileExtension'] = $ext;
                    $paramFile['NTR_CONTENT_DV']['fileName'] = $file_name;

                    $paramFile['NTR_CONTENT_DV']['physicalPath'] = UPLOADPATH . self::$uploadedPath . $nname;

                    array_push(self::$uploadedFiles, UPLOADPATH . self::$uploadedPath . $nname);
                    array_push($paramFiles, $paramFile);
                }
            }
        }
        
        $param = array(
            'id' => '',
            'refStructureId' => Input::post('refStructureId'),
            'recordId' => Input::post('recordId'),
            'commentText' => Input::post('commentText'),
            'createdUserId' => Ue::sessionUserKeyId(),
            'createdDate' => Date::currentDate(),
            'NTR_CONTENT_MAP_DV' =>  $paramFiles
        );

        includeLib('Utils/Functions');
        $result = Functions::runProcess('NTR_COMMENT_DV_001', $param);
        echo json_encode($result);
    }
    
    public function bpTaxonomyCacheClear() {
        
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        $templateId = Input::post('templateid');
        $metadataid = (Input::postCheck('metadataid')) ? Input::numeric('metadataid') : '1519803452017';

        $taxonamyConfig = glob($tmp_dir."/*/nt/ntTaxonamyConfig_".$templateId . "_*.txt");
        foreach ($taxonamyConfig as $taxonamyConfigRow) {
            @unlink($taxonamyConfigRow);
        }
        
        $templateWidgets = glob($tmp_dir."/*/nt/ntTemplateWidgetsById_".$templateId . "_*.txt");
        foreach ($templateWidgets as $templateWidgetsRow) {
            @unlink($templateWidgetsRow);
        }

        $templateWidgetsExp = glob($tmp_dir."/*/nt/ntTemplateWidgetsExpById_".$templateId . "_*.txt");
        foreach ($templateWidgetsExp as $templateWidgetsExpRow) {
            @unlink($templateWidgetsExpRow);
        }

        $taxonamyConfigTemp = glob($tmp_dir."/*/nt/ntTaxonamyConfigByTemplateId_".$templateId . "_*.txt");
        foreach ($taxonamyConfigTemp as $taxonamyConfigTempRow) {
            @unlink($taxonamyConfigTempRow);
        }

        $taxonamyScriptsEventKeyDtl = glob($tmp_dir."/*/nt/ntTaxonamyConfigByTag_*_".$templateId . ".txt");
        foreach ($taxonamyScriptsEventKeyDtl as $taxonamyScriptsEventKeyDtlRow) {
            @unlink($taxonamyScriptsEventKeyDtlRow);
        }

        $taxonamyScriptsEventKeyDtl = glob($tmp_dir."/*/nt/ntTaxonamyConfigByPath_*_*_" . $templateId . ".txt");
        foreach ($taxonamyScriptsEventKeyDtl as $taxonamyScriptsEventKeyDtlRow) {
            @unlink($taxonamyScriptsEventKeyDtlRow);
        }
        
        $taxonamyContent = glob($tmp_dir."/*/nt/ntTemplateTaxonamyContent_".$templateId . "_*.txt");
        foreach ($taxonamyContent as $taxonamyContentRow) {
            @unlink($taxonamyContentRow);
        }
        
        $taxonamyScriptsEvent = glob($tmp_dir."/*/nt/ntTaxonamyScriptsEvent_".$templateId . "_*.txt");
        foreach ($taxonamyScriptsEvent as $taxonamyScriptsEventRow) {
            @unlink($taxonamyScriptsEventRow);
        }
        
        $taxonamyScriptsEventDtl = glob($tmp_dir."/*/nt/ntTaxonamyScriptsEventDtl_".$templateId . "_*.txt");
        foreach ($taxonamyScriptsEventDtl as $taxonamyScriptsEventDtlRow) {
            @unlink($taxonamyScriptsEventDtlRow);
        }
        
        $taxonamyScriptsEventKeyDtl = glob($tmp_dir."/*/nt/ntTaxonamyScriptsEventKeyDtl_".$templateId . "_*.txt");
        foreach ($taxonamyScriptsEventKeyDtl as $taxonamyScriptsEventKeyDtlRow) {
            @unlink($taxonamyScriptsEventKeyDtlRow);
        }

        //$taxonamyConfig = glob($tmp_dir."/*/nt/*");
        /* foreach ($taxonamyConfig as $taxonamyConfigRow) {
            @unlink($taxonamyConfigRow);
        } */
        
        $response = array(
            'status' => 'success',
            'message' => 'Амжилттай цэвэрлэгдлээ'
        );
        echo json_encode($response); exit;
    }
    
    public function callCacheRenderMethod() {
        
        $methodId = Input::post('methodId');
        
        $cache = phpFastCache();
        $html = $cache->get('bpRenderMethodTemplate_'. $methodId);

        if ($html == null) {
            $html = '';
        }
        
        $response = array(
            'Html' => html_entity_decode($html, ENT_QUOTES, 'UTF-8'),
        );
        echo json_encode($response); exit;
    }
    
    public function cacheRenderMethod() {
        
        $methodId = Input::post('methodId');
        
        $cache = phpFastCache();
        $html = $cache->get('bpRenderMethodTemplate_'. $methodId);

        if ($html == null) {
            $html = Input::post('dataHtml');
            
            $cache->set('bpRenderMethodTemplate_'. $methodId, $html, Mdwebservice::$expressionCacheTime);
        }
    }
    
    public function electronRegisterLegal() {
        
        $this->view->row = Input::post('selectedRow');
        $this->view->id = $this->view->row['id'];
        $this->view->name = Str::upper($this->view->row['companyregiternumber'].' - '.$this->view->row['companyname']);
        $this->view->uniqid = getUID();
        
        $this->load->model('mddoc', 'middleware/models/');
        
        $this->view->metaDataId = '1528858041095420';
        $this->view->metaDataCode = 'ersEntityRegisterList';
        $this->view->hiddenInputs = '';
        $postData = Input::postData();
        $this->view->ishrm = issetParam($postData['paramData']['ishrm']);
        $this->view->isnotary = checkDefaultVal($postData['paramData']['isNotary'], '0');

        if (Input::postCheck('paramData')) {
            
            self::$paramData = $_POST['paramData'];
        
            if (isset(self::$paramData['refstructureid']) && self::$paramData['refstructureid'] != '') {

                self::$erlStructureId = self::$paramData['refstructureid'];
                $this->view->metaDataId = self::$paramData['dataViewId'];
                $this->view->metaDataCode = self::$paramData['dataViewCode'];
            } 
            
            if (isset(self::$paramData['nextwfmstatusid']) && self::$paramData['nextwfmstatusid'] != '') {
                
                $this->view->hiddenInputs = Form::hidden(array('name' => 'dataViewId', 'value' => self::$paramData['dataViewId']));
                $this->view->hiddenInputs .= Form::hidden(array('name' => 'nextWfmStatusId', 'value' => self::$paramData['nextwfmstatusid']));
                $this->view->hiddenInputs .= Form::hidden(array('name' => 'currentWfmStatusId', 'value' => $this->view->row['wfmstatusid']));
            }
        }
        
        if (isset(self::$paramData['saveprocesscode']) && self::$paramData['saveprocesscode'] != '') {
            $this->view->saveProcessCode = self::$paramData['saveprocesscode'];
        } else {
            $this->view->saveProcessCode = 'ERS_SERVICE_COMPANY_BOOK_META_DV_001';
        }
                
        $this->view->refStructureId = self::$erlStructureId;
        $this->view->rowJson = json_encode($this->view->row);
        $htmlPath = 'erl/mainForm';
        
        if (issetParam($postData['paramData']['ishrm']) === '1') {
            $this->view->id = $this->view->row['recordid'];
            $getFileData = $this->model->getErkContentMapModel_HRM($this->view->id);
        } elseif ($this->view->isnotary === '1') {
            $htmlPath = 'notary/mainForm';
            $getFileData = $this->model->getErkContentMapModel_NOTARY($this->view->id);
        } else {
            $getFileData = $this->model->getErkContentMapModel_V2($this->view->id);
        }
        
        $this->view->fileList = $getFileData['SS'];
        $this->view->fileCount = $getFileData['ROW_COUNT'];
        $this->view->fileRender = $this->view->renderPrint('erl/fileList_v2', self::$viewPath);
        
        $response = array(
            'html' => $this->view->renderPrint($htmlPath, self::$viewPath),
            'title' => 'Мета оруулах', 
            'uniqId' => $this->view->uniqid, 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function electronViewLegal() {
        
        if (Input::isEmpty('isWorkFlow') == false) {
            
            $decodedRow = Arr::decode(Input::post('selectedRow'));
            
            $_POST['paramData']['dataViewId'] = $decodedRow['dataViewId'];
            $this->view->row = $decodedRow['dataRow'];
            
            $isReturnJson = false;
            
            $this->view->isMainWindow = true;
            
        } else {
            $this->view->row = Input::post('selectedRow');
            $isReturnJson = true;
        }
        
        $this->view->isverifier = issetParamZero($this->view->row['isverifier']);
        $this->view->isworkflow = Input::post('isWorkFlow');
        $postData = Input::postData();
        $this->view->ishrm = issetParam($postData['paramData']['ishrm']);
        
        $this->view->type = (Input::postCheck('type')) ? Input::post('type') : '1';
        $this->view->uniqid = getUID();
        $this->view->hideFooter = false;
        $this->view->postData = Input::postData();
        $this->view->treeDvId = issetParam($this->view->postData['paramData']['treeDvId']);
        
        includeLib('Compress/Compression');
        $this->view->postDatac = Compression::encode_string_array($this->view->postData);
        
        if (isset($this->view->row['id']) && $this->view->row['id']) {
            
            $this->view->id = $this->view->row['id'];
            $this->view->rowJson = json_encode($this->view->row);
            
            switch ($this->view->type) {
                case '1':
                    $this->view->metaDataCode = 'ersEntityRegisterList';
                    $this->view->metaDataId = '1528858041095420';
                    $this->view->refStructureId = self::$erlStructureId;
                    
                    if (Config::getFromCache('previewSaveLog') === '1') { /* (!Config::getFromCache('isNotaryServer')  && Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { */ 
                        self::saveElecLog($this->view->row, self::$erlStructureId, $this->view->postData);
                    }
                    
                    break;
                case '2':
                    $this->view->metaDataCode = 'cvlCivilPackList';
                    $this->view->metaDataId = '1533714393827725';
                    $this->view->refStructureId = self::$erlStructureIdCivil;
                    break;
                case '4':
                    $this->view->metaDataCode = 'cvlCivilControlList';
                    $this->view->metaDataId = '1536131133813';
                    $this->view->refStructureId = self::$erlStructureIdCnt;
                    break;
            }
                
            self::$erlStructureId = $this->view->refStructureId;
            
            if (Input::postCheck('paramData')) {
                
                self::$paramData = $_POST['paramData'];

                if (isset(self::$paramData['refstructureid']) && self::$paramData['refstructureid'] != '') {

                    self::$erlStructureId = self::$paramData['refstructureid'];
                    $this->view->refStructureId = self::$erlStructureId;
                    $this->view->metaDataId = self::$paramData['dataViewId'];
                    $this->view->metaDataCode = issetParam(self::$paramData['dataViewCode']);
                } 
                
                if (isset(self::$paramData['treedataviewid']) && self::$paramData['treedataviewid'] != '') {
                    
                    $this->view->treeDataViewId = self::$paramData['treedataviewid'];
                    
                    if (isset(self::$paramData['dvInputMapping']) && self::$paramData['dvInputMapping'] != '') {
                        
                        $dvInputMapping = self::$paramData['dvInputMapping'];//registerdate@registerdate
                        $dvInputMappingArr = explode('|', $dvInputMapping);
                        
                        $this->view->treeInputParams = '';
                        
                        foreach ($dvInputMappingArr as $dvInputMappingRow) {
                            
                            $dvInputMappingList = explode('@', $dvInputMappingRow);
                            $dvPath = $dvInputMappingList[0];
                            $inputPath = $dvInputMappingList[1];
                            
                            if (isset($this->view->row[$dvPath])) {
                                $this->view->treeInputParams .= $inputPath.'='.$this->view->row[$dvPath].'&';
                            }
                        }
                    }
                }
                
                if (isset(self::$paramData['ignoreWorkFlow']) && self::$paramData['ignoreWorkFlow'] == '1') {
                    $this->view->ignoreWorkFlow = true;
                }
                
                if (isset(self::$paramData['hideFooter']) && self::$paramData['hideFooter'] == '1') {
                    $this->view->hideFooter = true;
                }
            }
        
            $this->load->model('mddoc', 'middleware/models/');       
            $this->view->fileCount = $this->model->getErlContentCountModel($this->view->row['id']);  
            
            if ($this->view->type == '2' || $this->view->type == '4') {
                $mailHtml = 'civil/mainView';
                
                if (isset(self::$paramData['isCivilRegister'])) {
                    $this->view->isCivilRegister = true;
                }
                
            } else {
                $mailHtml = 'erl/mainView';
            }
            
            $response = array(
                'html' => $this->view->renderPrint($mailHtml, self::$viewPath),
                'title' => 'Хяналт баталгаажуулалт',
                'uniqId' => $this->view->uniqid,
                'close_btn' => $this->lang->line('close_btn'),
                'treeDvId' => $this->view->treeDvId,
            );
            
        } else {
            $message = '<strong>PACK_ID</strong> олдсонгүй';
            $response = array(
                'status' => 'error',
                'message' => $message,
                'html' => $message,
            );
        }
        
        if ($isReturnJson) {
            echo json_encode($response); exit;
        } else {
            echo $response['html']; exit;
        }
    }
    
    public function electronEditLegal() {
        
        $postData = Input::postData();
        
        $this->view->isShowBtn = (isset($postData['paramData']['isShowBtn']) && $postData['paramData']['isShowBtn'] == '0') ? '0' : '1';
        $this->view->isShowPrintBtn = (isset($postData['paramData']['isShowPrintBtn']) && $postData['paramData']['isShowPrintBtn'] == '0') ? '0' : '1';
        $this->view->isEdit = (isset($postData['isEdit']) && $postData['isEdit'] == '0') ? '0' : '1';
        
        includeLib('Compress/Compression');
        $this->view->postDatac = Compression::encode_string_array($postData);
        
        if (Input::isEmpty('isWorkFlow') == false) {
            
            $decodedRow = Arr::decode(Input::post('selectedRow'));
            
            $_POST['paramData']['dataViewId'] = $decodedRow['dataViewId'];
            $this->view->row = $decodedRow['dataRow'];
            
            $isReturnJson = false;
            
            $this->view->isMainWindow = true;
            
        } else {
            $this->view->row = Input::post('selectedRow');
            $isReturnJson = true;
        }
        
        $this->view->type = (Input::postCheck('type')) ? Input::post('type') : '6';
        $this->view->uniqid = getUID();
        
        if (isset($this->view->row['id']) && $this->view->row['id']) {
            
            if ($this->view->type === '7' || $this->view->type === '6') {
                $this->model->saveCvlViewLogModel($this->view->row);
            }
            
            $this->view->id = $this->view->row['id'];
            $this->view->rowJson = json_encode($this->view->row);
            
            $this->view->refStructureId = self::$erlStructureIdCnt;
                
            if (Input::postCheck('paramData')) {
                
                self::$paramData = $_POST['paramData'];
                $this->view->metaDataCode = self::$paramData['dataViewCode'];
                $this->view->metaDataId = self::$paramData['dataViewId'];   
                
                if (isset(self::$paramData['treedataviewid']) && self::$paramData['treedataviewid'] != '') {
                    
                    $this->view->treeDataViewId = self::$paramData['treedataviewid'];
                    
                    if (isset(self::$paramData['dvInputMapping']) && self::$paramData['dvInputMapping'] != '') {
                        
                        $dvInputMapping = self::$paramData['dvInputMapping'];//registerdate@registerdate
                        $dvInputMappingArr = explode('|', $dvInputMapping);
                        
                        $this->view->treeInputParams = '';
                        
                        foreach ($dvInputMappingArr as $dvInputMappingRow) {
                            
                            $dvInputMappingList = explode('@', $dvInputMappingRow);
                            $dvPath = $dvInputMappingList[0];
                            $inputPath = $dvInputMappingList[1];
                            
                            if (isset($this->view->row[$dvPath])) {
                                $this->view->treeInputParams .= $inputPath.'='.$this->view->row[$dvPath].'&';
                            }
                        }
                    }
                }
                
                if (isset(self::$paramData['ignoreWorkFlow']) && self::$paramData['ignoreWorkFlow'] == '1') {
                    $this->view->ignoreWorkFlow = true;
                }
            }
        
            $this->load->model('mddoc', 'middleware/models/');       
            $this->view->fileCount = $this->model->getErlContentCountModel($this->view->row['id'], self::$erlStructureIdCivil);
            
            $mailHtml = 'control/mainView'; 
            
            $response = array(
                'html' => $this->view->renderPrint($mailHtml, self::$viewPath),
                'title' => 'Мета дата засах',
                'uniqId' => $this->view->uniqid,
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $message = '<strong>PACK_ID</strong> олдсонгүй';
            $response = array(
                'status' => 'error',
                'message' => $message,
                'html' => $message,
            );
        }
        
        if ($isReturnJson) {
            echo json_encode($response); exit;
        } else {
            echo $response['html']; exit;
        }
    }
    
    public function electronCvlViewLegal() {
        
        $this->view->row = Input::post('selectedRow');
        $this->view->type = '2';
        $this->view->uniqid = getUID();
        
        if (isset($this->view->row['id']) && $this->view->row['id']) {
            $this->view->id = $this->view->row['id'];
            $this->view->rowJson = json_encode($this->view->row);
        
            $this->view->metaDataId = '1533714393827725';
            $this->view->metaDataCode = 'cvlCivilPackList';
            $this->view->refStructureId = self::$erlStructureIdCivil;        
        
            $this->load->model('mddoc', 'middleware/models/');       
            $this->view->fileCount = $this->model->getErlContentCountModel($this->view->row['id']);  

            $response = array(
                'html' => $this->view->renderPrint('civil/mainViewCnt', self::$viewPath),
                'title' => 'Хяналт баталгаажуулалт',
                'uniqId' => $this->view->uniqid,
                'close_btn' => Lang::line('close_btn')
            );
            
        } else {
            $response = array(
                'status' => 'error',
                'message' => '<strong>PACK_ID</strong> олдсонгүй',
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function electronViewTreeList() {
        $fileList = $this->model->getTreeListModel();
        echo json_encode($fileList); exit;
    }
    
    public function electronViewTreeDataV2() {
        $fileList = $this->model->getTreeListV2Model();
        echo json_encode($fileList); exit;
    }
    
    public function electronViewTreeDataV3() {
        /* $fileList = $this->model->electronViewTreeDataV2Model(Input::post('parentId'), Input::post('companyId')); */
        $fileList = $this->model->getTreeListV3Model();
        echo json_encode($fileList); exit;
    }
    
    public function electronRegisterLegalProcess() {
        
        $contentId = Input::post('contentId');
        $this->view->fillData = $this->model->getErlSavedDataModel($contentId);
        $this->view->processId = '1529014412387';
        
        $this->load->model('mdwebservice', 'middleware/models/');
        $this->view->params = $this->model->groupParamsDataModel($this->view->processId, null, ' AND PAL.PARENT_ID IS NULL');

        $this->view->render('erl/process', self::$viewPath);
    }
    
    public function electronRegisterLegalSave() {
        
        $this->view->id = Input::post('recordId');
        $response = $this->model->electronRegisterLegalSaveModel();
        
        if (Input::isEmpty('refStructureId') == false) {
            self::$erlStructureId = Input::post('refStructureId');
        }
        
        if (Input::post('saveProcessCode') === 'ELEC_META_BOOK_DV_001') {
            $getFileData = $this->model->getErkContentMapModel_HRM($this->view->id);
        } elseif (Input::post('saveProcessCode') === 'NTR_SERVICE_CONTENT_MAP_DV_001') {
            $getFileData = $this->model->getErkContentMapModel_NOTARY($this->view->id);
        } else {
            $getFileData = $this->model->getErkContentMapModel_V2($this->view->id);
        }

        $response['fileRender'] = html_entity_decode($getFileData['SS'], ENT_QUOTES, 'UTF-8');

        echo json_encode($response); exit;
    }
    
    public function elcRegisterBookLegalSave() {
        $response = $this->model->elcRegisterBookLegalSaveModel();
        
        $this->view->id = Input::post('recordId');
        $this->view->companyKeyId = Input::post('companyKeyId');
        
        if (Input::isEmpty('refStructureId') == false) {
            self::$erlStructureId = Input::post('refStructureId');
        }
        
        $this->view->refStructureId = self::$erlCmpServiceStrId;
        $getFileData = $this->model->getErkContentMapModel_V5($this->view->id, $this->view->refStructureId, $this->view->companyKeyId);
        
        $response['fileRender'] = html_entity_decode($getFileData['SS'], ENT_QUOTES, 'UTF-8');

        echo json_encode($response); exit;
    }
    
    public function electronRegisterLegalSaveCvl() {
        $response = $this->model->electronRegisterLegalCvlSaveModel();
        
        $this->view->id = Input::post('recordId');
        
        if (Input::isEmpty('refStructureId') == false) {
            self::$erlStructureId = Input::post('refStructureId');
        }
        
        $getFileData = $this->model->getErkContentMapModel_V2($this->view->id);
        $this->view->fileList = $getFileData['SS'];
        $this->view->fileCount = $getFileData['ROW_COUNT'];
             
        $response['fileRender'] = $this->view->renderPrint('erl/fileList_v2', self::$viewPath);
        
        echo json_encode($response); exit;
    }
    
    public function electronRegisterLegalBulkScan() {        
        
        $recordId = Input::post('recordId');
        $selectedRow = array();
        
        if (Input::postCheck('paramData')) {
            self::$paramData = $_POST['paramData'];
        
            if (isset(self::$paramData['refstructureid']) && self::$paramData['refstructureid'] != '') {
                Mddoc::$erlStructureId = self::$paramData['refstructureid'];
            }
            
            $selectedRow = Input::post('selectedRow');
        }
        
        $response = $this->model->electronRegisterLegalBulkScanModel($recordId, $selectedRow);
        
        if (Input::postCheck('notFilesRender') == false) {
            $this->view->id = $recordId;
            
            $getFileData = $this->model->getErkContentMapModel_V2($recordId);
            $this->view->fileList = $getFileData['SS'];
            $this->view->fileCount = $getFileData['ROW_COUNT'];
        
            $response['fileRender'] = $this->view->renderPrint('erl/fileList_v2', self::$viewPath);
            $response['fileCount'] = $this->view->fileCount;
        }
        
        echo json_encode($response); exit;
    }
    
    public function electronRegisterLegalBulkReScan() {
        
        $recordId = Input::post('recordId');
        $selectedRow = array();
        
        if (Input::postCheck('paramData')) {
            
            self::$paramData = $_POST['paramData'];

            if (isset(self::$paramData['refstructureid']) && self::$paramData['refstructureid'] != '') {
                self::$erlStructureId = self::$paramData['refstructureid'];
            }
            
            $selectedRow = Input::post('selectedRow');
        }
        
        $response = $this->model->electronRegisterLegalBulkReScanModel($recordId, $selectedRow);
        
        if (Input::postCheck('notFilesRender') == false) {
            $this->view->id = $recordId;
            
            $getFileData = $this->model->getErkContentMapModel_V2($recordId);
            $this->view->fileList = $getFileData['SS'];
            $this->view->fileCount = $getFileData['ROW_COUNT'];
            
            $response['fileRender'] = $this->view->renderPrint('erl/fileList_v2', self::$viewPath);
            $response['fileCount'] = $this->view->fileCount;
        }
        
        echo json_encode($response); exit;
    }
    
    public function randomFieldValue() {
        $length = (Input::postCheck('length')) ? Input::post('length') : '8';
        $password = randomPassword($length);
        echo json_encode(array('encrypted' => Hash::create('sha256', $password), 'decrypted' => $password));
    }
    
    public function passwordFieldValue() {
        $password = Input::post('value');
        echo json_encode(array('encrypted' => Hash::createMD5reverse($password), 'decrypted' => $password));
    }
    
    public function callDvData() {
        $inputMetaDataId = Input::post('inputMetaDataId');
        $param = array(
            'systemMetaGroupId' => $inputMetaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );
        
        $cache = phpFastCache();
        
        $array = $cache->get('dvErlData_' . $inputMetaDataId);
        
        if ($array == null) {

            $result = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

            $array = array();

            if ($result['status'] == 'success' && isset($result['result'])) {

                $this->load->model('mdobject', 'middleware/models/');
                $idField = strtolower($this->model->getDataViewMetaValueId($inputMetaDataId));
                $codeField = strtolower($this->model->getDataViewMetaValueCode($inputMetaDataId));
                $nameField = strtolower($this->model->getDataViewMetaValueName($inputMetaDataId));

                unset($result['result']['aggregatecolumns']);
                unset($result['result']['paging']);

                foreach ($result['result'] as $k => $v) {
                    $array[$k]['META_VALUE_ID'] = $v[$idField];
                    $array[$k]['META_VALUE_CODE'] = $v[$codeField];
                    $array[$k]['META_VALUE_NAME'] = $v[$nameField];
                    $array[$k]['ROW_DATA'] = $v;
                }
                
                $cache->set('dvErlData_' . $inputMetaDataId, $array, Mdwebservice::$expressionCacheTime);
            }
        }
        
        echo json_encode(array('data' => $array));
    }
    
    public function electronRegisterCnt() {
        
        $this->view->row = Input::post('selectedRow');
        $this->view->id = $this->view->row['id'];
        $this->view->civilId = $this->view->row['civilid'];
        $this->view->typeId = Input::post('type');
        
        $postData = Input::postData();
        
        $this->view->paramData = $postData['paramData'];
        $this->view->isShowBtn = (isset($this->view->paramData['isShowBtn']) && $this->view->paramData['isShowBtn'] == '0') ? '0' : '1';
        $this->view->isEdit = (isset($this->view->paramData['isEdit']) && $this->view->paramData['isEdit'] == '0') ? '0' : '1';
        
        if (isset($this->view->row['id']) && $this->view->row['id']) {
            $this->view->name = Str::upper($this->view->row['stateregnumber'].' - '.$this->view->row['name']);
            $this->view->uniqid = $this->view->id;

            $this->load->model('mddoc', 'middleware/models/');
            
            $fileData = $this->model->getErkContentMapModel_V3($this->view->row['id'], self::$erlStructureIdCnt);
            $this->view->fileList = $fileData['SS'];
            $this->view->fileCount = $fileData['ROW_COUNT'];
            
            $this->view->fileRender = $this->view->renderPrint('civil/fileList', self::$viewPath);

            $this->view->metaDataId = '1536131133813';
            $this->view->metaDataCode = 'cvlCivilControlList';
            $this->view->refStructureId = self::$erlStructureIdCnt;
            $this->view->rowJson = json_encode($this->view->row);

            $response = array(
                'html' => $this->view->renderPrint('control/mainForm', self::$viewPath),
                'title' => 'Мета оруулах',
                'status' => 'success',
                'uniqId' => $this->view->uniqid,
                'close_btn' => Lang::line('close_btn')
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => '<strong>PACK_ID</strong> олдсонгүй',
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function electronRegisterLegalCivil() {
        
        $this->view->row = Input::post('selectedRow');
        $this->view->id = $this->view->row['id'];
        $this->view->civilId = $this->view->row['civilid'];
        $this->view->typeId = Input::post('type');
        
        if (isset($this->view->row['id']) && $this->view->row['id']) {
            $this->view->name = Str::upper($this->view->row['stateregnumber'].' - '.$this->view->row['name']);
            $this->view->uniqid = $this->view->id;

            $this->load->model('mddoc', 'middleware/models/');
            
            $fileData = $this->model->getErkContentMapModel_V3($this->view->row['id'], self::$erlStructureIdCivil);
            $this->view->fileList = $fileData['SS'];
            $this->view->fileCount = $fileData['ROW_COUNT'];
            
            $this->view->fileRender = $this->view->renderPrint('civil/fileList', self::$viewPath);

            $this->view->metaDataId = '1533714393827725';
            $this->view->metaDataCode = 'cvlCivilPackList';
            $this->view->refStructureId = self::$erlStructureIdCivil;
            $this->view->rowJson = json_encode($this->view->row);

            $response = array(
                'html' => $this->view->renderPrint('civil/mainForm', self::$viewPath),
                'title' => 'Мета оруулах',
                'status' => 'success',
                'uniqId' => $this->view->uniqid,
                'close_btn' => Lang::line('close_btn')
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => '<strong>PACK_ID</strong> олдсонгүй',
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function elcRegisterLegalBook() {
        
        $this->view->row = Input::post('selectedRow');
        $this->view->id = $this->view->row['id'];
        $this->view->companykeyid = $this->view->row['companykeyid'];
        $this->view->name = isset($this->view->row['companyregiternumber']) ? Str::upper($this->view->row['companyregiternumber'].' - '.$this->view->row['companyname']) : 'companyregiternumber';
        $this->view->uniqid = getUID();
        
        $this->load->model('mddoc', 'middleware/models/');
        
        $this->view->metaDataId = '1540202714291';
        $this->view->metaDataCode = 'CMP_SERVICE_BOOK_LIST';
                
        if (Input::postCheck('paramData')) {
            self::$paramBookData = $_POST['paramData'];
        
            if (isset(self::$paramBookData['refstructureid']) && self::$paramBookData['refstructureid'] != '') {

                self::$erlCmpServiceStrId = self::$paramBookData['refstructureid'];
                $this->view->metaDataId = self::$paramBookData['dataViewId'];
                $this->view->metaDataCode = self::$paramBookData['dataViewCode'];
            } 
        }
        
        if (isset(self::$paramBookData['saveprocesscode']) && self::$paramBookData['saveprocesscode'] != '') {
            $this->view->saveProcessCode = self::$paramBookData['saveprocesscode'];
        } else {
            $this->view->saveProcessCode = 'ERS_COMPANY_BOOK_META_DV_001';
        }
        
        $this->view->refStructureId = self::$erlCmpServiceStrId;
        $this->view->rowJson = json_encode($this->view->row);
        
        $getFileData = $this->model->getErkContentMapModel_V5($this->view->id, $this->view->refStructureId, $this->view->companykeyid);
        $this->view->fileList = $getFileData['SS'];
        $this->view->fileCount = $getFileData['ROW_COUNT'];
        $this->view->fileRender = $this->view->renderPrint('erl/fileList_v2', self::$viewPath);
        
        $response = array(
            'html' => $this->view->renderPrint('book/mainForm', self::$viewPath),
            'title' => 'Баримт хүлээн авах', 
            'uniqId' => $this->view->uniqid, 
            'companykeyid' => $this->view->companykeyid, 
            'close_btn' => Lang::line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function runProcessBefore() {
        
        $postData = Input::postData();
        $currentDate = Date::currentDate();
        $createdUserId = Ue::sessionUserKeyId();
        
        $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        
        $this->view->id = $postData['recordId'];
        $this->view->type = $postData['type'];
        $this->view->civilId = $postData['civilId'];
        
        try {
            
            $this->load->model('mddoc', 'middleware/models/');
            
            if (isset($postData['cvlContentId']) && $postData['cvlContentId']) {
            
                $cvlContentId = $postData['cvlContentId'];
                
                $params = array(
                    'id' => $this->view->id,
                    'CVL_CIVIL_BOOK_META_DV' => array(),
                );
                
                switch ($this->view->type) {
                    case '2':
                        if ($postData['wfmStatusId'] === '1532504692899860' || $postData['wfmStatusId'] === '1532505020715288' || $postData['wfmStatusId'] === '1532504887691783') {
                            $params['metaWfmLog'] = array( 
                                array(
                                    'id' => NULL,
                                    'refStructureId' => self::$erlStructureIdCivil,
                                    'recordId' => NULL,
                                    'wfmStatusId' => (($postData['wfmStatusId'] === '1532505020715288') ? '1532505007396254' : '1532504714283295'),
                                    'wfmDescription' => (($postData['wfmStatusId'] === '1532505020715288') ? ':Дахин мета дата оруулсан' : 'Мета дата оруулсан'),
                                    'createdDate' => $currentDate,
                                    'createdUserId' => $createdUserId,
                                    'contentHash' => NULL,
                                    'cipherText' => count($cvlContentId),
                                    'guid' => NULL,
                                )
                            );
                        }
                        break;
                    case '4':
                        if ($postData['wfmStatusId'] === '1540182290684067' || $postData['wfmStatusId'] === '1540182384443313' || $postData['wfmStatusId'] === '1540182332232604') {
                            $params['metaWfmLog'] = array( 
                                array(
                                    'id' => NULL,
                                    'refStructureId' => self::$erlStructureIdCnt,
                                    'recordId' => NULL,
                                    'wfmStatusId' => (($postData['wfmStatusId'] === '1540182384443313') ? '1540182398838288' : '1540182412552262'),
                                    'wfmDescription' => (($postData['wfmStatusId'] === '1540182384443313') ? ':Дахин мета дата оруулсан' : 'Мета дата оруулсан'),
                                    'createdDate' => $currentDate,
                                    'createdUserId' => $createdUserId,
                                    'contentHash' => NULL,
                                    'cipherText' => count($cvlContentId),
                                    'guid' => NULL,
                                )
                            );
                        }
                        break;
                }
                
                foreach ($cvlContentId as $key => $contentId) {
                    
                    $civilBookDate = ''; //$postData['cvlBookDate'][$key];
                    $civilBookTypeId = $postData['cvlBookTypeId'][$key];
                    
                    if (isset($postData['lastName'][$civilBookDate.'-'.$civilBookTypeId])) {
                        
                        $param = array(
                            'id' => null,
                            'bookTypeId' => $civilBookTypeId,
                            'civilPackId' => $this->view->id,
                            'civilId' => $this->view->civilId,
                            'bookDate' => '', //$civilBookDate,
                            'bookNumber' => null,
                            'stateRegisteredNumber' => isset($postData['registeredNum'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['registeredNum'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'familyName' => isset($postData['familyName'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['familyName'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'lastName' => isset($postData['lastName'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['lastName'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'firstName' => isset($postData['firstName'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['firstName'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'stateRegNumber' => isset($postData['registerNum'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['registerNum'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'gender' => isset($postData['gender'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['gender'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'byCreatedDate' => isset($postData['createdDate'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['createdDate'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'originId' => null,
                            'dateOfBirth' => isset($postData['dateofbirth'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['dateofbirth'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'city' => null,
                            'District' => null,
                            'street' => null,
                            'birthCity' => null,
                            'birthDistrict' => null,
                            'birthStreet' => null,
                            'wifeLastname' => null,
                            'wifeFirstname' => null,
                            'wifeRegNumber' => null,
                            'wifeBirthdate' => null,
                            'wifeCity' => null,
                            'wifeDistrict' => null,
                            'wifeStreet' => null,
                            'husbandLastname' => null,
                            'husbandFirstname' => null,
                            'husbandRegNumber' => null,
                            'husbandBirthdate' => null,
                            'husbandCity' => null,
                            'husbandDistrict' => null,
                            'husbandStreet' => null,
                            'motherLastname' => isset($postData['motherlastname'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['motherlastname'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'motherFirstname' => isset($postData['motherfirstname'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['motherfirstname'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'motherRegNumber' => isset($postData['motherregnumber'][$civilBookDate.'-'.$civilBookTypeId]) ? $postData['motherregnumber'][$civilBookDate.'-'.$civilBookTypeId] : '',
                            'spouseLastname' => null,
                            'spouseFirstname' => null,
                            'spouseRegNumber' => null,
                            'adoptMotherLastname' => null,
                            'adoptMotherFirstname' => null,
                            'adoptMotherRegNumber' => null,
                            'adoptSpouseLastname' => null,
                            'adoptSpouseFirstname' => null,
                            'adoptSpouseRegNumber' => null,
                            'previousLastname' => null,
                            'previousFirstname' => null,
                            'previousRegNumber' => null,
                            'changedLastname' => null,
                            'changedFirstname' => null,
                            'changedRegNumber' => null,
                            'passportNumber' => null,
                            'citizenCardNumber' => null,
                            'birthCertNumber' => null,
                            'foriegnPassportNumber' => null,
                            'marriageCertNumber' => null,
                            'deathCertNumber' => null,
                            'createdDate' => $currentDate,
                            'createdUserId' => $createdUserId,
                            'foreignPassportIssueDate' => null,
                            'foreignPassportExpireDate' => null,
                            'deathDate' => null,
                            'CVL_DM_RECORD_MAP_DV' => array (
                                'id' => null,
                                'wfmStatusId' => null,
                                'srcTableName' => 'CVL_CIVIL_BOOK',
                                'srcRecordId' => '',
                                'trgTableName' => 'ECM_CONTENT',
                                'trgRecordId' => $contentId
                            ),
                            'cvlCIVIL_BOOK_DTL_DV' =>  array()
                        );

                        if (isset($postData['cvlMarriageData'][$civilBookDate.'-'.$civilBookTypeId]) && $postData['cvlMarriageData'][$civilBookDate.'-'.$civilBookTypeId]) {
                            $selectedMarriageData = $postData['cvlMarriageData'][$civilBookDate.'-'.$civilBookTypeId];

                            $dataDtl = array (
                                'id' => null,
                                'civilBookId' => null,
                                'cvlBookTypeId' => $civilBookTypeId,
                                'wifeRegNumber' => isset($postData['marrWifeRegNumber'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrWifeRegNumber'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'wifeLastName' => isset($postData['marrWifeLastName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrWifeLastName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'wifeFirstName' => isset($postData['marrWifeFirstName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrWifeFirstName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'husbandRegNumber' => isset($postData['marrHusbandRegNumber'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrHusbandRegNumber'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'husbandLastName' => isset($postData['marrHusbandLastName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrHusbandLastName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'husbandFirstName' => isset($postData['marrHusbandFirstName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrHusbandFirstName'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'marriedDate' => isset($postData['marrMarriedDate'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrMarriedDate'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'marriageId' => isset($postData['marriageId'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marriageId'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'regDate' => isset($postData['marrRegDate'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrRegDate'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'civilId' => isset($postData['marrCivilId'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $postData['marrCivilId'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : ''
                            );

                            array_push($param['cvlCIVIL_BOOK_DTL_DV'], $dataDtl);
                        }

                        array_push($params['CVL_CIVIL_BOOK_META_DV'], $param);
                    }
                }
                
                includeLib('Utils/Functions');
                
                switch ($this->view->type) {
                    case '2':
                        $result = Functions::runProcess('CVL_CIVIL_PACK_META_DV_001', $params);
                        break;
                    case '4':
                        $result = Functions::runProcess('CVL_CNT_PACK_META_DV_001', $params);
                        break;
                }
                
            }
            
            if (isset($result['status']) && $result['status'] !== 'success') {
                $response = array('status' => $result['status'], 'message' => $result['text']);
            }
            
            switch ($this->view->type) {
                case '2':
                    $structureid = self::$erlStructureIdCivil;
                    break;
                case '4':
                    $structureid = self::$erlStructureIdCnt;
                    break;
            }
            
            if (Input::post('ignoreFileRender') != '1') {
                
                $fileData = $this->model->getErkContentMapModel_V3($this->view->id, $structureid);
                $this->view->fileList = $fileData['SS'];
                $this->view->fileCount = $fileData['ROW_COUNT'];   

                $response['fileRender'] = $this->view->renderPrint('civil/fileList', self::$viewPath);
            }

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => 'Error', 'ex' => $ex);
        }
        
        echo json_encode($response); exit;
    }
    
    public function runProcessBeforeV2() {
        
        $response = array('status' => 'success', 'message' => 'Success');
        
        try {
            
            $postData = Input::postData();
            $currentDate = Date::currentDate();
            $createdUserId = Ue::sessionUserKeyId();

            parse_str($postData['formData'], $formData);
            parse_str($postData['formInput'], $forInputmData);
            
            unset($postData['formData']);
            unset($postData['formInput']);
            
            if (!isset($postData['paramData']['cfgHdrCode']) || (isset($postData['paramData']['cfgHdrCode']) && !$postData['paramData']['cfgHdrCode'])) {
                throw new Exception("CONFIG hdr тохируулагдаагүй байна!"); 
            }

            if (!isset($postData['paramData']['refstructureid']) || (isset($postData['paramData']['refstructureid']) && !$postData['paramData']['refstructureid'])) {
                throw new Exception("STRUCTURE тохируулагдаагүй байна!"); 
            }

            if (!isset($postData['paramData']['processcode']) || (isset($postData['paramData']['processcode']) && !$postData['paramData']['processcode'])) {
                throw new Exception("PROCESSCODE тохируулагдаагүй байна!"); 
            }

            $structureId = $postData['paramData']['refstructureid'];
            $processcode = strtolower($postData['paramData']['processcode']);
            
            $bookHdrParams = explode(',', Config::getFromCache($postData['paramData']['cfgHdrCode']));
            $bookDtlParams = explode(',', Config::getFromCache($postData['paramData']['cfgDtlCode']));

            $this->view->id = $postData['recordId'];
            $this->view->type = $postData['type'];
            $this->view->civilId = $postData['civilId'];
            
            $this->load->model('mddoc', 'middleware/models/');
            
            if (isset($formData['cvlContentId']) && $formData['cvlContentId']) {
            
                $cvlContentId = $formData['cvlContentId'];
                
                $params = array(
                    'id' => $this->view->id,
                    'CVL_CIVIL_BOOK_META_DV' => array(),
                );
                
                switch ($this->view->type) {
                    case '2':
                        if ($postData['wfmStatusId'] == '1532504692899860' || $postData['wfmStatusId'] == '1532505020715288' || $postData['wfmStatusId'] == '1532504887691783') {
                            $params['metaWfmLog'] = array(
                                'id' => NULL,
                                'refStructureId' => $structureId,
                                'recordId' => NULL,
                                'wfmStatusId' => (($postData['wfmStatusId'] == '1532505020715288') ? '1532505007396254' : '1532504714283295'),
                                'wfmDescription' => (($postData['wfmStatusId'] == '1532505020715288') ? ':Дахин мета дата оруулсан' : 'Мета дата оруулсан'),
                                'createdDate' => $currentDate,
                                'createdUserId' => $createdUserId,
                                'contentHash' => NULL,
                                'cipherText' => count($cvlContentId),
                                'guid' => NULL,
                            );
                        }
                        break;
                    case '4':
                        if ($postData['wfmStatusId'] == '1540182290684067' || $postData['wfmStatusId'] == '1540182384443313' || $postData['wfmStatusId'] == '1540182332232604') {
                            
                            $dtlWfmStatusId = (($postData['wfmStatusId'] == '1540182384443313') ? '1540182398838288' : '1540182369128313');
                            //(($postData['wfmStatusId'] == '1540182384443313') ? '1540182398838288' : '1540182412552262');
                            
                            if ($processcode == 'cvl_cnt_pack_meta_dv_001') {
                                //$dtlWfmStatusId = '1540182412552262';
                                $params['wfmStatusId'] = $dtlWfmStatusId;
                            }
                            
                            $params['metaWfmLog'] = array( 
                                'id' => NULL,
                                'refStructureId' => $structureId,
                                'recordId' => NULL,
                                'wfmStatusId' => $dtlWfmStatusId,
                                'wfmDescription' => (($postData['wfmStatusId'] == '1540182384443313') ? 'Дахин мета дата оруулсан' : 'Мета дата оруулсан'),
                                'createdDate' => $currentDate,
                                'createdUserId' => $createdUserId,
                                'contentHash' => NULL,
                                'cipherText' => count($cvlContentId),
                                'guid' => NULL
                            );
                        }
                        break;
                }
                
                foreach ($cvlContentId as $key => $contentId) {
                    
                    $civilBookDate = ''; //$postData['cvlBookDate'][$key];
                    $civilBookTypeId = $formData['cvlBookTypeId'][$key];
                   
                    if (isset($formData['lastname'][$civilBookDate.'-'.$civilBookTypeId])) {
                        
                        $param = array (
                            'id' => null,
                            'bookTypeId' => $civilBookTypeId,
                            'civilPackId' => $this->view->id,
                            'civilId' => $this->view->civilId,
                            'bookDate' => '',
                            'bookNumber' => null,
                            'createdDate' => $currentDate,
                            'createdUserId' => $createdUserId,
                            'deathDate' => null,
                            'CVL_DM_RECORD_MAP_DV' => array (
                                'id' => null,
                                'wfmStatusId' => null,
                                'srcTableName' => 'CVL_CIVIL_BOOK',
                                'srcRecordId' => '',
                                'trgTableName' => 'ECM_CONTENT',
                                'trgRecordId' => $contentId
                            ),
                            'cvlCIVIL_BOOK_DTL_DV' => array()
                        );
                        
                        foreach ($bookHdrParams as $hdrParam) {
                            $hParam = Security::sanitize($hdrParam);
                            $param[$hParam] = isset($formData[$hParam][$civilBookDate.'-'.$civilBookTypeId]) ? $formData[$hParam][$civilBookDate.'-'.$civilBookTypeId] : '';
                        }
                        
                        if (isset($formData['cvlMarriageData'][$civilBookDate.'-'.$civilBookTypeId]) && $formData['cvlMarriageData'][$civilBookDate.'-'.$civilBookTypeId]) {
                            
                            $selectedMarriageData = $formData['cvlMarriageData'][$civilBookDate.'-'.$civilBookTypeId];

                            $dataDtl = array (
                                'id' => null,
                                'civilBookId' => null,
                                'civilId' => isset($formData['marrCivilId'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $formData['marrCivilId'][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '',
                                'cvlBookTypeId' => $civilBookTypeId,
                            );
                            
                            foreach ($bookDtlParams as $dtlParam) {
                                
                                $dParam = Input::param($dtlParam);
                                $param[$dParam] = isset($formData[$dParam][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData]) ? $formData[$dParam][$civilBookDate.'-'.$civilBookTypeId][$selectedMarriageData] : '';
                            }

                            array_push($param['cvlCIVIL_BOOK_DTL_DV'], $dataDtl);
                        }

                        array_push($params['CVL_CIVIL_BOOK_META_DV'], $param);
                    }
                }
                
                includeLib('Utils/Functions');
                $result = Functions::runProcess($processcode, $params);
            }
            
            if (isset($result['status']) && $result['status'] !== 'success') {
                $response = array('status' => $result['status'], 'message' => $result['text']);
            }
            
            $fileData = $this->model->getErkContentMapModel_V3($this->view->id, $structureId);
            $this->view->fileList = $fileData['SS'];
            $this->view->fileCount = $fileData['ROW_COUNT'];   
            
            $response['fileRender'] = $this->view->renderPrint('civil/fileList', self::$viewPath);

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage(), 'ex' => $ex);
        }
        
        echo json_encode($response); exit;
    }
    
    public function runProcessValue() {
        $data = $this->model->getBookContenDataModel(Input::post('id'));
        parse_str($data, $response);
        echo json_encode($response);
    }
    
    public function cvlFormDataHtml() {
        
        includeLib('Utils/Functions');
        
        $postData = Input::postData();
        $result = $bookData = $bookDataMarrigeData = array();
        $ticket = false;
        $civilId = (isset($postData['selectedRow']['civilid']) && $postData['selectedRow']['civilid']) ? $postData['selectedRow']['civilid'] : '';
        
        if (isset($postData['cvlBookId']) && $postData['cvlBookId']) {
            
            //CVL_BOOK_META_GET_DV_004
            $result = Functions::runProcess('CVL_META_SECOND_GET_DV_004', array('id' => $postData['cvlBookId'])); //CVL_CIVIL_BOOK_META_GET_DV_004
            
            if (isset($result['result']) && $result['result']) {
                $bookData = $result['result'];
            }

            if (isset($result['result']['cvlcivil_book_dtl_dv']) && $result['result']['cvlcivil_book_dtl_dv']) {
                $bookDataMarrigeData = $result['result']['cvlcivil_book_dtl_dv'];
                
                if ($bookDataMarrigeData) {
                    $ticket = true;
                }
            }
            
            $resultpackData = array();
            
            if (isset($postData['isDisabled']) && $postData['isDisabled'] == '1') {
                
                
                $civilPackId = (isset($postData['selectedRow']['id']) && $postData['selectedRow']['id']) ? $postData['selectedRow']['id'] : '';

                $getParam = array ( 'id' => $civilId, 'cvlCivilPackDV' => array ( 'id' => $civilPackId));
                //cvlCivilDV_004
                $resultpackData = Functions::runProcess('cvlMetaFirstGetDv_004', $getParam);

                if (isset($resultpackData['result']['cvlcivilmarriagebookdv']) && $resultpackData['result']['cvlcivilmarriagebookdv']) {
                    $bookDataMarrigeData_bookDtl = $bookDataMarrigeData;
                    $bookDataMarrigeData = $resultpackData['result']['cvlcivilmarriagebookdv'];
                }
            
            }
            
            if (isset($bookData['stateregnumber']) && $bookData['stateregnumber']) {

            } else {
                if (isset($resultpackData['result']['stateregnumber'])) {
                    $bookData = $resultpackData['result'];
                }
            }
            
        } else {
            
            $civilPackId = (isset($postData['selectedRow']['id']) && $postData['selectedRow']['id']) ? $postData['selectedRow']['id'] : '';
            
            $getParam = array ( 'id' => $civilId, 'cvlCivilPackDV' => array ( 'id' => $civilPackId));
            
            //cvlCivilDV_004
            $result = Functions::runProcess('cvlMetaFirstGetDv_004', $getParam);
            
            if (isset($result['result']) && $result['result']) {
                $bookData = $result['result'];
            }

            if (isset($result['result']['cvlcivilmarriagebookdv']) && $result['result']['cvlcivilmarriagebookdv']) {
                $bookDataMarrigeData = $result['result']['cvlcivilmarriagebookdv'];
            }
        }
        
        if (isset($result['result']['cvl_dm_record_map_dv']['trgrecordid']) && $result['result']['cvl_dm_record_map_dv']['trgrecordid']) {
           $contentId = $result['result']['cvl_dm_record_map_dv']['trgrecordid']; 
        }
        
        $contentId = (isset($postData['cvlContentId']) ? $postData['cvlContentId'] : '');
        $isDisabled = (isset($postData['isDisabled']) && $postData['isDisabled'] == '0') ? 'disabled="disabled"' : 'readonly="readonly"';
        
        $uniqId = (isset($postData['uniqId']) && $postData['uniqId']) ? $postData['uniqId'] : getUID();
        $index = (isset($postData['trIndex']) && $postData['trIndex']) ? $postData['trIndex']-1 : 0;
        
        $srcTarget = (isset($postData['cvlBookDate']) && isset($postData['cvlBookType'])) ? $postData['cvlBookDate']. '-'.$postData['cvlBookType'] : '0';
        
        (String) $html = '';
        
        if (isset($postData['isHide']) && $postData['isHide']) {} else {
            if (isset($postData['isDisabled']) && $postData['isDisabled'] == '0') {} else {
                $html .= '<button type="button" class="btn btn-sm btn-circle btn-danger float-right mb10" onclick="cvlEditBookData_'. $uniqId .'(this);"><i class="fa fa-edit"></i> Засах</button>';
            }
        }
        
        $html .= '
                 <table class="table table-sm table-no-bordered bp-header-param">
                    <tbody>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="registerNum" style="width: 46%">
                                <label for="registerNum" data-label-path="registerNum">'. Lang::line('CVL_REGISTER_NUM') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="registerNum" style="width: 54%" colspan="">
                                <div data-section-path="registerNum">
                                    <input type="hidden" name="civilPackId['. $srcTarget .']" '. $isDisabled .' value="'. (isset($bookData['civilpackid']) ? $bookData['civilpackid'] : '') .'">
                                    <input type="hidden" name="civilId['. $srcTarget .']" '. $isDisabled .' value="'. $civilId .'">
                                    <input type="hidden" data-path="bookTypeId" name="bookTypeId['. $srcTarget .']" value="'. (isset($bookData['booktypeid']) ? $bookData['booktypeid'] : '') .'">
                                    <input type="hidden" data-path="bookDate" name="bookDate['. $srcTarget .']" value="'. (isset($bookData['bookdate']) ? $bookData['bookdate'] : '') .'">
                                    <input type="hidden" name="srcTableName['. $srcTarget .']" '. $isDisabled .' value="CVL_CIVIL_BOOK">
                                    <input type="hidden" name="srcRecordId['. $srcTarget .']"  '. $isDisabled .' value="'. (isset($bookData['id']) ? $bookData['id'] : '') .'">
                                    <input type="hidden" name="trgTableName['. $srcTarget .']" '. $isDisabled .' value="ECM_CONTENT">
                                    <input type="hidden" name="trgRecordId['. $srcTarget .']"  '. $isDisabled .' value="'. $contentId .'">
                                    <input type="text" name="registerNum['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="registerNum" data-field-name="registerNum" value="'. (isset($bookData['stateregnumber']) ? $bookData['stateregnumber'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_REGISTER_NUM') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="registeredNum" style="width: 46%">
                                <label for="registeredNum" data-label-path="registeredNum">'. Lang::line('CVL_REGISTERED_NUM') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="registeredNum" style="width: 54%" colspan="">
                                <div data-section-path="registeredNum">
                                    <input type="text" name="registeredNum['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="registeredNum" data-field-name="registeredNum" value="'. (isset($bookData['stateregisterednumber']) ? $bookData['stateregisterednumber'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_REGISTERED_NUM') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="familyName" style="width: 46%">
                                <label for="familyName" data-label-path="familyName">'. Lang::line('CVL_FORENAME') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="familyName" style="width: 54%" colspan="">
                                <div data-section-path="familyName">
                                    <input type="text" name="familyName['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="familyName" data-field-name="familyName" value="'. (isset($bookData['familyname']) ? $bookData['familyname'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_FORENAME') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="lastName" style="width: 46%">
                                <label for="lastName" data-label-path="lastName">'. Lang::line('CVL_SURNAME') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="lastName" style="width: 54%" colspan="">
                                <div data-section-path="lastName">
                                    <input type="text" name="lastName['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="lastName" data-field-name="lastName" value="'. (isset($bookData['lastname']) ? $bookData['lastname'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_SURNAME') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="firstName" style="width: 46%">
                                <label for="firstName" data-label-path="firstName['. $srcTarget .']">'. Lang::line('CVL_GIVEN_NAME') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="firstName" style="width: 54%" colspan="">
                                <div data-section-path="firstName">
                                    <input type="text" name="firstName['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="firstName" data-field-name="firstName" value="'. (isset($bookData['firstname']) ? $bookData['firstname'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_GIVEN_NAME') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="createdDate" style="width: 46%">
                                <label for="createdDate" data-label-path="createdDate['. $srcTarget .']">'. Lang::line('CVL_CREATED_DATE') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="createdDate" style="width: 54%" colspan="">
                                <div data-section-path="createdDate">
                                    <input type="text" name="createdDate['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm dateInit" data-path="createdDate" data-field-name="createdDate" value="'. (isset($bookData['createddate']) ? $bookData['createddate'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_CREATED_DATE') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="dateofbirth" style="width: 46%">
                                <label for="dateofbirth" data-label-path="dateofbirth['. $srcTarget .']">'. Lang::line('CVL_BIRTH_DATE') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="dateofbirth" style="width: 54%" colspan="">
                                <div data-section-path="dateofbirth">
                                    <input type="text" name="dateofbirth['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm dateInit" data-path="dateofbirth" data-field-name="dateofbirth" value="'. (isset($bookData['dateofbirth']) ? $bookData['dateofbirth'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_BIRTH_DATE') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="gender" style="width: 46%">
                                <label for="gender" data-label-path="gender['. $srcTarget .']">'. Lang::line('CVL_SEX_NAME') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="gender" style="width: 54%" colspan="">
                                <div data-section-path="gender">
                                    <input type="text" name="gender['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="gender" data-field-name="gender" value="'. (isset($bookData['gender']) ? $bookData['gender'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_SEX_NAME') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="motherregnumber" style="width: 46%">
                                <label for="motherregnumber" data-label-path="motherregnumber['. $srcTarget .']">'. Lang::line('CVL_MO_REGISTER_NUM') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="motherregnumber" style="width: 54%" colspan="">
                                <div data-section-path="motherregnumber">
                                    <input type="text" name="motherregnumber['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="motherregnumber" data-field-name="motherregnumber" value="'. (isset($bookData['motherregnumber']) ? $bookData['motherregnumber'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_MO_REGISTER_NUM') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="motherlastname" style="width: 46%">
                                <label for="motherlastname" data-label-path="motherlastname['. $srcTarget .']">'. Lang::line('CVL_MO_SURNAME') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="motherlastname" style="width: 54%" colspan="">
                                <div data-section-path="motherlastname">
                                    <input type="text" name="motherlastname['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="motherlastname" data-field-name="motherlastname" value="'. (isset($bookData['motherlastname']) ? $bookData['motherlastname'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_MO_SURNAME') .'">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left middle border-bottom-cvl" data-cell-path="motherfirstname" style="width: 46%">
                                <label for="motherfirstname" data-label-path="motherfirstname['. $srcTarget .']">'. Lang::line('CVL_MO_GIVEN_NAME') .'</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="middle" data-cell-path="motherfirstname" style="width: 54%" colspan="">
                                <div data-section-path="motherfirstname">
                                    <input type="text" name="motherfirstname['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="motherfirstname" data-field-name="motherfirstname" value="'. (isset($bookData['motherfirstname']) ? $bookData['motherfirstname'] : '') .'" data-isclear="" placeholder="'. Lang::line('CVL_MO_GIVEN_NAME') .'">
                                </div>
                            </td>
                        </tr>
                    </tbody>
            </table>';
        
        if ($bookDataMarrigeData) {
            $disabled = '';
            if (isset($postData['isDisabled']) && $postData['isDisabled'] == '0') {
                $disabled = 'disabled="disabled"';
            }
            
            $html .= '<select  name="cvlMarriageData['. $srcTarget .']" onchange="callMarriageFnc(this)" '. $disabled .' class="form-control form-control-sm select2" required="required" style="width: 100%">';
            $selected = '';
            
            if ((isset($postData['isDisabled']) && $postData['isDisabled'] == '1')) {
                $html .= '<option selected="selected" value="">- Сонгох -</option>';
            }
            
            foreach ($bookDataMarrigeData as $key => $row) {
                
                $tempRow = $row;
                $selectText = $tempRow['regdate'] .' бүртгэгдсэн гэрлэлт';
                if (isset($postData['isDisabled']) && $postData['isDisabled'] == '0' && $key == 0) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                    if (isset($bookDataMarrigeData_bookDtl[0]['marriageid']) && $bookDataMarrigeData_bookDtl[0]['marriageid'] == $row['id']) {
                        $tempRow = $bookDataMarrigeData_bookDtl[0];
                        $selectText = $tempRow['regdate'] .' бүртгэгдсэн гэрлэлт хадгалагдсан';
                        $selected = 'selected="selected"';
                    }
                }
                
                $html .= '<option '. $selected . ' value="'. $tempRow['id'] .'">'. $selectText .'</option>';
                
            }

            $html .= '</select>';
            
            
            foreach ($bookDataMarrigeData as $key => $row) {
                $hidden = 'hidden';
                $tempRow = $row;
                if ((isset($postData['isDisabled']) && $postData['isDisabled'] == '0') && $key == 0) {
                    $hidden = '';
                } elseif (isset($bookDataMarrigeData_bookDtl[0]['marriageid']) && $bookDataMarrigeData_bookDtl[0]['marriageid'] == $row['marriageid']) {
                    $tempRow = $bookDataMarrigeData_bookDtl[0];
                    $hidden = '';
                }
                
                $html .= "<table class='". $tempRow['id'] ." $hidden cvl-marriage-table table table-sm table-no-bordered bp-header-param'>";
                    $html .= '<input type="hidden" name="marrCivilId['. $srcTarget .']['. $tempRow['id'] .']"  value="'. $tempRow['civilid'] .'">';
                    $html .= '<input type="hidden" name="marriageId['. $srcTarget .']['. $tempRow['id'] .']"  value="'. $tempRow['marriageid'] .'">';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrWifeRegNumber" style="width: 46%">
                                        <label for="marrWifeRegNumber" data-label-path="marrWifeRegNumber['. $srcTarget .']">'. Lang::line('CVL_WIFE_REG_NUMBER') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrWifeRegNumber" style="width: 54%" colspan="">
                                        <div data-section-path="marrWifeRegNumber">
                                            <input type="text" name="marrWifeRegNumber['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="marrWifeRegNumber" data-field-name="marrWifeRegNumber" value="'. $tempRow['wiferegnumber'] .'" data-isclear="" placeholder="'. Lang::line('CVL_WIFE_REG_NUMBER') .'">
                                        </div>
                                    </td>
                                </tr>';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrWifeLastName" style="width: 46%">
                                        <label for="marrWifeLastName" data-label-path="marrWifeLastName['. $srcTarget .']">'. Lang::line('CVL_WIFE_LAST_NAME') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrWifeLastName" style="width: 54%" colspan="">
                                        <div data-section-path="marrWifeLastName">
                                            <input type="text" name="marrWifeLastName['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="marrWifeLastName" data-field-name="marrWifeLastName" value="'. $tempRow['wifelastname'] .'" data-isclear="" placeholder="'. Lang::line('CVL_WIFE_LAST_NAME') .'">
                                        </div>
                                    </td>
                                </tr>';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrWifeFirstName" style="width: 46%">
                                        <label for="marrWifeFirstName" data-label-path="marrWifeFirstName['. $srcTarget .']">'. Lang::line('CVL_WIFE_FIRST_NAME') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrWifeFirstName" style="width: 54%" colspan="">
                                        <div data-section-path="marrWifeFirstName">
                                            <input type="text" name="marrWifeFirstName['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="marrWifeFirstName" data-field-name="marrWifeFirstName" value="'. $tempRow['wifefirstname'] .'" data-isclear="" placeholder="'. Lang::line('CVL_WIFE_FIRST_NAME') .'">
                                        </div>
                                    </td>
                                </tr>';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrHusbandRegNumber" style="width: 46%">
                                        <label for="marrHusbandRegNumber" data-label-path="marrHusbandRegNumber['. $srcTarget .']">'. Lang::line('CVL_HUSBAND_REG_NUMBER') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrHusbandRegNumber" style="width: 54%" colspan="">
                                        <div data-section-path="marrHusbandRegNumber">
                                            <input type="text" name="marrHusbandRegNumber['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="marrHusbandRegNumber" data-field-name="marrHusbandRegNumber" value="'. $tempRow['husbandregnumber'] .'" data-isclear="" placeholder="'. Lang::line('CVL_HUSBAND_REG_NUMBER') .'">
                                        </div>
                                    </td>
                                </tr>';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrHusbandLastName" style="width: 46%">
                                        <label for="marrHusbandLastName" data-label-path="marrHusbandLastName['. $srcTarget .']">'. Lang::line('CVL_HUSBAND_LAST_NAME') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrHusbandLastName" style="width: 54%" colspan="">
                                        <div data-section-path="marrHusbandLastName">
                                            <input type="text" name="marrHusbandLastName['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="marrHusbandLastName" data-field-name="marrHusbandLastName" value="'. $tempRow['husbandlastname'] .'" data-isclear="" placeholder="'. Lang::line('CVL_HUSBAND_LAST_NAME') .'">
                                        </div>
                                    </td>
                                </tr>';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrHusbandFirstName" style="width: 46%">
                                        <label for="marrHusbandFirstName" data-label-path="marrHusbandFirstName['. $srcTarget .']">'. Lang::line('CVL_HUSBAND_FIRST_NAME') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrHusbandFirstName" style="width: 54%" colspan="">
                                        <div data-section-path="marrHusbandFirstName">
                                            <input type="text" name="marrHusbandFirstName['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm stringInit" data-path="marrHusbandFirstName" data-field-name="marrHusbandFirstName" value="'. $tempRow['husbandfirstname'] .'" data-isclear="" placeholder="'. Lang::line('CVL_HUSBAND_FIRST_NAME') .'">
                                        </div>
                                    </td>
                                </tr>';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrMarriedDate" style="width: 46%">
                                        <label for="marrMarriedDate" data-label-path="marrMarriedDate['. $srcTarget .']">'. Lang::line('CVL_MARRIED_DATE') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrMarriedDate" style="width: 54%" colspan="">
                                        <div data-section-path="marrMarriedDate">
                                            <input type="text" name="marrMarriedDate['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm dateInit" data-path="marrMarriedDate" data-field-name="marrMarriedDate" value="'. $tempRow['marrieddate'] .'" data-isclear="" placeholder="'. Lang::line('CVL_MARRIED_DATE') .'">
                                        </div>
                                    </td>
                                </tr>';
                    $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="marrRegDate" style="width: 46%">
                                        <label for="marrRegDate" data-label-path="marrRegDate['. $srcTarget .']">'. Lang::line('CVL_REG_DATE') .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="marrRegDate" style="width: 54%" colspan="">
                                        <div data-section-path="marrRegDate">
                                            <input type="text" name="marrRegDate['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm dateInit" data-path="marrRegDate" data-field-name="marrRegDate" value="'. $tempRow['regdate'] .'" data-isclear="" placeholder="'. Lang::line('CVL_REG_DATE') .'">
                                        </div>
                                    </td>
                                </tr>';
                $html .= "</table>";

            }
        }
        
        echo json_encode(array('Html' => $html, 'resultData' => $result, 'ticked' => $ticket));
    }
    
    public function electronRegisterLegalBulkScanCvl() {
        
        $recordId = Input::post('recordId');
        $response = $this->model->electronRegisterLegalBulkScanModel($recordId);
        $structureid = self::$erlStructureIdCivil;
        
        if (Input::postCheck('notFilesRender') == false) {
            $this->view->id = $recordId;
            
            switch (Input::post('type')) {
                case '2':
                    $structureid = self::$erlStructureIdCivil;
                    break;
                case '4':
                    $structureid = self::$erlStructureIdCnt;
                    break;
            }
            
            $fileData = $this->model->getErkContentMapModel_V3($recordId, $structureid);
            $this->view->fileList = $fileData['SS'];
            $this->view->fileCount = $fileData['ROW_COUNT'];   
            
            $response['fileRender'] = $this->view->renderPrint('civil/fileList', self::$viewPath);
        }
        
        echo json_encode($response); exit;
    }
    
    public function electronRegisterLegalBulkReScanCvl() {
        
        $recordId = Input::post('recordId');
        $response = $this->model->electronRegisterLegalBulkReScanModel($recordId);
        $structureid = self::$erlStructureIdCivil;
        
        if (Input::postCheck('notFilesRender') == false) {
            
            $this->view->id = $recordId;
            
            switch (Input::post('type')) {
                case '2':
                    $structureid = self::$erlStructureIdCivil;
                    break;
                case '4':
                    $structureid = self::$erlStructureIdCnt;
                    break;
            }
            
            $fileData = $this->model->getErkContentMapModel_V3($recordId, $structureid);
            $this->view->fileList = $fileData['SS'];
            $this->view->fileCount = $fileData['ROW_COUNT'];   
            
            $response['fileRender'] = $this->view->renderPrint('civil/fileList', self::$viewPath);
        }
        
        echo json_encode($response); exit;
    }
    
    public function dataviewPdfExport() {
        
        $htmlContent = '';
        $getRows = $this->model->getListDataModel(Input::post('dataViewId'));
        $exportField = rtrim(strtolower(Input::post('exportField')), ',');
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $reportName = $this->model->getMetaDataModel(Input::post('dataViewId'));
        $reportName = $reportName['META_DATA_NAME'];
        
        if ($getRows) {
            foreach ($getRows as $row) {
                $htmlContent .= html_entity_decode(issetParam($row[$exportField]), ENT_QUOTES).'<br/>';
            }
        }
        
        $css = '<style type="text/css">';
            $css .= Mdpreview::printCss('statementPdf');
        $css .= '</style>';
        
        includeLib('PDF/Pdf');
        
        $pdf = Pdf::createSnappyPdf('Portrait', 'A4');
        Pdf::setSnappyOutput($pdf, $css . $htmlContent, $reportName);
        
    }    
    
    public function tempFileSave() {
        $base64Img = Input::post('finger');
        $getUID = getUID();
        
        $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/customer/', false);
        $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
        $response = array('filePath' => $filePath, 'base64Img' => $base64Img);
        
        if (Config::getFromCache('deleteTempFiles')) {         
            $bpProcessFiles = glob(UPLOADPATH . 'finger/customer/*');
            
            foreach ($bpProcessFiles as $bpProcessFile) {
                if (is_file($bpProcessFile) && (time() - filemtime($bpProcessFile) > 60 * 60 * 0.5 * 1)) { /* 30 min tutam ustgah */
                    @unlink($bpProcessFile);
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response); exit;
    }
    
    public function cvlControlFormDataHtml() {
        
        includeLib('Utils/Functions');
        
        $postData = Input::postData();
        (Array) $result = $bookData = $bookDataMarrigeData = array();
        $ticket = false;
        $civilId = (isset($postData['selectedRow']['civilid']) && $postData['selectedRow']['civilid']) ? $postData['selectedRow']['civilid'] : '';
        $inType = isset($postData['type']) ? $postData['type'] : '0';
        
        if ($inType === '7' || $inType === '6') {
            $this->model->saveCvlContentViewLogModel($postData);
        }
        
        if (isset($postData['cvlBookId']) && $postData['cvlBookId']) {
            $result = Functions::runProcess('CVL_CIVIL_BOOK_META_DV_004', array('id' => $postData['cvlBookId']));
            
            if (isset($result['result']) && $result['result']) {
                $bookData = $result['result'];
            }

            if (isset($result['result']['cvlcivil_book_dtl_dv']) && $result['result']['cvlcivil_book_dtl_dv']) {
                $bookDataMarrigeData = $result['result']['cvlcivil_book_dtl_dv'];
                
                if ($bookDataMarrigeData) {
                    $ticket = true;
                }
            }
            
            (Array) $resultpackData = array();
            
            $civilPackId = (isset($postData['selectedRow']['id']) && $postData['selectedRow']['id']) ? $postData['selectedRow']['id'] : '';

            $getParam = array ( 'id' => $civilId, 'cvlCivilPackDV' => array ( 'id' => $civilPackId));
            
            //cvlCivilDV_004
            $resultpackData = Functions::runProcess('cvlMetaFirstGetDv_004', $getParam);
            
            if (isset($resultpackData['result']['cvlcivilmarriagebookdv']) && $resultpackData['result']['cvlcivilmarriagebookdv']) {
                $bookDataMarrigeData_bookDtl = $bookDataMarrigeData;
                $bookDataMarrigeData = $resultpackData['result']['cvlcivilmarriagebookdv'];
            }
            /*
            if (isset($bookData['stateregnumber']) && $bookData['stateregnumber']) {

            } else {
                if (isset($resultpackData['result']['stateregnumber'])) {
                    $bookData = $resultpackData['result'];
                }
            }*/
            
        } else {
            
            $civilPackId = (isset($postData['selectedRow']['id']) && $postData['selectedRow']['id']) ? $postData['selectedRow']['id'] : '';
            
            $getParam = array ( 'id' => $civilId, 'cvlCivilPackDV' => array ( 'id' => $civilPackId));
            
            //cvlCivilDV_004
            $result = Functions::runProcess('cvlMetaFirstGetDv_004', $getParam);
            
            if (isset($result['result']) && $result['result']) {
                $bookData = $result['result'];
            }

            if (isset($result['result']['cvlcivilmarriagebookdv']) && $result['result']['cvlcivilmarriagebookdv']) {
                $bookDataMarrigeData = $result['result']['cvlcivilmarriagebookdv'];
            }
        }
        
        if (isset($result['result']['cvl_dm_record_map_dv']['trgrecordid']) && $result['result']['cvl_dm_record_map_dv']['trgrecordid']) {
           $contentId = $result['result']['cvl_dm_record_map_dv']['trgrecordid']; 
        }
        
        $this->view->civilId = $civilId;
        $this->view->inType = $inType;
        $this->view->postData = $postData;
        $this->view->bookData = $bookData;
        $this->view->bookDataMarrigeData = $bookDataMarrigeData;
        
        $this->view->cfgHdrCode = $this->view->postData['postData']['cfgHdrCode'];
        $this->view->cfgDtlCode = $this->view->postData['postData']['cfgDtlCode'];
        
        $this->view->bookHeadParams = Config::getFromCache($this->view->cfgHdrCode);
        $this->view->bookDtlParams = Config::getFromCache($this->view->cfgDtlCode);
        
        $this->view->bookDataMarrigeData_bookDtl = isset($bookDataMarrigeData_bookDtl) ? $bookDataMarrigeData_bookDtl : array();
        
        $html = $this->view->renderPrint('control/controlForm', self::$viewPath);
        
        echo json_encode(array('Html' => $html, 'resultData' => $result, 'ticked' => $ticket));
    }
    
    public function saveCvlBookData() {
        $sessionUserId = Ue::sessionUserId();
        $currentDate = Date::currentDate();
        $postData = Input::postData();
        
        $bookHdrParams = explode(',', Config::getFromCache($postData['cfgHdrCode'][0]));
        $bookDtlParams = explode(',', Config::getFromCache($postData['cfgDtlCode'][0]));
        
        try {
            
            (Array) $cvlCivilBookDtlDv = $cvlMarrBookDtlDv = array();
            
            if (isset($postData['cvlMarriageData'])) {
                foreach ($postData['cvlMarriageData'] as $key => $row) {
                    
                    $bookDtlDv = array (
                                    'id' => '',
                                    'marriageId' => $row,
                                    'civilBookId' => $postData['srcRecordId'][0],
                                    'CVL_CIVIL_BOOK_DTL_LOG_DV' => array (
                                        'id' => '',
                                        'civilBookId' => $postData['srcRecordId'][0],
                                        'marriageId' => $row,
                                        'createdDate' => $currentDate,
                                    ),
                                    'rowState' => 'unchanged',
                                );

                    $marrBookDtlDv = array (
                                    'id' => $row,
                                    'marriageId' => $row,
                                    'civilBookId' => $postData['srcRecordId'][0],
                                    'CVL_MARRIAGE_BOOK_LOG_DV' => array (
                                        'id' => '',
                                        'civilBookId' => $postData['srcRecordId'][0],
                                        'cvlMarriageBookId' => $row,
                                        'createdDate' => $currentDate,
                                    ),
                                    'rowState' => 'unchanged',
                                );
                    if ($bookDtlParams) {
                        
                        foreach ($bookDtlParams as $dtlParam) {
                            $dParam = Security::sanitize($dtlParam);
                            $bookDtlDv[$dParam] = $postData[$dParam][0][$row];
                            $bookDtlDv['CVL_CIVIL_BOOK_DTL_LOG_DV'][$dParam] = ($postData[$dParam][0][$row] != $postData[$dParam . '_old'][0][$row]) ? $postData[$dParam. '_old'][0][$row] : NULL;

                            $marrBookDtlDv[$dParam] = $postData[$dParam][0][$row];
                            $marrBookDtlDv['CVL_MARRIAGE_BOOK_LOG_DV'][$dParam] = ($postData[$dParam][0][$row] != $postData[$dParam . '_old'][0][$row]) ? $postData[$dParam. '_old'][0][$row] : NULL;
                        }
                        
                    }


                    array_push($cvlCivilBookDtlDv, $bookDtlDv);
                    array_push($cvlMarrBookDtlDv, $marrBookDtlDv);
                    
                }   
            }

            $param = array (
                        'id' => $postData['srcRecordId'][0],
                        'bookTypeId' => $postData['bookTypeId'][0], 
                        'civilPackId' => $postData['civilPackId'][0],
                        'civilId' => $postData['civilId'][0],
                        'createdUserId' => $sessionUserId,
                        'CVL_CIVIL_BOOK_LOG_DV' => array (
                            'id' => '',
                            'civilBookId' => $postData['srcRecordId'][0],
                            'createdUserId' => $sessionUserId,
                            'createdDate' => $currentDate,
                        ),
                        'CVL_CIVIL_DV' => array (
                            'id' => $postData['civilId'][0],
                            'createdUserId' => $sessionUserId,
                            'isOnline' => '0',
                            'CVL_CIVIL_LOG_DV' => array (
                                                        'id' => '',
                                                        'createdUserId' => $sessionUserId,
                                                        'isOnline' => '0',
                                                        'civilId' => $postData['civilId'][0],
                                                        'createdDate' => $currentDate,
                                                    ),
                        ),
                        'CVL_CIVIL_BOOK_DTL_DV' => $cvlCivilBookDtlDv,
                        'CVL_MARRIAGE_BOOK_DV' => $cvlMarrBookDtlDv,
                    );
            
            foreach ($bookHdrParams as $hdrParam) {
                $hParam = Security::sanitize($hdrParam);
                
                $param[$hParam] = $postData[$hParam][0];
                $param['CVL_CIVIL_DV'][$hParam] = $postData[$hParam][0];
                
                $param['CVL_CIVIL_BOOK_LOG_DV'][$hParam] = ($postData[$hParam][0] != $postData[$hParam . '_old'][0]) ? $postData[$hParam . '_old'][0] : NULL;
                $param['CVL_CIVIL_DV']['CVL_CIVIL_LOG_DV'][$hParam] = ($postData[$hParam][0] != $postData[$hParam . '_old'][0]) ? $postData[$hParam . '_old'][0] : NULL;
            }
            
            includeLib('Utils/Functions');
            $result = Functions::runProcess('CIVIL_META_UPDATE_DV_002', $param);
            
            $response = array('status' => 'error', 'message' => 'Error', 'result' => $result);
            
            if (isset($result['status']) && $result['status'] === 'success') {
                $response = array('status' => $result['status'], 'message' => isset($result['text']) ? $result['text'] : Lang::line('msg_save_success'), 'result' => $result);
            } else {
                $response['message'] = isset($result['text']) ? $result['text'] : Lang::line('msg_save_error');
            }
            
            $response['fileRender'] = '';
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => 'Error', 'ex' => $ex);
        }
        
        echo json_encode($response);
    }
    
    public function deletePhotCvl() {
        $currentDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        $param = array (
                    'id' => NULL,
                    'contentId' => Input::post('contentId'),
                    'civilId' => Input::post('civilId'),
                    'createdDate' => $currentDate,
                    'createdUserId' => $sessionUserKeyId,
                    'modifiedDate' => NULL,
                    'modifiedUserId' => NULL,
                    'ECM_CONTENT_DV' => 
                    array (
                      'id' => NULL,
                      'createdDate' => $currentDate,
                      'createdUserId' => $sessionUserKeyId,
                      'modifiedDate' => NULL,
                      'modifiedUserId' => NULL,
                      'isVersion' => '1',
                    ),
                );
        
        includeLib('Utils/Functions');
        $result = Functions::runProcess('DELETED_PHOTO_LOG_DV_001', $param);
        
        $response = array('status' => 'error', 'message' => 'Error', 'result' => $result);

        if (isset($result['status']) && $result['status'] === 'success') {
            $response = array('status' => $result['status'], 'message' => isset($result['text']) ? $result['text'] : Lang::line('msg_save_success'), 'result' => $result);
        } else {
            $response['message'] = isset($result['text']) ? $result['text'] : Lang::line('msg_save_error');
        }
        
        echo json_encode($response);
    }
    
    function documentEcmMapList() {
        jsonResponse($this->model->documentEcmMapListModel());
    }

    public function savedocumentassign() {        
        $postdata = Input::postData();
        $response = $this->model->savedocCommentLookupModel('DOC_DOCUMENT_WFM_ASSIGNMENT_DV_002', $postdata);
        
        if ($response['status'] == 'success') {
            $response = array(
                'status' => 'success',
                'message' => 'Амжилттай хадгалагдлаа',
            );
        } elseif ($response['status'] == 'error') {
            $response = array(
                'status' => 'error',
                'message' => $response['text'],
            );            
        } else {
            $response = array(
                'status' => 'error',
                'message' => issetParam($response['status']),
            );                        
        }

        echo json_encode($response); exit;
    }

    public function documentassign() {        
        $this->view->uniqid = getUID();
        $this->view->selectedRow = Input::post('selectedRow');

        $depCriteria = array(
            'parentId' => array(
                array(
                    'operator' => '=',
                    'operand' => ''
                )                
            )
        );
        $this->view->departmentList = $this->model->docCommentLookupModel('1545885281002188', $depCriteria);
        
        $response = array(
            'html' => $this->view->renderPrint('doc_process/documentassign_1', self::$viewPath),
            'title' => 'Шилжүүлэх',
            'uniqId' => $this->view->uniqid
        );
        echo json_encode($response); exit;
    }

    public function getDeparmentListJtreeData() {
        $response = $this->model->getDeparmentListJtreeDataModel(Input::get('parentId'), Input::get('parentNode'));
        echo json_encode($response); exit;
    }    

    public function documentassignDepartment() {        
        $depCriteria = array(
            'parentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Input::post('id')
                )                
            ),
            'departmentName' => array(
                array(
                    'operator' => 'like',
                    'operand' => '%'.Input::post('departmentName').'%'
                )                
            )
        );
        $departmentList = $this->model->docCommentLookupModel(1545885281002188, $depCriteria);

        if (Input::post('id')) {
            $depCriteria = array(
                'departmentId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('id')
                    )                
                )
            );        
            if (Input::post('positionId')) {
                $depCriteria['positionId'] = array(array(
                    'operator' => '=',
                    'operand' => Input::post('positionId')
                ));                
            }

            $employeeList = $this->model->docCommentLookupModel(1550554097703, $depCriteria);
        } else {
            $employeeList = array();
        }

        echo json_encode(array(
            'departmentList' => $departmentList,
            'employeeList' => $employeeList
        )); exit;
    }

    public function documentassignPosition() {        
        $depCriteria = array(
            'departmentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Input::post('id')
                )                
            )
        );
        $positionList = $this->model->docCommentLookupModel(1550802784817298, $depCriteria);

        echo json_encode($positionList); exit;
    }

    public function adddocument() {        
        $this->view->uniqid = Input::post('uniqid');
        $response = array(
            'html' => $this->view->renderPrint('doc_process/adddocument', self::$viewPath),
            'title' => 'Ирсэн бичиг бүртгэх',
            'uniqId' => $this->view->uniqid
        );
        echo json_encode($response); exit;
    }

    public function docParagraphCreate() {
        echo json_encode($this->model->docParagraphCreateModel()); exit;
    }

    public function documentEditComment() {
        $this->view->row = Input::post('selectedRow');
        $this->view->uniqid = getUID();
        $this->view->tinymceApiKey = 'luxfuboe0ozwfn4beq9j3rq1yn74cnmih2ychia8o1oxjdly';
        
        $this->load->model('mddoc', 'middleware/models/');
        $this->view->getFileInfo = $this->model->getFileDocComment($this->view->row);    
        echo $this->view->renderPrint('doc_comment/editComment', self::$viewPath);

        // $response = array(
        //     'html' => $this->view->renderPrint('doc_comment/editComment', self::$viewPath),
        //     'title' => 'Албан бичиг боловсруулалт', 
        //     'uniqId' => $this->view->uniqid
        // );
        // echo json_encode($response); exit;
    }

    public function docCommentCreate() {
        $content = file_get_contents('php://input');
        $this->view->uniqid = getUID();
        $result = array();

        if (!empty($content)) {
            $result = $this->model->docCommentCreateModel($content, $this->view->uniqid);
        }

        echo json_encode(array_merge(array(
            'uid' => $this->view->uniqid
        ), $result)); exit;
    }

    public function docCommentReply() {
        $content = json_decode(file_get_contents('php://input'), true);
        $result = array();

        if (!empty($content['body'])) {
            $result = $this->model->docCommentCreateModel($content['body'], $content['uid'], '1');
        }

        echo json_encode(array_merge(array(
            'uid' => $content['uid']
        ), $result)); exit;
    }    

    public function docParagraphChildCreate($id) {
        $content = Input::post('content');

        echo json_encode($this->model->docParagraphChildCreateModel($content, $id)); exit;
    }    

    public function getDocComments($id) {
        echo json_encode($this->model->getDocCommentsModel($id)); exit;
    }    

    public function htmltopdfDocDocument() {
        includeLib('PDF/Pdf');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        try {
            
            $site_url = defined('LOCAL_URL') ? LOCAL_URL : URL;
            
            $htmlContent = preg_replace('/(<img.*?src=")(?!http)(.*">)/', "$1$site_url/$2", Input::postNonTags('content'));
            $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
            $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
            
            $fileToSave = UPLOADPATH.Mdwebservice::$uploadedPath.'file_'.getUID();

            $_POST['left'] = $_POST['right'] = 10;

            $css = '<style type="text/css">';
            //$css .= file_get_contents('assets/custom/css/components-rounded.min.css');
            $css .= file_get_contents('assets/custom/css/print/snappyPrint.min.css');
            $css .= '</style>';
            
            $_POST['isIgnoreFooter'] = 1;
            
            $pdf = Pdf::createSnappyPdf('Portrait', 'A4');

            Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave, array(), true);
 
            echo json_encode(array('status' => 'success', 'filePath' => $fileToSave.'.pdf', 'message' => Lang::line('msg_save_success')));
            
        } catch (Exception $ex) {
            echo json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
        }
    }
    
    public function ntrBooknumberUpdate() {
        
    //        return false; // @MC 
        $currentDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        includeLib('Utils/Functions');
        $getParam = array ('id' => Input::post('id'));
        $result = Functions::runProcess('ntrGetTagSignatureList_004', $getParam);

        if (isset($result['result']['booknumber'])) {

            (String) $bookNumber = '';
            $id = Input::post('id');
            $strlen = strlen($result['result']['booknumber']);

            switch ($strlen) {
                case 1:
                    $bookNumber = '000' . $result['result']['booknumber'];
                    break;
                case 2:
                    $bookNumber = '00' . $result['result']['booknumber'];
                    break;
                case 3:
                    $bookNumber = '0' . $result['result']['booknumber'];
                    break;
                case 4:
                default:
                    $bookNumber = $result['result']['booknumber'];
                    break;
            }

            if ($bookNumber) {
                $checkBookDate = $this->db->GetOne("SELECT BOOK_DATE FROM NTR_SERVICE_BOOK WHERE ID = " . $this->db->Param(0), array($id));
                if ($checkBookDate) {
                    $this->db->AutoExecute('NTR_SERVICE_BOOK', array('BOOK_NUMBER' => $bookNumber), 'UPDATE', 'ID = ' . $id);
                } else {
                    $this->db->AutoExecute('NTR_SERVICE_BOOK', array('BOOK_NUMBER' => $bookNumber, 'BOOK_DATE' => $currentDate), 'UPDATE', 'ID = ' . $id);
                }
            }
        }

        echo json_encode(array('status' => 'success', 'txt' => 'success'));
    }
    
    public function erlPdfPrint($type = 'echo') {

        $_POST['top'] = '0px';
        $_POST['left'] = '0px';
        $_POST['bottom'] = '0px';
        $_POST['right'] = '0px';
        $_POST['isIgnoreFooter'] = '1';
        
        /**
         * Irgen der gadna buyu 172.169.100.64 en haygaar handj file aa awch chadhgui bsn tul dotood haygaar ni replace hiiw /Odbayar-n helsneer/
         */
        if (Config::getFromCache('ubegIsServerLink') == '1') {
            $htmlContent = "<!DOCTYPE html><html>
                <head>
                </head>
                <body>" . str_replace('//'.$_SERVER['HTTP_HOST'], '//'.$_SERVER['SERVER_ADDR'], Input::postNonTags('content')) . "</body>
                </html>";
        } elseif (Config::getFromCache('isNotaryServer') != '') {
            $htmlContent = "<!DOCTYPE html><html>
                <head>
                </head>
                <body>" . str_replace('//'.$_SERVER['HTTP_HOST'], '//' . Config::getFromCache('isNotaryServer'), Input::postNonTags('content')) . "</body>
                </html>";
        } else {
            $htmlContent = "<!DOCTYPE html><html>
                <head>
                </head>
                <body>" . Input::postNonTags('content') . "</body>
                </html>";           
        }
        
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        
        $css = '<style type="text/css">';
            $css .= 'body {margin: 0; padding: 0; } img { max-width: 99%; } .pagebreak { page-break-before: always; }';
        $css .= '</style>';


        $bpProcessFiles = glob(UPLOADPATH . 'temp_content/*/*/*/*');
        
        if (Config::getFromCache('deleteTempFiles')) { 
            foreach ($bpProcessFiles as $bpProcessFile) {
                if (is_file($bpProcessFile) && (time() - filemtime($bpProcessFile) > 60 * 60 * 24 * 1)) {
                    @unlink($bpProcessFile);
                }
            }
        }
        
        $tmp_dir = $this->model->bpTemplateUploadGetPath(UPLOADPATH . 'temp_content/');
        
        includeLib('PDF/Pdf');
        
        if ($type == 'echo') {
            $pdf = Pdf::createSnappyPdf();
        } else {
            $pdf = Pdf::createSnappyPdfResolverMerge('Portrait', 'A4');
        }
        $tempPdfFileName = 'temp-erp-pdf-' . getUID();
        
        Pdf::generateFromHtml($pdf, $css . $htmlContent, $tmp_dir.'/'.$tempPdfFileName, array(), false, false);                
        
        if (Input::post('compressData')) {
            includeLib('Compress/Compression');
            $compressData = Compression::decode_string_array(Input::post('compressData'));
            $selectRow = is_array($compressData['selectedRow']) ? $compressData['selectedRow'] : Compression::decode_string_array($compressData['selectedRow']);
            if (isset($selectRow['dataRow'])) {
                $selectRow = $selectRow['dataRow'];
            }
        
            if (Config::getFromCache('previewSaveLog') === '1') { /* (!Config::getFromCache('isNotaryServer') && Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { */
                self::saveElecLog($selectRow, null, $compressData, Input::post('smetaDataType'));
            }   
        }

        
        if ($type == 'echo') {
            echo URL . $tmp_dir . '/'.$tempPdfFileName.'.pdf';
        } else {
            return $tmp_dir . '/'.$tempPdfFileName.'.pdf';
        }
    }    

    public function erlPdfPrintUnlink() {
        @unlink(Input::post('url'));
    }

    public function erlPdfExport() {
        $_POST['top'] = '0px';
        $_POST['left'] = '0px';
        $_POST['bottom'] = '0px';
        $_POST['right'] = '0px';
        $_POST['isIgnoreFooter'] = '1';
        
        if (Config::getFromCache('ubegIsServerLink') == '1') {
            $htmlContent = "<!DOCTYPE html><html>
                <head>
                </head>
                <body>" . str_replace('//'. $_SERVER['HTTP_HOST'], '//'.$_SERVER['SERVER_ADDR'], Input::postNonTags('content')) . "</body>
                </html>";

        } elseif (Config::getFromCache('isNotaryServer') != '') {
            $htmlContent = "<!DOCTYPE html><html>
                <head>
                </head>
                <body>" . str_replace('//'.$_SERVER['HTTP_HOST'], '//' . Config::getFromCache('isNotaryServer'), Input::postNonTags('content')) . "</body>
                </html>";
        } else {
            
            $htmlContent = "<!DOCTYPE html><html>
                <head>
                </head>
                <body>" . Input::postNonTags('content') . "</body>
                </html>";       
        }
        
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        
        $css = '<style type="text/css">';
            $css .= 'body {margin: 0; padding: 0; } img { max-width: 99%; } .pagebreak { page-break-before: always; }';
        $css .= '</style>';
        
        if (Input::post('compressData')) {
            includeLib('Compress/Compression');
            $compressData = Compression::decode_string_array(Input::post('compressData'));
            $selectRow = is_array($compressData['selectedRow']) ? $compressData['selectedRow'] : Compression::decode_string_array($compressData['selectedRow']);
            if (isset($selectRow['dataRow'])) {
                $selectRow = $selectRow['dataRow'];
            }
            
            if (Config::getFromCache('previewSaveLog') === '1') { /* (!Config::getFromCache('isNotaryServer')  && Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { */ 
                self::saveElecLog($selectRow, null, $compressData, Input::post('smetaDataType'));
            }
        }
        
        includeLib('PDF/Pdf');
        
        $reportName = 'Veritech_ERP';
        $pdf = Pdf::createSnappyPdf();
        Pdf::setSnappyOutput($pdf, $css . $htmlContent, $reportName, false);
    }    

    /* Onlyoffice */

    public function canvasStampPos(){
        // $this->view->render('doc_comment/detail', self::$viewPath);  
        $this->view->uniqid = Input::get('uniqid');
        $this->view->pdfPath = Input::get('pdfPath');
        $this->view->pageStyle = Input::get('pageStyle');
        $this->view->render('doc_comment/poscanvas', self::$viewPath);
    }

    public function documentEcmMapListNew() {
        $this->view->uniqid = getUID();
        $metaDataIdArr = explode('_', Input::post('metaDataId'));
        $this->view->row = Input::post('selectedRow');
        $this->view->refStructureId = '1447239000602';
        $this->view->metaDataId = $metaDataIdArr[1];
        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();
        $this->view->rowId = Input::post('recordId');
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        $this->view->showPostponeHistory = $this->model->getShowPostponeHistory($this->view->rowId);
        $this->view->rowJson = json_encode($this->model->getDocMoreModelBaseInfo($this->view->rowId));
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->getFilePath = $this->view->getRow['filepath'];
        $this->view->docWfmAssignmentList = $this->view->renderPrint('doc_comment/docWfmAssignmentList', self::$viewPath);  
        $this->view->docCardClosedInfo = $this->view->renderPrint('doc_comment/docCardClosedInfo', self::$viewPath);  
        
        $html = $this->view->renderPrint('doc_comment/detail', self::$viewPath);  
        $response = array('html' => $html, 'uniqId' => $this->view->uniqid);
        
        echo json_encode($response); exit;
    }
    
    public function docWfmAssignmentList() {
        $this->view->rowId = Input::post('recordId');
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        $this->view->render('doc_comment/docWfmAssignmentList', self::$viewPath);  
    }
    
    public function docCardClosedInfo() {
        $this->view->rowId = Input::post('recordId');
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        $this->view->render('doc_comment/docCardClosedInfo', self::$viewPath);  
    }

    public function documentEcmMapListClean() {
        $this->view->uniqid = getUID();
        $metaDataIdArr = explode('_', Input::post('metaDataId'));
        $this->view->row = Input::post('selectedRow');
        $this->view->refStructureId = '1447239000602';
        $this->view->metaDataId = $metaDataIdArr[1];
        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();
        $this->view->rowId = Input::post('recordId');
        $this->view->getRow = $this->model->getDocMoreModel(Input::post('recordId'));
        $this->view->showPostponeHistory = $this->model->getShowPostponeHistory(Input::post('recordId'));
        $this->view->rowJson = json_encode($this->model->getDocMoreModelBaseInfo(Input::post('recordId')));
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->getFilePath = $this->view->getRow['filepath'];
        
        $html = $this->view->renderPrint('doc_comment/detailClean', self::$viewPath);  
        $response = array('html' => $html, 'uniqId' => $this->view->uniqid );
        echo json_encode($response); exit;
    }

    public function documentEcmMapListArchive() {
        $this->view->uniqid = getUID();
        $metaDataIdArr = explode('_', Input::post('metaDataId'));
        $this->view->row = Input::post('selectedRow');
        $this->view->refStructureId = '1447239000602';
        $this->view->metaDataId = $metaDataIdArr[1];
        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();
        $this->view->rowId = Input::post('recordId');
        $this->view->getRow = $this->model->getDocMoreArchiveModel(Input::post('recordId'));
        $this->view->rowJson = json_encode($this->model->getDocMoreArchiveModelBaseInfo(Input::post('recordId')));
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->getFilePath = $this->view->getRow['filepath'];
        $html = $this->view->renderPrint('doc_comment/detailArchive', self::$viewPath);  
        $response = array('html' => $html, 'uniqId' => $this->view->uniqid );
        echo json_encode($response); exit;
    }
    
    public function getDocWorkflowNextStatus() {
        $this->view->rowId = Input::post('rowId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->nextWfmStatusIds = $this->model->docNextWfmStatusIdsModel($this->view->rowId, $this->view->metaDataId);
        echo json_encode(array('data' => $this->view->nextWfmStatusIds, 'status' => 'success', 'message' => 'ok'));
        die;
    }
    
    public function documentComment() {

        $this->view->uniqid = getUID();    
        $this->view->rowId = Input::post('rowId');
        $this->view->typeId = Input::post('typeId');
        $this->view->isblank = Input::post('isblank', '1');

        if (Input::postCheck('metaDataId')){
            $metaDataIdArr = explode('_', Input::post('metaDataId'));
            $this->view->metaDataId = $metaDataIdArr[1];
        } else{
            $this->view->metaDataId = 1554813741229;
        }
        
        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();
        $this->view->refStructureId = '1447239000602';
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);

        $this->view->rowJson = json_encode($this->model->getDocMoreModelBaseInfo($this->view->rowId));
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->getFilePath = $this->model->getTemplateFileDocComment($this->view->typeId);

        if ($this->view->isblank === '2') {
            $this->view->getFilePath = $this->view->getRow['filepath'];
        }elseif ($this->view->isblank !== '2') {
            if (!empty($this->view->getFilePath)) {
                $pathInfo = pathinfo($this->view->getFilePath);
                $ext = $pathInfo['extension'];
                $docId = Input::post('rowId');
                $newPath = 'storage/uploads/process/doc_' . $docId . '.' . $ext;
                @copy($this->view->getFilePath, $newPath);
                $this->view->getFilePath = $newPath;
                $this->model->updateDocPath($this->view->rowId, $newPath);
            } else {
                $this->view->getFilePath = '';
            }
        }
        
        $this->view->docWfmAssignmentList = $this->view->renderPrint('doc_comment/docWfmAssignmentList', self::$viewPath);  

        $this->view->render('doc_comment/detail', self::$viewPath);
    }

    public function getDocStampPic(){
        $docid = Input::post('documentId');
        $row = $this->model->getDocStampPicModel($docid);
        echo json_encode($row);
    }
    
    public function documentCommentEdited() {
        $this->view->uniqid = getUID();    
        $this->view->rowId = Input::post('rowId');
        $this->view->parentId = Input::post('parentId');

        if (Input::postCheck('metaDataId')) {
            $metaDataIdArr = explode('_', Input::post('metaDataId'));
            $this->view->metaDataId = $metaDataIdArr[1];
        } else{
            $this->view->metaDataId = 1554813741229;
        }

        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();
        $this->view->refStructureId = '1447239000602';

        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        $this->view->rowJson = json_encode($this->model->getDocMoreModelBaseInfo($this->view->rowId));

        $this->view->getParentRow = $this->model->getDocMoreModel($this->view->parentId);
        $this->view->repDocNum = true;
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->parentPath = $this->view->getParentRow['filepath'];
        $this->view->docWfmAssignmentList = $this->view->renderPrint('doc_comment/docWfmAssignmentList', self::$viewPath);  

        /* $forcesave = $this->onlyofficeForceSave($this->view->parentPath);
        if($forcesave['status'] == 'success'){ */
            $pathInfo = pathinfo($this->view->parentPath);
            $ext = $pathInfo['extension'];
            $docId = $this->view->rowId;
            $newPath = 'storage/uploads/process/doc_' . $docId . '.' . $ext;
            @copy($this->view->getParentRow['filepath'], $newPath);
            $this->view->getFilePath = $newPath;
            $this->model->updateDocPath($this->view->rowId, $newPath);
            $this->view->render('doc_comment/detail', self::$viewPath);
        /* }
        else{
            echo json_encode($forcesave);
        } */
    }

    public function uploadOnlyOfficeUrl() {
        $fileSrc = Input::post('path');
        $pathInfo = pathinfo($fileSrc);
        $fileDesc = UPLOADPATH . 'signedDocument/file_' . getUID() . '.' . 'pdf';

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        if (($new_data = file_get_contents($fileSrc, false, stream_context_create($arrContextOptions))) === FALSE) {
            $result = array('status' => 'failed', 'message' => 'copy failed', 'path' => "");
        } else {
            file_put_contents($fileDesc, $new_data, LOCK_EX);
            $result = array('status' => 'success', 'message' => 'copy successful', 'path' => $fileDesc);
        }
        // if (!@copy($fileSrc, $fileDesc)) {
        //     array('status' => 'error', 'message' => 'copy failed');
        // }
        echo json_encode($result);
    }

    public function docDocxCopy(){
        $getFilePath = Input::post('path');
        $pathInfo = pathinfo($getFilePath);
        $ext = $pathInfo['extension'];
        $docId = Input::post('iconUniqId') ? Input::post('iconUniqId') : 'doc_' . getUID();
        
        $newPath = 'storage/uploads/process/' . $docId . '.' . $ext;
        
        if (!@copy($getFilePath, $newPath)) {
            array('status' => 'error', 'message' => 'copy failed');
        }
        $result = array('status' => 'success', 'message' => 'copy successful', 'path' => $newPath);
        echo json_encode($result);
    }

    public function replaceDocxTemplate(){
        $sourceFile = Input::post('sourceFile');
        $replaceArr = Input::post('replaceArr');
        try {
            if(is_array($replaceArr)){
                require_once(BASEPATH . LIBS . "Office/Phpword_0.18.1/index.php");
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($sourceFile);
                foreach ($replaceArr as $key => $value) {
                    $templateProcessor->setValue($value['tag'], $value['repValue']);
                }
                $templateProcessor->saveAs($sourceFile);
                echo json_encode(array("status" => 'success', "path" => $sourceFile));
            }else{
                echo json_encode(array("status" => 'error', "message" => "no replace tag array"));
            }
        } catch (\Throwable $th) {
            echo json_encode(array("status" => 'error', "message" => "error during operation, check filepath and extension", "exc" => $th));
        }
    }

    public function getDocumentSideBars(){
        $this->view->rowId = Input::post('rowid');
        $this->view->uniqid = Input::post('uniqid');
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        // $this->view->rowJson = json_encode($this->view->getRow);
        $this->view->rowJson = json_encode($this->model->getDocMoreModelBaseInfo($this->view->rowId));
        
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();

        $response = array(
            'left' => $this->view->renderPrint('doc_comment/sidebar_left', self::$viewPath),
            'right' => $this->view->renderPrint('doc_comment/sidebar_right', self::$viewPath)
        );
        echo json_encode($response); 
    }

    public function getDocumentSideBarsClean(){
        $this->view->rowId = Input::post('rowid');
        $this->view->uniqid = Input::post('uniqid');
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        // $this->view->rowJson = json_encode($this->view->getRow);
        $this->view->rowJson = json_encode($this->model->getDocMoreModelBaseInfo($this->view->rowId));
        
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();

        $response = array(
            'left' => $this->view->renderPrint('doc_comment/sidebarClean_left', self::$viewPath),
            'right' => $this->view->renderPrint('doc_comment/sidebarClean_right', self::$viewPath)
        );
        echo json_encode($response); 
    }

    public function getDocumentSideBarsArchive(){
        $this->view->rowId = Input::post('rowid');
        $this->view->uniqid = Input::post('uniqid');
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        $this->view->rowJson = json_encode($this->model->getDocMoreModelBaseInfo($this->view->rowId));
        
        $this->view->docComments = $this->model->docCommentModel($this->view->rowId); 
        $this->view->sideButtonConf = $this->model->getSideButtonConfModel();

        $response = array(
            'left' => $this->view->renderPrint('doc_comment/sidebarArchive_left', self::$viewPath),
            'right' => $this->view->renderPrint('doc_comment/sidebarArchive_right', self::$viewPath)
        );
        echo json_encode($response); 
    }

    public function getDocumentMainDiv(){
        $this->view->rowId = Input::post('rowid');
        $this->view->uniqid = Input::post('uniqid');
        $this->view->getRow = $this->model->getDocMoreModel($this->view->rowId);
        $response = array(
            'mainDiv' => $this->view->renderPrint('doc_comment/mainDiv', self::$viewPath)
        );
        echo json_encode($response); 
    }

    public function docxToPdfConverter(){
        $path =  Input::post('path');
        $url = URL . $path;
        $widget = new Mdwidget();
        $dockey = $widget->getDocEditorKey($path);
        $service_url = Config::getFromCacheDefault('DOC_SERVER_LOCAL', null, '') . '/ConvertService.ashx';
        $curl = curl_init($service_url);
        $data = array('async' => false, 
            'filetype' => 'docx', 
            'key' => $dockey, 
            'outputtype' => 'pdf', 
            // 'title' => 'Example', 
            'url' => $url);
        // var_dump($data);die;
        $curl_post_data = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $curl_response = curl_exec($curl);

        $response = array( 'status' => 'error', 'message' => 'Docx to pdf conversion service failed. Please contact service admin.' );
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
        }else{
            curl_close($curl);
            $xml=simplexml_load_string($curl_response);
            // $resultPath = '-1';
            if($xml->FileUrl->__toString() != '-1'){
                $resultPath = 'storage/uploads/process/' . basename($path, ".docx") . '_' .  getUID() .'.pdf';
                copy($xml->FileUrl->__toString(), $resultPath);
                $response = array( 'status' => 'success', 'url' => $resultPath, 'key' => $dockey );
                // $xml->FileUrl->__toString();
            }
        }
        echo json_encode($response);
    }

    public function setSignedPdfPath(){
        $id =   Input::post('id');
        $filename = Input::post('filename');
        $params = array(
            'FILE_PATH' => UPLOADPATH . "signedDocument/" . $filename,
        );
        $result = $this->db->AutoExecute('DOC_DOCUMENT', $params, 'UPDATE', " ID = '". $id ."'");
        echo json_encode($result);
    }

    public function office() {
        $widget = new Mdwidget();
        $this->view->fullname = Input::get('filename');
        $this->view->docname = Input::get('docname');
        $this->view->dcode = Input::get('dcode');
        $this->view->docId = Input::get('docId');


        $this->view->mode = isset($_GET['mode']) ? Input::get('mode') : 'edit';
        $this->view->edit = (isset($_GET['edit']) && $_GET['edit'] == 1) ? 'true' : 'false';
        $this->view->download = (isset($_GET['download']) && $_GET['download'] == 1) ? 'true' : 'false';
        $this->view->review = (isset($_GET['review']) && $_GET['review'] == 1) ? 'true' : 'false';

        if(!empty($this->view->fullname)){
            if (file_exists($this->view->fullname)) {
                $path_parts = pathinfo(basename($this->view->fullname));
                $this->view->filename = $path_parts['basename'];
                $this->view->ext = $path_parts['extension'];
                $this->view->doctype = $this->getDocumentType($this->view->filename);
                $this->view->dockey = $widget->getDocEditorKey($this->view->fullname);
                $this->view->render('doc_comment/office', self::$viewPath);
            }else{
                $this->view->render('doc_comment/nofile', self::$viewPath);
            }
        }else{
            $this->view->render('doc_comment/nodirectory', self::$viewPath);
        }
    }

    public function officeClean() {
        $this->view->contentid = Input::get('contentid');

        $this->view->ecmContentData = $this->model->getEcmContentData($this->view->contentid);
        // var_dump($this->view->ecmContentData);die;
        $this->view->ecmOfficeVersionData = $this->model->getEcmOfficeVersionData($this->view->contentid);

        $this->view->url = $this->view->ecmContentData['PHYSICAL_PATH'];
        $this->view->docname = $this->view->ecmContentData['FILE_NAME'];

        $widget = new Mdwidget();
        foreach ($this->view->ecmOfficeVersionData as $key => $value) {
            $this->view->ecmOfficeVersionData[$key]['KEY'] = $widget->getDocEditorKey($value['URL']);
        }

        $this->view->dcode = Input::get('dcode');
        $this->view->docId = Input::get('docId');
        $this->view->mode = isset($_GET['mode']) ? Input::get('mode') : 'edit';
        $this->view->edit = (isset($_GET['edit']) && $_GET['edit'] == 1) ? 'true' : 'false';
        $this->view->download = (isset($_GET['download']) && $_GET['download'] == 1) ? 'true' : 'false';
        $this->view->review = (isset($_GET['review']) && $_GET['review'] == 1) ? 'true' : 'false';

        if(!empty($this->view->url)){
            if (file_exists($this->view->url)) {
                $path_parts = pathinfo(basename($this->view->url));
                $this->view->filename = $path_parts['basename'];
                $this->view->ext = $path_parts['extension'];
                $this->view->doctype = $this->getDocumentType($this->view->filename);
                $this->view->dockey = $widget->getDocEditorKey($this->view->url);
                $this->view->render('doc_comment/officeClean', self::$viewPath);
            }else{
                $this->view->render('doc_comment/nofile', self::$viewPath);
            }
        }else{
            $this->view->render('doc_comment/nodirectory', self::$viewPath);
        }
    }

    public function GenerateRevisionId($expected_key) {
        if (strlen($expected_key) > 20) $expected_key = crc32( $expected_key);
        $key = preg_replace("[^0-9-.a-zA-Z_=]", "_", $expected_key);
        $key = substr($key, 0, min(array(strlen($key), 20)));
        return $key;
    }

    public function getDocumentType($filename) {
        $ext = strtolower('.' . pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, array(".doc", ".docx", ".docm",
                                 ".dot", ".dotx", ".dotm",
                                 ".odt", ".fodt", ".ott", ".rtf", ".txt",
                                 ".html", ".htm", ".mht",
                                 ".pdf", ".djvu", ".fb2", ".epub", ".xps"))) return "text";
        if (in_array($ext, array(".xls", ".xlsx", ".xlsm",
                                    ".xlt", ".xltx", ".xltm",
                                    ".ods", ".fods", ".ots", ".csv"))) return "spreadsheet";
        if (in_array($ext,  array(".pps", ".ppsx", ".ppsm",
                                     ".ppt", ".pptx", ".pptm",
                                     ".pot", ".potx", ".potm",
                                     ".odp", ".fodp", ".otp"))) return "presentation";
        return "";
    }
    
    /* amadeus */
    
    public function amadeusInsertSms() {
        
        $this->view->smsTypeData = $this->model->getAirSmsTypeModel();
        $this->view->uniqId = getUID();
        $this->view->title = 'AIR_MSG_INSERT_DV Нэмэх';
        
        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('amadeus/index', self::$viewPath);
            $this->view->render('footer');
        } else {
            
            $response = array(
                'html' => $this->view->renderPrint('amadeus/index', self::$viewPath),
                'title' => $this->view->title, 
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response); exit;
        }
    }
    
    public function screenCaptureSms($smsTypeId = '1', $isDialog = '1') {
        
        $this->view->smsTypeId = $smsTypeId;
        $postData = Input::postData();
        if (isset($postData['paramData']) && $postData['paramData']) {
            foreach ($postData['paramData'] as $key => $row) {
                if (isset($row['name']) && $row['name'] === 'smsTypeId') {
                    $this->view->smsTypeId = $row['value'];
                }
            }
        }
        
        $this->view->uniqId = getUID();
        $this->view->seeBtn = false;
        $this->view->title = 'Screen Capture Information';
        
        if (!is_ajax_request()) {
            $this->view->seeBtn = true;
            $this->view->render('header');
            $this->view->render('amadeus/capture', self::$viewPath);
            $this->view->render('footer');
        } else {
            
            $response = array(
                'Html' => $this->view->renderPrint('amadeus/capture', self::$viewPath),
                'Title' => $this->view->title, 
                'uniqId' => $this->view->uniqId, 
                'Height' => '500', 
                'Width' => '900', 
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response); exit;
        }
    }
    
    public function saveScSms() {
        $response = $this->model->saveScSmsModel();
        echo json_encode($response);
    }

    public function addBspForm() {
        
        $this->view->smsTypeData = $this->model->getAirSmsTypeModel();
        $this->view->uniqId = getUID();
        $this->view->title = 'BSP Нэмэх';
        $this->view->seeBtn = false;
        if (!is_ajax_request()) {
            $this->view->seeBtn = true;
            $this->view->render('header');
            $this->view->render('amadeus/index', self::$viewPath);
            $this->view->render('footer');
        } else {
            
            $response = array(
                'Html' => $this->view->renderPrint('amadeus/bspreport', self::$viewPath),
                'Title' => $this->view->title, 
                'uniqId' => $this->view->uniqId, 
                'Height' => '200', 
                'Width' => '500', 
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response); exit;
        }
    }
    
    public function saveBspReport() {
        (Array) $response = array();
        
        try {
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $currentDate = Date::currentDate();
            $fileDataArr = Input::fileData();
            $bspConfig = Config::getFromCache('BSP_URL'); //https://iis101.veritech.mn/bsp/index.aspx
            
            if (isset($fileDataArr['airSmsFile']['name'])) {
                foreach ($fileDataArr['airSmsFile']['name'] as $fileKey => $fileData) {

                    $newFName   = 'bspReport_' . getUID();
                    $fileExtension = strtolower(substr($fileDataArr['airSmsFile']['name'][$fileKey], strrpos($fileDataArr['airSmsFile']['name'][$fileKey], '.') + 1));
                    $fileName      = $newFName . '.' . $fileExtension;
                    $filePath      = UPLOADPATH . 'airsms/';

                    $file_name = $fileDataArr['airSmsFile']['name'][$fileKey];
                    $file_size = $fileDataArr['airSmsFile']['size'][$fileKey];
                    $ext = substr($fileDataArr['airSmsFile']['name'][$fileKey], strrpos($fileDataArr['airSmsFile']['name'][$fileKey], '.') + 1);
                    
                    FileUpload::SetFileName($fileName);
                    FileUpload::SetTempName($fileDataArr['airSmsFile']['tmp_name'][$fileKey]);
                    FileUpload::SetUploadDirectory($filePath);
                    FileUpload::SetValidExtensions(explode(',', 'pdf'));
                    FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
                    $uploadResult  = FileUpload::UploadFile();

                    if ($uploadResult && !file_exists(URL . $filePath . $fileName)) {
                        
                        $fullFilePath = ((URL === 'http://portal.local/') ? 'http://192.168.100.185/' : URL) . $filePath . $fileName;
                        $filePath = $filePath . $fileName;
                        $contentId = getUID();
                        
                        $data = array(
                                    'CONTENT_ID' => $contentId,
                                    'FILE_NAME' => $file_name,
                                    'PHYSICAL_PATH' => $fullFilePath,
                                    'THUMB_PHYSICAL_PATH' => $filePath,
                                    'FILE_SIZE' => $file_size,
                                    'FILE_EXTENSION' => $ext,
                                    'CREATED_DATE' => $currentDate,
                                    'CREATED_USER_ID' => $sessionUserKeyId,
                                    'TYPE_ID' => '67',
                                    'WFM_DESCRIPTION' => 'BSP report',
                                    'DESCRIPTION' => ''
                                );
                        
                        $result = $this->db->AutoExecute('ECM_CONTENT', $data);
                        
                        if ($result) {
                            $ch = curl_init($bspConfig . '?bspurl=' . $fullFilePath . '&created_user_id='. $sessionUserKeyId .'&department_id=' . Ue::sessionUserKeyDepartmentId().'&content_id=' . $contentId);
        
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'Content-Length: 0', 'Content-Type: text/html'));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                            $output = curl_exec($ch);
                            $err = curl_error($ch);

                            curl_close($ch);

                            if ($err) {
                                
                                $this->db->AutoExecute('ECM_CONTENT', array('DESCRIPTION' => $err), "UPDATE", "CONTENT_ID = $contentId");
                                $response = array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "fileName: <strong>". $file_name ."</strong><br>" . $err);
                                
                            } else {
                                if ($output) {
                                    $this->db->AutoExecute('ECM_CONTENT', array('DESCRIPTION' => $output), "UPDATE", "CONTENT_ID = $contentId");
                                    $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
                                }
                            }
                            
                        }

                    } else {
                        throw new Exception("Файл олдсонгүй!"); 
                    }

                }
            } else {
                throw new Exception("Файл олдсонгүй!"); 
            }
            
        } catch (Exception $e) {
            $response = array('status' => 'warning', 'message' => $e->getMessage());
        }
        echo json_encode($response);
    }
    
    /* end amadeus */
    
    public function trydownload() {
        $companyKeyId = Input::post('companyKeyId');
        $getConfig = Config::getFromCache('ubegScanLink');

        $filesString = file_get_contents($getConfig.'?scan_id='.$companyKeyId.'&filename='.$companyKeyId.'.lst&uid='.getUID());

        if (strpos($filesString, '.tif') === false) {
            echo json_encode(array('status' => 'error', 'message' => 'File not found!', 'showNotify' => true)); exit;
        }

        $filesString = explode(PHP_EOL, $filesString);

        $response = $this->model->tryElectronRegisterLegalBulkScanModel($filesString, $companyKeyId);
        $response = array_merge($response, array('showNotify' => true));
        echo json_encode($response); exit;
    }

    public function updatePathEcmContent() {
        $response = $this->model->updatePathEcmContentModel(Input::post('selectedRow'), Input::post('fileName'));
        echo json_encode($response);
    }
    
    public function previewErsFile() {
        
        $postData = Input::postData();
        $this->view->dataRow = $postData['selectedRow'];
        
        try {
            unset($_POST);

            $_POST['metaDataId'] = $this->view->dataRow['printmetadataid'];
            $_POST['dataRow'] = $this->view->dataRow;
            $_POST['isProcess'] = 'true';
            $_POST['templateId'] = '';

            $tempCtrl = Controller::loadController('Mdtemplate', 'middleware/controllers/');

            $this->view->templateArr = $tempCtrl->checkCriteria(true);

            if ($this->view->templateArr) {
                $this->view->templateId = (isset($this->view->templateArr[0]) && isset($this->view->templateArr[0]['ID'])) ? $this->view->templateArr[0]['ID'] : (isset($this->view->templateArr['ID']) ? $this->view->templateArr['ID'] : '');

                unset($_POST);

                $_POST = array (
                            'dataRow' => $this->view->dataRow,
                            'metaDataId' => $this->view->dataRow['printmetadataid'],
                            'print_options' => 
                            array (
                                'numberOfCopies' => '1',
                                'isPrintNewPage' => '1',
                                'isShowPreview' => '1',
                                'isPrintPageBottom' => '0',
                                'isSettingsDialog' => '0',
                                'isPrintPageRight' => '0',
                                'isPrintSaveTemplate' => '0',
                                'pageOrientation' => 'portrait',
                                'paperInput' => 'portrait',
                                'pageSize' => 'a4',
                                'printType' => '1col',
                                'templateMetaIds' => '',
                                'templates' => array (0 => $this->view->templateId),
                                'templateIds' => $this->view->templateId,
                            ),
                            'processId' => '',
                        );

                $this->view->templateRes = $tempCtrl->printByProcess(true, false);

                $this->view->uniqid = $this->view->uniqId = getUID();
                
                $this->view->title = $this->view->dataRow['enquiry'];
                $this->view->row = Input::post('selectedRow');

                $this->view->refStructureId = '1447239000602';
                $this->view->metaDataId = $this->view->dataRow['printmetadataid'];
                $this->view->rowId = Input::post('recordId');
                
                $this->view->sideBarLeft = $this->view->renderPrint('sidebar_left', 'middleware/views/document/erl/preview/');
                $this->view->sideBarRight = $this->view->renderPrint('sidebar_right', 'middleware/views/document/erl/preview/');

                $html = $this->view->renderPrint('erl/preview/detail', self::$viewPath);  

                $response = array('Html' => $html, 'uniqId' => $this->view->uniqid, 'Title' => $this->view->title );
            } else {
                $response = array('status' => 'error', 'text' => 'template олдсонгүй');
            }

            echo json_encode($response); exit;    
        } catch (Exception $ex) {
            echo json_encode(array('status' => 'warning', 'text' => $e->getMessage()));
        }
        
    }
    
    public function previewErsFileSave() {
        
        try {
            
            $postData = Input::postData();
            $currentDate = Date::currentDate();
            $param = array (
                        'weblink' => 'mddoc/previewErsFile',
                        'bookNumber' => NULL,
                        'companyKeyId' => NULL,
                        'stateRegNumber' => NULL,
                        'paperNumber' => $postData['paperNumber'],
                        'enquiry' => $postData['dataRow']['enquiry'],
                        'bodyText' => NULL,
                        'CMP_PAPER_BOOK_DTL_DV' => 
                        array (
                            'id' => NULL,
                            'bookId' => NULL,
                            'bookTypeId' => '23',
                            'fromDepartmentId' => NULL,
                            'fromEmployeeKeyId' => NULL,
                            'manufacturerId' => NULL,
                            'toCompanyKeyId' => NULL,
                            'toDepartmentId' => NULL,
                            'toEmployeeKeyId' => NULL,
                            'paperTypeId' => '21543894571157',
                            'paperStatusId' => '21543754238624',
                            'startNumber' => $postData['paperNumber'],
                            'endNumber' => $postData['paperNumber'],
                            'inQty' => '1',
                            'outQty' => NULL,
                            'description' => NULL,
                            'createdDate' => NULL,
                            'createdUserId' => NULL,
                            'refBookDtlId' => NULL,
                            'refPaperTypeId' => $postData['dataRow']['papertypeid'],
                            'CMP_PAPER_BOOK_DTL_DV2' => 
                            array (
                                'id' => NULL,
                                'bookId' => NULL,
                                'bookTypeId' => '24',
                                'fromDepartmentId' => '1467274989091',
                                'fromEmployeeKeyId' => '22222',
                                'manufacturerId' => NULL,
                                'toDepartmentId' => NULL,
                                'toEmployeeKeyId' => NULL,
                                'toCompanyKeyId' => NULL,
                                'paperTypeId' => '21543894571157',
                                'paperStatusId' => '21543754238624',
                                'startNumber' => $postData['paperNumber'],
                                'endNumber' => $postData['paperNumber'],
                                'inQty' => NULL,
                                'outQty' => '1',
                                'description' => NULL,
                                'createdDate' => NULL,
                                'createdUserId' => NULL,
                                'refBookDtlId' => NULL,
                                'refPaperTypeId' => $postData['dataRow']['papertypeid'],
                            ),
                        ),
                        'id' => NULL,
                        'paperTypeId' => $postData['dataRow']['papertypeid'],
                        'bookTypeId' => '9',
                        'icon' => 'storage/uploads/meta/menu/metamenu_1538629859468492_1565070503108728.jpg',
                        'bookDate' => $currentDate,
                        'createdDate' => $currentDate,
                        'createdUserId' => NULL,
                        'modifiedDate' => NULL,
                        'modifiedUserId' => NULL,
                    );

            $result = WebService::caller('WSDL-DE', SERVICE_FULL_ADDRESS, 'CMP_ENQUIRY_USED_DV_001', 'return', $param, 'serialize');
            echo json_encode($result); 
            
        } catch (Exception $ex) {
            echo json_encode(array('status' => 'warning', 'text' => $e->getMessage()));
        }
        
    }
    
    public function afisSaveControl() {
        $response = $this->model->afistSaveControlModel();
        echo json_encode($response);
    }
    
    public function cropByImageForm() {
        $this->view->uniqId = getUID();
        $this->view->imagePath = 'storage/uploads/ecm_content/file_1576051782234469.jpg';
        
        $this->view->width = Input::numeric('width');
        $this->view->height = Input::numeric('height');
        
        if (Input::post('base64img')) {
            $this->view->imagePath = Input::post('base64img');

            $type = pathinfo($this->view->imagePath, PATHINFO_EXTENSION);
            $data = file_get_contents($this->view->imagePath);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        ini_set('allow_url_fopen', 1);
        list($width, $height, $type, $attr) = getimagesize($this->view->imagePath);
        
        $this->view->imageWidth = $width;
        $this->view->imageHeigth = $height;
        
        $response = array(
            'uniqId' => $this->view->uniqId,
            'html' => $this->view->renderPrint('crop/cropper', 'middleware/views/asset/'),
            'title' => Lang::line('Crop_image'), 
            'imageWidth' => ($this->view->imageWidth > 200) ? $this->view->imageWidth : 200, 
            'imageHeigth' => ($this->view->imageHeigth > 200) ? $this->view->imageHeigth : 200,
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function cropImg() {
        ini_set('memory_limit', '-1');
        $tempdir = BASEPATH.UPLOADPATH.'crop/';
        $fullPath = UPLOADPATH . 'crop/';
        
        if (!is_dir($tempdir)) {
            mkdir($tempdir, 0777);
        }
        
        $getUID = getUID();
        $fileName = $tempdir . $getUID .'.jpg';
        $filePath = $fullPath . $getUID .'.jpg';
        
        $jpeg_quality = 90;
        $src = Input::post('image_path');
        
        if (!Input::post('x') && !Input::post('x') && !Input::post('x') && !Input::post('x')) {
            $type = pathinfo($src, PATHINFO_EXTENSION);
            $data = file_get_contents($src);
            
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $filePath = base64_to_jpeg(base64_encode($data), $filePath);
            
            echo json_encode(array('base64' => $base64, 'filePath' => $filePath, 'status' => 'success'));
            die;
        }
        
        $x = Input::post('x');
        $y = Input::post('y');
        $w = Input::post('w');
        $h = Input::post('h');
        
        $pos  = strpos($src, ';');
        $ftype = explode(':', substr($src, 0, $pos))[1];
        
        switch ($ftype) {
            case 'image/png':
                $img_r = imagecreatefrompng($src);
                break;
            default:
                $img_r = imagecreatefromjpeg($src);
                break;
        }
        
        $dst_r = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w, $h);

        imagejpeg($dst_r, $filePath, $jpeg_quality);
        imagedestroy($dst_r); 

        if (file_exists($filePath)) {
            $type = pathinfo($filePath, PATHINFO_EXTENSION);
            $data = file_get_contents($filePath);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            echo json_encode(array('base64' => $base64,'filePath' => $filePath,  'status' => 'success'));
        } else {
            echo json_encode(array('base64' => '', 'status' => 'success', 'text' => 'Crop хийхэд алдаа гарлаа'));
        }

    }
    
    public function saveFingerDataTemp() {
        
        $base64Img = Input::post('operatorFinger');
        $citizenFilePath = Input::post('finger');
        $registerNumber = Input::post('registerNumber');
        $civilId = Input::post('civilId');
        $serviceType = Input::post('serviceType');
        $methodCode = Input::post('methodCode');

        $getUID = getUID();
        $sessionUserId = Ue::sessionUserId();
        
        if (Config::getFromCache('deleteTempFiles')) {         
            $bpProcessFiles = glob(UPLOADPATH . 'finger/customer/*');
            
            foreach ($bpProcessFiles as $bpProcessFile) {
                if (is_file($bpProcessFile) && (time() - filemtime($bpProcessFile) > 60 * 60 * 0.5 * 1)) { /* 30 min tutam ustgah */
                    @unlink($bpProcessFile);
                }
            }
        }
        
        $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/customer/', false);
        $filePath = base64_to_jpeg($base64Img, $imagePath . $getUID .'.jpg' );
        $filePath1 = base64_to_jpeg($base64Img, $imagePath . getUID() .'.jpg' );
        
        $citizenData = array('filePath' => $citizenFilePath, 'stateRegNumber' => $registerNumber, 'civilId' => $civilId, 'base64Img' => $base64Img);
        
        $response = array('status' => 'warning', 'message' => Lang::line('msg_error'));
        
        $operatorData = $this->db->GetRow("SELECT 
                                                t1.FILE_PATH, 
                                                lower(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER, 
                                                T3.LAST_NAME, 
                                                T3.FIRST_NAME
                                            FROM UM_USER t0
                                            LEFT JOIN um_user_finger t1 ON t0.USER_ID = t1.USER_ID
                                            INNER JOIN um_system_user t2 ON t0.SYSTEM_USER_ID = t2.USER_ID
                                            INNER JOIN base_person t3 ON t2.PERSON_ID = t3.PERSON_ID
                                            WHERE t2.USER_ID = $sessionUserId");

        if (!isset($operatorData['STATE_REG_NUMBER'])) {
            echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>РЕГИСТРИЙН ДУГААР</strong><br> эсвэл <br><strong>АЖИЛТНЫ ХУРУУНЫ ХЭЭНИЙ ЗУРАГ</strong> олдсонгүй', 'status' => 'error'));
            die;
        }
        
        if (!file_exists($filePath)) {
            echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ХЭЭГЭЭ БҮРТГҮҮЛЭЭГҮЙ<strong> байна', 'status' => 'error'));
            die;
        }

        $authType = '3';
        $param = array(
            "auth" => array(
                "citizen" => array(
                    "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                    "civilId" => issetParam($citizenData['civilId']),        // Иргэний регистрийн дугаар
                    "fingerprint" => $citizenData['filePath'],          // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                    "authType" => $authType,
                ),
                "operator" => array(
                    "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                    "fingerprint" => $filePath1 // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                ),
            ),
            "regnum" => $citizenData['stateRegNumber'],
            "civilId" => issetParam($citizenData['civilId']),                 // Иргэний регистрийн дугаар
            'citizenRegnum' => $citizenData['stateRegNumber'],
            'citizenFingerPrint' => $citizenData['filePath'],
        );

        $this->load->model('mdintegration', 'middleware/models/');
        
        $configData = array();
        $processRow['WS_URL'] = 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL';
        
        if (Input::post('serviceType') === '2') {
            $processRow['CLASS_NAME'] = 'WS100133_getCitizenDeceaseInfo';

            if ($methodCode) {
                
                $configParam = array(
                    'processCode' => $methodCode,
                    'typeCode' => 'citizen_idcard_info',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
            }
            $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
            $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100101_getCitizenIDCardInfo');
            
        } else {

            if (Input::post('isaddress')) {
                $configParam = array(
                    'processCode' => $methodCode,
                    'typeCode' => 'citizen_addressinfo',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
                $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100103_getCitizenAddressInfo');
            } else {
                $configParam = array(
                    'processCode' => $methodCode,
                    'typeCode' => 'citizen_idcard_info',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
                $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100101_getCitizenIDCardInfo');
            }
            
        }
        
        $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], $processRow['WS_URL']);
        $result = $this->model->callXypService($processRow, $param);

        if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
            $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
        } else {
            //unlink($citizenData['filePath']);
            
            $result['data']['return']['response']['image'] = isset($result['data']['return']['response']['image']) ? base64_to_jpeg($result['data']['return']['response']['image'], UPLOADPATH . 'finger/customer/'. $getUID .'.jpg' ) : '';
            $response = array('message' => isset($result['data']['return']['resultmessage']) ? $result['data']['return']['resultmessage'] : 'Холбогдож чадсангүй', 'status' => 'success', 'data' => issetParamArray($result['data']['return']['response']));
            if ($processRow['CLASS_NAME'] === 'WS100101_getCitizenIDCardInfo' /* && Config::getFromCacheDefault('CALL_WITH_ADDRESSINFO') === '1' */) {
                $timestamp = (int) Input::post('timestamp');
                $processRow['WS_URL'] = 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL';

                $configParam = array(
                    'processCode' => $methodCode,
                    'typeCode' => 'citizen_addressinfo',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
                $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100103_getCitizenAddressInfo');
                $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], $processRow['WS_URL']);
                
                $this->load->model('mdintegration', 'middleware/models/');
                $resultAddress = $this->model->callXypService($processRow, $param, true, $timestamp);

                $response['addressinfo'] = $addressinfo = issetParamArray($resultAddress['data']['return']['response']);
                $response['addressinfo1'] = issetParamArray($resultAddress);

                if (issetParam($response['data']['aimagcitycode']) !== '' && issetParam($response['data']['aimagcityname']) !== '' && issetParam($addressinfo['aimagcitycode']) !== '' && issetParam($addressinfo['aimagcityname']) !== '') {

                    if (issetParam($addressinfo['fulladdress']) !== '') {
                        $addressinfo['fulladdress'] = str_replace(',', '@', $addressinfo['fulladdress']);
                    }

                    $response['data']['addressdetail'] = issetDefaultVal($addressinfo['addressdetail'], issetParam($response['data']['addressdetail']));
                    $response['data']['passportaddress'] = issetDefaultVal($addressinfo['fulladdress'], issetParam($response['data']['passportaddress']));
                    $response['data']['addressstreetname'] = issetDefaultVal($addressinfo['addressstreetname'], issetParam($response['data']['addressstreetname']));
                    $response['data']['aimagcitycode'] = issetDefaultVal($addressinfo['aimagcitycode'], issetParam($response['data']['aimagcitycode']));
                    $response['data']['aimagcityname'] = issetDefaultVal($addressinfo['aimagcityname'], issetParam($response['data']['aimagcityname']));
                    $response['data']['bagkhoroocode'] = issetDefaultVal($addressinfo['bagkhoroocode'], issetParam($response['data']['bagkhoroocode']));
                    $response['data']['bagkhorooname'] = issetDefaultVal($addressinfo['bagkhorooname'], issetParam($response['data']['bagkhorooname']));
                    $response['data']['fulladdress'] = issetDefaultVal($addressinfo['fulladdress'], issetParam($response['data']['fulladdress']));
                    $response['data']['soumdistrictcode'] = issetDefaultVal($addressinfo['soumdistrictcode'], issetParam($response['data']['soumdistrictcode']));
                    $response['data']['soumdistrictname'] = issetDefaultVal($addressinfo['soumdistrictname'], issetParam($response['data']['soumdistrictname']));
                } else {
                    $response['addressinfo'] = '0';
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response); exit;
    }
    
    public function callCitizenCardInfo() {
        
        $base64Img = Input::post('operatorFinger');
        $citizenFilePath = Input::post('finger');
        $registerNumber = Input::post('registerNumber');
        $getUID = getUID();
        $sessionUserId = Ue::sessionUserId();
        
        if (Config::getFromCache('deleteTempFiles')) {         
            $bpProcessFiles = glob(UPLOADPATH . 'finger/customer/*');
            
            foreach ($bpProcessFiles as $bpProcessFile) {
                if (is_file($bpProcessFile) && (time() - filemtime($bpProcessFile) > 60 * 60 * 0.5 * 1)) { /* 30 min tutam ustgah */
                    @unlink($bpProcessFile);
                }
            }
        }
        
        $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/customer/', false);
        $filePath = base64_to_jpeg($base64Img, $imagePath . $getUID .'.jpg' );
        
        $citizenData = array('filePath' => $citizenFilePath, 'stateRegNumber' => $registerNumber, 'base64Img' => $base64Img);
        
        $response = array('status' => 'warning', 'message' => Lang::line('msg_error'));
        
        $operatorData = $this->db->GetRow("SELECT 
                                                T2.CERTIFICATE_SERIAL_NUMBER,
                                                LOWER(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER
                                            FROM UM_USER T0 
                                            INNER JOIN UM_SYSTEM_USER T1 ON T0.SYSTEM_USER_ID = T1.USER_ID
                                            INNER JOIN UM_USER_MONPASS_MAP T2 ON T0.USER_ID = T2.USER_ID
                                            INNER JOIN BASE_PERSON t3 ON t1.PERSON_ID = t3.PERSON_ID
                                            WHERE T1.USER_ID = ". $this->db->Param(0) ." AND T2.IS_ACTIVE = 1", array($sessionUserId));


        if (!isset($operatorData['STATE_REG_NUMBER'])) {
            echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>РЕГИСТРИЙН ДУГААР</strong><br> эсвэл <br><strong>АЖИЛТНЫ ХУРУУНЫ ХЭЭНИЙ ЗУРАГ</strong> олдсонгүй', 'status' => 'error'));
            die;
        }

        $authType = '3';
        $param = $paramAddress = array(
            "auth" => array(
                "citizen" => array(
                    "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                    "fingerprint" => $citizenData['filePath'],          // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                    "authType" => $authType,
                ),
                "operator" => array(
                    "regnum" => $operatorData['STATE_REG_NUMBER'],      // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                    "fingerprint" => '',                                // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                    'certFingerprint' => $operatorData['CERTIFICATE_SERIAL_NUMBER'],//$operatorData['CERTIFICATE_SERIAL_NUMBER'], //toon gariin usegiin cerial dugaar
                    'signature' => Input::post('signature'),       //rd + '.' + timestamp
                ),
            ),
            "regnum" => $citizenData['stateRegNumber'],
        );
        
        $timestamp = (int) Input::post('timestamp');
                
        $processRow['WS_URL'] = 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL';
        $processRow['CLASS_NAME'] = (Input::post('isaddress')) ? 'WS100103_getCitizenAddressInfo' : 'WS100101_getCitizenIDCardInfo';
        
        $this->load->model('mdintegration', 'middleware/models/');
        $result = $this->model->callXypService($processRow, $param, true, $timestamp);

        if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
            $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
        } else {
            $result['data']['return']['response']['image'] = isset($result['data']['return']['response']['image']) ? base64_to_jpeg($result['data']['return']['response']['image'], UPLOADPATH . 'finger/customer/'. $getUID .'.jpg' ) : '';
            $response = array('message' => isset($result['data']['return']['resultmessage']) ? $result['data']['return']['resultmessage'] : 'Холбогдож чадсангүй', 'status' => 'success', 'data' => issetParamArray($result['data']['return']['response']));
            
            if ($processRow['CLASS_NAME'] === 'WS100101_getCitizenIDCardInfo' /* && Config::getFromCacheDefault('CALL_WITH_ADDRESSINFO') === '1' */) {
                $timestamp = (int) Input::post('timestamp');
                $processRow['WS_URL'] = 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL';
                $processRow['CLASS_NAME'] = 'WS100103_getCitizenAddressInfo';

                $this->load->model('mdintegration', 'middleware/models/');
                $resultAddress = $this->model->callXypService($processRow, $paramAddress, true, $timestamp);
                $response['addressinfo'] = $addressinfo = issetParamArray($resultAddress['data']['return']['response']);
                $response['addressinfo1'] = issetParamArray($resultAddress);

                if (issetParam($response['data']['aimagcitycode']) !== '' && issetParam($response['data']['aimagcityname']) !== '' && issetParam($addressinfo['aimagcitycode']) !== '' && issetParam($addressinfo['aimagcityname']) !== '') {

                    if (issetParam($addressinfo['fulladdress']) !== '') {
                        $addressinfo['fulladdress'] = str_replace(',', '@', $addressinfo['fulladdress']);
                    }

                    $response['data']['addressdetail'] = issetDefaultVal($addressinfo['addressdetail'], issetParam($response['data']['addressdetail']));
                    $response['data']['passportaddress'] = issetDefaultVal($addressinfo['fulladdress'], issetParam($response['data']['passportaddress']));
                    $response['data']['addressstreetname'] = issetDefaultVal($addressinfo['addressstreetname'], issetParam($response['data']['addressstreetname']));
                    $response['data']['aimagcitycode'] = issetDefaultVal($addressinfo['aimagcitycode'], issetParam($response['data']['aimagcitycode']));
                    $response['data']['aimagcityname'] = issetDefaultVal($addressinfo['aimagcityname'], issetParam($response['data']['aimagcityname']));
                    $response['data']['bagkhoroocode'] = issetDefaultVal($addressinfo['bagkhoroocode'], issetParam($response['data']['bagkhoroocode']));
                    $response['data']['bagkhorooname'] = issetDefaultVal($addressinfo['bagkhorooname'], issetParam($response['data']['bagkhorooname']));
                    $response['data']['fulladdress'] = issetDefaultVal($addressinfo['fulladdress'], issetParam($response['data']['fulladdress']));
                    $response['data']['soumdistrictcode'] = issetDefaultVal($addressinfo['soumdistrictcode'], issetParam($response['data']['soumdistrictcode']));
                    $response['data']['soumdistrictname'] = issetDefaultVal($addressinfo['soumdistrictname'], issetParam($response['data']['soumdistrictname']));
                } else {
                    $response['addressinfo'] = '0';
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response); exit;
    }
    
    public function getXypInformationData() {

        $base64Img = Input::post('operatorFinger');
        $filePath = Input::post('finger');
        
        $propertyNumber = Str::lower(Input::post('propertyNumber')); 'ү22'; // жижигээр бичнэ
        $legalEntityNumber = Str::lower(Input::post('legalEntityNumber')); 'ү22'; // жижигээр бичнэ
        $stateRegNumber = Str::lower(Input::post('stateRegNumber'));
        $civilId = Input::post('civilId');
        
        $typeId = Input::post('typeId');
        $response = array('status' => 'warning', 'message' => Lang::line('msg_error'));
        $getUID = getUID();
        
        $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/customer/', false);
        $operatorFilePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
        
        $citizenData = array('filePath' => $filePath, 'stateRegNumber' => $stateRegNumber, 'civilId' => $civilId, 'base64Img' => $base64Img);
        $authType = '3';
        
        switch ($typeId) {

            case '1':
                $configParam = array(
                    'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                    'typeCode' => 'citizen_idcard_info',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);

                $sessionUserId = Ue::sessionUserId();
                $operatorData = $this->db->GetRow("SELECT 
                                                        t1.FILE_PATH, 
                                                        lower(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER, T3.LAST_NAME, T3.FIRST_NAME
                                                    FROM UM_USER t0
                                                    LEFT JOIN um_user_finger t1 ON t0.USER_ID = t1.USER_ID
                                                    INNER JOIN um_system_user t2 ON t0.SYSTEM_USER_ID = t2.USER_ID
                                                    INNER JOIN base_person t3 ON t2.PERSON_ID = t3.PERSON_ID
                                                    WHERE t2.USER_ID = $sessionUserId");
                
                if (!isset($operatorData['STATE_REG_NUMBER'])) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>РЕГИСТРИЙН ДУГААР</strong><br> эсвэл <br><strong>АЖИЛТНЫ ХУРУУНЫ ХЭЭНИЙ ЗУРАГ</strong> олдсонгүй', 'status' => 'error'));
                    die;
                }
                
                if (!file_exists($operatorFilePath)) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ХЭЭГЭЭ БҮРТГҮҮЛЭЭГҮЙ<strong> байна', 'status' => 'error'));
                    die;
                }
                
                $param = array(
                    "auth" => array(
                        "citizen" => array(
                            "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                            "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                            "fingerprint" => $citizenData['filePath'], //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                            "authType" => $authType, //
                        ),
                        "operator" => array(
                            "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                            "fingerprint" => $operatorFilePath // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                        ),
                    ),
                    "regnum" => $citizenData['stateRegNumber'],
                    "civilId" => "",                 // Иргэний регистрийн дугаар
                    'citizenRegnum' => $citizenData['stateRegNumber'],
                    'citizenFingerPrint' => $citizenData['filePath'],
                );                

                $this->load->model('mdintegration', 'middleware/models/');
                
                $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100101_getCitizenIDCardInfo');

                $result = $this->model->callXypService($processRow, $param);
                
                if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
                    $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                } else {
                    //unlink($citizenData['filePath']);
                    $result['data']['return']['response']['image'] = isset($result['data']['return']['response']['image']) ? base64_to_jpeg($result['data']['return']['response']['image'], UPLOADPATH . 'finger/customer/'. $getUID .'.jpg' ) : '';
                    $response = array('message' => isset($result['data']['return']['resultmessage']) ? $result['data']['return']['resultmessage'] : 'Холбогдож чадсангүй', 'status' => 'success', 'data' => isset($result['data']['return']['response']) ? $result['data']['return']['response'] : array());
                }
                
                break;
            case '2':
                $configParam = array(
                    'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                    'typeCode' => 'get_property_list',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);

                $sessionUserId = Ue::sessionUserId();
                
                $operatorData = $this->db->GetRow("SELECT 
                                                        t1.FILE_PATH, 
                                                        lower(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER, T3.LAST_NAME, T3.FIRST_NAME
                                                    FROM UM_USER t0
                                                    LEFT JOIN um_user_finger t1 ON t0.USER_ID = t1.USER_ID
                                                    INNER JOIN um_system_user t2 ON t0.SYSTEM_USER_ID = t2.USER_ID
                                                    INNER JOIN base_person t3 ON t2.PERSON_ID = t3.PERSON_ID
                                                    WHERE t2.USER_ID = $sessionUserId");
                
                if (!isset($operatorData['STATE_REG_NUMBER'])) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>РЕГИСТРИЙН ДУГААР</strong><br> эсвэл <br><strong>АЖИЛТНЫ ХУРУУНЫ ХЭЭНИЙ ЗУРАГ</strong> олдсонгүй', 'status' => 'error'));
                    die;
                }
                
                if (!file_exists($operatorFilePath)) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ХЭЭГЭЭ БҮРТГҮҮЛЭЭГҮЙ<strong> байна', 'status' => 'error'));
                    die;
                }
                
                $param = array(
                    "auth" => array(
                        "citizen" => array(
                            "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                            "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                            "fingerprint" => $citizenData['filePath'], //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                            "authType" => $authType, //
                        ),
                        "operator" => array(
                            "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                            "fingerprint" => $operatorFilePath, // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                        ),
                    ),
                    "regnum" => $citizenData['stateRegNumber'],
                    "civilId" => "",                 // Иргэний регистрийн дугаар
                    'citizenRegnum' => $citizenData['stateRegNumber'],
                    'citizenFingerPrint' => $citizenData['filePath'],
                );
                
                $this->load->model('mdintegration', 'middleware/models/');
                
                $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/property-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100202_getPropertyList');

                $result = $this->model->callXypService($processRow, $param);
                
                if (issetParamArray($result['data']['return']['resultcode']) && issetParamZero($result['data']['return']['resultcode']) != '0') {
                    $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                } else {
                    $response = array('message' => issetParam($result['data']['return']['resultmessage']), 'status' => 'success', 'data' => issetParamArray($result['data']['return']['response']));
                }
                
                break;
            case '3':
                $sessionUserId = Ue::sessionUserId();
                $configParam = array(
                    'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                    'typeCode' => 'property_info',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
                
                $operatorData = $this->db->GetRow("SELECT 
                                                        t1.FILE_PATH, lower(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER, T3.LAST_NAME, T3.FIRST_NAME
                                                    FROM UM_USER t0
                                                    LEFT JOIN um_user_finger t1 ON t0.USER_ID = t1.USER_ID
                                                    INNER JOIN um_system_user t2 ON t0.SYSTEM_USER_ID = t2.USER_ID
                                                    INNER JOIN base_person t3 ON t2.PERSON_ID = t3.PERSON_ID
                                                    WHERE t2.USER_ID = $sessionUserId");
                if (!isset($operatorData['STATE_REG_NUMBER'])) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>РЕГИСТРИЙН ДУГААР</strong><br> эсвэл <br><strong>АЖИЛТНЫ ХУРУУНЫ ХЭЭНИЙ ЗУРАГ</strong> олдсонгүй', 'status' => 'error'));
                    die;
                }
                
                if (!file_exists($operatorFilePath)) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ХЭЭГЭЭ БҮРТГҮҮЛЭЭГҮЙ<strong> байна', 'status' => 'error'));
                    die;
                }
                
                $param = array(
                    "auth" => array(
                        "citizen" => array(
                            "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                            "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                            "fingerprint" => $citizenData['filePath'], //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                            "authType" => $authType, //
                        ),
                        "operator" => array(
                            "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                            "fingerprint" => $operatorFilePath // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                        ),
                    ),
                    "regnum" => $citizenData['stateRegNumber'],
                    "propertyNumber" => $propertyNumber,
                    "civilId" => "" ,                // Иргэний регистрийн дугаар
                    'citizenRegnum' => $citizenData['stateRegNumber'],
                    'citizenFingerPrint' => $citizenData['filePath'],
                );

                $this->load->model('mdintegration', 'middleware/models/');

                $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/property-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100201_getPropertyInfo');
                $result = $this->model->callXypService($processRow, $param);
                
                if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
                    $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                } else {
                    $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'success', 'data' => isset($result['data']['return']['response']) ? $result['data']['return']['response'] : array());
                }
                
                break;
            case '4':
                $sessionUserId = Ue::sessionUserId();
                $configParam = array(
                    'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                    'typeCode' => 'entityinfo',
                );
                
                $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);

                $operatorData = $this->db->GetRow("SELECT 
                                                        t1.FILE_PATH, lower(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER, T3.LAST_NAME, T3.FIRST_NAME
                                                    FROM UM_USER t0
                                                    LEFT JOIN um_user_finger t1 ON t0.USER_ID = t1.USER_ID
                                                    INNER JOIN um_system_user t2 ON t0.SYSTEM_USER_ID = t2.USER_ID
                                                    INNER JOIN base_person t3 ON t2.PERSON_ID = t3.PERSON_ID
                                                    WHERE t2.USER_ID = $sessionUserId");
                if (!isset($operatorData['STATE_REG_NUMBER'])) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>РЕГИСТРИЙН ДУГААР</strong><br> эсвэл <br><strong>АЖИЛТНЫ ХУРУУНЫ ХЭЭНИЙ ЗУРАГ</strong> олдсонгүй', 'status' => 'error'));
                    die;
                }
                
                if (!file_exists($operatorData['FILE_PATH'])) {
                    echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ХЭЭГЭЭ БҮРТГҮҮЛЭЭГҮЙ<strong> байна', 'status' => 'error'));
                    die;
                }
                
                $param = array(
                    "auth" => array(
                        "citizen" => array(
                            "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                            "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                            "fingerprint" => $citizenData['filePath'], //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                            "authType" => $authType, //
                        ),
                        "operator" => array(
                            "regnum" => $operatorData['STATE_REG_NUMBER'],// Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                            "fingerprint" => $operatorFilePath   // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                        ),
                    ),
                    "legalEntityNumber" => $legalEntityNumber,
                    "civilId" => "",                 // Иргэний регистрийн дугаар
                );

                $this->load->model('mdintegration', 'middleware/models/');

                $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/legal-entity-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100301_getLegalEntityInfo');
                $result = $this->model->callXypService($processRow, $param);
                
                if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
                    $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                } else {
                    $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'success', 'data' => isset($result['data']['return']['response']) ? $result['data']['return']['response'] : array());
                }
                
                break;
            
            default:
                $response = array('status' => 'warning', 'message' => Lang::line('msg_error'));
                break;
                
        }
        
        header('Content-Type: application/json');
        echo json_encode($response); exit;
    } 
    
    public function getXypInformationDataBySignature() {

        $postData = Input::postData();
        if (issetParam($postData['timestamp']) !== '') {

            $sessionUserId = Ue::sessionUserId();

            $filePath = Input::post('finger');
            $calledProcessCode = Input::post('calledProcessCode');

            $propertyNumber = Str::lower(Input::post('propertyNumber')); 'ү22'; // жижигээр бичнэ
            $legalEntityNumber = Str::lower(Input::post('legalEntityNumber')); 'ү22'; // жижигээр бичнэ
            $stateRegNumber = Str::lower(Input::post('stateRegNumber'));
            $civilId = Input::post('civilId');

            $typeId = Input::post('typeId');
            $response = array('status' => 'warning', 'message' => Lang::line('msg_error'));
            $getUID = getUID();

            $operatorFilePath = '';
            $citizenData = array('filePath' => $filePath, 'stateRegNumber' => $stateRegNumber, 'civilId' => $civilId, 'base64Img' => '');
            $authType = '2';

            switch ($typeId) {

                case '1':

                    $configParam = array(
                        'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                        'typeCode' => 'citizen_idcard_info',
                    );
                    
                    $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                    $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);

                    $sessionUserId = Ue::sessionUserId();
                    $operatorData = $this->db->GetRow("SELECT 
                                                            T2.CERTIFICATE_SERIAL_NUMBER,
                                                            LOWER(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER
                                                        FROM UM_USER T0 
                                                        INNER JOIN UM_SYSTEM_USER T1 ON T0.SYSTEM_USER_ID = T1.USER_ID
                                                        INNER JOIN UM_USER_MONPASS_MAP T2 ON T0.USER_ID = T2.USER_ID
                                                        INNER JOIN BASE_PERSON t3 ON t1.PERSON_ID = t3.PERSON_ID
                                                        WHERE T1.USER_ID = ". $this->db->Param(0) ." AND T2.IS_ACTIVE = 1", array($sessionUserId));
                    
                    if (!isset($operatorData['CERTIFICATE_SERIAL_NUMBER'])) {
                        echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ТООН ГАРЫН ҮСГЭЭ</strong> бүртгүүлнэ үү!', 'status' => 'error'));
                        die;
                    }

                    $timestamp = (int) Input::post('timestamp');

                    $param = array(
                        "auth" => array(
                            "citizen" => array(
                                "regnum" => $citizenData['stateRegNumber'],         // Иргэний регистрийн дугаар
                                "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                                "fingerprint" => $citizenData['filePath'],          //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                "authType" => "3",
                                
                            ),
                            "operator" => array(
                                "regnum" => $operatorData['STATE_REG_NUMBER'],      // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                                "fingerprint" => '',                                // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                'certFingerprint' => $operatorData['CERTIFICATE_SERIAL_NUMBER'],//$operatorData['CERTIFICATE_SERIAL_NUMBER'], //toon gariin usegiin cerial dugaar
                                'signature' => Input::post('operatorFinger'),       //rd + '.' + timestamp
                                "authType" => $authType,
                            ),
                        ),
                        "regnum" => $citizenData['stateRegNumber'],
                    );                

                    $this->load->model('mdintegration', 'middleware/models/');

                    $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                    $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100101_getCitizenIDCardInfo');
                    $result = $this->model->callXypService($processRow, $param, true, $timestamp);

                    if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
                        $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                    } else {
                        $result['data']['return']['response']['image'] = isset($result['data']['return']['response']['image']) ? base64_to_jpeg($result['data']['return']['response']['image'], UPLOADPATH . 'finger/customer/'. $getUID .'.jpg' ) : '';
                        $response = array('message' => isset($result['data']['return']['resultmessage']) ? $result['data']['return']['resultmessage'] : 'Холбогдож чадсангүй', 'status' => 'success', 'data' => isset($result['data']['return']['response']) ? $result['data']['return']['response'] : array());
                    }

                    break;
                case '2':
                    
                    $configParam = array(
                        'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                        'typeCode' => 'get_property_list',
                    );
                    
                    $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                    $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
                    $sessionUserId = Ue::sessionUserId();
                    $operatorData = $this->db->GetRow("SELECT 
                                                            T2.CERTIFICATE_SERIAL_NUMBER,
                                                            LOWER(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER
                                                        FROM UM_USER T0 
                                                        INNER JOIN UM_SYSTEM_USER T1 ON T0.SYSTEM_USER_ID = T1.USER_ID
                                                        INNER JOIN UM_USER_MONPASS_MAP T2 ON T0.USER_ID = T2.USER_ID
                                                        INNER JOIN BASE_PERSON t3 ON t1.PERSON_ID = t3.PERSON_ID
                                                        WHERE T1.USER_ID = ". $this->db->Param(0) ." AND T2.IS_ACTIVE = 1", array($sessionUserId));

                    if (!isset($operatorData['CERTIFICATE_SERIAL_NUMBER'])) {
                        echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ТООН ГАРЫН ҮСГЭЭ</strong> бүртгүүлнэ үү!', 'status' => 'error'));
                        die;
                    }
                    
                    $timestamp = (int) Input::post('timestamp');
                    $param = array(
                        "auth" => array(
                            "citizen" => array(
                                "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                                "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                                "fingerprint" => $citizenData['filePath'],          //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                "authType" => "3",
                            ),
                            "operator" => array(
                                "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                                "fingerprint" => '', // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                'certFingerprint' => $operatorData['CERTIFICATE_SERIAL_NUMBER'],//$operatorData['CERTIFICATE_SERIAL_NUMBER'], //toon gariin usegiin cerial dugaar
                                'signature' => Input::post('operatorFinger'),       //rd + '.' + timestamp
                                "authType" => $authType,
                            ),
                        ),
                        "regnum" => $citizenData['stateRegNumber'],
                        "civilId" => "",                 // Иргэний регистрийн дугаар
                        'citizenRegnum' => $citizenData['stateRegNumber'],
                        'citizenFingerPrint' => $citizenData['filePath'],
                    );

                    $this->load->model('mdintegration', 'middleware/models/');

                    $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/property-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                    $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100202_getPropertyList');

                    $result = $this->model->callXypService($processRow, $param, true, $timestamp);

                    if (issetParamArray($result['data']['return']['resultcode']) && issetParamZero($result['data']['return']['resultcode']) != '0') {
                        $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                    } else {
                        $response = array('message' => issetParam($result['data']['return']['resultmessage']), 'status' => 'success', 'data' => issetParamArray($result['data']['return']['response']));
                    }

                    break;
                case '3':

                    $configParam = array(
                        'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                        'typeCode' => 'property_info',
                    );
                    
                    $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                    $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
                    $sessionUserId = Ue::sessionUserId();
                    $operatorData = $this->db->GetRow("SELECT 
                                                            T2.CERTIFICATE_SERIAL_NUMBER,
                                                            LOWER(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER
                                                        FROM UM_USER T0 
                                                        INNER JOIN UM_SYSTEM_USER T1 ON T0.SYSTEM_USER_ID = T1.USER_ID
                                                        INNER JOIN UM_USER_MONPASS_MAP T2 ON T0.USER_ID = T2.USER_ID
                                                        INNER JOIN BASE_PERSON t3 ON t1.PERSON_ID = t3.PERSON_ID
                                                        WHERE T1.USER_ID = ". $this->db->Param(0) ." AND T2.IS_ACTIVE = 1", array($sessionUserId));

                    if (!isset($operatorData['CERTIFICATE_SERIAL_NUMBER'])) {
                        echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ТООН ГАРЫН ҮСГЭЭ</strong> бүртгүүлнэ үү!', 'status' => 'error'));
                        die;
                    }
                    
                    $timestamp = (int) Input::post('timestamp');
           
                    $param = array(
                        "auth" => array(
                            "citizen" => array(
                                "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                                "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                                "fingerprint" => $citizenData['filePath'],          //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                "authType" => "3",
                            ),
                            "operator" => array(
                                "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                                "fingerprint" => '', // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                'certFingerprint' => $operatorData['CERTIFICATE_SERIAL_NUMBER'],//$operatorData['CERTIFICATE_SERIAL_NUMBER'], //toon gariin usegiin cerial dugaar
                                'signature' => Input::post('operatorFinger'),       //rd + '.' + timestamp
                                "authType" => $authType,
                            ),
                        ),
                        "regnum" => $citizenData['stateRegNumber'],
                        "propertyNumber" => $propertyNumber,
                    );

                    $this->load->model('mdintegration', 'middleware/models/');

                    $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/property-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                    $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100201_getPropertyInfo');
                    $result = $this->model->callXypService($processRow, $param, true, $timestamp);

                    if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
                        $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                    } else {
                        $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'success', 'data' => isset($result['data']['return']['response']) ? $result['data']['return']['response'] : array());
                    }

                    break;
                case '4':
                    $configParam = array(
                        'processCode' => Input::post('methodCode', 'NTR_TRUST_BOOK_DV_001'),
                        'typeCode' => 'entityinfo',
                    );
                    
                    $checkConfigGet = Config::getFromCacheDefault('checkXypVersionProcessGet', null, 'NTR_KHUR_SYSINT_PROCESS_TMP_004');
                    $configData = $this->ws->runResponse(self::$gfServiceAddress, $checkConfigGet, $configParam);
                    $sessionUserId = Ue::sessionUserId();
                    $operatorData = $this->db->GetRow("SELECT 
                                                            T2.CERTIFICATE_SERIAL_NUMBER,
                                                            LOWER(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER
                                                        FROM UM_USER T0 
                                                        INNER JOIN UM_SYSTEM_USER T1 ON T0.SYSTEM_USER_ID = T1.USER_ID
                                                        INNER JOIN UM_USER_MONPASS_MAP T2 ON T0.USER_ID = T2.USER_ID
                                                        INNER JOIN BASE_PERSON t3 ON t1.PERSON_ID = t3.PERSON_ID
                                                        WHERE T1.USER_ID = ". $this->db->Param(0) ." AND T2.IS_ACTIVE = 1", array($sessionUserId));


                    if (!isset($operatorData['CERTIFICATE_SERIAL_NUMBER'])) {
                        echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ТООН ГАРЫН ҮСГЭЭ</strong> бүртгүүлнэ үү!', 'status' => 'error'));
                        die;
                    }
                    
                    $timestamp = (int) Input::post('timestamp');
           
                    $param = array(
                        "auth" => array(
                            "citizen" => array(
                                "regnum" => $citizenData['stateRegNumber'],        // Иргэний регистрийн дугаар
                                "civilId" => issetParam($citizenData['civilId']),               // Иргэний регистрийн дугаар
                                "fingerprint" => $citizenData['filePath'],          //base64_encode(file_get_contents($citizenData['filePath'])) // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                "authType" => "3",
                            ),
                            "operator" => array(
                                "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                                "fingerprint" => '', // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                                'certFingerprint' => $operatorData['CERTIFICATE_SERIAL_NUMBER'],//$operatorData['CERTIFICATE_SERIAL_NUMBER'], //toon gariin usegiin cerial dugaar
                                'signature' => Input::post('operatorFinger'),       //rd + '.' + timestamp
                                "authType" => $authType,
                            ),
                        ),
                        "regnum" => $citizenData['stateRegNumber'],
                        "legalEntityNumber" => $legalEntityNumber,
                    );

                    $this->load->model('mdintegration', 'middleware/models/');

                    $processRow['WS_URL'] = checkDefaultVal($configData['serviceurl'], 'https://xyp.gov.mn/legal-entity-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '1.3.0') .'/ws?WSDL');
                    $processRow['CLASS_NAME'] = checkDefaultVal($configData['classname'], 'WS100301_getLegalEntityInfo');
                    $result = $this->model->callXypService($processRow, $param, true, $timestamp);

                    if (isset($result['data']['return']['resultcode']) && $result['data']['return']['resultcode'] != '0') {
                        $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'error');
                    } else {
                        $response = array('message' => $result['data']['return']['resultmessage'], 'status' => 'success', 'data' => isset($result['data']['return']['response']) ? $result['data']['return']['response'] : array());
                    }

                    break;

                default:
                    $response = array('status' => 'warning', 'message' => Lang::line('msg_error'));
                    break;

            }

            header('Content-Type: application/json');
            echo json_encode($response); exit;
        }
        else {
            self::getXypInformationData();
        }
        
    }
    
    public function saveElecLog($selectedRow = '', $erlStructureId = null, $postData = array(), $type = '') {
        
        $currentDate = Date::currentDate();
        $sessionUserId = Ue::sessionUserKeyId();
        $tableName = '';
        
        $metaDataId = issetParam($postData['paramData']['dataViewId']);
        $saveTableName = issetParam($postData['paramData']['saveTableName']);
        $smetaDataId = issetParam($postData['paramData']['smetaDataId']);
        $smetaaDataType = issetParam($type) ? $type : issetParam($postData['paramData']['smetaaDataType']);
        
        if ($metaDataId) {
            $gRow = $this->db->GetRow("
                SELECT t1.REF_STRUCTURE_ID, t1.TABLE_NAME FROM META_GROUP_LINK t0
                INNER JOIN META_GROUP_LINK t1 ON t0.REF_STRUCTURE_ID = t1.META_DATA_ID
                WHERE t0.META_DATA_ID = '$metaDataId'");
            
            if ($gRow) {
                $tableName = $gRow['TABLE_NAME'];

                if (strlen(trim($tableName)) > 30) {    
                    $tableName = 'query';
                }
            } elseif(issetParam($postData['paramData']['refstructureid']))  {
                $gRow = $this->db->GetRow("
                    SELECT t1.REF_STRUCTURE_ID, t1.TABLE_NAME, t0.META_DATA_ID FROM META_GROUP_LINK t0
                    INNER JOIN META_GROUP_LINK t1 ON t0.REF_STRUCTURE_ID = t1.META_DATA_ID
                    WHERE t0.REF_STRUCTURE_ID = ".$this->db->Param(0), array($postData['paramData']['refstructureid']));

                if ($gRow) {
                    $tableName = $gRow['TABLE_NAME'];
                    $metaDataId = $gRow['META_DATA_ID'];

                    if (strlen(trim($tableName)) > 30) {    
                        $tableName = 'query';
                    }

                }
            }
        
        } elseif(issetParam($postData['paramData']['refstructureid']))  {
            $gRow = $this->db->GetRow("
                SELECT t1.REF_STRUCTURE_ID, t1.TABLE_NAME, t0.META_DATA_ID FROM META_GROUP_LINK t0
                INNER JOIN META_GROUP_LINK t1 ON t0.REF_STRUCTURE_ID = t1.META_DATA_ID
                WHERE t0.REF_STRUCTURE_ID = ".$this->db->Param(0), array($postData['paramData']['refstructureid']));
         
            if ($gRow) {
                $tableName = $gRow['TABLE_NAME'];
                $metaDataId = $gRow['META_DATA_ID'];

                if (strlen(trim($tableName)) > 30) {    
                    $tableName = 'query';
                }

            }
        }
        
        if (issetParam($postData['isWorkFlow']) == '1' && issetParam($postData['metaDataId'])) {
            $smetaDataId = $this->db->GetOne("SELECT ACTION_META_DATA_ID FROM META_MENU_LINK WHERE META_DATA_ID = '". issetParam($postData['metaDataId']) ."'");
            $smetaaDataType = 'weblink';
        }
        
        if (issetParam($saveTableName) !== '') {
            $data = array(
                'ID' => getUID(),
                'SYSTEM_ID' => issetParam($selectedRow['systemid']) !== '' ? issetParam($selectedRow['systemid']) : '30',
                'ACTION_DATE' => $currentDate,
                'ACTION_META_DATA_ID' => $smetaDataId, //menuId
                'ACTION_NAME' => $smetaaDataType,
                'ACTION_USER_ID' => $sessionUserId,
                'TABLE_NAME' => issetParam($tableName),
                'RECORD_ID' => issetParam($selectedRow['id']),
                'COMPANY_KEY_ID' => issetParam($selectedRow['companykeyid']),
                'DESCRIPTION' => issetParam($selectedRow['deleteContentIds']),
                'MAIN_META_DATA_ID' => $metaDataId,
                'IP_ADDRESS' => getIpAddress(),
                'BOOK_TYPE_ID' => issetParam($selectedRow['booktypeid']),
            );

            $this->db->AutoExecute('CVL_SYSTEM_ACTION_LOG', $data);
            
        } else {
            $data = array(
                'ID' => getUID(),
                'SYSTEM_ID' => issetParam($selectedRow['systemid']) !== '' ? issetParam($selectedRow['systemid']) : '30',
                'ACTION_DATE' => $currentDate,
                'ACTION_META_DATA_ID' => $smetaDataId, //menuId
                'ACTION_NAME' => $smetaaDataType,
                'ACTION_USER_ID' => $sessionUserId,
                'TABLE_NAME' => issetParam($tableName),
                'RECORD_ID' => issetParam($selectedRow['id']),
                'COMPANY_KEY_ID' => issetParam($selectedRow['companykeyid']),
                'DESCRIPTION' => issetParam($selectedRow['deleteContentIds']),
                'MAIN_META_DATA_ID' => $metaDataId,
            );

            $this->db->AutoExecute('CMP_SYSTEM_ACTION_LOG', $data);
        }
        
    }
    
    public function deleteErl() {
        
        $response = array('status' => 'error', 'text' => Lang::line('msg_delete_error'), 'title' => 'Error');
        try {
         
            includeLib('Compress/Compression');
            $compressData = Compression::decode_string_array(Input::post('compressData'));
            $selectRow = is_array($compressData['selectedRow']) ? $compressData['selectedRow'] : Compression::decode_string_array($compressData['selectedRow']);
            if (isset($selectRow['dataRow'])) {
                $selectRow = $selectRow['dataRow'];
            }
            
            if (!issetParamArray($selectRow)) {
                throw new Exception(Lang::line('Сонгогдсон мөр олдсонгүй')); 
                exit();
            }
            
            if (!Input::post('contentIds')) {
                throw new Exception(Lang::line('Сонгогдсон мөр олдсонгүй')); 
                exit();
            }
            
            $contentIds = implode(',', Input::post('contentIds'));

            $param = array(
                'systemMetaGroupId' => '1540461766473', // '1535007433998',
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'criteria' => array(
                    'companyId' => array(
                        array(
                            'operator' => '=',
                            'operand' => issetParam($selectRow['companyid'])
                        )
                    ),
                    'id' => array(
                        array(
                            'operator' => 'in',
                            'operand' => $contentIds
                        )
                    ),
                )
            );

            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] === 'success') {

                if (issetParamArray($data['result'])) {
                    foreach ($data['result'] as $key => $row) {
                        $this->db->Execute("UPDATE ECM_CONTENT SET IS_VERSION = 1 WHERE CONTENT_ID = '". $row['id'] ."'");
                    }

                    $selectRow['deleteContentIds'] = 'contentId: ' . $contentIds;
                    if (Config::getFromCache('previewSaveLog') === '1') { /* (!Config::getFromCache('isNotaryServer')  && Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { */ 
                        self::saveElecLog($selectRow, null, $compressData, 'deleteElec');
                    }

                    $response = array('status' => 'success', 'text' => Lang::line('msg_delete_success'), 'title' => 'Success');
                }

            }
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        echo json_encode($response); exit;
    }
    
    public function dataimport($id = '') {
        
        $this->view->uniqId = getUID();
        $this->view->otherUniqId = getUID();
        $this->view->title = 'Import';
        
        if (!is_ajax_request()) {

            $this->view->css = AssetNew::metaCss();
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        }

        try {
            
            $this->view->postData = Input::postData();
            $this->view->selectedRow = issetParam($this->view->postData['selectedRow']) ? Arr::decode(issetParam($this->view->postData['selectedRow'])) : '';
            $this->view->dataRow = isset($this->view->selectedRow['dataRow']) ? issetParamArray($this->view->selectedRow['dataRow']) : array();
            $this->view->id = isset($this->view->selectedRow['dataRow']['id']) ? $this->view->selectedRow['dataRow']['id'] : '';
            $this->view->companykeyid = isset($this->view->selectedRow['dataRow']['companykeyid']) ? $this->view->selectedRow['dataRow']['companykeyid'] : '';
            $this->view->companyname = isset($this->view->selectedRow['dataRow']['companyname']) ? $this->view->selectedRow['dataRow']['companyname'] : '';
            $this->view->rowId = ($id) ? $id : issetParam($this->view->postData['rowId']);
            $this->view->ishide = 'false';
            
            if ($this->view->id !== '' && $this->view->companykeyid !== '') {
                $param = array (
                            'systemMetaGroupId' => '1598441399264097',
                            'showQuery' => '0',
                            'paging' => array ('offset' => '1', 'pageSize' => '1',),
                            'criteria' => array (
                                'companykeyid' =>  array (
                                                    0 =>  array (
                                                            'operator' => '=',
                                                            'operand' => $this->view->companykeyid,
                                                          ),
                                                    ),
                                'servicebookid' => array (
                                                    0 =>  array (
                                                        'operator' => '=',
                                                        'operand' => $this->view->id,
                                                    ),
                                                ),
                            ),
                        );
                
                $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param); 
                if (isset($data['result'][0]['rowcount'])) {
                    $this->view->ishide = issetParam($data['result'][0]['rowcount']) !== '0' ? 'true' : 'false';
                }
            }
        } catch (Exception $ex) {
            $this->view->title = '404 Not Found';
            $this->view->setMessage = $ex->getMessage();
            $this->view->content = $this->view->renderPrint('government/lawrender/error');
        }

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('erl/import', self::$viewPath);
            $this->view->render('footer');
        } else {
            $this->view->render('erl/import', self::$viewPath);

            /* $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('erl/import', self::$viewPath),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response); */
        }
    }
    
    public function importDataCvl() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);
        
        if (issetParam($_FILES['excelFile']['tmp_name']) === '') {
            echo json_encode(array('status' => 'warning', 'message' => Lang::line('pl_0285')));
            die;
        }
        
        $templateId    = Input::post('templateId');
        $data          = file_get_contents($_FILES['excelFile']['tmp_name']);
        $fileContent   = base64_encode($data);
        $fileExtension = strtolower(substr($_FILES['excelFile']['name'], strrpos($_FILES['excelFile']['name'], '.') + 1));
        $logId         = getUID();
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $fileName = $logId . '.' . $fileExtension;
        $logResult = $this->model->checkImpExcelLog($logId, $templateId, $fileContent, $fileName, $fileExtension);
        
        if ($logResult) {
            $response = $logResult;
        } else {
            
            $param = array(
                'logId'               => $logId, 
                'templateId'          => $templateId, 
                'fileExtension'       => $fileExtension, 
                'byteValue'           => $fileContent, 
                'isReturnSuccessRows' => false,
                'isSaveWhenAllRowSuccessful' => true
            );

            $configWsUrl = Config::getFromCache('heavyServiceAddress');

            if ($configWsUrl) {
                $serviceAddress = $configWsUrl;
            } else {
                $serviceAddress = self::$gfServiceAddress;
            }

            $result = $this->ws->runSerializeResponse($serviceAddress, 'xls_imp_001', $param);

            $response = array('status' => 'success', 'message' => Lang::line('msg_success_import'), 'error_count' => '0', 'response' => '');

            if (issetParam($result['result']) !== '' && issetParam($result['status']) == 'success') {
                $result = issetParam($result['result']);
                $cacheTmpDir = Mdcommon::getCacheDirectory();
                $tempdir     = $cacheTmpDir . '/excelimport';

                if (!is_dir($tempdir)) {

                    mkdir($tempdir, 0777);

                } else {

                    $files = glob($tempdir.'/*');
                    $now   = time();
                    $day   = 0.5;

                    foreach ($files as $file) {
                        if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                            unlink($file);
                        } 
                    }
                }

                $uniqId    = getUID();
                $file_path = $tempdir.'/'.$uniqId.'.txt';

                $f = fopen($file_path, "w+");
                fwrite($f, $result);
                fclose($f);

                $response = array('status' => 'error', 'message' => Lang::line('msg_error_import'), 'uniqId' => $uniqId);

            } elseif(issetParam($result['status']) !== 'success') {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }

        }
        
        echo json_encode($response);
    }
    
    public function comparepic($apptype = '') {
        (String) $html = '';
        (Array) $response = array('status' => 'error', 'text' => Lang::line('error_sms'), 'Html' => $html);
        
        try {
            
            $postData = Input::postData();
            $selectedRow = issetParamArray($postData['selectedRow']);
            $dataViewId = issetParam($postData['dataViewId']);

            if (issetParam($selectedRow['opic']) !== '1' && Config::getFromCache('saveAdvSearchLogElec') === '1') {
                $data = array(
                    'ID'              => getUID(), 
                    'DV_META_DATA_ID' => Input::post('dataViewId'), 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
                    'CREATED_DATE'    => Date::currentDate(), 
                    'ROW_COUNT'       => '1', 
                    'IP_ADDRESS'      => get_client_ip(),
                    'FILTER1'         => $selectedRow['registernum'],
                    'FILTER70'        => '2'
                );
                $this->db->AutoExecute('CUSTOMER_DV_FILTER_DATA', $data);
            }

            if (issetParam($selectedRow['compareprocesscode']) !== '' && issetParam($selectedRow['registernum']) !== '') {

                $param = array(
                    'registernum' => $selectedRow['registernum']
                );

                includeLib('Utils/Functions');
                $responseData = Functions::runProcess($selectedRow['compareprocesscode'], $param);
                
                $responseData = self::callFtpFileData($responseData);
                
                if (issetParamArray($responseData['result'])) {
                    $data = $responseData['result'];
                    if (issetParam($selectedRow['opic']) !== '1') {
                        $html .= '<div class="row main-compare">';
                            $html .= '<div class="col-md-12">';
                                $html .= '<div class="row">';
                                    $html .= '<div class="col-md-2">';
                                    if (issetParam($data['croldidcardphotodv']['0']['photo'])) {
                                        $html .= '<img src="data:image/png;base64,'. $data['croldidcardphotodv']['0']['photo'] .'" style="width: 125px; height: 160px;" />';
                                    }
                                    $html .= '</div>';
                                    $html .= '<div class="col-md-8">';
                                        $html .= '<div class="row">';
                                            $html .= '<div class="col-md-4">';
                                                $html .= '<div class="col-md-12" style=" box-shadow: 2px 2px #e5e5e5; border: 1px solid #e5e5e5; margin-bottom: 1rem; ">';
                                                    
                                                    $html .= '<table>';
                                                        $html .= '<tbody>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="color: #2145f3;font-weight: bold;font-size: 11px;">Иргэний мэдээлэл</td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Регистрийн дугаар<br><a href="javascript:;" onclick="comparePicByReg(this, \''. issetParam($data['registernum']) .'\', \''. issetParam($selectedRow['compareprocesscode']) .'\', \''. $dataViewId .'\')" style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['registernum']) .'</a></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Эцэг (эх)-н нэр / Нэр<br><a href="javascript:;" onclick="comparePicByReg(this, \''. issetParam($data['registernum']) .'\', \''. issetParam($selectedRow['compareprocesscode']) .'\', \''. $dataViewId .'\')" style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['name']) .'</a></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Төрсөн өдөр / Хүйс<br><span style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['sex']) .'</span></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;" >Яс үндэс / Харъяалал<br><span style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['origin']) .'</span></td>';
                                                            $html .= '</tr>';
                                                        $html .= '</tbody>';
                                                    $html .= '</table>';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                            
                                            $html .= '<div class="col-md-4">';
                                                $html .= '<div class="col-md-12" style=" box-shadow: 2px 2px #e5e5e5; border: 1px solid #e5e5e5; margin-bottom: 1rem; ">';
                                                    $html .= '<table>';
                                                        $html .= '<tbody>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="color: #2145f3;font-weight: bold;font-size: 11px;">Эцгийн мэдээлэл</td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Регистрийн дугаар<br><a href="javascript:;" onclick="comparePicByReg(this, \''. issetParam($data['faregisternum']) .'\', \''. issetParam($selectedRow['compareprocesscode']) .'\', \''. $dataViewId .'\')" style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['faregisternum']) .'</a></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Эцэг (эх)-н нэр / Нэр<br><a href="javascript:;" onclick="comparePicByReg(this, \''. issetParam($data['faregisternum']) .'\', \''. issetParam($selectedRow['compareprocesscode']) .'\', \''. $dataViewId .'\')" style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['faname']) .'</a></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Төрсөн өдөр / Хүйс<br><span style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['fasex']) .'</span></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;" >Яс үндэс / Харъяалал<br><span style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['faorigin']) .'</span></td>';
                                                            $html .= '</tr>';
                                                        $html .= '</tbody>';
                                                    $html .= '</table>';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                            
                                            $html .= '<div class="col-md-4">';
                                                $html .= '<div class="col-md-12" style=" box-shadow: 2px 2px #e5e5e5; border: 1px solid #e5e5e5; margin-bottom: 1rem; ">';
                                                    $html .= '<table>';
                                                        $html .= '<tbody>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="color: #2145f3;font-weight: bold;font-size: 11px;">Эхийн мэдээлэл</td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Регистрийн дугаар<br><a href="javascript:;" onclick="comparePicByReg(this, \''. issetParam($data['moregisternum']) .'\', \''. issetParam($selectedRow['compareprocesscode']) .'\', \''. $dataViewId .'\')" style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['moregisternum']) .'</a></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Эцэг (эх)-н нэр / Нэр<br><a href="javascript:;" onclick="comparePicByReg(this, \''. issetParam($data['moregisternum']) .'\', \''. issetParam($selectedRow['compareprocesscode']) .'\', \''. $dataViewId .'\')" style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['moname']) .'</a></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;">Төрсөн өдөр / Хүйс<br><span style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['mosex']) .'</span></td>';
                                                            $html .= '</tr>';
                                                            $html .= '<tr>';
                                                                $html .= '<td style="font-size: 11px;" >Яс үндэс / Харъяалал<br><span style="color: #2145f3;font-weight: bold;font-size: 13px;">'. issetParam($data['moorigin']) .'</span></td>';
                                                            $html .= '</tr>';
                                                        $html .= '</tbody>';
                                                    $html .= '</table>';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                            
                                        $html .= '</div>';
                                    $html .= '</div>';
                                    
                                    $html .= '<div class="col-md-2"></div>';
                                $html .= '</div>';
                            $html .= '</div>';	
                        }
                        $html .= '<div class="col-md-12 data-pic">';
                            $html .= '<div class="row">';
                                $html .= '<div class="col-md-6">';
                                    $html .= '<div class="row">';
                                        $html .= '<div class="col-md-4" style="overflow: auto; max-height: 500px; overflow-x: hidden; display:grid;">';
                                            if (issetParamArray($data['cr_idcard_photo_get'])) {
                                                foreach ($data['cr_idcard_photo_get'] as $row) {
                                                    $html .= '<img src="data:image/png;base64,'. $row['photobase'] .'" onclick="changepic(this, \'photobase\', \''. $row['createddate'] .'\')" style="width: 125px; height: 160px;" />';
                                                }
                                            }
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-8" style="display: grid;">';
                                            if (issetParamArray($data['cr_idcard_photo_get'])) {
                                                $html .= '<span class="pull-left w-100" style="border-bottom: 1px solid #e5e5e5; border-top: 1px solid #e5e5e5; text-transform: uppercase; padding: 10px; font-weight: 700; ">'. Lang::line('cr_idcard_photo_title') .'</span>';
                                                $html .= '<img src="data:image/png;base64,'. $data['cr_idcard_photo_get'][0]['photobase'] .'" class="photobase" style="width: 300px; height: 450px;" />';
                                                $html .= '<span class="photobase pull-left w-100" style="border-bottom: 1px solid #e5e5e5; border-top: 1px solid #e5e5e5; text-transform: uppercase; padding: 10px; ">'. Lang::line('created_date') .': '. $data['cr_idcard_photo_get'][0]['createddate'] .'</span>';
                                            }
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';
                                $html .= '<div class="col-md-6" style="border-left: 1px solid #e5e5e5">';
                                    $html .= '<div class="row">';
                                        $html .= '<div class="col-md-8" style="display: grid;">';
                                            if (issetParamArray($data['cr_passport_import_get'])) {
                                                $html .= '<span class="pull-left w-100" style="border-bottom: 1px solid #e5e5e5; border-top: 1px solid #e5e5e5; text-transform: uppercase; padding: 10px; font-weight: 700; ">'. Lang::line('cr_passport_import_title') .'</span>';
                                                $html .= '<img src="data:image/png;base64,'. $data['cr_passport_import_get'][0]['imagebase'] .'" class="imagebase" style="width: 300px; height: 450px;"  />';
                                                $html .= '<span class="imagebase pull-left w-100" style="border-bottom: 1px solid #e5e5e5; border-top: 1px solid #e5e5e5; text-transform: uppercase; padding: 10px; ">'. Lang::line('created_date') .': '. $data['cr_passport_import_get'][0]['importdate'] .'</span>';
                                            }
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-4" style="overflow: auto; max-height: 500px; overflow-x: hidden; display:grid;">';
                                            if (issetParamArray($data['cr_passport_import_get'])) {
                                                foreach ($data['cr_passport_import_get'] as $row) {
                                                    $html .= '<img src="data:image/png;base64,'. $row['imagebase'] .'" onclick="changepic(this, \'imagebase\', \''. $row['importdate'] .'\')" style="width: 125px; height: 160px;" />';
                                                }
                                            }
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                        
                    if (issetParam($selectedRow['opic']) !== '1') {
                        $html .= '</div>';
                    }
                    $response = array(
                                    'status' => 'success', 
                                    'text' => '', 
                                    'Html' => $html, 
                                    'Width' => '1200', 
                                    'Height' => 'auto', 
                                    'Title' => Lang::line('zurag_tulgalt'), 
                                    'uniqId' => getUID(),
                                    'close_btn' => $apptype === '2' ? Lang::line('back_btn') : Lang::line('close_btn'),
                                ); 
                            
                    if ($apptype === '2') {
                        $response['approve_btn'] = Lang::line('approve_btn');
                    }
                } else {
                    $response = $responseData;
                }
                
            } else {
                throw new Exception("Мэдээллээ шалгана уу");
            }

        } catch (Exception $ex) {
            (Array) $result = array();

            $response['status'] = 'error';
            $response['text'] = $ex->getMessage();
        }
        
        echo json_encode($response);
    }

    public function callFtpFileData ($responseData) {
        
        (Array) $response = $responseData;
        if (issetParamArray($responseData['result'])) {
            $tmp = $data = $responseData['result'];

            if (issetParamArray($data['cr_idcard_photo_get'])) {
                $temp = array();
                foreach ($data['cr_idcard_photo_get'] as $row) {

                    if (
                        issetParam($row['ftpprocess']) !== '' && 
                        issetParam($row['outputparam']) !== '' && 
                        issetParam($row['inputparam']) !== '' && 
                        issetParam($row['srcftppath']) !== '' && 
                        issetParam($row['replacepath']) !== ''
                    ) {
                        (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                        if (strpos($row['ftpprocess'], ',') !== false) {
                            $processArr = explode(',', $row['ftpprocess']);
                            $outputparam = explode(',', $row['outputparam']);
                            $inputparam = explode(',', $row['inputparam']);
                            $srcftppath = explode(',', $row['srcftppath']);
                            $replacepath = explode(',', $row['replacepath']);
                        } else {
                            array_push($processArr, $row['ftpprocess']);
                            array_push($outputparam, $row['outputparam']);
                            array_push($inputparam, $row['inputparam']);
                            array_push($srcftppath, $row['srcftppath']);
                            array_push($replacepath, $row['replacepath']);
                        }

                        includeLib('Utils/Functions');

                        foreach($processArr as $pk => $pr) {
                            if (
                                    issetParam($processArr[$pk]) !== '' &&
                                    issetParam($outputparam[$pk]) !== '' &&
                                    issetParam($inputparam[$pk]) !== '' &&
                                    issetParam($srcftppath[$pk]) !== '' 
                            ) {

                                $rFtp = Functions::runProcess($processArr[$pk], array($inputparam[$pk] => $row[$srcftppath[$pk]]));
                                
                                if (issetParam($rFtp['result'][$outputparam[$pk]]) && issetParam($row[$replacepath[$pk]])) {
                                    $row[$replacepath[$pk]] = issetParam($rFtp['result'][$outputparam[$pk]]);
                                }
                            }
                        }
                    }

                    array_push($temp, $row);
                }

                $tmp['cr_idcard_photo_get'] = $temp;
            }

            $response['result'] = $tmp;
        }

        return $response;

    }

    public function saveTempFileByAfisFinger () {
        $postData = Input::postData();
        
        (Array) $tempdata = array();
        if (issetParamArray($postData['details'])) {

            foreach ($postData['details'] as $row) {
                if (issetParam($row['value']) !== '') {

                    switch ($row['key']) {
                        case 'RightThumbImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'RightIndexImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'RightMiddleImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'RightRingImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'RightLittleImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'LeftThumbImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'LeftIndexImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'LeftMiddleImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'LeftRingImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                        case 'LeftLittleImage' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                    }        
                }
                array_push($tempdata, $row);
    
            }
        }
        echo json_encode(array('details' => $tempdata));
    }

    public function saveTempFileByAfisPhoto () {
        $postData = Input::postData();
        
        (Array) $tempdata = array();
        if (issetParamArray($postData['details'])) {

            foreach ($postData['details'] as $row) {
                if (issetParam($row['value']) !== '') {

                    switch ($row['key']) {
                        case 'photo' :
                            
                            $getUID = getUID();
                            
                            $imagePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'finger/');
                            $base64Img = $row['value'];
                            $row['base64Img'] = $row['value'];
                            $filePath = base64_to_jpeg($base64Img, $imagePath. $getUID .'.jpg' );
                            $row['value'] = $filePath;
                            $row['fileSize'] = filesize($filePath);
                            $row['fileName'] = $getUID.'.jpg';
                            break;
                    }        
                }
                array_push($tempdata, $row);
    
            }
        }
        echo json_encode(array('details' => $tempdata));

    }

    public function checkRegister () {
        includeLib('Utils/Functions');
        $result = Functions::runProcess('crAdvancedSearchRdCheckGet_004', array('filterRegisterNum' => Input::post('registerNum')));
        echo json_encode(array('rdcheck' => issetParam($result['result']['rdcheck'])));
    }
    
    public function getPackId () {

        includeLib('Utils/Functions');
        $selectedRow = Input::post('selectedRow');
        $param1 = array(
            'stateregnumber' => issetParam($selectedRow['stateregnumber']), 
            'archivetypeid' => issetDefaultVal($selectedRow['archivetypeid'], '2'),
        );
        $civilPdata = Functions::runProcess('CVL_CIVIL_PACK_GET_LIST_008', $param1);
        
        if (issetParam($selectedRow['isftpscanid']) === '2' && issetParam($selectedRow['ftpscanid']) !== '') {
            echo json_encode(array('ftpscanid' => issetDefaultVal($civilPdata['result']['civilpackid'], $selectedRow['ftpscanid']), 'd' => $civilPdata));
        } else {
            echo json_encode(array('ftpscanid' => issetDefaultVal($civilPdata['result']['civilpackid'], $selectedRow['id']), 'd' => $civilPdata));
        }
    }
    
    public function docToPdfByDotNet() {
        
        $docPath = Input::post('docPath');
        
        if (file_exists($docPath)) {
            
            $exportServerAddress = defined('CONFIG_FILECONVERTER_SERVER_ADDRESS') ? CONFIG_FILECONVERTER_SERVER_ADDRESS : (defined('CONFIG_FILE_VIEWER_ADDRESS') ? CONFIG_FILE_VIEWER_ADDRESS : null);
            
            if ($exportServerAddress) {
                
                if (strpos($exportServerAddress, 'fileConverter') !== false) {
                    $exportServerAddress .= 'Converter.aspx';
                } else {
                    $exportServerAddress = str_replace(array('document/', 'document_dev/'), '', $exportServerAddress);
                    $exportServerAddress .= 'fileConverter/Converter.aspx';
                }
                
                $arrContextOptions = array(
                    'http' => array(
                        'timeout' => 5
                    ), 
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    )
                );  

                $checkHeaderStatus = @file_get_contents($exportServerAddress, false, stream_context_create($arrContextOptions));
                
                if ($checkHeaderStatus !== false) {
                        
                    $exportServerAddress .= '?mode=pdf&FromUrl='.URL.$docPath;
                    $ch = curl_init($exportServerAddress);

                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $output = curl_exec($ch);
                    $err = curl_error($ch);

                    curl_close($ch);
                    
                    if ($err) {
                        $response = array('status' => 'error', 'code' => 'curl', 'message' => $err);
                    } else {
                        $pdfFilePath = 'storage/uploads/process/' . getUID(). '.pdf';
                        $isCreate = @file_put_contents($pdfFilePath, $output);
                        
                        if ($isCreate) {
                            $response = array('status' => 'success', 'filePath' => $pdfFilePath);
                        } else {
                            $response = array('status' => 'error', 'message' => 'Файл үүсгэж чадсангүй!');
                        }
                    }
                } else {
                    $response = array('status' => 'error', 'message' => 'File converter холбогдож чадсангүй!');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'File converter тохиргоо хийгдээгүй байна!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Файл олдсонгүй!');
        }
        
        echo json_encode($response);
    }
    
    public function stampedFileByProcess() {

        $postData = Input::postData();
        if (issetParam($postData['stampProcessCode'])) {
            includeLib('Utils/Functions');
            $result = Functions::runProcess($postData['getProcessCode'], array('id' => '1'));

            if (Config::getFromCache('USE_DOCUMENT_ROOT_PATH') !== '1') {
                $param = array(
                            'pdfFileB64' => base64_encode(file_get_contents($postData['pdfPath'])),
                            'stampImageB64' => $result['result']['stampimageb64'],
                            'position' => $postData['position'],
                        );
        
                $resultStamp = Functions::runProcess(issetDefaultVal($postData['stampProcessCode'], 'CMS_SUBJECT_FILE_STAMP_001'), $param);
                $filePath = $this->model->bpTemplateUploadGetPath($path = UPLOADPATH . 'base64Pdf/');
            } else {
                $param = array(
                    'pdfFilePath' => $_SERVER['DOCUMENT_ROOT'] . '/'. $postData['pdfPath'],
                    'stampImageB64' => $result['result']['stampimageb64'],
                    'position' => $postData['position'],
                );

                $resultStamp = Functions::runProcess(issetDefaultVal($postData['stampProcessCode'], 'CMS_SUBJECT_FILE_STAMP_001'), $param);
            }
            
            if (Config::getFromCache('USE_DOCUMENT_ROOT_PATH') === '1' && issetParam($resultStamp['result']['stampedpdfpath']) !== '') {
                $filePath = str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $resultStamp['result']['stampedpdfpath']);
            } else {
                $data = base64_decode($resultStamp['result']['stampedpdfb64']);
                $fileName = getUID().'.pdf';
                $fileRealName = issetDefaultVal($postData['fileName'], $fileName);
                file_put_contents($filePath. $fileName,$data);
            }
            
            $response = array(
                'filePath' => filesize($filePath . $fileName) > 0 ? $filePath . $fileName : $postData['pdfPath'],
                'fileSize' => filesize($filePath . $fileName),
                'fileExtension' => 'pdf',
                'fileName' => $fileRealName,
            );

        } elseif (issetParam($postData['pdfBase64'])) {

            $data = base64_decode(Str::replace('data:application/pdf;base64,', '', $postData['pdfBase64']));
            $fileName = getUID().'.pdf';
            $fileRealName = issetDefaultVal($postData['fileName'], $fileName);
            file_put_contents($filePath. $fileName,$data);

            $response = array(
                'filePath' => $filePath . $fileName,
                'fileSize' => filesize($filePath . $fileName),
                'fileExtension' => 'pdf',
                'fileName' => $fileRealName,
            );
        } else {
            $response = array();
        }
        convJson($response);

    }

    public function bpFileToPath() {
        $fileData = Input::fileData();
        $response =  $this->model->fileUploadArr($fileData['files']);
        convJson($response);
    }

    public function getDataByQrcode() {
        
        $postData = Input::postData();        
        includeLib('Utils/Functions');
        $param = array (
                    'qrNumber' => $postData['filterPath']
                );
        $response = Functions::runProcess('NTR_TRUST_BOOK_QR_GET_LIST_004', $param);
        $ticket = false;
        $listHtml = '';

        if ($response['status'] === 'success') {

            $listHtml .= '<div class="list-group">';
                $index = 0; $counter = 1;
                if (issetParam($response['result']['ntr_customer_a_dv'])) {
                    $ticket = true;
                    foreach ($response['result']['ntr_customer_a_dv'] as $row) {
                        $listHtml .= '<a href="javascript:;" data-rowdata="'. htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8').'"  data-key="' . $index . '" class="list-group-item list-group-item-action">';
                            $listHtml .= $counter .'. '. $row['familyname'] . ', ' . $row['lastname'] . ', ' . $row['firstname'] . ', ';
                        $listHtml .= '</a>';
                        $index++;
                        $counter++;
                    }
                }
                
                /* $counter = 1; */
                if (issetParam($response['result']['ntr_customer_b_dv'])) {
                    $ticket = true;
                    foreach ($response['result']['ntr_customer_b_dv'] as $row) {
                        $listHtml .= '<a href="javascript:;" data-rowdata="'. htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8').'" data-key="' . $index . '" class="list-group-item list-group-item-action">';
                            $listHtml .= $counter .'. '. $row['familyname'] . ', ' . $row['lastname'] . ', ' . $row['firstname'] . ', ';
                        $listHtml .= '</a>';
                        $index++;
                        $counter++;
                    }
                }
            $listHtml .= "</div>";

        }

        if (!$ticket) {
            $listHtml = 'Өгөгдөл олдсонгүй.';
        }

        echo json_encode(array('Html' => $listHtml)); exit;
    }
    
    public function bpFileCheckSize() {
        $fileData = Input::fileData();
        $response = array('text' => '', 'status' => 'success');
        foreach ($fileData['files']['size'] as $size) {
            if ($size > Config::getFromCacheDefault('updateMaxFileSize', null, '26214400')) {
                $text = 'Файлын хэмжээ хэтэрхий их тул оруулах боломжгүй !!! 25мб - аас бага хэмжээтэй зураг оруулна';
                $response = array('text' => $text, 'status' => 'error');
            }
        }
        
        if ($response['status'] === 'success') {
            $response['data'] =  $this->model->fileUploadArr($fileData['files']);
        }

        convJson($response);
    }
    
    
    public function fingerCheckRender () {
        includeLib('Utils/Functions');
        $this->view->isAjax = is_ajax_request();
        $this->view->uniqId = getUID();
        
        $data = Functions::runDataViewWithoutLogin('16920026632653', array());
        $this->view->afisStatusChangeList = issetParamArray($data['result']);
        $data = Functions::runDataViewWithoutLogin('1694417262236542', array());
        $this->view->reqTypeList = issetParamArray($data['result']);

        (Array) $duplicateData = $tmp = $result = array();
        if (Input::post('registerNum')) {
            $param = array(
                'registerNum' => Input::post('registerNum'),
            );
    
            $result = Functions::runProcess('crAfisFingerCheckGetDv_004', $param);
            
            if ( issetParamArray($result['result']['crafisfingercheckgetdtldv'])) {
                $this->view->id = issetParam($result['result']['id']);
                foreach ($result['result']['crafisfingercheckgetdtldv'] as $key => $row) {
                    # code...
                    if (
                        issetParam($row['ftpprocess']) !== '' && 
                        issetParam($row['outputparam']) !== '' && 
                        issetParam($row['inputparam']) !== '' && 
                        issetParam($row['srcftppath']) !== '' && 
                        issetParam($row['replacepath']) !== ''
                    ) {
                        (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                        $processArr = $row['ftpprocess'];
                        $outputparam = $row['outputparam'];
                        $inputparam = $row['inputparam'];
                        $srcftppath = $row['srcftppath'];
                        $replacepath = $row['replacepath'];
    
                        $rFtp = Functions::runProcess($processArr, array($inputparam => $row[$srcftppath]));
                        
                        if (issetParam($rFtp['result'][$outputparam])) {
                            $row[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                        }
                    }
    
                    if (issetParamArray($row['fingerleftdv'])) {
                        $fingerleftdv = array();
                        foreach ($row['fingerleftdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
                            }
    
                            array_push($fingerleftdv, $subRow);
                        }
    
                        $row['fingerleftdv'] = $fingerleftdv;
                    }
    
                    if (issetParamArray($row['fingerrightdv'])) {
                        $fingerrightdv = array();
                        foreach ($row['fingerrightdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
    
                            }
                            array_push($fingerrightdv, $subRow);
                        }
    
                        $row['fingerrightdv'] = $fingerrightdv;
                    }
    
                    array_push($tmp, $row);
                }
            }
            if ( issetParamArray($result['result']['crafisfingerduplicatedv'])) {
                $this->view->id = issetParam($result['result']['id']);
                foreach ($result['result']['crafisfingerduplicatedv'] as $key => $row) {
                    # code...
                    if (
                        issetParam($row['ftpprocess']) !== '' && 
                        issetParam($row['outputparam']) !== '' && 
                        issetParam($row['inputparam']) !== '' && 
                        issetParam($row['srcftppath']) !== '' && 
                        issetParam($row['replacepath']) !== ''
                    ) {
                        (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                        $processArr = $row['ftpprocess'];
                        $outputparam = $row['outputparam'];
                        $inputparam = $row['inputparam'];
                        $srcftppath = $row['srcftppath'];
                        $replacepath = $row['replacepath'];
    
                        $rFtp = Functions::runProcess($processArr, array($inputparam => $row[$srcftppath]));
                        
                        if (issetParam($rFtp['result'][$outputparam])) {
                            $row[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                        }
                    }
    
                    if (issetParamArray($row['fingerleftdv'])) {
                        $fingerleftdv = array();
                        foreach ($row['fingerleftdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
                            }
    
                            array_push($fingerleftdv, $subRow);
                        }
    
                        $row['fingerleftdv'] = $fingerleftdv;
                    }
    
                    if (issetParamArray($row['fingerrightdv'])) {
                        $fingerrightdv = array();
                        foreach ($row['fingerrightdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
    
                            }
                            array_push($fingerrightdv, $subRow);
                        }
    
                        $row['fingerrightdv'] = $fingerrightdv;
                    }
    
                    array_push($duplicateData, $row);
                }
            }
        }

        $this->view->registerData = $tmp;
        $this->view->duplicateData = $duplicateData;
        
        if (!$this->view->isAjax) {
            $this->view->css = AssetNew::metaCss();
            $this->view->js = AssetNew::metaOtherJs(array_merge(AssetNew::highchartJs()));
            $this->view->fullUrlJs = AssetNew::metaJs();
            $this->view->render('header');
        }

        if ($this->view->isAjax) {

            (Array) $respone = array(
                'Html' => $this->view->renderPrint('/civil/search', self::$viewPath),
                'Data' => $result,
            );

        } else {
            $this->view->render('/civil/finger', self::$viewPath);
        }
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function findRegisterAfis() {
        includeLib('Utils/Functions');
        $this->view->isAjax = is_ajax_request();
        
        $data = Functions::runDataViewWithoutLogin('16920026632653', array());
        $this->view->afisStatusChangeList = issetParamArray($data['result']);
        $data = Functions::runDataViewWithoutLogin('1694417262236542', array());
        $this->view->reqTypeList = issetParamArray($data['result']);
        
        (Array) $duplicateData = $tmp = $result = array();
        if (Input::post('registerNum')) {
            $param = array(
                'registerNum' => Input::post('registerNum'),
            );
    
            $result = Functions::runProcess('crAfisFingerCheckGetDv_004', $param);
            
            if ( issetParamArray($result['result']['crafisfingercheckgetdtldv'])) {
                $this->view->id = issetParam($result['result']['id']);
                foreach ($result['result']['crafisfingercheckgetdtldv'] as $key => $row) {
                    # code...
                    if (
                        issetParam($row['ftpprocess']) !== '' && 
                        issetParam($row['outputparam']) !== '' && 
                        issetParam($row['inputparam']) !== '' && 
                        issetParam($row['srcftppath']) !== '' && 
                        issetParam($row['replacepath']) !== ''
                    ) {
                        (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                        $processArr = $row['ftpprocess'];
                        $outputparam = $row['outputparam'];
                        $inputparam = $row['inputparam'];
                        $srcftppath = $row['srcftppath'];
                        $replacepath = $row['replacepath'];
    
                        $rFtp = Functions::runProcess($processArr, array($inputparam => $row[$srcftppath]));
                        
                        if (issetParam($rFtp['result'][$outputparam])) {
                            $row[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                        }
                    }
    
                    if (issetParamArray($row['fingerleftdv'])) {
                        $fingerleftdv = array();
                        foreach ($row['fingerleftdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
                            }
    
                            array_push($fingerleftdv, $subRow);
                        }
    
                        $row['fingerleftdv'] = $fingerleftdv;
                    }
    
                    if (issetParamArray($row['fingerrightdv'])) {
                        $fingerrightdv = array();
                        foreach ($row['fingerrightdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
    
                            }
                            array_push($fingerrightdv, $subRow);
                        }
    
                        $row['fingerrightdv'] = $fingerrightdv;
                    }
    
                    array_push($tmp, $row);
                }
            }
            if ( issetParamArray($result['result']['crafisfingerduplicatedv'])) {
                $this->view->id = issetParam($result['result']['id']);
                foreach ($result['result']['crafisfingerduplicatedv'] as $key => $row) {
                    # code...
                    if (
                        issetParam($row['ftpprocess']) !== '' && 
                        issetParam($row['outputparam']) !== '' && 
                        issetParam($row['inputparam']) !== '' && 
                        issetParam($row['srcftppath']) !== '' && 
                        issetParam($row['replacepath']) !== ''
                    ) {
                        (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                        $processArr = $row['ftpprocess'];
                        $outputparam = $row['outputparam'];
                        $inputparam = $row['inputparam'];
                        $srcftppath = $row['srcftppath'];
                        $replacepath = $row['replacepath'];
    
                        $rFtp = Functions::runProcess($processArr, array($inputparam => $row[$srcftppath]));
                        
                        if (issetParam($rFtp['result'][$outputparam])) {
                            $row[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                        }
                    }
    
                    if (issetParamArray($row['fingerleftdv'])) {
                        $fingerleftdv = array();
                        foreach ($row['fingerleftdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
                            }
    
                            array_push($fingerleftdv, $subRow);
                        }
    
                        $row['fingerleftdv'] = $fingerleftdv;
                    }
    
                    if (issetParamArray($row['fingerrightdv'])) {
                        $fingerrightdv = array();
                        foreach ($row['fingerrightdv'] as $subRow) {
                            # code...
                            if (
                                issetParam($subRow['ftpprocess']) !== '' && 
                                issetParam($subRow['outputparam']) !== '' && 
                                issetParam($subRow['inputparam']) !== '' && 
                                issetParam($subRow['srcftppath']) !== '' && 
                                issetParam($subRow['replacepath']) !== ''
                            ) {
                                (Array) $processArr = $outputparam = $replacepath = $inputparam = $srcftppath = array();
                                $processArr = $subRow['ftpprocess'];
                                $outputparam = $subRow['outputparam'];
                                $inputparam = $subRow['inputparam'];
                                $srcftppath = $subRow['srcftppath'];
                                $replacepath = $subRow['replacepath'];
    
                                $rFtp = Functions::runProcess($processArr, array($inputparam => $subRow[$srcftppath]));
                                
                                if (issetParam($rFtp['result'][$outputparam])) {
                                    $subRow[$replacepath] = issetParam($rFtp['result'][$outputparam]);
                                }
    
                            }
                            array_push($fingerrightdv, $subRow);
                        }
    
                        $row['fingerrightdv'] = $fingerrightdv;
                    }
    
                    array_push($duplicateData, $row);
                }
            }
        }

        $this->view->registerData = $tmp;
        $this->view->duplicateData = $duplicateData;
        
        (Array) $respone = array(
            'Html' => $this->view->renderPrint('/civil/search', self::$viewPath),
            'Data' => $result,
            'registerData' => $this->view->registerData,
            'duplicateData' => $this->view->duplicateData,
        );
        jsonResponse($respone);

    }

    public function saveFingerSearchForm() {
        
        $postData = Input::postData();
        (Array) $result = array(
            'status' => 'error',
            'text' => Lang::lineCode('msg_save_error'),
            'post' => $postData,
        );
        jsonResponse($result);
        
        $_POST['responseType'] = 'outputArray';
        $_POST['nult'] = true;
        $_POST['methodId'] = '16853505517663';
        $_POST['processSubType'] = 'internal';
        $_POST['create'] = '0';
        $_POST['isSystemProcess'] = 'true';
        /* $_POST['param']['id'] = $postData['mainId']; */
        
        $result = (new Mdwebservice())->runProcess();
        
        jsonResponse($result);
    }
    
}
