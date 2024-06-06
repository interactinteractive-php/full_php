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
setTimeout(function() {
    am4core.ready(function() {
        function am4themes_RadarTheme(target) {
            if (target instanceof am4core.ColorSet) {
                target.list = [
                    am4core.color("#e91e63"),
                    am4core.color("#673ab7"),
                    am4core.color("#2196f3"),
                    am4core.color("#1ba68d"), 
                    am4core.color("#e7da4f"),
                    am4core.color("#E7DA4F")
                ];
            }
        }
        am4core.unuseAllThemes();
        am4core.useTheme(am4themes_RadarTheme);
        am4core.useTheme(am4themes_animated);

        var chart = am4core.create('chartdiv_<?php echo $uniqId; ?>', am4charts.RadarChart);
        var chartData = <?php echo json_encode($row, JSON_UNESCAPED_UNICODE); ?>;
        var radarData = chartData.position2;

        /*chart.data = [
            {
                "country": "Lithuania",
                "litres": 501
            }, 
            {
                "country": "Czechia",
                "litres": 301
            }
        ];*/

        /* Create axes */
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "position3";

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.axisFills.template.fill = chart.colors.getIndex(2);
        valueAxis.renderer.axisFills.template.fillOpacity = 0.05;
        valueAxis.tooltip.disabled = true;

        var duplicateGroupField = [];

        for (var c in radarData) {

            if (!duplicateGroupField.hasOwnProperty(radarData[c]['position5'])) {

                chart.data = [];

                for (var l in radarData) {
                    if (radarData[c]['position5'] == radarData[l]['position5']) {
                        chart.data.push(radarData[l]);
                    }
                }

                var series = chart.series.push(new am4charts.RadarSeries());
                series.data = chart.data; 
                series.dataFields.valueY = 'position4';
                series.dataFields.categoryX = 'position3';
                series.name = radarData[c]['position5'];
                series.strokeWidth = 3;
                series.fillOpacity = 0.1;
                series.tooltip.label.wrap = true;
                series.tooltip.label.width = 250;

                var bullet = series.bullets.push(new am4charts.CircleBullet());
                bullet.circle.radius = 4;
                bullet.tooltipText = "{name}\n{categoryX}: {valueY}";

                duplicateGroupField[radarData[c]['position5']] = 1;
            }
        }

        // Гарчиг
        if (chartData.hasOwnProperty('position1') && chartData.position1) {

            var title = chart.titles.create();
            title.text = chartData.position1;
            title.fontSize = 20;
            title.marginTop = 5;
            title.marginBottom = 10;
        }

        chart.legend = new am4charts.Legend();

        // Доорхи legend-ийн гарах өндөр /2 мөр байхаар тохируулав/
        chart.legend.maxHeight = 80;
        chart.legend.scrollable = true;

    });
}, 0);    
</script>

<div id="chartdiv_<?php echo $uniqId; ?>" class="pf_widget pf_widget_chart pf_widget_chart_radar"></div>
<?php
}
?>