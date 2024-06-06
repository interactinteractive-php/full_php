<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal ', 'id' => 'metaunlock-getpass-mode', 'method' => 'post')); ?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" name="getPassMode" value="byEmail" checked="checked"> <?php echo $this->lang->line('by_email'); ?>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" name="getPassMode" value="byPhoneNumber"> <?php echo $this->lang->line('by_phone_number'); ?>
                </label>
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>