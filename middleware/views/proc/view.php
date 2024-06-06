<div class="w-100" id="windowIdProc<?php echo $this->uniqId; ?>">    
    <div class="mt10">
        <div class="col-md-12">
            <div class="mb10 row">
                <h3 class="text-center w-100" style="color: #080882"><?php echo Lang::lineDefault('CAP_AF', 'ХУДАЛДАН АВАЛТЫН ХАРЬЦУУЛСАН СУДАЛГАА, ЗӨВШӨӨРЛИЙН ХУУДАС'); ?></h3>
            </div>
            <div class="mb10 row">
                <div class="col-md-8">
                    <button class="btn btn-sm blue view-proc-compare" data-id="<?php echo $this->id ?>" tabindex="-1"><?php echo Lang::lineDefault('See_details', 'Дэлгэрэнгүйг харах'); ?></button>
                    <?php
                    $procIsQty = $this->procIsQty == '1' ? '3' : '2';
                    $procIsQty2 = $this->procIsQty == '1' ? '2' : '1';

                    if (isset($this->wfmStatusParams['result'])) {
                        
                        $singleMenuHtml = '';

                        foreach ($this->wfmStatusParams['result'] as $wfmstatusRow) {
                            $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'1526524155528\', \'' . trim(issetVar($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetVar($wfmstatusRow['wfmstatusname']) . '\', \'\', \'changeHardAssign\',  \'\', undefined, \'1569494384546\', undefined , undefined , \'' . $wfmstatusRow['wfmstatusprocessid'] . '\' , \'' . $wfmstatusRow['wfmisdescrequired'] . '\', \'\', \'1\');"';
                            $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' class="btn btn-sm purple-plum btn-circle" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['processname'] .'</button> ';
                        }

                        echo '<span class="ml15"></span>' . $singleMenuHtml; 
                    }                    
                    ?>
                </div>
                <div class="col-md-4">
                    <div class="text-right"><strong><?php echo Lang::lineDefault('Made_comparison', 'Харьцуулалт хийсэн'); ?>: <?php echo $this->getProcCustomerItemList['firstname'] ?></strong></div>
                </div>
            </div>
            <div class="bp-overflow-xy-auto" style="max-height: 450px; overflow: auto;">
                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 bprocess-theme1-viewproc">
                    <thead>
                        <tr>
                            <th rowspan="3" class="rowNumber" style="width: 30px; background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;">№</th>
                            <th rowspan="3" class="itemnameheader" style=""><?php echo Lang::lineDefault('MET_330923', 'Нийлүүлэгч'); ?></th>
                            <th rowspan="3" class="description" style=""><?php echo Lang::lineDefault('total_rebate', 'Нийт дүн'); ?></th>                        
                            <?php 
                            $customersTh = '';
                            $customersTh1 = '';
                            if ($this->getProcCustomerList) {
                                foreach ($this->getProcCustomerList as $row) {
                                    echo '<th class="" data-aggregate="sum" style="width: 230px;">' . $row['positionname'] . '</th>';
                                    $customersTh .= '<th class=" unitprice" style="">' . $row['firstname'] . '</th>';
                                    $customersTh1 .= '<th class=" unitprice" style="">' . Date::formatter($row['approveddate'], 'Y-m-d H:i') . '</th>';
                                }
                                
                                $colspanCount = 0;
                                $percentSum = 0;
                                foreach ($this->getProcCustomerItemList['ext_comparison_kpi'] as $romKpi) {
                                    // if ($romKpi['percent']) {
                                        $colspanCount++;
                                        $customersTh .= '<th class=" unitprice" style="">' . $romKpi['indicatorname'] . '</th>';
                                        $customersTh1 .= '<th class=" unitprice" style="">' . $romKpi['percent'] . '</th>';
                                        $percentSum += (float) $romKpi['percent'];
                                    // }
                                }
                                
                                echo '<th colspan="'.$colspanCount.'" class="" data-aggregate="sum" style="width: 250px;">'.Lang::lineDefault('Үнэлгээ', 'Үнэлгээ').'</th>';
                            }
                            ?>                                    
                            <th rowspan="2" style="width:100px"><?php echo Lang::lineDefault('PL_0131', 'Нийт'); ?></th>
                        </tr>
                        <tr>        
                            <?php echo $customersTh ?>
                        </tr>
                        <tr>        
                            <?php 
                            echo $customersTh1; 
                            echo '<th>'.$percentSum.'</th>';
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        if ($this->getProcCustomerItemList) {
                            $paymentType = $countryName = '';
                            $looped = true; $suppliers = array();
                            $cusIndex = 0;
                            $supplierIndicatorVal = Arr::groupByArray($this->getProcCustomerItemList['ext_comparison_indicator'], 'supplierid');

                            foreach ($this->getProcCustomerItemList['supplierdtllist'] as $key => $row) { 
                                $totalPrice = 0;                                        

                                ?>
                                <tr class="saved-bp-row added-bp-row" data-itemid="<?php echo $row['supplierid'] ?>">
                                    <td class="text-center middle" style="background-color: rgb(255, 255, 255); position: relative; z-index: 9; background-clip: padding-box; left: 0px;"><span><?php echo ++$key ?></span></td>
                                    <td class="itemname"><?php echo $row['suppiername'] ?></td>
                                    <td class="text-right"><?php echo Number::amount($row['totalamount']) ?></td>
                                    <?php if ($row['approvedtllist']) {
                                        $scutomGroup1 = Arr::groupByArray($row['approvedtllist'], 'approveduserid');                                        

                                        foreach ($this->getProcCustomerList as $cus) {
                                            if (array_key_exists($cus['approveduserid'], $scutomGroup1)) {
                                                $scutomGroup2 = Arr::groupByArray($scutomGroup1[$cus['approveduserid']]['rows'], 'approveddate');
                                                
                                                if (array_key_exists($cus['approveddate'], $scutomGroup2) && array_key_exists($cus['approveduserid'], $scutomGroup1)) {
                                                    $getSingle = Arr::groupByArray($scutomGroup2[$cus['approveddate']]['rows'], 'supplierid');
                                                    echo '<td class="text-center">' . (isset($getSingle[$row['supplierid']]) && $getSingle[$row['supplierid']]['row']['isselected'] === '1' ? '<i class="icon-checkmark2" style="font-size:20px"></i>' : '') . '</td>';
                                                }
                                            }
                                        }                                           
                                    }                                     
                                             
                                    foreach ($this->getProcCustomerItemList['ext_comparison_kpi'] as $romKpi) {
                                        if ($romKpi['ext_comparison_kpi_dtl']) {                                                                                        
                                            if (array_key_exists($row['supplierid'], $supplierIndicatorVal)) {
                                                $kpiGroup = Arr::groupByArray($supplierIndicatorVal[$row['supplierid']]['rows'], 'indicatorid');

                                                if (array_key_exists($romKpi['indicatorid'], $kpiGroup)) {
                                                
                                                    if (count($kpiGroup[$romKpi['indicatorid']]['rows']) === 1) {
                                                        $cusIndex = 0;
                                                    }                                                         
                                                    
                                                    if ($kpiGroup[$romKpi['indicatorid']]['rows'][$cusIndex]['percent']) {
                                                        $totalPrice += $kpiGroup[$romKpi['indicatorid']]['rows'][$cusIndex]['percent'];
                                                        echo '<td class="text-right">' . Number::amount($kpiGroup[$romKpi['indicatorid']]['rows'][$cusIndex]['percent']) . '</td>';
                                                    } else {
                                                        echo '<td class="text-right"></td>';
                                                    }
                                                }
                                            }                                            
                                        }
                                    }
                                    
                                    echo '<td class="text-right total-amt">' . Number::amount($totalPrice) . '</td>';
                                    $cusIndex++;          
                                    ?>
                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>            
        </div>
        <hr>
        <div class="col-md-12">
            <?php foreach ($this->getProcCustomerList as $cusRow) { 
                if ($cusRow['description']) {
                ?>
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between" style="padding: 12px;">
                        <span><i class="icon-user-check mr-2"></i> <a href="javascript:;"><?php echo $cusRow['firstname'] ?></a></span>
                        <span class="text-muted"><?php echo Date::formatter($cusRow['approveddate'], 'Y-m-d H:i') ?></span>
                    </div>
                    <div class="card-body">
                        <p class="card-text" style="padding: 12px;color: #000;font-size: .875rem;"><?php echo $cusRow['description'] ?></p>
                    </div>
                </div>            
            <?php }} ?>
        </div>
    </div>
</div>

<style type="text/css">
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-table-dtl{
        table-layout: fixed !important; 
        max-width: 3433px !important;
    } 
    #windowIdProc<?php echo $this->uniqId ?> .itemnameheader {
        width:300px !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> .description {
        width:150px !important;
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
        border-color: #333;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1 > tfoot > tr > td {
        border-top: none;
        padding-right: 3px !important;
        padding-left: 3px !important;                                
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-viewproc > tbody > tr > td, 
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-viewproc > tbody > tr:last-child > td:first-child {
        padding-right: 3px !important;
        padding-left: 3px !important;
    }
    #windowIdProc<?php echo $this->uniqId ?> table.bprocess-theme1-viewproc > tbody > tr > td > table > tbody > tr > td {
        border-left: none;
        border-right: none;
        border-bottom: none;
        padding-right: 3px !important;
        padding-left: 3px !important;                                
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
</style>

<script type="text/javascript">
    var $windowIdProc<?php echo $this->uniqId ?> = $("#windowIdProc<?php echo $this->uniqId ?>");

    $(function () {
        $('table.bprocess-theme1-viewproc', $windowIdProc<?php echo $this->uniqId ?>).tableHeadFixer({'head': true, 'left': 3, 'z-index': 9});
        
        $('.view-proc-compare', $windowIdProc<?php echo $this->uniqId ?>).on('click', function(){
            procPageEdit($(this).data('id'), 'mdproc/edit', 'view');
        });
    });
    
</script>