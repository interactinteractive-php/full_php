<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlog_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }
    
    public function getRecordLogHistoryListModel() {
        
        $selectedRow = Input::post('selectedRow');
        
        $param = array(
            'systemMetaGroupId' => '1604893919544822',
            'showQuery' => 0, 
            'ignorePermission' => 1, 
            'criteria' => array(
                'filterMetaGroupId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::numeric('refStructureId')
                    )
                ), 
                'filterRecordId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $selectedRow['id']
                    )
                )
            )
        );
        
        $result = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($result['status'] == 'success' && isset($result['result'])) {

            unset($result['result']['paging']);
            unset($result['result']['aggregatecolumns']);
            
            if (isset($result['result'][0])) {
                $response = array('status' => 'success', 'data' => $result['result']);
            } else {
                $response = array('status' => 'error', 'message' => 'No data!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }
    
    public function getRecordLogDetailModel() {
        
        $param['id'] = Input::numeric('logId');
        
        $result = $this->ws->runArrayResponse(self::$gfServiceAddress, 'getLogInfo', $param);

        if ($result['status'] == 'success') {
            
            if (is_countable($result['result']) && count($result['result'])) {
                
                $processId = issetParam($result['result']['_processid']);
                
                unset($result['result']['_processid']);
                
                if (!$processId && $dvId = Input::numeric('dvId')) {
                    
                    $processId = self::getLogViewProcessIdByDvIdModel($dvId);
                    
                    if ($processId) {
                        Mdwebservice::$bpActionType = 'removedlog';
                    }
                }
                
                $result = array(
                    'status'    => $result['status'], 
                    'data'      => $result['result'], 
                    'processId' => $processId
                );
                
            } else {
                $result = array('status' => 'error', 'message' => 'Тохирох үр дүн олдсонгүй!');
            }
            
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }
    
    public function logRecoverModel() {
        $param['id'] = Input::numeric('logId');
        
        $result = $this->ws->runArrayResponse(self::$gfServiceAddress, 'restoreLogData', $param);
        
        if ($result['status'] == 'success') {
            $result = array('status' => 'success', 'message' => $this->lang->line('PF_RECOVER_SUCCESS'));
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $result;
    }
    
    public function getLogViewProcessIdByDvIdModel($dvId) {
        
        $processId = $this->db->GetOne("
            SELECT 
                PD.PROCESS_META_DATA_ID 
            FROM META_DM_PROCESS_DTL PD 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PD.PROCESS_META_DATA_ID 
            WHERE PD.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PD.IS_MAIN = 1 
            ORDER BY PD.ORDER_NUM ASC", 
            array($dvId)
        ); 
        
        return $processId;
    }
    
    public function ecmContentViewLogModel() {
        try {
            
            $contentId = Input::numeric('contentId');
            $logUniqId = Input::numeric('logUniqId');
            
            if ($logUniqId) {
                $row = $this->db->GetRow("SELECT ID, START_TIME, END_TIME FROM RECORD_VIEW_LOG WHERE ID = ".$this->db->Param(0), array($logUniqId));
                
                if ($row) {
                    
                    $currentDate = Date::currentDate();
                    $timeFirst   = strtotime($row['START_TIME']);
                    $timeSecond  = strtotime($currentDate);

                    $data = array(
                        'END_TIME' => $currentDate, 
                        'READ_SECOND' => ($timeSecond - $timeFirst)
                    );

                    $this->db->AutoExecute('RECORD_VIEW_LOG', $data, 'UPDATE', 'ID = '.$logUniqId);
                    $result = array('status' => 'success');
                    
                } else {
                    $result = array('status' => 'error', 'message' => 'Invalid uniqId!');
                }
                
            } elseif ($contentId) {
                
                includeLib('Detect/Browser');
                $browser = new Browser();
                
                $data = array(
                    'ID'           => getUID(), 
                    'TABLE_NAME'   => 'ECM_CONTENT',
                    'RECORD_ID'    => $contentId, 
                    'START_TIME'   => Date::currentDate(), 
                    'IP_ADDRESS'   => get_client_ip(), 
                    'BROWSER_NAME' => $browser->getBrowser(), 
                    'USER_ID'      => Ue::sessionUserKeyId()
                );

                $this->db->AutoExecute('RECORD_VIEW_LOG', $data);
                $result = array('status' => 'success', 'uniqId' => $data['ID']);
                
            } else {
                $result = array('status' => 'error', 'message' => 'Invalid id!');
            }
                    
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => 'An error occurred!');
        }
        
        return $result;
    }
    
    public function metaConfigChangeLogModel() {
        
        try {
            
            $metaId = Input::numeric('metaId');
            $uniqId = Input::numeric('uniqId');
            
            if ($metaId && $uniqId) {
                
                $this->db->Execute("DELETE FROM RECORD_VIEW_LOG WHERE ID = ".$this->db->Param(0)." AND TABLE_NAME = 'META_DATA'", array($uniqId));
                
                $beforeDate = Date::beforeDate('Y-m-d H:i:s', '-1 hour');
                $this->db->Execute("DELETE FROM RECORD_VIEW_LOG WHERE TABLE_NAME = 'META_DATA' AND START_TIME < TO_DATE('$beforeDate', 'YYYY-MM-DD HH24:MI:SS')");
                
                $result = array('status' => 'success');
                
            } elseif ($metaId && !$uniqId) {
                
                Auth::handleLogin();
                
                includeLib('Detect/Browser');
                $browser = new Browser();
                
                $data = array(
                    'ID'           => getUID(), 
                    'TABLE_NAME'   => 'META_DATA',
                    'RECORD_ID'    => $metaId, 
                    'START_TIME'   => Date::currentDate(), 
                    'IP_ADDRESS'   => get_client_ip(), 
                    'BROWSER_NAME' => $browser->getBrowser(), 
                    'USER_ID'      => Ue::sessionUserId()
                );

                $this->db->AutoExecute('RECORD_VIEW_LOG', $data);
                $result = array('status' => 'success', 'uniqId' => $data['ID']);
                
            } else {
                $result = array('status' => 'error', 'message' => 'Invalid id!');
            }
                    
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => 'An error occurred!');
        }
        
        return $result;
    }
    
}