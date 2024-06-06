<?php
if (!$this->isAjax) {
?>
<div class="col-md-12 ">
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
        <div class="card-body pt0">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_mdassetcovid_dashboard">
                    <?php
                    }
                    ?>
                    <div class="content covid19 resource ">
                        <div class="container-fluid ">
                            <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
                            <!-- <div class="d-sm-flex justify-content-between pt-2">
                                <div style="min-width:450px">
                                    <a href="javascript:;" style="float:left">
                                        <div class="d-flex ">
                                            <img src="middleware/assets/css/covid19/logo.png" class="img-fluid logo " alt="logo" draggable="false">
                                            <h4 class="header-title pt20" style="color: #405e7c; line-height: 1; ">Монгол улсын<br>онцгой комисс</h4>
                                        </div>
                                    </a>
                                    <a href="javascript:;" class="emy">
                                        <div class="d-flex ">
                                            <img src="middleware/assets/css/covid19/mlogo.png" class="img-fluid logo2" alt="logo" draggable="false">
                                            <h4 class="header-title moh"  style="color: #405e7c; line-height: 1;">Эрүүл<br>мэндийн яам</h4>
                                        </div>
                                    </a>
                                </div>
                                <div class="w-100 text-left pl-3"> 
                                    <h3 class="pt15 mt0">Нөөцийн удирдлагын самбар</h3>
                                </div>
                            </div> -->
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

                            <div class="row topamchart">
                                <div class="col-md-3 ">
                                    <div class="box-shadow">
                                        <div class="card-header d-flex justify-content-between">
                                            <h6 class="mb-0">Хэвтэж буй өвчтөний тоо</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <!-- <div id="chartdiv_pie" class="topcharts"></div> -->
                                                    <div class="chart has-fixed-height topcharts" id="pie_donut"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="box-shadow">
                                        <div class="card-header d-flex justify-content-between">
                                            <h6 class="mb-0">Ажиллаж буй эмчийн тоо</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="chartdiv" class="topcharts"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="box-shadow">
                                        <div class="card-header d-flex justify-content-between">
                                            <h6 class="mb-0">Ажиллаж буй сувилагчийн тоо</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <!-- <div id="chartdiv_pie2" class="topcharts"></div> -->
                                                    <div class="chart has-fixed-height topcharts" id="pie_donut2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 ">
                                    <div class="box-shadow">
                                        <div class="card-header d-flex justify-content-between">
                                            <h6 class="mb-0">Сул орны тоо</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="chartdiv4" class="topcharts"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>

                            <div class="row bottomamchart mt-n12">
                                <div class="col-md-9">
                                    <h3 class="ml15">Нийслэлийн хэмжээний эмнэлэгийн нөөцийн мэдээлэл</h3>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="box-shadow mbchart">
                                            
                                                <div class="selectdiv" id="selectdiv">
                                                    <label>
                                                        <select name ="hospital" id="hospital" onchange="hospitalFunction(value)">
                                                        <option value="1587614854725" selected> Эмнэлэгүүд </option>
                                                        <?php
                                                            foreach ($this->hospital as $k => $item) {
                                                            ?>
                                                       
                                                                <option value="<?php echo $item['hospitalid']; ?>"><?php echo $item['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div>
                                                    <ul id="HospitalData" class="list-group  bg-slate-600 border-0">
                                                        <?php
                                                            
                                                            foreach ($this->lsHospital as $k => $item) {
                                                                $sepClass = '';
                                                                if($item['isseperator'] === '1'){
                                                                    $sepClass = 'line';
                                                                }
                                                            ?>
                                                            <li class="list-group-item <?php echo $sepClass; ?>"> <?php echo $item['name']; ?> <span class="badge  ml-auto" style="background:<?php echo $item['color']; ?>"><?php echo $item['qty']; ?></span></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="box-shadow">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <h4><?php echo $this->mbchart1['1']['groupname'];?></h4>
                                                                    <div id="mbchart1" class="mbchart"></div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <h4><?php echo  $this->mbchart2['0']['groupname'];?></h4>
                                                                    <div id="mbchart2" class="mbchart"></div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <h4><?php echo  $this->mbchart3['0']['groupname'];?></h4>
                                                                    <div id="mbchart3" class="mbchart"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3"> 
                                    <h3>Тээвэрлэлт</h3>
                                    <div id="transArchiv" class="box-shadow mbchart">
                                        <ul  class="list-group  bg-slate-800 border-0">
                                        <?php
                                            if ($this->trasGroup) {
                                                foreach ($this->trasGroup as $key => $grouped) { ?>
                                                    <div class="theader p30">
                                                        <?php echo $key; ?>
                                                    </div>
                                                    <ul class="list-group-child border-0">
                                                        <?php foreach ($grouped as $item) {  ?>
                                                            <li class="list-group-item"> <?php echo $item['name']; ?> <span class="badge bg-pink-400 ml-auto"><?php echo $item['qty1']; ?></span></li>
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
<!-- <style>
#pie_donut{
    height:350px;
    width:100%
}
</style> -->

<script type="text/javascript" src="assets/core/js/plugins/visualization/d3/d3.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/visualization/echarts/echarts.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/amcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/serial.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/pie.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/ammap_amcharts_extension.v1.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/mongoliaLow.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/core.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/charts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/themes/animated.js"></script>

<script type="text/javascript">

    function hospitalFunction($id) {   
        $.ajax({
            type: "POST",
            url: "mdasset/selecthospital",
            data: { 'id': $id },
            dataType: 'json',
            success: function($response){

                var $data = $response.data;
                var $mainSelector = $('#HospitalData').empty();
                
                var $data = $response.data; 
                var $adata = $response.adata;
                var $bdata = $response.bdata;
                var $cdata = $response.cdata;
               
                $.each($data.getinfodtl, function(index, row) {
                    if(row.isseperator === '1'){
                        var $classLine = 'line';
                    }
                    var $html = '<li class="list-group-item '+ $classLine +'"> ' + row.name + ' <span class="badge ml-auto" style="background:'+row.color +'">'+ row.qty + '</span></li>';
                    $mainSelector.append($html);
                });
               
                $dataval1 = $adata.getniisleldtl;
                $dataval2 = $bdata.getniisleldtl;
                $dataval3 = $cdata.getniisleldtl;

                var mbchart1 = am4core.create("mbchart1", am4charts.XYChart);
                    mbchart1.data = $dataval1;
                    var categoryAxis = mbchart1.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "val";
                        categoryAxis.renderer.grid.template.disabled = true;
                        categoryAxis.renderer.labels.template.disabled = true;
                        categoryAxis.renderer.grid.template.location = 0;
                        categoryAxis.renderer.minGridDistance = 2;

                    var valueAxis = mbchart1.yAxes.push(new am4charts.ValueAxis());

                    var series1 = mbchart1.series.push(new am4charts.ColumnSeries());
                        series1.dataFields.valueY = "qty";
                        series1.dataFields.categoryX = "val";
                        series1.tooltip.label.textAlign = "top";
                        series1.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
                        // series1.columns.template.showTooltipOn = "always";
                        series1.columns.template.strokeWidth = 0;

                    mbchart1.logo.height = -60;
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
                            legend1.data = legenddata;
                });
                var mbchart2 = am4core.create("mbchart2", am4charts.XYChart);
                    mbchart2.data = $dataval2;
                    var categoryAxis = mbchart2.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "val";
                        categoryAxis.renderer.grid.template.disabled = true;
                        categoryAxis.renderer.labels.template.disabled = true;
                        categoryAxis.renderer.grid.template.location = 0;
                        categoryAxis.renderer.minGridDistance = 2;

                    var valueAxis = mbchart2.yAxes.push(new am4charts.ValueAxis());

                    var series2 = mbchart2.series.push(new am4charts.ColumnSeries());
                        series2.dataFields.valueY = "qty";
                        series2.dataFields.categoryX = "val";
                        series2.tooltip.label.textAlign = "top";
                        series2.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
                        // series2.columns.template.showTooltipOn = "always";
                        series2.columns.template.strokeWidth = 0;

                    mbchart2.logo.height = -15;
                    mbchart2.colors.list = [
                        am4core.color("#EA7369"),
                        am4core.color("#EB548C"),
                        am4core.color("#DB4CB2"),
                        am4core.color("#AF4BCE"),
                        am4core.color("#7D3ACI")];
                        
                    series2.columns.template.events.once("inited", function(event){
                        event.target.fill = mbchart2.colors.getIndex(event.target.dataItem.index);
                    });
                    
                    var valueLabel = series2.bullets.push(new am4charts.LabelBullet());
                        valueLabel.label.text = "{qty.formatNumber('#.0a')}"
                        valueLabel.label.fontSize = 11;
                        valueLabel.label.dy = -10;
                        // mbchart2.legend.markers.template.useDefaultMarker = true;

                    var legend2 = new am4charts.Legend();
                        legend2.parent = mbchart2.chartContainer;
                        legend2.itemContainers.template.togglable = false;
                        legend2.itemContainers.template.paddingTop = 2;
                        legend2.itemContainers.template.paddingBottom = 2;
                        legend2.marginTop = 5;

                        series2.events.on("ready", function(ev) {
                        var legenddata = [];
                        series2.columns.each(function(column) {
                            legenddata.push({
                            name: column.dataItem.categoryX,
                            fill: column.fill
                            });
                        });
                        legend2.data = legenddata;
                });
                var mbchart3 = am4core.create("mbchart3", am4charts.XYChart);
                    mbchart3.data = $dataval3;
                    var categoryAxis = mbchart3.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "val";
                        categoryAxis.renderer.grid.template.disabled = true;
                        categoryAxis.renderer.labels.template.disabled = true;
                        categoryAxis.renderer.grid.template.location = 0;
                        categoryAxis.renderer.minGridDistance = 2;

                    var valueAxis = mbchart3.yAxes.push(new am4charts.ValueAxis());

                    var series = mbchart3.series.push(new am4charts.ColumnSeries());
                        series.dataFields.valueY = "qty";
                        series.dataFields.categoryX = "val";
                        series.tooltip.label.textAlign = "top";
                        series.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
                        // series.columns.template.showTooltipOn = "always";
                        series.columns.template.strokeWidth = 0;

                    mbchart3.logo.height = -15;
                    mbchart3.colors.list = [
                        am4core.color("#00796B"),
                        am4core.color("#FDD835"),
                        am4core.color("#FF5722"),
                        am4core.color("#FF5722"),
                        am4core.color("#B71C1C")
                        ];
                    series.columns.template.events.once("inited", function(event){
                        event.target.fill = mbchart3.colors.getIndex(event.target.dataItem.index);
                    });
                    
                    var valueLabel = series.bullets.push(new am4charts.LabelBullet());
                        valueLabel.label.text = "{qty.formatNumber('0')}"
                        valueLabel.label.fontSize = 11;
                        valueLabel.label.dy = -10;
                        // mbchart3.legend.markers.template.useDefaultMarker = true;

                    var legend3 = new am4charts.Legend();
                            legend3.parent = mbchart3.chartContainer;
                            legend3.itemContainers.template.togglable = false;
                            legend3.itemContainers.template.paddingTop = 2;
                            legend3.itemContainers.template.paddingBottom = 2;
                            legend3.marginTop = 5;

                            series.events.on("ready", function(ev) {
                            var legenddata = [];
                            series.columns.each(function(column) {
                                legenddata.push({
                                name: column.dataItem.categoryX,
                                fill: column.fill
                                });
                            });
                            legend3.data = legenddata;
                });
            }
        });
    }

    var windowL = $('.resource').height();
    

    $(window).resize(function() {
        $('.topamchart').find('.topcharts').css('height', windowL / 2 - 130);
        $('.bottomamchart').find('.mbchart').css('height', windowL / 2 - 90);
        $('.bottomamchart').find('.mbchartLong').css('height', windowL / 2 - 130);
    
    });
    // Setup module
    // ------------------------------

    var DashboardSparklines = function() {

       
        
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

    <!-- amCharts javascript code -->
<script type="text/javascript">
    am4core.useTheme(am4themes_animated);
        var chart = am4core.create("pie_donut", am4charts.PieChart);
        var chart2 = am4core.create("pie_donut2", am4charts.PieChart);
        chart.logo.height = -15;
        chart2.logo.height = -15;
        chart.innerRadius = am4core.percent(40);
        chart2.innerRadius = am4core.percent(40);
        chart.data = <?php echo json_encode($this->gender); ?>;
        chart2.data = <?php echo json_encode($this->nationality); ?>;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        var pieSeries2 = chart2.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "value";
        pieSeries.dataFields.category = "name";
        pieSeries2.dataFields.value = "value";
        pieSeries2.dataFields.category = "name";

        var colors = ["#E53935","#C2185B","#7B1FA2","#512DA8","#303F9F","#00FF00"];
        var colorset = new am4core.ColorSet();
        colorset.list = [];
        for(var i=0;i<colors.length;i++)
            colorset.list.push(new am4core.color(colors[i]));
            pieSeries.colors = colorset;
            pieSeries.colors = colorset;

        pieSeries.ticks.template.disabled = true;
        pieSeries.alignLabels = false;
        pieSeries.labels.template.text = "{value}";
        pieSeries.labels.template.radius = am4core.percent(-40);
        pieSeries.labels.template.fill = am4core.color("white");
        pieSeries.labels.template.relativeRotation = 90;
      
        pieSeries2.ticks.template.disabled = true;
        pieSeries2.alignLabels = false;
        pieSeries2.labels.template.text = "{value}";
        pieSeries2.labels.template.radius = am4core.percent(-40);
        pieSeries2.labels.template.fill = am4core.color("white");
        pieSeries2.labels.template.relativeRotation = 90;
        chart.legend = new am4charts.Legend();
        chart2.legend = new am4charts.Legend();
  

    AmCharts.makeChart("chartdiv", {
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
    AmCharts.makeChart("chartdiv4", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,

        "theme": "default",
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 45
        },
        "colors": [
            "#6C3483"
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
                "precision": -3,
                "title": "Нийт сул ор",
                "labelPosition": "top",
                "labelText": "[[value]]",
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
        "dataProvider": <?php echo json_encode($this->emplCapacity); ?>
    });

    AmCharts.makeChart("chartdivlong", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"top"
        },
        "colors": [
            // "#176BAD", 
            // "#19AADE",
            // "#1AC9E6",
            // "#1AC9E6",
            // "#1DE4BD",
            // "#7D3ACI" ,
            // "#AF4BCE",
            // "#DB4CB2",
            // "#EB548C",
            // // "#EA7369",
            "#EABD38", 
            "#EE9A3A",
            "#EF7E32",
            "#DE542C",
            "#C02323"
        ],
        // 'legend': {
        //     spacing: 0,
        //     position: 'top',
        //     align: 'right',
        //     'markerType': 'circle',
        //     'periodValueText': 'Нийт: [[value.sum]]',
        //     // 'labelText': '[[title]] ',
        //     // 'valueText': '[[value]]',
        //     'valueWidth': 80
        // },
        "graphs": [{
             "balloonText": "[[val1]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-1",
                "title": "[[val2]]",
                "labelPosition": "left",
                "labelRotation":270,
                "labelText": "  [[val1]]",
                "type": "column",
                "valueField": "qty1"
            },
            {
                "balloonText": "[[val2]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "[[val2]]",
                "labelPosition": "left",
                "labelRotation":270,
                "labelText": "  [[val2]]",
                "type": "column",
                "valueField": "qty2"
            },
            {
                "balloonText": "[[val3]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "[[val2]]",
                "labelPosition": "bottom",
                "labelRotation":270,
                "labelText": "  [[val3]]",
                "type": "column",
                "valueField": "qty3"
            },
            {
                "balloonText": "[[val4]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-4",
                "labelPosition": "bottom",
                "labelRotation":270,
                "labelText": "  [[val4]]",
                "type": "column",
                "valueField": "qty4"
            },
            {
                "balloonText": "[[val5]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-5",
                "labelRotation":270,
                "title": "val5",
                "labelPosition": "top",
                "labelText": "  [[val5]]",
                "type": "column",
                "valueField": "qty5"
            }
        ],
        "dataProvider": <?php echo json_encode($this->lsHospitalTech); ?>
    });

    AmCharts.makeChart("mbchart1d", {
        "type": "serial",
        "categoryField": "qty",
        "startDuration": 1,
        "theme": "default",
        "addClassNames": true,
        "categoryAxis": {
            "gridPosition": "start",
            "position":"bottom"
        },
        "legend": {
            "horizontalGap": 10,
            "maxColumns": 1,
            "position": "top",
            "useGraphSettings": true,
            "markerSize": 10
        },
        "colors": [
            "#176BAD", 
            "#19AADE",
            "#1AC9E6",
            "#1AC9E6",
            "#1DE4BD",
        ],
        "graphs": [{
            "balloonText": "[[val]] / [[value]]",
            "fillAlphas": 1,
            "title": "Шахуурга, тариурын хамт",
            "bulletHitAreaSize": -6,
            "gapPeriod": 10,
            "id": "AmGraph-1",
            // "negativeLineColor": "colors",
            "fillColor":"colors",
            "labelPosition": "left",
            "labelRotation":270,
            "labelText": "  [[qty]]",
            "type": "column",
            "valueField": "qty"
            },
        ],
        "dataProvider": <?php echo json_encode($this->mbchart1); ?>
    });
    AmCharts.makeChart("mbchart2c", {
        "type": "serial",
        "categoryField": "val",
        "startDuration": 1,
        "theme": "default",
        "addClassNames": true,
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 90,
            "autoWrap": true,
            "autoRotateAngle": 0
        },
        "colors": [
            // "#176BAD", 
            // "#19AADE",
            // "#1AC9E6",
            // "#1AC9E6",
            // "#1DE4BD",
            "#7D3ACI" ,
            "#AF4BCE",
            "#DB4CB2",
            "#EB548C",
            "#EA7369",
            // "#EABD38", 
            // "#EE9A3A",
            // "#EF7E32",
            // "#DE542C",
            // "#C02323"
        ],
        // 'legend': {
        //     spacing: 0,
        //     position: 'top',
        //     align: 'right',
        //     'markerType': 'circle',
        //     'periodValueText': 'Нийт: [[value.sum]]',
        //     // 'labelText': '[[title]] ',
        //     // 'valueText': '[[value]]',
        //     'valueWidth': 80
        // },
        "graphs": [{
            "balloonText": "[[val]] / [[value]]",
            "fillAlphas": 1,
            "title": "[[val2]]",
            "bulletHitAreaSize": -6,
            "gapPeriod": 10,
            "id": "AmGraph-1",
            "lineAlpha": 0.68,
            "lineThickness": 4,
            "minDistance": 78,
            "negativeFillAlphas": 0.54,
            "negativeLineColor": "#1DE4BD",
            // "labelPosition": "left",
            // "labelRotation":270,
            "labelText": "  [[qty]]",
            "type": "column",
            "valueField": "qty"
            },
  
        ],
        "dataProvider": <?php echo json_encode($this->mbchart2); ?>
    });
    AmCharts.makeChart("mbchart3d", {
        "type": "serial",
        "categoryField": "val",
        "startDuration": 1,
        "theme": "default",
        "addClassNames": true,
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 90,
            "autoWrap": true,
            "autoRotateAngle": 0
        },
        "colors": [
            "#176BAD", 
            "#19AADE",
            "#1AC9E6",
            "#1AC9E6",
            "#1DE4BD",
            // "#7D3ACI" ,
            // "#AF4BCE",
            // "#DB4CB2",
            // "#EB548C",
            // // "#EA7369",
            // "#EABD38", 
            // "#EE9A3A",
            // "#EF7E32",
            // "#DE542C",
            // "#C02323"
        ],
        // 'legend': {
        //     spacing: 0,
        //     position: 'top',
        //     align: 'right',
        //     'markerType': 'circle',
        //     'periodValueText': 'Нийт: [[value.sum]]',
        //     // 'labelText': '[[title]] ',
        //     // 'valueText': '[[value]]',
        //     'valueWidth': 80
        // },
        "graphs": [{
            "balloonText": "[[val]] / [[value]]",
            "fillAlphas": 1,
            "title": "[[val2]]",
            "bulletHitAreaSize": -6,
            "gapPeriod": 10,
            "id": "AmGraph-1",
            "lineAlpha": 0.68,
            "lineThickness": 4,
            "minDistance": 78,
            "negativeFillAlphas": 0.54,
            "negativeLineColor": "#EB548C",
            // "labelPosition": "left",
            // "labelRotation":270,
            "labelText": "  [[qty]]",
            "type": "column",
            "valueField": "qty"
            },
  
        ],
        "dataProvider": <?php echo json_encode($this->mbchart3); ?>
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

    var EchartsPieDonutLight = function() {

        var _scatterPieDonutLightExample = function() {
            if (typeof echarts == 'undefined') {
                console.warn('Warning - echarts.min.js is not loaded.');
                return;
            }

            // Define element
            var pie_donut_element = document.getElementById('pie_donutg');
            var pie_donut_element2 = document.getElementById('pie_donut2g');
            
            if (pie_donut_element) {

                // Initialize chart
                var pie_donut = echarts.init(pie_donut_element);

                pie_donut.setOption({
                    color: [
                        '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                        '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                        '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                        '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089'
                    ],

                    // Global text styles
                    textStyle: {
                        fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                        fontSize: 11
                    },

                    // Add title
                    title: {
                        // text: 'Хэвтэж буй өвчтөний тоо',
                        // subtext: 'Open source information',
                        left: 'center',
                        textStyle: {
                            fontSize: 17,
                            fontWeight: 500
                        },
                        subtextStyle: {
                            fontSize: 12
                        }
                    },
                    

                    // Add tooltip
                    tooltip: {
                        trigger: 'item',
                        backgroundColor: 'rgba(0,0,0,0.75)',
                        padding: [10, 15],
                        textStyle: {
                            fontSize: 13,
                            fontFamily: 'Roboto, sans-serif'
                        },
                        formatter: "{a} <br/>{b}: {c} ({d}%)"
                    },

                    // // Add legend
                    // legend: {
                    //     orient: 'horizantal',
                    //     top: 'center',
                    //     left: 0,
                    //     data: ['IE', 'Opera', 'Safari', 'Firefox', 'Chrome'],
                    //     itemHeight: 8,
                    //     itemWidth: 8
                    // },

                    // Add series
                    series: [{
                        name: '',
                        type: 'pie',
                        radius: ['50%', '65%'],
                        center: ['50%', '50%'],
                        itemStyle: {
                            normal: {
                                borderWidth: 1,
                                borderColor: '#fff'
                            },
                            show:true,
                            position: 'inside',
                            formatter: function(data){
                                        var v = data.value;
                                        if (v[0]==161.2 && v[1]==51.6)
                                            return 'a'
                                        else
                                            return v
                                    }
                        },
                        data:<?php echo json_encode($this->gender); ?>
                        
                    }]
                });
            }
            if (pie_donut_element2) {

                // Initialize chart
                var pie_donut = echarts.init(pie_donut_element2);


                pie_donut.setOption({

                    // Colors
                    color: [
                        "#f77eb9",
                        "#7ebcff",
                        "#f2b8ff",
                        "#fec85e",
                        "#4cebb5",
                        "#a5d7fd",
                        "#b2bece",
                        "#a4e063"
                    ],

                    // Global text styles
                    textStyle: {
                        fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                        fontSize: 11
                    },

                    // Add title
                    title: {
                        // text: 'Хэвтэж буй өвчтөний тоо',
                        // subtext: 'Open source information',
                        left: 'center',
                        textStyle: {
                            fontSize: 17,
                            fontWeight: 500
                        },
                        subtextStyle: {
                            fontSize: 12
                        }
                    },

                    // Add tooltip
                    tooltip: {
                        trigger: 'item',
                        backgroundColor: 'rgba(0,0,0,0.75)',
                        padding: [10, 15],
                        textStyle: {
                            fontSize: 13,
                            fontFamily: 'Roboto, sans-serif'
                        },
                        formatter: "{a} <br/>{b}: {c} ({d}%)"
                    },

                    // // Add legend
                    // legend: {
                    //     orient: 'vertical',
                    //     top: 'center',
                    //     left: 0,
                    //     data: ['IE', 'Opera', 'Safari', 'Firefox', 'Chrome'],
                    //     itemHeight: 8,
                    //     itemWidth: 8
                    // },

                    // Add series
                    series: [{
                        name: '',
                        type: 'pie',
                        radius: ['50%', '65%'],
                        center: ['50%', '56.5%'],
                        itemStyle: {
                            normal: {
                                borderWidth: 1,
                                borderColor: '#fff'
                            }
                        },
                        data:<?php echo json_encode($this->nationality); ?>
                        
                    }]
                });
            }

            // Resize function
            var triggerChartResize = function() {
                pie_donut_element && pie_donut.resize();
            };

            // On sidebar width change
            var sidebarToggle = document.querySelector('.sidebar-control');
            sidebarToggle && sidebarToggle.addEventListener('click', triggerChartResize);

            // On window resize
            var resizeCharts;
            window.addEventListener('resize', function() {
                clearTimeout(resizeCharts);
                resizeCharts = setTimeout(function () {
                    triggerChartResize();
                }, 200);
            });
        };

        return {
            init: function() {
                _scatterPieDonutLightExample();
            }
        }
    }();

    document.addEventListener('DOMContentLoaded', function() {
        EchartsPieDonutLight.init();
    });

    var mbchart1 = am4core.create("mbchart1", am4charts.XYChart);
        mbchart1.data = <?php echo json_encode($this->mbchart1); ?>;
        var categoryAxis = mbchart1.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "val";
            categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.renderer.labels.template.disabled = true;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 2;

        var valueAxis = mbchart1.yAxes.push(new am4charts.ValueAxis());

        var series1 = mbchart1.series.push(new am4charts.ColumnSeries());
            series1.dataFields.valueY = "qty";
            series1.dataFields.categoryX = "val";
            series1.tooltip.label.textAlign = "top";
            series1.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
            // series1.columns.template.showTooltipOn = "always";
            series1.columns.template.strokeWidth = 0;

        mbchart1.logo.height = -60;

        mbchart1.colors.list = [
            am4core.color("#1DE4BD"),
            am4core.color("#1AC9E6"),
            am4core.color("#3498DB"),
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
                legend1.data = legenddata;
    });
    var mbchart2 = am4core.create("mbchart2", am4charts.XYChart);
        mbchart2.data = <?php echo json_encode($this->mbchart2); ?>;
        var categoryAxis = mbchart2.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "val";
            categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.renderer.labels.template.disabled = true;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 2;

        var valueAxis = mbchart2.yAxes.push(new am4charts.ValueAxis());

        var series2 = mbchart2.series.push(new am4charts.ColumnSeries());
            series2.dataFields.valueY = "qty";
            series2.dataFields.categoryX = "val";
            series2.tooltip.label.textAlign = "top";
            series2.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
            // series2.columns.template.showTooltipOn = "always";
            series2.columns.template.strokeWidth = 0;

        mbchart2.logo.height = -60;
        mbchart2.colors.list = [
            am4core.color("#EA7369"),
            am4core.color("#EB548C"),
            am4core.color("#DB4CB2"),
            am4core.color("#AF4BCE"),
            am4core.color("#7D3ACI")];
            
        series2.columns.template.events.once("inited", function(event){
            event.target.fill = mbchart2.colors.getIndex(event.target.dataItem.index);
        });
        
        var valueLabel = series2.bullets.push(new am4charts.LabelBullet());
            valueLabel.label.text = "{qty.formatNumber('0')}"
            valueLabel.label.fontSize = 11;
            // valueLabel.label.dy = -10;
            valueLabel.label.dy = -10;

           
            // mbchart2.legend.markers.template.useDefaultMarker = true;

        var legend2 = new am4charts.Legend();
            legend2.parent = mbchart2.chartContainer;
            legend2.itemContainers.template.togglable = false;
            legend2.itemContainers.template.paddingTop = 2;
            legend2.itemContainers.template.paddingBottom = 2;
            legend2.marginTop = 5;

            series2.events.on("ready", function(ev) {
            var legenddata = [];
            series2.columns.each(function(column) {
                legenddata.push({
                name: column.dataItem.categoryX,
                fill: column.fill
                });
            });
            legend2.data = legenddata;
    });
    var mbchart3 = am4core.create("mbchart3", am4charts.XYChart);
        mbchart3.data =<?php echo json_encode($this->mbchart3); ?>;
        var categoryAxis = mbchart3.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "val";
            categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.renderer.labels.template.disabled = true;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 2;

        var valueAxis = mbchart3.yAxes.push(new am4charts.ValueAxis());

        var series = mbchart3.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "qty";
            series.dataFields.categoryX = "val";
            series.tooltip.label.textAlign = "top";
            series.columns.template.tooltipText = "[bold]{valueY}[/]";
            series.columns.template.align  = "center";
            // series.columns.template.showTooltipOn = "always";
            series.columns.template.valign  = "top";
            series.columns.template.strokeWidth = 0;

        mbchart3.logo.height = -60;
        mbchart3.colors.list = [
            am4core.color("#00796B"),
            am4core.color("#FDD835"),
            am4core.color("#FF5722"),
            am4core.color("#FF5722"),
            am4core.color("#B71C1C") ];
        series.columns.template.events.once("inited", function(event){
            event.target.fill = mbchart3.colors.getIndex(event.target.dataItem.index);
        });
        
        var valueLabel = series.bullets.push(new am4charts.LabelBullet());
            valueLabel.label.text = "{qty.formatNumber('#.0a')}"
            valueLabel.label.fontSize = 11;
            valueLabel.label.dy = -10;
            mbchart3.maskBullets = false
           

        var legend3 = new am4charts.Legend();
                legend3.parent = mbchart3.chartContainer;
                legend3.itemContainers.template.togglable = false;
                legend3.itemContainers.template.paddingTop = 2;
                legend3.itemContainers.template.paddingBottom = 2;
                legend3.marginTop = 5;

                series.events.on("ready", function(ev) {
                var legenddata = [];
                series.columns.each(function(column) {
                    legenddata.push({
                    name: column.dataItem.categoryX,
                    fill: column.fill
                    });
                });
                legend3.data = legenddata;
    });
</script>

