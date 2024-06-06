<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdmenu_model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }
    
    public function getKpiIndicatorModuleRowModel($moduleId) {
        
        $langCode = Lang::getCode();
        
        $row = $this->db->GetRow("
            SELECT 
                ID, 
                CODE, 
                FNC_TRANSLATE('$langCode', TRANSLATION_VALUE, 'NAME', NAME) AS NAME  
            FROM KPI_INDICATOR 
            WHERE ID = ".$this->db->Param(0), 
            array($moduleId)
        );
        
        return $row;
    }
    
    public function getKpiIndicatorMenuModel($isMobile = false) {
        
        $langCode = Lang::getCode();
        $idPh1 = $this->db->Param(0);
        $where = '';
        
        if ($isMobile) {
            $where = ' AND IS_MOBILE = 1';
        }
        
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                CODE, 
                NAME, 
                PARENTID, 
                KPITYPEID, 
                WEBURL, 
                ISSHOWCARD, 
                PHOTONAME, 
                ICON 
            FROM 
                (
                    SELECT 
                        ID, 
                        CODE, 
                        FNC_TRANSLATE('$langCode', TRANSLATION_VALUE, 'NAME', NAME) AS NAME, 
                        PARENT_ID AS PARENTID, 
                        KPI_TYPE_ID AS KPITYPEID, 
                        null AS WEBURL, 
                        null AS ISSHOWCARD, 
                        null AS PHOTONAME, 
                        null AS ICON 
                    FROM KPI_INDICATOR 
                    WHERE KPI_TYPE_ID = 1110 
                        AND DELETED_USER_ID IS NULL  
                        $where 
                    START WITH PARENT_ID = 164992043035310 
                    CONNECT BY PRIOR ID = PARENT_ID
                ) 
            WHERE ID IN (
                SELECT 
                    ID 
                FROM KPI_INDICATOR 
                WHERE KPI_TYPE_ID = 1110 
                START WITH ID IN (
                    SELECT 
                        ID 
                    FROM KPI_INDICATOR 
                    WHERE KPI_TYPE_ID = 1110 
                        AND CASE WHEN $idPh1 = (
                            SELECT 
                                USER_ID 
                            FROM UM_USER_ROLE 
                            WHERE USER_ID = $idPh1 
                                AND ROLE_ID = 1
                        ) THEN 1 WHEN $idPh1 = 1 THEN 1 ELSE 0 END = 1 
                          
                    UNION 
                    
                    SELECT 
                        DISTINCT INDICATOR_ID 
                    FROM UM_PERMISSION_KEY 
                    WHERE 
                        (
                            USER_ID = $idPh1 
                            OR ROLE_ID IN (
                                SELECT 
                                    ROLE_ID 
                                FROM UM_USER_ROLE 
                                WHERE USER_ID = $idPh1
                            )
                        )
                ) CONNECT BY NOCYCLE PRIOR PARENT_ID = ID
            )", array(Ue::sessionUserKeyId())
        );
        
        $data = Arr::changeKeyLower($data);
        
        return $data;
    }
    
    public function getKpiMenuListByParentIdCacheModel($moduleId, $isMobile = false) {
        
        $menuIdPh = $this->db->Param(0);
        $userIdPh = $this->db->Param(1);
        
        $langCode = Lang::getCode();
        $where    = '';
        
        if ($isMobile) {
            $where = ' AND IS_MOBILE = 1';
        }
        
        $data = $this->db->GetAll("
            SELECT 
                A.ID, 
                A.NAME AS MENU_NAME, 
                CASE WHEN A.PARENT_ID = $menuIdPh THEN NULL 
                ELSE A.PARENT_ID END AS PARENT_ID, 
                A.KPI_TYPE_ID,
                K.RELATED_INDICATOR_ID, 
                null AS WEBURL, 
                null AS URLTRG,
                NVL(MDM.META_DATA_CODE, NVL(MD.META_DATA_CODE, A.CODE)) AS CODE, 
                NVL(MDM.META_DATA_NAME, NVL(MD.META_DATA_NAME, A.NAME)) AS NAME, 
                NVL(MDM.META_DATA_ID, NVL(K.META_DATA_ID, K.RELATED_INDICATOR_ID)) AS ACTIONMETADATAID, 
                NVL(MDM.META_TYPE_ID, NVL(MD.META_TYPE_ID, 123456)) AS ACTIONMETATYPEID,
                NVL(GLM.GROUP_TYPE, GL.GROUP_TYPE) AS GROUPTYPE, 
                LOWER(KT.CODE) AS KPITYPECODE, 
                KI.KPI_TYPE_ID AS ACTIONKPITYPEID, 
                null AS BOOKMARKURL,
                K.CATEGORY_NAME AS CATEGORYNAME,
                K.ICON,
                LOWER(KM.METHOD_NAME) AS METHODNAME
            FROM (
                    SELECT 
                        ID, 
                        CODE, 
                        FNC_TRANSLATE('$langCode', TRANSLATION_VALUE, 'NAME', NAME) AS NAME,  
                        PARENT_ID, 
                        KPI_TYPE_ID, 
                        ORDER_NUMBER, 
                        CREATED_DATE 
                    FROM KPI_INDICATOR 
                    WHERE KPI_TYPE_ID = 1120 
                        AND DELETED_USER_ID IS NULL 
                        $where 
                    START WITH PARENT_ID = $menuIdPh
                        CONNECT BY PRIOR ID = PARENT_ID 
                ) A 
                LEFT JOIN KPI_MENU K ON A.ID = K.SRC_RECORD_ID 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = K.META_DATA_ID 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = K.META_DATA_ID 
                LEFT JOIN META_MENU_LINK ML ON ML.META_DATA_ID = K.META_DATA_ID 
                LEFT JOIN META_DATA MDM ON MDM.META_DATA_ID = ML.ACTION_META_DATA_ID 
                LEFT JOIN META_GROUP_LINK GLM ON GLM.META_DATA_ID = MDM.META_DATA_ID 
                LEFT JOIN KPI_INDICATOR KI ON KI.ID = K.RELATED_INDICATOR_ID 
                LEFT JOIN KPI_TYPE KT ON KT.ID = KI.KPI_TYPE_ID 
                LEFT JOIN KPI_METHOD KM ON KI.ID = KM.SRC_RECORD_ID
            WHERE A.KPI_TYPE_ID = 1120 
                AND A.ID IN (
                    SELECT 
                        ID
                    FROM KPI_INDICATOR
                    START WITH ID IN (
                        SELECT 
                            ID 
                        FROM KPI_INDICATOR
                        WHERE KPI_TYPE_ID = 1120 
                            AND 
                            CASE WHEN $userIdPh = (SELECT USER_ID FROM UM_USER_ROLE WHERE USER_ID = $userIdPh AND ROLE_ID = 1)
                                THEN 1
                            WHEN $userIdPh = 1
                                THEN 1
                            ELSE 0
                            END = 1

                        UNION 

                        SELECT  
                            INDICATOR_ID 
                        FROM UM_PERMISSION_KEY 
                        WHERE 
                            (USER_ID = $userIdPh 
                            OR
                            ROLE_ID IN (
                                    SELECT ROLE_ID FROM UM_USER_ROLE WHERE USER_ID = $userIdPh 
                                )
                            ) 
                        GROUP BY INDICATOR_ID     
                    )
                    CONNECT BY NOCYCLE PRIOR PARENT_ID = ID
                ) 
            ORDER BY A.ORDER_NUMBER ASC, A.CREATED_DATE ASC", 
                
            array($moduleId, Ue::sessionUserKeyId())
        );
        
        $data = Arr::changeKeyLower($data);
        
        return $data;
    }
    
    public function topKpiMenuModuleRenderModel($moduleId, $moduleName, $menuData, $depth = 0, $parent = 0) {
        
        $menu = array();
        
        if ($depth != 0) {
                
            $menu[] = '<ul class="dropdown-menu">';

        } elseif ($depth == 0) {               

            $menu[] = '<ul class="navbar-nav d-flex align-content-around flex-wrap" data-no-scroll="true">';
        }
        
        foreach ($menuData as $k => $row) {
            
            if (!array_find_val($menuData, 'id', $row['parent_id'])) {
                $row['parent_id'] = 0;
            }
            
            if ($row['parent_id'] == $parent) { 
                
                $isChild = array_find_val($menuData, 'parent_id', $row['id']);
                $liClass = $attr = $titleClass = '';
                
                if ($isChild) {
                    
                    if ($depth != 0) {
                        $attr = ' class="dropdown-toggle navbar-nav-link" data-toggle="dropdown"';
                        $liClass .= ' dropdown-submenu';
                    } else {
                        $attr = ' class="dropdown-toggle navbar-nav-link" data-toggle="dropdown"';
                        $liClass .= ' dropdown';
                    }
                    
                } else {
                    
                    if ($depth == 0) {
                        $attr = ' class="navbar-nav-link"';
                    } else {
                        $attr = ' class="dropdown-item"';
                    }
                    
                    $rowMeta = Mdmeta::menuServiceAnchorByTab($row, $moduleId, $row['id'], $moduleName);
                    
                    $attr .= ' onclick="' . $rowMeta['linkOnClick'] . '"';
                }
                
                if ($depth == 0) {
                    $titleClass = ' class="title"';
                }
                
                $menu[] = '<li class="nav-item' . $liClass . '">';
                
                    $menu[] = '<a href="javascript:;" data-meta-data-id="' . $row['id'] . '" data-pfgotometa="1" data-default-open="' . issetParam($row['isdefaultopen']) . '"'.$attr.'>';
                        $menu[] = '<span'.$titleClass.'>' . Lang::line($row['menu_name']) . '</span>';
                    $menu[] = '</a>';

                    if ($isChild) {
                        $menu[] = $this->topKpiMenuModuleRenderModel($moduleId, $moduleName, $menuData, $depth + 1, $row['id']);
                    }
                        
                $menu[] = '</li>';
            }
        }

        $menu[] = '</ul>';
        
        return implode('', $menu);
    }
    
    public function getOpenMenuIdModel($moduleId) {
        
        try {
            
            $idPh1 = $this->db->Param(0);
            $idPh2 = $this->db->Param(1);
            
            $openMenuId = $this->db->GetOne("
                SELECT
                    C.TOP_MENU_ID 
                FROM UM_MENU_CONFIG C
                    LEFT JOIN UM_USER_ROLE R ON C.ROLE_ID = R.ROLE_ID 
                WHERE (R.USER_ID = $idPh1 OR C.USER_ID = $idPh1) 
                    AND C.MODULE_MENU_ID = $idPh2  
                ORDER BY C.ID DESC, C.USER_ID ASC", 
                array(Ue::sessionUserKeyId(), $moduleId)
            );
            
        } catch (Exception $ex) {
            $openMenuId = null;
        }
        
        return $openMenuId;
    }

}