<?php
$ws = new Mdwebservice();
$processsMainContentClassBegin = $processsMainContentClassEnd = $processsDialogContentClassBegin = $processsDialogContentClassEnd = $dialogProcessLeftBanner = $mainProcessLeftBanner = '';
$isBanner = false;

if ($this->isDialog == false && Input::isEmpty('workSpaceId') == true) {
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
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 '.(($this->isEditMode == true) ? '' : 'disabled').'" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . ($this->lang->line('printTemplate'.$this->methodId) == 'printTemplate'.$this->methodId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->methodId)) . '</button>';
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

    if ($mainProcessLeftBanner != '') {
        $processsMainContentClassBegin = '<div class="processs-main-content">';
        $processsMainContentClassEnd = '</div>';
        $isBanner = true;
    }
    
} else {
    $mainProcessBtnBar = '';
    $reportPrint = '';
    if ($this->isPrint) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green '.(($this->isEditMode == true) ? '' : 'disabled').'" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . ($this->lang->line('printTemplate'.$this->methodId) == 'printTemplate'.$this->methodId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->methodId)) . '</button>';
    }    

    $mainProcessLeftBanner = '';
    if ($dialogProcessLeftBanner != '') {
        $processsDialogContentClassBegin = '<div class="processs-main-content">';
        $processsDialogContentClassEnd = '</div>';
        $isBanner = true;
    }
}
?>
<div class="xs-form bp-banner-container bp-view-process web-process" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
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
            $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' class="btn btn-sm purple-plum btn-circle" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['processname'] .'</button> ';
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
        echo $mainProcessLeftBanner; /* banner */
        echo $processsMainContentClassBegin; 
        
        $isDtlTbl = $sidebarShow = $sidebarShowRowDtl = false;
        
        if ($this->paramList) {
            
            $tabHead = $tabHeaderHead = $tabContent = $tabHeaderContent = $sidebarContent = $sidebarGroup = $sidebarGroupMetaRender = $sidebarGroupMetaRowsRender = '';
            $tabNameArr = $tabHeaderArr = $sidebarHeaderArr = $sidebarDtlRowArr = $getDtlRowsPopup = array();           
            $tabActiveFirst = 0;
            $indexK = 0;
            foreach ($this->paramList as $k => $row) {
                if ($row['type'] == 'header') {
                    if (isset($row['data'])) {
                        
                        $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                        
                        if (count($buildData['featureParam']) > 0) {
                            echo $ws->renderViewFeatureParam($this->methodId, $buildData['featureParam'], $this->fillParamData, $this->isDialog);
                        }
                        $gridHeaderClass = '';
                        ?>
                        <div class="table-scrollable table-scrollable-borderless bp-header-param" style="background-color: #fff">
                            <table class="table table-sm table-no-bordered bp-header-param">
                                <tbody>
                                    <?php
                                    $resetArrIndex = 0;
                                    $ww = 0;
                                    $_seperator = false;
                                    $rows = array_chunk($buildData['onlyShow'], $this->columnCount);
                                    $w = count($rows);
                                    while ($ww < $w) {
                                        $columns = $rows[$ww];

                                        echo "<tr" . ($this->columnCount == 1 ? " data-cell-path='" . $rows[$ww][0]['META_DATA_CODE'] . "'" : "") . ">";
                                        $xx = count($columns);
                                        $xxx = 0;
                                        $hrClass = $colspan = '';

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

                                                if ($this->columnCount == 2 && $xxx % 2 == 0)
                                                    $colspan = 3; 
                                            }                                            
                                            ?>
                                        <td class="text-right middle" style="width: <?php echo $this->labelWidth; ?>%">
                                            <?php
                                            $labelAttr = array(
                                                'text' => $this->lang->line($columns[$xxx]['META_DATA_NAME']),
                                                'for' => "param[" . $columns[$xxx]['META_DATA_CODE'] . "]",
                                                'data-label-path' => $columns[$xxx]['META_DATA_CODE']
                                            );
                                            echo Form::label($labelAttr);
                                            ?>
                                        </td>
                                        <td class="middle" style="width: <?php echo $this->columnCount == 1 ? 55 : 27; ?>%" colspan="<?php echo $colspan; ?>">
                                            <div data-section-path="<?php echo $columns[$xxx]['PARAM_REAL_PATH']; ?>">
                                                <?php echo Mdwebservice::renderViewParamControl($this->methodId, $columns[$xxx], "param[" . $columns[$xxx]['META_DATA_CODE'] . "]", $columns[$xxx]['META_DATA_CODE'], $this->fillParamData); ?>
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
                                            <td colspan="<?php echo $this->columnCount * 2; ?>">
                                                <hr class="custom<?php echo " " . $hrClass; ?>">
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
                            <style type="text/css">.bp-window-<?php echo $this->methodId;?> table.bp-header-param{table-layout: fixed;} <?php echo $gridHeaderClass; ?></style>
                            <?php echo $buildData['hiddenParam']; ?>
                        </div>
                        <?php } } elseif ($row['type'] == 'detail') {
                            $isDetailUserConfig = $isMultiRow = $isTab = $detailView = $isAggregate = false;
                            $htmlHeaderCell = $htmlBodyCell = $gridHead = $gridBody = $gridFoot = $gridBodyRow = $gridBodyRowAfter = $gridTabBody = $gridTabContentHeader = $gridTabContentBody = $gridClass = $aggregateClass = '';
                            $htmlGridFoot = '<td></td>';
                            $gridRowTypePath = $firstLevelRowArr = array();
                            $sidebarGroupArr_[$row['id']] = array();

                            if (isset($row['data']) && $row['isShow'] == '1') {
                                if ($indexK%2 == 0) {
                                    echo '<div class="row mr0 ml0 merge-row-column float-left col-md-12 pl0 pr0">';
                                }
                                if ($row['isRequired'] === '1' || $row['isFirstRow'] === '1') {
                                    $detailView = true;
                                }
                            
                                if ($row['recordtype'] == 'rows') {
                                    if (!empty($row['sidebarName']))
                                        continue;

                                    $isMultiRow = true;
                                }
                                
                                if ($row['recordtype'] == 'rows' && $row['dtlTheme'] == '1') {
                                    
                                    $content = Mdwebservice::detailThemeView($this->methodId, $row['dtlTheme'], $row, $this->fillParamData);
                                    
                                } else {
                                    
                                    $gridHead = '<tr><th class="rowNumber" style="width:30px;">№</th>';
                                    $gridBody = '<tr class="bp-detail-row"><td class="text-center middle"><span>1</span><input type="hidden" name="param[' . $row['code'] . '.mainRowCount][]"/></td>';
                                    $gridFoot = '<tr><td class="number"></td>';
                                    $ii = 0;

                                    foreach ($row['data'] as $ind => $val) {

                                        $foodAmount = $aggregateClass = '';

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

                                        if (strtolower($val['META_TYPE_CODE']) == 'boolean' && $isMultiRow) {
                                            if (empty($val['SIDEBAR_NAME'])) {
                                                $gridHead .= '<th class="text-left' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                                $gridFoot .= '<td class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></td>';
                                            }
                                        } else {
                                            if (empty($val['SIDEBAR_NAME']) && $isMultiRow && $val['RECORD_TYPE'] !== 'row' && $val['RECORD_TYPE'] !== 'rows') {
                                                $gridHead .= '<th class="text-left ' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                                $gridFoot .= '<td class="text-right' . $hideClass . ' ' . $paramRealPath . ' bigdecimalInit"  data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $foodAmount . '</td>';
                                            }
                                        }

                                        if ($isMultiRow) {
                                            $gridClass .= Mdwebservice::fieldDetailStyleClassView($val, $paramRealPath, 'bp-window-' . $this->methodId);

                                            $arg = array(
                                                'parentRecordType' => 'rows'
                                            );
                                            if ($val['RECORD_TYPE'] == 'row') {
                                                if ($val['IS_BUTTON'] == '1') {
                                                    ++$ii;
                                                    $gridTabActive = '';
                                                    if ($ii === 1)
                                                        $gridTabActive = ' active';

                                                    $isTab = true;
                                                    $arg['isTab'] = 'tab';

                                                    array_push($gridRowTypePath, $row['code'] . '.' . $val['META_DATA_CODE']);
                                                    
                                                    $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '">';
                                                    $gridTabContentHeader .= '<a href="#' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                                    $gridTabContentHeader .= '</li>';
                                                    $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" data-section-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">';                                            
                                                    $gridTabContentBody .= $ws->buildTreeParamView($this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'row', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                                    $gridTabContentBody .= '</div>';
                                                } else {
                                                    $childRow = Mdwebservice::appendSubRowInProcessView($this->uniqId, $gridClass, $this->methodId, $val);
                                                    $gridHead .= $childRow['header'];
                                                    $gridBody .= $childRow['body'];
                                                    $gridFoot .= $childRow['footer'];
                                                }
                                            } elseif ($val['RECORD_TYPE'] == 'rows') {
                                                ++$ii;
                                                $gridTabActive = '';
                                                if ($ii === 1)
                                                    $gridTabActive = ' active';

                                                $isTab = true;
                                                $arg['isTab'] = 'tab';
                                                $arg['isShowAdd'] = $val['IS_SHOW_ADD'];
                                                $arg['isShowDelete'] = $val['IS_SHOW_DELETE'];
                                                $arg['isShowMultiple'] = $val['IS_SHOW_MULTIPLE'];

                                                array_push($gridRowTypePath, $row['code'] . '.' . $val['META_DATA_CODE']);
                                                
                                                $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '">';
                                                $gridTabContentHeader .= '<a href="#' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                                $gridTabContentHeader .= '</li>';
                                                $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . "_" . $val['META_DATA_CODE'] . '">';
                                                $gridTabContentBody .= $ws->buildTreeParamView($this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'rows', $val['ID'], null, '', $arg, '', $val['COLUMN_COUNT']);
                                                $gridTabContentBody .= '</div>';
                                            } elseif (empty($val['SIDEBAR_NAME'])) {
                                                $gridBody .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
                                                $gridBody .= Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], null);
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
                                                if ($val['META_TYPE_CODE'] == 'date') {
                                                    $inHtml = '<div style="width: 132px; text-align: left;">' . Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array()) . "</div>";
                                                } else {
                                                    $inHtml = Mdwebservice::renderViewParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array());
                                                }
                                                $sidebarDtlRowsContentArr_[$row['id'].$groupKey][] = array(
                                                    'input_label_txt' => Form::label($labelAttr),
                                                    'input_html' => $inHtml
                                                );
                                                $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'].$groupKey];                                    
                                            }
                                        } 

                                        $isDtlTbl = true;
                                    }

                                    if ($isMultiRow) {

                                        $actionWidth = 40;
                                        if (isset($sidebarShowRowsDtl_[$row['id']])) {
                                            $actionWidth = 70;
                                        }
                                        $htmlHeaderCell = '<th class="action ' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '" style="width:' . $actionWidth . 'px;"></th>';
                                        $htmlBodyCell .= '<td class="text-center middle' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '">';

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
                                        $htmlBodyCell .= '</td>';
                                    }

                                    if ($isTab) {
                                        
                                        if ($gridRowTypePath) {
                                            $gridRowTypePath = implode('|', $gridRowTypePath);
                                            $htmlHeaderCell .= '<th style="width:70px" data-cell-path="' . $gridRowTypePath . '"></th>';
                                        } else {
                                            $htmlHeaderCell .= '<th style="width:40px" data-cell-path=""></th>';
                                        }
                                        
                                        $gridFoot .= '<td data-cell-path="'.$gridRowTypePath.'"></td>';
                                        $gridBody .= '<td data-cell-path="'.$gridRowTypePath.'" class="text-center middle">';
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
                                    $gridFoot .= '<td class="' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '"></td>';
                                    $gridFoot .= '<tr>';

                                    $content = '<div class="row w-100" data-section-path="' . $row['code'] . '">
                                        <div class="col-md-12">';
                                    
                                    if ($isMultiRow && isset($row['columnUserConfig'])) {
                                        $content .= '<div class="table-toolbar">
                                                <div class="row">
                                                    <div class="col-md-12 text-right">';
                                        $content .= '<a href="javascript:;" class="btn btn-secondary btn-sm btn-circle default" title="Тохиргоо" onclick="bpDetailUserOption(this, \'' . $this->uniqId . '\');"><i class="fa fa-cog"></i></a>';
                                        $content .= '</div></div></div>';
                                        
                                        $isDetailUserConfig = true;
                                    }
                                    $gridBodyData = '';

                                    if ($this->fillParamData) {
                                        $renderFirstLevelDtl = $ws->renderFirstLevelDtlView($this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
                                        if ($renderFirstLevelDtl) {
                                            $gridBody = $renderFirstLevelDtl['gridBody'];
                                            $gridBodyRow = $ws->renderFirstLevelAddEditDtlRowView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData);
                                            $gridBodyRow .= $renderFirstLevelDtl['gridBodyRow'];
                                            $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
                                            $isRowState = $renderFirstLevelDtl['isRowState'];
                                        }
                                    }

                                    if (empty($gridBodyRow)) {
                                        if (!empty($htmlHeaderCell)) {
                                            
                                            $pagingAttributes = ' data-row-id="'.$row['id'].'"';
                                            
                                            if ($isDetailUserConfig) {
                                                $detailUserConfig = $ws->getDetailUserConfig($this->methodId, $row['id'], $row['code']);
                                                $pagingAttributes .= ' data-show-fields="'.$detailUserConfig['showFields'].'" data-hide-fields="'.$detailUserConfig['hideFields'].'"';
                                            }
                                            
                                            $content .= '<div class="table-scrollable bprocess-table-dtl-div">
                                                        <style type="text/css">#bp-window-' . $this->methodId . ' .bprocess-table-dtl{table-layout: fixed !important; max-width: '.Mdwebservice::$tableWidth.'px !important;} ' . $gridClass . '</style>
                                                        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="' . $row['code'] . '"' . $pagingAttributes . '>
                                                            <thead>
                                                                '.$gridHead.'
                                                            </thead>
                                                            <tbody class="tbody">
                                                                ' . ($detailView ? $gridBody : '') . $gridBodyData . '
                                                            </tbody>
                                                            <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                                                        </table>    
                                                    </div>
                                                </div>
                                            </div>';
                                        }

                                    } else {
                                        
                                        
                                        $content .= '<div class="table-scrollable table-scrollable-borderless mt0 w-100" data-section-path="' . $row['code'] . '">
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
                                                        $tabLabelWidth = $this->labelWidth <= 10 ? ($this->labelWidth - 1.5) : ($this->labelWidth - 3);
                                                        $tabHeaderContent .= '<td class="text-right middle" style="width: ' . $tabLabelWidth . '%">';
                                                            $labelAttr = array(
                                                                'text' => $this->lang->line($subrow['META_DATA_NAME']),
                                                                'for' => "param[" . $subrow['META_DATA_CODE'] . "]",
                                                                'data-label-path' => $subrow['META_DATA_CODE']
                                                            );
                                                            $tabHeaderContent .= Form::label($labelAttr);
                                                        $tabHeaderContent .= "</td>";
                                                        $tabHeaderContent .= '<td class="middle" style="width: 55%">';
                                                            $tabHeaderContent .= '<div data-section-path="' . $subrow['PARAM_REAL_PATH'] . '">';
                                                            $tabHeaderContent .= Mdwebservice::renderViewParamControl($this->methodId, $subrow, "param[" . $subrow['META_DATA_CODE'] . "]", $subrow['META_DATA_CODE'], $this->fillParamData);
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

                                        $tabContent .= '<div class="tab-pane' . $tabActive . '" id="tab_' . $this->methodId . '_' . $row['id'] . '">' . $tabHeaderContent . $content . '<!--'.$row['tabName'].'--></div>';
                                        ++$tabActiveFirst;

                                        $tabNameArr[$row['tabName']] = '';
                                        
                                    } else {
                                        $tabContent = str_replace('<!--'.$row['tabName'].'-->', $content.'<!--'.$row['tabName'].'-->', $tabContent);
                                    }
                                    
                                } else {
                                    
                                    $colCount = ($row['columnCount']) ? $row['columnCount'] : '12';
                                    /*
                                    echo '<div data-section-path="' . $row['code'] . '" class="col-md-'. $colCount .' pl0 merge-column">
                                            <div class="bg-white" style="float: left; width: 100%; height: 100%">
                                                <div class="card-header card-header-no-padding header-elements-inline">
                                                    <div class="card-title" style="color: #0074af;">'. $this->lang->line($row['name']) .'</div>
                                                </div>
                                                <div class="card-body">
                                                    ' . $content . ' 
                                                </div>
                                            </div>
                                        </div>';*/
                                    
                                    echo '<div data-section-path="' . $row['code'] . '" class="col-md-'. $colCount .' pl0 merge-column">
                                            <fieldset class="collapsible">
                                                <legend>' . $this->lang->line($row['name']) . '</legend>
                                                <div class="col-md-12 merge-column-content">' . $content . ' </div>
                                            </fieldset>
                                        </div>';
                                }
                                
                                if ($indexK%2 == 1) {
                                    echo '</div>';
                                }
                                $indexK++;
                            }
                        }
            }
            
            $tabHeaderActiveFirst = 0;
            if ($tabHead != '' || !empty($tabHeaderArr)) {
                if (isset($tabHeaderArr)) {
                    foreach ($tabHeaderArr as $key => $row) {
                        $tabUniqId = getUID();
                        $tabActive = '';
                        if ($tabHeaderActiveFirst === 0 && empty($tabHead)) {
                            $tabActive = ' active';
                        }                        
                        $tabHead .= '<li class="nav-item">
                                    <a href="#tab_' . $this->methodId . '_' . $tabUniqId . '" class="nav-link ' . $tabActive . '" data-toggle="tab">' . $this->lang->line($row) . '</a>
                                </li>';                            
                        $tabHeaderContent = '<div class="table-scrollable table-scrollable-borderless bp-header-param">
                                            <table class="table table-sm table-no-bordered bp-header-param"><tbody>';
                        foreach ($tabHeaderContentArr[$key] as $subrow) {
                            $tabHeaderParam = '';
                            if ($subrow['IS_SHOW'] != '1') {
                                $tabHeaderParam = 'hide';
                            }
                            $tabHeaderContent .= "<tr data-cell-path='" . $subrow['META_DATA_CODE'] . "' class='" . $tabHeaderParam . "'>";
                            $tabLabelWidth = $this->labelWidth <= 10 ? ($this->labelWidth - 1.5) : ($this->labelWidth - 3);
                            $tabHeaderContent .= '<td class="text-right middle" style="width: ' . $tabLabelWidth . '%">';
                                $labelAttr = array(
                                    'text' => $this->lang->line($subrow['META_DATA_NAME']),
                                    'for' => "param[" . $subrow['META_DATA_CODE'] . "]",
                                    'data-label-path' => $subrow['META_DATA_CODE']
                                );
                                $tabHeaderContent .= Form::label($labelAttr);
                            $tabHeaderContent .= "</td>";
                            $tabHeaderContent .= '<td class="middle" style="width: 55%">';
                                $tabHeaderContent .= '<div data-section-path="' . $subrow['PARAM_REAL_PATH'] . '">';
                                $tabHeaderContent .= Mdwebservice::renderViewParamControl($this->methodId, $subrow, "param[" . $subrow['META_DATA_CODE'] . "]", $subrow['META_DATA_CODE'], $this->fillParamData);
                                $tabHeaderContent .= "</div>";
                            $tabHeaderContent .= "</td>";                            
                            $tabHeaderContent .= "</tr>";
                        }
                        $tabHeaderContent .= '</tbody></table></div>';
                        $tabContent .= '<div class="tab-pane' . $tabActive . '" id="tab_' . $this->methodId . '_' . $tabUniqId . '">' . $tabHeaderContent . '</div>';
                        ++$tabHeaderActiveFirst;
                    }
                }                
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

<style>
    .vr-workspace-theme20 .package-tab-name {
        text-transform: none !important;
        font-size: 18px !important;
        font-weight: normal;
    }
</style>

<?php // <editor-fold defaultstate="collapsed" desc="JAVASCRIPT">     ?>

<script type="text/javascript">
    var bp_window_<?php echo $this->methodId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
    var isEditMode_<?php echo $this->methodId; ?> = <?php echo (($this->isEditMode) ? 'true' : 'false'); ?>;
    
    Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>);
    
    <?php echo $this->bpFullScriptsVarFnc; ?>    
        
    $(function(){
        
        var detectBtnMeta = bp_window_<?php echo $this->methodId; ?>.find('button[data-path]');
        if(detectBtnMeta.length) {
            detectBtnMeta.parent().attr('style', 'border-bottom: 1px solid #fff !important;border-right: 1px solid #fff !important;');
            detectBtnMeta.closest('tr').find('td:first-child').attr('style', 'border-bottom: 1px solid #fff !important;border-right: 1px solid #fff !important;border-left: 1px solid #fff !important;background-color:#fff').text('');
        }
        
        dtlAggregateFunction_<?php echo $this->methodId; ?>();                               
        
        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>();
        <?php echo $this->bpFullScriptsEvent; ?>       
            
        if (typeof window['bpLoadDetailHideShowFields'] === 'function') {
            bpLoadDetailHideShowFields(bp_window_<?php echo $this->methodId; ?>);
        }    

        showRenderSidebar(bp_window_<?php echo $this->methodId; ?>);
        
        Core.initCodeHighlight(bp_window_<?php echo $this->methodId; ?>);
        
        <?php if(!Input::isEmpty('workSpaceId')) {
            $workspaceId = Input::numeric('workSpaceId');
            ?>
            // var $workspaceId_<?php echo $this->dmMetaDataId.'_'.$workspaceId; ?> = $("div#workspace-id-<?php echo $workspaceId; ?>");
            // $workspaceId_<?php echo $this->dmMetaDataId.'_'.$workspaceId; ?>.find('.merge-row-column').closest('.package-div').attr('style', 'background-color: inherit !important; margin-bottom: -32px;margin-top: -13px;');
            // $workspaceId_<?php echo $this->dmMetaDataId.'_'.$workspaceId; ?>.find('.merge-row-column').closest('.package-div').find(".package-tab-name").attr('style', 'margin-top: 23px;position: absolute;z-index: 1;border:none;margin-left:10px;');
            // $workspaceId_<?php echo $this->dmMetaDataId.'_'.$workspaceId; ?>.find('.layout-cell').closest('.package-div').find(".package-tab-name").attr('style', 'margin-top: 8px; margin-bottom: 10px; margin-left: 3px;');
            // $workspaceId_<?php echo $this->dmMetaDataId.'_'.$workspaceId; ?>.find('.main-dataview-container').closest('.package-div').find(".package-tab-name").attr('style', 'margin-top: 8px; margin-bottom: 8px; margin-left: 3px;');

            var workspaceMenuId = $workspaceId_<?php echo $this->dmMetaDataId.'_'.$workspaceId; ?>.find("ul.workspace-menu > li.active").children().attr("data-menu-id");
            $workspaceId_<?php echo $this->dmMetaDataId.'_'.$workspaceId; ?>.find('.workspace-main-container').find('div[data-menu-id="'+workspaceMenuId+'"]').find('.merge-row-column').each(function (index, row) {
                if (2 == $(row).children().length && typeof $(row).attr('style') === 'undefined') {
                    var $height = $(row).height();


                    if($(row).find(' > .col-md-6').length == 2) {
                        $(row).children().last().css('padding-right', '0px');
                    }

                    if($(row).find(' > .col-md-6').length > 1) {
                        $(row).children().each(function(){
                            if(!$(this).hasClass('col-md-12'))
                                $(this).height(($height) - 8 + 'px');
                        });
                    }

                }
            });
            
        <?php } else { ?>
            
            $('.merge-row-column').closest('.package-div').attr('style', 'background-color: inherit !important; margin-bottom: -32px;margin-top: -13px;');
            $('.merge-row-column').closest('.package-div').find(".package-tab-name").attr('style', 'margin-top: 23px;position: absolute;z-index: 1;border:none;margin-left:10px;');
            $('.layout-cell').closest('.package-div').find(".package-tab-name").attr('style', 'margin-top: 8px; margin-bottom: 10px; margin-left: 3px;');
            $('.main-dataview-container').closest('.package-div').find(".package-tab-name").attr('style', 'margin-top: 8px; margin-bottom: 8px; margin-left: 3px;');

            $('.merge-row-column').each(function (index, row) {
                if (2 == $(row).children().length && typeof $(row).attr('style') === 'undefined') {
                    var $height = $(row).height();


                    if($(row).find(' > .col-md-6').length == 2) {
                        $(row).children().last().css('padding-right', '0px');
                    }

                    if($(row).find(' > .col-md-6').length > 1) {
                        $(row).children().each(function(){
                            if(!$(this).hasClass('col-md-12'))
                                $(this).height(($height) - 8 + 'px');
                        });
                    }

                }
            });            
            
        <?php } ?>
    });

    function bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
        var element = typeof elem === 'undefined' ? 'open' : elem; 
        var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
        var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
        var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
        var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
        
        <?php echo $this->bpFullScriptsWithoutEvent; ?>
    }
    function dtlAggregateFunction_<?php echo $this->methodId; ?>() {
        var aggregate = $('.bprocess-table-dtl > thead > tr > th[data-aggregate]', bp_window_<?php echo $this->methodId; ?>);
        var cellsSum = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-sum', bp_window_<?php echo $this->methodId; ?>);
        var cellsAvg = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-avg', bp_window_<?php echo $this->methodId; ?>);
        var cellsMax = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-max', bp_window_<?php echo $this->methodId; ?>);
        var cellsMin = $('.bprocess-table-dtl > .tbody > .bp-detail-row > .aggregate-min', bp_window_<?php echo $this->methodId; ?>);

        if ($('.bprocess-table-dtl > .tbody', bp_window_<?php echo $this->methodId; ?>).length > 0) {
            
            var el = aggregate;
            var len = el.length, i = 0;
            
            for (i; i < len; i++) { 
                
                var row = $(el[i]);
                var funcName = row.attr('data-aggregate');
                var path = row.attr('data-cell-path');
                var $gridBody = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + path + '"]', bp_window_<?php echo $this->methodId; ?>);
                var $footCell = $('.bprocess-table-dtl > tfoot > tr > td[data-cell-path="' + path + '"]', bp_window_<?php echo $this->methodId; ?>);
                
                if (funcName === 'sum') {
                    
                    var sum = 0, cellVal;
                    $gridBody.each(function() {
                        cellVal = $(this).text();
                        if (cellVal != '') {
                            sum += pureNumber(cellVal);
                        }
                    });
                    $footCell.autoNumeric('set', sum);
                }

                if (funcName == 'avg') {
                    var avg = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + path + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).avg();
                    $footCell.autoNumeric('set', avg);
                }

                if (funcName == 'max') {
                    var max = $('.bprocess-table-dtl > .tbody > .bp-detail-row > [data-cell-path="' + path + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).max();
                    $footCell.autoNumeric('set', max);
                }

                if (funcName == 'min') {
                    var min = 0;
                    $gridBody.each(function (index) {
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
                    $footCell.autoNumeric('set', min);
                }
            }
        }
    }
    
    var isSaveConfirm_<?php echo $this->methodId; ?> = false;
    
    function processBeforeSave_<?php echo $this->methodId; ?>(thisButton) {
        PNotify.removeAll();
        
        <?php echo $this->bpFullScriptsSave; ?>

        return true;
    }
    function processAfterSave_<?php echo $this->methodId; ?>(thisButton, responseStatus) {
        
        <?php echo $this->bpFullScriptsAfterSave; ?>

        return true;
    }
</script>

<?php // </editor-fold> ?>
