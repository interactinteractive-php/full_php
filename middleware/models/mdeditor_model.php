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

}