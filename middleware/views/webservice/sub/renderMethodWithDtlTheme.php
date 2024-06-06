<?php
$ws = new Mdwebservice();
$processsMainContentClassBegin = '';
$processsMainContentClassEnd = '';
$processsDialogContentClassBegin = '';
$processsDialogContentClassEnd = '';
$dialogProcessLeftBanner = '';
$mainProcessLeftBanner = '';
$isBanner = false;

if ($this->isDialog == false) {
    $mainProcessBtnBar = '<div class="meta-toolbar">';

    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', ($this->dmMetaDataId ? true : false)
            );
            if ($this->dmMetaDataId && Input::postCheck('isBackBtnIgnore') == false) {
                $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            }
        }
    } else {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    }

    $reportPrint = '';
    if ($this->isPrint) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 ' . (($this->isEditMode ==
                true) ? '' : 'disabled') . '" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode ==
                true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . $this->lang->line('printTemplate') . '</button>';
    }

    $mainProcessBtnBar .= '<div class="float-right">';
    
    $mainProcessBtnBar .= Form::button(
            array(
                'class' => 'btn btn-info btn-circle btn-sm mr-1 bp-btn-help',
                'value' => $this->lang->line('menu_system_guide'),
                'onclick' => "redirectHelpContent(this, '".$this->helpContentId."', '".$this->methodId."', 'meta_process');"
            ), ($this->helpContentId ? true : false)
            ) . Form::button(
            array(
                'class' => 'btn btn-sm btn-circle btn-success mr5',
                'value' => '<i class="fa fa-arrow-left"></i> ' . $this->lang->line('prev'),
                'onclick' => 'selectPrevNext(\'' . $this->dmMetaDataId . '\', \'' . $this->methodId . '\', this, null, null, \'' . 'transferProcessCriteria' . '\', \'' . $this->batchNumber . '\', -1, -1)'
            ), ($this->isShowPrevNext == '1') ? true : false
            ) . Form::button(
                    array(
                'class' => 'btn btn-sm btn-circle btn-success mr5',
                'value' => '<i class="fa fa-arrow-right"></i> ' . $this->lang->line('next'),
                'onclick' => 'selectPrevNext(\'' . $this->dmMetaDataId . '\', \'' . $this->methodId . '\', this, null, null, \'' . 'transferProcessCriteria' . '\', \'' . $this->batchNumber . '\', 1, 1)'
                    ), ($this->isShowPrevNext == '1') ? true : false
            ) . html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success mr5 bp-btn-saveadd',
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ', \'saveadd\');',
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="fa fa-save"></i> ' . $this->runMode, (!$this->isEditMode) ? (($this->runMode) ? true : false) : false
            ) . html_tag('button', array(
                    'type' => 'button', 
                    'class' => 'btn btn-sm btn-circle hide btn-success mr5 bp-btn-saveedit',
                    'onclick' => 'runAutoEditBusinessProcess(this, \''.$this->dmMetaDataId.'\', \''.$this->uniqId.'\', '.json_encode($this->isEditMode).');', 
                    'data-dm-id' => $this->dmMetaDataId 
                ), 
                '<i class="fa fa-pencil"></i> ' . $this->lang->line('save_btn_edit')
            ) . html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save ' . $this->runMode,
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ');',
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="fa fa-save"></i> ' . $this->processActionBtn
            ) . ((Config::getFromCache('IS_TEST_SERVER')) ? html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success bpTestCaseSaveButton ml5 bp-btn-testcase',
                'onclick' => "saveBusinessProcessTestCase($(this), $(this).closest('form'));",
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="fa fa-save"></i> Тест кэйс хадгалах'
            ) : '') . Form::button(
                    array(
                'class' => 'btn btn-sm btn-circle purple-plum ml5 bp-btn-print',
                'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                'onclick' => 'printProcess(this);'
                    ), isset($this->isPrintView) ? $this->isPrintView : false
            ) . $reportPrint .
            '
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="hide mt10" id="boot-fileinput-error-wrap"></div>
    <div class="clearfix w-100"></div>';

    $mainProcessLeftBanner = $ws->showBanner($this->methodId, 'left', $this->isBanner);
    if ($mainProcessLeftBanner != '') {
        $processsMainContentClassBegin = '<div class="processs-main-content">';
        $processsMainContentClassEnd = '</div>';
        $isBanner = true;
    }
} else {
    $mainProcessBtnBar = '';

    $dialogProcessLeftBanner = $ws->showBanner($this->methodId, 'left', $this->isBanner);
    $mainProcessLeftBanner = '';
    if ($dialogProcessLeftBanner != '') {
        $processsDialogContentClassBegin = '<div class="processs-main-content">';
        $processsDialogContentClassEnd = '</div>';
        $isBanner = true;
    }
}
?>
<div class="xs-form main-action-meta bp-banner-container <?php echo $this->methodRow['SKIN']; ?>" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
     
    <?php
    echo Form::create(array('id' => 'wsForm', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => ($isBanner ? 'bp-banner-content' : '')));
    $isCallNextFunction = '1';
    
    if (isset($this->selectedRowData) && isset($this->newStatusParams) && $this->newStatusParams) {
        $this->selectedRowsData = $this->selectedRowData;
                
        if (isset($this->selectedRowData[0])) {
            if (is_array($this->selectedRowData[0]))
                $this->selectedRowData = $this->selectedRowData[0];
            else
                $this->selectedRowsData = array($this->selectedRowsData);
        } else {
            $this->selectedRowsData = array($this->selectedRowsData);
        }
        $arrayToStrParam = Arr::encode($this->selectedRowsData);
        
        if (isset($arrayToStrParam) && isset($this->newStatusParams) && $this->newStatusParams && $arrayToStrParam) {
            $isCallNextFunction = '0';
        }
    }
    
    if (isset($this->wfmStatusParams['result']) && isset($this->selectedRowData) 
        && isset($this->hasMainProcess) && $this->hasMainProcess 
        && isset($this->wfmStatusBtns) && $this->wfmStatusBtns 
        && isset($this->wfmStatusBtns['result']) && $this->wfmStatusBtns['result']) {
            
        $singleMenuHtml = '<span class="workflowBtn-'. $this->methodId .' bp-wfmstatus-btns"></span>';

        foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
            $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['processname']) . '\', \'\', \'changeHardAssign\',  \'\', \''. $this->uniqId .'\', \''. $this->methodId .'\', undefined , undefined , \'' . $wfmstatusRow['wfmstatusprocessid'] . '\' , \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined , undefined , undefined , \'' . $isCallNextFunction .'\', \'' . $wfmstatusRow['isformnotsubmit'] . '\', \'' . $wfmstatusRow['usedescriptionwindow'] . '\');"';
            $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' class="hidden btn btn-sm purple-plum btn-circle hidden-wfm-status-'. $wfmstatusRow['wfmstatusid']  .'" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['processname'] .'</button> ';
        }

        echo $singleMenuHtml; 
        echo '<hr class="bp-top-hr"/>';
    } 
    
    echo $mainProcessBtnBar;

    echo $this->bpTab['tabStart'];
    
    echo $dialogProcessLeftBanner;
    echo $processsDialogContentClassBegin;
    ?><!-- banner -->
    <div class="row">
        <div class="col-md-12 center-sidebar">  
            
            <?php echo $mainProcessLeftBanner; ?><!-- banner -->
            <?php echo $processsMainContentClassBegin; ?>

            <?php
            $isDtlTbl = false;
            $sidebarShow = false;
            $sidebarShowRowDtl = false;
            
            if ($this->paramList) {
                
                echo $this->checkListStartHtml;
            
                $tabNameArr = array();
                $tabHead = '';
                $tabHeaderHead = '';
                $tabContent = '';
                $tabHeaderContent = '';
                $tabHeaderArr = array();
                $sidebarHeaderArr = array();
                $sidebarDtlRowArr = array();
                $getDtlRowsPopup = array();
                (String) $sidebarContent = '';
                (String) $sidebarGroup = '';
                (String) $sidebarGroupMetaRender = '';
                (String) $sidebarGroupMetaRowsRender = '';
                $tabSecondWidth = 100 - $this->labelWidth;

                $tabActiveFirst = 0;
                foreach ($this->paramList as $k => $row) {
                    if ($row['type'] == 'header') {
                        if (isset($row['data'])) {
                            $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);

                            if (count($buildData['featureParam']) > 0) {
                                echo Mdwebservice::renderFeatureParam($this->methodId, $buildData['featureParam'], $this->fillParamData, $this->isDialog);
                            }
                            $gridHeaderClass = '';
                            ?>
                            <div class="table-scrollable table-scrollable-borderless bp-header-param">
                                <table class="table table-sm table-no-bordered bp-header-param">
                                    <tbody>
                                        <?php
                                        $resetArrIndex = 0;
                                        $ww = 0;
                                        $_seperator = false;
                                        $rows = array_chunk($buildData['onlyShow'], $this->columnCount);
                                        $w = count($rows);
                                        
                                        if ($this->columnCount > 1) {
                                            $columnDividePercent = 100 - ($this->labelWidth * $this->columnCount);
                                            $columnDividePercent = $columnDividePercent / $this->columnCount;
                                        } else {
                                            $columnDividePercent = 55;
                                        }
                                        
                                        $seperatorWidth = $this->columnCount * 2;
                                        
                                        while ($ww < $w) {
                                            $columns = $rows[$ww];

                                            echo "<tr" . ($this->columnCount == 1 ? " data-cell-path='" . $rows[$ww][0]['META_DATA_CODE'] . "'" : "") . ">";
                                            $xx = count($columns);
                                            $xxx = 0;
                                            $hrClass = '';
                                            $colspan = '';

                                            while ($xxx < $xx) {
                                                $gridHeaderClass .= Mdwebservice::fieldHeaderStyleClass($columns[$xxx], 'bp-window-' . $this->methodId);

                                                $sidebarname = trim($columns[$xxx]['SIDEBAR_NAME']);
                                                if (!empty($sidebarname)) {
                                                    $sidebarShow = true;
                                                    $hdrSidebar = Mdwebservice::renderBpHdrSidebar($this->methodId, $columns[$xxx], $this->fillParamData);
                                                    $sidebarHeaderArr[$sidebarname][$columns[$xxx]['META_DATA_CODE']] = $hdrSidebar;
                                                    unset($buildData['onlyShow'][$resetArrIndex++]);
                                                    $xxx++;
                                                    continue;
                                                }

                                                $tabname = trim($columns[$xxx]['TAB_NAME']);
                                                if (!empty($tabname)) {
                                                    if (!in_array($tabname, $tabHeaderArr)) {
                                                        $tabHeaderArr[$resetArrIndex] = $tabname;
                                                        $tabHeaderContentArr[$resetArrIndex] = array();
                                                    }
                                                    $groupKey = array_search($tabname, $tabHeaderArr);
                                                    $tabHeaderContentArr[$groupKey][] = $columns[$xxx];
                                                    $tabHeaderContentArr[$groupKey] = $tabHeaderContentArr[$groupKey];
                                                    unset($buildData['onlyShow'][$resetArrIndex++]);
                                                    $xxx++;
                                                    continue;
                                                }

                                                if (!empty($columns[$xxx]['SEPARATOR_TYPE'])) {
                                                    $_seperator = true;

                                                    if ($this->columnCount == 2)
                                                        if ($xxx % 2 == 0) {
                                                            $colspan = 3;
                                                        }
                                                }
                                                ?>
                                            <td class="text-right middle" data-cell-path="<?php echo $columns[$xxx]['META_DATA_CODE']; ?>" style="width: <?php echo $this->labelWidth; ?>%">
                                                <?php
                                                $labelAttr = array(
                                                    'text' => $this->lang->line($columns[$xxx]['META_DATA_NAME']),
                                                    'for' => "param[" . $columns[$xxx]['META_DATA_CODE'] . "]",
                                                    'data-label-path' => $columns[$xxx]['META_DATA_CODE']
                                                );
                                                if ($columns[$xxx]['IS_REQUIRED'] == '1') {
                                                    $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                                }
                                                echo Form::label($labelAttr);
                                                ?>
                                            </td>
                                            <td class="middle" data-cell-path="<?php echo $columns[$xxx]['META_DATA_CODE']; ?>" style="width: <?php echo $columnDividePercent; ?>%" colspan="<?php echo $colspan; ?>">
                                                <div data-section-path="<?php echo $columns[$xxx]['PARAM_REAL_PATH']; ?>">
                                                    <?php
                                                    echo Mdwebservice::renderParamControl($this->methodId, $columns[$xxx], "param[" . $columns[$xxx]['META_DATA_CODE'] . "]", $columns[$xxx]['META_DATA_CODE'], $this->fillParamData);
                                                    ?>
                                                </div>
                                            </td>
                                            <?php
                                            unset($buildData['onlyShow'][$resetArrIndex++]);
                                            if ($_seperator) {
                                                $hrClass = $columns[$xxx]['SEPARATOR_TYPE'];
                                                $xxx = $xx;
                                            } else
                                                $xxx++;
                                        }
                                        ?>
                                        </tr>
                                        <?php if ($_seperator) { ?>
                                            <tr>
                                                <td colspan="<?php echo $seperatorWidth; ?>">
                                                    <hr class="custom <?php echo $hrClass; ?>">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($_seperator) {
                                            $rows = array_chunk($buildData['onlyShow'], $this->columnCount);
                                            $_seperator = false;
                                            $ww = 0;
                                            $w = count($rows);
                                            continue;
                                        }
                                        $ww++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <style type="text/css">.bp-window-<?php echo $this->methodId; ?> table.bp-header-param{table-layout: fixed;} <?php echo $gridHeaderClass; ?></style>
                                <?php echo $buildData['hiddenParam']; ?>
                            </div>
                            <?php
                        }
                    } elseif ($row['type'] == 'detail') {
                        (Boolean) $isMultiRow = false;
                        (Boolean) $isTab = false;
                        (String) $htmlHeaderCell = '';
                        (String) $htmlBodyCell = '';
                        (String) $htmlGridFoot = '<td></td>';
                        (String) $gridHead = '';
                        (String) $gridHeadFilter = '';
                        (String) $gridBody = '';
                        (String) $gridFoot = '';
                        (String) $gridBodyRow = '';
                        (String) $gridBodyRowAfter = '';
                        (String) $gridTabBody = '';
                        (String) $gridTabContentHeader = '';
                        (String) $gridTabContentBody = '';
                        (String) $gridTabContentHBody = '';
                        (Array) $gridRowTypePath = array();
                        (String) $gridClass = '';
                        (String) $detialView = false;
                        (String) $isAggregate = false;
                        (String) $aggregateClass = '';
                        (Array) $firstLevelRowArr = array();
                        (Array) $sidebarGroupArr_[$row['id']] = array();
                        $isDetailUserConfig = false;

                        if ($row['dataType'] === 'group' && ($row['isRequired'] === '1' || $row['isFirstRow'] === '1')) {
                            $detialView = true;
                        }
                        
                        if (isset($row['data']) && $row['isShow'] == '1') {
                            if ($row['recordtype'] == 'rows') {
                                if (!empty($row['sidebarName']))
                                    continue;

                                $isMultiRow = true;
                            }

                            $gridHead = '<tr>';
                            $gridHeadFilter = '<tr class="bp-filter-row">';
                            $gridHead .= '<th class="rowNumber" style="width:30px;">№</th>';
                            $gridHeadFilter .= '<th></th>';
                            $gridFoot = '<tr>';
                            $gridFoot .= '<td class="number"></td>';
                            $gridBody = '';
                            
                            if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) {
                                $position1 = $position2 = $position3 = $position4 = $position5 = $position6 = $position7 = $position8 = $position9 = $position10 = '';
                                (Array) $position = array();
                            } else {
                                $gridBody .= '<tr class="bp-detail-row">';
                                $gridBody .= '<td class="text-center middle"><span>1</span><input type="hidden" name="param[' . $row['code'] . '.mainRowCount][]"/></td>';
                            }
                            $ii = 0;

                            foreach ($row['data'] as $ind => $val) {

                                $foodAmount = '';
                                $aggregateClass = '';
                                if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) {
                                    $hideClass = '';
                            
                                    if ($val['IS_SHOW'] != '1') {
                                        $hideClass = ' hide';
                                    }
                                    $labelAttr = array(
                                        'text' => $this->lang->line($val['META_DATA_NAME'])
                                    );
                                    if ($val['IS_REQUIRED'] == '1') {
                                        $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                    }
                                    $gridBodyRowLabel = Form::label($labelAttr);
                                    
                                    switch ($val['THEME_POSITION_NO']) {
                                        case '1':
                                            if ($val['LOOKUP_TYPE'] === 'label') {
                                                $position1 .= '<div class="col-form-label-theme">';
                                                    $position1 .= Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $row['code'] . '.' . $val['META_DATA_CODE'], array());
                                                $position1 .= '</div>';
                                            } else {
                                                $position1 .= Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $row['code'] . '.' . $val['META_DATA_CODE'], array());
                                            }

                                            $val['LOOKUP_TYPE'] = '';
                                            if ($val['RECORD_TYPE'] == 'rows') {
                                                $val['LOOKUP_TYPE'] = 'combo';
                                            }
                                            $gridTabContentHBody .= '<tr class="'. $hideClass .'">
                                                                        <td class="text-right middle" data-cell-path="templateCode" style="width: 23%">'.$gridBodyRowLabel.'</td>
                                                                        <td class="text-right middle" data-cell-path="templateCode" style="width: 23%">
                                                                            '. Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $row['code'] . '.' . $val['META_DATA_CODE'], array()) .'
                                                                        </td>
                                                                    </tr>';

                                            break;
                                        case '2':
                                        case '3':
                                        case '4':
                                        case '5':
                                        case '6':
                                        case '7':
                                        case '8':
                                        case '9':
                                        case '10':
                                            (String) $position[$val['THEME_POSITION_NO']] = '';
                                            $position[$val['THEME_POSITION_NO']] = '<div class="row margin-right-15 ml15 '. $hideClass .'" style="margin-left: 3px !important; margin-right: 0px !important;">'
                                                            .'<div class="form-group row fom-row">'
                                                                .'<label class="col-form-label col-md-12 pr0 pl0">'. $this->lang->line($val['META_DATA_NAME']) .'</label>'
                                                                .'<div class="col-md-12 ';
                                                                if ($val['LOOKUP_TYPE'] === 'label') {
                                                                    $position[$val['THEME_POSITION_NO']] .=  'col-form-label-theme ';
                                                                }
                                                        $position[$val['THEME_POSITION_NO']] .=  'pr0 pl0 idcard-registerNum" style="  ">';
                                                        $position[$val['THEME_POSITION_NO']] .= Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $row['code'] . '.' . $val['META_DATA_CODE'], array());
                                                    $position[$val['THEME_POSITION_NO']] .= '</div>';
                                                $position[$val['THEME_POSITION_NO']] .= '</div>';
                                            $position[$val['THEME_POSITION_NO']] .= '</div>';

                                            $gridTabContentHBody .= '<tr class="'. $hideClass .'">
                                                                        <td class="text-right middle" data-cell-path="templateCode" style="width: 23%">'.$gridBodyRowLabel.'</td>
                                                                        <td class="text-right middle" data-cell-path="templateCode" style="width: 23%">
                                                                        '. Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $row['code'] . '.' . $val['META_DATA_CODE'], array()) .'
                                                                        </td>
                                                                    </tr>';
                                            break;

                                        default:
                                            $gridTabContentHBody .= '<tr class="'. $hideClass .'">
                                                                        <td class="text-right middle" data-cell-path="templateCode" style="width: 23%">'.$gridBodyRowLabel.'</td>
                                                                        <td class="text-right middle" data-cell-path="templateCode" style="width: 23%">
                                                                            '. Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $row['code'] . '.' . $val['META_DATA_CODE'], array()) .'
                                                                        </td>
                                                                    </tr>';

                                            break;
                                    }
                                    $isDtlTbl = true;
                                } else {
                                    if ($val['COLUMN_AGGREGATE'] != '') {
                                        $isAggregate = true;
                                        $foodAmount = '0.00';
                                        $aggregateClass = 'aggregate-' . $val['COLUMN_AGGREGATE'];
                                    }

                                    $hideClass = '';
                                    if ($val['IS_SHOW'] != '1') {
                                        $hideClass = ' hide';
                                    }

                                    $paramRealPath = str_replace('.', '', $val['PARAM_REAL_PATH']);
                                    if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) {
                                    $gridBody .= '<div class="row margin-right-15 ml15 '. $hideClass .'" style="margin-left: 3px !important; margin-right: 0px !important;">'
                                                    .'<div class="form-group row fom-row">';
                                        $gridBody .= '<label class="col-form-label col-md-12 pr0 pl0" style="font-size: 9px; margin-top: 5px; margin-bottom: 0;">'. $this->lang->line($val['META_DATA_NAME']) .'</label>';
                                        $gridBody .= '<div class="col-md-12 pr0 pl0 idcard-registerNum" style="  ">';
                                    }
                                    if (strtolower($val['META_TYPE_CODE']) == 'boolean' && $isMultiRow) {
                                        if (empty($val['SIDEBAR_NAME'])) {
                                            $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                            $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></th>';
                                            $gridFoot .= '<td class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></td>';
                                        }
                                    } else {
                                        if (empty($val['SIDEBAR_NAME']) && $isMultiRow && $val['RECORD_TYPE'] !== 'row' && $val['RECORD_TYPE'] !== 'rows') {
                                            $gridHead .= '<th class="' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                            $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"><input type="text"/></th>';
                                            $gridFoot .= '<td class="text-right' . $hideClass . ' ' . $paramRealPath . ' bigdecimalInit" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $foodAmount . '</td>';
                                        }
                                    }

                                    if ($isMultiRow) {
                                        $gridClass .= Mdwebservice::fieldDetailStyleClass($val, $paramRealPath, 'bp-window-' . $this->methodId);

                                        $arg = array(
                                            'parentRecordType' => 'rows'
                                        );
                                        if ($val['RECORD_TYPE'] == 'row') {
                                            if ($val['IS_BUTTON'] == '1') {
                                                ++$ii;
                                                (String) $gridTabActive = '';
                                                if ($ii === 1)
                                                    $gridTabActive = ' active';

                                                $isTab = true;
                                                $arg['isTab'] = 'tab';

                                                array_push($gridRowTypePath, $row['code'] . '.' . $val['META_DATA_CODE']);

                                                $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '" data-li-path="'.$row['code'].'.'.$val['META_DATA_CODE'].'">';
                                                $gridTabContentHeader .= '<a href="#' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                                $gridTabContentHeader .= '</li>';
                                                $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" data-section-path="' . $row['code'] . '.' . $val['META_DATA_CODE'] . '">';
                                                $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], 'row', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                $gridTabContentBody .= '</div>';

                                            } else {

                                                $childRow = Mdwebservice::appendSubRowInProcess($this->uniqId, $gridClass, $this->methodId, $val);
                                                $gridHead .= $childRow['header'];
                                                $gridHeadFilter .= $childRow['headerFilter'];
                                                $gridBody .= $childRow['body'];
                                                $gridFoot .= $childRow['footer'];

                                                if (!empty($childRow['sideBarArr'])) {
                                                    foreach ($childRow['sideBarArr'] as $sdk => $sval) {

                                                        $sidebarShowRowsDtl_[$row['id']] = true;
                                                        if (!in_array($sval['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']])) {
                                                            $sidebarGroupArr_[$row['id']][$ind.$sdk] = $sval['SIDEBAR_NAME'];
                                                            $sidebarDtlRowsContentArr_[$row['id'] . $ind.$sdk] = array();
                                                        }

                                                        $groupKey = array_search($sval['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']]);
                                                        $labelAttr = array(
                                                            'text' => $this->lang->line($sval['META_DATA_NAME']),
                                                            'for' => 'param[' . $sval['PARAM_REAL_PATH'] . '][0][]',
                                                            'data-label-path' => $sval['PARAM_REAL_PATH']
                                                        );
                                                        if ($sval['IS_REQUIRED'] == '1') {
                                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                                        }
                                                        $inHtml = Mdwebservice::renderParamControl($this->methodId, $sval, 'param[' . $sval['PARAM_REAL_PATH'] . '][0][]', $row['code'] . '.' . $sval['META_DATA_CODE'], array());

                                                        $sidebarDtlRowsContentArr_[$row['id'] . $groupKey][] = array(
                                                            'input_label_txt' => Form::label($labelAttr),
                                                            'data_path' => $sval['PARAM_REAL_PATH'],
                                                            'input_html' => $inHtml
                                                        );
                                                        $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'] . $groupKey];

                                                    }
                                                }
                                            }
                                        } elseif ($val['RECORD_TYPE'] == 'rows') {
                                            ++$ii;
                                            (String) $gridTabActive = '';
                                            if ($ii === 1)
                                                $gridTabActive = ' active';

                                            $isTab = true;
                                            $arg['isTab'] = 'tab';
                                            $arg['isShowAdd'] = $val['IS_SHOW_ADD'];
                                            $arg['isShowDelete'] = $val['IS_SHOW_DELETE'];
                                            $arg['isShowMultiple'] = $val['IS_SHOW_MULTIPLE'];
                                            $arg['groupKeyLookupMeta'] = $val['LOOKUP_KEY_META_DATA_ID'];
                                            $arg['isShowMultipleKeyMap'] = $val['IS_MULTI_ADD_ROW_KEY'];
                                            $arg['id'] = $val['META_DATA_CODE'];
                                            $arg['code'] = $val['META_DATA_CODE'];
                                            $arg['paramPath'] = $val['PARAM_REAL_PATH'];
                                            $arg['groupConfigParamPath'] = $val['GROUP_CONFIG_PARAM_PATH_GROUP'];
                                            $arg['groupConfigLookupPath'] = $val['GROUP_CONFIG_FIELD_PATH_GROUP'];
                                            $arg['isFirstRow'] = $val['IS_FIRST_ROW'];

                                            array_push($gridRowTypePath, $row['code'] . '.' . $val['META_DATA_CODE']);

                                            $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '" data-li-path="'.$row['code'].'.'.$val['META_DATA_CODE'].'">';
                                            $gridTabContentHeader .= '<a href="#' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                            $gridTabContentHeader .= '</li>';
                                            $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '">';
                                            $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], 'rows', $val['ID'], null, '', $arg, '', $val['COLUMN_COUNT']);                                        
                                            $gridTabContentBody .= '</div>';

                                        } elseif (empty($val['SIDEBAR_NAME'])) {

                                            $gridBody .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
                                            $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . '.' . $val['META_DATA_CODE'] . "][0][]", $row['code'] . '.' . $val['META_DATA_CODE'], null);
                                            $gridBody .= '</td>';

                                        } else {
                                            $sidebarShowRowsDtl_[$row['id']] = true;
                                            if (!in_array($val['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']])) {
                                                $sidebarGroupArr_[$row['id']][$ind] = $val['SIDEBAR_NAME'];
                                                $sidebarDtlRowsContentArr_[$row['id'] . $ind] = array();
                                            }

                                            $groupKey = array_search($val['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']]);
                                            $labelAttr = array(
                                                'text' => $this->lang->line($val['META_DATA_NAME']),
                                                'for' => 'param[' . $row['code'] . '.' . $val['META_DATA_CODE'] . '][0][]',
                                                'data-label-path' => $row['code'] . '.' . $val['META_DATA_CODE']
                                            );
                                            if ($val['IS_REQUIRED'] == '1') {
                                                $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                            }
                                            /*if ($val['META_TYPE_CODE'] == 'date') {
                                                $inHtml = '<div style="width: 132px; text-align: left;">' . Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array()) . "</div>";
                                            } else {*/
                                                $inHtml = Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array());
                                            //}
                                            $sidebarDtlRowsContentArr_[$row['id'] . $groupKey][] = array(
                                                'input_label_txt' => Form::label($labelAttr),
                                                'data_path' => $row['code'] . "." . $val['META_DATA_CODE'],
                                                'input_html' => $inHtml
                                            );
                                            $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'] . $groupKey];
                                        }
                                    } else {

                                        $gridClass .= Mdwebservice::fieldDetailRowStyleClass($val, 'bp-window-' . $this->methodId);
                                        $arg = array();
                                        if (empty($val['SIDEBAR_NAME'])) {

                                            if ($isMultiRow) {
                                                $gridBody .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput text-center' . $hideClass . '">';
                                                $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], null);
                                                $gridBody .= '</td>';
                                            } else {
                                                if ($val['RECORD_TYPE'] === 'rows') {
                                                    $arg['isShowAdd'] = $val['IS_SHOW_ADD'];
                                                    $arg['isShowDelete'] = $val['IS_SHOW_DELETE'];
                                                    $arg['isShowMultiple'] = $val['IS_SHOW_MULTIPLE'];
                                                    $arg['groupKeyLookupMeta'] = $val['LOOKUP_KEY_META_DATA_ID'];
                                                    $arg['isShowMultipleKeyMap'] = $val['IS_MULTI_ADD_ROW_KEY'];
                                                    $arg['id'] = $val['META_DATA_CODE'];
                                                    $arg['code'] = $val['META_DATA_CODE'];
                                                    $arg['paramPath'] = $val['PARAM_REAL_PATH'];
                                                    $arg['groupConfigParamPath'] = $val['GROUP_CONFIG_PARAM_PATH_GROUP'];
                                                    $arg['groupConfigLookupPath'] = $val['GROUP_CONFIG_FIELD_PATH_GROUP'];  
                                                    $arg['isFirstRow'] = $val['IS_FIRST_ROW'];

                                                    $gridBodyRowAfter .= '<tr class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">';

                                                    if ($val['META_TYPE_CODE'] === 'group' && $val['IS_BUTTON'] == '1') {
                                                        $gridBodyRowAfter .= '<td class="text-right middle float-left" style="width: 18%">';
                                                        $labelAttr = array(
                                                            'text' => $this->lang->line($val['META_DATA_NAME'])
                                                        );
                                                        if ($val['IS_REQUIRED'] == '1') {
                                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                                        }
                                                        $gridBodyRowAfter .= Form::label($labelAttr);
                                                        $gridBodyRowAfter .= '</td>';
                                                        $gridBodyRowAfter .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" style="width: 72%" class="middle float-left">';
                                                        $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                        $gridBodyRowAfter .= '</td>';
                                                    } else {
                                                        $gridBodyRowAfter .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" style="width: 100%" class="middle float-left" colspan="2">';
                                                        $gridBodyRowAfter .= '<p class="meta_description"><i class="fa fa-info-circle"></i> ' . $this->lang->line($val['META_DATA_NAME']) . '</p>';
                                                        $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                        $gridBodyRowAfter .= '</td>';
                                                    }

                                                    $gridBodyRowAfter .= '</tr>';
                                                } else if ($val['RECORD_TYPE'] === 'row') {
                                                    $gridBodyRowAfter .= '<tr class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">';
                                                    $gridBodyRowAfter .= '<td>';
                                                    $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], $this->fillParamData, '', array(), 1, $val['COLUMN_COUNT']);
                                                    $gridBodyRowAfter .= '</td>';
                                                    $gridBodyRowAfter .= '</tr>';
                                                } else {
                                                    array_push($firstLevelRowArr, $val);
                                                }
                                            }
                                        } else {
                                            $sidebarShowRowDtl = true;
                                            $fillParamData = isset($this->fillParamData[strtolower($row['code'])]) ? $this->fillParamData[strtolower($row['code'])] : null;
                                            if (!in_array($val['SIDEBAR_NAME'], $sidebarDtlRowArr)) {
                                                $sidebarDtlRowArr[$ind] = $val['SIDEBAR_NAME'];
                                                $sidebarDtlRowContentArr[$ind] = array();
                                            }

                                            $groupKey = array_search($val['SIDEBAR_NAME'], $sidebarDtlRowArr);
                                            $labelAttr = array(
                                                'text' => $this->lang->line($val['META_DATA_NAME']),
                                                'for' => 'param[' . $row['code'] . '.' . $val['META_DATA_CODE'] . '][0][]',
                                                'data-label-path' => $row['code'] . '.' . $val['META_DATA_CODE']
                                            );
                                            if ($val['IS_REQUIRED'] == '1') {
                                                $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                            }
                                            $sidebarDtlRowContentArr[$groupKey][] = array(
                                                'input_label_txt' => Form::label($labelAttr),
                                                'data_path' => $row['code'] . '.' . $val['META_DATA_CODE'],
                                                'input_html' => Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], $fillParamData)
                                            );
                                            $sidebarDtlRowContentArr[$groupKey] = $sidebarDtlRowContentArr[$groupKey];
                                        }
                                    }


                                    $gridBody .=  '</div>'
                                                        .'<div class="clearfix w-100"></div>'
                                                    .'</div>'
                                                    .'</div>';
                                    $isDtlTbl = true;
                                }
                            }
                            $gridBodyRow .= Mdwebservice::renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount']);
                            $gridBodyRow .= $gridBodyRowAfter;

                            if ($isMultiRow) {
                                $actionWidth = 40;
                                if (isset($sidebarShowRowsDtl_[$row['id']])) {
                                    $actionWidth = 70;
                                }
                                
                                $htmlHeaderCell = '<th class="action ' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '" style="width:' . $actionWidth . 'px;"></th>';
                                $htmlBodyCell .= '<td class="text-center stretchInput middle' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '">';

                                if (isset($sidebarShowRowsDtl_[$row['id']])) {
                                    $htmlBodyCell .= '<a href="javascript:;" onclick="proccessRenderPopup(\'div#bp-window-' . $this->methodId . ':visible\', this);" class="btn btn-xs purple-plum" style="width:21px" title="Popup цонхоор харах"><i class="fa fa-external-link"></i></a>';
                                    $htmlBodyCell .= '<div class="sidebarDetailSection hide">';

                                    if (!empty($sidebarGroupArr_[$row['id']])) {
                                        foreach ($sidebarGroupArr_[$row['id']] as $keyPopGroup => $rowPopGroup) {

                                            $htmlBodyCell .= '<p class="property_page_title">' . $this->lang->line($rowPopGroup) . '</p>' .
                                                    '<div class="panel panel-default bg-inverse grid-row-content">' .
                                                    '<table class="table sheetTable sidebar_detail">' .
                                                    '<tbody>';
                                            foreach ($sidebarDtlRowsContentArr_[$row['id']][$keyPopGroup] as $subrowPopGroup) {
                                                $htmlBodyCell .= "<tr data-cell-path='" . $subrowPopGroup['data_path'] . "'>" .
                                                        "<td style='width: 229px;' class='left-padding'>" . $this->lang->line($subrowPopGroup['input_label_txt']) . "</td>" .
                                                        "<td>" . $subrowPopGroup['input_html'] . "</td>" .
                                                        "</tr>";
                                            }
                                            $htmlBodyCell .= '</tbody></table></div>';
                                        }
                                    }

                                    $htmlBodyCell .= '</div>';
                                }
                                
                                if ($row['isShowDelete'] === '1') {
                                    if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) {}
                                    else {
                                        $htmlBodyCell .= '<a href="javascript:;" class="btn red btn-xs bp-remove-row" title="' . $this->lang->line('delete_btn') . '"><i class="fa fa-trash"></i></a>';
                                        $htmlBodyCell .= '</td>';
                                    }
                                }
                            }

                            if ($isTab) {
                                $gridRowTypePath = implode('|', $gridRowTypePath);
                                $htmlHeaderCell .= '<th style="width:40px" data-cell-path="' . $gridRowTypePath . '"></th>';
                                $gridFoot .= '<td data-cell-path="' . $gridRowTypePath . '"></td>';
                                $gridBody .= '<td data-cell-path="' . $gridRowTypePath . '" class="text-center stretchInput middle">';
                                $gridBody .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="Дэлгэрэнгүй">';
                                $gridBody .= '...';
                                $gridBody .= '</a> ';
                                $gridBody .= '<div class="param-tree-container-tab param-tree-container hide">';
                                $gridBody .= '<div class="tabbable-line">
                                                <ul class="nav nav-tabs">' . $gridTabContentHeader . '</ul>
                                                <div class="tab-content">
                                                    ' . $gridTabContentBody . '
                                                </div>
                                            </div>';
                                $gridBody .= '</div>';
                                $gridBody .= '</td>';
                            }
                            
                            $gridBody .= $htmlBodyCell;
                            
                            if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) {
                                
                                $themeBook = (new Mdmeta())->getThemeBook($row['dtlTheme']);
                                $backgroundColor = ($themeBook) ? $themeBook['BACKGROUND_COLOR'] : '#F9EFAF';
                                
                                $htmlBodyCell .= '<div class="col-md-12" style="padding-right:0px;">';
                                $htmlBodyCell .= '<div class="btn-group float-right">';
                                $htmlBodyCell .=  '<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" style="background: '. $backgroundColor .'; border: none;border-top-left-radius: 4px;border-bottom-left-radius: 0;border-bottom-right-radius: 0px;" data-toggle="dropdown" aria-expanded="false">';
                                $htmlBodyCell .=   '<i class="fa fa-ellipsis-horizontal"></i> <i class="fa fa-cog"></i>';
                                $htmlBodyCell .=   '</button>';
                                $htmlBodyCell .= '<ul class="dropdown-menu float-right">'
                                                    . '<li>'
                                                        . '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\', \'1\');"><i class="fa fa-edit"></i> Засах </a>'
                                                    . '</li>'
                                                    . '<li>'
                                                        . '<a href="javascript:;" class="bp-remove-theme-row"><i class="fa fa-trash"></i> Устгах </a>'
                                                    . '</li>'
                                                . '</ul>'
                                            . '</div>'
                                        . '</div>';

                                $gridBodyDataHidden = '<div class="param-tree-container-tab param-tree-container hide">';
                                    $gridBodyDataHidden .= '<table class="table table-sm table-no-bordered bp-header-param">';
                                        $gridBodyDataHidden .= '<tbody>';
                                            $gridBodyDataHidden .= $gridTabContentHBody;
                                        $gridBodyDataHidden .= '</tbody>';
                                    $gridBodyDataHidden .= '</table>';
                                $gridBodyDataHidden .= '</div>';
                                
                                $gridBody = '<div class="ml15 mb15 bp-new-dtltheme " style="background: '. $backgroundColor .';">'
                                            
                                                . '<div class="mt-card-avatar mt-overlay-1 ">'
                                                    . '<div class="mt-overlay">'
                                                        . '<ul class="mt-info">'
                                                            . '<li>'
                                                                . '<a class="btn default btn-outline" href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\', \'1\');">'
                                                                    . '<i class="fa fa-edit"></i>'
                                                                . '</a>'
                                                            . '</li>'
                                                            . '<li>'
                                                                . '<a class="btn default btn-outline bp-remove-theme-row" href="javascript:;">'
                                                                    . '<i class="fa fa-trash"></i>'
                                                                . '</a>'
                                                            . '</li>'
                                                        . '</ul>'
                                                    . '</div>'
                                                    . '<div class="card-body new-theme">'
                                                        . '<div class="row">'
                                                            . '<div class="col-md-10 pr10 ml15">'
                                                                . '<div class="row title-row">'
                                                                    . '<div class="form-group row fom-row">'
                                                                        . '<label class="col-form-label col-md-12 pr0 pl0 text font-weight-bold title" >'. $position1 .'</label>'
                                                                    . '</div> '
                                                                . '</div> '
                                                            . '</div> '
                                                            . '<div class="col-md-12"><input type="hidden" name="param[' . $row['code'] . '.mainRowCount][]"/>';

                                                        foreach ($position as $pos) {
                                                            $gridBody .= $pos;
                                                        }

                                    $gridBody .= '</div>'
                                                . '</div>'
                                            . '</div>'
                                        .'</div>'
                                    .'<div class="row">' . $gridBodyDataHidden . '</div>'
                                .'</div>';
                            }
                            else {
                                $gridBody .= '</tr>';
                            }

                            $gridHead .= $htmlHeaderCell;
                            $gridHead .= '</tr>';
                            $gridHeadFilter .= $htmlHeaderCell;
                            $gridHeadFilter .= '</tr>';
                            $gridFoot .= '<td class="' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '"></td>';
                            $gridFoot .= '</tr>';
                            
                            $content = '<div class="row" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                                            <div class="col-md-12-none-position">';

                            if ($isMultiRow) {
                                
                                $bpDtlAddHtml = $this->cache->get('bpDtlAddDtl_'.$this->methodId.'_'.$row['id']);

                                if ($bpDtlAddHtml == null) {
                                    $bpDtlAddHtml = Str::remove_doublewhitespace(str_replace(array("\r\n", "\n", "\r"), '', $gridBody));
                                    $this->cache->set('bpDtlAddDtl_'.$this->methodId.'_'.$row['id'], $bpDtlAddHtml, Mdwebservice::$expressionCacheTime);
                                }
                                    
                                $content .= '<div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-md-6">';

                                if ($row['isShowAdd'] === '1') {
                                    if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) { 
                                        $isDtlTheme = true;
                                        $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow float-left mr5 bp-add-one-row', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('addRow'), 'onclick' => 'bpAddMainThemeRow_' . $this->methodId . '(this, \''.$this->methodId.'\', \'' . $row['id'] . '\', \''. $row['dtlTheme'] . '\');'));
                                    } else {
                                        $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow float-left mr5 bp-add-one-row', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('addRow'), 'onclick' => 'bpAddMainRow_' . $this->methodId . '(this, \''.$this->methodId.'\', \'' . $row['id'] . '\');'));
                                    }
                                }

                                if ($row['isShowMultiple'] === '1' && $row['groupLookupMeta'] != '' 
                                        && $row['isShowMultipleMap'] != '0' && $row['dtlTheme'] !== '1' 
                                        && $row['recordtype'] === 'rows') {
                                    $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow mr5 float-left bp-add-multi-row', 'value' => '<i class="icon-plus3 font-size-12"></i> Олноор нэмэх', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'\');'));
                                }

                                if ($row['groupKeyLookupMeta'] != '' && $row['isShowMultipleKeyMap'] != '0') {
                                    $content .= '<div class="input-group quick-item-process float-left bp-add-ac-row" data-action-path="' . $row['code'] . '">';
                                    $content .= '<div class="input-icon">';
                                    $content .= '<i class="far fa-search"></i>';
                                    $content .= Form::text(
                                        array(
                                            'class' => 'form-control form-control-sm lookup-code-hard-autocomplete lookup-hard-autocomplete',
                                            'style' => 'padding-left:25px;',
                                            'data-processid' => $this->methodId,
                                            'data-lookupid' => $row['groupKeyLookupMeta'],
                                            'data-path' => $row['paramPath'],
                                            'data-in-param' => $row['groupConfigParamPath'],
                                            'data-in-lookup-param' => $row['groupConfigLookupPath']
                                        )
                                    );
                                    $content .= '</div>';
                                    $content .= '<span class="input-group-btn">';
                                    $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow bp-group-save',
                                                'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupKeyLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'autocomplete\');'));
                                    $content .= '</span>';
                                    $content .= '</div>';
                                }

                                $content .= '<div class="clearfix w-100"></div>';
                                $content .= '</div>';

                                if ($row['isSave'] == '1') {
                                    $content .= '<div class="col-md-6">
                                                ' . Form::button(array('class' => 'btn btn-xs green-meadow float-right',
                                                'value' => '<i class="fa fa-save"></i> '.$this->lang->line('save_btn'), 'onclick' => 'bpSaveMainRow(this);')) . '
                                            </div>';
                                }
                                $content .= '</div>
                                </div>';
                            }

                            $gridBodyData = '';

                            if ($this->fillParamData) {
                                if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) {
                                    $renderFirstLevelDtl = $ws->renderFirstLevelDtlTheme($this->uniqId, $this->methodId, $row, $getDtlRowsPopup, '1', $this->fillParamData, $row['dtlTheme']);
                                    if ($renderFirstLevelDtl) {
                                        $gridBody = $renderFirstLevelDtl['gridBody'];
                                        $gridBodyRow = $renderFirstLevelDtl['gridBodyRow'];
                                        $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
                                        $isRowState = $renderFirstLevelDtl['isRowState'];
                                    }
                                }
                                else {
                                    $renderFirstLevelDtl = $ws->renderFirstLevelDtl($this->uniqId, $this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
                                    if ($renderFirstLevelDtl) {
                                        $gridBody = $renderFirstLevelDtl['gridBody'];
                                        $gridBodyRow = $ws->renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData);
                                        $gridBodyRow .= $renderFirstLevelDtl['gridBodyRow'];
                                        $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
                                        $isRowState = $renderFirstLevelDtl['isRowState'];
                                    }
                                }
                            }

                            if (empty($gridBodyRow)) {
                                if (!empty($htmlHeaderCell)) {
                                    if ($row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) {
                                        $content .= '<div data-parent-path="'.$row['code'].'" class="row bprocess-table-dtl-theme pb10 mt-element-card mt-element-overlay">'
                                                . $gridBody. $gridBodyData. '</div></div></div>';
                                        if (!$this->fillParamData && $row['dtlTheme'] === '1' && $row['recordtype'] === 'rows' && Config::getFromCache('CONFIG_USE_BP_DTL_THEME')) { 
                                            $content .= '</div>';
                                        }

                                    } else {
                                        
                                        $pagingAttributes = ' data-row-id="'.$row['id'].'"';

                                        if ($isDetailUserConfig) {
                                            $detailUserConfig = $ws->getDetailUserConfig($this->methodId, $row['id'], $row['code']);
                                            $pagingAttributes .= ' data-show-fields="'.$detailUserConfig['showFields'].'" data-hide-fields="'.$detailUserConfig['hideFields'].'"';
                                        }                                        
                                        
                                        $content .= '<div data-parent-path="'.$row['code'].'" class="bp-overflow-xy-auto">
                                                        <style type="text/css">#bp-window-' . $this->methodId . ' .bprocess-table-dtl[data-table-path="' . $row['code'] . '"]{table-layout: fixed !important; max-width: ' . Mdwebservice::$tableWidth . 'px !important;} ' . $gridClass . '</style>
                                                        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="' . $row['code'] . '"' . $pagingAttributes . '>
                                                            <thead>
                                                                ' . $gridHead . $gridHeadFilter . '
                                                            </thead>
                                                            <tbody class="tbody">
                                                                ' . /* is required - one row */($detialView ? $gridBody : '') . $gridBodyData . '
                                                            </tbody>
                                                            <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                                                        </table>    
                                                    </div>
                                                </div>
                                            </div>';
                                    }
                                }
                            } else {
                                if ($row['isSave'] == '1') {
                                    $content .= Form::button(array('class' => 'btn btn-xs green-meadow float-right', 'value' => '<i class="fa fa-save"></i> '.$this->lang->line('save_btn'), 'onclick' => 'bpSaveMainRow(this);'));
                                }
                                $content .= '<div class="table-scrollable table-scrollable-borderless mt0" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                                    <style type="text/css">' . $gridClass . '</style>
                                    <table class="table table-sm table-no-bordered bprocess-table-row">
                                        <tbody>
                                            ' . $gridBodyRow . '
                                        </tbody>
                                        <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                                    </table>    
                                </div>
                            </div>
                        </div>';
                            }

                            if ($row['tabName'] != '') {

                                if (!isset($tabNameArr[$row['tabName']])) {
                                    $tabHeaderContent = '';

                                    if (!empty($tabHeaderArr)) {
                                        foreach ($tabHeaderArr as $tabKey => $tabVal) {
                                            if (Str::lower($row['tabName']) === Str::lower($tabVal)) {
                                                    $tabUniqId = getUID();
                                                    $tabHeaderContent .= '<div class="table-scrollable table-scrollable-borderless bp-header-param">
                                                                    <table class="table table-sm table-no-bordered bp-header-param"><tbody>';
                                                    foreach ($tabHeaderContentArr[$tabKey] as $subrow) {
                                                        $tabHeaderParam = '';
                                                        if ($subrow['IS_SHOW'] != '1') {
                                                            $tabHeaderParam = 'hide';
                                                        }
                                                        $tabHeaderContent .= "<tr data-cell-path='" . $subrow['META_DATA_CODE'] . "' class='" . $tabHeaderParam . "'>";
                                                        $tabHeaderContent .= '<td class="text-right middle" style="width: ' . $this->labelWidth . '%">';
                                                        $labelAttr = array(
                                                            'text' => $this->lang->line($subrow['META_DATA_NAME']),
                                                            'for' => "param[" . $subrow['META_DATA_CODE'] . "]",
                                                            'data-label-path' => $subrow['META_DATA_CODE']
                                                        );
                                                        if ($subrow['IS_REQUIRED'] == '1') {
                                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                                        }
                                                        $tabHeaderContent .= Form::label($labelAttr);
                                                        $tabHeaderContent .= "</td>";
                                                        $tabHeaderContent .= '<td class="middle" style="width: '.$tabSecondWidth.'%">';
                                                        $tabHeaderContent .= '<div data-section-path="' . $subrow['PARAM_REAL_PATH'] . '">';
                                                        $tabHeaderContent .= Mdwebservice::renderParamControl($this->methodId, $subrow, "param[" . $subrow['META_DATA_CODE'] . "]", $subrow['META_DATA_CODE'], $this->fillParamData);
                                                        $tabHeaderContent .= "</div>";
                                                        $tabHeaderContent .= "</td>";
                                                        $tabHeaderContent .= "</tr>";
                                                    }
                                                    $tabHeaderContent .= '</tbody></table></div>';

                                                    unset($tabHeaderArr[$tabKey]);
                                                    unset($tabHeaderContentArr[$tabKey]);
                                            }
                                        }
                                    }

                                    $tabActive = '';
                                    if ($tabActiveFirst === 0) {
                                        $tabActive = ' active';
                                    }
                                    $tabHead .= '<li class="nav-item">
                                            <a href="#tab_' . $this->methodId . '_' . $row['id'] . '" class="nav-link ' . $tabActive . '" data-toggle="tab">' . $this->lang->line($row['tabName']) . '</a>
                                        </li>';

                                    $tabContent .= '<div class="tab-pane' . $tabActive . '" id="tab_' . $this->methodId . '_' . $row['id'] . '">' . $tabHeaderContent . $content . '<!--' . $row['tabName'] . '--></div>';
                                    ++$tabActiveFirst;

                                    $tabNameArr[$row['tabName']] = '';
                                } else {
                                    $tabContent = str_replace('<!--' . $row['tabName'] . '-->', $content . '<!--' . $row['tabName'] . '-->', $tabContent);
                                }
                            } else {
                                echo '<div data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                                    <fieldset class="collapsible">
                                        <legend>' . $this->lang->line($row['name']) . '</legend>
                                        ' . $content . ' 
                                    </fieldset>
                                </div>';
                            }
                        } 
                        
                    }
                }

                $tabHeaderActiveFirst = 0;
                if ($tabHead != '' || !empty($tabHeaderArr)) {
                    if (isset($tabHeaderArr)) {
                        foreach ($tabHeaderArr as $key => $row) {
                            $tabUniqId = getUID();
                            $tabActive = $tabHeaderContent = '';
                            if ($tabHeaderActiveFirst === 0 && empty($tabHead)) {
                                $tabActive = ' active';
                            }
                            $tabHead .= '<li class="nav-item">
                                    <a href="#tab_' . $this->methodId . '_' . $tabUniqId . '" class="nav-link ' . $tabActive . '" data-toggle="tab">' . $this->lang->line($row) . '</a>
                                </li>';
                            
                            $tabHeaderContentData = Mdwebservice::findCriteria($this->methodId . '_' . $key, $tabHeaderContentArr[$key]);
                            
                            if (!empty($tabHeaderContentData) && isset($tabHeaderContentData['dataGroup'])) {
                                foreach ($tabHeaderContentData['dataGroup']['header'] as $headerKey => $headerValue) {
                                    $tabHeaderContent .= '<fieldset class="collapsible"><legend>' . $this->lang->line($headerValue) . 
                                            '</legend><table class="table table-sm table-no-bordered bp-header-param"><tbody>';

                                    foreach ($tabHeaderContentData['dataGroup']['content'][$headerKey] as $paramContent) {
                                        $tabHeaderContent .= Mdwebservice::getTabHeaderContent($this->methodId, $paramContent, $tabSecondWidth, $seperatorWidth, $this->labelWidth, $this->fillParamData);
                                    }

                                    $tabHeaderContent .= '</tbody></table></fieldset>';
                                }
                            }
                            
                            $tabHeaderContent .= '<div class="table-scrollable table-scrollable-borderless bp-header-param">
                                            <table class="table table-sm table-no-bordered bp-header-param"><tbody>';
                            
                            if (!empty($tabHeaderContentData) && isset($tabHeaderContentData['data'])) {
                                foreach ($tabHeaderContentData['data'] as $subrow) {
                                    $tabHeaderContent .= Mdwebservice::getTabHeaderContent($this->methodId, $subrow, $tabSecondWidth, $seperatorWidth, $this->labelWidth, $this->fillParamData);
                                }
                            }
                            
                            $tabHeaderContent .= '</tbody></table></div>';
                            $tabContent .= '<div class="tab-pane' . $tabActive . '" id="tab_' . $this->methodId . '_' . $tabUniqId . '">' . $tabHeaderContent . '</div>';
                            ++$tabHeaderActiveFirst;
                        }
                    }

                    echo '<div class="tabbable-line tabbable-tabdrop bp-tabs">
                        <ul class="nav nav-tabs">' . $tabHead . '</ul>
                        <div class="tab-content">
                        ' . $tabContent . '
                        </div>
                    </div>';
                }
            }
            ?>
            <div id="bprocessCoreParam">
                <?php 
                echo Form::hidden(array('name' => 'methodId', 'value' => $this->methodId)); 
                echo Form::hidden(array('name' => 'processSubType', 'value' => $this->processSubType));
                echo Form::hidden(array('name' => 'create', 'value' => ($this->processActionType == 'insert' ? '1' : '0')));
                echo Form::hidden(array('name' => 'responseType', 'value' => $this->responseType));
                echo Form::hidden(array('name' => 'wfmStatusParams', 'value' => isset($this->newStatusParams) ? $this->newStatusParams : ''));
                echo Form::hidden(array('name' => 'wfmStringRowParams', 'value' => isset($arrayToStrParam) ? $arrayToStrParam : ''));
                echo Form::hidden(array('name' => 'openParams', 'id' => 'openParams', 'value' => $this->openParams));
                echo Form::hidden(array('name' => 'isSystemProcess', 'value' => $this->isSystemProcess));
                echo Form::hidden(array('name' => 'dmMetaDataId', 'value' => $this->dmMetaDataId));
                echo Form::hidden(array('name' => 'cyphertext', 'value' => $this->cyphertext));
                echo Form::hidden(array('name' => 'plainText', 'value' => $this->plainText));
                echo Form::hidden(array('id' => 'saveAddEventInput'));
                echo Form::hidden(array('name' => 'windowSessionId', 'value' => $this->uniqId));
                
                if (isset($this->realSourceIdAutoMap)) {
                    
                    echo Form::hidden(array('name' => 'realSourceIdAutoMap', 'value' => $this->realSourceIdAutoMap . '_' . $this->dmMetaDataId));

                    if (isset($this->srcAutoMapPattern)) {
                        echo Form::textArea(array('name' => 'srcAutoMapPattern', 'class' => 'd-none', 'value' => $this->srcAutoMapPattern));
                    }
                }
                
                if (isset($this->fillParamData['_taskflowinfo']) && $this->fillParamData['_taskflowinfo']) {
                    echo Form::hidden(array('name' => 'taskFlowInfo', 'value' => Arr::encode($this->fillParamData['_taskflowinfo'])));
                }
                ?>    
            </div>
            
            <?php echo $this->checkListEndHtml; ?>
            
            <div id="responseMethod"></div>      
            <?php echo $processsMainContentClassEnd; ?>     
        </div>
        
        <?php if ($sidebarShow || $sidebarShowRowDtl) { ?>
            <div class="right-sidebar" data-status="closed">
                <div class="stoggler sidebar-right">
                    <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> 
                    <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
                </div>
                <div class="right-sidebar-content">
                    <?php
                    if (isset($sidebarHeaderArr)) {
                        foreach ($sidebarHeaderArr as $key => $row) {
                            echo '<p class="property_page_title">' . $this->lang->line($key) . '</p>' .
                            '<div class="panel panel-default bg-inverse grid-row-content">' .
                            '<table class="table sheetTable sidebar_detail">' .
                            '<tbody>';
                            foreach ($row as $subrow) {
                                echo "<tr>" .
                                "<td style='width: 150px;' class='left-padding'>" . $this->lang->line($subrow['input_label_txt']) . "</td>" .
                                "<td>" . $subrow['input_html'] . "</td>" .
                                "</tr>";
                            }
                            echo '</tbody></table></div>';
                        }
                    }
                    if (isset($sidebarDtlRowArr)) {
                        foreach ($sidebarDtlRowArr as $key => $row) {
                            echo '<p class="property_page_title">' . $this->lang->line($row) . '</p>' .
                            '<div class="panel panel-default bg-inverse grid-row-content">' .
                            '<table class="table sheetTable sidebar_detail">' .
                            '<tbody>';
                            foreach ($sidebarDtlRowContentArr[$key] as $subrow) {
                                echo "<tr>" .
                                "<td style='width: 150px;' class='left-padding'>" . $this->lang->line($subrow['input_label_txt']) . "</td>" .
                                "<td>" . $subrow['input_html'] . "</td>" .
                                "</tr>";
                            }
                            echo '</tbody></table></div>';
                        }
                    }

                    if (isset($sidebarGroupMetaRender))
                        echo $sidebarGroupMetaRender;

                    if (isset($sidebarGroupMetaRowsRender))
                        echo $sidebarGroupMetaRowsRender;
                    ?>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $(".right-sidebar", "div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']").css("min-height", $(".right-sidebar-content", "div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']").height() + "px");
                });
            </script>
            <?php
        }
        ?>        
    </div>

    <?php
        echo $processsDialogContentClassEnd;
        echo $this->bpTab['tabEnd'];
    ?>
    <div class="clearfix w-100"></div>
    <?php
    echo Mdlanguage::translateBtnByMetaId($this->methodId);
    echo Form::close(); 
    ?>          
</div>
<?php require getBasePath() . 'middleware/views/webservice/sub/script/main.php'; ?>

<style type="text/css">
    .bprocess-table-dtl-theme .mt-info .btn.btn-outline.default {
        border-color: #e1e5ec;
        color: #e1e5ec;
        background: 0 0;
        border-radius:0;
    }
    .bprocess-table-dtl-theme .mt-info .btn.btn-outline.default:hover {
        color: #666;
        background-color: #e6e6e6;
        border-color: #e0e0e0; 
        border-radius:0;
    }
    .bprocess-table-dtl-theme.mt-element-overlay .mt-overlay-1 {
        height: 100%;
        width: 100%;
        float: left;
        overflow: hidden;
        position: relative;
        text-align: center;
        cursor: default; 
        background: rgb(207, 216, 220);
        box-shadow: 2px 2px 0px rgba(0,0,0,0.1);
    }
    .bprocess-table-dtl-theme .ui-dialog {
        /*position: relative !important;*/
    }
    .bprocess-table-dtl-theme.mt-element-overlay .mt-overlay-1 .mt-info {
        text-decoration: none;
        display: inline-block;
        text-transform: uppercase;
        color: #fff;
        background-color: transparent;
        opacity: 0;
        filter: alpha(opacity=0);
        -webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
        padding: 0;
        margin: auto;
        position: absolute;
        top: 50%;
        width: 100%;
        left: 0;
        right: 0;
        transform: translateY(-50%) translateZ(0);
        -webkit-transform: translateY(-50%) translateZ(0);
        -ms-transform: translateY(-50%) translateZ(0); 
    }
    .bprocess-table-dtl-theme.mt-element-overlay .mt-overlay-1 .mt-info > li {
        list-style: none;
        display: inline-block;
        margin: 0 3px;
    }
    .bprocess-table-dtl-theme.mt-element-overlay .mt-overlay-1 .mt-info > li:hover {
        -webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
        cursor: pointer;
    }
    .bprocess-table-dtl-theme.mt-element-overlay .mt-overlay-1:hover .mt-overlay {
        opacity: 1;
        filter: alpha(opacity=100);
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0); 
    }
    .bprocess-table-dtl-theme.mt-element-overlay .mt-overlay-1:hover .mt-info {
        opacity: 1;
        filter: alpha(opacity=100);
        -webkit-transition-delay: .2s;
        transition-delay: .2s; 
    }
    .bprocess-table-dtl-theme.mt-element-overlay .mt-overlay-1 .mt-overlay {
        width: 100%;
        height: 100%;
        position: absolute;
        overflow: hidden;
        top: 0;
        z-index:9;
        left: 0;
        opacity: 0;
        background-color: rgba(0, 0, 0, 0.3);
        -webkit-transition: all .4s ease-in-out;
        transition: all .4s ease-in-out; 
    }
    .bprocess-table-dtl-theme .new-theme .col-form-label {
        font-size: 10px;
        margin-top: 5px;
        float: left;
        margin-bottom: 5px;
        text-align: left;
    }
    .bprocess-table-dtl-theme .new-theme .form-group {
        float: left;
        margin-bottom:0px;
    }
    .bprocess-table-dtl-theme .new-theme .title {
        text-transform: uppercase;
        
        max-height: 36px;
        overflow-x: auto;
        overflow: hidden;
        word-break: break-word;
        text-overflow: ellipsis;
    }
    .bprocess-table-dtl-theme .new-theme .title-row {
        margin-bottom: 5px; 
        line-height: 18px;
    }
    .bprocess-table-dtl-theme .new-theme {
        line-height: 10px;
        margin-bottom: 10px;
        padding-left: 15px;
        padding-right: 15px;
    }
    .bprocess-table-dtl-theme .bp-new-dtltheme {
        top:0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        width:25%;
        float: left;
    }
    .col-md-12-none-position {
        width:100%; 
        padding-left:15px; 
        padding-right:15px;
    }
</style>
