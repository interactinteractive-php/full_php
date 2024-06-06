var selectedObj, strokeCommon, drawRowCount = 0, wfIconAddPositionTop = 20, wfIconAddPostionLeft = 20;
var isViewTaskFlow = true;

$(function() {

    var x = 100000000;
    var me = this;
    var windows;
    var arrowStyle = 'Flowchart'; //Straight, Flowchart, Bezier, StateMachine

    $('#editor').on('click', 'div.wfposition', function () {
        selectedObj = $(this);
        setControlVal(selectedObj);
    });

    $.contextMenu({
        selector: '.wfMenu', 
        build: function($trigger, e) {
            
            var mainBpId = $("#mainBpId").val();
            var doBpId = $trigger.find(".wfIcon").attr('data-dobpid');
            var pId = $trigger.find(".wfIcon").attr('data-workflowid');
            var id = $trigger.attr('id');
                        
            var contextMenuData = {
                "configParameter": {
                    name: 'Parameter config', 
                    icon: 'cogs', 
                    callback: function(key, options) {
                        callMetaParameter(mainBpId, doBpId, pId);
                    }
                },
                "configSchedule": {
                    name: 'Schedule config', 
                    icon: 'clock', 
                    callback: function(key, options) {
                        
                        if (mainBpId === doBpId) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Үндсэн процэсс дээр тохируулах боломжгүй',
                                type: 'warning',
                                sticker: false
                            });
                        } else {
                            callMetaScheduleConfig(mainBpId, doBpId, pId);
                        }
                    }
                },
                "arrowDelete": {
                    name: "Remove transition", 
                    icon: "trash", 
                    callback: function(key, options) {
                        jsPlumb.select({source: id}).detach();
                    }
                },
                "delete": {
                    name: "Remove process", 
                    icon: "trash", 
                    callback: function(key, options) {
                        if ((!isTaskFlow && doBpId == mainBpId) || pId == '0') {
                            new PNotify({
                                title: 'Анхаар',
                                text: 'Устгах боломжгүй',
                                type: 'warning',
                                sticker: false
                            });
                        } else {

                            var dialogName = '#deleteConfirm';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).html(plang.get('msg_delete_confirm'));
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('msg_title_confirm'),
                                width: '350',
                                height: 'auto',
                                modal: true,
                                buttons: [
                                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                        jsPlumb.detach(id);
                                        jsPlumb.remove(id);
                                        $(dialogName).dialog('close');
                                    }},
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $(dialogName).dialog('close');
                                    }}
                                ]
                            });
                        }
                        $(dialogName).dialog('open');
                    }
                }, 
                "edit": {
                    name: "Процесс засах", 
                    icon: "edit", 
                    callback: function(key, options) {
                        window.open('mdmetadata/gotoEditMeta/' + doBpId, '_blank');
                    }
                }, 
                "gotofolder": {
                    name: "Процессийн фолдер руу очих", 
                    icon: "folder-open", 
                    callback: function(key, options) {
                        window.open('mdmetadata/gotoFolder/' + doBpId, '_blank');
                    }
                }
            };
            
            if ($trigger.hasClass('wfcomplexbp')) {
                contextMenuData.gotoComplexConfig = {
                    name: "Нийлмэлийн тохиргоо", 
                    icon: 'sitemap', 
                    callback: function(key, options) {
                        window.open('mdprocessflow/metaProcessWorkflow/' + doBpId, '_blank');
                    }
                };
            }
            
            if (isTaskFlow) {
                
                if ($trigger.hasClass('wf-tf-UI')) {
                    contextMenuData.changeTaskFlowType = {
                        name: 'Not UI', 
                        icon: 'external-link', 
                        callback: function(key, options) {
                            PNotify.removeAll();
                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/changeTaskFlowType',
                                data: {mainBpId: mainBpId, doBpId: doBpId, type: ''},
                                dataType: 'json', 
                                success: function (data) {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                    
                                    if (data.status == 'success') {
                                        $trigger.removeClass('wf-tf-UI');
                                    } 
                                }
                            });
                        }
                    };
                } else {
                    contextMenuData.changeTaskFlowType = {
                        name: 'UI', 
                        icon: 'external-link', 
                        callback: function(key, options) {
                            PNotify.removeAll();
                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/changeTaskFlowType',
                                data: {mainBpId: mainBpId, doBpId: doBpId, type: 'UI'},
                                dataType: 'json', 
                                success: function (data) {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                        
                                    if (data.status == 'success') {
                                        $trigger.addClass('wf-tf-UI');
                                    } 
                                }
                            });
                        }
                    };
                }
            }
            
            contextMenuData.setDescription = {
                name: 'Тайлбар', 
                icon: 'sticky-note', 
                callback: function(key, options) {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/getProcessflowDescription',
                        data: {mainBpId: mainBpId, doBpId: doBpId}, 
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function (data) {
                            var $dialogName = 'dialog-bp-descr';
                            if (!$("#" + $dialogName).length) {
                                $('<div id="' + $dialogName + '"></div>').appendTo('body');
                            }
                            var $dialog = $('#' + $dialogName);
                            var textarea = '<textarea class="form-control" rows="5" spellcheck="false">' + dvFieldValueShow(data.description) + '</textarea>';
                            
                            $dialog.html(textarea);
                            $dialog.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('description'),
                                width: '500',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                        
                                        PNotify.removeAll();
                                        var updateDescr = $dialog.find('textarea').val();
                                        
                                        $.ajax({
                                            type: 'post',
                                            url: 'mdprocessflow/updateProcessflowDescription',
                                            data: {mainBpId: mainBpId, doBpId: doBpId, description: updateDescr}, 
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({message: 'Loading...', boxed: true});
                                            },
                                            success: function (data) {
                                                if (data.status == 'success') {
                                                    if (updateDescr == '' || updateDescr == null) {
                                                        $trigger.find('.pflow-bp-descr').remove();
                                                    } else {
                                                        if ($trigger.find('.pflow-bp-descr').length) {
                                                            $trigger.find('.pflow-bp-descr').html(updateDescr);
                                                        } else {
                                                            $trigger.find('.iconText').after('<div class="pflow-bp-descr">'+updateDescr+'</div>');
                                                        }
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
                                        
                                        $dialog.dialog('close');
                                    }},
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            $dialog.dialog('open');
                            Core.unblockUI();
                        }
                    });
                }
            };
            
            var options =  {
                callback: function (key, opt) {
                    eval(key);
                },
                items: contextMenuData
            };

            return options;
        }
    });

    workflowConnectionImport = function (elem) {
        var common = {
            connector: [arrowStyle, {stub: [10, 20], gap: 5, cornerRadius: 5, alwaysRespectStubs: true}], /*[Straight, Flowchart, Bezier, StateMachine]*/
            paintStyle: {radius: 5},
            hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 5},
            dragOptions: {cursor: 'pointer'}
        };
        if ((typeof elem['source'] != 'undefined' && typeof elem['target'] != 'undefined') 
            && (elem['source'] != '' && elem['target'] != '')) {
        
            jsPlumb.connect({
                source: elem['source'],
                target: elem['target']
            }, common);
        }
    };

    workflow = function (elem) {
        jsPlumb.importDefaults({
            ConnectionsDetachable: false,
            ReattachConnections: false,
            connector: [arrowStyle, {stub: [10, 20], gap: 5, cornerRadius: 5, alwaysRespectStubs: true}],
            ConnectionOverlays: [["Arrow", {location: 1, length: 14}]],
            Endpoint: ["Dot", {radius: 6}],
        });

        windows = jsPlumb.getSelector('.wfposition');

        jsPlumb.makeSource(windows, {
            filter: ".connect",
            anchor: "Continuous",
            isSource: true,
            isTarget: false,
            reattach: true,
            maxConnections: 99,
            connector: [arrowStyle, {stub: [10, 20], gap: 5, cornerRadius: 1, alwaysRespectStubs: true}],
            connectorPaintStyle: {
                strokeStyle: "green",
                lineWidth: 2
            },
            connectorHoverPaintStyle: {
                strokeStyle: "#77ca00",
                outlineColor: "#77ca00",
                outlineWidth: 5
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
            isSource: false,
            isTarget: true,
            reattach: true,
            setDragAllowedWhenFull: true,
            dropOptions: {hoverClass: "dragHover"},
            anchor: "Continuous",
            paintStyle: {fillStyle: "transparent"},
            hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 7}
        });
        me.arrastrable();
    };

    me.arrastrable = function () {
        jsPlumb.draggable($(".wfposition"), {
            containment: "workFlowEditor"
        });
    };

    setIcon = function (elem) {
        var _left = elem['positionLeft'];
        var _top = elem['positionTop'];
        var linkTitle = elem['title'] + ' (' + elem['metaDataCode'] + ')';
        var isComplexProcess = '', complexProcessClass = '';
        if (elem['id'] == 'startObject001') {
            linkTitle = 'Эхлэл';
        }
        if (elem['id'] == 'endObject001') {
            linkTitle = 'Төгсгөл';
        }
        
        if (elem.hasOwnProperty('isComplexProcess') && elem.isComplexProcess == 1) {
            isComplexProcess = '<span class="is-complex-bp" title="Нийлмэл процесс"><i class="icon-tree7"></i></span>';
            complexProcessClass = ' wfcomplexbp';
        }
        
        if (elem.hasOwnProperty('metaTypeId') && elem.metaTypeId == '200101010000043') {
            isComplexProcess = '';
            complexProcessClass = ' wfcomplexbp';
        }
        
        var html = '<div id="' + elem['id'] + '" ' +
                'class="wfposition wfMenu ' + elem['type'] + complexProcessClass + ' wf-tf-'+elem['taskflowType']+'" ondblclick="processDrillDown(this)" ' +
                'style=" width: ' + elem['width'] + 'px; height: ' + elem['height'] + 'px; display: inline-block; top: ' + _top + 'px; left: ' + _left + 'px;">' +
                isComplexProcess + 
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
        
        if (elem['description'] != '' && elem['description'] != null) {
            html += '<div class="pflow-bp-descr">'+elem['description']+'</div>';
        }
        
        html += '<div class="connect"></div>' +
                (elem['childProcess'] == '1' ? ' <div class="drill-down-icon"><i class="icon-plus3 font-size-12"></i></div>' : '') +
                '<input type="hidden" data-boolentrueid="-1" data-boolenfalseid="-1" name="' + elem['id'] + '" id="metaDataBoolen' + elem['id'] + '">' +
                '</a>';
        
        if (elem.hasOwnProperty('metaTypeId') && elem.metaTypeId == '200101010000043') {
            html += '<div style="text-align: center;margin-top: -13px;font-size: 18px;"><i class="far fa-plus-square" style="background-color: #fff;" title="Taskflow"></i></div>';
        }
        
        html += '</div>';
        
        return html;
    };

    setControlVal = function (elem) {
        $('.wfposition.selected').removeClass('selected');
        elem.addClass('selected');
        elem.find('.wfIcon').attr({'data-top': elem.position().top, 'data-left': elem.position().left});
    };

    drawWorkFlowListHtml = function (metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/getChildMetaByProcess',
            data: {metaDataId: metaDataId},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {

                var i = 0;
                var bpData = data.bpData;
                
                if (data.className === 'null' || data.className == null) {
                    $("button#mainBpParamSet").show();
                } else {
                    $("button#mainBpParamSet").hide();
                }
                
                $('#metaProcessDetial').empty();
                
                $('#metaProcessDetial').append(
                    '<div class="mb10">' +
                    '<div class="caption buttons">' +
                    '<button type="button" class="btn btn-xs blue previewMeta"><i class="fa fa-bullseye"></i> Визуал</button>' +
                    '<button type="button" class="btn ml10 pl10 btn-xs green saveMeta"><i class="fa fa-save"></i> Хадгалах</button>' +
                    '</div>' +
                    '</div>' +
                    '<table class="table table-sm table-bordered table-hover" id="bpChild" cellspacing="0" width="100%">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Order</th>' +
                    '<th>Business process</th>' +
                    '<th>Type</th>' +
                    '<th>True order</th>' +
                    '<th>False order</th>' +
                    '<th>Is start</th>' +
                    '<th></th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                    '</tbody>' +
                '</table>');
        
                $.each(bpData, function () {
                    var checkClass = '';
                    var check = '';
                    var checkVal = 0;
                    i = i + 1;

                    if (this.IS_START == 1) {
                        checkClass = 'class="checked"';
                        check = 'checked="checked"';
                        var checkVal = this.META_DATA_ID;
                    }
                    if (this.BP_ORDER == null) {
                        this.BP_ORDER = i;
                    }
                    
                    $('#metaProcessDetial').find('#bpChild tbody').append(
                        '<tr>' +
                        '<td>' +
                        '<input type="hidden" class="form-control" style="max-width:50px;" name="id[]" value="' + ((this.META_PROCESS_WORKFLOW_ID != null) ? this.META_PROCESS_WORKFLOW_ID : "") + '">' +
                        '<input type="text" class="form-control" style="max-width:50px;" name="bpOrder[]" value="' + this.BP_ORDER + '">' +
                        '<input type="hidden" value="' + this.META_TYPE_CODE + '" name="metaTypeCode[]">' +
                        '<td><input type="hidden" value="' + this.META_DATA_ID + '" name="doBpId[]"><input type="hidden" value="' + this.META_DATA_NAME + '" name="metaDataName[]">' + this.META_DATA_NAME + '</td>' +
                        '<td>' + this.META_TYPE_CODE + '</td>' +
                        '<td>' +
                        '<input type="text" name="trueOrder[]" class="form-control" value="' + ((this.TRUE_ORDER != null) ? this.TRUE_ORDER : "") + '">' +
                        '<input type="hidden" name="oldTrueOrder[]" class="form-control" value="' + ((this.TRUE_ORDER != null) ? this.TRUE_ORDER : "") + '">' +
                        '</td> ' +
                        '<td>' +
                        '<input type="text" name="falseOrder[]" class="form-control" value="' + ((this.FALSE_ORDER != null) ? this.FALSE_ORDER : "") + '">' +
                        '<input type="hidden" name="oldFalseOrder[]" class="form-control" value="' + ((this.FALSE_ORDER != null) ? this.FALSE_ORDER : "") + '">' +
                        '</td>' +
                        '<td class="middle text-center"><div class="radio-list"><label class="radio-inline"><span ' + checkClass + '>' +
                        '<input type="radio" name="isStart" class="IS_START" id="isStart' + i + '" value="' + checkVal + '" data-id="' + this.META_DATA_ID + '" ' + check + '></span>  </label></div>' +
                        '</td>' +
                        '<td class="middle text-center"><button type="button" class="extra" data-id="' + this.META_DATA_ID + '">...</button></td>' +
                    '</tr>');
                });
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($('#metaProcessDetial'));
        });
    };

    viewVisualMetaData = function () {
        if ($('#mainBpId').val() != '') {
            var dialogNameMeta = '#priviewMetaDialog';
            if (!$(dialogNameMeta).length) {
                $('<div id="' + dialogNameMeta.replace('#', '') + '"></div>').appendTo('body');
            }
            $.ajax({
                type: 'post',
                url: 'mdprocessflow/drawProcess',
                data: $('#metaProcess-form').serialize(),
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({animate: true});
                },
                success: function (data) {
                    $(dialogNameMeta).html('<div class="heigh-editor"><input type="hidden" value="0" name="clickBoolenTrue"><input type="hidden" value="0" name="clickBoolenFalse"><div class="css-editor" id="workFlowEditor"></div></div>');
                    $('#workFlowEditor').html('');

                    $.each(data['object'], function (index, value) {
                        $('#workFlowEditor').append(setIcon(value));
                        workflow(value);
                    });
                    jsPlumb.detachEveryConnection();
                    var tempArray = [];
                    
                    $.each(data['connect'], function (index, value) {
                        if (value['lineBoolenType'] == 0 || value['lineBoolenType'] == 1) {
                            tempArray.push({'source': value['source'], 'target': value['target'], 'lineBoolenType': value['lineBoolenType']});
                        }
                        workflowConnectionImport({'source': value['SOURCE'], 'target': value['TARGET']});
                        workflow({'source': value['SOURCE'], 'target': value['TARGET']});
                    });

                    $('.wfposition').draggable({
                        containment: '#workFlowEditor',
                        stop: function () {
                            selectedObj = $(this);
                            setControlVal(selectedObj);
                        }
                    });
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                    $(dialogNameMeta).dialog('close');
                }
            }).done(function () {
                Core.initAjax($('#workFlowEditor'));
            });

            $(dialogNameMeta).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Бизнес процессийн загварчлал',
                width: '1000',
                height: 'auto',
                modal: true,
                open: function () {
                    setTimeout(function () {
                        $(dialogNameMeta).dialog("option", "position", {my: "center", at: "center", of: window});
                    }, 200);
                },
                buttons: [
                    {text: 'Хадгал', class: 'btn green btn-sm saveVisualParam', click: function () {
                        saveVisualMetaData($(dialogNameMeta).dialog('close'));
                    }},
                    {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function () {
                        $(dialogNameMeta).dialog('close');
                    }}
                ]
            }).dialog('open');
        }
    };

    viewVisualHtmlMetaProcessFlowData = function (mainBpId) {
        if (mainBpId != '') {
            
            var editorHeight = $(window).height() - 288;
            
            $.ajax({
                type: 'post',
                url: 'mdprocessflow/getChildMetaByProcess',
                data: {metaDataId: mainBpId, isIgnoreProcessList: 1},
                dataType: 'json',
                success: function (data) {
                    if (data.className === 'null' || data.className == null) {
                        $("button#mainBpParamSet").show();
                    } else {
                        $("button#mainBpParamSet").hide();
                    }

                    $('#metaProcessDetial').empty().append(
                            '<div class="mb10">' +
                                '<button type="button" class="btn btn-sm bg-indigo-400 mr-1 saveVisualParam">Хадгалах</button>' +
                                '<button type="button" class="btn btn-sm bg-success-400 addVisualMetaData">Нэмэх</button>' +
                                '<button type="button" class="btn btn-sm bg-danger-400 removeAllArrowData ml-1">Бүх сум устгах</button>' +
                            '</div>' +
                            '<div class="heigh-editor">' +
                                '<div class="css-editor" id="workFlowEditor" style="height: '+editorHeight+'px;"></div>' +
                            '</div>');
                    $('#workFlowEditor').empty();
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/drawProcessHtml',
                        data: {processData: data, mainBpId: mainBpId},
                        dataType: 'json',
                        success: function (data) {

                            $.each(data['object'], function (index, value) {
                                
                                $('#workFlowEditor').append(setIcon(value));
                                workflow(value);
                                
                                $('.heigh-editor', '#metaProcessDetial').append(
                                    '<input type="hidden" value="' + dvFieldValueShow(value.isScheduled) + '" name="' + value.doBpId + '_isscheduled" id="' + value.doBpId + '_isscheduled">'
                                    + '<input type="hidden" value="' + dvFieldValueShow(value.scheduledDatePath) + '" name="' + value.doBpId + '_scheduledpath" id="' + value.doBpId + '_scheduledpath">'
                                );
                            });

                            jsPlumb.detachEveryConnection();
                            var tempArray = [];
                            
                            $.each(data['connect'], function (index, value) {
                                var getBpId = value['SOURCE'] + '_' + value['TARGET'];
                                $('.heigh-editor', '#metaProcessDetial').append('<input type="hidden" value="' + dvFieldValueShow(value.CRITERIA) + '" name="' + getBpId + '" id="' + getBpId + '">');
                                workflowConnectionImport({source: value['SOURCE'], target: value['TARGET']});
                                workflow({source: value['SOURCE'], target: value['TARGET']});
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
                                }
                            });
                        },
                        error: function () {
                            $('#metaProcessDetial').html('');
                        }
                    });
                }
            });
        }
    };

    saveVisualMetaData = function (closeDailog, mainBpId) {
        
        var strBoolen = 0, clickBoolen = 0, boolenTrue = '', boolenFalse = '';
        var objects = []
        $('#wfEditorHiddenValues').empty();

        $("#workFlowEditor").find(".wfposition").each(function () {

            var $elem = $(this);
            var endpoints = jsPlumb.getEndpoints($elem.attr('id'));
            var boolenTrue = $elem.find("input[type=hidden]").attr("data-boolentrueid");
            var boolenFalse = $elem.find("input[type=hidden]").attr("data-boolenfalseid");
            objects.push({
                id: $elem.attr('id'),
                dobpid: $elem.find(".wfIcon").attr('data-dobpid'),
                metaProcessWorkFlowId: $elem.find(".wfIcon").attr('data-metaprocessworkflowid'),
                metaTypeCode: $elem.find(".wfIcon").attr('data-metatypecode'),
                bpOrder: $elem.find(".wfIcon").attr('data-bporder'),
                title: $elem.find(".wfIcon").attr('data-title'),
                type: $elem.find(".wfIcon").attr('data-type'),
                class: $elem.find(".wfIcon").attr('data-class'),
                positionTop: $elem.find(".wfIcon").attr('data-top'),
                positionLeft: $elem.find(".wfIcon").attr('data-left'),
                borderColor: $elem.find(".wfIcon").attr('data-border-color'),
                borderWidth: $elem.find(".wfIcon").attr('data-border-width'),
                background: $elem.find(".wfIcon").attr('data-background'),
                width: $elem.find(".wfIcon").attr('data-width'),
                height: $elem.find(".wfIcon").attr('data-height'),
                metaDataBoolen: strBoolen,
                metaDataTrueOrder: (boolenTrue != -1 ? boolenTrue : ''),
                metaDataFalseOrder: (boolenFalse != -1 ? boolenFalse : '')
            });
            strBoolen = 0;
        });

        var connections = [];
        $.each(jsPlumb.getConnections(), function (idx, connection) {
            var targetId = '', sourceId = '';
            if (typeof connection.targetId !== 'undefined' || connection.targetId !== "") {
                targetId = connection.targetId;
            }
            if (typeof connection.sourceId !== 'undefined' || connection.sourceId !== "") {
                sourceId = connection.sourceId;
            }
            connections.push({
                connectionId: connection.id,
                pageSourceId: sourceId,
                pageTargetId: targetId,
                strokeStyle: connection._jsPlumb.paintStyle['strokeStyle'],
                lineWidth: connection._jsPlumb.paintStyle['lineWidth']
            });
        });
        if (objects.length > 1) {
            closeDailog;
            var d = $.ajax({
                type: 'post',
                url: 'mdprocessflow/saveVisualMetaProcess',
                data: {objects: JSON.stringify(objects), connections: JSON.stringify(connections), mainBpId: $('#mainBpId').val()},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({animate: true});
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
                        viewVisualHtmlMetaProcessFlowData($('#mainBpId').val());
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
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
    };

    saveVisualMetaControlData = function (closeDailog, mainBpId) {
        var strBoolen = 0, clickBoolen = 0, boolenTrue = '', boolenFalse = '';
        var objects = [];
        
        $('#wfEditorHiddenValues').empty();

        $("#workFlowEditor").find(".wfposition").each(function () {

            var $elem = $(this);
            var endpoints = jsPlumb.getEndpoints($elem.attr('id'));
            var boolenTrue = $elem.find("input[type=hidden]").attr("data-boolentrueid");
            var boolenFalse = $elem.find("input[type=hidden]").attr("data-boolenfalseid");
            var doBpId = $elem.find(".wfIcon").attr('data-dobpid');
            var isScheduled = $('#'+doBpId+'_isscheduled').val();
            var scheduledPath = $('#'+doBpId+'_scheduledpath').val();
            
            objects.push({
                id: $elem.attr('id'),
                dobpid: doBpId,
                processWorkFlowId: $elem.find(".wfIcon").attr('data-workflowid'),
                title: $elem.find(".wfIcon").attr('data-title'),
                type: $elem.find(".wfIcon").attr('data-type'),
                positionTop: $elem.find(".wfIcon").attr('data-top'),
                positionLeft: $elem.find(".wfIcon").attr('data-left'), 
                isScheduled: (isScheduled ? isScheduled : ''), 
                scheduledPath: (scheduledPath ? scheduledPath : ''), 
                taskflowType: $elem.hasClass('wf-tf-UI') ? 'UI' : '', 
                description: $elem.find('.pflow-bp-descr').text()
            });
            strBoolen = 0;
        });

        var connections = [], conn = [];
        $.each(jsPlumb.getConnections(), function (idx, connection) {
            var targetId = '', sourceId = '';
            if (typeof connection.targetId !== 'undefined' || connection.targetId !== "") {
                targetId = connection.targetId;
            }
            if (typeof connection.sourceId !== 'undefined' || connection.sourceId !== "") {
                sourceId = connection.sourceId;
            }
            if (!$('#' + connection.sourceId + '_' + connection.targetId).length) {
                $('<div id="' + connection.sourceId + '_' + connection.targetId + '"></div>').appendTo('.heigh-editor', '#metaProcessDetial');
            }
            connections.push({
                connectionId: connection.id,
                pageSourceId: sourceId,
                pageTargetId: targetId,
                strokeStyle: connection._jsPlumb.paintStyle['strokeStyle'],
                lineWidth: connection._jsPlumb.paintStyle['lineWidth'],
                criteria: $('#' + sourceId + '_' + targetId).val()
            });
        });
        
        $("#workFlowEditor, .metaWfmStatusForm").find(".wfposition").each(function () {
            var $elem = $(this);
            conn.push({
                id: $elem.attr('id'),
                positionTop: $elem.find(".wfIcon").attr('data-top'),
                positionLeft: $elem.find(".wfIcon").attr('data-left')
            });
        });
        
        if (objects.length > 1) {
            closeDailog;
            var d = $.ajax({
                type: 'post',
                url: 'mdprocessflow/saveVisualMetaProcessWorkflow',
                data: {objects: JSON.stringify(objects), connections: JSON.stringify(connections), mainBpId: $('#mainBpId_valueField').val(), conn: conn},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
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
                        viewVisualHtmlMetaProcessFlowData($('#mainBpId').val());
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Хадгалах боломжгүй угсрах процесс сонгоно уу',
                type: 'error',
                sticker: false
            });
        }
    };

    callMetaParameter = function (mainBpId, doProcessId, pId) {
        
        var dialogName = '#bpChildDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);
        var connections = [];
        var connection = returnDoneProcessList(pId, connections, jsPlumb.getConnections());
        
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/getInputMetaParameterByProcess',
            data: {mainBpId: mainBpId, doProcessId: doProcessId, connection: connection},
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {
                $dialog.empty().append(data);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Parameter',
                    width: '1200',
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/saveMetaProcessParameter',
                                data: $('#metaProcessParameter-form').serialize(),
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({boxed: true, message: 'Хадгалж байна...'});
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
                                    } 
                                    Core.unblockUI();
                                },
                                error: function () { alert('Error'); }
                            });
                        }},
                        {text: plang.get('close_btn'), class: 'btn grey-cascade btn-sm', click: function () {
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
                
                setTimeout(function(){
                    $dialog.dialogExtend('maximize');
                    $dialog.dialog('open');
                }, 50);
                
                Core.unblockUI();
            },
            error: function () { alert("Error"); }
        });
    };
    
    callMetaScheduleConfig = function (mainBpId, doProcessId, pId) {
        
        var dialogName = '#bpChildDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);
        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdprocessflow/getScheduleConfig',
            data: {mainBpId: mainBpId, doProcessId: doProcessId},
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {
                if (data.status !== 'success') {
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
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Schedule config',
                    width: '500',
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/savescheduleConfig',
                                data: $('#bp-scheduleconfig-form').serialize(),
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({boxed: true, message: 'Хадгалж байна...'});
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
                                        $('#'+doProcessId+'_isscheduled').val((data.isScheduled ? data.isScheduled : ''));
                                        $('#'+doProcessId+'_scheduledpath').val((data.scheduledDatePath ? data.scheduledDatePath : ''));
                                        $dialog.dialog('close');
                                    }
                                    
                                    Core.unblockUI();
                                },
                                error: function () { alert("Error"); }
                            });
                        }},
                        {text: plang.get('close_btn'), class: 'btn grey-cascade btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                
                $dialog.dialog('open');
                Core.initUniform($dialog);
                Core.unblockUI();
            },
            error: function () { alert("Error"); }
        });
    };

    selectableCommonMetaDataGrid = function (chooseType, elem, params) {
        if (elem === 'metaGroup') {
            var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
            if (metaBasketNum > 0) {
                var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
                var wfIconClass = 'wfIconRectangle',
                    wfIconType = 'rectangle',
                    wfIconWidth = 160,
                    wfIconHeight = 70,
                    bpOrder = 0;

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
                        if ($elem.attr('id') === row.META_DATA_ID) {
                            alert('Нэг процесс 2 дуудагдаж байна');
                            isAddRow = false;
                        }
                    });
                    if (isAddRow) {
                        var tempWidth = (parseInt($("#workFlowEditor").width()) - 120) - parseInt(wfIconAddPostionLeft);

                        if (parseInt(tempWidth) < 0) {
                            wfIconAddPostionLeft = 20;
                            wfIconAddPositionTop = wfIconAddPositionTop + 120;
                        }
                        
                        var uid = bpGetUid();
                        wfIconArray = {
                            id: uid,
                            doBpId: metaDataId,
                            title: metaDataName,
                            type: wfIconType,
                            class: wfIconClass,
                            positionTop: wfIconAddPositionTop,
                            positionLeft: wfIconAddPostionLeft,
                            width: wfIconWidth,
                            height: wfIconHeight,
                            metaDataCode: metaDataCode, 
                            isComplexProcess: row.hasOwnProperty('IS_COMPLEX_PROCESS') ? row.IS_COMPLEX_PROCESS : 0, 
                            metaTypeId: row.META_TYPE_ID 
                        };

                        $('#workFlowEditor').append(setIcon(wfIconArray));
                        workflow(wfIconArray);
                        wfIconAddPostionLeft = wfIconAddPostionLeft + 180;

                        $('.wfposition').draggable({
                            containment: '#workFlowEditor',
                            stop: function () {
                                selectedObj = $(this);
                                setControlVal(selectedObj);
                            }
                        });

                    }
                }
            }
        }
    };

});

function returnDoneProcessList(doProcessId, connections, getConnections) {
    $.each(getConnections, function (idx, connection) {
        var targetId = '', sourceId = '';
        if (typeof connection.targetId !== 'undefined' || connection.targetId !== '') {
            targetId = connection.targetId;
        }
        if (typeof connection.sourceId !== 'undefined' || connection.sourceId !== '') {
            sourceId = connection.sourceId;
        }
        if (sourceId == doProcessId && sourceId != '') {
            returnDoneProcessListF(sourceId, connections, getConnections);
        }
        if (targetId == doProcessId && targetId != '') {
            returnDoneProcessListF(sourceId, connections, getConnections);
            connections.push({
                connectionId: connection.id,
                pageSourceId: sourceId,
                pageTargetId: targetId,
                strokeStyle: connection._jsPlumb.paintStyle['strokeStyle'],
                lineWidth: connection._jsPlumb.paintStyle['lineWidth']
            });
        }
    });
    return connections;
}

function returnDoneProcessListF(doProcessId, connections, getConnections) {
    $.each(getConnections, function (idx, connection) {
        var targetId = '', sourceId = '';
        if (typeof connection.targetId !== 'undefined' || connection.targetId !== '') {
            targetId = connection.targetId;
        }
        if (typeof connection.sourceId !== 'undefined' || connection.sourceId !== '') {
            sourceId = connection.sourceId;
        }
        if (doProcessId == targetId && sourceId != doProcessId) {
            connections.push({
                connectionId: connection.id,
                pageSourceId: sourceId,
                pageTargetId: targetId,
                strokeStyle: connection._jsPlumb.paintStyle['strokeStyle'],
                lineWidth: connection._jsPlumb.paintStyle['lineWidth']
            });
            connections = returnDoneProcessListF(sourceId, connections, getConnections);
        }
    });
    return connections;
}