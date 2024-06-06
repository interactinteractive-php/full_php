<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="sales_widget_window_<?php echo $this->uniqId; ?>">
<div class="col-md-12">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table">Oyu Tolgoi</h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table; color: #40d0d6" class="mt5">RECRUITMENT STATUS</p>
        <p style="font-size: 15px; margin: 0 auto; display: table; color: #e26b0a" class="mt5"><?php echo strtoupper(Date::currentDate('M Y')); ?></p>
        <p style="font-size: 15px; margin: 0 auto; display: table; color: #e26b0a" class="mt15">
            <input type="text" class="" value="<?php echo $this->startDate; ?>" style="border: 1px solid #ccc" id="start-date">&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php echo $this->endDate; ?>" style="border: 1px solid #ccc" id="end-date">&nbsp;&nbsp;&nbsp;
            <button id="date-filter" data-html2canvas-ignore>Filter</button>&nbsp;&nbsp;&nbsp;
            <button id="capture_btn" data-html2canvas-ignore>Capture</button>
        </p>
    </div>
</div>
<div class="col-md-12 col-sm-12 mt20">
    <div class="" style="background-color:#fff;">
        <div class="col-md-6">
            <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                <thead style="background-color: #e26b0a">
                    <tr style="">
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center" colspan="2">Open roles</td>
                    </tr>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000" colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000" colspan="2">Excluded Cat1</td>
                    </tr>
                    <tr>
                        <th style="font-size: 15px !important;  color: #000">
                            Row Labels
                        </th>
                        <th class="text-right" style="font-size: 15px !important;  color: #000">
                            Count of sub
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    $sumVar = 0;
                    if($this->openRoles) {
                        foreach ($this->openRoles as $key => $row) {
                            echo '<tr>';
                            echo '<td style="vertical-align: middle; width: 85%">' . $row['DEPARTMENT_NAME'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-right"><strong>' . $row['COUNT'] . '</strong></td>';
                            echo '</tr>';
                            $sumVar += (int) $row['COUNT'];
                        }
                    }
                    
                    $arrResult = array();
                    $arrResult2 = array();
                    if($this->getDataSalesByActivity) {
                        foreach ($this->getDataSalesByActivity as $k => $row) {
                            $arrResult[$k]['name'] = $row['DEPARTMENT_NAME'];
                            $arrResult[$k]['y'] = (int) $row['COUNT_OPEN'];
                            
                            $arrResult2[$k]['name'] = $row['DEPARTMENT_NAME'];
                            $arrResult2[$k]['y'] = (int) $row['COUNT_FILLED'];
                        }
                    }                    
                    ?>                
                </tbody>
                <tfoot style="border-top: 3px solid #e26b0a">
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold;" colspan="1">Grand Total</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: right" colspan="2"><?php echo $sumVar; ?></td>
                    </tr>
                </tfoot>
            </table>           
        </div>   
        <div class="col-md-6">
            <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                <thead style="background-color: #31869b">
                    <tr style="">
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center" colspan="2">Filled roles YTD</td>
                    </tr>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000" colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000" colspan="2">Excluded Cat1</td>
                    </tr>
                    <tr>
                        <th style="font-size: 15px !important;  color: #000">
                            Departments
                        </th>
                        <th class="text-right" style="font-size: 15px !important;  color: #000">
                            Sum of Filled Roles
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    $sumVar = 0;
                    if($this->filledRoles) {
                        foreach ($this->filledRoles as $key => $row) {
                            echo '<tr>';
                            echo '<td style="vertical-align: middle; width: 75%">' . $row['DEPARTMENT_NAME'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-right"><strong>' . $row['COUNT'] . '</strong></td>';
                            echo '</tr>';
                            $sumVar += (int) $row['COUNT'];
                        }
                    }                
                    ?>                
                </tbody>
                <tfoot style="border-top: 3px solid #31869b">
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold;" colspan="1">Grand Total</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: right" colspan="2"><?php echo $sumVar; ?></td>
                    </tr>
                </tfoot>
            </table>           
        </div>   
    </div>   
</div>
<div class="col-md-12 col-sm-12"> 
    <div class="col-md-12 mt5" style="background-color:#fff">
        <div id="serial1_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>
        <br>
    </div>
</div>
</div>

<style>
    #serial1_dashboard_<?php echo $this->uniqId; ?>, #sales2_widget_chart_<?php echo $this->uniqId; ?> {
        height: 420px;
    }
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#sales_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();
    
    $(function(){
        $(document).on('click', '#capture_btn', function(){
            
            html2canvas(document.body).then(canvas => {
                document.body.appendChild(canvas)
                console.log(canvas.toDataURL("image/png"));
            });    
        });
        
        $('#start-date, #end-date').inputmask('y-m-d');
        $('#start-date, #end-date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true, 
            todayBtn: 'linked', 
            todayHighlight: true 
        });       
        
        $(document).on('click', '#date-filter', function(){
            window.location = URL_APP + 'dashboard/recruitment_status/' + $('#start-date').val() + '/' + $('#end-date').val();
        });
    });

    var d = new Date();
    d.setDate(d.getDate()-1);
    var n = d.getMonth();
    $('#serial1_dashboard_<?php echo $this->uniqId; ?>').highcharts({
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
            //type: 'logarithmic'
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
            color: "#e26b0a",
            name: "In process",
            data: <?php echo json_encode($arrResult2); ?>
        },{                
            color: "#31869b",
            name: "Filled YTD",
            data: <?php echo json_encode($arrResult); ?>
        }]
    });
</script>