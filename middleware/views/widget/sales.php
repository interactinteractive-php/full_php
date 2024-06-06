<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 

$sumPayment = 0; $sumPaymentPayment = 0;
if($this->getDataSalesList) {
    foreach ($this->getDataSalesList as $row) {
        if($row['ISCALC'] == '1')
            $sumPaymentPayment += (int) $row['TOTAL_AMOUNT'];
        $sumPayment += (int) $row['TOTAL_AMOUNT'];
    }
}

?> 

<div id="sales_widget_window_<?php echo $this->uniqId; ?>" style="width:100%">
<div class="col-md-12">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table">Ривер Ресторан</h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table;" class="mt5">БОРЛУУЛАЛТЫН МЭДЭЭ</p>
        <p style="font-size: 15px; margin: 0 auto; display: table;" class="mt5"><?php echo Date::beforeDate('Y.m.d', '-1 day'); ?></p>
    </div>
</div>
<div style="padding-right: .625rem;padding-left: .625rem;overflow: auto;" class="mt20">
    <div style="padding: 4px;float:left;width: 25%;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Нийт борлуулалт</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($sumPayment); ?></p>
        </div>
    </div>
    <?php
        $numCount = 0;
        if(!empty($this->numberPlan))
            $numCount = $this->numberPlan[0]['total'];

        $var = $numCount - $sumPayment;
        $varpercent = $numCount == 0 ? 0 : $sumPayment * 100 / $numCount;
    ?>        
    <div style="padding: 4px;float:left;width: 25%;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Төлөвлөгөө</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($numCount, true); ?></p>
        </div>
    </div>
    <div style="padding: 4px;float:left;width: 25%;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Гүйцэтгэл</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo $varpercent < 95 ? '<span style="color: rgb(177, 52, 49)">' . Number::amount($varpercent) . '%</span>' : Number::amount($varpercent) . '%'; ?></p>
        </div>
    </div>
    <div style="padding: 4px;float:left;width: 25%;">
        <div style="background-color: rgb(255,191,210); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Зөрүү</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo $var < 0 ? '+' . Str::formatMoney($var * (-1)) : '<span style="color: rgb(177, 52, 49)">-' . Str::formatMoney($var) . '</span>'; ?></p>
        </div>
    </div>     
</div>
<div style="padding-right: .625rem;padding-left: .625rem;overflow: auto;">
    <div class="mt20 no-padding" style="background-color:#fff;float:left;width: 60%;">
        <p style="font-size: 15px; font-weight: bold">Төлбөрийн хэлбэрээр</p>
        <div id="sales2_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);" class="mr20"></div>	
        
        <div class="mr20" style="background-color:#fff; margin-top: 20px;">   
            <p style="font-size: 15px; font-weight: bold">Топ 5 борлуулалт</p>
            <table class="table table-hover" id="top5sale" style="border: 1px solid rgb(155,187,89);">
                <thead>
                    <tr>
                        <th style="font-size: 15px !important;  color: #000">
                            Бүтээгдэхүүний нэр
                        </th>
                        <th class="text-right" style="font-size: 15px !important;  color: #000">
                            Дүн
                        </th>
                        <th class="text-right" style="font-size: 15px !important;  color: #000; width: 60px;">
                            Тоо
                        </th>
                        <th class="" style="font-size: 15px !important;  color: #000; padding-left: 40px;">
                            Сүүлийн сард
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    if($this->getTopFiveItem) {
                        $sumVar = 0;
                        foreach ($this->getTopFiveItem as $key => $row) {
                            echo '<tr>';
                            echo '<td style="vertical-align: middle; width: 35%">' . $row['ITEM_NAME'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-right"><strong>' . Str::formatMoney($row['LINE_TOTAL_AMOUNT']) . '</strong></td>';
                            echo '<td style="vertical-align: middle" class="text-right"><strong>' . $row['INVOICE_QTY'] . '</strong></td>';
                            echo '<td style="padding-left: 40px; width: 40%" data-chart-json=\'' . htmlentities(json_encode($row['itemDtl']), ENT_QUOTES, 'UTF-8') . '\'><div id="inline_widget_chart_' . $key . '" style="vertical-align: middle;display: inline-block; width: 100%; height: 40px;"></div></td>';
                            echo '</tr>';
                        }
                    }

                    $sumType = 0;
                    if($this->getDataSalesByType) {
                        foreach ($this->getDataSalesByType as $row) {
                            $sumType += (int) $row['TOTAL_AMOUNT'];
                        }
                    }
                    
                    $sumType2 = 0;
                    if($this->getDataSalesByChannelType) {
                        foreach ($this->getDataSalesByChannelType as $row) {
                            $sumType2 += (int) $row['TOTAL_AMOUNT'];
                        }
                    }                    
                    
                    $arrResult = array();
                    $arrResult2 = array();
                    if($this->getDataSalesByActivity) {
                        foreach ($this->getDataSalesByActivity as $k => $row) {
                            $arrResult[$k]['name'] = Date::format('d', $row['INVOICE_DATE']);
                            $arrResult[$k]['y'] = (int) $row['TOTAL_AMOUNT'];
                            
                            $arrResult2[$k]['name'] = Date::format('d', $row['INVOICE_DATE']);
                            $arrResult2[$k]['y'] = $numCount;
                        }
                    }
                    ?>                
                </tbody>
            </table>           
        </div>        
    </div>    
    <div class="mt20" style="background-color:#fff; padding-right: 0px;float:left;width: 40%;">
        <p style="font-size: 15px; font-weight: bold;">Бүтээгдэхүүний төрлөөр</p>
        <div id="sales_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>	
        
        <div class="mt20">
            <p style="font-size: 15px; font-weight: bold;">Борлуулалтын сувгаар</p>
            <div id="sales_widget_chart2_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>        
        </div>
    </div>
</div>
<div class="col-md-12 col-sm-12 no-padding m-0 row"> 
    <div class="col-md-12 mt5" style="background-color:#fff">
        <p style="font-size: 15px; font-weight: bold">Борлуулалтын явц</p>
        <div id="serial1_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>
        <br>
    </div>
</div>
</div>

<style>
    #serial1_dashboard_<?php echo $this->uniqId; ?>, #sales2_widget_chart_<?php echo $this->uniqId; ?> {
        height: 420px;
    }
    #sales_widget_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div, #sales_widget_chart2_<?php echo $this->uniqId; ?> .amcharts-chart-div {
        height: 420px !important;
    }
    .sales-card-title {
        font-size: 15px;
    }    
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#sales_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();

    if($('#top5sale', widWindowId_<?php echo $this->uniqId; ?>).length) {
        $('#top5sale > tbody > tr', widWindowId_<?php echo $this->uniqId; ?>).each(function(){
            var chartContainer = $(this).find('td:last');
            
            if(chartContainer.attr('data-chart-json') != '')
                inlineWidgetRender_<?php echo $this->uniqId; ?>(chartContainer.children().attr('id'), chartContainer.attr('data-chart-json'));
        });
    }
    
    function inlineWidgetRender_<?php echo $this->uniqId; ?>($id, $dataJson) {
        AmCharts.makeChart($id, {
          "type": "serial",
          "dataProvider": JSON.parse($dataJson),
          "categoryField": "INVOICE_DATE",
          "autoMargins": false,
          "marginLeft": 0,
          "marginRight": 0,
          "marginTop": 0,
          "marginBottom": 0,
          "graphs": [{
            "valueField": "LINE_TOTAL_AMOUNT",
            "type": "column",
            "fillAlphas": 1,
            "showBalloon": true,
            "lineColor": "#ffbf63",
            "balloonText": "[[category]]: <b>[[value]]</b>"
          }],
          "valueAxes": [{
            "gridAlpha": 0,
            "axisAlpha": 0
          }],
          "categoryAxis": {
            "gridAlpha": 0,
            "axisAlpha": 0
          }
        } );
    }
    
    AmCharts.makeChart("sales_widget_chart_<?php echo $this->uniqId; ?>", {
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
          "text": '<?php echo Str::formatMoney($sumType); ?>',
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
        "dataProvider": <?php echo json_encode($this->getDataSalesByType); ?>,
        "valueField": "TOTAL_AMOUNT",
        "titleField": "ITEM_CATEGORY_NAME",         
        "export": {
            "enabled": false
        }
    });    
    
    AmCharts.makeChart("sales2_widget_chart_<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 15,
        "labelText": "[[percents]]%",
        "allLabels": [{
          "y": "45%",
          "align": "center",
          "size": 18,
          "bold": true,
          "text": '<?php echo Str::formatMoney($sumPaymentPayment); ?>',
          "color": "#555"
        }, {
          "y": "54%",
          "align": "center",
          "size": 12,
          "text": "",
          "color": "#555"
        }],              
        "legend": {
            "position": "right",
            "marginRight": 100,
            "valueWidth": 120,
            "autoMargins": false,
            "markerType": "circle"
        },
        "radius": "33%",
        "innerRadius": "75%",
        "dataProvider": <?php echo json_encode($this->getDataSalesList); ?>,
        "valueField": "TOTAL_AMOUNT",
        "titleField": "NAME",         
        "export": {
            "enabled": false
        }
    });   
    
    AmCharts.makeChart("sales_widget_chart2_<?php echo $this->uniqId; ?>", {
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
          "text": '<?php echo Str::formatMoney($sumType2); ?>',
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
        "dataProvider": <?php echo json_encode($this->getDataSalesByChannelType); ?>,
        "valueField": "TOTAL_AMOUNT",
        "titleField": "CHANNEL_NAME",         
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
            }
        },
        legend: {
            enabled: false
        },            
        plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }           
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [{
            color: "#00b3b3",
            data: <?php echo json_encode($arrResult2); ?>
        },{
            color: "#ff9900",
            data: <?php echo json_encode($arrResult); ?>
        }]
    });
</script>