<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'temp-folder-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Нэр', 'for' => 'name', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::text(
                array(
                    'name' => 'name', 
                    'id' => 'name', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required'
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('item_parent_category'), 'for' => 'parentId', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::select(
                array(
                    'name' => 'parentId', 
                    'id' => 'parentId', 
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->folderList, 
                    'op_value' => 'ID', 
                    'op_text' => 'NAME', 
                    'value' => $this->folderId 
                )
            ); 
            ?>
        </div>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId));
echo Form::close(); 
?>