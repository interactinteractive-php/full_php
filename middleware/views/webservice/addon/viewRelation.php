<?php if ($this->metaValueRelationRows['status'] == 'success') {
    ?>
    <?php if (empty($this->semanticConfigList)) { ?>
        <button type="button" class="btn green-meadow btn-circle btn-sm" id="addUmObjectBtn"><i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?></button>
    <?php } ?>
    <div id="umObjectSelectedList_<?php echo $this->uniqId; ?>" class="umObjectSelectedList">
        <div class="form-body">

        </div>
    </div>
    <script type="text/javascript">
        var UM_OBJECT_DV_ID='<?php echo Config::getFromCache('UM_OBJECT_DV_ID'); ?>';

        $(function(){
            if(typeof umObject === 'undefined'){
                $.getScript(URL_APP + 'middleware/assets/js/addon/umObject.js', function(){
                    $.getStylesheet(URL_APP + 'middleware/assets/css/addon/style.css');
                    umObject.init(<?php echo $this->uniqId; ?>, <?php echo $this->processId; ?>, <?php echo json_encode($this->metaValueRelationRows['automapparams']); ?>, <?php echo json_encode($this->semanticConfigList); ?>);
                });
            } else {
                umObject.init(<?php echo $this->uniqId; ?>, <?php echo $this->processId; ?>, <?php echo json_encode($this->metaValueRelationRows['automapparams']); ?>, <?php echo json_encode($this->semanticConfigList); ?>);
            }
        });

        function addUmObjectTo(metaDataCode, chooseType, elem, rows){
            if(rows.length === 0){
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Та мөр сонгоно уу',
                    type: 'warning',
                    sticker: false
                });
            } else {

                umObject.drawRelationHtml(rows, function(groupMetaDataIdList){
                    umObject.getMetaValueName(groupMetaDataIdList);
                });

                umObject.drawHiddenParamForRelation();
            }
        }
    </script>
    <?php
} else {
    echo html_tag('div', array('class' => 'alert alert-danger'), $this->metaValueRelationRows['text']);
}
?>