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
<link href="<?php echo autoVersion('assets/custom/css/updateui.css'); ?>" rel="stylesheet"/>
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
if (Config::getFromCache('USE_CHAT') && Config::getFromCache('isUseWebSocket')) {
    echo '<link href="'.autoVersion('chat/client/chat.css').'" rel="stylesheet" type="text/css"/>' . "\n";
}
if (defined('CUSTOM_FONT') && CUSTOM_FONT) {
    echo '<link href="'.autoVersion('assets/custom/css/custom-font.css').'" rel="stylesheet" type="text/css"/>' . "\n";
} 
if (issetParam($this->isAppmenuPage) && Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0)) {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;500;600;700&display=swap" rel="stylesheet">';    
}
$configSystemTheme = Config::getFromCacheDefault('systemTheme', null, 'blue');
?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSystemTheme.'.css'); ?>" rel="stylesheet" id="system-theme-css"/>
<link href="<?php echo autoVersion('assets/custom/css/responsive.css'); ?>" rel="stylesheet"/> 
<script src="<?php echo autoVersion('assets/core/js/main/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script src="assets/custom/addon/scripts/notification/notification.js"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/package.bundle-min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion(Lang::loadjs()); ?>" type="text/javascript"></script>
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
$configHeaderLogo = Config::isCode('header_logo_path') ? Config::getFromCache('header_logo_path') : Config::get('main_logo_path', Config::getFromCache('systemTheme'));

$configTopMenuLogoAlign = Config::getFromCache('header_logo_align');
$configHideDvFilterCondition = Config::getFromCache('hide_dv_filter_condition');
$configHidePopupLookupCode = Config::getFromCache('hide_popup_lookup_code');
$isTouchMode = Config::getFromCache('isTouchMode');
$configWidthPopupLookupCode = Config::getFromCache('width_popup_lookup_code');
$configSystemHeaderBgColor = Config::getFromCache('system_header_bgcolor');
$configBpAllControlFontSize = Config::getFromCache('bpAllControlFontSize');
$isFirstLetterUpperMenu = Config::getFromCacheDefault('ISFIRST_LETTER_UPPER_MENU', null, '1');
$isComboWithPopupChoiceOneLine = Config::getFromCache('isComboWithPopupChoiceOneLine');
$isAppmenuSearchHide = Config::getFromCache('isAppmenuSearchHide');
$isIgnoreHoverShow = Config::getFromCache('isIgnoreHoverShow');

if ($configHeaderLogo && file_exists($configHeaderLogo)) {
    if (Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0)) {
        $pageLogo = '<a href="appmenu/redirectDefaultUrl" style="z-index: 1" class="new-vlogo-link'.(isset($this->isAppmenuPage) ? ' new-vlogo-link-selector' : ' new-vlogo-link-backpage').'">
            <div class="header-logo text-white"><img src="assets/custom/img/new_veritech_logo.png"></div>
        </a>';
    } else {
        $pageLogo = '<a href="appmenu/redirectDefaultUrl" style="z-index: 1">
            <div class="header-logo text-white '.$configTopMenuLogoAlign.'"><img src="api/image_thumbnail?height=40&src='.$configHeaderLogo.'"></div>
        </a>';
    }
} else {
    if (Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0)) {
        $pageLogo = '<a href="appmenu/redirectDefaultUrl" style="z-index: 1" class="new-vlogo-link'.(isset($this->isAppmenuPage) ? ' new-vlogo-link-selector' : ' new-vlogo-link-backpage').'">
            <div class="header-logo text-white"><img src="assets/custom/img/new_veritech_logo.png"></div>
        </a>';
    } else {
        $pageLogo = '<a href="appmenu/redirectDefaultUrl" style="z-index: 1">
            <div class="header-logo text-white"><img src="assets/custom/img/veritech_white.png"></div>
        </a>';
    }
}

if ($isTouchMode) {
    $sessionTouchMode = Session::get(SESSION_PREFIX . 'touchMode');
    
    if ($sessionTouchMode) {
        $touchBodyClass = ' touch-screen-switch';
        $touchSwitchText = 'Touch mode off';
    }
}

if (Input::getCheck('mmid')) {

    $mmid = Input::get('mmid');
    
    if (!is_numeric($mmid)) {
        Message::add('e', '', 'back');
    }
    
    $menuRender = (new Mdmeta())->topMetaMenuModule($mmid);

    if (trim($menuRender['menuHtml']) != '') {

        $menuRenderBodyClass = ' vr-side-bar';
        $quickMenu = (new Mduser())->renderQuickMenu($mmid);
        $GLOBALS['openMenuId'] = $menuRender['openMenuId'];
        
        $pageLogo = '<div class="top-module-title"><div class="page-module-name">'.$this->lang->line($menuRender['moduleName']).'</div>'.$quickMenu.'</div>';
        $topMenuRenderWrap = 
        '<nav class="pf-topnavbar-menu w-100 d-flex justify-content-between">' . 
            '<div class="navbar-collapse collapse page-topbar isuppermenu'.$isFirstLetterUpperMenu.'" id="navbar-navigation">' . 
                $menuRender['menuHtml'] . 
            '</div>' . 
            '<div class="text-uppercase position-relative mr-n22">'. 
                '<a href="javascript:;" class="dropdown-toggle navbar-nav-link pull-right pf-topnavbar-menu-morebtn d-none" data-toggle="dropdown">' . 
                    '<i class="icon-menu7 ml5"></i>'. 
                '</a>'. 
                '<ul class="hidden-links dropdown-menu dropdown-menu-default dropdown-menu-right dropleft" role="menu"></ul>'.
            '</div>' . 
        '</nav>'.
        '<div class="clearfix"></div>';
    }
    
    $moduleSidebar = $menuRender['sideBarHtml'];
    
} elseif (Input::getCheck('kmid')) {
    
    $kmid = Input::get('kmid');
    
    if (!is_numeric($kmid)) {
        Message::add('e', '', 'back');
    }
    
    $menuRender = (new Mdmenu())->topKpiMenuModule($kmid);
    
    if (trim($menuRender['menuHtml']) != '') {

        $menuRenderBodyClass = ' vr-side-bar';

        $pageLogo = '<div class="top-module-title"><div class="page-module-name">'.$this->lang->line($menuRender['moduleName']).'</div></div>';
        $topMenuRenderWrap = 
        '<nav class="pf-topnavbar-menu w-100 d-flex justify-content-between">' . 
            '<div class="navbar-collapse collapse page-topbar isuppermenu'.$isFirstLetterUpperMenu.'" id="navbar-navigation">' . 
                $menuRender['menuHtml'] . 
                '</div>' . 
            '<div class="text-uppercase position-relative mr-n22">' . 
                '<a href="javascript:;" class="dropdown-toggle navbar-nav-link pull-right pf-topnavbar-menu-morebtn d-none" data-toggle="dropdown">' .
                    '<i class="icon-menu7 ml5"></i>' . 
                '</a>' .
                '<ul class="hidden-links dropdown-menu dropdown-menu-default dropdown-menu-right dropleft" role="menu"></ul>' .
            '</div>' . 
        '</nav>' . 
        '<div class="clearfix"></div>';
    }
    
    $moduleSidebar = $menuRender['sideBarHtml'];
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
    <?php } if ($configHideDvFilterCondition) { ?>
        .dv-filter-criteria-condition {
            display: none !important;
        }
        #object-value-list-1494211055536754 > .render-object-viewer .center-sidebar > .row > .col-md-3 {
            flex: 0 0 16.66667%;
            max-width: 16.66667%;
        }
        #object-value-list-1494211055536754 > .render-object-viewer .center-sidebar > .row > .col-md-9 {
            flex: 0 0 83.33333%;
            max-width: 83.33333%;
        }
        #object-value-list-1494211055536754 .left-sidebar-content #accordion4-1494211055536754 .panel-default {
            border-bottom: 0 !important;
            padding-bottom: 5px;
        }
        #object-value-list-1494211055536754 .left-sidebar-content #accordion4-1494211055536754 .panel-default .dateElement {
            max-width: 100% !important;
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
    <?php } if ($isAppmenuSearchHide) { ?>
    .appmenusearch {
        display: none;
    }
    <?php } if ($isIgnoreHoverShow) { ?>
    .dropdown-submenu:hover>.dropdown-menu, 
    .dropdown-menu>.dropdown-submenu:hover>.dropdown-item:not(.dropdown-toggle)~.dropdown-menu {
        display: none;
    }
    .dropdown-menu.show {
        display: block !important;
    }
    <?php } ?>
</style>
<script type="text/javascript">
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window, .moxman-window").length) {
        e.stopImmediatePropagation();
    }
});
$(document).ready(function () {
    Core.init();
    
    <?php if ((Config::getFromCache('passwordSuggest') == '1' && issetParam($this->getStartupMeta['IS_CHANGE_PASSWORD']) && Session::get(SESSION_PREFIX.'startupMeta') == '1') || (Config::getFromCacheDefault('defaultPasswordCheckGet', null, '') !== '' && Ue::sessionPasswordCheck() === Ue::sessionPassword() )) { ?>
        changePassword('no_nowpassword');
    <?php } ?>
});
$.extend($.fn.datagrid.defaults, {filterOnlyEnterKey: <?php echo Config::getFromCache('CONFIG_FILTER_ONLY_ENTER_KEY') ? 'true' : 'false'; ?>});
$.extend($.fn.treegrid.defaults, {filterOnlyEnterKey: <?php echo Config::getFromCache('CONFIG_FILTER_ONLY_ENTER_KEY') ? 'true' : 'false'; ?>});
</script>
</head>
<body class="body-top-menu-style <?php echo $touchBodyClass . (!$moduleSidebar ? ' without-left-iconbar' : ''); ?>">
    <div class="navbar navbar-expand-md navbar-dark fixed-top primary-top align-self-center d-flex justify-content-around system-header appmenu-newdesign-header-<?php echo Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0); ?>">
        <div class="container-fluid ml-0 pl-0 modname <?php echo Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0) ? 'd-flex justify-content-end' : '' ; ?>">
        <?php echo $pageLogo; ?>
        <!-- <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button> -->
        
        <div class="d-md-none">
            <button class="navbar-toggler text-secondary" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-tree5"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle text-secondary" type="button">
                <i class="icon-paragraph-justify3"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle-left text-secondary mr-2" type="button">
                <i class="icon-indent-decrease"></i>
            </button>
        </div>
        <div class="appmenusearch">
            <i class="icon-search4"></i><input id="appmenusearchinput" type="text" placeholder="<?php echo $this->lang->line('appmenu_search'); ?>..." data-ref="input-search">
        </div>
        <div class="collapse navbar-collapse" id="navbar-mobile">
            <div class="mr-md-auto"></div>
            <ul class="navbar-nav topnav mobile-header-contents">

                <?php 
//                echo Mduser::systemModeActions();
                echo Info::getDbName();
                echo (new Mdmeta)->topMetaLimitMenuRenderByService(true, 'close_all'); 
                
                echo Info::fiscalPeriodNewV2();
                echo Info::chooseEaScenario();
                echo Lang::getActiveLanguage(); 
                ?>
            </ul>
            <div class="mobile-header-menu">
                <button type="button" class="btn-icon btn-icon-only btn btn-sm mobile-toggle-header-nav">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </button>
            </div>
            <ul class="navbar-nav topnav">
                <?php
                echo (new Mdalert())->showAlertListForHdr();    
                echo (new Mdnotification())->showNotificationListForHdrNew();

                $hdrDropDownMenu = '';

                if (Config::getFromCache('SCL_GROUP_USER')) {
                    echo '<script src="'. autoVersion('assets/custom/gov/script.js') .'" type="text/javascript"></script>';
                    $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="privateGroup(\''. Config::getFromCache('SCL_GROUP_USER') .'\')"><i class="icon-cog"></i> '.$this->lang->line('group_control').'</a>', Config::getFromCache('sclGroupControl'));
                    $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="appMultiTab({weburl: \'government/documentation/'. Config::getFromCacheDefault('sclDocumentation', null, '1572596811089741') .'\', metaDataId: \'governmentdocumentation\', title: \'Гарын авлага\', type: \'selfurl\', tabReload: true}, this);"><i class="icon-profile"></i> '. Lang::line('Гарын авлага').'</a>', true);
                    $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="appMultiTab({weburl: \'government/documentation/'. Config::getFromCacheDefault('HOLBOGDOH_HUULI_JURAM', null, '16608786459209') .'16608786459209\', metaDataId: \'16608786459209\', title: \''. Lang::line('HOLBOGDOH_HUULI_JURAM') .'\', type: \'selfurl\', tabReload: true}, this);"><i class="icon-drawer3"></i> '.  Lang::line('HOLBOGDOH_HUULI_JURAM') .'</a>', Config::getFromCache('HOLBOGDOH_HUULI_JURAM'));
                    $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="appMultiTab({weburl: \'government/documentation/'. Config::getFromCacheDefault('HOLBOO_BARIH', null, '16608786461379') .'\', metaDataId: \'16608786461379\', title: \''. Lang::line('HOLBOO_BARIH') .'\', type: \'selfurl\', tabReload: true}, this);"><i class="icon-phone"></i> '.  Lang::line('HOLBOO_BARIH') .'</a>', Config::getFromCache('HOLBOO_BARIH'));
                    $hdrDropDownMenu .= '<hr class="mt-1 mb-1">';
                }

                $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item touch-screen-switch-btn"><i class="icon-touch font-weight-bold"></i> '.$touchSwitchText.'</a>', ($isTouchMode ? true : false));
                $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="changePassword();"><i class="icon-pencil7"></i> '.$this->lang->line('change_password').'</a>', (Session::get(SESSION_PREFIX.'isldap') == 1 && !Config::getFromCache('isLdapModifyPassword')) ? false : (Config::getFromCache('is_hide_system_change_password') ? false : true));
                $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="changeUsername();"><i class="icon-user"></i> '.$this->lang->line('change_username').'</a>', Config::getFromCache('isChangeUserName') ? true : false);
                $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="dataAccessPassword();"><i class="icon-key"></i> Security password</a>', Config::getFromCache('isDataSecurityPassword') ? true : false);
                $hdrDropDownMenu .= html_tag('li', '', '<a href="javascript:;" class="dropdown-item" onclick="ShowRegisterWin();"><i class="icon-key"></i> Токен бүртгүүлэх</a>', Config::getFromCache('CONFIG_USE_ETOKEN'));
                if (Config::getFromCache('IS_TESTCASE_MODE')) {
                    $hdrDropDownMenu .= html_tag('li', '','<div class="form-check form-check-switchery form-check-inline form-check-right pl17 pt2">
                        <label class="form-check-label">
                            <i class="icon-check mr8 mt2 font-size-18"></i> Testcase mode:
                            <input type="checkbox" class="form-check-input-switchery-warning notuniform" id="isHdrTestCaseMode"'.(Session::get(SESSION_PREFIX . 'testCaseMode') ? ' checked="checked"' : '').'>
                        </label>
                    </div>');
                }                   
                $hdrDropDownMenu .= html_tag('li', '', '<a href="logout" class="dropdown-item"><i class="icon-switch2"></i> '.$this->lang->line('logout_btn').'</a>');                            
                ?>
                <li class="dropdown dropdown-user dropdown-dark nav-item">
                    <a href="javascript:;" class="dropdown-toggle user-profile navbar-nav-link pt-0 pb-0" data-toggle="dropdown" data-close-others="true" data-ssid="<?php echo Ue::appUserSessionId(); ?>">
                        <span class="username username-hide-on-mobile text-a-right">
                            <?php 
                            $companyName = Ue::getSessionUserKeyName('CompanyName'); 
                            $userKeyCount = Session::get(SESSION_PREFIX . 'userKeyCount');
                            ?>
                            <div class="lhnormal company">
                                <div class="font-size-11 companyname<?php echo ($userKeyCount > 1 ? ' change-user-key' : ''); ?>" title="<?php echo $companyName; ?>"><?php echo $companyName; ?></div> 
                                <div class="font-size-11">
                                    <?php echo Ue::getSessionPersonWithLastName(); ?>
                                </div>
                            </div>
                        </span>
                        <?php echo Ue::getSessionPhoto('class="img-circle"'); ?>
                    </a>
                    <?php
                    echo html_tag('ul', array('class' => 'dropdown-menu dropdown-menu-default dropdown-menu-right'), $hdrDropDownMenu, ($hdrDropDownMenu != '' ? true : false));
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-fluid top-menu-render">   
        <?php echo $topMenuRenderWrap; ?>
    </div>
    <div class="card light m-tab header-tab w-100">
        <div class="topnavbarmenumode"></div>
    </div>             
</div>
<div class="page-content<?php echo ($moduleSidebar ? ' pf-iconsidebar' : ''); ?>">
    <?php echo $moduleSidebar; ?>
    <div class="content-wrapper">
        <div class="content">
            <div class="pf-header-main-content w-100 mt70">