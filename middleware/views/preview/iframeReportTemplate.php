<div class="st-iframe-parent">
    <button type="button" class="st-iframe-fullscreen-btn" title="Fullscreen" style="display: none">
        <span class="icon-size-fullscreen"></span>
    </button>
    <iframe src="<?php echo $this->reportUrl; ?>" data-default-url="<?php echo $this->defaultUrl; ?>" data-layout-id="<?php echo $this->layoutId; ?>" data-default-height="1130px" frameborder="0" style="width: 100%;height: 1130px; border: 0"></iframe>
</div>

<script type="text/javascript">
// var $statement_form_<?php //echo $this->metaDataId.$this->dataViewId; ?> = $("div#statement-form-<?php //echo $this->metaDataId; ?>");

$(function(){
    
    // $statement_form_<?php //echo $this->metaDataId.$this->dataViewId; ?>.find('iframe:eq(0)').on('load', function () {
    //     $statement_form_<?php //echo $this->metaDataId.$this->dataViewId; ?>.find('.st-iframe-fullscreen-btn').show();
    // });
                            
    // $statement_form_<?php //echo $this->metaDataId.$this->dataViewId; ?>.on('click', '.st-iframe-fullscreen-btn:visible:last', function() {
    //     var $this = $(this);
    //     var $parent = $this.closest('.st-iframe-parent');
    //     var $openDialog = $parent.closest('.ui-dialog');
    //     var $isDialog = ($openDialog.length) ? true : false;
        
    //     if (!$this.hasAttr('data-fullscreen')) {
            
    //         if ($isDialog) {
    //             $openDialog.css('overflow', 'inherit');
    //         }
        
    //         $this.attr({'data-fullscreen': '1', 'title': 'Restore'});
    //         $this.find('span').removeClass('icon-size-fullscreen').addClass('icon-size-actual');
    //         $parent.addClass('st-iframe-fullscreen');
    //         $('html').css('overflow', 'hidden');
            
    //     } else {
            
    //         if ($isDialog) {
    //             $openDialog.css('overflow', '');
    //         }
        
    //         $this.attr({'title': 'Fullscreen'}).removeAttr('data-fullscreen');
    //         $this.find('span').removeClass('icon-size-actual').addClass('icon-size-fullscreen');
    //         $parent.removeClass('st-iframe-fullscreen');
    //         $('html').css('overflow', '');
    //     }
    // });
});
</script>