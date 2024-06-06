<?php
$mdPos = new Mdpos();
$cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
$prevItem = null;
$trMatrixAttr = 'data-matrix-row="sent-matrix-row-'.getUID().'"';
$addClassName = ' d-none';

if ($this->itemList) {
    foreach ($this->itemList as $itemIndex => $item) {

        $itemName  = trim($item['itemname']);
        $giftArray = $mdPos->giftByItemRowRender($item);
        $item['id'] = isset($item['id']) ? $item['id'] : $item['itemid'];

        $matrixHtml = $matrixBtn = $giftButton = $giftRow = $qtyReadonly = $printCopies = '';

        if (!empty($giftArray['gift'])) {

            $giftButton   = '<button type="button" class="btn btn-xs purple gift-icon" onclick="posChooseItemGiftBtn(this);" title="'.$this->lang->line('POS_0036').'"><i class="fa fa-gift"></i></button>';
            $giftSelected = $mdPos->giftSelectedByItemRowRender($item);

            if ($giftSelected) {
                $giftRow = '<tr data-item-gift-row="true">
                                <td colspan="2"></td>  
                                <td colspan="6" data-item-gift-cell="true">'.$giftSelected.'</td>
                            </tr>';
            } else {
                $giftRow = '<tr data-item-gift-row="true" style="display: none">
                                <td colspan="2"></td>  
                                <td colspan="6" data-item-gift-cell="true"></td>
                            </tr>';
            }
        }

        $unitPrice      = $item['saleprice'];

        $isCityTax      = $item['iscitytax'];
        $cityTax        = $item['citytax'];
        $cityTaxPercent = $item['citytaxpercent'];

        if ($isCityTax != '1') {
            $isCityTax = $cityTax = $cityTaxPercent = 0;
        }

        if (isset($item['employeeid'])) {

            $employeeId      = $item['employeeid'];
            $salespersonCode = $item['salespersoncode'];
            $salespersonName = $item['salespersonname'];

        } else {
            $employeeId = $salespersonCode = $salespersonName = '';
        }

        $isDelivery        = 0;
        $isDeliveryChecked = '';

        if (isset($item['isdelivery']) && $item['isdelivery'] == 1) {

            $isDelivery        = $item['isdelivery'];
            $isDeliveryChecked = ' checked="checked"';
        }

        $itemQty = 1;

        if (isset($item['invoiceqty'])) {

            $itemQty = $item['invoiceqty'];

            if ($itemQty < 0 || $itemQty == '') {
                $itemQty = 1;
            }

            $lineTotalPrice  = number_format($unitPrice * $itemQty, 2, '.', '');
            $lineTotalAmount = $lineTotalPrice;

        } else {
            $lineTotalAmount = $unitPrice;

            if (isset($item['linetotalamount'])) {
                $lineTotalAmount = $item['linetotalamount'];
            }

            $lineTotalPrice = $unitPrice;

            if (isset($item['linetotalprice'])) {
                $lineTotalPrice = $item['linetotalprice'];
            }
        }

        if ($itemQty < 0 || $itemQty == '') {
            $itemQty = 1;
        }

        $isDiscount = $discountPercent = $unitDiscount = $discountAmount = $totalDiscount = $unitReceivable = $serialNumber = '';

        if (issetParam($item['percentdiscount'])) {

            $isDiscount      = '1';
            $discountPercent = $item['percentdiscount'];
            $unitDiscount    = $item['unitdiscount'];
            $discountAmount  = $item['discountamount'];
            $totalDiscount   = $unitDiscount * $itemQty;

            if ($unitDiscount < 0) {
                $discountAmount = $totalDiscount / $itemQty;
                $unitPrice = $discountAmount;
            } else {
                $unitPrice = $item['discountamount'];
            }
            
            $lineTotalAmount  = number_format($unitPrice * $itemQty, 6, '.', '');      
        } elseif (issetParam($item['unitdiscount'])) {
            $isDiscount      = '1';
            $unitDiscount    = $item['unitdiscount'];
            $discountAmount  = $item['linetotalamount'];
            $totalDiscount   = $unitDiscount * $itemQty;

            if ($unitDiscount < 0) {
                $discountAmount = $totalDiscount / $itemQty;
                $unitPrice = $discountAmount;
            } else {
                $unitPrice = $item['linetotalamount'];
            }
            
            $lineTotalAmount  = number_format($unitPrice * $itemQty, 6, '.', '');      
        }

        if ($prevItem && $itemIndex % 2 !== 0) {
            $_POST['filterItemId1'] = $prevItem['id'];
            $_POST['filterItemId2'] = $item['id'];
            $getMatrix = $mdPos->getMatrixDiscound(true);

            if ($getMatrix && $getMatrix['gift']) {
                $matrixBtn = '<button type="button" class="btn btn-xs green matrix-gift-icon" onclick="posChooseItemGiftMatrixBtn(this);" title="Matrix бэлэг"><i class="fa fa-gift"></i></button>';
                $matrixHtml .= '<div class="mt5"><input type="radio" id="chooseMatrix1" name="chooseMatrix" data-discount="' . $getMatrix['discountpercent'] . '" class="notuniform" value="discount"> <label class="ml6" style="font-size:18px;" for="chooseMatrix1">Хямдрал - <strong>' . $getMatrix['discountpercent'] . '%</strong></label></div>';
                $matrixHtml .= '<div class="mt10"><input type="radio" id="chooseMatrix2" name="chooseMatrix" class="notuniform" value="gift"> <label class="ml6" style="font-size:18px;" for="chooseMatrix2">Бэлэг</label></div>';    
                $matrixHtml .= '<div class="mt10">' . $getMatrix['gift'] . '</div>';                         

            } elseif ($getMatrix && $getMatrix['discountpercent']) {
                
                $isDiscount = '1';
                if ($discountPercent) {
                    $discountPercent += $getMatrix['discountpercent'];
                } else {
                    $discountPercent = $getMatrix['discountpercent'];
                }
                $matrixDiscount = ($getMatrix['discountpercent'] / 100) * $item['saleprice'];
                $unitDiscount += $matrixDiscount;
                $unitPrice = $unitPrice - $matrixDiscount;     
                $unitPrice = $unitPrice < 0 ? 0 : $unitPrice;     
                
                $lineTotalAmount = number_format($unitPrice * $itemQty, 6, '.', '');
            }
        }               

        if (isset($item['unitreceivable'])) {
            $unitReceivable = $item['unitreceivable'];
        }

        if (isset($item['serialnumber'])) {
            $serialNumber = $item['serialnumber'];
        }

        $isJob = '';

        if (issetParam($item['jobid'])) {

            $isJob               = '1';
            $giftButton          = '<button type="button" class="btn btn-xs yellow" title="'.$this->lang->line('POS_0043').'"><i class="fa fa-wrench"></i></button>';
            $item['measurecode'] = 'ш';
            $item['isvat']       = '1';

            if (isset($item['isservice']) && $item['isservice'] == 1) {
                $isDelivery = 1;
            }

        } elseif ($item['measurecode'] == '') {
            $item['measurecode'] = 'ш';
        }

        $isVat      = $item['isvat'];
        $vatPercent = $item['vatpercent'];
        $vatPrice   = $item['vatprice'];

        if ($isVat != '1') {
            $isVat = $vatPercent = $vatPrice = 0;
        }

        if (isset($item['couponkeyid']) && $item['couponkeyid'] != '') {
            $isJob = '2';
            $qtyReadonly = ' readonly="readonly" data-accept-remove="1"';
            $giftButton = '<button type="button" class="btn btn-xs red-intense" title="'+$this->lang->line('POS_0044')+'"><i class="fa fa-credit-card"></i></button>';
        }

        if (isset($item['printcopies'])) {
            $printCopies = $item['printcopies'];
        }
        
        $salePrice = $item['saleprice'];
        if ($isDiscount == '1') {
            if ($unitDiscount <= $salePrice) {
                $salePrice = $salePrice - $unitDiscount;
            }
        }

        $totalPrice  = $itemQty * $salePrice;
        $lineTotalCityTax = $lineTotalVat = 0;

        if ($isCityTax == '1') {
            $cityTax = number_format($salePrice / 111, 6, '.', '');
            $lineTotalCityTax = number_format($totalPrice / 111, 6, '.', '');
        }

        if ($isVat == '1' && $isCityTax == '1') {
            $item['novatprice'] = number_format($salePrice - ($salePrice / 11.1), 6, '.', '');
            $lineTotalVat = number_format($totalPrice / 11.1, 6, '.', '');
        } else if ($isVat == '1') {
            $item['novatprice'] = number_format($salePrice - ($salePrice / 11), 6, '.', '');
            $lineTotalVat = number_format($totalPrice / 11, 6, '.', '');
        }            

        if (issetParam($item['isservicecharge']) == '1') {
            $qtyReadonly = ' readonly="readonly" data-accept-remove="1"';
        }
    ?>
    <tr <?php echo $trMatrixAttr; ?> data-item-id="<?php echo $item['id']; ?>" data-item-id-customer-id="<?php echo $item['id'].'_'; ?>" data-customerid="" data-item-code="<?php echo strtolower($item['itemcode'].$serialNumber); ?>">
        <td data-field-name="gift" class="text-center<?php echo $addClassName ?>">
            <?php echo $giftButton; ?>
            <?php echo $matrixBtn; ?>
        </td>
        <td data-field-name="itemCode" class="text-left<?php echo $addClassName ?>" style="font-size: 14px;"><?php echo $item['itemcode']; ?></td>
        <td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left"><?php echo $serialNumber; ?></td>
        <td data-field-name="itemName" class="text-left" title="<?php echo $itemName; ?>" style="font-size: 14px; line-height: 15px;">
            <input type="hidden" name="id[]" value="<?php echo issetParam($item['editid']); ?>">
            <input type="hidden" name="itemId[]" value="<?php echo $item['id']; ?>">
            <input type="hidden" name="itemCode[]" value="<?php echo $item['itemcode']; ?>">
            <input type="hidden" name="itemName[]" value="<?php echo $itemName; ?>">
            <input type="hidden" name="salePrice[]" value="<?php echo $item['saleprice']; ?>">
            <input type="hidden" name="totalPrice[]" value="<?php echo $lineTotalPrice; ?>">            
            <input type="hidden" name="measureId[]" value="">
            <input type="hidden" name="measureCode[]" value="<?php echo $item['measurecode']; ?>">
            <input type="hidden" name="barCode[]" value="<?php echo $item['barcode']; ?>">
            <input type="hidden" name="isVat[]" value="<?php echo $isVat; ?>">
            <input type="hidden" name="vatPercent[]" value="<?php echo $vatPercent; ?>">
            <input type="hidden" name="lineTotalVat[]" value="<?php echo $lineTotalVat; ?>">
            <input type="hidden" name="vatPrice[]" value="<?php echo $vatPrice; ?>">
            <input type="hidden" name="noVatPrice[]" value="<?php echo $item['novatprice']; ?>">
            <input type="hidden" name="isCityTax[]" value="<?php echo $isCityTax; ?>">
            <input type="hidden" name="cityTax[]" value="<?php echo $cityTax; ?>">
            <input type="hidden" name="lineTotalCityTax[]" value="<?php echo $lineTotalCityTax; ?>">
            <input type="hidden" name="cityTaxPercent[]" value="<?php echo $cityTaxPercent; ?>">
            <input type="hidden" name="discountPercent[]" value="<?php echo $discountPercent; ?>">
            <input type="hidden" name="discountAmount[]" value="<?php echo $discountAmount; ?>">
            <input type="hidden" name="unitDiscount[]" value="<?php echo $unitDiscount; ?>">
            <input type="hidden" name="totalDiscount[]" value="<?php echo $totalDiscount; ?>">
            <input type="hidden" name="isDiscount[]" value="<?php echo $isDiscount; ?>">
            <input type="hidden" name="storeWarehouseId[]" value="<?php echo $item['storewarehouseid']; ?>">
            <input type="hidden" name="deliveryWarehouseId[]" value="<?php echo $item['deliverywarehouseid']; ?>">
            <input type="hidden" name="isJob[]" value="<?php echo $isJob; ?>">
            <input type="hidden" name="giftJson[]" value="<?php echo (!empty($giftArray['rowJson']) ? '['.rtrim($giftArray['rowJson'], ', ').']': ''); ?>">
            <input type="hidden" name="serialNumber[]" value="<?php echo $serialNumber; ?>">
            <input type="hidden" name="itemKeyId[]">
            <input type="hidden" name="unitReceivable[]" value="<?php echo $unitReceivable; ?>">
            <input type="hidden" name="maxPrice[]">
            <input type="hidden" name="printCopies[]" value="<?php echo $printCopies; ?>">
            <input type="hidden" name="discountEmployeeId[]">
            <input type="hidden" name="editPriceEmployeeId[]" value="<?php echo issetParam($item['discountemployeeid']); ?>">
            <input type="hidden" name="discountTypeId[]">
            <input type="hidden" name="discountDescription[]">
            <input type="hidden" name="sectionId[]" value="<?php echo issetParam($item['sectionid']); ?>">
            <input type="hidden" name="salesOrderDetailId[]" value="<?php echo issetParam($item['salesorderdetailid']); ?>">
            <input type="hidden" name="cashRegisterId[]" value="<?php echo issetParam($item['cashregisterid']); ?>">
            <input type="hidden" name="storeId[]" value="<?php echo issetParam($item['storeid']); ?>">
            <input type="hidden" name="savedQuantity[]" value="<?php echo $itemQty; ?>">
            <input type="hidden" name="stateRegNumber[]" value="<?php echo issetParam($item['stateregnumber']); ?>">
            <input type="hidden" name="merchantId[]" value="<?php echo issetParam($item['merchantid']); ?>">
            <input type="hidden" name="internalId[]" value="<?php echo issetParam($item['internalid']); ?>">
            <input type="hidden" name="contractId[]" value="<?php echo issetParam($item['contractid']); ?>">
            <input type="hidden" name="customerId[]" value="<?php echo issetParam($item['customerid']); ?>">
            <input type="hidden" name="customerIdSaved[]" value="<?php echo issetParam($item['guestname']); ?>">
            <input type="hidden" name="salesOrderId[]" value="<?php echo issetParam($item['salesorderid']); ?>">
            <!-- <input type="hidden" name="orgCashRegisterCode[]" value="<?php //echo issetParam($item['orgcashregistercode']); ?>"> -->
            <input type="hidden" name="orgCashRegisterCode[]" value="<?php echo issetParam($item['stateregnumber']) === '5228697' ? '1001' : issetParam($item['orgcashregistercode']); ?>">
            <!-- <input type="hidden" name="orgStoreCode[]" value="<?php //echo issetParam($item['orgstorecode']); ?>"> -->
            <input type="hidden" name="orgStoreCode[]" value="<?php echo issetParam($item['stateregnumber']) === '5228697' ? '1' : issetParam($item['orgstorecode']); ?>">
            <input type="hidden" name="orgPosHeaderName[]" value="<?php echo issetParam($item['posbillprintname']); ?>">
            <input type="hidden" name="orgPosLogo[]" value="<?php echo issetParam($item['poslogo']); ?>">
            <input type="hidden" name="salesorderdetailid[]" value="<?php echo issetParam($item['salesorderdetailid']); ?>">
            <input type="hidden" name="lineTotalBonusAmount[]" value="<?php echo issetParam($item['linetotalbonusamount']); ?>">
            <input type="hidden" name="unitBonusAmount[]" value="<?php echo issetParam($item['unitbonusamount']); ?>">
            <input type="hidden" name="salesPersonId[]" value="<?php echo issetParam($item['salespersonid']); ?>">
            <input type="hidden" data-name="accompanyItems" value="">
            <input type="hidden" data-name="isServiceCharge" value="<?php echo issetParam($item['isservicecharge']); ?>">
            <input type="hidden" data-name="isCalcUPoint" name="isCalcUPoint[]" value="<?php echo issetParam($item['iscalcupoint']); ?>">
            <input type="hidden" data-name="calcBonusPercent" name="unitBonusPercent[]" value="<?php echo issetParam($item['unitbonuspercent']); ?>">
            <input type="hidden" data-name="isNotUseBonusCard" name="isNotUseBonusCard[]" value="<?php echo issetParam($item['isnotusebonuscard']); ?>">
            <input type="hidden" name="guestName[]" value="<?php echo issetParam($item['guestname']); ?>">
            <input type="hidden" name="returnDescription[]" value="">
            <input type="hidden" data-name="isFood" value="<?php echo issetParam($item['isfood']); ?>">
            <input type="hidden" data-name="isSavedOrder" name="isSavedOrder[]" value="1">
            <input type="hidden" data-name="upointTotalPrice" value="<?php echo $lineTotalPrice; ?>">
            <?php echo Session::get(SESSION_PREFIX.'posTypeCode') == '3' ? '<div class="item-code-mini">'.$item['itemcode'].'</div><div class="mt3">'.$itemName.'</div>' : $itemName; ?>
        </td>
        <td data-field-name="salePrice" class="text-right bigdecimalInit"><?php echo Session::get(SESSION_PREFIX.'isEditBasketPrice') !== '1' && issetParam($item['discountemployeeid']) == '' && $item['itemcode'] !== '40000099' ? $item['saleprice'] : '<input type="text" name="salePriceInput[]" class="pos-saleprice-input bigdecimalInit" value="'.$item['saleprice'].'" data-mdec="3">'; ?></td>
        <td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit<?php echo $addClassName ?>"><?php echo $unitReceivable; ?></td>
        <td data-field-name="quantity" class="pos-quantity-cell text-right">
            <script type="text/template" data-template="giftrow"><?php echo $giftArray['gift']; ?></script>
            <script type="text/template" data-template="matrixgiftrow"><?php echo $matrixHtml; ?></script>
            <?php if (Session::get(SESSION_PREFIX.'posTypeCode') == '3') { ?>
                <a href="javascript:;" class="list-icons-item basket-inputqty-button d-flex justify-content-center" title="">
                    <span class="<?php echo issetParam($item['isservicecharge']) == '1' ? 'd-none' : ''; ?>"><i class="icon-minus3 mr5"></i></span>
                    <span><input type="text" name="quantity[]" <?php echo $cashRegisterId == issetParam($item['cashregisterid']) || issetParam($item['cashregisterid']) == '' ? '' : ''; ?> class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="<?php echo $itemQty; ?>" data-seperatevalue="<?php echo $itemQty; ?>" value="<?php echo $itemQty; ?>" data-mdec="3"<?php echo $qtyReadonly; ?>></span>
                    <span class="<?php echo issetParam($item['isservicecharge']) == '1' ? 'd-none' : ''; ?>"><i class="icon-plus3 ml5"></i></span>
                </a>            
            <?php } else { ?>
                <input type="text" name="quantity[]" <?php echo $cashRegisterId == issetParam($item['cashregisterid']) || issetParam($item['cashregisterid']) == '' ? '' : ''; ?> class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="<?php echo $itemQty; ?>" value="<?php echo $itemQty; ?>" data-mdec="3"<?php echo $qtyReadonly; ?>>
            <?php } ?>
        </td>
        <td data-field-name="totalPrice" class="text-right bigdecimalInit"><?php echo $lineTotalAmount; ?></td>
        <td data-field-name="delivery" class="text-center" data-config-column="delivery">
            <input type="hidden" name="isDelivery[]" value="<?php echo $isDelivery; ?>">
            <?php
            if ($isJob != '1' || $isDelivery) {
            ?>
            <input type="checkbox" class="isDelivery" value="1" title="<?php echo $this->lang->line('POS_0014'); ?>"<?php echo $isDeliveryChecked; ?>>
            <?php
            }
            ?>
        </td>
        <td data-field-name="salesperson" class="text-center" data-config-column="salesperson">
            <?php
            if ($isJob != '1' || $employeeId) {
            ?>
            <div class="meta-autocomplete-wrap" data-section-path="employeeId">
                <div class="input-group double-between-input">
                    <input type="hidden" name="employeeId[]" id="employeeId_valueField" data-path="employeeId" class="popupInit" value="<?php echo $employeeId; ?>">
                    <input type="text" name="employeeId_displayField[]" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="employeeId" id="employeeId_displayField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" value="<?php echo $salespersonCode; ?>" title="<?php echo $salespersonCode; ?>">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('employeeId', '1454315883636', '1522404331251', 'single', 'employeeId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                    </span>  
                    <span class="input-group-btn">
                        <input type="text" name="employeeId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="employeeId" id="employeeId_nameField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" value="<?php echo $salespersonName; ?>" title="<?php echo $salespersonName; ?>">
                    </span>   
                </div>
            </div>
            <?php
            } else {
            ?>
            <input type="hidden" name="employeeId[]">
            <?php
            }
            ?>
        </td>
    </tr>
    <?php
        echo $giftRow;

        if ($prevItem && $itemIndex % 2 !== 0) {
            $trMatrixAttr = 'data-matrix-row="sent-matrix-row-'.getUID().'"';
        }

        $prevItem = $item;
    }
}
?>