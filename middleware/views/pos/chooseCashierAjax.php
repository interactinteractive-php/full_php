<div class="col-md-4 col-center-block">
    <?php
    foreach ($this->cashierList as $row) {
    ?>
    <a href="javascript:;" onClick="chooseCashierLocker(this)" data-url="mdpos/chooseCashier/<?php echo $row['storeid']; ?>/<?php echo $row['cashregisterid']; ?>/<?php echo $row['cashierid']; ?>" class="csh-link">
        <div class="csh-tbl">
            <div class="csh-row">
                <div class="csh-name-cell"><?php echo $this->lang->line('POS_0137'); ?>:</div>
                <div class="csh-code-cell"><?php echo $row['storename']; ?></div>
            </div>   
            <div class="csh-row">
                <div class="csh-name-cell"><?php echo $this->lang->line('POS_0138'); ?>:</div>
                <div class="csh-code-cell"><?php echo $row['posname']; ?></div>
            </div>  
            <div class="csh-row">
                <div class="csh-name-cell"><?php echo $this->lang->line('POS_0139'); ?>:</div>
                <div class="csh-code-cell"><?php echo $row['cashiername']; ?></div>
            </div>  
        </div>
    </a>
    <?php
    }
    ?>

    <div class="text-center mt15"><?php echo $this->lang->line('POS_0140'); ?></div>
</div>

<script>
    function chooseCashierLocker(elem) {
        var $this = $(elem);
        $.ajax({
            type: 'post',
            url: $this.data('url'), 
            data: {
            },
            dataType: 'json',
            beforeSend: function(){
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true 
                });
            },
            success: function(data){
                if (data.status === 'success') {
                    $.ajax({
                        type: 'post',
                        url: 'mdpos/index', 
                        data: <?php echo json_encode(Input::postData('selectedRow')); ?>,
                        dataType: 'json',
                        beforeSend: function(){
                        },
                        success: function(data){
                            var $pthis = $this.closest('.col-center-block').parent();
                            $this.closest('.col-center-block').remove();
                            $pthis.append(data.html);
                            $pthis.find('.pos-wrap').css({"margin-left":"-15px", "margin-right":"-16px", "margin-top":"-9px"});
                        }
                    }).done(function(){
                        if (typeof checkInitPosJS === 'undefined') {
                            $.ajax({
                                url: "middleware/assets/js/pos/pos.js", 
                                dataType: "script",
                                cache: false, 
                                async: false 
                            });        
                        } else {
                            setTimeout(function(){
                                Core.initDecimalPlacesInput();
                                posConfigVisibler($('body'));
                                posPageLoadEndVisibler();
                                posItemCombogridList('');
                                $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();                 
                            }, 300);
                        }
                        setTimeout(function(){
                            posTableSetHeight();
                            posFixedHeaderTable();
                        }, 300);                            
                    });          
                }
                Core.unblockUI();
            }
        });        
    }
</script>