<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdfolder_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    public function deleteFolderModel($folderId, $replaceMetaDataId) {
        $result = $this->ws->runResponse(self::$gfServiceAddress, 'FD_DELETE', array(
                'id' => Input::param($folderId),
                'replaceId' => Input::param($replaceMetaDataId)
            )
        );

        if ($result['status'] == 'success') {
            return array(
                'status' => 'success',
                'message' => Lang::line('msg_delete_success')
            );
        } else {
            $message = $result['text'];
            $message .= $this->ws->errorReport($result);
            return array('status' => 'error', 'message' => $message);
        }
    }

    public function isUsedFolderModel($folderId) {
        return $this->ws->runResponse(self::$gfServiceAddress, 'IS_USED_FOLDER', array('id' => Input::param($folderId)));
    }

    public function commonFolderGridModel() {

        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1 ) * $rows;
        $where = '';

        if (Input::postCheck('searchData')) {
            parse_str(Input::post('searchData'), $qryStrings);
            foreach ($qryStrings as $k => $v) {
                if ($k == 'folderCode' && !empty($v)) {
                    $where .= " AND LOWER(FF.FOLDER_CODE) LIKE '%" . Str::lower($v) . "%'";
                } elseif ($k == 'folderName' && !empty($v)) {
                    $where .= " AND LOWER(FF.FOLDER_NAME) LIKE '%" . Str::lower($v) . "%'";
                }
            }
        }

        $sortField = 'CREATED_DATE';
        $sortOrder = 'DESC';
        
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }
        
        $join = '';
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();
            
            if ($sessionUserKeyId != 1) {
                $join = 'INNER JOIN FVM_FOLDER_USER_PERMISSION FB ON FB.FOLDER_ID = FF.FOLDER_ID AND FB.USER_ID = '.$sessionUserKeyId;
            }
        }

        $rowCount = $this->db->GetRow(
            "SELECT 
                COUNT(FF.FOLDER_ID) AS ROW_COUNT 
            FROM FVM_FOLDER FF 
                $join 
                LEFT JOIN UM_USER US ON US.USER_ID = FF.CREATED_USER_ID 
                LEFT JOIN UM_SYSTEM_USER SU ON SU.USER_ID = US.SYSTEM_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID 
            WHERE FF.IS_ACTIVE = 1 " . $where);

        $selectList = "
            SELECT 
                FF.FOLDER_ID,
                FF.FOLDER_CODE,
                FF.FOLDER_NAME,
                FF.CREATED_DATE,
                " . $this->db->IfNull('BP.FIRST_NAME', 'SU.USERNAME') . " AS CREATED_PERSON_NAME 
            FROM FVM_FOLDER FF 
                $join 
                LEFT JOIN UM_USER US ON US.USER_ID = FF.CREATED_USER_ID 
                LEFT JOIN UM_SYSTEM_USER SU ON SU.USER_ID = US.SYSTEM_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID 
            WHERE 
                FF.IS_ACTIVE = 1 $where 
            ORDER BY $sortField $sortOrder";

        $result = array();
        $result['total'] = $rowCount['ROW_COUNT'];
        $result['rows'] = array();

        if ($result['total'] > 0) {
            $rs = $this->db->SelectLimit($selectList, $rows, $offset);
            $result['rows'] = $rs->_array;
        }

        return $result;
    }

    
    public function metaFolderGridCompleteModel($type, $q) {

        $join = '';
        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();
            
            if ($sessionUserKeyId != 1) {
                $join = 'INNER JOIN FVM_FOLDER_USER_PERMISSION FB ON FB.FOLDER_ID = FF.FOLDER_ID AND FB.USER_ID = '.$sessionUserKeyId;
            }
        }

        if ($type == 'code') {
            $selectList = "
                SELECT
                FF.FOLDER_ID,
                FF.FOLDER_CODE,
                FF.FOLDER_NAME,
                FF.CREATED_DATE,
                " . $this->db->IfNull('BP.FIRST_NAME', 'SU.USERNAME') . " AS CREATED_PERSON_NAME 
            FROM
                FVM_FOLDER FF
                $join
                LEFT JOIN UM_USER US ON US.USER_ID = FF.CREATED_USER_ID
                LEFT JOIN UM_SYSTEM_USER SU ON SU.USER_ID = US.SYSTEM_USER_ID
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID
            WHERE
                FF.IS_ACTIVE = 1 
                AND   LOWER(FOLDER_CODE) LIKE LOWER('%$q%')";
        } elseif ($type == 'name') {
            $selectList = "
            SELECT 
                FF.FOLDER_ID,
                FF.FOLDER_CODE,
                FF.FOLDER_NAME,
                FF.CREATED_DATE,
                " . $this->db->ifnull('BP.FIRST_NAME', 'SU.USERNAME') . " AS CREATED_PERSON_NAME 
            FROM 
                FVM_FOLDER FF
                $join
                LEFT JOIN UM_USER US ON US.USER_ID = FF.CREATED_USER_ID
                LEFT JOIN UM_SYSTEM_USER SU ON SU.USER_ID = US.SYSTEM_USER_ID
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID
            WHERE 
                FF.IS_ACTIVE = 1  
                AND  LOWER(FOLDER_NAME) LIKE LOWER('%$q%')";
            } elseif ($type == 'idselect') {
                $selectList = "
                SELECT 
                    FF.FOLDER_ID,
                    FF.FOLDER_CODE,
                    FF.FOLDER_NAME,
                    FF.CREATED_DATE,
                    " . $this->db->ifnull('BP.FIRST_NAME', 'SU.USERNAME') . " AS CREATED_PERSON_NAME 
                FROM 
                    FVM_FOLDER FF
                    $join
                    LEFT JOIN UM_USER US ON US.USER_ID = FF.CREATED_USER_ID
                    LEFT JOIN UM_SYSTEM_USER SU ON SU.USER_ID = US.SYSTEM_USER_ID
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID
                WHERE 
                    FF.IS_ACTIVE = 1  
                    AND  FF.FOLDER_ID = $q";
            }
        $sqlResult = $this->db->SelectLimit($selectList, 30, 0);

        if ($sqlResult && isset($sqlResult->_array)) {

            $data = array();
            $rowsData = $sqlResult->_array;

            foreach ($rowsData as $row) {
                $name = $row['FOLDER_ID'].'|#'.html_entity_decode($row['FOLDER_CODE'], ENT_QUOTES, 'UTF-8').'|#'.html_entity_decode($row['FOLDER_NAME'], ENT_QUOTES, 'UTF-8');
                array_push($data, $name);
            }

            return $data;
        }

        return array();

    }

}