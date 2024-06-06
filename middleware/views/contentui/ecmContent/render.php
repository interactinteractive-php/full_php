<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div id="ecmRenderDiv_<?php echo $this->renderUniqid; ?>"></div>

<script type="text/javascript">
    if(<?php echo $this->renderType; ?> === 1){
        getEcmContentModal(function(response){
            $('#ecmRenderDiv_<?php echo $this->renderUniqid; ?>').html(response.html);
        });
    } else if(<?php echo $this->renderType; ?> === 2) {
       getEcmContentModal(); 
    }    
</script>