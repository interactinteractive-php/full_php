<?php
    if ($this->menuData) {
        foreach ($this->menuData as $row) {

            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            $subMenu = $unseenpost = $icon = '';
            
            if (strpos($row['icon'], 'storage/uploads') === false) {
                $icon = '<i class="icon-'.$row['icon'].' font-size-18" style="color: '.issetParam($row['color']).';"></i> ';
            } else {
                $icon = '<img src="'. $row['icon'] .'" data-comment-dd="1" class="mr5" width="18" height="18" alt="" onerror="onFiUserImgError(this);">';
            }
            

            if (issetParam($row['ischild'])) {
                $subMenu = ' nav-item-submenu';
            } 
            if (issetParam($row['unseenpost'])) {
                $unseenpost = '<span class="badge badge-success badge-pill ml-auto mr-3">'. $row['unseenpost'] .'</span>';
            }
            
            ?>
        <li class="nav-item<?php echo $subMenu; ?>" id="menu_<?php echo $this->uniqId ?>_<?php echo $row['posttypeid']; ?>">
            <a href="javascript:void(0);" class="nav-link" data-id="<?php echo $row['id']; ?>" data-listmetadataid='<?php echo $row['id']; ?>' title="<?php echo $row['name'] ?>" data-rowdata="<?php echo $rowJson; ?>">
                <?php echo $icon; ?>
                <span><?php echo $row['name']; ?></span>
                <?php echo $unseenpost; ?>
            </a>
        </li>
        <?php
        }
    }
?>