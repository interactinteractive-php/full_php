<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'request-edit-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Тайлбар', 'for' => 'description', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::textArea(
                array(
                    'name' => 'description', 
                    'id' => 'description', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required', 
                    'rows' => 7
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Дуусах хугацаа', 'for' => 'endTime', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-4">
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
</div>
<?php echo Form::close(); ?>