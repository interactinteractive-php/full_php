<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mdprocess_Model extends Model {

    public function __construct() {
        parent::__construct();
    }       

    public function savePhotoFromWebcamModel() {

        $metaDataId = Input::numeric('metaDataId');
        $metaValueId = Input::post('metaValueId');
        $base64Photo = Input::post('base64Photo');

        if (!empty($metaDataId) && !empty($metaValueId)) {

            $img = str_replace(' ', '+', $base64Photo);
            $data = base64_decode($img);
            $photoName = 'photo_' . getUID() . '.png';
            
            $photo_original = Mdwebservice::bpUploadCustomPath('/metavalue/photo_original/');
            $photo_thumb    = Mdwebservice::bpUploadCustomPath('/metavalue/photo_thumb/');
            
            $origPhoto = file_put_contents($photo_original.$photoName, $data);

            if ($origPhoto) {

                $thumbPhoto = file_put_contents($photo_thumb.$photoName, $data);

                if ($thumbPhoto) {
                    $attachPhotoId = getUID();

                    $dataAttachPhoto = array(
                        'ATTACH_ID'       => $attachPhotoId,
                        'ATTACH_NAME'     => '',
                        'ATTACH'          => $photo_original . $photoName,
                        'ATTACH_THUMB'    => $photo_thumb . $photoName,
                        'FILE_EXTENSION'  => 'png',
                        'FILE_SIZE'       => filesize($photo_original . $photoName),
                        'CREATED_USER_ID' => Ue::sessionUserId()
                    );
                    $attachPhoto = $this->db->AutoExecute('FILE_ATTACH', $dataAttachPhoto);

                    if ($attachPhoto) {
                        
                        $dataMetaPhoto = array(
                            'META_DATA_ID'  => $metaDataId,
                            'META_VALUE_ID' => $metaValueId,
                            'ATTACH_ID'     => $attachPhotoId,
                            'IS_MAIN'       => 0,
                            'ORDER_NUM'     => 1
                        );
                        $this->db->AutoExecute('META_VALUE_PHOTO', $dataMetaPhoto);

                        return array('status' => 'success', 'photoPath' => $dataAttachPhoto['ATTACH'], 'photoThumbPath' => $dataAttachPhoto['ATTACH_THUMB'], 'attachId' => $attachPhotoId, 'message' => Lang::line('msg_save_success'));
                    }
                }
            }

        } else {

            $photoExtension = 'png';
            $mimeType = getMimetypeByExtension($photoExtension);

            return array(
                'status' => 'success', 
                'extension' => $photoExtension, 
                'mimeType' => $mimeType, 
                'thumbBase64Data' => $base64Photo, 
                'origBase64Data' => $base64Photo, 
                'message' => Lang::line('msg_save_success')
            );
        }

        return array('status' => 'error', 'message' => 'Error');
    }
    
    public function textFileToProcessModel($paramData, $fileData) {
        
        $params = array();
        $response = array('status' => 'error', 'message' => 'Зөв өгөгдөлтэй файл сонгоно уу!');
        
        foreach ($paramData as $key => $val) {
            
            if (!is_array($val) && $val) {
                $params[$key] = $val;
            }
        }
        
        if (isset($fileData['name']['dtlFilePath']) && is_uploaded_file($fileData['tmp_name']['dtlFilePath'])) {
            
            $textContent = file_get_contents($fileData['tmp_name']['dtlFilePath']);
            
            if ($textContent) {
                
                $textLineArr = explode("\n", $textContent);
                
                if ($textLineArr) {
                    
                    foreach ($textLineArr as $k => $v) {
                        
                        $comma = trim($v);
                        
                        if ($comma && $comma != ',') {
                            $commaArr = explode(',', $comma);
                            if (count($commaArr) > 1 && $commaArr[0]) {
                                $params['FA_ASSET_PDA_BUFFER'][] = array(
                                    'barCode' => $commaArr[0],
                                    'qty' => $commaArr[1]
                                );
                            }
                        }
                    }
                    
                    if (isset($params['FA_ASSET_PDA_BUFFER'])) {
                        $response = array('status' => 'success', 'params' => $params);
                    } 
                } 
            } 
        } else {
            $response = array('status' => 'error', 'message' => 'Файл сонгоно уу!');
        }
        
        return $response;
    }
    
    public function runTestCaseModel() {
        
        $caseId = Input::numeric('caseId');
        
        if ($caseId) {
            
            $param = array('id' => $caseId);
            $startTime = microtime(true);

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'runTestCase', $param);
            
            $endTime = microtime(true);
            $costTime = $endTime - $startTime;
            
            if ($result['status'] == 'success') {
                $response = array('status' => 'success', 'result' => issetParam($result['result']));
            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
            
            $response['costTime'] = $costTime;
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        return $response;
    }
    
    public function runAllTestCaseModel() {
        
        $data = $this->db->GetAll("
            SELECT 
                ID 
            FROM TEST_CASE 
            WHERE IS_ACTIVE = 1 
                AND PROCESS_META_DATA_ID IS NOT NULL 
            ORDER BY CREATED_DATE ASC");
        
        if ($data) {
            
            foreach ($data as $row) {
                
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'runTestCase', array('id' => $row['ID']));
                
                if ($result['status'] != 'success') {
                    return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
            }
            
            $response = array('status' => 'success', 'message' => 'Ажиллаж дууслаа.');
            
        } else {
            $response = array('status' => 'error', 'message' => 'Өгөгдөл олдсонгүй!');
        }
        
        return $response;
    }
    
    public function importDetailExcelModel() {
        
        if (!empty($_FILES['excelFile']['name'])) { 
            
            set_time_limit(0);
            ini_set('memory_limit', '-1');
            
            $headerSheetName = 'Detail';
            $headerSheetNameLower = Str::lower($headerSheetName);
            $fileName = $_FILES['excelFile']['name'];
            $tmpName = $_FILES['excelFile']['tmp_name'];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if (!in_array($extension, array('xls', 'xlsx'))) {
                return array('status' => 'error', 'message' => "The extension '$extension' is invalid!");
            }
            
            if (!FileUpload::checkContentType($fileName, $tmpName)) {
                return array('status' => 'error', 'message' => 'ContentType is invalid!');
            }
            
            if ($extension == 'xlsx') {
                
                includeLib('Office/Excel/simplexlsx/SimpleXLSX');
                
                $xlsx = SimpleXLSX::parse($tmpName);
                $sheetNames = $xlsx->sheetNames();
                
            } else {
                
                includeLib('Office/Excel/simplexlsx/SimpleXLS');
                
                $xlsx = SimpleXLS::parse($tmpName);
                $sheetNames = $xlsx->sheets;
            }
            
            foreach ($sheetNames as $sheetKey => $sheetName) {
                
                $sheetNameLower = Str::lower($sheetName);
                
                if ($sheetNameLower == $headerSheetNameLower) {
                    $sheetIndex = $sheetKey;
                    break;
                }
            }
            
            if (isset($sheetIndex)) {
                
                $rows = $xlsx->rows($sheetIndex);
                $paths = array_map('strtolower', $rows[0]);
                
                $rows = array_slice($rows, 2, null, false);
                
                $lookupFields = $this->getProcessWithLookupFieldsModel($paths, $rows);
                
                $array = array();
                
                foreach ($rows as $row) {
                    
                    $val = array(); 
                    $isEmpty = true;
                    
                    foreach ($row as $k => $v) {
                        
                        $v = strval($v);
                        
                        if ($v != '') {
                            
                            $path = $paths[$k];
                            
                            if (isset($lookupFields[$path])) {
                                
                                if (isset($lookupFields[$path][Str::lower($v)])) {
                                    
                                    if (strpos($path, '.') !== false) {
                                        Arr::assignArrayByPath($val, $path, $lookupFields[$path][Str::lower($v)]);
                                    } else {
                                        $val[$path] = $lookupFields[$path][Str::lower($v)];
                                    }
                                
                                    $isEmpty = false;
                                }
                                
                            } else {
                            
                                if (strpos($path, '.') !== false) {
                                    Arr::assignArrayByPath($val, $path, $v);
                                } else {
                                    $val[$path] = $v;
                                }

                                $isEmpty = false;
                            }
                        }
                    }
                    
                    if ($isEmpty == false) {
                        $array[] = $val;
                    }
                }
                
                $response = array('status' => 'success', 'rows' => $array, 'message' => 'Амжилттай импорт хийгдлээ.');
                
            } else {
                $response = array('status' => 'error', 'message' => $headerSheetName.' гэсэн sheet нэр олдсонгүй!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Please select excel file!');
        }
        
        return $response;
    }
    
    public function getProcessWithLookupFieldsModel($paths, $selectedRows) {
        
        $lowerGroupPath    = strtolower(Input::post('paramRealPath'));
        $processMetaDataId = Input::numeric('processMetaDataId');
        $idPh              = $this->db->Param(0);
        
        $paths  = array_map(function($val) use ($lowerGroupPath) { return $lowerGroupPath . '.' . $val; }, $paths);
        $fields = "'" . Arr::implode_r("', '", $paths, true) . "'";
        $array  = array();
        
        $data = $this->db->GetAll("
            SELECT 
                PAL.LOOKUP_META_DATA_ID, 
                LOWER(PAL.PARAM_REAL_PATH) AS PARAM_REAL_PATH, 
                LOWER(PAL.LOOKUP_TYPE) AS LOOKUP_TYPE, 
                LOWER(PAL.DISPLAY_FIELD) AS DISPLAY_FIELD, 
                LOWER(PAL.VALUE_FIELD) AS VALUE_FIELD,  
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE MAIN_PROCESS_META_DATA_ID = $idPh  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH)  
                ) AS GROUP_CONFIG_PARAM_PATH,
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG  
                    WHERE MAIN_PROCESS_META_DATA_ID = $idPh  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                ) AS GROUP_CONFIG_LOOKUP_PATH 
            FROM META_PROCESS_PARAM_ATTR_LINK PAL 
            WHERE PAL.PROCESS_META_DATA_ID = $idPh 
                AND PAL.IS_INPUT = 1 
                AND PAL.IS_SHOW = 1 
                AND LOWER(PAL.PARAM_REAL_PATH) IN ($fields)  
                AND PAL.LOOKUP_META_DATA_ID IS NOT NULL 
                AND PAL.RECORD_TYPE IS NULL 
                AND PAL.LOOKUP_TYPE IS NOT NULL 
                AND PAL.CHOOSE_TYPE IS NOT NULL", 
            array($processMetaDataId)
        ); 
        
        if ($data) {
            
            $this->load->model('mdwebservice', 'middleware/models/');
            
            parse_str($_POST['headerParams'], $headerParamsArr);
            
            $lowerGroupPathLength = strlen($lowerGroupPath) + 1;
            $arrayMove = array();
            foreach ($paths as $pathKey => $pathVal) {
                $arrayMove[$pathVal] = $pathKey;
            }
            
            foreach ($data as $row) {
                
                if (isset($arrayMove[$row['PARAM_REAL_PATH']])) {
                    
                    $lookupFieldConfig = array(

                        'lookupMetaDataId' => $row['LOOKUP_META_DATA_ID'], 
                        'lookupType'       => $row['LOOKUP_TYPE'], 
                        'displayField'     => $row['DISPLAY_FIELD'], 
                        'valueField'       => $row['VALUE_FIELD'], 

                        'groupConfigLookupPath' => $row['GROUP_CONFIG_LOOKUP_PATH'], 
                        'groupConfigParamPath'  => $row['GROUP_CONFIG_PARAM_PATH']
                    );

                    $rowDatas = $this->model->getLookupRowDatas($lookupFieldConfig, $headerParamsArr, $selectedRows, $arrayMove[$row['PARAM_REAL_PATH']], 'code');

                    $array[substr($row['PARAM_REAL_PATH'], $lowerGroupPathLength, 300)] = $rowDatas;
                }
            }
        }
        
        return $array;
    }
    
    public function getBpFieldPropertyGridModel($type) {
        
        $arr = array();
        
        if ($type == 'layoutHeader') {
            
            $arr = array(
                array(
                    'label' => 'Section no border', 
                    'code' => 'sectionNoBorder', 
                    'valueType' => 'boolean'
                ), 
                array(
                    'label' => 'Section title no bottom border', 
                    'code' => 'sectionTitleNoBottomBorder', 
                    'valueType' => 'boolean'
                ), 
                array(
                    'label' => 'Background color', 
                    'code' => 'backgroundColor', 
                    'valueType' => 'color'
                ), 
                array(
                    'label' => 'Label color', 
                    'code' => 'labelColor', 
                    'valueType' => 'color'
                )
            );
            
        } elseif ($type == 'layoutSection') {
            
            $arr = array(
                array(
                    'label' => 'Background color', 
                    'code' => 'backgroundColor', 
                    'valueType' => 'color'
                ), 
                array(
                    'label' => 'Label color', 
                    'code' => 'labelColor', 
                    'valueType' => 'color'
                ), 
                array(
                    'label' => 'Label width', 
                    'code' => 'labelWidth', 
                    'valueType' => 'text'
                ), 
                array(
                    'label' => 'Text color', 
                    'code' => 'textColor', 
                    'valueType' => 'color'
                ), 
                array(
                    'label' => 'No padding', 
                    'code' => 'noPadding', 
                    'valueType' => 'boolean'
                ), 
                array(
                    'label' => 'No title', 
                    'code' => 'noTitle', 
                    'valueType' => 'boolean'
                ), 
                array(
                    'label' => 'Navbar fixed', 
                    'code' => 'navbarFixed', 
                    'valueType' => 'boolean'
                )
            );
            
        } elseif ($type == 'layoutParam') {
            
            $arr = array(
                array(
                    'label' => 'No label', 
                    'code' => 'noLabel', 
                    'valueType' => 'boolean'
                ), 
                array(
                    'label' => 'Label align', 
                    'code' => 'labelAlign', 
                    'valueType' => 'halign'
                ), 
                array(
                    'label' => 'Label font size', 
                    'code' => 'labelFontSize', 
                    'valueType' => 'text'
                ), 
                array(
                    'label' => 'Label margin bottom', 
                    'code' => 'labelMarginBottom', 
                    'valueType' => 'text'
                ), 
                array(
                    'label' => 'Column width', 
                    'code' => 'columnWidth', 
                    'valueType' => 'text'
                ), 
                array(
                    'label' => 'Control align', 
                    'code' => 'controlAlign', 
                    'valueType' => 'halign'
                ), 
                array(
                    'label' => 'Control height', 
                    'code' => 'controlHeight', 
                    'valueType' => 'text'
                ), 
                array(
                    'label' => 'Control font size', 
                    'code' => 'controlFontSize', 
                    'valueType' => 'text'
                ), 
                array(
                    'label' => 'Control font weight', 
                    'code' => 'controlFontWeight', 
                    'valueType' => 'fontWeight'
                ), 
                array(
                    'label' => 'Placeholder', 
                    'code' => 'placeholder', 
                    'valueType' => 'text'
                ), 
                array(
                    'label' => 'Line break', 
                    'code' => 'lineBreak', 
                    'valueType' => 'boolean'
                ),
                array(
                    'label' => 'Row break /only radio/', 
                    'code' => 'rowBreak', 
                    'valueType' => 'boolean'
                ), 
                array(
                    'label' => 'Show max rows /only radio/', 
                    'code' => 'showMaxRowsLength', 
                    'valueType' => 'long'
                ), 
                array(
                    'label' => 'Title /only rows path/', 
                    'code' => 'rowsTitle', 
                    'valueType' => 'text'
                )
            );
        }
        
        return $arr;
    }
    
    public function getTestCaseAndProcessRowModel($testCaseId) {
        
        $result = $this->ws->run('array', 'getTestCaseRequest', array('id' => $testCaseId));
        
        if ($result['status'] == 'success') {
            
            $row = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME, 
                    TC.PROCESS_META_DATA_ID, 
                    TC.TEST_CASE_NAME, 
                    TC.TEST_MODE, 
                    SM.SYSTEM_ID, 
                    SCM.SCENARIO_ID, 
                    SCM.ORDER_NUMBER 
                FROM TEST_CASE TC 
                    INNER JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = TC.PROCESS_META_DATA_ID 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.META_DATA_ID 
                    INNER JOIN TEST_CASE_SYSTEM_MAP SM ON TC.ID = SM.TEST_CASE_ID 
                    LEFT JOIN TEST_CASE_SCENARIO_MAP SCM ON TC.ID = SCM.TEST_CASE_ID 
                WHERE TC.ID = ".$this->db->Param(0), 
                array($testCaseId)
            );
            
            $row['REQUEST_DE'] = issetParam($result['result']);
            
            $response = array('status' => 'success', 'result' => $row);
            
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }
    
    public function getTestCaseLogRowModel($logId) {
        
        $row = $this->db->GetRow("
            SELECT 
                PROCESS_META_DATA_ID, 
                RESULT_TEXT  
            FROM TEST_CASE_RESULT  
            WHERE ID = ".$this->db->Param(0), 
            array($logId)
        );
        
        return $row;
    }
    
    public function getRenderProcessCommentRowModel($commentId) {
        
        try {
            $row = $this->db->GetRow("
                SELECT 
                    RECORD_ID, 
                    CREATED_COMMAND_ID, 
                    LIST_META_DATA_ID 
                FROM ECM_COMMENT 
                WHERE ID = ".$this->db->Param(0), 
                array($commentId)
            );    
        } catch (Exception $ex) {
            $row = array();
        }
        
        return $row;
    }
    
    public function saveRenderViewLogModel() {
        
        try {
            
            $bpId = Input::numeric('bpId');
            $uniqId = Input::numeric('uniqId');
            $recordId = Input::numeric('recordId');
            
            if ($bpId && $uniqId && $recordId) {
                
                $currentDate = Date::currentDate();
                $row = $this->db->GetRow("SELECT ID, START_TIME, END_TIME FROM RECORD_VIEW_LOG WHERE ID = ".$this->db->Param(0), array($uniqId));
                
                if ($row) {
                    
                    if (!$row['END_TIME']) {
                        
                        $timeFirst  = strtotime($row['START_TIME']);
                        $timeSecond = strtotime($currentDate);

                        $data = array(
                            'END_TIME' => $currentDate, 
                            'READ_SECOND' => ($timeSecond - $timeFirst)
                        );

                        $this->db->AutoExecute('RECORD_VIEW_LOG', $data, 'UPDATE', 'ID = '.$uniqId);
                    }
                    
                } else {
                    
                    includeLib('Detect/Browser');
                    $browser = new Browser();
                    
                    $this->load->model('mdwebservice', 'middleware/models/');
                    $bpRow = $this->model->getMethodIdByMetaDataModel($bpId);
                    $structureId = $bpRow['REF_META_GROUP_ID'];
                    $tableName = $structureId;
                    
                    if ($structureId) {
                        
                        $this->load->model('mdobject', 'middleware/models/');
                        $dvRow = $this->model->getDVMainQueriesModel($structureId);

                        if ($dvRow['TABLE_NAME'] && strlen($dvRow['TABLE_NAME']) <= 30) {
                            $tableName = $dvRow['TABLE_NAME'];
                        }
                    }
                    
                    $data = array(
                        'ID' => $uniqId, 
                        'TABLE_NAME' => $tableName,
                        'RECORD_ID' => $recordId, 
                        'COMMAND_NAME' => $bpId, 
                        'START_TIME' => $currentDate, 
                        'IP_ADDRESS' => get_client_ip(), 
                        'BROWSER_NAME' => $browser->getBrowser(), 
                        'USER_ID' => Ue::sessionUserKeyId()
                    );
                    
                    $this->db->AutoExecute('RECORD_VIEW_LOG', $data);
                }
                
                $result = array('status' => 'success');
                
            } else {
                throw new Exception('Invalid parameters!');
            }
            
        } catch (Exception $ex) {
            $result = array('status' => 'error');
        }
        
        return $result;
    }
    
}