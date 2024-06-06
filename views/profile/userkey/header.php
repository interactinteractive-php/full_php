<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<title><?php echo Config::getFromCache('TITLE'); echo (isset($this->title)) ? ' - ' . $this->title : ''; ?></title>
<base href="<?php echo URL; ?>">
<link rel="shortcut icon" href="<?php echo Config::getFromCacheDefault('favicon', null, 'assets/custom/img/favicon.png'); ?>"/>
<link type="text/css" href="assets/core/css/core.css" rel="stylesheet">
<link type="text/css" href="assets/core/icon/icomoon/styles.css" rel="stylesheet">
<link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
<link type="text/css" href="<?php echo autoVersion('assets/custom/css/main.css'); ?>" rel="stylesheet">
<?php $configSkinTheme = Config::getFromCacheDefault('systemTheme', null, 'blue'); ?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSkinTheme.'.css'); ?>" rel="stylesheet"/>
<link type="text/css" href="assets/custom/css/plugins.css" rel="stylesheet">
<script type="text/javascript" src="assets/core/js/main/jquery.min.js"></script>
<script type="text/javascript" src="assets/core/js/main/jquery-migrate.min.js"></script>
<script type="text/javascript" src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/custom/js/package.bundle-min.js"></script>
<script type="text/javascript" src="assets/custom/js/plugins.min.js"></script>
<script type="text/javascript" src="assets/custom/js/core.js"></script>
</head>
<body class="loginrun">
<div class="navbar navbar-expand-md navbar-dark fixed-top bluebg">
    <div class="container-fluid">

        <?php if (!isset($this->mainLogo)) { ?>
            <div class="navbar-brand">
                <a href="javascript:;">
                    <img src="assets/custom/img/veritech_white.png" class="loginrunlogo logo-default"/> 
                </a>
            </div>
        <?php } else { ?>
            <div class="navbar-brand">
                <a href="javascript:;">
                    <img src="<?php echo $this->mainLogo; ?>" class="loginrunlogo"/> 
                </a>
            </div>
        <?php } ?>
         
        <div class="d-md-none">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-tree5"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="icon-paragraph-justify3"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbar-mobile">
            <div class="mr-md-auto"></div>
            <ul class="navbar-nav">
                <li class="dropdown dropdown-user dropdown-dark">
                    <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                        <span class="">
                            <?php echo Ue::getSessionPersonName(); ?>
                        </span>
                        <?php echo Ue::getSessionPhoto('class="rounded-circle"'); ?>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="logout" class="dropdown-item">
                            <i class="icon-switch2"></i> <?php echo $this->lang->line('logout_btn'); ?>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="page-content">
    <div class="content-wrapper">