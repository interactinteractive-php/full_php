<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'metaPasswordForm', 'method' => 'post', 'autocomplete' => 'off')); ?>
    <input type="password" autocomplete="password" style="display:none" />
    <input type="password" autocomplete="username" style="display:none" />
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>$this->lang->line('META_00094'), 'for' => 'passwordHash', 'class'=>'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <div class="input-group">
                <?php
                echo Form::password(
                    array(
                        'name' => 'passwordHash',
                        'id' => 'passwordHash',
                        'class' => 'form-control form-control-sm', 
                        'autocomplete' => 'false', 
                        'required' => 'required'
                    )
                );
                ?>
                <span class="input-group-btn">
                    <button class="btn default btn-sm mr0" type="button" onclick="metaPasswordShow(this);"><i class="fa fa-eye"></i></button>
                </span>
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>