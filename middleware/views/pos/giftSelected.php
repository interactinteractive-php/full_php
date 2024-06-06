<?php
if ((isset($this->row['pos_item_list_get']) && $this->row['pos_item_list_get']) 
        || (isset($this->row['sdm_sales_order_item_package']) && $this->row['sdm_sales_order_item_package'])) {
    
    $itemList    = $this->row['pos_item_list_get'];
    $packageList = $this->row['sdm_sales_order_item_package'];
?>
<table style="width: 100%" class="table table-sm table-bordered pos-gift-table">
    <tbody>
        <tr>
            <td style="text-align: center"><?php echo $this->lang->line('POS_0037'); ?></td>
            <td style="width: 115px; text-align: center"><?php echo $this->lang->line('POS_0038'); ?></td>
            <td style="width: 45px; text-align: center"><i class="fa fa-truck" title="<?php echo $this->lang->line('POS_0014'); ?>"></i></td>
            <td style="width: 269px;"></td>
        </tr>
        <?php
        if ($itemList) {
            foreach ($itemList as $item) {

                $itemPrice = $rowAttribute = '';

                if ($item['saleprice'] > 0) {
                    $rowAttribute = ' data-calc-price="'.$item['saleprice'].'"';
                    $itemPrice = Number::amount($item['saleprice']);
                }

                $isDeliveryChecked = '';

                if (isset($item['isdelivery']) && $item['isdelivery'] == 1) {
                    $isDeliveryChecked = ' checked="checked"';
                }
        ?>
        <tr<?php echo $rowAttribute; ?>>
            <td style="text-align: left"><?php echo $item['itemname']; ?></td>
            <td style="text-align: right"><?php echo $itemPrice; ?></td>
            <td style="text-align: center"><input type="checkbox" class="isGiftDelivery" value="1" title="<?php echo $this->lang->line('POS_0014'); ?>"<?php echo $isDeliveryChecked; ?>></td>
            <td></td>
        </tr>
        <?php
            }
        }
        if ($packageList) {
            
            foreach ($packageList as $package) {   
                if ($package['isitem'] != '1') {
                    
                    $percent = issetParamZero($package['couponpercent']);
                    $namedPrice = issetParamZero($package['namedprice']);
                    $amount = $package['couponamount'];
                    
                    if ($percent > 0 && $namedPrice > 0) {
                        $amount = number_format(($percent / 100) * $namedPrice, 2, '.', '');
                    }
        ?>
        <tr>
            <td style="text-align: left"><?php echo $package['coupontypename']; ?></td>
            <td style="text-align: right"><?php echo Number::amount($amount); ?></td>
            <td></td>
            <td></td>
        </tr>
        <?php
                }
            }
        
        }
        ?>
    </tbody>
</table>
<?php
}
?>