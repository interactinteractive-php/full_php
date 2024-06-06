<div class="content hr-rec">
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
                foreach ($this->layoutPositionArr['rec1_0'] as $k => $data1) {
                    if (++$i == 9) break;
                    $rowJson = htmlentities(json_encode($data1), ENT_QUOTES, 'UTF-8');
                ?>

                <div class="col-3">
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
        <!-- end top cards -->
        <!-- body -->
        <div class="row top">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p3section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p3section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p3section4" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                  
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p3section5" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row middle">
            <div class="col-3">
                <div class="box-shadow ">
                    
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p3section6" class="chart"> Дашбоард тохируулах </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p3section7" class="chart"> Дашбоард тохируулах </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section8'); ?></h6>
                    </div>
                   
                    <div class="card-body">
                        <div id="p3section8" class="chart"> Дашбоард тохируулах </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p3section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p3section9" class="chart"> Дашбоард тохируулах </div>
                    </div>
                </div>
            </div>
        </div>
       
        <!-- end body -->
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">
   

    var p3section0 = <?php echo json_encode($this->layoutPositionArr['rec1_0']); ?>;
    var p3section2 = <?php echo json_encode($this->layoutPositionArr['rec1_1']); ?>;
    var p3section3 = <?php echo json_encode($this->layoutPositionArr['rec1_2']); ?>;
    var p3section4 = <?php echo json_encode($this->layoutPositionArr['rec1_3']); ?>;
    var p3section5 = <?php echo json_encode($this->layoutPositionArr['rec1_4']); ?>;
    var p3section6 = <?php echo json_encode($this->layoutPositionArr['rec1_5']); ?>;
    var p3section7 = <?php echo json_encode($this->layoutPositionArr['rec1_6']); ?>;
    var p3section8 = <?php echo json_encode($this->layoutPositionArr['rec1_7']); ?>;
    var p3section9 = <?php echo json_encode($this->layoutPositionArr['rec1_8']); ?>;
    

    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p3section2", am4charts.PieChart);
        var chart2 = am4core.create("p3section3", am4charts.PieChart);
        chart.logo.height = -15;
        chart2.logo.height = -15;
        chart.innerRadius = am4core.percent(40);
        chart2.innerRadius = am4core.percent(40);
        chart.data = p3section2;
        chart2.data =p3section3;
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453305075')
        });            
        pieSeries2.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453305094')
        });          
    });
    AmCharts.makeChart("p3section4ss", {
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
        // 'legend': {
        //     spacing: 0,
        //     position: 'top',
        //     align: 'right',
        //     'markerType': 'circle',
        //     'periodValueText': 'Нийт: [[value.sum]]',
        //     // 'labelText': '[[title]] ',
        //     // 'valueText': '[[value]]',
        //     'valueWidth': 60
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
        "dataProvider": p3section4
    });
    var chartp3section5 = AmCharts.makeChart("p3section5", {
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
        "graphs": [{
            "balloonText": "[[name]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "value",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "[[val1]]",
            "type": "column",
            "valueField": "val1"
            //"y": 20
        }],
        "dataProvider": p3section5
    });
    chartp3section5.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453305124')
    });        

    var chartp3section6 = AmCharts.makeChart("p3section6", {
        "type": "serial",
        "categoryField": "name",
        "categoryAxis": {
            "axisAlpha": 0,
            "labelOffset": 40,
            "gridAlpha": 0
        },        
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left",
            "labelFunction": function (label, item, axis) {
                if (label.length > 15)
                    return label.substr(0, 15) + '...';

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
            "labelText": "",
            "type": "column",
            "valueField": "val1",
            "y": 20
        }],
        "dataProvider": p3section6
    });
    chartp3section6.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453305139')
    });            

    function hideOthers(e) {
        var currentGraph = e.dataItem;
        var hidden = true;
        //check if we clicked on this graph before and if all the other graphs are visible.
        // if we clicked on this graph before and the other graphs are invisible,
        // make them visible, otherwise default to previous behavior
        if (e.chart.lastClicked == currentGraph.id && e.chart.allVisible == false) {
            hidden = false;
            e.chart.allVisible = true;
        }
        else {
            e.chart.allVisible = false;
        }
        e.chart.lastClicked = currentGraph.id; //keep track of the current one we clicked
        
        currentGraph.hidden = false; //force clicked graph to stay visible
        e.chart.graphs.forEach(function(graph) {
            if (graph.id !== currentGraph.id) {
            graph.hidden = hidden;  //set the other graph's visibility based on the rules above
            }
        });
        // update the chart with newly set hidden values
        e.chart.validateNow();
    }

    var chartp3section7 = AmCharts.makeChart("p3section7", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left"
        },
       
        "colors": [
            "#f694c4",
            "#95c6fc", 
            "#6D72C3"
        ],
        "legend": {
            "enabled": true,
            "useGraphSettings": true,
            "listeners": [{
            "event": "showItem",
            "method": hideOthers
            }, {
            "event": "hideItem",
            "method": hideOthers
            }]
        },
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p3section8_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "",
            "type": "column",
            "valueField": "val1",
            "y": 20
            },
            {
                "balloonText": "[[val2]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('p3section8_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "",
                "type": "column",
                "valueField": "val2"
            },
            {
                "balloonText": "[[val3]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "<?php echo $this->lang->line('p3section8_label_3'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "",
                "type": "column",
                "valueField": "val3"
            },
            {
                "balloonText": "[[val4]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-4",
                "title": "<?php echo $this->lang->line('p3section8_label_4'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "",
                "type": "column",
                "valueField": "val4"
            },
        
        ],
        "dataProvider": p3section7
    });
    chartp3section7.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453305153')
    });       

    var chartp3section8 = AmCharts.makeChart("p3section8", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left"
        },
        "colors": [
            "#f694c4",
            "#95c6fc", 
            "#6D72C3"
        ],
        "legend": {
            "enabled": true,
            "useGraphSettings": true,
            "listeners": [{
            "event": "showItem",
            "method": hideOthers
            }, {
            "event": "hideItem",
            "method": hideOthers
            }]
        },
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p3section8_label_11'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "",
            "type": "column",
            "valueField": "val1",
            "y": 20
            },
            {
                "balloonText": "[[val2]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('p3section8_label_22'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "",
                "type": "column",
                "valueField": "val2"
            },
            {
                "balloonText": "[[val3]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "<?php echo $this->lang->line('p3section8_label_33'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "",
                "type": "column",
                "valueField": "val3"
            },
            {
                "balloonText": "[[val4]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-4",
                "title": "<?php echo $this->lang->line('p3section8_label_44'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "",
                "type": "column",
                "valueField": "val4"
            }
        
        ],
        "dataProvider": p3section8
    });
    chartp3section8.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453305167')
    });        

    var dataTemp1 = [];
    var dataTemp4 = [];
    var data2 = groupBy(p3section9, 'category');
    var data4 = groupBy(p3section4, 'category');

    for(var key in data4){
        dataTemp4[key] = {};
        for(var i=0; i < data4[key].length; i++) {
            dataTemp4[key][data4[key][i]['name']] = Number(data4[key][i]['val1']);
        }
    }
    for(var key in data2){
        dataTemp1[key] = {};
        for(var i=0; i < data2[key].length; i++) {
            dataTemp1[key][data2[key][i]['name']] = Number(data2[key][i]['val1']);
        }
    }
    //p3section9

    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p3section9", am4charts.XYChart);
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453305181');
        });                    
    });

    //p3section4
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p3section4", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();

            // will use this to store colors of the same items
            var colors = ["#ee2f78","#6794dc","#7b1fa1","#512DA8","#7f67dc","#00FF00"];

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
            var data = dataTemp4;
           
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
            drilldownMeta(searchRow(p3section4, ev.target.dataItem.dataContext.realName), '1592453305110');
        });                   

    });

</script>