<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'meta-lock-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <input type="password" name="password" value="" style="display:none">
        <input type="text" name="username" value="" style="display:none">
        <?php echo Form::label(array('text' => 'Lock name', 'for' => 'lockName', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-8">
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
        <?php echo Form::label(array('text' => 'Lock pass', 'for' => 'lockPass', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-8">
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
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Тайлбар', 'for' => 'description', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-10">
            <?php 
            echo Form::textArea(
                array(
                    'name' => 'description', 
                    'id' => 'description', 
                    'class' => 'form-control form-control-sm', 
                    'rows' => 3
                )
            ); 
            ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>