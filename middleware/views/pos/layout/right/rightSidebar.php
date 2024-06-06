<table class="pos-right-inside">
    <tbody>
        <tr>
            <td class="text-left pos-amount-total-label" style="width: 120px"><?php echo $this->lang->line('POS_0160'); ?>:</td>
            <td class="text-right pos-amount-total">0</td>
        </tr>
        <tr>
            <td class="text-left">НӨАТ:</td>
            <td class="text-right pos-amount pos-amount-vat">0</td>
        </tr>
        <tr>
            <td class="text-left"><?php echo $this->lang->line('POS_0082'); ?>:</td>
            <td class="text-right pos-amount pos-amount-citytax">0</td>
        </tr>
        <tr>
            <td class="text-left">Хөнгөлөлт:</td>
            <td class="text-right pos-amount pos-amount-discount">0</td>
        </tr>
        <tr>
            <td class="text-left">Бонус:</td>
            <td class="text-right pos-amount pos-amount-bonus">0</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 20px;"></td>
        </tr>
        <tr>
            <td class="text-left pos-amount-paid-label">Төлөх дүн:</td>
            <td class="text-right pos-amount-paid">0</td>
        </tr>
        <tr style="display: none">
            <td class="text-right pos-amount-change-label"><?php echo $this->lang->line('POS_0096'); ?>:</td>
            <td class="text-right pos-amount-change">0</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 40px;"></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="button" class="btn btn-block btn-circle btn-lg green-meadow" onclick="posPayment();">
                    <i class="fa fa-money"></i> ТӨЛБӨР ТӨЛӨХ (F5)
                </button>
            </td>
        </tr>
    </tbody>
</table>

<div class="pos-right-inside-help">
    <button type="button" class="btn btn-block btn-circle btn-sm blue-hoki" onclick="posTestBillPrint();"><i class="fa fa-reorder"></i> <?php echo $this->lang->line('POS_0046'); ?> (Shift+F3)</button>
    <button type="button" class="btn btn-block btn-circle btn-sm grey-cascade" onclick="posTestBillPrint();"><i class="fa fa-print"></i> <?php echo $this->lang->line('POS_0145'); ?> (F2)</button>
</div>

<div class="pos-preview-print display-none"></div>