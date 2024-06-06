<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="sales_widget_window_<?php echo $this->uniqId; ?>">
<div class="col-md-12">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table"><?php echo $this->depName ? $this->depName['DEP_NAME'] : 'UULS ZAAMAR LLC'; ?></h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table; color: #005bab" class="mt5">HR REPORT</p>
        <p style="font-size: 15px; margin: 0 auto; display: table; color: #005bab" class="mt15">
            <input type="text" class="" value="<?php echo $this->startDate; ?>" style="border: 1px solid #ccc" id="start-date">&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php echo $this->endDate; ?>" style="border: 1px solid #ccc" id="end-date">
        </p>
        <p style="font-size: 15px; margin: 0 auto; display: table; color: #005bab" class="mt15">
            <select style="width: 280px;" multiple="" class="departmentIds form-control select2" placeholder="- Сонгох -">
                <?php 
                if($this->depList) {
                    $this->depId = explode(",", $this->depId);
                    
                    foreach($this->depList as $key => $row) {
                        $sel = '';
                        if(in_array($row['departmentid'], $this->depId))
                            $sel = ' selected';
                        echo '<option' . $sel . ' value="' . $row['departmentid'] . '">' . $row['departmentname'] . '</option>';
                    }
                } ?>
            </select>            
        </p>
        <p style="font-size: 15px; margin: 0 auto; display: table; color: #005bab" class="mt15">
            <button id="date-filter" data-html2canvas-ignore class="">Хайх</button>&nbsp;&nbsp;
            <button id="send_btn" data-html2canvas-ignore="true" class="">Email</button>
        </p>
    </div>
</div>
    
<div class="col-md-6 col-sm-6 mt20">
    <div class="" style="background-color:#fff;">
        <?php if($this->list1) { ?>
            <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    echo '<tr>';
                        echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff; width:40%" rowspan="2">Total staff - ' . $this->list1[1]['count'] . '</td>';
                        echo '<td style="vertical-align: middle; background-color: #ff8533; color: #fff; width:60%" class="text-center">Working - ' . $this->list1[2]['count'] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td style="vertical-align: middle; background-color: #ff8533; color: #fff" class="text-center">Not employed - ' . $this->list1[0]['count'] . '</td>';
                    echo '</tr>';
                    ?>                
                </tbody>
            </table>     
        <?php } ?> 
    </div>   
</div>
    
<div class="col-md-6 col-sm-6 mt20">
    <div class="" style="background-color:#fff;">
        <?php if($this->list5) { ?>
            <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    echo '<tr>';
                        echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff; width:40%" rowspan="2">Total staff - ' . ($this->list5[1]['count'] + $this->list5[0]['count']) . '</td>';
                        echo '<td style="vertical-align: middle; width:60%" class="text-center">Male - <strong>' . $this->list5[1]['percent'] . '%</strong> (' . $this->list5[1]['count'] . ')</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td style="vertical-align: middle" class="text-center">Female - <strong>' . $this->list5[0]['percent'] . '%</strong> (' . $this->list5[0]['count'] . ')</td>';
                    echo '</tr>';
                    ?>                
                </tbody>
            </table>     
        <?php } ?> 
    </div>
</div>
    
<div class="col-md-12 col-sm-12 mt10">
    <div class="" style="background-color:#fff;">
            <?php if($this->list2) { ?>
                <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                    <thead style="background-color: rgb(9, 173, 236)">
                        <tr style="">
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center; width: 8px;">№</td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center">Position</td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center">Name</td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center">Comment</td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center">Started date</td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center">Finish date</td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px !important;  color: #000">
                        <?php
                        foreach($this->list2 as $key => $row) { 
                            echo '<tr>';
                                echo '<td style="vertical-align: middle" class="text-center; width: 8px;">' . ++$key . '</td>';
                                echo '<td style="vertical-align: middle" class="text-center">' . $row['positionname'] . '</td>';
                                echo '<td style="vertical-align: middle" class="text-center">' . $row['employeename'] . '</td>';
                                echo '<td style="vertical-align: middle" class="text-center">' . $row['comment'] . '</td>';
                                echo '<td style="vertical-align: middle" class="text-center">' . $row['startdate'] . '</td>';
                                echo '<td style="vertical-align: middle" class="text-center">' . $row['enddate'] . '</td>';
                            echo '</tr>';
                        }
                        ?>                
                    </tbody>
                </table>     
            <?php } ?>
    </div>   
</div>
    
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 no-padding" style="background-color:#fff;">
        <p style="font-size: 15px; font-weight: bold" class="mt20">The number of staff by company</p>
        <div id="seria72_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
    </div>
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff;padding-right: 0px">
        <p style="font-size: 15px; font-weight: bold" class="">Marital status</p>
        <div id="serial92_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>                
    </div>
</div>    
    
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 no-padding mt20" style="background-color:#fff;">  
        <p style="font-size: 15px; font-weight: bold">The number of staff by residents of Tuv aimag</p>
        <div id="hrm82_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>            
    </div>
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff;padding-right: 0px">
        <p style="font-size: 15px; font-weight: bold" class="">Registered as residents of Tuv Aimag</p>
        <div id="seria7222_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>        
    </div>
</div>
    
<div class="col-md-12 col-sm-12 mt20" style="background-color:#fff;">
    <p style="font-size: 15px; font-weight: bold">By age</p>
    <div id="sales2_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>        
</div>    
    
<div class="col-md-12 col-sm-12 mt20" style="background-color:#fff;">
    <p style="font-size: 15px; font-weight: bold">Median age of all staff</p>
    <div id="sales222_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>        
</div>    
    
<div class="col-md-12 col-sm-12 mt20" style="background-color:#fff;">
    <p style="font-size: 15px; font-weight: bold">The number of staff by educational level</p>
    <div id="hrm22_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>        
</div>    
    
<div class="col-md-12 col-sm-12 mt20" style="background-color:#fff;">
    <p style="font-size: 15px; font-weight: bold" class="">Duration of employment</p>
    <div id="seria722_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
</div>    
    
<div class="col-md-12 col-sm-12 mt20" style="background-color:#fff;">
    <p style="font-size: 15px; font-weight: bold">The number of staff by provinces</p>
    <div id="hrm222_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>        
</div>      
    
</div>

<?php
    
    $arrResult72 = array();
    if($this->list3) {
        foreach ($this->list3 as $k => $row) {
            array_push($arrResult72, array($row['name'], (int) $row['count']));
        }
    }
    
    $arrResult722 = array();
    if($this->list10) {
        foreach ($this->list10 as $k => $row) {
            array_push($arrResult722, array($row['firstname'], (float) $row['roomcount']));
        }
    }
    
    $arrResult7222 = array();
    if($this->list12) {
        foreach ($this->list12 as $k => $row) {
            array_push($arrResult7222, array($row['firstname'], (float) $row['roomcount']));
        }
    }

    $arrResult92 = array();
    if($this->list6) {
        foreach ($this->list6 as $k => $row) {
            array_push($arrResult92, array($row['name'], (int) $row['count']));
        }
    }       
    
    $arrResult82 = array();
    if($this->list4) {
        foreach ($this->list4 as $k => $row) {
            array_push($arrResult82, array($row['name'], (int) $row['count']));
        }
    }    
    
    $arrResult91 = array();
    if($this->list8) {
        $arrResult2 = array();
        foreach ($this->list8 as $k => $row) {
            array_push($arrResult2, array('Median age', (float) Str::formatMoney($row['cnt'], true)));
        }
        array_push($arrResult91, array(
            'name' => 'Median age',
            'data' => $arrResult2
        ));        
    }

    $arrResult22 = array();
    if($this->list9) {
        foreach ($this->list9 as $k => $row) {
            $arrResult2 = array();
            array_push($arrResult2, array(
                $row['name'],
                (float) $row['count']
            ));               
            
            array_push($arrResult22, array(
                'name' => $row['name'],
                'data' => $arrResult2
            ));        
        }
    }    

    $arrResult222 = array();
    if($this->list11) {
        foreach ($this->list11 as $k => $row) {
            $arrResult2 = array();
            array_push($arrResult2, array(
                $row['name'],
                (float) $row['count']
            ));               
            
            array_push($arrResult222, array(
                'name' => $row['name'],
                'data' => $arrResult2
            ));        
        }
    }    
?>

<style>
    #serial1_dashboard_<?php echo $this->uniqId; ?>, 
    #hrm31_widget_chart_<?php echo $this->uniqId; ?>, 
    #seria51_dashboard_<?php echo $this->uniqId; ?>, 
    #seria52_dashboard_<?php echo $this->uniqId; ?>, 
    #seria53_dashboard_<?php echo $this->uniqId; ?>, 
    #seria61_dashboard_<?php echo $this->uniqId; ?>, 
    #seria62_dashboard_<?php echo $this->uniqId; ?>, 
    #seria72_dashboard_<?php echo $this->uniqId; ?>, 
    #seria722_dashboard_<?php echo $this->uniqId; ?>, 
    #seria7222_dashboard_<?php echo $this->uniqId; ?>, 
    #hrm82_widget_chart_<?php echo $this->uniqId; ?>,
    #serial92_dashboard_<?php echo $this->uniqId; ?>,      
    #hrm22_widget_chart_<?php echo $this->uniqId; ?> {
        height: 420px;
    }
    #hrm222_widget_chart_<?php echo $this->uniqId; ?>,
    #sales2_widget_chart_<?php echo $this->uniqId; ?> {
        height: 460px;
    }
    #sales222_widget_chart_<?php echo $this->uniqId; ?> {
        height: 250px;
    }
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#sales_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();
    
    Highcharts.setOptions({
        chart: {
            style: {
                fontSize: '13'
            }
        }
    });    
    
    $(function(){
        $('#start-date, #end-date').inputmask('y-m-d');
        $('#start-date, #end-date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true, 
            todayBtn: 'linked', 
            todayHighlight: true 
        });       
        
        Core.initSelect2();
        
        $(document).on('click', '#date-filter', function(){
            var depsStr = $('.departmentIds', widWindowId_<?php echo $this->uniqId; ?>).select2('val').length > 0 ? $('.departmentIds', widWindowId_<?php echo $this->uniqId; ?>).select2('val').join(',') : '';
            var sd = $('#start-date', widWindowId_<?php echo $this->uniqId; ?>).val() == '' ? '_' : $('#start-date', widWindowId_<?php echo $this->uniqId; ?>).val();
            var ed = $('#end-date', widWindowId_<?php echo $this->uniqId; ?>).val() == '' ? '_' : $('#end-date', widWindowId_<?php echo $this->uniqId; ?>).val();
            
            window.location = URL_APP + 'dashboard/hrm_uz/' + sd + '/' + ed + '/' + depsStr;
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
        
        $('#send_btn').on('click', function(){
            var $dialogName = 'dialog-email-'+getUniqueId(1);
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);

            $.ajax({
                type: 'post',
                url: 'dashboard/sendMailForm', 
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true 
                    });
                },
                success: function (data) {
                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 950,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        position: {my:'top', at:'top+55'}, 
                        buttons: [
                            {text: data.close_btn, class: 'btn btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                        ]
                    });
                    $dialog.dialog('open');

                    $('.sendMailBtnOt').off().on('click', function(){
                        tinyMCE.triggerSave();

                        $("#dashboard-mail-form").validate({errorPlacement: function () {}});

                        if ($("#dashboard-mail-form").valid()) {
                            
                            Core.blockUI({
                                message: 'Sending email...',
                                boxed: true
                            });
                            
                            setTimeout(function(){
                                
                                $('body').find('.ui-dialog, .ui-widget-overlay, .blockUI').attr('data-html2canvas-ignore', 'true');
                                
                                html2canvas(document.body).then(function(canvas) {
                                    $('#dashboard-mail-form', '#' + $dialogName).ajaxSubmit({
                                        type: 'post',
                                        url: 'dashboard/sendMail',
                                        data: data,
                                        dataType: 'json',
                                        beforeSubmit: function (formData, jqForm, options) {
                                            formData.push(
                                                {name: 'base64image', value: canvas.toDataURL("image/png")}
                                            );
                                        },
                                        success: function (data) {
                                            PNotify.removeAll();

                                            if (data.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                                $dialog.dialog('close');
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            Core.unblockUI();
                                        }
                                    });
                                });
                            }, 1000);
                        }
                    });

                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initAjax($dialog);
            });
        }); 
    });   
    
    $('#seria72_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category'
//            labels: {
//                rotation: -90,
//                style: {
//                    fontSize: '12px'
//                }                
//            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },        
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },        
        series: [{name: '', data: <?php echo json_encode($arrResult72); ?>}]
    });    
    
    $('#seria722_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category'
//            labels: {
//                rotation: -90,
//                style: {
//                    fontSize: '12px'
//                }                
//            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },        
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },        
        series: [{name: '', data: <?php echo json_encode($arrResult722); ?>}]
    });    
    
    $('#seria7222_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category'
//            labels: {
//                rotation: -90,
//                style: {
//                    fontSize: '12px'
//                }                
//            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },        
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },        
        series: [{name: '', data: <?php echo json_encode($arrResult7222); ?>}]
    });    
    
    $('#hrm82_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'pie'
        },
        "title": "",
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.0f}, ({point.percentage:.1f}%)'
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
        },
        series: [{
            name: '', 
            innerSize: '50%',
            data: <?php echo json_encode($arrResult82); ?>
        }]
    });    
     
    $('#serial92_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'pie'
        },
        "title": "",
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.0f}, ({point.percentage:.1f}%)'
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
        },
        series: [{
            name: '', 
            data: <?php echo json_encode($arrResult92); ?>
        }]
    });    
    
    AmCharts.makeChart("sales2_widget_chart_<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 15,
        "depth3D": 20,
        "angle": 40,
        "labelText": "[[percents]]%",           
        "legend": {
            "position": "right",
            "marginRight": 100,
            "valueWidth": 120,
            "autoMargins": false,
            "markerType": "circle"
        },
        "dataProvider": <?php echo json_encode($this->list7); ?>,
        "valueField": "roomcount",
        "titleField": "firstname",         
        "export": {
            "enabled": false
        }
    });    
    
    $('#sales222_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'bar'
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
            series: {
                dataLabels: {
                    enabled: true
                },
                stacking: 'normal'
            }            
        },
        series: <?php echo json_encode($arrResult91); ?>
    });    
    
    $('#hrm22_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'bar'
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
            series: {
                dataLabels: {
                    enabled: true
                },
                stacking: 'normal'
            }            
        },
        series: <?php echo json_encode($arrResult22); ?>
    });    
    
    $('#hrm222_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'bar'
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
            series: {
                dataLabels: {
                    enabled: true
                },
                stacking: 'normal'
            }            
        },
        series: <?php echo json_encode($arrResult222); ?>
    });    
</script>