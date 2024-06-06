<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<meta charset="utf-8"/>
<title><?php echo Config::getFromCache('TITLE'); echo (isset($this->title)) ? ' - ' . $this->title : ''; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<base href="<?php echo URL; ?>">
<link href="<?php echo autoVersion('assets/core/css/core.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/core/icon/icomoon/styles.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('middleware/assets/theme/metro/main.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('assets/custom/css/main.css'); ?>" rel="stylesheet"/>
<?php $configSkinTheme = Config::getFromCacheDefault('erp_skin', null, 'blue'); ?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSkinTheme.'.css'); ?>" rel="stylesheet"/>
<link href="assets/core/icon/fontawesome/all.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/icon/fontawesome/v4-shims.min.css" rel="stylesheet" type="text/css"/>
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
<link rel="shortcut icon" href="assets/core/global/img/favicon.png"/>
<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/core/js/main/jquery.min.js"></script>
<script src="assets/core/js/main/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script src="<?php echo autoVersion(Lang::loadjs()); ?>" type="text/javascript"></script>
<script type="text/javascript">
var URL_FN = URL,
    URL = '<?php echo URL; ?>',
    URL_APP = '<?php echo URL; ?>',
    ENVIRONMENT = '<?php echo ENVIRONMENT; ?>',
    sysLangCode = '<?php echo Lang::getCode(); ?>',
    decimal_fixed_num = 6, 
    round_scale = 2, 
    isAppMultiTab = <?php echo Config::getFromCacheDefault('CONFIG_MULTI_TAB', null, 0); ?>,
    isAlwaysNewTab = <?php echo Config::getFromCacheDefault('CONFIG_ALWAYS_NEWTAB', null, 0); ?>,
    isTestServer = <?php echo Config::getFromCache('IS_TEST_SERVER') ? 'true' : 'false'; ?>, 
    isCloseOnEscape = <?php echo Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE') ? 'true' : 'false'; ?>;
    <?php if(Session::isCheck(SESSION_PREFIX . 'isUrlAuthenticate')) echo 'var isUrlAuth = 1;'; ?>
</script>
<script src="<?php echo autoVersion('assets/core/js/main/common.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/addon/admin/layout4/scripts/layout.js'); ?>" type="text/javascript"></script>
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
?>
<script type="text/javascript">
    $(document).ready(function () {
        Core.init();
        Layout.init();
    });
</script>
</head>
<body class="page-header-fixed page-sidebar-fixed">
    <div class="page-header navbar navbar-fixed-top vr-white-header">
        <div class="page-header-inner">
            <div class="page-header-inner-color">
                <div class="page-logo">
                    <a href="<?php echo Config::getFromCache('CONFIG_START_LINK'); ?>">
                        <img src="assets/core/global/img/vr-logo-text-image.png" class="logo-default vr-text-logo"/> 
                    </a>
                </div>
                <div class="navbar navbar-default vr-mega-menu" id="navbar-second">
                    <ul class="nav navbar-nav no-border visible-xs-block">
                        <li>
                            <a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="fa fa-reorder"></i></a>
                        </li>
                    </ul>
                    <div class="navbar-collapse collapse" id="navbar-second-toggle">
                    </div>
                </div>

                <div class="float-left">
                    <div class="social-header-title">
                        МЭДЭЭЛЭЛ СОЛИЛЦООНЫ ПОРТАЛ
                    </div>

                    <div class="input-icon right float-left social-header-search">
                        <i class="icon-search4"></i>
                        <input type="text" class="form-control input-circle" placeholder="Хайлт...">
                    </div>
                </div>

                <div class="page-top">
                    <div class="top-menu">
                        <ul class="unstyled right-accessories">
                            <li>
                                <ul class="nav navbar-nav float-right">
                                    <?php //echo Lang::getActiveLanguage(); ?>
                                </ul>
                            </li>
                            <li><a href="logout" title="<?php echo $this->lang->line('logout_btn'); ?>"><i class="fa fa-power-off"></i></a></li>
                        </ul>
                    </div>
                    <div class="top-menu bar">
                        <?php
                        $hdrDropDownMenu = html_tag('li', '', '<a href="javascript:;" onclick="changePassword();"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line('change_password').'</a>', (!Config::getFromCache('CONFIG_USE_LDAP') || (Config::getFromCache('CONFIG_USE_LDAP') && Config::get('ldap_login') == '2')) ? true : false);
                        ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <div class="d-flex flex-row align-items-center">
                                    <span class="username username-hide-on-mobile">
                                        <?php $companyName = Ue::getSessionUserKeyName('CompanyName'); ?>
                                        <div class="company" title="<?php echo $companyName; ?>"><?php echo $companyName; ?></div>
                                        <?php echo Ue::getSessionPersonName(); ?>
                                    </span>
                                    <div>
                                        <?php 
                                            echo html_tag('i', array('class' => 'fa fa-angle-down'), '', ($hdrDropDownMenu != '' ? true : false)); 
                                            echo Ue::getSessionPhoto('class="rounded-circle user_pro"');
                                        ?>
                                    </div>
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php echo html_tag('a', array('class' => 'dropdown-item'), $hdrDropDownMenu, ($hdrDropDownMenu != '' ? true : false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div> 
            </div>
        </div>
    </div>
    <div class="clearfix"></div>       
    <div class="page-container no-padding m-0">
        <div class="page-content-wrapper">
            <div class="page-content ml0 padding-left-0">
                <div class="col">                   
                    <?php
                    if (Message::hasMessages()) {
                        echo '<div class="container"><div class="row"><div class="col-md-12">'.Message::display().'</div></div></div>';
                    }
                    ?>