<div class="content hr-relation1">
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
                foreach ($this->layoutPositionArr['relation1_1'] as $k => $data1) {
                    if (++$i == 9) break;
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
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p8section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p8section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                          <h6 class="mb-0"><?php echo $this->lang->line('p8section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section4" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p8section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section5" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row middle">
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p8section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section6" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p8section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section7" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                         <h6 class="mb-0"><?php echo $this->lang->line('p8section8'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section8" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                          <h6 class="mb-0"><?php echo $this->lang->line('p8section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section99" class="chart"> Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bottom">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                          <h6 class="mb-0"><?php echo $this->lang->line('p8section10'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section10" class="chart"></div>
                    </div> 
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                          <h6 class="mb-0"><?php echo $this->lang->line('p8section11'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section11" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                          <h6 class="mb-0"><?php echo $this->lang->line('p8section12'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section12" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                          <h6 class="mb-0"><?php echo $this->lang->line('p8section13'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p8section13" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end body -->
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">

    var p8section2 = <?php echo json_encode($this->layoutPositionArr['relation1_2']); ?>;
    var p8section3 = <?php echo json_encode($this->layoutPositionArr['relation1_3']); ?>;
    var p8section4 = <?php echo json_encode($this->layoutPositionArr['relation1_4']); ?>;
    var p8section5 = <?php echo json_encode($this->layoutPositionArr['relation1_5']); ?>;
    var p8section6 = <?php echo json_encode($this->layoutPositionArr['relation1_6']); ?>;
    var p8section7 = <?php echo json_encode($this->layoutPositionArr['relation1_7']); ?>;
    var p8section8 = <?php echo json_encode($this->layoutPositionArr['relation1_8']); ?>;
    var p8section9 = <?php echo json_encode($this->layoutPositionArr['relation1_9']); ?>;
    var p8section10 = <?php echo json_encode($this->layoutPositionArr['relation1_10']); ?>;
    var p8section11 = <?php echo json_encode($this->layoutPositionArr['relation1_11']); ?>;
    var p8section12 = <?php echo json_encode($this->layoutPositionArr['relation1_12']); ?>;
    var p8section13 = <?php echo json_encode($this->layoutPositionArr['relation1_13']); ?>;
 

    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p8section2", am4charts.PieChart);
      
        chart.logo.height = -120;
     
        chart.innerRadius = am4core.percent(0);
     
        chart.data = p8section2;
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453742975')
        });           
    });

    am4core.ready(function() {      
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p8section5", am4charts.PieChart);
      
        chart.logo.height = -120;
     
        chart.innerRadius = am4core.percent(0);
     
        chart.data = p8section5;
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
            drilldownMeta(ev.target.dataItem.dataContext, '1592453743018')
        });          
    });
    am4core.ready(function() {      
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p8section8", am4charts.PieChart);
      
        chart.logo.height = -120;
     
        chart.innerRadius = am4core.percent(0);
     
        chart.data = p8section8;
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
        
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453743061')
        });           
    });
    var chartp8section3 = AmCharts.makeChart("p8section3", {
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
        // "legend": {
        //     "horizontalGap": 10,
        //     "maxColumns": 3,
        //     "position": "bottom",
        //     "useGraphSettings": true,
        //     "markerSize": 10
        // },
        "dataProvider": p8section3,
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
            "title": "<?php echo $this->lang->line('p8section3_label_1'); ?>",
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
                "title": "<?php echo $this->lang->line('p8section3_label_2'); ?>",
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
                "title": "<?php echo $this->lang->line('p8section3_label_3'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val1]]",
                "type": "column",
                "valueField": "val3"
            },
        ],
        
    });
    chartp8section3.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453742990')
    });   
    
    var chartp8section9 = AmCharts.makeChart("p8section99", {
        "type": "serial",
        "startDuration": 1,
        "theme": "light",
        "columnSpacing":5,
        "rotate": false,
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 25,
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left"
        },
        // "legend": {
        //     "horizontalGap": 10,
        //     "maxColumns": 3,
        //     "position": "bottom",
        //     "useGraphSettings": true,
        //     "markerSize": 10
        // },
        "dataProvider": p8section9,
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
            "title": "<?php echo $this->lang->line('p8section3_label_1'); ?>",
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
                "title": "<?php echo $this->lang->line('p8section3_label_2'); ?>",
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
                "title": "<?php echo $this->lang->line('p8section3_label_3'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val1]]",
                "type": "column",
                "valueField": "val3"
            },
        ],
        
    });
    chartp8section9.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743075')
    });      

    var chartp8section4 = AmCharts.makeChart("p8section4", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "rotate": true,
        "columnSpacing":0,
        "theme": "light",
        "categoryAxis": {
            "position":"bottom"
        },
        "colors": [
            "#6D72C3",
            "#e53935", 
            "#6D72C3",
            "#1b2353"
        ],
        // "legend": {
        //     "horizontalGap": 10,
        //     "maxColumns": 4,
        //     "position": "bottom",
        //     "useGraphSettings": true,
        //     "markerSize": 10
        // },
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p8section4_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
        
        ],
        "dataProvider":p8section4
    });
    chartp8section4.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743004')
    });       

    var chartp8section6 = AmCharts.makeChart("p8section6", {
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
            "labelRotation": 25,
            "position": "left",
            "labelFunction": function (label, item, axis) {
                if (label.length > 18)
                    return label.substr(0, 18) + '...';

                return label;
            }             
        },
        // "legend": {
        //     "horizontalGap": 10,
        //     "maxColumns": 1,
        //     "position": "bottom",
        //     "useGraphSettings": true,
        //     "markerSize": 10
        // },
        "dataProvider": p8section6,
        "colors": [
            "#FA9500",
            "#e53935", 
            "#6D72C3"
        ],
    
        "graphs": [{
            "balloonText": "[[name]] / [[value]]",
            "fillAlphas": 1,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('p8section6_label_1'); ?>",
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
                "title": "<?php echo $this->lang->line('p8section6_label_2'); ?>",
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
                "title": "<?php echo $this->lang->line('p8section6_label_3'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[val1]]",
                "type": "column",
                "valueField": "val3"
            },
        ],
        
    });
    chartp8section6.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743032')
    });  

    var chartp8section7 = AmCharts.makeChart("p8section7", {
        "type": "serial",
        "startDuration": 1,
        "theme": "light",
        "columnSpacing":25,
        "rotate": true,
        "categoryField": "name",
        "categoryAxis": {
            "labelFunction": function (label, item, axis) {
                if (label.length > 15)
                    return label.substr(0, 15) + '...';

                return label;
            },     
            "position": "left"
        },
        // "legend": {
        //     "horizontalGap": 10,
        //     "maxColumns": 3,
        //     "position": "bottom",
        //     "useGraphSettings": true,
        //     "markerSize": 10
        // },
        "dataProvider": p8section7,
        "colors": [
            "#FA9500",
            "#e53935", 
            "#6D72C3"
        ],
    
        "graphs": [{
            "balloonText": "[[name]] / [[value]]",
            "fillAlphas": 1,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "name",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "[[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            },
            // {
            //     "balloonText": "[[val2]] / [[value]]",
            //     "fillAlphas": 1,
            //     "id": "AmGraph-2",
            //     "title": "name -4",
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
            //     "title": "name 3",
            //     "labelPosition": "top",
            //     "labelRotation":0,
            //     "labelText": "  [[val1]]",
            //     "type": "column",
            //     "valueField": "val3"
            // },
        ],
        
    });
    chartp8section7.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453743047')
    });      
    
    var data2 = groupBy(p8section10, 'category');
    var dataTemp1 = [];
    for(var key in data2){
        dataTemp1[key] = {};
        for(var i=0; i < data2[key].length; i++) {
            dataTemp1[key][data2[key][i]['name']] = Number(data2[key][i]['val1']);
        }
    }    
    
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p8section10", am4charts.XYChart);
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

                return categoryAxis.tooltipDataItem.dataContext.name;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {name}, {valueY}";
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
            var name = target.dataItem.dataContext.name;
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
            tempArray.push({ category: providerName + "_" + itemName, name: itemName, value: providerData[itemName], provider: providerName})
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
                legend.maxHeight = 80;
                legend.scrollable = true;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.name,
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
                
        /*
         * DrillDown
         */
        columnSeries.columns.template.events.on("hit", function(ev) {
            drilldownMeta(searchRow(p8section10, ev.target.dataItem.dataContext.name), '1592453742917');
        });                 

    });
    
    var data2 = groupBy(p8section11, 'category');
    var dataTemp1 = [];
    for(var key in data2){
        dataTemp1[key] = {};
        for(var i=0; i < data2[key].length; i++) {
            dataTemp1[key][data2[key][i]['name']] = Number(data2[key][i]['val1']);
        }
    }    
    
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p8section11", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();
            chart.colors.list = [
              am4core.color("#ea2c62"),
              am4core.color("#61b15a"),
              am4core.color("#8db596"),
              am4core.color("#965d62"),
              am4core.color("#ffd369"),
              am4core.color("#393e46")
            ];            

            // will use this to store colors of the same items
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){

                return categoryAxis.tooltipDataItem.dataContext.name;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {name}, {valueY}";
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
            var name = target.dataItem.dataContext.name;
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
            tempArray.push({ category: providerName + "_" + itemName, name: itemName, value: providerData[itemName], provider: providerName})
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
                legend.maxHeight = 80;
                legend.scrollable = true;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.name,
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
                
        /*
         * DrillDown
         */
        columnSeries.columns.template.events.on("hit", function(ev) {
            drilldownMeta(searchRow(p8section10, ev.target.dataItem.dataContext.name), '1592453742931');
        });                 

    });    
    
    var data2 = groupBy(p8section12, 'category');
    var dataTemp1 = [];
    for(var key in data2){
        dataTemp1[key] = {};
        for(var i=0; i < data2[key].length; i++) {
            dataTemp1[key][data2[key][i]['name']] = Number(data2[key][i]['val1']);
        }
    }    
    
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p8section12", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            chart.colors.list = [
              am4core.color("#59886b"),
              am4core.color("#c05555"),
              am4core.color("#ffc85c"),
              am4core.color("#d35d6e"),
              am4core.color("#efb08c"),
              am4core.color("#f8d49d")
            ];               
            //chart.scrollbarX = new am4core.Scrollbar();

            // will use this to store colors of the same items
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){

                return categoryAxis.tooltipDataItem.dataContext.name;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {name}, {valueY}";
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
            var name = target.dataItem.dataContext.name;
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
            tempArray.push({ category: providerName + "_" + itemName, name: itemName, value: providerData[itemName], provider: providerName})
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
                legend.maxHeight = 80;
                legend.scrollable = true;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.name,
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
                
        /*
         * DrillDown
         */
        columnSeries.columns.template.events.on("hit", function(ev) {
            drilldownMeta(searchRow(p8section12, ev.target.dataItem.dataContext.name), '1592453742945');
        });                 

    });
    
    var data2 = groupBy(p8section13, 'category');
    var dataTemp1 = [];
    for(var key in data2){
        dataTemp1[key] = {};
        for(var i=0; i < data2[key].length; i++) {
            dataTemp1[key][data2[key][i]['name']] = Number(data2[key][i]['val1']);
        }
    }    
    
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p8section13", am4charts.XYChart);
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

                return categoryAxis.tooltipDataItem.dataContext.name;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {name}, {valueY}";
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
            var name = target.dataItem.dataContext.name;
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
            tempArray.push({ category: providerName + "_" + itemName, name: itemName, value: providerData[itemName], provider: providerName})
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
                legend.maxHeight = 80;
                legend.scrollable = true;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.name,
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
                
        /*
         * DrillDown
         */
        columnSeries.columns.template.events.on("hit", function(ev) {
            drilldownMeta(searchRow(p8section13, ev.target.dataItem.dataContext.name), '1592453742959');
        });                 

    });
    
    
</script>