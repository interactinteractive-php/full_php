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
        stroke-width: 2px;
    }
</style>
<script type="text/javascript">
    
    var D3TreeCollapsible_<?php echo $this->uniqId ?> = function () {
        var _treeCollapsible = function (data) {
            var treeData_<?php echo $this->uniqId ?> = data;

            var margin_<?php echo $this->uniqId ?> = {top: 20, right: 90, bottom: 30, left: 120},
                width_<?php echo $this->uniqId ?> = 960 - margin_<?php echo $this->uniqId ?>.left - margin_<?php echo $this->uniqId ?>.right,
                height_<?php echo $this->uniqId ?> = 500 - margin_<?php echo $this->uniqId ?>.top - margin_<?php echo $this->uniqId ?>.bottom;

            var svg_<?php echo $this->uniqId ?> = d3.select("#d3-tree-model-<?php echo $this->uniqId ?>").append("svg")
                        .attr("width", width_<?php echo $this->uniqId ?> + margin_<?php echo $this->uniqId ?>.right + margin_<?php echo $this->uniqId ?>.left)
                        .attr("height", height_<?php echo $this->uniqId ?> + margin_<?php echo $this->uniqId ?>.top + margin_<?php echo $this->uniqId ?>.bottom)
                        .append("g")
                        .attr("transform", "translate("+ margin_<?php echo $this->uniqId ?>.left + "," + margin_<?php echo $this->uniqId ?>.top + ")");

            var i = 0, 
                duration_<?php echo $this->uniqId ?> = 750, 
                root_<?php echo $this->uniqId ?>;

            var treemap = d3.tree().size([height_<?php echo $this->uniqId ?>, width_<?php echo $this->uniqId ?>]);
            
            root_<?php echo $this->uniqId ?> = d3.hierarchy(treeData_<?php echo $this->uniqId ?>, function (d) {
                return d.children;
            });
            
            root_<?php echo $this->uniqId ?>.x0 = height_<?php echo $this->uniqId ?> / 2;
            root_<?php echo $this->uniqId ?>.y0 = 0;
//            root_<?php echo $this->uniqId ?>.children.forEach(collapse);
            
            update_<?php echo $this->uniqId ?>(root_<?php echo $this->uniqId ?>);
            /*
            function toggleAll(d) {
                if (d.children) {
                    d.children.forEach(toggleAll);
                    collapse(d);
                }
            }
*/
//            root_<?php echo $this->uniqId ?>.children.forEach(toggleAll);
//            collapse(root_<?php echo $this->uniqId ?>.children[0]);
            
            function collapse(d) {
                if (d.children) {
                    d._children = d.children
                    d._children.forEach(collapse)
                    d.children = null
                }
            }

            function update_<?php echo $this->uniqId ?>(source) {

                var treeData_<?php echo $this->uniqId ?> = treemap(root_<?php echo $this->uniqId ?>);
                var nodes = treeData_<?php echo $this->uniqId ?>.descendants(),
                    links = treeData_<?php echo $this->uniqId ?>.descendants().slice(1);

                nodes.forEach(function (d) {
                    d.y = d.depth * 180
                });

                var node = svg_<?php echo $this->uniqId ?>.selectAll('g.node').data(nodes, function (d) {
                            return d.id || (d.id = ++i);
                        });

                var nodeEnter = node.enter().append('g')
                        .attr('class', 'node')
                        .attr("transform", function (d) {
                            return "translate(" + source.y0 + "," + source.x0 + ")";
                        })
                        .on('click', click_<?php echo $this->uniqId ?>);

                nodeEnter.append('circle')
                        .attr('class', 'node')
                        .attr('r', 6)
                        .style("fill", function (d) {
                            return d._children ? "lightsteelblue" : "#fff";
                        })
                        .style("stroke", function (d) {
                            return d.data.colour;
                        });

                nodeEnter.append('text')
                        .attr("dy", "-15")
                        .attr("x", function (d) {
                            return d.children || d._children ? -1 : 1;
                        })
                        .attr("text-anchor", function (d) {
                            return d.children || d._children ? "end" : "start";
                        })
                        .text(function (d) {
                            return d.data.name;
                        })
                        .on("mouseover", handleMouseOver_<?php echo $this->uniqId ?>)
                        .on("mouseout", handleMouseOut_<?php echo $this->uniqId ?>);

                var nodeUpdate_<?php echo $this->uniqId ?> = nodeEnter.merge(node);

                nodeUpdate_<?php echo $this->uniqId ?>.transition()
                        .duration(duration_<?php echo $this->uniqId ?>)
                        .attr("transform", function (d) {
                            return "translate(" + d.y + "," + d.x + ")";
                        });

                nodeUpdate_<?php echo $this->uniqId ?>.select('circle.node')
                        .attr('r', 10)
                        .style("fill", function (d) {
                            return d._children ? "lightsteelblue" : "#fff";
                        })
                        .attr('cursor', 'pointer');

                function handleMouseOver_<?php echo $this->uniqId ?>(d, i) {  // Add interactivity
                    d3.select(this).attr("dy", -35)
                    d3.select(this).style("font-size", "20px");
                }

                function handleMouseOut_<?php echo $this->uniqId ?>(d, i) {
                    d3.select(this).attr("dy", -15)
                    d3.select(this).style("font-size", "10px");
                    ;
                }

                var nodeExit = node.exit().transition()
                        .duration(duration_<?php echo $this->uniqId ?>)
                        .attr("transform", function (d) {
                            return "translate(" + source.y + "," + source.x + ")";
                        })
                        .remove();

                nodeExit.select('circle').attr('r', 6);
                nodeExit.select('text').style('fill-opacity', 1e-6);

                var link_<?php echo $this->uniqId ?> = svg_<?php echo $this->uniqId ?>.selectAll('path.link').data(links, function (d) {
                            return d.id;
                        });

                var linkEnter_<?php echo $this->uniqId ?> = link_<?php echo $this->uniqId ?>.enter().insert('path', "g")
                        .attr("class", "link")
                        .attr('d', function (d) {
                            var o = {x: source.x0, y: source.y0}
                            return diagonal_<?php echo $this->uniqId ?>(o, o)
                        });

                var linkUpdate_<?php echo $this->uniqId ?> = linkEnter_<?php echo $this->uniqId ?>.merge(link_<?php echo $this->uniqId ?>);

                linkUpdate_<?php echo $this->uniqId ?>.transition()
                        .duration(duration_<?php echo $this->uniqId ?>)
                        .attr('d', function (d) {
                            return diagonal_<?php echo $this->uniqId ?>(d, d.parent)
                        });

                var linkExit_<?php echo $this->uniqId ?> = link_<?php echo $this->uniqId ?>.exit().transition()
                        .duration(duration_<?php echo $this->uniqId ?>)
                        .attr('d', function (d) {
                            var o = {x: source.x, y: source.y}
                            return diagonal_<?php echo $this->uniqId ?>(o, o)
                        })
                        .remove();

                nodes.forEach(function (d) {
                    d.x0 = d.x;
                    d.y0 = d.y;
                });

                function diagonal_<?php echo $this->uniqId ?>(s, d) {
                    path = `M ${s.y} ${s.x} C ${(s.y + d.y) / 2} ${s.x}, ${(s.y + d.y) / 2} ${d.x}, ${d.y} ${d.x}`
                    return path
                }

                function click_<?php echo $this->uniqId ?>(d) {
                    if (d.children) {
                        d._children = d.children;
                        d.children = null;
                    } else {
                        d.children = d._children;
                        d._children = null;
                    }
                    
                    update_<?php echo $this->uniqId ?>(d);
                }
            }
        };
        
        return {
            init: function (data) {
                _treeCollapsible(data);
            }
        };
    }();
    


</script>