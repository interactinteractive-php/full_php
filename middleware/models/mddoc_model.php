<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mddoc_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $monpassClientAddress = 'http://client.monpass.mn/serviceClient/MonPassClient.svc?wsdl';

    public function __construct() {
        parent::__construct();
    }        

    public function getContentDirectoryModel() {
        $data = $this->db->GetAll("SELECT ID, CODE, NAME FROM ECM_DIRECTORY ORDER BY NAME ASC");
        return $data;
    }

    public function getContentDirectory2Model() {
        $data = $this->db->GetAll("SELECT ID, CODE, NAME FROM ECM_DIRECTORY WHERE TYPE_ID = 1 ORDER BY NAME ASC");
        return $data;
    }

    public function toArchiveSaveModel($contentName, $directoryId, $fileToSave, $fileExtension = 'pdf', $qrCode = null) {

        $contentId = getUID();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate('Y-m-d H:i:s');
        $contentName = $contentName ? $contentName : 'File '.$contentId.'.'.$fileExtension;
        
        $data = array(
            'CONTENT_ID'      => $contentId, 
            'FILE_NAME'       => $contentName, 
            'PHYSICAL_PATH'   => $fileToSave, 
            'FILE_SIZE'       => filesize($fileToSave), 
            'FILE_EXTENSION'  => $fileExtension, 
            'CREATED_DATE'    => $currentDate, 
            'CREATED_USER_ID' => $sessionUserKeyId, 
            'WFM_STATUS_ID'   => 1463726941931829, 
            'TYPE_ID'         => 3
        );

        if (Input::post('wfmStatusId') == 'isnull') {
            unset($data['WFM_STATUS_ID']);
        }

        if (Input::post('typeId') == 'isnull') {
            unset($data['TYPE_ID']);
        }
        
        if (Input::isEmpty('htmlContent') == false) {
            
            $htmlFilePath = str_replace('.pdf', '.html', $fileToSave);
            
            $htmlContent = Input::postNonTags('htmlContent');
            $htmlContent = str_replace('<span style="display: inline-block; width: 30px;"></span>', '&emsp;&emsp;', $htmlContent);
            
            file_put_contents($htmlFilePath, $htmlContent);
            
            $data['THUMB_PHYSICAL_PATH'] = $htmlFilePath;
            
            if (Input::postCheck('orientation')) {
        
                $pageSettings = array(
                    'orientation'  => Input::post('orientation'), 
                    'size'         => Input::post('size'), 
                    'marginTop'    => Input::post('top'), 
                    'marginLeft'   => Input::post('left'), 
                    'marginBottom' => Input::post('bottom'), 
                    'marginRight'  => Input::post('right')
                );
                
                $filePath = Mdwebservice::bpUploadGetPath();
                
                if (Input::isEmpty('headerHtml') === false) {
                    
                    $headerHtml = html_entity_decode(Input::post('headerHtml'), ENT_QUOTES, 'UTF-8');
                    $headerHtmlPath = $filePath . getUIDAdd(1) . '.html';
                    file_put_contents($headerHtmlPath, $headerHtml);
                    
                    $pageSettings['headerHtmlPath'] = $headerHtmlPath;
                }
                
                if (Input::isEmpty('footerHtml') === false) {
                    
                    $footerHtml = html_entity_decode(Input::post('footerHtml'), ENT_QUOTES, 'UTF-8');
                    $footerHtmlPath = $filePath . getUIDAdd(2) . '.html';
                    file_put_contents($footerHtmlPath, $footerHtml);
                    
                    $pageSettings['footerHtmlPath'] = $footerHtmlPath;
                }
                
                $data['PAGE_SETTINGS'] = json_encode($pageSettings, JSON_UNESCAPED_UNICODE);
            }
        }
        
        if ($qrCode) {
            $data['QR_CODE'] = $qrCode;
        }

        $result = $this->db->AutoExecute('ECM_CONTENT', $data);

        if ($result) {

            if ($directoryId != '') {

                $dirMap = array(
                    'ID'           => getUID(), 
                    'CONTENT_ID'   => $contentId, 
                    'DIRECTORY_ID' => $directoryId 
                );
                $this->db->AutoExecute('ECM_CONTENT_DIRECTORY', $dirMap);
            }

            if (Input::isEmpty('recordId') == false && (Input::isEmpty('dataViewId') == false || Input::isEmpty('refStructureId') == false)) {
                
                if (Input::isEmpty('dataViewId') == false) {
                    $this->load->model('mdobject', 'middleware/models/');

                    $dataViewId = Input::numeric('dataViewId');
                    $groupRow = $this->model->getDataViewConfigRowModel($dataViewId);

                    $refStructureId = isset($groupRow['REF_STRUCTURE_ID']) ? $groupRow['REF_STRUCTURE_ID'] : $dataViewId;
                    
                } else {
                    $refStructureId = Input::numeric('refStructureId');
                }

                $map = array(
                    'ID'               => getUID(), 
                    'CONTENT_ID'       => $contentId, 
                    'REF_STRUCTURE_ID' => $refStructureId, 
                    'RECORD_ID'        => Input::post('recordId'), 
                    'MAIN_RECORD_ID'   => Input::numeric('reportMetaDataId'), 
                    'CREATED_DATE'     => $currentDate, 
                    'CREATED_USER_ID'  => $sessionUserKeyId
                );
                $this->db->AutoExecute('ECM_CONTENT_MAP', $map);
                
                if (Input::isEmpty('setWfmStatusParams') == false && Input::numeric('ignoreSetWfmStatusParams') != 1) {
                    
                    $this->load->model('mdobject', 'middleware/models/');
                    
                    $dataRow = $_POST['dataRow'];
                    
                    $_POST['newWfmStatusid'] = $dataRow['wfmstatusid'];
                    $_POST['metaDataId'] = $dataRow['metaDataId'];
                    $_POST['dataRow'] = Input::post('selectedRow');

                    $this->model->setRowWfmStatusModel();
                }
            }

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        }

        return array('status' => 'error', 'message' => 'Error');
    }

    public function getSignInfoViewModel($details, $refStructureId, $selectedRow) {

        $array = $response = array();

        foreach ($details as $k => $v) {

            $array[$k]['date'] = Date::formatter(Input::param($v['key']), 'Y-m-d H:i:s');
            $array[$k]['CREATED_DATE'] = $array[$k]['date'];
            $array[$k]['serialNumber'] = Input::param($v['value']);

            $VerifyCrlParam = array('certificateSerialNumber' => $array[$k]['serialNumber']);

            $VerifyCrlResult = $this->ws->caller('wsdl', self::$monpassClientAddress, 'VerifyCrl', 'VerifyCrlResult', $VerifyCrlParam);

            if ($VerifyCrlResult) {

                $array[$k]['IsRevoked'] = $VerifyCrlResult[0]['IsRevoked'];
                $array[$k]['RevocationDate'] = $VerifyCrlResult[0]['RevocationDate'];
                $array[$k]['isValidSign'] = true;

                if ($VerifyCrlResult[0]['IsRevoked'] == 'True') {

                    $signDate = new DateTime($array[$k]['date']);
                    $RevocationDate = new DateTime($array[$k]['RevocationDate']);

                    if ($signDate < $RevocationDate) {
                        $array[$k]['isValidSign'] = true;
                    } else {
                        $array[$k]['isValidSign'] = false;
                    }
                }

            }

            $GetCertificateParam = array(
                'appkey' => '', 
                'certificateSerialNumber' => $array[$k]['serialNumber']
            );

            $GetCertificateResult = $this->ws->caller('wsdl', self::$monpassClientAddress, 'GetCertificateByCertificateSerialNumber', 'GetCertificateByCertificateSerialNumberResponse', $GetCertificateParam);

            if ($GetCertificateResult) {

                $GetCertificateResult = $GetCertificateResult['GetCertificateByCertificateSerialNumberResult'];
                $CertificateSerialNumber = $GetCertificateResult['CertificateSerialNumber'];

                $ownUser = (new Mduser())->getUserRowByCrtSerialNumber($CertificateSerialNumber);

                if ($ownUser) {
                    $array[$k]['LAST_NAME'] = $ownUser['LAST_NAME'];
                    $array[$k]['FIRST_NAME'] = $ownUser['FIRST_NAME'];
                    $array[$k]['positionName'] = $ownUser['POSITION_NAME'];
                    $array[$k]['PICTURE'] = $ownUser['PICTURE'];
                    $array[$k]['systemUserId'] = $ownUser['USER_ID'];
                } else {
                    $array[$k]['fullName'] = $GetCertificateResult['CN'];
                    $array[$k]['positionName'] = $GetCertificateResult['T'];
                }

                $array[$k]['email'] = $GetCertificateResult['E'];
                $array[$k]['organizationName'] = $GetCertificateResult['O'];
                $array[$k]['departmentName'] = $GetCertificateResult['OU'];
                $array[$k]['status'] = $GetCertificateResult['Status'];

            }
        }

        $this->load->model('mdobject', 'middleware/models/');

        $wfmStatusId = isset($selectedRow['wfmstatusid']) ? $selectedRow['wfmstatusid'] : null;
        $recordId = isset($selectedRow['id']) ? $selectedRow['id'] : null;

        $assignmentUsers = $this->model->getWfmStatusAssignmentModel($wfmStatusId, $refStructureId, $recordId);

        if ($assignmentUsers) {

            if ($array) {

                $checkArr = array();

                foreach ($assignmentUsers as $k => $asgnUser) {
                    foreach ($array as $ak => $signUser) {
                        if (isset($signUser['systemUserId'])) {
                            if ($signUser['systemUserId'] == $asgnUser['SYSTEM_USER_ID'] && !isset($checkArr[$asgnUser['SYSTEM_USER_ID']])) {
                                $checkArr[$asgnUser['SYSTEM_USER_ID']] = 1;

                                $assignmentUsers[$k] = array_merge($asgnUser, $signUser);
                                unset($array[$ak]);
                            }
                        }
                    }
                }

                if (count($array) > 0) {
                    $asgnUserC = count($assignmentUsers);

                    foreach ($array as $signedUser) {
                        ++$asgnUserC;
                        $assignmentUsers[$asgnUserC] = $signedUser;
                    }

                } 

                $response = $assignmentUsers;

            } else {
                $response = $assignmentUsers;
            }

        } else {
            $response = $array;
        }

        return $response;
    }

    public function isCheckOcrProcessModel() {

        $result = $this->ws->caller('wsdl', Config::getFromCache('CONFIG_OCR_SERVICE'), 'OCRCheck', 'OCRCheckResult');

        if ($result) {

            if (isset($result[0]['OCRCheckResult']) && $result[0]['OCRCheckResult'] == false) {
                return array('status' => 'success');
            } 

        } else {
            if ($this->ws->isException()) {
                return array('status' => 'error', 'message' => $this->ws->getErrorMessage());
            }
        }

        return array('status' => 'error', 'message' => 'Та түр хүлээнэ үү');
    }

    public function ocrProcessModel($selectedRow) {

        if (isset($selectedRow['physicalpath']) && file_exists($selectedRow['physicalpath'])) {

            $fileName = 'file_'.getUID().'.docx';

            $param = array(
                'file' => URL.$selectedRow['physicalpath'], 
                'server' => URL.'mddoceditor/fileUpload', 
                'filename' => $fileName
            );

            $result = $this->ws->caller('wsdl', Config::getFromCache('CONFIG_OCR_SERVICE'), 'OCRProcessing', 'OCRProcessingResult', $param);

            if ($result) {

                if (isset($result[0]['OCRProcessingResult']) && ($result[0]['OCRProcessingResult'] == 'success' || $result[0]['OCRProcessingResult'] == true)) {

                    $contentName = isset($selectedRow['filename']) ? str_replace(array('.pdf', '.png', '.gif', '.jpg', '.jpeg', '.jpeg'), '', $selectedRow['filename']) : $fileName;
                    $directoryId = isset($selectedRow['directoryid']) ? $selectedRow['directoryid'] : '';
                    $fileToSave = UPLOADPATH.'process/'.$fileName;

                    self::toArchiveSaveModel($contentName, $directoryId, $fileToSave, 'docx');

                    return array('status' => 'success', 'message' => '('.$contentName.') нэртэй файл амжилттай үүслээ');

                } else {
                    return array('status' => 'error', 'message' => $result[0]['OCRProcessingResult']);
                }

            } else {
                if ($this->ws->isException()) {
                    return array('status' => 'error', 'message' => $this->ws->getErrorMessage());
                }
            }

        } else {
            return array('status' => 'error', 'message' => 'Error');
        }
    }

    public function getBPFolderModel() {
        $data = $this->db->GetAll("
            SELECT 
                FOLDER_ID, 
                FOLDER_CODE, 
                FOLDER_NAME
            FROM META_BP_TEMPLATE_FOLDER  
            ORDER BY FOLDER_CODE ASC");

        return $data;
    }

    public function getTaxonomyListModel() {
        $data = $this->db->GetAll("
            SELECT 
                NT.ID, 
                NT.TAG
            FROM NTR_TAXONOMY NT
            INNER JOIN NTR_TAXONOMY_TYPE NTT ON NTT.ID = NT.TAXONOMY_TYPE_ID
            WHERE NTT.TAXONOMY_TYPE_CODE = 'TAG'
            ORDER BY NT.TAG ASC");

        return $data;
    }

    public function getBPInputParamsModel($processMetaDataId) {

        $array = array();

        if ($processMetaDataId) {
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_REAL_PATH AS META_DATA_CODE, 
                    LABEL_NAME AS META_DATA_NAME, 
                    RECORD_TYPE, 
                    'param' AS KEY_TYPE, 
                    ID 
                FROM META_PROCESS_PARAM_ATTR_LINK    
                WHERE PROCESS_META_DATA_ID = $processMetaDataId 
                    AND PARENT_ID IS NULL 
                    AND IS_SHOW = 1 
                    AND IS_INPUT = 1 
                ORDER BY ORDER_NUMBER ASC"); 

            if ($data) {
                foreach ($data as $k => $row) {
                    if ($row['RECORD_TYPE'] == '') {
                        $array[$k]['META_DATA_CODE'] = $row['META_DATA_CODE'];
                        $array[$k]['META_DATA_NAME'] = $row['META_DATA_NAME'];
                        $array[$k]['KEY_TYPE'] = 'param';
                        $array[$k]['RECORD_TYPE'] = '';
                    } elseif ($row['RECORD_TYPE'] == 'row') {
                        $array[$k]['META_DATA_CODE'] = $row['META_DATA_CODE'];
                        $array[$k]['META_DATA_NAME'] = $row['META_DATA_NAME'];
                        $array[$k]['KEY_TYPE'] = 'param';
                        $array[$k]['RECORD_TYPE'] = 'row';
                        $array[$k]['CHILD_PARAMS'] = self::getBPInputChildParamsModel($processMetaDataId, $row['ID']);
                    } elseif ($row['RECORD_TYPE'] == 'rows') {
                        $array[$k]['META_DATA_CODE'] = $row['META_DATA_CODE'];
                        $array[$k]['META_DATA_NAME'] = $row['META_DATA_NAME'];
                        $array[$k]['KEY_TYPE'] = 'param';
                        $array[$k]['RECORD_TYPE'] = 'rows';
                    }
                }
            }
        }

        return $array;
    }

    public function getBPInputChildParamsModel($processMetaDataId, $parentId) {
        $data = $this->db->GetAll("
            SELECT 
                PARAM_REAL_PATH AS META_DATA_CODE, 
                LABEL_NAME AS META_DATA_NAME,  
                RECORD_TYPE, 
                'param' AS KEY_TYPE 
            FROM META_PROCESS_PARAM_ATTR_LINK    
            WHERE PROCESS_META_DATA_ID = $processMetaDataId 
                AND PARENT_ID = $parentId  
                AND IS_SHOW = 1     
                AND IS_INPUT = 1 
            ORDER BY ORDER_NUMBER ASC"); 

        return $data;
    }

    public function addBpTemplateSaveModel() {

        $templateId = getUID();
        $processMetaDataId = Input::post('processId');

        $data = array(
            'ID' => $templateId, 
            'META_DATA_ID' => $processMetaDataId, 
            'TEMPLATE_CODE' => Input::post('templateCode'),
            'CONTROL_DESIGN' => Input::post('controlDesign'),
            'TEMPLATE_NAME' => Input::post('templateName'),
            'IS_DEFAULT' => Input::postCheck('isDefault') ? 1 : 0, 
            'IS_ACTIVE' => Input::postCheck('isActive') ? 1 : 0, 
            'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
            'MODIFIED_USER_ID' => Ue::sessionUserKeyId(), 
            'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s') 
        );

        $path = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
        $htmlFilePath = $path . $templateId.'.html';
        
        if (file_put_contents($htmlFilePath, Input::postNonTags('tempEditor'))) {
            $data['HTML_FILE_PATH'] = $htmlFilePath;
        }

        $result = $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data);

        if ($result) {

            if (Input::isEmpty('folderId') == false) {

                $dataMap = array(
                    'ID' => getUID(), 
                    'FOLDER_ID' => Input::post('folderId'), 
                    'TEMPLATE_ID' => $templateId 
                );
                $result = $this->db->AutoExecute('META_BP_TEMPLATE_FOLDER_MAP', $dataMap);
            }

            if (Input::postCheck('templateWidget')) {

                $templateWidget = $_POST['templateWidget'];

                foreach ($templateWidget as $widget) {
                    $dataWidget = array(
                        'ID' => getUID(), 
                        'TEMPLATE_ID' => $templateId, 
                        'WIDGET_CODE' => $widget, 
                        'IS_ACTIVE' => 1 
                    );
                    $result = $this->db->AutoExecute('META_BP_TEMPLATE_WIDGET', $dataWidget);
                }
            }

            (new Mdmeta())->setMetaModifiedDate($processMetaDataId);

            $tmp_dir = Mdcommon::getCacheDirectory();

            $bpConfigFile = glob($tmp_dir."/*/bp/bpConfig_".$processMetaDataId.".txt");
            foreach ($bpConfigFile as $configFile) {
                @unlink($configFile);
            }

            return array('status' => 'success', 'message' => 'Success');
        }

        return array('status' => 'error', 'message' => 'Error');
    }

    public function configTaxonamyTemplateSaveModel() {
        $tagsData = Input::post('bpTemplateGroups');
        
        if($tagsData) {
            foreach ($tagsData as $ke => $va) {
                $wid = getUID();
                $metaAs = $va;
                $getMetaGroup = array();

                preg_match_all('/^(.*)@/', $va, $getMetaGroup);
                if(!empty($getMetaGroup[0])) {
                    $metaAs = $getMetaGroup[1][0];
                } else {
                    $metaExp = explode("&", $va);
                    if(isset($metaExp[1])) {
                        $copyPath = $metaExp[0];
                    }
                }
                
                if ($va == 'main@Widget') {
                    $metaAs = 'mainWidget';
                }
                
                if (empty($_POST['bpTemplateTaxonomyConfig-'.$metaAs])) {
                    $dataWidgetConfig = array(
                        'ID' => getUID(), 
                        'TAXONOMY_ID' => Input::param($_POST['bpTemplateTaxonamyId-'.$metaAs]),
                        'PATH' => isset($copyPath) ? $copyPath: Input::param($metaAs),
                        'PATH_AS' => Input::param($va),
                        'IS_ADD_BUTTON' => isset($_POST['bpTemplateIsAddBtn-'.$metaAs]) ? '1' : '0',
                        'IS_ADD_FOLLOW' => isset($_POST['bpTemplateIsAddFollowBtn-'.$metaAs]) ? '1' : '0',
                        'IS_PICTURE' => isset($_POST['bpTemplateIsPictureBtn-'.$metaAs]) ? '1' : '0',
                        'IS_MULTI' => isset($_POST['bpTemplateIsMultiBtn-'.$metaAs]) ? '1' : '0',
                        'IS_HIGHLIGHT' => isset($_POST['bpTemplateIsHighlightBtn-'.$metaAs]) ? '1' : '0',
                        'IS_COPY_BUTTON' => isset($_POST['bpTemplateIsCopyBtn-'.$metaAs]) ? '1' : '0',
                        'WIDGET_CODE' => Input::param($_POST['bpTemplateWidgetCode-'.$metaAs]), 
                        'TEMPLATE_ID' => Input::post('templateId')
                    );
                    $result = $this->db->AutoExecute('NTR_TAXONOMY_CONFIG', $dataWidgetConfig);
                    if($result) {
                        $this->db->UpdateClob('NTR_TAXONOMY_CONFIG', 'EXPRESSION', Input::paramWithDoubleSpace($_POST['bpTemplateTaxonamyExpression'][$ke]), 'ID = '.$dataWidgetConfig['ID']);
                        $this->db->UpdateClob('NTR_TAXONOMY_CONFIG', 'EXPRESSION_DTL', Input::paramWithDoubleSpace($_POST['bpTemplateTaxonamyExpressionDtl'][$ke]), 'ID = '.$dataWidgetConfig['ID']);
                        $this->db->UpdateClob('NTR_TAXONOMY_CONFIG', 'EXPRESSION_DTL_KEY', Input::paramWithDoubleSpace($_POST['bpTemplateTaxonamyExpressionDtl_1'][$ke]), 'ID = '.$dataWidgetConfig['ID']);
                    }

                } else {

                    $dataWidgetConfig = array(
                        'TAXONOMY_ID' => Input::param($_POST['bpTemplateTaxonamyId-'.$metaAs]),
                        'PATH' => isset($copyPath) ? $copyPath : Input::param($metaAs),
                        'PATH_AS' => Input::param($va),
                        'IS_ADD_BUTTON' => isset($_POST['bpTemplateIsAddBtn-'.$metaAs]) ? '1' : '0',
                        'IS_ADD_FOLLOW' => isset($_POST['bpTemplateIsAddFollowBtn-'.$metaAs]) ? '1' : '0',
                        'IS_PICTURE' => isset($_POST['bpTemplateIsPictureBtn-'.$metaAs]) ? '1' : '0',
                        'IS_MULTI' => isset($_POST['bpTemplateIsMultiBtn-'.$metaAs]) ? '1' : '0',
                        'IS_HIGHLIGHT' => isset($_POST['bpTemplateIsHighlightBtn-'.$metaAs]) ? '1' : '0',
                        'IS_COPY_BUTTON' => isset($_POST['bpTemplateIsCopyBtn-'.$metaAs]) ? '1' : '0',
                        'WIDGET_CODE' => Input::param($_POST['bpTemplateWidgetCode-'.$metaAs])
                    );
                    $tid = Input::param($_POST['bpTemplateTaxonomyConfig-'.$metaAs]);
                    $result = $this->db->AutoExecute('NTR_TAXONOMY_CONFIG', $dataWidgetConfig, 'UPDATE', 'ID = '.$tid);
                    $this->db->UpdateClob('NTR_TAXONOMY_CONFIG', 'EXPRESSION', Input::paramWithDoubleSpace($_POST['bpTemplateTaxonamyExpression'][$ke]), 'ID = '.$tid);
                    $this->db->UpdateClob('NTR_TAXONOMY_CONFIG', 'EXPRESSION_DTL', Input::paramWithDoubleSpace($_POST['bpTemplateTaxonamyExpressionDtl'][$ke]), 'ID = '.$tid);
                    $this->db->UpdateClob('NTR_TAXONOMY_CONFIG', 'EXPRESSION_DTL_KEY', Input::paramWithDoubleSpace($_POST['bpTemplateTaxonamyExpressionDtl_1'][$ke]), 'ID = '.$tid);
                }

                if($result) {
                    $metaCode = $_POST['metacode-'.$metaAs];
                    $widgetExpression = $_POST['expression-'.$metaAs];

                    foreach ($metaCode as $tids => $tidsRow) {
                        if(!empty($widgetExpression[$tids]) && empty($_POST['metacodeTaxonomyWidgetId-'.$metaAs][$tids])) {
                            $dataWidget = array(
                                'ID' => getUID(), 
                                'TAXONOMY_CONFIG_ID' => isset($dataWidgetConfig['ID']) ? $dataWidgetConfig['ID'] : Input::param($_POST['bpTemplateTaxonomyConfig-'.$metaAs]), 
                                'FIELD' => $tidsRow,
                                'EXPRESSION' => Input::param($widgetExpression[$tids])
                            );
                            $this->db->AutoExecute('NTR_TAXONOMY_WIDGET', $dataWidget);

                        } elseif(!empty($widgetExpression[$tids]) && !empty($_POST['metacodeTaxonomyWidgetId-'.$metaAs][$tids])) {

                            $dataWidget = array(
                                'FIELD' => $tidsRow,
                                'EXPRESSION' => Input::param($widgetExpression[$tids])
                            );
                            $this->db->AutoExecute('NTR_TAXONOMY_WIDGET', $dataWidget, 'UPDATE', 'ID = '.Input::param($_POST['metacodeTaxonomyWidgetId-'.$metaAs][$tids]));

                        } elseif(empty($widgetExpression[$tids]) && !empty($_POST['metacodeTaxonomyWidgetId-'.$metaAs][$tids])) {
                            $this->db->Execute("DELETE FROM NTR_TAXONOMY_WIDGET WHERE ID = " . Input::param($_POST['metacodeTaxonomyWidgetId-'.$metaAs][$tids]));
                        }
                    }        
                }
            }
        }
        
        return array('status' => 'success', 'message' => 'Success');
    }

    public function addWordTemplateSaveModel() {
        $newFName   = 'process_wordtemp_' . getUID();
        $fileExtension = strtolower(substr($_FILES['templateWordFile']['name'], strrpos($_FILES['templateWordFile']['name'], '.') + 1));
        $fileName      = $newFName . '.' . $fileExtension;
        //$filePath      = UPLOADPATH . 'ecm_content/';
        $filePath = self::bpTemplateUploadGetPath();
        
        FileUpload::SetFileName($fileName);
        FileUpload::SetTempName($_FILES['templateWordFile']['tmp_name']);
        FileUpload::SetUploadDirectory($filePath);
        FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
        FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
        $uploadResult  = FileUpload::UploadFile();            

        $contentData = array(
            'CONTENT_ID' => getUID(), 
            'FILE_NAME' => $fileName,
            'PHYSICAL_PATH' => $filePath . $fileName,
            'FILE_SIZE' => $_FILES['templateWordFile']['size'],
            'FILE_EXTENSION' => $fileExtension,
            'CREATED_USER_ID' => Ue::sessionUserKeyId()
        );
        $this->db->AutoExecute('ECM_CONTENT', $contentData);            

        $templateId = getUID();
        $data = array(
            'ID' => $templateId, 
            'META_DATA_ID' => Input::post('processId'), 
            'TEMPLATE_CODE' => Input::post('templateCode'),
            'TEMPLATE_NAME' => Input::post('templateName'),
            'ITEM_ID' => Input::post('serviceId'),
            'IS_DEFAULT' => '1', 
            'IS_ACTIVE' => '1', 
            'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
            'MODIFIED_USER_ID' => Ue::sessionUserKeyId(), 
            'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s') 
        );             

        try {
            $contentFile = $contentData['PHYSICAL_PATH'];

            if($contentFile === null) {
                throw new Exception("Файл хоосон байна!"); 
            }

            $filename = trim($contentFile);
            $fileparts = pathinfo($filename);
            $extension = strtolower($fileparts['extension']);
            if (!in_array($extension, array('doc', 'docx')))
                throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");

            if (!file_exists($contentFile)) {
                throw new Exception("Файл олдсонгүй!"); 
            }

            $data['CONTENT_ID'] = $contentData['CONTENT_ID'];

            $file = $contentFile;
            $handle = fopen($file, "rb");
            $contents = fread($handle, filesize($file));
            fclose($handle);
            $byte_array = unpack('c*', $contents);
            $ser = serialize($byte_array);

            $inparams = array(
                'file' => $ser
            );                
            $saveCache = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getWordContentAsHtml", $inparams);
            
            if(!isset($saveCache['result']))
                return array('status' => 'error', 'message' => $saveCache['text']);
            
            $htmlTemplate = isset($saveCache['result']['getwordcontentashtml']) ? base64_decode($saveCache['result']['getwordcontentashtml']) : base64_decode($saveCache['result']['result']);

            $htmlTemplate = preg_replace("/<img[^>]+\>/i", "", $htmlTemplate);
            $htmlTemplate = preg_replace('/<span id="_GoBack"\/>/i', "", $htmlTemplate);

            $htmlTemplate = html_entity_decode($htmlTemplate, ENT_QUOTES, 'UTF-8');            

            preg_match_all('/<p>.*?<\/p>/', $htmlTemplate, $parseMeta2);
            if(!empty($parseMeta2[0])) {
                foreach ($parseMeta2[0] as $pv) {
                    $htmlTemplate = str_replace($pv, '<p>'.strip_tags($pv).'</p>', $htmlTemplate);
                }
            }

            //preg_match_all('/<p .*? style="(.*?)">.*?<\/p>/', $htmlTemplate, $parseMeta2Style);
            preg_match_all('/<p style="(.*?)">.*?<\/p>/', $htmlTemplate, $parseMeta2Style);
            if(!empty($parseMeta2Style[0])) {
                foreach ($parseMeta2Style[0] as $pk => $pv) {
                    if(strpos($pv, '#') !== false)
                        $htmlTemplate = str_replace($pv, '<p style="'.$parseMeta2Style[1][$pk].'">'.strip_tags($pv).'</p>', $htmlTemplate);
                }
            }

            $path = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
            $htmlFilePath = $path . $templateId.'.html';
        
            if (file_put_contents($htmlFilePath, $htmlTemplate)) {
                $data['HTML_FILE_PATH'] = $htmlFilePath;
            }
            
            $result = $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data);

            if ($result) {
                $dataMap = array(
                    'ID' => getUID(), 
                    'FOLDER_ID' => Input::post('folderId'), 
                    'TEMPLATE_ID' => $templateId
                );
                $result = $this->db->AutoExecute('META_BP_TEMPLATE_FOLDER_MAP', $dataMap);        
            }                
            return array('status' => 'success', 'message' => 'Success');

        } catch (Exception $e) {
            return array('status' => 'warning', 'message' => $e->getMessage());
        }                                    

        return array('status' => 'error', 'message' => 'Error');
    }

    public function addWordTemplateCheckUpdateModel() {
        if(!empty($_FILES['templateWordFile'])) {
            $data = $this->db->GetRow("
                SELECT ID 
                FROM NTR_TAXONOMY_CONFIG
                    WHERE TEMPLATE_ID = " . Input::post('templateId')); 

            if ($data) {
                return array('status' => 'error', 'message' => 'Энэ загварт Taxonamy тохиргоо үүссэн байна! <br> Taxonamy Config, Widget Expression утстахыг анхаарна уу!');
            }
        }      

        return array('status' => 'success', 'message' => '');
    }

    public function addWordTemplateUpdateModel() {
        if(!empty($_FILES['templateWordFile'])) {
            $newFName   = 'process_wordtemp_' . getUID();
            $fileExtension = strtolower(substr($_FILES['templateWordFile']['name'], strrpos($_FILES['templateWordFile']['name'], '.') + 1));
            $fileName      = $newFName . '.' . $fileExtension;
            //$filePath      = UPLOADPATH . 'ecm_content/';
            $filePath = self::bpTemplateUploadGetPath();
            
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($_FILES['templateWordFile']['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
            $uploadResult  = FileUpload::UploadFile(); 

            $contentData = array(
                'CONTENT_ID' => getUID(), 
                'FILE_NAME' => $fileName,
                'PHYSICAL_PATH' => $filePath . $fileName,
                'FILE_SIZE' => $_FILES['templateWordFile']['size'],
                'FILE_EXTENSION' => $fileExtension,
                'CREATED_USER_ID' => Ue::sessionUserKeyId()
            );
            $this->db->AutoExecute('ECM_CONTENT', $contentData);
        }

        $templateId = getUID();
        $data = array(
            'TEMPLATE_CODE' => Input::post('templateCode'),
            'TEMPLATE_NAME' => Input::post('templateName'),
            'ITEM_ID' => Input::post('serviceId'),
            'IS_DEFAULT' => '1', 
            'IS_ACTIVE' => '1', 
            'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
            'MODIFIED_USER_ID' => Ue::sessionUserKeyId(), 
            'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s') 
        );             
        $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data, 'UPDATE', 'ID = '.Input::post('templateId'));

        try {
            
            if(!empty($_FILES['templateWordFile'])) {
                $contentFile = $contentData['PHYSICAL_PATH'];

                if($contentFile === null) {
                    throw new Exception("Файл хоосон байна!"); 
                }

                $filename = trim($contentFile);
                $fileparts = pathinfo($filename);
                $extension = strtolower($fileparts['extension']);
                if(!in_array($extension, array('doc', 'docx')))
                    throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");

                if(!file_exists($contentFile)) {
                    throw new Exception("Файл олдсонгүй!"); 
                }

                $file = $contentFile;
                $handle = fopen($file, "rb");
                $contents = fread($handle, filesize($file));
                fclose($handle);
                $byte_array = unpack('c*', $contents);
                $ser = serialize($byte_array);

                $inparams = array(
                    'file' => $ser
                );                
                $saveCache = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getWordContentAsHtml", $inparams);
                $htmlTemplate = isset($saveCache['result']['getwordcontentashtml']) ? base64_decode($saveCache['result']['getwordcontentashtml']) : base64_decode($saveCache['result']['result']);

                $htmlTemplate = preg_replace("/<img[^>]+\>/i", "", $htmlTemplate);
                $htmlTemplate = preg_replace('/<span id="_GoBack"\/>/i', "", $htmlTemplate);   
                
                $path = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
                $htmlFilePath = $path . $templateId.'.html';

                $htmlTemplate = html_entity_decode($htmlTemplate, ENT_QUOTES, 'UTF-8');

                preg_match_all('/<p>.*?<\/p>/', $htmlTemplate, $parseMeta2);
                if (!empty($parseMeta2[0])) {
                    foreach ($parseMeta2[0] as $pv) {
                        $htmlTemplate = str_replace($pv, '<p>'.strip_tags($pv).'</p>', $htmlTemplate);
                    }
                }          

                if(file_put_contents($htmlFilePath, $htmlTemplate)) {
                    $data = array(
                        'HTML_FILE_PATH' => $htmlFilePath,
                        'CONTENT_ID' => $contentData['CONTENT_ID']
                    );
                    $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data, 'UPDATE', 'ID = '.Input::post('templateId'));

                    /* $this->db->Execute("DELETE FROM NTR_TAXONOMY_CONFIG WHERE TEMPLATE_ID = " . Input::post('templateId')); */
                }
            }

            return array('status' => 'success', 'message' => 'Success');

        } catch (Exception $e) {
            return array('status' => 'warning', 'message' => $e->getMessage());
        }                                    

        return array('status' => 'error', 'message' => 'Error');
    }
    
    public function updateWordTemplateModel() {
        
        $currentDate = Date::currentDate('Y-m-d H:i:s');
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        if (!empty($_FILES['templateWordFile'])) {
            $newFName   = 'process_wordtemp_update_' . getUID();
            $fileExtension = strtolower(substr($_FILES['templateWordFile']['name'], strrpos($_FILES['templateWordFile']['name'], '.') + 1));
            $fileName      = $newFName . '.' . $fileExtension;
            //$filePath      = UPLOADPATH . 'ecm_content/';
            $filePath = self::bpTemplateUploadGetPath();
            
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($_FILES['templateWordFile']['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
            $uploadResult  = FileUpload::UploadFile(); 

            $contentData = array(
                'CONTENT_ID' => getUID(), 
                'FILE_NAME' => $fileName,
                'PHYSICAL_PATH' => $filePath . $fileName,
                'FILE_SIZE' => $_FILES['templateWordFile']['size'],
                'FILE_EXTENSION' => $fileExtension,
                'CREATED_USER_ID' => $sessionUserKeyId
            );
            $this->db->AutoExecute('ECM_CONTENT', $contentData);
        }

        $templateId = getUID();
        $data = array(
            'MODIFIED_USER_ID' => $sessionUserKeyId, 
            'MODIFIED_DATE' => $currentDate
        );
        
        $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data, 'UPDATE', 'ID = '.Input::post('templateId'));

        try {
            
            if(!empty($_FILES['templateWordFile'])) {
                $contentFile = $contentData['PHYSICAL_PATH'];

                if($contentFile === null) {
                    throw new Exception("Файл хоосон байна!"); 
                }

                $filename = trim($contentFile);
                $fileparts = pathinfo($filename);
                $extension = strtolower($fileparts['extension']);
                if (!in_array($extension, array('doc', 'docx')))
                    throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");

                if (!file_exists($contentFile)) {
                    throw new Exception("Файл олдсонгүй!"); 
                }

                $file = $contentFile;
                $handle = fopen($file, "rb");
                $contents = fread($handle, filesize($file));
                fclose($handle);
                $byte_array = unpack('c*', $contents);
                $ser = serialize($byte_array);

                $inparams = array(
                    'file' => $ser
                );            
                
                $saveCache = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getWordContentAsHtml", $inparams);
                $htmlTemplate = isset($saveCache['result']['getwordcontentashtml']) ? base64_decode($saveCache['result']['getwordcontentashtml']) : base64_decode($saveCache['result']['result']);

                $htmlTemplate = preg_replace("/<img[^>]+\>/i", "", $htmlTemplate);
                $htmlTemplate = preg_replace('/<span id="_GoBack"\/>/i', "", $htmlTemplate);      
                
                $path = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
                $htmlFilePath = $path . $templateId.'.html';

                $htmlTemplate = html_entity_decode($htmlTemplate, ENT_QUOTES, 'UTF-8');
                //$htmlTemplate = strip_tags($htmlTemplate, "<html><head><style><p><div><br/><br><table><th><td><thead><tbody><tfoot>");
                
                preg_match_all('/<p>.*?<\/p>/', $htmlTemplate, $parseMeta2);
                if (!empty($parseMeta2[0])) {
                    foreach ($parseMeta2[0] as $pv) {
                        $htmlTemplate = str_replace($pv, '<p>'.strip_tags($pv).'</p>', $htmlTemplate);
                    }
                }
                
                if (file_put_contents($htmlFilePath, $htmlTemplate)) {
                    $data = array(
                        'HTML_FILE_PATH' => $htmlFilePath,
                        'CONTENT_ID' => $contentData['CONTENT_ID']
                    );
                    $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data, 'UPDATE', 'ID = '.Input::post('templateId'));
                }
            }

            return array('status' => 'success', 'message' => 'Success');

        } catch (Exception $e) {
            return array('status' => 'warning', 'message' => $e->getMessage());
        }                                    

        return array('status' => 'error', 'message' => 'Error');
    }

    public function getBPTemplateByIdModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                PT.ID, 
                PT.META_DATA_ID, 
                PT.TEMPLATE_CODE, 
                PT.TEMPLATE_NAME, 
                PT.IS_DEFAULT, 
                PT.IS_ACTIVE, 
                PT.HTML_FILE_PATH,
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                '' AS FOLDER_ID,
                EC.FILE_NAME,
                PT.CONTENT_ID,
                PT.CONTROL_DESIGN,
                'assets/core/global/img/grid_layout/' || EXT.ICON AS CON_ICON
            FROM META_BUSINESS_PROCESS_TEMPLATE PT 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = PT.META_DATA_ID 
                LEFT JOIN ECM_CONTENT EC ON EC.CONTENT_ID = PT.CONTENT_ID 
                LEFT JOIN ECM_CONTENT_EXTENSION EXT ON UPPER(EXT.CODE) = UPPER(EC.FILE_EXTENSION)                    
            WHERE PT.ID = $id"); 

        if ($row) {

            $row['HTML_CONTENT'] = '';
            if ($row['HTML_FILE_PATH'] != '' && file_exists($row['HTML_FILE_PATH'])) {
                $row['HTML_CONTENT'] = file_get_contents($row['HTML_FILE_PATH']);
            }

            $folder = $this->db->GetRow("
                SELECT 
                    FOLDER_ID  
                FROM META_BP_TEMPLATE_FOLDER_MAP  
                WHERE TEMPLATE_ID = $id"
            );
            if ($folder) {
                $row = array_merge($row, $folder);
            }
        }

        return $row;
    }

    public function getBPTemplateWidgetByIdModel($id) {

        $data = $this->db->GetAll("
            SELECT 
                WIDGET_CODE 
            FROM META_BP_TEMPLATE_WIDGET      
            WHERE TEMPLATE_ID = $id"); 

        $array = array();

        if ($data) {

            foreach ($data as $row) {
                $array[$row['WIDGET_CODE']] = 1;
            }
        }

        return $array;
    }

    public function editBpTemplateSaveModel() {

        $templateId = Input::post('id');
        $processMetaDataId = Input::post('processId');

        $data = array(
            'META_DATA_ID' => $processMetaDataId,
            'TEMPLATE_CODE' => Input::post('templateCode'),
            'CONTROL_DESIGN' => Input::post('controlDesign'),
            'TEMPLATE_NAME' => Input::post('templateName'),
            'IS_DEFAULT' => Input::postCheck('isDefault') ? 1 : 0,
            'IS_ACTIVE' => Input::postCheck('isActive') ? 1 : 0, 
            'MODIFIED_USER_ID' => Ue::sessionUserKeyId(), 
            'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s')
        );

        $path = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
        $htmlFilePath = $path . $templateId.'.html';
        
        if (file_put_contents($htmlFilePath, Input::postNonTags('tempEditor'))) {
            $data['HTML_FILE_PATH'] = $htmlFilePath;
        }

        $result = $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data, 'UPDATE', 'ID = '.$templateId);

        if ($result) {

            $this->db->Execute("DELETE FROM META_BP_TEMPLATE_FOLDER_MAP WHERE TEMPLATE_ID = ".$templateId);

            if (Input::isEmpty('folderId') == false) {

                $dataMap = array(
                    'ID' => getUID(), 
                    'FOLDER_ID' => Input::post('folderId'), 
                    'TEMPLATE_ID' => $templateId 
                );
                $result = $this->db->AutoExecute('META_BP_TEMPLATE_FOLDER_MAP', $dataMap);
            }

            $this->db->Execute("DELETE FROM META_BP_TEMPLATE_WIDGET WHERE TEMPLATE_ID = ".$templateId);

            if (Input::postCheck('templateWidget')) {

                $templateWidget = $_POST['templateWidget'];

                foreach ($templateWidget as $widget) {
                    $dataWidget = array(
                        'ID' => getUID(), 
                        'TEMPLATE_ID' => $templateId, 
                        'WIDGET_CODE' => $widget, 
                        'IS_ACTIVE' => 1 
                    );
                    $result = $this->db->AutoExecute('META_BP_TEMPLATE_WIDGET', $dataWidget);
                }
            }

            (new Mdmeta())->setMetaModifiedDate($processMetaDataId);

            $tmp_dir = Mdcommon::getCacheDirectory();

            $bpConfigFile = glob($tmp_dir."/*/bp/bpConfig_".$processMetaDataId.".txt");
            foreach ($bpConfigFile as $configFile) {
                @unlink($configFile);
            }

            return array('status' => 'success', 'message' => 'Success');
        }

        return array('status' => 'error', 'message' => 'Error');
    }

    public function copyBpTemplateSaveModel() {

        $prevTemplateId = Input::post('id');

        $row = self::getBPTemplateByIdModel($prevTemplateId);

        $templateId = getUID();
        $processMetaDataId = $row['META_DATA_ID'];

        $data = array(
            'ID' => $templateId, 
            'META_DATA_ID' => $processMetaDataId, 
            'TEMPLATE_CODE' => Input::post('templateCode'),
            'TEMPLATE_NAME' => Input::post('templateName'),
            'IS_DEFAULT' => $row['IS_DEFAULT'], 
            'IS_ACTIVE' => $row['IS_ACTIVE'], 
            'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
        );
        
        $path = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
        
        $htmlFilePath = $path . $prevTemplateId.'.html';
        $newHtmlFilePath = $path . $templateId.'.html';
        
        if (copy($htmlFilePath, $newHtmlFilePath)) {
            $data['HTML_FILE_PATH'] = $newHtmlFilePath;
        }

        $result = $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data);

        if ($result) {

            $getFolder = $this->db->GetRow("SELECT FOLDER_ID FROM META_BP_TEMPLATE_FOLDER_MAP WHERE TEMPLATE_ID = ".$prevTemplateId);

            if ($getFolder) {
                $dataMap = array(
                    'ID' => getUID(), 
                    'FOLDER_ID' => $getFolder['FOLDER_ID'], 
                    'TEMPLATE_ID' => $templateId 
                );
                $result = $this->db->AutoExecute('META_BP_TEMPLATE_FOLDER_MAP', $dataMap);
            }

            $getWidgets = $this->db->GetAll("SELECT WIDGET_CODE FROM META_BP_TEMPLATE_WIDGET WHERE TEMPLATE_ID = ".$prevTemplateId);

            if ($getWidgets) {

                foreach ($getWidgets as $widget) {
                    $dataWidget = array(
                        'ID' => getUID(), 
                        'TEMPLATE_ID' => $templateId, 
                        'WIDGET_CODE' => $widget['WIDGET_CODE'], 
                        'IS_ACTIVE' => 1 
                    );
                    $result = $this->db->AutoExecute('META_BP_TEMPLATE_WIDGET', $dataWidget);
                }
            }

            $tmp_dir = Mdcommon::getCacheDirectory();

            $bpConfigFile = glob($tmp_dir."/*/bp/bpConfig_".$processMetaDataId.".txt");
            foreach ($bpConfigFile as $configFile) {
                @unlink($configFile);
            }

            return array('status' => 'success', 'message' => 'Success');
        }

        return array('status' => 'error', 'message' => 'Error');
    }

    public function getBProcessParamTypeModel($processId, $paramPath) {
        $row = $this->db->GetRow("
            SELECT 
                DATA_TYPE  
            FROM META_PROCESS_PARAM_ATTR_LINK  
            WHERE PROCESS_META_DATA_ID = $processId 
                AND LOWER(PARAM_REAL_PATH) = '".strtolower($paramPath)."'");

        if ($row) {
            return $row['DATA_TYPE'];
        } 

        return '';
    }

    public function checkOcrProcessModel() {

        $selectedRow = Input::post('selectedRow');

        if (isset($selectedRow['contenttypeid']) && $selectedRow['contenttypeid'] != '') {

            $typeId = Input::param($selectedRow['contenttypeid']);

            $data = $this->db->GetAll("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_NAME 
                FROM ECM_CONTENT_PROCESS_MAP PM 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PM.PROCESS_META_DATA_ID 
                WHERE PM.IS_ACTIVE = 1 AND PM.TYPE_ID = ".$typeId);

            if ($data) {
                $response = array('status' => 'success', 'processList' => $data);
            } else {
                $response = array('status' => 'error', 'message' => 'Төрөл дээр процессын тохиргоо хийгдээгүй байна');
            }

        } else {
            $response = array('status' => 'error', 'message' => 'Төрөл тодорхойгүй байна');
        }

        return $response;
    }

    public function ocrApiProcessModel() {

        $processId = Input::post('processId');
        $key = Input::post('key');
        $paramType = self::getBProcessParamTypeModel($processId, $key);

        $jpeg_quality = 90;
        $src = Input::post('image_path');
        $x = Input::post('x');
        $y = Input::post('y');
        $w = Input::post('w');
        $h = Input::post('h');
        $fileName = BASEPATH.UPLOADPATH.'ocr/'.getUID().'.jpg';

        $img_r = imagecreatefromjpeg($src);
        $dst_r = imagecreatetruecolor($w, $h);

        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w, $h);

        imagejpeg($dst_r, $fileName, $jpeg_quality);
        imagedestroy($dst_r); 

        includeLib('Ocr/Abbyy/Abbyy');

        $ocr = new Abbyy();
        $text = $ocr->convert('txt', $fileName);

        $convertedText = Str::remove_doublewhitespace(trim(Str::nlToSpace($text)));

        return array('text' => $convertedText, 'type' => $paramType);
    }

    public function bpTemplateAttachModel($processId, $bpTemplateId, $refStructureId, $sourceId, $isEditMode) {

        $data = $this->db->GetAll("
            SELECT 
                PP.ID, 
                PP.NAME  
            FROM META_PROCESS_CHECKLIST PC 
                INNER JOIN META_PROCESS_CHECKLIST_TEMP CT ON CT.PROCESS_CHECKLIST_ID = PC.ID 
                LEFT JOIN META_PROCESS_CHECKLIST PP ON PP.ID = PC.PARENT_ID 
            WHERE CT.PROCESS_TEMPLATE_ID = $bpTemplateId 
            GROUP BY 
                PP.ID, 
                PP.NAME, 
                PP.ORDER_NUM 
            ORDER BY PP.ORDER_NUM ASC"); 

        $html = null;

        if ($data) {

            $select = '';
            $join = '';

            if ($refStructureId && $sourceId) {
                $select = 'CM.FILE_URL, LOWER(CM.FILE_EXTENSION) AS FILE_EXTENSION,';
                $join = "LEFT JOIN META_PROCESS_CHECKLIST_MAP CM ON CM.CHECKLIST_ID = PC.ID AND CM.STRUCTURE_ID = $refStructureId AND CM.RECORD_ID = $sourceId";
            }
            $html = '<script type="text/javascript">$("head").append(\'<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>\');</script>';

            $html .= '<div class="card light bp-tmp-attach-part">
                <div class="card-header card-header-no-padding header-elements-inline">
                    <div class="card-title">
                        <i class="fa fa-paperclip"></i>
                        <span class="caption-subject font-weight-bold uppercase">Хавсралт баримт бичиг</span>
                    </div>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">';

            foreach ($data as $row) {

                $html .= '<div class="bp-tmp-attach-group">';
                    $html .= '<div class="bp-tmp-attach-group-title"><i class="fa fa-angle-right"></i> '.$row['NAME'].'</div>';

                    $dataChild = $this->db->GetAll("
                        SELECT 
                            $select  
                            PC.ID, 
                            PC.NAME 
                        FROM META_PROCESS_CHECKLIST PC 
                            INNER JOIN META_PROCESS_CHECKLIST_TEMP CT ON CT.PROCESS_CHECKLIST_ID = PC.ID 
                            $join 
                        WHERE CT.PROCESS_TEMPLATE_ID = $bpTemplateId AND PC.PARENT_ID = ".$row['ID']." 
                        GROUP BY 
                            PC.ID, 
                            PC.NAME, 
                            PC.ORDER_NUM 
                        ORDER BY PC.ORDER_NUM ASC"); 

                    if ($dataChild) {

                        $html .= '<div class="bp-tmp-attach-table">';

                        foreach ($dataChild as $rowChild) {

                            $icon = '<i class="fa fa-file"></i>';

                            if (isset($rowChild['FILE_EXTENSION'])) {
                                if ($rowChild['FILE_EXTENSION'] == 'pdf') {
                                    $icon = '<i class="fa fa-file-pdf-o"></i>';
                                } elseif ($rowChild['FILE_EXTENSION'] == 'doc' || $rowChild['FILE_EXTENSION'] == 'docx') {
                                    $icon = '<i class="fa fa-file-word-o"></i>';
                                } elseif ($rowChild['FILE_EXTENSION'] == 'xls' || $rowChild['FILE_EXTENSION'] == 'xlsx') {
                                    $icon = '<i class="fa fa-file-excel-o"></i>';
                                } elseif ($rowChild['FILE_EXTENSION'] == 'png' || $rowChild['FILE_EXTENSION'] == 'jpeg' 
                                    || $rowChild['FILE_EXTENSION'] == 'gif' || $rowChild['FILE_EXTENSION'] == 'jpg') {
                                    $icon = '<a href="'.$rowChild['FILE_URL'].'" class="fancybox-button main" data-rel="fancybox-button"><img src="'.$rowChild['FILE_URL'].'"/></a>';
                                }
                            }

                            $html .= '<table><tbody>';
                                $html .= '<tr>';
                                    $html .= '<td colspan="2" class="tmp-attach-title">'.$rowChild['NAME'].'</td>';
                                $html .= '</tr>';
                                $html .= '<tr>';
                                    $html .= '<td class="tmp-attach-action" style="width: 30%">';
                                        $html .= '<a href="javascript:;" class="btn btn-circle btn-block default btn-sm fileinput-button">Файл сонгох<input type="file" name="bp_attach['.$rowChild['ID'].'][]" onchange="bpTmpAttach(this);"/></a>';
                                        $html .= '<button type="button" class="btn btn-circle btn-block default btn-sm" onclick="bpTmpScanner(this);">Сканнер</button>';
                                        $html .= '<button type="button" class="btn btn-circle btn-block default btn-sm" onclick="bpTmpWebCamera(this);">Вэбкамер</button>';
                                        $html .= '<input type="hidden" name="bp_attach_photo['.$rowChild['ID'].'][]"/>';
                                        $html .= '<input type="hidden" name="bp_attach_photo_thumb['.$rowChild['ID'].'][]"/>';
                                        $html .= '<input type="hidden" name="bp_attach_photo_extension['.$rowChild['ID'].'][]"/>';
                                        $html .= '<input type="hidden" name="bp_attach_url['.$rowChild['ID'].'][]"/>';
                                    $html .= '</td>';
                                    $html .= '<td class="tmp-attach-thumb" style="width: 70%">';
                                        $html .= '<div class="tmp-attach-thumb-wrap">'.$icon.'</div>';
                                    $html .= '</td>';
                                $html .= '</tr>';
                            $html .= '</tbody></table>';

                        }

                        $html .= '</div>';
                    }

                $html .= '</div>';
            }

            $html .= '</div></div>';
        }

        return $html;
    }

    public function viewWordTemplateModel($selectedRow) {
        try {

            if (Input::post('isDeleteContent') === '1') {
                $selectedRow['contentid'] = null;
                $this->db->AutoExecute('NTR_SERVICE_BOOK', array('CONTENT_ID' => NULL), 'UPDATE', 'ID = ' . $selectedRow['id']);
            }

            if (isset($selectedRow['contentid']) && !empty($selectedRow['contentid'])) {
                $getContent = $this->getEcmContentByIdModel($selectedRow['contentid']);
                if ($getContent === false) {
                    throw new Exception("Контент устсан байна!");
                }

                return array(
                    'status' => 'success', 
                    '_' => '', 
                    'filepath' => $getContent['PHYSICAL_PATH']
                );
            }                

            if (!isset($selectedRow['templateid'])) {
                throw new Exception("Templateid байхгүй байна!"); 
            }

            if (empty($selectedRow['templateid'])) {
                throw new Exception("Templateid хоосон байна!"); 
            }
            
            $dataViewId = Input::post('dataViewId');
            $getContent = $this->getEcmContentModel($selectedRow['templateid']);
            
            $getProcessCommand = $this->getRunProcessCommandModel($dataViewId, Input::post('webLinkId'));
            
            if (!$getProcessCommand) {
                throw new Exception("Процесс команд тохируулаагүй байна!"); 
            }
            
            if (isset($selectedRow['getprocesscode'])) {
                $getProcessCommand['COMMAND_NAME'] = $selectedRow['getprocesscode'];
            }
            
            if (!$getContent) {
                throw new Exception("Контентоос бичлэг олдсонгүй!"); 
            }

            if (!isset($getContent['PHYSICAL_PATH']) || empty($getContent['PHYSICAL_PATH'])) {
                throw new Exception("Файл хоосон байна!"); 
            }

            $filename = trim($getContent['PHYSICAL_PATH']);
            
            $fileparts = pathinfo($filename);
            $extension = strtolower($fileparts['extension']);
            
            if (!in_array($extension, array('doc', 'docx')))
                throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");
                
            if (!file_exists($filename)) {
                throw new Exception("Файл олдсонгүй!"); 
            }
        
            $contentName = getUID();
            $wordFilePath = self::bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
            $wordFilePath .= 'file_'.$contentName.'.docx';

            $saveCache = self::replaceWordTags($getContent['PHYSICAL_PATH'], $wordFilePath, $selectedRow['id'], $getProcessCommand['COMMAND_NAME']);
            
            if ($saveCache['status'] === 'success') {
                $replacedWordTemplate = isset($saveCache['result']['replacewordtags']) ? base64_decode($saveCache['result']['replacewordtags']) : base64_decode($saveCache['result']['result']);
            } else {
                throw new Exception($saveCache['text']);
            }
            
            $groupData = $this->db->GetRow("
                SELECT 
                    T0.TABLE_NAME, T0.GROUP_TYPE, T1.REF_STRUCTURE_ID 
                FROM META_GROUP_LINK T1
                    INNER JOIN META_GROUP_LINK T0 ON T1.REF_STRUCTURE_ID = T0.META_DATA_ID
                WHERE T1.META_DATA_ID = $dataViewId");

            if (isset($groupData['TABLE_NAME']) && $groupData['TABLE_NAME'] && isset($groupData['GROUP_TYPE']) && strtolower($groupData['GROUP_TYPE']) === 'tablestructure') {
                $tableName = $groupData['TABLE_NAME'];
            } else {
                throw new Exception("Structure олдсонгүй!");
            }

            if (file_exists($wordFilePath)) {
                $contentData = array(
                    'CONTENT_ID' => $contentName, 
                    'FILE_NAME' => 'file_'.$contentName.'.docx',
                    'PHYSICAL_PATH' => $wordFilePath,
                    'FILE_SIZE' => filesize($wordFilePath),
                    'FILE_EXTENSION' => 'docx',
                    'CREATED_USER_ID' => Ue::sessionUserKeyId()
                );
                
                if (issetParam($selectedRow['iscontentmap']) === '1') {
                    $contentData['RELATED_ID'] = $selectedRow['id'];
                    $contentData['IS_DEFAULT'] = '1';
                    $this->db->AutoExecute('ECM_CONTENT', array('IS_DEFAULT' => '0'), 'UPDATE', "RELATED_ID='" . $selectedRow['id'] . "'");
                }

                $this->db->AutoExecute('ECM_CONTENT', $contentData);
                
                $bookData = array(
                    'CONTENT_ID' => $contentName
                );
                
                $this->load->model('mddatamodel', 'middleware/models/');
                $refConfig = $this->model->getCodeNameFieldNameModel($groupData['REF_STRUCTURE_ID']);

                if (issetParam($selectedRow['iscontentmap']) !== '1') {
                    if (isset($refConfig['idColumnName'])) {
                        $updateIdField = $refConfig['idColumnName'];
                    } else {
                        $updateIdField = 'ID';
                    }
                    
                    $result = $this->db->AutoExecute($tableName, $bookData, 'UPDATE', $updateIdField.' = '.$selectedRow['id']);                    
                }
                
                (Array) $childRows = array();
                if (0 < issetParam($selectedRow['childrecordcount'])) {
                    $dataViewId = Input::post('dataViewId');
                    includeLib('Utils/Functions');
                    $criteria = array(
                        'parentid' => array(
                            array (
                                'operator' => '=',
                                'operand' => $selectedRow['id']
                            )
                        )
                    );
                    
                    $result = Functions::runDataView($dataViewId, $criteria);
                    $childRowsTemp = issetParamArray($result['result']);
                    foreach ($childRowsTemp as $sk => $sRow) {
                        $tempa = self::viewWordTemplateModel($sRow);
                        $sRow['contentid'] = $tempa['contentid'];
                        $sRow['physicalpath'] = $tempa['filepath'];
                        array_push($childRows, $sRow);
                    }
                }

                if (issetParam($selectedRow['isconsul']) === '1' && file_exists($wordFilePath)) {
                    $tableName = issetDefaultVal($selectedRow['targettable'], 'NTR_SERVICE_BOOK');
                    $this->db->AutoExecute($tableName, array('WORD_PATH' => $wordFilePath), "UPDATE", "ID = '". $selectedRow['id'] ."'");
                }
    
                return array(
                    'status' => 'success', 
                    'filepath' => $wordFilePath,
                    'contentid' => $contentName,
                    'childRows' => $childRows
                );
            } else {
                throw new Exception("Replace Файл олдсонгүй!"); 
            }

        } catch (Exception $e) {
            return array('status' => 'warning', 'message' => $e->getMessage());
        }

        return array('status' => 'error', 'message' => 'Error');
    }
    
    public function viewWordTemplatePdfModel($selectedRow) {
        try {
            $getContent = $this->getEcmContentModel($selectedRow['templateid']);
            $getProcessCommand = $this->getRunProcessCommandModel(Input::numeric('dataViewId'), Input::numeric('webLinkId'));

            if (!$getContent) {
                throw new Exception("Контентоос бичлэг олдсонгүй!"); 
            }
            if (!$getProcessCommand) {
                throw new Exception("Процесс команд тохируулаагүй байна!"); 
            }
            if (!isset($selectedRow['physicalpath']) || empty($selectedRow['physicalpath'])) {
                throw new Exception("Файл хоосон байна!"); 
            }
            
            if (issetParam($selectedRow['isconsul']) === '1') {
                $selectedRow['physicalpath'] = $selectedRow['wordpath'];
            }

            $filename = trim($selectedRow['physicalpath']);
            $fileparts = pathinfo($filename);
            $extension = strtolower($fileparts['extension']);

            if (!in_array($extension, array('doc', 'docx'))) {
                throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");
            }

            if (!file_exists($filename)) {
                throw new Exception("Файл олдсонгүй!"); 
            }
            
            $path_ = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
            $newPath = $path_ . 'doc_' . getUID() . '.' . $extension;
            @copy($selectedRow['physicalpath'], $newPath);
            
            $tableName = issetDefaultVal($selectedRow['targettable'], 'NTR_SERVICE_BOOK');
            $this->db->AutoExecute($tableName, array('WORD_PATH' => $newPath), "UPDATE", "ID = '". $selectedRow['id'] ."'");
            
            $contentName = getUID();
            $wordFilePath = self::bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
            $wordFilePath .= 'file_'.$contentName.'.docx';

            $saveCache = self::replaceWordTags($selectedRow['physicalpath'], $wordFilePath, $selectedRow['id'], $getProcessCommand['COMMAND_NAME']);

            if ($saveCache['status'] === 'success') {
                $replacedWordTemplate = isset($saveCache['result']['replacewordtags']) ? base64_decode($saveCache['result']['replacewordtags']) : base64_decode($saveCache['result']['result']);
            }
            else {
                throw new Exception($saveCache['text']);
            }
            
            if (file_exists($wordFilePath)) {
                $contentData = array(
                    'FILE_NAME' => 'file_'.$contentName.'.docx',
                    'PHYSICAL_PATH' => $wordFilePath,
                    'FILE_SIZE' => filesize($wordFilePath),
                    'FILE_EXTENSION' => 'docx',
                    'CREATED_USER_ID' => Ue::sessionUserKeyId()
                );

                if (issetParam($selectedRow['iscontentmap']) === '1') {
                    $contentData['RELATED_ID'] = $selectedRow['id'];
                    $contentData['IS_DEFAULT'] = '1';
                    $contentData['CONTENT_ID'] = getUID();
                    $this->db->AutoExecute('ECM_CONTENT', array('IS_DEFAULT' => '0'), 'UPDATE', "RELATED_ID='" . $selectedRow['id'] . "'");
                    $this->db->AutoExecute('ECM_CONTENT', $contentData);
                } else {
                    $this->db->AutoExecute('ECM_CONTENT', $contentData, 'UPDATE', 'CONTENT_ID = '.$selectedRow['contentid']);
                }

                $qrCode = array();
                $qrCode['image'] = '';

                if (issetParam($selectedRow['qrcode']) == '' && issetParam($selectedRow['id']) !== '') {
                    $qrCode = self::generateQrCode($selectedRow['id']);
                    $tableName = issetDefaultVal($selectedRow['targettable'], 'NTR_SERVICE_BOOK');
                    $result = $this->db->AutoExecute($tableName, array('QR_NUMBER' => issetParam($qrCode['string'])) , 'UPDATE',  'ID = '. $selectedRow['id']);
                    $result = $this->db->UpdateClob($tableName, 'QR_CODE', issetParam($qrCode['image']), 'ID = ' . $selectedRow['id']);
                }
                
                (Array) $childRows = array();
                if (0 < issetParam($selectedRow['childrecordcount'])) {
                    $dataViewId = Input::post('dataViewId');
                    includeLib('Utils/Functions');
                    $criteria = array(
                        'parentid' => array(
                            array(
                                'operator' => '=',
                                'operand' => $selectedRow['id']
                            )
                        )
                    );
                    $result = Functions::runDataView($dataViewId, $criteria);
                    $childRows = issetParamArray($result['result']);
                }

                return array(
                    'status' => 'success', 
                    'filepath' => $wordFilePath,
                    'qrCodeString' => issetParam($qrCode['image']),
                    'childRows' => $childRows
                );
            } else {
                throw new Exception("Replace Файл олдсонгүй!"); 
            }

        } catch (ADODB_Exception $ex) {
            return array('status' => 'warning', 'message' => 'Error - viewWordTemplatePdfModel');
        } catch (Exception $e) {
            return array('status' => 'warning', 'message' => $e->getMessage());
        }

        return array('status' => 'error', 'message' => 'Error');
    }

    public function viewWordTemplatePdfModelNew($selectedRow) {        
        try {           
            $filesjoinStr = '';
            $getContent = $this->getEcmContentModel($selectedRow['templateid']);
            $getProcessCommand = $this->getRunProcessCommandModel(Input::post('dataViewId'), Input::post('webLinkId'));

            if (!$getContent) {
                throw new Exception("Контентоос бичлэг олдсонгүй!"); 
            }
            if (!$getProcessCommand) {
                throw new Exception("Процесс команд тохируулаагүй байна!"); 
            }
            if (!isset($selectedRow['physicalpath']) || empty($selectedRow['physicalpath'])) {
                throw new Exception("Файл хоосон байна!"); 
            }
            
            $filename = trim($selectedRow['physicalpath']);
            $fileparts = pathinfo($filename);
            
            $extension = strtolower($fileparts['extension']);
            if (!in_array($extension, array('doc', 'docx'))) {
                throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");
            }

            if (!file_exists($filename)) {
                throw new Exception("Файл олдсонгүй!"); 
            }
            
            $contentName = getUID();
            $wordFilePath = self::bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
            $wordFilePath .= 'file_'.$contentName.'.docx';

            $saveCache = self::replaceWordTags($getContent['physicalpath'], $wordFilePath, $selectedRow['id'], $getProcessCommand['COMMAND_NAME']);

            if ($saveCache['status'] === 'success') {
                $replacedWordTemplate = isset($saveCache['result']['replacewordtags']) ? base64_decode($saveCache['result']['replacewordtags']) : base64_decode($saveCache['result']['result']);
            }
            else {
                throw new Exception($saveCache['text']);
            }
            
            if (file_exists($wordFilePath)) {

                $param = array(
                    'systemMetaGroupId' => '1494555819962926',
                    'ignorePermission' => 1,
                    'criteria' => array(
                        'bookid' => array(
                            array(
                                'operator' => '=',
                                'operand' => $selectedRow['id']
                            )
                        )
                    )
                );
                $getHavsralt = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                $contentData = array(
                    'CREATED_USER_ID' => Ue::sessionUserKeyId()
                );

                $filesjoinStr .= URL . $wordFilePath;

                $inparams = array(
                    'parentid' => $selectedRow['id']
                );  
                $getChild = $this->ws->runResponse(GF_SERVICE_ADDRESS, "NTR_GET_CHILD_PHPATH_LIST_004", $inparams);

                if ($getChild['status'] == 'success' && $getChild['result']) {
                    foreach($getChild['result']['ntr_get_child_phpath_list'] as $childRow) {

                        if (!empty($childRow['physicalpath'])) {
                            $filesjoinStr .= '$$' . URL . $childRow['physicalpath'];
                        }
                    }
                }                   

                if ($getHavsralt['status'] === 'success') {
                    
                    unset($getHavsralt['result']['aggregatecolumns']);
                    unset($getHavsralt['result']['paging']);

                    if ($getHavsralt['result']) {
                        $scanLink = Config::getFromCache('ubegScanLink');

                        foreach($getHavsralt['result'] as $rowfilename) {

                            if (!empty($rowfilename['physicalpath'])) {
                                $filesjoinStr .= '$$' . $scanLink . '?scan_id=' . $selectedRow['id'] . '&filename=' . $rowfilename['filename'];
                            }
                        }                    
                    }
                }

                $requestParam = array(
                    'files' => $filesjoinStr
                );
                $respost = $this->ws->redirectPost('https://iis101.veritech.mn/document/converter.aspx', $requestParam);                            
                
                $wordFilePath = self::bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
                $wordFilePath .= 'file_'.$contentName.'.pdf';
            
                if (file_put_contents($wordFilePath, base64_decode($respost))) {
                    $contentData['FILE_SIZE'] = filesize($wordFilePath);
                    $contentData['FILE_NAME'] = 'file_'.$contentName.'.pdf';
                    $contentData['FILE_EXTENSION'] = 'pdf';
                    $contentData['PHYSICAL_PATH'] = $wordFilePath;
                }                

                $this->db->AutoExecute('ECM_CONTENT', $contentData, 'UPDATE', 'CONTENT_ID = '.$selectedRow['contentid']);

                $data = array(
                    'WFM_STATUS_ID' => '1493712436457277'
                );
                $this->db->AutoExecute('NTR_SERVICE_BOOK', $data, 'UPDATE', 'ID = '.$selectedRow['id']);                

                return array(
                    'status' => 'success', 
                    'filepath' => $wordFilePath
                );
            } else {
                throw new Exception("Replace Файл олдсонгүй!"); 
            }

        } catch (Exception $e) {
            return array('status' => 'warning', 'message' => $e->getMessage());
        }

        return array('status' => 'error', 'message' => 'Error');
    }

    public function getEcmContentModel($templateId) {
        try {
            $row = $this->db->GetRow("
                SELECT 
                    EC.CONTENT_ID,
                    EC.FILE_NAME,
                    EC.PHYSICAL_PATH,
                    EC.THUMB_PHYSICAL_PATH,
                    EC.FILE_SIZE,
                    EC.FILE_EXTENSION
                FROM META_BUSINESS_PROCESS_TEMPLATE BT
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = BT.CONTENT_ID
                WHERE BT.ID = ".$this->db->Param(0), 
                array($templateId)
            );

            if ($row) {
                return $row;
            }
            
        } catch (Exception $ex) {}
        
        return false;
    }   

    public function getRunProcessCommandModel($mainId, $processId) {
        $row = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_CODE AS COMMAND_NAME,  
                    TP.GET_META_DATA_ID
                FROM META_DM_TRANSFER_PROCESS TP 
                    INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = TP.GET_META_DATA_ID 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID
                WHERE TP.MAIN_META_DATA_ID = $mainId AND TP.PROCESS_META_DATA_ID = $processId");

        if ($row) {
            return $row;
        }
        return false;
    } 

    public function checkFilePathEcmContentModel($filePath) {
        $row = $this->db->GetRow("
            SELECT 
                EC.CONTENT_ID,
                EC.FILE_NAME,
                EC.PHYSICAL_PATH
            FROM ECM_CONTENT EC
            WHERE UPPER(EC.PHYSICAL_PATH) = UPPER('".$filePath."')");

        if ($row) {
            return false;
        }
        return true;
    } 

    public function getEcmContentByIdModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                EC.CONTENT_ID,
                EC.FILE_NAME,
                EC.PHYSICAL_PATH,
                EC.FILE_EXTENSION
            FROM ECM_CONTENT EC
            WHERE EC.CONTENT_ID = $id");

        if ($row) {
            return $row;
        }
        return false;
    } 

    public function getBusinessProcessTemplateByIdModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                BP.ID,
                BP.TEMPLATE_NAME,
                BP.TEMPLATE_CODE,
                BP.CONTROL_DESIGN,
                BP.HTML_FILE_PATH,
                BP.META_DATA_ID,
                BP.ITEM_ID
            FROM META_BUSINESS_PROCESS_TEMPLATE BP
            WHERE BP.ID = ".$this->db->Param(0), array($id));

        if ($row) {
            return $row;
        }
        return false;
    } 

    public function getBusinessProcessTemplateByIdWithMetaModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                BP.ID,
                BP.TEMPLATE_NAME,
                BP.TEMPLATE_CODE,
                BP.CONTROL_DESIGN,
                BP.META_DATA_ID,
                MD.META_DATA_CODE,
                MD.META_DATA_NAME,
                BP.ITEM_ID
            FROM META_BUSINESS_PROCESS_TEMPLATE BP
            LEFT JOIN META_DATA MD ON MD.META_DATA_ID = BP.META_DATA_ID
            WHERE BP.ID = $id");

        if ($row) {
            return $row;
        }
        return false;
    } 

    public function getBusinessProcessTemplateByIdWithItemModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                BP.ID,
                BP.TEMPLATE_NAME,
                BP.TEMPLATE_CODE,
                BP.CONTROL_DESIGN,
                BP.ITEM_ID,
                II.ITEM_CODE,
                II.ITEM_NAME,
                T0.PHYSICAL_PATH,
                BP.HTML_FILE_PATH 
            FROM META_BUSINESS_PROCESS_TEMPLATE BP
            LEFT JOIN ECM_CONTENT T0 ON BP.CONTENT_ID = T0.CONTENT_ID
            LEFT JOIN IM_ITEM II ON II.ITEM_ID = BP.ITEM_ID
            WHERE BP.ID = $id");

        if ($row) {
            return $row;
        }
        return false;
    } 

    public function generateQrCode($data) {
        
        require_once BASEPATH.'libs/QRCode/qrlib.php';

        $filePath = self::bpTemplateUploadGetPath(UPLOADPATH . 'temp/', false);
        $filename = $filePath .'test'.md5($data.'|L|4').'.png';
        
        $string = str_split(numToAlpha($data), 4);
        $data = implode('-', $string) . rand(10, 99);
        QRcode::png($data, $filename, "L", 4, 2, false, $data);
        
        $imageData = base64_encode(file_get_contents($filename));
        return array('image' => $imageData, 'string' => $data);
        
    }

    public function QRCodeSaveModel() {
        $id = Input::post('id');
        $qrCode = self::generateQrCode($id);
        
        if (Input::postCheck('confirmType') && !Input::isEmpty('confirmType') && Input::post('confirmType') === '2') {
            $result = $this->db->UpdateClob('CON_CONTRACT', 'QR_CODE', issetParam($qrCode['image']), 'CONTRACT_ID = ' . $id);
        } else {
            $result = $this->db->UpdateClob('NTR_SERVICE_BOOK', 'QR_CODE', issetParam($qrCode['image']), 'ID = ' . $id);
            $result = $this->db->AutoExecute('NTR_SERVICE_BOOK', array('QR_NUMBER' => issetParam($qrCode['string'])) , 'UPDATE', 'ID = '.$id);
        }

        if (!$result) {
            return array('status' => 'error', 'message' => 'QR Code үүсгэхэд алдаа гарлаа.');
        }
        
        return array('status' => 'status', 'message' => 'Success', '$qrCodeString' => issetParam($qrCode['image']));
    }

    public function configProcessWordTemplateSaveModel() {

        $templateId = Input::post('templateId');
        $processMetaDataId = Input::post('processId');

        $data = array(
            'META_DATA_ID' => $processMetaDataId
        );
        $result = $this->db->AutoExecute('META_BUSINESS_PROCESS_TEMPLATE', $data, 'UPDATE', 'ID = '.$templateId);

        if ($result) {
            return array('status' => 'success', 'message' => 'Success');
        }
        return array('status' => 'error', 'message' => 'Error');
    }

    public function getAllDataByPostData() {
        $postData = array(
                            '0' => 'departmentName',
                            '1' => 'fullName',
                            '2' => 'bookNumber',
                            '3' => 'year',
                            '4' => 'month',
                            '5' => 'day',
                            '6' => 'NTR_SERVICE_CUSTOMER_A_SEM_DV',
                            '7' => 'NTR_SERVICE_CUSTOMER_B_SEM_DV',
                            '8' => 'NTR_SERVICE_AUTHORITY_DV',
                            '9' => 'NTR_SERVICE_PROPERTY_SEM_DV',
                            '10' => 'validDuration',
                            '11' => 'validDuration',
                            '12' => 'privilegeTypeId',
                            '13' => 'feeAmount',
                            '14' => 'copiedNumber',
                            '15' => 'gaveNumber',
                            '16' => 'representee',
                            '17' => 'NTR_SERVICE_CUSTOMER_B_SEM_DV__1',
                            '18' => 'NTR_SERVICE_CUSTOMER_A_SEM_DV__1',
                            '19' => 'timeTypeId',
                        );

        foreach ($postData as $row) {

        }

//            'departmentName', 'fullName', 'bookNumber', 'year', 'month', 'day', 'NTR_SERVICE_CUSTOMER_A_SEM_DV', 'NTR_SERVICE_CUSTOMER_B_SEM_DV', 'NTR_SERVICE_AUTHORITY_DV', 'NTR_SERVICE_PROPERTY_SEM_DV', 'validDuration', 'validDuration', 'privilegeTypeId', 'feeAmount', 'copiedNumber', 'gaveNumber', 'representee', 'NTR_SERVICE_CUSTOMER_B_SEM_DV__1', 'NTR_SERVICE_CUSTOMER_B_SEM_DV__1', 'NTR_SERVICE_CUSTOMER_A_SEM_DV__1', 'timeTypeId';

        $params = Arr::implode_r(',', $postData, true);
        $data = $this->db->GetAll("SELECT 
                                        md.META_DATA_ID,
                                        md.META_DATA_CODE,
                                        md.META_DATA_NAME,
                                        md.META_TYPE_ID,
                                        mt.META_TYPE_CODE,
                                        mt.META_TYPE_NAME
                                    FROM meta_data md
                                    INNER JOIN (
                                        SELECT 
                                            MAX(META_DATA_ID) AS META_DATA_ID,
                                            META_DATA_CODE
                                        FROM META_DATA
                                        WHERE META_DATA_CODE IN ('departmentName', 'fullName', 'bookNumber', 'year', 'month', 'day', 'NTR_SERVICE_CUSTOMER_A_SEM_DV', 'NTR_SERVICE_CUSTOMER_B_SEM_DV', 'NTR_SERVICE_AUTHORITY_DV', 'NTR_SERVICE_PROPERTY_SEM_DV', 'validDuration', 'validDuration', 'privilegeTypeId', 'feeAmount', 'copiedNumber', 'gaveNumber', 'representee', 'NTR_SERVICE_CUSTOMER_B_SEM_DV__1', 'NTR_SERVICE_CUSTOMER_B_SEM_DV__1', 'NTR_SERVICE_CUSTOMER_A_SEM_DV__1', 'timeTypeId') AND IS_ACTIVE = 1
                                        GROUP BY META_DATA_CODE
                                    ) temp ON md.META_DATA_ID = temp.META_DATA_ID
                                    INNER JOIN META_TYPE mt ON md.META_TYPE_ID = mt.META_TYPE_ID");

        (Array) $tmpArr = $searchReplace = array();

        foreach ($data as $key => $row) {
            $html = self::renderProcessPreviewControl($row);
            array_push($tmpArr, $html);
            array_push($searchReplace, '#'. $row['META_DATA_CODE'] .'#');
        }

        $contentName = '1490807603521151.html';

        $layoutContent = file_get_contents(BASEPATH . 'storage/uploads/process_template/' . $contentName);
        $layoutRender = str_replace($searchReplace, $tmpArr, $layoutContent);
        return $layoutRender; 
    }

    public function renderProcessPreviewControl(array $param) {
        $this->load->model('mdwebservice', 'middleware/models/');

        (String) $typeCode = strtolower($param['META_TYPE_CODE']);
        (String) $controlName = strtolower($param['META_DATA_CODE']);

        (Array) $attrArray = array(
            'id' => $controlName,
            'name' => $controlName,
            'style' => 'width:150px;     display: inline-block;',
            'class' => 'form-control form-control-sm '.$typeCode.'Init',
            'data-field-name' => $param['META_DATA_CODE'],
            'data-metadataid' => $param['META_DATA_ID']
        );   

        if ($typeCode === 'metagroup') {
            $groupData = $this->db->GetAll("SELECT gc.LABEL_NAME FROM META_DATA MD 
                                INNER JOIN META_GROUP_CONFIG gc on MD.META_DATA_ID = gc.GROUP_META_DATA_ID 
                                WHERE MD.META_DATA_ID = '". $param['META_DATA_ID'] ."' AND gc.PARENT_ID IS NOT NULL AND gc.RECORD_TYPE IS NULL AND gc.COLUMN_NAME IS NOT NULL");

            $html = '<table class="table table-sm table-bordered table-hover">';
                $html .= '<thead>';
                    $html .= '<tr>';
                        $html .= '<th style="width:20px;"></th>';
                        foreach ($groupData as $group) {
                            $html .= '<th>'. Lang::line($group['LABEL_NAME']) .'</th>';
                        }
                    $html .= '</tr>';
                    $html .= '</thead>';
            $html .= '</table>';
            return $html;
        } elseif ($typeCode == 'boolean') {

            $attrArray['class'] = $typeCode.'Init';

            return Form::checkbox($attrArray);

        } elseif ($typeCode == 'description') {

            $attrArray['spellcheck'] = 'false'; 
            $attrArray['style'] = 'height: 20px;';

            $attrArray['placeholder'] = Lang::line($param['META_DATA_NAME']);

            return Form::textArea($attrArray);

        } elseif ($typeCode == 'description_auto') {

            $attrArray['spellcheck'] = 'false'; 
            $attrArray['style'] = 'height: 39px;overflow: hidden;';
            $attrArray['placeholder'] = Lang::line($param['META_DATA_NAME']);

            return Form::textArea($attrArray);

        } elseif ($typeCode == 'text_editor') {

            $attrArray['spellcheck'] = 'false'; 
            return Form::textArea($attrArray);

        } elseif ($typeCode == 'payroll_expression') {

            return '<div class="input-group">
                        '.Form::text($attrArray).'
                        <span class="input-group-btn"><button class="btn grey-cascade" type="button" onclick="payrollExpression(this);" title="Томъёо тохируулах"><i class="fa fa-laptop"></i></button></span> 
                    </div>';

        } elseif ($typeCode == 'date') {

            $dateInputClass = $attrArray['class'];

            $attrArray['placeholder'] = Lang::line($param['META_DATA_NAME']);

            return html_tag('div', array(
                    'class' => 'dateElement input-group',
                    'data-section-path' => $paramRealPath
                ), Form::text($attrArray) . '<span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>', true
            );

        } elseif ($typeCode == 'time') {

            $attrArray['placeholder'] = Lang::line($param['META_DATA_NAME']);

            return Form::text($attrArray);

        } elseif ($typeCode == 'password') {

            $attrArray = array_merge($attrArray, array('autocomplete' => 'false', 'onfocus' => "this.removeAttribute('readonly');"));

            return Form::password($attrArray);

        } elseif ($typeCode == 'encrypt') {
            $attrArray = array_merge($attrArray, array('autocomplete' => 'false', 'onfocus' => "this.removeAttribute('readonly');"));

            return Form::password($attrArray);

        } elseif ($typeCode == 'file') {

            return Form::file($attrArray);

        } elseif ($typeCode == 'coordinate' || $typeCode == 'route') {

            $attrArray['readonly'] = true;

            return html_tag('div', array('class' => 'input-group gmap-set-coordinate-control'), Form::text($attrArray) . '<span class="input-group-btn"><button onclick="setGMapCoordinate(this); return false;" class="btn mr0"><i class="fa fa-map-marker"></i></button></span>', true);

        } elseif ($typeCode == 'region') {

            $attrArray['readonly'] = true;

            return html_tag('div', array('class' => 'input-group gmap-set-region-control'), Form::text($attrArray) . '<span class="input-group-btn"><button onclick="setGoogleMapRegion(\''.$param['META_DATA_ID'].'\', this); return false;" class="btn mr0"><i class="fa fa-map-marker"></i></button></span>', true);

        } elseif ($typeCode == 'signature') {

            $signatureImage = '';

            return '<div class="bp-signature">
                        <button type="button" class="btn btn-sm purple-plum" onclick="bpSignatureWrite(this);"><i class="fa fa-pencil"></i> Гарын үсэг зурах</button>
                        <div class="bp-signature-image"'.$signatureImage.'></div>
                        '.Form::hidden($attrArray).'
                    </div>';

        } else {
            return Form::text($attrArray);
        }
    }

    public function getTaxonomyConfigModel($templateId) {
        $rows = $this->db->GetAll("
            SELECT TC.* 
            FROM NTR_TAXONOMY_CONFIG TC
            WHERE TC.TEMPLATE_ID = ".$this->db->Param(0), array($templateId));

        if ($rows) {
            return Arr::groupByArray($rows, 'PATH_AS');
        }
        return false;
    }

    public function getTaxonomyWidgetExpModel($templateId) {
        $rows = $this->db->GetAll("
            SELECT TW.* 
            FROM NTR_TAXONOMY_CONFIG TC
            INNER JOIN NTR_TAXONOMY_WIDGET TW ON TW.TAXONOMY_CONFIG_ID = TC.ID
            WHERE TC.TEMPLATE_ID = ".$this->db->Param(0), array($templateId));

        if ($rows) {
            return Arr::groupByArray($rows, 'TAXONOMY_CONFIG_ID');
        }
        return false;
    }
    
    public function getContentServiceBook($serviceBookId) {
        return $this->db->GetRow("SELECT 
                                        T0.ID, 
                                        T0.QR_CODE,
                                        T0.CONTENT_ID AS MAIN_CONTENTID,
                                        T2.DESCRIPTION AS GET_PROCESS_CODE,  
                                        T1.* 
                                    FROM NTR_SERVICE_BOOK T0
                                    INNER JOIN (
                                        SELECT 
                                            EC.CONTENT_ID,
                                            EC.FILE_NAME,
                                            EC.PHYSICAL_PATH,
                                            EC.THUMB_PHYSICAL_PATH,
                                            EC.FILE_SIZE,
                                            EC.FILE_EXTENSION,
                                            BT.ID
                                        FROM META_BUSINESS_PROCESS_TEMPLATE BT
                                        INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = BT.CONTENT_ID
                                    ) T1 ON T0.TEMPLATE_ID = T1.ID
                                    LEFT JOIN IM_ITEM T2 ON T0.ITEM_ID = T2.ITEM_ID
                                    WHERE T0.ID = $serviceBookId");
    }
    
    public function getContentContract($serviceBookId) {
        return $this->db->GetRow("SELECT 
                                        T0.CONTRACT_ID AS ID, 
                                        T0.QR_CODE,
                                        T0.CONTENT_ID AS MAIN_CONTENTID,
                                        'fitGetContract_004' AS GET_PROCESS_CODE,  
                                        T1.*  
                                    FROM CON_CONTRACT t0 
                                    INNER JOIN (
                                        SELECT 
                                            EC.CONTENT_ID,
                                            EC.FILE_NAME,
                                            EC.PHYSICAL_PATH,
                                            EC.THUMB_PHYSICAL_PATH,
                                            EC.FILE_SIZE,
                                            EC.FILE_EXTENSION,
                                            BT.ID
                                        FROM META_BUSINESS_PROCESS_TEMPLATE BT
                                        INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = BT.CONTENT_ID
                                    ) T1 ON T0.TEMPLATE_ID = T1.ID
                                    where t0.CONTRACT_ID = $serviceBookId");
    }
    
    public function confirmNtrServicePdfModel() {
        try {
            $serviceBookId = Input::post('id');
            $ticket = true;
            
            if (Input::postCheck('confirmType') && !Input::isEmpty('confirmType') && Input::post('confirmType') === '2') {
                $ticket = false;
                $getContent =  $this->getContentContract($serviceBookId);
            } else {
                $getContent =  $this->getContentServiceBook($serviceBookId);
            }
            
            if (!$getContent) {
                throw new Exception("Контентоос бичлэг олдсонгүй!"); 
            }
            
            if (!$getContent['GET_PROCESS_CODE']) {
                throw new Exception("Процесс команд тохируулаагүй байна!"); 
            }
            
            if (!isset($getContent['PHYSICAL_PATH']) || empty($getContent['PHYSICAL_PATH'])) {
                throw new Exception("Файл хоосон байна!"); 
            }

            $filename = trim($getContent['PHYSICAL_PATH']);
            $fileparts = pathinfo($filename);
            $extension = strtolower($fileparts['extension']);
            
            if (!in_array($extension, array('doc', 'docx')))
                throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");

            if(!file_exists($filename)) {
                throw new Exception("Файл олдсонгүй!"); 
            }

            $contentId = getUID();
            $contentName = getUID();
            $wordFilePath = self::bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
            $wordFilePath .= 'file_'.$contentName.'.docx';

            $saveCache = self::replaceWordTags($getContent['PHYSICAL_PATH'], $wordFilePath, $serviceBookId, $getContent['GET_PROCESS_CODE']);

            if ($saveCache['status'] === 'success') {
                $replacedWordTemplate = isset($saveCache['result']['replacewordtags']) ? base64_decode($saveCache['result']['replacewordtags']) : base64_decode($saveCache['result']['result']);
            }
            else {
                throw new Exception($saveCache['text']);
            }
        
            if (file_exists($wordFilePath)) {
                
                $contentData = array(
                    'CONTENT_ID' => $contentId, 
                    'FILE_NAME' => 'file_'.$contentId.'.docx',
                    'PHYSICAL_PATH' => $wordFilePath,
                    'FILE_SIZE' => filesize($wordFilePath),
                    'FILE_EXTENSION' => 'docx',
                    'CREATED_USER_ID' => Ue::sessionUserKeyId()
                );
                $this->db->AutoExecute('ECM_CONTENT', $contentData);

                $bookData = array(
                    'CONTENT_ID' => $contentId
                );
                
                if ($ticket) {
                    $this->db->AutoExecute('NTR_SERVICE_BOOK', $bookData, 'UPDATE', "ID = $serviceBookId");
                } else {
                    $this->db->AutoExecute('CON_CONTRACT', $bookData, 'UPDATE', "CONTRACT_ID = $serviceBookId");
                }
                
                $filename = trim($wordFilePath);
                $fileparts = pathinfo($filename);

                $extension = strtolower($fileparts['extension']);
                if (!in_array($extension, array('doc', 'docx'))) {
                    throw new Exception("Файлын төрөл буруу байна! <br><strong>Doc, Docx</strong> файл оруулна");
                }

                if (!file_exists($filename)) {
                    throw new Exception("Файл олдсонгүй!"); 
                }

                if ($ticket) {

                    $contentName = getUID();
                    $wordMainFilePath = self::bpTemplateUploadGetPath(UPLOADPATH . 'signed_content/');
                    $wordMainFilePath .= 'file_'.$contentName.'.docx';

                    $saveCache = self::replaceWordTags($wordFilePath, $wordMainFilePath, $serviceBookId, 'ntrGetTagSignatureList_004');

                    if ($saveCache['status'] === 'success') {
                        $replacedWordTemplate = isset($saveCache['result']['replacewordtags']) ? base64_decode($saveCache['result']['replacewordtags']) : base64_decode($saveCache['result']['result']);
                    }
                    else {
                        throw new Exception($saveCache['text']);
                    }

                    if (file_exists($wordMainFilePath)) {
                        $contentData = array(
                            'FILE_NAME' => 'file_'.$contentName.'.docx',
                            'PHYSICAL_PATH' => $wordMainFilePath,
                            'FILE_SIZE' => filesize($wordMainFilePath),
                            'FILE_EXTENSION' => 'docx',
                            'CREATED_USER_ID' => Ue::sessionUserKeyId()
                        );

                        $result = $this->db->AutoExecute('ECM_CONTENT', $contentData, 'UPDATE', "CONTENT_ID = $contentId");
                        if ($result) {
                            $path_ = self::bpTemplateUploadGetPath(UPLOADPATH . 'ntr_content/');
                            $newPath = $path_ . 'doc_' . getUID() . '.docx';
                            @copy($wordMainFilePath, $newPath);
                            
                            $this->db->AutoExecute('NTR_SERVICE_BOOK', array('WORD_PATH' => $newPath), "UPDATE", "ID = '". $serviceBookId ."'");
                        }
                        return array(
                            'status' => 'success', 
                            'filepath' => $wordMainFilePath,
                            'recordId' => $serviceBookId,
                            'qrcode' => $getContent['QR_CODE']
                        );
                    } else {
                        throw new Exception("Replace_next Файл олдсонгүй!"); 
                    }
                } else {
                     return array(
                            'status' => 'success', 
                            'filepath' => $wordFilePath,
                            'recordId' => $serviceBookId,
                            'qrcode' => $getContent['QR_CODE']
                        );
                }
            } else {
                throw new Exception("Replace Файл олдсонгүй!"); 
            }

        } catch (ADODB_Exception $ex) {
            return array('status' => 'warning', 'message' => 'Error - confirmNtrServicePdfModel');
        } catch (Exception $e) {
            return array('status' => 'warning', 'message' => $e->getMessage());
        }

        return array('status' => 'error', 'message' => 'Error');
    }
    
    public function getServiceBookContentModel($serviceBookId) {
        return  $this->db->GetAll("
            SELECT
                T0.ID,
                T1.DESCRIPTION,
                T1.FILE_EXTENSION,
                T1.FILE_NAME,
                T1.PHYSICAL_PATH,
                T1.FILE_SIZE,
                T0.BOOK_ID,
                T0.CONTENT_ID
            FROM NTR_SERVICE_CONTENT T0
                INNER JOIN ECM_CONTENT T1 ON T0.CONTENT_ID = T1.CONTENT_ID
            WHERE T0.BOOK_ID = $serviceBookId");
    }
    
    public function getErlContentMapModel($id, $typeId = '1') {
        if ($typeId == '1') {
            $data = $this->db->GetAll("
                SELECT 
                    EC.CONTENT_ID,
                    EC.FILE_NAME,
                    EC.PHYSICAL_PATH,
                    EC.FILE_EXTENSION,
                    CB.ID AS COMPANY_BOOK_ID,
                    DM.ID AS SEMANTIC_ID,
                    BT.BOOK_TYPE_NAME,
                    CB.BOOK_TYPE_ID,
                    CT.NAME AS CONTENT_TYPE_NAME,
                    EC.TYPE_ID AS CONTENT_TYPE_ID,
                    TO_CHAR(CB.BOOK_DATE, 'YYYY-MM-DD') AS BOOK_DATE,
                    CM.ORDER_NUM,
                    CM.ID
                FROM ECM_CONTENT_MAP CM 
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                    LEFT JOIN META_DM_RECORD_MAP DM ON EC.CONTENT_ID = DM.TRG_RECORD_ID 
                    LEFT JOIN CMP_COMPANY_BOOK CB ON CB.ID = DM.SRC_RECORD_ID 
                    LEFT JOIN BOOK_TYPE BT ON BT.BOOK_TYPE_ID = CB.BOOK_TYPE_ID 
                    LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                WHERE CM.RECORD_ID = $id  
                    AND CM.REF_STRUCTURE_ID = ".Mddoc::$erlStructureId." 
                    AND EC.IS_VERSION IS NULL
                ORDER BY CM.ORDER_NUM ASC");

            return $data;     
        } else {
            $data = $this->db->GetAll("
                SELECT
                    EC.CONTENT_ID,
                    EC.FILE_NAME,
                    EC.PHYSICAL_PATH,
                    EC.FILE_EXTENSION,
                    CB.ID AS COMPANY_BOOK_ID,
                    DM.ID AS SEMANTIC_ID,
                    BT.BOOK_TYPE_NAME,
                    CB.BOOK_TYPE_ID,
                    CT.NAME AS CONTENT_TYPE_NAME,
                    EC.TYPE_ID AS CONTENT_TYPE_ID,
                    TO_CHAR(CB.BOOK_DATE, 'YYYY-MM-DD') AS BOOK_DATE,
                    CM.ORDER_NUM,
                    CM.ID 
                FROM ECM_CONTENT_MAP CM 
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                    LEFT JOIN META_DM_RECORD_MAP DM ON EC.CONTENT_ID = DM.TRG_RECORD_ID 
                    LEFT JOIN CVL_CIVIL_BOOK CB ON CB.ID = DM.SRC_RECORD_ID 
                    LEFT JOIN BOOK_TYPE BT ON BT.BOOK_TYPE_ID = CB.BOOK_TYPE_ID 
                    LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                WHERE CM.RECORD_ID = $id  
                    AND CM.REF_STRUCTURE_ID = ". Mddoc::$erlStructureIdCivil. "
                    AND EC.IS_VERSION IS NULL
                ORDER BY CM.ORDER_NUM ASC");

            return $data;
        }
    }
    
    public function getErlContentCountModel($id, $structureId = null) {
        $count = $this->db->GetOne("
            SELECT 
                COUNT(*) AS PAGE_COUNT
            FROM ECM_CONTENT_MAP CM 
                INNER JOIN ECM_CONTENT EC ON CM.CONTENT_ID = EC.CONTENT_ID
            WHERE CM.RECORD_ID = $id  
                AND CM.REF_STRUCTURE_ID = ". ($structureId ?  $structureId : Mddoc::$erlStructureId)." 
                AND EC.IS_VERSION IS NULL
            GROUP BY CM.RECORD_ID");
        
        return $count;
    }
    
    public function getErkContentMapModel_HRM($id) {
        
        $xmlAgg = "'<tr data-filepath=\"'||t0.PHYSICAL_PATH||'\" data-hdr-id=\"'||$id||'\" data-book-type-append=\"0\" data-content-type-append=\"0\">'
                    ||'<td style=\"width: 10px;vertical-align: middle;\">'||t0.ROW_NUMB||'.</td>'
                    ||'<td style=\"width: 120px;vertical-align: middle\">
                        <strong>'||t0.FILE_NAME||'</strong>
                        <input type=\"hidden\" name=\"erlContentId[]\" value=\"'||t0.CONTENT_ID||'\"/>
                        <input type=\"hidden\" name=\"erlCompanyBookId[]\" value=\"'||t0.COMPANY_BOOK_ID||'\"/>
                        <input type=\"hidden\" name=\"erlSemanticId[]\" value=\"'||t0.SEMANTIC_ID||'\"/>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:115px\">
                        <input type=\"text\" name=\"bookDate[]\" class=\"form-control form-control-sm erl-bookdate\" data-path=\"\" required=\"required\" value=\"'||t0.BOOK_DATE||'\" data-value=\"'||t0.BOOK_DATE||'\">
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                        <select name=\"bookTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"bookTypeId\" required=\"required\" style=\"width: 250px\" data-oldval=\"'||t0.BOOK_TYPE_ID||'\">
                            '||CASE WHEN t0.BOOK_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.BOOK_TYPE_ID||'\">'||t0.BOOK_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                        <select name=\"contentTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"contentTypeId\" required=\"required\" style=\"width: 220px\" data-oldval=\"'||t0.CONTENT_TYPE_ID||'\">
                            '||CASE WHEN t0.CONTENT_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.CONTENT_TYPE_ID||'\">'||t0.CONTENT_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>"
                ."</tr>'";
        
        $sql = "
            SELECT 
                XMLAGG(XMLELEMENT(e, '<tr data-filepath=\"'||t0.PHYSICAL_PATH||'\" data-hdr-id=\"'||$id||'\" data-book-type-append=\"0\" data-content-type-append=\"0\">'
                    ||'<td style=\"width: 10px;vertical-align: middle;\">'||t0.ROW_NUMB||'.</td>'
                    ||'<td style=\"width: 120px;vertical-align: middle\">
                        <strong>'||t0.FILE_NAME||'</strong>
                        <input type=\"hidden\" name=\"erlContentId[]\" value=\"'||t0.CONTENT_ID||'\"/>
                        <input type=\"hidden\" name=\"erlCompanyBookId[]\" value=\"'||t0.COMPANY_BOOK_ID||'\"/>
                        <input type=\"hidden\" name=\"erlSemanticId[]\" value=\"'||t0.SEMANTIC_ID||'\"/>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:115px\">
                        <input type=\"text\" name=\"bookDate[]\" class=\"form-control form-control-sm erl-bookdate\" data-path=\"\" required=\"required\" value=\"'||t0.BOOK_DATE||'\" data-value=\"'||t0.BOOK_DATE||'\">
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                        <select name=\"contentTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"contentTypeId\" required=\"required\" style=\"width: 100%\" data-oldval=\"'||t0.CONTENT_TYPE_ID||'\">
                            '||CASE WHEN t0.CONTENT_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.CONTENT_TYPE_ID||'\">'||t0.CONTENT_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>"
                ."</tr>',' ').EXTRACT('//text()')).GetClobVal() AS SS, 
                COUNT(*) AS ROW_COUNT     
            FROM (
                                SELECT
                    ROW_NUMBER() OVER (ORDER BY CM.ORDER_NUM) AS ROW_NUMB,
                    EC.CONTENT_ID,
                    EC.FILE_NAME,
                    EC.PHYSICAL_PATH,
                    EC.FILE_EXTENSION,
                    CB.ID AS COMPANY_BOOK_ID,
                    DM.ID AS SEMANTIC_ID,
                    BT.BOOK_TYPE_NAME,
                    BT.BOOK_TYPE_ID, 
                    CT.NAME AS CONTENT_TYPE_NAME,
                    EC.TYPE_ID AS CONTENT_TYPE_ID,
                    COALESCE(TO_CHAR(CB.BOOK_DATE, 'YYYY-MM-DD'), TO_CHAR(SYSDATE, 'YYYY-MM-DD')) AS BOOK_DATE, 
                    CM.ORDER_NUM, 
                    CM.ID
                FROM ECM_CONTENT_MAP CM 
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                    LEFT JOIN META_DM_RECORD_MAP DM ON EC.CONTENT_ID = DM.TRG_RECORD_ID 
                    LEFT JOIN ELEC_META_BOOK CB ON CB.ID = DM.SRC_RECORD_ID 
                    LEFT JOIN BOOK_TYPE BT ON BT.BOOK_TYPE_ID = CB.BOOK_TYPE_ID
                    LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                WHERE CM.RECORD_ID = $id 
                    AND EC.IS_VERSION IS NULL 
                ORDER BY CM.ORDER_NUM ASC
            ) t0";
        
        return $this->db->GetRow($sql);
    }

    public function getErkContentMapModel_NOTARY($id, $type = '') {
        
        $sql = "
            SELECT 
                XMLAGG(XMLELEMENT(e, '<tr data-filepath=\"'||t0.PHYSICAL_PATH||'\" data-hdr-id=\"'||$id||'\" data-book-type-append=\"0\" data-content-type-append=\"0\">'
                    ||'<td style=\"width: 23px;vertical-align: middle;\">'||t0.ROW_NUMB||'.</td>'
                    ||'<td style=\"width: 87px;vertical-align: middle\">
                        <strong>'||t0.FILE_NAME||'</strong>
                        <input type=\"hidden\" name=\"erlContentId[]\" value=\"'||t0.CONTENT_ID||'\"/>
                        <input type=\"hidden\" name=\"isnotary\" value=\"1\"/>
                        <input type=\"hidden\" name=\"erlCompanyBookId[]\" value=\"'||t0.COMPANY_BOOK_ID||'\"/>
                        <input type=\"hidden\" name=\"erlSemanticId[]\" value=\"'||t0.SEMANTIC_ID||'\"/>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:200px\">
                        <select name=\"contentTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"contentTypeId\" required=\"required\" style=\"width: 100%\" data-oldval=\"'||t0.CONTENT_TYPE_ID||'\">
                            '||CASE WHEN t0.CONTENT_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.CONTENT_TYPE_ID||'\">'||t0.CONTENT_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>"
                ."</tr>',' ').EXTRACT('//text()')).GetClobVal() AS SS, 
                COUNT(*) AS ROW_COUNT     
            FROM (
                SELECT
                    ROW_NUMBER() OVER (ORDER BY CM.ORDER_NUM) AS ROW_NUMB,
                    EC.CONTENT_ID,
                    EC.FILE_NAME,
                    EC.PHYSICAL_PATH,
                    EC.FILE_EXTENSION,
                    NULL AS COMPANY_BOOK_ID,
                    CM.ID AS SEMANTIC_ID,
                    BT.ITEM_NAME AS BOOK_TYPE_NAME,
                    BT.ITEM_ID AS BOOK_TYPE_ID, 
                    CT.NAME AS CONTENT_TYPE_NAME,
                    EC.TYPE_ID AS CONTENT_TYPE_ID,
                    COALESCE(TO_CHAR(CM.CREATED_DATE, 'YYYY-MM-DD'), TO_CHAR(SYSDATE, 'YYYY-MM-DD')) AS BOOK_DATE, 
                    CM.ORDER_NUM, 
                    CM.ID
                FROM ECM_CONTENT_MAP CM 
                INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                LEFT JOIN NTR_SERVICE_BOOK SB ON CM.RECORD_ID = SB.ID 
                LEFT JOIN IM_ITEM BT ON BT.ITEM_ID = SB.ITEM_ID
                LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                WHERE 
                    CM.RECORD_ID = $id
                    AND CM.REF_STRUCTURE_ID = ".Mddoc::$erlStructureId." 
                    AND EC.IS_VERSION IS NULL 
                ORDER BY CM.ORDER_NUM ASC
            ) t0";
        
        
        return $this->db->GetRow($sql);
    }

    public function getErkContentMapModel_V2($id) {
        
        $xmlAgg = "'<tr data-filepath=\"'||t0.PHYSICAL_PATH||'\" data-hdr-id=\"'||$id||'\" data-book-type-append=\"0\" data-content-type-append=\"0\">'
                    ||'<td style=\"width: 10px;vertical-align: middle;\">'||t0.ROW_NUMB||'.</td>'
                    ||'<td style=\"width: 120px;vertical-align: middle\">
                        <strong>'||t0.FILE_NAME||'</strong>
                        <input type=\"hidden\" name=\"erlContentId[]\" value=\"'||t0.CONTENT_ID||'\"/>
                        <input type=\"hidden\" name=\"erlCompanyBookId[]\" value=\"'||t0.COMPANY_BOOK_ID||'\"/>
                        <input type=\"hidden\" name=\"erlSemanticId[]\" value=\"'||t0.SEMANTIC_ID||'\"/>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:115px\">
                        <input type=\"text\" name=\"bookDate[]\" class=\"form-control form-control-sm erl-bookdate\" data-path=\"\" required=\"required\" value=\"'||t0.BOOK_DATE||'\" data-value=\"'||t0.BOOK_DATE||'\">
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                        <select name=\"bookTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"bookTypeId\" required=\"required\" style=\"width: 250px\" data-oldval=\"'||t0.BOOK_TYPE_ID||'\">
                            '||CASE WHEN t0.BOOK_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.BOOK_TYPE_ID||'\">'||t0.BOOK_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                        <select name=\"contentTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"contentTypeId\" required=\"required\" style=\"width: 220px\" data-oldval=\"'||t0.CONTENT_TYPE_ID||'\">
                            '||CASE WHEN t0.CONTENT_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.CONTENT_TYPE_ID||'\">'||t0.CONTENT_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>"
                ."</tr>'";
        
        $sql = "
            SELECT 
                XMLAGG(XMLELEMENT(e, '<tr data-filepath=\"'||t0.PHYSICAL_PATH||'\" data-hdr-id=\"'||$id||'\" data-book-type-append=\"0\" data-content-type-append=\"0\">'
                    ||'<td style=\"width: 10px;vertical-align: middle;\">'||t0.ROW_NUMB||'.</td>'
                    ||'<td style=\"width: 120px;vertical-align: middle\">
                        <strong>'||t0.FILE_NAME||'</strong>
                        <input type=\"hidden\" name=\"erlContentId[]\" value=\"'||t0.CONTENT_ID||'\"/>
                        <input type=\"hidden\" name=\"erlCompanyBookId[]\" value=\"'||t0.COMPANY_BOOK_ID||'\"/>
                        <input type=\"hidden\" name=\"erlSemanticId[]\" value=\"'||t0.SEMANTIC_ID||'\"/>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:115px\">
                        <input type=\"text\" name=\"bookDate[]\" class=\"form-control form-control-sm erl-bookdate\" data-path=\"\" required=\"required\" value=\"'||t0.BOOK_DATE||'\" data-value=\"'||t0.BOOK_DATE||'\">
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                        <select name=\"bookTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"bookTypeId\" required=\"required\" style=\"width: 250px\" data-oldval=\"'||t0.BOOK_TYPE_ID||'\">
                            '||CASE WHEN t0.BOOK_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.BOOK_TYPE_ID||'\">'||t0.BOOK_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                        <select name=\"contentTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"contentTypeId\" required=\"required\" style=\"width: 220px\" data-oldval=\"'||t0.CONTENT_TYPE_ID||'\">
                            '||CASE WHEN t0.CONTENT_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.CONTENT_TYPE_ID||'\">'||t0.CONTENT_TYPE_NAME||'</option>' END||'
                        </select>
                    </td>"
                ."</tr>',' ').EXTRACT('//text()')).GetClobVal() AS SS, 
                COUNT(*) AS ROW_COUNT     
            FROM (
                SELECT
                    ROW_NUMBER() OVER (ORDER BY CM.ORDER_NUM) AS ROW_NUMB,
                    EC.CONTENT_ID,
                    EC.FILE_NAME,
                    EC.PHYSICAL_PATH,
                    EC.FILE_EXTENSION,
                    CB.ID AS COMPANY_BOOK_ID,
                    DM.ID AS SEMANTIC_ID,
                    (CASE WHEN BT.BOOK_TYPE_ID = 70034 THEN 'Шинэ бүртгэл' ELSE BT.BOOK_TYPE_NAME END) AS BOOK_TYPE_NAME,
                    (CASE WHEN BT.BOOK_TYPE_ID = 70034 THEN 1530619218209 ELSE BT.BOOK_TYPE_ID END) AS BOOK_TYPE_ID, 
                    CT.NAME AS CONTENT_TYPE_NAME,
                    EC.TYPE_ID AS CONTENT_TYPE_ID,
                    COALESCE(TO_CHAR(CB.BOOK_DATE, 'YYYY-MM-DD'), TO_CHAR(SYSDATE, 'YYYY-MM-DD')) AS BOOK_DATE, 
                    CM.ORDER_NUM, 
                    CM.ID
                FROM ECM_CONTENT_MAP CM 
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                    LEFT JOIN META_DM_RECORD_MAP DM ON EC.CONTENT_ID = DM.TRG_RECORD_ID 
                    LEFT JOIN CMP_COMPANY_BOOK CB ON CB.ID = DM.SRC_RECORD_ID 
                    LEFT JOIN CMP_SERVICE_BOOK SB ON CM.RECORD_ID = SB.ID 
                    LEFT JOIN BOOK_TYPE BT ON BT.BOOK_TYPE_ID = COALESCE(CB.BOOK_TYPE_ID, SB.BOOK_TYPE_ID) 
                    LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                WHERE CM.RECORD_ID = $id 
                    AND CM.REF_STRUCTURE_ID = ".Mddoc::$erlStructureId." 
                    AND EC.IS_VERSION IS NULL 
                ORDER BY CM.ORDER_NUM ASC
            ) t0";
        
        return $this->db->GetRow($sql);
    }
    
    public function getErkContentMapModel_V3($id, $refStructureId = '') {
        
        $structureId = ($refStructureId == '') ? Mddoc::$erlStructureIdCivil : $refStructureId;
        
        $xmlAgg = "'<tr tr-index=\"'||t0.ROW_NUMB||'\" class=\"cvlTable\" data-filepath=\"'||t0.PHYSICAL_PATH||'\"  data-hdr-id=\"'||$id||'\" call-process-get=\"0\" data-book-type-append=\"0\" data-content-type-append=\"0\">'
                    ||'<td style=\"width: 20px;vertical-align: middle;\">'||t0.ROW_NUMB||'.</td>'
                    ||'<td style=\"width: 95px;vertical-align: middle;\">'
                        ||'<strong>'||t0.FILE_NAME||'</strong>'
                        ||'<input type=\"hidden\" name=\"orderNumber[]\" data-path=\"orderNumber\" value=\"'||t0.ROW_NUMB||'\" />'
                        ||'<input type=\"hidden\" name=\"cvlContentId[]\" data-path=\"cvlContentId\" value=\"'||t0.CONTENT_ID||'\" />'
                        ||'<input type=\"hidden\" name=\"cvlBookId[]\" data-path=\"cvlBookId\" value=\"'||t0.CVL_BOOK_ID||'\" />'
                        ||'<input type=\"hidden\" name=\"cvlSemanticId[]\" data-path=\"cvlSemanticId\" value=\"'||t0.SEMANTIC_ID||'\" />'
                        ||'<input type=\"hidden\" name=\"cvlBookDate[]\" data-path=\"cvlBookdate\" value=\"'||t0.BOOK_DATE||'\" />'
                    ||'</td>'
                    ||'<td class=\"stretchInput text-center\" style=\"width:220px\">'
                        ||'<select name=\"cvlBookTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm select2\" data-status=\"no-append\" data-path=\"cvlBookType\" required=\"required\" data-oldval=\"'||t0.BOOK_TYPE_ID||'\" style=\"width: 100%\">'
                            ||CASE WHEN t0.BOOK_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.BOOK_TYPE_ID||'\" >'||t0.BOOK_TYPE_NAME||'</option>' END||
                        '</select>'
                    ||'</td>"
                ."</tr>'";
        
        $sql = "SELECT 
                    XMLAGG(XMLELEMENT(e, $xmlAgg,' ').EXTRACT('//text()')).GetClobVal() AS SS, 
                    COUNT(*) AS ROW_COUNT        
                FROM (
                    SELECT
                        ROW_NUMBER() OVER (ORDER BY CM.ORDER_NUM) AS ROW_NUMB,
                        EC.CONTENT_ID,
                        EC.FILE_NAME,
                        EC.PHYSICAL_PATH,
                        EC.FILE_EXTENSION,
                        DM.ID AS SEMANTIC_ID,
                        BT.BOOK_TYPE_NAME,
                        CT.NAME AS CONTENT_TYPE_NAME,
                        EC.TYPE_ID AS CONTENT_TYPE_ID,
                        CB.ID AS CVL_BOOK_ID,
                        CB.BOOK_TYPE_ID,
                        CB.BOOK_DATE,
                        CB.DISPLAY_DATE,
                        CM.ORDER_NUM,
                        CM.ID
                    FROM ECM_CONTENT_MAP CM 
                        INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                        LEFT JOIN META_DM_RECORD_MAP DM ON EC.CONTENT_ID = DM.TRG_RECORD_ID 
                        LEFT JOIN (
                            SELECT 
                                TO_CHAR(T0.BOOK_DATE, 'YYYY-MM-DD') AS BOOK_DATE, 
                                T0.BOOK_TYPE_ID, 
                                T0.ID, 
                                TO_CHAR(T0.BOOK_DATE, 'YYYY-MM-DD') AS DISPLAY_DATE
                            FROM CVL_CIVIL_BOOK T0
                        ) CB ON CB.ID = DM.SRC_RECORD_ID 
                        LEFT JOIN BOOK_TYPE BT ON BT.BOOK_TYPE_ID = CB.BOOK_TYPE_ID 
                        LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                    WHERE CM.RECORD_ID = $id 
                        AND CM.REF_STRUCTURE_ID = ". $structureId ."
                        AND EC.IS_VERSION IS NULL
                    ORDER BY CM.ORDER_NUM ASC
                ) t0 --WHERE ROW_NUMB < 10";
        
        return $this->db->GetRow($sql);
        
    }
    
    public function getErkContentMapModel_V5($id = '1540457535436', $refStructureId = '', $keyid = '0') {
        $companyKeyId = ($keyid) ? $keyid : '0';
        $xmlAgg = "'<tr data-filepath=\"'||t0.PHYSICAL_PATH||'\" data-hdr-id=\"'||$id||'\" data-book-type-append=\"0\" data-content-type-append=\"0\">'
                        ||'<td style=\"width: 10px;vertical-align: middle;\">'||t0.ROW_NUMB||'.</td>'
                        ||'<td style=\"width: 120px;vertical-align: middle\">
                            <strong>'||t0.FILE_NAME||'</strong>
                            <input type=\"hidden\" name=\"erlContentId[]\" value=\"'||t0.CONTENT_ID||'\"/>
                            <input type=\"hidden\" name=\"erlCompanyKeyId[]\" value=\"'||$companyKeyId||'\"/>
                            <input type=\"hidden\" name=\"erlCompanyBookId[]\" value=\"'||t0.COMPANY_BOOK_ID||'\"/>
                            <input type=\"hidden\" name=\"erlSemanticId[]\" value=\"'||t0.SEMANTIC_ID||'\"/>
                        </td>'
                        ||'<td class=\"stretchInput text-center\" style=\"width:115px\">
                            <input type=\"text\" name=\"bookDate[]\" class=\"form-control form-control-sm erl-bookdate\" data-path=\"\" required=\"required\" value=\"'||t0.BOOK_DATE||'\" data-value=\"'||t0.BOOK_DATE||'\">
                        </td>'
                        ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                            <select name=\"bookTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"bookTypeId\" required=\"required\" style=\"width: 250px\" data-oldval=\"'||t0.BOOK_TYPE_ID||'\">
                                '||CASE WHEN t0.BOOK_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.BOOK_TYPE_ID||'\">'||t0.BOOK_TYPE_NAME||'</option>' END||'
                            </select>
                        </td>'
                        ||'<td class=\"stretchInput text-center\" style=\"width:230px\">
                            <select name=\"contentTypeId[]\" onfocus=\"functionFocusSelect(this)\" class=\"form-control form-control-sm\" data-status=\"no-append\" data-path=\"contentTypeId\" required=\"required\" style=\"width: 220px\" data-oldval=\"'||t0.CONTENT_TYPE_ID||'\">
                                '||CASE WHEN t0.CONTENT_TYPE_ID IS NULL THEN '<option selected=\"selected\" value=\"\">- Сонгох -</option>' ELSE '<option selected=\"selected\" value=\"'||t0.CONTENT_TYPE_ID||'\">'||t0.CONTENT_TYPE_NAME||'</option>' END||'
                            </select>
                        </td>"
                    . "</tr>'";
        
        $sql = "SELECT 
                    XMLAGG(XMLELEMENT(e, $xmlAgg,' ').EXTRACT('//text()')).GetClobVal() AS SS, 
                    COUNT(*) AS ROW_COUNT        
                FROM (
                    SELECT
                        ROW_NUMBER() OVER (ORDER BY CM.ORDER_NUM) AS ROW_NUMB,
                        EC.CONTENT_ID,
                        EC.FILE_NAME,
                        EC.PHYSICAL_PATH,
                        EC.FILE_EXTENSION,
                        CB.ID AS COMPANY_BOOK_ID,
                        DM.ID AS SEMANTIC_ID,
                        BT.BOOK_TYPE_NAME,
                        CB.BOOK_TYPE_ID,
                        CT.NAME AS CONTENT_TYPE_NAME,
                        EC.TYPE_ID AS CONTENT_TYPE_ID,
                        TO_CHAR(CB.BOOK_DATE, 'YYYY-MM-DD') AS BOOK_DATE,
                        CM.ORDER_NUM,
                        CM.ID
                    FROM ECM_CONTENT_MAP CM 
                        INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                        LEFT JOIN META_DM_RECORD_MAP DM ON EC.CONTENT_ID = DM.TRG_RECORD_ID
                        LEFT JOIN CMP_COMPANY_BOOK CB ON DM.SRC_RECORD_ID = CB.ID
                        LEFT JOIN BOOK_TYPE BT ON BT.BOOK_TYPE_ID = CB.BOOK_TYPE_ID 
                        LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                    WHERE CM.RECORD_ID = $id AND 
                        CM.REF_STRUCTURE_ID = ".$refStructureId."
                        AND EC.IS_VERSION IS NULL
                    ORDER BY CM.ORDER_NUM ASC
                ) t0 --WHERE ROW_NUMB < 10";
        
        return $this->db->GetRow($sql);
        
    }
    
    public function getErlSavedDataModel($id) {
        
        $param = array(
            'systemMetaGroupId' => '1529325913140979',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $id
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            return $data['result'][0];
        }
        return array();
    }
    
    public function electronRegisterLegalSaveModel() {
        
        $postData = Input::postData();
        $erlContentIds = $postData['erlContentId'];
        
        if ($erlContentIds) {
            
            $response          = array('status' => 'success', 'message' => 'Success');
            $recordId          = $postData['recordId'];
            $erlCompanyBookIds = $postData['erlCompanyBookId'];
            $erlSemanticIds    = $postData['erlSemanticId'];
            $createdDate       = Date::currentDate();
            $createdUserId     = Ue::sessionUserKeyId();
            $saveProcessCode   = issetVar($postData['saveProcessCode']);
            
            if ($saveProcessCode && (strtolower($saveProcessCode) == 'ers_service_book_meta_dv_001' || strtolower($saveProcessCode) == 'ers_company_book_meta_add_dv_001' || strtolower($saveProcessCode) == 'ers_company_book_meta_reg_dv_001')) {
                if (strtolower($saveProcessCode) == 'ers_service_book_meta_dv_001') {
                    
                    $inputParams = array('id' => $recordId);

                    foreach ($erlContentIds as $k => $erlContentId) {

                        $bookDate = issetParam($postData['bookDate'][$k]);
                        $bookTypeId = issetParam($postData['bookTypeId'][$k]);
                        $contentTypeId = issetParam($postData['contentTypeId'][$k]);

                        if ($bookDate != '' && $bookTypeId != '' && $contentTypeId != '') {

                            $inputParams['ERS_COMPANY_BOOK_META_DV'][$k] = array(
                                'id'            => '' /*$erlCompanyBookIds[$k]*/,
                                'bookDate'      => $bookDate, 
                                'bookTypeId'    => $bookTypeId, 
                                'serviceBookId'  => $recordId,
                                'createdDate'   => $createdDate,
                                'createdUserId' => $createdUserId, 
                                'ERS_DM_RECORD_MAP_DV' => array(
                                    'id'            => $erlSemanticIds[$k],
                                    'srcRecordId'   => $erlCompanyBookIds[$k],
                                    'srcTableName'  => 'CMP_COMPANY_BOOK',
                                    'trgTableName'  => 'ECM_CONTENT',
                                    'trgRecordId'   => $erlContentId,
                                    'ERS_CON_TYPE_DV' => array(
                                        'id'     => $erlContentId,
                                        'typeId' => $contentTypeId
                                    )
                                )
                            );
                        }
                    }

                    $bpResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'ERS_SERVICE_BOOK_META_DV_001', $inputParams);
                    if ($bpResult['status'] != 'success') {
                        $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($bpResult));
                    } 
                }  elseif (Str::lower($saveProcessCode) == 'ers_service_company_book_meta_dv_001') { 

                } else {
                    foreach ($erlContentIds as $k => $erlContentId) {

                        $bookDate = issetVar($postData['bookDate'][$k]);
                        $bookTypeId = issetVar($postData['bookTypeId'][$k]);
                        $contentTypeId = issetVar($postData['contentTypeId'][$k]);

                        if ($bookDate != '' && $bookTypeId != '' && $contentTypeId != '') {

                            $inputParams = array(
                                //'id' => $erlCompanyBookIds[$k], @OBAMA
                                'ERS_DM_RECORD_MAP_DV' => array(
                                    'id'            => $erlSemanticIds[$k],
                                    'srcRecordId'   => '', //$erlCompanyBookIds[$k], @OBAMA
                                    'srcTableName'  => 'CMP_COMPANY_BOOK',
                                    'trgTableName'  => 'ECM_CONTENT',
                                    'trgRecordId'   => $erlContentId,
                                    'ERS_CON_TYPE_DV' => array(
                                        'id'     => $erlContentId,
                                        'typeId' => $contentTypeId 
                                    )
                                ),
                                'bookDate'      => $bookDate, 
                                'bookTypeId'    => $bookTypeId,              
                                'serviceBookId' => $recordId, 
                                'createdDate'   => $createdDate,
                                'createdUserId' => $createdUserId
                            );

                            $resProccess = $this->ws->runSerializeResponse(self::$gfServiceAddress, $saveProcessCode, $inputParams);
                        }
                    }
                }
            }  elseif ($saveProcessCode && strtolower($saveProcessCode) == 'elec_meta_book_dv_001') {
                $inputParams = array('id' => $recordId);

                $tempArr = array();
                foreach ($erlContentIds as $k => $erlContentId) {
                    $bookDate = issetParam($postData['bookDate'][$k]);
                    $bookTypeId = issetParam($postData['bookTypeId'][$k]);
                    $contentTypeId = issetParam($postData['contentTypeId'][$k]);

                    if ($bookDate != '' && $contentTypeId != '' && !in_array($erlContentId, $tempArr)) {
                        array_push($tempArr, $erlContentId);
                        $inputParams = array(
                            'id'            => '' /*$erlCompanyBookIds[$k]*/,
                            'bookDate'      => $bookDate, 
                            'bookTypeId'    => '999', 
                            'recordId'  => $recordId,
                            'createdDate'   => $createdDate,
                            'createdUserId' => $createdUserId, 
                            'ERS_DM_RECORD_MAP_DV' => array(array(
                                'id'            => $erlSemanticIds[$k],
                                'srcRecordId'   => $recordId,
                                'srcTableName'  => 'ELEC_META_BOOK',
                                'trgTableName'  => 'ECM_CONTENT',
                                'trgRecordId'   => $erlContentId,
                                'ERS_CON_TYPE_DV' => array(
                                    'id'     => $erlContentId,
                                    'typeId' => $contentTypeId
                                )
                            ))
                        );
                        $bpResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'ELEC_META_BOOK_DV_001', $inputParams);
                    }
                }
                if ($bpResult['status'] != 'success') {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($bpResult));
                } 
            }  elseif ($saveProcessCode && strtolower($saveProcessCode) == 'ntr_service_content_map_dv_001') {
                $inputParams = array('id' => $recordId);

                (Array) $contentMap = $tempArr = array();
                /* print_array($erlContentIds); */

                foreach ($erlContentIds as $k => $erlContentId) {
                    $bookDate = issetParam($postData['bookDate'][$k]);
                    $bookTypeId = issetParam($postData['bookTypeId'][$k]);
                    $contentTypeId = issetParam($postData['contentTypeId'][$k]);

                    if ($contentTypeId != '' && !in_array($erlContentId, $tempArr)) {
                        array_push($tempArr, $erlContentId);
                        
                        $contentMapParams =  array ( 
                            /* 'id' => '',  */
                            'recordId' => $recordId, 
                            'contentId' => $erlContentId, 
                            'tagCode' => $contentTypeId, 
                        );

                        array_push($contentMap, $contentMapParams);
                    }
                }
                if ($contentMap) {
                    $inputParams = array ( 
                        'id' => $recordId, 
                        'NTR_CONTENT_MAP_FILE_DV' => $contentMap,
                    );
                    $bpResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'NTR_SERVICE_CONTENT_MAP_DV_001', $inputParams);
                    /* print_array($bpResult);
                    die; */
                    if ($bpResult['status'] != 'success') {
                        $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($bpResult));
                    } 
                }
                
            } else {
                
                $inputParams = array('id' => $recordId);
                
                foreach ($erlContentIds as $k => $erlContentId) {
                    
                    $bookDate = issetParam($postData['bookDate'][$k]);
                    $bookTypeId = issetParam($postData['bookTypeId'][$k]);
                    $contentTypeId = issetParam($postData['contentTypeId'][$k]);
                    
                    if ($bookDate != '' && $bookTypeId != '' && $contentTypeId != '') {
                        
                        $inputParams['ERS_COMPANY_BOOK_META_DV'][$k] = array(
                            'id'            => '' /*$erlCompanyBookIds[$k]*/,
                            'bookDate'      => $bookDate, 
                            'bookTypeId'    => $bookTypeId, 
                            'companyKeyId'  => $recordId, 
                            'createdDate'   => $createdDate,
                            'createdUserId' => $createdUserId, 
                            'ERS_DM_RECORD_MAP_DV' => array(
                                'id'            => $erlSemanticIds[$k],
                                'srcRecordId'   => $erlCompanyBookIds[$k],
                                'srcTableName'  => 'CMP_COMPANY_BOOK',
                                'trgTableName'  => 'ECM_CONTENT',
                                'trgRecordId'   => $erlContentId,
                                'ERS_CON_TYPE_DV' => array(
                                    'id'     => $erlContentId,
                                    'typeId' => $contentTypeId
                                )
                            )
                        );
                    }
                }
                
                $bpResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'ERS_COMPANY_KEY_META_DV_001', $inputParams);
                
                if ($bpResult['status'] != 'success') {
                    
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($bpResult));
                    
                } else {
                    
                    if (isset($postData['dataViewId']) && isset($postData['nextWfmStatusId'])) {
                        
                        $this->load->model('mdobject', 'middleware/models/');
                        
                        unset($_POST);
            
                        $_POST['metaDataId'] = $postData['dataViewId'];
                        $_POST['newWfmStatusid'] = $postData['nextWfmStatusId'];
                        $_POST['dataRow'] = array('id' => $recordId, 'wfmStatusId' => $postData['currentWfmStatusId']);
                        $_POST['description'] = '';

                        $setStatus = $this->model->setRowWfmStatusModel();
                        
                        $this->load->model('mddoc', 'middleware/models/');
                        
                        if ($setStatus['status'] == 'success') {
                            $response['closeModal'] = 1;
                            $response['dataViewId'] = $postData['dataViewId'];
                        }
                    }
                }
                
                //$response['rowsIds'] = $rowsIds;
            }
            
        } else {
            $response = array('status' => 'warning', 'message' => 'No content!');
        }
        
        return $response;
    }
    
    public function elcRegisterBookLegalSaveModel() {
        
        $postData = Input::postData();
        $erlContentIds = $postData['erlContentId'];
        
        if ($erlContentIds) {
            
            $response          = array('status' => 'success', 'message' => 'Success', 'result' => array());
            $recordId          = $postData['recordId'];
            $erlCompanyBookIds = $postData['erlCompanyBookId'];
            $erlCompanyKeyIds  = $postData['erlCompanyKeyId'];
            $erlSemanticIds    = $postData['erlSemanticId'];
            $createdDate       = Date::currentDate();
            $createdUserId     = Ue::sessionUserKeyId();
            $saveProcessCode   = issetVar($postData['saveProcessCode']);
            
            $inputParams = array('id' => $recordId);

            foreach ($erlContentIds as $k => $erlContentId) {

                $bookDate = issetVar($postData['bookDate'][$k]);
                $bookTypeId = issetVar($postData['bookTypeId'][$k]);
                $contentTypeId = issetVar($postData['contentTypeId'][$k]);

                if ($bookDate != '' && $bookTypeId != '' && $contentTypeId != '') {

                    $inputParams['ERS_COMPANY_BOOK_META_DV'][$k] = array(
                        'id'            => '' /*$erlCompanyBookIds[$k]*/,
                        'bookDate'      => $bookDate, 
                        'bookTypeId'    => $bookTypeId,              
                        'companyKeyId'  => $recordId, 
                        'createdDate'   => $createdDate,
                        'createdUserId' => $createdUserId, 
                        'ERS_DM_RECORD_MAP_DV' => array(
                            'id'            => $erlSemanticIds[$k],
                            'srcRecordId'   => $erlCompanyBookIds[$k],
                            'srcTableName'  => 'CMP_COMPANY_BOOK',
                            'trgTableName'  => 'ECM_CONTENT',
                            'trgRecordId'   => $erlContentId,
                            'ERS_CON_TYPE_DV' => array(
                                'id'     => $erlContentId,
                                'typeId' => $contentTypeId
                            )
                        )
                    );
                }
            }
            
            $resProccess = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'ersServiceCompanyBookDV_001', $inputParams);
            $response['result'] = $resProccess;
        } else {
            $response = array('status' => 'warning', 'message' => 'No content!');
        }
        
        return $response;
    }
    
    public function electronRegisterLegalCvlSaveModel() {
        
        $postData = Input::postData();
        
        $erlContentIds = $postData['erlContentId'];
        
        if ($erlContentIds) {
            
            $response          = array('status' => 'success', 'message' => 'Success');
            $recordId          = $postData['recordId'];
            $erlCompanyBookIds = $postData['erlCompanyBookId'];
            $erlSemanticIds    = $postData['erlSemanticId'];
            $createdDate       = Date::currentDate();
            $createdUserId     = Ue::sessionUserKeyId();
                        
            foreach ($erlContentIds as $k => $erlContentId) {
                
                if (issetVar($postData['bookDate'][$k]) != '' && issetVar($postData['bookTypeId'][$k]) != '' && issetVar($postData['contentTypeId'][$k]) != '') {
                    $inputParams = array(
                        'id'  => $erlCompanyBookIds[$k],
                        'ERS_DM_RECORD_MAP_DV' => array(
                            'id' => $erlSemanticIds[$k],
                            'srcRecordId' => $erlCompanyBookIds[$k],
                            'srcTableName' => 'CMP_COMPANY_BOOK',
                            'trgTableName' => 'ECM_CONTENT',
                            'trgRecordId' => $erlContentId,
                            'ERS_CON_TYPE_DV' => array(
                                'id' => $erlContentId,
                                'typeId' => issetVar($postData['contentTypeId'][$k])
                            )
                        ),
                        'bookDate'      => issetVar($postData['bookDate'][$k]), 
                        'bookTypeId'    => issetVar($postData['bookTypeId'][$k]),              
                        'companyKeyId'  => $recordId,
                        'createdDate'   => $createdDate,
                        'createdUserId' => $createdUserId 
                    );
                    
                    $resProccess = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'CVL_CIVIL_BOOK_META_DV_001', $inputParams);
                }
            }
            
        } else {
            $response = array('status' => 'warning', 'message' => 'No content!');
        }
        
        return $response;
    }
    
    public function electronRegisterLegalBulkScanModel($recordId, $selectedRow = array()) {
        
        if (Config::getFromCache('CIVIL_OFFLINE_SERVER') === '1') {
            
            includeLib('Utils/Functions');
            $postData = Input::postData();
            set_time_limit(0);
            ini_set('memory_limit', '-1');        
            
            $filesObjs      = explode(',', $_POST['filesObj']['files']);
            $sessionUserId  = Ue::sessionUserKeyId();
            $currentDate    = Date::currentDate('Y-m-d H:i:s');
            $typeId         = Input::post('type');

            $civilPackId         = $selectedRow['id']; //$selectedRow['civilpackid'];
            
            if (issetParam($selectedRow['stateregnumber']) === '') {
                return array('status' => 'error', 'message' => 'РД олдсонгүй', 'row' => $selectedRow);
                die;
            }
            
            if (issetParam($selectedRow['crcivilid']) === '') {
                return array('status' => 'error', 'message' => 'CR CIVILID олдсонгүй');
                die;
            }

            $param1 =   array (
                            'stateregnumber' => $selectedRow['stateregnumber'], 
                            'archivetypeid' => issetDefaultVal($selectedRow['archivetypeid'], '2'),
                        );
            $civilPdata = Functions::runProcess('CVL_CIVIL_PACK_GET_LIST_008', $param1);
            
            if (issetParam($civilPdata['result']['civilpackid'])) {
                $civilPackId = $civilPdata['result']['civilpackid'];
            } else {
                if (issetParam($selectedRow['isftpscanid']) === '2' && issetParam($selectedRow['ftpscanid']) !== '') {
                    $civilPackId = $selectedRow['ftpscanid'];
                }
            }
            
            
            if (issetParam($selectedRow['civilid']) !== '') {
                $civilData = Functions::runProcess('CVL_CR_CIVIL_LIST_004', array('stateregnumber' => $selectedRow['stateregnumber']));
                
                if (issetParam($civilData['result']['stateregnumber']) === '') {
                    return array('status' => 'error', 'message' => 'РД-аар мэдээлэл олдсонгүй');
                    die;
                }
                
                $civilDataResult = $civilData['result'];
                $civilId = $civilDataResult['civilid'];
                
                $umdataCR = Functions::runProcess('CVL_CR_USER_LIST_004', array('crdepartmentId123' => Ue::sessionDepartmentId(), 'cruserId' => Ue::sessionUserKeyId()));
                if (issetParam($umdataCR['status']) !== 'success') {
                    return array('status' => 'error', 'message' => issetParam($umdataCR['text']), 'i' => '0');
                    die;
                }
                
                if (issetParam($umdataCR['result']['stateregnumber']) === '') {
                    return array('status' => 'error', 'message' => 'Таны РД олдсонгүй');
                    die;
                }
                
                $umdataR = Functions::runProcess('CVL_ELEC_USER_LIST_008', array('stateRegNumber' => $umdataCR['result']['stateregnumber'], 'elecDepartmentId' => $umdataCR['result']['crdepartmentid']));
                if (issetParam($umdataR['status']) !== 'success') {
                    return array('status' => 'error', 'message' => issetParam($umdataR['text']), 'i' => '1');
                    die;
                }
                
                if (issetParam($umdataR['result']['stateregnumber']) === '') {
                    return array('status' => 'error', 'message' => 'Цахимжуулалтын системээс таны эрх олдсонгүй');
                    die;
                }
                
                $params = array (
                            'id' => NULL,
                            'stateRegNumber' => issetParam($civilDataResult['stateregnumber']),
                            'isOnline' 			=> '1',
                            'sourceCode' 		=> 'civilReg',
                            'crUserId' 			=> issetParam($umdataCR['result']['cruserid']),
                            'crUserName' 		=> issetParam($umdataCR['result']['crusername']),
                            'crDepartmentName' 	=> issetParam($umdataCR['result']['crdepartmentname']),
                            'crDepartmentId' 	=> issetParam($umdataCR['result']['crdepartmentid']),
                            'createdDate' => $currentDate,
                            'stateRegNumber' => issetParam($civilDataResult['stateregnumber']),
                            'isactive' => '1',
                            'lastName' => issetParam($civilDataResult['lastname']),
                            'firstname' => issetParam($civilDataResult['firstname']),
                            'familyname' => issetParam($civilDataResult['familyname']),
                            'stateRegisteredNumber' => issetParam($civilDataResult['stateregisterednumber']),
                            'dateofbirth' => issetParam($civilDataResult['dateofbirth']),
                            'motherfirstname' => issetParam($civilDataResult['motherfirstname']),
                            'motherlastname' => issetParam($civilDataResult['motherlastname']),
                            'motherregnumber' => issetParam($civilDataResult['motherregnumber']),
                            'createdUserId' => issetParam($umdataR['result']['elecuserid']),
                        );
            
                $resultCivil = Functions::runProcess('CVL_ELEC_CIVIL_DV_007', $params);

                if (issetParam($resultCivil['status']) !== 'success') {
                    return array('status' => 'error', 'message' => issetParam($resultCivil['text']), 'i' => '2');
                    die;
                }
                $civilId = $resultCivil['result']['id'];
            } else {
                $civilId = $selectedRow['civilid'];
            }
            
            $params = array (
                            'id' => $civilPackId,
                            'civilId' => $civilId,
                            'archiveTypeId' => issetDefaultVal($selectedRow['archivetypeid'], '2'),
                            'isActive' => '1',
                            'description' => NULL,
                            'wfmStatusId' => '1540182412552262',
                            'wfmDescription' => 'Иргэний системээс батлав ['. issetDefaultVal($selectedRow['getbooktypeid'], '70001') .']',
                            'sourceCode' => 'civilReg',
                            'crUserId' 			=> issetParam($umdataCR['result']['cruserid']),
                            'crUserName' 		=> issetParam($umdataCR['result']['crusername']),
                            'crDepartmentName' 	=> issetParam($umdataCR['result']['crdepartmentname']),
                            'crDepartmentId' 	=> issetParam($umdataCR['result']['crdepartmentid']),
                            'createdDate' => NULL,
                            'createdElecUserId' => issetParam($umdataR['result']['elecuserid']),
                            'isOld' => '0',
                        );
            $resultCivilPack = Functions::runProcess('CVL_ELEC_CIVIL_PACK_DV_007', $params);
            
            if (issetParam($resultCivilPack['status']) !== 'success') {
                return array('status' => 'error', 'message' => issetParam($resultCivil['text']), 'i' => '3');
                die;
            }
            
            $recordId            = $civilPackId;
            
            if ($selectedRow) {
                
                $isAllRowsBookTypeId = true;
                
                $civilBookId = Functions::runProcess('CVL_CIVIL_ELEC_BOOK_DV_008', array('civilPackId' => $civilPackId));
                /*$cvlBookId = issetParam($civilBookId['result']['id']);
                if (issetParam($civilBookId['result']['id']) === '') {
                    $params = array (
                                'id' => NULL,
                                'civilPackId' => $civilPackId,
                                'bookTypeId' => issetDefaultVal($selectedRow['getbooktypeid'], '70001'),
                                'isactive' => '1',
                                'createdDate' => $currentDate,
                                'bookDate' => $currentDate,
                                'civilid' => $civilId,
                                'lastName' => issetParam($civilDataResult['lastname']),
                                'firstname' => issetParam($civilDataResult['firstname']),
                                'familyname' => issetParam($civilDataResult['familyname']),
                                'stateRegNumber' => issetParam($civilDataResult['stateregnumber']),
                                'stateRegisteredNumber' => issetParam($civilDataResult['stateregisterednumber']),
                                'dateofbirth' => issetParam($civilDataResult['dateofbirth']),
                                'gender' => NULL,
                                'createdUserId' => issetParam($umdataR['result']['elecuserid']),
                                'sourceCode' => 'civilReg',
                    );

                    $resultBook = Functions::runProcess('CVL_CIVIL_ELEC_BOOK_DV_007', $params);
                    $cvlBookId = issetParam($resultBook['result']['id']);
                    $isUpdateParams = true;
                } else {
                    $isAlreadyCvlBook = true;
                }*/
            }
            
            $mainResult     = Functions::runProcess('CVL_SCAN_PACK_DV_008', array('id' => $civilPackId));
            
            if (isset(Mddoc::$paramData['isContentMapInsert']) && Mddoc::$paramData['isContentMapInsert']) {
                $contentMapRecordId = Input::param(Mddoc::$paramData['isContentMapInsert']);
            }

            $result = Functions::runProcess('CVL_CR_CIVIL_BOOK_CONTENT_LIST_007', array('recordid' => $civilPackId, 'refStructureId' => '1532504449451647'));
            
            (Array) $mapFileNameKey = array();

            if (issetParamArray($result['result']['cvl_civil_book_content_dv'])) {
                $getMapDatas = $result['result']['cvl_civil_book_content_dv'];
                $mapFileNameKey = Arr::groupByArrayOnlyRows($getMapDatas, 'physicalpath');
            }
            
            $ss             = array();
            $newCount       = 0;
            $oldCount       = 0;
    
            $this->db->StartTrans();

            (Array) $cvlCrContentMapDv = $removeContentIds = array();
            foreach ($filesObjs as $k => $file) {

                $fileName  = explode('-', $file);
                $temp = array (
                            'refStructureId' => '1532504449451647',
                            'orderNum' => $fileName[0],
                            'isMain' => '0',
                            'tagCode' => 'civilReg',
                            'id' => NULL,
                            'contentId' => NULL,
                            'recordId' => NULL,
                            'createdDate' => NULL,
                            'createdUserId' => NULL,
                            'CVL_CR_CONTENT_DV' => array (
                                                        'fileName' => $fileName[1],
                                                        'fileExtension' => 'tif',
                                                        'physicalPath' => $fileName[1],
                                                        'isPhoto' => '1',
                                                        'isSigned' => '0',
                                                        'description' => 'civilReg',
                                                        'id' => NULL,
                                                        'createdDate' => NULL,
                                                        'createdUserId' => NULL,
                                                    ),
                        );
                
                if (isset($mapFileNameKey[$fileName[1]])) {
                    $temp['id'] = $mapFileNameKey[$fileName[1]][0]['id'];
                    $temp['contentId'] = $mapFileNameKey[$fileName[1]][0]['contentid'];
                    $temp['CVL_CR_CONTENT_DV']['id'] = $mapFileNameKey[$fileName[1]][0]['contentid'];
                    array_push($removeContentIds, $mapFileNameKey[$fileName[1]][0]['contentid']);
                    
                    unset($mapFileNameKey[$fileName[1]]);
                    $oldCount++;
                } else {
                    $newCount++;
                }
                
                array_push($cvlCrContentMapDv, $temp);
            }

            $params = array (
                            'id' => $civilPackId,
                            'wfmStatusId' => '1540182290684067',
                            'CVL_CR_CONTENT_MAP_DV' => $cvlCrContentMapDv,
                            'CVL_ELEC_WFM_LOG_DV' =>  array (/*
                                'refStructureId' => '1532504449451647',
                                'wfmStatusId' => '1540182290684067',
                                'wfmDescription' => 'Бүртгэлээс сканнердсан',
                                'id' => NULL,
                                'recordId' => NULL,
                                'createdDate' => NULL,
                                'createdUserId' => NULL,
                                'cipherText' => NULL,
                                'prevWfmStatusId' => issetParam($mainResult['result']['wfmstatusid']),
                            */),
                        );

            $result = Functions::runProcess('CVL_SCAN_PACK_DV_007', $params);
            $totalCount = $newCount + $oldCount;
            
            $this->db->CompleteTrans();

            if ($totalCount) {
                $resultKpi = Functions::runProcess('CVL_GET_KPI_BY_PACK_LIST_008', array('civilPackId' => $civilPackId));;
                
                if (issetParam($resultKpi['result']['dtlid'])) {
                    Functions::runProcess('CVL_KPI_FACT_DV_007', array('id' => $resultKpi['result']['dtlid'], 'fact3' => $totalCount));
                }
            }
            

            if ($mapFileNameKey) {

                (Array) $params = array();
                foreach ($mapFileNameKey as $val) {
                    Functions::runProcess('CVL_CONTENT_VERSION_DV_007', array('id' => $val[0]['contentid'], 'isversion' => '1'));
                }

                /* if ($params) {
                    Functions::runProcess('CVL_CIVIL_ELEC_BOOK_DV_007', $params);
                } */
            }

            (Array) $result1 = array();
            if (issetParam($result['status']) === 'success') {
                
                $savedResult = issetParamArray($result['result']);
                $savedResultDtl = issetParamArray($savedResult['cvl_cr_content_map_dv']);
                (Array) $cvlCivilElecBookDv = array();
                foreach ($savedResultDtl as $row) {
                    if (!in_array($row['contentid'], $removeContentIds)) {
                        $temp = array (
                                    'bookTypeId' => issetDefaultVal($selectedRow['getbooktypeid'], '70001'),
                                    'lastName' => issetParam($civilDataResult['lastname']),
                                    'firstname' => issetParam($civilDataResult['firstname']),
                                    'familyname' => issetParam($civilDataResult['familyname']),
                                    'stateRegNumber' => issetParam($civilDataResult['stateregnumber']),
                                    'stateRegisteredNumber' => issetParam($civilDataResult['stateregisterednumber']),
                                    'dateofbirth' => issetParam($civilDataResult['dateofbirth']),
                                    'sourceCode' => 'civilReg',
                                    'id' => NULL,
                                    'civilPackId' => $savedResult['id'],
                                    'createdDate' => $currentDate,
                                    'createdUserId' => $sessionUserId,
                                    'CVL_CIVIL_BOOK_CONTENT_DV' => 
                                        array (
                                            'srcTableName' => 'CVL_CIVIL_BOOK',
                                            'trgTableName' => 'ECM_CONTENT',
                                            'trgRecordId' => $row['contentid'],
                                            'id' => NULL,
                                            'srcRecordId' => NULL,
                                        ),
                                );
                        array_push($cvlCivilElecBookDv, $temp); 
                        
                    }
                }
                
                $params = array (
                        'id' => $savedResult['id'],
                        'wfmStatusId' => '1540182412552262',
                        'CVL_CIVIL_ELEC_BOOK_DV' =>  $cvlCivilElecBookDv,
                        'CVL_ELEC_WFM_LOG_DV' => array (
                            'id' => NULL,
                            'refStructureId' => '1532504449451647',
                            'recordId' => $savedResult['id'],
                            'createdDate' => $currentDate,
                            'createdElecUserId' => issetDefaultVal($umdataR['result']['elecuserid'], $sessionUserId) ,
                            'cipherText' => $newCount,
                            /*'wfmDescription' => 'Бүртгэлийн системээс мета автоматаар оруулав.',
                            'wfmStatusId' => '1540182369128313',
                            */
                            'wfmStatusId' => '1540182412552262',
                            'wfmDescription' => 'Иргэний системээс батлав ['. issetDefaultVal($selectedRow['getbooktypeid'], '70001') .']',
                            'prevWfmStatusId' => $savedResult['wfmstatusid'],
                        )
                );

                $result1 = Functions::runProcess('CVL_META_PACK_DV_007', $params);

                if (issetParam($result1['status']) !== 'success') {
                    return array('status' => 'error', 'message' => issetParam($result1['text']), 'i' => '4');
                    die;
                }
                
                $params = array (
                                'id' => NULL,
                                'crCivilId' => $selectedRow['crcivilid'],
                                'civilPackId' => $civilPackId,
                                'createdDate' => $currentDate,
                                'createdUserId' => $sessionUserId, 
                                'archiveTypeId' => issetDefaultVal($selectedRow['archiveTypeId'], '2'),
                                'bookTypeId' => issetDefaultVal($selectedRow['getbooktypeid'], '70001'),
                                'elecCivilId' => $civilId,
                            );
                
                $result2 = Functions::runProcess('CVL_CR_PACK_DV_001', $params);
                
                if (issetParam($result2['status']) !== 'success') {
                    return array('status' => 'error', 'message' => issetParam($result2['text']), 'i' => '5');
                    die;
                }
                $dd = array();
                if (issetParam($selectedRow['id']) !== '' && issetParam($selectedRow['processcode']) !== '') {
                    $parameters = array(
                                        'isScanned' => '1', 
                                        'id' => $selectedRow['id'],
                                        'cmodifiedUserId' => issetParam($selectedRow['cmodifieduserid']),
                                        'cmodifiedDate' => issetParam($selectedRow['cmodifieddate'])
                                    );
                    $dd = Functions::runProcess($selectedRow['processcode'], $parameters);
                }
            }
            return array('status' => 'success', 'message' => 'Success', 'ss' => $result, 'result1' => $result1, 's' => $dd);

        } else {

            set_time_limit(0);
            ini_set('memory_limit', '-1');        
            
            $filesObjs      = explode(',', $_POST['filesObj']['files']);
            $sessionUserId  = Ue::sessionUserKeyId();
            $currentDate    = Date::currentDate('Y-m-d H:i:s');
            $typeId         = (Input::postCheck('type')) ? Input::post('type') : '1'; /*  && (Input::post('type') === '2' || Input::post('type') === '3')  */
            
            if (isset(Mddoc::$paramData['allrowsbooktypeid']) && Mddoc::$paramData['allrowsbooktypeid'] && $selectedRow) {
                
                $isAllRowsBookTypeId = true;
                $allRowsBookTypeId   = Input::param(Mddoc::$paramData['allrowsbooktypeid']);
                $civilPackId         = $selectedRow['civilpackid'];
                $civilId             = $selectedRow['civilid'];
                $recordId            = $civilPackId;
                
                $civilBookId         = $this->db->GetOne("
                    SELECT 
                        CB.ID  
                    FROM CVL_CIVIL_BOOK CB 
                        INNER JOIN CVL_CIVIL_PACK CP ON CP.ID = CB.CIVIL_PACK_ID 
                    WHERE CP.ID = $civilPackId");
                
                if (!$civilBookId) {
                    $civilBookId = getUID();
                } else {
                    $isAlreadyCvlBook = true;
                }
            }
            
            if (isset(Mddoc::$paramData['isContentMapInsert']) && Mddoc::$paramData['isContentMapInsert']) {
                
                $contentMapRecordId = Input::param(Mddoc::$paramData['isContentMapInsert']);
            }
            
            $postData = Input::postData();
            $getMapDatas    = $this->getErlContentMapModel($recordId, $typeId);
            $mapFileNameKey = Arr::groupByArrayOnlyRows($getMapDatas, 'PHYSICAL_PATH');
            $ss             = array();
            $newCount       = 0;
            $oldCount       = 0;
    
            $this->db->StartTrans();
            
            foreach ($filesObjs as $k => $file) {
                
                $contentId = getUIDAdd($k);
                $fileName  = explode('-', $file);
                
                if (isset($mapFileNameKey[$fileName[1]])) {
    
                    if ($fileName[0] != $mapFileNameKey[$fileName[1]][0]['ORDER_NUM']) {
                        $this->db->Execute('UPDATE ECM_CONTENT_MAP SET ORDER_NUM = ' . $fileName[0] . ' WHERE ID = ' . $mapFileNameKey[$fileName[1]][0]['ID']);
                    }
                    unset($mapFileNameKey[$fileName[1]]);
                    $oldCount++;
                } else {
    
                    $this->db->Execute("INSERT INTO ECM_CONTENT (CONTENT_ID, FILE_NAME, PHYSICAL_PATH, FILE_EXTENSION, CREATED_DATE, CREATED_USER_ID, IS_PHOTO) VALUES ($contentId, '".$fileName[1]."', '".$fileName[1]."', 'tif', '$currentDate', $sessionUserId, 1)");
                    
                    switch ($typeId) {
                        case '1':
                            $structureId = Mddoc::$erlStructureId;
                            break;
                        case '2':
                        case '3':
                            $structureId = Mddoc::$erlStructureIdCivil;
                            break;
                        case '4':
                            $structureId = Mddoc::$erlStructureIdCnt;
                            break;
                    }
    
                    $this->db->Execute("INSERT INTO ECM_CONTENT_MAP (ID, CONTENT_ID, REF_STRUCTURE_ID, RECORD_ID, CREATED_DATE, CREATED_USER_ID, ORDER_NUM) VALUES ($contentId, $contentId, $structureId, $recordId, '$currentDate', $sessionUserId, ".$fileName[0].")"); 
                    
                    if (isset($isAllRowsBookTypeId)) {
                        $this->db->Execute("INSERT INTO META_DM_RECORD_MAP (ID, SRC_TABLE_NAME, SRC_RECORD_ID, TRG_TABLE_NAME, TRG_RECORD_ID) VALUES ($contentId, 'CVL_CIVIL_BOOK', $civilBookId, 'ECM_CONTENT', $contentId)"); 
                    }
                    
                    if (isset($contentMapRecordId)) {
                        $newContentId = getUIDAdd($k);
                        $this->db->Execute("INSERT INTO ECM_CONTENT_MAP (ID, CONTENT_ID, REF_STRUCTURE_ID, RECORD_ID, CREATED_DATE, CREATED_USER_ID, FOLDER_ID, ORDER_NUM) VALUES ($newContentId, $contentId, $structureId, $contentMapRecordId, '$currentDate', $sessionUserId, $recordId, ".$fileName[0].")"); 
                    }
                    
                    $newCount++;
                }
            }
            
            $totalCount = $newCount+$oldCount;
            
            if ($totalCount) {
                $resultQry = $this->db->GetRow("SELECT T2.ID, T2.FACT3 
                                                FROM CVL_CIVIL_PACK T0
                                                    INNER JOIN CVL_CIVIL T1 ON T0.CIVIL_ID = T1.ID
                                                    INNER JOIN KPI_DM_DTL T2 ON T1.ID = T2.BOOK_ID
                                                WHERE T0.ID = '". $selectedRow['id'] ."'");
                
                if ($resultQry) {
                    $this->db->AutoExecute("KPI_DM_DTL", array('FACT3' => $totalCount), 'UPDATE', "ID = '". $resultQry['ID'] ."'");
                }
            }
            
            
            if (isset($isAllRowsBookTypeId) && !isset($isAlreadyCvlBook)) {
                
                $this->db->Execute("INSERT INTO CVL_CIVIL_BOOK (ID, BOOK_TYPE_ID, CIVIL_PACK_ID, CIVIL_ID, BOOK_DATE, IS_ACTIVE) VALUES ($civilBookId, $allRowsBookTypeId, $civilPackId, $civilId, '$currentDate', 1)"); 
                
                $isUpdateParams = true;
            }
    
            $this->db->CompleteTrans();
            
            if (isset($isUpdateParams) && isset(Mddoc::$paramData['udps']) && Mddoc::$paramData['udps'] && $selectedRow) {
                
                $udps = Input::param(Mddoc::$paramData['udps']);
                $fields = explode('|', $udps);
                
                foreach ($fields as $field) {
                    
                    $paramsArr = explode('@', $field);
                    
                    if (count($paramsArr) == 2) {
                        
                        $params = explode('.', $paramsArr[0]);
                    
                        if (count($params) == 4) {
                            
                            $dvField = strtolower($params[1]);
                            
                            if (isset($selectedRow[$dvField])) {
                                
                                $tblName = $params[0];
                                $primaryCol = $params[2];
                                $colName = $params[3];
                                $setValue = $paramsArr[1];
                            
                                $this->db->AutoExecute($tblName, array($colName => $setValue), 'UPDATE', $primaryCol.' = '.$selectedRow[$dvField]);
                            }
                        }
                    }
                }
            }
            
            if ($mapFileNameKey) {
                
                foreach ($mapFileNameKey as $val) {
                    
                    $contentMapData = array('IS_VERSION' => '1');
                    
                    $this->db->AutoExecute('ECM_CONTENT', $contentMapData, 'UPDATE', 'CONTENT_ID = '.$val[0]['CONTENT_ID']);
                }
            }
            
            if (Input::postCheck('nextWfmStatus') && !isset(Mddoc::$paramData['ignoreWorkFlow'])) {
                
                $this->load->model('mdobject', 'middleware/models/');
                
                if (isset(Mddoc::$paramData['nextwfmstatusid'])) {
                    
                    $metaDataId = Mddoc::$paramData['dataViewId'];
                    $_POST['newWfmStatusid'] = Mddoc::$paramData['nextwfmstatusid'];
                    
                } else {
                    
                    switch ($typeId) {
                        case '1':
                            
                            $metaDataId = '1528858041095420';
                            if ($_POST['selectedRow']['wfmstatusid'] == '1530946042933028') {
                                $_POST['newWfmStatusid'] = '1529373710120176';
                            } else {
                                $_POST['newWfmStatusid'] = '1529317374113055';
                            }                       
                            
                            break;
                        case '2':
                        case '3':
                            
                            $metaDataId = '1533714393827725';
                            if ($_POST['selectedRow']['wfmstatusid'] == '1532504903194151') {
                                $_POST['newWfmStatusid'] = '1532504887691783';
                            } else {
                                $_POST['newWfmStatusid'] = '1532504692899860';
                            }
    
                            break;
                        case '4':
                            
                            $metaDataId = '1536131133813';
                            if ($_POST['selectedRow']['wfmstatusid'] == '1539923411241605') {
                                $_POST['newWfmStatusid'] = '1540182332232604';
                            } else {
                                $_POST['newWfmStatusid'] = '1540182290684067';
                            }
                            break;
                    }
                }
                
                $_POST['metaDataId'] = $metaDataId;
                $_POST['dataRow'] = $_POST['selectedRow'];
                $_POST['signerParams']['cyphertext'] = $newCount;
                $_POST['signerParams']['plainText'] = 'LegalBulkScan';
                
                if (issetParam($postData['paramData']['ignoreWorkFlow']) !== '1') {
                    $ss = $this->model->setRowWfmStatusModel();
                }
                
            }
            
            return array('status' => 'success', 'message' => 'Success', 'post' => $_POST, 'ss' => $ss);
        }
    }

    public function tryElectronRegisterLegalBulkScanModel($files, $recordId) { 

        set_time_limit(0);
        ini_set('memory_limit', '-1');        
        
        $filesObjs      = $files;
        $sessionUserId  = Ue::sessionUserKeyId();
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        $typeId         = (Input::postCheck('type')) ? Input::post('type') : '1';
        $getMapDatas    = $this->getErlContentMapModel($recordId, $typeId);
        $mapFileNameKey = array();
        $insertQuery    = ''; 
        
        foreach ($getMapDatas as $row) {
            $mapFileNameKey[$row['PHYSICAL_PATH']] = true;
        }

        $this->db->StartTrans();
        
        foreach ($filesObjs as $k => $file) {
            
            if (empty($file)) continue;
            
            $contentId = getUIDAdd($k);
            $fileName  = explode(' ', $file);
            
            $fileNameTrim = trim($fileName[1]);
            if (!isset($mapFileNameKey[$fileNameTrim])) {
                
                //@file_put_contents(BASEPATH.'log/custom_access.log', Date::currentDate()." ".$recordId." ".$contentId."\r\n", FILE_APPEND);

                $insertQuery = 'INSERT INTO ECM_CONTENT (CONTENT_ID, FILE_NAME, PHYSICAL_PATH, FILE_EXTENSION, CREATED_DATE, CREATED_USER_ID, IS_PHOTO) VALUES ('.$contentId.', \''.$fileNameTrim.'\', \''.$fileNameTrim.'\', \'tif\', \''.$currentDate.'\', '.$sessionUserId.', 1)';
                $this->db->Execute($insertQuery);
                
                $structureId = Mddoc::$erlStructureId;

                $insertQuery = 'INSERT INTO ECM_CONTENT_MAP (ID, CONTENT_ID, REF_STRUCTURE_ID, RECORD_ID, CREATED_DATE, CREATED_USER_ID, ORDER_NUM) VALUES ('.$contentId.', '.$contentId.', '.$structureId.', '.$recordId.', \''.$currentDate.'\', '.$sessionUserId.', '.$fileName[0].')';
                $this->db->Execute($insertQuery);
                
            }
        }

        $this->db->CompleteTrans();
        
        return array('status' => 'success', 'message' => 'Success');
    }    
    
    public function electronRegisterLegalBulkReScanModel($recordId, $selectedRow = array()) {
        
        $filesObjs     = explode(',', $_POST['filesObj']['files']);
        $sessionUserId = Ue::sessionUserKeyId();
        $currentDate   = Date::currentDate('Y-m-d H:i:s');
        $typeId        = (Input::postCheck('type')) ? Input::post('type') : '1';
        
        switch ($typeId) {
            case '1':
                $structureId = Mddoc::$erlStructureId;

                break;
            case '2':
            case '3':
                $structureId = Mddoc::$erlStructureIdCivil;

                break;
            case '4':
                $structureId = Mddoc::$erlStructureIdCnt;
                break;
        }
                
        $ss = array();
        
        if (isset(Mddoc::$paramData['allrowsbooktypeid']) && Mddoc::$paramData['allrowsbooktypeid'] && $selectedRow) {
            
            $isAllRowsBookTypeId = true;
            $civilPackId         = $selectedRow['civilpackid'];
            $recordId            = $civilPackId;
            $civilBookId         = $this->db->GetOne("
                SELECT 
                    CB.ID  
                FROM CVL_CIVIL_BOOK CB 
                    INNER JOIN CVL_CIVIL_PACK CP ON CP.ID = CB.CIVIL_PACK_ID 
                WHERE CP.ID = $civilPackId");
            
            if (!$civilBookId) {
                $civilBookId = getUID();
            } 
        }
        
        if (isset(Mddoc::$paramData['isContentMapInsert']) && Mddoc::$paramData['isContentMapInsert']) {
            
            $contentMapRecordId = Input::param(Mddoc::$paramData['isContentMapInsert']);
        }
            
        $resultMerge = $this->db->Execute("
            MERGE
                INTO ECM_CONTENT TRG
                USING ( 
                    SELECT 
                        EC.CONTENT_ID,
                        EC.FILE_NAME,
                        EC.PHYSICAL_PATH,
                        EC.FILE_EXTENSION,
                        CB.ID AS COMPANY_BOOK_ID,
                        DM.ID AS SEMANTIC_ID,
                        BT.BOOK_TYPE_NAME,
                        CB.BOOK_TYPE_ID,
                        CT.NAME AS CONTENT_TYPE_NAME,
                        EC.TYPE_ID AS CONTENT_TYPE_ID,
                        TO_CHAR(CB.BOOK_DATE, 'YYYY-MM-DD') AS BOOK_DATE,
                        CM.ORDER_NUM,
                        CM.ID
                    FROM ECM_CONTENT_MAP CM 
                        INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                        LEFT JOIN META_DM_RECORD_MAP DM ON EC.CONTENT_ID = DM.TRG_RECORD_ID 
                        LEFT JOIN CMP_COMPANY_BOOK CB ON CB.ID = DM.SRC_RECORD_ID 
                        LEFT JOIN BOOK_TYPE BT ON BT.BOOK_TYPE_ID = CB.BOOK_TYPE_ID 
                        LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                    WHERE CM.RECORD_ID = $recordId  
                        AND CM.REF_STRUCTURE_ID = $structureId 
                        AND EC.IS_VERSION IS NULL                                                                         
                ) SRC ON (TRG.CONTENT_ID = SRC.CONTENT_ID) 
                WHEN MATCHED THEN UPDATE 
            SET TRG.IS_VERSION = 1");        
        
        if ($resultMerge) {
            
            $newCount = 0;
            
            foreach ($filesObjs as $k => $file) {

                $contentId = getUIDAdd($k);
                $fileName = explode('-', $file);

                $contentData = array(
                    'CONTENT_ID'      => $contentId, 
                    'FILE_NAME'       => $fileName[1], 
                    'PHYSICAL_PATH'   => $fileName[1], 
                    'FILE_EXTENSION'  => 'jpg', 
                    'CREATED_DATE'    => $currentDate, 
                    'CREATED_USER_ID' => $sessionUserId, 
                    'IS_PHOTO'        => 1
                );

                $this->db->AutoExecute('ECM_CONTENT', $contentData);

                $contentMapData = array(
                    'ID'               => getUIDAdd($k), 
                    'CONTENT_ID'       => $contentId, 
                    'REF_STRUCTURE_ID' => $structureId, 
                    'RECORD_ID'        => $recordId, 
                    'CREATED_DATE'     => $currentDate, 
                    'CREATED_USER_ID'  => $sessionUserId, 
                    'ORDER_NUM'        => $fileName[0]
                );

                $this->db->AutoExecute('ECM_CONTENT_MAP', $contentMapData);    
                
                if (isset($isAllRowsBookTypeId)) {
                    $this->db->Execute("INSERT INTO META_DM_RECORD_MAP (ID, SRC_TABLE_NAME, SRC_RECORD_ID, TRG_TABLE_NAME, TRG_RECORD_ID) VALUES ($contentId, 'CVL_CIVIL_BOOK', $civilBookId, 'ECM_CONTENT', $contentId)"); 
                }
                
                if (isset($contentMapRecordId)) {
                    $newContentId = getUIDAdd($k);
                    $this->db->Execute("INSERT INTO ECM_CONTENT_MAP (ID, CONTENT_ID, REF_STRUCTURE_ID, RECORD_ID, CREATED_DATE, CREATED_USER_ID, FOLDER_ID, ORDER_NUM) VALUES ($newContentId, $contentId, $structureId, $contentMapRecordId, '$currentDate', $sessionUserId, $recordId, ".$fileName[0].")"); 
                }
                
                $newCount++;
            }
            
            $currentWfmStatusId = $_POST['selectedRow']['wfmstatusid'];
            
            if (isset(Mddoc::$paramData['checkwfmstatusid']) && Mddoc::$paramData['checkwfmstatusid'] != '') {
                $checkWfmStatusId = Mddoc::$paramData['checkwfmstatusid'];
            } else {
                $checkWfmStatusId = '1532504903194151';
            }
            
            if (Input::postCheck('nextWfmStatus') && ($currentWfmStatusId == '1530946042933028' || $currentWfmStatusId == $checkWfmStatusId)) {
                $this->load->model('mdobject', 'middleware/models/');
                
                if (isset(Mddoc::$paramData['nextwfmstatusid'])) {
                
                    $metaDataId = Mddoc::$paramData['dataViewId'];
                    $_POST['newWfmStatusid'] = Mddoc::$paramData['nextwfmstatusid'];

                } else {
                    
                    switch ($typeId) {
                        case '1':
                            
                            $metaDataId = '1528858041095420';
                            $_POST['newWfmStatusid'] = '1529373710120176';
                            break;
                        
                        case '2':
                        case '3':
                            
                            $metaDataId = '1533714393827725';
                            $_POST['newWfmStatusid'] = '1532504887691783';
                            break;
                        
                        case '4':
                            
                            $metaDataId = '1536131133813';
                            $_POST['newWfmStatusid'] = '1540182332232604';
                            break;
                    }
                    
                }

                $_POST['metaDataId'] = $metaDataId;
                $_POST['dataRow'] = $_POST['selectedRow'];
                $_POST['signerParams']['cyphertext'] = $newCount;
                $_POST['signerParams']['plainText'] = 'BulkReScan';

                $ss = $this->model->setRowWfmStatusModel();
            }
            
            return array('status' => 'success', 'message' => 'Success', 'post' => $_POST, 'ss' => $ss);
        } else {
            return array('status' => 'error', 'message' => 'Файл өөрчлөх явцад алдаа гарлаа.');
        }
    }
    
    public function getListDataModel($id) {
        
        $param = array(
            'systemMetaGroupId' => $id,
            'showQuery' => 0,
            'ignorePermission' => 1
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success') {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);            
            
            return $data['result'];
        }
        
        return array();
    }    
    
    public function getTreeListModel() {
        
        $param = array(
            'systemMetaGroupId' => ((Input::isEmpty('treeDvId') == false) ? Input::post('treeDvId') : '1540461766473'), // 1535007433998
            'showQuery' => 1, 
            'ignorePermission' => 1, 
            'criteria' => array(
                'companyId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('companyId')
                    )
                )
            )
        );
        
        if (Input::isEmpty('inputParams') == false) {
            
            parse_str(Input::post('inputParams'), $inputParams);
            
            foreach ($inputParams as $inputKey => $inputVal) {
                $param['criteria'][$inputKey][] = array(
                    'operator' => '=',
                    'operand' => Input::param($inputVal)
                );
            }
        }
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $sql      = $data['result'];
            $array    = array();
            $rows     = $this->db->GetAll($sql);
            
            if ($rows) {
                
                $firstRow = $rows[0];
            
                $array[] = array(
                    'companyKeyId' => $firstRow['COMPANYKEYID'], 
                    'id'           => $firstRow['ID'], 
                    'contentId'    => $firstRow['ID'], 
                    'isFile'       => 0, 
                    'name'         => $firstRow['COMPANYREGISTERNUMBER'].' ('.$firstRow['COMPANYNAME'].')', 
                    'physicalpath' => null,
                    'systemMetaGroupId' => '',
                );

                $groupedBookType = Arr::groupByArray($rows, 'BOOKTYPENAME');
                $idKey = 1;
                $bookTypeKey = 0;

                foreach ($groupedBookType as $bookTypeName => $bookTypeData) {

                    $idKey++;

                    $array[0]['children'][$bookTypeKey] = array(
                        'companyKeyId' => $bookTypeData['row']['COMPANYKEYID'], 
                        'contentId'    => issetParam($bookTypeData['row']['ID']), 
                        'id'           => $idKey, 
                        'isFile'       => 0, 
                        'name'         => $bookTypeName,
                        'physicalpath' => null,
                        'systemMetaGroupId' => $param['systemMetaGroupId'],
                    );

                    if (isset($bookTypeData['rows'])) {

                        $bookTypeRows       = $bookTypeData['rows'];
                        $groupedContentType = Arr::groupByArray($bookTypeRows, 'CONTENTTYPENAME');
                        $contentTypeKey     = 0;

                        foreach ($groupedContentType as $contentTypeName => $contentTypeData) {

                            $idKey++;
                            $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey] = array(
                                'companyKeyId' => $contentTypeData['row']['COMPANYKEYID'], 
                                'contentId'    => issetParam($contentTypeData['row']['ID']), 
                                'id'           => $idKey, 
                                'isFile'       => 0, 
                                'name'         => $contentTypeName, 
                                'physicalpath' => null,
                                'systemMetaGroupId' => $param['systemMetaGroupId'],
                            );

                            if (isset($contentTypeData['rows'])) {

                                $contentTypeRows = $contentTypeData['rows'];

                                foreach ($contentTypeRows as $contentKey => $contentData) {

                                    $idKey++;
                                    $nameInt = $contentKey + 1;

                                    $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey]['children'][$contentKey] = array(
                                        'companyKeyId' => $contentData['COMPANYKEYID'], 
                                        'contentId'    => $contentData['ID'], 
                                        'id'           => $idKey, 
                                        'isFile'       => 1, 
                                        'name'         => sprintf("%03d", $nameInt), 
                                        'physicalpath' => $contentData['PHYSICALPATH'],
                                        'systemMetaGroupId' => $param['systemMetaGroupId'],
                                    );
                                }
                            }

                            $contentTypeKey++;
                        }
                    }

                    $bookTypeKey++;
                }
            }
            
            return $array;
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }
    
    public function getTreeListV2Model() {
        //Input::post('parentId'), Input::post('companyId')
        
        if (Config::getFromCache('CIVIL_OFFLINE_SERVER') === '1') {
            includeLib('Utils/Functions');
            $postData = Input::postData();

            $param1 =   array (
                'stateregnumber' => issetParam($postData['selectedRow']['stateregnumber']), 
                'archivetypeid' => issetDefaultVal($postData['selectedRow']['archivetypeid'], '2'),
            );

            $civilPdata = Functions::runProcess('CVL_CIVIL_PACK_GET_LIST_008', $param1);
            $civilpackid = issetDefaultVal($civilPdata['result']['civilpackid'], $postData['selectedRow']['civilpackid']);

            $mainResult = Functions::runProcess('CVL_CR_PREVIEW_GET_LIST_008', array('civilpackid' => $civilpackid, 'bookTypeId' => $postData['selectedRow']['getbooktypeid']));
            
            if (issetParamArray($mainResult['result']['cvlinfocontrolcontenttypelist'])) {
                
                $rows = $mainResult['result']['cvlinfocontrolcontenttypelist'];
                
                $firstRow = isset($rows[0]) ? $rows[0] : array();
                $array    = array();
                
                $array[] = array(
                    'companyKeyId' => isset($firstRow['civilpackid']) ? $firstRow['civilpackid'] : '', 
                    'civilbookid'  => isset($firstRow['civilbookid']) ? $firstRow['civilpackid'] : '', 
                    'id'           => isset($firstRow['id']) ? $firstRow['id'] : '', 
                    'folderid'     => isset($firstRow['folderid']) ? $firstRow['folderid'] : '', 
                    'isFile'       => 0, 
                    'name'         => isset($firstRow['stateregnumber']) ? $firstRow['stateregnumber'].' ('.$firstRow['firstname'].')' : '', 
                    'physicalpath' => null
                );
                
                $groupedBookType = Arr::groupByArray($rows, 'archivetypename');
                $idKey = 1;
                $bookTypeKey = 0;
                
                foreach ($groupedBookType as $bookTypeName => $bookTypeData) {
                    
                    $idKey++;
                    
                    $array[0]['children'][$bookTypeKey] = array(
                        'companyKeyId' => $bookTypeData['row']['civilpackid'], 
                        'civilbookid'  => $bookTypeData['row']['civilbookid'], 
                        'folderid'     => isset($bookTypeData['folderid']) ? $bookTypeData['folderid'] : '', 
                        'id'           => $idKey, 
                        'isFile'       => 0, 
                        'name'         => $bookTypeName, 
                        'physicalpath' => null
                    );
                    
                    if (isset($bookTypeData['rows'])) {
                        
                        $bookTypeRows       = $bookTypeData['rows'];
                        $groupedContentType = Arr::groupByArray($bookTypeRows, 'booktypename');
                        $contentTypeKey     = 0;
    
                        foreach ($groupedContentType as $contentTypeName => $contentTypeData) {
                            
                            $idKey++;
                            $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey] = array(
                                'companyKeyId' => $contentTypeData['row']['civilpackid'], 
                                'civilbookid'  => $contentTypeData['row']['civilbookid'], 
                                'folderid'     => isset($contentTypeData['folderid']) ? $contentTypeData['folderid'] : '', 
                                'id'           => $idKey, 
                                'isFile'       => 0, 
                                'name'         => $contentTypeName, 
                                'physicalpath' => null
                            );
                            
                            if (isset($contentTypeData['rows'])) {
                                
                                $contentTypeRows = $contentTypeData['rows'];
                                
                                foreach ($contentTypeRows as $contentKey => $contentData) {
                                    
                                    $idKey++;
                                    $nameInt = $contentKey + 1;
                                    
                                    $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey]['children'][$contentKey] = array(
                                        'companyKeyId' => $contentData['civilpackid'], 
                                        'civilbookid'  => $contentData['civilbookid'], 
                                        'contentid'    => isset($contentData['contentid']) ? $contentData['contentid'] : '', 
                                        'folderid'     => isset($contentData['folderid']) ? $contentData['folderid'] : '', 
                                        'id'           => $idKey, 
                                        'isFile'       => 1, 
                                        'name'         => sprintf("%03d", $nameInt), 
                                        'physicalpath' => $contentData['physicalpath']
                                    );
                                }
                            }
                            
                            $contentTypeKey++;
                        }
                    }
                    
                    $bookTypeKey++;
                }
                
                return $array;
                
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($mainResult));
            }

        }
        else {
            $metaDataId = (Input::postCheck('type') && (Input::post('type') === '6' || Input::post('type') === '7')) ? '1537426653397' : '1537426634531';
            $param = array(
                'systemMetaGroupId' => $metaDataId,
                'showQuery' => 1, 
                'ignorePermission' => 1, 
                'criteria' => array(
                    'civilId' => array(
                        array(
                            'operator' => '=',
                            'operand' => Input::post('companyId')
                        )
                    )
                )
            );
            
            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
            if ($data['status'] == 'success' && isset($data['result'])) {
                
                $sql      = $data['result'];
                $rows     = $this->db->GetAll($sql);
                
                $firstRow = isset($rows[0]) ? $rows[0] : array();
                $array    = array();
                
                $array[] = array(
                    'companyKeyId' => isset($firstRow['CIVILPACKID']) ? $firstRow['CIVILPACKID'] : '', 
                    'civilbookid'  => isset($firstRow['CIVILBOOKID']) ? $firstRow['CIVILBOOKID'] : '', 
                    'id'           => isset($firstRow['ID']) ? $firstRow['ID'] : '', 
                    'folderid'     => isset($firstRow['FOLDERID']) ? $firstRow['FOLDERID'] : '', 
                    'isFile'       => 0, 
                    'name'         => isset($firstRow['STATEREGNUMBER']) ? $firstRow['STATEREGNUMBER'].' ('.$firstRow['FIRSTNAME'].')' : '', 
                    'physicalpath' => null
                );
                
                $groupedBookType = Arr::groupByArray($rows, 'ARCHIVETYPENAME');
                $idKey = 1;
                $bookTypeKey = 0;
                
                foreach ($groupedBookType as $bookTypeName => $bookTypeData) {
                    
                    $idKey++;
                    
                    $array[0]['children'][$bookTypeKey] = array(
                        'companyKeyId' => $bookTypeData['row']['CIVILPACKID'], 
                        'civilbookid'  => $bookTypeData['row']['CIVILBOOKID'], 
                        'folderid'     => isset($bookTypeData['FOLDERID']) ? $bookTypeData['FOLDERID'] : '', 
                        'id'           => $idKey, 
                        'isFile'       => 0, 
                        'name'         => $bookTypeName, 
                        'physicalpath' => null
                    );
                    
                    if (isset($bookTypeData['rows'])) {
                        
                        $bookTypeRows       = $bookTypeData['rows'];
                        $groupedContentType = Arr::groupByArray($bookTypeRows, 'BOOKTYPENAME');
                        $contentTypeKey     = 0;
    
                        foreach ($groupedContentType as $contentTypeName => $contentTypeData) {
                            
                            $idKey++;
                            $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey] = array(
                                'companyKeyId' => $contentTypeData['row']['CIVILPACKID'], 
                                'civilbookid'  => $contentTypeData['row']['CIVILBOOKID'], 
                                'folderid'     => isset($contentTypeData['FOLDERID']) ? $contentTypeData['FOLDERID'] : '', 
                                'id'           => $idKey, 
                                'isFile'       => 0, 
                                'name'         => $contentTypeName, 
                                'physicalpath' => null
                            );
                            
                            if (isset($contentTypeData['rows'])) {
                                
                                $contentTypeRows = $contentTypeData['rows'];
                                
                                foreach ($contentTypeRows as $contentKey => $contentData) {
                                    
                                    $idKey++;
                                    $nameInt = $contentKey + 1;
                                    
                                    $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey]['children'][$contentKey] = array(
                                        'companyKeyId' => $contentData['CIVILPACKID'], 
                                        'civilbookid'  => $contentData['CIVILBOOKID'], 
                                        'contentid'    => isset($contentData['CONTENTID']) ? $contentData['CONTENTID'] : '', 
                                        'folderid'     => isset($contentData['FOLDERID']) ? $contentData['FOLDERID'] : '', 
                                        'id'           => $idKey, 
                                        'isFile'       => 1, 
                                        'name'         => sprintf("%03d", $nameInt), 
                                        'physicalpath' => $contentData['PHYSICALPATH']
                                    );
                                }
                            }
                            
                            $contentTypeKey++;
                        }
                    }
                    
                    $bookTypeKey++;
                }
                
                return $array;
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
        }
    }
    
    public function getTreeListV3Model() {
        //Input::post('parentId'), Input::post('companyId')
        $param = array(
            'systemMetaGroupId' => '1537426653397',
            'showQuery' => 1, 
            'ignorePermission' => 1, 
            'criteria' => array(
                'civilId' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('companyId')
                    )
                )
            )
        );
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $sql      = $data['result'];
            $rows     = $this->db->GetAll($sql);
            
            $firstRow = $rows[0];
            $array    = array();
            
            $array[] = array(
                'companyKeyId' => $firstRow['CIVILPACKID'], 
                'id'           => $firstRow['ID'], 
                'isFile'       => 0, 
                'name'         => $firstRow['STATEREGNUMBER'].' ('.$firstRow['FIRSTNAME'].')', 
                'selectedRow'  => isset($firstRow) ? Arr::changeKeyLower($firstRow) : array(),
                'physicalpath' => null
            );
            
            $groupedBookType = Arr::groupByArray($rows, 'ARCHIVETYPENAME');
            $idKey = 1;
            $bookTypeKey = 0;
            
            foreach ($groupedBookType as $bookTypeName => $bookTypeData) {
                
                $idKey++;
                
                $array[0]['children'][$bookTypeKey] = array(
                    'companyKeyId' => $bookTypeData['row']['CIVILPACKID'], 
                    'id'           => $idKey, 
                    'isFile'       => 0, 
                    'name'         => $bookTypeName, 
                    'selectedRow'  => isset($bookTypeData['row']) ? $bookTypeData['row'] : array(),
                    'physicalpath' => null
                );
                
                if (isset($bookTypeData['rows'])) {
                    
                    $bookTypeRows       = $bookTypeData['rows'];
                    $groupedContentType = Arr::groupByArray($bookTypeRows, 'BOOKTYPENAME');
                    $contentTypeKey     = 0;

                    foreach ($groupedContentType as $contentTypeName => $contentTypeData) {
                        
                        $idKey++;
                        $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey] = array(
                            'companyKeyId' => $contentTypeData['row']['CIVILPACKID'], 
                            'id'           => $idKey, 
                            'isFile'       => 0, 
                            'name'         => $contentTypeName, 
                            'selectedRow'  => isset($contentTypeData['row']) ? Arr::changeKeyLower($contentTypeData['row']) : array(),
                            'physicalpath' => null
                        );
                        
                        if (isset($contentTypeData['rows'])) {
                            
                            $contentTypeRows = $contentTypeData['rows'];
                            
                            foreach ($contentTypeRows as $contentKey => $contentData) {
                                
                                $idKey++;
                                $nameInt = $contentKey + 1;
                                
                                $array[0]['children'][$bookTypeKey]['children'][$contentTypeKey]['children'][$contentKey] = array(
                                    'companyKeyId' => $contentData['CIVILPACKID'], 
                                    'id'           => $idKey, 
                                    'isFile'       => 1, 
                                    'name'         => sprintf("%03d", $nameInt), 
                                    'selectedRow'  => isset($contentData) ? Arr::changeKeyLower($contentData) : array(), 
                                    'physicalpath' => $contentData['PHYSICALPATH']
                                );
                            }
                        }
                        
                        $contentTypeKey++;
                    }
                }
                
                $bookTypeKey++;
            }
            
            return $array;
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }
    
    public function electronViewTreeDataV2Model($departmentId = null, $companyId= '', $notParent = '', $rows = array(), $deep = 0) {
        
        if ($deep === 0) {
            $criteriaValue = array(
                array(
                    'operator' => 'IS NULL',
                    'operand' => ''
                )
            );
            if ($departmentId) {
                $criteriaValue = array(
                    array(
                        'operator' => '=',
                        'operand' => $departmentId
                    )
                );
            }

            $departmentList = array();
            $this->load->model('mdmetadata', 'middleware/models/');
            $getMetaDataId = $this->model->getMetaDataByCodeModel('cvlServiceTypeContentList');            

            if ($departmentId) {
                $param = array(
                    'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'criteria' => array(
                        'parentId' =>  array(
                            array(
                                'operator' => '=',
                                'operand' => $departmentId
                            )
                        ),
                        'civilId' =>  array(
                            array(
                                'operator' => '=',
                                'operand' => $companyId
                            )
                        ),
                    )
                );
            } else {
                $param = array(
                    'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'criteria' => array(
                        'civilId' =>  array(
                            array(
                                'operator' => '=',
                                'operand' => $companyId
                            )
                        )
                    )
                );
            }

            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            if (count($data['result']) == 0) {
                $param['criteria'] = array(
                    'parentId' => array()
                );
                $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            }

            if ($data['status'] === 'success' && isset($data['result'])) {
                
                $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                if (false) {
                    $parentIds = array();
                    $childIds = array();

                    foreach ($data['result'] as $dk => $dataRow) {
                        
                        if (isset($dataRow['childrecordcount'])) {
                            $data['result'][$dk]['state'] = 'closed';
                            $parentIds[] = $data['result'][$dk]['id'];
                        } else {
                            $data['result'][$dk]['state'] = 'open';
                        }

                        if ($data['result'][$dk]['parentid'] != '') {
                            $childIds[] = array(
                                'k' => $dk, 
                                'parentId' => $data['result'][$dk]['parentid']
                            );
                        }
                    }

                    if ($parentIds && $childIds) {

                        foreach ($childIds as $childId) {

                            if (in_array($childId['parentId'], $parentIds)) {
                                unset($data['result'][$childId['k']]);
                                $result['total'] = $result['total'] - 1;
                            }
                        }

                        $data['result'] = array_values($data['result']);
                    }
                }

                $departmentList = $data['result'];
            }
        } else {
            $departmentList = $rows;
        }

        $response = array();
        
        if ($departmentList) {
            
            $departmentList = Arr::naturalsort($departmentList, 'ordernum');
            
            foreach ($departmentList as $row) {
                if (!array_find_val($departmentList, 'id', $row['parentid'])) {
                    $row['parentid'] = null;
                }

                if ($row['parentid'] == $departmentId) {
                    $response[] = array(
                        'name' => $row['name'],
                        'id'       => $row['id'],
                        'companyKeyId' => $row['civilbookid'],
                        'civilpackid' => $row['civilpackid'],
                        'physicalpath' => $row['physicalpath'],
                        'isFile' => $row['isfile'],
                        'children' => $this->getListJtreeDataV2Model($row['id'], $companyId, '', $departmentList, 1)
                    );                        
                }
            }
        }
        
        return $response;
    }    
    
    public function getListJtreeDataV2Model($departmentId = null, $companyId= '', $notParent = '', $rows = array(), $deep = 0) {
        
        if ($deep === 0) {
            
            $criteriaValue = array(
                array(
                    'operator' => 'IS NULL',
                    'operand' => ''
                )
            );
            
            if ($departmentId) {
                $criteriaValue = array(
                    array(
                        'operator' => '=',
                        'operand' => $departmentId
                    )
                );
            }

            $departmentList = array();
            $this->load->model('mdmetadata', 'middleware/models/');
            $getMetaDataId = $this->model->getMetaDataByCodeModel('cvlServiceTypeContentList');            

            if ($departmentId) {
                $param = array(
                    'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'criteria' => array(
                        'parentId' =>  array(
                            array(
                                'operator' => '=',
                                'operand' => $departmentId
                            )
                        ),
                        'companyId' =>  array(
                            array(
                                'operator' => '=',
                                'operand' => $companyId
                            )
                        ),
                    )
                );
            } else {
                $param = array(
                    'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'criteria' => array(
                        'companyId' =>  array(
                            array(
                                'operator' => '=',
                                'operand' => $companyId
                            )
                        )
                    )
                );
            }

            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            if (count($data['result']) == 0) {
                $param['criteria'] = array(
                    'parentId' => array()
                );
                $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            }

            if ($data['status'] === 'success' && isset($data['result'])) {
                $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                //if (count($data['result']) > 0) {
                if (false) {
                    $parentIds = array();
                    $childIds = array();

                    foreach ($data['result'] as $dk => $dataRow) {
                        if (isset($dataRow['childrecordcount'])) {
                            $data['result'][$dk]['state'] = 'closed';
                            $parentIds[] = $data['result'][$dk]['id'];
                        } else {
                            $data['result'][$dk]['state'] = 'open';
                        }

                        if ($data['result'][$dk]['parentid'] != '') {
                            $childIds[] = array(
                                'k' => $dk, 
                                'parentId' => $data['result'][$dk]['parentid']
                            );
                        }
                    }

                    if ($parentIds && $childIds) {

                        foreach ($childIds as $childId) {

                            if (in_array($childId['parentId'], $parentIds)) {
                                unset($data['result'][$childId['k']]);
                                $result['total'] = $result['total'] - 1;
                            }
                        }

                        $data['result'] = array_values($data['result']);
                    }
                }

                $departmentList = $data['result'];
            }
        } else {
            $departmentList = $rows;
        }

        $response = array();
        
        if ($departmentList) {
            
            foreach ($departmentList as $row) {
                
                if (!array_find_val($departmentList, 'id', $row['parentid'])) {
                    $row['parentid'] = null;
                }

                if ($row['parentid'] == $departmentId) {
                    $response[] = array(
                        'name'     => $row['name'],
                        'id'       => $row['id'],
                        'companyKeyId' => $row['civilbookid'],
                        'civilpackid' => $row['civilpackid'],
                        'physicalpath' => $row['physicalpath'],
                        'isFile' => $row['isfile'],
                        'children' => $this->getListJtreeDataV2Model($row['id'], $companyId, '', $departmentList, 1)
                    );                        
                }

            }
        }
        return $response;
    }    
    
    public function getBookContenDataModel($id, $type = '0') {
        if ($type) {
            return $this->db->GetRow("SELECT * FROM CVL_CIVIL_BOOK WHERE ID = $id");
        } else {
            $sql = "SELECT 
                        'id='||ID||'&'||
                        'bookTypeId='||BOOK_TYPE_ID||'&'||
                        'civilPackId='||CIVIL_PACK_ID||'&'||
                        'bookDate='||BOOK_DATE||'&'||
                        'bookNumber='||BOOK_NUMBER||'&'||
                        'familyName='||FAMILY_NAME||'&'||
                        'lastName='||LAST_NAME||'&'||
                        'firstName='||FIRST_NAME||'&'||
                        'stateRegNumber='||STATE_REG_NUMBER||'&'||
                        'genderId='||GENDER_ID||'&'||
                        'originId='||ORIGIN_ID||'&'||
                        'dateOfBirth='||DATE_OF_BIRTH||'&'||
                        'city='||CITY||'&'||
                        'district='||DISTRICT||'&'||
                        'street='||STREET||'&'||
                        'birthCity='||BIRTH_CITY||'&'||
                        'birthDistrict='||BIRTH_DISTRICT||'&'||
                        'birthStreet='||BIRTH_STREET||'&'||
                        'wifeLastname='||WIFE_LASTNAME||'&'||
                        'wifeFirstname='||WIFE_FIRSTNAME||'&'||
                        'wifeRegNumber='||WIFE_REG_NUMBER||'&'||
                        'wifeBirthdate='||WIFE_BIRTHDATE||'&'||
                        'wifeCity='||WIFE_CITY||'&'||
                        'wifeDistrict='||WIFE_DISTRICT||'&'||
                        'wifeStreet='||WIFE_STREET||'&'||
                        'husbandLastname='||HUSBAND_LASTNAME||'&'||
                        'husbandFirstname='||HUSBAND_FIRSTNAME||'&'||
                        'husbandRegNumber='||HUSBAND_REG_NUMBER||'&'||
                        'husbandBirthdate='||HUSBAND_BIRTHDATE||'&'||
                        'husbandCity='||HUSBAND_CITY||'&'||
                        'husbandDistrict='||HUSBAND_DISTRICT||'&'||
                        'husbandStreet='||HUSBAND_STREET||'&'||
                        'motherLastname='||MOTHER_LASTNAME||'&'||
                        'motherFirstname='||MOTHER_FIRSTNAME||'&'||
                        'motherRegNumber='||MOTHER_REG_NUMBER||'&'||
                        'spouseLastname='||SPOUSE_LASTNAME||'&'||
                        'spouseFirstname='||SPOUSE_FIRSTNAME||'&'||
                        'spouseRegNumber='||SPOUSE_REG_NUMBER||'&'||
                        'adoptMotherLastname='||ADOPT_MOTHER_LASTNAME||'&'||
                        'adoptMotherFirstname='||ADOPT_MOTHER_FIRSTNAME||'&'||
                        'adoptMotherRegNumber='||ADOPT_MOTHER_REG_NUMBER||'&'||
                        'adoptSpouseLastname='||ADOPT_SPOUSE_LASTNAME||'&'||
                        'adoptSpouseFirstname='||ADOPT_SPOUSE_FIRSTNAME||'&'||
                        'adoptSpouseRegNumber='||ADOPT_SPOUSE_REG_NUMBER||'&'||
                        'previousLastname='||PREVIOUS_LASTNAME||'&'||
                        'previousFirstname='||PREVIOUS_FIRSTNAME||'&'||
                        'previousRegNumber='||PREVIOUS_REG_NUMBER||'&'||
                        'changedLastname='||CHANGED_LASTNAME||'&'||
                        'changedFirstname='||CHANGED_FIRSTNAME||'&'||
                        'changedRegNumber='||CHANGED_REG_NUMBER||'&'||
                        'passportNumber='||PASSPORT_NUMBER||'&'||
                        'citizenCardNumber='||CITIZEN_CARD_NUMBER||'&'||
                        'birthCertNumber='||BIRTH_CERT_NUMBER||'&'||
                        'foriegnPassportNumber='||FORIEGN_PASSPORT_NUMBER||'&'||
                        'marriageCertNumber='||MARRIAGE_CERT_NUMBER||'&'||
                        'deathCertNumber='||DEATH_CERT_NUMBER||'&'||
                        'createdDate='||CREATED_DATE||'&'||
                        'createdUserId='||CREATED_USER_ID||'&'||
                        'foreignPassportIssueDate='||FOREIGN_PASSPORT_ISSUE_DATE||'&'||
                        'foreignPassportExpireDate='||FOREIGN_PASSPORT_EXPIRE_DATE||'&'||
                        'deathDat='||DEATH_DATE||''
                    FROM  CVL_CIVIL_BOOK WHERE ID = $id";

            return $this->db->GetOne($sql);
            
        }
    }
    
    public function getBookContenMarrigeDataModel($id) {
        return $this->db->GetAll("SELECT
                                    *
                                    FROM CVL_CIVIL_BOOK_DTL WHERE CIVIL_BOOK_ID = $id
                                    ORDER BY REG_DATE DESC");
    }
    
    public function getPackContenMarrigeDataModel($id) {
        return $this->db->GetAll("SELECT
                                    *
                                    FROM CVL_MARRIAGE_BOOK WHERE CIVIL_ID = $id
                                    ORDER BY REG_DATE DESC");
    }
    
    public function getAirSmsTypeModel() {
        return $this->db->GetAll("SELECT ID, NAME FROM AIR_MSG_TEMPLATE");
    }
    
    public function saveCvlViewLogModel($selectRow) {
        try {
            $data = array(
                'ID' => getUID(),
                'CIVIL_PACK_ID' => $selectRow['id'],
                'CIVIL_ID' => $selectRow['civilid'],
                'FILTER_LOG_ID' => isset($selectRow['filterlogid']) ? $selectRow['filterlogid'] : '',
                'BOOK_TYPE_ID' => $selectRow['booktypeid'],
                'ARCHIVE_TYPE_ID' => $selectRow['archivetypeid'],
                'BOOK_TYPE_NAME' => $selectRow['booktypename'],
                'STATE_REG_NUMBER' => $selectRow['stateregnumber'],
                'LAST_NAME' => $selectRow['lastname'],
                'FIRST_NAME' => $selectRow['firstname'],
                'CREATED_DATE' => Date::currentDate(),
                'CREATED_USER_ID' => Ue::sessionUserKeyId(),

            );

            $this->db->AutoExecute('CVL_VIEW_LOG', $data);    
        } catch (Exception $ex) {
            
        }
        
    }
    
    public function saveCvlContentViewLogModel($postData) {
        try {
            $bookTypeId = $postData['selectedRow']['booktypeid'];
            $bookTypeName = $postData['selectedRow']['booktypename'];
            $contentid = '';

            if (isset($postData['cvlBookId']) && $postData['cvlBookId']) {

                includeLib('Utils/Functions');
                $result = Functions::runProcess('cvl_Book_001_004', array('civilBookId' => $postData['cvlBookId'], 'contentId' => $postData['cvlContentId']));

                if (isset($result['result']) && $result['result']) {
                    $getBookData = $result['result'];
                }

                if (isset($getBookData['booktypeid']) && $getBookData['booktypeid']) {
                    $bookTypeId = $getBookData['booktypeid'];
                }

                if (isset($getBookData['booktypename']) && $getBookData['booktypename']) {
                    $bookTypeName = $getBookData['booktypename'];
                }

                if (isset($getBookData['contentid']) && $getBookData['contentid']) {
                    $contentid = $getBookData['contentid'];
                }
            }

            $data = array(
                'ID' => getUID(),
                'BOOK_TYPE_ID' => $bookTypeId,
                'BOOK_TYPE_NAME' => $bookTypeName,
                'CONTENT_ID' => $contentid,
                'CIVIL_PACK_ID' => $postData['selectedRow']['id'],
                'CIVIL_ID' => $postData['selectedRow']['civilid'],
                'FILTER_LOG_ID' => isset($postData['selectedRow']['filterlogid']) ? $postData['selectedRow']['filterlogid'] : '',
                'CIVIL_BOOK_ID' => isset($postData['cvlBookId']) ? $postData['cvlBookId'] : '',
                'ARCHIVE_TYPE_ID' => $postData['selectedRow']['archivetypeid'],
                'ARCHIVE_TYPE_NAME' => $postData['selectedRow']['archivetypename'],
                'STATE_REG_NUMBER' => $postData['selectedRow']['stateregnumber'],
                'LAST_NAME' => $postData['selectedRow']['lastname'],
                'FIRST_NAME' => $postData['selectedRow']['firstname'],
                'CREATED_DATE' => Date::currentDate(),
                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
            );
            $this->db->AutoExecute('CVL_CONTENT_LOG', $data);
        } catch (Exception $ex) {
            
        }
        
    }
    
    public function documentEcmMapListModel() {
        $join = '';
        if ($refStructureId = Input::numeric('refStructureId')) {
            $join = " AND CM.REF_STRUCTURE_ID = " . $refStructureId;
        }
        
        $rowId = Input::numeric('rowId');
        
        if ($rowId) {
            return $this->db->GetAll("".
                "SELECT
                    EC.CONTENT_ID,
                    EC.FILE_NAME,
                    EC.PHYSICAL_PATH,
                    EC.FILE_EXTENSION,
                    CT.NAME AS CONTENT_TYPE_NAME,
                    EC.TYPE_ID AS CONTENT_TYPE_ID,
                    CM.ORDER_NUM, 
                    CM.ID
                FROM ECM_CONTENT_MAP CM 
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID
                    LEFT JOIN ECM_CONTENT_TYPE CT ON CT.ID = EC.TYPE_ID 
                WHERE CM.RECORD_ID = $rowId 
                    $join
                ORDER BY CT.NAME DESC"
            );
        }
        
        return array();
    }   

    public function savedocCommentLookupModel($code, $data) {
        $inparamGroup = array();

        foreach ($data['userId'] as $key => $row) {
            array_push($inparamGroup, array(
                'recordId' => $data['recordId'],
                'wfmstatusId' => $data['wfmstatusId'],
                'description' => $data['description'][$key],
                'userId' => $row,
                'assigneduserid' => Ue::sessionUserKeyId()
            ));
        }
        $inparams = array(
            'id' => $data['recordId'],
            'wfmstatusId' => $data['wfmstatusId'],
            'DM_WFM_ASSIGNMENT_DV' => $inparamGroup
        );

        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, $code, $inparams);

        return $data;
    }    

    public function docCommentLookupModel($id, $criteria = false) {
        $datas = array();
        $param = array(
            'systemMetaGroupId' => $id,
            'showQuery' => 0, 
            'ignorePermission' => 1
        );

        if ($criteria) {
            $param['criteria'] = $criteria;
        }

        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);        
            $datas = $data['result'];
        }

        return $datas;
    }    

    public function getTemplateFileDocComment($typeid) {
        return $this->db->GetOne("SELECT ATTACH_FILE FROM DOC_TEMPLATE WHERE DOC_TEMPLATE_ID = " .  $typeid);
    }

    public function docParagraphCreateModel() {
        $fileDtls = array();
        $fileParam = array();

        $inparams = array(
            'documentIndex' => Input::post('documentIndex'),
            'documentCode' => Input::post('documentCode'),
            'documentTypeId' => Input::post('commentLookup1'),
            'directionId' => Input::post('directionId'),
            'documentNumber' => Input::post('documentNumber'),
            'documentName' => Input::post('documentName'),
            'customerId' => Input::post('customerId'),
            'createdDate' => Input::post('createdDate'),
            'departmentId' => Input::post('departmentId'),
            'userDepartmentAddress' => Input::post('userDepartmentAddress'),
            'userDepartmentRegistrationNumber' => Input::post('userDepartmentRegistrationNumber'),
            'isEDoc' => Input::post('isEDoc'),
            'responseDate' => Input::post('responseDate'),
            'priorityId' => Input::post('priorityId'),
            'createdUserId' => Ue::sessionUserKeyId(),
            'DOC_PARAGRAPH' => array(
                array(
                    'paragraphText' => Input::post('docParagraph'),
                    'createdDate' => Date::currentDate(),
                    'createdUserId' => Ue::sessionUserKeyId()
                )
            )

        );

        if (!empty($_FILES) && isset($_FILES['activity_file'])) {
            $fileData = $_FILES['activity_file'];

            foreach ($fileData['name'] as $key => $fileRow) {
                if (is_uploaded_file($fileData['tmp_name'][$key])) {

                    $newFileName   = 'file_' . getUID() . '_' . $key;
                    $fileExtension = strtolower(substr($fileRow, strrpos($fileRow, '.') + 1));
                    $fileName      = $newFileName . '.' . $fileExtension;
                    $filePath      = UPLOADPATH . 'process/';
                    FileUpload::SetFileName($fileName);
                    FileUpload::SetTempName($fileData['tmp_name'][$key]);
                    FileUpload::SetUploadDirectory($filePath);
                    FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                    FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
                    $uploadResult  = FileUpload::UploadFile();

                    if ($uploadResult) {
                        $fileParam['physicalPath']       = $filePath . $fileName;
                        $fileParam['fileSize']       = $fileData['size'][$key];
                        $fileParam['fileExtension']  = $fileExtension;
                        $fileParam['fileName'] = $fileRow;
                        array_push($fileDtls, array('ecmContent' => $fileParam));
                    }
                }
            }

            if (!empty($fileDtls)) $inparams['ecmContentMap'] = $fileDtls;
        }

        $saveParagraph = $this->ws->runResponse(GF_SERVICE_ADDRESS, "DOC_PLAN_DV_0011", $inparams);

        if ($saveParagraph['status'] === 'success') {
            return array(
                'status' => 'success',
                'message' => 'Амжилттай хадгалагдлаа'
            );
        } else {
            return array(
                'status' => 'error',    
                'message' => $saveParagraph['text']
            );            
        }        
    }    

    public function getFileDocComment($row) {
        $inparams = array(
            'id' => $row['id']
        );
        $getFile = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getParagraphParentId_004", $inparams);
        $getRow = array();

        if ($getFile['status'] === 'success') {
            return $getRow = $getFile['result'];
        }

        return $getRow;
    }    

    public function docCommentCreateModel($content, $uniqid, $reply = '') {
        pa($_POST);
        $inparams = array(
            'recordId' => $uniqid,
            'commentText' => $content,
            'isReply' => $reply,
            'createdDate' => Date::currentDate(),
            'createdUserId' => Ue::sessionUserKeyId()
        );
        $saveParagraph = $this->ws->runResponse(GF_SERVICE_ADDRESS, "DOC_PARAGRAPH_COMMENT_001", $inparams);

        if ($saveParagraph['status'] === 'success') {
            return array(
                'status' => 'success',
                'message' => 'Амжилттай хадгалагдлаа'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => $saveParagraph['text']
            );            
        }        
    }    

    public function getDocCommentsModel($id) {
        $inparams = array(
            'paraid' => $id
        );
        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getCommentHdr_004", $inparams);
        $getRow = array();

        if ($get['status'] === 'success') {
            return $getRow = $get['result'];
        }

        return $getRow;
    }    

    public function getDocMoreModel($id) {
        $inparams = array(
            'docid' => $id
        );

        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "docDocumentViewGetList_004", $inparams);
        $getRow = array();

        if ($get['status'] === 'success') {
            $getRow = $get['result'];
        }

        return $getRow;
    }    

    public function getDocMoreArchiveModel($id) {
        $inparams = array(
            'docid' => $id
        );
        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "archiveDocumentViewGetList_004", $inparams);
        $getRow = array();

        if ($get['status'] === 'success') {
            return $getRow = $get['result'];
        }

        return $getRow;
    }   

    public function getDocMoreModelBaseInfo($id) {
        $inparams = array(
            'docid' => $id
        );
        
        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "docDocumentViewGetListBase_004", $inparams);
        $getRow = array();

        if ($get['status'] === 'success') {
            return $getRow = $get['result'];
        }

        return $getRow;
    }    

    public function getDocMoreArchiveModelBaseInfo($id) {
        $inparams = array(
            'docid' => $id
        );
        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "archiveDocumentViewGetListBase_004", $inparams);
        $getRow = array();

        if ($get['status'] === 'success') {
            return $getRow = $get['result'];
        }

        return $getRow;
    }    

    public function getShowPostponeHistory($id) {
        $inparams = array(
            'docid' => $id
        );
        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "DOC_DOCUMENT_ACTIVITY_LIST_COUNT_004", $inparams);
        $getRow = array();

        if ($get['status'] === 'success') {
            return $getRow = $get['result'];
        }

        return $getRow;
    }   
          
    public function getDocStampPicModel($id) {
        $inparams = array(
            'id' => $id
        );
        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "DOC_LIST_SIGN_STAMP_004", $inparams);
        $getRow = array();
        if ($get['status'] === 'success') {
            return $getRow = $get['result'];
        }
        return $getRow;
    }

    public function docParagraphChildCreateModel($content, $id) {
        $inparams = array(
            'id' => $id,
            'paragraphText' => $content,
            'createdDate' => Date::currentDate(),
            'createdUserId' => Ue::sessionUserKeyId()
        );
        $saveParagraph = $this->ws->runResponse(GF_SERVICE_ADDRESS, "DOC_PARAGRAPH_PARENT_DV_001", $inparams);

        if ($saveParagraph['status'] === 'success') {
            return array(
                'status' => 'success',
                'message' => 'Амжилттай хадгалагдлаа'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => $saveParagraph['text']
            );            
        }        
    }     
    
    public function saveScSmsModel() {
        
        $response = array(
            'status' => 'error',
            'message' => 'Алдаатай өгөгдөл байна. Шалгаад дахин оролдлого хийнэ үү'
        );
        
        try {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $currentDate = Date::currentDate();
            $postData = Input::postData();
            
            $airSmsDataReplace = preg_replace("/\n/", "#break#", $postData['screenCaptureSms']);
            $airArrData = explode('#break#', $airSmsDataReplace);
            
            $index = 1;
            
            if (!is_numeric(substr(Str::sanitize($airArrData[1]), 0, 1))) {
                throw new Exception(Lang::line('air_sms_throw_002')); 
                exit();
            }
            
            (Array) $alteaSmsArr = array();
            (Boolean) $tick = $ticketHeader = $resultHdr = false;
            
            switch ($postData['smsTypeId']) {
                case '1':
                    if ($airArrData) {
                        foreach ($airArrData as $key => $row) {
                            $ticket = strpos($row, ' AP ');
                            if ($ticket !== false && $ticket === (int) 1) {
                                $tick = true;
                            } else {
                                if (!$tick) {
                                    array_push($alteaSmsArr, $row);
                                }
                            }
                        }
                    }
                    
                    if (isset($alteaSmsArr[0])) {

                        /*  begin header information */

                        $headerInformation = explode(' ', $alteaSmsArr[0]);
                        
                        if ($headerInformation) 
                        
                        (Array) $headerInfoArr = array();

                        foreach ($headerInformation as $row) {
                            if ($row) {
                                array_push($headerInfoArr, $row);
                            }
                        }

                        $smsId = getUID();
                        
                        (Array) $inputParam = array(
                                                    'ID' => $smsId, 
                                                    'CREATED_USER_ID' => $sessionUserKeyId,
                                                    'GDS' => 'altea',
                                                    'CREATED_DATE' => $currentDate
                                                );

                        if (isset($headerInfoArr[0])) {
                            $subOidAndOid = explode('/', $headerInfoArr[0]);
                            
                            if (Str::upper($subOidAndOid[0]) !== 'RP') {
                                throw new Exception(Lang::line('air_sms_throw_001')); 
                                exit();
                            }
                            
                            if (sizeof($subOidAndOid) > 2) {
                                $inputParam['SUB_OID'] = $subOidAndOid[1]; /* $subOidAndOid[0] . '/' .  */
                                $inputParam['OID'] = $subOidAndOid[2];
                            } else {
                                if (sizeof($subOidAndOid) == 2) {
                                    $inputParam['SUB_OID'] = $subOidAndOid[0] . '/' . $subOidAndOid[1];
                                } else {
                                    $inputParam['SUB_OID'] = $headerInfoArr[0];
                                }
                            }
                        }

                        if (isset($headerInfoArr[1])) {
                            $ticketHeader = true;
                            $issuedAgentArr = explode('/', $headerInfoArr[1]);
                            $inputParam['ISSUED_AGENT_SIGN'] = $issuedAgentArr[0];
                        }

                        if (isset($headerInfoArr[2])) {
                            $ticketHeader = true;
                            $issuedDateArr = explode('/', $headerInfoArr[2]);
                            $inputParam['ISSUE_DATE'] = $issuedDateArr[0];
                        }

                        if (isset($headerInfoArr[3])) {
                            $ticketHeader = true;
                            $inputParam['PNR'] = $headerInfoArr[3];
                        }

                        $resultHdr = $this->db->AutoExecute('AIR_MESSAGE_HEADER', $inputParam);
                        $this->db->UpdateClob('AIR_MESSAGE_HEADER', 'AIR_MESSAGE', $postData['screenCaptureSms'], " ID = '$smsId' ");
                        /*  end header information */

                        if ($ticketHeader && $resultHdr) {

                            (Array) $airPersonInfoArr = $airLineInfoArr = array();
                            (Array) $airTkeyInfoArr = $airKnKeyInfoArr = $airTaxKeyInfoArr = $airConKeyInfoArr = $airFfKeyInfoArr = array();
                            
                            foreach ($alteaSmsArr as $key => $sms) {
                                /* {2} */
                                $icheck = Str::sanitize(substr($sms, 0, 4));
                                $ticket = strpos($icheck, '.');
                                
                                if ($ticket === false && is_numeric($icheck)) {
                                    array_push($airLineInfoArr, $sms);
                                } elseif (preg_match("/^  +[0-9.]+[a-zA-Z0-9-]/", $sms)) {
                                    array_push($airPersonInfoArr, $sms);
                                } elseif (strpos($sms, 'T-') !== false) {
                                    $stmp = preg_replace('/T-/', '', $sms, 1);
                                    $stmp = explode(';', $stmp, 2); 
                                    
                                    if ($stmp) {
                                        $tmpCode = str_replace(' ', '', $stmp[0]);
                                        $dType = '';
                                        switch ($tmpCode) {
                                            case 'TKTT':
                                                $dType = '7A';
                                                break;
                                            case 'EMD':
                                                $dType = '7D';
                                                break;
                                            case 'RFND':
                                                $dType = 'RF';
                                                break;
                                            case 'CANX':
                                                $dType = 'MA';
                                                break;
                                            
                                        }
                                        $airTkeyInfoArr = array($dType, issetParam($stmp[1]));
                                    }

                                } elseif (strpos($sms, 'KN-') !== false) {
                                    $stmp = preg_replace('/KN-/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airKnKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'TAX') !== false) {
                                    $stmp = preg_replace('/TAX/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airTaxKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'COM') !== false) {
                                    $stmp = preg_replace('/COM/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airConKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'FF') !== false) {
                                    $stmp = preg_replace('/FF/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airFfKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                }

                            }

                            $totalSms = '0';
                            (Array) $amountArr = array();
                            if (issetParam($airKnKeyInfoArr['1']) !== '') {
                                $amountArr = explode(';', $airKnKeyInfoArr['1']);
                                foreach ($amountArr as $key => $row) {
                                    $totalSms += (float) $row;
                                }
                            }
                            
                            (Array) $amountConArr = array();
                            if (issetParam($airConKeyInfoArr['1']) !== '') {
                                $amountConArr = explode(';', $airConKeyInfoArr['1']);
                                /* foreach ($amountConArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $amountTaxArr = array();
                            if (issetParam($airTaxKeyInfoArr['1']) !== '') {
                                $amountTaxArr = explode(';', $airTaxKeyInfoArr['1']);
                                /* foreach ($amountTaxArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $amountFfArr = array();
                            if (issetParam($airFfKeyInfoArr['1']) !== '') {
                                $amountFfArr = explode(';', $airFfKeyInfoArr['1']);
                                /* foreach ($amountFfArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $tickNumberArr = array();
                            if (issetParam($airTkeyInfoArr['1']) !== '') {
                                $tickNumberArr = explode(';', $airTkeyInfoArr['1']);
                            }

                            (Array) $tickNumberArr = array();
                            if (issetParam($airTkeyInfoArr['1']) !== '') {
                                $tickNumberArr = explode(';', $airTkeyInfoArr['1']);
                            }


                            if (issetParam($airTkeyInfoArr[0])) {
                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', array(
                                                                                    'DOCUMENT_TYPE' => issetParam($airTkeyInfoArr[0]), 
                                                                                    'ISSUED_CURRENCY' => issetParam($airKnKeyInfoArr['0']),
                                                                                    'TOTAL' => issetParam($totalSms),
                                                                                ), 'UPDATE', "ID = $smsId");
                            }

                            foreach ($airLineInfoArr as $key => $row) {
                                
                                $starOption = 0;
                                $starPotion = 0;
                                
                                $rowFN = preg_replace("/^ +[0-9]  /", "", substr($row, 0, 11));
                                $row = substr($row, 12);
                                
                                $hkeyTempArr = explode(" ", $row);
                                
                                if (isset($hkeyTempArr[2]) && $hkeyTempArr[2]) {
                                    $starOptionBoolean = strpos($hkeyTempArr[2], '*');
                                    
                                    if ($starOptionBoolean !== false) {
                                        $starOption = 1;
                                        $starPotion = 2;
                                    }
                                }
                                
                                $paramDl = array(
                                                'AIRLINE_CODE' => isset($rowFN) ? substr($rowFN, 0, 2) : '',
                                                'FLIGHT_NUMBER' => isset($rowFN) ? trim(substr($rowFN, 2)) : '',
                                                'CLASS_OF_BOOKING' => isset($hkeyTempArr[0]) ? $hkeyTempArr[0] : '',
                                                'DEPARTURE_DATE' => isset($hkeyTempArr[1]) ? $hkeyTempArr[1] : '',
                                                'ORIGIN_AIRPORT' => isset($hkeyTempArr[3 - $starOption]) ? substr($hkeyTempArr[3 - $starOption], $starPotion, 3) : '',
                                                'DESTINATION_AIRPORT' => isset($hkeyTempArr[3 - $starOption]) ? substr($hkeyTempArr[3 - $starOption], 3+$starPotion) : '',
                                                'PNR_STATUS_CODE' => isset($hkeyTempArr[4 - $starOption]) ? $hkeyTempArr[4 - $starOption] : '',
                                                /* 'ISSUED_CURRENCY' => issetParam($airKnKeyInfoArr['0']),
                                                'TOTAL' => issetParam($airKnKeyInfoArr['1']), 
                                                'DEPARTURE_TIME' => isset($hkeyTempArr[6 - $starOption]) ? $hkeyTempArr[6 - $starOption] : '',
                                                'ARRIVAL_TIME' => isset($hkeyTempArr[7 - $starOption]) ? $hkeyTempArr[7 - $starOption] : '',
                                                'ARRIVAL_DATE' => isset($hkeyTempArr[9 - $starOption]) ? $hkeyTempArr[9 - $starOption] : '', */
                                            );
                                
                                /*
                                 * Altea oorchilt 09/09/2019
                                 * 
                                 */
                                $addinParamArr = explode($paramDl['PNR_STATUS_CODE'], $row);
                                
                                $addinParam = substr($addinParamArr[1], 10);
                                
                                $dtlParamAddin = explode(' ', $addinParam);
                                $addOneday = strpos($dtlParamAddin[1], '+');
                                
                                $paramDl['DEPARTURE_TIME'] = $dtlParamAddin[0];
                                $paramDl['ARRIVAL_TIME'] = $dtlParamAddin[1];
                                $paramDl['ARRIVAL_DATE'] = $paramDl['DEPARTURE_DATE'];
                                
                                if ($addOneday) {
                                    $adddayArr = explode('+', $dtlParamAddin[1]);
                                    $nextDate = Date::nextDate($paramDl['ARRIVAL_DATE'], $adddayArr[1]);
                                    $dd1 = Date::format('d', $nextDate);
                                    $dd2 = Str::upper(substr(Date::format('F', $nextDate), 0, 3));
                                    $paramDl['ARRIVAL_TIME'] = $adddayArr[0];
                                    $paramDl['ARRIVAL_DATE'] = $dd1.''.$dd2;
                                }
                                
                                /*
                                 * 
                                 * end
                                 */
                                
                                $paramDl['ID'] = getUID();
                                $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                $paramDl['CREATED_DATE'] = $currentDate;
                                $paramDl['ORDER_NUM'] = $key;
                                $paramDl['TAG_NAME'] = 'H-KEY-';
                                $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                /* $paramDl['TOTAL'] = issetParam($airKnKeyInfoArr['1']); */

                                $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                            }
                            
                            $ticketIndex = 1;
                            $tIndex = 0;
                            foreach ($airPersonInfoArr as $key => $row) {
                                $row = preg_replace("/[0-9]\.|\(INF|\)/", "##", trim($row));
                                $iTagArr = explode("##", $row);

                                foreach ($iTagArr as $skey => $srow) {
                                    
                                    (Array) $paramDl = array();

                                    if (Str::sanitize($srow)) {
                                        $paramDl['ID'] = getUID();
                                        $paramDl['PASSENGER_NAME'] = $srow;
                                        $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                        $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                        $paramDl['CREATED_DATE'] = $currentDate;
                                        $paramDl['TAG_NAME'] = 'I-';
                                        $paramDl['TAG_VALUE'] = $ticketIndex < 10 ? '0'.$ticketIndex : $ticketIndex;
                                        $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                        $paramDl['DOCUMENT_NUMBER'] = issetParam($tickNumberArr[$tIndex]);
                                        
                                        $paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                        $paramDl['TAX'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']));
                                        $paramDl['COMMISSION_PERCENT'] = checkDefaultVal($amountConArr[$tIndex], issetParamZero($amountConArr['0']));
                                        $paramDl['TOTAL'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']))+checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                        /*$paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountFfArr[$tIndex], issetParam($amountFfArr['0'])); */
                                        $ticketIndex++;
                                        $tIndex++;
                                    }
                                    
                                    if ($paramDl) {
                                        $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                                    }
                                }
                            }

                            $paramInitery = array(
                                'systemMetaGroupId' => '1561014189286738',
                                'criteria' => array(
                                    'smsId' => array(
                                        array(
                                            'operator' => '=',
                                            'operand' => $smsId
                                        )
                                    )
                                )
                            );

                            $resultInitery = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $paramInitery);                                                

                            if ($resultInitery['status'] == 'success' && isset($resultInitery['result'][0]['itinerary'])) {
                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', array('ITINERARY' => $resultInitery['result'][0]['itinerary']), 'UPDATE', "ID = $smsId");
                            }

                            $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'), 'id' => $smsId);

                        }

                    }
                    else {
                        throw new Exception("Процесс команд тохируулаагүй байна!"); 
                    }

                    break;
                case '2':
                    
                    if ($airArrData) {
                        $alteaSmsArr = $airArrData;
                    }
                    
                    if (isset($alteaSmsArr[0])) {

                        /*  begin header information */

                        $headerInformation = array_values(array_filter(explode(' ', trim($alteaSmsArr[0]))));
                        
                        (Array) $headerInfoArr = array();

                        foreach ($headerInformation as $row) {
                            if ($row) {
                                array_push($headerInfoArr, $row);
                            }
                        }

                        $smsId = getUID();
                        (Array) $inputParam = array(
                                                    'ID' => $smsId, 
                                                    'CREATED_USER_ID' => $sessionUserKeyId,
                                                    'CREATED_DATE' => $currentDate,
                                                    'GDS' => 'galileo',
                                                    'DOCUMENT_TYPE' => ''
                                                );

                        if (isset($headerInfoArr[0])) {
                            $subOidAndOid = explode('/', $headerInfoArr[0]);
                            $inputParam['PNR'] = $subOidAndOid[0];
                        }
                        
                        if (isset($headerInfoArr[5])) {
                            $ticketHeader = true;
                            $inputParam['ISSUE_DATE'] = $headerInfoArr[5];
                        }
                        
                        /*
                         * $resultHdr = true;
                         * */
                        if ($ticketHeader) {
                            $resultHdr = $this->db->AutoExecute('AIR_MESSAGE_HEADER', $inputParam);
                            $this->db->UpdateClob('AIR_MESSAGE_HEADER', 'AIR_MESSAGE', $postData['screenCaptureSms'], " ID = '$smsId' ");
                        }
                        
                        /*  end header information */
                        if ($ticketHeader && $resultHdr) {

                            (Array) $airPersonInfoArr = $airLineInfoArr = array();
                            (Array) $airTkeyInfoArr = $airKnKeyInfoArr = $airTaxKeyInfoArr = $airConKeyInfoArr = $airFfKeyInfoArr = array();
                            
                            foreach ($alteaSmsArr as $key => $sms) {

                                if (preg_match("/^\s*[0-9]+\s+.\s+[A-Z]/", $sms)) {
                                    array_push($airLineInfoArr, $sms);
                                } elseif (preg_match("/(^\s*[0-9.]+[0-9])|(^\s*[0-9.]+[0-9I])/", $sms)) {
                                    array_push($airPersonInfoArr, $sms);
                                }  elseif (strpos($sms, 'T-') !== false) {
                                    $stmp = preg_replace('/T-/', '', $sms, 1);
                                    $stmp = explode(';', $stmp, 2); 
                                    
                                    if ($stmp) {
                                        $tmpCode = str_replace(' ', '', $stmp[0]);
                                        $dType = '';
                                        switch ($tmpCode) {
                                            case 'TKTT':
                                                $dType = '7A';
                                                break;
                                            case 'EMD':
                                                $dType = '7D';
                                                break;
                                            case 'RFND':
                                                $dType = 'RF';
                                                break;
                                            case 'CANX':
                                                $dType = 'MA';
                                                break;
                                            
                                        }
                                        $airTkeyInfoArr = array($dType, issetParam($stmp[1]));
                                    }

                                } elseif (strpos($sms, 'KN-') !== false) {
                                    $stmp = preg_replace('/KN-/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airKnKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'TAX') !== false) {
                                    $stmp = preg_replace('/TAX/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airTaxKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'COM') !== false) {
                                    $stmp = preg_replace('/COM/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airConKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'FF') !== false) {
                                    $stmp = preg_replace('/FF/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airFfKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                }

                            }

                            $totalSms = '0';
                            (Array) $amountArr = array();
                            if (issetParam($airKnKeyInfoArr['1']) !== '') {
                                $amountArr = explode(';', $airKnKeyInfoArr['1']);
                                foreach ($amountArr as $key => $row) {
                                    $totalSms += (float) $row;
                                }
                            }
                            
                            (Array) $amountConArr = array();
                            if (issetParam($airConKeyInfoArr['1']) !== '') {
                                $amountConArr = explode(';', $airConKeyInfoArr['1']);
                                /* foreach ($amountConArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $amountTaxArr = array();
                            if (issetParam($airTaxKeyInfoArr['1']) !== '') {
                                $amountTaxArr = explode(';', $airTaxKeyInfoArr['1']);
                                /* foreach ($amountTaxArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $amountFfArr = array();
                            if (issetParam($airFfKeyInfoArr['1']) !== '') {
                                $amountFfArr = explode(';', $airFfKeyInfoArr['1']);
                                /* foreach ($amountFfArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $tickNumberArr = array();
                            if (issetParam($airTkeyInfoArr['1']) !== '') {
                                $tickNumberArr = explode(';', $airTkeyInfoArr['1']);
                            }

                            if (issetParam($airTkeyInfoArr[0])) {
                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', array(
                                                                                    'DOCUMENT_TYPE' => issetParam($airTkeyInfoArr[0]), 
                                                                                    'ISSUED_CURRENCY' => issetParam($airKnKeyInfoArr['0']),
                                                                                    'TOTAL' => issetParam($totalSms),
                                                                                ), 'UPDATE', "ID = $smsId");
                            }
                            
                            foreach ($airLineInfoArr as $key => $row) {
                                
                                $row = preg_replace("/^ +[0-9] +[.] /", "", $row);
                                
                                $hkeyTempArr = array_values(array_filter(explode(" ", $row)));
                                $paramDl = array(
                                                'AIRLINE_CODE' => isset($hkeyTempArr[0]) ? $hkeyTempArr[0] : '',
                                                'FLIGHT_NUMBER' => isset($hkeyTempArr[1]) ? $hkeyTempArr[1] : '',
                                                'CLASS_OF_BOOKING' => isset($hkeyTempArr[2]) ? $hkeyTempArr[2] : '',
                                                'PNR_STATUS_CODE' => isset($hkeyTempArr[5]) ? substr($hkeyTempArr[5], 0, 2) : '',
                                                'ORIGIN_AIRPORT' => isset($hkeyTempArr[4]) ? substr($hkeyTempArr[4], 0, 3) : '',
                                                'DESTINATION_AIRPORT' => isset($hkeyTempArr[4]) ? substr($hkeyTempArr[4], 3) : '',
                                                'DEPARTURE_DATE' => isset($hkeyTempArr[3]) ? $hkeyTempArr[3] : '',
                                                'DEPARTURE_TIME' => isset($hkeyTempArr[6]) ? $hkeyTempArr[6] : '',
                                                'ARRIVAL_TIME' => isset($hkeyTempArr[7]) ? $hkeyTempArr[7] : '',
                                                'ARRIVAL_DATE' => isset($hkeyTempArr[3]) ? $hkeyTempArr[3] : '',
                                                'ISSUED_CURRENCY' => issetParam($airKnKeyInfoArr['0']),
                                                /* 'TOTAL' => issetParam($airKnKeyInfoArr['1']), */
                                            );
                                $paramDl['ID'] = getUID();
                                $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                $paramDl['CREATED_DATE'] = $currentDate;
                                $paramDl['ORDER_NUM'] = $key;
                                $paramDl['TAG_NAME'] = 'H-KEY-';

                                $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                            }
                            
                            $ticketIndex = 1; $tIndex = 0;
                            $airPersonInfoArrTT = $airPersonInfoArr;
                            $airPersonInfoArr = array();
                            
                            foreach ($airPersonInfoArrTT as $key => $row) {
                                $row = preg_replace("/[0-9]\.[0-9]/", "##", $row);
                                if (strpos($row, '##') !== false) {
                                    $row = explode("##", $row);
                                    foreach ($row as $value) {
                                        if (3 < strlen($value)) {
                                            array_push($airPersonInfoArr, $value);
                                        }
                                    }
                                }
                            }

                            foreach ($airPersonInfoArr as $key => $row) {
                                // $tempArri = array_values(array_filter(explode(" ", $row)));
                                // $tempArri = array_values(array_filter(array($row)));

                                // foreach ($tempArri as $crow) {
                                //     $row = preg_replace("/[0-9]\.[0-9]/", "", $crow);

                                    (Array) $paramDl = array();
                                    if (Str::sanitize($row)) {
                                        $paramDl['ID'] = getUID();
                                        $paramDl['PASSENGER_NAME'] = $row;
                                        $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                        $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                        $paramDl['CREATED_DATE'] = $currentDate;
                                        $paramDl['TAG_NAME'] = 'I-';
                                        $paramDl['TAG_VALUE'] = $ticketIndex < 10 ? '0'.$ticketIndex : $ticketIndex;
                                        $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                        $paramDl['DOCUMENT_NUMBER'] = issetParam($tickNumberArr[$tIndex]);
                                        
                                        $paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                        $paramDl['TAX'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']));
                                        $paramDl['COMMISSION_PERCENT'] = checkDefaultVal($amountConArr[$tIndex], issetParamZero($amountConArr['0']));
                                        $paramDl['TOTAL'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']))+checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                        /*$paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountFfArr[$tIndex], issetParam($amountFfArr['0'])); */
                                        $ticketIndex++;
                                        $tIndex++;
                                    }

                                    /* print_array($paramDl); */
                                    if ($paramDl) {
                                        $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                                    }                                    
                                // }
                            }
                            
                            $paramInitery = array(
                                'systemMetaGroupId' => '1561014189286738',
                                'criteria' => array(
                                    'smsId' => array(
                                        array(
                                            'operator' => '=',
                                            'operand' => $smsId
                                        )
                                    )
                                )
                            );

                            $resultInitery = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $paramInitery);                                                

                            if ($resultInitery['status'] == 'success' && isset($resultInitery['result'][0]['itinerary'])) {
                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', array('ITINERARY' => $resultInitery['result'][0]['itinerary']), 'UPDATE', "ID = $smsId");
                            }
                            
                            $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'), 'id' => $smsId);

                        }

                    }
                    else {
                        throw new Exception("Процесс команд тохируулаагүй байна!"); 
                    }
                    break;
                case '3':   
                    
                    if ($airArrData) {
                        foreach ($airArrData as $key => $row) {
                            $ticket = strpos($row, 'LIMIT');
                            if ($ticket !== false) {
                                $tick = true;
                            } else {
                                if (!$tick) {
                                    array_push($alteaSmsArr, $row);
                                }
                            }
                        }
                    }
                    
                    if (isset($alteaSmsArr[0])) {
                        
                        (Array) $headerInfoArr = array();
                        $headerInformation = explode(' ', $alteaSmsArr[0]);
                        
                        foreach ($headerInformation as $row) {
                            if ($row) {
                                array_push($headerInfoArr, $row);
                            }
                        }
                        
                        $smsId = getUID();
                        (Array) $inputParam = array(
                                                    'ID' => $smsId, 
                                                    'CREATED_USER_ID' => $sessionUserKeyId,
                                                    'CREATED_DATE' => $currentDate,
                                                    'GDS' => 'sabre',
                                                    'DOCUMENT_TYPE' => ''
                                                );

                        if (isset($headerInfoArr[1])) {
                            $ticketHeader = true;
                            $subOidAndOid = explode('/', $headerInfoArr[1]);
                            $inputParam['ISSUE_DATE'] = issetParam($subOidAndOid[1]);
                        }
                        
                        if (isset($headerInfoArr[2])) {
                            $ticketHeader = true;
                            $inputParam['PNR'] = $headerInfoArr[2];
                        }
                        
                        if ($ticketHeader) {
                            $resultHdr = $this->db->AutoExecute('AIR_MESSAGE_HEADER', $inputParam);
                            $this->db->UpdateClob('AIR_MESSAGE_HEADER', 'AIR_MESSAGE', $postData['screenCaptureSms'], " ID = '$smsId' ");
                        }

                        /*  end header information */
                        if ($ticketHeader && $resultHdr) {

                            (Array) $airPersonInfoArr = $airLineInfoArr = array();
                            (Array) $airTkeyInfoArr = $airKnKeyInfoArr = $airTaxKeyInfoArr = $airConKeyInfoArr = $airFfKeyInfoArr = array();

                            foreach ($alteaSmsArr as $key => $sms) {

                                if (preg_match("/^ +[0-9 ]+[a-zA-Z]/", $sms)) {
                                    array_push($airLineInfoArr, $sms);
                                } 
                                elseif (preg_match("/^ *([0-9.]+[0-9]+)|([0-9.]+[I]+)/", $sms) && $key > 0) {
                                    array_push($airPersonInfoArr, $sms);
                                }elseif (strpos($sms, 'T-') !== false) {
                                    $stmp = preg_replace('/T-/', '', $sms, 1);
                                    $stmp = explode(';', $stmp, 2); 
                                    
                                    if ($stmp) {
                                        $tmpCode = str_replace(' ', '', $stmp[0]);
                                        $dType = '';
                                        switch ($tmpCode) {
                                            case 'TKTT':
                                                $dType = '7A';
                                                break;
                                            case 'EMD':
                                                $dType = '7D';
                                                break;
                                            case 'RFND':
                                                $dType = 'RF';
                                                break;
                                            case 'CANX':
                                                $dType = 'MA';
                                                break;
                                            
                                        }
                                        $airTkeyInfoArr = array($dType, issetParam($stmp[1]));
                                    }

                                } elseif (strpos($sms, 'KN-') !== false) {
                                    $stmp = preg_replace('/KN-/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airKnKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'TAX') !== false) {
                                    $stmp = preg_replace('/TAX/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airTaxKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'COM') !== false) {
                                    $stmp = preg_replace('/COM/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airConKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                } elseif (strpos($sms, 'FF') !== false) {
                                    $stmp = preg_replace('/FF/', '', $sms, 1);
                                    
                                    if ($stmp) {
                                        $airFfKeyInfoArr =  explode(';', $stmp, 2);										
                                    }
                                }

                            }

                            $totalSms = '0';
                            (Array) $amountArr = array();
                            if (issetParam($airKnKeyInfoArr['1']) !== '') {
                                $amountArr = explode(';', $airKnKeyInfoArr['1']);
                                foreach ($amountArr as $key => $row) {
                                    $totalSms += (float) $row;
                                }
                            }
                            
                            (Array) $amountConArr = array();
                            if (issetParam($airConKeyInfoArr['1']) !== '') {
                                $amountConArr = explode(';', $airConKeyInfoArr['1']);
                                /* foreach ($amountConArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $amountTaxArr = array();
                            if (issetParam($airTaxKeyInfoArr['1']) !== '') {
                                $amountTaxArr = explode(';', $airTaxKeyInfoArr['1']);
                                /* foreach ($amountTaxArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $amountFfArr = array();
                            if (issetParam($airFfKeyInfoArr['1']) !== '') {
                                $amountFfArr = explode(';', $airFfKeyInfoArr['1']);
                                /* foreach ($amountFfArr as $key => $row) {
                                    $totalSms += (float) $row;
                                } */
                            }

                            (Array) $tickNumberArr = array();
                            if (issetParam($airTkeyInfoArr['1']) !== '') {
                                $tickNumberArr = explode(';', $airTkeyInfoArr['1']);
                            }

                            if (issetParam($airTkeyInfoArr[0])) {
                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', array(
                                                                                    'DOCUMENT_TYPE' => issetParam($airTkeyInfoArr[0]), 
                                                                                    'ISSUED_CURRENCY' => issetParam($airKnKeyInfoArr['0']),
                                                                                    'TOTAL' => issetParam($totalSms),
                                                                                ), 'UPDATE', "ID = $smsId");
                            }

                            $ticketIndex = 0;
                            $tIndex = 0;
                            foreach ($airPersonInfoArr as $key => $row) {
                                
                                $iTagArr = preg_split("/(^ *[0-9.]+[0-9])|( +[0-9.]+[0-9I])/", $row);

                                foreach ($iTagArr as $skey => $srow) {

                                    (Array) $paramDl = array();
                                        
                                    if (Str::sanitize($srow)) {

                                        $srowExplode = explode('*', $srow);

                                        if (count($srowExplode) === 2) {
                                            $srowExplode1 = explode('/', $srowExplode[0]);                                     
                                            $srowFname = $srowExplode1[0];

                                            if (count($srowExplode1) > 2 && strpos($srow, '*I') === false) {
                                                $srowExplode2 = explode('/', $srowExplode[1]);
                                                unset($srowExplode1[0]);
        
                                                $gindex = 0;
                                                foreach ($srowExplode1 as $grow) {
                                                    $paramDl['ID'] = getUID();
                                                    
                                                    $paramDl['PASSENGER_NAME'] = $srowFname . '/' . $grow . '(' . $srowExplode2[$gindex] . ')';
                                                    $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                                    $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                                    $paramDl['CREATED_DATE'] = $currentDate;
                                                    $paramDl['TAG_NAME'] = 'I-';
                                                    $paramDl['TAG_VALUE'] = $ticketIndex < 10 ? '0'.$ticketIndex : $ticketIndex;
                                                    $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                                    $paramDl['DOCUMENT_NUMBER'] = issetParam($tickNumberArr[$tIndex]);
                                                    $paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                                    $paramDl['TAX'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']));
                                                    $paramDl['COMMISSION_PERCENT'] = checkDefaultVal($amountConArr[$tIndex], issetParamZero($amountConArr['0']));
                                                    $paramDl['TOTAL'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']))+checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                                    $ticketIndex++;                                                    
                                                    $gindex++;
                                                    $tIndex++;

                                                    $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                                                }
                                            } else {
                                                $gsrow = explode('/', $srow);
                                                $paramDl['ID'] = getUID();
                                                    
                                                $paramDl['PASSENGER_NAME'] = count($gsrow) == 3 ? $gsrow[1] . '/' . $gsrow[2] : $srow;
                                                $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                                $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                                $paramDl['CREATED_DATE'] = $currentDate;
                                                $paramDl['TAG_NAME'] = 'I-';
                                                $paramDl['TAG_VALUE'] = $ticketIndex < 10 ? '0'.$ticketIndex : $ticketIndex;
                                                $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                                $paramDl['DOCUMENT_NUMBER'] = issetParam($tickNumberArr[$tIndex]);
                                                
                                                $paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                                $paramDl['TAX'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']));
                                                $paramDl['COMMISSION_PERCENT'] = checkDefaultVal($amountConArr[$tIndex], issetParamZero($amountConArr['0']));
                                                $paramDl['TOTAL'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']))+checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                                /*$paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountFfArr[$tIndex], issetParam($amountFfArr['0'])); */
                                                /* $paramDl['TOTAL'] = issetParam($airKnKeyInfoArr['1']); */
                                                $ticketIndex++;           
                                                $tIndex++;           

                                                $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);                                                
                                            }

                                        } else {

                                            $srowExplode1 = explode('/', $srowExplode[0]);
                                            $srowFname = $srowExplode1[0];
                                            if (count($srowExplode1) > 2) {
                                                unset($srowExplode1[0]);
        
                                                $gindex = 0;
                                                foreach ($srowExplode1 as $grow) {
                                                    $paramDl['ID'] = getUID();
                                                    
                                                    $paramDl['PASSENGER_NAME'] = $srowFname . '/' . $grow;
                                                    $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                                    $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                                    $paramDl['CREATED_DATE'] = $currentDate;
                                                    $paramDl['TAG_NAME'] = 'I-';
                                                    $paramDl['TAG_VALUE'] = $ticketIndex < 10 ? '0'.$ticketIndex : $ticketIndex;
                                                    $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                                    $paramDl['DOCUMENT_NUMBER'] = issetParam($tickNumberArr[$tIndex]);
                                                    $paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountArr[$tIndex], issetParam($amountArr['0']));
                                                    $paramDl['TAX'] = checkDefaultVal($amountTaxArr[$tIndex], issetParam($amountTaxArr['0']));
                                                    $paramDl['COMMISSION_PERCENT'] = checkDefaultVal($amountConArr[$tIndex], issetParam($amountConArr['0']));
                                                    $paramDl['TOTAL'] = checkDefaultVal($amountTaxArr[$tIndex], issetParam($amountTaxArr['0']))+checkDefaultVal($amountArr[$tIndex], issetParam($amountArr['0']));
                                                    /*$paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountFfArr[$tIndex], issetParam($amountFfArr['0'])); */
                                                    /* $paramDl['TOTAL'] = issetParam($airKnKeyInfoArr['1']); */
                                                    $ticketIndex++;                                                    
                                                    $gindex++;                                                    
                                                    $tIndex++;                                                    

                                                    $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                                                }
                                            } else {
                                                $paramDl['ID'] = getUID();
                                                    
                                                $paramDl['PASSENGER_NAME'] = $srowExplode[0];
                                                $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                                $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                                $paramDl['CREATED_DATE'] = $currentDate;
                                                $paramDl['TAG_NAME'] = 'I-';
                                                $paramDl['TAG_VALUE'] = $ticketIndex < 10 ? '0'.$ticketIndex : $ticketIndex;
                                                $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                                $paramDl['DOCUMENT_NUMBER'] = issetParam($tickNumberArr[$tIndex]);
                                                
                                                $paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                                $paramDl['TAX'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']));
                                                $paramDl['COMMISSION_PERCENT'] = checkDefaultVal($amountConArr[$tIndex], issetParamZero($amountConArr['0']));
                                                $paramDl['TOTAL'] = checkDefaultVal($amountTaxArr[$tIndex], issetParamZero($amountTaxArr['0']))+checkDefaultVal($amountArr[$tIndex], issetParamZero($amountArr['0']));
                                                /*$paramDl['EQUIVALENT_MNT'] = checkDefaultVal($amountFfArr[$tIndex], issetParam($amountFfArr['0'])); */
                                                /* $paramDl['TOTAL'] = issetParam($airKnKeyInfoArr['1']); */
                                                $ticketIndex++;                           
                                                $tIndex++;                           

                                                $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);                                                
                                            }                                   
                                        }                                        
                                    }
                                        
                                }
                            }
                            
                            foreach ($airLineInfoArr as $key => $row) {

                                $row = preg_replace("/^ +[0-9]+ /", "", $row);
                                $airlineCodeTemp = substr($row, 0, 7);
                                $row = str_replace($airlineCodeTemp.' ', "", $row);
                                $row = str_replace('*', " ", $row);
                                $hkeyTempArr = explode(" ", $row);
                                
                                $airlineCode = substr($airlineCodeTemp, 0, 2);
                                $flightNumber = substr($airlineCodeTemp, 2, 4);
                                $classOfBooking = substr($airlineCodeTemp, 6);
                                
                                (Array) $tempArr = array();
                                foreach ($hkeyTempArr as $tt) {
                                    if (Str::sanitize($tt)) {
                                        array_push($tempArr, $tt);
                                    }
                                }
                                $hkeyTempArr = $tempArr;
                                $pnrStatusCode = isset($hkeyTempArr[3]) ? substr($hkeyTempArr[3], 0, 2) : '';
                                
                                $starOption = 0;
                                $starPotion = 0;

                                if (isset($hkeyTempArr[2]) && $hkeyTempArr[2]) {
                                    $starOptionBoolean = strpos($hkeyTempArr[2], '*');
                                    if ($starOptionBoolean !== false) {
                                        $pnrStatusCode = isset($hkeyTempArr[2]) ? substr($hkeyTempArr[2], 6) : '';
                                    }
                                }
                                
                                $paramDl = array(
                                                'AIRLINE_CODE' => isset($airlineCode) ? $airlineCode : '',
                                                'FLIGHT_NUMBER' => isset($flightNumber) ? $flightNumber : '',
                                                'CLASS_OF_BOOKING' => isset($classOfBooking) ? $classOfBooking : '',
                                                'PNR_STATUS_CODE' => $pnrStatusCode,
                                                'ORIGIN_AIRPORT' => isset($hkeyTempArr[2]) ? substr($hkeyTempArr[2], 0, 3) : '',
                                                'DESTINATION_AIRPORT' => isset($hkeyTempArr[2]) ? substr($hkeyTempArr[2], 3, 3) : '',
                                                'DEPARTURE_DATE' => isset($hkeyTempArr[0]) ? $hkeyTempArr[0] : '',
                                                'DEPARTURE_TIME' => isset($hkeyTempArr[4-$starOption]) ? $hkeyTempArr[4-$starOption] : '',
                                                'ARRIVAL_TIME' => isset($hkeyTempArr[5-$starOption]) ? $hkeyTempArr[5-$starOption] : '',
                                                'ARRIVAL_DATE' => (isset($hkeyTempArr[6-$starOption]) && strpos($hkeyTempArr[6-$starOption], '/') === false ) ? $hkeyTempArr[6-$starOption] : '',
                                                'ISSUED_CURRENCY' => issetParam($airKnKeyInfoArr['0']),
                                                /* 'TOTAL' => issetParam($airKnKeyInfoArr['1']) */
                                            
                                            );
                                
                                $paramDl['ID'] = getUID();
                                $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                $paramDl['CREATED_DATE'] = $currentDate;
                                $paramDl['ORDER_NUM'] = $key;
                                $paramDl['TAG_NAME'] = 'H-KEY-';
                                $paramDl['ISSUED_CURRENCY'] = issetParam($airKnKeyInfoArr['0']);
                                /* $paramDl['TOTAL'] = issetParam($airKnKeyInfoArr['1']); */
                                
                                $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                            }
                            
                            $paramInitery = array(
                                'systemMetaGroupId' => '1561014189286738',
                                'criteria' => array(
                                    'smsId' => array(
                                        array(
                                            'operator' => '=',
                                            'operand' => $smsId
                                        )
                                    )
                                )
                            );

                            $resultInitery = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $paramInitery);                                                

                            if ($resultInitery['status'] == 'success' && isset($resultInitery['result'][0]['itinerary'])) {
                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', array('ITINERARY' => $resultInitery['result'][0]['itinerary']), 'UPDATE', "ID = $smsId");
                            }
                            
                            $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'), 'id' => $smsId);

                        }

                    }
                    else {
                        throw new Exception("Процесс команд тохируулаагүй байна!"); 
                    }
                       /* throw new Exception("<strong>Sabre:</strong> Хөгжүүлэлт хийгдэж байна!");  */
                    break;
                default:
                    throw new Exception("Ажиллуулах төрөлөө дамжуулаагүй байна!"); 
                    break;
            }
            
        } catch (Exception $e) {
            $response = array('status' => 'warning', 'message' => $e->getMessage());
        }
        
        echo json_encode($response); exit;
        die;
    }

    public function updateDocPath($id, $path) {
        $data = array( 'FILE_PATH' => $path, );
        return $this->db->AutoExecute('DOC_DOCUMENT', $data, 'UPDATE', 'ID = '. $id);
    }
    
    public function docNextWfmStatusIdsModel($recordId, $metaDataId) {
        
        (Array) $result = array();
        $data = $this->db->GetRow("SELECT * FROM DOC_DOCUMENT WHERE ID = '$recordId'");
        // var_dump($data, $recordId, $metaDataId);die;
        if ($data) {
          
            $param = array(
                        'systemMetaGroupId' => $metaDataId,
                        'showQuery' => 0, 
                        'ignorePermission' => 1 ,
                        'id' => $recordId,
                        'wfmstatusid' => $data['WFM_STATUS_ID']
                    );

            $result = $this->ws->runResponse(self::$gfServiceAddress, 'GET_ROW_WFM_STATUS', $param);
            
        }
        
        return isset($result['result']) ? $result['result'] : array();
    }

    public function getDeparmentListJtreeDataModel($departmentId = null, $note= false, $notParent = '') {
        $criteriaValue = array(
            array(
                'operator' => 'IS NULL',
                'operand' => ''
            )
        );
        if ($departmentId) {
            $criteriaValue = array(
                array(
                    'operator' => '=',
                    'operand' => $departmentId
                )
            );
        }

        $departmentList = array();
        $this->load->model('mdmetadata', 'middleware/models/');
        $getMetaDataId = $this->model->getMetaDataByCodeModel('D20_01_ORG_DEPARTMENT_DV');            

        if ($departmentId) {
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'treeGrid' => 1,
                'paging' => array(
                    'sortColumnNames' => array(
                        'code' => array(
                            'sortType' => 'asc',
                            'dataType' => 'string'
                        )
                    )                
                ),
                'criteria' => array(
                    'parentId' =>  array(
                        array(
                            'operator' => '=',
                            'operand' => $departmentId
                        )
                    )
                )
            );
        } else {
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'treeGrid' => 1,
                'paging' => array(
                    'sortColumnNames' => array(
                        'code' => array(
                            'sortType' => 'asc',
                            'dataType' => 'string'
                        )
                    )                
                ),
                'criteria' => array(
                    'parentId' =>  array(
                        array(
                            'operator' => 'IS NULL',
                            'operand' => ''
                        )
                    )
                )
            );
        }

        //if($notParent !== '') {
        //    unset($param['criteria']['parentId']);
        //}

        if(Input::get('str') && Input::get('str') !== '___') {
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'treeGrid' => 1,
                'paging' => array(
                    'sortColumnNames' => array(
                        'code' => array(
                            'sortType' => 'asc',
                            'dataType' => 'string'
                        )
                    )                
                ),
                'criteria' => array(
                    'departmentname' =>  array(
                        array(
                            'operator' => 'like',
                            'operand' =>'%'.Input::get('str').'%'
                        )
                    )
                )
            );
        }
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if (count($data['result']) == 0) {
            $param['criteria'] = array(
                'parentId' => array()
            );
            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        }

        if ($data['status'] === 'success' && isset($data['result'])) {
            $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            //if (count($data['result']) > 0) {
            if (false) {
                $parentIds = array();
                $childIds = array();

                foreach ($data['result'] as $dk => $dataRow) {
                    if (isset($dataRow['childrecordcount'])) {
                        $data['result'][$dk]['state'] = 'closed';
                        $parentIds[] = $data['result'][$dk]['id'];
                    } else {
                        $data['result'][$dk]['state'] = 'open';
                    }

                    if ($data['result'][$dk]['parentid'] != '') {
                        $childIds[] = array(
                            'k' => $dk, 
                            'parentId' => $data['result'][$dk]['parentid']
                        );
                    }
                }

                if ($parentIds && $childIds) {

                    foreach ($childIds as $childId) {

                        if (in_array($childId['parentId'], $parentIds)) {
                            unset($data['result'][$childId['k']]);
                            $result['total'] = $result['total'] - 1;
                        }
                    }

                    $data['result'] = array_values($data['result']);
                }
            }

            $departmentList = $data['result'];
        }

        (Array) $response = array();
        if ($departmentList) {
            $depIds = isset($_GET['depIds']) ? Input::get('depIds') : array();
            $pSelected = isset($_GET['pSelected']) ? Input::get('pSelected') : '0';
            $preDepId = '';

            foreach ($departmentList as $row) {
                if($preDepId == $row['departmentid'])
                    continue;

                $response[] = array(
                    'text'     => $row['departmentname'],
                    'id'       => $row['departmentid'],
                    'icon'     => 'fa fa-folder text-orange-400',
                    'state'    => array(
                        'selected' => (in_array($row['departmentid'], $depIds) || $pSelected == '1') ? true : false,
                        'loaded'   => true,
                        'disabled' => false,
                        'opened'   => false,
                        'parentid' => $row['parentid'],
                    ),
                    'children' => isset($row['childrecordcount']) ? true : false
                );
                $preDepId = $row['departmentid'];
            }
        }
        return $response;
    }        

    public function updatePathEcmContentModel($selectedRow, $fileName) {
        includeLib('Utils/Functions');
        
        $contentData = array(
            'PHYSICAL_PATH' => UPLOADPATH . 'signedDocument/' . $fileName
        );
        
        $result = $this->db->AutoExecute('ECM_CONTENT', $contentData, 'UPDATE', 'CONTENT_ID = '.$selectedRow['contentid']);        
        if ($result && isset($selectedRow['processcode'])  && isset($selectedRow['id']) && isset($selectedRow['systemmetagroupid']) && isset($selectedRow['wfmstatusid']) && isset($selectedRow['ntrwfmstatusid']) && isset($selectedRow['ntrwfmdescription'])) {
            $params = array (
                                'id' => $selectedRow['id'], 
                                'systemMetaGroupId' => $selectedRow['systemmetagroupid'],
                                'wfmStatusId' => $selectedRow['wfmstatusid'],
                                'newWfmStatusId' => $selectedRow['ntrwfmstatusid'],
                                'newWfmDescription' => $selectedRow['ntrwfmdescription'],
                                'processcode' => $selectedRow['processcode'],
                            );
            
            $result = Functions::runProcess('NTR_UBEG_SERVICE_PROPERTY_LIST_004', array('serviceId' => $selectedRow['id']));

            if (isset($result['result']) && $result['result']['serviceid']) {
                $mainRow = $result['result'];

                $sessionUserId = Ue::sessionUserId();
                $certData = $this->db->GetRow("SELECT
                                                    LOWER(t3.state_reg_number) AS state_reg_number,
                                                    t2.certificate_serial_number
                                                FROM
                                                    um_system_user        t0
                                                    INNER JOIN um_user               t1 ON t0.user_id = t1.system_user_id
                                                    INNER JOIN um_user_monpass_map   t2 ON t2.user_id = t1.user_id
                                                    INNER JOIN base_person           t3 ON t0.person_id = t3.person_id
                                                WHERE
                                                    t0.user_id = '". $sessionUserId ."'
                                                    AND t2.is_active = 1");
                if ($certData) {
                    $timeStamp = time();
                    $temp = array(
                                'auth'  => array(
                                    'operator' => array(
                                        'signature' => $certData['STATE_REG_NUMBER'] . '.' . $timeStamp,
                                        'certFingerprint' => $certData['CERTIFICATE_SERIAL_NUMBER'],
                                        'fingerprint' => '',
                                        'regnum' => $certData['STATE_REG_NUMBER']
                                    )

                                ),
                                'serviceId' => (int) issetParam($mainRow['serviceid']), //Үйлчилгээний дугаар
                                'serviceName' => issetParam($mainRow['servicename']), //үйлчилгээний нэр
                                'bookDate' => isset($mainRow['bookdate']) ? Date::format('Y-m-d', $mainRow['bookdate']) : Date::currentDate('Y-m-d'), //Нотариат хийсэн огноо
                                'bookNumber' => issetParam($mainRow['booknumber']), //Нотариатын дугаар
                                'employeeCode' => issetParam($mainRow['employeecode']), //нотариатын ажилчины код
                                'firstName' => issetParam($mainRow['firstname']), //нэр
                                'lastName' => issetParam($mainRow['lastname']), //овог
                                'regnum' => issetParam($mainRow['stateregnumber']),  //регистрийн дугаар
                                'propertyNumber' => issetParam($mainRow['propertynumber']), //улсын бүртгэлийн дугаар
                                'physicalPath' => issetParam($mainRow['physicalpath']), //зураг үзэх линк
                                'status' => (int) issetParam($mainRow['wfmstatusid']), //төлөв
                                'createdDate' => isset($mainRow['createddate']) ? Date::format('Y-m-d', $mainRow['createddate']) : Date::currentDate('Y-m-d'), //хүсэлт ирсэн  огноо
                                'createEmpCode' => issetParam($mainRow['createempcode']), //хүсэлт  шалгасан ажилтан
                                'statusEmpId' => issetParam($mainRow['statusempid']), //төлөв олгосон ажилтан
                                'statusDate' => isset($mainRow['statusdate']) ? Date::format('Y-m-d', $mainRow['statusdate']) : Date::currentDate('Y-m-d'), //төлөв олгосон огноо
                                'statusName' => issetParam($mainRow['statusname']), //төлвийн нэр
                            );

                    $this->load->model('mdintegration', 'middleware/models/');

                    $processRow['WS_URL'] = 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '') .'/ws?WSDL';
                    $processRow['CLASS_NAME'] = 'WS101105_insertNotaryInfo';
                    $result = $this->model->callXypService($processRow, $temp, false, $timeStamp);
                }

            }
            
            $result = Functions::runProcess($selectedRow['processcode'], $params);
            return $result;
            
        } else {
            return array('status' => 'error');
        }
    }

    public function getSideButtonConfModel(){
        $data = $this->db->GetAll("select 'docDocumentCard' as code, 
                                    nvl((select cv.CONFIG_VALUE from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docDocumentCard'), 0) as value,  
                                    nvl((select c.DISPLAY_ORDER from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docDocumentCard'), 1) as disord from dual 
                                union 
                                select 'docDocumentWfmHistory' as code, 
                                    nvl((select cv.CONFIG_VALUE from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docDocumentWfmHistory'), 0) as value,  
                                    nvl((select c.DISPLAY_ORDER from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docDocumentWfmHistory'), 2) as disord from dual
                                union 
                                select 'docDocumentResponse' as code, 
                                    nvl((select cv.CONFIG_VALUE from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docDocumentResponse'), 0) as value,  
                                    nvl((select c.DISPLAY_ORDER from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docDocumentResponse'), 3) as disord from dual
                                union 
                                select 'docExtendResponseDate' as code, 
                                    nvl((select cv.CONFIG_VALUE from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docExtendResponseDate'), 0) as value,  
                                    nvl((select c.DISPLAY_ORDER from config c inner join config_value cv on cv.config_id = c.ID where c.code = 'docExtendResponseDate'), 4) as disord from dual
                                ");  

        $result = array_combine(array_column($data, 'CODE'), $data);
        return $result;
    }

    public function docCommentModel($id){
        $data = $this->db->GetAll("SELECT ec.id, ec.record_id, ec.COMMENT_TEXT, to_char(ec.CREATED_DATE, 'YYYY-MM-DD hh24:mi:ss') cdate, us.username, 
                CASE WHEN bp.last_name IS NULL THEN BP.FIRST_NAME ELSE
                SUBSTR(bp.last_name, 0,1) || '.' || BP.FIRST_NAME END AS fullname,
                HP.POSITION_NAME 
            FROM ecm_comment ec 
            LEFT join um_user us on us.user_id = ec.CREATED_USER_ID 
            LEFT JOIN UM_SYSTEM_USER USU ON USU.USER_ID = us.SYSTEM_USER_ID
            LEFT JOIN HRM_EMPLOYEE HE ON HE.PERSON_ID = USU.PERSON_ID AND HE.IS_ACTIVE = 1 
            LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = HE.PERSON_ID
            LEFT JOIN HRM_EMPLOYEE_KEY HEK ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID AND HEK.IS_ACTIVE = 1 
            LEFT JOIN HRM_POSITION_KEY HPK ON HPK.POSITION_KEY_ID = HEK.POSITION_KEY_ID
            LEFT JOIN HRM_POSITION HP ON HP.POSITION_ID = HPK.POSITION_ID
            WHERE EC.RECORD_ID = " . $id);
        return $data;
    }
    
    public function afistSaveControlModel() {
        $postData = Input::postData();
        (Array) $result = array('status' => 'error', 'text' => Lang::line('msg_save_error'));
        if (issetParam($postData['controlname'])) {
            
            try {
                
                if (!issetParam($postData['selectedRow'])) {
                    throw new Exception("Мөр сонгоогүй байна."); 
                }
                
                if (!issetParam($postData['selectedRow']['processcode'])) {
                    throw new Exception("Processcode олдсонгүй"); 
                }
                
                if (!issetParam($postData['mainData'])) {
                    throw new Exception("Хадгалах өгөгдөл олдсонгүй"); 
                }
                
                switch ($postData['controlname']) {
                    case 'afis_photo':
                        (Array) $params = array();
                        foreach ($postData['mainData'] as $key => $row) {
                            $index = isset($row['key']) ? $row['key'] : $key;
                            $params[$index] = isset($row['value']) ? $row['value'] : '';
                        }
                        var_export($params);
                        die;
                        break;
                    
                    case 'afis_finger':
                        
                        
                        
                        (Array) $params = array();
                        foreach ($postData['mainData'] as $key => $row) {
                            $index = isset($row['key']) ? $row['key'] : $key;
                            $params[$index] = isset($row['value']) ? $row['value'] : '';
                        }
                        
                        var_export($params);
                        die;
                        
                        break;

                    default:
                        throw new Exception("Тохиргоо олдсонгүй"); 
                        break;
                }
                
            } catch (Exception $ex) {
                $result = array('status' => 'error', 'text' => $ex->getMessage());
            }
            
        }
        return $result;
    }
    
    public function bpTemplateUploadGetPath($path = UPLOADPATH . 'ntr_content/', $userDate = true) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $date = Date::currentDate('Y/m/d');
        $newPath = $path . (($userDate) ? $date . '/' : '');

        if (!is_dir($newPath)) {
            mkdir($newPath, 0777, true);
        }

        if (!$userDate) {
            $date1 = Date::currentDate('Y');
            $files = glob($path . $date1 .'/*/*/*.jpg');
            foreach ($files as $file) {
                if (is_file($file) && (time() - filemtime($file) > 60 * 60 * 24 * 1)) {
                    @unlink($file);
                } 
            }
    
            $files = glob($path .'/*.jpg');
            foreach ($files as $file) {
                if (is_file($file) && (time() - filemtime($file) > 60 * 60 * 24 * 1)) {
                    @unlink($file);
                } 
            }
            
            $files = glob($path . $date1 .'/*/*/*.png');
            foreach ($files as $file) {
                if (is_file($file) && (time() - filemtime($file) > 60 * 60 * 24 * 1)) {
                    @unlink($file);
                } 
            }
    
            $files = glob($path .'/*.png');
            foreach ($files as $file) {
                if (is_file($file) && (time() - filemtime($file) > 60 * 60 * 24 * 1)) {
                    @unlink($file);
                } 
            }
        }
        
        return $newPath;
    }

    public function getEcmContentData($id){
        $data = $this->db->GetRow("SELECT * FROM ECM_CONTENT WHERE CONTENT_ID = " . $id);
        return $data;
    }

    public function getEcmOfficeVersionData($id){
        $data = $this->db->GetAll(
            "SELECT KEY, URL, CHANGE_URL, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD HH:MI:SS AM') AS LAST_SAVE_DATE, USERS,
            (SELECT USERNAME FROM UM_SYSTEM_USER WHERE USER_ID IN (SUBSTR(
              SUBSTR(DBMS_LOB.SUBSTR(USERS),3,LENGTH(DBMS_LOB.SUBSTR(USERS))),
              0,LENGTH(DBMS_LOB.SUBSTR(USERS))-4) ) ) AS USERNAME,
              (SUBSTR(
              SUBSTR(DBMS_LOB.SUBSTR(USERS),3,LENGTH(DBMS_LOB.SUBSTR(USERS))),
              0,LENGTH(DBMS_LOB.SUBSTR(USERS))-4) ) AS USERID
            FROM ECM_OFFICE_VERSION WHERE CONTENT_ID = $id ORDER BY CREATED_DATE ASC");
        return $data;
    }
    
    public function fileUploadArr ($fileAttrArr, $kk = '') {
        $response = array();

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        foreach($fileAttrArr['name'] as $key => $fileAttr) {

            $filePath = self::bpTemplateUploadGetPath($path = UPLOADPATH . 'digital_gov/', true);
            $newFileName = 'file_' . getUID();

            $fileAttrTemp = array(
                                'name' =>  ($kk !== '') ?  $fileAttrArr['name'][$key][$kk] :  $fileAttrArr['name'][$key],
                                'type' =>  ($kk !== '') ?  $fileAttrArr['type'][$key][$kk] :  $fileAttrArr['type'][$key],
                                'tmp_name' =>  ($kk !== '') ?  $fileAttrArr['tmp_name'][$key][$kk] :  $fileAttrArr['tmp_name'][$key],
                                'error' =>  ($kk !== '') ?  $fileAttrArr['error'][$key][$kk] :  $fileAttrArr['error'][$key],
                                'size' =>  ($kk !== '') ?  $fileAttrArr['size'][$key][$kk] :  $fileAttrArr['size'][$key]
                            );

            $fileName      = $fileAttrTemp['name'];
            $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
            $fileName      = $newFileName . '.' . $fileExtension;
            
            
            if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'))) {
                
                Upload::$File = $fileAttrTemp;
                Upload::$method = 0;
                Upload::$SavePath = $filePath;
                Upload::$NewWidth = 2000;
                Upload::$NewName = $newFileName;
                Upload::$OverWrite = true;
                Upload::$CheckOnlyWidth = true;
                    
                $uploadError = Upload::UploadFile();
    
                if ($uploadError == '') {
                    
                    $rs = array(
                        'size'      => $fileAttrTemp['size'], 
                        'extension' => $fileExtension, 
                        'name'      => $fileAttrTemp['name'], 
                        'path'      => $filePath, 
                        'newname'   => $fileName
                    );

                    array_push($response, $rs);
                }
            } else {
            
                FileUpload::SetFileName($fileName);
                FileUpload::SetTempName($fileAttrTemp['tmp_name']);
                FileUpload::SetUploadDirectory($filePath);
                FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
                $uploadResult = FileUpload::UploadFile();
                
                if ($uploadResult) {
                    $rs = array(
                        'status'   => 'success', 
                        'size'      => $fileAttrTemp['size'], 
                        'extension' => $fileExtension, 
                        'name'      => $fileAttrTemp['name'], 
                        'path'      => $filePath, 
                        'newname'   => $fileName,
                    );
    
                    array_push($response, $rs);
                } 
            }
        }

        return $response;
    }
    
    public function digitalSignatureWritePdfModel($css, $htmlContent, $fileToSave, $params) {
        
        try {
            
            $digitalSignatureUrl = Config::getFromCache('digital_Signature_Api_Url');
            
            if ($digitalSignatureUrl) {
                
                $reportHtmlPath = $this->db->GetOne("
                    SELECT 
                        TL.HTML_FILE_PATH 
                    FROM META_DATA MD 
                        INNER JOIN META_REPORT_TEMPLATE_LINK TL ON TL.META_DATA_ID = MD.META_DATA_ID 
                    WHERE LOWER(MD.META_DATA_CODE) = 'digitalsignaturereporttemplate'");
                
                if (!$reportHtmlPath || ($reportHtmlPath && !file_exists($reportHtmlPath))) {
                    throw new Exception('DigitalSignatureReportTemplate кодтой мета ороогүй байна!');
                }
                
                $reportHtmlPath = file_get_contents($reportHtmlPath);
                
                foreach ($params as $paramKey => $paramVal) {
                    $reportHtmlPath = str_ireplace('#'.$paramKey.'#', $paramVal, $reportHtmlPath);
                }
                
                $htmlDom = new DOMDocument;
                @$htmlDom->loadHTML($htmlContent);
                $imageTags = $htmlDom->getElementsByTagName('img');
                
                if ($imageTags->length) {
                    foreach ($imageTags as $imageTag) {
                        $imgSrc = $imageTag->getAttribute('src');
                        if ($imgSrc && strpos($imgSrc, 'data:image') === false && file_exists($imgSrc)) {
                            $type = pathinfo($imgSrc, PATHINFO_EXTENSION);
                            $base64Img = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($imgSrc));
                            $htmlContent = str_ireplace('src="'.$imgSrc.'"', 'src="'.$base64Img.'"', $htmlContent);
                        }
                    }
                }
                
                $bodyHtml = '<html><head><meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>'.$css.'</head><body>'.$htmlContent.$reportHtmlPath.'</body></html>';
                $digitalSignatureApiToken = Config::getFromCache('digital_Signature_Api_Url_Token');
                
                if (!$digitalSignatureApiToken) {
                    $digitalSignatureApiToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxODMwMyIsInByb2R1Y3QiOiJJbnRlcm5hbCIsImRldmljZV9pZCI6IkludGVybmFsIiwiaXNzIjoiZm9yZXgtc2VydmljZS0xIiwibW9iaWxlIjoiODAwNTY1NjgxIiwidHlwZSI6IkVtcGxveWVlIiwiYnJhbmNoIjoiNTA5OSIsImF1ZCI6WyJiYW5jcy10cmFuc2FjdGlvbi1zZXJ2aWNlLTIiLCJiYW5jcy10cmFuc2FjdGlvbi1zZXJ2aWNlLTEiLCJyYXRlLXNlcnZpY2UtMSIsImNhdGFsb2ctc2VydmljZS0xIiwic3RhdGVtZW50LXNlcnZpY2UtMSIsImJhbmNzLWlucXVpcnktc2VydmljZS0xIiwiaWRlbnRpdHktc2VydmljZS0xIiwiY2FyZHMtc2VydmljZS0xIiwiY2FyZHMtc2VydmljZS0yIiwiY2FyZC1hZGFwdGVyLXNlcnZpY2UtMiIsImNhcmQtYWRhcHRlci1zZXJ2aWNlLTEiLCJzaWduYXR1cmUtZG9jdW1lbnQtc2VydmljZS0xIiwiZG9jdW1lbnQtc2VydmljZS0xIiwiYWNjb3VudC1zZXJ2aWNlLTEiLCJkc2luZ2F0dXJlLXNlcnZpY2UtMSJdLCJkZXZpY2VfbmFtZSI6Ik1vemlsbGEvNS4wIChXaW5kb3dzIE5UIDEwLjA7IFdpbjY0OyB4NjQpIEFwcGxlV2ViS2l0LzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZS82NC4wLjMyODIuMTQwIFNhZmFyaS81MzcuMzYiLCJkZXZpY2VfYWRkcmVzcyI6IjEwLjYuMjE3LjIxIiwiZXhwIjoyNTQyMjcxOTU5LCJsYW5nIjoiZW4tVVMiLCJpYXQiOjE1NDIyNzAxNTksImp0aSI6IjI4NGQxNDYyLTg1NmItNDMyMy1hM2NlLWFkYmFhOGMxNDIzMCIsImVtYWlsIjoib2RraHV1LmRAa2hhbmJhbmsuY29tIn0.E0Io0AVo3tXiSbDIz1zxYiXe9oCsPkPDDW7ehRlV04L81Y20TgyJxBe19VwGdu9mhbTp_cM7gjOJ5VtlbNdgzw';
                }

                $param = array(
                    'url'              => $digitalSignatureUrl, 
                    'workerSigner'     => issetParam($params['workersigner']), 
                    'workerStamp'      => issetParam($params['workerstamp']), 
                    'documentType'     => issetParam($params['documenttype']), 
                    'branch'           => issetParam($params['branch']), 
                    'customerRegister' => issetParam($params['customerregister']), 
                    'fullName'         => issetParam($params['fullname']), 
                    'orgName'          => issetParam($params['orgname']), 
                    'description'      => issetParam($params['description']), 
                    'data'             => $bodyHtml, 
                    'headerMap'        => array(
                        'Authorization' => 'Bearer '.$digitalSignatureApiToken, 
                        'Content-Type'  => 'application/json'
                    )
                );
                $result = $this->ws->runResponse(self::$gfServiceAddress, 'pki_signature', $param);

                if ($result['status'] == 'success') {
                    if (isset($result['result']['qrcode']) && isset($result['result']['data'])) {

                        $pdfBase64Str = $result['result']['data'];
                        $fileToSave = $fileToSave.'.pdf';
                        
                        @file_put_contents($fileToSave, base64_decode($pdfBase64Str));

                        if (file_exists($fileToSave)) {
                            $response = array('status' => 'success', 'fileUrl' => $fileToSave, 'qrcode' => $result['result']['qrcode'], 'htmlContent' => $htmlContent);
                        } else {
                            throw new Exception('Base64 стрингийг PDF файл болгож чадсангүй!');
                        }
                    } else {
                        throw new Exception('Хариу дээр шаардлагатай талбарууд ирсэнгүй!');
                    }
                } else {
                    throw new Exception($this->ws->getResponseMessage($result));
                }
            } else {
                throw new Exception('digital_Signature_Api_Url уг тохиргоо ороогүй байна!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function replaceWordTags ($physicalPath, $replaceFilePath, $serviceBookId, $processCode) {
        $fullFilePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $physicalPath;
        $filename = trim($physicalPath);
        $ticket = true;

        if (file_exists($fullFilePath)) {
            $ticket = false;

            $inparams = array(
                'commandName' => $processCode,
                'recordId' => $serviceBookId,
                'isReplacePicture' => 0,
                'filePath' => $fullFilePath,
                'replaceFilePath' => $_SERVER['DOCUMENT_ROOT'] . '/' . $replaceFilePath,
            );
        } else {
            $handle = fopen($filename, "rb");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            $byte_array = unpack('c*', $contents);
            $ser = serialize($byte_array);
            
            $inparams = array(
                'commandName' => $processCode,
                'recordId' => $serviceBookId,
                'isReplacePicture' => 0,
                'file' => $ser,
            );
        }

        $saveCache = $this->ws->runResponse(GF_SERVICE_ADDRESS, "replaceWordTags", $inparams);
        if ($saveCache['status'] === 'success') {
            $replacedWordTemplate = isset($saveCache['result']['replacewordtags']) ? base64_decode($saveCache['result']['replacewordtags']) : base64_decode($saveCache['result']['result']);
            
            if ($ticket) {
                file_put_contents($replaceFilePath, $replacedWordTemplate);
            }
            
        }

        return $saveCache;
    }
}