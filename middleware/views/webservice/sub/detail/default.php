<?php

if (!isset($ws)) {
    $ws = new Mdwebservice();
}

if (isset($this->paramRow)) {
   $row = $this->paramRow;
}

Mdwebservice::$tableWidth = 0;

$isDetailUserConfig = $isTab = $detailView = $isAggregate = false;
$htmlHeaderCell = '';
$htmlBodyCell = '';
$htmlGridFoot = '<td></td>';
$gridBodyRow = '';
$gridBodyRowAfter = '';
$gridTabBody = '';
$gridTabContentHeader = '';
$gridTabContentBody = '';
$gridTabContentHBody = '';
$gridClass = '';
$aggregateClass = '';
$gridRowTypePath = array();
$firstLevelRowArr = array();
$getDtlRowsPopup = array();
$sidebarGroupArr_[$row['id']] = array();
$comboBoolean = Mdcommon::comboBoolean();
$bigGridView = ($row['dtlTheme'] == '13' || $row['dtlTheme'] == '15' || $row['dtlTheme'] == '2') ? true : false;
$sidebarDtlView = ($row['dtlTheme'] == '2') ? false : true;
$isTwoRightColumnFreeze = false;
$isMultiRow = ($row['recordtype'] == 'rows') ? true : false;
$isComment = issetParam($row['jsonConfig']['isComment']) ? true : false;

$widgetCode = issetParam($row['widgetCode']);
$availableWidgets = Mdwidget::bpDetailAvailableWidgets($widgetCode);

if ($row['isRequired'] == '1' || $row['isFirstRow'] == '1') {
    $detailView = true;
}

if ($isMultiRow && !empty($row['sidebarName'])) {
    
    $content = '';
    
} elseif ($isMultiRow && ($row['dtlTheme'] == '14' || $availableWidgets)) {
    
    require BASEPATH . 'middleware/views/webservice/sub/detail/customDetail.php'; 
    
} else {

    $gridHead = '<tr>';
    $gridHeadFilter = '<tr class="bp-filter-row">';
    $gridHead .= '<th class="rowNumber bp-dtl-rownumber" style="width:30px;" datarowspan="0">№</th>';
    $gridHeadFilter .= '<th class="rowNumber bp-dtl-rownumber"></th>';
    $gridFoot = '<tr>';
    $gridFoot .= '<td class="number"></td>';

    $gridBody = '<tr class="bp-detail-row">';
    $gridBody .= '<td class="text-center middle bp-dtl-rownumber"><span>1</span><input type="hidden" name="param[' . $row['code'] . '.mainRowCount][]" value="0"/></td>';

    $ii = 0;
    
    $mergeArr = $secondHeadRow = $columnWidthArr = $mergeColWidth = array();

    foreach ($row['data'] as $ind => $val) {

        $foodAmount = $aggregateClass = $hideClass = '';

        if ($val['COLUMN_AGGREGATE'] != '') {
            $isAggregate = true;
            $foodAmount = '0.00';
            $aggregateClass = 'aggregate-' . $val['COLUMN_AGGREGATE'];
        }

        if ($val['IS_SHOW'] != '1') {
            $hideClass = ' hide';
        }

        $globeColumnName = $this->lang->line($val['META_DATA_NAME']);

        $paramRealPath = $val['NODOT_PARAM_REAL_PATH'];

        if ($isMultiRow && ($val['META_TYPE_CODE'] == 'boolean' || $val['META_TYPE_CODE'] == 'button') && $val['SIDEBAR_NAME'] == '') {
            
            if ($val['IS_SHOW'] == '1' && $val['GROUPING_NAME']) {
                
                if (!isset($mergeArr[$val['GROUPING_NAME']])) {
                    $gridHead .= '<th style="width'.$val['GROUPING_NAME'].'" data-mergegroup-name="1" datacolspan="'.$val['GROUPING_NAME'].'">'.$this->lang->line($val['GROUPING_NAME']).'</th>';
                    $mergeArr[$val['GROUPING_NAME']] = 1;
                } else {
                    $mergeArr[$val['GROUPING_NAME']] += 1;
                }

                $secondHeadRow[$val['GROUPING_NAME']][] = $val;
                
                if ($val['META_TYPE_CODE'] == 'boolean') {
                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">'.$comboBoolean.'</th>';
                } else {
                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"></th>';
                }
                
            } else {
                
                if ($val['META_TYPE_CODE'] == 'boolean') {
                    $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" datarowspan="0">' . $globeColumnName . '</th>';
                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">'.$comboBoolean.'</th>';
                } else {
                    $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" datarowspan="0">' . $globeColumnName . '</th>';
                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"></th>';
                }
            }
            
            $gridFoot .= '<td class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"></td>';

        } elseif ($isMultiRow && $val['SIDEBAR_NAME'] == '' && !$val['RECORD_TYPE']) {
            
            if ($val['IS_SHOW'] == '1' && $val['GROUPING_NAME']) {
                
                if (!isset($mergeArr[$val['GROUPING_NAME']])) {
                    $gridHead .= '<th style="width'.$val['GROUPING_NAME'].'" data-mergegroup-name="1" datacolspan="'.$val['GROUPING_NAME'].'">'.$this->lang->line($val['GROUPING_NAME']).'</th>';
                    $mergeArr[$val['GROUPING_NAME']] = 1;
                } else {
                    $mergeArr[$val['GROUPING_NAME']] += 1;
                }

                $secondHeadRow[$val['GROUPING_NAME']][] = $val;
                
                if ($val['LOOKUP_TYPE'] == 'popup' && $val['LOOKUP_META_DATA_ID'] != '') {
                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">';
                    $gridHeadFilter .= '<div class="dtl-col-popup-code-f"><input type="text" data-type-code="popup-code" data-path-code="' . $val['PARAM_REAL_PATH'] . '" data-lookup-type="popup"/></div>';
                    $gridHeadFilter .= '<div class="dtl-col-popup-name-f"><input type="text" data-type-code="popup-name" data-path-code="' . $val['PARAM_REAL_PATH'] . '" data-lookup-type="popup"/></div>';
                    $gridHeadFilter .= '</th>';
                } else {
                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"><input type="text" data-type-code="' . $val['META_TYPE_CODE'] . '" data-lookup-type="' . $val['LOOKUP_TYPE'] . '" data-path-code="' . $val['PARAM_REAL_PATH'] . '"/></th>';
                }
                
            } else {
                
                if ($val['LOOKUP_TYPE'] == 'popup' && $val['LOOKUP_META_DATA_ID'] != '') {

                    $gridHead .= '<th class="' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '" datarowspan="0">';
                    $gridHead .= '<button type="button" class="bp-head-lookup-sort-code"></button>';
                    $gridHead .= '<span>' . $globeColumnName . '</span>';
                    $gridHead .= '<button type="button" class="bp-head-lookup-sort-name"></button>';
                    $gridHead .= '</th>';

                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">';
                    $gridHeadFilter .= '<div class="dtl-col-popup-code-f"><input type="text" data-type-code="popup-code" data-path-code="' . $val['PARAM_REAL_PATH'] . '" data-lookup-type="popup"/></div>';
                    $gridHeadFilter .= '<div class="dtl-col-popup-name-f"><input type="text" data-type-code="popup-name" data-path-code="' . $val['PARAM_REAL_PATH'] . '" data-lookup-type="popup"/></div>';
                    $gridHeadFilter .= '</th>';
                } else {
                    $gridHead .= '<th class="' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '" datarowspan="0">' . $globeColumnName . '</th>';
                    $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"><input type="text" data-type-code="' . $val['META_TYPE_CODE'] . '" data-lookup-type="' . $val['LOOKUP_TYPE'] . '" data-path-code="' . $val['PARAM_REAL_PATH'] . '"/></th>';
                }
            }

            $gridFoot .= '<td class="text-right' . $hideClass . ' ' . $paramRealPath . ' bigdecimalInit" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" data-mdec="' . $val['FRACTION_RANGE'] . '">' . $foodAmount . '</td>';
        }

        if ($isMultiRow && (!$bigGridView || $row['dtlTheme'] == '2')) {

            $gridClass .= Mdwebservice::fieldDetailStyleClass($val, $paramRealPath, 'bp-window-' . $this->methodId);

            $arg = array(
                'parentRecordType' => 'rows'
            );

            if ($val['RECORD_TYPE'] == 'row') {

                if ($val['IS_BUTTON'] == '1') {

                    ++$ii;
                    $gridTabActive = '';

                    if ($ii === 1) {
                        $gridTabActive = ' active';
                    }

                    $isTab = true;
                    $arg['isTab'] = 'tab';

                    array_push($gridRowTypePath, $val['PARAM_REAL_PATH']);

                    $gridTabContentHeader .= '<li class="nav-item" data-li-path="'.$val['PARAM_REAL_PATH'].'">';
                    $gridTabContentHeader .= '<a href="#' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $globeColumnName . '</a>';
                    $gridTabContentHeader .= '</li>';
                    $gridTabContentBody .= '<div class="tab-pane in' . $gridTabActive . '" id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" data-section-path="' . $val['PARAM_REAL_PATH'] . '">';
                    $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], 'row', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                    $gridTabContentBody .= '</div>';

                } else {

                    $childRow = $ws->appendSubRowInProcess($this->uniqId, $gridClass, $this->methodId, $val);
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
                            $inHtml = Mdwebservice::renderParamControl($this->methodId, $sval, 'param[' . $sval['PARAM_REAL_PATH'] . '][0][]', $sval['PARAM_REAL_PATH'], array());

                            $sidebarDtlRowsContentArr_[$row['id'] . $groupKey][] = array(
                                'input_label_txt' => Form::label($labelAttr),
                                'data_path' => $sval['PARAM_REAL_PATH'],
                                'input_html' => $inHtml
                            );
                            $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'] . $groupKey];
                        }
                    }
                }

            } elseif ($val['RECORD_TYPE'] == 'rows' && $val['IS_SHOW'] == '1') {

                ++$ii;
                $gridTabActive = '';
                if ($ii === 1) {
                    $gridTabActive = ' active';
                }

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

                array_push($gridRowTypePath, $val['PARAM_REAL_PATH']);

                $gridTabContentHeader .= '<li class="nav-item" data-li-path="'.$val['PARAM_REAL_PATH'].'">';
                $gridTabContentHeader .= '<a href="#' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $globeColumnName . '</a>';
                $gridTabContentHeader .= '</li>';
                $gridTabContentBody .= '<div class="tab-pane in' . $gridTabActive . '" id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" data-section-path="' . $val['PARAM_REAL_PATH'] . '">';
                $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], 'rows', $val['ID'], null, '', $arg, '', $val['COLUMN_COUNT']);                                        
                $gridTabContentBody .= '</div>';

            } elseif (empty($val['SIDEBAR_NAME'])) {

                $gridBody .= '<td data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
                $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $val['PARAM_REAL_PATH'] . "][0][]", $val['PARAM_REAL_PATH'], null);
                $gridBody .= '</td>';

                if (issetParam($val['DTL_BUTTON_NAME']) && !isset($dtlBtnName)) {

                    $gridHead .= '<!--dtlBtnName-->';
                    $gridHeadFilter .= '<!--dtlBtnName-->';
                    $gridFoot .= '<!--dtlBtnName-->';
                    $gridBody .= '<!--dtlBtnName-->';

                    $dtlBtnName = $this->lang->line($val['DTL_BUTTON_NAME']);
                }

            } else {
                $sidebarShowRowsDtl_[$row['id']] = true;
                if (!in_array($val['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']])) {
                    $sidebarGroupArr_[$row['id']][$ind] = $val['SIDEBAR_NAME'];
                    $sidebarDtlRowsContentArr_[$row['id'] . $ind] = array();
                }

                $groupKey = array_search($val['SIDEBAR_NAME'], $sidebarGroupArr_[$row['id']]);
                $labelAttr = array(
                    'text' => $globeColumnName,
                    'for' => 'param[' . $val['PARAM_REAL_PATH'] . '][0][]',
                    'data-label-path' => $val['PARAM_REAL_PATH']
                );
                if ($val['IS_REQUIRED'] == '1') {
                    $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                }

                $inHtml = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $val['PARAM_REAL_PATH'], array());

                $sidebarDtlRowsContentArr_[$row['id'] . $groupKey][] = array(
                    'input_label_txt' => Form::label($labelAttr),
                    'data_path' => $val['PARAM_REAL_PATH'],
                    'input_html' => $inHtml
                );
                $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'] . $groupKey];
            }

        } else {

            $gridClass .= Mdwebservice::fieldDetailRowStyleClass($val, 'bp-window-' . $this->methodId);
            $arg = array();

            if (empty($val['SIDEBAR_NAME'])) {

                if ($isMultiRow && !$bigGridView) {
                    $gridBody .= '<td data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput text-center' . $hideClass . '">';
                    $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $val['PARAM_REAL_PATH'] . "][0][]", $val['PARAM_REAL_PATH'], null);
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

                        $gridBodyRowAfter .= '<tr class="bp-detail-row ' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">';

                        if ($val['META_TYPE_CODE'] === 'group' && $val['IS_BUTTON'] == '1') {
                            $gridBodyRowAfter .= '<td class="text-right middle float-left" style="width: 18%">';
                            $labelAttr = array(
                                'text' => $globeColumnName
                            );
                            if ($val['IS_REQUIRED'] == '1') {
                                $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                            }
                            $gridBodyRowAfter .= Form::label($labelAttr);
                            $gridBodyRowAfter .= '</td>';
                            $gridBodyRowAfter .= '<td data-cell-path="' . $val['PARAM_REAL_PATH'] . '" style="width: 72%" class="middle float-left">';
                            $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                            $gridBodyRowAfter .= '</td>';
                        } else {
                            $gridBodyRowAfter .= '<td data-cell-path="' . $val['PARAM_REAL_PATH'] . '" style="width: 100%" class="middle float-left" colspan="2">';
                                $gridBodyRowAfter .= '<fieldset><legend class="text-uppercase font-size-sm font-weight-bold">' . $globeColumnName . '</legend>';
                                    $gridBodyRowAfter .= '<div class="tab-pane in " id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" data-section-path="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '">';                                        
                                    $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                                    $gridBodyRowAfter .= '</div>';
                                $gridBodyRowAfter .= '</fieldset>';
                            $gridBodyRowAfter .= '</td>';
                        }

                        $gridBodyRowAfter .= '</tr>';
                    } elseif ($val['RECORD_TYPE'] === 'row') {
                        $gridBodyRowAfter .= '<tr class="bp-detail-row ' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">';
                        $gridBodyRowAfter .= '<td>';
                        $gridBodyRowAfter .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], issetParamArray($this->fillParamData[strtolower($row['code'])]), '', array(), 1, $val['COLUMN_COUNT']);
                        $gridBodyRowAfter .= '</td>';
                        $gridBodyRowAfter .= '</tr>';
                    } else {
                        array_push($firstLevelRowArr, $val);
                    }
                }
            } else {
                $sidebarShowRowDtl = ($sidebarDtlView) ? true : false;
                $fillParamData = isset($this->fillParamData[strtolower($row['code'])]) ? $this->fillParamData[strtolower($row['code'])] : null;
                if (!in_array($val['SIDEBAR_NAME'], $sidebarDtlRowArr)) {
                    $sidebarDtlRowArr[$ind] = $val['SIDEBAR_NAME'];
                    $sidebarDtlRowContentArr[$ind] = array();
                }

                $groupKey = array_search($val['SIDEBAR_NAME'], $sidebarDtlRowArr);
                $labelAttr = array(
                    'text' => $globeColumnName,
                    'for' => 'param[' . $val['PARAM_REAL_PATH'] . '][0][]',
                    'data-label-path' => $val['PARAM_REAL_PATH']
                );
                if ($val['IS_REQUIRED'] == '1') {
                    $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                }
                $sidebarDtlRowContentArr[$groupKey][] = array(
                    'input_label_txt' => Form::label($labelAttr),
                    'data_path' => $val['PARAM_REAL_PATH'],
                    'input_html' => Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $val['PARAM_REAL_PATH'] . "][0][]", $val['PARAM_REAL_PATH'], $fillParamData)
                );
                $sidebarDtlRowContentArr[$groupKey] = $sidebarDtlRowContentArr[$groupKey];
            }
        }

        $gridBody .=  '</div></div></div>';
        $isDtlTbl = true;
    }
            
    if ($bigGridView) {
        if ($row['dtlTheme'] == '2') {
            $gridBodyRow .= $ws->renderFirstLevelAddEditDtlRowCardView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], '', '', $gridBodyRowAfter, $row);
        } elseif($row['dtlTheme'] == '15') {
            $gridBodyRow .= $ws->renderFirstLevelAddEditDtlRowNtrGridView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], '', $this->uniqId, $gridBodyRowAfter, $row);
        } else {
            $gridBodyRow .= $ws->renderFirstLevelAddEditDtlRowBigGridView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], '', '', $gridBodyRowAfter, $row);
        }
    } else {
        $gridBodyRow .= $ws->renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount']);
        $gridBodyRow .= $gridBodyRowAfter;
    }

    if ($isTab) {

        if (isset($dtlBtnName)) {

            if ($gridRowTypePath) {
                $gridRowTypePath = implode('|', $gridRowTypePath);
                $htmlHeaderCol = '<th style="width:80px;-moz-box-shadow:inset 0 -2px 0px -1px #ddd;-webkit-box-shadow:inset 0 -2px 0px -1px #ddd;box-shadow:inset 0 -2px 0px -1px #ddd;" data-cell-path="' . $gridRowTypePath . '" datarowspan="0">'.$dtlBtnName.'</th>';
            } else {
                $htmlHeaderCol = '<th style="width:40px" data-cell-path="" datarowspan="0">'.$dtlBtnName.'</th>';
            }

            $gridHead = str_replace('<!--dtlBtnName-->', $htmlHeaderCol, $gridHead);
            $gridHeadFilter = str_replace('<!--dtlBtnName-->', '<th data-cell-path="' . $gridRowTypePath . '"></th>', $gridHeadFilter);
            $gridFoot = str_replace('<!--dtlBtnName-->', '<td data-cell-path="' . $gridRowTypePath . '"></td>', $gridFoot);

            $gridBodyCol = '<td data-cell-path="' . $gridRowTypePath . '" class="text-center stretchInput middle">';
            $gridBodyCol .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" title="'.$dtlBtnName.'" data-b-path="' . $gridRowTypePath . '">...</a> ';
            $gridBodyCol .= '<div class="param-tree-container-tab param-tree-container hide">';
            $gridBodyCol .= '<div class="tabbable-line">
                        <ul class="nav nav-tabs">' . $gridTabContentHeader . '</ul>
                        <div class="tab-content">
                            ' . $gridTabContentBody . '
                        </div>
                    </div>';
            $gridBodyCol .= '</div>';
            $gridBodyCol .= '</td>';

            $gridBody = str_replace('<!--dtlBtnName-->', $gridBodyCol, $gridBody);

        } else {

            if ($gridRowTypePath) {
                $gridRowTypePath = implode('|', $gridRowTypePath);
                $htmlHeaderCell .= '<th style="width:70px" data-cell-path="' . $gridRowTypePath . '" datarowspan="0"></th>';
            } else {
                $htmlHeaderCell .= '<th style="width:40px" data-cell-path="" datarowspan="0"></th>';
            }

            $isTwoRightColumnFreeze = true;

            $gridFoot .= '<td data-cell-path="' . $gridRowTypePath . '"></td>';
            $gridBody .= '<td data-cell-path="' . $gridRowTypePath . '" class="text-center stretchInput middle">';
            $gridBody .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" title="Дэлгэрэнгүй" data-b-path="' . $gridRowTypePath . '">...</a> ';
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
    }

    if ($isMultiRow && (!$bigGridView || $row['dtlTheme'] == '2')) {
        $actionWidth = 40;
        if (isset($sidebarShowRowsDtl_[$row['id']])) {
            $actionWidth = 70;
        }

        if ($isComment) {
            $htmlHeaderCell .= '<th class="" style="width:' . $actionWidth . 'px; min-width:' . $actionWidth . 'px;"></th>';
        }
        $htmlHeaderCell .= '<th class="action bp-dtl-action-col' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '" style="width:' . $actionWidth . 'px; min-width:' . $actionWidth . 'px;" datarowspan="0"></th>';
        $htmlBodyCell .= '<td class="text-center stretchInput middle tbl-cell-right-freeze' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '">';

        if (isset($sidebarShowRowsDtl_[$row['id']])) {
            $htmlBodyCell .= '<a href="javascript:;" onclick="proccessRenderPopup(\'div#bp-window-' . $this->methodId . ':visible\', this);" class="btn btn-xs purple-plum bp-btn-sidebar" style="width:21px" title="Popup цонхоор харах"><i class="fa fa-external-link"></i></a>';
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
            $htmlBodyCell .= '<a href="javascript:;" class="btn red btn-xs bp-remove-row" title="' . $this->lang->line('delete_btn') . '"><i class="fa fa-trash"></i></a>';
            $htmlBodyCell .= '</td>';
        }
    }

    $gridBody .= $htmlBodyCell;

    $gridBody .= '</tr>';

    $gridHead .= $htmlHeaderCell;
    $gridHead .= '</tr>';
    
    if ($mergeArr) {

        $gridHead = str_replace('datarowspan="0"', 'rowspan="2"', $gridHead);

        foreach ($mergeArr as $mergeName => $mergeCount) {
            $gridHead = str_replace('datacolspan="'.$mergeName.'"', 'colspan="'.$mergeCount.'"', $gridHead);
        }

        $gridHead .= '<tr data-ignore-leftfreeze="1">';

        foreach ($secondHeadRow as $secondName => $secondRow) {
            foreach ($secondRow as $secondCol) {

                if ($secondCol['COLUMN_WIDTH']) {

                    $style = 'width: '.$secondCol['COLUMN_WIDTH'];
                    $columnWidthArr[$secondCol['PARAM_REAL_PATH']] = $secondCol['COLUMN_WIDTH'];

                } else {
                    
                    $columnWidthArr[$secondCol['PARAM_REAL_PATH']] = '120px';
                    
                    if ($secondCol['LOOKUP_TYPE'] == 'popup') {
                        $columnWidthArr[$secondCol['PARAM_REAL_PATH']] = '282px';
                    }
                    
                    $style = 'width: ' . $columnWidthArr[$secondCol['PARAM_REAL_PATH']];
                }

                if ($secondCol['GROUPING_NAME']) {
                    if (!isset($mergeColWidth[$secondCol['GROUPING_NAME']])) {
                        $mergeColWidth[$secondCol['GROUPING_NAME']] = (int) $columnWidthArr[$secondCol['PARAM_REAL_PATH']];
                    } else {
                        $mergeColWidth[$secondCol['GROUPING_NAME']] += (int) $columnWidthArr[$secondCol['PARAM_REAL_PATH']];
                    }
                }

                $gridHead .= '<th class="bp-head-sort '.$secondCol['NODOT_PARAM_REAL_PATH'].'" data-cell-path="' . $secondCol['PARAM_REAL_PATH'] . '" data-aggregate="' . $secondCol['COLUMN_AGGREGATE'] . '" style="'.$style.'">'.$this->lang->line($secondCol['META_DATA_NAME']).'</th>';
            }
        }

        $gridHead .= '</tr>';

        $headerRow = 2;

        foreach ($mergeColWidth as $mergeName => $mergeWidth) {
            $gridHead = str_replace('width'.$mergeName.'"', 'width: '.$mergeWidth.'px"', $gridHead);
        }
    }
    
    $gridHeadFilter .= $htmlHeaderCell;
    $gridHeadFilter .= '</tr>';
    $gridFoot .= '<td class="' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '"></td>';
    $gridFoot .= '</tr>';

    $content = '<div class="row mb10" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                    <div class="col-md-12" data-bp-detail-container="1">';

    $gridBody = ($bigGridView && $row['dtlTheme'] != '2') ? $gridBodyRow : $gridBody;

    if ($isMultiRow) {

        $bpDtlAddHtml = $this->cache->get('bpDtlAddDtl_'.$this->methodId.'_'.$row['id']);

        if ($bpDtlAddHtml == null) {
            $bpDtlAddHtml = Str::remove_doublewhitespace(str_replace(array("\r\n", "\n", "\r"), '', $gridBody));
            $this->cache->set('bpDtlAddDtl_'.$this->methodId.'_'.$row['id'], $bpDtlAddHtml, Mdwebservice::$expressionCacheTime);
        }

        $content .= '<div class="table-toolbar">
                <div class="row">
                    <div class="col">';

        if ($row['isShowAdd'] === '1') {
            $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow float-left mr5 bp-add-one-row', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('addRow'), 'onclick' => 'bpAddMainRow_' . $this->methodId . '(this, \''.$this->methodId.'\', \'' . $row['id'] . '\');'));
        }

        if ($row['isShowMultiple'] === '1' && $row['groupLookupMeta'] != '' && $row['isShowMultipleMap'] != '0' 
            && $row['dtlTheme'] !== '1' && $row['recordtype'] === 'rows') {
            $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow mr5 float-left bp-add-multi-row', 'value' => '<i class="icon-plus3 font-size-12"></i> Олноор нэмэх', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'\');'));
        }

        if ($row['groupKeyLookupMeta'] != '' && $row['isShowMultipleKeyMap'] != '0') {
            $content .= '<div class="input-group quick-item-process float-left bp-add-ac-row" data-action-path="' . $row['code'] . '">';
            $content .= '<div class="input-group-btn">
                    <button type="button" class="btn default dropdown-toggle" data-toggle="dropdown">'.Lang::lineDefault('by_code', 'Кодоор').'</button>
                    <ul class="dropdown-menu">
                        <li style="display:none"><a href="javascript:;" onclick="bpDetailACModeToggle(this);" data-filter-type="code">'.Lang::lineDefault('by_code', 'Кодоор').'</a></li>
                        <li><a href="javascript:;" onclick="bpDetailACModeToggle(this);" data-filter-type="name">'.Lang::lineDefault('by_name', 'Нэрээр').'</a></li>
                    </ul>
                </div>';
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
                    'data-in-lookup-param' => $row['groupConfigLookupPath'], 
                    'placeholder' => isset($row['groupingName']) ? $this->lang->line($row['groupingName']) : ''
                )
            );
            $content .= '</div>';
            $content .= '<span class="input-group-btn">';
            $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow',
                        'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupKeyLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'autocomplete\', \''.($widgetCode == 'detail_frame_paper_001' ? 'detail_frame_paper_001_basket_function' : '').'\');'));
            $content .= '</span>';
            $content .= '</div>';
        }

        $content .= '</div>';

        $content .= '<div class="col-auto dv-right-tools-btn">';

        if ($row['isSave'] == '1') {
            $content .= Form::button(
                array(
                    'class' => 'btn btn-xs green-meadow bp-group-save',
                    'value' => '<i class="fa fa-save"></i> '.$this->lang->line('save_btn'), 
                    'onclick' => 'bpSaveMainRow(this);'
                )
            );
        }

        if (isset($row['columnUserConfig'])) {
            $isDetailUserConfig = true;
            $content .= '<button type="button" class="btn btn-secondary btn-sm btn-circle default bp-detail-user-option" title="Тохиргоо" onclick="bpDetailUserOption(this, \'' . $this->uniqId . '\');"><i class="fa fa-cog"></i></button>';
        }

        $isExcelExport = issetParam($row['isExcelExport']);
        $isExcelImport = issetParam($row['isExcelImport']);

        if ($isExcelImport == '1') {
            $content .= '<button type="button" class="btn btn-secondary btn-sm btn-circle default ml4 bp-detail-excelimport" onclick="bpDetailExcelImport(this);"><i class="icon-upload7"></i> Excel импорт</button>';
        }

        if ($isExcelExport == '1') {
            $content .= '<button type="button" class="btn btn-secondary btn-sm btn-circle default ml4 bp-detail-excel" onclick="bpDetailExcel(this);"><i class="icon-download7"></i> Excel татах</button>';
        }

        $content .= '<button type="button" class="btn btn-secondary btn-sm btn-circle default ml4 bp-detail-fullscreen" title="Fullscreen" onclick="bpDetailFullScreen(this);" data-action-path="'.$row['code'].'"><i class="fa fa-expand"></i></button>';

        $content .= '</div>';
        $content .= '</div></div>';
    }

    $gridBodyData = '';
    $isEditModeEmptyRow = false;

    if ($this->fillParamData) {

        if ($bigGridView) {
            $detailView = false;

            if ($row['dtlTheme'] == '2') {
                $renderFirstLevelDtl = $ws->renderFirstLevelDtlCardView($this->uniqId, $this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
            } else {
                $renderFirstLevelDtl = $ws->renderFirstLevelDtlBigGridView($this->uniqId, $this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
            }

        } else {
            $renderFirstLevelDtl = $ws->renderFirstLevelDtl($this->uniqId, $this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
        }

        if ($renderFirstLevelDtl) {

            $gridBody = $renderFirstLevelDtl['gridBody'];

            if ($bigGridView) {
                if ($row['dtlTheme'] == '2') {
                    $gridBodyRow = $ws->renderFirstLevelAddEditDtlRowCardView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData, $this->uniqId, '', $row);
                } elseif($row['dtlTheme'] == '15') {
                    $gridBodyRow = $ws->renderFirstLevelAddEditDtlRowNtrGridView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData, $this->uniqId, $gridBodyRowAfter, $row);
                } else {
                    $gridBodyRow = $ws->renderFirstLevelAddEditDtlRowBigGridView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData, $this->uniqId, '', $row);
                }

            } else {
                $gridBodyRow = $ws->renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData);
            }

            $gridBodyRow .= $renderFirstLevelDtl['gridBodyRow'];
            $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
            $isRowState = $renderFirstLevelDtl['isRowState'];

        } else {
            $isEditModeEmptyRow = true;
        }
    }

    if (empty($gridBodyRow) || $bigGridView) {
        if (!empty($htmlHeaderCell) || $bigGridView) {

            $pagingAttributes = '';

            if (isset($this->cacheId) && $this->cacheId !== '' && $row['pagingConfig']) {

                $groupPathLower = strtolower($row['code']);

                if ($isEditModeEmptyRow && $detailView && $gridBody) {

                    $getArrayByDetailHtml = Mdcommon::getArrayProcessDetailParamsArray($this->methodId, $row['id'], $this->uniqId, true, $row['code'], $groupPathLower);

                    if (isset($getArrayByDetailHtml['rowData'])) {
                        $this->fillParamData[$groupPathLower] = array($getArrayByDetailHtml['rowData']);
                    }

                    $this->fillParamData = (new Mdcache())->fillParamDataSplice($this->methodId, $this->cacheId, $this->pagerConfig, $this->fillParamData, 'load_first');
                }

                $pagingAttributes .= ' data-pager="true" data-cacheid="'.$this->cacheId.'" data-pager-default-size="'.$row['pagingConfig']['pagesize'].'"';

                if (isset($this->fillParamData[$groupPathLower.'_total'])) {
                    $aggregateColumns = '';
                    if (isset($this->fillParamData[$groupPathLower.'_aggregatecolumns'])) {
                        $aggregateColumns = http_build_query($this->fillParamData[$groupPathLower.'_aggregatecolumns']);
                    }
                    $pagingAttributes .= ' data-pager-total="'.$this->fillParamData[$groupPathLower.'_total'].'" data-pager-aggregate="'.$aggregateColumns.'"';
                }
            }

            if (isset($row['detailModifyMode'])) {
                $isDetailModifyMode = true;
                $pagingAttributes .= ' data-detailmodify-mode="'.$row['detailModifyMode'].'"';
            }

            if ($isDetailUserConfig) {
                $detailUserConfig = $ws->getDetailUserConfig($this->methodId, $row['id'], $row['code']);
                $pagingAttributes .= ' data-show-fields="'.$detailUserConfig['showFields'].'" data-hide-fields="'.$detailUserConfig['hideFields'].'"';
            }

            if ($bigGridView) {
                $class = ($row['dtlTheme'] == '2') ? 'class="tbody row w-100"' : 'class="tbody"';
                $content .= '<div class="table-scrollable table-scrollable-borderless mt0" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                            <style type="text/css">' . $gridClass . '</style>                                        
                                <table class="table table-sm table-no-bordered bprocess-table-dtl cool-row '.($row['dtlTheme'] == '15' ? 'ntrGridView' : '').'" data-table-path="' . $row['code'] . '" data-table-path-lower="' . Str::lower($row['code']) . '" data-row-id="'.$row['id'].'">
                                    <tbody '. $class .'>
                                        ' . /* is required - one row */($detailView ? $gridBodyData : '') . $gridBodyData . '
                                    </tbody>
                                </table>    
                            </div>
                        </div>
                    </div>';
            } else {
                $content .= '<div data-parent-path="'.$row['code'].'" class="bp-overflow-xy-auto">
                                <style type="text/css">'.(Mdwebservice::$tablePercentWidth ? '' : '#bp-window-' . $this->methodId . ' .bprocess-table-dtl[data-table-path="' . $row['code'] . '"]{table-layout: fixed !important; max-width: ' . Mdwebservice::$tableWidth . 'px !important;} '). $gridClass . '</style>
                                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1'.($isTwoRightColumnFreeze ? ' bp-dtl-tworightcolfreeze' : '').'" data-table-path="' . $row['code'] . '" data-table-path-lower="' . Str::lower($row['code']) . '" data-row-id="'.$row['id'].'" data-lookupmeta="' . $row['groupLookupMeta'] . '"' . $pagingAttributes . '>
                                    <thead>
                                        ' . $gridHead . $gridHeadFilter . '
                                    </thead>
                                    <tbody class="tbody">
                                        ' . /* is required - one row */($detailView ? $gridBody : '') . $gridBodyData . '
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
            $content .= Form::button(array('class' => 'btn btn-xs green-meadow float-right', 'value' => '<i class="icon-checkmark-circle2"></i> '.$this->lang->line('save_btn'), 'onclick' => 'bpSaveMainRow(this);'));
        }
        $content .= '<div class="table-scrollable table-scrollable-borderless mt0" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                    <style type="text/css">' . $gridClass . '</style>
                    <table class="table table-sm table-no-bordered bprocess-table-row">
                        <tbody>' . $gridBodyRow . '</tbody>
                        <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                    </table>    
                </div>
            </div>
        </div>';
    }
    
}

$frameWidgets = (new Mdwidget())->bpDetailFrameWidgets($widgetCode, $this->methodId, $row, $this->fillParamData);
    
if ($frameWidgets) {
    $content = str_replace('{content}', $content, $frameWidgets);
    if (isset($addRowResult['customLocationAddBtn'])) {
        $content = str_replace('{customLocationAddBtn}', $addRowResult['customLocationAddBtn'], $content);
    }
}

if (isset($this->isLayoutRender)) {
    echo $content;
}