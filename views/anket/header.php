<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->title; ?></title>
    <meta name="robots" content="index, follow" />
    <meta content="<?php echo $this->title; ?>" name="description" />
    <meta name="keywords" content="<?php echo $this->title; ?>" />
    <meta name="author" content="<?php echo $this->title; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta property="og:title" content="<?php echo $this->title; ?>">
    <meta property="og:url" content="<?php echo Uri::currentURL(); ?>">
    <meta property="og:description" content="<?php echo $this->title; ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo URL; ?>">
    <meta property="og:image" content="<?php echo URL.$this->anketLogo; ?>">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@anket" />
    <meta name="twitter:creator" content="@anket" />
    <meta name="twitter:title" content="<?php echo $this->title; ?>" />
    <meta name="twitter:description" content="<?php echo $this->title; ?>" />
    <meta name="twitter:url" content="<?php echo Uri::currentURL(); ?>" />
    <meta name="twitter:image" content="<?php echo URL.$this->anketLogo; ?>" />
    <link rel="canonical" href="<?php echo Uri::currentURL(); ?>" />
    <base href="<?php echo URL; ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo URL.$this->anketLogo; ?>" />
    <link href="assets/core/css/core.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo autoVersion('assets/core/js/plugins/select2/select2.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
    <link href="assets/core/icon/fontawesome/all.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/core/icon/fontawesome/v4-shims.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/core/icon/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?php echo autoVersion('assets/custom/anket/css/style.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo autoVersion('assets/custom/css/plugins.css'); ?>" rel="stylesheet"/>
    <script src="<?php echo autoVersion(Lang::loadjs()); ?>" type="text/javascript"></script>
</head>
<body class="anket-body">
<header class="anket-header mb-4">
    <div class="top-menu">
        <div class="container">
            <div class="d-flex align-items-center">
                <?php
                if ($this->anketLogo) {
                ?>   
                <div class="col-3 pl-0">
                    <a href="<?php echo URL ?>anket">
                        <img src="<?php echo URL.$this->anketLogo; ?>" class="logo"/>
                    </a>
                    <button type="button" class="toggle-menu-btn" id="toggleMenuBtn"><i class="fa fa-bars fr"></i></button>
                </div>
                <?php
                }
                if ($this->anketContactPhone) {
                ?>    
                <div class="col-2 top-contact d-flex align-items-center ml-auto">
                    <i class="icon-phone2 mr-2"></i>
                    <span class="top-contact-title pt-10"><?php echo $this->anketContactPhone; ?></span>
                </div>
                <?php
                }
                if ($this->anketWorkTime) {
                ?>  
                <div class="col-2 top-contact d-flex align-items-center">
                    <i class="icon-clipboard5 mr-2"></i>
                    <div class="d-flex flex-column top-contact-title">
                        <?php echo html_entity_decode($this->anketWorkTime, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
                <?php
                }
                if ($this->anketContactHeaderLocation) {
                ?>  
                <div class="col-3 top-contact d-flex align-items-center">
                    <i class="icon-location3 mr-2"></i>
                    <div class="d-flex flex-column top-contact-title">
                        <?php echo $this->anketContactHeaderLocation; ?>
                    </div>
                </div>
                <?php
                }
                ?>    
                <ul class="d-flex social-icons-header list-inline mb-0">
                    <?php 
                    if ($this->anketFacebookLink) { 
                    ?>
                    <li><a href="<?php echo $this->anketFacebookLink ?>" target="_blank"><i class="icon-facebook" style="background-color: <?php echo $this->anketColor ?>95"></i></a></li>
                    <?php 
                    } 
                    if ($this->anketTwitterLink) { 
                    ?>
                    <li><a href="<?php echo $this->anketTwitterLink ?>" target="_blank"><i class="icon-twitter" style="background-color: <?php echo $this->anketColor ?>95"></i></a></li>
                    <?php 
                    }
                    if ($this->anketLinkedinLink) { 
                    ?>
                    <li><a href="<?php echo $this->anketLinkedinLink ?>" target="_blank"><i class="icon-linkedin" style="background-color: <?php echo $this->anketColor ?>95"></i></a></li>
                    <?php 
                    }
                    if ($this->anketInstaLink) { 
                    ?>
                    <li><a href="<?php echo $this->anketInstaLink ?>" target="_blank"><i class="icon-instagram" style="background-color: <?php echo $this->anketColor ?>95"></i></a></li>
                    <?php 
                    } 
                    ?>
                </ul>
            </div>
        </div>
    </div>
</header>
<style type="text/css">
    .anket-body section#body ul.ot-paging li.active,
    .anket-body .section-anket .section-title::before,
    .anket-body .anket-desc h3::before,
    .anket-body section#body ul.anket-pagination li.active,
    .anket-body section#body ul.anket-pagination li:hover,
    .anket-body section#body ul.anket-pagination li:active,
    .anket-body section#body ul.anket-pagination li:focus {
        background-color: <?php echo $this->anketColor; ?>
    }
    .anket-form-body .green-meadow.btn i{
        color:<?php echo $this->anketColor; ?> !important;
    }
    .anket-form-body .green-meadow.btn{
        background:#fff !important;
        color:<?php echo $this->anketColor; ?> !important;
        border:1px  solid <?php echo $this->anketColor; ?>;
    }
    .anket-body .table-striped tbody tr:nth-of-type(odd) {
        background-color: <?php echo $this->anketColor; ?>10;
    }
    .anket-form-body .btn-xs.bp-remove-row{
        background-color: #fff !important;
        color: red !important;
    }
    .anket-body .table-striped tbody tr:hover {
        background-color: <?php echo $this->anketColor; ?>20 !important;
    }
</style>