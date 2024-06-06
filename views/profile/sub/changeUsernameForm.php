<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
echo Form::create(array('class' => 'form-horizontal', 'id' => 'form-change-username', 'method' => 'post', 'autocomplete' => 'off')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('user_name'), 'for' => 'user_name', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php 
            echo Form::text(
                array(
                    'name' => 'user_name', 
                    'id' => 'user_name', 
                    'class' => 'form-control input-sm readonly-white-bg', 
                    'autocomplete' => 'off', 
                    'required' => 'required', 
                    'readonly' => 'readonly', 
                    'onfocus' => 'this.removeAttribute(\'readonly\');'
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('pass_word'), 'for' => 'password', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php 
            echo Form::password(
                array(
                    'name' => 'password', 
                    'id' => 'password', 
                    'class' => 'form-control input-sm readonly-white-bg', 
                    'autocomplete' => 'off', 
                    'required' => 'required', 
                    'readonly' => 'readonly',
                    'onfocus' => 'this.removeAttribute(\'readonly\');'
                )
            ); 
            ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>