<div id="main-id-<?php echo $this->uniqId ?>">
    <form>
        <div>
            <img src="middleware/assets/img/mv/add-relation-header-banner.png" alt="header picture">
        </div>
        <div class="mt50 mb50 ml70">
            <img src="middleware/assets/img/mv/add-relation-s-line.png" alt="header picture">
            <input readonly type="text" name="srcIndicator" class="form-control" style="background-color: #F9F9F9;height: 32px;border: none;border-radius: 10px;width: 335px;position: absolute;margin-top: -130px;margin-left: 200px;">
            <div class="meta-autocomplete-wrap" style="height: 32px;border: none;border-radius: 10px;position: absolute;margin-top: -45px;margin-left: 115px;">
                <div class="input-group double-between-input">
                    <input type="hidden" name="trgIndicatorId" id="trgIndicatorId_valueField" data-path="trgIndicatorId" value="" class="popupInit" data-row-data="" placeholder="Холбогдох үзүүлэлт">
                    <input type="text" required="required" name="trgIndicatorId_displayField" tabindex="" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" data-field-name="trgIndicatorId" id="trgIndicatorId_displayField" data-processid="16425125580661" data-lookupid="16424911273171" placeholder="Холбогдох үзүүлэлтээ сонгоно уу!" value="" title="" autocomplete="off" style="height: 32px;flex: 0 0 303px !important;width: 303px !important;max-width: 303px !important;border-top-left-radius: 10px;border-bottom-left-radius: 10px;border-color: #468ce2;">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered btn-xs mr-0" style="height: 32px;border-top-right-radius: 10px !important;border-bottom-right-radius: 10px !important;background-color: #468ce2;border-color: #468ce2;" onclick="dataViewSelectableGrid('trgIndicatorId', '16425125580661', '16424911273171', 'single', 'trgIndicatorId', this, 'returnRowData');" tabindex="-1"><i style="color:#fff" class="far fa-search"></i></button>
                    </span> 
                </div>
            </div>        
        </div>
    </form>
</div>
<script type="text/javascript">
    function returnRowData(  
        metaDataCode,
        processMetaDataId,
        chooseType,
        elem,
        rows,
        paramRealPath,
        lookupMetaDataId,
        isMetaGroup) {
            var row = rows[0];
            $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val(row.id);
            $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val(row.code+' - '+row.name);
    }
</script>    