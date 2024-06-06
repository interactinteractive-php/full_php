<?php

if (!isset($ws)) {
    $ws = new Mdwebservice();
}

if (isset($this->paramRow)) {
   $row = $this->paramRow;
}

$isDetailUserConfig = $isTab = $detailView = $isAggregate = false;
$htmlHeaderCell = $htmlBodyCell = $gridHead = $gridBody = $gridFoot = $gridBodyRow = $gridBodyRowAfter = $gridTabBody = $gridTabContentHeader = $gridTabContentBody = $gridClass = $aggregateClass = '';
$htmlGridFoot = '<td></td>';
$gridRowTypePath = $firstLevelRowArr = $getDtlRowsPopup = array();
$sidebarGroupArr_[$row['id']] = array();
$isMultiRow = ($row['recordtype'] == 'rows') ? true : false;
$isComment = issetParam($row['jsonConfig']['isComment']) ? true : false;

$widgetCode = issetParam($row['widgetCode']);

$availableChartWidgets = array(
    'chart_donut1', 'chart_gauge1', 'chart_zoomable_bubble1', 'chart_radar1', 
    'chart_stacked_bar1', 'card_with_icons1', 'grouped_list1'
);

$availableDetailWidgets = array(
    'detail_withoutlabel1', 'detail_withoutlabel2', 'detail_user_card_001', 
    'detail_attachments', 'detail_attachments2', 'detail_doc_history', 'detail_buttons', 
    'detail_circle_icon', 'detail_circle_file', 'detail_notes', 'detail_file_preview_001', 
    'detail_cart_slider', 'detail_calendar_sidebar'
);

$bpAddonWidgets = array('pfprocessphotowidget', 'pfprocessfilewidget', 'pfprocesscommentwidget');

if ($row['isRequired'] == '1' || $row['isFirstRow'] == '1') {
    $detailView = true;
}

if ($row['recordtype'] == 'rows' && !empty($row['sidebarName'])) {

    $content = '';

} elseif ($row['recordtype'] == 'rows' && $row['dtlTheme'] == '1') {

    $content = (new Mdwebservice())->detailThemeView($this->methodId, $row['dtlTheme'], $row, $this->fillParamData);

} elseif ($row['recordtype'] && in_array($widgetCode, $availableDetailWidgets)) {
    
    $addRowResult = (new Mdwidget())->bpDetailAddRow(
        array(
            'methodId' => $this->methodId, 
            'uniqId'   => $this->uniqId, 
            'row'      => $row
        )
    );
    
    $content = '<div data-section-path="' . $row['code'] . '" class="row mb10">';
        $content .= '<div class="col-md-12" data-bp-detail-container="1">';
        
        $gridBodyData = '';

        if ($this->fillParamData) {
            $row['viewMode'] = 'view';
            $fillRender = $ws->renderFirstLevelDivDtl($this->uniqId, $this->methodId, issetDefaultVal($row['widgetCode'], $row['dtlTheme']), $row, array(), $this->fillParamData);
            $gridBodyData = issetParam($fillRender['gridBodyData']);
        }
        
        $content .= $ws->renderCustomParent($row, $gridBodyData, issetParam($addRowResult['bottomCustomAddRow'])); 
        $content .= '</div>';
    $content .= '</div>';

} elseif ($row['recordtype'] && in_array($widgetCode, $availableChartWidgets)) {
    
    $content = (new Mdwebservice())->detailWidgetByView($this->methodId, $widgetCode, $row, $this->fillParamData);

} elseif (in_array($widgetCode, $bpAddonWidgets)) {
    
    if ($widgetCode == 'pfprocessphotowidget') {
    
        $content = $ws->renderEditModeBpPhotoTab($this->uniqId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId, 'view');

    } elseif ($widgetCode == 'pfprocessfilewidget') {

        $content = $ws->renderEditModeBpFileTab($this->uniqId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId, 'view');

    } else {
        $content = $ws->renderEditModeBpCommentTab($this->uniqId, $this->methodId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId);
    }
    
} elseif (Mdwebservice::$isLogViewMode 
        && $row['code'] == 'kpiDmDtl' 
        && isset($this->fillParamData['templateid']) 
        && isset($this->fillParamData['kpidmdtl'][0])) {
    
    $content = (new Mdform())->viewKpiFromBp($this->fillParamData['templateid'], $this->fillParamData['kpidmdtl']);
    
} else {

    $gridHead = '<tr><th class="rowNumber bp-dtl-rownumber" style="width:30px;">№</th>';
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

        if ($val['META_TYPE_CODE'] == 'boolean' && $isMultiRow) {
            if (empty($val['SIDEBAR_NAME'])) {
                $gridHead .= '<th class="text-left' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                $gridFoot .= '<td class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></td>';
            }
        } else {
            if (empty($val['SIDEBAR_NAME']) && $isMultiRow && $val['RECORD_TYPE'] !== 'row' && $val['RECORD_TYPE'] !== 'rows') {
                $gridHead .= '<th class="text-left ' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                $gridFoot .= '<td class="text-right' . $hideClass . ' ' . $paramRealPath . ' bigdecimalInit" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $foodAmount . '</td>';
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
                    $gridTabContentHeader .= '<a href="#' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                    $gridTabContentHeader .= '</li>';
                    $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" data-section-path="' . $row['code'] . '.' . $val['META_DATA_CODE'] . '">';
                    $gridTabContentBody .= (new Mdwebservice())->buildTreeParamView($this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'row', $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
                    $gridTabContentBody .= '</div>';
                } else {
                    $childRow = (new Mdwebservice())->appendSubRowInProcessView($this->uniqId, $gridClass, $this->methodId, $val);
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
                $gridTabContentBody .= (new Mdwebservice())->buildTreeParamView($this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'rows', $val['ID'], null, '', $arg, '', $val['COLUMN_COUNT']);
                $gridTabContentBody .= '</div>';

            } elseif (empty($val['SIDEBAR_NAME'])) {

                $gridBody .= '<td data-cell-path="' . $row['code'] . '.' . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
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
        $htmlHeaderCell .= '<th class="action ' . ($row['isShowDelete'] === '1' ? '' : 'hide') . '" style="width:' . $actionWidth . 'px;"></th>';
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

    $content = '<div class="row" data-section-path="' . $row['code'] . '">
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
        $renderFirstLevelDtl = (new Mdwebservice())->renderFirstLevelDtlView($this->methodId, $row, $getDtlRowsPopup, $isMultiRow, $this->fillParamData);
        if ($renderFirstLevelDtl) {
            $gridBody = $renderFirstLevelDtl['gridBody'];
            $gridBodyRow = (new Mdwebservice())->renderFirstLevelAddEditDtlRowView($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount'], $this->fillParamData);
            $gridBodyRow .= $renderFirstLevelDtl['gridBodyRow'];
            $gridBodyData = $renderFirstLevelDtl['gridBodyData'];
            $isRowState = $renderFirstLevelDtl['isRowState'];
            
            if (strpos($gridBodyData, 'proccessRenderPopup(') !== false) {
                $gridHead = str_replace('class="action hide"', 'class="action"', $gridHead);
            }
        }
    }

    if (empty($gridBodyRow)) {
        if (!empty($htmlHeaderCell)) {

            $pagingAttributes = ' data-row-id="'.$row['id'].'"';

            if ($isDetailUserConfig) {
                $detailUserConfig = (new Mdwebservice())->getDetailUserConfig($this->methodId, $row['id'], $row['code']);
                $pagingAttributes .= ' data-show-fields="'.$detailUserConfig['showFields'].'" data-hide-fields="'.$detailUserConfig['hideFields'].'"';
            }

            $content .= '<div class="table-scrollable bprocess-table-dtl-div">
                        <style type="text/css">#bp-window-' . $this->methodId . ' .bprocess-table-dtl{table-layout: fixed !important; max-width: '.Mdwebservice::$tableWidth.'px !important;} ' . $gridClass . '</style>
                        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="' . $row['code'] . '" data-popup-ignore-save-button="1"' . $pagingAttributes . '>
                            <thead>
                                '.$gridHead.'
                            </thead>
                            <tbody class="tbody">
                                ' . ($detailView ? $gridBody : '') . $gridBodyData . '
                            </tbody>
                            <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                        </table>    
                    </div>';
        }

    } else {
        $content .= '<div class="table-scrollable table-scrollable-borderless mt0" data-section-path="' . $row['code'] . '">
                    <style type="text/css">' . $gridClass . '</style>
                    <table class="table table-sm table-no-bordered bprocess-table-row">
                        <tbody class="tbody">
                            ' . $gridBodyRow . '
                        </tbody>
                        <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                    </table>    
                </div>';
    }

    $content .= '</div></div>';
}

if (isset($this->isLayoutRender)) {
    echo $content;
}