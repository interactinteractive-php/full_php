<div class="col-md-12">
    <div class="vr-card-menu animated zoomIn">
        
        <?php
        foreach ($this->menuList as $k => $groupRow) {
            
            echo html_tag('h2', array(), ($k == 'яяяrow') ? $this->lang->line('other_title') : $this->lang->line($groupRow['row']['tagname']));
            
            foreach ($groupRow['rows'] as $row) {
                
                if ($row['code'] == 'ERP_MENU_MOBILE') {
                    continue;
                }
                        
                $linkHref = 'javascript:;';
                $linkTarget = '_self';
                $linkOnClick = '';

                if ($row['isshowcard'] == 'true') {

                    $linkHref = 'appmenu/sub/'.$row['code'];
                    $linkTarget = '_self';
                    $linkOnClick = '';

                } elseif (!empty($row['weburl'])) {

                    if (strtolower(substr($row['weburl'], 0, 4)) == 'http') {
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
        ?>
        <a href="<?php echo $linkHref; ?>" target="<?php echo $linkTarget; ?>" onclick="<?php echo $linkOnClick; ?>" class="vr-menu-tile animated shadow" data-metadataid="<?php echo $row['metadataid']; ?>">
            <div class="vr-menu-img">
                <?php
                $imgSrc = 'assets/custom/img/appmenu.png';
                if ($row['photoname'] != '' && file_exists($row['photoname'])) {
                    $imgSrc = $row['photoname'];
                }
                if ($imgSrc == 'assets/custom/img/appmenu.png' && $row['icon']) {
                    echo '<i style="font-size: 18px;" class="fa '.$row['icon'].'"></i>';
                } else {
                    echo '<img src="'.$imgSrc.'">';
                }                
                ?>
            </div>
            <div class="vr-menu-title">
                <p><?php echo $this->lang->line($row['name']); ?></p>
                <span><?php echo $row['description']; ?></span>
            </div>
        </a>
        <?php
            }
        }
        ?>
        <div class="clearfix"></div>
    </div>
</div>
    