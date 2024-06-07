<?php
$ws = new Mdwebservice();
$processsMainContentClassBegin = '';
$processsMainContentClassEnd = '';
$processsDialogContentClassBegin = '';
$processsDialogContentClassEnd = '';
$dialogProcessLeftBanner = '';
$mainProcessLeftBanner = '';

if ($this->isDialog == false) {
    $mainProcessBtnBar = '<div class="meta-toolbar">';
    
    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-sm btn-circle btn-secondary card-subject-btn-border mr10 bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left22"></i>', true
            );
            $mainProcessBtnBar .= ' <div class="main-process-text">';
                $mainProcessBtnBar .= ' <span class="bp-text">' . $this->lang->line('business_process') . ' - </span>';
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
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', true
            );
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
    
    $mainProcessBtnBar .= Mdcommon::helpContentButton([
                'contentId' => $this->helpContentId, 
                'sourceId' => $this->methodId, 
                'fromType' => 'meta_process'
            ]) . Form::button(
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
    }
} else {
    $mainProcessBtnBar = '';

    $dialogProcessLeftBanner = $ws->showBanner($this->methodId, 'left', $this->isBanner);
    $mainProcessLeftBanner = '';
    if ($dialogProcessLeftBanner != '') {
        $processsDialogContentClassBegin = '<div class="processs-main-content">';
        $processsDialogContentClassEnd = '</div>';
    }
}
?>
<div class="xs-form bp-banner-container bp-template-mode main-action-meta<?php echo (isset($this->bpTemplateRow) ? ' '.issetParam($this->bpTemplateRow['controlDesign']) : ''); ?>" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
    <?php 
    echo Form::create(array('id' => 'wsForm', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => ($this->isBanner ? 'bp-banner-content' : '')));
    
    $isCallNextFunction = '1';
    
    if (isset($this->selectedRowData) && isset($this->newStatusParams) && $this->newStatusParams) {
        $this->selectedRowsData = $this->selectedRowData;
                
        if (isset($this->selectedRowData[0])) {
            if (is_array($this->selectedRowData[0])) {
                $this->selectedRowData = $this->selectedRowData[0];
            } else {
                $this->selectedRowsData = array($this->selectedRowsData);
            }
        } else {
            $this->selectedRowsData = array($this->selectedRowsData);
        }
        $arrayToStrParam = Arr::encode($this->selectedRowsData);
        
        if (isset($arrayToStrParam) && isset($this->newStatusParams) && $this->newStatusParams && $arrayToStrParam) {
            $isCallNextFunction = '0';
        }
    }
    
    if (isset($this->wfmStatusParams['result']) && isset($this->selectedRowData) && isset($this->hasMainProcess) && $this->hasMainProcess) {
        
        $singleMenuHtml = '';
        
        if (isset($this->wfmStatusBtns) && $this->wfmStatusBtns && isset($this->wfmStatusBtns['result']) && $this->wfmStatusBtns['result']) {
            $singleMenuHtml .= '<span class="workflowBtn-'. $this->methodId .' bp-wfmstatus-btns"></span>';
            foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
                $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['wfmstatusname']) . '\', \'\', \'changeHardAssign\',  \'\', \''. $this->uniqId .'\', \''. $this->methodId .'\', undefined , undefined , \'' . $wfmstatusRow['wfmstatusprocessid'] . '\' , \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined , undefined , undefined , \'' . $isCallNextFunction .'\', \'' . $wfmstatusRow['isformnotsubmit'] . '\', \'' . $wfmstatusRow['usedescriptionwindow'] . '\');"';
                $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' class="hidden btn btn-sm purple-plum btn-circle hidden-wfm-status-'. $wfmstatusRow['wfmstatusid']  .'" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['wfmstatusname'] .'</button> ';
            } 
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

        $isDtlTbl = false;
        $sidebarShow = false;
        $sidebarShowRowDtl = false;
        $notUseControls = '';
        
        if ($this->paramList) {
            
            echo $this->templateDropDownList;
            
            echo '<div class="bp-template-wrap">';
            echo '<div class="bp-template-table">';
            echo '<div class="bp-template-table-row">';
            echo '<div class="bp-template-table-cell-left">';
            
            $tabNameArr = array();
            $tabHeaderArr = array();            
            $sidebarHeaderArr = array();
            $sidebarDtlRowArr = array();            
            $getDtlRowsPopup = array();
            $constantKeys = Mdstatement::constantKeys();
            $tabHead = '';
            $tabHeaderHead = '';
            $tabContent = '';
            $tabHeaderContent = '';            
            $tabActiveFirst = 0;
            $tabSecondWidth = 100 - $this->labelWidth;
            $subProcessButtons = '';
            $sidebarDvs = '';
            $sidebarDvsLeft = '';
            
            $htmlContent = $this->htmlContent;
            $sidebarGroupMetaRowsRender = $sidebarGroup = $sidebarGroupMetaRender = '';
            
            foreach ($constantKeys as $constantKey => $constantKeyValue) {
                $htmlContent = str_ireplace($constantKey, $constantKeyValue, $htmlContent);
            }
            
            $htmlContent = Mdstatement::assetsReplacer($htmlContent);
            $htmlContent = Mdstatement::configValueReplacer($htmlContent, null);
            
            $arg = array();
            
            foreach ($this->paramList as $k => $row) {
                
                if ($row['type'] == 'header' && isset($row['data'])) {

                    if (strpos($htmlContent, '{{tabsaddons}}') === false) {

                        $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                        $headerParams = $buildData['onlyShow'];
                        $gridHeaderClass = ''; 
    
                        foreach ($headerParams as $headerParam) {
                            
                            $gridHeaderClass .= Mdwebservice::fieldHeaderStyleClassByTemplate($headerParam, 'bp-window-' . $this->methodId);
                            
                            $control = '<div class="bp-template-control-div" data-section-path="'.$headerParam['PARAM_REAL_PATH'].'">';
                            $control .= Mdwebservice::renderParamControl($this->methodId, $headerParam, 'param[' . $headerParam['META_DATA_CODE'] . ']', $headerParam['META_DATA_CODE'], $this->fillParamData);
                            $control .= '</div>';
                            
                            $htmlContent = str_ireplace('#'.$headerParam['META_DATA_CODE'].'#', $control, $htmlContent, $replacedCount);
    
                            if ($replacedCount == 0) {
                                $notUseControls .= $control;
                            }
                        }                        

                    } else {

                        $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                        
                        $gridHeaderClass = '';

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

                            $xx = count($columns);
                            $xxx = 0;
                            $hrClass = $colspan = '';

                            while ($xxx < $xx) {
                                $gridHeaderClass .= Mdwebservice::fieldHeaderStyleClass($columns[$xxx], 'bp-window-' . $this->methodId);

                                $sidebarname = trim($columns[$xxx]['SIDEBAR_NAME']);
                                if (!empty($sidebarname)) {
                                    if (!empty($columns[$xxx]['LOOKUP_META_DATA_ID'])) {
                                        if (strtolower($sidebarname) === 'right') {
                                            $sidebarDvs .= '<div class="process-template-sidebar-dataview row" data-position="'.strtolower($sidebarname).'" data-lookup-meta="'.$columns[$xxx]['LOOKUP_META_DATA_ID'].'"></div>';
                                        } else {
                                            $sidebarDvsLeft .= '<div class="process-template-sidebar-dataview row" data-position="'.strtolower($sidebarname).'" data-lookup-meta="'.$columns[$xxx]['LOOKUP_META_DATA_ID'].'"></div>';
                                        }
                                    }
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

                                    if ($this->columnCount == 2 && $xxx % 2 == 0) {
                                        $colspan = 3;
                                    }    
                                }

                                $labelAttr = array(
                                    'text' => $this->lang->line($columns[$xxx]['META_DATA_NAME']),
                                    'for' => 'param[' . $columns[$xxx]['META_DATA_CODE'] . ']',
                                    'data-label-path' => $columns[$xxx]['META_DATA_CODE']
                                );
                                if ($columns[$xxx]['IS_REQUIRED'] == '1') {
                                    $labelAttr['required'] = 'required';
                                }
                            
                            $control = Mdwebservice::renderParamControl($this->methodId, $columns[$xxx], 'param[' . $columns[$xxx]['META_DATA_CODE'] . ']', $columns[$xxx]['META_DATA_CODE'], $this->fillParamData);

                            $htmlContent = str_ireplace('#'.$columns[$xxx]['META_DATA_CODE'].'#', $control, $htmlContent, $replacedCount);

                            if ($replacedCount == 0) {
                                $notUseControls .= $control;
                            }

                            unset($buildData['onlyShow'][$resetArrIndex++]);
                            if ($_seperator) {
                                $hrClass = $columns[$xxx]['SEPARATOR_TYPE'];
                                $xxx = $xx;
                            } else
                                $xxx++;
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
                }
                echo $buildData['hiddenParam']; 
                ?>
                    <style type="text/css">.bp-window-<?php echo $this->methodId;?> table.bp-header-param{table-layout: fixed;} <?php echo $gridHeaderClass; ?></style>
                <?php 
                    echo $buildData['hiddenParam'];                     
                    
                } elseif ($row['type'] == 'detail' && $row['isShow'] == '1' && isset($row['data'])) {
                    
                    // start default detail
                        
                    require BASEPATH . 'middleware/views/webservice/sub/detail/default.php'; 

                    // end default detail
                    
                    if ($row['recordtype'] == 'row') {
                        foreach ($row['data'] as $ind => $val) {
                            if (empty($val['SIDEBAR_NAME'])) {
                                            
                                $control = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $row['code'].'.'.$val['META_DATA_CODE'] . '][0][]', $val['META_DATA_CODE'], isset($this->fillParamData[strtolower($row['code'])]) ? $this->fillParamData[strtolower($row['code'])] : null);

                                $htmlContent = str_ireplace('#'.$row['code'].'.'.$val['META_DATA_CODE'].'#', $control, $htmlContent);
                            }
                        }
                    }
                        
                    $htmlContent = str_ireplace('#'.$row['code'].'#', $content, $htmlContent, $replacedCount);

                    if ($replacedCount == 0) {
                        $notUseControls .= $content;
                    }
                    
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
                    } 
                }
            }
            
            if (strpos($htmlContent, '{{tabsaddons}}') !== false) {
                
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
                                    $tabHeaderContent .= '<table class="table table-sm table-no-bordered bp-header-param"><tbody>';
                                    foreach ($tabHeaderContentData['data'] as $subrow) {
                                        if ($subrow['META_TYPE_CODE'] === 'button') {
                                            $subProcessButtons .= '<button data-path="'.$subrow['PARAM_REAL_PATH'].'" class="btn btn-warning btn-circle btn-sm" onclick="return false;"><i class="fa '.$subrow['ICON_NAME'].'"></i> '.Lang::line($subrow['META_DATA_NAME']).'</button>';
                                            continue;
                                        }
                                        $tabHeaderContent .= Mdwebservice::getTabHeaderContent($this->methodId, $subrow, $tabSecondWidth, $seperatorWidth, $this->labelWidth, $this->fillParamData);
                                    }
                                    $tabHeaderContent .= '</tbody></table>';
                                }
                            }

                            $tabHeaderContent .= '</div>';
                            $tabContent .= '<div class="tab-pane' . $tabActive . '" id="tab_' . $this->methodId . '_' . $tabUniqId . '">' . $tabHeaderContent . '</div>';
                            ++$tabHeaderActiveFirst;
                        }
                    }
                    
                    $htmlContentTab = '<div class="clearfix w-100"></div>
                            <div class="tabbable-line tabbable-tabdrop bp-tabs">
                            <ul class="nav nav-tabs">' . $tabHead . $this->bpTab['tabStartNoBase'] . '</ul>
                            <div class="tab-content">
                            ' . $tabContent . $this->bpTab['tabEndNoBase'] . '
                            </div>
                        </div>';         

                    $htmlContent = str_replace('{{tabsaddons}}', $htmlContentTab, $htmlContent);
                    $htmlContent = str_replace('{{subprocess}}', $subProcessButtons, $htmlContent);
                    $htmlContent = str_replace('{{sidebarrightdv}}', $sidebarDvs, $htmlContent);
                    $htmlContent = str_replace('{{sidebarleftdv}}', $sidebarDvsLeft, $htmlContent);
                }
            }
        
            echo $htmlContent;
        }
        ?>
        <div class="d-none">
            <?php echo $notUseControls; ?>        
        </div>    
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
                
        <?php require getBasePath().'middleware/views/webservice/sub/script/main.php'; ?>   
        
        </div>
        <?php echo $this->widgets; ?>
        </div>        
        </div>
        </div><!-- template wrap -->        
        
        <div id="responseMethod"></div>      
        <?php echo $processsMainContentClassEnd; ?>     
    </div>
       
    </div>
    <?php
    echo $processsDialogContentClassEnd;
    echo $this->bpTab['tabEnd'];
    ?>
    <div class="clearfix w-100"></div>
    
    <?php echo Form::close(); ?>        
</div>

<script type="text/javascript">
$(function () {

    if (bp_window_<?php echo $this->methodId; ?>.find(".process-template-sidebar-dataview").length) {
        bp_window_<?php echo $this->methodId; ?>.find(".process-template-sidebar-dataview").each(function(){
            var $this = $(this);
            var dvid = $this.data("lookup-meta");

            $.ajax({
                type: 'post',
                data: {isDynamicHeight: 0, isNeedTitle: 1},
                url: 'mdobject/dataview/' + dvid + '/1',
                success: function (data) {
                    $this.empty().append('<div class="" style="background-color: #fff;">' + data + '</div>');
                    setTimeout(function () {
                        $this.find(".right-sidebar-content-for-resize").css('margin-left', '0px');
                    }, 100);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initAjax($this);
            });                
        });
    }
});
</script>
