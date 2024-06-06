<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<?php echo Form::create(array('id' => 'login-form', 'class' => 'login-form', 'method' => 'post', 'action' => 'login/send_password', 'autocomplete' => 'off')); ?>
<div class="row loginbody">
    <div class="col-md-auto rightside">
        <div class="w-100">
            <div class="text-center mb-2">
                <img class="logo mt-5" src="<?php echo isset($this->mainLogo) ? 'api/viewimage?src=' . str_replace('storage/uploads/', '', $this->mainLogo) : 'assets/custom/img/veritech_logo.png'; ?>">
                <span class="d-block text-muted"><?php echo Message::display(); ?></span>
            </div>
            <?php 
            echo $this->selectMultiDbControl; 
            
            if ($this->passwordByPhone) {
            ?>
            <div class="form-group form-group-feedback form-group-feedback-left">
                <div class="btn-group btn-group-toggle w-100 email-phone" data-toggle="buttons">
                    <label class="btn btn-light cursor-pointer active">
                        <input type="radio" name="p_type" id="e-mail" value="email" checked="checked">
                        <label class="mb-0" for="e-mail">И-мейл хаягаар</label>
                    </label>
                    <label class="btn btn-light">
                        <input type="radio" name="p_type" id="phone-number" value="phoneNumber">
                        <label class="mb-0" for="phone-number">Утасны дугаараар</label>
                    </label>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="form-group form-group-feedback form-group-feedback-left">
                <div class="input-icon">
                    <?php echo Form::text(array('name' => 'p_email', 'id' => 'p_email', 'class' => 'form-control placeholder-no-fix email', 'placeholder' => $this->lang->line('email_address'), 'required' => 'required', 'autocomplete' => 'off', 'tabindex' => 1)); ?>
                </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
                <div class="input-group">
                    <span class="input-group-btn">
                        <img src="api/captcha?fDownload=1" id="captcha"><br />
                        <a href="javascript:;" onclick="new_captcha();" class="captcha-refresh"><?php echo $this->lang->line('captcha_new_code'); ?></a>
                    </span>
                    <?php echo Form::text(array('name' => 'security_code', 'id' => 'security_code', 'class' => 'form-control pl-2', 'placeholder' => $this->lang->line('captcha_typing_code'), 'required' => 'required', 'autocomplete' => 'off', 'tabindex' => 2)); ?>
                </div>
            </div>
            <div class="form-actions mt-4">
                <?php echo Form::submit(array('class' => 'btn btn-primary btn-block', 'value' => $this->lang->line('send_btn') . ' <i class="m-icon-swapright m-icon-white"></i>', 'tabindex' => 3)); ?>
                <a href="login" style="background:#555;" class="forget-password btn btn-primary btn-block"><?php echo $this->lang->line('login_title'); ?></a>
            </div>
        </div>
    </div>
    <div class="col-7 leftside " id="loginSlider" data-ride="carousel">
        <div class="www"></div>
        <div class="carousel slide">
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
            <?php
            }
            if (isset($this->logo)) {
            ?>
            <!-- <div class="left-logo">
                <img src="api/viewimage?src=<?php echo str_replace('storage/uploads/', '', $this->logo); ?>">
            </div> -->
            <?php
            }
            ?>
        </div>
    </div>
    <div class="col leftside-new barcode" data-ride="carousel">
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
if ($this->isEFinger) { ?>
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
<?php }
?>