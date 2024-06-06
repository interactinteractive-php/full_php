<?php 
if ($this->recordList) {
    
    $graphField = issetDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['graphfield'], 'bpmn');
    $firstRow = $this->recordList[0];

    if (!array_key_exists($graphField, $firstRow)) {
        echo html_tag('div', array('class' => 'alert alert-warning'), 'No graphfield!'); exit;
    }
    
    $graph = array();
    
    foreach ($this->recordList as $row) {
        
        $uid = getUID();
        
        $graph[] = Form::textArea(array('id' => 'graphInput-'.$uid, 'style' => 'display: none', 'class' => 'mxgraph-load', 'value' => $row[$graphField], 'data-dtlid' => $uid));
        $graph[] = '<div id="graphview-'.$uid.'" class="svg-d-inline text-center"></div>';
    }
    
    echo implode('', $graph);
?>

<style type="text/css">
.div-objectdatagrid-<?php echo $this->dataViewId; ?>.bgnone {
    background: 0 !important;
    border: 0 !important;
}
</style>
<script type="text/javascript">
    Core.initMxGraph($('#objectdatagrid-<?php echo $this->dataViewId; ?>'));
</script>

<?php
} else {
    echo html_tag('div', array('class' => 'alert alert-warning'), 'No data!'); 
}
?>