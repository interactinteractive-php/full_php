<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'create-configbackup-form', 'method' => 'post')); ?>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>$this->lang->line('META_00007'), 'for' => 'description', 'class'=>'col-form-label col-md-3')); ?>
            <div class="col-md-9">
                <?php echo Form::textArea(array('name' => 'description', 'class'=>'form-control form-control-sm', 'rows' => 4)); ?>
            </div>
        </div>
    <?php echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); ?>
    <?php echo Form::close(); ?>
</div>