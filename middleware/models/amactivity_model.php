<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

if (class_exists('Amactivity_model') != true) {

    class Amactivity_model extends Model {

        private $alphas = array();

        public function __construct() {
            parent::__construct();
            $this->alphas = excelColumnRange('A', 'ZZ');
        }

        public function saveActivityModel() {
            $param = Input::postData();
            $data  = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_amount', $param);

            if ($data['status'] === 'success')
                    $response = array('text'   => 'Амжилттай хадгалагдлаа.', 'status' => 'success', 'title'  => 'Success',
                    'result' => $data['result']['save_amount']);
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function saveActivityAccountModel() {
            $param = Input::postData();
            $data  = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_budget', $param);

            if ($data['status'] === 'success')
                    $response = array('text' => 'Амжилттай хадгалагдлаа.', 'status' => 'success', 'title' => 'Success');
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function deleteActivitySheetModel() {
            
            $isWithChild = Input::post('isWithChild');
                    
            foreach ($_POST['idRows'] as $id)
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'delete_budget',
                                                   array('id' => $id, 'isWithChild' => $isWithChild));

            if ($data['status'] === 'error') {
                $response = array('text' => $data['text'], 'status' => 'warning', 'title' => 'Warning');
            } else {
                $response = array('text' => 'Амжилттай устгагдлаа.', 'status' => 'success', 'title' => 'Success');
            }
            return $response;
        }
        
         public function deleteActivityTemplateDtl() {
            
            $isWithChild = Input::post('isWithChild');
                    
            foreach ($_POST['idRows'] as $id)
                    $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'delete_template_dtl',
                                                   array('id' => $id, 'isWithChild' => $isWithChild));

            if ($data['status'] === 'error') {
                $response = array('text' => $data['text'], 'status' => 'warning', 'title' => 'Warning');
            } else {
                $response = array('text' => 'Амжилттай устгагдлаа.', 'status' => 'success', 'title' => 'Success');
            }
            return $response;
        }

        public function getRowActivityKeyModel($activityKeyId) {
            return $this->db->GetRow("
                SELECT 
                    AA.ID,
                    AA.ACTIVITY_KEY_CODE,
                    AA.DESCRIPTION,
                    AA.FISCAL_PERIOD_ID,
                    MIN(BB.ORDER_NUM) AS MIN_DIMENION,
                    MAX(BB.ORDER_NUM) AS MAX_DIMENION
                FROM AM_ACTIVITY_KEY AA
                INNER JOIN AM_ACTIVITY_KEY_DIMENSION BB ON BB.ACTIVITY_KEY_ID = AA.ID
                WHERE AA.ID = " . Input::param($activityKeyId) . "
                GROUP BY AA.ID, AA.ACTIVITY_KEY_CODE, AA.DESCRIPTION, AA.FISCAL_PERIOD_ID"
            );
        }

        public function getActivityKeyModel($activityKeyId) {
            return $this->db->GetRow("
                SELECT 
                    AA.START_DATE, AA.END_DATE, ODE.DEPARTMENT_ID, ODE.DEPARTMENT_NAME
                FROM AM_ACTIVITY_KEY AA
                    LEFT JOIN ORG_DEPARTMENT ODE ON AA.MAIN_DEPARTMENT_ID = ODE.DEPARTMENT_ID 
                WHERE AA.ID = " . Input::param($activityKeyId)
            );
        }

//        public function getAllActivityPeriodModel($activityKeyId) {
//            return $this->db->GetAll("
//                SELECT 
//                    AA.FISCAL_PERIOD_ID,
//                    BB.PERIOD_NAME
//                FROM AM_ACTIVITY_KEY_PERIOD AA
//                INNER JOIN FIN_FISCAL_PERIOD BB ON BB.ID = AA.FISCAL_PERIOD_ID
//                WHERE AA.ACTIVITY_KEY_ID = " . Input::param($activityKeyId) . "
//                ORDER BY AA.ORDER_NUM"
//            );
//        }

        public function actionActivitySheetCalculateModel() {
            $param    = Input::postData();
            $rows     = array();
            $fileDtls = array();

            $idsArr = json_decode($param['semantic']['semIds']);
            if(count($idsArr) > 0) {
                foreach ($idsArr as $rowVal) {
                    $dmData = array(
                        'ID' => getUID(),
                        'SRC_TABLE_NAME' => 'AM_ACTIVITY_KEY',
                        'SRC_RECORD_ID' => $param['activityKeyId'],
                        'TRG_TABLE_NAME' => $param['semantic']['tableName'],
                        'TRG_RECORD_ID' => $rowVal
                    );
                    $response = $this->db->AutoExecute("META_DM_RECORD_MAP", $dmData, "INSERT");
                }
            }

            foreach ($param['request'] as $key => $row) {
                $row['rowindex'] = ++$key;
                array_push($rows, $row);
            }
            $param['request'] = $rows;

            if (isset($_POST['activity_file_edit'])) {
                foreach ($_POST['activity_file_edit'] as $rk => $removeFile) {
                    $actionFile = Arr::decode($removeFile);

                    if ($_POST['activity_file_action'][$rk] === 'removed') {
                        if (file_exists($actionFile['path'])) {
                            @unlink($actionFile['path']);
                        }
                    } else {
                        $fileParam['path']       = $actionFile['path'];
                        $fileParam['size']       = $actionFile['size'];
                        $fileParam['extension']  = $actionFile['extension'];
                        $fileParam['attachName'] = $actionFile['attachname'];
                        array_push($fileDtls, $fileParam);
                    }
                }
            }

            if (!empty($_FILES) && isset($_FILES['activity_file'])) {
                $fileData = $_FILES['activity_file'];

                foreach ($fileData['name'] as $key => $fileRow) {
                    if (is_uploaded_file($fileData['tmp_name'][$key])) {

                        $newFileName   = 'activityFile_' . getUID() . '_' . $key;
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
                            $fileParam['path']       = $filePath . $fileName;
                            $fileParam['size']       = $fileData['size'][$key];
                            $fileParam['extension']  = $fileExtension;
                            $fileParam['attachName'] = $fileRow;
                            array_push($fileDtls, $fileParam);
                        }
                    }
                }

                if (!empty($fileDtls)) $param['attach'] = $fileDtls;
            }

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'calculate_budget', $param);

            if ($result['status'] !== 'success') {
                $response = array('text' => $result['text'], 'status' => 'error', 'title' => 'Error');
                return $response;
            }
            $response = array('text' => 'Амжилттай хадгалагдлаа', 'status' => 'success', 'title' => 'success');
            return $response;
        }

        public function actionActivitySheetNotZeroModel() {
            $param  = Input::postData();
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'update_key_amount', $param);
            return true;
        }

        public function getAllActivitySheetModel() {            
            $param = array(
                'activityKeyId' => Input::post('activityKeyId'),
                'periodId'      => Input::post('periodId')
            );
            $data  = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'render_budget', $param);
            //print_r($data); die;
            if ($data['status'] === 'success') {
                $headerArr      = array();
                $headerConfig   = array();
                $firstHeaderArr = array();
                $freezeArr      = array();
                $dataGridRows   = is_null($data['result']['detail']) ? array() : $data['result']['detail'];
                $header         = $data['result']['header'];

                array_push($freezeArr, array(
                    'field' => $data['result']['staticheader']['code'],
                    'title' => $data['result']['staticheader']['name'],
                    'width' => '350'
                ));

                foreach ($header as $key => $row) {
                    $row['code'] = strtolower($row['code']);

                    if ($row['isfreeze'] != "true") {
                        array_push($firstHeaderArr,
                                   array(
                            'title' => $this->alphas[$key]
                        ));
                    }

                    $headerAttrValue                         = array();
                    $headerConfig[$row['code'] . '_comment'] = $row['comment'];
                    $headerConfig[$row['code']]['isround']   = $row['isround'];
                    $headerConfig[$row['code']]['linkmetadataid']   = $row['linkmetadataid'];

                    if (issetVar($row['islabel']) === 'true') {
                        $headerAttrValue = array_merge($headerAttrValue,
                        array(
                            'field' => $row['code'],
                            'title' => $row['name'],
                            'width' => empty($row['width']) ? '150' : $row['width'],
                            'attr'  => $row['type']
                        ));
                        if ($row['isfreeze'] == "true") {
                            array_push($freezeArr, $headerAttrValue);
                        } else {
                            array_push($headerArr, $headerAttrValue);
                        }
                    } else {

                        if ($row['type'] === '1' && !empty($row['metadataid'])) {
                            $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                            $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code'] . '_code']['id']          = $row['code'];

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'] . '_code',
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '200' : $row['width'],
                                'editor' => array(
                                    'type' => 'textbox'
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                        } elseif ($row['type'] === '4') 
                            {

                            $headerAttrValue = array_merge($headerAttrValue,
                                array(
                                'field'  => $row['code'],
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '120' : $row['width'],
                                'attr'   => $row['type'],
                                'editor' => array(
                                    'type' => 'datebox'
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                        } elseif ($row['type'] === '3' && !empty($row['metadataid'])) {
                            $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                            $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code'] . '_code']['id']          = $row['code'];

                            $this->load->model('mdcommon', 'middleware/models/');
                            $resultComboData = $this->model->getRowsDataViewByMetaIdModel($row['metadataid']);

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'],
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '200' : $row['width'],
                                'editor' => array(
                                    'type'    => 'combobox',
                                    'options' => array(
                                        'valueField' => 'id',
                                        'textField'  => 'name',
                                        'data'       => $resultComboData
                                    )
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                        } elseif ($row['type'] === '5') {
                            $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                            $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code'] . '_code']['id']          = $row['code'];

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'] . '_code',
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '200' : $row['width'],
                                'editor' => array(
                                    'type' => 'textarea'
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                        } else {
                            $headerConfig[$row['code']]['type']        = $row['type'];
                            $headerConfig[$row['code']]['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code']]['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code']]['expression']  = $row['expression'];

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'],
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '150' : $row['width'],
                                'attr'   => $row['type'],
                                'align'  => 'right',
                                'editor' => array(
                                    'type'    => 'numberbox',
                                    'options' => array(
                                        'precision' => 2
                                    )
                                )
                            ));
                            if ($row['type'] === '0') {
                                if ($row['isfreeze'] == "true") {
                                    array_push($freezeArr, $headerAttrValue);
                                } else {
                                    array_push($headerArr, $headerAttrValue);
                                }
                            } else {
                                $headerAttrValue = array_merge($headerAttrValue,
                                array(
                                    'field'  => $row['code'],
                                    'title'  => $row['name'],
                                    'width'  => empty($row['width']) ? '150' : $row['width'],
                                    'attr'   => $row['type'],
                                    'round'  => $row['isround'],
                                    'editor' => array(
                                        'type' => 'textbox'
                                    )
                                ));
                                if ($row['isfreeze'] == "true") {
                                    array_push($freezeArr, $headerAttrValue);
                                } else {
                                    array_push($headerArr, $headerAttrValue);
                                }
                            }
                        }
                    }
                }

                $dataGrid = array(
                    'firstHeader'  => $firstHeaderArr,
                    'headerConfig' => $headerConfig,
                    'header'       => $headerArr,
                    'freeze'       => $freezeArr,
                    'detail'       => array(
                        'rows'   => $dataGridRows,
                        'footer' => is_null($data['result']['footer']) ? array() : array($data['result']['footer']),
                        'total'  => count($dataGridRows)
                    )
                );

                $response = array('status' => 'success', 'getRows' => $dataGrid);
            } else $response = array('status' => 'error', 'msg' => 'Error');

            return $response;
        }

        public function getBtnActivityModel() {
            
            $defaultLevel = "1 = 1";
//            $defaultLevel = "AA.ORDER_NUM = " . Input::post('levelNum');
//            if(Input::post('currentLevelNum') !== 'NOT_FOUND')
//                $defaultLevel = "(AA.ORDER_NUM = " . Input::post('levelNum') . " OR AA.ORDER_NUM = " . Input::post('currentLevelNum') . ")";

            $data = $this->db->GetAll(
                "SELECT 
                        BB.NAME, 
                        CC.META_DATA_CODE, 
                        BB.CODE, 
                        DD.FIELD_PATH, 
                        AA.IS_CODE_SHOW, 
                        DD.INPUT_NAME, 
                        CC.META_DATA_NAME,
                        AA.RELATED_DIMENSION_ID,
                        AA.CRITERIA
                    FROM AM_ACTIVITY_KEY_DIMENSION AA
                    INNER JOIN AM_ACTIVITY_DIMENSION BB ON BB.ID = AA.DIMENSION_ID
                    INNER JOIN META_DATA CC ON CC.META_DATA_ID = BB.META_DATA_ID
                    LEFT JOIN META_GROUP_CONFIG DD ON DD.MAIN_META_DATA_ID = BB.META_DATA_ID
                WHERE AA.ACTIVITY_KEY_ID = " . Input::post('activityKeyId') . " AND " . $defaultLevel . " AND (DD.INPUT_NAME = 'META_VALUE_NAME' OR DD.INPUT_NAME = 'META_VALUE_CODE') AND DD.PARENT_ID IS NULL
                ORDER BY AA.ORDER_NUM, CC.META_DATA_CODE, DD.INPUT_NAME DESC"
            );                        

            $dataDim = null;
            if(!Input::post('selectedRow'))
                $dataDim = $this->db->GetAll(
                    "SELECT 
                            BB.NAME,
                            BB.CODE, 
                            AA.IS_CODE_SHOW,
                            AA.DESCRIPTION AS LABEL_NAME, 
                            AA.RELATED_DIMENSION_ID,
                            AA.ORDER_NUM,
                            AA.CRITERIA
                        FROM AM_ACTIVITY_KEY_DIMENSION AA
                        INNER JOIN AM_ACTIVITY_DIMENSION BB ON BB.ID = AA.DIMENSION_ID
                    WHERE AA.ACTIVITY_KEY_ID = " . Input::post('activityKeyId') . " AND AA.ORDER_NUM = (SELECT MIN(ORDER_NUM) FROM AM_ACTIVITY_KEY_DIMENSION WHERE ACTIVITY_KEY_ID = " . Input::post('activityKeyId') . ")
                    ORDER BY AA.ORDER_NUM, AA.DESCRIPTION DESC"
                );
            else {
                $getMaxMinOrder  = $this->db->GetRow("SELECT MAX(ORDER_NUM) AS MA, MIN(ORDER_NUM) AS MI FROM AM_ACTIVITY_KEY_DIMENSION WHERE ACTIVITY_KEY_ID = " . Input::post('activityKeyId'));
                $selOrder = !empty($_POST['selectedRow']['dimorder']) ? (int) $_POST['selectedRow']['dimorder'] : $getMaxMinOrder['MI'];
                
                if($getMaxMinOrder['MA'] > $selOrder) {
                    $selOrder = ++$selOrder;
                } else
                    $selOrder = $getMaxMinOrder['MA'];
                    
                $dataDim = $this->db->GetAll(
                    "SELECT 
                            BB.NAME,
                            BB.CODE, 
                            AA.IS_CODE_SHOW,
                            AA.DESCRIPTION AS LABEL_NAME, 
                            AA.RELATED_DIMENSION_ID,
                            AA.ORDER_NUM,
                            AA.CRITERIA
                        FROM AM_ACTIVITY_KEY_DIMENSION AA
                        INNER JOIN AM_ACTIVITY_DIMENSION BB ON BB.ID = AA.DIMENSION_ID
                    WHERE AA.ACTIVITY_KEY_ID = " . Input::post('activityKeyId') . " AND AA.ORDER_NUM = '".Input::param($selOrder)."'
                    ORDER BY AA.ORDER_NUM, AA.DESCRIPTION DESC"
                );
            }
            
            $response = array();
            if($dataDim) {
                $jd = array();
                foreach ($dataDim as $rowDim) {
                    array_push($jd, array('labelName' => $rowDim['LABEL_NAME'], 'inputName' => $rowDim['CODE'], 'orderNum' => $rowDim['ORDER_NUM']));
                }
                $response[] = array(
                    'META_DATA_CODE'  => '',
                    'META_DATA_NAME'  => '',
                    'CODE'            => '',
                    'FIELD_PATH'      => '',
                    'FIELD_PATH_CODE' => "HIDE",
                    'CRITERIA' => '',
                    'JSON_DATA' => json_encode($jd)
                );                
            }           
            
            if ($data) {
                $data     = array_chunk($data, 2);
                $selectedRow = (Input::postCheck('selectedRow')) ? Input::post('selectedRow') : '';
                if (isset($selectedRow['id'])) {
                    unset($selectedRow['id']);
                }
                
                foreach ($data as $rowVal) {
                    
                    $rules = Str::lower($rowVal[0]['CRITERIA']);
                    if ($selectedRow && !empty($rules)) {
                        foreach ($selectedRow as $sk => $sv) {
                            if (is_string($sv) && strpos($sv, "'") === false) {
                                $sv = "'".Str::lower($sv)."'";
                            } elseif (is_null($sv)) {
                                $sv = "''";
                            }

                            $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                        }
                    }

                    if (empty($rowVal[0]['IS_CODE_SHOW'])) {
                        $response[] = array(
                            'META_DATA_CODE'  => $rowVal[0]['META_DATA_CODE'],
                            'META_DATA_NAME'  => $rowVal[0]['META_DATA_NAME'],
                            'CODE'            => $rowVal[0]['CODE'],
                            'FIELD_PATH'      => $rowVal[0]['FIELD_PATH'],
                            'FIELD_PATH_CODE' => "HIDE",
                            'CRITERIA' => $rules
                        );
                    } else {
                        $response[] = array(
                            'META_DATA_CODE'  => $rowVal[0]['META_DATA_CODE'],
                            'META_DATA_NAME'  => $rowVal[0]['META_DATA_NAME'],
                            'CODE'            => $rowVal[0]['CODE'],
                            'FIELD_PATH'      => $rowVal[0]['FIELD_PATH'],
                            'FIELD_PATH_CODE' => isset($rowVal[1]['FIELD_PATH']) ? $rowVal[1]['FIELD_PATH'] : '',
                            'CRITERIA' => $rules
                        );
                    }
                }                
            }            
            
            if(!empty($response))
                return $response;
            
            return false;
        }

        public function saveActivityPartialModel() {
            $request    = $_POST['request'];
            $param      = Input::postData();
            
            $requestArr = array();
            
            foreach ($request as $row) {
                array_push($requestArr,
                    array(
                        'description'     => $row['description'],
                        'dimensionCode'   => $row['fieldCode'],
                        $row['fieldCode'] => $row['fieldVal'] 
                    )
                );
            }
            
            $metaDataCode = $param['metaDataCode'];
            unset($param['metaDataCode']);
            
            $this->load->model('mdmetadata', 'middleware/models/');
            
            $param['dimensionlistId'] = $this->model->getMetaDataIdByCodeModel($metaDataCode);
            $param['request'] = $requestArr;
            
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_budget_row', $param);

            if ($data['status'] === 'success') {
                $response = array('text' => 'Амжилттай хадгалагдлаа.', 'status' => 'success', 'title' => 'Success');
            } else {
                $message  = isset($data['text']) ? $data['text'] : 'Амжилтгүй боллоо.';
                $response = array('text' => $message, 'status' => 'error', 'title' => 'Error');
            }

            return $response;
        }

        public function saveActivityTemplatePartialModel() {
            $request    = $_POST['request'];
            $param      = Input::postData();
            $requestArr = array();
            
            foreach ($request as $row) {
                array_push($requestArr,
                    array(
                        'description'     => $row['description'],
                        'dimensionCode'   => $row['fieldCode'],
                        $row['fieldCode'] => $row['fieldVal'] 
                    )
                );
            }
            $param['request'] = $requestArr;
            $metaDataCode = $param['metaDataCode'];
            unset($param['metaDataCode']);
                        
            $this->load->model('mdmetadata', 'middleware/models/');   
            $param['dimensionlistId'] = $this->model->getMetaDataIdByCodeModel($metaDataCode);         

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_template_row', $param);

            if ($data['status'] === 'success')
                    $response = array('text' => 'Амжилттай хадгалагдлаа.', 'status' => 'success', 'title' => 'Success');
            else
                    $response = array('text' => 'Амжилтгүй боллоо.', 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function getPeriodActivityModel($param) {
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'get_period', $param);

            if ($data['status'] === 'success')
                    $response = array('text' => 'Success', 'status' => 'success', 'title' => 'Success',
                    'getRows' => $data['result']);
            else $response = array('text' => 'Error', 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function getDmRecordMapModel($id) {
            $mapDatas = $this->db->GetAll("SELECT *
                    FROM META_DM_RECORD_MAP AA
                    LEFT JOIN CRM_CUSTOMER BB ON BB.CUSTOMER_ID = AA.TRG_RECORD_ID
                    LEFT JOIN ORG_DEPARTMENT CC ON CC.DEPARTMENT_ID = AA.TRG_RECORD_ID
                    WHERE AA.SRC_RECORD_ID = " . $id);

            return $mapDatas;
        }

        public function getActivityComboModel($param) {
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'get_expression_values', $param);

            if ($data['status'] === 'success') {
                $resultArr = array();
                $response = isset($data['result']) ? $data['result'] : '';
            } else 
                $response = array();

            return $response;
        }

        public function getActivityComboTextModel($data) {
            $result = array();
            
            if(!empty($data)) {
                foreach ($data as $k => $v) {
                    $result[$k] = $this->getActivityKeyTemplateModel($k);
                }
            }
            
            return $result;
        }

        public function getActivityFilesModel($param) {
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'render_attach', $param);

            if ($data['status'] === 'success') {
                $response = isset($data['result']) ? $data['result'] : '';
            } else $response = array();

            return $response;
        }

        public function saveActivityTemplateModel() {
            $param = array(
                'description'     => Input::post('templateDescription'),
                'activityKeyCode' => Input::post('templateCode'),
                'activityKeyId'   => Input::post('activityKeyId')
            );
            $data  = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_as_template', $param);

            if ($data['status'] === 'success')
                    $response = array('text' => 'Темплейт амжилттай хадгалагдлаа.', 'status' => 'success',
                    'title' => 'Success');
            else
                    $response = array('text' => 'Амжилтгүй боллоо.', 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function getAllActivityTemplateModel() {
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'render_template', Input::postData());

            if ($data['status'] === 'success') {
                $headerArr      = array();
                $headerConfig   = array();
                $firstHeaderArr = array();
                $freezeArr      = array();
                $dataGridRows   = is_null($data['result']['detail']) ? array() : $data['result']['detail'];
                $header         = $data['result']['header'];

                array_push($freezeArr, array(
                    'field' => strtolower($data['result']['staticheader']['oppaccountcode']),
                    'title' => $data['result']['staticheader']['oppaccountname'],
                    'width' => '150'
                ));
                array_push($freezeArr, array(
                    'field' => $data['result']['staticheader']['code'],
                    'title' => $data['result']['staticheader']['name'],
                    'width' => '350'
                ));

                foreach ($header as $key => $row) {
                    $row['code'] = strtolower($row['code']);

                    if ($row['isfreeze'] != "true") {
                        array_push($firstHeaderArr,
                                   array(
                            'title' => $this->alphas[$key]
                        ));
                    }

                    $headerAttrValue                         = array();
                    $headerConfig[$row['code'] . '_comment'] = $row['comment'];
                    $headerConfig[$row['code']]['isround']   = $row['isround'];

                    if (issetVar($row['islabel']) === 'true') {
                        $headerAttrValue = array_merge($headerAttrValue,
                        array(
                            'field' => $row['code'],
                            'title' => $row['name'],
                            'width' => empty($row['width']) ? '150' : $row['width'],
                            'attr'  => $row['type']
                        ));
                        if ($row['isfreeze'] == "true") {
                            array_push($freezeArr, $headerAttrValue);
                        } else {
                            array_push($headerArr, $headerAttrValue);
                        }
                    } else {

                        if ($row['type'] === '1' && !empty($row['metadataid'])) {
                            $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                            $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code'] . '_code']['id']          = $row['code'];

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'] . '_code',
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '200' : $row['width'],
                                'editor' => array(
                                    'type' => 'textbox'
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                        } elseif ($row['type'] === '4') {

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'],
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '120' : $row['width'],
                                'attr'   => $row['type'],
                                'editor' => array(
                                    'type' => 'datebox'
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                            
                        } elseif ($row['type'] === '3' && !empty($row['metadataid'])) {
                            
                            $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                            $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code'] . '_code']['id']          = $row['code'];

                            $this->load->model('mdcommon', 'middleware/models/');
                            $resultComboData = $this->model->getRowsDataViewByMetaIdModel($row['metadataid']);

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'],
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '200' : $row['width'],
                                'editor' => array(
                                    'type'    => 'combobox',
                                    'options' => array(
                                        'valueField' => 'id',
                                        'textField'  => 'name',
                                        'data'       => $resultComboData
                                    )
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                            
                        } elseif ($row['type'] === '5') {
                            
                            $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                            $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code'] . '_code']['id']          = $row['code'];

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'] . '_code',
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '200' : $row['width'],
                                'editor' => array(
                                    'type' => 'textarea'
                                )
                            ));
                            if ($row['isfreeze'] == "true") {
                                array_push($freezeArr, $headerAttrValue);
                            } else {
                                array_push($headerArr, $headerAttrValue);
                            }
                        } else {
                            
                            $headerConfig[$row['code']]['type']        = $row['type'];
                            $headerConfig[$row['code']]['metadataid']  = $row['metadataid'];
                            $headerConfig[$row['code']]['isattribute'] = $row['isattribute'];
                            $headerConfig[$row['code']]['expression']  = $row['expression'];

                            $headerAttrValue = array_merge($headerAttrValue,
                                                           array(
                                'field'  => $row['code'],
                                'title'  => $row['name'],
                                'width'  => empty($row['width']) ? '150' : $row['width'],
                                'attr'   => $row['type'],
                                'align'  => 'right',
                                'editor' => array(
                                    'type'    => 'numberbox',
                                    'options' => array(
                                        'precision' => 2
                                    )
                                )
                            ));
                            if ($row['type'] === '0') {
                                if ($row['isfreeze'] == "true") {
                                    array_push($freezeArr, $headerAttrValue);
                                } else {
                                    array_push($headerArr, $headerAttrValue);
                                }
                            } else {
                                
                                $headerAttrValue = array_merge($headerAttrValue,
                                array(
                                    'field'  => $row['code'],
                                    'title'  => $row['name'],
                                    'width'  => empty($row['width']) ? '150' : $row['width'],
                                    'attr'   => $row['type'],
                                    'round'  => $row['isround'],
                                    'editor' => array(
                                        'type' => 'textbox'
                                    )
                                ));
                                if ($row['isfreeze'] == "true") {
                                    array_push($freezeArr, $headerAttrValue);
                                } else {
                                    array_push($headerArr, $headerAttrValue);
                                }
                            }
                        }
                    }
                }

                $dataGrid = array(
                    'firstHeader'  => $firstHeaderArr,
                    'headerConfig' => $headerConfig,
                    'header'       => $headerArr,
                    'freeze'       => $freezeArr,
                    'detail'       => array(
                        'rows'   => $dataGridRows,
                        //'footer' => is_null($data['result']['footer']) ? array() : array($data['result']['footer']),
                        'total'  => count($dataGridRows)
                    )
                );

                $response = array('status' => 'success', 'getRows' => $dataGrid);                
                /*$headerArr      = array();
                $headerConfig   = array();
                $firstHeaderArr = array();
                $freezeArr      = array();
                $header         = $data['result']['header'];

                array_push($freezeArr,
                           array(
                    'field' => $data['result']['staticheader']['code'],
                    'title' => $data['result']['staticheader']['name'],
                    'width' => '350'
                ));
                
                foreach ($header as $key => $row) {
                    $row['code'] = strtolower($row['code']);
                    array_push($firstHeaderArr,
                               array(
                        'title' => $this->alphas[$key]
                    ));

                    if ($row['type'] === '3' && !empty($row['metadataid'])) {
                        $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                        $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                        $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                        $headerConfig[$row['code'] . '_code']['id']          = $row['code'];
                    }
                    if ($row['type'] === '1' && !empty($row['metadataid'])) {
                        $headerConfig[$row['code'] . '_code']['type']        = $row['type'];
                        $headerConfig[$row['code'] . '_code']['metadataid']  = $row['metadataid'];
                        $headerConfig[$row['code'] . '_code']['isattribute'] = $row['isattribute'];
                        $headerConfig[$row['code'] . '_code']['id']          = $row['code'];

                        array_push($headerArr,
                                   array(
                            'field' => $row['code'] . '_code',
                            'title' => $row['name'],
                            'width' => '150',
                            'attr'  => $row['isattribute'],
                            'align' => 'right'
                        ));
                    } else {
                        array_push($headerArr,
                                       array(
                            'field' => $row['code'],
                            'title' => $row['name'],
                            'width' => '150',
                            'attr'  => $row['isattribute'],
                            'align' => 'right'
                        ));
                    }
                }
                
                $dataGrid = array(
                    'firstHeader'  => $firstHeaderArr,
                    'headerConfig' => $headerConfig,
                    'header'       => $headerArr,
                    'freeze'       => $freezeArr,
                    'detail'       => is_null($data['result']['detail']) ? array() : $data['result']['detail']
                );

                $response = array('status' => 'success', 'getRows' => $dataGrid);*/
            } else {
                $response = array('status' => 'error', 'msg' => 'Error', 'text' => $data['text']);
            }

            return $response;
        }

        public function getAllPeriodActivityModel() {
            $fData = Input::post('fData');
            $fDataArr = array();
            $fDataPeriodArr = array();
            unset($_POST['fData']);
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'render_all', Input::postData());
            
            if(!empty($fData)) {
                parse_str($fData, $fDataArrPost);
                $fDataArr = isset($fDataArrPost['amActivityColumnConfig']) ? $fDataArrPost['amActivityColumnConfig'] : array();
                $fDataPeriodArr = isset($fDataArrPost['amActivityColumnPeriodConfig']) ? $fDataArrPost['amActivityColumnPeriodConfig'] : array();
            }

            if ($data['status'] === 'success') {
                $firstHeaderArr = array();
                $headerArr      = array();
                $freezeArr      = array();
                $groupCheck     = '';
                
                $header         = $data['result']['header'];
                $keyArr         = array_keys($header);
                
                array_push($freezeArr,
                                        array(
                                            'field' => 'description',
                                            'title' => 'Тайлбар',
                                            'width' => '300'
                                        ));
                $ii             = 1;
                $keyIndexZero   = 0;                
                $countColspan   = 0;

                foreach ($keyArr as $keyInd => $row) {
                    if (!isset($header[$row]['period'])) {
                        $firstHeaderArr[$keyInd] = array(
                            'field'   => $row,
                            'title'   => $header[$row]['fact'],
                            'attr'    => $header[$row]['type'],
                            'width'   => '130',
                            'align'   => $header[$row]['type'] != 0 ? 'left' : 'right',
                            'rowspan' => 2
                        );
                    } else {                                
                        if(!empty($fDataPeriodArr) || !empty($fDataArr)) {
                            
                            if(empty($fDataPeriodArr)) {
                                $ffiieelldd = explode('_', $row);
                                if(!empty($fDataArr) && in_array($ffiieelldd[0], $fDataArr)) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;
                                } elseif(empty($fDataArr)) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;                                
                                }
                                
                            } else {
                                
                                $ffiieelldd = explode('_', $row);
                                if(!empty($fDataArr) && in_array($ffiieelldd[0], $fDataArr) && in_array($header[$row]['period'], $fDataPeriodArr) && $ffiieelldd[1] == $header[$row]['periodid']) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;
                                } elseif(empty($fDataArr)) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;
                                }                                
                            }

                            if(!empty($fDataPeriodArr) && in_array($header[$row]['period'], $fDataPeriodArr)) {
                                if ($header[$row]['period'] !== $groupCheck) {
                                    $countColspan++;
                                    $ii                      = 1;
                                    $keyGroup                = $keyInd;
                                    $firstHeaderArr[$keyInd] = array(
                                        'title' => $header[$row]['period']
                                    );                            
                                } else {
                                    $firstHeaderArr[$keyGroup]['colspan'] = $ii;
                                }

                                $groupCheck = $header[$row]['period'];
                                $ii++;                           
                                
                            } elseif(empty($fDataPeriodArr)) {
                                if ($header[$row]['period'] !== $groupCheck) {
                                    $countColspan++;
                                    $ii                      = 1;
                                    $keyGroup                = $keyInd;
                                    $firstHeaderArr[$keyInd] = array(
                                        'title' => $header[$row]['period']
                                    );                            
                                } else {
                                    $firstHeaderArr[$keyGroup]['colspan'] = $ii;
                                }

                                $groupCheck = $header[$row]['period'];
                                $ii++;
                            }
                            
                        } else {
                            
                            $headerArr[$keyIndexZero] = array(
                                'field' => $row,
                                'title' => $header[$row]['fact'],
                                'attr'  => $header[$row]['type'],
                                'groupKey'=> $header[$row]['period'],
                                'width' => '130',
                                'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                            );

                            if ($header[$row]['period'] !== $groupCheck) {
                                $ii                      = 1;
                                $keyGroup                = $keyInd;
                                $firstHeaderArr[$keyInd] = array(
                                    'title' => $header[$row]['period']
                                );                            
                            } else {
                                $firstHeaderArr[$keyGroup]['colspan'] = $ii;
                            }

                            $groupCheck = $header[$row]['period'];
                            $ii++;
                            $keyIndexZero++;
                        }
                    }
                }
                
                if(!empty($fDataArr)) {
                    $resetColspan = $keyIndexZero / $countColspan;
                    
                    foreach ($firstHeaderArr as $kk => $vv)
                        $firstHeaderArr[$kk]['colspan'] = $resetColspan;
                }

                $dataGridRows = is_null($data['result']['detail']) ? array() : $data['result']['detail'];
                $dataGrid     = array(
                    'firstHeader' => array_values($firstHeaderArr),
                    'header'      => $headerArr,
                    'freeze'      => $freezeArr,
                    'detail'      => array(
                        'rows'   => $dataGridRows,
                        'footer' => array($data['result']['footer']),
                        'total'  => count($dataGridRows)
                    )
                );

                $response = array('status' => 'success', 'getRows' => $dataGrid);
            } else
                    $response = array('status' => 'error', 'title' => 'Error', 'text' => $data['text']);

            return $response;
        }

        public function getAllPeriodActivity2Model() {
            $fData = Input::post('fData');
            $fDataArr = array();
            $fDataPeriodArr = array();
            unset($_POST['fData']);
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'render_all_rise', Input::postData());

            if(!empty($fData)) {
                parse_str($fData, $fDataArrPost);
                $fDataArr = isset($fDataArrPost['amActivityColumnConfig']) ? $fDataArrPost['amActivityColumnConfig'] : array();
                $fDataPeriodArr = isset($fDataArrPost['amActivityColumnPeriodConfig']) ? $fDataArrPost['amActivityColumnPeriodConfig'] : array();
                
                $this->saveUserColumn(Input::post('activityKeyId'), $fData);
            } else {
                
                $getRow = $this->getUserColumn(Input::post('activityKeyId'));
                
                if($getRow) {
                    parse_str($getRow['FDATA'], $fDataArrPost);
                    $fDataArr = isset($fDataArrPost['amActivityColumnConfig']) ? $fDataArrPost['amActivityColumnConfig'] : array();
                    $fDataPeriodArr = isset($fDataArrPost['amActivityColumnPeriodConfig']) ? $fDataArrPost['amActivityColumnPeriodConfig'] : array();    
                }
            }

            if ($data['status'] === 'success') {
                $firstHeaderArr = array();
                $headerArr      = array();
                $freezeArr      = array();
                $groupCheck     = '';
                
                $header         = $data['result']['header'];
                $keyArr         = array_keys($header);
                
                array_push($freezeArr,
                                        array(
                                            'field' => 'description',
                                            'title' => 'Тайлбар',
                                            'width' => '300'
                                        ));
                $ii             = 1;
                $keyIndexZero   = 0;                
                $countColspan   = 0;

                foreach ($keyArr as $keyInd => $row) {
                    if (!isset($header[$row]['period'])) {
                        $firstHeaderArr[$keyInd] = array(
                            'field'   => $row,
                            'title'   => $header[$row]['fact'],
                            'attr'    => $header[$row]['type'],
                            'width'   => '130',
                            'align'   => $header[$row]['type'] != 0 ? 'left' : 'right',
                            'rowspan' => 2
                        );
                    } else {                                
                        if(!empty($fDataPeriodArr) || !empty($fDataArr)) {
                            
                            if(empty($fDataPeriodArr)) {
                                $ffiieelldd = explode('_', $row);
                                if(!empty($fDataArr) && in_array($ffiieelldd[0], $fDataArr)) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;
                                } elseif(empty($fDataArr)) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;                                
                                }
                                
                            } else {
                                
                                $ffiieelldd = explode('_', $row);
                                if(!empty($fDataArr) && in_array($ffiieelldd[0], $fDataArr) && in_array($header[$row]['period'], $fDataPeriodArr) && $ffiieelldd[1] == $header[$row]['periodid']) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;
                                } elseif(empty($fDataArr)) {
                                    $headerArr[$keyIndexZero] = array(
                                        'field' => $row,
                                        'title' => $header[$row]['fact'],
                                        'attr'  => $header[$row]['type'],
                                        'groupKey'=> $header[$row]['period'],
                                        'width' => '130',
                                        'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                                    );
                                    $keyIndexZero++;
                                }                                
                            }

                            if(!empty($fDataPeriodArr) && in_array($header[$row]['period'], $fDataPeriodArr)) {
                                if ($header[$row]['period'] !== $groupCheck) {

                                    if($header[$row]['periodtypeid'] !== '4')
                                        $countColspan++;

                                    $ii                      = 1;
                                    $keyGroup                = $keyInd;
                                    $firstHeaderArr[$keyInd] = array(
                                        'title' => $header[$row]['period'],
                                        'colspan' => 1
                                    );                            
                                } else {
                                    $firstHeaderArr[$keyGroup]['colspan'] = $ii;
                                }

                                $groupCheck = $header[$row]['period'];
                                $ii++;                           
                                
                            } elseif(empty($fDataPeriodArr)) {
                                if ($header[$row]['period'] !== $groupCheck) {

                                    if($header[$row]['periodtypeid'] !== '4')
                                        $countColspan++;

                                    $ii                      = 1;
                                    $keyGroup                = $keyInd;
                                    $firstHeaderArr[$keyInd] = array(
                                        'title' => $header[$row]['period'],
                                        'colspan' => 1
                                    );                            
                                } else {
                                    $firstHeaderArr[$keyGroup]['colspan'] = $ii;
                                }

                                $groupCheck = $header[$row]['period'];
                                $ii++;
                            }
                            
                        } else {
                            
                            $headerArr[$keyIndexZero] = array(
                                'field' => $row,
                                'title' => $header[$row]['fact'],
                                'attr'  => $header[$row]['type'],
                                'groupKey'=> $header[$row]['period'],
                                'width' => '130',
                                'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                            );

                            if ($header[$row]['period'] !== $groupCheck) {
                                $ii                      = 1;
                                $keyGroup                = $keyInd;
                                $firstHeaderArr[$keyInd] = array(
                                    'title' => $header[$row]['period'],
                                    'colspan' => 1
                                );                            
                            } else {
                                $firstHeaderArr[$keyGroup]['colspan'] = $ii;
                            }

                            $groupCheck = $header[$row]['period'];
                            $ii++;
                            $keyIndexZero++;
                        }
                    }
                }
                
                /*if(!empty($fDataArr)) {
                    $resetColspan = $keyIndexZero / $countColspan;
                    
                    foreach ($firstHeaderArr as $kk => $vv)
                        $firstHeaderArr[$kk]['colspan'] = round ($resetColspan);
                }*/

                $dataGridRows = is_null($data['result']['detail']) ? array() : $data['result']['detail'];
                $dataGrid     = array(
                    'firstHeader' => array_values($firstHeaderArr),
                    'header'      => $headerArr,
                    'freeze'      => $freezeArr,
                    'detail'      => array(
                        'rows'   => $dataGridRows,
                        'footer' => array($data['result']['footer']),
                        'total'  => count($dataGridRows)
                    )
                );

                $response = array('status' => 'success', 'getRows' => $dataGrid);
            } else
                    $response = array('status' => 'error', 'title' => 'Error', 'text' => $data['text']);

            return $response;            
        }
        
        /*public function getAllPeriodActivity2Model() {
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'render_all_rise', Input::postData());

            if ($data['status'] === 'success') {
                $firstHeaderArr = array();
                $headerArr      = array();
                $freezeArr      = array();
                $groupCheck     = '';
                $header         = $data['result']['header'];
                $keyArr         = array_keys($header);
                
                array_push($freezeArr,
                                        array(
                                            'field' => 'description',
                                            'title' => 'Тайлбар',
                                            'width' => '300'
                                        ));
                $ii             = 1;
                $keyIndexZero   = 0;

                foreach ($keyArr as $keyInd => $row) {
                    if (!isset($header[$row]['period'])) {
                        $firstHeaderArr[$keyInd] = array(
                            'field'   => $row,
                            'title'   => $header[$row]['fact'],
                            'attr'    => $header[$row]['type'],
                            'width'   => '130',
                            'align'   => $header[$row]['type'] != 0 ? 'left' : 'right',
                            'rowspan' => 2
                        );
                    } else {
                        $headerArr[$keyIndexZero] = array(
                            'field' => $row,
                            'title' => $header[$row]['fact'],
                            'attr'  => $header[$row]['type'],
                            'width' => '130',
                            'align' => $header[$row]['type'] != 0 ? 'left' : 'right'
                        );
                        
                        if ($header[$row]['period'] !== $groupCheck) {
                            $ii                      = 1;
                            $keyGroup                = $keyInd;
                            $firstHeaderArr[$keyInd] = array(
                                'title' => $header[$row]['period']
                            );
                        } else {
                            $firstHeaderArr[$keyGroup]['colspan'] = $ii;
                        }

                        $groupCheck = $header[$row]['period'];
                        $ii++;
                        $keyIndexZero++;
                    }
                }

                $dataGridRows = is_null($data['result']['detail']) ? array() : $data['result']['detail'];
                $dataGrid     = array(
                    'firstHeader' => array_values($firstHeaderArr),
                    'header'      => $headerArr,
                    'freeze'      => $freezeArr,
                    'detail'      => array(
                        'rows'   => $dataGridRows,
                        'footer' => array($data['result']['footer']),
                        'total'  => count($dataGridRows)
                    )
                );

                $response = array('status' => 'success', 'getRows' => $dataGrid);
            } else
                    $response = array('status' => 'error', 'title' => 'Error', 'text' => $data['text']);

            return $response;
        }*/

        public function reorderActivityModel() {
            $methodName = 'get_details';
            if (Input::post('requestType') === 'template') $methodName = 'get_template_details';

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, $methodName, Input::postData());

            if ($data['status'] === 'success')
                    if (empty($data['result']['result']))
                        $response = array('text' => 'Error', 'status' => 'error', 'title' => 'Error');
                else
                        $response = array('text' => 'Success', 'status' => 'success', 'title' => 'Success',
                        'getRows' => $data['result']['result']);
            else $response = array('text' => 'Error', 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function reorderActivitySaveModel() {
            $methodName = 'change_order';
            if (Input::post('requestType') === 'template') $methodName = 'change_order_template';

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, $methodName, Input::postData());

            if ($data['status'] === 'success')
                    $response = array('text' => 'Дараалал амжилттай шинэчлэгдлээ.', 'status' => 'success',
                    'title' => 'Success');
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function expressionActivitySaveModel() {
            $param = Input::postData();
            $rows  = array();
            foreach ($param['rows'] as $key => $row) {
                $row['rowindex'] = ++$key;
                array_push($rows, $row);
            }
            $param['rows'] = $rows;

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_expression', $param);

            if ($data['status'] === 'success')
                    $response = array('text' => 'Томъёо амжилттай хадгалагдлаа.', 'status' => 'success',
                    'title' => 'Success');
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function expressionActivityTemplateSaveModel() {
            $param = Input::postData();
            $rows  = array();
            foreach ($param['rows'] as $key => $row) {
                $row['rowindex'] = ++$key;
                array_push($rows, $row);
            }
            $param['rows'] = $rows;
            $param['functionName'] = $this->validateExpression();

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_template_expression', $param);

            if ($data['status'] === 'success')
                    $response = array('text' => 'Томъёо амжилттай хадгалагдлаа.', 'status' => 'success',
                    'title' => 'Success');
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }
        
        public function validateExpression() {

            loadPhpQuery();

            $expressionContent = Input::postNonTags('expressionContent');

            $htmlObj = phpQuery::newDocumentHTML($expressionContent);  
            $matches = $htmlObj->find('span.p-exp-meta:not(:empty)');

            if ($matches->length) {

                foreach ($matches as $tag) {
                    $metaCode = pq($tag)->attr('data-code');
                    pq($tag)->replaceWith($metaCode);
                }

                $expressionContent = $htmlObj->html();
            }

            $search  = array('=',  '&nbsp;', '\r\n', '\r', '\n', "\r\n", "\r", "\n");
            $replace = array('==', ' ',       '',     '',   '',   '',     '',   ''); 

            $expressionContent = html_entity_decode(trim(str_replace($search, $replace, strip_tags($expressionContent))));
            $expressionContent = str_replace(array("\xC2", "\xA0", '\u00a0', '<==', '>==', '!=='), array(' ', ' ', '', '<=', '>=', '!='), $expressionContent);
            $expressionContent = Str::remove_doublewhitespace(Str::remove_whitespace_feed($expressionContent));
            $expressionContent = trim($expressionContent, "\x20,\xC2,\xA0");
            $expressionContent = preg_replace('/\bor\b/u', '||', $expressionContent);
            $expressionContent = preg_replace('/\bOR\b/u', '||', $expressionContent);
            $expressionContent = preg_replace('/\bOr\b/u', '||', $expressionContent);
            $expressionContent = preg_replace('/\band\b/u', '&&', $expressionContent);
            $expressionContent = preg_replace('/\bAND\b/u', '&&', $expressionContent);
            $expressionContent = preg_replace('/\bAnd\b/u', '&&', $expressionContent);
            
            return trim($expressionContent);
        }        

        public function activityDuplicateModel() {            
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'copy_budget', Input::postData());

            if ($data['status'] === 'success')
                    $response = array('text' => 'Амжилттай хуулагдлаа.', 'status' => 'success', 'title' => 'Success');
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function activityTemplateDuplicateModel() {
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'copy_template', Input::postData());

            if ($data['status'] === 'success')
                    $response = array('text' => 'Амжилттай хуулагдлаа.', 'status' => 'success', 'title' => 'Success');
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }

        public function processListModel() {
            (Array) $param = array(
                'systemMetaGroupId' => '1466997726043',
                'showQuery'         => 0
            );

            (Array) $data = $this->ws->runResponse(GF_SERVICE_ADDRESS,
                                                   Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success') {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']); 
                return array(
                    'status' => 'success',
                    'rows'   => $data['result']
                );
            }
            return array(
                'status' => 'error',
                'rows'   => $data['text']
            );
        }

        public function duplicateTemplateModel() {
            $param = array(
                'activityKeyId' => Input::post('activityKeyId')
            );
            $data  = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'refresh_from_template', $param);

            if ($data['status'] === 'success')
                    $response = array('text' => 'Амжилттай сэргээгдлээ.', 'status' => 'success', 'title' => 'Success');
            else
                    $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

            return $response;
        }
        
        public function expressionFunctionListModel() {
            $this->load->model('mdmetadata', 'middleware/models/');

            $getMetaDataId = $this->model->getMetaDataByCodeModel('activityFunctionList');            
            
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'ignorePermission'  => 1,
                'showQuery'         => 0
            );

            (Array) $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success') {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']); 
                return $data['result'];
            }
            return array();
        }        
        
        public function expressionTemplateListModel() {
            $this->load->model('mdmetadata', 'middleware/models/');

            $getMetaDataId = $this->model->getMetaDataByCodeModel('AM_ACTIVITY_KEY_TEMPLATE_LIST_EXPR');            
            
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'ignorePermission'  => 1,
                'showQuery'         => 0,
                'criteria' => array(
                    'activityKeyId' => array(
                        array(
                            'operator' => '=',
                            'operand'  => Input::post('templateId')
                        )
                    )
                )
            );

            (Array) $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success') {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']); 
                return $data['result'];
            }
            return array();
        }
        
        public function factListByTemplateIdModel() {
            $this->load->model('mdmetadata', 'middleware/models/');

            $getMetaDataId = $this->model->getMetaDataByCodeModel('activityFactList');            
            
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'ignorePermission'  => 1,
                'showQuery'         => 0,
                'criteria' => array(
                    'activityKeyId' => array(
                        array(
                            'operator' => '=',
                            'operand'  => Input::post('templateId')
                        )
                    )
                )
            );

            (Array) $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success') {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']); 
                return $data['result'];
            }
            return array();
        }
        
        public function getActivityKeyTemplateModel($activityKeyId) {
            return $this->db->GetOne("SELECT DESCRIPTION FROM AM_ACTIVITY_KEY WHERE ID = " . $activityKeyId);
        }
        
        public function getCommentActivitySheetCtrlModel() {
            if (Input::postCheck('activityKeyId') && Input::postCheck('path') && Input::postCheck('rowId')
                && Input::isEmpty('activityKeyId') === false && Input::isEmpty('path') === false && Input::isEmpty('rowId') === false) {
                    try {
                        $activityBudgetDtlId = Input::post('rowId');
                        $paramPath = Input::post('path');
                        
                        $budgetDataLog = $this->db->GetAll("SELECT 
                                                                uu.USERNAME,
                                                                cc.*
                                                            FROM AM_ACTIVITY_KEY_BUDGET_COMMENT cc
                                                            INNER JOIN um_user uu ON cc.CREATED_USER_ID = uu.USER_ID
                                                            WHERE cc.ACTIVITY_BUDGET_DTL_ID = '$activityBudgetDtlId' 
                                                              AND cc.PARAM_PATH = '$paramPath' 
                                                            ORDER BY cc.CREATED_DATE DESC");
                        
                        $response = array('status' => 'success', 'log' => $budgetDataLog);
                    } catch (Exception $ex) {
                        $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'), 'ex' => $ex->msg);
                    }
                
            } else {
                $response = array('status' => 'warning', 'message' => Lang::line('record_not_found'));
            }
            
            return $response;
        }
        
        public function commentActivitySheetCtrlModel() {
            if (Input::postCheck('activityKeyId') && Input::postCheck('path') && Input::postCheck('pathValue') && Input::postCheck('rowId')  && Input::postCheck('description')
                && Input::isEmpty('activityKeyId') === false && Input::isEmpty('path') === false && Input::isEmpty('pathValue') === false && Input::isEmpty('rowId') === false  && Input::isEmpty('description') === false    
                ) {
                    try {
                        $activityBudgetDtlId = Input::post('rowId');
                        $paramPath = Input::post('path');

                        $data = array(
                                    'ID' => getUID(),
                                    'ACTIVITY_BUDGET_DTL_ID' => Input::post('rowId'),
                                    'PARAM_PATH' => Input::post('path'),
                                    'CREATED_DATE' => Date::currentDate(),
                                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                    'VALUE' => Input::post('pathValue'),
                                    'DESCRIPTION' => Input::post('description'));
                        
                        $result = $this->db->AutoExecute('AM_ACTIVITY_KEY_BUDGET_COMMENT', $data);
                    
                        if ($result) {
                            $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                        } else {
                            $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'));
                        }
                        
                    } catch (Exception $ex) {
                        $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'), 'ex' => $ex->msg);
                    }
                
            } else {
                $response = array('status' => 'warning', 'message' => Lang::line('msg_save_error'));
            }
            
            return $response;
        }
        
        public function getDrillParamsModel($activityKeyId, $dvId) {
            $row = Input::post('row');
            
            $result = $this->db->GetAll("
                SELECT 
                    LOWER(PARAM_PATH) AS PARAM_PATH, LOWER(FILTER_PATH) AS FILTER_PATH
                FROM AM_ACTIVITY_DRILLDOWN_PARAM
                WHERE ACTIVITY_KEY_ID =  $activityKeyId AND MAIN_META_DATA_ID = $dvId"
            );
            
            $rArr = array();
            foreach($result as $rowVal) {
                if(isset($row[trim($rowVal['PARAM_PATH'])]))
                    $rArr[$rowVal['FILTER_PATH']] = $row[trim($rowVal['PARAM_PATH'])];
            }
            
            return $rArr;
        }        
        
        public function getColumnListModel($activityKeyId) {
            $fDataArr = array();
            $fDataPeriodArr = array();
            
            $result1 = $this->db->GetAll("SELECT FACT_FIELD_NAME, DESCRIPTION, '0' AS CHECKED FROM AM_ACTIVITY_KEY_FACT WHERE TYPE_ID = 0 AND ACTIVITY_KEY_ID = $activityKeyId ORDER BY ORDER_NUM");
            
            $result2 = $this->db->GetAll("SELECT BB.PERIOD_CODE, BB.PERIOD_NAME, '0' AS CHECKED  
                                FROM AM_ACTIVITY_KEY_PERIOD AA
                                INNER JOIN FIN_FISCAL_PERIOD BB ON BB.ID = AA.FISCAL_PERIOD_ID
                                WHERE AA.ACTIVITY_KEY_ID = $activityKeyId
                                ORDER BY AA.ORDER_NUM");
            
            $getRow = $this->getUserColumn(Input::post('activityKeyId'));

            if($getRow) {
                parse_str($getRow['FDATA'], $fDataArrPost);
                $fDataArr = isset($fDataArrPost['amActivityColumnConfig']) ? $fDataArrPost['amActivityColumnConfig'] : array();
                $fDataPeriodArr = isset($fDataArrPost['amActivityColumnPeriodConfig']) ? $fDataArrPost['amActivityColumnPeriodConfig'] : array();                    
            }            
            
            foreach($result1 as $k => $row) {
                if(in_array($row['FACT_FIELD_NAME'], $fDataArr)) {
                    $result1[$k]['CHECKED'] = '1';
                }
            }
            
            foreach($result2 as $k => $row) {
                if(in_array($row['PERIOD_NAME'], $fDataPeriodArr)) {
                    $result2[$k]['CHECKED'] = '1';
                }
            }
            
            return array(
                'fact' => $result1,
                'period' => $result2
            );
        }
        
        private function saveUserColumn($keyId, $fdata) {

            try {
                
                $existRow = $this->db->GetOne('SELECT USER_ID FROM AM_ACTIVITY_USER_COLUMN WHERE USER_ID = ' . Ue::sessionUserKeyId() . ' AND ACTIVITY_KEY_ID = ' . $keyId);

                if($existRow) {
                    $dataMap = array(
                        'FDATA' => $fdata
                    );
                    $this->db->AutoExecute('AM_ACTIVITY_USER_COLUMN', $dataMap, 'UPDATE', "USER_ID = " . Ue::sessionUserKeyId() . " AND ACTIVITY_KEY_ID = " . $keyId);
                    
                } else {
                    $dataMap = array(
                        'USER_ID' => Ue::sessionUserKeyId(), 
                        'ACTIVITY_KEY_ID' => $keyId, 
                        'FDATA' => $fdata
                    );

                    $this->db->AutoExecute('AM_ACTIVITY_USER_COLUMN', $dataMap);
                }

                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

            } catch (ADODB_Exception $ex) {
                return array('status' => 'error', 'message' => $ex->getMessage());
            }
        }        
        
        private function getUserColumn($keyId) {
            return $this->db->GetRow('SELECT FDATA FROM AM_ACTIVITY_USER_COLUMN WHERE USER_ID = ' . Ue::sessionUserKeyId() . ' AND ACTIVITY_KEY_ID = ' . $keyId);
        }        
        
        public function updateTemplateActivityModel($data) {
            $dataMap = array(
                'TEMPLATE_ID' => $data['activityKeyId']
            );
            $this->db->AutoExecute('AM_ACTIVITY_KEY_BUDGET_DTL', $dataMap, 'UPDATE', "ID = " . $data['id']);
        }        

        public function deleteActivityTemplateDtl2() {
            
            $dataMap = array(
                'TEMPLATE_ID' => null
            );            
            foreach ($_POST['idRows'] as $id)
                $response = $this->db->AutoExecute('AM_ACTIVITY_KEY_BUDGET_DTL', $dataMap, 'UPDATE', "ID = " . $id);

            if (!$response) {
                $response = array('text' => $data['text'], 'status' => 'warning', 'title' => 'Warning');
            } else {
                $response = array('text' => 'Амжилттай устгагдлаа.', 'status' => 'success', 'title' => 'Success');
            }
            return $response;
        }        

        public function deleteActivityTemplateDtl3() {
            
            $dataMap = array(
                'ACCOUNT_TEMPLATE_ID' => null
            );            
            foreach ($_POST['idRows'] as $id)
                $response = $this->db->AutoExecute('AM_ACTIVITY_KEY_BUDGET_DTL', $dataMap, 'UPDATE', "ID = " . $id);

            if (!$response) {
                $response = array('text' => $data['text'], 'status' => 'warning', 'title' => 'Warning');
            } else {
                $response = array('text' => 'Амжилттай устгагдлаа.', 'status' => 'success', 'title' => 'Success');
            }
            return $response;
        }        

        public function deleteActivityTemplateDtl4() {
            foreach ($_POST['idRows2'] as $id) {
                $this->ws->runResponse(GF_SERVICE_ADDRESS, 'delete_opp_account', array(
                    'rowkey' => $id['rowkey'],
                    'activitykeyid' => $id['activitykeyid'],
                    'id' => $id['id']
                ));
            }

            $response = array('text' => 'Амжилттай устгагдлаа.', 'status' => 'success', 'title' => 'Success');
            return $response;
        }    

        public function updateTemplateAccount2ActivityModel($data) {
            $this->ws->runResponse(GF_SERVICE_ADDRESS, 'save_opp_account', $data);
        }        
    }
}