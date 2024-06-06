<div class="content hr-main2">
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
                foreach ($this->layoutPositionArr['main2_1'] as $k => $data1) {
                    if (++$i == 5) break;
                    $rowJson = htmlentities(json_encode($data1), ENT_QUOTES, 'UTF-8');
                ?>

                <div class="col">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                        <?php if(isset($data1['dvid'])){?>
                            <a href="javascript:;" class="drill"  data-row="<?php echo $rowJson; ?>"  onclick="drilldownHrList(this, '','<?php echo isset($data1['dvid']) ? $data1['dvid'] : '' ?>')" ></a>
                        <?php } ?>
                        <div class="d-flex align-items-center text-white">
                            <div class="w-100 desc row justify-content-around">
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
        <!-- body -->
        <div class="row top">
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                       <h6 class="mb-0"><?php echo $this->lang->line('p2section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p2section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                       <h6 class="mb-0"><?php echo $this->lang->line('p2section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p2section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                       <h6 class="mb-0"><?php echo $this->lang->line('p2section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p2section4" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="box-shadow">
                <div class="card-header">
                   <h6 class="mb-0"><?php echo $this->lang->line('p2section5'); ?></h6>
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
                            foreach ($this->layoutPositionArr['main2_5'] as $k => $data1) { ?>
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
        <div class="row middle">
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                       <h6 class="mb-0"><?php echo $this->lang->line('p2section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p2section6" class="chart"> data oruulash</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                       <h6 class="mb-0"><?php echo $this->lang->line('p2section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p2section7" class="chart"> data oruulash</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                       <h6 class="mb-0"><?php echo $this->lang->line('p2section8'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p2section8" class="chart"> data oruulash</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end body -->
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">
   
    var p2section2 = <?php echo json_encode($this->layoutPositionArr['main2_2']); ?>;
    var p2section3 = <?php echo json_encode($this->layoutPositionArr['main2_3']); ?>;
    var p2section4 = <?php echo json_encode($this->layoutPositionArr['main2_4']); ?>;
    var p2section5 = <?php echo json_encode($this->layoutPositionArr['main2_5']); ?>;
    var p2section6 = <?php echo json_encode($this->layoutPositionArr['main2_6']); ?>;
    var p2section7 = <?php echo json_encode($this->layoutPositionArr['main2_7']); ?>;
    var p2section8 = <?php echo json_encode($this->layoutPositionArr['main2_8']); ?>;

    var chartp2section2 = AmCharts.makeChart("p2section2", {
        "type": "serial",
        "theme": "light",
        "colors": [
            "#f77eb9",
            "#7ebcff"
        ],
        "rotate": true,
        "marginBottom": 50,
        "dataProvider":p2section2,
        "startDuration" : 1,
        "graphs": [{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val1",
            "title": "<?php echo $this->lang->line('tr_label_name'); ?>",
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
            "text": "<?php echo $this->lang->line('tr_label_name1'); ?>",
            "x": "28%",
            "y": "95%",
            "bold": true,
            "align": "middle"
        }, {
            "text": "<?php echo $this->lang->line('tr_label_name2'); ?>",
            "x": "75%",
            "y": "95%",
            "bold": true,
            "align": "middle"
        }]

    });
    chartp2section2.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453116656')
    });      

    var chartp2section4 = AmCharts.makeChart("p2section4", {
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
        "dataProvider": p2section4
    });
    chartp2section4.addListener("clickSlice", function (event) {
        drilldownMeta(event.dataItem.dataContext, '1592453116684')
    });         

    AmCharts.makeChart("p2section3old", {
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
        "dataProvider": p2section3
    });
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p2section6", am4charts.PieChart);
        var chart2 = am4core.create("p2section7", am4charts.PieChart);
        chart.logo.height = -15;
        chart2.logo.height = -15;
        chart.innerRadius = am4core.percent(60);
        chart2.innerRadius = am4core.percent(60);
        chart.data = p2section6;
        chart2.data =p2section7;
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
        pieSeries.labels.template.fill = am4core.color("black");
        pieSeries.labels.template.relativeRotation = 90;
      
        pieSeries2.ticks.template.disabled = true;
        pieSeries2.alignLabels = false;
        pieSeries2.labels.template.text = "{value}";
        pieSeries2.labels.template.radius = am4core.percent(-40);
        pieSeries2.labels.template.fill = am4core.color("black");
        pieSeries2.labels.template.relativeRotation = 90;
        chart.legend = new am4charts.Legend();
        chart2.legend = new am4charts.Legend();
        
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453116712')
        });            
        pieSeries2.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453116727')
        });            
    });
    //p2section8 

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

    var chartp2section8 = AmCharts.makeChart("p2section8", {
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
            "title": "<?php echo $this->lang->line('se2_p8_label_1'); ?>",
            "labelPosition": "center",
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
                "title": "<?php echo $this->lang->line('se2_p8_label_2'); ?>",
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
                "title": "<?php echo $this->lang->line('se2_p8_label_3'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val1]]",
                "type": "column",
                "valueField": "val3"
            },
            {
                "balloonText": "[[val4]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "<?php echo $this->lang->line('se2_p8_label_4'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val5]]",
                "type": "column",
                "valueField": "val4"
            },
            {
                "balloonText": "[[val5]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "<?php echo $this->lang->line('se2_p8_label_5'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val5]]",
                "type": "column",
                "valueField": "val5"
            },
        
        ],
        "dataProvider": p2section8
    });
    chartp2section8.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1593075390613')
    });      
    var dataTemp1 = [];
    var data2 = groupBy(p2section3, 'category');

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

            var chart = am4core.create("p2section3", am4charts.XYChart);
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
                categoryAxis.renderer.labels.template.horizontalCenter = "right";
                categoryAxis.renderer.labels.template.verticalCenter = "middle";
                categoryAxis.renderer.labels.template.rotation = 320;
                categoryAxis.renderer.labels.template.maxWidth = 250;
                categoryAxis.renderer.labels.template.truncate = true;

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
                range.label.tooltipText = tempArray[0].provider;

                range.label.adapter.add("maxWidth", function(maxWidth, target){
                    return 120;
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453116670');
        });                 

    });
   
</script>