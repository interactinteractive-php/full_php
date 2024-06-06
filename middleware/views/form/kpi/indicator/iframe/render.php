<iframe id="<?php echo $this->uniqId; ?>" src="<?php echo $this->row['C1']; ?>" frameborder="0" allowfullscreen style="width: 100%;height: 600px;"></iframe>

<script type="text/javascript">
$(function() {
    var $id = $('#<?php echo $this->uniqId; ?>');
    var dynamicHeight = $(window).height() - $id.offset().top - 45;
    $id.css('height', dynamicHeight);
});
</script>