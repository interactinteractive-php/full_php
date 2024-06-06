<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12">
    <?php 
    $row = $this->getlifeCycle;
    echo Form::create(array('class' => 'form-horizontal', 'id' => 'lifecycle-form', 'method' => 'post')); 
    echo Form::hidden(array('name' => 'lifecycleId', 'id' => 'lifecycleId', 'value' => $row['LIFECYCLE_ID'])); ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'for' => 'lifecycleCode', 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
        <div class="col-md-10">
            <?php
            echo Form::text(
                    array(
                        'name' => 'lifecycleCode',
                        'id' => 'lifecycleCode',
                        'class' => 'form-control',
                        'required' => 'required',
                        'value' => $row['LIFECYCLE_CODE']
                    )
            );
            ?>
        </div>
    </div>

    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00125'), 'for' => 'lifecycleName', 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
        <div class="col-md-10">
            <?php
            echo Form::text(
                array(
                    'name' => 'lifecycleName',
                    'id' => 'lifecycleName',
                    'class' => 'form-control',
                    'required' => 'required',
                    'value' => $row['LIFECYCLE_NAME']
                )
            );
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00080'), 'for' => $this->lang->line('META_00080'), 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
        <div class="col-md-10">
            <?php
            echo Form::text(
                array(
                    'name' => 'orderNum',
                    'id' => 'orderNum',
                    'class' => 'form-control longInit',
                    'required' => 'required',
                    'value' => $row['ORDER_NUM'],
                    'style' => 'width:50px'
                )
            );
            ?>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>