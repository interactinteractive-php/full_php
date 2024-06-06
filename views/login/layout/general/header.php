<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo Config::getFromCache('TITLE') . ' - ' . $this->title; ?></title>
<base href="<?php echo URL; ?>">
<link href="<?php echo autoVersion('assets/core/css/core.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/core/icon/icomoon/styles.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo autoVersion('assets/custom/css/login.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('assets/custom/css/plugins.css'); ?>" rel="stylesheet"/> 
<?php $configSkinTheme = Config::getFromCacheDefault('systemTheme', null, 'blue'); ?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSkinTheme.'.css'); ?>" rel="stylesheet"/>
<link rel="shortcut icon" href="<?php echo Config::getFromCacheDefault('favicon', null, 'assets/custom/img/favicon.png'); ?>"/>
<script src="<?php echo autoVersion('assets/core/js/main/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
</head>
<body>
<div class="page-content">
    <div class="content-wrapper">
        <div class="content d-flex justify-content-center align-items-center p-0">