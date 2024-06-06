<?php
if ($this->iconList) {
?>
<div class="row">
    <?php
    foreach ($this->iconList as $icon) {
    ?>
    <div class="col-sm-4 col-md-2 mb10">
        <img src="assets/core/global/img/metaicon/big/<?php echo $icon['META_ICON_CODE']; ?>" class="img-thumbnail cursor-pointer" onclick="metaIconSelect('<?php echo $icon['META_ICON_ID']; ?>', '<?php echo $icon['META_ICON_CODE']; ?>');" style="width: 100%;">
    </div>
    <?php
    }
    ?>
</div>
<?php
} else {
    echo "No icon!";
}
?>