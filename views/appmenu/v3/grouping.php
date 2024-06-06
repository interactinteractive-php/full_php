<div class="col-md-12">
    <div class="white-card-menu animated zoomIn">
        <?php
        foreach ($this->menuList as $k => $groupRow) {
            
            echo html_tag('h2', array(), ($k == 'яяяrow') ? $this->lang->line('other_title') : $this->lang->line($groupRow['row']['tagname']));
            
            foreach ($groupRow['rows'] as $row) {
                
                if ($row['code'] == 'ERP_MENU_MOBILE') {
                    continue;
                }
                        
                $linkHref = 'javascript:;';
                $linkTarget = '_self';
                $linkOnClick = $class = '';

                if ($row['isshowcard'] == 'true') {

                    $linkHref = 'appmenu/sub/'.$row['code'];
                    $linkTarget = '_self';
                    $linkOnClick = '';

                } elseif (!empty($row['weburl'])) {

                    if (strtolower(substr($row['weburl'], 0, 4)) == 'http' || $row['weburl'] == 'appmenu/kpi') {
                        $linkHref = $row['weburl'];
                    } else {
                        $linkHref = $row['weburl'].'&mmid='.$row['metadataid'];                
                    }

                    $linkTarget = $row['urltrg'];
                    $linkOnClick = '';

                } elseif (empty($row['weburl']) && empty($row['actionmetadataid'])) {

                    $linkHref = 'appmenu/module/'.$row['metadataid'].'?mmid='.$row['metadataid'];
                    $linkTarget = '_self';
                    $linkOnClick = '';

                } else {

                    if ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
                        $linkHref = 'appmenu/module/'.$row['metadataid'].'/'.$row['actionmetadataid'].'?mmid='.$row['metadataid'];
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
                        $linkOnClick = "appLicenseExpireBefore(this, '".$row['licenseenddate']."', '".$row['licenseremainingdays']."', '$linkHref');";
                        $linkHref = 'javascript:;';
                        $linkTarget = '_self';
                    } elseif ($row['licensestatus'] == '3') {
                        $linkOnClick = "appLicenseExpireWait(this, '".$row['licenseenddate']."', '".$row['licenseremainingdays']."', '$linkHref');";
                        $linkHref = 'javascript:;';
                        $linkTarget = '_self';
                    } elseif ($row['licensestatus'] == '4') {
                        $linkHref = 'javascript:;';
                        $linkTarget = '_self';
                        $linkOnClick = "appLicenseExpired(this, '".$row['licenseenddate']."');";
                        $class = ' disabled';
                    }
                }
        ?>
        <a href="<?php echo $linkHref; ?>" target="<?php echo $linkTarget; ?>" onclick="<?php echo $linkOnClick; ?>" class="vr-menu-tile animated<?php echo $class; ?>" data-metadataid="<?php echo $row['metadataid']; ?>" data-pfgotometa="1">
            <div class="vr-menu-cell">
                <div class="vr-menu-img">
                    <?php
                    if ($row['photoname'] != '' && file_exists($row['photoname'])) {
                        $imgSrc = $row['photoname'];
                    } else {
                        $imgSrc = 'assets/core/global/img/appmenu.png';
                    }
                    echo '<img src="'.$imgSrc.'">';
                    ?>
                </div>
            </div>    
            <div class="vr-menu-title">
                <div class="vr-menu-row">
                    <div class="vr-menu-name" data-app-name="true"><?php echo $this->lang->line($row['name']); ?></div>
                </div>
            </div>
            <div class="vr-menu-descr"><?php echo $row['description']; ?></div>
        </a>
        <?php
            }
        }
        ?>
        <div class="clearfix"></div>
    </div>
</div>
    