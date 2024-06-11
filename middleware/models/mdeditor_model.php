<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Mdeditor_model extends Model {
    
    private $fieldMetaDataId = '200101010000017';
    private $groupdMetaDataId = '200101010000016';

    public function __construct() {
        parent::__construct();
    }

    public function getMetaList($folderId) {
        $folderId = Security::sanitize($folderId);
        $sql = "SELECT MD.META_DATA_ID, MD.META_DATA_NAME FROM META_DATA_FOLDER_MAP FM INNER JOIN META_DATA MD ON FM.META_DATA_ID = MD.META_DATA_ID WHERE FM.FOLDER_ID = $folderId";

        return $this->db->GetAll($sql);
    }

    // select field meta
    public function getTextMetas($metaDataGroupId) {
        $metaDataGroupId = Security::sanitize($metaDataGroupId);
        $sql = "SELECT TRG.META_DATA_ID, TRG.META_DATA_NAME 
                FROM META_META_MAP M 
                INNER JOIN META_DATA TRG ON M.TRG_META_DATA_ID = TRG.META_DATA_ID 
                WHERE META_TYPE_ID = $this->fieldMetaDataId 
                AND IS_ACTIVE = 1 
                AND M.SRC_META_DATA_ID = $metaDataGroupId
                ORDER BY META_DATA_NAME ASC";
        return $this->db->GetAll($sql);
    }

    // select meta group
    public function getGroupList() {
        $sql = "select META_DATA_ID, META_DATA_NAME from meta_data where META_TYPE_ID = $this->groupdMetaDataId and is_active = 1 ORDER BY META_DATA_NAME ASC";

        return $this->db->GetAll($sql);
    }
    
    public function getProcessMetaList() {
        $sql = "SELECT META_DATA_ID, META_DATA_NAME, META_DATA_CODE FROM META_DATA WHERE META_TYPE_ID = ".$this->processMetaTypeId." ORDER BY META_DATA_NAME ASC";
        return $this->db->GetAll($sql);
    }
    
    public function getDbtColumnsModel() {
        
        try {
            
            $dbs = Input::post('dbs');
            $dbs = base64_decode($dbs);
            $dbs = rtrim($dbs, ';');
            
            if (DB_DRIVER == 'oci8') {
                
                $result = $this->db->Execute("SELECT * FROM ($dbs) WHERE 1 = 0");
                $fieldObjs = Arr::objectToArray($result->_fieldobjs);

            } elseif (DB_DRIVER == 'postgres9') {

                $rs = $this->db->Execute("SELECT * FROM ($dbs) WHERE 1 = 0");
                $fieldObjects = $rs->fieldTypesArray();
                
                $this->load->model('mdupgrade', 'middleware/models/');

                $fieldObjs = $this->model->postgreArrayColumnsConvert($fieldObjects);
            }
            
            $fields = [];
            
            foreach ($fieldObjs as $field) {
                $fields[$field['name']] = $field['type'];
            }
            
            $result = ['status' => 'success', 'columns' => $fields];
            
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->msg];
        }
        
        return $result;
    }
    
    public function getDbtDataGridModel() {
        
        try {
            
            $dbs = Input::post('dbs');
            $dbs = base64_decode($dbs);
            $dbs = rtrim($dbs, ';');
            
            $page = Input::post('page', 1);
            $rows = Input::post('rows', 10);
            $offset = ($page - 1) * $rows;
            $subCondition = null;
            $result = ['status' => 'success'];

            if (Input::postCheck('filterRules')) {
                $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $value = Input::param(Str::lower($rule['value']));

                    if (!empty($value)) {
                        $subCondition .= " AND (LOWER($field) LIKE '%$value%')";
                    }
                }
            }

            $selectCount = "SELECT COUNT(1) AS ROW_COUNT FROM ($dbs) WHERE 1 = 1 $subCondition";
            $selectList = "SELECT * FROM ($dbs) WHERE 1 = 1 $subCondition";

            if (Input::isEmpty('sort') == false && Input::isEmpty('order') == false) {
                
                $sortField = Input::post('sort');
                $sortOrder = Input::post('order');
                
                $selectList .= " ORDER BY $sortField $sortOrder";
            }

            $rowCount = $this->db->GetRow($selectCount);

            $result['total'] = $rowCount['ROW_COUNT'];
            $result['rows'] = [];

            $rs = $this->db->SelectLimit($selectList, $rows, $offset);

            if (isset($rs->_array)) {
                $result['rows'] = $rs->_array;
            }
        
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'total' => 0, 'rows' => []];
        }
        
        return $result;
    }

}