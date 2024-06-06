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
        
        if ($val['THEME_POSITION_NO']) {
                
            $position[$val['THEME_POSITION_NO']] = issetParam($rowData[$val['LOWER_PARAM_NAME']]);
        }
            
        $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';

            $controls[] = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);

        $controls[] = '</div>';
    }
    
    $controls[] = '<div class="media">
        <div class="mr-2">
            <img src="'.issetParam($position[1]).'" onerror="onUserImgError(this);" class="rounded-circle" title="'.issetParam($position[2]).'">
        </div>
        <div class="media-body">
            <div class="font-weight-semibold">'.issetParam($position[2]).'</div>
            <span class="text-muted">'.issetParam($position[3]).'</span>
        </div>
    </div>';
    
    $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
    
    echo implode('', $controls);
    ?>
</div>
<?php
}
?>

<style type="text/css">
    .bpdtl-widget-detail_user_card_001 .tbody {
        display: inline;
    }
    .bpdtl-widget-detail_user_card_001 .bp-detail-row {
        display: inline-block;
        /*width: 200px;*/
        position: relative;
        padding: 0;
        margin-bottom: 5px;
        margin-right: 8px;
    }
    .bpdtl-widget-detail_user_card_001 .bp-detail-row .media {
        margin-top: .5rem;
    }
    .bpdtl-widget-detail_user_card_001 .bp-detail-row .media img.rounded-circle {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
    .bpdtl-widget-detail_user_card_001 .bp-detail-row .bp-remove-row {
        display: none;
        position: absolute;
        top: 0;
        right: 0;
        height: 20px;
        width: 20px;
        border-radius: 100px;
        padding: 0;
    }
    .bpdtl-widget-detail_user_card_001 .bp-detail-row:hover .bp-remove-row {
        display: inline-block;
    }
</style>