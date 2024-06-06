<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12">

    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'lifecycle-form', 'method' => 'post')); ?>
    <?php echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->metaDataId)); ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lifecycle book', 'for' => 'Lifecycle book', 'required' => 'required', 'class' => 'col-md-3 col-form-label')); ?>
        <div class="col-md-9">
            <?php
            echo Form::select(
                array(
                    'name' => 'lcBookId', 
                    'id' => 'lcBookId', 
                    'data' => $this->lifecycleBookList,
                    'op_value' => 'ID', 
                    'op_text' => 'LC_BOOK_CODE| |-| |LC_BOOK_NAME', 
                    'class' => 'form-control select2me',
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>

    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lifecycle код', 'for' => 'Lifecycle код', 'required' => 'required', 'class' => 'col-md-3 col-form-label')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'lcCode',
                    'id' => 'lcCode',
                    'class' => 'form-control',
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lifecycle нэр', 'for' => 'Lifecycle нэр', 'required' => 'required', 'class' => 'col-md-3 col-form-label')); ?>
        <div class="col-md-9">
            <?php
            echo Form::text(
                array(
                    'name' => 'lcName',
                    'id' => 'lcName',
                    'class' => 'form-control',
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>