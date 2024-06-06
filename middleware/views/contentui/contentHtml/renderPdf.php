<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<iframe id="<?php echo $this->uniqId; ?>" src="<?php echo URL; ?>api/pdf/web/viewer.html?file=<?php echo URL . $this->contentData['PHYSICAL_PATH']; ?>" frameborder="0" style="width: 100%;height: 550px;"></iframe>

<script type="text/javascript">
$(function() {
    setTimeout(function(){
        $("#<?php echo $this->uniqId; ?>").css({"height": $(window).height() - $("#<?php echo $this->uniqId; ?>").offset().top - 10});
    }, 100);
});
</script>
