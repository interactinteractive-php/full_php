<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdupgrade extends Controller {

    private static $viewPath = 'middleware/views/upgrade/';

    public function __construct() {
        parent::__construct();
    }
    
    public function bugfix() {
        Auth::handleLogin();
        Uri::isUrlAuth();
        
        $this->view->title = 'Bug Fixing';   
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            
            $this->view->render('header');
            $this->view->render('meta/fix', self::$viewPath); 
            $this->view->render('footer');     
            
        } else {
            $this->view->render('meta/fix', self::$viewPath); 
        }
    }
    
    public function bugfixDatagrid() {
        Auth::handleLogin();
        
        $result = $this->model->bugfixDatagridModel();
        convJson($result);
    }
    
    public function updatingBugFixing() {
        Auth::handleLogin();
        
        $response = $this->model->updatingBugFixingModel(); 
        convJson($response);
    }
    
    public function downloadBugFixing() {
        Auth::handleLogin();
        
        $ids = rtrim(Input::post('bugfixIds'), ',');
        
        $exportData = Mdupgrade::getBugfixDataByCommand('download', ['ids' => $ids]);

        if ($exportData['status'] == 'success') {
                
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="bugfix_'.date('Y-m-d H-i-s').'.txt"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo $exportData['result'];
            
        } else {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $exportData['message']; 
        }
        
        exit;
    }
    
    public function clientDownloadBugFixing() {
        Auth::handleLogin();
        
        $ids = rtrim(Input::post('bugfixIds'), ',');
        
        $exportData = $this->model->downloadBugFixingModel($ids);

        if ($exportData['status'] == 'success') {
                
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="bugfix_'.date('Y-m-d H-i-s').'.txt"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo $exportData['result'];
            
        } else {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $exportData['message']; 
        }
        
        exit;
    }
    
    public function exportMeta() {
        Auth::handleLogin();
        
        if (Config::getFromCache('is_dev') && Ue::sessionUserId() != '1453998999913') {
            $exportData = array('status' => 'error', 'message' => 'Метаг экспорт хийх боломжгүй тул Patch ашиглана уу.');
        } else {
            $exportData = $this->model->exportMetaModel();
        }
        
        if ($exportData['status'] == 'success') {
            
            if (isset($exportData['metaId'])) {
                $fileName = 'meta_'.$exportData['metaId'].'_'.date('YmdHis').'.txt';
            } else {
                $fileName = 'meta_'.date('YmdHis').'.txt';
            }
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo $exportData['result'];
            
        } else {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $exportData['message']; 
        }
        exit;
    }
    
    public function importMeta() {
        Auth::handleLogin();
        
        $response = [
            'html' => $this->view->renderPrint('meta/import', self::$viewPath),
            'title' => 'Шинэчлэлийн файл уншуулах', 
            'import_btn' => 'Уншуулах', 
            'close_btn' => $this->lang->line('close_btn')
        ];
        convJson($response);
    }
    
    public function importMetaFile() {
        Auth::handleLogin();
        
        $result = $this->model->importMetaFileModel();
        convJson($result);
    }
    
    public function importAnotherServerForm() {
        Auth::handleLogin();
        
        $id = Input::post('metaId');
        
        $this->view->metaRow    = $this->model->getMetaUpgradeInfoModel($id);
        $this->view->personName = '';
        
        $lockUrl = Config::getFromCache('LOCK_SERVER_URL');
        $isImportBtn = true;
        
        if ($lockUrl) {

            $curl_handle = curl_init();

            curl_setopt($curl_handle, CURLOPT_URL, $lockUrl . 'checkLockByMetaId/' . $id);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl_handle, CURLOPT_HEADER, false);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
                'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36', 
                'Content-Type: application/json'                                                                                                                                                    
            ));    

            $buffer = curl_exec($curl_handle);
            $err = curl_error($curl_handle);
            $errNo = curl_errno($curl_handle);
            
            curl_close($curl_handle);
            
            if (!$err) {
                
                $json = json_decode($buffer, true);
            
                if (isset($json['isLocked']) && $json['isLocked']) {

                    $this->view->personName = $json['personName'];
                    $this->view->message    = 'Түгжсэн байгаа тул боломжгүй байна. Та түгжсэн хүнээс зөвшөөрөл авна уу.';

                    $isImportBtn = false;

                } elseif (isset($json['isLocked']) && $json['isLocked'] == false) {

                    $this->view->personName = '';

                } else {
                    
                    $this->view->message = 'Lock сервертэй холбогдож чадсангүй!<br />';
                    $this->view->message .= $err;

                    $isImportBtn = false;
                }
            
            } else {
                
                $this->view->message = 'Lock сервертэй холбогдож чадсангүй!<br />';
                $this->view->message .= $errNo.' - '.$err;

                $isImportBtn = false;
            }
            
        } else {
            $this->view->message = 'Lock серверийн тохиргоо хийгдээгүй байна!';
            $isImportBtn = false;
        }
        
        $response = [
            'html'          => $this->view->renderPrint('meta/importAnotherServer', self::$viewPath),
            'title'         => 'Upgrade',
            'is_import_btn' => $isImportBtn,
            'import_btn'    => 'Үндсэн систем рүү шинэчлэх', 
            'close_btn'     => $this->lang->line('close_btn')
        ];
        convJson($response);
    }
    
    public function importAnotherServer() {
        Auth::handleLogin();
        
        $result = $this->model->importAnotherServerModel();
        convJson($result);
    }
    
    public function encryptedFileImport() {
        $result = $this->model->encryptedFileImportModel();
        convJson($result);
    }
    
    public function exportObject() {
        Auth::handleLogin();
        
        $exportData = $this->model->exportObjectModel();
        
        if ($exportData['status'] == 'success') {
            
            if (isset($exportData['objectId'])) {
                $fileName = $exportData['typeId'].'_'.$exportData['objectId'].'_'.date('YmdHis').'.txt';
            } else {
                $fileName = $exportData['typeId'].'_'.date('YmdHis').'.txt';
            }
                
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo $exportData['result'];
            
        } else {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $exportData['message']; 
        }
        
        exit;
    }
    
    public function exportKpiIndicator() {
        
        Auth::handleLogin();
        
        if (Input::numeric('isConfirmedData') == 1) {
            
            self::exportObject();
            
        } else {
            $checkData = $this->model->isTableDataKpiIndicatorModel();
            
            if ($checkData) {
            
                if ($checkData['status'] == 'success') {

                    header('Pragma: no-cache');
                    header('Expires: 0');
                    header('Set-Cookie: fileDownload=false; path=/');

                    echo 'confirmDataExport|'.$checkData['count']; 
                } else {
                    header('Pragma: no-cache');
                    header('Expires: 0');
                    header('Set-Cookie: fileDownload=false; path=/');

                    echo $checkData['message']; 
                }
                
            } else {
                self::exportObject();
            }
        }
        
        exit;
    }
    
    public function sysUpdatePopup() {
        
        Auth::handleLogin();
        
        $response = ['html' => $this->view->renderPrint('system/sysUpdatePopup', self::$viewPath)];
        convJson($response);
    }
    
    public function sysUpdateAccessByPass() {
        
        Auth::handleLogin();
        
        $result = $this->model->sysUpdateAccessByPassModel();
        
        if ($result) {
            $response = [
                'status' => 'success', 
                'html' => $this->view->renderPrint('system/confirmDb', self::$viewPath)
            ];
        } else {
            $response = ['status' => 'error', 'message' => 'Нууц үг буруу байна!'];
        }
        
        convJson($response);
    }
    
    public function sysUpdate() {
        Auth::handleLogin();
        $response = $this->model->sysUpdateModel();
        convJson($response);
    }
    
    public static function phpImportServiceAddr() {
        
        $url = Config::getFromCache('bugfixServiceAddress');
                
        if (!$url) {
            $url = 'http://bugfixservice.veritech.mn/mdupgrade/bugfixservice';
        }        
        
        return $url;
    }
    
    public function bugfixservice() {
        
        $jsonBody = file_get_contents('php://input');
        $param    = json_decode($jsonBody, true);
        
        $commandName = Input::param(issetParam($param['commandName']));
        $response    = ['status' => 'error', 'message' => 'Undefined commandName!'];
        
        if ($commandName) {
            
            $commandName = strtolower($commandName);
            
            if ($commandName == 'list') {
                
                $param = Input::param($param['param']);
                $param['ignorePermission'] = 1;
                
                $response = $this->ws->runSerializeDefaultSession(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
                
            } elseif ($commandName == 'download') {
                
                $param = Input::param($param['param']);
                
                $response = $this->model->downloadBugFixingModel($param['ids']);
                
            } elseif ($commandName == 'downloadobject') {
                
                $param = Input::param($param['param']);
                
                $response = $this->model->downloadObjectModel($param['objectCode'], $param['ids']);
                
            } elseif ($commandName == 'getscript') {
                
                $param = Input::param($param['param']);
                
                $response = $this->model->getBugFixingScriptModel($param['id']);
                
            } elseif ($commandName == 'getknowledge') {
                
                $param = Input::param($param['param']);
                
                $response = $this->model->getBugFixingKnowledgeModel($param['id']);
                
            } elseif ($commandName == 'getpatchlist') {
                
                $response = $this->model->getPatchListModel($param['id']);
            }
        }
        
        convJson($response);
    }
    
    public static function getBugfixDataByCommand($command, $param = []) {
        
        $url = self::phpImportServiceAddr();

        $data = (new WebService())->curlRequest($url, ['commandName' => $command, 'param' => $param]);
        
        return $data;
    }
    
    public function metaConfigReplace() {
        Auth::handleLogin();
        
        $response = $this->model->metaConfigReplaceModel();
        convJson($response);
    }
    
    public function knowMetasInFile() {
        Auth::handleLogin();
        
        $response = $this->model->knowMetasInFileModel();
        convJson($response);
    }
    
    public function metaSendToRunLoop() {
        
        Auth::handleLogin();
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir = $cacheTmpDir.'/getData';
        $fileId = Input::numeric('fileId');
        
        if (!is_dir($cacheDir)) {

            mkdir($cacheDir, 0777);

        } else {

            $files = glob($cacheDir.'/*');
            $now   = time();
            $day   = 0.5;

            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                    @unlink($file);
                } 
            }
        }

        $filePath = $cacheDir.'/'.$fileId.'.txt';
        
        $fileResult = @file_get_contents($filePath);
        
        if (!$fileResult) {
            $exportData = $this->model->exportMetaModel();
            @file_put_contents($filePath, json_encode($exportData, JSON_UNESCAPED_UNICODE));
        } else {
            $exportData = json_decode($fileResult, true);
        }
        
        if ($exportData['status'] == 'success') {
            
            $url = Input::post('domain');
            
            try {

                $params = ['encryptedSource' => $exportData['result']]; 

                $curl_handle = curl_init();

                curl_setopt($curl_handle, CURLOPT_URL, $url . 'mdupgrade/encryptedFileImport');
                curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30000);
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_handle, CURLOPT_POST, true);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($params));
                curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);        
                curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);        
                curl_setopt($curl_handle, CURLOPT_HEADER, false);
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
                    'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36',
                    'Cache-Control: no-cache', 
                    'Content-Type: application/json'
                ]);

                $buffer = curl_exec($curl_handle);
                curl_close($curl_handle);

                $response = json_decode(remove_utf8_bom($buffer), true);

            } catch (Exception $ex) {
                $response = ['status' => 'error', 'message' => $ex->getMessage()];
            }
            
        } else {
            $response = ['status' => 'error', 'message' => $exportData['message']];
        }
        
        convJson($response);
    }
    
    public function sendToMetaByIds() {
        
        $domain = Config::getFromCache('metaSendToDomain');
        
        if ($domain) {
            
            $domain = rtrim($domain, '/');
            $_POST['fileId'] = getUID();
            $_POST['domain'] = $domain . '/';
        
            self::metaSendToRunLoop();
            
        } else {
            convJson(['status' => 'error', 'message' => 'No domain config!']);
        }
    }
    
    public static function metaImportExternalServerAddress() {
        $url = Config::getFromCache('metaImportExternalServerAddress');
        
        if ($url) {
            $url = rtrim($url, '/');
        }
        
        return $url;
    }
    
    public function getMetaFolderList() {
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }

        $_POST['nult'] = true;
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $jsonBody = file_get_contents('php://input');
        $jsonBody = json_decode($jsonBody, true);
        
        $_REQUEST = $jsonBody;
        
        $response = $this->model->childFolderSystemModel();
        convJson($response);
    }
    
    public function commonMetaDataGrid() {
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }

        $_POST['nult'] = true;
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $jsonBody = file_get_contents('php://input');
        $jsonBody = json_decode($jsonBody, true);
        
        $_POST = $jsonBody;
        
        $response = $this->model->commonMetaDataGridModel();
        convJson($response);
    }
    
    public function metaExportExternalServer() {
        
        $jsonBody = file_get_contents('php://input');
        $jsonBody = json_decode($jsonBody, true);
        
        $_POST = $jsonBody;
        
        $response = $this->model->exportMetaModel();
        convJson($response);
    }
    
    public function metaImportExternalServer() {
        
        $url = self::metaImportExternalServerAddress();        
        $data = $this->ws->curlRequest($url . '/mdupgrade/metaExportExternalServer', $_POST);
        
        if (issetParam($data['status']) == 'success') {
            $response = $this->model->encryptedFileImportModel(array('encryptedSource' => $data['result']));
        } else {
            $response = array('status' => 'error', 'message' => 'Unkhown error!');
        }
        
        convJson($response);
    }
    
    public function metaPatchRollback() {
        $response = $this->model->metaPatchRollbackModel();
        convJson($response);
    }
    
    public function metaPatchImport() {
        $response = $this->model->metaPatchImportModel();
        convJson($response);
    }
    
    public function metaImportCopy() {
        Auth::handleLogin();
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $folderId = Input::numeric('folderId');
        $this->view->folderRow = $this->model->getFolderRowModel($folderId);
        
        $this->view->render('meta/importCopy', self::$viewPath);
    }
    
    public function metaImportCopyFile() {
        Auth::handleLogin();
        
        $response = $this->model->metaImportCopyFileModel();
        convJson($response);
    }
    
    public function getBugFixingScript() {
        Auth::handleLogin();
        
        $id = Input::numeric('bugfixId');
        $response = Mdupgrade::getBugfixDataByCommand('getScript', ['id' => $id]);
        
        convJson($response);
    }
    
    public function getBugFixingKnowledge() {
        Auth::handleLogin();
        
        $id = Input::numeric('bugfixId');
        $response = Mdupgrade::getBugfixDataByCommand('getKnowledge', ['id' => $id]);
        
        convJson($response);
    }
    
    public function metaCopyReplaceForm() {
        Auth::handleLogin();
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaId = Input::numeric('metaId');
        $folderId = Input::numeric('folderId');
        
        $this->view->metaRow = $this->model->getMetaDataModel($metaId);
        $this->view->folderRow = $this->model->getFolderRowModel($folderId);
        
        $this->view->newMetaId = getUID();
        
        $this->view->render('meta/copyReplace', self::$viewPath);
    }
    
    public function metaCopyReplace() {
        Auth::handleLogin();
        
        $response = $this->model->metaCopyReplaceModel();
        convJson($response);
    }
    
    public function metaReplaceForm() {
        Auth::handleLogin();
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaId = Input::numeric('metaId');
        $this->view->metaRow = $this->model->getMetaDataModel($metaId);
        
        $this->view->render('meta/replace', self::$viewPath);
    }
    
    public function metaReplace() {
        Auth::handleLogin();
        
        $response = $this->model->metaReplaceModel();
        convJson($response);
    }
    
    public function decryptFile() {
        includeLib('Compress/Compression');
        $fileContent = Compression::gzinflate(file_get_contents('kpiindicator_17122915297839_20240417200640.txt'));
        
        file_put_contents('kpi-decryptFile.txt', $fileContent);die;
    }
    
    public function encryptFile() {
        includeLib('Compress/Compression');
        $fileContent = Compression::gzdeflate(file_get_contents('platfrom_db_scripts.txt'));
        
        file_put_contents('DB_COMPARE_PLATFORM.txt', $fileContent);die;
    }
    
    public static function getCloudInstallUrl() {
        return 'http://192.168.193.200:81/mdupgrade/bugfixservice';
    }
    
    public function getCloudPatchList() {
        Auth::handleLogin();
        
        $url = self::getCloudInstallUrl();
        $response = (new WebService())->curlRequest($url, ['commandName' => 'getPatchList']);
        convJson($response);
    }
    
    public function installCloudPatchDownload() {
        Auth::handleLogin();
        
        $response = $this->model->installCloudPatchDownloadModel();
        convJson($response);
    }
    
    public function installCloudPatchImport() {
        Auth::handleLogin();
        
        $response = $this->model->installCloudPatchImportModel();
        convJson($response);
    }
    
    public function externalCloudPatchImport() {
        $response = $this->model->externalCloudPatchImportModel();
        convJson($response);
    }
    
}
