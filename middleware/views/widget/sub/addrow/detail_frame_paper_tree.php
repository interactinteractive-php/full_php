<?php
$row = $this->row;

if ($row['groupKeyLookupMeta'] != '' && $row['isShowMultipleKeyMap'] != '0' && $row['isShowAdd'] === '1') {
?>
<div class="bp-detail-row-add bp-add-one-row mb10">
    <?php
    echo Form::button(
        array(
            'data-action-path' => $row['code'], 
            'class' => 'btn btn-xs green-meadow',
            'style' => '', 
            'value' => '<i class="icon-plus3 font-size-12"></i>', 
            'title' => $this->lang->line('add_btn'), 
            'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupKeyLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'autocomplete\', \'detail_frame_paper_tree_basket_function\');'
        )
    );
    ?>
</div>
<?php
}
?>

<?php
$cache = phpFastCache();
$bpDtlAddHtml = $cache->get('bpDtlAddDtl_'.$this->methodId.'_'.$row['id']);

if ($bpDtlAddHtml == null) {
    if (!isset($ws)) {
        $ws = new Mdwebservice();
    }

    $renderParamControlFnc = 'renderParamControl';
    $controls = array('<div class="bp-detail-row saved-bp-row" style="display:flex">
        <input type="hidden" name="param['.$row['code'].'.mainRowCount][]" value="0"/>
        <input type="hidden" name="param['.$row['code'].'.rowState][<?php echo $rk; ?>][]" data-path="'.$row['code'].'.rowState" data-field-name="rowState" data-isclear="0" value="unchanged">'
    );
    $position = array();

    foreach ($row['data'] as $ind => $val) {
                    
        if ($val['THEME_POSITION_NO']) {
            $position[$val['THEME_POSITION_NO']] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" style="width:' . $val['COLUMN_WIDTH'] . '">'.
                Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $val['PARAM_REAL_PATH']).
            '</div>';
        } else {
            $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';
                $controls[] = Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $val['PARAM_REAL_PATH']);
            $controls[] = '</div>';
        }
    }

    $position1 = issetParam($position[1]);
    $position2 = issetParam($position[2]);

    $controls[] = $position1;
    $controls[] = $position2;

    $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
    $controls[] = '</div>';

    $dtlHtml = implode('', $controls);

    $bpDtlAddHtml = Str::remove_doublewhitespace(str_replace(array("\r\n", "\n", "\r"), '', $dtlHtml));
    $cache->set('bpDtlAddDtl_'.$this->methodId.'_'.$row['id'], $bpDtlAddHtml, Mdwebservice::$expressionCacheTime);
}        
?>