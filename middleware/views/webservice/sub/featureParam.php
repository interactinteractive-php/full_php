<?php
$parentColMd = 'col-md-12 ';
$colMd = 'col-md-6';
$featureParam123 = $featureParam456 = $featureParam7 = $featureParam8 = '';

foreach ($this->paramData as $param) {
    
    if ($param['FEATURE_NUM'] == '1' || $param['FEATURE_NUM'] == '2' || $param['FEATURE_NUM'] == '3') {
        
        $labelAttr = array(
            'text' => $this->lang->line($param['META_DATA_NAME']),
            'for' => 'param[' . $param['META_DATA_CODE'] . ']',
            'class' => 'col-form-label col-md-4',
            'data-label-path' => $param['META_DATA_CODE']
        );
        if ($param['IS_REQUIRED'] == '1') {
            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
        }
                
        $featureParam123 .= '<div class="form-group row fom-row bp-param-cell" data-section-path="'.$param['PARAM_REAL_PATH'].'">';
        $featureParam123 .= Form::label($labelAttr);
                    
        $featureParam123 .= '<div class="col-md-8">'.
                            Mdwebservice::renderParamControl(
                                $this->methodId, $param, 'param[' . $param['META_DATA_CODE'] . ']', $param['META_DATA_CODE'], $this->fillParamData
                            ).'
                            </div>
                        <div class="clearfix w-100"></div>
                    </div>';
        
    } elseif ($param['FEATURE_NUM'] == '4' || $param['FEATURE_NUM'] == '5' || $param['FEATURE_NUM'] == '6') {
        
        $labelAttr = array(
            'text' => $this->lang->line($param['META_DATA_NAME']),
            'for' => 'param[' . $param['META_DATA_CODE'] . ']',
            'class' => 'col-form-label col-md-4',
            'data-label-path' => $param['META_DATA_CODE']
        );
        if ($param['IS_REQUIRED'] == '1') {
            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
        }
                
        $featureParam456 .= '<div class="form-group row fom-row bp-param-cell" data-section-path="'.$param['PARAM_REAL_PATH'].'">';
        $featureParam456 .= Form::label($labelAttr);
                    
        $featureParam456 .= '<div class="col-md-8">'.
                            Mdwebservice::renderParamControl(
                                $this->methodId, $param, 'param[' . $param['META_DATA_CODE'] . ']', $param['META_DATA_CODE'], $this->fillParamData
                            ).'
                            </div>
                        <div class="clearfix w-100"></div>
                    </div>';
        
    } elseif ($param['FEATURE_NUM'] == '7') {
        
        $parentColMd = 'col-md-9 ';
        
        $labelAttr = array(
            'text' => $this->lang->line($param['META_DATA_NAME']),
            'for' => 'param[' . $param['META_DATA_CODE'] . ']',
            'data-label-path' => $param['META_DATA_CODE']
        );
        if ($param['IS_REQUIRED'] == '1') {
            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
        }
                    
        $featureParam7 .= '<div class="col-md-3 warehouse-header-sum-price">
                <div class="sum-price-control bp-param-cell" data-section-path="'.$param['PARAM_REAL_PATH'].'">'.
                    Form::label($labelAttr).
                    Mdwebservice::renderParamControl(
                        $this->methodId, $param, 'param[' . $param['META_DATA_CODE'] . ']', $param['META_DATA_CODE'], $this->fillParamData
                    ).'
                </div>
            </div>';
        
    } elseif ($param['FEATURE_NUM'] == '8') {
        
        $colMd = 'col-md-5';
        $filePath = Mdwebservice::findRowKeyValFillData($this->fillParamData, $param['META_DATA_CODE']);
        
        $featureParam8 .= '<div class="col-md-2">';
        
            if (strtolower($param['META_TYPE_CODE']) === 'file') {
                if ($this->fillParamData)
                    $featureParam8 .= "<input type='hidden' name='updateFileData' value='" . $filePath . "'>";

                    $featureParam8 .= '<div class="boot-file-input-wrap">';

                    $param = array_merge($param, array('IS_REQUIRED' => '0'));
                    $featureParam8 .=  Mdwebservice::renderParamControl(
                        $this->methodId, $param, 'param[' . $param['META_DATA_CODE'] . ']', $param['META_DATA_CODE'], $this->fillParamData
                    );

                    $featureParam8 .= '</div><div class="mb5"></div>';

            } else {

                $featureParam8 .= '<div class="form-group row fom-row bp-param-cell" data-section-path="'.$param['PARAM_REAL_PATH'].'">';

                $labelAttr = array(
                    'text' => $this->lang->line($param['META_DATA_NAME']),
                    'for' => 'param[' . $param['META_DATA_CODE'] . ']',
                    'class' => 'col-form-label col-md-4',
                    'data-label-path' => $param['META_DATA_CODE']
                );
                if ($param['IS_REQUIRED'] == '1') {
                    $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                }
                $featureParam8 .= Form::label($labelAttr);

                $featureParam8 .= '<div class="col-md-8">';
                
                $featureParam8 .= Mdwebservice::renderParamControl(
                    $this->methodId, $param, 'param[' . $param['META_DATA_CODE'] . ']', $param['META_DATA_CODE'], $this->fillParamData
                );

                $featureParam8 .= '</div><div class="clearfix w-100"></div></div>';        
            }
        $featureParam8 .= '</div>';
    }
    
}
?>
<div class="warehouse-header-row bp-header-param">
    <div class="warehouse-header-content">
        <div class="<?php //echo $parentColMd; ?> warehouse-header-control d-flex">
            <?php echo $featureParam8; ?>
            <div class="<?php echo $colMd; ?>">
                <?php echo $featureParam123; ?>
            </div>
            <div class="<?php echo $colMd; ?>">
                <?php echo $featureParam456; ?>
            </div> 
        </div>
        <?php echo $featureParam7; ?>
    </div>
</div>