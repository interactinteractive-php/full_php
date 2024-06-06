<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
 
class Mdmeta_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    public function getRefStructureIdByMidModel($metaDataId) {
        return $this->db->GetOne("SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }

    public function getMetaDataIdByCodeModel($code) {
        return $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE LOWER(META_DATA_CODE) = ".$this->db->Param(0), array(Str::lower($code)));
    }

    public function getMetaDataCodeByIdModel($id) {
        return $this->db->GetOne("SELECT META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($id));
    }

    public function getMenuLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_NAME, 
                LOWER(ML.MENU_POSITION) AS MENU_POSITION, 
                LOWER(ML.MENU_ALIGN) AS MENU_ALIGN, 
                LOWER(ML.MENU_THEME) AS MENU_THEME,
                ML.VIEW_META_DATA_ID 
            FROM META_DATA MD 
                INNER JOIN META_MENU_LINK ML ON ML.META_DATA_ID = MD.META_DATA_ID 
            WHERE ML.META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId) 
        );

        return $row;
    }

    public function getMenuOnlyMetasModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID AS MENU_META_DATA_ID,
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                ML.ACTION_META_DATA_ID AS META_DATA_ID, 
                ML.WEB_URL, 
                ML.URL_TARGET, 
                MLM.META_TYPE_ID, 
                BL.BOOKMARK_URL, 
                BL.TARGET AS BOOKMARK_TARGET, 
                RL.REPORT_MODEL_ID, 
                GL.GROUP_TYPE, 
                ML.ICON_NAME 
            FROM META_META_MAP MM 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MM.TRG_META_DATA_ID 
                LEFT JOIN META_MENU_LINK ML ON ML.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_DATA MLM ON MLM.META_DATA_ID = ML.ACTION_META_DATA_ID  
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MLM.META_DATA_ID 
                LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MLM.META_DATA_ID 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MLM.META_DATA_ID 
            WHERE MM.SRC_META_DATA_ID = $metaDataId 
                AND MD.META_TYPE_ID IN (" . implode(",", Mdmeta::$onlyMenuTypeIds) . ") 
                AND MD.IS_ACTIVE = 1 
            ORDER BY MM.ORDER_NUM ASC");

        return $data;
    }

    public function horizontalMenuRenderModel($metaDataId, $menuRow, $depth = 0) {

        $menu = "";
        $metaDatas = self::getMenuOnlyMetasModel($metaDataId);

        if ($metaDatas) {
            if ($depth == 0) {
                $menu .= '<div class="navbar navbar-default meta-horizontal-menu shadow ' . $menuRow['MENU_THEME'] . ' ' . $menuRow['MENU_ALIGN'] . '" role="navigation">';
                $menu .= '<div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                          </div>';
                $menu .= '<div class="collapse navbar-collapse navbar-ex1-collapse">';
                $ulClass = ' class="nav navbar-nav"';
            } else {
                $ulClass = ' class="dropdown-menu"';
            }
            $menu .= '<ul' . $ulClass . '>';

            foreach ($metaDatas as $row) {
                
                $childMenu = '';
                $liClass = '';
                $liIcon = '';

                $childMenu = self::horizontalMenuRenderModel($row['MENU_META_DATA_ID'], $menuRow, $depth + 1);

                if ($childMenu != "") {
                    if ($depth == 0) {
                        $liClass = ' class="dropdown"';
                    } else if ($depth >= 1) {
                        $liClass = ' class="dropdown dropdown-submenu"';
                    }

                    $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                    $menuAnchor .= $row['META_DATA_NAME'] . $liIcon;
                    $menuAnchor .= '</a>';
                } else {
                    $rowMeta = Mdmeta::menuAnchor($row);

                    $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                    $menuAnchor .= $row['META_DATA_NAME'];
                    $menuAnchor .= '</a>';
                }

                $menu .= '<li' . $liClass . '>';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            $menu .= '</ul>';
            if ($depth == 0) {
                $menu .= '</div>';
                $menu .= '</div>';
            }
        }

        return $menu;
    }

    public function verticalMenuRenderModel($metaDataId, $menuRow, $depth = 0) {

        $menu = '';
        $metaDatas = self::getMenuOnlyMetasModel($metaDataId);

        if ($metaDatas) {
            if ($depth == 0) {
                $menu .= '<div class="navbar navbar-default meta-vertical-menu shadow ' . $menuRow['MENU_THEME'] . ' ' . $menuRow['MENU_ALIGN'] . '" role="navigation">';
                $menu .= '<div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                          </div>';
                $menu .= '<div class="collapse navbar-collapse navbar-ex1-collapse">';
                $ulClass = ' class="nav navbar-nav"';
            } else {
                $ulClass = ' class="dropdown-menu"';
            }
            $menu .= '<ul' . $ulClass . '>';

            foreach ($metaDatas as $row) {
                $liClass = "";
                $liIcon = "";

                $childMenu = self::verticalMenuRenderModel($row['MENU_META_DATA_ID'], $menuRow, $depth + 1);

                if ($childMenu != "") {
                    if ($depth == 0) {
                        $liClass = ' class="dropdown"';
                        if (!empty($row['ICON_NAME'])) {
                            $liIcon = '<i class="fa ' . $row['ICON_NAME'] . '"></i> ';
                        }
                    } elseif ($depth >= 1) {
                        $liClass = ' class="dropdown dropdown-submenu"';
                        $liIcon = '<i class="fa fa-caret-right"></i> ';
                    }

                    $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                    $menuAnchor .= $liIcon . $row['META_DATA_NAME'];
                    $menuAnchor .= '</a>';
                } else {
                    $rowMeta = Mdmeta::menuAnchor($row);

                    if ($depth == 0 && !empty($row['ICON_NAME'])) {
                        $liIcon = '<i class="fa ' . $row['ICON_NAME'] . '"></i> ';
                    }

                    $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                    $menuAnchor .= $liIcon . $row['META_DATA_NAME'];
                    $menuAnchor .= '</a>';
                }

                $menu .= '<li' . $liClass . '>';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            $menu .= '</ul>';
            if ($depth == 0) {
                $menu .= '</div>';
                $menu .= '</div>';
            }
        }

        return $menu;
    }

    public function mainLeftMenuRenderModel($metaDataId, $depth = 0, $isChild, $menuOpen, $urlId) {

        if (!$metaDataId) {
            return;
        }

        $menu = "";
        $metaDatas = self::getMenuOnlyMetasModel($metaDataId);

        if ($metaDatas) {

            if ($isChild || $depth != 0) {
                $menuOpenStyle = "";
                if ($menuOpen == 'open_all') {
                    $menuOpenStyle = ' style="display: block;"';
                }
                $menu .= '<ul class="sub-menu"' . $menuOpenStyle . '>';
            }

            foreach ($metaDatas as $row) {
                $childMenu = self::mainLeftMenuRenderModel($row['MENU_META_DATA_ID'], $depth + 1, $isChild, $menuOpen, $urlId);

                if ($isChild || $depth != 0) {
                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" >';
                        $menuAnchor .= '<i class="fa fa-caret-right"></i> <span>' . $row['META_DATA_NAME'] . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= '<i class="fa fa-caret-right"></i> <span>' . $row['META_DATA_NAME'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = '';
                    if (!empty($row['ICON_NAME'])) {
                        $icon = '<i class="fa ' . $row['ICON_NAME'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['META_DATA_NAME'] . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" class="navban-link-item" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['META_DATA_NAME'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = '';
                if ($row['META_DATA_ID'] === $urlId) {
                    $liClass = ' class="active"';
                }

                $menu .= '<li' . $liClass . '>';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            if ($isChild || $depth != 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function topMenuRenderModel($metaDataId, $depth = 0, $isChild, $menuOpen, $urlId) {

        if (!$metaDataId) {
            return;
        }

        $menu = '';
        $metaDatas = self::getMenuOnlyMetasModel($metaDataId);

        if ($metaDatas) {

            if ($depth == 0) {
                $menu .= '<ul class="nav navbar-nav">';
            } elseif ($depth == 1) {
                $menu .= '<ul class="dropdown-menu">';
            } else {
                $menu .= '<ul class="dropdown-menu">';
            }

            foreach ($metaDatas as $row) {
                $childMenu = self::topMenuRenderModel($row['MENU_META_DATA_ID'], $depth + 1, $isChild, $menuOpen, $urlId);

                if ($isChild || $depth != 0) {
                    $icon = '';
                    if (!empty($row['ICON_NAME'])) {
                        $icon = '<i class="fa ' . $row['ICON_NAME'] . '"></i> ';
                    }
                    if ($childMenu != '') {
                        if ($depth == 0) {
                            $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                            $menuAnchor .= $icon . '<span>' . $row['META_DATA_NAME'] . '</span>';
                        } else {
                            $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                            $menuAnchor .= $icon . '<span>' . $row['META_DATA_NAME'] . '</span>';
                        }

                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span>' . $row['META_DATA_NAME'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = '';
                    if (!empty($row['ICON_NAME'])) {
                        $icon = '<i class="fa ' . $row['ICON_NAME'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['META_DATA_NAME'] . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['META_DATA_NAME'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = '';
                if ($depth == 0) {
                    $liClass = ' class="dropdown"';
                } else {
                    if ($childMenu != '') {
                        $liClass = ' class="dropdown-submenu"';
                    }
                }

                $menu .= '<li' . $liClass . '>';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            if ($isChild || $depth != 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function topMenuRenderByDataModel($menuData, $depth, $isChild, $menuOpen, $urlId) {
        $menu = '';

        if (is_array($menuData)) {

            if ($depth == 0) {
                $menu .= '<ul class="nav navbar-nav">';
            } elseif ($depth == 1) {
                $menu .= '<ul class="dropdown-menu">';
            } else {
                $menu .= '<ul class="dropdown-menu">';
            }

            foreach ($menuData as $row) {
                
                $childMenu = '';

                if (isset($row['child'])) {
                    $childMenu = self::topMenuRenderByDataModel($row['child'], $depth + 1, $isChild, $menuOpen, $urlId);
                }

                if ($isChild || $depth != 0) {
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }
                    if ($childMenu != "") {
                        if ($depth == 0) {
                            $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                            $menuAnchor .= $icon . '<span>' . $row['name'] . '</span>';
                        } else {
                            $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                            $menuAnchor .= $icon . '<span>' . $row['name'] . '</span>';
                        }
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span>' . $row['name'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = "";
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }

                    if ($childMenu != "") {
                        $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['name'] . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['name'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = "";
                if ($depth == 0) {
                    $liClass = ' class="dropdown"';
                } else {
                    if ($childMenu != "") {
                        $liClass = ' class="dropdown-submenu"';
                    }
                }

                $menu .= '<li' . $liClass . '>';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            if ($isChild || $depth != 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function topChildMenuRenderByDataModel($menuData, $depth, $isChild, $menuOpen, $urlId) {
        (String) $menu = "";

        if (is_array($menuData)) {

            $menu .= '<ul class="dropdown-menu">';

            foreach ($menuData as $row) {
                (String) $childMenu = "";

                if (isset($row['child'])) {
                    $childMenu = self::topChildMenuRenderByDataModel($row['child'], $depth + 1, $isChild, $menuOpen, $urlId);
                }

                if ($isChild || $depth != 0) {
                    $icon = "";
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }
                    if ($childMenu != "") {
                        $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                        $menuAnchor .= $icon . '<span>' . $row['name'] . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span>' . $row['name'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                } else {
                    
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['name'] . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . $row['name'] . '</span>';
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = '';
                if ($childMenu != '') {
                    $liClass = ' class="dropdown-submenu"';
                }

                $menu .= '<li' . $liClass . '>';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            if ($isChild || $depth != 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function topMetaMenuRenderByDataModel($menuData, $moduleId, $depth, $isChild, $menuOpen, $urlId, $rowId = 0) {
        $menu = '';

        if (is_array($menuData)) {

            if ($depth != 0) {
                $ulClass = '';
                $menu .= '<ul class="dropdown-menu' . $ulClass . '">';
            }

            foreach ($menuData as $k => $row) {
                
                $childMenu = '';

                if (isset($row['child'])) {
                    $childMenu = self::topMetaMenuRenderByDataModel($row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $urlId, $k);
                }
                
                $countMetaData = '';
                
                if (isset($row['countmetadataid']) && $row['countmetadataid'] != '') {
                    $leftMenuCount = self::getLeftMenuCountModel($row['countmetadataid']);
                    if ($leftMenuCount != '') {
                        $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '">' . $leftMenuCount . '</span>';
                    }
                }

                if ($isChild || $depth != 0) {
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa '.$row['icon'].'"></i> ';
                    }
                    if ($childMenu != '') {
                        if ($depth == 0) {
                            $menuAnchor = '<a href="javascript:;" class="dropdown-toggle navbar-nav-link" data-toggle="dropdown">';
                            $menuAnchor .= $icon . '<span>' . Lang::line($row['name']) . '</span>';
                        } else {
                            $menuAnchor = '<a href="javascript:;" class="dropdown-toggle navbar-nav-link" data-toggle="dropdown">';
                            $menuAnchor .= $icon . '<span>' . Lang::line($row['name']) . '</span>';
                        }
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row, $moduleId);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" class="navbar-nav-link" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span>' . Lang::line($row['name']) . '</span>'.$countMetaData;
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="dropdown-toggle navbar-nav-link" data-toggle="dropdown">';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row, $moduleId);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" class="navbar-nav-link" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>'.$countMetaData;
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = '';
              
                
                if ($depth == 0) {
                    $liClass = ' class="nav-item"';
                }
                if ($childMenu != '') {
                    $liClass = ($depth == 0) ? ' class="nav-item dropdown"' : ' class="dropdown-item dropdown-submenu"';
                }

                $menu .= '<li' . $liClass . ' data-metadataid="'.$row['metadataid'].'">';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            if ($isChild || $depth != 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function topMetaMenuRenderByLimitDataModel($menuData, $moduleId, $depth, $isChild, $menuOpen, $urlId, $rowId = 0) {
        $menu = '';

//        if (is_array($menuData)) {
//
//            foreach ($menuData as $k => $row) {
//
//                $rowMeta = Mdmeta::menuServiceAnchor($row, $moduleId);
//                
//                if ($rowMeta['linkHref'] === 'mdmetadata/system') {
//                    
//                    $menu .= '<li class="top-menu-link nav-item">';
//                        $menu .= '<a href="mdmetadata/system" class="tooltips navbar-nav-link" data-placement="top" data-close-others="true" aria-expanded="false" data-original-title="EISTUDIO" title="EISTUDIO">';
//                            $menu .= '<i class="fas fa-cogs font-size-15"></i>';
//                        $menu .= '</a>';
//                    $menu .= '</li>';
//                    
//                } elseif ($rowMeta['linkHref'] === 'mdhelpdesk/login') {
//                    
//                    $menu .= '<li class="top-menu-link nav-item">';
//                        $menu .= '<a href="mdhelpdesk/login" target="_blank" class="tooltips navbar-nav-link" data-placement="top" data-close-others="true" aria-expanded="false" title="HELPDESK">';
//                            $menu .= '<i class="fas fa-life-ring font-size-15"></i>';
//                        $menu .= '</a>';
//                    $menu .= '</li>';  
//                    
//                } elseif ($rowMeta['linkHref'] === 'mdhelpdesk/ssoLogin') {
//                    
//                    $menu .= '<li class="top-menu-link nav-item">';
//                        $menu .= '<a href="javascript:;" class="tooltips navbar-nav-link" data-placement="top" data-close-others="true" aria-expanded="false" onclick="redirectFunction(this, \'mdhelpdesk/ssoLogin\')" title="HELP VERITECH">';
//                            $menu .= '<i class="fas icon-stack font-size-15"></i>';
//                        $menu .= '</a>';
//                        $menu .= '<a href="javascript:;" class="newtab d-none " target="_blank"></a>';
//                    $menu .= '</li>';  
//                    
//                } elseif ($rowMeta['linkHref'] === 'mdobject/package/1648088644166855') {
//                    
//                    $menu .= '<li class="top-menu-link nav-item">';
//                        $menu .= '<a href="mdobject/package/1648088644166855" target="_blank" class="tooltips navbar-nav-link" data-placement="top" data-close-others="true" aria-expanded="false" title="Meta export">';
//                            $menu .= '<i class="fas fa-cloud-download font-size-15"></i>';
//                        $menu .= '</a>';
//                    $menu .= '</li>';
//                    
//                } elseif ($row['metadataid'] === '1680166503684224') { /*Зөрчил шалгах*/
//                    
//                    $countMetaData = '';
//                    
//                    if (isset($row['countmetadataid']) && $row['countmetadataid']) {
//                    
//                        $leftMenuCount = self::getLeftMenuCountModel($row['countmetadataid']);
//                        $countMetaData = ' <span class="badge badge-warning left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '" data-depth="'.$depth.'">' . ($leftMenuCount ? $leftMenuCount : '') . '</span>';
//                    }
//                    
//                    $menu .= '<li class="top-menu-link nav-item">';
//                        $menu .= '<a href="'.$rowMeta['linkHref'].'" target="_blank" class="tooltips navbar-nav-link hdr-open-notification-list" data-placement="top" data-close-others="true" aria-expanded="false" title="Зөрчил шалгах">';
//                            $menu .= '<i class="fas fa-exclamation-circle font-size-15"></i>' . $countMetaData;
//                        $menu .= '</a>';
//                    $menu .= '</li>';
//                    
//                }
//            }
//        }
        
        if (Config::getFromCache('isShowAppMarket')) {
                    
            $countMetaData = '';

            $leftMenuCount = self::getLeftMenuCountModel(1701853458614950);
            $countMetaData = ' <span class="badge badge-warning left-menu-count-meta" data-counmetadataid="' . 1701853458614950 . '">' . ($leftMenuCount ? $leftMenuCount : '') . '</span>';

            $menu .= '<li class="top-menu-link nav-item">';
                $menu .= '<a href="appmarket/basket&mmid=1701920318761819&mid=1701920318761819" class="tooltips navbar-nav-link hdr-open-notification-list" data-placement="top" data-close-others="true" aria-expanded="false" title="App market basket">';
                    $menu .= '<i class="fas fa-shopping-basket font-size-15 font-size-15"></i>' . $countMetaData;
                $menu .= '</a>';
            $menu .= '</li>';
        }         

        return $menu;
    }

    public function sidebarMetaMenuRenderByLimitDataModel($menuData, $moduleId, $depth, $isChild, $menuOpen, $urlId, $rowId = 0) {
        $menu = '';

        if (is_array($menuData)) {

            foreach ($menuData as $k => $row) {

                $rowMeta = Mdmeta::menuServiceAnchor($row, $moduleId);
                
                if ($rowMeta['linkHref'] === 'mdmetadata/system') {
                    
                    $menu .= '<a href="mdmetadata/system" class="veri-app-engage-btn p-0" data-toggle="tooltip" data-placement="left" title="Developer mode">';
                        $menu .= '<i class="fas fa-cogs font-size-16"></i>';
                    $menu .= '</a>';
                    
                } elseif ($rowMeta['linkHref'] === 'mdhelpdesk/login') {
                    
                    $menu .= '<a href="mdhelpdesk/login" target="_blank" class="veri-app-engage-btn p-0" data-toggle="tooltip" data-placement="left" title="Helpdesk">';
                        $menu .= '<i class="fas fa-user-headset font-size-16"></i>';
                    $menu .= '</a>';
                    
                } elseif ($rowMeta['linkHref'] === 'mdhelpdesk/ssoLogin') {
                    
                    $menu .= '<a href="javascript:;" class="veri-app-engage-btn p-0" data-toggle="tooltip" data-placement="left" data-close-others="true" aria-expanded="false" onclick="redirectFunction(this, \'mdhelpdesk/ssoLogin\')" title="Help center">';
                        $menu .= '<i class="fas fa-question-circle font-size-16"></i>';
                    $menu .= '</a>';
                    $menu .= '<a href="javascript:;" class="newtab d-none" target="_blank"></a>';
                    
                } elseif ($rowMeta['linkHref'] === 'mdobject/package/1648088644166855') {
                    
                    $menu .= '<a href="mdobject/package/1648088644166855" target="_blank" class="veri-app-engage-btn p-0" data-toggle="tooltip" data-placement="left" title="Bugfix">';
                        $menu .= '<i class="fas fa-cloud-download font-size-16"></i>';
                    $menu .= '</a>';
                    
                } elseif ($row['metadataid'] === '1709258055034258') {
                    
                    $menu .= '<a href="mdlayout/v2/17091131987379" target="_blank" class="veri-app-engage-btn p-0" data-toggle="tooltip" data-placement="left" title="Developer workspace">';
                        $menu .= '<i class="fas fa-window-restore font-size-16"></i>';
                    $menu .= '</a>';
                    
                } elseif ($row['metadataid'] === '1680166503684224') { /*Зөрчил шалгах*/
                    
                    $countMetaData = '';
                    
                    if (isset($row['countmetadataid']) && $row['countmetadataid']) {
                    
                        $leftMenuCount = self::getLeftMenuCountModel($row['countmetadataid']);
                        $countMetaData = ' <span class="badge badge-warning left-menu-count-meta" style="padding: 1px 2px 1px 2px;" data-counmetadataid="' . $row['countmetadataid'] . '" data-depth="'.$depth.'">' . ($leftMenuCount ? $leftMenuCount : '') . '</span>';
                    }
                    
                    $menu .= '<a href="'.$rowMeta['linkHref'].'" target="_blank" class="veri-app-engage-btn p-0 hdr-open-notification-list" data-toggle="tooltip" data-placement="left" title="Check query">';
                        $menu .= '<i class="fas fa-exclamation-circle font-size-16"></i>' . $countMetaData;
                    $menu .= '</a>';
                    
                } elseif ($row['metadataid'] === '1717413145805304' && Config::getFromCache('PF_METAVERSE_COMMAND_PROMPT_URL')) {
                    
                    $menu .= '<a href="javascript:;" class="veri-app-engage-btn p-0" data-toggle="tooltip" data-placement="left" title="MetaVerse Command Prompt" onclick="metaVerseCommandPromptIframe(this);">';
                        $menu .= '<i class="fas fa-terminal font-size-16"></i>';
                    $menu .= '</a>';
                    
                }
            }
        }

        return $menu;
    }

    public function getModuleNameModel($moduleId) {
        return $this->db->GetRow("
            SELECT 
                ".$this->db->IfNull('ML.GLOBE_CODE', 'MD.META_DATA_NAME')." AS META_DATA_NAME,
                ML.MENU_THEME, 
                ML.IS_MODULE_SIDEBAR 
            FROM META_DATA MD 
                LEFT JOIN META_MENU_LINK ML ON ML.META_DATA_ID = MD.META_DATA_ID 
            WHERE MD.META_DATA_ID = " . $this->db->Param(0), array($moduleId));
    }

    public function leftMetaMenuModuleModel($menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId) {
        if (!$moduleId) {
            return;
        }

        $menu = '';

        if (is_array($menuData)) {

            if ($isChild || $depth != 0) {
                
                $menuOpenStyle = '';
                if ($menuOpen == 'open_all') {
                    $menuOpenStyle = ' style="display: block;"';
                }
                $menu .= '<ul class="sub-menu"' . $menuOpenStyle . '>';
                
            } elseif ($depth == 0) {
                
                $moduleInfo = self::getModuleNameModel($moduleId);
                $moduleName = $moduleInfo['META_DATA_NAME'];
                
                if (Config::getFromCache('CONFIG_KHANBANK')) {
                    $menu = '<div class="user-photo">'
                                . '<div class="khanbank-user-photo page-sidebar-menu-title">'
                                    . Ue::getSessionPhoto('class="rounded-circle" onerror="onUserImgError(this);" width="70"') 
                                .'</div>'
                                .'<span class="khanbank-sidebar-menu-title page-sidebar-menu-title ' . $moduleInfo['MENU_THEME'] . '">'.Ue::getSessionPersonName().'</span>'
                            .'</div>';
                } else {
                    $menu = '<span class="page-sidebar-menu-title ' . $moduleInfo['MENU_THEME'] . '">' . Lang::line($moduleName) . '</span>';
                }
                
                $menu .= '<ul class="page-sidebar-menu ' . $moduleInfo['MENU_THEME'] . '" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">';
            }

            foreach ($menuData as $k => $row) {

                $childMenu = '';

                if (isset($row['child'])) {
                    $childMenu = self::leftMetaMenuModuleModel($row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $menuId);
                }

                if ($isChild || $depth != 0) {
                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;">';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row, $moduleId, $row['metadataid']);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-meta-data-id="' . $row['metadataid'] . '">';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon .= '<i class="fa ' . $row['icon'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        $newArea = '';
                        if ($row['viewtype'] == 'newarea') {
                            $newArea = ' class="vr-menu-new-area"';
                        }
                        $menuAnchor = '<a href="javascript:;"'.$newArea.'>';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchor($row, $moduleId, $row['metadataid']);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-meta-data-id="' . $row['metadataid'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    }
                }
                
                $liClass = '';
                if ($row['metadataid'] === $menuId) {
                    $liClass = ' class="active"';
                }

                $menu .= '<li' . $liClass . '>';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            if (($isChild || $depth != 0) || $depth == 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function leftMetaMenuModuleByTabModel($menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId) {
        if (!$moduleId) {
            return;
        }

        $menu = '';

        if (is_array($menuData)) {

            if ($isChild || $depth != 0) {
                
                if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                    $menu .= '<ul class="dropdown-menu">';
                } else {
                    $menuOpenStyle = '';
                    if ($menuOpen == 'open_all') {
                        $menuOpenStyle = ' style="display: block;"';
                    }
                    $menu .= '<ul class="sub-menu nav nav-group-sub"' . $menuOpenStyle . '>';
                }

            } elseif ($depth == 0) {

                $menuBack = '';
                $moduleInfo = self::getModuleNameModel($moduleId);
                $moduleName = $moduleInfo['META_DATA_NAME'];

                $cache = phpFastCache();

                $appMenuCache = $cache->get('appmenu_' . Ue::sessionUserId());
                
                if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                    
                    if ($appMenuCache['count'] > 1) {
                        $menuBack = '<a href="javascript:;" class="btn btn-circle btn-secondary page-sidebar-back-menu" data-module-id="' . $moduleId . '" data-top-menu="true"><i class="icon-arrow-left16"></i></a> ';
                    }

                    $menu = '<span class="page-topbar-menu-title ' . $moduleInfo['MENU_THEME'] . '">'.$menuBack.Lang::line($moduleName).'</span>';
                    $menu .= '<ul class="page-topbar-menu ' . $moduleInfo['MENU_THEME'] . '" data-no-scroll="true">';
                    
                } else {
                    if ($appMenuCache['count'] > 1) {
                        $menuBack = '<a href="javascript:;" class="btn btn-circle btn-secondary page-sidebar-back-menu" data-module-id="' . $moduleId . '"><i class="icon-arrow-left16"></i></a> ';
                    }
                    
                    if (Config::getFromCache('CONFIG_KHANBANK')) {
                        $menu = '<div class="user-photo">'
                                    . '<div class="khanbank-user-photo page-sidebar-menu-title">'
                                        . Ue::getSessionPhoto('class="rounded-circle" onerror="onUserImgError(this);" width="70"') 
                                    .'</div>'
                                    .'<span class="khanbank-sidebar-menu-title page-sidebar-menu-title ' . $moduleInfo['MENU_THEME'] . '">'.Ue::getSessionPersonName().'</span>'
                                .'</div>';
                    } else {
                        $menu = '<span class="page-sidebar-menu-title ' . $moduleInfo['MENU_THEME'] . '">'.$menuBack.Lang::line($moduleName).'</span>';
                    }
                    
                    $menu .= '<ul class="nav nav-sidebar ' . $moduleInfo['MENU_THEME'] . '" data-keep-expanded="false" data-nav-type="accordion" data-slide-speed="200">';
                }
            }

            foreach ($menuData as $k => $row) {

                $childMenu = $countMetaData = $attr = '';

                if (isset($row['child'])) {
                    $childMenu = self::leftMetaMenuModuleByTabModel($row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $menuId);
                }

                if (isset($row['countmetadataid']) && $row['countmetadataid'] != '') {
                    $leftMenuCount = self::getLeftMenuCountModel($row['countmetadataid']);
                    if ($leftMenuCount != '') {
                        $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '">' . $leftMenuCount . '</span>';
                    }
                }
                
                if (isset($row['menutooltip'])) {
                    $attr .= ' title="'.Lang::line($row['menutooltip']).'"';
                }

                if ($isChild || $depth != 0) {
                    
                    if ($childMenu != '') {
                        
                        if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                            $attr .= ' class="dropdown-toggle" data-toggle="dropdown"';
                        }
                        
                        $menuAnchor = '<a href="javascript:;"'.$attr.' class="nav-link">';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchorByTab($row, $moduleId, $row['metadataid']);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" class="nav-link" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-meta-data-id="' . $row['metadataid'] . '"'.$attr.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }else{
                        $icon .= '<i class="icon-stack"></i> ';
                    }

                    if ($childMenu != '') {
                        $newArea = $attr;
                        /*if ($row['viewtype'] == 'newarea') {
                            $newArea = ' class="vr-menu-new-area"';
                        }*/
                        if ($childMenu && defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                            $newArea .= ' class="dropdown-toggle" data-toggle="dropdown"';
                        }
                        $menuAnchor = '<a href="javascript:;"'.$newArea.' class="nav-link">';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchorByTab($row, $moduleId, $row['metadataid']);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" class="nav-link" starget="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-meta-data-id="' . $row['metadataid'] . '"'.$attr.'>';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = 'nav-item';
                
                if ($childMenu) {
                    $liClass .= ' nav-item-submenu';
                }
                if ($row['metadataid'] === $menuId) {
                    $liClass .= ' active';
                }

                $menu .= '<li class="' . $liClass . '" data-menumeta-id="'.$row['metadataid'].'">';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }

            if (($isChild || $depth != 0) || $depth == 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }
    
    public function topMetaMenuModuleModel($menuData, $moduleId, $depth = 0, $menuId, $moduleInfo = array()) {
        if (!$moduleId) {
            return;
        }

        $menu = '';
        $isFirstLetterUpperMenu = Config::getFromCacheDefault('ISFIRST_LETTER_UPPER_MENU', null, '1');

        if (is_array($menuData)) {

            if ($depth != 0) {
                
                $menu .= '<ul class="dropdown-menu">';

            } elseif ($depth == 0) {               

                $menu .= '<ul class="navbar-nav'.($isFirstLetterUpperMenu == '1' ? ' text-uppercase' : '').' d-flex align-content-around flex-wrap" data-no-scroll="true">';
            }

            foreach ($menuData as $k => $row) {

                $childMenu = $attr = '';

                if (isset($row['child'])) {
                    $childMenu = self::topMetaMenuModuleModel($row['child'], $moduleId, $depth + 1, $menuId, $moduleInfo);
                }

                $countMetaData = '';

                if (isset($row['countmetadataid']) && $row['countmetadataid']) {
                    
                    if ($depth == 0) {
                        $leftMenuCount = self::getLeftMenuCountModel($row['countmetadataid']);
                    } else {
                        $leftMenuCount = '<i class="fa fa-spinner fa-spin"></i>';
                    }
                    
                    $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta position-relative" data-counmetadataid="' . $row['countmetadataid'] . '" data-depth="'.$depth.'">' . ($leftMenuCount ? $leftMenuCount : '') . '</span>';
                }
                
                if (isset($row['menutooltip'])) {
                    $attr .= ' title="'.Lang::line($row['menutooltip']).'"';
                }

                if ($depth != 0) {
                    
                    if ($childMenu != '') {
                        
                        $menuAnchor = '<a href="javascript:;" class="navbar-nav-link dropdown-toggle dropdown-item" data-toggle="dropdown" data-meta-data-id="' . $row['metadataid'] . '" data-pfgotometa="1" data-default-open="' . issetParam($row['isdefaultopen']) . '">';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                        
                    } else {
                        
                        $rowMeta = Mdmeta::menuServiceAnchorByTab($row, $moduleId, $row['metadataid'], $moduleInfo['META_DATA_NAME']);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" class="dropdown-item" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-default-open="' . issetParam($row['isdefaultopen']) . '" data-meta-data-id="' . $row['metadataid'] . '" data-pfgotometa="1"'.$attr.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                    
                } else {
                    
                    $icon = '';
                    
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        
                        $newArea = '';
                        
                        if ($childMenu) {
                            $newArea .= ' class="dropdown-toggle navbar-nav-link" data-toggle="dropdown"';
                        } 
                        
                        $menuAnchor = '<a href="javascript:;"'.$newArea.' data-meta-data-id="' . $row['metadataid'] . '" data-pfgotometa="1"'.$attr.'>';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . $countMetaData;
                        $menuAnchor .= '</a>';
                        
                    } else {
                        
                        $rowMeta = Mdmeta::menuServiceAnchorByTab($row, $moduleId, $row['metadataid'], $moduleInfo['META_DATA_NAME']);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" class="navbar-nav-link" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-meta-data-id="' . $row['metadataid'] . '" data-pfgotometa="1" data-default-open="' . issetParam($row['isdefaultopen']) . '"'.$attr.'>';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = '';
                
                if ($childMenu) {
                    $liClass .= ($depth == 0) ? ' dropdown' : ' dropdown-submenu';
                }
                
                if ($row['metadataid'] === $menuId) {
                    $liClass .= ' active';
                }

                $menu .= '<li class="nav-item' . $liClass . '">';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
            }
            
            if ($depth == 0 && Config::getFromCache('is_dev')) {
                $menu .= '<li class="nav-item"><a href="javascript:;" class="navbar-nav-link" onclick="menuMetaAddByUser(this, \''.$moduleId.'\');"><span class="title"><i class="fa fa-plus" style="color: #999"></i></span></a></li>';
            }

            if ($depth != 0 || $depth == 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function topMetaMegaMenuModuleByTabModel($menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId) {
        if (!$moduleId) {
            return;
        }

        $menu = '';

        if (is_array($menuData)) {

            if ($isChild || $depth != 0) {
                if($depth == 1)
                    $menu .= '<ul class="dropdown-menu"><li><div class="mega-menu-content"><div class="row">';
                elseif($depth == 2)
                    $menu .= '';
                else
                    $menu .= '<ul class="dropdown-menu">';

            } elseif ($depth == 0) {

                $menuBack = '';
                $moduleInfo = self::getModuleNameModel($moduleId);
                $moduleName = $moduleInfo['META_DATA_NAME'];

                $cache = phpFastCache();

                $appMenuCache = $cache->get('appmenu_' . Ue::sessionUserId());
                
                if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                    
                    if ($appMenuCache['count'] > 1) {
                        $menuBack = '<a href="javascript:;" class="btn btn-circle btn-secondary page-sidebar-back-menu" data-module-id="' . $moduleId . '" data-top-menu="true"><i class="fa fa-arrow-left"></i></a> ';
                    }

                    $menu = '<span class="page-topbar-menu-title ' . $moduleInfo['MENU_THEME'] . '">'.$menuBack.Lang::line($moduleName).'</span>';
                    $menu .= '<ul class="page-topbar-menu ' . $moduleInfo['MENU_THEME'] . '" data-no-scroll="true">';
                    
                } else {
                    if ($appMenuCache['count'] > 1) {
                        $menuBack = '<a href="javascript:;" class="btn btn-circle btn-secondary page-sidebar-back-menu" data-module-id="' . $moduleId . '"><i class="fa fa-arrow-left"></i></a> ';
                    }

                    $menu = '<span class="page-sidebar-menu-title ' . $moduleInfo['MENU_THEME'] . '">'.$menuBack.Lang::line($moduleName).'</span>';
                    $menu .= '<ul class="page-sidebar-menu ' . $moduleInfo['MENU_THEME'] . '" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">';
                }
            }

            foreach ($menuData as $k => $row) {

                $childMenu = '';

                if (isset($row['child'])) {
                    $childMenu = self::topMetaMegaMenuModuleByTabModel($row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $menuId, $moduleName);
                }

                $countMetaData = '';

                if (isset($row['countmetadataid']) && $row['countmetadataid'] != '') {
                    $leftMenuCount = self::getLeftMenuCountModel($row['countmetadataid']);
                    if ($leftMenuCount != '') {
                        $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '">' . $leftMenuCount . '</span>';
                    }
                }

                if ($isChild || $depth != 0) {
                    
                    if ($childMenu != '') {
                        if($depth == 1) {                            
                            $menuAnchor = '<h3>' . Lang::line($row['name']) . '</h3>';

                        } else {
                            $attr = '';
                            $attr = ' class="dropdown-toggle" data-toggle="dropdown"';

                            $menuAnchor = '<a href="javascript:;"'.$attr.'>';
                            $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                            $menuAnchor .= '</a>';
                        }
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchorByTab($row, $moduleId, $row['metadataid'], $moduleName);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-meta-data-id="' . $row['metadataid'] . '">';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        $newArea = '';
                        $newArea .= ' class="dropdown-toggle" data-toggle="dropdown"';
                        
                        $menuAnchor = '<a href="javascript:;"'.$newArea.'>';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                        
                    } else {
                        $rowMeta = Mdmeta::menuServiceAnchorByTab($row, $moduleId, $row['metadataid'], $moduleName);

                        $menuAnchor = '<a href="' . $rowMeta['linkHref'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" data-meta-data-id="' . $row['metadataid'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = 'mega-menu-dropdown';
                if ($row['metadataid'] === $menuId) {
                    $liClass .= ' active';
                }

                if($depth == 1) {
                    $menu .= '<ul class="col-md-4 mega-menu-submenu"><li>';
                    $liClass = '';
                } elseif($depth > 2)
                    $liClass = 'dropdown-submenu';
                
                $menu .= '<li class="' . $liClass . '">';
                $menu .= $menuAnchor;
                $menu .= $childMenu;
                $menu .= '</li>';
                
                if($depth == 1 || $depth == 2)
                    $menu .= '</li>';
                
                if($depth == 1) {
                    $menu .= '</ul>';
                }
            }

            if (($isChild || $depth == 1)) {
                $menu .= '</div></div></li></ul>';
            }
            if ($depth == 0 || ($depth > 1 && $isChild)) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function getLeftMenuCountModel($countMetaDataId, $criteriaParam = '') {
        $leftMenuCount = '';

        if ($countMetaDataId != '' && !empty($countMetaDataId)) {
            
            $metaPh = $this->db->Param(0);
            $countMetaTypeId = $this->db->GetOne("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = $metaPh", array($countMetaDataId));

            if ($countMetaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                
                $param = array(
                    'systemMetaGroupId' => $countMetaDataId,
                    'showQuery' => '0', 
                    'ignorePermission' => 1 
                );
                $criteria = array();
                
                $paramList = $this->db->GetAll("
                    SELECT 
                        FIELD_PATH AS PARAM_REAL_PATH, 
                        DATA_TYPE, 
                        DEFAULT_VALUE 
                    FROM META_GROUP_CONFIG 
                    WHERE MAIN_META_DATA_ID = $metaPh  
                        AND PARENT_ID IS NULL 
                        AND DATA_TYPE <> 'group' 
                        AND DEFAULT_VALUE IS NOT NULL", 
                    array($countMetaDataId)
                );

                foreach ($paramList as $input) {
                        
                    $value = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $input['DATA_TYPE']);

                    $criteria[$input['PARAM_REAL_PATH']][] = array(
                        'operator' => '=',
                        'operand' => $value
                    );
                }
                
                if (Input::isEmpty('workSpaceId') == false && Input::isEmpty('workSpaceParams') == false) {
                        
                    $workSpaceId = Input::numeric('workSpaceId');
                    $criteria = Arr::changeKeyLower($criteria);

                    $this->load->model('mdobject', 'middleware/models/');
                    $wsParamMap = $this->model->getWorkSpaceDvParamMap($countMetaDataId, $workSpaceId);

                    if ($wsParamMap) {

                        $workSpaceParams = Input::post('workSpaceParams');

                        parse_str($workSpaceParams, $workSpaceParamArray);
                        $workSpaceParamArray = Arr::changeKeyLower($workSpaceParamArray);

                        foreach ($wsParamMap as $wsParamMapRow) {

                            $fieldPath = strtolower($wsParamMapRow['FIELD_PATH']);

                            if (isset($workSpaceParamArray['workspaceparam'][$fieldPath])) {

                                $paramPath = strtolower($wsParamMapRow['PARAM_PATH']);
                                
                                $criteria[$paramPath][] = array(
                                    'operator' => '=',
                                    'operand' => Input::param($workSpaceParamArray['workspaceparam'][$fieldPath])
                                );
                            }
                        }
                    }                        
                }
                
                $dataViewCmd = Mddatamodel::$getRowDataViewCommand;
                
                if ($criteriaParam) {
                    foreach ($criteriaParam as $k => $val) {
                        $criteria[$k][] = array(
                            'operator' => '=',
                            'operand' => $val
                        );                        
                    }
                    $param['paging'] = array(
                        'offset' => 1,
                        'pageSize' => 6
                    );
                    $dataViewCmd = Mddatamodel::$getDataViewCommand;
                }

                $param['criteria'] = $criteria;

                $dataViewValue = $this->ws->runSerializeResponse(self::$gfServiceAddress, $dataViewCmd, $param);

                if ($criteriaParam && isset($dataViewValue['result']['paging']['totalcount'])) {
                    $leftMenuCount = $dataViewValue['result']['paging']['totalcount'];
                } elseif (isset($dataViewValue['result']) && isset($dataViewValue['result']['count'])) {
                    $leftMenuCount = $dataViewValue['result']['count'];
                }

            } elseif ($countMetaTypeId == Mdmetadata::$businessProcessMetaTypeId) {

                $this->load->model('mdwebservice', 'middleware/models/');
                $resultCountMetaData = $this->model->getMethodIdByMetaDataModel($countMetaDataId);

                if ($resultCountMetaData) {

                    $param = array();
                    $paramList = $this->model->groupParamsDataModel($countMetaDataId, null, ' AND PAL.PARENT_ID IS NULL');

                    foreach ($paramList as $input) {
                        if ($input['META_TYPE_CODE'] != 'group') {
                            $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $input['META_TYPE_CODE']);
                        } 
                    }
                    
                    if (Input::isEmpty('workSpaceId') == false && Input::isEmpty('workSpaceParams') == false) {
                        
                        $workSpaceId = Input::numeric('workSpaceId');
                        $param = Arr::changeKeyLower($param);
                        
                        $this->load->model('mdobject', 'middleware/models/');
                        $wsParamMap = $this->model->getWorkSpaceDvParamMap($countMetaDataId, $workSpaceId);
                        
                        if ($wsParamMap) {
                            
                            $workSpaceParams = Input::post('workSpaceParams');
                            
                            parse_str($workSpaceParams, $workSpaceParamArray);
                            $workSpaceParamArray = Arr::changeKeyLower($workSpaceParamArray);
                            
                            foreach ($wsParamMap as $wsParamMapRow) {
                                
                                $fieldPath = strtolower($wsParamMapRow['FIELD_PATH']);
                                
                                if (isset($workSpaceParamArray['workspaceparam'][$fieldPath])) {
                                    
                                    $paramPath = strtolower($wsParamMapRow['PARAM_PATH']);
                                    $param[$paramPath] = Input::param($workSpaceParamArray['workspaceparam'][$fieldPath]);
                                }
                            }
                        }                        
                    }

                    $resultBusinessProcess = $this->ws->caller($resultCountMetaData['SERVICE_LANGUAGE_CODE'], $resultCountMetaData['WS_URL'], $resultCountMetaData['META_DATA_CODE'], 'return', $param);

                    if (isset($resultBusinessProcess['result']) && $resultBusinessProcess['status'] = 'success' && isset($resultBusinessProcess['result']['count'])) {
                        $leftMenuCount = $resultBusinessProcess['result']['count'];
                    }
                }
                
                $this->load->model('mdmeta', 'middleware/models/');
            }
        }

        return $leftMenuCount;
    }

    public function getMetaMenuListByServiceModel($menuCode) {

        $cache = phpFastCache();
        $userKeyId = Ue::sessionUserKeyId();
        $response = $cache->get('topmenu_' . $userKeyId);

        if ($response == null) {

            $param = array(
                'menuCode' => $menuCode,
                'userId' => $userKeyId
            );

            $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'get_menus', $param);

            if ($data['status'] == 'success' && array_key_exists('result', $data)) {

                $response = array('status' => 'success', 'menuData' => $data['result']);
                $cache->set('topmenu_' . $userKeyId, $response, Mdmetadata::$defaultCacheTime);

            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
        }

        return $response;
    }

    public function getMetaMenuListByModuleIdModel($menuId) {

        $param = array(
            'menuId' => $menuId,
            'userId' => Ue::sessionUserKeyId()
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'child_menus', $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            return array('status' => 'success', 'menuData' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }
    
    public function getMenuListByParentIdCacheModel($menuId) {
        
        if ($menuId) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();

            $cache = phpFastCache();
            $menuData = $cache->get('leftmenu_'.$sessionUserKeyId.'_'.$menuId);

            if ($menuData == null) {
                $menuData = self::getMetaMenuListByModuleIdModel($menuId);
                $cache->set('leftmenu_'.$sessionUserKeyId.'_'.$menuId, $menuData, 28800);
            }

            return $menuData;
        }
        
        return null;
    }

    public function metaImportFileModel() {

        /*if (!Mdmeta::isAccessMetaImport()) {
            return array('status' => 'error', 'message' => 'Импорт хийх боломжгүй байна.');
        }*/

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);

        $importType = Input::post('importType');
        $isOverride = Input::post('isOverride');

        $rowId = Input::post('rowId');
        $xmlSource = array();

        $totalFile = count($_FILES['meta_import_file']['name']);

        for ($i = 0; $i < $totalFile; $i++) {
            if ($_FILES['meta_import_file']['error'][$i] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['meta_import_file']['tmp_name'][$i])) {
                $xmlSource[] = file_get_contents($_FILES['meta_import_file']['tmp_name'][$i]);
            }
        }

        if ($importType === 'meta') {

            $param = array(
                'isOverride' => $isOverride,
                'xmlSource' => $xmlSource, 
                'folderId' => null
            );

            $data = $this->ws->runResponse(self::getBPServiceUrlByMetaCode('MD_IMP_001'), 'MD_IMP_001', $param, 'master');

            if ($data['status'] == 'success') {
                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }

        } elseif ($importType === 'metas') {

            $param = array(
                'isOverride' => $isOverride,
                'xmlSource' => $xmlSource, 
                'folderId' => null 
            );
            $data = $this->ws->runResponse(self::$gfServiceAddress, 'MD_IMP_002', $param, 'master');

            if ($data['status'] == 'success') {
                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }

        } elseif ($importType === 'folder') {

            $param = array(
                'isOverride' => $isOverride,
                'xmlSource' => $xmlSource, 
                'folderId' => null 
            );
            $data = $this->ws->runResponse(self::$gfServiceAddress, 'FD_IMP_001', $param, 'master');

            if ($data['status'] == 'success') {
                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }

        } elseif ($importType === 'foldermeta') {

            $param = array(
                'isOverride' => $isOverride,
                'xmlSource' => $xmlSource, 
                'folderId' => null 
            );
            $data = $this->ws->runResponse(self::$gfServiceAddress, 'FD_IMP_002', $param, 'master');

            if ($data['status'] == 'success') {
                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }

        } elseif ($importType === 'upgrade') {

            $param = array(
                'xmlSource' => $xmlSource
            );
            $data = $this->ws->runResponse(self::$gfServiceAddress, 'vr_imp_001', $param, 'master');

            if ($data['status'] == 'success') {

                $response = array('status' => 'success', 'message' => 'Амжилттай шинэчлэлээ');

                if (isset($data['result']) && !empty($data['result'])) {

                    includeLib('Compress/Compression');
                    $s = Compression::decompress($data['result']);

                    $unSerialize = unserialize($s);

                    if (isset($unSerialize['result']) && is_array($unSerialize['result'])) {
                        $response['result'] = $unSerialize['result'];
                    }
                }

                return $response;

            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
        }
    }

    public function getMetaGroupParamMetaModel($metaDataId) {
        
        if ($metaDataId) {
            
            $data = $this->db->GetAll("
                SELECT 
                    FIELD_PATH AS FIELD_NAME, 
                    LABEL_NAME AS META_DATA_NAME 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND PARENT_ID IS NULL 
                ORDER BY FEATURE_NUM ASC", array($metaDataId));
            
        } else {
            $data = array();
        }
        
        return $data;
    }

    public function getMetaProcessParamMetaModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                PARAM_NAME, 
                PARAM_REAL_PATH, 
                LABEL_NAME  
            FROM META_PROCESS_PARAM_ATTR_LINK  
            WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_INPUT = 1 
                AND IS_SHOW = 1      
                AND DATA_TYPE <> 'group' 
            ORDER BY ORDER_NUMBER ASC", array($metaDataId));

        return $data;
    }

    public function getDMTransferProcessModel($mainMetaDataId, $processMetaDataId, $basket = false, $returnData = false) {
        $andWhere = 'AND PD.BASKET_PATH IS NULL'; 
        $addinPath = ''; 
        $inputPath = 'INPUT_PARAM_PATH';
        $defaultValue = 'DEFAULT_VALUE';

        if ($basket) {
            $defaultValue = 'BASKET_INPUTPATH';
            $inputPath = 'BASKET_PATH';
            $addinPath = 'Basket';
            $andWhere = ' AND PD.BASKET_PATH IS NOT NULL';
        }
        
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                PD.VIEW_FIELD_PATH, 
                PD.INPUT_PARAM_PATH, 
                PD.DEFAULT_VALUE,
                PD.BASKET_PATH,
                PD.BASKET_INPUTPATH
            FROM META_DM_TRANSFER_PROCESS PD 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = PD.GET_META_DATA_ID 
                    AND MD.IS_ACTIVE = 1 
            WHERE PD.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PD.PROCESS_META_DATA_ID = ".$this->db->Param(1)." $andWhere", 
            array($mainMetaDataId, $processMetaDataId)
        );
        
        if ($returnData) {
            return $data;
        }
        
        $result = '';

        if ($data) {
            foreach ($data as $row) {
                $result .= Form::hidden(array('name' => 'groupProcessDtl'. $addinPath .'TransferGetMetaId[' . $processMetaDataId . '][]', 'value' => $row['META_DATA_ID']));
                $result .= Form::hidden(array('name' => 'groupProcessDtl'. $addinPath .'TransferViewPath[' . $processMetaDataId . '][]', 'value' => $row['VIEW_FIELD_PATH']));
                $result .= Form::hidden(array('name' => 'groupProcessDtl'. $addinPath .'TransferParamPath[' . $processMetaDataId . '][]', 'value' => $row[$inputPath]));
                $result .= Form::hidden(array('name' => 'groupProcessDtl'. $addinPath .'TransferDefaultValue[' . $processMetaDataId . '][]', 'value' => $row[$defaultValue]));
            }
        }

        return $result;
    }

    public function getGroupChildMetasNotGroupType($metaDataId) {

        $data = $this->db->GetAll("
            SELECT 
                LABEL_NAME AS META_DATA_NAME, 
                FIELD_PATH AS META_DATA_CODE 
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)."  
                AND PARENT_ID IS NULL 
                AND IS_SELECT = 1 
                AND DATA_TYPE <> 'group'     
            ORDER BY DISPLAY_ORDER ASC", array($metaDataId));

        return $data;
    }

    public function saveCopyMetaDataModel() {
        
        $this->load->model('mdmetadata', 'middleware/models/');

        $metaDataCode = Input::post('metaDataCode');
        
        if ($this->model->checkMetaDataCodeModel($metaDataCode)) {
            return array('status' => 'error', 'message' => 'Үзүүлэлтийн код давхардаж байна.', 'fieldName' => 'metaDataCode');
        }

        $param = array(
            'id'       => Input::post('metaDataId'),
            'code'     => Input::post('metaDataCode'),
            'name'     => Input::post('metaDataName'),
            'folderId' => Input::post('folderId')
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, 'copy_metadata', $param);

        if ($data['status'] == 'success') {
            
            if ($newId = issetParam($data['id'])) {
                
                try {
                    
                    $insertData = array(
                        'ID'               => getUID(), 
                        'META_DATA_ID'     => $param['id'], 
                        'NEW_META_DATA_ID' => $newId, 
                        'CREATED_USER_ID'  => Ue::sessionUserKeyId(), 
                        'CREATED_DATE'     => Date::currentDate('Y-m-d H:i:s')
                    );

                    $this->db->AutoExecute('CUSTOMER_META_COPY_LOG', $insertData);
                
                } catch (Exception $ex) {
                    $message = $ex->getMessage();
                }
            }
            
            return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function getMetaGroupLinkRowModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_NAME, 
                GL.TABLE_NAME 
            FROM META_DATA MD 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
            WHERE MD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
        
        return $row;
    }

    public function getMetaGroupChildMetasModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                GL.META_DATA_NAME, 
                GL.META_DATA_CODE, 
                " . $this->db->IfNull('FL.DATA_TYPE', 'MT.META_TYPE_CODE') . " AS META_TYPE_CODE 
            FROM META_META_MAP MD 
                INNER JOIN META_DATA GL ON GL.META_DATA_ID = MD.TRG_META_DATA_ID 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = GL.META_TYPE_ID 
                LEFT JOIN META_FIELD_LINK FL ON FL.META_DATA_ID = GL.META_DATA_ID 
            WHERE MD.SRC_META_DATA_ID = ".$this->db->Param(0)." 
            ORDER BY MD.ORDER_NUM ASC", array($metaDataId));

        return $data;
    }

    public function createTableStructureModel() {
        $param = array(
            'metaGroupId' => Input::numeric('metaDataId')
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'create_structure_metadata', $param);

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Амжилттай үүслээ.');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function dataViewSqlModel($metaDataId) {
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'sql' => $this->ws->getValue($data['result']));
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function getGroupPathModel($metaDataId) {
        $param = array(
            'metaDataId' => $metaDataId
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'meta_path', $param);
        if ($data['status'] == 'success') {
            return array('status' => 'success', 'data' => $data['result']);
        } else {
            $message = $data['text'];
            $message .= $this->ws->errorReport($data);

            return array('status' => 'error', 'message' => $message);
        }
    }

    public function drawGroupPathModel($result, $title = '', $depth = 0, $parent = '', $space = 20) {
        $html = '';
        if ($title != '') {
            $html .= '<tr><h3>' . $title . '</h3></tr>';
        }
        foreach ($result as $k => $value) {
            if (isset($value['default'])) {
                $row = $value['default'];
                if (isset($row['childs'])) {
                    if (count($row['childs']) > 0)
                        $html .= '<tr class="tabletree-' . $k . $depth . ' tabletree-parent-' . $parent . '">';
                } else {
                    $html .= '<tr class="tabletree-' . $k . $depth . ' tabletree-parent-' . $parent . '">';
                }
                $html .= '<td>';
                $html .= '<div style="position: relative; top: -20px; left: ' . $space . 'px; bottom:0px;">';
                $html .= '<strong>' . $row['metadataname'] . '</strong>';
                foreach ($row['folderid'] as $key => $r) {
                    $html .= '<br><a href="javascript:;" onclick="childRecordView(\'' . $key . '\', \'folder\', \'\');"><i class="fa fa-caret-right"></i> ' . $r . '</a>';
                }
                $html .= '</div>';
                $html .= '</td>';
                $html .= '<tr>';
                if (isset($row['childs'])) {
                    if (count($row['childs']) > 0) {
                        $html .= self::drawGroupPathModel($row['childs'], '', $depth + 1, $k . $depth, $space + 20);
                    }
                }
                if (isset($row['inputmetadataid'])) {
                    $html .= self::drawInputOutputPathModel($row['inputmetadataid'], 'Оролтын групп', $depth, '', $space + 20);
                }
                if (isset($row['outputmetadataid'])) {
                    $html .= self::drawInputOutputPathModel($row['outputmetadataid'], 'Гаралтын групп', $depth, '', $space + 20);
                }
            } else {
                $space = $space + 20;
                if (isset($value['childs'])) {
                    if (count($value['childs']) > 0)
                        $html .= '<tr class="tabletree-' . $k . $depth . ' tabletree-parent-' . $parent . '">';
                } else {
                    $html .= '<tr class="tabletree-' . $k . $depth . ' tabletree-parent-' . $parent . '">';
                }
                $html .= '<td>';
                $html .= '<div style="position: relative; top: -20px; left: ' . $space . 'px; bottom:0px;">';
                $html .= '<strong>' . $value['metadataname'] . '</strong>';
                foreach ($value['folderid'] as $key => $r) {
                    $html .= '<br><a href="javascript:;" onclick="childRecordView(\'' . $key . '\', \'folder\', \'\');"><i class="fa fa-caret-right"></i> ' . $r . '</a>';
                }
                $html .= '</div>';
                $html .= '</td>';
                $html .= '<tr>';
                if (isset($value['childs'])) {
                    if (count($value['childs']) > 0) {
                        $html .= self::drawGroupPathModel($value['childs'], '', $depth + 1, $k . $depth, $space + 20);
                    }
                }
                if (isset($value['inputmetadataid'])) {
                    $html .= self::drawInputOutputPathModel($value['inputmetadataid'], 'Оролтын групп', $depth + 1, '', $space + 20);
                }
                if (isset($value['outputmetadataid'])) {
                    $html .= self::drawInputOutputPathModel($value['outputmetadataid'], 'Гаралтын групп', $depth + 1, '', $space + 20);
                }
            }
        }
        return $html;
    }

    public function drawInputOutputPathModel($result, $title, $depth = 0, $parent = '', $space = 20) {
        $html = '';
        if ($title != '') {
            $html .= '<tr><td>';
            $html .= '<div style="margin-left: ' . $space . 'px;">';
            $html .= '<h4><strong>' . $title . '</strong></h4>';
            $html .= '</div>';
            $html .= '</td></tr>';
        }
        foreach ($result as $k => $row) {
            if (isset($row['childs'])) {
                if (count($row['childs']) > 0)
                    $html .= '<tr class="tabletree-' . $k . $depth . ' tabletree-parent-' . $parent . '">';
            } else {
                $html .= '<tr class="tabletree-' . $k . $depth . ' tabletree-parent-' . $parent . '">';
            }
            $html .= '<td>';
            $html .= '<div style="position: relative; top: -20px; left: ' . $space . 'px; bottom:0px;">';
            $html .= '<strong>' . $row['metadataname'] . '</strong>';
            foreach ($row['folderid'] as $key => $r) {
                $html .= '<br><a href="javascript:;" onclick="childRecordView(\'' . $key . '\', \'folder\', \'\');"><i class="fa fa-caret-right"></i> ' . $r . '</a>';
            }
            $html .= '</div>';
            $html .= '</td>';
            $html .= '<tr>';
            if (isset($row['childs'])) {
                if (count($row['childs']) > 0) {
                    $html .= self::drawInputOutputPathModel($row['childs'], '', $depth + 1, $k . $depth, $space + 20);
                }
            }
        }
        return $html;
    }

    public function entityListModel() {
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'all_entities');

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'data' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function generateEntityToGroupModel() {
        $paramData = array(
            'entityName' => Input::post('entityName'),
            'folderId' => Input::post('folderId')
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'generate_meta_group', $paramData);

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Амжилттай үүсгэлээ');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function tablesListModel() {
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'all_tables');

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'data' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function generateTableToStructureModel() {
        $data = $_POST['tableName'];

        $tableNames = array();
        foreach ($data as $key => $row) {
            array_push($tableNames, $row);
        }

        $paramData = array(
            'folderId' => Input::post('folderId'),
            'tableNames' => $tableNames
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, 'generate_structure', $paramData);

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Амжилттай үүсгэлээ');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function getStructureChildMetasModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME 
            FROM META_META_MAP MM 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MM.TRG_META_DATA_ID 
            WHERE MM.SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND MD.META_TYPE_ID <> ".$this->db->Param(1)." 
            ORDER BY MM.ORDER_NUM ASC", 
            array($metaDataId, Mdmetadata::$metaGroupMetaTypeId)
        );

        return $data;
    }

    public function refreshStructureModel($metaDataId) {
        $param = array(
            'metaDataId' => $metaDataId
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'refresh_structure', $param);

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Амжилттай update хийлээ');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function checkWorkFlow($metaDataId, $metaDataCode, $metaDataName) {
        $getUID = getUID();
        $result = $this->db->GetOne("
            SELECT 
                WFM_WORKFLOW_ID
            FROM WFM_WORKFLOW
            WHERE ENTITY_META_DATA_ID = $metaDataId");
        if ($result != null) {
            return $result;
        } else {
            $data = array(
                'WFM_WORKFLOW_ID' => Input::param($getUID),
                'WFM_WORKFLOW_NAME' => $metaDataName,
                'WFM_WORKFLOW_GROUP_ID' => null,
                'WFM_WORKFLOW_CODE' => $metaDataCode,
                'WFM_WORKFLOW_PARENT_ID' => null,
                'IS_ACTIVE' => 1,
                'ENTITY_META_DATA_ID' => $metaDataId,
                'ENTITY_META_DATA_Code' => $metaDataCode
            );
            $result = $this->db->AutoExecute('WFM_WORKFLOW', $data);
            return $getUID;
        }
    }

    public function saveWorkFlowStatusModel() {
        $postData = Input::postData();
        $wfmWorkflowId = self::checkWorkFlow($postData['metaDataId'], $postData['metaDataCode'], $postData['metaDataName']);
        $data = array(
            'WFM_STATUS_ID' => Input::param(getUID()),
            'WFM_STATUS_CODE' => $postData['wfmStatusCode'],
            'WFM_STATUS_NAME' => $postData['wfmStatusName'],
            'WFM_WORKFLOW_ID' => Input::param($wfmWorkflowId),
            'WFM_STATUS_COLOR' => $postData['wfmStatusColor'],
            'IS_ACTIVE' => $postData['isActive']
        );
        $result = $this->db->AutoExecute('WFM_WORKFLOW_STATUS', $data);
        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function updateWorkFlowStatusModel() {
        $postData = Input::postData();
        $data = array(
            'WFM_STATUS_CODE' => $postData['wfmStatusCode'],
            'WFM_STATUS_NAME' => $postData['wfmStatusName'],
            'WFM_STATUS_COLOR' => $postData['wfmStatusColor'],
            'IS_ACTIVE' => $postData['isActive']
        );
        $result = $this->db->AutoExecute('WFM_WORKFLOW_STATUS', $data, 'UPDATE', 'WFM_STATUS_ID = ' . $postData['wfmStatusId']);
        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function initWorkFlowStatusModel() {
        $result = $this->db->GetAll("
            SELECT  
                WWS.WFM_STATUS_ID,
                WWS.WFM_STATUS_CODE,
                WWS.WFM_STATUS_NAME,
                WWS.WFM_STATUS_COLOR,
                WWS.IS_ACTIVE,
                WWS.WFM_WORKFLOW_ID
            FROM WFM_WORKFLOW WW
                INNER JOIN WFM_WORKFLOW_STATUS WWS ON WW.WFM_WORKFLOW_ID = WWS.WFM_WORKFLOW_ID
            WHERE WW.ENTITY_META_DATA_ID=" . Input::numeric('metaDataId'));
        
        if (count($result) > 0) {
            return array('status' => 'success', 'result' => $result);
        }
        return array('status' => 'error', 'message' => 'Бичлэг олдсонгүй');
    }

    public function deleteWorkFlowStatusModel() {
        $id = Input::post('id');
        $result = $this->db->Execute("DELETE FROM WFM_WORKFLOW_STATUS WHERE WFM_STATUS_ID = $id");

        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
        }
    }

    public function wfmStatusListModel($wfmStatusId) {
        $data = $this->db->GetRow("
            SELECT 
                WFM_STATUS_ID,
                WFM_STATUS_CODE,
                WFM_STATUS_NAME,
                WFM_WORKFLOW_ID,
                WFM_STATUS_COLOR,
                IS_ACTIVE
            FROM WFM_WORKFLOW_STATUS
            WHERE WFM_STATUS_ID = $wfmStatusId");
        
        return $data;
    }

    public function lifecycleBookListModel($dataModelId, $dataModelName) {

        $data = array();
        $result = $this->db->GetAll("
            SELECT 
                MDLM.LC_BOOK_ID,
                MDLB.LC_BOOK_CODE,
                MDLB.LC_BOOK_NAME,
                MDLM.CRITERIA,
                MDLM.DATA_MODEL_ID
            FROM META_DM_LIFECYCLE_MAP MDLM
                INNER JOIN META_DM_LC_BOOK MDLB ON MDLM.LC_BOOK_ID = MDLB.ID
            WHERE MDLM.DATA_MODEL_ID = $dataModelId");
        $i = 0;
        if (count($result) > 0) {
            $html = '';
            foreach ($result as $k => $row) {
                $i++;
                $data[$k]['LC_BOOK_ID'] = $row['LC_BOOK_ID'];
                $data[$k]['LC_BOOK_CODE'] = $row['LC_BOOK_CODE'];
                $data[$k]['LC_BOOK_NAME'] = $row['LC_BOOK_NAME'];
                $data[$k]['CRITERIA'] = $row['CRITERIA'];
                $data[$k]['DATA_MODEL_ID'] = $row['DATA_MODEL_ID'];
                $lifecycle = self::lifecycleListModel($row['DATA_MODEL_ID'], $row['LC_BOOK_ID']);
                $lifecycleCount = count($lifecycle);
                if ($lifecycleCount > 0) {
                    if ($lifecycleCount === 1) {
                        $r = $lifecycle[0];
                        $html .= '<tr class="lcbook tabletree-lc-' . $r['LIFECYCLE_ID'] . ' tabletree-parent-lcb-' . $row['LC_BOOK_ID'] . ' tabletree-collapsed lcbook-lifecycle" data-row="tabletree-lc-' . $r['LIFECYCLE_ID'] . '" data-row-parent="tabletree-parent-lcb-' . $row['LC_BOOK_ID'] . '">';
                        $html .= '<td class="middle">';
                        $html .= '<input type="hidden" name="lcBookId[]" value="' . $row['LC_BOOK_ID'] . '">';
                        $html .= '<input type="hidden" name="lifecycleId[]" value="' . $r['LIFECYCLE_ID'] . '">';
                        $html .= '</td>';
                        $html .= '<td class="middle">' . $r['LIFECYCLE_CODE'] . '</td>';
                        $html .= '<td class="middle">' . $r['LIFECYCLE_NAME'] . '</td>';
                        $html .= '<td class="middle">';
                        $html .= '<a href="javascript:;" class="btn green btn-xs" onclick="viewLifeCycle(\'' . $dataModelId . '\', \'' . $dataModelName . '\', \'' . $row['LC_BOOK_ID'] . '\', \'' . $row['LC_BOOK_NAME'] . '\', \'' . $r['LIFECYCLE_ID'] . '\', \'' . $r['LIFECYCLE_NAME'] . '\')" title="Lifecycle харах">';
                        $html .= '<i class="fa fa-eye"></i>';
                        $html .= '</a>';
                        $html .= '<a href="javascript:;" class="btn blue btn-xs" onclick="editLcBookLifecycle(\'' . $row['LC_BOOK_ID'] . '\', \'' . $r['LIFECYCLE_ID'] . '\')" title="Lifecycle book & lifecycle засах">';
                        $html .= '<i class="fa fa-edit"></i>';
                        $html .= '</a>';
                        $html .= '<a href="javascript:;" class="btn red btn-xs" onclick="deleteLcBookLifecycle(\'' . $row['DATA_MODEL_ID'] . '\', \'' . $row['LC_BOOK_ID'] . '\',  \'' . $r['LIFECYCLE_ID'] . '\')" title="Lifecycle book & lifecycle устгах">';
                        $html .= '<i class="fa fa-trash"></i>';
                        $html .= '</a>';
                        $html .= '</td>';
                        $html .= '</tr>';
                    } else {
                        if ($lifecycleCount > 1) {
                            $html .= '<tr class="lcbook tabletree-lcb-' . $row['LC_BOOK_ID'] . ' tabletree-parent- tabletree-collapsed lifecycleBook" data-row="lc-' . $row['LC_BOOK_ID'] . '" data-row-parent="">';
                            $html .= '<td class="middle"><input type="hidden" name="lcBookId[]" value="' . $row['LC_BOOK_ID'] . '"></td>';
                            $html .= '<td class="middle">' . $row['LC_BOOK_CODE'] . '</td>';
                            $html .= '<td class="middle">' . $row['LC_BOOK_NAME'] . '</td>';
                            $html .= '<td class="middle">';
                            $html .= '<a href="javascript:;" class="btn blue btn-xs" onclick="editLcBook(\'' . $row['LC_BOOK_ID'] . '\')" title="Lifecycle book засах"><i class="fa fa-edit"></i></a>';
                            $html .= '</td>';
                            $html .= '</tr>';
                            foreach ($lifecycle as $r) {
                                $html .= '<tr class="tabletree-lc-' . $r['LIFECYCLE_ID'] . ' tabletree-parent-lcb-' . $row['LC_BOOK_ID'] . ' tabletree-collapsed lifecycle" data-row="tabletree-lc-' . $r['LIFECYCLE_ID'] . '" data-row-parent="tabletree-parent-lcb-' . $row['LC_BOOK_ID'] . '">';
                                $html .= '<td class="middle"><input type="hidden" name="lifecycleId[]" value="' . $r['LIFECYCLE_ID'] . '"></td>';
                                $html .= '<td class="middle">' . $r['LIFECYCLE_CODE'] . '</td>';
                                $html .= '<td class="middle">' . $r['LIFECYCLE_NAME'] . '</td>';
                                $html .= '<td class="middle">';
                                $html .= '<a href="mdtaskflow/metaProcess/' . $dataModelId . '" target="_blank" class="btn green btn-xs"  title="Lifecycle харах">';
                                //$html .= '<a href="mdtaskflow/metaProcess/' . $dataModelId . '" class="btn green btn-xs" onclick="viewLifeCycle(\'' . $dataModelId . '\', \'' . $dataModelName . '\', \'' . $row['LC_BOOK_ID'] . '\', \'' . $row['LC_BOOK_NAME'] . '\', \'' . $r['LIFECYCLE_ID'] . '\', \'' . $r['LIFECYCLE_NAME'] . '\')"  title="Lifecycle харах">';
                                $html .= '<i class="fa fa-eye"></i>';
                                $html .= '</a>';
                                $html .= '<a href="javascript:;" class="btn blue btn-xs" onclick="editLifeCycle(\'' . $r['LIFECYCLE_ID'] . '\')" title="Lifecycle засах">';
                                $html .= '<i class="fa fa-edit"></i>';
                                $html .= '</a>';
                                $html .= '<a href="javascript:;" class="btn red btn-xs" onclick="deleteLifecycle(\'' . $r['LIFECYCLE_ID'] . '\')" title="Lifecycle устгах">';
                                $html .= '<i class="fa fa-trash"></i>';
                                $html .= '</a>';
                                $html .= '</td>';
                                $html .= '</tr>';
                            }
                        }
                    }
                }
            }
            return array('status' => 'success', 'result' => $html);
        }
        return array('status' => 'error', 'message' => 'Бичлэг олдсонгүй');
    }

    public function lifecycleListModel($dataModelId, $lcBookId) {
        $result = $this->db->GetAll("
            SELECT 
                LIFECYCLE_ID,
                LIFECYCLE_CODE,
                LIFECYCLE_NAME,
                PARENT_ID,
                LC_BOOK_ID,
                ORDER_NUM
            FROM META_DM_LIFECYCLE
            WHERE LC_BOOK_ID=$lcBookId
            ORDER BY ORDER_NUM ASC");
        // and DATA_MODEL_ID = $dataModelId 
        // өмнө оруулсан lifecycle - уудад datamodel id ороогүй байсан тул авсан
        if (count($result) > 0) {
            return $result;
        }
    }

    public function getlifeCycleBookModel($lcBookId) {
        $result = $this->db->GetRow("
            SELECT 
                MDLB.ID,
                MDLB.LC_BOOK_CODE,
                MDLB.LC_BOOK_NAME,
                MDLB.PARENT_ID,
                MDLB.IS_MAIN,
                MDLM.CRITERIA,
                MDLM.DATA_MODEL_ID
            FROM META_DM_LC_BOOK MDLB
                INNER JOIN META_DM_LIFECYCLE_MAP MDLM ON MDLB.ID = MDLM.LC_BOOK_ID
            WHERE MDLB.ID = $lcBookId");
        if (count($result) > 0) {
            return $result;
        }
        return array();
    }

    public function getlifeCycleModel($lifecycleId) {
        $result = $this->db->GetRow("
            SELECT 
                LIFECYCLE_ID,
                LIFECYCLE_CODE,
                LIFECYCLE_NAME,
                ORDER_NUM
            FROM META_DM_LIFECYCLE 
            WHERE LIFECYCLE_ID = $lifecycleId");
        if (count($result) > 0) {
            return $result;
        }
        return array();
    }

    public function initLifecycleBookListModel($dataModelId) {
        $result = $this->db->GetOne("
            SELECT 
                MDL.LIFECYCLE_ID,
                MDL.LIFECYCLE_NAME,
                MDLM.LC_BOOK_ID,
                MDLB.LC_BOOK_CODE,
                MDLB.LC_BOOK_NAME,
                MDLM.CRITERIA,
                MDLM.DATA_MODEL_ID
            FROM META_DM_LIFECYCLE_MAP MDLM
                INNER JOIN META_DM_LC_BOOK MDLB ON MDLM.LC_BOOK_ID = MDLB.ID
                INNER JOIN META_DM_LIFECYCLE MDL ON MDLB.ID = MDL.LC_BOOK_ID
            WHERE MDLM.DATA_MODEL_ID = $dataModelId");
        
        if (count($result) > 0) {
            return array('status' => 'success', 'result' => $result);
        }
        return array('status' => 'error', 'message' => 'Бичлэг олдсонгүй');
    }

    public function insertLifecycleBookModel() {
        $lcBookId = getUID();
        $postData = Input::postData();

        $data = array(
            'ID' => Input::param($lcBookId),
            'LC_BOOK_CODE' => $postData['lcBookCode'],
            'LC_BOOK_NAME' => $postData['lcBookName'],
            'PARENT_ID' => null,
            'IS_MAIN' => '0'
        );
        $resultLcBook = $this->db->AutoExecute('META_DM_LC_BOOK', $data);

        $lcData = $this->db->GetAll("
            SELECT 
                LIFECYCLE_ID 
            FROM META_DM_LIFECYCLE 
            WHERE LC_BOOK_ID = $lcBookId");

        if (empty($lcData)) {
            $data = array(
                'LIFECYCLE_ID' => Input::param(getUID()),
                'LIFECYCLE_CODE' => 'LC ' . $postData['lcBookCode'],
                'LIFECYCLE_NAME' => 'LC ' . $postData['lcBookName'],
                'DATA_MODEL_ID' => Input::param($postData['metaDataId']),
                'ORDER_NUM' => '1',
                'ADDON_DATA' => null,
                'LC_BOOK_ID' => Input::param($lcBookId)
            );
            $this->db->AutoExecute('META_DM_LIFECYCLE', $data);
        }

        $data = array(
            'ID' => Input::param(getUID()),
            'LC_BOOK_ID' => $lcBookId,
            'DATA_MODEL_ID' => $postData['metaDataId'],
            'CRITERIA' => Input::postNonTags('criteria')
        );
        $resultMap = $this->db->AutoExecute('META_DM_LIFECYCLE_MAP', $data);
        
        if ($resultMap && $resultLcBook) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function updateLifecycleBookModel() {
        $postData = Input::postData();
        $data = array(
            'LC_BOOK_CODE' => Input::param($postData['lcBookCode']),
            'LC_BOOK_NAME' => Input::param($postData['lcBookName']),
        );
        $result = $this->db->AutoExecute('META_DM_LC_BOOK', $data, 'UPDATE', 'ID=' . Input::param($postData['lcBookId']));

        $data = array(
            'CRITERIA' => Input::param($postData['criteria'])
        );
        $result = $this->db->AutoExecute('META_DM_LIFECYCLE_MAP', $data, 'UPDATE', 'DATA_MODEL_ID=' . Input::param($postData['metaDataId']) . ' AND LC_BOOK_ID=' . Input::param($postData['lcBookId']));
        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function deleteLcBookLifecycleModel() {

        $lcBookId = Input::post('lcBookId');
        $dataModelId = Input::post('dataModelId');
        $lifecycleId = Input::post('lifecycleId');
        $result = $this->db->GetAll("
            SELECT 
                LIFECYCLE_ID 
            FROM META_DM_LIFECYCLE_DTL 
            WHERE LIFECYCLE_ID = $lifecycleId");
        if (count($result) > 0) {
            return array('status' => 'error', 'message' => 'Устгах боломжгүй');
        } else {
            $result = $this->db->Execute("DELETE FROM META_DM_LIFECYCLE WHERE LIFECYCLE_ID = $lifecycleId");
            if ($result) {
                $result = $this->db->GetAll("
                    SELECT 
                        LIFECYCLE_ID 
                    FROM META_DM_LIFECYCLE 
                    WHERE LC_BOOK_ID = $lcBookId");
                if (count($result) > 0) {
                    return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
                } else {
                    $result = $this->db->Execute("DELETE FROM META_DM_LIFECYCLE_MAP WHERE LC_BOOK_ID = $lcBookId AND DATA_MODEL_ID=$dataModelId");
                    if ($result) {
                        $result = $this->db->Execute("DELETE FROM META_DM_LC_BOOK WHERE ID = $lcBookId");
                        return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
                    } else {
                        return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
                    }
                }
            } else {
                return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
            }
        }
        return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
    }

    public function deleteLifecycle() {
        $lcBookId = Input::post('lcBookId');
        $dataModelId = Input::post('dataModelId');
        $lifecycleId = Input::post('lifecycleId');
        $result = $this->db->GetAll("
                SELECT 
                    LIFECYCLE_ID 
                FROM META_DM_LIFECYCLE_DTL 
                WHERE LIFECYCLE_ID = $lifecycleId");
        if (count($result) > 0) {
            return array('status' => 'error', 'message' => 'Устгах боломжгүй');
        } else {
            $result = $this->db->Execute("DELETE FROM META_DM_LIFECYCLE WHERE LIFECYCLE_ID = $lifecycleId");
            if ($result) {
                return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
            } else {
                return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
            }
        }
    }

    public function updateLifecycleModel() {
        $postData = Input::postData();
        $data = array(
            'LIFECYCLE_CODE' => $postData['lifecycleCode'],
            'LIFECYCLE_NAME' => $postData['lifecycleName'],
        );
        $result = $this->db->AutoExecute('META_DM_LIFECYCLE', $data, 'UPDATE', 'LIFECYCLE_ID=' . $postData['lifecycleId']);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function getLcBookLifecycleModel($lcBookId, $lifecycleId) {
        $data = array();
        $getlcBook = self::getlifeCycleBookModel($lcBookId);
        $getLifecycle = self::getlifeCycleModel($lifecycleId);
        $data['lcBook'] = $getlcBook;
        $data['lifecycle'] = $getLifecycle;
        return $data;
    }

    public function updateLcBookLifecycleModel() {
        $postData = Input::postData();
        $data = array(
            'LIFECYCLE_CODE' => $postData['lifecycleCode'],
            'LIFECYCLE_NAME' => $postData['lifecycleName'],
        );
        $result = $this->db->AutoExecute('META_DM_LIFECYCLE', $data, 'UPDATE', 'LIFECYCLE_ID=' . $postData['lifecycleId']);

        $data = array(
            'LC_BOOK_CODE' => $postData['lcBookCode'],
            'LC_BOOK_NAME' => $postData['lcBookName'],
        );
        $result = $this->db->AutoExecute('META_DM_LC_BOOK', $data, 'UPDATE', 'ID=' . $postData['lcBookId']);

        $data = array(
            'CRITERIA' => $postData['criteria']
        );
        $result = $this->db->AutoExecute('META_DM_LIFECYCLE_MAP', $data, 'UPDATE', 'DATA_MODEL_ID=' . $postData['metaDataId'] . ' AND LC_BOOK_ID=' . $postData['lcBookId']);
        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function processTypeModel() {
        $result = $this->db->GetAll("
            SELECT
                MPCT.CONTENT_TYPE_ID,
                MPCT.CONTENT_TYPE_NAME,
                (SELECT COUNT(CONTENT_ID) FROM META_PROCESS_CONTENT WHERE CONTENT_TYPE_ID = MPCT.CONTENT_TYPE_ID) AS COUNT_ICON
            FROM META_PROCESS_CONTENT_TYPE MPCT");
        if ($result) {
            return $result;
        }
        return array();
    }

    public function processIconListModel($processTypeId) {
        $data = $this->db->GetAll("
            SELECT 
                CONTENT_ID,
                CONTENT_NAME,
                CONTENT_DATA,
                VIDEO_URL,
                CONTENT_TYPE
            FROM META_PROCESS_CONTENT
            WHERE IS_ACTIVE=1 AND CONTENT_TYPE_ID=$processTypeId");
        if ($data) {
            return $data;
        }
        return array();
    }

    public function saveProcessContentModel() {
        $contentData = $_POST['contentId'];
        $data = array();
        foreach ($contentData as $k => $row) {
            $data = array(
                'MAIN_META_DATA_ID' => Input::param(Input::numeric('metaDataId')),
                'CONTENT_ID' => Input::param($row),
                'WEB_URL' => Input::param($_POST['webUrl'][$k]),
                'URL_TARGET' => Input::param($_POST['urlTarget'][$k]),
                'URL_TYPE' => '',
                'ORDER_NUM' => Input::param($_POST['orderNum'][$k]),
                'POSITION_TYPE' => Input::param($_POST['positionType'][$k])
            );
            if (Input::param($_POST['rowId'][$k]) == '0') {

                $data = array_merge($data, array('ID' => Input::param(getUID())));
                $this->db->AutoExecute('META_PROCESS_CONTENT_MAP', $data);
            } else {
                $this->db->AutoExecute('META_PROCESS_CONTENT_MAP', $data, 'UPDATE', 'ID=' . Input::param($_POST['rowId'][$k]));
            }
        }
        return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
    }

    public function initProcessContentModel($metaDataId) {
        $html = '';
        $data = $this->db->GetAll("
            SELECT 
                MPCM.ID,
                MPCM.MAIN_META_DATA_ID,
                MPCM.CONTENT_ID,
                MPCM.WEB_URL,
                MPCM.URL_TARGET,
                MPCM.URL_TYPE,
                MPCM.ORDER_NUM,
                MPCM.POSITION_TYPE,
                MPC.CONTENT_TYPE,
                MPC.CONTENT_DATA,
                MPC.CONTENT_NAME
            FROM META_PROCESS_CONTENT_MAP MPCM
            INNER JOIN META_PROCESS_CONTENT MPC ON MPCM.CONTENT_ID = MPC.CONTENT_ID
            WHERE MPCM.MAIN_META_DATA_ID=$metaDataId");
        if (count($data) > 0) {
            $i = 1;
            foreach ($data as $k => $row) {
                $bannerPath = 'assets/custom/addon/img/process_content/' . $row['CONTENT_TYPE'] . '/' . $row['CONTENT_DATA'];
                $bannerPath = (strpos($row['CONTENT_DATA'], UPLOADPATH) !== false) ? $row['CONTENT_DATA'] : $bannerPath;
                
                $html .= '<tr>';
                $html .= '<td>';
                    $html .= $i;
                    $html .= '<input type="hidden" name="rowId[]" value="' . $row['ID'] . '">';
                    $html .= '<input type="hidden" name="contentId[]" value="' . $row['CONTENT_ID'] . '">';
                $html .= '</td>';
                $html .= '<td>';
                    $html .= '<a href="'. $bannerPath .'" data-fancybox="images"><img src="'. $bannerPath .'" class="d-block w-auto" alt="img name" style="max-height:45px;"/></a>    ';
                $html .= '</td>';
                $html .= '<td>' . $row['CONTENT_NAME'] . '</td>';
                $html .= '<td>';
                $html .=
                    Form::select(array(
                        'name' => 'positionType[]',
                        'class' => 'form-control select2',
                        'data' => array(
                            array('ID' => 'top', 'TITLE' => Lang::line('META_00131')),
                            array('ID' => 'right', 'TITLE' => Lang::line('META_00055')),
                            array('ID' => 'bottom', 'TITLE' => Lang::line('META_00054')),
                            array('ID' => 'left', 'TITLE' => Lang::line('META_00082'))
                        ),
                        'op_value' => 'ID',
                        'op_text' => 'TITLE',
                        'required' => 'required',
                        'value' => $row['POSITION_TYPE']
                    ));
                $html .= '</td>';
                $html .= '<td><input type="text" name="orderNum[]" class="form-control longInit" value="' . $row['ORDER_NUM'] . '"></td>';
                $html .= '<td><input type="text" name="webUrl[]" class="form-control" value="' . $row['WEB_URL'] . '"></td>';
                $html .= '<td>';
                $html .=
                        Form::select(array(
                            'name' => 'urlTarget[]',
                            'class' => 'form-control select2',
                            'data' => array(
                                array('ID' => '_blank', 'TITLE' => Lang::line('META_00167')),
                                array('ID' => '_parent', 'TITLE' => Lang::line('META_00016'))
                            ),
                            'op_value' => 'ID',
                            'op_text' => 'TITLE',
                            'required' => 'required',
                            'value' => $row['URL_TARGET']
                ));
                $html .= '</td>';
                $html .= '<td>';
                $html .= '<a href="javascript:;" class="btn red btn-xs" onclick="deleteProcessContent(this)"><i class="fa fa-trash"></i></a>';
                $html .= '</td>';
                $html .= '</tr>';
                $i++;
            }
            return $html;
        }
    }

    public function deleteProcessContentModel() {
        $result = $this->db->Execute("DELETE FROM META_PROCESS_CONTENT_MAP WHERE ID=" . Input::post('rowId'));
        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
        }
    }

    public function getProcessLookupFieldsMappingModel($mainMetaDataId, $fieldPath, $isKey = false) {

        if ($mainMetaDataId && $fieldPath) {

            if ($isKey) {
                $where = ' AND IS_KEY_LOOKUP = 1';
            } else {
                $where = ' AND (IS_KEY_LOOKUP = 0 OR IS_KEY_LOOKUP IS NULL)'; 
            }

            $data = $this->db->GetAll("
                SELECT 
                    LOOKUP_FIELD_PATH, 
                    PARAM_FIELD_PATH 
                FROM META_PROCESS_LOOKUP_MAP 
                WHERE (MAIN_META_DATA_ID = ".$this->db->Param(0)." OR PROCESS_META_DATA_ID = ".$this->db->Param(0).") 
                    $where 
                    AND LOWER(FIELD_PATH) = ".$this->db->Param(1), 
                array($mainMetaDataId, strtolower($fieldPath)) 
            ); 

        } else {
            $data = null;
        }

        return $data;
    }

    public function findParentMetaIdByMetaIdModel($metaDataId, $selectedMetaId) {
        $idPh = $this->db->Param(0);
        $getType = $this->db->GetOne("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = $idPh", array($metaDataId));

        if ($getType == Mdmetadata::$menuMetaTypeId) {
            $result = $this->db->GetAll("SELECT SRC_META_DATA_ID FROM META_META_MAP WHERE TRG_META_DATA_ID = $idPh", array($metaDataId));
            if ($result) {
                foreach ($result as $items) {
                    if ($items['SRC_META_DATA_ID'] == $selectedMetaId) {
                        return true;
                    } else {
                        if ($this->findParentMetaIdByMetaIdModel($items['SRC_META_DATA_ID'], $selectedMetaId)) {
                            return true;
                        }
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function internalProcessActionModel() {
        $postData = Input::postData();

        $status = 'success';
        $message = 'Амжилттай үүсгэлээ';
        
        if (count($postData['action']) > 0) {
            
            $i = 0;
            $folderId = Input::param($postData['folderId']);
            $metaDataId = Input::param($postData['metaDataId']);

            foreach ($postData['action'] as $k => $row) {
                
                $param = array(
                    'folderId' => $folderId,
                    'groupMetaDataId' => $metaDataId,
                    'action' => Input::param($k)
                );
                $data = $this->ws->runResponse(self::$gfServiceAddress, 'generate_standart_action', $param);

                if ($data['status'] == 'error') {
                    $status = 'error';
                    if (empty($i)) {
                        $message = '';
                    }
                    $message .= $data['text'] . '<br>';
                }
                $i++;
            }
            
            if ($status == 'success') {
                return array('status' => 'success', 'message' => $message);
            } else {
                return array('status' => 'error', 'message' => $message);
            }
        }
    }

    public function checkPasswordModel($metaDataId) {
        $row = $this->db->GetRow("SELECT PASSWORD_HASH FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

        if ($row && $row['PASSWORD_HASH'] != '') {
            return true;
        }
        return false;
    }

    public function checkPasswordProcessModel() {
        $mainMetaDataId = Input::numeric('mainMetaDataId');
        $processMetaDataId = Input::numeric('processMetaDataId');

        $getPassword = $this->db->GetRow("
            SELECT 
                PASSWORD_PATH
            FROM META_DM_PROCESS_DTL 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PROCESS_META_DATA_ID = ".$this->db->Param(1), 
            array($mainMetaDataId, $processMetaDataId)
        );     

        if ($getPassword && !empty($getPassword['PASSWORD_PATH'])) {
            return Str::lower($getPassword['PASSWORD_PATH']);
        }
        return false;
    }

    public function loginPasswordModel() {

        $metaDataId = Input::numeric('metaDataId');
        $passwordHash = Crypt::encrypt(Input::post('passwordHash'), 'md');

        $row = $this->db->GetRow("SELECT PASSWORD_HASH FROM META_DATA WHERE META_DATA_ID = $metaDataId AND PASSWORD_HASH = '$passwordHash'");

        if ($row) {
            return true;
        }
        return false;
    }

    public function metaUnLockModel() {

        $metaDataId = Input::numeric('metaDataId');
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $passwordHash = Input::post('passwordHash');
        $passwordOldHash = Hash::createMD5reverse($passwordHash);
        $passwordNewHash = Hash::create('sha256', $passwordHash);

        $row = $this->db->GetRow("
            SELECT 
                PASSWORD_HASH 
            FROM UM_META_LOCK 
            WHERE META_DATA_ID = ".$this->db->Param(0)." 
                AND (PASSWORD_HASH = ".$this->db->Param(1)." OR PASSWORD_HASH = ".$this->db->Param(2).")
                AND USER_ID = ".$this->db->Param(3), 
            array($metaDataId, $passwordOldHash, $passwordNewHash, $sessionUserKeyId)
        ); 

        if ($row) {
            return true;
        }
        return false;
    }
    
    public function metaUnLockPasswordResetModel() {
        
        try {
            
            $randStr     = Str::random_string('alnum', 4); 
            $newPassword = Hash::createMD5reverse($randStr);
            $userId      = Ue::sessionUserId();
            $userKeyId   = Ue::sessionUserKeyId();
            $metaDataId  = Input::numeric('metaDataId');
            $getPassMode = strtolower(Input::post('getPassMode'));
            $isByEmail = $isByPhoneNumber = true;
            
            $id1Ph = $this->db->Param(0);
            $id2Ph = $this->db->Param(1);
            
            $umMetaLockId = $this->db->GetOne("
                SELECT 
                    ID 
                FROM UM_META_LOCK 
                WHERE USER_ID = $id1Ph 
                    AND META_DATA_ID = $id2Ph", 
                array($userKeyId, $metaDataId)
            );

            if ($umMetaLockId) {

                $this->db->AutoExecute('UM_META_LOCK', array('PASSWORD_HASH' => $newPassword), 'UPDATE', 'ID = '.$umMetaLockId);

            } else {

                $data = array(
                    'ID'              => getUID(), 
                    'META_DATA_ID'    => $metaDataId, 
                    'USER_ID'         => $userKeyId, 
                    'PASSWORD_HASH'   => $newPassword, 
                    'CREATED_DATE'    => Date::currentDate('Y-m-d H:i:s'), 
                    'CREATED_USER_ID' => $userKeyId
                );
                $this->db->AutoExecute('UM_META_LOCK', $data);
            }
            
            $metaDataName = $this->db->GetOne("
                SELECT 
                    ".$this->db->IfNull('ML.GLOBE_CODE', 'MD.META_DATA_NAME')." AS META_DATA_NAME 
                FROM META_DATA MD 
                    LEFT JOIN META_MENU_LINK ML ON ML.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.META_DATA_ID = $id1Ph", 
                array($metaDataId)
            );
            
            $metaDataName = $this->lang->line($metaDataName);
            $email        = $this->db->GetOne("SELECT EMAIL FROM UM_SYSTEM_USER WHERE USER_ID = $id1Ph", array($userId));
            $body         = 'Veritech ERP системд '.$metaDataName.' - д нэвтрэх нууц үг: '.$randStr;
            
            if ($getPassMode == 'byemail') {
                $isByPhoneNumber = false;
            } elseif ($getPassMode == 'byphonenumber') {
                $isByEmail = false;
            }
            
            if ($isByEmail) {
                
                if ($getPassMode && !$email) {
                    return array('status' => 'error', 'message' => 'И-мейл хаяг бүртгэлгүй байна!');
                }
                
                includeLib('Mail/Mail');
                
                $mailResponse = Mail::sendPhpMailer(
                    array(
                        'subject' => 'Veritech ERP - '.$metaDataName, 
                        'altBody' => 'Veritech ERP - '.$metaDataName, 
                        'body'    => $body, 
                        'toMail'  => $email 
                    )
                );
                
                if ($mailResponse['status'] != 'success') {
                    
                    return $mailResponse;
                }
            }
            
            if ($isByPhoneNumber) {
                
                $phoneNumber = $this->db->GetOne("
                    SELECT 
                        ".$this->db->IfNull('EMP.EMPLOYEE_MOBILE', 'US.PHONE_NUMBER')." AS PHONE_NUMBER 
                    FROM UM_SYSTEM_USER US 
                        LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                        LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                    WHERE US.USER_ID = $id1Ph", array($userId));
                
                if ($getPassMode && !$phoneNumber) {
                    return array('status' => 'error', 'message' => 'Утасны дугаар бүртгэлгүй байна!');
                }
                
                if ($phoneNumber) {

                    $param = array(
                        'phoneNumber' => $phoneNumber, 
                        'msg'         => 'Veritech ERP systemd '.cyrillicToLatin($metaDataName).' -d nevtreh nuuts ug: '.$randStr 
                    );
                    $data = $this->ws->runResponse(self::$gfServiceAddress, 'SEND_SMS', $param);
                }
            }
            
            $message = 'Та и-мейл хаягаа шалгана уу. Хэрвээ Inbox фолдерт байхгүй бол SPAM, JUNK фолдероос шалгана уу.';
            
            if ($getPassMode == 'byphonenumber') {
                $message = $phoneNumber.' утасны дугаар руу нэвтрэх нууц үгийг илгээлээ.';
            } 
            
            return array('status' => 'success', 'message' => $message);
            
        } catch (Exception $ex) {
            return array('status' => 'warning', 'message' => $ex->getMessage());
        }
    }

    public function loginPasswordProcessModel() {

        $mainMetaDataId = Input::numeric('mainMetaDataId');
        $processMetaDataId = Input::numeric('processMetaDataId');

        $getPassword = $this->db->GetRow("
            SELECT 
                PASSWORD_PATH
            FROM META_DM_PROCESS_DTL 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PROCESS_META_DATA_ID = ".$this->db->Param(1), 
            array($mainMetaDataId, $processMetaDataId)
        );

        if ($getPassword) {
            return $getPassword['PASSWORD_PATH'];
        }
        return false;
    }
    
    public function checkPassPathDataViewProcessMapModel() {
        
        $getPasswordPath = self::loginPasswordProcessModel();
        
        if ($getPasswordPath) {
            
            $getPasswordPath = strtolower($getPasswordPath);
            $rows = Input::post('rows');
            
            if ($rows) {
                
                if (!isset($rows[0])) {
                    $rows = array($rows);
                }
                
                $rowData = $rows[0];
                
                if (isset($rowData[$getPasswordPath])) {
                    
                    $checkHash = $rowData[$getPasswordPath];
                    $passwordHash = Input::post('passwordHash');
                    $passwordHashOld = Hash::createMD5reverse($passwordHash);
                    $passwordHashNew = Hash::create('sha256', $passwordHash);
                    
                    if ($checkHash == $passwordHashOld || $checkHash == $passwordHashNew) {
                        $response = array('status' => 'success');
                    } else {
                        $response = array('status' => 'error', 'message' => 'Түлхүүр үг буруу байна!');
                    }
                    
                } else {
                    $response = array('status' => 'error', 'message' => 'Тохируулсан баганын дата олдсонгүй!');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Сонгосон мөр дата ирсэнгүй!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Passord path тохируулаагүй байна!');
        }
        
        return $response;
    }

    public function getGetDataProcessParamModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                PARAM_CODE, 
                PARAM_META_DATA_ID, 
                DEFAULT_VALUE 
            FROM META_PROCESS_DEFAULT_GET 
            WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
            ORDER BY ID ASC", array($metaDataId));

        return $data;
    }

    public function getLifeCycleBookListModel($dataModelId) {
        $result = $this->db->GetAll("
            SELECT 
                MDLB.ID, 
                MDLB.LC_BOOK_CODE,
                MDLB.LC_BOOK_NAME
            FROM META_DM_LIFECYCLE_MAP MDLM
                INNER JOIN META_DM_LC_BOOK MDLB ON MDLM.LC_BOOK_ID = MDLB.ID
            WHERE MDLM.DATA_MODEL_ID = " . $dataModelId);
        if (count($result) > 0) {
            return $result;
        }
        return array();
    }

    public function insertLifecycleModel() {
        $lcBookId = Input::post('lcBookId');
        $orderNum = 1;
        if ($lcBookId) {
            $result = $this->db->GetRow("SELECT ORDER_NUM FROM META_DM_LIFECYCLE where LC_BOOK_ID=$lcBookId ORDER BY ORDER_NUM DESC");
            if (count($result) > 0) {
                $orderNum = $result['ORDER_NUM'] + 1;
            }
        }
        $data = array(
            'LIFECYCLE_ID' => getUID(),
            'LIFECYCLE_CODE' => Input::post('lcCode'),
            'LIFECYCLE_NAME' => Input::post('lcName'),
            'ORDER_NUM' => Input::param($orderNum),
            'LC_BOOK_ID' => Input::param(Input::post('lcBookId'))
        );
        $result = $this->db->AutoExecute('META_DM_LIFECYCLE', $data);

        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
    }

    public function getWorkSpaceModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MWL.MENU_META_DATA_ID, 
                MMD.META_DATA_CODE AS MENU_DATA_CODE,
                MMD.META_DATA_NAME AS MENU_DATA_NAME,
                MWL.SUBMENU_META_DATA_ID,
                MMDS.META_DATA_CODE AS SUBMENU_DATA_CODE,
                MMDS.META_DATA_NAME AS SUBMENU_DATA_NAME,
                MWL.GROUP_META_DATA_ID, 
                MMGD.META_DATA_CODE AS GROUP_META_DATA_CODE,
                MMGD.META_DATA_NAME AS GROUP_META_DATA_NAME,
                MWL.THEME_CODE,
                MWL.DEFAULT_MENU_ID,
                MMDD.META_DATA_CODE AS DEFAULT_MENU_DATA_CODE,
                MMDD.META_DATA_NAME AS DEFAULT_MENU_DATA_NAME,
                MWL.WINDOW_HEIGHT, 
                MWL.WINDOW_SIZE, 
                MWL.WINDOW_TYPE, 
                MWL.WINDOW_WIDTH,
                MWL.IS_FLOW,
                MWL.USE_TOOLTIP,
                MWL.USE_PICTURE,
                MWL.USE_COVER_PICTURE,
                MWL.USE_MENU,
                MWL.CHECK_MODIFIED_CATCH,
                MWL.USE_LEFT_SIDE,
                MWL.ACTION_TYPE, 
                MWL.MOBILE_THEME, 
                MWL.ROW_DATAVIEW_ID,
                MWL.IS_LAST_VISIT_MENU, 
                RDMD.META_DATA_CODE AS ROW_DATA_CODE,
                RDMD.META_DATA_NAME AS ROW_DATA_NAME 
            FROM META_DATA MD 
                INNER JOIN META_WORKSPACE_LINK MWL ON MD.META_DATA_ID = MWL.META_DATA_ID 
                LEFT JOIN META_DATA MMD ON MWL.MENU_META_DATA_ID = MMD.META_DATA_ID 
                LEFT JOIN META_DATA MMDD ON MWL.DEFAULT_MENU_ID = MMDD.META_DATA_ID 
                LEFT JOIN META_DATA MMDS ON MWL.SUBMENU_META_DATA_ID = MMDS.META_DATA_ID 
                LEFT JOIN META_DATA MMGD ON MWL.GROUP_META_DATA_ID = MMGD.META_DATA_ID 
                LEFT JOIN META_DATA RDMD ON MWL.ROW_DATAVIEW_ID = RDMD.META_DATA_ID 
            WHERE MD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

        return $row;
    }

    public function getBackUpListModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                DESCRIPTION, 
                CREATED_DATE 
            FROM META_DATA_BACKUP 
            WHERE META_DATA_ID = ".$this->db->Param(0)." 
            ORDER BY CREATED_DATE DESC", 
            array($metaDataId)
        );

        return $data;
    }

    public function createConfigBackupModel() {
        
        try {
            
            $metaDataId = Input::numeric('metaDataId');
        
            $_POST['metaId'] = $metaDataId;

            $this->load->model('mdupgrade', 'middleware/models/');
            $exportData = $this->model->exportMetaModel();

            if ($exportData['status'] == 'success') {

                $data = array(
                    'ID' => getUID(), 
                    'META_DATA_ID' => $metaDataId, 
                    'DESCRIPTION' => Input::post('description'), 
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                    'CREATED_USER_ID' => Ue::sessionUserId()
                );

                $result = $this->db->AutoExecute('META_DATA_BACKUP', $data);

                if ($result) {
                    $this->db->UpdateClob('META_DATA_BACKUP', 'BACKUP_SRC', $exportData['result'], 'ID = ' . $data['ID']);
                }
                
                return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));

            } else {
                return array('status' => 'error', 'message' => $exportData['message']);
            }
        
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function restoreConfigBackUpModel() {

        try {
            
            $id = Input::numeric('id');
            $backupSrc = $this->db->GetOne("SELECT BACKUP_SRC FROM META_DATA_BACKUP WHERE ID = ".$this->db->Param(0), array($id));

            if ($backupSrc) {
                
                $param = array('encryptedSource' => $backupSrc); 
                
                $this->load->model('mdupgrade', 'middleware/models/');
                $result = $this->model->encryptedFileImportModel($param);
                
                return $result;
            }
        
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function getStatementReportGroupingModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                LG.GROUP_FIELD_PATH, 
                LG.GROUP_ORDER, 
                LG.GROUP_HEADER, 
                LG.GROUP_FOOTER, 
                LG.IS_USER_OPTION, 
                LG.HEADER_BG_COLOR, 
                LG.FOOTER_BG_COLOR
            FROM META_STATEMENT_LINK_GROUP LG 
                INNER JOIN META_STATEMENT_LINK SL ON SL.ID = LG.META_STATEMENT_LINK_ID 
            WHERE SL.META_DATA_ID = $metaDataId 
            ORDER BY LG.GROUP_ORDER ASC");

        return $data;
    }

    public function getProcessFieldListModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                PL.PARAM_REAL_PATH, 
                MD.META_DATA_NAME 
            FROM META_PROCESS_PARAM_ATTR_LINK PL 
            INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.PARAM_META_DATA_ID 
            WHERE PL.PROCESS_META_DATA_ID = $metaDataId 
                AND PL.PARENT_ID IS NULL 
                AND PL.IS_INPUT = 1");

        if ($data) {
            return $data;
        }
        return array();
    }

    public function insertProcessThemeFieldModel() {
        $data = array(
            'ID' => getUID(),
            'META_DATA_ID' => Input::numeric('metaDataId'),
            'THEME_FIELD' => Input::post('themeField'),
            'PROCESS_FIELD' => Input::post('processField'),
            'IS_LABEL' => Input::post('isLabel'),
            'TAB_NAME' => Input::post('tabName'),
            'ORDER_NUM' => Input::post('orderNum')
        );
        $result = $this->db->AutoExecute('META_PROCESS_THEME_FIELD_MAP', $data);

        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
    }

    public function initProcessThemeFieldModel($metaDataId) {
        $html = '';
        $data = $this->db->GetAll("
            SELECT 
                ID,
                THEME_FIELD, 
                PROCESS_FIELD,
                IS_LABEL,
                TAB_NAME,
                ORDER_NUM
            FROM META_PROCESS_THEME_FIELD_MAP 
            WHERE 
                META_DATA_ID = " . $metaDataId);
        if (count($data) > 0) {
            $i = 1;
            foreach ($data as $k => $row) {
                $html .= '<tr>';
                $html .= '<td><input type="hidden" name="rowId[]" value="' . $row['ID'] . '">' . $i . '</td>';
                $html .= '<td>' . $row['THEME_FIELD'] . '</td>';
                $html .= '<td>' . $row['PROCESS_FIELD'] . '</td>';
                $html .= '<td>' . $row['TAB_NAME'] . '</td>';
                $html .= '<td><div class="checker" id="uniform-isLabel"><span  class="' . ($row['IS_LABEL'] == '1' ? 'checked' : '') . '"><input type="checkbox" id="isLabel" name="isLabel" class="form-control" value="' . $row['IS_LABEL'] . '" onclick="isLabelField(this);"></span></div></td>';
                $html .= '<td>'.($row['TAB_NAME']!= '' ? '<input type="text" name="orderNum[]" class="form-control form-control-sm longInit text-center" value="'.$row['ORDER_NUM'].'" onchange="updateOrderNumField(this);">' : '').'</td>';
                $html .= '<td><a href="javascript:;" class="btn red btn-xs" onclick="deleteProcessThemeField(this)"><i class="fa fa-trash"></i></a></td>';
                $html .= '</tr>';
                $i++;
            }
        }
        return $html;
    }

    public function deleteProcessThemeFieldModel() {
        $result = $this->db->Execute("DELETE FROM META_PROCESS_THEME_FIELD_MAP WHERE ID=" . Input::post('rowId'));
        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
        }
    }

    public function updateProcessThemeFieldIsLabelModel($rowId, $isLabel){
        $data = array('IS_LABEL' => $isLabel);
        $result = $this->db->AutoExecute('META_PROCESS_THEME_FIELD_MAP', $data, 'UPDATE', 'ID=' . $rowId);
        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
    }

    public function updateProcessThemeFieldOrderNumModel($rowId, $orderNum){
        $data = array('ORDER_NUM' => $orderNum);
        $result = $this->db->AutoExecute('META_PROCESS_THEME_FIELD_MAP', $data, 'UPDATE', 'ID=' . $rowId);
        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
    }

    public function getProcessThemeFieldOrderNumModel($rowId) {
        $result = $this->db->GetOne("SELECT ORDER_NUM FROM META_PROCESS_THEME_FIELD_MAP WHERE ID=$rowId");
        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'), 'value'=>$result);
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'), 'value'=>0);
        }
    }

    public function getMetaTypeModel($metaDataId) {
        return $this->db->GetOne("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }

    public function isUsedMetaModel($metaDataId) {
        $result = $this->ws->runResponse(self::$gfServiceAddress, 'IS_USED_META', array('id' => Input::param($metaDataId)));

        if (isset($result['result'])) {
            return $result['result'];
        }
        return 'true';
    }

    public function getMetaProcessByMetaSingleDatasModel($processId, $parentId = '') {
        $html = array();

        if ($parentId === '') {
            $data = $this->db->GetAll("
                SELECT 
                    ID, 
                    PARENT_ID, 
                    LABEL_NAME, 
                    PARAM_REAL_PATH, 
                    DATA_TYPE, 
                    RECORD_TYPE 
                FROM META_PROCESS_PARAM_ATTR_LINK  
                WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)."
                    AND IS_INPUT = 1 
                    AND PARENT_ID IS NULL 
                ORDER BY ORDER_NUMBER ASC", array($processId));
        } else {
            $data = $this->db->GetAll("
                SELECT 
                    ID, 
                    PARENT_ID, 
                    LABEL_NAME, 
                    PARAM_REAL_PATH, 
                    DATA_TYPE, 
                    RECORD_TYPE 
                FROM META_PROCESS_PARAM_ATTR_LINK 
                WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                    AND PARENT_ID = ".$this->db->Param(1)." 
                    AND IS_INPUT = 1 
                ORDER BY ORDER_NUMBER ASC", array($processId, $parentId));
        }

        foreach ($data as $meta) {

            $paramPath = $meta['PARAM_REAL_PATH'];
            $labelName = Lang::line($meta['LABEL_NAME']);

            if ($meta['DATA_TYPE'] == 'group') {
                
                $html[] = '<tr class="meta-by-group ' . $meta['DATA_TYPE'] . '-type-code" data-meta-code="' . $paramPath . '" data-clipboard-text="' . $paramPath . '">';
                $html[] = '<td style="white-space: nowrap;">
                                <strong title="' . $labelName . '">' . $paramPath . '</strong>
                              </td>
                              <td style="white-space: nowrap;">
                                ' . $labelName . '
                              </td>
                              <td>
                                ' . $meta['RECORD_TYPE'] . '
                              </td>
                            </tr>';
                $html[] = self::getMetaProcessByMetaSingleDatasModel($processId, $meta['ID']);
                
            } else {
                
                $html[] = '<tr class="meta-by-group ' . $meta['DATA_TYPE'] . '-type-code" data-meta-code="' . $paramPath . '" data-clipboard-text="' . $paramPath . '">';
                $html[] = '<td style="white-space: nowrap;">
                                <span title="'.$labelName.'">' . $paramPath . '</span>
                              </td>
                              <td style="white-space: nowrap;">
                                ' . $labelName . '
                              </td>
                              <td>
                                ' . $meta['DATA_TYPE'] . '
                              </td>
                          </tr>';
            }
        }

        return implode('', $html);
    }

    public function deleteLayoutLinkParamMapModel($metaLayoutParamMapId) {

        $data = array(
            'BP_META_DATA_ID' => null,
            'LABEL_NAME' => '',
            'BACKGROUND_COLOR' => '',
            'FONT_COLOR' => ''
        );

        $result = $this->db->AutoExecute('META_LAYOUT_PARAM_MAP', $data, 'UPDATE', 'ID=' . $metaLayoutParamMapId);

        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
        }
    }

    public function deleteGoogleMapLinkModel($metaGoogleMapLinkId) {
        $result = $this->db->Execute("DELETE FROM META_GOOGLE_MAP_PARAM WHERE GOOGLE_MAP_LINK_ID = " . $metaGoogleMapLinkId);
        if ($result) {
            $this->db->Execute("DELETE FROM META_GOOGLE_MAP_LINK WHERE ID=" . $metaGoogleMapLinkId);
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
        }
    }

    public function initGoogleMapLinkModel($metaDataId) {
        $html = '';
        $result = $this->db->GetAll("
            SELECT 
                MGML.ID,
                MGML.META_DATA_ID,
                MGML.LIST_META_DATA_ID,
                MD.META_DATA_NAME AS LIST_META_DATA_NAME,
                MGML.DISPLAY_COLOR,
                MGML.ACTION_META_DATA_ID,
                AMD.META_DATA_NAME AS ACTION_META_DATA_NAME,
                MGML.ACTION_META_TYPE_ID,
                MGML.ICON_NAME,
                MGML.ORDER_NUM,
                MGML.SERVICE_URL,
                MGML.IS_DYNAMIC,
                MGML.SERVICE_NAME
            FROM META_GOOGLE_MAP_LINK MGML 
                LEFT JOIN META_DATA MD ON MGML.LIST_META_DATA_ID = MD.META_DATA_ID
                LEFT JOIN META_DATA AMD ON MGML.ACTION_META_DATA_ID = AMD.META_DATA_ID
            WHERE MGML.META_DATA_ID = $metaDataId
            ORDER BY MGML.ORDER_NUM ASC");

        $i = 1;
        foreach ($result as $k=>$row) {
            $html .= '<tr>';
            $html .= '<td>';
            $html .= $i;
            $html .= '<input type="hidden" name="metaGoogleMapLinkId[]" value="' . $row['ID'] . '">';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<div class="input-group">';
            $html .= '<input type="hidden" name="listMetaDataId[]" value="' . $row['LIST_META_DATA_ID'] . '">';
            $html .= '<input type="text" value="' . $row['LIST_META_DATA_NAME'] . '" name="listMetaDataName[]" class="form-control form-control-sm" style="min-width: 150px;">';
            $html .= '<span class="input-group-btn"><button type="button" class="btn blue form-control-sm mr0" onclick="commonMetaDataGrid(\'single\', \'metaObject\', \'autoSearch=1&metaTypeId=' . Mdmetadata::$metaGroupMetaTypeId . '\', \'chooseListMetaData\', this);"><i class="fa fa-search"></i></button></span>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '<td class="text-center">'
                    . '<input type="hidden" value="' . $row['DISPLAY_COLOR'] . '" name="displayColor[]">'
                    . '<input type="hidden" value="' . $row['ICON_NAME'] . '" name="iconName[]">'
                    . '<span class="markerImg">' . Mdcommon::svgIconByColor($row['DISPLAY_COLOR'], $row['ICON_NAME'], false) . '</span>'
                    . '<button type="button" class="btn btn-sm blue-hoki ml5 float-right" onclick="chooseIcon(this)">...</button>'
                    . '</td>';
            $html .= '<td>';
            $html .= '<div class="input-group">';
            $html .= '<input type="hidden" name="actionMetaDataId[]" value="' . $row['ACTION_META_DATA_ID'] . '">';
            $html .= '<input type="hidden" name="actionMetaTypeId[]" value="' . $row['ACTION_META_TYPE_ID'] . '">';
            $html .= '<input type="text" value="' . $row['ACTION_META_DATA_NAME'] . '" name="actionMetaDataName[]" class="form-control form-control-sm" style="min-width: 150px;">';
            $html .= '<span class="input-group-btn">';
            $html .= '<button type="button" class="btn blue form-control-sm mr0" onclick="commonMetaDataGrid(\'single\', \'metaObject\', \'autoSearch=1&metaTypeId=' . Mdmetadata::$metaGroupMetaTypeId . '|' . Mdmetadata::$diagramMetaTypeId . '|' . Mdmetadata::$googleMapMetaTypeId . '|' . Mdmetadata::$businessProcessMetaTypeId . '|'.Mdmetadata::$layoutMetaTypeId.'\', \'chooseActionMetaData\', this);"><i class="fa fa-search"></i></button>';
            $html .= '<button type="button" class="btn purple-plum form-control-sm mr0" onclick="googleMapParam(this)" title="Параметр тохируулах">...</button>';
            $html .= '</span>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '<td class="text-center">';
            $html .= '<input type="hidden" name="isDynamic[]" value="' . $row['IS_DYNAMIC'] . '">';
            $html .= Form::checkbox(array('onclick'=>'isDymanic(this);', 'saved_val'=> '1', 'value'=> $row['IS_DYNAMIC']));
            $html .= '</td>';
            $html .= '<td><input type="text" class="form-control form-control-sm" name="serviceName[]" value="' . $row['SERVICE_NAME'] . '" style="width: 100%;"></td>';
            $html .= '<td><input type="text" class="form-control form-control-sm" name="serviceUrl[]" value="' . $row['SERVICE_URL'] . '" style="width: 100%;"></td>';
            $html .= '<td><input type="text" class="form-control form-control-sm" value="' . $row['ORDER_NUM'] . '" name="orderNum[]" style="width: 50px;"></td>';
            $html .= '<td><button type="button" class="btn btn-sm red-sunglo" onclick="removeMetaGoogleMapLink(this);"><i class="fa fa-trash"></i></button></td>';
            $html .= '</tr>';
            $i++;
        }

        return $html;
    }

    public function insertGoogleMapLinkModel() {
        $data = array();
        foreach ($_POST['displayColor'] as $k => $row) {
            $data[$k]['ID'] = (Input::param($_POST['metaGoogleMapLinkId'][$k]) != null ? Input::param($_POST['metaGoogleMapLinkId'][$k]) : getUID());
            $data[$k]['META_DATA_ID'] =  Input::numeric('metaDataId');
            $data[$k]['LIST_META_DATA_ID'] = Input::param($_POST['listMetaDataId'][$k]);
            $data[$k]['DISPLAY_COLOR'] = trim(Input::param($_POST['displayColor'][$k]),"#");
            $data[$k]['ORDER_NUM'] = Input::param($_POST['orderNum'][$k]);
            $data[$k]['ACTION_META_DATA_ID'] = Input::param($_POST['actionMetaDataId'][$k]);
            $data[$k]['ACTION_META_TYPE_ID'] = Input::param($_POST['actionMetaTypeId'][$k]);
            $data[$k]['ICON_NAME'] = Input::param($_POST['iconName'][$k]);
            $data[$k]['SERVICE_URL'] = Input::param($_POST['serviceUrl'][$k]);
            $data[$k]['IS_DYNAMIC'] = Input::param($_POST['isDynamic'][$k]);
            $data[$k]['SERVICE_NAME'] = Input::param($_POST['serviceName'][$k]);
            $data[$k]['PARAM'] = false;
            if (Input::param($_POST['metaGoogleMapLinkId'][$k]) != null) {
                $googleMapLinkParam = $this->db->GetAll('
                    SELECT 
                        ID,
                        GOOGLE_MAP_LINK_ID,
                        SRC_PARAM,
                        TRG_PARAM,
                        DEFAULT_VALUE
                    FROM 
                        META_GOOGLE_MAP_PARAM
                    WHERE GOOGLE_MAP_LINK_ID = ' . Input::param($_POST['metaGoogleMapLinkId'][$k]));
                $data[$k]['PARAM'] = $googleMapLinkParam;
            }
        }
        $googleMapLink = $this->db->GetAll('
            SELECT 
                ID
            FROM 
                META_GOOGLE_MAP_LINK
            WHERE META_DATA_ID = ' . Input::numeric('metaDataId'));
        foreach ($googleMapLink as $k => $row) {
            $this->db->Execute("DELETE FROM META_GOOGLE_MAP_PARAM WHERE GOOGLE_MAP_LINK_ID = " . $row['ID']);
            $this->db->Execute("DELETE FROM META_GOOGLE_MAP_LINK WHERE ID = " . $row['ID']);
        }
        foreach ($data as $k => $row) {
            $insertData = array(
                'ID' => $row['ID'],
                'META_DATA_ID' =>  $row['META_DATA_ID'],
                'LIST_META_DATA_ID' => $row['LIST_META_DATA_ID'],
                'DISPLAY_COLOR' => $row['DISPLAY_COLOR'],
                'ORDER_NUM' => $row['ORDER_NUM'],
                'ACTION_META_DATA_ID' => $row['ACTION_META_DATA_ID'],
                'ACTION_META_TYPE_ID' => $row['ACTION_META_TYPE_ID'],
                'ICON_NAME' => $row['ICON_NAME'],
                'SERVICE_URL' => $row['SERVICE_URL'],
                'IS_DYNAMIC' => $row['IS_DYNAMIC'],
                'SERVICE_NAME' => $row['SERVICE_NAME']
            );
            $this->db->AutoExecute('META_GOOGLE_MAP_LINK', $insertData);

            if ($row['PARAM']) {
                foreach ($row['PARAM'] as $kk => $krow) {
                    $dataParam = array(
                        'ID' => $krow['ID'],
                        'GOOGLE_MAP_LINK_ID' =>  $krow['GOOGLE_MAP_LINK_ID'],
                        'SRC_PARAM' => $krow['SRC_PARAM'],
                        'TRG_PARAM' => $krow['TRG_PARAM'],
                        'DEFAULT_VALUE' => $krow['DEFAULT_VALUE'],
                    );
                    $this->db->AutoExecute('META_GOOGLE_MAP_PARAM', $dataParam);    
                }
            }
        }
        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    public function insertGoogleMapParamModel() {
        $this->db->Execute("DELETE FROM META_GOOGLE_MAP_PARAM WHERE GOOGLE_MAP_LINK_ID=" . Input::post('googleMapLinkId'));
        foreach ($_POST['srcParam'] as $k => $row) {
            $data = array(
                'ID' => getUID(),
                'GOOGLE_MAP_LINK_ID' =>  Input::post('googleMapLinkId'),
                'SRC_PARAM' => Input::param($_POST['srcParam'][$k]),
                'TRG_PARAM' => trim(Input::param($_POST['trgParam'][$k]),"#"),
                'DEFAULT_VALUE' => '',
            );
            $result = $this->db->AutoExecute('META_GOOGLE_MAP_PARAM', $data);
        }
        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    public function deleteGoogleMapParamModel() {
        $data = array(
            'TRG_PARAM' => null,
            'DEFAULT_VALUE' => null
        );
        $result = $this->db->AutoExecute('META_GOOGLE_MAP_PARAM', $data, 'UPDATE', 'ID=' . Input::post('googleMapParamId'));
        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
    }

    public function googleMapParamDataModel($metaDataId, $actionMetaTypeId = '') {

        if ($actionMetaTypeId == Mdmetadata::$businessProcessMetaTypeId) {

            $result = $this->db->GetAll("
                SELECT 
                    LABEL_NAME AS META_DATA_NAME,
                    PARAM_REAL_PATH AS FIELD_PATH
                FROM META_PROCESS_PARAM_ATTR_LINK  
                WHERE PROCESS_META_DATA_ID = " . $metaDataId . "
                GROUP BY 
                    LABEL_NAME, 
                    PARAM_REAL_PATH 
                ORDER BY PARAM_REAL_PATH ASC");

        } else {
            $result = $this->db->GetAll("
                SELECT 
                    LABEL_NAME AS META_DATA_NAME,
                    FIELD_PATH
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = " . $metaDataId . "
                ORDER BY FIELD_PATH ASC");
        }


        if ($result) {
            return $result;
        }
        return false;
    }

    public function googleMapParamSetDataModel($listData, $googleMapLinkId = '') {
        $data = array();
        if ($googleMapLinkId != '') {
            $result = $this->db->GetAll("
                SELECT 
                    ID,
                    SRC_PARAM, 
                    TRG_PARAM, 
                    DEFAULT_VALUE 
                FROM 
                    META_GOOGLE_MAP_PARAM 
                WHERE 
                    GOOGLE_MAP_LINK_ID = $googleMapLinkId");
            foreach ($listData as $k => $row) {
                $data[$k]['GOOGLE_MAP_PARAM_ID'] = '';
                $data[$k]['META_DATA_ID'] = $row['META_DATA_ID'];
                $data[$k]['META_DATA_NAME'] = $row['META_DATA_NAME'];
                $data[$k]['FIELD_PATH'] = $row['FIELD_PATH'];
                $data[$k]['TRG_PARAM'] = '';
                $data[$k]['DEFAULT_VALUE'] = '';
                foreach ($result as $val) {
                    $data[$k]['GOOGLE_MAP_PARAM_ID'] = $val['ID'];
                    if ($row['FIELD_PATH'] == $val['SRC_PARAM']) {
                        $data[$k]['TRG_PARAM'] = $val['TRG_PARAM'];
                        $data[$k]['DEFAULT_VALUE'] = $val['DEFAULT_VALUE'];
                    }
                }
            }
            return $data;    
        }
        return $listData;
    }

    public function googleMapParamHtmlModel($listMetaDataId, $actionMetaDataId, $googleMapLinkId, $actionMetaTypeId) {
        $trgParamData = self::googleMapParamDataModel($actionMetaDataId, $actionMetaTypeId);
        $result = self::googleMapParamSetDataModel(self::googleMapParamDataModel($listMetaDataId), $googleMapLinkId);
        $html = '';
        if (count($result) > 0) {
            $j = 0;
            foreach ($result as $k => $row) {
                $j++;
                $html .= '<tr>';
                    $html .= '<td>' . $j . Form::hidden(array('value'=> $row['GOOGLE_MAP_PARAM_ID'], 'name'=>'googleMapParamId[]')) . '</td>';
                    $html .= '<td>' . Form::text(array('value'=> $row['META_DATA_NAME'], 'readonly' => true, 'class' => 'form-control form-control-sm')) . '</td>';
                    $html .= '<td>' . Form::text(array('name'=> 'srcParam[]', 'value'=> $row['FIELD_PATH'], 'readonly' => true, 'class' => 'form-control form-control-sm')) . '</td>';
                    $html .= '<td>' . Form::select(array(
                                        'name' => 'trgParam[]',
                                        'class' => 'form-control form-control-sm select2',
                                        'data' => $trgParamData,
                                        'op_value' => 'FIELD_PATH',
                                        'op_text' => 'META_DATA_NAME| |-| |FIELD_PATH',
                                        'value' => $row['TRG_PARAM']
                                    )) . '</td>';
                    $html .= '<td class="text-center"><button type="button" class="btn btn-sm red-sunglo" onclick="removeGoogleMapParam(this);"><i class="fa fa-trash"></i></button></td>';
                $html .= '</tr>';
            }
            return $html;
        }
        return false;
    }

    public function getGoogleMapParamModel($metaGoogleMapLinkId) {
        $result = $this->db->GetAll("
            SELECT 
                SRC_PARAM,
                TRG_PARAM,
                DEFAULT_VALUE
            FROM META_GOOGLE_MAP_PARAM 
            WHERE GOOGLE_MAP_LINK_ID = $metaGoogleMapLinkId 
                AND (TRG_PARAM IS NOT NULL OR DEFAULT_VALUE IS NOT NULL)");
        
        if ($result) {
            return $result;
        }
        return false;
    }

    public function saveChangeMetaFolderModel($folderId, $metaDataId, $metaDataIds) {

        if ($metaDataIds) {
            $metaDataIdData = $metaDataIds;
        } else {
            $metaDataIdData = array($metaDataId);
        }

        $this->load->model('mdmetadata', 'middleware/models/');

        foreach ($metaDataIdData as $mId) {    
            
            $mId = Input::param($mId);
            $this->model->clearMetaFolderMap($mId);

            $dataFolder = array(
                'ID'           => getUID(),
                'FOLDER_ID'    => $folderId,
                'META_DATA_ID' => $mId
            );
            $this->db->AutoExecute('META_DATA_FOLDER_MAP', $dataFolder);
        }

        return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
    }

    public function getBPFullExpressionByConfigModel($metaDataId) {

        $row = $this->db->GetRow("
            SELECT 
                ED.ID AS CONFIG_ID,  
                PL.META_DATA_ID, 
                ED.EVENT_EXPRESSION_STRING, 
                ED.LOAD_EXPRESSION_STRING, 
                ED.VAR_FNC_EXPRESSION_STRING, 
                ED.SAVE_EXPRESSION_STRING, 
                MD.META_DATA_NAME, 
                PL.INPUT_META_DATA_ID, 
                PL.ACTION_TYPE, 
                ED.TITLE 
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                INNER JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
            WHERE PL.META_DATA_ID = ".$this->db->Param(0)." 
                AND EX.IS_DEFAULT = 1", 
            array($metaDataId));

        if ($row) {
            
            $row['cacheExp'] = self::getBPFullExpCacheByVersionModel($row['CONFIG_ID']);
            return $row;
            
        } else {
            
            $row = self::getBPFullExpressionModel($metaDataId);
            $row['cacheExp'] = self::getBPFullExpCacheModel($row['ID']);
            
            return $row;
        }
    }
    
    public function getBPFullExpCacheByVersionModel($versionId) {

        $data = $this->db->GetAll("
            SELECT 
                ID, 
                RUN_MODE, 
                GROUP_PATH, 
                CODE, 
                DESCRIPTION, 
                EXPRESSION_STRING 
            FROM META_BP_EXP_CACHE_VERSION  
            WHERE VERSION_ID = ".$this->db->Param(0)."  
            ORDER BY CREATED_DATE ASC", 
            array($versionId)
        );
        
        return $data;
    }
    
    public function getBPFullExpCacheModel($linkId) {

        $data = $this->db->GetAll("
            SELECT 
                ID, 
                RUN_MODE, 
                GROUP_PATH, 
                CODE, 
                DESCRIPTION, 
                EXPRESSION_STRING 
            FROM META_BP_EXP_CACHE   
            WHERE BP_LINK_ID = ".$this->db->Param(0)."  
            ORDER BY CREATED_DATE ASC", 
            array($linkId)
        );
        
        return $data;
    }

    public function getBPFullExpressionByVersionModel($versionId) {

        $row = $this->db->GetRow("
            SELECT 
                ED.ID AS CONFIG_ID,  
                ED.TITLE, 
                ED.DESCRIPTION,  
                PL.META_DATA_ID, 
                ED.EVENT_EXPRESSION_STRING, 
                ED.LOAD_EXPRESSION_STRING, 
                ED.VAR_FNC_EXPRESSION_STRING, 
                ED.SAVE_EXPRESSION_STRING, 
                MD.META_DATA_NAME, 
                EX.IS_DEFAULT 
            FROM META_BUSINESS_PROCESS_LINK PL  
                INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                LEFT JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
            WHERE ED.ID = " . $this->db->Param(0), 
            array($versionId)
        );
        
        $row['cacheExp'] = self::getBPFullExpCacheByVersionModel($versionId);
        
        return $row;
    }

    public function getBPFullExpressionModel($metaDataId) {

        $row = $this->db->GetRow("
            SELECT 
                BP.ID, 
                BP.LOAD_EXPRESSION_STRING,
                BP.EVENT_EXPRESSION_STRING, 
                BP.VAR_FNC_EXPRESSION_STRING, 
                BP.SAVE_EXPRESSION_STRING, 
                BP.ACTION_TYPE, 
                MD.META_DATA_NAME 
            FROM META_BUSINESS_PROCESS_LINK BP 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.META_DATA_ID 
            WHERE BP.META_DATA_ID = " . $this->db->Param(0), 
            array($metaDataId)
        );

        if ($row) {
            return $row;
        }

        return null;
    }

    public function saveFullExpressionModel() {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaDataId = Input::numeric('bpExpKeyMetaId');
        $checkLock = $this->model->checkMetaLock($metaDataId);

        if ($checkLock) {
            return $checkLock;
        }

        if (Input::postCheck('fullExpressionString_set') && Input::isEmpty('configId') == true) {

            try {
                $result = $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'EVENT_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionString_set']), 'META_DATA_ID = ' . $metaDataId); 

                if ($result) {
                    $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'LOAD_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionOpenCriteria_set']), 'META_DATA_ID = ' . $metaDataId); 
                    $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'VAR_FNC_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringVarFnc_set']), 'META_DATA_ID = ' . $metaDataId);

                    $afterSave = ' ';

                    if (!empty($_POST['fullExpressionStringAfterSave_set'])) {
                        $afterSave = "\n".'startAfterSave '.Input::param($_POST['fullExpressionStringAfterSave_set']).' endAfterSave';
                    }

                    $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'SAVE_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringSave_set']).$afterSave, 'META_DATA_ID = ' . $metaDataId); 
                    
                    if (Input::postCheck('cacheId')) {
                        
                        $cacheIdData = $_POST['cacheId'];
                        $currentDate = Date::currentDate('Y-m-d H:i:s');
                        $sessionUserKeyId = Ue::sessionUserKeyId();
                        $bpLinkId = $this->db->GetOne("SELECT ID FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = ".$metaDataId);
                        
                        foreach ($cacheIdData as $k => $v) {
                            
                            if ($v == '') {
                                
                                $cacheId = getUID();
                                $cacheData = array(
                                    'ID' => $cacheId, 
                                    'BP_LINK_ID' => $bpLinkId, 
                                    'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                    'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                    'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                    'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k]),  
                                    'CREATED_USER_ID' => $sessionUserKeyId, 
                                    'CREATED_DATE' => $currentDate
                                );
                                $cacheResult = $this->db->AutoExecute('META_BP_EXP_CACHE', $cacheData);
                                
                            } else {
                                
                                $cacheId = $v;
                                
                                if (Input::param($_POST['cacheRowDelete'][$k]) == 'deleted') {
                                    
                                    $this->db->Execute("DELETE FROM META_BP_EXP_CACHE WHERE ID = $cacheId");
                                    $cacheResult = false;
                                    
                                } else {
                                    
                                    $cacheData = array(
                                        'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                        'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                        'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                        'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k])
                                    );
                                    
                                    $cacheResult = $this->db->AutoExecute('META_BP_EXP_CACHE', $cacheData, 'UPDATE', 'ID = '.$cacheId);
                                }
                            }
                            
                            if ($cacheResult) {
                                $this->db->UpdateClob('META_BP_EXP_CACHE', 'EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['cacheExpression'][$k]), 'ID = ' . $cacheId); 
                            }
                        }
                    }

                    Mdmeta::bpOnlyExpressionClearCache($metaDataId);
                    (new Mdmeta())->bpFullExpressionUseProcess($metaDataId);
                    (new Mdmeta())->setMetaModifiedDate($metaDataId);
                }

                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

            } catch (Exception $ex) {
                return array('status' => 'error', 'message' => $ex->getMessage());
            }

        } elseif (Input::isEmpty('configId') == false) {

            $configId = Input::post('configId');

            try {
                $result = $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'EVENT_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionString_set']), 'ID = ' . $configId); 

                if ($result) {
                    $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'LOAD_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionOpenCriteria_set']), 'ID = ' . $configId); 
                    $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'VAR_FNC_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringVarFnc_set']), 'ID = ' . $configId); 

                    $afterSave = ' ';

                    if (!empty($_POST['fullExpressionStringAfterSave_set'])) {
                        $afterSave = "\n".'startAfterSave '.Input::paramWithDoubleSpace($_POST['fullExpressionStringAfterSave_set']).' endAfterSave';
                    }

                    $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'SAVE_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringSave_set']).$afterSave, 'ID = ' . $configId); 
                    
                    if (Input::postCheck('cacheId')) {
                        
                        $cacheIdData = $_POST['cacheId'];
                        $currentDate = Date::currentDate('Y-m-d H:i:s');
                        $sessionUserKeyId = Ue::sessionUserKeyId();
                        
                        foreach ($cacheIdData as $k => $v) {
                            
                            if ($v == '') {
                                
                                $cacheId = getUID();
                                $cacheData = array(
                                    'ID' => $cacheId, 
                                    'VERSION_ID' => $configId, 
                                    'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                    'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                    'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                    'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k]),  
                                    'CREATED_USER_ID' => $sessionUserKeyId, 
                                    'CREATED_DATE' => $currentDate
                                );
                                $cacheResult = $this->db->AutoExecute('META_BP_EXP_CACHE_VERSION', $cacheData);
                                
                            } else {
                                
                                $cacheId = $v;
                                
                                if (Input::param($_POST['cacheRowDelete'][$k]) == 'deleted') {
                                    
                                    $this->db->Execute("DELETE FROM META_BP_EXP_CACHE_VERSION WHERE ID = $cacheId");
                                    $cacheResult = false;
                                    
                                } else {
                                    
                                    $cacheData = array(
                                        'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                        'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                        'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                        'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k])
                                    );
                                    
                                    $cacheResult = $this->db->AutoExecute('META_BP_EXP_CACHE_VERSION', $cacheData, 'UPDATE', 'ID = '.$cacheId);
                                }
                            }
                            
                            if ($cacheResult) {
                                $this->db->UpdateClob('META_BP_EXP_CACHE_VERSION', 'EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['cacheExpression'][$k]), 'ID = ' . $cacheId); 
                            }
                        }
                    }
                    
                    Mdmeta::bpOnlyExpressionClearCache($metaDataId);
                    (new Mdmeta())->bpFullExpressionUseProcess($metaDataId);
                }

                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

            } catch (Exception $ex) {
                return array('status' => 'error', 'message' => $ex->getMessage());
            }
        }

        return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
    }

    public function saveNewVersionFullExpressionModel() {

        if (Input::postCheck('fullExpressionString_set')) {

            try {
                
                $this->load->model('mdmetadata', 'middleware/models/');
        
                $metaDataId = Input::post('bpExpKeyMetaId');
                $checkLock = $this->model->checkMetaLock($metaDataId);

                if ($checkLock) {
                    return $checkLock;
                }
                
                $idPh = $this->db->Param(0);
        
                $bpLinkId = $this->db->GetOne("SELECT ID FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = $idPh", array($metaDataId));

                if ($bpLinkId) {

                    $dtlId = getUID();
                    $currentDate = Date::currentDate('Y-m-d H:i:s');
                    $sessionUserKeyId = Ue::sessionUserKeyId();
                            
                    $fields = array(
                        'ID' => $dtlId, 
                        'BP_LINK_ID' => $bpLinkId, 
                        'TITLE' => Input::post('title'), 
                        'DESCRIPTION' => Input::post('description'), 
                        'CREATED_DATE' => $currentDate, 
                        'CREATED_USER_ID' => $sessionUserKeyId
                    );
                    $result = $this->db->AutoExecute('META_BP_EXPRESSION_DTL', $fields); 

                    if ($result) {

                        if (Input::postCheck('isDefault')) {

                            $this->db->Execute("
                                UPDATE CUSTOMER_BP_EXP_CONFIG 
                                SET IS_DEFAULT = 0 
                                WHERE ID IN (
                                    SELECT 
                                        EX.ID 
                                    FROM META_BUSINESS_PROCESS_LINK PL 
                                        INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                                        INNER JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
                                    WHERE PL.META_DATA_ID = $idPh  
                                )", 
                                array($metaDataId)
                            );

                            $configFields = array(
                                'ID' => getUID(), 
                                'EXP_DTL_ID' => $dtlId, 
                                'IS_DEFAULT' => 1
                            );
                            $this->db->AutoExecute('CUSTOMER_BP_EXP_CONFIG', $configFields); 
                        }

                        $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'EVENT_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionString_set']), 'ID = ' . $dtlId); 
                        $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'LOAD_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionOpenCriteria_set']), 'ID = ' . $dtlId); 
                        $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'VAR_FNC_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringVarFnc_set']), 'ID = ' . $dtlId); 

                        $afterSave = ' ';

                        if (!empty($_POST['fullExpressionStringAfterSave_set'])) {
                            $afterSave = "\n".'startAfterSave '.Input::paramWithDoubleSpace($_POST['fullExpressionStringAfterSave_set']).' endAfterSave';
                        }

                        $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'SAVE_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringSave_set']).$afterSave, 'ID = ' . $dtlId); 
                        
                        if (Input::postCheck('cacheId')) {
                        
                            $cacheIdData = $_POST['cacheId'];

                            foreach ($cacheIdData as $k => $v) {

                                $cacheId = getUID();
                                $cacheData = array(
                                    'ID' => $cacheId, 
                                    'VERSION_ID' => $dtlId, 
                                    'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                    'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                    'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                    'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k]),  
                                    'CREATED_USER_ID' => $sessionUserKeyId, 
                                    'CREATED_DATE' => $currentDate
                                );
                                $cacheResult = $this->db->AutoExecute('META_BP_EXP_CACHE_VERSION', $cacheData);

                                if ($cacheResult) {
                                    $this->db->UpdateClob('META_BP_EXP_CACHE_VERSION', 'EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['cacheExpression'][$k]), 'ID = ' . $cacheId); 
                                }
                            }
                        }
                    
                        Mdmeta::bpOnlyExpressionClearCache($metaDataId);
                        (new Mdmeta())->bpFullExpressionUseProcess($metaDataId);
                        (new Mdmeta())->setMetaModifiedDate($metaDataId);
                    }

                    return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

                } else {
                    return array('status' => 'error', 'message' => 'MetaBusinessProcessLink үүсээгүй байна');
                }

            } catch (ADODB_Exception $ex) {
                return array('status' => 'error', 'message' => $ex->getMessage());
            }
        }

        return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
    }

    public function saveUpdateVersionFullExpressionModel() {

        if (Input::postCheck('fullExpressionString_set')) {

            try {

                $this->load->model('mdmetadata', 'middleware/models/');
        
                $metaDataId = Input::post('bpExpKeyMetaId');
                $checkLock = $this->model->checkMetaLock($metaDataId);

                if ($checkLock) {
                    return $checkLock;
                }
        
                $configId = Input::post('configId');

                $fields = array(
                    'TITLE' => Input::post('title'), 
                    'DESCRIPTION' => Input::post('description') 
                );
                $result = $this->db->AutoExecute('META_BP_EXPRESSION_DTL', $fields, 'UPDATE', 'ID = '.$configId); 

                if ($result) {

                    if (Input::postCheck('isDefault')) {

                        $this->db->Execute("
                            UPDATE CUSTOMER_BP_EXP_CONFIG 
                            SET IS_DEFAULT = 0 
                            WHERE ID IN (
                                SELECT 
                                    EX.ID 
                                FROM META_BUSINESS_PROCESS_LINK PL 
                                    INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                                    INNER JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
                                WHERE PL.META_DATA_ID = $metaDataId 
                            )");
                    }

                    $keyCols = array('EXP_DTL_ID');

                    $configFields = array(
                        'ID' => getUID(), 
                        'EXP_DTL_ID' => $configId, 
                        'IS_DEFAULT' => Input::postCheck('isDefault') ? 1 : 0 
                    );
                    $this->db->Replace('CUSTOMER_BP_EXP_CONFIG', $configFields, $keyCols);

                    $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'EVENT_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionString_set']), 'ID = ' . $configId); 
                    $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'LOAD_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionOpenCriteria_set']), 'ID = ' . $configId); 
                    $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'VAR_FNC_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringVarFnc_set']), 'ID = ' . $configId); 

                    $afterSave = ' ';

                    if (!empty($_POST['fullExpressionStringAfterSave_set'])) {
                        $afterSave = "\n".'startAfterSave '.Input::paramWithDoubleSpace($_POST['fullExpressionStringAfterSave_set']).' endAfterSave';
                    }

                    $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'SAVE_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringSave_set']).$afterSave, 'ID = ' . $configId); 
                    
                    if (Input::postCheck('cacheId')) {
                        
                        $cacheIdData = $_POST['cacheId'];
                        $currentDate = Date::currentDate('Y-m-d H:i:s');
                        $sessionUserKeyId = Ue::sessionUserKeyId();
                        
                        foreach ($cacheIdData as $k => $v) {
                            
                            if ($v == '') {
                                
                                $cacheId = getUID();
                                $cacheData = array(
                                    'ID' => $cacheId, 
                                    'VERSION_ID' => $configId, 
                                    'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                    'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                    'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                    'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k]),  
                                    'CREATED_USER_ID' => $sessionUserKeyId, 
                                    'CREATED_DATE' => $currentDate
                                );
                                $cacheResult = $this->db->AutoExecute('META_BP_EXP_CACHE_VERSION', $cacheData);
                                
                            } else {
                                
                                $cacheId = $v;
                                
                                if (Input::param($_POST['cacheRowDelete'][$k]) == 'deleted') {
                                    
                                    $this->db->Execute("DELETE FROM META_BP_EXP_CACHE_VERSION WHERE ID = $cacheId");
                                    $cacheResult = false;
                                    
                                } else {
                                    
                                    $cacheData = array(
                                        'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                        'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                        'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                        'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k])
                                    );
                                    
                                    $cacheResult = $this->db->AutoExecute('META_BP_EXP_CACHE_VERSION', $cacheData, 'UPDATE', 'ID = '.$cacheId);
                                }
                            }
                            
                            if ($cacheResult) {
                                $this->db->UpdateClob('META_BP_EXP_CACHE_VERSION', 'EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['cacheExpression'][$k]), 'ID = ' . $cacheId); 
                            }
                        }
                    }
                    
                    Mdmeta::bpOnlyExpressionClearCache($metaDataId);
                    (new Mdmeta())->bpFullExpressionUseProcess($metaDataId);
                    (new Mdmeta())->setMetaModifiedDate($metaDataId);
                }

                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

            } catch (ADODB_Exception $ex) {
                return array('status' => 'error', 'message' => $ex->getMessage());
            }
        }

        return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
    }
    
    public function tempSaveFullExpressionModel() {
        
        try {
            
            $sessionId = Ue::appUserSessionId();
            $metaDataId = Input::numeric('bpExpKeyMetaId');
            
            $eventExp = Input::paramWithDoubleSpace($_POST['fullExpressionString_set']); 
            $withoutEventExp = Input::paramWithDoubleSpace($_POST['fullExpressionOpenCriteria_set']); 
            $varFncExp = Input::paramWithDoubleSpace($_POST['fullExpressionStringVarFnc_set']);
            $beforeSaveExp = Input::paramWithDoubleSpace($_POST['fullExpressionStringSave_set']);
            $afterSaveExp = Input::paramWithDoubleSpace($_POST['fullExpressionStringAfterSave_set']);
            
            $cache = phpFastCache();
            $fullExp = new Mdexpression();
            
            $cache->set('bp_'.$metaDataId.'_ExpVarFnc_'.$sessionId, $varFncExp, Mdwebservice::$expressionCacheTime);
            $cache->set('bp_'.$metaDataId.'_ExpEvent_'.$sessionId, $eventExp, Mdwebservice::$expressionCacheTime);
            $cache->set('bp_'.$metaDataId.'_ExpLoad_'.$sessionId, $withoutEventExp, Mdwebservice::$expressionCacheTime);
            $cache->set('bp_'.$metaDataId.'_ExpBeforeSave_'.$sessionId, $beforeSaveExp, Mdwebservice::$expressionCacheTime);
            $cache->set('bp_'.$metaDataId.'_ExpAfterSave_'.$sessionId, $afterSaveExp, Mdwebservice::$expressionCacheTime);
            
            $bpFullScriptsVarFnc = $fullExp->fullExpressionConvertWithoutEvent($varFncExp, $metaDataId, '', true);
            $cache->set('bp_'.$metaDataId.'_ExpVarFncRun_'.$sessionId, $bpFullScriptsVarFnc, Mdwebservice::$expressionCacheTime);

            $bpFullScriptsEvent = $fullExp->fullExpressionConvertEvent($eventExp, $metaDataId, '');
            $cache->set('bp_'.$metaDataId.'_ExpEventRun_'.$sessionId, $bpFullScriptsEvent, Mdwebservice::$expressionCacheTime);

            $bpFullScriptsWithoutEvent = $fullExp->fullExpressionConvertWithoutEvent($withoutEventExp, $metaDataId, '', false);
            $cache->set('bp_'.$metaDataId.'_ExpLoadRun_'.$sessionId, $bpFullScriptsWithoutEvent, Mdwebservice::$expressionCacheTime);

            $bpFullScriptsBeforeSave = $fullExp->fullExpressionConvertWithoutEvent($beforeSaveExp, $metaDataId, '', false, 'before_save');
            $cache->set('bp_'.$metaDataId.'_ExpBeforeSaveRun_'.$sessionId, $bpFullScriptsBeforeSave, Mdwebservice::$expressionCacheTime);
            
            $bpFullScriptsAfterSave = $fullExp->fullExpressionConvertWithoutEvent($afterSaveExp, $metaDataId, '', false, 'before_save');
            $cache->set('bp_'.$metaDataId.'_ExpAfterSaveRun_'.$sessionId, $bpFullScriptsAfterSave, Mdwebservice::$expressionCacheTime);
            
            $result = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $result;
    }

    public function deleteBpFullExpressionVersionModel() {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaDataId = Input::numeric('metaDataId');
        $checkLock = $this->model->checkMetaLock($metaDataId);

        if ($checkLock) {
            return $checkLock;
        }
        
        $versionId = Input::post('versionId');
        $idPh = $this->db->Param(0);
        
        $this->db->Execute("DELETE FROM CUSTOMER_BP_EXP_CONFIG WHERE EXP_DTL_ID = $idPh", array($versionId));
        $this->db->Execute("DELETE FROM META_BP_EXPRESSION_DTL WHERE ID = $idPh", array($versionId));
        $this->db->Execute("DELETE FROM META_BP_EXP_CACHE_VERSION WHERE VERSION_ID = $idPh", array($versionId));

        Mdmeta::bpOnlyExpressionClearCache($metaDataId);
        (new Mdmeta())->bpFullExpressionUseProcess($metaDataId);
        (new Mdmeta())->setMetaModifiedDate($metaDataId);

        return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
    }

    public function getFullExpressionVersionListModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                ED.ID, 
                PL.META_DATA_ID, 
                ED.TITLE, 
                ED.DESCRIPTION, 
                ED.CREATED_DATE, 
                EX.IS_DEFAULT 
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                LEFT JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
            WHERE PL.META_DATA_ID = ".$this->db->Param(0)." 
                ORDER BY ED.CREATED_DATE ASC", array($metaDataId));

        return $data;
    }

    public function getPfObjectExportModel() {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);

        $postData = $_POST['paramData'];

        if (is_array($postData)) {

            $sourceId = $objectCode = null;

            foreach ($postData as $val) {
                if ($val['name'] == 'id') {
                    $sourceId = $val['value'];
                } elseif ($val['name'] == 'object') {
                    $objectCode = $val['value'];
                }
            }

            if ($sourceId && $objectCode) {

                $param = array(
                    'id' => $sourceId, 
                    'type' => $objectCode 
                );

                $commandCode = 'MD_EXP_001';
                $objectCode = strtolower($objectCode);

                if ($objectCode == 'gltemplate' || $objectCode == 'report') {
                    $commandCode = 'FIN_EXP_001';
                }

                $data = $this->ws->runResponse(self::getBPServiceUrlByMetaCode($commandCode), $commandCode, $param);
                
                if ($data['status'] == 'success' && isset($data['result'])) {

                    $result = $this->ws->returnValue($data);
                    $result['object'] = $objectCode;

                    return $result;  

                } else {
                    return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
                }
            }
        } 

        return array('status' => 'error', 'message' => 'Дамжуулах параметрын тохиргоо хийгдээгүй байна. (Process Detail -> Post Param)');
    }

    public function getBPServiceUrlByMetaCode($code) {
        return self::$gfServiceAddress;
    }
    
    public function serviceReloadModel() {

        $data = $this->ws->runResponse(self::$gfServiceAddress, 'reload_command');
        
        $configWsUrl = Config::getFromCache('heavyServiceAddress');
            
        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
            $this->ws->runResponse($configWsUrl, 'reload_command');
        } 
        
        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'success');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }
    
    public function serviceReloadConfigModel() {
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'reload_config');

        $configWsUrl = Config::getFromCache('heavyServiceAddress');
            
        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
            $this->ws->runResponse($configWsUrl, 'reload_config');
        } 
        
        self::serviceReloadModel();
        
        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'success');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function serverReloadByProcessModel($processCode) {

        $param = array(
            'commandName' => $processCode
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'reload_command', $param);
        
        $configWsUrl = Config::getFromCache('heavyServiceAddress');
            
        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
            $this->ws->runResponse($configWsUrl, 'reload_command', $param);
        } 
        
        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'success');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function serverReloadByDataViewModel($dataViewId) {

        $param = array(
            'systemMetaGroupId' => $dataViewId
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'reload_command', $param);
        
        $configWsUrl = Config::getFromCache('heavyServiceAddress');
            
        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
            $this->ws->runResponse($configWsUrl, 'reload_command', $param);
        } 
            
        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'success');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function bpFullExpressionUseProcessModel($processMetaDataId) {
        
        $idPh = $this->db->Param(0);
        
        $data = $this->db->GetAll("
            SELECT 
                EVENT_EXPRESSION_STRING, 
                LOAD_EXPRESSION_STRING, 
                SAVE_EXPRESSION_STRING, 
                VAR_FNC_EXPRESSION_STRING 
            FROM META_BUSINESS_PROCESS_LINK 
            WHERE META_DATA_ID = $idPh 
               UNION ALL 
            SELECT 
                ED.EVENT_EXPRESSION_STRING, 
                ED.LOAD_EXPRESSION_STRING, 
                ED.SAVE_EXPRESSION_STRING, 
                ED.VAR_FNC_EXPRESSION_STRING 
            FROM META_BP_EXPRESSION_DTL ED 
                INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.ID = ED.BP_LINK_ID 
            WHERE PL.META_DATA_ID = $idPh", array($processMetaDataId)); 

        if ($data) {

            $array = array();

            foreach ($data as $row) {

                $fullExpression = $row['EVENT_EXPRESSION_STRING'].$row['LOAD_EXPRESSION_STRING'].$row['SAVE_EXPRESSION_STRING'].$row['VAR_FNC_EXPRESSION_STRING'];

                if (strpos($fullExpression, 'runProcessValue(') !== false) {
                    preg_match_all('/runProcessValue\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 2) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }

                if (strpos($fullExpression, 'getProcessParam(') !== false) {
                    preg_match_all('/getProcessParam\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }

                if (strpos($fullExpression, 'execProcess(') !== false) {
                    preg_match_all('/execProcess\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'callProcess(') !== false) {
                    preg_match_all('/callProcess\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'callProcessBpOpen(') !== false) {
                    preg_match_all('/callProcessBpOpen\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'callProcessDefaultGet(') !== false) {
                    preg_match_all('/callProcessDefaultGet\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'callDataView(') !== false) {
                    preg_match_all('/callDataView\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'callStatement(') !== false) {
                    preg_match_all('/callStatement\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'callWorkspace(') !== false) {
                    preg_match_all('/callWorkspace\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'getFieldValueOtherProcess(') !== false) {
                    preg_match_all('/getFieldValueOtherProcess\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'setFieldValueOtherProcess(') !== false) {
                    preg_match_all('/setFieldValueOtherProcess\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'setComboTextOtherProcess(') !== false) {
                    preg_match_all('/setComboTextOtherProcess\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'setComboValueOtherProcess(') !== false) {
                    preg_match_all('/setComboValueOtherProcess\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'setDataViewFilter(') !== false) {
                    preg_match_all('/setDataViewFilter\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'dataViewRefresh(') !== false) {
                    preg_match_all('/dataViewRefresh\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'getDataViewColumnVal(') !== false) {
                    preg_match_all('/getDataViewColumnVal\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'getVisibleDataViewColumnVal(') !== false) {
                    preg_match_all('/getVisibleDataViewColumnVal\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'getDataViewFilterVal(') !== false) {
                    preg_match_all('/getDataViewFilterVal\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'getPanelSelectedRowVal(') !== false) {
                    preg_match_all('/getPanelSelectedRowVal\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'getWfmNextStatusList(') !== false) {
                    preg_match_all('/getWfmNextStatusList\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'getWfmNextStatusListByRowData(') !== false) {
                    preg_match_all('/getWfmNextStatusListByRowData\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);

                                if (count($evArr) > 1) {
                                    $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                    $array[$processCode] = 1;
                                }
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'dataViewExpandAll(') !== false) {
                    preg_match_all('/dataViewExpandAll\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);
                                $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                $array[$processCode] = 1;
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'dataViewCollapseAll(') !== false) {
                    preg_match_all('/dataViewCollapseAll\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);
                                $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                $array[$processCode] = 1;
                            } 
                        }
                    }
                }
                
                if (strpos($fullExpression, 'workSpaceReload(') !== false) {
                    preg_match_all('/workSpaceReload\((.*?)\)/i', $fullExpression, $getProcess);

                    if (count($getProcess[0]) > 0) {

                        foreach ($getProcess[1] as $ek => $ev) {

                            if (strpos($ev, ',') !== false) {
                                $evArr = explode(',', $ev);
                                $processCode = strtolower(str_replace(array("'", '"'), array('', ''), $evArr[0]));
                                $array[$processCode] = 1;
                            } 
                        }
                    }
                }
            }
            
            $this->db->Execute("DELETE FROM META_BP_EXPRESSION_PROCESS WHERE PROCESS_META_DATA_ID = $idPh", array($processMetaDataId));

            if ($array) {

                $processCodes = implode("','", array_keys($array));

                $processData = $this->db->GetAll("
                    SELECT 
                        META_DATA_ID 
                    FROM META_DATA 
                    WHERE LOWER(META_DATA_CODE) IN ('$processCodes')");

                foreach ($processData as $p => $process) {

                    $param = array(
                        'ID' => getUIDAdd($p), 
                        'PROCESS_META_DATA_ID' => $processMetaDataId, 
                        'USE_META_DATA_ID' => $process['META_DATA_ID']
                    );
                    $this->db->AutoExecute('META_BP_EXPRESSION_PROCESS', $param);
                }
            }
        }

        return true;
    }

    public function getDvHeaderFooterHtml($metaDataId) {
        return $this->db->GetRow("SELECT HEADER_HTML, FOOTER_HTML FROM CUSTOMER_DV_HDR_FTR WHERE META_DATA_ID = $metaDataId");
    }

    public function getProcessChildMetasByLock($id) {
        
        $idPh = $this->db->Param(0);
        
        $data = $this->db->GetAll("
            SELECT 
                TT.LOOKUP_META_DATA_ID 
            FROM ( 
                SELECT 
                    LOOKUP_META_DATA_ID  
                FROM META_PROCESS_PARAM_ATTR_LINK  
                WHERE PROCESS_META_DATA_ID = $idPh 
                    AND LOOKUP_META_DATA_ID IS NOT NULL 
                    
                UNION ALL

                SELECT 
                    LOOKUP_KEY_META_DATA_ID AS LOOKUP_META_DATA_ID  
                FROM META_PROCESS_PARAM_ATTR_LINK  
                WHERE PROCESS_META_DATA_ID = $idPh 
                    AND LOOKUP_KEY_META_DATA_ID IS NOT NULL 
            ) TT     
            GROUP BY TT.LOOKUP_META_DATA_ID", array($id));

        return $data;
    }

    public function getDVChildMetasByLock($id) {
        
        $idPh = $this->db->Param(0);
        
        $data = $this->db->GetAll("
            SELECT 
                TT.LOOKUP_META_DATA_ID 
            FROM ( 
                SELECT 
                    LOOKUP_META_DATA_ID  
                FROM META_GROUP_CONFIG   
                WHERE MAIN_META_DATA_ID = $idPh 
                    AND LOOKUP_META_DATA_ID IS NOT NULL 

                UNION ALL 

                SELECT 
                    LOOKUP_KEY_META_DATA_ID AS LOOKUP_META_DATA_ID  
                FROM META_GROUP_CONFIG   
                WHERE MAIN_META_DATA_ID = $idPh  
                    AND LOOKUP_KEY_META_DATA_ID IS NOT NULL 
            ) TT     
            GROUP BY TT.LOOKUP_META_DATA_ID", array($id));

        return $data;
    }

    public function multiLockModel() {

        $data = $this->db->GetAll("
            SELECT 
                META_DATA_ID  
            FROM TEST_TABLE");

        if ($data) {

            $lockName = 'vr_togtokhsuren';
            $lockPass = 'Pass89';

            foreach ($data as $row) {

                $id = $row['META_DATA_ID'];

                $data = array(
                    'id' => $id, 
                    'lockName' => $lockName, 
                    'lockPass' => $lockPass
                );
                
                $this->load->model('mdmetadata', 'middleware/models/');
                $metaRow = $this->model->getMetaDataModel($id);

                if ($metaRow['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {

                    $this->load->model('mdmeta', 'middleware/models/');
                    $data['childMetas'] = $this->model->getProcessChildMetasByLock($id);

                } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {

                    $this->load->model('mdmeta', 'middleware/models/');
                    $data['childMetas'] = $this->model->getDVChildMetasByLock($id);
                }

                $response = $this->ws->redirectPost(Mdmeta::getLockServerAddr().'locking', $data);
            }

            return $response;
        }

        return true;
    }

    public function getWhLocationPhotoModel($id) {
        
        if ($id && is_numeric($id)) {
            
            $r = $this->db->GetRow("
                SELECT WH.PARENT_ID
                FROM WH_LOCATION WH
                WHERE WH.LOCATION_ID = $id"
            );                   

            if ($r) {
                if (empty($r['PARENT_ID'])) 
                    $rr = false;
                else
                    $rr = $this->db->GetRow("
                        SELECT FA.ATTACH, WH.PARENT_ID
                        FROM WH_LOCATION WH
                        INNER JOIN WH_LOCATION_IMAGE WHI ON WHI.LOCATION_ID = WH.LOCATION_ID
                        INNER JOIN FILE_ATTACH FA ON FA.ATTACH_ID = WHI.ATTACH_ID
                        WHERE WH.LOCATION_ID = " . $r['PARENT_ID']);

                if ($rr) {

                    $childRegions = $this->db->GetAll("
                        SELECT WH.REGION 
                        FROM WH_LOCATION WH       
                        WHERE WH.PARENT_ID = ".$r['PARENT_ID']." AND WH.LOCATION_ID != $id"
                    );       

                    return array('url' => $rr['ATTACH'], 'region' => $childRegions);

                } else {
                    $r = $this->db->GetRow("
                        SELECT FA.ATTACH, WH.PARENT_ID 
                        FROM WH_LOCATION WH
                        INNER JOIN WH_LOCATION_IMAGE WHI ON WHI.LOCATION_ID = WH.LOCATION_ID
                        INNER JOIN FILE_ATTACH FA ON FA.ATTACH_ID = WHI.ATTACH_ID
                        WHERE WH.LOCATION_ID = $id"
                    );

                    return array('url' => issetVar($r['ATTACH']), 'region' => '');
                }
            }
        }
        
        return array('url' => '', 'region' => '');
    }     

    public function getWhLocationPhotoModel2($id = null, $deviceId = null) {
        
        if ($id && $deviceId) {
            
            $r = $this->db->GetRow("
                SELECT 
                    FA.ATTACH
                FROM WH_LOCATION WH
                    INNER JOIN WH_LOCATION_IMAGE WHI ON WHI.LOCATION_ID = WH.LOCATION_ID
                    INNER JOIN FILE_ATTACH FA ON FA.ATTACH_ID = WHI.ATTACH_ID
                WHERE WH.WAREHOUSE_ID = $id AND WH.PARENT_ID IS NULL
                ORDER BY WH.CREATED_DATE"
            );      

            if ($r) {
                $childRegions = $this->db->GetAll("
                   SELECT COORDINATE as REGION FROM WH_LOCATION_DEVICE WHERE DEVICE_ID = $deviceId"
                );

                return array(
                    'url' => $r['ATTACH'],
                    'region' => $childRegions
                );
            }
        }
        
        return array('url' => '', 'region' => '');
    }     

    public function getWhLocationPhotoModel3($id) {
        
        if ($id && is_numeric($id)) {
            $r = $this->db->GetRow("
                SELECT FA.ATTACH
                FROM WH_LOCATION WH
                INNER JOIN WH_LOCATION_IMAGE WHI ON WHI.LOCATION_ID = WH.LOCATION_ID
                INNER JOIN FILE_ATTACH FA ON FA.ATTACH_ID = WHI.ATTACH_ID
                WHERE WH.WAREHOUSE_ID = $id AND WH.PARENT_ID IS NULL
                ORDER BY WH.CREATED_DATE"
            );      

            if ($r) {
                $rrr = array();
                $childRegions = $this->db->GetAll("
                   SELECT WHD.COORDINATE as REGION, '' AS LOCATIONID, '' AS LOCATIONNAME, IO.CODE AS LOCATIONCODE, '' AS ITEMKEYID, '' AS OBJECTPHOTO
                   FROM IO_DEVICE IO
                   INNER JOIN WH_LOCATION_DEVICE WHD ON WHD.DEVICE_ID = IO.ID
                   WHERE WHD.WAREHOUSE_ID = $id AND WHD.COORDINATE IS NOT NULL"
                );
                foreach($childRegions as $row) {
                    array_push($rrr, array(
                        'REGION' => $row['REGION'],
                        'LOCATION_ID' => $row['LOCATIONID'],
                        'LOCATION_NAME' => $row['LOCATIONNAME'],
                        'LOCATION_CODE' => $row['LOCATIONCODE'],
                        'ITEM_KEY_ID' => $row['ITEMKEYID'],
                        'PHOTO' => $row['OBJECTPHOTO'],
                    ));
                }

                return array('url' => $r['ATTACH'], 'region' => $rrr);
            }
        }
        
        return array('url' => '', 'region' => '');
    }     

    public function getWhLocationPhotoViewReferenceModel($id) {
        
        if ($id && is_numeric($id)) {
            
            $r = $this->db->GetAll("
                SELECT 
                    WH.REGION, 
                    WH.LOCATION_ID, 
                    WH.LOCATION_ID AS LOCATION_KEY_ID, 
                    WH.LOCATION_CODE, 
                    WH.LOCATION_NAME, 
                    CASE
                        WHEN DTL.END_QTY > 0
                        THEN 1
                        ELSE 0
                    END COLOR, 
                    FA.ATTACH AS PHOTO 
                FROM WH_LOCATION WH       
                    INNER JOIN WH_LOCATION_IMAGE WHI ON WHI.LOCATION_ID = WH.LOCATION_ID
                    INNER JOIN FILE_ATTACH FA ON FA.ATTACH_ID = WHI.ATTACH_ID 
                    LEFT JOIN
                        (SELECT 
                            KEY.LOCATION_ID,
                            SUM(D.IN_QTY - D.OUT_QTY) AS END_QTY,
                            SUM(D.IN_COST_AMOUNT - D.OUT_COST_AMOUNT) AS END_COST
                        FROM IM_ITEM_BOOK_DTL D
                            INNER JOIN IM_ITEM_BOOK B ON B.ITEM_BOOK_ID = D.ITEM_BOOK_ID
                            INNER JOIN IM_ITEM_KEY KEY ON KEY.ITEM_KEY_ID = D.ITEM_KEY_ID
                        GROUP BY KEY.LOCATION_ID
                            HAVING SUM(D.IN_QTY - D.OUT_QTY) > 0
                        ) DTL ON DTL.LOCATION_ID = WH.LOCATION_ID            
                WHERE WH.PARENT_ID = $id"
            );
            $rr = $this->db->GetRow("
                SELECT 
                    FA.ATTACH, 
                    WH.PARENT_ID
                FROM WH_LOCATION WH
                    INNER JOIN WH_LOCATION_IMAGE WHI ON WHI.LOCATION_ID = WH.LOCATION_ID
                    INNER JOIN FILE_ATTACH FA ON FA.ATTACH_ID = WHI.ATTACH_ID
                WHERE WH.LOCATION_ID = " . $id);

            if ($rr) {
                return array('url' => $rr['ATTACH'], 'region' => $r);
            }
        }
        
        return array('url' => '', 'region' => '');
    }

    public function getWhLocationPhotoViewModel($params) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $getMetaDataId = $this->model->getMetaDataByCodeModel('getLocationRegion');            

        $param = array(
            'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'itemkeyid' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $params['itemKeyId']
                    )
                ),
                'parentid' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $params['locationId']
                    )
                )
            )
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);

            if (!empty($data['result'])) {
                $r = array();

                foreach($data['result'] as $row) {
                    array_push($r, array(
                        'REGION' => $row['region1'],
                        'LOCATION_ID' => $row['locationid'],
                        'LOCATION_NAME' => $row['locationname'],
                        'LOCATION_CODE' => $row['locationcode'],
                        'ITEM_KEY_ID' => $row['itemkeyid'],
                        'PHOTO' => $row['objectphoto'],
                    ));
                }

                return array('url' => $params['objectPhoto'], 'region' => $r);                
            }
        } 
        return array('url' => '', 'region' => '');
    }

    public function getAssetLocationPhotoViewModel($params, $type = '', $p = null, $rlocationId = '') {
        $key = ($type === 'back' && $rlocationId != $p) ? 'id' : 'parentid';
        
        $param = array(
            'systemMetaGroupId' => $params['dataViewId'],
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                $key => array(
                    array(
                        'operator' => '=',
                        'operand' => ($type === 'back' && $rlocationId != $p) ? $p : $params['id']
                    )
                )
            )
        );
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $r = array();

            foreach($data['result'] as $row) {
                array_push($r, array(
                    'REGION' => $row[$params['location']],
                    'LOCATION_ID' => $row['id'],
                    'LOCATION_KEY_ID' => isset($params['rowid']) ? issetVar($row[$params['rowid']]) : '',
                    'LOCATION_CODE' => $row[$params['code']],
                    'LOCATION_NAME' => $row[$params['name']],
                    'ITEM_KEY_ID' => '',
                    'PHOTO' => $row[$params['picturepath']],
                    'CHILD_PHOTO' => isset($params['profilephoto']) ? issetVar($row[$params['profilephoto']]) : '',
                    'SIDEBAR_DV_ID' => isset($params['sidebardvid']) ? issetVar($row[$params['sidebardvid']]) : '',
                    'PROCESS_ID' => isset($params['processid']) ? issetVar($row[$params['processid']]) : '',
                ));
            }
            
            $url = ($type === 'back') ? $data['result'][0]['ppicturepath'] : $params['planpicture'];
            $parentid = ($type === 'back') ? $data['result'][0]['parentid'] : '';
            
            return array('url' => $url, 'parentid' => $parentid, 'region' => $r);                
        }
        
        return array('url' => $params['planpicture'], 'region' => '');
    }
    
    public function folderCacheClearModel($folderId) {
        
        $folders = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_TYPE_ID 
            FROM META_DATA MD 
                INNER JOIN META_DATA_FOLDER_MAP FM ON FM.META_DATA_ID = MD.META_DATA_ID
                INNER JOIN (
                    SELECT
                        FVM.FOLDER_ID
                    FROM FVM_FOLDER FVM
                    START WITH FVM.FOLDER_ID = ".$this->db->Param(0)." 
                    CONNECT BY NOCYCLE PRIOR FVM.FOLDER_ID = FVM.PARENT_FOLDER_ID
                ) FL ON FL.FOLDER_ID = FM.FOLDER_ID 
            WHERE MD.META_TYPE_ID IN (200101010000016, 200101010000011) 
            ORDER BY 
            CASE MD.META_TYPE_ID   
                WHEN 200101010000016 THEN 1  
                WHEN 200101010000011 THEN 2  
            END ASC", array($folderId));
        
        if ($folders) {
            
            $tmp_dir = Mdcommon::getCacheDirectory();
            
            foreach ($folders as $row) {
                
                if ($row['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                    (new Mdmeta())->dataViewCacheClear($tmp_dir, $row['META_DATA_ID']);
                } else {
                    (new Mdmeta())->processCacheClear($tmp_dir, $row['META_DATA_ID'], $row['META_DATA_CODE']);
                }
            }
            
            (new Mdmeta())->serviceReload();
            
            $response = array('status' => 'success', 'message' => 'Success');
            
        } else {
            $response = array('status' => 'warning', 'message' => 'BusinessProcess & MetaGroup төрөлтэй үзүүлэлт олдсонгүй.');
        }
        
        return $response;
    }
    
    public function getMetaUnLockModel() {

        $metaDataId = Input::numeric('metaDataId');
        $sessionUserKeyId = Ue::sessionUserKeyId();

        $row = $this->db->GetRow("
            SELECT 
                PASSWORD_HASH 
            FROM UM_META_LOCK 
            WHERE META_DATA_ID = ".$this->db->Param(0)." 
                AND USER_ID = ".$this->db->Param(1), 
            array($metaDataId, $sessionUserKeyId)); 

        if ($row) {
            return $row;
        }
        return false;
    }    
    
    public function updateMetaUnLockPasswordModel($data)
    {
        $result = $this->db->AutoExecute('UM_META_LOCK', $data, 'UPDATE', 'USER_ID = '.Ue::sessionUserKeyId().' AND META_DATA_ID = '.Input::numeric('metaDataId'));
        if ($result) {
            return true;
        } 
        return false;
    }    
    
    public function getMetaGroupSubQueryModel($groupLinkId) {
        return $this->db->GetAll("SELECT ID, CODE, GLOBE_CODE, DESCRIPTION, TABLE_NAME FROM META_GROUP_SUB_QUERY WHERE META_GROUP_LINK_ID = ".$this->db->Param(0), array($groupLinkId));
    }
    
    public function getDMRowProcessModel($mainMetaDataId, $processMetaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                PD.ID,
                PD.MAIN_META_DATA_ID,
                PD.PROCESS_META_DATA_ID,
                LOWER(PD.SRC_PARAM_PATH) AS SRC_PARAM_PATH,
                LOWER(PD.TRG_PARAM_PATH) AS TRG_PARAM_PATH
            FROM META_DM_ROW_PROCESS_PARAM PD 
            WHERE PD.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PD.PROCESS_META_DATA_ID = ".$this->db->Param(1), 
            array($mainMetaDataId, $processMetaDataId)
        );

        $result = '';

        if ($data) {
            foreach ($data as $row) {
                $result .= Form::hidden(array('name' => 'groupProcessDtlTransferDataViewPath[' . $processMetaDataId . '][]', 'value' => $row['SRC_PARAM_PATH']));
                $result .= Form::hidden(array('name' => 'groupProcessDtlTransferProcessParamPath[' . $processMetaDataId . '][]', 'value' => $row['TRG_PARAM_PATH']));
            }
        }

        return $result;
    }    

    public function createDmTableModel() {
        $param = array(
            'metadataid' => Input::post('metaDataId')
        );

        $dmQuery = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'create_datamart_table', $param);

        if ($dmQuery['status'] === 'success') {
            return array('status' => 'success', 'msg' => 'Success');
        } else {
            return array('status' => 'warning', 'msg' => $dmQuery['text']);            
        }
    } 
    
    public function moduleSidebarMenuModel($moduleId, $isReturnSidebar = false) {
        $this->load->model('appmenu');
        
        $moduleData = $this->model->getMenuListModel();
        $item = '';
        
        if (isset($moduleData['menuData']) && $moduleList = $moduleData['menuData']) {
            
            $count = count($moduleList);
            
            foreach ($moduleList as $key => $row) {

                if ($row['code'] == 'ERP_MENU_MOBILE') {
                    continue;
                }

                $activeClass = '';

                if ($moduleId == $row['metadataid']) {
                    $activeClass = ' active';
                }
                
                $linkOnClick = "";
                if ($row['weburl'] == 'mdform/indicatorProduct/16805027402801') {
                    $indicatorId = '16805027402801';
                    $linkHref = 'javascript:;';
                    $linkOnClick = ' onclick="mvProductRenderInit(this, \''.$linkHref.'\', \''.$indicatorId.'\');"';                    
                }

                $item .= '<a href="javascript:;"'.$linkOnClick.' data-moduleid="'.$row['metadataid'].'" data-code="'.$row['code'].'" data-original-title="'.$this->lang->line($row['name']).'" data-weburl="'.$row['weburl'].'" data-urltrg="'.$row['urltrg'].'" data-bookmarkurl="'.$row['bookmarkurl'].'" data-bookmarktrg="'.$row['bookmarktrg'].'" data-actionmetadataid="'.$row['actionmetadataid'].'" data-actionmetatypeid="'.$row['actionmetatypeid'].'" class="nav-link d-flex flex-column'.$activeClass.'" data-pfgotometa="1">';

                if ($row['icon']) {
                    $item .= '<i class="far '.$row['icon'].'"></i>';
                } else {
                    $item .= '<i class="fa fa-folder"></i>';
                }

                if (isset($row['countmetadataid']) && $row['countmetadataid']) {
                    $leftMenuCount = self::getLeftMenuCountModel($row['countmetadataid']);
                    if ($leftMenuCount != '') {
                        $item .= '<span class="badge bg-danger-400 badge-pill badge-float border-1 border-white left-menu-count-meta" data-counmetadataid="'.$row['countmetadataid'].'">'.($leftMenuCount ? $leftMenuCount : '').'</span>';
                    }
                }

                $item .= '<span class="line-height-normal text-center text-two-line mt3">'.$this->lang->line($row['name']).'</span></a>';
            }
        }
        
        if ($item || $isReturnSidebar) {
            
            $appmenuIcon = Config::getFromCache('appmenu-ico');
            $appMarket = Config::getFromCache('isShowAppMarket');
            $appmenuUrl = Config::getFromCacheDefault('CONFIG_START_LINK', null, 'appmenu');
            
            if ($appMarket) {
                $item .= '<a href="javascript:;" title="" data-moduleid="" data-original-title="App market" data-weburl="appmarket" data-urltrg="" data-bookmarkurl="" data-bookmarktrg="" data-actionmetadataid="" data-actionmetatypeid="" class="nav-link d-flex flex-column pt13" data-pfgotometa="1" style="position: fixed;bottom: 0;background-color: #5A6785;width: 86px;"><i class="far fa-store"></i><span class="line-height-normal text-center text-two-line mt3">App<br>market</span></a>';
            }
            
            return '<div class="iconbar">
                        <div class="'. ($appmenuIcon ? '-home-icon' : 'home-icon') .'">
                            <a href="'. (Config::getFromCache('tmsCustomerCode') == 'gov' ? 'javascript:;' : $appmenuUrl) .'" class="nav-link border-right-0">
                                '. ($appmenuIcon ? '<img src="'. $appmenuIcon .'" style="height: 70px;">' : '<i class="far fa-home"></i>') .'
                            </a>
                        </div>
                        <nav class="nav pf-module-sidebar">
                            '.$item.'
                        </nav>
                    </div>';
        }
        
        return '';
    }
    
    public function moduleSidebarKpiMenuModel($moduleId, $isReturnSidebar = false) {
        $this->load->model('mdmenu', 'middleware/models/');
        
        $moduleList = $this->model->getKpiIndicatorMenuModel();
        $item = '';
        
        if ($moduleList) {
            
            $count = count($moduleList);
            
            foreach ($moduleList as $key => $row) {

                $activeClass = '';

                if ($moduleId == $row['id']) {
                    $activeClass = ' active';
                }

                $item .= '<a href="javascript:;" data-moduleid="'.$row['id'].'" data-code="'.$row['code'].'" data-original-title="'.$this->lang->line($row['name']).'" data-weburl="" data-urltrg="" data-bookmarkurl="" data-bookmarktrg="" data-actionmetadataid="" data-actionmetatypeid="" class="nav-link d-flex flex-column'.$activeClass.'" data-pfgotometa="1" data-kpi-indicator="1">';

                if (isset($row['icon'])) {
                    $item .= '<i class="far '.$row['icon'].'"></i>';
                } else {
                    $item .= '<i class="fa fa-folder"></i>';
                }

                $item .= '<span class="line-height-normal text-center text-two-line mt3">'.$this->lang->line($row['name']).'</span></a>';
            }
        }
        
        if ($item || $isReturnSidebar) {
            
            $appmenuIcon = Config::getFromCache('appmenu-ico');
            $appMarket = Config::getFromCache('isShowAppMarket');
            $appmenuUrl = Config::getFromCacheDefault('CONFIG_START_LINK', null, 'appmenu');
            
            if ($appMarket) {
                $item .= '<a href="javascript:;" title="" data-moduleid="" data-original-title="App market" data-weburl="appmarket" data-urltrg="" data-bookmarkurl="" data-bookmarktrg="" data-actionmetadataid="" data-actionmetatypeid="" class="nav-link d-flex flex-column pt13" data-pfgotometa="1" style="position: fixed;bottom: 0;background-color: #5A6785;width: 86px;"><i class="far fa-store"></i><span class="line-height-normal text-center text-two-line mt3">App<br>market</span></a>';
            }            
            
            return '<div class="iconbar">
                        <div class="'. ($appmenuIcon ? '-home-icon' : 'home-icon') .'">
                            <a href="'.$appmenuUrl.'" class="nav-link border-right-0">
                                '. ($appmenuIcon ? '<img src="'. $appmenuIcon .'" style="height: 70px;">' : '<i class="far fa-home"></i>') .'
                            </a>
                        </div>
                        <nav class="nav pf-module-sidebar">
                            '.$item.'
                        </nav>
                    </div>';
        }
        
        return '';
    }
    
    public function getMetaProcessParamMetaInlineEditModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                PARAM_NAME, 
                PARAM_REAL_PATH, 
                LABEL_NAME  
            FROM META_PROCESS_PARAM_ATTR_LINK  
            WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_INPUT = 1
                AND DATA_TYPE <> 'group' 
            ORDER BY ORDER_NUMBER ASC", array($metaDataId));

        return $data;
    }
    
    public function clipboardMetaPasteModel() {
        
        $ids = Input::post('ids');
        
        if ($ids) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            
            $response = array('status' => 'success');
            
            $folderId = Input::post('folderId');
            $idsArr   = explode(',', $ids);
            
            foreach ($idsArr as $id) {
                
                $metaRow   = $this->model->getMetaDataModel($id);
                $copyCount = (int) $metaRow['COPY_COUNT'];
                $copyCount = $copyCount ? $copyCount + 1 : '';
                
                $param = array(
                    'id'       => $id,
                    'code'     => $metaRow['META_DATA_CODE'] . '_copy' . $copyCount,
                    'name'     => $metaRow['META_DATA_NAME'] . ' copy' . $copyCount,
                    'folderId' => $folderId
                );

                $data = $this->ws->runResponse(self::$gfServiceAddress, 'copy_metadata', $param);
                
                if ($data['status'] != 'success') {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
                } else {
                    $this->db->AutoExecute('META_DATA', array('COPY_COUNT' => $copyCount ? $copyCount : 1), 'UPDATE', 'META_DATA_ID = ' . $id);
                }
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid ids!');
        }
        
        return $response;
    }
    
    public function dvQuerySaveModel() {
        
        $metaId = Input::numeric('metaId');
        
        if ($metaId) {
            
            try {
            
                $this->db->UpdateClob('META_GROUP_LINK', 'TABLE_NAME', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['dvQuery_set'])), 'META_DATA_ID = '.$metaId);
                $this->db->UpdateClob('META_GROUP_LINK', 'POSTGRE_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['postgreSql_set'])), 'META_DATA_ID = '.$metaId);
                $this->db->UpdateClob('META_GROUP_LINK', 'MS_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['msSql_set'])), 'META_DATA_ID = '.$metaId);
                
                $tmp_dir = Mdcommon::getCacheDirectory();
                $dvMainQueries = glob($tmp_dir."/*/dv/dvMainQueries_".$metaId.".txt");
                
                foreach ($dvMainQueries as $dvMainQuery) {
                    @unlink($dvMainQuery);
                }
                
                (new Mdmeta())->serverReloadByDataView($metaId);
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
                
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        return $response;
    }
    
}