<div class="content hr-main1">
    <div class="container-fluid">
        <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
        <!-- top cards -->
        <div class="row navlistTop">
            <?php
                $color1 = array(
                    0 => 'gradient-deepblue', 
                    1 => 'gradient-ibiza', 
                    2 => 'gradient-scooter', 
                    3 => 'gradient-ohhappiness'
                );
                $i = 0;
                foreach ($this->layoutPositionArr['relation2_1'] as $k => $data1) {
                    if (++$i == 5) break;
                ?>

                <div class="col">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                        <?php if(isset($data1['dvid'])){?>
                            <a href="javascript:;" class="drill"  data-row="<?php echo $rowJson; ?>"  onclick="drilldownHrList(this, '','<?php echo isset($data1['dvid']) ? $data1['dvid'] : '' ?>')" ></a>
                        <?php } ?>
                        <div class="d-flex align-items-center text-white">
                            <div class=" w-100 desc row justify-content-around">
                                <div class="col"> <p class="text-uppercase"><?php echo $data1['name']; ?></p></div>
                                <div class="col" style="text-align: right"> <span  style="font-size: 28px;"><?php echo $data1['val1']; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
            ?>
        </div>
        <!-- end top cards -->
        <!-- body -->
        <div class="row top">
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section4" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row middle">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section5" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
           
        </div>
        <div class="row bottom">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section6" class="chart"></div>
                    </div> 
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section7" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section8'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section8" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p9section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p9section9" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bottom-card">
            <?php
                $color1 = array(
                    0 => 'gradient-deepblue', 
                    1 => 'gradient-ibiza', 
                    2 => 'gradient-scooter', 
                    3 => 'gradient-ohhappiness'
                );
                $i = 0;
                foreach ($this->layoutPositionArr['relation2_10'] as $k => $data1) {
                    if (++$i == 5) break;
                ?>

                <div class="col">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                        <?php if(isset($data1['dvid'])){?>
                            <a href="javascript:;" class="drill"  data-row="<?php echo $rowJson; ?>"  onclick="drilldownHrList(this, '','<?php echo isset($data1['dvid']) ? $data1['dvid'] : '' ?>')" ></a>
                        <?php } ?>
                        <div class="d-flex align-items-center text-white">
                            <div class=" w-100 desc row justify-content-around">
                                <div class="col-auto"> <p class="text-uppercase"><?php echo $data1['name']; ?></p></div>
                                <div class="col"> <span  class="count-value"><?php echo $data1['val1']; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
            ?>
        </div>
        <!-- end body -->
    </div>
</div>

<!-- amCharts javascript code -->
<script type="text/javascript">

    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p9section2", am4charts.PieChart);

        chart.logo.height = -120;

        chart.innerRadius = am4core.percent(0);

        chart.data = <?php echo json_encode($this->layoutPositionArr['relation2_2']); ?>;
        // Add and configure Series
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
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453743857')
        });              
    });
    AmCharts.makeChart("p9section3d", {
        "type": "serial",
        "theme": "light",
        "rotate": false,
        "marginBottom": 50,
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['relation2_3']); ?>,
        "startDuration": 1,
        "graphs": [{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val1",
            "title": "val1",
            "labelText": "[[value]]",
            "clustered": false,
            "labelFunction": function(item) {
            return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
            return item.category + ": " + Math.abs(item.values.value) ;
            }
        }, {
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val2",
            "title": "val2",
            "labelText": "[[value]]",
            "clustered": false,
            "labelFunction": function(item) {
            return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
            return item.category + ": " + Math.abs(item.values.value);
            }
        },{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val3",
            "title": "val3",
            "labelText": "[[value]]",
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
                return Math.abs(value) ;
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
        }
    });
    var chartp9section3 = AmCharts.makeChart("p9section3", {
        "type": "serial",
        "startDuration": 1,
        "theme": "light",
        "columnSpacing":5,
        //"rotate": false,
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
       
        },
        // "legend": {
        //     "horizontalGap": 10,
        //     "maxColumns": 3,
        //     "position": "bottom",
        //     "useGraphSettings": true,
        //     "markerSize": 10
        // },
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['relation2_3']); ?>,
        "colors": [
            "#FA9500",
            "#e53935", 
            "#6D72C3"
        ],
    
        "graphs": [{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val1",
            "title": "val1",
            "labelText": "[[value]]",
            "clustered": false,
            "labelFunction": function(item) {
            return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
            return item.category + ": " + Math.abs(item.values.value) ;
            }
        },{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val2",
            "title": "val2",
            "labelText": "[[value]]",
            "clustered": false,
            "labelFunction": function(item) {
            return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
            return item.category + ": " + Math.abs(item.values.value) ;
            }
        },
        {
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val3",
            "title": "val3",
            "labelText": "[[value]]",
            "clustered": false,
            "labelFunction": function(item) {
            return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
            return item.category + ": " + Math.abs(item.values.value) ;
            }
        },
     
        ],
        
    });
    chartp9section3.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743872')
    });   
    
    var chartp9section4 = AmCharts.makeChart("p9section4", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "default",
        "rotate": true,
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#EABD38", 
            "#EE9A3A",
            "#EF7E32",
        ],
        "legend": false,
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "",
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
                "title": "",
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
                "title": "",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[val1]]",
                "type": "column",
                "valueField": "val3"
            },
            
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['relation2_4']); ?>
    });
    chartp9section4.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743886')
    });   
    
    var chartp9section5 = AmCharts.makeChart("p9section5", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#EABD38", 
            "#EE9A3A",
            "#EF7E32",
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
            },
            {
                "balloonText": "[[val2]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "[[val2]]",
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
                "title": "[[val2]]",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[val1]]",
                "type": "column",
                "valueField": "val3"
            },
            
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['relation2_5']); ?>
    });
    chartp9section5.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743900')
    });      

    var p9section5 = <?php echo json_encode($this->layoutPositionArr['relation2_5']); ?>;

    var dataTemp1 = [];
    var data2 = groupBy(p9section5, 'category');
    for(var key in data2){
        dataTemp1[key] = {};
        for(var i=0; i < data2[key].length; i++) {
            dataTemp1[key][data2[key][i]['name']] = Number(data2[key][i]['val1']);
        }
    }
    //p2section3
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p9section5", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();
            chart.colors.list = [
              am4core.color("#845EC2"),
              am4core.color("#D65DB1"),
              am4core.color("#FF6F91"),
              am4core.color("#FF9671"),
              am4core.color("#FFC75F"),
              am4core.color("#F9F871")
            ];                        

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
            var data = dataTemp1;
           
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

    });
   
    var chartp9section7 = AmCharts.makeChart("p9section7", {
        "type": "serial",
        "startDuration": 1,
        "theme": "light",
        "columnSpacing":5,
        "rotate": false,
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left"
        },
        "legend": false,
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['relation2_7']); ?>,
        "colors": [
            "#FA9500",
            "#e53935", 
            "#6D72C3"
        ],
    
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 1,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p9section7_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "[[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            },
            {
                "balloonText": "[[val2]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('p9section7_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val2]]",
                "type": "column",
                "valueField": "val2"
            },
            {
                "balloonText": "[[val3]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "<?php echo $this->lang->line('p9section7_label_3'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val1]]",
                "type": "column",
                "valueField": "val3"
            },
        ],
        
    });
    chartp9section7.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743928')
    });      

 
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p9section6", am4charts.PieChart);
    
        chart.logo.height = -120;
    
        chart.innerRadius = am4core.percent(0);
    
        chart.data = <?php echo json_encode($this->layoutPositionArr['relation2_6']); ?>;
        // Add and configure Series
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
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453743914')
        });         
    });
    
    var chartp9section8 = AmCharts.makeChart("p9section8", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom",
            "labelRotation": 45,
            "labelFunction": function (label, item, axis) {
                if (label.length > 15)
                    return label.substr(0, 15) + '...';

                return label;
            }               
        },
        "colors": [
            "#5aa469",
            "#1a93ff", 
            "#6D72C3",
            "#1b2353"
        ],
        "legend": false,
        "graphs": [{
            "balloonText": "[[name]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p7section12_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['relation2_8']); ?>
    });
    chartp9section8.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743942')
    }); 
    
    var chartp9section9 = AmCharts.makeChart("p9section9", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom",
            "labelRotation": 45,
            "labelFunction": function (label, item, axis) {
                if (label.length > 15)
                    return label.substr(0, 15) + '...';

                return label;
            }               
        },
        "colors": [
            "#5aa469",
            "#1a93ff", 
            "#6D72C3",
            "#1b2353"
        ],
        "legend": false,
        "graphs": [{
            "balloonText": "[[name]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p7section12_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        ],
        "dataProvider":<?php echo json_encode($this->layoutPositionArr['relation2_9']); ?>
    });
    chartp9section9.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743956')
    });     
</script>