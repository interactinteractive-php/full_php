<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="cash_widget_window_<?php echo $this->uniqId; ?>" style="width:100%">
<div class="col-md-12">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table">River Kitchen</h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table;" class="mt5">КАССЫН МЭДЭЭ</p>
        <p style="font-size: 15px; margin: 0 auto; display: table;" class="mt5"><?php echo Date::beforeDate('Y.m.d', '-1 day'); ?></p>
    </div>
</div>
<div class="mt20" style="padding-right: .625rem;padding-left: .625rem;overflow: auto;">
    <div class="m-0 no-padding" style="float:left;width: 40%;">
        <p style="font-size: 15px;" class="bold">Касс</p>
    </div>
    <div class="" style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right" class="bold mr10">Эхний үлдэгдэл</p>
    </div>
    <div style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right; padding-right: 15px;" class="bold">Орлого</p>
    </div>
    <div style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right; padding-right: 18px;" class="bold">Зарлага</p>
    </div>
    <div style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right; padding-right: 25px;" class="bold">Эцсийн үлдэгдэл</p>
    </div>
</div>
<div style="padding-right: .625rem;padding-left: .625rem;overflow: auto" class="mt15">
    <div style="padding-right: .625rem;padding-left: .625rem;overflow: auto;background-color: rgb(204,255,102);">
            <?php
            if($this->getAllCashCurrency) {
            foreach($this->getAllCashCurrency as $crow) {
                if(empty($crow['BEGIN_AMOUNT']) && empty($crow['DEBIT_AMOUNT']) && empty($crow['CREDIT_AMOUNT']) && empty($crow['END_AMOUNT']))
                    continue;
                ?>
                <div style="float:left;width: 40%;" class="m-0 no-padding">
                    <div style="padding-top: 3px;">
                        <p class="" style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo $crow['BANK'] ?>
                        </p>
                    </div>
                </div>
                <div style="float:left;width: 15%;">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['BEGIN_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div>
                <div style="float:left;width: 15%;">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['DEBIT_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div> 
                <div style="float:left;width: 15%;">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['CREDIT_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div>   
                <div style="float:left;width: 15%;">
                    <div style="padding-top: 3px;padding-right: 8px;">
                        <p class="bold" style="font-size: 15px; text-align: right">
                            <?php echo Str::formatMoney($crow['END_AMOUNT'], true); ?>
                        </p>
                    </div>
                </div>        
                <div class="clearfix w-100"></div>
                
                <?php if(strtoupper($crow['CURRENCY_CODE']) !== 'MNT') { ?>
                    <div class="" style="padding-right: 0px;float:left;width: 40%;">
                        <div style="padding-top: 3px;">
                        </div>
                    </div>
                    <div style="float:left;width: 15%;">
                        <div style="padding-top: 3px;padding-right: 8px;">
                            <p class="" style="font-size: 15px; text-align: right">
                                (<?php echo Str::formatMoney($crow['BEGIN_AMOUNT_BASE'], true); ?>) <?php echo $crow['CURRENCY_CODE']; ?>
                            </p>
                        </div>
                    </div>
                    <div style="float:left;width: 15%;">
                        <div style="padding-top: 3px;padding-right: 8px;">
                            <p class="" style="font-size: 15px; text-align: right">
                                (<?php echo Str::formatMoney($crow['DEBIT_AMOUNT_BASE'], true); ?>) <?php echo $crow['CURRENCY_CODE']; ?>
                            </p>
                        </div>
                    </div> 
                    <div style="float:left;width: 15%;">
                        <div style="padding-top: 3px;padding-right: 8px;">
                            <p class="" style="font-size: 15px; text-align: right">
                                (<?php echo Str::formatMoney($crow['CREDIT_AMOUNT_BASE'], true); ?>) <?php echo $crow['CURRENCY_CODE']; ?>
                            </p>
                        </div>
                    </div>   
                    <div style="float:left;width: 15%;">
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
<div style="padding-right: .625rem;padding-left: .625rem;overflow: auto;" class="mt20">
    <div style="float:left;width: 40%;" class="m-0 no-padding">
        <p></p>
    </div>
    <div style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right" class="bold mr10"><?php echo Str::formatMoney($this->getCashBeginAmount['BEGIN_AMOUNT'], true); ?></p>
    </div>
    <div style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right; padding-right: 15px;" class="bold"><?php echo Str::formatMoney($this->getCashBeginAmount['DEBIT_AMOUNT'], true); ?></p>
    </div>
    <div style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right; padding-right: 18px;" class="bold"><?php echo Str::formatMoney($this->getCashBeginAmount['CREDIT_AMOUNT'], true); ?></p>
    </div>
    <div style="float:left;width: 15%;">
        <p style="font-size: 15px; text-align: right; padding-right: 25px;" class="bold"><?php echo Str::formatMoney($this->getCashBeginAmount['END_AMOUNT'], true); ?></p>
    </div>
</div>    
<div style="padding-right: .625rem;padding-left: .625rem;overflow: auto;">    
    <div class="mt20" style="background-color:#fff;float:left;width: 50%">
        <p style="font-size: 15px; font-weight: bold;">Кассын орлогын мэдээ</p>
        <div id="cash_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);" class="mt20"></div>	
    </div>
    <div class="mt20" style="background-color:#fff;float:left;width: 50%">
        <p style="font-size: 15px; font-weight: bold;">Кассын зарлагын мэдээ</p>
        <div id="cash_widget2_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);" class="mt20"></div>	
    </div>
</div>     
<div class="col-md-12 col-sm-12 no-padding m-0 mt15 row"> 
    <div class="col-md-12 mt20" style="background-color:#fff">
        <div id="serial1_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>
        <br>
    </div>
</div>
</div>

<?php
    $sumTypeIncome = 0;
    if($this->getDataCashIncome) {
        foreach ($this->getDataCashIncome as $row) {
            $sumTypeIncome += (int) $row['DEBIT_AMOUNT'];
        }
    }
    
    $sumTypeOutcome = 0;
    if($this->getDataCashOutcome) {
        foreach ($this->getDataCashOutcome as $row) {
            $sumTypeOutcome += (int) $row['CREDIT_AMOUNT'];
        }
    }
    
    $arrResult = array();
    $arrResult2 = array();
    if($this->getDataCashByActivity) {
        foreach ($this->getDataCashByActivity as $k => $row) {
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
    }
    #cash_widget_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div, #cash_widget2_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div {
        height: 420px !important;
    }
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#cash_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();
    
    setTimeout(function () {
        if($("#cash_widget_chart_<?php echo $this->uniqId; ?>").height() > $("#cash_widget2_chart_<?php echo $this->uniqId; ?>").height()) {
            $("#cash_widget2_chart_<?php echo $this->uniqId; ?>").height($("#cash_widget_chart_<?php echo $this->uniqId; ?>").height());
        } else {
            $("#cash_widget_chart_<?php echo $this->uniqId; ?>").height($("#cash_widget2_chart_<?php echo $this->uniqId; ?>").height());
        }
    }, 100);    
    
    AmCharts.makeChart("cash_widget_chart_<?php echo $this->uniqId; ?>", {
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
        "dataProvider": <?php echo json_encode($this->getDataCashIncome); ?>,
        "valueField": "DEBIT_AMOUNT",
        "titleField": "SUB_CATEGORY_NAME",         
        "export": {
            "enabled": false
        }
    });    
    
    AmCharts.makeChart("cash_widget2_chart_<?php echo $this->uniqId; ?>", {
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
        "dataProvider": <?php echo json_encode($this->getDataCashOutcome); ?>,
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