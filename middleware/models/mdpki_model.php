<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdpki_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCertificateInformation() {
        $userId = Ue::sessionUserKeyId();
        return $this->db->GetRow("SELECT MONPASS_USER_ID, CERTIFICATE_SERIAL_NUMBER, TOKEN_SERIAL_NUMBER FROM UM_USER_MONPASS_MAP WHERE USER_ID = '$userId' AND IS_ACTIVE = 1");
    }

    public function saveHashForDocumentSign($hash, $ecmContentId = null) {
        try{
            $userId = Ue::sessionUserKeyId();
            $hash = Security::sanitize($hash);
            $expiryDate = strtotime(Date::currentDate('Y-m-d H:i:s') . "+10minutes");
            $plainText = null;
            $array = array(
                'ID' => getUID(),
                'HASH' => $hash, 
                'IS_ACTIVE' => 1, 
                'EXPIRY_DATE' => $expiryDate, 
                'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                'ECM_CONTENT_ID' => $ecmContentId, 
                'PLAIN_TEXT' => $plainText,
                'CREATED_USER_ID' => $userId
            );
            $this->db->AutoExecute('PKI_ONE_TIME_HASH', $array);
            return true;
        }catch(Exception $e) {
            return false;
        }
    }

    public function checkIsSigned($ecmContentId, $filePath) {
        
        if ($ecmContentId) {
            try {
                if (Config::getFromCache('createMapSignedFiles')) {
                    $qry = "SELECT 
                                ID ,
                                CONTENT_ID,
                                REF_STRUCTURE_ID,
                                RECORD_ID,
                                ". $this->db->IfNull('ORDER_NUM', '0') ."+1 AS ORDER_NUM
                            FROM ECM_CONTENT_MAP WHERE CONTENT_ID = " . $this->db->Param(0);
                    $contentMap = $this->db->GetRow($qry, array($ecmContentId));
                    if ($contentMap) {
                        $qry = "SELECT 
                                    CONTENT_ID,
                                    FILE_NAME,
                                    PHYSICAL_PATH,
                                    FILE_SIZE,
                                    FILE_EXTENSION,
                                    CREATED_DATE,
                                    CREATED_USER_ID,
                                    IS_SIGNED
                                FROM ECM_CONTENT WHERE CONTENT_ID = " . $this->db->Param(0);
                        $contentData = $this->db->GetRow($qry, array($ecmContentId));
                        
                        $userId = Ue::sessionUserKeyId();
                        $currentDate = Date::currentDate();
    
                        $insertContent = array(
                            'CONTENT_ID' => getUID(),
                            'FILE_NAME' => $contentData['FILE_NAME'],
                            'PHYSICAL_PATH' => $filePath,
                            'FILE_EXTENSION' => 'pdf',
                            'FILE_SIZE' => filesize($filePath),
                            'CREATED_DATE' => $currentDate,
                            'CREATED_USER_ID' => $userId,
                            'IS_SIGNED' => '1',
                        );
    
                        $result = $this->db->AutoExecute('ECM_CONTENT', $insertContent);
                        if ($result) {
                            
                            $map = array(
                                'ID' => getUID(),
                                'CONTENT_ID' => $insertContent['CONTENT_ID'],
                                'REF_STRUCTURE_ID' => $contentMap['REF_STRUCTURE_ID'],
                                'RECORD_ID' => $contentMap['RECORD_ID'],
                                'ORDER_NUM' => $contentMap['ORDER_NUM'],
                            );
    
                            $result = $this->db->AutoExecute('ECM_CONTENT_MAP', $map);
                        }
                    }
                } else {
                    $this->db->AutoExecute('ECM_CONTENT', array('IS_SIGNED' => 1, 'THUMB_PHYSICAL_PATH' => $filePath), 'UPDATE', 'CONTENT_ID = ' . $ecmContentId);
                }
                return true;
            } catch(Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getRegisterNumber() {
        $row = $this->db->GetOne("
            SELECT
                BP.STATE_REG_NUMBER
            FROM BASE_PERSON BP
                LEFT JOIN UM_SYSTEM_USER CRM  ON CRM.PERSON_ID = BP.PERSON_ID 
            WHERE CRM.USER_ID = " . Ue::sessionUserId()
        );        

        if ($row) {
            return $row;
        }
        return '';
    }
    
}