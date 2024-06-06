<?php
if ($this->fileExtension == 'pdf') {
?>
<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="<?php echo URL; ?>api/pdf/web/viewer.html?file=<?php echo $this->fullPath; ?>#zoom=page-actual" frameborder="0" style="width: 100%;height: 550px;"></iframe>
<?php
} elseif ($this->fileExtension == 'doc' || $this->fileExtension == 'docx') {
?>
<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0&url=<?php echo $this->fullPath; ?>" frameborder="0" style="width: 100%;height: 550px;"></iframe>
<?php
} elseif ($this->fileExtension == 'ppt' || $this->fileExtension == 'pptx') {
?>
<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>documentviewer.aspx?showRb=0&url=<?php echo $this->fullPath; ?>" frameborder="0" style="width: 100%;height: 550px;"></iframe>
<?php
} elseif ($this->fileExtension == 'xls' || $this->fileExtension == 'xlsx') {
?>
<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0&url=<?php echo $this->fullPath; ?>" frameborder="0" style="width: 100%;height: 550px;"></iframe>
<?php
} elseif ($this->fileExtension == 'jpg' 
        || $this->fileExtension == 'jpeg' 
        || $this->fileExtension == 'png' 
        || $this->fileExtension == 'gif' 
        || $this->fileExtension == 'tiff' 
        || $this->fileExtension == 'bmp') {
?>
<img src="<?php echo $this->fullPath; ?>" class="img-fluid"/>
<?php
} elseif ($this->fileExtension == 'mp4' 
        || $this->fileExtension == 'ogg' 
        || $this->fileExtension == 'avi' 
        || $this->fileExtension == 'mov' 
        || $this->fileExtension == 'm4p' 
        || $this->fileExtension == 'm4v') {
?>
<video width="420" height="340" controls id="bp-video-id">
    <source src="<?php echo $this->fullPath; ?>" type="video/<?php echo $this->fileExtension; ?>">
    Your browser does not support HTML5 video.
</video>
<?php
} elseif ($this->fileExtension == 'mp3') {
?>
<audio controls>
    <source src="<?php echo $this->fullPath; ?>" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<?php
}
?>
<script type="text/javascript">
$(function() {
    var $dialog = $('#file_viewer_<?php echo $this->rowId; ?>').closest('.ui-dialog-content');
    
    if (!$dialog.length) {
        var $iframeElement = $('#file_viewer_<?php echo $this->rowId; ?>');
        setTimeout(function() {
            $iframeElement.css('height', ($(document).height() - $iframeElement.offset().top - 12)+'px');
        }, 1);
    } 
});
</script>