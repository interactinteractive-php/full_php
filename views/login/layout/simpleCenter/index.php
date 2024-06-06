<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<?php echo Form::create(array('id' => 'login-form', 'class' => 'login-form', 'method' => 'post', 'action' => 'login/run', 'autocomplete' => 'off')); ?>

<div class="content-wrapper loginbody <?php echo $this->color_mode ?> login_center_layout" style="background: url('<?php echo isset($this->background[0]['image']) ? 'api/viewimage?src=' . str_replace('storage/uploads/', '', $this->background[0]['image']) : 'assets/custom/img/background/erp-login-left.jpg'; ?>') ">
    <div class="content d-flex justify-content-center align-items-center">
        <div class="card card-transfarent mb-0">
            <div class="text-center profile mb-3">
                <img class="logo_center" src="<?php echo isset($this->mainLogo) ? 'api/viewimage?src=' . str_replace('storage/uploads/', '', $this->mainLogo) : 'assets/custom/img/veritech_logo.png'; ?>">
            </div>
            <div class="login_center_body p15">

                <?php 
                echo ($this->loginTitle == '&nbsp;' || $this->loginTitle == '') ? '' : '<p class="line-height-normal mb15">' .$this->loginTitle. '</p>';
                echo '<span class="d-block text-muted display-message">'.Message::display().'</span>';
                echo $this->selectMultiDbControl;
                ?>
           
                <div class="form-group form-group-feedback form-group-feedback-left">
                    <?php echo Form::text(array('name' => 'user_name', 'id' => 'user_name', 'class' => 'form-control placeholder-no-fix', 'placeholder' => $this->lang->line('user_name'), 'required' => 'required', 'autocomplete' => 'off')); ?>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                    <span id="user_name-error" class="help-block" style="display: none;">This field is required.</span>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <?php echo Form::password(array('name' => 'pass_word', 'class' => 'form-control placeholder-no-fix', 'placeholder' => $this->lang->line('pass_word'), 'required' => 'required', 'autocomplete' => ($this->isLoginSaveUsername ? 'new-password' : 'off'))); ?>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                    <span id="pass_word-error" class="help-block" style="display: none;">This field is required.</span>
                </div>
                <div>
                    <?php 
                    echo html_tag('div', 
                        array('class' => 'ldap activedir mt-2'), 
                        '<label><span class="isLdapCheckBox"><input type="checkbox" name="isSaveUsername" value="1" class="notuniform mr-1"></span> <span>'.Lang::line('PF_SAVE_USERNAME').'</span></label>', 
                        $this->isLoginSaveUsername
                    ); 
                    echo html_tag('div', array('class' => 'ldap activedir mt-2'), $this->ldapControl, $this->isLDap); 
                    ?>
                </div>    
                <?php
                if (isset($this->isLoginCaptcha) && $this->isLoginCaptcha) {
                ?>
                <div class="form-group mt-3 captcha rom-row">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <img src="api/captcha?fDownload=1" id="captcha"><br />
                            <a href="javascript:;" onclick="new_captcha();" class="captcha-refresh" tabindex="-1"><?php echo $this->lang->line('captcha_new_code'); ?></a>
                        </span>
                        <?php echo Form::text(array('name' => 'security_code', 'id' => 'security_code', 'class' => 'form-control', 'placeholder' => $this->lang->line('captcha_typing_code'), 'required' => 'required', 'autocomplete' => 'off')); ?>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="form-actions mt-3 d-flex align-items-center justify-content-between">    
                    <?php 
                    echo Form::submit(array('style' => 'background:#1BBC9B;', 'class' => 'btn btn-primary  green-meadow float-left', 'value' => $this->lang->line('login_btn') . '')); 
                    // echo Form::button(array('class' => ' hidden btn btn-primary float-right ', 'value' => 'E TOKEN MonPass', 'onclick' => 'ShowTokenLoginWin();'), $this->isEToken);
                    ?> 
                    <div class="form-group forgetpass mb-0">
                        <?php echo html_tag('a', array('href' => 'login/password_reset', 'class' => 'forget-password ml-auto'), $this->lang->line('onlineanket_forgotpass'), Config::getFromCache('hideLoginForgotPassword') == '1' ? false : true);?>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <span>Powered by <a href="http://www.veritech.mn" target="_blank" rel="noopener noreferrer">Veritech ERP</a></span>
            </div>
        </div>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'csrf_token', 'value' => $this->csrf_token)); 
echo Form::close(); 

if ($this->isEToken) {
    echo Form::create(array('id' => 'etoken-form', 'class' => 'etoken-form', 'method' => 'post', 'action' => 'login/runEToken', 'autocomplete' => 'off', 'style' => 'display: none;')); 
    echo Form::text(array('name' => 'seasonId', 'id' => 'seasonId', 'class' => 'form-control placeholder-no-fix', 'required' => 'required', 'autocomplete' => 'off')); 
    echo Form::close(); 
}
?>