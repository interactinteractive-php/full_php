<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdpermission_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getFilterCriteriaModel($id) {
        return $this->db->GetRow("SELECT ID, CODE, NAME, DESCRIPTION, META_DATA_ID, CRITERIA_STRING FROM UM_CRITERIA WHERE ID = " . $this->db->Param(0), array($id));
    }

    public function filterCriteriaSaveModel($param) {
        return $this->db->AutoExecute('UM_CRITERIA', $param);
    }

    public function filterCriteriaUpdateModel($param, $id) {
        return $this->db->AutoExecute('UM_CRITERIA', $param, 'UPDATE', 'ID = ' . $id);
    }

    public function getCriteriaListByDataviewModel($id) {
        
        $roleIdPh = $this->db->Param(0);
        $idPh = $this->db->Param(1);
        
        if (Input::post('roleOrUser') === 'user') {
            
            return $this->db->GetAll(
                    "SELECT DISTINCT
                        AA.ID, 
                        AA.CODE, 
                        AA.NAME, 
                        AA.DESCRIPTION, 
                        AA.META_DATA_ID, 
                        AA.CRITERIA_STRING,
                        (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ELSE NVL(BB.CRITERIA_ID, 0) END) AS CHECKED_VAL,
                        (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ELSE BB.BATCH_NUMBER END) AS BATCH_NUMBER
                    FROM UM_CRITERIA AA
                        LEFT JOIN UM_META_PERM_CRITERIA BB ON BB.CRITERIA_ID = AA.ID
                        LEFT JOIN UM_META_PERMISSION CC ON CC.PERMISSION_ID = BB.PERMISSION_ID AND CC.USER_ID = $roleIdPh
                    WHERE AA.META_DATA_ID = $idPh  
                    ORDER BY AA.NAME", 
                array($id, Input::post('roleId'))    
            );
        }
        
        return $this->db->GetAll(
                "SELECT DISTINCT
                    AA.ID, 
                    AA.CODE, 
                    AA.NAME, 
                    AA.DESCRIPTION, 
                    AA.META_DATA_ID, 
                    AA.CRITERIA_STRING,
                    (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ELSE NVL(BB.CRITERIA_ID, 0) END) AS CHECKED_VAL,
                    (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ELSE BB.BATCH_NUMBER END) AS BATCH_NUMBER
                FROM UM_CRITERIA AA
                    LEFT JOIN UM_META_PERM_CRITERIA BB ON BB.CRITERIA_ID = AA.ID
                    LEFT JOIN UM_META_PERMISSION CC ON CC.PERMISSION_ID = BB.PERMISSION_ID AND CC.ROLE_ID = $roleIdPh 
                WHERE AA.META_DATA_ID = $idPh 
                ORDER BY AA.NAME", 
            array($id, Input::post('roleId'))        
        );
    }        

    public function umMetaPermCriteriaCreateModel($param) {
        return $this->db->AutoExecute('UM_META_PERM_CRITERIA', $param);
    }        

    public function checkPermCriteriaModel($id, $cid) {
        
        $result = $this->db->GetRow("
            SELECT 
                ID 
            FROM UM_META_PERM_CRITERIA 
            WHERE PERMISSION_ID = " . $this->db->Param(0) . " 
                AND CRITERIA_ID = " . $this->db->Param(1), 
            array($id, $cid)
        );

        if ($result) {
            return $result;
        }
        
        return false;
    }        

    public function umMetaPermCriteriaDeleteModel($id) {
        return $this->db->Execute("DELETE FROM UM_META_PERM_CRITERIA WHERE ID = " . $this->db->Param(0), array($id));
    }        

    public function checkPermUpdateModel($param, $id) {
        return $this->db->AutoExecute('UM_META_PERM_CRITERIA', $param, 'UPDATE', 'ID = ' . $id);
    }        

}
