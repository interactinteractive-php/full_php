<?php 
echo Form::create(array('id'=>'newCopyReplaceForm', 'method'=>'post', 'class'=>'form-horizontal')); 
?>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'ID', 'class' => 'col-form-label col-md-2 pt8')); ?>
    <div class="col-md-6 mb0">
        <input type="text" value="<?php echo $this->metaRow['META_DATA_ID']; ?>" class="form-control form-control-sm" readonly="readonly">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Код', 'class' => 'col-form-label col-md-2 pt8')); ?>
    <div class="col-md-6 mb0">
        <input type="text" value="<?php echo $this->metaRow['META_DATA_CODE']; ?>" class="form-control form-control-sm" readonly="readonly">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Нэр', 'class' => 'col-form-label col-md-2 pt8')); ?>
    <div class="col-md-10 mb0">
        <input type="text" value="<?php echo $this->metaRow['META_DATA_NAME']; ?>" class="form-control form-control-sm" readonly="readonly">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Шинэ ID', 'class' => 'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-6 mb0">
        <input type="text" name="newMetaId" value="<?php echo $this->newMetaId; ?>" class="form-control form-control-sm" readonly="readonly" required="required">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Шинэ код', 'class' => 'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-6 mb0">
        <input type="text" name="newMetaCode" value="<?php echo $this->metaRow['META_DATA_CODE']; ?>_copy" class="form-control form-control-sm" required="required">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Шинэ нэр', 'class' => 'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-10 mb0">
        <input type="text" name="newMetaName" value="<?php echo $this->metaRow['META_DATA_NAME']; ?>_copy" class="form-control form-control-sm" required="required">  
    </div>
</div>
<div class="form-group row mb-2">
    <?php echo Form::label(array('text'=>$this->lang->line('META_00024'), 'class'=>'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-10">
        <div class="meta-autocomplete-wrap">
            <div class="input-group double-between-input">
                <?php echo Form::hidden(array('name' => 'folderId', 'value' => Arr::get($this->folderRow, 'FOLDER_ID'))); ?>
                <input type="text" id="_displayField" class="form-control form-control-sm md-folder-code-autocomplete" value="<?php echo Arr::get($this->folderRow, 'FOLDER_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" required="required">
                <span class="input-group-btn">
                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonFolderDataGrid('single', '', 'chooseMetaFolderByCopyReplace', this);"><i class="fa fa-search"></i></button>
                </span>      
                <span class="input-group-btn flex-col-group-btn">
                    <input type="text" id="_nameField" class="form-control form-control-sm md-folder-name-autocomplete" value="<?php echo Arr::get($this->folderRow, 'FOLDER_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" required="required">      
                </span>     
            </div>
        </div>  
    </div>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
function chooseMetaFolderByCopyReplace(chooseType, elem, params) {
    var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
    if (folderBasketNum > 0) {
        var rows = $('#commonBasketFolderGrid').datagrid('getRows'), 
            row = rows[0], 
            $form = $('#newCopyReplaceForm');

        $form.find('input[name="folderId"]').val(row.FOLDER_ID);
        $form.find('input#_displayField').val(row.FOLDER_CODE);
        $form.find('input#_nameField').val(row.FOLDER_NAME);
    }        
} 
</script>
