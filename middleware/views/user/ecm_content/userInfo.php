<div class="media">
    <div class="mr-2">
        <?php echo Ue::getFullUrlPhoto($this->row['PICTURE'], 'class="rounded-circle avatar" width="42" height="42"'); ?>
    </div>
    <div class="media-body">
        <h6 class="mb-0"><?php echo ($this->row['FIRST_NAME'] ? $this->row['FIRST_NAME'] : $this->row['USERNAME']); ?></h6>
        <span class="text-muted"><?php echo Date::formatter($this->row['CREATED_DATE'], 'Y/m/d H:i'); ?></span>
    </div>
</div>
<?php
if ($this->row['FILE_NAME']) {
?>
<div class="mt-2" style="border-top: 1px #eee solid; padding-top: 10px">
    <?php echo $this->row['FILE_NAME']; ?>
</div>
<?php
}
?>