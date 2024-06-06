<?php

if ($row['widgetCode'] == 'pfprocessphotowidget') {
    
    if ($this->sourceId) {
        $content = $ws->renderEditModeBpPhotoTab($this->uniqId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId, 'update');
    } else {
        $content = $ws->renderAddModeBpPhotoTab($this->uniqId, $this->methodRow['REF_META_GROUP_ID']);
    }
    
} elseif ($row['widgetCode'] == 'pfprocessfilewidget') {
    
    if ($this->sourceId) {
        $content = $ws->renderEditModeBpFileTab($this->uniqId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId, 'update');
    } else {
        $content = $ws->renderAddModeBpFileTab($this->uniqId, $this->methodRow['REF_META_GROUP_ID']);
    }
    
} elseif ($row['widgetCode'] == 'pfprocesscommentwidget') {
    
    if ($this->sourceId) {
        $content = $ws->renderEditModeBpCommentTab($this->uniqId, $this->methodId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId);
    } else {
        $content = $ws->renderAddModeBpCommentTab($this->uniqId);
    }
    
} else {

    $addRow = issetParam($availableWidgets['topAddRow']);

    $content = '<div data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '" class="row mb10">';
    $content .= '<div class="col-md-12" data-bp-detail-container="1">';

    $addRowResult = (new Mdwidget())->bpDetailAddRow([
        'methodId' => $this->methodId, 
        'uniqId'   => $this->uniqId, 
        'row'      => $row
    ]);

    if ($addRow) {

        $content .= '<div class="table-toolbar">
                        <div class="row">
                            <div class="col">';

        if ($row['isShowAdd'] == '1' && issetParam($availableWidgets['topAddOneRow'])) {
            $content .= Form::button(['data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow float-left mr5 bp-add-one-row', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('addRow'), 'onclick' => 'bpAddMainRow_' . $this->methodId . '(this, \''.$this->methodId.'\', \'' . $row['id'] . '\');']);
            $isDtlTbl = true;
        }

        if ($row['groupKeyLookupMeta'] != '' && $row['isShowMultipleKeyMap'] != '0') {
            $content .= '<div class="input-group quick-item-process float-left bp-add-ac-row" data-action-path="' . $row['code'] . '">';
            $content .= '<div class="input-group-btn">
                    <button type="button" class="btn default dropdown-toggle" data-toggle="dropdown">'.Lang::lineDefault('by_code', 'Кодоор').'</button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:;" onclick="bpDetailACModeToggle(this);">'.Lang::lineDefault('by_name', 'Нэрээр').'</a></li>
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
                        'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupKeyLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'autocomplete\');'));
            $content .= '</span>';
            $content .= '</div>';
        }

        $content .= '<div class="clearfix w-100"></div>';
        $content .= '</div>';

        $content .= '<div class="col-auto text-right">';
        $content .= '</div>';

        $content .= '</div>';
        $content .= '</div>';

    } else {

        if ($addRowResult['topCustomAddRow']) {
            $content .= $addRowResult['topCustomAddRow'];
        }
    }

    $bpDtlAddHtml = $this->cache->get('bpDtlAddDtl_'.$this->methodId.'_'.$row['id']);

    if ($bpDtlAddHtml == null) {
        $gridBody = $ws->bpCustomDetail($this->methodId, $this->uniqId, $row, [['tempPath' => '']]);
        $bpDtlAddHtml = Str::remove_doublewhitespace(str_replace(["\r\n", "\n", "\r"], '', $gridBody));
        $this->cache->set('bpDtlAddDtl_'.$this->methodId.'_'.$row['id'], $bpDtlAddHtml, Mdwebservice::$expressionCacheTime);
    }    

    $gridBodyData = '';

    if ($this->fillParamData) {
        $fillRender = $ws->renderFirstLevelDivDtl($this->uniqId, $this->methodId, issetDefaultVal($row['widgetCode'], $row['dtlTheme']), $row, [], $this->fillParamData);
        $gridBodyData = issetParam($fillRender['gridBodyData']);
    }

    $content .= $ws->renderCustomParent($row, $gridBodyData, issetParam($addRowResult['bottomCustomAddRow']));

    $content .= '</div></div>';
}