<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<meta charset="utf-8"/>
<title><?php echo (isset($this->title)) ? $this->title .' - '. Config::getFromCache('TITLE') : Config::getFromCache('TITLE'); ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<base href="<?php echo URL; ?>">
<link rel="shortcut icon" href="<?php echo Config::getFromCacheDefault('favicon', null, 'assets/custom/img/favicon.png'); ?>"/>
<link href="assets/core/css/core.css" rel="stylesheet" type="text/css">
<link href="assets/core/fonts/ptsans/ptsans.css" rel="stylesheet" type="text/css">
<link href="assets/core/css/fonts.css" rel="stylesheet" type="text/css">
<link href="assets/core/icon/fontawesome/all.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/icon/fontawesome/v4-shims.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/icon/icomoon/styles.css" rel="stylesheet" type="text/css">
<link href="<?php echo autoVersion('assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet"/>
<link href="assets/core/js/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/js/plugins/addon/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('middleware/assets/js/pos/keyboard/keyboard.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/css/main.css'); ?>" rel="stylesheet"/>
<?php $configSkinTheme = Config::getFromCacheDefault('erp_skin', null, 'blue'); ?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSkinTheme.'.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/css/plugins.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/css/left-main.css'); ?>" rel="stylesheet"/>
<?php
if (isset($this->css)) {
    foreach ($this->css as $key => $css) {
        echo '<link href="'.autoVersion('assets/'.(($key == '0' || is_numeric($key)) ? $css : $key)).'" rel="stylesheet" type="text/css" media="' . (($css == 'print') ? 'print' : 'screen') . '"/>' . "\n";
    }
}
if (isset($this->fullUrlCss)) {
    foreach ($this->fullUrlCss as $fullUrlCss) {
        echo '<link href="'.autoVersion($fullUrlCss).'" rel="stylesheet" type="text/css"/>' . "\n";
    }
}
?>
<script src="assets/core/js/main/jquery.min.js"></script>
<script src="assets/core/js/main/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script src="<?php echo autoVersion(Lang::loadjs()); ?>" type="text/javascript"></script>
<?php require 'views/header/globaljsvars.php'; ?>
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/package.bundle-min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdmetadata.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdbp.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdexpression.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mddv.js'); ?>" type="text/javascript"></script>        
<script src="<?php echo autoVersion('middleware/assets/js/pos/keyboard/jquery.keyboard.js'); ?>" type="text/javascript"></script>        
<?php
if (isset($this->jsready)) {
    foreach ($this->jsready as $jsready) {
        echo $jsready;
    }
}
if (isset($this->js)) {
    foreach ($this->js as $js) {
        echo '<script src="'.autoVersion('assets/'.$js).'" type="text/javascript"></script>' . "\n";
    }
}
if (isset($this->fullUrlJs)) {
    foreach ($this->fullUrlJs as $fullUrlJs) {
        echo '<script src="'.autoVersion($fullUrlJs).'" type="text/javascript"></script>' . "\n";
    }
}

$menuRenderBodyClass = $menuRenderWrap = $quickMenu = '';
?>
<script type="text/javascript">
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window, .moxman-window").length) {
            e.stopImmediatePropagation();
        }
    });
    $(document).ready(function () {
        Core.init();
    });
    $.extend($.fn.datagrid.defaults, {filterOnlyEnterKey: <?php echo Config::getFromCache('CONFIG_FILTER_ONLY_ENTER_KEY') ? 'true' : 'false'; ?>});
    $.extend($.fn.treegrid.defaults, {filterOnlyEnterKey: <?php echo Config::getFromCache('CONFIG_FILTER_ONLY_ENTER_KEY') ? 'true' : 'false'; ?>});
</script>        
</head>
<body class="body-left-menu-style page-header-fixed page-sidebar-fixed<?php echo $menuRenderBodyClass; ?>">
    <div class="navbar navbar-expand-lg navbar-light fixed-top left-menu vr-white-header">
        <div class="navbar-brand">
            <a href="<?php echo Config::getFromCache('CONFIG_START_LINK'); ?>">
                <?php
                $configMainLogo = Config::getFromCache('main_logo_path');
                if ($configMainLogo && file_exists($configMainLogo)) {
                ?>
                <img src="<?php echo $configMainLogo; ?>" class="logo-default vr-custom-logo"/> 
                <?php
                } else {
                ?>
                <img src="assets/custom/img/veritech_logo.png" class="logo-default vr-text-logo"/> 
                <?php
                }
                ?>
            </a>
        </div>
        <div class="d-flex justify-content-start navbar-collapse collapse left-top-menu" id="navbar-mobile">
            <div class="navbar navbar-default vr-mega-menu" id="navbar-second">
                <span class="pos-store-name" data-hdr-store-id="<?php echo Session::get(SESSION_PREFIX.'storeId'); ?>">
                    <?php echo Session::get(SESSION_PREFIX.'storeName'); ?>
                </span>   
                <?php 
                    if (Session::get(SESSION_PREFIX.'posTypeCode') == '3') {
                        if ($this->getDateCashier && $this->getDateCashier['bookdate']) {
                            echo '<span style="margin-top: 7px;font-size: 18px;"><span class="ml6 mr6">|</span><span style="color:#333">'.$this->getDateCashier['bookdate'].'</span></span>';
                        }  
                    }
                ?>
                <div class="clearfix w-100 mt2"></div>
                <span class="pos-pos-name" data-posapi-path="<?php echo Session::get(SESSION_PREFIX.'vatNumber').'\\'.Session::get(SESSION_PREFIX.'storeCode').'\\'.Session::get(SESSION_PREFIX.'cashRegisterCode'); ?>">
                    <?php 
                    $cashRegisterCode = Session::get(SESSION_PREFIX.'cashRegisterCode');
                    if ($cashRegisterCode) {
                        $isBasketShow = true;
                        echo $cashRegisterCode.' - '.Session::get(SESSION_PREFIX.'cashRegisterName'); 
                    }
                    ?>
                </span>   
            </div>
            <div class="navbar-nav page-top ml-auto">
                <ul class="nav navbar-nav">
                    <?php
                    if (isset($isBasketShow) && isset($this->basketCount)) {
                    ?>
                    <li class="nav-item">
                        <a href="javascript:;" class="navbar-nav-link pos-header-basket" title="<?php echo $this->lang->line('POS_0142'); ?>" onclick="posBasketList(this)" data-criteria="storeId=<?php echo Session::get(SESSION_PREFIX.'storeId'); ?>">
                            <div class="pos-basket-icon"><i class="fa fa-shopping-cart"></i></div>
                            <div class="pos-basket-count"><?php echo $this->basketCount; ?></div>
                            <span class="ml3 infoShortcut mt12">(Shift+F9)</span>
                        </a>
                    </li>    
                    <?php
                    } 
                    $hdrDropDownMenu = html_tag('li', '', '<a href="javascript:;" onclick="changePassword();"><i class="icon-pencil7"></i> '.$this->lang->line('change_password').'</a>', (!Config::getFromCache('CONFIG_USE_LDAP') || (Config::getFromCache('CONFIG_USE_LDAP') && Config::getFromCache('ldap_login') == '2')) ? true : false);
                    ?>
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle user-profile navbar-nav-link" data-toggle="dropdown" data-close-others="true" data-ssid="<?php echo Ue::appUserSessionId(); ?>">
                            <span class="username username-hide-on-mobile">
                                <?php 
                                $companyName = Ue::getSessionUserKeyName('CompanyName'); 
                                $userKeyCount = Session::get(SESSION_PREFIX . 'userKeyCount');
                                ?>
                                <div class="company<?php echo ($userKeyCount > 1 ? ' change-user-key' : ''); ?>" title="<?php echo $companyName; ?>"><?php echo $companyName; ?></div>
                                <?php echo Ue::getSessionPersonName(); ?>
                            </span>
                            <?php 
                            echo html_tag('i', array('class' => ''), '', ($hdrDropDownMenu != '' ? true : false)); 
                            echo Ue::getSessionPhoto('class="rounded-circle"');
                            ?>
                        </a>
                        <?php
                        echo html_tag('ul', array('class' => 'dropdown-menu dropdown-menu-default'), $hdrDropDownMenu, ($hdrDropDownMenu != '' ? true : false));
                        ?>
                    </li>
                </ul>
                <div class="left-menu">
                    <ul class="unstyled right-accessories">
                        <li>
                            <ul class="nav navbar-nav float-right">
                                <?php echo Lang::getActiveLanguage(); ?>             
                            </ul>
                        </li>
                        <li><a href="logout" title="<?php echo $this->lang->line('logout_btn'); ?>"><i class="icon-switch2"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>       
    </div>
    <div class="clearfix"></div>       
    <div class="page-content">
        <?php echo $menuRenderWrap; ?>
        <div class="content-wrapper">
            <div class="ml0 pl0 overflow-hidden">
                <div class="row pf-header-main-content">
                    