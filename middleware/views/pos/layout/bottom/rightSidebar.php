<table style="width: 100%" id="pos-bottom-bar">
    <tbody>
        <tr>
            <td style="width: 70%; background-color: #eee; padding: 10px 15px 10px 10px; vertical-align: top">
                
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td style="width: 15%; padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('POS_0160'); ?>:
                            </td>
                            <td style="width: 35%; padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-total">
                                0
                            </td>
                            <td style="width: 15%; padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('FIN_VAT'); ?>:
                            </td>
                            <td style="width: 35%; padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-vat">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('discount'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-discount">
                                0
                            </td>
                            <td style="padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('FIN_QUANTITY'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-total-qty">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('POS_0082'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-citytax">
                                0
                            </td>
                            <td style="padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('POS_0096'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-change">
                                0 
                            </td>
                        </tr>
                        <tr data-config-column="unitreceivable">
                            <td style="padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('POS_0095'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-receivable">
                                0
                            </td>
                            <td style="padding: 2px 5px;" class="pos-bottom-label">
                                <?php echo $this->lang->line('POS_0169'); ?>:
                            </td>
                            <td style="padding: 2px 5px;" class="text-right bigdecimalInit pos-amount-receivable-from-person">
                                0
                            </td>
                        </tr>
                        <?php if (Config::getFromCache('CONFIG_POS_PRINT_TYPE') === 'intermed') { ?>
                            <tr>
                                <td style="padding: 2px 5px;" class="pos-bottom-label">
                                    ЭМД:
                                </td>
                                <td style="padding: 2px 5px;" class="text-right bigdecimalInit">
                                    0
                                </td>
                                <td style="padding: 2px 5px;" class="pos-bottom-label">                                
                                </td>
                                <td style="padding: 2px 5px;" class="">
                                </td>
                            </tr>       
                        <?php } ?>
                        <tr>
                            <td style="padding: 2px 5px; color: red; font-size: 14px;" class="pos-footer-msg" colspan="4"></td>
                        </tr>
                    </tbody>
                </table> 
                
            </td>
            <td style="width: 30%; background-color: #eee; border-left: 1px solid #ddd; vertical-align: top; padding: 10px 30px 30px 30px;">
                
                <table style="width: 100%; table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td style="padding: 0; text-align: left; font-size: 20px; width: 148px;" class="pos-amount-paid-label">
                                <?php echo $this->lang->line('MET_331392'); ?>:
                            </td>
                            <td style="padding: 0; text-align: right; font-size: 25px; width: 100%" class="bigdecimalInit pos-amount-paid">
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