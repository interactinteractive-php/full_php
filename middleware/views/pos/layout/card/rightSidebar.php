<table style="width: 100%" id="pos-card-bar">
    <tbody>
        <tr>
            <td style="width: 100%; background-color: #FAFAFA; border-top: 1px solid #E6E6E6; padding: 10px 15px 0px 10px; vertical-align: top">
                
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td style="width: 15%; padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0160'); ?>:
                            </td>
                            <td style="width: 35%; padding: 5px 5px;" class="text-right bigdecimalInit pos-amount-total">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 15%; padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('FIN_VAT'); ?>:
                            </td>
                            <td style="width: 35%; padding: 5px 5px;" class="text-right bigdecimalInit pos-amount-vat">
                                0
                            </td>                            
                        </tr>
                        <tr>
                            <td style="padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('discount'); ?>:
                            </td>
                            <td style="padding: 5px 5px;" class="text-right bigdecimalInit pos-amount-discount">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('FIN_QUANTITY'); ?>:
                            </td>
                            <td style="padding: 5px 5px;" class="text-right bigdecimalInit pos-total-qty">
                                0
                            </td>                            
                        </tr>
                        <tr>
                            <td style="padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0082'); ?>:
                            </td>
                            <td style="padding: 5px 5px;" class="text-right bigdecimalInit pos-amount-citytax">
                                0
                            </td>
                        </tr>
                        <tr>
                           <td style="padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0096'); ?>:
                            </td>
                            <td style="padding: 5px 5px;" class="text-right bigdecimalInit pos-amount-change">
                                0 
                            </td>                            
                        </tr>
                        <tr data-config-column="unitreceivable">
                            <td style="padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0095'); ?>:
                            </td>
                            <td style="padding: 5px 5px;" class="text-right bigdecimalInit pos-amount-receivable">
                                0
                            </td>
                        </tr>
                        <tr data-config-column="unitreceivable">
                            <td style="padding: 5px 5px;" class="pos-card-label">
                                <?php echo $this->lang->line('POS_0169'); ?>:
                            </td>
                            <td style="padding: 5px 5px;" class="text-right bigdecimalInit pos-amount-receivable-from-person">
                                0
                            </td>                            
                        </tr>
                        <tr>
                            <td style="padding: 4px 4px; color: red; font-size: 14px;" class="pos-footer-msg" colspan="4"></td>
                        </tr>
                    </tbody>
                </table> 
                
            </td>
        </tr>
        <tr>
            <td style="width: 100%; background-color: #FAFAFA; vertical-align: top; padding: 0px 18px 10px 14px;">
                
                <table style="width: 100%; table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td style="padding: 0; text-align: left; font-size: <?php echo $this->isIpad ? 16 : 20; ?>px; width: 148px;" class="pos-amount-paid-label">
                                <?php echo $this->lang->line('MET_331392'); ?>:
                            </td>
                            <td style="padding: 0; text-align: right; font-size: <?php echo $this->isIpad ? 18 : 25; ?>px; width: 100%" class="bigdecimalInit pos-amount-paid">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 0 0 0; text-align: center" colspan="2">
                                <?php if ($this->isBasketOnly) { ?>
                                    <button type="button" class="btn btn-block btn-circle btn-lg green-meadow uppercase" onclick="posNoPayment();">
                                        <i class="fa fa-money"></i> <?php echo $this->lang->line('POS_0499'); ?> (F5)
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-block btn-circle btn-lg green-meadow uppercase" onclick="posPayment();">
                                        <i class="fa fa-money"></i> <?php echo $this->lang->line('POS_0060'); ?> (F5)
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table> 
                
            </td>
        </tr>
    </tbody>
</table>

<div class="pos-preview-print display-none"></div>