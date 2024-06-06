
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en" class="no-js">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo Config::getFromCache('TITLE'); echo (isset($this->title)) ? ' - ' . $this->title : ''; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <base href="<?php echo URL; ?>">
        <link href="<?php echo autoVersion('assets/core/global/css/fonts/ptsans/ptsans.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo autoVersion('assets/core/global/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo autoVersion('assets/core/global/plugins/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet"/>
        <link href="<?php echo autoVersion('assets/core/global/css/components-rounded.css'); ?>" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="<?php echo autoVersion('assets/core/global/theme/metro/main.css'); ?>" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="assets/core/frontend/ot/img/favicon.png"/>

        <!--[if lt IE 9]>
        <script src="assets/core/global/plugins/respond.min.js"></script>
        <script src="assets/core/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        <script src="<?php echo autoVersion('assets/core/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo autoVersion('assets/core/global/plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo autoVersion('assets/core/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo autoVersion('assets/core/global/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo autoVersion('assets/core/global/plugins/plugins.min.js'); ?>" type="text/javascript"></script>       
        <script src="<?php echo autoVersion('assets/core/global/scripts/metronic.js'); ?>" type="text/javascript"></script>
    </head>

    <body class="endofpage">
        <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; ?>" class="btn-back-ot">Буцах</a>
    </body>