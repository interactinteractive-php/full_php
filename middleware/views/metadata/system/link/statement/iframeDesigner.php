<div class="row" style="margin-top: -11px; margin-bottom: -10px" id="<?php echo $this->uniqId; ?>">
    <iframe src="<?php echo $this->iframeUrl; ?>" frameborder="0" style="width: 100%;height: <?php echo $this->windowHeight; ?>px; border: 0"></iframe>
</div>

<script type="text/javascript">
$(function(){
    var $iframe_<?php echo $this->uniqId; ?> = $('#<?php echo $this->uniqId; ?>');
    
    bpBlockMessageStart('Loading...');
    
    $iframe_<?php echo $this->uniqId; ?>.find('iframe:eq(0)').on('load', function () {
        bpBlockMessageStop();
    });
});    
</script>