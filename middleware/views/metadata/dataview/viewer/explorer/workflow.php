<?php
$movedDate = '';
$dueDate = 'Хугацаа сонгоогүй';

if ($this->logUser) {
    $movedDate = Date::formatter($this->logUser['CREATED_DATE'], 'Y/m/d');
    if (isset($this->logUser['DUE_DATE'])) {
        $dueDate = Date::formatter($this->logUser['DUE_DATE'], 'Y/m/d');
    }
?>
Хэнээс:
<div class="wfm-users">
    <div class="wfm-user">
        <div class="wfm-user-picture">
            <img src="assets/core/global/img/avatar.png" class="rounded-circle">
        </div>
        <div class="wfm-user-name">
            <?php echo $this->logUser['FIRST_NAME']; ?>
        </div>
    </div>
</div>
<div class="clearfix w-100"></div>
<hr class="mt10 mb10"/>
<?php
}
?>
<div class="row">
    <div class="col-md-5 pr0">
        Шилжүүлсэн: 
    </div>
    <div class="col-md-6 pr0">
        <?php echo $movedDate; ?>
    </div>
    <div class="col-md-5 pr0">
        Cүүлийн хугацаа:
    </div>
    <div class="col-md-6 pr0">
        <?php echo $dueDate; ?>
    </div>
</div>
<hr class="mt10 mb10"/>

<?php echo $this->wfmStatusButtons; ?>