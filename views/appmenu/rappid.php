<div class="" style="margin:20px;z-index:100;position: relative;">
    <button type="button" class="btn btn-sm green-meadow" onclick="kpiDataMartAddObject2(this);">
        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?>
    </button>
</div>
<div id="app">
    <div class="canvas"></div>
</div>
<script src="http://localhost:8080/bundle.js" type="text/javascript"></script>
<script type="text/javascript">
    function kpiDataMartAddObject2(elem) {
        dataViewSelectableGrid('nullmeta', '0', '16511984441409', 'multi', 'nullmeta', elem, 'kpiDataMartFillEditor2');
    }
    function kpiDataMartFillEditor2(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
        selectModels(rows);
    }    
</script>