<?php
echo Form::create(array('class' => 'form-horizontal', 'id' => 'meta-delete-form', 'method' => 'post'));
echo Form::hidden(array('name' => 'metaTypeId', 'id' => 'metaTypeId', 'value' => $this->metaRow['META_TYPE_ID']));
echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->metaDataId));
echo Form::hidden(array('name' => 'replaceMetaDataId', 'id' => 'replaceMetaDataId', 'value' => ''));
?>
<div class="form-group row mb5">
    <label class="col-md-2 col-form-label">Код:</label>
    <div class="col-md-10">
        <?php echo $this->metaRow['META_DATA_CODE']; ?>
    </div>
</div>
<div class="form-group row mb5">
    <label class="col-md-2 col-form-label">Нэр:</label>
    <div class="col-md-10">
        <?php echo $this->metaRow['META_DATA_NAME']; ?>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2 col-form-label">Төрөл:</label>
    <div class="col-md-10">
        <?php echo $this->metaRow['META_TYPE_NAME']; ?>
    </div>
</div>
<?php if ($this->isParent == 'true') { ?>
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">Солих мета сонгох:</label>
        <div class="col-md-8">
            <span id="replace-meta-name"></span>
            <a href="javascript:;" class="btn btn-sm purple-plum mr0" onclick="commonMetaDataGrid('single', 'metaMenu', 'autoSearch=1&metaTypeId=<?php echo $this->metaRow['META_TYPE_ID']; ?>', 'replaceMetaData');">...</a>
            <span class="form-text">Энэ мета өөр газар ашиглагдсан тул солих метаг сонгоно уу</span>
        </div>
    </div>

    <script type="text/javascript">
        function replaceMetaData(chooseType, elem, params) {
            var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
            if (metaBasketNum > 0) {
                var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
                
                if (rows['0']['META_DATA_ID'] != '<?php echo $this->metaDataId; ?>') {
                    $("#replace-meta-name").html(rows['0']['META_DATA_NAME']);
                    $("#replaceMetaDataId").val(rows['0']['META_DATA_ID']);
                } else {
                    var dialogName = '#removeDialog';
                    if (!$(dialogName).length) {
                        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                    }
                    $(dialogName).html('Өөр мета сонгоно уу!').dialog({
                            cache: false,
                            resizable: true,
                            title: 'Сануулга',
                            bgiframe: true,
                            autoOpen: false,
                            width: '450px',
                            height: 'auto',
                            modal: true,
                            buttons: [
                                {text: '<?php echo $this->lang->line('close_btn'); ?>', class: 'btn green-meadow btn-sm', click: function () {
                                    $(dialogName).dialog('close');
                                }}]
                        }).dialog('open');
                }

            }
        }
    </script>
<?php 
} else {
?>
    <div class="alert alert-info mb-2">Та устгахдаа итгэлтэй байна уу?</div>
<?php
}

echo Form::close(); 
?>
