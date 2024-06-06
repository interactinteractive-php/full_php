<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdprocess Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Business process
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdprocess
 */

class Mdprocess extends Controller {
    
    private static $viewPath = 'middleware/views/webservice/';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function bpAddPhotoFromWebcam() {
        Auth::handleLogin();
        
        $this->view->metaDataId  = Input::numeric('metaDataId');
        $this->view->metaValueId = Input::numeric('metaValueId');
        
        $response = array(
            'html' => $this->view->renderPrint('addon/sub/bpAddPhotoFromWebcam', self::$viewPath),
            'title' => 'Веб камер',
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function savePhotoFromWebcam() {
        Auth::handleLogin();
        $response = $this->model->savePhotoFromWebcamModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function addBpUploadPhoto($useOrigImage = '0', $returnType='json') {
        Auth::handleLogin();
        
        $result = array();
        
        if (isset($_FILES['bp_photo'])) {

            $photo_arr = Arr::arrayFiles($_FILES['bp_photo']);
            
            $photo_original = Mdwebservice::bpUploadCustomPath('/metavalue/photo_temp/original/');
            $photo_thumb    = Mdwebservice::bpUploadCustomPath('/metavalue/photo_temp/thumb/');
            
            foreach ($photo_arr as $p => $photo) {
                if ($photo['name'] != '') {
                    
                    $newPhotoName = 'photo_' . getUID() . $p;
                    $photoExtension = strtolower(substr($photo['name'], strrpos($photo['name'], '.') + 1));
                    $photoName = $newPhotoName . '.' . $photoExtension;
                    
                    Upload::$File = $photo;
                    Upload::$method = 0;
                    Upload::$SavePath = $photo_original;
                    Upload::$ThumbPath = $photo_thumb;
                    Upload::$NewWidth = 1000;
                    Upload::$TWidth = 150;
                    Upload::$NewName = $newPhotoName;
                    Upload::$OverWrite = true;
                    $uploadError = Upload::UploadFile();
                    
                    if ($uploadError == '') {
                        
                        $origImage = $photo_original.$photoName;
                        $thumbImage = $photo_thumb.$photoName;
                        $mimeType = getMimetypeByExtension($photoExtension);

                        if ($useOrigImage) {
                            
                            $result[] = array(
                                'extension'       => $photoExtension, 
                                'mimeType'        => $mimeType, 
                                'origImage'       => $origImage, 
                                'fileName'        => $newPhotoName, 
                                'thumbImage'      => $thumbImage
                            );
                            
                        } else {
                            $thumbImageData = base64_encode(file_get_contents($thumbImage));
                            $origImageData = base64_encode(file_get_contents($origImage));
                            
                            $result[] = array(
                                'extension'       => $photoExtension, 
                                'mimeType'        => $mimeType, 
                                'thumbBase64Data' => $thumbImageData, 
                                'origBase64Data'  => $origImageData
                            );
                            
                            @unlink($thumbImage);
                            @unlink($origImage);
                        }
                    }
                }
            }
            
            if (!empty($result)) {
                $response = array('status' => 'success', 'imageData' => $result, 'message' => 'Амжилттай нэмлээ');
            } else {
                $response = array('status' => 'error', 'message' => 'Алдаа гарлаа');
            }
        } else {
            $response = array('status' => 'info', 'message' => '');
        }
        if ($returnType === 'json') {
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            return $response;
        }
    }
    
    public function addBpUploadBannerPhoto() {
        Auth::handleLogin();
        
        $result = self::addBpUploadPhoto('1', '');
        $bannerProcessTypeId = Input::post('bannerProcessTypeId');
        
        if (issetParamArray($result['imageData'])) {
            $currentDate = Date::currentDate();
            foreach ($result['imageData'] as $key => $row) {
                $data = array(
                    'CONTENT_ID' => getUID(),
                    'CONTENT_NAME' => $row['fileName'],
                    'CONTENT_DATA' => $row['origImage'],
                    'CONTENT_TYPE_ID' => $bannerProcessTypeId,
                    'IS_ACTIVE' => '1',
                    'CREATED_DATE' => $currentDate,
                    'CONTENT_TYPE' => 'photo',
                );
                
                $this->db->AutoExecute('META_PROCESS_CONTENT', $data);
            }
        }

        convJson($result);
    }

    public function deleteProcessBanner() {
        $this->db->AutoExecute('META_PROCESS_CONTENT', array('IS_ACTIVE' => '1'), 'UPDATE', "CONTENT_ID = " . Input::post('id'));
        $response = array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
    }
    
    public function addBpTmpUploadPhoto() {
        Auth::handleLogin();
        
        $result = array();
        
        if (isset($_FILES['bp_attach'])) {

            $photo = $_FILES['bp_attach'];
            
            $photoKey = key($photo['name']);

            if ($photo['name'][$photoKey][0] != '') {
                
                $photo_original = Mdwebservice::bpUploadCustomPath('/metavalue/photo_temp/original/');
                $photo_thumb    = Mdwebservice::bpUploadCustomPath('/metavalue/photo_temp/thumb/');
            
                $photo = array(
                    'name' => $photo['name'][$photoKey][0],
                    'type' => $photo['type'][$photoKey][0],
                    'tmp_name' => $photo['tmp_name'][$photoKey][0],
                    'error' => $photo['error'][$photoKey][0],
                    'size' => $photo['size'][$photoKey][0]
                );
                
                $newPhotoName = 'photo_' . getUID();
                $photoExtension = strtolower(substr($photo['name'], strrpos($photo['name'], '.') + 1));
                $photoName = $newPhotoName . '.' . $photoExtension;
                
                Upload::$File = $photo;
                Upload::$method = 0;
                Upload::$SavePath = $photo_original;
                Upload::$ThumbPath = $photo_thumb;
                Upload::$NewWidth = 1000;
                Upload::$TWidth = 150;
                Upload::$NewName = $newPhotoName;
                Upload::$OverWrite = true;
                $uploadError = Upload::UploadFile();

                if ($uploadError == '') {

                    $origImage = $photo_original.$photoName;
                    $thumbImage = $photo_thumb.$photoName;
                    $thumbImageData = base64_encode(file_get_contents($thumbImage));
                    $origImageData = base64_encode(file_get_contents($origImage));
                    $mimeType = getMimetypeByExtension($photoExtension);

                    $result = array(
                        'extension' => $photoExtension, 
                        'mimeType' => $mimeType, 
                        'thumbBase64Data' => $thumbImageData, 
                        'origBase64Data' => $origImageData
                    );

                    @unlink($thumbImage);
                    @unlink($origImage);
                }
            }
            
            if (!empty($result)) {
                $response = array('status' => 'success', 'imageData' => $result, 'message' => 'Амжилттай нэмлээ');
            } else {
                $response = array('status' => 'error', 'message' => 'Алдаа гарлаа');
            }
            
        } else {
            $response = array('status' => 'info', 'message' => '');
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function bpTmpAddPhotoFromWebcam() {
        Auth::handleLogin();
        
        $this->view->metaDataId = '';
        $this->view->metaValueId = '';
        
        $response = array(
            'html' => $this->view->renderPrint('addon/sub/bpAddPhotoFromWebcam', self::$viewPath),
            'title' => 'Веб камер',
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function bpGenerateQRcode() {
        Auth::handleLogin();
        
        $data = Input::post('qrcodeString');
        
        if ($data == '') {
            return '';
        }
        
        includeLib('QRCode/qrlib');
        
        ob_start();
            
        QRcode::png($data, false, 'L', 10, 3);
        $imageData = ob_get_contents();

        ob_end_clean();
        
        jsonResponse(base64_encode($imageData));        
    }
    
    public function getTestCaseSaveForm() {
        Auth::handleLogin();
        
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $this->view->systemList = $this->model->getDataMartDvRowsModel('1577069094972856');
        $this->view->typeList = $this->model->getDataMartDvRowsModel('1586846011802913');
        /*$this->view->scenarioList = $this->model->getDataMartDvRowsModel('16297205243121');*/

        $this->view->render('addon/testcase/save-form', self::$viewPath);
    }
    
    public function runTestCase() {
        Auth::handleLogin();
        
        $response = $this->model->runTestCaseModel();
        jsonResponse($response);
    }
    
    public function runAllTestCase() {
        Auth::handleLogin();
        
        $response = $this->model->runAllTestCaseModel();
        jsonResponse($response);
    }
    
    public function downloadDetailExcelTemplate() {
        Auth::handleLogin();
        
        includeLib('Office/Excel/PHPExcel');
        includeLib('Office/Excel/PHPExcel/Writer/Excel2007');
        
        $title = Config::getFromCache('TITLE');
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator($title)->setCompany($title);
        
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $startRowIndex = 1;
        $headerData = Input::post('headerData');
        $headerCount = count($headerData);
        
        $sheet->setTitle(Str::excelSheetName(Str::utf8_substr('Detail', 0, 31)));
        
        foreach ($headerData as $key => $row) {
                
            $columnNumberIndex = $key + 1;
            $alphaCol = numToAlpha($columnNumberIndex);
            
            $sheet->setCellValue($alphaCol . $startRowIndex, $row['path']);
            $sheet->setCellValue($alphaCol . ($startRowIndex + 1), $row['labelName']);
        }
        
        $getHighestRowNum = $sheet->getHighestRow();
        
        foreach (range(0, $headerCount) as $columnID) {
            $sheet->getColumnDimensionByColumn($columnID)->setAutoSize(true);
        }
        
        $sheet->getStyle('A1:' . numToAlpha($headerCount) . $getHighestRowNum)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'wrap' => true
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '74ad42')
                )
            )
        );
        
        try {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="Detail - ' . Date::currentDate('YmdHi') . '.xlsx"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            ob_end_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            
        } catch (Exception $e) {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $e->getMessage();
        }
        
        exit;
    }
    
    public function importDetailExcel() {
        Auth::handleLogin();
        
        $response = $this->model->importDetailExcelModel();
        
        if ($response['status'] == 'success') {
            
            $ws = Controller::loadController('Mdwebservice', 'middleware/controllers/');
            $this->load->model('mdwebservice', 'middleware/models/');
            
            $_POST['selectedRows'] = $response['rows'];
            $ws->renderDtlGroup();
            
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            jsonResponse($response);
        }
    }
    
    public function bpTempFileUpload() {
        Auth::handleLogin();
        
        $fileData = Input::fileData();

        $input = array(
            'ID'         => getUID(), 
            'uploadPath' => 'storage/uploads/processtemp/'
        );
        
        $tempdir = $input['uploadPath'];

        if (!is_dir($tempdir)) {

            mkdir($tempdir, 0777);

        } else {

            $files = glob($tempdir . '*');
            $now   = time();
            $day   = 0.3;

            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                    unlink($file);
                } 
            }
        }
                
        $result = array();
        
        foreach ($fileData as $k => $file) {
                                        
            $uploadResult = Mdwebservice::bpFileUpload($input, $file);
            
            if ($uploadResult) {
                $result[] = $uploadResult;
            }
        }
        
        jsonResponse($result);
    }
    
    public function callProcessFromAnotherServer() {
        Auth::handleLogin();
        
        $config = Config::getFromCache('callProcessFromAnotherServerUrl');
        
        if ($config) {
            
            $url_headers = @get_headers($config);
            
            if (!$url_headers || $url_headers[0] == 'HTTP/1.1 404 Not Found') {
                $response = json_encode(array('status' => 'error', 'message' => $config . ' уг хаяг хандалтгүй байна!'));
            } else {
                
                $metaDataId = Input::post('metaDataId');
                $config = rtrim($config, '/');
                
                $response = $this->ws->getJsonByCurl($config . '/mdcommon/renderProcess/'.$metaDataId);
                $response = str_replace('"isSystemProcess":"false"', '"isSystemProcess":"false","serverUrl":"'.$config.'"', $response);
            }
            
        } else {
            $response = json_encode(array('status' => 'error', 'message' => 'Тохиргоо хийгдээгүй байна! /callProcessFromAnotherServerUrl/'));
        }
        
        echo $response; exit;
    }
    
    public function bpFieldPropertyGrid() {
        Auth::handleLogin();
        
        $type = Input::post('type');
        $propertyList = $this->model->getBpFieldPropertyGridModel($type);
        
        jsonResponse(array('propertyList' => $propertyList));
    }
    
    public function renderByTestCase() {
        
        $testCaseId = Input::get('testCaseId');
        $autoSave = Input::get('autoSave');
        
        if (!$testCaseId || $autoSave == '') {
            
            set_status_header(404);
        
            $err = Controller::loadController('Err');
            $err->index();
            exit;
        }
        
        $this->view->row = $this->model->getTestCaseAndProcessRowModel($testCaseId);
        
        $this->view->isTestCase = true;
        
        if (!$this->view->row) {
            $this->view->isTestCase = false;
        }
        
        $this->view->title = 'Test case';
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }
        
        if ($this->view->isTestCase == true) {
            
            $requestData = $this->view->row['REQUEST_DE'];
            $requestData = @unserialize($requestData);
            
            if (isset($requestData['request']['parameters'])) {
                
                $bpId = $this->view->row['PROCESS_META_DATA_ID'];
                $parameters = $requestData['request']['parameters'];
            
                $_POST['isSystemMeta'] = 'false';
                $_POST['isDialog'] = false;
                $_POST['nult'] = true;
                $_POST['isEditMode'] = true;
                $_POST['fillDataParams'] = $parameters;

                $bpContent = (new Mdwebservice())->callMethodByMeta($bpId, null, true);
                
                $bpContent = str_replace('meta-toolbar', 'meta-toolbar d-none', $bpContent);
                $bpContent = str_replace('dv-right-tools-btn', 'dv-right-tools-btn d-none', $bpContent);
                $bpContent = str_replace('bp-btn-translate', 'bp-btn-translate d-none', $bpContent);
                
                $bpContent = str_replace('pfFullExpSetFieldValue = true;', 'pfFullExpSetFieldValue = false;', $bpContent);
                $bpContent = str_replace('/*bpScriptLoadEnd*/', 'pfFullExpSetFieldValue = true; /*bpScriptLoadEnd*/', $bpContent);
                
                if ($autoSave == '1') {
                    $bpContent = str_replace('/*bpScriptLoadEnd*/', 'runSaveTestCaseForm();', $bpContent);
                }
                
                $this->view->contentHtml = $bpContent;
                
                $scenarioId = Input::get('scenarioId');
                
                if ($scenarioId) {
                    $this->view->_runTest = json_encode(array('id' => $testCaseId, 'scenarioId' => $scenarioId));
                }
                
                if (!isset($this->view->_runTest) && isset($requestData['request']['_runtest']) && $requestData['request']['_runtest']) {
                    $this->view->_runTest = json_encode($requestData['request']['_runtest']);
                } 
                
            } else {
                $this->view->isTestCase = false;
            }
        }
        
        $this->view->render('header', 'middleware/views/frame/');
        $this->view->render('index', 'middleware/views/frame/');
        $this->view->render('footer', 'middleware/views/frame/');
    }
    
    public function runTestCaseProcess() {
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }
        
        $_POST['responseType'] = 'returnRequestParams';
        $param = (new Mdwebservice())->runProcess();
        
        if ($windowSessionId = Input::post('windowSessionId')) {
            WebService::$addonHeaderParam['windowSessionId'] = $windowSessionId;
        }
        
        if (isset($param['bpHeaderInfo'])) {
            
            $row = $param['bpHeaderInfo'];
            unset($param['bpHeaderInfo']);
            
            if (Input::postCheck('_runTest')) {
                WebService::$addonHeaderParam['_runTest'] = json_decode($_POST['_runTest'], true);
            }
            
            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param, 'serialize');
            
            if ($this->ws->isException()) {
                $responseArray = array('status' => 'error', 'message' => $this->ws->getErrorMessage());
            } else {
                if ($result['status'] == 'success') {
                    $responseArray = array('status' => 'success', 'message' => Lang::line('msg_save_success'), 'result' => issetParam($result['result']), 'uniqId' => getUID());
                } else {
                    $responseArray = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
            }
            
        } else {
            $responseArray = array('status' => 'error', 'message' => 'No parameters!');
        }
        
        jsonResponse($responseArray);
    }
    
    public function renderBpByTestCase() {
        
        $mode = strtolower(Input::post('mode'));
        
        if ($mode == 'testcase') {
            
            $testCaseId = Input::numeric('id');
        
            if ($testCaseId) {

                $row = $this->model->getTestCaseAndProcessRowModel($testCaseId);

                if ($row['status'] == 'success') {
                    
                    $row = $row['result'];
                    $requestData = $row['REQUEST_DE'];

                    if ($requestData) {

                        $_POST['metaDataId'] = $row['PROCESS_META_DATA_ID'];
                        $_POST['fillDataParams'] = $requestData;
                        $_POST['isSystemMeta'] = 'false';
                        $_POST['isDialog'] = 'true';
                        $_POST['callerType'] = 'drilldown';
                        
                        ob_start();
                            (new Mdwebservice())->callMethodByMeta(); 
                            $bpContent = ob_get_contents();
                        ob_end_clean();

                        $bpContent = str_replace('pfFullExpSetFieldValue = true;', 'pfFullExpSetFieldValue = false;', $bpContent);
                        $bpContent = str_replace('/*bpScriptLoadEnd*/', 'pfFullExpSetFieldValue = true; /*bpScriptLoadEnd*/', $bpContent);

                        echo $bpContent; exit;
                    }

                } else {
                    jsonResponse(array('status' => 'error', 'message' => 'TestCase олдсонгүй!'));
                }

            } else {
                jsonResponse(array('status' => 'error', 'message' => 'Invalid id!'));
            }
            
        } elseif ($mode == 'testcaselog') {
            
            $testCaseLogId = Input::numeric('id');
        
            if ($testCaseLogId) {

                $row = $this->model->getTestCaseLogRowModel($testCaseLogId);

                if ($row && $row['RESULT_TEXT']) {

                    $requestData = '{' . $row['RESULT_TEXT']. '}';
                    $requestData = preg_replace('/[\x00-\x1F]/', '', $requestData);
                    $requestData = json_decode($requestData, true);
                    $jsonErrorMsg = json_last_error_msg();
                    
                    if ($requestData) {
                        
                        $defaultStatus = issetParam($requestData['default']['status']);
                        $resultStatus = issetParam($requestData['result']['status']);
                        $responseStatus = issetParam($requestData['response']['status']);
                        $status = $defaultStatus ? $defaultStatus : ($resultStatus ? $resultStatus : $responseStatus);
                        
                        if ($status == 'error' || $status == 'info' || $status == 'warning') {
                            
                            $defaultMessage = issetParam($requestData['default']['text']);
                            
                            $message = $defaultMessage ? $defaultMessage : issetParam($requestData['result']['text']);
                            
                            jsonResponse(array('status' => $status, 'message' => $message));
                            
                        } elseif (isset($requestData['result']['result']) || isset($requestData['response']['result'])) {
                            
                            $_POST['metaDataId'] = $row['PROCESS_META_DATA_ID'];
                            $_POST['isSystemMeta'] = 'false';
                            $_POST['isDialog'] = 'true';
                            $_POST['callerType'] = 'drilldown';
                            
                            if (isset($requestData['response']['result'])) {
                                $_POST['fillDataParams'] = $requestData['response']['result'];
                            } else {
                                $_POST['fillDataParams'] = $requestData['result']['result'];
                            }
                            
                            ob_start();
                                (new Mdwebservice())->callMethodByMeta(); 
                                $bpContent = ob_get_contents();
                            ob_end_clean();
                            
                            $bpContent = str_replace('pfFullExpSetFieldValue = true;', 'pfFullExpSetFieldValue = false;', $bpContent);
                            $bpContent = str_replace('/*bpScriptLoadEnd*/', 'pfFullExpSetFieldValue = true; /*bpScriptLoadEnd*/', $bpContent);
                
                            echo $bpContent; exit;
                        
                        } else {
                            jsonResponse(array('status' => 'error', 'message' => 'TestCase RenderData олдсонгүй!'));
                        }
                        
                    } else {
                        jsonResponse(array('status' => 'error', 'message' => $jsonErrorMsg));
                    }

                } else {
                    jsonResponse(array('status' => 'error', 'message' => 'TestCase Log олдсонгүй!'));
                }

            } else {
                jsonResponse(array('status' => 'error', 'message' => 'Invalid id!'));
            }
            
        } else {
            jsonResponse(array('status' => 'error', 'message' => 'Invalid mode!'));
        }
    }
    
    public function kpiIndicatorRender() {
        
        /*$hashArr = array(
            'indicatorId' => 16842310757569, 
            'crudIndicatorId' => 180859038, 
            'actionType' => 'update',
            'expiredate' => Date::currentDate('Y-m-d H:i:s'), 
            'rowData' => array(
                'id' => '1685435125276210'
            )
        );

        $hashJson = json_encode($hashArr);
        $hash = Hash::encryption($hashJson);
        $hash = str_replace(array('+', '='), array('tttnmhttt', 'ttttntsuttt'), $hash);
        var_dump($hash);die;*/
        
        if (Input::getCheck('hash')) {
                
            $hash = $_GET['hash'];
            
            $jsonStr = str_replace(array('tttnmhttt', 'ttttntsuttt'), array('+', '='), $hash);
            $urlData = @json_decode(Hash::decryption($jsonStr), true);
            
            if ($urlData) {
                
                $urlData = Arr::changeKeyLower($urlData);
            
                if ((strtotime(Date::currentDate('Y-m-d H:i:s')) - strtotime($urlData['expiredate'])) > 30) {
                    
                    set_status_header(400);
                    echo 'Bad Request /expiredate/';
                    
                } else {
                    
                    $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
                    
                    if ($logged == false) {
                        Session::set(SESSION_PREFIX . 'LoggedIn', true);
                    }
                    
                    $_POST['nult'] = true;
                    
                    $this->view->actionType = Input::param($urlData['actiontype']);
                    
                    $_POST['isResponseArray'] = 1;
                    $_POST['param']['indicatorId'] = Input::param($urlData['indicatorid']);
                    $_POST['param']['actionType'] = $this->view->actionType;
                    $_POST['param']['crudIndicatorId'] = Input::param($urlData['crudindicatorid']);
                    
                    if (($this->view->actionType == 'update' || $this->view->actionType == 'read') && isset($urlData['rowdata']['id'])) {
                        $_POST['param']['dynamicRecordId'] = Input::param($urlData['rowdata']['id']); 
                    }

                    $indicatorContent = (new Mdform())->kpiIndicatorTemplateRender(); 

                    $this->view->contentHtml = $indicatorContent['html'];
                    
                    $this->view->css = AssetNew::metaCss();
                    $this->view->js = AssetNew::metaOtherJs();
                    $this->view->isSystemHeaderHide = true;

                    $this->view->render('header', 'middleware/views/frame/');
                    $this->view->render('kpiIndicator', 'middleware/views/frame/');
                    $this->view->render('footer', 'middleware/views/frame/');
                }
                
            } else {
                set_status_header(400);
                echo 'Bad Request /json decode/';
            }

        } else {
            set_status_header(400);
            echo 'Bad Request /get param/';
        }
    }
    
    public function saveKpiDynamicData() {
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
                    
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }

        $_POST['nult'] = true;
                    
        $response = (new Mdform())->saveKpiDynamicData();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function renderCommentId($commentId = '') {
        
        if ($commentId == '' || ($commentId != '' && !is_numeric($commentId))) {
            
            set_status_header(404);
        
            $err = Controller::loadController('Err');
            $err->index();
            exit;
        }
        
        $row = $this->model->getRenderProcessCommentRowModel($commentId);
        
        if (!$row) {
            set_status_header(404);
        
            $err = Controller::loadController('Err');
            $err->index();
            exit;
        }
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->isAjax = is_ajax_request();
        
        $this->view->metaDataId = $row['CREATED_COMMAND_ID'];
        $dmId = $row['LIST_META_DATA_ID'];
        $recordId = $row['RECORD_ID'];
        
        $_POST['metaDataId'] = $this->view->metaDataId;
        $_POST['isSystemMeta'] = 'false';
        $_POST['isDialog'] = false;
        $_POST['isEditMode'] = true;
        $_POST['dmMetaDataId'] = $dmId;
        $_POST['oneSelectedRow']['id'] = $recordId;
        
        ob_start();
            (new Mdwebservice())->callMethodByMeta(); 
            $bpJsonContent = ob_get_contents();
        ob_end_clean();
        
        $bpJsonContent = @json_decode($bpJsonContent, true);
        $bpContent = $bpJsonContent['Html'];
        $uniqId = $bpJsonContent['uniqId'];
        
        $bpContent = str_replace('meta-toolbar', 'meta-toolbar d-none', $bpContent);
        $bpContent = str_replace('dv-right-tools-btn', 'dv-right-tools-btn d-none', $bpContent);
        $bpContent = str_replace('bp-btn-translate', 'bp-btn-translate d-none', $bpContent);
        $bpContent = str_replace('pfFullExpSetFieldValue = true;', 'pfFullExpSetFieldValue = true; var pfFocusComment_'.$uniqId.' = '.$commentId.';', $bpContent);
        
        $this->view->title = $bpJsonContent['Title'].' - Notification';
        $this->view->contentHtml = $bpContent;
        
        $this->view->render('header');
        $this->view->render('processRender', 'middleware/views/notification/');
        $this->view->render('footer');
    }
    
    public function saveRenderViewLog() {
        $response = $this->model->saveRenderViewLogModel();
        echo json_encode($response);
    }
    
}