/* global Core */
var IS_LOAD_ASSET_MOBI_SCRIPT = true;

var connectionAssets = function () {
    //<editor-fold defaultstate="collapsed" desc="variables">
    var $leftTreeList,
            $rightSideDv,
            $selectedRow,
            uniqId,
            $metaDataId,
            $taskTabMetaDataId,
            $pkiTabMetaDataId,
            $assetId,
            $checkkeyid,
            $taskid,
            $locationid,
            $directorypath,
            $srcRecordId,
            $processid,
            $defaultProcessId,
            $equipmentConfigId,
            $isEdit,
            $isDialog = false,
            META_GROUP_TYPE = '200101010000016',
            BUSINESS_PROCESS_TYPE = '200101010000011';
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent = function (viewMode, selectedTreeId) {
        $leftTreeList = $("#left-tree-list-" + uniqId),
                $rightSideDv = $("#rightSideDv_" + uniqId);

        setTimeout(function () {
            initLifeCycleListTree(viewMode, selectedTreeId);
            // lifeCycleContextMenu();
        }, 1);

        $('.lifecycle-toggler').on('click', function () {
            var $this = $(this);
            var togglerMode = $this.attr('data-toggler');
            var $togglerLeft = $('.lifecycle-toggler-left');
            var $togglerRight = $('.lifecycle-toggler-right');

            if (togglerMode === 'collapse') {
                $togglerLeft.removeClass('col-md-4').addClass('col-md-1');
                $togglerRight.removeClass('col-md-8').addClass('col-md-11');
                $this.find('i').removeClass('fa-chevron-circle-left').addClass('fa-chevron-circle-right');
                $this.attr('data-toggler', 'expand');
            } else {
                $togglerLeft.removeClass('col-md-1').addClass('col-md-4');
                $togglerRight.removeClass('col-md-11').addClass('col-md-8');
                $this.find('i').removeClass('fa-chevron-circle-right').addClass('fa-chevron-circle-left');
                $this.attr('data-toggler', 'collapse');
            }
            $(window).resize();
        });
    };

    var lifeCycleTreeParams = function (node, selectedTreeId) {
        return {
                locationId: (typeof node.data !== 'undefined' && typeof node.data.locationid !== 'undefined' ? node.data.locationid : ''),
                directorypath: $directorypath,
                srcRecordId: $srcRecordId,
                selectedRow: $selectedRow,
                selectedTreeId: selectedTreeId,
                parent: 'ok',
                parentNode: $srcRecordId
            };
            
        if (node.id === "#" || typeof node.id === "undefined") {
            return {
                locationId: $locationid,
                directorypath: $directorypath,
                srcRecordId: $srcRecordId,
                selectedRow: $selectedRow,
                selectedTreeId: selectedTreeId,
                parent: 'ok',
                parentNode: $srcRecordId
            };
        }
    };

    var initLifeCycleListTree = function (viewMode, selectedTreeId) {
        $leftTreeList.remove();
        $('<div id="left-tree-list-' + uniqId + '" class="lifecycle-common-div lifecycle-selected-t"></div>').insertBefore($("#left-tree-list-adjacent_" + uniqId));
        $leftTreeList = $('#left-tree-list-' + uniqId);
        $leftTreeList.on("changed.jstree", function (e, data) {
            
            $('li').removeClass('selected-row');
            if (data.action === 'ready' && typeof selectedTreeId !=='undefined' && selectedTreeId) {
                console.log($leftTreeList.find('.jstree-children .jstree-node:eq(0)').find('#' + selectedTreeId));
                $leftTreeList.find('.jstree-children .jstree-node:eq(0)').find('#' + selectedTreeId).find('.jstree-anchor:eq(0)').trigger('click');
            }
            if (typeof data.node !== 'undefined') {
                $('li#' + data.node.data.id).addClass('selected-row');
                $('a[href="#tab_asset_general_' + uniqId + '"]').trigger('click');

                $assetId = data.node.data.assetid;
                $directorypath = data.node.data.directorypath;
                $locationid = typeof data.node.data.topparentid === 'undefined' ? '' : data.node.data.topparentid;
                $srcRecordId = data.node.data.id;
                $processid = data.node.data.processid;
                $defaultProcessId = data.node.data.recordid;
                $checkkeyid = data.node.data.checkkeyid;
                $equipmentConfigId = data.node.data.equipmentconfigid;
                var locid = data.node.data.locationid;
                var checkkeyid = data.node.data.checkkeyid;

                $('input#dataview-criteria-params-' + $metaDataId).val('parentLocationId=' + locid + '&taskId=' + $taskid + '&dvCheckKeyId=' + checkkeyid );

                $('#href-tab-' + $taskTabMetaDataId).data('default-criteria', 'assetId=' + $assetId);
                if (viewMode === 'dialog') {
                    viewConnectionInstallationAction();
                } else {
    //                renderWebServiceByMeta(($isEdit === 'true' ? $processid : '1534753171545'), ".rightSideRenderBp_" + uniqId, true, 'id=' + $defaultProcessId, false, '1', undefined, undefined, {callerType: 'assetConfig', afterSaveNoAction: true});
                    renderConnectionInstallationAction(($isEdit === 'true' ? $processid : '1534753171545'), 'id=' + $defaultProcessId, '.rightSideRenderBp_' + uniqId);
                }
                
                $('#asset-mobi-port-connection-'+ uniqId).find('.urllink:eq(0)').remove();
                $('#asset-mobi-port-connection-'+ uniqId).prepend('<div class="pull-left w-100 urllink"><div class="org-choice w-100"><strong>LINK: </strong>'+ URL_APP + 'mdasset/renderconnectionmobi/' + $('#asset-mobi-port-connection-'+ uniqId).attr('data-assetid') + '/' + $srcRecordId +'</div></div>');
                
            } else {
                var locid;
                var checkkeyid ;
            }
        }).on("open_node.jstree", function (e, data) {}).on('ready.jstree', function () {
            setTimeout(function () {
                if (!selectedTreeId) {
                    $leftTreeList.find('.jstree-children .jstree-node:eq(0)').find('.jstree-anchor:eq(0)').trigger('click');
                }
            }, 100);
        }).jstree({
            'core': {
                "check_callback": true,
                "expand_selected_onload": true,
                "open_parents": true,
                "load_open": true,
                "data": {
                    url: 'mdasset/getAssetsListTree',
                    dataType: "json",
                    data: function (node) {
                        return lifeCycleTreeParams(node, selectedTreeId);
                    }
                },
                "themes": {
                    'responsive': true,
                }
            },
            'types': {
                "default": {
                    "icon": "icon-folder2 text-orange-300"
                },
                "file": {
                    "icon": "fa fa-play-circle text-orange-400"
                }
            },
            'unique': {
                'duplicate': function (name, counter) {
                    return name + ' ' + counter;
                }
            },
            'plugins': [
                'changed', 'types', 'unique', 'wholerow'
            ]
        });
    };
    
    var lifeCycleTreeAction = function (mainMetaDataId, processMetaDataId) {
        var $dataRow = JSON.parse('{"id": "' + $srcRecordId + '","assetid":"' + $assetId + '"}');
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: processMetaDataId,
                isDialog: true,
                isSystemMeta: false,
                dmMetaDataId: mainMetaDataId,
                responseType: '',
                oneSelectedRow: $dataRow,
                openParams: '{"callerType":"mobCheckEquipmentList"}'
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
//                connectionAssets.initLifeCycleListTree();
//                     $(renderDiv).empty().append('<div class="row" id="object-value-list-' + $pkiTabMetaDataId + '">' + data.Html + '</div>').promise().done(function () {
//                 });
            },
            error: function () {
                alert("Error");
            }
        });
    }
    var viewConnectionInstallationAction = function (mainMetaDataId, processMetaDataId) {
        var $dataRow = JSON.parse('{"id": "' + $checkkeyid + '","assetid":"' + $assetId + '"}');
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: '1534753070460',
                isDialog: true,
                isSystemMeta: false,
                dmMetaDataId: '1529564179653',
                responseType: '',
                oneSelectedRow: $dataRow,
                openParams: '{"callerType":"MOB_CHECK_KEY_DV"}'
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $(".rightSideRenderBp_" + uniqId).empty().append('<div class="row" id="object-value-list-' + $pkiTabMetaDataId + '">' + data.Html + '</div>').promise().done(function () { });
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }

    var processTabRender = function (renderDiv) {
        var $dataRow = '{"kpiDtlGetProcess": "MOB_EQUIPMENT_CONFIG_DV_004","checkkeyid":"' + $checkkeyid + '", "taskid":"' + $taskid + '", "isactive":"1", "description":""}';
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: ($isEdit === 'true' ? $pkiTabMetaDataId : '1537175678448'),
                isDialog: false,
                isHeaderName: false,
                isSystemMeta: false,
                dmMetaDataId: "1533787142880",
                responseType: '',
                addonJsonParam: $dataRow,
                openParams: '{"callerType":"MOB_EQUIPMENT_CONFIG_DV","afterSaveNoAction":true}'
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $(renderDiv).empty().append('<div class="col-md-12 pr-0" id="object-value-list-' + $pkiTabMetaDataId + '">' + data.Html + '</div>').promise().done(function () {
                    $(renderDiv).find('input[name="param[checkKeyId]"]').val($checkkeyid);
                    $(renderDiv).find('input[name="param[taskId]"]').val($taskid);
                    $(renderDiv).find('input[name="param[mainId]"]').val(uniqId);
                });
            },
            error: function () {
                alert("Error");
            }
        });
    }
    var dataViewTabRender = function (element, renderDiv) {
        $.ajax({
            type: 'post',
            url: 'mdobject/dataValueViewer',
            data: {
                metaDataId: $taskTabMetaDataId,
                viewType: 'detail',
                uriParams: '{"assetId": ' + $assetId + '}',
                ignorePermission: 1
            },
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (dataHtml) {

                $(renderDiv).empty().append('<div class="row" id="object-value-list-' + $taskTabMetaDataId + '">' + dataHtml + '</div>');

                Core.unblockUI();
            },
            error: function () {
                alert('Error');
            }
        });
    };

    var tabRender = function () {

        var $elementTabId = '#tab_asset_connection_' + uniqId;
        $($elementTabId).html('');
        $.ajax({
            type: 'post',
            url: 'mdasset/getConnectionData',
            dataType: 'json',
            data: {
                assetId: $assetId,
                uniqId: uniqId,
                locationId: $locationid,
                directorypath: $directorypath,
                srcRecordId: $srcRecordId,
                checkkeyid: $checkkeyid,
                taskId: $taskid,
                isEdit: $isEdit
            },
            beforeSend: function () {
                Core.blockUI({
                    target: $elementTabId,
                    animate: true
                });
            },
            success: function (data) {
                $($elementTabId).empty().append(data.Html);
                Core.unblockUI($elementTabId);
            },
            error: function (data) {
                $($elementTabId).empty().append(data);
                Core.unblockUI($elementTabId);
            }
        }).done(function () {
            Core.initAjax($($elementTabId));
        });
    }

    var formRender = function (element, assetId, uniqId, locationId, directorypath, checkkeyid, connectionId, isstart, installationId) {

        var $this = $(element);
        var $dataRow = JSON.parse($this.attr('data-row-data'));
        var $elementTabId = '#asset-mobi-port-connection' + uniqId;
        $.ajax({
            type: 'post',
            url: 'mdasset/setConnectionDataForm',
            dataType: 'json',
            data: {
                assetId: assetId,
                uniqId: uniqId,
                locationId: locationId,
                directorypath: directorypath,
                dataRow: $dataRow,
                srcRecordId: $srcRecordId,
                checkkeyid: checkkeyid,
                connectionId: connectionId,
                installationId: installationId,
                isstart: isstart,
                taskId: $taskid,
                isEdit: $isEdit
            },
            beforeSend: function () {
                Core.blockUI({
                    target: $elementTabId,
                    animate: true
                });
            },
            success: function (data) {
                var $dialogName = 'dialog-connection-port';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                $dialog.empty().append(data.Html).promise().done(function () {
                });
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green-meadow ' + ($isEdit === 'false' ? 'hide' : ''), click: function () {
                                $("#mobi-template-connection").validate({
                                    ignore: "",
                                    errorPlacement: function () {}
                                });
                                if (!$("#mobi-template-connection").valid()) {
                                    PNotify.removeAll();
                                    new PNotify({
                                            title: 'Холболтоо шалгана уу',
                                            text: 'Холболтоо шалгана уу. Заавал талбаруудыг бөглөнө үү',
                                            type: 'warning',
                                            sticker: false
                                        });
                                    return;
                                }

                                $('#mobi-template-connection').ajaxSubmit({
                                    type: 'post',
                                    url: 'mdasset/savePortconnection',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({
                                            message: plang.get('msg_saving_block'),
                                            boxed: true
                                        });
                                    },
                                    success: function (data) {
                                        PNotify.removeAll();

                                        if (data.status === 'success') {
                                            tabRender();
                                            $dialog.dialog('close');
                                        }

                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        Core.unblockUI();
                                    }
                                });
                            }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });

                $dialog.dialog('open');
                Core.unblockUI($elementTabId);
            },
            error: function (data) {
                alert('Error');
                Core.unblockUI($elementTabId);
            }
        });
    }
    var renderEquipmentInstallationMobiInit = function (elem, processMetaDataId, dataViewId, selectedRow, paramData) {
        paramData.push({ name: "wfmStatusParams", value: selectedRow["wfmStatusParams"]  });
        var $this = $(elem);
        var $elementClass = $('.render-object-viewer');

        $.ajax({
            type: 'post',
            url: 'mdasset/renderEquipmentInstallationMobi',
            dataType: 'json',
            data: paramData,
            beforeSend: function () {
                Core.blockUI({
                    target: $elementClass,
                    animate: true
                });
            },
            success: function (data) {
                var $dialogName = 'dialog-render-mobi';
                if (typeof data.mainId != 'undefined' && data.mainId != '')
                    $dialogName = 'dialog-render-mobi-' + data.mainId;

                if (!$($dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo("body");
                }
                var $dialog = $('#' + $dialogName);
                $dialog.empty().append(data.Html).promise().done(function () {
                    Core.initAjax($dialog);
                });
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm blue btn-save', click: function () {
                                PNotify.removeAll();
                                $.ajax({
                                    type: 'post',
                                    url: 'mdasset/saveEquipmentInstallationStatus',
                                    dataType: 'json',
                                    data: {dataType: 'json', wfmStatusParams: $dialog.find('input[name="wfm-status-params-' + uniqId + '"]').val(), selectedRow: selectedRow},
                                    success: function (data) {
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                            $("#" + $dialogName).dialog('close');
                                            dataViewReload(dataViewId);
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                        }
                                    }
                                });
                            }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                                $("#" + $dialogName).dialog('close');
                                dataViewReload(dataViewId);
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

                $dialog.dialog('open');
                Core.unblockUI($elementClass);
            },
            error: function (data) {
                alert('Error');
                Core.unblockUI($elementClass);
            }
        });
    }

    var renderEquipmentConnectionMobiInit = function (elem, processMetaDataId, dataViewId, selectedRow, paramData) {
        var $this = $(elem);
        var $elementClass = $('.render-object-viewer');

        $.ajax({
            type: 'post',
            url: 'mdasset/renderEquipmentConnectionMobi',
            dataType: 'json',
            data: {dataType: 'json', selectedRow: selectedRow, postRowData: {dataRow: selectedRow}},
            beforeSend: function () {
                Core.blockUI({
                    target: $elementClass,
                    animate: true
                });
            },
            success: function (data) {
                var $dialogName = 'dialog-render-mobi';
                if (typeof data.mainId != 'undefined' && data.mainId != '')
                    $dialogName = 'dialog-render-mobi-' + data.mainId;

                if (!$($dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo("body");
                }
                var $dialog = $('#' + $dialogName);
                $dialog.empty().append(data.Html).promise().done(function () {
                    $('.editMode').hide();
                    $('.viewDisableMode').prop('disabled', true);
                });
                $dialog.dialog({
                    cache: false,
//                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
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

                $dialog.dialog('open');
                Core.unblockUI($elementClass);
            },
            error: function (data) {
                alert('Error');
                Core.unblockUI($elementClass);
            }
        });
    }


    var initOnTab = function (elem, paramData) {
        paramData['dataType'] = 'json';
        $.ajax({
            type: 'post',
            url: 'mdasset/renderconnectionmobi',
            data: paramData,
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {

                var $dialogName = 'dialog-render-mobi';
                if (typeof data.mainId != 'undefined' && data.mainId != '')
                    $dialogName = 'dialog-render-mobi-' + data.mainId;

                if (!$($dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo("body");
                }
                var $dialog = $('#' + $dialogName);
                $dialog.empty().append(data.Html).promise().done(function () {
                });
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1300,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.finish_btn, class: 'btn btn-sm blue finish_btn', click: function () {
                                PNotify.removeAll();
                                $("#" + $dialogName).dialog('close');
                            }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                                $("#" + $dialogName).dialog('close');
                                if (typeof dataViewId !== 'undefined') {
                                    dataViewReload(dataViewId);
                                }
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

                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert('Error');
            }
        });
    }

    //<editor-fold defaultstate="collapsed" desc="File upload">
    var getFileUploadModal = function (mapId, taskId) {
        var data = {id: mapId, taskId: taskId};
        Core.blockUI({
            animate: true
        });
        $.ajax({
            url: "mdlifecycle/getFileUploadModal",
            type: "POST",
            data: data,
            dataType: "JSON",
            success: function (data) {
                var config = {
                    title: data.Title,
                    width: data.width,
                    height: data.height,
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-madison', click: function () {
                                $('#dialog-lc-fileupload').dialog('close');
                            }}
                    ]
                };

                Core.initDialog('dialog-lc-fileupload', data.html, config, function ($dialog) {
                    $dialog.dialogExtend("maximize");
                });
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });
    };

    var lifeCycleContextMenu = function () {
        $.contextMenu({
            selector: '#left-tree-list-' + uniqId + ' ul.jstree-container-ul li.jstree-node',
            callback: function (key, opt) {
                if (key === 'bp') {
                    _processPostParam = 'taskId=' + opt.$trigger.attr('data-tid');
                    callWebServiceByMeta('1501571866720', true);
                }
            },
            items: {
                "bp": {name: "Төлөвлөгөө", icon: "clipboard"}
            }
        });
    };
    //</editor-fold>
    //</editor-fold>
    return {
        init: function (uId, metaDataId, selectedRow, taskTabMetaDataId, pkiTabMetaDataId, viewMode, taskid, isEdit, selectedTreeId) {
            uniqId = uId;
            $selectedRow = selectedRow;
            $metaDataId = metaDataId;
            $taskTabMetaDataId = taskTabMetaDataId;
            $pkiTabMetaDataId = pkiTabMetaDataId;
            $taskid = taskid;
            $isEdit = isEdit;
            initEvent(viewMode, selectedTreeId);
        },
        initOnTab: function (elem, paramData) {
            initOnTab(elem, paramData);
        },
        initLifeCycleListTree: function (viewMode, selectedRow) {
            initLifeCycleListTree(viewMode, selectedRow);
        },
        viewConnectionInstallationAction: function (element) {
            viewConnectionInstallationAction();
        },
        tabRender: function (element) {
            tabRender();
        },
        processTabRender: function (renderDiv) {
            processTabRender(renderDiv);
        },
        dataViewTabRender: function (element, renderDiv) {
            dataViewTabRender(element, renderDiv);
        },
        formRender: function (elemet, assetId, uniqId, locationId, directorypath, $checkkeyid, connectionId, isstart, installationId) {
            formRender(elemet, assetId, uniqId, locationId, directorypath, $checkkeyid, connectionId, isstart, installationId);
        },
        lifeCycleTreeAction: function (mainMetaDataId, processMetaDataId) {
            lifeCycleTreeAction(mainMetaDataId, processMetaDataId);
        },
        renderEquipmentInstallationMobiInit: function (elem, processMetaDataId, dataViewId, selectedRow, paramData) {
            renderEquipmentInstallationMobiInit(elem, processMetaDataId, dataViewId, selectedRow, paramData);
        },
        renderEquipmentConnectionMobiInit: function (elem, processMetaDataId, dataViewId, selectedRow, paramData) {
            renderEquipmentConnectionMobiInit(elem, processMetaDataId, dataViewId, selectedRow, paramData);
        }
    };
}();


function renderConnectionInstallationAction($processid, runDefaultGetParam, tag) {
    
    var __params = {
            metaDataId: $processid,
            isDialog: false,
            isGetConsolidate: false,
            workSpaceId: '',
            workSpaceParams: '',
            wfmStatusParams: '',
            signerParams: false,
            batchNumber: false,
            openParams: JSON.stringify({callerType: 'assetConfig', afterSaveNoAction: true}),
            runDefaultGetParam: runDefaultGetParam,
            runDefaultGet: 1,
        };
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: __params,
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            var $dialogName = $(tag);
            $dialogName.empty().append(data.Html).promise().done(function () {
                Core.unblockUI();
                $dialogName.find('.is-bp-open-').removeClass('is-bp-open-');
            });
        },
        error: function (jqXHR, exception) {
            Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
        }
    });
}