<table class="pos-leftsidebar" style="margin-top: -20px;">
    <tbody>
        <tr style="height: 1px">
            <td style="width: 65px; height: 1px; font-size: 2px"></td>
            <td style="height: 1px; font-size: 2px"></td>
        </tr>
        <tr>
            <td colspan="2" class="title"><?php echo $this->lang->line('POS_0052'); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-left pb0">Бараа сонгох:</td>
        </tr>
        <tr>
            <td colspan="2" class="pos-item-combogrid-cell">
                <input type="text" id="scanItemCode" class="form-control form-control-sm ignorebarcode" placeholder="<?php echo $this->lang->line('POS_0003'); ?>" style="width: 227px">
            </td>
        </tr>
        <tr>
            <td class="text-right">Код:</td>
            <td data-field-name="detail-code"></td>
        </tr>
        <tr>
            <td class="text-right">Нэр:</td>
            <td data-field-name="detail-name"></td>
        </tr>
        <tr>
            <td class="text-right">Х.нэгж:</td>
            <td data-field-name="detail-measure"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0007'); ?>:</td>
            <td data-field-name="detail-saleprice"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0162'); ?>:</td>
            <td data-field-name="detail-vatprice"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0163'); ?>:</td>
            <td data-field-name="detail-novatprice"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0161'); ?>:</td>
            <td data-field-name="detail-salesperson"></td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0131'); ?>:</td>
            <td>
                <input type="text" class="pos-discount-input bigdecimalInit" id="pos-discount-percent" readonly="readonly" data-inputmask-regex="^100(\.(0){0,2})?$|^([1-9]?[0-9])(\.(\d{0,2}))?\%$">
            </td>
        </tr>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0132'); ?>:</td>
            <td>
                <input type="text" class="pos-discount-input bigdecimalInit" id="pos-discount-amount" readonly="readonly">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-left pb0">Үйлчилгээ сонгох:</td>
        </tr>
        <tr>
            <td colspan="2" class="pos-service-combogrid-cell">
                <input type="text" id="posServiceCode" class="form-control form-control-sm ignorebarcode" placeholder="<?php echo $this->lang->line('POS_0043'); ?>" style="width: 227px">
            </td>
        </tr>
        <!--<tr>
            <td colspan="2" class="title">Тасгийн мэдээлэл</td>
        </tr>
        <tr>
            <td class="text-right">Тасаг:</td>
            <td></td>
        </tr>!-->
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
        <input type="hidden" id="invoiceId">
    </div>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posInvoiceList(this);" data-criteria="storeId=<?php echo Session::get(SESSION_PREFIX.'storeId'); ?>">
        <?php echo $this->lang->line('POS_0147'); ?> (F3)
    </button>
    <!--<button type="button" class="btn btn-block btn-circle blue-madison btn-sm">
        ИНТЕРНЭТ
    </button>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm">
        ДАРАА ТООЦОО
    </button>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm">
        БАРТЕР
    </button>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm">
        ЛИЗИНГ
    </button>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm">
        АЖИЛЛАГСАДЫН ЗЭЭЛ
    </button>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm">
        ХЯМДРАЛ
    </button>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm">
        ЭРХИЙН БИЧИГ
    </button>-->
</div>