<div class="card light shadow">
    <div class="card-body" id="db_<?php echo $this->metaDataId.'_'.$this->cellId; ?>"></div>
</div>

<script type="text/javascript">
    getRmChartClean("#db_<?php echo $this->metaDataId.'_'.$this->cellId; ?>", <?php echo $this->row['CHART_ID']; ?>);    
</script>

