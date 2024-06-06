<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdpreview_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function createEcmContentLogModel($contentId, $logType) {
        
        if (is_numeric($contentId)) {
            
            try {
                
                $sessionUserKeyId = Ue::sessionUserKeyId();
                $currentDate = Date::currentDate();
                $sessionCustomerId = Session::get(SESSION_PREFIX.'customerid');
                $showUserId = $sessionCustomerId ? $sessionCustomerId : $sessionUserKeyId;
                
                $ecmContent = array(
                    'ID'              => getUID(),
                    'CONTENT_ID'      => $contentId,
                    'SHOW_DATE'       => $currentDate,
                    'SHOW_USER_ID'    => $showUserId, 
                    'SHOW_USER_IP'    => get_client_ip(),
                    'LOG_TYPE'        => $logType, 
                    'CREATED_USER_ID' => $sessionUserKeyId,
                    'CREATED_DATE'    => $currentDate
                );

                $this->db->AutoExecute('ECM_CONTENT_LOG', $ecmContent);
                
            } catch (Exception $ex) {
                return false;
            }
        }
        
        return true;
    }
    
    public function getContentByRecordIdsModel($recordIds, $structureId = null) {
        
        try {
            
            $recordIds = Number::separatorNumbers(',', $recordIds);
            $data = array();

            if ($recordIds) {

                $where = '';

                if ($structureId && is_numeric($structureId)) {
                    $where = ' AND CM.REF_STRUCTURE_ID = '.$structureId;
                }

                $data = $this->db->GetAll("
                    SELECT 
                        EC.CONTENT_ID, 
                        EC.FILE_NAME, 
                        EC.PHYSICAL_PATH, 
                        EC.THUMB_PHYSICAL_PATH, 
                        EC.FILE_EXTENSION, 
                        (
                            SELECT 
                                COUNT(1) 
                            FROM ECM_CONTENT_FILE_VERSION 
                            WHERE CONTENT_ID = EC.CONTENT_ID 
                                AND PHYSICAL_PATH IS NOT NULL 
                                AND PREV_PHYSICAL_PATH IS NOT NULL 
                        ) AS VERSION_COUNT 
                    FROM ECM_CONTENT_MAP CM 
                        INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                    WHERE CM.RECORD_ID IN ($recordIds) 
                        $where 
                    GROUP BY 
                        EC.CONTENT_ID, 
                        EC.FILE_NAME, 
                        EC.PHYSICAL_PATH, 
                        EC.THUMB_PHYSICAL_PATH, 
                        EC.FILE_EXTENSION, 
                        EC.CREATED_DATE 
                    ORDER BY EC.CREATED_DATE ASC");
            }

            return $data;
        
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public function getContentByIdsModel($contentIds) {
        
        try {
            
            $contentIds = Number::separatorNumbers(',', $contentIds);
            $data = array();

            if ($contentIds) {

                $data = $this->db->GetAll("
                    SELECT 
                        EC.CONTENT_ID, 
                        EC.FILE_NAME, 
                        EC.PHYSICAL_PATH, 
                        EC.THUMB_PHYSICAL_PATH, 
                        EC.FILE_EXTENSION, 
                        (
                            SELECT 
                                COUNT(1) 
                            FROM ECM_CONTENT_FILE_VERSION 
                            WHERE CONTENT_ID = EC.CONTENT_ID 
                                AND PHYSICAL_PATH IS NOT NULL 
                                AND PREV_PHYSICAL_PATH IS NOT NULL 
                        ) AS VERSION_COUNT 
                    FROM ECM_CONTENT EC 
                    WHERE EC.CONTENT_ID IN ($contentIds) 
                    ORDER BY EC.CONTENT_ID ASC");
            }

            return $data;
        
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public function getEcmContentRowModel($id) {
        
        if (is_numeric($id)) {
            $row = $this->db->GetRow("SELECT * FROM ECM_CONTENT WHERE CONTENT_ID = ".$this->db->Param(0), array($id));
            return $row;  
        } 
        
        return null;
    }
    
    public function isCheckStatementExportPermissionModel() {
        
        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo()); 
        
        $id = $this->db->GetOne("SELECT META_DATA_ID FROM VW_META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array('1598840150158844'));
        
        $this->db->CompleteTrans();
        
        return $id;  
    }

}