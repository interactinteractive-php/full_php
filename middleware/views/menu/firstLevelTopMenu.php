<span class="page-topbar-menu-title">
    <?php echo $this->appMenuName; ?>
</span>
<ul class="page-topbar-menu">
    <?php
    if (isset($this->menuList['menuData'])) {
        $menuData = $this->menuList['menuData'];
        
        foreach ($menuData as $row) {
            
            $icon = '';
            if (!empty($row['icon'])) {
                $icon = '<i class="fa ' . $row['icon'] . '"></i> ';
            }
                        
            echo '<li>';
                echo '<a href="javascript:;" onclick="renderAppChildTopMenu(\''.$row['metadataid'].'\');">';
                    echo $icon.'<span class="title">' . $this->lang->line($row['name']) . '</span>';
                echo '</a>';
            echo '</li>';
        }
    }
    ?>
</ul>