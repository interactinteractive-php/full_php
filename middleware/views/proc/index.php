<div class="w-100" id="windowIdProc<?php echo $this->uniqId ?>">
    <form method="post" enctype="multipart/form-data">
    <?php echo Form::hidden(array('name' => 'windowSessionId', 'value' => issetParam($this->uniqId))); ?>
    <div class="tabbable-line" style="">
        <ul class="nav nav-tabs bp-addon-tab">
            <li>
                <a href="#bp_main_tab_<?php echo $this->uniqId ?>" class="active" data-toggle="tab"><?php echo Lang::lineDefault('META_00008', 'Үндсэн'); ?></a>
            </li>
            <li>
                <a href="#bp_file_tab_<?php echo $this->uniqId ?>" data-toggle="tab"><?php echo Lang::lineDefault('MET_331604', 'Файл'); ?></a>
            </li>          
        </ul>
        <div class="tab-content">
        <div class="tab-pane active" id="bp_main_tab_<?php echo $this->uniqId ?>">            
        <div class="mt10">
        <div class="col-md-12">
            <div class="form-body xs-form pl0">
                <div class="row">
                <?php 
                $procIsQty = $this->procIsQty == '1' ? '3' : '2';
                $procIsQty2 = $this->procIsQty == '1' ? '2' : '1';

                if ($this->getProcIndicatorList) { 
                    $splitColumn = array_chunk($this->getProcIndicatorList, 2);
                    $required = '';
                    if ($this->proc_required_percent_comparison === '1') { 
                        $required = 'required';
                    } 
                  
                    foreach ($splitColumn as $splitRow) {
                        echo '<div class="col-md-5">';
                        foreach ($splitRow as $row) { ?>
                            <div class="form-group row fom-row">
                              
                                <label for="" class="col-form-label col-md-4"><?php if ($this->proc_required_percent_comparison ==='1') { echo '<span class="required">*</span>'; } echo $row['name'] ?>:</label>                                                
                                <div class="col-md-4">
                                    <input type="hidden" name="indicatorId[]" value="<?php echo $row['id'] ?>">
                                    <div class="input-group mt6">
                                        <input title="" type="hidden" value="" name="indicatorIdValue[]">
                                        <input title="Хувь" type="text" value="<?php echo $row['id'] == '1551340381857' && !array_key_exists('defaultpercentvalue', $row) ? '100' : issetParam($row['defaultpercentvalue']) ?>" name="indicatorValue[]" onchange="indicator<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>', this)" class="numberInit form-control form-control-sm proc-indicator-value" data-id="<?php echo $row['id'] ?>">
                                        <span title="Хувь" style="border-top-left-radius: 0;border-bottom-left-radius: 0; height: 25px" class="input-group-btn btn default btn-bordered form-control-sm mr0"><i class="fa fa-percent" style="color: #484848"></i></span>
                                    </div>
                                    <div class="input-group mt4">
                                    <?php if ($row['id'] != '1551340381857') { ?>
                                            <input title="Дүн" type="text" name="indicatorPointValue[]" <?php echo $required ; ?> placeholder="Оноо оруулах" class="numberInit form-control form-control-sm proc-indicator-point-value"   data-pointid="<?php echo $row['id'] ?>">
                                            <span title="Дүн" style="border-top-left-radius: 0;border-bottom-left-radius: 0; height: 25px" class="input-group-btn btn default btn-bordered form-control-sm mr0"><i class="fa fa-calculator" style="color: #484848"></i></span>                                            
                                    <?php } else {
                                        echo '<input type="hidden" name="indicatorPointValue[]">';
                                    } ?>
                                    </div>
                                </div>      
                            </div>
                    <?php }
                        echo '</div>';
                    } ?>
                    <div class="w-100"></div>
                    <div class="col-md-5">
                        <div class="form-group row fom-row">
                            <label for="" class="col-form-label col-md-4"><span class="required">*</span><?php echo Lang::lineDefault('META_00187', 'Файл'); ?>:</label>                                                
                            <div class="col-md-8">
                                <textarea required class="form-control form-control-sm" name="headerDescription" style="height: 55px"></textarea>
                            </div>      
                        </div>                
                    </div>            
                    <div class="w-100"></div>
                    <div class="col-md-10 mt-4 headerMoreDescription <?php echo Config::getFromCache('PROC_IS_USE_DESCRIPTION_MORE_COMPARISON') != '1' ? "hidden" : ""; ?>">
                        <div class="form-group row fom-row">
                            <label for="" class="col-form-label col-md-2"><span class="required">*</span><?php echo Lang::line('GLOBE_CODE_00187991199119911'); ?>:</label>                                                
                            <div class="col-md-8">
                                <textarea required class="form-control form-control-sm" name="headerMoreDescription" id="headerMoreDescription" rows="12"></textarea>
                            </div>      
                        </div>                
                    </div>                          
                <?php } ?>                      
                </div>                
            </div>
            <div class="tabbable-line mt20">
                <ul class="nav nav-tabs bp-addon-tab">
                    <?php 
                    if ($this->getProcIndicatorList) { 
                        foreach ($this->getProcIndicatorList as $rkey => $row) {
                            if ($row['id'] === '1551340381857') { ?>                
                                <li class="nav-item">
                                    <a href="#proc_tab_<?php echo $this->uniqId . $row['id'] ?>" class="nav-link active" data-toggle="tab"><?php echo $row['name'] ?></a>
                                </li>
                    <?php 
                            } else { ?>
                                <li class="nav-item">
                                    <a href="#proc_tab_<?php echo $this->uniqId . $row['id'] ?>" class="nav-link hidden" data-toggle="tab"><?php echo $row['name'] ?></a>
                                </li>  
                    <?php
                            }
                        }} ?>
                </ul>            
                <div class="tab-content">
                    <div class="tab-pane active" id="proc_tab_<?php echo $this->uniqId . '1551340381857' ?>">
                        <div class="bp-overflow-xy-auto" style="max-height: 450px; overflow: auto;">
                            <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 bprocess-theme1-proc">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="rowNumber" style="width: 30px;">№</th>
                                        <th rowspan="2" class="itemnameheader bp-head-sort-proc" style=""><?php echo Lang::lineDefault('Item_nameP', 'Бараа, үйлчилгээний нэр'); ?></th>
                                        <th rowspan="2" class="description bp-head-sort-proc" style=""><?php echo Lang::lineDefault('MET_999992512', 'Тодорхойлолт'); ?></th>                        
                                        <th rowspan="2" class="qty bp-head-sort-proc" data-aggregate="sum" style=""><?php echo Lang::lineDefault('PP_0002', 'Тоо хэмжээ'); ?></th>                        
                                        <th rowspan="2" class="qty bp-head-sort-proc" style=""><?php echo Lang::lineDefault('Данс', 'Данс'); ?></th>                        
                                        <?php 
                                        $customers = false;
                                        $customersTh = '';
                                        $customersFilterTh = '';
                                        if ($this->getProcCustomerList) {
                                            $customers = true;
                                            foreach ($this->getProcCustomerList as $row) {
                                                echo '<th colspan="'.$procIsQty.'" class="" data-aggregate="sum" style="width: 200px;">' . $row['suppliername'] . '</th>';
                                                if ($this->procIsQty == '1') {
                                                    $customersTh .= '<th class="bp-head-sort-proc">Тоо хэмжээ</th>';
                                                }
                                                $customersTh .= '<th class="bp-head-sort-proc unitprice" style="">'.Lang::lineDefault('429512345', 'Нэгж үнэ').'</th>';
                                                $customersTh .= '<th class="bp-head-sort-proc unitprice" style="">'.Lang::lineDefault('4295123456', 'Нийт үнэ').'</th>';
                                                if ($this->procIsQty == '1') {
                                                    $customersFilterTh .= '<th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>';
                                                }
                                                $customersFilterTh .= '<th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>';
                                                $customersFilterTh .= '<th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>';
                                            }
                                            echo '<th colspan="'.$procIsQty.'" class="" data-aggregate="sum" style="width: 200px;">Хамгийн бага</th>';
                                            $customersTh .= '<th colspan="'.$procIsQty2.'" style="width:70px" class="bp-head-sort-proc unitprice">'.Lang::lineDefault('429512345', 'Нэгж үнэ').'</th>';
                                            $customersTh .= '<th class="bp-head-sort-proc unitprice" style="">'.Lang::lineDefault('4295123456', 'Нийт үнэ').'</th>';
                                            echo '<th rowspan="2" class="bp-head-sort-proc unitprice" style="">'.Lang::lineDefault('proc2', 'Хувийн хэтрэлт').'</th>';
                                            $customersFilterTh .= '<th colspan="'.$procIsQty2.'" class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>';
                                            $customersFilterTh .= '<th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>';
                                            $customersFilterTh .= '<th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>';
                                        }
                                        ?>                                    
                                    </tr>
                                    <tr>        
                                        <?php echo $customersTh ?>
                                    </tr>
                                    <tr class="bp-filter-row-proc bp-filter-row">
                                        <th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"></th>
                                        <th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>
                                        <th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>
                                        <th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>
                                        <th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>
                                        <?php echo $customersFilterTh; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $hiddenParams = '';

                                    if ($this->getProcCustomerItemList) {
                                        $paymentType = $countryName = '';
                                        $looped = true; $suppliers = $suppliersMinValues = array();
                                        $supplierTotalMinSum = 0;

                                        $hiddenParams .= '<input type="hidden" name="departmentId" value="'.$this->getProcCustomerItemList['departmentid'].'">';
                                        $hiddenParams .= '<input type="hidden" name="rfId" value="'.$this->rfId.'">';
                                        $hiddenParams .= '<input type="hidden" name="ordertypeid" value="'.issetParam($this->getProcCustomerItemList['ordertypeid']).'">';
                                        $hiddenParams .= '<input type="hidden" name="isforeign" value="'.issetParam($this->getProcCustomerItemList['isforeign']).'">';
                                        $hiddenParams .= '<input type="hidden" name="wfmStatusId" value="'.$this->wfmStatusId.'">';

                                        foreach ($this->getProcCustomerItemList['ext_price_comparison_dtl'] as $key => $row) { 
                                            /**
                                             * Item more declaration
                                             */
                                            $totalPrice = $rate = $itemMoreStr1 = $itemMoreStr2 = $itemMoreStr3 = '';                 
                                            $indexKey = $key;
                                            if (isset($row['comparisondtlviewpath'])) {
                                                unset($row['comparisondtlviewpath']['id']);
                                                foreach ($row['comparisondtlviewpath'] as $mkey => $mval) {
                                                    ${'moreValue_'.$mkey} = '';
                                                }
                                            }

                                            ?>
                                            <tr class="saved-bp-row added-bp-row item-main-row" data-itemid="<?php echo $row['itemid'] ?>">
                                                <td class="text-center middle" style="background-color: rgb(255, 255, 255); position: relative; z-index: 9; background-clip: padding-box; left: 0px;"><span><?php echo ++$key ?></span></td>
                                                <td class="itemname itemnameheader" onclick="itemMore<?php echo $this->uniqId ?>('<?php echo $row['itemid'] ?>', this, '<?php echo $indexKey ?>')"><i class="icon-circle-down2 mr3"></i> <?php echo $row['itemname'] ?>
                                                    <input type="hidden" name="itemid[]" value="<?php echo $row['itemid'] ?>" />
                                                    <input type="hidden" name="indexKey[]" value="<?php echo $indexKey ?>" />
                                                    <input type="hidden" name="qty[]" value="<?php echo $row['qty'] ?>" />
                                                    <input type="hidden" name="beginPrice[]" value="<?php echo $row['beginprice'] ?>" />
                                                    <textarea class="d-none" name="description[]"><?php echo $row['description'] ?></textarea>
                                                    <input type="hidden" name="typeId[]" value="<?php echo $row['typeid'] ?>" />
                                                    <input type="hidden" name="totalAmount[]" value="0" />
                                                    <input type="hidden" name="templateId[]" value="<?php echo $row['templateid'] ?>" />
                                                </td>
                                                <td class="description"><textarea style="border:none;resize: none;width: 100%;outline: none;" readonly><?php echo $row['description'] ?></textarea></td>
                                                <td class="text-right"><?php echo $row['qty'] ?></td>
                                                <td class=""><?php echo $row['measurecode'] ?></td>
                                                <?php if ($row['comparison_dtl'] && $customers) {
                                                    $groupComparison = Arr::groupByArray($row['comparison_dtl'], 'compareid');
                                                    $overDue = '';
                                                    
                                                    foreach ($row['comparison_dtl'] as $rdata) {
                                                        if ($rdata['calcbudgetprice'] !== '' && $rdata['calcbudgetprice'] > 0) {
                                                            $supplierMin = $rdata['calcbudgetprice'];
                                                            $supplierIdMin = $rdata['supplierid'];
                                                            $supplierTotalMin = $rdata['calcbudgetprice'] * $rdata['qty'];    
                                                        }                                         
                                                    }

                                                    foreach ($this->getProcCustomerList as $cus) {

                                                        if ($key === 1) {
                                                            $suppliers[$cus['compareid']] = 0;
                                                        }

                                                        if (array_key_exists($cus['compareid'], $groupComparison)) {
                                                            $dtl = $groupComparison[$cus['compareid']]['row'];

                                                            $totalAmount = $dtl['qty'] * $dtl['calcbudgetprice'];
                                                            $suppliers[$cus['compareid']] += $totalAmount;
                                                            $overDue .= '<span class="over-due-'.$cus['supplierid'].'-'.str_replace('.', '_', $dtl['calcbudgetprice']).'-'.$indexKey.' d-none">'.$dtl['overduepercent'].'</span>';

                                                            if ($dtl['totalprice']) {
                                                                $totalPrice = $dtl['totalprice'];
                                                            }
                                                            if ($dtl['rate']) {
                                                                $rate = $dtl['rate'];
                                                            }
                                                            
                                                            if ($this->procIsQty == '1') {
                                                                echo '<td class="text-right">' . $dtl['qty'] . '</td>';
                                                            }
                                                            echo '<td class="text-right'.(empty($dtl['calcbudgetprice']) ? ' zero-amt' : '').' unitprice selected-supplier '.$cus['supplierid'].'-'.str_replace('.', '_', $dtl['calcbudgetprice']).'-'.$indexKey.'" data-key="'.$cus['supplierid'].'-'.str_replace('.', '_', $dtl['calcbudgetprice']).'-'.$indexKey.'">' . Number::amount($dtl['calcbudgetprice']);
                                                            echo '<input type="hidden" name="supplierId'.$indexKey.'[]" value="'.$cus['supplierid'].'">';
                                                            echo '<input type="hidden" name="supplierCurrencyId'.$indexKey.'[]" value="'.$dtl['currencyid'].'">';
                                                            echo '<input type="hidden" class="selected-supplier" name="supplierSelected'.$indexKey.'[]">';
                                                            echo '<input type="hidden" name="supplierTotalPrice'.$indexKey.'[]" value="'.$dtl['totalprice'].'">';
                                                            echo '<input type="hidden" name="isVat'.$indexKey.'[]" value="'.$dtl['isvat'].'">';
                                                            echo '<input type="hidden" name="qty'.$indexKey.'[]" value="'.$dtl['qty'].'">';
                                                            echo '<input type="hidden" name="rate'.$indexKey.'[]" value="'.$rate.'">';
                                                            echo '<input type="hidden" name="dueDate'.$indexKey.'[]" value="'.$dtl['duedate'].'">';
                                                            echo '<input type="hidden" name="guaranteeMonth'.$indexKey.'[]" value="'.$dtl['guaranteemonth'].'">';
                                                            echo '<input type="hidden" name="discountPercent'.$indexKey.'[]" value="'.$dtl['discountpercent'].'">';
                                                            echo '<input type="hidden" name="discountAmount'.$indexKey.'[]" value="'.$dtl['discountamount'].'">';
                                                            echo '<input type="hidden" name="dtlId'.$indexKey.'[]" value="'.$dtl['dtlid'].'">';
                                                            echo '<input type="hidden" name="unitPriceBase'.$indexKey.'[]" value="'.$dtl['unitpricebase'].'">';
                                                            echo '<input type="hidden" name="customCost'.$indexKey.'[]" value="'.$dtl['customcost'].'">';
                                                            echo '<input type="hidden" name="indicatorId'.$indexKey.'[]" value="1551340381857">';
                                                            echo '<input type="hidden" name="rfqId'.$indexKey.'[]" value="'.$dtl['rfqid'].'">';
                                                            echo '<input type="hidden" name="kpidmdtl'.$indexKey.'[]" value="'.($dtl['kpidmdtl'] ? htmlentities(json_encode($dtl['kpidmdtl']), ENT_QUOTES) : '').'" class="kpidmdtlobject">';
                                                            echo '</td>';                                                            
                                                            echo '<td class="text-right unitprice'.(empty($totalAmount) ? ' zero-amt' : '').'">' . Number::amount($totalAmount) . '</td>';                                                        

                                                            if ($dtl['calcbudgetprice'] !== '' && $dtl['calcbudgetprice'] > 0) {
                                                                if ($supplierMin > $dtl['calcbudgetprice']) {
                                                                    $supplierMin = $dtl['calcbudgetprice'];
                                                                    $supplierIdMin = $dtl['supplierid'];
                                                                    $supplierTotalMin = $totalAmount;
                                                                }
                                                            }                                                        

                                                            $itemMoreStr1 .= '<td class="text-right" colspan="'.$procIsQty2.'">' . Number::amount($totalPrice) . '</td>';
                                                            $itemMoreStr1 .= '<td class="">' . $dtl['currencycode'] . '</td>';
                                                            if (Str::upper($dtl['currencycode']) !== 'MNT') {
                                                                $itemMoreStr2 .= '<td class="text-right" colspan="'.$procIsQty2.'">' . ($totalPrice != '' && $rate != '' ? Number::amount($totalPrice * $rate) : '') . '</td>';
                                                                $itemMoreStr2 .= '<td class="">MNT</td>';
                                                            } else {
                                                                $itemMoreStr2 .= '<td class="text-right" colspan="'.$procIsQty2.'"></td>';
                                                                $itemMoreStr2 .= '<td class=""></td>';
                                                            }
                                                            if ($dtl['isvat'] == '1') {
                                                                $itemMoreStr3 .= '<td colspan="'.$procIsQty.'" class="">Тийм</td>';
                                                            } else {
                                                                $itemMoreStr3 .= '<td colspan="'.$procIsQty.'" class="">Үгүй</td>';
                                                            }

                                                            if (isset($row['comparisondtlviewpath'])) {
                                                                foreach ($row['comparisondtlviewpath'] as $mkey => $mval) {
                                                                    if (isset($dtl[$mkey])) {
                                                                        if (is_numeric($dtl[$mkey])) {
                                                                            ${'moreValue_'.$mkey} .= '<td colspan="'.$procIsQty.'" class="text-right">'.Number::amount($dtl[$mkey]).'</td>';
                                                                        } else {
                                                                            ${'moreValue_'.$mkey} .= '<td colspan="'.$procIsQty.'" class="">'.$dtl[$mkey].'</td>';
                                                                        }
                                                                    } else {
                                                                        ${'moreValue_'.$mkey} .= '<td colspan="'.$procIsQty.'" class=""></td>';
                                                                    }
                                                                }
                                                            }

                                                            if ($cus['paymentdtl'] && $looped) {
                                                                $paymentType .= '<td style="background-color: #fff;" colspan="'.$procIsQty2.'"><table class="table table-sm table-hover bprocess-table-dtl bprocess-theme1"><tbody>';
                                                                foreach ($cus['paymentdtl'] as $pay) {
                                                                    $paymentType .= '<tr>';
                                                                    $paymentType .= '<td>';
                                                                    $paymentType .= '<input type="hidden" name="paymentDtlTypeName'.$cus['supplierid'].'[]" value="'.$pay['typename'].'">';
                                                                    $paymentType .= '<input type="hidden" name="paymentDtlPercent'.$cus['supplierid'].'[]" value="'.$pay['percent'].'">';
                                                                    $paymentType .= '<input type="hidden" name="paymentDtlCurrencyName'.$cus['supplierid'].'[]" value="'.$pay['currencyname'].'">';
                                                                    $paymentType .= '<input type="hidden" name="paymentDtlCurrencyId'.$cus['supplierid'].'[]" value="'.$pay['currencyid'].'">';
                                                                    $paymentType .= '<input type="hidden" name="paymentDtlDim1'.$cus['supplierid'].'[]" value="'.$pay['dim1'].'">';                                                                    
                                                                    $paymentType .= $pay['typename'] . '</td>';
                                                                    $paymentType .= '</tr>';
                                                                }
                                                                $paymentType .= '</tbody></table></td>';
                                                                $paymentType .= '<td style="background-color: #fff"><table class="table table-sm table-hover bprocess-table-dtl bprocess-theme1"><tbody>';
                                                                foreach ($cus['paymentdtl'] as $pay) {
                                                                    $paymentType .= '<tr>';
                                                                    $paymentType .= '<td class="text-right">' . $pay['percent'] . '%</td>';
                                                                    $paymentType .= '</tr>';
                                                                }
                                                                $paymentType .= '</tbody></table></td>';

                                                                $countryName .= '<td colspan="'.$procIsQty.'" class="text-center" style="background-color: #fff">' . $cus['countryname'] . '</td>';

                                                            } elseif ($looped) {

                                                                $paymentType .= '<td style="background-color: #fff" colspan="'.$procIsQty2.'"><table class="table table-sm table-hover bprocess-table-dtl bprocess-theme1"><tbody>';
                                                                $paymentType .= '<tr>';
                                                                $paymentType .= '<td></td>';
                                                                $paymentType .= '</tr>';
                                                                $paymentType .= '</tbody></table></td>';                                                                
                                                                $paymentType .= '<td style="background-color: #fff"><table class="table table-sm table-hover bprocess-table-dtl bprocess-theme1"><tbody>';
                                                                $paymentType .= '<tr>';
                                                                $paymentType .= '<td class="text-right"></td>';
                                                                $paymentType .= '</tr>';
                                                                $paymentType .= '</tbody></table></td>';                  
                                                                $countryName .= '<td colspan="'.$procIsQty.'" style="background-color: #fff"></td>';                                                            
                                                            }

                                                        } elseif ($looped) {

                                                            echo '<td class="text-right">';
                                                            echo '<input type="hidden" name="supplierId'.$indexKey.'[]" value="'.$cus['supplierid'].'">';
                                                            echo '<input type="hidden" name="supplierCurrencyId'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" class="selected-supplier" name="supplierSelected'.$indexKey.'[]">';
                                                            echo '<input type="hidden" name="supplierTotalPrice'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="isVat'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="rate'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="dueDate'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="guaranteeMonth'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="discountPercent'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="discountAmount'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="dtlId'.$indexKey.'[]" value="">';
                                                            echo '<input type="hidden" name="unitPriceBase'.$indexKey.'[]" value="">';           
                                                            echo '<input type="hidden" name="indicatorId'.$indexKey.'[]" value="">';
                                                            echo '</td>';
                                                            echo '<td class="text-right"></td>';
                                                            $paymentType .= '<td style="background-color: #fff" colspan="'.$procIsQty2.'"><table class="table table-sm table-hover bprocess-table-dtl bprocess-theme1"><tbody>';
                                                            $paymentType .= '<tr>';
                                                            $paymentType .= '<td></td>';
                                                            $paymentType .= '</tr>';
                                                            $paymentType .= '</tbody></table></td>';                                                                
                                                            $paymentType .= '<td><table class="table table-sm table-hover bprocess-table-dtl bprocess-theme1"><tbody>';
                                                            $paymentType .= '<tr>';
                                                            $paymentType .= '<td class="text-right"></td>';
                                                            $paymentType .= '</tr>';
                                                            $paymentType .= '</tbody></table></td>';                  
                                                            $countryName .= '<td colspan="'.$procIsQty.'" style="background-color: #fff"></td>';           

                                                            $itemMoreStr1 .= '<td class="text-right" colspan="'.$procIsQty2.'"></td>';
                                                            $itemMoreStr1 .= '<td class=""></td>';                                                        
                                                            $itemMoreStr2 .= '<td class="text-right" colspan="'.$procIsQty2.'"></td>';
                                                            $itemMoreStr2 .= '<td class=""></td>';                                                          
                                                            $itemMoreStr3 .= '<td colspan="'.$procIsQty.'"></td>';           
                                                            
                                                            if (isset($row['comparisondtlviewpath'])) {
                                                                foreach ($row['comparisondtlviewpath'] as $mkey => $mval) {
                                                                    ${'moreValue_'.$mkey} .= '<td colspan="'.$procIsQty.'"></td>';
                                                                }
                                                            }                                                            

                                                        } else {

                                                            echo '<td class="text-right"></td>';
                                                            echo '<td class="text-right"></td>';                                                        
                                                            $itemMoreStr1 .= '<td class="text-right" colspan="'.$procIsQty2.'"></td>';
                                                            $itemMoreStr1 .= '<td class=""></td>';                                                        
                                                            $itemMoreStr2 .= '<td class="text-right" colspan="'.$procIsQty2.'"></td>';
                                                            $itemMoreStr2 .= '<td class=""></td>';                                                          
                                                            $itemMoreStr3 .= '<td colspan="'.$procIsQty.'"></td>';                                                          

                                                            if (isset($row['comparisondtlviewpath'])) {
                                                                foreach ($row['comparisondtlviewpath'] as $mkey => $mval) {
                                                                    ${'moreValue_'.$mkey} .= '<td colspan="'.$procIsQty.'"></td>';
                                                                }
                                                            }                                                                    
                                                        }

                                                    }
                                                    $looped = false;

                                                    $supplierTotalMinSum += $supplierTotalMin;
                                                    echo '<td colspan="'.$procIsQty2.'" style="width:70px" class="text-right unitprice">' . Number::amount($supplierMin) . '</td>';
                                                    echo '<td class="text-right unitprice">' . Number::amount($supplierTotalMin) . '</td>';         
                                                    echo '<td class="text-right over-due">' . $overDue . '</td>';         
                                                    $itemMoreStr1 .= '<td class="text-right" colspan="'.$procIsQty2.'"></td>';
                                                    $itemMoreStr1 .= '<td class=""></td>';                                                        
                                                    $itemMoreStr2 .= '<td class="text-right" colspan="'.$procIsQty2.'"></td>';
                                                    $itemMoreStr2 .= '<td class=""></td>';                                                          
                                                    $itemMoreStr3 .= '<td colspan="'.$procIsQty.'"></td>';       
                                                    
                                                    array_push($suppliersMinValues, array(
                                                        'id' => $supplierIdMin,
                                                        'index' => $indexKey,
                                                        'value' => str_replace('.', '_', $supplierMin),
                                                    ));
                                                    unset($supplierMin);
                                                    
                                                    if (isset($row['comparisondtlviewpath'])) {
                                                        foreach ($row['comparisondtlviewpath'] as $mkey => $mval) {
                                                            ${'moreValue_'.$mkey} .= '<td colspan="'.$procIsQty.'"></td>';
                                                        }
                                                    }                                                            
                                                } ?>
                                            </tr>                                    
                                            <tr class="hidden saved-bp-row added-bp-row item-<?php echo $row['itemid'].'-'.$indexKey ?>" data-itemid="<?php echo $row['itemid'] ?>">
                                                <td colspan="5" rowspan="2" class="text-right"><?php echo Lang::lineDefault('unit_price', 'Нэгж үнэ'); ?></td>
                                                <?php echo $itemMoreStr1 ?>
                                            </tr>                                    
                                            <tr class="hidden saved-bp-row added-bp-row item-<?php echo $row['itemid'].'-'.$indexKey ?>" data-itemid="<?php echo $row['itemid'] ?>">
                                                <?php echo $itemMoreStr2 ?>
                                            </tr>                                    
                                            <tr class="hidden saved-bp-row added-bp-row item-<?php echo $row['itemid'].'-'.$indexKey ?>" data-itemid="<?php echo $row['itemid'] ?>">
                                                <td colspan="5" class="text-right"><?php echo Lang::lineDefault('isVat', 'НӨАТ-тай эсэх'); ?></td>
                                                <?php echo $itemMoreStr3 ?>
                                            </tr>
                                            <?php
                                            if (isset($row['comparisondtlviewpath'])) {
                                                foreach ($row['comparisondtlviewpath'] as $mkey => $mval) { ?>
                                                    <tr class="hidden saved-bp-row added-bp-row item-<?php echo $row['itemid'].'-'.$indexKey ?>" data-itemid="<?php echo $row['itemid'] ?>">
                                                    <td colspan="5" class="text-right"><?php echo $mval ?></td>
                                                    <?php
                                                    echo ${'moreValue_'.$mkey};
                                                    echo '</tr>';
                                                }
                                            }                                                    
                                            ?>
                                            <tr class="hidden saved-bp-row added-bp-row item-<?php echo $row['itemid'].'-'.$indexKey ?> kpitemplate" data-itemid="<?php echo $row['itemid'] ?>">
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <?php if ($this->getProcCustomerItemList) { ?>
                                    <tr>
                                        <td colspan="5" class="text-right" style="background-color: #fff"><?php echo Lang::lineDefault('MET_331462', 'Улс'); ?></td>
                                        <?php echo $countryName ?>
                                        <td colspan="<?php echo $procIsQty ?>" style="background-color: #fff"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="background-color: #fff;padding-right: 3px !important;" class="text-right"><?php echo Lang::lineDefault('From_Payment', 'Төлбөрийн хэлбэр'); ?></td>
                                        <?php echo $paymentType ?>
                                        <td colspan="<?php echo $procIsQty ?>" style="background-color: #fff"></td>
                                    </tr>                                    
                                    <?php } ?>
                                    <tr>
                                        <td colspan="5" class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"><?php echo Lang::lineDefault('total_rebate', 'Нийт үнийн дүн'); ?></td>
                                        <?php 
                                        $pointFooter = '';
                                        if ($row['comparison_dtl'] && $customers) {
                                            foreach ($this->getProcCustomerList as $cus) {       
                                                if ($this->procIsQty == '1') { 
                                                    echo '<td class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                                }
                                                echo '<td class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                                echo '<td class="text-right sum-total-amount" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;">' . Number::amount($suppliers[$cus['compareid']]) . '</td>';
                                                $pointFooter .= '<td class="text-right" colspan="'.$procIsQty2.'" style="background-color: #fff; position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                                $pointFooter .= '<td class="text-right sum-percent-total-amount" data-footer-supplierid="'.$cus['supplierid'].'" style="background-color: #fff; position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                            }
                                            echo '<td colspan="'.$procIsQty2.'" class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                            echo '<td class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;">' . Number::amount($supplierTotalMinSum) . '</td>';
                                        } 
                                        echo '<td class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                        ?>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="number text-right" style="background-color: #fff; position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"><?php echo Lang::lineDefault('HR_00566', 'Оноо'); ?> <span class="ml28"></span></td>
                                        <?php echo $pointFooter ?>
                                        <td class="text-right" colspan="<?php echo $procIsQty2 ?>" style="background-color: #fff; position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"></td>
                                        <td class="text-right" style="background-color: #fff; position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"></td>
                                        <td class="text-right" style="background-color: #fff; position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php if ($this->getProcIndicatorList) { 
                        foreach ($this->getProcIndicatorList as $rkey => $rowIndicator) {
                            if ($rowIndicator['id'] === '1551340381857') continue;
                        ?>                
                            <div class="tab-pane" id="proc_tab_<?php echo $this->uniqId . $rowIndicator['id'] ?>">
                                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 bprocess-theme1-proc2">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="rowNumber" style="width: 30px;">№</th>
                                            <th rowspan="2" class="" style="">Бараа, үйлчилгээний нэр</th>
                                            <th rowspan="2" class=" description" style="">Тодорхойлолт</th>
                                            <?php 
                                            $customers = false;
                                            $customersTh = '';
                                            if ($this->getProcCustomerList) {
                                                $customers = true;
                                                foreach ($this->getProcCustomerList as $row) {
                                                    echo '<th colspan="2" class="" data-aggregate="sum" style="width: 280px;">' . $row['suppliername'] . '</th>';
                                                    $customersTh .= '<th class=" unitprice" style="">Тодорхойлолт</th>';
                                                    $customersTh .= '<th class=" unitprice" style="">Оноо</th>';
                                                }
                                            }
                                            ?>                                    
                                        </tr>
                                        <tr>        
                                            <?php echo $customersTh ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        
                                        if ($this->getProcCustomerItemList) {
                                            $looped = true;
                                            $required = '';
                                            if($this->proc_required_percent_comparison === '1'){$required = 'required';}
                                         
                                            foreach ($this->getProcCustomerItemList['ext_price_comparison_dtl'] as $key => $row) {                                                 
                                                $indexKey = $key;
                                                ?>

                                                <tr class="saved-bp-row added-bp-row" data-itemid="<?php echo $row['itemid'] ?>">
                                                    <td class="text-center middle" style="background-color: rgb(255, 255, 255); position: relative; z-index: 9; background-clip: padding-box; left: 0px;"><span><?php echo ++$key ?></span></td>
                                                    <td style="background-color: #fff; padding-left: 3px !important; padding-right: 3px !important;"><?php echo $row['itemname'] ?></td>
                                                    <td style="background-color: #fff; padding-left: 3px !important; padding-right: 3px !important;"><textarea style="border:none;resize: none;width: 100%;outline: none;" readonly><?php echo $row['description'] ?></textarea></td>
                                                    <?php if ($row['comparison_dtl'] && $customers) {
                                                        $groupComparison = Arr::groupByArray($row['comparison_dtl'], 'compareid');
                                                        
                                                        foreach ($this->getProcCustomerList as $cusKey => $cus) {

                                                          
                                                            if (array_key_exists($cus['compareid'], $groupComparison)) {
                                                                $dtl = $groupComparison[$cus['compareid']]['row'];
                                                                

                                                                echo '<td class="stretchInput"><input type="text" name="supplierDescription'.$rowIndicator['id'].'_'.$indexKey.'_'.$cus['supplierid'].$cusKey.'" class="form-control form-control-sm stringInit">';
                                                                echo '</td>';
                                                                echo '<td class="stretchInput"><input type="text" '.$required.' name="supplierPoint'.$rowIndicator['id'].'_'.$indexKey.'_'.$cus['supplierid'].$cusKey.'" data-id="'.$rowIndicator['id'].'" data-supid="'.$cus['compareid'].'" data-supplierid="'.$cus['supplierid'].'" class="form-control form-control-sm bigdecimalInit supplier-point-proc supplier-point-proc-'.$cus['compareid'].'"></td>';

                                                            } elseif ($looped) {

                                                                echo '<td class="stretchInput"><input type="text" name="supplierDescription'.$rowIndicator['id'].'_'.$indexKey.'_'.$cus['supplierid'].$cusKey.'" class="form-control form-control-sm stringInit">';
                                                                echo '</td>';
                                                                echo '<td class="stretchInput"><input type="text"'.$required.' name="supplierPoint'.$rowIndicator['id'].'_'.$indexKey.'_'.$cus['supplierid'].$cusKey.'" class="form-control form-control-sm bigdecimalInit"></td>';

                                                            } else {

                                                                echo '<td class="stretchInput"><input type="text" name="supplierDescription'.$rowIndicator['id'].'_'.$indexKey.'_'.$cus['supplierid'].$cusKey.'" class="form-control form-control-sm stringInit">';
                                                                echo '</td>';
                                                                echo '<td class="stretchInput"><input type="text" '.$required.' name="supplierPoint'.$rowIndicator['id'].'_'.$indexKey.'_'.$cus['supplierid'].$cusKey.'" class="form-control form-control-sm bigdecimalInit"></td>';
                                                            }

                                                        }
                                                        $looped = false;                                         
                                                    } ?>
                                                </tr>
                                        <?php
                                            }
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;">Нийт оноо</td>
                                            <?php 
                                            $pointFooter = '';
                                            if ($row['comparison_dtl'] && $customers) {                                                
                                                foreach ($this->getProcCustomerList as $cus) {         
                                                    echo '<td class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                                    echo '<td class="text-right sum-total-amount-other" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                                    $pointFooter .= '<td class="text-right" style="background-color: #fff; position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                                    $pointFooter .= '<td class="text-right sum-percent-total-amount-other" data-footer-supplierid="'.$cus['supplierid'].'" style="background-color: #fff; position: relative; z-index: 9; background-clip: padding-box; bottom: 0px;"></td>';
                                                }
                                            } ?>
                                            <td class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right" style="background-color: #fff; position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;">Оноо</td>
                                            <td class="text-right" style="background-color: #fff; position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"></td>
                                            <?php echo $pointFooter ?>
                                            <td class="text-right" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; bottom: 0px; left: 0px;"></td>
                                        </tr>
                                    </tfoot>
                                </table>                            
                            </div>
                    <?php }} ?>                
                </div>
            </div>
        </div>
        <div class="proc-hidden-params">
            <?php
            echo $hiddenParams;
            ?>
        </div>        
        <div class="col-md-12<?php echo $this->isAjax ? ' hidden' : '' ?>">
            <button type="button" class="pull-right btn btn-sm green proc-save">Хадгалах</button>
        </div>
        </div>
        </div>
        <div class="tab-pane" id="bp_file_tab_<?php echo $this->uniqId ?>">
            <div class="">
              <div class="col-md-12">
                <ul class="grid cs-style-2 list-view0 list-view-file-new">
                  <li class="meta" data-attach-id="0">
                    <a href="javascript:;" class="btn fileinput-button btn-block btn-xs" title="Файл нэмэх">
                      <i class="icon-plus3 big"></i>
                      <input type="file" name="procFiles[]" class="" multiple onchange="onChangeAttachFIleAddMode(this)" />
                    </a>
                  </li>
                </ul>

                <div class="hiddenFileDiv hidden"></div>
              </div>
            </div>          
        </div>
        </div>
    </div>
    </form>
</div>

<style type="text/css">
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-table-dtl{
        table-layout: fixed !important; 
        max-width: 3433px !important;
    } 
    #windowIdProc<?php echo $this->uniqId ?> .itemnameheader {
        width:282px !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> .description {
        width:282px !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> .unitprice {
        width:100px !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> .itemname, #windowIdProc<?php echo $this->uniqId ?> .selected-supplier {
        cursor: pointer;
    }
    #windowIdProc<?php echo $this->uniqId ?> .qty {
        width:60px !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1 > tbody > tr > td, #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1 > tfoot > tr > td {
        height: 28px;
        font-size: 13.3px;
        padding: 0 !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1 > tfoot > tr:not(:nth-child(2)) > td {
        padding-right: 3px !important;
        padding-left: 3px !important;                                
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1 > tfoot > tr:nth-child(3) > td, #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1 > tfoot > tr:nth-child(4) > td {
        font-weight: bold;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1 > tfoot > tr > td {
        border-top: none;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-proc > tbody > tr > td, #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-proc > tbody > tr:last-child > td:first-child {
        padding-right: 3px !important;
        padding-left: 3px !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-proc > tfoot > tr:nth-child(2) > td > table > tbody > tr > td {
        border-left: none;
        border-right: none;
        border-bottom: none;
        padding-right: 3px !important;
        padding-left: 3px !important;                                
        background-color: #fff;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-proc > tfoot > tr:nth-child(1) > td,
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-proc > tfoot > tr:nth-child(2) > td,
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-proc > tfoot > tr:nth-child(2) > td > table > tbody > tr > td {
        font-weight: normal;
    }
    #windowIdProc<?php echo $this->uniqId ?> .fancybox-button {
        background: none;
        padding: 0;
    }
    #windowIdProc<?php echo $this->uniqId ?> .img-container {
        vertical-align: baseline;
    }    
    .float-right>.dropdown-menu {
        right: auto;
    }
    .dropdown > .dropdown-menu.float-left:before, .dropdown-toggle > .dropdown-menu.float-left:before, .btn-group > .dropdown-menu.float-left:before {
        left: 9px;
        right: auto;
    }
    .fileinput-button .big {
        font-size: 70px;
        line-height: 112px;
        text-align: center;
        color: #ddd;
    }    
    #windowIdProc<?php echo $this->uniqId ?> table.table td.stretchInput select {
        border: 1px transparent solid;
        font-size: 12px;
        margin: 0 !important;
        padding: 0 3px !important;
        outline: none;
        box-sizing: border-box;
        display: inline-block;
        height: 25px;
        border-radius: 0 !important;
        -moz-border-radius: 0 !important;
        -webkit-border-radius: 0 !important;
    }    
    #windowIdProc<?php echo $this->uniqId ?> .form-control[disabled] {
        cursor: default;
        background-color: #fff;
    }    
    #windowIdProc<?php echo $this->uniqId ?> .form-control:disabled {
        color: #333;
        -webkit-appearance: none;
        -moz-appearance: none;        
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-table-dtl > thead > tr > th.bp-head-sort-proc {
        cursor: pointer;
        padding-right: 10px !important;
        background: #E7E7E7 url(<?php echo URL.'assets/custom/' ?>addon/img/bp-up-down.gif) no-repeat right center;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }    
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-table-dtl > thead > tr > th.bp-head-sort-proc-desc {
        background: #cdc7c7 url(<?php echo URL.'assets/custom/' ?>addon/img/bp-down.gif) no-repeat right center;
    }    
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-table-dtl > thead > tr > th.bp-head-sort-proc-asc {
        background: #cdc7c7 url(<?php echo URL.'assets/custom/' ?>addon/img/bp-up.gif) no-repeat right center;
    }    
    /* #windowIdProc<?php echo $this->uniqId ?> table.bprocess-table-dtl > tbody .zero-amt {
        background-color: #ccc;
        cursor: default;
    }     */
</style>

<script type="text/javascript">
    var $windowIdProc<?php echo $this->uniqId ?> = $("#windowIdProc<?php echo $this->uniqId ?>");

    $(function () {
        
        $('table.bprocess-theme1-proc', $windowIdProc<?php echo $this->uniqId ?>).tableHeadFixer({'head': true, 'left': 5, 'z-index': 9, 'foot': true});
        
        $('.selected-supplier', $windowIdProc<?php echo $this->uniqId ?>).on('click', function(){
            $this = $(this);

             if ($this.hasClass('zero-amt')) {
                 return;
             }
            
            $this.closest('tr').find('.selected-supplier').css('background-color', 'transparent').removeClass('slc');
            $this.closest('tr').find('.selected-supplier').val('');      
            
            $('.over-due-'+$this.data('key')).parent().children().addClass('d-none');
            $('.over-due-'+$this.data('key')).removeClass('d-none');
            
            if (!$this.hasClass('slc')) {
                $this.css('background-color', 'rgb(56, 208, 56)').addClass('slc');
                $this.find('.selected-supplier').val('1');
            }
        });        

        <?php foreach ($suppliersMinValues as $row) { ?>
            $('.<?php echo $row['id'].'-'.$row['value'].'-'.$row['index'] ?>').css('background-color', 'rgb(56, 208, 56)').addClass('slc');
            $('.<?php echo $row['id'].'-'.$row['value'].'-'.$row['index'] ?>').find('.selected-supplier').val('1');
            $('.over-due-<?php echo $row['id'].'-'.$row['value'].'-'.$row['index'] ?>').removeClass('d-none');
        <?php } ?>
        
        $('.proc-save', $windowIdProc<?php echo $this->uniqId ?>).on('click', function(){
            $windowIdProc<?php echo $this->uniqId ?>.find('form').ajaxSubmit({
                type: 'post',
                url: 'mdproc/save',
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    if (data.status == 'success') {
                    }
                    Core.unblockUI();
                },
                error: function() {
                    alert('Error');
                    Core.unblockUI();
                }
            });            
        });
        var $notify_type = '<?php echo $this->proc_required_percent_comparison; ?>';
        if( $notify_type === '1'){
            $('.proc-indicator-value', $windowIdProc<?php echo $this->uniqId ?>).addClass('error')
        }
        $(document.body).on("keydown", '.proc-indicator-value', function(e) {
            if (e.which == 13) {
                indicator<?php echo $this->uniqId ?>($(this).data('id'), $(this).val());
               
                PNotify.removeAll();

                if($notify_type === '1'){
                    new PNotify({
                        title: 'Анхааруулга',
                        text: 'Оноог оруулна уу',
                        type: 'info',
                        sticker: false,
                        addclass: 'pnotify-center'
                    });
                }
            }
        });
        
        $('.supplier-point-proc', $windowIdProc<?php echo $this->uniqId ?>).on('change', function(){
            var $this = $(this);
            var tdindex = $this.closest('td').index() - 2;
            var tdSum,
                pointMax = $('input[data-pointid="'+$this.data('id')+'"]', $windowIdProc<?php echo $this->uniqId ?>).val();
            
            if (Number(pointMax) < Number($this.val())) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Хамгийн ихдээ ' + pureNumberFormat(pointMax) + ' байна!',
                    type: 'warning',
                    sticker: false
                });           
                setTimeout(function() {
                    $this.val('');
                }, 5);
            }
            
            setTimeout(function() {
                tdSum = $('.supplier-point-proc-'+$this.data('supid'), '#proc_tab_<?php echo $this->uniqId ?>' + $this.data('id')).sum();

                $('#proc_tab_<?php echo $this->uniqId ?>' + $this.data('id')).find('table.bprocess-theme1-proc2 > tfoot > tr:first-child > td:eq('+tdindex+')').text(pureNumberFormat(tdSum));
                indicator<?php echo $this->uniqId ?>($this.data('id'), $('input[data-id="'+$this.data('id')+'"]', $windowIdProc<?php echo $this->uniqId ?>).val());
            }, 10);
        });
        
        if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
        }        
        
        $('input[data-id="1551340381857"]').trigger('change');

        $(document.body).on('click', 'table.bprocess-table-dtl > thead > tr > th.bp-head-sort-proc', function(){
        
            var $this = $(this), $table = $this.closest('table'), $tbody = $table.find('tbody:eq(0)');
            
            if ($tbody.find('tr').length > 1) {                
                var el = $tbody.children('tr.item-main-row'), len = el.length, i = 0;
                for (i; i < len; i++) {
                    if (!$(el[i]).find('i').hasClass('icon-circle-down2')) {
                        $(el[i]).find('i').removeClass('icon-circle-up2').addClass('icon-circle-down2');
                        $('.item-' + ($(el[i]).data('itemid')), $windowIdProc<?php echo $this->uniqId ?>).addClass('hidden');                    
                    }
                }                
                
                var $colIndex = $this.index(), $fieldTypeElem = $tbody.find('tr:eq(0) > td:eq('+$colIndex+')'), $fieldType = '';
                
                if ($fieldTypeElem.find('input.bigdecimalInit:eq(0)').length > 0) {
                    $fieldType = 'number';
                } else if ($fieldTypeElem.find('div.checker').length > 0) {
                    $fieldType = 'checkbox';
                } else if ($fieldTypeElem.find('div.meta-autocomplete-wrap').length > 0) {
                    $fieldType = 'lookup';
                } else if ($fieldTypeElem.find('input[type=text]:eq(0)').length > 0) {
                    $fieldType = 'text';
                } else {
                    $fieldType = 'text';
                }
                
                $table.find('thead:eq(0) > tr > th').removeClass('bp-head-sort-proc-asc bp-head-sort-proc-desc');
                
                var rows = $tbody.children('tr.item-main-row').toArray().sort(bpComparer($colIndex, $fieldType));
                this.asc = !this.asc;
                
                if (!this.asc) { 
                    $this.removeClass('bp-head-sort-proc-asc').addClass('bp-head-sort-proc-desc');
                    rows = rows.reverse(); 
                } else {
                    $this.removeClass('bp-head-sort-proc-desc').addClass('bp-head-sort-proc-asc');
                }
                for (var i = 0; i < rows.length; i++) {
                    $tbody.append(rows[i]);
                }                
                
                var el = $tbody.children('tr.item-main-row'), len = el.length, i = 0;
                for (i; i < len; i++) { 
                    $(el[i]).find('td:eq(0) > span').text(i + 1);
                }
            }
        });        

        $(".bp-filter-row-proc > th > input").on("keyup", function() {
            var value = $(this).val();
            var $this = $(this), $table = $this.closest('table'), 
                $tbody = $table.find('tbody:eq(0)');
            var thindex = $(this).closest('th').index();
            value = value.trim().toLowerCase();

            $tbody.find("> tr").each(function(index) {
                var $row = $(this);

                if ($row.is(':visible')) {
                    var id = $row.find("td:eq("+thindex+")").text();
                    id = id.replace(/[,]/g, '').trim().toLowerCase();                

                    if (id.indexOf(value) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                }
            });
        });

        if ($('.proc-indicator-value', $windowIdProc<?php echo $this->uniqId ?>).length) {
            $('.proc-indicator-value', $windowIdProc<?php echo $this->uniqId ?>).each(function(){
                $(this).trigger('change');
            });
        }

        if(typeof tinymce === 'undefined'){ 
        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function(){
          initInlineTinyMceEditor();
        });
      } else {
        tinymce.remove('textarea#headerMoreDescription');
        setTimeout(function(){
          initInlineTinyMceEditor();
        }, 100);
      }

      $(document).on('focusin', function(e){
        if($(event.target).closest(".mce-window").length){
          e.stopImmediatePropagation();
        }
      });     
        
    });

    function initInlineTinyMceEditor(){
        tinymce.dom.Event.domLoaded=true;
        tinymce.baseURL=URL_APP + 'assets/custom/addon/plugins/tinymce';
        tinymce.suffix='.min';

        tinymce.init({
          selector: '#headerMoreDescription',
          plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager lineheight'
          ],
          toolbar1: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | forecolor backcolor | fontselect | fontsizeselect | fullscreen',
          fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 16px 18px 20px 24px 36px',
          image_advtab: true,
          toolbar_items_size: 'small',
          force_br_newlines: true,
          force_p_newlines: false,
          forced_root_block: '',
          paste_data_images: true,
          menubar: false,
          statusbar: true,
          paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
          table_toolbar: '', 
          resize: true,
          theme_advanced_statusbar_location: '',
          elementpath: false,
          table_default_styles: {
            width: '100%', 
            height: '100%'
          }, 
          document_base_url: URL_APP, 
          content_css: URL_APP+'assets/custom/css/print/tinymce_email.css'
        });
      }       
    
    function itemMore<?php echo $this->uniqId ?>(id, elem, indexKey) {
        var $this = $(elem);
        
        if ($this.find('i').hasClass('icon-circle-down2')) {
            $this.find('i').removeClass('icon-circle-down2').addClass('icon-circle-up2');
            $('.item-' + id + '-' + indexKey, $windowIdProc<?php echo $this->uniqId ?>).removeClass('hidden');

            var getHiddenHtml = $('.item-' + id + '-' + indexKey, $windowIdProc<?php echo $this->uniqId ?>).clone();
            $('.item-' + id + '-' + indexKey, $windowIdProc<?php echo $this->uniqId ?>).remove();
            $this.closest('tr').after(getHiddenHtml);
            
            if ($('.item-' + id + '.kpitemplate', $windowIdProc<?php echo $this->uniqId ?>).text().trim().length === 0) {
                var templId = $this.closest('tr').find('input[name="templateId[]"]').val();
                
                if (templId) {
                    $.ajax({
                        type: 'post',
                        url: 'mdform/renderKpiTemplateFormProcurement', 
                        data: {
                            templateId: $this.closest('tr').find('input[name="templateId[]"]').val(),
                            itemId: indexKey,
                            supplierKpiData: $this.closest('tr').find('.kpidmdtlobject').serialize(),
                            rfId: $('input[name="rfId"]', $windowIdProc<?php echo $this->uniqId ?>).val()
                        },
                        dataType: 'json',
                        async: false, 
                        success: function (data) {
                            PNotify.removeAll();

                            if (data.status === 'success') {

                                var indicatorStr = '',
                                    customerCount = <?php echo count($this->getProcCustomerList) ?>;

                                var factColspan;
                                for (var i = 0; i < data.indicator.length; i++) {
                                    indicatorStr += '<tr class="kpitemplate item-' + id + '">';
                                    indicatorStr += '<td colspan="2"></td>';
                                    indicatorStr += '<td colspan="2" class="text-left" style="background-color: rgb(255, 255, 255);">' + data.indicator[i] + '</td>';

                                    for (var ii = 0; ii < customerCount; ii++) {
                                        factColspan = data.facts[data.suppliers[ii]['supplierid']].length === 1 ? 2 : 2;

                                        for (var key in data.facts[data.suppliers[ii]['supplierid']]) {
                                            indicatorStr += '<td class="stretchInput" colspan="' + factColspan + '">';
                                            indicatorStr += data.facts[data.suppliers[ii]['supplierid']][key][i] ? data.facts[data.suppliers[ii]['supplierid']][key][i].replace('[kpiDmDtl', '[kpidmdtl'+id+'_'+data.suppliers[ii]['supplierid']).replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1$3') : '';
                                            indicatorStr += '</td>';
                                        }
                                    }
                                    indicatorStr += '<td colspan="2"></td>';
                                    indicatorStr += '</tr>';
                                }

                                $('.item-' + id + '.kpitemplate', $windowIdProc<?php echo $this->uniqId ?>).after(indicatorStr);
                                $('.item-' + id + '.kpitemplate', $windowIdProc<?php echo $this->uniqId ?>).find('select,input').addClass('form-control-sm').prop('disabled', true);
                            }
                        },
                        error: function () {
                            alert("Error");
                        }
                    });        
                }
            }
            
        } else {
            $this.find('i').removeClass('icon-circle-up2').addClass('icon-circle-down2');
            $('.item-' + id + '-' + indexKey, $windowIdProc<?php echo $this->uniqId ?>).addClass('hidden');
        }
    }
    
    function indicator<?php echo $this->uniqId ?>($id, elem) {
        var thisVal = typeof elem === 'string' ? pureNumber(elem) : pureNumber($(elem).val()), castNum, numProc;
        
        if ($('.proc-indicator-value', $windowIdProc<?php echo $this->uniqId ?>).sum() > 100) {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Хамгийн ихдээ 100 байна!',
                type: 'warning',
                sticker: false
            });    
            
            if (typeof elem !== 'string') {
                setTimeout(function() {
                    $(elem).val('');
                }, 10);
            }
            
            thisVal = 0;
        }
        
        if ($id == 1551340381857) {
            var minVal = pureNumber('<?php echo $supplierTotalMinSum ?>');

            var $tableFoot1 = $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('table > tfoot > tr:eq(2) > td.sum-total-amount');
            var $tableFoot2 = $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('table > tfoot > tr:last-child > td.sum-percent-total-amount');

            $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('table > tfoot > tr:last-child > td:eq(0) > span').text(thisVal + '%');
            $tableFoot1.each(function(k, r){
                castNum = pureNumber($(this).text());
                numProc = pureNumberFormat(minVal / castNum * thisVal);
                numProc = isNaN(numProc) ? '' : numProc;                
                $tableFoot2.eq(k).text(numProc);
                
                if ($('input[name="indicatorPercentValue'+$tableFoot2.eq(k).data('footer-supplierid')+k+'_'+$id+'"]', $windowIdProc<?php echo $this->uniqId ?>).length === 0) {
                    $('.proc-hidden-params', $windowIdProc<?php echo $this->uniqId ?>).append('<input type="hidden" value="'+numProc+'" name="indicatorPercentValue'+$tableFoot2.eq(k).data('footer-supplierid')+k+'_'+$id+'">');
                } else {
                    $('input[name="indicatorPercentValue'+$tableFoot2.eq(k).data('footer-supplierid')+k+'_'+$id+'"]', $windowIdProc<?php echo $this->uniqId ?>).val(numProc);
                }                            
            });
        } else {

            if (thisVal > 0) {
                $('a[href="#proc_tab_<?php echo $this->uniqId ?>' + $id + '"]').removeClass('hidden');
            } else {
                $('a[href="#proc_tab_<?php echo $this->uniqId ?>1551340381857"]').tab('show');
                $('a[href="#proc_tab_<?php echo $this->uniqId ?>' + $id + '"]').addClass('hidden');
                
                $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('input[type="text"]').val('');
                $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('td.sum-total-amount-other').text('');
                $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('td.sum-percent-total-amount-other').text('');
            }
            
            var $tableFoot1 = $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('table > tfoot > tr:first-child > td.sum-total-amount-other');
            var $tableFoot2 = $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('table > tfoot > tr:last-child > td.sum-percent-total-amount-other');
            var maxVal = Number($('input[data-pointid="'+$id+'"]', $windowIdProc<?php echo $this->uniqId ?>).val());

            $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('table > tfoot > tr:last-child > td:eq(1)').text(thisVal + '%');
            $tableFoot1.each(function(k, r){
                castNum = pureNumber($(this).text());
                numProc = pureNumberFormat(castNum /  maxVal * thisVal);
                numProc = isNaN(numProc) ? '' : (numProc / $('#proc_tab_<?php echo $this->uniqId ?>' + $id).find('table > tbody > tr').length).toFixed(2);
                $tableFoot2.eq(k).text(numProc);
                
                if ($('input[name="indicatorPercentValue'+$tableFoot2.eq(k).data('footer-supplierid')+k+'_'+$id+'"]', $windowIdProc<?php echo $this->uniqId ?>).length === 0) {
                    $('.proc-hidden-params', $windowIdProc<?php echo $this->uniqId ?>).append('<input type="hidden" value="'+numProc+'" name="indicatorPercentValue'+$tableFoot2.eq(k).data('footer-supplierid')+k+'_'+$id+'">');
                } else {
                    $('input[name="indicatorPercentValue'+$tableFoot2.eq(k).data('footer-supplierid')+k+'_'+$id+'"]', $windowIdProc<?php echo $this->uniqId ?>).val(numProc);
                }               
            });            
            
            var maxCheckVals = {};
            $('.proc-indicator-point-value', $windowIdProc<?php echo $this->uniqId ?>).each(function(kk, rr){
                var maxVal2 = Number($(this).val());
                var poId = $(this).data('pointid');
                $('.supplier-point-proc', $windowIdProc<?php echo $this->uniqId ?>).each(function(k, r){
                    var castNum2 = pureNumber($(this).val());
                    var numProc2 = pureNumberFormat(castNum2 /  maxVal2 * thisVal);
                    numProc2 = isNaN(numProc2) ? '' : (numProc2 / $('#proc_tab_<?php echo $this->uniqId ?>' + poId).find('table > tbody > tr').length).toFixed(2);            
                    if (Number(numProc2)) {
                        var trindex = $(this).closest('tr').index();                        
                        if (maxCheckVals[trindex] && maxCheckVals[trindex]['point'] < Number(numProc2)) {
                            maxCheckVals[trindex] = {point: Number(numProc2), suppId: $(this).data('supplierid')};
                        } else if (!maxCheckVals[trindex]) {
                            maxCheckVals[trindex] = {point: Number(numProc2), suppId: $(this).data('supplierid')};
                        }                        
                    }
                });
            });
            
            $.each(Object.keys(maxCheckVals), function(key, value) {
                $('.bprocess-theme1-proc > tbody > tr.item-main-row:eq('+key+')', $windowIdProc<?php echo $this->uniqId ?>).find('input[value="'+maxCheckVals[key]['suppId']+'"]').closest('.selected-supplier').trigger('click');
            });
        }
         
    }
    
    function onChangeAttachFIleAddMode(input){
      if($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx",
        "zip", "rar", "mp3", "mp4", "msg"])){
        var ext=input.value.match(/\.([^\.]+)$/)[1],
            i = 0;
        if(typeof ext !== "undefined"){
          
          for(i; i < input.files.length; i++) {
            ext=input.files[i].name.match(/\.([^\.]+)$/)[1];
            
            var li='',
                    fileImgUniqId=Core.getUniqueID('file_img'),
                    fileAUniqId=Core.getUniqueID('file_a'),
                    extension=ext.toLowerCase();

            li='<li class="meta">' +
                    '<figure class="directory">' +
                    '<div class="img-precontainer">' +
                    '<div class="img-container directory">';
            if(extension == 'png' ||
                    extension == 'gif' ||
                    extension == 'jpeg' ||
                    extension == 'pjpeg' ||
                    extension == 'jpg' ||
                    extension == 'x-png' ||
                    extension == 'bmp'){
              li+='<a href="javascript:;" id="' + fileAUniqId + '" class="fancybox-button main" data-rel="fancybox-button">';
              li+='<img src="" id="' + fileImgUniqId + '"/>';
              li+='</a>';
            } else {
              li+='<a href="javascript:;" title="">';
              li+='<img src="assets/core/global/img/filetype/64/' + (extension == 'msg' ? 'zip' : extension) + '.png"/>';
              li+='</a>';
            }

            li+='</div>' +
                    '</div>' +
                    '<div class="box">'+
                    '<h4 class="ellipsis">'+input.files[i].name+'</h4>' +
                    '</div>' +
                    '</a>' +
                    '</figure>' +
                    '</li>';
            var $listViewFile=$('.list-view-file-new');
            $listViewFile.append(li);
            Core.initFancybox($listViewFile);
            Core.initUniform($listViewFile);

            previewPhotoAddMode(input.files[i], $listViewFile.find('#' + fileImgUniqId), $listViewFile.find('#' + fileAUniqId));

            initFileContentMenuAddMode();
          }
          var $this=$(input), $clone=$this.clone();
          $this.after($clone).appendTo($('.hiddenFileDiv', $windowIdProc<?php echo $this->uniqId ?>));          
            
        }
      }
      else {
        alert('Файл сонгоно уу.');
        $(input).val('');
      }
    }

    function previewPhotoAddMode(input, $targetImg, $targetAnchor){
      if(input){
        var reader=new FileReader();
        reader.onload=function(e){
          $targetImg.attr('src', e.target.result);
          $targetAnchor.attr('href', e.target.result);
        };
        reader.readAsDataURL(input);
      }
    }

    function initFileContentMenuAddMode(){
      $.contextMenu({
        selector: 'ul.list-view-file-new li.meta',
        callback: function(key, opt){
          if(key === 'delete'){
            deleteBpTabFileAddMode(opt.$trigger);
          }
        },
        items: {
          "delete": {name: "Устгах", icon: "trash"}
        }
      });
    }

    function deleteBpTabFileAddMode(li){
      var dialogName='#deleteConfirm';
      if(!$(dialogName).length){
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
      }
      $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
      $(dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Сануулах',
        width: '350',
        height: 'auto',
        modal: true,
        buttons: [
          {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function(){
              li.remove();
              $(dialogName).dialog('close');
            }},
          {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function(){
              $(dialogName).dialog('close');
            }}
        ]
      });
      $(dialogName).dialog('open');
    }    
</script>