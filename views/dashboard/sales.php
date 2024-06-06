<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card light shadow">			
                <div class="card-body form">
                    <form id="totalStoreByDate-form" role="form" method="post">
                        <div class="form-group row fom-row">
                            <div class="col-md-4">
                                <?php echo Form::select(array('name'=>'store_id[]','id'=>'store_id','class'=>'bs-select form-control','data'=>Info::getStoreList(),'op_value'=>'STORE_ID','op_text'=>'NAME','text'=>'notext', 'multiple'=>'multiple', 'data-live-search'=>'true', 'data-selected-text-format'=>'count>3','selected'=>'all')); ?>
                            </div>
                            <div class="col-md-8">
                                <div class="float-left">
                                    <div class="btn-group btn-group-sm dateViewType pt1" data-toggle="buttons">
                                        <label class="btn btn-secondary active">
                                            <input class="toggle active" type="radio" name="dateViewType" value="day" checked="checked"> Өдрөөр
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input class="toggle" type="radio" name="dateViewType" value="month"> Сараар
                                        </label>
                                    </div>
                                </div>
                                <div class="float-left pt2 dayRangeRender">
                                    <?php echo Form::text(array('name'=>'start_date','id'=>'start_date','value'=>Date::beforeDate('Y-m-d', '-1 month'),'class'=>'form-control input-sm input-date','placeholder'=>$this->lang->line('start_date'))); ?>
                                </div>
                                <div class="float-left pt2 dayRangeRender">
                                    <?php echo Form::text(array('name'=>'end_date','id'=>'end_date','value'=>Date::currentDate('Y-m-d'),'class'=>'form-control input-sm input-date','placeholder'=>$this->lang->line('start_date'))); ?>
                                </div>
                                <div class="float-left pt2 monthRangeRender" style="display: none">
                                    <?php echo Form::text(array('name'=>'start_month','id'=>'start_month','value'=>Date::beforeDate('Y-m', '-3 month'),'class'=>'form-control input-sm input-date','placeholder'=>$this->lang->line('start_date'))); ?>
                                </div>
                                <div class="float-left pt2 monthRangeRender" style="display: none">
                                    <?php echo Form::text(array('name'=>'end_month','id'=>'end_month','value'=>Date::currentDate('Y-m'),'class'=>'form-control input-sm input-date','placeholder'=>$this->lang->line('start_date'))); ?>
                                </div>
                                
                                <?php echo Form::button(array('class'=>'btn blue-hoki btn-xs','value'=>'<i class="fa fa-filter"></i> '.$this->lang->line('FIN_FILTER'),'onclick'=>'chart4();', 'style'=>'margin:4px 0 0 20px;')); ?>  
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <div id="chart4"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card light shadow">			
                <div class="card-body form">
                    <form class="form-inline" id="totalStore-form" role="form" method="post">
                        <div class="form-group row fom-row">
                            <?php echo Form::text(array('name'=>'start_date','id'=>'start_date','value'=>Date::beforeDate('Y-m-d', '-10 month'),'class'=>'form-control input-sm input-date','placeholder'=>$this->lang->line('start_date'))); ?>
                        </div>
                        <div class="form-group row fom-row">
                            <?php echo Form::text(array('name'=>'end_date','id'=>'end_date','value'=>Date::currentDate('Y-m-d'),'class'=>'form-control input-sm input-date','placeholder'=>$this->lang->line('end_date'))); ?>
                        </div>
                        <?php echo Form::button(array('class'=>'btn blue-hoki btn-xs','value'=>'<i class="fa fa-filter"></i> '.$this->lang->line('FIN_FILTER'),'onclick'=>'chart3();')); ?>  
                    </form>
                    <div id="chart3"></div>
                </div>
            </div>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-6">
            <div class="card light shadow">			
                <div class="card-body" id="chart1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card light shadow">			
                <div class="card-body" id="chart2"></div>
            </div>
        </div>
    </div>    
</div>

<script type="text/javascript">
$(function(){
    $("#start_month, #end_month").inputmask("y-m");
    $("#start_month").datepicker({
        format: "yyyy-mm",
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        var endDate = new Date($("#end_month").datepicker("getDate").valueOf());
        if (startDate > endDate) {
            $("#end_month").datepicker("update", startDate);
        } 
        $('#end_month').datepicker('setStartDate', startDate);
    }).on('clearDate', function(selected) {
        $('#end_month').datepicker('setStartDate', null);
    });

    $("#end_month").datepicker({
        format: "yyyy-mm",
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true
    }).on('changeDate', function(selected) {
        var startDate = new Date($("#start_date").datepicker("getDate").valueOf());
        var endDate = new Date(selected.date.valueOf());
        if (startDate > endDate) {
            $("#start_month").datepicker("update", endDate);
        } 
        $('#start_month').datepicker('setEndDate', endDate);
    }).on('clearDate', function(selected) {
        $('#start_month').datepicker('setEndDate', null);
    });
    
    $("#start_date, #end_date").inputmask("y-m-d");
    $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        var endDate = new Date($("#end_date").datepicker("getDate").valueOf());
        if (startDate > endDate) {
            $("#end_date").datepicker("update", startDate);
        } 
        $('#end_date').datepicker('setStartDate', startDate);
    }).on('clearDate', function(selected) {
        $('#end_date').datepicker('setStartDate', null);
    });

    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).on('changeDate', function(selected) {
        var startDate = new Date($("#start_date").datepicker("getDate").valueOf());
        var endDate = new Date(selected.date.valueOf());
        if (startDate > endDate) {
            $("#start_date").datepicker("update", endDate);
        } 
        $('#start_date').datepicker('setEndDate', endDate);
    }).on('clearDate', function(selected) {
        $('#start_date').datepicker('setEndDate', null);
    });

    $('.bs-select').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check'
    });
    
    $('.dateViewType').on('click', 'label', function(){ 
        var thisVal = $(this).find("input").val();
        if (thisVal === 'day') {
            $('.monthRangeRender').hide();
            $('.dayRangeRender').show();
        } else {
            $('.dayRangeRender').hide();
            $('.monthRangeRender').show();
        }
    });
    
    chart4();
    chart3();
    chart1();
    chart2();
});    

function chart1(){
    var options1 = {
        chart: {
            renderTo: 'chart1',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Борлуулалт (сувгаар) - Дүн',
            style: {
                fontSize: "13px",
                fontWeight: "bold"
            }
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y, 0, '.')+' ('+Highcharts.numberFormat(this.percentage, 1, '.')+'%)';
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return this.point.name;
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Channel',
            data: []
        }]
    };

    $.getJSON("dashboard/salesChart1", function(json) {
        options1.series[0].data = json;
        chart = new Highcharts.Chart(options1);
    }); 
}

function chart2(){
    var options2 = {
        chart: {
            renderTo: 'chart2',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Борлуулалт (сувгаар) - Тоо хэмжээ',
            style: {
                fontSize: "13px",
                fontWeight: "bold"
            }
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y, 0, '.')+' ('+Highcharts.numberFormat(this.percentage, 1, '.')+'%)';
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return this.point.name;
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Channel',
            data: []
        }]
    };

    $.getJSON("dashboard/salesChart2", function(json) {
        options2.series[0].data = json;
        chart = new Highcharts.Chart(options2);
    }); 
}

function chart3(){
    var options3 = {
        chart: {
            renderTo: 'chart3',
            type: 'column'
        },
        title: {
            text: 'Борлуулалтын мэдээ /салбараар/',
            style: {
                fontSize: "13px",
                fontWeight: "bold"
            }
        },
        scrollbar: {
            enabled: true
        },
        xAxis: {
            type: 'category',
            min: 1,
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y, 0, '.');
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            type: 'column',
            name: 'Channel',
            color: '#7798BF',
            shadow: true,
            data: [],
            dataLabels: {
                enabled: true,
                color: '#000000',
                align: 'center',
                x: 4,
                y: 1,
                style: {
                    fontSize: '10px'
                }
            }
        }]
    };
    
    $.ajax({
        type: 'post',
        url: 'dashboard/salesChart3',
        data: $("#totalStore-form").serialize(),
        dataType: "json",
        beforeSend:function(){},
        success:function(json){
            options3.series[0].data = json;
            chart = new Highcharts.Chart(options3);
        }
    });
}

function chart4(){
    var options4 = {
        chart: {
            renderTo: 'chart4',
            type: 'column'
        },
        title: {
            text: 'Борлуулалтын мэдээ',
            style: {
                fontSize: "13px",
                fontWeight: "bold"
            }
        },
        subtitle: {
            text: 'мянган төгрөгөөр'
        },
        xAxis: {
            type: 'category',
            min: 1,
            labels: {
                rotation: -45,
                style: {
                    fontSize: '11px'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        scrollbar: {
            enabled: true
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y, 0, '.');
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            type: 'column',
            name: 'Channel',
            color: '#8d4654',
            shadow: true,
            data: [],
            dataLabels: {
                enabled: true,
                color: '#000000',
                align: 'center',
                x: 4,
                y: 1,
                style: {
                    fontSize: '10px'
                },
                formatter: function() {
                    return Highcharts.numberFormat(this.y, 0, '.');
                }
            }
        }]
    };
    
    $.ajax({
        type: 'post',
        url: 'dashboard/salesChart4',
        data: $("#totalStoreByDate-form").serialize(),
        dataType: "json",
        beforeSend:function(){},
        success:function(json){
            options4.series[0].data = json;
            chart = new Highcharts.Chart(options4);
        }
    });
}
</script> 