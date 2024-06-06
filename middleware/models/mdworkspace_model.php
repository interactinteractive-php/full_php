<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdworkspace_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $coverImgUploadedPath = 'ws_cover/';
    private static $coverImgSemanticTypeId = '1001';

    public function __construct() {
        parent::__construct();
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

    public function getMetaMenuListByWorkSpaceIdModel($workSpaceLinkId, $selectedRow) {

        $param = array(
            'id' => $workSpaceLinkId,
            'userId' => Ue::sessionUserKeyId(), 
            'row' => $selectedRow 
        );
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'workspace_child_menus', $param);
        
        if ($data['status'] == 'success' && isset($data['result'][0]['child'])) {  
            $menuDatas = $data['result'][0]['child'];
            ksort($menuDatas);
            $data['result'][0]['child'] = $menuDatas;
            
            return array('status' => 'success', 'menuData' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function leftMetaMenuModuleModel($workSpaceId, $menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId, $rowData = '', $autoNumber = 0, $themeCode = '', $wsRow = array(), $selectedRow = array(), $dmMetaDataId = '') {
        if (!$moduleId) {
            return;
        }

        $menu = '';
        $dataRow = array();

        if ($dmMetaDataId && $selectedRow) {
            $dataRow['dataViewId'] = $dmMetaDataId;
            $dataRow['workspaceId'] = $workSpaceId;
            $dataRow['metaDataId'] = $dmMetaDataId;
            $dataRow['refStructureId'] = $this->db->GetOne("SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($dmMetaDataId));
            $dataRow['dataRow'] = $selectedRow;
        }

        $onSelectedRow = Arr::encode($dataRow);

        if (is_array($menuData)) {

            if ($isChild || $depth != 0) {

                $menuOpenStyle = ' style="display: none;"';
                if ($menuOpen == 'open_all') {
                    $menuOpenStyle = ' style="display: block;"';
                }
                $menu .= '<ul class="sub-menu"' . $menuOpenStyle . '>';

            } elseif ($depth == 0) {
                $menu = '<ul class="page-sidebar-menu workspace-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" data-islastvisitmenu="'.$wsRow['IS_LAST_VISIT_MENU'].'">';
            }

            foreach ($menuData as $k => $row) {

                $childMenu = '';
                $autoNumber = $autoNumber + 1;

                if (isset($row['child'])) {
                    $childMenu = self::leftMetaMenuModuleModel($workSpaceId, $row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $menuId, $rowData, $autoNumber + 1, $themeCode, $wsRow, $selectedRow, $dmMetaDataId);
                }
                
                $countMetaData = '';
                    
                if (isset($row['countmetadataid']) && $row['countmetadataid']) {
                    $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '"><i class="fa fa-spinner fa-spin"></i></span>';
                }

                if ($isChild || $depth != 0) {
                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'"  attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1">';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);

                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'"  attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                } else {
                    $icon = '';
                    if (!empty($row['icon'])) {
                        $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
                    }

                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'"  attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1">';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span> <span class="arrow"></span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);

                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'"  attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '">';
                        $menuAnchor .= $icon . '<span class="title">' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = '';

                if ($row['metadataid'] === $menuId) {
                    $liClass = ' class="active"';
                }

                $menu .= '<li' . $liClass . ' '.($depth != 0 ? 'onclick="activeMenu(this);"' : '').' data-auto-number="'.$autoNumber.'">';
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

    public function topMetaMenuModuleModel($workSpaceId, $menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId, $rowData = '', $autoNumber = 0, $themeCode = '', $wsRow = array(), $selectedRow = array(), $dmMetaDataId = '') {
        if (!$moduleId) {
            return;
        }

        $menuLinkId = $wsRow['ID'];

        $dataRow = array();

        if ($dmMetaDataId && $selectedRow) {
            $dataRow['dataViewId'] = $dmMetaDataId;
            $dataRow['workspaceId'] = $workSpaceId;
            $dataRow['metaDataId'] = $dmMetaDataId;
            $dataRow['refStructureId'] = $this->db->GetOne("SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = $dmMetaDataId");
            $dataRow['dataRow'] = $selectedRow;
        }

        $encodeSelectedRow = Arr::encode($selectedRow);
        $onSelectedRow = Arr::encode($dataRow);

        $menu = $className = $notooltip = '';
        $ticket = false;
        
        if ($themeCode === 'theme15') {
            $notooltip = 'ws15-theme-menu';
            if ($wsRow && isset($wsRow['USE_TOOLTIP']) && $wsRow['USE_TOOLTIP'] === '1') {
                $ticket = true;
                $notooltip = '';
            }
        }

        if (is_array($menuData)) {

            if ($isChild || $depth != 0) {

                $menuOpenStyle = ' style="display: none;"';
                if ($menuOpen == 'open_all') {
                    $menuOpenStyle = ' style="display: block;"';
                }
                if ($themeCode == 'theme10') {
                    $className = ' nav navbar-nav';
                }
                $menu .= '<ul class="sub-menu'. $className .'"' . $menuOpenStyle . '>';

            } elseif ($depth == 0) {
                if ($themeCode === 'theme28') {
                    $menu = '<ul class="workspace-menu workspace-menu-v2 nav nav-sidebar">';
                } else {
                    $menu = '<ul class="workspace-menu workspace-menu-v2 nav navbar-nav nav-tabs">';
                }
            }

            foreach ($menuData as $k => $row) {

                $childMenu = $icon = $iconstyle = $menuclass = $bgColor = '';
                $autoNumber = $autoNumber + 1;

                $menuname = Lang::line($row['name']);
                $menutooltip = Lang::line($row['name']);
                $menupx = mb_strlen($menuname, 'UTF-8') * 9 + 45;

                if (isset($row['color']) && $row['color'] != '') {
                    $bgColor = ' style="background-color: '.$row['color'].';"';
                }

                if (isset($row['child'])) {
                    $childMenu = self::topMetaMenuModuleModel($workSpaceId, $row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $menuId, $rowData, $autoNumber + 1, $themeCode, $wsRow, $selectedRow, $dmMetaDataId);
                }
                
                $countMetaData = '';

                if (isset($row['countmetadataid']) && $row['countmetadataid']) {
                    $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '"><i class="fa fa-spinner fa-spin"></i></span>';
                }

                if ($isChild || $depth != 0) {
                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '"'.$bgColor.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);

                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                } else {
                    if ($themeCode === 'theme15') { 
                        $menuclass = 'vr-theme-15-menu ' . (($ticket) ? 'hidden' : '');
                        $icon = '<i class="fa fa-home '. $notooltip .'" style="'. $iconstyle .'"></i> ';
                    }

                    if (!empty($row['icon']) && $themeCode !== 'theme14' || $themeCode !== 'theme18' || $themeCode !== 'theme19' || $themeCode !== 'theme21') {
                        $icon = '<i class="fa ' . $row['icon'] . ' '. $notooltip .'" style="'. $iconstyle .'"></i> ';
                    } 

                    if ($childMenu != '') {
                        $targetLinkAttr = '';
                        if (($themeCode == 'theme10' || $themeCode == 'theme14' || $themeCode == 'theme19' || $themeCode == 'theme18' || $themeCode == 'theme21' || $themeCode == 'theme30') && isset($row['child'][0])) {
                            $rowMeta = self::menuAnchor($row['child'][0], $workSpaceId, $rowData, '1');
                            $targetLinkAttr = 'target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" menu-data-link-id = "'. $menuLinkId .'" selectedrow-encode = "'. $encodeSelectedRow .'"';
                        }

                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" '. $targetLinkAttr .'  '.$bgColor.'>';
                            $menuAnchor .= $icon . '<span class="title '. $menuclass . ' ' . $notooltip .'">' . $menuname . '</span> <span class="arrow"></span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);

                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                            $menuAnchor .= $icon . '<span class="title '. $menuclass . ' ' . $notooltip .'">' . $menuname . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                }

                if ($themeCode === 'theme28') {
                    $liClass = ' class="nav-item"';
                } else {
                    $liClass = ' class="nav-item dropdown dropdown-fw dropdown-fw-disabled "';
                }

                if ($row['metadataid'] === $menuId) {
                    if ($themeCode === 'theme28') {
                        $liClass = ' class="nav-item active"';
                    } else {
                        $liClass = ' class="nav-item dropdown dropdown-fw dropdown-fw-disabled active"';
                    }
                }

                $menu .= '<li' . $liClass . ' '.($depth != 0 ? 'onclick="activeMenu(this);"' : '').' style="" data-menu-width="'. $menupx .'" data-toggle="tooltip" data-placement="bottom" title="'. (($ticket) ? $menutooltip : '') .'" data-auto-number="'.$autoNumber.'">';
                    $menu .= $menuAnchor . (($themeCode !== 'theme14' || $themeCode !== 'theme19' || $themeCode !== 'theme18' || $themeCode !== 'theme21') ? $childMenu : '' );
                $menu .= '</li>';
            }

            if (($isChild || $depth != 0) || $depth == 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function topMetaMenuModuleV2Model($workSpaceId, $menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId, $rowData = '', $autoNumber = 0, $themeCode = '', $wsRow = array(), $selectedRow = array(), $dmMetaDataId = '') {
        if (!$moduleId) {
            return;
        }

        $menuLinkId = $wsRow['ID'];

        $dataRow = array();

        if ($dmMetaDataId && $selectedRow) {
            $dataRow['dataViewId'] = $dmMetaDataId;
            $dataRow['workspaceId'] = $workSpaceId;
            $dataRow['metaDataId'] = $dmMetaDataId;
            $dataRow['refStructureId'] = $this->db->GetOne("SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = $dmMetaDataId");
            $dataRow['dataRow'] = $selectedRow;
        }
        
        $encodeSelectedRow = Arr::encode($selectedRow);
        $onSelectedRow = Arr::encode($dataRow);

        $menu = $className = $notooltip = '';
        $ticket = false;
        
        if ($themeCode === 'theme15') {
            $notooltip = 'ws15-theme-menu';
            if ($wsRow && isset($wsRow['USE_TOOLTIP']) && $wsRow['USE_TOOLTIP'] === '1') {
                $ticket = true;
                $notooltip = '';
            }
        }

        if (is_array($menuData)) {

            if ($isChild || $depth != 0) {

                $menuOpenStyle = ' style="display: none;"';
                if ($menuOpen == 'open_all') {
                    $menuOpenStyle = ' style="display: block;"';
                }
                if ($themeCode == 'theme10') {
                    $className = ' nav navbar-nav';
                }
                if ($themeCode == 'theme28') {
                    $className = ' nav nav-group-sub';
                }
                $menu .= '<ul class="sub-menu'. $className .'"' . $menuOpenStyle . '>';

            } elseif ($depth == 0) {
                
                if ($themeCode === 'theme28') {
                    $menu = '<ul class="workspace-menu workspace-menu-v2 nav nav-sidebar" data-islastvisitmenu="'.$wsRow['IS_LAST_VISIT_MENU'].'">';
                } else {
                    $menu = '<ul class="workspace-menu workspace-menu-v2 nav navbar-nav nav-tabs" data-islastvisitmenu="'.$wsRow['IS_LAST_VISIT_MENU'].'">';
                }
                
                if ($themeCode === 'theme19' || $themeCode === 'theme21') {
                    $menu .= '<li class="nav-item dropdown dropdown-fw dropdown-fw-disabled" style="width: auto !important;" data-menu-width="" data-toggle="tooltip" data-placement="bottom">';
                        $menu .= '<a href="javascript:;" class="nav-link" data-selected-row="" data-menu-id="-999" onclick="renderStaticMenuByWorkSpace('.$workSpaceId.', this)" target="_self"><i class="fa fa-user"></i> <span class="title">Нүүр хуудас</span></a>';
                    $menu .= '</li>';                
                }
            }
            
            $n = 1;
            
            foreach ($menuData as $k => $row) {
                
                $childMenu = $icon = $iconstyle = $menuclass = $bgColor = '';
                $autoNumber = $autoNumber + 1;

                $menuname = Lang::line($row['name']);
                $menutooltip = Lang::line($row['name']);
                $menupx = mb_strlen($menuname, 'UTF-8')*9 + 45;

                if (isset($row['color']) && $row['color'] != '') {
                    $bgColor = ' style="background-color: '.$row['color'].';"';
                }

                if (isset($row['child'])) {
                    $childMenu = self::topMetaMenuModuleV2Model($workSpaceId, $row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $menuId, $rowData, $autoNumber + 1, $themeCode, $wsRow, $selectedRow, $dmMetaDataId);
                }
                
                $countMetaData = '';

                if (isset($row['countmetadataid']) && $row['countmetadataid']) {
                    $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '"><i class="fa fa-spinner fa-spin"></i></span>';
                }

                if ($isChild || $depth != 0) {
                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="nav-link" '.($themeCode === 'theme28' ? 'class="nav-link"' : '').' data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1"'.$bgColor.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);

                        $menuAnchor = '<a href="javascript:;" class="nav-link" '.($themeCode === 'theme28' ? 'class="nav-link"' : '').' data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                } else {
                    if ($themeCode === 'theme15') { 
                        $menuclass = 'vr-theme-15-menu ' . (($ticket) ? 'hidden' : '');
                        $icon = '<i class="fa fa-home '. $notooltip .'" style="'. $iconstyle .'"></i> ';
                    }

                    if (!empty($row['icon']) && $themeCode !== 'theme14' || $themeCode !== 'theme19' || $themeCode !== 'theme21') {
                        $icon = '<i class="fa ' . $row['icon'] . ' '. $notooltip .'" style="'. $iconstyle .'"></i> ';
                    } 

                    if (!empty($row['icon']) && $themeCode === 'theme18') {
                        $icon = '<i class="fa ' . $row['icon'] . ' '. $notooltip .'" style="'. $iconstyle .'" title="'. $menuname .'"></i> ';
                    } 

                    if ($childMenu != '') {
                        $targetLinkAttr = '';
                        if (($themeCode == 'theme10' || $themeCode == 'theme14' || $themeCode == 'theme19' || $themeCode == 'theme18' || $themeCode == 'theme21') && isset($row['child'][0])) {
                            $rowMeta = self::menuAnchor($row['child'][0], $workSpaceId, $rowData, '1');
                            $targetLinkAttr = 'target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" menu-data-link-id = "'. $menuLinkId .'" selectedrow-encode = "'. $encodeSelectedRow .'"';
                        }

                        $menuAnchor = '<a href="javascript:;" class="nav-link" '.($themeCode === 'theme28' ? 'class="nav-link"' : '').' data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" '. $targetLinkAttr .'  '.$bgColor.'>';
                            $menuAnchor .= $icon . '<span class="title '. $menuclass . ' ' . $notooltip .'">' . $menuname . '</span> <span class="arrow"></span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);

                        $menuAnchor = '<a href="javascript:;" class="nav-link" '.($themeCode === 'theme28' ? 'class="nav-link"' : '').' data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                            $menuAnchor .= $icon . '<span class="title '. $menuclass . ' ' . $notooltip .'">' . $menuname . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                }

                if ($themeCode === 'theme28') {
                    $liClass = ' class="nav-item'.($depth != 0 ? ' pl35' : '').'"';
                    if ($childMenu != '') {
                        $liClass = ' class="nav-item nav-item-submenu"';
                    }
                } else {
                    $liClass = ' class="nav-item dropdown dropdown-fw dropdown-fw-disabled"';
                }

                if ($row['metadataid'] === $menuId) {
                    if ($themeCode === 'theme28') {
                        $liClass = ' class="nav-item active'.($depth != 0 ? ' pl35' : '').'"';
                        if ($childMenu != '') {
                            $liClass = ' class="nav-item nav-item-submenu active"';
                        }                        
                    } else {
                        $liClass = ' class="nav-item dropdown dropdown-fw dropdown-fw-disabled active"';
                    }                    
                }
                
                if (!$menuId && $depth == 0 && $n == 1) {
                    $liClass = ' class="nav-item dropdown dropdown-fw dropdown-fw-disabled active"';
                }

                $menu .= '<li' . $liClass . ' '.($depth != 0 ? 'onclick="activeMenu(this);"' : '').' style="width: auto !important;" data-menu-width="'. $menupx .'" data-toggle="tooltip" data-placement="bottom" title="'. (($ticket) ? $menutooltip : '') .'" data-auto-number="'.$autoNumber.'">';
                    $menu .= $menuAnchor . (($themeCode !== 'theme14' || $themeCode !== 'theme19' || $themeCode !== 'theme18' || $themeCode !== 'theme21') ? $childMenu : '' );
                $menu .= '</li>';
                
                $n ++;
            }

            if (($isChild || $depth != 0) || $depth == 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }
    

    public function menuTheme33Model($workSpaceId, $menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId, $rowData = '', $autoNumber = 0, $themeCode = '', $wsRow = array(), $selectedRow = array(), $dmMetaDataId = '') {
        if (!$moduleId) {
            return;
        }

        $menuLinkId = $wsRow['ID'];

        $dataRow = array();

        if ($dmMetaDataId && $selectedRow) {
            $dataRow['dataViewId'] = $dmMetaDataId;
            $dataRow['workspaceId'] = $workSpaceId;
            $dataRow['metaDataId'] = $dmMetaDataId;
            $dataRow['refStructureId'] = $this->db->GetOne("SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = $dmMetaDataId");
            $dataRow['dataRow'] = $selectedRow;
        }
        
        $encodeSelectedRow = Arr::encode($selectedRow);
        $onSelectedRow = Arr::encode($dataRow);

        $menu = $className = $notooltip = '';

        if (is_array($menuData)) {
            foreach ($menuData as $k => $row) {

                $childMenu = $icon = $iconstyle = $menuclass = $bgColor = '';
                $autoNumber = $autoNumber + 1;

                $menuname = Lang::line($row['name']);
                $menutooltip = Lang::line($row['name']);
                $menupx = mb_strlen($menuname, 'UTF-8')*9 + 45;
                
                $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);

                $menu = '<a href="javascript:;" class="section-menu-item" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                  '<svg class="section-menu-item-icon icon-'.$row['icon'].'">'.
                    '<use xlink:href="#svg-'.$row['icon'].'"></use>'.
                  '</svg>';
                  $menu .= '<p class="section-menu-item-text">'.$menuname.'</p>';
                $menu .= '</a>';
            }
        }

        return $menu;
    }

    public function menuThemeWizardModel($workSpaceId, $menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId, $rowData = '', $autoNumber = 0, $themeCode = '', $wsRow = array(), $selectedRow = array(), $dmMetaDataId = '') {
        if (!$moduleId) {
            return;
        }

        $menuLinkId = $wsRow['ID'];

        $dataRow = array();

        if ($dmMetaDataId && $selectedRow) {
            $dataRow['dataViewId'] = $dmMetaDataId;
            $dataRow['workspaceId'] = $workSpaceId;
            $dataRow['metaDataId'] = $dmMetaDataId;
            $dataRow['refStructureId'] = $this->db->GetOne("SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = $dmMetaDataId");
            $dataRow['dataRow'] = $selectedRow;
        }
        
        $encodeSelectedRow = Arr::encode($selectedRow);
        $onSelectedRow = Arr::encode($dataRow);

        $menu = $className = $notooltip = $autoAdditional = '';

        if (issetParam($selectedRow['prevprocessid']) !== '' && issetParam($selectedRow['nextprocessid']) !== '' && issetParam($selectedRow['finishprocessid']) !== '') {
            $listName = '';
            $metaTypeName = 'dataview';
            
            if (issetParam($selectedRow['callmetadataid'])) {
                $result = $this->db->GetRow("
                                                    SELECT 
                                                        t0.META_DATA_ID, 
                                                        t0.META_DATA_CODE, 
                                                        t0.META_TYPE_ID,
                                                        " . $this->db->IfNull('t1.LIST_NAME', 't0.META_DATA_NAME') . " AS LIST_NAME
                                                    FROM META_DATA t0
                                                    LEFT JOIN META_GROUP_LINK t1 ON t0.META_DATA_ID = t1.REF_STRUCTURE_ID
                                                    WHERE t0.META_DATA_ID = " . $this->db->Param(0), array($selectedRow['callmetadataid']));
                
                $listName = issetParam($result['LIST_NAME']);
                switch ($result['META_TYPE_ID']) {
                    case Mdmetadata::$workSpaceMetaTypeId:
                        $metaTypeName = 'workspace';
                        break;
                    
                    default:
                        
                        $metaTypeName = 'dataview';
                        break;
                }
            }
            
            $autoAdditional = ' data-prevprocessid="'. $selectedRow['prevprocessid'] .'" data-nextprocessid="'. $selectedRow['nextprocessid'] .'" data-finishprocessid="'. $selectedRow['finishprocessid'] .'" data-closeprocessid="'. issetParam($selectedRow['closeprocessid']) .'" data-callmetadataid="'. issetParam($selectedRow['callmetadataid']) .'"  data-listname="'. issetParam($listName) .'"  data-metatypename="'. issetParam($metaTypeName) .'" ';
        }
        
        if (is_array($menuData)) {
            foreach ($menuData as $k => $row) {

                $childMenu = $icon = $iconstyle = $menuclass = $bgColor = '';
                $autoNumber = $autoNumber + 1;

                $menuname = Lang::line($row['name']);
                $menutooltip = Lang::line($row['name']);
                $menupx = mb_strlen($menuname, 'UTF-8')*9 + 45;
                
                $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);
                
                // $menu .= '<a class="section-menu-item" href="javascript:;" >';
                    $menu .= '<h3 class="section-menu-item" '. $autoAdditional .' data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                        /* '<svg class="section-menu-item-icon icon-'.$row['icon'].'">'.
                            '<use xlink:href="#svg-'.$row['icon'].'"></use>'.
                        '</svg>'; */
                        /* $menu .= '<p class="section-menu-item-text">'.$menuname.'</p>'; */
                        $menu .= $menuname;
                    $menu .= '</h3>';
                // $menu .= '</a>';
                $menu .= '<section class="mb-2 px-2" style="padding: 0 .625rem!important;"></section>';
            }
        }

        return $menu;
    }
    
    public function tabModuleModel($workSpaceId, $menuData, $moduleId, $menuId, $rowData = '') {
        if (!$moduleId) {
            return;
        }
        
        $html = $tabMenu = $tabContent = '';

        if (is_array($menuData)) {
            foreach ($menuData as $k => $row) {
                $menuAnchor = self::menuAnchor($row, $workSpaceId, $rowData);
                $tabMenu .= '<li class="nav-item"><a href="#tab_'.$row['metadataid'].'" data-toggle="tab" ' . ($row['metadataid'] === $menuId ? ' class="active"' : '') . ' aria-expanded="true" onclick="clearTab();'.$menuAnchor['linkOnClick'].'" class="nav-link">'.Lang::line($row['name']).'</a></li>';
                $tabContent .= '<div class="tab-pane ' . ($row['metadataid'] === $menuId ? 'active' : '') . '" id="tab_'.$row['metadataid'].'"><div class="workspace-main-container"></div></div>';
            }
        }
        
        $html .= '<ul class="nav nav-tabs">'.$tabMenu.'</ul><div class="tab-content">'.$tabContent.'</div>';

        return $html;
    }
    
    public function cartModuleModel($workSpaceId, $menuData, $moduleId, $depth = 0, $isChild, $menuOpen, $menuId, $rowData = '', $autoNumber = 0, $themeCode = '', $wsRow = array(), $selectedRow = array(), $dmMetaDataId = '') {
        if (!$moduleId) {
            return;
        }

        $menuLinkId = $wsRow['ID'];

        $dataRow = array();

        if ($dmMetaDataId && $selectedRow) {
            $dataRow['dataViewId'] = $dmMetaDataId;
            $dataRow['workspaceId'] = $workSpaceId;
            $dataRow['metaDataId'] = $dmMetaDataId;
            $dataRow['refStructureId'] = $this->db->GetOne("SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = $dmMetaDataId");
            $dataRow['dataRow'] = $selectedRow;
        }
        
        $encodeSelectedRow = Arr::encode($selectedRow);
        $onSelectedRow = Arr::encode($dataRow);

        $menu = $className = $notooltip = '';
        $ticket = false;
        
        if ($themeCode === 'theme15') {
            $notooltip = 'ws15-theme-menu';
            if ($wsRow && isset($wsRow['USE_TOOLTIP']) && $wsRow['USE_TOOLTIP'] === '1') {
                $ticket = true;
                $notooltip = '';
            }
        }

        if (is_array($menuData)) {

            if ($isChild || $depth != 0) {

                $menuOpenStyle = ' style="display: none;"';
                if ($menuOpen == 'open_all') {
                    $menuOpenStyle = ' style="display: block;"';
                }
                if ($themeCode == 'theme10') {
                    $className = ' nav navbar-nav';
                }
                $menu .= '<ul class="sub-menu'. $className .'"' . $menuOpenStyle . '>';

            } elseif ($depth == 0) {
                $menu = '<ul class="grid list-view0 workspace-cart-menu">';
            }

            foreach ($menuData as $k => $row) {

                $childMenu = $icon = $iconstyle = $menuclass = $bgColor = '';
                $autoNumber = $autoNumber + 1;

                $menuname = Lang::line($row['name']);
                $menutooltip = Lang::line($row['name']);
                $menupx = mb_strlen($menuname, 'UTF-8')*9 + 45;

                if (isset($row['color']) && $row['color'] != '') {
                    $bgColor = ' style="background-color: '.$row['color'].';"';
                }

                if (isset($row['child'])) {
                    $childMenu = self::cartModuleModel($workSpaceId, $row['child'], $moduleId, $depth + 1, $isChild, $menuOpen, $menuId, $rowData, $autoNumber + 1, $themeCode, $wsRow, $selectedRow, $dmMetaDataId);
                }
                
                $countMetaData = '';

                if (isset($row['countmetadataid']) && $row['countmetadataid']) {
                    $countMetaData = ' <span class="badge badge-success ml-auto left-menu-count-meta" data-counmetadataid="' . $row['countmetadataid'] . '"><i class="fa fa-spinner fa-spin"></i></span>';
                }

                if ($isChild || $depth != 0) {
                    if ($childMenu != '') {
                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1"'.$bgColor.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>';
                        $menuAnchor .= '</a>';
                    } else {
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData, 'main');

                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                        $menuAnchor .= '<span>' . Lang::line($row['name']) . '</span>' . $countMetaData;
                        $menuAnchor .= '</a>';
                    }
                } else {
                    
                    $icon = '14.png';
                    $getIcon = self::getMetaIcon($row['metadataid']);
                    
                    if ($getIcon) {
                        $icon = $getIcon;
                    } 

                    if ($childMenu != '') {
                        $targetLinkAttr = '';
                        
                        if (($themeCode == 'theme10' || $themeCode == 'theme14' || $themeCode == 'theme19' || $themeCode == 'theme18' || $themeCode == 'theme21') && isset($row['child'][0])) {
                            $rowMeta = self::menuAnchor($row['child'][0], $workSpaceId, $rowData, '1');
                            $targetLinkAttr = 'target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" menu-data-link-id = "'. $menuLinkId .'" selectedrow-encode = "'. $encodeSelectedRow .'"';
                        }

                        $menuAnchor = '<a href="javascript:;" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" '. $targetLinkAttr .'  '.$bgColor.'>';
                            $menuAnchor .= '<div class="img-precontainer">
                                                <div class="img-container directory"><span></span>
                                                    <img class="directory-img" src="assets/core/global/img/metaicon/big/14.png">
                                                </div>
                                            </div>';
                            $menuAnchor .= $icon . '<span class="title '. $menuclass . ' ' . $notooltip .'">' . $menuname . '</span> <span class="arrow"></span>';
                        $menuAnchor .= '</a>';
                        
                    } else {
                        
                        $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData, 'dialog');

                        $menuAnchor = '<a href="javascript:;" class="nav-link" class="nav-link" data-selected-row="'. $onSelectedRow .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '" data-pfgotometa="1" target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '"'.$bgColor.'>';
                            $menuAnchor .= '<div class="img-precontainer">
                                                <div class="img-container directory"><span></span>
                                                    <img class="directory-img" src="assets/core/global/img/metaicon/big/' . $icon . '">
                                                </div>
                                            </div>';
                            $menuAnchor .= '<div class="box">';
                            $menuAnchor .= '<h4 class="ellipsis '. $menuclass . ' ' . $notooltip .'">' . $menuname . '</h4>';
                            $menuAnchor .= '</div>';
                        $menuAnchor .= '</a>';
                    }
                }

                $liClass = ' class="nav-item dropdown dropdown-fw dropdown-fw-disabled "';

                if ($row['metadataid'] === $menuId) {
                    $liClass = ' class="nav-item dropdown dropdown-fw dropdown-fw-disabled active"';
                }

                $menu .= '<li' . $liClass . ' '.($depth != 0 ? 'onclick="activeMenu(this);"' : '').' style="width: 124px;" data-menu-width="'. $menupx .'" data-toggle="tooltip" data-placement="bottom" title="'. (($ticket) ? $menutooltip : '') .'" data-auto-number="'.$autoNumber.'"><figure class="directory" style="width: 122px;">';
                    $menu .= $menuAnchor . (($themeCode !== 'theme14' || $themeCode !== 'theme19' || $themeCode !== 'theme18' || $themeCode !== 'theme21') ? $childMenu : '' );
                $menu .= '</figure></li>';
            }

            if (($isChild || $depth != 0) || $depth == 0) {
                $menu .= '</ul>';
            }
        }

        return $menu;
    }

    public function initThemePositionListModel($workSpaceMetaId, $targetMetaId) {

        $html = '';
        $result = $this->db->GetAll("
            SELECT 
                ID, 
                FIELD_PATH, 
                PARAM_PATH, 
                LABEL_NAME, 
                IS_TARGET 
            FROM META_WORKSPACE_PARAM_MAP 
            WHERE WORKSPACE_META_ID = $workSpaceMetaId AND 
                TARGET_META_ID = $targetMetaId AND 
                IS_TARGET = 0 
            ORDER BY PARAM_PATH ASC");

        if ($result) {
            $i = 1;
            foreach ($result as $row) {
                $html .= '<tr>';
                    $html .= '<td><input type="hidden" name="rowId[]" value="' . $row['ID'] . '">' . $i . '</td>';
                    $html .= '<td>' . $row['PARAM_PATH'] . '</td>';
                    $html .= '<td>' . $row['FIELD_PATH'] . '</td>';
                    $html .= '<td>' . $row['LABEL_NAME'] . '</td>';
                    $html .= '<td class="text-center">'
                            . '<a href="javascript:;" class="btn blue btn-xs" onclick="editThemePosition(this);"><i class="fa fa-edit"></i></a>'
                            . '<a href="javascript:;" class="btn red btn-xs" onclick="deleteThemePosition(this);"><i class="fa fa-trash"></i></a>'
                            . '</td>';
                $html .= '</tr>';
                $i++;
            }
        }

        return $html;
    }

    public function getWorkSpacePositionByIdModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                ID, 
                FIELD_PATH, 
                PARAM_PATH, 
                LABEL_NAME  
            FROM META_WORKSPACE_PARAM_MAP 
            WHERE ID = $id");

        return $row;
    }

    public function getDVParameterListModel($mainMetaDataId) {
        $result = $this->db->GetAll("
            SELECT 
                FIELD_PATH, 
                LABEL_NAME AS META_DATA_NAME 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL 
                AND DATA_TYPE <> 'group'", array($mainMetaDataId)
        );

        if ($result) {
            return $result;
        }
        return array();
    }

    public function insertThemePositionModel() {

        $metaDataId = Input::numeric('metaDataId');

        $headerId = $this->db->GetOne("SELECT ID FROM META_WORKSPACE_LINK WHERE META_DATA_ID = $metaDataId");

        $data = array(
            'ID' => getUID(),
            'WORKSPACE_META_ID' => $metaDataId,
            'TARGET_META_ID' => Input::post('targetMetaId'),
            'FIELD_PATH' => Input::post('fieldPath'),
            'PARAM_PATH' => Input::post('paramPath'),
            'LABEL_NAME' => Input::post('labelName'),
            'IS_TARGET' => '0', 
            'META_WORKSPACE_LINK_ID' => $headerId
        );
        $result = $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', $data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа');
    }

    public function updateThemePositionModel() {

        $id = Input::post('id');

        $data = array(
            'FIELD_PATH' => Input::post('fieldPath'),
            'PARAM_PATH' => Input::post('paramPath'),
            'LABEL_NAME' => Input::post('labelName')
        );
        $result = $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', $data, 'UPDATE', 'ID = '.$id);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа');
    }

    public function deleteThemePositionModel() {
        $result = $this->db->Execute("DELETE FROM META_WORKSPACE_PARAM_MAP WHERE ID = " . Input::post('rowId'));

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Устгах үед алдаа гарлаа');
    }

    public function initWorkSpaceProcessListModel($workSpaceMetaId) {
        $html = '';

        $result = $this->db->GetAll("
            SELECT 
                MWPM.ID, 
                MWPM.TARGET_META_ID,
                MWPM.TARGET_INDICATOR_ID,
                MD.META_DATA_NAME AS TARGET_META_NAME,
                KP.NAME AS TARGET_INDICATOR_NAME,
                MWPM.FIELD_PATH, 
                MWPM.PARAM_PATH, 
                MWPM.IS_TARGET 
            FROM META_WORKSPACE_PARAM_MAP MWPM 
                LEFT JOIN META_DATA MD ON MWPM.TARGET_META_ID = MD.META_DATA_ID 
                LEFT JOIN KPI_INDICATOR KP ON MWPM.TARGET_INDICATOR_ID = KP.ID  
            WHERE MWPM.WORKSPACE_META_ID = ".$this->db->Param(0)." 
                AND MWPM.IS_TARGET = 1 
                AND (MD.META_DATA_ID IS NOT NULL OR KP.ID IS NOT NULL) 
            ORDER BY MWPM.TARGET_META_ID ASC, MWPM.TARGET_INDICATOR_ID ASC", array($workSpaceMetaId));

        if (count($result) > 0) {
            foreach ($result as $k => $row) {
                $html .= '<tr>';
                    $html .= '<td><input type="hidden" name="rowId[]" value="' . $row['ID'] . '"><span>' . (++$k) . '</span></td>';
                    $html .= '<td data-path="fieldPath">' . $row['FIELD_PATH'] . '</td>';
                    $html .= '<td data-path="paramPath" class="text-break">' . $row['PARAM_PATH'] . '</td>';
                    $html .= '<td data-path="targetMetaName">' . $row['TARGET_META_NAME'] . '</td>';
                    $html .= '<td data-path="targetIndicatorName">' . $row['TARGET_INDICATOR_NAME'] . '</td>';
                    $html .= '<td>';
                        $html .= '<a href="javascript:;" class="btn blue btn-xs" onclick="editWorkSpaceProcess(this);"><i class="fa fa-edit"></i></a>';
                        $html .= '<a href="javascript:;" class="btn red btn-xs" onclick="deleteWorkSpaceProcess(this)"><i class="fa fa-trash"></i></a>';
                    $html .= '</td>';
                $html .= '</tr>';
            }
        }

        return $html;
    }
    
    public function getWorkSpaceProcessMapModel($rowId) {
        $row = $this->db->GetRow("
            SELECT 
                MWPM.ID, 
                MWPM.TARGET_META_ID,
                MWPM.TARGET_INDICATOR_ID,
                MD.META_DATA_CODE AS TARGET_META_CODE,
                MD.META_DATA_NAME AS TARGET_META_NAME,
                KP.CODE AS TARGET_INDICATOR_CODE,
                KP.NAME AS TARGET_INDICATOR_NAME,
                MWPM.FIELD_PATH, 
                MWPM.PARAM_PATH, 
                MWPM.IS_TARGET 
            FROM META_WORKSPACE_PARAM_MAP MWPM 
                LEFT JOIN META_DATA MD ON MWPM.TARGET_META_ID = MD.META_DATA_ID 
                LEFT JOIN KPI_INDICATOR KP ON MWPM.TARGET_INDICATOR_ID = KP.ID 
            WHERE MWPM.ID = ".$this->db->Param(0)." 
                AND MWPM.IS_TARGET = 1 
            ORDER BY MWPM.TARGET_META_ID ASC, MWPM.TARGET_INDICATOR_ID ASC", array($rowId));
        
        return $row;
    }
    
    public function insertWorkSpaceProcessModel() {

        $metaDataId = Input::numeric('metaDataId');

        $headerId = $this->db->GetOne("SELECT ID FROM META_WORKSPACE_LINK WHERE META_DATA_ID = $metaDataId");

        $data = array(
            'ID' => getUID(),
            'WORKSPACE_META_ID' => $metaDataId,
            'TARGET_META_ID' => Input::numeric('targetMetaId'),
            'TARGET_INDICATOR_ID' => Input::numeric('targetIndicatorId'),
            'FIELD_PATH' => Input::post('fieldPath'),
            'PARAM_PATH' => Input::post('paramPath'),
            'IS_TARGET' => '1', 
            'META_WORKSPACE_LINK_ID' => $headerId
        );
        $result = $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', $data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа');
    }
    
    public function updateWorkSpaceProcessMapModel() {
        
        try {
            
            $rowId = Input::numeric('rowId');
            
            $data = array(
                'TARGET_META_ID' => Input::numeric('targetMetaId'),
                'TARGET_INDICATOR_ID' => Input::numeric('targetIndicatorId'),
                'FIELD_PATH'     => Input::post('fieldPath'),
                'PARAM_PATH'     => Input::post('paramPath')
            );
            
            $result = $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', $data, 'UPDATE', 'ID = '.$rowId);
            
            if ($result) {
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
            } else {
                $response = array('status' => 'error', 'message' => 'Save error');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }

    public function getWorkSpaceByMetaIdModel($metaDataId) {

        $row = $this->db->GetRow("
            SELECT 
                WL.ID, 
                WL.META_DATA_ID, 
                MD.META_DATA_NAME, 
                WL.MENU_META_DATA_ID, 
                WL.SUBMENU_META_DATA_ID, 
                WL.GROUP_META_DATA_ID, 
                WL.LAYOUT_META_DATA_ID, 
                WL.THEME_CODE, 
                WL.DEFAULT_MENU_ID, 
                WL.WINDOW_HEIGHT, 
                WL.WINDOW_SIZE, 
                WL.WINDOW_TYPE, 
                WL.WINDOW_WIDTH,
                WL.IS_FLOW,
                WL.USE_TOOLTIP,
                WL.USE_PICTURE,
                WL.USE_COVER_PICTURE,
                WL.USE_MENU,
                WL.CHECK_MODIFIED_CATCH,
                WL.USE_LEFT_SIDE,
                WL.ACTION_TYPE, 
                WL.ROW_DATAVIEW_ID, 
                WL.IS_LAST_VISIT_MENU   
            FROM META_WORKSPACE_LINK WL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = WL.META_DATA_ID 
            WHERE WL.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

        return $row;
    }

    public function getWorkSpaceParamMap($workSpaceId, $targetId, $positionCode = null) {

        if ($targetId != null) {
            
            $bindVars = [$targetId, $workSpaceId];
            
            $targetIdPh = $this->db->Param(0);
            $workSpaceIdPh = $this->db->Param(1);
            
            if ($positionCode) {
                array_push($bindVars, $positionCode);
                $andWhere = ' AND PM.PARAM_PATH = '.$this->db->Param(2);
            } else {
                $andWhere = ' AND PM.TARGET_META_ID = '.$targetIdPh;
            }
        
            $data = $this->db->GetAll("
                SELECT 
                    LOWER(PM.FIELD_PATH) AS FIELD_PATH, 
                    GC.LABEL_NAME, 
                    LOWER(PM.PARAM_PATH) AS PARAM_PATH, 
                    PM.LINK_META_DATA_ID, 
                    PM.LABEL_NAME AS WS_LABEL_NAME 
                FROM META_WORKSPACE_PARAM_MAP PM 
                    INNER JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = $targetIdPh 
                        AND LOWER(GC.FIELD_PATH) = LOWER(PM.FIELD_PATH) 
                WHERE PM.WORKSPACE_META_ID = $workSpaceIdPh 
                    $andWhere 
                GROUP BY PM.FIELD_PATH, 
                    GC.LABEL_NAME, 
                    PM.PARAM_PATH, 
                    PM.LINK_META_DATA_ID, 
                    PM.LABEL_NAME", $bindVars);

            return $data;
        }

        return false;
    }

    public function getWorkSpaceHeaderPosition($workSpaceId, $dmMetaDataId, $positionCode, $selectedRow, $themeCode = null) {

        $getWorkSpaceParamMap = self::getWorkSpaceParamMap($workSpaceId, $dmMetaDataId, $positionCode);
        
        if ($themeCode !== 'theme10' && $themeCode !== 'theme13') {

            if ($positionCode == 'header-position-1' && $getWorkSpaceParamMap) {

                if ($getWorkSpaceParamMap && isset($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']])) {
                    return html_entity_decode($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']]);
                }

                return 'assets/core/global/img/avatar.png';
            }
        } else {

            if ($positionCode == 'header-position-1' && $getWorkSpaceParamMap && isset($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']])) {
                return html_entity_decode($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']]);
            }
            
            return 'assets/core/global/img/avatar.png';
        }


        if ($positionCode == 'header-position-2' || $positionCode == 'header-position-3' || $positionCode == 'header-position-5'
                || $positionCode == 'header-position-6' || $positionCode == 'header-position-7') {

            if ($getWorkSpaceParamMap && isset($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']])) {

                return html_entity_decode(Lang::line($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']]));
            }
        }

        if ($positionCode == 'header-position-4' && $getWorkSpaceParamMap) {

            $printParam = '';
            $countParams = count($getWorkSpaceParamMap);
            $i = 0;

            foreach ($getWorkSpaceParamMap as $k => $row) {
                $printValue = isset($selectedRow[$row['FIELD_PATH']]) ? $selectedRow[$row['FIELD_PATH']] : null;

                if ($printValue != '') {
                    $printParam .= Lang::line($row['LABEL_NAME']) . ': ' . html_entity_decode($printValue);
                    if (++ $i != $countParams) {
                        $printParam .= ', ';
                    }
                }
            }

            return $printParam;
        }
        
        if ($getWorkSpaceParamMap && $getWorkSpaceParamMap[0]['LINK_META_DATA_ID'] != '') {
            $getMetaType = $this->db->GetRow("".
                "SELECT MD.META_DATA_ID, 
                    MT.META_TYPE_ID, 
                    MG.LIST_NAME
                FROM META_DATA MD 
                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID
                LEFT JOIN META_GROUP_LINK MG ON MG.META_DATA_ID = MD.META_DATA_ID
                WHERE MD.META_DATA_ID = " . $getWorkSpaceParamMap[0]['LINK_META_DATA_ID']
            );
            
            $wsParamVal = array();
            foreach ($selectedRow as $skey => $sval) {
                $wsParamVal[$skey] = $sval;
            }
            $wsParamQuery = http_build_query(array('workSpaceParam' => $wsParamVal));
            
            if ($getMetaType['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                return '<button type="button" class="btn btn-sm blue" onclick="appMultiTab({metaDataId:\''.$getWorkSpaceParamMap[0]['LINK_META_DATA_ID'].'\', weburl:\'mdobject/dataview/'.$getWorkSpaceParamMap[0]['LINK_META_DATA_ID'].'\', title:\''.Lang::line($getMetaType['LIST_NAME']).'\', type:\'selfurl\', workSpaceParams:\''.$wsParamQuery.'\', workSpaceId:\''.$workSpaceId.'\'}, this);">'.Lang::line($getWorkSpaceParamMap[0]['WS_LABEL_NAME']).'</button>';
            } elseif ($getMetaType['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                return self::renderPositionLabelByMetaId($workSpaceId, $getWorkSpaceParamMap[0]);
            } elseif ($getMetaType['META_TYPE_ID'] == Mdmetadata::$bookmarkMetaTypeId) {
                return '<button type="button" class="btn btn-sm blue" onclick="checkXypNtr(this);">'.Lang::line($getWorkSpaceParamMap[0]['WS_LABEL_NAME']).'</button>';
            } else {
                return '';
            }
        }
                
        for ($iposition = 8; $iposition <= 50; $iposition++) {
            
            if ($positionCode == 'header-position-'.$iposition && $getWorkSpaceParamMap && isset($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']])) {
                return html_entity_decode(Lang::line($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']]));
            }
        }

        return '';
    }

    public function renderPositionLabelByMetaId($workSpaceId, $row) {
        return '<button type="button" class="btn btn-sm blue" onclick="runProcessByWorkSpace(\''.$row['LINK_META_DATA_ID'].'\', \''.$workSpaceId.'\', this, \'dialog\');">'.Lang::line($row['WS_LABEL_NAME']).'</button>';
    }

    public function getWorkSpaceCoverPosition($workSpaceId, $dmMetaDataId, $dataViewId, $selectedRow) {
        
        $getWorkSpaceParamMap = self::getWorkSpaceParamMap($workSpaceId, $dmMetaDataId, 'cover');
        
        if ($getWorkSpaceParamMap && isset($selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']])) {
            return $selectedRow[$getWorkSpaceParamMap[0]['FIELD_PATH']];
        }
        
        if ($dataViewId) {
            
            $this->load->model('mdobject', 'middleware/models/');
            $row = $this->model->getDataViewConfigRowModel($dataViewId);
            
            $this->load->model('mdworkspace', 'middleware/models/');

            if ($row && !is_null($row['REF_STRUCTURE_ID']) && isset($selectedRow['id']) && !empty($selectedRow['id'])) {

                $rowTableName = $this->db->GetRow("SELECT TABLE_NAME FROM META_GROUP_LINK WHERE META_DATA_ID = " .$this->db->Param(0), [$row['REF_STRUCTURE_ID']]);
                
                if (issetParam($rowTableName['TABLE_NAME']) !== '' && strlen($rowTableName['TABLE_NAME']) <= 30) {
                    $dmRecordSql = "SELECT 
                                        RM.ID,
                                        EC.PHYSICAL_PATH
                                    FROM META_DM_RECORD_MAP RM 
                                        INNER JOIN ECM_CONTENT EC ON RM.TRG_RECORD_ID = EC.CONTENT_ID
                                    WHERE RM.SRC_TABLE_NAME = '" . $rowTableName['TABLE_NAME'] . "'
                                        AND RM.TRG_TABLE_NAME = 'ECM_CONTENT' 
                                        AND RM.SEMANTIC_VALUE = '1' 
                                        AND RM.SRC_RECORD_ID = " . $selectedRow['id'];

                    $dmRecordResult = $this->db->GetRow($dmRecordSql);

                    if (!empty($dmRecordResult)) {
                        return $dmRecordResult['PHYSICAL_PATH'];
                    } else {
                        return 'assets/core/global/img/warehouse_header_img1.png';
                    }
                }
            }
        }

        return 'assets/core/global/img/warehouse_header_img1.png';
    }

    public function getWorkSpaceHeaderPositions($workSpaceId, $dmMetaDataId, $selectedRow, $themeCode = null) {

        $paramPosition = [];

        if ($selectedRow) {
            
            $getWorkSpaceParamMap = self::getWorkSpaceParamMap($workSpaceId, $dmMetaDataId);
            
            foreach ($selectedRow as $srow) {
                $pPosition = [];

                foreach ($getWorkSpaceParamMap as $pmap) {

                    if (isset($srow[$pmap['FIELD_PATH']]) && isset($pmap['PARAM_PATH'])) {
                        $pPosition[$pmap['PARAM_PATH']] = Input::param($srow[$pmap['FIELD_PATH']]);
                    }
                }

                array_push($paramPosition, $pPosition);
            }
        }

        return $paramPosition;
    }

    public function renderHiddenParams($selectedRow) {

        $hiddenParam = '';

        if (is_array($selectedRow)) {
            if (isset($selectedRow['children']) && is_array($selectedRow['children'])) {
                unset($selectedRow['children']);
            }
            if (isset($selectedRow['pfnextstatuscolumn']) && is_array($selectedRow['pfnextstatuscolumn'])) {
                unset($selectedRow['pfnextstatuscolumn']);
            }
            foreach ($selectedRow as $k => $v) {
                $v = str_replace(array('&lt;', '&gt;'), '', $v);
                $hiddenParam .= Form::hidden(array('name' => 'workSpaceParam[' . $k . ']', 'value' => $v));
            }
        }

        return $hiddenParam;
    }

    public function setLabelNameWorkSpaceModel($metaDataId, $targetMetaId, $html) {
        
        if ($metaDataId && $targetMetaId && strpos($html, '-labelname}') !== false) {
                
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_PATH, 
                    LABEL_NAME 
                FROM META_WORKSPACE_PARAM_MAP 
                WHERE WORKSPACE_META_ID = ".$this->db->Param(0)." 
                    AND TARGET_META_ID = ".$this->db->Param(1)." 
                    AND PARAM_PATH IS NOT NULL 
                    AND LABEL_NAME IS NOT NULL", 
                [$metaDataId, $targetMetaId]
            ); 

            if ($data) {
                foreach ($data as $row) {
                    $html = str_replace('{'.$row['PARAM_PATH'].'-labelname}', Lang::line($row['LABEL_NAME']), $html);
                }
            }
        }

        return $html;
    }
    
    public function setMetaWorkSpaceModel($metaDataId, $selectedRow, $html) {
        
        $data = $this->db->GetAll("
            SELECT 
                PARAM_PATH, 
                LABEL_NAME, 
                LINK_META_DATA_ID, 
                IS_IGNORE_TOOLBAR 
            FROM META_WORKSPACE_PARAM_MAP 
            WHERE WORKSPACE_META_ID = ".$this->db->Param(0)." 
                AND FIELD_PATH IS NULL 
                AND PARAM_PATH IS NOT NULL 
                AND LINK_META_DATA_ID IS NOT NULL 
                AND IS_TARGET = 0", [$metaDataId]); 

        if ($data) {
			
            foreach ($data as $row) {
                
                $html = str_replace('{'.$row['PARAM_PATH'].'-labelname}', Lang::line($row['LABEL_NAME']), $html);
                
                ob_start(); 
                
                $paramRow['workSpaceParam'] = $selectedRow;
                
                $_POST['ignorePermission'] = 1;
                $_POST['workSpaceId'] = $metaDataId;
                $_POST['workSpaceParams'] = http_build_query($paramRow);
                
                if ($row['IS_IGNORE_TOOLBAR'] == '1') {
                    $_POST['dvIgnoreToolbar'] = 1;
                }
                
                (new Mdobject())->dataview($row['LINK_META_DATA_ID']);

                $dataViewHtml = ob_get_clean(); 
            
                $html = str_replace('{'.$row['PARAM_PATH'].'}', $dataViewHtml, $html);
            }
        }

        return $html;
    }

    public function menuAnchor($row, $workSpaceId, $rowData = array(), $renderType = 'main') {
        $array = array();

        if (!empty($row['weburl'])) {
            $array['urlType'] = (parse_url($row['weburl'], PHP_URL_SCHEME) != null ? 'external' : 'internal');
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = $row['urltrg'];
            $array['windowId'] = 'div#workspace-id-'.$workSpaceId;
            $array['linkOnClick'] = "workSpaceDirectURL('" . $row['weburl'] . "', '" . $array['linkTarget'] . "', '', '".$array['urlType']."', '".$array['windowId']."', '".$row['metadataid']."', '".$workSpaceId."', this);";

            return $array;
        }

        if ($row['actionmetatypeid'] == Mdmetadata::$bookmarkMetaTypeId) {
            $array['urlType'] = (parse_url($row['bookmarkurl'], PHP_URL_SCHEME) != null ? 'external' : 'internal');
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['windowId'] = 'div#workspace-id-'.$workSpaceId;
            $array['linkOnClick'] = "workSpaceDirectURL('" . $row['bookmarkurl'] . "', '" . $array['linkTarget'] . "', '', '".$array['urlType']."', '".$array['windowId']."', '".$row['metadataid']."', '".$workSpaceId."', this);";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$businessProcessMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "runProcessByWorkSpace('" . $row['actionmetadataid'] . "', '" . $workSpaceId . "', this);";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$reportMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callReportByMeta('" . $row['reportmodelid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$dashboardMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callDashboardByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$metaGroupMetaTypeId) {
            if ($row['grouptype'] == 'dataview') {
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = "dataViewByWorkSpace('" . $row['actionmetadataid'] . "', '" . $workSpaceId . "', this);";
            } else {
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = '';
            }
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callContentByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$googleMapMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callGoogleMapByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$bannerMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callBannerByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$menuMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$packageMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "packageByWorkSpace('" . $row['actionmetadataid'] . "', '" . $workSpaceId . "', this, '" . $renderType . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$workSpaceMetaTypeId) {
            $array['linkHref'] = 'mdobject/workspace/' . $row['actionmetadataid'];
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$layoutMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "layoutViewByWorkSpace('" . $row['actionmetadataid'] . "', '" . $workSpaceId . "', this);";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$statementMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "statementViewByWorkSpace('" . $row['actionmetadataid'] . "', '" . $workSpaceId . "', this);";
        } else {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        }
        
        return $array;
    }

    public function defaultProcessModel($actionMenuId) {
        $result = $this->db->GetRow("
            SELECT 
                ACTION_META_DATA_ID 
            FROM META_MENU_LINK 
            WHERE META_DATA_ID = $actionMenuId");
        
        if ($result) {
            return $result;
        }
        return false;
    }

    public function getDataViewRowByRowIdModel($dmMetaDataId, $rowId) {

        $param = array(
            'systemMetaGroupId' => $dmMetaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1,  
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $rowId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            if (isset($data['result'][0])) {
                return $data['result'][0];
            }
        }

        return array();
    }

    public function paramsToUrlModel($workSpaceId, $metaDataId) {
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(FIELD_PATH) AS FIELD_PATH,  
                PARAM_PATH
            FROM META_WORKSPACE_PARAM_MAP 
            WHERE WORKSPACE_META_ID = ".$this->db->Param(0)." 
                AND TARGET_META_ID = ".$this->db->Param(1)." 
            GROUP BY FIELD_PATH, PARAM_PATH", array($workSpaceId, $metaDataId)); 

        return $data;
    }

    public function saveCoverModel() {
        $fileDataTmp = Input::fileData();
        if (isset($fileDataTmp['coverImg'])) {
            $fileData = $fileDataTmp['coverImg'];

            if (is_uploaded_file($fileData['tmp_name'])) {
                $newFileName = "file_" . getUID();
                $fileName = $fileData['name'];
                $fileSize = $fileData['size'];

                $mimes = array(
                    'image/png' => 'png',
                    'image/jpeg' => 'jpg',
                    'image/gif' => 'gif',
                    'image/bmp' => 'bmp',
                );

                if (isset($mimes[$fileData['type']])) {
                    $fileExtension = $mimes[$fileData['type']];
                } else {
                    $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
                }

                $newFileName = $newFileName . '.' . $fileExtension;
                $filePath = UPLOADPATH . self::$coverImgUploadedPath;
                FileUpload::SetFileName($newFileName);
                FileUpload::SetTempName($fileData['tmp_name']);
                FileUpload::SetUploadDirectory($filePath);
                FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
                $uploadResult = FileUpload::UploadFile();

                if ($uploadResult) {
                    
                    $contentId = getUID();
                    $ecmContentData = array(
                        'CONTENT_ID' => $contentId,
                        'FILE_NAME' => $newFileName,
                        'PHYSICAL_PATH' => $filePath . $newFileName,
                        'FILE_SIZE' => $fileSize,
                        'FILE_EXTENSION' => $fileExtension,
                        'DESCRIPTION' => Input::post('description'),
                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                        'CREATED_DATE' => Date::currentDate(),
                    );

                    $ecmContent = $this->db->AutoExecute('ECM_CONTENT', $ecmContentData);

                    $dataViewId = Input::post('dataViewId');

                    if ($dataViewId != null && $dataViewId != '' && $ecmContent) {
                        
                        $sql = "SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = " . $dataViewId;
                        $row = $this->db->GetRow($sql);
                        
                        if ($row && !is_null($row['REF_STRUCTURE_ID'])) {
                            
                            $srcRecordId = Input::post('srcRecordId');
                            $sqlTableName = "SELECT TABLE_NAME FROM META_GROUP_LINK WHERE META_DATA_ID = " . $row['REF_STRUCTURE_ID'];
                            $rowTableName = $this->db->GetRow($sqlTableName);

                            $metaDmRecordMapDataUpdate = array(
                                'SEMANTIC_VALUE' => 0,
                            );

                            $where = ' SRC_TABLE_NAME = \'' . $rowTableName['TABLE_NAME'] . '\' AND SRC_RECORD_ID = ' . $srcRecordId . ' AND TRG_TABLE_NAME = \'ECM_CONTENT\' AND SEMANTIC_TYPE_ID = ' . self::$coverImgSemanticTypeId;
                            $this->db->AutoExecute('META_DM_RECORD_MAP', $metaDmRecordMapDataUpdate, 'UPDATE', $where);

                            $metaDmRecordMapData = array(
                                'ID' => getUID(),
                                'SRC_TABLE_NAME' => $rowTableName['TABLE_NAME'],
                                'SRC_RECORD_ID' => $srcRecordId,
                                'TRG_TABLE_NAME' => 'ECM_CONTENT',
                                'TRG_RECORD_ID' => $contentId,
                                'SEMANTIC_VALUE' => 1,
                                'SEMANTIC_TYPE_ID' => self::$coverImgSemanticTypeId,
                            );

                            $this->db->AutoExecute('META_DM_RECORD_MAP', $metaDmRecordMapData);

                            return array(
                                'message' => Lang::line('msg_save_success'),
                                'status' => 'success',
                                'cover' => $filePath . $newFileName,
                            );
                        } else {
                            return array(
                                'message' => 'Ref structure тохируулаагүй байна.',
                                'status' => 'error'
                            );
                        }
                    }
                }
            }
        }

        return false;
    }

    public function submenuRenderModel() {
        $workSpaceId = Input::numeric('workSpaceId');
        $menuLinkId = Input::post('menuLinkId');
        $menuId = (Input::postCheck('parentMenuId') && Input::isEmpty('parentMenuId') === false) ? Input::post('parentMenuId') : Input::post('menuId');

        $response = array();
        $menuAnchor = $bgColor = $notooltip = $menuclass = $icon = '';
        $menuData = self::getMetaMenuListByModuleIdModel($menuId);

        if ($menuData['status'] === 'success') {
            $menuData = isset($menuData['menuData']['0']['child']) ? $menuData['menuData']['0']['child'] : array();
            $rowData = isset($menuData['menuData']['0']) ? $menuData['menuData']['0'] : array();


            $menuAnchor .= '<div class="col-md-12 pl0 dataview-menu-render"><ul class="grid list-view0" id="main-item-container">';
            foreach ($menuData as $row) {
                $metaIconId = $this->db->GetOne("SELECT META_ICON_ID FROM  META_DATA WHERE META_DATA_ID = '". $row['metadataid'] ."'");
                $metaIconId = ($metaIconId) ? $metaIconId : '69';

                $menuname = Lang::line($row['name']);
                $menutooltip = Lang::line($row['name']);
                $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData);
                $targetLinkAttr = '';
                if (isset($row['child'][0])) {
                    $rowMeta = self::menuAnchor($row['child'][0], $workSpaceId, $rowData, '1');
                    $targetLinkAttr = 'target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" menu-data-link-id = "'. $menuLinkId .'"';
                } else {
                    $rowMeta = self::menuAnchor($row, $workSpaceId, $rowData, '1');
                    $targetLinkAttr = 'target="' . $rowMeta['linkTarget'] . '" onclick="' . $rowMeta['linkOnClick'] . '" menu-data-link-id = "'. $menuLinkId .'"';
                }

                $menuAnchor .=  '<li class="meta menu_meta isactive-1 ui-selectee margin-padding" id="ws-menu-id-'. $row['metadataid'] .'">';
                $menuAnchor .=  '<figure class="directory width-0">';
                    $menuAnchor .= '<a href="javascript:;" data-parent-menuid="'. $menuId .'" attr-' . $row['metadataid'] . ' = "' . $row['metadataid'] . '" data-menu-id="' . $row['metadataid'] . '"' . $targetLinkAttr . $bgColor .'>';
                        $menuAnchor .= '<div class="img-precontainer-mini directory display-block">';
                            $menuAnchor .= '<div class="img-container-mini">';
                                $menuAnchor .= '<img class="directory-img" src="assets/core/global/img/metaicon/small/'. $metaIconId .'.png">';
                            $menuAnchor .= '</div>';
                        $menuAnchor .= '</div>';
                        $menuAnchor .= '<div class="box">';
                            $menuAnchor .= '<h4 class="ellipsis font-size-11" title="' . $menuname . '">'. $menuname .'</h4>';
                        $menuAnchor .= '</div>';
                    $menuAnchor .= '</a>';
                $menuAnchor .= '</figure>';
                $menuAnchor .= '</li>';
            }
            $menuAnchor .= '</ul></div>';
            $response = array('menuAnchor' => $menuAnchor);
        }
        
        return $response;
    }
    
    public function initWorkSpaceWidgetListModel($workSpaceMetaId) {
        return $this->db->GetAll("SELECT 
                                        MWWM.ID, 
                                        MWWM.WIDGET_META_DATA_ID AS META_DATA_ID,
                                        MD.META_DATA_NAME,
                                        MWWM.WIDGET_CRITERIA, 
                                        MWWM.WIDGET_TYPE,
                                        MWWM.WIDGET_POSITION,
                                        MWWM.DISPLAY_ORDER
                                    FROM META_WORKSPACE_WIDGET_MAP MWWM
                                        INNER JOIN META_DATA MD ON MWWM.WIDGET_META_DATA_ID = MD.META_DATA_ID
                                    WHERE MWWM.WORKSPACE_META_ID = $workSpaceMetaId
                                    ORDER BY MWWM.DISPLAY_ORDER ASC");
    }
    
    public function getWidgetDataModel($widgetId) {
        return $this->db->GetRow("SELECT 
                                        MWWM.ID, 
                                        MWWM.WIDGET_META_DATA_ID AS META_DATA_ID,
                                        MD.META_DATA_NAME,
                                        MWWM.WIDGET_CRITERIA, 
                                        MWWM.WIDGET_TYPE,
                                        MWWM.WIDGET_POSITION,
                                        MWWM.DISPLAY_ORDER
                                    FROM META_WORKSPACE_WIDGET_MAP MWWM
                                        INNER JOIN META_DATA MD ON MWWM.WIDGET_META_DATA_ID = MD.META_DATA_ID
                                    WHERE MWWM.ID = $widgetId");
    }
    
    public function updateWorkSpaceWidgetModel() {
        
        $response = array('status' => 'warning', 'message' => Lang::line('message_save_error'));
        
        try {
            
            $data = array(
                'WIDGET_META_DATA_ID' => Input::post('targetMetaId'),
                'WIDGET_CRITERIA' => Input::post('criteria'),
                'WIDGET_TYPE' => Input::post('widgetType'),
                'WIDGET_POSITION' => Input::post('widgetPosition'),
                'DISPLAY_ORDER' => Input::post('diplayOrder'),
            );
            $result = $this->db->AutoExecute('META_WORKSPACE_WIDGET_MAP', $data, "UPDATE", "ID = " . Input::post('widgetId'));

            if ($result) {
                $response = array('status' => 'success', 'message' => Lang::line('message_save_success'));
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function insertWorkSpaceWidgetModel() {

        $response = array('status' => 'warning', 'message' => Lang::line('message_save_error'));
        
        try {
            
            $data = array(
                'ID' => getUID(),
                'WORKSPACE_META_ID' => Input::numeric('metaDataId'),
                'WIDGET_META_DATA_ID' => Input::post('targetMetaId'),
                'WIDGET_CRITERIA' => Input::post('criteria'),
                'WIDGET_TYPE' => Input::post('widgetType'),
                'WIDGET_POSITION' => Input::post('widgetPosition'),
                'DISPLAY_ORDER' => Input::post('diplayOrder'),
            );
            $result = $this->db->AutoExecute('META_WORKSPACE_WIDGET_MAP', $data);

            if ($result) {
                $response = array('status' => 'success', 'message' => Lang::line('message_save_success'));
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function initWorkSpaceWidgetHtmlModel($widgetId) {
        $data = $this->initWorkSpaceWidgetListModel($widgetId);
        $html = '';
        if (count($data) > 0) {
            $i = 1;
            foreach ($data as $row) {
                $html .= '<tr rowId="' . $row['ID'] . '">';
                    $html .= '<td><input type="hidden" name="rowId[]" value="' . $row['ID'] . '">' . $i . '</td>';
                    $html .= '<td>' . $row['META_DATA_NAME'] . '</td>';
                    $html .= '<td>' . Form::select(
                                                    array(
                                                        'readonly' => 'readonly',
                                                        'class' => 'form-control form-control-sm select2',
                                                        'data' => array(
                                                            array('ID' => '1', 'NAME' => 'To Widget'),
                                                            array('ID' => '2', 'NAME' => 'To Workspace')
                                                        ),
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'value' => $row['WIDGET_TYPE'])) . '</td>';
                    $html .= '<td>' . Form::select(
                                                    array(
                                                        'readonly' => 'readonly',
                                                        'class' => 'form-control form-control-sm select2',
                                                        'data' =>  array(
                                                            array('ID' => '1', 'NAME' => 'Top'),
                                                            array('ID' => '2', 'NAME' => 'Right'),
                                                            array('ID' => '3', 'NAME' => 'Bottom'),
                                                            array('ID' => '4', 'NAME' => 'Left')
                                                        ),
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'value' => $row['WIDGET_POSITION']
                                                    )
                                                ) . '</td>';
                    $html .= '<td>' . $row['DISPLAY_ORDER'] . '</td>';
                    $html .= '<td>'
                                . '<div class="btn-group">'
                                    . '<a href="javascript:;" class="btn btn-warning btn-xs mr0" onclick="editWorkSpaceWidget(this)"><i class="fa fa-trash"></i></a>'
                                    . '<a href="javascript:;" class="btn btn-danger btn-xs" onclick="deleteWorkSpaceWidget(this)"><i class="fa fa-trash"></i></a>'
                                . '</div>'
                            . '</td>';
                $html .= '</tr>';
                $i++;
            }
        }

        return $html;
    }
    
    public function deleteWorkSpaceWidgetModel() {
        $response = array('status' => 'warning', 'message' => Lang::line('message_save_error'));
        
        try {
            $result = $this->db->Execute("DELETE FROM META_WORKSPACE_WIDGET_MAP WHERE ID = " . Input::post('rowId'));
            
            if ($result) {
                $response = array('status' => 'success', 'message' => Lang::line('message_save_success'));
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getMetaIcon($metaId) {
        $result = $this->db->GetOne("
            SELECT 
                MI.META_ICON_NAME
            FROM META_DATA MD
                INNER JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID
            WHERE MD.META_DATA_ID = $metaId");
        
        if ($result) {
            return $result;
        }
        return false;
    }
    
    public function getRowDataViewIdByIdModel($srcDvId, $trgDvId, $selectedRowData) {
        
        $configData = $this->db->GetAll("
            SELECT 
                LOWER(SRC_PARAM_NAME) AS SRC_PARAM_NAME, 
                LOWER(TRG_PARAM_NAME) AS TRG_PARAM_NAME 
            FROM META_SRC_TRG_PARAM 
            WHERE SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND TRG_META_DATA_ID = ".$this->db->Param(1), 
            array($srcDvId, $trgDvId)
        );
         
        if ($configData) {
            
            foreach ($configData as $row) {
                
                if (isset($selectedRowData[$row['SRC_PARAM_NAME']])) {
                    
                    $paramCriteria[$row['TRG_PARAM_NAME']][] = array(
                        'operator' => '=',
                        'operand' => $selectedRowData[$row['SRC_PARAM_NAME']]
                    );
                    $isConfig = true;
                }
            }
        }
        
        if (!isset($isConfig)) {
            $paramCriteria['id'][] = array(
                'operator' => '=',
                'operand' => $selectedRowData['id']
            );
        }
        
        $param = array(
            'systemMetaGroupId' => $trgDvId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => $paramCriteria 
        );
        
        $data = $this->ws->runSerializeResponse(Mddatamodel::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if (isset($data['result']) && isset($data['result'][0])) {
            $rowData = $data['result'][0];
            unset($rowData['id']);
            return $rowData;
        }
        
        return null;
    }
    
}

