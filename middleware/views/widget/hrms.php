<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 

$sumPayment = 0;
if($this->getDataHrmsList) {
    foreach ($this->getDataHrmsList as $row) {
        $sumPayment += (int) $row['CNT'];
    }
}

$sumType = 0;
if($this->getDataHrmsByType) {
    foreach ($this->getDataHrmsByType as $row) {
        $sumType += (int) $row['CNT'];
    }
}

$arrResult = array();
$arrResult0 = array();
if($this->getDataHrmsByDepartment) {
    foreach ($this->getDataHrmsByDepartment as $k => $row) {
        $arrResult[$k]['name'] = $row['DEPARTMENT_NAME'];
        $arrResult[$k]['y'] = (int) $row['ALL_CNT'];
        
        $arrResult0[$k]['name'] = $row['DEPARTMENT_NAME'];
        $arrResult0[$k]['y'] = (int) $row['NEW_CNT'];
    }
}

$arrResult00 = array();
$arrResult000 = array();
if($this->getDataTimeByDepartment) {
    foreach ($this->getDataTimeByDepartment as $k => $row) {
        $arrResult00[$k]['name'] = $row['DEPARTMENT_NAME'];
        $arrResult00[$k]['y'] = (int) $row['PLAN_TIME'];
        
        $arrResult000[$k]['name'] = $row['DEPARTMENT_NAME'];
        $arrResult000[$k]['y'] = (int) $row['WORKED_CLEAN_TIME'];
    }
}

$arrResult2 = array();
if($this->getDataHrmsByRegimen) {
    foreach ($this->getDataHrmsByRegimen as $k => $row) {
        $arrResult2[$k]['name'] = $row['BOOK_TYPE_NAME'];
        $arrResult2[$k]['y'] = (int) $row['CNT'];
    }
}

$arrResult3 = array();
if($this->getDataHrmsByPension) {
    foreach ($this->getDataHrmsByPension as $k => $row) {
        $arrResult3[$k]['name'] = $row['BOOK_TYPE_NAME'];
        $arrResult3[$k]['y'] = (int) $row['CNT'];
    }
}

$arrResult4 = array();
if($this->getDataHrmsByWorkOut) {
    foreach ($this->getDataHrmsByWorkOut as $k => $row) {
        $arrResult4[$k]['name'] = $row['DEPARTMENT_NAME'];
        $arrResult4[$k]['y'] = (int) $row['CNT'];
    }
}

?> 

<div id="hrms_widget_window_<?php echo $this->uniqId; ?>">
<div class="col-md-12">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table">Ривер бэйкери ХХК</h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table;" class="mt5">ХҮНИЙ НӨӨЦИЙН МЭДЭЭ</p>
        <p style="font-size: 15px; margin: 0 auto; display: table;" class="mt5"><?php echo Date::beforeDate('Y.m.d', '-1 day'); ?></p>
    </div>
</div>
<div class="col-md-12 col-sm-12 mt20">
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Ажилд орсон ажилтнууд</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataHrmsCart['cart1Data']); ?></p>
        </div>
    </div>     
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Гарсан ажилтнууд</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataHrmsCart['cart2Data']); ?></p>
        </div>
    </div>     
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Анкет бөглөсөн ажил горилогчид</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataHrmsCart['cart3Data']); ?></p>
        </div>
    </div>     
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">Сахилга авсан ажилтнууд</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataHrmsCart['cart4Data']); ?></p>
        </div>
    </div>     
</div>
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 mt20 no-padding" style="background-color:#fff;">
        <p style="font-size: 15px; font-weight: bold">Ажилтны төрлөөр</p>
        <div id="hrms2_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>	
        
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">Нэгжээр /Ажилтны тоо/</p>
            <div id="hrms3_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>	
        </div>    
        
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">Тэтгэмж, Хангамжийн төрлөөр</p>
            <div id="hrms5_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"><span><br><br><br><br><br><br><br><br><br><br>0</span></div>	
        </div>    
    </div>    
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff; padding-right: 0px">
        <p style="font-size: 15px; font-weight: bold;">Ажилтны статусаар</p>
        <div id="hrms_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>	
        
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">Ажлаас гарсан /Нэгжээр/</p>
            <div id="hrms6_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"><span><br><br><br><br><br><br><br><br><br><br>0</span></div>	
        </div>          
        
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">Сахилгын төрлөөр</p>
            <div id="hrms4_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"><span><br><br><br><br><br><br><br><br><br><br>0</span></div>	
        </div>                  
    </div>
</div>
<div class="col-md-12 col-sm-12">    
    <div class="" style="margin-top: 20px;">
        <p style="font-size: 15px; font-weight: bold">Нийт төлөвлөгөөт болон гүйцэтгэл цаг /Нэгжээр/</p>
        <div id="hrms7_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>
    </div>        
</div>
</div>

<style>
    #hrms3_widget_chart_<?php echo $this->uniqId; ?>, 
    #hrms2_widget_chart_<?php echo $this->uniqId; ?>, 
    #hrms4_widget_chart_<?php echo $this->uniqId; ?>, 
    #hrms5_widget_chart_<?php echo $this->uniqId; ?>,
    #hrms7_widget_chart_<?php echo $this->uniqId; ?>,
    #hrms6_widget_chart_<?php echo $this->uniqId; ?> {
        height: 420px;
        text-align: center;
    }
    #hrms4_widget_chart_<?php echo $this->uniqId; ?> span, 
    #hrms5_widget_chart_<?php echo $this->uniqId; ?> span,
    #hrms6_widget_chart_<?php echo $this->uniqId; ?> span {
        color: #000;
        font-weight: bold;        
        font-size: 15px;
    }
    #hrms_widget_chart_<?php echo $this->uniqId; ?> .amcharts-chart-div, #hrms_widget_chart2_<?php echo $this->uniqId; ?> .amcharts-chart-div {
        height: 420px !important;
    }
    .hrms-card-title {
        font-size: 15px;
    }
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#hrms_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();
    
    setTimeout(function () {
        if($("#hrms_widget_chart_<?php echo $this->uniqId; ?>").height() > $("#hrms2_widget_chart_<?php echo $this->uniqId; ?>").height()) {
            $("#hrms2_widget_chart_<?php echo $this->uniqId; ?>").height($("#hrms_widget_chart_<?php echo $this->uniqId; ?>").height());
        } else {
            $("#hrms_widget_chart_<?php echo $this->uniqId; ?>").height($("#hrms2_widget_chart_<?php echo $this->uniqId; ?>").height());
        }
    }, 100);    
    
    AmCharts.makeChart("hrms_widget_chart_<?php echo $this->uniqId; ?>", {
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
        "dataProvider": <?php echo json_encode($this->getDataHrmsByType); ?>,
        "valueField": "CNT",
        "titleField": "CURRRENT_STATUS_NAME",         
        "export": {
            "enabled": false
        }
    });    
    
    AmCharts.makeChart("hrms2_widget_chart_<?php echo $this->uniqId; ?>", {
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
          "text": '<?php echo Str::formatMoney($sumPayment); ?>',
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
        "dataProvider": <?php echo json_encode($this->getDataHrmsList); ?>,
        "valueField": "CNT",
        "titleField": "STATUS_NAME",         
        "export": {
            "enabled": false
        }
    });
    
    $('#hrms3_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
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
            allowDecimals: false,
            title: {
                enabled: false,
                text: 'Custom with <b>simple</b> <i>markup</i>',
                style: {
                    fontWeight: 'normal'
                }
            }
        },
        legend: {
            enabled: true,
            reversed: true
        },            
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true
                },
                stacking: 'normal'
            }            
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [{
            color: "#00b3b3",
            name: "Энэ сард",
            data: <?php echo json_encode($arrResult0); ?>
        },{
            color: "#ff9900",
            name: "Өмнөх саруудад",
            data: <?php echo json_encode($arrResult); ?>
        }]
    }); 
    
    $('#hrms7_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
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
            allowDecimals: false,
            title: {
                enabled: false,
                text: 'Custom with <b>simple</b> <i>markup</i>',
                style: {
                    fontWeight: 'normal'
                }
            }
        },
        legend: {
            enabled: true,
            reversed: false
        },            
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true
                }
            }            
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [
        {
            color: "#ff9900",
            name: "Төлөвлөгөөт цаг",
            data: <?php echo json_encode($arrResult00); ?>
        },{
            color: "#00b3b3",
            name: "Ажилсан цаг",
            data: <?php echo json_encode($arrResult000); ?>
        }]
    }); 
    
    <?php if(count($arrResult2) > 0) { ?>
    $('#hrms4_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
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
            allowDecimals: false,
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
            series: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [{
            pointWidth: 50,
            color: "#ff4f1e",
            data: <?php echo json_encode($arrResult2); ?>
        }]
    });
    <?php } ?>
    
    <?php if(count($arrResult3) > 0) { ?>
    $('#hrms5_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
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
            allowDecimals: false,
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
            series: {
                dataLabels: {
                    enabled: true
                }
            }            
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [{
            color: "rgb(132, 183, 97)",
            pointWidth: 50,
            data: <?php echo json_encode($arrResult3); ?>
        }]
    });
    <?php } ?>
    
    <?php if(count($arrResult4) > 0) { ?>
    $('#hrms6_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
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
            allowDecimals: false,
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
            series: {
                dataLabels: {
                    enabled: true
                }
            }            
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [{
            color: "rgb(103, 183, 220)",
            data: <?php echo json_encode($arrResult4); ?>
        }]      
    });
    <?php } ?>
</script>