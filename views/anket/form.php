<?php
if (isset($this->css)) {
    foreach ($this->css as $key => $css) {
        echo '<link href="' . autoVersion('assets/core/' . (($key == '0' || is_numeric($key)) ? $css : $key)) . '" rel="stylesheet" type="text/css" media="' . (($css ==
        'print') ? 'print' : 'screen') . '"/>' . "\n";
    }
}

if (isset($this->fullUrlCss)) {
    foreach ($this->fullUrlCss as $fullUrlCss) {
        echo '<link href="' . autoVersion($fullUrlCss) . '" rel="stylesheet" type="text/css"/>' . "\n";
    }
}
?>
<link href="assets/core/js/plugins/addon/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]>
<script src="assets/core/global/plugins/respond.min.js"></script>
<script src="assets/core/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/core/js/main/jquery.min.js"></script>
<script src="assets/core/js/main/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    var URL_FN = URL,
    URL = '<?php echo URL; ?>',
    URL_APP = '<?php echo URL; ?>',
    sysLangCode = '<?php echo Lang::getCode(); ?>',
    ENVIRONMENT = '<?php echo ENVIRONMENT; ?>',
    decimal_fixed_num = 6, 
    round_scale = 2, 
    vr_top_menu = true,  
    isAppMultiTab = <?php echo Config::getFromCacheDefault('CONFIG_MULTI_TAB', null, 0); ?>,
    isAlwaysNewTab = <?php echo Config::getFromCacheDefault('CONFIG_ALWAYS_NEWTAB', null, 0); ?>,
    isTestServer = <?php echo Config::getFromCache('IS_TEST_SERVER') ? 'true' : 'false'; ?>,                
    isCloseOnEscape = <?php echo Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE') ? 'true' : 'false'; ?>,
    pnotifyPosition = '<?php echo Config::getFromCache('CONFIG_PNOTIFY_POSITION'); ?>',
    isDeleteActionBeforeReload = <?php echo Config::getFromCache('CONFIG_IS_DELETEACTION_BEFORERELOAD') ? 'true' : 'false'; ?>,
    gmapApiKey = '',
    isAddRowAsync = true;
</script>
<script src="assets/custom/js/plugins.min.js" type="text/javascript"></script>
<?php
if (isset($this->jsready)) {
    foreach ($this->jsready as $jsready) {
        echo $jsready;
    }
}
if (isset($this->js)) {
    foreach ($this->js as $js) {
        echo '<script src="' . autoVersion('assets/' . $js) . '" type="text/javascript"></script>' . "\n";
    }
}
if (isset($this->fullUrlJs)) {
    foreach ($this->fullUrlJs as $fullUrlJs) {
        echo '<script src="' . autoVersion($fullUrlJs) . '" type="text/javascript"></script>' . "\n";
    }
}
?>
<script type="text/javascript">   
$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    if (options.type.toLowerCase() == 'post') {
        options.data += '&nult=1';
    }
});
</script>    
<script src="<?php echo autoVersion('assets/custom/js/core.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/pki/sign.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdmetadata.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdbp.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mdexpression.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('middleware/assets/js/mddv.js'); ?>" type="text/javascript"></script>  
<script type="text/javascript">
$(document).ready(function(){
    Core.init();
    Core.blockUI({message: 'Loading...', boxed: true});
});
</script>
<section id="body" class="section-anket">
    <div class="anket-form-body">
        <div class="container">
            <div id="processHtml" class="d-none">
                <?php echo $this->contentHtml; ?> 
                
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn bpMainSaveButton rounded-round">
                            <i class="icon-paperplane mr-2"></i> Анкет илгээх
                        </button>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</section> 
<?php 
if ($this->hrAnketWarningMessage && $this->hrAnketWarningMessage['0']['warningmessage']) { 
?>
<div class="modal fade" id="confirm-anket" tabindex="-1" role="dialog" aria-labelledby="confirm-anket" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php echo $this->hrAnketWarningMessage['0']['warningmessage']; ?>
            </div>
            <div class="modal-footer d-block">
                <div class="input-group float-left" style="width: 334px;">
                    <span class="input-group-prepend">	
                        <button class="btn btn-light btn-icon" type="button" id="captcha_reload"><i class="icon-sync"></i></button>
                    </span>
                    <img src="api/captcha?fDownload=1" id="captcha_img">
                    <input type="text" class="form-control" id="captcha_input" placeholder="Зурган дээрхи кодыг оруулна уу">
                </div>
                <button type="button" class="btn button2 btn-secondary hranket-modal-sendbtn" style="border: 0;border-radius: 0; background-color:#258cab;">Анкет илгээх</button> 
                <button type="button" class="btn button2 btn-secondary hranket-modal-closebtn float-right" data-dismiss="modal" style="border: 0;border-radius: 0;">Хаах</button>
            </div>
        </div>
    </div>
</div>    
<?php 
} 
if ($this->hrAnketSaveAfterMessage && $this->hrAnketSaveAfterMessage['0']['saveaftermessage']) { 
?>
<div class="modal fade" id="confirm-successmsg-anket" tabindex="-1" role="dialog" aria-labelledby="confirm-successmsg-anket" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="width:450px;">
            <div class="modal-body">
                <?php echo $this->hrAnketSaveAfterMessage['0']['saveaftermessage']; ?>
            </div>
            <div class="modal-footer">
                <a href="<?php URL ?>anket" class="btn button2 btn-secondary" style="border: 0;border-radius: 0; background-color:#258cab;">Ойлголоо</a> 
            </div>                    
        </div>
    </div>
</div>    
<?php 
} 
?>
 
<style type="text/css">
.anket-form-body .nav-scroll li {
    width: auto !important;
    position: relative;
}
.anket-form-body .nav-scroll li a {
    display: flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    font-size: 11px;
    color: #1b4588;
    padding: 10px !important;
    text-align: left;
    min-height: 48px;
    font-weight: bold;
    line-height: normal;
}
.anket-form-body .nav-scroll li a .order-number {
    background: #1b4588;
    color: #fff;
    font-style: normal;
    font-weight: bold;
    border-radius: 20px;
    width: 25px;
    min-width: 25px;
    height: 25px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}
.anket-form-body .nav-scroll li.active,
.anket-form-body .nav-scroll li:active,
.anket-form-body .nav-scroll li:hover,
.anket-form-body .nav-scroll li:focus {
    background: <?php echo $this->anketHoverColor ?>;
    color: #FFF !important;
}    
.anket-form-body .bpMainSaveButton {
    background-color: <?php echo $this->anketHoverColor ?>;
    color:<?php echo $this->anketColor; ?> !important;
    border:1px  solid <?php echo $this->anketColor; ?>;
}
.form-control .select2-choice, .form-control .select2-choices {
    height: 34px;
}
.bp-layout .col-form {
    padding-bottom: 22px;
}
.bp-layout .card {
    box-shadow: 0 5px 30px 0px rgb(0 0 0 / 10%);
}
.bp-layout .card > .card-body {
    padding: 0.5rem 0.8rem;
}
div[data-bp-detail-container] .table-toolbar {
    margin-top: 10px !important;
    margin-bottom: 10px;
}
.bp-layout .col-form-label {
    font-weight: normal !important;
    margin-bottom: 0px !important;
}
.bp-layout .form-group {
    margin-bottom: 6px !important;
}
.bp-layout .card > .card-header:not(.invisible) {
    margin-top: -14px;
    margin-bottom: 13px;
    border-bottom: 1px solid #d8e7f7;
    padding-bottom: 5px;
}
.bp-layout .card > .card-header.invisible {
    height: 0;
    padding: 0;
    margin: 0;
}
.bp-layout .card > .card-header > .card-title {
    color: #405e7c;
    font-weight: bold;
    font-size: 14px;
    padding-left: 10px;
}
.bp-layout .card > .card-header > .card-title::before {
    background-color: #1b4588;
    content: "";
    position: absolute;
    top: 0%;
    left: 0px;
    width: 3px;
    height: 100%;
    z-index: 1;
}
.bp-layout .card > .card-body > .card-header {
    margin-top: 0;
    margin-bottom: 13px;
    margin-left: -20px;
    margin-right: -20px;
    border-bottom: 1px solid #d8e7f7;
    padding-bottom: 5px;
}
.bp-layout .card > .card-body > .card-header > .card-title {
    color: #405e7c;
    font-weight: bold;
    font-size: 14px;
    padding-left: 10px;
}
.bp-layout .card > .card-body > .card-header > .card-title::before {
    background-color: #1b4588;
    content: "";
    position: absolute;
    top: 0%;
    left: 0px;
    width: 3px;
    height: 100%;
    z-index: 1;
}
.bp-layout .card > .card-body > .card-header:first-of-type {
    margin-top: -5px;
    padding-top: 0;
}
</style>
<script src="<?php echo autoVersion('assets/custom/anket/js/anketForm.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
    anketForm.init('<?php echo $this->departmentId; ?>', '<?php echo issetParam($this->value['departmentname']); ?>', '<?php echo $this->positionId; ?>', '<?php echo issetParam($this->value['positionname']); ?>', '<?php echo $this->templateId; ?>', '<?php echo $this->campaignKeyId; ?>', '<?php echo $this->kpiTemplateId; ?>', '<?php echo issetParam($this->publicAnket); ?>');
});
function anketFormLoadEnd() {
    $('#processHtml').removeClass('d-none');
    Core.unblockUI();
}
</script>