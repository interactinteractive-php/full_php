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
    
    chart.data = chartData.position5;

    /*chart.data = [
        {
            "title": "Afghanistan",
            "id": "AF",
            "color": "#eea638",
            "continent": "asia",
            "x": 1349.69694102398,
            "y": 60.524,
            "value": 33397058
        },
        {
            "title": "Albania",
            "id": "AL",
            "color": "#d8854f",
            "continent": "europe",
            "x": 6969.30628256456,
            "y": 77.185,
            "value": 3227373
        },
    ];*/
    
    var valueAxisX = chart.xAxes.push(new am4charts.ValueAxis());
    valueAxisX.renderer.ticks.template.disabled = true;
    valueAxisX.renderer.axisFills.template.disabled = true;

    var valueAxisY = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxisY.renderer.ticks.template.disabled = true;
    valueAxisY.renderer.axisFills.template.disabled = true;

    var series = chart.series.push(new am4charts.LineSeries());
    series.dataFields.valueX = 'position10';
    series.dataFields.valueY = 'position11';
    series.dataFields.value = 'position12';
    series.dataFields.id = 'position7';
    series.strokeOpacity = 0;
    series.sequencedInterpolation = true;
    series.tooltip.pointerOrientation = "vertical";

    var bullet = series.bullets.push(new am4core.Circle());
    bullet.fill = am4core.color("#ff0000");
    bullet.propertyFields.fill = 'position8';
    bullet.strokeOpacity = 0;
    bullet.strokeWidth = 2;
    bullet.fillOpacity = 0.5;
    bullet.stroke = am4core.color("#ffffff");
    bullet.hiddenState.properties.opacity = 0;
    bullet.tooltipText = "[bold]{position6}:[/]\n"+chartData.position2+": {value.value}\n"+chartData.position3+": {valueX.value}\n"+chartData.position4+":{valueY.value}";

    var outline = chart.plotContainer.createChild(am4core.Circle);
    outline.fillOpacity = 0;
    outline.strokeOpacity = 0.8;
    outline.stroke = am4core.color("#ff0000");
    outline.strokeWidth = 2;
    outline.hide(0);

    var blurFilter = new am4core.BlurFilter();
    outline.filters.push(blurFilter);

    bullet.events.on("over", function(event) {
        var target = event.target;
        outline.radius = target.pixelRadius + 2;
        outline.x = target.pixelX;
        outline.y = target.pixelY;
        outline.show();
    });

    bullet.events.on("out", function(event) {
        outline.hide();
    });

    var hoverState = bullet.states.create("hover");
    hoverState.properties.fillOpacity = 1;
    hoverState.properties.strokeOpacity = 1;

    series.heatRules.push({ target: bullet, min: 2, max: 60, property: "radius" });

    bullet.adapter.add("tooltipY", function (tooltipY, target) {
        return -target.radius;
    });

    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "zoomXY";
    chart.cursor.snapToSeries = series;

    chart.scrollbarX = new am4core.Scrollbar();
    chart.scrollbarY = new am4core.Scrollbar();
    
    // Гарчиг
    if (chartData.hasOwnProperty('position1') && chartData.position1) {
        
        var title = chart.titles.create();
        title.text = chartData.position1;
        title.fontSize = 20;
        title.marginBottom = 0;
    }

});
</script>

<div id="chartdiv_<?php echo $uniqId; ?>" class="pf_widget pf_widget_chart pf_widget_chart_zoomable_bubble"></div>
<?php
}
?>