var IS_LOAD_ELECVIEW_SCRIPT = true;
var $cvlUniqHtmlContainerView = '';
var jqTreeData = [];
var $globalTree;

function elecScanView(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type) {

    var windowHeight = $(window).height();
    var newParamData = paramDataToObject(paramData);

    var $mainUrl = (typeof $type !== 'undefined' && $type == '3') ? 'mddoc/electronCvlViewLegal' : 'mddoc/electronViewLegal';
    $mainUrl = (typeof $type !== 'undefined' && $type == '6' || $type == '7') ? 'mddoc/electronEditLegal' : $mainUrl;
    $.ajax({
        type: 'post',
        url: $mainUrl,
        data: { selectedRow: selectedRow, type: $type, paramData: newParamData },
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            var $dialogName = 'dialog-erl-' + data.uniqId;
            var $treeViewId = typeof data.treeDvId !== 'undefined' ? data.treeDvId : '';

            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="dialog-after-save-close"></div>').appendTo('body');
            }

            var $dialog = $('#' + $dialogName);

            if (typeof $type !== 'undefined' && ($type == '2' || $type == '4' || $type == '6' || $type == '7') && typeof data.status !== 'undefined' && data.status !== 'success') {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });

                Core.unblockUI();
                return;
            }

            $dialog.empty().append(data.html).promise().done(function () {
                doneViewRender($dialog, selectedRow, paramData, $type, data.uniqId, newParamData, $treeViewId);
            });
            $dialog.dialog({
                cache: false,
                resizable: false,
                draggable: false,
                bgiframe: true,
                autoOpen: false,
                title: getConfigValue('CIVIL_OFFLINE_SERVER') === '1' ? 'Цахимжуулалт харах' : data.title,
                width: $(window).width(),
                height: windowHeight - 5,
                modal: true,
                position: { my: 'top', at: 'top+0' },
                closeOnEscape: isCloseOnEscape,
                open: function () {
                    disableScrolling();
                    $dialog.find('.erl-image-preview').css({ 'height': windowHeight - 140 });
                    $dialog.find('.ecl-height').css({ 'height': windowHeight - 222 });
                    $dialog.find('.erl-content-tbl > tbody > tr:eq(0)').click();
                },
                close: function () {
                    enableScrolling();

                    if (typeof $type !== 'undefined' && $type == '2') {
                        /*dataViewReload('1533714393827725');  */
                        $('#dv-search-1533714393827725').find('input[data-path="filterregisternumber"]').focus();
                        dataViewFirstColumnFocus('1533714393827725');
                    }

                    if (typeof $type !== 'undefined' && ($type == '4' || $type == '6' || $type == '7')) {
                        /*dataViewReload('1536131133813'); */
                        dataViewFirstColumnFocus('1536131133813');
                    }

                    if (typeof $type !== 'undefined' && $type == '3') {
                        /*dataViewReload('1536131133813'); */
                    }

                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {
                        text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });

}

function doneViewRender($selecterDialog, selectedRow, paramData, $type, $uniqId, newParamData, treeDvId) {

    var sentParams = { companyId: selectedRow.companyid, treeDvId: treeDvId };
    var $treeUrl = 'mddoc/electronViewTreeList';
    var $tree = $selecterDialog.find('.ecl-jstree-container');
    var isCivilRegister = false;
    var $selecterDialogId = $selecterDialog.attr('id');

    $globalTree = $tree;

    if ($tree.hasAttr('data-treedvid') && $tree.attr('data-treedvid') != '') {
        sentParams['treeDvId'] = $tree.attr('data-treedvid');

        if ($tree.hasAttr('data-inputparams') && $tree.attr('data-inputparams') != '') {
            sentParams['inputParams'] = $tree.attr('data-inputparams');
        }
    }

    if (typeof newParamData != 'undefined' && newParamData.hasOwnProperty('isCivilRegister')) {
        isCivilRegister = true;
    }

    if (typeof $type !== 'undefined' && ($type == '2' || $type == '4' || $type == '6' || $type == '7')) {
        sentParams = {
            companyId: selectedRow.civilid,
            parentId: '',
            paramData: paramData,
            postData: newParamData,
            selectedRow: selectedRow,
            type: $type,
            uniqId: $uniqId
        };
        $treeUrl = 'mddoc/electronViewTreeDataV2';
    }

    if (typeof $type !== 'undefined' && $type == '3') {
        sentParams = {
            companyId: selectedRow.civilid,
            parentId: '',
            paramData: paramData,
            postData: newParamData
        };
        $treeUrl = 'mddoc/electronViewTreeDataV3';
    }

    $.ajax({
        type: 'post',
        url: $treeUrl,
        data: sentParams,
        dataType: 'json',
        beforeSend: function () {
            $tree.text('Түр хүлээнэ үү...');
        },
        success: function (data) {
            $tree.data('jstree', false).empty().tree({
                onCreateLi: function (node, $li) {
                    var name = '';

                    if (node.isFile == 1) {
                        name = '<i class="fa fa-file text-info-800"></i> ';
                    }

                    var $title = $li.find('.jqtree-title');
                    $title.prepend(name);
                },
                data: data,
                autoOpen: true,
            });
            jqTreeData = data;
        }
    });

    $tree.on('tree.init', function () {
        var $element = $tree.find('.jqtree-title:eq(0)');
        var node = $tree.tree('getNodeByHtmlElement', $element);
        $tree.tree('selectNode', node);
    });

    $tree.on('tree.click', function (event) {

        if (event.node) {
            var $imagePanel = $('.erl-image-preview');
            var $companyKeyId = event.node.companyKeyId;
            var $selected_node = event.node;
            var ubegScanLink = CONFIG_URL + getConfigValue('ubegScanLink');

            if (typeof $selecterDialog.attr('data-verifer') !== 'undefined' && $selecterDialog.attr('data-verifer') === '1') {

                var $selectorMain = $selecterDialog.find('a[data-deleteid]');
                $selectorMain.show();
                $selectorMain.attr('data-deleteid', $selected_node.id);
                $selectorMain.attr('data-name', $selected_node.name);
                $selectorMain.attr('data-contentid', $selected_node.contentId);
            }

            if (event.click_event.ctrlKey) {

                var selected_node = event.node;
                $imagePanel.parent().find('span').hide();

                event.preventDefault();

                if ($tree.tree('isNodeSelected', selected_node)) {
                    $tree.tree('removeFromSelection', selected_node);
                    $imagePanel.find('#' + selected_node.id).remove();
                } else {
                    if (event.node.isFile == '1') {
                        var uid = getUniqueId(1);

                        if ($imagePanel.find('img.erl-single-image').length) {
                            $imagePanel.find('img.erl-single-image').attr('style', 'height: 265px;margin-left: 4px; margin-top: 6px;');
                        }
                        $imagePanel.append('<img id="' + selected_node.id + '" style="height: 265px;margin-left: 4px; margin-top: 6px;" src="' + ubegScanLink + '?scan_id=' + $companyKeyId + '&filename=' + event.node.physicalpath + '&uid=' + uid + '">');
                        $tree.tree('addToSelection', selected_node);
                    }
                }

            } else {

                $imagePanel.parent().find('span').show();
                var treeSelectedRows = $tree.tree('getSelectedNodes');
                for (var ei = 0; ei < treeSelectedRows.length; ei++) {
                    $tree.tree('removeFromSelection', treeSelectedRows[ei]);
                }

                if (typeof $type !== 'undefined' && ($type == '2' || $type == '4' || $type == '6' || $type == '7')) {
                    sentParams = {
                        companyId: selectedRow.ccid,
                        parentId: ''
                    };
                    $treeUrl = 'mddoc/electronViewTreeDataV2';
                    $companyKeyId = event.node.folderid;
                }

                if (typeof $type !== 'undefined' && ($type == '6' || $type == '7')) {

                    $companyKeyId = event.node.folderid;
                    if (typeof event.node.contentid !== 'undefined') {
                        $('body').find('.hide_' + $uniqId).show();
                        $('input[data-path="contentId_' + $uniqId + '"]').attr('value', event.node.contentid);
                        $('input[data-path="nodeId_' + $uniqId + '"]').attr('value', event.node.id);

                    } else {
                        $('body').find('.hide_' + $uniqId).hide();
                    }
                }

                if (event.node.isFile == '1') {
                    var imgHeight = $(window).height();
                    var uid = getUniqueId(1);
                    $imagePanel.empty().append('<img id="' + event.node.id + '" style="height: ' + (imgHeight - 180) + 'px" class="erl-single-image" src="' + ubegScanLink + '?scan_id=' + $companyKeyId + '&filename=' + event.node.physicalpath + '&uid=' + uid + '">');
                } else {
                    $imagePanel.empty().append('<i class="fa fa-eye-slash" style="font-size: 28px; color: #a7a7a7"></i>');
                }

                $imagePanel.parent().find('span').html('<i class="fa fa-search-plus"></i> Томоор харах');

                /*
                if (typeof $type !== 'undefined' && ($type == '2' || $type == '4')) {

                    Core.blockUI({
                        target: '.cvl-bookMeta',
                        animate: true
                    });
                    
                    var $processParam = {
                        cvlBookId: event.node.civilbookid,
                        selectedRow: selectedRow,
                        isDisabled: '0',
                        type: $type,
                        uniqId: $uniqId,
                        postData: newParamData
                    };
                    
                    $.ajax({
                        type: 'post',
                        url: 'mddoc/cvlFormDataHtml',
                        data: $processParam,
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function(response) {
                            $selecterDialog.find('.cvl-bookMeta').empty().append(response.Html).promise().done(function () {
                                
                                var windowHeight = $(window).height();
                
                                $selecterDialog.find('.cvl-bookMeta').css({'height': windowHeight - 180, 'overflow' : 'auto'});
                                
                                Core.unblockUI();
                                Core.unblockUI($('.cvl-bookMeta'));
                            });
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                    
                }

                if (typeof $type !== 'undefined' && ($type == '6' || $type == '7')) {

                    Core.blockUI({
                        target: '.cvl-bookMeta',
                        animate: true
                    });
                    
                    var $processParam = {
                        cvlBookId: event.node.civilbookid,
                        cvlContentId: typeof event.node.contentid !== 'undefined' ? event.node.contentid : '',
                        cvlBooktypeid: typeof event.node.booktypeid !== 'undefined' ? event.node.booktypeid : '',
                        selectedRow: selectedRow,
                        isDisabled: '0',
                        type: $type,
                        uniqId: $uniqId,
                        postData: newParamData
                    };
                    
                    $.ajax({
                        type: 'post',
                        url: 'mddoc/cvlControlFormDataHtml',
                        data: $processParam,
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function(response) {
                            $selecterDialog.find('.cvl-bookMeta').empty().append(response.Html).promise().done(function () {
                                
                                var windowHeight = $(window).height();
                
                                $selecterDialog.find('.cvl-bookMeta').css({'height': windowHeight - 180, 'overflow' : 'auto'});
                                
                                Core.unblockUI();
                                Core.unblockUI($('.cvl-bookMeta'));
                            });
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }

                if (typeof $type !== 'undefined' && $type == '3') {

                    Core.blockUI({
                        target: '.cvl-bookMeta',
                        animate: true
                    });
                    
                    var $processParam = {
                        metaDataId: '1534753471132',
                        dmMetaDataId: '1537426653397',
                        isDialog: false,
                        isSystemMeta: false,
                        runDefaultGet: 0,
                        cvlBookId: event.node.civilbookid,
                        oneSelectedRow: (typeof event.node.selectedRow !== 'undefined') ? event.node.selectedRow : [],
                        isDisabled: '0'
                    };
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdwebservice/callMethodByMeta',
                        data: $processParam,
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Loading...', 
                                boxed: true
                            });
                        },
                        success: function (data) {
                            $selecterDialog.find('.cvl-bookMeta').empty().append('<div class="col-md-12">'+data.Html+'</div>').promise().done(function () {
                                
                                var windowHeight = $(window).height();
                
                                $selecterDialog.find('.cvl-bookMeta').css({'height': windowHeight - 180, 'overflow' : 'auto'});
                                $selecterDialog.find('.cvl-bookMeta').find('.bp-btn-back').hide();
                                $selecterDialog.find('.cvl-bookMeta').find('span.portlet-subject-blue').hide();
                                $selecterDialog.find('.cvl-bookMeta').find('button.bpTestCaseSaveButton').hide();
                                
                                Core.unblockUI();
                                Core.unblockUI($('.cvl-bookMeta'));
                            });

                            Core.unblockUI();
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    
                }
                */
            }
        }

    });

    $tree.on('tree.select', function (event) {
        if (event.node) {

            var $imagePanel = $('.erl-image-preview');
            var $companyKeyId = event.node.companyKeyId;

            if (typeof $type !== 'undefined' && ($type == '2' || $type == '4' || $type == '6' || $type == '7')) {
                sentParams = {
                    companyId: selectedRow.ccid,
                    parentId: ''
                };
                $treeUrl = 'mddoc/electronViewTreeDataV2';
                $companyKeyId = ($type == '7') ? event.node.folderid : event.node.id;
            }

            if (isCivilRegister) {
                $companyKeyId = event.node.folderid;
            }

            if (event.node.isFile == '1') {
                var imgHeight = $(window).height();
                var uid = getUniqueId(1);
                var ubegScanLink = CONFIG_URL + getConfigValue('ubegScanLink');
                $imagePanel.empty().append('<img id="' + event.node.id + '" style="height: ' + (imgHeight - 180) + 'px" class="erl-single-image" src="' + ubegScanLink + '?scan_id=' + $companyKeyId + '&filename=' + event.node.physicalpath + '&uid=' + uid + '">');
            } else {
                $imagePanel.empty().append('<i class="fa fa-eye-slash" style="font-size: 28px; color: #a7a7a7"></i>');
            }

            $imagePanel.parent().find('span').html('<i class="fa fa-search-plus"></i> Томоор харах');

            if (isCivilRegister == false && typeof $type !== 'undefined' && ($type == '2' || $type == '4')) {

                Core.blockUI({
                    target: '.cvl-bookMeta',
                    animate: true
                });

                var $processParam = {
                    cvlBookId: event.node.civilbookid,
                    selectedRow: selectedRow,
                    isDisabled: '0',
                    type: $type,
                    uniqId: $uniqId,
                    postData: newParamData
                };

                $.ajax({
                    type: 'post',
                    url: 'mddoc/cvlFormDataHtml',
                    data: $processParam,
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (response) {
                        $selecterDialog.find('.cvl-bookMeta').empty().append(response.Html).promise().done(function () {

                            var windowHeight = $(window).height();

                            $selecterDialog.find('.cvl-bookMeta').css({ 'height': windowHeight - 180, 'overflow': 'auto' });

                            Core.unblockUI();
                            Core.unblockUI($('.cvl-bookMeta'));
                        });
                    },
                    error: function () {
                        alert("Error");
                    }
                });

            }

            if (isCivilRegister == false && typeof $type !== 'undefined' && ($type == '6' || $type == '7') && getConfigValue('CIVIL_OFFLINE_SERVER') !== '1') {

                Core.blockUI({
                    target: '.cvl-bookMeta',
                    animate: true
                });

                var $processParam = {
                    cvlBookId: event.node.civilbookid,
                    cvlContentId: typeof event.node.contentid !== 'undefined' ? event.node.contentid : '',
                    cvlBooktypeid: typeof event.node.booktypeid !== 'undefined' ? event.node.booktypeid : '',
                    selectedRow: selectedRow,
                    isDisabled: '0',
                    type: $type,
                    uniqId: $uniqId,
                    postData: newParamData
                };

                $.ajax({
                    type: 'post',
                    url: 'mddoc/cvlControlFormDataHtml',
                    data: $processParam,
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (response) {
                        $selecterDialog.find('.cvl-bookMeta').empty().append(response.Html).promise().done(function () {

                            if (typeof $type !== 'undefined' && ($type == '6' || $type == '7')) {

                                $companyKeyId = event.node.folderid;
                                if (typeof event.node.contentid !== 'undefined') {
                                    $('body').find('.hide_' + $uniqId).show();
                                    $('input[data-path="contentId_' + $uniqId + '"]').attr('value', event.node.contentid);
                                    $('input[data-path="nodeId_' + $uniqId + '"]').attr('value', event.node.id);

                                } else {
                                    $('body').find('.hide_' + $uniqId).hide();
                                }
                            }

                            var windowHeight = $(window).height();

                            $selecterDialog.find('.cvl-bookMeta').css({ 'height': windowHeight - 180, 'overflow': 'auto' });

                            Core.unblockUI();
                            Core.unblockUI($('.cvl-bookMeta'));
                        });
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            }

            if (isCivilRegister == false && typeof $type !== 'undefined' && $type == '3') {

                Core.blockUI({
                    target: '.cvl-bookMeta',
                    animate: true
                });

                var $processParam = {
                    metaDataId: '1534753471132',
                    dmMetaDataId: '1537426653397',
                    isDialog: false,
                    isSystemMeta: false,
                    runDefaultGet: 0,
                    cvlBookId: event.node.civilbookid,
                    oneSelectedRow: (typeof event.node.selectedRow !== 'undefined') ? event.node.selectedRow : [],
                    isDisabled: '0'
                };

                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/callMethodByMeta',
                    data: $processParam,
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        $selecterDialog.find('.cvl-bookMeta').empty().append('<div class="col-md-12">' + data.Html + '</div>').promise().done(function () {

                            var windowHeight = $(window).height();

                            $selecterDialog.find('.cvl-bookMeta').css({ 'height': windowHeight - 180, 'overflow': 'auto' });
                            $selecterDialog.find('.cvl-bookMeta').find('.bp-btn-back').hide();
                            $selecterDialog.find('.cvl-bookMeta').find('span.portlet-subject-blue').hide();
                            $selecterDialog.find('.cvl-bookMeta').find('button.bpTestCaseSaveButton').hide();

                            Core.unblockUI();
                            Core.unblockUI($('.cvl-bookMeta'));
                        });

                        Core.unblockUI();
                    },
                    error: function () {
                        alert('Error');
                    }
                });

            }

        }
    });
}

function erlImgFullsize(elem) {
    var $this = $(elem), $parent = $this.parent(),
        imgPath = $parent.find('.erl-image-preview').find('img').attr('src');

    if ($this.children().hasClass('fa-search-plus')) {
        $this.html('<i class="fa fa-search-minus"></i> Жижгээр харах');
        $parent.find('.erl-image-preview').empty().append('<div style="overflow: auto; width: 1200px;"><img style="width: 1200px;" src="' + imgPath + '"></div>');
    } else {
        var imgHeight = $(window).height();
        $this.html('<i class="fa fa-search-plus"></i> Томоор харах');
        $parent.find('.erl-image-preview').empty().append('<img style="height: ' + (imgHeight - 170) + 'px" src="' + imgPath + '">');
    }
}

function erlPrint(elem, compressData) {
    var uid, treeSelectedRows = $globalTree.tree('getSelectedNodes'), imgStr = '';
    var ubegScanLink = CONFIG_URL + getConfigValue('ubegScanLink');

    for (var ei = 0; ei < treeSelectedRows.length; ei++) {
        uid = getUniqueId(1);
        imgStr += '<img src="' + ubegScanLink + '?scan_id=' + treeSelectedRows[ei].companyKeyId + '&filename=' + treeSelectedRows[ei].physicalpath + '&uid=' + uid + '" height="1388px"><div class="pagebreak"></div>';
    }

    $.ajax({
        type: 'post',
        url: 'mddoc/erlPdfPrint',
        data: {
            content: imgStr,
            smetaDataType: 'erlPrint',
            compressData: compressData
        },
        dataType: 'html',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            if (typeof printJS === 'undefined') {
                $.when(
                    $.getScript(URL + 'assets/custom/addon/plugins/printjs/print.min.js')
                ).then(function () {

                    printJS({
                        printable: data,
                        type: 'pdf',
                        header: '',
                        documentTitle: ''
                    });
                    Core.unblockUI();

                }, function () {
                    console.log('an error occurred somewhere');
                });
            } else {
                printJS({
                    printable: data,
                    type: 'pdf',
                    header: '',
                    documentTitle: ''
                });
                Core.unblockUI();
            }

            $.ajax({
                type: 'post',
                url: 'mddoc/erlPdfPrintUnlink',
                data: {
                    url: data
                },
                dataType: 'html',
                success: function () {
                }
            });
        }
    });

}

function erlPrintAll(elem, compressData) {
    var uid, imgStr = '';
    var ubegScanLink = CONFIG_URL + getConfigValue('ubegScanLink');

    for (var i = 0; i < jqTreeData[0].children.length; i++) {
        for (var ii = 0; ii < jqTreeData[0]['children'][i].children.length; ii++) {
            for (var iii = 0; iii < jqTreeData[0]['children'][i]['children'][ii].children.length; iii++) {
                uid = getUniqueId(1);
                imgStr += '<img src="' + ubegScanLink + '?scan_id=' + jqTreeData[0]['children'][i]['children'][ii]['children'][iii].companyKeyId + '&filename=' + jqTreeData[0]['children'][i]['children'][ii]['children'][iii].physicalpath + '&uid=' + uid + '" height="1050px"><div class="pagebreak"></div>';
            }
        }
    }

    $.ajax({
        type: 'post',
        url: 'mddoc/erlPdfPrint',
        data: {
            content: imgStr,
            smetaDataType: 'erlPrintAll',
            compressData: compressData
        },
        dataType: 'html',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            if (typeof printJS === 'undefined') {
                $.when(
                    $.getScript(URL + 'assets/custom/addon/plugins/printjs/print.min.js')
                ).then(function () {

                    printJS({
                        printable: data,
                        type: 'pdf',
                        header: '',
                        documentTitle: ''
                    });
                    Core.unblockUI();

                }, function () {
                    console.log('an error occurred somewhere');
                });
            } else {
                printJS({
                    printable: data,
                    type: 'pdf',
                    header: '',
                    documentTitle: ''
                });
                Core.unblockUI();
            }

            $.ajax({
                type: 'post',
                url: 'mddoc/erlPdfPrintUnlink',
                data: {
                    url: data
                },
                dataType: 'html',
                success: function () {
                }
            });
        }
    });
}

function erlPdf(elem, compressData) {
    var treeSelectedRows = $globalTree.tree('getSelectedNodes'), imgStr = '';
    var ubegScanLink = CONFIG_URL + getConfigValue('ubegScanLink');

    for (var ei = 0; ei < treeSelectedRows.length; ei++) {
        uid = getUniqueId(1);
        imgStr += '<img src="' + ubegScanLink + '?scan_id=' + treeSelectedRows[ei].companyKeyId + '&filename=' + treeSelectedRows[ei].physicalpath + '&uid=' + uid + '"><div class="pagebreak"></div>';
    }

    $.fileDownload(URL + 'mddoc/erlPdfExport', {
        httpMethod: "POST",
        data: {
            content: imgStr,
            smetaDataType: 'erlPdf',
            compressData: compressData
        }
    }).done(function () {
    }).fail(function () {
        alert("File download failed!");
    });
}

function erlPdfAll(elem, compressData) {
    var imgStr = '';
    var ubegScanLink = CONFIG_URL + getConfigValue('ubegScanLink');

    Core.blockUI({
        message: 'Loading...',
        boxed: true
    });

    for (var i = 0; i < jqTreeData[0].children.length; i++) {
        for (var ii = 0; ii < jqTreeData[0]['children'][i].children.length; ii++) {
            for (var iii = 0; iii < jqTreeData[0]['children'][i]['children'][ii].children.length; iii++) {
                uid = getUniqueId(1);
                imgStr += '<img src="' + ubegScanLink + '?scan_id=' + jqTreeData[0]['children'][i]['children'][ii]['children'][iii].companyKeyId + '&filename=' + jqTreeData[0]['children'][i]['children'][ii]['children'][iii].physicalpath + '&uid=' + uid + '"><div class="pagebreak"></div>';
            }
        }
    }

    $.fileDownload(URL + 'mddoc/erlPdfExport', {
        httpMethod: "POST",
        data: {
            content: imgStr,
            smetaDataType: 'erlPdfAll',
            compressData: compressData
        }
    }).done(function () {
        Core.unblockUI();
    }).fail(function () {
        alert("File download failed!");
    });
}

function erlDelete(elem, compressData, uniqId) {
    var $tree = window['$window_' + uniqId].find('.ecl-jstree-container');
    var $id = $(elem).attr('data-deleteid');
    var $contentId = $(elem).attr('data-contentid');
    const node = $tree.tree('getNodeById', $id);
    var contentIds = [];
    contentIds = arrayTempFnc([node], contentIds);

    var $dialogConfirm = 'dialog-confirm-';

    if (!$("#" + $dialogConfirm).length) {
        $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
    }

    var $dialog = $("#" + $dialogConfirm);

    $dialog.empty().append("<strong>" + $(elem).attr('data-name') + "</strong><br>Устгахдаа итгэлтэй байна уу?");
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Баталгаажуулалт',
        width: 400,
        height: "auto",
        modal: true,
        close: function () {
            $dialog.empty().dialog('close');
        },
        buttons: [
            {
                text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {

                    $.ajax({
                        type: 'post',
                        url: 'mddoc/deleteErl',
                        data: { contentIds: contentIds, compressData: compressData },
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function (response) {

                            if (response.status === 'success') {
                                $tree.tree('removeNode', node);
                            }

                            Core.unblockUI();
                            PNotify.removeAll();
                            new PNotify({
                                title: response.title,
                                text: response.text,
                                type: response.status,
                                sticker: false
                            });

                        },
                        error: function (jqXHR, exception) {
                            Core.unblockUI();
                            Core.showErrorMessage(jqXHR, exception);
                        }
                    });

                    $dialog.empty().dialog('close');
                }
            },
            {
                text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }
            }
        ]
    });
    $dialog.dialog('open');

}

function arrayTempFnc(node, contentIds) {
    $.each(node, function (index, row) {
        if (typeof row.contentId !== 'undefined' && row.contentId) {
            contentIds.push(row.contentId);
            if (typeof row.children !== 'undefined' && (row.children).length > 0) {
                contentIds = arrayTempFnc(row.children, contentIds);
            }
        }
    });
    return contentIds;
}