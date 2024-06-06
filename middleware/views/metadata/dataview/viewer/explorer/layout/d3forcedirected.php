<div style="background: #FFF;" id="d3-forcedirected-<?php echo $this->dataViewId; ?>"></div>

<style type="text/css">
.node circle {
    fill: #fff;
    stroke: steelblue;
    stroke-width: 3px;
}
.node text {
    font: 10px sans-serif;
}
.link {
    fill: none;
    stroke: #ccc;
    stroke-width: 1px dashed;   
}
</style>

<script type="text/javascript">

<?php
$node = $nodeList = $relation = $color = array();

foreach ($this->recordList as $row) {
    
    if (!isset($node[$row['srcname']])) {
        $nodeList[] = array(
            'id' => $row['srcid'], 
            'name' => $row['srcname'], 
        );
        
        $node[$row['srcname']] = 1;
        $color[$row['srcname']] = $row['srccolor'];
    }
    
    if (!isset($node[$row['trgname']])) {
        $nodeList[] = array(
            'id' => $row['trgid'], 
            'name' => $row['trgname'], 
        );
        
        $node[$row['trgname']] = 1;
        $color[$row['trgname']] = $row['trgcolor'];
    }
    
    $relation[] = array(
        'source' => $row['srcname'], 
        'target' => $row['trgname']
    );
}

$data = array('node' => $nodeList, 'relation' => $relation);
?>

if (typeof d3 === 'undefined') {
    $.cachedScript('assets/core/js/plugins/visualization/d3/d3.min.js').done(function() {
        dvForceDirectedInit_<?php echo $this->dataViewId; ?>();
    });
} else {
    dvForceDirectedInit_<?php echo $this->dataViewId; ?>();
}

function dvForceDirectedInit_<?php echo $this->dataViewId; ?>() {
    var d3Data_ = <?php echo json_encode($data); ?>;
    var m_ = [40, 240, 40, 240];
    var cateCode_ = <?php echo json_encode($color); ?>;
    var tempSelectedNode_ = {};
    var links_ = d3Data_.relation;
    var dataNode_ = d3Data_.node;
    var nodes_ = {};
    var nodeToType_ = {};
    var tempNodeArr_ = {}, tempNodeIds = [];

    links_.forEach(function(link_) {

        link_.source = nodes_[link_.source] || (nodes_[link_.source] = {name: link_.source});
        link_.target = nodes_[link_.target] || (nodes_[link_.target] = {name: link_.target});

        nodeToType_[link_.target.name] = link_.col;
    });

    dataNode_.forEach(function(link_) {

        if ($.inArray(link_.id, tempNodeIds) == -1) {

            tempNodeArr_[link_.name] = {
                name: link_.name, 
                templateid: link_.templateId, 
                id: link_.id
            };
            tempNodeIds.push(link_.srcTemplateId);
            
            nodeToType_[link_.name] = cateCode_[link_.name];
        }
    });

    var $forceDirected = $('#d3-forcedirected-<?php echo $this->dataViewId; ?>'),
        width_ = $forceDirected.width(),
        height_ = $(window).height() - $forceDirected.offset().top - 40, 
        $hardHeight = $forceDirected.closest('[data-hard-height]');
    
    if ($hardHeight.length) {
        height_ = $hardHeight.attr('data-hard-height');
    }

    var force_ = d3.layout.force()
        .nodes(d3.values(nodes_))
        .links(links_)
        .size([width_, height_])
        .linkDistance(60)

    .charge(function(d, i) { return i==0 ? -1000 : -500; })
        .on("tick", tick_)
        .start();

    var svg_ = d3.select("#d3-forcedirected-<?php echo $this->dataViewId; ?>").append("svg")
        .attr("width", width_)
        .attr("height", height_)
        .attr("class", "drawSvg ")
        .call(d3.behavior.zoom().scaleExtent([0.5, 5]).on("zoom", function () {
            svg_.attr("transform", "translate(" + d3.event.translate + ")" + " scale(" + d3.event.scale + ")")
        })).append("g");

    var link_ = svg_.selectAll(".link")
        .data(force_.links())
        .enter().append("line")
        .attr("class", "link");

    d3.contextMenu = function (menu, openCallback) {

        // create the div element that will hold the context menu
        d3.selectAll('.d3-context-menu').data([1])
            .enter()
            .append('div')
            .attr('class', 'd3-context-menu');

        // close menu
        d3.select('body').on('click.d3-context-menu', function() {
            d3.select('.d3-context-menu').style('display', 'none');
        });

        // this gets executed when a contextmenu event occurs
        return function(data, index) {
                var elm = this;

                d3.selectAll('.d3-context-menu').html('');
                var list = d3.selectAll('.d3-context-menu').append('ul');
                list.selectAll('li').data(menu).enter()
                    .append('li')
                    .html(function(d) {
                        return (typeof d.title === 'string') ? d.title : d.title(data);
                    })
                    .on('click', function(d, i) {
                        d.action(elm, data, index);
                        d3.select('.d3-context-menu').style('display', 'none');
                    });

                // the openCallback allows an action to fire before the menu is displayed
                // an example usage would be closing a tooltip
                if (openCallback && openCallback(data, index) === false) {
                    return;
                }

                // display context menu
                d3.select('.d3-context-menu')
                    .style('left', (d3.event.pageX - 2) + 'px')
                    .style('top', (d3.event.pageY - 2) + 'px')
                    .style('display', 'block');

                d3.event.preventDefault();
                d3.event.stopPropagation();
        };                       
    };        

    var menuData = [
        {
            title: 'Rename node',
            action: function(elm, d, i) {
                console.log('Rename node');
            }
        },
        {
            title: 'Delete node',
            action: function(elm, d, i) {
                console.log('Delete node');
            }
        },
        {
            title: 'Create child node',
            action: function(elm, d, i) {
                console.log('Create child node');
            }
        }
    ];

    var node_ = svg_.selectAll(".node")
    .data(force_.nodes())
    .enter().append("g")
    .attr("class", "node")
    .on("mouseover", mouseover_)
    .on("mouseout", mouseout_)
    .on("dblclick", function(d) { 
        $('#d3-forcedirected-<?php echo $this->dataViewId; ?>').find('.node').removeAttr("style");
        d3.select(this).style("fill", "magenta");

        if (tempNodeArr_[d.name]['id'] === tempSelectedNode_['id']) {
            tempSelectedNode_ = {};
        }

        if (Object.keys(tempSelectedNode_).length > 0) {

            var $dialogName = 'dialog-confirm-ea';
            var $html = '<strong>' + tempNodeArr_[d.name]['name'] + '</strong>' + ' болон ' + '<strong>' + tempSelectedNode_['name'] + '</strong>' +  ' холбох уу?';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);

            $dialog.empty().append($html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Баталгаажуулалт',
                width: 500,
                height: "auto",
                modal: true,
                close: function () {
                    $dialog.empty().dialog('close');
                },
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: "post",
                            url: "mdlayout/relationD3Tree",
                            data: {
                                trgTemplateId: tempNodeArr_[d.name]['id'],
                                trgTemplateName: tempNodeArr_[d.name]['name'],
                                srcTemplateId: tempSelectedNode_['id'],
                                srcTemplateName: tempSelectedNode_['name']
                            }, 
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true
                                });
                            },
                            dataType: 'json',
                            async: false,
                            success: function(data) {
                                if (typeof data.status !== 'undefined' && data.status === 'success') {
                                    $.ajax({
                                        type: "post",
                                        url: "mdlayout/treeTemplate",
                                        dataType: 'json',
                                        async: false,
                                        success: function(data) {
                                            $('.tree-model-').html(data.Html).promise().done(function () {
                                                $dialog.dialog('close');
                                            });
                                        }
                                    });  
                                }

                                Core.unblockUI();
                                tempSelectedNode_ = {};
                            }
                        });  
                    }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
        } else {
            tempSelectedNode_ = tempNodeArr_[d.name];
        }

    }).call(force_.drag);

    node_.append("circle")
        .attr("r", 8)
        .style("fill", function(d) { return '#FFF'; /* nodeToType_[d.name];*/ })
        .style("stroke", function(d) { return nodeToType_[d.name]; });

    node_.append("text")
        .attr("x", 14)
        .attr("dy", ".35em")
        .text(function(d) { return d.name; });

    function zoom_() {
        var scale = d3.event.scale,
            translation = d3.event.translate,
            tbound = -height_ * scale,
            bbound = height_ * scale,
            lbound = (-width_ + m_[1]) * scale,
            rbound = (width_ - m_[3]) * scale;
        // limit translation to thresholds
        translation = [
            Math.max(Math.min(translation[0], rbound), lbound),
            Math.max(Math.min(translation[1], bbound), tbound)
        ];

        d3.select(".drawSvg").attr("transform", "translate(" + translation + ")" + " scale(" + scale + ")");
    }

    function tick_() {
        link_.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node_.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    }

    function mouseover_() {
        d3.select(this).select("circle").transition().duration(750).attr("r", 16);
    }

    function mouseout_() {
        d3.select(this).select("circle").transition().duration(750).attr("r", 8);
    }
}
</script>