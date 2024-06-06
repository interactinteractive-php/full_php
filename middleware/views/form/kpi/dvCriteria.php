<div data-bp-uniq-id="<?php echo $this->uniqId; ?>" class="dv-kpiform-criteria">
    <div class="col-md-12">
        <div class="form-group row">
            <?php 
            echo Form::label(
                array(
                    'text' => 'Загвар',  
                    'class' => 'col-form-label col-md-3 text-right pr0'
                )
            ); 
            ?>
            <div class="col-md-9">
                <?php 
                echo Form::select(
                    array(
                        'class' => 'form-control form-control-sm select2 kpitemplateid-combo',
                        'data' => $this->getTemplates['data'], 
                        'op_value' => 'id', 
                        'op_text' => 'name'
                    )
                ); 
                ?> 
            </div>
        </div>
    </div>    
    <div data-section-path="kpiDmDtl" class="mt15">
    </div>    
</div>     

<script type="text/javascript">
var bp_window_<?php echo $this->uniqId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
            
$(function() {
    
    bp_window_<?php echo $this->uniqId; ?>.on('change', '.kpitemplateid-combo', function() {
        
        var $elem = $(this), kpiTemplateId = $elem.val(), 
            $kpiSection = bp_window_<?php echo $this->uniqId; ?>.find('div[data-section-path="kpiDmDtl"]');
        
        $kpiSection.empty();
        
        if (kpiTemplateId != '') {
            
            $.ajax({
                type: 'post', 
                url: 'mdform/kpiFormByTemplateId', 
                data: {
                    uniqId: '<?php echo $this->uniqId; ?>', 
                    templateId: kpiTemplateId
                },
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    
                    if (data.status == 'success') {
                        $kpiSection.append(data.html).promise().done(function() {
                            $elem.closest('.ui-dialog-content').dialog('option', 'position', {my: 'center', at: 'center', of: window});
                            Core.unblockUI();
                        });
                    } else {
                        Core.unblockUI();
                    }
                }, 
                error: function() { alert('Error'); Core.unblockUI(); }
            });      
        } 
    });
    
});    
</script>