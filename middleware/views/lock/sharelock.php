<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'metaShareLockForm', 'method' => 'post', 'autocomplete' => 'off')); ?>
    <input type="password" autocomplete="password" style="display:none" />
    <input type="password" autocomplete="username" style="display:none" />
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lock name', 'for' => 'lockName', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'lockName',
                    'id' => 'lockName',
                    'class' => 'form-control form-control-sm readonly-white-bg', 
                    'required' => 'required', 
                    'readonly' => 'readonly', 
                    'onfocus' => 'this.removeAttribute(\'readonly\');', 
                    'autocomplete' => 'false'
                )
            );
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lock pass', 'for' => 'lockPass', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php
            echo Form::password(
                array(
                    'name' => 'lockPass',
                    'id' => 'lockPass',
                    'class' => 'form-control form-control-sm readonly-white-bg', 
                    'required' => 'required', 
                    'readonly' => 'readonly', 
                    'onfocus' => 'this.removeAttribute(\'readonly\');', 
                    'autocomplete' => 'false'
                )
            );
            ?>
        </div>
    </div>
    
    <hr />
    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Хэрэглэгч', 'for' => 'userId', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php
            echo Form::select(
                array(
                    'name' => 'userId',
                    'id' => 'userId',
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->users, 
                    'op_value' => 'USER_ID', 
                    'op_text' => 'USERNAME', 
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Дуусах хугацаа', 'for' => 'endTime', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'endTime',
                    'id' => 'endTime',
                    'class' => 'form-control form-control-sm datetimeInit', 
                    'required' => 'required', 
                    'value' => Date::currentDate('Y-m-d H:i:s')
                )
            );
            ?>
        </div>
    </div>
    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Тайлбар', 'for' => 'description', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::textArea(
                array(
                    'name' => 'description', 
                    'id' => 'description', 
                    'class' => 'form-control form-control-sm', 
                    'rows' => 4, 
                    'required' => 'required'
                )
            ); 
            ?>
        </div>
    </div>
    
    <?php echo Form::close(); ?>
</div>