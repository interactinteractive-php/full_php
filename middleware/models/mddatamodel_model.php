<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mddatamodel_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $fieldDrillConfigs = array();

    public function __construct() {
        parent::__construct();
    }        

    public function getConvertGridDataModel($mainMetaDataId, $selectedRow, $params = array()) {

        if (isset($selectedRow['children'])) {
            unset($selectedRow['children']);
        }

        $item = array();
        parse_str($params, $param);

        $this->load->model('mdobject', 'middleware/models/');
        $gridData = $this->model->getDataViewGridHeaderModel($mainMetaDataId, '1 = 1', 1, false, true);
        $gridDataArr = Arr::multidimensional_list($gridData, array('IS_BASKET_EDIT' => '1'));

        foreach ($gridDataArr as $gRow) {
            foreach ($selectedRow as $key => $row) {
                $row[$gRow['FIELD_PATH']] = $param[$gRow['FIELD_PATH']][$key];
                array_push($item, $row);
            }
        }

        return $item;
    }

    public function checkCriteriaProcessModel($mainMetaDataId, $batchNumber, $selectedRows, $params = array()) {
        
        $mainMetaDataIdPh = $this->db->Param(0);
        $batchNumberPh    = $this->db->Param(1);

        $bindVars = array($mainMetaDataId, $batchNumber);
        
        $data = $this->db->GetAll("
            SELECT 
                PD.PROCESS_META_DATA_ID, 
                MD.META_TYPE_ID,  
                PD.CRITERIA, 
                PD.ADVANCED_CRITERIA 
            FROM META_DM_PROCESS_DTL PD 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PD.PROCESS_META_DATA_ID 
            WHERE PD.MAIN_META_DATA_ID = $mainMetaDataIdPh 
                AND PD.BATCH_NUMBER = $batchNumberPh", $bindVars); 

        $item = array();

        if ($data) {
            
            if (array_key_exists(0, $selectedRows)) {
                $selectedRow = $selectedRows[0];
                $rows = $selectedRows;
            } else {
                $selectedRow = $selectedRows;
                $rows = array($selectedRow);
            }
            
            if (isset($selectedRow['children'])) {
                unset($selectedRow['children']);
            }
            
            if (isset($selectedRow['pfnextstatuscolumn'])) {
                unset($selectedRow['pfnextstatuscolumn']);
            }

            $item = $selectedRow;
            
            foreach ($data as $row) {
                
                $explodedRules = explode('#', $row['CRITERIA']);
                $rules = Str::lower(trim($explodedRules[0]));
                $returnMessage = isset($explodedRules[1]) ? $explodedRules[1] : null;

                foreach ($selectedRow as $sk => $sv) {
                    if (is_string($sv) && strpos($sv, "'") === false) {
                        $sv = "'".Str::lower($sv)."'";
                    } elseif (is_null($sv)) {
                        $sv = "''";
                    }

                    $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                }

                $rules = Mdmetadata::defaultKeywordReplacer($rules);
                $rules = Mdmetadata::criteriaMethodReplacer($rules);
                
                if (trim($rules) != '') {
                    
                    $advancedCriteria = trim($row['ADVANCED_CRITERIA']);
                    
                    if (Mdcommon::expressionEvalFixWithReturn($rules)) { 
                        
                        if ($advancedCriteria && strpos($advancedCriteria, 'equal=') !== false) {
                
                            $splitedCriteria = explode('&&', $advancedCriteria);
                            $isSame = true;

                            foreach ($splitedCriteria as $aCriteria) {

                                $splitedAdvCriteria = explode('#', $aCriteria);
                                $tmpParamPath = trim(str_replace('equal=', '', Str::lower($splitedAdvCriteria[0])));

                                if (isset($rows[0][$tmpParamPath])) {
                                    foreach ($rows as $sv) {
                                        if ($rows[0][$tmpParamPath] != $sv[$tmpParamPath]) {
                                            $isSame = false;
                                            break;
                                        }
                                    }
                                    if (!$isSame) {
                                        break;
                                    }
                                } else {
                                    $isSame = false;
                                    $splitedAdvCriteria[1] = 'Процессийн set criteria config тохиргоо буруу хийгдсэн!';
                                    break;
                                }
                            }

                            if (!$isSame) {
                                return array('processNoAccess' => true, 'processName' => isset($splitedAdvCriteria[1]) ? Lang::line($splitedAdvCriteria[1]) : 'Нөхцөл тохирохгүй байна', 'selectedRow' => $item);
                            } 
                        }

                        $this->load->model('mdobject', 'middleware/models/');
                        $checkProcessAction = $this->model->checkProcessActionModel($mainMetaDataId, $row['PROCESS_META_DATA_ID'], $row['META_TYPE_ID'], $selectedRow, false);

                        return array_merge(array('status' => 'success', 'processMetaDataId' => $row['PROCESS_META_DATA_ID'], 'metaTypeId' => $row['META_TYPE_ID'], 'selectedRow' => $selectedRow), $checkProcessAction);

                    } elseif ($advancedCriteria) {
                        
                        if (strpos($advancedCriteria, 'equal=') == false) {
                            
                            $rules = Str::lower($advancedCriteria);
                            
                            foreach ($selectedRow as $sk => $sv) {
                                if (is_string($sv) && strpos($sv, "'") === false) {
                                    $sv = "'".Str::lower($sv)."'";
                                } elseif (is_null($sv)) {
                                    $sv = "''";
                                }

                                $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                            }

                            $rules = Mdmetadata::defaultKeywordReplacer($rules);
                            $rules = Mdmetadata::criteriaMethodReplacer($rules);
                            $rules .= 'return true;';
                            
                            $rulesResult = Mdcommon::expressionEvalFix($rules);
                            
                            if ($rulesResult !== true) {
                                $returnMessage = Str::firstUpper(Lang::line($rulesResult));
                            }
                        }
                    }
                }
            }
        }

        return array('processNoAccess' => true, 'processName' => (isset($returnMessage) && $returnMessage) ? $returnMessage : 'Нөхцөл тохирохгүй байна', 'selectedRow' => $item);
    }

    public function checkCriteriaProcessByOneRowModel($mainMetaDataId, $processMetaDataId, $selectedRow) {
        
        $mainMetaDataIdPh    = $this->db->Param(0);
        $processMetaDataIdPh = $this->db->Param(1);

        $bindVars = array($this->db->addQ($mainMetaDataId), $this->db->addQ($processMetaDataId));
        
        $row = $this->db->GetRow("
            SELECT 
                PD.PROCESS_META_DATA_ID, 
                PD.CRITERIA, 
                MD.META_TYPE_ID 
            FROM META_DM_PROCESS_DTL PD 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PD.PROCESS_META_DATA_ID 
            WHERE PD.MAIN_META_DATA_ID = $mainMetaDataIdPh 
                AND PD.PROCESS_META_DATA_ID = $processMetaDataIdPh", $bindVars); 

        if ($row) {

            if (!empty($row['CRITERIA'])) {

                if (isset($selectedRow['children'])) {
                    unset($selectedRow['children']);
                }

                $rules = Str::lower($row['CRITERIA']);
                
                if (isset($selectedRow['children'])) {
                    unset($selectedRow['children']);
                }
                
                if (isset($selectedRow['pfnextstatuscolumn'])) {
                    unset($selectedRow['pfnextstatuscolumn']);
                }
            
                foreach ($selectedRow as $sk => $sv) {
                    if (is_string($sv) && strpos($sv, "'") === false) {
                        $sv = "'".Str::lower($sv)."'";
                    } elseif (is_null($sv)) {
                        $sv = "''";
                    }

                    $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                }

                $rules = Mdmetadata::defaultKeywordReplacer($rules);
                $rules = Mdmetadata::criteriaMethodReplacer($rules);

                if (trim($rules) != '' && Mdcommon::expressionEvalFixWithReturn($rules)) {
                    return array('status' => 'success');
                }

            } else {
                return array('status' => 'success');
            }   
        }

        return array('processNoAccess' => true, 'processName' => 'Нөхцөл тохирохгүй байна');
    }

    public function getCodeNameFieldNameModel($metaDataId) {

        $cache = phpFastCache();

        $data = $cache->get('dvStandartFields_'.$metaDataId);

        if ($data == null) {
            
            $metaDataIdPh = $this->db->Param(0);
            $bindVars = array($this->db->addQ($metaDataId));
        
            $id = $this->db->GetRow("
                SELECT 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    UPPER(COLUMN_NAME) AS COLUMN_NAME  
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                    AND INPUT_NAME = 'META_VALUE_ID'", $bindVars);

            $code = $this->db->GetRow("
                SELECT 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    UPPER(COLUMN_NAME) AS COLUMN_NAME   
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                    AND INPUT_NAME = 'META_VALUE_CODE'", $bindVars);

            $name = $this->db->GetRow("
                SELECT 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    UPPER(COLUMN_NAME) AS COLUMN_NAME   
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                    AND INPUT_NAME = 'META_VALUE_NAME'", $bindVars);

            $parent = $this->db->GetRow("
                SELECT 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    UPPER(COLUMN_NAME) AS COLUMN_NAME   
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                    AND INPUT_NAME = 'PARENT_ID'", $bindVars);

            $openTree = $this->db->GetRow("
                SELECT 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    UPPER(COLUMN_NAME) AS COLUMN_NAME 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                    AND INPUT_NAME = 'OPEN_TREE'", $bindVars);
            
            $value = $this->db->GetRow("
                SELECT 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    UPPER(COLUMN_NAME) AS COLUMN_NAME   
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                    AND INPUT_NAME = 'META_VALUE'", $bindVars);

            $data = array(
                'id' => '', 
                'code' => '', 
                'name' => '', 
                'parent' => '', 
                'opentree' => '', 
                'value' => '', 
                
                'idColumnName' => null,
                'codeColumnName' => null,
                'nameColumnName' => null,
                'parentColumnName' => null,
                'openTreeColumnName' => null,
                'valueColumnName' => null
            );

            if ($id) {
                $data['id'] = $id['FIELD_PATH'];
                $data['idColumnName'] = $id['COLUMN_NAME'];
            }
            if ($code) {
                $data['code'] = $code['FIELD_PATH'];
                $data['codeColumnName'] = $code['COLUMN_NAME'];
            }
            if ($name) {
                $data['name'] = $name['FIELD_PATH'];
                $data['nameColumnName'] = $name['COLUMN_NAME'];
            }
            if ($parent) {
                $data['parent'] = $parent['FIELD_PATH'];
                $data['parentColumnName'] = $parent['COLUMN_NAME'];
            }
            if ($openTree) {
                $data['opentree'] = $openTree['FIELD_PATH'];
                $data['openTreeColumnName'] = $openTree['COLUMN_NAME'];
            }
            if ($value) {
                $data['value'] = $value['FIELD_PATH'];
                $data['valueColumnName'] = $value['COLUMN_NAME'];
            }

            $cache->set('dvStandartFields_'.$metaDataId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function getIdCodeNameModel($metaDataId, $metaValueId) {

        $getCodeNameFieldName = self::getCodeNameFieldNameModel($metaDataId);
        $code = $name = $rowData = '';

        $id = isset($getCodeNameFieldName['id']) ? $getCodeNameFieldName['id'] : 'id';
        
        if ($id == '' && isset($getCodeNameFieldName['code']) && $getCodeNameFieldName['code'] != '') {
            $id = $getCodeNameFieldName['code'];
        }
        
        if (is_array($metaValueId)) {
            
            if (count($metaValueId) == 1) {
                $operator = '=';
                $metaValueId = $metaValueId[0];
            } else {
                $operator = 'IN';
                $metaValueId = Arr::implode_r(',', $metaValueId, true);
            }
            
        } else {
            if (strpos($metaValueId, ',') !== false) {
                $operator = 'IN';
            } else {
                $operator = '=';
            }
        }
        
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0,
            'ignorePermission' => 1,  
            'criteria' => array(
                $id => array(
                    array(
                        'operator' => $operator,
                        'operand' => $metaValueId
                    )
                )
            )
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
        if (isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $data = $data['result'];

            if (isset($data[1])) {

                $codeComma = $nameComma = '';

                foreach ($data as $sqlRow) {

                    if ($getCodeNameFieldName['code'] && isset($sqlRow[$getCodeNameFieldName['code']])) {
                        $codeComma .= $sqlRow[$getCodeNameFieldName['code']] . ', ';
                    }
                    if ($getCodeNameFieldName['name'] && isset($sqlRow[$getCodeNameFieldName['name']])) {
                        $nameComma .= $sqlRow[$getCodeNameFieldName['name']] . ', ';
                    }
                }

                $code = rtrim($codeComma, ', ');
                $name = rtrim($nameComma, ', ');

                $rowData = htmlentities(str_replace('&quot;', '\\&quot;', json_encode($data)), ENT_QUOTES, 'UTF-8');

            } else {

                $sqlLowerResult = $data[0];

                if ($getCodeNameFieldName['code'] && isset($sqlLowerResult[$getCodeNameFieldName['code']])) {
                    $code = $sqlLowerResult[$getCodeNameFieldName['code']];
                }
                if ($getCodeNameFieldName['name'] && isset($sqlLowerResult[$getCodeNameFieldName['name']])) {
                    $name = $sqlLowerResult[$getCodeNameFieldName['name']];
                }

                $rowData = htmlentities(str_replace('&quot;', '\\&quot;', json_encode($sqlLowerResult, JSON_UNESCAPED_UNICODE)), ENT_QUOTES, 'UTF-8');
            }
        }

        return array('code' => $code, 'name' => $name, 'rowData' => $rowData);
    }

    public function sendMailBySelectionRowsModel() {
        
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
        
        if (Input::postCheck('rowsAttachType')) {
            
            $response = self::sendMailByRowsAttachTypeModel();
            return $response;
        }
        
        $emailTo           = Input::post('emailTo');
        $emailSubject      = Input::post('emailSubject');
        $emailBody         = html_entity_decode(Input::post('emailBody'));
        $dataViewId        = Input::post('dataViewId');
        $sessionUserId     = Ue::sessionUserId();
        $selectedRows      = Input::postNonTags('selectedRows');
        $ignoreList        = Input::post('ignoreList');
        $sendMode          = Input::post('sendMode');
        $ignoreFromOwnMail = Input::post('ignoreFromOwnMail');
        $fileAttachDrillField = Input::post('fileAttachDrillField');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        
        if (!defined('SMTP_USER')) {
                
            $mail->SMTPAuth = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        } else {
            $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER; 
                $mail->Password = SMTP_PASS; 
            } else {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        }
        
        if (defined('SMTP_SSL_VERIFY') && !SMTP_SSL_VERIFY) {
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
            $mail->Hostname = SMTP_HOSTNAME;
        }        
        $mail->Port = SMTP_PORT;
        $mail->isHTML(true);
        
        $emailFrom = EMAIL_FROM;        
        $emailFromName = EMAIL_FROM_NAME;
        
        if (Input::isEmpty('setFrom') == false) {
            
            $emailFrom = Input::post('setFrom');
            $emailFromName = $emailFrom;
            
        } elseif ($ignoreFromOwnMail != 'true' && $personEmail = Ue::getSessionEmail()) {
            
            $emailFrom = $personEmail;
            $emailFromName = Ue::getSessionPersonName();
        }
        
        $mail->setFrom($emailFrom, $emailFromName);
        $mail->AddReplyTo($emailFrom, $emailFromName);
        
        $response = array('status' => 'success', 'message' => Lang::line('msg_mail_success'));
        $emailBodyTemplate = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
        
        $emlSignatureTemp = self::getEmlTemplateByCodeModel('emailSignature');
                    
        if (isset($emlSignatureTemp[0]['MESSAGE'])) {
            $emailBodyTemplate = str_replace('{htmlTable}', '{htmlTable}' . html_entity_decode($emlSignatureTemp[0]['MESSAGE']), $emailBodyTemplate);
        }
        
        if (Input::isEmpty('emailTplCode') == false) {
            
            $emlBodyTpl = self::getEmlBodyTplByCodeModel(Input::post('emailTplCode'));
            
            if ($emlBodyTpl && strpos($emlBodyTpl['BODY_TEMPLATE'], '[message]') !== false) {
                $bodyTemplate = html_entity_decode($emlBodyTpl['BODY_TEMPLATE']);
            }
        }
        
        $selectedRows = ($selectedRows) ? json_decode($selectedRows, true) : array();
        
        if ($sendMode == '' || $sendMode == 'allRowsEmail') {
            
            if ($ignoreList == 'false') {
                
                $rowsHtml = self::rowsToHtmlTable($dataViewId, $selectedRows, false, true);   
                
                if ($rowsHtml) {
                    $emailBody = empty($emailBody) ? '<br />'.$rowsHtml : $emailBody.'<br /><br />'.$rowsHtml;
                }
            }
            
            if (isset($bodyTemplate)) {
                $emailBody = str_replace('[message]', $emailBody, $bodyTemplate);
            }
            
            if (isset($selectedRows[0])) {
                
                $firstRow = $selectedRows[0];
            
                foreach ($firstRow as $rowKey => $rowVal) {
                    if (!is_array($rowVal)) {
                        $emailBody = str_ireplace('['.$rowKey.']', $rowVal, $emailBody);
                        $emailSubject = str_ireplace('['.$rowKey.']', $rowVal, $emailSubject);
                    }
                }
            }
            
            $mail->Subject = $emailSubject;
            $mail->AltBody = 'Veritech ERP - ' . $emailSubject;

            $emailBodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyTemplate);
            
            $mail->msgHTML($emailBodyContent);

            $emailList = array();

            if (is_array($emailTo)) {
                $emailToArr = array_unique($emailTo);
            } else {
                $emailToArr = array_map('trim', explode(';', rtrim($emailTo, ';')));
            }   

            if (count($emailToArr)) {

                $emailList = $emailToArr;
                $emailToCc = Input::post('emailToCc');

                if (!empty($emailToCc)) {
                    $emailToCcArr = array_map('trim', explode(';', rtrim($emailToCc, ';')));
                    
                    foreach ($emailToCcArr as $emailCc) {
                        $emailCc = trim($emailCc);
                        if ($emailCc) {
                            $mail->addCC($emailCc);
                        }
                    }
                }

                $emailToBcc = Input::post('emailToBcc');

                if (!empty($emailToBcc)) {
                    $emailToBccArr = array_map('trim', explode(';', rtrim($emailToBcc, ';')));
                    $emailList = array_merge_recursive($emailList, $emailToBccArr);
                }

                $emailList = array_unique($emailList);

                foreach ($emailList as $email) {
                    
                    $email = trim($email);
                    
                    if ($email) {
                        
                        if (!isValidEmail($email)) {
                            return array('status' => 'error', 'message' => 'Зөв имейл хаяг оруулна уу! '.$email);
                        }
                        
                        $mail->addAddress($email);

                        self::saveUserSendEmail($sessionUserId, $email);

                        if (isset($_FILES['file1']) && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
                            $mail->addAttachment($_FILES['file1']['tmp_name'], $_FILES['file1']['name']);
                        }

                        if (isset($_FILES['file2']) && $_FILES['file2']['error'] == UPLOAD_ERR_OK) {
                            $mail->addAttachment($_FILES['file2']['tmp_name'], $_FILES['file2']['name']);
                        }
                        
                        if (Input::postCheck('ecmContentAttachPath')) {
                            $ecmContentAttachPaths = $_POST['ecmContentAttachPath'];
                            foreach ($ecmContentAttachPaths as $p => $ecmContentAttachPath) {
                                $ecmContentAttachPath = Input::param($ecmContentAttachPath);
                                if (file_exists($ecmContentAttachPath)) {
                                    $mail->addAttachment($ecmContentAttachPath, Input::param($_POST['ecmContentAttachFileName'][$p]));
                                }
                            }
                        }
                    }
                }
                
                $row = array();
                
                if (issetParam($emlBodyTpl)) {
                    $row['email_template_id'] = $emlBodyTpl['ID'];
                }
                if ($recordId = issetVar($selectedRows[0]['id'])) {
                    $row['id'] = $recordId;
                }
                if ($ref_structure_id = Input::numeric('ref_structure_id')) {
                    $row['ref_structure_id'] = $ref_structure_id;
                }

                if (!$mail->send()) {
                    
                    $response = array('status' => 'error', 'message' => 'Хүсэлт амжилтгүй ахин илгээх үйлдэл хийнэ үү! '. $mail->ErrorInfo);
                    self::sentMailsToSaveLog($email, '[PHP] ' . $mail->ErrorInfo);
                    
                } else {
                    
                    $successEmail = array();
                    
                    foreach ($emailList as $email) {
                        
                        $email = trim($email);
                        
                        if ($email) {
                            self::sentMailsToSaveLog($email, '[PHP] sent', $row);
                            $successEmail[] = $email;
                        }
                    }
                    
                    if ($successEmail) {
                        $response = array('status' => 'success', 'message' => implode(',', $successEmail) . ' хаяг руу амжилттай илгээгдлээ.');
                    }
                }

                $mail->clearAllRecipients();                
            }
            
        } elseif ($sendMode == 'ccGroupEmail') {
            
            $groupEmail = strtolower(Input::post('groupEmail'));
            
            if (isset($selectedRows[0])) {
                
                $firstRow = $selectedRows[0];
            
                foreach ($firstRow as $rowKey => $rowVal) {
                    if (!is_array($rowVal)) {
                        $emailSubject = str_ireplace('['.$rowKey.']', $rowVal, $emailSubject);
                    }
                }
                
                if (!array_key_exists($groupEmail, $firstRow)) {
                    return array('status' => 'error', 'message' => 'groupEmail path дээрх багана олдсонгүй!');
                }
            }
            
            $mail->Subject = $emailSubject;
            $mail->AltBody = 'Veritech ERP - ' . $emailSubject;     
            
            foreach ($selectedRows as $row) {
                
                $email       = trim($row['email']);
                $emailSecond = trim($row[$groupEmail]);
                
                $rowsHtml    = self::rowsToHtmlTable($dataViewId, array($row), false, true);   
                
                if ($rowsHtml) {
                    $rowEmailBody = empty($emailBody) ? '<br />' . $rowsHtml : $emailBody . '<br /><br />' . $rowsHtml;
                } else {
                    $rowEmailBody = $emailBody;
                }
                
                if (isset($bodyTemplate)) {
                    $rowEmailBody = str_replace('[message]', $rowEmailBody, $bodyTemplate);
                }
            
                foreach ($row as $rowKey => $rowVal) {
                    if (!is_array($rowVal)) {
                        $rowEmailBody = str_ireplace('['.$rowKey.']', $rowVal, $rowEmailBody);
                    }
                }
                
                $emailBodyContent = str_replace('{htmlTable}', $rowEmailBody, $emailBodyTemplate);

                $mail->msgHTML($emailBodyContent);

                if (issetParam($emlBodyTpl)) {
                    $row['email_template_id'] = $emlBodyTpl['ID'];
                }
                if (Input::isEmpty('ref_structure_id') == false) {
                    $row['ref_structure_id'] = Input::post('ref_structure_id');
                }                
            
                if ($email && isValidEmail($email)) {
                    
                    $mail->addAddress($email);
                    self::saveUserSendEmail($sessionUserId, $email);
                    
                    if (isset($_FILES['file1']) && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
                        $mail->addAttachment($_FILES['file1']['tmp_name'], $_FILES['file1']['name']);
                    }

                    if (isset($_FILES['file2']) && $_FILES['file2']['error'] == UPLOAD_ERR_OK) {
                        $mail->addAttachment($_FILES['file2']['tmp_name'], $_FILES['file2']['name']);
                    }
                    
                    if (!$mail->send()) {
                        $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                        self::sentMailsToSaveLog($email, '[PHP] ' . $mail->ErrorInfo);
                    } else {
                        self::sentMailsToSaveLog($email, '[PHP] sent', $row);
                    }
                    
                    $mail->clearAllRecipients();
                }
                
                if ($emailSecond && isValidEmail($emailSecond)) {
                    
                    $mail->addAddress($emailSecond);
                    self::saveUserSendEmail($sessionUserId, $emailSecond);
                    
                    if (isset($_FILES['file1']) && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
                        $mail->addAttachment($_FILES['file1']['tmp_name'], $_FILES['file1']['name']);
                    }

                    if (isset($_FILES['file2']) && $_FILES['file2']['error'] == UPLOAD_ERR_OK) {
                        $mail->addAttachment($_FILES['file2']['tmp_name'], $_FILES['file2']['name']);
                    }
                    
                    if (!$mail->send()) {
                        $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                        self::sentMailsToSaveLog($email, '[PHP] ' . $mail->ErrorInfo);
                    } else {
                        self::sentMailsToSaveLog($email, '[PHP] sent', $row);
                    }
                    
                    $mail->clearAllRecipients();
                }
            }
            
            /*if ($groupEmail != '' && array_key_exists($groupEmail, $selectedRows[0])) {
                $groupedRows = Arr::groupByArray($selectedRows, $groupEmail);
                foreach ($groupedRows as $groupedEmail => $groupedRow) {}
            }*/
            
        } elseif ($sendMode == 'mailToEach') {
            
            if (isset($selectedRows[0])) {
                
                $firstRow = $selectedRows[0];
            
                if (!array_key_exists('email', $firstRow)) {
                    return array('status' => 'error', 'message' => 'email багана олдсонгүй!');
                }
            }
            
            if (isset($_FILES['file1']) && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
                $mail->addAttachment($_FILES['file1']['tmp_name'], $_FILES['file1']['name']);
            }

            if (isset($_FILES['file2']) && $_FILES['file2']['error'] == UPLOAD_ERR_OK) {
                $mail->addAttachment($_FILES['file2']['tmp_name'], $_FILES['file2']['name']);
            }
            
            if (isset($bodyTemplate)) {
                $emailBody = str_replace('[message]', $emailBody, $bodyTemplate);
            }
            
            $emailBodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyTemplate);
            
            foreach ($selectedRows as $row) {
                
                $email = trim($row['email']);
                
                if ($email && isValidEmail($email)) {
                    
                    $emailRowSubject = $emailSubject;
                    $emailRowBody    = $emailBodyContent;
                    
                    foreach ($row as $rowKey => $rowVal) {
                        if (!is_array($rowVal)) {
                            $emailRowSubject = str_ireplace('['.$rowKey.']', $rowVal, $emailRowSubject);
                            $emailRowBody    = str_ireplace('['.$rowKey.']', $rowVal, $emailRowBody);
                        }
                    }
                    
                    $mail->Subject = $emailRowSubject;
                    $mail->AltBody = 'Veritech ERP - ' . $emailRowSubject; 
                    
                    if ($fileAttachDrillField) {
                        
                        $attachFiles = self::fileAttachDrillFieldModel($dataViewId, $fileAttachDrillField, $row);
                        
                        if ($attachFiles) {
                            foreach ($attachFiles as $attachFile) {
                                if (file_exists($attachFile['physicalpath'])) {
                                    $mail->addAttachment($attachFile['physicalpath'], $attachFile['filename']);
                                }
                            }
                        }
                    }
                    
                    if (Input::postCheck('ecmContentAttachPath')) {
                        $ecmContentAttachPaths = $_POST['ecmContentAttachPath'];
                        foreach ($ecmContentAttachPaths as $p => $ecmContentAttachPath) {
                            $ecmContentAttachPath = Input::param($ecmContentAttachPath);
                            if (file_exists($ecmContentAttachPath)) {
                                $mail->addAttachment($ecmContentAttachPath, Input::param($_POST['ecmContentAttachPath'][$p]));
                            }
                        }
                    }
                    
                    $mail->msgHTML($emailRowBody);
                    $mail->addAddress($email);
                    
                    if (issetParam($emlBodyTpl)) {
                        $row['email_template_id'] = $emlBodyTpl['ID'];
                    }
                    if (Input::isEmpty('ref_structure_id') == false) {
                        $row['ref_structure_id'] = Input::post('ref_structure_id');
                    }

                    if (!$mail->send()) {
                        $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                        self::sentMailsToSaveLog($email, '[PHP] ' . $mail->ErrorInfo, $row);
                    } else {
                        self::sentMailsToSaveLog($email, '[PHP] sent', $row);
                    }

                    $mail->clearAllRecipients();
                }
            }
        }                

        return $response;
    }
    
    public function fileAttachDrillFieldModel($dataViewId, $fileAttachDrillField, $row) {
        
        if (!self::$fieldDrillConfigs) {
            $this->load->model('mdobject', 'middleware/models/');
            self::$fieldDrillConfigs = $this->model->getDrillDownMetaDataModel($dataViewId, $fileAttachDrillField);            
        }
        
        if (self::$fieldDrillConfigs) {
            
            foreach (self::$fieldDrillConfigs as $ddrow) {
                if ($ddrow['DEFAULT_VALUE']) {
                    if ($ddrow['TRG_PARAM']) {
                        $drillParams[$ddrow['TRG_PARAM']] = $ddrow['DEFAULT_VALUE'];
                    }
                } else {
                    if ($ddrow['TRG_PARAM']) {
                        $drillParams[$ddrow['TRG_PARAM']] = issetParam($row[$ddrow['SRC_PARAM']]);
                    }
                }                    
            }
            
            $_POST['pagingWithoutAggregate'] = 1;
            $_POST['drillDownDefaultCriteria'] = json_encode($drillParams);
            
            $result = (new Mdobject())->dataViewDataGrid(false, false, self::$fieldDrillConfigs[0]['LINK_META_DATA_ID']);

            if (isset($result['rows'][0])) {
                
                $rows = $result['rows'];
                $firstRow = $rows[0];
                
                if (isset($firstRow['filename']) && isset($firstRow['physicalpath'])) {
                    return $rows;
                }
            }
        }
        
        return null;
    }
    
    public function sendMailByRowsAttachTypeModel() {
        
        $emailTo        = Input::post('emailTo');
        $emailSubject   = Input::post('emailSubject');
        $emailBody      = html_entity_decode(Input::post('emailBody'));
        $dataViewId     = Input::post('dataViewId');
        $sessionUserId  = Ue::sessionUserId();
        $selectedRows   = Input::postNonTags('selectedRows');
        $rowsAttachType = Input::post('rowsAttachType');
        $footerSumCount = Input::post('footerSumCount');
        $drillDownField = Input::post('drillDownField');
        $footerSumCount = $footerSumCount ? Arr::decode($footerSumCount) : '';
        $emailFrom      = EMAIL_FROM;        
        $emailFromName  = EMAIL_FROM_NAME;
        
        $response          = array('status' => 'success', 'message' => $this->lang->line('msg_mail_success'));
        $emailBodyTemplate = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
        
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        
        if (!defined('SMTP_USER')) {
                
            $mail->SMTPAuth = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        } else {
            $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER; 
                $mail->Password = SMTP_PASS; 
            } else {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        }
        
        if (defined('SMTP_SSL_VERIFY') && !SMTP_SSL_VERIFY) {
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
            $mail->Hostname = SMTP_HOSTNAME;
        }        
        $mail->Port = SMTP_PORT;
        $mail->isHTML(true);
        $mail->Subject = $emailSubject;
        $mail->AltBody = 'Veritech ERP - ' . $emailSubject;
        
        if (Input::isEmpty('setFrom') == false) {
            $emailFrom = Input::post('setFrom');
            $emailFromName = $emailFrom;   
        }
        
        $mail->setFrom($emailFrom, $emailFromName); 
        $mail->AddReplyTo($emailFrom, $emailFromName);
            
        $selectedRows = ($selectedRows) ? json_decode($selectedRows, true) : array();
        $drillParams = $getDatasDrill = array();

        if ($drillDownField) {
            $this->load->model('mdobject', 'middleware/models/');
            $ddown = $this->model->getDrillDownMetaDataModel($dataViewId, $drillDownField);            

            if ($ddown) {
                foreach ($selectedRows as $selRow) {
                    foreach ($ddown as $ddrow) {
                        if ($ddrow['DEFAULT_VALUE']) {
                            if ($ddrow['TRG_PARAM']) {
                                $drillParams[$ddrow['TRG_PARAM']] = $ddrow['DEFAULT_VALUE'];
                            }
                        } else {
                            if ($ddrow['TRG_PARAM']) {
                                $drillParams[$ddrow['TRG_PARAM']] = issetParam($selRow[$ddrow['SRC_PARAM']]);
                            }
                        }                    
                    }

                    $_POST['drillDownDefaultCriteria'] = json_encode($drillParams);
                    $getDataDrill = (new Mdobject())->dataViewDataGrid(false, false, $ddown[0]['LINK_META_DATA_ID']);

                    $footerSumCount = $getDataDrill['footer'][0];
                    $getDatasDrill = array_merge($getDataDrill['rows'], $getDatasDrill);                    

                    $dataViewId = $ddown[0]['LINK_META_DATA_ID'];
                    $selectedRows = $getDatasDrill;
                }
            }
        }
        
        $emailCcList = array();

        $emailToCc = Input::post('emailToCc');

        if (!empty($emailToCc)) {
            $emailToCcArr = array_map('trim', explode(';', rtrim($emailToCc, ';')));
            $emailCcList = array_merge_recursive($emailCcList, $emailToCcArr);
        }

        $emailToBcc = Input::post('emailToBcc');

        if (!empty($emailToBcc)) {
            $emailToBccArr = array_map('trim', explode(';', rtrim($emailToBcc, ';')));
            $emailCcList = array_merge_recursive($emailCcList, $emailToBccArr);
        }
        
        $isExcel = $isPdf = $isBody = false;
        
        foreach ($rowsAttachType as $rowsAttachTypeVal) {
            if ($rowsAttachTypeVal == 'excel') {
                $isExcel = true;
            } elseif ($rowsAttachTypeVal == 'pdf') {
                $isPdf = true;
            } elseif ($rowsAttachTypeVal == 'body') {
                $isBody = true;
            }
        }
        
        if ($isExcel) {
            
            includeLib('Office/Excel/phpspreadsheet/vendor/autoload');
            
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        }
        
        if ($isPdf) {
            includeLib('PDF/tcpdf/tcpdf_include');
            $isFilePassword = Input::postCheck('isFilePassword');
        }
        
        if (!$isBody) {
            $bodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyTemplate);
        }
            
        $groupField = strtolower(Input::post('groupField'));
        $isGroupingField = false;

        if ($groupField && array_key_exists($groupField, $selectedRows[0])) {
            $groupedRows = Arr::groupByArray($selectedRows, $groupField);
            $isGroupingField = true;
        }

        $obj = new Mdobject();

        if ($isGroupingField) {

            foreach ($groupedRows as $groupedKey => $groupedRow) {

                $row  = $groupedRow['row'];
                $rows = $groupedRow['rows'];

                $email = trim($row['email']);

                if ($email && isValidEmail($email)) {

                    $mail->addAddress($email);

                    $exportData = array('status' => 'success', 'rows' => $rows);

                    $responseData = $obj->dataViewPrintExportData($dataViewId, $exportData, $footerSumCount, true);
                    
                    if ($isBody) {
                        $bodyContent = str_replace('{htmlTable}', $emailBody . '<br /><br />' . $responseData['data'], $emailBodyTemplate);
                    } 
                    
                    $mail->msgHTML($bodyContent);
                    
                    $fileName = issetDefaultVal($row['fileextendname'], 'Report');
                    
                    if ($isExcel) {
                                          
                        $headerRow = $responseData['headerRow'];
                        
                        $spreadsheet   = $reader->loadFromString($responseData['data']);
                        $sheet         = $spreadsheet->getActiveSheet();
                        $highestColumn = $sheet->getHighestDataColumn();
                        
                        $rangeCols = $this->excelColumnRange('A', $highestColumn);
                        foreach ($rangeCols as $ecolumn) {
                            if ($ecolumn == 'A') {
                                $sheet->getColumnDimension($ecolumn)->setAutoSize(true);
                            } else {
                                $sheet->getColumnDimension($ecolumn)->setAutoSize(false);
                                $sheet->getColumnDimension($ecolumn)->setWidth(20);
                            }
                        }
                        
                        $headerTemplateRow = 1;
                        
                        if ($responseData['headerTemplateRow']) {
                            $headerTemplateRow = $responseData['headerTemplateRow'] + 2;
                        }
                        $lastRow = count($selectedRows) + 2 + $headerTemplateRow;

                        $spreadsheet->getActiveSheet()->getStyle('A'.$headerTemplateRow.':'.$highestColumn.($headerTemplateRow - 1 + $headerRow))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('cccccc');
                        $spreadsheet->getActiveSheet()->getStyle('A'.$headerTemplateRow.':'.$highestColumn.($headerTemplateRow - 1 + $headerRow))->getAlignment()->setWrapText(true);
                        (Array) $headerStyles = array(
                            'alignment' => array(
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                            ),
                            'borders' => array(
                                'allBorders' => array(
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000')
                                )
                            )                            
                        );                       
                        $spreadsheet->getActiveSheet()->getStyle('A'.$headerTemplateRow.':'.$highestColumn.($headerTemplateRow - 1 + $headerRow))->applyFromArray($headerStyles); 
                        
                        //$spreadsheet->getActiveSheet()->getStyle('A'.$lastRow.':'.$highestColumn.$lastRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('cccccc');

                        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                        
                        ob_start();
                            $writer->save('php://output'); 
                            $excelData = ob_get_contents();
                        ob_end_clean();
                        
                        $mail->addStringAttachment($excelData, $fileName . '.xlsx');
                    }
                    
                    if ($isPdf) {
                        
                        $pdf = new TCPDF('L', 'mm', array(2300, 450), true, 'UTF-8', false);
                        
                        if ($isFilePassword && isset($row['filepassword']) && $row['filepassword']) {
                            $pdf->SetProtection(array('print', 'modify', 'copy'), $row['filepassword'], $row['filepassword'], 0, null);
                        }
                        
                        $pdf->setPrintHeader(false);
                        $pdf->setPrintFooter(false);
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetAutoPageBreak(true, 10);
                        $pdf->SetFont('arial', '', 12);
                        $pdf->AddPage();
                        $pdf->writeHTML($responseData['data'], true, false, false, false, '');
                        
                        $pdfString = $pdf->Output($fileName . '.pdf', 'S');
                        
                        $mail->addStringAttachment($pdfString, $fileName . '.pdf');
                    }

                    if (!$mail->send()) {
                        $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                        self::sentMailsToSaveLog($email, '[PHP] ' . $mail->ErrorInfo);
                    } else {
                        self::sentMailsToSaveLog($email);
                    }
                    
                    $mail->clearAttachments();
                    $mail->clearAllRecipients();
                }
            }

        } else {

            foreach ($selectedRows as $row) {

                $email = trim($row['email']);

                if ($email && isValidEmail($email)) {

                    $mail->addAddress($email);

                    $exportData = array('status' => 'success', 'rows' => array($row));

                    $responseData = $obj->dataViewPrintExportData($dataViewId, $exportData, $footerSumCount, true);

                    $bodyContent = $emailBodyContent . '<br />' . $responseData['data'];

                    $mail->msgHTML($bodyContent);

                    if (!$mail->send()) {
                        $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                        self::sentMailsToSaveLog($email, '[PHP] ' . $mail->ErrorInfo);
                    } else {
                        self::sentMailsToSaveLog($email);
                    }
                    
                    $mail->clearAttachments();
                    $mail->clearAllRecipients();
                }
            }
        }

        if ($emailCcList) {
            
            $this->load->model('mdobject', 'middleware/models/');
            $dvConfig = $this->model->getDataViewConfigRowModel($dataViewId);
            $fileName = $this->lang->line($dvConfig['LIST_NAME']);
            
            $exportData = array('status' => 'success', 'rows' => $selectedRows);

            $responseData = $obj->dataViewPrintExportData($dataViewId, $exportData, $footerSumCount, true);
            
            if ($responseData['status'] == 'success') {

                $emailCcList = array_unique($emailCcList);
                
                if ($isBody) {
                    $bodyContent = str_replace('{htmlTable}', $emailBody . '<br /><br />' . $responseData['data'], $emailBodyTemplate);
                } 
                
                $mail->msgHTML($bodyContent);
                
                if ($isExcel) {
                        
                    $headerRow = $responseData['headerRow'];

                    $spreadsheet   = $reader->loadFromString($responseData['data']);
                    $sheet         = $spreadsheet->getActiveSheet();
                    $highestColumn = $sheet->getHighestDataColumn();

                    $rangeCols = $this->excelColumnRange('A', $highestColumn);
                    foreach ($rangeCols as $ecolumn) {
                        if ($ecolumn == 'A') {
                            $sheet->getColumnDimension($ecolumn)->setAutoSize(true);
                        } else {
                            $sheet->getColumnDimension($ecolumn)->setAutoSize(false);
                            $sheet->getColumnDimension($ecolumn)->setWidth(20);
                        }
                    }

                    $spreadsheet->getActiveSheet()->getStyle('A1:'.$highestColumn.$headerRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('cccccc');
                    $spreadsheet->getActiveSheet()->getStyle('A1:'.$highestColumn.$headerRow)->getAlignment()->setWrapText(true);

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

                    ob_start();
                        $writer->save('php://output'); 
                        $excelData = ob_get_contents();
                    ob_end_clean();

                    $mail->addStringAttachment($excelData, $fileName . '.xlsx');
                }
                
                if ($isPdf) {
                    
                    $pdf = new TCPDF('L', 'mm', array(2300, 450), true, 'UTF-8', false);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetMargins(10, 10, 10);
                    $pdf->SetAutoPageBreak(true, 10);
                    $pdf->SetFont('arial', '', 12);
                    $pdf->AddPage();
                    $pdf->writeHTML($responseData['data'], true, false, false, false, '');

                    $pdfString = $pdf->Output($fileName . '.pdf', 'S');

                    $mail->addStringAttachment($pdfString, $fileName . '.pdf');
                }

                foreach ($emailCcList as $email) {

                    $email = trim($email);
                    
                    if ($email && isValidEmail($email)) {
                        
                        $mail->addAddress($email);

                        if (!$mail->send()) {
                            $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                            self::sentMailsToSaveLog($email, '[PHP] ' . $mail->ErrorInfo);
                        } else {
                            self::sentMailsToSaveLog($email);
                        }

                        $mail->clearAllRecipients();
                    }
                }

            } else {
                $response = $responseData;
            }
        }
        
        return $response;
    }

    public function rowsToHtmlTable($dataViewId, $selectedRows, $wfm = false, $isRowsArray = false) {
        
        $headerColumns = self::getOnlyEmailColumns($dataViewId); 
        
        if ($headerColumns) {
            
            if (!$isRowsArray) {
                $selectedRows = ($selectedRows) ? json_decode($selectedRows, true) : array();
            }

            $text = ($wfm) ? Lang::lineDefault('Trans_01', 'Зочилж харах') : 'Бүгдийг харах';
            $more = Lang::lineDefault('more', 'Дэлгэрэнгүй');

            $linkedDataViewId = $dataViewId;
            $criteriaColumns = array('id' => 'id');
            $criterias = $html = '';
            
            $cellAlignArr = array();

            $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0">';

                $html .= '<thead>';
                    $html .= '<tr>';
                        $html .= '<th style="width:30px; text-align: center" width="30px" align="center">№</th>';

                        foreach ($headerColumns as $head) {

                            $confAlign = $head['BODY_ALIGN'];

                            if ($confAlign) {
                                $cellAlignArr[$head['FIELD_PATH']] = ' style="text-align: '.$confAlign.'" align="'.$confAlign.'"';
                            } else {
                                $cellAlignArr[$head['FIELD_PATH']] = ' style="text-align: left" align="left"';
                            }

                            $html .= '<th>'.Lang::line($head['LABEL_NAME']).'</th>';
                        }

                        $html .= '<th style="text-align: center"></th>';
                    $html .= '</tr>';
                $html .= '</thead>';

                $html .= '<tbody>';
                    foreach ($selectedRows as $k => $row) {

                        $urlStr = '';

                        foreach ($criteriaColumns as $criteriaColumnKey => $criteriaColumn) {
                            $urlStr .= '&dv['.$criteriaColumn.'][]='.$row[$criteriaColumnKey];
                        }

                        $html .= '<tr>';
                            $html .= '<td style="text-align: center" align="center">'.(++$k).'</td>';

                            foreach ($headerColumns as $body) {
                                $html .= '<td'.$cellAlignArr[$body['FIELD_PATH']].'>'.$row[$body['FIELD_PATH']].'</td>';
                            }

                            $html .= '<td style="text-align: center" align="center"><a href="'.URL.'mdobject/dataview/'.$linkedDataViewId.'?'.$urlStr.'" target="_blank">'.$more.'</a></td>';
                        $html .= '</tr>';

                        $criterias .= $urlStr;
                    }

                $html .= '</tbody>';

            $html .= '</table>';

            $html .= '<br /><a href="'.URL.'mdobject/dataview/'.$linkedDataViewId.'?'.$criterias.'" target="_blank">'. $text .'</a>';

            return $html;
            
        } else {
            return null;
        }
    }

    public function getOnlyEmailColumns($dataViewId) {
        
        $cache = phpFastCache();
        $data = $cache->get('dvOnlyEmailColumns_'.$dataViewId);
        
        if ($data == null) {
            
            $data = $this->db->GetAll("
                SELECT
                    LABEL_NAME, 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    DATA_TYPE AS META_TYPE_CODE, 
                    COLUMN_WIDTH, 
                    BODY_ALIGN  
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND PARENT_ID IS NULL 
                    AND IS_SELECT = 1                       
                    AND FEATURE_NUM IS NOT NULL 
                ORDER BY FEATURE_NUM ASC", array($dataViewId));
            
            $cache->set('dvOnlyEmailColumns_'.$dataViewId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function getTreeListFiscalPeriodModel() {
        $response = array();
        $andWhere = 'fp.TYPE_ID = 4';

        $periodYearId = Session::get(SESSION_PREFIX.'periodYearId');
        $periodId = Session::get(SESSION_PREFIX.'periodId');

        $parentId = Input::get('parentId');
        $disabled = true;
        
        if ($parentId !== '#') {
            $andWhere = " fp.PARENT_ID = $parentId";
            $disabled = false;
        }

        $rows = $this->db->GetAll("
            SELECT 
                fp.ID, 
                fp.PERIOD_NAME, 
                fp.IS_CURRENT ,
                fp.IS_CLOSED,
                fp.PARENT_ID,
                CASE WHEN tem.IS_CHILD > 0 THEN 1 ELSE 0 END CHILDREN
            FROM FIN_FISCAL_PERIOD fp
                LEFT JOIN (
                    SELECT 
                        COUNT(ID) AS IS_CHILD, 
                        PARENT_ID
                    FROM FIN_FISCAL_PERIOD 
                    GROUP BY PARENT_ID
                ) tem ON fp.ID = tem.PARENT_ID
            WHERE  $andWhere
            ORDER BY fp.START_DATE, fp.END_DATE ASC");
        
        if ($rows) {
            foreach ($rows as $row) {
                $icon = '';
                $opened = $selected = false;

                if ($row['IS_CLOSED'] === '1') {
                    $icon = ' fa fa-lock';
                }
                if ($row['ID'] === $periodYearId || $row['PARENT_ID'] === $periodYearId) {
                    $opened = true;
                }
                if ($row['ID'] === $periodId) {
                    $opened = true;
                    $selected = true;
                }

                $response[] = array(
                    'text'      => $row['PERIOD_NAME'],
                    'id'        => $row['ID'],
                    'icon'      => $icon. ' text-orange-400',
                    'state'     => array(
                        'selected' => $selected,
                        'loaded'   => true,
                        'disabled' => $disabled,
                        'opened'   => $opened,
                        'parentid' => false,
                    ),
                    'children' => ($row['CHILDREN'] === '1') ? true : false
                );    
            }
        }
        return $response;
    }

    public function sendMailBySelectionUserModel() {
        
        $wfmlogId = Input::post('wfmlogId');
        $nextUserIds = Input::post('nextUserIds');
        $idPh = $this->db->Param(0);
        
        if ($nextUserIds) {
            
            $idsSplit = array_chunk($nextUserIds, 500); 
            $where    = ' AND (';

            foreach ($idsSplit as $idsArr) {
                $where .= ' T0.USER_ID IN (' . implode(',', $idsArr) . ') OR';
            }

            $where = rtrim($where, ' OR');
            $where .= ')';
                            
            $emailToArr = $this->db->GetAll("
                SELECT 
                    ".$this->db->IfNull('T1.EMAIL', 'T2.EMPLOYEE_EMAIL')." AS EMAIL 
                FROM UM_USER T0 
                    INNER JOIN UM_SYSTEM_USER T1 ON T1.USER_ID = T0.SYSTEM_USER_ID 
                    LEFT JOIN HRM_EMPLOYEE T2 ON T2.PERSON_ID = T1.PERSON_ID 
                WHERE (T1.EMAIL IS NOT NULL OR T2.EMPLOYEE_EMAIL IS NOT NULL) $where", 
                array($wfmlogId)
            );
            
        } else {
        
            $emailToArr = $this->db->GetAll("
                SELECT 
                    ".$this->db->IfNull('SU.EMAIL', 'HE.EMPLOYEE_EMAIL')." AS EMAIL 
                FROM META_WFM_LOG WL
                    INNER JOIN META_WFM_ASSIGNMENT ASS ON WL.WFM_STATUS_ID = ASS.WFM_STATUS_ID 
                        AND WL.REF_STRUCTURE_ID = ASS.REF_STRUCTURE_ID 
                        AND WL.RECORD_ID = ASS.RECORD_ID 
                    INNER JOIN UM_USER UU ON ASS.USER_ID = UU.USER_ID
                    INNER JOIN UM_SYSTEM_USER SU ON UU.SYSTEM_USER_ID = SU.USER_ID
                    LEFT JOIN HRM_EMPLOYEE HE ON SU.PERSON_ID = HE.PERSON_ID 
                WHERE WL.ID = $idPh AND (SU.EMAIL IS NOT NULL OR HE.EMPLOYEE_EMAIL IS NOT NULL)", 
                array($wfmlogId)
            );
        }
        
        if (!$emailToArr) {
            return array('status' => 'error', 'message' => 'И-мейл хаяг олдсонгүй!');
        }
        
        $dataViewId = Input::numeric('metaDataId');
        $selectedRow = Input::post('selectedRow');
         
        $userData = $this->db->GetRow("
            SELECT 
                SUBSTR(ve.LAST_NAME, 1, 1)||'.'||ve.FIRST_NAME AS EMPLOYEE_NAME, 
                ve.POSITION_NAME, 
                ve.DEPARTMENT_NAME, 
                su.USER_ID 
            FROM UM_SYSTEM_USER su 
                INNER JOIN VW_EMPLOYEE ve ON su.PERSON_ID = ve.PERSON_ID 
            WHERE su.USER_ID = $idPh", 
            array(Ue::sessionUserId())
        );

        $dataRow = Arr::decode($selectedRow);

        $metaDataCode = $this->db->GetOne("
            SELECT 
                LOWER(CON.FIELD_PATH) 
            FROM META_GROUP_CONFIG CON 
            WHERE CON.MAIN_META_DATA_ID = $idPh 
                AND CON.INPUT_NAME = 'META_VALUE_CODE'", array($dataViewId));
        
        $metaDataName = $this->db->GetOne("
            SELECT 
                LOWER(CON.FIELD_PATH) 
            FROM META_GROUP_CONFIG CON 
            WHERE CON.MAIN_META_DATA_ID = $idPh 
                AND CON.INPUT_NAME = 'META_VALUE_NAME'", array($dataViewId));

        $listName = $this->db->GetOne("
            SELECT 
                " . $this->db->IfNull("GLC.LIST_NAME", "MD.META_DATA_NAME") . " 
            FROM META_DATA MD 
                INNER JOIN META_GROUP_LINK GLC ON MD.META_DATA_ID = GLC.META_DATA_ID 
            WHERE MD.META_DATA_ID = $idPh", array($dataViewId));

        $emailSubject = 'Veritech ERP | '.$this->lang->lineDefault('wfm_reminder_mail_subject', 'Сануулах');
        
        if ($this->lang->isExisting('wfm_reminder_mail_body')) {
            
            $headerBody = $this->lang->lineVar('wfm_reminder_mail_body', array(
                'listname'   => (($listName) ? $this->lang->line($listName) : ''), 
                'statusname' => (isset($dataRow['wfmstatusname']) ? $dataRow['wfmstatusname'] : ''), 
                'code'       => (isset($dataRow[$metaDataCode]) ? $dataRow[$metaDataCode] : ''), 
                'name'       => (isset($dataRow[$metaDataName]) ? $dataRow[$metaDataName] : ''), 
            )); 
            
        } else {
            $headerBody = "Таньд <strong>". (isset($dataRow['wfmstatusname']) ? $dataRow['wfmstatusname'] : '') ."</strong> төлөвтэй (". (isset($dataRow[$metaDataCode]) ? $dataRow[$metaDataCode] : '') ." | ". (isset($dataRow[$metaDataName]) ? $dataRow[$metaDataName] : '') .") <strong>". (($listName) ? $this->lang->line($listName) : '')  ."</strong> жагсаалтанд бичлэг хүлээгдэж байна.";
        }
        
        $footerBody = '';

        if ($userData) {
            $footerBody = $userData['DEPARTMENT_NAME'] . ', ' . $userData['EMPLOYEE_NAME'] .' (' . $userData['POSITION_NAME'] .')';
        }

        $emailBody = $headerBody;

        $rowsHtml = self::rowsToHtmlTable($dataViewId, json_encode([$dataRow], JSON_UNESCAPED_UNICODE), true);
        $emailBody = empty($emailBody) ? '<br />'.$rowsHtml : $emailBody.'<br /><br />'.$rowsHtml . '<br />' . $footerBody;

        $emailBodyContent = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
        $emailBodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyContent);

        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        
        if (!defined('SMTP_USER')) {
                
            $mail->SMTPAuth = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        } else {
            $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER; 
                $mail->Password = SMTP_PASS; 
            } else {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        }
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
            $mail->Hostname = SMTP_HOSTNAME;
        }        
        $mail->Port = SMTP_PORT;
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME); 
        $mail->Subject = $emailSubject;
        $mail->isHTML(true);
        $mail->Body = $emailBodyContent;
        $mail->AltBody = $emailSubject;

        $response = array('status' => 'success', 'message' => $this->lang->line('msg_mail_success'), 'mailList' => $emailToArr);

        foreach ($emailToArr as $email) {

            $mail->addAddress(trim($email['EMAIL']));

            if (!$mail->send()) {
                $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
            }

            $mail->clearAllRecipients();
        }

        return $response;
    }

    public function getImportExcelTemplateModel($showTemplateIds = '') {
        
        $where = '';
        
        if ($showTemplateIds) {
            
            $showTemplateIds = explode(',', $showTemplateIds);
            $ids = array();
            
            foreach ($showTemplateIds as $id) {
                $ids[] = Input::param($id);
            }
            
            $where = 'WHERE ID IN ('.implode(',', $ids).')';
        }
        
        $data = $this->db->GetAll("SELECT ID, CODE, NAME FROM IMP_EXCEL_TEMPLATE $where ORDER BY NAME ASC");
        
        return $data;
    }

    public function importingExcelTemplateModel() {
        
        $templateId    = Input::post('templateId');
        $data          = file_get_contents($_FILES['excelFile']['tmp_name']);
        $fileContent   = base64_encode($data);
        $fileName      = $_FILES['excelFile']['name'];
        $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
        $logId         = getUID();
        $isNoWait      = Config::getFromCache('isExcelImportNoWait');
        $configWsUrl   = Config::getFromCache('heavyServiceAddress');
        $importCommand = 'xls_imp_001';
        
        if ($fileExtension == 'csv') {
            $importCommand = 'csv_imp_001';
        }
        
        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 3))))) {
            $serviceAddress = $configWsUrl;
        } else {
            $serviceAddress = self::$gfServiceAddress;
        }
        
        if ($isNoWait == '1') {
            
            try {
                
                $data = array(
                    'ID'              => $logId, 
                    'TEMPLATE_ID'     => $templateId, 
                    'FILE_EXTENSION'  => $fileExtension, 
                    'FILE_NAME'       => $fileName, 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
                    'CREATED_DATE'    => Date::currentDate()
                );

                $this->db->AutoExecute('IMP_EXCEL_LOG', $data);
                $this->db->UpdateClob('IMP_EXCEL_LOG', 'REQUEST_DATA', $fileContent, 'ID = '.$logId);
        
                $param = array(
                    'logId'               => $logId, 
                    'templateId'          => $templateId, 
                    'fileExtension'       => $fileExtension, 
                    'isSaveWhenAllRowSuccessful' => Input::postCheck('isSaveWhenAllRowSuccessful') ? true : false
                );
                
                ini_set('max_execution_time', 2);
                ini_set('default_socket_timeout', 2);
                
                $result = $this->ws->runSerializeResponse($serviceAddress, $importCommand, $param);
                
                return array('status' => 'success', 'message' => 'Эксель файлыг хүлээж авлаа, та үр дүнг логын жагсаалтаас харна уу! <a href="mdobject/dataview/16072733644623&dv[templateid][]='.$templateId.'" target="_blank">Лог харах</a>');
                
            } catch (Exception $ex) {
                return array('status' => 'info', 'message' => $ex->getMessage());
            }
        }
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);
        
        if ($logResult = self::checkImpExcelLog($logId, $templateId, $fileContent, $fileName, $fileExtension)) {
            return $logResult;
        }

        $param = array(
            'logId'               => $logId, 
            'templateId'          => $templateId, 
            'fileExtension'       => $fileExtension, 
            'byteValue'           => $fileContent, 
            'isReturnSuccessRows' => Input::postCheck('isReturnSuccessRows') ? true : false, 
            'isSaveWhenAllRowSuccessful' => Input::postCheck('isSaveWhenAllRowSuccessful') ? true : false
        );
        
        $processParam = Input::post('param');
        
        if ($processParam) {
            $param['parameters'] = $processParam;
        }

        $result = $this->ws->runSerializeResponse($serviceAddress, $importCommand, $param);

        if ($result['status'] == 'success') {

            if (isset($result['result']) && $result['result']) {

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
                fwrite($f, $result['result']);
                fclose($f);
                
                $globeMessage = Lang::line('excel_import_result');
                
                if ($globeMessage == 'excel_import_result') {
                    $globeMessage = 'Алдаа гарсан тул та алдаатай файлыг татаж авч засаад дахин сонгоно уу!';
                }
                
                $response = array(
                    'status'        => 'info', 
                    'message'       => $globeMessage, 
                    'uniqId'        => $uniqId, 
                    'fileExtension' => $fileExtension 
                );

            } else {
                $response = array('status' => 'success', 'message' => 'Амжилттай импорт хийгдлээ');
            }

        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }

    public function importExcelTemplateAddSaveModel() {

        try {

            $hdrId = getUID();
            $fileExtension = null;

            if (isset($_FILES['excelFile']['name']) && $_FILES['excelFile']['name'] != '') {
                $fileExtension = strtolower(substr($_FILES['excelFile']['name'], strrpos($_FILES['excelFile']['name'], '.') + 1));
            }

            $hdrData = array(
                'ID' => $hdrId, 
                'CODE' => Input::post('templateCode'), 
                'NAME' => Input::post('templateName'), 
                'ROW_INDEX' => Input::post('rowIndex'), 
                'SHEET_NAME' => Input::post('sheetName'), 
                'FILE_EXTENSION' => $fileExtension, 
                'PROCESS_META_DATA_ID' => Input::post('excelTemplateProcessId'), 
                'SYSTEM_ID' => Input::post('systemId'), 
                'MODULE_ID' => Input::post('moduleId'), 
                'IS_HIERARCHY' => Input::postCheck('isHierarchy') ? 1 : 0, 
                'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                'CREATED_USER_ID' => Ue::sessionUserKeyId()
            );

            $result = $this->db->AutoExecute('IMP_EXCEL_TEMPLATE', $hdrData);

            if ($result) {

                if ($fileExtension) {

                    $data = file_get_contents($_FILES['excelFile']['tmp_name']);
                    $fileContent = base64_encode($data);

                    $this->db->UpdateClob('IMP_EXCEL_TEMPLATE', 'FILE_DATA', $fileContent, 'ID = '.$hdrId);
                }

                if (Input::postCheck('paramPath')) {

                    $paramPathData = $_POST['paramPath'];

                    foreach ($paramPathData as $k => $paramPath) {

                        $dtlData = array(
                            'ID' => getUID(), 
                            'EXCEL_TEMPLATE_ID' => $hdrId, 
                            'PARAM_PATH' => Input::param($paramPath), 
                            'COLUMN_NAME' => Input::param($_POST['columnName'][$k]), 
                            'DEFAULT_VALUE' => Input::param($_POST['defaultValue'][$k]), 
                            'EXPRESSION' => Input::param($_POST['expression'][$k]), 
                            'ORDER_NUMBER' => $k 
                        );

                        $this->db->AutoExecute('IMP_EXCEL_TEMPLATE_OPTIONS', $dtlData);
                    }
                }

                $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));

            } else {
                $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'));
            }

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }

    public function getExcelTemplateByIdModel($id) {

        $row = $this->db->GetRow("
            SELECT 
                IT.ID, 
                IT.CODE, 
                IT.NAME,
                IT.ROW_INDEX, 
                IT.SHEET_NAME, 
                IT.SYSTEM_ID, 
                US.SYSTEM_CODE, 
                US.SYSTEM_NAME, 
                IT.MODULE_ID, 
                UM.CODE AS MODULE_CODE, 
                UM.NAME AS MODULE_NAME, 
                IT.PROCESS_META_DATA_ID, 
                IT.IS_HIERARCHY,   
                MD.META_DATA_CODE AS PROCESS_META_DATA_CODE, 
                MD.META_DATA_NAME AS PROCESS_META_DATA_NAME 
            FROM IMP_EXCEL_TEMPLATE IT 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = IT.PROCESS_META_DATA_ID 
                LEFT JOIN UM_SYSTEM US ON US.SYSTEM_ID = IT.SYSTEM_ID 
                LEFT JOIN UM_MODULE UM ON UM.MODULE_ID = IT.MODULE_ID 
            WHERE IT.ID = $id");

        return $row;
    }

    public function getExcelTemplateParamsByIdModel($id, $processId) {

        $data = $this->db->GetAll("
            SELECT 
                DTL.ID, 
                DTL.PARAM_PATH, 
                UPPER(DTL.COLUMN_NAME) AS COLUMN_NAME, 
                DTL.DEFAULT_VALUE, 
                PAL.LABEL_NAME, 
                DTL.EXPRESSION  
            FROM IMP_EXCEL_TEMPLATE_OPTIONS DTL 
                LEFT JOIN META_PROCESS_PARAM_ATTR_LINK PAL ON PAL.PROCESS_META_DATA_ID = $processId 
                    AND PAL.IS_INPUT = 1 
                    AND LOWER(PAL.PARAM_REAL_PATH) = LOWER(DTL.PARAM_PATH) 
            WHERE DTL.EXCEL_TEMPLATE_ID = $id 
            ORDER BY DTL.ORDER_NUMBER ASC");

        return $data;
    }

    public function getExcelTemplateFileDataById($id) {
        $row = $this->db->GetRow("SELECT NAME, FILE_DATA, FILE_EXTENSION FROM IMP_EXCEL_TEMPLATE WHERE ID = ".$this->db->Param(0), array($id));
        return $row;
    }
    
    public function getExcelLogFileDataById($id) {
        $row = $this->db->GetRow("
            SELECT 
                ET.NAME AS TEMPLATE_NAME, 
                EL.REQUEST_DATA, 
                EL.RESPONSE_DATA, 
                EL.FILE_NAME, 
                EL.FILE_EXTENSION, 
                EL.STATUS 
            FROM IMP_EXCEL_LOG EL 
                INNER JOIN IMP_EXCEL_TEMPLATE ET ON ET.ID = EL.TEMPLATE_ID 
            WHERE EL.ID = ".$this->db->Param(0), array($id));
        return $row;
    }

    public function importExcelTemplateEditSaveModel() {

        try {

            $hdrId = Input::post('templateId');

            $hdrData = array(
                'CODE' => Input::post('templateCode'), 
                'NAME' => Input::post('templateName'), 
                'ROW_INDEX' => Input::post('rowIndex'), 
                'SHEET_NAME' => Input::post('sheetName'), 
                'PROCESS_META_DATA_ID' => Input::post('excelTemplateProcessId'), 
                'SYSTEM_ID' => Input::post('systemId'), 
                'MODULE_ID' => Input::post('moduleId'), 
                'IS_HIERARCHY' => Input::postCheck('isHierarchy') ? 1 : 0, 
                'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                'MODIFIED_USER_ID' => Ue::sessionUserKeyId()
            );

            $result = $this->db->AutoExecute('IMP_EXCEL_TEMPLATE', $hdrData, 'UPDATE', 'ID = '.$hdrId);

            if ($result) {

                if (isset($_FILES['excelFile']) && $_FILES['excelFile']['name'] != '') {

                    $fileExtension = strtolower(substr($_FILES['excelFile']['name'], strrpos($_FILES['excelFile']['name'], '.') + 1));
                    $data = file_get_contents($_FILES['excelFile']['tmp_name']);
                    $fileContent = base64_encode($data);

                    $this->db->AutoExecute('IMP_EXCEL_TEMPLATE', array('FILE_EXTENSION' => $fileExtension), 'UPDATE', 'ID = '.$hdrId);
                    $this->db->UpdateClob('IMP_EXCEL_TEMPLATE', 'FILE_DATA', $fileContent, 'ID = '.$hdrId);
                }

                if (Input::postCheck('paramPath')) {

                    $paramPathData = $_POST['paramPath'];

                    foreach ($paramPathData as $k => $paramPath) {

                        $isNew = Input::param($_POST['isNew'][$k]);

                        if ($isNew == '1') {

                            $dtlData = array(
                                'ID' => getUID(), 
                                'EXCEL_TEMPLATE_ID' => $hdrId, 
                                'PARAM_PATH' => Input::param($paramPath), 
                                'COLUMN_NAME' => Input::param($_POST['columnName'][$k]), 
                                'DEFAULT_VALUE' => Input::param($_POST['defaultValue'][$k]), 
                                'EXPRESSION' => Input::param($_POST['expression'][$k]), 
                                'ORDER_NUMBER' => $k 
                            );

                            $this->db->AutoExecute('IMP_EXCEL_TEMPLATE_OPTIONS', $dtlData);

                        } elseif ($isNew == '0') {

                            $id = Input::param($_POST['id'][$k]);

                            $dtlData = array(
                                'PARAM_PATH' => Input::param($paramPath), 
                                'COLUMN_NAME' => Input::param($_POST['columnName'][$k]), 
                                'DEFAULT_VALUE' => Input::param($_POST['defaultValue'][$k]), 
                                'EXPRESSION' => Input::param($_POST['expression'][$k]), 
                                'ORDER_NUMBER' => $k 
                            );

                            $this->db->AutoExecute('IMP_EXCEL_TEMPLATE_OPTIONS', $dtlData, 'UPDATE', 'ID = '.$id);

                        } elseif ($isNew == '2') {

                            $id = Input::param($_POST['id'][$k]);

                            $this->db->Execute('DELETE FROM IMP_EXCEL_TEMPLATE_OPTIONS WHERE ID = '.$id);
                        }

                    }
                }

                $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));

            } else {
                $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'));
            }

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function checkImpExcelLog($logId, $templateId, $fileContent, $fileName, $fileExtension) {
        
        try {
            
            $firstContent = substr($fileContent, 0, 2000);
            $lastContent  = substr($fileContent, -2000);

            $checkContent = $firstContent . $lastContent;

            $row = $this->db->GetRow("SELECT ID, STATUS, RESPONSE_DATA FROM IMP_EXCEL_LOG WHERE CHECK_DATA = '$checkContent'");

            if ($row) {

                if ($row['STATUS'] == '') {

                    return array('status' => 'info', 'message' => 'Уг файлыг уншуулж байгаа тул та түр хүлээнэ үү!');

                } elseif ($row['STATUS'] == 'success') {

                    return array('status' => 'info', 'message' => 'Уг файл өмнө нь уншсан байна!');

                } elseif ($row['STATUS'] == 'exception') {

                    return array('status' => 'error', 'message' => $row['RESPONSE_DATA']);

                } elseif ($row['STATUS'] == 'error') {

                    $cacheTmpDir = Mdcommon::getCacheDirectory();
                    $tempdir     = $cacheTmpDir . '/excelimport';

                    $uniqId    = getUID();
                    $file_path = $tempdir.'/'.$uniqId.'.txt';

                    $f = fopen($file_path, "w+");
                    fwrite($f, $row['RESPONSE_DATA']);
                    fclose($f);

                    $globeMessage = Lang::line('excel_import_result');

                    if ($globeMessage == 'excel_import_result') {
                        $globeMessage = 'Алдаа гарсан тул та алдаатай файлыг татаж авч засаад дахин сонгоно уу!';
                    }

                    return array(
                        'status'        => 'info', 
                        'message'       => $globeMessage, 
                        'uniqId'        => $uniqId, 
                        'fileExtension' => $fileExtension 
                    );

                } else {
                    return array('status' => 'error', 'message' => 'Unknown error');
                }

            } else {

                $data = array(
                    'ID'              => $logId, 
                    'CHECK_DATA'      => $checkContent, 
                    'TEMPLATE_ID'     => $templateId, 
                    'FILE_NAME'       => $fileName, 
                    'FILE_EXTENSION'  => $fileExtension, 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
                    'CREATED_DATE'    => Date::currentDate()
                );

                $this->db->AutoExecute('IMP_EXCEL_LOG', $data);
                
                $this->db->UpdateClob('IMP_EXCEL_LOG', 'REQUEST_DATA', $fileContent, 'ID = '.$logId);
                
                $beforeDate = Date::beforeDate('Y-m-d', '-7 days');
        
                $this->db->Execute("DELETE FROM IMP_EXCEL_LOG WHERE CREATED_DATE < ".$this->db->ToDate("'$beforeDate'", 'YYYY-MM-DD'));
            }
            
            return false;
            
        } catch (ADODB_Exception $ex) {
            return false;
        }
    }
    
    public function sqlDecryptModel() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        try {
            includeLib('Compress/Compression');
            
            $time_start = microtime(true); 
            $size       = 500;
            
            $rowsCount = $this->db->GetOne("
                SELECT 
                    COUNT(*) 
                FROM META_GROUP_LINK 
                WHERE TABLE_NAME IS NOT NULL 
                    AND LENGTH(TABLE_NAME) > 30"); 
            
            $pages = ceil($rowsCount / $size);
            
            $this->db->Execute("DELETE FROM TMP_DV_TABLE_NAME");
            
            for ($p = 1; $p <= $pages; $p++) {
                
                $rows = $this->db->GetAll("
                    SELECT * FROM
                    (
                        SELECT a.*, rownum r__
                        FROM
                        (
                            SELECT 
                                ID,
                                META_DATA_ID,
                                TABLE_NAME
                            FROM META_GROUP_LINK
                            WHERE TABLE_NAME IS NOT NULL
                                AND LENGTH(TABLE_NAME) > 30 
                            ORDER BY ID ASC 
                        ) a
                        WHERE rownum < ((:pageNumber * :pageSize) + 1)
                    )
                    WHERE r__ >= (((:pageNumber-1) * :pageSize) + 1)", array('pageNumber' => $p, 'pageSize' => $size));
                
                foreach ($rows as $k => $row) {
                        
                    $sql = Compression::decompress($row['TABLE_NAME']);
                    $id = getUIDAdd($k);

                    $data = array(
                        'ID' => $id, 
                        'META_DATA_ID' => $row['META_DATA_ID']
                    );

                    $this->db->AutoExecute('TMP_DV_TABLE_NAME', $data);
                    $this->db->UpdateClob('TMP_DV_TABLE_NAME', 'SQL_STR', $sql, 'ID = '.$id);
                }
            }
            
            $rowsCount = $this->db->GetOne("
                SELECT 
                    COUNT(*) 
                FROM META_GROUP_CONFIG 
                WHERE TABLE_NAME IS NOT NULL 
                    AND LENGTH(TABLE_NAME) > 30"); 
            
            if ($rowsCount) {
                
                $pages = ceil($rowsCount / $size);
                
                for ($p = 1; $p <= $pages; $p++) {
                
                    $rows = $this->db->GetAll("
                        SELECT * FROM
                        (
                            SELECT a.*, rownum r__
                            FROM
                            (
                                SELECT 
                                    ID,
                                    MAIN_META_DATA_ID,
                                    TABLE_NAME, 
                                    FIELD_PATH 
                                FROM META_GROUP_CONFIG 
                                WHERE TABLE_NAME IS NOT NULL
                                    AND LENGTH(TABLE_NAME) > 30 
                                ORDER BY ID ASC 
                            ) a
                            WHERE rownum < ((:pageNumber * :pageSize) + 1)
                        )
                        WHERE r__ >= (((:pageNumber-1) * :pageSize) + 1)", array('pageNumber' => $p, 'pageSize' => $size));

                    foreach ($rows as $k => $row) {

                        $sql = Compression::decompress($row['TABLE_NAME']);
                        $id = getUIDAdd($k);

                        $data = array(
                            'ID' => $id, 
                            'META_DATA_ID' => $row['MAIN_META_DATA_ID'], 
                            'FIELD_PATH' => $row['FIELD_PATH']
                        );

                        $this->db->AutoExecute('TMP_DV_TABLE_NAME', $data);
                        $this->db->UpdateClob('TMP_DV_TABLE_NAME', 'SQL_STR', $sql, 'ID = '.$id);
                    }
                }
            }
            
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start) / 60;
            
            return 'Success - '.$execution_time.' minutes';
            
        } catch (ADODB_Exception $ex) {
            return $ex->getMessage();
        }
    }
    
    public function saveStarRatingModel() {
        
        $row = Input::post('row');
        $param = array(
            'id' => $row['id'], 
            'rate' => Input::post('rate')
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'UPDATE_PAPER_CHECK_BOOK_STAR_002', $param);
        
        if ($result['status'] == 'success') {
            
            $this->load->model('mdobject', 'middleware/models/');
            $metaDataId = Input::numeric('metaDataId');
            
            unset($_POST);
            
            $_POST['metaDataId'] = $metaDataId;
            $_POST['newWfmStatusid'] = $row['starwfmstatusid'];
            $_POST['dataRow'] = $row;
            
            $setStatus = $this->model->setRowWfmStatusModel();
            
            $response = array('status' => 'success', 'message' => 'Success');
            
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }
    
    public function gmapInfoWindowByDvModel() {
        
        $dvId = Input::post('dvId');
        $row  = Input::post('rowData');
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'showQuery' => 0,
            'ignorePermission' => 1
        );
        
        if (array_key_exists('id', $row)) {
            unset($row['id']);
        }
        
        foreach ($row as $key => $val) {
            $param['criteria'][$key][] = array('operator' => '=', 'operand' => $val);
        }
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if (isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $this->load->model('mdobject', 'middleware/models/');
            $attributes = $this->model->getDataViewMetaValueAttributes(null, null, $dvId);
            
            $id = isset($attributes['id']) ? strtolower($attributes['id']) : null;
            $code = isset($attributes['code']) ? strtolower($attributes['code']) : null;
            $name = isset($attributes['name']) ? strtolower($attributes['name']) : null;
            
            $gMap = array();
            
            foreach ($data['result'] as $key => $row) {
                $gMap[$key] = $row;
                $gMap[$key]['title'] = $row[$name];
                $gMap[$key]['rowData'] = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            }
            
            $this->load->model('mdobject', 'middleware/models/');
            $ddown = $this->model->getDrillDownMetaDataModel($dvId, $name);
            
            $clickFunction = '';
            
            if ($ddown) {
                
                $link_metatypecode = $ddown[0]['META_TYPE_CODE'];
                $link_linkmetadataid = $ddown[0]['LINK_META_DATA_ID'];
                $link_linkcriteria = $ddown[0]['CRITERIA'];
                $clinkMetadataId = issetParam($ddown[0]['CLINK_META_DATA_ID']);
                $sizeDrillDownArray = count($ddown);
                $indexDrillDownArray = 1;
                $sourceParam = '';
                $isnewTab = 'true';
                
                if (isset($ddown[0]['SHOW_TYPE'])) {
                    $showType = strtolower($ddown[0]['SHOW_TYPE']);
                    if ($showType == 'tab') {
                        $isnewTab = 'true';
                    } elseif ($showType) {
                        $isnewTab = "'".$showType."'";
                    }
                }
                
                foreach ($ddown as $drillValue) {
                    if ($drillValue['DEFAULT_VALUE']) {
                        if ($indexDrillDownArray === $sizeDrillDownArray) {
                            $sourceParam .= ($drillValue['TRG_PARAM']) ? $drillValue['TRG_PARAM'] . "=" . $drillValue['DEFAULT_VALUE'] : '';
                        } else {
                            $sourceParam .= ($drillValue['TRG_PARAM']) ? $drillValue['TRG_PARAM'] . "=" . $drillValue['DEFAULT_VALUE'] . "&" : '';
                        }
                    } else {
                        if ($indexDrillDownArray === $sizeDrillDownArray) {
                            $sourceParam .= ($drillValue['TRG_PARAM']) ? $drillValue['TRG_PARAM'] . '={'.$drillValue['SRC_PARAM'].'}' : '';
                        } else {
                            $sourceParam .= ($drillValue['TRG_PARAM']) ? $drillValue['TRG_PARAM'] . "={".$drillValue['SRC_PARAM']."}&" : '';
                        }
                    }
                    
                    $indexDrillDownArray++;
                }
            
                $clickFunction = "gridDrillDownLink(this, '', '" . $link_metatypecode . "', '" . $clinkMetadataId . "', '" . str_replace("'", "\'", $link_linkcriteria) . "', '" . $dvId . "', '" . $name . "', '" . $link_linkmetadataid . "', '" . $sourceParam . "', $isnewTab, true);";
            }
            
            return array('status' => 'success', 'data' => $gMap, 'clickFunction' => $clickFunction);
        }
        
        return array('status' => 'info');
    }
    
    public function getEmlTemplateByCodeModel($code) {
        
        $code = explode('||', Str::lower($code));
        
        $data = $this->db->GetAll("
            SELECT 
                CODE, 
                NAME, 
                SUBJECT, 
                MESSAGE, 
                DIRECT_URL 
            FROM EML_TEMPLATE 
            WHERE LOWER(CODE) IN ('".Arr::implode_r("','", $code, true)."')"
        );        
        
        return $data;
    }
    
    public function getEmlBodyTplByCodeModel($code) {
        $val = $this->db->GetRow("SELECT BODY_TEMPLATE, ID FROM EML_TEMPLATE WHERE LOWER(CODE) = ".$this->db->Param(0), array(Str::lower($code)));
        return $val;
    }
    
    public function sentMailsToSaveLog($email, $status = '[PHP] sent', $arrParams = array()) {
        
        try {
            
            $ipAddress   = get_client_ip();
            $userId      = Ue::sessionUserId();
            $currentDate = Date::currentDate();
                
            $data = array(
                'ID'          => getUID(), 
                'EMAIL'       => $email, 
                'ACTION_DATE' => $currentDate, 
                'STATUS'      => $status, 
                'FROM_IP'     => $ipAddress, 
                'USER_ID'     => $userId
            );
            if (issetParam($arrParams['email_template_id'])) {
                $data['EMAIL_TEMPLATE_ID'] = $arrParams['email_template_id'];
            }
            if (issetParam($arrParams['id'])) {
                $data['RECORD_ID'] = $arrParams['id'];
            }
            if (issetParam($arrParams['ref_structure_id'])) {
                $data['REF_STUCTURE_ID'] = $arrParams['ref_structure_id'];
            }

            $this->db->AutoExecute('EML_EMAIL_LOG', $data);
            
            $response = array('status' => 'success', 'message' => 'Success');
            
        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function saveUserSendEmail($userId, $email) {

        if (empty($email)) {
            return true;
        }
        
        $row = $this->db->GetRow("
            SELECT 
                ID 
            FROM UM_USER_SEND_EMAIL 
            WHERE USER_ID = ".$this->db->Param(0)." 
                AND LOWER(EMAIL) = ".$this->db->Param(1), 
            array($userId, strtolower($email))
        );

        if (!$row) {
            $data = array(
                'ID' => getUID(), 
                'USER_ID' => $userId, 
                'EMAIL' => trim($email)
            );
            $this->db->AutoExecute('UM_USER_SEND_EMAIL', $data);
        }

        return true;
    }    
    
    public function getEmailAutoCompleteModel() {

        $sessionUserId = Ue::sessionUserId();
        $data = $this->db->GetAll("SELECT EMAIL FROM UM_USER_SEND_EMAIL WHERE USER_ID = ".$this->db->Param(0)." ORDER BY EMAIL ASC", array($sessionUserId));
        $array = array();

        if ($data) {
            foreach ($data as $row) {
                $array[] = $row['EMAIL'];
            }
        }

        return $array;
    }    
    
    public function getSetFromEmailsModel() {

        $sessionUserId = Ue::sessionUserId();
        $data = $this->db->GetAll("SELECT EMAIL FROM UM_USER_SET_FROM_EMAIL WHERE SYSTEM_USER_ID = ".$this->db->Param(0)." ORDER BY EMAIL ASC", array($sessionUserId));
        
        if ($personEmail = Ue::getSessionEmail()) {
            array_unshift($data, array('EMAIL' => $personEmail));
        }
        
        return $data;
    }   
    
    public function saveRemovedLookupItemModel() {
        
        try {
            
            $data = array(
                'ID'           => getUID(), 
                'META_DATA_ID' => Input::numeric('lookupId'), 
                'VALUE_ID'     => Input::numeric('rowId'), 
                'IS_REMOVE'    => 1
            );
            
            $this->db->AutoExecute('META_DATA_VALUE_SUGGEST', $data);
            
            $response = array('status' => 'success');
            
        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getDataMartObjectAttributesModel() {
        
        $data = array();
        $templateId = Input::numeric('templateId');
        $serviceId = Input::numeric('serviceId');
        
        if ($templateId && $serviceId) {
            
            $param = array(
                'systemMetaGroupId' => '1577172425103',
                'ignorePermission' => 1, 
                'showQuery' => 0,
                'criteria' => array(
                    'templateId' => array(
                        array(
                            'operator' => '=',
                            'operand' => $templateId
                        )
                    ), 
                    'serviceId' => array(
                        array(
                            'operator' => '=',
                            'operand' => $serviceId
                        )
                    )
                )
            );

            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

            if (isset($result['result']) && isset($result['result'][0])) {
                unset($result['result']['aggregatecolumns']);
                unset($result['result']['paging']);
                $data = $result['result'];
            }
        }
        
        return $data;
    }
    
    public function newDataMartObjectRelationModel() {
        
        $sourceId = Input::numeric('sourceId');
        $targetId = Input::numeric('targetId');
        $sourceFieldId = Input::numeric('sourceFieldId');
        $targetFieldId = Input::numeric('targetFieldId');
        
        if ($sourceId && $targetId && $sourceFieldId && $targetFieldId) {
            
            $param = array(
                'indicatorCode'   => 'link',
                'indicatorName'   => 'link',
                'relationTypeId'  => '51', 
                'templateId'      => $sourceId,
                'relatedObjectId' => $targetId,
                'sId'             => $sourceFieldId, 
                'tId'             => $targetFieldId, 
                'kpiIndicator' => array(
                    'code' => 'link',
                    'name' => 'link',
                    'isActive' => '1',
                    'showType' => 'object',
                    'name2' => 'link'
                ),
                'kpiIndicatorValue' => array(),
                'kpiTemplateDtlFact' => array('showType' => 'object'),
                'kpiTemplateMap' => array(
                    array(
                        'trgTemplateId' => $targetId
                    )
                )
            );

            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'eaObjTypeIndicatorDv_0010', $param);

            if ($result['status'] == 'success') {
                
                if (isset($result['result']['id']) && $result['result']['id']) {
                    
                    $dtlIdRow = self::getLastSrcTplDtlId($result['result']['id']);
                    
                    if ($dtlIdRow) {
                        $result = array(
                            'status'   => 'success', 
                            'id'       => $dtlIdRow['ID'], 
                            'srcDtlId' => $dtlIdRow['SRC_TEMPLATE_DTL_ID'], 
                            'trgDtlId' => $dtlIdRow['TRG_TEMPLATE_DTL_ID'], 
                            'name'     => $dtlIdRow['NAME']
                        );
                    } else {
                        $result = array('status' => 'error', 'message' => $result['result']['id'] . ' уг id-аар утга олдсонгүй');
                    }
                    
                } else {
                    $result = array('status' => 'error', 'message' => 'Output ID path invalid');
                }
                
            } else {
                $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
            
        } else {
            $result = array('status' => 'error', 'message' => 'Invalid ids!');
        }
        
        return $result;
    }
    
    public function getLastSrcTplDtlId($dtlId) {
        
        $row = $this->db->GetRow("
            SELECT 
                KT.ID, 
                KTD_SRC.ID AS SRC_TEMPLATE_DTL_ID, 
                KTD_TRG.ID AS TRG_TEMPLATE_DTL_ID, 
                KT_SRC.NAME || ' links(холбогдоно) ' || KT_TRG.NAME AS NAME 
            FROM KPI_TEMPLATE KT 
                INNER JOIN KPI_TEMPLATE_DTL KTD_SRC ON KT.ID = KTD_SRC.TEMPLATE_ID 
                INNER JOIN KPI_INDICATOR KI_SRC ON KTD_SRC.INDICATOR_ID = KI_SRC.ID 
                    AND KI_SRC.CODE = 'srcId' 
                INNER JOIN KPI_TEMPLATE_DTL KTD_TRG ON KT.ID = KTD_TRG.TEMPLATE_ID 
                INNER JOIN KPI_INDICATOR KI_TRG ON KTD_TRG.INDICATOR_ID = KI_TRG.ID 
                    AND KI_TRG.CODE = 'trgId' 
                INNER JOIN KPI_TEMPLATE KT_SRC ON KT.SRC_TEMPLATE_ID = KT_SRC.ID 
                INNER JOIN KPI_TEMPLATE KT_TRG ON KT.TRG_TEMPLATE_ID = KT_TRG.ID 
            WHERE KT.SRC_TEMPLATE_DTL_ID = ".$this->db->Param(0)." 
                AND KT.TYPE_ID = 102", array($dtlId));
        
        return $row;
    }
    
    public function getDataMartDvRowsModel($dvId, $criteria = array()) {
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => $criteria
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = array();
        }
        
        return $data;
    }
    
    public function getDataMartGetDataModel($bpCode, $param) {

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, $bpCode, $param);

        if (isset($result['status']) && $result['status'] == 'success') {
            $data = $result['result'];
        } else {
            $data = array();
        }
        
        return $data;
    }
    
    public function saveDataMartRelationConfigModel() {
        
        $data = Input::post('data');
        parse_str($data, $dataArr);
        
        $serviceId = $dataArr['serviceId'];
        
        if (is_numeric($serviceId)) {
            
            try {
                
                $connections = Input::post('connections');
                $tempDtl = $fieldDtl = array();
                
                foreach ($connections as $k => $conn) {
                    
                    $tempDtl[] = array(
                        'serviceId'        => $serviceId, 
                        'pathNumber'       => 1, 
                        'srcTemplateId'    => Input::param($conn['sourceId']), 
                        'srcTemplateDtlId' => Input::param($conn['sourceDtlId']), 
                        'trgTemplateId'    => Input::param($conn['targetId']), 
                        'trgTemplateDtlId' => Input::param($conn['targetDtlId']),
                        'orderNumber'      => $k
                    );
                }
                
                if (isset($dataArr['pivotAttr'])) {
                    
                    $fields = $dataArr['pivotAttr'];
                    $n = 0;

                    foreach ($fields as $objectId => $attrs) {

                        foreach ($attrs as $attrId => $val) {
                            
                            $fieldDtlRow = array(
                                'serviceId'     => $serviceId, 
                                'templateId'    => $objectId, 
                                'templateDtlId' => $attrId, 
                                'paramArea'     => $val['type'], 
                                'labelName'     => $val['labelName'], 
                                'labelName2'    => issetParam($val['labelName2']), 
                                'isFilter'      => $val['isFilter'],  
                                'expression'    => $val['expression'], 
                                'criteria'      => $val['criteria'], 
                                'orderNumber'   => $n, 
                                'serviceDtlFieldExpId' => $val['dtlFieldExpId'], 
                                'refIndicatorId'       => issetParam($val['indicator']), 
                                'refIndicatorShowType' => issetParam($val['showtype']), 
                                'columnExpression'     => issetParam($val['colExpression'])
                            );
                            
                            if (isset($val['dtl']) && $val['dtl']) {
                                $fieldDtlRow['eaServiceDMDtlDesign'] = json_decode($val['dtl'], true);
                            }
                            
                            $fieldDtl[] = $fieldDtlRow;

                            $n++;
                        }
                    }
                }
                
                $param = array(
                    'id'                  => $serviceId, 
                    'graphJson'           => Input::post('positions'), 
                    'eaServiceDMDtl'      => $fieldDtl, 
                    'eaServiceDMTemplate' => $tempDtl
                );
                
                $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'eaServiceConfigDV_001', $param);
                
                if ($result['status'] == 'success') {
                    
                    $listMetaDataId = self::getDVIdByServiceIdModel($serviceId);
                    
                    if ($listMetaDataId) {
                        (new Mdmeta())->dvCacheClearByMetaId($listMetaDataId);
                    }
                    
                    $response = array('status' => 'success', 'dvId' => $listMetaDataId, 'message' => $this->lang->line('msg_save_success'));
                    
                } else {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
                
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        return $response;
    }
    
    public function getDataMartGraphJsonModel($id) {
        $json = $this->db->GetOne("SELECT GRAPH_JSON FROM EA_SERVICE WHERE ID = ".$this->db->Param(0), array($id));
        return $json;
    }
    
    public function getDVIdByServiceIdModel($id) {
        $dvId = $this->db->GetOne("SELECT LIST_META_DATA_ID FROM EA_SERVICE WHERE ID = ".$this->db->Param(0), array($id));
        return $dvId;
    }
    
    public function getTempIdByServiceIdModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                LIST_META_DATA_ID, 
                TEMPLATE_ID, 
                HIDE_ROW_TOTAL, 
                HIDE_COLUMN_TOTAL, 
                IS_COLLAPSED 
            FROM EA_SERVICE 
            WHERE ID = ".$this->db->Param(0), array($id));
        return $row;
    }
    
    public function excelColumnRange($first, $second) {
        $second++;
        $letters = array();
        $letter = $first;

        while ($letter !== $second) {
            $letters[] = $letter++;
        }

        return $letters;
    }
    
    public function getDvRowsByCriteriaModel($attr = array()) {
        
        if (!$attr) {
            $dvId = Input::numeric('dvId');
        }
        
        if (!$dvId) {
            return array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'showQuery' => 0,
            'ignorePermission' => 1
        );
        
        if (Input::postCheck('criteria')) {
            
            $criteria = Input::post('criteria');
            
            foreach ($criteria as $row) {
                $param['criteria'][$row['path']] = array(
                    array(
                        'operator' => $row['operator'],
                        'operand' => $row['operand']
                    )
                );
            }
        }

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['status']) && $data['status'] == 'success') {
            
            if (isset($data['result'][0])) {
                
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
                
                $standardField = self::getCodeNameFieldNameModel($dvId);
                
                if (isset($standardField['name']) && $standardField['name']) {
                    $nameField = $standardField['name'];
                } else {
                    $nameField = 'name';
                }

                $response = array('status' => 'success', 'nameField' => $nameField, 'rows' => $data['result']);
                
            } else {
                $response = array('status' => 'success', 'rows' => array());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
        
        return $response;
    }
    
    public function saveDvDmRecordMapModel() {
        
        $rows = json_decode(Input::postNonTags('rows'), true);
        
        $recordId = Input::post('recordId');
        $srcRefStructureId = Input::numeric('srcRefStructureId');
        $trgRefStructureId = Input::numeric('refStructureId');
        $dataViewId = Input::numeric('dataViewId');
        $srcName = Input::post('srcName');
        $trgName = Input::post('trgName');
        $trgWfmStatusId = Input::numeric('trgWfmStatusId');
        $workFlowId = Input::numeric('workFlowId');
        $srcWfmWorkflowId = Input::numeric('srcWfmWorkflowId');
        
        $this->load->model('mdobject', 'middleware/models/');
        $srcRefRow = $this->model->getDVMainQueriesModel($srcRefStructureId);
        $srcTableName = $srcRefRow['TABLE_NAME'];
        
        $trgRefRow = $this->model->getDVMainQueriesModel($trgRefStructureId);
        $trgTableName = $trgRefRow['TABLE_NAME'];
        
        if (isset($_POST['param'])) {
            
            $paramData = $_POST['param'];
            $kpiData = $paramData['kpiDmDtl.pdfColumnName'];
            $kpiFact = $paramData['kpiDmDtl.fact1']; 
            $kpiParam = array();
            
            foreach ($kpiData as $k => $kpiRow) {
                
                $columnName   = Input::param($kpiRow[0]);
                $templateId   = $paramData['kpiDmDtl.kpiTemplateId'][$k][0];
                $factType     = issetParam($paramData['kpiDmDtl.factType'][$k][0]);
                $value        = isset($kpiFact[$k]) ? $kpiFact[$k][0] : ''; 
                
                if ($value && ($factType == 'decimal' || $factType == 'bigdecimal')) {
                        
                    $value = Number::decimal(Input::param($value));

                } elseif ($value && $factType == 'multicombo') {

                    $value = Arr::implode_r(',', $kpiFact[$k], true);
                }
                
                if ($columnName) {
                    $kpiParam['templateId'] = $templateId;
                    $kpiParam[$columnName] = is_array($value) ? Arr::implode_r(',', $value, true) : Input::param($value);
                }
            }
        }
        
        foreach ($rows as $row) {
            
            $param = array(
                'srcTableName'      => $srcTableName, 
                'srcRecordId'       => $row['id'], 
                'trgTableName'      => $trgTableName, 
                'trgRecordId'       => $recordId, 
                'srcWfmWorkflowId'  => $srcWfmWorkflowId, 
                'trgWfmWorkflowId'  => $workFlowId, 
                'srcWfmStatusId'    => $row['wfmstatusid'], 
                'trgWfmStatusId'    => $trgWfmStatusId, 
                'srcName'           => $srcName, 
                'trgName'           => $trgName, 
                'srcRefStructureId' => $srcRefStructureId, 
                'trgRefStructureId' => $trgRefStructureId, 
                'dataViewId'        => $dataViewId
            );
            
            if (isset($kpiParam) && $kpiParam) {
                $param = array_merge($param, $kpiParam);
            }
            
            $result = $this->ws->runResponse(self::$gfServiceAddress, 'META_DM_RECORD_MAP_STRUCTURE_M_001', $param);

            if (isset($result['status']) && $result['status'] != 'success') {
                
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
        }
        
        return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
    }
    
    public function historyDvDmRecordMapModel() {
            
        $param = array(
            'systemMetaGroupId' => Input::numeric('dvId'), 
            'recordId' => Input::numeric('id')
        );
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'get_semantics_history', $param);

        if (isset($result['status']) && $result['status'] == 'success') {
            return array('status' => 'success', 'list' => $result['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function selectedRowsToPdfZipModel() {
        
        try {
            
            $mode           = Input::post('mode');
            $selectedRows   = Input::postNonTags('selectedRows');
            $drillDownField = Input::post('drillDownField');
            $dataViewId     = Input::numeric('dataViewId');

            if ($drillDownField) {

                $drillParams = $getDatasDrill = array();

                $this->load->model('mdobject', 'middleware/models/');
                $ddown = $this->model->getDrillDownMetaDataModel($dataViewId, $drillDownField);            

                if ($ddown) {

                    foreach ($selectedRows as $selRow) {
                        foreach ($ddown as $ddrow) {
                            if ($ddrow['DEFAULT_VALUE']) {
                                if ($ddrow['TRG_PARAM']) {
                                    $drillParams[$ddrow['TRG_PARAM']] = $ddrow['DEFAULT_VALUE'];
                                }
                            } else {
                                if ($ddrow['TRG_PARAM']) {
                                    $drillParams[$ddrow['TRG_PARAM']] = issetParam($selRow[$ddrow['SRC_PARAM']]);
                                }
                            }                    
                        }

                        $_POST['drillDownDefaultCriteria'] = json_encode($drillParams);

                        $getDataDrill = $this->model->dataViewDataGridModel(false, $ddown[0]['LINK_META_DATA_ID']);

                        $footerSumCount = $getDataDrill['footer'][0];
                        $getDatasDrill = array_merge($getDataDrill['rows'], $getDatasDrill);                    

                        $dataViewId = $ddown[0]['LINK_META_DATA_ID'];
                        $selectedRows = $getDatasDrill;
                    }
                }
            }

            $groupField = strtolower(Input::post('groupField'));
            $isGroupingField = false;

            if ($groupField && array_key_exists($groupField, $selectedRows[0])) {
                $groupedRows = Arr::groupByArray($selectedRows, $groupField);
                $isGroupingField = true;
            }

            includeLib('PDF/tcpdf/tcpdf_include');

            $cacheTmpDir = Mdcommon::getCacheDirectory();
            $tempdir     = $cacheTmpDir . '/zip';

            if (!is_dir($tempdir)) {

                mkdir($tempdir, 0777);

            } else {

                $files = glob($tempdir.'/*');
                $now   = time();

                foreach ($files as $file) {
                    if (is_file($file) && ($now - filemtime($file) >= 300)) {
                        unlink($file);
                    } 
                }
            }

            $zip = new ZipArchive();        
            $zipName = $tempdir . '/' . time() . '.zip';
            $zip->open($zipName, ZipArchive::CREATE);

            $obj = new Mdobject();

            if ($isGroupingField) {

                foreach ($groupedRows as $groupedKey => $groupedRow) {

                    $row  = $groupedRow['row'];
                    $rows = $groupedRow['rows'];

                    $exportData = array('status' => 'success', 'rows' => $rows);

                    $responseData = $obj->dataViewPrintExportData($dataViewId, $exportData, $footerSumCount, true);

                    $fileName = issetDefaultVal($row['fileextendname'], 'Report');

                    $pdf = new TCPDF('L', 'mm', array(2300, 450), true, 'UTF-8', false);

                    if (isset($row['filepassword']) && $row['filepassword']) {
                        $pdf->SetProtection(array('print', 'modify', 'copy'), $row['filepassword'], $row['filepassword'], 0, null);
                    }

                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetMargins(10, 10, 10);
                    $pdf->SetAutoPageBreak(true, 10);
                    $pdf->SetFont('arial', '', 12);
                    $pdf->AddPage();
                    $pdf->writeHTML($responseData['data'], true, false, false, false, '');

                    $pdfString = $pdf->Output($fileName . '.pdf', 'S');

                    $zip->addFromString($fileName . '.pdf', $pdfString);
                }
            }

            $zip->close();
            
            $response = array('status' => 'success', 'zipPath' => $zipName);
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function dvFilterLookupSuggestValModel() {
        
        $mainDvId = Input::numeric('mainDvId');
        $controlDvId = Input::numeric('controlDvId');
        
        if ($mainDvId && $controlDvId) {
            
            $param = array(
                'systemMetaGroupId' => $controlDvId,
                'showQuery' => 0,
                'ignorePermission' => 1
            );

            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
            if ($data['status'] == 'success') {
                
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
                
                if (isset($data['result'][0])) {
                    
                    $response = array('status' => 'success', 'data' => $data['result']);
                    
                    $getCodeNameFieldName = self::getCodeNameFieldNameModel($controlDvId);

                    $id = (isset($getCodeNameFieldName['id']) && $getCodeNameFieldName['id']) ? $getCodeNameFieldName['id'] : 'id';
                    $name = (isset($getCodeNameFieldName['name']) && $getCodeNameFieldName['name']) ? $getCodeNameFieldName['name'] : 'name';
                    
                    $response['id'] = $id;
                    $response['name'] = $name;
                    $response['selected'] = null;
                    
                    $savedIds = self::dvFilterLookupSuggestedValuesModel($mainDvId, $controlDvId);
                    
                    if ($savedIds) {
                        $response['selected'] = Arr::implode_key(',', $savedIds, 'VALUE_ID', true);
                    }
        
                } else {
                    $response = array('status' => 'error', 'message' => 'Empty!');
                }
            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid ids!');
        }
        
        return $response;
    }
    
    public function dvFilterLookupSuggestedValuesModel($mainDvId, $controlDvId) {
        
        $mainDvIdPh = $this->db->Param(0);
        $controlDvIdPh = $this->db->Param(1);
        $userIdPh = $this->db->Param(2);
        $sessionUserId = Ue::sessionUserId();
        
        $data = $this->db->GetAll("
            SELECT 
                VALUE_ID 
            FROM META_DATA_VALUE_SUGGEST 
            WHERE MAIN_META_DATA_ID = $mainDvIdPh 
                AND META_DATA_ID = $controlDvIdPh 
                AND CREATED_USER_ID = $userIdPh", 
            array($mainDvId, $controlDvId, $sessionUserId)
        );
        
        return $data;
    }
    
    public function dvFilterLookupSuggestValSaveModel() {
        
        $mainDvId = Input::numeric('mainDvId');
        $controlDvId = Input::numeric('controlDvId');
        
        if ($mainDvId && $controlDvId) {
            
            try {
                
                $mainDvIdPh = $this->db->Param(0);
                $controlDvIdPh = $this->db->Param(1);
                $userIdPh = $this->db->Param(2);
                $sessionUserId = Ue::sessionUserId();
                $filter = Input::post('filter');
                
                $this->db->Execute("
                    DELETE  
                    FROM META_DATA_VALUE_SUGGEST 
                    WHERE MAIN_META_DATA_ID = $mainDvIdPh 
                        AND META_DATA_ID = $controlDvIdPh 
                        AND CREATED_USER_ID = $userIdPh", 
                    array($mainDvId, $controlDvId, $sessionUserId)
                );
                
                if ($filter) {
                    foreach ($filter as $f => $filterId) {
                        
                        $dataInsert = array(
                            'ID'                => getUIDAdd($f),
                            'META_DATA_ID'      => $controlDvId,
                            'VALUE_ID'          => $filterId,
                            'MAIN_META_DATA_ID' => $mainDvId,
                            'CREATED_USER_ID'   => $sessionUserId,
                            'CREATED_DATE'      => Date::currentDate()
                        );
                        
                        $this->db->AutoExecute('META_DATA_VALUE_SUGGEST', $dataInsert);
                    }
                }
                
                $response = array('status' => 'success', 'message' => 'Success');
                
                $this->load->model('mdobject', 'middleware/models/');
                $dataGridOptionData = $this->model->getDVGridOptionsModel($mainDvId);
                
                $response['objectValueViewType'] = $dataGridOptionData['DETAULTVIEWER'];
                    
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid ids!');
        }
        
        return $response;
    }
    
    public function saveErdConfigModel() {
        
        $data = Input::post('data');
        parse_str($data, $dataArr);
        
        $erdId = $dataArr['erdId'];
        
        if (is_numeric($erdId)) {
            
            try {
                
                $connections = Input::post('connections');
                $columns = Input::post('columns');
                $tables = Input::post('tables');
                
                $param = array(
                    'id'        => $erdId, 
                    'graphJson' => Input::post('positions'), 
                    'dtls'      => $connections, 
                    'columns'   => $columns, 
                    'tables'    => $tables
                );
                
                $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'eisArcErdDV_Add_001', $param);
                
                if ($result['status'] == 'success') {
                    $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
                } else {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
                
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        return $response;
    }

}