<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php 
echo Form::create(array('class' => 'form-horizontal', 'id' => 'pf-metastatus-form', 'method' => 'post')); 
    echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId));
?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row mt20">
        <?php echo Form::label(array('class' => 'col-md-3 col-form-label', 'text' => 'Төлөв сонгох', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php
            echo Form::select(
                array(
                    'name' => 'statusId',
                    'id' => 'statusId',
                    'class' => 'form-control form-control-sm select2',
                    'data' => $this->statusList,
                    'op_value' => 'ID',
                    'op_text' => 'NAME', 
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>