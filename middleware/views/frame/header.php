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
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<base href="<?php echo URL; ?>">
<link rel="shortcut icon" href="<?php echo Config::getFromCacheDefault('favicon', null, 'assets/custom/img/favicon.png'); ?>"/>
<link href="<?php echo autoVersion('assets/core/icon/icomoon/styles.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/core/css/core.css'); ?>" rel="stylesheet" type="text/css">
<link href="assets/core/icon/fontawesome/all.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/icon/fontawesome/v4-shims.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/core/js/plugins/addon/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/core/js/plugins/select2/select2.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/css/top-menu.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/css/main.css'); ?>" rel="stylesheet"/>
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
$configSystemTheme = Config::getFromCacheDefault('systemTheme', null, 'blue');
?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSystemTheme.'.css'); ?>" rel="stylesheet" id="system-theme-css"/>
<link href="<?php echo autoVersion('assets/custom/css/responsive.css'); ?>" rel="stylesheet"/> 
<script src="<?php echo autoVersion('assets/core/js/main/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/package.bundle-min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion(Lang::loadjs()); ?>" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->
<?php 
require 'views/header/globaljsvars.php'; 
?>
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
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

$menuRenderBodyClass = $topMenuRenderWrap = $pageHomeMenu = $quickMenu = $moduleSidebar = $touchBodyClass = '';
$touchSwitchText = 'Touch mode on';

$configHeaderLogo = Config::isCode('header_logo_path') ? Config::getFromCache('header_logo_path') : Config::getFromCache('main_logo_path');
$configTopMenuLogoAlign = Config::getFromCache('header_logo_align');

$configHideDvFilterCondition = Config::getFromCache('hide_dv_filter_condition');
$configHidePopupLookupCode = Config::getFromCache('hide_popup_lookup_code');
$isTouchMode = Config::getFromCache('isTouchMode');
$configWidthPopupLookupCode = Config::getFromCache('width_popup_lookup_code');
$configSystemHeaderBgColor = Config::getFromCache('system_header_bgcolor');
$configBpAllControlFontSize = Config::getFromCache('bpAllControlFontSize');
$isFirstLetterUpperMenu = Config::getFromCacheDefault('ISFIRST_LETTER_UPPER_MENU', null, '1');
$isComboWithPopupChoiceOneLine = Config::getFromCache('isComboWithPopupChoiceOneLine');

if ($configHeaderLogo && file_exists($configHeaderLogo)) {
    $pageLogo = '<a href="./">
        <div class="header-logo text-white '.$configTopMenuLogoAlign.'"><img src="api/image_thumbnail?height=40&src='.$configHeaderLogo.'"></div>
    </a>';
} else {
    $pageLogo = '<a href="./">
        <div class="header-logo text-white"><img src="assets/custom/img/veritech_white.png"></div>
    </a>';
}

if ($isTouchMode) {
    $sessionTouchMode = Session::get(SESSION_PREFIX . 'touchMode');
    
    if ($sessionTouchMode) {
        $touchBodyClass = ' touch-screen-switch';
        $touchSwitchText = 'Touch mode off';
    }
}
?>
<style type="text/css">
    <?php if ($configSystemHeaderBgColor) { ?>
    .system-header {
        background <?php echo $configSystemHeaderBgColor; ?>;
    }
    .without-left-iconbar .system-header {
        background: var(--root-color1);
    }
    <?php } if ($configHidePopupLookupCode) { ?>
        .lookup-code-autocomplete, .dtl-col-popup-code-f, .bp-head-lookup-sort-code {
            display: none !important;
        }
    <?php } if ($configWidthPopupLookupCode) { ?>
        .double-between-input input.form-control.meta-autocomplete {
            width: <?php echo $configWidthPopupLookupCode; ?>px !important;
            flex: 0 0 <?php echo $configWidthPopupLookupCode; ?>px;
            max-width: <?php echo $configWidthPopupLookupCode; ?>px;
        }
    <?php } if ($configBpAllControlFontSize) { ?>
        div[data-bp-uniq-id] input[type="text"].form-control-sm, 
        div[data-bp-uniq-id] textarea.form-control-sm, 
        div[data-bp-uniq-id] .select2-container.form-control-sm {
            font-size: <?php echo $configBpAllControlFontSize; ?> !important;
        }
    <?php } if ($isComboWithPopupChoiceOneLine) { ?> 
    .bp-field-with-popup-combo .select2-choices {
        display: flex;
        flex-direction: row;
        width: 100%;
        overflow: hidden;
        overflow-x: auto;
        cursor: auto;
        padding-right: 15px;
    }
    .bp-field-with-popup-combo .select2-search-field,
    .bp-field-with-popup-combo .select2-choices li input {
        width: 100% !important;
        min-width: 20px;
    }
    .bp-field-with-popup-combo .select2-search-choice {
        white-space: nowrap !important;
    }
    <?php 
    } 
    if (isset($this->isSystemHeaderHide) && $this->isSystemHeaderHide) {
    ?>
    .system-header-hide .system-header {
        display: none !important;
    }
    .system-header-hide .pf-header-main-content {
        margin-top: 0 !important;
    }
    .system-header-hide .page-content>.content-wrapper>.content {
        padding-bottom: 10px !important;
    }
    <?php
    }
    ?>
</style>
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
<body class="body-top-menu-style <?php echo $touchBodyClass . (!$moduleSidebar ? ' without-left-iconbar' : ''); ?> system-header-hide">
<div class="navbar navbar-expand-md navbar-dark fixed-top primary-top align-self-center d-flex justify-content-around system-header">
    <div class="container-fluid ml-0 pl-0 modname">
        <?php echo $pageLogo; ?>
        <div class="appmenusearch">
            test case
            <i class="icon-search4"></i><input id="appmenusearchinput" type="text" placeholder="<?php echo $this->lang->line('appmenu_search'); ?>..." data-ref="input-search">
        </div>
        <div class="collapse navbar-collapse" id="navbar-mobile">
            <div class="mr-md-auto"></div>
            <ul class="navbar-nav topnav mobile-header-contents">
                <?php 
                echo Info::getDbName();
                ?>
            </ul>
            <div class="mobile-header-menu">
                <button type="button" class="btn-icon btn-icon-only btn btn-sm mobile-toggle-header-nav">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid top-menu-render">   
        <div class="d-md-none">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-tree5"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="icon-paragraph-justify3"></i>
            </button>
        </div>
    </div>
    <div class="card light m-tab header-tab w-100">
        <div class="topnavbarmenumode"></div>
    </div>             
</div>
<div class="page-content">
    <div class="content-wrapper">
        <div class="content">
            <div class="pf-header-main-content w-100 mt70">