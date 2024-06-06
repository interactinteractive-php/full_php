<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
echo Form::create(array(
    'class' => 'form-horizontal', 
    'id' => 'form-change-password', 
    'method' => 'post', 
    'autocomplete' => 'off', 
    'minlength' => $this->passwordMinLength
)); 
?>
<div class="col-md-12 xs-form">
    <input type="text" name="username" value=""/>
    <input type="password" name="password" value=""/>
    <?php if (isset($this->showMessage) && $this->showMessage) { ?>
        <div class="form-group row fom-row">
            <div class="alert alert-info alert-styled-left">
                <span><?php echo $this->showMessage ?></span>
            </div>
        </div>
    <?php } ?>    
    <?php if (!isset($this->no_nowpassword)) { ?>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('user_current_password'), 'for' => 'currentPassword', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
            <div class="col-md-8">
                <?php 
                echo Form::password(
                    array(
                        'name' => 'currentPassword', 
                        'id' => 'currentPassword', 
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
    <?php } else {
       echo Form::hidden(array('name' => 'no_nowpassword', 'value' => '1'));
    } ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('user_new_password'), 'for' => 'newPassword', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php 
            echo Form::password(
                array(
                    'name' => 'newPassword', 
                    'id' => 'newPassword', 
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
        <?php echo Form::label(array('text' => $this->lang->line('user_confirm_password'), 'for' => 'confirmPassword', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php 
            echo Form::password(
                array(
                    'name' => 'confirmPassword', 
                    'id' => 'confirmPassword', 
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

<script type="text/javascript">
<?php if (Config::getFromCache('passRequirementsHtml')) { ?>
    var passRequirementsHtml = '<?php echo Config::getFromCache('passRequirementsHtml'); ?>';
<?php } ?>

$(function(){
    
    var $form = $('#form-change-password');
    
    setTimeout(function(){
        $form.find('input[name="username"], input[name="password"]').hide();
    }, 10);
    setTimeout(function(){
        $form.find('#currentPassword').focus();
    }, 400);
    
    $('#newPassword, #confirmPassword').PassRequirements({
        title: '----',
        popoverPlacement: 'left',     
        rules: {
            minlength: {
                text: plang.get('p_character'),
                minLength: <?php echo $this->passwordMinLength; ?>
            },
            containSpecialChars: {
                text: plang.get('p_special_character'),
                minLength: 1,
                regex: new RegExp('([^!,%,&,@,#,$,^,*,?,_,~])', 'g')
            },
            containLowercase: {
                text: plang.get('p_lower_character'),
                minLength: 1,
                regex: new RegExp('[^a-zа-яөү]', 'g')
            },
            containUppercase: {
                text: plang.get('p_upper_character'),
                minLength: 1,
                regex: new RegExp('[^A-ZА-ЯӨҮ]', 'g')
            },
            containNumbers: {
                text: plang.get('p_number'),
                minLength: 1,
                regex: new RegExp('[^0-9]', 'g')
            }
        }
    });
});
</script>
<?php if (Config::getFromCache('passRequirementsHtml')) { ?>
<style type="text/css">
    .popover {
        max-width: 370px !important;
        left: 333.656px !important;
    }
    .popover-body {
        width: 333px;
    }
</style>
<?php } ?>