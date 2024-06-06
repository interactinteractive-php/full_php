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
<link href="<?php echo autoVersion('assets/custom/css/top-menu.css'); ?>" rel="stylesheet"/>
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

<script src="assets/core/js/main/jquery.min.js"></script>
<script src="assets/core/js/main/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>

<script src="<?php echo autoVersion(Lang::loadjs()); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/bootstrap-datepicker.mn.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/package.bundle-min.js'); ?>" type="text/javascript"></script>

<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->          
<script type="text/javascript">
var URL_FN = URL,
    URL = '<?php echo URL; ?>', 
    URL_APP = '<?php echo URL; ?>', 
    ENVIRONMENT = '<?php echo ENVIRONMENT; ?>',
    decimal_fixed_num = 6, 
    round_scale = 2, 
    vr_top_menu = true, 
    isAppMultiTab = <?php echo Config::getFromCacheDefault('CONFIG_MULTI_TAB', null, 0); ?>,
    isAlwaysNewTab = <?php echo Config::getFromCacheDefault('CONFIG_ALWAYS_NEWTAB', null, 0); ?>,
    isTestServer = <?php echo Config::getFromCache('IS_TEST_SERVER') ? 'true' : 'false'; ?>,                
    isCloseOnEscape = <?php echo Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE') ? 'true' : 'false'; ?>,
    pnotifyPosition = '<?php echo Config::getFromCache('CONFIG_PNOTIFY_POSITION'); ?>';
    isDeleteActionBeforeReload = <?php echo Config::getFromCache('CONFIG_IS_DELETEACTION_BEFORERELOAD') ? 'true' : 'false'; ?>;
    gmapApiKey = '<?php echo Config::getFromCacheDefault('googleMapApiKey', null, 'AIzaSyC8RYmijsVKDS8eju_24-lQ1YjTXnpuwF4'); ?>';
    <?php if($accountMask = Config::getFromCache('CONFIG_ACCOUNT_CODE_MASK')) echo 'var accountCodeMask = \''.$accountMask.'\';'; ?>
</script>
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/pki/sign.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdmetadata.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdbp.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdexpression.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mddv.js'); ?>" type="text/javascript"></script>        
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

$menuRenderBodyClass = $topMenuRenderWrap = $pageHomeMenu = $quickMenu = '';

$configMainLogo = Config::getFromCache('main_logo_path');
$configHeaderBg = null;//Config::getFromCache('header_bg_path');

if ($configMainLogo && file_exists($configMainLogo)) {
    $pageLogo = '<a href="'.Config::getFromCache('CONFIG_START_LINK').'">
        <img src="'.$configMainLogo.'" height="70" class="logo-default topmainlogo vr-text-logo px-5"/>
        <div class="header-logo text-white animated bounceInLeft"><img src="'.$configMainLogo.'"></div>
    </a>';
} else {
    $pageLogo = '<a href="'.Config::getFromCache('CONFIG_START_LINK').'">
        <img src="assets/custom/img/logo.png" height="70" class="logo-default topmainlogo vr-text-logo px-5"/>
        <div class="header-logo text-white animated bounceInLeft"><img src="assets/custom/img/veritech_white.png"></div>
    </a>';
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

        var $multiTab = $('.card-multi-tab > .card-header');

        if ($multiTab.length) {
            var $multiTabClone = $multiTab.clone();
            $('.m-tab').html($multiTabClone);
            $multiTab.remove();
        }

    });
    $.extend($.fn.datagrid.defaults, {filterOnlyEnterKey: <?php echo Config::getFromCache('CONFIG_FILTER_ONLY_ENTER_KEY') ? 'true' : 'false'; ?>});
    $.extend($.fn.treegrid.defaults, {filterOnlyEnterKey: <?php echo Config::getFromCache('CONFIG_FILTER_ONLY_ENTER_KEY') ? 'true' : 'false'; ?>});

    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass("show");
        });
        return false;
    });
</script>
</head>
<body class="body-top-menu-style">
<div class="page-content mt0">
    <div class="content-wrapper">
        <div class="content">
            <div class="row pf-header-main-content"> 