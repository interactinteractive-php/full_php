<?php if ($this->menuList) { ?>
    <div class="page-actions btn-group">
        <button type="button" class="btn btn-sm dropdown-toggle module-quickmenu-btn pt2 pb2" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="Quick menu">
            <i class="icon-star-full2"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-left" role="menu">
            <?php
            foreach ($this->menuList as $menu) {
                
                $attr = Mduser::renderQuickMenuAnchor($menu);
            ?>
                <a href="<?php echo $attr['linkHref']; ?>" onclick="<?php echo $attr['linkOnClick']; ?>" class="dropdown-item" data-qmid="<?php echo $attr['linkId']; ?>" data-qm-hotkey="<?php echo $menu['HOT_KEY']; ?>">
                    <i class="icon-arrow-right5"></i> <?php echo $this->lang->line($menu['MENU_NAME']); ?> 
                    <?php echo ($menu['HOT_KEY']) ? '<span class="badge badge-pill bg-grey-300 ml-auto" title="Hotkey">'.$menu['HOT_KEY'].'</span>' : ''; ?>
                </a>
            <?php
            }
            ?>
        </div>
    </div>
<?php } ?>