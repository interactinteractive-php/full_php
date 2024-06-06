<?php
echo Form::create(array('class' => 'form-horizontal', 'id' => 'folder-delete-form', 'method' => 'post'));
echo Form::hidden(array('name' => 'moveFolderId', 'id' => 'moveFolderId', 'value' => ''));
if ($this->isParent == 'true') {
?>
<div class="form-group row fom-row">
    <label class="col-md-4 col-form-label"><?php echo $this->lang->line('metadata_move_to_folder'); ?>: </label>
    <div class="col-md-8">
        <span id="move-folder-name"></span>
        <a href="javascript:;" class="btn btn-sm purple-plum mr0" onclick="commonFolderDataGrid('single', 'autoSearch=1');">...</a>
        <span class="form-text">Child folder, meta-г өөр folder-т шилжүүлэх</span>
    </div>

</div>

<?php echo Form::close(); ?>
<script type="text/javascript">
    function selectableCommonFolderGrid(chooseType, elem, params) {
        var metaBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketFolderGrid').datagrid('getRows');
            $("#move-folder-name").html(rows['0']['FOLDER_NAME']);
            $("#moveFolderId").val(rows['0']['FOLDER_ID']);
        }
    }
</script>
<?php
} else { 
    echo $this->lang->line('msg_delete_confirm');
}
?>
