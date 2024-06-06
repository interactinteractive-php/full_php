<?php
echo Form::select(
    array(
        'name' => 'chartId',
        'id' => 'chartId',
        'class' => 'form-control select2',
        'data' => (new Mdmetadata())->getDmChart(),
        'op_value' => 'CHART_ID',
        'op_text' => 'CHART_NAME',
        'required' => 'required'
    )
);
?> 