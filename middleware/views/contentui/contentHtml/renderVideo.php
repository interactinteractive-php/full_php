<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<video id="<?php echo $this->uniqId; ?>" width="100%" height="340" controls>
    <source src="<?php echo $this->contentData['PHYSICAL_PATH']; ?>" type="video/<?php echo $this->fileExtension; ?>">
    Your browser does not support HTML5 video.
</video>

<script type="text/javascript">
$(function() {
    setTimeout(function(){
        $("#<?php echo $this->uniqId; ?>").attr('height', $(window).height() - $("#<?php echo $this->uniqId; ?>").offset().top - 45);
    }, 100);
});
</script>
