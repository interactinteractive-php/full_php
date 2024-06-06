<?php
if ($this->buttonCriterias) {
    foreach ($this->buttonCriterias as $param) {
        
        echo html_tag('div', array('class' => 'd-inline-block ml10 mr5'), $this->lang->line($param['META_DATA_NAME']).':');
        
        echo Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $this->fillParamData); 
        
        echo html_tag('button', array('class' => 'btn btn-sm dv-button-criteria-play'), '<i class="icon-play4"></i>');
    }
}