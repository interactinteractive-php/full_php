<div class="content hr-main1">
    <div class="container-fluid">
        <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
     
        <div class="row top">
            <?php
                $color1 = array(
                    0 => 'gradient-deepblue', 
                    1 => 'gradient-ibiza', 
                    2 => 'gradient-scooter', 
                    3 => 'gradient-ohhappiness'
                );
                $i = 0;
                foreach ($this->layoutPositionArr['rec2_0'] as $k => $data1) {
                    if (++$i == 5) break;
                    $rowJson = htmlentities(json_encode($data1), ENT_QUOTES, 'UTF-8');
                ?>

                <div class="col">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                        <?php if(isset($data1['dvid'])){?>
                            <a href="javascript:;" class="drill"  data-row="<?php echo $rowJson; ?>"  onclick="drilldownHrList(this, '','<?php echo isset($data1['dvid']) ? $data1['dvid'] : '' ?>')" ></a>
                        <?php } ?>
                        <div class="d-flex align-items-center text-white">
                            <div class=" w-100 desc row justify-content-around">
                                <div class="col"> <p class="text-uppercase"><?php echo $data1['name']; ?></p></div>
                                <div class="col-auto"> <span  class="count-value"><?php echo $data1['val1']; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
          
        </div>
        <!-- body -->
        <div class="row top">
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section4" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row middle">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section5" class="chart"> Дашбоард тохируулах </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section6" class="chart"> Дашбоард тохируулах </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section7" class="chart"> Дашбоард тохируулах </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bottom">
            <div class="col-8">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section8'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section8" class="chart"></div>
                    </div> 
                </div>
            </div>
            <div class="col-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p4section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p4section9" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end body -->
    </div>
</div>
<!-- amCharts javascript code -->


<script type="text/javascript">

    var p3section9 = <?php echo json_encode($this->layoutPositionArr['rec2_1']); ?>;
    
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p4section2", am4charts.PieChart);
        var chart2 = am4core.create("p4section3", am4charts.PieChart);
        chart.logo.height = -15;
        chart2.logo.height = -15;
        chart.innerRadius = am4core.percent(40);
        chart2.innerRadius = am4core.percent(40);
        chart.data = <?php echo json_encode($this->layoutPositionArr['rec2_1']); ?>;
        chart2.data =<?php echo json_encode($this->layoutPositionArr['rec2_2']); ?>;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        var pieSeries2 = chart2.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "val1";
        pieSeries.dataFields.category = "name";
        pieSeries2.dataFields.value = "val1";
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
        
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453306732')
        });            
        pieSeries2.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453306747')
        });          
    });

    var chartp4section4 = AmCharts.makeChart("p4section4", {
        "type": "serial",
        "theme": "light",
        "colors": [
            "#f77eb9",
            "#7ebcff"
        ],
        "rotate": true,
        "marginBottom": 50,
        "dataProvider":<?php echo json_encode($this->layoutPositionArr['rec2_3']); ?>,
        "startDuration" : 1,
        "graphs": [{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val1",
            "title": "<?php echo $this->lang->line('p4section4_label_1'); ?>",
            "labelText": "[[val1]]",
            "clustered": false,
            "labelFunction": function(item) {
                return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
                return item.category + " : " + Math.abs(item.values.value);
            }
        }, {
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val2",
            "title": "Эмэгтэй",
            "labelText": "[[val2]]",
            "clustered": false,
            "labelFunction": function(item) {
                return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
                return item.category + ": " + Math.abs(item.values.value);
            }
        }],
        "categoryField": "name",
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
            "text": "<?php echo $this->lang->line('p4section4_label_1'); ?>",
            "x": "28%",
            "y": "95%",
            "bold": true,
            "align": "middle"
        }, {
            "text": "<?php echo $this->lang->line('p4section4_label_2'); ?>",
            "x": "75%",
            "y": "95%",
            "bold": true,
            "align": "middle"
        }]

    });
    chartp4section4.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453306761')
    });           

    function groupBy(chartdata, property) {
        return chartdata.reduce((acc, obj) => {
            const key = obj[property];
            if (!acc[key]) {
            acc[key] = [];
            }
            acc[key].push(obj);
            return acc;
        }, {});
    }
    var p4section5 = <?php echo json_encode($this->layoutPositionArr['rec2_4']); ?>;
    var p4section8 = <?php echo json_encode($this->layoutPositionArr['rec2_7']); ?>;
    var data = groupBy(p4section5, 'category');
    var data8 = groupBy(p4section8, 'category');
    var dataTemp = [];
    var dataTemp8 = [];

    for(var key in data){
        dataTemp[key] = {};
        for(var i=0; i < data[key].length; i++) {
            dataTemp[key][data[key][i]['name']] = Number(data[key][i]['val1']);
        }
    }
    for(var key in data8){
        dataTemp8[key] = {};
        for(var i=0; i < data8[key].length; i++) {
            dataTemp8[key][data8[key][i]['name']] = Number(data8[key][i]['val1']);
        }
    }
    //p4section5
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p4section5", am4charts.XYChart);
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453306775');
        });                        
    });
     //p4section8
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p4section8", am4charts.XYChart);
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
            var data = dataTemp8;
           
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453306818');
        });    
    });

    var chartp4section6 = AmCharts.makeChart("p4section6", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left"
        },
        "colors": [
            "#FA9500",
            "#1D1128", 
            "#6D72C3"
        ],
    
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
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['rec2_5']); ?>
    });
    chartp4section6.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453306789')
    });      
    
    var chartp4section7 = AmCharts.makeChart("p4section7", {
        "type": "pie",
        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
        "innerRadius": 0,
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
        "valueField": "val1",
        "allLabels": [],
        "balloon": {},
        "legend": {
            "enabled": true,
            "useGraphSettings": false,
            "align": "center",
            "markerType": "circle"
        },
        "titles": [],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['rec2_6']); ?>
    });
    chartp4section7.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453306804')
    });      

    var chartp4section9 = AmCharts.makeChart("p4section9", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#ee2f78", 
            "#6794dc",
            "#EF7E32",
        ],
        'legend': {
            spacing: 0,
            position: 'bottom',
            align: 'left',
            'markerType': 'circle',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 60
        },
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p4section8_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            },
            {
                "balloonText": "[[val2]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('p4section8_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[val2]]",
                "type": "column",
                "valueField": "val2"
            },
            {
                "balloonText": "[[val3]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "<?php echo $this->lang->line('p4section8_label_3'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[val1]]",
                "type": "column",
                "valueField": "val3"
            },
         
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['rec2_8']); ?>
    });
    chartp4section9.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453306832')
    });          

</script>