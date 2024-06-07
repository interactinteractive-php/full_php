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
    $mainProcessBtnBar = '<div class="col-md-12"><div class="meta-toolbar">';
        
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
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    }
        
        $mainProcessBtnBar .= '<div class="float-right">
            ' . Mdcommon::helpContentButton([
                'contentId' => $this->helpContentId, 
                'sourceId' => $this->methodId, 
                'fromType' => 'meta_process'
            ]) .Form::button(
                array(
                    'class' => 'btn btn-sm btn-circle btn-success bpMainSaveButton',
                    'value' => '<i class="fa fa-save"></i> ' . $this->processActionBtn,
                    'onclick' => 'runBusinessProcess(this, \''.$this->dmMetaDataId.'\', \''.$this->uniqId.'\', '.json_encode($this->isEditMode).');'
                )
            ). Form::button(
                    array(
                'class' => 'btn btn-circle purple-plum ml5',
                'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                'onclick' => 'printProcess(this);'
                    ), isset($this->isPrintView) ? $this->isPrintView : false
            ). Form::button(
                    array(
                'id' => 'printProcess',
                'class' => 'btn btn-sm btn-circle purple-plum ml5 ' . (($this->isEditMode == true) ? '' : 'disabled'),
                'value' => '<i class="fa fa-print"></i> ' . $this->lang->line('print_btn'),
                'onclick' => 'processPrintPreview(\'' . (isset($this->templateDataModelId) ? $this->templateDataModelId : '') . '\', \'' . (isset($this->reportTemplateId) ? $this->reportTemplateId : '') . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->printPath) ? $this->printPath : '') . '\');'
                    ), $this->isPrint
            ).'
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="hide mt10" id="boot-fileinput-error-wrap"></div>
    <div class="clearfix w-100"></div></div>';
    
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
<div class="xs-form bp-banner-container <?php echo $this->methodRow['SKIN'];?>" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
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
    ?>
    
    <div class="row">
    <?php 
    echo $mainProcessBtnBar;
    echo $this->bpTab['tabStart']; 
    echo $dialogProcessLeftBanner; 
    echo $processsDialogContentClassBegin; 
    ?><!-- banner -->
    
    <div class="col-md-12 center-sidebar">        
        <?php echo $mainProcessLeftBanner; ?><!-- banner -->
        <?php echo $processsMainContentClassBegin;?>
        <?php
        $isDtlTbl = false;
        $sidebarShow = false;
        $sidebarShowRowDtl = false;
        
        if ($this->paramList) {
            $tabHead = '';
            $tabHeaderHead = '';
            $tabContent = '';
            $tabHeaderContent = '';
            $tabHeaderArr = array();
            $sidebarHeaderArr = array();
            $sidebarDtlRowArr = array();            
            $getDtlRowsPopup = array();
            (String) $sidebarContent = "";
            (String) $sidebarGroup = "";
            (String) $sidebarGroupMetaRender = "";
            (String) $sidebarGroupMetaRowsRender = "";
            
            $tabActiveFirst = 0;
            foreach ($this->paramList as $k => $row) {
                if ($row['type'] == 'header' && isset($row['data'])) {
                    $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);

                    if (count($buildData['featureParam']) > 0) {
                        echo $ws->renderFeatureParam($this->methodId, $buildData['featureParam'], $this->fillParamData, $this->isDialog);
                    }
                }

                if ($k === 0) {
        ?>
                <div class="row">
                    <div class="col-md-3 bp-treeview-panel">
                        <div class="mt15"></div>
                        <div class="tabbable-line tab-not-padding-top">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#meta-tree-view-tab" class="nav-link active" data-toggle="tab">Бүлгүүд</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane in active" id="meta-tree-view-tab">
                                    <div id="processWindowTreeView" class="tree-demo">
                                        <?php echo $this->folderTreeView; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="col-md-9 bp-treeview-body">
                        <div class="mt15"></div>        
                        <?php
                    }

                    if ($row['type'] == 'header') {
                        if (isset($row['data'])) {
                            $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                            $gridHeaderClass = '';
                            ?>
                            <div class="hideTreeFolderMetaGroupClass hide" data-section-path="<?php echo $row['code']; ?>" id="<?php echo $this->methodId; ?>">
                                <p class="meta_description"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line(!empty($row['description']) ? $row['description'] : $row['name']); ?></p>
                                <div class="table-scrollable table-scrollable-borderless bp-header-param">
                                    <table class="table table-sm table-no-bordered" style="table-layout: fixed !important">
                                        <tbody>
                                        <?php
                                        $resetArrIndex = 0;
                                        $ww = 0;
                                        $_seperator = false;
                                        $rows = array_chunk($buildData['onlyShow'], $this->columnCount);
                                        $w = count($rows);
                                        while($ww < $w) {
                                            $columns = $rows[$ww];
                                            
                                            echo "<tr>";
                                                $xx = count($columns);
                                                $xxx = 0;
                                                $hrClass = '';
                                                $colspan = '';            
                                                
                                                while($xxx < $xx) {
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
                                                    
                                                    if(!empty($columns[$xxx]['SEPARATOR_TYPE'])) {
                                                        $_seperator = true;

                                                        if($this->columnCount == 2)
                                                            if($xxx % 2 == 0) {
                                                                $colspan = 3;
                                                            }
                                                    }
                                                    ?>
                                                    <td class="text-right middle" style="width: <?php echo $this->labelWidth; ?>%">
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
                                                    <td class="middle" style="width: <?php echo $this->columnCount == 1 ? 55 : 27; ?>%" colspan="<?php echo $colspan; ?>">
                                                        <div data-section-path="<?php echo $columns[$xxx]['PARAM_REAL_PATH']; ?>">
                                                            <?php echo Mdwebservice::renderParamControl($this->methodId, $columns[$xxx], "param[" . $columns[$xxx]['META_DATA_CODE'] . "]", $columns[$xxx]['META_DATA_CODE'], $this->fillParamData); ?>
                                                        </div>
                                                    </td>                                    
                                                    <?php
                                                    unset($buildData['onlyShow'][$resetArrIndex++]);
                                                    if($_seperator) {
                                                        $hrClass = $columns[$xxx]['SEPARATOR_TYPE'];                                                    
                                                        $xxx = $xx;
                                                    } else 
                                                        $xxx++;
                                                }
                                            echo "</tr>";
                                            if($_seperator) { ?>
                                                <tr>
                                                    <td colspan="<?php echo $this->columnCount * 2; ?>">
                                                        <hr class="custom<?php echo " ".$hrClass; ?>">
                                                    </td>
                                                </tr>
                                        <?php }

                                            if($_seperator) {
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
                                    <style type="text/css"><?php echo $gridHeaderClass;?></style>
                                    <?php echo $buildData['hiddenParam']; ?>
                                </div>
                            </div>
                            <?php
                        }
                    } elseif ($row['type'] == 'detail') {

                        $isMultiRow = $isTab = $detialView = $isAggregate = false;
                        $htmlHeaderCell = '';
                        $htmlBodyCell = '';
                        $htmlGridFoot = '<td></td>';
                        $gridHead = '';
                        $gridBody = '';
                        $gridFoot = '';
                        $gridBodyRow = '';
                        $gridBodySubTree = '';
                        $gridTabBody = '';
                        $gridTabContentHeader = '';
                        $gridTabContentBody = '';
                        $content = '';
                        $gridClass ='';
                        $aggregateClass = '';
                        $firstLevelRowArr = array();
                        
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
                            $gridHead .= '<th class="rowNumber" style="width:15px;">№</th>';
                            $gridFoot = '<tr>';
                            $gridFoot .= '<td class="number"></td>';
                            $gridBody = '';

                            $gridBody .= '<tr class="bp-detail-row">';
                            $gridBody .= '<td class="text-center middle"><span>1</span><input type="hidden" name="param[' . $row['code'] . '.mainRowCount][]"/></td>';

                            $ii = 0;
                            foreach ($row['data'] as $val) {
                                
                                $foodAmount = $aggregateClass = $hideClass = '';
                                
                                if ($val['COLUMN_AGGREGATE'] != '') {
                                    $isAggregate = true;
                                    $foodAmount = '0.00';
                                    $aggregateClass = 'aggregate-'.$val['COLUMN_AGGREGATE'];
                                }

                                if ($val['IS_SHOW'] != '1') {
                                    $hideClass = " hide";
                                }
                                
                                $paramRealPath = str_replace('.', '', $val['PARAM_REAL_PATH']);
                                $gridClass .= Mdwebservice::fieldDetailStyleClass($val, $paramRealPath, 'bp-window-' . $this->methodId);
                            
                                if ($val['META_TYPE_CODE'] == 'boolean' && $isMultiRow) {
                                    $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                    $gridFoot .= '<td class="' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></td>';
                                } else {
                                    if (empty($val['SIDEBAR_NAME']) && $isMultiRow && $val['RECORD_TYPE'] !== 'row' && $val['RECORD_TYPE'] !== 'rows') {
                                        $gridHead .= '<th class="' . $hideClass . '  ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                        $gridFoot .= '<td class="text-right' . $hideClass . ' bigdecimalInit"  data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">'.$foodAmount.'</td>';
                                    }
                                }

                                if ($isMultiRow) {
                                    $arg = array(
                                        'parentRecordType' => 'rows'
                                    );
                                    if ($val['RECORD_TYPE'] == 'row') {
                                        if ($val['IS_BUTTON'] == '1') {
                                            ++$ii;
                                            $gridTabActive = '';
                                            if ($ii === 1) {
                                                $gridTabActive = " active";
                                            }
                                            $isTab = true;
                                            $arg['isTab'] = 'tab';

                                            $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '" data-li-path="'. $row['code'] . "." . $val['META_DATA_CODE'] .'">';
                                            $gridTabContentHeader .= '<a href="#' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                            $gridTabContentHeader .= '</li>';
                                            $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" data-section-path="'. $row['code'] . "." . $val['META_DATA_CODE'] .'">';
                                            $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'row', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                            $gridTabContentBody .= '</div>';
                                        } else {
                                            $childRow = Mdwebservice::appendSubRowInProcess($this->uniqId, $gridClass, $this->methodId, $val);
                                            $gridHead .= $childRow['header'];
                                            $gridBody .= $childRow['body'];
                                            $gridFoot .= $childRow['footer'];
                                        }
                                    } elseif ($val['RECORD_TYPE'] == 'rows') {
                                        ++$ii;
                                        $gridTabActive = '';
                                        if ($ii === 1) {
                                            $gridTabActive = ' active';
                                        }
                                        $isTab = true;
                                        $arg['isTab'] = 'tab';

                                        $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '">';
                                        $gridTabContentHeader .= '<a href="#' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                        $gridTabContentHeader .= '</li>';
                                        $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . "_" . $val['META_DATA_CODE'] . '">';
                                        $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'rows', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                        $gridTabContentBody .= '</div>';
                                    } elseif (empty($val['SIDEBAR_NAME'])) {
                                        $gridBody .= '<td data-cell-path="' . $row['code'] . '.' . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
                                        $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], null);
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
                                            'for' => "param[" . $row['code'] . '.' . $val['META_DATA_CODE'] . "][0][]",
                                            'data-label-path' => $row['code'] . '.' . $val['META_DATA_CODE']
                                        );
                                        if ($val['IS_REQUIRED'] == '1') {
                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                        }
                                        if($val['META_TYPE_CODE'] == 'date') {
                                            $inHtml = '<div style="width: 132px; text-align: left;">' . Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array()) . "</div>";
                                        } else {
                                            $inHtml = Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array());
                                        }
                                        $sidebarDtlRowsContentArr_[$row['id'].$groupKey][] = array(
                                            'input_label_txt' => Form::label($labelAttr),
                                            'input_html' => $inHtml
                                        );
                                        $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'].$groupKey];                                    
                                    }
                                } else {
                                    if (empty($val['SIDEBAR_NAME'])) {

                                        if ($val['RECORD_TYPE'] === 'rows' || $val['RECORD_TYPE'] === 'row') {
                                            if ($row['recordtype'] == 'row' && strpos($row['paramPath'], '.') !== false) {
                                                // Row төрөлтэй meta TREE бүтцээр харуулах    
                                                $gridBodySubTree .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], $val['RECORD_TYPE'], $val['ID'], null, 'subTree', array(), $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                            } else {
                                                $gridBodyRow .= '<tr class="' . $hideClass . '">';
                                                if ($val['META_TYPE_CODE'] == 'group' and $val['IS_BUTTON'] == '1') {
                                                    $gridBodyRow .= '<td class="text-right middle float-left" style="width: 28%">';
                                                    $labelAttr = array(
                                                        'text' => $this->lang->line($val['META_DATA_NAME'])
                                                    );
                                                    if ($val['IS_REQUIRED'] == '1') {
                                                        $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                                    }
                                                    $gridBodyRow .= Form::label($labelAttr);
                                                    $gridBodyRow .= '</td>';
                                                    $gridBodyRow .= '<td data-cell-path="' . $row['code'] . '.' . $val['META_DATA_CODE'] . '" style="width: 72%" class="middle float-left">';
                                                    $gridBodyRow .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], $val['RECORD_TYPE'], $val['ID'], null, '', array(), $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                    $gridBodyRow .= '</td>';                                                    
                                                } else {
                                                    $gridBodyRow .= '<td data-cell-path="' . $row['code'] . '.' . $val['META_DATA_CODE'] . '" style="width: 100%" class="middle float-left">';
                                                    $gridBodyRow .= '<p class="meta_description"><i class="fa fa-info-circle"></i> ' . $this->lang->line($val['META_DATA_NAME']) . '</p>';
                                                    $gridBodyRow .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], $val['RECORD_TYPE'], $val['ID'], null, '', array(), $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                    $gridBodyRow .= '</td>';                                                    
                                                }
                                                
                                                $gridBodyRow .= '</tr>';
                                            }
                                        } else {
                                            array_push($firstLevelRowArr, $val);
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
                                            'input_html' => Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], $fillParamData)
                                        );
                                        $sidebarDtlRowContentArr[$groupKey] = $sidebarDtlRowContentArr[$groupKey];                                    
                                    }
                                }

                                $isDtlTbl = true;
                            }
                            $gridBodyRow .= Mdwebservice::renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount']);

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
                                                $htmlBodyCell .= "<tr>" .
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
                                    $htmlBodyCell .= '<a href="javascript:;" class="btn red btn-xs bp-remove-row" title="'.$this->lang->line('delete_btn').'"><i class="fa fa-trash"></i></a>';
                                }                            
                                $htmlBodyCell .= '</td>';
                            }                            
                            
                            if ($isTab) {
                                $htmlHeaderCell .= "<th></th>";
                                $gridFoot .= "<td></td>";
                                $gridBody .= '<td class="text-center stretchInput middle">';
                                $gridBody .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-'.$this->methodId.':visible\');" class="hide-tbl btn btn-sm purple-plum" style="width:31px" title="Дэлгэрэнгүй">';
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
                            
                            $gridFoot .= '<td class="' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '"></td>';
                            
                            $content = '<div class="row">
                            <div class="col-md-12">';

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
                                    $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow mr5 bp-add-one-row', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('addRow'), 'onclick' => 'bpAddMainRow_'.$this->methodId.'(this, \''.$this->methodId.'\', \'' . $row['id'] . '\');'));
                                }

                                if ($row['isShowMultiple'] === '1' && $row['groupLookupMeta'] != '' && $row['isShowMultipleMap'] != '0') {
                                    $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow mr5 bp-add-multi-row', 'value' => '<i class="icon-plus3 font-size-12"></i> Олноор нэмэх', 'onclick' => 'bpAddMainMultiRow(this, \'' . $this->methodId . '\', \'' . $row['groupLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\');'));
                                }
                                
                                $content .= '</div>';
                                if ($row['isSave'] == '1') {
                                    $content .= '<div class="col-md-6">
                                                ' . Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow float-right bp-group-save', 'value' => '<i class="fa fa-save"></i> Хадгалах', 'onclick' => 'bpSaveMainRow(this);')) . '
                                            </div>';
                                }
                                $content .= '</div>
                                </div>';
                            }

                            $gridBodyData = '';
                            
                            if ($this->fillParamData) {
                                $renderFirstLevelDtl = $ws->renderFirstLevelDtl($this->uniqId, $this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
                                if ($renderFirstLevelDtl) {
                                    $gridBody = $renderFirstLevelDtl['gridBody'];
                                    $gridBodyRow = $ws->renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData);
                                    $gridBodyRow .= $renderFirstLevelDtl['gridBodyRow'];
                                    $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
                                    $isRowState = $renderFirstLevelDtl['isRowState'];
                                }                                
                            } 

                            if (empty($gridBodyRow)) {
                                if (!empty($htmlHeaderCell)) {
                                    $content .= '<div data-parent-path="'.$row['code'].'" class="bp-overflow-xy-auto">
                                                <style type="text/css">#bp-window-' . $this->methodId . ' .bprocess-table-dtl[data-table-path="' . $row['code'] . '"]{table-layout: fixed !important; } ' . $gridClass . '</style>
                                                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="' . $row['code'] . '">
                                                    <thead>
                                                        ' . $gridHead . '
                                                    </thead>
                                                    <tbody class="tbody">
                                                        ' . /* is reqiered - one row */($detialView ? $gridBody : '') . $gridBodyData . '
                                                    </tbody>
                                                    <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                                                </table>    
                                            </div>';
                                }
                                $content .= '</div>
                                        </div>';
                            } else {
                                if ($row['isSave'] == '1') {
                                    $content .= Form::button(array('class' => 'btn btn-xs green-meadow float-right', 'value' => '<i class="fa fa-save"></i> Хадгалах', 'onclick' => 'bpSaveMainRow(this);'));
                                }
                                $content .= '<div class="table-scrollable table-scrollable-borderless mt0">
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

                            if ($row['tabName'] != "") {
                                $tabActive = '';
                                if ($tabActiveFirst === 0 ) {
                                    $tabActive = ' active';
                                }
                                $tabHead .= '<li class="nav-item" data-li-path="'. $row['code'] .'">
                                        <a href="#tab_' . $this->methodId . '_' . $row['id'] . '" class="nav-link ' . $tabActive . '" data-toggle="tab">' . $this->lang->line($row['tabName']) . '</a>
                                    </li>';

                                $tabContent .= '<div class="tab-pane' . $tabActive . '" id="tab_' . $this->methodId . '_' . $row['id'] . '" data-section-path="' . $row['code'] . '">' . $content . '</div>';
                                ++$tabActiveFirst;
                            } else {
                                echo $gridBodySubTree;
                                echo '<div class="hideTreeFolderMetaGroupClass hide" data-section-path="' . $row['code'] . '" id="' . $row['id'] . '" data-section-required="' . $row['isRequired'] . '">';
                                echo '<p class="meta_description"><i class="fa fa-info-circle"></i> ' . $this->lang->line((!empty($row['description']) ? $row['description'] : $row['name'])) . '</p>';
                                echo $content;
                                echo '</div>';
                            }
                        }
                    }
                }
                if ($tabHead != "") {
                    echo '<div class="tabbable-line tabbable-tabdrop mt10 bp-tabs">
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
        </div>
        </div>
        <div id="responseMethod"></div>
        <?php echo $processsMainContentClassEnd;?>   
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
    </div>
    <?php
    echo Mdlanguage::translateBtnByMetaId($this->methodId);
    echo Form::close(); 
    ?>  
</div>    
<style type="text/css">
.jstree {
    overflow-x: auto; 
    overflow-y: hidden;
    padding-bottom: 10px;
}    
</style>

<?php // <editor-fold defaultstate="collapsed" desc="JAVASCRIPT">  ?>
<script type="text/javascript">
    var bp_window_<?php echo $this->methodId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
    var isEditMode_<?php echo $this->methodId; ?> = <?php echo (($this->isEditMode) ? 'true' : 'false'); ?>;
    var processWindowTreeView = $('#processWindowTreeView');
    var checkFullExp_<?php echo $this->methodId; ?> = <?php echo empty($this->bpFullScriptsEvent) ? 'false' : 'true'; ?>;
    var checkFullExpWithoutEvent_<?php echo $this->methodId; ?> = <?php echo empty($this->bpFullScriptsWithoutEvent) ? 'false' : 'true'; ?>;
    
    Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>);
    
    $(function () {
        
        bp_window_<?php echo $this->methodId; ?>.on('keyup copy paste cut', ".bigdecimalInit", function(){
            var _this = $(this);
            _this.next("input[type=hidden]").val(pureNumber(_this.val()));
        });
        
        processWindowTreeView.jstree({
            "core": {
                "themes": {
                    "responsive": true
                }
            },
            "types": {
                "default": {
                    "icon": "icon-folder2 text-orange-300"
                }
            },
            "plugins": ["types", "cookies"]
        }).on('ready.jstree', function (e, data) {
            $("#processWindowTreeView > ul > li > a").click();
        });
        processWindowTreeView.jstree('open_all');
        
        bp_window_<?php echo $this->methodId; ?>.on("change", "select.linked-combo", function (e, isTrigger) {            
            var _this = $(this);
            if (isTrigger === true || (_this.hasClass("linked-combo-worked") && isTrigger === "EDIT"))
                return;

            if (isTrigger === "EDIT")
                _this.addClass("linked-combo-worked");
            var _outParam = _this.attr("data-out-param");            
            if (_this.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl")) {
                var _thisRow = _this.closest(".bp-detail-row");
                var _outParamSplit = _outParam.split("|");
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    
                    _thisRow.find("td").each(function () {
                        var _thisCell = $(this);
                        var _cellSelect = _thisCell.find("select[data-path='" + selfParam + "']");
                        if (_cellSelect.length) {
                            var _inParams = "";
                            
                            if (typeof _cellSelect.attr("data-in-param") !== 'undefined') {
                                var _inParam = _cellSelect.attr("data-in-param");
                                if (_this.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl")) {
                                    var _thisChildRow = _thisCell.closest(".bp-detail-row");
                                    var _inParamSplit = _inParam.split("|");
                                    
                                    for (var j = 0; j < _inParamSplit.length; j++) {
                                        _thisChildRow.find("td").each(function () {
                                            var _thisChildCell = $(this);
                                            var _lastCombo = _thisChildCell.find("select[data-path='" + _inParamSplit[j] + "']");
                                            if (_lastCombo.length) {
                                                if (_lastCombo.val() !== "") {
                                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                                }
                                            } else {
                                                var _lastCombo = _thisChildCell.find("input[data-path='" + _inParamSplit[j] + "']");
                                                if (_lastCombo.length) {
                                                    if (_lastCombo.val() !== "") {
                                                        _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                                    }
                                                }
                                            }
                                        });
                                    }
                                }
                            }

                            if (_inParams !== "") {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdobject/bpLinkedCombo',
                                    data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                    dataType: 'json',
                                    async: false,
                                    beforeSend: function () {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function (dataStr) {
                                        if (_cellSelect.hasClass("select2")) {
                                            _cellSelect.select2('val', '');
                                            _cellSelect.select2('enable');
                                        } else {
                                            _cellSelect.val('');
                                            _cellSelect.removeAttr('disabled readonly');
                                            _cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                        }
                                        $("option:gt(0)", _cellSelect).remove();
                                        var comboData = dataStr[selfParam];
                                        _cellSelect.addClass("data-combo-set");
                                        
                                        $.each(comboData, function () {
                                            if (isEditMode_<?php echo $this->methodId; ?>) {
                                                if (_cellSelect.attr("data-edit-value") == this.META_VALUE_ID) {
                                                    _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("selected", "selected"));
                                                } else {
                                                    _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                                }
                                            } else
                                                _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                        });
                                        _cellSelect.trigger("change");
                                        Core.initSelect2(_cellSelect);
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                            } else {
                                _cellSelect.select2('val', '');
                                _cellSelect.select2('disable');
                                $("option:gt(0)", _cellSelect).remove();
                                Core.initSelect2(_cellSelect);
                            }
                        }
                    });
                }
            } else {
                var _outParamSplit = _outParam.split("|");
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    var _inParams = "";
                    var _cellSelect = bp_window_<?php echo $this->methodId; ?>.find("select[data-path='" + selfParam + "']");
                    
                    if (_cellSelect.length === 0) {
                        var _cellInp = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + selfParam + "']");
                        
                        if (_this.val().length > 0 && _cellInp.length > 0) {
                            if (_cellInp.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && _cellInp.attr("data-edit-value") === undefined) {
                                if (isTrigger === undefined) {
                                    _cellInp = _cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row").find("input[data-path='" + selfParam + "']");
                                } else
                                    _cellInp = _cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("input[data-path='" + selfParam + "']");
                            }
                            _cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("disabled");
                            _cellInp.parent().find("button").removeAttr("disabled");
                        }
                        
                    } else {  
                        
                        if (_cellSelect.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && _cellSelect.attr("data-edit-value") === undefined) {
                            if (isTrigger === undefined) {
                                _cellSelect = _cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row").find("select[data-path='" + selfParam + "']");
                            } else
                                _cellSelect = _cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("select[data-path='" + selfParam + "']");
                        }
                        if (_cellSelect.length) {
                            if (typeof _cellSelect.attr("data-in-param") !== 'undefined') {
                                var _inParam = _cellSelect.attr("data-in-param");
                                var _inParamSplit = _inParam.split("|");
                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("select[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length) {
                                        if (_lastCombo.val() !== "") {
                                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        }
                                    } else {
                                        var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                                        if (_lastCombo.length) {
                                            if (_lastCombo.val() !== '') {
                                                _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (_inParams !== '') {
                            $.ajax({
                                type: 'post',
                                url: 'mdobject/bpLinkedCombo',
                                data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (dataStr) {
                                    if (_cellSelect.hasClass("select2")) {
                                        _cellSelect.select2('val', '');
                                        _cellSelect.select2('enable');
                                    } else {
                                        _cellSelect.val('');
                                        _cellSelect.removeAttr('disabled readonly');
                                    }
                                    $("option:gt(0)", _cellSelect).remove();
                                    var comboData = dataStr[selfParam];
                                    _cellSelect.addClass("data-combo-set");
                                    
                                    if (isEditMode_<?php echo $this->methodId; ?>) {
                                        _cellSelect.each(function(){
                                            var _thisSelect = $(this);
                                            $.each(comboData, function () {
                                                if (_thisSelect.attr("data-edit-value") == this.META_VALUE_ID) {
                                                    _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("selected", "selected"));
                                                } else
                                                    _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                            });
                                        });
                                    } else { 
                                        $.each(comboData, function () {
                                            _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                        });
                                    }
                                    _cellSelect.trigger("change");
                                    Core.initSelect2(_cellSelect);
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        } else {
                            _cellSelect.select2('val', '');
                            _cellSelect.select2('disable');
                            $("option:gt(0)", _cellSelect).remove();
                            Core.initSelect2(_cellSelect);
                        }
                    }
                }
            }
        });
        bp_window_<?php echo $this->methodId; ?>.on("change", "input.linked-combo", function (e, isTrigger) {
            var _this = $(this);
            if (isTrigger === true || (_this.hasClass("linked-combo-worked") && isTrigger === "EDIT"))
                return;

            if (isTrigger === "EDIT")
                _this.addClass("linked-combo-worked");
            var _outParam = _this.attr("data-out-param");
            
            if (_this.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl")) {
                var _thisRow = _this.closest("tr");
                var _outParamSplit = _outParam.split("|");
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    _thisRow.find("td").each(function () {
                        var _thisCell = $(this);
                        var _cellSelect = _thisCell.find("[data-path='" + selfParam + "']");
                        
                        if (_cellSelect.length) {
                            var _inParams = '';
                            
                            if (typeof _cellSelect.attr("data-in-param") !== 'undefined') {
                                var _inParam = _cellSelect.attr("data-in-param");
                                if (_this.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl")) {
                                    var _thisChildRow = _thisCell.closest("tr");
                                    var _inParamSplit = _inParam.split("|");

                                    for (var j = 0; j < _inParamSplit.length; j++) {
                                        _thisChildRow.find("td").each(function () {
                                            var _thisChildCell = $(this);
                                            var _lastCombo = _thisChildCell.find("[data-path='" + _inParamSplit[j] + "']");
                                            if (_lastCombo.length) {
                                                if (_lastCombo.val() !== "") {
                                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                                }
                                            }
                                        });
                                    }
                                }
                            }

                            if (_inParams !== '') {
                                
                                if (_cellSelect.prop("tagName").toLowerCase() == 'select') {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdobject/bpLinkedCombo',
                                        data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                        dataType: 'json',
                                        async: false,
                                        beforeSend: function () {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function (dataStr) {
                                            if (_cellSelect.hasClass("select2")) {
                                                _cellSelect.select2('val', '');
                                                _cellSelect.select2('enable');
                                            } else {
                                                _cellSelect.val('');
                                                _cellSelect.removeAttr('disabled readonly');
                                                _cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                            }
                                            $("option:gt(0)", _cellSelect).remove();
                                            var comboData = dataStr[selfParam];
                                            _cellSelect.addClass("data-combo-set");
                                            $.each(comboData, function () {
                                                _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                            });
                                            Core.initSelect2(_cellSelect);
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                    
                                } else {
                                    _cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                }
                                
                            } else {
                                if (_cellSelect.prop("tagName").toLowerCase() == 'select') {
                                    if (_cellSelect.hasClass("select2")) {
                                        _cellSelect.select2('val', '');
                                        _cellSelect.select2('disable');
                                    } else {
                                        _cellSelect.val('');
                                        _cellSelect.attr('disable', 'disable');
                                    }
                                    $("option:gt(0)", _cellSelect).remove();
                                    Core.initSelect2(_cellSelect);
                                } else {
                                    _cellSelect.val('');
                                    _cellSelect.parent().find('button').attr('disabled', 'disabled');
                                    _cellSelect.parent().find('input').attr('readonly', 'readonly');
                                }
                            }
                        } 
                    });
                }
            } else {
                var _outParamSplit = _outParam.split("|");
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    var _cellSelect = bp_window_<?php echo $this->methodId; ?>.find("select[data-path='" + selfParam + "']");
                    
                    if (_cellSelect.length === 0) {
                        var _cellInp = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + selfParam + "']");
                        
                        if (_this.val().length > 0 && _cellInp.length > 0) {
                            if (_cellInp.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && _cellInp.attr("data-edit-value") === undefined) {
                                if (isTrigger === undefined) {
                                    _cellInp = _cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row").find("input[data-path='" + selfParam + "']");
                                } else
                                    _cellInp = _cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("input[data-path='" + selfParam + "']");
                            }
                            _cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("readonly disabled");
                            _cellInp.parent().find("button").removeAttr("disabled");
                        }
                        
                    } else {

                        if (_cellSelect.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && _cellSelect.attr("data-edit-value") === undefined) {
                            if (isTrigger === undefined) {
                                _cellSelect = _cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row").find("select[data-path='" + selfParam + "']");
                            } else
                                _cellSelect = _cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("select[data-path='" + selfParam + "']");
                        }

                        var _inParams = '';

                        if (_cellSelect.length) {
                            if (typeof _cellSelect.attr("data-in-param") !== 'undefined') {
                                var _inParam = _cellSelect.attr("data-in-param");
                                var _inParamSplit = _inParam.split("|");

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length) {
                                        if (_lastCombo.val() !== '') {
                                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        }
                                    } else {
                                        var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                                        if (_lastCombo.length) {
                                            if (_lastCombo.val() !== "") {
                                                _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (_inParams !== '') {
                            $.ajax({
                                type: 'post',
                                url: 'mdobject/bpLinkedCombo',
                                data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (dataStr) {
                                    if (_cellSelect.hasClass("select2")) {
                                        _cellSelect.select2('val', '');
                                        _cellSelect.select2('enable');
                                    } else {
                                        _cellSelect.val('');
                                        _cellSelect.removeAttr('disabled readonly');
                                        _cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                    }
                                    $("option:gt(0)", _cellSelect).remove();
                                    var comboData = dataStr[selfParam];
                                    _cellSelect.addClass("data-combo-set");
                                    $.each(comboData, function () {
                                        _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                    });
                                    Core.initSelect2(_cellSelect);
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        } else {
                        
                            if (_cellSelect.prop("tagName").toLowerCase() == 'select') {
                                if (_cellSelect.hasClass("select2")) {
                                    _cellSelect.select2('val', '');
                                    _cellSelect.select2('disable');
                                } else {
                                    _cellSelect.val('');
                                    _cellSelect.attr('disable', 'disable');
                                }
                                $("option:gt(0)", _cellSelect).remove();
                                Core.initSelect2(_cellSelect);
                            } else {
                                _cellSelect.val('');
                                _cellSelect.parent().find('button').attr('disabled', 'disabled');
                                _cellSelect.parent().find('input').attr('readonly', 'readonly');
                            }
                        }
                    }
                }
            }
        });  
        
    });   
    
    <?php echo $this->bpFullScriptsVarFnc; ?>
        
    function metaGroupSelectableTreeFolderFilter(param) {
        $(".hideTreeFolderMetaGroupClass", bp_window_<?php echo $this->methodId; ?>).each(function () {
            var _this = $(this);

            if (!_this.hasClass('hide'))
                _this.addClass('hide').hide();

            if (_this.attr("id") == param) {
                _this.removeClass('hide').show();
            }
        });
    }

    $(function(){
        
        dtlAggregateFunction_<?php echo $this->methodId; ?>();
        setVerticalBannerSize();                                                    
        
        // *** BINDING FULL EXPRESSION *** //
        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>();
        <?php echo $this->bpFullScriptsEvent; ?>
        // *** BINDING FULL EXPRESSION *** //               
        
        bp_window_<?php echo $this->methodId; ?>.on("change", "select.group-dtl-linked", function (e, isTriggered) {
            if (!isTriggered) {
                var _this = $(this);

                if (_this.closest("div.bp-header-param").length > 0) {
                    var postData = {
                        uniqId: '<?php echo $this->uniqId; ?>',
                        processMetaDataId: '<?php echo $this->methodId; ?>',
                        changedParamPath: _this.attr('data-path'),
                        headerData: _this.closest("#wsForm").find("input, select").serialize(),
                        groupPath: _this.attr('data-out-group')
                    };
                }

                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/bpLinkedGroup',
                    data: postData,
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function(dataStr) {
                        $.each(dataStr, function(tablePath, v) {
                            $("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>).hide();
                            $("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>).html(v);
                            Core.initBPInputType($("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>));
                            
                            bp_window_<?php echo $this->methodId; ?>.find("table[data-table-path='" + tablePath + "'] > .tbody > .bp-detail-row").each(function(){
                                bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($(this));
                            });
                            $("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>).show();
                        });
                    },
                    error: function() {
                        alert("Error");
                    }
                }).done(function() {
                    dtlAggregateFunction_<?php echo $this->methodId; ?>();
                    Core.unblockUI();
                });
            }
        });
        bp_window_<?php echo $this->methodId; ?>.on("change", "input.group-dtl-linked", function(e, isTriggered) {
            if (!isTriggered) {
                var _this = $(this);

                if (_this.closest("div.bp-header-param").length > 0) {
                    var postData = {
                        uniqId: '<?php echo $this->uniqId; ?>',
                        processMetaDataId: '<?php echo $this->methodId; ?>',
                        changedParamPath: _this.attr('data-path'),
                        headerData: _this.closest("#wsForm").find("input, select").serialize(),
                        groupPath: _this.attr('data-out-group')
                    };
                }
                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/bpLinkedGroup',
                    data: postData,
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function(dataStr) {
                        $.each(dataStr, function(tablePath, v) {
                            $("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>).hide();
                            $("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>).html(v);
                            Core.initBPInputType($("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>));
                            
                            bp_window_<?php echo $this->methodId; ?>.find("table[data-table-path='" + tablePath + "'] > .tbody > .bp-detail-row").each(function(){
                                bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($(this));
                            });
                            $("table[data-table-path='" + tablePath + "'] > .tbody", bp_window_<?php echo $this->methodId; ?>).show();
                        });
                    },
                    error: function() {
                        alert("Error");
                    }
                }).done(function() {
                    _this.trigger('change', [true]);
                    dtlAggregateFunction_<?php echo $this->methodId; ?>();
                    Core.unblockUI();
                });
            }
        }); 
        
        showRenderSidebar(bp_window_<?php echo $this->methodId; ?>);

        $('.bprocess-table-dtl > .tbody', bp_window_<?php echo $this->methodId; ?>).on('focus', 'tr', function () {
            var $row = $(this);
            $('.bprocess-table-dtl > .tbody > .bp-detail-row').removeClass("currentTarget");
            if ($row.hasClass("currentTarget")) {
                $row.removeClass("currentTarget");
            } else {
                $row.addClass("currentTarget");
            }            
        });
        $('.bprocess-table-dtl .tbody', bp_window_<?php echo $this->methodId; ?>).find('tr').on('change', 'input, select, textarea', function (e, ce) {
            if (ce !== "EDIT") {
                var _thisChangedElem = $(this);
                var _thisChangedRowElem = _thisChangedElem.closest('tr');
                if (!_thisChangedRowElem.hasClass("removed-tr")) {
                    _thisChangedRowElem.find("input[data-path*='rowState']").val('MODIFIED');
                }
            }
        });
        $('.bprocess-table-row .tbody', bp_window_<?php echo $this->methodId; ?>).find('tr').on('change', 'input, select, textarea', function (e, ce) {
            if(ce !== "EDIT")
                $(this).closest('tbody').find("input[data-path*='rowState']").val('MODIFIED');
        });
        $('div.bp-header-param', bp_window_<?php echo $this->methodId; ?>).find('tr').on('change', 'input, select, textarea', function (e, ce) {
            if(ce !== "EDIT")
                $(this).closest('tbody').find("input[data-path*='rowState']").val('MODIFIED');
        });        

        if ($(".boot-file-input-wrap", bp_window_<?php echo $this->methodId; ?>).length > 0) {
            var infile = $(".boot-file-input-wrap", bp_window_<?php echo $this->methodId; ?>).find("input[type='file']");
            var infilePath = $(".boot-file-input-wrap", bp_window_<?php echo $this->methodId; ?>).parent().find("input[name='updateFileData']").val();
            var fileprev = '';
            
            if (infile.hasAttr('data-valid-extension') && infile.attr('data-valid-extension') != '') {
                var getExtension = infile.attr('data-valid-extension').replace(/\s+/g, '');
                getExtension = getExtension.split(',');
            } else {
                var getExtension = ['jpg', 'jpeg', 'png', 'gif'];
            }
            
            if (typeof infilePath !== 'undefined') {
                var ext = ["jpg", "jpeg", "png", "gif"];
                if (ext.indexOf(infilePath.split('.').pop().toLowerCase()) !== -1)
                    fileprev = '<img src="' + infilePath + '" style="height: 145px" class="file-preview-image">';
                else
                    fileprev = '<div class="file-preview-other"><span class="file-icon-4x"><i class="fa fa-file-o fa-2x text-success"></i></span></div>';
            } else {
                fileprev = '<img src="assets/core/global/img/user.png" style="height: 145px" class="file-preview-image" alt="Default photo">';
            }

            infile.fileinput({
                showCaption: false,
                showUpload: false,
                browseClass: "btn btn-xs btn-primary",
                removeClass: "btn btn-xs",
                removeLabel: "",
                defaultPreviewContent: '<img src="assets/core/global/img/user.png" style="height: 145px" class="file-preview-image" alt="Default photo">',
                previewFileIcon: '<i class="fa fa-file-o fa-2x text-success"></i>',
                allowedFileExtensions: getExtension,
                elErrorContainer: '#boot-fileinput-error-wrap',
                msgErrorClass: 'alert alert-block alert-danger',
                previewFileIconSettings: {
                    'docx': '<i class="fa fa-file-word-o fa-2x text-success"></i>',
                    'doc': '<i class="fa fa-file-word-o fa-2x text-success"></i>',
                    'xlsx': '<i class="fa fa-file-excel-o fa-2x text-success"></i>',
                    'pptx': '<i class="fa fa-file-powerpoint-o fa-2x text-success"></i>',
                    'pdf': '<i class="fa fa-file-pdf-o fa-2x text-success"></i>',
                    'zip': '<i class="fa fa-file-archive-o fa-2x text-success"></i>'
                },
                previewSettings: {
                    image: {width: "auto", height: "145px"},
                    text: {width: "145px", height: "145px"},
                    other: {width: "145px", height: "145px"}
                },
                initialPreview: [
                    fileprev
                ]
            });
            
            $(".boot-file-input-wrap", bp_window_<?php echo $this->methodId; ?>).children().append('<button type="button" onclick="profilePhotoDownload_<?php echo $this->methodId; ?>(this);" class="btn btn-xs fileinput-remove fileinput-download-button"><i class="fa fa-download"></i></button>');
        }        
        
        bp_window_<?php echo $this->methodId; ?>.on("keydown", "input[type='text']:visible", function(e){
            var addBtn = '';
            if(e.which == 13) {
                var tbl = $(this).parents('table.table:first');
                var parentDiv = tbl.parent('div');
                if (parentDiv.hasClass('param-tree-container')) {
                    addBtn = parentDiv.children('.btn:first');
                } else {
                    addBtn = tbl.parents('fieldset').find('.btn:first');
                }
                var tblInput = tbl.find('tbody tr td:visible input[type="text"]');
                var cellIndex = tblInput.index(this);        
                if (tblInput.length == (cellIndex+1)) {
                    var tableIndex = $("table.table", bp_window_<?php echo $this->methodId; ?>).index(tbl) + 1;
                    var tableNext = $("table.table", bp_window_<?php echo $this->methodId; ?>).eq(tableIndex);
                    var headerTbl = parentDiv.hasClass('bp-header-param');
                    if (headerTbl) {
                        tableNext.find('tbody tr td:visible input[type="text"]:first').focus();
                    } else {
                        if (tableNext.find('tbody:first tr').length > 0) {
                            tableNext.find('tbody tr td:visible input[type="text"]:first').focus();
                        } else {
                            addBtn.trigger('click');
                        }
                    }
                } else {
                    tblInput[cellIndex+1].focus();  
                } 
                e.preventDefault();
            }
        });
        bp_window_<?php echo $this->methodId; ?>.on("change", ".bprocess-table-dtl td input[type='text']:visible", function(e){
            dtlAggregateFunction_<?php echo $this->methodId; ?>();
        });
        <?php // <editor-fold defaultstate="collapsed" desc="Sidebar дахь утгыг DTL рүү SET хийх">  ?>
        $("div.sidebarDetailSection", bp_window_<?php echo $this->methodId; ?>).on("keyup", "input", function() {
            var selectedTR = $(bp_window_<?php echo $this->methodId; ?>).find('.bprocess-table-dtl .tbody').find('tr.currentTarget');
            var vthis = $(this);
            if(vthis.closest("div.meta-autocomplete-wrap").length === 0)
                selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + vthis.attr("data-path") + "']").val(pureNumber(vthis.val()));
        });        
        $("div.sidebarDetailSection", bp_window_<?php echo $this->methodId; ?>).on("keyup", "textarea", function() {
            var selectedTR = $(bp_window_<?php echo $this->methodId; ?>).find('.bprocess-table-dtl .tbody').find('tr.currentTarget');
            var vthis = $(this);
            selectedTR.find("td:last-child").find("i.input_html").find("textarea[data-path='" + vthis.attr("data-path") + "']").text(vthis.val());
        });        
        $("div.sidebarDetailSection", bp_window_<?php echo $this->methodId; ?>).on("click", "input[type='checkbox']", function() {
            var selectedTR = $(bp_window_<?php echo $this->methodId; ?>).find('.bprocess-table-dtl .tbody').find('.bp-detail-row.currentTarget');
            var vthis = $(this);
            if(vthis.is(':checked'))
                selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + vthis.attr("data-path") + "']").attr("checked", "checked");
            else
                selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + vthis.attr("data-path") + "']").removeAttr("checked");
        });        
        $("div.sidebarDetailSection", bp_window_<?php echo $this->methodId; ?>).on("change", "select", function() {
            var selectedTR = $(bp_window_<?php echo $this->methodId; ?>).find('.bprocess-table-dtl .tbody').find('.bp-detail-row.currentTarget');
            var vthis = $(this);
            selectedTR.find("td:last-child").find("i.input_html").find("select[data-path='" + vthis.attr("data-path") + "'] option")
                .each(function() {
                    $(this).removeAttr("selected");
                });
            selectedTR.find("td:last-child").find("i.input_html").find("select[data-path='" + vthis.attr("data-path") + "'] option")
                .filter('[value="' + vthis.val() + '"]').attr("selected", "selected");
        });        
        $("div.sidebarDetailSection", bp_window_<?php echo $this->methodId; ?>).on("changeDate", "input", function() {
            var selectedTR = $(bp_window_<?php echo $this->methodId; ?>).find('.bprocess-table-dtl .tbody').find('.bp-detail-row.currentTarget');
            var vthis = $(this);
            selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + vthis.attr("data-path") + "']").val(vthis.val());
        });     
        <?php // </editor-fold> ?>    
        
        $('.bprocess-table-dtl, .bprocess-table-row').on("blur", "textarea:not(.description_autoInit)", function(){
            $(this).removeAttr("style").css({
                height: '30px'
            });
            if($(this).closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl"))
                $(this).parent().removeAttr("style");
        });                     
    });
    function bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
        var element = typeof elem === 'undefined' ? 'open' : elem; 
        var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
        var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
        var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
        var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
        
        <?php echo $this->bpFullScriptsWithoutEvent; ?>
    }
    <?php
    if ($isDtlTbl) {
    ?>
    function bpAddMainRow_<?php echo $this->methodId; ?>(elem, processId, rowId) {
        var _this = $(elem);
        var _parent = _this.closest("fieldset");
        if (_parent.length > 0) {
            var _parent = _this.closest("div.tab-pane");
        }
        if (_parent.length === 0 || _parent.attr("id") === "bp_main_tab") {
            var _parent = _this.closest("div.hideTreeFolderMetaGroupClass");
        }
        $.ajax({
            type: 'post',
            url: 'mdcommon/renderBpDtlRow',
            data: {processId: processId, uniqId: <?php echo $this->uniqId; ?>, rowId: rowId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (dataStr) {
                var $html = $('<div />', {html: dataStr});
                $html.find("tr:eq(0)").addClass("display-none added-bp-row");
                
                if (isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val("ADDED");   
                }
                _parent.find(".bprocess-table-dtl:eq(0) > .tbody").append($html.html());

                Core.initBPInputType(_parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child"));
                
                _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").find("select.linked-combo").each(function () {
                    if ($(this).attr("data-out-param").indexOf(".") !== -1) {
                        $(this).trigger("change");
                    }
                });
                _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").find("input.linked-combo").each(function () {
                    if ($(this).attr("data-out-param").indexOf(".") !== -1) {
                        $(this).trigger("change");
                    }
                });
                _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").find("input[data-in-param]").each(function () {
                    var _thisLp = $(this);
                    var dataInParam = _thisLp.attr("data-in-param").split("|");
                    var dataInParamLength = dataInParam.length;
                    var linkedFieldIsEmpty = true;
                    for (var ip = 0; ip < dataInParamLength; ip++) {
                        if ($("input[data-path='"+dataInParam[ip]+"']", bp_window_<?php echo $this->methodId; ?>).val() === "") {
                            linkedFieldIsEmpty = false;
                        }
                    }
                    if (linkedFieldIsEmpty) {
                        setBpRowParamEnable(bp_window_<?php echo $this->methodId; ?>, _thisLp, _thisLp.attr("data-path"));
                    }
                });
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            dtlAggregateFunction_<?php echo $this->methodId; ?>();
            partialExpressionStart_<?php echo $this->methodId; ?>(_parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child"));
            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>(_parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child"), _parent.find(".bprocess-table-dtl:eq(0)").attr('data-table-path'), false);
            _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row.currentTarget").removeClass("currentTarget");
            _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row").each(function (i) {
                $(this).find("td:first").eq(0).find("span").text(i + 1);
            });
            bpSetRowIndex(_parent);
            _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row.display-none").removeClass("display-none");
            _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").addClass("currentTarget").find("input:visible:first").focus();
            Core.unblockUI();
        });
    }
    function bpAddMainRowSidebar(elem, htmlStr) {
        var _this = $(elem);
        var _parent = _this.closest("div");

        $.ajax({
            type: 'post',
            url: 'mdcommon/cryptEncodeToDecodeByPost',
            data: {string: htmlStr},
            beforeSend: function () {
                Core.blockUI({
                    target: _parent,
                    animate: true
                });
            },
            success: function (dataStr) {
                var $dialogName = 'dialog-param-tree-popup-' + Core.getUniqueID('sidebarParam');
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                $("#" + $dialogName).empty().html(dataStr);

                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: $(elem).attr('title'),
                    width: 880,
                    height: "auto",
                    modal: true,
                    position: {my: 'top', at: 'top+100'},
                    close: function () {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: 'Хадгалах', class: 'btn green-meadow btn-sm', click: function () {

                                var $tableRowsCell = "<tr>";
                                var $tableCell = "";
                                $("#" + $dialogName).find("table.table > .tbody > .bp-detail-row").each(function (key) {
                                    var _thisRow = $(this);
                                    var count_td = _thisRow.find("td").length;

                                    for (var i = 1; i <= count_td; i++) {
                                        _thisRow.find("td:eq(" + i + ")").find("input").each(function () {
                                            $(this).val($(this).val());
                                        });
                                        _thisRow.find("td:eq(" + i + ")").find("select").each(function () {
                                            $(this).find('option[value="' + $(this).val() + '"]').attr("selected", "selected");
                                        });
                                    }

                                    if (key === 0) {
                                        $tableRowsCell += '<td style="padding-left: 15px !important;">';
                                        $tableRowsCell += '<a href="javascript:;" onclick="paramTreePopup(this, \'' + Core.getUniqueID('sidebarLink') + '\, \'div#bp-window-<?php echo $this->methodId; ?>:visible\');" class="form-control-plaintext float-left thisChangeText" title="Дэлгэрэнгүй">';
                                        $tableRowsCell += _thisRow.find("td:last-child").find("input").val().length === 0
                                                ? _thisRow.find("td:last-child").find("select").find("option:selected").text()
                                                : _thisRow.find("td:last-child").find("input").val();
                                        $tableRowsCell += '</a>';

                                        $tableCell += '<tr>';
                                        $tableCell += _thisRow.html();
                                        $tableCell += '</tr>';

                                    } else {
                                        $tableCell += '<tr>';
                                        $tableCell += _thisRow.html();
                                        $tableCell += '</tr>';
                                    }
                                });
                                $tableRowsCell += '<div class="hide param-tree-container">';
                                $tableRowsCell += '<table class="table table-sm table-no-bordered"><tbody>';
                                $tableRowsCell += $tableCell;
                                $tableRowsCell += '</tbody></table>';
                                $tableRowsCell += '</div>';
                                $tableRowsCell += '</td>';
                                $tableRowsCell += '<td style="max-width: 10px" class="middle">';
                                $tableRowsCell += '<a href="javascript:;" onclick="removeBpMainRowSidebar(this);" class="hide-tbl btn red btn-xs float-right" title="<?php echo $this->lang->line('delete_btn'); ?>">';
                                $tableRowsCell += '<i class="fa fa-trash"></i>';
                                $tableRowsCell += '</a> ';
                                $tableRowsCell += '</td>';
                                $tableRowsCell += '</tr>';

                                if (isEditMode_<?php echo $this->methodId; ?>) {
                                    var $html = $('<div />', {html: dataStr});
                                    $html.find("input[data-path*='rowState']").val("ADDED");
                                    _parent.find("table.sidebar_detail > .tbody").append($html.html());
                                } else {
                                    _parent.find("table.sidebar_detail > .tbody").append($tableRowsCell);
                                    _parent.find("table.sidebar_detail > .tbody > .bp-detail-row").each(function (i) {
                                        $(this).find("td").eq(0).find("span").text(i + 1);
                                    });
                                }
                                bpSetRowIndex();
                                bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>();
                                Core.initBPInputType(_parent.find("table.sidebar_detail > .tbody > .bp-detail-row:last-child"));
                                $("#" + $dialogName).dialog('close');
                            }},
                        {
                            text: 'Хаах', class: 'btn blue-madison btn-sm', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": true,
                    "dblclick": "maximize",
                    "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }
                });
                $("#" + $dialogName).dialog('open');
                Core.initBPAjax($("#" + $dialogName));
                Core.unblockUI(_parent);
            },
            error: function () {
                alert("Error");
            }
        });
    }
    function bpAddDtlRow_<?php echo $this->methodId; ?>(elem, htmlStr) {
        var _this = $(elem);
        var _parent = _this.parent();
        var $table = _parent.find('table.table:first');
        $.ajax({
            type: 'post',
            url: 'mdcommon/cryptEncodeToDecodeByPost',
            data: {processId: '<?php echo $this->methodId; ?>', rowId: $table.attr('data-row-id'), string: htmlStr},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (dataStr) {
                var $html = $('<div />', {html: dataStr});
                $html.find("tr:eq(0)").addClass("display-none");
                
                if (isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val("ADDED");
                }
                $table.find('> .tbody').append($html.html());
                
                $table.find('> .tbody > .bp-detail-row').each(function (i) {
                    $(this).find("td:first").eq(0).find("span").text(i + 1);
                });
                    
                _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").find("input:visible:first").focus();
                bpSetRowIndexDepth(_parent, bp_window_<?php echo $this->methodId; ?>);
                
                Core.initBPInputType($table.find('> .tbody > .bp-detail-row:last-child'));
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            dtlAggregateFunction_<?php echo $this->methodId; ?>();
            partialExpressionStart_<?php echo $this->methodId; ?>(_parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child"));
            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>(_parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child"), _parent.find(".bprocess-table-dtl:eq(0)").attr('data-table-path'), false);
            _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").removeClass("display-none");
        });
    }       
    function removeBpMainRow(elem) {
        var _this = $(elem);
        var parent = _this.closest("tr");
        var parentTbl = parent.closest("table");

        if (parent.hasClass("saved-bp-row")) {
            if (parent.hasClass("removed-tr")) {
                parent.removeClass("removed-tr");
                parent.find("input[data-path*='rowState']").val("MODIFIED");
            } else {
                parent.addClass("removed-tr");
                parent.find("input[data-path*='rowState']").val("REMOVED");
            }
        } else {
            parent.remove();
            var _parent = parentTbl.parent();
            _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row").each(function (i) {
                $(this).find("td:first").eq(0).find("span").text(i + 1);
            });
            parentTbl.find("input[type=text]:visible").trigger("keyup");
            bpSetRowIndex(parentTbl.parent());
        }
    }
    function removeBpMainRowSidebar(elem) {
        var _this = $(elem);
        var parent = _this.closest("tr");
        var parentTbl = parent.closest("table");

        if (parent.hasClass("saved-bp-row")) {
            if (parent.hasClass("removed-tr")) {
                parent.removeClass("removed-tr");
                parent.find("input[data-path*='rowState']").val("MODIFIED");
            } else {
                parent.addClass("removed-tr");
                parent.find("input[data-path*='rowState']").val("REMOVED");
            }
        } else {
            parent.remove();
        }
    }
    function removeBpDtlRow(elem) {
        var _this = $(elem);
        var parent = _this.closest("tr");
        var parentTbl = parent.closest("table");

        if (parent.hasClass("saved-bp-row") && isEditMode_<?php echo $this->methodId; ?>) {
            if (parent.hasClass("removed-tr")) {
                parent.removeClass("removed-tr");
                parent.find("input[data-path*='rowState']").val("MODIFIED");
            } else {
                parent.addClass("removed-tr");
                parent.find("input[data-path*='rowState']").val("REMOVED");
            }
        } else {
            parent.remove();
            parentTbl.find("tbody > tr").each(function (i) {
                $(this).find("td:first").eq(0).find("span").text(i + 1);
            });
            bpSetRowIndexDepth(parentTbl.parent(), bp_window_<?php echo $this->methodId; ?>);
        }
        dtlAggregateFunction_<?php echo $this->methodId; ?>();
    }
    function bpSaveMainRow(elem) {
        var _this = $(elem);
        var headerParam = false;
        var groupParam = false, treeViewGroupParam = '';

        if ($("div.bp-header-param", bp_window_<?php echo $this->methodId; ?>).length > 0) {
            var _thisHeaderParamElement = $("div.bp-header-param", bp_window_<?php echo $this->methodId; ?>);
            headerParam = true;
        }
        if (_this.closest("div.hideTreeFolderMetaGroupClass").length > 0) {
            var _thisGroupParamElement = _this.closest("div.hideTreeFolderMetaGroupClass");

            _thisGroupParamElement.addClass('notSerializeTreeFolderMetaGroup')
            _thisGroupParamElement.closest('div.col-md-9').find('.hideTreeFolderMetaGroupClass').each(function(){
                var _thisTreeFolder = $(this);

                if (typeof _thisTreeFolder.attr('data-section-required') !== 'undefined' && !_thisTreeFolder.hasClass('notSerializeTreeFolderMetaGroup') && _thisTreeFolder.attr('data-section-required') === '1') {
                    treeViewGroupParam += '&' + _thisTreeFolder.find("input, select").serialize();
                }
            });                     
            groupParam = true;
        } else {
            if (_this.closest("fieldset").length > 0) {
                var _thisGroupParamElement = _this.closest("fieldset");
                groupParam = true;
            }
        }

        var formData;

        if (headerParam) {
            formData += _thisHeaderParamElement.find("input, select").serialize();
        }
        if (groupParam) {
            formData += '&' + _thisGroupParamElement.find("input, select").serialize() + treeViewGroupParam;
        }
        if ($("#bprocessCoreParam", bp_window_<?php echo $this->methodId; ?>).length > 0) {
            formData += '&' + $("#bprocessCoreParam", bp_window_<?php echo $this->methodId; ?>).find("input").serialize();
        }                
        /*formData += '&methodId=<?php echo $this->methodId; ?>';*/

        $.ajax({
            type: 'post',
            url: 'mdwebservice/runProcess',
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү',
                    boxed: true
                });
            },
            success: function (responseData) {
                PNotify.removeAll();
                
                if (responseData.status === 'success') {
                    new PNotify({
                        title: 'Success',
                        text: responseData.message,
                        type: 'success',
                        sticker: false
                    });
                    var resultData = responseData.resultData;
                    
                    if (typeof resultData.id !== 'undefined') {
                        $("input[data-path='id']", bp_window_<?php echo $this->methodId; ?>).val(resultData.id);
                        $("input[data-path='rowState']", bp_window_<?php echo $this->methodId; ?>).val('modified');
                    }
                    
                    var getSaveGroupPath = _thisGroupParamElement.find('table').attr('data-table-path');
                    if (typeof resultData[getSaveGroupPath.toLowerCase()] !== 'undefined') {
                        var groupSavedParams = resultData[getSaveGroupPath.toLowerCase()];
                        $.each(groupSavedParams, function(k, v){
                            $("table[data-table-path='"+getSaveGroupPath+"'] > .tbody > .bp-detail-row:eq("+k+") > td", bp_window_<?php echo $this->methodId; ?>).find("input[data-field-name='id']:eq(0)").val(v.id);
                        });
                    }
                    
                    bp_window_<?php echo $this->methodId; ?>.find('input[name="windowSessionId"]').val(responseData.uniqId);
                    
                    <?php if ($this->dmMetaDataId) { echo 'window[\'objectdatagrid_'.$this->dmMetaDataId.'\'].datagrid(\'reload\');'; } ?>
                } else {
                    new PNotify({
                        title: 'Error',
                        text: responseData.message,
                        type: 'error',
                        sticker: false
                    });
                }
                
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
<?php
}
?> 
    
    function partialExpressionStart_<?php echo $this->methodId; ?>(el, humanNotTriggered) {
        if (checkFullExp_<?php echo $this->methodId; ?> || checkFullExpWithoutEvent_<?php echo $this->methodId; ?>)
            return;
        
        if (typeof (humanNotTriggered) === 'undefined') {
            humanNotTriggered = true;
        }
        $("div.bp-header-param", bp_window_<?php echo $this->methodId; ?>).find("select.linked-combo").trigger("change", [humanNotTriggered]);
    }    
    
    function setVerticalBannerSize() {
        var bannerHeight = 0;
        <?php
        if (($this->methodRow['WINDOW_SIZE'] == 'custom' && $this->methodRow['WINDOW_HEIGHT'] != null) && ($this->methodRow['WINDOW_SIZE'] == 'custom' && $this->methodRow['WINDOW_HEIGHT'] != 'auto')) {
            echo 'bannerHeight = Number('.$this->methodRow['WINDOW_HEIGHT'].') - 120;';
        } else {
            echo 'bannerHeight = $(\'div[data-bp-uniq-id="'.$this->uniqId.'"] div.page-processs-main-content\').height();';
        }
        ?>
        $(".banner-position-dialog-left div.bp-banner-spacer, .banner-position-dialog-right div.bp-banner-spacer, .banner-position-left div.bp-banner-spacer, .banner-position-right div.bp-banner-spacer", bp_window_<?php echo $this->methodId; ?>).height(bannerHeight);
    }

    function dtlAggregateFunction_<?php echo $this->methodId; ?> () {
        Core.initNumberInput();
        var aggregate = $('.bprocess-table-dtl thead tr th[data-aggregate]', bp_window_<?php echo $this->methodId; ?>);
        var cellsSum = $('.bprocess-table-dtl tbody tr td.aggregate-sum', bp_window_<?php echo $this->methodId; ?>);
        var cellsAvg = $('.bprocess-table-dtl tbody tr td.aggregate-avg', bp_window_<?php echo $this->methodId; ?>);
        var cellsMax = $('.bprocess-table-dtl tbody tr td.aggregate-max', bp_window_<?php echo $this->methodId; ?>);
        var cellsMin = $('.bprocess-table-dtl tbody tr td.aggregate-min', bp_window_<?php echo $this->methodId; ?>);
        cellsSum.on('change', 'input[type=text]', function(e) {
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var sum = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+cellPath+'"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).sum();
            var gridFootSum = $('.bprocess-table-dtl tfoot tr td[data-cell-path="'+cellPath+'"]', bp_window_<?php echo $this->methodId; ?>);
            gridFootSum.autoNumeric('set', sum);
        });
        
        cellsAvg.on('change', 'input[type=text]', function(e) {
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var avg = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+cellPath+'"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).avg();
            var gridFootAvg = $('.bprocess-table-dtl tfoot tr td[data-cell-path="'+cellPath+'"]', bp_window_<?php echo $this->methodId; ?>);
            gridFootAvg.autoNumeric('set', avg);
        });
        
        cellsMax.on('change', 'input[type=text]', function(e) {
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var max = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+cellPath+'"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).max();
            var gridFootMax = $('.bprocess-table-dtl tfoot tr td[data-cell-path="'+cellPath+'"]', bp_window_<?php echo $this->methodId; ?>);
            gridFootMax.autoNumeric('set', max);
        });

        cellsMin.on('change', 'input[type=text]', function(e) {
            var min = 0;
            var cellPath = $(this).parent('td').attr('data-cell-path');
            var gridBodyMin = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+cellPath+'"]', bp_window_<?php echo $this->methodId; ?>);
            var gridFootMin = $('.bprocess-table-dtl tfoot tr td[data-cell-path="'+cellPath+'"]', bp_window_<?php echo $this->methodId; ?>);
            $(gridBodyMin).each(function(index) {
                var cellVal = $(this).find('input[type="text"]').autoNumeric('get');
                if (cellVal != '') {
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
        if ($('.bprocess-table-dtl tbody', bp_window_<?php echo $this->methodId; ?>).length > 0) {
            $.each(aggregate, function(key, row){
                var funcName = $(row).attr('data-aggregate');
                var path = $(row).attr('data-cell-path');
                var gridBody = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+path+'"]', bp_window_<?php echo $this->methodId; ?>);
                var footCell = $('.bprocess-table-dtl tfoot tr td[data-cell-path="'+path+'"]', bp_window_<?php echo $this->methodId; ?>);
                if (funcName === 'sum') {
                    var sum = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+path+'"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).sum();
                    footCell.autoNumeric('set', sum);
                }

                if (funcName == 'avg') {
                    var avg = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+path+'"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).avg();
                    footCell.autoNumeric('set', avg);
                }

                if (funcName == 'max') {
                    var max = $('.bprocess-table-dtl tbody tr td[data-cell-path="'+path+'"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).max();
                    footCell.autoNumeric('set', max);
                }

                if (funcName == 'min') {
                    var min = 0;
                    $(gridBody).each(function(index) {
                        var cellVal = $(this).find('input[type="text"]').autoNumeric('get');
                        if (cellVal != "") {
                            cellVal = Number(cellVal);
                            if (index === 0) {
                                min = cellVal;
                            }
                            if (min > cellVal) {
                                min = cellVal;
                            }
                        }

                    });
                    footCell.autoNumeric('set', min);
                }
            });
        }
    }
    
    function bpAddMainMultiRow(elem, processMetaDataId, lookupMetaDataId, groupLookupMetaTypeId, paramRealPath) {
        $.ajax({
            type: 'post',
            url: 'mdmetadata/dataViewSelectableGrid',
            data: {
                chooseType: 'multi',
                metaDataId: lookupMetaDataId,
                processMetaDataId: processMetaDataId,
                paramRealPath: paramRealPath
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                var $dialogName = 'dialog-dataview-selectable-'+lookupMetaDataId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                $("#" + $dialogName).empty().html(data.Html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: "auto",
                    modal: true,
                    close: function () {
                        enableScrolling();
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.addbasket_btn, class: 'btn green-meadow btn-sm float-left', click: function () {
                            window['basketCommonSelectableDataGrid_'+lookupMetaDataId]();
                        }},
                        {text: data.choose_btn, class: 'btn blue btn-sm', click: function () {
                            var countBasketList = $('#commonSelectableBasketDataGrid_'+lookupMetaDataId).datagrid('getData').total;
                            if (countBasketList > 0) {
                                var rows = dataViewSelectedRowsResolver($('#commonSelectableBasketDataGrid_'+lookupMetaDataId).datagrid('getRows'));
                                selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, processMetaDataId, paramRealPath, lookupMetaDataId, rows);
                                $("#" + $dialogName).dialog('close');
                            }
                        }},
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });
    }
    function selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, processMetaDataId, paramRealPath, lookupMetaDataId, rows) {
        var _this = $(elem);
        var _parent = _this.closest("fieldset");
        if (_parent.length === 0) {
            var _parent = _this.closest("div.tab-pane");
        }
        
        $.ajax({
            type: 'post',
            url: 'mdwebservice/renderDtlGroup',
            data: { 
                processMetaDataId: processMetaDataId,
                paramRealPath: paramRealPath,
                lookupMetaDataId: lookupMetaDataId, 
                selectedRows: rows,
                uniqId: '<?php echo $this->uniqId; ?>'
            },
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (dataStr) {
                var $html = $('<div />', {html: dataStr});
                $html.children('tr').addClass("added-bp-row display-none multi-added-row");
                
                if (isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val("ADDED");   
                }
                _parent.find(".bprocess-table-dtl > .tbody").append($html.html());
                
                _parent.find(".bprocess-table-dtl > .tbody > .bp-detail-row").each(function (i) {
                    $(this).find("td:first").eq(0).find("span").text(i + 1);
                });
                bpSetRowIndex(_parent);
            },
            error: function () {
                alert("Error");
            }
        }).done(function(){
            Core.initInputType();
            _parent.find(".bprocess-table-dtl > .tbody > .bp-detail-row.multi-added-row", bp_window_<?php echo $this->methodId; ?>).each(function(){
                bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($(this), _parent.find(".bprocess-table-dtl:eq(0)").attr('data-table-path'), true);
            });
            _parent.find(".bprocess-table-dtl > .tbody > .bp-detail-row.multi-added-row").removeClass("multi-added-row display-none");
            dtlAggregateFunction_<?php echo $this->methodId; ?>();
            Core.unblockUI();
        });
    }
    
    var isSaveConfirm_<?php echo $this->methodId; ?> = false;
    
    function processBeforeSave_<?php echo $this->methodId; ?>(thisButton){
        PNotify.removeAll();
        
        <?php echo $this->bpFullScriptsSave; ?>
        
        return true;
    }
    function processAfterSave_<?php echo $this->methodId; ?>(thisButton, responseStatus) {
        
        <?php echo $this->bpFullScriptsAfterSave; ?>

        return true;
    }
    function profilePhotoDownload_<?php echo $this->methodId; ?>(elem) {
        location.href=$(elem).closest('.boot-file-input-wrap').children('a:first').attr('href');
    }
</script>
<?php
// </editor-fold> ?>