<iframe src="api/pdf/web/viewer.html?file=../../../<?php echo $this->mergedFile; ?>" frameborder="0" style="width: 100%;height: 550px;" id="<?php echo $this->uid; ?>"></iframe>

<script type="text/javascript">
$(function(){
    setTimeout(function() {
        $('#<?php echo $this->uid; ?>').css('height', ($(window).height() - 110)+'px');
    }, 1);
});    
</script>