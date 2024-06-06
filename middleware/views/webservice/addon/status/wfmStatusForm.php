<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Одоогийн төлөв', 'class'=>'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <p class="form-control-plaintext"><span class="badge badge-success">New</span></p>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Дараагийн төлөв', 'class'=>'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <p class="form-control-plaintext"><span class="badge badge-primary">Done</span></p>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Хэрэглэгчид', 'class'=>'col-form-label col-md-4')); ?>
        <div class="col-md-7"></div>
    </div>
</div>