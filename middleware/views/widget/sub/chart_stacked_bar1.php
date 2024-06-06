<?php
foreach ($this->widgetData as $row) {
    $uniqId = getUID();
?>

<style type="text/css">
#chartdiv_<?php echo $uniqId; ?> {
    width: 100%;
    height: <?php echo issetDefaultVal($this->configs['height'], '500px'); ?>;
}
</style>

<script type="text/javascript">    
am4core.ready(function() {
    
    am4core.unuseAllThemes();
    am4core.useTheme(am4themes_material);
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create('chartdiv_<?php echo $uniqId; ?>', am4charts.XYChart);
    var chartData = <?php echo json_encode($row, JSON_UNESCAPED_UNICODE); ?>;
    var barData = chartData.position2;
    var distinctBarData = [];
    var distinctPosition3 = [];
    var distinctSeries = [];
    
    for (var b in barData) {
        
        var groupField = barData[b]['position3'];
        
        if (!distinctPosition3.hasOwnProperty(groupField)) {
            
            distinctPosition3[groupField] = 1;  
            
            var combinedRow = {position3: groupField};
            
            for (var c in barData) {
                if (barData[b]['position3'] == barData[c]['position3']) {
                    combinedRow[barData[c]['position4']] = barData[c]['position5'];
                    distinctSeries[barData[c]['position4']] = 1;
                }
            }
            
            distinctBarData.push(combinedRow);
        } 
    }
    
    chart.data = distinctBarData;
    
    /*chart.data = [
        {
            "year": "2016",
            "europe": 2.5,
            "namerica": 2.5,
            "asia": 2.1,
            "lamerica": 0.3,
            "meast": 0.2,
            "africa": 0.1
        }, 
        {
            "year": "2017",
            "europe": 2.6,
            "namerica": 2.7,
            "asia": 2.2,
            "lamerica": 0.3,
            "meast": 0.3,
            "africa": 0.1
        }
    ]*/;

    chart.legend = new am4charts.Legend();
    chart.legend.position = "right";

    // Create axes
    var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "position3";
    categoryAxis.renderer.grid.template.opacity = 0;
    categoryAxis.renderer.minGridDistance = 20;

    var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    valueAxis.renderer.grid.template.opacity = 0;
    valueAxis.renderer.ticks.template.strokeOpacity = 0.5;
    valueAxis.renderer.ticks.template.stroke = am4core.color("#495C43");
    valueAxis.renderer.ticks.template.length = 10;
    valueAxis.renderer.line.strokeOpacity = 0.5;
    valueAxis.renderer.baseGrid.disabled = true;
    valueAxis.renderer.minGridDistance = 40;

    // Create series
    function createSeries(field, name) {
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueX = field;
        series.dataFields.categoryY = "position3";
        series.stacked = true;
        series.name = name;
        
        series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryY}: {valueX}";

        var labelBullet = series.bullets.push(new am4charts.LabelBullet());
        labelBullet.locationX = 0.5;
        labelBullet.label.text = "{valueX}";
        labelBullet.label.fill = am4core.color("#fff");
    }
    
    for (var s in distinctSeries) {
        createSeries(s, s);
    }
    
    // Гарчиг
    if (chartData.hasOwnProperty('position1') && chartData.position1) {
        
        var title = chart.titles.create();
        title.text = chartData.position1;
        title.fontSize = 20;
        title.marginTop = 0;
        title.marginBottom = 5;
    }

});
</script>

<div id="chartdiv_<?php echo $uniqId; ?>" class="pf_widget pf_widget_chart pf_widget_chart_stacked_bar"></div>
<?php
}
?>