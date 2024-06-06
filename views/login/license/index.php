<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="login-form">
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
                    <img class="logo mt-1" src="<?php echo isset($this->mainLogo) ? 'api/viewimage?src=' . str_replace('storage/uploads/', '', $this->mainLogo) : 'assets/custom/img/veritech_logo.png'; ?>">
                    <h3 class="mb-3"><?php echo $this->title; ?></h3>
                    <span class="d-block text-muted"><?php echo Message::display(); ?></span>
                </div>
                
                <form id="wsForm" autocomplete="off">
                    <?php echo $this->contentHtml; ?>
                    <div class="form-actions mt-4">
                        <?php echo Form::button(['class' => 'btn btn-primary btn-block clouduser-signup-btn', 'value' => $this->lang->line('register_btn')]); ?>
                        <a href="login" style="background:#555;" class="forget-password btn btn-primary btn-block"><?php echo $this->lang->line('login_title'); ?></a>
                    </div>
                </form>
                
            </div>
            <?php echo (Config::getFromCache('isIgnorePoweredByTitle') != '1') ? '<div class="login-copyright">'. $this->loginFooterTitle .'</div>' : ''; ?>
        </div>
    </div>
</div>