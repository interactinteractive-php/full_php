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

    am4core.useTheme(am4themes_animated);

    var chartMin = -50;
    var chartMax = 100;
    
    var data = <?php echo json_encode($row, JSON_UNESCAPED_UNICODE); ?>;
    
    /*var data = {
        score: 52.7,
        gradingData: [
            {
                title: "Unsustainable",
                color: "#ee1f25",
                lowScore: -100,
                highScore: -20
            },
            {
                title: "Volatile",
                color: "#f04922",
                lowScore: -20,
                highScore: 0
            },
            {
                title: "Foundational",
                color: "#fdae19",
                lowScore: 0,
                highScore: 20
            },
            {
                title: "Developing",
                color: "#f3eb0c",
                lowScore: 20,
                highScore: 40
            },
            {
                title: "Maturing",
                color: "#b0d136",
                lowScore: 40,
                highScore: 60
            },
            {
                title: "Sustainable",
                color: "#54b947",
                lowScore: 60,
                highScore: 80
            },
            {
                title: "High Performing",
                color: "#0f9747",
                lowScore: 80,
                highScore: 100
            }
        ]
    };*/

    /**
    Grading Lookup
     */
    function lookUpGrade(lookupScore, grades) {
        // Only change code below this line
        for (var i = 0; i < grades.length; i++) {
            if (
              Number(grades[i].position6) < lookupScore &&
              Number(grades[i].position7) >= lookupScore
            ) {
                return grades[i];
            }
        }
        return '';
    }

    // create chart
    var chart = am4core.create('chartdiv_<?php echo $uniqId; ?>', am4charts.GaugeChart);
    chart.hiddenState.properties.opacity = 0;
    chart.fontSize = 11;
    chart.innerRadius = am4core.percent(80);
    chart.resizable = true;

    /**
     * Normal axis
     */

    var axis = chart.xAxes.push(new am4charts.ValueAxis());
    axis.min = chartMin;
    axis.max = chartMax;
    axis.strictMinMax = true;
    axis.renderer.radius = am4core.percent(80);
    axis.renderer.inside = true;
    axis.renderer.line.strokeOpacity = 0.1;
    axis.renderer.ticks.template.disabled = false;
    axis.renderer.ticks.template.strokeOpacity = 1;
    axis.renderer.ticks.template.strokeWidth = 0.5;
    axis.renderer.ticks.template.length = 5;
    axis.renderer.grid.template.disabled = true;
    axis.renderer.labels.template.radius = am4core.percent(15);
    axis.renderer.labels.template.fontSize = "0.9em";

    /**
     * Axis for ranges
     */

    var axis2 = chart.xAxes.push(new am4charts.ValueAxis());
    axis2.min = chartMin;
    axis2.max = chartMax;
    axis2.strictMinMax = true;
    axis2.renderer.labels.template.disabled = true;
    axis2.renderer.ticks.template.disabled = true;
    axis2.renderer.grid.template.disabled = false;
    axis2.renderer.grid.template.opacity = 0.5;
    axis2.renderer.labels.template.bent = true;
    axis2.renderer.labels.template.fill = am4core.color("#000");
    axis2.renderer.labels.template.fontWeight = "bold";
    axis2.renderer.labels.template.fillOpacity = 0.3;

    /**
    Ranges
    */

    for (let grading of data.position3) {
        var range = axis2.axisRanges.create();
        range.axisFill.fill = am4core.color(grading.position5);
        range.axisFill.fillOpacity = 0.8;
        range.axisFill.zIndex = -1;
        range.value = grading.position6 > chartMin ? grading.position6 : chartMin;
        range.endValue = grading.position7 < chartMax ? grading.position7 : chartMax;
        range.grid.strokeOpacity = 0;
        range.stroke = am4core.color(grading.position5).lighten(-0.1);
        range.label.inside = true;
        range.label.text = grading.position4.toUpperCase();
        range.label.inside = true;
        range.label.location = 0.5;
        range.label.inside = true;
        range.label.radius = am4core.percent(10);
        range.label.paddingBottom = -5; // ~half font size
        range.label.fontSize = "0.9em";
    }

    var matchingGrade = lookUpGrade(Number(data.position2), data.position3);

    /**
     * Label 1
     */

    var label = chart.radarContainer.createChild(am4core.Label);
    label.isMeasured = false;
    label.fontSize = "6em";
    label.x = am4core.percent(50);
    label.paddingBottom = 15;
    label.horizontalCenter = "middle";
    label.verticalCenter = "bottom";
    //label.dataItem = data;
    label.text = Number(data.position2).toFixed(1);
    //label.text = "{score}";
    label.fill = am4core.color(matchingGrade.position5);

    /**
     * Label 2
     */

    var label2 = chart.radarContainer.createChild(am4core.Label);
    label2.isMeasured = false;
    label2.fontSize = "2em";
    label2.horizontalCenter = "middle";
    label2.verticalCenter = "bottom";
    label2.text = matchingGrade.position4.toUpperCase();
    label2.fill = am4core.color(matchingGrade.position5);

    /**
     * Hand
     */

    var hand = chart.hands.push(new am4charts.ClockHand());
    hand.axis = axis2;
    hand.innerRadius = am4core.percent(55);
    hand.startWidth = 8;
    hand.pin.disabled = true;
    hand.value = data.position2;
    hand.fill = am4core.color("#444");
    hand.stroke = am4core.color("#000");

    hand.events.on("positionchanged", function(){
        label.text = axis2.positionToValue(hand.currentPosition).toFixed(1);
        var matchingGrade = lookUpGrade(axis.positionToValue(hand.currentPosition), data.position3);
        label2.text = matchingGrade.position4;
        label2.fill = am4core.color(matchingGrade.position5);
        label2.stroke = am4core.color(matchingGrade.position5);  
        label.fill = am4core.color(matchingGrade.position5);
    });
    
    // Гарчиг
    if (data.hasOwnProperty('position1') && data.position1) {
        
        var title = chart.titles.create();
        title.text = chartData.position1;
        title.fontSize = 20;
        title.marginBottom = 0;
    }
});
</script>

<div id="chartdiv_<?php echo $uniqId; ?>" class="pf_widget pf_widget_chart pf_widget_chart_gauge"></div>
<?php
}
?>