<?php
$ws = new Mdwebservice();
$processsMainContentClassBegin = $processsMainContentClassEnd =  $processsDialogContentClassBegin = '';
$processsDialogContentClassEnd = $dialogProcessLeftBanner = $mainProcessLeftBanner = '';

if ($this->isDialog == false) {
    if ($this->mainBpTab['ticket'] == '0') {
        $mainProcessBtnBar = '<div class="meta-toolbar meta-toolbar-' . $this->methodId . '">';
        if (Config::getFromCache('CONFIG_MULTI_TAB')) {
            if ($this->isHeaderName) {
                $mainProcessBtnBar .= html_tag(
                    'a',
                    array(
                        'href' => 'javascript:;',
                        'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                        'onclick' => 'backFormMeta();'
                    ),
                    '<i class="icon-arrow-left7"></i>',
                    true
                );
                $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
                $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            } else {
                $mainProcessBtnBar .= html_tag(
                    'a',
                    array(
                        'href' => 'javascript:;',
                        'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10',
                        'onclick' => 'backFirstContent(this);',
                        'data-dm-id' => $this->dmMetaDataId
                    ),
                    '<i class="icon-arrow-left7"></i>',
                    ($this->dmMetaDataId ? true : false)
                );
                $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            }
        } else {
            if ($this->isHeaderName) {
                $mainProcessBtnBar .= html_tag(
                    'a',
                    array(
                        'href' => 'javascript:;',
                        'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                        'onclick' => 'backFormMeta();'
                    ),
                    '<i class="icon-arrow-left7"></i>',
                    true
                );
                $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
                $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            } else {
                $mainProcessBtnBar .= html_tag(
                    'a',
                    array(
                        'href' => 'javascript:;',
                        'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10',
                        'onclick' => 'backFirstContent(this);',
                        'data-dm-id' => $this->dmMetaDataId
                    ),
                    '<i class="icon-arrow-left7"></i>',
                    true
                );
                $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            }
        }
    } else {
        $mainProcessBtnBar = '';
    }

    echo $this->mainBpTab['tabStart'];

    $reportPrint = '';

    if ($this->isPrint) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 ' . (($this->isEditMode == true) ? '' : 'disabled') . '" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> Хэвлэх</button>';
    }

    $confirmType = '1';
    if (isset($this->bpTemplateRow) && isset($this->bpTemplateRow['confirmType']) && $this->bpTemplateRow['confirmType'] == '2') {
        $confirmType = '2';
    }

    if ($this->mainBpTab['ticket'] == '0') {
        $mainProcessBtnBar .= '<div class="ml-auto">
                ' . Mdcommon::helpContentButton([
                'contentId' => $this->helpContentId, 
                'sourceId' => $this->methodId, 
                'fromType' => 'meta_process'
            ]) . html_tag(
            'button',
            array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success mr5',
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ', \'saveadd\');',
                'data-dm-id' => $this->dmMetaDataId
            ),
            '<i class="fa fa-save"></i> ' . $this->runMode,
            (!$this->isEditMode) ? (($this->runMode) ? true : false) : false
        ) . html_tag(
            'button',
            array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success mr5',
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ', \'saveprint\');',
                'data-get-process-id' => (isset($this->getProcessId) ? $this->getProcessId : ''),
                'data-dm-id' => $this->dmMetaDataId
            ),
            '<i class="fa fa-print"></i> ' . Lang::line('saveandprint'),
            $this->isSavePrint
        ) . html_tag(
            'button',
            array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success bpMainSaveButton',
                'onclick' => 'runBusinessProcess_v1(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ', undefined, qrGenerateProcessAfterSave);',
                'data-confirmtype' => $confirmType,
                'data-dm-id' => $this->dmMetaDataId
            ),
            '<i class="fa fa-save"></i> ' . $this->processActionBtn
        ) . Form::button(
            array(
                'class' => 'btn btn-sm btn-circle purple-plum ml5',
                'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                'onclick' => 'printProcess(this);'
            ),
            isset($this->isPrintView) ? $this->isPrintView : false
        ) . $reportPrint .
            '
            </div>
        </div>
        <div class="hide mt10" id="boot-fileinput-error-wrap"></div>
        <div class="clearfix w-100"></div>';
    }

    $mainProcessLeftBanner = $ws->showBanner($this->methodId, 'left', $this->isBanner);
    if ($mainProcessLeftBanner != '') {
        $processsMainContentClassBegin = '<div class="processs-main-content">';
        $processsMainContentClassEnd = '</div>';
    }
} else {

    $mainProcessBtnBar = '';
    $mainProcessLeftBanner = '';

    $dialogProcessLeftBanner = $ws->showBanner($this->methodId, 'left', $this->isBanner);

    if ($dialogProcessLeftBanner != '') {
        $processsDialogContentClassBegin = '<div class="processs-main-content">';
        $processsDialogContentClassEnd = '</div>';
    }
}
?>
<script type="text/javascript">
    var singleClick_<?php echo $this->methodId; ?> = 0;
    var selectedJTreePathId_<?php echo $this->methodId; ?> = [],
        selectedJTreePathText_<?php echo $this->methodId; ?> = [],
        choosenJTreePathText_<?php echo $this->methodId; ?> = [],
        choosenJTreePathId_<?php echo $this->methodId; ?> = [],
        selectedJTreePath_<?php echo $this->methodId; ?> = false;

    $(document).keyup(function(e) {
        if (e.which == 27) {
            closeselectedJtree_<?php echo $this->methodId ?>();
        }
    });

    $(document).click(function(e) {
        if (!e.hasOwnProperty('isTrigger') && e.target.className.indexOf('selectionNodeList_search_') === -1 && e.target.className.indexOf('jstree-icon') === -1) {
            closeselectedJtree_<?php echo $this->methodId ?>();
        }
    });

    function changeSelector_<?php echo $this->methodId ?>(_this, $tagName, groupPath, mainSelector, selectorOption, selectorOption2, selSel, selInp, splitPaths, splitDataPaths, $thisDataPathLastIndexVal, $thisNameLastIndexVal, cindex, _thisName, thisval, isChange, isready) {
        if ($tagName === 'select') {
            _this.attr('name', _this.attr('data-path'));
        }
        
        /*        
        
        bp_window_<?php echo $this->methodId; ?>.find('table[data-table-path="'+ $tagSelector.attr('data-lookup-path') +'"]').each(function () {
            
        });

        */

        if (typeof splitPaths[2] === 'undefined') {
            var spPath = groupPath.split('&');
            if (typeof spPath[1] !== 'undefined') {
                groupPath = spPath[0].replace('\\', '');
            }

            mainSelector = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + groupPath + '"]');
            if ($tagName === 'select') {
                var $mmselect = mainSelector.find('table > tbody:first > tr:eq(' + cindex + ') > td');
                selSel = $mmselect.find($tagName + '[data-path=\'' + _this.attr('data-path') + '\']');

                if (selSel.length) {

                    selSel.prop('disabled', false);
                    if (typeof isChange !== 'undefined') {
                        selSel.removeAttr('value');
                    }
                    var $tmpTag = _this.attr('data-path');
                    var $tmpTagArr = $tmpTag.split('.');
                    var $tmpSizeOf = $tmpTagArr.length - 1;
                    var $tmpPathName = '';

                    $.each($tmpTagArr, function(index, row) {

                        if (index === $tmpSizeOf) {
                            $tmpPathName += 'orderNum';
                        } else {
                            $tmpPathName += row + '.';
                        }
                    });

                    $mmselect.find('input[data-path="' + $tmpPathName + '"]').val(cindex);
                    $mmselect.find('input[data-path="' + $tmpPathName + '"]').attr('value', cindex);
                    
                    selSel.children().val(thisval).attr('selected', 'selected');
                }

                setTimeout(function() {
                    selSel.select2({
                        allowClear: true,
                        closeOnSelect: false,
                        dropdownAutoWidth: true,
                        escapeMarkup: function(markup) {
                            return markup;
                        }
                    });
                }, 50);

            } else {
                selInp = mainSelector.find('table > tbody:first > tr:eq(' + cindex + ') > td').find($tagName + '[data-path=\'' + _thisName + '\']');
                selInp.val(thisval);

                var selInpBigDecimal = mainSelector.find('table > tbody:first > tr:eq(' + cindex + ') > td').find($tagName + '[data-path=\'' + _thisName + '_bigdecimal\']');

                if (selInpBigDecimal.length) {
                    var $getNumber = _this.autoNumeric('get');
                    var $resultNum = '0';
                    if (isNaN($getNumber)) {
                        $resultNum = Number(_this.val());
                    } else {
                        $resultNum = Number($getNumber);
                    }

                    selInpBigDecimal.val($resultNum);
                }
            }
        } else {
            if (typeof splitPaths[3] !== 'undefined') {

                var cindex = _this.closest('.detail-template-body-subkey-rows').attr('data-parent-index');
                var subIndex = _this.closest('.detail-template-body-subkey-rows').attr('data-index');
                var subKeyIndex = _this.closest('.detail-template-body-subkey-rows').attr('data-key-index');

                var spPath = groupPath.split('&');
                if (typeof spPath[1] !== 'undefined') {
                    groupPath = spPath[0].replace('\\', '');
                }

                mainSelector = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + groupPath + '"]');
                var secondMainSelector = mainSelector.find('table > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + splitDataPaths[0] + '.' + splitPaths[1] + '"] > tbody:first > tr:eq(' + subIndex + ')');

                selectorOption = secondMainSelector.find('table[data-table-path="' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + splitPaths[2] + '"] > tbody:first > tr:eq(' + subKeyIndex + ')').find('td ' + $tagName + '[data-path=\'' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + splitPaths[2] + '.' + $thisDataPathLastIndexVal + '\']');
                selectorOptionInd = secondMainSelector.find('table[data-table-path="' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + splitPaths[2] + '"] > tbody:first > tr:eq(' + subKeyIndex + ')').find('td input[data-path=\'' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + splitPaths[2] + '.orderNum\']');

                selectorOption2 = secondMainSelector.find('table[data-table-path="' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + splitPaths[2] + '"] > tbody:first > tr:eq(' + subKeyIndex + ')').find('td ' + $tagName + '[data-path=\'' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + splitPaths[2] + '.' + $thisNameLastIndexVal + '\']');
                
                if ($tagName === 'select') {
                    setTimeout(function() {
                        if (selectorOption.length) {
                            selectorOption.prop('disabled', false);
                            selectorOption.children().val(thisval);

                        } else {
                            selectorOption2.prop('disabled', false);
                            selectorOption2.children().val(thisval);
                        }

                        selectorOptionInd.attr('value', subKeyIndex);
                        selectorOptionInd.val(subKeyIndex);
                    }, 300);
                } else {
                    if (selectorOption.length) {
                        selectorOption.val(thisval);
                    } else {
                        selectorOption2.val(thisval);
                    }
                }

            } else {

                var subIndex = _this.closest('.detail-template-body-sub-rows').attr('data-index');
                var spPath = groupPath.split('&');

                if (typeof spPath[1] !== 'undefined') {
                    groupPath = spPath[0].replace('\\', '');
                }

                mainSelector = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + groupPath + '"]');
                selectorOption = mainSelector.find('table > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + splitDataPaths[0] + '.' + splitPaths[1] + '"] > tbody:first > tr:eq(' + subIndex + ') > td').find($tagName + '[data-path=\'' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + $thisDataPathLastIndexVal + '\']');
                selectorOptionInd = mainSelector.find('table > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + splitDataPaths[0] + '.' + splitPaths[1] + '"] > tbody:first > tr:eq(' + subIndex + ') > td').find('input[data-path=\'' + splitDataPaths[0] + '.' + splitPaths[1] + '.orderNum\']');
                
                selectorOption2 = mainSelector.find('table > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + splitDataPaths[0] + '.' + splitPaths[1] + '"] > tbody:first > tr:eq(' + subIndex + ') > td').find($tagName + '[data-path=\'' + splitDataPaths[0] + '.' + splitPaths[1] + '.' + $thisNameLastIndexVal + '\']');

                if ($tagName === 'select') {
                    
                    setTimeout(function() {
                        if (selectorOption.length) {
                            selectorOption.prop('disabled', false);
                            selectorOption.children().val(thisval);
                        } else {
                            selectorOption2.prop('disabled', false);
                            selectorOption2.children().val(thisval);
                        }

                        selectorOptionInd.attr('value', subIndex);
                        selectorOptionInd.val(subIndex);
                    }, 300);

                } else {
                    if (selectorOption.length) {
                        selectorOption.val(thisval);
                    } else {
                        selectorOption2.val(thisval);
                    }
                }
            }
        }
    }

    function closeselectedJtree_<?php echo $this->methodId; ?>() {
        singleClick_<?php echo $this->methodId ?> = 0;
        $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').addClass('hidden');
        // $('.selectedJTreePathIco_<?php echo $this->methodId ?>').removeClass('fa-angle-up').addClass('fa-angle-down');
        $(".list-jtree-<?php echo $this->methodId ?>").jstree("close_all");
    }

    function renderPartyPanel_<?php echo $this->methodId; ?>(uniqId, taxonamyObj, widgetExpression, widget, type) {
        if (typeof taxonamyObj === 'undefined')
            return;

        var uniqElement = $('body').find('div#' + uniqId);
        var idCardHtml = '';
        var widget = JSON.parse(widget);

        appendTaxonamyBodyByTag_<?php echo $this->methodId; ?>(uniqId, taxonamyObj, type);
        idCardHtml += appendWidgetProcessTemplate(uniqId, taxonamyObj, widgetExpression, widget);

        uniqElement.html(idCardHtml);
    }

    function appendTaxonamyBodyByTag_<?php echo $this->methodId; ?>(uniqId, taxonamyObj, renderType) {
        try {
            var uniqElement = $('body').find('div#' + uniqId);
            var processElement = uniqElement.closest('form');
            var taxonamyObjParse = JSON.parse(taxonamyObj),
                tloop1 = 0,
                tloop = 0,
                tlength = taxonamyObjParse.length;

            var taxonamyConvert = [];
            for (tloop1; tloop1 < tlength; tloop1++) {
                taxonamyConvert.push({
                    BODY: '',
                    BTN: taxonamyObjParse[tloop1].BTN,
                    EXPRESSION: taxonamyObjParse[tloop1].EXPRESSION,
                    EXPRESSION_DTL: taxonamyObjParse[tloop1].EXPRESSION_DTL,
                    IS_ADD_FOLLOW: taxonamyObjParse[tloop1].IS_ADD_FOLLOW,
                    PATH: taxonamyObjParse[tloop1].PATH,
                    PATH_AS: taxonamyObjParse[tloop1].PATH_AS,
                    TAG: taxonamyObjParse[tloop1].TAG,
                    TAXONOMY_ID: taxonamyObjParse[tloop1].TAXONOMY_ID,
                    WIDGET_CODE: taxonamyObjParse[tloop1].WIDGET_CODE,
                });
            }

            for (tloop; tloop < tlength; tloop++) {

                var p = taxonamyObjParse[tloop].PATH_AS.match(/^(.*)@/);
                var spPath = taxonamyObjParse[tloop].PATH_AS.split('&');

                if (p !== null && typeof p[1] !== 'undefined') {
                    taxonamyObjParse[tloop].PATH = p[1];
                } else if (typeof spPath[1] !== 'undefined') {
                    taxonamyObjParse[tloop].PATH = taxonamyObjParse[tloop].PATH_AS.replace('&', '\\&');
                }

                var taxBody = taxonamyObjParse[tloop].BODY,
                    taxBodyTag = '<p class="detail-template-body-rows"></p>';

                if (taxonamyObjParse[tloop].IS_HIGHLIGHT == '1') {
                    taxBodyTag = '<p class="detail-template-body-rows taxonomy-highlight"></p>';
                }

                if (taxonamyObjParse[tloop].IS_ADD_FOLLOW === '0') {
                    taxBodyTag = '';
                    taxBody = '';
                }

                if (taxonamyObjParse[tloop].EXPRESSION == '' || taxonamyObjParse[tloop].EXPRESSION == null) {
                    var $processElementTag = processElement.find('.' + taxonamyObjParse[tloop].PATH).attr('data-dtl-template-path', taxonamyObjParse[tloop].PATH).attr('data-dtl-template-widget', taxonamyObjParse[tloop].WIDGET_CODE);

                    if (taxonamyObjParse[tloop].BTN == '1') {
                        $processElementTag.append(taxBodyTag).promise().done(function() {
                            var encodeTaxonamyObj = encodeURIComponent(taxonamyObj);

                            if (renderType === 'widget_none') {
                                encodeTaxonamyObj = '';
                            }

                            $processElementTag.children(':last').append('<a href="javascript:;" onclick="templateDtlAddRowParty_<?php echo $this->methodId; ?>(this, \'' + uniqId + '\', \'' + encodeTaxonamyObj + '\')" class="btn btn-xs btn-circle purple-plum float-left bp-tmp-idcard-part-add-sidebar mt5" style="margin-left: -23px;">&nbsp;<i class="icon-plus3 font-size-12"></i>&nbsp;</a>' + taxBody.replace('#num#', '<span class="row-num-class">1</span>'));
                        });
                    } else {
                        $processElementTag.append(taxBodyTag).promise().done(function() {
                            $processElementTag.children(':last').append(taxBody);
                        });
                    }

                    $('.' + renderType + '_information').html(taxBody);
                    $('.' + renderType + '_information').find('input').attr('style', 'width: 100% !important;');
                    $('.' + renderType + '_information').find('select').attr('style', 'width: 100% !important;');

                } else if (processElement.find('.' + taxonamyObjParse[tloop].PATH).hasClass('detail-template-child-rows')) {
                    
                    if (taxonamyObjParse[tloop].BTN == '1') {
                        processElement.find('.' + taxonamyObjParse[tloop].PATH).attr('data-dtl-template-path', taxonamyObjParse[tloop].PATH).attr('data-dtl-template-widget', taxonamyObjParse[tloop].WIDGET_CODE).addClass('taxonamy-expression-exist').append('<p class="detail-template-body-rows"><a href="javascript:;" onclick="templateDtlAddExpressionRowParty_<?php echo $this->methodId; ?>(this, \'' + uniqId + '\', \'' + encodeURIComponent(JSON.stringify(taxonamyConvert)) + '\')" class="btn btn-xs btn-circle purple-plum float-left bp-tmp-idcard-part-add-sidebar mt5" style="margin-left: -23px;">&nbsp;<i class="icon-plus3 font-size-12"></i>&nbsp;</a></p>');
                    } else {
                        processElement.find('.' + taxonamyObjParse[tloop].PATH).attr('data-dtl-template-path', taxonamyObjParse[tloop].PATH).attr('data-dtl-template-widget', taxonamyObjParse[tloop].WIDGET_CODE).addClass('taxonamy-expression-exist').append('<p class="detail-template-body-rows"></p>');
                    }
                }
            }
        } catch (err) {
            alert('Хөгжүүлэгчид хандан уу! :(');
        }
    }

    function renderMainWidget_<?php echo $this->methodId; ?>() {
        $('.mainWidget-config', '#bp-window-<?php echo $this->methodId; ?>').find('input').removeAttr('name');
        $('.mainWidget-config', '#bp-window-<?php echo $this->methodId; ?>').find('select').removeAttr('name');

        $('.mainWidget', '#bp-window-<?php echo $this->methodId; ?>').empty().append($('.mainWidget-config', '#bp-window-<?php echo $this->methodId; ?>').html());
    }

    function multiSelectionRender_<?php echo $this->methodId ?>(lookupMetaDataId, parentId, tag, element, mainSelector) {

        var paramData = [];
        var $fSel2Clone = mainSelector.clone();

        if (typeof $(mainSelector).attr('data-in-param') !== 'undefined') {
            var paramsPathArr = $(mainSelector).attr('data-in-param').split('|');
            var _dataPath = $(mainSelector).attr('data-path');

            var $dataSelectionPath = _dataPath.replace(".", "_");
            var $mainDataPath = _dataPath.split('.');

            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0].trim();
                var fieldValue = '';

                var bpElem = getBpElement(bp_window_<?php echo $this->methodId; ?>, $(mainSelector), fieldPath);

                if (bpElem) {
                    fieldValue = getBpRowParamNum(bp_window_<?php echo $this->methodId; ?>, $(mainSelector), fieldPath);
                } else {
                    fieldValue = fieldPath;
                }

                paramData.push({
                    name: fieldPath,
                    value: fieldValue
                });
            }

            paramData.push({
                name: 'metaDataId',
                value: lookupMetaDataId
            });

            var $html = '<div class="trgMultiSelection_<?php echo $this->methodId ?> ' + $dataSelectionPath + '_<?php echo $this->methodId ?>" data-lookupparamid="' + lookupMetaDataId + '" data-lookup-parentid="' + parentId + '" data-lookup-path="' + tag + '" style="background: #cefbe2; padding: 0 5px;table-layout: fixed;float: left; line-height: 20px; width: 100%"></div>'
                            // + '<div class="input-icon right groupSelectionNodeId_<?php echo $this->methodId ?>" data-toggle="dropdown" data-delay="1000" data-close-others="true">'
                                + '<div class="input-icon right groupSelectionNodeId_<?php echo $this->methodId ?>">' +
                                    '<i class="hidden fa fa-angle-down selectedJTreePathIco_<?php echo $this->methodId ?>"></i>' +
                                        '<input type="hidden" data-path="selectionList_<?php echo $this->methodId; ?>" value="" />' +
                                        '<input type="text" name="selectionNodeList_search" data-path="' + _dataPath + '" placeholder="Сонгох..." autocomplete="off"  class="selectionNodeList_search_<?php echo $this->methodId; ?> form-control form-control-sm selectedRowData<?php echo $this->methodId ?>" />' +
                                '</div>' +
                                '<div class="srcMultiSelection_<?php echo $this->methodId ?> hidden selectionNodeList-jtree-<?php echo $this->methodId; ?> jtree-list">' +
                                    '<div class="search-tree-<?php echo $this->methodId; ?>">' +
                                        '<a class="selectionNode-multiselect-all-<?php echo $this->methodId ?>" href="javascript:;"><span>Бүгдийг сонгох</span></a>' +
                                        '<a class="selectionNode-multiselect-none-<?php echo $this->methodId ?>" style="margin-left:10px;" href="javascript:;"><span>Буцаах</span></a>' +
                                    '</div>' +
                                '<div class="list-jtree-<?php echo $this->methodId; ?>"></div>' +
                            '</div>';

            element.empty().append('<div class="hidden"></div>' + $html).promise().done(function() {
                
                var $removeSelector = element.find('.hidden:first');

                $removeSelector.append('<select data-path="' + _dataPath + '" name="' + _dataPath + '" class="trgMainMultiSelector_<?php echo $this->methodId ?>" ><option value="">Сонгох<option></select>');
                $removeSelector.find('select').trigger('change');

                $('.list-jtree-<?php echo $this->methodId ?>').on("changed.jstree", function(e, data) {
                    var selectedJTreePathIdTemp_<?php echo $this->methodId ?> = [];
                    
                    if (data.action === "select_node") {

                        if (typeof data.node.parent !== 'undefined') {
                            var parent = data.node.parent;
                            if ($.inArray(parent, selectedJTreePathId_<?php echo $this->methodId ?>) < 0) {
                                if (parent != '#') {
                                    selectedJTreePathId_<?php echo $this->methodId ?>.push(parent);
                                    selectedJTreePathIdTemp_<?php echo $this->methodId ?>.push(parent);

                                    var getDepName = $('.list-jtree-<?php echo $this->methodId ?>').jstree("get_node", parent);
                                    selectedJTreePathText_<?php echo $this->methodId ?>.push(getDepName.text);
                                }
                            }
                        }

                        if ($.inArray(data.node.id, selectedJTreePathId_<?php echo $this->methodId ?>) < 0) {
                            selectedJTreePath_<?php echo $this->methodId ?> = true;
                            selectedJTreePathId_<?php echo $this->methodId ?>.push(data.node.id);
                            selectedJTreePathText_<?php echo $this->methodId ?>.push(data.node.text);
                            selectedJTreePathIdTemp_<?php echo $this->methodId ?>.push(data.node.id);
                        }
                        selectNode_<?php echo $this->methodId ?>(data.node.id);
                        $.each(data.node.children_d, function(key, value) {
                            selectNode_<?php echo $this->methodId ?>(value);
                            if (value != '#') {
                                if ($.inArray(value, selectedJTreePathId_<?php echo $this->methodId ?>) < 0) {
                                    selectedJTreePathId_<?php echo $this->methodId ?>.push(value);
                                    selectedJTreePathIdTemp_<?php echo $this->methodId ?>.push(value);
                                    var getDepName = $('.list-jtree-<?php echo $this->methodId ?>').jstree("get_node", value);
                                    selectedJTreePathText_<?php echo $this->methodId ?>.push(getDepName.text);
                                }
                            }
                        });

                    } else if (data.action === "deselect_node") {
                        $('.selectionNode-multiselect-all-<?php echo $this->methodId ?>').removeClass('allCheckedData');

                        var _index = selectedJTreePathId_<?php echo $this->methodId ?>.indexOf(data.node.id);
                        selectedJTreePath_<?php echo $this->methodId ?> = true;
                        selectedJTreePathId_<?php echo $this->methodId ?>.splice(_index, 1);
                        selectedJTreePathText_<?php echo $this->methodId ?>.splice(_index, 1);
                        deSelectNode_<?php echo $this->methodId ?>(data.node.id);

                        if (typeof data.node.parent !== 'undefined' && data.node.parent == selectedJTreePathId_<?php echo $this->methodId ?>[selectedJTreePathId_<?php echo $this->methodId ?>.length - 1]) {
                            var parent = data.node.parent;
                            var _indexParent = selectedJTreePathId_<?php echo $this->methodId ?>.indexOf(parent);
                            selectedJTreePathId_<?php echo $this->methodId ?>.splice(_indexParent, 1);
                            selectedJTreePathText_<?php echo $this->methodId ?>.splice(_indexParent, 1);
                        }

                        $.each(data.node.children_d, function(key, value) {
                            var indexMid = selectedJTreePathId_<?php echo $this->methodId ?>.indexOf(value);
                            selectedJTreePathId_<?php echo $this->methodId ?>.splice(indexMid, 1);
                            selectedJTreePathText_<?php echo $this->methodId ?>.splice(indexMid, 1);

                            deSelectNode_<?php echo $this->methodId ?>(value);
                        });
                    } else if (data.action === "select_all") {
                        selectedJTreePathId_<?php echo $this->methodId ?> = [];
                        selectedJTreePathText_<?php echo $this->methodId ?> = [];

                        selectedJTreePath_<?php echo $this->methodId ?> = true;
                        var selectedElms = $('.list-jtree-<?php echo $this->methodId ?>').jstree("get_selected", true);
                        for (var el = 0; el < selectedElms.length; el++) {
                            if (!inArray_<?php echo $this->methodId; ?>(selectedJTreePathId_<?php echo $this->methodId ?>, selectedElms[el].id) && selectedElms[el].parent == '#') {

                                selectedJTreePathId_<?php echo $this->methodId ?>.push(selectedElms[el].id);
                                selectedJTreePathText_<?php echo $this->methodId ?>.push(selectedElms[el].text);
                                selectedJTreePathIdTemp_<?php echo $this->methodId ?>.push(selectedElms[el].id);

                                for (var el_s = 0; el_s < selectedElms.length; el_s++) {
                                    if (!inArray_<?php echo $this->methodId; ?>(selectedJTreePathId_<?php echo $this->methodId ?>, selectedElms[el_s].id) && selectedElms[el_s].parent == selectedElms[el].id) {
                                        selectedJTreePathId_<?php echo $this->methodId ?>.push(selectedElms[el_s].id);
                                        selectedJTreePathText_<?php echo $this->methodId ?>.push(selectedElms[el_s].text);
                                        selectedJTreePathIdTemp_<?php echo $this->methodId ?>.push(selectedElms[el_s].id);
                                    }
                                }
                            }
                        }

                    } <?php if ($this->isEditMode == true) { ?>
                        else if (data.action === 'ready') {
                            $.each(data.selected, function(key, value) {
                                if (typeof data['instance']['_model']['data'] !== 'undefined' && typeof data['instance']['_model']['data'][value] !== 'undefined') {
                                    selectNode_<?php echo $this->methodId ?>(value);
                                    if ($.inArray(value, selectedJTreePathId_<?php echo $this->methodId ?>) < 0) {
                                        selectedJTreePathId_<?php echo $this->methodId ?>.push(value);
                                        selectedJTreePathIdTemp_<?php echo $this->methodId ?>.push(value);
                                        selectedJTreePathText_<?php echo $this->methodId ?>.push(data['instance']['_model']['data'][value].text);
                                    }
                                }
                            });
                            
                        }
                    <?php } ?>

                    var $selectionContent = '';
                    var $ticket = true;
                    var $selectionListArr = $('input[data-path="selectionList_<?php echo $this->methodId; ?>"]').val();
                    var $selectionListArrSplit = $selectionListArr.split(',');
                    var $tagSelector = $('.' + $dataSelectionPath + '_<?php echo $this->methodId ?>');
                    
                    $.each(selectedJTreePathText_<?php echo $this->methodId ?>, function(index, rowText) {
                        if (!$selectionListArr && $selectionListArr.indexOf(selectedJTreePathId_<?php echo $this->methodId ?>[index]) > -1) {
                            $ticket = false;
                        } else {

                            if (typeof rowText !== 'undefined' && rowText.indexOf('#') > -1) {
                                var $cssStyle = '';
                                $.ajax({
                                    type: 'post',
                                    url: 'mddoc/pregMatchAllInTaxonamy',
                                    async: false,
                                    data: {
                                        rowText: rowText,
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function(responseData) {
                                        if (responseData) {
                                            $.each(responseData, function(indexT, rowT) {
                                                var $elementPath = '';
                                                var getPathElement = $('#bp-window-render-<?php echo $this->methodId ?>').find("[data-path='" + $mainDataPath[0] + '.' + rowT + "']");
                                                if (typeof getPathElement !== 'undefined' && getPathElement.length > 0) {
                                                    var $tagName = getPathElement.prop("tagName").toLowerCase();

                                                    if ($tagName === 'select') {
                                                        $elementPath = getPathElement;
                                                    } else {
                                                        $elementPath = getPathElement;
                                                    }
                                                    
                                                    rowText = rowText.replace('#' + rowT + '#', $elementPath.parent().html());
                                                    $cssStyle = 'display: grid;height: 100%;display: inherit;';
                                                }
                                            });
                                        }

                                        Core.unblockUI();
                                    },
                                    error: function() {
                                        alert('Error');
                                        Core.unblockUI();
                                    }

                                });
                            }

                            if (selectedJTreePathId_<?php echo $this->methodId ?>[index] !== '#' && !$tagSelector.find('span[data-path-id="' + selectedJTreePathId_<?php echo $this->methodId ?>[index] + '"]').length) {
                                $selectionContent += '<span data-path-id="' + selectedJTreePathId_<?php echo $this->methodId ?>[index] + '" style="' + $cssStyle + '"  class="float-left mr5 padding-1">' + rowText + ', </span> ';
                            }

                        }
                    });

                    if (!selectedJTreePathId_<?php echo $this->methodId ?> || !$selectionListArr || $ticket) {}
                    
                    $tagSelector.append($selectionContent).promise().done(function() {

                        var getPathElement = element.find("[data-path='" + _dataPath + "']");
                        
                        if (getPathElement.length > 0) {
                            var $tagName = getPathElement.prop("tagName").toLowerCase();
                            var _this = element.find($tagName + '[data-path="' + _dataPath + '"]');
                            _this.val(selectedJTreePathId_<?php echo $this->methodId ?>);

                            if ($tagName === 'select') {
                                var _thisName = typeof _this.attr('name') === 'undefined' ? _this.attr('data-path') : _this.attr('name'),
                                    groupPath = _this.closest('.detail-template-body').attr('data-dtl-template-path'),
                                    cindex = _this.closest('.detail-template-body-rows').index();

                                if (typeof groupPath === 'undefined') {
                                    return;
                                }

                                if (groupPath === 'mainWidget') {
                                    fillMainHeader_<?php echo $this->methodId; ?>(_this);
                                    return;
                                }

                                var splitPaths = _thisName.split('.');
                                var splitDataPaths = _this.attr('data-path').split('.');

                                var $thisDataPathLastIndexVal = splitDataPaths[splitDataPaths.length - 1];
                                var $thisNameLastIndexVal = splitPaths[splitPaths.length - 1];
                                _this.children().val(selectedJTreePathId_<?php echo $this->methodId ?>);
                                _this.children().attr('data-temp-value', selectedJTreePathIdTemp_<?php echo $this->methodId ?>);
                                _this.trigger('change');

                                <?php if ($this->isEditMode == true) { ?>

                                    $tagSelector.find('input').each(function (__index, __row) {
                                        
                                        var $tempPath = $(__row).attr('data-path');
                                        var $selectorValue = $('#bp-window-render-<?php echo $this->methodId ?>').find('input[data-path="'+ $tempPath +'"]').val();
                                        
                                        $(__row).val($selectorValue);
                                        $(__row).attr('value', $selectorValue);

                                    });
                                    //changeSelector_<?php echo $this->methodId ?>(_this, $tagName, groupPath, '', '', '', '', '', splitDataPaths, splitDataPaths, $thisDataPathLastIndexVal, $thisNameLastIndexVal, cindex, _thisName, selectedJTreePathId_<?php echo $this->methodId ?>, 'change', '1');
                                <?php } else { ?>
                                    changeSelector_<?php echo $this->methodId ?>(_this, $tagName, groupPath, '', '', '', '', '', splitDataPaths, splitDataPaths, $thisDataPathLastIndexVal, $thisNameLastIndexVal, cindex, _thisName, selectedJTreePathId_<?php echo $this->methodId ?>, 'change');
                                <?php } ?>
                                
                            } else {
                                _this.trigger('change');
                            }

                        }

                        $.each($selectionListArrSplit, function(index, rowId) {
                            if (!inArray_<?php echo $this->methodId; ?>(selectedJTreePathId_<?php echo $this->methodId ?>, rowId)) {
                                $('span[data-path-id="' + rowId + '"]').remove();
                            }
                        });

                        $('input[data-path="selectionList_<?php echo $this->methodId; ?>"]').val(selectedJTreePathId_<?php echo $this->methodId ?>);

                        $tagSelector.find('.select2-container').remove();
                        
                        Core.initDateInput($tagSelector);
                        Core.initDateTimeInput($tagSelector);
                        Core.initNumberInput($tagSelector);
                        Core.initLongInput($tagSelector);
                        Core.initSelect2($tagSelector);
                        Core.initUniform($tagSelector);
                        Core.initDateMinuteInput($tagSelector);
                        Core.initTimeInput($tagSelector);
                        Core.initTimerInput($tagSelector);
                        Core.initRegexMaskInput($tagSelector);
//                        Core.initAccountCodeMask($tagSelector);
//                        Core.initStoreKeeperKeyCodeMask($tagSelector);
                        Core.initTinymceEditor($tagSelector);
                        Core.initCodeView($tagSelector);
                        Core.initIconPicker($tagSelector);
                        Core.initMxGraph($tagSelector);
                        Core.initBpToolbarSticky($tagSelector);
                        Core.initMaxLength($tagSelector);
                        
                        <?php if ($this->isEditMode == true) { ?>
                            
                            $tagSelector.find('select').each(function (__index, __row) {

                                var $tempPath = $(__row).attr('data-path');
                                var $selectorValue = $('#bp-window-render-<?php echo $this->methodId ?>').find('select[data-path="'+ $tempPath +'"]').val();

                                $(__row).trigger("select2-opening", 'notdisabled');
                                $(__row).select2('val', $selectorValue);

                            });
                            
                        <?php } ?>
                    });

                }).on("loaded.jstree", function() {

                }).jstree({
                    "core": {
                        expand_selected_onload: false,
                        "open_parents": true,
                        "load_open": true,
                        'data': {
                            url: URL_APP + 'mddoc/getLookupParams',
                            type: 'post',
                            data: function(node) {
                                var $ticketParam = true;
                                $.each(paramData, function(pInd, pRow) {
                                    if (pRow['name'] === 'parentId') {
                                        pRow['value'] = (node.id === "#" ? '' : node.id);
                                        $ticketParam = false;
                                    }
                                });
                                if ($ticketParam) {
                                    paramData.push({
                                        name: 'parentId',
                                        value: (node.id === "#" ? '' : node.id)
                                    });
                                }

                                return paramData;
                            },
                            dataType: "json",
                            success: function() {
                                setTimeout(function() {
                                    $(".list-jtree-<?php echo $this->methodId ?>").jstree("close_all");
                                }, 1000);
                            }
                        },
                        'themes': {
                            'responsive': false,
                            'stripes': true,
                            "icons": false
                        }
                    },
                    "checkbox": {
                        keep_selected_style: false,
                        real_checkboxes: true,
                        real_checkboxes_names: function(n) {
                            var nid = 0;
                            $(n).each(function(data) {
                                nid = $(this).attr("nodeid");
                            });
                            return (["check_" + nid, nid]);
                        },
                        three_state: true,
                        two_state: true,
                        whole_node: true
                    },
                    'unique': {
                        'duplicate': function(name, counter) {
                            return name + ' ' + counter;
                        }
                    },
                    'plugins': [
                        'changed', 'unique', 'wholerow', 'checkbox', 'search'
                    ]
                });
            });
        }

    }

    $('body').on('click', '.selectedRowData<?php echo $this->methodId ?>', function() {
        // if ($('.selectedJTreePathIco_<?php echo $this->methodId ?>').hasClass('fa-angle-up')) {
        //     return;
        // }

        if (singleClick_<?php echo $this->methodId ?> == 0) {
            singleClick_<?php echo $this->methodId ?> = 1;
            var _jtreewidth = 629;
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').width(_jtreewidth);
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').find('.jstree-container-ul').width(_jtreewidth - 12);
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').removeClass('hidden');
            // $('.selectedJTreePathIco_<?php echo $this->methodId ?>').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            singleClick_<?php echo $this->methodId ?> = 0;
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').addClass('hidden');
            // $('.selectedJTreePathIco_<?php echo $this->methodId ?>').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });

    $('body').on('click', '.selectionNode-multiselect-all-<?php echo $this->methodId ?>', function() {
        if ($(this).hasClass('allCheckedData')) {
            return;
        }
        $(this).addClass('allCheckedData');
        $('.list-jtree-<?php echo $this->methodId ?>').jstree("select_all");
        $(".list-jtree-<?php echo $this->methodId ?>").jstree("close_all");
    });

    $('body').on('click', '.selectionNode-multiselect-none-<?php echo $this->methodId ?>', function() {
        $('.selectionNode-multiselect-all-<?php echo $this->methodId ?>').removeClass('allCheckedData');
        selectedJTreePathId_<?php echo $this->methodId ?> = [];
        selectedJTreePathText_<?php echo $this->methodId ?> = [];

        $('.list-jtree-<?php echo $this->methodId ?>').jstree("deselect_all");
        $(".list-jtree-<?php echo $this->methodId ?>").jstree("close_all");

        $('input[data-path="selectionList_<?php echo $this->methodId; ?>"]').val('');
    });

    var selectNode_<?php echo $this->methodId ?> = function(id) {
        $('.list-jtree-<?php echo $this->methodId ?>').jstree("select_node", id, true, true);
    };

    var deSelectNode_<?php echo $this->methodId ?> = function(id) {
        $('.list-jtree-<?php echo $this->methodId ?>').jstree("deselect_node", id, true, true);
    };

    function cellRightSidebar_<?php echo $this->methodId ?>(element) {
        var $parent = $(element).closest('.bp-template-table-cell-right');

        if ($parent.attr('cell-right-inside-status') === 'hidden') {
            $parent.attr('cell-right-inside-status', 'show');
            $parent.find('.bp-template-table-cell-right-inside').show("slide", {
                direction: "right"
            }, 1200);
        } else {
            $parent.find('.bp-template-table-cell-right-inside').hide("slide", {
                direction: "right"
            }, 1200);
            $parent.attr('cell-right-inside-status', 'hidden');
        }
    }
</script>
<div class="xs-form bp-banner-container bp-template-mode <?php echo (isset($this->bpTemplateRow) ? ' ' . issetParam($this->bpTemplateRow['controlDesign']) : '');
                                                            echo ($this->mainBpTab['ticket'] == '1') ? 'pl0 pr0' : '' ?>" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
    <?php
    echo Form::create(array('id' => 'wsForm', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => ($this->isBanner ? 'bp-banner-content' : '')));

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

    echo $mainProcessBtnBar;

    if (isset($this->wfmStatusParams['result']) && isset($this->selectedRowData) && isset($this->hasMainProcess) && $this->hasMainProcess) {

        /*
        if (isset($this->wfmStatusBtns) && $this->wfmStatusBtns && isset($this->wfmStatusBtns['result']) && $this->wfmStatusBtns['result']) {
            $singleMenuHtml .= '<span class="workflowBtn-'. $this->methodId .'"></span>';
            foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
                $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['wfmstatusname']) . '\', \'\', \'changeHardAssign\',  \'\', \''. $this->uniqId .'\', \''. $this->methodId .'\', undefined , undefined , \'' . $wfmstatusRow['wfmstatusprocessid'] . '\' , \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined , undefined , undefined , \'' . $isCallNextFunction .'\', \'' . $wfmstatusRow['isformnotsubmit'] . '\');"';
                $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' class="hidden btn btn-sm purple-plum btn-circle hidden-wfm-status-'. $wfmstatusRow['wfmstatusid']  .'" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['wfmstatusname'] .'</button> ';
            } 
        }*/

        $wfmPanelViewer = (new Mdworkflow())->wfmPanelViewer($this->refStructureId, $this->sourceId, $this->selectedRowData['wfmstatusid']);
        $singleMenuHtml = $wfmPanelViewer['statusStep'];
        $wfmAssignmentUsers = $wfmPanelViewer['assignmentUsers'];

        echo $singleMenuHtml;
    }

    echo $this->bpTab['tabStart'];

    echo $dialogProcessLeftBanner;
    echo $processsDialogContentClassBegin;

    ?>
    <!-- banner -->
    <div class="row">
        <div class="col-md-12 center-sidebar <?php echo ($this->mainBpTab['ticket'] == '0') ? 'bpTemplatemap-' . $this->methodId : '' ?>">
            <?php
            echo $mainProcessLeftBanner;
            echo $processsMainContentClassBegin;

            $isDtlTbl = $sidebarShow = $sidebarShowRowDtl = false;
            $notUseControls = $expressionTagReplace = '';

            if ($this->paramList) {

                if (isset($this->templateDropDownList)) {
                    echo $this->templateDropDownList;
                }

                echo '<div class="bp-template-wrap">';
                echo '<div class="bp-template-table">';
                echo '<div class="bp-template-table-row">';
                echo '<div class="bp-template-table-cell-left">';

                $sidebarHeaderArr = array();
                $sidebarDtlRowArr = array();
                $getDtlRowsPopup = array();
                $constantKeys = Mdstatement::constantKeys();

                (string) $htmlContent = $this->htmlContent;
                (string) $sidebarGroupMetaRowsRender = '';
                (string) $sidebarGroup = '';
                (string) $sidebarGroupMetaRender = '';
                $replacedCount = 0;

                foreach ($constantKeys as $constantKey => $constantKeyValue) {
                    $htmlContent = str_ireplace($constantKey, $constantKeyValue, $htmlContent);
                    
                }
                
                $htmlContent = str_ireplace('div style', 'div sstyle', $htmlContent);
                
                if (!isset($this->notFieldReplace)) {
                    $htmlContent = Mdstatement::assetsReplacer($htmlContent);
                    $htmlContent = Mdstatement::configValueReplacer($htmlContent, null);
                }

                foreach ($this->paramList as $k => $row) {
                    if ($row['type'] == 'header' && isset($row['data'])) {

                        $buildData = Mdwebservice::getOnlyShowParamAndHiddenPrint($row['data'], $this->fillParamData);
                        $headerParams = $buildData['onlyShow'];
                        $gridHeaderClass = '';

                        foreach ($headerParams as $headerParam) {

                            $gridHeaderClass .= Mdwebservice::fieldHeaderStyleClassByWTemplate($headerParam, 'bp-window-' . $this->methodId);
                            $control = Mdwebservice::renderParamControl($this->methodId, $headerParam, 'param[' . $headerParam['META_DATA_CODE'] . ']', $headerParam['META_DATA_CODE'], $this->fillParamData);

                            if (!isset($this->notFieldReplace)) {
                                $htmlContent = str_ireplace('#' . $headerParam['META_DATA_CODE'] . '#', $control, $htmlContent, $replacedCount);
                            }

                            if ($replacedCount == 0) {
                                $notUseControls .= $control;
                            }
                        }
            ?>
                        <style type="text/css">
                            .bp-window-<?php echo $this->methodId; ?>table.bp-header-param {
                                table-layout: fixed;
                            }

                            <?php echo $gridHeaderClass; ?>
                        </style>
                    <?php
                        echo $buildData['hiddenParam'];
                    } elseif ($row['type'] == 'detail') {
                        (bool) $isMultiRow = false;
                        (bool) $isTab = false;
                        (string) $htmlHeaderCell = '';
                        (string) $htmlBodyCell = '';
                        (string) $htmlGridFoot = '<td></td>';
                        (string) $gridHead = '';
                        (string) $gridHeadFilter = '';
                        (string) $gridBody = '';
                        (string) $gridFoot = '';
                        (string) $gridBodyRow = '';
                        (string) $gridBodyRowAfter = '';
                        (string) $gridTabBody = '';
                        (string) $gridTabContentHeader = '';
                        (string) $gridTabContentBody = '';
                        (string) $gridRowTypePath = '';
                        (string) $gridClass = '';
                        (string) $detialView = false;
                        (string) $isAggregate = false;
                        (string) $aggregateClass = '';
                        (string) $gridDetialClass = '';
                        (array) $firstLevelRowArr = array();
                        (array) $sidebarGroupArr_{
                            $row['id']} = array();
                        $replacedCountDtl = 0;

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
                                $gridDetialClass .= Mdwebservice::fieldHeaderStyleClassByWTemplate($val, 'bp-window-' . $this->methodId);
                                $foodAmount = '';
                                $aggregateClass = '';

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
                                        $gridHead .= '<th class="text-center' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                        $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></th>';
                                        $gridFoot .= '<td class="text-center' . $hideClass . ' ' . $paramRealPath . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"></td>';
                                    }
                                } else {
                                    if (empty($val['SIDEBAR_NAME']) && $isMultiRow && $val['RECORD_TYPE'] !== 'row' && $val['RECORD_TYPE'] !== 'rows') {
                                        $gridHead .= '<th class="' . $hideClass . ' ' . $paramRealPath . ' bp-head-sort" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" data-aggregate="' . $val['COLUMN_AGGREGATE'] . '">' . $this->lang->line($val['META_DATA_NAME']) . '</th>';
                                        $gridHeadFilter .= '<th class="' . $hideClass . '" data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '"><input type="text"/></th>';
                                        $gridFoot .= '<td class="text-right' . $hideClass . ' ' . $paramRealPath . ' bigdecimalInit"  data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">' . $foodAmount . '</td>';
                                    }
                                }

                                if ($isMultiRow) {
                                    $gridClass .= Mdwebservice::fieldDetailStyleTaxoClass($val, $paramRealPath, str_replace('.', '\.', $val['PARAM_REAL_PATH']), 'bp-window-' . $this->methodId);

                                    $arg = array(
                                        'parentRecordType' => 'rows'
                                    );
                                    if ($val['RECORD_TYPE'] == 'row') {
                                        if ($val['IS_BUTTON'] == '1') {
                                            ++$ii;
                                            (string) $gridTabActive = '';
                                            if ($ii === 1)
                                                $gridTabActive = ' active';

                                            $isTab = true;
                                            $arg['isTab'] = 'tab';

                                            $gridRowTypePath = $row['code'] . '.' . $val['META_DATA_CODE'];
                                            $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '">';
                                            $gridTabContentHeader .= '<a href="#' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                            $gridTabContentHeader .= '</li>';
                                            $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . '_' . $val['META_DATA_CODE'] . '" data-section-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '">';
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
                                        (string) $gridTabActive = "";
                                        if ($ii === 1)
                                            $gridTabActive = " active";

                                        $isTab = true;
                                        $arg['isTab'] = 'tab';
                                        $arg['isShowAdd'] = $val['IS_SHOW_ADD'];
                                        $arg['isShowDelete'] = $val['IS_SHOW_DELETE'];
                                        $arg['isShowMultiple'] = $val['IS_SHOW_MULTIPLE'];
                                        $arg['isFirstRow'] = $val['IS_FIRST_ROW'];

                                        $gridRowTypePath = $row['code'] . '.' . $val['META_DATA_CODE'];
                                        $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '">';
                                        $gridTabContentHeader .= '<a href="#' . $row['code'] . "_" . $val['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . $this->lang->line($val['META_DATA_NAME']) . '</a>';
                                        $gridTabContentHeader .= '</li>';
                                        $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . $row['code'] . "_" . $val['META_DATA_CODE'] . '">';
                                        $gridTabContentBody .= $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $row['code'] . '.' . $val['META_DATA_CODE'], 'rows', $val['ID'], null, '', $arg, '', $val['COLUMN_COUNT']);
                                        $gridTabContentBody .= '</div>';
                                    } elseif (empty($val['SIDEBAR_NAME'])) {
                                        $gridBody .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput middle text-center' . $hideClass . ' ' . $row['code'] . $val['META_DATA_CODE'] . ' ' . $aggregateClass . '">';
                                        $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], null);
                                        $gridBody .= '</td>';
                                    } else {
                                        $sidebarShowRowsDtl_{
                                            $row['id']} = true;
                                        if (!in_array($val['SIDEBAR_NAME'], $sidebarGroupArr_{
                                            $row['id']})) {
                                            $sidebarGroupArr_{
                                                $row['id']}[$ind] = $val['SIDEBAR_NAME'];
                                            $sidebarDtlRowsContentArr_{
                                                $row['id'] . $ind} = array();
                                        }

                                        $groupKey = array_search($val['SIDEBAR_NAME'], $sidebarGroupArr_{
                                            $row['id']});
                                        $labelAttr = array(
                                            'text' => $this->lang->line($val['META_DATA_NAME']),
                                            'for' => "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]",
                                            'data-label-path' => $row['code'] . "." . $val['META_DATA_CODE']
                                        );
                                        if ($val['IS_REQUIRED'] == '1') {
                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                        }
                                        if ($val['META_TYPE_CODE'] == 'date') {
                                            $inHtml = '<div style="width: 132px; text-align: left;">' . Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array()) . "</div>";
                                        } else {
                                            $inHtml = Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], array());
                                        }
                                        $sidebarDtlRowsContentArr_{
                                            $row['id'] . $groupKey}[] = array(
                                            'input_label_txt' => Form::label($labelAttr),
                                            'data_path' => $row['code'] . "." . $val['META_DATA_CODE'],
                                            'input_html' => $inHtml
                                        );
                                        $sidebarDtlRowsContentArr_{
                                            $row['id']}[$groupKey] = $sidebarDtlRowsContentArr_{
                                            $row['id'] . $groupKey};
                                    }
                                } else {

                                    $gridClass .= Mdwebservice::fieldDetailRowStyleClass($val, 'bp-window-' . $this->methodId);
                                    $arg = array();
                                    if (empty($val['SIDEBAR_NAME'])) {

                                        if ($isMultiRow) {
                                            $gridBody .= '<td data-cell-path="' . $row['code'] . "." . $val['META_DATA_CODE'] . '" class="' . $row['code'] . $val['META_DATA_CODE'] . ' stretchInput text-center' . $hideClass . '">';
                                            $gridBody .= Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], null);
                                            $gridBody .= '</td>';
                                        } else {
                                            if ($val['RECORD_TYPE'] === 'rows') {
                                                $arg['isShowAdd'] = $val['IS_SHOW_ADD'];
                                                $arg['isShowDelete'] = $val['IS_SHOW_DELETE'];
                                                $arg['isShowMultiple'] = $val['IS_SHOW_MULTIPLE'];
                                                $arg['isFirstRow'] = $val['IS_FIRST_ROW'];

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
                                            } else if ($val['RECORD_TYPE'] === 'row') {
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
                                        $fillParamData = isset($this->fillParamData[Str::lower($row['code'])]) ? $this->fillParamData[Str::lower($row['code'])] : null;
                                        if (!in_array($val['SIDEBAR_NAME'], $sidebarDtlRowArr)) {
                                            $sidebarDtlRowArr[$ind] = $val['SIDEBAR_NAME'];
                                            $sidebarDtlRowContentArr{
                                                $ind} = array();
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
                                        $sidebarDtlRowContentArr{
                                            $groupKey}[] = array(
                                            'input_label_txt' => Form::label($labelAttr),
                                            'data_path' => $row['code'] . "." . $val['META_DATA_CODE'],
                                            'input_html' => Mdwebservice::renderParamControl($this->methodId, $val, "param[" . $row['code'] . "." . $val['META_DATA_CODE'] . "][0][]", $row['code'] . "." . $val['META_DATA_CODE'], $fillParamData)
                                        );
                                        $sidebarDtlRowContentArr[$groupKey] = $sidebarDtlRowContentArr{
                                            $groupKey};
                                    }
                                }

                                $isDtlTbl = true;
                            }

                            if (isset($this->getTaxonamyTagExpression)) {
                                /**
                                 * Taxonamy Expression Tag Replace Procces
                                 */

                                if (isset($this->getTaxonamyTagExpression[$row['code']])) {
                                    foreach ($this->getTaxonamyTagExpression[$row['code']]['tag'] as $ek => $ev) {
                                        $expressionTagReplace .= '<div class="' . $ev . '-' . $row['code'] . '" data-taxonamy-id="' . $this->getTaxonamyTagExpression[$row['code']]['id'][$ek] . '"><p></p>';

                                        $replacedExpVar = Str::htmltotext($this->getTaxonamyTagExpression[$row['code']]['body'][$ek]);
                                        foreach ($row['data'] as $indt => $valt) {
                                            $replacedExpVar = str_ireplace('#' . $valt['META_DATA_CODE'] . '#', '<label class="' . $row['code'] . $valt['META_DATA_CODE'] . ' 0">' . Mdwebservice::renderParamControl($this->methodId, $valt, $row['code'] . "." . $valt['META_DATA_CODE'], $valt['META_DATA_CODE'], null) . '</label>', $replacedExpVar);
                                        }

                                        //$expressionTagReplace .= html_entity_decode($replacedExpVar, ENT_QUOTES, 'UTF-8'); combo darhad ERROR garch bna
                                        $expressionTagReplace .= $replacedExpVar;
                                        $expressionTagReplace .= '</div>';
                                    }
                                }

                                if (isset($this->getTaxonamyTagExpression[$row['code'] . '\\'])) {
                                    foreach ($this->getTaxonamyTagExpression[$row['code'] . '\\']['tag'] as $ek => $ev) {
                                        $expressionTagReplace .= '<div class="' . $ev . '-' . $row['code'] . '" data-taxonamy-id="' . $this->getTaxonamyTagExpression[$row['code'] . '\\']['id'][$ek] . '"><p></p>';
                                        $replacedExpVar = Str::htmltotext($this->getTaxonamyTagExpression[$row['code'] . '\\']['body'][$ek]);
                                        foreach ($row['data'] as $indt => $valt) {
                                            $replacedExpVar = str_ireplace('#' . $valt['META_DATA_CODE'] . '#', '<label class="' . $row['code'] . $valt['META_DATA_CODE'] . ' 1">' . Mdwebservice::renderParamControl($this->methodId, $valt, $row['code'] . "." . $valt['META_DATA_CODE'], $valt['META_DATA_CODE'], null) . '</label>', $replacedExpVar);
                                        }

                                        //$expressionTagReplace .= html_entity_decode($replacedExpVar, ENT_QUOTES, 'UTF-8'); combo darhad ERROR garch bna
                                        $expressionTagReplace .= $replacedExpVar;
                                        $expressionTagReplace .= '</div>';
                                    }
                                }

                                /**
                                 * Dtl dtl combo expression
                                 */
                                if (isset($this->getTaxonamyTagExpression[$row['code'] . '__dtlpath'])) {
                                    foreach ($this->getTaxonamyTagExpression[$row['code']]['tag_dtl'] as $ek => $ev) {
                                        $expressionTagReplace .= '<div class="' . $ev . '-' . $row['code'] . '-' . $this->getTaxonamyTagExpression[$row['code'] . '__dtlpath'] . '" data-taxonamy-id="' . $this->getTaxonamyTagExpression[$row['code']]['id_dtl'][$ek] . '"><p></p>';

                                        $replacedExpVar = Str::htmltotext($this->getTaxonamyTagExpression[$row['code']]['body_dtl'][$ek]);
                                        foreach ($row['data'] as $indt => $valt) {
                                            $replacedExpVar = str_ireplace('#' . $valt['META_DATA_CODE'] . '#', '<label class="' . $row['code'] . $this->getTaxonamyTagExpression[$row['code'] . '__dtlpath'] . $valt['META_DATA_CODE'] . ' 3">' . Mdwebservice::renderParamControl($this->methodId, $valt, $row['code'] . "." . $this->getTaxonamyTagExpression[$row['code'] . '__dtlpath'] . "." . $valt['META_DATA_CODE'], $valt['META_DATA_CODE'], null) . '</label>', $replacedExpVar);
                                        }

                                        //$expressionTagReplace .= html_entity_decode($replacedExpVar, ENT_QUOTES, 'UTF-8'); combo darhad ERROR garch bna
                                        $expressionTagReplace .= $replacedExpVar;
                                        $expressionTagReplace .= '</div>';
                                    }
                                }

                                /**
                                 * Dtl key combo expression
                                 */
                                if (isset($this->getTaxonamyTagExpression[$row['code'] . '__dtlkeypath'])) {
                                    foreach ($this->getTaxonamyTagExpression[$row['code']]['tag_dtlkey'] as $ek => $ev) {
                                        $expressionTagReplace .= '<div class="' . $ev . '-' . $row['code'] . '-' . $this->getTaxonamyTagExpression[$row['code'] . '__dtlkeypath'] . '" data-taxonamy-id="' . $this->getTaxonamyTagExpression[$row['code']]['id_dtlkey'][$ek] . '"><p></p>';

                                        $replacedExpVar = Str::htmltotext($this->getTaxonamyTagExpression[$row['code']]['body_dtlkey'][$ek]);
                                        foreach ($row['data'] as $indt => $valt) {
                                            $replacedExpVar = str_ireplace('#' . $valt['META_DATA_CODE'] . '#', '<label class="' . $row['code'] . $this->getTaxonamyTagExpression[$row['code'] . '__dtlkeypath'] . $valt['META_DATA_CODE'] . ' 4">' . Mdwebservice::renderParamControl($this->methodId, $valt, $row['code'] . "." . $this->getTaxonamyTagExpression[$row['code'] . '__dtlkeypath'] . "." . $valt['META_DATA_CODE'], $valt['META_DATA_CODE'], null) . '</label>', $replacedExpVar);
                                        }

                                        //$expressionTagReplace .= html_entity_decode($replacedExpVar, ENT_QUOTES, 'UTF-8'); combo darhad ERROR garch bna
                                        $expressionTagReplace .= $replacedExpVar;
                                        $expressionTagReplace .= '</div>';
                                    }
                                }

                                /**
                                 * END
                                 */
                            }

                            $gridBodyRow .= Mdwebservice::renderFirstLevelAddEditDtlRow($this->methodId, $firstLevelRowArr, $row['code'], $row['columnCount']);
                            $gridBodyRow .= $gridBodyRowAfter;

                            if ($isMultiRow) {
                                $actionWidth = 40;
                                if (isset($sidebarShowRowsDtl_{
                                    $row['id']})) {
                                    $actionWidth = 70;
                                }
                                $htmlHeaderCell = '<th class="action ' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '" style="width:' . $actionWidth . 'px;"></th>';
                                $htmlBodyCell .= '<td class="text-center stretchInput middle' . ($row['isShowDelete'] === '1' ? '' : ' hide') . '">';

                                if (isset($sidebarShowRowsDtl_{
                                    $row['id']})) {
                                    $htmlBodyCell .= '<a href="javascript:;" onclick="proccessRenderPopup(\'div#bp-window-' . $this->methodId . ':visible\', this);" class="btn btn-xs purple-plum" style="width:21px" title="Popup цонхоор харах"><i class="fa fa-external-link"></i></a>';
                                    $htmlBodyCell .= '<div class="sidebarDetailSection hide">';

                                    if (!empty($sidebarGroupArr_{
                                        $row['id']})) {
                                        foreach ($sidebarGroupArr_{
                                            $row['id']} as $keyPopGroup => $rowPopGroup) {

                                            $htmlBodyCell .= '<p class="property_page_title">' . $this->lang->line($rowPopGroup) . '</p>' .
                                                '<div class="panel panel-default bg-inverse grid-row-content">' .
                                                '<table class="table sheetTable sidebar_detail">' .
                                                '<tbody>';
                                            foreach ($sidebarDtlRowsContentArr_{
                                                $row['id']}[$keyPopGroup] as $subrowPopGroup) {
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
                                }
                                $htmlBodyCell .= '</td>';
                            }

                            if ($isTab) {
                                $htmlHeaderCell .= '<th data-cell-path="' . $gridRowTypePath . '"></th>';
                                $gridFoot .= '<td data-cell-path="' . $gridRowTypePath . '"></td>';
                                $gridBody .= '<td data-cell-path="' . $gridRowTypePath . '" class="text-center stretchInput middle">';
                                $gridBody .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $this->methodId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="Дэлгэрэнгүй">';
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
                            $gridFoot .= '</tr>';

                            $content = '<div class="row" data-section-path="' . $row['code'] . '" data-isclear="' . $row['isRefresh'] . '">
                            <div class="col-md-12">';

                            if ($isMultiRow) {

                                $bpDtlAddHtml = $this->cache->get('bpDtlAddDtl_' . $this->methodId . '_' . $row['id']);

                                if ($bpDtlAddHtml == null) {
                                    $bpDtlAddHtml = Str::remove_doublewhitespace(str_replace(array("\r\n", "\n", "\r"), '', $gridBody));
                                    $this->cache->set('bpDtlAddDtl_' . $this->methodId . '_' . $row['id'], $bpDtlAddHtml, Mdwebservice::$expressionCacheTime);
                                }

                                $content .= '<div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">';

                                if ($row['isShowAdd'] === '1') {
                                    $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow float-left mr5 bp-add-one-row', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('addRow'), 'onclick' => 'bpAddMainRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['id'] . '\');'));
                                }

                                if ($row['isShowMultiple'] === '1' && $row['groupLookupMeta'] != '' && $row['isShowMultipleMap'] != '0') {
                                    $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow mr5 float-left bp-add-multi-row', 'value' => '<i class="icon-plus3 font-size-12"></i> Олноор нэмэх', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'\');'));
                                }

                                if ($row['groupKeyLookupMeta'] != '' && $row['isShowMultipleKeyMap'] != '0') {
                                    $content .= '<div class="input-group quick-item-process float-left bp-add-ac-row" data-action-path="' . $row['code'] . '">';
                                    $content .= '<div class="input-icon">';
                                    $content .= '<i class="far fa-search"></i>';
                                    $content .= Form::text(array(
                                        'class' => 'form-control form-control-sm lookup-code-hard-autocomplete lookup-hard-autocomplete',
                                        'style' => 'padding-left:25px;',
                                        'data-processid' => $this->methodId,
                                        'data-lookupid' => $row['groupKeyLookupMeta'],
                                        'data-path' => $row['paramPath'],
                                        'data-in-param' => $row['groupConfigParamPath'],
                                        'data-in-lookup-param' => $row['groupConfigLookupPath']
                                    ));
                                    $content .= '</div>';
                                    $content .= '<span class="input-group-btn">';
                                    $content .= Form::button(array('data-action-path' => $row['code'], 'class' => 'btn btn-xs green-meadow bp-group-save', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupKeyLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'autocomplete\');'));
                                    $content .= '</span>';
                                    $content .= '</div>';
                                }
                                $content .= '<div class="clearfix w-100"></div>';
                                $content .= '</div>';

                                if ($row['isSave'] == '1') {
                                    $content .= '<div class="col-md-6">
                                                ' . Form::button(array('class' => 'btn btn-xs green-meadow float-right', 'value' => '<i class="fa fa-save"></i> Хадгалах', 'onclick' => 'bpSaveMainRow(this);')) . '
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
                                    $content .= '<div data-parent-path="' . $row['code'] . '" class="bp-overflow-xy-auto">
                                                <style type="text/css">#bp-window-' . $this->methodId . ' .bprocess-table-dtl[data-table-path="' . $row['code'] . '"]{table-layout: fixed !important; max-width: ' . Mdwebservice::$tableWidth . 'px !important;} ' . $gridClass . '</style>
                                                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="' . $row['code'] . '">
                                                    <thead>
                                                        ' . $gridHead . $gridHeadFilter . '
                                                    </thead>
                                                    <tbody class="tbody">
                                                        ' . /* is required - one row */ ($detialView ? $gridBody : '') . $gridBodyData . '
                                                    </tbody>
                                                    <tfoot>' . ($isAggregate === true ? $gridFoot : '') . '</tfoot>
                                                </table>    
                                            </div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                if ($row['isSave'] == '1') {
                                    $content .= Form::button(array('class' => 'btn btn-xs green-meadow float-right', 'value' => '<i class="fa fa-save"></i> Хадгалах', 'onclick' => 'bpSaveMainRow(this);'));
                                }

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

                            $controlGroup = '<div data-section-path="' . $row['code'] . '" class="bp-word-template-group-path" data-isclear="' . $row['isRefresh'] . '">' . $content . '</div>';

                            if (!isset($this->notFieldReplace)) {
                                foreach ($this->getTaxoConfigByTemplate as $taxcon) {
                                    if (Str::lower($taxcon['PATH']) == Str::lower($row['code']) && empty($taxcon['TAXONOMY_ID'])) {
                                        $htmlContent = str_ireplace('#' . $row['code'] . '#', $controlGroup, $htmlContent, $replacedCountDtl);
                                    }
                                }
                            }

                            if ($replacedCountDtl == 0) {
                                $notUseControls .= $controlGroup;
                            }
                        }
                    ?>
                        <style type="text/css">
                            <?php
                            echo $gridDetialClass;
                            ?>
                        </style>
            <?php
                    }

                    if (isset($this->getTaxonamyTagExpression) && isset($this->getTaxonamyTagExpression['mainWidget']['id_header']) && !empty($this->getTaxonamyTagExpression['mainWidget']['id_header'])) {
                        /**
                         * Taxonamy Expression Tag Replace Procces
                         */
                        $headerRowData = Arr::multidimensional_search($this->paramList, array('type' => 'header'));

                        $expressionTagReplace .= '<div class="mainWidget-config" data-taxonamy-id="' . $this->getTaxonamyTagExpression['mainWidget']['id_header'] . '"><p></p>';

                        $replacedExpVar = Str::htmltotext($this->getTaxonamyTagExpression['mainWidget']['body_header']);
                        foreach ($headerRowData['data'] as $indt => $valt) {
                            $replacedExpVar = str_ireplace('#' . $valt['META_DATA_CODE'] . '#', '<label class="' . $valt['META_DATA_CODE'] . ' 5">' . Mdwebservice::renderParamControl($this->methodId, $valt, 'param[' . $valt['META_DATA_CODE'] . ']', $valt['META_DATA_CODE'], $this->fillParamData) . '</label>', $replacedExpVar);
                        }

                        $expressionTagReplace .= $replacedExpVar;
                        $expressionTagReplace .= '</div>';

                        echo '<script type="text/javascript">';
                        echo  '$(function () { renderMainWidget_' . $this->methodId . '() });';
                        echo '</script>';
                    }
                }
                
                echo '<div class="ntr_fullhtml" style="width: 595pt">';
                    echo $htmlContent;
                echo '</div>';
            }

            ?>
            <div class="d-none" id="bp-window-render-<?php echo $this->methodId; ?>">
                <?php echo $notUseControls; ?>
            </div>
            <div class="d-none taxonamy-expression-tags">
                <?php echo $expressionTagReplace; ?>
            </div>
            <div id="bprocessCoreParam">
                <?php
                echo Form::hidden(array('name' => 'methodId', 'value' => $this->methodId));
                echo Form::hidden(array('name' => 'methodCode', 'value' => issetParam($this->methodRow['META_DATA_CODE'])));
                echo Form::hidden(array('name' => 'processSubType', 'value' => $this->processSubType));
                echo Form::hidden(array('name' => 'create', 'value' => ($this->processActionType == 'insert' ? '1' : '0')));
                echo Form::hidden(array('name' => 'responseType', 'value' => $this->responseType));
                echo Form::hidden(array('name' => 'wfmStatusParams', 'value' => isset($this->newStatusParams) ? $this->newStatusParams : ''));
                echo Form::hidden(array('name' => 'wfmStringRowParams', 'value' => isset($arrayToStrParam) ? $arrayToStrParam : ''));
                echo Form::hidden(array('id'   => 'openParams', 'value' => $this->openParams));
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

        </div>

        <?php
        if (isset($this->widgetConfig)) {
            (array) $replacedWidget = array();
            $ntrFileUniqId = getUID();
        ?>
            <div class="bp-template-table-cell-right" cell-right-inside-status="show">
                <div class="bp-template-table-cell-right-hidden" style="
                    float: right;
                    width: 10%;
                    position: absolute;
                    right: -35px;
                    margin-top: 10px;
                ">
                    <a href="javascript:;" onclick="cellRightSidebar_<?php echo $this->methodId; ?>(this)" type="button" class="btn btn-secondary btn-xs" style="
                        background-color: #616cf378 !important;
                        border: none;
                        font-weight: 700;
                        text-transform: uppercase;
                        color: #58573f;
                        float: right;
                        vertical-align: middle;
                        padding: 10px 5px;
                        "><i class="fa fa-chevron-right"></i></a>
                </div>
                <div class="bp-template-table-cell-right-inside" style="overflow: auto; overflow-x: hidden; height: 450px; width: 400px;">
                    <?php
                    if (isset($wfmAssignmentUsers)) {
                        echo $wfmAssignmentUsers;
                    }

                    if ($this->paramList) {
                        foreach ($this->paramList as $k => $row) {
                            if ($row['type'] == 'detail' && isset($this->widgetConfigPath) && $this->widgetConfigPath) {
                                foreach ($this->widgetConfigPath as $key => $wPath) {
                                    if (isset($this->widgetConfig[$wPath['id']]) && Arr::in_array_multi($row['code'], $this->widgetConfig[$wPath['id']]['rows'], 'PATH')) {
                                        foreach ($row['data'] as $ind => $val) {
                                            foreach ($this->widgetConfig[$wPath['id']]['rows'] as $kk => $vv) {
                                                if ($row['code'] == $vv['PATH'] && strpos($this->widgetConfig[$wPath['id']]['rows'][$kk]['BODY'], '#' . $val['META_DATA_CODE'] . '#') !== false) {
                                                    $controllBody = str_replace('"', "'", Mdwebservice::renderParamControl($this->methodId, $val, $row['code'] . '.' . $val['META_DATA_CODE'], $val['META_DATA_CODE'], null));
                                                    $this->widgetConfig[$wPath['id']]['rows'][$kk]['BODY'] = html_entity_decode(str_ireplace('#' . $val['META_DATA_CODE'] . '#', $controllBody, $this->widgetConfig[$wPath['id']]['rows'][$kk]['BODY']), ENT_QUOTES, 'UTF-8');
                                                }
                                            }
                                        }
                                        $replacedWidget[$wPath['id']] = json_encode($this->widgetConfig[$wPath['id']]['rows']);
                                    }
                                }
                            }
                        }
                    }

                    if (isset($this->getTaxonamyTagExpression) && isset($this->getTaxonamyTagExpression['mainWidget']['id_header']) && !empty($this->getTaxonamyTagExpression['mainWidget']['id_header'])) {
                        echo '<div class="card light bp-tmp-idcard-header-part" data-template-index="0" data-path-code="">'
                            . '<div class="card-header card-header-no-padding header-elements-inline">'
                            . '<div class="card-title"><i class="fa fa-credit-card"></i>'
                            . '<span class="caption-subject font-weight-bold uppercase">Иргэн</span> '
                            . '<span class="party-title-counter">(0)</span>'
                            . '</div>'
                            . '<div class="header-elements">'
                            . '<a href="javascript:;" onclick = "bpHeaderIDCardReadWtemplate(this, \'' . (isset($this->widgetExpression) ? urlencode(json_encode($this->widgetExpression)) : 'null') . '\', \'' . $this->methodId . '\');" title="И/үнэмлэх" class="btn btn-xs green" style="height: 23px"><i class="fa fa-credit-card"></i></a> '
                            . '<a href="javascript:;" style="height: 23px" onclick = "bpChangeCustomerInformation(this);" title="Мэдээлэл шинэчлэх" class="btn btn-xs btn-success citizenRef_' . $this->methodId . '"><i class="fa fa-refresh"></i></a> '
                            . '<a href="javascript:;" class="collapse float-right" style="background-repeat: no-repeat;"></a>'
                            . '</div>'
                            . '</div>'
                            . '<div class="card-body" style="min-height: 85px;">'
                            . '<div class="row">'
                            . '<div class="col-md-12 pr10 widget-party-container"></div>'
                            . '</div>'
                            . '</div>'
                            . '</div>';
                    }

                    if (isset($this->widgetConfigPath) && $this->widgetConfigPath) {
                        foreach ($this->widgetConfigPath as $key => $wPath) {
                            if (isset($this->widgetConfig[$wPath['id']]) && isset($replacedWidget[$wPath['id']]) && $wPath['function'] !== '') {
                                $cardUniqId = getUID();
                                echo '<div id="' . $cardUniqId . '">';
                                switch ($wPath['id']) {
                                    case 'widget_realestate':
                                    case 'widget_firearm':
                                    case 'widget_share':
                                    case 'widget_asset':
                                    case 'widget_auto':
                                    case 'widget_organization':
                                    case 'widget_none':
                                        echo '<script type="text/javascript">' . $wPath['function'] . '(\'' . $cardUniqId . '\', JSON.stringify(' . $replacedWidget[$wPath['id']] . '), \'' . $wPath['id'] . '\');</script>';
                                        break;
                                    default:
                                        echo '<script type="text/javascript">' . $wPath['function'] . '(\'' . $cardUniqId . '\', JSON.stringify(' . $replacedWidget[$wPath['id']] . '), JSON.stringify(' . json_encode($this->widgetExpression) . ') , JSON.stringify(' . json_encode($wPath) . ') , \'' . $wPath['id'] . '\');</script>';
                                        break;
                                }
                                switch ($wPath['id']) {
                                    case 'widget_realestate':
                                    case 'widget_firearm':
                                    case 'widget_share':
                                    case 'widget_asset':
                                    case 'widget_organization': ?>
                                        <div class="card light bp-tmp-realestate-part">
                                            <div class="card-header card-header-no-padding header-elements-inline">
                                                <div class="card-title">
                                                    <i class="fa fa-building"></i>
                                                    <span class="caption-subject font-weight-bold uppercase"><?php echo $wPath['text']; ?></span>
                                                </div>
                                                <div class="header-elements">
                                                    <div class="list-icons">
                                                        <a class="list-icons-item" data-action="collapse"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body <?php echo $wPath['id']; ?>_information"></div>
                                        </div>
                                        <script type="text/javascript">
                                            $(function() {
                                                $('.bp-tmp-realestate-input').on('keydown', function(e) {
                                                    var code = (e.keyCode ? e.keyCode : e.which);
                                                    if (code === 13) {
                                                        var _this = $(this);
                                                        var _parent = _this.closest('.bp-tmp-realestate-part');
                                                        var certificateNumber = _this.val();
                                                        var city = 'Улаанбаатар';
                                                        var district = 'Хан-Уул дүүрэг';
                                                        var soum = '3 дугаар хороо';
                                                        var street = 'Чингисийн өргөн чөлөө';
                                                        var area = '80 m2';
                                                        var room = '5';
                                                        var regnumber = '9011441051';

                                                        _parent.find('.bp-tmp-realestate-regnumber').text(regnumber);
                                                        _parent.find('.bp-tmp-realestate-address').text(city + ', ' + district + ', ' + soum + ', ' + street);
                                                        _parent.find('.bp-tmp-realestate-area').text(area);
                                                        _parent.find('.bp-tmp-realestate-room').text(room);

                                                        var _parentForm = _this.closest('form');

                                                        _parentForm.find("input[data-path='CERTIFICATE_NUMBER']").val(regnumber);
                                                        _parentForm.find("input[data-path='register']").val(certificateNumber);
                                                        _parentForm.find("input[data-path='CITY_NAME']").val(city);
                                                        _parentForm.find("input[data-path='DISTRICT_NAME']").val(district);
                                                        _parentForm.find("input[data-path='STREET_NAME']").val(soum);
                                                        _parentForm.find("input[data-path='addressLine2']").val(street);
                                                        _parentForm.find("input[data-path='ACTUAL_SIZE']").val(area);
                                                        _parentForm.find("input[data-path='roomCount']").val(room);
                                                    }
                                                });
                                            });
                                        </script>
                                        <style type="text/css">
                                            .<?php echo $wPath['id']; ?>_information table {
                                                table-layout: fixed;
                                                width: 340px !important;
                                                border: none;
                                            }

                                            .<?php echo $wPath['id']; ?>_information table tr {}

                                            .<?php echo $wPath['id']; ?>_information table tr td {
                                                width: 100px !important;
                                                border: none;
                                            }

                                            .<?php echo $wPath['id']; ?>_information table tr td input,
                                            .<?php echo $wPath['id']; ?>_information table tr td select {
                                                width: 120px !important;
                                            }
                                        </style>
                                    <?php
                                        break;
                                    case 'widget_auto': ?>
                                        <div class="card light bp-tmp-realestate-part">
                                            <div class="card-header card-header-no-padding header-elements-inline">
                                                <div class="card-title">
                                                    <i class="fa fa-building"></i>
                                                    <span class="caption-subject font-weight-bold uppercase">Тээврийн хэрэгсэл</span>
                                                </div>
                                                <div class="header-elements">
                                                    <div class="list-icons">
                                                        <a class="list-icons-item" data-action="collapse"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group row fom-row">
                                                    <div class="row">
                                                        <label class="col-md-5 col-form-label mt5">Улсын дугаар:</label>
                                                        <div class="col-md-7 pl5">
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Марк:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Үйлдвэрлэсэн он:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Зориулалт:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Өнгө:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Аралын дугаар:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Гэрчилгээний дугаар:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Гэрчилгээ олгосон огноо:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                    <label class="col-form-label col-md-5 pr0 pl0">Гаалийн мэдүүлгийн дугаар:</label>
                                                    <div class="col-md-7 pr0 pl5"></div>
                                                    <div class="clearfix w-100"></div>
                                                </div>
                                                <div class="clearfix w-100"></div>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(function() {
                                                $('.bp-tmp-realestate-input').on('keydown', function(e) {
                                                    var code = (e.keyCode ? e.keyCode : e.which);
                                                    if (code === 13) {
                                                        return;
                                                    }
                                                });
                                            });
                                        </script> <?php
                                                    break;
                                            }
                                            echo '</div>';
                                        }
                                    }
                                }

                                if (isset($this->widgetConfig['attach'])) {
                                    $bpAttachRender = (new Mddoc())->bpTemplateAttach($this->methodId, $this->bpTemplateId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId, $this->isEditMode);
                                    echo $bpAttachRender;
                                }
                                                    ?>
                    <!-- <div class="card light bp-tmp-realestate-part" id="ntrFile_<?php echo $ntrFileUniqId; ?>">
                        <div class="card-header card-header-no-padding header-elements-inline">
                            <div class="card-title">
                                <i class="fa fa-file"></i>
                                <span class="caption-subject font-weight-bold uppercase">Хавсралт</span>
                            </div>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fileSidebarRows">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.mainRowCount][]" class="form-control form-control-sm longInit" placeholder="">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.id][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.id">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.bookId][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.bookId">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.contentId][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.contentId">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.id][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.id">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileName][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileName">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileSize][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileSize">
                                <input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileExtension][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileExtension">
                                <span class="btn btn-xs btn-success fileinput-button mb5">
                                    <span>Файл сонгох</span>
                                    <input type="file" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.physicalPath][0][]" data-path='NTR_CONTENT_DV.physicalPath' onchange="changeServiceContentDvName(this)" style="width:100%">
                                </span>
                                <span data-path="physicalPath" class="word-wrap-service" href='javascript:;' style="margin-left: 2px;">...</span>
                                <input class="form-control" name="param[TNR_SERVICE_CONTENT_DV.description][0][]" type="text" style="max-width: 250px; margin-top: 0 !important" data-path="NTR_SERVICE_CONTENT_DV.description" placeholder="Тайлбар">
                                <a href="javascript:;" class="btn btn-xs btn-success float-right ml5" title="Нэмэх" onclick="addFileSidebarNotarity(this);">
                                    <i class="icon-plus3 font-size-12"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-xs btn-success float-right ml5" title="Сканнер" onclick="personNtrScanner_<?php echo $ntrFileUniqId; ?>(this);">
                                    <i class="fa fa-print"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-xs btn-success float-right" title="Вэбкамер" onclick="personWebNtrCamera_<?php echo $ntrFileUniqId; ?>(this);">
                                    <i class="fa fa-camera"></i>
                                </a>
                            </div>
                            <?php
                            if (isset($this->fillParamData) && !empty($this->fillParamData) && isset($this->sourceId)) {
                                $bpAttach = (new Mddoc())->bpAttachFiles($this->fillParamData, $this->sourceId, $ntrFileUniqId);
                                echo $bpAttach;
                            }
                            ?>
                        </div>
                    </div> -->
                    <script type="text/javascript">
                        window.pressed = function() {
                            var a = document.getElementById('aa');
                            fileLabel.innerHTML = "afwfawfw";
                        };

                        function changeServiceContentDvName(element) {
                            $(element).closest('.fileSidebarRows').find('span[data-path="physicalPath"]').empty().append($(element).context.files[0].name);
                        }

                        function addFileSidebarNotarity(elem) {
                            var getDiv = $(elem).parent().parent();
                            $(getDiv).append(
                                '<div class="mt5 fileSidebarRows">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.mainRowCount][]" class="form-control form-control-sm longInit" placeholder="">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.id][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.id">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.bookId][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.bookId">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.contentId][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.contentId">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.id][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.id">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileName][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileName">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileSize][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileSize">' +
                                '<input type="hidden" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileExtension][0][]" class="form-control form-control-sm longInit" placeholder="" data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.fileExtension">' +
                                '<span class="btn btn-xs btn-success fileinput-button mb5">' +
                                '<span>Файл сонгох</span>' +
                                '<input type="file" onchange="changeServiceContentDvName(this)" data-path="NTR_CONTENT_DV.physicalPath" name="param[NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.physicalPath][0][]" style="width:100%">' +
                                '</span>' +
                                '<span data-path="physicalPath" class="word-wrap-service" style="margin-left: 2px;">...</span>' +
                                '<input class="form-control" name="param[NTR_SERVICE_CONTENT_DV.description][0][]" type="text" style="max-width: 250px; margin-top: 0 !important" data-path="NTR_SERVICE_CONTENT_DV.description" placeholder="Тайлбар">' +
                                '<a href="javascript:;" class="btn btn-xs btn-danger float-right ml5" title="Устгах" onclick="removeNotarityFile(this);"><i class="icon-cross2 font-size-12"></i></a>' +
                                '<a href="javascript:;" class="btn btn-xs btn-success float-right ml5" title="Сканнер" onclick="personNtrScanner_<?php echo $ntrFileUniqId; ?>(this);">' +
                                '<i class="fa fa-print"></i>' +
                                '</a>' +
                                '<a href="javascript:;" class="btn btn-xs btn-success float-right" title="Вэбкамер" onclick="personWebNtrCamera_<?php echo $ntrFileUniqId; ?>(this);">' +
                                '<i class="fa fa-camera"></i>' +
                                '</a>' +
                                '</div>'
                            );
                            ntrFileProcessRowIndexSet();
                        }

                        function removeNotarityFile(element) {
                            $(element).parent().remove();
                            ntrFileProcessRowIndexSet();
                        }

                        function ntrFileProcessRowIndexSet() {
                            var el = $("#ntrFile_<?php echo $ntrFileUniqId; ?>").find('.card-body').children();
                            var len = el.length,
                                i = 0;
                            for (i; i < len; i++) {
                                var subElement = $(el[i]).find('input, select, textarea');
                                var slen = subElement.length,
                                    j = 0;
                                for (j; j < slen; j++) {
                                    var _inputThis = $(subElement[j]);
                                    var _inputName = _inputThis.attr('name');
                                    if (typeof _inputName !== 'undefined') {
                                        _inputThis.attr('name', _inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
                                    }
                                }
                            }
                        }

                        function personNtrScanner_<?php echo $ntrFileUniqId; ?>(elem) {
                            Core.blockUI({
                                boxed: true,
                                message: 'Loading...'
                            });

                            if ("WebSocket" in window) {
                                console.log("WebSocket is supported by your Browser!");
                                var ws = new WebSocket("ws://localhost:58324/socket");
                                var uniqueId = getUniqueId();

                                ws.onopen = function() {
                                    var currentDateTime = GetCurrentDateTime();
                                    ws.send('{"command":"get_scan_image", "dateTime":"' + currentDateTime + '", details: [{"key": "filename", "value": "' + uniqueId + '"}, {"key": "server", "value": "' + URL_APP + 'mddoceditor/vrClientScannerUpload' + '"}]}');
                                };

                                ws.onmessage = function(evt) {
                                    var received_msg = evt.data;
                                    var jsonData = JSON.parse(received_msg);
                                    PNotify.removeAll();

                                    if (jsonData.status == 'success') {

                                        var savedImg = 'storage/uploads/metavalue/photo_temp/original/' + uniqueId + '.jpeg';
                                        $(elem).closest('.fileSidebarRows').find('span[data-path="physicalPath"]').empty().append(uniqueId + '.jpeg');
                                        $(elem).closest('.fileSidebarRows').find('input[data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.physicalPath"]').val(savedImg);

                                        Core.initFancybox($('.person-photo-wrap'));

                                    } else {
                                        if (jsonData.description != null) {
                                            new PNotify({
                                                title: 'Error',
                                                text: jsonData.description,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                    }

                                    Core.unblockUI();
                                };

                                ws.onerror = function(event) {
                                    if (event.code != null) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Error',
                                            text: event.code,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }

                                    Core.unblockUI();
                                };

                                ws.onclose = function() {
                                    console.log("Connection is closed...");
                                    Core.unblockUI();
                                };

                            } else {

                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Error',
                                    text: 'WebSocket NOT supported by your Browser!',
                                    type: 'error',
                                    sticker: false
                                });

                                Core.unblockUI();
                            }
                        }

                        function personWebNtrCamera_<?php echo $ntrFileUniqId; ?>(elem) {
                            $.getScript(URL_APP + "assets/custom/addon/plugins/swfobject/swfobject.js").done(function() {
                                $.getScript(URL_APP + 'assets/custom/addon/plugins/webcam/scriptcam/scriptcam.js').done(function() {

                                    var dialogName = '#dialog-person-photo-webcam';
                                    if (!$(dialogName).length) {
                                        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                                    }

                                    $.ajax({
                                        type: 'post',
                                        url: 'mdprocess/bpTmpAddPhotoFromWebcam',
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function(data) {
                                            $(dialogName).empty().append(data.html);
                                            $(dialogName).dialog({
                                                cache: false,
                                                resizable: true,
                                                bgiframe: true,
                                                autoOpen: false,
                                                title: data.title,
                                                width: 800,
                                                height: 550,
                                                modal: true,
                                                close: function() {
                                                    $(dialogName).empty().dialog('destroy').remove();
                                                },
                                                buttons: [{
                                                        text: data.save_btn,
                                                        class: 'btn green-meadow btn-sm',
                                                        click: function() {

                                                            var savedImg = $('form#bpWebcam-form').find("input[name='base64Photo']").val();
                                                            $(elem).closest('.fileSidebarRows').find('span[data-path="physicalPath"]').empty().append(savedImg);
                                                            $(elem).closest('.fileSidebarRows').find('input[data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.physicalPath"]').val(savedImg);

                                                            $('.person-photo-wrap').empty().append(img).promise().done(function() {
                                                                Core.initFancybox($('.person-photo-wrap'));
                                                            });

                                                            $(dialogName).dialog('close');
                                                        }
                                                    },
                                                    {
                                                        text: data.close_btn,
                                                        class: 'btn blue-madison btn-sm',
                                                        click: function() {
                                                            $(dialogName).dialog('close');
                                                        }
                                                    }
                                                ]
                                            });
                                            $(dialogName).dialog('open');

                                            Core.unblockUI();
                                        },
                                        error: function() {
                                            alert("Error");
                                        }
                                    });
                                });
                            });
                        }
                    </script>
                    <?php /* if (isset($this->editNtrMode) && !$this->editNtrMode) { editable  */ ?>
                    <div class="card light bp-tmp-realestate-part">
                        <div class="card-header card-header-no-padding header-elements-inline">
                            <div class="card-title">
                                <i class="fa fa-money"></i>
                                <span class="caption-subject font-weight-bold uppercase">Төлбөр</span>
                            </div>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row fom-row">
                                <div class="row w-100">
                                    <label class="col-md-5 col-form-label mt5">Үйлчилгээний хөлс:</label>
                                    <div class="col-md-7 pl5">
                                        <input class="form-control widget-fee-amount bigdecimalInit" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="row w-100">
                                    <label class="col-md-5 col-form-label mt5">Бусад хөлс:</label>
                                    <div class="col-md-7 pl5">
                                        <input class="form-control widget-other-amount bigdecimalInit" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="row w-100">
                                    <label class="col-md-5 col-form-label mt5">Нийт:</label>
                                    <div class="col-md-7 pl5">
                                        <input class="form-control widget-amount-total bigdecimalInit" disabled="disabled" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function() {
                            $('.widget-fee-amount').on('change', function(e) {
                                var tval = pureNumber($(this).val());
                                $(this).closest('form').find('input[data-path="feeAmount"]').val(tval);

                                var tot = $(this).closest('form').find('.widget-other-amount').val() == '' ? 0 : pureNumber($(this).closest('form').find('.widget-other-amount').val());
                                $(this).closest('form').find('.widget-amount-total').val(pureNumberFormat(tval + tot));
                            });

                            $('.widget-other-amount').on('change', function(e) {
                                var tval = pureNumber($(this).val());
                                $(this).closest('form').find('input[data-path="otherAmount"]').val(tval);

                                var tot = $(this).closest('form').find('.widget-fee-amount').val() == '' ? 0 : pureNumber($(this).closest('form').find('.widget-fee-amount').val());
                                $(this).closest('form').find('.widget-amount-total').val(pureNumberFormat(tval + tot));
                            });

                            $('input[data-path="feeAmount"]').on('change', function(e) {
                                var tval = pureNumber($(this).val());
                                $(this).closest('form').find('.widget-fee-amount').val(tval);

                                var tot = $(this).closest('form').find('.widget-other-amount').val() == '' ? 0 : pureNumber($(this).closest('form').find('.widget-other-amount').val());
                                $(this).closest('form').find('.widget-amount-total').val(pureNumberFormat(tval + tot));
                            });

                            $('.bpTemplatemap-<?php echo $this->methodId; ?>').find('.taxonomy-highlight').each(function() {
                                var $this = $(this);
                                var $parent = $this.parents('p');
                                $parent.prev('p').find('span').addClass('label-taxonomy-highlight');
                            });
                        });
                    </script>
                    <?php /* } editable */ ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</div>

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

<style type="text/css">
    .selectionNodeList_search_<?php echo $this->methodId; ?> {
        border: 1px solid #45b6ad !important;
        width: 100% !important;
        float: left !important;
    }
    
    #bp-window-<?php echo $this->methodId; ?> .meta-toolbar.is_stuck {
        z-index: 1 !important;
        top: 65px !important;
    }

    .fileinput-button {
        float: left;
        position: relative;
        overflow: hidden;
    }

    .fileinput-button input {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
        font-size: 200px;
        direction: ltr;
        cursor: pointer;
    }

    .mainbp-window-<?php echo $this->methodId; ?>>li>a>span {
        cursor: pointer;
        position: absolute;
        right: -9px;
        color: #999;
    }

    .mainbp-window-<?php echo $this->methodId; ?>>li>a>span.subtab {
        display: none;
    }

    .mainbp-window-<?php echo $this->methodId; ?>>li:hover>a>span.subtab {
        display: inline-block;
    }

    .mainbp-window-<?php echo $this->methodId; ?>>li>a:focus,
    .mainbp-window-<?php echo $this->methodId; ?>>li>a:hover {
        background: inherit !important;
    }

    .mainbp-window-<?php echo $this->methodId; ?>>li {
        float: left;
    }

    .meta-toolbar-<?php echo $this->methodId; ?> {
        background: #FFF;
        position: fixed;
        right: 0;
        left: 0;
    }

    ul.mainbp-window-<?php echo $this->methodId; ?> {
        position: relative;
        top: 30px;
    }


    .meta-toolbar-<?php echo $this->methodId; ?> {
        margin-top: -10px !important;
        padding: 10px 15px 0 100px !important;
        z-index: 9;
    }

    ul.meta-toolbar-<?php echo $this->methodId; ?> {
        margin-top: 15px !important;
        padding: 10px 10px 0 100px !important;
        z-index: 1;
    }

    #bp-window-<?php echo $this->methodId; ?> {
        padding-top: 22px;
    }

    /* Fixes for IE < 8 */

    @media screen\9 {
        .fileinput-button input {
            filter: alpha(opacity=0);
            font-size: 100%;
            height: 100%;
        }
    }

    .word-wrap-service {
        float: right !important;
        width: 70%;
        word-wrap: break-word;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 6px 0;
        color: rgb(105, 153, 204);
    }

    .bp-template-mode div.select2-container-multi {
        /*width: 710px;*/
    }

    .bp-template-mode .form-control-sm {
        border: 1px solid transparent;
        border-bottom: 1px solid #d0d0d0;
        font-size: 12px !important;
    }

    .bp-template-mode .dropdownInput {
        border: 1px solid transparent !important;
    }

    .bp-template-mode .form-control .select2-choice,
    .bp-template-mode .form-control .select2-choices {
        border: 1px solid transparent !important;
        border-bottom: 1px solid #d0d0d0 !important;
    }

    .bp-template-mode .select2-container .select2-choice .select2-arrow {
        border-left: 1px solid transparent !important;
    }

    .bp-template-wrap {
        /*background: url(<?php echo URL; ?>assets/core/global/img/ntr_background.jpg) no-repeat left center fixed;*/
        background-color: #F0F0F0;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    .bp-template-table-cell-right {
        background-color: transparent;
        padding-top: 20px;
        margin-left: -100px;
        position: fixed;
    }

    .bp-template-table-cell-left {
        width: 75%;
    }

    .bp-template-wrap .card {
        background-color: #616cf378 !important;
    }

    .bp-template-wrap .form-control {
        background-color: rgba(255, 255, 255, 0.8);
    }

    .bp-template-wrap .bp-template-table-cell-right-inside .card.light {
        margin-bottom: 6px;
        border: none;
    }

    .hidden-important {
        display: none !important;
    }

    .selectedRowData<?php echo $this->methodId; ?> {
        background-color: #FFF;
        cursor: default !important;
    }

    .selectionNodeList-jtree-<?php echo $this->methodId; ?> {
        overflow: auto;
        z-index: 101;
        /*position:absolute;*/
        border-right: 1px solid #CCC;
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
        max-height: 350px;
        padding-bottom: 10px;
        background: #FFF;
    }

    .selectionNodeList-jtree-<?php echo $this->methodId; ?>.jstree-container-ul {
        background: #FFF !important;
    }

    .search-tree-<?php echo $this->methodId; ?>>span {
        float: left;
        margin-right: 5px;
        margin-left: 5px;
        padding-top: 5px;
        font-size: 12px;
    }

    .search-tree-<?php echo $this->methodId; ?> {
        background: #FFF !important;
        padding-top: 6px;
        padding-bottom: 2px;
        border-bottom: 1px solid #ccc;
        padding-left: 8px;
        padding-right: 8px;
        font-size: 12px;
    }

    .search-tree-<?php echo $this->methodId; ?>>input {
        border-radius: 0 !important;
        width: 100% !important;
    }

    .groupSelectionNodeId_<?php echo $this->methodId ?> {
        width: 100%;
        position: inherit;
    }

    .padding-1 {
        padding: 1px;
        align-items: center;
        /*display: flex;*/
    }
</style>

<script type="text/javascript">
    
    var bp_window_<?php echo $this->methodId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
    var $taxonamyExpressionTags_<?php echo $this->methodId; ?> = bp_window_<?php echo $this->methodId; ?>.find('.taxonamy-expression-tags');
    var $otherCriteriaArr_<?php echo $this->methodId; ?> = <?php echo json_encode($this->otherCriteriaArr); ?>;
    var $mergedCriteriaArr_<?php echo $this->methodId; ?> = <?php echo json_encode($this->mergedCriteriaArr); ?>;
    var widgetExpressionGlobalStr_<?php echo $this->methodId; ?> = encodeURIComponent(JSON.stringify(<?php echo isset($this->widgetExpression) ? json_encode($this->widgetExpression) : 'null'; ?>));
    var widgetExpressionGlobal_<?php echo $this->methodId; ?> = JSON.parse(decodeURIComponent(widgetExpressionGlobalStr_<?php echo $this->methodId; ?>));
    var index<?php echo $this->methodId; ?> = 0;
    var bp_window_operator_<?php echo $this->methodId ?> = <?php echo json_encode(issetParamArray($this->operator)) ?>;

    $(function() {

        var $height = $(window).height() - 210;
        $('.bp-template-table-cell-right-inside', "div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']").attr('style', 'height: ' + $height + 'px; overflow: auto; overflow-x: hidden; width: 400px; ');

        <?php echo isset($this->taxonamyScriptsEvent) ? $this->taxonamyScriptsEvent : ''; ?>
        <?php echo isset($this->taxonamyScriptsEventDtl) ? $this->taxonamyScriptsEventDtl : ''; ?>
        <?php echo isset($this->taxonamyScriptsEventKeyDtl) ? $this->taxonamyScriptsEventKeyDtl : ''; ?>

        bp_window_<?php echo $this->methodId; ?>.on("change", 'input, select, textarea', function() {
            
            var mainSelector = '',
                selectorOption = '',
                selectorOption2 = '',
                selSel = '',
                selInp = '';
            var _this = $(this),
                _thisName = typeof _this.attr('name') === 'undefined' ? _this.attr('data-path') : _this.attr('name'),
                groupPath = _this.closest('.detail-template-body').attr('data-dtl-template-path'),
                cindex = _this.closest('.detail-template-body-rows').index();

            _this.attr('value', _this.val());

            if (typeof groupPath === 'undefined') {
                return;
            }

            if (groupPath === 'mainWidget') {
                fillMainHeader_<?php echo $this->methodId; ?>(_this);
                return;
            }

            var $tagName = _this.prop('tagName').toLowerCase();
            var splitPaths = _thisName.split('.');
            var splitDataPaths = _this.attr('data-path').split('.');

            var $thisDataPathLastIndexVal = splitDataPaths[splitDataPaths.length - 1];
            var $thisNameLastIndexVal = splitPaths[splitPaths.length - 1];

            changeSelector_<?php echo $this->methodId ?>(_this, $tagName, groupPath, mainSelector, selectorOption, selectorOption2, selSel, selInp, splitPaths, splitDataPaths, $thisDataPathLastIndexVal, $thisNameLastIndexVal, cindex, _thisName, _this.val());
        });

        <?php
        if (isset($this->widgetConfig)) { ?>
            bp_window_<?php echo $this->methodId; ?>.find('div.bp-template-table-cell-left').children('div:first').css({
                'margin': '0 auto',
                'font-size': '14px',
                'background-color': '#fff',
                'margin-top': '30px',
                'margin-bottom': '30px',
                'padding': '0px 80px 80px 80px',
                'line-height': '28px'
            });
        <?php } else { ?>

            bp_window_<?php echo $this->methodId; ?>.find('div.bp-template-table-cell-left').children('div:first').css({
                'margin': '0 auto',
                'font-size': '14px',
                'line-height': '28px'
            });

            bp_window_<?php echo $this->methodId; ?>.find('div.bp-template-wrap').css('background', 'rgb(245, 245, 245)');

        <?php } ?>

        bp_window_<?php echo $this->methodId; ?>.on('mouseenter mouseleave', '.detail-template-body-rows, .detail-template-body-sub-rows', function(e) {
            var _this = $(this);

            if (_this.hasClass('detail-template-body-rows')) {
                if (e.type === 'mouseleave') {
                    _this.closest('.bp-template-table-cell-left').find('.detail-template-body-rows').removeAttr('style');
                    //                    _this.closest('.bp-template-table-cell-left').find('.detail-template-body-rows').find('.template-action-buttons:first').addClass('hidden');
                } else {
                    _this.closest('.bp-template-table-cell-left').find('.detail-template-body-sub-rows').removeAttr('style');
                    _this.css({
                        'background-color': 'rgb(187, 234, 230, 0.7)'
                    });
                    //                    _this.find('.template-action-buttons:first').removeClass('hidden');                
                }
            } else {
                if (e.type === 'mouseleave') {
                    _this.closest('.bp-template-table-cell-left').find('.detail-template-body-sub-rows').removeAttr('style');
                    //                    _this.closest('.bp-template-table-cell-left').find('.detail-template-body-sub-rows').find('.template-action-buttons').addClass('hidden');
                } else {
                    _this.closest('.bp-template-table-cell-left').find('.detail-template-body-rows').removeAttr('style');
                    _this.css({
                        'background-color': 'rgb(187, 234, 230, 0.7)'
                    });
                    //                    _this.find('.template-action-buttons').removeClass('hidden');                
                }
            }
        });

        bp_window_<?php echo $this->methodId; ?>.on("change", "select.linked-combo", function(e, isTrigger) {
            var element = this;
            var _this = $(element),
                attrToJson = '';
            e.stopPropagation();
            e.stopImmediatePropagation();

            if (isTrigger === "EDIT") {
                _this.addClass("linked-combo-worked");
            }

            if (typeof window['tax' + _this.attr('data-path') + 'ChangeFnc_<?php echo $this->uniqId ?>'] === 'function') {
                window['tax' + _this.attr('data-path') + 'ChangeFnc_<?php echo $this->uniqId ?>'](element);
            }

            var _outParam = _this.attr("data-out-param");
            var _outParamSplit = _outParam.split("|");

            if (_this.attr('data-path') === 'itemId') {
                _this.closest('form#wsForm').find('select[data-path="NTR_CUSTOMER_A_DV.dim1"]').removeAttr('disabled');
                _this.closest('form#wsForm').find('select[data-path="NTR_CUSTOMER_B_DV.dim1"]').removeAttr('disabled');
            }

            try {
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    var _inParams = "",
                        _cellSelect = '';
                    var $dataSelectionPath = selfParam.replace(".", "_");
                    if ($('.' + $dataSelectionPath + '_<?php echo $this->methodId ?>').length !== 0) {

                        var $lookupMetaDataId = $('.' + $dataSelectionPath + '_<?php echo $this->methodId ?>').attr('data-lookupparamid');
                        var $parentId = $('.' + $dataSelectionPath + '_<?php echo $this->methodId ?>').attr('data-lookup-parentid');
                        var $tagLk = $('.' + $dataSelectionPath + '_<?php echo $this->methodId ?>').attr('data-lookup-path');
                        var $parentElement = $('.' + $dataSelectionPath + '_<?php echo $this->methodId ?>').parent();
                        var $mainSelectorLk = $("select[data-path='" + selfParam + "']", '#bp-window-render-<?php echo $this->methodId ?>').first();

                        multiSelectionRender_<?php echo $this->methodId ?>($lookupMetaDataId, $parentId, $tagLk, $parentElement, $mainSelectorLk);

                    } else {
                        if (_this.closest('.detail-template-body-rows').length === 0) {
                            _cellSelect = bp_window_<?php echo $this->methodId; ?>.find('div.bp-template-table-cell-left').children('div:first').find("select[data-path='" + selfParam + "']");
                            /*
                             * 
                             */

                            var $splitThirdPath = selfParam.split('.');

                            if ($splitThirdPath.length > 3) {
                                var $parent = $('table[data-table-path="' + $splitThirdPath[0] + '.' + $splitThirdPath[1] + '.' + $splitThirdPath[2] + '"]');

                                var parentGroupIndex = $parent.closest('tr').index();
                                bpSetRowIndexDepthThree_<?php echo $this->methodId; ?>($parent, bp_window_<?php echo $this->methodId; ?>, $parent.closest('table[data-table-path="' + $splitThirdPath[0] + '.' + $splitThirdPath[1] + '"]').closest('tr').index(), parentGroupIndex);
                            }
                        } else {
                            var _cellSelect = _this.closest('.detail-template-body-rows').find("select[data-path='" + selfParam + "']");

                            if (_cellSelect.length === 0) {
                                _cellSelect = _this.closest('div').find("select[data-path='" + selfParam + "']");
                            }
                        }

                        if (_cellSelect.length === 0) {

                            var _cellInp = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + selfParam + "']");
                            if (_this.val().length > 0 && _cellInp.length > 0) {
                                if (_cellInp.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && _cellInp.attr("data-edit-value") === undefined) {
                                    if (isTrigger === undefined) {
                                        _cellInp = _cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row").find("input[data-path='" + selfParam + "']");
                                    } else {
                                        _cellInp = _cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("input[data-path='" + selfParam + "']");
                                    }
                                }
                                _cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("readonly disabled");
                                _cellInp.parent().find("button").removeAttr("disabled");
                            }

                        } else {
                            if (_cellSelect.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && _cellSelect.attr("data-edit-value") === undefined) {
                                if (isTrigger === undefined) {
                                    _cellSelect = _cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row").find("select[data-path='" + selfParam + "']");
                                } else {
                                    _cellSelect = _cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("select[data-path='" + selfParam + "']");
                                }
                            }
                            if (_cellSelect.length && typeof _cellSelect.attr("data-in-param") !== 'undefined') {
                                var _inParam = _cellSelect.attr("data-in-param");
                                var _inParamSplit = _inParam.split("|");
                                for (var j = 0; j < _inParamSplit.length; j++) {

                                    if (_this.closest('.detail-template-body-rows').find("[data-path='" + _inParamSplit[j] + "']").length === 0)
                                        var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                    else
                                        var _lastCombo = _this.closest('.detail-template-body-rows').find("[data-path='" + _inParamSplit[j] + "']");

                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                }
                            }

                            if (_inParams !== '') {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdobject/bpLinkedCombo',
                                    data: {
                                        processMetaDataId: '<?php echo $this->methodId; ?>',
                                        selfParam: selfParam,
                                        inputParams: _inParams,
                                        requestType: 'linkedCombo'
                                    },
                                    dataType: 'json',
                                    async: false,
                                    beforeSend: function() {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function(dataStr) {
                                        var $selectValArr = [];
                                        _cellSelect.each(function() {
                                            $selectValArr.push($(this).val());
                                        });

                                        _cellSelect.select2('destroy');
                                        if (_cellSelect.hasClass("select2")) {
                                            _cellSelect.select2('val', '');
                                            _cellSelect.select2('readonly', false).select2('enable');
                                        } else {
                                            _cellSelect.val('');
                                            _cellSelect.removeAttr('disabled readonly');
                                        }

                                        $("option:gt(0)", _cellSelect).remove();
                                        var comboData = dataStr[selfParam];

                                        _cellSelect.addClass("data-combo-set");
                                        if (typeof isEditMode_<?php echo $this->methodId; ?> !== 'undefined' && isEditMode_<?php echo $this->methodId; ?>) {
                                            _cellSelect.each(function($index, $val) {
                                                var _thisSelect = $(this);
                                                $.each(comboData, function() {
                                                    if (_thisSelect.attr("data-edit-value") == this.META_VALUE_ID) {
                                                        _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("selected", "selected").attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                    } else {
                                                        _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                    }
                                                });

                                                _thisSelect.val($selectValArr[$index]);
                                                if (_thisSelect.hasClass("select2")) {
                                                    _thisSelect.select2('val', $selectValArr[$index]);
                                                }
                                            });
                                        } else {
                                            _cellSelect.each(function($index, $val) {
                                                var _thisSelect = $(this);
                                                $.each(comboData, function() {
                                                    _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                });

                                                _thisSelect.val($selectValArr[$index]);
                                                if (_thisSelect.hasClass("select2")) {
                                                    _thisSelect.select2('val', $selectValArr[$index]);
                                                }
                                            });
                                        }

                                        _cellSelect.select2({
                                            allowClear: true,
                                            dropdownAutoWidth: true,
                                            closeOnSelect: false,
                                        });

                                        $('select[data-path="NTR_CUSTOMER_A_DV.dim1"]').select2({
                                            dropdownAutoWidth: true,
                                            escapeMarkup: function(markup) {
                                                return markup;
                                            }
                                        });
                                        $('select[data-path="NTR_CUSTOMER_B_DV.dim1"]').select2({
                                            dropdownAutoWidth: true,
                                            escapeMarkup: function(markup) {
                                                return markup;
                                            }
                                        });

                                        Core.unblockUI();
                                    },
                                    error: function() {
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
            } catch (err) {
                console.log('Комбоны хамаарал алдааны мэдээлэл: ' + err);
                Core.unblockUI();
            }
        });

    });

    function bpSetRowIndexDepthThree_<?php echo $this->methodId; ?>(elem, window, rowIndex, parentRowIndex) {
        var activeTRindex = (typeof rowIndex === 'undefined') ? (window.find('.bprocess-table-dtl > .tbody').find('.bp-detail-row.currentTarget').length > 0 ? window.find('.bprocess-table-dtl > .tbody').find('.bp-detail-row.currentTarget').index() : 0) : rowIndex;
        var $parentElement = $('.bprocess-table-dtl', elem).length ? $('.bprocess-table-dtl', elem) : elem;

        $parentElement.each(function() {
            var $tblThis = $(this);
            var isRows = true;
            if ($tblThis.closest(".bprocess-table-row").length > 0) {
                isRows = false;
            }

            $tblThis.find("tbody:eq(0) > tr").each(function(i) {
                var $rowThis = $(this);
                var $rowTable = $rowThis.closest('table').attr('data-table-path');

                $rowThis.find("input, select, textarea").each(function() {
                    var $inputThis = $(this);
                    var $tableThis = $inputThis.closest('table');
                    /* console.log('[' + activeTRindex + '][' + parentRowIndex + ']');*/
                    if ($rowTable === $tableThis.attr('data-table-path')) {
                        var $inputName = $inputThis.attr('name');
                        if (typeof $inputName !== 'undefined') {
                            if (isRows) {
                                if (/^(.*)(\[[0-9]+\])(\[[0-9]+\])(.*)$/.test($inputName) && $rowThis.closest('.bprocess-table-dtl').hasClass('bprocess-table-dtl')) {
                                    $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[[0-9]+\])(.*)$/, '$1[' + activeTRindex + '][' + parentRowIndex + ']$4'));
                                } else {
                                    $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + activeTRindex + '][' + parentRowIndex + ']$3'));
                                }
                            } else {
                                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
                            }
                        }
                    } else {
                        var $inputName = $inputThis.attr('name');
                        if (typeof $inputName !== 'undefined') {
                            if (isRows) {
                                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + activeTRindex + ']$3'));
                            } else {
                                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
                            }
                        }
                    }
                });
            });
        });
    }

    function bpSetFillFieldCriteria_<?php echo $this->methodId; ?>(element, $srcPath, $trgPath, __selectorObject) {
        try {
            var $splitSrcPath = $srcPath.split('.');
            var $splitTrgPath = $trgPath.split('.');
            var $trgLastPath = ($splitTrgPath[$splitTrgPath.length - 1]).toLowerCase();

            var $srcParamPath = $('input[data-path="' + $srcPath + '"]');
            var $trgParamPath = $('input[data-path="' + $trgPath + '"]');
            var $srcParamVal = $srcParamPath.val();

            if ($splitTrgPath.length > 1) {
                for (var $kindex = 0; $kindex < __selectorObject.length; $kindex++) {
                    if (__selectorObject[$kindex]['__path'] === $splitSrcPath[0]) {

                        var __mainSelectorLength = parseInt(__selectorObject[$kindex]['__selectorLength']);
                        var addinIndex = __mainSelectorLength - parseInt(__selectorObject[$kindex]['__length']);

                        var $trgFillPath = '',
                            $srcFillPath = '';

                        for (var $findex = 0; $findex < $splitTrgPath.length - 1; $findex++) {
                            $trgFillPath += ($trgFillPath === '') ? $splitTrgPath[$findex] : '.' + $splitTrgPath[$findex];
                        }

                        for (var $sindex = 0; $sindex < $splitSrcPath.length - 1; $sindex++) {
                            $srcFillPath += ($srcFillPath === '') ? $splitSrcPath[$sindex] : '.' + $splitSrcPath[$sindex];
                        }

                        var __trgDataTableSelector = 'table[data-table-path="' + $trgFillPath + '"]',
                            __srcDataTableSelector = 'table[data-table-path="' + $srcFillPath + '"]';

                        var $srcDataTableBody = $(__srcDataTableSelector + ' > tbody > tr');

                        var $srcTableLength = $srcDataTableBody.length + addinIndex;

                        for (var $aindex = 0, $k = 0; $aindex < $srcDataTableBody.length; $aindex++, $k++) {
                            var $trgDataTableBody = $(__trgDataTableSelector + ' > tbody > tr'),
                                $appendRow = $('span[data-dtl-template-path="' + $trgFillPath + '"] > .detail-template-body-rows'),
                                _parent = $(__trgDataTableSelector).parent();
                            var $changeRowHtml = $appendRow.html();

                            var $removeBtnAndPic =
                                '<div class="float-left notuniform-pic" style="margin-left: -72px;"><img src="assets/core/global/img/images.jpg" class="img-fluid rounded-circle imageSrc" style="border:1px #ccc solid;height:70px"></div>' +
                                ' <a href="javascript:;" title="Устгах" style="margin-right: -23px;" ' +
                                'onclick="templateDtlRemoveRow_<?php echo $this->methodId; ?>(this, \'' +
                                $splitTrgPath[0] + '\', ' +
                                ' \'<?php echo $this->uniqId; ?>\', \'1\')" class="btn btn-xs btn-danger float-right"><i class="fa fa-trash"></i>' +
                                '</a>';

                            $appendRow.find('.float-left:not(.notuniform-pic)').remove();
                            if ($trgDataTableBody.length < $srcTableLength) {
                                var $firstRow = $(__trgDataTableSelector + ' > tbody > tr:first').html();
                                if (!$appendRow.hasClass('add-removeBtn')) {
                                    $appendRow.empty().append($removeBtnAndPic + $changeRowHtml).promise().done(function() {
                                        $appendRow.addClass('add-removeBtn');
                                    });
                                }

                                $(__trgDataTableSelector + ' > tbody').append('<tr>' + $firstRow + '</tr>').promise().done(function() {
                                    $(__trgDataTableSelector + ' > tbody > tr:last').find('input, select').val('');
                                    $(__trgDataTableSelector + ' > tbody > tr:last').find('input, select').val('');
                                    $(__trgDataTableSelector + ' > tbody > tr:last').find('select').children().val('');

                                    if ($appendRow.length < $srcTableLength) {
                                        $('span[data-dtl-template-path="' + $trgFillPath + '"]').append('<p class="detail-template-body-rows"></p>').promise().done(function() {
                                            $('span[data-dtl-template-path="' + $trgFillPath + '"] .detail-template-body-rows:last').prepend($appendRow.html()).promise().done(function() {
                                                $('span[data-dtl-template-path="' + $trgFillPath + '"] .detail-template-body-rows:last').find('input, select').val('');
                                                $('span[data-dtl-template-path="' + $trgFillPath + '"] .detail-template-body-rows:last').find('input, select').val('');
                                                $('span[data-dtl-template-path="' + $trgFillPath + '"] .detail-template-body-rows:last').find('select').children().val('');

                                                trgTableFill_<?php echo $this->methodId ?>($splitSrcPath, $srcFillPath, $trgFillPath, $srcParamVal, $trgLastPath, $trgPath, $k, addinIndex, _parent);
                                            });
                                        });
                                    } else {
                                        if ($appendRow.length > $srcTableLength) {
                                            $('span[data-dtl-template-path="' + $trgFillPath + '"] .detail-template-body-rows:last').remove();
                                        }

                                        trgTableFill_<?php echo $this->methodId ?>($splitSrcPath, $srcFillPath, $trgFillPath, $srcParamVal, $trgLastPath, $trgPath, $k, addinIndex, _parent);
                                    }
                                });
                            } else {
                                if ($trgDataTableBody.length > $srcTableLength && __selectorObject[__selectorObject.length - 1]['__path'] === $splitSrcPath[0]) {
                                    var $appendLen = $trgDataTableBody.length - $srcTableLength;
                                    for (var $saindex = 0; $saindex < $appendLen; $saindex++) {
                                        $(__trgDataTableSelector + ' > tbody > tr:last').remove();
                                        $('span[data-dtl-template-path="' + $trgFillPath + '"] .detail-template-body-rows:last').remove();
                                    }
                                }
                            }

                            trgTableFill_<?php echo $this->methodId ?>($splitSrcPath, $srcFillPath, $trgFillPath, $srcParamVal, $trgLastPath, $trgPath, $k, addinIndex, _parent);
                        }
                    }
                }
            } else {
                $trgParamPath.val($srcParamVal);
                $trgParamPath.val($srcParamVal);
            }
        } catch (err) {
            console.log('bpSetFillFieldCriteria_: ' + err);
            Core.unblockUI();
        }
    }

    function trgTableFill_<?php echo $this->methodId ?>($splitSrcPath, $srcFillPath, $trgFillPath, $srcParamVal, $trgLastPath, $trgPath, $k, addinIndex, $parentElement) {

        var $el = $parentElement.find('.bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row');
        var $len = $el.length,
            i = 0;

        for (i; i < $len; i++) {
            var $subElement = $($el[i]).find('input, select, textarea');
            var $slen = $subElement.length,
                j = 0;
            for (j; j < $slen; j++) {
                var $inputThis = $($subElement[j]);
                var $inputName = $inputThis.attr('name');

                if (typeof $inputName !== 'undefined') {
                    $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
                }
            }
        }

        var $parentSelector = '',
            $loopIndex = 0,
            $exited = false,
            $trgKey = $k + addinIndex;
        var $pi = $splitSrcPath.length - 1;
        var $lindex = $pi;
        var $mainSrcGetPath = $splitSrcPath[0];
        var $srcGetPath = '';

        for (var $i = 0; $i < $lindex; $i++) {
            if (!$exited) {
                $srcGetPath = $splitSrcPath[0];

                if ($pi !== 0) {
                    for (var $ii = 1; $ii < $pi; $ii++) {
                        $srcGetPath += ($srcGetPath === '') ? $splitSrcPath[$ii] : '.' + $splitSrcPath[$ii];
                    }
                }

                $srcGetPath += '.' + $splitSrcPath[$lindex];
                $parentSelector = $($('table[data-table-path="' + $srcFillPath + '"]' + ' > tbody > tr:eq(' + $k + ')'));

                for (var $li = 0; $li < $loopIndex; $li++) {
                    $parentSelector = $parentSelector.closest('table').closest('tr');
                }

                $srcParamVal = $parentSelector.find('[data-path="' + $srcGetPath + '"]').val();

                var $fillSelector = $('span[data-dtl-template-path="' + $trgFillPath + '"] > .detail-template-body-rows:eq(' + $trgKey + ')');
                var $fillTableSelector = $('table[data-table-path="' + $trgFillPath + '"] > tbody > tr:eq(' + $trgKey + ')');

                var $srcSignatureSelector = $('span[class="' + $mainSrcGetPath + '&signature detail-template-body detail-template-child-rows"]').find('.detail-template-body-rows:eq(' + $trgKey + ')');
                $srcSignatureSelector.each(function($sIndex, $sRow) {
                    var $signatureValue = $($sRow).find('input[data-path="' + $mainSrcGetPath + '.signature"]').val();
                    if ($trgPath === 'NTR_CUSTOMER_NOTE_DV.signature') {
                        $fillTableSelector.find('input[data-path="' + $trgPath + '"]').val($srcParamVal).val($signatureValue);
                    }
                });

                if ($srcParamVal) {

                    if ($trgLastPath === 'picture' && $srcParamVal !== 'assets/core/global/img/images.jpg') {

                        setTimeout(function() {
                            $fillSelector.find('.imageSrc').attr('src', 'data:image/jpeg;base64,' + $srcParamVal);
                        }, 900);

                    } else {
                        $fillSelector.find('input[data-path="' + $trgPath + '"]').val($srcParamVal).val($srcParamVal);
                    }

                    $fillTableSelector.find('input[data-path="' + $trgPath + '"]').val($srcParamVal).val($srcParamVal);

                    $exited = true;
                }

                $pi--;
                $loopIndex++;
            }
        }
    }

    function inArray_<?php echo $this->methodId; ?>(array, value) {
        var length = array.length;

        for (var i = 0; i < length; i++) {
            if (array[i] == value) {
                return true;
            }
        }

        return false;
    }

    function fillMainHeader_<?php echo $this->methodId; ?>($this) {
        var _thisName = $this.attr('data-path');
        var $tagName = $this.prop('tagName').toLowerCase();
        var selMainOption = $('#bp-window-render-<?php echo $this->methodId; ?>').find($tagName + '[data-path=\'' + _thisName + '\']');

        if ($tagName === 'select') {
            selMainOption.children().val($this.val());
        } else {
            selMainOption.val($this.val());
        }
    }

    if ($().contextMenu) {
        $.contextMenu({
            selector: 'ul.mainbp-window-<?php echo $this->methodId; ?> > li:not(".main-tab")',
            callback: function(key, opt) {
                if (key === 'app_tab_close') {
                    var _this = opt.$trigger;
                    multiBpTabCloseConfirm_<?php echo $this->methodId; ?>(_this.find('a'));
                }
            },
            items: {
                "app_tab_close": {
                    name: "Хаах",
                    icon: "times-circle"
                }
            }
        });
    }

    function multiBpTabCloseConfirm_<?php echo $this->methodId; ?>(elem) {
        var _li = elem.closest('li');
        _li.addClass('hidden');
    }

    function templateDtlAddRowParty_<?php echo $this->methodId; ?>(elem, uniqId, taxonamyObj, editMode) {
        var mainForm = elem.closest('form');
        var uniqElement = $('body').find('div#' + uniqId);

        if (typeof editMode === 'undefined') {
            if (taxonamyObj == '') {

                var $tempTaxonamyBody = 'temp-taxonamy-body_<?php echo $this->methodId; ?>';

                if (!$("#" + $tempTaxonamyBody).length) {
                    $('<div class="hide" id="' + $tempTaxonamyBody + '"></div>').appendTo("body");
                }

                var $mainSelector = $(elem).closest('span');
                var $taxBodySelector = $(elem).closest('p');
                var isHighlight = $taxBodySelector.hasClass('taxonomy-highlight');

                $("#" + $tempTaxonamyBody).empty().append($taxBodySelector.html()).promise().done(function() {
                    $("#" + $tempTaxonamyBody).find('a:first').remove();

                    var taxoHtml = $("#" + $tempTaxonamyBody).html(),
                        taxoMagrinTop = 'mt10',
                        taxoMagrinBtnTop = '';

                    if (isHighlight) {
                        $mainSelector.append('<p class="detail-template-body-rows taxonomy-highlight"></p>');
                    } else {
                        $mainSelector.append('<p class="detail-template-body-rows"></p>');
                    }

                    if ($("#" + $tempTaxonamyBody).find(' > table:has(thead)').length) {
                        var tblIndex = $mainSelector.children(':last').index();
                        $("#" + $tempTaxonamyBody).find(' > table > tbody > tr > td:first > .row-num-class').html(++tblIndex);

                        var htmlStr = $("#" + $tempTaxonamyBody).find(' > table > tbody').html();

                        taxoHtml = '<table border="1" style="margin-top: -1px;margin-left: 5px;border-top: none;">' + htmlStr + '</table>';
                        taxoMagrinTop = '';
                        taxoMagrinBtnTop = 'margin-top:-28px';
                    }

                    $mainSelector.children(':last').append('<div class="' + taxoMagrinTop + '"></div>' + taxoHtml + ' <a href="javascript:;" title="Устгах" style="margin-right: -23px;' + taxoMagrinBtnTop + '" onclick="templateDtlRemoveRow_<?php echo $this->methodId; ?>(this, \'' + $mainSelector.attr('data-dtl-template-path') + '\', \'' + uniqId + '\')" class="btn btn-xs btn-danger float-right"><i class="fa fa-trash"></i></a>');

                    $(elem).closest('span').children('.detail-template-body-rows:last').find('.select2-container').remove();
                    $(elem).closest('span').children('.detail-template-body-rows:last').find('select').select2({
                        allowClear: true,
                        closeOnSelect: false,
                        dropdownAutoWidth: true
                    });
                });
            } else {
                var taxonamyObjParse = JSON.parse(decodeURIComponent(taxonamyObj)),
                    tloop = 0,
                    tlength = taxonamyObjParse.length;

                for (tloop; tloop < tlength; tloop++) {
                    if (taxonamyObjParse[tloop].IS_ADD_FOLLOW === '1') {
                        bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH).append('<p class="detail-template-body-rows"></p>');
                        bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH).children(':last').append('<div class="mt10"></div>' + taxonamyObjParse[tloop].BODY + ' <a href="javascript:;" title="Устгах" style="margin-right: -20px;" onclick="templateDtlRemoveRow_<?php echo $this->methodId; ?>(this, \'' + taxonamyObjParse[0].PATH + '\', \'' + uniqId + '\')" class="btn btn-xs btn-danger float-right"><i class="fa fa-trash"></i></a>');
                    }
                }
            }

            var btn = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + $(elem).closest('.detail-template-body').attr('data-dtl-template-path') + '"]').find('button.bp-add-one-row:first');
            var btnClick = btn.attr('onclick').replace('bpAddMainRow_', 'bpAddMainRowNtr_');
            btn.attr('onclick', btnClick).trigger('click');
        }
    }

    function templateDtlAddExpressionRowParty_<?php echo $this->methodId; ?>(elem, uniqId, taxonamyObj) {
        var mainForm = elem.closest('form');
        if (uniqId != '') {
            var uniqElement = $('body').find('div#' + uniqId);
        }

        var taxonamyObjParse = JSON.parse(decodeURIComponent(taxonamyObj)),
            tloop = 0,
            tlength = taxonamyObjParse.length;
        var cindex = $(elem).closest('.detail-template-body-rows').index();

        for (tloop; tloop < tlength; tloop++) {
            if (taxonamyObjParse[tloop].IS_ADD_FOLLOW === '1') {
                
                var p = taxonamyObjParse[tloop].PATH_AS.match(/^(.*)@/);
                var spPath = taxonamyObjParse[tloop].PATH_AS.split('&');

                if (p !== null && typeof p[1] !== 'undefined') {
                    taxonamyObjParse[tloop].PATH = p[1];
                } else {
                    if (typeof spPath[1] !== 'undefined') {
                        taxonamyObjParse[tloop].PATH = taxonamyObjParse[tloop].PATH_AS.replace('&', '\\&');
                    }
                }

                if (taxonamyObjParse[tloop].EXPRESSION == '' || taxonamyObjParse[tloop].EXPRESSION == null) {
                    
                    bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH).append('<p class="detail-template-body-rows"></p>').promise().done(function() {
                        if (taxonamyObjParse[tloop].BODY) {
                            bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH).children(':last').append('<div class="mt10"></div>' + taxonamyObjParse[tloop].BODY + ' <a href="javascript:;" title="Устгах" style="margin-right: -20px;" onclick="templateDtlRemoveRow_<?php echo $this->methodId; ?>(this, \'' + taxonamyObjParse[0].PATH + '\', \'' + uniqId + '\')" class="btn btn-xs btn-danger float-right"><i class="fa fa-trash"></i></a>').promise().done(function() {
                                Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH));
                            });
                        } else {
                            bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH).children(':last').append('<span></span>').promise().done(function() {
                                Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH));
                            });
                        }
                    });
                } else {
                    
                    var $mainSelectorOption = bp_window_<?php echo $this->methodId; ?>.find('.' + taxonamyObjParse[tloop].PATH + ':first');
                    var $replaceTagHtml = '<p class="detail-template-body-rows">';
                            if (typeof taxonamyObjParse[tloop].IS_COPY_BUTTON !== 'undefined' && taxonamyObjParse[tloop].IS_COPY_BUTTON === '1') {
                                var taxonamyConvert = [];
                                taxonamyConvert.push({
                                    BODY: '',
                                    BTN: taxonamyObjParse[tloop].BTN,
                                    EXPRESSION: taxonamyObjParse[tloop].EXPRESSION,
                                    EXPRESSION_DTL: taxonamyObjParse[tloop].EXPRESSION_DTL,
                                    IS_ADD_FOLLOW: taxonamyObjParse[tloop].IS_ADD_FOLLOW,
                                    PATH: taxonamyObjParse[tloop].PATH,
                                    PATH_AS: taxonamyObjParse[tloop].PATH_AS,
                                    TAG: taxonamyObjParse[tloop].TAG,
                                    TAXONOMY_ID: taxonamyObjParse[tloop].TAXONOMY_ID,
                                    WIDGET_CODE: taxonamyObjParse[tloop].WIDGET_CODE,
                                });
                                if ('<?php echo $this->showCopyBtn ?>' === '1') {
                                    $replaceTagHtml += ' <a href="javascript:;" title="Хуулах" class="btn btn-xs btn-circle purple-plum float-left mt5 bp-tmp-idcard-part-add-sidebar" style="margin-left: -40px;width: 36px;" onclick="bpCopyPrevData(this, \''+ encodeURIComponent(JSON.stringify(taxonamyConvert)) +'\', \'1\', \''+ widgetExpressionGlobalStr_<?php echo $this->methodId; ?> +'\', \''+ taxonamyObjParse[tloop].PATH +'\')" ><i class="fa fa-copy"></i></a>'
                                }
                            }
                            $replaceTagHtml +=  $(elem).parent().find('select').prop('outerHTML') ;
                            $replaceTagHtml += ' <a href="javascript:;" title="Устгах" style="margin-right: -20px;" onclick="templateDtlRemoveRow_<?php echo $this->methodId; ?>(this, \'' + taxonamyObjParse[0].PATH + '\', \'' + uniqId + '\')" class="btn btn-xs btn-danger float-right tax-remove"><i class="fa fa-trash"></i></a>'
                        $replaceTagHtml +=  '</p>';
                        
                    $mainSelectorOption.append($replaceTagHtml).promise().done(function () {

						$mainSelectorOption.children(':last').find('select').select2({
							allowClear: true,
							dropdownAutoWidth: true,
							escapeMarkup: function(markup) {
								return markup;
							}
						});

						$mainSelectorOption.children(':last').find('select').select2('val', '');

					});

                }
            }
        }

        if (!$(elem).hasClass('triggerClick')) {
            var $templatePath = $(elem).closest('.detail-template-body').attr('data-dtl-template-path');
            var btn = bp_window_<?php echo $this->methodId; ?>.find('div[data-parent-path="' + $templatePath + '"]').parent().find('button.bp-add-one-row:first');
            var btnClick = btn.attr('onclick').replace('bpAddMainRow_', 'bpAddMainRowNtr_');

            btn.attr('onclick', btnClick).addClass('triggerClick').trigger('click');
        }
    }

    function templateDtlDtlAddExpressionRowParty_<?php echo $this->methodId; ?>(elem, childDtlPath) {
        var $this = $(elem);
        var sumIndex = Number($this.closest('.detail-template-body-sub-rows').attr('data-sum-index'));

        $this.closest('.detail-template-body-sub-rows').attr('data-sum-index', (++sumIndex));

        var cindex = $this.closest('.detail-template-body-rows').index(),
            currentIndex = $(elem).closest('.detail-template-body-sub-rows').attr('data-sum-index');
        var __html = $this.parent().find('select').prop('outerHTML');

        var $mainSelector = $this.closest('.detail-template-body-sub-rows').parent();
        var $actionBtn = '<a href="javascript:;" title="Устгах" style="margin-right: -20px;" onclick="templateDtlRemoveRow_<?php echo $this->methodId; ?>(this, \'' + childDtlPath + '\', \'\')" class="btn btn-xs btn-danger float-right"><i class="fa fa-trash"></i></a>';

        if (typeof __html !== 'undefined') {
            $mainSelector.append('<p class="detail-template-body-sub-rows" ' +
                'data-parent-index="' + cindex + '" ' +
                'data-dtl-dtl-path="' + childDtlPath + '" ' +
                'data-index="' + currentIndex + '">' +
                $(elem).parent().find('select').prop('outerHTML') +
                $actionBtn +
                '</p>');

            $mainSelector.children(':last').find('select').select2({
                allowClear: true,
                dropdownAutoWidth: true,
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            $mainSelector.children(':last').find('select').select2('val', '');
        }

        if (!$(elem).hasClass('triggerClick')) {
            var firstGroupPath = childDtlPath.split('.');

            var secondTableBody = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + firstGroupPath[0] + '"]').find('table > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + childDtlPath + '"] > tbody > tr');
            var secondTableBodyContentLen = secondTableBody.length - 1;

            if (secondTableBodyContentLen < currentIndex) {
                var btn = secondTableBody.closest('table[data-table-path="' + firstGroupPath[0] + '.' + firstGroupPath[1] + '"]').parent().find('button[data-action-path="' + childDtlPath + '"]');
                btn = (btn.length > 1) ? $(btn[0]) : btn;
                var btnClick = btn.attr('onclick').replace('bpAddDtlRow_', 'bpAddDtlRowNtr_');
                btn.attr('onclick', btnClick).addClass('triggerClick').trigger('click');
            }
        }
    }

    function templateDtlDtlKeyAddExpressionRowParty_<?php echo $this->methodId; ?>(elem, childDtlPath) {
        var $this = $(elem);
        var paIndex = Number($this.closest('.detail-template-body-sub-rows').attr('data-parent-index'));
        var cindex = Number($this.closest('.detail-template-body-sub-rows').attr('data-index'));
        var sumIndex = Number($this.closest('.detail-template-body-subkey-rows').attr('data-sumkey-index'));

        $this.closest('.detail-template-body-subkey-rows').attr('data-sumkey-index', (++sumIndex));

        var currentIndex = $this.closest('.detail-template-body-subkey-rows').attr('data-sumkey-index');

        var __html = $this.parent().find('select').prop('outerHTML');

        var $mainSelector = $this.closest('.detail-template-body-subkey-rows').parent();
        var $actionBtn = ' <a href="javascript:;" title="Устгах" style="margin-right: -20px;" ' +
            'onclick="templateDtlKeyRemoveRow_<?php echo $this->methodId; ?>(this, \'' + childDtlPath + '\', \'\')" ' +
            'class="btn btn-xs btn-danger float-right"><i class="fa fa-trash"></i></a>';

        if (typeof __html !== 'undefined') {
            var $appendHtml = '<p class="detail-template-body-subkey-rows" ' +
                'data-parent-index="' + paIndex + '" ' +
                'data-dtl-dtl-path="' + childDtlPath + '" ' +
                'data-index="' + cindex + '" ' +
                'data-key-index="' + currentIndex + '" >' +
                $(elem).parent().find('select').prop('outerHTML') +
                $actionBtn +
                '</p>';

            $mainSelector.append($appendHtml).promise().done(function() {
                Core.initBPInputType($mainSelector);
            });

            $mainSelector.children(':last').find('select').select2({
                allowClear: true,
                dropdownAutoWidth: true,
                closeOnSelect: false,
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            $mainSelector.children(':last').find('select').select2('val', '');
        }

        if (!$this.hasClass('triggerClick')) {
            var firstGroupPath = childDtlPath.split('.');
            var thirdTableBody = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + firstGroupPath[0] + '"]').find('table > tbody:first > tr:eq(' + paIndex + ')').find('table[data-table-path="' + firstGroupPath[0] + '.' + firstGroupPath[1] + '"] > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + firstGroupPath[0] + '.' + firstGroupPath[1] + '.' + firstGroupPath[2] + '"] > tbody > tr');
            var $thirdTableBodyContentLen = thirdTableBody.length - 1;

            if ($thirdTableBodyContentLen < currentIndex) {
                // тухайн dtl-dtl н мөрийнхөө btn-г олж чадахгүй байсан тул өөрчлөлт орууллаа
                var btn = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + firstGroupPath[0] + '"]').find('table > tbody:first > tr:eq(' + paIndex + ')').find('table[data-table-path="' + firstGroupPath[0] + '.' + firstGroupPath[1] + '"] > tbody:first > tr:eq(' + cindex + ')').find('button[data-action-path="' + firstGroupPath[0] + '.' + firstGroupPath[1] + '.' + firstGroupPath[2] + '"]');

                btn = (btn.length > 1) ? $(btn[0]) : btn;
                var btnClick = btn.attr('onclick').replace('bpAddDtlRow_', 'bpAddDtlKeyRowNtr_');
                btn.attr('onclick', btnClick).attr('data-parent-index', cindex).addClass('triggerClick').trigger('click');
            }
        }
    }

    function templateDtlAddRowProperty_<?php echo $this->methodId; ?>(elem, tag, body, uniqId) {
        alert('Developer Mode :)');
        return;
    }

    function templateDtlRemoveRow_<?php echo $this->methodId; ?>(elem, path, uniqId, uniqType) {
        var tempInd = $(elem).closest('.detail-template-body-rows').index();
        var cindex = $(elem).closest('.detail-template-body-sub-rows').attr('data-index');
        var widgetCode = $(elem).closest('.detail-template-body').attr('data-dtl-template-widget');
        var dataUindex = tempInd;
        var $mainSelector = $(elem).closest('.bpTemplatemap > .tab-content > div.active');

        if ($(elem).closest('.detail-template-body-sub-rows').length) {
            dataUindex = tempInd + '' + cindex;
        }

        if (typeof uniqType !== 'undefined') {
            uniqId = $mainSelector.attr('data-metadata-id');
        }

        if (uniqId != '') {
            var uniqElement = $('body').find('div#' + uniqId);
            if (uniqElement.length) {
                uniqElement.find('.widget-party-container').find('span[data-uindex="' + dataUindex + '"]').remove();
                uniqElement.find('span.party-title-counter').text('(' + (uniqElement.find('.widget-party-container').children('span').length) + ')');
            }
        } else {
            var uniqElement = $('body').find('div[data-path-code="' + $(elem).closest('.detail-template-body').attr('data-dtl-template-path') + '"]');
            if (uniqElement.length) {
                uniqElement.find('.widget-party-container').find('span[data-uindex="' + dataUindex + '"]').remove();
                uniqElement.find('span.party-title-counter').text('(' + (uniqElement.find('.widget-party-container').children('span').length) + ')');
            }
        }

        if ($(elem).closest('.detail-template-body-sub-rows').length) {
            var pth = path.split('.');
            var dtlIndexReset = $(elem).closest('.detail-template-body-rows');

            if ($(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').length && widgetCode !== 'widget_none') {
                $(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').each(function() {
                    $(this).children('.detail-template-body-rows:eq(' + tempInd + ')').children('.detail-template-body-sub-rows:eq(' + cindex + ')').remove();
                });
            } else {
                $(elem).closest('.detail-template-body-sub-rows').remove();
            }

            bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + pth[0] + '"]').find('table > tbody:first > tr:eq(' + tempInd + ')').find('table[data-table-path="' + path + '"] > tbody:first > tr:eq(' + cindex + ')').remove();

            dtlIndexReset.find('.detail-template-body-sub-rows').each(function(ke, va) {
                if (ke === 0) {
                    $(this).attr('data-sum-index', (--dtlIndexReset.find('.detail-template-body-sub-rows').length));
                }

                $(this).attr('data-index', ke);
            });
        } else {
            if ($(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').length && widgetCode !== 'widget_none') {
                $(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').each(function() {
                    $(this).children('.detail-template-body-rows:eq(' + tempInd + ')').remove();
                });
            } else {
                $(elem).closest('.detail-template-body-rows').remove();
            }

            if (typeof uniqType !== 'undefined') {

                $mainSelector.find('div[data-section-path="' + path + '"]').find('table > tbody:first > tr:eq(' + tempInd + ')').remove();
                bpSetRowIndex($mainSelector);
            } else {
                bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + path + '"]').find('table > tbody:first > tr:eq(' + tempInd + ')').remove();
            }
        }
    }

    function templateDtlKeyRemoveRow_<?php echo $this->methodId; ?>(elem, path, uniqId) {
        var ptempInd = $(elem).closest('.detail-template-body-rows').index();
        var tempInd = $(elem).closest('.detail-template-body-subkey-rows').attr('data-index');
        var cindex = $(elem).closest('.detail-template-body-subkey-rows').attr('data-key-index');
        var widgetCode = $(elem).closest('.detail-template-body').attr('data-dtl-template-widget');
        var dataUindex = tempInd;

        if ($(elem).closest('.detail-template-body-subkey-rows').length) {
            dataUindex = ptempInd + '' + tempInd + '' + cindex;
        }

        if (uniqId != '') {
            var uniqElement = $('body').find('div#' + uniqId);
            if (uniqElement.length) {
                uniqElement.find('.widget-party-container').find('span[data-uindex="' + dataUindex + '"]').remove();
                uniqElement.find('span.party-title-counter').text('(' + (uniqElement.find('.widget-party-container').children('span').length) + ')');
            }
        } else {
            var uniqElement = $('body').find('div[data-path-code="' + $(elem).closest('.detail-template-body').attr('data-dtl-template-path') + '"]');

            if (uniqElement.length) {
                uniqElement.find('.widget-party-container').find('span[data-uindex="' + dataUindex + '"]').remove();
                uniqElement.find('span.party-title-counter').text('(' + (uniqElement.find('.widget-party-container').children('span').length) + ')');
            }
        }

        if ($(elem).closest('.detail-template-body-subkey-rows').length) {
            var pth = path.split('.');
            var dtlIndexReset = $(elem).closest('.detail-template-body-sub-rows');

            if ($(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').length && widgetCode !== 'widget_none') {
                $(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').each(function() {
                    $(this).children('.detail-template-body-rows:eq(' + ptempInd + ')')
                        .children('.detail-template-body-sub-rows:eq(' + tempInd + ')')
                        .children('.detail-template-body-subkey-rows:eq(' + cindex + ')').remove();
                    $(this).children('.detail-template-body-rows:eq(' + ptempInd + ')')
                        .children('.detail-template-body-subkey-rows:eq(' + cindex + ')').remove();
                });
            } else {
                $(elem).closest('.detail-template-body-subkey-rows').remove();
            }


            bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + pth[0] + '"]').find('table > tbody:first > tr:eq(' + ptempInd + ')').find('table[data-table-path="' + pth[0] + '.' + pth[1] + '"] > tbody:first > tr:eq(' + tempInd + ')').find('table[data-table-path="' + pth[0] + '.' + pth[1] + '.' + pth[2] + '"] > tbody > tr:eq(' + cindex + ')').remove();

            dtlIndexReset.find('.detail-template-body-subkey-rows').each(function(ke, va) {
                if (ke === 0) {
                    $(this).attr('data-sumkey-index', (--dtlIndexReset.find('.detail-template-body-subkey-rows').length));
                }
                $(this).attr('data-key-index', ke);
            });

        } else {
            if ($(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').length)
                $(elem).closest('.bp-template-wrap').find('span[data-dtl-template-widget="' + widgetCode + '"]').each(function() {
                    $(this).children('.detail-template-body-rows:eq(' + ptempInd + ')')
                        .children('.detail-template-body-sub-rows:eq(' + tempInd + ')')
                        .children('.detail-template-body-subkey-rows:eq(' + cindex + ')').remove();
                    $(this).children('.detail-template-body-rows:eq(' + ptempInd + ')')
                        .children('.detail-template-body-subkey-rows:eq(' + cindex + ')').remove();
                });
            else
                $(elem).closest('.detail-template-body-subkey-rows').remove();

            bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + path + '"]').find('table > tbody:first > tr:eq(' + tempInd + ')').remove();
        }
    }

    function bpAddMainRowNtr_<?php echo $this->methodId; ?>(elem, processId, rowId) {
        var _this = $(elem);
        var _parent;
        _parent = _this.closest('div.table-toolbar').parent().children(':last');
        var getTable = _parent.find('.bprocess-table-dtl:eq(0)');
        var getTableBody = getTable.find('> .tbody:first');

        $.ajax({
            type: 'post',
            url: 'mdcommon/renderBpDtlRow',
            data: {
                processId: processId,
                uniqId: <?php echo $this->uniqId; ?>,
                rowId: rowId
            },
            beforeSend: function() {
                Core.blockUI({
                    animate: true
                });
            },
            success: function(dataStr) {

                var $html = $('<div />', {
                    html: dataStr
                });
                $html.find('tr:eq(0)').addClass('added-bp-row bp-detail-row');

                if (typeof isEditMode_<?php echo $this->methodId; ?> !== 'undefined' && isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val('ADDED');
                }

                getTableBody.append($html.html()).promise().done(function() {
                    Core.initBPInputType(getTableBody);
                });
                bpSetRowIndex(_parent);
                Core.unblockUI();
            },
            error: function() {
                alert('Error');
            }

        });

        return;
    }

    function bpAddDtlRowNtr_<?php echo $this->methodId; ?>(elem, htmlStr) {
        var _this = $(elem);
        var _parent = _this.parent();
        var $table = _parent.find('table.table:first');
        $.ajax({
            type: 'post',
            url: 'mdcommon/cryptEncodeToDecodeByPost',
            data: {
                processId: '<?php echo $this->methodId; ?>',
                rowId: $table.attr('data-row-id'),
                string: htmlStr
            },
            beforeSend: function() {
                Core.blockUI({
                    animate: true
                });
            },
            success: function(dataStr) {
                var $html = $('<div />', {
                    html: dataStr
                });
                $html.find("tr:eq(0)").addClass("display-none");

                if (typeof isEditMode_<?php echo $this->methodId; ?> !== 'undefined' && isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val("ADDED");
                }
                $table.find('> tbody').append($html.html());

                var el = $table.find('> tbody > tr');
                var len = el.length,
                    i = 0;
                for (i; i < len; i++) {
                    $(el[i]).find("td:first > span").text(i + 1);
                }

                _parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").find("input:visible:first").focus();

                bpSetRowIndexDepth_<?php echo $this->methodId; ?>(_parent, bp_window_<?php echo $this->methodId; ?>, _parent.closest('tr').index());

                Core.initBPInputType(_parent.find(".bprocess-table-dtl.table:first > tbody > .bp-detail-row:last-child"));
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }

    function bpAddDtlKeyRowNtr_<?php echo $this->methodId; ?>(elem, htmlStr) {
        var $this = $(elem);
        var $parent = $this.parent();
        var $table = $parent.find('table.table:eq(0)');

        $.ajax({
            type: 'post',
            url: 'mdcommon/cryptEncodeToDecodeByPost',
            data: {
                processId: '<?php echo $this->methodId; ?>',
                rowId: $table.attr('data-row-id'),
                string: htmlStr
            },
            beforeSend: function() {
                Core.blockUI({
                    animate: true
                });
            },
            success: function(dataStr) {
                var $html = $('<div />', {
                    html: dataStr
                });
                $html.find("tr:eq(0)").addClass("display-none");

                if (isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val('ADDED');
                }
                $table.find('> tbody').append($html.html());

                var el = $table.find('> tbody > tr');
                var len = el.length,
                    i = 0;
                for (i; i < len; i++) {
                    $(el[i]).find("td:first > span").text(i + 1);
                }

                $parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child").find("input:visible:first").focus();

                var $splitPath = $this.attr('data-action-path').split('.'),
                    // three level group parent group-н индекс
                    parentGroupIndex = (typeof $this.attr('data-parent-index') !== 'undefined') ? $this.attr('data-parent-index') : $parent.closest('tr').index();

                bpSetRowIndexDepthThree_<?php echo $this->methodId; ?>($parent, bp_window_<?php echo $this->methodId; ?>, $parent.closest('[data-table-path="' + $splitPath[0] + '.' + $splitPath[1] + '"]').closest('.bp-detail-row').index(), parentGroupIndex);

                Core.initBPInputType($parent.find(".bprocess-table-dtl.table:first > .tbody > .bp-detail-row:last-child"));
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }

    function appendDetailKeyTaxonamyFucntion_<?php echo $this->methodId; ?>(tag, _this, hideParam, checkSignature, checkChildSignature) {
        var $this = $(_this),
            groupPath = $this.attr('data-path').split('.'),
            cindex = $this.closest('.detail-template-body-subkey-rows').attr('data-parent-index'),
            subIndex = $this.closest('.detail-template-body-subkey-rows').attr('data-index'),
            subKeyIndex = $this.closest('.detail-template-body-subkey-rows').attr('data-key-index'),
            relationTag = false,
            tagCode = tag.split('.');

        var spPath = tag.split('@');

        if (typeof spPath[1] !== 'undefined') {
            tag = spPath[0];
            relationTag = true;
            tagCode[1] = tagCode[1].split('@')[0];

            spPath[1] = spPath[1].replace('&', '\\&');
            var $mainSelector = bp_window_<?php echo $this->methodId; ?>.find('.' + spPath[1]).find('.detail-template-body-rows:eq(' + cindex + ')');

            if (hideParam !== 'hide') {
                if ($mainSelector.length === 0) {
                    bp_window_<?php echo $this->methodId; ?>.find('.' + spPath[1]).append('<p class="detail-template-body-rows"><span class="detail-template-body-sub-rows" data-parent-index="' + cindex + '" data-index="' + subIndex + '"><span></span></span></p>');
                } else {
                    var $secoundSelector = $mainSelector.children('.detail-template-body-sub-rows:eq(' + subIndex + ')').children('.detail-template-body-subkey-rows:eq(' + subKeyIndex + ')');
                    if ($secoundSelector.length) {
                        $secoundSelector.remove();
                    }

                    $mainSelector.append('<span class="detail-template-body-subkey-rows" data-parent-index="' + cindex + '" data-key-index="' + subKeyIndex + '" data-index="' + subIndex + '"><br><br><span></span></span>');
                }
            }

            var $mainSubkeySelector = $mainSelector.children('.detail-template-body-subkey-rows:eq(' + subKeyIndex + ')');
            $this = $mainSubkeySelector.children();

            if ($mainSubkeySelector.find('.detail-template-body-subkey-rows:eq(' + subKeyIndex + ')').length) {
                $mainSubkeySelector.find('.detail-template-body-subkey-rows:eq(' + subKeyIndex + ')').remove();
            }

            bp_window_<?php echo $this->methodId; ?>.find('.' + spPath[1]).append('<p class="detail-template-body-subkey-rows"><span></span></p>');

            if ($this.parent().children().children('span:last').length) {
                $this.parent().children().children('span:last').remove();
            }
        } else {
            if ($this.parent().children('span:last').length) {
                $this.parent().children('span:last').remove();
            }
        }

        if (hideParam !== 'hide') {
            if ($this.closest('.detail-template-body').attr('data-display-picture') === '1') {
                var $appendTaxHtml = $taxonamyExpressionTags_<?php echo $this->methodId; ?>.find('div[class="' + tagCode[2] + '-' + groupPath[0] + '-' + tagCode[0] + '.' + tagCode[1] + '"]').html();

                var $appendTaxConfigHtml = '<div class="clearfix w-100"></div>' +
                    '<span>' +
                    '<p></p>' +
                    '<div class="float-left" style="margin-left: -72px;">' +
                    '<img src="assets/core/global/img/images.jpg" class="img-fluid rounded-circle imageSrc" style="border:1px #ccc solid;height:70px">' +
                    '</div>' +
                    actionBtns_<?php echo $this->methodId; ?>(tagCode[0] + '.' + tagCode[1]) +
                    $appendTaxHtml +
                    '</span>';
                $this.parent().append($appendTaxConfigHtml).promise().done(function() {
                    Core.initBPInputType($this.parent());
                });
            } else {
                var signatureCode = tagCode[2].split('&');
                var $appendSignatureHtml = $taxonamyExpressionTags_<?php echo $this->methodId; ?>.find('div[class="' + signatureCode[1] + '-' + groupPath[0] + '-' + tagCode[0] + '.' + tagCode[1] + '"]').html();

                $this.parent().append('<div class="clearfix w-100"></div><span>' + $appendSignatureHtml + '</span>').promise().done(function() {
                    Core.initBPInputType($this.parent());
                });
            }
        }

        if (!checkSignature || !checkChildSignature) {
            var thirdTableBody = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + groupPath[0] + '"]').find('table > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + groupPath[0] + '.' + tagCode[0] + '"] > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + groupPath[0] + '.' + tagCode[0] + '.' + tagCode[1] + '"] > tbody > tr');
            var thirdTableBodyContentLen = thirdTableBody.length - 1;

            if (thirdTableBodyContentLen < subKeyIndex) {
                var btn = thirdTableBody.closest('table[data-table-path="' + groupPath[0] + '.' + tagCode[0] + '.' + tagCode[1] + '"]').parent().find('button[data-action-path="' + groupPath[0] + '.' + tagCode[0] + '.' + tagCode[1] + '"]');
                btn = (btn.length > 1) ? $(btn[0]) : btn;
                if (btn.attr('onclick').indexOf("bpAddDtlKeyRowNtr_")) {
                    var btnClick = btn.attr('onclick').replace('bpAddDtlRow_', 'bpAddDtlKeyRowNtr_');
                    btn.attr('onclick', btnClick);
                }

                btn.attr('data-parent-index', cindex);
                btn.trigger('click');
            }
        }

        $this.parent().children('span:last').find('div.select2-container').each(function() {
            var _thisSel2 = $(this);

            if (!_thisSel2.hasClass('select2-container-multi')) {
                _thisSel2.remove();
            }
        });

        setTimeout(function() {
            $this.parent().children('span:last').find('select').select2({
                allowClear: true,
                closeOnSelect: false,
                dropdownAutoWidth: true
            });
        }, 50);

        $this.parent().children('span:last').children(':first').remove();
    }

    function appendDetailTaxonamyFunction_<?php echo $this->methodId; ?>(tag, _this, dtlCombo, hideParam, checkSignature, checkChildSignature) {

        if ($(_this).closest('.detail-template-body-subkey-rows').length) {
            appendDetailKeyTaxonamyFucntion_<?php echo $this->methodId; ?>(tag, _this, hideParam, checkSignature, checkChildSignature);
            return;
        }

        var groupPath = $(_this).attr('data-path').split('.'),
            $this = $(_this),
            $parent = $this.parent(),
            relationTag = false,
            cindex = $(_this).closest('.detail-template-body-sub-rows').attr('data-parent-index'),
            subIndex = $(_this).closest('.detail-template-body-sub-rows').attr('data-index'),
            $sumIndex = $(_this).closest('.detail-template-body-sub-rows').attr('data-sum-index'),
            tagCode = tag.split('.');

        var spPath = tag.split('@');

        if (typeof spPath[1] !== 'undefined') {
            tag = spPath[0];
            relationTag = true;
            tagCode[1] = tagCode[1].split('@')[0];

            spPath[1] = spPath[1].replace('&', '\\&');

            var $mainSelector = bp_window_<?php echo $this->methodId; ?>.find('.' + spPath[1]);
            var $mainSelectorSubRows = $mainSelector.find('.detail-template-body-rows:eq(' + cindex + ')');

            if (hideParam !== 'hide') {
                if ($mainSelectorSubRows.length === 0) {
                    $mainSelector.append('<p class="detail-template-body-rows"><span class="detail-template-body-sub-rows" data-parent-index="' + cindex + '" data-index="' + subIndex + '"><span></span></span></p>');
                } else {
                    if ($mainSelectorSubRows.children('.detail-template-body-sub-rows:eq(' + subIndex + ')').length) {
                        $mainSelectorSubRows.children('.detail-template-body-sub-rows:eq(' + subIndex + ')').remove();
                    }
                    $mainSelectorSubRows.append('<span class="detail-template-body-sub-rows" data-parent-index="' + cindex + '" data-index="' + subIndex + '"><br><br><span></span></span>');
                }
            }

            $this = $mainSelector.children('.detail-template-body-rows:eq(' + cindex + ')').children('.detail-template-body-sub-rows:eq(' + subIndex + ')').children();
            $parent = $this.parent();

            if ($parent.children().children('span:last').length) {
                $parent.children().children('span:last').remove();
            }
        } else {
            if ($parent.children('span:last').length) {
                $parent.children('span:last').remove();
            }
        }

        var $findedHtml = $taxonamyExpressionTags_<?php echo $this->methodId; ?>.find('.' + tagCode[1] + '-' + groupPath[0] + '-' + tagCode[0]).html();

        if (hideParam !== 'hide') {
            if ($this.closest('.detail-template-body').attr('data-display-picture') === '1') {
                $parent.append('<div class="clearfix w-100"></div>' +
                    '<span>' +
                    '<p></p>' +
                    '<div class="float-left" style="margin-left: -72px;">' +
                    '<img src="assets/core/global/img/images.jpg" class="img-fluid rounded-circle imageSrc" style="border:1px #ccc solid;height:70px">' +
                    '</div>' +
                    actionBtns_<?php echo $this->methodId; ?>(tagCode[0]) +
                    $findedHtml +
                    '</span>').promise().done(function() {
                    Core.initBPInputType($parent);
                });
            } else {
                $parent.append('<div class="clearfix w-100"></div><span>' + $findedHtml + '</span>').promise().done(function() {
                    Core.initBPInputType($parent);
                });
            }
        }

        if (checkSignature > 0 || checkChildSignature > 0) {

            var secondTableBody = bp_window_<?php echo $this->methodId; ?>.find('div[data-section-path="' + groupPath[0] + '"]').find('table > tbody:first > tr:eq(' + cindex + ')').find('table[data-table-path="' + groupPath[0] + '.' + tagCode[0] + '"] > tbody > tr');
            var secondTableBodyContentLen = secondTableBody.length - 1;

            if (secondTableBodyContentLen < subIndex) {
                var btn = secondTableBody.closest('table[data-table-path="' + groupPath[0] + '.' + tagCode[0] + '"]').parent().find('button[data-action-path="' + groupPath[0] + '.' + tagCode[0] + '"]');
                btn = (btn.length > 1) ? $(btn[0]) : btn;
                if (btn.attr('onclick').indexOf("bpAddDtlRowNtr_")) {
                    var btnClick = btn.attr('onclick').replace('bpAddDtlRow_', 'bpAddDtlRowNtr_');
                    btn.attr('onclick', btnClick);
                }

                btn.trigger('click');
            }
        }

        $parent.children('span:last').find('div.select2-container').each(function() {
            var _thisSel2 = $(this);

            if (!_thisSel2.hasClass('select2-container-multi')) {
                _thisSel2.remove();
            }
        });

        if (dtlCombo != '' && !relationTag) {
            var $selectorBodySubRow = $this.closest('.detail-template-body-sub-rows');

            if ($selectorBodySubRow.children('.detail-template-body-subkey-rows').length) {
                $selectorBodySubRow.children('.detail-template-body-subkey-rows').remove();
            }

            var dtlComboSplit = dtlCombo.split('.');
            var $appendHtml = '<p class="detail-template-body-subkey-rows ml20" ' +
                'data-parent-index="' + cindex + '" ' +
                'data-dtl-dtl-path="' + dtlComboSplit[0] + '.' + dtlComboSplit[1] + dtlComboSplit[2] + '" ' +
                'data-index="' + subIndex + '" data-sum-index="' + $sumIndex + '" ' +
                'data-key-index="0" data-sumkey-index="0">' +
                '<a href="javascript:;" ' +
                'onclick="templateDtlDtlKeyAddExpressionRowParty_<?php echo $this->methodId; ?>(this, \'' + dtlComboSplit[0] + '.' + dtlComboSplit[1] + '.' + dtlComboSplit[2] + '\')" class="btn btn-xs purple-plum btn-circle float-left bp-tmp-idcard-part-add-sidebar mt5" style="margin-left: -23px;">&nbsp;<i class="icon-plus3 font-size-12"></i>&nbsp;' +
                '</a>' +
                '</p>';

            $selectorBodySubRow.append($appendHtml).promise().done(function() {

                $selectorBodySubRow.children('.detail-template-body-subkey-rows').append($("select[data-path='" + dtlCombo + "']").first().clone()).promise().done(function() {

                    Core.initBPInputType($selectorBodySubRow);

                    $selectorBodySubRow.find('select').select2({
                        allowClear: true,
                        closeOnSelect: false,
                        dropdownAutoWidth: true,
                        escapeMarkup: function(markup) {
                            return markup;
                        }
                    });

                    $selectorBodySubRow.children('.detail-template-body-subkey-rows').find('select').select2('val', '');
                });

            });

        } else {
            $parent.find('select').select2({
                allowClear: true,
                closeOnSelect: false,
                dropdownAutoWidth: true,
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
        }
    }

    function appendTaxonamyFunction_<?php echo $this->methodId; ?>(tag, _this, dtlCombo, hideParam) {
        var checkSignature = tag.indexOf("signature");
        var checkChildSignature = tag.indexOf("childSignature");

        if ($(_this).closest('.detail-template-body-sub-rows').length) {
            appendDetailTaxonamyFunction_<?php echo $this->methodId; ?>(tag, _this, dtlCombo, hideParam, checkSignature, checkChildSignature);
            return;
        }

        var groupPath = $(_this).attr('data-path').split('.'),
            $this = $(_this),
            $parent = $this.parent(),
            relationTag = false,
            cindex = $this.closest('.detail-template-body-rows').index();

        try {
            var uniqElement = $('body').find('div[data-path-code="' + groupPath[0] + '"]');
            if (uniqElement.length) {
                uniqElement.find('.widget-party-container').find('span[data-uindex^="' + cindex + '"]').remove();
                uniqElement.find('span.party-title-counter').text('(' + (uniqElement.find('.widget-party-container').children('span').length) + ')');
            }
        } catch (e) {
            console.log(e);
        }

        var spPath = tag.split('@');
        /* console.log(spPath); */

        if (typeof spPath[1] !== 'undefined') {
            tag = spPath[0];
            relationTag = true;

            spPath[1] = spPath[1].replace('&', '\\&');
            var $mainSelectorSubRow = bp_window_<?php echo $this->methodId; ?>.find('.' + spPath[1]).find('.detail-template-body-rows:eq(' + cindex + ')');

            if ($mainSelectorSubRow.length) {
                $mainSelectorSubRow.remove();
            }

            bp_window_<?php echo $this->methodId; ?>.find('.' + spPath[1]).append('<p class="detail-template-body-rows"><span></span></p>');

            if (hideParam !== 'hide') {
                $this = bp_window_<?php echo $this->methodId; ?>.find('.' + spPath[1]).children('.detail-template-body-rows:eq(' + cindex + ')').children(),
                    $parent = $this.parent();

            } else {
                return;
            }

            if ($parent.children().children('span:last').length) {
                $parent.children().children('span:last').remove();
            }
        } else {
            if ($parent.children('span:last').length) {
                $parent.children('span:last').remove();
            }
        }

        if ($this.closest('.detail-template-body').attr('data-display-picture') === '1') {
            $taxonamyExpressionTags_<?php echo $this->methodId; ?>.find('.' + tag + '-' + groupPath[0]).find('select').trigger("select2-opening", [true]);
            $parent.append('<div class="clearfix w-100"></div><span><p></p><div class="float-left" style="margin-left: -72px;"><img src="assets/core/global/img/images.jpg" class="img-fluid rounded-circle imageSrc" style="border:1px #ccc solid;height:70px"></div>' + actionBtns_<?php echo $this->methodId; ?>('') + bp_window_<?php echo $this->methodId; ?>.find('.taxonamy-expression-tags').find('.' + tag + '-' + groupPath[0]).html() + '</span>').promise().done(function() {
                Core.initBPInputType($parent);
            });
        } else {
            $taxonamyExpressionTags_<?php echo $this->methodId; ?>.find('.' + tag + '-' + groupPath[0]).find('select').trigger("select2-opening", [true]);
            $parent.append('<div class="clearfix w-100"></div><span>' + $taxonamyExpressionTags_<?php echo $this->methodId; ?>.find('.' + tag + '-' + groupPath[0]).html() + '</span>').promise().done(function() {
                Core.initBPInputType($parent);
            });;
        }

        $parent.children('span:last').find('div.select2-container').each(function() {
            var _thisSel2 = $(this);
            _thisSel2.remove();
        });

        if (dtlCombo != '' && !relationTag) {
            var $selectorBodyRow = $this.closest('.detail-template-body-rows');

            if ($selectorBodyRow.children('.detail-template-body-sub-rows').length) {
                $selectorBodyRow.children('.detail-template-body-sub-rows').remove();
            }

            var dtlComboSplit = dtlCombo.split('.');

            var $appendHtml = '<p class="detail-template-body-sub-rows ml20" ' +
                'data-parent-index="' + cindex + '" ' +
                'data-dtl-dtl-path="' + dtlComboSplit[0] + '.' + dtlComboSplit[1] + '" ' +
                'data-index="0" data-sum-index="0">' +
                '<a href="javascript:;" onclick="templateDtlDtlAddExpressionRowParty_<?php echo $this->methodId; ?>(this, \'' + dtlComboSplit[0] + '.' + dtlComboSplit[1] + '\')" class="btn btn-xs purple-plum btn-circle float-left bp-tmp-idcard-part-add-sidebar mt5" style="margin-left: -23px;">&nbsp;<i class="icon-plus3 font-size-12"></i>&nbsp;' +
                '</a>' +
                '</p>';

            $selectorBodyRow.append($appendHtml).promise().done(function() {

                $selectorBodyRow.children('.detail-template-body-sub-rows').append($("select[data-path='" + dtlCombo + "']").first().clone()).promise().done(function() {
                    Core.initBPInputType($selectorBodyRow);

                    $selectorBodyRow.find('select').select2({
                        allowClear: true,
                        closeOnSelect: false,
                        dropdownAutoWidth: true,
                        escapeMarkup: function(markup) {
                            return markup;
                        }
                    });

                    $selectorBodyRow.children('.detail-template-body-sub-rows').find('select').select2('val', '');

                });

                $parent.children('span:last').children(':first').remove();
            });

        } else {
            $parent.find('select').select2({
                allowClear: true,
                closeOnSelect: false,
                dropdownAutoWidth: true,
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
        }

    }

    function appendTaxonamyFunctionMultiple_<?php echo $this->methodId; ?>(tag, _this, dtlCombo, hideParam, thisval) {
        var $this = $(_this),
            $thisAttr = $this.attr('data-path').replace(".", "_"),
            groupPath = $this.attr('data-path').split('.');

        var $mainSelector = $('.' + $thisAttr + '_<?php echo $this->methodId ?>').find('span[data-path-id="' + thisval + '"]');
        var $selectionListArr = $('input[data-path="selectionList_<?php echo $this->methodId; ?>"]').val();
        var $ticket = true;
        if (!$selectionListArr) {
            $ticket = true;
        } else {
            if ($selectionListArr.indexOf(thisval) > -1) {
                $ticket = false;
            }
        }

        if ($ticket) {
            $mainSelector.empty().append($taxonamyExpressionTags_<?php echo $this->methodId; ?>.find('.' + tag + '-' + groupPath[0]).html());

            $mainSelector.find('div.select2-container').each(function() {
                var _thisSel2 = $(this);
                _thisSel2.remove();
            });

            $mainSelector.find('select').removeAttr('name');
            $mainSelector.find('input').removeAttr('name');

            Core.initBPInputType($('.' + $thisAttr + '_<?php echo $this->methodId ?>').find('span[data-path-id="' + thisval + '"]'));
        }
    }

    function actionBtns_<?php echo $this->methodId; ?>(grouPath) {
        return '<div class="template-action-buttons btn-group btn-group-circle btn-group-xs btn-group-solid" style="margin-left: -9.8%; margin-top: 11%; float: left !important;">' +
            '<a href="javascript:;" style="padding: 2px 6px 2px 6px;" onclick="bpIDCardReadWtemplate(this, \'' + widgetExpressionGlobalStr_<?php echo $this->methodId; ?> + '\', \'' + grouPath + '\');" title="И/үнэмлэх" class="btn btn-xs green"><i class="fa fa-credit-card"></i></a> ' +
            '<a href="javascript:;" style="padding: 2px 6px 2px 6px;" onclick="bpChangeCustomerInformation(this, \'' + widgetExpressionGlobalStr_<?php echo $this->methodId; ?> + '\', \'' + grouPath + '\');" title="Мэдээлэл шинэчлэх" class="btn btn-xs btn-success citizenRef_<?php echo $this->methodId; ?>"><i class="fa fa-refresh"></i></a> ' +
            '<a href="javascript:;" style="padding: 2px 6px 2px 6px;" onclick="bpCitizenData(this, \'' + widgetExpressionGlobalStr_<?php echo $this->methodId; ?> + '\', \'' + grouPath + '\');" title="Х/уншуулах" class="btn btn-xs purple-plum"><i class="fa fa-credit-card"></i></a> ' +
            '<a href="javascript:;" style="padding: 2px 6px 2px 6px;" onclick="bpCopyPrevData(this, \'' + grouPath + '\', undefined, \''+ widgetExpressionGlobalStr_<?php echo $this->methodId; ?> +'\', \'' + grouPath + '\');" title="Мэдээлэл хуулах" class="btn btn-xs purple-plum"><i class="fa fa-copy"></i></a> ' +
            '</div>';
    }
    
</script>

<?php require getBasePath() . 'middleware/views/webservice/sub/script/main.php'; ?>

<script type="text/javascript">
    /*
    jQuery(document).ready(function () {
        Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>);
    });
    */
    <?php if (!$this->renderType) { ?>

        function renderAddBpTab($templateId, $metaDataId, $dmMetaDataId, prevUniqId, element) {
            var $thisStatus = $(element);
            $('a.citizenRef_<?php echo $this->methodId; ?>').trigger('click');
            if ($thisStatus.attr('bptab-status') === 'close') {
                var $params = {
                    metaDataId: $metaDataId,
                    isDialog: true,
                    valuePackageId: '',
                    workSpaceId: '',
                    workSpaceParams: '',
                    wfmStatusParams: '',
                    addonJsonParam: '{"templateId":"' + $templateId + '"}',
                    dmMetaDataId: $dmMetaDataId,
                    signerParams: '',
                    openParams: '',
                    renderType: "html",
                    bpTemplateId: $templateId,
                    showCopyBtn: '1'
                };

                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/callMethodByMeta',
                    data: $params,
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function(data) {
                        $('#bp_template_tab_' + $templateId).empty().append(data.Html).promise().done(function() {
                            $thisStatus.attr('bptab-status', 'open');
                            $("div[data-bp-uniq-id='" + data.uniqId + "']").attr('data-relateduniqid', prevUniqId);

                            // for (var index = 0; index < $otherCriteriaArr_<?php echo $this->methodId; ?>.length; index++) { 
                            //     var $row = $otherCriteriaArr_<?php echo $this->methodId; ?>[index];
                            //     var __selectorObject = [], __length = 0, __beforePathLength = 0;

                            //     for (var $index = 0; $index < $mergedCriteriaArr_<?php echo $this->methodId; ?>.length; $index++) {
                            //         var __beforePathLength = $('table[data-table-path="'+ $mergedCriteriaArr_<?php echo $this->methodId; ?>[$index]['MAIN_SRC_PARAM_NAME'] +'"] > tbody > tr').length;
                            //         var $mainPath = $mergedCriteriaArr_<?php echo $this->methodId; ?>[$index]['MAIN_SRC_PARAM_NAME'].split('.');
                            //         var $startIndex = ($index == 0) ? 0 : __beforePathLength;
                            //         __length += __beforePathLength;
                            //         __selectorObject.push({'__path': $mainPath[0], '__selectorLength' : __length, '__length' : __beforePathLength, '__startIndex': $startIndex});
                            //     }

                            //     bpSetFillFieldCriteria_<?php echo $this->methodId; ?>(this, $row['SRC_PARAM_NAME'], $row['TRG_PARAM_NAME'], __selectorObject);
                            // }

                            Core.unblockUI();
                        });
                    },
                    error: function() {
                        alert("Error");
                        Core.unblockUI();
                    }
                });
            }
        }

        $(function() {

            $('body').on('click', '.mainbp-window-<?php echo $this->methodId; ?> > li > a > span.maintab', function(e) {
                var $this = $(this);
                var $thisClosestLi = $this.closest('ul').find('li[bp-trg-meta="1"]');
                var $srcMetaDataId_<?php echo $this->methodId; ?> = $this.closest('a').attr('src-metadataid');
                var $bpTemplateId_<?php echo $this->methodId; ?> = $this.closest('a').attr('bp-templateid');
                var $openedTrgMetaDataId_<?php echo $this->methodId; ?> = [];

                $thisClosestLi.each(function(index, row) {
                    if (!$(row).hasClass('hidden')) {
                        $openedTrgMetaDataId_<?php echo $this->methodId; ?>.push($(row).attr('trg-metadataid'));
                    }
                });

                $.ajax({
                    type: 'post',
                    url: 'mddoc/callNtrBusinessProcessTemplate',
                    data: {
                        uniqId: '<?php echo $this->methodId ?>',
                        bpTemplateId: $bpTemplateId_<?php echo $this->methodId; ?>,
                        trgMetaDataId: $openedTrgMetaDataId_<?php echo $this->methodId; ?>,
                        srcMetaDataId: $srcMetaDataId_<?php echo $this->methodId; ?>
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function(data) {
                        if (!data.Data) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Холбогдох загвар олдсонгүй',
                                type: 'warning',
                                sticker: false
                            });
                            Core.unblockUI();

                            return;
                        }
                        var $dialogName = 'dialog-call-bpTemplate-<?php echo $this->methodId; ?>';
                        if (!$("#" + $dialogName).length) {
                            $('<div id="' + $dialogName + '"></div>').appendTo('body');
                        }
                        $("#" + $dialogName).empty().append(data.Html);
                        $("#" + $dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: data.Title,
                            width: 500,
                            height: 'auto',
                            modal: true,
                            close: function() {
                                $("#" + $dialogName).empty().dialog('destroy').remove();
                            },
                            buttons: [{
                                text: data.close_btn,
                                class: 'btn blue-madison btn-sm',
                                click: function() {
                                    $("#" + $dialogName).dialog('close');
                                }
                            }]
                        });
                        $("#" + $dialogName).dialog('open');

                        Core.unblockUI();
                    },
                    error: function() {
                        alert("Error");
                    }
                });
            });

            $('body').on('click', '.renderBpTemplate', function(e) {
                var $this = $(this),
                    $metadataId = $this.attr('data-metadataid'),
                    $tempateCode = $this.attr('data-templateCode');
                $this.remove();
                $('li[trg-metadataid="' + $metadataId + '_' + $tempateCode + '"]', '.mainbp-window-<?php echo $this->methodId; ?>').removeClass('hidden');
            });

            $('body').on('click', '.mainbp-window-<?php echo $this->methodId; ?> > li > a > span.subtab', function(e) {
                multiBpTabCloseConfirm($(this));
            });
        });

    <?php } else { ?>

        jQuery(document).ready(function() {
            enableBpDetailFilter(bp_window_<?php echo $this->methodId; ?>);
        });

    <?php }  ?>

    function callRenderBpTemplate_<?php echo $this->methodId; ?>(element, metaDataId) {}

    function bpSetRowIndexDepth_<?php echo $this->methodId; ?>(elem, window, rowIndex) {
        var activeTRindex = (typeof rowIndex === 'undefined') ? (window.find('.bprocess-table-dtl > .tbody').find('.bp-detail-row.currentTarget').length > 0 ? window.find('.bprocess-table-dtl > .tbody').find('.bp-detail-row.currentTarget').index() : 0) : rowIndex;
        var $parentElement = $('.bprocess-table-dtl', elem).length ? $('.bprocess-table-dtl', elem) : elem;

        $parentElement.each(function() {
            var $tblThis = $(this);
            var isRows = true;
            if ($tblThis.closest(".bprocess-table-row").length > 0) {
                isRows = false;
            }
            $tblThis.find(".tbody:eq(0) > .bp-detail-row").each(function(i) {
                var $rowThis = $(this);
                var $rowTable = $rowThis.closest('table').attr('data-table-path');

                $rowThis.find("input, select, textarea").each(function() {
                    var $inputThis = $(this);
                    var $inputName = $inputThis.attr('name');

                    if (typeof $inputName !== 'undefined') {
                        if (isRows) {
                            $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + activeTRindex + ']$3'));
                        } else {
                            $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
                        }
                    }
                });
            });
        });
    }
</script>

<?php echo $this->mainBpTab['tabEnd']; ?>