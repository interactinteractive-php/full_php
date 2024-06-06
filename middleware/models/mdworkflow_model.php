<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mdworkflow_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;        

    public function __construct() {
        parent::__construct();
    }                 
    
    public function getWorkflowNextStatusModel($metaDataId, $dataRow) {

        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );

        if (!empty($dataRow)) {
            $param = array_merge($param, $dataRow);
        }

        $result = $this->ws->runResponse(self::$gfServiceAddress, 'GET_ROW_WFM_STATUS', $param);

        if ($result['status'] == 'success') {
            $response = array(
                'status'     => 'success',
                'data'       => $result['result'],
                'datastatus' => isset($result['result'][0]['wfmstatusid']) ? true : false
            );
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }

    public function getLastCreatedCipherTextModel($refStructureId, $recordId) {

        if ($refStructureId && $recordId) {

            $cipherText = $this->db->GetOne("
                SELECT 
                    CIPHER_TEXT 
                FROM META_WFM_LOG 
                WHERE REF_STRUCTURE_ID = $refStructureId 
                    AND RECORD_ID = $recordId 
                ORDER BY CREATED_DATE DESC");

            return $cipherText;
        }

        return null;
    }

    public function getUserDataBySessionUserIdModel() {
        
        $userKeyId = Ue::sessionUserKeyId();
        
        return $this->db->GetRow("
            SELECT 
                SUBSTR(BP.LAST_NAME, 1, 1) || '.' || BP.FIRST_NAME AS EMPLOYEE_NAME, 
                VE.POSITION_NAME, 
                VE.STATUS_NAME, 
                VE.DEPARTMENT_CODE, 
                VE.DEPARTMENT_NAME,
                UM.USER_ID 
            FROM UM_USER UM 
                INNER JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                LEFT JOIN VW_EMPLOYEE VE ON EMP.EMPLOYEE_ID = VE.EMPLOYEE_ID 
            WHERE VE.IS_ACTIVE = 1 AND UM.USER_ID = $userKeyId");
    }

    public function saveWfmassignmentModel() {
        
        if (Input::postCheck('refStructureId') && Input::postCheck('recordId')&& Input::isEmpty('refStructureId') === false && Input::isEmpty('recordId') === false) {
            
            $refStructureId = Input::post('refStructureId');
            $recordId = Input::post('recordId');

            $sessionUserId = Ue::sessionUserKeyId();
            $currentDate = Date::currentDate();
            $order = $_POST['order'];
            
            try {
                foreach ($order as $key => $row) {
                    
                    $assignMentId = getUID();
                    $dueDate = Input::param($_POST['dueDate'][$key]).' '.Input::param($_POST['dueTime'][$key]).':00';
                    
                    if (Input::postCheck('wfmAssingmentId') && Input::isEmpty('wfmAssingmentId') === false) {
                        $data = array(
                            'REF_STRUCTURE_ID' => Input::post('refStructureId'),
                            'RECORD_ID' => Input::post('recordId'),
                            'USER_ID' => Input::param($_POST['assigmentUserId'][$key]),
                            'WFM_STATUS_ID' => Input::post('wfmStatusId'),
                            'WFM_RULE_ID' => Input::post('ruleId'),
                            'IS_NEED_SIGN' => Input::param($_POST['lock'][$key]),
                            'ASSIGNED_DATE' => $currentDate,
                            'BATCH_NUMBER' => '',
                            'DUE_DATE' => $dueDate,
                            'ASSIGNED_USER_ID' => $sessionUserId,
                            'ORDER_NUM' => $row,
                            'IS_TRANSFERED' => '1',
                        );
                        $this->db->AutoExecute('META_WFM_ASSIGNMENT', $data, 'UPDATE', 'ID = '. Input::param($_POST['wfmAssingmentId']));
                    } else {
                        $data = array(
                            'ID' => $assignMentId,
                            'REF_STRUCTURE_ID' => Input::post('refStructureId'),
                            'RECORD_ID' => Input::post('recordId'),
                            'USER_ID' => Input::param($_POST['assigmentUserId'][$key]),
                            'WFM_STATUS_ID' => Input::post('wfmStatusId'),
                            'WFM_RULE_ID' => Input::post('ruleId'),
                            'IS_NEED_SIGN' => Input::param($_POST['lock'][$key]),
                            'ASSIGNED_DATE' => $currentDate,
                            'BATCH_NUMBER' => '',
                            'DUE_DATE' => $dueDate,
                            'ASSIGNED_USER_ID' => $sessionUserId,
                            'ORDER_NUM' => $row,
                            'IS_TRANSFERED' => '0',
                        );
                        $this->db->AutoExecute('META_WFM_ASSIGNMENT', $data);
                    }
                }
            } catch (Exception $ex) {
                return array('status' => 'warning', 'message' => Lang::line('msg_save_error'), 'ex' => $ex->msg, 'exception' => $ex);
            }
        }
        
        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    public function deleteAssignmentModel() {
        
        $assignmentId = Input::post('assigmentId');
        
        $row = $this->db->GetRow("SELECT * FROM META_WFM_ASSIGNMENT WHERE ID = $assignmentId");
        
        $param = array(
            'systemMetaGroupId' => Input::numeric('metaDataId'), 
            'wfmStatusId' => $row['WFM_STATUS_ID'], 
            'id' => $row['RECORD_ID'], 
            'assignedUsers' => array(
                array(
                    'id' => $assignmentId, 
                    'isTransfered' => $row['IS_TRANSFERED'], 
                    'isNeedSign' => $row['IS_NEED_SIGN'], 
                    'orderNumber' => $row['ORDER_NUM'], 
                    'userId' => $row['USER_ID'],
                    'isActive' => 0, 
                    'removedDate' => Date::currentDate('Y-m-d H:i:s'), 
                    'removedUserId' => Ue::sessionUserKeyId()
                )
            )
        );
        
        $result = $this->ws->runResponse(self::$gfServiceAddress, 'SET_ROW_WFM_STATUS', $param);
        
        if ($result['status'] == 'success') {
            $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }
    
    public function viewStatusFlowModel($refStructureId, $recordId, $statusId) {
        
        $param = array(
            'systemMetaGroupId' => 1516619439218, // DVCode = headerStatusList
            'showQuery' => 0, 
            'ignorePermission' => 1, 
            'criteria' => array(
                'refStrId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $refStructureId
                    )
                ),
                'statusId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $statusId
                    )
                ),
                'recId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $recordId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result'])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'];
        } else {
            return array();
        }
    }
    
    public function getStatusAssignmentUsersModel($refStructureId, $recordId, $statusId) {
        
        $param = array(
            'systemMetaGroupId' => 1516620901266387, // DVCode = sidebarStatusList
            'showQuery' => 0, 
            'ignorePermission' => 1, 
            'criteria' => array(
                'refStrId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $refStructureId
                    )
                ),
                'statusId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $statusId
                    )
                ), 
                'recId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $recordId
                    )
                )
            )
        );
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result'])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'];
        } else {
            return array();
        }
    }
    
    public function changeWfmStatusByStrModel() {
        
        $param = array(
            'subject' => Input::post('paramStr'), 
            'newWfmDescription' => Input::post('description')
        );
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'setRowWfmStatusOnLog', $param);

        if ($result['status'] == 'success') {
            $result = array('status' => 'success', 'message' => 'Амжилттай төлөв өөрчлөгдлөө.');
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }
    
    public function copyAssignmentModel() {
        
        try {
            
            $assigmentId = Input::numeric('assigmentId');
            
            if ($assigmentId) {
                
                $row = $this->db->GetRow("SELECT * FROM META_WFM_ASSIGNMENT WHERE ID = ".$this->db->Param(0), array($assigmentId));
                
                if ($row) {
                    
                    $row['ID'] = getUID();
                    $row['USER_STATUS_ID'] = null;
                    $row['USER_STATUS_DATE'] = null;
                    $row['DESCRIPTION'] = 'НӨХЦӨЛ БУЦААВ';
                    
                    $this->db->AutoExecute('META_WFM_ASSIGNMENT', array('IS_ACTIVE' => 0), 'UPDATE', 'ID = '.$assigmentId);
                    $this->db->AutoExecute('META_WFM_ASSIGNMENT', $row);
                    
                    $response = array('status' => 'success', 'message' => 'Төлөв амжилттай буцаагдлаа.');
                    
                } else {
                    $response = array('status' => 'error', 'message' => 'No data! Assignment Id: '.$assigmentId);
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid assignment id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
}