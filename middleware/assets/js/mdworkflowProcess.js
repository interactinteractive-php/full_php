var selectedObj, strokeCommon, drawRowCount = 0,
        wfmWorkFlowStatus = [], foo = true,
        tempStatusId = 0, tempStatusCode = 0,
        tempStatusName = 0, tempStatusColor = 0,
        wfIconAddPositionTop = 20,
        _startWfmStatusId = 0,
        wfIconAddPostionLeft = 20,
        wfObjectBoolean = "14359007153593",
        wfmWorkFlowId = "0", mainMetaDataId = "0", selectedTransitionId = "0", _selectTransitionId = "0",
        transId = "0";

$(function () {

    var x = 100000000;
    var me = this;
    var windows;
    var arrowStyle = arrowShape ? arrowShape : 'StateMachine'; //Straight, Flowchart, Bezier, StateMachine

    $('#editor').on('click', 'div.wfposition', function () {
        selectedObj = $(this);
        setControlVal(selectedObj);
    });
        
    $.contextMenu({
        selector: '.wfStatusMenu',
        events: {
            show: function(opt) {
                if ((typeof isWfmShowOnly !== 'undefined' && !isWfmShowOnly) || typeof isWfmShowOnly == 'undefined') {
                    var $rightPanel = $('.pivotgrid-table-center-right-cell');
                    if ($rightPanel.hasAttr('data-islock') && $rightPanel.attr('data-islock') == '1') {
                        return false;
                    } 
                    return true;
                } else {
                    return false;
                }
            }
        },
        callback: function (key, opt) {
            if (key === 'edit') {
                var $elem = $(this);
                var selectedMetaWfmStatusId = $elem.attr('id');
                var $dialogName = 'dialog-editworkflowStatus-' + selectedMetaWfmStatusId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);
                        
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/editWorkFlowStatusForm',
                    dataType: 'json',
                    data: {metaWfmStatusId: selectedMetaWfmStatusId, metaDataId: mainMetaDataId},
                    beforeSend: function () {
                        Core.blockUI({animate: true});
                        
                        if (!$().colorpicker) { 
                            $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                            });
                        }
                        if (!$().iconpicker) { 
                            $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                            });
                        }
                    },
                    success: function (dataHtml) {
                        
                        var buttonClass = '';
                        if (dataHtml.hasOwnProperty('isLock') && dataHtml.isLock) {
                            buttonClass = ' d-none';
                        }
                        
                        $dialog.empty().append(dataHtml.Html);
                        $dialog.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: dataHtml.Title,
                            width: 450,
                            height: "auto",
                            modal: true,
                            close: function () {
                                $dialog.empty().dialog('destroy').remove();
                            },
                            buttons: [
                                {text: dataHtml.save_btn, class: 'btn btn-sm green-meadow'+buttonClass, click: function () {
                                    $("#updateWfmStatus-from", "#" + $dialogName).validate({errorPlacement: function () {}});
                                    if ($("#updateWfmStatus-from", "#" + $dialogName).valid()) {
                                        $('#updateWfmStatus-from', "#" + $dialogName).ajaxSubmit({
                                            type: 'post',
                                            url: 'mdprocessflow/updateWfmStatus',
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                            },
                                            success: function (data) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false
                                                });

                                                if (data.status === 'success') {
                                                    wfmWorkFlowId = data.wfmWorkFlowId;
                                                    if (selectedTransitionId !== '0') {
                                                        selectJsPlumbViewBy(selectedTransitionId);
                                                    }
                                                    $dialog.dialog('close');
                                                } 
                                                Core.unblockUI();
                                            }
                                        });
                                    }
                                }},
                                {text: dataHtml.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                    $dialog.dialog('close');
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
                        alert("Error");
                    }
                }).done(function () {
                    Core.initAjax($dialog);
                });
            }
            if (key === 'delete') {

                var $elem = $(this);
                var _selectedStatusId = $elem.attr('id');
                if (_startWfmStatusId === _selectedStatusId) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Эхлэлийн цэгээ устгах гэж байна, Болохгүйг анхаарна уу?',
                        type: 'warning',
                        sticker: false
                    });
                    return;
                }
                var dialogName = '#deleteConfirm';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Сануулах',
                    width: '350',
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $(dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            $(dialogName).dialog('close');
                            jsPlumb.remove(_selectedStatusId);
                            var ticket = false;
                            $.each($('.ui-status-no-draggable'), function (index, value) {
                                if ($(value).attr('data-status-id') === $elem.attr('data-status-id')) {
                                    ticket = true;
                                }
                            });
                            if (!ticket) {
                                var fontColor = ($elem.attr('data-status-color').indexOf("#") == 0) ? 'color: #fff;' : 'color: #000;';
                                $(".status-ui-droppable").append('<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 ' + $elem.attr('data-status-color') + '  ui-draggable ui-draggable-handle" style="' + fontColor + '; position: relative; background-color: ' + $elem.attr('data-status-color') + ';" data-status-color="' + $elem.attr('data-status-color') + '" data-status-name="' + $elem.attr('data-status-name') + '" data-status-code="' + $elem.attr('data-status-code') + '" data-status-id="' + $elem.attr('data-status-id') + '" target="_self"><span class="title">' + $elem.attr('data-status-name') + '</span></a>');
                                draggableEnableFunction();
                            }
                        }},
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                });
                $(dialogName).dialog('open');

            }
        },
        items: {
            "edit": {name: plang.get('edit_btn'), icon: "edit"},
            "delete": {name: plang.get('delete_btn'), icon: "trash"}
        }
    });

    $.contextMenu({
        selector: '.wfMenuStart-status',
        events: {
            show: function(opt) {
                if ((typeof isWfmShowOnly !== 'undefined' && !isWfmShowOnly) || typeof isWfmShowOnly == 'undefined') {
                    var $rightPanel = $('.pivotgrid-table-center-right-cell');
                    if ($rightPanel.hasAttr('data-islock') && $rightPanel.attr('data-islock') == '1') {
                        return false;
                    } 
                    return true;
                } else {
                    return false;
                }
            }
        },
        callback: function (key, opt) {
            if (key === 'arrowDelete') {
                $('#workFlowEditor').find('input[name="' + $(this).attr('id') + '"]').attr('data-boolentrueid', -1);
                $('#workFlowEditor').find('input[name="' + $(this).attr('id') + '"]').attr('data-boolenfalseid', -1);
                jsPlumb.select({source: $(this).attr('id')}).detach();
            }
        },
        items: {
            "arrowDelete": {name: "Сум устгах", icon: "trash"}
        }
    });

    $.contextMenu({
        selector: '.ui-status-no-draggable',
        events: {
            show: function(options){
                if ((typeof isWfmShowOnly !== 'undefined' && !isWfmShowOnly) || typeof isWfmShowOnly == 'undefined') {
                    return true;
                } else {
                    return false;
                }
            }
        },
        callback: function (key, opt) {
            if (key === 'edit') {
                var selectedMetaWfmStatusId = $(this).attr('data-status-id');
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/editWorkFlowStatusForm',
                    dataType: 'json',
                    data: {metaWfmStatusId: selectedMetaWfmStatusId, metaDataId: mainMetaDataId},
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                        if (!$().colorpicker) {
                            $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                            });
                        }
                    },
                    success: function (dataHtml) {
                        var $dialogName = 'dialog-editworkflowStatus-' + selectedMetaWfmStatusId;
                        if (!$("#" + $dialogName).length) {
                            $('<div id="' + $dialogName + '"></div>').appendTo('body');
                        }
                        $("#" + $dialogName).empty().html(dataHtml.Html);
                        $("#" + $dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: dataHtml.Title,
                            width: 450,
                            height: "auto",
                            modal: true,
                            close: function () {
                                $("#" + $dialogName).empty().dialog('destroy').remove();
                            },
                            buttons: [
                                {text: dataHtml.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                                        $("#updateWfmStatus-from", "#" + $dialogName).validate({errorPlacement: function () {}});
                                        if ($("#updateWfmStatus-from", "#" + $dialogName).valid()) {
                                            $('#updateWfmStatus-from', "#" + $dialogName).ajaxSubmit({
                                                type: 'post',
                                                url: 'mdprocessflow/updateWfmStatus',
                                                dataType: 'json',
                                                beforeSend: function () {
                                                    Core.blockUI({
                                                        message: plang.get('msg_saving_block'),
                                                        boxed: true
                                                    });
                                                },
                                                success: function (data) {
                                                    if (data.status === 'success') {
                                                        new PNotify({
                                                            title: 'Success',
                                                            text: data.message,
                                                            type: 'success',
                                                            sticker: false
                                                        });
                                                        wfmWorkFlowId = data.wfmWorkFlowId;
                                                        selectJsPlumbViewBy(selectedTransitionId);
                                                        $("#" + $dialogName).empty().dialog('destroy').remove();
                                                    } else {
                                                        PNotify.removeAll();
                                                        new PNotify({
                                                            title: data.status,
                                                            text: data.message,
                                                            type: data.status,
                                                            sticker: false
                                                        });
                                                    }
                                                    Core.unblockUI();
                                                }
                                            });
                                        }
                                    }},
                                {text: dataHtml.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                        $("#" + $dialogName).empty().dialog('destroy').remove();
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
                        $("#" + $dialogName).dialog('open');
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                }).done(function () {
                    Core.initAjax();
                });
            }
            if (key === 'delete') {

                var statusId = $(this).attr('data-status-id');
                var dialogName = '#deleteConfirm-status-' + statusId;
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Сануулах',
                    width: '350',
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdprocessflow/deleteWfmStatus',
                                    dataType: 'json',
                                    data: {statusId: statusId},
                                    beforeSend: function () {
                                        Core.blockUI({
                                            message: plang.get('msg_saving_block'),
                                            boxed: true
                                        });
                                    },
                                    success: function (data) {
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false
                                            });
                                            viewVisualHtmlMetaData(mainMetaDataId);
                                            $(dialogName).empty().dialog('destroy').remove();
                                        } else {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }},
                        {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                                $(dialogName).empty().dialog('destroy').remove();
                            }}
                    ]
                });
                $(dialogName).dialog('open');
            }
        },
        items: {
            "edit": {name: plang.get('edit_btn'), icon: "edit"},
            "delete": {name: plang.get('delete_btn'), icon: "trash"}
        }
    });

    workflowConnectionImport = function (elem) {
        var common = {
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}], /*[Straight, Flowchart, Bezier, StateMachine]*/
            paintStyle: {radius: 5},
            hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 5},
            dragOptions: {
                cursor: 'pointer',
                drop: function (e, ui) {
                    console.log('drop!');
                }
            }
        };
        if (elem['PREV_WFM_STATUS_ID'] != null && elem['NEXT_WFM_STATUS_ID'] != null) {
            
            var html = '<input type="hidden" id="DESCRIPTION_' + elem['PREV_WFM_STATUS_ID'] + '_' + elem['NEXT_WFM_STATUS_ID'] + '" value="' + elem.DESCRIPTION + '"/>'
                + '<input type="hidden" id="CRITERIA_' + elem['PREV_WFM_STATUS_ID'] + '_' + elem['NEXT_WFM_STATUS_ID'] + '" value="' + elem.CRITERIA + '"/>'
                + '<input type="hidden" id="TRANSITION_TIME_' + elem['PREV_WFM_STATUS_ID'] + '_' + elem['NEXT_WFM_STATUS_ID'] + '" value="' + elem.TRANSITION_TIME + '"/>'
                + '<input type="hidden" id="TIME_TYPE_ID_' + elem['PREV_WFM_STATUS_ID'] + '_' + elem['NEXT_WFM_STATUS_ID'] + '" value="' + elem.TIME_TYPE_ID + '"/>'
                + '<input type="hidden" id="TRANSITION_COST_' + elem['PREV_WFM_STATUS_ID'] + '_' + elem['NEXT_WFM_STATUS_ID'] + '" value="' + elem.TRANSITION_COST + '"/>'
                + '<input type="hidden" id="TRANSITION_DISTANCE_' + elem['PREV_WFM_STATUS_ID'] + '_' + elem['NEXT_WFM_STATUS_ID'] + '" value="' + elem.TRANSITION_DISTANCE + '"/>';

            $("#workFlowEditor").append(html);
            
            var overlays = ['Arrow'];
            
            if (elem['DESCRIPTION']) {
                overlays.push(["Label", {label: elem['DESCRIPTION'], class: "connectionLabel", location: 0.25, id: "myLabel_" + elem['PREV_WFM_STATUS_ID'] + "_" + elem['NEXT_WFM_STATUS_ID']}]);
            }
            
            var instance = jsPlumb.connect({
                source: elem['PREV_WFM_STATUS_ID'],
                target: elem['NEXT_WFM_STATUS_ID'],
                overlays: overlays
            }, common);
        }
    }

    workflow = function (elem) {
        jsPlumb.importDefaults({
            ConnectionsDetachable: true,
            ReattachConnections: true,
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}],
            Endpoint: ["Dot", {radius: 6}],
            ConnectorZIndex: 5
        });

        windows = jsPlumb.getSelector('.wfposition');

        jsPlumb.makeSource(windows, {
            filter: ".connect",
            anchor: "Continuous",
            isSource: true,
            isTarget: true,
            dragOptions: {hoverClass: "dragHover"},
            reattach: true,
            maxConnections: 99,
            connector: [arrowStyle, {stub: [40, 60], gap: 4, cornerRadius: 0, alwaysRespectStubs: true}],
            connectorPaintStyle: {
                strokeStyle: "green",
                lineWidth: 1
            },
            connectorHoverPaintStyle: {
                strokeStyle: "#77ca00",
                outlineColor: "#77ca00",
                outlineWidth: 2
            },
            connectorStyle: {
                strokeStyle: "#5c96bc",
                lineWidth: 2,
                outlineColor: "#fff",
                outlineWidth: 2
            },
            paintStyle: {fillStyle: "transparent"},
            hoverPaintStyle: {fillStyle: "transparent", lineWidth: 5},
            Endpoint: ["Dot", {radius: 1}]
        });

        jsPlumb.makeTarget(windows, {
            isSource: true,
            isTarget: true,
            reattach: true,
            setDragAllowedWhenFull: true,
            dropOptions: {hoverClass: "dragHover"},
            anchor: "Continuous",
            paintStyle: {fillStyle: "transparent"},
            hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 7}
        });

        me.arrastrable();
    }

    me.arrastrable = function () {
        jsPlumb.draggable($(".wfposition"), {
            containment: "workFlowEditor"
        });
    }

    setIcon = function (elem) {
        var _left = elem['LEFT'];
        var _top = elem['positionTop'];
        var linkTitle = elem['WFM_STATUS_NAME'] + ' (' + elem['WFM_STATUS_CODE'] + ')';

        if (elem['ID'] == 'endObject001') {
            linkTitle = 'Төгсгөл';
        }
        /* 2018-01-29 */
        var html = '<div id="' + elem['ID'] + '" ' +
                'class="wfposition wfStatusMenu circle' + (elem['TYPE'] == 'circle' ? 'circle' : '') + ' ' + (elem['ID'] == 'startObject001' ? ' wfMenuStart-status ' : '') + (elem['childProcess'] == '1' ? ' drill-down ' : '') + '" ' + (elem['childProcess'] == '1' ? 'ondblclick="processDrillDown(this)"' : '') +
                'onclick = "clickSeeSidebarFnc(this)"' +
                'data-status-color ="' + elem['WFM_STATUS_COLOR'] + '" data-status-name="' + elem['WFM_STATUS_NAME'] + '" data-status-code="' + elem['WFM_STATUS_CODE'] + '" data-status-id="' + elem['ID'] + '" ' +
                'style="' +
                /*'width: ' + (elem['TYPE'] == 'circle' ? '30px' : '100px; ') + 
                 'height: ' + (elem['TYPE'] == 'circle' ? '30px' : '100px; ') + */
                'display: inline-block;' +
                'top: ' + elem['TOP'] + 'px; ' +
                'left: ' + elem['LEFT'] + 'px; ' +
                '"' +
                '> ' +
                '<a href="javascript:;" title="' + linkTitle + '" >' +
                '<div ' +
                'class="wfIcon ' + (elem['TYPE'] == 'circle' ? 'wfIconCircle' : '') + '" ' +
                'data-width="100" ' +
                'data-height="100" ' +
                'data-top="' + elem['TOP'] + '" ' +
                'data-left="' + elem['LEFT'] + '" ' +
                'data-type="' + (elem['TYPE'] == 'circle' ? 'wfIconCircle' : '') + '" ' +
                'data-class="' + (elem['TYPE'] == 'circle' ? 'wfIconCircle' : '') + '" ' +
                'data-metatypecode="' + elem['WFM_STATUS_CODE'] + '" ' +
                'data-outputmetadataid="' + elem['outputMetaDataId'] + '" ' +
                'data-dobpid="' + elem['ID'] + '" ' +
                'style="width: ' + (elem['TYPE'] == 'circle' ? '30px' : 'auto') + '; height:' + (elem['TYPE'] == 'circle' ? '30px' : 'auto') + '; background: ' + elem['WFM_STATUS_COLOR'] + '; padding: 6px 14px;border-radius: 25px !important;"' +
                '>';
        html += '<span class="iconText" style="position: inherit;">';
        if (elem['TYPE'] == 'rectangle') {
            html += '<div class="bp-name" style="color:#fff;font-size: 12px;">' + elem['WFM_STATUS_NAME'] + '</div>';
        }
        html += '</span>';
        html += '</div>';

        html += '<div class="connect"></div>' +
                (elem['childProcess'] == '1' ? ' <div class="drill-down-icon"><i class="icon-plus3 font-size-12"></i></div>' : '') +
                '<input type="hidden" data-outputmetadataid="' + elem['outputMetaDataId'] + '" data-boolentrueid="-1" data-boolenfalseid="-1" name="' + elem['ID'] + '" id="metaDataBoolen' + elem['ID'] + '">' +
                '</a>' +
                '</div>';
        return html;
    }

    setIconGroup = function (elem) {
        var _left = elem['positionLeft'];
        var _top = elem['positionTop'];
        var linkTitle = elem['title'] + ' (' + elem['metaDataCode'] + ')';
        if (elem['id'] == 'startObject001') {
            linkTitle = 'Эхлэл';
        }
        if (elem['id'] == 'endObject001') {
            linkTitle = 'Төгсгөл';
        }
        var html = '<div id="' + elem['id'] + '" ' +
                'class="wfposition wfpositionGroup wfMenu ' + elem['type'] + '" ondblclick="processDrillDown(this)" ' +
                'style=" width: ' + elem['width'] + 'px;  height: ' + elem['height'] + 'px; display: inline-block; top: ' + _top + 'px; left: ' + _left + 'px; "> ' +
                '<a href="javascript:;" title="' + linkTitle + '">' +
                '<div class="wfIcon ' + elem['class'] + ' " data-type="' + elem['type'] + '" data-width="' + elem['width'] + '" data-height="' + elem['height'] + '" ' +
                'data-top="' + elem['positionTop'] + '" data-left="' + elem['positionLeft'] + '" ' +
                'data-class="' + elem['class'] + '" data-title="' + elem['title'] + '" ' +
                'data-workflowid="' + elem['id'] + '" ' +
                'data-dobpid="' + elem['doBpId'] + '" >' +
                '</div>';
        html += '<span class="iconText">';
        if (elem['type'] == 'rectangle') {
            html += '<div class="bp-code">' + (elem['type'] != 'circle' ? ' (' + elem['metaDataCode'] + ')' : '') + '</div>';
            html += '<div class="bp-name">' + elem['title'] + '</div>';
        }
        html += '</span>';
        html += '<div class="connect"></div>' +
                (elem['childProcess'] == '1' ? ' <div class="drill-down-icon"><i class="icon-plus3 font-size-12"></i></div>' : '') +
                '<input type="hidden" data-boolentrueid="-1" data-boolenfalseid="-1" name="' + elem['id'] + '" id="metaDataBoolen' + elem['id'] + '">' +
                '</a>' +
                '</div>';
        return html;
    }

    setControlVal = function (elem) {
        var currentObj = elem;
        $('.wfposition').each(function () {
            $(this).removeClass('selected');
        });
        currentObj.addClass('selected');

        currentObj.find('.wfIcon').attr('data-top', currentObj.position().top);
        currentObj.find('.wfIcon').attr('data-left', currentObj.position().left);
    }

    callMetaParameter = function (mainBpId, doProcessId) {
        var dialogName = '#bpChildDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/getInputMetaParameterByProcess',
            data: {mainBpId: mainBpId, doProcessId: doProcessId},
            beforeSend: function () {
                Core.blockUI({
                    target: 'body',
                    animate: true
                });
            },
            success: function (data) {
                $(dialogName).html(data);
                $.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });

        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Бизнес процессын параметр',
            width: '1200',
            height: 'auto',
            modal: true,
            buttons: [
                {text: 'Хадгалах', class: 'btn blue btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdprocessflow/saveMetaProcessParameter',
                            data: $('#metaProcessParameter-form').serialize(),
                            dataType: "json",
                            beforeSend: function () {
                                Core.blockUI({
                                    message: 'Loading...',
                                    target: 'body',
                                    boxed: true
                                });
                            },
                            success: function (data) {
                                $(dialogName).empty().dialog('destroy').remove();
                                $.unblockUI();
                                if (data.status === 'success') {
                                    new PNotify({
                                        title: 'Success',
                                        text: 'Амжилттай хадгаллаа',
                                        type: 'success',
                                        sticker: false
                                    });
                                } else {
                                    new PNotify({
                                        title: 'Error',
                                        text: data.message,
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                            },
                            error: function () {
                                new PNotify({
                                    title: 'Error',
                                    text: 'error',
                                    type: 'error',
                                    sticker: false
                                });
                            }
                        });
                        $(dialogName).empty().dialog('destroy').remove();
                    }},
                {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function () {
                        $(dialogName).empty().dialog('destroy').remove();
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
        $(dialogName).dialogExtend("maximize");
        $(dialogName).dialog('open');
    }

    selectableCommonMetaDataGrid = function (chooseType, elem, params) {
        if (elem === 'metaGroup') {
            var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
            if (metaBasketNum > 0) {
                var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
                var wfIconClass = 'wfIconRectangle',
                        wfIconType = 'rectangle',
                        positionTop = 20,
                        wfIconWidth = 160,
                        wfIconHeight = 70,
                        bpOrder = 0,
                        wfTypeCode = 'PROCESS';

                $("#workFlowEditor").find(".wfposition").each(function () {
                    var $elem = $(this);
                    if (bpOrder < $elem.find(".wfIcon").attr('data-bporder')) {
                        bpOrder = $elem.find(".wfIcon").attr('data-bporder');
                    }
                });
                for (var i = 0; i < rows.length; i++) {
                    bpOrder = parseInt(bpOrder) + 1;
                    var row = rows[i];
                    var isAddRow = true;
                    var wfIconArray = '';
                    var metaDataId = row.META_DATA_ID;
                    var metaDataCode = row.META_DATA_CODE;
                    var metaDataName = row.META_DATA_NAME;
                    $('#workFlowEditor .wfposition').each(function () {
                        var $elem = $(this);
                        if ($elem.attr("id") === row.META_DATA_ID) {
                            alert('Нэг процесс 2 дуудагдаж байна');
                            isAddRow = false;
                        }
                    });
                    if (isAddRow) {
                        var outputMetadataId = '';
                        $.ajax({
                            type: 'post',
                            url: 'mdprocessflow/getInputOutputMetaData',
                            data: {mainBpId: metaDataId},
                            dataType: "json",
                            async: false,
                            success: function (data) {
                                outputMetadataId = data.OUTPUT_META_DATA_ID;
                            },
                            error: function () {
                                alert("Error");
                            }
                        });
                        var tempWidth = (parseInt($("#workFlowEditor").width()) - 120) - parseInt(wfIconAddPostionLeft);
                        if (parseInt(tempWidth) < 0) {
                            wfIconAddPostionLeft = 20;
                            wfIconAddPositionTop = wfIconAddPositionTop + 120;
                        }

                        if (outputMetadataId != 'undefined') {
                            if (outputMetadataId == wfObjectBoolean) {
                                wfTypeCode = 'EXPRESSION';
                                wfIconType = 'rombo';
                                wfIconClass = 'wfIconRombo';
                                wfIconWidth = 100;
                                wfIconHeight = 100;
                                wfIconAddPostionLeft = 80;
                            }
                        }
                        wfIconArray = {
                            outputMetaDataId: outputMetadataId,
                            metaProcessWorkFlowId: '',
                            metaTypeCode: wfTypeCode,
                            bpOrder: bpOrder,
                            id: metaDataId,
                            title: metaDataName,
                            type: wfIconType,
                            class: wfIconClass,
                            positionTop: wfIconAddPositionTop,
                            positionLeft: wfIconAddPostionLeft,
                            width: wfIconWidth,
                            height: wfIconHeight,
                            metaDataCode: metaDataCode
                        };

                        $('#workFlowEditor').append(setIcon(wfIconArray));
                        workflow(wfIconArray);

                        wfIconAddPostionLeft = wfIconAddPostionLeft + 180;

                        $('.wfposition').draggable({
                            containment: '#workFlowEditor',
                            stop: function () {
                                selectedObj = $(this);
                                setControlVal(selectedObj);
                                saveWfmStatusPosition(selectedObj.position().top, selectedObj.position().left, selectedObj.attr('id'));
                            }
                        });
                    }
                }
            }
        }
    }

    if ($(jsPlumb)) {
        /*
         jsPlumb.bind("connectionDragStop", function(info){ 
         $.ajax({
         type: 'post',
         url: 'mdprocessflow/updateWorkflowStatusTransition',
         data: {target: (isNaN(info.target.id) ? '' : info.target.id), source : (isNaN(info.source.id) ? '' : info.source.id)},
         dataType: 'json',
         beforeSend: function () {
         Core.blockUI({
         target: "#workFlowEditor",
         animate: true
         });
         },
         success: function (data) {
         Core.unblockUI('#workFlowEditor');
         },
         error: function () {
         }
         });   
         });*/
    }

    /*$("body").off('click', metaProcessWindowId + ' .stoggler');*/

    $("body").on('click', metaProcessWindowId + ' .stoggler', function () {

        var _thisToggler = $(this);
        var centersidebar = $(".center-sidebar", metaProcessWindowId);
        var rightsidebar = $(".right-sidebar", metaProcessWindowId);
        var rightsidebarstatus = rightsidebar.attr("data-status");
        if (rightsidebarstatus === "closed") {
            centersidebar.removeClass("col-md-12").addClass("col-md-8");
            rightsidebar.addClass("col-md-4");
            rightsidebar.find(".glyphicon-chevron-right").parent().hide();
            rightsidebar.find(".glyphicon-chevron-left").hide();
            rightsidebar.find(".right-sidebar-content").show(
                    "slide", {direction: "right"}, 600,
                    function () {
                        rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn("slow");
                        rightsidebar.find(".glyphicon-chevron-right").fadeIn("slow");
                    }
            );
            rightsidebar.attr('data-status', 'opened');
            _thisToggler.addClass("sidebar-opened");
        } else {
            rightsidebar.find(".glyphicon-chevron-right").hide();
            rightsidebar.find(".glyphicon-chevron-right").parent().hide();
            rightsidebar.find(".right-sidebar-content").hide(
                    "slide", {direction: "right"}, 600,
                    function () {
                        centersidebar.removeClass("col-md-8").addClass("col-md-12");
                        rightsidebar.removeClass("col-md-4");
                        rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");
                        rightsidebar.find(".glyphicon-chevron-left").fadeIn("slow");
                    }
            );
            rightsidebar.attr('data-status', 'closed');
            _thisToggler.removeClass("sidebar-opened");
        }
    });

    $("body").on("mouseover", metaProcessWindowId + ' .stoggler', function () {
        $(this).css({
            "background-color": "rgba(230, 230, 230, 0.80)",
            "border-right": "1px solid rgba(230, 230, 230, 0.80)"
        });
    });

    $("body").on("mouseleave", metaProcessWindowId + ' .stoggler', function () {
        $(this).css({
            "background-color": "#FFF",
            "border-right": "#FFF"
        });
    });
    /*
     $('#metaProcessDetial').on('click', '.saveVisualParam', function () {
     saveVisualMetaData('', $("#mainBpId").val());
     }); */
});

function viewVisualHtmlMetaData(mainBpId, transId, selftype) {
    if (mainBpId != '') {
        mainMetaDataId = mainBpId;
        transId = transId;

        $.ajax({
            type: 'post',
            url: 'mdprocessflow/getWorkFlow',
            data: {metaDataId: mainBpId},
            dataType: 'json',
            success: function (data) {
                $('#metaProcessDetial').html('');
                wfmWorkFlowStatus = data.workFlowStatus;
                workFlowDrawHtml(mainBpId, data, transId, selftype);
                if (data.status === 'success') {
                    wfmWorkFlowId = data.workFlowId;
                    $('.workflowLiClass').removeClass('active');
                    $('.wfmWorkFlowId_' + data.workFlowId).addClass('active');

                    $('#workFlowEditor').html('');
                    var _linkWorkFlowId = (typeof linkWorkFlowId != 'undefined' && linkWorkFlowId != '0') ? linkWorkFlowId : data.workFlowId;
                }
            }
        });
    }
}

function addMetaWorkFlowFuntion() {
    var metaDataId = $("#metaDataId_valueField").val();
    if (metaDataId === '') {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Choose Meta.',
            type: 'warning',
            sticker: false
        });
        return;
    }
    var $dialogName = 'dialog-addworkflow-' + metaDataId;

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $("#" + $dialogName).empty().html(
            '<div class="col-md-12">'
            + '<table class="table table-sm table-no-bordered" style="table-layout: fixed !important">'
            + '<tbody>'
            + '<tr>'
            + '<td class="text-right middle" style="width: 45%">'
            + '<label for="workFlowCode" data-label-path="title">Ажлын урсгалын код:</label>'
            + '</td>'
            + '<td class="middle" style="width: 55%" colspan="">'
            + '<div data-section-path="workFlowCode">'
            + '<input type="text" id="workFlowCode" placeholder="Ажлын урсгалын код" class="form-control form-control-sm">'
            + '</div>'
            + '</td>'
            + '</tr>'
            + '<tr>'
            + '<td class="text-right middle" style="width: 45%">'
            + '<label for="workFlowName" data-label-path="title">Ажлын урсгалын нэр:</label>'
            + '</td>'
            + '<td class="middle" style="width: 55%" colspan="">'
            + '<div data-section-path="workFlowName">'
            + '<input type="text" id="workFlowName" placeholder="Ажлын урсгалын нэр" class="form-control form-control-sm">'
            + '</div>'
            + '</td>'
            + '</tr>'
            + '</tbody>'
            + '</table>'
            + '</div>');
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: "Ажлын урсгал нэмэх",
        width: 450,
        height: 'auto',
        modal: true,
        close: function () {
            $("#" + $dialogName).empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                    var workFlowName = $('#workFlowName').val();
                    var workFlowCode = $('#workFlowCode').val();

                    $('#workFlowName').removeClass('error');
                    $('#workFlowCode').removeClass('error');

                    if (workFlowName.length === 0 && workFlowCode.length === 0) {
                        $('#workFlowName').addClass('error');
                        $('#workFlowCode').addClass('error');
                        return;
                    }

                    if (workFlowName.length === 0) {
                        $('#workFlowName').addClass('error');
                        return;
                    }

                    if (workFlowCode.length === 0) {
                        $('#workFlowCode').addClass('error');
                        return;
                    }

                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/createWfmWorkFlow',
                        dataType: "json",
                        data: {metaDataId: metaDataId, workFlowName: workFlowName, workFlowCode: workFlowCode},
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true
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
                            if (data.status === 'success') {
                                wfmWorkFlowId = data.wfmWorkFlowId;
                                viewVisualHtmlMetaData(metaDataId);
                                $("#" + $dialogName).empty().dialog('destroy').remove();
                            }
                            Core.unblockUI();
                        },
                        error: function () {
                            alert("Error");
                        }
                    });
                }
            },
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                }
            }
        ]
    });
    $("#" + $dialogName).dialog('open');
}

function editMetaWorkFlowFuntion(metaDataId) {
    if (typeof wfmWorkFlowId != 'undefined' && wfmWorkFlowId === '0') {
        alert('Ажлын урсгалаа сонгоно уу?');
        return;
    }

    var $dialogName = 'dialog-addworkflow-' + metaDataId;

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/getMetaWfmWorkFlowData',
        dataType: "json",
        data: {metaDataId: metaDataId, wfmWorkFlowId: wfmWorkFlowId},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            Core.unblockUI();
            $("#" + $dialogName).empty().html(
                    '<div class="col-md-12">'
                    + '<table class="table table-sm table-no-bordered" style="table-layout: fixed !important">'
                    + '<input type="hidden" id="workFlowId" value="' + data.data['ID'] + '">'
                    + '<tbody>'
                    + '<tr>'
                    + '<td class="text-right middle" style="width: 45%">'
                    + '<label for="workFlowCode" data-label-path="title">Ажлын урсгалын код:</label>'
                    + '</td>'
                    + '<td class="middle" style="width: 55%" colspan="">'
                    + '<div data-section-path="workFlowCode">'
                    + '<input type="text" id="workFlowCode" placeholder="Ажлын урсгалын код" class="form-control form-control-sm" value="' + data.data['WFM_WORKFLOW_CODE'] + '">'
                    + '</div>'
                    + '</td>'
                    + '</tr>'
                    + '<tr>'
                    + '<td class="text-right middle" style="width: 45%">'
                    + '<label for="workFlowName" data-label-path="title">Ажлын урсгалын нэр:</label>'
                    + '</td>'
                    + '<td class="middle" style="width: 55%" colspan="">'
                    + '<div data-section-path="workFlowName">'
                    + '<input type="text" id="workFlowName" placeholder="Ажлын урсгалын нэр" class="form-control form-control-sm" value="' + data.data['WFM_WORKFLOW_NAME'] + '">'
                    + '</div>'
                    + '</td>'
                    + '</tr>'
                    + '</tbody>'
                    + '</table>'
                    + '</div>');
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: "Ажлын урсгал нэмэх",
                width: 450,
                height: 'auto',
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                            var workFlowName = $('#workFlowName').val();
                            var workFlowCode = $('#workFlowCode').val();
                            var workFlowId = $('#workFlowId').val();

                            $('#workFlowName').removeClass('error');
                            $('#workFlowCode').removeClass('error');

                            if (workFlowName.length === 0 && workFlowCode.length === 0) {
                                $('#workFlowName').addClass('error');
                                $('#workFlowCode').addClass('error');
                                return;
                            }

                            if (workFlowName.length === 0) {
                                $('#workFlowName').addClass('error');
                                return;
                            }

                            if (workFlowCode.length === 0) {
                                $('#workFlowCode').addClass('error');
                                return;
                            }

                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/updateWfmWorkFlow',
                                dataType: "json",
                                data: {metaDataId: metaDataId, workFlowName: workFlowName, workFlowCode: workFlowCode, workFlowId: workFlowId},
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
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
                                    if (data.status === 'success') {
                                        wfmWorkFlowId = data.wfmWorkFlowId;
                                        viewVisualHtmlMetaData(metaDataId);
                                        $("#" + $dialogName).empty().dialog('destroy').remove();
                                    }
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        }
                    },
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $("#" + $dialogName).empty().dialog('destroy').remove();
                        }
                    }
                ]
            });
            $("#" + $dialogName).dialog('open');
        },
        error: function () {
            alert("Error");
        }
    });

}

function addMetaWorkFlowStatusFuntion(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/addWorkFlowStatusForm',
        dataType: 'json',
        data: {metaDataId: metaDataId, transitionId: ''},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
            if (!$().colorpicker) {
                $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                });
            }
        },
        success: function (dataHtml) {
            var $dialogName = 'dialog-addworkflowStatus-' + metaDataId;
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            $("#" + $dialogName).empty().html(dataHtml.Html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: dataHtml.Title,
                width: 450,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: dataHtml.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                            $("#createWfmStatus-from", "#" + $dialogName).validate({errorPlacement: function () {}});
                            if ($("#createWfmStatus-from", "#" + $dialogName).valid()) {
                                $('#createWfmStatus-from').ajaxSubmit({
                                    type: 'post',
                                    url: 'mdprocessflow/createNewWfmStatus',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({
                                            message: plang.get('msg_saving_block'),
                                            boxed: true
                                        });
                                    },
                                    success: function (data) {
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false
                                            });
                                            mainAppendWfmStatusDroppableContent(data.workFlowStatus);
                                            $("#" + $dialogName).empty().dialog('destroy').remove();
                                        } else {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }},
                    {text: dataHtml.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $("#" + $dialogName).empty().dialog('destroy').remove();
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
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}

function runWorkFlowByWfmStatus(workFlowId, metaDataId) {
    $('.workflowLiClass').removeClass('active');
    $('.wfmWorkFlowId_' + workFlowId).addClass('active');
    $('.editMetaWorkFlowFuntion_' + metaDataId).removeClass('hidden');
    $('.createWfmStatus_' + metaDataId).removeClass('hidden');

    if (metaDataId != '') {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/drawWorkFlowProcess',
            data: {metaDataId: metaDataId, workFlowId: workFlowId},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: "#workFlowEditor",
                    animate: true
                });
            },
            success: function (data) {
                $('#workFlowEditor').html('');
                if ($(jsPlumb)) {
                    statusAllConnections(data);
                    Core.unblockUI('#workFlowEditor');
                }
            },
            error: function () {
            }
        });
    }

}

function saveWfmStatusPosition(positionTop, positionLeft, wfmStatusId) {
    if (isNaN(parseFloat(wfmStatusId))) {
        return false;
    }
    /*
     $.ajax({
     type: 'post',
     url: 'mdprocessflow/updateWorkflowStatus',
     data: {wfmStatusId: wfmStatusId, positionTop: positionTop, positionLeft: positionLeft},
     dataType: 'json',
     beforeSend: function () {
     Core.blockUI({
     target: "#workFlowEditor",
     animate: true
     });
     },
     success: function (data) {
     Core.unblockUI('#workFlowEditor');
     },
     error: function () {
     }
     });   
     */
}

function clickSeeSidebarFnc(element) {
    var $wfmStatusId = $(element).attr('id');
    if (isNaN(parseFloat($wfmStatusId))) {
        return false;
    }
    tempWfmStatusId = $wfmStatusId;
    wfmStatusSideBarReload(tempWfmStatusId);
}

function wfmStatusSideBarReload($wfmStatusId) {
    var $rightColumn = $('.pivotgrid-table-center-right-cell');
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/wfmStatusForm',
        dataType: 'json',
        data: {
            wfmStatusId: $wfmStatusId, 
            metaDataId: mainMetaDataId, 
            transitionId: $rightColumn.attr('data-transitionid'), 
            fromType: wfmFromType
        },
        beforeSend: function () {
            Core.blockUI({target: $rightColumn, animate: true});
        },
        success: function (dataHtml) {
            
            isWfmLock = (dataHtml.hasOwnProperty('isLock') && dataHtml.isLock) ? true : false;
            var _wfmStatusHtml = dataHtml.wfmStatusHtml;
            var $wfmStatusRightSidebar = $('.wfm-status-transition-permission');
            
            if (!isWfmShowOnly && !isWfmLock) {
                _wfmStatusHtml += '<div class="row"><div class="col-md-12"><button type="button" class="btn btn-success btn-circle btn-sm float-right saveWfmStatusRightSideBar">'+plang.get('save_btn')+'</button></div></div>';
            }
            
            $wfmStatusRightSidebar.empty().append(dataHtml.Html).promise().done(function() {
                if (isWfmShowOnly || isWfmLock) {
                    $wfmStatusRightSidebar.find('.wfm-status-add-toolbar').remove();
                }
            });
            
            if ($("link[href='assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css']").length == 0) {
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
            }
            if ($("link[href='assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css']").length == 0) {
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
            }
        
            $.cachedScript("assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1").done(function () {

                $.cachedScript("assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js").done(function () {

                    $('.wfm-status-editable-update').empty().append(_wfmStatusHtml);
                    Core.initAjax($('.wfm-status-editable-update'));
                    
                    $('.saveWfmStatusRightSideBar').off('click').on('click', function () {
                        $("#updateWfmStatus-from", ".wfm-status-editable-update").validate({errorPlacement: function () {}});
                        if ($("#updateWfmStatus-from", ".wfm-status-editable-update").valid()) {
                            $('#updateWfmStatus-from', ".wfm-status-editable-update").ajaxSubmit({
                                type: 'post',
                                url: 'mdprocessflow/updateWfmStatus',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                },
                                success: function (data) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                        
                                    if (data.status === 'success') {
                                        wfmWorkFlowId = data.wfmWorkFlowId;
                                        if (selectedTransitionId !== '0') {
                                            selectJsPlumbViewBy(selectedTransitionId);
                                            wfmStatusSideBarReload($wfmStatusId);
                                        }
                                    } 
                                    Core.unblockUI();
                                }
                            });
                        }
                    });
                });
            });

            $('#tabid_wfmStatusCfgUserProcess_' + mainMetaDataId).removeClass('active');
            $('#wfmStatus_' + mainMetaDataId).removeClass('active');
            $('#wfmStatusRoleProcess_' + mainMetaDataId).addClass('active');
            $('#tabid_wfmStatusCfgRoleProcess_' + mainMetaDataId).addClass('active');

            Core.unblockUI($rightColumn);
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax($rightColumn);
    });
}
function pfWfmFullScreenMode(elem) {
    var $this = $(elem), $parent = $this.closest('.metaWfmStatusForm');
    var $getHeightsDiv = $parent.find('[data-set-height="1"]');
    
    if (!$this.hasAttr('data-fullscreen')) {
        
        var getHeight = $getHeightsDiv.eq(0).css('height');
        var windowHeight = $(window).height() - 50;
        
        $this.attr({'data-fullscreen': '1', 'title': 'Restore', 'data-old-height': getHeight}).find('i').removeClass('fa-expand').addClass('fa-compress');
        $parent.addClass('bp-dtl-fullscreen');
        
        $getHeightsDiv.css({'max-height': windowHeight, 'height': windowHeight});
        
    } else {
        var oldHeight = $this.attr('data-old-height');
        
        $this.attr('title', 'Fullscreen').removeAttr('data-fullscreen').find('i').removeClass('fa-compress').addClass('fa-expand');
        $parent.removeClass('bp-dtl-fullscreen');
        
        $getHeightsDiv.css({'max-height': oldHeight, 'height': oldHeight});
    }
}
function workFlowDrawHtml(mainBpId, data, transId, selftype) {
    
    var $metaProcessDetail = $('#metaProcessDetial');
    var windowHeight = $(window).height();
    var _mainWindowHeight = windowHeight - 143, mainHtml = '';
    
    if (typeof isWfmShowOnly !== 'undefined' && isWfmShowOnly) {
        _mainWindowHeight = _mainWindowHeight + 40;
    }
    
    if (typeof isWfmMainWindow !== 'undefined' && isWfmMainWindow) {
        _mainWindowHeight = windowHeight - $metaProcessDetail.offset().top - 80;
    }
    
    if ((typeof isWfmShowOnly !== 'undefined' && !isWfmShowOnly) || typeof isWfmShowOnly == 'undefined') {
        
        mainHtml += '<div class="col-md-auto pl0" style="width: 262px">'
                    + '<div class="mb10 btn-group btn-group-devided">'
                        + '<button type="button" class="btn btn-secondary rounded-round btn-sm" onclick="popupConnectAddWorkFlow1(\'' + mainBpId + '\')"><i class="icon-plus3 font-size-12"></i> Ажлын урсгал нэмэх</button>'
                    + '</div>'
                + '</div>';
        
        mainHtml += '<div class="col">';
        
            if (typeof isWfmMainWindow !== 'undefined' && isWfmMainWindow) {
                mainHtml += '<button type="button" class="btn btn-outline-success rounded-round btn-sm float-left" onclick="saveVisualMetaStatusData(this);">'+plang.get('save_btn')+'</button>';
                mainHtml += '<button type="button" class="btn btn-secondary rounded-round btn-sm float-right" onclick="pfWfmFullScreenMode(this);" title="Fullscreen"><i class="far fa-expand"></i></button>';
            }
        
        mainHtml += '</div>';
        
        mainHtml += '<div class="col-md-auto pl0" style="width: 411px;min-width: 411px;max-width: 411px;">'
                    + '<div class="mb10 mt0 ph0 border-0">'
                        + '<button type="button" class="btn btn-outline-primary rounded-round btn-sm" onclick="checkSelectedTransitionId(this);"><i class="icon-plus3 font-size-12"></i> Төлөв нэмэх</button>'
                        + ((typeof selftype !== 'undefined' && selftype === 'selfurl') ? '<button type="button" class="btn btn-sm btn-circle btn-success savevisual-btn" onclick="saveVisualMetaStatusData();"><i class="icon-checkmark-circle2"></i> Хадгалах</button>' : '')
                    + '</div>'
                + '</div>';
    }
    
    mainHtml += '<div class="col-md-12 pivotgrid-table">'
            + '<div class="pivotgrid-table-left-cell" data-set-height="1" style="width: 250px !important; min-width: 250px !important; max-height: ' + _mainWindowHeight + 'px; height: ' + _mainWindowHeight + 'px; border: 1px solid #999;">'
            + '<div class="slimScrollDiv list-jtree-' + mainBpId + '" data-set-height="1" style="position: relative;  width: auto; height: ' + _mainWindowHeight + 'px;">'
            + '</div>'
            + '</div>'
            + '<div class="pivotgrid-table-collapse-cell"></div>'
            + '<div class="pivotgrid-table-right-cell pv-grid pivotgrid-table-right-cell-inside">'
            + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
            + '<div class="heigh-editor" data-set-height="1" style="max-height: ' + _mainWindowHeight + 'px; height: ' + _mainWindowHeight + 'px; border-radius: 0;">'
            + '<button type="button" class="btn btn-light rounded-round btn-sm wfmConnectionLabelToggleBtn" onclick="wfmConnectionLabelToggle(this);" style="position: absolute;bottom: 10px;left: 18px;z-index: 9999;"><i class="far fa-eye-slash"></i> Тайлбар нуух</button>'
            + '<button type="button" class="btn btn-light rounded-round btn-sm" onclick="wfmConnectionToPng(this);" style="position: absolute;bottom: 10px;left: 144px;z-index: 9999;"><i class="far fa-file-image"></i> Зураг татах</button>'
            + '<input type="hidden" value="0" name="clickBoolenTrue"><input type="hidden" value="0" name="clickBoolenFalse">'
            + '<div class="css-editor" id="workFlowEditor" style="width:100%;float: left;height: 2000px;"></div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="pivotgrid-table-center-right-collapse-cell"></div>'
            + '<div class="pivotgrid-table-center-right-cell" data-status="open" style="border: 1px solid #999; margin-top:0px; max-height: ' + _mainWindowHeight + 'px; height: ' + _mainWindowHeight + 'px; display: table-cell; vertical-align: top; margin: 0; padding: 0 0 0 10px; width: 400px; min-width: 400px; max-width: 400px;">'
            + '<div data-set-height="1" style="max-height: ' + _mainWindowHeight + 'px; height: ' + _mainWindowHeight + 'px; overflow-x: hidden; overflow-y: auto;">'
            + '<div class="tabbable-line">'
                + '<ul class="nav nav-tabs">'
                    + '<li class="nav-item">'
                        + '<a href="#wfmStatus_' + mainBpId + '" id="tabid_wfmStatusCfgUserProcess_' + mainBpId + '" data-toggle="tab" class="nav-link active">Төлөв</a>'
                    + '</li>'
                    + '<li class="nav-item">'
                        + '<a href="#wfmStatusRoleProcess_' + mainBpId + '" id="tabid_wfmStatusCfgRoleProcess_' + mainBpId + '" data-toggle="tab" class="nav-link">Тохиргоо</a>'
                    + '</li>'
                + '</ul>'
            + '<div class="tab-content">'
            + '<div class="tab-pane active" id="wfmStatus_' + mainBpId + '">';
    mainHtml += '<div class="col-md-12 status-ui-droppable " style="margin-left: -14px;">'
    if (wfmWorkFlowStatus.length != 0) {
        $.each(wfmWorkFlowStatus, function (ind, status) {
            var fontColor = 'color: #fff;';
            mainHtml += '<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 ' + status.WFM_STATUS_COLOR + ' " data-status-color = "' + status.WFM_STATUS_COLOR + '" style= "background-color:' + status.WFM_STATUS_COLOR + '; ' + fontColor + '" data-status-name="' + status.WFM_STATUS_NAME + '" data-status-code="' + status.WFM_STATUS_CODE + '" data-status-id="' + status.WFM_STATUS_ID + '" target="_self">'
                    + '<span class="title">' + status.WFM_STATUS_NAME + '</span>'
                    + '</a>';
        });
    }

    mainHtml += '</div>'
            + '</div>'
            + '<div class="tab-pane" id="wfmStatusRoleProcess_' + mainBpId + '">'
            + '<div class="wfm-status-editable-update xs-form pl0" style="padding-right: 10px !important;"></div>'
            + '<div class="w-100"></div>'
            + '<div class="wfm-status-transition-permission col-md-12 pl0 pr0" style="min-height: 300px; max-height: 300px;"></div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>';
    
    $metaProcessDetail.empty().append(mainHtml);

    $('.list-jtree-' + mainBpId).on("changed.jstree", function (e, data) {
        $('.list-jtree-' + mainBpId).jstree(data.action, data.node.id, true, true);
    }).jstree({
        "core": {
            "themes": {
                "responsive": true
            },
            "check_callback": true,
            'data': {
                type: 'post',
                url: 'mdprocessflow/getTransitionListJtreeData',
                data: {metaDataId: mainMetaDataId, transId: transId},
                dataType: 'json'
            }
        },
        "types": {
            "default": {
                "icon": "icon-folder2 text-orange-300"
            }
        },
        "plugins": ["types", "cookies", "dnd"]
    }).bind("select_node.jstree", function (e, data) {
        var nid = data.node.id === 'null' ? '' : data.node.id;
        selectJsPlumbViewBy(nid);
    });

    $.contextMenu({
        selector: '.list-jtree-' + mainBpId + ' li.jstree-node',
        events: {
            show: function(opt) {
                if ((typeof isWfmShowOnly !== 'undefined' && !isWfmShowOnly) || typeof isWfmShowOnly == 'undefined') {
                    return true;
                } else {
                    return false;
                }
            }
        },
        build: function($trigger, e) {
            
            var transitionId = $trigger.attr('id');
            var $iconElem = $trigger.find('.jstree-icon.jstree-themeicon');
            var contextMenuData = {};
            var exportItem = {
                name: plang.get('export_btn'), 
                icon: 'download', 
                callback: function(key, options) {
                    var $dialogName = 'dialog-workflowsingle-export';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $("#" + $dialogName);
                    var data = '<div class="mt10"><input type="checkbox" id="workflowIsUserExport"> <label for="workflowIsUserExport">Хэрэглэгч</label></div>';
                    data += '<div><input type="checkbox" id="workflowIsRoleExport"> <label for="workflowIsRoleExport">Дүр</label></div>';
                    data += '<div><input type="checkbox" id="workflowIsNotificationExport"> <label for="workflowIsNotificationExport">Сонордуулга</label></div>';

                    $dialog.empty().append(data);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Экспорт тохиргоо',
                        width: 350,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [{
                                text: 'Экспорт',
                                class: 'btn yellow-gold btn-circle btn-sm',
                                click: function () {
                                    var $isCheckedUser = $dialog.find('#workflowIsUserExport').is(':checked') ? '1' : '0', paramData = [];
                                    paramData.push({
                                        'name': 'workflowIsUserExport',
                                        'value': $isCheckedUser
                                    });

                                    $isCheckedUser = $dialog.find('#workflowIsRoleExport').is(':checked') ? '1' : '0';
                                    paramData.push({
                                        'name': 'workflowIsRoleExport',
                                        'value': $isCheckedUser
                                    });
                                    $isCheckedUser = $dialog.find('#workflowIsNotificationExport').is(':checked') ? '1' : '0';
                                    paramData.push({
                                        'name': 'workflowIsNotificationExport',
                                        'value': $isCheckedUser
                                    });
                                    paramData.push({
                                        'name': 'transitionId',
                                        'value': transitionId
                                    });

                                    Core.blockUI({boxed: true, message: 'Exporting...'});

                                    $.fileDownload(URL_APP + 'mdprocessflow/exportWorkflowSingle', {
                                        httpMethod: 'POST',
                                        data: paramData
                                    }).done(function () {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Success',
                                            text: 'Successful Export',
                                            type: 'success',
                                            sticker: false
                                        });
                                        Core.unblockUI();
                                    }).fail(function (msg, url) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Error',
                                            text: msg,
                                            type: 'error',
                                            sticker: false
                                        });
                                        Core.unblockUI();
                                    });
                                    $dialog.dialog('close');
                                }
                            }]
                    });
                    $dialog.dialog('open');
                    Core.initAjax($dialog);
                }
            };
            
            if ($iconElem.hasClass('fa-lock')) {
                
                contextMenuData = {
                    "export": exportItem,
                    "unlock": {
                        name: 'Түгжээг цуцлах', 
                        icon: 'unlock', 
                        callback: function(key, options) {
                            var dialogName = '#dialog-unlocktransition-' + transitionId;
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $dialog = $(dialogName), html = [], wfName = $trigger.find('.jstree-anchor').text();

                            html.push('<form>');
                                html.push('<div class="form-group row mb-2">');
                                    html.push('<label class="col-md-3 col-form-label text-right pr0">Нэр:</label>');
                                    html.push('<div class="col-md-9">'+wfName+'</div>');
                                html.push('</div>');
                                html.push('<div class="form-group row mb-2">');
                                    html.push('<label class="col-md-3 col-form-label text-right pr0">Нууц үг:</label>');
                                    html.push('<div class="col-md-9"><input type="password" class="form-control readonly-white-bg" name="unlockPassword" required="required" readonly="readonly" onfocus="this.removeAttribute(\'readonly\');"></div>');
                                html.push('</div>');
                            html.push('</form>');

                            $dialog.html(html.join(''));
                            $dialog.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Түгжээг цуцлах',
                                width: 500,
                                height: 'auto',
                                modal: true,
                                open: function() {
                                    $(this).keypress(function(e) {
                                        if (e.keyCode == $.ui.keyCode.ENTER) {
                                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger('click');
                                        }
                                    });
                                },
                                close: function () {
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: 'Түгжээг цуцлах', class: 'btn green-meadow btn-sm', click: function () {
                                        PNotify.removeAll();

                                        var $form = $dialog.find('form');
                                        $form.validate({
                                            rules: {
                                                unlockPassword: {required: true}
                                            },
                                            messages: {
                                                unlockPassword: {required: plang.get('user_insert_password')}
                                            }
                                        });

                                        if ($form.valid()) {
                                            $form.ajaxSubmit({
                                                type: 'post',
                                                url: 'mdprocessflow/unlockWfmTransition',
                                                dataType: 'json',
                                                beforeSubmit: function(formData, jqForm, options) {
                                                    formData.push({name: 'transitionId', value: transitionId});
                                                },
                                                beforeSend: function () {
                                                    Core.blockUI({message: 'Loading...', boxed: true});
                                                },
                                                success: function (data) {
                                                    new PNotify({
                                                        title: data.status,
                                                        text: data.message,
                                                        type: data.status,
                                                        sticker: false
                                                    });

                                                    if (data.status === 'success') {

                                                        $iconElem.removeClass('fa-lock').addClass('fa-folder text-orange-400');

                                                        var $rightColumn = $('.pivotgrid-table-center-right-cell');
                                                        if (transitionId == $rightColumn.attr('data-transitionid')) {
                                                            $rightColumn.attr('data-islock', '0');
                                                            $rightColumn.find('.saveWfmStatusRightSideBar').removeClass('d-none');
                                                            $('.save-main-wfm-button').show();
                                                        }

                                                        $dialog.dialog('close');
                                                    }
                                                    Core.unblockUI();
                                                }
                                            });
                                        }
                                    }},
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            $dialog.dialog('open');
                        }
                    }
                };
                
            } else {
                
                contextMenuData = {
                    "edit": {
                        name: plang.get('edit_btn'), 
                        icon: 'edit', 
                        callback: function(key, options) {

                            var $dialogName = 'dialog-editworkflowStatus-' + transitionId;
                            if (!$("#" + $dialogName).length) {
                                $('<div id="' + $dialogName + '"></div>').appendTo('body');
                            }
                            var $dialog = $('#' + $dialogName);

                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/editWorkFlowFirstStatusForm',
                                dataType: 'json',
                                data: {transitionId: transitionId, metaDataId: mainMetaDataId},
                                beforeSend: function () {
                                    Core.blockUI({animate: true});
                                    if (!$("link[href='middleware/assets/css/salary/expression.css']").length) {
                                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css"/>');
                                    }
                                },
                                success: function (dataHtml) {

                                    $dialog.empty().append(dataHtml.Html);
                                    $dialog.dialog({
                                        cache: false,
                                        resizable: true,
                                        bgiframe: true,
                                        autoOpen: false,
                                        title: dataHtml.Title,
                                        width: 1100,
                                        height: "auto",
                                        modal: true,
                                        close: function () {
                                            $dialog.empty().dialog('destroy').remove();
                                        },
                                        buttons: [
                                            {text: dataHtml.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                                                $("#updateWfmTransition-from", "#" + $dialogName).validate({errorPlacement: function () {}});
                                                if ($("#updateWfmTransition-from", "#" + $dialogName).valid()) {
                                                    PNotify.removeAll();

                                                    var expArea = $dialog.find('.p-exp-area');
                                                    var expAreaContent = $.trim(expArea.html());
                                                    $('#updateWfmTransition-from').find('input[name="bpCriteria"]').val(expAreaContent);

                                                    $('#updateWfmTransition-from').ajaxSubmit({
                                                        type: 'post',
                                                        url: 'mdprocessflow/updateWfmWorkFlowTransition',
                                                        dataType: 'json',
                                                        beforeSend: function () {
                                                            Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                                        },
                                                        success: function (data) {
                                                            new PNotify({
                                                                title: data.status,
                                                                text: data.message,
                                                                type: data.status,
                                                                sticker: false
                                                            });
                                                            if (data.status === 'success') {
                                                                viewVisualHtmlMetaData(mainMetaDataId, transId, selftype);
                                                                $dialog.dialog('close');
                                                            }
                                                            Core.unblockUI();
                                                        }
                                                    });
                                                }
                                            }},
                                            {text: dataHtml.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                                $dialog.dialog('close');
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
                                error: function () { alert("Error"); }

                            }).done(function () { Core.initAjax($dialog); });
                        }
                    },
                    "copy": {
                        name: plang.get('copy_btn'), 
                        icon: 'copy', 
                        callback: function(key, options) {

                            var $dialogName = 'dialog-workflow-copy';
                            if (!$("#" + $dialogName).length) {
                                $('<div id="' + $dialogName + '"></div>').appendTo('body');
                            }
                            var $dialog = $("#" + $dialogName);
                            var prevName = $trigger.find('.jstree-anchor').text();
                            var form = [];
                            
                            form.push('<form method="post">');
                                form.push('<div class="col-md-12 xs-form">');

                                    form.push('<div class="form-group row mt10">');
                                        form.push('<label class="col-form-label col-md-4 text-right pt-1"><span class="required">*</span>'+plang.get('wf_name')+':</label>');
                                        form.push('<div class="col-md-8">');
                                            form.push('<input type="text" class="form-control" value="' + prevName + '" required="required"/>');
                                        form.push('</div>');
                                    form.push('</div>');

                                    form.push('<div class="form-group row mt20 mb10">');
                                        form.push('<label class="col-form-label col-md-4 text-right" for="isPermission">Эрхтэй хуулах:</label>');
                                        form.push('<div class="col-md-8">');
                                            form.push('<input type="checkbox" name="isPermission" id="isPermission" class="booleanInit" value="1">');
                                        form.push('</div>');
                                    form.push('</div>');

                                form.push('</div>');
                            form.push('</form>');

                            $dialog.empty().append(form.join(''));
                            $dialog.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('copy_btn'),
                                width: 510,
                                height: "auto",
                                modal: true,
                                close: function () {
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [{
                                        text: plang.get('copy_btn'),
                                        class: 'btn green-meadow btn-circle btn-sm',
                                        click: function () {

                                            PNotify.removeAll();

                                            var $form = $dialog.find('form');
                                            $form.validate({errorPlacement: function () {}});

                                            if ($form.valid()) {
                                                var newName = $dialog.find('input[type="text"]').val();

                                                if (prevName.toLowerCase() != newName.toLowerCase()) {
                                                    
                                                    var isPermission = $dialog.find('input[name="isPermission"]').is(':checked') ? 1 : '';
                                                    
                                                    $.ajax({
                                                        type: 'post',
                                                        url: 'mdprocessflow/copyWfmWorkFlowTransition',
                                                        data: {transitionId: transitionId, newName: newName, isPermission: isPermission},
                                                        dataType: 'json',
                                                        beforeSend: function () {
                                                            Core.blockUI({message: 'Loading...', boxed: true});
                                                        },
                                                        success: function (data) {
                                                            new PNotify({
                                                                title: data.status,
                                                                text: data.message,
                                                                type: data.status,
                                                                sticker: false
                                                            });
                                                            if (data.status === 'success') {
                                                                viewVisualHtmlMetaData(mainMetaDataId, transId, selftype);
                                                                $dialog.dialog('close');
                                                            }
                                                            Core.unblockUI();
                                                        }
                                                    });

                                                } else {
                                                    new PNotify({
                                                        title: 'Info',
                                                        text: 'Нэр өөрчилнө үү!',
                                                        type: 'info',
                                                        sticker: false
                                                    });
                                                }
                                            }
                                        }
                                    },
                                    {
                                        text: plang.get('close_btn'),
                                        class: 'btn blue-madison btn-circle btn-sm',
                                        click: function () {
                                            $dialog.dialog('close');
                                        }
                                    }]
                            });
                            Core.initUniform($dialog);
                            $dialog.dialog('open');
                        }
                    },
                    "export": exportItem,
                    "lock": {
                        name: plang.get('PL_0272'), 
                        icon: 'lock', 
                        callback: function(key, options) {
                            var dialogName = '#dialog-locktransition-' + transitionId;
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $dialog = $(dialogName), html = [], wfName = $trigger.find('.jstree-anchor').text();
                            
                            html.push('<form>');
                                html.push('<div class="form-group row mb-2">');
                                    html.push('<label class="col-md-3 col-form-label text-right pr0">Нэр:</label>');
                                    html.push('<div class="col-md-9">'+wfName+'</div>');
                                html.push('</div>');
                                html.push('<div class="form-group row mb-2">');
                                    html.push('<label class="col-md-3 col-form-label text-right pr0">Нууц үг:</label>');
                                    html.push('<div class="col-md-9"><input type="password" class="form-control readonly-white-bg" name="newPassword" id="newPassword" required="required" readonly="readonly" onfocus="this.removeAttribute(\'readonly\');"></div>');
                                html.push('</div>');
                                html.push('<div class="form-group row mb-2">');
                                    html.push('<label class="col-md-3 col-form-label text-right pr0">Нууц үг давтах:</label>');
                                    html.push('<div class="col-md-9"><input type="password" class="form-control readonly-white-bg" name="confirmPassword" id="confirmPassword" required="required" readonly="readonly" onfocus="this.removeAttribute(\'readonly\');"></div>');
                                html.push('</div>');
                            html.push('</form>');

                            $dialog.html(html.join(''));
                            $dialog.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('PL_0272'),
                                width: 500,
                                height: 'auto',
                                modal: true,
                                open: function() {
                                    $(this).keypress(function(e) {
                                        if (e.keyCode == $.ui.keyCode.ENTER && $(e.target).attr('name') == 'confirmPassword') {
                                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger('click');
                                        }
                                    });
                                },
                                close: function () {
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: plang.get('PL_0272'), class: 'btn green-meadow btn-sm', click: function () {
                                        PNotify.removeAll();
                                        
                                        var $form = $dialog.find('form');
                                        $form.validate({
                                            rules: {
                                                newPassword: {
                                                    required: true,
                                                    minlength: 8
                                                },
                                                confirmPassword: {
                                                    required: true,
                                                    minlength: 8,
                                                    equalTo: '#newPassword'
                                                }
                                            },
                                            messages: {
                                                newPassword: {
                                                    required: plang.get('user_insert_password')
                                                },
                                                confirmPassword: {
                                                    required: plang.get('user_insert_password'),
                                                    equalTo: plang.get('user_equal_password')
                                                }
                                            }
                                        });
                                        
                                        if ($form.valid()) {
                                            $form.ajaxSubmit({
                                                type: 'post',
                                                url: 'mdprocessflow/lockWfmTransition',
                                                dataType: 'json',
                                                beforeSubmit: function(formData, jqForm, options) {
                                                    formData.push({name: 'transitionId', value: transitionId});
                                                },
                                                beforeSend: function () {
                                                    Core.blockUI({message: 'Loading...', boxed: true});
                                                },
                                                success: function (data) {
                                                    new PNotify({
                                                        title: data.status,
                                                        text: data.message,
                                                        type: data.status,
                                                        sticker: false
                                                    });

                                                    if (data.status === 'success') {
                                                        $iconElem.removeClass('fa-folder text-orange-400').addClass('fa-lock');
                                                        
                                                        var $rightColumn = $('.pivotgrid-table-center-right-cell');
                                                        if (transitionId == $rightColumn.attr('data-transitionid')) {
                                                            $rightColumn.attr('data-islock', '1');
                                                            $rightColumn.find('.saveWfmStatusRightSideBar').addClass('d-none');
                                                            $('.save-main-wfm-button').hide();
                                                        }
                                                    
                                                        $dialog.dialog('close');
                                                    }
                                                    Core.unblockUI();
                                                }
                                            });
                                        }
                                    }},
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            $dialog.dialog('open');
                        }
                    }, 
                    "delete": {
                        name: plang.get('delete_btn'), 
                        icon: 'trash', 
                        callback: function(key, options) {
                            var dialogName = '#deleteConfirm-transition-' + transitionId;
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $dialog = $(dialogName);

                            $dialog.html('Та устгахдаа итгэлтэй байна уу?');
                            $dialog.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулах',
                                width: '350',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                        PNotify.removeAll();
                                        $.ajax({
                                            type: 'post',
                                            url: 'mdprocessflow/deleteWfmTransition',
                                            dataType: 'json',
                                            data: {transtionId: transitionId, metaDataId: mainMetaDataId},
                                            beforeSend: function () {
                                                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                            },
                                            success: function (data) {
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false
                                                });

                                                if (data.status === 'success') {
                                                    $dialog.dialog('close');
                                                    viewVisualHtmlMetaData(mainMetaDataId, transId, selftype);
                                                    _selectTransitionId = selectedTransitionId = data.transitionId;
                                                    selectJsPlumbViewBy(selectedTransitionId);
                                                    setTimeout(function () {
                                                        if (selectedTransitionId != '0') {
                                                            $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
                                                            $('#' + selectedTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
                                                            Core.unblockUI();
                                                        }
                                                    }, 900);
                                                }
                                                Core.unblockUI();
                                            }
                                        });
                                    }},
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            $dialog.dialog('open');
                        }
                    }
                };
            }
            
            var options =  {
                callback: function (key, opt) {
                    eval(key);
                },
                items: contextMenuData
            };

            return options;
        }
    });

    draggableEnableFunction();

    ws_selector_left.on('click', '.pivotgrid-table-collapse-cell', function () {
        var _this = $(this);
        var _thisPvGridContainer = $('div#metaProcessWindow').find('.pv-grid');
        var _thisPvGrid = _thisPvGridContainer.find('table.pv-main-element');

        if (_this.hasClass('pv-panel-closed')) {
            $('div#metaProcessWindow').find('.pivotgrid-table-left-cell').removeClass('d-none');
            _this.removeClass('pv-panel-closed');
            var _rightWidth = _thisPvGridContainer.attr('data-width');
            var _calWidth = _rightWidth - 2;
        } else {
            _thisPvGridContainer.attr('data-width', _thisPvGridContainer.find('.pivotgrid-table-right-cell-inside').innerWidth());
            $('div#metaProcessWindow').find('.pivotgrid-table-left-cell').addClass('d-none');
            _this.addClass('pv-panel-closed');
        }
    });
    ws_selector_left.on('click', '.pivotgrid-table-center-right-collapse-cell', function () {
        var _this = $(this);
        var _thisPvGridContainer = $('div#metaProcessWindow').find('.pv-grid');
        var _thisPvGrid = _thisPvGridContainer.find('table.pv-main-element');

        if (_this.hasClass('right-pv-panel-closed')) {
            $('div#metaProcessWindow').find('.pivotgrid-table-center-right-cell').removeClass('d-none');
            _this.removeClass('right-pv-panel-closed');
            var _rightWidth = _thisPvGridContainer.attr('data-width');
            var _calWidth = _rightWidth - 2;
        } else {
            _thisPvGridContainer.attr('data-width', _thisPvGridContainer.find('.pivotgrid-table-right-cell-inside').innerWidth());
            $('div#metaProcessWindow').find('.pivotgrid-table-center-right-cell').addClass('d-none');
            _this.addClass('right-pv-panel-closed');
        }
    });
    if (typeof data.transitionId != 'undefined') {
        _selectTransitionId = data.transitionId;
    }

    if (selectedTransitionId != '0') {
        selectJsPlumbViewBy(selectedTransitionId);
    } else {
        selectJsPlumbViewBy(_selectTransitionId);
    }
    setTimeout(function () {
        if (selectedTransitionId != '0') {
            $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
            $('#' + selectedTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
            Core.unblockUI();
        } else {
            $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
            $('#' + _selectTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
            Core.unblockUI();
        }
    }, 900);
}

function selectJsPlumbViewBy(transitionId) {
    var $rightColumn = $('.pivotgrid-table-center-right-cell');
    $rightColumn.attr('data-transitionid', transitionId);
    selectedTransitionId = transitionId;
    
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/getTransitionNewListData',
        dataType: 'json',
        data: {transitionId: transitionId, metaDataId: mainMetaDataId},
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            if (data.status == 'success') {
                
                if (data.hasOwnProperty('isLock') && data.isLock) {
                    $rightColumn.attr('data-islock', '1');
                    $('.save-main-wfm-button').hide();
                } else {
                    $rightColumn.attr('data-islock', '0');
                    $('.save-main-wfm-button').show();
                }
                
                _startWfmStatusId = data.startWfmStatusId;
                mainWfmStatusDroppableContent(data.workFlowStatus);
                statusAllConnections(data);
                $('#tabid_wfmStatusCfgUserProcess_' + mainMetaDataId).addClass('active');
                $('#tabid_wfmStatusCfgRoleProcess_' + mainMetaDataId).removeClass('active');

                $('#wfmStatus_' + mainMetaDataId).addClass('active');
                $('#wfmStatusRoleProcess_' + mainMetaDataId).removeClass('active');
            }
            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });
}

function mainWfmStatusDroppableContent(workFlowStatus) {
    wfmWorkFlowStatus = workFlowStatus;
    var mainHtml = '';
    if (wfmWorkFlowStatus.length != 0) {
        $.each(wfmWorkFlowStatus, function (ind, status) {
            var fontColor = 'color: #fff;';
            mainHtml += '<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 ' + status.WFM_STATUS_COLOR + ' " data-status-color = "' + status.WFM_STATUS_COLOR + '" style= "background-color:' + status.WFM_STATUS_COLOR + '; ' + fontColor + '" data-status-name="' + status.WFM_STATUS_NAME + '" data-status-code="' + status.WFM_STATUS_CODE + '" data-status-id="' + status.WFM_STATUS_ID + '" target="_self">'
                    + '<span class="title">' + status.WFM_STATUS_NAME + '</span>'
                    + '</a>';
        });
    }
    $('.status-ui-droppable').html(mainHtml);
    draggableEnableFunction();
}

function mainAppendWfmStatusDroppableContent(status) {
    var fontColor = 'color: #fff;';
    var mainHtml = '<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 ' + status.WFM_STATUS_COLOR + ' " data-status-color = "' + status.WFM_STATUS_COLOR + '" style= "background-color:' + status.WFM_STATUS_COLOR + '; ' + fontColor + '" data-status-name="' + status.WFM_STATUS_NAME + '" data-status-code="' + status.WFM_STATUS_CODE + '" data-status-id="' + status.ID + '" target="_self">'
            + '<span class="title">' + status.WFM_STATUS_NAME + '</span>'
            + '</a>';
    $('.status-ui-droppable').append(mainHtml);
    draggableEnableFunction();
}

function statusAllConnections(data) {

    wfmWorkFlowId = data.workFlowId;

    try {
        jsPlumb.detachEveryConnection();
        jsPlumb.deleteEveryEndpoint();
    } catch (err) {

    }
    
    var $workFlowEditor = $('#workFlowEditor');
    
    $workFlowEditor.empty();
    $('.wfm-status-transition-permission').empty();
    
    if (data['object'].length != 0) {
        var wfmHtml = "";

        $.each(data['object'], function (index, value) {
            wfmHtml += setIcon(value);
        });
        $workFlowEditor.append(wfmHtml);
        workflow('');
    }

    if (data['connect'].length != 0) {
        $.each(data['connect'], function (index, value) {
            workflowConnectionImport(value);
            workflow(value);
        });
        $('.wfposition').draggable({
            containment: '#workFlowEditor',
            start: function () {
                selectedObj = $(this);
                setControlVal(selectedObj);
            },
            stop: function () {
                selectedObj = $(this);
                setControlVal(selectedObj);
                saveWfmStatusPosition(selectedObj.position().top, selectedObj.position().left, selectedObj.attr('id'));
            }
        });
        
        wfmConnectionLabelToggle($workFlowEditor.closest('.heigh-editor').find('.wfmConnectionLabelToggleBtn')[0], 1);
    }
}

function saveVisualMetaStatusData(closeDailog) {
    if (selectedTransitionId == '0') {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Ажлын урсгалаа сонгоно уу?',
            type: 'warning',
            sticker: false
        });
        return false;
    }
    var strBoolen = 0;
    var objects = []
    $('#wfEditorHiddenValues').empty();

    $("#workFlowEditor, .metaWfmStatusForm").find(".wfposition").each(function () {
        var $elem = $(this);
        var endpoints = jsPlumb.getEndpoints($elem.attr('id'));
        var boolenObject = $elem.find("input[type=hidden]").attr("data-outputmetadataid");
        var boolenTrue = $elem.find("input[type=hidden]").attr("data-boolentrueid");
        var boolenFalse = $elem.find("input[type=hidden]").attr("data-boolenfalseid");
        objects.push({
            id: $elem.attr('id'),
            positionTop: $elem.find(".wfIcon").attr('data-top'),
            positionLeft: $elem.find(".wfIcon").attr('data-left')
        });
        strBoolen = 0;
    });
    var connections = [];

    $.each(jsPlumb.getConnections(), function (idx, connection) {
        var targetId = "", sourceId = "";
        if (typeof connection.targetId !== 'undefined' || connection.targetId !== "") {
            targetId = connection.targetId;
        }
        if (typeof connection.sourceId !== 'undefined' || connection.sourceId !== "") {
            sourceId = connection.sourceId;
        }

        connections.push({
            connectionId: connection.id,
            prevStatusId: sourceId,
            nextStatusId: targetId,
            strokeStyle: connection._jsPlumb.paintStyle['strokeStyle'],
            lineWidth: connection._jsPlumb.paintStyle['lineWidth'],
            description: $('#DESCRIPTION_' + sourceId + '_' + targetId).val(),
            criteria: $('#CRITERIA_' + sourceId + '_' + targetId).val(),
            transitionTime: $('#TRANSITION_TIME_' + sourceId + '_' + targetId).val(),
            timeTypeId: $('#TIME_TYPE_ID_' + sourceId + '_' + targetId).val(),
            transitionCost: $('#TRANSITION_COST_' + sourceId + '_' + targetId).val(),
            transitionDistance: $('#TRANSITION_DISTANCE_' + sourceId + '_' + targetId).val(),
            top: $('#' + connection.target.id).find(".wfIcon").attr('data-top'),
            left: $('#' + connection.target.id).find(".wfIcon").attr('data-left')
        });
    });
    if (objects.length > 1) {
        var d = $.ajax({
            type: 'post',
            url: 'mdprocessflow/saveVisualMetaStatusData',
            data: {connections: connections, metaDataId: mainMetaDataId, transitionId: selectedTransitionId, objects: objects, workFlowHtml: objects},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({target: 'body', animate: true});
            },
            success: function (data) {
                PNotify.removeAll();
                if (data.status === 'success') {
                    new PNotify({
                        title: data.status,
                        text: 'Амжилттай хадгаллаа',
                        type: data.status,
                        sticker: false
                    });

                    selectedTransitionId = data.transitionId;
                    /* viewVisualHtmlMetaData(mainMetaDataId); */
                    selectJsPlumbViewBy(data.transitionId);
                    /* closeDailog.dialog('close'); */
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.text,
                        type: data.status,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    } else {
        PNotify.removeAll();
        new PNotify({
            title: 'Анхааруулга',
            text: 'Хадгалах боломжгүй процесс байна',
            type: 'error',
            sticker: false
        });
    }
}

function draggableEnableFunction() {
    $(".ui-status-draggable").draggable({
        cursor: "crosshair",
        revert: "invalid",
        start: function (event, ui) {
            tempStatusId = $(this).attr('data-status-id');
            tempStatusCode = $(this).attr('data-status-code');
            tempStatusName = $(this).attr('data-status-name');
            tempStatusColor = $(this).attr('data-status-color');
        }

    });
    $(".css-editor").droppable({
        accept: ".ui-status-draggable",
        drop: function (event, ui) {
            $(this).removeClass("border").removeClass("over");
            var dropped = ui.draggabe;
            var droppedOn = $(this);
            var droppableTop = parseFloat((ui.offset).top) - 92;
            var droppableLeft = parseFloat(parseFloat((ui.offset).left) - 287.015625); /*(ui.offset).left;*/ /*parseFloat(parseFloat((ui.offset).left)-287.015625);*/

            $(dropped).detach().css({top: 0, left: 0}).appendTo(droppedOn);
            $(ui.draggable).remove();
            var $wfIconGroupHeight = $(event.originalEvent.toElement).closest('.wfpositionGroup').find('> a > div.wfIcon');
            $wfIconGroupHeight.css('height', $wfIconGroupHeight.height() + 30 + 'px');

            var _wfmTransitionArr = {
                ID: tempStatusId,
                LEFT: droppableLeft,
                TOP: droppableTop,
                TYPE: "rectangle",
                WFM_STATUS_CODE: tempStatusCode,
                WFM_STATUS_NAME: tempStatusName,
                WFM_STATUS_COLOR: tempStatusColor,
            };

            if ($(event.originalEvent.toElement).closest('.wfpositionGroup').length) {
                $(event.originalEvent.toElement).closest('.wfpositionGroup').find('> a').append(setIcon(_wfmTransitionArr)).find('.wfposition:last').css({'left': 0, 'z-index': 10, 'top': ($wfIconGroupHeight.height() - 30) + 'px'});
            } else {
                $('#workFlowEditor').append(setIcon(_wfmTransitionArr));
            }

            workflow(_wfmTransitionArr);
            $('.wfposition').draggable({
                containment: '#workFlowEditor',
                stop: function () {
                    selectedObj = $(this);
                    setControlVal(selectedObj);
                }
            });
        },
        over: function (event, elem) {
            $(this).addClass("over");
        },
        out: function (event, elem) {
            $(this).removeClass("over");
        }
    });

    $(".css-editor").sortable();
    $(".status-ui-droppable").droppable({
        accept: ".ui-status-draggable",
        drop: function (event, ui) {
            $(this).removeClass("border").removeClass("over");
            var dropped = ui.draggable;
            var droppedOn = $(this);
            $(dropped).detach().css({top: 0, left: 0}).appendTo(droppedOn);
        }
    });
}

function popupConnectAddWorkFlow1(metaDataId) {
    var $dialogName = 'dialog-addworkflow-' + metaDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/addWorkFlowFirstStatusForm',
        dataType: 'json',
        data: {metaDataId: metaDataId},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
            if (!$().colorpicker) {
                $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                });
            }
        },
        success: function (dataHtml) {
            var $dialogName = 'dialog-addworkflowStatus-' + metaDataId;
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            $("#" + $dialogName).empty().html(dataHtml.Html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: dataHtml.Title,
                width: 450,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: dataHtml.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                            $("#createWfmStatus-from", "#" + $dialogName).validate({errorPlacement: function () {}});
                            if ($("#createWfmTransition-from", "#" + $dialogName).valid()) {
                                var isNewStatusChecked = $('#isNewStatus').is(':checked');
                                if (!isNewStatusChecked) {
                                    var oldWfmStatusId = $('#wfmStatusId, #createWfmStatus-from').val();
                                    if (oldWfmStatusId.length === 0) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Warning',
                                            text: 'Хуучин төлөвөөс сонгоно уу?',
                                            type: 'warning',
                                            sticker: false
                                        });
                                        return false;
                                    }
                                }
                                bpCriteriaEditorParam.save();
                                $('#createWfmTransition-from').ajaxSubmit({
                                    type: 'post',
                                    url: 'mdprocessflow/createWfmWorkFlow',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({
                                            message: plang.get('msg_saving_block'),
                                            boxed: true
                                        });
                                    },
                                    success: function (data) {
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false
                                            });
                                            viewVisualHtmlMetaData(mainMetaDataId);
                                            $("#" + $dialogName).empty().dialog('destroy').remove();
                                        } else {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }},
                    {text: dataHtml.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $("#" + $dialogName).empty().dialog('destroy').remove();
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
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}

function customMenu(node) {
    var items = {
        'item1': {
            'label': 'item1',
            'action': function (e, data) {
                console.log('item1 = ', e);
                /* action */ }
        },
        'item2': {
            'label': 'item2',
            'action': function (e, data) { /* action */
                $node = tree.create_node($node);
                tree.edit($node);

                console.log(tree.get_selected($node).id);
            }
        }
    }
    if (node.type === 'level_1') {
        delete items.item2;
    } else if (node.type === 'level_2') {
        delete items.item1;
    }
    return items;
}

function globalStatusSelectabledGrid(metaDataCode, chooseType, elem, rows) {
    var params = [];
    $.each(rows, function (index, value) {
        var _temp = {
            id: value.wfmstatusid,
            wfmstatuscode: value.wfmstatuscode,
            wfmstatuscolor: value.wfmstatuscolor,
            wfmstatusname: value.wfmstatusname,
        }
        params.push(_temp);
    });

    $.ajax({
        type: 'post',
        url: 'mdprocessflow/createWfmWorkFlowFromGlobal',
        data: {params: params, metaDataId: mainMetaDataId},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: plang.get('msg_saving_block'),
                boxed: true
            });
        },
        success: function (data) {
            if (data.status === 'success') {
                new PNotify({
                    title: 'Success',
                    text: data.message,
                    type: 'success',
                    sticker: false
                });
                /* mainWfmStatusDroppableContent(data.workFlowStatus); */
                if (selectedTransitionId != '0') {
                    $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
                    $('#' + selectedTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
                    selectJsPlumbViewBy(selectedTransitionId);
                    Core.unblockUI();
                } else {
                    $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
                    $('#' + _selectTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
                    selectJsPlumbViewBy(_selectTransitionId);
                    Core.unblockUI();
                }
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
            Core.unblockUI();
        }
    });
}

function checkSelectedTransitionId() {
    /*if (selectedTransitionId === '0') {
     PNotify.removeAll();
     new PNotify({
     title: 'Warning',
     text: 'Ажлын урсгалаа сонгоно уу?',
     type: 'warning',
     sticker: false
     });
     return;
     }*/
    dataViewCustomSelectableGrid('META_WFM_GLOBAL_STATUS_DV', 'multi', 'globalStatusSelectabledGrid', '', this);
}

function appendWorkflowGroup(mainBpId, elem) {
    var wfIconArray = {
        id: 123,
        doBpId: 321,
        title: '',
        type: 'rectangle',
        class: 'wfIconRectangle',
        positionTop: '120',
        positionLeft: '20',
        width: '160',
        height: '70',
        metaDataCode: ''
    };

    $('#workFlowEditor').append(setIconGroup(wfIconArray));
    workflow(wfIconArray);

    $('.wfposition').draggable({
        containment: '#workFlowEditor',
        stop: function () {
            selectedObj = $(this);
            setControlVal(selectedObj);
        }
    });

}    