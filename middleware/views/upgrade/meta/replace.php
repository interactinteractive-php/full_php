<?php 
echo Form::create(array('id'=>'newReplaceForm', 'method'=>'post', 'class'=>'form-horizontal')); 
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
    <div class="col-md-6 mb0">
        <input type="text" value="<?php echo $this->metaRow['META_DATA_NAME']; ?>" class="form-control form-control-sm" readonly="readonly">  
    </div>
</div>
<div class="form-group row mb-2">
    <?php echo Form::label(array('text'=>$this->lang->line('Replace meta'), 'class'=>'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-10">
        <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo $this->metaRow['META_TYPE_ID']; ?>">
            <div class="input-group double-between-input">
                <input id="replaceMetaId" name="replaceMetaId" type="hidden">
                <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" required="required" style="width: 200px!important;flex: 0 0 200px!important;max-width: 200px!important;">
                <span class="input-group-btn">
                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                </span>     
                <span class="input-group-btn flex-col-group-btn">
                    <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" required="required">      
                </span> 
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>
