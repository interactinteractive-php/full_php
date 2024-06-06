var pvAmcharts = function () {
    
    var mixedDialy = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        // generate data
        var chartData = [];

        function generateChartData() {
            var firstDate = new Date();
            firstDate.setTime(firstDate.getTime() - 10 * 24 * 60 * 60 * 1000);

            for (var i = firstDate.getTime(); i < (firstDate.getTime() + 10 * 24 * 60 * 60 * 1000); i += 60 * 60 * 1000) {
                var newDate = new Date(i);

                if (i == firstDate.getTime()) {
                    var value1 = Math.round(Math.random() * 10) + 1;
                } else {
                    var value1 = Math.round(chartData[chartData.length - 1].value1 / 100 * (90 + Math.round(Math.random() * 20)) * 100) / 100;
                }

                if (newDate.getHours() == 12) {
                    // we set daily data on 12th hour only
                    var value2 = Math.round(Math.random() * 12) + 1;
                    chartData.push({
                        date: newDate,
                        value1: value1,
                        value2: value2
                    });
                } else {
                    chartData.push({
                        date: newDate,
                        value1: value1
                    });
                }
            }
        }

        generateChartData();

        var chart = AmCharts.makeChart(container, {
            "type": "serial",
            "theme": "light",
            "marginRight": 80,
            "dataProvider": chartData,
            "valueAxes": [{
                "axisAlpha": 0.1
            }],

            "graphs": [{
                "balloonText": "[[title]]: [[value]]",
                "columnWidth": 20,
                "fillAlphas": 1,
                "title": "daily",
                "type": "column",
                "valueField": "value2"
            }, {
                "balloonText": "[[title]]: [[value]]",
                "lineThickness": 2,
                "title": "intra-day",
                "valueField": "value1"
            }],
            "zoomOutButtonRollOverAlpha": 0.15,
            "chartCursor": {
                "categoryBalloonDateFormat": "MMM DD JJ:NN",
                "cursorPosition": "mouse",
                "showNextAvailable": true
            },
            "autoMarginOffset": 5,
            "columnWidth": 1,
            "categoryField": "date",
            "categoryAxis": {
                "minPeriod": "hh",
                "parseDates": true
            },
            "export": {
                "enabled": true
            }
        });
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
    }
    
    var cylinderGauge = function(container, data, config, metaDataId, drillDown, mainMetaDataId) {
        var chart = AmCharts.makeChart(container, {
            "theme": "light",
            "type": "serial",
            "depth3D": 20,
            "angle": 500,
            "autoMargins": false,
            "marginBottom": 100,
            "marginLeft": 150,
            "marginRight": 100,
            "dataProvider": data,
            "valueAxes": [ {
              "stackType": "100%",
              "gridAlpha": 0
            } ],
            "graphs": config.graphs,
            "categoryField": 'title',
            "categoryAxis": {
              "axisAlpha": 0,
              "labelOffset": 40,
              "gridAlpha": 0
            },
            "export": {
              "enabled": true
            }
        });
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var combined = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        var chart = AmCharts.makeChart(container, {
            "type": "serial",
            "theme": "light",
            "dataDateFormat": "YYYY-MM-DD",
            "precision": 2,
            "valueAxes": [{
              "id": "v1",
              "title": " ",
              "position": "left",
              "autoGridCount": false,
              "labelFunction": function(value) {
                return "$" + Math.round(value);
              }
            }, {
              "id": "v2",
              "title": " ",
              "gridAlpha": 0,
              "position": "right",
              "autoGridCount": false
            }],
            "graphs": config.graphs,
            "chartScrollbar": {
              "graph": "g1",
              "oppositeAxis": false,
              "offset": 30,
              "scrollbarHeight": 50,
              "backgroundAlpha": 0,
              "selectedBackgroundAlpha": 0.1,
              "selectedBackgroundColor": "#888888",
              "graphFillAlpha": 0,
              "graphLineAlpha": 0.5,
              "selectedGraphFillAlpha": 0,
              "selectedGraphLineAlpha": 1,
              "autoGridCount": true,
              "color": "#AAAAAA"
            },
            "chartCursor": {
              "pan": true,
              "valueLineEnabled": true,
              "valueLineBalloonEnabled": true,
              "cursorAlpha": 0,
              "valueLineAlpha": 0.2
            },
            "categoryField": config.xAxisName,
            "categoryAxis": {
              "parseDates": true,
              "dashLength": 1,
              "minorGridEnabled": true
            },
            "legend": {
              "useGraphSettings": true,
              "position": "top"
            },
            "balloon": {
              "borderThickness": 1,
              "shadowAlpha": 0
            },
            "export": {
             "enabled": true
            },
            "dataProvider": data
        });
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var zoomable = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        var chart = AmCharts.makeChart(container, {
            "type": "serial",
            "theme": "light",
            "marginRight": 40,
            "marginLeft": 40,
            "autoMarginOffset": 20,
            "dataDateFormat": "YYYY-MM-DD",
            "valueAxes": [{
                "id": "v1",
                "axisAlpha": 0,
                "position": "left",
                "ignoreAxisWidth":true
            }],
            "balloon": {
                "borderThickness": 1,
                "shadowAlpha": 0,
            },
            "graphs": config.graphs,
            "chartCursor": {
                "valueLineEnabled": true,
                "valueLineBalloonEnabled": true,
                "cursorAlpha":0,
                "zoomable":false,
                "valueZoomable":true,
                "valueLineAlpha":0.5
            },
            "valueScrollbar":{
             "autoGridCount":true,
              "color":"#000000",
              "scrollbarHeight":50
            },
            "categoryField": config.xAxisName,
            "categoryAxis": {
                "parseDates": true,
                "dashLength": 1,
                "minorGridEnabled": true
            },
            "dataProvider": data
        });
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var trendLine = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        var chart = AmCharts.makeChart(container, {
            "type": "serial",
            "theme": "light",
            "marginRight":80,
            "autoMarginOffset":20,
            "dataDateFormat": "YYYY-MM-DD HH:NN",
            "dataProvider": data,
            "valueAxes": [{
                "axisAlpha": 0,
                "guides": [{
                    "fillAlpha": 0.1,
                    "fillColor": "#888888",
                    "lineAlpha": 0,
                    "toValue": 16,
                    "value": 10
                }],
                "position": "left",
                "tickLength": 0
            }],
            "graphs": config.graphs,
            "trendLines": config.trendLines,
            "chartScrollbar": {
                "scrollbarHeight":2,
                "offset":-1,
                "backgroundAlpha":0.1,
                "backgroundColor":"#888888",
                "selectedBackgroundColor":"#67b7dc",
                "selectedBackgroundAlpha":1
            },
            "chartCursor": {
                "fullWidth":true,
                "valueLineEabled":true,
                "valueLineBalloonEnabled":true,
                "valueLineAlpha":0.5,
                "cursorAlpha":0
            },
            "categoryField": config.xAxisName,
            "categoryAxis": {
                "parseDates": true,
                "axisAlpha": 0,
                "gridAlpha": 0.1,
                "minorGridAlpha": 0.1,
                "minorGridEnabled": true
            },
            "export": {
                "enabled": true
             }
        });
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var reverse = function(container, data, config, metaDataId, drillDown, mainMetaDataId) {
      var chart = AmCharts.makeChart(container, {
          "type": "serial",
          "theme": "light",
          "legend": {
              "useGraphSettings": true
          },
          "dataProvider": data,
          "valueAxes": [{
              "integersOnly": true,
              "minimum": 0.1,
              "reversed": false,
              "axisAlpha": 0,
              "dashLength": 5,
              "gridCount": 10,
              "position": "left",
              "title": " "
          }],
          "startDuration": 0.5,
          "graphs": config.graphs,
          "chartCursor": {
              "cursorAlpha": 0,
              "zoomable": false
          },
          "categoryField": config.xAxisName,
          "categoryAxis": {
              "gridPosition": "start",
              "axisAlpha": 0,
              "fillAlpha": 0.05,
              "fillColor": "#000000",
              "gridAlpha": 0,
              "position": "top",
              "labelRotation": config.xLabelRotation
          },
          exportConfig: {
            "menu": [ {
                "class": "export-main",
                "format": "PRINT"
            } ]
          }
      });
      
      if ((config['cTheme']).length > 1)
          chart.colors = (config['cTheme']).split(' '); 
      
      if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
      }
      
      $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var treedFunnel = function (container, data, config, metaDataId, drillDown, mainMetaDataId)  {
        var chart = AmCharts.makeChart( container, {
            "type": "funnel",
            "theme": "light",
            "dataProvider": data,
            "balloon": {
              "fixedPosition": true
            },
            "titleField": config.xAxisName,
            "valueField": config.yAxisName,
            "marginRight": 240,
            "marginLeft": 50,
            "startX": -500,
            "depth3D": 100,
            "angle": 40,
            "outlineAlpha": 1,
            "outlineColor": "#FFFFFF",
            "outlineThickness": 2,
            "labelPosition": "right",
            "balloonText": "[[title]]: [[value]]n[[description]]",
            "export": {
              "enabled": true
            }
        });
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    } 
    
    var funnel = function (container, data, config, metaDataId, drillDown, mainMetaDataId)  {
        var chart = AmCharts.makeChart( container, {
            "type": "funnel",
            "theme": "light",
            "dataProvider": data,
            "balloon": {
              "fixedPosition": true
            },
            "titleField": config.xAxisName,
            "valueField": config.yAxisName,
            "marginRight": 160,
            "marginLeft": 15,
            "labelPosition": "right",
            "funnelAlpha": 0.9,
            "startX": 0,
            "neckWidth": "40%",
            "startAlpha": 0,
            "outlineThickness": 1,
            "neckHeight": "30%",
            "balloonText": "[[title]]:<b>[[value]]</b>",
            "export": {
              "enabled": true
            }
        });
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    } 
    
    var stacked = function(container, data, config, metaDataId, drillDown, mainMetaDataId) {
      
      var chart = AmCharts.makeChart(container, {
          "type": "serial",
          "theme": "light",
          "legend": {
              "horizontalGap": 10,
              "maxColumns": 1,
              "position": "bottom",
              "useGraphSettings": true,
              "markerSize": 10
          },
          "dataProvider": data,
          "valueAxes": [{
              "stackType": "regular",
              "axisAlpha": 0.5,
              "gridAlpha": 0
          }],
          "graphs": config.graphs,
          "rotate": true,
          "categoryField": config.xAxisName,
          "categoryAxis": {
              "gridPosition": "start",
              "axisAlpha": 0,
              "gridAlpha": 0,
              "position": "left"
          },
          exportConfig: {
            "menu": [ {
                "class": "export-main",
                "format": "PRINT"
            } ]
          }
      });
      
      if ((config['cTheme']).length > 1)
          chart.colors = (config['cTheme']).split(' '); 
      
      if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
      }
      
      $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var radar = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        var chart = new AmCharts.makeChart(container, {
            "type": "radar",
            "theme": "light",
            "dataProvider": data,
            "valueAxes": [],
            "startDuration": 1,
            "graphs": [],
            "categoryField": config.xAxisName,
            "categoryAxis": {
              "gridPosition": "start",
              "axisAlpha": 0,
              "tickLength": 0,
              "labelRotation": config.xLabelRotation,
            },
            exportConfig: {
              "menu": [ {
                  "class": "export-main",
                  "format": "PRINT"
              } ]
            },
            "legend": {},
            "labelsEnabled": true,
            "inside": true
            
        });
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
        
        $.each(config.graphs, function(i, dtl) {
            var graph = new AmCharts.AmGraph();
            graph.title = dtl.title;
            graph.valueField = dtl.valueField;
            graph.balloonText= "[[value]]",
            graph.bullet = "round",
            graph.fillAlphas= 0.1,
            graph.axisTitleOffset = 20,
            graph.minimum=  0,
            graph.axisAlpha= 0.15
            graph.lineThickness = 1.5,
            chart.addGraph(graph);
        });
        
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
          
        if (parseFloat(drillDown) > 0) {
            if (typeof noDrillDown == 'undefined') {
                chart.addListener("clickGraphItem", function(event) {
                    isSubChart = 1;
                    selectedChartEvent = event;
                    ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
                });
            }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var cylinder = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        var chart = AmCharts.makeChart(container, {
            "theme": "light",
            "type": "serial",
            "rotate": config.isBar,
            "startDuration": 2,
            "dataProvider": data,
            "graphs": config.graphs,
            "categoryField": config.xAxisName,
            "depth3D": 40,
            "angle": 30,
            "categoryAxis": {
                "gridPosition": "start",
                "labelRotation": config.xLabelRotation
            },
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },    
            exportConfig: {
              "menu": [ {
                  "class": "export-main",
                  "format": "PRINT"
              } ]
            }

        });
        
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
        
        if (parseFloat(drillDown) > 0) {
            if (typeof noDrillDown == 'undefined') {
                chart.addListener("clickGraphItem", function(event) {
                    isSubChart = 1;
                    selectedChartEvent = event;
                    ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
                });
            }
        }

        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }

    var bar = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        var chart = AmCharts.makeChart(container, {
            "type": "serial",
            "theme": "light",
            "categoryField": config.xAxisName,
            "rotate": config.isBar,
            "startDuration": 0.7,
            "sequencedAnimation": true,
            "categoryAxis": {
                "gridPosition": "start",
                "position": "left"
            },
            "trendLines": [],
            "graphs": config.graphs,
            "guides": [],
            "valueAxes": [
                {
                    "id": "ValueAxis-1",
                    "position": "top",
                    "axisAlpha": 0
                }
            ],
            "allLabels": [],
            "balloon": {},
            "titles": [],
            "dataProvider": data,
            exportConfig: {
              "menu": [ {
                  "class": "export-main",
                  "format": "PRINT"
              } ]
            }

        });
        
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
        
        if (parseFloat(drillDown) > 0) {
            if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });  
            }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }

    var donut = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        data = getDataGrouping(data, config);
        
        var chart = AmCharts.makeChart(container, {
            "type": "pie",
            "theme": "light",
            "legend": {
                "position":"bottom",
                "autoMargins":false,
            },
            "dataProvider": data,
            "titleField": config.xAxisName,
            "valueField": config.yAxisName,
            "labelRadius": 5,
            "radius": "42%",
            "innerRadius": "60%",
            "startDuration": 0,
            "labelText": "[[title]] ([[percents]]%)",
            "sequencedAnimation": true,
            exportConfig: {
              "menu": [ {
                  "class": "export-main",
                  "format": "PRINT"
              } ]
            }
        });
        
        chart.addListener("init", handleInit);

        chart.addListener("rollOverSlice", function(e) {
            handleRollOver(e);
        });

        function handleInit(){
            chart.legend.addListener("rollOverItem", handleRollOver);
        }

        function handleRollOver(e){
            var wedge = e.dataItem.wedge.node;
            wedge.parentNode.appendChild(wedge);  
        }
        
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
        
        if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
        }
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }

    var pie = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
        data = getDataGrouping(data, config);
      
        var chart = AmCharts.makeChart(container, {
            "type": "pie",
            "theme": "light",
            "dataProvider": data,
            "startDuration": 0.1,
            "titleField": config.xAxisName,
            "valueField": config.yAxisName,
            "sequencedAnimation": true,
            "balloon": {
                "fixedPosition": true
            },
            exportConfig: {
              "menu": [ {
                  "class": "export-main",
                  "format": "PRINT"
              } ]
            }

        });
        
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
          
        if (parseFloat(drillDown) > 0) {
            if (typeof noDrillDown == 'undefined') {
                chart.addListener("clickGraphItem", function(event) {
                    isSubChart = 1;
                    selectedChartEvent = event;
                    ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
                });
            }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var dual = function (container, data, config, metaDataId, dataChart, drillDown, mainMetaDataId) {
        var chart = new AmCharts.makeChart(container, {
            "type": "serial",
            "addClassNames": true,
            "theme": "light",
            "autoMargins": false,
            "width" : '100%',
            "marginLeft": 100,
            "marginRight": 0,
            "marginTop": 10,
            "marginBottom": 26,
            "sequencedAnimation": true,
            "balloon": {
              "adjustBorderColor": false,
              "horizontalPadding": 10,
              "verticalPadding": 8,
              "color": "#ffffff"
            },
            "dataProvider": data,
            "valueAxes": [{
              "axisAlpha": 0,
              "position": "left"
            }],
            "startDuration": 0.7,
            "graphs": [],
            "categoryField": config.xAxisName,
            "categoryAxis": {
              "gridPosition": "start",
              "axisAlpha": 0,
              "tickLength": 0,
              "labelRotation": config.xLabelRotation,
            },
            exportConfig: {
              "menu": [ {
                  "class": "export-main",
                  "format": "PRINT"
              } ]
            }
            
        });
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
        
        $.each(config.graphs, function(i, dtl) {
            var graph = new AmCharts.AmGraph();
            graph.title = dtl.title;
            graph.valueField = dtl.valueField;
            if (typeof dtl.bullet != 'undefined') {
                graph.bullet = dtl.bullet;
                graph.bulletBorderAlpha = dtl.bulletBorderAlpha;
                graph.bulletBorderThickness = dtl.bulletBorderThickness;
                graph.bulletColor = dtl.bulletColor;
                graph.bulletSize = dtl.bulletSize;
                graph.fillAlphas = dtl.fillAlphas;
                graph.id = dtl.id;
                graph.lineAlpha = dtl.lineAlpha;
                graph.lineThickness = dtl.lineThickness;
                graph.useLineColorForBulletBorder = dtl.useLineColorForBulletBorder;
                graph.lineAlpha = dtl.lineAlpha;
            } else {
                graph.type = dtl.type;  
                graph.fillAlphas = dtl.fillAlphas;  
                graph.dashLengthField = dtl.dashLengthField;  
                graph.balloonText = dtl.balloonText;  
                graph.alphaField = dtl.alphaField;  
            }
              
            chart.addGraph(graph);
        });
        
        if ((config['cTheme']).length > 1)
            chart.colors = (config['cTheme']).split(' '); 
          
        if (parseFloat(drillDown) > 0) {
            if (typeof noDrillDown == 'undefined') {
                chart.addListener("clickGraphItem", function(event) {
                    isSubChart = 1;
                    selectedChartEvent = event;
                    ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
                });
            }
        }
        
        $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    }
    
    var threeDStackedClustered = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {

      if(typeof data !== "undefined"){
        var tmpData = {},
                isGrouped = false,
                tmpDataGrouped = [];

          $.each(data, function(key, value){
            if(typeof tmpData[value[config.xAxisName]] === "undefined"){
              tmpData[value[config.xAxisName]] = {};
              $.each(config.yAxisName.split(','), function(splitedKey, splitedValue){
                var yAxisNameSplitedMore = splitedValue.split('_');
                if(typeof yAxisNameSplitedMore[1] !== "undefined"){
                  tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]] = !isNaN(value[yAxisNameSplitedMore[1]]) ? value[yAxisNameSplitedMore[1]] : 0;
                }
              });
            }else{
              $.each(config.yAxisName.split(','), function(splitedKey, splitedValue){
                var yAxisNameSplitedMore = splitedValue.split('_');
                if(typeof yAxisNameSplitedMore[1] !== "undefined"){
                  tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]] = parseFloat(tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]]) + parseFloat(value[yAxisNameSplitedMore[1]]);
                }
              });
              
              isGrouped = true;
            }
          });

        if(isGrouped){
            var cnt = 0;
            $.each(tmpData, function(key, value){
                tmpDataGrouped[cnt] = {};
                tmpDataGrouped[cnt][config.xAxisName] = key;
                $.each(config.yAxisName.split(','), function(splitedKey, splitedValue){
                  var yAxisNameSplitedMore = splitedValue.split('_');
                  if(typeof yAxisNameSplitedMore[1] !== "undefined"){
                     tmpDataGrouped[cnt][yAxisNameSplitedMore[1]] = !isNaN(value[yAxisNameSplitedMore[1]]) ? value[yAxisNameSplitedMore[1]] : 0;
                  }
                });
              
                cnt++;
            });

            data = tmpDataGrouped;
          }
      }
       
      var chart = AmCharts.makeChart(container, {
          "theme": "light",
          "type": "serial",
          "valueAxes": [{
              "stackType": "3d",
              "position": "left",
          }],
          "startDuration": 1,
          "dataProvider": data,
          "graphs": config.graphs,
          "plotAreaFillAlphas": 0.1,
          "depth3D": 60,
          "angle": 30,
          "categoryField": config.xAxisName,
          "categoryAxis": {
              "gridPosition": "start",
              "labelRotation": config.xLabelRotation
          },
          "chartCursor": {
              "enabled": true
          },
          "chartScrollbar": {
              "enabled": true
          },
          exportConfig: {
            "menu": [ {
                "class": "export-main",
                "format": "PRINT"
            } ]
          }
      });
      
      if ((config['cTheme']).length > 1)
          chart.colors = (config['cTheme']).split(' '); 
      
      if (parseFloat(drillDown) > 0) {
          if (typeof noDrillDown == 'undefined') {
              chart.addListener("clickGraphItem", function(event) {
                  isSubChart = 1;
                  selectedChartEvent = event;
                  ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
              });
          }
      }
      
      $('#dialog-dashboard-'+mainMetaDataId).height($('.amcharts-main-div').height()+100);
    };
        
    var getDataGrouping = function(data, config){
      if(typeof data !== "undefined"){
        var tmpData = {},
                 isGrouped = false,
                 tmpDataGrouped = [];

           $.each(data, function(key, value){
             if(typeof tmpData[value[config.xAxisName]] === "undefined"){
               tmpData[value[config.xAxisName]] = !isNaN(value[config.yAxisName]) ? value[config.yAxisName] : 0;
             }else{
               tmpData[value[config.xAxisName]] = parseFloat(tmpData[value[config.xAxisName]])+value[config.yAxisName];
               isGrouped = true;
             }
           });

           if(isGrouped){
             var cnt = 0;
             $.each(tmpData, function(key, value){
                 tmpDataGrouped[cnt] = {};
                 tmpDataGrouped[cnt][config.xAxisName] = key;
                 tmpDataGrouped[cnt][config.yAxisName] = !isNaN(value) ? value : 0;
                 cnt++;
             });

             data = tmpDataGrouped;
           }
       }
       
       return data;
    };
    
    var pvserial = function (pvdashboardData, divContent) {
        var serial = AmCharts.makeChart(divContent, {
            "type": "serial",
            "theme": "light",
            "marginRight": 40,
            "marginLeft": 40,
            "autoMarginOffset": 20,
            "dataDateFormat": "YYYY-MM-DD",
            "sequencedAnimation": true,
            "valueAxes": [{
                    "id": "v1",
                    "axisAlpha": 0,
                    "position": "left",
                    "ignoreAxisWidth": true
                }],
            "balloon": {
                "borderThickness": 1,
                "shadowAlpha": 0
            },
            "graphs": [{
                "id": "g1",
                "balloon": {
                    "drop": true,
                    "adjustBorderColor": false,
                    "color": "#ffffff"
                },
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "bulletSize": 5,
                "hideBulletsCount": 50,
                "lineThickness": 2,
                "title": "red line",
                "useLineColorForBulletBorder": true,
                "valueField": pvdashboardData.yAxisName,
                "balloonText": "<span style='font-size:10px;'>[[value]]</span>"
            }],
            "chartScrollbar": {
                "graph": "g1",
                "oppositeAxis": false,
                "offset": 30,
                "scrollbarHeight": 80,
                "backgroundAlpha": 0,
                "selectedBackgroundAlpha": 0.1,
                "selectedBackgroundColor": "#888888",
                "graphFillAlpha": 0,
                "graphLineAlpha": 0.5,
                "selectedGraphFillAlpha": 0,
                "selectedGraphLineAlpha": 1,
                "autoGridCount": true,
                "color": "#AAAAAA"
            },
            "chartCursor": {
                "pan": true,
                "valueLineEnabled": true,
                "valueLineBalloonEnabled": true,
                "cursorAlpha": 1,
                "cursorColor": "#258cbb",
                "limitToGraph": "g1",
                "valueLineAlpha": 0.2
            },
            "valueScrollbar": {
                "oppositeAxis": false,
                "offset": 50,
                "scrollbarHeight": 10
            },
            "categoryField": pvdashboardData.xAxisName,
            "categoryAxis": {
                "parseDates": false,
                "dashLength": 1,
                "minorGridEnabled": true
            },
            "export": {
                "enabled": true,
                "menu": [{
                  "class": "export-main",
                  "format": "PRINT"
                }]
            },
            "dataProvider": pvdashboardData.data
        });
    }

    var pvcolumn = function (pvdashboardData, divContent) {
        var chart = AmCharts.makeChart(divContent, {
            "type": "serial",
            "theme": "light",
//            "rotate": config.isBar,
            "startDuration": 0.7,
            "dataProvider": pvdashboardData.data,
            "sequencedAnimation": true,
            "gridAboveGraphs": true,
            "graphs": [ {
                    "balloonText": "[[category]]: <b>[[value]]</b>",
                    "fillAlphas": 0.8,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "valueField": pvdashboardData.yAxisName
            } ],
            "categoryField": pvdashboardData.xAxisName,
            "categoryAxis": {
                "gridPosition": "start",
                "labelRotation": '15'
            },
            "export": {
                "enabled": true,
                "menu": [{
                  "class": "export-main",
                  "format": "PRINT"
                }]
            },

        });
    }
    
    var pvclustered = function (pvdashboardData, divContent) {
        var graphs = [];
        var yAxisArr = pvdashboardData.yAxisName.split(',');
        
        $.each(yAxisArr, function (index, row) {
            var graphsTemp = {
                "balloonText": pvdashboardData.xAxisName + ': ' + row + "=[[value]]",
                "fillAlphas": 0.8,
                "id": "AmGraph-" + index,
                "lineAlpha": 0.2,
                "title": row,
                "type": "column",
                "valueField": row
            };
            graphs.push(graphsTemp);
        });
        
        var chart = new AmCharts.makeChart(divContent, {
            "type": "serial",
            "addClassNames": true,
            "theme": "light",
            "autoMargins": true,
            "rotate": false,
            "width" : '100%',
            "marginLeft": 100,
            "marginRight": 0,
            "marginTop": 10,
            "marginBottom": 26,
            "sequencedAnimation": true,
            "graphs": graphs,
            "balloon": {},
            "valueAxes": [{
              "axisAlpha": 0,
              "position": "left"
            }],
            "startDuration": 0.7,
            "dataProvider": pvdashboardData.data,
            "categoryField": pvdashboardData.xAxisName,
            "categoryAxis": {
                "gridPosition": "start",
                "labelRotation": '15'
            },
            "export": {
                "enabled": true,
                "menu": [{
                  "class": "export-main",
                  "format": "PRINT"
                }]
            },
            
        });
    }
    
    return {
        renderPvDashboard: function (pvdashboardData, divContent) {
            switch (pvdashboardData.charttype) {
                case 'am_serial' : {
                    pvserial(pvdashboardData, divContent);
                    break;
                }
                case 'am_column' : {
                    pvcolumn(pvdashboardData, divContent);
                    break;
                }
                case 'clustered_bar_chart' : {
                    pvclustered(pvdashboardData, divContent);
                    break;
                }
                case 'am_radar_chart' : {
                    radar(pvdashboardData, divContent);
                    break;
                }
                case 'am_threed_cylinder_chart' : {
                    cylinder(pvdashboardData, divContent);
                    break;
                }
                case 'am_bar' : {
                    bar(pvdashboardData, divContent);
                    break;
                }
                case 'am_donut' : {
                    donut(pvdashboardData, divContent);
                    break;
                }
                case 'am_pie' : {
                    pie(pvdashboardData, divContent);
                    break;
                }
                case 'am_dual' : {
                    dual(pvdashboardData, divContent);
                    break;
                }
                case 'am_stacked_bar_chart' : {
                    stacked(pvdashboardData, divContent);
                    break;
                }
                case 'am_reversed' : {
                    reverse(pvdashboardData, divContent);
                    break;
                }
                case 'am_zoomable_value_axis' : {
                    zoomable(pvdashboardData, divContent);
                    break;
                }
                case 'am_trend_lines' : {
                    trendLine(pvdashboardData, divContent);
                    break;
                }
                case 'am_threed_funnel' : {
                    treedFunnel(pvdashboardData, divContent);
                    break;
                }
                case 'am_funnel' : {
                    funnel(pvdashboardData, divContent);
                    break;
                }
                case 'am_cylinder_gauge' : {
                    cylinderGauge(pvdashboardData, divContent);
                    break;
                }
                case 'am_combined_bullet' : {
                    combined(pvdashboardData, divContent);
                    break;
                }
                case 'am_mixed_dialy_and_intra_day_chart' : {
                    mixedDialy(pvdashboardData, divContent);
                    break;
                }
                case 'am_3d_stacked_column_chart' : {
                    threeDStackedClustered(pvdashboardData, divContent);
                    break;
                }
            }
        }
    };

}();