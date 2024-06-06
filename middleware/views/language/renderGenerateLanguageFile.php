<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form mt10 form-horizontal">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Нийт орчуулга', 'class'=>'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <p class="form-control-plaintext font-weight-bold">( <?php echo $this->globeCount; ?> )</p>
        </div>
    </div>
</div>