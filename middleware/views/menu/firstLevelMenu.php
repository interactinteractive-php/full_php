<span class="page-sidebar-menu-title">
    <?php echo $this->appMenuName; ?>
</span>
<ul class="nav nav-sidebar" data-keep-expanded="false" data-slide-speed="200">
    <?php
    if (isset($this->menuList['menuData'])) {
        $menuData = $this->menuList['menuData'];
        
        foreach ($menuData as $row) {
            
            $icon = '';
            if (!empty($row['icon'])) {
                $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
            }
                        
            echo '<li class="nav-item">';
                echo '<a href="javascript:;" class="nav-link" onclick="renderAppChildMenu(\''.$row['metadataid'].'\');">';
                    echo $icon.'<span class="title">' . $this->lang->line($row['name']) . '</span>';
                echo '</a>';
            echo '</li>';
        }
    }
    ?>
</ul>