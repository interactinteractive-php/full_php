<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="ecmContentDropzone">
  <form action="#" class="dropzone" id="ecmContentDropzone_<?php echo $this->dUniqid; ?>"></form>
</div>

<script type="text/javascript">
    var message_to_uploader="<?php echo $this->lang->line('message_to_uploader'); ?>",
            dataviewIdForContent='<?php echo $this->dataViewId; ?>',
            dUniqid=<?php echo $this->dUniqid; ?>;
    /* global ecmContent */
    $(function(){
      if(typeof Dropzone === 'undefined' && typeof ecmContent === 'undefined'){
        $.getScript(URL_APP + 'assets/custom/addon/plugins/dropzone/dropzone.min.js', function(){
          $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/dropzone/css/dropzone.css');
          $.getStylesheet(URL_APP + 'middleware/assets/css/contentui/ecmContent.css');
          $.getScript(URL_APP + 'middleware/assets/js/contentui/ecmContent.js', function(){
            ecmContent.init();
          });
        });
      } else {
        ecmContent.init();
      }
    });
</script>