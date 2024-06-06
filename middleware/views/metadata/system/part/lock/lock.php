<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'metaLockForm', 'method' => 'post', 'autocomplete' => 'off')); ?>
    <input type="password" autocomplete="password" style="display:none" />
    <input type="password" autocomplete="username" style="display:none" />
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9"><span><?php echo $this->metaRow['META_DATA_CODE']; ?></span></div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00125'), 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9"><span><?php echo $this->metaRow['META_DATA_NAME']; ?></span></div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lock name', 'for' => 'lockName', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'lockName',
                    'id' => 'lockName',
                    'class' => 'form-control form-control-sm', 
                    'autocomplete' => 'false', 
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lock pass', 'for' => 'lockPass', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php
            echo Form::password(
                array(
                    'name' => 'lockPass',
                    'id' => 'lockPass',
                    'class' => 'form-control form-control-sm', 
                    'autocomplete' => 'false', 
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    <?php 
    echo Form::hidden(array('name' => 'id', 'value' => $this->id)); 
    echo Form::close(); 
    ?>
</div>