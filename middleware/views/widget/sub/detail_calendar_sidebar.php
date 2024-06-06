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
?>
<?php 
$groupName = '';
foreach ($this->fillParamData as $rk => $rowData) { 
    $controls = $position = array();
        
    foreach ($row['data'] as $ind => $val) {
        if ($val['THEME_POSITION_NO'] === '1') {
            $groupName = $val['LOWER_PARAM_NAME'];
        }
    }
}
if ($groupName) {
    $groupFillParamData = Arr::groupByArrayOnlyRows($this->fillParamData, $groupName);
    $index__ = 1;
    foreach ($groupFillParamData as $gname => $fillParamRow) {  ?>
        <div class="card-group-control card-group-control-right card-calendar-sidebar">
            <div class="card bg-transparent">
                <div class="card-header">
                    <h6 class="card-title">
                        <a data-toggle="collapse" class="side-title" href="#groupBpPath_<?php echo $index__ ?>" aria-expanded="true"><?php echo $gname ?></a>
                    </h6>
                </div>
                <div id="groupBpPath_<?php echo $index__++ ?>" class="collapse show">
                    <div class="card-body py-4 px-3">
                        <?php
                            foreach ($fillParamRow as $rk => $rowData) { ?>
                        
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
                                    $position5 = issetParam($position[5]);
                                    $position6 = issetParam($position[6]);
                                    $position7 = issetParam($position[7]);
                                    $position8 = issetParam($position[8]);
                                    $onclick='';
                                    if ($position6) {
                                        $onclick = 'onclick="callWorkSpace_' . $this->uniqId . '(this, \''. $position6 .'\', \''. $position7 .'\', \''. $position8 .'\')"';
                                    }
                                    $controls[] = '<ul class="media-list mb-2">
                                                        <li class="media">
                                                            <div class="mr-3 position-relative align-self-center">
                                                                <span class="badge badge-mark bg-success mr-1 wh-10" style="background: '. checkDefaultVal($position2, '') .' !important"></span>
                                                            </div>
                            
                                                            <div class="media-body">
                                                                <div class="d-flex justify-content-between pb-1">
                                                                    <label class="side-title" '. $onclick .'>'. checkDefaultVal($position3, '...') .'</label>
                                                                </div>
                                                                '. issetParam($position4) . ' <span class="font-size-sm text-muted">'. checkDefaultVal($position5, '...') .'</span>
                                                            </div>
                                                        </li>
                                                    </ul>';
                                    
                                    if (!$isView) {
                                        $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
                                    }
                                    
                                    echo implode('', $controls);
                                    ?>
                                </div>
                                <?php
                            } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    <?php 
    }
} else {
    echo 'POSITION_1 groupPath тохируулаагүй байна.';
    die;
}
?>
<style type="text/css">
    .card-calendar-sidebar {
        .side-title {
            color: #000000;
            font-size: 14px;
            font-weight: 700;
            line-height: 16px;
            letter-spacing: 0em;
            text-align: left;
        }    

        .wh-10 {
            width: 10px;
            height: 10px;
        }
    }
</style>

<script type="text/javascript">
    <?php if (issetParam($position6)) { ?>
        function callWorkSpace_<?php echo $this->uniqId ?> (elem, workSpaceId, workSpaceName, id) {
            appMultiTab({metaDataId: workSpaceId, title: workSpaceName, type: 'workspace', recordId: id}, elem);
        }
    <?php } ?>
</script>