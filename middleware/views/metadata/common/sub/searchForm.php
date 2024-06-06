<div class="form-group row fom-row">
    <?php echo Form::label(array('text'=>'ID','for'=>'metaDataId')); ?> 
    <div class="clearfix w-100"></div>
    <div class="col-md-2 pl0 pr0">
        <select name="condition[metadataid]" class="form-control form-control-sm right-radius-zero pl0 pr0">
            <option value="like">Төстэй</option>
            <option value="=">=</option>
        </select>
    </div>
    <div class="col-md-10 pl0 pr0">
        <?php echo Form::text(array('name'=>'metaDataId','id'=>'metaDataId','class'=>'form-control form-control-sm longInit')); ?>
    </div>    
</div>    
<div class="clearfix w-100"></div>
<div class="form-group row fom-row">
    <?php echo Form::label(array('text'=>$this->lang->line('META_00075'),'for'=>'metaDataCode')); ?> 
    <div class="clearfix w-100"></div>
    <div class="col-md-2 pl0 pr0">
        <select name="condition[metadatacode]" class="form-control form-control-sm right-radius-zero pl0 pr0">
            <option value="like">Төстэй</option>
            <option value="=">=</option>
        </select>
    </div>
    <div class="col-md-10 pl0 pr0">
        <?php echo Form::text(array('name'=>'metaDataCode','id'=>'metaDataCode','class'=>'form-control form-control-sm')); ?>
    </div>    
</div>    
<div class="clearfix w-100"></div>
<div class="form-group row fom-row mt10">
    <?php echo Form::label(array('text'=>$this->lang->line('META_00125'),'for'=>'metaDataName')); ?>
    <div class="clearfix w-100"></div>
    <div class="col-md-2 pl0 pr0">
        <select name="condition[metadataname]" class="form-control form-control-sm right-radius-zero pl0 pr0">
            <option value="like">Төстэй</option>
            <option value="=">=</option>
        </select>
    </div>
    <div class="col-md-10 pl0 pr0">
        <?php echo Form::text(array('name'=>'metaDataName','id'=>'metaDataName','class'=>'form-control form-control-sm')); ?>
    </div>    
</div>
<div class="clearfix w-100"></div>
<div class="form-group row fom-row mt10">
    <?php echo Form::label(array('text'=>$this->lang->line('META_00145'),'for'=>'metaTypeId')); ?>
    <?php 
    echo Form::select(
        array(
            'name' => 'metaTypeId',
            'id' => 'metaTypeId',
            'class' => 'form-control form-control-sm select2',
            'data' => (new Mdmetadata())->getMetaTypeList(),
            'op_value' => 'META_TYPE_ID', 
            'op_text' => 'META_TYPE_NAME', 
            'text' => '- Бүгд -'
        )
    ); 
    ?>
</div>