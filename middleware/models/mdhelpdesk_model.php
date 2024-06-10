<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mdhelpdesk_Model extends Model {

    public function __construct() {
        parent::__construct();
    } 
    
    public function setCloudHelpSaveModel() {
        try {
            
            $helpUrl = Input::post('helpUrl');
            parse_str(parse_url($helpUrl, PHP_URL_QUERY), $urlArr);
            
            if (isset($urlArr['filterid']) && is_numeric($urlArr['filterid'])) {
                
                $fromType     = Input::post('fromType');
                $contentId    = Input::numeric('contentId');
                $sourceId     = Input::numeric('sourceId');
                $setContentId = $urlArr['filterid'];
                
                if ($fromType == 'meta_process' || $fromType == 'meta_dv') {
                    
                    $this->db->AutoExecute('META_DATA', ['HELP_CONTENT_ID' => $setContentId], 'UPDATE', "META_DATA_ID = $sourceId");
                    
                    if ($fromType == 'meta_process') {
                        (new Mdmeta())->bpParamsClearCache($sourceId, null);
                    } elseif ($fromType == 'meta_dv') {
                        (new Mdmeta())->dvCacheClearByMetaId($sourceId);
                    }
                    
                } elseif ($fromType == 'mv_method' || $fromType == 'mv_list') {
                    
                    $this->db->AutoExecute('KPI_INDICATOR', ['CONTENT_ID' => $setContentId], 'UPDATE', "ID = $sourceId");
                    (new Mdmeta())->clearCacheKpiTemplateById($sourceId);
                }
                
                $result = ['status' => 'success', 'setContentId' => $setContentId, 'message' => 'Successfully'];
                
            } else {
                throw new Exception('Invalid filterid parameter!');
            }
            
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        
        return $result;
    }

}