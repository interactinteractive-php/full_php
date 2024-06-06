<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'criteriaFilterSaveForm', 'method' => 'post', 'autocomplete' => 'off')); ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Код', 'for' => 'filterCode', 'class'=>'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'filterCode',
                    'id' => 'filterCode',
                    'class' => 'form-control form-control-sm', 
                    'autocomplete' => 'off', 
                    'required' => 'required'
                )
            ); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Нэр', 'for' => 'filterName', 'class'=>'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'filterName',
                    'id' => 'filterName',
                    'class' => 'form-control form-control-sm', 
                    'autocomplete' => 'off', 
                    'required' => 'required'
                )
            ); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Тайлбар', 'for' => 'filterDesc', 'class'=>'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php
            echo Form::textArea(
                array(
                    'name' => 'filterDesc',
                    'id' => 'filterDesc',
                    'rows' => '4',
                    'class' => 'form-control form-control-sm'
                )
            ); ?>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>