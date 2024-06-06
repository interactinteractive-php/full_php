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
                'onclick' => 'backFirstContent(this);'
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
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10',
                'onclick' => 'backFirstContent(this);'
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    }
    
    $reportPrint = '';
    if ($this->isPrint) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 '.(($this->isEditMode == true) ? '' : 'disabled').'" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> Хэвлэх</button>';
    }
    $mainProcessBtnBar .= '<div class="float-right">
            ' . Form::button(
                array(
                    'class' => 'btn btn-sm btn-circle purple-plum ml5',
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
<div class="xs-form bp-banner-container bp-template-mode" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
    <?php 
    echo Form::create(array('id' => 'wsForm', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => ($this->isBanner ? 'bp-banner-content' : ''))); 
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
            
            if (isset($this->wfmStatusParams['result']) && isset($this->selectedRowData) && isset($this->hasMainProcess) && $this->hasMainProcess) {
                $singleMenuClick = '';
                $singleMenuHtml = '';
                if (isset($this->wfmStatusBtns) && $this->wfmStatusBtns && isset($this->wfmStatusBtns['result']) && $this->wfmStatusBtns['result']) {
                    $singleMenuHtml .= '<span  class="workflowBtn-'. $this->methodId .' bp-wfmstatus-btns"></span>';
                    foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
                        $wfmMenuClick = 'onclick="changeWfmStatusId(this,  \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\',  \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['wfmstatusname']) . '\', \'\', \'changeHardAssign\',   \'\', undefined , \''. $this->methodId .'\',  undefined ,  undefined, \'' . $wfmstatusRow['wfmstatusprocessid'] . '\' , \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined, \'1\', undefined);"';
                        $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . '  data-dm-id="'. $this->dmMetaDataId .'" class="btn btn-sm purple-plum btn-circle hidden-wfm-status-'. $wfmstatusRow['wfmstatusid']  .'" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['wfmstatusname'] .'</button> ';
                    } 
                } 

                echo $singleMenuHtml; 
                echo '<hr class="bp-top-hr"/>';
            }

            $sidebarHeaderArr = array();
            $sidebarDtlRowArr = array();            
            $getDtlRowsPopup = array();
            $constantKeys = Mdstatement::constantKeys();
            
            $htmlContent = $this->htmlContent;
            $DOMContent = phpQuery::newDocumentHTML($htmlContent);
            $sidebarGroupMetaRowsRender = '';
            $sidebarGroup = '';
            $sidebarGroupMetaRender = '';
            
            foreach ($constantKeys as $constantKey => $constantKeyValue) {
                $htmlContent = str_ireplace($constantKey, $constantKeyValue, $htmlContent);
            }
            
            $htmlContent = Mdstatement::assetsReplacer($htmlContent);
            $htmlContent = Mdstatement::configValueReplacer($htmlContent, null);
            
            foreach ($this->paramList as $k => $row) {
                
                if ($row['type'] == 'header' && isset($row['data'])) {
                        
                    $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                    $gridHeaderClass = '';

                    $headerParams = $buildData['onlyShow'];

                    foreach ($headerParams as $headerParam) {
                        $gridHeaderClass .= Mdwebservice::fieldHeaderStyleClass($headerParam, 'bp-window-' . $this->methodId);
                        $control = Mdwebservice::renderViewParamControl($this->methodId, $headerParam, 'param[' . $headerParam['META_DATA_CODE'] . ']', $headerParam['META_DATA_CODE'], $this->fillParamData);

                        $htmlContent = str_ireplace('#'.$headerParam['META_DATA_CODE'].'#', $control, $htmlContent, $replacedCount);

                        if ($replacedCount == 0) {
                            $notUseControls .= $control;
                        }
                    }
                ?>
                <style type="text/css">.bp-window-<?php echo $this->methodId;?> table.bp-header-param{table-layout: fixed;} <?php echo $gridHeaderClass; ?></style>
                <?php 
                    echo $buildData['hiddenParam'];                     
                    
                } elseif ($row['type'] == 'detail') {
                    
                    if ($DOMContent['table#'.$row['paramPath'].':eq(0)']->length) {
                        $htmlContent = Mdwebservice::processDtlViewRender($this->methodId, $row, $htmlContent, $this->fillParamData);
                        continue;
                    }
                    
                    $isMultiRow = false;
                    $isTab = false;
                    $htmlHeaderCell = '';
                    $htmlBodyCell = '';
                    $htmlGridFoot = '<td></td>';
                    $gridHead = '';
                    $gridHeadFilter = '';
                    $gridBody = '';
                    $gridFoot = '';
                    $gridBodyRow = '';
                    $gridBodyRowAfter = '';
                    $gridTabBody = '';
                    $gridTabContentHeader = '';
                    $gridTabContentBody = '';
                    $gridRowTypePath = '';
                    $gridClass = '';
                    $detialView = false;
                    $isAggregate = false;
                    $aggregateClass = '';
                    $firstLevelRowArr = array();
                    $sidebarGroupArr_[$row['id']] = array();

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

                        $gridBody .= '<tr class="bp-detail-row">';
                        $gridBody .= '<td class="text-center middle"><span>1</span><input type="hidden" name="param[' . $row['code'] . '.mainRowCount][]"/></td>';
                        $ii = 0;
                        
                        foreach ($row['data'] as $ind => $val) {
                            
                            $foodAmount = '';
                            $aggregateClass = '';
                            $hideClass = '';
                            
                            if ($val['COLUMN_AGGREGATE'] != '') {
                                $isAggregate = true;
                                $foodAmount = '0.00';
                                $aggregateClass = 'aggregate-' . $val['COLUMN_AGGREGATE'];
                            }
                            
                            if ($val['IS_SHOW'] != '1') {
                                $hideClass = ' hide';
                            }
                            
                            $paramRealPath = str_replace('.', '', $val['PARAM_REAL_PATH']);
                            
                            if (strtolower($val['META_TYPE_CODE']) == 'boolean' && $isMultiRow) {
                                if (empty($val['SIDEBAR_NAME'])) {
                                    $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                    $gridHeadFilter .= '<th class="'.$hideClass.'" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></th>';
                                    $gridFoot .= '<td class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></td>';
                                }
                            } else {
                                if (empty($val['SIDEBAR_NAME']) && $isMultiRow && $val['RECORD_TYPE'] !== 'row' && $val['RECORD_TYPE'] !== 'rows') {
                                    $gridHead .= '<th class="' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                    $gridHeadFilter .= '<th class="'.$hideClass.'" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"><input type="text"/></th>';
                                    $gridFoot .= '<td class="text-right' . $hideClass . ' ' . $paramRealPath . ' bigdecimalInit"  data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $foodAmount . '</td>';
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
                                        $gridTabActive = '';
                                        
                                        if ($ii == 1) {
                                            $gridTabActive = ' active';
                                        }
                                        
                                        $isTab = true;
                                        $arg['isTab'] = 'tab';
                                        
                                        $gridRowTypePath = $row['code'].'.'.$val['META_DATA_CODE'];
                                        $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '">';
                                        $gridTabContentHeader .= '<a href="#' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                        $gridTabContentHeader .= '</li>';
                                        $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" data-section-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">';
                                        $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'row', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                        $gridTabContentBody .= '</div>';
                                        
                                    } else {
                                        $childRow = Mdwebservice::appendSubRowInProcess($this->uniqId, $gridClass, $this->methodId, $val);
                                        $gridHead .= $childRow['header'];
                                        $gridHeadFilter .= $childRow['headerFilter'];
                                        $gridBody .= $childRow['body'];
                                        $gridFoot .= $childRow['footer'];
                                    }
                                    
                                } elseif ($val['RECORD_TYPE'] == 'rows') {
                                    
                                    ++$ii;
                                    $gridTabActive = '';
                                    
                                    if ($ii == 1) {
                                        $gridTabActive = " active";
                                    }

                                    $isTab = true;
                                    $arg['isTab'] = 'tab';
                                    $arg['isShowAdd'] = $val['IS_SHOW_ADD'];
                                    $arg['isShowDelete'] = $val['IS_SHOW_DELETE'];
                                    $arg['isShowMultiple'] = $val['IS_SHOW_MULTIPLE'];
                                    
                                    $gridRowTypePath = $row['code'].'.'.$val['META_DATA_CODE'];
                                    $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '">';
                                    $gridTabContentHeader .= '<a href="#' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                    $gridTabContentHeader .= '</li>';
                                    $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '">';
                                    $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'rows', $val['ID'], null, '', $arg, '', $val['COLUMN_COUNT']);
                                    $gridTabContentBody .= '</div>';
                                    
                                } elseif (empty($val['SIDEBAR_NAME'])) {
                                    
                                    $gridBody .= '<td data-cell-path="' . $row['code'] . '.' . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
                                    $gridBody .= Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . '.' . $val['META_DATA_CODE'] . "][0][]", $row['code'] . '.' . $val['META_DATA_CODE'], null);
                                    $gridBody .= '</td>';
                                    
                                } else {
                                    
                                    $sidebarShowRowsDtl_[$row['id']] = true;
                                    if (!in_array($val['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']])) {
                                        $sidebarGroupArr_[$row['id']][$ind] = $val['SIDEBAR_NAME'];
                                        $sidebarDtlRowsContentArr_[$row['id'].$ind] = array();
                                    }

                                    $groupKey = array_search($val['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']]);
                                    $labelAttr = array(
                                        'text' => $this->lang->line($val['META_DATA_NAME']),
                                        'for' => "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]",
                                        'data-label-path' => $row['code'] . "." . $val['META_DATA_CODE']
                                    );
                                    if ($val['IS_REQUIRED'] == '1') {
                                        $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                    }
                                    if ($val['META_TYPE_CODE'] == 'date') {
                                        $inHtml = '<div style="width: 132px; text-align: left;">' . Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array()) . "</div>";
                                    } else {
                                        $inHtml = Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array());
                                    }
                                    $sidebarDtlRowsContentArr_[$row['id'].$groupKey][] = array(
                                        'input_label_txt' => Form::label($labelAttr),
                                        'data_path' => $row['code'] . "." . $val['META_DATA_CODE'], 
                                        'input_html' => $inHtml
                                    );
                                    $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'].$groupKey];                                    
                                }
                            } else {
                                
                                $gridClass .= Mdwebservice::fieldDetailRowStyleClass($val, 'bp-window-' . $this->methodId);
                                $arg = array();
                                if (empty($val['SIDEBAR_NAME'])) {
                                    
                                    if ($isMultiRow) {
                                        $gridBody .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput text-center' . $hideClass . '">';
                                        $gridBody .= Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], null);
                                        $gridBody .= '</td>';
                                    } else {
                                        if ($val['RECORD_TYPE'] == 'rows') {
                                            $arg['isShowAdd'] = $val['IS_SHOW_ADD'];
                                            $arg['isShowDelete'] = $val['IS_SHOW_DELETE'];
                                            $arg['isShowMultiple'] = $val['IS_SHOW_MULTIPLE'];

                                            $gridBodyRowAfter .= '<tr class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">';

                                            if ($val['META_TYPE_CODE'] == 'group' && $val['IS_BUTTON'] == '1') {
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
                                                $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], $val['RECORD_TYPE'], $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                $gridBodyRowAfter .= '</td>';
                                                
                                            } else {
                                                $gridBodyRowAfter .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" style="width: 100%" class="middle float-left" colspan="2">';
                                                $gridBodyRowAfter .= '<p class="meta_description"><i class="fa fa-info-circle"></i> ' . $this->lang->line($val['META_DATA_NAME']) . '</p>';
                                                $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], $val['RECORD_TYPE'], $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                $gridBodyRowAfter .= '</td>';
                                            }

                                            $gridBodyRowAfter .= '</tr>';
                                            
                                        } else if ($val['RECORD_TYPE'] == 'row') {
                                            $gridBodyRowAfter .= '<tr class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">';
                                            $gridBodyRowAfter .= '<td>';
                                            $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], $val['RECORD_TYPE'], $val['ID'], $this->fillParamData, '', array(), 1, $val['COLUMN_COUNT']);
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
                                        'for' => "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]",
                                        'data-label-path' => $row['code'] . "." . $val['META_DATA_CODE']
                                    );
                                    if ($val['IS_REQUIRED'] == '1') {
                                        $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                    }
                                    $sidebarDtlRowContentArr[$groupKey][] = array(
                                        'input_label_txt' => Form::label($labelAttr),
                                        'data_path' => $row['code'] . "." . $val['META_DATA_CODE'], 
                                        'input_html' => Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], $fillParamData)
                                    );
                                    $sidebarDtlRowContentArr[$groupKey] = $sidebarDtlRowContentArr[$groupKey];                                    
                                }
                            }

                            $isDtlTbl = true;
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
                                            $htmlBodyCell .= "<tr data-cell-path='".$subrowPopGroup['data_path']."'>" .
                                            "<td style='width: 229px;' class='left-padding'>" . $this->lang->line($subrowPopGroup['input_label_txt']) . "</td>" .
                                            "<td>" . $subrowPopGroup['input_html'] . "</td>" .
                                            "</tr>";
                                        }
                                        $htmlBodyCell .= '</tbody></table></div>';

                                    }
                                }

                                $htmlBodyCell .= '</div>';
                            }                          
                            $htmlBodyCell .= '</td>';
                        }

                        if ($isTab) {
                            $htmlHeaderCell .= '<th data-cell-path="'.$gridRowTypePath.'"></th>';
                            $gridFoot .= '<td data-cell-path="'.$gridRowTypePath.'"></td>';
                            $gridBody .= '<td data-cell-path="'.$gridRowTypePath.'" class="text-center stretchInput middle">';
                            $gridBody .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\');" class="hide-tbl btn btn-sm purple-plum" style="width:31px" title="Дэлгэрэнгүй">';
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
                        $gridBody .= '</tr>';

                        $gridHead .= $htmlHeaderCell;
                        $gridHead .= '</tr>';
                        $gridHeadFilter .= $htmlHeaderCell;
                        $gridHeadFilter .= '</tr>';
                        $gridFoot .= '<td class="' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '"></td>';
                        $gridFoot .= '<tr>';

                        $content = '<div class="row" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                            <div class="col-md-12">';

                        $gridBodyData = '';

                        /*if ($this->fillParamData) {
                            $renderFirstLevelDtl = (new Mdwebservice())->renderFirstLevelDtl($this->uniqId, $this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
                            if ($renderFirstLevelDtl) {
                                $gridBody = $renderFirstLevelDtl['gridBody'];
                                $gridBodyRow = Mdwebservice::renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData);
                                $gridBodyRow .= $renderFirstLevelDtl['gridBodyRow'];
                                $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
                                $isRowState = $renderFirstLevelDtl['isRowState'];
                            }
                        }*/

                        if (empty($gridBodyRow)) {
                            if (!empty($htmlHeaderCell)) {
                                $content .= '<div class="table-scrollable bprocess-table-dtl-div">
                                        <style type="text/css">#bp-window-' . $this->methodId . ' .bprocess-table-dtl{table-layout: fixed !important; max-width: '.Mdwebservice::$tableWidth.'px !important;} ' . $gridClass . '</style>
                                        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="' . $row['code'] . '">
                                            <thead>
                                                '.$gridHead.$gridHeadFilter.'
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
                        } else {
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
                        
                        $controlGroup = '<div data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                                <fieldset class="collapsible">
                                    <legend>' . $this->lang->line($row['name']) . '</legend>
                                    ' . $content . ' 
                                </fieldset>
                            </div>';
                        
                        $htmlContent = str_ireplace('#'.$row['code'].'#', $controlGroup, $htmlContent, $replacedCount);
                        
                        if ($replacedCount == 0) {
                            $notUseControls .= $controlGroup;
                        }
                    }
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
            echo Form::hidden(array('name' => 'wfmStatusParams', 'value' => (isset($this->wfmStatusParams) ? $this->wfmStatusParams : ''))); 
            echo Form::hidden(array('id' => 'openParams', 'value' => $this->openParams)); 
            echo Form::hidden(array('name' => 'isSystemProcess', 'value' => $this->isSystemProcess)); 
            echo Form::hidden(array('id' => 'saveAddEventInput')); 
            echo Form::hidden(array('name' => 'windowSessionId', 'value' => $this->uniqId));
            ?>
        </div> 
        
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

<?php // <editor-fold defaultstate="collapsed" desc="JAVASCRIPT">     ?>

<script type="text/javascript">
    var bp_window_<?php echo $this->methodId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
    var isEditMode_<?php echo $this->methodId; ?> = <?php echo (($this->isEditMode) ? 'true' : 'false'); ?>;
    
    Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>);
    
    <?php echo $this->bpFullScriptsVarFnc; ?>    
        
    $(function(){
        
        dtlAggregateFunction_<?php echo $this->methodId; ?>();
        setVerticalBannerSize();                                 
        
        // *** BINDING FULL EXPRESSION *** //
        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>();
        <?php echo $this->bpFullScriptsEvent; ?>
        // *** BINDING FULL EXPRESSION *** //               

        showRenderSidebar(bp_window_<?php echo $this->methodId; ?>);
      
    });

    function bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
        var element = typeof elem === 'undefined' ? 'open' : elem; 
        var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
        var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
        var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
        var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
        
        <?php echo $this->bpFullScriptsWithoutEvent; ?>
    }

    function setVerticalBannerSize() {
        var bannerHeight = 0;
        <?php
        if (($this->methodRow['WINDOW_SIZE'] == 'custom' && $this->methodRow['WINDOW_HEIGHT'] != null) && ($this->methodRow['WINDOW_SIZE'] == 'custom' && $this->methodRow['WINDOW_HEIGHT'] != 'auto')) {
            echo 'bannerHeight = Number(' . $this->methodRow['WINDOW_HEIGHT'] . ') - 120;';
            echo '$(".banner-position-dialog-left div.bp-banner-spacer, .banner-position-dialog-right div.bp-banner-spacer, .banner-position-left div.bp-banner-spacer, .banner-position-right div.bp-banner-spacer", bp_window_' . $this->methodId . ').height(bannerHeight);';
        } elseif ($this->methodRow['WINDOW_SIZE'] == 'standart') {
            echo 'bannerHeight = $(\'div[data-bp-uniq-id="'.$this->uniqId.'"] div.page-processs-main-content\').height();';
            echo '$(".banner-position-dialog-left div.bp-banner-spacer, .banner-position-dialog-right div.bp-banner-spacer, .banner-position-left div.bp-banner-spacer, .banner-position-right div.bp-banner-spacer", bp_window_' . $this->methodId . ').height(bannerHeight);';
        }
        ?>
    }
    function dtlAggregateFunction_<?php echo $this->methodId; ?>() {
        var aggregate = $('.bprocess-table-dtl > thead > tr > th[data-aggregate]', bp_window_<?php echo $this->methodId; ?>);
        var cellsSum = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-sum', bp_window_<?php echo $this->methodId; ?>);
        var cellsAvg = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-avg', bp_window_<?php echo $this->methodId; ?>);
        var cellsMax = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-max', bp_window_<?php echo $this->methodId; ?>);
        var cellsMin = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-min', bp_window_<?php echo $this->methodId; ?>);

        cellsSum.on('change', 'input[type=text]', function (e) {
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var sum = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + cellPath + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).sum();
            var gridFootSum = $('.bprocess-table-dtl > tfoot > tr > td[data-cell-path="' + cellPath + '"]', bp_window_<?php echo $this->methodId; ?>);
            gridFootSum.autoNumeric('set', sum);
        });

        cellsAvg.on('change', 'input[type=text]', function (e) {
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var avg = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + cellPath + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).avg();
            var gridFootAvg = $('.bprocess-table-dtl > tfoot > tr > td[data-cell-path="' + cellPath + '"]', bp_window_<?php echo $this->methodId; ?>);
            gridFootAvg.autoNumeric('set', avg);
        });

        cellsMax.on('change', 'input[type=text]', function (e) {
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var max = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + cellPath + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).max();
            var gridFootMax = $('.bprocess-table-dtl > tfoot > tr > td[data-cell-path="' + cellPath + '"]', bp_window_<?php echo $this->methodId; ?>);
            gridFootMax.autoNumeric('set', max);
        });

        cellsMin.on('change', 'input[type=text]', function (e) {
            var min = 0;
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var gridTable = $(this).closest('.bprocess-table-dtl');
            var gridBodyMin = gridTable.find('.tbody > .bp-detail-row > [data-cell-path="' + cellPath + '"]');
            var gridFootMin = gridTable.find('tfoot > tr > td[data-cell-path="' + cellPath + '"]');
            $(gridBodyMin).each(function (index) {
                var cellVal = $(this).find('input[type="text"]').autoNumeric('get');
                if (cellVal != "" || Number(cellVal) > 0) {
                    cellVal = Number(cellVal);
                    if (index === 0) {
                        min = cellVal;
                    }
                    if (min > cellVal) {
                        min = cellVal;
                    }
                }
            });
            gridFootMin.autoNumeric('set', min);
        });
        if ($('.bprocess-table-dtl > .tbody', bp_window_<?php echo $this->methodId; ?>).length > 0) {
            
            var el = aggregate;
            var len = el.length, i = 0;
            for (i; i < len; i++) { 
                var row = $(el[i]);
                var funcName = $(row).attr('data-aggregate');
                var path = $(row).attr('data-cell-path');
                var gridBody = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + path + '"]', bp_window_<?php echo $this->methodId; ?>);
                var footCell = $('.bprocess-table-dtl > tfoot > tr > td[data-cell-path="' + path + '"]', bp_window_<?php echo $this->methodId; ?>);
                if (funcName === 'sum') {
                    var sum = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + path + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).sum();
                    footCell.autoNumeric('set', sum);
                }

                if (funcName == 'avg') {
                    var avg = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + path + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).avg();
                    footCell.autoNumeric('set', avg);
                }

                if (funcName == 'max') {
                    var max = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + path + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).max();
                    footCell.autoNumeric('set', max);
                }

                if (funcName == 'min') {
                    var min = 0;
                    $(gridBody).each(function (index) {
                        if (typeof $(this).find('input[type="text"]').val() != 'undefined') {
                            var cellVal = $(this).find('input[type="text"]').autoNumeric('get');
                            if (cellVal != "" || Number(cellVal) > 0) {
                                cellVal = Number(cellVal);
                                if (index === 0) {
                                    min = cellVal;
                                }
                                if (min > cellVal) {
                                    min = cellVal;
                                }
                            }
                        }
                    });
                    footCell.autoNumeric('set', min);
                }
            }
        }
    }
  
</script>

<?php // </editor-fold> ?>
