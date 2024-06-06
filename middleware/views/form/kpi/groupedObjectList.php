<?php
if ($this->groupedObjectList) {
    foreach ($this->groupedObjectList as $row) {
?>
<a href="javascript:void(0);" class="tab-style2">
    <div class="d-flex align-items-center">
        <div class="tab-cell">
            <i class="<?php echo $row['icon'] ? $row['icon'] : 'icon-home'; ?>"></i>
        </div>
        <div class="tab-title"><?php echo $row['templatename']; ?></div>
        <div class="tab-count"><?php echo $row['count']; ?></div>
    </div>
</a>
<?php
    }
} 
?>
