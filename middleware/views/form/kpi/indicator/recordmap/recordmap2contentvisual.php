<div id="app">
    <div class="canvas"></div>
</div>  
<input type="hidden" value="<?php echo $this->indicatorId; ?>" data-kpidatamart-id="222"/>
<input type="hidden" value="<?php echo $this->indicatorInfo['NAME']; ?>" data-kpidatamart-name="222"/>

<script type="text/javascript">
    function kpiDataMartAddObject2(elem) {
        dataViewSelectableGrid('nullmeta', '0', '16511984441409', 'multi', 'nullmeta', elem, 'kpiDataMartFillEditor2');
    }

    function kpiDataMartFillEditor2(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
        selectModels(rows);
    }           
</script>