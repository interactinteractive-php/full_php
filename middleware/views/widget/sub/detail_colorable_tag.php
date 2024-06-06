<?php
$row = $this->row;

foreach ($this->fillParamData as $rk => $rowData) {
?>
<div class="bp-detail-row saved-bp-row">
    <input type="hidden" name="param[<?php echo $row['code']; ?>.mainRowCount][]" value="<?php echo $rk; ?>"/>
    <input type="hidden" name="param[<?php echo $row['code']; ?>.rowState][<?php echo $rk; ?>][]" data-path="<?php echo $row['code']; ?>.rowState" data-field-name="rowState" data-isclear="0" value="unchanged">
    
    <?php
    $controls = $position = array();
    
    foreach ($row['data'] as $ind => $val) {
        
        if ($val['IS_SHOW'] != '1') {
            
            $controls[] = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);
            
        } else {
            
            if ($val['THEME_POSITION_NO']) {
                
                $position[$val['THEME_POSITION_NO']] = issetParam($rowData[$val['LOWER_PARAM_NAME']]);
            } 
            
            $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';

                $controls[] = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);

            $controls[] = '</div>';
        }
    }
    
    $controls[] = '<span class="badge badge-primary badge-pill" style="background-color:'.issetParam($position[2]).'">'.issetDefaultVal($position[1], 'no tag').'</span>';
    
    $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
    
    echo implode('', $controls);
    ?>
</div>
<?php
}
?>