var selectedObj, strokeCommon, drawRowCount = 0,
    wfmWorkFlowStatus = [], foo = true,
    tempStatusId = 0, tempStatusCode = 0,
    tempStatusName = 0, tempStatusColor = 0,
    wfIconAddPositionTop = 20,
    _startWfmStatusId = 0,
    wfIconAddPostionLeft = 20,
    wfObjectBoolean = "14359007153593", 
    wfmWorkFlowId  = "0", mainMetaDataId = "0", selectedTransitionId = "0", _selectTransitionId = "0",
    transId = "0";
	
$(function () {
    
    var x = 100000000;
    var me = this;
    var windows;
    var arrowStyle = 'StateMachine'; //Straight, Flowchart, Bezier, StateMachine

    $('#editor').on('click', 'div.wfposition', function () {
        selectedObj = $(this);
        setControlVal(selectedObj);
    });

    $.contextMenu({
        selector: '.wfStatusMenu',
        callback: function (key, opt) {
            if (key === 'edit') {
                var $elem = $(this);
                var selectedMetaWfmStatusId = $elem.attr('id');
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/editWorkFlowStatusForm',
                    dataType: 'json',
                    data: {metaWfmStatusId : selectedMetaWfmStatusId, metaDataId : mainMetaDataId},
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
                        var $dialogName = 'dialog-editworkflowStatus-'+selectedMetaWfmStatusId;
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
                                                    if (selectedTransitionId !== '0')
                                                        selectJsPlumbViewBy(selectedTransitionId);
                                                    $("#" + $dialogName).empty().dialog('destroy').remove();
                                                } 
                                                else {
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
                    buttons: [
                        {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {                            
                            $(dialogName).empty().dialog('destroy').remove();
                            jsPlumb.remove(_selectedStatusId);
                            var ticket =  false;
                            $.each($('.ui-status-no-draggable'), function (index, value) {
                                if ($(value).attr('data-status-id') === $elem.attr('data-status-id')) {
                                    ticket = true;
                                }
                            });
                            if (!ticket) {
                                var fontColor = ($elem.attr('data-status-color').indexOf("#") == 0) ? 'color: #fff;' : 'color: #000;';
                                $(".status-ui-droppable").append('<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 '+ $elem.attr('data-status-color') +'  ui-draggable ui-draggable-handle" style="'+ fontColor +'; position: relative; background-color: ' + $elem.attr('data-status-color') + ';" data-status-color="' + $elem.attr('data-status-color') + '" data-status-name="'+ $elem.attr('data-status-name') +'" data-status-code="'+ $elem.attr('data-status-code') +'" data-status-id="'+ $elem.attr('data-status-id') +'" target="_self"><span class="title">'+ $elem.attr('data-status-name') +'</span></a>'); 
                                draggableEnableFunction();
                            }
                        }},
                        {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).empty().dialog('destroy').remove();
                        }}
                    ]
                });
                $(dialogName).dialog('open');

            }
            if (key === 'arrowDelete') {
                var doBpId = $(this).attr('id');
                $('#workFlowEditor').find('input[name="' + doBpId + '"]').attr('data-boolentrueid', -1);
                $('#workFlowEditor').find('input[name="' + doBpId + '"]').attr('data-boolenfalseid', -1);
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/deleteStatusArrow',
                    data: {transitionId: doBpId},
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            target: 'body',
                            animate: true
                        });
                    },
                    success: function (data) {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        if (data.status === 'success') {
                            runWorkFlowByWfmStatus(wfmWorkFlowId, mainMetaDataId);
                        } 
                        
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                }).done(function () {
                    Core.initAjax();
                });
            }
        },
        items: {
            "edit": {name: "Засах", icon: "edit"},
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
    
    $.contextMenu({
        selector: '.wfMenuStart-status',
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
        callback: function (key, opt) {
            if (key === 'edit') {
                var selectedMetaWfmStatusId = $(this).attr('data-status-id');
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/editWorkFlowStatusForm',
                    dataType: 'json',
                    data: {metaWfmStatusId : selectedMetaWfmStatusId, metaDataId: mainMetaDataId},
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
                        var $dialogName = 'dialog-editworkflowStatus-'+selectedMetaWfmStatusId;
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
                                                } 
                                                else {
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
                                    } 
                                    else {
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
            "edit": {name: "Засах", icon: "edit"},
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
    
    workflowConnectionImport = function (elem) {
        var common = {
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}], /*[Straight, Flowchart, Bezier, StateMachine]*/
            paintStyle: {radius: 5},
            hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 5},
            dragOptions: {
                cursor: 'pointer', 
                drop:function(e, ui) { 
                    console.log('drop!'); 
                }
            }
        };
        if (elem['PREV_WFM_STATUS_ID'] != null && elem['NEXT_WFM_STATUS_ID'] != null) {
            var html = '<input type="hidden" id = "DESCRIPTION_'+ elem['PREV_WFM_STATUS_ID'] +'_'+ elem['NEXT_WFM_STATUS_ID'] +'" value="'+ elem.DESCRIPTION + '"/>'
                    + '<input type="hidden" id = "CRITERIA_'+ elem['PREV_WFM_STATUS_ID'] +'_'+ elem['NEXT_WFM_STATUS_ID'] +'" value="'+ elem.CRITERIA + '"/>';
            
            $("#workFlowEditor").append(html);
            var instance = jsPlumb.connect({
                source: elem['PREV_WFM_STATUS_ID'],
                target: elem['NEXT_WFM_STATUS_ID'],
                overlays:[ 
                    "Arrow", 
                    [ "Label", { label:elem['DESCRIPTION'], class:"connectionLabel", location:0.25, id:"myLabel_"+elem['PREV_WFM_STATUS_ID']+ "_" + elem['NEXT_WFM_STATUS_ID'] } ]
                ],
            }, common);
        } 
    }
    
    workflow = function (elem) {
        jsPlumb.importDefaults({
            ConnectionsDetachable: true,
            ReattachConnections: true,
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}],
            Endpoint: ["Dot", {radius: 6}],
            ConnectorZIndex:5
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
        if (typeof elem['HEIGHT'] !== 'undefined' && elem['HEIGHT'] != '') {
            var wfIconArray = {
                id: elem['ID'],
                doBpId: '',
                title: '',
                type: 'rectangle',
                class: 'wfIconRectangle',
                positionTop: elem['TOP'],
                positionLeft: elem['LEFT'],
                width: '160',
                groupheight: elem['HEIGHT'],
                metaDataCode: elem['WFM_STATUS_NAME']
            };            
            return setIconGroup(wfIconArray);
        }

        var _left = elem['LEFT'];
        var _top = elem['positionTop'];
        var linkTitle = elem['WFM_STATUS_NAME'] + ' (' + elem['WFM_STATUS_CODE'] + ')';
        
        if (elem['ID'] == 'endObject001') {
            linkTitle = 'Төгсгөл';
        }
        /* 2018-01-29 */
        var html = '<div id="' + elem['ID'] + '" ' +
                'class="wfposition wfStatusMenu circle' + (elem['TYPE'] == 'circle' ? 'circle' : '') + ' ' + (elem['ID'] == 'startObject001' ? ' wfMenuStart-status ' : '') + (elem['childProcess'] == '1' ? ' drill-down ' : '') + '" ' + (elem['childProcess'] == '1' ? 'ondblclick="processDrillDown(this)"' : '') +
                'onclick = "clickSeeSidebarFnc(this)"'+
                'data-status-color ="'+ elem['WFM_STATUS_COLOR'] +'" data-status-name="'+ elem['WFM_STATUS_NAME'] +'" data-status-code="'+ elem['WFM_STATUS_CODE'] +'" data-status-id="'+ elem['ID'] +'" '+
                (typeof elem['PACKID'] !== 'undefined' && elem['PACKID'] != '' ? 'data-pack-id="' + elem['PACKID'] + '" ' : '') +                
                'style="' +
                /*'width: ' + (elem['TYPE'] == 'circle' ? '30px' : '100px; ') + 
                'height: ' + (elem['TYPE'] == 'circle' ? '30px' : '100px; ') + */
                'display: inline-block;' +
                'top: '+ elem['TOP'] +'px; ' +
                'z-index: 10; ' +
                'left: '+ elem['LEFT'] +'px; ' +
                '"' +
                '> ' +
                '<a href="javascript:;" title="' + linkTitle + '" >' +
                '<div ' +
                'class="wfIcon ' + (elem['TYPE'] == 'circle' ? 'wfIconCircle' : '') + '" ' +
                'data-width="100" ' +
                'data-height="100" ' +
                'data-top="'+ elem['TOP'] +'" ' +
                'data-left="' + elem['LEFT'] + '" ' +
                'data-type="' + (elem['TYPE'] == 'circle' ? 'wfIconCircle' : '') + '" ' +
                'data-class="' + (elem['TYPE'] == 'circle' ? 'wfIconCircle' : '') + '" ' +
                'data-metatypecode="' + elem['WFM_STATUS_CODE'] + '" ' +
                'data-outputmetadataid="' + elem['outputMetaDataId'] + '" ' +
                'data-dobpid="' + elem['ID'] + '" ' +
                'style="width: ' + (elem['TYPE'] == 'circle' ? '30px' : 'auto') + '; height:' + (elem['TYPE'] == 'circle' ? '30px' : 'auto') + '; background: '+ elem['WFM_STATUS_COLOR'] +'; padding: 6px 14px;border-radius: 25px !important;"' +
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
                '<div class="wfIcon ' + elem['class'] + ' " data-type="' + elem['type'] + '" data-width="' + elem['width'] + '" data-height="' + elem['height'] + '" data-group-height="' + elem['groupheight'] + '" ' +
                'data-top="' + elem['positionTop'] + '" data-left="' + elem['positionLeft'] + '" ' +
                'data-class="' + elem['class'] + '" data-title="' + elem['title'] + '" ' +
                'data-workflowid="' + elem['id'] + '" ' +
                'data-dobpid="' + elem['doBpId'] + '" style="height:' + elem['groupheight'] + 'px">' +
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
    
    $("body").on("mouseover", metaProcessWindowId + ' .stoggler',  function () {
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

function viewVisualHtmlMetaData(mainBpId, transId) {
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
				workFlowDrawHtml(mainBpId, data, transId);
				if (data.status === 'success') {
					wfmWorkFlowId = data.workFlowId;
					$('.workflowLiClass').removeClass('active');
					$('.wfmWorkFlowId_'+ data.workFlowId).addClass('active');
					
					$('#workFlowEditor').html('');
					var _linkWorkFlowId = (typeof linkWorkFlowId != 'undefined' && linkWorkFlowId != '0') ? linkWorkFlowId : data.workFlowId;
				}
			}
		});
	}
}

function addMetaWorkFlowFuntion() {
    var metaDataId = $("#metaDataId_valueField").val();
    if(metaDataId === '') {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Choose Meta.',
            type: 'warning',
            sticker: false
        });        
        return;
    }
    var $dialogName = 'dialog-addworkflow-'+metaDataId;
    
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
                        url: 'mdprocessflow/createWfmWorkFlowPack',
                        dataType: "json",
                        data : {metaDataId : metaDataId, workFlowName: workFlowName, workFlowCode: workFlowCode},
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
    
    var $dialogName = 'dialog-addworkflow-'+metaDataId;
    
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/getMetaWfmWorkFlowData',
        dataType: "json",
        data : {metaDataId : metaDataId, wfmWorkFlowId: wfmWorkFlowId},
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
                            + '<input type="hidden" id="workFlowId" value="'+ data.data['ID'] +'">'
                            + '<tbody>'
                                + '<tr>'
                                    + '<td class="text-right middle" style="width: 45%">'
                                        + '<label for="workFlowCode" data-label-path="title">Ажлын урсгалын код:</label>'
                                    + '</td>'
                                    + '<td class="middle" style="width: 55%" colspan="">'
                                        + '<div data-section-path="workFlowCode">' 
                                            + '<input type="text" id="workFlowCode" placeholder="Ажлын урсгалын код" class="form-control form-control-sm" value="'+ data.data['WFM_WORKFLOW_CODE'] +'">'
                                        + '</div>'
                                    + '</td>'
                                + '</tr>'
                                + '<tr>'
                                    + '<td class="text-right middle" style="width: 45%">'
                                        + '<label for="workFlowName" data-label-path="title">Ажлын урсгалын нэр:</label>'
                                    + '</td>'
                                    + '<td class="middle" style="width: 55%" colspan="">'
                                        + '<div data-section-path="workFlowName">' 
                                            + '<input type="text" id="workFlowName" placeholder="Ажлын урсгалын нэр" class="form-control form-control-sm" value="'+ data.data['WFM_WORKFLOW_NAME'] +'">'
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
                                data : {metaDataId : metaDataId, workFlowName: workFlowName, workFlowCode: workFlowCode, workFlowId: workFlowId},
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
        data: {metaDataId : metaDataId, transitionId: ''},
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
            var $dialogName = 'dialog-addworkflowStatus-'+metaDataId;
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
                                    } 
                                    else {
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

function runWorkFlowByWfmStatus(workFlowId, metaDataId ) {
    $('.workflowLiClass').removeClass('active');
    $('.wfmWorkFlowId_'+ workFlowId).addClass('active');
    $('.editMetaWorkFlowFuntion_'+metaDataId).removeClass('hidden');
    $('.createWfmStatus_'+metaDataId).removeClass('hidden');
    
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

function clickSeeSidebarFnc (element) {
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
        data: {wfmStatusId: $wfmStatusId, metaDataId: mainMetaDataId},
        beforeSend: function () {
            Core.blockUI({
                target: $rightColumn,
                animate: true
            });
        },
        success: function (dataHtml) {
            
            var _data = dataHtml.data;
            var _wfmStatusHtml = dataHtml.wfmStatusHtml + '<div class="row"><div class="col-md-12"><button type="button" class="btn btn-success btn-circle btn-sm float-right saveWfmStatusRightSideBar">Хадгалах</button></div></div>';
            
            $('.wfm-status-transition-permission').empty().append(dataHtml.Html);
            
            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
            $.cachedScript("assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1").done(function () {

                $.cachedScript("assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js").done(function() {

                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                    $('.wfm-status-editable-update').empty().append(_wfmStatusHtml);
                    Core.initAjax($('.wfm-status-editable-update'));
                    $('.saveWfmStatusRightSideBar').off('click');
                    $('.saveWfmStatusRightSideBar').on('click', function () {
                        $("#updateWfmStatus-from", ".wfm-status-editable-update").validate({errorPlacement: function () {}});
                        if ($("#updateWfmStatus-from", ".wfm-status-editable-update").valid()) {
                            $('#updateWfmStatus-from', ".wfm-status-editable-update").ajaxSubmit({
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
                                    PNotify.removeAll();
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        wfmWorkFlowId = data.wfmWorkFlowId;
                                        if (selectedTransitionId !== '0') {
                                            selectJsPlumbViewBy(selectedTransitionId);
                                            wfmStatusSideBarReload($wfmStatusId);
                                        }
                                    } else {
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

function workFlowDrawHtml(mainBpId, data, transId) {
    $('#metaProcessDetial').html('');
    var _mainWindowHeight = $(window).height() - 140;
    var mainHtml = ''
        +'<div class="col-md-6 pl0">'
            + '<div class="mb10 btn-group btn-group-devided">' 
                + '<button type="button" class="btn btn-secondary btn-circle btn-sm" onclick="popupConnectAddWorkFlow1(\''+ mainBpId +'\')"><i class="icon-plus3 font-size-12"></i> Ажлын урсгал нэмэх</button>' 
                + '<button type="button" class="btn btn-secondary btn-circle btn-sm" onclick="appendWorkflowGroup(\''+ mainBpId +'\', this)"><i class="icon-plus3 font-size-12"></i> Багц нэмэх</button>' 
            + '</div>' 
        + '</div>' 
        + '<div class="col-md-6 pr0" style="padding-right: 310px !important;">'
            + '<div class="mb10 btn-group btn-group-devided float-right text-right">' 
                + '<button type="button" class="btn btn-success btn-circle btn-sm ml10" onclick="checkSelectedTransitionId(this);"><i class="icon-plus3 font-size-12"></i> Төлөв нэмэх</button>' 
            + '</div>' 
        + '</div>' 
        + '<div class="col-md-12 pivotgrid-table">'
            + '<div class=" pivotgrid-table-left-cell" style="width: 250px !important; min-width: 250px !important; max-height: '+ _mainWindowHeight +'px; !important; height: '+ _mainWindowHeight +'px; border: 1px solid #999;">'
                + '<div class="slimScrollDiv list-jtree-' + mainBpId + '" style="position: relative;  width: auto; height: '+ _mainWindowHeight +'px;">'
                + '</div>'
            + '</div>'
            + '<div class="pivotgrid-table-collapse-cell"></div>'
            + '<div class="pivotgrid-table-right-cell pv-grid pivotgrid-table-right-cell-inside">'
                    + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                        + '<div class="heigh-editor" style=" max-height: '+ _mainWindowHeight +'px; height: '+ _mainWindowHeight +'px; border-radius: 0;">' 
                            + '<input type="hidden" value="0" name="clickBoolenTrue"><input type="hidden" value="0" name="clickBoolenFalse">' 
                            + '<div class="css-editor" id="workFlowEditor" style="width:100%;"></div>' 
                        + '</div>'
                    + '</div>'
                + '</div>'
                + '<div class="pivotgrid-table-center-right-collapse-cell"></div>'
                    + '<div class="pivotgrid-table-center-right-cell" data-status="open" style="border: 1px solid #999; margin-top:0px; max-height: '+ _mainWindowHeight +'px; height: '+ _mainWindowHeight +'px; display: table-cell; vertical-align: top; margin: 0; padding: 0 0 0 10px; width: 400px; min-width: 400px; max-width: 400px;">'
                        + '<div style="max-height: '+ _mainWindowHeight +'px; height: '+ _mainWindowHeight +'px; overflow-x: hidden; overflow-y: auto;">'
                        + '<div class="tabbable-line">'
                            + '<ul class="nav nav-tabs">'
                                + '<li class="nav-item">'
                                    + '<a href="#wfmStatus_'+ mainBpId +'" id="tabid_wfmStatusCfgUserProcess_'+ mainBpId +'" data-toggle="tab" class="nav-link active">Төлөв</a>'
                                + '</li>'
                                + '<li class="nav-item">'
                                    + '<a href="#wfmStatusRoleProcess_'+ mainBpId +'" id="tabid_wfmStatusCfgRoleProcess_'+ mainBpId +'" data-toggle="tab" class="nav-link">Тохиргоо</a>'
                                + '</li>'
                            + '</ul>'
                            + '<div class="tab-content">'
                                + '<div class="tab-pane active" id="wfmStatus_'+ mainBpId +'">'
                                    + '<div class="mb10 btn-group btn-group-devided">' 
                                        /*+ '<button type="button" class="btn btn-success btn-circle btn-sm ml10" onclick="addMetaWorkFlowStatusFuntion(\''+ mainBpId +'\')"><i class="icon-plus3 font-size-12"></i> Төлөв нэмэх</button>' */
                                    + '</div>';
                                    mainHtml += '<div class="col-md-12 status-ui-droppable " style="margin-left: -14px;">'
                                    if (wfmWorkFlowStatus.length != 0) {
                                        $.each(wfmWorkFlowStatus, function (ind, status) {
                                            var fontColor = 'color: #fff;';
                                            mainHtml += '<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 '+ status.WFM_STATUS_COLOR +' " data-status-color = "' + status.WFM_STATUS_COLOR + '" style= "background-color:' + status.WFM_STATUS_COLOR + '; '+ fontColor +'" data-status-name="'+ status.WFM_STATUS_NAME +'" data-status-code="'+ status.WFM_STATUS_CODE +'" data-status-id="'+ status.WFM_STATUS_ID +'" target="_self">'
                                                        + '<span class="title">'+ status.WFM_STATUS_NAME +'</span>'
                                                    + '</a>';
                                        });
                                    }
                                        
                                     mainHtml += '</div>'
                                + '</div>'
                                + '<div class="tab-pane" id="wfmStatusRoleProcess_'+ mainBpId +'">'
                                    + '<div class="wfm-status-editable-update xs-form pl0" style="min-height: 700px; padding-right: 10px !important;"></div>'
                                    + '<div class="w-100"></div>'
                                    + '<div class="wfm-status-transition-permission col-md-12 pl0 pr0" style="min-height: 300px; max-height: 300px;"></div>'
                                + '</div>'
                            + '</div>'
                        +'</div>'    
                    +'</div>'
                + '</div>'
            + '</div>'
        + '</div>';
    $('#metaProcessDetial').html(mainHtml);
    $('.list-jtree-' + mainBpId).on("changed.jstree", function (e, data) {
        $('.list-jtree-'+ mainBpId).jstree(data.action, data.node.id, true, true);
    }).jstree({
        "core": {
            "themes": {
                "responsive": true
            },
            "check_callback": true,
            'data': {
                url: URL_APP + 'mdprocessflow/getTransitionListJtreeDataPack',
                data: {metaDataId : mainMetaDataId, transId: transId},
                dataType: "json",
            },
        },
        "types": {
            "default": {
                "icon": "icon-folder2 text-orange-300"
            }
        },
        "contextmenu": { 
            "items": function($node) {
                var tree = $("#tree_1").jstree(true);
                return {
                    "Edit": {
                        "name": "Засах", 
                        "icon": "fa fa-edit",
                        "separator_before": false,
                        "separator_after": true,
                        "label": "Засах",
                        "action": function (obj) { 
                            var transitionId = $($node).attr('id');
                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/editWorkFlowFirstStatusForm',
                                dataType: 'json',
                                data: {transitionId : transitionId, metaDataId: mainMetaDataId},
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
                                    var $dialogName = 'dialog-editworkflowStatus-'+transitionId;
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
                                                $("#updateWfmTransition-from", "#" + $dialogName).validate({errorPlacement: function () {}});
                                                if ($("#updateWfmTransition-from", "#" + $dialogName).valid()) {
                                                    bpCriteriaEditorParam.save();
                                                    $('#updateWfmTransition-from').ajaxSubmit({
                                                        type: 'post',
                                                        url: 'mdprocessflow/updateWfmWorkFlowTransition',
                                                        dataType: 'json',
                                                        beforeSend: function () {
                                                            Core.blockUI({
                                                                message: plang.get('msg_saving_block'),
                                                                boxed: true
                                                            });
                                                        },
                                                        success: function (data) {
                                                            new PNotify({
                                                                title: data.status,
                                                                text: data.message,
                                                                type: data.status,
                                                                sticker: false
                                                            });
                                                            if (data.status === 'success') {
                                                                viewVisualHtmlMetaData(mainMetaDataId);
                                                                $("#" + $dialogName).empty().dialog('destroy').remove();
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
                    },
                    "Delete": {
                        "name": "Устгах", 
                        "icon": "fa fa-trash",
                        "separator_before": false,
                        "separator_after": true,
                        "label": "Устгах",
                        "action": function (obj) { 
                            var transitionId = $($node).attr('id');
                            var dialogName = '#deleteConfirm-transition-'+transitionId;
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
                                        $(dialogName).empty().dialog('destroy').remove();
                                        $.ajax({
                                            type: 'post',
                                            url: 'mdprocessflow/deleteWfmTransition',
                                            dataType: 'json',
                                            data: {transtionId: transitionId, metaDataId: mainMetaDataId},
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
                                                    _selectTransitionId = selectedTransitionId = data.transitionId;
                                                    selectJsPlumbViewBy(selectedTransitionId);
                                                    setTimeout(function () {
                                                        if (selectedTransitionId != '0') {
                                                            $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
                                                            $('#'+selectedTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
                                                            Core.unblockUI();
                                                        }
                                                    }, 900);
                                                } 
                                                else {
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
                    "Export": {
                        "name": "Export", 
                        "icon": "fa fa-download",
                        "separator_before": false,
                        "separator_after": true,
                        "label": "Export",
                        "action": function (obj) { 
                            var transitionId = $($node).attr('id');
                            Core.blockUI({
                                boxed: true, 
                                message: 'Exporting...'
                            });    
                            $.fileDownload(URL_APP + 'mdprocessflow/exportWorkflowSingle', {
                                httpMethod: 'POST',
                                data: {transitionId:transitionId}
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
                        }
                    },
                };
            }
        },
        "plugins": ["types", "cookies", "dnd", "contextmenu"]
    }).bind("select_node.jstree", function (e, data) {
        var nid = data.node.id === 'null' ? '' : data.node.id;
        selectJsPlumbViewBy(nid);
    });
    
    draggableEnableFunction();
    
    ws_selector_left.on('click', '.pivotgrid-table-collapse-cell', function() {
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
    ws_selector_left.on('click', '.pivotgrid-table-center-right-collapse-cell', function() {
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
            $('#'+selectedTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
            Core.unblockUI();
        } else {
            $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
            $('#'+_selectTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
            Core.unblockUI();
        }
    }, 900);
}

function selectJsPlumbViewBy(transitionId) {
    selectedTransitionId = transitionId;
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/getTransitionNewListDataPack',
        dataType: 'json',
        data: {transitionId: transitionId, metaDataId: mainMetaDataId},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            if (data.status === 'success') {
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
        error: function () {
            alert("Error");
        }
    });
}

function mainWfmStatusDroppableContent(workFlowStatus) {
    wfmWorkFlowStatus = workFlowStatus;
    var mainHtml = '';
    if (wfmWorkFlowStatus.length != 0) {  
        $.each(wfmWorkFlowStatus, function (ind, status) {
            var fontColor = 'color: #fff;';
            mainHtml+= '<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 '+ status.WFM_STATUS_COLOR +' " data-status-color = "' + status.WFM_STATUS_COLOR + '" style= "background-color:' + status.WFM_STATUS_COLOR + '; '+ fontColor +'" data-status-name="'+ status.WFM_STATUS_NAME +'" data-status-code="'+ status.WFM_STATUS_CODE +'" data-status-id="'+ status.WFM_STATUS_ID +'" target="_self">'
                        + '<span class="title">'+ status.WFM_STATUS_NAME +'</span>'
                    + '</a>';
        });
    }
    $('.status-ui-droppable').html(mainHtml);
    draggableEnableFunction();
}

function mainAppendWfmStatusDroppableContent(status) {
    var fontColor = 'color: #fff;';
    var mainHtml  = '<a class="pv-field ui-status-no-draggable ui-status-draggable mb5 ml5 '+ status.WFM_STATUS_COLOR +' " data-status-color = "' + status.WFM_STATUS_COLOR + '" style= "background-color:' + status.WFM_STATUS_COLOR + '; '+ fontColor +'" data-status-name="'+ status.WFM_STATUS_NAME +'" data-status-code="'+ status.WFM_STATUS_CODE +'" data-status-id="'+ status.ID +'" target="_self">'
                        + '<span class="title">'+ status.WFM_STATUS_NAME +'</span>'
                    + '</a>';
    $('.status-ui-droppable').append(mainHtml);
    draggableEnableFunction();
}

function statusAllConnections(data) {
    
    wfmWorkFlowId = data.workFlowId;
    
    try {
        jsPlumb.detachEveryConnection();
        jsPlumb.deleteEveryEndpoint();
    }
    catch(err) {
        
    }
    
    $('#workFlowEditor').empty();
    $('.wfm-status-transition-permission').empty();
    if (data['object'].length != 0) {
        var wfmHtml = "";

        $.each(data['object'], function (index, value) {
            wfmHtml = setIcon(value);                
            if (typeof value.PACKID !== 'undefined' && value.PACKID != '') {
                $('#workFlowEditor').find('#'+value.PACKID+' > a').append(wfmHtml);
            } else {
                $('#workFlowEditor').append(wfmHtml);
            }
        });        
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
            packid: $elem.attr('data-pack-id'),
            positionTop: $elem.find(".wfIcon").attr('data-top'),
            positionLeft: $elem.find(".wfIcon").attr('data-left'),
            positionHeight: $elem.find(".wfIcon").attr('data-group-height')
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
            description: $('#DESCRIPTION_'+ sourceId +'_'+ targetId).val(),
            criteria: $('#CRITERIA_'+ sourceId +'_'+ targetId).val(),
            top: $('#'+ connection.target.id).find(".wfIcon").attr('data-top'),
            left:  $('#'+ connection.target.id).find(".wfIcon").attr('data-left'),
        });
    });
    if (objects.length > 1) {
        var d = $.ajax({
            type: 'post',
            url: 'mdprocessflow/saveVisualMetaStatusDataPack',
            data: {connections: connections, metaDataId: mainMetaDataId, transitionId: selectedTransitionId, objects: objects, workFlowHtml: objects},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    target: 'body',
                    animate: true
                });
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
        }).done(function () {
            Core.initAjax();
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
        start: function(event, ui) {
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
            var droppableTop = parseFloat((ui.offset).top)-92;
            var droppableLeft = parseFloat(parseFloat((ui.offset).left)-287.015625); /*(ui.offset).left;*/ /*parseFloat(parseFloat((ui.offset).left)-287.015625);*/
            
            $(dropped).detach().css({top: 0, left: 0}).appendTo(droppedOn);
            $(ui.draggable).remove();
            var $parentGroup = $(event.originalEvent.toElement).closest('.wfpositionGroup');
            var $wfIconGroupHeight = $parentGroup.find('> a > div.wfIcon');
                $wfIconGroupHeight.css('height', $wfIconGroupHeight.height() + 30 + 'px').attr('data-group-height', $wfIconGroupHeight.height() + 30);
            
            var _wfmTransitionArr = {
                ID : tempStatusId,
                LEFT : droppableLeft,
                TOP : droppableTop,
                TYPE : "rectangle",
                WFM_STATUS_CODE : tempStatusCode,
                WFM_STATUS_NAME : tempStatusName,
                WFM_STATUS_COLOR : tempStatusColor,
            };
                        
            if ($parentGroup.length) {
                $parentGroup.find('> a').append(setIcon(_wfmTransitionArr)).find('.wfposition:last').css({'left': 0, 'top': ($wfIconGroupHeight.height() - 30) + 'px'}).attr('data-pack-id', $parentGroup.attr('id'));
                $parentGroup.find('> a').find('.wfposition:last').find('.wfIcon').attr('data-left', 0).attr('data-top', $wfIconGroupHeight.height() - 30);
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
    var $dialogName = 'dialog-addworkflow-'+metaDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/addWorkFlowFirstStatusForm',
        dataType: 'json',
        data: {metaDataId : metaDataId},
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
            var $dialogName = 'dialog-addworkflowStatus-'+metaDataId;
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
                                url: 'mdprocessflow/createWfmWorkFlowPack',
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
                                    } 
                                    else {
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
        'item1' : {
            'label' : 'item1',
            'action' : function (e, data) { 
                console.log('item1 = ' , e);
                /* action */ }
        },
        'item2' : {
            'label' : 'item2',
            'action' : function (e, data) { /* action */ 
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
        data: {params : params, metaDataId: mainMetaDataId},
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
                    $('#'+selectedTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
                    selectJsPlumbViewBy(selectedTransitionId);
                    Core.unblockUI();
                } else {
                    $('.list-jtree-' + mainMetaDataId).find('.jstree-clicked').removeClass('jstree-clicked');
                    $('#'+_selectTransitionId).find('.jstree-anchor').addClass('jstree-clicked');
                    selectJsPlumbViewBy(_selectTransitionId);
                    Core.unblockUI();
                }
            } 
            else {
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

function checkSelectedTransitionId () {
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

function appendWorkflowGroup (mainBpId, elem) {
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/insertWfmStatusPack',
        async: false,
        success: function (data) {

            if (data == '') {
                alert('Failed!');
                return;
            }
  
            var wfIconArray = {
                id: data,
                doBpId: '',
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
        },
        error: function () {
            alert("Error");
        }
    });      

}    