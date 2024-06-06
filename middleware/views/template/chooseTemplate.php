<div class="col-md-12">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Темплейт', 'for' => 'template', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php
            echo Form::select(
                array(
                    'name' => 'templateId',
                    'id' => 'templateId',
                    'required' => 'required',
                    'class' => 'form-control select2 form-control-sm input-xxlarge',
                    'data' => $this->templates,
                    'op_value' => 'ID',
                    'op_text' => 'META_DATA_NAME'
                )
            );
            ?>
        </div>
    </div>  
</div>