<style>
    .joint-paper-scroller.joint-theme-modern {
        background-color:#ffff;
    }
    [data-name="save"] {
        border-color: #1bbc9b !important;
        color: #1bbc9b !important;
        padding: 10px 10px 10px 10px !important;  
        margin-left: 8px !important;
        margin-right: 8px !important;
    }
    [data-name="png"] {
        padding: 10px 10px 10px 10px !important;  
        margin-left: 8px !important;
    }
    /* .joint-theme-modern {
        top: 0px !important;
    } */
</style>
<div id="app" class="joint-app">
    <div class="app-header">
    <div class="toolbar-container"></div>
    </div>
    <div class="app-body">
    <!-- <div class="stencil-container"></div> -->
    <div class="paper-container"></div>
    <!-- <div class="inspector-container"></div> -->
    <div class="navigator-container"></div>
    </div>
</div>
<script src="<?php echo autoVersion('assets/rappidjs/industry/bundle.js'); ?>" type="text/javascript"></script>
<!-- <script src="http://localhost:8080/bundle.js" type="text/javascript"></script> -->

<script type="text/javascript">
    var dynamicHeight = $(window).height() - $("#app").offset().top - 20;
    $("#app").css('height', dynamicHeight - 20);

    if (typeof window.joint === 'undefined') {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/industry/css/style.css"/>');
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/industry/css/theme-picker.css"/>');
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/industry/css/style.dark.css"/>');
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/industry/css/style.material.css"/>');
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/industry/css/style.modern.css"/>');
        // $("head").append('<link rel="stylesheet" type="text/css" href="http://localhost:8080/css/style.css"/>');
        // $("head").append('<link rel="stylesheet" type="text/css" href="http://localhost:8080/css/theme-picker.css"/>');
        // $("head").append('<link rel="stylesheet" type="text/css" href="http://localhost:8080/css/style.dark.css"/>');
        // $("head").append('<link rel="stylesheet" type="text/css" href="http://localhost:8080/css/style.material.css"/>');
        // $("head").append('<link rel="stylesheet" type="text/css" href="http://localhost:8080/css/style.modern.css"/>');
        // $.cachedScript("http://localhost:8080/bundle.js").done(function() {
        // });
    }

    $(function() {
    });
</script>