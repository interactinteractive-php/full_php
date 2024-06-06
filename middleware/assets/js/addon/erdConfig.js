var isErdConfig = true;
var erdArrowStyle = 'Flowchart'; /*[Straight, Flowchart, Bezier, StateMachine]*/
var erdConnectConfig = {
    connector: [erdArrowStyle, {stub: [10, 20], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}], 
    paintStyle: {strokeStyle: "#5c96bc", lineWidth: 2, outlineColor: "#fff", outlineWidth: 2, radius: 5},
    hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 5},
    dragOptions: {cursor: 'pointer'}
};

function erdConfig(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    
    if (typeof $appendElement == 'undefined') {
        erdConfigDialog(elem, processMetaDataId, dataViewId, paramData);
    } else {
        erdConfigAppend(elem, processMetaDataId, dataViewId, paramData, $appendElement);
    }
}
function erdConfigDialog(elem, processMetaDataId, dataViewId, paramData) {
    
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

                            setErdConfigVisualObjects($editor, data.objects, data.objects.graphjson, false);
                            
                            Core.unblockUI();
                        });
                    }, 
                    close: function() {
                        enableScrolling();
                    }, 
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                            
                            saveErdConfig(elem, $dialog);
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            
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
function erdConfigAppend(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    $.ajax({
        type: 'post',
        url: 'mddatamodel/erdConfig',
        data: paramData, 
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                
                var uniqId = data.uniqId;
                var isReadOnly = (data.hasOwnProperty('isReadOnly') && data.isReadOnly == '1') ? true : false;
                    
                $appendElement.empty().append(data.html).promise().done(function() {
                    
                    window['erdConfigForm_' + uniqId] = $('#erdVisualConfigForm-'+uniqId);
                    
                    var $editor = window['erdConfigForm_' + uniqId].find('.css-editor');
                    var setHeight = $(window).height() - $editor.offset().top - 10;
                    
                    window['jsPlumb_' + uniqId] = jsPlumb.getInstance({
                        Container: 'datamart-editor-' + uniqId
                    });

                    if ($appendElement.hasClass('ea-content')) {
                        setHeight = setHeight - 30;
                    } 
                    
                    window['erdConfigForm_' + uniqId].find('.heigh-editor').css({'height': setHeight, 'max-height': setHeight});
                    /*$editor.css({'height': setHeight - 2, 'max-height': setHeight - 2});*/
                    window['erdConfigForm_' + uniqId].find('#datamart-attributes').css({'height': setHeight + 40, 'max-height': setHeight + 40});
                    
                    setErdConfigVisualObjects(uniqId, $editor, data.objects, data.objects.graphjson, isReadOnly);
                    setErdConfigJsPlumbEvents(uniqId);

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

                    setErdConfigVisualObjects($editor, data.objects, data.graphJson, true);

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

function saveErdConfig(elem, $dialog) {
    
    Core.blockUI({message: 'Saving...', boxed: true});
    
    var $this = $(elem), $form = $this.closest('form');
    var uniqId = $form.attr('data-uniqid');
    
    var positions = [], connections = [], columns = [], tables = [],
        getConnections = window['jsPlumb_' + uniqId].getConnections(), 
        $editor = $form.find('.css-editor'), 
        erdId = $form.find('[name="erdId"]').val();

    $editor.find('.wfposition').each(function () {

        var $elem = $(this), objId = $elem.attr('data-id'), 
            top = $elem.css('top');
        
        if (top.indexOf('-') !== -1) {
            top = '2px';
        }    

        positions.push({
            id: objId,
            top: top,
            left: $elem.css('left')
        });
        
        tables.push({
            erdId: erdId, 
            tableId: objId, 
            color: $elem.attr('data-color'),
            description: $elem.attr('title')
        });
    });
    
    if (getConnections.length) {

        $.each(getConnections, function (idx, connection) {

            var sourceId = connection.sourceId, 
                targetId = connection.targetId;
                
            var sourceIdArr = sourceId.split('_');
            var targetIdArr = targetId.split('_');

            sourceId = sourceIdArr[1];
            targetId = targetIdArr[1];
                
            var $linkInput = $editor.find('input[name="'+sourceId+'_'+targetId+'"]');

            if ($linkInput.length) {

                var linkArr = ($linkInput.val()).split('_');
                var srcColumnId = linkArr['0'];
                var trgColumnId = linkArr['1'];
                var description = '';
                
                if (srcColumnId == '0') {
                    srcColumnId = null;
                }
                
                if (trgColumnId == '0') {
                    trgColumnId = null;
                }
                
                if ($linkInput.hasAttr('data-description')) {
                    description = $linkInput.attr('data-description');
                }

                connections.push({
                    erdId: erdId, 
                    srcTableId: sourceId, 
                    trgTableId: targetId, 
                    srcColumnId: srcColumnId, 
                    trgColumnId: trgColumnId,
                    description: description
                });
            }
        });
    }
    
    $editor.find('div[data-attr-id]').each(function () {

        var $elem = $(this), columnId = $elem.attr('data-attr-id'), 
            tableId = $elem.attr('data-tbl-id'), description = '', groupName = '', isShow = 1;
        
        if ($elem.hasAttr('data-description') && nullToDefVal($elem.attr('data-description'), '') != '') {
            description = $elem.attr('data-description');
        }
        
        if ($elem.hasAttr('data-groupname') && nullToDefVal($elem.attr('data-groupname'), '') != '') {
            groupName = $elem.attr('data-groupname');
        }
        
        if ($elem.hasClass('d-none')) {
            isShow = 0;
        }
            
        columns.push({
            erdId: erdId,
            tableId: tableId,
            columnId: columnId, 
            description: description, 
            groupName: groupName, 
            isShow: isShow
        });
    });

    var postData = {
        data: $form.serialize(), 
        positions: JSON.stringify(positions), 
        connections: connections, 
        columns: columns, 
        tables: tables
    };

    $.ajax({
        type: 'post',
        url: 'mddatamodel/saveErdConfig',
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
            
            if (data.status == 'success' && typeof $dialog !== 'undefined') {
                $dialog.dialog('close');
            }

            Core.unblockUI();
        }
    });
}
function erdConfigAddObject(elem) {
    dataViewSelectableGrid('nullmeta', '0', '1636516987758182', 'multi', 'nullmeta', elem, 'erdConfigFillEditor');
}
function erdConfigFillEditor(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    
    var $this = $(elem), $form = $this.closest('form');
    var uniqId = $form.attr('data-uniqid');
    var $editor = $form.find('.css-editor');
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
            if ($elem.attr('data-id') == row.id) {
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
                dtlId: row.id,
                code: row.code, 
                title: row.code,
                type: wfIconType,
                class: wfIconClass,
                positionTop: wfIconAddPositionTop,
                positionLeft: wfIconAddPostionLeft,
                width: wfIconWidth,
                height: wfIconHeight, 
                colorCode: 'grey' /*row.color*/
            };

            $editor.append(setBoxErdConfigRelation(uniqId, wfIconArray));
            wfIconAddPostionLeft = wfIconAddPostionLeft + 180;
            
            /*jsPlumb.detachEveryConnection();*/
            
            var $lastBox = $editor.find('.wfposition:last');
            
            setVisualErdConfigRelation(uniqId, $lastBox);
            erdConfigBoxDraggable(uniqId, $lastBox);
        }
    }
}

function setBoxErdConfigRelation(uniqId, elem) {
    var _left = elem.positionLeft;
    var _top = elem.positionTop;
    var html = [];
    var connectTag = '<div class="connect"></div>';
    
    if (Number(_top) < 0) {
        _top = 2;
    }
    
    html.push('<div id="'+uniqId+'_'+elem.id+'" data-id="'+elem.id+'" data-dtlid="' + elem.dtlId + '" ' +
            'class="wfposition wfdmart ' + elem.type + ' wfdmcolor-' + elem.colorCode + ' wfisreadonly-'+elem.isReadonly+'" onclick="clickBoxErdConfigRelation(\''+uniqId+'\', this);" ' +
            'style="top: ' + _top + 'px; left: ' + _left + 'px;" data-color="'+elem.colorCode+'" title="'+elem.description+'">' +
            '<div class="wfIcon ' + elem.class + '" data-type="' + elem.type + '" ' +
            'data-top="' + elem.positionTop + '" data-left="' + elem.positionLeft + '" ' +
            'data-class="' + elem.class + '" data-title="' + elem.title + '">');
    
    html.push('<span class="iconText">');
    if (elem.type == 'rectangle') {
        html.push('<div class="bp-code">' + elem.code + '</div>');
    }
    html.push('</span>');
    
    if (elem.isReadonly) {
        connectTag = '';
    }
    
    html.push('<div class="dmart-object-attr">'+setErdConfigObjectAttribute(elem.id, elem.attributeList)+'</div>' + connectTag);
    
    html.push('</div></div>');
    
    return html.join('');
}

function setErdConfigObjectAttribute(tblId, attributeList) {
    var html = [];
    
    if (attributeList && attributeList.length) {
        
        const groupByCategory = attributeList.reduce((acc, obj) => {
            const key = obj['groupname'];
            if (!acc[key]) {
                acc[key] = [];
            }
            acc[key].push(obj);
            return acc;
        }, {});
        
        for (var g in groupByCategory) {
            
            var attrList = groupByCategory[g];
            
            if (g != null && g != 'null') {
                html.push('<div class="erd-attr-group-name">'+g+'</div>');
            }
            
            for (var k in attrList) {
            
                var descr = nullToDefVal(attrList[k]['description'], '');
                var groupName = nullToDefVal(attrList[k]['groupname'], '');

                if (attributeList[k]['isshow'] == '1') {
                    html.push('<div data-tbl-id="'+tblId+'" data-attr-id="'+attrList[k]['columnid']+'" data-description="'+descr+'" data-groupname="'+groupName+'">'+attrList[k]['name']+'</div>');
                } else if (descr != '' || groupName != '') {
                    html.push('<div data-tbl-id="'+tblId+'" data-attr-id="'+attrList[k]['columnid']+'" class="d-none" data-description="'+descr+'" data-groupname="'+groupName+'">'+attrList[k]['name']+'</div>');
                }
            }
        }
        
        /*for (var k in attributeList) {
            
            var descr = nullToDefVal(attributeList[k]['description'], '');
            var groupName = nullToDefVal(attributeList[k]['groupname'], '');
            
            if (attributeList[k]['isshow'] == '1') {
                html.push('<div data-tbl-id="'+tblId+'" data-attr-id="'+attributeList[k]['columnid']+'" data-description="'+descr+'" data-groupname="'+groupName+'">'+attributeList[k]['name']+'</div>');
            } else if (descr != '' || groupName != '') {
                html.push('<div data-tbl-id="'+tblId+'" data-attr-id="'+attributeList[k]['columnid']+'" class="d-none" data-description="'+descr+'" data-groupname="'+groupName+'">'+attributeList[k]['name']+'</div>');
            }
        }*/
    }
    
    return html.join('');
}

function setVisualErdConfigRelation(uniqId, elem) {
    
    window['jsPlumb_' + uniqId].importDefaults({
        ConnectionsDetachable: false,
        ReattachConnections: false,
        connector: [erdArrowStyle, {stub: [10, 20], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}],
        ConnectionOverlays: [["Arrow", {location: 0.99, width: 12, length: 10, foldback: 1}]],
        Endpoint: ["Dot", {radius: 6}]
    });

    window['jsPlumb_' + uniqId].makeSource(elem, {
        filter: ".connect",
        anchor: "Continuous",
        isSource: true,
        isTarget: false,
        reattach: true,
        maxConnections: 99,
        connector: [erdArrowStyle, {stub: [10, 20], gap: 10, cornerRadius: 1, alwaysRespectStubs: true}],
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
    window['jsPlumb_' + uniqId].makeTarget(elem, {
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

function erdConfigBoxDraggable(uniqId, elem) {
    window['jsPlumb_' + uniqId].draggable(elem, {
        containment: '#erdVisualConfigForm-'+uniqId+' .css-editor', 
        stop: function () {
            setBoxAttrErdConfigRelation($(this));
        }
    });
};

function setBoxAttrErdConfigRelation(elem) {
    elem.find('.wfIcon').attr({'data-top': elem.position().top, 'data-left': elem.position().left});
}

function clickBoxErdConfigRelation(uniqId, elem, isLink) {
    
    var $parent = window['erdConfigForm_' + uniqId].find('#datamart-attributes');
    var $editor = window['erdConfigForm_' + uniqId].find('.css-editor');
    
    $editor.find('.wfposition.selected').removeClass('selected');
    
    if (typeof isLink == 'undefined') {
        
        var $this = $(elem), objectId = $this.attr('data-id');
        $this.addClass('selected');
    
    } else {
        
        var sourceIdArr = (elem.sourceId).split('_');
        var targetIdArr = (elem.targetId).split('_');

        var sourceId = sourceIdArr[1];
        var targetId = targetIdArr[1];
            
        var $linkInput = $editor.find('input[name="'+sourceId+'_'+targetId+'"]').val();
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
        
        $parent.find('[data-object], [data-object-info]').hide();
        
        if ($objectAttr.prop('tagName') == 'TABLE') {
            $parent.show();
            $objectAttr.show();
            $parent.find('[data-object-info="'+objectId+'"]').show();
        } else {
            $parent.hide();
        }
        
    } else {
        
        $.ajax({
            type: 'post',
            url: 'mddatamodel/getErdConfigObjectAttributes',
            data: {tableId: objectId, erdId: $parent.attr('data-erd-id')}, 
            dataType: 'json', 
            success: function (data) {
                
                if (data.length) {
                    
                    var attributes = [], checkedIcon = '', addonAttrs = '';
                    
                    attributes.push('<div data-object-info="'+objectId+'">');
                        attributes.push('<div class="font-weight-bold">'+$this.find('.wfIcon').attr('data-title')+'</div>');
                        attributes.push('<div>'+$this.attr('title')+'</div>');
                    attributes.push('</div>');
                    
                    attributes.push('<table class="table table-hover" data-object="'+objectId+'">');
                        attributes.push('<thead>');
                            attributes.push('<tr>');
                                attributes.push('<th style="width: 34px"><a href="javascript:;" class="dmart-attr-check-all"><i class="icon-circle text-success font-size-18"></i></a></th>');
                                attributes.push('<th>Багана</th>');
                                attributes.push('<th>Тайлбар</th>');
                            attributes.push('</tr>');
                        attributes.push('</thead>');
                        attributes.push('<tbody>');
                        
                    for (var k in data) {
                        
                        addonAttrs = ' data-groupname="'+nullToDefVal(data[k]['groupname'], '')+'"';
                        
                        if (data[k]['isselected'] == '1') {
                            checkedIcon = 'icon-checkmark-circle2';
                        } else {
                            checkedIcon = 'icon-circle';
                        }
                        
                        if (typeof isLink !== 'undefined') {
                            addonAttrs += ' data-objId="'+objectId+'"';
                        }
                        
                        attributes.push('<tr data-id="'+data[k]['id']+'"'+addonAttrs+'>');
                            attributes.push('<td>');
                                attributes.push('<input type="hidden" name="objectAttr['+data[k]['id']+']" value="'+data[k]['isselected']+'">');
                                attributes.push('<a href="javascript:;" class="dmart-attr-check"><i class="'+checkedIcon+' text-success font-size-18"></i></a>');
                            attributes.push('</td>');
                            attributes.push('<td>'+data[k]['code']+'</td>');
                            attributes.push('<td>'+nullToDefVal(data[k]['description'], '')+'</td>');
                        attributes.push('</tr>');
                    }
                    
                        attributes.push('</tbody>');
                    attributes.push('</table>');    
                    
                    if ($parent.find('[data-object="'+objectId+'"]').length == 0) {
                        $parent.append(attributes.join(''));
                    }
                    
                    $parent.find('[data-object], [data-object-info]').hide();
                    $parent.find('[data-object="'+objectId+'"], [data-object-info="'+objectId+'"]').show();
                    $parent.show();
                    
                } else {
                    $parent.find('[data-object], [data-object-info]').hide();
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
                    }, erdConnectConfig);
                        
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
                                }, erdConnectConfig);

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
function nullToDefVal(val, dVal) {
    return isNull(val) ? dVal : val;
}
function setErdConfigVisualObjects(uniqId, $editor, objects, graphJson, isReadonly) {
    
    window['jsPlumb_' + uniqId].detachEveryConnection();
    
    if (objects.hasOwnProperty('dtls') 
        && objects.dtls 
        && Object.keys(objects.dtls).length) {
        
        var graphObj = [], connections = [], isSavedPosition = false, 
            wfIconClass = 'wfIconRectangle', wfIconType = 'rectangle', 
            wfIconWidth = 160, wfIconHeight = 70, 
            wfIconAddPositionTop = 20, wfIconAddPostionLeft = 40, 
            templateList = objects.dtls;
    
        if (graphJson) {
            
            var graphObjs = JSON.parse(html_entity_decode(graphJson, "ENT_QUOTES"));

            for (var g in graphObjs) {
                graphObj[graphObjs[g]['id']] = {top: graphObjs[g]['top'], left: graphObjs[g]['left']};
            }
            
            isSavedPosition = true;
        }
        
        for (var k in templateList) {
            
            var row = templateList[k];
            var attributeList = row.columns;
            
            delete row.columns;
            
            if (row.typeid == '102') {
                
                connections.push(row);
                
            } else {
                
                if (!isSavedPosition || (isSavedPosition && typeof graphObj[row.srctableid] === 'undefined')) {
                    
                    var tempWidth = (parseInt($editor.width()) - 470) - parseInt(wfIconAddPostionLeft);

                    if (parseInt(tempWidth) < 0) {
                        wfIconAddPositionTop = wfIconAddPositionTop + 120;
                        wfIconAddPostionLeft = 40;
                    }
                    
                } else {
                    wfIconAddPositionTop = (graphObj[row.srctableid]['top']).replace('px', '');
                    wfIconAddPostionLeft = (graphObj[row.srctableid]['left']).replace('px', '');
                }
                
                var wfIconArray = {
                    id: row.srctableid,
                    dtlId: row.id,
                    code: row.name, 
                    title: row.name,
                    type: wfIconType,
                    class: wfIconClass,
                    positionTop: wfIconAddPositionTop,
                    positionLeft: wfIconAddPostionLeft,
                    width: wfIconWidth,
                    height: wfIconHeight, 
                    colorCode: (row.color) ? row.color : 'grey', 
                    description: row.description,
                    attributeList: attributeList, 
                    isReadonly: isReadonly
                };

                $editor.append(setBoxErdConfigRelation(uniqId, wfIconArray));
                
                if (!isSavedPosition) {
                    wfIconAddPostionLeft = wfIconAddPostionLeft + 200;
                }

                var $lastBox = $editor.find('.wfposition:last');

                setVisualErdConfigRelation(uniqId, $lastBox);
                
                if (!isReadonly) {
                    erdConfigBoxDraggable(uniqId, $lastBox);
                }
            }
        }
        
        if (connections) {
            
            for (var c in connections) {
                
                var cRow = connections[c];
                
                if ($editor.find('#' + uniqId + '_' + cRow.srctableid).length && $editor.find('#' + uniqId + '_' + cRow.trgtableid).length) {
                    
                    var connectionLabel = '';
                    
                    if (cRow.name) {
                        connectionLabel = cRow.name;
                    }
                    
                    if (cRow.description) {
                        if (connectionLabel != '') {
                            connectionLabel += '<br />' + cRow.description;
                        } else {
                            connectionLabel += cRow.description;
                        }
                    }
                    
                    window['jsPlumb_' + uniqId].connect({
                        source: uniqId + '_' + cRow.srctableid, 
                        target: uniqId + '_' + cRow.trgtableid, 
                        overlays: [['Label', {label: connectionLabel}]]
                    }, erdConnectConfig);

                    $editor.append('<input type="hidden" name="'+cRow.srctableid+'_'+cRow.trgtableid+'" value="'+nullToDefVal(cRow.srccolumnid, '0')+'_'+nullToDefVal(cRow.trgcolumnid, '0')+'" data-name="'+cRow.name+'" data-description="'+cRow.description+'" data-sourceid="'+cRow.srctableid+'" data-targetid="'+cRow.trgtableid+'"/>');
                }
            }
        }
    }
    
    return;
}

function setErdConfigJsPlumbEvents(uniqId) {
    
    window['jsPlumb_' + uniqId].bind('beforeDrop', function(info) {

        var $editor = window['erdConfigForm_' + uniqId].find('.css-editor');
        var sourceId = info.sourceId;
        var targetId = info.targetId;
        var sourceIdArr = sourceId.split('_');
        var targetIdArr = targetId.split('_');
        
        sourceId = sourceIdArr[1];
        targetId = targetIdArr[1];
        
        window['jsPlumb_' + uniqId].connect({
            source: info.sourceId,
            target: info.targetId
        }, erdConnectConfig);
        
        $editor.append('<input type="hidden" name="'+sourceId+'_'+targetId+'" value="0_0" data-name="newConnect" data-sourceid="'+sourceId+'" data-targetid="'+targetId+'"/>');
        
        return false;
    });
    
    window['jsPlumb_' + uniqId].bind('click', function(connection, originalEvent) {
        
        if ($(connection.source).hasClass('wfisreadonly-false')) {
            clickBoxErdConfigRelation(uniqId, connection, true);
        }
    });
    
    window['jsPlumb_' + uniqId].bind('contextmenu', function(connection, originalEvent) {
        
        if ($(connection.source).hasClass('wfisreadonly-false')) {
            
            $.contextMenu('destroy', '#erdVisualConfigForm-'+uniqId+' ._jsPlumb_connector');
            
            $.contextMenu({
                selector: '#erdVisualConfigForm-'+uniqId+' ._jsPlumb_connector',
                events: {
                    show: function(opt) {
                        var $this = $(opt.$trigger), uniqId = $this.closest('form').attr('data-uniqid');
                        if (window['isRo_' + uniqId]) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                callback: function (key, opt) {

                    if (key == 'edit') {
                        
                        var sourceIdArr = (connection.sourceId).split('_');
                        var targetIdArr = (connection.targetId).split('_');

                        var sourceId = sourceIdArr[1];
                        var targetId = targetIdArr[1];
                        
                        var $elem = $(this), $form = $elem.closest('form'), erdId = $form.find('[name="erdId"]').val();
                        
                        _processPostParam = 'defaultGetPf=1&erdId='+erdId+'&srcTableId='+sourceId+'&trgTableId='+targetId;
                
                        /*eisArcErdDV_Add_erdDtlEdit_002*/

                        callWebServiceByMeta('164647246152210', true, '', false, {callerType: 'erdConfig', isMenu: false}, undefined, undefined, undefined, function (data) {

                            if (data.status == 'success') {
                                var resultData = data.resultData, connectionLabel = '', name = '';
                    
                                if (resultData.hasOwnProperty('name') && resultData.name) {
                                    connectionLabel = resultData.name;
                                    name = connectionLabel;
                                }

                                if (resultData.hasOwnProperty('description') && resultData.description) {
                                    if (connectionLabel != '') {
                                        connectionLabel += '<br />' + resultData.description;
                                    } else {
                                        connectionLabel += resultData.description;
                                    }
                                    
                                    var $editor = window['erdConfigForm_' + uniqId].find('.css-editor');
                                    var $linkInput = $editor.find('input[name="'+sourceId+'_'+targetId+'"]');
                                    
                                    if ($linkInput.length) {
                                        $linkInput.attr('data-description', resultData.description);
                                    } else {
                                        $editor.append('<input type="hidden" name="'+sourceId+'_'+targetId+'" value="0_0" data-name="'+name+'" data-description="'+resultData.description+'" data-sourceid="'+sourceId+'" data-targetid="'+targetId+'"/>');
                                    }
                                }
                                
                                if (connectionLabel != '') {
                                    connection.addOverlay(['Label', {label: connectionLabel} ]);
                                }
                            }

                        }, undefined, undefined, undefined);
                        
                    } else if (key == 'removeConnect') {
                        
                        var sourceIdArr = (connection.sourceId).split('_');
                        var targetIdArr = (connection.targetId).split('_');

                        var sourceId = sourceIdArr[1];
                        var targetId = targetIdArr[1];
        
                        var $linkInput = window['erdConfigForm_' + uniqId].find('.css-editor').find('input[name="'+sourceId+'_'+targetId+'"]');

                        if ($linkInput.length) {

                            var $attrs = window['erdConfigForm_' + uniqId].find('#datamart-attributes');

                            $attrs.find('[data-object="'+sourceId+'"]').remove();
                            $linkInput.remove();
                        }

                        window['jsPlumb_' + uniqId].select({source: connection.sourceId, target: connection.targetId}).detach();
                    }
                },
                items: {
                    "edit": {name: plang.get('edit_btn'), icon: "edit"},
                    "removeConnect": {name: "Холбоосыг устгах", icon: "trash"}
                }
            });
        }
    });
}
function fullScreenErdConfig(elem) {
    var $this = $(elem), $parent = $this.closest('form'), 
        $editor = $parent.find('.heigh-editor'), 
        $attr = $parent.find('#datamart-attributes');    
    
    if (!$this.hasAttr('data-fullscreen')) {
        
        var oldHeight = $editor.css('height');
        var oldAttrHeight = $attr.css('height');
        var windowHeight = $(window).height();
        var editorHeight = windowHeight - 70;
        var attrHeight = windowHeight - 20;

        $this.attr({'data-fullscreen': '1', 'title': 'Restore', 'data-old-height': oldHeight, 'data-old-attr-height': oldAttrHeight}).find('i').removeClass('fa-expand').addClass('fa-compress');
        $parent.addClass('erd-fullscreen');

        $editor.css({'max-height': editorHeight, 'height': editorHeight});
        $attr.css({'max-height': attrHeight, 'height': attrHeight});

    } else {

        $this.attr('title', 'Fullscreen').find('i').removeClass('fa-compress').addClass('fa-expand');
        $parent.removeClass('erd-fullscreen');
        
        var editorHeight = $this.attr('data-old-height');
        var attrHeight = $this.attr('data-old-attr-height');

        $editor.css({'max-height': editorHeight, 'height': editorHeight});
        $attr.css({'max-height': attrHeight, 'height': attrHeight});
        
        $this.removeAttr('data-fullscreen data-old-height data-old-attr-height');
    }
}

$(function() {
    
    $(document.body).on('click', '.dmart-attr-check', function() {
        
        var $this = $(this);
        var $form = $this.closest('form');
        var uniqId = $form.attr('data-uniqid');
        
        if (window['isRo_' + uniqId]) {
            return false;
        }
        
        var $selectedObj = $form.find('.wfposition.selected');
        
        if ($selectedObj.hasClass('wfisreadonly-true')) {
            return false;
        }
        
        var $this = $(this);
        var $row = $this.closest('tr');
        var $icon = $this.find('i');
        var descr = nullToDefVal($row.find('> td:eq(2)').text(), '');
        var groupName = nullToDefVal($row.attr('data-groupname'), '');
        
        if ($icon.hasClass('icon-circle')) {
            
            var $selectedObjAttr = $selectedObj.find('.dmart-object-attr');
            var shortName = $row.find('td:nth-child(2)').text();
            var tblId = $selectedObj.attr('data-id');
            
            $icon.removeClass('icon-circle').addClass('icon-checkmark-circle2');
            $row.find('input').val('1');
            
            if ($selectedObjAttr.find('div[data-attr-id="'+$row.attr('data-id')+'"]').length == 0) {
                $selectedObjAttr.append('<div data-tbl-id="'+tblId+'" data-attr-id="'+$row.attr('data-id')+'" data-description="'+descr+'" data-groupname="'+groupName+'">'+shortName+'</div>');
            } else {
                $selectedObjAttr.find('[data-attr-id="'+$row.attr('data-id')+'"]').removeClass('d-none');
            }
            
        } else {
            
            $icon.removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $row.find('input').val('0');
            
            if (descr == '' && groupName == '') {
                $selectedObj.find('div[data-attr-id="'+$row.attr('data-id')+'"]').remove();
            } else {
                var $selectedObjAttr = $selectedObj.find('.dmart-object-attr');
                var shortName = $row.find('td:nth-child(2)').text();
                var tblId = $selectedObj.attr('data-id');
                var $attrItem = $selectedObjAttr.find('div[data-attr-id="'+$row.attr('data-id')+'"]');
                
                if ($attrItem.length == 0) {
                    $selectedObjAttr.append('<div data-tbl-id="'+tblId+'" data-attr-id="'+$row.attr('data-id')+'" data-description="'+descr+'" data-groupname="'+groupName+'" class="d-none">'+shortName+'</div>');
                } else {
                    $attrItem.addClass('d-none');
                }
            }
        }
        
        window['jsPlumb_' + uniqId].repaintEverything();
    });
    
    $(document.body).on('click', '.dmart-attr-check-all', function() {
        
        var $this = $(this);
        var $form = $this.closest('form');
        var uniqId = $form.attr('data-uniqid');
        
        if (window['isRo_' + uniqId]) {
            return false;
        }
        
        var $selectedObj = $form.find('.wfposition.selected');
        
        if ($selectedObj.hasClass('wfisreadonly-true')) {
            return false;
        }
        
        var $this = $(this);
        var $icon = $this.find('i');
        var $tbody = $this.closest('table').find('tbody');
        
        if ($icon.hasClass('icon-circle')) {
            
            var $selectedObjAttr = $selectedObj.find('.dmart-object-attr');
            var tblId = $selectedObj.attr('data-id');
            
            $tbody.find('tr').each(function() {
                
                var $row = $(this);
                var shortName = $row.find('td:nth-child(2)').text();
                var groupName = nullToDefVal($row.attr('data-groupname'), '');
                
                if ($selectedObjAttr.find('div[data-attr-id="'+$row.attr('data-id')+'"]').length == 0) {
                    $selectedObjAttr.append('<div data-tbl-id="'+tblId+'" data-attr-id="'+$row.attr('data-id')+'" data-description="'+nullToDefVal($row.find('> td:eq(2)').text(), '')+'" data-groupname="'+groupName+'">'+shortName+'</div>');
                } else {
                    $selectedObjAttr.find('[data-attr-id="'+$row.attr('data-id')+'"]').removeClass('d-none');
                }
            });
            
            $icon.removeClass('icon-circle').addClass('icon-checkmark-circle2');
            $tbody.find('i').removeClass('icon-circle').addClass('icon-checkmark-circle2');
            $tbody.find('input').val('1');
            
        } else {
            
            $tbody.find('tr').each(function() {
                var $row = $(this);
                var descr = nullToDefVal($row.find('> td:eq(2)').text(), '');
                var groupName = nullToDefVal($row.attr('data-groupname'), '');
                
                if (descr == '' && groupName == '') {
                    $selectedObj.find('div[data-attr-id="'+$row.attr('data-id')+'"]').remove();
                }
            });
            
            $icon.removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $tbody.find('i').removeClass('icon-checkmark-circle2').addClass('icon-circle');
            $tbody.find('input').val('0');
        }
        
        window['jsPlumb_' + uniqId].repaintEverything();
    });
    
    $.contextMenu({
        selector: '.wfdmart:not(.wfisreadonly-true)',
        events: {
            show: function(opt) {
                var $this = $(opt.$trigger), uniqId = $this.closest('form').attr('data-uniqid');
                if (window['isRo_' + uniqId]) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        callback: function (key, opt) {
            
            if (key == 'edit') {

                var $elem = $(this), $form = $elem.closest('form'), tableId = $elem.attr('data-id'),
                    erdId = $form.find('[name="erdId"]').val();
                
                _processPostParam = 'defaultGetPf=1&erdId='+erdId+'&tableId='+tableId;
                
                /*eisArcErdDV_Add_table_002*/
                
                callWebServiceByMeta('164647245511710', true, '', false, {callerType: 'erdConfig', isMenu: false}, undefined, undefined, undefined, function (data) {
                    
                    if (data.status == 'success') {
                        var resultData = data.resultData;
                        
                        if (resultData.hasOwnProperty('color') && resultData.color) {
                            $elem.removeClass('wfdmcolor-orange wfdmcolor-grey wfdmcolor-red wfdmcolor-green')
                                .addClass('wfdmcolor-' + resultData.color)
                                .attr('data-color', resultData.color);
                        }
                        
                        if (resultData.hasOwnProperty('description')) {
                            $elem.attr('title', resultData.description);
                        }
                    }
                    
                }, undefined, undefined, undefined);
                
            } else if (key == 'removeObj') {
                
                var $elem = $(this);
                var dialogName = '#dialog-dmart-obj-confirm';
                var $form = $elem.closest('form');
                var uniqId = $form.attr('data-uniqid');
        
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
                                
                            var $editor = window['erdConfigForm_' + uniqId].find('.css-editor');
                            var $attrs  = window['erdConfigForm_' + uniqId].find('#datamart-attributes');
                            var objId   = $elem.attr('data-id');
                            var id      = $elem.attr('id');
                            
                            $editor.find('input[data-sourceid="'+objId+'"], input[data-targetid="'+objId+'"]').each(function() {
                                var $thisObj = $(this);
                                $thisObj.remove();
                            });
                            
                            $attrs.find('[data-object="'+objId+'"]').remove();
                            
                            window['jsPlumb_' + uniqId].detach(id);
                            window['jsPlumb_' + uniqId].remove(id);
                            
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
            "edit": {name: plang.get('edit_btn'), icon: "edit"},
            "removeObj": {name: plang.get('delete_btn'), icon: "trash"}
        }
    });
    
    $.contextMenu({
        selector: 'table[data-object] > tbody > tr[data-id]',
        events: {
            show: function(opt) {
                var $this = $(opt.$trigger), uniqId = $this.closest('form').attr('data-uniqid');
                if (window['isRo_' + uniqId]) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        callback: function (key, opt) {
            
            if (key == 'edit') {

                var $elem = $(this), $form = $elem.closest('form'), columnId = $elem.attr('data-id'),
                    erdId = $form.find('[name="erdId"]').val();
                
                _processPostParam = 'defaultGetPf=1&erdId='+erdId+'&columnId='+columnId;
                
                /*eisArcErdDV_Add_Column_002*/
                
                callWebServiceByMeta('164647246205210', true, '', false, {callerType: 'erdConfig', isMenu: false}, undefined, undefined, undefined, function (data) {
                    
                    if (data.status == 'success') {
                        
                        var resultData = data.resultData;
                        var $selectedObj = $form.find('.wfposition.selected');
                        var $selectedObjAttr = $selectedObj.find('.dmart-object-attr');
                        var shortName = $elem.find('td:nth-child(2)').text();
                        var tblId = $selectedObj.attr('data-id');
                        
                        if (resultData.hasOwnProperty('description') && resultData.description) {
                            
                            var descr = nullToDefVal(resultData.description, '');
                            var $attrItem = $selectedObjAttr.find('div[data-attr-id="'+$elem.attr('data-id')+'"]');
                            
                            $elem.find('> td:eq(2)').html(descr);
                            
                            if ($elem.find('i').hasClass('icon-circle')) {
                                
                                if ($attrItem.length == 0) {
                                    $selectedObjAttr.append('<div data-tbl-id="'+tblId+'" data-attr-id="'+$elem.attr('data-id')+'" data-description="'+descr+'" class="d-none">'+shortName+'</div>');
                                } else {
                                    $attrItem.addClass('d-none').attr('data-description', descr);
                                }
                                
                            } else {
                                $attrItem.attr('data-description', descr);
                            }
                        }
                        
                        if (resultData.hasOwnProperty('groupname') && resultData.groupname) {
                            
                            var groupName = nullToDefVal(resultData.groupname, '');
                            var $attrItem = $selectedObjAttr.find('div[data-attr-id="'+$elem.attr('data-id')+'"]');
                            
                            if ($elem.find('i').hasClass('icon-circle')) {
                                
                                if ($attrItem.length == 0) {
                                    $selectedObjAttr.append('<div data-tbl-id="'+tblId+'" data-attr-id="'+$elem.attr('data-id')+'" data-groupname="'+groupName+'" class="d-none">'+shortName+'</div>');
                                } else {
                                    $attrItem.addClass('d-none').attr('data-groupname', groupName);
                                }
                                
                            } else {
                                $attrItem.attr('data-groupname', groupName);
                            }
                        }
                    }
                    
                }, undefined, undefined, undefined);
            }
        },
        items: {
            "edit": {name: plang.get('edit_btn'), icon: "edit"}
        }
    });
    
});