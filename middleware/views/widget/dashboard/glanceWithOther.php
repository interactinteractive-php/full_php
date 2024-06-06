<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 

$sumPayment = 0; $sumPaymentPayment = 0;

?> 

<div id="sales_widget_window_<?php echo $this->uniqId; ?>">
<div class="row">
    <div class="col-md-12 no-padding m-0" style="background-color: #2d9ada;background-image: url(../../../middleware/assets/theme/metro/img/blue-bg.jpg);background-repeat: no-repeat;background-size: cover;">
        <div class="col-md-6">
            <div class="main-title-<?php echo $this->uniqId ?>">
                <h2 class="h2_style" >
                    <!-- <span><i class="fa fa-users"></i></span>  -->
                    <?php echo $this->lang->line('execDB5_0'); ?>
                </h2>
            </div>
        </div>
        <div class="col-md-6 dc-data-<?php echo $this->uniqId ?>">
            <div class="col-md-12">
                <div class="col-md-4">
                    <label class="header-title-dd">Эхлэх </label>  
                    <label class="header-value-dd"><input type="text" class="" value="<?php echo $this->startDate; ?>" style="width: 100px" id="start-date"></label>
                </div>
                <div class="col-md-8">
                <label class="header-title-dd">Салбар нэгж </label>  
                    <label class="header-value-dd">
                    <select class="departmentIds form-control select2" placeholder="- Сонгох -">
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
                    <label class="header-title-dd">Дуусах </label>  
                    <label class="header-value-dd"><input type="text" value="<?php echo $this->endDate; ?>" style="width: 100px" id="end-date"></label>                
                </div>
                <div class="col-md-8">
                    <label class="header-title-dd">Харъяа нэгж багтах </label>  
                    <label class="header-value-dd"><input type="checkbox" id="isHierarchy" class="" <?php echo $this->isHierarchy == '1' ? 'checked' : ''; ?> value="1"> 
                    <button id="date-filter" class="float-right" style="color: #333;">Хайх</button></label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 col-sm-12 mt20">
    <div class="col-md-8 col-sm-8" style="padding: 4px;">
    <?php if($this->getCartData) { ?>
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: <?php echo $this->getCartData['color1']; ?>; height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px; color: #fff; border-radius: 6px">
                <i class="fa <?php echo $this->getCartData['icon1']; ?>" style="font-size: 4vw;position: absolute; color: <?php echo $this->getCartData['iconcolor1']; ?>; margin-left: 8%; margin-top: 40px"></i>            
                <p style="font-size: 2vw;font-weight: bold;margin-top: 20px; margin-left: 60%"><?php echo $this->getCartData['value1']; ?></p>
                <p style="margin-top: 20px; margin-left: 60%" style="font-size: 15px;"><?php echo $this->getCartData['name1']; ?></p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: <?php echo $this->getCartData['color2']; ?>; height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px; color: #fff; border-radius: 6px">
                <i class="fa <?php echo $this->getCartData['icon2']; ?>" style="font-size: 4vw;position: absolute; color: <?php echo $this->getCartData['iconcolor2']; ?>; margin-left: 8%; margin-top: 40px"></i>            
                <p style="font-size: 2vw;font-weight: bold;margin-top: 20px; margin-left: 60%"><?php echo $this->getCartData['value2']; ?></p>
                <p style="margin-top: 20px; margin-left: 60%" style="font-size: 15px;"><?php echo $this->getCartData['name2']; ?></p>
            </div>
        </div>
        <?php
            $var = 1415320 - $sumPayment;
            $varpercent = $sumPayment * 100 / 1415320;
        ?>    
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: <?php echo $this->getCartData['color3']; ?>; height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px; color: #fff; border-radius: 6px">
                <i class="fa <?php echo $this->getCartData['icon3']; ?>" style="font-size: 4vw;position: absolute; color: <?php echo $this->getCartData['iconcolor3']; ?>; margin-left: 8%; margin-top: 40px"></i>            
                <p style="font-size: 2vw;font-weight: bold;margin-top: 20px; margin-left: 60%"><?php echo $this->getCartData['value3']; ?></p>
                <p style="margin-top: 20px; margin-left: 60%" style="font-size: 15px;"><?php echo $this->getCartData['name3']; ?></p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: <?php echo $this->getCartData['color4']; ?>; height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px; color: #fff; border-radius: 6px">
                <i class="fa <?php echo $this->getCartData['icon4']; ?>" style="font-size: 4vw;position: absolute; color: <?php echo $this->getCartData['iconcolor4']; ?>; margin-left: 8%; margin-top: 40px"></i>            
                <p style="font-size: 2vw;font-weight: bold;margin-top: 20px; margin-left: 60%"><?php echo $this->getCartData['value4']; ?></p>
                <p style="margin-top: 20px; margin-left: 60%" style="font-size: 15px;"><?php echo $this->getCartData['name4']; ?></p>
            </div>
        </div>
    <?php } ?>
    <div class="clearfix w-100"></div>
    <div class="col-md-12 col-sm-12" style="background-color:#fff;">
        <div class="col-md-6 col-sm-6" style="padding: 4px;">
        <p style="font-size: 15px; font-weight: bold; text-align: center"><?php echo $this->lang->line('execDB5_2'); ?></p>
        <div id="sales_widget_chart_<?php echo $this->uniqId; ?>"></div>	
        </div>
        <div class="col-md-6 col-sm-6" style="padding: 4px;">
            <p style="font-size: 15px; font-weight: bold; text-align: center"><?php echo $this->lang->line('execDB5_5'); ?></p>
            <div id="sales_widget_chart_222<?php echo $this->uniqId; ?>"></div>
        </div>        
        <div class="clearfix w-100"></div>
        <div class="col-md-6 col-sm-6" style="padding: 4px;">
            <p style="font-size: 15px; font-weight: bold; text-align: center"><?php echo $this->lang->line('execDB5_6'); ?></p>
            <div id="serial111_dashboard_<?php echo $this->uniqId; ?>"></div>
        </div>    
        <div class="col-md-6 col-sm-6" style="padding: 4px;">
            <p style="font-size: 15px; font-weight: bold; text-align: center"><?php echo $this->lang->line('execDB5_7'); ?></p>
            <div id="sales_widget_chart_222_333<?php echo $this->uniqId; ?>"></div>
        </div>           
    </div>                 
    </div>
    <div class="col-md-4 col-sm-4" style="background-color:#fff;">
        <div class="" style="background-color:#fff; margin-top: 20px;">   
            <p style="font-size: 15px; font-weight: bold; text-align: center"><?php echo $this->lang->line('execDB5_1'); ?></p>
            <table class="table table-hover" id="top5sale">
                <thead>
                    <tr>
                        <th style="font-size: 15px !important;  color: #000">
                            Төсвийн нэр
                        </th>
                        <th class="text-right" style="font-size: 15px !important;  color: #000">
                            Төлөвлөсөн
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    if($this->getTopFiveItem) {
                        $sumVar = 0;
                        foreach ($this->getTopFiveItem as $key => $row) {
                            echo '<tr>';
                            echo '<td style="vertical-align: middle; width: 35%">' . $row['name'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-right"><strong>' . $row['value'] . '</strong></td>';
                            echo '</tr>';
                        }
                    }

                    $sumType = 0;
                    /*if($this->getDataSalesByType) {
                        foreach ($this->getDataSalesByType as $row) {
                            $sumType += (int) $row['TOTAL_AMOUNT'];
                        }
                    }*/
                    
                    $sumType2 = 0;
                    
                    $arrResult = array();
                    $arrResult2 = array();
                    $arrResult3 = array();
                    if($this->getDataSalesByActivity) {
                        foreach ($this->getDataSalesByActivity as $k => $row) {
                            $arrResult[$k]['name'] = $row['name'];
                            $arrResult[$k]['y'] = (int) $row['value'];
                            $arrResult2[$k]['name'] = $row['name'];
                            $arrResult2[$k]['y'] = (int) $row['value1'];
                            $arrResult3[$k]['name'] = $row['name'];
                            $arrResult3[$k]['y'] = (int) $row['value2'];
                        }
                    }
                    
                    $arrResult11 = array();
                    $arrResult21 = array();
                    $arrResult31 = array();
                    if($this->getDataSalesByActivity2) {
                        foreach ($this->getDataSalesByActivity2 as $k => $row) {
                            $arrResult11[$k]['name'] = $row['name'];
                            $arrResult11[$k]['y'] = (int) $row['value'];
                            $arrResult21[$k]['name'] = $row['name'];
                            $arrResult21[$k]['y'] = (int) $row['value1'];
                            $arrResult31[$k]['name'] = $row['name'];
                            $arrResult31[$k]['y'] = (int) $row['value2'];
                        }
                    }
                    
                    $arrResultArea = array();
                    if($this->getAreaList) {
                        foreach ($this->getAreaList as $k => $row) {
                            $arrResultArea[$k]['name'] = $row['name'];
                            $arrResultArea[$k]['value'] = (int) $row['value'];
                        }
                    }
                    ?>                
                </tbody>
            </table>           
        </div>        
        <br>
        <p style="font-size: 15px; font-weight: bold; text-align: center"><?php echo $this->lang->line('execDB5_3'); ?></p>
        <div id="serial1_dashboard_<?php echo $this->uniqId; ?>"></div>
        <br>
        <p style="font-size: 15px; font-weight: bold; text-align: center"><?php echo $this->lang->line('execDB5_4'); ?></p>
        <div id="sales_widget_chart2_<?php echo $this->uniqId; ?>"></div>	
    </div>
</div>
</div>

<style>
    #serial1_dashboard_<?php echo $this->uniqId; ?>, #serial111_dashboard_<?php echo $this->uniqId; ?> {
        height: 380px;
    }
    #sales_widget_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div, #sales_widget_chart2_<?php echo $this->uniqId; ?> .amcharts-chart-div {
        height: 300px !important;
    }
    #sales_widget_chart_222<?php echo $this->uniqId; ?> .amcharts-chart-div {
        height: 300px !important;
    }
    #sales_widget_chart_222_333<?php echo $this->uniqId; ?> .amcharts-chart-div {
        height: 360px !important;
    }
    .sales-card-title {
        font-size: 15px;
    }    
    .dc-data-<?php echo $this->uniqId ?> {
        min-height: 68px;
        color: #fff;
    }
    .dc-data-<?php echo $this->uniqId ?> input{
        color: #333;
    }
    .dc-data-<?php echo $this->uniqId ?> .header-title-dd {
        font-size: 12px;
        width: 35%;
    }
    .dc-data-<?php echo $this->uniqId ?> .header-value-dd {
        font-size: 12px;
        margin-top: 10px;
        color: #fff;
        width: 60%;
    }    
    .main-title-<?php echo $this->uniqId ?> {
        width: 100%;
        float: left;
    }    
    .h2_style {
        color: #fff;
        margin-left: 20px;
    }
    .h2_style span{
        margin-right: 10px;
    }
    .h2_style .fa-users {
        font-size: 30px;
        color: #fff;
    }    
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#sales_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();

    $('#start-date, #end-date').inputmask('y-m-d');
    $('#start-date, #end-date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true, 
        todayBtn: 'linked', 
        todayHighlight: true 
    });       
    
    Core.initSelect2();    

    $(document).on('click', '#date-filter', function() {
        var depsStr = $('.departmentIds', widWindowId_<?php echo $this->uniqId; ?>).select2('val');
        var sd = $('#start-date', widWindowId_<?php echo $this->uniqId; ?>).val() == '' ? '_' : $('#start-date', widWindowId_<?php echo $this->uniqId; ?>).val();
        var ed = $('#end-date', widWindowId_<?php echo $this->uniqId; ?>).val() == '' ? '_' : $('#end-date', widWindowId_<?php echo $this->uniqId; ?>).val();
        var isHierarchy = $('#isHierarchy', widWindowId_<?php echo $this->uniqId; ?>).is(':checked') ? '1' : '';
        
        window.location = URL_APP + 'dashboard/glanceWithOther/' + sd + '/' + ed + '/' + depsStr + '/' + isHierarchy;
    });
    
    var $openRoleStartDate = $('#start-date');
    var $openRoleEndDate = $('#end-date');
    
    $openRoleStartDate.on('changeDate', function(){
        
        if ($openRoleStartDate.val() != '' && $openRoleEndDate.val() != '') {
            var $thisStartDateVal = new Date($openRoleStartDate.val());
            var $thisEndDateVal = new Date($openRoleEndDate.val());

            if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                $openRoleEndDate.datepicker('update', $openRoleStartDate.val());
            }
        }
    });
    
    $openRoleEndDate.on('changeDate', function(){
        
        if ($openRoleStartDate.val() != '' && $openRoleEndDate.val() != '') {
            var $thisStartDateVal = new Date($openRoleStartDate.val());
            var $thisEndDateVal = new Date($openRoleEndDate.val());

            if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                $openRoleStartDate.datepicker('update', $thisEndDateVal.getFullYear()+'-01-01');
            }
        }
    });    

    /*if($('#top5sale', widWindowId_<?php echo $this->uniqId; ?>).length) {
        $('#top5sale > tbody > tr', widWindowId_<?php echo $this->uniqId; ?>).each(function(){
            var chartContainer = $(this).find('td:last');
            
            if(chartContainer.attr('data-chart-json') != '')
                inlineWidgetRender_<?php echo $this->uniqId; ?>(chartContainer.children().attr('id'), chartContainer.attr('data-chart-json'));
        });
    }*/
    
    function inlineWidgetRender_<?php echo $this->uniqId; ?>($id, $dataJson) {
        var chartInline = AmCharts.makeChart($id, {
          "type": "serial",
          "dataProvider": JSON.parse($dataJson),
          "categoryField": "name",
          "autoMargins": false,
          "marginLeft": 0,
          "marginRight": 0,
          "marginTop": 0,
          "marginBottom": 0,
          "graphs": [{
            "valueField": "value",
            "type": "column",
            "fillAlphas": 1,
            "showBalloon": true,
            "lineColor": "rgb(88, 153, 226)",
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
        });
    }
    
    var chart = AmCharts.makeChart("sales_widget_chart_<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 13,
        "autoMargins": false,
        "labelText": "[[percents]]%",
        "allLabels": [{
          "y": "45%",
          "align": "center",
          "size": 15,
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
        "radius": "30%",
        "innerRadius": "70%",
        "dataProvider": <?php echo json_encode($this->getDataSalesByType); ?>,
        "valueField": "value",
        "titleField": "name",         
        //"gradientRatio": [0, 0, 0 ,-0.2, -0.4],
        "colorField": "color",   
        "export": {
            "enabled": false
        }
    });    
    for(var i = 0; i < chart.dataProvider.length; i++) {
        if(i == 0)
            chart.dataProvider[i].color = "rgb(88,153,226)";
        if(i == 1)
            chart.dataProvider[i].color = "rgb(73,183,236)";
        if(i == 2)
            chart.dataProvider[i].color = "rgb(73,214,190)";
        if(i == 3)
            chart.dataProvider[i].color = "rgb(131,220,238)";
        if(i == 4)
            chart.dataProvider[i].color = "rgb(123,233,168)";
    }   
    
    var chart = AmCharts.makeChart("sales_widget_chart_222<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 13,
        "autoMargins": false,
        "labelText": "[[percents]]%",
        "allLabels": [{
          "y": "45%",
          "align": "center",
          "size": 15,
          "bold": true,
          "text": '',
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
        "dataProvider": <?php echo json_encode($this->getDataSalesByType2); ?>,
        "valueField": "value",
        "titleField": "name",         
        //"gradientRatio": [0, 0, 0 ,-0.2, -0.4],
        "colorField": "color",   
        "export": {
            "enabled": false
        }
    });  
    for(var i = 0; i < chart.dataProvider.length; i++) {
        if(i == 0)
            chart.dataProvider[i].color = "rgb(88,153,226)";
        if(i == 1)
            chart.dataProvider[i].color = "rgb(73,183,236)";
        if(i == 2)
            chart.dataProvider[i].color = "rgb(73,214,190)";
        if(i == 3)
            chart.dataProvider[i].color = "rgb(131,220,238)";
        if(i == 4)
            chart.dataProvider[i].color = "rgb(123,233,168)";
    }      
    
    var chart = AmCharts.makeChart("sales_widget_chart_222_333<?php echo $this->uniqId; ?>", {
        "type": "serial",
        "theme": "light",
        "marginRight": 0,
        "marginLeft": 53,
        "autoMarginOffset": 20,
        "valueAxes": [ {
            "id": "v1",
            "axisAlpha": 0,            
            "position": "left",
            "ignoreAxisWidth": true
        } ],
        "balloon": {
            "borderThickness": 1,
            "shadowAlpha": 0
        },
        "graphs": [{
            "id": "g1",
            "balloon":{
                "drop":true,
                "adjustBorderColor":false,
                "color":"#ffffff"
            },
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "red line",
            "useLineColorForBulletBorder": true,
            "valueField": "value",
            "lineColor": "rgb(88, 153, 226)",
            "balloonText": "<span style='font-size:13px;'>[[value]]</span>"
        },{
            "id": "g2",
            "balloon":{
                "drop":true,
                "adjustBorderColor":false,
                "color":"#ffffff"
            },
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "red line",
            "useLineColorForBulletBorder": true,
            "valueField": "value1",
            "lineColor": "rgb(142,146,254)",
            "balloonText": "<span style='font-size:13px;'>[[value1]]</span>"
        }],
        "chartCursor": {
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            "cursorAlpha": 0,
            "zoomable": false,
            "valueZoomable": false,
            "valueLineAlpha": 0.5
        },
        "categoryField": "name",
        "categoryAxis": {
            "dashLength": 1,
            "labelRotation": 45,
            "minorGridEnabled": false
        },
        "export": {
            "enabled": false
        },
        "dataProvider": <?php echo json_encode($this->getDataSalesByType22); ?>
    }); 
    
    AmCharts.makeChart("sales_widget_chart2_<?php echo $this->uniqId; ?>", {
        "type": "serial",
        "theme": "light",
        "marginRight": 0,
        "marginLeft": 53,
        "autoMarginOffset": 20,
        "valueAxes": [ {
            "id": "v1",
            "axisAlpha": 0,
            "position": "left",
            "ignoreAxisWidth": true
        } ],
        "balloon": {
            "borderThickness": 1,
            "shadowAlpha": 0
        },
        "graphs": [{
            "id": "g1",
            "balloon": {
            "drop": true,
            "adjustBorderColor": false,
            "color": "#ffffff",
            "type": "smoothedLine"
            },
            "fillColors": "rgb(73,183,236)",
            "fillAlphas": 0.5,
            "bullet": "round",
            "bulletBorderAlpha": 0.2,
            "bulletColor": "#FFFFFF",
            "bulletSize": 3,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "red line",
            "useLineColorForBulletBorder": true,
            "valueField": "value",
            "lineColor": "rgb(88, 153, 226)",
            "balloonText": "<span style='font-size:13px;'>[[value]]</span>"
        }],
        "chartCursor": {
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            "cursorAlpha": 0,
            "zoomable": false,
            "valueZoomable": false,
            "valueLineAlpha": 0.5
        },
        "categoryField": "name",
        "categoryAxis": {
            "dashLength": 1,
            "labelRotation": 45,
            "minorGridEnabled": false
        },
        "export": {
            "enabled": false
        },
        "dataProvider": <?php echo json_encode($arrResultArea); ?>
    });    

    var d = new Date();
    d.setDate(d.getDate()-1);
    var n = d.getMonth();
    $('#serial1_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column',
            inverted: true
        },
        "title": {
          "text": ''
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
            color: "rgb(73,214,190)",
            data: <?php echo json_encode($arrResult); ?>
        },{
            color: "rgb(73,183,236)",
            data: <?php echo json_encode($arrResult2); ?>
        },{
            color: "rgb(88,153,226)",
            data: <?php echo json_encode($arrResult3); ?>
        }]
    });
    $('#serial111_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        "title": {
          "text": ''
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
            color: "rgb(73,214,190)",
            data: <?php echo json_encode($arrResult11); ?>
        },{
            color: "rgb(73,183,236)",
            data: <?php echo json_encode($arrResult21); ?>
        },{
            color: "rgb(88,153,226)",
            data: <?php echo json_encode($arrResult31); ?>
        }]
    });
</script>