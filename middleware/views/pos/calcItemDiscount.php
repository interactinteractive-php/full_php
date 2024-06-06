<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<form class="pos-item-row-discount">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="pb5"><?php echo $this->lang->line('POS_0130'); ?></div>
            <?php
            echo Form::select(
                array(
                    'id' => 'discountTypeId',
                    'data' => $this->typeList,
                    'op_value' => 'ID',
                    'op_text' => 'NAME',
                    'op_param' => 'IS_PLUS', 
                    'class' => 'form-control form-control-sm select2', 
                    'text' => '- '.$this->lang->line('choose_btn').' -', 
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    <div class="row mt15">
        <div class="col-md-6 text-center">
            <div class="pb5"><?php echo $this->lang->line('POS_0131'); ?></div>
            <input type="text" class="form-control input-circle bigdecimalInit" placeholder="<?php echo $this->lang->line('POS_0131'); ?>" id="calcRowDiscountPercentInput" required="required">
        </div>
        <div class="col-md-6 text-center">
            <div class="pb5"><?php echo $this->lang->line('POS_0132'); ?></div>
            <input type="text" class="form-control input-circle bigdecimalInit" placeholder="<?php echo $this->lang->line('POS_0132'); ?>" id="calcRowDiscountAmountInput" required="required">
        </div>
    </div>
    <div class="row mt15">
        <div class="col-md-12 text-center">
            <div class="pb5"><?php echo $this->lang->line('POS_0133'); ?></div>
            <div class="meta-autocomplete-wrap" data-section-path="discountEmployeeId">
                <div class="input-group double-between-input">
                    <input type="hidden" name="discountEmployeeId" id="discountEmployeeId_valueField" data-path="discountEmployeeId" class="popupInit" data-criteria="filterStoreId=<?php echo $this->storeId; ?>">
                    <input type="text" name="discountEmployeeId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="discountEmployeeId" id="discountEmployeeId_displayField" data-processid="1454315883636" data-lookupid="1525865169487845" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" required="required">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('discountEmployeeId', '1454315883636', '1525865169487845', 'single', 'discountEmployeeId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                    </span>  
                    <span class="input-group-btn">
                        <input type="text" name="discountEmployeeId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="discountEmployeeId" id="discountEmployeeId_nameField" data-processid="1454315883636" data-lookupid="1525865169487845" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" required="required">
                    </span>   
                </div>
            </div>
        </div>
    </div>
    <div class="row mt15">
        <div class="col-md-12 text-center">
            <div class="pb5">Бүх бараа хөнгөлөх эсэх</div>
            <?php
            echo Form::checkbox(
                array(
                    'id' => 'isAllItemsForDiscount',
                    'class' => 'notuniform'
                )
            );
            ?>
        </div>
    </div>
    <div class="row mt15">
        <div class="col-md-12 text-center">
            <div class="pb5"><?php echo $this->lang->line('POS_0129'); ?></div>
            <?php
            echo Form::textArea(
                array(
                    'id' => 'discountDescription',
                    'class' => 'form-control form-control-sm', 
                    'rows' => 2
                )
            );
            ?>
        </div>
    </div>
    <div class="row mt15">
        <div class="col-md-12">
            <button type="button" class="btn btn-block btn-circle green-meadow posDiscountBtn" onclick="posItemDiscountBtn();">
                <?php echo $this->lang->line('POS_0134'); ?>
            </button>
        </div>
    </div>
</div>    

<script type="text/javascript">
$(function(){
    
    var $discountForm = $('form.pos-item-row-discount');
    
    Core.initDecimalPlacesInput($discountForm);
    Core.initRegexMaskInput($discountForm);
    Core.initSelect2($discountForm);
            
    var $tbody = $('#posTable > tbody'), 
        $itemRow = $tbody.find('> tr[data-item-id].pos-selected-row:eq(0)'), 
        $discountType = $itemRow.find('input[name="discountTypeId[]"]');
    
    if ($discountType.val() != '') {
        
        var $discountEmployee = $itemRow.find('input[name="discountEmployeeId[]"]');
        
        $('#discountTypeId').select2('val', $discountType.val());
        $('#calcRowDiscountPercentInput').val($itemRow.find('input[name="discountPercent[]"]').val());
        $('#calcRowDiscountAmountInput').val($itemRow.find('input[name="unitDiscount[]"]').val());
        $('#discountEmployeeId_valueField').val($discountEmployee.val());
        $('#discountEmployeeId_displayField').val($discountEmployee.attr('data-emp-code'));
        $('#discountEmployeeId_nameField').val($discountEmployee.attr('data-emp-name'));
        $('#discountEmployeeId_valueField').attr('data-row-data', $discountEmployee.attr('data-emp-json'));
        $('#discountDescription').val($itemRow.find('input[name="discountDescription[]"]').val());
    }
    
    setTimeout(function(){
        $('#calcRowDiscountPercentInput').focus();
    }, 10);
    
    $('#calcRowDiscountPercentInput').on('change', function(){ 
        
        var $discountEmployeeId = $('#discountEmployeeId_valueField'), 
            $this = $(this), 
            thisVal = $this.val();
        
        if ($discountEmployeeId.hasAttr('data-row-data') && $discountEmployeeId.attr('data-row-data') != '') {
            
            var discountEmployeeJson = JSON.parse($discountEmployeeId.attr('data-row-data')), 
                minPercent = Number(discountEmployeeJson.minvalue), 
                maxPercent = Number(discountEmployeeJson.maxvalue), 
                thisPercent= Number(thisVal);
            
            if (thisPercent < minPercent) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: plang.getVar('POS_0135', {minPercent: minPercent}),
                    type: 'warning', 
                    sticker: false
                });
                setTimeout(function(){
                    $this.val('').focus().select();
                }, 5);
                return;
            }
            
            if (thisPercent > maxPercent) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: plang.getVar('POS_0136', {maxPercent: maxPercent}),
                    type: 'warning', 
                    sticker: false
                });
                setTimeout(function(){
                    $this.val('').focus().select();
                }, 5);
                return;
            }
        }
        
        var discountPercent = Number(thisVal), 
            salePrice       = Number($itemRow.find('input[name="salePrice[]"]').val()), 
            discount        = (discountPercent / 100) * salePrice;
        
        $('#calcRowDiscountAmountInput').autoNumeric('set', discount);
        
        $('#discountEmployeeId_valueField').attr('data-criteria', 'filterStoreId=<?php echo $this->storeId; ?>&filterDiscountPercent='+thisVal);
    });
    
    $('#calcRowDiscountPercentInput').on('keydown', function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {
            $('#calcRowDiscountAmountInput').focus().select();
            return e.preventDefault();
        }
    });

    $('#isAllItemsForDiscount').on('click', function(){
        if($(this).is(':checked'))
            $('#calcRowDiscountAmountInput').val('').prop('readonly', true);
        else
        $('#calcRowDiscountAmountInput').val('').prop('readonly', false);
    });
    
    $('#calcRowDiscountAmountInput').on('change', function(){
        
        var $this = $(this), 
            salePrice = Number($itemRow.find('input[name="salePrice[]"]').val()),
            discountAmount = Number($this.autoNumeric('get')), 
            discountPercent = (discountAmount * 100) / salePrice, 
            $discountEmployeeId = $('#discountEmployeeId_valueField');
            
        if ($discountEmployeeId.hasAttr('data-row-data') && $discountEmployeeId.attr('data-row-data') != '') {
            var discountEmployeeJson = JSON.parse($discountEmployeeId.attr('data-row-data')), 
                minPercent = Number(discountEmployeeJson.minvalue), 
                maxPercent = Number(discountEmployeeJson.maxvalue);
            
            if (discountPercent < minPercent) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: plang.getVar('POS_0135', {minPercent: minPercent}),
                    type: 'warning', 
                    sticker: false
                });
                setTimeout(function(){
                    $this.autoNumeric('set', '').focus().select();
                }, 5);
                return;
            }
            
            if (discountPercent > maxPercent) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: plang.getVar('POS_0136', {maxPercent: maxPercent}),
                    type: 'warning', 
                    sticker: false
                });
                setTimeout(function(){
                    $this.autoNumeric('set', '').focus().select();
                }, 5);
                return;
            }
        }
        
        $('#calcRowDiscountPercentInput').val(discountPercent);
        $('#discountEmployeeId_valueField').attr('data-criteria', 'filterStoreId=<?php echo $this->storeId; ?>&filterDiscountPercent='+$('#calcRowDiscountPercentInput').val());
    });
    
    $('#calcRowDiscountAmountInput').on('keydown', function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {
            $('#discountEmployeeId_displayField').focus().select();
            return e.preventDefault();
        }
    });
    
    $('#discountTypeId').on('change', function(){
        var $this = $(this), 
            isDiscountPlus = $this.find('option:selected').attr('param'), 
            $discountEmployeeId = $('#discountEmployeeId_displayField, #discountEmployeeId_nameField');
            
        if (isDiscountPlus == '1') {
            $discountEmployeeId.removeAttr('required');
        } else {
            $discountEmployeeId.attr('required', 'required');
        }
    });
});
</script>