<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mddatamodel Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	DataModel
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mddatamodel
 */

class Mddatamodel extends Controller {
    
    public static $gfServiceAddress = GF_SERVICE_ADDRESS;
    public static $createDataViewCommand = 'PL_MDVR_001';
    public static $updateDataViewCommand = 'PL_MDVR_002';
    public static $deleteDataViewCommand = 'PL_MDVR_005';
    public static $getDataViewCommand = 'PL_MDVIEW_004';
    public static $getRowDataViewCommand = 'PL_MDVR_004';
    public static $consolidateDataViewCommand = 'PL_MDVR_006';
    public static $ignorePermission = false;
    private static $mainViewPath = 'middleware/views/metadata/'; 
    
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function checkCriteriaProcess() {
        
        $mainMetaDataId = Input::post('mainMetaDataId');
        $batchNumber = Input::post('batchNumber');
        $selectedRows = Input::post('selectedrow');
        $params = Input::postCheck('params') ? Input::post('params') : '';
        
        $response = $this->model->checkCriteriaProcessModel($mainMetaDataId, $batchNumber, $selectedRows, $params);
        jsonResponse($response);
    }
    
    public function getConvertGridData() {
        
        $mainMetaDataId = Input::post('mainMetaDataId');
        $selectedrow = Input::post('selectedrow');
        $params = Input::postCheck('params') ? Input::post('params') : '';
        
        //$result = $this->model->getConvertGridDataModel($mainMetaDataId, $selectedrow, $params);
        echo json_encode(array('selectedRows' => $selectedrow)); exit();
    }
    
    public function checkCriteriaProcessByOneRow() {
        
        $mainMetaDataId = Input::post('mainMetaDataId');
        $processMetaDataId = Input::post('processMetaDataId');
        $selectedRow = Input::post('selectedRow');

        $response = $this->model->checkCriteriaProcessByOneRowModel($mainMetaDataId, $processMetaDataId, $selectedRow);
        jsonResponse($response);
    }
    
    public function joinType() {
        return array(
            array(
                'code' => 'INNER JOIN',
                'name' => 'INNER JOIN'
            ),
            array(
                'code' => 'LEFT JOIN',
                'name' => 'LEFT JOIN'
            ),
            array(
                'code' => 'RIGHT JOIN',
                'name' => 'RIGHT JOIN'
            ),
            array(
                'code' => 'LEFT OUTER JOIN',
                'name' => 'LEFT OUTER JOIN'
            ),
            array(
                'code' => 'RIGHT OUTER JOIN',
                'name' => 'RIGHT OUTER JOIN'
            ),
            array(
                'code' => 'FULL OUTER JOIN',
                'name' => 'FULL OUTER JOIN'
            )
        );
    }
    
    public function getIdCodeName($metaDataId, $metaValueId) {
        $this->load->model('mddatamodel', 'middleware/models/');
        return $this->model->getIdCodeNameModel($metaDataId, $metaValueId);
    }
    
    public function sendMailBySelectionRowsForm($dataViewId = null, $processMetaDataId = null, $selectedRows = array(), $emailTo = '', $return = false) {
        
        $this->load->model('mdwebservice', 'middleware/models/');
        
        if ($dataViewId && $processMetaDataId && $selectedRows) {
            
            $dataViewId        = $dataViewId;
            $processMetaDataId = $processMetaDataId;
            $selectedRows      = $selectedRows;
            
        } else {
            
            $dataViewId        = Input::post('dataViewId');
            $processMetaDataId = Input::post('processMetaDataId');
            $selectedRows      = Input::post('selectedRows');
        }
        
        $selectedRowsCount = is_countable($selectedRows) ? count($selectedRows) : 0;
        $fillData = array();
        
        if ($selectedRowsCount == 1) {
            
            $selectedRowData = $selectedRows[0];
            $fillData = $this->model->getRunDataProcessModel($dataViewId, $processMetaDataId, $selectedRowData);
            
        } elseif ($selectedRowsCount > 1) {
            
            $fillData = $this->model->getConsolidateDataProcessModel($dataViewId, $processMetaDataId, $selectedRows);
        }
        
        $this->view->emailTo        = '';
        $this->view->emailToControl = '';
        $this->view->emailSubject   = '';
        $this->view->emailBody      = '';
        
        $this->view->ignoreList        = Input::postCheck('ignoreList') ? 'true' : 'false';
        $this->view->ignoreFromOwnMail = Input::post('ignoreFromOwnMail');
        $this->view->ignoreCheckBox    = Input::post('ignoreCheckBox');
        $this->view->ignoreChooseFile  = Input::post('ignoreChooseFile');
        $this->view->isRowsAttachType  = Input::post('isRowsAttachType');
        $this->view->isSetFromCombo    = Input::post('isSetFromCombo');
        $this->view->drillDownField    = Input::post('drillDownField');
        $this->view->sendModeChecked   = Input::post('sendModeChecked');
        $this->view->emailHide         = Input::post('emailHide');
        $this->view->footerSumCount    = Input::post('footerSumCount');
        $this->view->refStructureId    = Input::post('ref_structure_id');
        $this->view->rtMetaDataId      = Input::numeric('rtMetaDataId');
        $this->view->fileAttachDrillField = Input::post('fileAttachDrillField');
        $isEcmContentAttach = Input::post('isEcmContentAttach');
        $isEcmContentAttach = ($isEcmContentAttach == '1' || $isEcmContentAttach == 'true') ? true : false;
        
        if ($emailTo) {
            $this->view->emailTo = $emailTo;
        } 
        
        if ($fillData) {
            
            if (isset($fillData['subjecttxt'])) {
                $this->view->emailSubject = $fillData['subjecttxt'];
            }
            
            if (isset($fillData['messagetxt'])) {
                $this->view->emailBody = $fillData['messagetxt'];
            }
            
            if (isset($fillData['maillist'])) {
                $this->view->emailTo = $fillData['maillist'];
            }
            
            if (isset($fillData['lookupmetadataid']) && $fillData['lookupmetadataid'] != '') {
                
                $control = array('GROUP_PARAM_CONFIG_TOTAL' => '0', 'GROUP_CONFIG_PARAM_PATH' => NULL, 'GROUP_CONFIG_LOOKUP_PATH' => NULL, 'GROUP_CONFIG_PARAM_PATH_GROUP' => NULL, 'GROUP_CONFIG_FIELD_PATH_GROUP' => NULL, 'GROUP_CONFIG_FIELD_PATH' => NULL, 'GROUP_CONFIG_GROUP_PATH' => NULL, 'IS_MULTI_ADD_ROW' => '0', 'IS_MULTI_ADD_ROW_KEY' => '0', 'META_DATA_CODE' => 'mailList', 'LOWER_PARAM_NAME' => 'maillist', 'META_DATA_NAME' => 'Илгээх', 'DESCRIPTION' => NULL, 'ATTRIBUTE_ID_COLUMN' => NULL, 'ATTRIBUTE_CODE_COLUMN' => NULL, 'ATTRIBUTE_NAME_COLUMN' => NULL, 'IS_SHOW' => '1', 'IS_REQUIRED' => '1', 'DEFAULT_VALUE' => NULL, 'RECORD_TYPE' => NULL, 'LOOKUP_META_DATA_ID' => $fillData['lookupmetadataid'], 'LOOKUP_TYPE' => 'combo', 'CHOOSE_TYPE' => 'multi', 'DISPLAY_FIELD' => 'username', 'VALUE_FIELD' => 'email', 'PARAM_REAL_PATH' => 'mailList', 'NODOT_PARAM_REAL_PATH' => 'mailList', 'META_TYPE_CODE' => 'string', 'TAB_NAME' => NULL, 'SIDEBAR_NAME' => NULL, 'FEATURE_NUM' => NULL, 'IS_SAVE' => NULL, 'FILE_EXTENSION' => NULL, 'PATTERN_TEXT' => NULL, 'PATTERN_NAME' => NULL, 'GLOBE_MESSAGE' => NULL, 'IS_MASK' => NULL, 'COLUMN_WIDTH' => NULL, 'COLUMN_AGGREGATE' => NULL, 'SEPARATOR_TYPE' => NULL, 'GROUP_LOOKUP_META_DATA_ID' => NULL, 'IS_BUTTON' => '1', 'COLUMN_COUNT' => NULL, 'MAX_VALUE' => NULL, 'MIN_VALUE' => NULL, 'IS_SHOW_ADD' => NULL, 'IS_SHOW_DELETE' => NULL, 'IS_SHOW_MULTIPLE' => NULL, 'LOOKUP_KEY_META_DATA_ID' => NULL, 'IS_REFRESH' => '0', 'FRACTION_RANGE' => NULL, 'GROUPING_NAME' => NULL, 'PARENT_ID' => null);
                $mailData = array();
                
                if ($this->view->emailTo) {
                    $mailData = array('maillist' => array_map('trim', explode(';', rtrim($this->view->emailTo, ';'))));
                }
                
                $this->view->emailToControl = Mdwebservice::renderParamControl($processMetaDataId, $control, 'emailTo', $control['META_DATA_CODE'], $mailData);
                
            } else {
                $this->view->emailToControl = Form::text(array('name' => 'emailTo', 'value' => $this->view->emailTo, 'id' => 'emailTo', 'class'=>'form-control form-control-sm', 'required'=>'required'));
            }
            
        } else {
            
            if (empty($this->view->emailTo) && isset($selectedRows[0]['email'])) {
                
                foreach ($selectedRows as $email) {
                    if (!empty($email['email'])) {
                        $this->view->emailTo .= $email['email'].';';
                    }
                }
                
                $this->view->emailTo = rtrim($this->view->emailTo, ';');
            }
            
            $this->view->emailToControl = Form::text(array('name' => 'emailTo', 'value' => $this->view->emailTo, 'id' => 'emailTo', 'class'=>'form-control form-control-sm', 'required'=>'required'));
            
            if (Input::isEmpty('emailTemplateCode') == false) {
                
                $this->load->model('mddatamodel', 'middleware/models/');
                
                $emailTplCode = Input::post('emailTemplateCode');
                $emlTempRow   = $this->model->getEmlTemplateByCodeModel($emailTplCode);

                if ($emlTempRow) {
                    
                    if (isset($emlTempRow[1])) {
                        $this->view->emailTplCombo = $emlTempRow;
                    } 
                    
                    $emlTempRow = $emlTempRow[0];
                    $emailTplCode = $emlTempRow['CODE'];
                    
                    $this->view->emailSubject = $emlTempRow['SUBJECT'];
                    $this->view->emailBody    = html_entity_decode($emlTempRow['MESSAGE']);
                    $this->view->emailBody    = str_replace('[URL]', URL, $this->view->emailBody);                                     
                    $this->view->emailTplCode = $emailTplCode;
                }
            }
            
            if (Input::isEmpty('ccEmail') == false) {
                
                $ccPath = strtolower(Input::post('ccEmail'));
                
                if (isset($selectedRows[0][$ccPath])) {
                    
                    $this->view->emailCc = '';
                    $emailCcAlready = array();
                    
                    foreach ($selectedRows as $ccField) {
                        
                        if (!empty($ccField[$ccPath]) && !isset($emailCcAlready[$ccField[$ccPath]])) {
                            
                            $this->view->emailCc .= $ccField[$ccPath].';';
                            $emailCcAlready[$ccField[$ccPath]] = 1;
                        }
                    }

                    $this->view->emailCc = rtrim($this->view->emailCc, ';');
                } else {
                    $this->view->emailCc = Input::post('ccEmail');
                }
            }
            
            if (Input::isEmpty('groupEmail') == false) {
                $this->view->groupEmail = Input::post('groupEmail');
            }
            
            if ($this->view->emailSubject == '') {
                $this->view->emailSubject = Input::post('emailSubject');
            }
        }
        
        $this->view->selectedRows = $selectedRows;
        
        if (isset($selectedRows[0]) && $selectedRowsCount == 1) {

            $firstRow = $selectedRows[0];

            foreach ($firstRow as $rowKey => $rowVal) {
                if (!is_array($rowVal)) {
                    $this->view->emailBody    = str_ireplace('['.$rowKey.']', $rowVal, $this->view->emailBody);
                    $this->view->emailSubject = str_ireplace('['.$rowKey.']', $rowVal, $this->view->emailSubject);
                }
            }
            
        } elseif (isset($selectedRows[0]) && $selectedRowsCount > 1) {
            
            loadPhpQuery();
            $bodyHtml = phpQuery::newDocumentHTML($this->view->emailBody);
            $bodytrHtml = str_replace(array('%5B', '%5D'), array('[', ']'), $bodyHtml['tbody']->html());
            $bodyHtml['tbody']->empty();

            $appendTr = '';
            
            foreach ($selectedRows as $rowKey => $rowVal) {
                $bodytrHtmlReplaced = $bodytrHtml;
                foreach ($rowVal as $rwKey => $rwVal) {
                    if (!is_array($rwVal)) {            
                        $bodytrHtmlReplaced = str_ireplace('['.$rwKey.']', $rwVal, $bodytrHtmlReplaced);
                    }
                }
                $appendTr .= $bodytrHtmlReplaced;
            }

            $bodyHtml['tbody']->append($appendTr);
            $this->view->emailBody = $bodyHtml->html();
        }
        
        if (!$this->view->sendModeChecked && $this->view->ignoreCheckBox != '1') {
            $this->view->sendModeChecked = 'ccgroupemail';
        }
        
        if ($this->view->isSetFromCombo == 'true') {
            
            $this->load->model('mddatamodel', 'middleware/models/');
            
            $this->view->setFromEmails = $this->model->getSetFromEmailsModel();
            
            if (!$this->view->setFromEmails) {
                $this->view->isSetFromCombo = 'false';
            }
        }
        
        if ($this->view->rtMetaDataId) {
            
            $reportTemplateHtml = (new Mdtemplate())->getTemplateByArguments($this->view->rtMetaDataId, 'selfDvId', $selectedRows);
            
            if ($reportTemplateHtml) {
                if (strpos($this->view->emailBody, '[reportTemplateHtml]') !== false) {
                    $this->view->emailBody = str_replace('[reportTemplateHtml]', $reportTemplateHtml, $this->view->emailBody);
                } else {
                    $this->view->emailBody .= $reportTemplateHtml;
                }
            }
        }
        
        if ($isEcmContentAttach) {
            
            $recordIds = array();
            foreach ($selectedRows as $selectedRow) {
                if (isset($selectedRow['id']) && $selectedRow['id']) {
                    $recordIds[] = $selectedRow['id'];
                }
            }
            
            if ($recordIds) {
                $this->load->model('mdpreview', 'middleware/models/');
                $this->view->ecmContentAttachs = $this->model->getContentByRecordIdsModel(implode(',', $recordIds));
            }
        }
        
        $response = array(
            'html' => $this->view->renderPrint('dataview/form/sendmailSelectionRows', self::$mainViewPath),
            'title' => $this->lang->line('sendmail'),  
            'selectedRows' => $selectedRows,
            'dataViewId' => $dataViewId,
            'processMetaDataId' => $processMetaDataId, 
            'send_btn' => $this->lang->line('send_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        
        if ($return) {
            return $response;
        } else {
            jsonResponse($response);
        }
    }
        
    public function sendMailBySelectionRowsMVForm($dataViewId = null, $processMetaDataId = null, $selectedRows = array(), $emailTo = '', $return = false) {                        
        
        if ($dataViewId && $processMetaDataId && $selectedRows) {
            
            $dataViewId        = $dataViewId;
            $processMetaDataId = $processMetaDataId;
            $selectedRows      = $selectedRows;
            
        } else {
            
            $dataViewId        = Input::post('dataViewId');
            $processMetaDataId = Input::post('processMetaDataId');
            $selectedRows      = Input::post('selectedRows');
        }
        
        $this->load->model('mdform', 'middleware/models/');
        $getMapConfig = $this->model->getKpiIndicatorIndicatorMapByJsonConfig($dataViewId, $processMetaDataId);      
        $getMapConfig = json_decode($getMapConfig['JSON_CONFIG'], true);
        
        $this->load->model('mdwebservice', 'middleware/models/');
        
        $selectedRowsCount = is_countable($selectedRows) ? count($selectedRows) : 0;
        $fillData = array();
        
//        if ($selectedRowsCount == 1) {
//            
//            $selectedRowData = $selectedRows[0];
//            $fillData = $this->model->getRunDataProcessModel($dataViewId, $processMetaDataId, $selectedRowData);
//            
//        } elseif ($selectedRowsCount > 1) {
//            
//            $fillData = $this->model->getConsolidateDataProcessModel($dataViewId, $processMetaDataId, $selectedRows);
//        }
        
        $this->view->emailTo        = '';
        $this->view->emailToControl = '';
        $this->view->emailSubject   = '';
        $this->view->emailBody      = '';
        
        $this->view->ignoreList        = Input::postCheck('ignoreList') ? 'true' : 'false';
        $this->view->ignoreFromOwnMail = Input::post('ignoreFromOwnMail');
        $this->view->ignoreCheckBox    = Input::post('ignoreCheckBox');
        $this->view->ignoreChooseFile  = Input::post('ignoreChooseFile');
        $this->view->isRowsAttachType  = Input::post('isRowsAttachType');
        $this->view->isSetFromCombo    = Input::post('isSetFromCombo');
        $this->view->drillDownField    = Input::post('drillDownField');
        $this->view->sendModeChecked   = Input::post('sendModeChecked');
        $this->view->emailHide         = Input::post('emailHide');
        $this->view->footerSumCount    = Input::post('footerSumCount');
        $this->view->refStructureId    = Input::post('ref_structure_id');
        $this->view->rtMetaDataId      = Input::numeric('rtMetaDataId');
        $this->view->fileAttachDrillField = Input::post('fileAttachDrillField');
        $isEcmContentAttach = Input::post('isEcmContentAttach');
        $isEcmContentAttach = ($isEcmContentAttach == '1' || $isEcmContentAttach == 'true') ? true : false;
        
        if ($emailTo) {
            $this->view->emailTo = $emailTo;
        } 
        
        if ($fillData) {
            
            if (isset($fillData['subjecttxt'])) {
                $this->view->emailSubject = $fillData['subjecttxt'];
            }
            
            if (isset($fillData['messagetxt'])) {
                $this->view->emailBody = $fillData['messagetxt'];
            }
            
            if (isset($fillData['maillist'])) {
                $this->view->emailTo = $fillData['maillist'];
            }
            
            if (isset($fillData['lookupmetadataid']) && $fillData['lookupmetadataid'] != '') {
                
                $control = array('GROUP_PARAM_CONFIG_TOTAL' => '0', 'GROUP_CONFIG_PARAM_PATH' => NULL, 'GROUP_CONFIG_LOOKUP_PATH' => NULL, 'GROUP_CONFIG_PARAM_PATH_GROUP' => NULL, 'GROUP_CONFIG_FIELD_PATH_GROUP' => NULL, 'GROUP_CONFIG_FIELD_PATH' => NULL, 'GROUP_CONFIG_GROUP_PATH' => NULL, 'IS_MULTI_ADD_ROW' => '0', 'IS_MULTI_ADD_ROW_KEY' => '0', 'META_DATA_CODE' => 'mailList', 'LOWER_PARAM_NAME' => 'maillist', 'META_DATA_NAME' => 'Илгээх', 'DESCRIPTION' => NULL, 'ATTRIBUTE_ID_COLUMN' => NULL, 'ATTRIBUTE_CODE_COLUMN' => NULL, 'ATTRIBUTE_NAME_COLUMN' => NULL, 'IS_SHOW' => '1', 'IS_REQUIRED' => '1', 'DEFAULT_VALUE' => NULL, 'RECORD_TYPE' => NULL, 'LOOKUP_META_DATA_ID' => $fillData['lookupmetadataid'], 'LOOKUP_TYPE' => 'combo', 'CHOOSE_TYPE' => 'multi', 'DISPLAY_FIELD' => 'username', 'VALUE_FIELD' => 'email', 'PARAM_REAL_PATH' => 'mailList', 'NODOT_PARAM_REAL_PATH' => 'mailList', 'META_TYPE_CODE' => 'string', 'TAB_NAME' => NULL, 'SIDEBAR_NAME' => NULL, 'FEATURE_NUM' => NULL, 'IS_SAVE' => NULL, 'FILE_EXTENSION' => NULL, 'PATTERN_TEXT' => NULL, 'PATTERN_NAME' => NULL, 'GLOBE_MESSAGE' => NULL, 'IS_MASK' => NULL, 'COLUMN_WIDTH' => NULL, 'COLUMN_AGGREGATE' => NULL, 'SEPARATOR_TYPE' => NULL, 'GROUP_LOOKUP_META_DATA_ID' => NULL, 'IS_BUTTON' => '1', 'COLUMN_COUNT' => NULL, 'MAX_VALUE' => NULL, 'MIN_VALUE' => NULL, 'IS_SHOW_ADD' => NULL, 'IS_SHOW_DELETE' => NULL, 'IS_SHOW_MULTIPLE' => NULL, 'LOOKUP_KEY_META_DATA_ID' => NULL, 'IS_REFRESH' => '0', 'FRACTION_RANGE' => NULL, 'GROUPING_NAME' => NULL, 'PARENT_ID' => null);
                $mailData = array();
                
                if ($this->view->emailTo) {
                    $mailData = array('maillist' => array_map('trim', explode(';', rtrim($this->view->emailTo, ';'))));
                }
                
                $this->view->emailToControl = Mdwebservice::renderParamControl($processMetaDataId, $control, 'emailTo', $control['META_DATA_CODE'], $mailData);
                
            } else {
                $this->view->emailToControl = Form::text(array('name' => 'emailTo', 'value' => $this->view->emailTo, 'id' => 'emailTo', 'class'=>'form-control form-control-sm', 'required'=>'required'));
            }
            
        } else {
            
            if (empty($this->view->emailTo) && isset($selectedRows[0]['EMAIL'])) {
                
                foreach ($selectedRows as $email) {
                    if (!empty($email['EMAIL'])) {
                        $this->view->emailTo .= $email['EMAIL'].';';
                    }
                }
                
                $this->view->emailTo = rtrim($this->view->emailTo, ';');
            }
            
            $this->view->emailToControl = Form::text(array('name' => 'emailTo', 'value' => $this->view->emailTo, 'id' => 'emailTo', 'class'=>'form-control form-control-sm', 'required'=>'required'));
            
            if (Input::isEmpty('emailTemplateCode') == false) {
                
                $this->load->model('mddatamodel', 'middleware/models/');
                
                $emailTplCode = Input::post('emailTemplateCode');
                $emlTempRow   = $this->model->getEmlTemplateByCodeModel($emailTplCode);

                if ($emlTempRow) {
                    
                    if (isset($emlTempRow[1])) {
                        $this->view->emailTplCombo = $emlTempRow;
                    } 
                    
                    $emlTempRow = $emlTempRow[0];
                    $emailTplCode = $emlTempRow['CODE'];
                    
                    $this->view->emailSubject = $emlTempRow['SUBJECT'];
                    $this->view->emailBody    = html_entity_decode($emlTempRow['MESSAGE']);
                    $this->view->emailBody    = str_replace('[URL]', URL, $this->view->emailBody);                                     
                    $this->view->emailTplCode = $emailTplCode;
                }
            }
            
            if (Input::isEmpty('ccEmail') == false) {
                
                $ccPath = strtolower(Input::post('ccEmail'));
                
                if (isset($selectedRows[0][$ccPath])) {
                    
                    $this->view->emailCc = '';
                    $emailCcAlready = array();
                    
                    foreach ($selectedRows as $ccField) {
                        
                        if (!empty($ccField[$ccPath]) && !isset($emailCcAlready[$ccField[$ccPath]])) {
                            
                            $this->view->emailCc .= $ccField[$ccPath].';';
                            $emailCcAlready[$ccField[$ccPath]] = 1;
                        }
                    }

                    $this->view->emailCc = rtrim($this->view->emailCc, ';');
                } else {
                    $this->view->emailCc = Input::post('ccEmail');
                }
            }
            
            if (Input::isEmpty('groupEmail') == false) {
                $this->view->groupEmail = Input::post('groupEmail');
            }
            
        }
        
        $this->view->selectedRows = $selectedRows;
        
        if (isset($selectedRows[0])) {

            $firstRow = $selectedRows[0];

            foreach ($firstRow as $rowKey => $rowVal) {
                if (!is_array($rowVal)) {
                    if ($this->view->emailSubject == '' && $getMapConfig['emailSubject'] == $rowKey) {
                        $this->view->emailSubject = $rowVal;
                    }                    
//                    $this->view->emailBody    = str_ireplace('['.$rowKey.']', $rowVal, $this->view->emailBody);
//                    $this->view->emailSubject = str_ireplace('['.$rowKey.']', $rowVal, $this->view->emailSubject);
                }
            }
            
        } elseif (isset($selectedRows[0]) && $selectedRowsCount > 1) {
            
            loadPhpQuery();
            $bodyHtml = phpQuery::newDocumentHTML($this->view->emailBody);
            $bodytrHtml = str_replace(array('%5B', '%5D'), array('[', ']'), $bodyHtml['tbody']->html());
            $bodyHtml['tbody']->empty();

            $appendTr = '';
            
            foreach ($selectedRows as $rowKey => $rowVal) {
                $bodytrHtmlReplaced = $bodytrHtml;
                foreach ($rowVal as $rwKey => $rwVal) {
                    if (!is_array($rwVal)) {            
                        $bodytrHtmlReplaced = str_ireplace('['.$rwKey.']', $rwVal, $bodytrHtmlReplaced);
                    }
                }
                $appendTr .= $bodytrHtmlReplaced;
            }

            $bodyHtml['tbody']->append($appendTr);
            $this->view->emailBody = $bodyHtml->html();
        }
        
        if (!$this->view->sendModeChecked && $this->view->ignoreCheckBox != '1') {
            $this->view->sendModeChecked = 'ccgroupemail';
        }
        
        if ($this->view->isSetFromCombo == 'true') {
            
            $this->load->model('mddatamodel', 'middleware/models/');
            
            $this->view->setFromEmails = $this->model->getSetFromEmailsModel();
            
            if (!$this->view->setFromEmails) {
                $this->view->isSetFromCombo = 'false';
            }
        }
        
        if ($this->view->rtMetaDataId) {
            
            $reportTemplateHtml = (new Mdtemplate())->getTemplateByArguments($this->view->rtMetaDataId, 'selfDvId', $selectedRows);
            
            if ($reportTemplateHtml) {
                if (strpos($this->view->emailBody, '[reportTemplateHtml]') !== false) {
                    $this->view->emailBody = str_replace('[reportTemplateHtml]', $reportTemplateHtml, $this->view->emailBody);
                } else {
                    $this->view->emailBody .= $reportTemplateHtml;
                }
            }
        }
        
        if ($isEcmContentAttach) {
            
            $recordIds = array();
            foreach ($selectedRows as $selectedRow) {
                if (isset($selectedRow['id']) && $selectedRow['id']) {
                    $recordIds[] = $selectedRow['id'];
                }
            }
            
            if ($recordIds) {
                $this->load->model('mdpreview', 'middleware/models/');
                $this->view->ecmContentAttachs = $this->model->getContentByRecordIdsModel(implode(',', $recordIds));
            }
        }
        
        $response = array(
            'html' => $this->view->renderPrint('dataview/form/sendmailSelectionRows', self::$mainViewPath),
            'title' => $this->lang->line('sendmail'),  
            'selectedRows' => $selectedRows,
            'dataViewId' => $dataViewId,
            'processMetaDataId' => $processMetaDataId, 
            'send_btn' => $this->lang->line('send_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        
        if ($return) {
            return $response;
        } else {
            jsonResponse($response);
        }
    }
    
    public function sendMailBySelectionRows() {
        $response = $this->model->sendMailBySelectionRowsModel();
        jsonResponse($response);
    }
    
    public function sendMailBySelectionUser() {
        $response = $this->model->sendMailBySelectionUserModel();
        jsonResponse($response);
    }
    
    public function getTreeListFiscalPeriod() {
        $response = $this->model->getTreeListFiscalPeriodModel();
        jsonResponse($response);
    }  
    
    public function importExcelTemplate() {
        
        $this->view->templateId = $this->view->additionalParametersProcessId = $showTemplateIds = null;
        $this->view->isReturnSuccessRows = Input::post('isReturnSuccessRows');
        $this->view->isSaveWhenAllRowSuccessful = Input::post('isSaveWhenAllRowSuccessful');
        
        if (Input::postCheck('onceTemplateId')) { 
            $this->view->templateId = Input::post('onceTemplateId');
        }
        
        if (Input::postCheck('showTemplateIds')) {
            $showTemplateIds = Input::post('showTemplateIds');
        }
        
        if (Input::postCheck('additionalParametersProcessId')) {
            $this->view->additionalParametersProcessId = Input::post('additionalParametersProcessId');
        }
        
        if (Input::postCheck('getParams')) {
            parse_str(Input::post('getParams'), $getParams);
            
            if (!$this->view->templateId && isset($getParams['onceTemplateId'])) {
                $this->view->templateId = $getParams['onceTemplateId'];
            }
            
            if (!$showTemplateIds && isset($getParams['showTemplateIds'])) {
                $showTemplateIds = $getParams['showTemplateIds'];
            }
            
            if (!$this->view->additionalParametersProcessId && isset($getParams['additionalParametersProcessId'])) {
                $this->view->additionalParametersProcessId = $getParams['additionalParametersProcessId'];
            }
        }
        
        $this->view->templateList = $this->model->getImportExcelTemplateModel($showTemplateIds);
        
        if (isset($this->view->additionalParametersProcessId) && $this->view->additionalParametersProcessId) {
            $this->load->model('mdwebservice', 'middleware/models/');
            $this->view->paramList = $this->model->groupParamsDataModel($this->view->additionalParametersProcessId, null, ' AND PAL.PARENT_ID IS NULL');
        }
        
        $response = array(
            'html' => $this->view->renderPrint('common/import/v2/importExcelTemplate', self::$mainViewPath),
            'title' => $this->lang->line('META_00087'),
            'import_btn' => 'Импорт хийх',
            'close_btn' => $this->lang->line('close_btn')
        );

        jsonResponse($response);
    }
    
    public function importingExcelTemplate() {
        $response = $this->model->importingExcelTemplateModel();
        jsonResponse($response);
    }
    
    public function importExcelTemplateAdd() {
        
        $this->view->row = $this->view->params = array();
        $this->view->isEdit = false;
        
        $postData = Input::postData();
        $paramData = issetParamArray($postData['paramData']);

        if (issetParam($paramData['excelTemplateProcessId'])) {
            $meta = &getInstance();
            $meta->load->model('mdmetadata', 'middleware/models/');                    

            $metaData = $meta->model->getMetaDataModel($paramData['excelTemplateProcessId']);
            $this->view->row['PROCESS_META_DATA_ID'] = issetParam($metaData['META_DATA_ID']);
            $this->view->row['PROCESS_META_DATA_NAME'] = issetParam($metaData['META_DATA_NAME']);
            $this->view->row['PROCESS_META_DATA_CODE'] = issetParam($metaData['META_DATA_CODE']);
        }

        $response = array(
            'html' => $this->view->renderPrint('common/import/v2/importExcelTemplateAdd', self::$mainViewPath),
            'title' => 'Загвар үүсгэх',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        jsonResponse($response);
    }
    
    public function importExcelTemplateAddSave() {
        $response = $this->model->importExcelTemplateAddSaveModel(); 
        jsonResponse($response);
    }
    
    public function importExcelTemplateEdit() {
        
        $templateId = Input::post('id');
        
        $this->view->isEdit = true;
        
        $this->view->row = $this->model->getExcelTemplateByIdModel($templateId); 
        $this->view->params = $this->model->getExcelTemplateParamsByIdModel($templateId, $this->view->row['PROCESS_META_DATA_ID']); 
        
        $response = array(
            'html' => $this->view->renderPrint('common/import/v2/importExcelTemplateAdd', self::$mainViewPath),
            'title' => 'Загвар засах',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        jsonResponse($response);
    }
    
    public function importExcelTemplateEditSave() {
        $response = $this->model->importExcelTemplateEditSaveModel(); 
        jsonResponse($response);
    }
    
    public function base64Download() {
        
        $vId = Input::post('vId');
        $vTable = Input::post('vTable');
        
        if ($vTable == 'imp_excel_template') {
            
            $row = $this->model->getExcelTemplateFileDataById($vId);
            
            $fileName = $row['NAME'];
            $fileExtension = $row['FILE_EXTENSION'] ? $row['FILE_EXTENSION'] : 'xlsx';
            $base64Str = $row['FILE_DATA'];
            
        } elseif ($vTable == 'imp_excel_log') {
            
            $row = $this->model->getExcelLogFileDataById($vId);
            
            $fileName = $row['FILE_NAME'] ? $row['FILE_NAME'] : $row['TEMPLATE_NAME'];
            $fileName = str_replace(array('.xlsx', '.xls', '.csv'), '', $fileName);
            $fileExtension = $row['FILE_EXTENSION'] ? $row['FILE_EXTENSION'] : 'xlsx';
            
            /*if ($row['STATUS'] == 'error') {
                
                $base64Str = $row['RESPONSE_DATA'];
                
                if (!$base64Str) {
                    $base64Str = $row['REQUEST_DATA'];
                }
                
            } else {
                $firstString = substr($row['RESPONSE_DATA'], 0, 20);
                
                if ($firstString == 'UEsDBBQACAgIAACUflEA' || $firstString == 'QklMTElOR19QRVJJT0QJ') {
                    $base64Str = $row['RESPONSE_DATA'];
                } else {
                    $base64Str = $row['REQUEST_DATA'];
                }
            }*/
            
            $base64Str = $row['RESPONSE_DATA'];
                
            if (!$base64Str) {
                $base64Str = $row['REQUEST_DATA'];
            }
            
            if (strpos($base64Str, Mdcommon::$separator) !== false) {
                $base64StrArr = explode(Mdcommon::$separator, $base64Str);
                $base64Str = $base64StrArr[1];
            }
        }
        
        if (isset($fileName)) {
            base64ToDownloadFile($fileName, $fileExtension, $base64Str);
        }
    }
    
    public function errorExcelFileDownload() {
        
        $uniqId = Input::post('uniqId');
        $fileExtension = Input::post('fileExtension');

        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $file_path = $cacheTmpDir.'/excelimport/'.$uniqId.'.txt'; 
        $fileName = 'errorFile'.$uniqId.'.'.$fileExtension;

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || !defined('CACHE_PATH')) {

            $fileData = file_get_contents($file_path);

            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo base64_decode($fileData); exit;

        } else {
            
            shell_exec('base64 --decode '.$file_path.' > '.$cacheTmpDir.'/excelimport/'.$fileName);
            fileDownload($fileName, 'cache/excelimport/'.$fileName);					
        }
    }
    
    public function dvSqlDecrypt() {
        $result = $this->model->sqlDecryptModel();
        echo $result; 
    }
    
    public function dataViewRowReload() {
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $dataModelId = Input::numeric('metaDataId');
        
        $attributes = $this->model->getDataViewMetaValueAttributes(null, null, $dataModelId);
        
        $idField = isset($attributes['id']) ? strtolower($attributes['id']) : 'id';
        $row = $_POST['prevRow'];
        
        unset($_POST['prevRow']);
        
        $_POST['page'] = 1;
        $_POST['rows'] = 1;
        $_POST['isIgnoreParentIsNull'] = 1;
        $_POST['dvDefaultCriteria'] = '{"filterRecordId": "'.$row[$idField].'", "'.$idField.'": "'.$row[$idField].'"}';
        
        $result = $this->model->dataViewDataGridModel();
        
        if (isset($result['rows'][0])) {
            $response = array('status' => 'success', 'row' => $result['rows'][0]);
        } else {
            $response = array('status' => 'error');
        }

        jsonResponse($response);
    }
    
    public function saveStarRating() {
        $response = $this->model->saveStarRatingModel();
        jsonResponse($response);
    }
    
    public function renderGmapInfoWindowByDv() {
        
        $result = $this->model->gmapInfoWindowByDvModel();
        
        if ($result['status'] == 'success') {
            
            $this->view->rows = $result['data'];
            $this->view->clickFunction = $result['clickFunction'];
            
            $response = array('status' => 'success', 'html' => $this->view->renderPrint('gmap/infoWindow', 'middleware/views/common/'));
        } else {
            $response = array('status' => 'info', 'html' => 'Msg');
        }
        
        jsonResponse($response);
    }
    
    public function getEmailAutoComplete() {
        $response = $this->model->getEmailAutoCompleteModel();
        jsonResponse($response);
    }   
    
    public function saveRemovedLookupItem() {
        $response = $this->model->saveRemovedLookupItemModel();
        jsonResponse($response);
    }
    
    public function dataMartRelationConfig() {
        
        $this->view->serviceId = Input::numeric('id');
        
        if ($this->view->serviceId) {
            
            $this->view->isDialog = !Input::postCheck('selectedRow');
            
            $objects = $this->model->getDataMartGetDataModel('eaServiceGet_004', array('id' => $this->view->serviceId));
            $fields = $this->model->getDataMartGetDataModel('eaServiceGet2_004', array('id' => $this->view->serviceId));
            
            if (!isset($fields['easervicedtlgetlist']) || (isset($fields['easervicedtlgetlist']) && !is_array($fields['easervicedtlgetlist']))) {
                $fields = array();
            } else {
                $fields = $fields['easervicedtlgetlist'];
            }
            
            $fieldExpCombo = $this->model->getDataMartDvRowsModel('1578394306291752');
            $expressionCombo = $this->model->getDataMartDvRowsModel('1570435323600');
            $indicatorCombo = $this->model->getDataMartDvRowsModel('1580874528037260');
            $showTypeCombo = $this->model->getDataMartDvRowsModel('1479890683890712');
            $operatorCombo = $this->model->getDataMartDvRowsModel('1582863108412835');
            $colorCombo = $this->model->getDataMartDvRowsModel('1582821376254');
            
            $graphJson = $this->model->getDataMartGraphJsonModel($this->view->serviceId);
            
            $response = array(
                'html'      => $this->view->renderPrint('config/datamart/dataMartRelationConfig', 'middleware/views/'), 
                'status'    => 'success', 
                'objects'   => $objects, 
                'fields'    => $fields, 
                'graphJson' => $graphJson, 
                'fieldExpCombo' => $fieldExpCombo, 
                'expressionCombo' => $expressionCombo, 
                'comboData' => array(
                    'fieldExpCombo'   => $fieldExpCombo, 
                    'expressionCombo' => $expressionCombo, 
                    'indicatorCombo'  => $indicatorCombo, 
                    'showTypeCombo'   => $showTypeCombo, 
                    'operatorCombo'   => $operatorCombo, 
                    'colorCombo'      => $colorCombo
                )
            );
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        jsonResponse($response);
    }
    
    public function dataMartRelationConfigView() {
        
        $this->view->serviceId = Input::numeric('id');
        
        if ($this->view->serviceId) {
            
            $this->view->isDialog = !Input::postCheck('selectedRow');
            
            $objects = $this->model->getDataMartGetDataModel('eaServiceGet_004', array('id' => $this->view->serviceId));
            $fields = $this->model->getDataMartGetDataModel('eaServiceGet2_004', array('id' => $this->view->serviceId));
            
            if (!isset($fields['easervicedtlgetlist']) || (isset($fields['easervicedtlgetlist']) && !is_array($fields['easervicedtlgetlist']))) {
                $fields = array();
            } else {
                $fields = $fields['easervicedtlgetlist'];
            }
            
            $graphJson = $this->model->getDataMartGraphJsonModel($this->view->serviceId);
            
            $response = array(
                'html'      => $this->view->renderPrint('config/datamart/dataMartRelationConfigView', 'middleware/views/'), 
                'status'    => 'success', 
                'objects'   => $objects, 
                'fields'    => $fields, 
                'graphJson' => $graphJson
            );
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        jsonResponse($response);
    }
    
    public function getDataMartObjectAttributes() {
        $response = $this->model->getDataMartObjectAttributesModel();
        jsonResponse($response);
    }
    
    public function getDataMartObjectRelation() {
        
        $sourceId = Input::numeric('sourceId');
        $targetId = Input::numeric('targetId');
        
        $result = $this->model->getDataMartDvRowsModel('1577155132007', array(
            'srcId' => array(
                array(
                    'operator' => '=',
                    'operand' => $sourceId
                )
            ), 
            'trgId' => array(
                array(
                    'operator' => '=',
                    'operand' => $targetId
                )
            ))
        );
        
        if ($result) {
            
            $response['status'] = 'already';
            $response['list'] = $result;
            
        } else {
            
            $_POST['templateId'] = $sourceId;
            $sourceAttrs = $this->model->getDataMartObjectAttributesModel();
            
            $_POST['templateId'] = $targetId;
            $targetAttrs = $this->model->getDataMartObjectAttributesModel();
            
            $response['status'] = 'new';
            $response['sourceAttrs'] = $sourceAttrs;
            $response['targetAttrs'] = $targetAttrs;
        }
        
        jsonResponse($response);
    }
    
    public function newDataMartObjectRelation() {
        $response = $this->model->newDataMartObjectRelationModel();
        jsonResponse($response);
    }
    
    public function saveDataMartRelationConfig() {
        $response = $this->model->saveDataMartRelationConfigModel();
        jsonResponse($response);
    }
    
    public function getDataViewByEaServiceId() {
        
        $id = Input::numeric('id');
        
        if ($id) {
            
            $dvId = $this->model->getDVIdByServiceIdModel($id);
            
            if ($dvId) {
                
                $_POST['callerType'] = 'package';
                
                (new Mdobject)->dataview($dvId, 0, 'json'); 
                exit;
                
            } else {
                $response = array('status' => 'error', 'message' => 'Жагсаалт үүсээгүй байна!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        jsonResponse($response);
    }
    
    public function getPivotByEaServiceId() {
        
        $id = Input::numeric('id');
        
        if ($id) {
            
            $row = $this->model->getTempIdByServiceIdModel($id);
            
            if ($row) {
                
                $_POST['metadataid'] = $row['LIST_META_DATA_ID'];
                $_POST['templateid'] = $row['TEMPLATE_ID'];
                $_POST['isignoretemplatelist'] = 1;
                $_POST['readonly'] = 1;
                $_POST['hiderowtotal'] = $row['HIDE_ROW_TOTAL'];
                $_POST['hidecolumntotal'] = $row['HIDE_COLUMN_TOTAL'];
                $_POST['collapse'] = $row['IS_COLLAPSED'];
                
                (new Mdpivot)->dataViewPivotView(); 
                exit;
                
            } else {
                $response = array('status' => 'error', 'message' => 'Тохиргоо үүсээгүй байна!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        jsonResponse($response);
    }
    
    public function getDvFirstRow($lookupMetaDataId, $lookupType, $valueField) {
        
        $dvInput = array(
            'systemMetaGroupId' => $lookupMetaDataId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'paging' => array('offset' => 1, 'pageSize' => 1)
        );
        $firstRowValue = null;
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $dvInput);

        if (isset($data['result'][0])) {
            
            if ($lookupType == 'combo') {
                
                $valueField = strtolower($valueField);
                
            } else {
                
                $field = $this->db->GetOne("
                    SELECT 
                        FIELD_PATH 
                    FROM META_GROUP_CONFIG 
                    WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                        AND IS_SELECT = 1 
                        AND INPUT_NAME = ".$this->db->Param(1)." 
                        AND COLUMN_NAME IS NOT NULL", 
                    array($lookupMetaDataId, 'META_VALUE_ID')); 
                
                $valueField = strtolower($field);
            }
            
            if ($valueField && isset($data['result'][0][$valueField])) {
                $firstRowValue = $data['result'][0][$valueField];
            }
        }
        
        return $firstRowValue;
    }
    
    public function setFormDvDmRecordMap() {
        
        $row = Input::post('row');
        
        $this->view->dvId = Input::numeric('dvId');
        $this->view->uniqId = getUID();
        $this->view->refStrId = Input::numeric('refStrId');
        $this->view->wfmStatusId = $row['wfmstatusid'];
        $this->view->refStructureDvId = '1584499381976247';
        $this->view->workFlowDvId = '1584498400843';
        $this->view->recordDvId = '1584498403837';
        
        $this->view->refStructureList = $this->model->getDataMartDvRowsModel($this->view->refStructureDvId, array(
            'srcRefStructureId' => array(
                array(
                    'operator' => '=',
                    'operand' => $this->view->refStrId
                )
            ), 
            'srcWfmStatusId' => array(
                array(
                    'operator' => '=',
                    'operand' => $this->view->wfmStatusId
                )
            ))
        );
        
        $this->view->workFlowList = array();
        $this->view->recordList = array();
        
        $this->view->render('dataview/form/setFormDvDmRecordMap', self::$mainViewPath);
    }
    
    public function getDvRowsByCriteria() {
        $response = $this->model->getDvRowsByCriteriaModel();
        jsonResponse($response);
    }
    
    public function saveDvDmRecordMap() {
        $response = $this->model->saveDvDmRecordMapModel();
        jsonResponse($response);
    }
    
    public function historyDvDmRecordMap() {
        $this->view->historyList = $this->model->historyDvDmRecordMapModel();
        $this->view->render('dataview/form/historyDvDmRecordMap', self::$mainViewPath);
    }
    
    public function getEmailTplDataByCode() {
        $emailTplCode = Input::post('code');
        $data = $this->model->getEmlTemplateByCodeModel($emailTplCode);
        $data = $data[0];
        $selectedRows = Input::post('selectedRows');
        $selectedRowsCount = count($selectedRows);
        
        if (isset($selectedRows[0]) && $selectedRowsCount == 1) {

            $firstRow = $selectedRows[0];

            foreach ($firstRow as $rowKey => $rowVal) {
                if (!is_array($rowVal)) {
                    $data['MESSAGE']    = str_ireplace('['.$rowKey.']', $rowVal, $data['MESSAGE']);
                    $data['SUBJECT'] = str_ireplace('['.$rowKey.']', $rowVal, $data['SUBJECT']);
                }
            }
            
        } elseif (isset($selectedRows[0]) && $selectedRowsCount > 1) {
            
            loadPhpQuery();
            $bodyHtml = phpQuery::newDocumentHTML($data['MESSAGE']);
            $bodytrHtml = str_replace(array('%5B', '%5D'), array('[', ']'), $bodyHtml['tbody']->html());
            $bodyHtml['tbody']->empty();

            $appendTr = '';
            
            foreach ($selectedRows as $rowKey => $rowVal) {
                $bodytrHtmlReplaced = $bodytrHtml;
                foreach ($rowVal as $rwKey => $rwVal) {
                    if (!is_array($rwVal)) {            
                        $bodytrHtmlReplaced = str_ireplace('['.$rwKey.']', $rwVal, $bodytrHtmlReplaced);
                    }
                }
                $appendTr .= $bodytrHtmlReplaced;
            }

            $bodyHtml['tbody']->append($appendTr);
            $data['MESSAGE'] = $bodyHtml->html();
        }                
        
        jsonResponse($data);
    }
    
    public function selectedRowsToPdfZip() {
        
        $response = $this->model->selectedRowsToPdfZipModel();
        
        if ($response['status'] == 'success') {
                
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="zip_'.date('Y-m-d H-i-s').'.zip"');
            header('Content-type: application/zip');
            
            readfile($response['zipPath']);
            
        } else {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $response['message']; 
        }
        
        exit;
    }
    
    public function dvFilterLookupSuggestVal() {
        $response = $this->model->dvFilterLookupSuggestValModel();
        
        if ($response['status'] == 'success') {
            
            $this->view->id = $response['id'];
            $this->view->name = $response['name'];
            $this->view->comboData = $response['data'];
            $this->view->selected = $response['selected'];
            
            $response = array(
                'status' => 'success', 
                'html' => $this->view->renderPrint('dataview/search/filterLookupSuggestValue', self::$mainViewPath)
            );
        }
        
        jsonResponse($response);
    }
    
    public function dvFilterLookupSuggestValSave() {
        $response = $this->model->dvFilterLookupSuggestValSaveModel();
        jsonResponse($response);
    }
    
    public function dvFilterLookupSuggestedValues($mainDvId, $param) {
        $this->load->model('mddatamodel', 'middleware/models/');
        $savedIds = $this->model->dvFilterLookupSuggestedValuesModel($mainDvId, $param['LOOKUP_META_DATA_ID']);
        return $savedIds;
    }
    
    public function getDataMartDvRowsModel($dvId, $criteria = array()) {
        $this->load->model('mddatamodel', 'middleware/models/');
        $result = $this->model->getDataMartDvRowsModel($dvId, $criteria);
        return $result;
    }
    
    public function erdConfig() {
        
        $this->view->erdId = Input::numeric('id');
        
        if ($this->view->erdId) {
            
            $this->view->isDialog = !Input::postCheck('selectedRow');
            $this->view->isReadOnly = Input::numeric('isreadonly');
            $this->view->uniqId = getUID();
            
            $objects = $this->model->getDataMartGetDataModel('eisArcErdDV_Get_004', array('id' => $this->view->erdId));
            
            $response = array(
                'html'    => $this->view->renderPrint('config/erd/erdConfig', 'middleware/views/'), 
                'status'  => 'success', 
                'objects' => $objects, 
                'isReadOnly' => $this->view->isReadOnly, 
                'uniqId'     => $this->view->uniqId
            );
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        jsonResponse($response);
    }
    
    public function getErdConfigObjectAttributes() {
        $criteria = array(
            'erdId' => array(
                array(
                    'operator' => '=',
                    'operand' => Input::numeric('erdId')
                )
            ), 
            'tableId' => array(
                array(
                    'operator' => '=',
                    'operand' => Input::numeric('tableId')
                )
            )
        );
        
        $response = $this->model->getDataMartDvRowsModel('1636517226323426', $criteria);
        jsonResponse($response);
    }
    
    public function saveErdConfig() {
        $response = $this->model->saveErdConfigModel();
        jsonResponse($response);
    }
    
    public function paragraphUpdate() {
        
        try {
            
            $time_start = microtime(true); 
            $size       = 500;
            
            $rowsCount = $this->db->GetOne("
                SELECT 
                    COUNT(*) 
                FROM CON_TEMPLATE_PARAGRAPH 
                WHERE PARAGRAPH_TEXT IS NOT NULL"); 
            
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
                                PARAGRAPH_TEXT
                            FROM CON_TEMPLATE_PARAGRAPH
                            WHERE PARAGRAPH_TEXT IS NOT NULL
                            ORDER BY ID ASC 
                        ) a
                        WHERE rownum < ((:pageNumber * :pageSize) + 1)
                    )
                    WHERE r__ >= (((:pageNumber-1) * :pageSize) + 1)", array('pageNumber' => $p, 'pageSize' => $size));
                
                foreach ($rows as $k => $row) {
                        
                    $sql = str_replace('font-size:12px', 'font-size:12pt', $row['PARAGRAPH_TEXT']);
                    $id = $row['ID'];

                    $this->db->UpdateClob('CON_TEMPLATE_PARAGRAPH', 'PARAGRAPH_TEXT', $sql, 'ID = '.$id);
                }
            }
            
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start) / 60;
            
            echo 'Success - '.$execution_time.' minutes';
            
        } catch (ADODB_Exception $ex) {
            echo $ex->getMessage();
        }
        
        exit;
    }
    
}