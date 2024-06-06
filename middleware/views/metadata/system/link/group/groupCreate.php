<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'generate-MetaGroup-form', 'method' => 'post')); ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Folder', 'for' => 'folderId', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-8">
            <div class="input-group">
                <?php echo Form::text(array('class' => 'form-control', 'id' => 'folderName', 'readonly' => 'readonly', 'required' => 'required', 'value' => Arr::get($this->folderRow, 'FOLDER_NAME'))); ?>
                <?php echo Form::hidden(array('name' => 'folderId', 'value' => $this->folderId)); ?>
                <span class="input-group-btn">
                    <?php echo Form::button(array('class' => 'btn purple-plum', 'value' => '<i class="fa fa-search"></i>', 'onclick' => 'commonFolderDataGrid(\'single\', \'\', \'chooseMetaFolderByGroupCreate\', this);')); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Entity name', 'for' => 'entityName', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php
            echo Form::select(
                array(
                    'name' => 'entityName',
                    'id' => 'entityName',
                    'class' => 'form-control select2 form-control-sm',
                    'op_value' => 'entityname',
                    'op_text' => 'entityname',
                    'data' => $this->entityList,
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>

<script type="text/javascript">
function chooseMetaFolderByGroupCreate(chooseType, elem, params) {
    var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
    if (folderBasketNum > 0) {
        var rows = $('#commonBasketFolderGrid').datagrid('getRows');
        var row = rows[0];
        $("input#folderName", "#generate-MetaGroup-form").val(row.FOLDER_NAME);
        $("input[name='folderId']", "#generate-MetaGroup-form").val(row.FOLDER_ID);
    }        
}
</script>