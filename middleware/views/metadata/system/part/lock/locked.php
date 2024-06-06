<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'metaLockedForm', 'method' => 'post', 'autocomplete' => 'off')); ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <span><?php echo $this->metaRow['META_DATA_CODE']; ?></span>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00125'), 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <span><?php echo $this->metaRow['META_DATA_NAME']; ?></span>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Түгжсэн', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php echo $this->personName; ?>
        </div>
    </div>
    <?php 
    echo Form::close(); 
    ?>
</div>