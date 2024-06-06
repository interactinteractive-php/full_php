<div class="content hr-main1">
    <div class="container-fluid">
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
                foreach ($this->layoutPositionArr['vp_1'] as $k => $data1) {
                    if (++$i == 5) break;
                ?>

                <div class="col-3">
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
        <!-- body -->
        <div class="row top">
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section4" class="chart"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row middle">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section5" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section7" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section8'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section8" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bottom">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section9" class="chart"></div>
                    </div> 
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section11'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section11" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section12'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section12" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bottom">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section6" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>    
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p7section10'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p7section10" class="chart"></div>
                    </div>
                </div>
            </div>            
        </div>        
        <!-- end body -->
        <div class="row navlistbottom">

            <?php
                $color1 = array(
                    0 => 'gradient-deepblue', 
                    1 => 'gradient-ibiza', 
                    2 => 'gradient-scooter', 
                    3 => 'gradient-ohhappiness'
                );
                $i = 0;
                if ($this->layoutPositionArr['vp_13']) {
                    foreach ($this->layoutPositionArr['vp_13'] as $k => $data1) {
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
                }
                ?>
        </div>
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">
   
    var p7section2 = <?php echo json_encode($this->layoutPositionArr['vp_2']); ?>;
    var p7section3 = <?php echo json_encode($this->layoutPositionArr['vp_3']); ?>;
    var p7section4 = <?php echo json_encode($this->layoutPositionArr['vp_4']); ?>;
    var p7section5 = <?php echo json_encode($this->layoutPositionArr['vp_5']); ?>;
    var p7section6 = <?php echo json_encode($this->layoutPositionArr['vp_6']); ?>;
    var p7section7 = <?php echo json_encode($this->layoutPositionArr['vp_7']); ?>;
    var p7section8 = <?php echo json_encode($this->layoutPositionArr['vp_8']); ?>;
    var p7section9 = <?php echo json_encode($this->layoutPositionArr['vp_9']); ?>;
    var p7section10 = <?php echo json_encode($this->layoutPositionArr['vp_10']); ?>;
    var p7section11 = <?php echo json_encode($this->layoutPositionArr['vp_11']); ?>;
    var p7section12 = <?php echo json_encode($this->layoutPositionArr['vp_12']); ?>;
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

    var data = groupBy(p7section2, 'category');
    var dataTemp = [];
    
    for(var key in data){
        dataTemp[key] = {};
        for(var i=0; i < data[key].length; i++) {
            dataTemp[key][data[key][i]['name']] = Number(data[key][i]['val1']);
        }
    }
     //p1section2
     am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p7section2", am4charts.XYChart);
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453742205');
        });       
    });

    var chartp7section3 = AmCharts.makeChart("p7section3", {
        "type": "serial",
        "startDuration": 1,
        "theme": "light",
        "columnSpacing":0,
        "rotate": true,
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left"
        },
        "legend": false,
        "dataProvider": p7section3,
        "colors": [
            "#FA9500",
            "#1a93ff", 
            "#6D72C3"
        ],
        "graphs": [{
            "fillAlphas": 1,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p7section3_label_1'); ?>",
            "labelPosition": "right",
            "labelRotation":0,
            "labelText": "[[val1]]",
            "type": "column",
            "valueField": "val1"
            }
        ],        
    });
    chartp7section3.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742220')
    });        

    var chartp7section4 = AmCharts.makeChart("p7section4", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
            "axisAlpha": 0,
            "autoGridCount": false,
            "gridPosition": "middle",
            "labelRotation": 45,
            "autoWrap":true, 
            "gridAlpha": 0.1,
            "gridPosition": "middle",
            "labelFrequency": 1,
            "tickLength": 0,
            "gridCount": 50,
            "maxSeries": 70,
            "labelFunction": function (label, item, axis) {
                if (label.length > 13)
                    return label.substr(0, 13) + '...';

                return label;
            }                
        },
        "colors": [
            "#6D72C3",
            "#1a93ff", 
            "#6D72C3",
            "#1b2353"
        ],
        "legend": false,
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p7section4_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        ],
        "dataProvider":p7section4
    });
    chartp7section4.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742234')
    });       

    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Create chart instance
        var chart = am4core.create("p7section5", am4charts.XYChart);
        chart.logo.height = -120;
        // Export
        chart.exporting.menu = new am4core.ExportMenu();

        // Data for both series
        var data = p7section5
        
        /* Create axes */
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "name";
        categoryAxis.renderer.minGridDistance = 30;

        /* Create value axis */
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        /* Create series */
        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "Income";
        columnSeries.dataFields.valueY = "val1";
        columnSeries.dataFields.categoryX = "name";

        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";

        var lineSeries = chart.series.push(new am4charts.LineSeries());
        lineSeries.name = "Expenses";
        lineSeries.dataFields.valueY = "val2";
        lineSeries.dataFields.categoryX = "name";

        lineSeries.stroke = am4core.color("#fdd400");
        lineSeries.strokeWidth = 3;
        lineSeries.propertyFields.strokeDasharray = "lineDash";
        lineSeries.tooltip.label.textAlign = "middle";

        var bullet = lineSeries.bullets.push(new am4charts.Bullet());
        bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
        bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        var circle = bullet.createChild(am4core.Circle);
        circle.radius = 4;
        circle.fill = am4core.color("#fff");
        circle.strokeWidth = 3;

        chart.data = data;

        columnSeries.columns.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453742248');
        });  
    }); 
    am4core.ready(function() {

        am4core.useTheme(am4themes_animated);

        var chart = am4core.create("p7section6", am4charts.PieChart);
            chart.data = p7section6;
            chart.logo.height = -60;
            chart.radius = am4core.percent(50);
        var pieSeries = chart.series.push(new am4charts.PieSeries());

            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";            

            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 1;
            pieSeries.slices.template.strokeOpacity = 1;

            // This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;
            
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453742262')
        });         

    }); 
    var chartp7section7 = AmCharts.makeChart("p7section7", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom"
        },
        "colors": [
            "#6D72C3",
            "#9b58b6", 
            "#6D72C3",
            "#1b2353"
        ],
        "legend": false,
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p7section7_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        ],
        "dataProvider":p7section7
    });
    chartp7section7.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742276')
    });       
    var chartp7section8 = AmCharts.makeChart("p7section8", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":10,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom",
            "labelRotation": 45,
        },
        "legend": false,
        "colors": [
            "#6D72C3",
            "#9b58b6", 
            "#6D72C3",
            "#1b2353"
        ],        
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p7section8_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1"
            }
        ],
        "dataProvider":p7section8,
    });
    chartp7section8.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742290')
    });     
    var chartp7section9 = AmCharts.makeChart("p7section9", {
        "type": "serial",
        "startDuration": 1,
        "theme": "light",
        "columnSpacing":0,
        "rotate": true,
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left"
        },
        "legend": false,
        "dataProvider": p7section9,
        "colors": [
            "#FA9500",
            "#1a93ff", 
            "#6D72C3"
        ],
    
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 1,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p7section9_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "[[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        ],
        
    });
    chartp7section9.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742304')
    });      
    var chartp7section10 = AmCharts.makeChart("p7section10", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
    	 	"axisAlpha": 0,
        	"autoGridCount": false,
            "gridPosition": "middle",
            "labelRotation": 35,
            "autoWrap":true, 
            "gridAlpha": 0.1,
	        "gridPosition": "middle",
	        "labelFrequency": 1,
	        "tickLength": 0,
	        "maxSeries": 70,
        },
        "colors": [
            "#6D72C3",
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
            "title": "<?php echo $this->lang->line('p7section10_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        ],
        "dataProvider":p7section10
    });
    chartp7section10.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742148')
    });      
    
    var chartp7section12 = AmCharts.makeChart("p7section12", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom",
            "labelRotation": 45,
            "labelFunction": function (label, item, axis) {
                if (label.length > 20)
                    return label.substr(0, 20) + '...';

                return label;
            }               
        },
        "colors": [
            "#6D72C3",
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
        "dataProvider":p7section12
    });
    chartp7section12.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742177')
    }); 

    var chartp7section12 = AmCharts.makeChart("p7section11", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom",
            "labelRotation": 45,
            "labelFunction": function (label, item, axis) {
                if (label.length > 20)
                    return label.substr(0, 20) + '...';

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
            "balloonText": "[[val1]] / [[value]]",
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
        "dataProvider":p7section11
    });
    chartp7section12.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742162')
    }); 
</script>