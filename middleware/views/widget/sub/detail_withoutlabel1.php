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
    $controls = array();
    
    foreach ($row['data'] as $ind => $val) {
        
        if ($val['IS_SHOW'] != '1') {
            
            $controls[] = Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);
            
        } else {
            
            $inlineStyle = '';
            $width = $val['COLUMN_WIDTH'];
            
            if ($width) {
                $inlineStyle = 'width: '.(is_numeric($width) ? $width.'px' : $width);
            }
            
            $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="float-left line-height-normal" style="'.$inlineStyle.'">';

                $controls[] = Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);

            $controls[] = '</div>';
        }
    }
    
    echo implode('', $controls);
    ?>
    <div class="clearfix"></div>
</div>
<?php
}
?>

<style type="text/css">
    .bpdtl-widget-detail_withoutlabel1 .bp-detail-row {
        padding: 10px 0;
        border-bottom: 1px #ddd solid;
    }
    .bpdtl-widget-detail_withoutlabel1 .bp-detail-row:hover {
        background-color: rgba(0,0,0,.03);
    }
    .bpdtl-widget-detail_withoutlabel1 .bp-detail-row div[data-cell-path] {
        padding: 0 5px 0 10px;
    }
</style>