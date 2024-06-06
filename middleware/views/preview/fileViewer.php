<?php
if ($this->fileExtension == 'pdf') {
    echo issetParam($this->statusButtons);
?>
<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="<?php echo URL; ?>api/pdf/web/viewer.html?file=<?php echo $this->fullPath; ?>&isignoredownload=<?php echo $this->isIgnoreDownload; ?>&isignoretoolbarprint=<?php echo $this->isIgnoreToolbarPrint; ?>#zoom=page-actual" frameborder="0" style="width: 100%;height: 550px;"></iframe>
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
} elseif ($this->fileExtension == 'ifc') { 
?>
<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="https://bim.interactive.mn/?filepath=<?php echo $this->fullPath; ?>" frameborder="0" style="width: 100%;height: 550px;"></iframe>
<?php
} elseif ($this->fileExtension == 'png' 
        || $this->fileExtension == 'gif' 
        || $this->fileExtension == 'jpeg' 
        || $this->fileExtension == 'jpg' 
        || $this->fileExtension == 'bmp' 
        || $this->fileExtension == 'tiff') {  
?>
<div id="file_viewer_<?php echo $this->rowId; ?>" class="text-center">
    <img src="<?php echo $this->fullPath; ?>" class="img-fluid mar-auto">
</div>
<?php
} elseif ($this->fileExtension == 'mp4' 
        || $this->fileExtension == 'ogg' 
        || $this->fileExtension == 'avi' 
        || $this->fileExtension == 'mov' 
        || $this->fileExtension == 'm4p' 
        || $this->fileExtension == 'm4v') {
?>
<div class="text-center">
    <video width="650" height="450" controls>
        <source src="<?php echo $this->fullPath; ?>" type="video/<?php echo $this->fileExtension; ?>">
        Your browser does not support HTML5 video.
    </video>
</div>    
<?php
} elseif ($this->fileExtension == 'mp3') {
?>
<div class="text-center">
    <audio controls>
        <source src="<?php echo $this->fullPath; ?>" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</div>
<?php
} elseif ($this->fileExtension == 'html') {
    $htmlContent = @file_get_contents(str_replace(URL, '', $this->fullPath));
    echo $htmlContent;
}
?>
<script type="text/javascript">
$(function() {
    var $dialogId = $('#dialog-fileviewer-<?php echo $this->rowId; ?>');
    
    if (!$dialogId.length) {
        var $iframeElement = $('#file_viewer_<?php echo $this->rowId; ?>');
        setTimeout(function() {
            $iframeElement.css('height', ($(window).height() - $iframeElement.offset().top - 12)+'px');
        }, 1);
    } 
});
</script>