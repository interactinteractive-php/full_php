<?php
if (isset($this->dataGridSearchFields) && $this->dataGridSearchFields) {
    
    $useMandatory = isset($this->useMandatoryCriteria) ? ($this->useMandatoryCriteria ? '1' : '0') : '0';
    $fieldsCount = count($this->dataGridSearchFields);
    
    foreach ($this->dataGridSearchFields as $param) {
        
        if ($param['IS_CRITERIA_SHOW_BASKET'] == '1') {
            if ($fieldsCount == 1 && $useMandatory) {
                $param['IS_SHOW'] = 0;
                echo '<div class="d-none">';
            } else {
                echo '<div class="px-2 mb-2">';
            }
        } else {
            
            $lowerPath = strtolower($param['META_DATA_CODE']);
            
            if (isset($this->criteriaParams[$lowerPath])) {
                $param['DEFAULT_VALUE'] = $this->criteriaParams[$lowerPath];
            }
            
            if ($param['DEFAULT_VALUE'] == '') {
                continue;
            }
                
            $param['IS_SHOW'] = 0;
            echo '<div class="d-none">';    
        }
        
        if ($param['IS_MANDATORY_CRITERIA'] !== '1' /*&& !$useMandatory*/) {
            echo Form::label(array('text' => $this->lang->line($param['META_DATA_NAME']), 'style'=> 'margin-bottom:2px;')); 
            echo '<div class="row">';
                echo '<div class="col-3 pr-0 dv-filter-criteria-condition">';
                    echo Form::select(
                        array(
                            'name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                            'id' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                            'class' => 'form-control form-control-sm right-radius-zero float-right',
                            'op_value' => 'value',
                            'op_text' => 'code',
                            'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                            'text' => 'notext', 
                            'value' => ($param['DEFAULT_OPERATOR'] ? $param['DEFAULT_OPERATOR'] : ($param['META_TYPE_CODE'] == 'string' ? 'like' : ''))
                        )
                    ); 
                echo '</div>';

                echo '<div class="col">';
                    echo Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], false); 
                echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';    
    }
}