<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<?php echo Form::create(array('id' => 'password-reset-form', 'class' => 'login-form', 'method' => 'post', 'action' => 'login/activeAccountByPassSubmit', 'autocomplete' => 'off')); ?>
<div class="row loginbody">
    <div class="col leftside" style="background: url('<?php echo isset($this->background) ? "api/viewimage?src=" . str_replace('storage/uploads/', '', $this->background) : 'assets/custom/img/background/erp-login-left.jpg?v=1'; ?>') ">
        <?php
        if (isset($this->logo)) {
        ?>
        <div class="left-logo">
            <img src="api/viewimage?src=<?php echo str_replace('storage/uploads/', '', $this->logo); ?>">
        </div>
        <?php
        }
        ?>
        <div class="d-flex flex-column titles">
            <h3><?php echo $this->mainLoginTitle; ?></h3>
            <p><?php echo $this->loginTitle; ?></p>
        </div>
    </div>
    <div class="col-md-auto rightside">
        <div class="w-100">
            <div class="text-center mb-2">
                <img class="logo mt-5" src="<?php echo isset($this->mainLogo) ? 'api/viewimage?src=' . str_replace('storage/uploads/', '', $this->mainLogo) : 'assets/custom/img/veritech_logo.png'; ?>">
                <h3 class="mb-3"><?php echo $this->title; ?></h3>
                <span class="d-block text-muted"><?php echo Message::display(); ?></span>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
                <div class="input-icon">
                    <?php echo Form::password(array('name' => 'p_new_password', 'id' => 'p_new_password', 'class' => 'form-control placeholder-no-fix', 'placeholder' => $this->lang->line('user_new_password'), 'required' => 'required', 'autocomplete' => 'off', 'tabindex' => 1)); ?>
                    <div class="form-control-feedback">
                        <i class="icon-key text-muted"></i>
                    </div>
                </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
                <div class="input-icon">
                    <?php echo Form::password(array('name' => 'p_new_password_confirm', 'id' => 'p_new_password_confirm', 'class' => 'form-control placeholder-no-fix', 'placeholder' => $this->lang->line('user_confirm_password'), 'required' => 'required', 'autocomplete' => 'off', 'tabindex' => 2)); ?>
                    <div class="form-control-feedback">
                        <i class="icon-key text-muted"></i>
                    </div>
                </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
                <div class="input-group">
                    <span class="input-group-btn">
                        <img src="api/captcha?fDownload=1" id="captcha"><br />
                        <a href="javascript:;" onclick="new_captcha();" class="captcha-refresh"><?php echo $this->lang->line('captcha_new_code'); ?></a>
                    </span>
                    <?php echo Form::text(array('name' => 'security_code', 'id' => 'security_code', 'class' => 'form-control pl-2', 'placeholder' => $this->lang->line('captcha_typing_code'), 'required' => 'required', 'autocomplete' => 'off', 'tabindex' => 3)); ?>
                </div>
            </div>
            <div class="form-actions mt-4">
                <?php echo Form::submit(array('class' => 'btn btn-primary btn-block', 'value' => $this->lang->line('save_btn') . ' <i class="m-icon-swapright m-icon-white"></i>', 'tabindex' => 3)); ?>
                <a href="login" style="background:#555;" class="forget-password btn btn-primary btn-block"><?php echo $this->lang->line('login_title'); ?></a>
            </div>
        </div>
        <?php echo (Config::getFromCache('isIgnorePoweredByTitle') != '1') ? '<div class="login-copyright">'. $this->loginFooterTitle .'</div>' : ''; ?>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'csrf_token', 'value' => $this->csrf_token)); 
echo Form::hidden(array('name' => 'aapud', 'value' => $this->userId));
echo Form::hidden(array('name' => 'aaph', 'value' => $this->hash));
echo Form::close(); 
?>