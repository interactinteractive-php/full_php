var isDataMartRelationConfig = true;
var arrowStyle = 'Flowchart'; /*[Straight, Flowchart, Bezier, StateMachine]*/
var dataMartConnectConfig = {
    connector: [arrowStyle, {stub: [10, 20], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}], 
    paintStyle: {strokeStyle: "#5c96bc", lineWidth: 2, outlineColor: "#fff", outlineWidth: 2, radius: 5},
    hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 5},
    dragOptions: {cursor: 'pointer'}
};
var fieldExpComboOption = '', expressionComboOption = '', indicatorComboOption = '', 
    showTypeComboOption = '', operatorComboOption = '', colorComboOption = '';

function dataMartRelationConfig(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    
    if (typeof $appendElement == 'undefined') {
        dataMartRelationConfigDialog(elem, processMetaDataId, dataViewId, paramData);
    } else {
        dataMartRelationConfigAppend(elem, processMetaDataId, dataViewId, paramData, $appendElement);
    }
}
function dataMartRelationConfigDialog(elem, processMetaDataId, dataViewId, paramData) {
    
    PNotify.removeAll();
    var $dialogName = 'dialog-dmart-relationconfig';
    if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mddatamodel/dataMartRelationConfig',
        data: paramData, 
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('dmart_relation_title'),
                    width: $(window).width(),
                    height: $(window).height(),
                    modal: false,
                    open: function() {

                        disableScrolling();

                        $dialog.empty().append(data.html).promise().done(function() {

                            var setHeight = $(window).height() - 178;
                            var $editor = $('#datamart-editor');
                            
                            $('.heigh-editor').css({'height': setHeight, 'max-height': setHeight});
                            $editor.css({'height': setHeight - 2, 'max-height': setHeight - 2});
                            $('.dmart-selectable-list').css({'height': setHeight + 15, 'max-height': setHeight + 15});
                            $('#datamart-attributes').css({'height': setHeight + 35, 'max-height': setHeight + 35});

                            dataMartPivotSelectable();
                            setDataMartVisualObjects($editor, data.objects, data.graphJson, false);
                            setDataMartPivotFields(data.fields);
                            
                            Core.unblockUI();
                        });
                    }, 
                    close: function() {
                        enableScrolling();
                    }, 
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                            
                            saveDataMartRelationConfig(elem, $dialog);
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                
                setDataMartLookupCombo(data.comboData);
            
            } else {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
                Core.unblockUI();
            }
        }
    });
}
function dataMartRelationConfigAppend(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    $.ajax({
        type: 'post',
        url: 'mddatamodel/dataMartRelationConfig',
        data: paramData, 
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                
                $appendElement.empty().append(data.html).promise().done(function() {
                    
                    var $editor = $('#datamart-editor');
                    var setHeight = $(window).height() - $editor.offset().top - 10;

                    $('.heigh-editor').css({'height': setHeight, 'max-height': setHeight});
                    $editor.css({'height': setHeight - 2, 'max-height': setHeight - 2});
                    $('.dmart-selectable-list').css({'height': setHeight + 15, 'max-height': setHeight + 15});
                    $('#datamart-attributes').css({'height': setHeight + 35, 'max-height': setHeight + 35});

                    dataMartPivotSelectable();
                    setDataMartVisualObjects($editor, data.objects, data.graphJson, false);
                    setDataMartPivotFields(data.fields);

                    Core.unblockUI();
                });
                
                setDataMartLookupCombo(data.comboData);
            
            } else {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
                Core.unblockUI();
            }
        }
    });
}

function dataMartRelationConfigView(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    dataMartRelationConfigViewAppend(elem, processMetaDataId, dataViewId, paramData, $appendElement);
}
function dataMartRelationConfigViewAppend(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    $.ajax({
        type: 'post',
        url: 'mddatamodel/dataMartRelationConfigView',
        data: paramData, 
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                
                $appendElement.empty().append(data.html).promise().done(function() {
                    
                    var $editor = $('#datamart-editor');
                    var setHeight = $(window).height() - $editor.offset().top - 10;

                    $('.heigh-editor').css({'height': setHeight, 'max-height': setHeight});
                    $editor.css({'height': setHeight - 2, 'max-height': setHeight - 2});

                    setDataMartVisualObjects($editor, data.objects, data.graphJson, true);

                    Core.unblockUI();
                });
            
            } else {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
                Core.unblockUI();
            }
        }
    });
}

function saveDataMartRelationConfig(elem, $dialog) {
    
    Core.blockUI({message: 'Saving...', boxed: true});
                            
    var positions = [], connections = [], objects = {},  
        getConnections = jsPlumb.getConnections(), $editor = $('#datamart-editor');  

    $editor.find('.wfposition').each(function () {

        var $elem = $(this), objId = $elem.attr('id');

        positions.push({
            id: objId,
            top: $elem.css('top'),
            left: $elem.css('left')
        });
        
        objects[objId] = 1;
    });

    if (getConnections.length) {

        $.each(getConnections, function (idx, connection) {

            var sourceId = connection.sourceId, targetId = connection.targetId;
            var $linkInput = $editor.find('input[name="'+sourceId+'_'+targetId+'"]');

            if ($linkInput.length) {

                var $objectSrc = $editor.find('#' + sourceId); 
                var $objectTrg = $editor.find('#' + targetId); 
                var objectSrcDtlId = $objectSrc.attr('data-dtlid');
                var objectTrgDtlId = $objectTrg.attr('data-dtlid');

                var linkArr = ($linkInput.val()).split('_');
                var linkTargetId = linkArr['0'];
                var sourceDtlId = linkArr['1'];
                var targetDtlId = linkArr['2'];
                
                var srcLinkTargetId = linkTargetId;
                var trgLinkTargetId = linkTargetId;
                
                if (linkTargetId == '0') {
                    srcLinkTargetId = sourceId;
                    trgLinkTargetId = targetId;
                }
                
                if (sourceDtlId == 'null' || sourceDtlId == '0') {
                    sourceDtlId = '';
                }
                
                if (targetDtlId == 'null' || targetDtlId == '0') {
                    targetDtlId = '';
                }
                
                if (objectSrcDtlId == 'null') {
                    objectSrcDtlId = '';
                }
                
                if (objectTrgDtlId == 'null') {
                    objectTrgDtlId = '';
                }
                
                if (linkTargetId != '0') {
                    
                    connections.push({
                        sourceId: sourceId, 
                        targetId: trgLinkTargetId, 
                        sourceDtlId: objectSrcDtlId, 
                        targetDtlId: sourceDtlId
                    });
                }

                connections.push({
                    sourceId: srcLinkTargetId, 
                    targetId: targetId, 
                    sourceDtlId: targetDtlId, 
                    targetDtlId: objectTrgDtlId
                });
            }
            
            delete objects[sourceId];
            delete objects[targetId];
        });
    }
    
    if (Object.keys(objects).length) {
        
        for (var oId in objects) {
            
            connections.push({
                sourceId: oId, 
                targetId: '', 
                sourceDtlId: '', 
                targetDtlId: ''
            });
        }
    }

    var postData = {
        data: $('#dataMartVisualConfigForm').serialize(), 
        positions: JSON.stringify(positions), 
        connections: connections
    };

    $.ajax({
        type: 'post',
        url: 'mddatamodel/saveDataMartRelationConfig',
        data: postData, 
        dataType: 'json', 
        success: function (data) {

            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                addclass: pnotifyPosition,
                sticker: false
            });
            
            if (data.status == 'success') {
                
                if (data.dvId) {
                    
                    var $parent = $(elem).closest('.workspace-main-container');
                    
                    if ($parent.length) {
                        
                        var $dvElement = $parent.find('#object-value-list-' + data.dvId);   
                        
                        if ($dvElement.length) {
                            $dvElement.closest('.workspace-part').remove();
                        }
                        
                        var $pivotElement = $parent.find('[data-pivot-dvid="'+data.dvId+'"]'); 
                        
                        if ($pivotElement.length) {
                            $pivotElement.closest('.workspace-part').remove();
                        }
                    }
                }
                
                if (typeof $dialog !== 'undefined') {
                    $dialog.dialog('close');
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdlanguage/generateLanguageFile',
                    dataType: 'json',
                    success: function(dataSub) {
                        console.log('------ generateLanguageFile');
                        console.log(dataSub);
                    }
                });
            }

            Core.unblockUI();
        }
    });
}
function setDataMartLookupCombo(comboData) {
    var fieldExpCombo = comboData.fieldExpCombo;
    var expressionCombo = comboData.expressionCombo;
    var indicatorCombo = comboData.indicatorCombo;
    var showTypeCombo = comboData.showTypeCombo;
    var operatorCombo = comboData.operatorCombo;
    var colorCombo = comboData.colorCombo;
    
    fieldExpComboOption = '<option value="">- '+plang.get('select_btn')+' -</option>';
    for (var c in fieldExpCombo) {
        fieldExpComboOption += '<option value="'+fieldExpCombo[c]['id']+'">'+fieldExpCombo[c]['name']+'</option>';
    }

    expressionComboOption = '<option value="">- '+plang.get('select_btn')+' -</option>';
    for (var c in expressionCombo) {
        expressionComboOption += '<option value="'+expressionCombo[c]['id']+'">'+expressionCombo[c]['id']+'</option>';
    }
    
    indicatorComboOption = '<option value="">- '+plang.get('select_btn')+' -</option>';
    for (var c in indicatorCombo) {
        indicatorComboOption += '<option value="'+indicatorCombo[c]['id']+'" data-showtype="'+indicatorCombo[c]['showtype']+'">'+indicatorCombo[c]['name']+'</option>';
    }
    
    showTypeComboOption = '<option value="">- '+plang.get('select_btn')+' -</option>';
    for (var c in showTypeCombo) {
        showTypeComboOption += '<option value="'+showTypeCombo[c]['id']+'">'+showTypeCombo[c]['name']+'</option>';
    }
    
    operatorComboOption = '<option value="">- '+plang.get('select_btn')+' -</option>';
    for (var c in operatorCombo) {
        operatorComboOption += '<option value="'+operatorCombo[c]['id']+'">'+operatorCombo[c]['name']+'</option>';
    }
    
    colorComboOption = '<option value="">- '+plang.get('select_btn')+' -</option>';
    for (var c in colorCombo) {
        colorComboOption += '<option value="'+colorCombo[c]['id']+'">'+colorCombo[c]['name']+'</option>';
    }
}
function dataMartAddObject(elem) {
    dataViewSelectableGrid('nullmeta', '0', '1577172137040112', 'multi', 'nullmeta', elem, 'dataMartFillEditor');
}
function dataMartFillEditor(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    
    var $editor = $('#datamart-editor');
    var wfIconClass = 'wfIconRectangle',
        wfIconType = 'rectangle',
        wfIconWidth = 160,
        wfIconHeight = 70,
        bpOrder = 0,
        wfIconAddPositionTop = 20,
        wfIconAddPostionLeft = 20,
        isAddRow = true;
    
    for (var k in rows) {
        
        var row = rows[k];
        bpOrder = parseInt(bpOrder) + 1;
        isAddRow = true;
        
        $editor.find('.wfposition').each(function () {
            var $elem = $(this);
            if ($elem.attr('id') == row.id) {
                isAddRow = false;
            }
        });
        
        if (isAddRow) {
            var tempWidth = (parseInt($editor.width()) - 470) - parseInt(wfIconAddPostionLeft);

            if (parseInt(tempWidth) < 0) {
                wfIconAddPostionLeft = 20;
                wfIconAddPositionTop = wfIconAddPositionTop + 120;
            }
            
            var wfIconArray = {
                id: row.id,
                dtlId: row.templatedtlid,
                code: row.code, 
                title: row.name,
                type: wfIconType,
                class: wfIconClass,
                positionTop: wfIconAddPositionTop,
                positionLeft: wfIconAddPostionLeft,
                width: wfIconWidth,
                height: wfIconHeight, 
                colorCode: row.color
            };

            $editor.append(setBoxDataMartRelation(wfIconArray));
            wfIconAddPostionLeft = wfIconAddPostionLeft + 180;
            
            /*jsPlumb.detachEveryConnection();*/
            
            var $lastBox = $editor.find('.wfposition:last');
            
            setVisualDataMartRelation($lastBox);
            dataMartBoxDraggable($lastBox);
        }
    }
}

function setBoxDataMartRelation(elem) {
    var _left = elem.positionLeft;
    var _top = elem.positionTop;
    var html = [];
    
    html.push('<div id="' + elem.id + '" data-dtlid="' + elem.dtlId + '" ' +
            'class="wfposition wfdmart ' + elem.type + ' wfdmcolor-' + elem.colorCode + ' wfisreadonly-'+elem.isReadonly+'" onclick="clickBoxDataMartRelation(this);" ' +
            'style="top: ' + _top + 'px; left: ' + _left + 'px;">' +
            '<div class="wfIcon ' + elem.class + '" data-type="' + elem.type + '" ' +
            'data-top="' + elem.positionTop + '" data-left="' + elem.positionLeft + '" ' +
            'data-class="' + elem.class + '" data-title="' + elem.title + '">');
    
    html.push('<span class="iconText">');
    if (elem.type == 'rectangle') {
        html.push('<div class="bp-code">' + elem.code + '</div>');
        html.push('<div class="bp-name">' + elem.title + '</div>');
    }
    html.push('</span>');
    
    if (!elem.isReadonly) {
        html.push('<div class="dmart-object-attr">'+setDataMartObjectAttribute(elem.attributeList)+'</div><div class="connect"></div>');
    }
    
    html.push('</div></div>');
    
    return html.join('');
}

function setDataMartObjectAttribute(attributeList) {
    var html = [];
    
    if (attributeList && attributeList.length) {
        for (var k in attributeList) {
            html.push('<div data-attr-id="'+attributeList[k]['templatedtlid']+'">'+attributeList[k]['name']+'</div>');
        }
    }
    
    return html.join('');
}

function setVisualDataMartRelation(elem) {
    
    jsPlumb.importDefaults({
        ConnectionsDetachable: false,
        ReattachConnections: false,
        connector: [arrowStyle, {stub: [10, 20], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}],
        ConnectionOverlays: [["Arrow", {location: 0.99, width: 12, length: 10, foldback: 1}]],
        Endpoint: ["Dot", {radius: 6}]
    });

    jsPlumb.makeSource(elem, {
        filter: ".connect",
        anchor: "Continuous",
        isSource: true,
        isTarget: false,
        reattach: true,
        maxConnections: 99,
        connector: [arrowStyle, {stub: [10, 20], gap: 10, cornerRadius: 1, alwaysRespectStubs: true}],
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
    jsPlumb.makeTarget(elem, {
        isSource: false,
        isTarget: true,
        reattach: true,
        setDragAllowedWhenFull: true,
        dropOptions: {hoverClass: "dragHover"},
        anchor: "Continuous",
        connectorHoverPaintStyle: {
            strokeStyle: "#77ca00",
            outlineColor: "#77ca00",
            outlineWidth: 5
        },
        paintStyle: {fillStyle: "transparent"},
        hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 7}
    });
}

function dataMartBoxDraggable(elem) {
    jsPlumb.draggable(elem, {
        containment: '#datamart-editor', 
        stop: function () {
            setBoxAttrDataMartRelation($(this));
        }
    });
};

function setBoxAttrDataMartRelation(elem) {
    elem.find('.wfIcon').attr({'data-top': elem.position().top, 'data-left': elem.position().left});
}

function clickBoxDataMartRelation(elem, isLink) {
    
    var $parent = $('#datamart-attributes');
    
    if (typeof isLink == 'undefined') {
        
        var $this = $(elem), objectId = $this.attr('id');
        
        $('.wfposition.selected').removeClass('selected');
        $this.addClass('selected');
        
        if ($this.hasClass('wfisreadonly-true')) {
            return;
        }
    
    } else {
        
        var $editor = $('#datamart-editor');
        $editor.find('.wfposition.selected').removeClass('selected');
        
        var $linkInput = $editor.find('input[name="'+elem.sourceId+'_'+elem.targetId+'"]').val();
        var linkArr = $linkInput.split('_');
        var objectId = linkArr[0];
        
        if (!objectId) {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Холбоосын ID олдсонгүй!',
                type: 'info',
                addclass: pnotifyPosition,
                sticker: false
            });
            return;
        }
    }
    
    var $objectAttr = $parent.find('[data-object="'+objectId+'"]');
    
    if ($objectAttr.length) {
        
        $parent.find('[data-object]').hide();
        
        if ($objectAttr.prop('tagName') == 'TABLE') {
            $parent.show();
            $objectAttr.show();
        } else {
            $parent.hide();
        }
        
    } else {
        
        $.ajax({
            type: 'post',
            url: 'mddatamodel/getDataMartObjectAttributes',
            data: {templateId: objectId, serviceId: $parent.attr('data-service-id')}, 
            dataType: 'json', 
            success: function (data) {
                
                if (data.length) {
                    
                    var attributes = [], checkedIcon = '', addonAttrs = '';
                    
                    attributes.push('<table class="table table-hover" data-object="'+objectId+'">');
                        attributes.push('<thead>');
                            attributes.push('<tr>');
                                attributes.push('<th style="width: 34px"><a href="javascript:;" class="dmart-attr-check-all"><i class="icon-circle text-success font-size-18"></i></a></th>');
                                attributes.push('<th>Код</th>');
                                attributes.push('<th>Нэр</th>');
                                attributes.push('<th>Төрөл</th>');
                            attributes.push('</tr>');
                        attributes.push('</thead>');
                        attributes.push('<tbody>');
                        
                    for (var k in data) {
                        
                        if (data[k]['isselected'] == '1') {
                            checkedIcon = 'icon-checkmark-circle2';
                        } else {
                            checkedIcon = 'icon-circle';
                        }
                        
                        if (typeof isLink !== 'undefined') {
                            addonAttrs = ' data-objId="'+objectId+'"';
                        }
                        
                        attributes.push('<tr data-id="'+data[k]['id']+'"'+addonAttrs+'>');
                            attributes.push('<td>');
                                attributes.push('<input type="hidden" name="objectAttr['+data[k]['id']+']" value="'+data[k]['isselected']+'">');
                                attributes.push('<a href="javascript:;" class="dmart-attr-check"><i class="'+checkedIcon+' text-success font-size-18"></i></a>');
                            attributes.push('</td>');
                            attributes.push('<td>'+data[k]['code']+'</td>');
                            attributes.push('<td>'+data[k]['name']+'</td>');
                            attributes.push('<td>'+data[k]['showtype']+'</td>');
                        attributes.push('</tr>');
                    }
                    
                        attributes.push('</tbody>');
                    attributes.push('</table>');    
                    
                    if ($parent.find('[data-object="'+objectId+'"]').length == 0) {
                        $parent.append(attributes.join(''));
                    }
                    
                    $parent.find('[data-object]').hide();
                    $parent.find('[data-object="'+objectId+'"]').show();
                    $parent.show();
                    
                } else {
                    $parent.find('[data-object]').hide();
                    $parent.append('<div data-object="'+objectId+'"></div>');
                }
            }
        });
    }
}

function dataMartMultiRelationConfirm(data, info, $editor) {
    
    var def = $.Deferred();
    var $dialogName = 'dialog-dmart-relationconfirm';
    if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
    var $dialog = $('#' + $dialogName);
    var relations = '<div class="form-group pt-2">';
    
    for (var k in data) {
        relations += '<div class="form-check">'+
            '<label class="form-check-label">'+
                '<input type="radio" class="form-check-input-styled" name="dataMartObjectRelationId" value="'+data[k]['id']+'_'+data[k]['srctemplatedtlid']+'_'+data[k]['trgtemplatedtlid']+'" data-id="'+data[k]['id']+'" data-name="'+data[k]['name']+'"/> '+
                data[k]['name']+
            '</label>'+
        '</div>';
    }
    
    relations += '</div>';
    
    $dialog.html(relations);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Холбоос сонгох',
        width: 400,
        height: 'auto',
        modal: true,
        open: function() {
            Core.initUniform($dialog);
        },
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn btn-sm green', click: function() {
                
                PNotify.removeAll();
                
                var $checkedRelation = $dialog.find('input[name="dataMartObjectRelationId"]:checked');
                var checkedRelationId = $checkedRelation.val();
                
                if (checkedRelationId) {
                    
                    jsPlumb.connect({
                        source: info.sourceId, 
                        target: info.targetId, 
                        overlays: [['Label', {label: $checkedRelation.attr('data-name')}]]
                    }, dataMartConnectConfig);
                        
                    $editor.append('<input type="hidden" name="'+info.sourceId+'_'+info.targetId+'" value="'+checkedRelationId+'" data-linkid="'+$checkedRelation.attr('data-id')+'" data-name="'+$checkedRelation.attr('data-name')+'" data-sourceid="'+info.sourceId+'" data-targetid="'+info.targetId+'"/>');    
                    
                    $dialog.dialog('close');
                    def.resolve(true);
                    
                } else {
                    new PNotify({
                        title: 'Info',
                        text: 'Та холбоосыг сонгоно уу!',
                        type: 'info',
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
            }},
            {text: plang.get('no_btn'), class: 'btn btn-sm blue-hoki', click: function() { 
                $dialog.dialog('close');
                def.resolve(false);   
            }}
        ]
    });
    $dialog.dialog('open');
    
    return def.promise();
}
function dataMartNewRelationConnect(info, data, $editor) {
    var def = $.Deferred();
    
    var sourceAttrs = data.sourceAttrs;
    var targetAttrs = data.targetAttrs;
    
    if (sourceAttrs.length == 0 || targetAttrs.length == 0) {
        PNotify.removeAll();
        new PNotify({
            title: 'Info',
            text: 'Темплейтийн талбарууд ирсэнгүй!',
            type: 'info',
            addclass: pnotifyPosition,
            sticker: false
        });
        def.resolve(true);
        return def.promise();
    }
    
    var $dialogName = 'dialog-dmart-relationconnect';
    if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
    var $dialog = $('#' + $dialogName);
    var $src = $editor.find('#' + info.sourceId);
    var $trg = $editor.find('#' + info.targetId);
    var sourceRows = '', targetRows = '';
    
    for (var s in sourceAttrs) {
        sourceRows += '<tr data-id="'+sourceAttrs[s]['id']+'">'+
            '<td><a href="javascript:;" class="dmart-attrconnect-check" data-info="source"><i class="icon-circle text-success font-size-18"></i></a></td>'+    
            '<td>'+sourceAttrs[s]['code']+'</td>'+
            '<td>'+sourceAttrs[s]['name']+'</td>'+
            '<td>'+sourceAttrs[s]['showtype']+'</td>'+
        '</tr>';
    }
    
    for (var t in targetAttrs) {
        targetRows += '<tr data-id="'+targetAttrs[t]['id']+'">'+
            '<td><a href="javascript:;" class="dmart-attrconnect-check" data-info="target"><i class="icon-circle text-success font-size-18"></i></a></td>'+        
            '<td>'+targetAttrs[t]['code']+'</td>'+
            '<td>'+targetAttrs[t]['name']+'</td>'+
            '<td>'+targetAttrs[t]['showtype']+'</td>'+
        '</tr>';
    }
    
    var html = '<div class="row">'+
        '<div class="col-md-6">'+
            '<span class="font-weight-bold">' + $src.find('.wfIcon').attr('data-title') + '</span>' + 
            '<table class="table table-hover">'+
                '<thead>'+
                    '<tr>'+
                        '<th style="width: 30px"></th>'+
                        '<th>Код</th>'+
                        '<th>Нэр</th>'+
                        '<th>Төрөл</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>'+sourceRows+'</tbody>'+
            '</table>'+
            '<input type="hidden" name="connectSourceFieldId"/>'+
        '</div>'+
        '<div class="col-md-6">'+
            '<span class="font-weight-bold">' + $trg.find('.wfIcon').attr('data-title') + '</span>' + 
            '<table class="table table-hover">'+
                '<thead>'+
                    '<tr>'+
                        '<th style="width: 30px"></th>'+
                        '<th>Код</th>'+
                        '<th>Нэр</th>'+
                        '<th>Төрөл</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>'+targetRows+'</tbody>'+
            '</table>'+
            '<input type="hidden" name="connectTargetFieldId"/>'+
        '</div>'+
    '</div>';
    
    $dialog.html(html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Connect',
        width: 900,
        height: 'auto',
        modal: true,
        close: function () {
            PNotify.removeAll();
            $dialog.dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {
                
                PNotify.removeAll();
                
                var connectSourceFieldId = $('input[name="connectSourceFieldId"]').val();
                var connectTargetFieldId = $('input[name="connectTargetFieldId"]').val();
                
                if (connectSourceFieldId == '' || connectTargetFieldId == '') {
                    
                    new PNotify({
                        title: 'Info',
                        text: 'Та талбарыг сонгоно уу!',
                        type: 'info',
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    
                } else {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mddatamodel/newDataMartObjectRelation',
                        data: {
                            sourceId: info.sourceId, 
                            targetId: info.targetId, 
                            sourceFieldId: connectSourceFieldId, 
                            targetFieldId: connectTargetFieldId
                        }, 
                        dataType: 'json', 
                        beforeSend: function() {
                            Core.blockUI({message: 'Connecting...', boxed: true});
                        },
                        success: function (dataSub) {

                            if (dataSub.status == 'success') {

                                jsPlumb.connect({
                                    source: info.sourceId,
                                    target: info.targetId, 
                                    overlays: [['Label', {label: dataSub.name}]]
                                }, dataMartConnectConfig);

                                $editor.append('<input type="hidden" name="'+info.sourceId+'_'+info.targetId+'" value="'+dataSub.id+'_'+dataSub.srcDtlId+'_'+dataSub.trgDtlId+'" data-linkid="'+dataSub.id+'" data-name="'+dataSub.name+'" data-sourceid="'+info.sourceId+'" data-targetid="'+info.targetId+'"/>');
                                
                                $dialog.dialog('close');
                                def.resolve(true);
                                
                            } else {
                                new PNotify({
                                    title: dataSub.status,
                                    text: dataSub.message,
                                    type: dataSub.status,
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                            }

                            Core.unblockUI();
                        }
                    });
                }
            }},
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() { 
                $dialog.dialog('close');
                def.resolve(false);   
            }}
        ]
    });
    $dialog.dialog('open');
    
    return def.promise();
}
function dataMartNewRelationConfirm(info, $editor) {
    
    var def = $.Deferred();
    var $dialogName = 'dialog-dmart-relationconfirm';
    if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
    var $dialog = $('#' + $dialogName);
    var $src = $editor.find('#' + info.sourceId);
    var $trg = $editor.find('#' + info.targetId);
    var html = '<strong>' + $src.find('.wfIcon').attr('data-title') + ' <i class="icon-arrow-right7"></i> ' + $trg.find('.wfIcon').attr('data-title') + '</strong><br />холбохдоо итгэлтэй байна уу?';
    
    $dialog.html(html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'),
        width: 500,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn btn-sm green', click: function() {
                $dialog.dialog('close');
                def.resolve(true);
            }},
            {text: plang.get('no_btn'), class: 'btn btn-sm blue-hoki', click: function() { 
                $dialog.dialog('close');
                def.resolve(false);   
            }}
        ]
    });
    $dialog.dialog('open');
    
    return def.promise();
}

function dataMartPivotSelectable() {
    $('#dmart-selectable-alllist, #dmart-selectable-rowlist, #dmart-selectable-columnlist, #dmart-selectable-datalist').sortable({
        connectWith: '.dmart-selectable-list', 
        placeholder: 'ui-state-highlight', 
        revert: 100, 
        over: function() {
            $(this).addClass('drop-hover');
        },
        out: function() {
            $(this).removeClass('drop-hover');
        }, 
        stop: function(event, ui) {
            var $this = $(ui.item), 
                type = $this.closest('ul').attr('data-pivot-type');
        
            $this.find('input[data-field="type"]').val(type);
        }
    }).disableSelection();
}
function nullToDefVal(val, dVal) {
    return isNull(val) ? dVal : val;
}
function setDataMartVisualObjects($editor, objects, graphJson, isReadonly) {
    
    jsPlumb.detachEveryConnection();
    
    if (objects.hasOwnProperty('easervicetemplategetlist') 
        && objects.easervicetemplategetlist 
        && Object.keys(objects.easervicetemplategetlist).length) {
        
        var graphObj = [], connections = [], isSavedPosition = false, 
            wfIconClass = 'wfIconRectangle', wfIconType = 'rectangle', 
            wfIconWidth = 160, wfIconHeight = 70, 
            wfIconAddPositionTop = 20, wfIconAddPostionLeft = 40, 
            templateList = objects.easervicetemplategetlist;
    
        if (graphJson) {
            
            var graphObjs = JSON.parse(html_entity_decode(graphJson, "ENT_QUOTES"));

            for (var g in graphObjs) {
                graphObj[graphObjs[g]['id']] = {top: graphObjs[g]['top'], left: graphObjs[g]['left']};
            }
            
            isSavedPosition = true;
        }
        
        for (var k in templateList) {
            
            var row = templateList[k];
            var attributeList = row.easervicedtlgetlist;
            
            delete row.easervicedtlgetlist;
            
            if (row.typeid == '102') {
                
                connections.push(row);
                
            } else {
                
                if (!isSavedPosition || (isSavedPosition && typeof graphObj[row.id] === 'undefined')) {
                    
                    var tempWidth = (parseInt($editor.width()) - 470) - parseInt(wfIconAddPostionLeft);

                    if (parseInt(tempWidth) < 0) {
                        wfIconAddPositionTop = wfIconAddPositionTop + 120;
                        wfIconAddPostionLeft = 40;
                    }
                    
                } else {
                    wfIconAddPositionTop = (graphObj[row.id]['top']).replace('px', '');
                    wfIconAddPostionLeft = (graphObj[row.id]['left']).replace('px', '');
                }
                
                var wfIconArray = {
                    id: row.id,
                    dtlId: row.srctemplatedtlid,
                    code: row.code, 
                    title: row.name,
                    type: wfIconType,
                    class: wfIconClass,
                    positionTop: wfIconAddPositionTop,
                    positionLeft: wfIconAddPostionLeft,
                    width: wfIconWidth,
                    height: wfIconHeight, 
                    colorCode: row.color, 
                    attributeList: attributeList, 
                    isReadonly: isReadonly
                };

                $editor.append(setBoxDataMartRelation(wfIconArray));
                
                if (!isSavedPosition) {
                    wfIconAddPostionLeft = wfIconAddPostionLeft + 200;
                }

                var $lastBox = $editor.find('.wfposition:last');

                setVisualDataMartRelation($lastBox);
                
                if (!isReadonly) {
                    dataMartBoxDraggable($lastBox);
                }
            }
        }
        
        if (connections) {
            
            for (var c in connections) {
                
                var cRow = connections[c];
                
                if ($editor.find('#' + cRow.srctemplateid).length && $editor.find('#' + cRow.trgtemplateid).length) {
                    
                    jsPlumb.connect({
                        source: cRow.srctemplateid, 
                        target: cRow.trgtemplateid, 
                        overlays: [['Label', {label: cRow.name}]]
                    }, dataMartConnectConfig);

                    $editor.append('<input type="hidden" name="'+cRow.srctemplateid+'_'+cRow.trgtemplateid+'" value="'+nullToDefVal(cRow.id, '0')+'_'+nullToDefVal(cRow.srctemplatedtlid, '0')+'_'+nullToDefVal(cRow.trgtemplatedtlid, '0')+'" data-linkid="'+nullToDefVal(cRow.id, '0')+'" data-name="'+cRow.name+'" data-sourceid="'+cRow.srctemplateid+'" data-targetid="'+cRow.trgtemplateid+'"/>');
                }
            }
        }
    }
    
    return;
}

function setDataMartPivotFields(fields) {
    
    if (fields.length) {
        
        var $all = $('#dmart-selectable-alllist');
        var $row = $('#dmart-selectable-rowlist');
        var $col = $('#dmart-selectable-columnlist');
        var $val = $('#dmart-selectable-datalist');
        var allObj = [], rowObj = [], colObj = [], valObj = [];
        var inputAppend = '';
        
        for (var k in fields) {
            
            inputAppend = setDataMartPivotInputs(
                {
                    objectId: fields[k]['templateid'], 
                    id: fields[k]['templatedtlid'], 
                    labelName: fields[k]['labelname'], 
                    labelName2: fields[k]['labelname2'],
                    isFilter: fields[k]['isfilter'], 
                    dtlFieldExpId: fields[k]['servicedtlfieldexpid'], 
                    expression: fields[k]['expression'], 
                    criteria: fields[k]['criteria'], 
                    indicator: fields[k]['refindicatorid'], 
                    showtype: fields[k]['refindicatorshowtype'], 
                    colExpression: (fields[k].hasOwnProperty('columnexpression')) ? fields[k]['columnexpression'] : '', 
                    dtl: (fields[k].hasOwnProperty('design') && fields[k]['design'] && Object.keys(fields[k]['design']).length) ? JSON.stringify(fields[k]['design']) : ''
                }
            );
        
            if (fields[k]['paramarea'] == 'ROW') {
                
                var inputs = '<input type="hidden" name="pivotAttr['+fields[k]['templateid']+']['+fields[k]['templatedtlid']+'][type]" data-field="type" value="ROW"/>';
                inputs += inputAppend;
                
                rowObj.push('<li class="p-1 rounded" data-field-id="'+fields[k]['templatedtlid']+'" data-obj-id="'+fields[k]['templateid']+'"><span>'+fields[k]['name']+'</span>'+inputs+'</li>');
                
            } else if (fields[k]['paramarea'] == 'COLUMN') {
                
                var inputs = '<input type="hidden" name="pivotAttr['+fields[k]['templateid']+']['+fields[k]['templatedtlid']+'][type]" data-field="type" value="COLUMN"/>';
                inputs += inputAppend;
                
                colObj.push('<li class="p-1 rounded" data-field-id="'+fields[k]['templatedtlid']+'" data-obj-id="'+fields[k]['templateid']+'"><span>'+fields[k]['name']+'</span>'+inputs+'</li>');
                
            } else if (fields[k]['paramarea'] == 'DATA') {
                
                var inputs = '<input type="hidden" name="pivotAttr['+fields[k]['templateid']+']['+fields[k]['templatedtlid']+'][type]" data-field="type" value="DATA"/>';
                inputs += inputAppend;
                
                valObj.push('<li class="p-1 rounded" data-field-id="'+fields[k]['templatedtlid']+'" data-obj-id="'+fields[k]['templateid']+'"><span>'+fields[k]['name']+'</span>'+inputs+'</li>');
                
            } else {
                
                var inputs = '<input type="hidden" name="pivotAttr['+fields[k]['templateid']+']['+fields[k]['templatedtlid']+'][type]" data-field="type" value="FIELD"/>';
                inputs += inputAppend;
                
                allObj.push('<li class="p-1 rounded" data-field-id="'+fields[k]['templatedtlid']+'" data-obj-id="'+fields[k]['templateid']+'"><span>'+fields[k]['name']+'</span>'+inputs+'</li>');
            }
        }
        
        $all.append(allObj.join(''));
        $row.append(rowObj.join(''));
        $col.append(colObj.join(''));
        $val.append(valObj.join(''));
    }
    
    return;
}

function setDataMartPivotInputs(data) {
    
    var inputs = '<input type="hidden" name="pivotAttr['+data.objectId+']['+data.id+'][labelName]" value="'+dvFieldValueShow(data.labelName)+'"/>';
    
    inputs += '<input type="hidden" name="pivotAttr['+data.objectId+']['+data.id+'][labelName2]" value="'+dvFieldValueShow(data.labelName2)+'"/>';
    inputs += '<input type="hidden" name="pivotAttr['+data.objectId+']['+data.id+'][isFilter]" value="'+dvFieldValueShow(data.isFilter)+'"/>';
    inputs += '<input type="hidden" name="pivotAttr['+data.objectId+']['+data.id+'][dtlFieldExpId]" value="'+dvFieldValueShow(data.dtlFieldExpId)+'"/>';
    inputs += '<input type="hidden" name="pivotAttr['+data.objectId+']['+data.id+'][expression]" value="'+dvFieldValueShow(data.expression)+'"/>';
    inputs += '<input type="hidden" name="pivotAttr['+data.objectId+']['+data.id+'][indicator]" value="'+dvFieldValueShow(data.indicator)+'"/>';
    inputs += '<input type="hidden" name="pivotAttr['+data.objectId+']['+data.id+'][showtype]" value="'+dvFieldValueShow(data.showtype)+'"/>';
    inputs += '<textarea class="d-none" name="pivotAttr['+data.objectId+']['+data.id+'][criteria]">'+dvFieldValueShow(data.criteria)+'</textarea>';
    inputs += '<textarea class="d-none" name="pivotAttr['+data.objectId+']['+data.id+'][dtl]">'+dvFieldValueShow(data.dtl)+'</textarea>';
    inputs += '<textarea class="d-none" name="pivotAttr['+data.objectId+']['+data.id+'][colExpression]">'+dvFieldValueShow(data.colExpression)+'</textarea>';
    
    inputs += '<button type="button" class="btn btn-sm float-right" onclick="setDataMartPivotAttributes(this);"><i class="icon-pencil7"></i></button>';
    inputs += '<div class="clearfix"></div>';
    
    return inputs;
}

function setDataMartPivotAttributes(elem) {
    var $parent = $(elem).closest('li');
    var $dialogName = 'dialog-dmart-pivotattr';
    if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
    var $dialog = $('#' + $dialogName);
    var pivotType = $parent.closest('ul').attr('data-pivot-type');
    
    var fieldTitle = $parent.find('span').text();
    var labelName = $parent.find('input[name*="[labelName]"]').val();
    var labelName2 = $parent.find('input[name*="[labelName2]"]').val();
    var isFilter = $parent.find('input[name*="[isFilter]"]').val();
    var dtlFieldExpId = $parent.find('input[name*="[dtlFieldExpId]"]').val();
    var expression = $parent.find('input[name*="[expression]"]').val();
    var criteria = $parent.find('textarea[name*="[criteria]"]').val();
    var indicator = $parent.find('input[name*="[indicator]"]').val();
    var showtype = $parent.find('input[name*="[showtype]"]').val();
    var dtl = $parent.find('textarea[name*="[dtl]"]').val();
    var colExpression = $parent.find('textarea[name*="[colExpression]"]').val();
    
    var html = '<div class="col-md-12 xs-form">'+
        '<div class="form-group row">'+
            '<span class="d-block col-md-12 text-left font-size-15 mb10" style="border-bottom: 1px #eee solid;" data-fieldtitle="1">'+fieldTitle+'</span>'+
        '</div>'+    
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_label_name">'+plang.get('dmart_label_name')+':</label>'+
            '<div class="col-md-8">'+
                '<input type="text" id="dmart_label_name" class="form-control form-control-sm" placeholder="'+plang.get('dmart_label_name')+'" value="'+labelName+'"/>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_label_name2">'+plang.get('dmart_label_name2')+':</label>'+
            '<div class="col-md-8">'+
                '<input type="text" id="dmart_label_name2" class="form-control form-control-sm" placeholder="'+plang.get('dmart_label_name2')+'" value="'+labelName2+'"/>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_isfilter">'+plang.get('dmart_isfilter')+':</label>'+
            '<div class="col-md-8">'+
                '<input type="checkbox" id="dmart_isfilter" value="1" '+(isFilter == '1' ? 'checked' : '')+'/>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_field_exp">'+plang.get('dmart_field_exp')+':</label>'+
            '<div class="col-md-8">'+
                '<select class="form-control form-control-sm" id="dmart_field_exp">'+fieldExpComboOption.replace('value="'+dtlFieldExpId+'"', 'value="'+dtlFieldExpId+'" selected')+'</select>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_expression">'+plang.get('dmart_expression')+':</label>'+
            '<div class="col-md-8">'+
                '<select class="form-control form-control-sm" id="dmart_expression">'+expressionComboOption.replace('value="'+expression+'"', 'value="'+expression+'" selected')+'</select>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_indicator">'+plang.get('dmart_indicator')+':</label>'+
            '<div class="col-md-8">'+
                '<select class="form-control form-control-sm dmart_indicator" id="dmart_indicator">'+indicatorComboOption.replace('value="'+indicator+'"', 'value="'+indicator+'" selected')+'</select>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_showtype">'+plang.get('dmart_showtype')+':</label>'+
            '<div class="col-md-8">'+
                '<select class="form-control form-control-sm" id="dmart_showtype">'+showTypeComboOption.replace('value="'+showtype+'"', 'value="'+showtype+'" selected')+'</select>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_criteria">'+plang.get('dmart_criteria')+':</label>'+
            '<div class="col-md-8">'+
                '<textarea class="form-control form-control-sm" id="dmart_criteria" rows="4">'+criteria+'</textarea>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row">'+
            '<label class="col-form-label col-md-4 text-right pr0" for="dmart_colexpression">'+plang.get('dmart_colexpression')+':</label>'+
            '<div class="col-md-8">'+
                '<div class="input-group">'+
                    '<textarea class="form-control form-control-sm" id="dmart_colexpression" rows="1" style="min-height: 23.8px">'+colExpression+'</textarea>'+
                    '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="dMartFieldExpression(this);" title="'+plang.get('dmart_colexpression')+'"><i class="fa fa-laptop"></i></button></span>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>';
    
    if (pivotType == 'DATA') {
        
        html += '<button type="button" class="btn btn-sm btn-success mb6" onclick="addDataMartFieldDtl(this);"><i class="icon-plus3 font-size-12"></i> '+plang.get('add_btn')+'</button>';
        html += '<table class="table table-bordered table-hover">';
            html += '<thead>';
                html += '<tr>';
                    html += '<th>Operator</th>';
                    html += '<th>Min</th>';
                    html += '<th>Max</th>';
                    html += '<th>Color</th>';
                    html += '<th></th>';
                html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            if (dtl) {
                
                var dtlObj = JSON.parse(dtl);
                
                if (Object.keys(dtlObj).length) {
                    
                    for (var d in dtlObj) {
                        
                        html += setDataMartFieldDtlInputs({
                            operatorvalue: dtlObj[d]['operatorvalue'], 
                            minvalue: dtlObj[d]['minvalue'], 
                            maxvalue: dtlObj[d]['maxvalue'], 
                            color: dtlObj[d]['color']
                        });
                    }
                }
            }
            
            html += '</tbody>';
        html += '</table>';
    }
    
    $dialog.empty().append(html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('dmart_field_config'),
        width: 520,
        height: 'auto',
        modal: true,
        open: function() {
            Core.initUniform($dialog);
        }, 
        buttons: [
            {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                
                $parent.find('input[name*="[labelName]"]').val($dialog.find('#dmart_label_name').val());
                $parent.find('input[name*="[labelName2]"]').val($dialog.find('#dmart_label_name2').val());
                $parent.find('input[name*="[isFilter]"]').val($dialog.find('#dmart_isfilter').is(':checked') ? '1' : '0');
                $parent.find('input[name*="[dtlFieldExpId]"]').val($dialog.find('#dmart_field_exp').val());
                $parent.find('input[name*="[expression]"]').val($dialog.find('#dmart_expression').val());
                $parent.find('input[name*="[indicator]"]').val($dialog.find('#dmart_indicator').val());
                $parent.find('input[name*="[showtype]"]').val($dialog.find('#dmart_showtype').val());
                $parent.find('textarea[name*="[criteria]"]').val($dialog.find('#dmart_criteria').val());    
                $parent.find('textarea[name*="[colExpression]"]').val($dialog.find('#dmart_colexpression').val());    
                
                if (pivotType == 'DATA') {
                    
                    var $rows = $dialog.find('table > tbody > tr');  
                    
                    if ($rows.length) {
                        
                        var jsonObj = [];
                        
                        $rows.each(function(index) {
                            var $row = $(this);
                            jsonObj.push({
                                'operatorvalue': $row.find('[data-dtl-name="operatorvalue"]').val(),
                                'minvalue': $row.find('[data-dtl-name="minvalue"]').val(),
                                'maxvalue': $row.find('[data-dtl-name="maxvalue"]').val(),
                                'color': $row.find('[data-dtl-name="color"]').val(), 
                                'orderNumber': index
                            });
                        });
                        
                        $parent.find('textarea[name*="[dtl]"]').val(JSON.stringify(jsonObj));  
                        
                    } else {
                        $parent.find('textarea[name*="[dtl]"]').val('');  
                    }
                }
                    
                $dialog.dialog('close');
            }},
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
                
    return;
}

function setDataMartFieldDtlInputs(dtlObj) {
    
    var html = '<tr>';
        html += '<td><select class="form-control form-control-sm" data-dtl-name="operatorvalue">'+operatorComboOption.replace('value="'+dtlObj['operatorvalue']+'"', 'value="'+dtlObj['operatorvalue']+'" selected')+'</select></td>';
        html += '<td><input type="text" data-dtl-name="minvalue" class="form-control form-control-sm" value="'+dvFieldValueShow(dtlObj['minvalue'])+'"/></td>';
        html += '<td><input type="text" data-dtl-name="maxvalue" class="form-control form-control-sm" value="'+dvFieldValueShow(dtlObj['maxvalue'])+'"/></td>';
        html += '<td><select class="form-control form-control-sm" data-dtl-name="color">'+colorComboOption.replace('value="'+dtlObj['color']+'"', 'value="'+dtlObj['color']+'" selected')+'</select></td>';
        html += '<td><button type="button" class="btn btn-xs red mr0" onclick="removeDataMartFieldDtl(this);" title="'+plang.get('delete_btn')+'"><i class="fa fa-trash"></i></button></td>';
    html += '</tr>';
    
    return html;
}

function addDataMartFieldDtl(elem) {
    var $this = $(elem);
    var $tbody = $this.parent().find('table > tbody');
    
    var inputs = setDataMartFieldDtlInputs({
        operatorvalue: '', 
        minvalue: '', 
        maxvalue: '', 
        color: ''
    });
    
    $tbody.append(inputs);
}

function removeDataMartFieldDtl(elem) {
    $(elem).closest('tr').remove();
}

function dMartFieldExpression(elem) {
    
    var $dialogName = 'dialog-dmart-fieldexp';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    var $this = $(elem);
    var $form = $this.closest('.xs-form');
    var $parent = $this.closest('.input-group');
    var _expression = $parent.find('textarea').val();
    var isMetaList = true;
    var metas = [];
    var hideButtonClass = 'hide';
    
    var rowMetaId = 1;
    var rowMetaCode = '';
    var rowMetaName = $form.find('[data-fieldtitle="1"]').text();
    
    if (isMetaList) {
        
        var $rows = $('#datamartConfig-pivot').find('li[data-field-id]');
        
        if ($rows.length) {
            var i = 0;
            $rows.each(function() {

                var $thisRow = $(this);
                var metaId = $thisRow.attr('data-field-id');
                var metaName = $thisRow.find('span').text();
                
                var rowObj = {
                    metaId: metaId,
                    metaCode: '['+metaId+']',
                    metaName: metaName
                };
                metas[i] = rowObj;
                i++;
            });
        }
    }
    
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdsalary/payrollExpressionForm',
        data: {
            rowMetaId: rowMetaId, 
            rowMetaCode: rowMetaCode, 
            rowMetaName: rowMetaName, 
            expression: _expression, 
            metas: metas, 
            isMetaList: isMetaList, 
            isBracketExp: 1
        }, 
        dataType: 'json',
        beforeSend: function(){
            if (!$("link[href='middleware/assets/css/salary/expression.css?v=1']").length){
                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css?v=1"/>');
            }
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 1100,
                height: "auto",
                modal: true,
                close: function () {
                    PNotify.removeAll();
                    $dialog.dialog('destroy').remove();
                },
                buttons: [
                    {text: data.check_btn, class: 'btn red-sunglo btn-sm ' + hideButtonClass, click: function () {
                        
                        PNotify.removeAll();
                        
                        var expArea = $dialog.find('.p-exp-area');
                        var expAreaContent = $.trim(expArea.html());
                        
                        if (expAreaContent != '') {
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdsalary/validateExpression', 
                                data: {expressionContent: expAreaContent}, 
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Checking...', boxed: true});
                                },
                                success: function (checkData) {
                                    new PNotify({
                                        title: checkData.status,
                                        text: checkData.message,
                                        type: checkData.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                    Core.unblockUI();
                                }
                            });
                            
                        } else {
                            new PNotify({
                                title: 'Error',
                                text: 'Томъёо бичигдээгүй байна',
                                type: 'error',
                                addclass: pnotifyPosition,
                                sticker: false
                            });
                        } 
                    }},
                    {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        
                        PNotify.removeAll();
                        
                        var expArea = $dialog.find('.p-exp-area');
                        var expAreaContent = $.trim(expArea.html());
                        
                        if (expAreaContent != '') {
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdsalary/validateExpression', 
                                data: {expressionContent: expAreaContent, isRun: hideButtonClass}, 
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Checking...', boxed: true});
                                },
                                success: function (checkData) {
                                    
                                    if (checkData.status == 'success') {
                                        $parent.find('textarea').val($.trim(checkData.expression));
                                        $dialog.dialog('close');
                                    } else {
                                        new PNotify({
                                            title: checkData.status,
                                            text: checkData.message,
                                            type: checkData.status,
                                            addclass: pnotifyPosition,
                                            sticker: false
                                        });
                                    }
                                    Core.unblockUI();
                                }
                            });
                            
                        } else {
                            $parent.find('textarea').val('');
                            $dialog.dialog('close');
                        }
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert("Error"); Core.unblockUI(); }
    });
}

$(function() {
    
    $(document.body).on('click', '#datamart-attributes .dmart-attr-check', function() {
        var $this = $(this);
        var $row = $this.closest('tr');
        var $icon = $this.find('i');
        var $selectedObj = $('.wfposition.selected');
        
        if ($icon.hasClass('icon-circle')) {
            
            var $pivotAllFields = $('#dmart-selectable-alllist');
            var $selectedObjAttr = $selectedObj.find('.dmart-object-attr');
            
            if ($row.hasAttr('data-objId')) {
                
                var $editor = $('#datamart-editor');
                var objectId = $row.attr('data-objId');
                var shortName = $row.find('td:nth-child(3)').text();
                var name = shortName + ' ('+$editor.find('input[data-linkid="'+objectId+'"]').attr('data-name')+')';
                
            } else {
                
                var shortName = $row.find('td:nth-child(3)').text();
                var name = shortName + ' ('+$selectedObj.find('.bp-code').text()+')';
                var objectId = $selectedObj.attr('id');
            }
            
            var inputs = '<input type="hidden" name="pivotAttr['+objectId+']['+$row.attr('data-id')+'][type]" data-field="type" value="FIELD"/>';
            
            inputs += setDataMartPivotInputs(
                {
                    objectId: objectId, 
                    id: $row.attr('data-id'), 
                    labelName: '', 
                    labelName2: '',
                    isFilter: '', 
                    dtlFieldExpId: '', 
                    expression: '', 
                    criteria: '', 
                    indicator: '', 
                    showtype: '', 
                    colExpression: '', 
                    dtl: ''
                }
            );
            
            $icon.removeClass('icon-circle').addClass('icon-checkmark-circle2');
            $row.find('input').val('1');
            
            if ($selectedObjAttr.find('div[data-attr-id="'+$row.attr('data-id')+'"]').length == 0) {
                $selectedObjAttr.append('<div data-attr-id="'+$row.attr('data-id')+'">'+shortName+'</div>');
            }
            
            $pivotAllFields.append('<li class="p-1 rounded" data-field-id="'+$row.attr('data-id')+'" data-obj-id="'+objectId+'"><span>' + name + '</span>' + inputs + '</li>');
            
        } else {
            
            var $pivotFields = $('.dmart-selectable-list');
            
            $icon.removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $row.find('input').val('0');
            
            $selectedObj.find('div[data-attr-id="'+$row.attr('data-id')+'"]').remove();
            $pivotFields.find('li[data-field-id="'+$row.attr('data-id')+'"]').remove();
        }
        
        jsPlumb.repaintEverything();
    });
    
    $(document.body).on('click', '#datamart-attributes .dmart-attr-check-all', function() {
        var $this = $(this);
        var $icon = $this.find('i');
        var $tbody = $this.closest('table').find('tbody');
        var $selectedObj = $('.wfposition.selected');
        
        if ($icon.hasClass('icon-circle')) {
            
            var $pivotAllFields = $('#dmart-selectable-alllist');
            var objectId = $selectedObj.attr('id');
            var $editor = $('#datamart-editor');
            var $selectedObjAttr = $selectedObj.find('.dmart-object-attr');
            
            $tbody.find('tr').each(function() {
                
                var $row = $(this);
                
                if ($row.hasAttr('data-objId')) {
                    var objectId = $row.attr('data-objId');
                    var shortName = $row.find('td:nth-child(3)').text();
                    var name = shortName + ' ('+$editor.find('input[data-linkid="'+objectId+'"]').attr('data-name')+')';
                } else {
                    var shortName = $row.find('td:nth-child(3)').text();
                    var name = shortName + ' ('+$selectedObj.find('.bp-code').text()+')';
                }
                
                var inputs = '<input type="hidden" name="pivotAttr['+objectId+']['+$row.attr('data-id')+'][type]" data-field="type" value="FIELD"/>';
                
                inputs += setDataMartPivotInputs(
                    {
                        objectId: objectId, 
                        id: $row.attr('data-id'), 
                        labelName: '', 
                        labelName2: '', 
                        isFilter: '', 
                        dtlFieldExpId: '', 
                        expression: '', 
                        criteria: '', 
                        indicator: '', 
                        showtype: '', 
                        colExpression: '', 
                        dtl: ''
                    }
                );
                
                if ($selectedObjAttr.find('div[data-attr-id="'+$row.attr('data-id')+'"]').length == 0) {
                    $selectedObjAttr.append('<div data-attr-id="'+$row.attr('data-id')+'">'+shortName+'</div>');
                }
                
                $pivotAllFields.append('<li class="p-1 rounded" data-field-id="'+$row.attr('data-id')+'" data-obj-id="'+objectId+'"><span>' + name + '</span>' + inputs + '</li>');
            });
            
            $icon.removeClass('icon-circle').addClass('icon-checkmark-circle2');
            $tbody.find('i').removeClass('icon-circle').addClass('icon-checkmark-circle2');
            $tbody.find('input').val('1');
            
        } else {
            
            var $pivotFields = $('.dmart-selectable-list');
            
            $tbody.find('tr').each(function() {
                var $row = $(this);
                $selectedObj.find('div[data-attr-id="'+$row.attr('data-id')+'"]').remove();
                $pivotFields.find('li[data-field-id="'+$row.attr('data-id')+'"]').remove();
            });
            
            $icon.removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $tbody.find('i').removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $tbody.find('input').val('0');
        }
        
        jsPlumb.repaintEverything();
    });
    
    $(document.body).on('change', 'select.dmart_indicator', function() {
        var $this = $(this);
        
        if ($this.val() != '') {
            var showType = $this.find('option:selected').attr('data-showtype');
            if (showType != '') {
                $this.closest('.xs-form').find('select#dmart_showtype').val(showType);
            }
        }
    });
    
    $(document.body).on('click', '.dmart-attrconnect-check', function() {
        var $this = $(this);
        var $row = $this.closest('tr');
        var $icon = $this.find('i');
        var info = $this.attr('data-info');
        
        if ($icon.hasClass('icon-circle')) {
            
            var $tbody = $this.closest('tbody');
            
            $tbody.find('.icon-checkmark-circle2').removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $icon.removeClass('icon-circle').addClass('icon-checkmark-circle2');
            
            $tbody.find('.table-info').removeClass('table-info');
            $row.addClass('table-info');
            
            if (info == 'source') {
                $('input[name="connectSourceFieldId"]').val($row.attr('data-id'));
            } else {
                $('input[name="connectTargetFieldId"]').val($row.attr('data-id'));
            }
            
        } else {
            
            $icon.removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $row.removeClass('table-info');
            
            if (info == 'source') {
                $('input[name="connectSourceFieldId"]').val('');
            } else {
                $('input[name="connectTargetFieldId"]').val('');
            }
        }
    });
    
    jsPlumb.bind('beforeDrop', function(info) {
        
        var result = false;
        
        $.ajax({
            type: 'post',
            url: 'mddatamodel/getDataMartObjectRelation',
            data: {
                sourceId: info.sourceId, 
                targetId: info.targetId, 
                serviceId: $('input[data-dmart-serviceid="1"]').val()
            }, 
            dataType: 'json', 
            async: false, 
            success: function (data) {
                
                var $editor = $('#datamart-editor');
                
                if (data.status == 'already') {
                    
                    var relationLength = data.list.length;
                    
                    if (relationLength == 1) {
                        
                        jsPlumb.connect({
                            source: info.sourceId,
                            target: info.targetId, 
                            overlays: [['Label', {label: data.list[0]['name']}]]
                        }, dataMartConnectConfig);
                                
                        $editor.append('<input type="hidden" name="'+info.sourceId+'_'+info.targetId+'" value="'+data.list[0]['id']+'_'+data.list[0]['srctemplatedtlid']+'_'+data.list[0]['trgtemplatedtlid']+'" data-linkid="'+data.list[0]['id']+'" data-name="'+data.list[0]['name']+'" data-sourceid="'+info.sourceId+'" data-targetid="'+info.targetId+'"/>');
                        result = false;
                        
                    } else {
                        
                        $.when(dataMartMultiRelationConfirm(data.list, info, $editor)).then(function(status) {
                            result = false;
                        });
                    }
                    
                } else if (data.status == 'new') {
                    
                    $.when(dataMartNewRelationConnect(info, data, $editor)).then(function(status) {
                        result = false;
                    });
                    
                    /*$.when(dataMartNewRelationConfirm(info, $editor)).then(function(status) {
                            
                        if (status) {
                            
                            $.ajax({
                                type: 'post',
                                url: 'mddatamodel/newDataMartObjectRelation',
                                data: {sourceId: info.sourceId, targetId: info.targetId}, 
                                dataType: 'json', 
                                beforeSend: function() {
                                    Core.blockUI({message: 'Connecting...', boxed: true});
                                },
                                success: function (dataSub) {
                                    
                                    if (dataSub.status == 'success') {
                                        
                                        jsPlumb.connect({
                                            source: info.sourceId,
                                            target: info.targetId, 
                                            overlays: [['Label', {label: dataSub.name}]]
                                        }, dataMartConnectConfig);
                                        
                                        $editor.append('<input type="hidden" name="'+info.sourceId+'_'+info.targetId+'" value="'+dataSub.id+'_'+dataSub.srcDtlId+'_'+dataSub.trgDtlId+'" data-linkid="'+dataSub.id+'" data-name="'+dataSub.name+'" data-sourceid="'+info.sourceId+'" data-targetid="'+info.targetId+'"/>');
                                    }
                                    
                                    Core.unblockUI();
                                }
                            });
                        }

                        result = false;
                    });*/
                    
                } else {
                    console.log(data);
                }   
            }
        });
        
        return result;
    });
    
    jsPlumb.bind('click', function(connection, originalEvent) {
        
        if ($(connection.source).hasClass('wfisreadonly-false')) {
            clickBoxDataMartRelation(connection, true);
        }
    });
    
    jsPlumb.bind('contextmenu', function(connection, originalEvent) {
        
        if ($(connection.source).hasClass('wfisreadonly-false')) {
            
            $.contextMenu({
                selector: '._jsPlumb_connector',
                callback: function (key, opt) {

                    if (key == 'removeConnect') {

                        var $linkInput = $('#datamart-editor').find('input[name="'+connection.sourceId+'_'+connection.targetId+'"]');

                        if ($linkInput.length) {

                            var $attrs  = $('#datamart-attributes');
                            var $pivot  = $('#datamartConfig-pivot');
                            var thisObjId = $linkInput.attr('data-linkid');

                            $attrs.find('[data-object="'+thisObjId+'"]').remove();
                            $pivot.find('[data-obj-id="'+thisObjId+'"]').remove();
                            $linkInput.remove();
                        }

                        jsPlumb.select({source: connection.sourceId, target: connection.targetId}).detach();
                    }
                },
                items: {
                    "removeConnect": {name: "Холбоосыг устгах", icon: "trash"}
                }
            });
        }
    });
    
    $.contextMenu({
        selector: '.wfdmart:not(.wfisreadonly-true)',
        callback: function (key, opt) {
            
            if (key == 'removeObj') {
                
                var $elem = $(this);
                var dialogName = '#dialog-dmart-obj-confirm';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName);
                
                $dialog.html(plang.get('msg_delete_confirm'));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('msg_title_confirm'), 
                    width: 300,
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                                
                            var $editor = $('#datamart-editor');
                            var $attrs  = $('#datamart-attributes');
                            var $pivot  = $('#datamartConfig-pivot');
                            var objId   = $elem.attr('id');
                            
                            $editor.find('input[data-sourceid="'+objId+'"], input[data-targetid="'+objId+'"]').each(function() {
                                
                                var $thisObj = $(this);
                                var thisObjId = $thisObj.attr('data-linkid');
                                
                                $attrs.find('[data-object="'+thisObjId+'"]').remove();
                                $pivot.find('[data-obj-id="'+thisObjId+'"]').remove();
                                $thisObj.remove();
                            });
                            
                            $attrs.find('[data-object="'+objId+'"]').remove();
                            $pivot.find('[data-obj-id="'+objId+'"]').remove();
                            
                            jsPlumb.detach(objId);
                            jsPlumb.remove(objId);
                            
                            $dialog.dialog('close');
                        }},
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            }
        },
        items: {
            "removeObj": {name: plang.get('delete_btn'), icon: "trash"}
        }
    });
    
});