<style type="text/css">
    .bpdtl-widget-detail_buttons .tbody {
        display: inline;
    }
    .bpdtl-widget-detail_buttons .bp-detail-row {
        display: inline-block;
        position: relative;
        padding: 0;
        margin-bottom: 5px;
        margin-right: 2px;
    }
    .bpdtl-widget-detail_buttons .bp-detail-row > .btn {
        padding: 4px 12px;
        line-height: 20px;
        background-color: #fff;
        border-width: 1px;
        border-style: solid;
        border-color: #41c7ae;
        border-radius: 100px;
        color: #41c7ae;
        text-align: center;
        font-weight: normal;
    }
    .bpdtl-widget-detail_buttons .bp-detail-row-add {
        display: inline;
    }
    .bpdtl-widget-detail_buttons .bp-detail-row .bp-remove-row {
        display: none;
        position: absolute;
        top: -12px;
        right: -2px;
        height: 20px;
        width: 20px;
        border-radius: 100px;
        padding: 0;
    }
    .bpdtl-widget-detail_buttons .bp-detail-row:hover .bp-remove-row {
        display: inline-block;
    }
</style>

<?php
$row = $this->row;

if (issetParam($row['viewMode']) == 'view') {
    $renderParamControlFnc = 'renderViewParamControl';
} else {
    $renderParamControlFnc = 'renderParamControl';
}

foreach ($this->fillParamData as $rk => $rowData) {
?>
<div class="bp-detail-row saved-bp-row">
    <input type="hidden" name="param[<?php echo $row['code']; ?>.mainRowCount][]" value="<?php echo $rk; ?>"/>
    <input type="hidden" name="param[<?php echo $row['code']; ?>.rowState][<?php echo $rk; ?>][]" data-path="<?php echo $row['code']; ?>.rowState" data-field-name="rowState" data-isclear="0" value="unchanged">
    
    <?php
    $buttonPath = '';
    $controls = $position = array();
    
    foreach ($row['data'] as $ind => $val) {
        
        if ($val['THEME_POSITION_NO']) {
                
            $position[$val['THEME_POSITION_NO']] = issetParam($rowData[$val['LOWER_PARAM_NAME']]);
            
            if ($val['THEME_POSITION_NO'] == '1') {
                $buttonPath = $val['PARAM_REAL_PATH'];
            }
        } 
        
        if ($val['THEME_POSITION_NO'] != '1') {
            
            $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';
                $controls[] = Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);
            $controls[] = '</div>';
        }
    }
    
    $style = $icon = $rightIcon = '';
    
    if (isset($position[2])) {
        $color = $position[2];
        $style = ' style="border-color: '.$color.'; color: '.$color.';"';
    }
    
    if (isset($position[3])) {
        $icon = '<i class="far '.$position[3].'"></i> ';
    }
    
    if (isset($position[4]) && $position[4] != '' && $position[4] != '0') {
        $rightIcon = ' <i class="far fa-key"></i> ';
    }
    
    $controls[] = '<button type="button" class="btn btn-warning btn-circle btn-sm" data-path="' . $buttonPath . '"'.$style.'>'.$icon.issetDefaultVal($position[1], 'Position 1').$rightIcon.'</button>';
    
    echo implode('', $controls);
    ?>
</div>
<?php
}
?>