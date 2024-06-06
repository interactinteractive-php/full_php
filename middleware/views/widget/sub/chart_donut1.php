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

    var chart = am4core.create('chartdiv_<?php echo $uniqId; ?>', am4charts.PieChart);
    var chartData = <?php echo json_encode($row, JSON_UNESCAPED_UNICODE); ?>;
    
    chart.legend = new am4charts.Legend();
    
    // Доорхи legend-ийн гарах өндөр /2 мөр байхаар тохируулав/
    chart.legend.maxHeight = 80;
    chart.legend.scrollable = true;
    
    chart.data = chartData.position4;
    
    // Доторх дугуйн хэмжээ
    chart.innerRadius = 100;
    
    var series = chart.series.push(new am4charts.PieSeries());
    
    // Чарт харуулах баганын тохиргоо
    series.dataFields.value = 'position6';
    series.dataFields.category = 'position5';
    
    // Чарт дата 10 аас их байгаа үед label ийг disable хийв
    if (chart.data && chart.data.length > 10) {
        series.labels.template.disabled = true;
    }
    
    // Гарчиг
    if (chartData.hasOwnProperty('position1') && chartData.position1) {
        
        var title = chart.titles.create();
        title.text = chartData.position1;
        title.fontSize = 20;
        title.marginTop = 15;
        title.marginBottom = -20;
    }
    
    // Дугуй доторх голын текст 
    if ((chartData.hasOwnProperty('position2') && chartData.position2) || (chartData.hasOwnProperty('position3') && chartData.position3)) {
        
        var label = chart.seriesContainer.createChild(am4core.Label);
        label.textAlign = 'middle';
        label.horizontalCenter = 'middle';
        label.verticalCenter = 'middle';
        label.fontSize = 50;
        
        if (chartData.hasOwnProperty('position2') && chartData.position2 && chartData.hasOwnProperty('position3') && chartData.position3) {
            
            label.adapter.add('text', function(text, target){
                return "[font-size:40px]"+chartData.position2+"[/]\n[font-size:15px]"+chartData.position3+"[/]";
            });
            
        } else if (chartData.hasOwnProperty('position2') && chartData.position2) {
            
            label.adapter.add('text', function(text, target){
                return "[font-size:40px]"+chartData.position2+"[/]";
            });
            
        } else if (chartData.hasOwnProperty('position3') && chartData.position3) {
            
            label.adapter.add('text', function(text, target){
                return "[font-size:15px]"+chartData.position3+"[/]";
            });
        }
    }
    
});
</script>

<div id="chartdiv_<?php echo $uniqId; ?>" class="pf_widget pf_widget_chart pf_widget_chart_donut"></div>
<?php
}
?>