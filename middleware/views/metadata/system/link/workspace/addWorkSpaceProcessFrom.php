<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php 
echo Form::create(array('class' => 'form-horizontal', 'id' => 'add-workspace-process-form', 'method' => 'post')); 
echo Form::hidden(array('id'=>'metaDataId', 'name'=>'metaDataId', 'value'=>$this->metaDataId));
echo Form::hidden(array('name'=>'rowId', 'value'=>$this->rowId));
?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <label class="col-md-2 col-form-label">Field path:</label>
        <div class="col-md-10">
            <?php
            echo Form::select(
                array(
                    'name' => 'fieldPath',
                    'id' => 'fieldPath',
                    'class' => 'form-control select2 form-control-sm',
                    'data' => $this->getDVParameterList,
                    'op_value' => 'FIELD_PATH',
                    'op_text' => 'FIELD_PATH| |-| |META_DATA_NAME', 
                    'value' => Arr::get($this->row, 'FIELD_PATH')
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-2 col-form-label">Param path:</label>
        <div class="col-md-10">
            <?php
            echo Form::text(
                array(
                    'name' => 'paramPath',
                    'id' => 'paramPath',
                    'class' => 'form-control',
                    'value' => Arr::get($this->row, 'PARAM_PATH')
                )
            );
            ?>            
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-2 col-form-label">Target meta:</label>
        <div class="col-md-10">
            <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId.'|'.Mdmetadata::$menuMetaTypeId; ?>">
                <div class="input-group double-between-input">
                    <input id="targetMetaId" name="targetMetaId" type="hidden" value="<?php echo Arr::get($this->row, 'TARGET_META_ID'); ?>">
                    <input id="_displayField" value="<?php echo Arr::get($this->row, 'TARGET_META_CODE'); ?>" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                    </span>     
                    <span class="input-group-btn not-group-btn">
                        <div class="btn-group pf-meta-manage-dropdown">
                            <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                        </div>
                    </span>  
                    <span class="input-group-btn flex-col-group-btn">
                        <input id="_nameField" value="<?php echo Arr::get($this->row, 'TARGET_META_NAME'); ?>" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                    </span>     
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-2 col-form-label">Target indicator:</label>
        <div class="col-md-10">
            <div class="meta-autocomplete-wrap" data-section-path="targetIndicatorId">
                <div class="input-group double-between-input">
                    <input type="hidden" name="targetIndicatorId" value="<?php echo Arr::get($this->row, 'TARGET_INDICATOR_ID'); ?>" id="targetIndicatorId_valueField" data-path="targetIndicatorId" class="popupInit">
                    <input type="text" name="targetIndicatorId_displayField" value="<?php echo Arr::get($this->row, 'TARGET_INDICATOR_CODE'); ?>" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="targetIndicatorId" id="targetIndicatorId_displayField" data-processid="16424366405551" data-lookupid="16424911273171" placeholder="кодоор хайх" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered btn-xs mr-0" onclick="dataViewSelectableGrid('targetIndicatorId', '16424366405551', '16424911273171', 'single', 'targetIndicatorId', this);" tabindex="-1"><i class="far fa-search"></i></button>
                    </span>
                    <span class="input-group-btn">
                        <input type="text" name="targetIndicatorId_nameField" value="<?php echo Arr::get($this->row, 'TARGET_INDICATOR_NAME'); ?>" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="targetIndicatorId" id="targetIndicatorId_nameField" data-processid="16424366405551" data-lookupid="16424911273171" placeholder="нэрээр хайх">
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>