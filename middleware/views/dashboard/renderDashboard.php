<div class="col-md-12 mt10 pl0 pr0">
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container" style="width: <?php echo ($this->diagram['WIDTH']) ? $this->diagram['WIDTH'].'px' : '100%'; ?>; height: <?php echo ($this->diagram['HEIGHT']) ? $this->diagram['HEIGHT'].'px' : '100%'; ?>">
        <div class="card light bordered mb0 pb5 mddashboard-card">
            <div class="card-title mddashboard-card-title" id="card-title-<?php echo $this->metaDataId; ?>">
                <div class="caption mddashboard-caption">
                    <span class="caption-subject font-weight-bold mddashboard-title" title="" id="dashboard-title-<?php echo $this->metaDataId; ?>"></span>
                    <span class="caption-helper mddashboard-helper" id="dashboard-helper-<?php echo $this->metaDataId; ?>"></span>
                </div>
                <div class="actions mddashboard-actions">
                    <a href="javascript:;" class="btn btn-sm blue-madison" style="color:#EEEEEE !important" id="search-btn-<?php echo $this->metaDataId; ?>"><i class="fa fa-search"></i> Хайлт</a>
                </div>
            </div>
            <div class="card-body" id="dashboard-<?php echo $this->metaDataId; ?>">
                <img src="assets/core/global/img/loading.gif" />
            </div>
        </div>
    </div>
</div>
<div id="dashboard-filter-div-<?php echo $this->metaDataId; ?>" class="hidden">
    <form class="dashboard-filter-form-<?php echo $this->metaDataId; ?>">
        <?php echo $this->defaultCriteria; ?>
    </form>
</div>
<script type="text/javascript">
    var metaDataId<?php echo $this->metaDataId; ?> = '<?php echo $this->metaDataId; ?>',
            chartType<?php echo $this->metaDataId; ?> = '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>',
            title<?php echo $this->metaDataId; ?>;

    jQuery(document).ready(function () {
        amChartMinify.init();
        Core.initAjax();
<?php if ($this->dataViewHeaderData != null) { ?>

            $("#dashboard-filter-form-<?php echo $this->metaDataId; ?> .dataview-default-filter-btn").on("click", function () {
                drawChart($('#dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
            });

            drawChart($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
<?php } else { ?>
            drawChart($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
<?php } ?>
    });

    function drawChart(defaultCriteriaData, chartType, metaDataId) {
        if (chartType === 'column') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getColumnDiagramData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    if (typeof response.series != 'undefined' && (response.series).length != 0) {
                        chartColumn(response, 'dashboard-' + metaDataId, metaDataId);
                    }
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });

        } else
        if (chartType === 'columnOne') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getColumnOneDiagramData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    if (typeof response.series != 'undefined' && (response.series).length != 0) {
                        chartColumn(response, 'dashboard-' + metaDataId, metaDataId);
                    }
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        } else
        if (chartType === 'barOne') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getBarOneDiagramData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    chartBar(response, 'dashboard-' + metaDataId, metaDataId);
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        } else
        if (chartType === 'bar') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getColumnDiagramData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    chartBar(response, 'dashboard-' + metaDataId, metaDataId);
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        } else
        if (chartType === 'pie') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getPieDiagramData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    chartPie(response, 'dashboard-' + metaDataId, metaDataId);
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        } else
        if (chartType === 'line') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getLineDiagramData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    chartLine(response, 'dashboard-' + metaDataId, metaDataId);
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        } else
        if (chartType === 'areaWithNull') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getAreaWithNullDiagramData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    chartAreaWithNull(response, 'dashboard-' + metaDataId, metaDataId);
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        } else
        if (chartType === 'stockSingleLine') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getStockSingleLineData',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    chartStockSingleLine(response, 'dashboard-' + metaDataId, metaDataId);
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        } else
        if (chartType === 'dualAxes') {
            $.ajax({
                type: 'post',
                url: URL_APP + 'mddashboard/getDualAxes',
                dataType: 'json',
                data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    dualAxes(response, 'dashboard-' + metaDataId, metaDataId);
                    Core.unblockUI();
                }
            }).done(function () {
                $('#dashboard-container-' + metaDataId).find('.open').removeClass('open');
            });
        }
    }

    function chartColumn(response, renderTo, metaDataId) {
<?php if (!isset($this->isLayout)) { ?>
//            $('#dashboard-container-' + metaDataId).width(response.width + 50);
//            $('#dashboard-container-' + metaDataId).height(response.height + 50);
<?php } ?>

        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }

        var title = '';
        if (response.isTitle === 1) {
            title = response.title;
            $('#card-title-' + metaDataId).show();
            $('#dashboard-title-' + metaDataId).html(title);
        } else {
            $('#card-title-' + metaDataId).hide();
        }


        var legend = false;
        if (response.isShowLabel === 1) {
            legend = true;
        }

        var isXLabel = false;
        if (response.isXLabel === 1) {
            isXLabel = true;
        }

        var isYLabel = false;
        if (response.isYLabel === 1) {
            isYLabel = true;
        }
        var tickLength = 0;
        var lineWidth = 0;
        var gridLineWidthX = 0;
        var gridLineWidthY = 0;
        if (response.isBackground === 1) {
            tickLength = 10;
            lineWidth = 1;
            gridLineWidthX = 0;
            gridLineWidthY = 1;
        } else {
            tickLength = 0;
            lineWidth = 0;
            gridLineWidthX = 0;
            gridLineWidthY = 0;
        }

        var groupPadding = 0;
        var pointPadding = 1;
        if (response.isLittle === 1) {
            groupPadding = 0;
            pointPadding = 0.02;
        } else {
            groupPadding = 0.2;
            pointPadding = 0.1;
        }

        var options = {
            chart: {
                type: 'column',
                renderTo: renderTo,
                width: response.width,
                height: response.height
            },
            title: false,
            exporting: {
                enabled: isExport
            },
            legend: legend,
            credits: {
                enabled: false
            },
            colors: response.colorList ? response.colorList : ['#7cb5ec', '#434348', '#90ed7d',
                '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'],
            xAxis: {
                categories: response.categories,
                labels:
                        {
                            enabled: isXLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthX,
                tickLength: tickLength
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                labels:
                        {
                            enabled: isYLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthY,
                tickLength: tickLength
            },
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: '',
                    groupPadding: groupPadding, // 0.2
                    pointPadding: pointPadding  // 0.1
                }
            },
            series: [response.series]
        };
        var chart = new Highcharts.Chart(options);
        if (response.isLegend === 0) {
            var legend = chart.legend;
            if (legend.display) {
                legend.group.hide();
                legend.display = false;
            }
        }

        return chart;
    }

    function chartBar(response, renderTo, metaDataId) {
<?php if (!isset($this->isLayout)) { ?>
//            $('#dashboard-container-' + metaDataId).width(response.width + 50);
//            $('#dashboard-container-' + metaDataId).height(response.height + 50);
<?php } ?>

        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }

        var title = '';
        if (response.isTitle === 1) {
            title = response.title;
            $('#card-title-' + metaDataId).show();
            $('#dashboard-title-' + metaDataId).html(title);
        } else {
            $('#card-title-' + metaDataId).hide();
        }

        var legend = false;
        if (response.isShowLabel === 1) {
            legend = true;
        }

        var isXLabel = false;
        if (response.isXLabel === 1) {
            isXLabel = true;
        }

        var isYLabel = false;
        if (response.isYLabel === 1) {
            isYLabel = true;
        }

        var tickLength = 0;
        var lineWidth = 0;
        var gridLineWidthX = 0;
        var gridLineWidthY = 0;
        if (response.isBackground === 1) {
            tickLength = 10;
            lineWidth = 1;
            gridLineWidthX = 0;
            gridLineWidthY = 1;
        } else {
            tickLength = 0;
            lineWidth = 0;
            gridLineWidthX = 0;
            gridLineWidthY = 0;
        }

        var options = {
            chart: {
                type: 'bar',
                renderTo: renderTo,
                width: response.width,
                height: response.height
            },
            title: false,
            exporting: {
                enabled: isExport
            },
            legend: legend,
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
                            enabled: isXLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthX,
                tickLength: tickLength
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                labels:
                        {
                            enabled: isYLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthY,
                tickLength: tickLength
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
        if (response.isLegend === 0) {
            var legend = chart.legend;
            if (legend.display) {
                legend.group.hide();
                legend.display = false;
            }
        }

        return chart;
    }

    function chartPie(response, renderTo, metaDataId) {
<?php if (!isset($this->isLayout)) { ?>
//            $('#dashboard-container-' + metaDataId).width(response.width + 85);
//            $('#dashboard-container-' + metaDataId).height(response.height + 85);
<?php } ?>

        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }

        var title = '';
        if (response.isTitle === 1) {
            title = response.title;
            $('#card-title-' + metaDataId).show();
            $('#dashboard-title-' + metaDataId).html(title);
        } else {
            $('#card-title-' + metaDataId).hide();
        }

        var legend = false;
        if (response.isShowLabel === 1) {
            legend = true;
        }

        var dataLabel = false;
        if (response.dataLabel === 1) {
            dataLabel = true;
        }

        if (response.isBackground !== 1) {
            $('#card-title-' + metaDataId).remove();
        }

        var tickLength = 0;
        var lineWidth = 0;
        var gridLineWidthX = 0;
        var gridLineWidthY = 0;
        if (response.isBackground === 1) {
            tickLength = 10;
            lineWidth = 1;
            gridLineWidthX = 0;
            gridLineWidthY = 1;
        } else {
            tickLength = 0;
            lineWidth = 0;
            gridLineWidthX = 0;
            gridLineWidthY = 0;
        }

        var tmpWidth = response.width;

//        var options = {
//            chart: {
//                plotBackgroundColor: null,
//                plotBorderWidth: null,
//                plotShadow: false,
//                type: 'pie',
//                renderTo: renderTo,
//                width: tmpWidth,
//                height: response.height
//            },
//            colors: response.colorList ? response.colorList : ['#7cb5ec', '#434348', '#90ed7d',
//                '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'],
//            credits: {
//                enabled: false
//            },
//            title: false,
//            exporting: {
//                enabled: isExport
//            },
//            exporting: {
//                enabled: isExport
//            },
//            tooltip: {
////                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//            },
//            plotOptions: {
//                pie: {
//                    allowPointSelect: true,
//                    cursor: 'pointer',
//                    dataLabels: {
//                        enabled: dataLabel,
//                        format: '<b>{point.percentage:.1f}%</b>',
//                    },
//                    showInLegend: true
//                }
//            },
//            series: [{
//                    name: response.categories ? response.categories : 'Total',
//                    colorByPoint: true,
//                    data: response.series
//                }]
//        };

        var options = {colors: ['#5faee3', '#4e5859', '#cccccc', '#b2b2b2'],
            chart: {
                backgroundColor: 'transparent',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                renderTo: renderTo,
                width: tmpWidth,
                height: response.height
            },
            title: {
                text: null,
                align: 'left',
                style: {
                    fontSize: '14px',
                    fontFamily: 'Arial, sans-serif'
                }
            },
            tooltip: {
                pointFormat: ''
            },
            exporting: {
                enabled: isExport
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: dataLabel,
                        // format: '<b>{point.percentage:.1f} %</b>: <br> {point.name}',
                        style: {
                            color: '#4e5859',
                            fontSize: '12px',
                            fontFamily: 'Arial, sans-serif'
                        },
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%',
//                        formatter: function () {
//                            return '<div class="legend-text"><b style="font-size: 17px; font-weight: bold;">' + this.y + '</b> <br> ' + this.key + '</div>';
//                        }

                    },
                    borderWidth: 0
                }
            },
            series: [{
                    animation: true,
                    type: 'pie',
                    name: response.categories ? response.categories : 'Total',
                    size: '80%',
                    innerSize: '56%',
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

    function chartLine(response, renderTo, metaDataId) {
<?php if (!isset($this->isLayout)) { ?>
//            $('#dashboard-container-' + metaDataId).width();
//            $('#dashboard-container-' + metaDataId).height();
<?php } ?>
        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }

        var title = '';
        if (response.isTitle === 1) {
            title = response.title;
            $('#card-title-' + metaDataId).show();
            $('#dashboard-title-' + metaDataId).html(title);
        } else {
            $('#card-title-' + metaDataId).hide();
        }

        var legend = false;
        if (response.isShowLabel === 1) {
            legend = true;
        }

        var isXLabel = false;
        if (response.isXLabel === 1) {
            isXLabel = true;
        }

        var isYLabel = false;
        if (response.isYLabel === 1) {
            isYLabel = true;
        }

        var tickLength = 0;
        var lineWidth = 0;
        var gridLineWidthX = 0;
        var gridLineWidthY = 0;
        if (response.isBackground === 1) {
            tickLength = 10;
            lineWidth = 1;
            gridLineWidthX = 0;
            gridLineWidthY = 1;
        } else {
            tickLength = 0;
            lineWidth = 0;
            gridLineWidthX = 0;
            gridLineWidthY = 0;
        }

        var options = {
            chart: {
                type: 'line',
                renderTo: renderTo,
                width: response.width,
                height: response.height
            },
            title: false,
            exporting: {
                enabled: isExport
            },
            legend: legend,
            xAxis: {
                categories: response.categories,
                labels:
                        {
                            enabled: isXLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthX,
                tickLength: tickLength
            },
            yAxis: {
                title: {
                    text: ''
                },
                labels:
                        {
                            enabled: isYLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthY,
                tickLength: tickLength
            },
            tooltip: {
                crosshairs: true
//                backgroundColor: '#FCFFC5',
//                borderColor: 'black',
//                borderRadius: 10,
//                borderWidth: 3
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: response.dataLabel
                    }
                }
            },
            series: response.series
        };
        var chart = new Highcharts.Chart(options);
    }

    function chartAreaWithNull(response, renderTo, metaDataId) {
<?php if (!isset($this->isLayout)) { ?>
//            $('#dashboard-container-' + metaDataId).width(response.width + 50);
//            $('#dashboard-container-' + metaDataId).height(response.height + 50);
<?php } ?>

        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }

        var title = '';
        if (response.isTitle === 1) {
            title = response.title;
            $('#card-title-' + metaDataId).show();
            $('#dashboard-title-' + metaDataId).html(title);
        } else {
            $('#card-title-' + metaDataId).hide();
        }

        var legend = false;
        if (response.isShowLabel === 1) {
            legend = true;
        }

        var isXLabel = false;
        if (response.isXLabel === 1) {
            isXLabel = true;
        }

        var isYLabel = false;
        if (response.isYLabel === 1) {
            isYLabel = true;
        }

        var tickLength = 0;
        var lineWidth = 0;
        var gridLineWidthX = 0;
        var gridLineWidthY = 0;
        if (response.isBackground === 1) {
            tickLength = 10;
            lineWidth = 1;
            gridLineWidthX = 0;
            gridLineWidthY = 1;
        } else {
            tickLength = 0;
            lineWidth = 0;
            gridLineWidthX = 0;
            gridLineWidthY = 0;
        }

        var options = {
            chart: {
                type: 'area',
                spacingBottom: 30,
                renderTo: renderTo,
                width: response.width,
                height: response.height
            },
            title: false,
            exporting: {
                enabled: isExport
            },
            legend: false,
//        subtitle: {
//            text: '* Jane\'s banana consumption is unknown',
//            floating: true,
//            align: 'right',
//            verticalAlign: 'bottom',
//            y: 15
//        },
//        legend: {
//            layout: 'vertical',
//            align: 'left',
//            verticalAlign: 'top',
//            x: 150,
//            y: 100,
//            floating: true,
//            borderWidth: 1,
//            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
//        },
            xAxis: {
                categories: response.categories,
                labels:
                        {
                            enabled: isXLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthX,
                tickLength: tickLength
            },
            yAxis: {
                title: {
                    text: ''
                },
                labels:
                        {
                            enabled: isYLabel
                        },
                lineWidth: lineWidth,
                gridLineWidth: gridLineWidthY,
                tickLength: tickLength
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y;
                }
            },
            plotOptions: {
                area: {
                    fillOpacity: 0.5
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                    data: response.series
                }]
        };
        var chart = new Highcharts.Chart(options);
    }

    function chartStockSingleLine(response, renderTo, metaDataId) {
<?php if (!isset($this->isLayout)) { ?>
//            $('#dashboard-container-' + metaDataId).width(response.width + 50);
//            $('#dashboard-container-' + metaDataId).height(response.height + 50);
<?php } ?>

        $('#' + renderTo).highcharts('StockChart', {
            rangeSelector: {
                selected: 1
            },
            title: {
                text: response.title
            },
            series: [{
                    name: 'AAPL',
                    data: response.data,
                    tooltip: {
                        valueDecimals: 2
                    }
                }]
        });
//        var options = {
//          rangeSelector : {
//                selected : 1
//            },
//
//            title : {
//                text : 'AAPL Stock Price'
//            },
//
//            series : [{
//                name : 'AAPL',
//                data : response.data,
//                tooltip: {
//                    valueDecimals: 2
//                }
//            }]
//        };
//        var chart = new Highstock.Chart(options);
    }

    function dualAxes(response, renderTo, metaDataId) {
<?php if (!isset($this->isLayout)) { ?>
//            $('#dashboard-container-' + metaDataId).width(response.width + 50);
//            $('#dashboard-container-' + metaDataId).height(response.height + 50);
<?php } ?>
        var isExport = false;
        if (response.isExport === 1) {
            isExport = true;
        }

        var title = '';
        if (response.isTitle === 1) {
            title = response.title;
            $('#card-title-' + metaDataId).show();
            $('#dashboard-title-' + metaDataId).html(title);
        } else {
            $('#card-title-' + metaDataId).hide();
        }

        var legend = false;
        if (response.isShowLabel === 1) {
            legend = true;
        }

        var isXLabel = false;
        if (response.isXLabel === 1) {
            isXLabel = true;
        }

        var isYLabel = false;
        if (response.isYLabel === 1) {
            isYLabel = true;
        }

        var tickLength = 0;
        var lineWidth = 0;
        var gridLineWidthX = 0;
        var gridLineWidthY = 0;
        if (response.isBackground === 1) {
            tickLength = 10;
            lineWidth = 1;
            gridLineWidthX = 0;
            gridLineWidthY = 1;
        } else {
            tickLength = 0;
            lineWidth = 0;
            gridLineWidthX = 0;
            gridLineWidthY = 0;
        }

        var groupPadding = 0;
        var pointPadding = 1;
        if (response.isLittle === 1) {
            groupPadding = 0;
            pointPadding = 0.02;
        } else {
            groupPadding = 0.2;
            pointPadding = 0.1;
        }

        var options = {
            chart: {
                zoomType: 'xy',
                renderTo: renderTo,
                width: response.width,
                height: response.height
            },
            title: {
                text: false
            },
            exporting: {
                enabled: isExport
            },
            subtitle: {
                text: false
            },
            xAxis: [{
                    categories: response.categories,
                    crosshair: true,
                    labels: {
                        enabled: isXLabel
                    },
                    lineWidth: lineWidth,
                    gridLineWidth: gridLineWidthX,
                    tickLength: tickLength
                }],
            yAxis: [{// Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        },
                        enabled: isYLabel
                    },
                    title: {
                        text: response.series[1]['name'],
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        },
                        enabled: isYLabel
                    },
                    lineWidth: lineWidth,
                    gridLineWidth: gridLineWidthY,
                    tickLength: tickLength
                }, {// Secondary yAxis
                    title: {
                        text: response.series[0]['name'],
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        },
                        enabled: isYLabel
                    },
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        },
                        enabled: isYLabel
                    },
                    opposite: true,
                    lineWidth: lineWidth,
                    gridLineWidth: gridLineWidthY,
                    tickLength: tickLength
                }],
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: '',
                    groupPadding: groupPadding, // 0.2
                    pointPadding: pointPadding  // 0.1
                }
            },
            legend: {
                layout: 'vertical',
                enabled: legend,
                align: 'right',
                x: 0,
                verticalAlign: 'top',
                y: 0,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            series: response.series
        };
        var chart = new Highcharts.Chart(options);
    }
    
    $("#search-btn-<?php echo $this->metaDataId; ?>").on("click", function () {
        var $dialogName = 'dialog-search-dashboard-<?php echo $this->metaDataId;  ?>-divid';
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $("#" + $dialogName).empty().html($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').html());
        $("#" + $dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: '<?php echo $this->diagram['TITLE']; ?> хайлт',
            width: 'auto',
            height: 'auto',
            modal: true,
            close: function() {
              $("#" + $dialogName).empty().dialog('destroy').remove();
            },
            buttons: [
              { html: '<i class="fa fa-search"></i> Хайх', class:'btn btn-sm blue-madison', click: function() {
                    drawChart($("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
                }
              },
              { html: 'Хаах', class: 'btn btn-sm default', click: function() {
                  $("#" + $dialogName).empty().dialog('destroy').remove();
                }
              }
            ]
        }).dialogExtend({
              "closable": true,
              "maximizable": true,
              "minimizable": true,
              "collapsable": true,
              "dblclick": "maximize",
              "minimizeLocation": "left",
              "icons": {
                  "close": "ui-icon-circle-close",
                  "maximize": "ui-icon-extlink",
                  "minimize": "ui-icon-minus",
                  "collapse": "ui-icon-triangle-1-s",
                  "restore": "ui-icon-newwin"
              }
          });
        $("#" + $dialogName).dialog('open');
        Core.initAjax();
    });
</script>


