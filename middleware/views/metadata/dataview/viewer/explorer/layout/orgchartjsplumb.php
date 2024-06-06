<div class="heigh-editor" id="width-orgchart-container-<?php echo $this->dataViewId; ?>">
    <div class="css-editor" id="workFlowEditor">
        <div class="panzoom">
            <?php
            $id = $this->row['dataViewLayoutTypes']['explorer']['fields']['id'];
            $parent = $this->row['dataViewLayoutTypes']['explorer']['fields']['parent'];
            $dependencydepartmentid = 'dependencydepartmentid';

            $name1 = $this->row['dataViewLayoutTypes']['explorer']['fields']['name1'];
            $name2 = $this->row['dataViewLayoutTypes']['explorer']['fields']['name2'];

            foreach ($this->recordList as $row) {
                echo '<div class="wfposition" id="'.$row[$id].'" data-sourceid="'.$row[$parent].'" data-targetid="'.$row[$id].'" data-twotargetid="'.$row[$dependencydepartmentid].'">'.$row[$name2].'</div>';
            }
            ?>
        </div>    
    </div>
</div>

<style>
.wfposition { width: 100px; height: 100px; border: 1px #000 solid; }
.panzoom {
  position: absolute;
  background-color: rgba(blue,.1);
  overflow: visible;
  border: 5px dotted rgba(#CCC,.1);
}
</style> 

<script type="text/javascript">
var windows;    
var arrowStyle = "Flowchart"; //Straight, Flowchart, Bezier, StateMachine
var minScale = 0.4;
var maxScale = 2;
var incScale = 0.1;
var $panzoom = null;
var $container = $("#width-orgchart-container-<?php echo $this->dataViewId; ?> .css-editor");

$(function(){
        
    $.when(
        $.getStylesheet(URL_APP+'assets/custom/addon/plugins/jsplumb/css/style.css'), 
        $.getScript(URL_APP+'assets/custom/addon/plugins/html2canvas.min.js'),
        $.getScript(URL_APP+'assets/custom/addon/plugins/jsplumb/jsplumb.min.js'),
        $.getScript(URL_APP+'assets/custom/addon/plugins/d3/dagre/dagre.min.js'), 
        $.getScript(URL_APP+'assets/custom/addon/plugins/jquery.panzoom/jquery.panzoom.min.js')
    ).then(function () {
        
        orgChartResizer_<?php echo $this->dataViewId; ?>();
        
        var dg = new dagre.graphlib.Graph();
        dg.setGraph({nodesep:30,ranksep:30,marginx:50,marginy:50});
        dg.setDefaultEdgeLabel(function() { return {}; });

        var nodes = $(".wfposition:visible");

        nodes.each(function(){
            var n = $(this);
            dg.setNode(n.attr('id'), {width: n.width(), height: n.height()});
        });    

        nodes.each(function(){
            var n = $(this);
            if (n.hasAttr('data-sourceid') && n.attr('data-sourceid') != '' && n.attr('data-targetid') != '') {
                dg.setEdge(n.attr('data-sourceid'), n.attr('data-targetid'));
            }
        }); 

        dagre.layout(dg);

        dg.nodes().forEach(function(v) {
            $("#" + v).css("left", dg.node(v).x + "px");
            $("#" + v).css("top", dg.node(v).y + "px");
        });
    
        /* ================= */

        jsPlumb.detachEveryConnection();
       
        jsPlumb.importDefaults({
            ConnectionsDetachable: true,
            ReattachConnections: true,
            connector: [arrowStyle, {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}],
            ConnectionOverlays: [["Arrow", {location: 1, length: 14}]],
            Endpoint: ["Dot", {radius: 1}]
        });

        windows = jsPlumb.getSelector('.wfposition');

        jsPlumb.makeSource(windows, {
            filter: ".connect",
            anchor: "Continuous",
            isSource: true,
            isTarget: false,
            reattach: true,
            maxConnections: 999,
            connector: [arrowStyle, {stub: [10, 60], gap: 10, cornerRadius: 1, alwaysRespectStubs: true}],
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

        var common = {
            connector: [arrowStyle, {stub: [10, 60], midpoint: 0.0001, gap: 10, cornerRadius: 5, alwaysRespectStubs: true}], /*[Straight, Flowchart, Bezier, StateMachine]*/
            paintStyle: {radius: 5},
            hoverPaintStyle: {fillStyle: "#77ca00", strokeStyle: "#77ca00", lineWidth: 5},
            dragOptions: {cursor: 'pointer'} 
        };

        var connections = $(".wfposition");
        
        jsPlumb.setSuspendDrawing(true);
        
        connections.each(function(){
            
            jsPlumb.setSuspendDrawing(true);
            
            var n = $(this);
            if (n.hasAttr('data-sourceid') && n.attr('data-sourceid') != '' && n.attr('data-targetid') != '') {
                jsPlumb.connect({
                    source: n.attr('data-sourceid'),
                    target: n.attr('data-targetid')
                }, common);
            }
            if (n.hasAttr('data-twotargetid') && n.attr('data-twotargetid') != '') {
                jsPlumb.connect({
                    source: n.attr('data-targetid'),
                    target: n.attr('data-twotargetid')
                }, common);
            }
            
            jsPlumb.setSuspendDrawing(false, true);
        });   
        
        jsPlumb.setSuspendDrawing(false, true);
        
        jsPlumb.repaintEverything();
        
        $panzoom = $container.find('.panzoom').panzoom({
            minScale: minScale,
            maxScale: maxScale,
            increment: incScale,
            cursor: "",
            ignoreChildrensEvents:true 
        }).on("panzoomstart",function(e,pz,ev){
            $panzoom.css("cursor","move");
        })
        .on("panzoomend",function(e,pz){
            $panzoom.css("cursor","");
        });
        $panzoom.parent()
        .on('mousewheel.focal', function(e) {
            if (e.ctrlKey||e.originalEvent.ctrlKey) {
                e.preventDefault();
                var delta = e.delta || e.originalEvent.wheelDelta;
                var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
                $panzoom.panzoom('zoom', zoomOut, {
                    animate: true,
                    exponential: false,
                });
            } else {
                e.preventDefault();
                var deltaY = e.deltaY || e.originalEvent.wheelDeltaY || (-e.originalEvent.deltaY);
                var deltaX = e.deltaX || e.originalEvent.wheelDeltaX || (-e.originalEvent.deltaX);
                $panzoom.panzoom("pan",deltaX/2,deltaY/2,{
                    animate: true,
                    relative: true
                });
            }
        })
        .on("mousedown touchstart",function(ev){
            var matrix = $container.find(".panzoom").panzoom("getMatrix");
            var offsetX = matrix[4];
            var offsetY = matrix[5];
            var dragstart = {x:ev.pageX,y:ev.pageY,dx:offsetX,dy:offsetY};
            $(ev.target).css("cursor","move");
            $(this).data('dragstart', dragstart);
        })
        .on("mousemove touchmove", function(ev){
            var dragstart = $(this).data('dragstart');
            if (dragstart) {
                var deltaX = dragstart.x-ev.pageX;
                var deltaY = dragstart.y-ev.pageY;
                var matrix = $container.find(".panzoom").panzoom("getMatrix");
                matrix[4] = parseInt(dragstart.dx)-deltaX;
                matrix[5] = parseInt(dragstart.dy)-deltaY;
                $container.find(".panzoom").panzoom("setMatrix",matrix);
            }
        })
        .on("mouseup touchend touchcancel", function(ev){
            $(this).data('dragstart', null);
            $(ev.target).css("cursor", "");
        });
  
        var currentScale = 1;
        
        jsPlumb.draggable($(".wfposition"), {
            containment: "workFlowEditor", 
            start: function(e){
                var pz = $container.find(".panzoom");
                currentScale = pz.panzoom("getMatrix")[0];
                $(this).css("cursor","move");
                pz.panzoom("disable");
            },
            drag:function(e,ui){
                ui.position.left = ui.position.left/currentScale;
                ui.position.top = ui.position.top/currentScale;
                if ($(this).hasClass("jsplumb-connected")) {
                    jsPlumb.repaint($(this).attr('id'), ui.position);
                }
            },
            stop: function(e,ui){
                var nodeId = $(this).attr('id');
                if ($(this).hasClass("jsplumb-connected")) {
                    jsPlumb.repaint(nodeId, ui.position);
                }
                $(this).css("cursor","");
                $container.find(".panzoom").panzoom("enable");
            }
        });

    }, function () {
        console.log('an error occurred somewhere');
    });

    $('#orgchart-container-<?php echo $this->dataViewId; ?>').on('click', '.node', function(){
        var elem = this;
        var _this = $(elem);
        var _parent = _this.closest('.orgchart');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
    });

    $('#orgchart-container-<?php echo $this->dataViewId; ?>').on('contextmenu', '.node', function(e) {
        e.preventDefault();
        var elem = this;
        var _this = $(elem);
        var _parent = _this.closest('.orgchart');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
    });
     
});   

function orgChartResizer_<?php echo $this->dataViewId; ?>() {
    var orgChartElement = $('#width-orgchart-container-<?php echo $this->dataViewId; ?>');
    
    var getHeight = $(window).height() - orgChartElement.offset().top - 40;
    orgChartElement.height(getHeight);
    
    var getWidth = $('#object-value-list-<?php echo $this->dataViewId; ?>').width();
    orgChartElement.width(getWidth);
}
</script>