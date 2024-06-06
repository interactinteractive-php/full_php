<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12 xs-form mt15">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'generate-structure-form', 'method' => 'post')); ?>
    <?php echo Form::hidden(array('name' => 'folderId', 'value' => $this->folderId));?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'All table', 'for' => 'tablename', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php
            echo Form::multiselect(
                array(
                    'name' => 'tableName[]',
                    'id' => 'tableName',
                    'class' => 'form-control select2 form-control-sm',
                    'op_value' => 'tablename',
                    'op_text' => 'tablename',
                    'multiple' => true,
                    'data' => $this->tablesList,
                    'glue' => '-',
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>