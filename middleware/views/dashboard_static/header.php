<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8"/>
<title>Veritech ERP - <?php echo $this->title; ?></title>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">

<base href="<?php echo URL; ?>">

<!-- <link href="<?php echo autoVersion('assets/custom/css/fonts/opensans/opensans.css'); ?>" rel="stylesheet" type="text/css"/> -->
<!-- <link href="<?php echo autoVersion('assets/custom/css/fonts/ptsans/ptsans.css'); ?>" rel="stylesheet" type="text/css"/> -->
<!-- <link href="<?php echo autoVersion('assets/custom/css/fonts/opensans/opensans.css'); ?>" rel="stylesheet" type="text/css"/> -->
<!-- <link href="<?php echo autoVersion('assets/custom/addon/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css"/> -->
<!-- <link href="<?php echo autoVersion('assets/custom/addon/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/> -->
<link href="<?php echo autoVersion('assets/custom/css/plugins.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo autoVersion('assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/core/css/core.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('middleware/assets/theme/metro/main.css'); ?>" rel="stylesheet" type="text/css">

<link href="<?php echo autoVersion('assets/custom/css/main.css'); ?>" rel="stylesheet" type="text/css"/>
<?php $configSkinTheme = Config::getFromCacheDefault('erp_skin', null, 'blue'); ?>
<link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSkinTheme.'.css'); ?>" rel="stylesheet"/>
<link href="<?php echo autoVersion('assets/custom/addon/plugins/select2/select2.css'); ?>" rel="stylesheet" type="text/css"/>

<script src="<?php echo autoVersion('assets/core/js/main/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/addon/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/addon/plugins/html2canvas.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo autoVersion('middleware/assets/js/mdmetadata.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdexpression.js'); ?>" type="text/javascript"></script>

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
        isCloseOnEscape = <?php echo Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE') ? 'true' : 'false'; ?>;
        <?php if($accountMask = Config::getFromCache('CONFIG_ACCOUNT_CODE_MASK')) echo 'var accountCodeMask = \''.$accountMask.'\';'; ?>
        <?php if(Session::isCheck(SESSION_PREFIX . 'isUrlAuthenticate')) echo 'var isUrlAuth = 1;'; ?>
</script>

<script src="assets/custom/addon/plugins/amcharts/amcharts/amChartMinify.js" type="text/javascript"></script>
<script src="assets/custom/addon/plugins/amcharts/amcharts/gauge.js" type="text/javascript"></script>

<script src="assets/custom/addon/plugins/highcharts/js/highchartsNew.js" type="text/javascript"></script>
<!-- <script src="assets/custom/addon/plugins/highcharts/js/highcharts-more.js" type="text/javascript"></script>
<script src="assets/custom/addon/plugins/highcharts/js/solid-gauge.js" type="text/javascript"></script> -->
<link rel="shortcut icon" href="assets/custom/img/favicon.png"/>
</head>
<body style="background-color:#fff;" class="dcReport">
<div class="page-container no-padding m-0">    
<div class="page-content-wrapper">
    <div class="row">       