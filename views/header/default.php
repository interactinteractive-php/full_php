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
<link href="assets/core/icon/fontawesome/all.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/icon/fontawesome/v4-shims.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/icon/icomoon/styles.css" rel="stylesheet" type="text/css">
<link href="<?php echo autoVersion('assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet"/>
<link href="assets/core/js/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/js/plugins/addon/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/css/main.css'); ?>" rel="stylesheet"/>
<?php $configSkinTheme = Config::getFromCacheDefault('erp_skin', null, 'blue'); ?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSkinTheme.'.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/css/plugins.css'); ?>" rel="stylesheet"/>
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
<link href="<?php echo autoVersion('assets/custom/css/left-main.css'); ?>" rel="stylesheet"/>
<script src="assets/core/js/main/jquery.min.js"></script>
<script src="assets/core/js/main/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script src="<?php echo autoVersion(Lang::loadjs()); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/package.bundle-min.js'); ?>" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->
<?php 
require 'views/header/globaljsvars.php'; 
if (Config::getFromCache('isUseWebSocket')) {
?>

<script src="<?php echo autoVersion('assets/core/js/plugins/socket/ws.js'); ?>" type="text/javascript"></script>
<?php
}
?>
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/pages/scripts/layout_fixed_sidebar_custom.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/pki/sign.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdmetadata.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdbp.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdexpression.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mddv.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/addon/meta.js'); ?>" type="text/javascript"></script>
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

if (Input::getCheck('mmid')) {

    $mmid = Input::get('mmid');

    $menuRender = (new Mdmeta())->leftMetaMenuModule(false, $mmid, 'close_all');

    if (trim($menuRender) != '') {
        $menuRenderBodyClass = ' vr-side-bar';

        $menuRenderWrap = '<div class="sidebar  sidebar-dark sidebar-main sidebar-fixed sidebar-expand-md">
            <div class="sidebar-content page-sidebar  sidebar-left-menu overflow-hidden">
                <div class="card card-sidebar-mobile">
                ' . $menuRender . '
                </div>
            </div>
        </div>';

        $quickMenu = (new Mduser())->renderQuickMenu($mmid);
    }
}
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
    <div class="navbar navbar-expand-lg navbar-light fixed-top left-menu vr-white-header system-header">
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
                <img src="assets/core/global/img/vr-logo-text-image.png" class="logo-default vr-text-logo"/> 
                <?php
                } 
                ?>
            </a>
        </div>
        <div class="d-flex justify-content-start navbar-collapse collapse left-top-menu" id="navbar-mobile">
            <div class="navbar navbar-default vr-mega-menu" id="navbar-second">
                <ul class="d-flex flex-1 d-md-none">
                    <li>
                        <a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="fa fa-reorder"></i></a>
                    </li>
                </ul>
                <div class="navbar-collapse collapse" id="navbar-second-toggle">
                    <ul class="nav navbar-nav nav-tabs nav-portal d-flex justify-content-between">
                        <li class="nav-item">
                            <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                                <i class="icon-paragraph-justify3"></i>
                            </a>
                        </li>
                        <?php echo (new Mdmeta())->topMetaMenuRenderByService(true, 'open_all'); ?> 
                    </ul>
                </div>
            </div>
            <?php echo $quickMenu; ?>
            <div class="navbar-nav page-top ml-auto">
                <ul class="nav navbar-nav d-flex align-items-center">
                    <?php
                    echo Info::getDbName();
                    echo Info::fiscalPeriodNewV2();

                    echo (new Mdalert())->showAlertListForHdr();
                    echo (new Mdnotification())->showNotificationListForHdrNew();

                    $hdrDropDownMenu = html_tag('li', '', '<a href="webmail"><i class="fa fa-envelope-o"></i> Webmail</a>', Config::getFromCache('CONFIG_USE_WEBMAIL'));
                    $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" onclick="changePassword();"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line('change_password').'</a>', (!Config::getFromCache('CONFIG_USE_LDAP') || (Config::getFromCache('CONFIG_USE_LDAP') && Config::getFromCache('ldap_login') == '2')) ? true : false);
                    $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" onclick="ShowRegisterWin();"><i class="fa fa-key"></i> Токен бүртгүүлэх</a>', Config::getFromCache('CONFIG_USE_ETOKEN'));
                    ?>
                    <input id="appmenusearchinput" type="hidden" placeholder="Хайлт..." data-ref="input-search">

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
                        <li><a href="logout" title="<?php echo $this->lang->line('logout_btn'); ?>"><i class="fa fa-power-off"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>       
    </div>
    <div class="page-content">
        <?php echo $menuRenderWrap; ?>
        <div class="content-wrapper">
            <div class="content">
                <div class="pf-header-main-content">