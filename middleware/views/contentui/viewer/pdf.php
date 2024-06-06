<div id="file_viewer_<?php echo $this->contentUniqId; ?>"></div>

<style type="text/css">
#file_viewer_<?php echo $this->contentUniqId; ?> .pdfobject { border: 1px solid #666; }
</style>

<script type="text/javascript">
$.getScript('assets/custom/addon/plugins/pdfobject/pdfobject.min.js').done(function(){
    if (PDFObject.supportsPDFs) {
        var options = {
            height: '500px',
            pdfOpenParams: { view: 'FitV', page: '1' }
        };
        PDFObject.embed("<?php echo $this->fullPath; ?>", "#file_viewer_<?php echo $this->contentUniqId; ?>", options);
    } else {
       alert("PDF are not supported by this browser");
    }
});
</script>