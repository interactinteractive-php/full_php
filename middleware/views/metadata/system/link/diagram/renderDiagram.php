<div class="col-md-12">
    <div id="container"></div>
</div>
<div id="dialog-confirm"></div>
<?php
?>
<script type="text/javascript">
    var metaDataId = '<?php echo $this->metaDataId; ?>',
            chartType = '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>',
            title;
    if (chartType === 'column' || chartType === 'bar') {
        $.ajax({
            type: 'post',
            url: 'mddashboard/getColumnDiagramData',
            dataType: 'json',
            data: {metaDataId: metaDataId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (response) {
                if (response.chartType === 'column' || response.chartType === 'bar') {
                    chartColumn(response);
                    Core.unblockUI();
                }
            }
        });
    } else if (chartType === 'pie') {
        $.ajax({
            type: 'post',
            url: 'mddashboard/getPieDiagramData',
            dataType: 'json',
            data: {metaDataId: metaDataId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (response) {
                if (response.chartType === 'pie') {
                    chartPie(response);
                    Core.unblockUI();
                }
            }
        });
    }

    function chartColumn(response) {
        $('#container').width(response.width);
        $('#container').height(response.height);

        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }
        if (response.isTitle === 1) {
            title = response.title;
        } else {
            title = '';
        }

        var options = {
            chart: {
                type: response.chartType,
                renderTo: 'container'
            },
            title: {
                text: title
            },
            credits: {
                enabled: false
            },
            colors: response.colorList ? response.colorList : ['#7cb5ec', '#434348', '#90ed7d',
                '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'],
            exporting: {
                enabled: isExport
            },
            xAxis: {
                categories: response.categories,
                labels:
                        {
                            enabled: false
                        }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
            },
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: ''
                }
            },
            series: response.series
        };
        var chart = new Highcharts.Chart(options);
//    var opt = chart.series[0].options;
//    opt.dataLabels.enabled = !opt.dataLabels.enabled;
//    chart.series[0].update(opt);
        if (response.isLegend === 0) {
            var legend = chart.legend;
            if (legend.display) {
                legend.group.hide();
                legend.display = false;
            }
        }
    }
    ;

    function chartPie(response) {
        $('#container').width(response.width);
        $('#container').height(response.height);
        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }
        if (response.isTitle === 1) {
            title = response.title;
        } else {
            title = '';
        }

        var options = {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                renderTo: 'container'
            },
            colors: response.colorList ? response.colorList : ['#7cb5ec', '#434348', '#90ed7d',
                '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'],
            title: {
                text: title
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: isExport
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                    name: response.seriesName ? response.seriesName : 'Total',
                    colorByPoint: true,
                    data: response.series
                }]
        };
        var chart = new Highcharts.Chart(options);
        if (response.isLegend === 0) {
            var legend = chart.legend;
            if (legend.display) {
                legend.group.hide();
                legend.display = false;
            }
        }
    }
    ;
</script>


