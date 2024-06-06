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
            $controls[] = Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);
        $controls[] = '</div>';
    }

    $position1 = issetParam($position[1]);
    $position2 = issetParam($position[2]);
    $position3 = issetParam($position[3]);
    $position4 = issetParam($position[4]);

    $controls[] = '<div class="media mt0">
        <div class="mr-2">
            <img src="'.$position4.'" class="rounded-circle" width="40" height="40" onerror="onUserLogoError(this);">
        </div>
        <div class="media-body">
            <span class="d-block note-descr">
                <span class="pos-1">'.$position1.'</span>
                <span class="pos-2">'.$position2.'</span>
            </span>
            <span class="pos-3">'.$position3.'</span> 
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

<style type="text/css">
.bpdtl-widget-detail_notes .bp-detail-row {
    display: block;
    position: relative;
    padding: 7px 0;
}
.bpdtl-widget-detail_notes .bp-detail-row:last-of-type {
    padding-bottom: 0;
}
.bpdtl-widget-detail_notes .bp-detail-row .media img {
    max-width: 40px;
}
.bpdtl-widget-detail_notes .bp-detail-row .bp-remove-row {
    display: none;
    position: absolute;
    top: -8px;
    right: -2px;
    height: 20px;
    width: 20px;
    border-radius: 100px;
    padding: 0;
}
.bpdtl-widget-detail_notes .bp-detail-row:hover .bp-remove-row {
    display: inline-block;
}
.bpdtl-widget-detail_notes .bp-detail-row-add {
    display: inline;
}
.bpdtl-widget-detail_notes .note-descr {
    border-radius: 8px;
    background-color: #f2f2f2;
    padding: 8px 10px;
}
.bpdtl-widget-detail_notes .note-descr .pos-1 {
    display: block;
    color: #afafaf;
    font-size: 11px;
}
.bpdtl-widget-detail_notes .note-descr .pos-2 {
    display: block;
    font-weight: bold;
    padding-top: 5px;
    color: #585858;
}
.bpdtl-widget-detail_notes .pos-3 {
    display: block;
    color: #b1b3c1;
    font-size: 11px;
    padding-top: 7px;
    padding-left: 10px;
}
</style>