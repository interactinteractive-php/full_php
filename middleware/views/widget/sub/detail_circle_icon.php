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
        <div class="mr-2 pt-1">
            <span class="btn rounded-round btn-icon pos-icon">
                <i class="far fa-file-check"></i>
            </span>
        </div>
        <div class="media-body">
            <span class="d-block pos-1">'.$position1.'</span>
            <span class="d-block pos-2">'.$position2.'</span>
            <span class="pos-3">'.$position3.'</span> 
            <span class="pos-4">'.$position4.'</span>
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
.bpdtl-widget-detail_circle_icon .bp-detail-row {
    display: block;
    position: relative;
    padding: 8px 0;
}
.bpdtl-widget-detail_circle_icon .bp-detail-row:last-of-type {
    padding-bottom: 0;
}
.bpdtl-widget-detail_circle_icon .bp-detail-row .media img {
    max-width: 40px;
}
.bpdtl-widget-detail_circle_icon .bp-detail-row .bp-remove-row {
    display: none;
    position: absolute;
    top: -8px;
    right: -2px;
    height: 20px;
    width: 20px;
    border-radius: 100px;
    padding: 0;
}
.bpdtl-widget-detail_circle_icon .bp-detail-row:hover .bp-remove-row {
    display: inline-block;
}
.bpdtl-widget-detail_circle_icon .bp-detail-row-add {
    display: inline;
}
.bpdtl-widget-detail_circle_icon .pos-icon {
    background-color: #e1ebfd;
    color: #5c99e5;
    font-size: 18px;
    font-weight: bold;
    width: 38px;
    height: 38px;
    text-align: center;
    padding-top: 6px;
}
.bpdtl-widget-detail_circle_icon .pos-1 {
    color: #b1b3c1;
    font-size: 11px;
}
.bpdtl-widget-detail_circle_icon .pos-2 {
    font-weight: bold;
    color: #585858;
}
.bpdtl-widget-detail_circle_icon .pos-3 {
    color: #b1b3c1;
    font-size: 11px;
}
.bpdtl-widget-detail_circle_icon .pos-4 {
    color: #b1b3c1;
    font-size: 11px;
    padding-left: 18px;
}
</style>