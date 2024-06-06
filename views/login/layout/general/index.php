<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<?php echo Form::create(array('id' => 'login-form', 'class' => 'login-form', 'method' => 'post', 'action' => 'login/run', 'autocomplete' => 'off')); ?>
<div class="row loginbody">
    <div class="col leftside carousel slide" id="loginSlider" data-ride="carousel">
        <?php
        if (isset($this->background) && count($this->background) > 1) {
            
            $indicators = $item = '';
            
            foreach ($this->background as $key => $val) {
                
                $itemClass = $imageCaption = '';
                
                if ($key == 0) {
                    $itemClass = 'active';
                }
                
                $indicators .= '<li data-target="#loginSlider" data-slide-to="'.$key.'" class="'.$itemClass.'"></li>';
                
                if ($val['title'] || $val['descr']) {
                    $imageCaption = '<div class="carousel-caption d-none d-md-block titles">
                        '.($val['title'] ? '<h3 class="mb-0">'.$val['title'].'</h3>' : '').'
                        '.($val['descr'] ? '<p>'.$val['descr'].'</p>' : '').'
                    </div>';
                }
                
                $item .= '<div class="carousel-item '.$itemClass.'">
                    <img class="img-slide" src="api/viewimage?src=' . str_replace('storage/uploads/', '', $val['image']) . '">
                    '.$imageCaption.'    
                </div>';
            }
        ?>
        <ol class="carousel-indicators">
            <?php echo $indicators; ?>
        </ol>
        <div class="carousel-inner">
            <?php echo $item; ?>
        </div>
        <div class="d-flex flex-column titles">
            <h3><?php echo $this->mainLoginTitle; ?></h3>
            <p><?php echo $this->loginTitle; ?></p>
        </div>
        <a class="carousel-control-prev" href="#loginSlider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Өмнөх</span>
        </a>
        <a class="carousel-control-next" href="#loginSlider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Дараах</span>
        </a>
        <?php
        } else {
            $backgroundUrl = (isset($this->background[0]['image']) ? 'api/viewimage?src=' . str_replace('storage/uploads/', '', $this->background[0]['image']) : 'assets/custom/img/background/erp-login-left.jpg?v=1');
        ?>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="img-slide" src="<?php echo $backgroundUrl ?>">
            </div>
        </div>
        <div class="d-flex flex-column titles">
            <h3><?php echo $this->mainLoginTitle; ?></h3>
            <?php if (issetParam($this->loginTitle) !== '') { ?>
                <p style="background-color: rgb(0 0 0 / 51%); padding: 10px;"><?php echo $this->loginTitle; ?></p>
            <?php } ?>
        </div>
        <?php
        }
        if (isset($this->logo)) {
        ?>
        <div class="left-logo">
            <img src="api/viewimage?src=<?php echo str_replace('storage/uploads/', '', $this->logo); ?>">
        </div>
        <?php
        }
        ?>
    </div>
    <div class="col-md-auto rightside">
        <div class="w-100">
            <div class="text-center">
                <img class="logo mt-5" src="<?php echo isset($this->mainLogo) ? 'api/viewimage?src=' . str_replace('storage/uploads/', '', $this->mainLogo) : 'assets/custom/img/veritech_logo.png'; ?>">
            </div>
            <div class="mb-3">
                <p style="color:#024490;font-weight: bold;font-size: 28px;margin-bottom: 0;"><?php echo $this->lang->line('login_title'); ?></p>
                <span class="d-block text-muted">Та өөрийн Нэвтрэх нэр, Нууц үгийг оруулж нэвтэрнэ үү</span>
                <span class="d-block text-muted"><?php echo Message::display(); ?></span>
            </div>
            
            <?php echo $this->selectMultiDbControl; ?>
            
            <div class="form-group form-group-feedback form-group-feedback-left">
                <?php echo Form::text(array('name' => 'user_name', 'id' => 'user_name', 'class' => 'form-control placeholder-no-fix', 'style' => 'background-color: #EFEFEF;border: none;', 'placeholder' => $this->lang->line('user_name'), 'required' => 'required', 'autocomplete' => 'off')); ?>
                <?php echo ($this->isEFinger OR $this->isDan) ? Form::hidden(array('name' => 'isHash', 'id' => 'isHash', 'value' => '0')) : ''; ?>
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
                <span id="user_name-error" class="help-block" style="display: none;">This field is required.</span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
                <?php echo Form::password(array('name' => 'pass_word', 'class' => 'form-control placeholder-no-fix', 'style' => 'background-color: #EFEFEF;border: none;', 'placeholder' => $this->lang->line('pass_word'), 'required' => 'required', 'autocomplete' => 'off')); ?>
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
                <span id="pass_word-error" class="help-block" style="display: none;">This field is required.</span>
            </div>
            <div class="d-flex justify-content-between pl-3 pr-3">
                <?php echo html_tag('div', array('class' => 'ldap float-left activedir', 'style' => 'color:#A0A0A0'), $this->ldapControl, $this->isLDap); ?>
                <div class="form-group d-flex align-items-center forgetpass">
                    <?php echo html_tag('a', array('href' => 'login/password_reset', 'class' => 'forget-password ml-auto', 'style' => 'color:#A0A0A0'), $this->lang->line('onlineanket_forgotpass'), Config::getFromCache('hideLoginForgotPassword') == '1' ? false : true);?>
                </div>
            </div>
            <?php
            if (isset($this->isLoginCaptcha) && $this->isLoginCaptcha) {
            ?>
            <div class="form-group row fom-row">
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
            <div class="form-actions mt-4">
                <?php 
                echo Form::submit(array('class' => 'btn btn-primary btn-block', 'style' => 'background: linear-gradient(90deg, #024490 0%, #005BC5 100%);', 'value' => $this->lang->line('login_btn') . '')); 
                echo "<div class='d-flex justify-content-center mt-3'><p style='color:#A0A0A0'>Эсвэл</p>";
                echo "</div>";
                echo "<div class='d-flex'>";
                echo Form::button(array('style' => 'background:#fff;color:#03A9F4;box-shadow: 0px 3px 12px rgba(0, 0, 0, 0.12);margin-top: 8px;margin-right: 15px;height: 39px;font-weight: bold;', 'class' => 'btn btn-primary btn-block general-login-button', 'value' => 'E TOKEN', 'onclick' => 'ShowTokenLoginWin();'), $this->isEToken);
                echo Config::getFromCache('SSO_URI') ? Html::anchor(Config::getFromCache('SSO_URI'), 'Cloud Sign', array('style' => 'color:#F44336;background:#fff;box-shadow: 0px 3px 12px rgba(0, 0, 0, 0.12);font-weight: bold;', 'class' => 'btn btn-primary btn-block general-login-button')) : '';            
                echo "</div>";
                echo "<div class='d-flex'>";
                echo Form::button(array('style' => 'background:#fff;color:#03A9F4;box-shadow: 0px 3px 12px rgba(0, 0, 0, 0.12);margin-top: 8px;margin-right: 15px;height: 39px;font-weight: bold;', 'class' => 'btn btn-primary btn-block general-login-button', 'value' => 'Хурууны хээ', 'onclick' => 'ShowFingerLogin();'), $this->isEFinger);
                echo Form::button(array('style' => 'background:#fff;color:#03A9F4;box-shadow: 0px 3px 12px rgba(0, 0, 0, 0.12);margin-top: 8px;height: 39px;font-weight: bold;font-size: 13px;', 'class' => 'btn btn-primary btn-block general-login-button', 'value' => 'Дангаар нэвтрэх', 'onclick' => 'ShowDanLogin();'), $this->isDan);
                echo "</div>";
                echo "<div class='d-flex'>";
                echo Form::button(array('style' => 'background:#fff;color:#03A9F4;box-shadow: 0px 3px 12px rgba(0, 0, 0, 0.12);margin-top: 8px;height: 39px;font-weight: bold;', 'class' => 'btn btn-primary btn-block general-login-button', 'value' => 'EHalamj нэвтрэх', 'onclick' => 'ShowEhalamjLogin();'), $this->isKhalamj);
                echo "</div>";
                echo "<div class='d-flex justify-content-center mt-3'><p style='color:#282828'>Хаягаар нэвтрэх</p>";
                echo "</div>";                    
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php echo (Config::getFromCache('isIgnorePoweredByTitle') != '1') ? '<div class="login-copyright">'. $this->loginFooterTitle .'</div>' : ''; ?>
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
if ($this->isEFinger) { 
?>
<style type="text/css"> 
    .ui-widget-header {
        border-bottom: 1px solid #e5e5e5;
        background: #2196f3;
        padding: 10px;
        color: #ffffff;
        font-weight: 700;
    }
    .btn-primary-border {
        border: 1px solid #2196f3 !important;
        background: #FFF !important;
    }
</style>
<?php 
}
?>
<style>
    .general-login-button:hover {
        border-color: #ccc;
    }
</style>