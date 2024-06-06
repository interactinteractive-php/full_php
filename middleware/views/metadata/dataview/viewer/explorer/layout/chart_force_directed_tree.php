<?php 
$idField = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['id']));
$parentidField = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['parent']));
$nameField = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['name']));
$valueField = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['number']));

if (!$idField || !$parentidField || !$nameField || !$valueField) {
    echo html_tag('div', array('class' => 'alert alert-waring'), 'Талбарын тохиргоо дутуу байна!'); exit;
}

function buildTree($elements, $idField, $parentidField, $nameField, $valueField, $parentId = '') {

    $branch = array();

    foreach ($elements as $element) {
            
        if ($element[$parentidField] == $parentId) {
            
            $children = buildTree($elements, $idField, $parentidField, $nameField, $valueField, $element[$idField]);
            $newElement = array('name' => $element[$nameField]);
            
            if ($children) {
                $newElement['children'] = $children;
            } else {
                $newElement['value'] = $element[$valueField];
            }
            
            $branch[] = $newElement;
        }
    }

    return $branch;
} 

$treeData = buildTree($this->recordList, $idField, $parentidField, $nameField, $valueField);
?>
<div id="chartdiv_<?php echo $this->dataViewId; ?>"></div>

<style type="text/css">
    #chartdiv_<?php echo $this->dataViewId; ?> {
        width: 100%;
        max-width: 100%;
        min-height: 300px;
    }
    .div-objectdatagrid-<?php echo $this->dataViewId; ?>.explorer-table-cell {
        background-color: transparent!important;
        border: 0!important;
    }
</style>

<script type="text/javascript">
$(function() {
   
    var $treeElement = $('#chartdiv_<?php echo $this->dataViewId; ?>');
    var treeElementHeight = $(window).height() - $treeElement.offset().top - 40;
    $treeElement.css('height', treeElementHeight);

    $.cachedScript('assets/custom/addon/plugins/amcharts4/plugins/forceDirected.js').done(function() {
        amchartForceDirectedTreeInit_<?php echo $this->dataViewId; ?>();
    });
    
});  

function amchartForceDirectedTreeInit_<?php echo $this->dataViewId; ?>() {
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        var chart = am4core.create("chartdiv_<?php echo $this->dataViewId; ?>", am4plugins_forceDirected.ForceDirectedTree);
        
        chart.legend = new am4charts.Legend();
        // Доорхи legend-ийн гарах өндөр /2 мөр байхаар тохируулав/
        chart.legend.maxHeight = 80;
        chart.legend.scrollable = true;
        
        var networkSeries = chart.series.push(new am4plugins_forceDirected.ForceDirectedSeries());

        networkSeries.data = <?php echo json_encode($treeData, JSON_UNESCAPED_UNICODE); ?>;

        networkSeries.dataFields.linkWith = "linkWith";
        networkSeries.dataFields.name = "name";
        networkSeries.dataFields.id = "name";
        networkSeries.dataFields.value = "value";
        networkSeries.dataFields.children = "children";

        networkSeries.nodes.template.tooltipText = "{name}";
        networkSeries.nodes.template.fillOpacity = 1;

        networkSeries.nodes.template.label.text = "{name}";
        networkSeries.fontSize = 8;
        networkSeries.maxLevels = 2;
        networkSeries.maxRadius = am4core.percent(6);
        networkSeries.manyBodyStrength = -16;
        networkSeries.nodes.template.label.hideOversized = true;
        networkSeries.nodes.template.label.truncate = true;

    }); // end am4core.ready()
}
</script>