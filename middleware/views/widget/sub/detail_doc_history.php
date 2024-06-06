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
    
    $extension = issetParam($position[1]);
    
    if ($extension == 'pdf' || $extension == 'xls' 
        || $extension == 'xlsx' || $extension == 'doc' 
        || $extension == 'docx' || $extension == 'ppt' || $extension == 'pptx') {
        
        $iconName = '<img src="assets/core/global/img/filetype/64/'.$extension.'.png">';
        
    } else if ($extension == 'png' || $extension == 'gif' || $extension == 'jpg' || $extension == 'jpeg') {
        
        $iconName = '<img src="assets/core/global/img/filetype/64/jpeg.png">';
        
    } else if ($extension == 'zip' || $extension == 'rar') {
        $iconName = '<img src="assets/core/global/img/filetype/64/rar.png">';
    } else if ($extension == 'mp3' || $extension == 'wav') {
        $iconName = '<img src="assets/core/global/img/filetype/64/rar.png">';
    } else if ($extension == 'mp4' || $extension == 'mov') {
        $iconName = '<img src="assets/core/global/img/filetype/64/rar.png">';
    } else {
        $iconName = '<img src="assets/core/global/img/filetype/64/rar.png">';
    }
    
    $userName1 = issetParam($position[2]);
    $userNameDate1 = issetParam($position[3]);
    
    $userName2 = issetParam($position[4]);
    $userNameDate2 = issetParam($position[5]);
    $filePath = issetParam($position[6]);
    $fileName = issetParam($position[7]);
    
    $attr = ' data-fileurl="'.$filePath.'" data-filename="'.$fileName.'"';
    
    $controls[] = '<div class="row">';
        $controls[] = '<div class="col" style="min-width: 170px;">';
            $controls[] = '<div class="media mt0">
                <div class="mr-2"><a href="javascript:;" onclick="bpFilePreview(this);"'.$attr.'>'.$iconName.'</a></div>
                <div class="media-body">
                    <span class="font-weight-bold line-height-normal" title="'.$userName1.'">'.$userName1.'</span>
                    <span class="text-muted">'.$userNameDate1.'</span>
                </div>
            </div>';
        $controls[] = '</div>';
        $controls[] = '<div class="col-md-auto text-center" style="width: 35px">';
            $controls[] = '<i class="far fa-long-arrow-right font-size-20 text-blue"></i>';
        $controls[] = '</div>';
        $controls[] = '<div class="col">';
            $controls[] = '<div class="media mt0">
                <div class="media-body">
                    <span class="font-weight-bold line-height-normal" title="'.$userName2.'">'.$userName2.'</span>
                    <span class="text-muted">'.$userNameDate2.'</span>
                </div>
            </div>';
        $controls[] = '</div>';
    $controls[] = '</div>';
    
    if (!$isView) {
        $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
    }
    
    echo implode('', $controls);
    ?>
</div>
<?php
}
?>

<style type="text/css">
.bpdtl-widget-detail_doc_history .bp-detail-row {
    display: block;
    position: relative;
    padding: 10px 0;
    border-radius: 8px;
}
.bpdtl-widget-detail_doc_history .bp-detail-row .media-body .line-height-normal {
    margin-bottom: 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    word-break: break-word;
}
.bpdtl-widget-detail_doc_history .bp-detail-row .media i {
    font-size: 38px;
}
.bpdtl-widget-detail_doc_history .bp-detail-row .media img {
    max-width: 40px;
}
.bpdtl-widget-detail_doc_history .bp-detail-row .bp-remove-row {
    display: none;
    position: absolute;
    top: -8px;
    right: -2px;
    height: 20px;
    width: 20px;
    border-radius: 100px;
    padding: 0;
}
.bpdtl-widget-detail_doc_history .bp-detail-row:hover .bp-remove-row {
    display: inline-block;
}
.bpdtl-widget-detail_doc_history .bp-detail-row-add {
    display: inline;
}
</style>