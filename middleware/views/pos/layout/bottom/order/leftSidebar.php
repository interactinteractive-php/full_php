<table class="pos-leftsidebar" style="margin-top: -20px;">
    <tbody>
        <tr style="height: 1px">
            <td style="width: 65px; height: 1px; font-size: 2px"></td>
            <td style="height: 1px; font-size: 2px"></td>
        </tr>
        <tr>
            <td colspan="2" class="title"><?php echo $this->lang->line('POS_0052'); ?></td>
        </tr>
        <?php
        if (isset($this->getInvoiceTypeList)) {
        ?>
        <tr>
            <td colspan="2" class="text-left pb0"><?php echo $this->lang->line('POS_0220'); ?>:</td>
        </tr>
        <tr>
            <td colspan="2" class="">
            <?php
                echo Form::select(
                    array(
                        'id' => 'invoiceTypeId',
                        'name' => 'invoiceTypeId',
                        'data' => $this->getInvoiceTypeList,
                        'op_value' => 'id',
                        'op_text' => 'code| |-| |name',
                        'op_custom_attr' => array(array(
                            'attr' => 'data-notcheckqty',
                            'key' => 'isnotcheckendqty'
                        )),                        
                        'class' => 'form-control form-control-sm select2', 
                        'text' => '- '.$this->lang->line('choose_btn').' -',
                    )
                );
            ?>
            </td>
        </tr>
        <?php } ?>        
        <tr>
            <td colspan="2" class="text-left pb0"><?php echo $this->lang->line('POS_0205'); ?>:</td>
        </tr>
        <tr>
            <td colspan="2" class="pos-item-combogrid-cell">
                <input type="text" id="scanItemCode" class="form-control form-control-sm ignorebarcode" placeholder="<?php echo $this->lang->line('POS_0003'); ?>" style="width: 227px">
            </td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('code'); ?>:</td>
            <td data-field-name="detail-code"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('FIN_NAME'); ?>:</td>
            <td data-field-name="detail-name"></td>
        </tr>
        <tr>
            <td class="text-right">Х.нэгж:</td>
            <td data-field-name="detail-measure"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0007'); ?>:</td>
            <td data-field-name="detail-saleprice" class="bigdecimalInit"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0162'); ?>:</td>
            <td data-field-name="detail-vatprice" class="bigdecimalInit"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0163'); ?>:</td>
            <td data-field-name="detail-novatprice" class="bigdecimalInit"></td>
        </tr>
        <?php
        if ($this->isConfigSalesPerson) {
        ?>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0161'); ?>:</td>
            <td data-field-name="detail-salesperson"></td>
        </tr>
        <?php
        }
        if ($this->isConfigRowOrderDiscount) {
        ?>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0069'); ?>:</td>
            <td>
                <button type="button" class="btn btn-xs  mt2" onclick="posCalcItemRowDiscount();" id="posCalcItemRowDiscount" disabled="disabled"><i class="fa fa-calculator"></i> (F6)</button>
                <button type="button" class="btn btn-xs  mt2" onclick="posCalcItemRowDiscountRemove();" id="posCalcItemRowDiscountRemove" disabled="disabled" title="<?php echo $this->lang->line('POS_0165'); ?>"><i class="icon-cross2 font-size-12"></i></button>
            </td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0131'); ?>:</td>
            <td>
                <input type="text" class="pos-discount-input bigdecimalInit" id="pos-discount-percent" readonly="readonly">
            </td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0132'); ?>:</td>
            <td>
                <input type="text" class="pos-discount-input bigdecimalInit" id="pos-discount-amount" readonly="readonly">
            </td>
        </tr>
        <?php
        }
        if ($this->isConfigServiceJob) {
        ?>
        <tr>
            <td colspan="2" class="text-left pb0"><?php echo $this->lang->line('POS_0206'); ?>:</td>
        </tr>
        <tr>
            <td colspan="2" class="pos-service-combogrid-cell">
                <input type="text" id="posServiceCode" class="form-control form-control-sm ignorebarcode" placeholder="<?php echo $this->lang->line('POS_0043'); ?>" style="width: 227px">
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div class="pos-left-inside-help">
    <div class="pos-invoice-number" style="display: none">
        <?php echo $this->lang->line('POS_0168'); ?>:
        <div style="padding: 2px 0 0 0;">
            
            <div class="input-group">
                <input type="text" class="form-control form-control-sm pos-invoice-number-text" readonly="readonly">
                <span class="input-group-btn">
                    <button class="btn btn-sm red" type="button" onclick="posRemoveInvoiceNumber();"><i class="icon-cross2 font-size-12"></i></button>
                </span>
            </div>
            
        </div>
    </div>
    
    <input type="hidden" id="invoiceId">
    
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posElectronInvoiceList(this);" data-criteria="storeId=<?php echo Session::get(SESSION_PREFIX.'storeId'); ?>">
        <?php echo $this->lang->line('POS_0147'); ?> (F3)
    </button>
</div>

<script type="text/javascript">
    var posElectronTalonWindow = true;
    <?php if (Session::get(SESSION_PREFIX.'isEditBasketPrice') === '1') { ?>
        var posIsEditBasketPrice = true;
    <?php } ?>
</script>