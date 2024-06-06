<?php
echo Form::select(
    array(
        'name' => 'REPORT_MODEL_ID',
        'id' => 'REPORT_MODEL_ID',
        'class' => 'form-control select2',
        'data' => (new Mdmetadata())->getActiveDmReportModel(),
        'op_value' => 'REPORT_MODEL_ID',
        'op_text' => 'REPORT_MODEL_NAME',
        'required' => 'required'
    )
);
?> 