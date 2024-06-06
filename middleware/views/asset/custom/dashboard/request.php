<div class="content">
    <div class="container-fluid">
        <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
       
        <!-- body -->
        <div class="row">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section1'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section1" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                    <h6 class="mb-0"><?php echo $this->lang->line('p5section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section2" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section4" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section5" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section6" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section7'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section7" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section8'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section8" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section9" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section10'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section10" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section11'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section11" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section12'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section12" class="chart"></div>
                    </div>
                </div>
            </div>
           
        </div>
        <div class="row">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section13'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section13" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('p5section14'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p5section14" class="chart"></div>
                    </div>
                </div>
            </div>
         
        </div>
        
        <!-- end body -->
   
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">

    var p5section1 = <?php echo json_encode($this->layoutPositionArr['req_1']); ?>;
    var p5section2 = <?php echo json_encode($this->layoutPositionArr['req_2']); ?>;
    var p5section3 = <?php echo json_encode($this->layoutPositionArr['req_3']); ?>;
    var p5section4 = <?php echo json_encode($this->layoutPositionArr['req_4']); ?>;
    var p5section5 = <?php echo json_encode($this->layoutPositionArr['req_5']); ?>;
    var p5section6 = <?php echo json_encode($this->layoutPositionArr['req_6']); ?>;
    var p5section7 = <?php echo json_encode($this->layoutPositionArr['req_7']); ?>;
    var p5section8 = <?php echo json_encode($this->layoutPositionArr['req_8']); ?>;
    var p5section9 = <?php echo json_encode($this->layoutPositionArr['req_9']); ?>;
    var p5section10 = <?php echo json_encode($this->layoutPositionArr['req_10']); ?>;
    var p5section11 = <?php echo json_encode($this->layoutPositionArr['req_11']); ?>;
    var p5section12 = <?php echo json_encode($this->layoutPositionArr['req_12']); ?>;
    var p5section13 = <?php echo json_encode($this->layoutPositionArr['req_13']); ?>;
    var p5section14 = <?php echo json_encode($this->layoutPositionArr['req_14']); ?>;
 
    // p5section1
    am4core.ready(function() {

        am4core.useTheme(am4themes_animated);
       
        var chart = am4core.create("p5section1", am4charts.PieChart);
            chart.data =p5section1;
            chart.logo.height = -60;
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
            
            /*
             * DrillDown
             */
            pieSeries.slices.template.events.on("hit", function(ev) {
                drilldownMeta(ev.target.dataItem.dataContext, '1592453740588')
            });            

    }); 
    // p5section2
    am4core.ready(function() {

        am4core.useTheme(am4themes_animated);
       
        var chart = am4core.create("p5section2", am4charts.PieChart);
            chart.data = p5section2;
            chart.logo.height = -60;
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
            
            /*
             * DrillDown
             */
            pieSeries.slices.template.events.on("hit", function(ev) {
                drilldownMeta(ev.target.dataItem.dataContext, '1592453740658')
            });                 
    }); 
    // p5section3
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p5section3", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(0);
        chart.data = p5section3;
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
            chart.legend.position = "right";
            
            /*
             * DrillDown
             */
            pieSeries.slices.template.events.on("hit", function(ev) {
                drilldownMeta(ev.target.dataItem.dataContext, '1592453740673')
            });                 
    });
    // p5section4
    var chartp5section4 = AmCharts.makeChart("p5section4", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left"
        },
        "colors": [
            "#6794dc",
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
        "dataProvider": p5section4
    });
    chartp5section4.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453740687')
    });     

    // p5section5
    var chartp5section5 = AmCharts.makeChart("p5section5", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": false,
        "categoryAxis": {
            "position":"bottom"
        },
        "colors": [
            "#6794dc",
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
        "dataProvider": p5section5,
    });
    chartp5section5.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453740701')
    });    

    // p5section6
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p5section6", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(35);
        chart.data = p5section6;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";

        var colors = ["#00FF00, #303F9F, #E53935","#C2185B","#7B1FA2","#512DA8"];
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
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453740716')
        });             
    });
    //p5section7
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p5section7", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(35);
        chart.data = p5section7;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";

        var colors = ["#512DA8","#303F9F","#7B1FA2","#512DA8","#303F9F","#00FF00"];
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
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453740730')
        });              
    });

    //p5section8
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p5section8", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(35);
        chart.data = p5section8;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";

        var colors = ["#512DA8","#303F9F","#7B1FA2","#512DA8","#303F9F","#00FF00"];
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
            
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453740744')
        });                
    });
    //p5section9

    var chartp5section9 = AmCharts.makeChart("p5section9", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": false,
        "categoryAxis": {
            "position":"left",
            "labelRotation": 35,
        },
        "colors": [
            "#6794dc",
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
        "dataProvider": p5section9
    });
    chartp5section9.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453740758')
    });       

    var chartp5section10 = AmCharts.makeChart("p5section10", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"bottom"
        },
        "colors": [
            "#6794dc",
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
        "dataProvider":p5section10
    });
    chartp5section10.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453740602')
    });           
    
    //p5section11
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p5section11", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(35);
        chart.data = p5section11;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";

        var colors = ["#00FF00, #303F9F, #E53935","#C2185B","#7B1FA2","#512DA8"];
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
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453740616')
        });                
    });
    //p5section12
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p5section12", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(0);
        chart.data = p5section12;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "val1";
            pieSeries.dataFields.category = "name";

        var colors = ["#00FF00","#303F9F","#7B1FA2","#512DA8","#303F9F","#00FF00"];

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
        /*
         * DrillDown
         */
        pieSeries.slices.template.events.on("hit", function(ev) {
            drilldownMeta(ev.target.dataItem.dataContext, '1592453740630')
        });              
    });
    //p5section13
    var chartp5section13 = AmCharts.makeChart("p5section13", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left"
        },
        "colors": [
            "#6794dc",
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
        "dataProvider": p5section13
    });
    chartp5section13.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453740644')
    });       
    var chartp5section14 = AmCharts.makeChart("p5section14", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": false,
        "categoryAxis": {
            "position":"bottom"
        },
        "colors": [
            "#6794dc",
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
        "dataProvider": p5section14
    });
    chartp5section14.addListener("clickGraphItem", function (event) {
        drilldownMeta(event.item.dataContext, '1592453741366')
    });      
    

</script>