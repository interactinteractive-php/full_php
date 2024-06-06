
<div id="container">
    <div id="dashboard-body"> 
        
        <!-- BEGIN PORTLET-->
        <div class="card light bordered">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="card-title">
                    <h4 class="widget-h" id="dashboard-title">Daily Visitors</h4>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown">Шүүлт</a>
                        <ul class="dropdown-menu float-right">
                            <li>
                                <div class="span-12">asdasd
                                    <input type="text" class="form-control"/>
                                asdsd
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body" id="dashboard" style="width: <?php echo $this->diagram['WIDTH']; ?>px; height: <?php echo $this->diagram['HEIGHT']; ?>px;">
                <img src="" />
            </div>
        </div>
        <!-- END PORTLET-->
<!--        <div class="panel-body">
            <div class="top-stats-panel">
                <div class="daily-visit">
                    <h4 class="widget-h" id="dashboard-title">Daily Visitors</h4>
                    <div id="dashboard" style="width: <?php echo $this->diagram['WIDTH']; ?>px; height: <?php echo $this->diagram['HEIGHT']; ?>px;">

                    </div>
                    <ul class="chart-meta clearfix">
                        <li class="float-left visit-chart-value">3233</li>
                        <li class="float-right visit-chart-title"><i class="fa fa-arrow-up"></i> 15%</li>
                    </ul>
                </div>
            </div>
        </div>-->
    </div>
</div>
<style>
    ul {
        list-style-type: none;
    }
    .widget-h {
        color: #afaebc;
        font-size: 15px;
        text-transform: uppercase;
        margin: 0px 0px 10px 0px;
        text-align: center;
    }
    .visit-chart-value{
        font-size: 18px;
        color: #3acdc7;
        font-weight: 600;
        padding-top: 10px;
    }
    .visit-chart-title i {
        color: #3acdc7;
    }
    
    .visit-chart-title {
        font-size: 15px;
        padding-top: 10px;
        color: #ccc;
    }
</style>
<?php
?>
<script src="assets/custom/addon/plugins/jquery-flotchart/jquery.flot.js"></script>
<script src="assets/custom/addon/plugins/jquery-flotchart/jquery.flot.tooltip.min.js"></script>
<script src="assets/custom/addon/plugins/jquery-flotchart/jquery.flot.resize.js"></script>
<script src="assets/custom/addon/plugins/jquery-flotchart/jquery.flot.pie.resize.js"></script>
<script src="assets/custom/addon/plugins/jquery-flotchart/jquery.flot.animator.min.js"></script>
<script src="assets/custom/addon/plugins/jquery-flotchart/jquery.flot.growraf.js"></script>

<script type="text/javascript">
    var metaDataId = '<?php echo $this->metaDataId; ?>',
        chartType = '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>',
        title;
    
    (function ($) {
        "use strict";
        $(document).ready(function () {
            if ($.fn.plot) {
                drawChart(null);
            }
        });


    })(jQuery);
    
    function drawChart(defaultCriteriaData){
        if (chartType === 'line') {
            $.ajax({
                type: 'post',
                url: 'mddashboard/flotChartLineDiagram',
                dataType: 'json',
                data: {metaDataId: metaDataId},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (response) {
                    if (response.chartType === 'line') {
                        chartLine(response);
                        Core.unblockUI();
                    }
                }
            });
        } 
    }
    
    function chartLine(response) {
        
        $('#container').width(response.width + 60);
        $('#container').height(response.height + 120);
        
//        $('#dashboard-body').width(response.width);
//        $('#dashboard-body').height(response.height);
        
        if (response.isTitle === 1) {
            $('#dashboard-title').html(response.title);
        } else {
            $('#dashboard-title').html('');            
        }
        
        var d1 = [
            [0, 10],
            [1, 20],
            [2, 33],
            [3, 24],
            [4, 45],
            [5, 96],
            [6, 47],
            [7, 18],
            [8, 11],
            [9, 13],
            [10, 21]

        ];
        
        var data = ([{
            label: "Too",
            data: d1,
            lines: {
                show: true,
                fill: true,
                lineWidth: 2,
                fillColor: {
                    colors: ["rgba(255,255,255,.1)", "rgba(160,220,220,.8)"]
                }
            }
        }]);
        var options = {
            grid: {
                backgroundColor: {
                    colors: ["#fff", "#fff"]
                },                        
                borderWidth: 0,
                borderColor: "#f0f0f0",
                margin: 0,
                minBorderMargin: 0,
                labelMargin: 20,
                hoverable: true,
                clickable: true
            },
            // Tooltip
            tooltip: true,
            tooltipOpts: {
                content: "%s X: %x Y: %y",
                shifts: {
                    x: -60,
                    y: 25
                },
                defaultTheme: false
            },

            legend: {
                labelBoxBorderColor: "#ccc",
                show: false,
                noColumns: 0
            },
            series: {
                stack: true,
                shadowSize: 0,
                highlightColor: 'rgba(30,120,120,.5)'

            },
            xaxis: {
                tickLength: 0,
                tickDecimals: 0,
                show: true,
                min: 2,

                font: {

                    style: "normal",


                    color: "#666666"
                }
            },
            yaxis: {
                ticks: 3,
                tickDecimals: 0,
                show: true,
                tickColor: "#f0f0f0",
                font: {

                    style: "normal",


                    color: "#666666"
                }
            },
            //        lines: {
            //            show: true,
            //            fill: true
            //
            //        },
            points: {
                show: true,
                radius: 2,
                symbol: "circle"
            },
            colors: ["#87cfcb", "#48a9a7"]
        };

        var plot = $.plot($("#dashboard"), data, options);
    }
    
</script>


