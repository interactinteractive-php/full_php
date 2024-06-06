<?php
foreach ($this->dataList as $row) {
?>
<div class="imp-file-item" data-id="<?php echo $row['ID']; ?>">
    <div class="imp-file-item-name"><?php echo $row['NAME']; ?></div>
    <div class="imp-file-item-date"><?php echo $row['CREATED_DATE']; ?></div>
</div>
<?php 
} 
?>

