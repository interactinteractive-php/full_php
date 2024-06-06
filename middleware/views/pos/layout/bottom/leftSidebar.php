<table class="pos-leftsidebar" style="margin-top: -20px;">
    <tbody>
        <tr style="height: 1px">
            <td style="width: 65px; height: 1px; font-size: 2px"></td>
            <td style="height: 1px; font-size: 2px"></td>
        </tr>
        <!-- <tr>
            <td colspan="2" class="title"><?php //echo $this->lang->line('POS_0052'); ?></td>
        </tr> -->
        <?php echo $this->crmChoosePosition['top']; ?>
        <?php
        if ($this->sidebarShowList) {
        foreach ($this->sidebarShowList['']['rows'] as $row) {
            if ($row['path'] == 'customerId') {
                if (array_key_exists($row['ordernumber'], $this->sidebarShowList)) {
                    foreach ($this->sidebarShowList[$row['ordernumber']]['rows'] as $childRow) { ?>
                        <tr>
                            <td class="text-right"><?php echo $childRow['name']; ?>:</td>
                            <td data-field-name="detail-customer-<?php echo Str::lower($childRow['path']); ?>"></td>
                        </tr>                        
                    <?php                     
                    }
                }
            }
        }} ?>
        <tr class="<?php echo Session::get(SESSION_PREFIX.'posTypeCode') == '4' ? '' : 'hidden' ?>">
            <td colspan="2" class="text-left pb0">Guest name:</td>
        </tr>
        <tr class="<?php echo Session::get(SESSION_PREFIX.'posTypeCode') == '4' ? '' : 'hidden' ?>">
            <td colspan="2" class="meta-autocomplete-wrap">
                <input type="text" id="guestName" class="form-control form-control-sm lookup-guestname-autocomplete-pos" autocomplete="off" placeholder="" style="width: 227px">
            </td>
        </tr>                               
        <tr>
            <td colspan="2" class="text-left pb0"><?php echo $this->lang->line('POS_0205'); ?> <span class="infoShortcut">(F4)</span>:</td>
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
        <tr style="display: none">
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
        <?php if (issetVar($this->quickItemList)) { 
            $rows = array_chunk($this->quickItemList, 2);
            foreach ($rows as $rrow) {
                echo '<tr><td colspan="2"><div class="d-flex justify-content-between">';
                foreach ($rrow as $rrkey => $rrrow) {
                    if (issetParam($rrrow['itemname'])) {
                    $ssstl = $rrkey ? 'margin-left:3px' : 'margin-right:3px';
                ?>
                    <button type="button" class="btn btn-circle blue-madison mt4 btn-block pos-append-quick-item" style="color: #333;height: 51px !important;padding: 2px;text-transform: lowercase;<?php echo $ssstl; ?>" onclick="appendQuickItem(this, '<?php echo $rrrow['itemcode'] ?>', '<?php echo $rrrow['itemid'] ?>');">
                        <?php echo $rrrow['itemname'] ?>
                    </button>
                <?php
                    }
                }
                echo '</div></td></tr>';
            }
        }
        if ($this->isConfigHealthRecipe) {
        ?>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0158'); ?>:</td>
            <td data-field-name="detail-emd-amount" class="bigdecimalInit"></td>
        </tr>
        <?php
        }
        if ($this->isConfigIsShowItemCheckEndQty) {
        ?>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0164'); ?>:</td>
            <td>
                <button style="color:#282828" type="button" class="btn btn-xs grey-cascade mt2" onclick="posItemEndQtyShowList();">Жагсаалт</button>
            </td>
        </tr>
        <?php
        }
        if ($this->isConfigSalesPerson) {
        ?>
        <tr>
            <td class="text-right"><?php echo $this->lang->line('POS_0161'); ?>:</td>
            <td data-field-name="detail-salesperson"></td>
        </tr>
        <?php
        }
        if ($this->isConfigRowDiscount) {
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
        if (isset($this->getLocker) && isset($this->getLocker['id'])) { 
        ?>
        <tr>
            <td class="text-right">Локер:</td>
            <td>
                <?php echo isset($this->getLocker['code']) ? $this->getLocker['code'].' | ' : ''; echo isset($this->getLocker['name']) ? $this->getLocker['name'] : ''; ?>
                <input type="hidden" id="lockerId" value="<?php echo $this->getLocker['id'].'_'.(isset($this->getLocker['code']) ? $this->getLocker['code'] : ''); ?>">
                <input type="hidden" id="lockerOrderId" value="<?php echo $this->basketInvoiceId; ?>">
                <input type="hidden" id="vipLockerId" value="<?php echo $this->vipLockerId ? $this->vipLockerId : $this->getLocker['id']; ?>">
                <input type="hidden" id="lockerCustomerId" value="<?php echo $this->lockerCustomerId; ?>">
                <input type="hidden" id="specialLocker" value="<?php echo $this->specialLocker; ?>">
                <input type="hidden" id="windowSessionId" value="<?php echo $this->windowSessionId; ?>">
            </td>
        </tr>            
        <?php 
        }        
        if (isset($this->multipleLockers)) { 
        ?>
        <tr>
            <td class="text-right">Локер:</td>
            <td>
                <?php if (is_array($this->multipleLockers)) {
                    $lockerJoin = '';

                    foreach ($this->multipleLockers as $locker) {
                        $lockerJoin .= $locker['lockernumber'].', ';  
                        echo '<input type="hidden" class="multipleLockerId" name="multipleLockerId[]" value="' . $locker['id'].'_'.$locker['lockernumber'].'">';
                    }
                } ?>
                <?php echo rtrim($lockerJoin, ', '); ?>
            </td>
        </tr>            
        <?php 
        }        
        if (isset($this->getLocker) && isset($this->getLocker['serialtext'])) { 
        ?>
        <tr class="hidden">
            <td class="text-right"></td>
            <td>
                <input type="hidden" name="serialText" value="<?php echo $this->getLocker['serialtext']; ?>">
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
        if ($this->isConfigHealthRecipe) {
        ?>
        <tr>
            <td colspan="2" class="text-left pb0">Хяналтын дугаар:</td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" id="posReceiptNumber" class="form-control form-control-sm pos-receipt-number" placeholder="<?php echo $this->lang->line('POS_0166'); ?>" style="width: 227px">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-left pb0">Регистрийн дугаар:</td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" id="posReceiptRegNumber" class="form-control form-control-sm pos-receipt-regnumber" placeholder="<?php echo $this->lang->line('POS_0166'); ?>" style="width: 227px">
            </td>
        </tr> 
        <?php
        }
        
        echo $this->crmChoosePosition['bottom'];

        if ($this->isConfigAccompanyItem) {
        ?>
        <tr>
            <td colspan="2" class="text-left pb0"><?php echo $this->lang->line('POS_ACCOMPANY_ITEM'); ?>:</td>
        </tr>
        <tr>
            <td colspan="2" class="pos-service-combogrid-cell">
                <input type="text" id="posAccompanyItem" class="form-control form-control-sm ignorebarcode" placeholder="" style="width: 227px">
            </td>
        </tr>
        <?php
        }        
        if ($this->isConfigServiceJobAccompany) {
        ?>
        <tr>
            <td colspan="2" class="text-left pb0"><?php echo $this->lang->line('POS_ACCOMPANY_SERVICE'); ?>:</td>
        </tr>
        <tr>
            <td colspan="2" class="pos-service-combogrid-cell">
                <input type="text" id="posServiceCodeAccompany" class="form-control form-control-sm ignorebarcode" placeholder="" style="width: 227px">
            </td>
        </tr>
        <?php
        }            
        if ($this->isConfigShowQrcode) {
        ?>
        <tr>
            <td colspan="2" class="text-left pb0">Qrcode:</td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" id="posEshopQrcode" class="form-control form-control-sm" placeholder="" style="width: 227px">
                <input type="hidden" id="posEshopOrderTime" class="form-control form-control-sm" placeholder="">
            </td>
        </tr>      
        <?php
        }            
        ?>           
    </tbody>
</table>

<div class="pos-left-inside-help">
    <div class="pos-invoice-number" style="display: none">
        <span id="pos-invoice-label-title"><?php echo $this->lang->line('POS_0168'); ?></span>:
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
    <input type="hidden" id="invoiceBasketTypeId">
    <input type="hidden" id="invoiceJsonStr">
    
    <input type="hidden" id="returnInvoiceId">
    <input type="hidden" id="returnTypeInvoice">
    <input type="hidden" id="returnInvoiceBillId">
    <input type="hidden" id="returnInvoiceNumber">
    <input type="hidden" id="returnInvoiceRefNumber">
    <input type="hidden" id="returnInvoiceBillType">
    <input type="hidden" id="returnInvoiceBillDate">
    <input type="hidden" id="returnInvoiceIsGL">
    <input type="hidden" id="returnInvoiceReceiptNumber">

    <input type="hidden" id="returnInvoiceBillStateRegNumber">
    <input type="hidden" id="returnInvoiceBillStoreCode">
    <input type="hidden" id="returnInvoiceBillCashRegisterCode">
    
    <input type="hidden" id="basketInvoiceId" value="<?php echo $this->basketInvoiceId ?>">
    <input type="hidden" id="basketCustomerId">
    <input type="hidden" id="basketCustomerCode">
    <input type="hidden" id="basketCustomerName">
    <input type="hidden" id="basketCardNumber">
    <input type="hidden" id="basketCreatedUserId">
    <input type="hidden" id="posLocationId">
    <input type="hidden" id="posRestWaiterId">    
    
    <?php
    if ($this->isConfigRedPointItems) {
    ?>
    <button type="button" class="btn btn-block btn-circle red btn-sm" onclick="posRedPointItemList();">
        RedPoint бараанууд
    </button>
    <?php
    }
    ?>
    
    <?php
    if (Session::get(SESSION_PREFIX.'posTypeCode') == '4' && false) {
    ?>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="multiCustomerList2Pos();">
        Харилцагчаар
    </button>
    <?php
    }
    ?>
    
    <?php
    if ($this->candyCashback) {
    ?>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posCandyInfo(this);" data-criteria="storeId=<?php echo Session::get(SESSION_PREFIX.'storeId'); ?>">
        <?php echo $this->lang->line('candy cashback'); ?>
    </button>
    <?php
    }
    ?>
    <?php
    if (Config::getFromCacheDefault('POS_CUSTOMER_ADD_FORM', null, '0')) {
    ?>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posNewCardCustomer(this, true);">
        <?php echo $this->lang->line('POS_0187'); ?>
    </button>
    <?php
    }
    ?>
    <?php
    if ($this->isConfigInvoiceList) {
    ?>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posInvoiceList(this, '<?php echo Config::getFromCache('IS_USE_POSAPI_V3') ? '1521452365722' : Config::getFromCacheDefault('CONFIG_POS_INVOICE_LIST_META_DATA_ID', null, 0) ?>');" data-criteria="storeId=<?php echo Session::get(SESSION_PREFIX.'storeId'); ?>">
        <?php echo $this->lang->line('POS_0147'); ?> <span class="infoShortcut">(F3)</span>
    </button>
    <button type="button" style="background-color: #1BBC9B !important;color: #fff !important;" class="btn btn-block btn-circle blue-madison btn-sm hidden" onclick="posOrderSave(1);">
        Нэхэмжлэл үүсгэх
    </button>
    <?php
    }
    if ($this->isConfigContractList) {
    ?>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posContractList(this);">
        Гэрээний жагсаалт
    </button>
    <?php
    }
    if ($this->isConfigAddCustomerSidebar) {
    ?>
    <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posNewServiceCustomer(this, true);">
        <?php echo $this->lang->line('POS_0187'); ?>
    </button>
    <?php
    }
    if (Config::getFromCacheDefault('POS_IS_SHOW_TALON_LIST', null, '1')) { 
    ?>
    <button type="button" class="btn btn-block btn-circle btn-sm blue-madison" onclick="posTalonList();">
        <?php echo $this->lang->line('POS_0046'); ?> <span class="infoShortcut">(Shift+F3)</span>
    </button>
    <?php 
    }
    if ($this->isCreateDeposit) { ?>
    <button type="button" class="btn btn-block btn-circle btn-sm grey-cascade" onclick="posCreateDepozit('<?php echo $this->isCreateDeposit ?>');">
        <?php echo 'Депозит үүсгэх'; ?>
    </button>
    <?php } ?>
    <?php if ($this->isConfigTestPrint) { ?>
    <button type="button" class="btn btn-block btn-circle btn-sm grey-cascade" onclick="posTestBillPrint();">
        <?php echo $this->lang->line('POS_0145'); ?> <span class="infoShortcut">(F2)</span>
    </button>
    <?php } ?>
</div>