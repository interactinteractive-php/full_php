<?php
if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#app_tab_mdassetsales_dashboard" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
        </div>
        <div class="card-body pt0">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_mdassetsales_dashboard">
<?php
}
?>  


<div class="content sales_dashboard p-0">
    <div class="container-fluid">
        <!-- <div class="d-sm-flex justify-content-between">
            <div>
                <h4><?php //echo  $this->lang->line('sales_dashboard_chart_'); ?></h4>
            </div>
        </div> -->

        <div class="row">
            <?php
            $color1 = array(
                0 => 'gradient-deepblue', 
                1 => 'gradient-ibiza', 
                2 => 'gradient-scooter', 
                3 => 'gradient-ohhappiness',
                4 => 'gradient-naviblue', 
                5 => 'gradient-blue2', 
                6 => 'gradient-deepblue', 
                7 => 'gradient-ibiza'
            );
            $i = 0;
            foreach ($this->topCards as $k => $data1) {
                if (++$i == 7) break;
            ?>
            <div class="col">
                <div class="box-shadow  card <?php echo $color1[$k]; ?>">
                    <div class="d-flex align-items-center text-white">
                        <div>
                            <div class="text-uppercase font-weight-bold"><?php echo $data1['title']; ?></div>
                            <span style="font-size: 24px;" class="bigdecimalInit" data-mdec='0'><?php echo $data1['qty']; ?></span>
                        </div>
                    </div>
                    <!-- <div id="new-visitors-<?php echo $k; ?>"></div> -->
                </div>
            </div>
            <?php
            }
            ?>
           
            <div class="col-lg-8 col-xl-9">
                <div class="box-shadow">
                    <div class="card-header">
                        <div class="d-sm-flex justify-content-between">
                            <div class="mb-0 mg-sm-b-0">
                                <h4><?php echo $this->lang->line('sales_dashboard_chart1'); ?></h4>
                            </div>
                        </div>
                        <!-- <div class="d-sm-flex justify-content-between">
                            <p class="text-muted">2020/03/01 - 2020/03/31</p>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="chartdiv" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xl-3">
                <div class="box-shadow">
                    <div class="card-header">
                        <h4><?php echo $this->lang->line('sales_dashboard_chart2'); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="chartdiv_pie" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6><?php echo $this->lang->line('sales_dashboard_chart3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="transArchiv" class="chart-box">
                            <ul  class="list-group border-0">
                            <?php
                                if ($this->trasGroup) {
                                    foreach ($this->trasGroup as $key => $grouped) { ?>
                                        <div class="theader p30">
                                            <?php echo $key; ?>
                                        </div>
                                        <ul class="list-group-child border-0">
                                            <?php foreach ($grouped as $item) {  ?>
                                                <li class="list-group-item"> <?php echo $item['name']; ?> <span class="badge cloud bigdecimalInit ml-auto"  data-mdec='0'><?php echo $item['val']; ?></span></li>
                                            <?php 
                                            } ?>
                                        </ul>
                                    <?php
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('sales_dashboard_chart4'); ?></h6>
                    </div>
                    <div class="card-body mt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="chart-container">
                                    <div class="chart" id="chartCloudy" style="height:455px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="lh-5"><?php echo $this->lang->line('sales_dashboard_chart6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div id="map-mn-markers" style="height: 420px"></div>
                            </div>
                            <div class="col-4">
                                <?php
                                foreach ($this->district as $d) {
                                ?>
                                <div class="mb10">
                                    <div class="mb5">
                                        <span class="text-gray"><?php echo $d['name']; ?></span>
                                        <span class="float-right text-gray"><?php echo $d['value']; ?></span>
                                    </div>
                                    <div class="progress mb-1" style="height: 0.375rem;">
                                        <div class="progress-bar bg-danger" style="width: <?php echo $d['percent']; ?>%">
                                            <span class="sr-only"><?php echo $d['percent']; ?>% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>

<?php
if (!$this->isAjax) {
?>                    
                </div>
            </div>
        </div>
    </div>    
</div>
<?php
}
?> 
<style>
    

    .sales_dashboard {
    padding-top: 0px !important;
    }
    /**************************************** DANGER ******************************************/
    .sales_dashboard .box-shadow {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        box-shadow: 0 0 30px 0 rgba(115, 77, 191, 0.2);
        border-radius: 7px;
        border: 0;
        margin-bottom: 1.5rem;
        width: 100%;
        padding: 20px;
    }
    .sales_dashboard .box-shadow.card .bigdecimalInit{
        text-align: right;
        display: inline-block;
        line-height: 40px;padding-bottom: 5px;
    }
    .sales_dashboard .box-shadow.card {
        padding: 15px 0 0px 15px;
    }
    .sales_dashboard table th {
        border-bottom: 1px solid #e0e0e0;
    }
    .sales_dashboard table th,
    .sales_dashboard table td {
        border-top: .07rem dashed #e5e5e5;
        line-height: normal;
    }
    .sales_dashboard .btn-icon-small {
        padding: 12px !important;
    }
    .sales_dashboard .btn-icon-medium {
        padding: 14px !important;
    }
    .sales_dashboard .amcharts-chart-div > a {
        display: none !important;
    }
    .sales_dashboard .amcharts-legend-div text {
        
    }
    /****************************************** VeriCloud START ******************************************/
    .vericloud {
        width: 100%;
    }
    .vericloud .sidebar {
        margin-left: 300px;
    }
    .vericloud .user_propic {
        border-radius: 8px;
    }
    /******************************************* VeriCloud END *******************************************/

    .gradient-deepblue{
        background: #ff7e79 !important;
    }
    .gradient-ibiza {
        background: #ff8e50 !important;
    }
    .gradient-scooter {
        background: #fec345 !important;
    }
    .gradient-ohhappiness {
        background: #39e0cf !important;
    }
    .gradient-naviblue {
        background: #48c7f4 !important;
    }
    .gradient-blue2 {
        background: #6373ed !important;
    }


    #transArchiv .list-group-child {
        padding: 0;
    }
    #transArchiv  .badge.cloud{
        color: #000;
        font-weight: 100px;
    }

    #transArchiv .bg-green .badge{
        background: #690fcb;
        font-size: 12px;
        padding: 3px 8px;
    }
    #transArchiv .bg-green {
        background: #32ca59;
        color: #000;
    }
    .progress-bar.bg-danger {
        background: #ff8e50 !important;
    }
    #transArchiv ul {
        margin: 8px;
        padding: 17px;
    }
    #transArchiv ul li {
        padding: 4px 15px;
    }
    #transArchiv ul .theader {
    border-bottom: 1px solid rgba(255,255,255,.1);
    text-transform: uppercase;
    padding-bottom: 10px;
    font-weight: 600;
    }
    /****************************************** VeriCloud START ******************************************/
    .vericloud {
        width: 100%;
    }
    .vericloud .sidebar {
        margin-left: 300px;
    }
    .vericloud .user_propic {
        border-radius: 8px;
    }
    /******************************************* VeriCloud END *******************************************/
</style>



<!-- <script type="text/javascript" src="assets/core/js/plugins/visualization/d3/d3.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/visualization/echarts/echarts.min.js"></script> -->
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/amcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/serial.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/pie.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/ammap_amcharts_extension.v1.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/mongoliaLow.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/core.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/charts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/themes/animated.js"></script>

<!-- amCharts javascript code -->
<script type="text/javascript">

    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chartCloudy = am4core.create("chartCloudy", am4charts.XYChart);

        // Add data
        chartCloudy.logo.height = -120;
        chartCloudy.data = <?php echo json_encode($this->capacity); ?>;

        // Create axes
        var dateAxis = chartCloudy.xAxes.push(new am4charts.DateAxis());
            dateAxis.renderer.grid.template.location = 0;
            dateAxis.renderer.minGridDistance = 50;

        var valueAxis = chartCloudy.yAxes.push(new am4charts.ValueAxis());

        // Create series
        var series = chartCloudy.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "value";
            series.dataFields.dateX = "date";
            series.strokeWidth = 3;
            series.fillOpacity = 0.5;

        // Add vertical scrollbar
        chartCloudy.scrollbarY = new am4core.Scrollbar();
        chartCloudy.scrollbarY.marginLeft = 0;

        // Add cursor
        chartCloudy.cursor = new am4charts.XYCursor();
        chartCloudy.cursor.behavior = "zoomY";
        chartCloudy.cursor.lineX.disabled = true;

    }); // end am4core.ready()

    /* AMCHART 4 */
    am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv_pie", am4charts.PieChart);
            chart.innerRadius = am4core.percent(40);
            chart.logo.height = -60;

            chart.data = <?php echo json_encode($this->gender); ?>;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "qty";
        pieSeries.dataFields.category = "name";

        var colors = ["#fec345","#ff7e79","#6373ed","#6373ed","#303F9F","#00FF00"];
        var colorset = new am4core.ColorSet();
        colorset.list = [];
        for(var i=0;i<colors.length;i++)
            colorset.list.push(new am4core.color(colors[i]));
            pieSeries.colors = colorset;
            pieSeries.colors = colorset;

        pieSeries.ticks.template.disabled = true;
        pieSeries.alignLabels = false;
        pieSeries.labels.template.text = "{qty}";
        pieSeries.labels.template.radius = am4core.percent(-40);
        pieSeries.labels.template.fill = am4core.color("white");
        pieSeries.labels.template.relativeRotation = 90;
    
    chart.legend = new am4charts.Legend();

    var mbchart1 = am4core.create("chartdiv", am4charts.XYChart);
        mbchart1.data = <?php echo json_encode($this->locationCapacity); ?>;
        var categoryAxis = mbchart1.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            // categoryAxis.renderer.grid.template.disabled = true;
            // categoryAxis.renderer.labels.template.disabled = true;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 2;

        var valueAxis = mbchart1.yAxes.push(new am4charts.ValueAxis());

        var series1 = mbchart1.series.push(new am4charts.ColumnSeries());
            series1.dataFields.valueY = "qty";
            series1.dataFields.categoryX = "name";
            series1.tooltip.label.textAlign = "top";
            series1.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
            // series1.columns.template.showTooltipOn = "always";
            series1.columns.template.strokeWidth = 0;

        mbchart1.logo.height = -120;
        mbchart1.colors.list = [
            am4core.color("#1DE4BD"),
            am4core.color("#1AC9E6"),
            am4core.color("#1AC9E6"),
            am4core.color("#19AADE"),
            am4core.color("#176BAD")
            ];
        series1.columns.template.events.once("inited", function(event){
            event.target.fill = mbchart1.colors.getIndex(event.target.dataItem.index);
        });
        
        var valueLabel = series1.bullets.push(new am4charts.LabelBullet());
            valueLabel.label.text = "{qty.formatNumber('0')}"
            valueLabel.label.fontSize = 11;
            valueLabel.label.dy = -10;
            // mbchart1.legend.markers.template.useDefaultMarker = true;

        var legend1 = new am4charts.Legend();
                legend1.parent = mbchart1.chartContainer;
                legend1.itemContainers.template.togglable = false;
                legend1.itemContainers.template.paddingTop = 2;
                legend1.itemContainers.template.paddingBottom = 2;
                legend1.marginTop = 5;

                series1.events.on("ready", function(ev) {
                var legenddata = [];
                series1.columns.each(function(column) {
                    legenddata.push({
                    name: column.dataItem.categoryX,
                    fill: column.fill
                    });
                });
               // legend1.data = legenddata;
    });
 
    AmCharts.makeChart("chartdivF", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 45,
            "gridThickness": 0,
            "autoWrap": true  
        },
        // "colors": [
        //     "#7ebcff",
        //     "#f77eb9"
        // ],
        'legend': {
            "spacing": 0,
            "position": 'top',
            "align": 'right',
            'markerType': 'circle',
            'periodValueText': 'Нийт: [[value.sum]]',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 80
        },
        "graphs": [{
                "balloonText": "[[category]] - [[name]] / [[value]]",
                "bulletHitAreaSize": -6,
                "fillAlphas": 1,
                "gapPeriod": 10,
                "id": "AmGraph-1",
                "lineAlpha": 0.68,
                "lineThickness": 4,
                "minDistance": 78,
                "negativeFillAlphas": 0.54,
                "negativeLineColor": "#762E2E",
                "labelPosition": "top",
                "labelText": " [[qty]]",
                "precision": -3,
                "title": "Эмчийн тоо",
                "type": "column",
                "valueField": "qty"
            },
            // {
            //     "balloonText": "[[category]] - [[name]] /Багтаамж/ [[value]]",
            //     "fillAlphas": 1,
            //     "id": "AmGraph-2",
            //     "title": "Багтаамж",
            //     "type": "column",
            //     "valueField": "capacityqty"
            // }
        ],
        "dataProvider": <?php echo json_encode($this->locationCapacity); ?>
    });

    AmCharts.makeChart("chartdivS",
        {
            "type": "serial",
            "categoryField": "name",
            "startDuration": 1,
            "theme": "default",
            "categoryAxis": {
                "gridPosition": "start"
            },
            // "colors": [
            //     "#7ebcff", 
            //     "#f77eb9"
            // ],
            'legend': {
                "spacing": 0,
                "position": 'top',
                "align": 'right',
                'markerType': 'circle',
                'periodValueText': 'Нийт: [[value.sum]]',
                'labelText': '[[title]] ',
                'valueText': '[[value]]',
                'valueWidth': 80
            },
            "graphs": [
                {
                    "balloonText": "[[name]] / [[value]]",
                    "bulletHitAreaSize": -6,
                    "fillAlphas": 1,
                    "gapPeriod": 10,
                    "id": "AmGraph-1",
                    "lineAlpha": 0.68,
                    "lineThickness": 4,
                    "minDistance": 78,
                    "negativeFillAlphas": 0.54,
                    "negativeLineColor": "#762E2E",
                    "labelPosition": "top",
                    "labelText": "[[value]]",
                    "precision": -3,
                    "title": "Борлуулалт",
                    "type": "column",
                    "valueField": "qty"
                },
                // {
                //     "balloonText": "[[name]]  [[value]]",
                //     "fillAlphas": 1,
                //     "id": "AmGraph-2",
                //     "title": "",
                //     "type": "column",
                //     "valueField": "capacityqty"
                // }
            ],
            "dataProvider": <?php echo json_encode($this->locationCapacity); ?>
        }
    );
   
    AmCharts.makeChart("chartdiv_pieOld",
        {
            "type": "pie",
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "innerRadius": 50,
            "colors": [
                "#7ebcff", 
                "#f77eb9"
            ],
            "labelsEnabled": false,
            "labelColorField": "#FF5F5F",
            "labelTickAlpha": 0,
            "outlineAlpha": 1,
            "outlineThickness": 2,
            "titleField": "name",
            "valueField": "qty",
            "allLabels": [],
            "balloon": {},
            "legend": {
                "enabled": true,
                "useGraphSettings": false,
                "align": "center",
                "markerType": "circle"
            },
            "titles": [],
            "dataProvider": <?php echo json_encode($this->gender); ?>
        }
    );

    AmCharts.makeChart("chartdiv_pie2",
        {
            "type": "pie",
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "innerRadius": 50,
            "colors": [
                "#f77eb9",
                "#7ebcff",
                "#f2b8ff",
                "#fec85e",
                "#4cebb5",
                "#a5d7fd",
                "#b2bece",
                "#a4e063"
            ],
            "labelsEnabled": false,
            "labelColorField": "#FF5F5F",
            "labelTickAlpha": 0,
            "outlineAlpha": 1,
            "outlineThickness": 2,
            "titleField": "name",
            "valueField": "qty",
            "allLabels": [],
            "balloon": {},
            "legend": {
                "enabled": true,
                "useGraphSettings": false,
                "align": "center",
                "markerType": "circle"
            },
            "titles": [],
            "dataProvider": <?php echo json_encode($this->nationality); ?>
        }
    );

    AmCharts.makeChart("chartdiv_pie3",
        {
            "type": "pie",
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "innerRadius": 50,
            "colors": [
                "#f77eb9",
                "#7ebcff",
                "#f2b8ff",
                "#fec85e",
                "#4cebb5",
                "#a5d7fd",
                "#b2bece",
                "#a4e063"
            ],
            "labelColorField": "#FF5F5F",
            "labelTickAlpha": 0,
            "outlineAlpha": 1,
            "outlineThickness": 2,
            "labelsEnabled": false,
            "titleField": "name",
            "valueField": "qty",
            "allLabels": [],
            "balloon": {},
            "legend": {
                "enabled": true,
                "useGraphSettings": false,
                "align": "center",
                "markerType": "circle"
            },
            "titles": [],
            "dataProvider": <?php echo json_encode($this->infectionSources); ?>
        }
    );

    AmCharts.makeChart("gender_over_time",
        {
            "type": "serial",
            "categoryField": "category",
            "startDuration": 1,
            "categoryAxis": {
                "gridPosition": "start"
            },
            "trendLines": [],
            "graphs": [
                {
                    "balloonText": "[[title]] of [[category]]:[[value]]",
                    "fillAlphas": 0.7,
                    "id": "AmGraph-1",
                    "lineAlpha": 0,
                    "title": "graph 1",
                    "valueField": "column-1"
                },
                {
                    "balloonText": "[[title]] of [[category]]:[[value]]",
                    "fillAlphas": 0.7,
                    "id": "AmGraph-2",
                    "lineAlpha": 0,
                    "title": "graph 2",
                    "valueField": "column-2"
                }
            ],
            "guides": [],
            // "valueAxes": [
            // 	{
            // 		"id": "ValueAxis-1",
            // 		"title": "Axis title"
            // 	}
            // ],
            "allLabels": [],
            "balloon": {},
            "legend": {
                "enabled": false
            },
            // "titles": [
            // 	{
            // 		"id": "Title-1",
            // 		"size": 15,
            // 		"text": "Chart Title"
            // 	}
            // ],
            "dataProvider": [
                {
                    "category": "category 1",
                    "column-1": 8,
                    "column-2": 5
                },
                {
                    "category": "category 2",
                    "column-1": 6,
                    "column-2": 7
                },
                {
                    "category": "category 3",
                    "column-1": 2,
                    "column-2": 3
                },
                {
                    "category": "category 4",
                    "column-1": 1,
                    "column-2": 3
                },
                {
                    "category": "category 5",
                    "column-1": 2,
                    "column-2": 1
                },
                {
                    "category": "category 6",
                    "column-1": 3,
                    "column-2": 2
                },
                {
                    "category": "category 7",
                    "column-1": 6,
                    "column-2": 8
                },
                {
                    "category": "category 8",
                    "column-1": "9",
                    "column-2": "5"
                },
                {
                    "category": "category 9",
                    "column-1": "8",
                    "column-2": "9"
                },
                {
                    "category": "category 10",
                    "column-1": "5",
                    "column-2": "10"
                },
                {
                    "category": "category 11",
                    "column-1": "3",
                    "column-2": "16"
                },
                {
                    "category": "category 12",
                    "column-1": "9",
                    "column-2": "10"
                },
                {
                    "category": "category 13",
                    "column-1": "12",
                    "column-2": "9"
                },
                {
                    "category": "category 14",
                    "column-1": "10",
                    "column-2": "5"
                },
                {
                    "category": "category 15",
                    "column-1": "10",
                    "column-2": "1"
                }
            ]
        }
    );

    var chart = AmCharts.makeChart("tornado_negative_stack", {
        "type": "serial",
        "theme": "light",
        "colors": [ 
                "#f77eb9", 
                "#7ebcff"
        ],
        "rotate": true,
        "marginBottom": 50,
        "dataProvider": <?php echo json_encode($this->ageGenderDistrubition); ?>,
        "startDuration": 1,
        "graphs": [{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "malecount",
            "title": "Эрэгтэй",
            "labelText": "[[value]]",
            "clustered": false,
            "labelFunction": function(item) {
            return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
            return item.category + " нас: " + Math.abs(item.values.value);
            }
        }, {
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "femalecount",
            "title": "Эмэгтэй",
            "labelText": "[[value]]",
            "clustered": false,
            "labelFunction": function(item) {
            return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
            return item.category + ": " + Math.abs(item.values.value);
            }
        }],
        "categoryField": "age",
        "categoryAxis": {
            "gridPosition": "start",
            "gridAlpha": 0.2,
            "axisAlpha": 0
        },
        "valueAxes": [{
            "gridAlpha": 0,
            "ignoreAxisWidth": true,
            "labelFunction": function(value) {
            return Math.abs(value);
            },
            "guides": [{
            "value": 0,
            "lineAlpha": 0.2
            }]
        }],
        "balloon": {
            "fixedPosition": true
        },
        "chartCursor": {
            "valueBalloonsEnabled": false,
            "cursorAlpha": 0.05,
            "fullWidth": true
        },
        "allLabels": [{
            "text": "Эрэгтэй",
            "x": "28%",
            "y": "97%",
            "bold": true,
            "align": "middle"
        }, {
            "text": "Эмэгтэй",
            "x": "75%",
            "y": "97%",
            "bold": true,
            "align": "middle"
        }]

    });
    console.log(<?php echo json_encode($this->map); ?>);
    var map = AmCharts.makeChart("map-mn-markers", {

        "type": "map",
        "theme": "none",
        "dataProvider": {
            "map": "mongoliaLow",
            "getAreasFromMap": true,
                images: [{
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "7", //Улаанбаатар
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 55.916668,
                    longitude: 134.9166645,
                    labelFontSize: 15,
                    type: 'circle',
                    alpha: 0.5,
                    height: 200,
                    width: 200,
                    color: '#f10075',
                    title: 'Улаанбаатар - 7'
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Хөвсгөл",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 63.633331,
                    longitude: 112.150002
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "9",
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 61.466667,
                    longitude: 131.949997,
                    labelFontSize: 14,
                    type: 'circle',
                    alpha: 0.5,
                    height: 250,
                    width: 250,
                    color: '#f10075',
                    title: 'Дархан - 9'
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Сэлэнгэ",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 63.166667,
                    longitude: 129.149997
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Эрдэнэт",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 60.846666,
                    longitude: 126.033333
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Увс",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 61.983334,
                    longitude: 90.066666
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "5",
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 62.166667,
                    longitude: 77.166667,
                    labelFontSize: 14,
                    type: 'circle',
                    alpha: 0.5,
                    height: 150,
                    width: 150,
                    color: '#f10075',
                    title: 'Баян-Өлгий - 5'
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Ховд",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 53.000000,
                    longitude: 85.633331
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Говь-Алтай",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 46.366669,
                    longitude: 96.250000
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "3",
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 56.733334,
                    longitude: 100.833336,
                    labelFontSize: 14,
                    type: 'circle',
                    alpha: 0.5,
                    height: 90,
                    width: 90,
                    color: '#f10075',
                    title: 'Завхан - 3'
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Дундговь",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 44.750000,
                    longitude: 132.250000
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Говьсүмбэр",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 49.350000,
                    longitude: 140.250000
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "1",
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 45.183334,
                    longitude: 110.716667,
                    labelFontSize: 12,
                    type: 'circle',
                    alpha: 0.5,
                    height: 40,
                    width: 40,
                    color: '#f10075',
                    title: 'Баянхонгор - 1'
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "1",
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 55.466667,
                    longitude: 115.449997,
                    labelFontSize: 12,
                    type: 'circle',
                    alpha: 0.5,
                    height: 40,
                    width: 40,
                    color: '#f10075',
                    title: 'Архангай - 1'
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Булган",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 58.799999,
                    longitude: 122.533333
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Өвөрхангай",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 48.250000,
                    longitude: 120.766670
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Өмнөговь",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 32.566666,
                    longitude: 125.416664
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Төв",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 53.566666,
                    longitude: 132.416664
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "6",
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 40.566666,
                    longitude: 145.416664,
                    labelFontSize: 14,
                    type: 'circle',
                    alpha: 0.5,
                    height: 180,
                    width: 180,
                    color: '#f10075',
                    title: 'Дорноговь - 6'
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Сүхбаатар",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 50.566666,
                    longitude: 158.416664
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    labelFontSize: 10,
                    label: "Хэнтий ",
                    labelPosition: "bottom",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 56.316666,
                    longitude: 146.650002
                }, {
                    zoomLevel: 5,
                    scale: 0.5,
                    label: "1",
                    labelPosition: "middle",
                    labelColor: "#fff",
                    labelRollOverColor: "#fff",
                    latitude: 57.066666,
                    longitude: 162.533333,
                    labelFontSize: 12,
                    type: 'circle',
                    alpha: 0.5,
                    height: 40,
                    width: 40,
                    color: '#f10075',
                    title: 'Дорнод - 1'
                }],
                "areas": <?php echo json_encode($this->map); ?>
            },
            "areasSettings": {
                "autoZoom": true,
                "selectedColor": "#39e0cf",
                "outlineThickness": 2,
                "rollOverOutlineColor": "#fff",
                "rollOverColor": "#39e0cf",
                "balloonText": "<b><span style='font-size:14px'>[[title]]</span></b><br><span style='font-size:14px; font-family: Arial'>[[customdata]]</span>",

            },
            "balloon": {
                "adjustBorderColor": true,
                "color": "#000",
                "cornerRadius": 0,
                "fillColor": "#fff",
                "label": 'customdata',
                "textAlign": "left",
                "fillAlpha": 5,
            },
            "zoomControl": {
                "zoomControlEnabled": false,
                "buttonFillColor": "#07509E",
                "buttonSize": 16
            },
            "legend": {
                "width": 130,
                "marginRight": 17,
                "marginLeft": 7,
                "equalWidths": false,
                "backgroundAlpha": 0,
                "backgroundColor": "#FFFFFF",
                "borderColor": "#ffffff",
                "borderAlpha": 0,
                "top": 0,
                "right": 0,
                "maxColumns": 1,
                "equalWidths": true,
                "horizontalGap": 10
            }
        });


        map.addListener("init", function() {
        var longitude = {
            "BI": 77.166667,
            "BR": 110.716667,
            "AR": 115.449997,
            "DO": 145.416664,
            "DD": 162.533333,
            "DA": 131.949997,
            "SE": 129.149997,
            "TB": 132.416664,
            "UB": 134.9166645,
            "ZA": 100.833336,
        };

        var latitude = {
            "BI": 62.166667,
            "BR": 45.183334,
            "AR": 55.466667,
            "DO": 40.566666,
            "DD": 57.066666,
            "DA": 61.466667,
            "SE": 63.166667,
            "TB": 53.566666,
            "UB": 55.916668,
            "ZA": 56.733334,
        };


        setTimeout(function() {
            map.dataProvider.images = [];
            var area = map.dataProvider.areas;

            for (x in map.dataProvider.areas) {
                var area = map.dataProvider.areas[x];
                var image = new AmCharts.MapImage();
                area.groupId = area.id;
                if (area.groupId == "ER"){
                    var fu = area.customdata * 35;
                }else{
                    var fu = area.customdata * 20;
                }
                var hvalue = area.value;
                var mm = hvalue*100/fu;
            // console.log(hvalue);

                if (area.groupId == "UB"){
                    image.labelColor = '#FFF';
                }
            
                if (area.groupId == "TB" || area.groupId == "SE") {
                    image.labelPosition = 'bottom';
                }
                
                image.latitude = latitude[area.id] || map.getAreaCenterLatitude(area);
                image.longitude = longitude[area.id] || map.getAreaCenterLongitude(area);
                image.title = area.title +'<br/>'+ area.value;
                // image.label = area.title ;
                image.label = area.title  ;
                image.zoomLevel = 6;
                image.alpha = 0.4;
                image.type = 'circle';
                image.color = '#f10075';
                image.height = mm ;
                image.width = mm;
                image.labelPosition = 'middle';
                image.labelFontSize = 9;
                // image.labelColor = '#000';
                image.linkToObject = area;
                image.groupId = area.id;
                map.dataProvider.images.push(image);
            }
            map.validateData();
            
        }, 100)
        });


</script> 