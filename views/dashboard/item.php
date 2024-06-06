<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card light shadow">			
                <div class="card-body">
                    <form class="form-inline" id="itemBalance-form" role="form" method="post">
                        <div class="form-group row fom-row">
                            <?php echo Form::select(array('name'=>'item_id','id'=>'item_id','data'=>Info::getItemList(),'op_value'=>'ITEM_ID','op_text'=>'ITEM_CODE| |-| |ITEM_NAME','class'=>'form-control input-sm select2me input-xlarge','text'=>'notext')); ?>
                        </div>
                        <div class="form-group row fom-row">
                            <?php echo Form::text(array('name'=>'start_date','id'=>'start_date','value'=>Date::currentDate('Y-m-d'),'class'=>'form-control input-sm input-date','placeholder'=>$this->lang->line('start_date'))); ?>
                        </div>
                        <?php echo Form::button(array('class'=>'btn blue-hoki btn-xs','value'=>'<i class="fa fa-filter"></i> '.$this->lang->line('FIN_FILTER'),'onclick'=>'chart1();')); ?>  
                    </form>
                    <div id="chart1"></div>
                </div>
            </div>
        </div>
    </div>    
</div>

<script type="text/javascript">
$(function(){
    $("#start_date").inputmask("y-m-d");
    $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    chart1();
});    

function chart1(){
    var options1 = {
        chart: {
            renderTo: 'chart1',
            type: 'column'
        },
        title: {
            text: 'Бараа материалын үлдэгдэл',
            style: {
                fontSize: "13px",
                fontWeight: "bold"
            }
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
        url: 'dashboard/itemChart1',
        data: $("#itemBalance-form").serialize(),
        dataType: "json",
        beforeSend:function(){},
        success:function(json){
            options1.series[0].data = json;
            chart = new Highcharts.Chart(options1);
        }
    });
}
</script> 