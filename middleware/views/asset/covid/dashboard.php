<?php
if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#app_tab_mdassetcovid_dashboard" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
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
    <div class="container">
        <div class="d-sm-flex justify-content-between">
            <div>
                <!--<ol class="breadcrumb">
                    <li class="breadcrumb-item text-uppercase line-height-normal text-orange">Хянах самбар</li>
                </ol>-->
                <h4>Монгол дахь COVID-19 вирусын дэгдэлтийн хяналтын самбар</h4>
            </div>
        </div>

        <div class="row">
            <?php
            $color1 = array(
                0 => 'gradient-deepblue', 
                1 => 'gradient-ibiza', 
                2 => 'gradient-scooter', 
                3 => 'gradient-ohhappiness'
            );
            foreach ($this->topCards as $k => $data1) {
            ?>
            <div class="col-sm-3">
                <div class="box-shadow pt15 pb6 <?php echo $color1[$k]; ?>">
                    <div class="d-flex align-items-center text-white">
                        <div>
                            <div class="text-uppercase"><?php echo $data1['title']; ?></div>
                            <span style="font-size: 40px"><?php echo $data1['qty']; ?></span>
                        </div>
                    </div>
                    <div id="new-visitors-<?php echo $k; ?>"></div>
                </div>
            </div>
            <?php
            }
            ?>
            <!--<div class="col-sm-3">
                <div class="box-shadow gradient-ibiza">
                    <div class="d-flex align-items-center mb-2 text-white">
                        <div>
                            <div class="text-uppercase">Currently in hospital</div>
                            <span class="font-size-22">309</span> <span>67.9%</span> <span>of total cases</span>
                        </div>
                    </div>
                    <div id="new-sessions"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="box-shadow gradient-scooter">
                    <div class="d-flex align-items-center mb-2 text-white">
                        <div>
                            <div class="text-uppercase">Deceased</div>
                            <span class="font-size-22">02</span> <span>0.4%</span> <span>of total cases</span>
                        </div>
                    </div>
                    <div id="total-online"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="box-shadow gradient-ohhappiness">
                    <div class="d-flex align-items-center mb-2 text-white">
                        <div>
                            <div class="text-uppercase">Discharged</div>
                            <span class="font-size-22">144</span> <span>31.6%</span> <span>of total cases</span>
                        </div>
                    </div>
                    <div id="server-load"></div>
                </div>
            </div>-->
            <div class="col-lg-8 col-xl-9">
                <div class="box-shadow">
                    <div class="card-header">
                        <div class="d-sm-flex justify-content-between">
                        <div class="mb-0 mg-sm-b-0">
                            <h6 class="mb-0">Тусгаарлах байрны дүүргэлт</h6>
                        </div>
                        <!--<ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="javascript:void(0);">
                                    <span>Reported Symptomatic</span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);">
                                    <span>Confirmed Cases</span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);">
                                    <span>Discharged</span>
                                </a>
                            </li>
                        </ul>-->
                        </div>
                        <div class="d-sm-flex justify-content-between">
                            <p class="text-muted">2020/03/01 - 2020/03/31</p>
                        </div>
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
                        <h6 class="mb-0">Хүйсээр /баталгаажсан/</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="chartdiv_pie" style="height: 431px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="lh-5">Сэжигтэй тохиолдлоор тусгаарлагдсан иргэдийн тоон статистик</h6>
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
                                <div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray"><?php echo $d['name']; ?></span>
                                        <span class="float-right text-gray"><?php echo $d['qty']; ?></span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-danger" style="width: <?php echo $d['percent']; ?>%">
                                            <span class="sr-only"><?php echo $d['percent']; ?>% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <!--<div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray">Mongolia</span>
                                        <span class="float-right text-gray">159.103</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-success" style="width: 19%">
                                            <span class="sr-only">19% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray">Kiev</span>
                                        <span class="float-right text-gray">29.56</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-danger" style="width: 70%">
                                            <span class="sr-only">70% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray">Ukraine</span>
                                        <span class="float-right text-gray">10.996</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-dark" style="width: 46%">
                                            <span class="sr-only">46% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray">Budapest</span>
                                        <span class="float-right text-gray">25.909</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-orange" style="width: 92%">
                                            <span class="sr-only">92% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray">Berlin</span>
                                        <span class="float-right text-gray">35.102</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-teal" style="width: 25%">
                                            <span class="sr-only">25% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray">Prague</span>
                                        <span class="float-right text-gray">65.789</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-purple" style="width: 58%">
                                            <span class="sr-only">58% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="mb-2">
                                        <span class="text-gray">France</span>
                                        <span class="float-right text-gray">12.456</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 0.375rem;">
                                        <div class="progress-bar bg-pink" style="width: 72%">
                                            <span class="sr-only">72% Complete</span>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">Улсаар /баталгаажсан/</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div id="chartdiv_pie2" style="height: 485px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">Нас хүйсийн харьцаа /сэжигтэй тохиолдол/</h6>
                    </div>
                    <div class="card-body mt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="chart-container">
                                    <div class="chart" id="tornado_negative_stack" style="height:455px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="box-shadow">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="mb-0">Халдварын эх үүсвэр</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="chartdiv_pie3" style="height: 485px;"></div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!--<div class="col-lg-6">
                <div class="box-shadow">
                    <div class="card-header">
                        <h6 class="mb-0">Gender over Time</h6>
                        <p class="mb-0 text-muted">Showing Male Cases vs Female Over Time.</p>
                    </div>
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        <a href="javascript:vopid(0);" class="btn bg-primary border-0 opacity-05 text-teal btn-icon-small"><i class="icon-man"></i></a>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-size-22 line-height-normal">275</div>
                                        <div class="text-muted text-uppercase font-size-12">Male</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        <a href="javascript:vopid(0);" class="btn bg-pink border-0 opacity-05 text-teal btn-icon-small"><i class="icon-woman"></i></a>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-size-22 line-height-normal">180</div>
                                        <div class="text-muted text-uppercase font-size-12">Female</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-eight">
                            <img src="assets/custom/img/3-chart.png" class="w-100">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="box-shadow">
                    <div class="card-header">
                        <h6 class="mb-0">Infection Source over Time</h6>
                        <p class="mb-0 text-muted">Showing Number of Imported Cases vs Local Transmission Over Time.</p>
                    </div>
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        <a href="javascript:vopid(0);" class="btn bg-primary border-0 opacity-05 text-teal btn-icon-small">
                                            <i class="icon-connection"></i>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-size-22 line-height-normal">275</div>
                                        <div class="text-muted text-uppercase font-size-12">Male</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        <a href="javascript:vopid(0);" class="btn bg-pink border-0 opacity-05 text-teal btn-icon-small">
                                            <i class="icon-paperplane"></i>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="font-size-22 line-height-normal">275</div>
                                        <div class="text-muted text-uppercase font-size-12">Female</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-eight">
                            <img src="assets/custom/img/3-chart.png" class="w-100">
                        </div>
                    </div>
                </div>
            </div>-->

            <div class="col-lg-12 col-xl-8">
                
                <?php
                $rows = '';
                $ageSum = 0;
                $imported = 0;
                $casesCount = count($this->cases);
                
                foreach ($this->cases as $c) {
                    
                    $rows .= '
                    <tr>
                        <td><a href="javascript:void(0);">'.$c['cases'].'</a></td>
                        <td class="text-right"><span class="text-success-600">'.$c['confirmeddate'].'</span></td>
                        <td class="text-right font-weight-bold text-gray">'.$c['age'].'</td>
                        <td class="text-right text-blue"><span class="btn '.($c['gender'] == 'Эрэгтэй' ? 'bg-orange' : 'bg-teal').' btn-sm font-size-11 opacity-06">'.$c['gender'].'</span></td>
                        <td class="text-right">'.$c['symptomatic'].'</td>
                    </tr>';
                    
                    $ageSum += $c['age'];
                    
                    if ($c['islocal'] != 1) {
                        $imported += 1;
                    }
                }
                ?>
                
                <div class="box-shadow">
                    <div class="card-header">
                        <h6 class="mb-0">Тохиолдол /баталгаажсан/</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        <a href="javascript:vopid(0);" class="btn bg-teal p-3 border-0 opacity-05 text-teal btn-icon-medium">
                                            <i class="icon-alarm font-size-22"></i>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-muted text-uppercase font-size-12">Дундаж нас</div>
                                        <div class="font-size-22 line-height-normal"><?php echo $casesCount ? round($ageSum / $casesCount) : 0; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        <a href="javascript:vopid(0);" class="btn bg-pink p-3 border-0 opacity-05 text-teal btn-icon-medium">
                                            <i class="icon-paperplane font-size-22"></i>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-muted text-uppercase font-size-12">Гадаад улсаас орж ирсэн</div>
                                        <div class="font-size-22 line-height-normal"><?php echo $imported; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        <a href="javascript:vopid(0);" class="btn bg-primary p-3 border-0 opacity-05 text-teal btn-icon-medium">
                                            <i class="icon-connection font-size-22"></i>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-muted text-uppercase font-size-12">Дотоодоос</div>
                                        <div class="font-size-22 line-height-normal"><?php echo $casesCount - $imported; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                <th class="text-muted">Тохиолдол</th>
                                <th class="text-right text-muted">Баталгаажсан огноо</th>
                                <th class="text-right text-muted">Нас</th>
                                <th class="text-right text-muted">Хүйс</th>
                                <th class="text-right text-muted">Биеийн байдал</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $rows; ?>
                                <!--<tr>
                                    <td><a href="javascript:void(0);">454</a></td>
                                    <td class="text-right"><span class="text-danger"><i class="icon-stats-decline2 mr-2"></i> 30 March 2020</span></td>
                                    <td class="text-right font-weight-bold text-gray">47</td>
                                    <td class="text-right text-red"><span class="btn bg-teal btn-sm font-size-11 opacity-06">Female</span></td>
                                    <td class="text-right">Unknown</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="box-shadow">
                    <div class="card-header">
                        <h6>Clusters</h6>
                    </div>
                    <div class="card-body">
                        <div class="pt-3 pb-3">
                            <img src="assets/custom/img/map-chart.png" class="w-100">
                        </div>
                        <div class="table-responsive">
                            <table class="table withmap">
                                <thead>
                                    <tr>
                                        <th class="text-muted">Clusters</th>
                                        <th class="text-muted">Infection</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($this->clusters as $c) {
                                    ?>
                                    <tr>
                                        <td><?php echo $c['name']; ?></td>
                                        <td class="text-right text-orange font-weight-bold"><?php echo $c['qty']; ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
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
    if($(element).length > 0) {


        // Basic setup
        // ------------------------------

        // Define main variables
        var d3Container = d3.select(element),
            margin = {top: 0, right: 0, bottom: 0, left: 0},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom;


        // Generate random data (for demo only)
        var data = [];
        for (var i=0; i < qty; i++) {
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
            .x(function(d, i) { return x(i); })
            .y(function(d, i) { return y(d); });

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
            .attr('id', function(d, i) { return 'load-clip-' + element.substring(1) })

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
            .attr('clip-path', function(d, i) { return 'url(#load-clip-' + element.substring(1) + ')'})
            .append('path')
                .datum(data)
                .attr('transform', 'translate(' + x(0) + ',0)');

        // Add path based on chart type
        if(chartType == 'area') {
            path.attr('d', area).attr('class', 'd3-area').style('fill', color); // area
        }
        else {
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
            if(chartType == 'area') {
                path.attr('d', area).attr('class', 'd3-area').style('fill', color)
            }
            else {
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
    AmCharts.makeChart("chartdiv",
        {
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
            "graphs": [
                {
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
        }
    );

    AmCharts.makeChart("chartdiv_pie",
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

var map = AmCharts.makeChart("map-mn-markers", {

        "type": "map",
        "theme": "none",
        "dataProvider": {
        "map": "mongoliaLow",
        "getAreasFromMap": true,
         images: [
    {
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Улаанбаатар",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 57.916668,
      longitude: 134.9166645
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Хөвсгөл",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 63.633331,
      longitude: 112.150002
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Дархан",
            labelPosition: "right",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 61.466667,
      longitude:  131.949997
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Сэлэнгэ",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 63.166667,
      longitude:  129.149997
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Эрдэнэт",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 60.846666,
      longitude: 126.033333
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Увс",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 61.983334,
      longitude: 90.066666
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Баян-Өлгий",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 62.166667,
      longitude: 77.166667
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Ховд",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 53.000000,
      longitude: 85.633331
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Говь-Алтай",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 46.366669,
      longitude: 96.250000
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Завхан",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 56.733334,
      longitude: 100.833336
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Дундговь",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 44.750000,
      longitude:  132.250000
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Говьсүмбэр",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 49.350000,
      longitude:  140.250000
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Баянхонгор",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 46.183334,
      longitude:  110.716667
    },{
      zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Архангай",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 55.466667,
      longitude:  115.449997
    },{
        zoomLevel: 5,
      scale: 0.5,
      labelFontSize: 10,
      label: "Булган",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 58.799999,
      longitude:  122.533333
    },{
      zoomLevel: 5,
      scale: 0.5,
        labelFontSize: 10,
      label: "Өвөрхангай",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude: 48.250000,
      longitude:  120.766670
    },{
      zoomLevel: 5,
      scale: 0.5,
        labelFontSize: 10,
      label: "Өмнөговь",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude:   32.566666,
      longitude:  125.416664
    },{
      zoomLevel: 5,
      scale: 0.5,
        labelFontSize: 10,
      label: "Төв",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude:   53.566666,
      longitude:  132.416664
    },{
      zoomLevel: 5,
      scale: 0.5,
        labelFontSize: 10,
      label: "Дорноговь",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude:   40.566666,
      longitude:  145.416664
    },{
      zoomLevel: 5,
      scale: 0.5,
        labelFontSize: 10,
      label: "Сүхбаатар",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude:   50.566666,
      longitude:  158.416664
    },{
      zoomLevel: 5,
      scale: 0.5,
        labelFontSize: 10,
      label: "Хэнтий",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude:   56.316666,
      longitude:  146.650002 
    },{
      zoomLevel: 5,
      scale: 0.5,
        labelFontSize: 10,
      label: "Дорнод",
            labelPosition: "bottom",
            labelColor: "#fff",
            labelRollOverColor:"#fff",
      latitude:   57.066666 ,
      longitude:  162.533333  
    }]          ,
        "areas": [{
              "title": "Архангай аймаг",
              "id": "AR",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Баян-Өлгий аймаг",
              "id": "BI",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Баянхонгор аймаг",
              "id": "BR",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Булган аймаг",
              "id": "BL",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>1</span>",
              "color": "#D6E1ED",
            },{
              "title": "Говь-Алтай аймаг",
              "id": "GA",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>1</span>",
              "color": "#D6E1ED",
            },{
              "title": "Дорноговь аймаг",
              "id": "DO",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>1</span>",
              "color": "#D6E1ED",
            },{
              "title": "Дундговь аймаг",
              "id": "DU",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>1</span>",
              "color": "#D6E1ED",
            },{
              "title": "Завхан аймаг",
              "id": "ZA",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>2</span>",
              "color": "#D6E1ED",
            },{
              "title": "Өвөрхангай аймаг",
              "id": "UR",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Өмнөговь аймаг",
              "id": "UM",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>2</span>",
              "color": "#D6E1ED",
            },{
              "title": "Сүхбаатар аймаг",
              "id": "SV",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>1</span>",
              "color": "#D6E1ED",
            },{
              "title": "Сэлэнгэ аймаг",
              "id": "SE",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Төв аймаг",
              "id": "TB",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Увс аймаг",
              "id": "UV",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Ховд аймаг",
              "id": "XO",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Хөвсгөл аймаг",
              "id": "HU",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Хэнтий аймаг",
              "id": "HE",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Дархан-Уул аймаг",
              "id": "DA",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Орхон аймаг",
              "id": "ER",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>3</span>",
              "color": "#D6E1ED",
            },{
              "title": "Улаанбаатар",
              "id": "UB",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>28</span>",
              "color": "#D6E1ED",
            },{
              "title": "Говьсүмбэр аймаг",
              "id": "GR",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>1</span>",
              "color": "#D6E1ED",
            },{
              "title": "Дорнод аймаг",
              "id": "DD",
              "customData": "<hr>Тоо: <span style='color: #d1655d'>2</span>",
              "color": "#D6E1ED",
            }]
    },
        "areasSettings": {
       /* "autoZoom": true,*/
        /*"selectedColor": "#8ec649",*/
        "outlineThickness":2,
        "rollOverOutlineColor": "#fff",
       /* "rollOverColor":"#8ec649",*/
        "balloonText": "<b><span style='font-size:14px'>[[title]]</span></b><br><span style='font-size:14px; font-family: Arial'>[[customData]]</span>",   

    },
  "balloon": {
    "adjustBorderColor": true,
    "color": "#000000",
    "cornerRadius": 0,
    "fillColor": "#FFFFFF",
    "textAlign" : "left",
    "fillAlpha": 10,
  },
  "zoomControl": {
    "zoomControlEnabled": false,
    "buttonFillColor": "#07509E",
    "buttonSize": 15
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
})
</script>