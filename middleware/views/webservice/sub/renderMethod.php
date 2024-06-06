<?php
$ws = new Mdwebservice();
$processsMainContentClassBegin = '';
$processsMainContentClassEnd = '';
$processsDialogContentClassBegin = '';
$processsDialogContentClassEnd = '';
$dialogProcessLeftBanner = '';
$mainProcessLeftBanner = '';
$isBanner = false;

if (issetParam($this->isBpOpenParam) == '1') {
    /**
     * Init JS Global variable
     */
    $jsGlobal = '<script type="text/javascript">'; 
    $jsGlobal .= 'var isBpOpenParam'.$this->uniqId.' = "is-bp-open-'.issetParam($this->isBpOpenParam).'";'; 
    $jsGlobal .= '</script>';
    echo $jsGlobal;
}

if ($this->isDialog == false) {
    
    $mainProcessBtnBar = '<div class="meta-toolbar is-bp-open-'.issetParam($this->isBpOpenParam).'">';

    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-sm btn-circle btn-secondary card-subject-btn-border mr10 bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left22"></i>', true
            );
            $mainProcessBtnBar .= ' <div class="main-process-text">';
                $mainProcessBtnBar .= '<span>' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            $mainProcessBtnBar .= '</div>';
        } else {
            if (issetParam($this->isBpOpenParam) != '1') {
                $mainProcessBtnBar .= html_tag('a', array(
                    'href' => 'javascript:;',
                    'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                    'onclick' => 'backFirstContent(this);',
                    'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="icon-arrow-left22"></i>', ($this->dmMetaDataId ? true : false)
                );
            }
            if (Input::postCheck('isBackBtnIgnore') == false) {
                $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            }
        }
    } else {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left22"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            if (issetParam($this->isBpOpenParam) != '1') {
                $mainProcessBtnBar .= html_tag('a', array(
                    'href' => 'javascript:;',
                    'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                    'onclick' => 'backFirstContent(this);',
                    'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="icon-arrow-left22"></i>', true
                );
            }
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    }

    $reportPrint = '';
    if ($this->isPrint && $this->isEditMode) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 bp-btn-print ' . (($this->isEditMode ==
                true) ? '' : 'disabled') . '" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode ==
                true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . ($this->lang->line('printTemplate'.$this->methodId) == 'printTemplate'.$this->methodId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->methodId)) . '</button>';
    }

    $mainProcessBtnBar .= '<div class="ml-auto">';
    
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
                    ), '<i class="icon-checkmark-circle2"></i> ' . $this->runMode,  $this->runMode ? true : false //$this->runMode, (!$this->isEditMode) ? ($this->runMode ? true : false) : false
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
                    ), '<i class="icon-checkmark-circle2"></i> ' . $this->processActionBtn, (isset($this->isIgnoreActionBtn) ? false : true)
            ) . ((Config::getFromCache('IS_TEST_SERVER') && !isset($this->isIgnoreActionBtn)) ? html_tag('button', array(
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
            ) . html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle purple-plum ml5 bp-btn-saveprint',
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ', \'saveprint\');',
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="fa fa-print"></i> ' . Lang::line('saveandprint'), $this->isSavePrint 
            ). $reportPrint .
            '
        </div>
    </div>
    <div class="hide mt10" id="boot-fileinput-error-wrap"></div>';

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
<div class="xs-form is-bp-open-<?php echo issetParam($this->isBpOpenParam); ?> main-action-meta bp-banner-container <?php echo $this->methodRow['SKIN']; ?> <?php echo $this->methodRow['THEME'] ?>" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>" data-isgroup="<?php echo Mdwebservice::$isGroupRender; ?>">
     
    <?php
    echo Form::create(array('id' => 'wsForm', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => ($isBanner ? 'bp-banner-content' : '')));
    $isCallNextFunction = '1';
    
    $this->postSelectedRowData = isset($this->selectedRowData) ? $this->selectedRowData : array();
    
    if (isset($this->selectedRowData) && isset($this->newStatusParams) && $this->newStatusParams) {
        $this->selectedRowsData = $this->selectedRowData;
        
        if (isset($this->selectedRowData[0])) {
            if (is_array($this->selectedRowData[0]))
                $this->selectedRowData = $this->selectedRowData[0];
            else
                $this->selectedRowsData = array($this->selectedRowsData);
        } else {
            if (isset($this->selectedRowsData['pfnextstatuscolumn'])) {
                unset($this->selectedRowsData['pfnextstatuscolumn']);
            }
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
        $rowJson = htmlentities(json_encode($this->postSelectedRowData), ENT_QUOTES, 'UTF-8'); 
        
        foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
            $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['processname']) . '\', \'\', \'changeHardAssign\', \'\', \''. $this->uniqId .'\', \''. $this->methodId .'\', undefined, undefined, \'' . $wfmstatusRow['wfmstatusprocessid'] . '\', \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined, undefined, undefined, \'' . $isCallNextFunction .'\', \'' . $wfmstatusRow['isformnotsubmit'] . '\', \'' . $wfmstatusRow['usedescriptionwindow'] . '\');"';
            $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' data-rowdata="'. $rowJson .'" class="hidden btn btn-sm purple-plum btn-circle hidden-wfm-status-'. $wfmstatusRow['wfmstatusid']  .'" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['processname'] .'</button> ';
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
            <?php 
            echo $mainProcessLeftBanner;
            echo $processsMainContentClassBegin; 

            $isDtlTbl = $sidebarShow = $sidebarShowRowDtl = false;
            
            if ($this->paramList) {
                
                echo $this->checkListStartHtml;
                echo Mduser::processToolsButton($this->methodId, (issetParam($this->methodRow['IS_TOOLS_BTN']) ? false : $this->isEditMode), $this->runMode, $this->bpTab['tabStart']);
                
                $tabNameArr = $tabHeaderArr = $sidebarHeaderArr = $sidebarDtlRowArr = $wizardStepDetail = [];
                $tabHead = '';
                $tabHeaderHead = '';
                $tabContent = '';
                $tabHeaderContent = '';
                $sidebarContent = '';
                $sidebarGroup = '';
                $sidebarGroupMetaRender = '';
                $sidebarGroupMetaRowsRender = '';
                $grouptHtmlWithoutTab = '';
                $groupHtmlWithoutTabFirst = '';
                $tabSecondWidth = 100 - $this->labelWidth;
                $tabActiveFirst = 0;
                
                foreach ($this->paramList as $k => $row) {
                    
                    if ($row['type'] == 'header') {
                        
                        if (isset($row['data'])) {
                            $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                            
                            if (count($buildData['featureParam']) > 0) {
                                echo $ws->renderFeatureParam($this->methodId, $buildData['featureParam'], $this->fillParamData, $this->isDialog);
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

                                            echo '<tr' . ($this->columnCount == 1 ? " data-cell-path='" . $rows[$ww][0]['META_DATA_CODE'] . "'" : '') . '>';
                                            $xx = count($columns);
                                            $xxx = 0;
                                            $hrClass = $colspan = '';

                                            while ($xxx < $xx) {

                                                $sidebarname = trim($columns[$xxx]['SIDEBAR_NAME']);
                                                
                                                if (!empty($sidebarname)) {
                                                    $sidebarShow = true;
                                                    $hdrSidebar = Mdwebservice::renderBpHdrSidebar($this->methodId, $columns[$xxx], $this->fillParamData);
                                                    $sidebarHeaderArr[$sidebarname][$columns[$xxx]['META_DATA_CODE']] = $hdrSidebar;
                                                    unset($buildData['onlyShow'][$resetArrIndex++]);
                                                    $xxx++;
                                                    continue;
                                                }
                                                
                                                $gridHeaderClass .= Mdwebservice::fieldHeaderStyleClass($columns[$xxx], 'bp-window-' . $this->methodId);

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

                                                    if ($this->columnCount == 2 && $xxx % 2 == 0) {
                                                        $colspan = 3;
                                                    } elseif ($this->columnCount > 2 && $xxx == 0) {
                                                        $colspan = ($this->columnCount - ($xxx + 1)) * 2 + 1;
                                                    }   
                                                }
                                                ?>
                                            <td class="text-right middle" data-cell-path="<?php echo $columns[$xxx]['META_DATA_CODE']; ?>" style="width: <?php echo $this->labelWidth; ?>%">
                                                <?php
                                                $labelText = $this->lang->line($columns[$xxx]['META_DATA_NAME']);
                                                
                                                $labelAttr = array(
                                                    'for' => 'param[' . $columns[$xxx]['META_DATA_CODE'] . ']',
                                                    'data-label-path' => $columns[$xxx]['META_DATA_CODE']
                                                );
                                                
                                                if (isset($columns[$xxx]['JSON_CONFIG']['tooltip'])) {
                                                    $labelAttr['no_colon'] = 1;
                                                    $labelText .= '<span class="label-colon">:</span> <i class="fas fa-info-circle text-grey-700" data-qtip-title="'.$this->lang->line($columns[$xxx]['JSON_CONFIG']['tooltip']).'" data-qtip-pos="top"></i>';
                                                }
                                                
                                                $labelAttr['text'] = $labelText;
                                                
                                                if ($columns[$xxx]['IS_REQUIRED'] == '1') {
                                                    $labelAttr['required'] = 'required';
                                                }
                                                echo Form::label($labelAttr);
                                                ?>
                                            </td>
                                            <td class="middle" data-cell-path="<?php echo $columns[$xxx]['META_DATA_CODE']; ?>" style="width: <?php echo $columnDividePercent ?>%" colspan="<?php echo $colspan; ?>">
                                                <div data-section-path="<?php echo $columns[$xxx]['PARAM_REAL_PATH']; ?>">
                                                    <?php
                                                    echo Mdwebservice::renderParamControl($this->methodId, $columns[$xxx], 'param[' . $columns[$xxx]['META_DATA_CODE'] . ']', $columns[$xxx]['META_DATA_CODE'], $this->fillParamData);
                                                    ?>
                                                </div>
                                            </td>
                                        <?php
                                            unset($buildData['onlyShow'][$resetArrIndex++]);
                                            if ($_seperator) {
                                                $hrClass = $columns[$xxx]['SEPARATOR_TYPE'];
                                                $xxx = $xx;
                                            } else {
                                                $xxx++;
                                            }
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
                        
                    } elseif ($row['type'] == 'detail' && $row['isShow'] == '1' && isset($row['data'])) {
                        
                        // start default detail
                        require BASEPATH . 'middleware/views/webservice/sub/detail/default.php'; 
                        // end default detail
                        
                        $wizardStep = issetParam($row['wizardStep']);
                        
                        if ($wizardStep) {

                            $wizardStepDetail[] = [
                                'wizardStep' => $wizardStep, 
                                'tabName' => $row['tabName'], 
                                'code' => $row['code'], 
                                'name' => $row['name'], 
                                'columnWidth' => $row['columnWidth'], 
                                'isRefresh' => $row['isRefresh'], 
                                'content' => $content
                            ];
                            
                        } else {

                            if ($row['tabName'] != '') {

                                if (!isset($tabNameArr[$row['tabName']])) {
                                    $tabHeaderContent = '';

                                    if (!empty($tabHeaderArr)) {
                                        foreach ($tabHeaderArr as $tabKey => $tabVal) {

                                            if (Str::lower($row['tabName']) === Str::lower($tabVal)) {

                                                $tabUniqId = getUID();
                                                $tabHeaderContent .= '<div class="table-scrollable table-scrollable-borderless bp-header-param">';

                                                if (isset($this->methodRow['TAB_COLUMN_COUNT']) && $this->methodRow['TAB_COLUMN_COUNT'] > 1) {

                                                    $tabHeaderContent .= Mdwebservice::getTabSplitColumnContent($this->methodId, $this->methodRow['TAB_COLUMN_COUNT'], $tabHeaderContentArr[$tabKey], $seperatorWidth, $this->labelWidth, $this->fillParamData);

                                                } else {

                                                    $tabHeaderContent .= '<table class="table table-sm table-no-bordered bp-header-param"><tbody>';
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
                                                    $tabHeaderContent .= '</tbody></table>';
                                                }

                                                $tabHeaderContent .= '</div>';

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

                                if ($row['dtlTheme'] == '16') {

                                    $grouptHtmlWithoutTab .= '
                                        <div class="tabbable-line tabbable-tabdrop bp-tabs '.($row['dtlTheme'] == '15' ? 'w-100 ntrGridView' : '').' " '.($row['columnWidth'] ? 'style="width:'.$row['columnWidth'].'"' : '').' data-section-path="'.$row['code'].'">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a href="#tab_' . $this->methodId . '_' . $row['id'] . '" class="nav-link active" data-toggle="tab">' . $this->lang->line($row['name']) . '</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_' . $this->methodId . '_' . $row['id'] . '">' . $content . '<!--' . $row['tabName'] . '--></div>
                                        </div>
                                    </div>';

                                } elseif ($row['dtlTheme'] == '15') {
                                    $groupHtmlWithoutTabFirst .= '<hr><div data-section-path="' . $row['code'] . '" '.($row['columnWidth'] ? 'class="float-left" style="width:'.$row['columnWidth'].'"' : '').' data-isclear="' . $row['isRefresh'] . '">
                                        '. $content .'
                                    </div><hr>';
                                } else {
                                    if (issetParam($row['widgetCode']) === 'detail_calendar_sidebar') {
                                        $grouptHtmlWithoutTab .= '<div data-section-path="' . $row['code'] . '" '.($row['columnWidth'] ? 'class="float-left" style="width:'.$row['columnWidth'].'"' : '').' data-isclear="' . $row['isRefresh'] . '">
                                            ' . $content . ' 
                                        </div>';
                                    } else {
                                        $grouptHtmlWithoutTab .= '<div data-section-path="' . $row['code'] . '" '.($row['columnWidth'] ? 'class="float-left" style="width:'.$row['columnWidth'].'"' : '').' data-isclear="' . $row['isRefresh'] . '">
                                            <div class="bp-detail-title">' . $this->lang->line($row['name']) . '</div>
                                            <div class="bp-detail-body">' . $content . '</div> 
                                        </div>';
                                    }
                                }
                            }

                        }
                    }
                }
                
                echo $groupHtmlWithoutTabFirst;
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
                            
                            $tabHeaderContent .= '<div class="table-scrollable table-scrollable-borderless bp-header-param">';
                            
                            if (!empty($tabHeaderContentData) && isset($tabHeaderContentData['data'])) {
                                
                                if (isset($this->methodRow['TAB_COLUMN_COUNT']) && $this->methodRow['TAB_COLUMN_COUNT'] > 1) {
                                    
                                    $tabHeaderContent .= Mdwebservice::getTabSplitColumnContent($this->methodId, $this->methodRow['TAB_COLUMN_COUNT'], $tabHeaderContentData['data'], $seperatorWidth, $this->labelWidth, $this->fillParamData);
                                    
                                } else {
                                    
                                    $tabContentGraph = '';
                                    $tabContentWithoutGraph = '';
                                    
                                    foreach ($tabHeaderContentData['data'] as $subrow) {
                                        
                                        if ($subrow['META_TYPE_CODE'] == 'graph') {
                                            
                                            $tabContentGraph .= Mdwebservice::graphFieldRender($this->methodId, $subrow, $this->fillParamData);
                                            
                                        } elseif ($subrow['META_TYPE_CODE'] == 'file' && issetParam($subrow['RENDER_TYPE']) == 'preview') {
                                            
                                            $tabContentGraph = '<div data-cell-path="'.$subrow['PARAM_REAL_PATH'].'">';
                                                $tabContentGraph .= Mdwebservice::renderParamControl($this->methodId, $subrow, 'param[' . $subrow['META_DATA_CODE'] . ']', $subrow['META_DATA_CODE'], $this->fillParamData);
                                            $tabContentGraph .= '</div>';
                                            
                                        } else {
                                            $tabContentWithoutGraph .= Mdwebservice::getTabHeaderContent($this->methodId, $subrow, $tabSecondWidth, $seperatorWidth, $this->labelWidth, $this->fillParamData);
                                        }
                                    }
                                    
                                    if ($tabContentWithoutGraph) {
                                        $tabHeaderContent .= '<table class="table table-sm table-no-bordered bp-header-param"><tbody>';
                                        $tabHeaderContent .= $tabContentWithoutGraph;
                                        $tabHeaderContent .= '</tbody></table>';
                                    }
                                    
                                    $tabHeaderContent .= $tabContentGraph;
                                }
                            }
                            
                            $tabHeaderContent .= '</div>';
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
                
                echo $grouptHtmlWithoutTab;
            }
            
            $wizardStepHeaderParams = issetParamArray($buildData['wizardStepParam']);
            
            if ($wizardStepHeaderParams || $wizardStepDetail) {
                $isWizard = true;
                $wizardSteps = [];
                $gridHeaderClass = '';
                
                foreach ($wizardStepHeaderParams as $wizardStepHeaderParam) {
                    $wizardSteps[$wizardStepHeaderParam['WIZARD_STEP']] = ['step' => $wizardStepHeaderParam['WIZARD_STEP']];
                }
                
                foreach ($wizardStepDetail as $wizardStepDetailRow) {
                    $wizardSteps[$wizardStepDetailRow['wizardStep']] = ['step' => $wizardStepDetailRow['wizardStep']];
                }
                
                $wizardSteps = Arr::sortBy('step', $wizardSteps, 'asc');
                $wizardStepHeaderParams = Arr::groupByArray($wizardStepHeaderParams, 'WIZARD_STEP');
                
                if ($wizardStepDetail) {
                    $wizardStepDetail = Arr::groupByArray($wizardStepDetail, 'wizardStep');
                }
            ?>
            
            <div id="wizard-<?php echo $this->uniqId; ?>">
                <?php
                foreach ($wizardSteps as $wizardStep) {
                ?>
                <h3><?php echo $wizardStep['step']; ?></h3>
                <section>
                    <?php
                    if (isset($wizardStepHeaderParams[$wizardStep['step']]['rows'])) {
                        $headerShowParams = $wizardStepHeaderParams[$wizardStep['step']]['rows'];
                    ?>
                    <div class="table-scrollable table-scrollable-borderless bp-header-param">
                        <table class="table table-sm table-no-bordered bp-header-param">
                            <tbody>
                                <?php
                                $resetArrIndex = 0;
                                $ww = 0;
                                $_seperator = false;
                                $rows = array_chunk($headerShowParams, $this->columnCount);
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

                                    echo '<tr' . ($this->columnCount == 1 ? " data-cell-path='" . $rows[$ww][0]['META_DATA_CODE'] . "'" : '') . '>';
                                    $xx = count($columns);
                                    $xxx = 0;
                                    $hrClass = $colspan = '';

                                    while ($xxx < $xx) {

                                        $gridHeaderClass .= Mdwebservice::fieldHeaderStyleClass($columns[$xxx], 'bp-window-' . $this->methodId);

                                        if (!empty($columns[$xxx]['SEPARATOR_TYPE'])) {
                                            $_seperator = true;

                                            if ($this->columnCount == 2 && $xxx % 2 == 0) {
                                                $colspan = 3;
                                            } elseif ($this->columnCount > 2 && $xxx == 0) {
                                                $colspan = ($this->columnCount - ($xxx + 1)) * 2 + 1;
                                            }   
                                        }
                                        ?>
                                    <td class="text-right middle" data-cell-path="<?php echo $columns[$xxx]['META_DATA_CODE']; ?>" style="width: <?php echo $this->labelWidth; ?>%">
                                        <?php
                                        $labelText = $this->lang->line($columns[$xxx]['META_DATA_NAME']);

                                        $labelAttr = array(
                                            'for' => 'param[' . $columns[$xxx]['META_DATA_CODE'] . ']',
                                            'data-label-path' => $columns[$xxx]['META_DATA_CODE']
                                        );

                                        if (isset($columns[$xxx]['JSON_CONFIG']['tooltip'])) {
                                            $labelAttr['no_colon'] = 1;
                                            $labelText .= '<span class="label-colon">:</span> <i class="fas fa-info-circle text-grey-700" data-qtip-title="'.$this->lang->line($columns[$xxx]['JSON_CONFIG']['tooltip']).'" data-qtip-pos="top"></i>';
                                        }

                                        $labelAttr['text'] = $labelText;

                                        if ($columns[$xxx]['IS_REQUIRED'] == '1') {
                                            $labelAttr['required'] = 'required';
                                        }
                                        echo Form::label($labelAttr);
                                        ?>
                                    </td>
                                    <td class="middle" data-cell-path="<?php echo $columns[$xxx]['META_DATA_CODE']; ?>" style="width: <?php echo $columnDividePercent ?>%" colspan="<?php echo $colspan; ?>">
                                        <div data-section-path="<?php echo $columns[$xxx]['PARAM_REAL_PATH']; ?>">
                                            <?php
                                            echo Mdwebservice::renderParamControl($this->methodId, $columns[$xxx], 'param[' . $columns[$xxx]['META_DATA_CODE'] . ']', $columns[$xxx]['META_DATA_CODE'], $this->fillParamData);
                                            ?>
                                        </div>
                                    </td>
                                <?php
                                    unset($headerShowParams[$resetArrIndex++]);
                                    if ($_seperator) {
                                        $hrClass = $columns[$xxx]['SEPARATOR_TYPE'];
                                        $xxx = $xx;
                                    } else {
                                        $xxx++;
                                    }
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
                                    $rows = array_chunk($headerShowParams, $this->columnCount);
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
                    </div>
                    <?php
                    }
                    
                    if (isset($wizardStepDetail[$wizardStep['step']]['rows'])) {
                        $detailShowParams = $wizardStepDetail[$wizardStep['step']]['rows'];
                        $detailShowTabs = [];
                        $detailShowWidgetTabs = [];
                                
                        foreach ($detailShowParams as $detailShowParam) {
                            if ($detailShowParam['tabName']) {
                                
                                $detailShowTabs[Str::lower($detailShowParam['tabName'])][] = [
                                    'tabName' => $detailShowParam['tabName'], 
                                    'code' => $detailShowParam['code'], 
                                    'name' => $detailShowParam['name'], 
                                    'columnWidth' => $detailShowParam['columnWidth'], 
                                    'isRefresh' => $detailShowParam['isRefresh'], 
                                    'content' => $detailShowParam['content']
                                ];
                                
                            } elseif (Mdwidget::bpDetailAvailableWidgets($detailShowParam['code'])) {
                                
                                $detailShowWidgetTabs[Str::lower($detailShowParam['name'])][] = [
                                    'tabName' => $detailShowParam['name'], 
                                    'code' => $detailShowParam['code'], 
                                    'name' => $detailShowParam['name'], 
                                    'columnWidth' => $detailShowParam['columnWidth'], 
                                    'isRefresh' => $detailShowParam['isRefresh'], 
                                    'content' => $detailShowParam['content']
                                ];
                                
                            } else {
                                
                                echo '<div data-section-path="' . $detailShowParam['code'] . '" '.($detailShowParam['columnWidth'] ? 'class="float-left" style="width:'.$detailShowParam['columnWidth'].'"' : '').' data-isclear="' . $detailShowParam['isRefresh'] . '">
                                            <div class="bp-detail-title">' . $this->lang->line($detailShowParam['name']) . '</div>
                                            <div class="bp-detail-body">' . $detailShowParam['content'] . '</div> 
                                        </div>';
                            }
                        }
                        
                        if ($detailShowTabs) {

                            $tabIndex = 0;
                            $tabItem = $tabContent = '';
                            
                            foreach ($detailShowTabs as $detailShowTab) {
                                $detailShowTabRow = $detailShowTab[0]; 
                                
                                $tabItem .= '<li class="nav-item">
                                    <a href="#tab_' . $this->methodId . '_' . $tabIndex . '_wizardstep" class="nav-link'.($tabIndex == 0 ? ' active' : '').'" data-toggle="tab">' . $this->lang->line($detailShowTabRow['tabName']) . '</a>
                                </li>';
                                
                                $tabContent .= '<div class="tab-pane'.($tabIndex == 0 ? ' active' : '').'" id="tab_' . $this->methodId . '_' . $tabIndex . '_wizardstep">';
                                foreach ($detailShowTab as $detailShowTabContent) {
                                    $tabContent .= $detailShowTabContent['content'];
                                }
                                $tabContent .= '</div>';
                                
                                $tabIndex ++;
                            }
                            
                            echo '<div class="tabbable-line tabbable-tabdrop bp-tabs">
                                <ul class="nav nav-tabs">
                                    '.$tabItem.'
                                </ul>
                                <div class="tab-content">
                                    '.$tabContent.'
                                </div>
                            </div>';
                        }

                        if ($detailShowWidgetTabs) {
                            foreach ($detailShowWidgetTabs as $detailShowTab) {
                                $detailShowParam = $detailShowTab[0]; 
                                
                                echo '<div data-section-path="' . $detailShowParam['code'] . '" '.($detailShowParam['columnWidth'] ? 'class="float-left" style="width:'.$detailShowParam['columnWidth'].'"' : '').' data-isclear="' . $detailShowParam['isRefresh'] . '">
                                            <div class="bp-detail-title">' . $this->lang->line($detailShowParam['name']) . '</div>
                                            <div class="bp-detail-body">' . $detailShowParam['content'] . '</div> 
                                        </div>';
                            }
                        }
                    }
                    ?>
                </section>
                <?php
                }
                ?>
            </div>
            <?php
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
                echo Form::hidden(array('name' => 'cacheId', 'value' => $this->cacheId));
                echo Form::hidden(array('name' => 'windowSessionId', 'value' => $this->uniqId));
                
                if (isset($this->realSourceIdAutoMap)) {
                    
                    echo Form::hidden(array('name' => 'realSourceIdAutoMap', 'value' => $this->realSourceIdAutoMap . '_' . $this->dmMetaDataId));
                    
                    if (isset($this->srcAutoMapPattern)) {
                        echo Form::textArea(array('name' => 'srcAutoMapPattern', 'class' => 'd-none', 'value' => $this->srcAutoMapPattern));
                    }
                }
                
                if (isset($this->fillParamData['_taskflowinfo']) && $this->fillParamData['_taskflowinfo']) {
                    echo Form::hidden(array('name' => 'taskFlowInfo', 'value' => Arr::encode($this->fillParamData['_taskflowinfo'])));
                    echo Form::hidden(array('name' => 'isTaskFlowView', 'value' => issetParam($_POST['isTaskFlowView'])));
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
                                echo "<tr data-cell-path=\"".$subrow['data_path']."\">" .
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

                    if (isset($sidebarGroupMetaRender)) {
                        echo $sidebarGroupMetaRender;
                    }

                    if (isset($sidebarGroupMetaRowsRender)) {
                        echo $sidebarGroupMetaRowsRender;
                    }
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
    echo Mdlanguage::translateBtnByMetaId($this->methodId);
    echo Form::close(); 
    ?>        
</div>
<?php require getBasePath() . 'middleware/views/webservice/sub/script/main.php'; ?>
