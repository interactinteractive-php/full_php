<div class="cloud_dashboard theme4 pt-1">
    <div class="container-fliud" id="theme4">
        <div class="row mt-3">
            <div class="col-7">
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="card-title">title</h6>
                    </div>
                    <div class="card-body">
                       <div id=""></div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="card-title">
                          title
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id=""></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title">
                            title
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="card-progress">
                            <div class="d-flex justify-content-between mb-2">
                            
                                <?php echo $this->lang->line('p1_cloud_sales_news4'); ?>                     
                                <span class="font-weight-bold">32,167 <span class="text-muted font-weight-semibold">(60%)</span></span>                        
                            </div>
                            <div class="progress mb-3" style="height: 0.625rem;">
                                <div class="progress-bar bg-green" style="width: 60%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title">
                            title
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="card-progress">
                            <div class="d-flex justify-content-between mb-2">
                            
                                <?php echo $this->lang->line('p1_cloud_sales_news4'); ?>                     
                                <span class="font-weight-bold">32,167 <span class="text-muted font-weight-semibold">(60%)</span></span>                        
                            </div>
                            <div class="progress mb-3" style="height: 0.625rem;">
                                <div class="progress-bar bg-green" style="width: 60%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title">
                            title
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="card-progress">
                            <div class="d-flex justify-content-between mb-2">
                            
                                <?php echo $this->lang->line('p1_cloud_sales_news4'); ?>                     
                                <span class="font-weight-bold">32,167 <span class="text-muted font-weight-semibold">(60%)</span></span>                        
                            </div>
                            <div class="progress mb-3" style="height: 0.625rem;">
                                <div class="progress-bar bg-green" style="width: 60%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title">
                            title
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="card-progress">
                            <div class="d-flex justify-content-between mb-2">
                            
                                <?php echo $this->lang->line('p1_cloud_sales_news4'); ?>                     
                                <span class="font-weight-bold">32,167 <span class="text-muted font-weight-semibold">(60%)</span></span>                        
                            </div>
                            <div class="progress mb-3" style="height: 0.625rem;">
                                <div class="progress-bar bg-green" style="width: 60%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-7">
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="card-title">title</h6>
                    </div>
                    <div class="card-body">
                       <div id=""></div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="card-title">
                          title
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id=""></div>
                    </div>
                </div>
            </div>
        </div>

      

    </div>
</div>
<style>
    .text-muted {
    color: #5a5a5a!important;
}
    #chart6{height: 240px;}
</style>
<script type="text/javascript" src="assets/custom/addon/plugins/apexcharts/apexcharts.js"></script>

<script>

    var cart6 = <?php echo json_encode($this->p6); ?>;
    Core.initNumberInput();
    var datasalesName = <?php echo json_encode($this->chartName); ?>;
    var chartName2 = <?php echo json_encode($this->chartName2); ?>;
    var chartval1 = <?php echo json_encode($this->chartval1); ?>;
    var chartval2 = <?php echo json_encode($this->chartval2); ?>;
    var chartval21 = <?php echo json_encode($this->chartval21); ?>;
    var chartval22 = <?php echo json_encode($this->chartval22); ?>;

    var options1 = {
		chart: {
			height: 400,
			type: 'area',
			zoom: {
				enabled: false
			},
			dropShadow: {
				enabled: true,
				opacity: 0.2,
			},
			toolbar: {
			  show: false
			},
			events: {
			  mounted: function(ctx, config) {
				const highest1 = ctx.getHighestValueInSeries(0);
				const highest2 = ctx.getHighestValueInSeries(1);
				ctx.addPointAnnotation({
				  x: new Date(ctx.w.globals.seriesX[0][ctx.w.globals.series[0].indexOf(highest1)]).getTime(),
				  y: highest1,
				  label: {
						style: {
						  cssClass: 'd-none'
						}
					},
					  customSVG: {
						  SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#661fd6" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
						  cssClass: undefined,
						  offsetX: -8,
						  offsetY: 5
						}
					})
					ctx.addPointAnnotation({
					  x: new Date(ctx.w.globals.seriesX[1][ctx.w.globals.series[1].indexOf(highest2)]).getTime(),
					  y: highest2,
					  label: {
						style: {
						  cssClass: 'd-none'
						}
					  },
					  customSVG: {
						  SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f7b731" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
						  cssClass: undefined,
						  offsetX: -8,
						  offsetY: 5
					  }
					})
				},
			}
		},
		colors: ['#fd5261','#525ce5'],
        
		dataLabels: {
		  enabled: false
		},
		stroke: {
		  show: true,
		  curve: 'smooth',
		  width: 2,
		  lineCap: 'square'
		},
		series: [{
		  name: '<?php echo $this->lang->line('sales_income'); ?>',
		  data: chartval1
		}, {
		  name: '<?php echo $this->lang->line('sales_expenses'); ?>',
		  data: chartval2
		}],
		labels: datasalesName,
		xaxis: {
			axisBorder: {
			  show: false
			},
			axisTicks: {
			  show: false
			},
			crosshairs: {
			  show: true
			},
			labels: {
			  offsetX: 0,
			  offsetY: 5,
			}
		},
		yaxis: {
			labels: {
			  offsetX: -2,
			  offsetY: 0,
			}
		},
		grid: {
			borderColor: 'rgba(112, 131, 171, .1)',
			xaxis: {
				lines: {
					show: true
				}
			},
			yaxis: {
				lines: {
					show: false,
				}
			},
			padding: {
			  top: 0,
			  right: 0,
			  bottom: 0,
			  left: 0
			},
		},
		legend: {
			position: 'top',
		},
		tooltip: {
			theme: 'dark',
			marker: {
			  show: true,
			},
			x: {
			  show: false,
			}
		},
		fill: {
		  type:"gradient",
		  gradient: {
			  type: "vertical",
			  shadeIntensity: 1,
			  inverseColors: !1,
			  opacityFrom: .28,
			  opacityTo: .05,
			  stops: [45, 100]
		  }
		},
	}
    var options2 = {
		chart: {
			height: 400,
			type: 'area',
			zoom: {
				enabled: false
			},
			dropShadow: {
				enabled: true,
				opacity: 0.2,
			},
			toolbar: {
			  show: false
			},
			events: {
			  mounted: function(ctx, config) {
				const highest1 = ctx.getHighestValueInSeries(0);
				const highest2 = ctx.getHighestValueInSeries(1);
				ctx.addPointAnnotation({
				  x: new Date(ctx.w.globals.seriesX[0][ctx.w.globals.series[0].indexOf(highest1)]).getTime(),
				  y: highest1,
				  label: {
						style: {
						  cssClass: 'd-none'
						}
					},
					  customSVG: {
						  SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#661fd6" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
						  cssClass: undefined,
						  offsetX: -8,
						  offsetY: 5
						}
					})
					ctx.addPointAnnotation({
					  x: new Date(ctx.w.globals.seriesX[1][ctx.w.globals.series[1].indexOf(highest2)]).getTime(),
					  y: highest2,
					  label: {
						style: {
						  cssClass: 'd-none'
						}
					  },
					  customSVG: {
						  SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f7b731" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
						  cssClass: undefined,
						  offsetX: -8,
						  offsetY: 5
					  }
					})
				},
			}
		},
		colors: [ '#fd5261','#525ce5'],
		dataLabels: {
		  enabled: false
		},
		stroke: {
		  show: true,
		  curve: 'smooth',
		  width: 2,
		  lineCap: 'square'
		},
		series: [{
		  name: '<?php echo $this->lang->line('sales_receivable'); ?>',
		  data: chartval21
		}, {
		  name: '<?php echo $this->lang->line('sales_payable'); ?>',
		  data: chartval22
		}],
		labels: chartName2,
		xaxis: {
			axisBorder: {
			  show: false
			},
			axisTicks: {
			  show: false
			},
			crosshairs: {
			  show: true
			},
			labels: {
			  offsetX: 0,
			  offsetY: 5,
			}
		},
		yaxis: {
			labels: {
			  offsetX: -2,
			  offsetY: 0,
			}
		},
		grid: {
			borderColor: 'rgba(112, 131, 171, .1)',
			xaxis: {
				lines: {
					show: true
				}
			},
			yaxis: {
				lines: {
					show: false,
				}
			},
			padding: {
			  top: 0,
			  right: 0,
			  bottom: 0,
			  left: 0
			},
		},
		legend: {
			position: 'top',
		},
		tooltip: {
			theme: 'dark',
			marker: {
			  show: true,
			},
			x: {
			  show: false,
			}
		},
		fill: {
		  type:"gradient",
		  gradient: {
			  type: "vertical",
			  shadeIntensity: 1,
			  inverseColors: !1,
			  opacityFrom: .28,
			  opacityTo: .05,
			  stops: [45, 100]
		  }
		}
	}
	var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
    chart1.render();

	var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
    chart2.render();

    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chart6", am4charts.PieChart);
        
        chart.logo.height = -120;
        chart.innerRadius = am4core.percent(50);
        chart.data = cart6;
        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "qty";
            pieSeries.dataFields.category = "name";

        // var colors = ["#E53935","#C2185B","#7B1FA2","#512DA8","#303F9F","#00FF00"];
        var colors = ["#fec345","#ff7e79","#512DA8","#6373ed","#303F9F","#00FF00"];
        
        var colorset = new am4core.ColorSet();
            colorset.list = [];
        for(var i=0;i<colors.length;i++)
            colorset.list.push(new am4core.color(colors[i]));
            pieSeries.colors = colorset;
            pieSeries.colors = colorset;

            pieSeries.ticks.template.disabled = true;
            pieSeries.alignLabels = false;
            pieSeries.labels.template.text = "";
            pieSeries.labels.template.radius = am4core.percent(-40);
            pieSeries.labels.template.fill = am4core.color("white");
            pieSeries.labels.template.relativeRotation = 90;
            chart.legend = new am4charts.Legend();
            chart.legend.position = "right";
            var markerTemplate = chart.legend.markers.template;
                markerTemplate.width = 10;
                markerTemplate.height = 10;
    });
</script>