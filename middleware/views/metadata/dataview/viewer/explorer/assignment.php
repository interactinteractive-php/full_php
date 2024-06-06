<?php
if ($this->assignmentUsers) {
?>
<div class="wfm-users">
    <?php
    foreach ($this->assignmentUsers as $assignmentUser) {
    ?>
    <div class="wfm-user">
        <div class="wfm-user-picture">
            <img src="assets/core/global/img/avatar.png" class="rounded-circle">
        </div>
        <div class="wfm-user-name">
            <?php echo $assignmentUser['FIRST_NAME']; ?>
        </div>
    </div>
    <?php
    }
    ?>
</div>
<div class="clearfix w-100"></div>
<hr class="mt10 mb10"/>
<?php
}
?>