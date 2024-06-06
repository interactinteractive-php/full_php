<div class="card card-dashboard-one card-dark-two card-<?php echo $this->metaDataId ?>">
    <div class="card-header pl-3 pr-3" style="border: 0 !important;">
        <h6 class="card-title" id="position-3-text-<?php echo $this->metaDataId ?>"><?php echo $this->card['TEXT'] ?></h6>
    </div>
    <div class="card-body p-0">
        <div class="pl-3 pr-3">
            <h6 style="font-size: 42px;" id="position-3-val-<?php echo $this->metaDataId ?>"><?php echo (isset($this->card['CARD_RESULT']) && $this->card['CARD_RESULT']) ? $this->card['CARD_RESULT'] : '0' ?></h6>
        </div>
        <div class="chart-wrapper">
            <div class="mt-4" id="position-3-chart-<?php echo $this->uniqId ?>"></div>
        </div>
        <form id="finFiscalPeriodCloseForm_<?php echo $this->uniqId; ?>" class="form-horizontal xs-form d-none" method="post">
            <input type="hidden" id="filterStartDate" name="param[filterStartDate]" class="form-control form-control-sm dateInit fin-fiscalperiod-enddate" value="<?php echo Ue::sessionFiscalPeriodStartDate() ?>" placeholder="Шүүлт дуусах огноо" data-metadataid="" data-path="" data-field-name="">
            <input type="hidden" id="filterEndDate" name="param[filterEndDate]" class="form-control form-control-sm dateInit fin-fiscalperiod-enddate" value="<?php echo Ue::sessionFiscalPeriodEndDate() ?>" placeholder="Шүүлт дуусах огноо" data-metadataid="" data-path="" data-field-name="">
        </form>
    </div>
</div>
<style type="text/css">
    .card-<?php echo $this->metaDataId ?>.card-dashboard-one {
        min-height: 162px;
    }

    .card-<?php echo $this->metaDataId ?>.card-dashboard-one.card-dark-two .card-title {
        color: #fff !important;
    }
    
    .card-<?php echo $this->metaDataId ?>.card-dashboard-one.card-dark-two .card-body h6 {
        color: #fff;
    }
    
    .card-<?php echo $this->metaDataId ?>.card-dashboard-one .card-body h6 {
        margin-bottom: 0;
        color: #1c273c;
        font-size: 42px;
        font-weight: 600;
        letter-spacing: -.5px;
        line-height: 1;
    }
    
    .card-<?php echo $this->metaDataId ?>.card {
        box-shadow: 0 0 0;
        border-radius: 0;
    }
    
    .card-<?php echo $this->metaDataId ?> h6.card-title {
        text-transform: uppercase;
        font-weight: bold;
        font-size: 14px;
    }
    
    .card-<?php echo $this->metaDataId ?>.card-dashboard-one.card-dark-two {
        background-color: #0040ff;
        background-image: linear-gradient(to bottom, #0a47ff 0%, #3366ff 100%);
        background-repeat: repeat-x;
    }

    .card-<?php echo $this->metaDataId ?>.card {
        border-width: 0;
        box-shadow: 0 0 6px rgba(28, 39, 60, 0.12);
    }

    .card-<?php echo $this->metaDataId ?>.card {
        margin-top: 0px;
        margin-bottom: 20px;
        padding: 0px;
    }

    .card-<?php echo $this->metaDataId ?>.card-dashboard-one .card-header {
        padding: 15px 15px 15px 0;
        background-color: transparent;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-<?php echo $this->metaDataId ?> .card-header:not(.no-border) {
        height: 40px;
    }

</style>
<script type="text/javascript">

    $(document).ready(function () {
        $.getScript(URL_APP + "assets/core/js/plugins/visualization/d3/d3.min.js").done(function() {
            $.getScript(URL_APP + "assets/core/js/plugins/visualization/d3/d3_tooltip.js").done(function() {
                dataViewByMeta_<?php echo $this->uniqId ?>('<?php echo $this->card['CHART_DATA_VIEW_ID'] ?>', '#position-3-chart-<?php echo $this->uniqId ?>', '<?php echo $this->card['CHART_TYPE'] ?>');
            });
        });
    });
    
    var _BarChart_<?php echo $this->uniqId ?> = function(element, barQty, height, animate, easing, duration, delay, color, tooltip, dataRow) {
        var $this = $(element);
        
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if ($(element).length > 0) {

            // Basic setup
            // ------------------------------
            // Add data set
            
            var bardata = [];
            
            for (var i=0; i < dataRow.length; i++) {
                bardata.push(dataRow[i]['alphavalue']);
            }
            
            // Main variables
            var d3Container = d3.select(element),
                width = d3Container.node().getBoundingClientRect().width;

            // Construct scales
            // ------------------------------
            // Horizontal
            
            var x = d3.scale.ordinal().rangeBands([0, width], 0.3);

            // Vertical
            var y = d3.scale.linear().range([0, height]);

            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.range(0, bardata.length));

            // Vertical
            y.domain([0, d3.max(bardata)]);



            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container.attr('width', width).attr('height', height).append('g');

            //
            // Append chart elements
            //

            // Bars
            var bars = svg.selectAll('rect')
                .data(bardata)
                .enter()
                .append('rect')
                    .attr('class', 'd3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    })
                    .style('fill', color);



            // Tooltip
            // ------------------------------

            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0]);

            // Show and hide
            if (tooltip == 'tooltip') {
                bars.call(tip).on('mouseover', tip.show).on('mouseout', tip.hide);
                
                tip.html(function (d, i) {
                    return '<div class="text-center">' +
                            '<h6 class="m-0">' + dataRow[i]['alphatext'] + '</h6>' +
                            //'<span class="font-size-sm">members</span>' +
                            '<div class="font-size-sm">' + d + '</div>' +
                        '</div>'
                });
            }



            // Bar loading animation
            // ------------------------------

            // Choose between animated or static
            if (animate) {
                withAnimation_<?php echo $this->uniqId ?>();
            } else {
                withoutAnimation_<?php echo $this->uniqId ?>();
            }

            // Animate on load
            function withAnimation_<?php echo $this->uniqId ?>() {
                bars
                    .attr('height', 0)
                    .attr('y', height)
                    .transition()
                        .attr('height', function(d) {
                            return y(d);
                        })
                        .attr('y', function(d) {
                            return height - y(d);
                        })
                        .delay(function(d, i) {
                            return i * delay;
                        })
                        .duration(duration)
                        .ease(easing);
            }

            // Load without animateion
            function withoutAnimation_<?php echo $this->uniqId ?>() {
                bars
                    .attr('height', function(d) {
                        return y(d);
                    })
                    .attr('y', function(d) {
                        return height - y(d);
                    })
            }

            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', barsResize_<?php echo $this->uniqId ?>);

            // Call function on sidebar width change
            $(document).on('click', '.sidebar-control', barsResize_<?php echo $this->uniqId ?>);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            
            function barsResize_<?php echo $this->uniqId ?>() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width;


                // Layout
                // -------------------------

                // Main svg width
                container.attr('width', width);

                // Width of appended group
                svg.attr('width', width);

                // Horizontal range
                x.rangeBands([0, width], 0.3);


                // Chart elements
                // -------------------------

                // Bars
                svg.selectAll('.d3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    });
            }
        }
    };

    var _DailyRevenueLineChart_<?php echo $this->uniqId ?> = function(element, height, datasetRow) {
        var $this = $(element);
        
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if ($(element).length > 0) {
            // Basic setup
            // ------------------------------
            // Add data set
            // 
            // Main variables
            var dataset = datasetRow;
            var d3Container = d3.select(element),
                margin = {top: 0, right: 0, bottom: 0, left: 0},
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
                height = height - margin.top - margin.bottom,
                padding = 20;

            // Format date
            var parseDate = d3.time.format('%m/%d/%y').parse,
                formatDate = d3.time.format('%a, %B %e');


            // Add tooltip
            // ------------------------------

            var tooltip = d3.tip()
                .attr('class', 'd3-tip')
                .html(function (d) {
                    var dd = d.alphatext;
                    var dd = new Date();
                    var $month = (dd.getMonth()+1 < 10) ? '0'+(dd.getMonth()+1) : dd.getMonth()+1;
                    var datestring = $month   + "/" + dd.getDate(); // + "-" + dd.getFullYear();
                    
                    return '<ul class="list-unstyled mb-1">' +
                        '<li>' + '<div class="font-size-base my-1"><i class="icon-check2 mr-2"></i>' + d.carttitle + '</div>' + '</li>' + //formatDate(d.date)
                        '<li>' + datestring + ': <span class="font-weight-semibold float-right">' + d.alphavalue + '</span>' + '</li>' + //'Утга: &nbsp;' + 
                        //'<li>' + 'Revenue: &nbsp; ' + '<span class="font-weight-semibold float-right">' + '$' + (d.alpha * 25).toFixed(2) + '</span>' + '</li>' + 
                    '</ul>';
                });

            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                    .attr('width', width + margin.left + margin.right)
                    .attr('height', height + margin.top + margin.bottom)
                    .append('g')
                        .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')')
                        .call(tooltip);



            // Load data
            // ------------------------------
            
            dataset.forEach(function (d) {
                d.alphatext = parseDate(d.alphatext);
                
                if (typeof d.alphavalue === 'undefined') {
                    d.alphavalue = 0;
                } else {
                    d.alphavalue = +d.alphavalue;
                }
            });
            


            // Construct scales
            // ------------------------------

            // Horizontal
//            var x = d3.scale.ordinal().rangeBands([0, width], 0.3);
            var x = d3.time.scale().range([padding, width - padding]);
            // Vertical
            var y = d3.scale.linear()
                .range([height, 5]);



            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.extent(dataset, function (d) {
                return d.alphatext;
            }));

            // Vertical
            y.domain([0, d3.max(dataset, function (d) {
                return d.alphavalue; //Math.max(d.alpha);
            })]);



            // Construct chart layout
            // ------------------------------

            // Line
            var line = d3.svg.line()
                .x(function(d) {
                    return x(d.alphatext);
                })
                .y(function(d) {
                    return y(d.alphavalue)
                });



            //
            // Append chart elements
            //

            // Add mask for animation
            // ------------------------------

            // Add clip path
            var clip = svg.append('defs')
                .append('clipPath')
                .attr('id', 'clip-line-small');

            // Add clip shape
            var clipRect = clip.append('rect')
                .attr('class', 'clip')
                .attr('width', 0)
                .attr('height', height);

            // Animate mask
            clipRect
                  .transition()
                      .duration(1000)
                      .ease('linear')
                      .attr('width', width);



            // Line
            // ------------------------------

            // Path
            var path = svg.append('path')
                .attr({
                    'd': line(dataset),
                    'clip-path': 'url(#clip-line-small)',
                    'class': 'd3-line d3-line-medium'
                })
                .style('stroke', '#fff');

            // Animate path
            svg.select('.line-tickets')
                .transition()
                    .duration(1000)
                    .ease('linear');



            // Add vertical guide lines
            // ------------------------------

            // Bind data
            var guide = svg.append('g')
                .selectAll('.d3-line-guides-group')
                .data(dataset);

            // Append lines
            guide
                .enter()
                .append('line')
                    .attr('class', 'd3-line-guides')
                    .attr('x1', function (d, i) {
                        return x(d.alphatext);
                    })
                    .attr('y1', function (d, i) {
                        return height;
                    })
                    .attr('x2', function (d, i) {
                        return x(d.alphatext);
                    })
                    .attr('y2', function (d, i) {
                        return height;
                    })
                    .style('stroke', 'rgba(255,255,255,0.3)')
                    .style('stroke-dasharray', '4,2')
                    .style('shape-rendering', 'crispEdges');

            // Animate guide lines
            guide
                .transition()
                    .duration(1000)
                    .delay(function(d, i) { return i * 150; })
                    .attr('y2', function (d, i) {
                        return y(d.alphavalue);
                    });



            // Alpha app points
            // ------------------------------

            // Add points
            var points = svg.insert('g')
                .selectAll('.d3-line-circle')
                .data(dataset)
                .enter()
                .append('circle')
                    .attr('class', 'd3-line-circle d3-line-circle-medium')
                    .attr('cx', line.x())
                    .attr('cy', line.y())
                    .attr('r', 3)
                    .style('stroke', '#fff')
                    .style('fill', '#29B6F6');



            // Animate points on page load
            points
                .style('opacity', 0)
                .transition()
                    .duration(250)
                    .ease('linear')
                    .delay(1000)
                    .style('opacity', 1);


            // Add user interaction
            points
                .on('mouseover', function (d) {
                    tooltip.offset([-10, 0]).show(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 4);
                })

                // Hide tooltip
                .on('mouseout', function (d) {
                    tooltip.hide(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 3);
                });

            // Change tooltip direction of first point
            d3.select(points[0][0])
                .on('mouseover', function (d) {
                    tooltip.offset([0, 10]).direction('e').show(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 4);
                })
                .on('mouseout', function (d) {
                    tooltip.direction('n').hide(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 3);
                });

            // Change tooltip direction of last point
            d3.select(points[0][points.size() - 1])
                .on('mouseover', function (d) {
                    tooltip.offset([0, -10]).direction('w').show(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 4);
                })
                .on('mouseout', function (d) {
                    tooltip.direction('n').hide(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 3);
                })



            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', revenueResize_<?php echo $this->uniqId ?>);

            // Call function on sidebar width change
            $(document).on('click', '.sidebar-control', revenueResize_<?php echo $this->uniqId ?>);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function revenueResize_<?php echo $this->uniqId ?>() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                // Layout
                // -------------------------

                // Main svg width
                container.attr('width', width + margin.left + margin.right);

                // Width of appended group
                svg.attr('width', width + margin.left + margin.right);

                // Horizontal range
                x.range([padding, width - padding]);


                // Chart elements
                // -------------------------

                // Mask
                clipRect.attr('width', width);

                // Line path
                svg.selectAll('.d3-line').attr('d', line(dataset));

                // Circles
                svg.selectAll('.d3-line-circle').attr('cx', line.x());

                // Guide lines
                svg.selectAll('.d3-line-guides')
                    .attr('x1', function (d, i) {
                        return x(d.alphatext);
                    })
                    .attr('x2', function (d, i) {
                        return x(d.alphatext);
                    });
            }
        }

    };
        
    function dataViewByMeta_<?php echo $this->uniqId; ?>(metaDataId, element, charttype) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            async: false,
            data: {
                ignorePermission: false,
                defaultCriteriaData: $("#finFiscalPeriodCloseForm_<?php echo $this->uniqId; ?>").serialize()
            },
            url: 'mdobject/dataViewDataGrid/1/1/' + metaDataId + '/',
            beforeSend: function () {
                Core.blockUI({
                    animate: true,
                    target: element
                });
            },
            success: function (data) {
                var $dataRow = (typeof data['rows'] !== 'undefined' && data['rows']) ? data['rows'] : [];

                if ($dataRow.length > 0) {

                    switch (charttype) {
                        case '1':
                            _DailyRevenueLineChart_<?php echo $this->uniqId ?>(element, 50, $dataRow);
                            break;
                        case '2':
                            _BarChart_<?php echo $this->uniqId ?>(element, 24, 50, true, 'elastic', 1200, 50, 'rgba(255, 255, 255, 0.8)', 'tooltip', $dataRow);
                            break;
                    }

                }
                Core.unblockUI(element);
            },
            error: function() {
                alert("Error");
                Core.unblockUI(element);
            }
        });
    }
    
</script>