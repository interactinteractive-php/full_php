<?php
$gridHead = '<tr>';
$gridHeadFilter = '<tr class="bp-filter-row">';
$gridHead .= '<th class="rowNumber" style="width:30px;" rowspan="2">№</th>';
$gridHeadFilter .= '<th></th>';
$gridFoot = '<tr>';
$gridFoot .= '<td class="number"></td>';
$gridBody = '';

$gridBody .= '<tr>';
$gridBody .= '<td class="text-center middle"><span>1</span><input type="hidden" name="param[' . $row['code'] . '.mainRowCount][]"/></td>';

$ii = 0;

$pathRowId = null;
$pivotPath = '';
$pivotPathArr = array('EXT_PRICE_COMPARISON_DTL.COMPARISON_DTL', 'EXT_COMPARISON_KPI.EXT_COMPARISON_KPI_DTL');

foreach ($row['data'] as $ind => $val) {
    
    if (in_array($val['PARAM_REAL_PATH'], $pivotPathArr)) {
        $pathRowId = $val['ID'];     
        $pivotPath = $val['PARAM_REAL_PATH'];
        continue;
    }

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

    if (($val['META_TYPE_CODE'] == 'boolean' || $val['META_TYPE_CODE'] == 'button') && $isMultiRow && $val['SIDEBAR_NAME'] == '') {

        if ($val['META_TYPE_CODE'] == 'boolean') {
            $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" rowspan="2">' . $globeColumnName . '</th>';
            $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">'.$comboBoolean.'</th>';
        } else {
            $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" rowspan="2">' . $globeColumnName . '</th>';
            $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"></th>';
        }
        
        $gridFoot .= '<td class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"></td>';

    } elseif ($val['SIDEBAR_NAME'] == '' && $isMultiRow && $val['RECORD_TYPE'] !== 'row' && $val['RECORD_TYPE'] !== 'rows') {

        if ($val['LOOKUP_TYPE'] == 'popup' && $val['LOOKUP_META_DATA_ID'] != '') {

            $gridHead .= '<th class="' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '" rowspan="2">';
            $gridHead .= '<button type="button" class="bp-head-lookup-sort-code"></button>';
            $gridHead .= '<span>' . $globeColumnName . '</span>';
            $gridHead .= '<button type="button" class="bp-head-lookup-sort-name"></button>';
            $gridHead .= '</th>';

            $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">';
            $gridHeadFilter .= '<div class="dtl-col-popup-code-f"><input type="text" data-type-code="popup-code" data-path-code="' . $val['PARAM_REAL_PATH'] . '"/></div>';
            $gridHeadFilter .= '<div class="dtl-col-popup-name-f"><input type="text" data-type-code="popup-name" data-path-code="' . $val['PARAM_REAL_PATH'] . '"/></div>';
            $gridHeadFilter .= '</th>';
        } else {
            $gridHead .= '<th class="' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $val['PARAM_REAL_PATH'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '" rowspan="2">' . $globeColumnName . '</th>';
            $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $val['PARAM_REAL_PATH'] . '"><input type="text" data-type-code="' . $val['META_TYPE_CODE'] . '" data-path-code="' . $val['PARAM_REAL_PATH'] . '"/></th>';
        }

        $gridFoot .= '<td class="text-right' . $hideClass . ' ' . $paramRealPath . ' bigdecimalInit" data-cell-path="' . $val['PARAM_REAL_PATH'] . '">' . $foodAmount . '</td>';
    }

    // start $isMultiRow

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
            $gridTabContentBody .= (new Mdwebservice())->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], 'row', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
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
        $gridTabContentBody .= (new Mdwebservice())->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], 'rows', $val['ID'], null, '', $arg, '', $val['COLUMN_COUNT']);                                        
        $gridTabContentBody .= '</div>';

    } elseif (empty($val['SIDEBAR_NAME'])) {

        $gridBody .= '<td data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
        $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $val['PARAM_REAL_PATH'] . "][0][]", $val['PARAM_REAL_PATH'], null);
        $gridBody .= '</td>';

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
        $inHtml = Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $val['PARAM_REAL_PATH'] . "][0][]", $val['PARAM_REAL_PATH'], array());
        $sidebarDtlRowsContentArr_[$row['id'] . $groupKey][] = array(
            'input_label_txt' => Form::label($labelAttr),
            'data_path' => $val['PARAM_REAL_PATH'],
            'input_html' => $inHtml
        );
        $sidebarDtlRowsContentArr_[$row['id']][$groupKey] = $sidebarDtlRowsContentArr_[$row['id'] . $groupKey];
    }
        
    // end $isMultiRow

    $gridBody .=  '</div></div></div>';
    
    $isDtlTbl = true;
}

$gridBodyRow .= Mdwebservice::renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount']);
$gridBodyRow .= $gridBodyRowAfter;

if ($isTab) {
    
    if ($gridRowTypePath) {
        $gridRowTypePath = implode('|', $gridRowTypePath);
        $htmlHeaderCell .= '<th style="width:70px" data-cell-path="' . $gridRowTypePath . '" rowspan="3"></th>';
    } else {
        $htmlHeaderCell .= '<th style="width:40px" data-cell-path="" rowspan="2"></th>';
    }

    $gridFoot .= '<td data-cell-path="' . $gridRowTypePath . '"></td>';
    $gridBody .= '<td data-cell-path="' . $gridRowTypePath . '" class="text-center stretchInput middle">';
    $gridBody .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="Дэлгэрэнгүй" data-b-path="' . $gridRowTypePath . '">...</a> ';
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

if ($isMultiRow && isset($sidebarShowRowsDtl_[$row['id']])) {

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

$gridBody .= $htmlBodyCell;

$gridBody .= '</tr>';

$pt = new Mdpivot();

if (!isset($pivotColumns)) {
    $pivotColumns = $pt->processPivotColumns($this->fillParamData['rfqid']);
}
$pivotCells = '';

if ($pivotColumns) {

    $showColumns = $pt->bpInputParamsData($this->methodId, $pathRowId, null);
    $showColumnsCount = 0;
    
    foreach ($showColumns as $cl) {
        if ($cl['IS_SHOW'] == '1' && $cl['LOWER_PARAM_NAME'] != 'paymentdtl') {
            $showColumnsCount++;
        }
    }
    
    $colIndex = $showColumnsCount - 1;
    
    foreach ($pivotColumns as $p => $pivotRow) {
        
        $htmlHeaderCell .= '<th data-header-pivot-num="'.$p.'" colspan="'.$showColumnsCount.'" class="pv-border-right-bold" style="{width}" data-cols-path="'.$pivotPath.'" data-key-id="'.$pivotRow['supplierid'].'">'.$pivotRow['suppliername'].'</th>';
        $cellWidth = 0;
        
        foreach ($showColumns as $c => $colRow) {
            
            if ($colRow['IS_SHOW'] == '1' && $colRow['LOWER_PARAM_NAME'] != 'paymentdtl') {
            
                $foodAmount = $aggregateClass = '';
                $paramRealPath = $colRow['NODOT_PARAM_REAL_PATH'];
                $colStyle = ($colIndex == $c) ? ' pv-border-right-bold' : '';
                
                if ($colRow['COLUMN_AGGREGATE'] != '') {
                    $isAggregate = true;
                    $foodAmount = '0.00';
                    $aggregateClass = ' aggregate-' . $colRow['COLUMN_AGGREGATE'];
                }

                $pivotCells .= '<th class="'.$paramRealPath.$colStyle.'" data-cell-path="'.$colRow['PARAM_REAL_PATH'].'" data-aggregate="' . $colRow['COLUMN_AGGREGATE'] . '" data-pivot-colcode="'.$p.'">'.$this->lang->line($colRow['META_DATA_NAME']).'</th>';

                if ($val['LOOKUP_TYPE'] == 'popup' && $val['LOOKUP_META_DATA_ID'] != '') {

                    $gridHeadFilter .= '<th data-cell-path="' . $colRow['PARAM_REAL_PATH'] . '" class="'.$colStyle.'">';
                    $gridHeadFilter .= '<div class="dtl-col-popup-code-f"><input type="text" data-type-code="popup-code" data-path-code="' . $colRow['PARAM_REAL_PATH'] . '"/></div>';
                    $gridHeadFilter .= '<div class="dtl-col-popup-name-f"><input type="text" data-type-code="popup-name" data-path-code="' . $colRow['PARAM_REAL_PATH'] . '"/></div>';
                    $gridHeadFilter .= '</th>';

                } else {
                    $gridHeadFilter .= '<th data-cell-path="' . $colRow['PARAM_REAL_PATH'] . '" class="'.$colStyle.'"><input type="text" data-type-code="' . $colRow['META_TYPE_CODE'] . '" data-path-code="' . $colRow['PARAM_REAL_PATH'] . '"/></th>';

                    if ($colRow['COLUMN_AGGREGATE'] != '') {
                        $foodAmount = '0.00';
                    }
                }

                $gridFoot .= '<td class="text-right ' . $paramRealPath .$colStyle. ' bigdecimalInit" data-cell-path="' . $colRow['PARAM_REAL_PATH'] . '" data-pivot-colcode="'.$p.'">' . $foodAmount . '</td>';
                
                $gridClass .= Mdwebservice::fieldDetailStyleClass($colRow, $paramRealPath, 'bp-window-' . $this->methodId);
                $cellWidth += Mdwebservice::fieldDetailStyleWidth($colRow);
            }
        }
        
        $htmlHeaderCell = str_replace('{width}', 'width: '.$cellWidth.'px; min-width: '.$cellWidth.'px;', $htmlHeaderCell);
    }
    
    if ($row['isShowDelete'] === '1') {
        $htmlHeaderCell .= '<th rowspan="3" class="action" style="width: 40px;max-width: 40px;min-width: 40px;"></th>';
        $gridFoot .= '<td></td>';
    }
}

$gridHead .= $htmlHeaderCell;
$gridHead .= '</tr>';

$gridHead .= '<tr>'.$pivotCells.'</tr>';

//$gridHeadFilter .= $htmlHeaderCell;
$gridHeadFilter .= '</tr>';
$gridFoot .= '</tr>';

$content = '<div class="row mb10" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                <div class="col-md-12" data-bp-detail-container="1">';

if ($isMultiRow) {

    $content .= '<div class="table-toolbar">
            <div class="row">
                <div class="col-md-8">';
    
    if ($row['isShowAdd'] == '1') {
        $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow float-left mr5 bp-add-one-row', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('addRow'), 'onclick' => 'bpAddMainRow_' . $this->methodId . '(this, \''.$this->methodId.'\', \'' . $row['id'] . '\');'));
    }
    
    if ($row['groupKeyLookupMeta'] != '' && $row['isShowMultipleKeyMap'] != '0') {
        $content .= '<div class="input-group quick-item-process float-left bp-add-ac-row" data-action-path="' . $row['code'] . '">';
        $content .= '<div class="input-group-btn">
                <button type="button" class="btn default dropdown-toggle" data-toggle="dropdown">Кодоор</button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="bpDetailACModeToggle(this);">Нэрээр</a></li>
                </ul>
            </div>';
        $content .= '<div class="input-icon">';
        $content .= '<i class="fa fa-search"></i>';
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
                    'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupKeyLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'autocomplete\');'));
        $content .= '</span>';
        $content .= '</div>';
    }

    $content .= '<div class="clearfix w-100"></div>';
    $content .= '</div>';

    $content .= '<div class="col-md-4 text-right dv-right-tools-btn">';

    $content .= '<button type="button" class="btn btn-secondary btn-sm btn-circle default ml4 bp-detail-fullscreen" title="Fullscreen" onclick="bpDetailFullScreen(this);" data-action-path="'.$row['code'].'"><i class="fa fa-expand"></i></button>';

    if (isset($row['isExcelExport']) && $row['isExcelExport'] == '1') {
        $content .= '<button type="button" class="btn btn-secondary btn-sm btn-circle default ml4 bp-detail-excel" title="Excel татах" onclick="bpDetailExcel(this);"><i class="fa fa-file-excel-o"></i></button>';
    }

    $content .= '</div>';
    $content .= '</div></div>';
}

$gridBodyData = $gridBodyRow = '';

if ($this->fillParamData) {
    $renderFirstLevelDtl = $ws->renderFirstLevelPivotDtl($this->uniqId, $this->methodId, $pivotColumns, $showColumns, $pivotPath, $colIndex, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
    if ($renderFirstLevelDtl) {
        $gridBody = $renderFirstLevelDtl['gridBody'];
        $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
        $isRowState = $renderFirstLevelDtl['isRowState'];
    }
}

if (empty($gridBodyRow)) {
    
    if (!empty($htmlHeaderCell)) {

        $pagingAttributes = ' data-row-id="'.$row['id'].'"';

        $content .= '<div data-parent-path="'.$row['code'].'" class="bp-overflow-xy-auto">
                        <style type="text/css">#bp-window-' . $this->methodId . ' .bprocess-table-dtl[data-table-path="' . $row['code'] . '"]{table-layout: fixed !important;} ' . $gridClass . ' .pv-border-right-bold{border-right: 2px #000 solid!important}</style>
                        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="' . $row['code'] . '" data-dtl-id="'.$pathRowId.'" data-pivot-path="'.$pivotPath.'" data-pivot-dtl="1"' . $pagingAttributes . '>
                            <thead>
                                ' . $gridHead . $gridHeadFilter . '
                            </thead>
                            <tbody class="tbody">
                                ' . ($detialView ? $gridBody : '') . $gridBodyData . '
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
    echo '<div data-section-path="' . $row['code'] . '" '.($row['columnWidth'] ? 'class="float-left" style="width:'.$row['columnWidth'].'"' : '').' data-isclear="' . $row['isRefresh'] . '">
        <fieldset class="collapsible">
            <legend>' . $this->lang->line($row['name']) . '</legend>
            ' . $content . ' 
        </fieldset>
    </div>';
}