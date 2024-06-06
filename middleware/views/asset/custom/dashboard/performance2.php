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
                foreach ($this->layoutPositionArr['main1_1'] as $k => $data1) {
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
            <div class="col-md-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p1performance2_3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p1section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p1performance2_3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p1section3" class="chart"></div>
                    </div>
                </div>
            </div>          
        </div>
        <div class="row middle">
            <div class="col-sm-2 col-md-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p1performance2_4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p1section5" class="chart"></div>
                    </div>
                </div>
            </div>            
            <div class="col-sm-2 col-md-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p1performance2_5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p1section6" class="chart">Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p1performance2_6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p1section7" class="chart">Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
        </div>     
        <!-- end body -->
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">
    var p1section2 = <?php echo json_encode($this->layoutPositionArr['main1_2']); ?>;
    var p1section3 = <?php echo json_encode($this->layoutPositionArr['main1_3']); ?>;
    var p1section5 = <?php echo json_encode($this->layoutPositionArr['main1_5']); ?>;
    var p1section6 = <?php echo json_encode($this->layoutPositionArr['main1_6']); ?>;
    var p1section7 = <?php echo json_encode($this->layoutPositionArr['main1_7']); ?>;

    var data22 = groupBy(p1section5, 'category');
    var dataTemp4 = [];
    for(var key in data22){
        for(var i=0; i < data22[key].length; i++) {
            if (!dataTemp4[i]) {
                dataTemp4[i] = {};
            }            
            dataTemp4[i][key] = data22[key][i]['val1'];
            dataTemp4[i]['name'] = data22[key][i]['name'];
        }
    }    

    var data22 = groupBy(p1section6, 'category');
    var dataTemp5 = [];
    for(var key in data22){
        for(var i=0; i < data22[key].length; i++) {
            if (!dataTemp5[i]) {
              dataTemp5[i] = {};
            }            
            dataTemp5[i][key] = data22[key][i]['val1'];
            dataTemp5[i]['name'] = data22[key][i]['name'];
        }
    }    

    var data22 = groupBy(p1section7, 'category');
    var dataTemp6 = [];
    for(var key in data22){
        for(var i=0; i < data22[key].length; i++) {
            if (!dataTemp6[i]) {
              dataTemp6[i] = {};
            }            
            dataTemp6[i][key] = data22[key][i]['val1'];
            dataTemp6[i]['name'] = data22[key][i]['name'];
        }
    }    
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end


    // Create chart instance
    var chart = am4core.create("p1section5", am4charts.XYChart);

    // Add data
    chart.data = dataTemp4;
    chart.colors.list = [
      am4core.color("#cd5d7d"),
      am4core.color("#ffd66b"),
      am4core.color("#944e6c"),
      am4core.color("#70af85"),
      am4core.color("#fa9579"),
      am4core.color("#65d6ce")
    ];        

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";
    categoryAxis.renderer.opposite = false;
    categoryAxis.renderer.minGridDistance = 30;

    // Create value axis
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.inversed = false;
    valueAxis.title.text = "";
    valueAxis.renderer.minLabelPosition = 0.01;

    for (var key in data22) {
        // Create series
        var series1 = chart.series.push(new am4charts.LineSeries());
        series1.dataFields.valueY = key;
        series1.dataFields.categoryX = "name";
        series1.name = key;
        series1.bullets.push(new am4charts.CircleBullet());
        series1.tooltipText = "{name}: {valueY}";
        series1.legendSettings.valueText = "{valueY}";
        series1.visible  = false;
        
        let hs1 = series1.segments.template.states.create("hover")
        hs1.properties.strokeWidth = 5;
        series1.segments.template.strokeWidth = 1;        
    }

    // Add chart cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "zoomY";

    // Add legend
    chart.legend = new am4charts.Legend();
    chart.legend.itemContainers.template.events.on("over", function(event){
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function(segment){
        segment.isHover = true;
      })
    })

    chart.legend.itemContainers.template.events.on("out", function(event){
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function(segment){
        segment.isHover = false;
      })
    })
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end


    // Create chart instance
    var chart = am4core.create("p1section6", am4charts.XYChart);

    // Add data
    chart.data = dataTemp5;
    chart.colors.list = [
      am4core.color("#944e6c"),
      am4core.color("#70af85"),
      am4core.color("#fa9579"),        
      am4core.color("#cd5d7d"),
      am4core.color("#ffd66b"),
      am4core.color("#65d6ce")
    ];        

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";
    categoryAxis.renderer.opposite = false;
    categoryAxis.renderer.minGridDistance = 30;

    // Create value axis
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.inversed = false;
    valueAxis.title.text = "";
    valueAxis.renderer.minLabelPosition = 0.01;

    for (var key in data22) {
        // Create series
        var series1 = chart.series.push(new am4charts.LineSeries());
        series1.dataFields.valueY = key;
        series1.dataFields.categoryX = "name";
        series1.name = key;
        series1.bullets.push(new am4charts.CircleBullet());
        series1.tooltipText = "{name}: {valueY}";
        series1.legendSettings.valueText = "{valueY}";
        series1.visible  = false;
        
        let hs1 = series1.segments.template.states.create("hover")
        hs1.properties.strokeWidth = 5;
        series1.segments.template.strokeWidth = 1;        
    }

    // Add chart cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "zoomY";

    // Add legend
    chart.legend = new am4charts.Legend();
    chart.legend.itemContainers.template.events.on("over", function(event){
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function(segment){
        segment.isHover = true;
      })
    })

    chart.legend.itemContainers.template.events.on("out", function(event){
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function(segment){
        segment.isHover = false;
      })
    })
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end


    // Create chart instance
    var chart = am4core.create("p1section7", am4charts.XYChart);

    // Add data
    chart.data = dataTemp6;
    chart.colors.list = [
      am4core.color("#70af85"),
      am4core.color("#cd5d7d"),
      am4core.color("#65d6ce"),
      am4core.color("#ffd66b"),
      am4core.color("#944e6c"),      
      am4core.color("#fa9579"),      
    ];        

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";
    categoryAxis.renderer.opposite = false;
    categoryAxis.renderer.minGridDistance = 30;

    // Create value axis
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.inversed = false;
    valueAxis.title.text = "";
    valueAxis.renderer.minLabelPosition = 0.01;

    for (var key in data22) {
        // Create series
        var series1 = chart.series.push(new am4charts.LineSeries());
        series1.dataFields.valueY = key;
        series1.dataFields.categoryX = "name";
        series1.name = key;
        series1.bullets.push(new am4charts.CircleBullet());
        series1.tooltipText = "{name}: {valueY}";
        series1.legendSettings.valueText = "{valueY}";
        series1.visible  = false;
        
        let hs1 = series1.segments.template.states.create("hover")
        hs1.properties.strokeWidth = 5;
        series1.segments.template.strokeWidth = 1;        
    }

    // Add chart cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "zoomY";

    // Add legend
    chart.legend = new am4charts.Legend();
    chart.legend.itemContainers.template.events.on("over", function(event){
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function(segment){
        segment.isHover = true;
      })
    })

    chart.legend.itemContainers.template.events.on("out", function(event){
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function(segment){
        segment.isHover = false;
      })
    })
    

    var chart = am4core.create("p1section3", am4charts.RadarChart);

    /* Add data */
    chart.data = p1section3;
    chart.colors.list = [
      am4core.color("#845EC2"),
      am4core.color("#D65DB1"),
      am4core.color("#FF6F91"),
      am4core.color("#FF9671"),
      am4core.color("#FFC75F"),
      am4core.color("#F9F871")
    ];    

    /* Create axes */
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.axisFills.template.fill = chart.colors.getIndex(2);
    valueAxis.renderer.axisFills.template.fillOpacity = 0.05;

    /* Create and configure series */
    var series = chart.series.push(new am4charts.RadarSeries());
    series.dataFields.valueY = "val1";
    series.dataFields.categoryX = "name";
    series.name = "<?php echo $this->lang->line('performancechart1val1'); ?>";
    series.strokeWidth = 3;    
    
    var series = chart.series.push(new am4charts.RadarSeries());
    series.dataFields.valueY = "val2";
    series.dataFields.categoryX = "name";
    series.name = "<?php echo $this->lang->line('performancechart1val2'); ?>";
    series.strokeWidth = 3;    
    
    var series = chart.series.push(new am4charts.RadarSeries());
    series.dataFields.valueY = "val3";
    series.dataFields.categoryX = "name";
    series.name = "<?php echo $this->lang->line('performancechart1val3'); ?>";
    series.strokeWidth = 3;    
    
    var series = chart.series.push(new am4charts.RadarSeries());
    series.dataFields.valueY = "val4";
    series.dataFields.categoryX = "name";
    series.name = "<?php echo $this->lang->line('performancechart1val4'); ?>";
    series.strokeWidth = 3;    
    
    var series = chart.series.push(new am4charts.RadarSeries());
    series.dataFields.valueY = "val5";
    series.dataFields.categoryX = "name";
    series.name = "<?php echo $this->lang->line('performancechart1val5'); ?>";
    series.strokeWidth = 3;    
    
    chart.legend = new am4charts.Legend();
         

    //p1section2
    am4core.useTheme(am4themes_animated);
    // Themes end

    var chart = am4core.create("p1section2", am4charts.XYChart);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chart.data = p1section2;

    chart.colors.step = 2;
    chart.padding(30, 30, 10, 30);
    chart.legend = new am4charts.Legend();

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";
    categoryAxis.renderer.minGridDistance = 30;
    categoryAxis.renderer.grid.template.location = 0;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    valueAxis.max = 100;
    valueAxis.strictMinMax = true;
    valueAxis.calculateTotals = true;
    valueAxis.renderer.minWidth = 50;


    var series1 = chart.series.push(new am4charts.ColumnSeries());
    series1.columns.template.width = am4core.percent(80);
    series1.columns.template.tooltipText =
      "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
    series1.name = "<?php echo $this->lang->line('performancechart1val1'); ?>";
    series1.dataFields.categoryX = "name";
    series1.dataFields.valueY = "val1";
    series1.dataFields.valueYShow = "totalPercent";
    series1.dataItems.template.locations.categoryX = 0.5;
    series1.stacked = true;
    series1.tooltip.pointerOrientation = "vertical";

    var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
    bullet1.interactionsEnabled = false;
    bullet1.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
    bullet1.label.fill = am4core.color("#ffffff");
    bullet1.locationY = 0.5;

    var series2 = chart.series.push(new am4charts.ColumnSeries());
    series2.columns.template.width = am4core.percent(80);
    series2.columns.template.tooltipText =
      "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
    series2.name = "<?php echo $this->lang->line('performancechart1val2'); ?>";
    series2.dataFields.categoryX = "name";
    series2.dataFields.valueY = "val2";
    series2.dataFields.valueYShow = "totalPercent";
    series2.dataItems.template.locations.categoryX = 0.5;
    series2.stacked = true;
    series2.tooltip.pointerOrientation = "vertical";

    var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
    bullet2.interactionsEnabled = false;
    bullet2.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
    bullet2.locationY = 0.5;
    bullet2.label.fill = am4core.color("#ffffff");

    var series3 = chart.series.push(new am4charts.ColumnSeries());
    series3.columns.template.width = am4core.percent(80);
    series3.columns.template.tooltipText =
      "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
    series3.name = "<?php echo $this->lang->line('performancechart1val3'); ?>";
    series3.dataFields.categoryX = "name";
    series3.dataFields.valueY = "val3";
    series3.dataFields.valueYShow = "totalPercent";
    series3.dataItems.template.locations.categoryX = 0.5;
    series3.stacked = true;
    series3.tooltip.pointerOrientation = "vertical";

    var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
    bullet3.interactionsEnabled = false;
    bullet3.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
    bullet3.locationY = 0.5;
    bullet3.label.fill = am4core.color("#ffffff");    

    var series3 = chart.series.push(new am4charts.ColumnSeries());
    series3.columns.template.width = am4core.percent(80);
    series3.columns.template.tooltipText =
      "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
    series3.name = "<?php echo $this->lang->line('performancechart1val4'); ?>";
    series3.dataFields.categoryX = "name";
    series3.dataFields.valueY = "val4";
    series3.dataFields.valueYShow = "totalPercent";
    series3.dataItems.template.locations.categoryX = 0.5;
    series3.stacked = true;
    series3.tooltip.pointerOrientation = "vertical";

    var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
    bullet3.interactionsEnabled = false;
    bullet3.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
    bullet3.locationY = 0.5;
    bullet3.label.fill = am4core.color("#ffffff");    

    var series3 = chart.series.push(new am4charts.ColumnSeries());
    series3.columns.template.width = am4core.percent(80);
    series3.columns.template.tooltipText =
      "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
    series3.name = "<?php echo $this->lang->line('performancechart1val5'); ?>";
    series3.dataFields.categoryX = "name";
    series3.dataFields.valueY = "val5";
    series3.dataFields.valueYShow = "totalPercent";
    series3.dataItems.template.locations.categoryX = 0.5;
    series3.stacked = true;
    series3.tooltip.pointerOrientation = "vertical";

    var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
    bullet3.interactionsEnabled = false;
    bullet3.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
    bullet3.locationY = 0.5;
    bullet3.label.fill = am4core.color("#ffffff");    

</script>