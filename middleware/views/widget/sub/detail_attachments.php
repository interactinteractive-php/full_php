<?php
if (!isset($ws)) {
    $ws = new Mdwebservice();
}

$row = $this->row;
$isView = (issetParam($row['viewMode']) == 'view') ? true : false;

foreach ($this->fillParamData as $rk => $rowData) {
?>
<div class="bp-detail-row saved-bp-row">
    <input type="hidden" name="param[<?php echo $row['code']; ?>.mainRowCount][]" value="<?php echo $rk; ?>"/>
    <input type="hidden" name="param[<?php echo $row['code']; ?>.rowState][<?php echo $rk; ?>][]" data-path="<?php echo $row['code']; ?>.rowState" data-field-name="rowState" data-isclear="0" value="unchanged">
    
    <?php
    $controls = $position = array();
    
    foreach ($row['data'] as $ind => $val) {
                
        if ($val['THEME_POSITION_NO']) {

            $position[$val['THEME_POSITION_NO']] = issetParam($rowData[$val['LOWER_PARAM_NAME']]);

            if ($val['THEME_POSITION_NO'] == 4) {
                $val['IS_REQUIRED'] = 0;
            }
        } 

        $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';
            
            if ($val['RECORD_TYPE'] == 'row' || ($val['RECORD_TYPE'] == 'rows' && $val['IS_SHOW'] == '1')) {
                $arg = array('parentRecordType' => 'rows');
                $controls[] = $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], $rowData, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
            } else {
                $controls[] = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);
            }

        $controls[] = '</div>';
    }
    
    $iconName = '<i class="icon-file-empty text-warning-400 mt2"></i>';
    $extension = issetParam($position[1]);
    
    if ($extension == 'pdf') {
        $iconName = '<i class="icon-file-pdf text-danger-600 mt2"></i>';
    } else if ($extension == 'xls' || $extension == 'xlsx') {
        $iconName = '<i class="icon-file-excel text-success-600 mt2"></i>';
    } else if ($extension == 'doc' || $extension == 'docx') {
        $iconName = '<i class="icon-file-word text-primary-600 mt2"></i>';
    } else if ($extension == 'zip' || $extension == 'rar') {
        $iconName = '<i class="icon-file-zip text-danger-600 mt2"></i>';
    } else if ($extension == 'mp3' || $extension == 'wav') {
        $iconName = '<i class="icon-file-music text-danger-600 mt2"></i>';
    } else if ($extension == 'mp4' || $extension == 'mov') {
        $iconName = '<i class="icon-file-video text-danger-600 mt2"></i>';
    } else if ($extension == 'png' || $extension == 'gif' || $extension == 'jpg' || $extension == 'jpeg') {
        $iconName = '<i class="icon-file-picture text-info-600 mt2"></i>';
    } 
    
    $fileName = issetParam($position[2]);
    $fileSize = issetParam($position[3]);
    $filePath = issetParam($position[4]);
    
    $attr = ' data-fileurl="'.$filePath.'" data-filename="'.$fileName.'"';
    
    $controls[] = '<div class="media mt0">
        <div class="mr-2"><a href="javascript:;" onclick="bpFilePreview(this);"'.$attr.'>'.$iconName.'</a></div>
        <div class="media-body">
            <a href="javascript:;" onclick="bpFilePreview(this);" '.$attr.' class="font-weight-bold line-height-normal" title="'.$fileName.'">'.$fileName.'</a>
            <span class="text-muted">'.formatSizeUnits($fileSize).'</span>
        </div>
    </div>';
    
    if (!$isView) {
        $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
    }
    
    echo implode('', $controls);
    ?>
</div>
<?php
}
?>