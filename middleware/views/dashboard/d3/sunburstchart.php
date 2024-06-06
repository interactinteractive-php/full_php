
<script src="<?php echo autoVersion('assets/core/js/plugins/visualization/d3/d3.min.js'); ?>"></script>
<div class="chart-container has-scroll text-center">
    <div id="lifechart"></div>
    <?php echo $this->diagram['TITLE']; ?>
</div>
<script>
    var root3 = {
        "name": "name",
        "children": [
            {
            "name": "data1",
            "children": [
                {
                "name": "data1.2",
                "children": [
                    {
                    "name": "data1.3",
                    "size": 150
                    },
                    {
                    "name": "data1.4",
                    "size": 150
                    },
                    {
                    "name": "data1.5",
                    "size": 150
                    }
                ]
                }
            ]
            },
            {
            "name": "data11",
            "children": [
                {
                "name": "Consumer",
                "size": 300,
                "children": [
                    {
                    "name": "UX",
                    "size": 100
                    },
                    {
                    "name": "Citizen",
                    "size": 100
                    }
                ]
                },
              
            ]
            },
            {
            "name": "data1.3",
            "children": [
            
                {
                "name": "data131",
                "size": 200,
                "children": [
                    {
                    "name": "data132",
                    "size": 100
                    },
                    {
                    "name": "data133",
                    "size": 50
                    }
                ]
                }
            ]
            },
            {
            "name": "data141",
            "children": [
               
                {
                "name": "data142",
                "size": 300,
                "children": [
                    {
                    "name": "data143",
                    "size": 100
                    },
                    {
                    "name": "data144",
                    "size": 100
                    },
                    {
                    "name": "data145",
                    "size": 50
                    }
                ]
                }
            ]
            }
        ]
    };E

    var metaDataId = '<?php echo $this->metaDataId; ?>',
        chartType = '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>',
        title;
       // console.log(chartType);
  

    if (chartType === 'sunburst') {
        $.ajax({
            type: 'post',
            url: 'mdobject/getAjaxTree',
            dataType: 'json',
            data: {dataViewId: '1583285117892452',structureMetaDataId:''},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (response) {
                if (response.chartType === 'sunburst') {
                    console.log(response);
                    Core.unblockUI();
                }
            }
        });
    } 
    

    var width = 700,
        height = 700,
        radius = Math.min(width, height) / 2;

    var x = d3.scale.linear()
        .range([0, 2 * Math.PI]);

    var y = d3.scale.sqrt()
        .range([0, radius]);

    var color = d3.scale.category20c();

    var svg = d3.select("#lifechart").append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + (height / 2 + 10) + ") rotate(-90 0 0)");

    var partition = d3.layout.partition()
        .value(function (d) {
        return d.size;
    });

    var arc = d3.svg.arc()
        .startAngle(function (d) {
        return Math.max(0, Math.min(2 * Math.PI, x(d.x)));
    })
        .endAngle(function (d) {
        return Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx)));
    })
        .innerRadius(function (d) {
        return Math.max(0, y(d.y));
    })
        .outerRadius(function (d) {
        return Math.max(0, y(d.y + d.dy));
    });

    //d3.json("/d/4063550/flare.json", function(error, root) {
    var root = root3

    var g = svg.selectAll("g")
        .data(partition.nodes(root))
        .enter().append("g");

    var path = g.append("path")
        .attr("d", arc)
        .style("fill", function (d) {
        return color((d.children ? d : d.parent).name);
    })
        .on("click", click);

    var text = g.append("text")
        .attr("x", function (d) {
        return y(d.y);
    })
        .attr("dx", "6") // margin
    .attr("dy", ".35em") // vertical-align
    .text(function (d) {
        return d.name;
    });

    function computeTextRotation(d) {
        var angle = x(d.x + d.dx / 2) - Math.PI / 2;
        return angle / Math.PI * 180;
    }

    text.attr("transform", function (d) {
        return "rotate(" + computeTextRotation(d) + ")";
    });


    function click(d) {
        // fade out all text elements
        if(d.size !== undefined) {
            d.size += 100;
        };
        text.transition().attr("opacity", 0);

        path.transition()
            .duration(750)
            .attrTween("d", arcTween(d))
            .each("end", function (e, i) {
            // check if the animated element's data e lies within the visible angle span given in d
            if (e.x >= d.x && e.x < (d.x + d.dx)) {
                // get a selection of the associated text element
                var arcText = d3.select(this.parentNode).select("text");
                // fade in the text element and recalculate positions
                arcText.transition().duration(750)
                    .attr("opacity", 1)
                    .attr("transform", function () {
                    return "rotate(" + computeTextRotation(e) + ")"
                })
                    .attr("x", function (d) {
                    return y(d.y);
                });
            }
        });
    } //});

    d3.select(self.frameElement).style("height", height + "px");

    // Interpolate the scales!
    function arcTween(d) {
        var xd = d3.interpolate(x.domain(), [d.x, d.x + d.dx]),
            yd = d3.interpolate(y.domain(), [d.y, 1]),
            yr = d3.interpolate(y.range(), [d.y ? 20 : 0, radius]);
        return function (d, i) {
            return i ? function (t) {
                return arc(d);
            } : function (t) {
                x.domain(xd(t));
                y.domain(yd(t)).range(yr(t));
                return arc(d);
            };
        };
    }

</script>