<?php
echo Form::select(
    array(
        'name' => 'modelId',
        'id' => 'modelId',
        'class' => 'form-control select2',
        'data' => (new Mdmetadata())->getActiveMetaModel(),
        'op_value' => 'MODEL_ID',
        'op_text' => 'MODEL_NAME',
        'required' => 'required', 
        'value' => $this->mlRow['MODEL_ID']
    )
);