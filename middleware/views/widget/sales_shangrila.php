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

<div class="w-100" id="sales_widget_window_<?php echo $this->uniqId; ?>">
    <div class="head">
        <br>
        <div>
            <h2 style="margin: 0 auto; display: table">Шангри - Ла</h2>
            <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table;" class="mt5">БОРЛУУЛАЛТЫН МЭДЭЭ</p>
            <p style="font-size: 15px; margin: 0 auto; display: table;" class="mt5"><?php echo Date::beforeDate('Y.m.d', '-1 day'); ?></p>
        </div>
    </div>

    <div class=" mt-2">
        <div class="col-md-3 col-sm-3" style="padding: 6px;">
            <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt-2" style="font-size: 15px;">Нийт борлуулалт</p>
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
        <div class="col-md-3 col-sm-3" style="padding: 6px;">
            <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt-2" style="font-size: 15px;">Төлөвлөгөө</p>
                <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($numCount, true); ?></p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3" style="padding: 6px;">
            <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt-2" style="font-size: 15px;">Гүйцэтгэл</p>
                <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo $varpercent < 95 ? '<span style="color: rgb(177, 52, 49)">' . Number::amount($varpercent) . '%</span>' : Number::amount($varpercent) . '%'; ?></p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3" style="padding: 6px;">
            <div style="background-color: rgb(255,191,210); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt-2" style="font-size: 15px;">Зөрүү</p>
                <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo $var < 0 ? '+' . Str::formatMoney($var * (-1)) : '<span style="color: rgb(177, 52, 49)">-' . Str::formatMoney($var) . '</span>'; ?></p>
            </div>
        </div>     
    </div>

    <div class=" mt-2 mb-4">
        <div class="col-md-6 col-sm-6 mt-3 no-padding" style="background-color:#fff;">
            <p style="font-size: 15px; font-weight: bold">Төлбөрийн хэлбэрээр</p>
            <div id="sales2_widget_chart_<?php echo $this->uniqId; ?>" style="border: 2px solid rgb(155,187,89);"></div>	
        </div>    
        
        <div class="col-md-6 col-sm-6 mt-3 mb-3" style="background-color:#fff;">   
            <p style="font-size: 15px; font-weight: bold;">Топ 5-н борлуулалт </p>
            <div id="sales_widget_chart_member_<?php echo $this->uniqId; ?>" style="min-height:420px; border: 2px solid rgb(155,187,89); padding:20px; ">
                  
                <table class="table table-hover"  style="margin-top: 35px;" id="top5sale">
                    <tbody style="font-size: 15px !important;  color: #000">
                        <?php
                        if($this->getTopFiveItem) {
                            $sumVar = 0;
                            foreach ($this->getTopFiveItem as $key => $row) {
                                echo '<tr>';
                                echo '<td style="vertical-align: middle; width: 40%">' . $row['ITEM_NAME'] . '</td>';
                                echo '<td style="vertical-align: middle" class="text-right"><strong>' . Str::formatMoney($row['LINE_TOTAL_AMOUNT']) . '</strong></td>';
                                echo '<td style="vertical-align: middle" class="text-right"><strong>' . $row['INVOICE_QTY'] . '</strong></td>';
                                echo '<td style="padding-left: 40px; width: 1%" data-chart-json=\'' . htmlentities(json_encode($row['itemDtl']), ENT_QUOTES, 'UTF-8') . '\'><div id="inline_widget_chart_' . $key . '" style="vertical-align: middle;display: inline-block; width: 100%; height: 40px;"></div></td>';
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

        <div class="w-100 mt-3" style="background-color:#fff; padding-right: 0px">
            <p style="font-size: 15px; font-weight: bold; margin-left: 10px; mt-2">Бүтээгдэхүүний төрлөөр</p>
            <div id="sales_widget_chart_<?php echo $this->uniqId; ?>" style="border: 2px solid rgb(155,187,89); height:420px; margin-left: 10px;margin-right: 10px;"></div> 
        </div>
        <!-- <div class="col-md-6 col-sm-6 mt-3" style="background-color:#fff;">
            <div id="sales_widget_chart_member_<?php echo $this->uniqId; ?>" style="border: 2px solid rgb(155,187,89); padding:20px; ">
            <p style="font-size: 15px; font-weight: bold;">Гишүүний бүртгэл</p>

            <?php 
            
                if(!empty($this->getDataActiveMember))
                    $item1 = $this->getDataActiveMember[0]['NAME'];
                    $item2 = $this->getDataActiveMember[0]['CNT'];
                    $item3 = $this->getDataActiveMember[1]['NAME'];
                    $item4 = $this->getDataActiveMember[1]['CNT'];
                    $item5 = $this->getDataActiveMember[2]['NAME'];
                    $item6 = $this->getDataActiveMember[2]['CNT'];
                    $item7 = $this->getDataActiveMember[3]['NAME'];
                    $item8 = $this->getDataActiveMember[3]['CNT'];
                    // $item9 = $this->getDataActiveMember[4]['NAME'];
                    // $item10 = $this->getDataActiveMember[4]['CNT'];
                
            ?>  
            <div id="wrapperdd"><span class="label"><?php echo $item1; ?> <p><?php echo $item2; ?></p></span>
                <div class="branch lv1">
                    <div class="entry"><span class="label color1"><b><?php echo $item4; ?></b></span><p><?php echo $item3; ?></p></div>
                    <div class="entry"><span class="label color2"><b><?php echo $item6; ?></b></span><p><?php echo $item5; ?></p></div>
                    <div class="entry"><span class="label color3"><b><?php echo $item8; ?></b></span><p><?php echo $item7; ?></p></div>
                </div>
                </div>
            </div> 
           
        </div> -->
        <!-- <div class="col-md-6 col-sm-6 mt-3">
            <div  style="background-color:#fff; display:flex; border: 2px solid rgb(155,187,89);">
                <div id="chartdiv"></div>
            </div>
        </div> -->
    </div>
</div>



<style>
    .col-md-3, .col-md-6{
        float: left;
    }
    .row{
        margin: 0 !important;
        display: flex; 
    }

    #wrapperdd {
        position: relative;
        min-height: 350px;
        margin-left: 5px;
    }

    .branch {
        position: relative;
        margin-left: 310px;
        top: -5px;
    }
    .branch:before {
        content: "";
        width: 75px;
        border-top: 1px solid #7d849a;
        position: absolute;
        left: -100px;
        top: 50%;
        margin-top: 1px;
    }
    .branch.lv1 .entry p{
        margin-left: 152px;
        padding-top: 40px;
        font-size: 15px;
    }
    .branch.lv1 .label.color1{
        border-color:red;
    }
    .branch.lv1 .label.color2{
        border-color:#67b7dc;
    }
    .branch.lv1 .label.color3{
        border-color:#fdd400;
    }
    .branch.lv1 .label{
        width: 100px;
        height: 100px;
        left: 30px;
        top: 0;
        border-width: 5px;
    }

    .entry {
        position: relative;
        min-height: 100px;
        margin-bottom: 15px;
    }
    .entry:before {
        content: "";
        height: 100%;
        border-left: 1px solid #7d849a;
        position: absolute;
        left: -50px;
    }
    .entry:after {
        content: "";
        width: 80px;
        border-top: 1px solid #7d849a;
        position: absolute;
        left: -50px;
        top: 50%;
        margin-top: 1px;
    }
    .entry:first-child:before {
        width: 10px;
        height: 70%;
        top: 50%;
        margin-top: 2px;
        border-radius: 10px 0 0 0;
    }
    .entry:first-child:after {
        height: 10px;
        border-radius: 10px 0 0 0;
    }
    .entry:last-child:before {
        width: 10px;
        border-radius: 0 0 0 10px;
        height: 65%;
        top: -15px;
    }
    .entry:last-child:after {
        height: 10px;
        border-top: none;
        border-bottom: 1px solid #7d849a;
        border-radius: 0 0 0 10px;
        margin-top: -9px;
    }
    .entry.sole:before {
        display: none;
    }
    .entry.sole:after {
        width: 50px;
        height: 0;
        margin-top: 1px;
        border-radius: 0;
    }

    .label p{
        font-weight: 600;
    }
    .label {
        display: block;
        width: 150px;
        height: 150px;
        line-height: 22px;
        text-align: center;
        border: 10px solid #2f4074;
        border-radius: 50%;
        color:#000;
        white-space: inherit;
        position: absolute;
        left: 60px;
        top: 24%;
        font-size: 15px;
        padding-top: 35px;
    }

    #serial1_dashboard_<?php echo $this->uniqId; ?>, #sales2_widget_chart_<?php echo $this->uniqId; ?> {
        height: 420px;
    }
    #sales_widget_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div, #sales_widget_chart2_<?php echo $this->uniqId; ?> .amcharts-chart-div {
        
    }
    .sales-card-title {
        font-size: 15px;
    }    

    #chartdiv {
        width: 70%;
        height: 350px;
        float:left;
        overflow: inherit;
    }
    .amcharts-chart-div{
        overflow: inherit !important;
    }
    #chartdiv svg{
        left: -70px !important;
        width: 141% !important;
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
        // "legend": {
        //     "position": "bottom",
        //     "autoMargins": false,
        //     "maxColumns": 1,
        //     "valueWidth": 120,
        //     "align": "center",
        //     "labelText": "[[title]]",
        //     "valueText": "[[value]]",
        //     "markerType": "circle"
        // },
        "legend": {
            "position": "right",
            "marginRight": 100,
            "valueWidth": 120,
            "autoMargins": false,
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
            "position": "bottom",
            "autoMargins": false,
            "maxColumns": 1,
            "valueWidth": 120,
            "align": "center",
            "labelText": "[[title]]",
            "valueText": "[[value]]",
            "markerType": "circle"
        },      
        // "legend": {
        //     "position": "right",
        //     "marginRight": 100,
        //     "valueWidth": 120,
        //     "autoMargins": false,
        //     "markerType": "circle"
        // },
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
    
    var gaugeChart = AmCharts.makeChart("chartdiv", {
        "type": "gauge",
        "theme": "none",
        "labelText": "label",
        "titles": [
            {
            "text": "Борлуулалт",
            "size": 15
            }
        ],
        "axes": [{
            "axisAlpha": 0,
            "tickAlpha": 0,
            "labelsEnabled": false,
            "bottomText":"",
            "startValue": 0,
            "endValue": 100,
            "startAngle": 0,
            "endAngle": 270,
            "bands": [{
                "color": "#eee",
                "startValue": 12,
                "endValue": 100,
                "radius": "100%", 
                "innerRadius": "85%"
            }, {
                "color": "#84b761",
                "startValue": 0,
                "endValue": 92,
                "radius": "100%",
                "innerRadius": "85%",
                "balloonText": "92%"
            },
             {
                "color": "#eee",
                "startValue": 0,
                "endValue": 100,
                "radius": "80%",
                "innerRadius": "65%"
            }, {
                "color": "#fdd400",
                "startValue": 0,
                "endValue": 35,
                "radius": "80%",
                "innerRadius": "65%",
                "balloonText": "35%"
            }, {
                "color": "#eee",
                "startValue": 0,
                "endValue": 100,
                "radius": "60%",
                "innerRadius": "45%"
            }, {
                "color": "#cc4748",
                "startValue": 0,
                "endValue": 92,
                "radius": "60%",
                "innerRadius": "45%",
                "balloonText": "92%"
            }, {
                "color": "#eee",
                "startValue": 0,
                "endValue": 100,
                "radius": "40%",
                "innerRadius": "25%"
            }, {
                "color": "#67b7dc",
                "startValue": 0,
                "endValue": 68,
                "radius": "40%",
                "innerRadius": "25%",
                "balloonText": "68%"
            }]
        }],
        "allLabels": [{
            "text": "______Спорт хувцас",
            "x": "73%",
            "y": "25%",
            "class": "color",
            "size": 12,
            "bold": true,
            "color": "#84b761",
            "align": "left"
        }, {
            "text": "____Уураг,нэмэлт хэрэгсэл",
            "x": "72%",
            "y": "35%",
            "size": 12,
            "bold": true,
            "color": "#fdd400",
            "align": "left"
        }, {
            "text": "__________Жүүс бар",
            "x": "65%",
            "y": "45%",
            "size": 12,
            "bold": true,
            "color": "#cc4748",
            "align": "left"
        }, {
            "text": "____________Үйлчилгээний зөвлөх",
            "x": "58%",
            "y": "55%",
            "size": 12,
            "bold": true,
            "color": "#67b7dc",
            "align": "left"
        }],
        
    });
</script>