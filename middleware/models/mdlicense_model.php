<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlicense_Model extends Model {
    
    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
    }

    public function importLicenseModel() {
        
        $uniqId = $this->db->GetOne("SELECT UNIQUE_ID FROM CUSTOMER_INFO");
        
        if ($uniqId) {
            
            $data = $this->ws->runSerializeDefaultSession('http://dev.interactive.mn:8088/', 'export_license', array('customerUniqueId' => $uniqId), 'erp-services-test/SoapWS');

            if ($data['status'] == 'success' && isset($data['result']) && !empty($data['result'])) {

                $dataImport = $this->ws->runResponse(self::$gfServiceAddress, 'import_license_all', array('license' => $this->ws->getValue($data['result'])));
                
                if ($dataImport['status'] == 'success' && isset($dataImport['result'])) {
                    $result = array('status' => 'success', 'message' => 'Successfully');
                } else {
                    $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($dataImport));
                }
                
            } elseif ($data['status'] == 'success' && isset($data['result']) && empty($data['result'])) {
                $result = array('status' => 'warning', 'message' => 'Боломжгүй');
            } else {
                $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
            
        } else {
            $result = array('status' => 'warning', 'message' => 'Unique ID тодорхойгүй байна.');
        }
        
        return $result;
    }

}
		