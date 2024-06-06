<table style="width: 100%" id="pos-card-bar">
    <tbody>
        <tr>
            <td style="width: 70%; background-color: #eee; padding: 10px 15px 10px 10px; vertical-align: top">
                
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td style="width: 15%; padding: 2px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0160'); ?>:
                            </td>
                            <td style="width: 35%; padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-total">
                                0
                            </td>
                            <td style="width: 15%; padding: 2px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('FIN_VAT'); ?>:
                            </td>
                            <td style="width: 35%; padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-vat">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('FIN_QUANTITY'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-total-qty">
                                0
                            </td>
                            <td style="display: none"></td>
                            <td style="display: none" class="bigdecimalInit pos-amount-bonus">
                                0
                            </td>
                        </tr>
                        <tr data-config-column="unitreceivable">
                            <td style="padding: 2px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0095'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-receivable">
                                0
                            </td>
                            <td style="padding: 2px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0169'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-receivable-from-person">
                                0
                            </td>
                        </tr>
                        <tr data-config-column="unitreceivable">
                            <td style="padding: 2px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('discount'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-discount">
                                0
                            </td>
                            <td style="padding: 2px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0082'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-citytax">
                                0
                            </td>
                        </tr>
                    </tbody>
                </table> 
                
            </td>
            <td style="width: 30%; background-color: #ddd; vertical-align: top; padding: 10px 30px 30px 30px;">
                
                <table style="width: 100%; table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td style="padding: 0; text-align: left; font-size: 19px; width: 110px;" class="pos-amount-paid-label">
                                <?php echo $this->lang->line('MET_331392'); ?>:
                            </td>
                            <td style="padding: 0; text-align: right; font-size: 24px; width: 100%" class="bigdecimalInit pos-amount-paid">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 0 0 0; text-align: center" colspan="2">
                                <button type="button" class="btn btn-block btn-circle btn-lg green-meadow" onclick="posOrderSave();">
                                    <i class="fa fa-shopping-cart"></i> ЗАХИАЛГА ҮҮСГЭХ
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table> 
                
            </td>
        </tr>
    </tbody>
</table>

<div class="pos-preview-print display-none"></div>