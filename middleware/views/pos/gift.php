<?php
if (issetParam($this->rowData['tablerulelist'])) {
?>
<strong>RULE LIST</strong><br />
<table class="table table-striped pos-rule-list">
    <tbody>
        
        <?php
        foreach ($this->rowData['tablerulelist'] as $rule) {
            if (isset($rule['tablerulepolicylist'])) {
        ?>
        <tr>
            <td>
                <button type="button" class="btn btn-xs grey-cascade" onclick="posGiftRowToggle(this);" data-toggle-status="opened"><i class="fa fa-chevron-down"></i></button>
                <?php echo $rule['rulename']; ?>
            </td>
        </tr>
        <tr>
            <td>

                <table class="table table-sm table-bordered tablerulepolicylist" data-tablerulepolicylist="true">
                    <tbody>
                        <?php
                        foreach ($rule['tablerulepolicylist'] as $rulePolicyList) {
                        ?>
                        <tr data-policy-price="<?php echo $rulePolicyList['namedprice']; ?>" data-policy-id="<?php echo $rulePolicyList['policyid']; ?>">
                            <td style="font-weight: 700"><?php echo $rulePolicyList['policyname']; ?></td>
                        </tr>
                        
                            <?php
                            if (isset($rulePolicyList['tabletrulepolicypackagelist'])) {
                            ?>
                            <tr data-policy-id="<?php echo $rulePolicyList['policyid']; ?>">
                                <td class="pl15 pt10 pb10">

                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 20px"></th>
                                                <th style="text-align: center; font-weight: 600"><?php echo $this->lang->line('POS_0037'); ?></th>
                                                <th style="width: 160px; text-align: right; font-weight: 600"><?php echo $this->lang->line('POS_0038'); ?></th>
                                                <th style="width: 60px; text-align: right; font-weight: 600">V/num</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($rulePolicyList['tabletrulepolicypackagelist'] as $packageList) {

                                                $itemName       = $packageList['itemname'];
                                                
                                                $amount         = $packageList['couponamount'];
                                                $percent        = issetParam($packageList['couponpercent']);
                                                
                                                $couponTypeName = $packageList['coupontypename'];
                                                $itemPrice      = $packageList['saleprice'];
                                                
                                                if (!$amount && $percent > 0 && $rulePolicyList['namedprice'] && $rulePolicyList['namedprice'] > 0) {
                                                    $amount = number_format(($percent / 100) * $rulePolicyList['namedprice'], 2, '.', '');
                                                    $packageList['couponpercentamount'] = $amount;
                                                }

                                                if ($couponTypeName) {
                                                    
                                                    $itemName = $couponTypeName;
                                                    $amount   = Number::amount($amount);
                                                    
                                                } elseif ($packageList['jobname']) {
                                                    
                                                    $itemName = $packageList['jobname'];
                                                }
                                                
                                                if ($itemPrice > 0 && ($packageList['discountamount'] > 0 || $packageList['discountpercent'] > 0)) {
                                                    
                                                    $discountAmount = $itemPrice;
                                
                                                    if ($packageList['discountamount'] > 0) {

                                                        $discountAmount = $packageList['discountamount'];

                                                    } elseif ($packageList['discountpercent'] > 0) {

                                                        $discount = ($packageList['discountpercent'] / 100) * $itemPrice;
                                                        $discountAmount = $itemPrice - $discount;
                                                    }
                                
                                                    $itemName .= ' ('.$this->lang->line('POS_0141').': '.Number::amount($itemPrice).' '.$this->lang->line('POS_0085').': '.Number::amount($discountAmount).')';
                                                    $itemPrice = $itemPrice - $discountAmount;
                                                    $amount = Number::amount($itemPrice);
                                                    
                                                } else {
                                                    $itemPrice = '0';
                                                }

                                                $packageListJson = htmlentities(json_encode($packageList), ENT_QUOTES, 'UTF-8');
                                                $packageChecked = '';
                                                
                                                if (isset($this->packageSelected[$packageList['packagedtlid'].'_'.$packageList['policyid']])) {
                                                    $packageChecked = ' checked="checked"';
                                                    $this->rowJson .= $packageListJson.', ';
                                                }
                                            ?>
                                            <tr data-v-num="<?php echo $packageList['versionnumber']; ?>" data-coupon-type="<?php echo $packageList['coupontypeid']; ?>" data-is-service="<?php echo $packageList['isservice']; ?>" data-gift-price="<?php echo $itemPrice; ?>">
                                                <td class="text-center">
                                                    <input type="hidden" name="posRulePolicyJson[]" value="<?php echo $packageListJson; ?>">
                                                    <input type="checkbox" name="posRulePolicyCheckBox[]" value="1" class="pos-gift-item" id="onlyRule-<?php echo $rule['ruleid'].'-'.$rulePolicyList['policyid'].'-'.$packageList['packagedtlid']; ?>"<?php echo $packageChecked; ?>>
                                                </td>
                                                <td data-gift-name="true">
                                                    <label for="onlyRule-<?php echo $rule['ruleid'].'-'.$rulePolicyList['policyid'].'-'.$packageList['packagedtlid']; ?>">
                                                        <?php echo $itemName; ?>
                                                    </label>    
                                                </td>
                                                <td data-gift-amount="true" style="text-align: right"><?php echo $amount; ?></td>
                                                <td style="text-align: right"><?php echo $packageList['versionnumber']; ?></td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>    
                                    </table>        
                                </td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>
<?php
}

if (issetParam($this->rowData['tablepolicylist'])) {
    $countPolicyList = count($this->rowData['tablepolicylist']);
?>
<strong>POLICY LIST</strong><br />
<table class="table table-sm table-bordered" data-single-policy-count="<?php echo $countPolicyList; ?>">
    <tbody>
        <?php
        foreach ($this->rowData['tablepolicylist'] as $rulePolicyList) {
        ?>
        <tr data-single-policy-price="<?php echo $rulePolicyList['namedprice']; ?>" data-single-policy-id="<?php echo $rulePolicyList['policyid']; ?>">
            <td style="font-weight: 700">
                <div class="d-flex justify-content-between">
                    <div>
                        <?php 
                        if ($countPolicyList > 1) {
                        ?>
                        <label class="bold"><input type="checkbox" class="single-policy-price-checkbox"> <?php echo $rulePolicyList['policyname']; ?></label>
                        <?php
                        } else {
                            echo $rulePolicyList['policyname']; 
                        }
                        ?>
                    </div>
                    <div>
                        <?php echo Number::formatMoney($rulePolicyList['namedprice']); ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php
        if (isset($rulePolicyList['tablepolicypackagelist'])) {
        ?>
        <tr data-single-policy-id="<?php echo $rulePolicyList['policyid']; ?>" data-single-policy-qty="<?php echo empty($rulePolicyList['discountqty']) ? 10000000 : $rulePolicyList['discountqty']; ?>">
            <td class="pl15 pt10 pb10">
                <table class="table table-sm table-hover pos-policy-list">
                    <thead>
                        <tr>
                            <th style="width: 20px"></th>
                            <th style="text-align: center; font-weight: 600"><?php echo $this->lang->line('POS_0037'); ?></th>
                            <th style="width: 160px; font-weight: 600; text-align: right"><?php echo $this->lang->line('POS_0038'); ?></th>
                            <th style="width: 60px; font-weight: 600; text-align: right">V/num</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rulePolicyList['tablepolicypackagelist'] as $packageList) {

                            $itemName       = $packageList['itemname'];
                            
                            $amount         = $packageList['couponamount'];
                            $percent        = issetParam($packageList['couponpercent']);
                            
                            $couponTypeName = $packageList['coupontypename'];
                            $itemPrice      = $packageList['saleprice'];
                            
                            if (!$amount && $percent > 0 && $rulePolicyList['namedprice'] && $rulePolicyList['namedprice'] > 0) {
                                $amount = number_format(($percent / 100) * $rulePolicyList['namedprice'], 2, '.', '');
                                $packageList['couponpercentamount'] = $amount;
                            }

                            if ($couponTypeName) {
                                
                                $itemName = $couponTypeName;
                                $amount = Number::amount($amount);
                                
                            } elseif ($packageList['jobname']) {
                                
                                $itemName = $packageList['jobname'];
                            }
                            
                            if ($itemPrice > 0 && ($packageList['discountamount'] > 0 || $packageList['discountpercent'] > 0)) {
                                
                                $discountAmount = $itemPrice;
                                
                                if ($packageList['discountamount'] > 0) {
                                    
                                    $discountAmount = $packageList['discountamount'];
                                    
                                } elseif ($packageList['discountpercent'] > 0) {
                                    
                                    $discount = ($packageList['discountpercent'] / 100) * $itemPrice;
                                    $discountAmount = $discount;
                                }
                                
                                $itemName .= ' ('.$this->lang->line('POS_0141').': '.Number::amount($itemPrice).' '.$this->lang->line('POS_0085').': '.Number::amount($discountAmount).')';
                                $itemPrice = $itemPrice - $discountAmount;
                                $amount = Number::amount($itemPrice);
                                
                            } else {
                                $itemPrice = '0';
                            }

                            $packageListJson = htmlentities(json_encode($packageList), ENT_QUOTES, 'UTF-8');
                            $packageChecked = '';
                                                
                            if (isset($this->packageSelected[$packageList['packagedtlid'].'_'.$packageList['policyid']])) {
                                $packageChecked = ' checked="checked"';
                                $this->rowJson .= $packageListJson.', ';
                            }
                        ?>
                        <tr data-v-num="<?php echo $packageList['versionnumber']; ?>" data-coupon-type="<?php echo $packageList['coupontypeid']; ?>" data-is-service="<?php echo $packageList['isservice']; ?>" data-gift-price="<?php echo $itemPrice; ?>">
                            <td class="text-center">
                                <input type="hidden" name="posPolicyJson[]" value="<?php echo $packageListJson; ?>">
                                <input type="checkbox" name="posPolicyCheckBox[]" value="1" class="pos-gift-item" id="onlyPolicy-<?php echo $rulePolicyList['policyid'].'-'.$packageList['packagedtlid']; ?>"<?php echo $packageChecked; ?>>
                            </td>
                            <td data-gift-name="true">
                                <label for="onlyPolicy-<?php echo $rulePolicyList['policyid'].'-'.$packageList['packagedtlid']; ?>"><?php echo $itemName; ?></label>    
                            </td>
                            <td data-gift-amount="true" style="text-align: right"><?php echo $amount; ?></td>
                            <td style="text-align: right"><?php echo $packageList['versionnumber']; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>    
                </table>        
            </td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>
<?php
}

if (issetParam($this->rowData['tablepolicypackagelist'])) {
?>
<strong>MATRIX POLICY LIST</strong><br />
<table class="table table-sm table-hover pos-policy-list">
    <thead>
        <tr>
            <th style="width: 20px"></th>
            <th style="text-align: center; font-weight: 600"><?php echo $this->lang->line('POS_0037'); ?></th>
            <th style="width: 160px; font-weight: 600; text-align: right"><?php echo $this->lang->line('POS_0038'); ?></th>
            <th style="width: 60px; font-weight: 600; text-align: right">V/num</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->rowData['tablepolicypackagelist'] as $packageList) {

            $itemName       = $packageList['itemname'];
            
            $amount         = $packageList['couponamount'];
            $percent        = issetParam($packageList['couponpercent']);
            
            $couponTypeName = $packageList['coupontypename'];
            $itemPrice = '0';

            if ($couponTypeName) {
                
                $itemName = $couponTypeName;
                $amount = Number::amount($amount);
                
            } elseif ($packageList['jobname']) {
                
                $itemName = $packageList['jobname'];
            }

            $packageListJson = htmlentities(json_encode($packageList), ENT_QUOTES, 'UTF-8');
            $packageChecked = '';
                                
            if (isset($this->packageSelected[$packageList['packagedtlid']])) {
                $packageChecked = ' checked="checked"';
                $this->rowJson .= $packageListJson.', ';
            }
        ?>
        <tr data-v-num="<?php echo $packageList['versionnumber']; ?>" data-coupon-type="<?php echo $packageList['coupontypeid']; ?>" data-is-service="<?php echo $packageList['isservice']; ?>" data-gift-price="<?php echo $itemPrice; ?>">
            <td class="text-center">
                <input type="hidden" name="posPolicyJson[]" value="<?php echo $packageListJson; ?>">
                <input type="checkbox" name="posPolicyCheckBox[]" value="1" class="pos-gift-item" id="onlyPolicy-<?php echo $packageList['packagedtlid']; ?>"<?php echo $packageChecked; ?>>
            </td>
            <td data-gift-name="true">
                <label for="onlyPolicy-<?php echo $packageList['packagedtlid']; ?>"><?php echo $itemName; ?></label>    
            </td>
            <td data-gift-amount="true" style="text-align: right"><?php echo $amount; ?></td>
            <td style="text-align: right"><?php echo $packageList['versionnumber']; ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>    
</table>
<?php
}

if (issetParam($this->rowData['bundledtl'])) {
    $countPolicyList = count($this->rowData['bundledtl']);
?>
<strong>BUNDLE LIST</strong><br />
<table class="table table-sm table-bordered" data-single-policy-count="<?php echo $countPolicyList; ?>">
    <tbody>
        <?php
        foreach ($this->rowData['bundledtl'] as $rulePolicyList) {
        ?>
        <tr data-single-policy-discountamount="<?php echo $rulePolicyList['discountamount']; ?>" data-single-policy-id="<?php echo $rulePolicyList['policyid']; ?>">
            <td style="font-weight: 700">
                <label class="bold"><input type="radio" <?php echo $countPolicyList == 1 ? "checked" : "" ?> class="single-bundle-price-checkbox"> <?php echo $rulePolicyList['policyname']; ?></label>
            </td>
        </tr>
        <?php
        if (isset($rulePolicyList['policydtl'])) {
        ?>
        <tr data-single-child-policy-id="<?php echo $rulePolicyList['policyid']; ?>" data-single-policy-qty="<?php echo empty($rulePolicyList['discountqty']) ? 10000000 : $rulePolicyList['discountqty']; ?>">
            <td class="pl15 pt10 pb10">
                <table class="table table-sm table-hover pos-policy-list">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-weight: 600;">Багц</th>
                            <th style="width: 160px; font-weight: 600; text-align: right"><?php echo $this->lang->line('POS_0038'); ?></th>
                            <!-- <th style="width: 60px; font-weight: 600; text-align: right">V/num</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rulePolicyList['policydtl'] as $packageList) {

                            $itemName       = $packageList['itemname'];
                            
                            $amount         = 0;
                            $percent        = 0;
                            
                            $couponTypeName = "";
                            $itemPrice      = $packageList['saleprice'];

                            $packageListJson = htmlentities(json_encode($packageList), ENT_QUOTES, 'UTF-8');
                            $packageChecked = '';
                                                
                            // if (isset($this->packageSelected[$packageList['packagedtlid'].'_'.$packageList['policyid']])) {
                            //     $packageChecked = ' checked="checked"';
                            //     $this->rowJson .= $packageListJson.', ';
                            // }
                        ?>
                        <tr data-v-num="<?php echo $packageList['versionnumber']; ?>" data-gift-price="<?php echo $itemPrice; ?>">
                            <td data-gift-name="true">
                                <input type="hidden" name="posPolicyJson[]" value="<?php echo $packageListJson; ?>">
                                <label for="onlyPolicy-<?php echo $rulePolicyList['policyid']; ?>"><?php echo $itemName; ?></label>    
                            </td>
                            <td data-gift-amount="true" style="text-align: right"><?php echo $itemPrice; ?></td>
                            <!-- <td style="text-align: right"><?php echo $packageList['versionnumber']; ?></td> -->
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>    
                </table>        
            </td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>
<?php
}
?>