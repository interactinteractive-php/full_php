<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'pos-order-form', 'method' => 'post')); ?>
<div class="row xs-form">
    <div class="col-md-12" id="pos-payment-accordion-delivery">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('POS_0130'), 'for' => 'orderTypeId', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-9">
                <div class="radio-list">
                    <label class="radio-inline pt0">
                        <input type="radio" name="orderTypeIdTemp" value="12" checked="checked"> Талон
                    </label>
                    <label class="radio-inline pt0">
                        <input type="radio" name="orderTypeIdTemp" value="91"> Нэхэмжлэх
                        <input type="hidden" name="orderTypeId" value="12">
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('POS_0128'), 'for' => 'phoneNumber', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-4">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'phoneNumber', 
                        'id' => 'phoneNumber', 
                        'class' => 'form-control form-control-sm posAddressField', 
                        'required' => 'required', 
                        'data-inputmask-regex' => '^[0-9]{1,8}$', 
                        'placeholder' => $this->lang->line('POS_0128'), 
                        'value' => Arr::get($this->row, 'deliverycontactphone')
                    )
                ); 
                ?>
            </div>
        </div>
        
        <div id="orderInvoicePart" style="display: none">
            <div class="form-group row fom-row">
                <label class="col-form-label col-md-3" for="bankAccountId_displayField">Банк:</label>
                <div class="col-md-8">
                    <div class="meta-autocomplete-wrap" data-section-path="bankAccountId">
                        <div class="input-group double-between-input">
                            <input type="hidden" name="bankAccountId" id="bankAccountId_valueField" data-path="bankAccountId" class="popupInit" value="<?php echo Arr::get($this->row, 'bankaccountid'); ?>">
                            <input type="text" name="bankAccountId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="bankAccountId" id="bankAccountId_displayField" data-processid="1454315883636" data-lookupid="1522946988985" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" value="<?php echo Arr::get($this->row, 'bankaccountcode'); ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('bankAccountId', '1454315883636', '1451439499696', 'single', 'bankAccountId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                            </span>  
                            <span class="input-group-btn">
                                <input type="text" name="bankAccountId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="bankAccountId" id="bankAccountId_nameField" data-processid="1454315883636" data-lookupid="1522946988985" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" value="<?php echo Arr::get($this->row, 'bankaccountname'); ?>">
                            </span>   
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row fom-row">
                <label class="col-form-label col-md-3"><span class="required">*</span>Нэхэмжлэхийн төрөл:</label>
                <div class="col-md-8">
                    <?php
                    echo Form::select(
                        array(
                            'id' => 'invoiceTypeIdForm',
                            'name' => 'invoiceTypeIdForm',
                            'data' => $this->invoiceTypeList,
                            'op_value' => 'id',
                            'op_text' => 'code| |-| |name',
                            'class' => 'form-control form-control-sm select2', 
                            'text' => '- '.$this->lang->line('choose_btn').' -', 
                            'value' => Arr::get($this->row, 'invoicetypeid')
                        )
                    );
                    ?>
                </div>
            </div>
            
            <div class="form-group row fom-row">
                <label class="col-form-label col-md-3" for="expireDate"><?php echo $this->lang->line('POS_0170'); ?>:</label>
                <div class="col-md-4">
                    <?php 
                    if (isset($this->row['expiredate'])) {
                        $expireDate = Date::formatter($this->row['expiredate'], 'Y-m-d H:i');
                    } else {
                        $expireDate = Date::weekdayAfter('Y-m-d H:i', Date::currentDate('Y-m-d H:i'), '+1 day');
                    }
                    echo Form::text(
                        array(
                            'name' => 'expireDate', 
                            'id' => 'expireDate', 
                            'class' => 'form-control dateminuteInit posAddressField', 
                            'placeholder' => $this->lang->line('POS_0170'), 
                            'value' => $expireDate
                        )
                    ); 
                    ?>
                </div>
            </div>

            <div class="form-group row fom-row">
                <label class="col-form-label col-md-3" for="customerId_displayField">Харилцагч:</label>
                <div class="col-md-8">
                    <div class="meta-autocomplete-wrap" data-section-path="customerId">
                        <div class="input-group double-between-input">
                            <input type="hidden" name="customerId" id="customerId_valueField" data-path="customerId" class="popupInit" value="<?php echo Arr::get($this->row, 'bankaccountid'); ?>">
                            <input type="text" name="customerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="customerId" id="customerId_displayField" data-processid="1454315883636" data-lookupid="1522946988985" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" value="<?php echo Arr::get($this->row, 'bankaccountcode'); ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('customerId', '1454315883636', '1522946988985', 'single', 'customerId', this, 'returnRowData');" tabindex="-1"><i class="fa fa-search"></i></button>
                            </span>  
                            <span class="input-group-btn">
                                <input type="text" name="customerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="customerId" id="customerId_nameField" data-processid="1454315883636" data-lookupid="1522946988985" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" value="<?php echo Arr::get($this->row, 'bankaccountname'); ?>">
                            </span>   
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="recipientLastName"><span class="required">*</span><?php echo $this->lang->line('Хүлээн авагчийн овог'); ?>:</label>
            <div class="col-md-8">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'recipientLastName', 
                        'id' => 'recipientLastName', 
                        'class' => 'form-control posAddressField', 
                        'required' => 'required', 
                        'data-inputmask-regex' => '^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ| -]{1,60}$', 
                        'placeholder' => $this->lang->line('Хүлээн авагчийн овог'), 
                        'value' => Arr::get($this->row, 'deliverycontactlastname')
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="recipientName"><span class="required">*</span><?php echo $this->lang->line('POS_0171'); ?>:</label>
            <div class="col-md-8">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'recipientName', 
                        'id' => 'recipientName', 
                        'class' => 'form-control posAddressField', 
                        'required' => 'required', 
                        'data-inputmask-regex' => '^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ| -]{1,60}$', 
                        'placeholder' => $this->lang->line('POS_0171'), 
                        'value' => Arr::get($this->row, 'deliverycontactname')
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="recipientRegisterNum"><span class="required">*</span><?php echo $this->lang->line('Хүлээн авагчийн регистр'); ?>:</label>
            <div class="col-md-4">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'recipientRegisterNum', 
                        'id' => 'recipientRegisterNum', 
                        'class' => 'form-control posAddressField', 
                        'required' => 'required', 
                        'placeholder' => $this->lang->line('Хүлээн авагчийн регистр'), 
                        'value' => Arr::get($this->row, 'deliveryregisternum')
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label panel-title col-md-3" for="coordinate">Google map:</label>
            <div class="input-group gmap-set-coordinate-control col-md-8">
                <input type="text" name="coordinate" id="coordinate" class="form-control form-control-sm coordinateInit" readonly="1"/>
                <span class="input-group-btn">
                    <button onclick="setGMapCoordinate(this); return false;" placeholder="Coordinate" class="btn btn-sm blue mr0 pt3 pb0"><i class="fa fa-map-marker"></i></button>
                </span>
            </div>
        </div>        
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="what3words">What3words:</label>
            <div class="col-md-8">
                <input type="text" name="what3words" id="what3words" class="form-control posAddressField" placeholder="What3words" autocomplete="off">
            </div>
        </div>        
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="cityId"><?php echo $this->lang->line('POS_0172'); ?>:</label>
            <div class="col-md-4">
                <select name="cityId" id="cityId" data-path="cityId" class="form-control form-control-sm select2 posAddressField" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1446632274202&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;cityId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                    <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                </select>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="districtId"><?php echo $this->lang->line('POS_0173'); ?>:</label>
            <div class="col-md-4">
                <select name="districtId" id="districtId" data-path="districtId" disabled="disabled" data-criteria-param="cityId@cityId" class="form-control form-control-sm select2 posAddressField" data-row-data="{&quot;META_DATA_ID&quot;:&quot;144436175673444&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;districtId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                    <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                </select>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="districtId"><?php echo $this->lang->line('POS_0174'); ?>:</label>
            <div class="col-md-4">
                <select name="streetId" id="streetId" data-path="streetId" disabled="disabled" data-criteria-param="districtId@districtId" class="form-control form-control-sm select2 posAddressField" data-row-data="{&quot;META_DATA_ID&quot;:&quot;144436196690182&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;streetId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                    <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                </select>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="districtId"><?php echo $this->lang->line('POS_0175'); ?>:</label>
            <div class="col-md-8">
                <?php 
                echo Form::textArea(
                    array(
                        'name' => 'detailAddress', 
                        'id' => 'detailAddress', 
                        'class' => 'form-control posAddressField', 
                        'placeholder' => $this->lang->line('POS_0175'), 
                        'rows' => 4, 
                        'value' => Arr::get($this->row, 'detailaddress')
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="dueDate"><?php echo $this->lang->line('POS_0209'); ?>:</label>
            <div class="col-md-4">
                <?php 
                if (isset($this->row['duedate'])) {
                    $dueDate = Date::formatter($this->row['duedate'], 'Y-m-d H:i');
                } else {
                    $dueDate = Date::currentDate('Y-m-d H:i');
                }
                echo Form::text(
                    array(
                        'name' => 'dueDate', 
                        'id' => 'dueDate', 
                        'class' => 'form-control dateminuteInit posAddressField', 
                        'placeholder' => 'Хүргэх огноо', 
                        'value' => $dueDate
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-3" for="description"><?php echo $this->lang->line('POS_0129'); ?>:</label>
            <div class="col-md-8">
                <?php 
                echo Form::textArea(
                    array(
                        'name' => 'description', 
                        'id' => 'description', 
                        'class' => 'form-control posAddressField', 
                        'placeholder' => $this->lang->line('POS_0129'), 
                        'rows' => 4, 
                        'value' => Arr::get($this->row, 'description')
                    )
                ); 
                ?>
            </div>
        </div>

    </div>
</div>
<input type="hidden" name="payAmount" value="<?php echo $this->payAmount; ?>">
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function(){
    $('input[name="orderTypeIdTemp"]').on('click', function(){
        var orderTypeId = $(this).val();
        
        $('input[name="orderTypeId"]').val(orderTypeId);
        if (orderTypeId == '91') {
            $('#orderInvoicePart').show();
            $('.pos-order-save-print').removeClass('hide');
            $('#invoiceTypeIdForm').attr('required', 'required').select2();
        } else {
            $('#orderInvoicePart').hide();
            $('.pos-order-save-print').addClass('hide');
            $('#invoiceTypeIdForm').removeAttr('required aria-required').removeClass('error');
        }
    });

    if ($('#invoiceTypeId').length) {
        if ($('#invoiceTypeId').val()) {
            $('#invoiceTypeIdForm').val($('#invoiceTypeId').val());
            $('#invoiceTypeIdForm').select2('disable'); 
            $('input[name="orderTypeIdTemp"]').eq(1).trigger('click');            
            $('input[name="invoiceTypeIdForm"]').remove();
            $('#invoiceTypeIdForm').parent().append('<input type="hidden" name="invoiceTypeIdForm" value="'+$('#invoiceTypeId').val()+'">');
        } else {
            $('#invoiceTypeIdForm').val('');
            $('#invoiceTypeIdForm').select2('enable');
            $('input[name="invoiceTypeIdForm"]').remove();
        }
        $('input[name="orderTypeIdTemp"]').prop('disabled', true);
    }
    
    $('#phoneNumber').on('keydown', function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which), 
            $this = $(this), 
            thisVal = ($this.val()).trim();
        
        if (keyCode == 13 && thisVal != '') {
            
            $.ajax({
                type: 'post',
                url: 'mdpos/getAddressInfoByPhone',
                data: {phoneNumber: thisVal}, 
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {

                    if (data.hasOwnProperty('cityid')) {

                        $('#recipientName').val(data.contactname);
                        $('#invoiceTypeIdForm').select2('val', data.invoicetypeid);
                        
                        if (data.bankaccountid != null) {
                            setLookupPopupValue('bankAccountId', data.bankaccountid);
                        }
                        
                        if (data.cityid != null) {
                            $('select[name="cityId"]').trigger('select2-opening', [true]);
                            $('select[name="cityId"]').select2('val', data.cityid);

                            var $districtId = $('select#districtId');
                            $districtId.select2('enable');
                            $districtId.removeClass('data-combo-set');
                        }

                        if (data.districtid != null) {
                            $('select[name="districtId"]').trigger('select2-opening', [true]);
                            $('select[name="districtId"]').select2('val', data.districtid);

                            var $streetId = $('select#streetId');
                            $streetId.select2('enable');
                            $streetId.removeClass('data-combo-set');
                        }

                        if (data.citystreetid != null) {
                            $('select[name="streetId"]').trigger('select2-opening', [true]);
                            $('select[name="streetId"]').select2('val', data.citystreetid);
                        }
                        
                        $('#detailAddress').val(data.address);
                        $('#description').val(data.description);
                    }

                    Core.unblockUI();
                }
            });
        }
    });
    
    <?php
    if (Input::post('isInv') == '1') { ?>
        $('input[name="orderTypeIdTemp"]').last().trigger('click');
    <?php }
    
    if ($this->row) {
    ?>
    var $orderDialog = $('#dialog-pos-order');    
    Core.initSelect2($orderDialog);        
    $('input[name="orderTypeIdTemp"][value=<?php echo Arr::get($this->row, 'booktypeid'); ?>]').prop('checked', 'checked').click();
    <?php
    if (isset($this->row['cityid'])) {
    ?>
        $('select[name="cityId"]').trigger('select2-opening', [true]);
        $('select[name="cityId"]').select2('val', '<?php echo $this->row['cityid']; ?>');

        var $districtId = $('select#districtId');
        $districtId.select2('enable');
        $districtId.removeClass('data-combo-set');
    <?php
    }
    if (isset($this->row['districtid'])) {
    ?>
        $('select[name="districtId"]').trigger('select2-opening', [true]);
        $('select[name="districtId"]').select2('val', '<?php echo $this->row['districtid']; ?>');

        var $streetId = $('select#streetId');
        $streetId.select2('enable');
        $streetId.removeClass('data-combo-set');
    <?php
    } 
    if (isset($this->row['streetid'])) {
    ?>
        $('select[name="streetId"]').trigger('select2-opening', [true]);
        $('select[name="streetId"]').select2('val', '<?php echo $this->row['streetid']; ?>');
    <?php
    }
    }
    ?>
    
    $('#recipientLastName, #recipientName').tooltip({'trigger':'focus', 'title': 'Кирил үсгээр бичнэ үү'});
});  

function returnRowData(  
    metaDataCode,
    processMetaDataId,
    chooseType,
    elem,
    rows,
    paramRealPath,
    lookupMetaDataId,
    isMetaGroup) {
        var row = rows[0];
        $("#recipientLastName").val(row.lastname);
        $("#recipientName").val(row.customername);
        $("#recipientRegisterNum").val(row.positionname);
        $("#customerId_valueField").val(row.id); 
        $("#customerId_displayField").val(row.customercode); 
        $("#customerId_nameField").val(row.customername); 
}
</script>    