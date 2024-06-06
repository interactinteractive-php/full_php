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
.d3-context-menu {
    position: absolute;
    display: none;
    background-color: #f2f2f2;
    border-radius: 4px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    min-width: 150px;
    border: 1px solid #d4d4d4;
    z-index:1200;
}
.d3-context-menu ul {
    list-style-type: none;
    margin: 4px 0px;
    padding: 0px;
    cursor: default;
}
.d3-context-menu ul li {
    padding: 4px 16px;
}
.d3-context-menu ul li:hover {
    background-color: #4677f8;
    color: #fefefe;
}
</style>

<script type="text/javascript">
    
    var D3TreeCollapsible_<?php echo $this->uniqId ?> = function () {
        
        var _treeCollapsible = function (data) {
            
            var m_<?php echo $this->uniqId ?> = [40, 240, 40, 240];
            var cateCode_<?php echo $this->uniqId ?> = <?php echo isset($this->colorPath) ? json_encode($this->colorPath) : '{}' ?>;
            var tempSelectedNode_<?php echo $this->uniqId ?> = {};
            var links_<?php echo $this->uniqId ?> = data.relation;
            var dataNode_<?php echo $this->uniqId ?> = data.node;
            var nodes_<?php echo $this->uniqId ?> = {};
            var nodeToType_<?php echo $this->uniqId ?> = {};
            var tempNodeArr_<?php echo $this->uniqId ?> = {}, tempNodeIds = [];
            
            links_<?php echo $this->uniqId ?>.forEach(function(link_<?php echo $this->uniqId ?>) {
                
                link_<?php echo $this->uniqId ?>.source = nodes_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.source] || (nodes_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.source] = {name: link_<?php echo $this->uniqId ?>.source});
                link_<?php echo $this->uniqId ?>.target = nodes_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.target] || (nodes_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.target] = {name: link_<?php echo $this->uniqId ?>.target});
                
                nodeToType_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.target.name] = link_<?php echo $this->uniqId ?>.col;
            });
            
            dataNode_<?php echo $this->uniqId ?>.forEach(function(link_<?php echo $this->uniqId ?>) {
                
                if ($.inArray(link_<?php echo $this->uniqId ?>.id, tempNodeIds)  == -1) {
                    
                    tempNodeArr_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.name] = {
                        name: link_<?php echo $this->uniqId ?>.name, 
                        templateid: link_<?php echo $this->uniqId ?>.templateId, 
                        id: link_<?php echo $this->uniqId ?>.id
                    };
                    tempNodeIds.push(link_<?php echo $this->uniqId ?>.srcTemplateId);
                    
                    <?php if (isset($this->colorPath) && $this->colorPath) { ?>
                        nodeToType_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.name] = cateCode_<?php echo $this->uniqId ?>[link_<?php echo $this->uniqId ?>.categoryId];
                    <?php } ?>
                }
            });

            var width_<?php echo $this->uniqId ?> = $(window).width() - 50,
                height_<?php echo $this->uniqId ?> = $(window).height() - $('#d3-tree-model-<?php echo $this->uniqId ?>').offset().top - 20;

            var force_<?php echo $this->uniqId ?> = d3.layout.force()
                .nodes(d3.values(nodes_<?php echo $this->uniqId ?>))
                .links(links_<?php echo $this->uniqId ?>)
                .size([width_<?php echo $this->uniqId ?>, height_<?php echo $this->uniqId ?>])
                .linkDistance(60)

            .charge(function(d, i) { return i==0 ? -1000 : -500; })
                .on("tick", tick_<?php echo $this->uniqId ?>)
                .start();

            var svg_<?php echo $this->uniqId ?> = d3.select("#d3-tree-model-<?php echo $this->uniqId ?>").append("svg")
                .attr("width", width_<?php echo $this->uniqId ?>)
                .attr("height", height_<?php echo $this->uniqId ?>)
                .attr("class", "drawSvg ")
                .call(d3.behavior.zoom().scaleExtent([0.5, 5]).on("zoom", function () {
                    svg_<?php echo $this->uniqId ?>.attr("transform", "translate(" + d3.event.translate + ")" + " scale(" + d3.event.scale + ")")
                })).append("g");

            var link_<?php echo $this->uniqId ?> = svg_<?php echo $this->uniqId ?>.selectAll(".link")
                .data(force_<?php echo $this->uniqId ?>.links())
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
            
            var node_<?php echo $this->uniqId ?> = svg_<?php echo $this->uniqId ?>.selectAll(".node")
            .data(force_<?php echo $this->uniqId ?>.nodes())
            .enter().append("g")
            .attr("class", "node")
            .on("mouseover", mouseover_<?php echo $this->uniqId ?>)
            .on("mouseout", mouseout_<?php echo $this->uniqId ?>)
            .on("dblclick", function(d) { 
                $('#d3-tree-model-<?php echo $this->uniqId ?>').find('.node').removeAttr("style");
                d3.select(this).style("fill", "magenta");

                if (tempNodeArr_<?php echo $this->uniqId ?>[d.name]['id'] === tempSelectedNode_<?php echo $this->uniqId ?>['id']) {
                    tempSelectedNode_<?php echo $this->uniqId ?> = {};
                }
                
                if (Object.keys(tempSelectedNode_<?php echo $this->uniqId ?>).length > 0) {
                    
                    var $dialogName = 'dialog-confirm-ea';
                    var $html = '<strong>' + tempNodeArr_<?php echo $this->uniqId ?>[d.name]['name'] + '</strong>' + ' болон ' + '<strong>' + tempSelectedNode_<?php echo $this->uniqId ?>['name'] + '</strong>' +  ' холбох уу?';
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
                                        trgTemplateId: tempNodeArr_<?php echo $this->uniqId ?>[d.name]['id'],
                                        trgTemplateName: tempNodeArr_<?php echo $this->uniqId ?>[d.name]['name'],
                                        srcTemplateId: tempSelectedNode_<?php echo $this->uniqId ?>['id'],
                                        srcTemplateName: tempSelectedNode_<?php echo $this->uniqId ?>['name'],
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
                                                    $('.tree-model-<?php echo $this->uniqId ?>').html(data.Html).promise().done(function () {
                                                        $dialog.dialog('close');
                                                    });
                                                }
                                            });  
                                        }

                                        Core.unblockUI();
                                        tempSelectedNode_<?php echo $this->uniqId ?> = {};
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
                    tempSelectedNode_<?php echo $this->uniqId ?> = tempNodeArr_<?php echo $this->uniqId ?>[d.name];
                }

            }).call(force_<?php echo $this->uniqId ?>.drag);

            node_<?php echo $this->uniqId ?>.append("circle")
                .attr("r", 8)
                .style("fill", function(d) { return '#FFF'; /* nodeToType_<?php echo $this->uniqId ?>[d.name];*/ })
                .style("stroke", function(d) { return nodeToType_<?php echo $this->uniqId ?>[d.name]; });

            node_<?php echo $this->uniqId ?>.append("text")
                .attr("x", 14)
                .attr("dy", ".35em")
                .text(function(d) { return d.name; });
            
            function zoom_<?php echo $this->uniqId ?>() {
                var scale = d3.event.scale,
                    translation = d3.event.translate,
                    tbound = -height_<?php echo $this->uniqId ?> * scale,
                    bbound = height_<?php echo $this->uniqId ?> * scale,
                    lbound = (-width_<?php echo $this->uniqId ?> + m_<?php echo $this->uniqId ?>[1]) * scale,
                    rbound = (width_<?php echo $this->uniqId ?> - m_<?php echo $this->uniqId ?>[3]) * scale;
                // limit translation to thresholds
                translation = [
                    Math.max(Math.min(translation[0], rbound), lbound),
                    Math.max(Math.min(translation[1], bbound), tbound)
                ];
                
                d3.select(".drawSvg").attr("transform", "translate(" + translation + ")" + " scale(" + scale + ")");
            }

            function tick_<?php echo $this->uniqId ?>() {
                link_<?php echo $this->uniqId ?>.attr("x1", function(d) { return d.source.x; })
                    .attr("y1", function(d) { return d.source.y; })
                    .attr("x2", function(d) { return d.target.x; })
                    .attr("y2", function(d) { return d.target.y; });

                node_<?php echo $this->uniqId ?>.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
            }

            function mouseover_<?php echo $this->uniqId ?>() {
                d3.select(this).select("circle").transition().duration(750).attr("r", 16);
            }

            function mouseout_<?php echo $this->uniqId ?>() {
                d3.select(this).select("circle").transition().duration(750).attr("r", 8);
            }
            
            $.contextMenu({
                selector: ".node",
                build: function($trigger, e) {
                    
                    var $this = $(e.currentTarget), contextMenuData = {}, getName = $this.find('text').text();
                    var noteRow = tempNodeArr_<?php echo $this->uniqId ?>[getName];
                    var templateId = (typeof noteRow['templateid'] !== 'undefined' ? noteRow['templateid'] : null);
                    var nodeId = (typeof noteRow['id'] !== 'undefined' ? noteRow['id'] : null);
                    var showMenu = false;
                    
                    if (!templateId && nodeId) {
                        templateId = nodeId;
                    }
                    
                    if (templateId) {
                    
                        $.ajax({
                            type: "post",
                            url: "mdlayout/getMenuDataByTemplate",
                            data: {templateId: templateId}, 
                            dataType: 'json',
                            async: false,
                            success: function(data){

                                var dataLength = data.length;
                                var rowListMetaDataId = null;
                                
                                if (dataLength) {

                                    for (var i = 0; i < dataLength; i++) {
                                        
                                        rowListMetaDataId = (typeof data[i].listmetadataid != 'undefined' ? data[i].listmetadataid : null);
                                        
                                        contextMenuData[data[i].name] = {
                                            name: data[i].name,
                                            recordId: data[i].trgtemplateid,
                                            selectedId: tempNodeArr_<?php echo $this->uniqId ?>[getName]['id'],
                                            charttype: data[i].charttype,
                                            listmetadataid: rowListMetaDataId,
                                            callback: function(key, options) {
                                                
                                                treeLayoutContentRender(
                                                    {
                                                        selectedRow: JSON.stringify({
                                                            id: options['items'][key]['recordId'], 
                                                            selectedId: options['items'][key]['selectedId'], 
                                                            charttype: options['items'][key]['charttype'], 
                                                            listmetadataid: options['items'][key]['listmetadataid'], 
                                                            name: key
                                                        })
                                                    }, 
                                                    options['items'][key]['recordId']
                                                );
                                            }                                    
                                        };
                                    }
                                    
                                    showMenu = true;
                                }
                            }
                        });
                    }

                    var options = {
                        callback: function (key, opt) {},
                        items: contextMenuData
                    };

                    return showMenu && options;         
                }
            });            
        };
        
        return {
            init: function (data) {
                _treeCollapsible(data);
            }
        };
    }();
</script>