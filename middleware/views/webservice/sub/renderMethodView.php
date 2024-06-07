<?php
$processsMainContentClassBegin = $processsMainContentClassEnd = $processsDialogContentClassBegin = $processsDialogContentClassEnd = $dialogProcessLeftBanner = $mainProcessLeftBanner = '';
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
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 bp-btn-print '.(($this->isEditMode == true) ? '' : 'disabled').'" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . ($this->lang->line('printTemplate'.$this->methodId) == 'printTemplate'.$this->methodId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->methodId)) . '</button>';
    }
    $mainProcessBtnBar .= '<div class="float-right">
            ' . Mdcommon::helpContentButton([
                'contentId' => $this->helpContentId, 
                'sourceId' => $this->methodId, 
                'fromType' => 'meta_process'
            ]) . Form::button(
                array(
                    'class' => 'btn btn-sm btn-circle purple-plum ml5',
                    'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                    'onclick' => 'printProcess(this);'
                ), isset($this->isPrintView) ? $this->isPrintView : false
            ) . $reportPrint .
            '
        </div>
        <div class="clearfix"></div>
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

    $mainProcessLeftBanner = '';
    if ($dialogProcessLeftBanner != '') {
        $processsDialogContentClassBegin = '<div class="processs-main-content">';
        $processsDialogContentClassEnd = '</div>';
        $isBanner = true;
    }
}
?>
<div class="xs-form bp-banner-container bp-view-process <?php echo $this->methodRow['SKIN']; ?>" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
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
            $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['processname']) . '\', \'\', \'changeHardAssign\', \'\', \''. $this->uniqId .'\', \''. $this->methodId .'\', undefined, undefined, \'' . $wfmstatusRow['wfmstatusprocessid'] . '\', \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined, undefined, undefined, \'' . $isCallNextFunction .'\', \'' . $wfmstatusRow['isformnotsubmit'] . '\', \'' . $wfmstatusRow['usedescriptionwindow'] . '\', \'' . issetParam($wfmstatusRow['isnotconfirm']) . '\');"';
            $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' class="btn btn-sm purple-plum btn-circle" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'">'. $wfmstatusRow['processname'] .'</button> ';
        }

        echo $singleMenuHtml; 
        echo '<hr class="bp-top-hr"/>';
    }
    
    echo $mainProcessBtnBar;
    echo issetParam($this->processToolbarCommand);

    echo $this->bpTab['tabStart'];

    echo $dialogProcessLeftBanner;
    echo $processsDialogContentClassBegin;
    ?><!-- banner -->
    <div class="row">
    <div class="col-md-12 center-sidebar">  
        <?php 
        echo $mainProcessLeftBanner; /* banner */
        echo $processsMainContentClassBegin; 
        
        if (Mdwebservice::$isLogViewMode) {
            echo '<div class="text-right">
                <input type="checkbox" data-off-color="warning" data-on-color="info" data-on-text="Шинэ" data-size="small" data-off-text="Хуучин" class="form-check-input-switch-bplog_'.$this->methodId.' notuniform" checked>
            </div>';
        }
        
        $isDtlTbl = $sidebarShow = $sidebarShowRowDtl = false;
        
        if ($this->paramList) {
            
            $tabHead = $tabHeaderHead = $tabContent = $tabHeaderContent = $sidebarContent = $sidebarGroup = $sidebarGroupMetaRender = $sidebarGroupMetaRowsRender = '';
            $tabNameArr = $tabHeaderArr = $sidebarHeaderArr = $sidebarDtlRowArr = $getDtlRowsPopup = array();           
            $tabActiveFirst = 0;
            
            foreach ($this->paramList as $k => $row) {
                
                if ($row['type'] == 'header') {
                    if (isset($row['data'])) {
                        
                        $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                        
                        if (count($buildData['featureParam']) > 0) {
                            echo (new Mdwebservice())->renderViewFeatureParam($this->methodId, $buildData['featureParam'], $this->fillParamData, $this->isDialog);
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
                        <?php                            
                    }
                    
                } elseif ($row['type'] == 'detail' && $row['isShow'] == '1' && isset($row['data'])) {
                    
                    // start default detail

                    require BASEPATH . 'middleware/views/webservice/sub/detail/defaultView.php'; 

                    // end default detail

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
                                                $tabHeaderContent .= Mdwebservice::renderViewParamControl($this->methodId, $subrow, 'param[' . $subrow['META_DATA_CODE'] . ']', $subrow['META_DATA_CODE'], $this->fillParamData);
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
                        echo '<div data-section-path="' . $row['code'] . '">
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
        ?>
        <div id="bprocessCoreParam">
            <?php
            echo Form::hidden(array('name' => 'methodId', 'value' => $this->methodId)); 
            echo Form::hidden(array('name' => 'processSubType', 'value' => $this->processSubType));
            echo Form::hidden(array('name' => 'create', 'value' => ($this->processActionType == 'insert' ? '1' : '0')));
            echo Form::hidden(array('name' => 'responseType', 'value' => $this->responseType));
            echo Form::hidden(array('name' => 'wfmStatusParams', 'value' => isset($this->newStatusParams) ? $this->newStatusParams : '')); 
            echo Form::hidden(array('name' => 'wfmStringRowParams', 'value' => isset($arrayToStrParam) ? $arrayToStrParam : '')); 
            echo Form::hidden(array('id' => 'openParams', 'value' => $this->openParams));
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
<?php require getBasePath() . 'middleware/views/webservice/sub/script/view.php'; ?>
