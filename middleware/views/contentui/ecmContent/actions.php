<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<style type="text/css">
  .static-folder-list .div-objectdatagrid-<?php echo Config::getFromCache('ECM_CONTENT_FOLDER_DV'); ?> .datagrid-pager,
  .static-folder-list .remove-type-<?php echo Config::getFromCache('ECM_CONTENT_FOLDER_DV'); ?> {
      display: none;
  }
</style>

<div class="hide" id="actions-div-<?php echo $this->contentId; ?>">  
  <div class="static-folder-list"></div>
</div>

<script type="text/javascript">
    var ECM_CONTENT_FOLDER_DV=<?php echo Config::getFromCache('ECM_CONTENT_FOLDER_DV'); ?>,
            selectedDataviewId='<?php echo $this->dataViewId; ?>',
            selectedContentId='<?php echo $this->contentId; ?>',
            MET_99990632='<?php echo $this->lang->line('MET_99990632'); ?>',
            move_btn_txt='<?php echo $this->lang->line('move_btn'); ?>',
            make_copy_txt='<?php echo $this->lang->line('Make a copy'); ?>',
            copy_btn_txt='<?php echo $this->lang->line('copy_btn'); ?>';

    /* global folderAction */
    $(function(){
      if(typeof folderAction === 'undefined'){
        $.getScript(URL_APP + 'middleware/assets/js/contentui/folderAction.js', function(){
          initFolderAction();
        });
      } else {
        initFolderAction();
      }
    });
    function initFolderAction(){
<?php if ($this->typeFolderAction == 1 || $this->typeFolderAction == 2) { ?>
          folderAction.init(<?php echo $this->typeFolderAction; ?>, true);
<?php } ?>
    }
</script>