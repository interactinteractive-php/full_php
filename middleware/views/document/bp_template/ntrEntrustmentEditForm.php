<div class="bg-white">
    <div class="card-body form" id="mainRenderDiv" style="">
        <div class="xs-form main-action-meta bp-banner-container " id="bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
            <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-air-sms-form', 'method' => 'post')); ?>
                <div class="meta-toolbar">
                    <span class="font-weight-bold text-uppercase text-gray2"><?php echo $this->title ?></span>
                    <div class="ml-auto">
                        <button type="button" class="btn btn-sm btn-circle btn-success air-sms-btn-save " onclick="">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save_btn'); ?>
                        </button>
                    </div>
                </div>
                <div class="clearfix w-100"></div>
                <!-- banner -->
                <div class="row">
                    <div class="col-md-12 center-sidebar">
                        <div class="table-scrollable table-scrollable-borderless bp-header-param">
                            <table class="table table-sm table-no-bordered bp-header-param">
                                <tbody>
                                    <tr>
                                        <td class="text-right middle" data-cell-path="code" style="width: 23%">
                                            <label for="param[id]" data-label-path="id"><span class="required">*</span>Sms file: </label>
                                        </td>
                                        <td class="middle" data-cell-path="code" style="width: 27%" colspan="">
                                            <div data-section-path="code">
                                                <?php echo Form::text(array('name' => 'airSmsFile', 'id' => 'airSmsFile', 'class' => 'form-control-sm', 'style' => 'border: 1px solid #CCC;', 'required' => 'required')); ?>
                                            </div>
                                        </td>
                                        <td class="text-right middle" data-cell-path="id" style="width: 23%"></td>
                                        <td class="middle" data-cell-path="id" style="width: 27%" colspan=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="clearfix w-100"></div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('body').on('click', '.air-sms-btn-save', function () {
        $('#appbp-air-sms-form').validate({errorPlacement: function () {}});
        if ($('#appbp-air-sms-form').valid()) {
            $('#appbp-air-sms-form').ajaxSubmit({
                type: 'post',
                url: 'mddoc/saveAirSms',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: plang.get('msg_saving_block'),
                        boxed: true
                    });
                },
                success: function (data) {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    
                    Core.unblockUI();
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: msg,
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                }
            });
        } else {
            
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: 'Заавал бөглөх талбараа бөглөнө үү',
                type: 'error',
                sticker: false
            });
        }
    });
    
    function multiSelectionRender_<?php echo $this->methodId ?>(lookupMetaDataId, parentId, tag, element, mainSelector) {
        
        var paramData = []; 
        var $fSel2Clone = mainSelector.clone();
        
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
            
        var $html = '<div class="'+ $dataSelectionPath +'_<?php echo $this->methodId ?>" data-lookupparamid="'+ lookupMetaDataId +'" data-lookup-parentid="'+ parentId +'" data-lookup-path="'+ tag +'" style="background: #cefbe2; padding: 0 5px;table-layout: fixed;float: left; line-height: 20px; width: 100%"></div>'
                        + '<div class="input-icon right groupSelectionNodeId_<?php echo $this->methodId ?>" data-toggle="dropdown" data-delay="1000" data-close-others="true">'
                        + '<i class="hidden fa fa-angle-down selectedJTreePathIco_<?php echo $this->methodId ?>"></i>'
                        + '<input type="hidden" data-path="selectionList_<?php echo $this->methodId; ?>" value="" />'
                        + '<input type="text" name="selectionNodeList_search" data-path="'+ _dataPath +'" placeholder="Сонгох..." autocomplete="off" '
                            + 'class="selectionNodeList_search_<?php echo $this->methodId; ?> form-control form-control-sm selectedRowData<?php echo $this->methodId ?>" />'
                    + '</div>'
                    + '<div class="hidden selectionNodeList-jtree-<?php echo $this->methodId; ?> jtree-list">'
                        + '<div class="search-tree-<?php echo $this->methodId; ?>">'
                            + '<a class="selectionNode-multiselect-all-<?php echo $this->methodId ?>" href="javascript:;"><span>Бүгдийг сонгох</span></a>'
                            + '<a class="selectionNode-multiselect-none-<?php echo $this->methodId ?>" style="margin-left:10px;" href="javascript:;"><span>Буцаах</span></a>'
                        + '</div>'
                        + '<div class="list-jtree-<?php echo $this->methodId; ?>"></div>'
                    + '</div>';
            
        
        element.empty().append('<div class="hidden"></div>' + $html).promise().done(function() {
            var $removeSelector = element.find('.hidden:first');
            
            $removeSelector.append('<select data-path="'+ _dataPath +'" name="'+ _dataPath +'" ><option value="">Сонгох<option></select>');
            $removeSelector.find('select').trigger('change');
            
            $('.list-jtree-<?php echo $this->methodId ?>').on("changed.jstree", function (e, data) {
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
                    $.each(data.node.children_d, function (key, value) {
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

                } 
                else if (data.action === "deselect_node") {
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

                    $.each(data.node.children_d, function (key, value) {
                        var indexMid = selectedJTreePathId_<?php echo $this->methodId ?>.indexOf(value);
                        selectedJTreePathId_<?php echo $this->methodId ?>.splice(indexMid, 1);
                        selectedJTreePathText_<?php echo $this->methodId ?>.splice(indexMid, 1);

                        deSelectNode_<?php echo $this->methodId ?>(value);
                    });
                } 
                else if (data.action === "select_all") {
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
                    
                }
                
                var $selectionContent = '';
                var $ticket = true;
                var $selectionListArr = $('input[data-path="selectionList_<?php echo $this->methodId; ?>"]').val();
                var $selectionListArrSplit = $selectionListArr.split(',');
                
                $.each(selectedJTreePathText_<?php echo $this->methodId ?>, function (index, rowText) {
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
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (responseData) {
                                    if (responseData) {
                                        $.each(responseData, function (indexT, rowT) {
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
                                error: function () {
                                    alert('Error');
                                    Core.unblockUI();
                                }

                            });
                        }
                        
                        if (selectedJTreePathId_<?php echo $this->methodId ?>[index] !== '#' && !$('.'+ $dataSelectionPath +'_<?php echo $this->methodId ?>').find('span[data-path-id="'+ selectedJTreePathId_<?php echo $this->methodId ?>[index] +'"]').length) {
                            $selectionContent += '<span data-path-id="'+ selectedJTreePathId_<?php echo $this->methodId ?>[index] +'" style="'+ $cssStyle +'"  class="float-left mr5 padding-1">' + rowText + ', </span> ';
                        }
                    }
                });
                
                if (!selectedJTreePathId_<?php echo $this->methodId ?> || !$selectionListArr || $ticket) {
                }
                
                $('.'+ $dataSelectionPath +'_<?php echo $this->methodId ?>').append($selectionContent).promise().done(function() {
                    var getPathElement = element.find("[data-path='" + _dataPath + "']");
                    
                    if (getPathElement.length > 0) {
                        var $tagName = getPathElement.prop("tagName").toLowerCase();
                        var _this = element.find($tagName + '[data-path="'+ _dataPath +'"]');
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

                            changeSelector_<?php echo $this->methodId ?>(_this, $tagName, groupPath, '', '', '', '', '', splitDataPaths, splitDataPaths, $thisDataPathLastIndexVal, $thisNameLastIndexVal, cindex, _thisName, selectedJTreePathId_<?php echo $this->methodId ?>, 'change');
                        } else {
                            _this.trigger('change');
                        }

                    }

                    $.each($selectionListArrSplit, function (index, rowId) {
                        if (!inArray_<?php echo $this->methodId; ?>(selectedJTreePathId_<?php echo $this->methodId ?>, rowId)) {
                            $('span[data-path-id="'+ rowId +'"]').remove();
                        }
                    });

                    $('input[data-path="selectionList_<?php echo $this->methodId; ?>"]').val(selectedJTreePathId_<?php echo $this->methodId ?>);

                    $('.'+ $dataSelectionPath +'_<?php echo $this->methodId ?>').find('.select2-container').remove();

                    Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>);
                });

            }).on("loaded.jstree", function() {
                <?php if (isset($this->editNtrMode) && $this->editNtrMode) { ?>
                    var $multiData = $(mainSelector).attr('dt-value').split(',');
                    $.each($multiData, function ($index, $row) {
                        if ($row !== '') {
                            $('.list-jtree-<?php echo $this->methodId ?>').jstree(true).select_node($row);
                        }
                    });
                <?php } ?>
            }).jstree({
                "core": {
                    expand_selected_onload: false,
                    "open_parents": true,
                    "load_open": true,
                    'data': {
                        url: URL_APP + 'mddoc/getLookupParams',
                        type: 'post',
                        data: function (node) {
                            var $ticketParam = true;
                            $.each(paramData, function (pInd, pRow) {
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
                        success: function () {
                            setTimeout(function () {
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
                    real_checkboxes_names: function (n) {
                        var nid = 0;
                        $(n).each(function (data) {
                            nid = $(this).attr("nodeid");
                        });
                        return (["check_" + nid, nid]);
                    },
                    three_state: true,
                    two_state: true,
                    whole_node: true
                },
                'unique': {
                    'duplicate': function (name, counter) {
                        return name + ' ' + counter;
                    }
                },
                'plugins': [
                    'changed', 'unique', 'wholerow', 'checkbox', 'search'
                ]
            });
        });
        
    }
    
    $('body').on('click', '.selectedRowData<?php echo $this->methodId ?>',  function () {
        if ($('.selectedJTreePathIco_<?php echo $this->methodId ?>').hasClass('fa-angle-up')) {
            return;
        }
        
        if (singleClick_<?php echo $this->methodId ?> == 0) {
            singleClick_<?php echo $this->methodId ?> = 1;
            var _jtreewidth = 629;
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').width(_jtreewidth);
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').find('.jstree-container-ul').width(_jtreewidth - 12);
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').removeClass('hidden');
            $('.selectedJTreePathIco_<?php echo $this->methodId ?>').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            singleClick_<?php echo $this->methodId ?> = 0;
            $('.selectionNodeList-jtree-<?php echo $this->methodId ?>').addClass('hidden');
            $('.selectedJTreePathIco_<?php echo $this->methodId ?>').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });
       
    $('body').on('click', '.selectionNode-multiselect-all-<?php echo $this->methodId ?>', function () {
        if ($(this).hasClass('allCheckedData')) {
            return;
        }
        $(this).addClass('allCheckedData');
        $('.list-jtree-<?php echo $this->methodId ?>').jstree("select_all");
        $(".list-jtree-<?php echo $this->methodId ?>").jstree("close_all");
    });
    
    $('body').on('click', '.selectionNode-multiselect-none-<?php echo $this->methodId ?>', function () {
        $('.selectionNode-multiselect-all-<?php echo $this->methodId ?>').removeClass('allCheckedData');
        selectedJTreePathId_<?php echo $this->methodId ?> = [];
        selectedJTreePathText_<?php echo $this->methodId ?> = [];        
        
        $('.list-jtree-<?php echo $this->methodId ?>').jstree("deselect_all");
        $(".list-jtree-<?php echo $this->methodId ?>").jstree("close_all");
        
        $('input[data-path="selectionList_<?php echo $this->methodId; ?>"]').val('');
    });
    
    var selectNode_<?php echo $this->methodId ?> = function (id) {
        $('.list-jtree-<?php echo $this->methodId ?>').jstree("select_node", id, true, true);
    };
    
    var deSelectNode_<?php echo $this->methodId ?> = function (id) {
        $('.list-jtree-<?php echo $this->methodId ?>').jstree("deselect_node", id, true, true);
    };
    
</script>