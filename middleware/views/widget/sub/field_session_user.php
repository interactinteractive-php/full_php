<div class="media">
    <div class="mr-2">
        <?php echo Ue::getSessionPhoto('class="rounded-circle" width="38" height="38"'); ?>
    </div>
    <div class="media-body">
        <div class="font-weight-semibold"><?php echo Ue::getSessionPersonWithLastName(); ?></div>
        <span class="text-muted"><?php echo Ue::getSessionUserKeyName('CompanyName'); ?></span>
    </div>
    <div class="d-none">
        <?php echo $this->control; ?>
    </div>
</div>