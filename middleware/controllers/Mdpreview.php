<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdpreview Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Preview
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdpreview
 */

class Mdpreview extends Controller {
    
    private static $viewPath = 'middleware/views/preview/';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function renderToolbar($pageProperties) {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->metaDataId = $pageProperties['metaDataId'];
        $this->view->dataViewId = $pageProperties['dataViewId'];
        
        if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS && isset($pageProperties['reportLayoutId'])) {
            
            $this->view->defaultUrl = CONFIG_REPORT_SERVER_ADDRESS . 'Viewer.aspx?';
            $this->view->layoutId   = $pageProperties['reportLayoutId'];
            
            if ($sdbid = Session::get(SESSION_PREFIX . 'sdbid')) {
                $this->view->defaultUrl .= 'dbId=' . $sdbid;
            }
            
            if (true) {
                $this->view->defaultUrl .= '&ignoreExport=0';
            }
            
            $this->view->reportUrl = $this->view->defaultUrl;
            
            if (isset($pageProperties['reportId'])) {
                
                $this->view->reportUrl .= '&reportid=' . $pageProperties['reportId'] . '&layoutId=' . $this->view->layoutId;
                
                if (isset($pageProperties['expandReportId'])) { 
                    $this->view->reportUrl .= '&subReportIds=' . $pageProperties['expandReportId'];
                }
            }

            return $this->view->renderPrint('iframeReport', self::$viewPath);  
        } 
        
        $this->view->pageProperty = $pageProperties;
        
        $this->view->contentHtml = '';

        $this->view->style = '';
        $this->view->style .= 'padding-top: '.$pageProperties['pageMarginTop'].';';
        $this->view->style .= 'padding-left: '.$pageProperties['pageMarginLeft'].';';
        $this->view->style .= 'padding-right: '.$pageProperties['pageMarginRight'].';';
        $this->view->style .= 'padding-bottom: '.$pageProperties['pageMarginBottom'].';';

        if ($fontFamily = issetParam($pageProperties['fontFamily'])) {
            $this->view->style .= 'font-family: '.$fontFamily.';';
        }
        
        if ($fontSize = issetParam($pageProperties['fontSize'])) {
            $this->view->style .= 'font-size: '.$fontSize.';';
        }

        if (isset($pageProperties['contentHtml'])) {
            $this->view->contentHtml = $pageProperties['contentHtml'];
        }
        
        if (Config::getFromCache('isCheckStatementExportPermission')) {
            
            $this->load->model('mdpreview', 'middleware/models/');
            $check = $this->model->isCheckStatementExportPermissionModel();
            
            if (!$check) {
                $this->view->pageProperty['pagePrint'] = false;
                $this->view->pageProperty['pagePdf'] = false;
                $this->view->pageProperty['pagePdfView'] = false;
                $this->view->pageProperty['pageExcel'] = false;
                $this->view->pageProperty['pageWord'] = false;
            }
        }

        return $this->view->renderPrint('control', self::$viewPath);
    }  
    
    public static function printCss($mode = null) {
        
        $orientation = Input::post('orientation');
        $size = Input::post('size');
        $top = Input::post('top');
        $left = Input::post('left');
        $bottom = Input::post('bottom');
        $right = Input::post('right');
        $width = Input::post('width');
        $height = Input::post('height');
        $fontFamily = Input::post('fontFamily');
        $fontSize = Input::post('fontSize');
        
        $bodyMinWidth = '';
        
        $isCustom = false;
        
        if ($size == 'custom') {
            
            $isCustom = true;
            $size = $width.' '.$height;
            $widthHeight = 'width: '.$width.'; height: '.$height.';';
            
        } else {
            if ($orientation == 'landscape') {
                $widthHeight = 'width: 29.7cm; height: 21cm;';
                $width = '29.7cm';
            } else {
                $widthHeight = 'width: 21cm; height: 29.7cm;';
                $width = '21cm';
            }
        }
        
        if (empty($fontFamily)) {
            $fontFamily = 'Arial, Helvetica, sans-serif';
        }
        
        if (empty($fontSize)) {
            $fontSize = '12px';
        }
        
        if ($isCustom == false) {
            
            if ($mode == 'statementPdf') {
                
                $cssDpi = 'div.print-width-dpi {
                    display: block;
                    min-width: '.($orientation == 'landscape' ? '1080' : '880').'px;
                    height: 1px;
                }';
                
            } else {
                
                $minWidth = ($orientation == 'landscape' ? '1202' : '880');
                
                $cssDpi = 'div.print-width-dpi {
                    display: block;
                    min-width: '.$minWidth.'px;
                    height: 1px;
                }';
                
                $bodyMinWidth = 'min-width: '.$minWidth.'px;';
            }
            
        } else {
            $cssDpi = 'div.print-width-dpi {
                display: none;
            }';
        }
        
        $css = '';
        
        if ($mode == null || $mode == 'return') {
            
            $css .= '@page {
                margin-top: '.$top.';
                margin-right: '.$right.';
                margin-bottom: '.$bottom.';
                margin-left: '.$left.';';

            if ($isCustom == false) {
                $css .= 'size: '.$size.';
                    size: '.$orientation.';
                    '.$widthHeight;
            }

            $css .= 'background: white; }';
        }
        
        $css .= '* {
        -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
                box-sizing: border-box;
        }
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
                box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            line-height: 1.4em;
            font: '.$fontSize.' '.$fontFamily.';
            color: #000;
            width: 100%; 
            '.$bodyMinWidth.'  
            -webkit-print-color-adjust: exact;
        }
        a, a:visited, a:hover, a:active {
            color: inherit; 
            text-decoration: none; 
        } 
        a:after { content:\'\'; } 
        a[href]:after { content: none !important; } 
        .navbar, .sidebar-nav {
            display: none;
        }
        p {
            margin: 0 0 10px;
        } 
        hr {
            margin: 20px 0;
            border: 0;
            border-top: 1px solid #ddd;
            border-bottom: 0;
            width: 100%;
        }
        table {
            table-layout: fixed; 
            clear: both;
            border-collapse: collapse;
            page-break-after: auto;
            font-size: 12px;
            border-color: grey;
        }
        tr { page-break-inside:avoid; page-break-after:auto }
        td { page-break-inside:avoid; page-break-after:auto }
        thead {
            display: table-header-group; 
        }
        tbody {
           display: table-row-group;
        }
        tfoot {
            display: table-footer-group;
        }
        table thead th, table thead td, table tbody td, table tfoot td {
            overflow: hidden; 
            word-wrap: break-word;
            padding: 2px 3px;
            line-height: 12px;
        }
        table thead th ul, table thead td ul, table tbody td ul, table tfoot td ul {
            margin-bottom: 0px;
            padding-left: 14px;
        }
        table.pf-repeat-page-header thead td, 
        table.pf-repeat-page-header thead th, 
        table.pf-repeat-page-header tbody td, 
        table.pf-repeat-page-header tfoot td {
            padding: 2px 3px !important;
        }
        table.pf-repeat-page-header tbody td table tbody td, 
        table.pf-repeat-page-header tbody td table thead td, 
        table.pf-repeat-page-header tbody td table thead th, 
        table.pf-repeat-page-header tbody td table tfoot td {
            padding: 2px 0 !important;
        }
        table tbody td table tbody td, 
        table tbody td table thead td, 
        table tbody td table thead th, 
        table tbody td table tfoot td {
            padding: 2px 0 !important;
        }
        table[border="1"] > thead > tr > td, 
        table[border="1"] > thead > tr > th, 
        table[border="1"] > tbody > tr > td, 
        table[border="1"] > tbody > tr > th, 
        table[border="1"] > tfoot > tr > td, 
        table[border="1"] > tfoot > tr > th {
            border: 1px #777 solid !important;
        }
        .pf-st-group-tbl > table[border="1"] {
            width: 100.1% !important;
        }
        td.pf-st-group-tbl, 
        table.pf-repeat-page-header[border="1"] tbody td.pf-st-group-tbl {
            padding: 0 !important;
        }
        table.pf-repeat-page-header tbody td table[border="1"] tfoot td {
            padding: 2px 3px !important;
        }
        .pf-st-group-tbl > table[border="1"] > thead > tr > th {
            border: 1px #777 solid !important;
        }
        .pf-st-group-tbl > table[border="1"] > thead > tr > td {
            border: 1px #777 solid !important;
        }
        .pf-st-group-tbl > table[border="1"] > tbody > tr > td {
            border: 1px #777 solid !important;
        }
        .pf-st-group-tbl > table[border="1"] > tfoot > tr > td {
            border: 1px #777 solid !important;
        }
        .pf-st-group-tbl > table[border="1"] > tfoot > tr:first-child > td {
          border-top: 1px #fff solid !important;
        }
        .pf-st-group-tbl > table[border="1"] > tfoot > tr > td:first-child {
          border-left: 1px #fff solid !important;
        }
        .pf-st-group-tbl > table[border="1"] > tfoot > tr:last-child > td {
          border-bottom: 1px #fff solid !important;
        }
        .pf-st-group-tbl > table[border="1"] > tfoot > tr > td:last-child {
          border-right: 1px #fff solid !important;
        }
        .right-rotate {
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
            white-space: nowrap;
            word-wrap: break-word;
        }
        .left-rotate {
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
            white-space: nowrap;
            word-wrap: break-word;
        }
        .right-rotate span, .left-rotate span {
            display: block;
        }
        table.pf-report-table-none, 
        table.pf-report-table-none td, 
        table.pf-report-table-none th {
            border: 0px #fff solid;
        }
        table.pf-report-table-dotted, 
        table.pf-report-table-dotted td, 
        table.pf-report-table-dotted th {
            border: 1px #000 dotted;
        }
        table.pf-report-table-dashed, 
        table.pf-report-table-dashed td, 
        table.pf-report-table-dashed th {
            border: 1px #000 dashed;
        }
        table.pf-report-table-solid, 
        table.pf-report-table-solid td, 
        table.pf-report-table-solid th {
            border: 1px #000 solid;
        }
        table:has(thead) {
            min-width: 100%; 
        }';
        
        $css .= $cssDpi;
        
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n",'    ','     '), '', $css);
        $css = str_replace(';}','}', $css);
        
        if ($mode == 'statementPdf' || $mode == 'return') {
            
            ob_start("ob_html_compress");
            $compressCss = $css;
            ob_end_flush();
            
            return $compressCss;
            
        } else {
            ob_start("ob_html_compress");
                echo $css;
            ob_end_flush();
            exit;
        }
    }
    
    public function fileViewer() {
        
        Auth::handleLogin();
        
        $this->view->rowId = Input::numeric('rowId');
        $this->view->fileExtension = strtolower(Input::post('fileExtension'));
        $this->view->fileName = Input::post('fileName');
        $this->view->fullPath = Input::post('fullPath');
        $this->view->contentId = Input::numeric('contentId');
        $this->view->isIgnoreDownload = Input::numeric('isIgnoreDownload');
        $this->view->isIgnoreToolbarPrint = Input::numeric('isIgnoreToolbarPrint');
        
        $this->model->createEcmContentLogModel($this->view->contentId, 1);
        
        $contentRow = $this->model->getEcmContentRowModel($this->view->contentId);
        $selectedRow = Input::post('selectedRow');
        
        if (isset($selectedRow['wfmstatusid']) && isset($selectedRow['wfmstatuscode'])) {
            
            $this->view->refStructureId = Input::post('refStructureId');
            
            if ($this->view->refStructureId) {
                $this->load->model('mdtemplate', 'middleware/models/');
                
                $this->view->metaDataId = Input::post('dvId');
                $nextStatus = $this->model->getWfmNextStatusByRow($this->view->metaDataId, $selectedRow);

                if ($nextStatus) {
                    $this->view->statusList = $nextStatus;
                    $this->view->statusButtons = $this->view->renderPrint('wfmStatus', self::$viewPath);
                }
            }
        }
        
        $response = array(
            'html' => $this->view->renderPrint('fileViewer', self::$viewPath),  
            'close_btn' => $this->lang->line('close_btn')
        );
        
        if (!$contentRow && $this->view->fileName && $this->view->fullPath) {
            if (file_exists($this->view->fileName)) {
                $fileInfo = pathinfo($this->view->fileName); 
                $this->view->fileName = $fileInfo['basename'];
            }
            $contentRow = array(
                'FILE_NAME'     => $this->view->fileName, 
                'PHYSICAL_PATH' => str_replace(URL, '', $this->view->fullPath)
            );
        }
        
        if ($contentRow) {
            $response['title'] = ($contentRow['FILE_NAME'] && !file_exists($contentRow['FILE_NAME'])) ? $contentRow['FILE_NAME'] : '';
            $response['fullPath'] = 'mdobject/downloadFile?fDownload=1&file='.$contentRow['PHYSICAL_PATH'].'&fileName='.$contentRow['FILE_NAME'];
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function filePreview() {
        
        Auth::handleLogin();
        
        $this->view->fileExtension = '';
        $this->view->fullPath = '';
        $this->view->rowId = getUID();
        $selectedRow = Input::post('selectedRow');
        $isJson = true;
        
        if (Input::post('isWorkFlow') == '1') {
            $decodedRow = Arr::decode($selectedRow);
            $selectedRow = $decodedRow['dataRow'];
            $isJson = false;
        }
        
        if (isset($selectedRow['physicalpath'])) {
            
            $this->view->fileExtension = strtolower(substr($selectedRow['physicalpath'], strrpos($selectedRow['physicalpath'], '.') + 1));
            $this->view->fullPath = URL.$selectedRow['physicalpath'];
            $html = $this->view->renderPrint('filePreview', self::$viewPath);
            
        } else {
            $html = html_tag('div', array('class' => 'alert alert-warning'), 'File not fount!');
        }
        
        if ($isJson) {
            $response = array('html' => $html, 'uniqId' => $this->view->rowId);
            echo json_encode($response); 
        } else {
            echo $html;
        }

        exit;
    }
    
    public function fileViewerForWebLink() {
        
        Auth::handleLogin();
        
        $this->view->fullPath = Input::post('fullPath');
        
        if (file_exists($this->view->fullPath)) {
            
            $this->view->fullPath = URL . $this->view->fullPath;
            $this->view->rowId = Input::post('rowId');
            $this->view->fileExtension = strtolower(Input::post('fileExtension'));
            $this->view->contentId = Input::post('contentId');
            $this->view->statusButtons = '';

            $this->model->createEcmContentLogModel($this->view->rowId, 1);

            $selectedRow = Input::post('selectedRow');

            if (isset($selectedRow['wfmstatusid']) && isset($selectedRow['wfmstatuscode'])) {

                $this->view->refStructureId = Input::post('refStructureId');

                if ($this->view->refStructureId) {
                    $this->load->model('mdtemplate', 'middleware/models/');

                    $this->view->metaDataId = Input::post('dvId');
                    $nextStatus = $this->model->getWfmNextStatusByRow($this->view->metaDataId, $selectedRow);

                    if ($nextStatus) {
                        $this->view->statusList = $nextStatus;
                        $this->view->statusButtons = $this->view->renderPrint('wfmStatus', self::$viewPath);
                    }
                }
            }

            $response = array(
                'html' => $this->view->renderPrint('fileViewerWebLink', self::$viewPath),  
                'wfmHtml' => $this->view->statusButtons,
                'close_btn' => $this->lang->line('close_btn')
            );
        
        } else {
            $response = array('status' => 'error');
        }
        
        echo json_encode($response);
    }    
    
    public function contentViewerById() {
        
        $response = array('status' => 'info', 'message' => 'Файл олдсонгүй!');
        
        $contentData = array();
        $recordIds = Input::post('recordIds');
        
        if ($recordIds) {
            $contentData = $this->model->getContentByRecordIdsModel($recordIds);
        } else {
            $contentIds = Input::post('contentIds');
            if ($contentIds) {
                $contentData = $this->model->getContentByIdsModel($contentIds);
            }
        }
        
        if ($contentData) {
            
            $this->view->uniqId = Input::post('uniqId');
            $this->view->fileArr = array();
            
            foreach ($contentData as $filePath) {

                if (file_exists($filePath['PHYSICAL_PATH'])) {
                    
                    $extension = $filePath['FILE_EXTENSION'];
                    
                    if (!$extension) {
                        $pathInfo = pathinfo($filePath['PHYSICAL_PATH']);
                        $extension = $pathInfo['extension'];
                    }

                    if (Input::post('useStampedFileView') === '1') {
                        $fullPathPic = Config::get('stamp_filepath');

                        $params = array(
                            'stampImageB64' => base64_encode(file_get_contents($fullPathPic)),
                            'pdfFileB64' => base64_encode(file_get_contents($filePath['PHYSICAL_PATH'])),
                            /* 'pdfFilePath' => $_SERVER['DOCUMENT_ROOT'] . '/' . $filePath['PHYSICAL_PATH'], */
                            'position' => 't_right',
                        );

                        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'NTR_CONSUL_STAMP_BP', $params);

                        if (issetParam($result['result']['stampedpdfb64'])) {
                            $tmpFilePathArr = $filePathArr = explode('/', $filePath['PHYSICAL_PATH']);
                            $tmpFilePathArr[sizeOf($tmpFilePathArr)-1] = 'stamped';
                            $stampledFilePath = implode('/', $tmpFilePathArr);

                            if (!is_dir($stampledFilePath)) {
                                mkdir($stampledFilePath, 0777, true);
                            }

                            Mdcache::createCacheFolder($stampledFilePath, 0.5);

                            $filePathArr[sizeOf($filePathArr)-1] = 'stamped/' . $filePathArr[sizeOf($filePathArr)-1];
                            $stampledFilePath = implode('/', $filePathArr);
                            
                            $pdf_b64 = base64_decode($result['result']['stampedpdfb64']);
                            file_put_contents($stampledFilePath, $pdf_b64);

                            $filePath['PHYSICAL_PATH'] = $stampledFilePath;
                        }
                    }

                    $this->view->fileArr[] = array(
                        'contentId'    => $filePath['CONTENT_ID'], 
                        'path'         => $filePath['PHYSICAL_PATH'], 
                        'thumbPath'    => $filePath['THUMB_PHYSICAL_PATH'], 
                        'versionCount' => $filePath['VERSION_COUNT'], 
                        'extention'    => $extension, 
                        'name'         => ($filePath['FILE_NAME'] ? $filePath['FILE_NAME'] : 'File name is empty!')
                    );
                    
                    $isFile = true;
                }
            }
            
            if (isset($isFile)) {
                
                $this->view->isIframe = false;
                
                if (defined('CONFIG_FILE_VIEWER_ADDRESS') && CONFIG_FILE_VIEWER_ADDRESS) {
                    $this->view->isIframe = true;
                }
                
                $this->view->isFilePreviewLog = Config::getFromCache('IS_FILE_PREVIEW_LOG');
                
                $response = array(
                    'status' => 'success', 
                    'html' => $this->view->renderPrint('multiFileViewer', self::$viewPath)
                );
            }
        }
        
        echo json_encode($response); exit;
    }
    
    public function runPrintLogProcess() {
        
        Auth::handleLogin();
        
        $bpCode = Input::post('bpCode');
        $contentId = Input::numeric('contentId');
        
        if ($bpCode && $contentId) {
            
            $param = array(
                'contentId' => $contentId, 
                'createdUserId' => Ue::sessionUserKeyId(), 
                'systemUserId' => Ue::sessionUserId(), 
                'createdDate' => Date::currentDate('Y-m-d H:i:s')
            );

            $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, $bpCode, $param);

            if ($result['status'] == 'success') {
                $response = array('status' => 'success');
            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid parameters!');
        }
        
        jsonResponse($response);
    }
    
    public function getEcmContentFiles() {
        Auth::handleLogin();
        
        $structureId = Input::numeric('structureId');
        $recordId = Input::numeric('recordId');
        
        $contentData = $this->model->getContentByRecordIdsModel($recordId, $structureId);
        
        echo json_encode($contentData, JSON_UNESCAPED_UNICODE);
    }
    
}