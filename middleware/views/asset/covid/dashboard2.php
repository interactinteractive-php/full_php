<?php
if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#app_tab_mdassetcovid_dashboard" class="active" data-toggle="tab">
                        <i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span>
                        <i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_mdassetcovid_dashboard">
                    <?php
                    }
                    ?>
                    <div class="content covid19">
                        <div class="container-fluid">
                            <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
                            <div class="d-sm-flex justify-content-between">
                                <div style="min-width:500px;"> 
                                    <img src="middleware/assets/css/covid19/logo.png" class="logo" alt="">
                                    <h4 class="pt12">УЛСЫН ОНЦГОЙ КОМИССЫН ШУУРХАЙ ШТАБ</h4>
                                </div>
                                <div>
                                     <img src="middleware/assets/css/covid19/clogo.png" class="emlogo" alt="">
                                </div>
                            </div>
                            <div class="row navlist">
                                <?php
                                $color1 = array(
                                    0 => 'gradient-deepblue', 
                                    1 => 'gradient-ibiza', 
                                    2 => 'gradient-scooter', 
                                    3 => 'gradient-ohhappiness',
                                    4 => 'd-none',
                                    5 => 'd-none',
                                    6 => 'd-none'
                                );
                                $i = 0;
                                foreach ($this->topCards as $k => $data1) {
                                    if (++$i == 5) break;
                                ?>

                                <div class="col">
                                    <div class="box-shadow  <?php echo $color1[$k]; ?> ">
                                        <div class="d-flex align-items-center text-white">
                                            <div class=" w-100 desc row justify-content-around">
                                                <div class="col-md-10"> <p class="text-uppercase"><?php echo $data1['title']; ?></p></div>
                                                <div class="col"> <span class="top-descrition" style="font-size: 32px;text-align:right"><?php echo $data1['qty']; ?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="row">
                                <div class="col-sm-3 left-dash">
                                    <div class="box-shadow">
                                        <div class="card-header d-flex justify-content-between">
                                            <h6 class="mb-0">Covid-19 вирусын халдвар илэрсэн тоо</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="chartdiv_pie2" class="lchart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="pl0 left-item">
                                        <?php
                                        $color1 = array(
                                            0 => 'd-none', 
                                            1 => 'd-none',
                                            2 => 'd-none',
                                            3 => 'd-none',
                                            4 => 'gradient-ibiza', 
                                            5 => 'gradient-scooter', 
                                            6 => 'gradient-ohhappiness',
                                            7 => 'gradient-deepblue',
                                            8 => 'gradient-scooter',
                                        );
                                        foreach ($this->leftCards as $k => $item){
                                        ?>
                                            <li class="box-shadow <?php echo $color1[$k]; ?>" >
                                                <div class="w-100 desc row justify-content-around">
                                                    <p class="text-uppercase"><?php echo $item['title']; ?>
                                                    <span  style="font-size: 28px;text-align:right"><?php echo $item['qty']; ?></span></p>
                                                </div>
                                            </li>
                                        <?php
                                            }
                                            ?>
                                    </ul>

                                   
                                </div>
                                <div class="col-sm-6">

                                    <div class="box-shadow">
                                        <div class="card-header d-flex justify-content-between">
                                            <h6 class="lh-5">Тусгаарлах байрны байршилууд</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="map-mn-markers"></div>
                                                </div>
                                            </div>
                                            <div class="box-shadows">
                                                <?php
                                                    $rows = '';
                                                    $ageSum = 0;
                                                    $imported = 0;
                                                    $casesCount = count($this->cases);
                                                ?>
                                                <div class="card-header">
                                                    <h6 class="mb-0">Тусгаарлалтаас гарах тоон мэдээ</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mt-4 mb-4">
                                                        <?php 
                                                        $icon = array(
                                                            0 => 'alarm', 
                                                            1 => 'paperplane', 
                                                            2 => 'calendar52', 
                                                            3 => 'd-none',
                                                            4 => 'd-none',
                                                            5 => 'd-none'
                                                        );
                                                            foreach ($this->weeklyAndDay as $k => $data1) {
                                                        ?>
                                                            <div class="col codate ">
                                                                <div
                                                                    class="media align-items-center color<?php echo $k; ?>">
                                                                    <div class="mr-3">
                                                                        <a href="javascript:vopid(0);"
                                                                            class="btn bg-icon p-3 border-0 opacity-05 text-teal btn-icon-medium">
                                                                            <i class="icon-calendar font-size-22"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <div class="text-uppercase font-size-12 text-muted">
                                                                            <?php echo $data1['title']; ?></div>
                                                                        <div class="font-size-22 line-height-normal color5">
                                                                            <?php echo $data1['qty']; ?></div>
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
                                <div class="col-sm-3 right-dash">

                                    <div class="box-shadow">
                                        <div class="card-header d-flex justify-content-between">
                                            <h6 class="mb-0">Нас хүйсийн харьцаа /сэжигтэй тохиолдол/</h6>
                                        </div>
                                        <div class="card-body mt-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="chart-container">
                                                        <div class="chart rchart" id="tornado_negative_stack"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="box-shadow">
                                        <div class="card-header">
                                            <div class="d-sm-flex justify-content-between">
                                                <div class="mb-0 mg-sm-b-0">
                                                    <h6 class="mb-0">Тусгаарлах байрны дүүргэлт</h6>
                                                </div>
                                            </div>
                                            <div class="d-sm-flex justify-content-between">
                                                <p class="text-muted">
                                                <?php
                                                    if ($this->locationCapacity) {
                                                        
                                                        $minDate = min(array_column($this->locationCapacity, 'bookdate'));
                                                        $maxDate = max(array_column($this->locationCapacity, 'bookdate'));
                                                        
                                                        echo Date::formatter($minDate, 'Y/m/d') . ' - ' . Date::formatter($maxDate, 'Y/m/d');
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <div id="chartdiv" class="rchart"></div>
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

<script type="text/javascript">
/* ------------------------------------------------------------------------------
 *
 *  # D3.js - horizontal bar chart
 *
 *  Demo d3.js horizontal bar chart setup with .csv data source
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var DashboardSparklines = function() {

    var windowL = $('.covid19').height();
    $('.left-dash').find('.lchart').css('height', windowL / 2 - 150);
    $('.right-dash').find('.rchart').css('height', windowL / 2 - 180);
    $('.box-shadow').find('#map-mn-markers').css('height', windowL - 375);

    $(window).resize(function() {

        //console.log(windowL);
    });

    //
    // Setup module components
    //

    // Sparklines chart
    var _chartSparkline = function(element, chartType, qty, height, interpolation, duration, interval, color) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if ($(element).length > 0) {


            // Basic setup
            // ------------------------------

            // Define main variables
            var d3Container = d3.select(element),
                margin = {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
                height = height - margin.top - margin.bottom;


            // Generate random data (for demo only)
            var data = [];
            for (var i = 0; i < qty; i++) {
                data.push(Math.floor(Math.random() * qty) + 5)
            }


            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.scale.linear().range([0, width]);

            // Vertical
            var y = d3.scale.linear().range([height - 5, 5]);


            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain([1, qty - 3])

            // Vertical
            y.domain([0, qty])



            // Construct chart layout
            // ------------------------------

            // Line
            var line = d3.svg.line()
                .interpolate(interpolation)
                .x(function(d, i) {
                    return x(i);
                })
                .y(function(d, i) {
                    return y(d);
                });

            // Area
            var area = d3.svg.area()
                .interpolate(interpolation)
                .x(function(d, i) {
                    return x(i);
                })
                .y0(height)
                .y1(function(d) {
                    return y(d);
                });



            // Create SVG
            // ------------------------------

            // Container
            var container = d3Container.append('svg');

            // SVG element
            var svg = container
                .attr('width', width + margin.left + margin.right)
                .attr('height', height + margin.top + margin.bottom)
                .append("g")
                .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');



            // Add mask for animation
            // ------------------------------

            // Add clip path
            var clip = svg.append('defs')
                .append('clipPath')
                .attr('id', function(d, i) {
                    return 'load-clip-' + element.substring(1)
                })

            // Add clip shape
            var clips = clip.append('rect')
                .attr('class', 'load-clip')
                .attr('width', 0)
                .attr('height', height);

            // Animate mask
            clips
                .transition()
                .duration(1000)
                .ease('linear')
                .attr('width', width);



            //
            // Append chart elements
            //

            // Main path
            var path = svg.append('g')
                .attr('clip-path', function(d, i) {
                    return 'url(#load-clip-' + element.substring(1) + ')'
                })
                .append('path')
                .datum(data)
                .attr('transform', 'translate(' + x(0) + ',0)');

            // Add path based on chart type
            if (chartType == 'area') {
                path.attr('d', area).attr('class', 'd3-area').style('fill', color); // area
            } else {
                path.attr('d', line).attr('class', 'd3-line d3-line-strong').style('stroke', color); // line
            }

            // Animate path
            path
                .style('opacity', 0)
                .transition()
                .duration(750)
                .style('opacity', 1);



            // Set update interval. For demo only
            // ------------------------------

            setInterval(function() {

                // push a new data point onto the back
                data.push(Math.floor(Math.random() * qty) + 5);

                // pop the old data point off the front
                data.shift();

                update();

            }, interval);



            // Update random data. For demo only
            // ------------------------------

            function update() {

                // Redraw the path and slide it to the left
                path
                    .attr('transform', null)
                    .transition()
                    .duration(duration)
                    .ease('linear')
                    .attr('transform', 'translate(' + x(0) + ',0)');

                // Update path type
                if (chartType == 'area') {
                    path.attr('d', area).attr('class', 'd3-area').style('fill', color)
                } else {
                    path.attr('d', line).attr('class', 'd3-line d3-line-strong').style('stroke', color);
                }
            }



            // Resize chart
            // ------------------------------

            // Call function on window resize
            window.addEventListener('resize', resizeSparklines);

            // Call function on sidebar width change
            var sidebarToggle = document.querySelector('.sidebar-control');
            sidebarToggle && sidebarToggle.addEventListener('click', resizeSparklines);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function resizeSparklines() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                // Layout
                // -------------------------

                // Main svg width
                container.attr('width', width + margin.left + margin.right);

                // Width of appended group
                svg.attr('width', width + margin.left + margin.right);

                // Horizontal range
                x.range([0, width]);


                // Chart elements
                // -------------------------

                // Clip mask
                clips.attr('width', width);

                // Line
                svg.select('.d3-line').attr('d', line);

                // Area
                svg.select('.d3-area').attr('d', area);
            }
        }
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _chartSparkline('#new-visitors-0', 'line', 30, 35, 'basis', 750, 2000, '#4e90ff');
            _chartSparkline('#new-visitors-1', 'line', 30, 35, 'basis', 750, 2000, '#ff7c53');
            _chartSparkline('#new-visitors-2', 'line', 30, 35, 'basis', 750, 2000, '#00f3ff');
            _chartSparkline('#new-visitors-3', 'line', 30, 35, 'basis', 750, 2000, '#a2ff00');
        }
    }
}();

// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    DashboardSparklines.init();
});
</script>

<script type="text/javascript" src="assets/core/js/plugins/visualization/d3/d3.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/visualization/echarts/echarts.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/amcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/serial.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/pie.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/ammap_amcharts_extension.v1.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/mongoliaLow.js"></script>

<!-- amCharts javascript code -->
<script type="text/javascript">
AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "categoryField": "bookdate",
    "startDuration": 1,
    "theme": "default",
    "categoryAxis": {
        "gridPosition": "start"
    },
    "colors": [
        "#7ebcff",
        "#f77eb9"
    ],
    'legend': {
        spacing: 0,
        position: 'top',
        align: 'right',
        'markerType': 'circle',
        'periodValueText': 'Нийт: [[value.sum]]',
        'labelText': '[[title]] ',
        'valueText': '[[value]]',
        'valueWidth': 80
    },
    "graphs": [{
            "balloonText": "[[category]] - [[name]] /Дүүргэлт/ [[value]]",
            "bulletHitAreaSize": -6,
            "fillAlphas": 1,
            "gapPeriod": 10,
            "id": "AmGraph-1",
            "lineAlpha": 0.68,
            "lineThickness": 4,
            "minDistance": 78,
            "negativeFillAlphas": 0.54,
            "negativeLineColor": "#762E2E",
            "precision": -3,
            "title": "Дүүргэлт",
            "type": "column",
            "valueField": "qty"
        },
        {
            "balloonText": "[[category]] - [[name]] /Багтаамж/ [[value]]",
            "fillAlphas": 1,
            "id": "AmGraph-2",
            "title": "Багтаамж",
            "type": "column",
            "valueField": "capacityqty"
        }
    ],
    "dataProvider": <?php echo json_encode($this->locationCapacity); ?>
});

AmCharts.makeChart("chartdiv_pie", {
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
});

AmCharts.makeChart("chartdiv_pie2", {
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
    "labelColorField": "#fff",
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
});

AmCharts.makeChart("chartdiv_pie3", {
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
});

AmCharts.makeChart("gender_over_time", {
    "type": "serial",
    "categoryField": "category",
    "startDuration": 1,
    "categoryAxis": {
        "gridPosition": "start"
    },
    "trendLines": [],
    "graphs": [{
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
    "dataProvider": [{
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
});

var chart = AmCharts.makeChart("tornado_negative_stack", {
    "type": "serial",
    "theme": "light",
    "colors": [
        "#f77eb9",
        "#7ebcff"
    ],
    "rotate": true,
    "marginBottom": 50,
    "dataProvider": <?php echo json_encode($this->ageGenderDistrubition); ?> ,
    "startDuration" : 1,
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
        "y": "95%",
        "bold": true,
        "align": "middle"
    }, {
        "text": "Эмэгтэй",
        "x": "75%",
        "y": "95%",
        "bold": true,
        "align": "middle"
    }]

});

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
        "selectedColor": "#8ec649",
        "outlineThickness": 2,
        "rollOverOutlineColor": "#fff",
        "rollOverColor": "#8ec649",
        "balloonText": "<b><span style='font-size:14px'>[[title]]</span></b><br><span style='font-size:14px; font-family: Arial'>[[customdata]]</span>",

    },
    "balloon": {
        "adjustBorderColor": true,
        "color": "#000000",
        "cornerRadius": 0,
        "fillColor": "#FFFFFF",
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
map.addListener("click", function (event) {
    map.dataProvider.images = [];
    var area = map.dataProvider.areas;
//    console.log(event.area.id);
    for (x in map.dataProvider.areas) {
        var area = map.dataProvider.areas[x];
        console.log(area.id);
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
            image.title = area.title +'<br/>'+ 'Дүүргэлт: ' + area.value;
            // image.label = area.title ;
            image.label = area.title + '('+ area.customdata  +')' ;
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