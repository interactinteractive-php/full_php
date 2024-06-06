<div class="content hr-ceo">
    <div class="container-fluid">
        <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
        <!-- top cards -->
        <div class="row navlistTop">

            <?php
                $color1 = array(
                    0 => 'gradient-deepblue', 
                    1 => 'gradient-ibiza', 
                    2 => 'gradient-scooter', 
                    3 => 'gradient-ohhappiness',
                    4 => 'gradient-scooter',
                    5 => 'gradient-ohhappiness ',
                    6 => 'gradient-ibiza',
                    7 => 'gradient-deepblue'
                );
                $i = 0;
                foreach ($this->layoutPositionArr['ceo_1'] as $k => $data1) {
                    if (++$i == 9) break;
                ?>

                <div class="col-sm-6 col-md-3 ">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                        <?php if(isset($data1['dvid'])){?>
                            <a href="javascript:;" class="drill"  data-row="<?php echo $rowJson; ?>"  onclick="drilldownHrList(this, '','<?php echo isset($data1['dvid']) ? $data1['dvid'] : '' ?>')" ></a>
                        <?php } ?>
                        <div class="d-flex align-items-center text-white">
                            <div class=" w-100 desc row justify-content-around">
                                <div class="col"> <p class="text-uppercase"><?php echo $data1['name']; ?></p></div>
                                <div class="col-auto"> <span  style="font-size: 28px;"><?php echo $data1['val1']; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
        </div>
        <!-- end top cards -->

        <div class="row">
            <div class="box-shadow">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo $this->lang->line('p6section2'); ?></h6>
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
                            foreach ($this->layoutPositionArr['ceo_2'] as $k => $data1) { ?>
                            <div class="col codate ">
                                <div class="media align-items-center color<?php echo $k; ?>">
                                    <div class="mr-3">
                                        <a href="javascript:;" class="btn bg-icon p-3 border-0 opacity-05 text-teal btn-icon-medium">
                                            <?php if(isset($data1['icon'])){?>
                                                <?php echo $data1['icon']; ?>
                                            <?php }else{ ?>
                                                <i class="icon-calendar font-size-22"></i>
                                            <?php } ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="text-uppercase font-size-12 text-muted">
                                        <?php echo $data1['name']; ?>
                                    </div>
                                    <div class="font-size-22 line-height-normal color5">
                                        <?php echo $data1['val1']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="row top">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section4" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">                       
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section5" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="row bottom">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section7" class="chart"></div>
                    </div> 
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section8'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section8" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section9" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bottom">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section6" class="chart"></div>
                    </div>
                </div>
            </div>            
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section10'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p6section10" class="chart"></div>
                    </div>
                </div>
            </div>            
        </div>
        <!-- end body -->
    </div>
</div>

<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/funnel.js"></script>


<script type="text/javascript">

    var p6section2 = <?php echo json_encode($this->layoutPositionArr['ceo_2']); ?>;
    var p6section3 = <?php echo json_encode($this->layoutPositionArr['ceo_3']); ?>;
    var p6section4 = <?php echo json_encode($this->layoutPositionArr['ceo_4']); ?>;
    var p6section5 = <?php echo json_encode($this->layoutPositionArr['ceo_5']); ?>;
    var p6section6 = <?php echo json_encode($this->layoutPositionArr['ceo_6']); ?>;
    var p6section7 = <?php echo json_encode($this->layoutPositionArr['ceo_7']); ?>;
    var p6section8 = <?php echo json_encode($this->layoutPositionArr['ceo_8']); ?>;
    var p6section9 = <?php echo json_encode($this->layoutPositionArr['ceo_9']); ?>;
    var p6section10 = <?php echo json_encode($this->layoutPositionArr['ceo_10']); ?>;
 

    var chartp6section3 = AmCharts.makeChart("p6section3", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left",
            "labelFunction": function (label, item, axis) {
                if (label.length > 20)
                    return label.substr(0, 20) + '...';

                return label;
            }                 
        },
        "colors": [
            "#FA9500",
            "#1D1128", 
            "#6D72C3"
        ],
    
        "graphs": [{
            "balloonText": "[[name]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "value",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
            // {
            //     "balloonText": "[[val2]] / [[value]]",
            //     "fillAlphas": 1,
            //     "id": "AmGraph-2",
            //     "title": "[[val2]]",
            //     "labelPosition": "top",
            //     "labelRotation":0,
            //     "labelText": "  [[val2]]",
            //     "type": "column",
            //     "valueField": "val2"
            // },
            // {
            //     "balloonText": "[[val3]] / [[value]]",
            //     "fillAlphas": 1,
            //     "id": "AmGraph-3",
            //     "title": "[[val2]]",
            //     "labelPosition": "top",
            //     "labelRotation":0,
            //     "labelText": "  [[val1]]",
            //     "type": "column",
            //     "valueField": "val3"
            // },
        
        ],
        "dataProvider": p6section3
    });
    chartp6section3.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453741518')
    });     

    var chartp6section5 = AmCharts.makeChart("p6section5", {
        "type": "funnel",
        "theme": "light",
        "labelText": "[[percents]]%",
        "legend": {
            "valueText": "[[value]]",
            "valueWidth": 75
        },
        "dataProvider": p6section5,
        "titleField": "name",
        "marginRight": 0,
        "marginLeft": 15,
        "labelPosition": "right",
        "funnelAlpha": 0.9,
        "valueField": "val1",
        "startX": 0,
        "neckWidth": "40%",
        "startAlpha": 0,
        "labelPosition": "center",
        "outlineThickness": 1,
        "neckHeight": "30%",
        "balloonText": "[[title]]:<b>[[value]]</b>",
    });
    chartp6section5.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453741546')
    });   
    
    var chartp6section4 = AmCharts.makeChart("p6section4", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "categoryAxis": {
            "position":"left",
            "labelFunction": function (label, item, axis) {
                if (label.length > 20)
                    return label.substr(0, 20) + '...';

                return label;
            }                 
        },
        "rotate": true,
        "colors": [
            "#5941A9",
            "#1D1128", 
            "#6D72C3"
        ],
        // 'legend': {
        //     spacing: 0,
        //     position: 'top',
        //     align: 'right',
        //     'markerType': 'circle',
        //     'labelText': '[[title]] ',
        //     'valueText': '[[value]]',
        //     'valueWidth': 80
        // },
        "graphs": [{
            "balloonText": "[[name]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "value",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        
        ],
        "dataProvider":p6section4
    });
    chartp6section4.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453741532')
    });     
    
    var chartp6section6 = AmCharts.makeChart("p6section6", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom"
        },
        "rotate": true,
        "colors": [
            "#5941A9",
            "#1D1128", 
            "#6D72C3"
        ],
        // 'legend': {
        //     spacing: 0,
        //     position: 'top',
        //     align: 'right',
        //     'markerType': 'circle',
        //     'labelText': '[[title]] ',
        //     'valueText': '[[value]]',
        //     'valueWidth': 80
        // },
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "value",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        
        ],
        "dataProvider": p6section6
    });
    chartp6section6.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453741560')
    }); 

    // p6section7 04fd03
    am4core.ready(function() {
      
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p6section7", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(0);
        chart.data = p6section7;
     
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";

       
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
            chart.legend = new am4charts.Legend();
           
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453741574')
        });            
    });
    // p6section8 04fd03
    am4core.ready(function() {
    
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p6section8", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(0);
        chart.data = p6section8;
      
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";

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
            chart.legend = new am4charts.Legend();
            chart.legend.position = "right";
            
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453741588')
        });                   
    });

    AmCharts.makeChart("p6section10d", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom"
        },
        "rotate": true,
        "colors": [
            "#5941A9",
            "#1D1128", 
            "#6D72C3"
        ],
        'legend': {
            spacing: 0,
            position: 'top',
            align: 'right',
            'markerType': 'circle',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 80
        },
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "value",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        
        ],
        "dataProvider": p6section10
    });

    var data = groupBy(p6section9, 'category');
    var data10 = groupBy(p6section10, 'category');
    var dataTemp = [];
    var dataTemp10 = [];

    for(var key in data){
        dataTemp[key] = {};
        for(var i=0; i < data[key].length; i++) {
            dataTemp[key][data[key][i]['name']] = Number(data[key][i]['val1']);
        }
    }

    for(var key in data10){
        dataTemp10[key] = {};
        for(var i=0; i < data10[key].length; i++) {
            dataTemp10[key][data10[key][i]['name']] = Number(data10[key][i]['val1']);
        }
    }

    //p6section9
    
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p6section9", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();

            // will use this to store colors of the same items
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){

                return categoryAxis.tooltipDataItem.dataContext.realName;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
                columnSeries.dataFields.categoryX = "category";
                columnSeries.dataFields.valueY = "value";

            // second value axis for quantity
            var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis2.renderer.opposite = true;
                valueAxis2.syncWithAxis = valueAxis;
                valueAxis2.tooltip.disabled = true;

            // quantity line series
            var lineSeries = chart.series.push(new am4charts.LineSeries());
                lineSeries.tooltipText = "{valueY}";
                lineSeries.dataFields.categoryX = "category";
                lineSeries.dataFields.valueY = "quantity";
                lineSeries.yAxis = valueAxis2;
                lineSeries.bullets.push(new am4charts.CircleBullet());
                lineSeries.stroke = chart.colors.getIndex(13);
                lineSeries.fill = lineSeries.stroke;
                lineSeries.strokeWidth = 2;
                lineSeries.snapTooltip = true;

            // when data validated, adjust location of data item based on count
            lineSeries.events.on("datavalidated", function(){
            lineSeries.dataItems.each(function(dataItem){
            // if count divides by two, location is 0 (on the grid)
            if(dataItem.dataContext.count / 2 == Math.round(dataItem.dataContext.count / 2)){
            dataItem.setLocation("categoryX", 0);
            }
            // otherwise location is 0.5 (middle)
            else{
                dataItem.setLocation("categoryX", 0.5);
            }
            })
            })

            // fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
            columnSeries.columns.template.adapter.add("fill", function(fill, target) {
            var name = target.dataItem.dataContext.realName;
            if (!colors[name]) {
            colors[name] = chart.colors.next();
            }
            target.stroke = colors[name];
            return colors[name];
            })


            var rangeTemplate = categoryAxis.axisRanges.template;
                rangeTemplate.tick.disabled = false;
                rangeTemplate.tick.location = 0;
                rangeTemplate.tick.strokeOpacity = 0.6;
                rangeTemplate.tick.length = 30;
                rangeTemplate.grid.strokeOpacity = 0.5;
                rangeTemplate.label.tooltip = new am4core.Tooltip();
                rangeTemplate.label.tooltip.dy = -10;
                rangeTemplate.label.cloneTooltip = false;

            ///// DATA
            var chartData = [];
            var lineSeriesData = [];
            var data = dataTemp;
           
            for (var providerName in data) {
            var providerData = data[providerName];
            var tempArray = [];
            var count = 0;
            // add items
            for (var itemName in providerData) {
            if(itemName != "quantity"){
            count++;
            // we generate unique category for each column (providerName + "_" + itemName) and store realName
            tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
            }
            }
         
            // sort temp array
            tempArray.sort(function(a, b) {
            if (a.value > b.value) {
            return 1;
            }
            else if (a.value < b.value) {
            return -1
            }
            else {
            return 0;
            }
            })

            // add quantity and count to middle data item (line series uses it)
            var lineSeriesDataIndex = Math.floor(count / 2);
                tempArray[lineSeriesDataIndex].quantity = providerData.quantity;
                tempArray[lineSeriesDataIndex].count = count;
            // push to the final data
            am4core.array.each(tempArray, function(item) {
            chartData.push(item);
            })

            // create range (the additional label at the bottom)
            var range = categoryAxis.axisRanges.create();
                range.category = tempArray[0].category;
                range.endCategory = tempArray[tempArray.length - 1].category;
                range.label.text = tempArray[0].provider;
                range.label.dy = 10;
                range.label.truncate = true;
                range.label.fontWeight = "bold";
                range.label.tooltipText = tempArray[0].provider;

            range.label.adapter.add("maxWidth", function(maxWidth, target){
            var range = target.dataItem;
            var startPosition = categoryAxis.categoryToPosition(range.category, 0);
            var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
            var startX = categoryAxis.positionToCoordinate(startPosition);
            var endX = categoryAxis.positionToCoordinate(endPosition);
            return endX - startX;
            })
            }

                 
            chart.data = chartData;

            var legend = new am4charts.Legend();
                legend.parent = chart.chartContainer;
                legend.itemContainers.template.togglable = false;
                legend.itemContainers.template.paddingTop = 2;
                legend.itemContainers.template.paddingBottom = 2;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.realName,
                        fill: column.fill
                        });
                    }
                });
                legend.data = legenddata;
            });
            // last tick
            var range = categoryAxis.axisRanges.create();
                range.category = chart.data[chart.data.length - 1].category;
                range.label.disabled = true;
                range.tick.location = 1;
                range.grid.location = 1;

        columnSeries.columns.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453741602');
        });  
    });

    //p6section10
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p6section10", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){
                    categoryAxis.renderer.inversed = true;
                return categoryAxis.tooltipDataItem.dataContext.realName;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
                columnSeries.dataFields.categoryX = "category";
                columnSeries.dataFields.valueY = "value";

            // second value axis for quantity
            var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis2.renderer.opposite = true;
                valueAxis2.syncWithAxis = valueAxis;
                valueAxis2.tooltip.disabled = true;

            // quantity line series
            var lineSeries = chart.series.push(new am4charts.LineSeries());
                lineSeries.tooltipText = "{valueY}";
                lineSeries.dataFields.categoryX = "category";
                lineSeries.dataFields.valueY = "quantity";
                lineSeries.yAxis = valueAxis2;
                lineSeries.bullets.push(new am4charts.CircleBullet());
                lineSeries.stroke = chart.colors.getIndex(13);
                lineSeries.fill = lineSeries.stroke;
                lineSeries.strokeWidth = 2;
                lineSeries.snapTooltip = true;

            // when data validated, adjust location of data item based on count
            lineSeries.events.on("datavalidated", function(){
            lineSeries.dataItems.each(function(dataItem){
            // if count divides by two, location is 0 (on the grid)
            if(dataItem.dataContext.count / 2 == Math.round(dataItem.dataContext.count / 2)){
            dataItem.setLocation("categoryX", 0);
            }
            // otherwise location is 0.5 (middle)
            else{
                dataItem.setLocation("categoryX", 0.5);
            }
            })
            })

            // fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
            columnSeries.columns.template.adapter.add("fill", function(fill, target) {
            var name = target.dataItem.dataContext.realName;
            if (!colors[name]) {
            colors[name] = chart.colors.next();
            }
            target.stroke = colors[name];
            return colors[name];
            })


            var rangeTemplate = categoryAxis.axisRanges.template;
                rangeTemplate.tick.disabled = false;
                rangeTemplate.tick.location = 0;
                rangeTemplate.tick.strokeOpacity = 0.6;
                rangeTemplate.tick.length = 30;
                rangeTemplate.grid.strokeOpacity = 0.5;
                rangeTemplate.label.tooltip = new am4core.Tooltip();
                rangeTemplate.label.tooltip.dy = -10;
                rangeTemplate.label.cloneTooltip = false;

            ///// DATA
            var chartData = [];
            var lineSeriesData = [];
            var data = dataTemp10;
           
            for (var providerName in data) {
            var providerData = data[providerName];
            var tempArray = [];
            var count = 0;
            // add items
            for (var itemName in providerData) {
            if(itemName != "quantity"){
            count++;
            // we generate unique category for each column (providerName + "_" + itemName) and store realName
            tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
            }
            }
         
            // sort temp array
            tempArray.sort(function(a, b) {
            if (a.value > b.value) {
            return 1;
            }
            else if (a.value < b.value) {
            return -1
            }
            else {
            return 0;
            }
            })

            // add quantity and count to middle data item (line series uses it)
            var lineSeriesDataIndex = Math.floor(count / 2);
                tempArray[lineSeriesDataIndex].quantity = providerData.quantity;
                tempArray[lineSeriesDataIndex].count = count;
            // push to the final data
            am4core.array.each(tempArray, function(item) {
            chartData.push(item);
            })

            // create range (the additional label at the bottom)
            var range = categoryAxis.axisRanges.create();
                range.category = tempArray[0].category;
                range.endCategory = tempArray[tempArray.length - 1].category;
                range.label.text = tempArray[0].provider;
                range.label.dy = 10;
                range.label.truncate = true;
                range.label.fontWeight = "bold";
                range.label.tooltipText = tempArray[0].provider;

            range.label.adapter.add("maxWidth", function(maxWidth, target){
            var range = target.dataItem;
            var startPosition = categoryAxis.categoryToPosition(range.category, 0);
            var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
            var startX = categoryAxis.positionToCoordinate(startPosition);
            var endX = categoryAxis.positionToCoordinate(endPosition);
            return endX - startX;
            })
            }

                 
            chart.data = chartData;

            var legend = new am4charts.Legend();
                legend.parent = chart.chartContainer;
                legend.itemContainers.template.togglable = false;
                legend.itemContainers.template.paddingTop = 2;
                legend.itemContainers.template.paddingBottom = 2;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.realName,
                        fill: column.fill
                        });
                    }
                });
                legend.data = legenddata;
            });
            // last tick
            var range = categoryAxis.axisRanges.create();
                range.category = chart.data[chart.data.length - 1].category;
                range.label.disabled = true;
                range.tick.location = 1;
                range.grid.location = 1;

        columnSeries.columns.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453741488');
        });  
    });
    
    
    
</script>