<li class="nav-item not-module-menu">
    <a href="<?php echo URL; ?>appmenu" class="navbar-nav-link" target="_self"><span class="title"><i class="icon-home"></i></span></a>
</li>
<li class="nav-item dropdown appmenu-menu-list not-module-menu">
    <a href="#appm" class="navbar-nav-link dropdown-toggle ihome pl-0" style="margin-left:-8px;" data-toggle="dropdown" aria-expanded="false">        
    </a>
    <div class="dropdown-menu">
        <?php 
        
        $cloneMenuList = $this->menuList;
        
        $firstModule = $firstModuleCode = '';
        $i = 0;
        
        foreach ($cloneMenuList as $k => $groupRow) {
            $cards = $subAppmenu = '';

            if (empty($groupRow['row']['ptagcode'])) {
                
                $activeClass = '';
                
                if ($k == 'яяяrow') {
                    $k = 'other';
                    $title = $this->lang->line('othermenu_title');
                } else {
                    $title = $this->lang->line($groupRow['row']['tagname']);
                }

                if ($i == 0) {
                    $activeClass = ''; //' active';
                    $firstModule = $title;
                    $firstModuleCode = $k;
                }

                
                if (!empty($groupRow['row']['tagcode'])) {
                    
                    foreach ($cloneMenuList as $ck => $childRow) { 

                        if ($groupRow['row']['tagcode'] == $childRow['row']['ptagcode']) {    
                            
                            $ctitle = $this->lang->line($childRow['row']['tagname']);

                            // $subAppmenu .= '<a href="#" class="dropdown-item">';
                            // $subAppmenu .= $ctitle;
                            // $subAppmenu .= '</a>';

                            $subAppmenu .= '<div class="dropdown-submenu">';
                            $subAppmenu .= '<a href="#" class="dropdown-item dropdown-toggle">' . $ctitle . '</a>';
                            $subAppmenu .= '<div class="dropdown-menu">';
                            foreach ($cloneMenuList[$ck]['rows'] as $row) {

                                if ($row['code'] == 'ERP_MENU_MOBILE') {
                                    continue;
                                }
                                
                                $linkHref = 'javascript:;';
                                $linkTarget = '_self';
                                $linkOnClick = $class = '';
                
                                if ($row['isshowcard'] == 'true') {
                                    $linkHref = 'appmenu/sub/' . $row['code'];
                                    $linkTarget = '_self';
                                    $linkOnClick = '';
                                    
                                } elseif (!empty($row['weburl'])) {
                
                                    if (strtolower(substr($row['weburl'], 0, 4)) == 'http') {
                                        $linkHref = $row['weburl'];
                                    } else {
                                        $linkHref = $row['weburl'] . '&mmid=' . $row['metadataid'];
                                    }
                
                                    $linkTarget = $row['urltrg'];
                                    $linkOnClick = '';
                                    
                                } elseif (empty($row['weburl']) && empty($row['actionmetadataid'])) {
                
                                    $linkHref = 'appmenu/module/' . $row['metadataid'] . '?mmid=' . $row['metadataid'];
                                    $linkTarget = '_self';
                                    $linkOnClick = '';
                                    
                                } else {
                
                                    if ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
                                        $linkHref = 'appmenu/module/' . $row['metadataid'] . '/' . $row['actionmetadataid'] . '?mmid=' . $row['metadataid'];
                                        $linkTarget = '_self';
                                        $linkOnClick = '';
                                    } else {
                                        $linkMeta = Mdmeta::menuServiceAnchor($row, $row['metadataid'], $row['metadataid']);
                                        $linkHref = $linkMeta['linkHref'];
                                        $linkTarget = $linkMeta['linkTarget'];
                                        $linkOnClick = $linkMeta['linkOnClick'];
                                    }
                                }
                
                                if (isset($row['licensestatus'])) {
                
                                    if ($row['licensestatus'] == '2') {
                                        $linkOnClick = "appLicenseExpireBefore(this, '" . $row['licenseenddate'] . "', '" . $row['licenseremainingdays'] . "', '$linkHref');";
                                        $linkHref = 'javascript:;';
                                        $linkTarget = '_self';
                                    } elseif ($row['licensestatus'] == '3') {
                                        $linkOnClick = "appLicenseExpireWait(this, '" . $row['licenseenddate'] . "', '" . $row['licenseremainingdays'] . "', '$linkHref');";
                                        $linkHref = 'javascript:;';
                                        $linkTarget = '_self';
                                    } elseif ($row['licensestatus'] == '4') {
                                        $linkHref = 'javascript:;';
                                        $linkTarget = '_self';
                                        $linkOnClick = "appLicenseExpired(this, '" . $row['licenseenddate'] . "');";
                                        $class = ' disabled';
                                    }
                                }
                                    
                                $subAppmenu .= '<a href="' . $linkHref . '" target="' . $linkTarget . '" onclick="' . $linkOnClick . '" data-metadataid="' . $row['metadataid'] . '" class="dropdown-item">';
                                $subAppmenu .= $this->lang->line($row['name']);
                                $subAppmenu .= '</a>'; 
                            }
                            $subAppmenu .= '</div>';
                            $subAppmenu .= '</div>';
                            
                            unset($cloneMenuList[$ck]);
                        }
                    }
                }
                

                $cards .= '<div class="dropdown-menu">';
                foreach ($groupRow['rows'] as $row) {
                
                    if ($row['code'] == 'ERP_MENU_MOBILE') {
                        continue;
                    }
                    
                    $linkHref = 'javascript:;';
                    $linkTarget = '_self';
                    $linkOnClick = $class = '';
    
                    if ($row['isshowcard'] == 'true') {
    
                        $linkHref = 'appmenu/sub/' . $row['code'];
                        $linkTarget = '_self';
                        $linkOnClick = '';
                        
                    } elseif (!empty($row['weburl'])) {
    
                        if (strtolower(substr($row['weburl'], 0, 4)) == 'http') {
                            $linkHref = $row['weburl'];
                        } else {
                            $linkHref = $row['weburl'] . '&mmid=' . $row['metadataid'];
                        }
    
                        $linkTarget = $row['urltrg'];
                        $linkOnClick = '';
                        
                    } elseif (empty($row['weburl']) && empty($row['actionmetadataid'])) {
    
                        $linkHref = 'appmenu/module/' . $row['metadataid'] . '?mmid=' . $row['metadataid'];
                        $linkTarget = '_self';
                        $linkOnClick = '';
                        
                    } else {
    
                        if ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
                            $linkHref = 'appmenu/module/' . $row['metadataid'] . '/' . $row['actionmetadataid'] . '?mmid=' . $row['metadataid'];
                            $linkTarget = '_self';
                            $linkOnClick = '';
                        } else {
                            $linkMeta = Mdmeta::menuServiceAnchor($row, $row['metadataid'], $row['metadataid']);
                            $linkHref = $linkMeta['linkHref'];
                            $linkTarget = $linkMeta['linkTarget'];
                            $linkOnClick = $linkMeta['linkOnClick'];
                        }
                    }
    
                    if (isset($row['licensestatus'])) {
    
                        if ($row['licensestatus'] == '2') {
                            $linkOnClick = "appLicenseExpireBefore(this, '" . $row['licenseenddate'] . "', '" . $row['licenseremainingdays'] . "', '$linkHref');";
                            $linkHref = 'javascript:;';
                            $linkTarget = '_self';
                        } elseif ($row['licensestatus'] == '3') {
                            $linkOnClick = "appLicenseExpireWait(this, '" . $row['licenseenddate'] . "', '" . $row['licenseremainingdays'] . "', '$linkHref');";
                            $linkHref = 'javascript:;';
                            $linkTarget = '_self';
                        } elseif ($row['licensestatus'] == '4') {
                            $linkHref = 'javascript:;';
                            $linkTarget = '_self';
                            $linkOnClick = "appLicenseExpired(this, '" . $row['licenseenddate'] . "');";
                            $class = ' disabled';
                        }
                    }
                        
                    $cards .= '<a href="' . $linkHref . '" target="' . $linkTarget . '" onclick="' . $linkOnClick . '" data-metadataid="' . $row['metadataid'] . '" data-pfgotometa="1" class="dropdown-item">';
                    $cards .= $this->lang->line($row['name']);
                    $cards .= '</a>';                    
                }
                $cards .= $subAppmenu;
                $cards .= '</div>';  

                echo '<div class="dropdown-submenu">';
                echo '<a href="#" class="dropdown-item dropdown-toggle">' . Str::upper($title) . '</a>' . $cards;
                echo '</div>';
            }

            $i++;
        }
        ?>
    </div>
</li>
