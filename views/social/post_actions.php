<li class="main-link">
    <?php
    if ($this->isSaved) {
    ?>
    <a href="javascript:;" data-post-id="<?php echo $this->postId; ?>" class="post-unsave-item">
        <i class="icon-cross2 font-size-12" aria-hidden="true"></i> Хадгалсныг болих
    </a>
    <?php
    } else {
    ?>
    <a href="javascript:;" data-post-id="<?php echo $this->postId; ?>" class="post-save-item">
        <i class="fa fa-save" aria-hidden="true"></i> Хадгалах
    </a>
    <?php
    }
    ?>
</li>
<?php
if ($this->userId == $this->savedUserId) {
?>
<!--<li class="main-link">
    <a href="javascript:;" data-post-id="<?php echo $this->postId; ?>" class="edit-post">
        <i class="fa fa-edit" aria-hidden="true"></i> Засах
    </a>
</li>-->
<li class="main-link">
    <a href="javascript:;" data-post-id="<?php echo $this->postId; ?>" class="delete-post">
        <i class="fa fa-trash" aria-hidden="true"></i> Устгах
    </a>
</li>
<?php
}
?>