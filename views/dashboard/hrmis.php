<div class="col-md-12">
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
   var options1 = {
        chart: {
            renderTo: 'chart1',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Хэлтсийн ажилтнуудын эзлэх хувь',
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

    $.getJSON("dashboard/hrmChart1", function(json) {
        options1.series[0].data = json;
        chart = new Highcharts.Chart(options1);
    }); 
    
    var options2 = {
        chart: {
            renderTo: 'chart2',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Албан тушаалаар ажилтнуудын эзлэх хувь',
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

    $.getJSON("dashboard/hrmChart2", function(json) {
        options2.series[0].data = json;
        chart = new Highcharts.Chart(options2);
    }); 
});    
</script> 