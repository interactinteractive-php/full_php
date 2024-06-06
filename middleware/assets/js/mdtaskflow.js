var selectedObj, strokeCommon, drawRowCount = 0,
        metaDataBoolenTrue = "Үнэн",
        metaDataBoolenFalse = "Худал",
        wfIconAddPositionTop = 20,
        wfIconAddPostionLeft = 20,
        wfObjectBoolean = "14359007153593",
        metaProcessWindowId = "#metaProcessWindow",
        businessProcessWindowId = '#businessPorcessWindow';


$(function () {

    var x = 100000000;
    var me = this;
    var windows;
    var arrowStyle = "Flowchart"; //Straight, Flowchart, Bezier, StateMachine


    $('#editor').on('click', 'div.wfposition', function () {
        selectedObj = $(this);
        setControlVal(selectedObj);
    });

    $.contextMenu({
        selector: '.wfMenu',
        callback: function (key, opt) {
            bpContextMenu(key, this);
        },
        items: {
            "configParameter": {name: "Параметр тохируулах", icon: "gears"},
            "arrowDelete": {name: "Сум устгах", icon: "trash"},
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
    $.contextMenu({
        selector: '.wfMenuStartCriteria',
        callback: function (key, opt) {
            bpContextMenu(key, this);
            if (key === 'startEndConfig') {
                startEndConfig(this);
            }
        },
        items: {
            "startEndConfig": {name: "Эхлэл цэг", icon: "star"},
            "configParameter": {name: "Параметр тохируулах", icon: "gears"},
            "arrowDelete": {name: "Сум устгах", icon: "trash"},
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
    $.contextMenu({
        selector: '.wfMenuStart',
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
        selector: '.runProcess',
        callback: function (key, opt) {
            if (key === 'runprocess') {
                console.log('run process');
            }
        },
        items: {
            "runprocess": {name: "Процесс ажиллуулах", icon: "run"}
        }
    });
    $.contextMenu({
        selector: '.viewhistory',
        callback: function (key, opt) {
            if (key === 'viewhistory') {
                getBusinessProcessProcessLog(this);
            }
        },
        items: {
            "viewhistory": {name: "Процессын түүх", icon: "history"}
        }
    });

    workflowConnectionImport = function (elem) {
        var common = {
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}], /*[Straight, Flowchart, Bezier, StateMachine]*/
            paintStyle: {radius: 5},
            hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 5},
            dragOptions: {cursor: 'pointer'}
        };
        if (elem['source'] != '' && elem['target'] != '') {
            jsPlumb.connect({
                source: elem['source'],
                target: elem['target']
            }, common);
        }
    }
    workflow = function (elem) {
        jsPlumb.importDefaults({
            ConnectionsDetachable: true,
            ReattachConnections: true,
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}],
            ConnectionOverlays: [["Arrow", {location: 1, length: 14}]],
            Endpoint: ["Dot", {radius: 6}]
        });
        
        windows = jsPlumb.getSelector('.wfposition');

        jsPlumb.makeSource(windows, {
            filter: ".connect",
            anchor: "Continuous",
            isSource: true,
            isTarget: false,
            reattach: true,
            maxConnections: 99,
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 1, alwaysRespectStubs: true}],
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
    }
    me.arrastrable = function () {
        jsPlumb.draggable($(".wfposition"), {
            containment: "workFlowEditor"
        });
    }
    setIcon = function (elem) {
        //console.log(elem);
        var _left = elem['positionLeft'];
        var _top = elem['positionTop'];
        var linkTitle = elem['title'] + ' (' + elem['metaDataCode'] + ')';
        if (elem['id'] == 'startObject001') {
            linkTitle = 'Эхлэл';
        }
        if (elem['id'] == 'endObject001') {
            linkTitle = 'Төгсгөл';
        }
        var html = '<div ' +
                'id="' + elem['id'] + '" ' +
                'class="wfposition ' + elem['type'] + (elem['type'] != 'circle' ? ' wfMenu ' : '') + (elem['id'] == 'startObject001' ? ' wfMenuStart wfStart ' : '') + (elem['id'] == 'endObject001' ? ' wfEnd ' : '') + ' " ' +
                'style="' +
                'display: inline-block;' +
                'top: ' + _top + 'px; ' +
                'left: ' + _left + 'px; ' +
                '"' +
                (elem['type'] != 'circle' ? 'onclick="setControlVal(this)"' : 'onclick="resetControlVal(this)"') +
                ' >' +
                '<a href="javascript:;" title="' + linkTitle + '">' +
                '<div ' +
                'class="wfIcon ' + elem['class'] + '" ' +
                'data-type="' + elem['type'] + '" ' +
                'data-top="' + elem['positionTop'] + '" ' +
                'data-left="' + elem['positionLeft'] + '" ' +
                'data-class="' + elem['class'] + '" ' +
                'data-title="' + elem['title'] + '" ' +
                'data-statusid="' + elem['statusId'] + '" ' +
                'data-isnonflow="' + elem['isNonFlow'] + '" ' +
                'data-lifecycledtlid="' + elem['lifeCycleDtlId'] + '" ' +
                'data-lifecycleid="' + elem['lifeCycleId'] + '" ' +
                'data-processmetadataid="' + elem['processMetaDataId'] + '" ' +
                '>' +
                '</div>';
        html += '<span class="iconText">';
        if (elem['type'] == 'rectangle') {
            html += '<div class="bp-code">' + (elem['type'] != 'circle' ? ' (' + elem['metaDataCode'] + ')' : '') + '</div>';
            html += '<div class="bp-name">' + elem['title'] + '</div>';
        }
        html += '</span>';
        if (elem['type'] != 'circle') {
            html += '<input type="hidden" name="objectMetaId[]" value="' + elem['id'] + '">';
        }

        html += '<div class="connect"></div>' +
                '</a>' +
                '</div>';
        return html;
    }
    setControlVal = function (elem) {

        $("#processConfig", metaProcessWindowId).show();
        $(".metaDmBehaviourDtlConfig", metaProcessWindowId).find("table tbody").empty();
        var currentObj = $(elem);

        $('.wfposition').each(function () {
            $(this).removeClass('selected');
        });
        currentObj.addClass('selected');
        var id = currentObj.attr('id');//visualeditor tsonhnoos songoson metadataid
        var _top = currentObj.position().top;//find('.wfIcon').attr('data-top');
        var _left = currentObj.position().left;//currentObj.find('.wfIcon').attr('data-left');
        var iconText = currentObj.find('.iconText').html();
        currentObj.find('.wfIcon').attr('data-top', _top);
        currentObj.find('.wfIcon').attr('data-left', _left);
        var lifeCycleId = currentObj.find('.wfIcon').attr('data-lifecycleid');
        var processMetaDataId = currentObj.find('.wfIcon').attr('data-processmetadataid');

        //property хэсэгт утга оноох эхлэл

        var title = currentObj.find('a').attr('title');
        var statusId = currentObj.find('a div.wfIcon').attr('data-statusId');
        var isNonFlowId = currentObj.find('a div.wfIcon').attr('data-isNonFlow');

        $("#selectedObject", metaProcessWindowId).val(id);
        $("#selectedObjectName", metaProcessWindowId).val(title);

        var sidebarContent = $(".taskFlowMainConfig", metaProcessWindowId).find("table tbody");
        sidebarContent.html('');
        var sideBarString = '';

        sideBarString += "<tr>";
        sideBarString += "<td style='width: 150px;' class='left-padding'>Нэр:</td>";
        sideBarString += "<td class='text-left'><p class='property_page_sum_padding form-control-plaintext'>" + title + "</p></td>";
        sideBarString += "</tr>";

        sideBarString += "<tr style='display:none;'>";
        sideBarString += "<td style='width: 150px;' class='left-padding'>Статус:</td>";
        sideBarString += "<td class='text-left'><select id=\"statusId\" name=\"statusId\" class=\"form-control select2me propertyChange\" data-placeholder=\"Статус\"><option value=\"\"> </option></select> </td>";
        sideBarString += "</tr>";

        var isNonFlow = [{ID: '0', TITLE: 'Холбоостой'}, {ID: '1', TITLE: 'Холбоосгүй'}];
        sideBarString += "<tr>";
        sideBarString += "<td style='width: 150px;' class='left-padding'>Is nonFlow:</td>";
        sideBarString += "<td class='text-left'><select id=\"isNonFlow\" name=\"isNonFlow\" class=\"form-control select2me propertyChange\" data-placeholder=\"Холбоос тохиргоо\"><option value=\"\"> </option></select></td>";
        sideBarString += "</tr>";

        sideBarString += '<tr>';
        sideBarString += '<td class="left-padding">Оролтын параметр:</td>';
        sideBarString += '<td colspan="2">';
        sideBarString += '<button type="button" class="btn btn-sm purple-plum" onclick="setLcBpParamAttributes(\'' + id + '\');">...</button>';
        sideBarString += '<div id="dialog-paramattributes" style="display: none"></div>';
        sideBarString += '</td>';
        sideBarString += '</tr>';
        sideBarString += '<tr>';
        sideBarString += '<td class="left-padding">Гаралтын параметр:</td>';
        sideBarString += '<td colspan="2">';
        sideBarString += '<button type="button" class="btn btn-sm purple-plum" onclick="setLcBpOutputParamAttributes(\'' + id + '\');">...</button>';
        sideBarString += '<div id="dialog-outputparamattributes" style="display: none"></div>';
        sideBarString += '</td>';
        sideBarString += '</tr>';

        sidebarContent.prepend(sideBarString);

        var nonFlowString = '';

        $.each(isNonFlow, function () {
            nonFlowString += '<option value="' + this.ID + '" ' + (isNonFlowId === this.ID ? 'selected' : '') + '>' + this.TITLE + '</option>';
        });

        $("#isNonFlow", metaProcessWindowId).html(nonFlowString);

        var statusString = '';
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/getWorkFlowStatusList',
            dataType: 'json',
            data: {metaDataId: $("#dataModelId", metaProcessWindowId).val()},
            success: function (data) {
                $("option:gt(0)", $("#statusId", metaProcessWindowId)).remove();
                $.each(data, function () {
                    statusString += '<option value="' + this.WFM_STATUS_ID + '" ' + (statusId === this.WFM_STATUS_ID ? 'selected' : '') + '>' + this.WFM_STATUS_CODE + ' - ' + this.WFM_STATUS_NAME + '</option>';
                });
                $("#statusId", metaProcessWindowId).append(statusString);
            }
        }).done(function () {
            Core.initAjax();
        });

        $("#statusId", metaProcessWindowId).on("change", function () {
            $("#" + id).find(".wfIcon").attr('data-statusid', $(this).val());
        });
        $("#isNonFlow", metaProcessWindowId).on("change", function () {
            $("#" + id).find(".wfIcon").attr('data-isnonflow', $(this).val());
        });

        //Хугацааны тохиргоо хийх
        var dtl = $("." + id, metaProcessWindowId);
        var dtlCount = dtl.find('input[name="dtlCount' + id + '"]').val();
        var sidebarContent = $(".metaDmBehaviourDtlConfig", metaProcessWindowId).find("table tbody");

        var sideBarString = '';
        if (dtlCount != '0') {
            sideBarString += '<tr>';
            sideBarString += '<td class="left-padding" style="max-width: 150px; padding-right:15px">';
            sideBarString += 'Main Process:';
            sideBarString += '</td>';
            sideBarString += '<td colspan="3"><p class="property_page_sum_padding form-control-plaintext">' + $("#selectedObjectName", metaProcessWindowId).val() + '</p></td>';
            sideBarString += '</tr>';
        }

        var dataModelId = $("#dataModelId", metaProcessWindowId).select2('val');
        var lcBookId = $("#lcBookId", metaProcessWindowId).select2('val');
        $("input[name='dtlRowId" + processMetaDataId + "[]']", metaProcessWindowId).each(function () {
            var rowId = $(this).val();
            var extend = processMetaDataId + rowId;
            var mainLifeCycle = $('#mainLifeCycle' + extend, metaProcessWindowId).val();
            var mainProcess = $('#mainProcess' + extend, metaProcessWindowId).val();
            var doneLifeCycle = $('#doneLifeCycle' + extend, metaProcessWindowId).val();
            var doneProcess = $('#doneProcess' + extend, metaProcessWindowId).val();
            var maxRepeatCount = $('#maxRepeatCount' + extend, metaProcessWindowId).val();
            var inParamCriteria = $('#inParamCriteria' + extend, metaProcessWindowId).val();
            var outParamCriteria = $('#outParamCriteria' + extend, metaProcessWindowId).val();
            var batchNumber = $('#batchNumber' + extend, metaProcessWindowId).val();
            //console.log('mainLifeCycle=' + mainLifeCycle + ' | mainProcess=' + mainProcess + ' | doneLifeCycle=' + doneLifeCycle + ' | doneProcess=' + doneProcess + ' | maxRepeatCount=' + maxRepeatCount + ' | inParamCriteria=' + inParamCriteria + ' | outParamCriteria=' + outParamCriteria);
            sideBarString += '<tr>';
            sideBarString += '<td style="max-width: 150px;">';
            sideBarString += '<input type="hidden" id="id' + rowId + '" name="id[]" value="' + rowId + '">';
            sideBarString += '<input type="hidden" id="dtlRowId' + rowId + '" name="dtlRowId[]" value="' + rowId + '">';
            sideBarString += '<input type="hidden" name="doneLifeCycleName[]">';
            sideBarString += '<select id="doneLifeCycle' + rowId + '" name="doneLifeCycle[]" class="form-control select2" data-placeholder="Target LifeCycle" onchange="enableDtlBtn(this); setMetaDmBehaviourDtlProcess(this);"></select>';
            sideBarString += '</td>';
            sideBarString += '<td style="max-width: 150px;">';
            sideBarString += '<input type="hidden" name="doneProcessName[]">';
            sideBarString += '<select id="doneProcess' + rowId + '" name="doneProcess[]" class="form-control select2" data-placeholder="Target Process" onchange="enableDtlBtn(this);"></select>';
            sideBarString += '</td>';
            sideBarString += '<td style="min-width: 30px !important; width: 30px !important; max-width: 30px !important; text-align: right">';
            sideBarString += '<input type="text" class="control-from" name="batchNumber[]" value="' + batchNumber + '" onchange="setBatchNumber(this); enableDtlBtn(this);">';
            sideBarString += '</td>';
            sideBarString += '<td style="max-width: 80px; text-align: right">';
            sideBarString += '<button type="button" class="btn btn-sm purple-plum config" onclick="setProcessConfig(this);">...</button>';
            sideBarString += '<button type="button" class="btn btn-sm red remove" onclick="removeProcessConfig(this);"><i class="fa fa-trash"></button>';
            sideBarString += '</td>';
            sideBarString += '</tr>';
            //console.log(' mainProcess=' + mainProcess + ' mainLifeCycle=' + mainLifeCycle + ' doneProcess=' + rowId + ' doneProcess='+doneProcess);
            drawLifeCycleList(lcBookId, 'doneLifeCycle' + rowId, doneLifeCycle);
            drawProcessList(mainProcess, doneLifeCycle, 'doneProcess' + rowId, doneProcess);
        });
        sidebarContent.html(sideBarString);
    }
    setIconVal = function () {
        var selectedObjectId = $("#selectedObject", metaProcessWindowId).val();
        var selectedObject = $('#' + selectedObjectId, metaProcessWindowId);
        var statusId = $("#statusId", metaProcessWindowId).val();
        var isNonFlow = $("#isNonFlow", metaProcessWindowId).val();
        selectedObject.find('.wfIcon').attr('data-statusid', statusId);
        selectedObject.find('.wfIcon').attr('data-isnonflow', isNonFlow);
    }
    drawWorkFlowListHtml = function (metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/getChildMetaByProcess',
            data: {metaDataId: metaDataId},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: 'body',
                    animate: true
                });
            },
            success: function (data) {
                $('#metaProcessDetial').empty();
                var i = 0;
                if (data.className === 'null' || data.className == null) {
                    $("button#mainBpParamSet").show();
                } else {
                    $("button#mainBpParamSet").hide();
                }
                var bpData = data.bpData;
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

                    if (this.IS_START == 1)
                    {
                        checkClass = 'class="checked"';
                        check = 'checked="checked"';
                        var checkVal = this.META_DATA_ID;
                    }
                    if (this.BP_ORDER == null)
                    {
                        this.BP_ORDER = i;

                    }
                    $('#metaProcessDetial').find('#bpChild tbody').append(
                            '<tr>' +
                            '<td>' +
                            '<input type="hidden" class="form-control" style="max-width:50px;" name="id[]" value="' + ((this.META_PROCESS_WORKFLOW_ID != null) ? this.META_PROCESS_WORKFLOW_ID : "") + '">' +
                            '<input type="text" class="form-control" style="max-width:50px;" name="bpOrder[]" value="' + this.BP_ORDER + '">' +
                            '<input type="hidden" value="' + this.META_TYPE_CODE + '" name="metaTypeCode[]">' +
                            '<input type="hidden" value="' + this.OUTPUT_META_DATA_ID + '" name="outputMetaDataId[]">' +
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
                $.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });
    }
    viewVisualHtmlMetaData = function (metaDataId, lifeCycleId) {
        if (metaDataId != '') {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/getAdminChildMetaByProcess',
                data: {lifeCycleId: lifeCycleId, metaDataId: metaDataId, sourceId: 0},
                dataType: 'json',
                success: function (data) {
                    $('#metaProcessDetial', metaProcessWindowId).html('');

                    $('#metaProcessDetial', metaProcessWindowId).html(
                            '<div class="mb10 btn-group btn-group-devided">' +
                            '<button type="button" class="btn btn-success btn-circle btn-sm saveVisualParam "><i class="fa fa-save"></i> Хадгалах</button>' +
                            '<button type="button" class="btn purple-plum btn-circle btn-sm addVisualMetaData ml10"><i class="icon-plus3 font-size-12"></i> Процесс нэмэх</button>' +
                            '<button type="button" class="btn blue-madison btn-circle btn-sm metaDmPeriodicLimit ml10" onclick="metaDmPeriodicLimit()"><i class="fa fa-clock-o"></i> Хугацааны тохиргоо</button>' +
                            '</div>' +
                            '<div class="heigh-editor">' +
                            '<input type="hidden" value="0" name="clickBoolenTrue"><input type="hidden" value="0" name="clickBoolenFalse">' +
                            '<div class="css-editor" id="workFlowEditor"></div>' +
                            '</div>');

                    $('#workFlowEditor', metaProcessWindowId).html('');
                    $.ajax({
                        type: 'post',
                        url: 'mdtaskflow/drawProcessHtml',
                        data: {processData: data, lifeCycleId: lifeCycleId, metaDataId: metaDataId},
                        dataType: 'json',
                        success: function (data) {

                            $.each(data['object'], function (index, value) {
                                $('#workFlowEditor').append(setIcon(value));
                                workflow(value);
                                if (value['type'] != 'circle') {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdtaskflow/getBehaviourDtlList',
                                        data: {lifeCycleDtlId: value['lifeCycleDtlId']},
                                        dataType: 'json',
                                        success: function (data) {
                                            var string = '';
                                            if (data['count'] > 0) {
                                                string += '<input type="hidden" value="' + data['count'] + '" name="dtlCount' + value['processMetaDataId'] + '">';
                                                $.each(data['result'], function () {
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="id' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="id' + value['processMetaDataId'] + '[]" value="' + this.BEHAVIOUR_ID + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="dtlRowId' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="dtlRowId' + value['processMetaDataId'] + '[]" value="' + this.BEHAVIOUR_ID + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="mainLifeCycle' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="mainLifeCycle' + value['processMetaDataId'] + '[]" value="' + this.MAIN_LIFECYCLE_ID + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="mainProcess' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="mainProcess' + value['processMetaDataId'] + '[]" value="' + this.MAIN_PROCESS_ID + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="doneLifeCycle' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="doneLifeCycle' + value['processMetaDataId'] + '[]" value="' + this.DONE_LIFECYCLE_ID + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="doneProcess' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="doneProcess' + value['processMetaDataId'] + '[]" value="' + this.DONE_PROCESS_ID + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="maxRepeatCount' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="maxRepeatCount' + value['processMetaDataId'] + '[]" value="' + this.MAX_REPEAT_COUNT + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="inParamCriteria' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="inParamCriteria' + value['processMetaDataId'] + '[]" value="' + (this.IN_PARAM_CRITERIA == 'null' ? '' : this.IN_PARAM_CRITERIA) + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="outParamCriteria' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="outParamCriteria' + value['processMetaDataId'] + '[]" value="' + (this.OUT_PARAM_CRITERIA == 'null' ? '' : this.OUT_PARAM_CRITERIA) + '">';
                                                    string += '<input type="hidden" data-item="' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" id="batchNumber' + value['processMetaDataId'] + this.BEHAVIOUR_ID + '" name="batchNumber' + value['processMetaDataId'] + '[]" value="' + this.BATCH_NUMBER + '">';
                                                });
                                            } else {
                                                string += '<input type="hidden" value="0" name="dtlCount' + value['processMetaDataId'] + '">';
                                            }
                                            $("." + value["id"], metaProcessWindowId).remove();
                                            $(".metaDmBehaviourDtlConfig", metaProcessWindowId).append('<div class="' + value["id"] + '">' + string + '</div>');
                                        }
                                    });
                                }
                            });
                            jsPlumb.detachEveryConnection();
                            $.each(data['connect'], function (index, value) {

                                workflowConnectionImport(value);
                                workflow(value);
                                if (value.source === 'startObject001') {
                                    $("#" + value.target, metaProcessWindowId).removeClass("wfMenu");
                                    $("#" + value.target, metaProcessWindowId).addClass("wfMenuStartCriteria");
                                }
                            });
                            $('.wfposition').draggable({containment: '#workFlowEditor'});
                        },
                        error: function () {
                            $('#metaProcessDetial').html('');
                        }
                    }).done(function () {
                        Core.initAjax();
                    });
                }
            });
        }
    }
    callMetaParameter = function (mainBpId, doProcessId) {
        var dialogName = '#bpChildDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/getInputMetaParameterByProcess',
            data: {mainBpId: mainBpId, doProcessId: doProcessId},
            beforeSend: function () {
                Core.blockUI({
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
        }).done(function () {
            Core.initAjax();
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
                            url: 'mdtaskflow/saveMetaProcessParameter',
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
                                $(dialogName).dialog('close');
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
                        }).done(function () {
                            Core.initAjax();
                        });
                        $(dialogName).dialog('close');
                    }},
                {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function () {
                        $(dialogName).dialog('close');
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
                        wfIconHeight = 70;
                var lifeCycleDtlId = '';

                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    if ($('#workFlowEditor').find("div.wfIcon[data-processmetadataid=" + row.META_DATA_ID + "]").length === 0) {
                        var tempWidth = (parseInt($("#workFlowEditor").width()) - 120) - parseInt(wfIconAddPostionLeft);
                        if (parseInt(tempWidth) < 0) {
                            wfIconAddPostionLeft = 20;
                            wfIconAddPositionTop = wfIconAddPositionTop + 160;
                        }

                        var wfIconArray = {
                            id: row.META_DATA_ID,
                            lifeCycleDtlId: "",
                            lifeCycleId: $("#selectedLifeCycleId", metaProcessWindowId).val(),
                            title: row.META_DATA_NAME,
                            type: wfIconType,
                            class: wfIconClass,
                            positionTop: wfIconAddPositionTop,
                            positionLeft: wfIconAddPostionLeft,
                            width: wfIconWidth,
                            height: wfIconHeight,
                            metaDataCode: row.META_DATA_CODE,
                            statusId: 11001,
                            isNonFlow: 0,
                            processMetaDataId: row.META_DATA_ID
                        };
                        $('#workFlowEditor', metaProcessWindowId).append(setIcon(wfIconArray));
                        $('.metaDmBehaviourDtlConfig', metaProcessWindowId).append('<div class="' + row.META_DATA_ID + '"><input type="hidden" value="0" name="dtlCount' + row.META_DATA_ID + '"></div>');
                        workflow(wfIconArray);
                        wfIconAddPostionLeft = wfIconAddPostionLeft + 180;

                        $('.wfposition').draggable({
                            containment: '#workFlowEditor'
                        });
                    }
                }
            }
        }
    }
    businessProcess = function (lifeCycleId, sourceId) {
        var rootDivId = '#metaProcessDetial';
        if (lifeCycleId != '') {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/getChildMetaByProcess',
                data: {lifeCycleId: lifeCycleId, sourceId: sourceId},
                dataType: 'json',
                success: function (data) {
                    $(rootDivId).html('');

                    $(rootDivId).html(
                            '<div class="tabbable-line">' +
                            '<ul class="nav nav-tabs">' +
                            '<li class="nav-item"><a href="#tab-taskflow-list" data-toggle="tab" aria-expanded="true" class="nav-link active">List</a></li>' +
                            '<li class="nav-item"><a href="#tab-taskflow-visual" data-toggle="tab" aria-expanded="false" class="nav-link">Visual</a></li>' +
                            '</ul>' +
                            '<div class="tab-content">' +
                            '<div class="tab-pane active" id="tab-taskflow-list">' +
                            '<div class="heigh-editor">' +
                            '<input type="hidden" value="0" name="clickBoolenTrue"><input type="hidden" value="0" name="clickBoolenFalse">' +
                            '<div class="css-editor" id="workFlowEditor"></div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="tab-pane" id="tab-taskflow-visual">' +
                            '<div id="taskflow-list-view"></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>'
                            );
                    $('#workFlowEditor', rootDivId).html('');
                    $.ajax({
                        type: 'post',
                        url: 'mdtaskflow/drawProcessHtml',
                        data: {processData: data, lifeCycleId: lifeCycleId},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data['object'], function (index, value) {
                                $('#workFlowEditor', rootDivId).append(businessProcessSetIcon(value));
                                businessProcessWorkflow(value);
                            });
                            jsPlumb.detachEveryConnection();


                            $.each(data['connect'], function (index, value) {
                                workflowConnectionImport(value);
                                businessProcessWorkflow(value);
                            });
                        },
                        error: function () {
                            $('#workFlowEditor', rootDivId).html('');
                        }
                    }).done(function () {
                        Core.initAjax();
                    });

                    $.ajax({
                        type: 'post',
                        url: 'mdtaskflow/drawTaskFlowListHtml',
                        data: {processData: data, lifeCycleId: lifeCycleId},
                        dataType: 'json',
                        success: function (data) {
                            $('#taskflow-list-view', rootDivId).html(data);
                        },
                        error: function () {
                            $('#taskflow-list-view', rootDivId).html('');
                        }
                    }).done(function () {
                        Core.initAjax();
                    });
                }
            });
        }
    }
    businessProcess1 = function (lifeCycleId, sourceId) {
        var rootDivId = '#metaProcessDetial';
        if (lifeCycleId != '') {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/getChildMetaByProcess',
                data: {lifeCycleId: lifeCycleId, sourceId: sourceId},
                dataType: 'json',
                success: function (data) {
                    $(rootDivId).html('');

                    $(rootDivId).html(
                            '<div class="tabbable-line">' +
                            '<ul class="nav nav-tabs">' +
                            '<li class="nav-item"><a href="#tab-taskflow-list" data-toggle="tab" aria-expanded="true" class="nav-link active">List</a></li>' +
                            '<li class="nav-item"><a href="#tab-taskflow-visual" data-toggle="tab" aria-expanded="false" class="nav-link">Visual</a></li>' +
                            '</ul>' +
                            '<div class="tab-content">' +
                            '<div class="tab-pane active" id="tab-taskflow-list">' +
                            '<div id="taskflow-list-view"></div>' +
                            '</div>' +
                            '<div class="tab-pane" id="tab-taskflow-visual">' +
                            '<div class="heigh-editor">' +
                            '<input type="hidden" value="0" name="clickBoolenTrue"><input type="hidden" value="0" name="clickBoolenFalse">' +
                            '<div class="css-editor" id="workFlowEditor"></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>'
                            );
                    $('#workFlowEditor', rootDivId).html('');
                    $.ajax({
                        type: 'post',
                        url: 'mdtaskflow/drawProcessHtml',
                        data: {processData: data, lifeCycleId: lifeCycleId},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data['object'], function (index, value) {
                                $('#workFlowEditor', rootDivId).append(businessProcessSetIcon(value));
                                businessProcessWorkflow(value);
                            });
                            jsPlumb.detachEveryConnection();


                            $.each(data['connect'], function (index, value) {
                                workflowConnectionImport(value);
                                businessProcessWorkflow(value);
                            });
                        },
                        error: function () {
                            $('#workFlowEditor', rootDivId).html('');
                        }
                    }).done(function () {
                        Core.initAjax();
                    });

                    $.ajax({
                        type: 'post',
                        url: 'mdtaskflow/drawTaskFlowListHtml',
                        data: {processData: data, lifeCycleId: lifeCycleId},
                        dataType: 'json',
                        success: function (data) {
                            $('#taskflow-list-view', rootDivId).html(data);
                        },
                        error: function () {
                            $('#taskflow-list-view', rootDivId).html('');
                        }
                    }).done(function () {
                        Core.initAjax();
                    });
                }
            });
        }
    }
    businessProcessWorkflow = function (elem) {
        jsPlumb.importDefaults({
            connector: ["Flowchart", {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}], /*[Straight, Flowchart, Bezier, StateMachine]*/
            Endpoint: ["Dot", {
                    radius: 1
                }],
            anchor: ["BottomCenter", "TopCenter", "RightMiddle", "LeftMiddle"], /*, "Bottom", "Top"*/
            hoverPaintStyle: {
                strokeStyle: "#0f79c3",
                lineWidth: 2
            },
            ConnectionOverlays: [["Arrow", {
                        location: 1,
                        id: "arrow",
                        length: 14,
                        foldback: 0.8
                    }], ["Label", {
                        label: "",
                        id: "label",
                        cssClass: "aLabel"
                    }]],
        });

        windows = jsPlumb.getSelector('.wfposition');

        jsPlumb.makeSource(windows, {
            filter: ".connect",
            anchor: "Continuous",
            paintStyle: {
                fillStyle: "transparent",
                outlineColor: "#000000",
                outlineWidth: 1
            },
            hoverPaintStyle: {fillStyle: "red"},
            connector: [arrow_style, {stub: [40, 60], gap: 10, cornerRadius: 3, alwaysRespectStubs: true}],
            connectorPaintStyle: {
                strokeStyle: "0f79c3",
                lineWidth: 1,
                outlineColor: "#fff",
                outlineWidth: 2
            },
            connectorHoverPaintStyle: {
                strokeStyle: "#0f79c3",
                outlineColor: "yellow",
                outlineWidth: 1
            },
            connectorStyle: {
                strokeStyle: "#0f79c3",
                lineWidth: 2,
                outlineColor: "transparent",
                outlineWidth: 4
            }
        });
        jsPlumb.makeTarget(windows, {
            anchor: "Continuous"
        });

        var targetDropOptions = {
            tolerance: 'touch',
            hoverClass: 'dropHover',
            activeClass: 'dragActive'
        };
    }
    businessProcessSetIcon = function (elem) {
        var _left = elem['positionLeft'];
        var _top = elem['positionTop'];
        var linkTitle = elem['title'] + ' (' + elem['metaDataCode'] + ')';
        var isDisable = ' disabled ';
        var process = '';
        var viewHistory = ' viewhistory ';
        if (elem['id'] == 'startObject001') {
            linkTitle = 'Эхлэл';
            viewHistory = '';
        }
        if (elem['id'] == 'endObject001') {
            linkTitle = 'Төгсгөл';
            viewHistory = '';
        }


        if (elem['isSolved'] == 'true') {
            isDisable = ' current ';
            process = ' onclick="runProcessLifeCycle(\'' + elem['processMetaDataId'] + '\', \'' + elem['lifeCycleDtlId'] + '\', \'' + elem['lifeCycleId'] + '\', \'' + $("#dataModelId", businessProcessWindowId).val() + '\', \'' + $("#sourceId", businessProcessWindowId).val() + '\');" ';
        }

//                if (elem['logBookCount'] >= elem['maxRepeatCount'] && elem['prevProcessId'] !== "") {
//                    isDisable = ' disabled ';
//                } else {
//                    if (elem['prevLifeCycleId'] != elem['lifeCycleId'] && elem['logBookCount'] < elem['maxRepeatCount'] && elem['prevLogbookcount'] >= elem['prevMaxRepeatCount']) {
//                        isDisable = '';
//                        process = ' onclick="runProcessLifeCycle(\'' + elem['processMetaDataId'] + '\', \'' + elem['lifeCycleDtlId'] + '\', \'' + elem['lifeCycleId'] + '\', \'' + $("#dataModelId", businessProcessWindowId).val() + '\', \'' + $("#sourceId", businessProcessWindowId).val() + '\');" ';
//                    } else if (elem['prevLogbookcount'] === elem['prevMaxRepeatCount'] && elem['prevLifeCycleId'] === elem['lifeCycleId'] && elem['logBookCount'] < elem['maxRepeatCount']) {
//                        isDisable = '';
//                        process = ' onclick="runProcessLifeCycle(\'' + elem['processMetaDataId'] + '\', \'' + elem['lifeCycleDtlId'] + '\', \'' + elem['lifeCycleId'] + '\', \'' + $("#dataModelId", businessProcessWindowId).val() + '\', \'' + $("#sourceId", businessProcessWindowId).val() + '\');" ';
//                    } else if (parseInt(elem['maxRepeatCount']) > parseInt(elem['logBookCount']) && parseInt(elem['prevLogbookcount']) > parseInt(elem['prevMaxRepeatCount'])) {
//                        isDisable = ' current ';
//                        process = ' onclick="runProcessLifeCycle(\'' + elem['processMetaDataId'] + '\', \'' + elem['lifeCycleDtlId'] + '\', \'' + elem['lifeCycleId'] + '\', \'' + $("#dataModelId", businessProcessWindowId).val() + '\', \'' + $("#sourceId", businessProcessWindowId).val() + '\');" ';
//                    }
//                }

        var html = '<div ' +
                'id="' + elem['id'] + '" ' +
                'class="wfposition ' + viewHistory + elem['type'] + (elem['type'] != 'circle' ? isDisable : '') + ' ' + ' " ' +
                'style="' +
//                        'width: ' + elem['width'] + 'px; ' +
//                        'height: ' + elem['height'] + 'px; ' +
                'display: inline-block;' +
                'top: ' + _top + 'px; ' +
                'left: ' + _left + 'px; ' +
                '"' +
                (elem['type'] != 'circle' ? process : '') +
                '> ' +
                '<a href="javascript:;" title="' + linkTitle + '">' +
                '<div ' +
                'class="wfIcon ' + elem['class'] + '" ' +
                'data-type="' + elem['type'] + '" ' +
                'data-top="' + elem['positionTop'] + '" ' +
                'data-left="' + elem['positionLeft'] + '" ' +
                'data-class="' + elem['class'] + '" ' +
                'data-title="' + elem['title'] + '" ' +
                'data-lifecycledtlid="' + elem['lifeCycleDtlId'] + '" ' +
                'data-processmetadataid="' + elem['processMetaDataId'] + '" ' +
                'data-issolved="' + elem['isSolved'] + '" ' +
                'data-statusid="' + elem['statusId'] + '" ' +
                'data-isnonflow="' + elem['isNonFlow'] + '" ' +
                'style="' +
//                        'width: ' + elem['width'] + 'px; ' +
//                        'height: ' + elem['height'] + 'px; ' +
                '">' +
                '</div>';
        html += '<span class="iconText"><div class="box"><div class="ellipsis">' + elem['title'] + '</div></div></span>';
        html += '</a>' +
                '</div>';
        return html;
    }
    saveVisualMetaData = function (dataModelId, lcBookId, lifeCycleId) {
        var strBoolen = 0,
                clickBoolen = 0,
                boolenTrue = "",
                boolenFalse = "";
        var objects = [];
        $('#wfEditorHiddenValues').empty();

        $("#workFlowEditor").find(".wfposition").each(function () {

            var $elem = $(this);
            var endpoints = jsPlumb.getEndpoints($elem.attr('id'));

            var boolenObject = $elem.find("input[type=hidden]").attr("data-outputmetadataid");
            var boolenTrue = $elem.find("input[type=hidden]").attr("data-boolentrueid");
            var boolenFalse = $elem.find("input[type=hidden]").attr("data-boolenfalseid");

            objects.push({
                id: $elem.attr('id'),
                statusId: $elem.find(".wfIcon").attr('data-statusid'),
                isNonFlow: $elem.find(".wfIcon").attr('data-isnonflow'),
                lifeCycleDtlId: $elem.find(".wfIcon").attr('data-lifecycledtlid'),
                processMetaDataId: $elem.find(".wfIcon").attr('data-processmetadataid'),
                title: $elem.find(".wfIcon").attr('data-title'),
                type: $elem.find(".wfIcon").attr('data-type'),
                class: $elem.find(".wfIcon").attr('data-class'),
                positionTop: $elem.find(".wfIcon").attr('data-top'),
                positionLeft: $elem.find(".wfIcon").attr('data-left'),
                background: $elem.find(".wfIcon").attr('data-background'),
                dataModelId: $elem.find(".wfIcon").attr('data-datamodelid'),
                width: $elem.find(".wfIcon").attr('data-width'),
                height: $elem.find(".wfIcon").attr('data-height')
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
                pageSourceId: sourceId,
                pageTargetId: targetId
            });
        });
        if (objects.length > 1) {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/saveVisualMetaProcessBehaviourDtl',
                data: $('#metaProcess-form').serialize(),
                dataType: "json",
                success: function (data) {
                    //console.log('success behaviour');
                },
                error: function () {
                    console.log("Error behaviour");
                }
            });

            $.ajax({
                type: 'post',
                url: 'mdtaskflow/saveVisualMetaProcess',
                data: {objects: JSON.stringify(objects), connections: JSON.stringify(connections), dataModelId: dataModelId, lifeCycleId: lifeCycleId},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
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
                        viewVisualHtmlMetaData(dataModelId, lifeCycleId);
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
                    new PNotify({
                        title: "Error",
                        text: "Error visualMeta.",
                        type: "error",
                        sticker: false
                    });
                    console.log("Error visualMeta.");
                }
            }).done(function () {
                Core.initAjax();
                workflow();
            });
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Хадгалах боломжгүй lifeCycle байна.',
                type: 'error',
                sticker: false
            });
        }

    }
    getBusinessProcessProcessLog = function (lifeCycleDtlId, sourceId) {

        var dialogName = '#getProcessLogDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/processHistory',
            data: {lifeCycleDtlId: lifeCycleDtlId, sourceId: sourceId},
            dataType: 'json',
            success: function (data) {
                $(dialogName).html(data.Html);
                $(dialogName).dialog({
                    close: function () {
                        $(dialogName).empty().dialog('destroy');
                    },
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 650,
                    height: "auto",
                    modal: true,
                    buttons: [
                        {text: data.close_btn, class: 'btn grey-cascade btn-sm', click: function () {
                                $(dialogName).dialog('close');
                            }}
                    ]
                });
                $(dialogName).dialog('open');
            },
            error: function () {
                $('#metaProcessDetial').html('');
            }
        }).done(function () {
            Core.initAjax();
        });
    };
});
function bpContextMenu(key, elem) {
    var $elem = $(elem);
    if (key === 'delete') {
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
                        jsPlumb.remove($elem.attr('id'));
                        $(dialogName).dialog('close');
                    }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
            ]
        });
        $(dialogName).dialog('open');

    }
    if (key === 'arrowDelete') {
        $('#workFlowEditor').find('input[name="' + $elem.attr('id') + '"]').attr('data-boolentrueid', -1);
        $('#workFlowEditor').find('input[name="' + $elem.attr('id') + '"]').attr('data-boolenfalseid', -1);
        jsPlumb.select({source: $elem.attr('id')}).detach();
    }
    if (key === 'configParameter') {
        configParameter($elem);
    }
}
function warningMsgChooseMainBp() {
    var dialogName = '#warningMsgDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $(dialogName).html("Бизнес процесс сонгоогүй байна");
    $(dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Анхааруулга',
        width: '300',
        height: 'auto',
        modal: true,
        buttons: [
            {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function () {
                    $(dialogName).dialog('close');
                }}
        ]
    });
    $(dialogName).dialog('open');
}
function clearControlProperty() {
    $(".grid-row-content", metaProcessWindowId).html('');
}
function metaDmPeriodicLimit() {
    var dataModelId = $("#dataModelId", metaProcessWindowId).select2('val');
    var lcBookId = $("#lcBookId", metaProcessWindowId).select2('val');
    var lifeCycleId = $("#selectedLifeCycleId", metaProcessWindowId).val();
    var dialogName = '#metaDmPeriodicLimitDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/metaDmPeriodicLimit',
        dataType: 'json',
        data: {lcBookId: lcBookId, lifeCycleId: lifeCycleId},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $(dialogName).empty().html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1100,
                height: "auto",
                modal: true,
                close: function () {
                    $(dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}
function metaDmRepeat() {
    var dataModelId = $("#dataModelId").select2('val');
    var dialogName = '#metaDmRepeatDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/metaDmRepeat',
        dataType: 'json',
        data: {dataModelId: dataModelId},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $(dialogName).empty().html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1100,
                height: "auto",
                modal: true,
                close: function () {
                    $(dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}
function resetControlVal(elem) {
    var sidebarContent = $(".taskFlowMainConfig", metaProcessWindowId).find("table tbody");
    sidebarContent.empty();
    var currentObj = $(elem);
    currentObj.addClass('selected');
    var id = currentObj.attr('id');
    var _top = currentObj.position().top;//find('.wfIcon').attr('data-top');
    var _left = currentObj.position().left;//currentObj.find('.wfIcon').attr('data-left');
    currentObj.find('.wfIcon').attr('data-top', _top);
    currentObj.find('.wfIcon').attr('data-left', _left);
}
function metaDmEnable() {
    var dataModelId = $("#dataModelId").select2('val');
    var dialogName = '#metaDmEnableDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/metaDmEnable',
        dataType: 'json',
        data: {dataModelId: dataModelId},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $(dialogName).empty().html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1100,
                height: "auto",
                modal: true,
                close: function () {
                    $(dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}
function setMetaDmBehaviourDtlProcess(elem) {
    var _this = $(elem);
    var row = _this.parents('tr');
    var dtlRowId = row.find('input[name="dtlRowId[]"]').val();
    var doneLifeCycleId = row.find('select[name="doneLifeCycle[]"]').select2('val');
    drawProcessList($("#selectedObject", metaProcessWindowId).val(), doneLifeCycleId, 'doneProcess' + dtlRowId);
}
function addMetaDmBehaviourDtl() {
    var selectLifeCycle = $("#selectedLifeCycleId", metaProcessWindowId).val();
    var dataModelId = $("#dataModelId", metaProcessWindowId).select2('val');
    var lcBookId = $("#lcBookId", metaProcessWindowId).select2('val');
    var itemCount = parseInt($("input[name='dtlCount" + $("#selectedObject", metaProcessWindowId).val() + "']", metaProcessWindowId).val()) + 1;

    $("input[name='dtlCount" + $("#selectedObject", metaProcessWindowId).val() + "']", metaProcessWindowId).val(itemCount);
    var sidebarContent = $(".metaDmBehaviourDtlConfig", metaProcessWindowId).find("table tbody");
    var sideBarString = '';
    if (itemCount === 1) {
        sideBarString += '<tr>';
        sideBarString += '<td class="left-padding" style="max-width: 150px;">';
        sideBarString += 'Source Process:';
        sideBarString += '</td>';
        sideBarString += '<td colspan="2"><p class="property_page_sum_padding form-control-plaintext">' + $("#selectedObjectName", metaProcessWindowId).val() + '</p></td>';
        sideBarString += '</tr>';
    }

    sideBarString += '<tr>';
    sideBarString += '<td style="max-width: 150px;">';
    sideBarString += '<input type="hidden" id="id' + itemCount + '" name="id[]" value="0">';
    sideBarString += '<input type="hidden" id="dtlRowId' + itemCount + '" name="dtlRowId[]" value="' + itemCount + '">';
    sideBarString += '<input type="hidden" name="doneLifeCycleName[]">';
    sideBarString += '<select id="doneLifeCycle' + itemCount + '" name="doneLifeCycle[]" class="form-control select2" data-placeholder="Target LifeCycle" onchange="enableDtlBtn(this); setMetaDmBehaviourDtlProcess(this);"></select>';
    sideBarString += '</td>';

    drawLifeCycleList(lcBookId, 'doneLifeCycle' + itemCount, selectLifeCycle);
    sideBarString += '<td style="max-width: 150px;">';
    sideBarString += '<input type="hidden" name="doneProcessName[]">';
    sideBarString += '<select id="doneProcess' + itemCount + '" name="doneProcess[]" class="form-control select2" data-placeholder="Target Process" onchange="enableDtlBtn(this);"></select>';
    drawProcessList(dataModelId, selectLifeCycle, 'doneProcess' + itemCount, '');
    sideBarString += '</td>';
    sideBarString += '<td style="min-width: 30px !important; width: 30px !important; max-width: 30px !important; text-align: right">';
    sideBarString += '<input type="text" class="control-from" value="1" style="width:20px;" name="batchNumber[]" onchange="setBatchNumber(this); enableDtlBtn(this);">';
    sideBarString += '</td>';
    sideBarString += '<td style="max-width: 80px; min-width: 80px; text-align: right">';
    sideBarString += '<button type="button" class="btn btn-sm purple-plum config" onclick="setProcessConfig(this);" disabled="true">...</button>';
    sideBarString += '<button type="button" class="btn btn-sm red remove" onclick="removeProcessConfig(this);"><i class="fa fa-trash"></i></button>'
    sideBarString += '</td>';

    sideBarString += '</tr>';
    sidebarContent.append(sideBarString);
}
function drawProcessList(processMetaDatId, lifeCycleId, contorlName, selectId) {
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/getProcessList',
        dataType: 'json',
        data: {lifeCycleId: lifeCycleId},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            //console.log('processMetaDatId=' + processMetaDatId + ' | lifeCycleId=' + lifeCycleId + ' | contorlName=' + contorlName + ' | selectId=' + selectId);
            var _cellSelect = $('#' + contorlName, metaProcessWindowId);
            Core.initSelect2();
            _cellSelect.select2('val', '');
            $("option:gt(0)", _cellSelect).remove();
            $.each(data, function () {
                _cellSelect.append($("<option />").val(this.PROCESS_META_DATA_ID).text(this.META_DATA_NAME));
            });
            _cellSelect.select2('val', selectId);
            Core.initSelect2();
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}

function drawLifeCycleList(lcBookId, contorlName, selectId) {
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/getMetaDmLifeCycle',
        dataType: 'json',
        data: {lcBookId: lcBookId, lifeCycleId: ""},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            //console.log('lcBookId=' + lcBookId + ' | contorlName=' + contorlName + ' | selectId=' + selectId);
            var _cellSelect = $('#' + contorlName, metaProcessWindowId);
            Core.initSelect2();
            _cellSelect.select2('val', '');
            $("option:gt(0)", _cellSelect).remove();
            $.each(data, function () {
                _cellSelect.append($("<option />").val(this.LIFECYCLE_ID).text(this.LIFECYCLE_NAME));
            });
            _cellSelect.select2('val', selectId);
            Core.initSelect2();
            Core.unblockUI();
        },
        error: function () {
            alert("Error lifeCycle");
        }
    }).done(function () {
        Core.initAjax();
    });
}
function enableDtlBtn(elem) {
    var row = $(elem).parents('tr');
    if (row.find('select[name="doneLifeCycle[]"]').select2('val') > 0 && row.find('select[name="doneProcess[]"]').select2('val') > 0 && row.find('input[name="batchNumber[]"]').val() != '') {
        $('button[type="button"]').attr('disabled', false);
    } else {
        $('button.config[type="button"]').attr('disabled', true);
    }
}
function setProcessConfig(elem) {
    var row = $(elem).parents('tr');
    //console.log(row);
    var dataModelId = $('#dataModelId', metaProcessWindowId).select2('val');
    var mainLifeCycle = $('#selectedLifeCycleId', metaProcessWindowId).val();
    var mainProcess = $('#selectedObject', metaProcessWindowId).val();
    var doneLifeCycle = row.find('select[name="doneLifeCycle[]"]', metaProcessWindowId).select2('val');
    var doneProcess = row.find('select[name="doneProcess[]"]', metaProcessWindowId).select2('val');
    var batchNumber = row.find('input[name="batchNumber[]"]', metaProcessWindowId).val();
    var id = row.find('input[name="id[]"]', metaProcessWindowId).val();
    var dtlRowId = row.find('input[name="dtlRowId[]"]', metaProcessWindowId).val();
    var maxRepeatCount = $('#maxRepeatCount' + mainProcess + dtlRowId).val();
    var inParamCriteria = $('#inParamCriteria' + mainProcess + dtlRowId).val();
    var outParamCriteria = $('#outParamCriteria' + mainProcess + dtlRowId).val();
    var rowDiv = $('.' + mainProcess);

    //console.log('mainLifeCycle: ' + mainLifeCycle + ', mainProcess: ' + mainProcess + ', doneLifeCycle: ' + doneLifeCycle + ', doneProcess: ' + doneProcess + ' dtlRowId:' + dtlRowId);

    var dialogName = '#dialog-behaviour';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/metaDmBehaviour',
        dataType: 'json',
        data: {dataModelId: dataModelId, mainLifeCycle: mainLifeCycle, mainProcess: mainProcess, doneLifeCycle: doneLifeCycle, doneProcess: doneProcess, dtlRowId: dtlRowId, maxRepeatCount: maxRepeatCount, inParamCriteria: inParamCriteria, outParamCriteria: outParamCriteria, id: id, batchNumber: batchNumber},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            //console.log(data);
            $(dialogName).empty().html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 800,
                height: "auto",
                modal: true,
                close: function () {
                    $(dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green btn-sm', click: function () {
                            var mainLifeCycle = $("#mainLifeCycle", dialogName).val();
                            var mainProcess = $("#mainProcess", dialogName).val();
                            var doneLifeCycle = $("#doneLifeCycle", dialogName).val();
                            var doneProcess = $("#doneProcess", dialogName).val();
                            var maxRepeatCount = $("#maxRepeatCount", dialogName).val();
                            var batchNumber = $("#batchNumber", dialogName).val();
                            var id = Number($("#id", dialogName).val());
                            var dtlRowId = Number($("#dtlRowId", dialogName).val());
                            var dtlCount = Number($("input[name='dtlCount[" + mainProcess + "]']#dtlRowId", dialogName).val());
                            expressionEditorInParam.save();
                            var inParamCriteria = expressionEditorInParam.getValue();
                            var outParamCriteria = '';
                            if (id == '0') {
                                var string = '';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="id' + mainProcess + dtlRowId + '" name="id' + mainProcess + '[]" value="0">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="dtlRowId' + mainProcess + dtlRowId + '" name="dtlRowId' + mainProcess + '[]" value="' + dtlRowId + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="mainLifeCycle' + mainProcess + dtlRowId + '" name="mainLifeCycle' + mainProcess + '[]" value="' + mainLifeCycle + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="mainProcess' + mainProcess + dtlRowId + '"  name="mainProcess' + mainProcess + '[]" value="' + mainProcess + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="doneLifeCycle' + mainProcess + dtlRowId + '" name="doneLifeCycle' + mainProcess + '[]" value="' + doneLifeCycle + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="doneProcess' + mainProcess + dtlRowId + '" name="doneProcess' + mainProcess + '[]" value="' + doneProcess + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="maxRepeatCount' + mainProcess + dtlRowId + '" name="maxRepeatCount' + mainProcess + '[]" value="' + maxRepeatCount + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="inParamCriteria' + mainProcess + dtlRowId + '" name="inParamCriteria' + mainProcess + '[]" value="' + inParamCriteria + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="outParamCriteria' + mainProcess + dtlRowId + '" name="outParamCriteria' + mainProcess + '[]" value="' + outParamCriteria + '">';
                                string += '<input type="hidden" data-item="' + mainProcess + dtlRowId + '" id="batchNumber' + mainProcess + dtlRowId + '" name="batchNumber' + mainProcess + '[]" value="' + batchNumber + '">';
                                $('.' + mainProcess, metaProcessWindowId).append(string);
                            } else {
                                $('.' + mainProcess).each(function () {
                                    $('#id' + mainProcess + dtlRowId).val(id);
                                    $('#mainLifeCycle' + mainProcess + dtlRowId).val(mainLifeCycle);
                                    $('#mainProcess' + mainProcess + dtlRowId).val(mainProcess);
                                    $('#doneLifeCycle' + mainProcess + dtlRowId).val(doneLifeCycle);
                                    $('#doneProcess' + mainProcess + dtlRowId).val(doneProcess);
                                    $('#maxRepeatCount' + mainProcess + dtlRowId).val(maxRepeatCount);
                                    $('#inParamCriteria' + mainProcess + dtlRowId).val(inParamCriteria);
                                    $('#outParamCriteria' + mainProcess + dtlRowId).val(outParamCriteria);
                                    $('#batchNumber' + mainProcess + dtlRowId).val(batchNumber);
                                });
                            }

//                            var string = '';
//                            console.log("mainLifeCycle=" + mainLifeCycle + " mainProcess=" + mainProcess + " doneLifeCycle=" + doneLifeCycle + " doneProcess=" + doneProcess + " maxRepeatCount=" + maxRepeatCount + " inParamCriteria=" + inParamCriteria + " outParamCriteria=" + outParamCriteria + " ");

                            $(dialogName).dialog('close');
                        }},
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        expressionEditorInParam.refresh();
        Core.initAjax();
    });
}
function removeProcessConfig(elem) {
    var row = $(elem).parents('tr');
    var id = row.find('input[name="id[]"]', metaProcessWindowId).val();
    //console.log('mainLifeCycle: ' + mainLifeCycle + ', mainProcess: ' + mainProcess + ', doneLifeCycle: ' + doneLifeCycle + ', doneProcess: ' + doneProcess + ' dtlRowId:' + dtlRowId);

    var $elem = $(this);
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
                    $.ajax({
                        type: 'post',
                        url: 'mdtaskflow/removeMetaDmBehaviour',
                        dataType: 'json',
                        data: {id: id},
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function (data) {
                            if (data.status === 'success') {
                                row.remove();
                                new PNotify({
                                    title: 'Success',
                                    text: data.message,
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
                            Core.unblockUI();
                        },
                        error: function () {
                            alert("Error");
                        }
                    });
                    $(dialogName).dialog('close');
                }},
            {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                    $(dialogName).dialog('close');
                }}
        ]
    });
    $(dialogName).dialog('open');
}
function startEndConfig(elem) {
    var _this = elem;
    var processMetaDataId = _this.find('div.wfIcon', metaProcessWindowId).attr('data-processmetadataid');
    var lifeCycleId = $('#selectedLifeCycleId', metaProcessWindowId).val();
    var lcBookId = $('#lcBookId', metaProcessWindowId).select2('val');

    var dialogName = '#startEndConfigDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/startEndConfig',
        data: {processMetaDataId: processMetaDataId, lifeCycleId: lifeCycleId, lcBookId: lcBookId},
        dataType: 'json',
        success: function (data) {
            $(dialogName).html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: '1000',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: data.close_btn, class: 'btn grey-cascade btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                ]
            });
            $(dialogName).dialog('open');
        },
        error: function () {
            $('#metaProcessDetial').html('');
        }
    }).done(function () {
        Core.initAjax();
    });
}
function setBatchNumber(elem) {
    var selectedObject = $("#selectedObject", metaProcessWindowId).val();
    var _this = $(elem);
    var row = _this.parents('tr');
    var rowId = row.find("input[name='dtlRowId[]']").val();
    $("#batchNumber" + selectedObject + rowId, metaProcessWindowId).val(_this.val());
}

function configParameter(elem) {
    var object = $(elem);
    var lifeCycleDtlId = object.find('div.wfIcon').attr('data-lifecycledtlid');
    var processMetaDataId = object.find('div.wfIcon').attr('data-processmetadataid');
    var entityId = $("#dataModelId").select2('val');
    var dialogName = '#processParameterDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/inputOutputParameterConfigList',
        data: {lifeCycleDtlId: lifeCycleDtlId, processMetaDataId: processMetaDataId, entityId: entityId},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                target: 'body',
                animate: true
            });
        },
        success: function (data) {
            $(dialogName).html(data.html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: '1200',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: data.save_btn, class: 'btn blue btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdtaskflow/saveBpParamLink',
                                dataType: 'json',
                                data: $('#metaProcessParameter-form').serialize(),
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
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
                                        $(dialogName).dialog('close');
                                    } else {
                                        new PNotify({
                                            title: 'Алдаа',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
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
                    {text: data.close_btn, class: 'btn grey-cascade btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }
                    }
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
            $.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function (data) {
        Core.initAjax();
        var doneLifeCycle = $('select[name="doneLifeCycleId[]"]', dialogName);
        $.each(doneLifeCycle, function () {
            var _this = $(this);
            var row = _this.parents('tr');
            var doneProcess = row.find('select[name="doneProcessId[]"]');
            var doneIsInput = row.find('input[type="checkbox"]');
            if (_this.attr('readonly')) {
                _this.select2('readonly', true);
            }
            if (doneProcess.attr('readonly')) {
                doneProcess.select2('readonly', true);
            }
            if (doneIsInput.attr('readonly')) {
                doneIsInput.attr('disabled', true);
            }
        });
        $('table#parametersConfigTbl tbody tr[data-show="0"]').attr('style', 'display: none !important;');
    });
}

function setLcBpParamAttributes(metaDataId) {
    var $dialogName = 'dialog-lcbp-paramattributes';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/getInputOutputMetaData',
        data: {mainBpId: metaDataId},
        dataType: "json",
        success: function (dataInput) {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/setParamAttributesEditMode',
                data: {inputMetaDataId: dataInput.INPUT_META_DATA_ID, metaDataId: metaDataId},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (dataForm) {
                    $("#" + $dialogName).empty().html('<form id="lc-bp-input-params-form" method="post">' + dataForm.Html + '</form>');
                    $("#" + $dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: dataForm.Title,
                        width: 1200,
                        minWidth: 1200,
                        height: "auto",
                        modal: true,
                        buttons: [
                            {text: dataForm.save_btn, class: 'btn btn-sm green', click: function () {
                                    $("#lc-bp-input-params-form", "#" + $dialogName).ajaxSubmit({
                                        type: 'post',
                                        url: 'mdmetadata/saveBpInputParams',
                                        dataType: "json",
                                        beforeSubmit: function (formData, jqForm, options) {
                                            formData.push(
                                                {name: 'mainMetaDataId', value: metaDataId}
                                            );
                                        },
                                        beforeSend: function () {
                                            Core.blockUI({
                                                message: plang.get('msg_saving_block'),
                                                boxed: true
                                            });
                                        },
                                        success: function (data) {
                                            Core.unblockUI();
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'Error',
                                                    sticker: false
                                                });
                                            }
                                            $("#" + $dialogName).dialog('close');
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
                                }},
                            {text: dataForm.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
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
                    $("#" + $dialogName).dialog('open');
                    $("#" + $dialogName).dialogExtend("maximize");
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initNumber($("#" + $dialogName));
                Core.initSetFractionRangeInput($("#" + $dialogName));
            });
        }
    });
}
function setLcBpOutputParamAttributes(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdtaskflow/getInputOutputMetaData',
        data: {mainBpId: metaDataId},
        dataType: "json",
        success: function (dataOutput) {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/setOutputParamAttributesEditMode',
                data: {outputMetaDataId: dataOutput.OUTPUT_META_DATA_ID, metaDataId: metaDataId},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (dataForm) {
                    var $dialogName = 'dialog-lcbp-output-param-attributes';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }

                    $("#" + $dialogName).empty().html('<form id="lc-bp-output-params-form" method="post">' + dataForm.Html + '</form>');
                    $("#" + $dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: dataForm.Title,
                        width: 1200,
                        minWidth: 1200,
                        height: "auto",
                        modal: true,
                        buttons: [
                            {text: dataForm.save_btn, class: 'btn btn-sm green', click: function () {
                                    $("#lc-bp-output-params-form", "#" + $dialogName).ajaxSubmit({
                                        type: 'post',
                                        url: 'mdmetadata/saveBpOutputParams',
                                        dataType: "json",
                                        beforeSubmit: function (formData, jqForm, options) {
                                            formData.push(
                                                {name: 'mainMetaDataId', value: metaDataId}
                                            );
                                        },
                                        beforeSend: function () {
                                            Core.blockUI({
                                                message: plang.get('msg_saving_block'),
                                                boxed: true
                                            });
                                        },
                                        success: function (data) {
                                            Core.unblockUI();
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'Error',
                                                    sticker: false
                                                });
                                            }
                                            $("#" + $dialogName).dialog('close');
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
                                }},
                            {text: dataForm.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
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
                    $("#" + $dialogName).dialog('open');
                    $("#" + $dialogName).dialogExtend("maximize");
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    });
} 