<div class="card light shadow">
    <div class="card-body" style="height: <?php echo $this->cellAttr['height']; ?>; width:100%; margin: 0 auto" id="pdb_<?php echo $this->metaDataId.'_'.$this->cellId; ?>"></div>
</div>

<script type="text/javascript">
$(function(){
    <?php
    if ($this->row['CHART_TYPE'] !== 'pie') {
    ?>
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'pdb_<?php echo $this->metaDataId.'_'.$this->cellId; ?>',
            type: '<?php echo $this->row['CHART_TYPE']; ?>', 
            zoomType: 'xy'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: '<?php echo $this->row['DASHBOARD_NAME']; ?>'
        },
        exporting: {
            enabled: false
        },
        scrollbar: {
            enabled: true
        },
        xAxis: {
            categories: <?php echo json_encode($this->chartData['categories']); ?>, 
            min: 0,
            //max: 10
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        tooltip: {
            formatter: function() {
                return this.x + '<br /><b>'+ this.series.name +'</b>: '+ Highcharts.numberFormat(this.y, 2, '.');
            }
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0, 
            maxHeight: 62
        },
        series: <?php echo json_encode($this->chartData['series']); ?>
    });
    <?php
    } else {
    ?>
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'pdb_<?php echo $this->metaDataId.'_'.$this->cellId; ?>',
            type: 'pie', 
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: ''
        },
        subtitle: {
            text: '<?php echo $this->row['DASHBOARD_NAME']; ?>'
        },
        exporting: {
            enabled: false
        },
        tooltip: {
            formatter: function() {
                return this.point.name+'<br /><b>'+Highcharts.numberFormat(this.y, 2)+'</b>';
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Row meta',
            data: []
        }]
    });
    <?php
    }
    ?> 
});
</script>