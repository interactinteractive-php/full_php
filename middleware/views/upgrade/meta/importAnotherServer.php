<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal')); ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'class' => 'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <span><?php echo $this->metaRow['META_DATA_CODE']; ?></span>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00125'), 'class' => 'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <span><?php echo $this->metaRow['META_DATA_NAME']; ?></span>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('Сүүлд оруулсан огноо'), 'class' => 'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <span><?php echo $this->metaRow['LAST_UPGRADE_DATE']; ?></span>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('Сүүлд оруулсан хэрэглэгч'), 'class' => 'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <span><?php echo $this->metaRow['USERNAME']; ?></span>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Түгжсэн', 'class' => 'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <?php echo $this->personName; ?>
        </div>
    </div>
    <?php 
    if (isset($this->message)) {
        echo html_tag('div', array('class' => 'alert alert-info'), $this->message, true);
    }
    echo Form::close(); 
    ?>
</div>