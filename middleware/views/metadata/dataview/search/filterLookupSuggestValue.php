<?php
echo Form::create(array('name' => 'dv-filter-lookup-suggest', 'method' => 'post'));
echo Form::multiselect(
    array(
        'multiple' => 'multiple',
        'name' => 'filter[]',
        'class' => 'listbox',
        'data' => $this->comboData, 
        'op_value' => $this->id, 
        'op_text' => $this->name, 
        'value' => $this->selected, 
        'nonulloption' => true
    )
);
echo Form::close();
?>