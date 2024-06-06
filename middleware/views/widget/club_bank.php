<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="bank_widget_window_<?php echo $this->uniqId; ?>" class="w-100 container">
<div class="w-100">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table">River Club</h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table;" class="mt5">ХАРИЛЦАХЫН МЭДЭЭ</p>
        <p style="font-size: 15px; margin: 0 auto; display: table;" class="mt5"><?php echo Date::beforeDate('Y.m.d', '-1 day'); ?></p>
    </div>
</div>
<div class="mt-3 w-100">
    <div class="row w-100">
        <div class="col-md-4 col-sm-4 m-0 no-padding">
            <p style="font-size: 15px;" class="bold">Харилцах</p>
        </div>
        <div class="col-md-2 col-sm-2">
            <p style="font-size: 15px; text-align: right" class="bold mr10">Эхний үлдэгдэл</p>
        </div>
        <div class="col-md-2 col-sm-2">
            <p style="font-size: 15px; text-align: right; padding-right: 15px;" class="bold">Орлого</p>
        </div>
        <div class="col-md-2 col-sm-2">
            <p style="font-size: 15px; text-align: right; padding-right: 18px;" class="bold">Зарлага</p>
        </div>
        <div class="col-md-2 col-sm-2">
            <p style="font-size: 15px; text-align: right; padding-right: 25px;" class="bold">Эцсийн үлдэгдэл</p>
        </div>
    </div>
</div>
<div class="row w-100 mt15">
    <div class="w-100" style="background-color: rgb(204,255,102);">
            <?php
            if($this->getAllBankCurrency) {
            foreach($this->getAllBankCurrency as $crow) {
                if(empty($crow['BEGIN_AMOUNT']) && empty($crow['DEBIT_AMOUNT']) && empty($crow['CREDIT_AMOUNT']) && empty($crow['END_AMOUNT']))
                    continue;                
                ?>
                <div class="col-md-4 col-sm-4" style="padding-right: 0px;">
                    <div style="padding-top: 3px;">
                        <p class="" style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo $crow['BANK'] ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['BEGIN_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['DEBIT_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div> 
                <div class="col-md-2 col-sm-2">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['CREDIT_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div>   
                <div class="col-md-2 col-sm-2">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['END_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div>        
                <div class="clearfix w-100"></div>
                
                <?php if(strtoupper($crow['CURRENCY_CODE']) !== 'MNT') { ?>
                        <div class="col-md-4 col-sm-4" style="padding-right: 0px;">
                            <div style="padding-top: 3px;">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div style="padding-top: 3px;padding-right: 8px;">
                                <p class="" style="font-size: 15px; text-align: right">
                                    (<?php echo Str::formatMoney($crow['BEGIN_AMOUNT_BASE'], true); ?>) <?php echo $crow['CURRENCY_CODE']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div style="padding-top: 3px;padding-right: 8px;">
                                <p class="" style="font-size: 15px; text-align: right">
                                    (<?php echo Str::formatMoney($crow['DEBIT_AMOUNT_BASE'], true); ?>) <?php echo $crow['CURRENCY_CODE']; ?>
                                </p>
                            </div>
                        </div> 
                        <div class="col-md-2 col-sm-2">
                            <div style="padding-top: 3px;padding-right: 8px;">
                                <p class="" style="font-size: 15px; text-align: right">
                                    (<?php echo Str::formatMoney($crow['CREDIT_AMOUNT_BASE'], true); ?>) <?php echo $crow['CURRENCY_CODE']; ?>
                                </p>
                            </div>
                        </div>   
                        <div class="col-md-2 col-sm-2">
                            <div style="padding-top: 3px;padding-right: 8px;">
                                <p class="" style="font-size: 15px; text-align: right">
                                    (<?php echo Str::formatMoney($crow['END_AMOUNT_BASE'], true); ?>) <?php echo $crow['CURRENCY_CODE']; ?>
                                </p>
                            </div>
                        </div>   
                <?php } ?>
        <?php }} ?>
    </div> 
</div>
<div class=" mt20">
    <div class="col-md-4 col-sm-4 m-0 no-padding">
    </div>
    <div class="col-md-2 col-sm-2">
        <p style="font-size: 15px; text-align: right" class="bold mr10"><?php echo Str::formatMoney($this->getBankBeginAmount['BEGIN_AMOUNT'], true); ?></p>
    </div>
    <div class="col-md-2 col-sm-2">
        <p style="font-size: 15px; text-align: right; padding-right: 15px;" class="bold"><?php echo Str::formatMoney($this->getBankBeginAmount['DEBIT_AMOUNT'], true); ?></p>
    </div>
    <div class="col-md-2 col-sm-2">
        <p style="font-size: 15px; text-align: right; padding-right: 18px;" class="bold"><?php echo Str::formatMoney($this->getBankBeginAmount['CREDIT_AMOUNT'], true); ?></p>
    </div>
    <div class="col-md-2 col-sm-2">
        <p style="font-size: 15px; text-align: right; padding-right: 25px;" class="bold"><?php echo Str::formatMoney($this->getBankBeginAmount['END_AMOUNT'], true); ?></p>
    </div>
</div>    
<div class="row no-padding m-0 w-100" style="">    
    <div class="col-md-6 col-sm-6 mt20 chart1-div" style="background-color:#fff;">
        <p style="font-size: 15px; font-weight: bold;">Харилцахын орлогын мэдээ</p>
        <div id="bank_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89); min-height:420px;" class="mt20"></div>	
    </div>
    <div class="col-md-6 col-sm-6 mt20 chart2-div" style="background-color:#fff;">
        <p style="font-size: 15px; font-weight: bold;">Харилцахын зарлагын мэдээ</p>
        <div id="bank_widget2_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);" class="mt20"></div>	
    </div>
</div>

<div class="w-100" style="background-color:#fff">
    <div id="serial1_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>
    <br>
</div>

</div>

<?php
    $sumTypeIncome = 0;
    if($this->getDataBankIncome) {
        foreach ($this->getDataBankIncome as $row) {
            $sumTypeIncome += (int) $row['DEBIT_AMOUNT'];
        }
    }
    
    $sumTypeOutcome = 0;
    if($this->getDataBankOutcome) {
        foreach ($this->getDataBankOutcome as $row) {
            $sumTypeOutcome += (int) $row['CREDIT_AMOUNT'];
        }
    }
    
    $arrResult = array();
    $arrResult2 = array();
    if($this->getDataBankByActivity) {
        foreach ($this->getDataBankByActivity as $k => $row) {
            $arrResult[$k]['name'] = Date::format('d', $row['BOOK_DATE']);
            $arrResult[$k]['y'] = (int) $row['DEBIT_AMOUNT'];

            $arrResult2[$k]['name'] = Date::format('d', $row['BOOK_DATE']);
            $arrResult2[$k]['y'] = (int) $row['CREDIT_AMOUNT'];
        }
    }    
?>

<style>
    
    #serial1_dashboard_<?php echo $this->uniqId; ?> {
        height: 420px;
        display: inline-block;
        margin-top: 30px;
        width: 100%;
    }
    #bank_widget_window_<?php echo $this->uniqId; ?>{
        overflow: hidden;
    }
    #bank_widget_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div, #bank_widget2_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div {
        height: 420px !important;
    }
    .col-md-4,.col-md-2,.col-md-3, .col-md-6{
        float: left;
        display: block;
    }

  
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#bank_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();
    
    setTimeout(function () {
        if($("#bank_widget_chart_<?php echo $this->uniqId; ?>").height() > $("#bank_widget2_chart_<?php echo $this->uniqId; ?>").height()) {
            $("#bank_widget2_chart_<?php echo $this->uniqId; ?>").height($("#bank_widget_chart_<?php echo $this->uniqId; ?>").height());
        } else {
            $("#bank_widget_chart_<?php echo $this->uniqId; ?>").height($("#bank_widget2_chart_<?php echo $this->uniqId; ?>").height());
        }
    }, 100);      
    
    AmCharts.makeChart("bank_widget_chart_<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 15,
        "autoMargins": false,
        "labelText": "[[percents]]%",
        "allLabels": [{
          "y": "45%",
          "align": "center",
          "size": 18,
          "bold": true,
          "text": '<?php echo Str::formatMoney($sumTypeIncome); ?>',
          "color": "#555"
        }, {
          "y": "54%",
          "align": "center",
          "size": 12,
          "text": "",
          "color": "#555"
        }],       
        "legend": {
            "position": "bottom",
            "autoMargins": false,
            "maxColumns": 1,
            "valueWidth": 120,
            "align": "center",
            "labelText": "[[title]]",
            "valueText": "[[value]]",
            "markerType": "circle"
        },
        "radius": "33%",
        "innerRadius": "75%",
        "dataProvider": <?php echo json_encode($this->getDataBankIncome); ?>,
        "valueField": "DEBIT_AMOUNT",
        "titleField": "SUB_CATEGORY_NAME",         
        "export": {
            "enabled": false
        }
    });    
    
    AmCharts.makeChart("bank_widget2_chart_<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 15,
        "autoMargins": false,
        "labelText": "[[percents]]%",
        "allLabels": [{
          "y": "45%",
          "align": "center",
          "size": 18,
          "bold": true,
          "text": '<?php echo Str::formatMoney($sumTypeOutcome); ?>',
          "color": "#555"
        }, {
          "y": "54%",
          "align": "center",
          "size": 12,
          "text": "",
          "color": "#555"
        }],       
        "legend": {
            "position": "bottom",
            "autoMargins": false,
            "maxColumns": 1,
            "valueWidth": 120,
            "align": "center",
            "labelText": "[[title]]",
            "valueText": "[[value]]",
            "markerType": "circle"
        },
        "radius": "33%",
        "innerRadius": "75%",
        "dataProvider": <?php echo json_encode($this->getDataBankOutcome); ?>,
        "valueField": "CREDIT_AMOUNT",
        "titleField": "SUB_CATEGORY_NAME",         
        "export": {
            "enabled": false
        }
    });    
    
    var d = new Date();
    d.setDate(d.getDate()-1);
    var n = d.getMonth();
    $('#serial1_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        "title": {
          "text": n + 1 + ' сар'
        },        
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                enabled: false,
                text: 'Custom with <b>simple</b> <i>markup</i>',
                style: {
                    fontWeight: 'normal'
                }
            },
            type: 'logarithmic'                 
        },
        legend: {
            enabled: true
        },            
        plotOptions: {
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [{
            color: "#ff9900",
            name: "Орлого",
            data: <?php echo json_encode($arrResult); ?>
        },{
            color: "#00b3b3",
            name: "Зарлага",
            data: <?php echo json_encode($arrResult2); ?>
        }]
    });
</script>