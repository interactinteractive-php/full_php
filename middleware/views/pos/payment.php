<form method="post" id="pos-payment-form" style="margin-top: -10px;" autocomplete="off">
    <input type="password" autocomplete="off" style="display:none" />
    <input type="password" autocomplete="off" style="display:none" />
    
    <div class="row">
        <div class="<?php echo Config::getFromCache('CONFIG_POS_TEMP_INVOICE_KEY_FIELD') === 'locker' ? 'col-md-12' : 'col-md-8' ?>" style="border-right: 1px #999 solid;">
            
            <div class="row pos-payment-header" style="padding-top: 7px;">
                <div class="col-md-4">
                    <div class="radio-list">
                        <label>
                            <input type="radio" name="posBillType" value="person"<?php echo ($this->billType == 'person') ? ' checked="checked"' : ''; ?>> <?php echo $this->lang->line('POS_0176'); ?> <span class="infoShortcut">(F6)</span>
                        </label>
                        <label>
                            <input type="radio" name="posBillType" value="organization"<?php echo ($this->billType == 'organization') ? ' checked="checked"' : ''; ?>> <?php echo $this->lang->line('POS_0177'); ?> <span class="infoShortcut">(F6)</span>
                        </label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group row fom-row mb5" id="pos-org-number-row">
                        <label class="col-md-4 col-form-label text-right pl0" for="pos-org-number"><?php echo $this->lang->line('POS_0178'); ?>:</label>
                        <div class="col-md-6 p-0">
                            <input type="text" name="orgRegNumber" class="form-control form-control-sm" id="pos-org-number" value="<?php echo $this->orgNumber; ?>">
                        </div>
                        <div class="col-md-2 pl0">
                            <button type="button" class="btn btn-xs grey-cascade" onclick="posNotVatCustomerList();" title="<?php echo $this->lang->line('POS_0071'); ?>" style="margin-left: 12px"><i class="fa fa-align-justify"></i></button>
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row mb5" id="pos-org-name-row">
                        <label class="col-md-4 col-form-label text-right" for="pos-org-name"><?php echo $this->lang->line('POS_0179'); ?>:</label>
                        <div class="col-md-8 pl0">
                            <input type="text" name="orgName" class="form-control form-control-sm" id="pos-org-name" value="<?php echo $this->orgName; ?>">
                            <input type="hidden" name="orgVatPayer" id="pos-org-vatpayer">
                            <input type="hidden" name="tmpPayAmount" id="tmpPayAmount" value="<?php echo $this->payAmount; ?>">
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 pos-payment-area">

                    <div class="form-group row fom-row mb5">
                        <label class="col-md-3 col-form-label text-right pl0 uppercase" for="posPayAmount" style="font-size: 19px"><?php echo $this->lang->line('MET_331392'); ?>:</label>
                        <div class="pos-payment-amount-col">
                            <input type="text" name="payAmount" class="form-control form-control-sm bigdecimalInit" id="posPayAmount" value="<?php echo $this->payAmount; ?>" readonly="readonly">
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>

                    <hr/>

                    <div class="form-group row fom-row mb5">
                        <label class="col-md-3 col-form-label text-right" for="posCashAmount"><?php echo $this->lang->line('POS_0180'); ?>:</label>
                        <div class="pos-payment-amount-col">
                            <input type="text" name="cashAmount" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" <?php echo issetParam($this->cashAmountDisable) ? "readonly" : "";?> placeholder="<?php echo $this->lang->line('POS_0180'); ?>" id="posCashAmount" value="<?php echo $this->cashAmount; ?>">
                        </div>
                        <div class="col-md-2">
                            <?php // if (defined('CONFIG_POS_TEMP_INVOICE_KEY_FIELD') && CONFIG_POS_TEMP_INVOICE_KEY_FIELD === 'locker') { ?>
                                <button type="button" class="btn btn-xs grey-cascade mt2" onclick="posCashMoneyBill();" title="<?php echo $this->lang->line('POS_0181'); ?>" style="margin-left: 1px"><i class="fa fa-sort-amount-desc"></i> <span class="infoShortcut" style="color:#fff">(F8)</span></button>
                            <?php // } ?>
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>

                    <hr/>

                    <div class="form-group row fom-row mb5<?php echo Config::getFromCacheDefault('POS_FILL_CASH_AMOUNT_PAYMENT', null, '0') ? ' hidden' : '' ?>">
                        <label class="col-md-3 col-form-label text-right uppercase"><?php echo $this->lang->line('POS_0207'); ?>:</label>
                        <div class="col-md-9 pos-bank-row-dtl">
                            <?php
                            $sumBankAmount = 0;
                            $bankTerminalConfirmCode = '';
                            
                            if (isset($this->bankAmountList) && !empty($this->bankAmountList)) {
                                foreach ($this->bankAmountList as $bk => $bankRow) {
                                    ?>
                                    <div class="row pos-bank-row">
                                        <div class="pos-payment-amount-col">
                                            <input type="text" name="bankAmountDtl[]" class="form-control form-control-sm bigdecimalInit posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0207'); ?>" value="<?php echo $bankRow['amount']; ?>">
                                            <input type="hidden" name="deviceRrn[]" />
                                            <input type="hidden" name="devicePan[]" />
                                            <input type="hidden" name="deviceAuthcode[]" />
                                            <input type="hidden" name="deviceTerminalId[]" value="<?php echo issetParam($bankRow['terminalnumber']); ?>" />
                                            <input type="hidden" name="deviceTraceNo[]" value="<?php echo issetParam($bankRow['cardregisternumber']); ?>" />
                                            <input type="hidden" name="rowTerminalConfirmCode[]" value="<?php echo issetParam($bankRow['confirmcode']); ?>">
                                            <input type="hidden" name="rowTerminalAmount[]" value="<?php echo issetParam($bankRow['amount']); ?>">
                                        </div>
                                        <div class="col-md-5 pr0">
                                            <?php 
                                            echo str_replace('<option value="'.$bankRow['bankid'].'"', '<option value="'.$bankRow['bankid'].'" selected="selected"', $this->bankCardCombo); 
                                            ?>
                                        </div>    
                                        <div class="col-md-1">
                                            <?php
                                            if ($bk == 0) {
                                            ?>
                                            <button type="button" class="btn btn-circle btn-sm green" onclick="addPosBankRow(this);" data-bank-action="add"><i class="icon-plus3 font-size-12"></i></button><span class="infoShortcut" style="position: absolute;">(F11)</span>
                                            <?php
                                            } else {
                                            ?>
                                            <button type="button" class="btn btn-circle btn-sm red" onclick="removePosBankRow(this);" data-bank-action="remove"><i class="fa fa-trash"></i></button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    $sumBankAmount += $bankRow['amount'];
                                    if ($bankRow['confirmcode']) {
                                        $bankTerminalConfirmCode = $bankRow['confirmcode'];
                                    }
                                }
                            }
                            ?>
                        </div>    
                        <input type="hidden" name="bankAmount" id="posBankAmount"  value="<?php echo $sumBankAmount; ?>">
                        <input type="hidden" name="bankTerminalConfirmCode" id="posTerminalConfirmCode" class="" value="<?php echo $bankTerminalConfirmCode; ?>">
                        <div class="clearfix w-100"></div>
                    </div>
                    
                    <div class="form-group row fom-row mb5 d-none" data-config-column="isNotUseIpterminal">
                        <label class="col-md-3 col-form-label text-right uppercase">Карт ашиглахгүй эсэх:</label>
                        <div class="col-md-9" style="padding-left:0">
                            <input type="checkbox" id="isNotUseIpterminal" value="1"/>
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>     
                    
                    <div data-config-column="qpay-amount"<?php echo Config::getFromCache('CONFIG_POS_PAYMENT_QPAY') === '1' ? '' : ' class="hidden"' ?>>
                        <hr/>

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posqpayAmt" style="padding-left: 0;">QPAY:</label>
                            <div class="col-md-9">
                                <div class="row pos-qpay-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="posqpayAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="QPAY" id="posqpayAmt" value="">
                                        <input type="hidden" name="qpay_bill_no" value="" />
                                        <input type="hidden" name="qpay_traceNo" value="" />
                                    </div> 
                                    <div class="float-left pl15">
                                        <button type="button" class="btn btn-circle btn-sm blue" title="QR code үүсгэх" onclick="posSearchQpay(this);"><i class="fa fa-qrcode"></i></button>
                                    </div>                              
                                </div>                              
                            </div>                              
                            <div class="clearfix w-100"></div>
                        </div>                      
                    </div>                    
                    
                    <div data-config-column="tokipay-amount"<?php echo Config::getFromCache('CONFIG_POS_PAYMENT_TOKI_PAY') === '1' ? '' : ' class="hidden"' ?>>
                        <hr/>

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="postokiAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_00056'); ?>:</label>
                            <div class="col-md-9">
                                <div class="row pos-tokipay-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="postokipayAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="<?php echo $this->lang->line('POS_00056'); ?>" id="postokipayAmt" value="">
                                        <input type="hidden" name="tokipay_bill_no" value="" />
                                        <input type="hidden" name="tokipay_traceNo" value="" />
                                    </div> 
                                    <div class="float-left pl15">
                                        <button type="button" class="btn btn-circle btn-sm blue" title="QR code үүсгэх" onclick="posSearchTokipay(this);"><i class="fa fa-qrcode"></i></button>
                                    </div>                              
                                </div>                              
                            </div>                              
                            <div class="clearfix w-100"></div>
                        </div>                      
                    </div>                    
                    
                    <div data-config-column="socialpay-amount"<?php echo Config::getFromCache('isPosSocialPay') === '1' ? '' : ' class="hidden"' ?>>
                        <hr/>

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posSocialpayAmt" style="padding-left: 0;">SOCIAL PAY:</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="posSocialpayAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" <?php echo issetParam($this->socialAmountDisable) ? "readonly" : "";?> placeholder="SOCIAL PAY" id="posSocialpayAmt" value="<?php echo is_array($this->socialAmount) && issetParam($this->socialAmount['amount']) ? $this->socialAmount['amount'] : ''; ?>">
                                    </div>
                                    <div class="col-md-5 pr0">
                                        <input type="text" name="posSocialpayPhoneNumber" <?php echo issetParam($this->socialAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm integerInit pos-payment-input" style="height: 27px;" placeholder="УТАСНЫ ДУГААР" id="posSocialpayPhoneNumber" value="">
                                        <input type="hidden" name="posSocialpayUID" class="" value="<?php echo is_array($this->socialAmount) ? issetParam($this->socialAmount['bankid']) : ''; ?>" />
                                        <input type="hidden" name="posSocialpayApprovalCode" value="<?php echo is_array($this->socialAmount) ? issetParam($this->socialAmount['confirmcode']) : ''; ?>" />
                                        <input type="hidden" name="posSocialpayCardNumber" value="<?php echo is_array($this->socialAmount) ? issetParam($this->socialAmount['bankcardnumber']) : ''; ?>" />
                                        <input type="hidden" name="posSocialpayTerminal" value="<?php echo is_array($this->socialAmount) ? issetParam($this->socialAmount['terminalnumber']) : ''; ?>" />
                                    </div>    
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-circle btn-sm blue" onclick="posQRSocialPay(this);"><i class="fa fa-qrcode"></i></button>
                                    </div>                              
                                </div>                              
                            </div>                              
                            <div class="clearfix w-100"></div>
                        </div>                      
                    </div>                    
                    
                    <div data-config-column="coupon-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" style="padding-left: 0;"><?php echo $this->lang->line('POS_0088'); ?>:</label>
                            <div class="col-md-9 pos-voucher-row-dtl">
                                <?php
                                $sumCouponAmount = 0;

                                if (isset($this->couponAmountList) && !empty($this->couponAmountList)) {
                                    foreach ($this->couponAmountList as $ck => $couponRow) {
                                ?>
                                <div class="row pos-voucher-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="voucherDtlAmount[]" class="form-control form-control-sm bigdecimalInit posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0088'); ?>" title="<?php echo $this->lang->line('POS_0088'); ?>" readonly="readonly" value="<?php echo $couponRow['amount']; ?>">
                                    </div>
                                    <div class="col-md-5 pr0">
                                        <input type="text" name="voucherDtlSerialNumber[]" class="form-control form-control-sm pos-payment-input" placeholder="<?php echo $this->lang->line('POS_0197'); ?> (SHIFT+Z)" title="<?php echo $this->lang->line('POS_0197'); ?>" value="<?php echo $couponRow['name']; ?>">
                                        <input type="hidden" name="voucherDtlId[]">
                                        <input type="hidden" name="voucherTypeId[]">
                                    </div>
                                    <div class="col-md-1">
                                        <?php
                                        if ($ck == 0) {
                                        ?>
                                        <button type="button" class="btn btn-circle btn-sm green" onclick="addPosVoucherRow(this);" data-voucher-action="add"><i class="icon-plus3 font-size-12"></i></button>
                                        <?php
                                        } else {
                                        ?>
                                        <button type="button" class="btn btn-circle btn-sm red" onclick="removePosVoucherRow(this);" data-voucher-action="remove"><i class="fa fa-trash"></i></button>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    $sumCouponAmount += $couponRow['amount'];
                                    }
                                }
                                ?>
                            </div>
                            <input type="hidden" name="voucherAmount" id="posVoucherAmount" class="posUserAmount" value="<?php echo $sumCouponAmount; ?>">
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>

                    <div data-config-column="coupon2-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" style="padding-left: 0;"><?php echo $this->lang->line('POS_0214'); ?>:</label>
                            <div class="col-md-9 pos-voucher2-row-dtl">
                            <?php
                                $sumCouponAmount = 0;

                                if (isset($this->coupon2AmountList) && !empty($this->coupon2AmountList)) {
                                    foreach ($this->coupon2AmountList as $ck => $couponRow) {
                                ?>
                                <div class="row pos-voucher-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="voucher2DtlAmount[]" class="form-control form-control-sm bigdecimalInit posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0214'); ?>" title="<?php echo $this->lang->line('POS_0214'); ?>" readonly="readonly" value="<?php echo $couponRow['voucher2Amount']; ?>">
                                    </div>
                                    <div class="col-md-5 pr0">
                                        <input type="text" name="voucher2DtlSerialNumber[]" class="form-control form-control-sm pos-payment-input" placeholder="<?php echo $this->lang->line('POS_0197'); ?>" title="<?php echo $this->lang->line('POS_0197'); ?>" value="<?php echo $couponRow['name']; ?>">
                                        <input type="hidden" name="voucher2DtlId[]">
                                        <input type="hidden" name="voucher2TypeId[]">
                                    </div>
                                    <div class="col-md-1">
                                        <?php
                                        if ($ck == 0) {
                                        ?>
                                        <button type="button" class="btn btn-circle btn-sm green" onclick="addPosVoucherRow(this);" data-voucher-action="add"><i class="icon-plus3 font-size-12"></i></button>
                                        <?php
                                        } else {
                                        ?>
                                        <button type="button" class="btn btn-circle btn-sm red" onclick="removePosVoucherRow(this);" data-voucher-action="remove"><i class="fa fa-trash"></i></button>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    $sumCouponAmount += $couponRow['amount'];
                                    }
                                }
                                ?>
                            </div>
                            <input type="hidden" name="voucher2Amount" id="posVoucher2Amount" class="posUserAmount" value="<?php echo $sumCouponAmount; ?>">
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <div data-config-column="bonuscard-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posBonusCardAmount" style="padding-left: 0;"><?php echo $this->lang->line('MET_99990557'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="bonusCardAmount" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="<?php echo $this->lang->line('MET_99990557'); ?>" id="posBonusCardAmount" readonly="readonly" value="<?php echo (isset($this->bonusCardAmount) ? $this->bonusCardAmount : ''); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="discount-activity-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posdiscountActivityAmount" style="padding-left: 0;"><?php echo $this->lang->line('SM_0001'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="discountActivityAmount" <?php echo issetParam($this->discountAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="<?php echo $this->lang->line('SM_0001'); ?>" id="posdiscountActivityAmount" value="<?php echo (isset($this->discountActivityAmount) ? $this->discountActivityAmount : ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <!-- <select name="discountActivityCustomerId" id="discountActivityCustomerId" data-path="discountActivityCustomerId" class="form-control form-control-sm select2" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1536742182010&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;customername&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;cityId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                                    <option value="">- Харилцагч сонгох -</option>
                                </select> -->
                                <div class="meta-autocomplete-wrap <?php echo issetParam($this->discountAmountDisable) ? "hidden" : "";?>" data-section-path="discountActivityCustomerId">
                                    <div class="input-group double-between-input">
                                        <input type="hidden" name="discountActivityCustomerId" id="discountActivityCustomerId_valueField" data-path="discountActivityCustomerId" class="popupInit">
                                        <input type="text" name="discountActivityCustomerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="discountActivityCustomerId" id="discountActivityCustomerId_displayField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="<?php echo Lang::line('code_search') ?>" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('discountActivityCustomerId', '1454315883636', '1536742182010', 'single', 'discountActivityCustomerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                        </span>  
                                        <span class="input-group-btn">
                                            <input type="text" name="discountActivityCustomerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="discountActivityCustomerId" id="discountActivityCustomerId_nameField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="<?php echo Lang::line('name_search') ?>" tabindex="-1" autocomplete="off">
                                        </span>   
                                    </div>
                                </div>                                
                            </div>                            
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="insurance-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posinsuranceAmount" style="padding-left: 0;"><?php echo $this->lang->line('POS_0218'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="insuranceAmount" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0218'); ?>" id="posinsuranceAmount" value="<?php echo (isset($this->insuranceAmount) ? $this->insuranceAmount : ''); ?>">
                            </div> 
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="accounttransfer-amount">
                        <hr />
                        
                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase"><?php echo $this->lang->line('POS_0089'); ?>:</label>
                            <div class="col-md-9 pos-accounttransfer-row-dtl">
                                <?php
                                $sumAccountTransferAmount = 0;

                                if (isset($this->accountTransferAmountList) && !empty($this->accountTransferAmountList)) {                                    
                                    foreach ($this->accountTransferAmountList as $tk => $accountTransferRow) {
                                ?>
                                <div class="row pos-accounttransfer-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="accountTransferAmountDtl[]" class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0089'); ?>" value="<?php echo $accountTransferRow['amount']; ?>">
                                        <input type="hidden" name="accountTransferBillingIdDtl[]">
                                        <input type="hidden" name="accountTransferDescrDtl[]">
                                    </div>
                                    <div class="col-md-5 pr0">
                                        <?php 
                                        echo str_replace('<option value="'.$accountTransferRow['bankid'].'"', '<option value="'.$accountTransferRow['bankid'].'" selected="selected"', str_replace('posBankIdDtl[]', 'accountTransferBankIdDtl[]', $this->bankTransferCombo)); 
                                        ?>
                                    </div>    
                                    <div class="col-md-1">
                                        <?php
                                        if ($tk == 0) {
                                        ?>
                                        <button type="button" class="btn btn-circle btn-sm green" onclick="addPosAccountTransferRow(this);" data-row-action="add" title="<?php echo $this->lang->line('addrow'); ?>"><i class="icon-plus3 font-size-12"></i></button>
                                        <?php
                                        } else {
                                        ?>
                                        <button type="button" class="btn btn-circle btn-sm red" onclick="removePosAccountTransferRow(this);" data-bank-action="remove"><i class="fa fa-trash"></i></button>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    $sumAccountTransferAmount += $accountTransferRow['amount'];
                                    }
                                }
                                ?>
                            </div>    
                            <input type="hidden" name="posAccountTransferAmt" id="posAccountTransferAmt" class="posUserAmount" value="<?php echo $sumAccountTransferAmount; ?>">
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>    
                    
                    <div data-config-column="mobilenet-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posMobileNetAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0090'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posMobileNetAmt" <?php echo issetParam($this->mobileTransferAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0090'); ?>" id="posMobileNetAmt" value="<?php echo issetParam($this->mobileNetAmount['amount']); ?>">
                            </div>
                            <div class="col-md-4 <?php echo issetParam($this->mobileTransferAmountDisable) ? "hidden" : "";?>" style="padding-right: 14px;">
                                <?php 
                                $mobileNetBankCombo = str_replace(
                                    array('name="posBankIdDtl[]"', 'class="form-control form-control-sm select2 mb5"'), 
                                    array('name="posMobileNetBankId"', 'class="form-control form-control-sm select2"'), 
                                    $this->bankCombo
                                );

                                if (isset($this->mobileNetAmount['bankid'])) {
                                    $mobileNetBankCombo = str_replace('<option value="'.$this->mobileNetAmount['bankid'].'"', '<option value="'.$this->mobileNetAmount['bankid'].'" selected="selected"', $mobileNetBankCombo);
                                }

                                echo $mobileNetBankCombo;
                                ?>
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="other-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posOtherAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0219'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posOtherAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0219'); ?>" id="posOtherAmt" value="<?php echo issetParam($this->otherAmount['amount']); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>

                    <div data-config-column="prepayment-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" style="padding-left: 0;"><?php echo $this->lang->line('POS_GLOBE_PREPAYMENT'); ?>:</label>
                            <div class="col-md-9 pos-prepayment-row-dtl">
                                <?php
                                // $sumPrePaymentAmount = '';

                                // if (isset($this->prePaymentAmountList) && !empty($this->prePaymentAmountList)) {
                                //     foreach ($this->prePaymentAmountList as $ck => $couponRow) {
                                ?>
                                <div class="row pos-prepayment-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="prePyamentDtlAmount" class="form-control form-control-sm bigdecimalInit posKeyAmount" placeholder="<?php echo $this->lang->line('POS_GLOBE_PREPAYMENT'); ?>" title="" readonly="readonly" value="<?php echo issetParam($this->prePaymentAmount['amount']); ?>">
                                    </div>
                                    <div class="col-md-5 pr0">
                                        <div class="meta-autocomplete-wrap w-100" data-section-path="prePaymentCustomerId">
                                            <div class="input-group double-between-input">
                                                <input type="hidden" name="prePaymentCustomerId" id="prePaymentCustomerId_valueField" data-path="prePaymentCustomerId" class="popupInit" value="<?php echo issetParam($this->prePaymentAmount['extransactionId']); ?>">
                                                <input type="text" name="prePaymentCustomerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" value="<?php echo issetParam($this->prePaymentAmount['code']); ?>" id="prePaymentCustomerId_displayField" data-processid="1454315883636" data-lookupid="1579598747132" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('prePaymentCustomerId', '1454315883636', '1579598747132', 'single', 'prePaymentCustomerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                                </span>  
                                                <span class="input-group-btn">
                                                    <input type="text" name="prePaymentCustomerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" id="prePaymentCustomerId_nameField" data-processid="1454315883636" value="<?php echo issetParam($this->prePaymentAmount['name']); ?>" data-lookupid="1579598747132" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                </span>   
                                            </div>
                                        </div>      
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <?php
                                //     $sumPrePaymentAmount += $couponRow['amount'];
                                //     }
                                // }
                                ?>
                            </div>
                            <input type="hidden" name="prePaymentAmount" id="posPrePaymentAmount" class="posUserAmount" value="<?php echo issetParam($this->prePaymentAmount['amount']); ?>">
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <div data-config-column="leasing-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posLeasingAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0092'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posLeasingAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0092'); ?>" id="posLeasingAmt" value="<?php echo issetParam($this->leasingAmount['amount']); ?>">
                            </div>
                            <div class="col-md-4" style="padding-right: 13px;">
                                <?php 
                                $leasingBankCombo = str_replace(
                                    array('name="posBankIdDtl[]"', 'class="form-control form-control-sm select2 mb5"'), 
                                    array('name="posLeasingBankId"', 'class="form-control form-control-sm select2"'), 
                                    $this->bankCombo
                                );

                                if (isset($this->leasingAmount['bankid'])) {
                                    $leasingBankCombo = str_replace('<option value="'.$this->leasingAmount['bankid'].'"', '<option value="'.$this->leasingAmount['bankid'].'" selected="selected"', $leasingBankCombo);
                                }

                                echo $leasingBankCombo;
                                ?>
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="candy-amount">
                        <hr />
                        
                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase">Монпэй:</label>
                            <div class="col-md-9 pos-candy-row-dtl">
                                <?php
                                $sumCandyAmount = '';
                                ?>
                            </div>    
                            <input type="hidden" name="posCandyAmt" id="posCandyAmt" class="posUserAmount" value="<?php echo $sumCandyAmount; ?>">
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>    
                    
                    <div data-config-column="upoint-amount">
                        <hr />
                        
                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase">U-point:</label>
                            <div class="col-md-9 pos-upoint-row-dtl">
                                <div class="row pos-upoint-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="upointAmountDtl[]" readonly class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" value="<?php echo isset($this->upointAmount) && is_array($this->upointAmount) ? issetParam($this->upointAmount['upointAmount']) : ''; ?>" placeholder="U-POINT">
                                        <input type="hidden" name="upointTypeCodeDtl[]">
                                        <input type="hidden" name="intamt[]" value="<?php echo isset($this->upointAmount) && is_array($this->upointAmount) ? issetParam($this->upointAmount['intamt']) : ''; ?>">
                                        <input type="hidden" name="upointDetectedNumberDtl[]" value="<?php echo isset($this->upointAmount) && is_array($this->upointAmount) ? issetParam($this->upointAmount['bankcardnumber']) : ''; ?>">
                                        <input type="hidden" name="upointTransactionIdDtl[]" value="<?php echo isset($this->upointAmount) && is_array($this->upointAmount) ? issetParam($this->upointAmount['exttransactionid']) : ''; ?>">
                                    </div>
                                    <div class="float-left pl15">
                                        <button type="button" class="btn btn-circle btn-sm blue d-none" onclick="posSearchUpoint(this);" title="Мэдээлэл шалгах"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>                                
                                <?php
                                $sumUpointAmount = isset($this->upointAmount) && is_array($this->upointAmount) ? issetParam($this->upointAmount['upointAmount']) : 0;
                                ?>
                            </div>    
                            <input type="hidden" name="posUpointAmt" id="posUpointAmt" class="posUserAmount" value="<?php echo $sumUpointAmount; ?>">
                            <input type="hidden" id="posUpointReturnResult" class="" value="">
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>    
                    
                    <div data-config-column="candy-coupon-amount">
                        <hr />
                        
                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase">Монпэй купон:</label>
                            <div class="col-md-9 pos-candy-coupon-row-dtl">
                                <?php
                                $sumCandyCouponAmount = '';
                                ?>
                            </div>    
                            <input type="hidden" name="posCandyCouponAmt" id="posCandyCouponAmt" class="posUserAmount" value="<?php echo $sumCandyCouponAmount; ?>">
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>    
                    
                    <div data-config-column="barter-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posBarterAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0091'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posBarterAmt" <?php echo issetParam($this->barterAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0091'); ?>" <?php echo issetParam($this->barterDisable) ? "readonly" : ""; echo Config::getFromCache('POS_IS_DISABLE_PAYMENT_BARTER') ? " readonly" : ""; ?> id="posBarterAmt" value="<?php echo issetParam($this->barterAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="emploan-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posEmpLoanAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0093'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posEmpLoanAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0093'); ?>" <?php echo issetParam($this->empLoanDisable) ? "readonly" : ""; echo Config::getFromCache('POS_IS_DISABLE_PAYMENT_EMPLOAN') ? " readonly" : ""; ?> id="posEmpLoanAmt" value="<?php echo issetParam($this->empLoanAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="emd-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posEmdAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0095'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posEmdAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0095'); ?>" id="posEmdAmt" readonly="readonly" value="<?php echo issetParam($this->emdAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="emd-insured-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posEmdInsuredAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0169'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posEmdInsuredAmt" class="form-control form-control-sm bigdecimalInit" placeholder="<?php echo $this->lang->line('POS_0169'); ?>" id="posEmdInsuredAmt" readonly="readonly" value="<?php echo issetParam($this->emdInsuredAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>

                    <div data-config-column="localexpense-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posLocalExpenseAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0094'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posLocalExpenseAmt" <?php echo issetParam($this->localExpenseAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" <?php echo (new Mdpos())->paymentTypeLocalExp() ? "readonly" : ""; ?> placeholder="<?php echo $this->lang->line('POS_0094'); ?>" id="posLocalExpenseAmt" value="<?php echo issetParam($this->localExpenseAmount); ?>">
                            </div>
                            <div class="col-md-4">
                                <div class="meta-autocomplete-wrap <?php echo issetParam($this->discountAmountDisable) ? "hidden" : "";?>" data-section-path="localExpenseCustomerId">
                                    <div class="input-group double-between-input">
                                        <input type="hidden" name="localExpenseCustomerId" id="localExpenseCustomerId_valueField" data-path="localExpenseCustomerId" class="popupInit">
                                        <input type="text" name="localExpenseCustomerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="localExpenseCustomerId" id="localExpenseCustomerId_displayField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="<?php echo Lang::line('code_search') ?>" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('localExpenseCustomerId', '1454315883636', '1536742182010', 'single', 'localExpenseCustomerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                        </span>  
                                        <span class="input-group-btn">
                                            <input type="text" name="localExpenseCustomerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="localExpenseCustomerId" id="localExpenseCustomerId_nameField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="<?php echo Lang::line('name_search') ?>" tabindex="-1" autocomplete="off">
                                        </span>   
                                    </div>
                                </div>                                
                            </div>                            
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <div data-config-column="certificate-amount"<?php echo Config::getFromCache('isPosCertificate') === '1' ? '' : ' class="hidden"' ?>>
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posCertificateExpenseAmt" style="padding-left: 0;">ЭРХИЙН БИЧИГ:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posCertificateExpenseAmt" <?php echo issetParam($this->certAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="ЭРХИЙН БИЧИГ" id="posCertificateExpenseAmt" value="">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="warrantyRepair-amount"<?php echo Config::getFromCache('CONFIG_POS_PAYMENT_VERIFIED_SERVICE') === '1' ? '' : ' class="hidden"' ?>>
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posWarrantyRepairAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0221'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posWarrantyRepairAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0221'); ?>" id="posWarrantyRepairAmt" value="">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="delivery-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posDeliveryAmt" style="padding-left: 0;">Үлдэгдэл төлбөр:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posDeliveryAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="Үлдэгдэл төлбөр" id="posDeliveryAmt">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="lendmn-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posLendMnAmt" style="padding-left: 0;">LendMn:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posLendMnAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="LendMn" id="posLendMnAmt">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                    
                    <div data-config-column="recievable-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase"><?php echo $this->lang->line('POS_0217'); ?>:</label>
                            <div class="col-md-9 pos-recievable-row-dtl">
                                <?php
                                $sumAccountTransferAmount = 0;

                                if (isset($this->recievableAmountList) && !empty($this->recievableAmountList)) {                                    
                                    foreach ($this->recievableAmountList as $tk => $accountTransferRow) {
                                ?>
                                <div class="row pos-recievable-row">
                                    <div class="pos-payment-amount-col">
                                        <input type="text" name="posRecievableAmtDtl[]" class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0217'); ?>" value="<?php echo $accountTransferRow['recievableAmount']; ?>">
                                    </div>
                                    <div class="col-md-5 pr0">
                                        <div class="meta-autocomplete-wrap w-100" data-section-path="recievableId">
                                            <div class="input-group double-between-input">
                                                <input type="hidden" name="recievableCustomerId[]" id="recievableId_valueField" data-path="recievableId" class="popupInit" value="<?php echo $accountTransferRow['customerId']; ?>">
                                                <input type="text" name="recievableId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" value="<?php echo $accountTransferRow['code']; ?>" id="recievableId_displayField" data-processid="1454315883636" data-lookupid="1579598747132" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('recievableId', '1454315883636', '<?php echo Config::getFromCacheDefault('CONFIG_POS_PAYMENT_RECEIVABLE', null, 0); ?>', 'single', 'recievableId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                                </span>  
                                                <span class="input-group-btn">
                                                    <input type="text" name="recievableId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" id="recievableId_nameField" data-processid="1454315883636" value="<?php echo $accountTransferRow['name']; ?>" data-lookupid="1579598747132" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                </span>   
                                            </div>
                                        </div>      
                                    </div>    
                                </div>
                                <?php
                                    $sumAccountTransferAmount += $accountTransferRow['recievableAmount'];
                                    }
                                }
                                ?>
                            </div>    
                            <input type="hidden" name="posRecievableAmt" id="posRecievableAmt" class="posUserAmount" value="<?php echo $sumAccountTransferAmount; ?>">
                            <div class="clearfix w-100"></div>
                        </div>                        

                        <!-- <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posRecievableAmt" style="padding-left: 0;">Авлага:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posRecievableAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="Авлага" id="posRecievableAmt" <?php echo issetParam($this->recievableDisable) ? "readonly" : "" ?> value="<?php echo issetParam($this->recievableAmount) ? $this->recievableAmount['recievableAmount'] : ''; ?>">
                            </div>
                            <div class="col-md-4" style="padding-right: 14px;">
                                <select name="recievableCustomerId" id="recievableCustomerId" data-path="recievableCustomerId" class="form-control form-control-sm select2" data-row-data="{&quot;META_DATA_ID&quot;:&quot;<?php echo Config::getFromCacheDefault('CONFIG_POS_PAYMENT_RECEIVABLE', null, 0); ?>&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;customername&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;cityId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                                    <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                                </select>
                            </div>
                            <div class="clearfix w-100"></div>
                        </div> -->
                    </div>

                    <div data-config-column="licieng-amount"<?php echo Config::getFromCache('isPosLicieng') === '1' ? '' : ' class="hidden"' ?>>
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posLiciengExpenseAmt" style="padding-left: 0;">ХЭРЭГЛЭЭНИЙ ЛИЗИНГ:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posLiciengExpenseAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" placeholder="ХЭРЭГЛЭЭНИЙ ЛИЗИНГ" id="posLiciengExpenseAmt" value="<?php echo issetParam($this->liciengExpenseAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>    
                    
                    <div data-config-column="tcard-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posTcardAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0931'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posTcardAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0931'); ?>" id="posTcardAmt" value="<?php echo issetParam($this->tcardAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <div data-config-column="shoppy-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posShoppyAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0932'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posShoppyAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0932'); ?>" id="posShoppyAmt" value="<?php echo issetParam($this->shoppyAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <div data-config-column="glmtreward-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posGlmtRewardAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0933'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posGlmtRewardAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0933'); ?>" id="posGlmtRewardAmt" value="<?php echo issetParam($this->glmtRewardAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <div data-config-column="socialpayreward-amount">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posSocialpayrewardAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_0934'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posSocialpayrewardAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0934'); ?>" id="posSocialpayrewardAmt" value="<?php echo issetParam($this->socialPayRewardAmount); ?>">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <div data-config-column="tax-invoice-amount" class="hidden">
                        <hr />

                        <div class="form-group row fom-row mb5">
                            <label class="col-md-3 col-form-label text-right uppercase" for="posTaxInvoiceAmt" style="padding-left: 0;"><?php echo $this->lang->line('POS_1115'); ?>:</label>
                            <div class="pos-payment-amount-col">
                                <input type="text" name="posTaxInvoiceAmt" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_1115'); ?>" id="posTaxInvoiceAmt" value="">
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>                    
                    
                    <hr />

                    <div class="form-group row fom-row mb5<?php echo Config::getFromCacheDefault('POS_FILL_CASH_AMOUNT_PAYMENT', null, '0') ? ' hidden' : '' ?>">
                        <label class="col-md-3 col-form-label text-right pl0 uppercase" for="posPaidAmount" style="font-size: 19px"><?php echo $this->lang->line('MET_99990184'); ?>:</label>
                        <div class="pos-payment-amount-col">
                            <input type="text" name="paidAmount" class="form-control form-control-sm bigdecimalInit" id="posPaidAmount" placeholder="<?php echo $this->lang->line('MET_99990184'); ?>" readonly="readonly" value="<?php echo issetParam($this->paidAmount); ?>">
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>
                    
                    <hr />

                    <div class="form-group row fom-row mb5<?php echo Config::getFromCacheDefault('POS_FILL_CASH_AMOUNT_PAYMENT', null, '0') ? ' hidden' : '' ?>">
                        <label class="col-md-3 col-form-label text-right pl0 uppercase" for="posBalanceAmount" style="font-size: 19px"><?php echo $this->lang->line('POS_0182'); ?>:</label>
                        <div class="pos-payment-amount-col">
                            <input type="text" class="form-control form-control-sm bigdecimalInit" id="posBalanceAmount" placeholder="<?php echo $this->lang->line('POS_0182'); ?>" readonly="readonly">
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>
                    
                    <hr />

                    <div class="form-group row fom-row mb10<?php echo Config::getFromCacheDefault('POS_FILL_CASH_AMOUNT_PAYMENT', null, '0') ? ' hidden' : '' ?>">
                        <label class="col-md-3 col-form-label text-right pl0 uppercase" for="posChangeAmount" style="font-size: 19px"><?php echo $this->lang->line('POS_0096'); ?>:</label>
                        <div class="pos-payment-amount-col">
                            <input type="text" name="changeAmount" class="form-control form-control-sm bigdecimalInit" id="posChangeAmount" placeholder="<?php echo $this->lang->line('POS_0096'); ?>" readonly="readonly">
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>

                </div>    
            </div>    
            <div class="pos-payment-footer">
                <div class="row">
                    <div class="col-md-8">
                        <?php echo $this->lang->line('POS_0212'); ?>: 
                        
                        <div class="btn-group btn-group-toggle ml5" data-toggle="buttons">
                            <label class="btn btn-secondary<?php echo $this->printCopies[0]['active']; ?>">
                                <input type="radio" name="posPrintCopies" class="notuniform" value="0"<?php echo $this->printCopies[0]['checked']; ?>><i class="fa fa-times-circle"></i>
                            </label>
                            <label class="btn btn-secondary<?php echo $this->printCopies[1]['active']; ?>">
                                <input type="radio" name="posPrintCopies" class="notuniform" value="1"<?php echo $this->printCopies[1]['checked']; ?>> 1
                            </label>
                            <label class="btn btn-secondary<?php echo $this->printCopies[2]['active']; ?>">
                                <input type="radio" name="posPrintCopies" class="notuniform" value="2"<?php echo $this->printCopies[2]['checked']; ?>> 2
                            </label>
                            <label class="btn btn-secondary<?php echo $this->printCopies[3]['active']; ?>">
                                <input type="radio" name="posPrintCopies" class="notuniform" value="3"<?php echo $this->printCopies[3]['checked']; ?>> 3
                            </label>
                            <label class="btn btn-secondary<?php echo $this->printCopies[4]['active']; ?>">
                                <input type="radio" name="posPrintCopies" class="notuniform" value="4"<?php echo $this->printCopies[4]['checked']; ?>> 4
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-block btn-circle green-meadow posPaymentBtn uppercase" onclick="posPayment();">
                            <?php echo $this->lang->line('print_btn'); ?> (F5)
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-4 pos-payment-right-area <?php echo Config::getFromCache('CONFIG_POS_TEMP_INVOICE_KEY_FIELD') === 'locker' ? 'hidden' : '' ?>">
            
            <div class="card-group-control card-group-control-right" id="pos-payment-accordion">
                
                <?php if (Config::getFromCache('CONFIG_POS_IS_USE_REMAINDER_COUPON')) { ?>
                    <div class="card mb5">
                        <div class="card-header" style="padding-left: 5px !important;">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-default" href="#pos-payment-accordion-bonus-card">
                                    <?php echo $this->lang->line('POS_0088'); ?>
                                </a>
                            </h6>
                        </div>
                        <div id="pos-payment-accordion-bonus-card" class="collapse <?php Session::get(SESSION_PREFIX.'posTypeCode') == '3' ? '' : 'show'; ?>" data-parent="#pos-payment-accordion">
                            <div class="card-body">
                                <div class="col">
                                    <div class="form-group row fom-row">
                                        <label class="col-form-label panel-title" for="cardOwnerNameCoupon"><?php echo $this->lang->line('POS_0191'); ?>:</label>
                                        <input type="text" id="cardOwnerNameCoupon" class="form-control" placeholder="<?php echo $this->lang->line('POS_0191'); ?>" autocomplete="off" readonly="readonly">
                                    </div>
                                    <div class="form-group row fom-row">
                                        <label class="col-form-label panel-title" for="cardBeginAmountCoupon"><?php echo $this->lang->line('POS_0192'); ?>:</label>
                                        <input type="text" name="cardBeginAmountCoupon" id="cardBeginAmountCoupon" class="form-control bigdecimalInit" placeholder="<?php echo $this->lang->line('POS_0192'); ?>" autocomplete="off" readonly="readonly">
                                    </div>
                                    <div class="form-group row fom-row">
                                        <label class="col-form-label panel-title" for="cardEndAmountCoupon"><?php echo $this->lang->line('POS_0195'); ?>:</label>
                                        <input type="text" name="cardEndAmountCoupon" id="cardEndAmountCoupon" class="form-control bigdecimalInit" placeholder="<?php echo $this->lang->line('POS_0195'); ?>" autocomplete="off" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    

                <?php } ?>

                <?php if (Config::getFromCache('POS_CONFIG_LOTTERY_SENT_EMAIL') == '1') { ?>
                <div class="card mb5">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-lottery-email">
                                Сугалаа имэйлээр илгээх
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-lottery-email" class="collapse show" data-parent="#pos-payment-accordion">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="isLotterySendEmail">Сугалаа имэйлээр илгээх эсэх:</label>
                                    <input type="checkbox" name="isLotterySendEmail" id="isLotterySendEmail" class="form-control" value="1">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="lotteryEmail"><?php echo Str::firstUpper($this->lang->line('email')); ?>:</label>
                                    <input type="text" name="lotteryEmail" id="lotteryEmail" class="form-control" placeholder="<?php echo Str::firstUpper($this->lang->line('email')); ?>" autocomplete="off">
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                <?php } ?>

                <div class="card mb5 <?php echo Config::get('POS_HIDE_ADDITIONAL_CUSTOMER_INFO', 'postype='.Session::get(SESSION_PREFIX.'posTypeCode')) === '1' ? 'hidden' : '' ?>">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-account-transfer">
                                <?php echo $this->lang->line('FIN_01199'); ?>
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-account-transfer" class="collapse<?php echo Config::getFromCache('POS_CONFIG_LOTTERY_SENT_EMAIL') == '1' || Session::get(SESSION_PREFIX.'posTypeCode') == '3' ? '' : ' show' ?>" data-parent="#pos-payment-accordion">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="invInfoInvoiceNumber"><?php echo $this->lang->line('POS_0168'); ?>:</label>
                                    <input type="text" name="invInfoInvoiceNumber" id="invInfoInvoiceNumber" class="form-control invInfoField invInfoFieldAll" placeholder="<?php echo $this->lang->line('POS_0168'); ?>" autocomplete="off">
                                </div>
                                <div class="form-group row fom-row<?php echo Config::getFromCache('configPosIsHideRecipe') == '1' ? ' hidden' : '' ?>">
                                    <label class="col-form-label panel-title" for="invInfoBookNumber"><?php echo $this->lang->line('POS_0183'); ?>:</label>
                                    <input type="text" name="invInfoBookNumber" id="invInfoBookNumber" class="form-control invInfoField invInfoFieldAll" placeholder="<?php echo $this->lang->line('POS_0183'); ?>" autocomplete="off">
                                </div>

                                <?php
                                if (isset($this->isCusBankInfo)) {
                                ?>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title"><?php echo $this->lang->line('Банк'); ?>:</label>
                                    <?php echo str_replace('posBankIdDtl[]', 'customerBankId', $this->bankCombo); ?>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="customerBankAccount"><?php echo $this->lang->line('Дансны дугаар'); ?>:</label>
                                    <input type="text" name="customerBankAccount" id="customerBankAccount" class="form-control invInfoField" placeholder="<?php echo $this->lang->line('Дансны дугаар'); ?>" autocomplete="off" data-inputmask-regex="^[0-9]{1,10}$">
                                </div>
                                <?php
                                }
                                ?>

                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="invInfoCustomerLastName"><?php echo $this->lang->line('Харилцагчийн овог'); ?>:</label>
                                    <input type="text" name="invInfoCustomerLastName" id="invInfoCustomerLastName" class="form-control invInfoField invInfoFieldAll" placeholder="<?php echo $this->lang->line('Харилцагчийн овог'); ?>" autocomplete="off" value="<?php echo $this->invInfoCustomerLastName; ?>" data-inputmask-regex="^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ| -]{1,60}$">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="invInfoCustomerName"><?php echo $this->lang->line('POS_0184'); ?>:</label>
                                    <input type="text" name="invInfoCustomerName" id="invInfoCustomerName" class="form-control invInfoField invInfoFieldAll" placeholder="<?php echo $this->lang->line('POS_0184'); ?>" autocomplete="off" value="<?php echo $this->invInfoCustomerName; ?>" data-inputmask-regex="^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ| -]{1,60}$">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="invInfoCustomerRegNumber"><?php echo $this->lang->line('POS_0185'); ?>:</label>
                                    <input type="text" name="invInfoCustomerRegNumber" id="invInfoCustomerRegNumber" class="form-control invInfoField invInfoFieldAll" placeholder="<?php echo $this->lang->line('POS_0185'); ?>" autocomplete="off" value="<?php echo $this->invInfoCustomerRegNumber; ?>">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="invInfoPhoneNumber">Утасны дугаар:</label>
                                    <input type="text" name="invInfoPhoneNumber" id="invInfoPhoneNumber" class="form-control invInfoField invInfoFieldAll" placeholder="Утасны дугаар" autocomplete="off" data-inputmask-regex="^[0-9]{1,8}$" value="<?php echo $this->invInfoPhoneNumber; ?>">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="invInfoEmail">Имэйл:</label>
                                    <input type="text" name="invInfoEmail" id="invInfoEmail" class="form-control invInfoField invInfoFieldAll" placeholder="Имэйл" autocomplete="off" value="">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="invInfoTransactionValue"><?php echo $this->lang->line('POS_0186'); ?>:</label>
                                    <textarea name="invInfoTransactionValue" id="invInfoTransactionValue" class="form-control invInfoFieldAll" placeholder="<?php echo $this->lang->line('POS_0186'); ?>" rows="2"><?php echo $this->invInfoTransactionValue; ?></textarea>
                                </div>

                                <div class="form-group row fom-row">
                                    <label for="serviceCustomerId_displayField"><?php echo $this->lang->line('choose_customer'); ?>:</label>
                                    <div class="w-100"></div>
                                    
                                    <div class="meta-autocomplete-wrap w-100" data-section-path="serviceCustomerId">
                                        <div class="input-group double-between-input">
                                            <input type="hidden" name="serviceCustomerId" id="serviceCustomerId_valueField" data-path="serviceCustomerId" class="popupInit" value="<?php echo issetParam($this->invInfoCustomerId) ?>">
                                            <input type="text" name="serviceCustomerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" id="serviceCustomerId_displayField" data-processid="1454315883636" data-lookupid="1522946988985" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" value="<?php echo issetParam($this->invInfoCustomerCode) ?>">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('serviceCustomerId', '1454315883636', '1522946988985', 'single', 'serviceCustomerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                            </span>  
                                            <span class="input-group-btn">
                                                <input type="text" name="serviceCustomerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" id="serviceCustomerId_nameField" data-processid="1454315883636" data-lookupid="1522946988985" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" value="<?php echo issetParam($this->invInfoCustomerName) ?>">
                                            </span>   
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <hr class="w-100" style="margin: 10px 0"/>
                                </div>

                                <input type="hidden" name="newServiceCustomerJson" id="newServiceCustomerJson">

                                <div class="row">
                                    <div class="col-md-8 pl0">
                                        <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posNewServiceCustomer(this);"><?php echo $this->lang->line('POS_0187'); ?></button>
                                    </div>    
                                    <div class="col-md-4 pr0">
                                        <button type="button" class="btn btn-block btn-circle grey-cascade btn-sm" onclick="posNewServiceCustomerCancel(this);"><?php echo $this->lang->line('POS_0188'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                
                <div class="card mb5 pos-payment-delivery-header">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-accordion-delivery">
                                <?php echo $this->lang->line('POS_0189'); ?>
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-accordion-delivery" class="collapse " data-parent="#pos-payment-accordion">
                        <div class="card-body">
                            <div class="col">
                                <?php if (Config::getFromCache('configPosIsHideAddress') != '1' && $this->isDelivery == '1') { ?>
                                    <div class="form-group row fom-row">
                                        <label class="col-form-label panel-title" for="coordinate">Google map:</label>
                                        <div class="input-group gmap-set-coordinate-control">
                                            <input type="text" name="coordinate" id="coordinate" class="form-control form-control-sm coordinateInit" readonly="1"/>
                                            <span class="input-group-btn">
                                                <button onclick="setGMapCoordinate(this); return false;" placeholder="Coordinate" class="btn btn-sm blue mr0 pt3 pb0"><i class="fa fa-map-marker"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="what3words">What3words:</label>
                                    <input type="text" name="what3words" id="what3words" class="form-control posAddressField" placeholder="What3words" autocomplete="off">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="recipientName"><?php echo $this->lang->line('POS_0171'); ?>:</label>
                                    <input type="text" name="recipientName" id="recipientName" class="form-control posAddressField" placeholder="<?php echo $this->lang->line('POS_0171'); ?>" autocomplete="off">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cityId"><?php echo $this->lang->line('POS_0172'); ?>:</label>
                                    <select name="cityId" id="cityId" data-path="cityId" class="form-control form-control-sm select2 posAddressField" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1446632274202&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;cityId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                                        <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                                    </select>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="districtId"><?php echo $this->lang->line('POS_0173'); ?>:</label>
                                    <select name="districtId" id="districtId" data-path="districtId" disabled="disabled" data-criteria-param="cityId@cityId" class="form-control form-control-sm select2 posAddressField" data-row-data="{&quot;META_DATA_ID&quot;:&quot;144436175673444&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;districtId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                                        <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                                    </select>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="streetId"><?php echo $this->lang->line('POS_0174'); ?>:</label>
                                    <select name="streetId" id="streetId" data-path="streetId" disabled="disabled" data-criteria-param="districtId@districtId" class="form-control form-control-sm select2 posAddressField" data-row-data="{&quot;META_DATA_ID&quot;:&quot;144436196690182&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;streetId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                                        <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                                    </select>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="detailAddress"><?php echo $this->lang->line('POS_0175'); ?>:</label>
                                    <textarea name="detailAddress" id="detailAddress" class="form-control posAddressField" placeholder="<?php echo $this->lang->line('POS_0175'); ?>" rows="2"></textarea>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="descriptionAddress"><?php echo $this->lang->line('POS_0129'); ?>:</label>
                                    <textarea name="descriptionAddress" id="descriptionAddress" class="form-control posAddressField" placeholder="<?php echo $this->lang->line('POS_0129'); ?>" rows="2"></textarea>
                                </div>

                                <div class="form-group row fom-row">
                                    <div class="col-md-6">
                                        <div class="form-group row fom-row mb0">
                                            <label class="col-form-label panel-title" for="phone1"><?php echo $this->lang->line('phone'); ?> 1:</label>
                                            <input type="text" name="phone1" id="phone1" class="form-control posAddressField" placeholder="<?php echo $this->lang->line('phone'); ?> 1" autocomplete="off" data-inputmask-regex="^[0-9]{1,8}$">
                                        </div>
                                    </div>   
                                    <div class="col-md-6 pl20">
                                        <div class="form-group row fom-row mb0">
                                            <label class="col-form-label panel-title" for="phone2"><?php echo $this->lang->line('phone'); ?> 2:</label>
                                            <input type="text" name="phone2" id="phone2" class="form-control posAddressField" placeholder="<?php echo $this->lang->line('phone'); ?> 2" autocomplete="off" data-inputmask-regex="^[0-9]{1,8}$">
                                        </div>
                                    </div>    
                                </div>

                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="dueDate"><?php echo $this->lang->line('POS_0209'); ?>:</label>
                                    <input type="text" name="dueDate" id="dueDate" class="form-control dateInit posAddressField" placeholder="<?php echo $this->lang->line('POS_0209'); ?>" autocomplete="off" value="<?php echo Date::currentDate('Y-m-d'); ?>">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="timeZoneId">Цагийн бүс:</label>
                                    <select name="timeZoneId" id="timeZoneId" data-path="timeZoneId" class="form-control form-control-sm select2 posAddressField" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1571915480228469&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;timeZoneId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1522036719483&quot;,&quot;CHOOSE_TYPE&quot;:&quot;single&quot;}">
                                        <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                                    </select>
                                </div>                                    
                            </div>    
                        </div>
                    </div>
                </div>    
                
                <div class="card mb5">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-accordion-bonus-card2">
                                <?php echo $this->lang->line('MET_99990557'); ?>
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-accordion-bonus-card2" class="collapse <?php echo Session::get(SESSION_PREFIX.'posTypeCode') == '3' ? 'show' : '' ?>" data-parent="#pos-payment-accordion">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <?php
                                    if (Config::getFromCache('CONFIG_POS_ONLY_CARDNUMBER')) {
                                    ?>
                                    <label class="col-form-label panel-title" for="cardNumber"><?php echo $this->lang->line('MET_330824'); ?>:</label>
                                    <div class="input-group">
                                        <input type="text" name="cardNumber" id="cardNumber" class="form-control" placeholder="<?php echo $this->lang->line('MET_330824'); ?>" style="font-weight: bold;font-size: 17px;" autocomplete="off" readonly="readonly">
                                        <input type="hidden" name="cardMemberShipId" id="cardMemberShipId">
                                        <input type="hidden" name="cardId" id="cardId">
                                        <span class="input-group-btn">
                                            <button class="btn yellow-casablanca btn-sm" type="button" style="padding-top: 2px;padding-bottom: 3px;" onclick="posNFCCardRead(this);"><?php echo $this->lang->line('POS_0208'); ?></button>
                                        </span>
                                    </div>
                                    <?php
                                    } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-6 pr5">
                                            <label class="col-form-label panel-title" for="cardNumber"><?php echo $this->lang->line('MET_330824'); ?>:</label>
                                            <input type="text" name="cardNumber" id="cardNumber" class="form-control" placeholder="<?php echo $this->lang->line('MET_330824'); ?>" style="font-weight: bold;font-size: 17px;" autocomplete="off">
                                            <input type="hidden" name="cardMemberShipId" id="cardMemberShipId">
                                            <input type="hidden" name="cardId" id="cardId">
                                        </div>
                                        <div class="col-md-6 pl5">
                                            <label class="col-form-label panel-title" for="cardPhoneNumber"><?php echo $this->lang->line('POS_0128'); ?>:</label>
                                            <input type="text" name="cardPhoneNumber" id="cardPhoneNumber" class="form-control" placeholder="<?php echo $this->lang->line('POS_0128'); ?>" style="font-weight: bold;font-size: 17px;" autocomplete="off">
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cardPinCode"><?php echo $this->lang->line('POS_0190'); ?>:</label>
                                    <input type="password" name="cardPinCode" id="cardPinCode" class="form-control readonly-white-bg" placeholder="<?php echo $this->lang->line('POS_0190'); ?>" autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');">
                                </div>
                                <div class="form-group row fom-row mt15 hidden">
                                    <label class="col-form-label panel-title" for="cardDiscountType">Нэмэх (+):</label>
                                    <input type="radio" name="cardDiscountType" id="cardDiscountType" class="form-control" value="+">
                                    <label class="col-form-label panel-title ml20" for="cardDiscountType2">Хасах (-):</label>
                                    <input type="radio" name="cardDiscountType" id="cardDiscountType2" class="form-control" value="-">
                                </div>

                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cardOwnerFirstName">Эзэмшигчийн овог:</label>
                                    <input type="text" id="cardOwnerFirstName" class="form-control" placeholder="Эзэмшигчийн овог" autocomplete="off" readonly="readonly">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cardOwnerName"><?php echo $this->lang->line('POS_0191'); ?>:</label>
                                    <input type="text" id="cardOwnerName" class="form-control" placeholder="<?php echo $this->lang->line('POS_0191'); ?>" autocomplete="off" readonly="readonly">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cardOwnerRegisterNumber">Регистэрийн дугаар:</label>
                                    <input type="text" id="cardOwnerRegisterNumber" class="form-control" placeholder="Регистэрийн дугаар" autocomplete="off" readonly="readonly">
                                </div>                                
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cardOwnerBirthday">Төрсөн өдөр:</label>
                                    <input type="text" id="cardOwnerBirthday" class="form-control" placeholder="Төрсөн өдөр" autocomplete="off" readonly="readonly">
                                </div>                                
                                <div class="form-group row fom-row <?php echo Config::getFromCacheDefault('POS_NEXT_DISCOUNT_PAYMENT', null, '0') == 0 ? '' : 'hidden'; ?>">
                                    <label class="col-form-label panel-title" for="cardBeginAmount"><?php echo $this->lang->line('POS_0192'); ?>:</label>
                                    <input type="text" name="cardBeginAmount" id="cardBeginAmount" class="form-control bigdecimalInit" placeholder="<?php echo $this->lang->line('POS_0192'); ?>" autocomplete="off" readonly="readonly">
                                </div>
                                <div class="form-group row fom-row <?php echo Config::getFromCacheDefault('POS_NEXT_DISCOUNT_PAYMENT', null, '0') == 0 ? '' : 'hidden'; ?>">
                                    <label class="col-form-label panel-title" for="cardDiscountPercentAmount">Нэмэгдэх бонусын дүн: / <span id="cardDiscountPercent-label" style="font-weight: bold">0</span> % /</label>
                                    <input type="text" name="cardDiscountPercentAmount" id="cardDiscountPercentAmount" class="form-control bigdecimalInit" placeholder="Урамшуулах/Хөнгөлөх дүн" autocomplete="off" readonly="readonly">
                                    <input type="hidden" name="cardDiscountPercent" id="cardDiscountPercent">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cardEndAmount"><?php echo $this->lang->line('POS_0195'); ?>:</label>
                                    <input type="text" name="cardEndAmount" id="cardEndAmount" class="form-control bigdecimalInit" placeholder="<?php echo $this->lang->line('POS_0195'); ?>" autocomplete="off" readonly="readonly">
                                </div>
                                <div class="form-group row fom-row <?php echo Config::getFromCacheDefault('POS_NEXT_DISCOUNT_PAYMENT', null, '0') == 0 ? '' : 'hidden'; ?>">
                                    <label class="col-form-label panel-title" for="cardPayPercentAmount">Зарцуулагдах боломжит дүн: / <span id="cardPayPercent-label" style="font-weight: bold">0</span> % /</label>
                                    <input type="text" name="cardPayPercentAmount" id="cardPayPercentAmount" class="form-control bigdecimalInit" placeholder="Зарцуулагдах боломжит дүн" autocomplete="off" readonly="readonly">
                                </div>                                    

                                <input type="hidden" name="newCardCustomerJson" id="newCardCustomerJson">
                                
                                <div class="row mt15">
                                    <div class="col-md-8 pl0">
                                        <button type="button" class="btn btn-block btn-circle blue-madison btn-sm" onclick="posNewCardCustomer(this);"><?php echo $this->lang->line('POS_0196'); ?></button>
                                    </div>    
                                    <div class="col-md-4 pr0">
                                        <button type="button" class="btn btn-block btn-circle grey-cascade btn-sm" onclick="posNewCardCustomerCancel(this);"><?php echo $this->lang->line('POS_0188'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>              
                
                <?php if (Config::get('UPOINT_API')) { ?>
                <div class="card mb5">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default pos-payment-accordion-upoint" href="#pos-payment-accordion-upoint">
                                U-point
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-accordion-upoint" class="collapse">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="upointPayAmount">Upoint бодох дүн:</label>
                                    <input type="text" readonly="readonly" name="upointPayAmount" id="upointPayAmount" class="form-control bigdecimalInit" value="<?php echo issetParam($this->upointAmount) ? issetParam($this->upointAmount['calcAmount']) : ''; ?>" autocomplete="off">
                                </div>                                
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="cardNumber"><?php echo $this->lang->line('MET_330824'); ?> / Утасны дугаар:</label>
                                    <div class="input-group">
                                        <input type="text" id="upointCardNumber2" class="form-control longInit" value="<?php echo issetParam($this->upointAmount) ? issetParam($this->upointAmount['bankcardnumber']) : ''; ?>" placeholder="<?php echo $this->lang->line('MET_330824'); ?>" style="font-weight: bold;font-size: 17px;" autocomplete="off;width: 90px;">
                                        <input type="hidden" name="upointCardNumber" id="upointCardNumber" class="form-control longInit" value="<?php echo issetParam($this->upointAmount) ? issetParam($this->upointAmount['bankcardnumber']) : ''; ?>" placeholder="<?php echo $this->lang->line('MET_330824'); ?>" style="font-weight: bold;font-size: 17px;" autocomplete="off;width: 90px;">
                                        <input type="hidden" name="upointCardId" id="upointCardId">
                                        <input type="hidden" name="upointMobile" id="upointMobile" value="<?php echo isset($this->headerData) ? issetParam($this->headerData['phoneNumber']) : ''; ?>" class="form-control longInit" placeholder="Утасны дугаар" style="font-weight: bold;font-size: 17px;" autocomplete="off">
                                        <input type="text" id="upointMobile2" value="<?php echo isset($this->headerData) ? issetParam($this->headerData['phoneNumber']) : ''; ?>" class="form-control longInit" placeholder="Утасны дугаар" style="font-weight: bold;font-size: 17px;" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="upointCardPinCode"><?php echo $this->lang->line('POS_0190'); ?>:</label>
                                    <input type="password" name="upointCardPinCode" id="upointCardPinCode" class="form-control readonly-white-bg" placeholder="<?php echo $this->lang->line('POS_0190'); ?>" autocomplete="off">
                                </div>
                                <div class="form-group row fom-row<?php echo issetParam($this->upointAmount) && issetParam($this->upointAmount['bankcardnumber']) ? ' d-none' : ''; ?>">
                                    <label class="col-form-label panel-title" for="upointIsCost">Зарцуулах эсэх:</label>
                                    <input type="checkbox" id="upointIsCost" name="upointIsCost">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="upointBalance">Хэрэглэгчийн онооны үлдэгдэл:</label>
                                    <input type="text" id="upointBalance" name="upointBalance" class="form-control" placeholder="" autocomplete="off" readonly="readonly">
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="upointCreated">Хэрэглэгчийн бүртгэгдсэн огноо</label>
                                    <input type="text" id="upointCreated" class="form-control" placeholder="" autocomplete="off" readonly="readonly">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                <?php } ?>

                <div class="card mb5 <?php echo Config::getFromCache('POS_VAT_PERIOD_MONTH') && Date::currentDate('d') <= Config::getFromCache('POS_VAT_PERIOD_MONTH') ? '' : 'hidden' ?>">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-account-reportmonth">
                                Баримт нөхөж бичих
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-account-reportmonth" class="collapse" data-parent="#pos-payment-accordion">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="reportmonth">Өмнөх сар нөхөж бичих</label>
                                    <input type="checkbox" name="reportmonth" id="reportmonth" class="form-control ml15" value="1"/>
                                </div>    
                            </div>
                        </div>    
                    </div>
                </div>                  

                <div class="card mb5 <?php echo Config::getFromCache('POS_PAY_LEFT_SIDE_SHOW_LEAD') ? '' : 'hidden' ?>">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-account-sejim">
                                Сэжим бүртгэх
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-account-sejim" class="collapse<?php echo Config::getFromCache('POS_CONFIG_LOTTERY_SENT_EMAIL') == '1' || Session::get(SESSION_PREFIX.'posTypeCode') == '3' ? '' : ' show' ?>" data-parent="#pos-payment-accordion">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="sejimPhoneNumber">Утасны дугаар:</label>
                                    <input type="hidden" name="sejimId" id="sejimId" class="form-control" placeholder="" value="">
                                    <input type="text" name="sejimPhoneNumber" id="sejimPhoneNumber" class="form-control" placeholder="Утасны дугаараа оруулаад enter дарна уу." autocomplete="off" data-inputmask-regex="^[0-9]{1,8}$" value="">
                                </div>                                
                                <div class="form-group row fom-row serjim-fields d-none">
                                    <label class="col-form-label panel-title" for="sejimLastName">Овог:</label>
                                    <input type="text" name="sejimLastName" id="sejimLastName" class="form-control" placeholder="Овог" autocomplete="off" value="">
                                </div>
                                <div class="form-group row fom-row serjim-fields d-none">
                                    <label class="col-form-label panel-title" for="sejimFirstName">Нэр:</label>
                                    <input type="text" name="sejimFirstName" id="sejimFirstName" class="form-control" placeholder="Нэр" autocomplete="off" value="">
                                </div>
                                <div class="form-group row fom-row serjim-fields d-none">
                                    <label class="col-form-label panel-title" for="sejimEmail">Имэйл:</label>
                                    <input type="text" name="sejimEmail" id="sejimEmail" class="form-control" placeholder="Имэйл" autocomplete="off" value="">
                                </div>
                                <div class="form-group row fom-row serjim-fields d-none">
                                    <label class="col-form-label panel-title" for="sejimGenderId">Хүйс:</label>
                                    <select name="sejimGenderId" id="sejimGenderId" class="form-control">
                                        <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                                    </select>
                                </div>
                                <div class="form-group row fom-row serjim-fields d-none">
                                    <label class="col-form-label panel-title" for="sejimAgeRange">Насны бүлэг:</label>
                                    <select name="sejimAgeRange" id="sejimAgeRange" class="form-control">
                                        <option value="">- <?php echo $this->lang->line('choose_btn'); ?> -</option>
                                    </select>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>                
                
                <?php if (Config::get('CONFIG_POS_IS_SHOW_ADDITIONAL_NUMBER')) { ?>
                <div class="card mb5">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-accordion-localphone">
                                Утасны дугаар
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-accordion-localphone" class="collapse">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="localCustomerPhone">Утасны дугаар:</label>
                                    <div class="input-group">
                                        <input type="text" name="localCustomerPhone" id="localCustomerPhone" class="form-control" value="" placeholder="" style="font-weight: bold;font-size: 17px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                <?php } ?>
                
                <?php
                if (Config::getFromCache('CONFIG_POS_GIFT')) {
                ?>                
                <div class="card mb5">
                    <div class="card-header" style="padding-left: 5px !important;">
                        <h6 class="card-title">
                            <a data-toggle="collapse" class="text-default" href="#pos-payment-accordion-bonus-card3">
                                Бэлэг
                            </a>
                        </h6>
                    </div>
                    <div id="pos-payment-accordion-bonus-card3" class="collapse" data-parent="#pos-payment-accordion">
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="pressureApparat">Даралтын аппарат: <input type="checkbox" id="pressureApparat" name="pressureApparat" value="1" class="form-control" autocomplete="off"></label>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="mixer">Холигч: <input type="checkbox" id="mixer" name="mixer" class="form-control" value="1" autocomplete="off"></label>
                                </div>
                                <div class="form-group row fom-row">
                                    <label class="col-form-label panel-title" for="airCleaner">Агаар цэвэршүүлэгч 50%: <input type="checkbox" id="airCleaner" name="airCleaner" class="form-control" value="1" autocomplete="off"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                <?php } ?>

                <?php if (Config::getFromCache('POS_IS_CHOOSE_INVOICE_RETURN_TYPE') && $this->reasonReturnHtml) { ?>
                    <div class="card mb5">
                        <div class="card-header" style="padding-left: 5px !important;">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-default" href="#pos-payment-accordion-reason-return">
                                    Талон буцаалтын шалтгаан
                                </a>
                            </h6>
                        </div>
                        <div id="pos-payment-accordion-reason-return" class="collapse show" data-parent="#pos-payment-accordion">
                            <div class="card-body">
                                <?php echo $this->reasonReturnHtml; ?>
                            </div>
                        </div>
                    </div>    
                <?php } ?>                
            </div>
            
        </div>
        <input type="hidden" name="giftPaymentJson">
    </div>
</form>

<script type="text/javascript">
$(function(){
    var returnTypeInvoice = $('#returnTypeInvoice').val();
    
    posPaymentBillType();
    
    setTimeout(function(){
        $('#posCashAmount').focus().select();
    }, 10);
    
    if (returnTypeInvoice == 'typeCancel' || returnTypeInvoice == 'typeReduce' || returnTypeInvoice == 'typeChange') {
        
        if ($('.pos-bank-row-dtl').children().length == 0) {
            $('.pos-bank-row-dtl').html($('script[data-template="bankrow"]').text());
        }
        if ($('.pos-voucher-row-dtl').children().length == 0) {
            $('.pos-voucher-row-dtl').html($('script[data-template="voucherrow"]').text());
        }
        if ($('.pos-voucher2-row-dtl').children().length == 0) {
            $('.pos-voucher2-row-dtl').html($('script[data-template="voucherrow2"]').text());
        }
        
        var $paymentForm = $('#pos-payment-form'), 
            returnNotDisableFields = '#pos-org-number, #pos-org-name';
        
        if (isReturnCustomerInfoRequired) {
            returnNotDisableFields += ', #invInfoCustomerLastName, #invInfoCustomerName, #invInfoCustomerRegNumber, #invInfoPhoneNumber';
        }
        
        if ($('#bp-window-1594091838794').length) {
            returnNotDisableFields += ',[data-field-name]';
            $('#bp-window-1594091838794').find('.meta-toolbar').hide();
        }
        $paymentForm.find('button.btn-circle, button.grey-cascade').not('.posPaymentBtn').prop('disabled', true);
        $paymentForm.find('input[type="text"], textarea, select').not(returnNotDisableFields).prop('readonly', true);
        $paymentForm.find('input[type="password"]').not('#upointCardPinCode').prop('disabled', true);
        
    } else {
        
        $('.pos-bank-row-dtl').html($('script[data-template="bankrow"]').text());
        $('.pos-voucher-row-dtl').html($('script[data-template="voucherrow"]').text());
        $('.pos-voucher2-row-dtl').html($('script[data-template="voucherrow2"]').text());
        $('.pos-prepayment-row-dtl').html($('script[data-template="prepaymentrow"]').text());
        $('.pos-accounttransfer-row-dtl').html($('script[data-template="accounttransferrow"]').text());
        $('.pos-recievable-row-dtl').html($('script[data-template="recievablerow"]').text());
        $('.pos-candy-row-dtl').html($('script[data-template="candyrow"]').text());
        $('.pos-upoint-row-dtl').html($('script[data-template="upointrow"]').text());
        $('.pos-candy-coupon-row-dtl').html($('script[data-template="candycouponrow"]').text());        
        
        var dataCriteria = '';
        var posEmpLoanAmt = Number($('#posEmpLoanAmt').val());
        var posBarterAmt = Number($('#posBarterAmt').val());

        if (posBarterAmt > 0) {
            dataCriteria = '5';
        }
            
        if (posEmpLoanAmt > 0) {
            if (dataCriteria != '') {
                dataCriteria += ',7';
            } else {
                dataCriteria = '7';
            }
        } 

        Core.initDecimalPlacesInput($("#pos-payment-form"));        
        
        if (dataCriteria != '') {
            $('#serviceCustomerId_valueField').attr('data-criteria', 'filterTypeId='+dataCriteria);
        }
    }
    
    <?php
    if ($this->isDelivery == '0') {
    ?>
    $('.pos-payment-delivery-header').hide();
    <?php
    } else {
    ?>
    $('.pos-payment-delivery-header').show();        
    <?php
    }
    ?>     
            
    var openInvoiceJsonStr = $('#invoiceJsonStr').val();
    if (openInvoiceJsonStr != '') {
        var invoiceJsonStrObj = JSON.parse(openInvoiceJsonStr);
        $('#invInfoInvoiceNumber').val(invoiceJsonStrObj.booknumber);

        if (isEditCustomerInfoBook === '1') {
            $('#serviceCustomerId_displayField').prop('readonly', true);
            $('#serviceCustomerId_nameField').prop('readonly', true);
            $('#serviceCustomerId_displayField').parent().find('button').prop('disabled', true);              
        }
    }
    
    if (!isConfigPaymentCoupon) {
        $('[data-config-column="coupon-amount"]').css({'display': 'none'});
    }

    if (!posRemainderCoupon) {
        $('[data-config-column="coupon2-amount"]').css({'display': 'none'});
    }
    if (!isConfigPrePayment) {
        $('[data-config-column="prepayment-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentBonuscard) {
        $('[data-config-column="bonuscard-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentDiscountActivity) {
        $('[data-config-column="discount-activity-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentInsurance) {
        $('[data-config-column="insurance-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentAccountTransfer) {
        $('[data-config-column="accounttransfer-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentMobilenet) {
        $('[data-config-column="mobilenet-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentOther) {
        $('[data-config-column="other-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentTcard) {
        $('[data-config-column="tcard-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentShoppy) {
        $('[data-config-column="shoppy-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentGlmtreward) {
        $('[data-config-column="glmtreward-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentSocialpayreward) {
        $('[data-config-column="socialpayreward-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentTaxInvoice) {
        $('[data-config-column="tax-invoice-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentBarter) {
        $('[data-config-column="barter-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentLeasing) {
        $('[data-config-column="leasing-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentEmpLoan) {
        $('[data-config-column="emploan-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentLocalExpense) {
        $('[data-config-column="localexpense-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentUnitReceivable) {
        $('[data-config-column="emd-amount"], [data-config-column="emd-insured-amount"]').css({'display': 'none'});
    } 
    if ((typeof isConfigPaymentCandy !== 'undefined' && !isConfigPaymentCandy) || typeof isConfigPaymentCandy == 'undefined') {
        $('[data-config-column="candy-amount"]').css({'display': 'none'});
    }
    if ((typeof isConfigPaymentUpoint !== 'undefined' && !isConfigPaymentUpoint) || typeof isConfigPaymentUpoint == 'undefined') {
        $('[data-config-column="upoint-amount"]').css({'display': 'none'});
    }
    if ((typeof isConfigPaymentCandyCoupon !== 'undefined' && !isConfigPaymentCandyCoupon) || typeof isConfigPaymentCandyCoupon == 'undefined') {
        $('[data-config-column="candy-coupon-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentDelivery) {
        $('[data-config-column="delivery-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentLendMn) {
        $('[data-config-column="lendmn-amount"]').css({'display': 'none'});
    }
    if (!isConfigPaymentReceivable) {
        $('[data-config-column="recievable-amount"]').css({'display': 'none'});
    }
    if (posUseIpTerminal === '1' && returnBillType == 'typeCancel') {
        $('[data-config-column="isNotUseIpterminal"]').removeClass('d-none');
    }
    
    <?php
    if (isset($this->addressInfo) && $this->isDelivery != '0') {
    ?>
        var $paymentDialog = $('#dialog-pos-payment');    
        Core.initClean($paymentDialog);
        
        $('#recipientName').val('<?php echo $this->addressInfo['contactname']; ?>');
        $('#detailAddress').val('<?php echo $this->addressInfo['address']; ?>');
        $('#phone1').val('<?php echo $this->addressInfo['phonenumber1']; ?>');
        $('#what3words').val('<?php echo issetParam($this->addressInfo['what3words']); ?>');
        $('#coordinate').val('<?php echo issetParam($this->addressInfo['coordinate']); ?>');

        <?php
        if (isset($this->addressInfo['cityid'])) {
        ?>
            $('select[name="cityId"]').trigger('select2-opening', [true]);
            $('select[name="cityId"]').select2('val', '<?php echo $this->addressInfo['cityid']; ?>');

            var $districtId = $('select#districtId');
            $districtId.select2('enable');
            $districtId.removeClass('data-combo-set');
        <?php
        }
        if (isset($this->addressInfo['districtid'])) {
        ?>
            $('select[name="districtId"]').trigger('select2-opening', [true]);
            $('select[name="districtId"]').select2('val', '<?php echo $this->addressInfo['districtid']; ?>');

            var $streetId = $('select#streetId');
            $streetId.select2('enable');
            $streetId.removeClass('data-combo-set');
        <?php
        } 
        if (isset($this->addressInfo['citystreetid'])) {
        ?>
            $('select[name="streetId"]').trigger('select2-opening', [true]);
            $('select[name="streetId"]').select2('val', '<?php echo $this->addressInfo['citystreetid']; ?>');
        <?php
        }
        if (isset($this->addressInfo['duedate'])) {
        ?>
        $('#dueDate').datetimepicker('update', '<?php echo Date::formatter($this->addressInfo['duedate'], 'Y-m-d H:i'); ?>');
        <?php
        }
        ?> 
    <?php
    }
    ?>        
            
    $('#invInfoCustomerLastName, #invInfoCustomerName').tooltip({'trigger':'focus', 'title': 'Кирил үсгээр бичнэ үү'});
});
</script>    

<script type="text/template" data-template="bankrow">
    <div class="row pos-bank-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="bankAmountDtl[]" class="form-control form-control-sm bigdecimalInit posUserAmount posKeyAmount" <?php echo issetParam($this->bankAmountDisable) ? "readonly" : "";?> placeholder="<?php echo $this->lang->line('POS_0207'); ?>">
            <input type="hidden" name="deviceRrn[]" />
            <input type="hidden" name="devicePan[]" />
            <input type="hidden" name="deviceAuthcode[]" />
            <input type="hidden" name="deviceTerminalId[]" />                
            <input type="hidden" name="deviceTraceNo[]" />                
        </div>
        <div class="col-md-5 pr0 <?php echo issetParam($this->bankAmountDisable) ? "hidden" : "";?>">
            <?php echo $this->bankCardCombo; ?>
        </div>    
        <div class="float-left pl10">
            <?php if (Session::get(SESSION_PREFIX.'posUseIpTerminal') == '1') { ?>
                <img style="height: 23px;cursor:pointer;margin-right: 4px;" class="copperCart hidden" onclick="posSaleBankTerminalCopper(this)" title="ЗЭС карт" src="data:image/png;base64,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6H7o/ujz8cGn1E+f/gUDmPP8usTo0wAAAAlwSFlzAAAOxAAADsQBlSsOGwAAOylpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIgogICAgICAgICAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgICAgICAgICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgICAgICAgICAgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIgogICAgICAgICAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICAgICAgICAgIHhtbG5zOnRpZmY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vdGlmZi8xLjAvIgogICAgICAgICAgICB4bWxuczpleGlmPSJodHRwOi8vbnMuYWRvYmUuY29tL2V4aWYvMS4wLyI+CiAgICAgICAgIDx4bXA6Q3JlYXRvclRvb2w+QWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKTwveG1wOkNyZWF0b3JUb29sPgogICAgICAgICA8eG1wOkNyZWF0ZURhdGU+MjAxNi0xMC0xN1QxNDo0Mzo1NyswODowMDwveG1wOkNyZWF0ZURhdGU+CiAgICAgICAgIDx4bXA6TWV0YWRhdGFEYXRlPjIwMTYtMTAtMTdUMTQ6NDM6NTcrMDg6MDA8L3htcDpNZXRhZGF0YURhdGU+CiAgICAgICAgIDx4bXA6TW9kaWZ5RGF0ZT4yMDE2LTEwLTE3VDE0OjQzOjU3KzA4OjAwPC94bXA6TW9kaWZ5RGF0ZT4KICAgICAgICAgPHhtcE1NOkluc3RhbmNlSUQ+eG1wLmlpZDpmNWE0MzgyMS04NGQ3LTU3NDItYjBmOS04ZTYyYTM3Nzk1ZDY8L3htcE1NOkluc3RhbmNlSUQ+CiAgICAgICAgIDx4bXBNTTpEb2N1bWVudElEPnhtcC5kaWQ6MjQxYTBlNDctMTE2MS03NTQwLTk5MDctMDAxODM5MGU4Yzg4PC94bXBNTTpEb2N1bWVudElEPgogICAgICAgICA8eG1wTU06T3JpZ2luYWxEb2N1bWVudElEPnhtcC5kaWQ6MjQxYTBlNDctMTE2MS03NTQwLTk5MDctMDAxODM5MGU4Yzg4PC94bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ+CiAgICAgICAgIDx4bXBNTTpIaXN0b3J5PgogICAgICAgICAgICA8cmRmOlNlcT4KICAgICAgICAgICAgICAgPHJkZjpsaSByZGY6cGFyc2VUeXBlPSJSZXNvdXJjZSI+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDphY3Rpb24+Y3JlYXRlZDwvc3RFdnQ6YWN0aW9uPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6aW5zdGFuY2VJRD54bXAuaWlkOjI0MWEwZTQ3LTExNjEtNzU0MC05OTA3LTAwMTgzOTBlOGM4ODwvc3RFdnQ6aW5zdGFuY2VJRD4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OndoZW4+MjAxNi0xMC0xN1QxNDo0Mzo1NyswODowMDwvc3RFdnQ6d2hlbj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OnNvZnR3YXJlQWdlbnQ+QWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKTwvc3RFdnQ6c29mdHdhcmVBZ2VudD4KICAgICAgICAgICAgICAgPC9yZGY6bGk+CiAgICAgICAgICAgICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0iUmVzb3VyY2UiPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6YWN0aW9uPnNhdmVkPC9zdEV2dDphY3Rpb24+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDppbnN0YW5jZUlEPnhtcC5paWQ6ZjVhNDM4MjEtODRkNy01NzQyLWIwZjktOGU2MmEzNzc5NWQ2PC9zdEV2dDppbnN0YW5jZUlEPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6d2hlbj4yMDE2LTEwLTE3VDE0OjQzOjU3KzA4OjAwPC9zdEV2dDp3aGVuPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6c29mdHdhcmVBZ2VudD5BZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpPC9zdEV2dDpzb2Z0d2FyZUFnZW50PgogICAgICAgICAgICAgICAgICA8c3RFdnQ6Y2hhbmdlZD4vPC9zdEV2dDpjaGFuZ2VkPgogICAgICAgICAgICAgICA8L3JkZjpsaT4KICAgICAgICAgICAgPC9yZGY6U2VxPgogICAgICAgICA8L3htcE1NOkhpc3Rvcnk+CiAgICAgICAgIDxwaG90b3Nob3A6RG9jdW1lbnRBbmNlc3RvcnM+CiAgICAgICAgICAgIDxyZGY6QmFnPgogICAgICAgICAgICAgICA8cmRmOmxpPnhtcC5kaWQ6NTE3MkZBMjgxNTIwNjgxMTgyMkFENjdCRTM2MUExNjI8L3JkZjpsaT4KICAgICAgICAgICAgICAgPHJkZjpsaT54bXAuZGlkOmJiYjQ0NWU0LWExZTctMmI0NC1hNDUwLTQ2MGU2ODAwNTNiZjwvcmRmOmxpPgogICAgICAgICAgICA8L3JkZjpCYWc+CiAgICAgICAgIDwvcGhvdG9zaG9wOkRvY3VtZW50QW5jZXN0b3JzPgogICAgICAgICA8cGhvdG9zaG9wOkNvbG9yTW9kZT4zPC9waG90b3Nob3A6Q29sb3JNb2RlPgogICAgICAgICA8cGhvdG9zaG9wOklDQ1Byb2ZpbGU+c1JHQiBJRUM2MTk2Ni0yLjE8L3Bob3Rvc2hvcDpJQ0NQcm9maWxlPgogICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL3BuZzwvZGM6Zm9ybWF0PgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpYUmVzb2x1dGlvbj4zMDAwMDAwLzEwMDAwPC90aWZmOlhSZXNvbHV0aW9uPgogICAgICAgICA8dGlmZjpZUmVzb2x1dGlvbj4zMDAwMDAwLzEwMDAwPC90aWZmOllSZXNvbHV0aW9uPgogICAgICAgICA8dGlmZjpSZXNvbHV0aW9uVW5pdD4yPC90aWZmOlJlc29sdXRpb25Vbml0PgogICAgICAgICA8ZXhpZjpDb2xvclNwYWNlPjE8L2V4aWY6Q29sb3JTcGFjZT4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjI3NDwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOlBpeGVsWURpbWVuc2lvbj4xNzI8L2V4aWY6UGl4ZWxZRGltZW5zaW9uPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAKPD94cGFja2V0IGVuZD0idyI/Pt6BmP8AACZnSURBVHheZZxZjKTXdd9PdXVV9Ta9zdIzHA5nITkUF2jhJsaSYBu0nQCxHeQtRmQlyoLIQBYgcJ6SByEC8pAHC8hLgkAB4iBygsB7HChIZIlaaEEZihJpUjbN4Wwczt7d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">
            <?php } ?>
            <button type="button" class="btn btn-circle btn-sm green" onclick="addPosBankRow(this);" data-bank-action="add" title="<?php echo $this->lang->line('addrow'); ?>"><i class="icon-plus3 font-size-12"></i></button><span class="infoShortcut" style="position: absolute;">(F11)</span>
        </div>
    </div>
</script>

<script type="text/template" data-template="voucherrow">
    <div class="row pos-voucher-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="voucherDtlAmount[]" class="form-control form-control-sm bigdecimalInit posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0088'); ?>" title="<?php echo $this->lang->line('POS_0088'); ?>" readonly="readonly">
        </div>
        <div class="col-md-5 pr0">
            <input type="text" name="voucherDtlSerialNumber[]" <?php echo issetParam($this->couponAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm pos-payment-input" placeholder="<?php echo $this->lang->line('POS_0197'); ?> (SHIFT+Z)" title="<?php echo $this->lang->line('POS_0197'); ?>">
            <input type="hidden" name="voucherDtlId[]">
            <input type="hidden" name="voucherTypeId[]">
        </div>    
        <div class="col-md-1">
            <button type="button" class="btn btn-circle btn-sm green" onclick="addPosVoucherRow(this);" data-voucher-action="add" title="<?php echo $this->lang->line('addrow'); ?>"><i class="icon-plus3 font-size-12"></i></button>
        </div>
    </div>
</script>

<script type="text/template" data-template="voucherrow2">
    <div class="row pos-voucher2-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="voucher2DtlAmount[]" class="form-control form-control-sm bigdecimalInit posKeyAmount" placeholder="<?php echo $this->lang->line('POS_0214'); ?>" title="<?php echo $this->lang->line('POS_0214'); ?>" readonly="readonly">
        </div>
        <div class="col-md-5 pr0">
            <input type="text" name="voucher2DtlSerialNumber[]" class="form-control form-control-sm pos-payment-input" placeholder="<?php echo $this->lang->line('POS_0197'); ?>" title="<?php echo $this->lang->line('POS_0197'); ?>">
            
            <input type="hidden" name="voucher2DtlId[]">
            <input type="hidden" name="voucher2TypeId[]">
        </div>
        <div class="col-md-1">
            <span class="voucherstramount line-height-normal"></span>
            <button type="button" class="btn btn-circle btn-sm green" onclick="addPosVoucher2Row(this);" data-voucher-action="add" title="<?php echo $this->lang->line('addrow'); ?>"><i class="icon-plus3 font-size-12"></i></button>
        </div>
    </div>
</script>

<script type="text/template" data-template="prepaymentrow">
    <div class="row pos-prepayment-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="prePyamentDtlAmount" class="form-control form-control-sm bigdecimalInit posKeyAmount" placeholder="<?php echo $this->lang->line('POS_GLOBE_PREPAYMENT'); ?>" title="<?php echo $this->lang->line('POS_GLOBE_PREPAYMENT'); ?>" readonly="readonly">
        </div>
        <div class="col-md-5 pr0">
            <div class="meta-autocomplete-wrap w-100" data-section-path="prePaymentCustomerId">
                <div class="input-group double-between-input">
                    <input type="hidden" name="prePaymentCustomerId" id="prePaymentCustomerId_valueField" data-path="prePaymentCustomerId" class="popupInit">
                    <input type="text" name="prePaymentCustomerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" id="prePaymentCustomerId_displayField" data-processid="1454315883636" data-lookupid="1579598747132" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('prePaymentCustomerId', '1454315883636', '1579598747132', 'single', 'prePaymentCustomerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                    </span>  
                    <span class="input-group-btn">
                        <input type="text" name="prePaymentCustomerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" id="prePaymentCustomerId_nameField" data-processid="1454315883636" data-lookupid="1579598747132" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                    </span>   
                </div>
            </div>        
        </div>    
        <div class="col-md-1">
        </div>
    </div>
</script>

<script type="text/template" data-template="accounttransferrow">
    <div class="row pos-accounttransfer-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="accountTransferAmountDtl[]" class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" <?php echo issetParam($this->accountTransferAmountDisable) ? "readonly" : "";?> placeholder="<?php echo $this->lang->line('POS_0089'); ?>">
            <input type="hidden" name="accountTransferBillingIdDtl[]">
            <input type="hidden" name="accountTransferDescrDtl[]">
        </div>
        <div class="col-md-5 pr0 <?php echo issetParam($this->accountTransferAmountDisable) ? "hidden" : "";?>">
            <?php echo str_replace('posBankIdDtl[]', 'accountTransferBankIdDtl[]', $this->bankTransferCombo); ?>
        </div>    
        <div class="float-left pl10">
            <button type="button" class="btn btn-circle btn-sm green" onclick="addPosAccountTransferRow(this);" data-row-action="add" title="<?php echo $this->lang->line('addrow'); ?>"><i class="icon-plus3 font-size-12"></i></button>
            <button type="button" class="btn btn-circle btn-sm blue" onclick="searchPosAccountStatement(this);" title="Хуулга хайх"><i class="fa fa-search"></i></button>
        </div>
    </div>
</script>

<script type="text/template" data-template="candyrow">
    <div class="row pos-candy-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="candyAmountDtl[]" class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" placeholder="Монпэй" readonly="readonly">
            <input type="hidden" name="candyTypeCodeDtl[]">
            <input type="hidden" name="candyDetectedNumberDtl[]">
            <input type="hidden" name="candyTransactionIdDtl[]">
        </div>
        <div class="float-left pl15">
            <button type="button" class="btn btn-circle btn-sm green" onclick="addPosCandyRow(this);" data-row-action="add" title="<?php echo $this->lang->line('addrow'); ?>"><i class="icon-plus3 font-size-12"></i></button>
            <button type="button" class="btn btn-circle btn-sm blue" onclick="posSearchCandy(this);" title="Монпэй QR"><i class="fa fa-qrcode"></i></button>
        </div>
    </div>
</script>

<script type="text/template" data-template="upointrow">
    <div class="row pos-upoint-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="upointAmountDtl[]" readonly class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" placeholder="U-POINT">
            <input type="hidden" name="upointTypeCodeDtl[]">
            <input type="hidden" name="upointDetectedNumberDtl[]">
            <input type="hidden" name="upointTransactionIdDtl[]">
        </div>
        <div class="float-left pl15">
            <button type="button" class="btn btn-circle btn-sm blue d-none" onclick="posSearchUpoint(this);" title="Мэдээлэл шалгах"><i class="fa fa-search"></i></button>
        </div>
    </div>
</script>

<script type="text/template" data-template="candycouponrow">
    <div class="row pos-candy-coupon-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="candyCouponAmountDtl[]" class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" placeholder="Монпэй купон" readonly="readonly">
            <input type="hidden" name="candyCouponTypeCodeDtl[]">
            <input type="hidden" name="candyCouponDetectedNumberDtl[]">
            <input type="hidden" name="candyCouponTransactionIdDtl[]">
        </div>
        <div class="float-left pl15">
            <button type="button" class="btn btn-circle btn-sm purple" onclick="posCoupenCandy(this);" title="Монпэй купон">Монпэй купон</button>
        </div>
    </div>
</script>

<script type="text/template" data-template="recievablerow">
    <div class="row pos-recievable-row">
        <div class="pos-payment-amount-col">
            <input type="text" name="posRecievableAmtDtl[]" <?php echo issetParam($this->recAmountDisable) ? "readonly" : "";?> class="form-control form-control-sm bigdecimalInit posKeyAmount invAmountField" placeholder="<?php echo $this->lang->line('POS_0217'); ?>">
        </div>
        <div class="col-md-5 pr0 <?php echo issetParam($this->recAmountDisable) ? "hidden" : "";?>">
            <div class="meta-autocomplete-wrap" data-section-path="recievableId">
                <div class="input-group double-between-input">
                    <input type="hidden" name="recievableCustomerId[]" id="recievableId_valueField" data-path="recievableId" class="popupInit">
                    <input type="text" name="recievableId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="recievableId" id="recievableId_displayField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="кодоор хайх" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('recievableId', '1454315883636', '<?php echo Config::getFromCacheDefault('CONFIG_POS_PAYMENT_RECEIVABLE', null, 0); ?>', 'single', 'recievableId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                    </span>  
                    <span class="input-group-btn">
                        <input type="text" name="recievableId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="recievableId" id="recievableId_nameField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="нэрээр хайх" tabindex="-1" autocomplete="off">
                    </span>   
                </div>
            </div>
        </div>    
        <div class="float-left pl10">
            <button type="button" class="btn btn-circle btn-sm green" onclick="addPosRecievableRow(this);" data-row-action="add" title="<?php echo $this->lang->line('addrow'); ?>"><i class="icon-plus3 font-size-12"></i></button>
        </div>
    </div>
</script>

<style type="text/css">
    .pos-payment-amount-col {
        float: left;
        position: relative;
        width: 210px;
        padding: 0;
    }
</style>
