<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12">

    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'saveworkflow-form', 'method' => 'post')); ?>
    <?php
    echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->metaDataId));
    echo Form::hidden(array('name' => 'metaDataCode', 'id' => 'metaDataCode', 'value' => $this->metaDataCode));
    echo Form::hidden(array('name' => 'metaDataName', 'id' => 'metaDataName', 'value' => $this->metaDataName));
    ?>

    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Статус код', 'for' => 'statusCode', 'required' => 'required', 'class' => 'col-md-4 col-form-label')); ?>
        <div class="col-md-8">
            <?php
            echo Form::text(
                    array(
                        'name' => 'wfmStatusCode',
                        'id' => 'wfmStatusCode',
                        'class' => 'form-control',
                        'required' => 'required'
                    )
            );
            ?>
        </div>
    </div>

    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Статус нэр', 'for' => 'statusName', 'required' => 'required', 'class' => 'col-md-4 col-form-label')); ?>
        <div class="col-md-8">
            <?php
            echo Form::text(
                array(
                    'name' => 'wfmStatusName',
                    'id' => 'wfmStatusName',
                    'class' => 'form-control',
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>

    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Статус өнгө', 'for' => 'statusColor', 'required' => 'required', 'class' => 'col-md-4 col-form-label')); ?>
        <div class="col-md-8">
            <?php 
            $this->params['textColor'] = "#ff0000";
            ?>
            <div class="input-group color colorpicker-default" data-color="<?php echo $this->params['textColor']; ?>" data-color-format="rgba">
                <input type="text" name="wfmStatusColor" id="wfmStatusColor" class="form-control" value="<?php echo $this->params['textColor']; ?>">
                <span class="input-group-btn">
                    <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo $this->params['textColor']; ?>;"></i>&nbsp;</button>
                </span>
            </div>
            

        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Идэвхтэй', 'for' => 'isActive', 'required' => 'required', 'class' => 'col-md-4 col-form-label')); ?>
        <div class="col-md-8">
            <?php
            echo Form::checkbox(
                    array(
                        'name' => 'isActive',
                        'id' => 'isActive',
                        'value' => '1',
                        'saved_val' => '1'
                    )
            );
            ?>
        </div>
    </div>

    <?php echo Form::close(); ?>

</div>

<script type="text/javascript">
$(function(){
    $('.colorpicker-default').colorpicker({
        format: 'hex'
    });
});    
</script>