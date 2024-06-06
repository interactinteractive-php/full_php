<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Appmenu_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getMenuListModel($menuCode = null, $isDuplicateByTag = false) {
        
        $cache = phpFastCache();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $isDuplicateByTag = (int) $isDuplicateByTag;
        $menuCacheName = 'appmenu_' . $sessionUserKeyId.(is_null($menuCode) ? '' : '_'.$menuCode).'_'.$isDuplicateByTag;
        
        $data = $cache->get($menuCacheName);

        if ($data == null) {
            
            $param = array(
                'userId'       => $sessionUserKeyId,
                'menuCode'     => is_null($menuCode) ? 'ERP_MENU' : $menuCode, 
                'checkLicense' => 0
            );
            
            if ($isDuplicateByTag) {
                $param['isDuplicateByTag'] = true;
            }

            $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'one_level_menu', $param);
            
            if ($data['status'] == 'success') {
                
                if (isset($data['result']) && is_array($data['result'])) {
                    
                    $result = $data['result'];
                    $count = count($result);

                    if ($count < 11) {
                        foreach ($result as $k => $row) {
                            if ($row['code'] == 'ERP_MENU_MOBILE') {
                                unset($result[$k]);
                                $count--;
                                break;
                            }
                        }
                        $result = array_values($result);
                    }

                    $data = array(
                        'status'           => 'success', 
                        'menuData'         => $result, 
                        'count'            => $count, 
                        'menuId'           => $result[0]['metadataid'],
                        'actionmetadataid' => issetParam($result[0]['actionmetadataid']),
                        'actionmetatypeid' => issetParam($result[0]['actionmetatypeid']), 
                        'weburl'           => issetParam($result[0]['weburl'])
                    );

                    $cache->set($menuCacheName, $data, 43200);

                    return $data;
                    
                } else {
                    return array('status' => 'error', 'message' => 'Эрхийн тохиргоо хийгдээгүй тул та систем хариуцсан ажилтанд хандана уу!');
                }
                
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
            
        } else {
            return $data;
        }
    }
    
    public function getChildMenuListModel($menuMetaDataId) {
        $param = array(
            'userId' => Ue::sessionUserKeyId(),
            'menuId' => $menuMetaDataId
        );
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'child_menus', $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            return array('status' => 'success', 'menuData' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }    
    
    public function startupUser() {
        $get = $this->db->GetRow("SELECT * FROM UM_USER WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserKeyId()));
        return $get;
    }

    public function getMetaDataModel($metaDataId) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                MT.META_TYPE_CODE, 
                MD.DESCRIPTION, 
                MD.ADDON_XML_DATA, 
                MD.COPY_COUNT 
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
            WHERE MD.META_DATA_ID = $metaDataIdPh", $bindVars);

        return $row;
    }    
    
    public function getResetPasswordUser() {
        
        $day = Config::getFromCache('ChangePasswordDate');
        $userHash = Config::getFromCache('userPasswordHash');
        
        if ($day || $userHash) {
            
            $userId = Ue::sessionUserId();
            
            if ($userHash) {
                
                $get = $this->db->GetRow("
                    SELECT 
                        USER_ID, 
                        USERNAME, 
                        null AS PASSWORD_RESET_DATE 
                    FROM UM_SYSTEM_USER 
                    WHERE USER_ID = ".$this->db->Param(0)." 
                        AND PASSWORD_HASH = ".$this->db->Param(1), 
                array($userId, $userHash));
                
                if ($get) {
                    return $get;
                }
            }
            
            if ($day) {
                
                $get = $this->db->GetRow("
                    SELECT 
                        USER_ID, 
                        USERNAME, 
                        PASSWORD_RESET_DATE 
                    FROM UM_SYSTEM_USER 
                    WHERE USER_ID = ".$this->db->Param(0)." 
                        AND (PASSWORD_RESET_DATE IS NULL OR (PASSWORD_RESET_DATE + ".$this->db->Param(1).") < SYSDATE)", 
                array($userId, $day));     
                
                if ($get) {
                    return $get;
                }
            }
        }
        
        return false;
    }
    
    public function getMetaVerseModuleListModel() {
        
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $idPh = $this->db->Param(0);
        
        $data = $this->db->GetAll("
            SELECT 
                K.*  
            FROM (
                SELECT 
                    KI.ID, 
                    KI.ORDER_NUMBER, 
                    KCC.ORDER_NUMBER AS CATEGORY_ORDER,
                    PP.META_DATA_ID, 
                    MM.ACTION_META_DATA_ID, 
                    MD.META_TYPE_ID AS ACTION_META_TYPE_ID, 
                    PP.BPA_CODE AS CODE, 
                    PP.BPA_NAME AS NAME, 
                    PP.DESCRIPTION, 
                    PP.CREATED_DATE, 
                    PP.CREATED_USER_ID,
                    PP.PROFILE_PHOTO,
                    PP.WFM_STATUS_ID,
                    PP.WFM_DESCRIPTION,
                    PP.PRODUCT_TYPE_ID,
                    PP.PRODUCT_CATEGORY_ID,
                    PP.MANAGER_ID,
                    PP.DEVELOPER_TEAM_ID,
                    KC.CATEGORY_ID,
                    KCC.NAME AS CATEGORY_NAME, 
                    'Онцлох' AS GROUP_NAME,
                    PP.MENU_INDICATOR_ID,
                    PP.LANDING_PAGE_INDICATOR_ID,
                    (
                        SELECT 
                            COUNT(1) 
                        FROM KPI_INDICATOR_INDICATOR_MAP 
                        WHERE SRC_INDICATOR_ID = KI.ID 
                            AND SEMANTIC_TYPE_ID IN (44, 79) 
                    ) AS IS_RELATION 
                FROM KPI_INDICATOR KI 
                    INNER JOIN PLM_PRODUCT PP ON PP.SRC_RECORD_ID = KI.ID 
                    LEFT JOIN KPI_INDICATOR_CATEGORY KC ON KC.INDICATOR_ID = KI.ID 
                    LEFT JOIN KPI_INDICATOR KCC ON KCC.ID = KC.CATEGORY_ID 
                    LEFT JOIN META_MENU_LINK MM ON MM.META_DATA_ID = PP.META_DATA_ID 
                    LEFT JOIN META_DATA MD ON MD.META_DATA_ID = MM.ACTION_META_DATA_ID 
                WHERE KI.KPI_TYPE_ID = 16818054066154 
                    AND PP.BPA_NAME IS NOT NULL 
                    AND KI.ID IN (
                        SELECT 
                            ID
                        FROM KPI_INDICATOR
                        START WITH ID IN (
                            SELECT 
                                ID 
                            FROM KPI_INDICATOR
                            WHERE KPI_TYPE_ID = 16818054066154 
                                AND 
                                CASE WHEN $idPh = (SELECT MAX(USER_ID) FROM UM_USER_ROLE WHERE USER_ID = $idPh AND ROLE_ID = 1)
                                    THEN 1
                                WHEN $idPh = 1
                                    THEN 1
                                ELSE 0
                                END = 1

                            UNION 

                            SELECT  
                                INDICATOR_ID 
                            FROM UM_PERMISSION_KEY 
                            WHERE (
                                USER_ID = $idPh
                                    OR
                                ROLE_ID IN (SELECT ROLE_ID FROM UM_USER_ROLE WHERE USER_ID = $idPh)
                            ) 
                            GROUP BY INDICATOR_ID     
                        )
                        CONNECT BY NOCYCLE PRIOR PARENT_ID = ID
                    ) 
            ) K 
            WHERE K.MENU_INDICATOR_ID IS NOT NULL 
                OR K.ACTION_META_DATA_ID IS NOT NULL 
                OR K.IS_RELATION > 0 
            ORDER BY K.CATEGORY_ORDER, K.ORDER_NUMBER", [$sessionUserKeyId]);
        
        return $data;
    }     

}