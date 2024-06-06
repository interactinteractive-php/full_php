<div class="bg-white">
    <?php echo $this->title; ?> 
    <button type="button" class="btn btn-light btn-sm" id="radar-chart-refresh">
        <i class="icon-database-refresh"></i>
    </button>
    <div style="margin-left: auto;margin-right: auto;">
        <div class="radarChart1"></div>
    </div>
</div>

<style type="text/css">
    .radarChart1 .legendOrdinal > .cell {
        cursor: pointer;
    }
    .radarChart1 .legendOrdinal > .cell > .label {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

<script type="text/javascript" src="assets/core/js/plugins/visualization/d3/d3.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/visualization/d3/d3-legend.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/visualization/d3/radarChart.js?v=20"></script>

<script type="text/javascript">
var margin = {top: 100, right: 100, bottom: 100, left: 100},
    legendPosition = {x: 10, y: 25},
    width = Math.min(window.innerWidth, $('.radarChart1').width()) - margin.left - margin.right,
    height = Math.min(width, window.innerHeight - margin.top - margin.bottom - 20);

var data = <?php echo json_encode($this->radarChart1, JSON_NUMERIC_CHECK); ?>;
var color = d3.scale.ordinal().range([
    '#EDC951','#CC333F','#00A0B0','#7986cb',
    '#801336','#510A32','#2D142C','#8FB9A8',
    '#FEFAD4','#FCD0BA','#F1828D','#765D69',
    '#FCBB6D','#D8737F','#AB6C82','#685D79',
    '#475C7A','#F18C8E','#F0B7A4','#F1D1B5',
    '#568EA6','#305F72','#CC2A49','#F99E4C',
    '#F36F38','#EF4648','#582841','#F26627',
    '#F9A26C','#9BD7D1','#325D79','#DDA5B6',
    '#F2CC8C','#9BD7D1','#F1E6C1','#3F6A8A',
    '#4D5E72','#510A32','#2D142C','#8FB9A8'
]);

var radarChartOptions = {
    w: width,
    h: height,
    margin: margin,
    legendPosition: legendPosition,
    maxValue: 1,
    wrapWidth: 60,
    levels: 5,
    labelFactor: 1.1, 
    roundStrokes: true,
    color: color,
    axisName: "axis",
    areaName: "data",
    value: "value"
};

RadarChart('.radarChart1', data, radarChartOptions);    

$(function() {
    
    $('#radar-chart-refresh').on('click', function() {
        $.ajax({
            type: 'post',
            url: 'mdlayout/radarChartData',
            dataType: 'json',
            success: function(data) {
                RadarChart('.radarChart1', data, radarChartOptions); 
            }
        });
    });
});
</script>