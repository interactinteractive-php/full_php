<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdcommon_Model extends Model {

    public static $gfServiceAddress = GF_SERVICE_ADDRESS;
    public static $fingerServiceAddress = 'http://192.168.100.22/FingerScan/FingerScan.svc?wsdl';

    public function __construct() {
        parent::__construct();
    }

    public function getAutoNumberModel($params) {
        $result = $this->ws->runResponse(self::$gfServiceAddress, 'FIN_AUNUM_004', $params);
        return $this->ws->returnValue($result);
    }

    public function getRowsDataViewByCodeNameModel($dataViewId, $criteria, $idField, $codeField, $nameField) {
        
        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 1, 
            'ignorePermission' => 1,  
            'criteria' => $criteria
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());

            $sqlResult = $this->db->SelectLimit($data['result'], 10, -1);

            $this->db->CompleteTrans();

            if ($sqlResult && isset($sqlResult->_array)) {

                $data = array();
                $rowsData = Arr::changeKeyLower($sqlResult->_array);

                $idField = strtolower($idField);
                $codeField = strtolower($codeField);
                $nameField = strtolower($nameField);

                foreach ($rowsData as $row) {
                    $name = array(
                        'id' => $row[$idField],
                        'code' => $row[$codeField],
                        'name' => is_null($row[$nameField]) ? '' : $row[$nameField]
                    );
                    array_push($data, $name);
                }

                return $data;
            }
        }

        return array();
    }

    public function getRowsDataViewByMetaIdModel($dataViewId) {

        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());

            $sqlResult = $this->db->GetAll($data['result']);

            $this->db->CompleteTrans();

            if ($sqlResult) {
                $this->load->model('mdobject', 'middleware/models/');

                $idField = $this->model->getDataViewMetaValueId($dataViewId);
                $nameField = $this->model->getDataViewMetaValueName($dataViewId);                        
                $data = array();
                $rowsData = Arr::changeKeyLower($sqlResult);

                $idField = strtolower($idField);
                $nameField = strtolower($nameField);

                foreach ($rowsData as $row) {
                    $name = array(
                        'id' => $row[$idField],
                        'name' => is_null($row[$nameField]) ? '' : $row[$nameField]
                    );
                    array_push($data, $name);
                }

                return $data;
            }
        }

        return array();
    }

    public function getMetaTypeMetaGroupGonfigModel($metaDataId, $fieldPath) {
        $row = $this->db->GetRow("
            SELECT 
                DATA_TYPE AS META_TYPE_CODE 
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = $metaDataId 
                AND LOWER(FIELD_PATH) = '" . strtolower($fieldPath) . "'");

        return $row;
    }    

    public function savePersonRegisterModel() {

        $personId = getUID();
        $registerNumber = Input::post('registerNumber');
        $userKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate('Y-m-d H:i:s');
        $firstName = Input::post('firstName');
        $lastName = Input::post('lastName');

        $data = array(
            'PERSON_ID' => $personId, 
            'STATE_REG_NUMBER' => $registerNumber, 
            'LAST_NAME' => $lastName, 
            'FIRST_NAME' => $firstName, 
            'FAMILY_NAME' => Input::post('familyName'), 
            'DATE_OF_BIRTH' => Date::formatter(Input::post('birthDate'), 'Y-m-d'), 
            'GENDER' => Input::post('gender'), 
            'IS_ACTIVE' => 1, 
            'CREATED_DATE' => $currentDate, 
            'CREATED_USER_ID' => $userKeyId
        );

        $result = $this->db->AutoExecute('BASE_PERSON', $data);

        if ($result) {

            $customerId = getUID();

            $dataCrm = array(
                'CUSTOMER_ID' => $customerId, 
                'CUSTOMER_CODE' => $registerNumber, 
                'CUSTOMER_NAME' => $firstName,
                'STATE_REG_NUMBER' => $registerNumber,
                'LAST_NAME' => $lastName,
                'PERSON_ID' => $personId, 
                'IS_ACTIVE' => 1, 
                'CREATED_DATE' => $currentDate, 
                'CREATED_USER_ID' => $userKeyId
            );

            $resultCrm = $this->db->AutoExecute('CRM_CUSTOMER', $dataCrm);

            if ($resultCrm) {

                $dataCrmAddress = array(
                    'ID' => getUID(), 
                    'CUSTOMER_ID' => $customerId, 
                    'PERSON_ID' => $personId, 
                    'LINE1' => Input::post('city'), 
                    'LINE2' => Input::post('district'), 
                    'LINE3' => Input::post('street'), 
                    'DESCRIPTION' => Input::post('addressDetail') 
                );
                $this->db->AutoExecute('CRM_CUSTOMER_ADDRESS', $dataCrmAddress);

                if (Input::isEmpty('person_attach_photo') == false) {

                    $photoExtension = Input::isEmpty('person_attach_photo_extension');
                    $photoName = 'file_'.getUID().'.'.$photoExtension;

                    $imgOrig = str_replace(' ', '+', Input::post('person_attach_photo'));
                    $imgThumb = str_replace(' ', '+', Input::post('person_attach_photo_thumb'));
                    $dataOrig = base64_decode($imgOrig);
                    $dataThumb = base64_decode($imgThumb);

                    $origPhoto = file_put_contents(UPLOADPATH.'customer/'.$photoName, $dataOrig);

                    if ($origPhoto) {

                        $thumbPhoto = file_put_contents(UPLOADPATH.'customer/thumb/'.$photoName, $dataThumb);

                        if ($thumbPhoto) {
                            $dataAttach = array(
                                'OBJECT_PHOTO' => UPLOADPATH.'customer/'.$photoName
                            );
                            $this->db->AutoExecute('CRM_CUSTOMER', $dataAttach, 'UPDATE', 'CUSTOMER_ID = '.$customerId);
                        }
                    }

                } elseif (isset($_FILES['person_attach']['name']) && $_FILES['person_attach']['name'] != '') {

                    $newFileName = 'file_' . getUID();
                    $fileExtension = strtolower(substr($_FILES['person_attach']['name'], strrpos($_FILES['person_attach']['name'], '.') + 1));
                    $fileName = $newFileName.'.'.$fileExtension;

                    FileUpload::SetFileName($fileName);
                    FileUpload::SetTempName($_FILES['person_attach']['tmp_name']);
                    FileUpload::SetUploadDirectory(UPLOADPATH . 'customer/');
                    FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                    FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
                    $uploadResult = FileUpload::UploadFile();

                    if ($uploadResult) {
                        $dataAttach = array(
                            'OBJECT_PHOTO' => UPLOADPATH . 'customer/'.$fileName
                        );
                        $this->db->AutoExecute('CRM_CUSTOMER', $dataAttach, 'UPDATE', 'CUSTOMER_ID = '.$customerId);
                    }

                } elseif (Input::isEmpty('person_attach_url') == false) { 

                    $tempPath = Input::post('person_attach_url');
                    $fileNameExplodeArr = explode('.', $tempPath);
                    $fileExtension = $fileNameExplodeArr[1];
                    $newFileName = 'file_'.getUID().'.'.$fileExtension;

                    $newPath = UPLOADPATH . 'customer/'.$newFileName;

                    $cut = rename($tempPath, $newPath);

                    if ($cut) {
                        $dataAttach = array(
                            'OBJECT_PHOTO' => $newPath 
                        );
                        $this->db->AutoExecute('CRM_CUSTOMER', $dataAttach, 'UPDATE', 'CUSTOMER_ID = '.$customerId);
                    }
                }
            }

            if (Input::isEmpty('fingerPrint') == false) {

                $userId = $this->db->GetOne("SELECT (".$this->db->IfNull('MAX(USERID)', '0')." + 1) AS NEXT_ID FROM IENGINE_IDKIT");

                $param = array(
                    'userId' => (int) $userId, 
                    'template' => Input::post('fingerPrint')
                );

                $result = $this->ws->caller('wsdl', self::$fingerServiceAddress, 'Add', 'AddResult', $param);

                if (isset($result[0]['AddResult']) && $result[0]['AddResult']) {
                    $dataPersonFinger = array(
                        'ID' => getUID(), 
                        'PERSON_ID' => $personId, 
                        'USERID' => $userId
                    );
                    $this->db->AutoExecute('BASE_PERSON_FINGER', $dataPersonFinger);
                }

                /*$dataFinger = array(
                    'USERID' => $userId
                );
                $this->db->AutoExecute('IENGINE_IDKIT', $dataFinger);

                $encodedData = str_replace(' ', '+', Input::post('fingerPrint'));
                $decodedData = base64_decode($encodedData);
                $this->db->UpdateBlob('IENGINE_IDKIT', 'RECORD', $decodedData, 'USERID = '.$userId);*/
            }

            $responseData = array(
                'registerNumber' => $data['STATE_REG_NUMBER'], 
                'lastName' => $data['LAST_NAME'], 
                'firstName' => $data['FIRST_NAME'], 
                'familyName' => $data['FAMILY_NAME'], 
                'birthDate' => $data['DATE_OF_BIRTH'], 
                'gender' => $data['GENDER'] == '0' ? Lang::line('male') : Lang::line('female'), 
                'city' => '',
                'district' => '',
                'street' => '',
                'addressDetail' => '', 
                'photo' => ''
            );

            if (isset($dataCrmAddress)) {
                $responseData['city'] = $dataCrmAddress['LINE1'];
                $responseData['district'] = $dataCrmAddress['LINE2'];
                $responseData['street'] = $dataCrmAddress['LINE3'];
                $responseData['addressDetail'] = $dataCrmAddress['DESCRIPTION'];
            }

            if (isset($dataAttach)) {
                $responseData['photo'] = $dataAttach['OBJECT_PHOTO'];
            }

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'), 'data' => $responseData);
        }

        return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
    }

    public function getPersonByIdKitUserIdModel($userId) {

        $row = $this->db->GetRow("
            SELECT 
                BP.FIRST_NAME, 
                BP.LAST_NAME, 
                BP.DATE_OF_BIRTH, 
                BP.GENDER, 
                BP.STATE_REG_NUMBER, 
                BP.FAMILY_NAME, 
                CA.LINE1 AS CITY,
                CA.LINE2 AS DISTRICT, 
                CA.LINE3 AS STREET, 
                CA.DESCRIPTION AS ADDRESSDETAIL, 
                CRM.OBJECT_PHOTO 
            FROM BASE_PERSON BP 
                INNER JOIN BASE_PERSON_FINGER PF ON PF.PERSON_ID = BP.PERSON_ID 
                LEFT JOIN CRM_CUSTOMER_ADDRESS CA ON CA.PERSON_ID = BP.PERSON_ID 
                LEFT JOIN CRM_CUSTOMER CRM ON CRM.PERSON_ID = BP.PERSON_ID 
            WHERE PF.USERID = $userId");

        if ($row) {

            $responseData = array(
                'registerNumber' => $row['STATE_REG_NUMBER'], 
                'lastName' => $row['LAST_NAME'], 
                'firstName' => $row['FIRST_NAME'], 
                'familyName' => $row['FAMILY_NAME'],  
                'birthDate' => $row['DATE_OF_BIRTH'], 
                'gender' => $row['GENDER'] == '0' ? Lang::line('male') : Lang::line('female'), 
                'city' => $row['CITY'],  
                'district' => $row['DISTRICT'],  
                'street' => $row['STREET'],  
                'addressDetail' => $row['ADDRESSDETAIL'], 
                'photo' => $row['OBJECT_PHOTO']   
            );

            return array('status' => 'success', 'data' => $responseData);
        }

        return array('status' => 'error', 'message' => 'Error');
    }

    public function getBpCheckListModel($tempId, $refStructureId, $recordId) {

        $where = "= $tempId ";

        if (strpos($tempId, ',') !== false) {
            $where = "IN ($tempId)";
        }

        $data = $this->db->GetAll(" 
            SELECT 
                TD.CHECKLIST_ID, 
                PC.NAME, 
                PC.IS_MANDATORY, 
                PC.PROCESS_META_DATA_ID, 
                CV.ID,  
                CV.IS_CHECKED, 
                CG.ID AS GROUP_ID, 
                CG.GROUP_NAME, 
                MAX(TD.TEMPLATE_ID) AS TEMPLATE_ID  
            FROM META_PROCESS_CHECKLIST PC 
                INNER JOIN META_BP_CHECKLIST_TEMP_DTL TD ON TD.CHECKLIST_ID = PC.ID 
                LEFT JOIN META_PROCESS_CHECKLIST_GROUP CG ON CG.ID = PC.GROUP_ID 
                LEFT JOIN META_BP_CHECKLIST_VALUE CV ON CV.REF_STRUCTURE_ID = $refStructureId 
                    AND CV.TEMPLATE_ID = TD.TEMPLATE_ID 
                    AND CV.CHECKLIST_ID = PC.ID 
                    AND CV.RECORD_ID = $recordId 
            WHERE TD.TEMPLATE_ID $where 
            GROUP BY 
                TD.CHECKLIST_ID, 
                PC.NAME, 
                PC.IS_MANDATORY, 
                PC.PROCESS_META_DATA_ID, 
                CV.ID,  
                CV.IS_CHECKED, 
                CG.ID, 
                CG.GROUP_NAME, 
                TD.ORDER_NUM  
            ORDER BY TD.ORDER_NUM ASC");

        return $data;
    }

    public function saveCheckListFormModel() {

        $refStructureId = Input::post('refStructureId');
        $recordId = Input::post('recordId');

        $keyCols = array('REF_STRUCTURE_ID', 'CHECKLIST_ID', 'TEMPLATE_ID', 'RECORD_ID');

        $checkListData = $_POST['bp_checklist']; 

        foreach ($checkListData as $checkId => $val) {

            $tempId = Input::param($_POST['bp_checkListTempId'][$checkId]); 

            $fields = array(
                'ID' => getUID(), 
                'REF_STRUCTURE_ID' => $refStructureId, 
                'RECORD_ID' => $recordId, 
                'TEMPLATE_ID' => $tempId, 
                'IS_CHECKED' => $val, 
                'CHECKLIST_ID' => $checkId 
            );

            $result = $this->db->Replace('META_BP_CHECKLIST_VALUE', $fields, $keyCols);
        }

        if ($result) {
            return array('status' => 'success', 'message' => 'Successfuly');
        }
        return array('status' => 'error', 'message' => 'Error');
    }

    public function getMetaValueNameModel() {

        $groupMetaDataIdList = Input::post('groupMetaDataIdList');
        $groupMetaDataIdList = trim($groupMetaDataIdList, ',');

        $metaValueNameColumnName = $this->db->GetAll("SELECT MAIN_META_DATA_ID, LOWER(FIELD_PATH) AS FIELD_PATH FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID IN($groupMetaDataIdList) AND LOWER(INPUT_NAME) = 'meta_value_name'");

        return $metaValueNameColumnName;
    }

    public function saveNtrFingerDataModel() {
        $userFingerId = getUID();
        $userId = Input::post('userId');
        $base64Img = Input::post('fingerImg');
        
        $filePath = base64_to_jpeg($base64Img, UPLOADPATH . 'finger/user/'. $userId .'.jpg' );
        
        $userDatas = array(
            'USER_FINGER_ID' => $userFingerId,
            'USER_ID' => $userId,
            'FILE_PATH' => $filePath,
        );
        
        $result = $this->db->AutoExecute('UM_USER_FINGER', $userDatas);
        
        if ($result) {
            $this->db->UpdateClob('UM_USER_FINGER', 'USER_FINGER', $base64Img, 'USER_FINGER_ID = '.$userFingerId);
            return array('status' => 'success', 'message' => 'Хурууны хээ амжилттай хадгалагдлаа.');
        }
        return array('status' => 'error', 'message' => 'Error');            
    }
    
    public function saveNtrUserFingerCtrlModel() {
        $userFingerId = getUID();
        Session::init();
        $userId = Ue::sessionUserKeyId();
        
        $base64Img = Input::post('fingerImg');
        $checkUserKey = $this->db->GetRow("SELECT * FROM UM_USER_FINGER WHERE USER_ID = $userId");
        
        if ($checkUserKey) { 
            return array('status' => 'warning', 'message' => 'Хурууны хээ бүртгэлтэй байна.');
        } else {
            
            $filePath = base64_to_jpeg($base64Img, UPLOADPATH . 'finger/user/'. $userId .'.jpg' );

            $userDatas = array(
                'USER_FINGER_ID' => $userFingerId,
                'USER_ID' => $userId,
                'FILE_PATH' => $filePath,
            );

            $result = $this->db->AutoExecute('UM_USER_FINGER', $userDatas);

            if ($result) {
                $this->db->UpdateClob('UM_USER_FINGER', 'USER_FINGER', $base64Img, 'USER_FINGER_ID = '.$userFingerId);
                return array('status' => 'success', 'message' => 'Хурууны хээ амжилттай хадгалагдлаа.');
            }
            
        }
        
        return array('status' => 'error', 'message' => 'Error');            
    }

    public function getDvResultAutoCompleteModel() {

        $q = Input::post('q');
        $metaGroupId = Input::post('metaGroupId');
        $metaValueName = Input::post('metaValueName');
        $tempParam = Input::post('tempParam');

        $criteria = array(
            $metaValueName => array(
                array(
                    'operator' => 'LIKE',
                    'operand' => '%' . $q . '%'
                )
            )
        );

        if (!is_null($tempParam)) {
            foreach ($tempParam as $key => $value) {
                $criteria = array_merge($criteria,
                    array(
                        $key => array(
                            array(
                                'operator' => 'LIKE',
                                'operand' => $value
                            )
                        )
                    )
                );
            }
        }

        $param = array(
            'systemMetaGroupId' => $metaGroupId,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => '1',
                'pageSize' => '50',
            ),
        );

        $result = array();

        $param['criteria'] = $criteria;

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            $result = $data['result'];
        }

        return $result;
    }

    public function getMetaRowByCodeModel($code) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID,
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                MT.META_TYPE_CODE, 
                BL.BOOKMARK_URL, 
                BL.TARGET AS BOOKMARK_TARGET 
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = MD.META_DATA_ID  
            WHERE LOWER(MD.META_DATA_CODE) = '".strtolower($code)."'");

        return $row;
    }
    
    public function checkDataPermissionModel($objectCode, $actionId, $recordId) {
        
        Session::init();
        
        $param = array(
            'userId' => Ue::sessionUserKeyId(),
            'type' => $objectCode, 
            'actionId' => $actionId, 
            'recordId' => $recordId
        );        

        $result = $this->ws->runResponse(self::$gfServiceAddress, 'checkUserPermission', $param);

        if ($result['status'] === 'success') {
            return true;
        } else {
            return false;
        }
    }
    
    public function controlSubTypeModel($dataType, $lookupType, $isDv) {
        
        $where = null;
                
        if ($lookupType) {
            $lookupType = Input::param($lookupType);
            $where .= "LOWER(CRITERIA) = 'lookuptype=$lookupType' OR "; 
        }
        
        $data = $this->db->GetAll("
            SELECT 
                TYPE_CODE 
            FROM META_FIELD_SUBTYPE 
            WHERE PARENT_TYPE_CODE = ".$this->db->Param(0)." 
                AND ".($isDv ? 'IS_DV = 1' : 'IS_BP = 1')." 
                AND ($where CRITERIA IS NULL) 
            GROUP BY TYPE_CODE", array($dataType));
        
        return $data;
    }

}