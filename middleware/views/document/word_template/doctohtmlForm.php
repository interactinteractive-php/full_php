<div class="htmlform<?php echo $this->uniqId ?> d-none">
    <?php echo html_entity_decode(issetParam(Crypt::decrypt($this->data['HTML_BODY'])), HTML_ENTITIES, 'UTF-8'); ?>
</div>

<script type="text/javascript">
    
var $processId_<?php echo $this->uniqId ?> = '<?php echo issetParam($this->data['PROCESS_ID']) ?>';
var $processUniqId_<?php echo $this->uniqId ?> = '<?php echo issetParam($this->data['UNIQ_ID']) ?>';
var $bp_ntr_htmlform_<?php echo $this->uniqId ?> = $('.htmlform<?php echo $this->uniqId ?>');

jQuery(document).ready(function () {
    
    $bp_ntr_htmlform_<?php echo $this->uniqId ?>.find('.select2-container').remove();
    $bp_ntr_htmlform_<?php echo $this->uniqId ?>.find('.bpMainSaveButton').remove();
    $bp_ntr_htmlform_<?php echo $this->uniqId ?>.find('select.select2').select2({allowClear: false,dropdownAutoWidth: false,closeOnSelect: false, escapeMarkup: function(markup) { return markup;}});
    $bp_ntr_htmlform_<?php echo $this->uniqId ?>.find('select.select2').each(function (index, row) {
        $(row).select2('val', $(row).attr('value'));
    });
    
//    console.clear();
    
    var $trgMultiSelection_<?php echo $this->uniqId ?> = $bp_ntr_htmlform_<?php echo $this->uniqId ?>.find('.trgMultiSelection_'  + $processUniqId_<?php echo $this->uniqId ?>);
    var srcMultiSelector_<?php echo $this->uniqId ?> = $bp_ntr_htmlform_<?php echo $this->uniqId ?>.find('.srcMultiSelection_'  + $processUniqId_<?php echo $this->uniqId ?>);
    var trgMainMultiSelector_<?php echo $this->uniqId ?> = $bp_ntr_htmlform_<?php echo $this->uniqId ?>.find('.trgMainMultiSelector_'  + $processUniqId_<?php echo $this->uniqId ?>);
    
    $('.htmlform<?php echo $this->uniqId ?>').removeClass('d-none');
    
});

</script>