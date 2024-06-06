var selectedObj;

$(function(){
    
    var x = 100000000; 
    var count = Math.floor(Math.random()*x) + 1;
    var me = this;
    var conexion_selecionada = null;
    var objeto_selecionado = null;
    var windows;
    var arrow_style = "Straight";
    /*
    $('#colorControlBackground').colorpicker().on('changeColor', function(ev){
        var _color = ev.color.toHex();
        $('#metaDataBackgroundColor').val(_color);
        $('#colorControlBackground').attr('data-color', _color);
        setIconVal();
    });
    
    $('#colorControlStroke').colorpicker().on('changeColor', function(ev){
        var _color = ev.color.toHex();
        $('#metaDataBorderColor').val(_color);
        $('#colorControlStroke').attr('data-color', _color);
        setIconVal();
    });
    */
    $('#editor').on('click', 'div.wfposition', function(){
        selectedObj = $(this);
        setControlVal(selectedObj);
    });
    
    $('#metaDataTitle, #metaDataBackgroundColor, #metaDataBorderWidth, #metaDataWidth, #metaDataHeight, #metaDataPositionTop, #metaDataPositionLeft').on('change', function(){
        setIconVal();
    });
    
    $('.colorpicker.dropdown-menu').css('min-width', 125);
    $('.colorpicker-default').find('.form-control').css('padding', '3px 6px 3px 6px');
    
    $('.saveMeta').on('click', function(){
    
        var nodes = []
	$(".wfposition").each(function (idx, elem) {
	var $elem = $(elem);
	var endpoints = jsPlumb.getEndpoints($elem.attr('id'));
            var itemObj = $(this).find('#' + $elem.attr('id') + ' div.wfIcon');
            $('body').append('<div id="temp' + $elem.attr('id') + '" style="display:none">' + itemObj.context.innerHTML + '</div>');
            
                //array('id'=>2015061, 'title'=>'Cалбар хэлтэс', 'type'=>'rectangle', 'class'=>'wfIconRectangle', 'positionTop'=>'20', 'positionLeft'=>'20', 'borderColor'=>'#000', 'borderWidth'=>'5', 'background'=>'#dd00ff', 'width'=>'100', 'height'=>'80'),
		nodes.push({
			id: $elem.attr('id'),
			title: $('#temp' + $elem.attr('id') + ' > div').attr('data-title'),
                        type: $('#temp' + $elem.attr('id') + ' > div').attr('data-type'),
                        class: $('#temp' + $elem.attr('id') + ' > div').attr('data-class'),
                        positionTop: $('#temp' + $elem.attr('id') + ' > div').attr('data-top'),
                        positionLeft: $('#temp' + $elem.attr('id') + ' > div').attr('data-left'),
                        borderColor: $('#temp' + $elem.attr('id') + ' > div').attr('data-border-color'),
                        borderWidth: $('#temp' + $elem.attr('id') + ' > div').attr('data-border-width'),
                        background: $('#temp' + $elem.attr('id') + ' > div').attr('data-background'),
                        width: $('#temp' + $elem.attr('id') + ' > div').attr('data-width'),
                        height: $('#temp' + $elem.attr('id') + ' > div').attr('data-height')
		});
                $('#temp' + $elem.attr('id')).remove();
	});
        
        var connections = [];
	$.each(jsPlumb.getConnections(), function (idx, connection) {
            //console.log(connection);
            //lineWidth, strokeStyle
            connections.push({
                    connectionId: connection.id,
                    pageSourceId: connection.sourceId,
                    pageTargetId: connection.targetId,
                    strokeStyle: connection._jsPlumb.paintStyle['strokeStyle'],
                    lineWidth: connection._jsPlumb.paintStyle['lineWidth']
            });
	});

        $.ajax({
            type: 'post',
            url: 'wfbuilder/saveProcess',
            data: {PROCESS:$('#editor').html()}, 
            dataType: 'json',
            beforeSend:function(){
                Metronic.blockUI({
                    target: 'body',
                    animate: true
                });
            },
            success:function(data){
                $.unblockUI();
                if (data.status === 'success') {
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success',
                        sticker: false
                    });
                }
            },
            error:function(){
                alert("Error");
            }
        }).done(function(){
            Metronic.initAjax();
        });
    });
    $('#META_DATA_ID').on('change', function(){
        $.ajax({
            type: 'post',
            url: 'wfbuilder/getProcessList',
            data: {PROCESS_ID:'5'}, 
            dataType: 'json',
            beforeSend:function(){
                Metronic.blockUI({
                    target: 'body',
                    animate: true
                });
            },
            success:function(data){
                $('#editor').empty();
                $.each(data['object'], function( index, value ){
                    $('#editor').append(setIcon(value));
                });
                $.each(data['connect'], function( index, value ){
                    workflowConnectionImport(value);
                });
                
                workflow();
                
                $('.wfposition').draggable({
                    containment: '#editor',
                    start: function() {
                        selectedObj = $(this);
                        setControlVal(selectedObj);
                    },
                    stop: function() {
                        selectedObj = $(this);
                        setControlVal(selectedObj);
                    }
                });
                $.unblockUI();
            },
            error:function(){
                alert("Error");
            }
        }).done(function(){
            Metronic.initAjax();
        });
    });

    $(".wfIcon").draggable({
        appendTo: "body",
        helper: function() {
            return $("<div id='editor'></div>").append( $(this).clone() );
        }
    });
    $('#editor').droppable({
        accept: "#editor",
        drop: function(event,ui){
            count++;
            workflow();
            var input = setIcon($(ui.draggable).attr('data-type')) + '<span class="iconText">Шинэ</span><div class="connect"></div>';
            var top = parseInt(ui.position['top'], 10) - 250;
            var left = parseInt(ui.position['left'], 10) - 560;
            var style = 'position:absolute; top:' + top + 'px; left:' + left + 'px; width: ' + $(ui.draggable).attr('data-width') + 'px; height: ' + $(ui.draggable).attr('data-height') + 'px; ';
            
            $('<div \n\
                    data-type="' + $(ui.draggable).attr('data-type') + '" \n\
                    data-width="' + $(ui.draggable).attr('data-width') + '" \n\
                    data-height="' + $(ui.draggable).attr('data-height') + '" \n\
                    data-border-width="' + $(ui.draggable).attr('data-border-width') + '" \n\
                    data-border-color="' + $(ui.draggable).attr('data-border-color') + '" \n\
                    data-background="' + $(ui.draggable).attr('data-background') + '" \n\
                    data-top="' + top + '" \n\
                    data-left="' + left + '"  \n\
                    style="' + style + '" \n\
                    id="' + count + '" \n\
                    onclick="genCall(this)" \n\
                    ></div>').append(input).appendTo(this);
            workflow();
        }
    });

    $.contextMenu({
        selector: '._jsPlumb_connector',
        callback: function(key, opt) {
            if (key === 'delete') {
                removeConector($(this));
            }
        },
        items: {
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
    $.contextMenu({
        selector: '.wfposition',
        callback: function(key, opt) {
            if (key === 'edit') {
                console.log('ok delete');
            }
        },
        items: {
            "edit": {name: "Засах", icon: "trash"}
        }
    });

    removeConector = function(elem){
        
    }
    workflowConnectionImport = function(elem){
        var common = {
                connector: ["StateMachine"],/*[Straight, Flowchart, Bezier, StateMachine]*/
                anchor: ["Left", "Right", "Bottom", "Top"],
                endpoint:"Dot",
                paintStyle:{ strokeStyle:elem['strokeStyle'], lineWidth:elem['lineWidth'] },
                endpointStyle:{ fillStyle:elem['fillStyle'], outlineColor:elem['outlineColor'] }
            };
        jsPlumb.connect({
            source:elem['source'],
            target:elem['target']
        }, common);
    }
    workflow = function(){
        jsPlumb.importDefaults({
            Endpoint : ["Dot", {radius:2}],
            HoverPaintStyle : {strokeStyle:"#1e8151", lineWidth:0 },
            ConnectionOverlays : [
                [ "Arrow", { 
                    location:1,
                    id:"arrow",
                    length:14,
                    foldback:0.8
                } ]
            ]
        });       

        windows = jsPlumb.getSelector('.wfposition');
        
        jsPlumb.makeSource(windows, {

            filter:".connect",               
            anchor:"Continuous",
            connector:[ arrow_style, { curviness:63 } ],
            connectorStyle:{ 
                strokeStyle:"#5c96bc", 
                lineWidth:2, 
                outlineColor:"transparent", 
                outlineWidth:4
            },
            isTarget:true,
            dropOptions : targetDropOptions
            
        }); 

        jsPlumb.makeTarget(windows, {
            dropOptions:{ hoverClass:"dragHover" },
            anchor: "Continuous"             
        });

        var targetDropOptions = {
            tolerance:'touch',
            hoverClass:'dropHover',
            activeClass:'dragActive'
        };
        
        
        me.arrastrable();

    }
    me.menu_accion = function(accion){
        console.log(conexion_selecionada);
        if(accion == "Eliminar"){
            jsPlumb.detach(conexion_selecionada, {
                fireEvent: false, 
                forceDetach: false 
            })
        }

        if(accion == "flecha"){
            conexion_selecionada.setConnector("Straight");
        }

        if(accion == "diagrama"){
            conexion_selecionada.setConnector("Flowchart");
        }

        if(accion == "ondular"){
            conexion_selecionada.setConnector("Bezier");
        }

        if(accion == "cursiva"){
            conexion_selecionada.setConnector("StateMachine");
        }

        if(accion == "discontinua"){
            conexion_selecionada.setPaintStyle({ 
                strokeStyle:"#000", 
                lineWidth:2, 
                outlineColor:"transparent", 
                outlineWidth:4,
                dashstyle: "4 2"
            });
        }

        if(accion == "solido"){
            conexion_selecionada.setPaintStyle({ 
                strokeStyle:"#000", 
                lineWidth:2, 
                outlineColor:"transparent", 
                outlineWidth:4
            });
        }

    }
    me.arrastrable = function(){
        jsPlumb.draggable($(".wfposition"), {
          containment:"editor"
        });
    }
    setIcon = function(elem){
        return '<div ' + 
                    'id="' + elem['id'] + '" ' + 
                    'class="wfposition ' + elem['type'] + '" ' + 
                    'style="' + 
                            'width: ' + elem['width'] + 'px; ' + 
                            'height: ' + elem['height'] + 'px; ' +
                            'display: inline-block;' + 
                            'top: ' + elem['positionTop'] + 'px; ' +
                            'left: ' + elem['positionLeft'] + 'px; ' +
                    '"' + 
                '> '+
                    '<div ' + 
                        'class="wfIcon ' + elem['class'] + '" ' +  
                        'data-type="' + elem['type'] + '" ' + 
                        'data-width="' + elem['width'] + '" ' + 
                        'data-height="' + elem['height'] + '" ' + 
                        'data-border-width="' + elem['borderWidth'] + '" ' + 
                        'data-border-color="' + elem['borderColor'] + '" ' + 
                        'data-background="' + elem['background'] + '" ' + 
                        'data-top="' + elem['positionTop'] + '" ' + 
                        'data-left="' + elem['positionLeft'] + '" ' + 
                        'data-class="' + elem['class'] + '" ' +  
                        'data-title="' + elem['title'] + '" ' +  
                        'style="' + 
                            'background: ' + elem['background'] + '; ' + 
                            'border: ' + elem['borderWidth'] + 'px solid ' + $(elem)['borderColor'] + '; ' + 
                            'width: ' + elem['width'] + 'px; ' + 
                            'height: ' + elem['height'] + 'px;' +
                            '">' + 
                    '</div>' + 
                    '<span class="iconText">' + elem['title'] + '</span>' + 
                    '<div class="connect"></div>' + 
                '</div>';
    }
    setControlVal = function(elem){

        //var currentObj = $('#editor').find('#' + elem);
        var currentObj = elem;
        
        $('.wfposition').each(function(){
            $(this).removeClass('selected');
        });
        currentObj.addClass('selected');
        var id = currentObj.attr('id');
        var _width = currentObj.find('.wfIcon').attr('data-width');
        var _height = currentObj.find('.wfIcon').attr('data-height');
        var strokeWidth = currentObj.find('.wfIcon').attr('data-border-width');
        var strokeColor = currentObj.find('.wfIcon').attr('data-border-color');
        var background = currentObj.find('.wfIcon').attr('data-background');
        var _top = currentObj.find('.wfIcon').attr('data-top');
        var _left = currentObj.find('.wfIcon').attr('data-left');
        var iconText = currentObj.find('.iconText').html();
//        console.log('iconId=' + id + ' | _width=' + _width + ' | _height=' + _height + ' | strokeWidt=' + strokeWidth + ' | strokeColor=' + strokeColor + ' | background=' + background + ' | iconText=' + iconText);
        //$('#currentObjectId').val();
        
        $('#metaDataBorderWidth, #metaDataWidth, #metaDataHeight, #metaDataPositionTop, #metaDataPositionLeft, #metaDataTitle').empty().prop('disabled', false);
        
//        $('#metaDataBackgroundColor').val(background);
//        $('#colorControlBackground').colorpicker('setValue', background);
        
//        $('#colorControlStroke').colorpicker('setValue', strokeColor);
//        $('#colorControlStroke').val(strokeColor);
//        
        $.post('wfbuilder/metaDataBorderWidth', function(data) {
            var selVal;
            $.each(data, function() {
                if(this.ID == strokeWidth)
                {
                    selVal += '<option value="' + this.ID + '" selected="selected">' + this.TITLE + '</option>';
                }else{
                    selVal += '<option value="' + this.ID + '">' + this.TITLE + '</option>';
                }
            });
            $('#metaDataBorderWidth').append(selVal);
            formatResult = function (item) {
                return '<span style="width:30px;  display: inline-block;">' + item.text + '</span> <span style="margin-left:6px; background: #000; width: 50px; height:' + item.text + '; display: inline-block;"></span>';
            }
            $('#metaDataBorderWidth').select2({
                formatResult: formatResult,
                formatSelection: formatResult
            });
            $('#metaDataBorderWidth').trigger('change');
        }, 'json');
        
        $('#metaDataWidth').val(_width);
        $('#metaDataHeight').val(_height);
        $('#metaDataPositionTop').val(currentObj.position().top);
        $('#metaDataPositionLeft').val(currentObj.position().left);
        $('#metaDataTitle').val(iconText);
    }
    setIconVal = function(){
        
        var currentIcon = selectedObj;
        var _width = $('#metaDataWidth').val();
        var _height = $('#metaDataHeight').val();
        var _top = $('#metaDataPositionTop').val();
        var _left = $('#metaDataPositionLeft').val();
        
        console.log(currentIcon);
        
        currentIcon.find('.wfIcon').attr('data-background', $('#metaDataBackgroundColor').val());
        currentIcon.find('.wfIcon').css('background', $('#metaDataBackgroundColor').val());
        
        currentIcon.attr('data-border-color', $('#metaDataBorderColor').val());
        currentIcon.find('.wfIcon').css('border', $('#metaDataBorderColor').val() + ' ' + $('#metaDataBorderWidth').val() + 'px solid');
        
        console.log(currentIcon);
        currentIcon.css('width', _width);
        currentIcon.css('height', _height);
        currentIcon.find('.wfIcon').css('width', _width);
        currentIcon.find('.wfIcon').css('height', _height);
        currentIcon.find('.wfIcon').attr('data-width', _width);
        currentIcon.find('.wfIcon').attr('data-height', _height);

        console.log('width=' + _width + ' | height=' + _height);
        currentIcon.find('.iconText').html($('#metaDataTitle').val());
        
//        currentIcon.css('top', _top + 'px');
//        currentIcon.css('left', _left + 'px');
//        currentIcon.find('.wfIcon').css('top', _top + 'px');
//        currentIcon.find('.wfIcon').css('left', _left + 'px');
//        currentIcon.find('.wfIcon').attr('data-top', _top);
//        currentIcon.find('.wfIcon').attr('data-left', _left);
    }    
         
    
})