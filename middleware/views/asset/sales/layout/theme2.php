<div class="cloud sales_theme_dashboard">
    <div class="w-100 pt8">
        <div class="row">
            <div class="col-md-8 col-lg-8">
                <div class="row align-items-center topcharts">
                    <div class="col-3">
                        <div class="card-box tcard ">
                            <h3>Total Accounts Receivable</h3>
                            <p>$333333</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card-box tcard">
                            <h3>Total Accounts Receivable</h3>
                            <p>$333333</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card-box tcard">
                            <h3>Total Accounts Receivable</h3>
                            <p>$333333</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card-box tcard">
                            <h3>Total Accounts Receivable</h3>
                            <p>$333333</p>
                        </div>
                    </div>
                </div>
                <div class="w-100 align-items-end mt10 topchart1">
                    <div class="row card-box" style="margin:0 -5px">
                        <div class="col-md-6"><div class="bchart" id="schart1">schart1</div> </div>
                        <div class="col-md-6"><div class="bchart" id="schart2">sd</div> </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box">
                    <h3>chart title</h3>
                    <div id="chart5" class="rchart"></div>
                </div>
            </div>
            
        </div>
        <div class="row mt10">
            <div class="col-md-6">
                <div class="card-box">
                    <h3>chart title</h3>
                    <div class="btchart" id="chart6">chart1</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-box">
                    <h3>chart title</h3>
                    <div class=" btchart" id="chart7">chart1</div>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="<?php echo autoVersion('middleware/assets/css/scss/hr-main.css'); ?>" rel="stylesheet"/> 

<script type="text/javascript">


    var chart7 = <?php echo json_encode($this->dataGroup7); ?>;
    console.log(chart7);
    // chart6
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create("chart6", am4charts.XYChart);
    chart.logo.height = -220;
    chart.colors.step = 2;
    // Add data
    chart.data = generateChartData();
    // Create axes
    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.minGridDistance = 50;

    // Create series
    function createAxisAndSeries(field, name, opposite, bullet) {
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        if(chart.yAxes.indexOf(valueAxis) != 0){
            valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
        }
        
        var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = field;
            series.dataFields.dateX = "date";
            series.strokeWidth = 2;
            series.yAxis = valueAxis;
            series.name = name;
            series.tooltipText = "{name}: [bold]{valueY}[/]";
            series.tensionX = 0.8;
            series.showOnInit = true;
        
        var interfaceColors = new am4core.InterfaceColorSet();
        
        switch(bullet) {
            case "triangle":
            var bullet = series.bullets.push(new am4charts.Bullet());
                bullet.width = 12;
                bullet.height = 12;
                bullet.horizontalCenter = "middle";
                bullet.verticalCenter = "middle";
                
            var triangle = bullet.createChild(am4core.Triangle);
                triangle.stroke = interfaceColors.getFor("background");
                triangle.strokeWidth = 2;
                triangle.direction = "top";
                triangle.width = 12;
                triangle.height = 12;
            break;
            case "rectangle":
            var bullet = series.bullets.push(new am4charts.Bullet());
            bullet.width = 10;
            bullet.height = 10;
            bullet.horizontalCenter = "middle";
            bullet.verticalCenter = "middle";
            
            var rectangle = bullet.createChild(am4core.Rectangle);
            rectangle.stroke = interfaceColors.getFor("background");
            rectangle.strokeWidth = 2;
            rectangle.width = 10;
            rectangle.height = 10;
            break;
            default:
            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.stroke = interfaceColors.getFor("background");
            bullet.circle.strokeWidth = 2;
            break;
        }
        
        valueAxis.renderer.line.strokeOpacity = 1;
        valueAxis.renderer.line.strokeWidth = 2;
        valueAxis.renderer.line.stroke = series.stroke;
        valueAxis.renderer.labels.template.fill = series.stroke;
        valueAxis.renderer.opposite = opposite;
    }

    createAxisAndSeries("visits", "Visits", false, "circle");
    createAxisAndSeries("views", "Views", true, "triangle");
    createAxisAndSeries("hits", "Hits", true, "rectangle");

    // Add legend
    chart.legend = new am4charts.Legend();

    // Add cursor
    chart.cursor = new am4charts.XYCursor();

    function generateChartData() {
        var chartData = [];
        var firstDate = new Date();
        firstDate.setDate(firstDate.getDate() - 100);
        firstDate.setHours(0, 0, 0, 0);

        var visits = 1600;
        var hits = 2900;
        var views = 8700;

        for (var i = 0; i < 15; i++) {
            var newDate = new Date(firstDate);

            newDate.setDate(newDate.getDate() + i);
            visits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
            hits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
            views += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

            chartData.push({
            date: newDate,
            visits: visits,
            hits: hits,
            views: views
            });
        }
        return chartData;
    }

    // chart5
    am4core.ready(function() {

        am4core.useTheme(am4themes_animated);
     
        var chart = am4core.create('chart5', am4charts.XYChart)
            chart.colors.step = 2;
            chart.legend = new am4charts.Legend()
            chart.legend.position = 'bottom'
            chart.legend.paddingBottom = 5
            chart.legend.labels.template.maxWidth = 95

        var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
            xAxis.dataFields.category = 'category'
            xAxis.renderer.cellStartLocation = 0.1
            xAxis.renderer.cellEndLocation = 0.9
            xAxis.renderer.grid.template.location = 0;

        var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
            yAxis.min = 0;

        function createSeries(value, name) {
            var series = chart.series.push(new am4charts.ColumnSeries())
            series.dataFields.valueY = value
            series.dataFields.categoryX = 'category'
            series.name = name

            series.events.on("hidden", arrangeColumns);
            series.events.on("shown", arrangeColumns);

            var bullet = series.bullets.push(new am4charts.LabelBullet())
            bullet.interactionsEnabled = false
            bullet.dy = 30;
            bullet.label.text = '{valueY}'
            bullet.label.fill = am4core.color('#ffffff')

            return series;
        }

        chart.data = [
            {
                category: 'Place #1',
                first: 40,
                second: 55,
                third: 60
            },
            {
                category: 'Place #2',
                first: 30,
                second: 78,
                third: 69
            },
        ]


        createSeries('first', 'The Thirst');
        createSeries('second', 'The Second');
        // createSeries('third', 'The Third');

        function arrangeColumns() {
            var series = chart.series.getIndex(0);
            var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
            if (series.dataItems.length > 1) {
                var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
                var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
                var delta = ((x1 - x0) / chart.series.length) * w;
                if (am4core.isNumber(delta)) {
                    var middle = chart.series.length / 2;

                    var newIndex = 0;
                    chart.series.each(function(series) {
                        if (!series.isHidden && !series.isHiding) {
                            series.dummyData = newIndex;
                            newIndex++;
                        }
                        else {
                            series.dummyData = chart.series.indexOf(series);
                        }
                    })
                    var visibleCount = newIndex;
                    var newMiddle = visibleCount / 2;

                    chart.series.each(function(series) {
                        var trueIndex = chart.series.indexOf(series);
                        var newIndex = series.dummyData;

                        var dx = (newIndex - trueIndex + middle - newMiddle) * delta

                        series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                        series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                    })
                }
            }
        }
    }); 

    var dataTemp = [];

    for(var key in chart7){
        dataTemp[key] = {};
       
        for(var i=0; i < chart7[key].length; i++) {
            dataTemp[key][chart7[key][i]['expense']] = Number(chart7[key][i]['expense']);
            dataTemp[key][chart7[key][i]['income']] = Number(chart7[key][i]['income']);
            dataTemp[key][chart7[key][i]['cogs']] = Number(chart7[key][i]['cogs']);
            dataTemp[key]['result'] = Number(chart7[key][i]['result']);
        }
    }
    console.log(dataTemp);
    //chart 7
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create("chart7", am4charts.XYChart);
      
        chart.paddingBottom = 50;
        chart.logo.height = -220;

        chart.cursor = new am4charts.XYCursor();
        var colors = {};
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.minGridDistance = 20;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.dataItems.template.text = "";
            categoryAxis.adapter.add("tooltipText", function(tooltipText, target){
            return categoryAxis.tooltipDataItem.dataContext.realName;
        })

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.tooltip.disabled = true;
            valueAxis.min = 0;

        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
            columnSeries.columns.template.width = am4core.percent(80);
            columnSeries.tooltipText = "{provider}: {realName}";
            columnSeries.dataFields.categoryX = "category";
            columnSeries.dataFields.valueY = "value";


        var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis2.renderer.opposite = true;
            valueAxis2.syncWithAxis = valueAxis;
            valueAxis2.tooltip.disabled = true;
           
        // result line series
        var lineSeries = chart.series.push(new am4charts.LineSeries());
            lineSeries.tooltipText = "{valueY}";
            lineSeries.dataFields.categoryX = "category";
            lineSeries.dataFields.valueY = "result";
            lineSeries.yAxis = valueAxis2;
            lineSeries.bullets.push(new am4charts.CircleBullet());
            lineSeries.stroke = chart.colors.getIndex(13);
            lineSeries.fill = lineSeries.stroke;
            lineSeries.strokeWidth = 1;
            lineSeries.snapTooltip = true;

        // when data validated, adjust location of data item based on count
        lineSeries.events.on("datavalidated", function(){
        lineSeries.dataItems.each(function(dataItem){
            // if count divides by two, location is 0 (on the grid)
            if(dataItem.dataContext.count / 2 == Math.round(dataItem.dataContext.count / 2)){
            dataItem.setLocation("categoryX", 0);
            }
            else{
                dataItem.setLocation("categoryX", 0.5);
            }
            })
        })

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

            var data = dataTemp
           

            for (var providerName in data) {
                var providerData = data[providerName];

                var tempArray = [];
                var count = 0;
                // add items
                for (var itemName in providerData) {
                    if(itemName != "result"){
                    count++;
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

                // add result and count to middle data item (line series uses it)
                var lineSeriesDataIndex = Math.floor(count / 2);
                    tempArray[lineSeriesDataIndex].result = providerData.result;
                    tempArray[lineSeriesDataIndex].count = count;
                
                am4core.array.each(tempArray, function(item) {
                    chartData.push(item);
                })

                
                var range = categoryAxis.axisRanges.create();
                    range.category = tempArray[0].category;
                    range.endCategory = tempArray[tempArray.length - 1].category;
                    range.label.text = tempArray[0].provider;
                    range.label.dy = 0;
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
            legend.marginTop = 5;
            columnSeries.events.on("ready", function(ev) {
            var legenddata = [];
            columnSeries.columns.each(function(column,key) {
                if(key < count ){
                    legenddata.push({
                    name: '<?php echo $this->lang->line('salestheme_chart7'); ?>'+ key,
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

</script>