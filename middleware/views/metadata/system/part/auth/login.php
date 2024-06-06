<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'auth-login-form', 'method' => 'post', 'autocomplete' => 'off')); ?>
    <input type="password" autocomplete="password" style="display:none" />
    <input type="password" autocomplete="username" style="display:none" />
    
    <?php
    if (isset($this->metaRow) && $this->metaRow) {
    ?>
    <div class="form-group row mb5">
        <label class="col-md-3 col-form-label">Мета ID:</label>
        <div class="col-md-9">
            <?php echo $this->metaRow['META_DATA_ID']; ?>
        </div>
    </div>
    <div class="form-group row mb5">
        <label class="col-md-3 col-form-label">Мета код:</label>
        <div class="col-md-9">
            <?php echo $this->metaRow['META_DATA_CODE']; ?>
        </div>
    </div>
    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label">Мета нэр:</label>
        <div class="col-md-9">
            <?php echo $this->metaRow['META_DATA_NAME']; ?>
        </div>
    </div>
    <?php
    }
    ?>
    
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'User name', 'for' => 'unlockUserName', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'unlockUserName',
                    'id' => 'unlockUserName',
                    'class' => 'form-control form-control-sm', 
                    'autocomplete' => 'off', 
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Password', 'for' => 'unlockUserPass', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php
            echo Form::password(
                array(
                    'name' => 'unlockUserPass',
                    'id' => 'unlockUserPass',
                    'class' => 'form-control form-control-sm readonly-white-bg', 
                    'autocomplete' => 'off', 
                    'required' => 'required', 
                    'readonly' => 'readonly', 
                    'onfocus' => 'this.removeAttribute(\'readonly\');'
                )
            );
            ?>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>