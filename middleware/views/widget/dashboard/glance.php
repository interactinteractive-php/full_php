<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="sales_widget_window_<?php echo $this->uniqId; ?>">
    <div class="row">
        <div class="col-md-12 no-padding m-0" style="background-color: #2d9ada;background-image: url(../../../middleware/assets/theme/metro/img/blue-bg.jpg);background-repeat: no-repeat;background-size: cover;">
            <div class="col-md-6">
                <div class="main-title-<?php echo $this->uniqId ?>">
                    <h2 class="h2_style_v2" style="margin: 18px 0px 0 50px;">
                        <!-- <span><i class="fa fa-users"></i></span>  -->
                        <?php echo $this->lang->line('execDB0_0'); ?>
                    </h2>
                </div>
            </div>
            <div class="col-md-6 dc-data-<?php echo $this->uniqId ?>">
                <div class="col-md-12" style="margin-top: 8px">
                    <div class="col-md-4">
                        <label class="header-title-dd" for="start-date">Эхлэх </label>  
                        <label class="header-value-dd"><input type="text" class="" value="<?php echo $this->startDate; ?>" style="width: 100px" id="start-date"></label>
                    </div>
                    <div class="col-md-8">
                    <label class="header-title-dd" for="select-departmentIds">Салбар нэгж </label>  
                        <label class="header-value-dd">
                        <select class="departmentIds form-control select2" id="select-departmentIds" placeholder="- Сонгох -">
                            <?php 
                            if($this->depList) {
                                echo '<option value="">- Сонгох -</option>';                            
                                foreach($this->depList as $key => $row) {
                                    $sel = '';
                                    if($row['departmentid'] == $this->depId)
                                       $sel = ' selected';
                                    echo '<option' . $sel . ' value="' . $row['departmentid'] . '">' . $row['departmentname'] . '</option>';
                                }
                            } ?>
                        </select>                      
                        </label>                
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <label class="header-title-dd" for="end-date">Дуусах </label>  
                        <label class="header-value-dd"><input type="text" value="<?php echo $this->endDate; ?>" style="width: 100px" id="end-date"></label>                
                    </div>
                    <div class="col-md-8">
                        <label class="header-title-dd" for="isHierarchy">Харъяа нэгж багтах </label>  
                        <label class="header-value-dd">
                            <input type="checkbox" id="isHierarchy" class="" style="margin-left: 8px;" <?php echo $this->isHierarchy == '1' ? 'checked' : ''; ?> value="1"> 
                            <button id="date-filter" class="float-right" style="color: #333;"><i class="fa fa-search"></i></button>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12">
        <div class="col-md-6 col-sm-6 no-padding" style="background-color:#fff;">
            <div id="sales2_widget_chart_<?php echo $this->uniqId; ?>">
                <div class="col-md-12" style="text-align: center;">
                    <h2 class="h2_style_2" ><?php echo $this->lang->line('execDB0_1')  ?></h2>
                </div>
                <div class="col-md-5 p-0" style="width: 43%;padding: 45px 10px !important;position: absolute;right: 0px;top: 75px;/* background: #a2d559; */">
                    <div class="col-md-12 p-0" style="border: 1px solid #ccc; height:80px">
                        <div class="numberWidget mt15">
                        <i class="fa fa-money" style="    position: absolute;font-size: 36px;color: #ccc;margin-top: 18px;left: 5%;"></i>
                            <div class="number" style="text-align: center;">
                                <div class="main_number" style="color:#F28455;"><?php echo $this->positionData1[0]['plannedcur']. ' '.$this->positionData1[0]['plannedamount'] ?></div>
                                <div class="mt15" style="font-size:15px;color: #9a9a9a;text-transform: uppercase;font-weight: normal;"><?php echo $this->positionData1[0]['plannedtext'] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-0 mt5" style="border: 1px solid #ccc; height:80px">
                        <div class="numberWidget mt15">
                        <i class="fa fa-money" style="    position: absolute;font-size: 36px;color: #ccc;margin-top: 18px;left: 5%;"></i>
                            <div class="number" style="text-align: center;">
                                <div class="main_number" style="color:#F28455;"><?php echo $this->positionData1[0]['actualcur']. ' '.$this->positionData1[0]['actualamount'] ?></div>
                                <div class="mt15" style="font-size:15px;color: #9a9a9a;text-transform: uppercase;font-weight: normal;"><?php echo $this->positionData1[0]['actualtext'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 p-0" style="width: 55%">
                    <div class="col-md-12 p-0">
                        <div id="glance-dashboard-<?php echo $this->uniqId; ?>"></div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 no-padding" style="background-color:#fff;">
            <div id="sales2_widget_chart_<?php echo $this->uniqId; ?>">
                <div class="col-md-12" style="text-align: center;">
                    <h2 class="h2_style_2" ><?php echo $this->lang->line('execDB0_2')  ?></h2>
                </div>
                <div class="col-md-5 p-0" style="width: 43%;padding: 45px 10px !important;position: absolute;right: 0px;top: 75px;/* background: #a2d559; */">
                    <div class="col-md-12 p-0" style="border: 1px solid #ccc; height:80px">
                        <div class="numberWidget mt15">
                            <i class="fa fa-money" style="    position: absolute;font-size: 36px;color: #ccc;margin-top: 18px;left: 5%;"></i>
                            <div class="number" style="text-align: center;">
                                <div class="main_number" style="color:#F28455;"><?php echo $this->positionData2[0]['plannedcur']. ' '.$this->positionData2[0]['plannedamount'] ?></div>
                                <div class="mt15" style="font-size:15px;color: #9a9a9a;text-transform: uppercase;font-weight: normal;"><?php echo $this->positionData2[0]['plannedtext'] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-0 mt5" style="border: 1px solid #ccc; height:80px">
                        <div class="numberWidget mt15">
                        <i class="fa fa-money" style="    position: absolute;font-size: 36px;color: #ccc;margin-top: 18px;left: 5%;"></i>
                            <div class="number" style="text-align: center;">
                                <div class="main_number" style="color:#F28455;"><?php echo $this->positionData2[0]['actualcur']. ' '.$this->positionData2[0]['actualamount'] ?></div>
                                <div class="mt15" style="font-size:15px;color: #9a9a9a;text-transform: uppercase;font-weight: normal;"><?php echo $this->positionData2[0]['actualtext'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 p-0" style="width: 55%">
                    <div class="col-md-12 p-0">
                        <div id="glance-dashboard-1-<?php echo $this->uniqId; ?>"></div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    
    <div class="col-md-12 col-sm-12">
        <div class="col-md-12">
            <div class="col-md-12">
                <h2 class="h2_style_2" >
                    <?php 
                    $addminText = (isset($this->positionData5) && $this->positionData5)  ? '' : 'Хүсэлт олдсонгүй';
                    echo $this->lang->line('execDB0_5') . ' ' . $addminText; ?>
                </h2>
            </div>
            <div id="objectdatagrid-<?php echo $this->sdataViewId ?>" class="not-datagrid div-objectdatagrid-<?php echo $this->sdataViewId ?>">
                <div class="mt-actions">
                    <?php if (isset($this->positionData5)) {
                        foreach ($this->positionData5 as $row) { 
                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                            <li class="dv-explorer-row " style="list-style: none;">
                                <div class="selected-row-link" data-row-data="<?php echo $rowJson; ?>">
                                    <div class="mt-action" >
                                        <div class="mt-action-img">
                                            <img src="<?php echo $row['employeepicture'] ?>" onerror="onUserImgError(this);"> 
                                        </div>
                                        <div class="mt-action-body">
                                            <div class="mt-action-row">
                                                <div class="mt-action-info ">
                                                    <div class="mt-action-details ">
                                                        <span class="mt-action-author"><?php echo $row['employeename'] ?></span>
                                                        <p class="mt-action-desc"><?php echo $row['departmentname'] ?></p>
                                                    </div>
                                                    <div class="mt-action-icon vertical-align-middle mt-action-detail-more">
                                                        <a class="" href="javascript:;" 
                                                            onclick="drillDownTransferProcessAction('transferProcessAction', '1', '', '', '<?php echo $this->sdataViewId ?>', '1517395069003', 'processCriteria', '', this, {callerType: 'bmBudgetBookRequestList2', isDrillDown: true});">
                                                            <?php echo $row['purpose'] ?>
                                                        </a>
                                                    </div>
                                                    <div class="mt-action-icon vertical-align-middle">
                                                        <?php echo $row['requestamountbase'] . ' ' . $row['currencycode']  ?>
                                                    </div>
                                                    <div class="mt-action-icon vertical-align-middle">
                                                        <?php echo $row['booktypename']  ?>
                                                    </div>
                                                    <div class="mt-action-icon bg-green-confirm vertical-align-middle">
                                                        <a href="javascript:;" class="btn btn-circle btn-sm green" style="
                                                            <?php if (isset($row['wfmstatuscolor']) && $row['wfmstatuscolor']) { ?>
                                                                background: <?php echo $row['wfmstatuscolor']  ?>
                                                            <?php } ?> "> 
                                                            <?php if (isset($row['wfmstatusname']) && $row['wfmstatusname']) echo $row['wfmstatusname']  ?></a>
                                                    </div>
                                                </div>
                                                <div class="mt-action-datetime vertical-align-middle p-0">
                                                    <span class="mt-action-date"><?php echo Date::format('Y-m-d', $row['bookdate'])  ?></span>
                                                </div>
                                                <div class="mt-action-buttons vertical-align-middle p-0">
                                                    <div class="btn-group btn-group-circle">
                                                        <?php if (isset($row['pfnextstatuscolumn']) && $row['pfnextstatuscolumn']) { ?>
                                                            <?php if (isset($row['pfnextstatuscolumn'][0]['wfmstatusid'])) { ?>
                                                                <a type="button" href='javascript:;' data-row-data="<?php echo $rowJson; ?>" 

                                                                    <?php if (isset($row['pfnextstatuscolumn'][0]['wfmstatusprocessid']) && $row['pfnextstatuscolumn'][0]['wfmstatusprocessid']) { ?>
                                                                        onclick="transferProcessAction('', '<?php echo $this->sdataViewId ?>', '<?php echo $row['pfnextstatuscolumn'][0]['wfmstatusprocessid'] ?>', '200101010000011', 'toolbar', this, {callerType: 'bmBudgetBookRequestList2', isWorkFlow: true, wfmStatusId: '<?php echo $row['pfnextstatuscolumn'][0]['wfmstatusid'] ?>', wfmStatusCode: ''}, 'dataViewId=<?php echo $this->sdataViewId ?>&refStructureId=<?php echo $row['refstructureid'] ?>&statusId=<?php echo $row['pfnextstatuscolumn'][0]['wfmstatusid'] ?>&statusName=<?php echo $row['pfnextstatuscolumn'][0]['wfmstatusname'] ?>&statusColor=<?php echo $row['pfnextstatuscolumn'][0]['wfmstatuscolor'] ?>&rowId=<?php echo $row['id'] ?>');" 
                                                                    <?php } else { ?>
                                                                        onclick="changeWfmStatusId(this, '<?php echo $row['pfnextstatuscolumn'][0]['wfmstatusid'] ?>', '<?php echo $this->sdataViewId ?>', '<?php echo $row['refstructureid'] ?>', '<?php echo $row['pfnextstatuscolumn'][0]['wfmstatuscolor'] ?>', '<?php echo $row['pfnextstatuscolumn'][0]['wfmstatusname'] ?>');"
                                                                    <?php } ?>

                                                                   class="btn btn-outline btn-sm selected-row-link " style="color: #FFF; background: <?php echo $row['pfnextstatuscolumn'][0]['wfmstatuscolor']; ?>"><?php echo (!$row['pfnextstatuscolumn'][0]['wfmstatusicon']) ? '<i class="fa fa-cogs"></i>' : '<i class="fa '. $row['pfnextstatuscolumn'][0]['wfmstatusicon'] .'"></i>'; ?></a>
                                                            <?php } ?>
                                                            <?php if (isset($row['pfnextstatuscolumn'][1]['wfmstatusid'])) { ?>
                                                                <a type="button" href='javascript:;'
                                                                    <?php if (isset($row['pfnextstatuscolumn'][1]['wfmstatusprocessid']) && $row['pfnextstatuscolumn'][1]['wfmstatusprocessid']) { ?>
                                                                        onclick="transferProcessAction('', '<?php echo $this->sdataViewId ?>', '<?php echo $row['pfnextstatuscolumn'][1]['wfmstatusprocessid'] ?>', '200101010000011', 'toolbar', this, {callerType: 'bmBudgetBookRequestList2', isWorkFlow: true, wfmStatusId: '<?php echo $row['pfnextstatuscolumn'][1]['wfmstatusid'] ?>', wfmStatusCode: ''}, 'dataViewId=<?php echo $this->sdataViewId ?>&refStructureId=<?php echo $row['refstructureid'] ?>&statusId=<?php echo $row['pfnextstatuscolumn'][1]['wfmstatusid'] ?>&statusName=<?php echo $row['pfnextstatuscolumn'][1]['wfmstatusname'] ?>&statusColor=<?php echo $row['pfnextstatuscolumn'][1]['wfmstatuscolor'] ?>&rowId=<?php echo $row['id'] ?>');" 
                                                                    <?php } else { ?>
                                                                        onclick="changeWfmStatusId(this, '<?php echo $row['pfnextstatuscolumn'][1]['wfmstatusid'] ?>', '<?php echo $this->sdataViewId ?>', '<?php echo $row['refstructureid'] ?>', '<?php echo $row['pfnextstatuscolumn'][1]['wfmstatuscolor'] ?>', '<?php echo $row['pfnextstatuscolumn'][1]['wfmstatusname'] ?>');"
                                                                    <?php } ?> 
                                                                        class="btn btn-outline btn-sm selected-row-link " style="color: #FFF;  background: <?php echo $row['pfnextstatuscolumn'][1]['wfmstatuscolor']; ?>"><?php echo (!$row['pfnextstatuscolumn'][1]['wfmstatusicon']) ? '<i class="fa fa-cogs"></i>' : '<i class="fa '. $row['pfnextstatuscolumn'][1]['wfmstatusicon'] .'"></i>'; ?></a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
        <div class="col-md-12" style="height: 500px; margin-top: 60px;">
            <div class="col-md-6 p-0">
                <div id="glance-dashboard-2-<?php echo $this->uniqId; ?>"></div>
            </div>
            <div class="col-md-6 p-0">
                <div id="glance-dashboard-4-<?php echo $this->uniqId; ?>"></div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #Title-1 {
        border: 1px solid #F00;
    }
    
    .main_title {
        margin-left: 5px;
        
    }
    
    .percent-text {
        margin-top: 24px;
    }
    
    .percent-value {
        font-size: 28px;
        font-weight: normal;
    }
    
    .number-font-normal {
        font-weight: normal;
        font-size: 30px;
        margin-top: 15px;
    }
    
    .word-font-normal {
        margin-top: 16px;
    }
    
    .col-md-4.p-0.margin-minus-top {
        margin-top: -6px;
    }    
    
    .h2_style_v2 {
        color: #fff;
        margin-left: 20px;
        font-size: 27px;
    }
    
    .dc-data-<?php echo $this->uniqId; ?> .header-title-dd {
        font-size: 12px;
        width: 35%;
    }
    
    .dc-data-<?php echo $this->uniqId; ?> .header-value-dd {
        font-size: 12px;
        color: #fff;
        width: 60%;
    }
    
    .dc-data-<?php echo $this->uniqId; ?> {
        min-height: 68px;
        color: #fff;
    }
    
    button#date-filter {
        border: 1px solid #f7f7f7;
        border-radius: 50px;
        background: inherit;
        color: #FFF !important;
    }
    
    .dc-data-<?php echo $this->uniqId; ?> input {
        color: #FFF;
        background: inherit;
        border: none;
    }
    
    .float-right {
        float: right !important;
    }
    
    .h2_style .fa-users {
        font-size: 30px;
    }
    
    .border-with-color {
        background: #63830c;
        border-radius: 50px;
        color: #FFF;
        height: 30px;
        width: 30px;
        float: left;
        text-align: center;
        vertical-align: middle;
        position: absolute;
        right: 100px;
        top: 15px;
    }
    
    .border-with-color .fa-check {
        font-size: 15px;
        margin-top: 8px;
    }
    
    .heigh-94 {
        min-height: 57px;
    }
    
    .numberWidget {
        color: rgb(102, 102, 102); 
        font-weight: bold; 
        font-style: normal; 
        text-decoration: none; 
        font-size: 34px;
    }
    
    .numberWidget .number, .numberWidget .number-canvas {
        width: auto;
        
    }
    
    .wordWidget .number, .wordWidget .number-canvas {
        white-space: normal;
        word-wrap: break-word;
    }
    
    .wordWidget {
        color: rgb(102, 102, 102);
        font-weight: bold;
        font-style: normal;
        text-decoration: none;
        font-size: 12px;
        text-transform: uppercase;
    }
    
    .h2_style {
        margin: 0 auto;
        display: table;
        float: left;
        margin-top: 10px;
        top: 4.5px;
        padding-left: 7.62857px;
        color: #FFF;
        -webkit-text-fill-color: #FFF;
        background: #8cbfdca3;
        font-weight: normal;
        font-style: normal;
        text-decoration: none;
        text-align: left;
        padding: 10px;
        margin-left: 10px;
        margin-bottom: 10px;
    }
    
    .h2_style_2 {
        top: 11px;
        padding-left: 4.57714px;
        color: rgb(102, 102, 102);
        -webkit-text-fill-color: rgb(102, 102, 102);
        background-color: transparent;
        font-weight: bold;
        font-style: normal;
        text-decoration: none;
        text-align: left;
        font-size: 15px;
        text-rendering: auto;
        color: initial;
        letter-spacing: normal;
        word-spacing: normal;
        text-transform: none;
        text-indent: 0px;
        text-shadow: none;
        display: inline-block;
        margin-bottom: 40px;
        margin-top: 40px;
    }
    .select2-container .select2-choice>.select2-chosen{
        color:#FFF;
    }
    .select2-container .select2-choice .select2-arrow {
        background: inherit;
        border: none;
    }
    
    .form-control.select2-container {
        background: inherit;
        color: #FFF;
    }
    
    .select2-container .select2-choice {
        background: inherit;
        border: none;
    }
    
    #serial1_dashboard_<?php echo $this->uniqId; ?>, #sales2_widget_chart_<?php echo $this->uniqId; ?> {
        height: 245px;
    }
    
    #glance-dashboard-<?php echo $this->uniqId; ?>, #glance-dashboard-1-<?php echo $this->uniqId; ?> {
        min-height: 210px;
    }
    #glance-dashboard-2-<?php echo $this->uniqId; ?>, #glance-dashboard-4-<?php echo $this->uniqId; ?>  {
        min-height: 350px;
    }
    .mt-actions .mt-action .mt-action-img>img {
        border-radius: 50%!important;
        margin-bottom: 2px;
        height: 41px;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-details .mt-action-author {
        color: #060606;
        font-weight: 600;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-details .mt-action-desc {
        margin-bottom: 0;
        color: #999b9b;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row {
        display: table;
        width: 100%;
    }
    .mt-actions .mt-action {
        margin: 0;
        padding: 15px 0;
        border-bottom: 1px solid #c5c9cc;
    }
    .mt-actions .mt-action .mt-action-img {
        width: 40px;
        float: left;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-details {
        display: table-cell;
        vertical-align: top;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-icon>i {
        display: inline-block;
        position: relative;
        top: 10px;
        font-size: 25px;
        color: #78E0E8;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-icon {
        display: table-cell;
        padding: 6px 20px 6px 6px;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info {
        display: table-cell;
        vertical-align: top;
    }
    .mt-actions .mt-action .mt-action-body {
        padding-left: 15px;
        position: relative;
        overflow: hidden;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime {
        vertical-align: top;
        display: table-cell;
        text-align: center;
        width: 90px;
        white-space: nowrap;
        padding-top: 8px;
        color: #A6A8A8;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime .mt-action-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%!important;
        margin-left: 5px;
        margin-right: 5px;
    }
    .bg-green {
        background: #32c5d2!important;
    }
    .bg-red {
        background: #e7505a!important;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-buttons {
        vertical-align: top;
        display: table-cell;
        text-align: center;
        width: 112px;
        white-space: nowrap;
        padding-top: 10px;
    }
    .btn.btn-outline.green {
        border-color: #32c5d2;
        color: #32c5d2;
        background: 0 0;
    }
    .btn.btn-outline.green.active, .btn.btn-outline.green:active, .btn.btn-outline.green:active:focus, .btn.btn-outline.green:active:hover, .btn.btn-outline.green:focus, .btn.btn-outline.green:hover {
        border-color: #32c5d2;
        color: #FFF;
        background-color: #32c5d2;
    }
    .btn.btn-outline.red {
        border-color: #e7505a;
        color: #e7505a;
        background: 0 0;
    }
    .btn.btn-outline.red.active, .btn.btn-outline.red:active, .btn.btn-outline.red:active:focus, .btn.btn-outline.red:active:hover, .btn.btn-outline.red:focus, .btn.btn-outline.red:hover {
        border-color: #e7505a;
        color: #fff;
        background-color: #e7505a;
    }
    
    .vertical-align-middle {
        vertical-align: middle !important;
        width: 150px;
    }
    .mt-action-details {
        width: 290px;
    }
    .mt-action-icon.vertical-align-middle.mt-action-detail-more {
        width: 180px;
    }
    
    @media only screen and (min-width: 1200px) {
        .mt-action-icon.vertical-align-middle.mt-action-detail-more {
            width: 780px;
        }
    }
</style>

<script type="text/javascript">
    
    var widWindowId_<?php echo $this->uniqId; ?> = '#sales_widget_window_<?php echo $this->uniqId; ?>';
    var objectdatagrid_<?php echo $this->sdataViewId ?> = $('#objectdatagrid-<?php echo $this->sdataViewId; ?>');
    amChartMinify.init();
    
    $('#start-date, #end-date').inputmask('y-m-d');
    $('#start-date, #end-date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true, 
        todayBtn: 'linked', 
        todayHighlight: true 
    });       
    
    Core.initSelect2();    
    
    $(function () {
        $('.dv-explorer-row').on('click', function () {
            $('.dv-explorer-row').removeClass('selected-row');
            $(this).addClass('selected-row');
        });
    });
    
    $(document).on('click', '#date-filter', function() {
        var depsStr = $('.departmentIds', widWindowId_<?php echo $this->uniqId; ?>).select2('val');
        var sd = $('#start-date', widWindowId_<?php echo $this->uniqId; ?>).val() == '' ? '_' : $('#start-date', widWindowId_<?php echo $this->uniqId; ?>).val();
        var ed = $('#end-date', widWindowId_<?php echo $this->uniqId; ?>).val() == '' ? '_' : $('#end-date', widWindowId_<?php echo $this->uniqId; ?>).val();
        var isHierarchy = $('#isHierarchy', widWindowId_<?php echo $this->uniqId; ?>).is(':checked') ? '1' : '';
        
        window.location = URL_APP + 'dashboard/glance/' + sd + '/' + ed + '/' + depsStr + '/' + isHierarchy;
    });
    
    var $openRoleStartDate = $('#start-date');
    var $openRoleEndDate = $('#end-date');
    
    $openRoleStartDate.on('changeDate', function() {
        
        if ($openRoleStartDate.val() != '' && $openRoleEndDate.val() != '') {
            var $thisStartDateVal = new Date($openRoleStartDate.val());
            var $thisEndDateVal = new Date($openRoleEndDate.val());

            if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                $openRoleEndDate.datepicker('update', $openRoleStartDate.val());
            }
        }
    });
    
    $openRoleEndDate.on('changeDate', function() {
        
        if ($openRoleStartDate.val() != '' && $openRoleEndDate.val() != '') {
            var $thisStartDateVal = new Date($openRoleStartDate.val());
            var $thisEndDateVal = new Date($openRoleEndDate.val());

            if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                $openRoleStartDate.datepicker('update', $thisEndDateVal.getFullYear()+'-01-01');
            }
        }
    });    
    
    var gaugeChart = AmCharts.makeChart( 'glance-dashboard-<?php echo $this->uniqId; ?>', {
        "type": "gauge",
	"gaugeX": 200,
	"marginBottom": -10,
	"marginLeft": 0,
	"marginRight": 0,
	"marginTop": 10,
	"fontSize": 14,
        "theme": "light",
        "radius": "45%",
        "axes": [{
            "startAngle": -140,
            "endAngle": 140,
            "axisThickness": 0,
            "axisAlpha": 0,
            "tickAlpha": 0,
            "valueInterval": 100,
            "bottomTextFontSize": 35,
            "bottomTextYOffset": -50,
            "bottomText": "<?php echo $this->positionData1[0]['percentvalue'] ?>%",
            "bands": [{
                "color": "#8bcb30",
                "endValue": <?php echo $this->positionData1[0]['percentchart'] ?>,
                "innerRadius": "115",
                "startValue": -100
            }, {
                "color": "#b3ef84",
                "endValue": 100,
                "innerRadius": "115",
                "startValue": <?php echo $this->positionData1[0]['percentchart'] ?>
            }],
            "startValue": <?php echo $this->positionData1[0]['percentstart'] ?>,
            "endValue": <?php echo $this->positionData1[0]['percentend'] ?>,
        }],
        "arrows": [{
            "color": "#8bcb30",
            "value": <?php echo $this->positionData1[0]['percentchart'] ?>
        }],
        exportConfig: {
            "menu": [ {
                "class": "export-main",
                "format": "PRINT"
            }]
        }
    });
      
    var gaugeChart1 = AmCharts.makeChart( 'glance-dashboard-1-<?php echo $this->uniqId; ?>', {
        "type": "gauge",
	"gaugeX": 200,
	"marginBottom": -10,
	"marginLeft": 0,
	"marginRight": 0,
	"marginTop": 10,
	"fontSize": 14,
        "theme": "light",
        "radius": "45%",
        "axes": [{
            "startAngle": -140,
            "endAngle": 140,
            "axisThickness": 0,
            "axisAlpha": 0,
            "tickAlpha": 0,
            "valueInterval": 100,
            "bottomTextFontSize": 35,
            "bottomTextYOffset": -50,
            "bottomText": "<?php echo $this->positionData1[0]['percentvalue'] ?>%",
            "bands": [{
                "color": "#0577b3",
                "endValue": <?php echo $this->positionData2[0]['percentchart'] ?>,
                "innerRadius": "115",
                "startValue": -100
            }, {
                "color": "#35b3f7",
                "endValue": 100,
                "innerRadius": "115",
                "startValue": <?php echo $this->positionData2[0]['percentchart'] ?>
            }],
            "startValue": <?php echo $this->positionData2[0]['percentstart'] ?>,
            "endValue": <?php echo $this->positionData2[0]['percentend'] ?>,
        }],
        "arrows": [{
            "color": "#0577b3",
            "value": <?php echo $this->positionData2[0]['percentchart'] ?>
        }],
        exportConfig: {
            "menu": [ {
                "class": "export-main",
                "format": "PRINT"
            }]
        }
    });
    
    var serialChart = AmCharts.makeChart( "glance-dashboard-2-<?php echo $this->uniqId; ?>", {
        "type": "serial",
        "theme": "light",
        "depth3D": 0,
        "angle": 30,
        "colors": ["#b3ef84", "#8bcb30"],
        "titles": [
            {
                "id": "TitleId",
                "size": 15,
                "color": '#666666',
                "text": "<?php echo $this->lang->line('execDB0_3') ?>"
            }
	],
        "legend": {
            "horizontalGap": 10,
            "useGraphSettings": true,
            "markerSize": 10,
            enabled: false,
        },
        "dataProvider": <?php echo json_encode($this->positionData3) ?>,
        "valueAxes": [{
            "stackType": "regular",
            "axisAlpha": 0,
            "gridAlpha": 0
        }],
        "graphs": [{
            "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
            "fillAlphas": 0.8,
            "labelText": "[[value]]",
            "lineAlpha": 0.3,
            "title": "Утга 1",
            "type": "column",
            "color": "#000000",
            "valueField": "value1"
        }, {
            "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
            "fillAlphas": 0.8,
            "labelText": "[[value]]",
            "lineAlpha": 0.3,
            "title": "Утга 2",
            "newStack": true,
            "type": "column",
            "color": "#000000",
            "valueField": "value2"
        }],
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left",
            "labelRotation": 45
        },
        "export": {
            "enabled": true
        }

      });
    
    var serialChart2 = AmCharts.makeChart( "glance-dashboard-4-<?php echo $this->uniqId; ?>", {
        "type": "serial",
        "theme": "light",
        "depth3D": 0,
        "angle": 30,
        "colors": ["#35b3f7", "#0577b3"],
        "titles": [
            {
                "id": "TitleId",
                "size": 15,
                "color": '#666666',
                "text": "<?php echo $this->lang->line('execDB0_4') ?>"
            }
	],
        "legend": {
            "horizontalGap": 10,
            "useGraphSettings": true,
            "markerSize": 10,
            enabled: false,
        },
        "dataProvider": <?php echo json_encode($this->positionData4) ?>,
        "valueAxes": [{
            "stackType": "regular",
            "axisAlpha": 0,
            "gridAlpha": 0
        }],
        "graphs": [{
            "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
            "fillAlphas": 0.8,
            "labelText": "[[value]]",
            "lineAlpha": 0.3,
            "title": "Утга 1",
            "type": "column",
            "color": "#000000",
            "valueField": "value1"
        }, {
            "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
            "fillAlphas": 0.8,
            "labelText": "[[value]]",
            "lineAlpha": 0.3,
            "title": "Утга 2",
            "newStack": true,
            "type": "column",
            "color": "#000000",
            "valueField": "value2"
        }],
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left",
            "labelRotation": 45
        },
        "export": {
            "enabled": true
        }
        
      });
      
    function explorerRefresh_<?php echo $this->sdataViewId; ?> () {
        location.reload();
    }
</script>