<?php
if (!isset($ws)) {
    $ws = new Mdwebservice();
}

$row = $this->row;
$isView = (issetParam($row['viewMode']) == 'view') ? true : false;


if ($isView) {
    $renderParamControlFnc = 'renderViewParamControl';
} else {
    $renderParamControlFnc = 'renderParamControl';
}

foreach ($this->fillParamData as $rk => $rowData) {
?>
    <div class="bp-detail-row saved-bp-row" style="display:flex">
        <input type="hidden" name="param[<?php echo $row['code']; ?>.mainRowCount][]" value="<?php echo $rk; ?>"/>
        <input type="hidden" name="param[<?php echo $row['code']; ?>.rowState][<?php echo $rk; ?>][]" data-path="<?php echo $row['code']; ?>.rowState" data-field-name="rowState" data-isclear="0" value="unchanged">
        
        <?php
        $controls = $position = array();
        
        foreach ($row['data'] as $ind => $val) {
                    
            if ($val['THEME_POSITION_NO']) {
                $position[$val['THEME_POSITION_NO']] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" style="width:' . $val['COLUMN_WIDTH'] . '">'.
                    Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData).
                '</div>';
            } else { 
                $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';
                    $controls[] = Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);
                $controls[] = '</div>';
            }
        }

        $position1 = issetParam($position[1]);
        $position2 = issetParam($position[2]);

        $controls[] = $position1;
        $controls[] = $position2;
        
        if (!$isView) {
            $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
        }
        
        echo implode('', $controls);
        ?>
    </div>
<?php
}
?>