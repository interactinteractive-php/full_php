<?php
if ($this->dataViewSearchData['visible']) {
?>
<form method="post" class="form-horizontal">
    <div class="xs-form">
<?php
foreach ($this->dataViewSearchData['visible'] as $param) {
    
    if (!array_key_exists(0, $param)) {
        
        $labelArr = array(
            'text' => $this->lang->line($param['META_DATA_NAME']),
            'for' => 'param['.$param['META_DATA_CODE'].']',
            'class' => 'col-form-label col-md-4 text-right'
        );

        if ($param['IS_REQUIRED'] == '1') {
            $labelArr['required'] = 'required'; 
        }

        if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
            $param['CHOOSE_TYPE'] = 'multi';
        }
        
        $control = Mdcommon::criteriaCondidion(
            $param,     
            Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $this->fillParamData)
        );
        
        echo '<div class="form-group row">
            ' . Form::label($labelArr) . '
            <div class="col-lg-8">
                ' . $control . '
            </div>
        </div>';
        
    } else {
                            
        $metaDataCode = $isRequired = '';

        if (array_key_exists(0, $param)) {
            $metaDataCode = $param[0]['META_DATA_CODE'];
            $isRequired = $param[0]['IS_REQUIRED'];
        } elseif (array_key_exists(1, $param)) {
            $metaDataCode = $param[1]['META_DATA_CODE'];
            $isRequired = $param[1]['IS_REQUIRED'];
        }

        $labelArr = array(
            'text' => $this->lang->line('date'),
            'for' => 'param['.$metaDataCode.']',
            'class' => 'col-form-label col-md-4 text-right'
        );
        if ($isRequired == '1') {
            $labelArr['required'] = 'required'; 
        }
        
        $control = '';
        
        if (array_key_exists(0, $param)) {
            $control = Mdwebservice::renderParamControl($this->dataViewId, $param[0], 'param['.$param[0]['META_DATA_CODE'].']', $param[0]['META_DATA_CODE'], $this->fillParamData); 
        }
        if (array_key_exists(1, $param)) {
            $control .= html_tag('div', array('class' => 'float-left pt5'), '<i class="icon-dash font-size-12"></i>');
            $control .= Mdwebservice::renderParamControl($this->dataViewId, $param[1], 'param['.$param[1]['META_DATA_CODE'].']', $param[1]['META_DATA_CODE'], $this->fillParamData); 
        }
        
        echo '<div class="form-group row">
            ' . Form::label($labelArr) . '
            <div class="col-lg-8 date-float-left">
                ' . $control . '
            </div>
        </div>';
    }
}
?>
    </div>    
</form>
<?php
}
?>