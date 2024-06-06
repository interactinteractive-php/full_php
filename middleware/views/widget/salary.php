<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 

$arrResult2 = array();
if($this->getDataHrmsByRegimen) {
    foreach ($this->getDataHrmsByRegimen as $k => $row) {
        $arrResult2[$k]['name'] = $row['BOOK_TYPE_NAME'];
        $arrResult2[$k]['y'] = (int) $row['CNT'];
    }
}

$arrResult3 = array();
$arrResult33 = array();
if($this->getDataHrmsByPension) {
    foreach ($this->getDataHrmsByPension as $k => $row) {
        $arrResult3[$k]['name'] = $row['month'];
        $arrResult3[$k]['y'] = (int) $row['f244'];
        $arrResult33[$k]['name'] = $row['month'];
        $arrResult33[$k]['y'] = (int) $row['f245'];
    }
}

$arrResult6 = array();
$arrResult66 = array();
if($this->getDataHrmsByPension1) {
    foreach ($this->getDataHrmsByPension1 as $k => $row) {
        $arrResult6[$k]['name'] = $row['month'];
        $arrResult6[$k]['y'] = (int) $row['f201'];
        $arrResult66[$k]['name'] = $row['month'];
        $arrResult66[$k]['y'] = (int) $row['f199'];
    }
}

$arrResult4 = array();
if($this->getDataHhoatSalary) {
    foreach ($this->getDataHhoatSalary as $k => $row) {
        $arrResult4[$k]['name'] = $row['month'];
        $arrResult4[$k]['y'] = (int) $row['f161'];
    }
}

$arrResult5 = array();
if($this->getDataRestYearSalary) {
    foreach ($this->getDataRestYearSalary as $k => $row) {
        $arrResult5[$k]['name'] = $row['month'];
        $arrResult5[$k]['y'] = (int) $row['f188'];
    }
}

?> 

<div id="hrms_widget_window_<?php echo $this->uniqId; ?>">
<div class="col-md-12">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table">Голомт банк</h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table;" class="mt5">ЦАЛИНГИЙН МЭДЭЭ</p>
        <p style="font-size: 15px; margin: 0 auto; display: table;" class="mt5"><?php echo Date::currentDate('Y'); ?></p>
    </div>
</div>
<div class="col-md-12 col-sm-12 mt20">
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">ЭЭЛЖИЙН АМРАЛТ</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataCart1Salary, true); ?></p>
        </div>
    </div>     
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">ХЧТА ТЭТГЭМЖ, БАЙГУУЛЛАГААС</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataCart2Salary, true); ?></p>
        </div>
    </div>     
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">ИЛҮҮ ЦАГ</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataCart3Salary, true); ?></p>
        </div>
    </div>     
    <div class="col-md-3 col-sm-3" style="padding: 4px;">
        <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
            <p class="mt10" style="font-size: 15px;">ЦАЛИН БОДОЛТ</p>
            <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->getDataCart4Salary, true); ?></p>
        </div>
    </div>     
</div>
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 mt20 no-padding" style="background-color:#fff;">   
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">Нийгмийн даатгалын шимтгэл төлөлт | сараар</p>
            <div id="hrms7_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"><span><br><br><br><br><br><br><br><br><br><br>0</span></div>	
        </div>   
        
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">Амралтын мөнгө | сараар</p>
            <div id="hrms3_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>	
        </div>            
    </div>    
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff; padding-right: 0px">         
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">ХЧТА тэтгэмж | сараар</p>
            <div id="hrms5_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"><span><br><br><br><br><br><br><br><br><br><br>0</span></div>	
        </div>         
        
        <div class="" style="margin-top: 20px;">
            <p style="font-size: 15px; font-weight: bold">ХХОАТ | сараар</p>
            <div id="hrms6_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"><span><br><br><br><br><br><br><br><br><br><br>0</span></div>	
        </div>                      
    </div>
</div>
<div class="col-md-12 col-sm-12">    
    <div class="" style="margin-top: 10px;">
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
    
    <?php if(count($arrResult6) > 0) { ?>
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
            type: 'column',
            data: <?php echo json_encode($arrResult6); ?>
        },{
            color: "rgb(67, 67, 72)",
            type: 'spline',
            data: <?php echo json_encode($arrResult66); ?>
        }]
    });
    <?php } ?>
    
    <?php if(count($arrResult3) > 0) { ?>
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
            color: "rgb(144, 237, 125)",
            pointWidth: 50,
            type: 'column',
            data: <?php echo json_encode($arrResult3); ?>
        },{
            color: "rgb(67, 67, 72)",
            type: 'spline',
            data: <?php echo json_encode($arrResult33); ?>
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
            color: "rgb(247, 163, 92)",
            pointWidth: 50,
            data: <?php echo json_encode($arrResult4); ?>
        }]      
    });
    <?php } ?>
    
    <?php if(count($arrResult5) > 0) { ?>
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
            pointWidth: 50,
            data: <?php echo json_encode($arrResult5); ?>
        }]      
    });
    <?php } ?>
</script>