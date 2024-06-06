var chartData = [];
var chartNm;
var _defaultCriteriaData;
var _chartType;
var _metaDataId;
var chartContainer = "";
var chartMetaDataId = "";

var ChartsAmcharts = (function () {
  var chartTitleSeparatorSplit = function (yAxisName) {
    var yAxisArray = [];

    if (yAxisName) {
      if (yAxisName.indexOf("|$|") !== -1) {
        yAxisArray = yAxisName.split("|$|");
      } else {
        yAxisArray = yAxisName.split(",");
      }
    }

    return yAxisArray;
  };

  var gauge = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var gaugeChart = AmCharts.makeChart(container, {
      type: "gauge",
      theme: "light",
      axes: [
        {
          axisThickness: 1,
          axisAlpha: 0.2,
          tickAlpha: 0.2,
          valueInterval: 20,
          bands: [
            {
              color: "#84b761",
              endValue: 90,
              startValue: 0,
            },
            {
              color: "#fdd400",
              endValue: 130,
              startValue: 90,
            },
            {
              color: "#cc4748",
              endValue: 220,
              innerRadius: "95%",
              startValue: 130,
            },
          ],
          bottomText: "0 km/h",
          bottomTextYOffset: -20,
          endValue: 220,
        },
      ],
      arrows: [{}],
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    setInterval(randomValue, 2000);

    function randomValue() {
      var value = Math.round(Math.random() * 200);
      if (gaugeChart) {
        if (gaugeChart.arrows) {
          if (gaugeChart.arrows[0]) {
            if (gaugeChart.arrows[0].setValue) {
              gaugeChart.arrows[0].setValue(value);
              gaugeChart.axes[0].setBottomText(value + " km/h");
            }
          }
        }
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var mixedDialy = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    // generate data
    var chartData = [];

    function generateChartData() {
      var firstDate = new Date();
      firstDate.setTime(firstDate.getTime() - 10 * 24 * 60 * 60 * 1000);

      for (
        var i = firstDate.getTime();
        i < firstDate.getTime() + 10 * 24 * 60 * 60 * 1000;
        i += 60 * 60 * 1000
      ) {
        var newDate = new Date(i);

        if (i == firstDate.getTime()) {
          var value1 = Math.round(Math.random() * 10) + 1;
        } else {
          var value1 =
            Math.round(
              (chartData[chartData.length - 1].value1 / 100) *
                (90 + Math.round(Math.random() * 20)) *
                100
            ) / 100;
        }

        if (newDate.getHours() == 12) {
          // we set daily data on 12th hour only
          var value2 = Math.round(Math.random() * 12) + 1;
          chartData.push({
            date: newDate,
            value1: value1,
            value2: value2,
          });
        } else {
          chartData.push({
            date: newDate,
            value1: value1,
          });
        }
      }
    }

    generateChartData();

    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      marginRight: 80,
      dataProvider: chartData,
      valueAxes: [
        {
          axisAlpha: 0.1,
        },
      ],

      graphs: [
        {
          balloonText: "[[title]]: [[value]]",
          columnWidth: 20,
          fillAlphas: 1,
          title: "daily",
          type: "column",
          valueField: "value2",
        },
        {
          balloonText: "[[title]]: [[value]]",
          lineThickness: 2,
          title: "intra-day",
          valueField: "value1",
        },
      ],
      zoomOutButtonRollOverAlpha: 0.15,
      chartCursor: {
        categoryBalloonDateFormat: "MMM DD JJ:NN",
        cursorPosition: "mouse",
        showNextAvailable: true,
      },
      autoMarginOffset: 5,
      columnWidth: 1,
      categoryField: "date",
      categoryAxis: {
        minPeriod: "hh",
        parseDates: true,
      },
      export: {
        enabled: true,
      },
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }
  };

  var cylinderGauge = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      theme: "light",
      type: "serial",
      depth3D: 20,
      angle: 500,
      autoMargins: false,
      marginBottom: 100,
      marginLeft: 150,
      marginRight: 100,
      dataProvider: data,
      valueAxes: [
        {
          stackType: "100%",
          gridAlpha: 0,
        },
      ],
      graphs: config.graphs,
      categoryField: "title",
      categoryAxis: {
        axisAlpha: 0,
        labelOffset: 40,
        gridAlpha: 0,
      },
      export: {
        enabled: true,
      },
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var angularGauge = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var xValue = parseFloat(data[0][config.xAxisName]).toFixed(2);
    var yValue = parseFloat(data[0][config.yAxisName]);

    var chart = AmCharts.makeChart(container, {
      type: "gauge",
      theme: "none",
      axes: [
        {
          axisThickness: 1,
          axisAlpha: 0.2,
          tickAlpha: 0.2,
          valueInterval: 10,
          bands: [
            {
              color: "#84b761",
              endValue: yValue,
              startValue: 0,
            },
          ],
          bottomText: "0",
          bottomTextYOffset: -20,
          endValue: yValue,
        },
      ],
      arrows: [{}],
      export: {
        enabled: true,
      },
    });

    setTimeout(function () {
      if (chart) {
        if (chart.arrows) {
          if (chart.arrows[0]) {
            if (chart.arrows[0].setValue) {
              chart.arrows[0].setValue(xValue);
              chart.axes[0].setBottomText(xValue);
            }
          }
        }
      }
    }, 600);

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var combined = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      dataDateFormat: "YYYY-MM-DD",
      precision: 2,
      valueAxes: [
        {
          id: "v1",
          title: " ",
          position: "left",
          autoGridCount: false,
          labelFunction: function (value) {
            return "$" + Math.round(value);
          },
        },
        {
          id: "v2",
          title: " ",
          gridAlpha: 0,
          position: "right",
          autoGridCount: false,
        },
      ],
      graphs: config.graphs,
      chartScrollbar: {
        graph: "g1",
        oppositeAxis: false,
        offset: 30,
        scrollbarHeight: 50,
        backgroundAlpha: 0,
        selectedBackgroundAlpha: 0.1,
        selectedBackgroundColor: "#888888",
        graphFillAlpha: 0,
        graphLineAlpha: 0.5,
        selectedGraphFillAlpha: 0,
        selectedGraphLineAlpha: 1,
        autoGridCount: true,
        color: "#AAAAAA",
      },
      chartCursor: {
        pan: true,
        valueLineEnabled: true,
        valueLineBalloonEnabled: true,
        cursorAlpha: 0,
        valueLineAlpha: 0.2,
      },
      categoryField: config.xAxisName,
      categoryAxis: {
        parseDates: true,
        dashLength: 1,
        fontSize: chartValueFontSize,
        minorGridEnabled: true,
      },
      legend: {
        useGraphSettings: true,
        fontSize: chartValueFontSize,
        position: "top",
      },
      balloon: {
        borderThickness: 1,
        shadowAlpha: 0,
      },
      export: {
        enabled: true,
      },
      dataProvider: data,
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var zoomable = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      marginRight: 40,
      marginLeft: 40,
      autoMarginOffset: 20,
      dataDateFormat: "YYYY-MM-DD",
      valueAxes: [
        {
          id: "v1",
          axisAlpha: 0,
          position: "left",
          ignoreAxisWidth: true,
        },
      ],
      balloon: {
        borderThickness: 1,
        shadowAlpha: 0,
      },
      graphs: config.graphs,
      chartCursor: {
        valueLineEnabled: true,
        valueLineBalloonEnabled: true,
        cursorAlpha: 0,
        zoomable: false,
        valueZoomable: true,
        valueLineAlpha: 0.5,
      },
      valueScrollbar: {
        autoGridCount: true,
        color: "#000000",
        scrollbarHeight: 50,
      },
      categoryField: config.xAxisName,
      categoryAxis: {
        parseDates: true,
        dashLength: 1,
        minorGridEnabled: true,
      },
      dataProvider: data,
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var trendLine = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      marginRight: 80,
      autoMarginOffset: 20,
      dataDateFormat: "YYYY-MM-DD HH:NN",
      dataProvider: data,
      valueAxes: [
        {
          axisAlpha: 0,
          guides: [
            {
              fillAlpha: 0.1,
              fillColor: "#888888",
              lineAlpha: 0,
              toValue: 16,
              value: 10,
            },
          ],
          position: "left",
          tickLength: 0,
        },
      ],
      graphs: config.graphs,
      trendLines: config.trendLines,
      chartScrollbar: {
        scrollbarHeight: 2,
        offset: -1,
        backgroundAlpha: 0.1,
        backgroundColor: "#888888",
        selectedBackgroundColor: "#67b7dc",
        selectedBackgroundAlpha: 1,
      },
      chartCursor: {
        fullWidth: true,
        valueLineEabled: true,
        valueLineBalloonEnabled: true,
        valueLineAlpha: 0.5,
        cursorAlpha: 0,
      },
      categoryField: config.xAxisName,
      categoryAxis: {
        parseDates: true,
        axisAlpha: 0,
        gridAlpha: 0.1,
        minorGridAlpha: 0.1,
        minorGridEnabled: true,
      },
      export: {
        enabled: true,
      },
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var reverse = function (
    container,
    data,
    config,
    metaDataId,
    dataChart,
    drillDown,
    mainMetaDataId,
    responseData
  ) {
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create(container, am4charts.XYChart);
    chart.data = data;

    // Create category axis
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = config.xAxisName;

    // Create value axis
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "";
    valueAxis.renderer.minLabelPosition = 0.01;

    for (var i = 0; i < config.graphs.length; i++) {
      // Create series
      var series1 = chart.series.push(new am4charts.LineSeries());
      series1.dataFields.valueY = config.graphs[i]["valueField"];
      series1.dataFields.categoryX = config.xAxisName;
      series1.name = config.graphs[i]["title"];
      series1.bullets.push(new am4charts.CircleBullet());
      series1.tooltipText = "{name}: {valueY}";
      series1.legendSettings.valueText = "{valueY}";
      series1.visible = false;

      let hs1 = series1.segments.template.states.create("hover");
      hs1.properties.strokeWidth = 5;
      series1.segments.template.strokeWidth = 1;
    }

    // Add chart cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "zoomY";

    // Add legend
    chart.legend = new am4charts.Legend();
    chart.legend.itemContainers.template.events.on("over", function (event) {
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function (segment) {
        segment.isHover = true;
      });
    });

    chart.legend.itemContainers.template.events.on("out", function (event) {
      var segments = event.target.dataItem.dataContext.segments;
      segments.each(function (segment) {
        segment.isHover = false;
      });
    });
  };

  // var reverse = function (container, data, config, metaDataId, dataChart, drillDown, mainMetaDataId, responseData) {

  //     console.log(`config.graphs`, config.graphs)
  //     var chart = AmCharts.makeChart(container, {
  //         "type": "serial",
  //         "theme": "light",
  //         "legend": {
  //             "enabled": (typeof responseData.isUseLegend !== 'undefined' && responseData.isUseLegend == '1') ? true : false,
  //             "useGraphSettings": (typeof responseData.isUseLegend !== 'undefined' && responseData.isUseLegend == '1') ? true : false,
  //             "align": "center",
  //             "equalWidths": false,
  //             "valueAlign": "left",
  //             "fontSize": chartValueFontSize,
  //             "valueWidth": 100
  //         },
  //         "dataProvider": data,
  //         "valueAxes": [{
  //             "integersOnly": true,
  //             "minimum": 0.1,
  //             "reversed": false,
  //             "axisAlpha": 0,
  //             "dashLength": 5,
  //             "gridCount": 10,
  //             "fontSize": chartValueFontSize,
  //             "position": "left",
  //             "title": config.valueAxisTitle
  //         }],
  //         "startDuration": 0.5,
  //         "graphs": config.graphs,
  //         "chartCursor": {
  //             "cursorAlpha": 0,
  //             "zoomable": false
  //         },
  //         "categoryField": config.xAxisName,
  //         "categoryAxis": {
  //             "gridPosition": "start",
  //             "axisAlpha": 0,
  //             "fillAlpha": 0.05,
  //             "fillColor": "#000000",
  //             "gridAlpha": 0,
  //             "fontSize": chartValueFontSize,
  //             "position": "bottom",
  //             "labelRotation": config.xLabelRotation,
  //             "title": config.categoryAxisTitle
  //         },
  //         exportConfig: {
  //             "menu": [{
  //                     "class": "export-main",
  //                     "format": "PRINT"
  //                 }]
  //         }
  //     });

  //     if ((config['cTheme']).length > 1)
  //         chart.colors = (config['cTheme']).split(' ');

  //     if (parseFloat(drillDown) > 0) {
  //         if (typeof noDrillDown == 'undefined') {
  //             chart.addListener("clickGraphItem", function (event) {
  //                 isSubChart = 1;
  //                 selectedChartEvent = event;
  //                 ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
  //             });
  //         }
  //     }

  //     $('#dialog-dashboard-' + mainMetaDataId).height($('.amcharts-main-div').height() + 100);
  // }

  var treedFunnel = function (
    container,
    data,
    config,
    metaDataId,
    dataChart,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "funnel",
      theme: "light",
      dataProvider: data,
      balloon: {
        fixedPosition: true,
      },
      titleField: config.xAxisName,
      valueField: config.yAxisName,
      marginRight: 240,
      marginLeft: 50,
      startX: -500,
      depth3D: 100,
      angle: 40,
      outlineAlpha: 1,
      outlineColor: "#FFFFFF",
      outlineThickness: 2,
      labelPosition: "right",
      balloonText: "[[title]]: [[value]]n[[description]]",
      export: {
        enabled: true,
      },
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickSlice", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var funnel = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "funnel",
      theme: "light",
      dataProvider: data,
      balloon: {
        fixedPosition: true,
      },
      titleField: config.xAxisName,
      valueField: config.yAxisName,
      marginRight: 160,
      marginLeft: 15,
      labelPosition: "right",
      funnelAlpha: 0.9,
      startX: 0,
      neckWidth: "40%",
      startAlpha: 0,
      outlineThickness: 1,
      neckHeight: "30%",
      balloonText: "[[title]]:<b>[[value]]</b>",
      export: {
        enabled: true,
      },
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var stackedColumn = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      legend: {
        horizontalGap: 10,
        fontSize: chartValueFontSize,
        maxColumns: 1,
        position: config.legendPosition,
        useGraphSettings: true,
        markerSize: 10,
      },
      dataProvider: data,
      valueAxes: [
        {
          stackType: "100%",
          axisAlpha: 0.5,
          gridAlpha: 0,
          fontSize: chartValueFontSize,
        },
      ],
      graphs: config.graphs,
      categoryField: config.xAxisName,
      categoryAxis: {
        gridPosition: "start",
        axisAlpha: 0,
        gridAlpha: 0,
        position: "left",
        fontSize: chartValueFontSize,
      },
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  //   var stacked = function (
  //     container,
  //     data,
  //     config,
  //     metaDataId,
  //     drillDown,
  //     mainMetaDataId
  //   ) {
  //     am4core.useTheme(am4themes_animated);
  //     var chart = am4core.create(container, am4charts.XYChart);

  //     chart.data = data;

  //     var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
  //     categoryAxis.dataFields.category = config.xAxisName;
  //     categoryAxis.title.text = "";
  //     categoryAxis.renderer.grid.template.location = 0;
  //     categoryAxis.renderer.minGridDistance = 20;
  //     categoryAxis.renderer.cellStartLocation = 0.1;
  //     categoryAxis.renderer.cellEndLocation = 0.9;
  //     categoryAxis.renderer.inversed = true;

  //     var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
  //     valueAxis.min = 0;
  //     valueAxis.title.text = "";

  //     // Create series
  //     function createSeries(field, name) {
  //       var series = chart.series.push(new am4charts.ColumnSeries());
  //       series.dataFields.valueX = field;
  //       series.dataFields.categoryY = config.xAxisName;
  //       series.name = name;
  //       series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
  //       series.stacked = true;
  //       series.columns.template.width = am4core.percent(95);
  //     }
  //     for (var i = 0; i < config.graphs.length; i++) {
  //       createSeries(config.graphs[i]["title"], config.graphs[i]["valueField"]);
  //     }

  //     // Add legend
  //     chart.legend = new am4charts.Legend();
  //   };

  var stacked = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chartConfigs = {
      type: "serial",
      theme: "light",
      legend: {
        enabled: config.isTitle == "0" ? false : true,
        divId: "customLegendDiv-" + mainMetaDataId,
        fontSize: chartValueFontSize,
      },
      dataProvider: data,
      valueAxes: [
        {
          stackType: "regular",
          axisAlpha: 0.5,
          gridAlpha: 0.07,
          position: "left",
          fontSize: chartValueFontSize,
          labelFunction: function (value) {
            return Math.abs(value);
          },
          title: config.valueAxisTitle,
        },
      ],
      graphs: config.graphs,
      rotate:
        config.addonSettings && config.addonSettings.isvertical == "1"
          ? false
          : true,
      categoryField: config.xAxisName,
      categoryAxis: {
        gridPosition: "start",
        axisAlpha: 0,
        gridAlpha: 0,
        position: "left",
        fontSize: chartValueFontSize,
        title: config.categoryAxisTitle,
        labelFunction: function (label, item, axis) {
          if (label.length > config.labelTextSubStr)
            return label.substr(0, config.labelTextSubStr) + "...";

          return label;
        },
      },
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    };

    if (config.hasOwnProperty("colorsSet") && config.colorsSet) {
      chartConfigs.colors = config["colorsSet"].split(" ");
    } else if (config["cTheme"].length > 1) {
      chartConfigs.colors = config["cTheme"].split(" ");
    }

    var chart = AmCharts.makeChart(container, chartConfigs);

    if (parseFloat(drillDown) > 0 && typeof noDrillDown == "undefined") {
      chart.addListener("clickGraphItem", function (event) {
        isSubChart = 1;
        selectedChartEvent = event;
        ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
      });
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var serial = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var serial = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      marginRight: 40,
      marginLeft: 40,
      autoMarginOffset: 20,
      dataDateFormat: "YYYY-MM-DD",
      sequencedAnimation: true,
      valueAxes: [
        {
          id: "v1",
          axisAlpha: 0,
          position: "left",
          ignoreAxisWidth: true,
        },
      ],
      balloon: {
        borderThickness: 1,
        shadowAlpha: 0,
      },
      graphs: [
        {
          id: "g1",
          balloon: {
            drop: true,
            adjustBorderColor: false,
            color: "#ffffff",
          },
          bullet: "round",
          bulletBorderAlpha: 1,
          bulletColor: "#FFFFFF",
          bulletSize: 5,
          hideBulletsCount: 50,
          lineThickness: 2,
          title: "red line",
          useLineColorForBulletBorder: true,
          valueField: config.yAxisName,
          balloonText: "<span style='font-size:10px;'>[[value]]</span>",
        },
      ],
      chartScrollbar: {
        graph: "g1",
        oppositeAxis: false,
        offset: 30,
        scrollbarHeight: 80,
        backgroundAlpha: 0,
        selectedBackgroundAlpha: 0.1,
        selectedBackgroundColor: "#888888",
        graphFillAlpha: 0,
        graphLineAlpha: 0.5,
        selectedGraphFillAlpha: 0,
        selectedGraphLineAlpha: 1,
        autoGridCount: true,
        color: "#AAAAAA",
      },
      chartCursor: {
        pan: true,
        valueLineEnabled: true,
        valueLineBalloonEnabled: true,
        cursorAlpha: 1,
        cursorColor: "#258cbb",
        limitToGraph: "g1",
        valueLineAlpha: 0.2,
      },
      valueScrollbar: {
        oppositeAxis: false,
        offset: 50,
        scrollbarHeight: 10,
      },
      categoryField: config.xAxisName,
      categoryAxis: {
        parseDates: false,
        dashLength: 1,
        minorGridEnabled: true,
      },
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
      dataProvider: data,
    });

    if (config["cTheme"].length > 1 && typeof chart !== "undefined")
      chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        serial.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }
    function zoomChart() {
      serial.zoomToIndexes(
        serial.dataProvider.length - 40,
        serial.dataProvider.length - 1
      );
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var radar = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = new AmCharts.makeChart(container, {
      type: "radar",
      theme: "light",
      dataProvider: data,
      valueAxes: [],
      startDuration: 1,
      graphs: [],
      categoryField: config.xAxisName,
      categoryAxis: {
        gridPosition: "start",
        axisAlpha: 0,
        tickLength: 0,
        labelRotation: config.xLabelRotation,
      },
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
      legend: {},
      labelsEnabled: true,
      inside: true,
    });
    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (Object.keys(data).length) {
      $.each(config.graphs, function (i, dtl) {
        var graph = new AmCharts.AmGraph();
        graph.title = dtl.title;
        graph.valueField = dtl.valueField;
        (graph.balloonText = "[[value]]"),
          (graph.bullet = "round"),
          (graph.fillAlphas = 0.1),
          (graph.axisTitleOffset = 20),
          (graph.minimum = 0),
          (graph.axisAlpha = 0.15);
        (graph.lineThickness = 1.5), chart.addGraph(graph);
      });
    }

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var column = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create(container, am4charts.XYChart);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
    chart.data = data;

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.dataFields.category = config.xAxisName;
    categoryAxis.renderer.minGridDistance = 40;
    categoryAxis.fontSize = 11;
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.categoryX = config.xAxisName;
    series.dataFields.valueY = config.yAxisName;
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.tooltipY = 0;
    series.columns.template.strokeOpacity = 0;

    // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
    series.columns.template.adapter.add("fill", function (fill, target) {
      return chart.colors.getIndex(target.dataItem.index);
    });
  };

  // var column = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
  //     if ((config['cTheme']).length > 1) {
  //         var colors = (config['cTheme']).split(' ');
  //         if(config.graphs[0]['colorField'] == '') {
  //             for (i = 0; i < data.length; i++) {data[i].color = colors[i] ? colors[i] : '';}
  //             config.graphs[0]['colorField'] = 'color';
  //         }
  //     }

  //     var columnConfig = {
  //         "type": "serial",
  //         "theme": "light",
  //         "rotate": config.isBar,
  //         "startDuration": 0,
  //         "dataProvider": data,
  //         "sequencedAnimation": true,
  //         "gridAboveGraphs": true,
  //         "graphs": config.graphs,
  //         "legend": config.isLegend != '0' ? {
  //             "horizontalGap": 10,
  //             "useGraphSettings": true,
  //             "position": "right",
  //             "markerSize": 10
  //         } : false,
  //         "categoryField": config.xAxisName,
  //         "categoryAxis": {
  //             "gridPosition": "start",
  //             "labelRotation": config.xLabelRotation,
  //             "fontSize": chartCategoryAxisFontSize
  //         },
  //         exportConfig: {
  //             "menu": [{
  //                     "class": "export-main",
  //                     "format": "PRINT"
  //                 }]
  //         }

  //     };

  //     if (config.valueAxesMax && config.valueAxesMin) {
  //         columnConfig['valueAxes'] = [{
  //             "minimum": config.valueAxesMin,
  //             "maximum": config.valueAxesMax
  //         }];
  //     } else if (config.valueAxesMin) {
  //         columnConfig['valueAxes'] = [{
  //             "minimum": config.valueAxesMin
  //         }];
  //     } else if (config.valueAxesMax) {
  //         columnConfig['valueAxes'] = [{
  //             "maximum": config.valueAxesMax
  //         }];
  //     }

  //     var chart = AmCharts.makeChart(container, columnConfig);

  //     if (parseFloat(drillDown) > 0) {
  //         if (typeof noDrillDown == 'undefined') {
  //             chart.addListener("clickGraphItem", function (event) {
  //                 isSubChart = 1;
  //                 selectedChartEvent = event;
  //                 ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
  //             });
  //         }
  //     }

  //     $('#dialog-dashboard-' + mainMetaDataId).height($('.amcharts-main-div').height() + 100);
  // }

  var pie_charts_bullets = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var columnConfig = {
      data: data,
      hiddenState: {
        properties: {
          opacity: 0,
        },
      },
      xAxes: [
        {
          type: "CategoryAxis",
          dataFields: {
            category: config.xAxisName,
          },
          renderer: {
            grid: {
              disabled: true,
            },
          },
        },
      ],
      yAxes: [
        {
          type: "ValueAxis",
          title: {
            text: "",
          },
          min: 0,
          renderer: {
            baseGrid: {
              disabled: true,
            },
            grid: {
              strokeOpacity: 0.07,
            },
          },
        },
      ],
      series: [
        {
          type: "ColumnSeries",
          dataFields: {
            valueY: config.yAxisName,
            categoryX: config.xAxisName,
          },
          tooltip: {
            pointerOrientation: "vertical",
          },
          columns: {
            column: {
              tooltipText:
                "Series: {name}\nCategory: {categoryX}\nValue: {valueY}",
              tooltipY: 0,
              cornerRadiusTopLeft: 20,
              cornerRadiusTopRight: 20,
            },
            strokeOpacity: 0,
            adapter: {
              fill: function (fill, target) {
                var chart = target.dataItem.component.chart;
                var color = chart.colors.getIndex(target.dataItem.index * 3);
                return color;
              },
            },
            // pie
            children: [
              {
                type: "PieChart",
                forceCreate: true,
                width: "80%",
                height: "80%",
                align: "center",
                valign: "middle",
                dataFields: {
                  data: "pie",
                },
                series: [
                  {
                    type: "PieSeries",
                    dataFields: {
                      value: config.addonSettings.value,
                      category: config.addonSettings.title,
                    },
                    labels: {
                      disabled: true,
                    },
                    ticks: {
                      disabled: true,
                    },
                    slices: {
                      stroke: "#ffffff",
                      strokeWidth: 1,
                      strokeOpacity: 0,
                      adapter: {
                        fill: function (fill, target) {
                          return am4core.color("#ffffff");
                        },
                        fillOpacity: function (fillOpacity, target) {
                          return (target.dataItem.index + 1) * 0.2;
                        },
                      },
                    },
                    hiddenState: {
                      properties: {
                        startAngle: -90,
                        endAngle: 270,
                      },
                    },
                  },
                ],
              },
            ],
          },
        },
      ],
    };

    if (config.valueAxesMax && config.valueAxesMin) {
      columnConfig["valueAxes"] = [
        {
          minimum: config.valueAxesMin,
          maximum: config.valueAxesMax,
        },
      ];
    } else if (config.valueAxesMin) {
      columnConfig["valueAxes"] = [
        {
          minimum: config.valueAxesMin,
        },
      ];
    } else if (config.valueAxesMax) {
      columnConfig["valueAxes"] = [
        {
          maximum: config.valueAxesMax,
        },
      ];
    }

    am4core.useTheme(am4themes_animated);
    var chart = am4core.createFromConfig(columnConfig, container, "XYChart");

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var risk_heatmap = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    if (config.valueAxisTitle && config.categoryAxisTitle) {
      if (
        $("#" + container)
          .parent()
          .find(".heatmap-y-text").length
      ) {
        $("#" + container).removeClass("heatmap-xy-text");
        $("#" + container)
          .parent()
          .find(".heatmap-y-text")
          .remove();
        $("#" + container)
          .parent()
          .find(".heatmap-x-text")
          .remove();
      }
      $("#" + container).addClass("heatmap-xy-text");
      $("#" + container)
        .parent()
        .append(
          '<div class="heatmap-y-text" style="text-align:center;transform: rotate(-90deg);-webkit-transform: rotate(-90deg);-moz-transform: rotate(-90deg);-ms-transform: rotate(-90deg);-o-transform: rotate(-90deg);top: 50%;left: -20px;position: absolute;">' +
            config.categoryAxisTitle +
            "</div>" +
            '<div class="heatmap-x-text" style="text-align:center;border-top: 1px solid #9c9c9c;">' +
            config.valueAxisTitle +
            "</div>"
        );
    }

    am4core.useTheme(am4themes_animated);

    var chart = am4core.create(container, am4charts.XYChart);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chart.maskBullets = false;

    var xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());

    xAxis.dataFields.category = config.xAxisName;
    yAxis.dataFields.category = config.yAxisName;

    xAxis.renderer.grid.template.disabled = true;
    xAxis.renderer.minGridDistance = 40;

    yAxis.renderer.grid.template.disabled = true;
    yAxis.renderer.inversed = true;
    yAxis.renderer.minGridDistance = 30;

    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.categoryX = config.xAxisName;
    series.dataFields.categoryY = config.yAxisName;
    series.dataFields.value = config.addonSettings.risKvalue;
    series.sequencedInterpolation = true;
    series.defaultState.transitionDuration = 3000;

    // Set up column appearance
    var column = series.columns.template;
    column.strokeWidth = 2;
    column.strokeOpacity = 1;
    column.stroke = am4core.color("#ffffff");
    column.tooltipText = "{x}, {y}: {value.workingValue.formatNumber('#.')}";
    column.width = am4core.percent(100);
    column.height = am4core.percent(100);
    column.column.cornerRadius(6, 6, 6, 6);
    column.propertyFields.fill = config.colorField;

    // Set up bullet appearance
    var bullet1 = series.bullets.push(new am4charts.CircleBullet());
    //bullet1.circle.propertyFields.radius = "value";
    bullet1.circle.fill = am4core.color("#000");
    bullet1.circle.strokeWidth = 0;
    bullet1.circle.fillOpacity = 0.7;
    bullet1.interactionsEnabled = false;

    var bullet2 = series.bullets.push(new am4charts.LabelBullet());
    bullet2.label.text = "{value}";
    bullet2.label.fill = am4core.color("#fff");
    bullet2.zIndex = 1;
    bullet2.fontSize = 11;
    bullet2.interactionsEnabled = false;

    chart.data = data;

    var baseWidth = Math.min(
      chart.plotContainer.maxWidth,
      chart.plotContainer.maxHeight
    );
    var maxRadius = baseWidth / Math.sqrt(chart.data.length) / 2 - 2; // 2 is jast a margin
    series.heatRules.push({
      min: 10,
      max: maxRadius,
      property: "radius",
      target: bullet1.circle,
    });

    chart.plotContainer.events.on("maxsizechanged", function () {
      var side = Math.min(
        chart.plotContainer.maxWidth,
        chart.plotContainer.maxHeight
      );
      bullet1.circle.clones.each(function (clone) {
        clone.scale = side / baseWidth;
      });
    });

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var percent_stacked_area_chart = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create(container, am4charts.XYChart);
    chart.colors.step = 2;

    chart.data = data;

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = config.xAxisName;
    categoryAxis.title.text = "";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 20;

    categoryAxis.startLocation = 0.5;
    categoryAxis.endLocation = 0.5;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "";
    valueAxis.calculateTotals = true;
    valueAxis.min = 0;
    valueAxis.max = 100;
    valueAxis.strictMinMax = true;
    valueAxis.renderer.labels.template.adapter.add("text", function (text) {
      return text + "%";
    });

    // Create series
    var getLegend = chartTitleSeparatorSplit(config.yAxisName);
    for (var saco = 0; saco < getLegend.length; saco++) {
      var series = chart.series.push(new am4charts.LineSeries());
      series.dataFields.valueY = getLegend[saco];
      series.dataFields.valueYShow = "totalPercent";
      series.dataFields.categoryX = config.xAxisName;
      series.name = getLegend[saco];

      series.tooltipHTML =
        "<span style='font-size:14px; color:#000000;'><b>{valueY.value}</b></span>";

      series.tooltip.getFillFromObject = false;
      series.tooltip.background.fill = am4core.color("#FFF");

      series.tooltip.getStrokeFromObject = true;
      series.tooltip.background.strokeWidth = 3;

      series.fillOpacity = 0.85;
      series.stacked = true;

      // static
      series.legendSettings.labelText = getLegend[saco] + ":";
      series.legendSettings.valueText = "{valueY.close}";

      // hovering
      series.legendSettings.itemLabelText = getLegend[saco] + ":";
      series.legendSettings.itemValueText = "{valueY}";
    }

    // Add cursor
    chart.cursor = new am4charts.XYCursor();

    // add legend
    chart.legend = new am4charts.Legend();

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var variable_radius_radar = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create(container, am4charts.RadarChart);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

    var label = chart.createChild(am4core.Label);
    label.text = "Drag slider to change radius";
    label.exportable = false;

    chart.data = data;
    console.log(JSON.stringify(data));
    chart.radius = am4core.percent(95);
    chart.startAngle = 270 - 180;
    chart.endAngle = 270 + 180;
    chart.innerRadius = am4core.percent(60);

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category =
      config.addonSettings.category.toUpperCase();
    categoryAxis.renderer.labels.template.location = 0.5;
    categoryAxis.renderer.grid.template.strokeOpacity = 0.1;
    categoryAxis.renderer.axisFills.template.disabled = true;
    categoryAxis.mouseEnabled = false;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.tooltip.disabled = true;
    valueAxis.renderer.grid.template.strokeOpacity = 0.05;
    valueAxis.renderer.axisFills.template.disabled = true;
    valueAxis.renderer.axisAngle = 260;
    valueAxis.renderer.labels.template.horizontalCenter = "right";
    valueAxis.min = 0;

    var getLegend = chartTitleSeparatorSplit(config.yAxisName);
    for (var saco = 0; saco < getLegend.length; saco++) {
      var series1 = chart.series.push(new am4charts.RadarColumnSeries());
      series1.columns.template.radarColumn.strokeOpacity = 1;
      series1.name = getLegend[saco];
      series1.dataFields.categoryX =
        config.addonSettings.category.toUpperCase();
      series1.columns.template.tooltipText = "{name}: {valueY.value}";
      series1.dataFields.valueY = getLegend[saco];
      series1.stacked = true;
    }

    chart.seriesContainer.zIndex = -1;

    var slider = chart.createChild(am4core.Slider);
    slider.start = 0.5;
    slider.exportable = false;
    slider.events.on("rangechanged", function () {
      var start = slider.start;

      chart.startAngle = 270 - start * 179 - 1;
      chart.endAngle = 270 + start * 179 + 1;

      valueAxis.renderer.axisAngle = chart.startAngle;
    });

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var animated_xy_bubble = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    am4core.useTheme(am4themes_animated);

    var yearData = data;
    var firstKey = Number(Object.keys(data)[0]);
    var lastKey = Number(Object.keys(data)[Object.keys(data).length - 1]);

    var chart = am4core.create(container, am4charts.XYChart);
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "zoomXY";

    // Create axes
    var xAxis = chart.xAxes.push(new am4charts.ValueAxis());
    xAxis.min = Number(config.addonSettings.min);
    xAxis.max = Number(config.addonSettings.max);
    xAxis.keepSelection = true;
    xAxis.renderer.grid.template.above = true;

    var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
    yAxis.min = Number(config.addonSettings.min);
    yAxis.max = Number(config.addonSettings.max);
    yAxis.keepSelection = true;
    yAxis.renderer.grid.template.above = true;

    // Create color series
    // top left
    var series1 = chart.series.push(new am4charts.LineSeries());
    series1.dataFields.valueX = "ax";
    series1.dataFields.valueY = "ay";
    series1.strokeOpacity = 0;
    series1.fillOpacity = 1;
    series1.ignoreMinMax = true;
    series1.fill = am4core.color("#e3853c");
    series1.data = [
      {
        ax: -1000,
        ay: 0,
      },
      {
        ax: 0,
        ay: 0,
      },
      {
        ax: 0,
        ay: 1000,
      },
      {
        ax: -1000,
        ay: 1000,
      },
    ];

    // bottom left
    var series2 = chart.series.push(new am4charts.LineSeries());
    series2.dataFields.valueX = "ax";
    series2.dataFields.valueY = "ay";
    series2.strokeOpacity = 0;
    series2.ignoreMinMax = true;
    series2.fill = am4core.color("#48b2b7");
    series2.fillOpacity = 0.9;
    series2.data = [
      {
        ax: -1000,
        ay: 0,
      },
      {
        ax: 0,
        ay: 0,
      },
      {
        ax: 0,
        ay: -1000,
      },
      {
        ax: -1000,
        ay: -1000,
      },
    ];

    // bottom right
    var series3 = chart.series.push(new am4charts.LineSeries());
    series3.dataFields.valueX = "ax";
    series3.dataFields.valueY = "ay";
    series3.strokeOpacity = 0;
    series3.fill = am4core.color("#91d1da");
    series3.ignoreMinMax = true;
    series3.fillOpacity = 0.9;
    series3.data = [
      {
        ax: 1000,
        ay: 0,
      },
      {
        ax: 0,
        ay: 0,
      },
      {
        ax: 0,
        ay: -1000,
      },
      {
        ax: 1000,
        ay: -1000,
      },
    ];

    // top right
    var series4 = chart.series.push(new am4charts.LineSeries());
    series4.dataFields.valueX = "ax";
    series4.dataFields.valueY = "ay";
    series4.strokeOpacity = 0;
    series4.fill = am4core.color("#e8c634");
    series4.ignoreMinMax = true;
    series4.fillOpacity = 0.9;
    series4.data = [
      {
        ax: 1000,
        ay: 0,
      },
      {
        ax: 0,
        ay: 0,
      },
      {
        ax: 0,
        ay: 1000,
      },
      {
        ax: 1000,
        ay: 1000,
      },
    ];

    var series = chart.series.push(new am4charts.LineSeries());
    series.dataFields.valueX = config.xAxisName;
    series.dataFields.valueY = config.yAxisName;
    series.dataFields.value = config.addonSettings.bubbleValue;
    series.strokeOpacity = 0;

    var bullet = series.bullets.push(new am4core.Circle());
    bullet.fill = am4core.color("#000000");
    bullet.strokeOpacity = 0;
    bullet.strokeWidth = 2;
    bullet.fillOpacity = 0.5;
    bullet.stroke = am4core.color("#ffffff");
    bullet.hiddenState.properties.opacity = 0;
    bullet.tooltipText =
      "value:{value.value} x:{valueX.value} y:{valueY.value}";

    bullet.events.on("over", function (event) {
      var target = event.target;
      chart.cursor.triggerMove({ x: target.pixelX, y: target.pixelY }, "hard");
      chart.cursor.lineX.y = target.pixelY;
      chart.cursor.lineY.x = target.pixelX - chart.plotContainer.pixelWidth;
      xAxis.tooltip.disabled = false;
      yAxis.tooltip.disabled = false;
    });

    bullet.events.on("out", function (event) {
      chart.cursor.triggerMove(event.pointer.point, "none");
      chart.cursor.lineX.y = 0;
      chart.cursor.lineY.x = 0;
      xAxis.tooltip.disabled = true;
      yAxis.tooltip.disabled = true;
    });

    series.heatRules.push({
      target: bullet,
      min: 2,
      max: 30,
      property: "radius",
    });
    series.data = yearData[firstKey];
    chart.scrollbarX = new am4core.Scrollbar();
    chart.scrollbarY = new am4core.Scrollbar();

    var label = chart.plotContainer.createChild(am4core.Label);
    label.fontSize = 60;
    label.fillOpacity = 0.4;
    label.align = "center";
    label.zIndex = 1000;

    var sliderContainer = chart.bottomAxesContainer.createChild(
      am4core.Container
    );
    sliderContainer.width = am4core.percent(100);
    sliderContainer.layout = "horizontal";

    var playButton = sliderContainer.createChild(am4core.PlayButton);
    playButton.valign = "middle";
    playButton.events.on("toggled", function (event) {
      if (event.target.isActive) {
        playSlider();
      } else {
        stopSlider();
      }
    });

    var slider = sliderContainer.createChild(am4core.Slider);
    slider.valign = "middle";
    slider.margin(0, 0, 0, 0);
    slider.marginLeft = 30;
    slider.height = 15;

    slider.startGrip.events.on("drag", stop);

    var sliderAnimation = slider
      .animate({ property: "start", to: 1 }, 40000, am4core.ease.linear)
      .pause();
    sliderAnimation.events.on("animationended", function () {
      playButton.isActive = false;
    });

    slider.events.on("rangechanged", function () {
      var year = firstKey + Math.round(slider.start * (lastKey - firstKey - 1));
      var data = yearData[firstKey];
      for (var i = 0; i < series.data.length; i++) {
        var dataContext = series.data[i];
        dataContext.x = data[i].x;
        dataContext.y = data[i].y;
        dataContext.radius = data[i].radius;
      }

      chart.invalidateRawData();

      label.text = year.toString();
    });

    function playSlider() {
      if (slider) {
        if (slider.start >= 1) {
          slider.start = 0;
          sliderAnimation.start();
        }

        sliderAnimation.setProgress(slider.start);

        sliderAnimation.resume();
        playButton.isActive = true;
      }
    }

    function stopSlider() {
      sliderAnimation.pause();
      playButton.isActive = false;
    }

    setTimeout(function () {
      playSlider();
    }, 1500);

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var cylinder = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create(container, am4charts.XYChart3D);

    chart.data = data;

    let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = config.xAxisName;
    // categoryAxis.renderer.labels.template.rotation = 45;
    categoryAxis.renderer.labels.template.hideOversized = false;
    // categoryAxis.renderer.minGridDistance = 20;
    // categoryAxis.renderer.labels.template.horizontalCenter = "right";
    // categoryAxis.renderer.labels.template.verticalCenter = "middle";
    // categoryAxis.tooltip.label.rotation = 270;
    // categoryAxis.tooltip.label.horizontalCenter = "right";
    // categoryAxis.tooltip.label.verticalCenter = "middle";

    let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries3D());
    series.dataFields.valueY = config.yAxisName;
    series.dataFields.categoryX = config.xAxisName;
    series.name = "";
    series.tooltipText = "[bold]{valueY}[/]";

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 2;
    columnTemplate.strokeOpacity = 1;
    columnTemplate.stroke = am4core.color("#FFFFFF");

    columnTemplate.adapter.add("fill", function (fill, target) {
      return chart.colors.getIndex(target.dataItem.index);
    });

    columnTemplate.adapter.add("stroke", function (stroke, target) {
      return chart.colors.getIndex(target.dataItem.index);
    });

    chart.cursor = new am4charts.XYCursor();
    chart.cursor.lineX.strokeOpacity = 0;
    chart.cursor.lineY.strokeOpacity = 0;
  };

  // var cylinder = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
  //     var valAxis = [{"fontSize": chartCategoryAxisFontSize}];
  //     if (config.categoryAxisTitle) {
  //         valAxis = [{
  //            "title": config.categoryAxisTitle,
  //            "position": "left",
  //            "fontSize": chartCategoryAxisFontSize
  //         }];
  //     }
  //     var chart = AmCharts.makeChart(container, {
  //         "theme": "light",
  //         "type": "serial",
  //         "rotate": config.isBar,
  //         "startDuration": 2,
  //         "dataProvider": data,
  //         "graphs": config.graphs,
  //         "categoryField": config.xAxisName,
  //         "depth3D": 40,
  //         "angle": 30,
  //         "valueAxes": valAxis,
  //         "categoryAxis": {
  //             "gridPosition": "start",
  //             "labelRotation": config.xLabelRotation,
  //             "fontSize": chartCategoryAxisFontSize
  //         },
  //         "chartCursor": {
  //             "categoryBalloonEnabled": false,
  //             "cursorAlpha": 0,
  //             "zoomable": false
  //         },
  //         exportConfig: {
  //             "menu": [{
  //                     "class": "export-main",
  //                     "format": "PRINT"
  //                 }]
  //         }

  //     });

  //     if ((config['cTheme']).length > 1)
  //         chart.colors = (config['cTheme']).split(' ');

  //     if (parseFloat(drillDown) > 0) {
  //         if (typeof noDrillDown == 'undefined') {
  //             chart.addListener("clickGraphItem", function (event) {
  //                 isSubChart = 1;
  //                 selectedChartEvent = event;
  //                 ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
  //             });
  //         }
  //     }

  //     $('#dialog-dashboard-' + mainMetaDataId).height($('.amcharts-main-div').height() + 100);
  // }

  var bar = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create(container, am4charts.XYChart);
    chart.data = data;

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = config.xAxisName;
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = config.yAxisName;
    series.dataFields.categoryX = config.xAxisName;
    series.name = "";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 2;
    columnTemplate.strokeOpacity = 1;
  };

  // var bar = function (container, data, config, metaDataId, drillDown, mainMetaDataId) {
  //     var chart = AmCharts.makeChart(container, {
  //         "type": "serial",
  //         "theme": "light",
  //         "categoryField": config.xAxisName,
  //         "rotate": config.isBar,
  //         "startDuration": 0,
  //         "sequencedAnimation": true,
  //         "categoryAxis": {
  //             "gridPosition": "start",
  //             "position": "left",
  //             "autoGridCount": false,
  //             "gridCount": 50
  //         },
  //         "trendLines": [],
  //         "graphs": config.graphs,
  //         "guides": [],
  //         "valueAxes": [
  //             {
  //                 "id": "ValueAxis-1",
  //                 "position": "top",
  //                 "axisAlpha": 0
  //             }
  //         ],
  //         "allLabels": [],
  //         "balloon": {},
  //         "titles": [],
  //         "dataProvider": data,
  //         exportConfig: {
  //             "menu": [{
  //                     "class": "export-main",
  //                     "format": "PRINT"
  //                 }]
  //         }

  //     });

  //     if ((config['cTheme']).length > 1)
  //         chart.colors = (config['cTheme']).split(' ');

  //     if (parseFloat(drillDown) > 0) {
  //         if (typeof noDrillDown == 'undefined') {
  //             chart.addListener("clickGraphItem", function (event) {
  //                 isSubChart = 1;
  //                 selectedChartEvent = event;
  //                 ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
  //             });
  //         }
  //     }

  //     $('#dialog-dashboard-' + mainMetaDataId).height($('.amcharts-main-div').height() + 100);
  // }

  var barAxis = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      categoryField: config.xAxisName,
      rotate: config.isBar,
      startDuration: 0,
      sequencedAnimation: true,
      categoryAxis: {
        gridPosition: "start",
        position: "left",
      },
      trendLines: [],
      graphs: config.graphs,
      guides: [],
      valueAxes: [
        {
          id: "ValueAxis-1",
          position: "top",
          axisAlpha: 0,
        },
      ],
      allLabels: [],
      balloon: {},
      titles: [],
      dataProvider: data,
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var durationOnvalueAxis = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var valueTitle = config.valueAxisTitle.split("_");
    var catTitle = config.categoryAxisTitle.split("_");

    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      categoryField: config.xAxisName,
      startDuration: 0,
      legend: {
        equalWidths: true,
        useGraphSettings: true,
        valueAlign: "left",
        fontSize: chartValueFontSize,
      },
      categoryAxis: {
        gridPosition: "start",
        position: "left",
        labelRotation: config.xLabelRotation,
        fontSize: chartValueFontSize,
      },
      trendLines: [],
      graphs: config.graphs,
      guides: [],
      valueAxes: [
        {
          id: "distanceAxis",
          axisAlpha: 0,
          gridAlpha: 0,
          position: "left",
          fontSize: chartValueFontSize,
          title: valueTitle[0],
        },
        {
          id: "latitudeAxis",
          axisAlpha: 0,
          gridAlpha: 0,
          labelsEnabled: false,
          fontSize: chartValueFontSize,
          position: "right",
        },
        {
          id: "durationAxis",
          axisAlpha: 0,
          gridAlpha: 0,
          inside: true,
          position: "right",
          fontSize: chartValueFontSize,
          title: catTitle[0],
        },
      ],
      allLabels: [],
      balloon: {},
      titles: [],
      dataProvider: data,
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var durationOnvalueAxis2 = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var valueTitle = config.valueAxisTitle.split("_");
    var catTitle = config.categoryAxisTitle.split("_");

    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      categoryField: config.xAxisName,
      startDuration: 0,
      legend: {
        equalWidths: true,
        useGraphSettings: true,
        valueAlign: "left",
        fontSize: chartValueFontSize,
      },
      categoryAxis: {
        gridPosition: "start",
        position: "left",
        labelRotation: config.xLabelRotation,
        fontSize: chartValueFontSize,
      },
      trendLines: [],
      graphs: config.graphs,
      guides: [],
      valueAxes: [
        {
          id: "distanceAxis",
          fontSize: chartValueFontSize,
          axisAlpha: 0,
          titleBold: false,
          gridAlpha: 0,
          position: "left",
          align: "top",
          title: valueTitle[0],
        },
        {
          id: "latitudeAxis",
          axisAlpha: 0,
          gridAlpha: 0,
          fontSize: chartValueFontSize,
          labelsEnabled: false,
          position: "right",
        },
        {
          id: "durationAxis",
          axisAlpha: 0,
          titleBold: false,
          gridAlpha: -50,
          fontSize: chartValueFontSize,
          // "inside": true,
          position: "right",
          showLastLabel: "true",
          title: catTitle[0],
        },
      ],
      allLabels: [],
      balloon: {},
      titles: [],
      dataProvider: data,
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var multipleValueAxis = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      categoryField: config.xAxisName,
      startDuration: 0,
      legend: {
        equalWidths: true,
        useGraphSettings: true,
        valueAlign: "left",
        fontSize: chartValueFontSize,
      },
      categoryAxis: {
        gridPosition: "start",
        position: "left",
        fontSize: chartValueFontSize,
      },
      trendLines: [],
      graphs: config.graphs,
      guides: [],
      valueAxes: [
        {
          id: "v1",
          axisAlpha: 0,
          gridAlpha: 0,
          fontSize: chartValueFontSize,
          position: "left",
          title: config.valueAxisTitle,
        },
        {
          id: "v2",
          axisAlpha: 0,
          gridAlpha: 0,
          fontSize: chartValueFontSize,
          labelsEnabled: false,
          position: "right",
        },
        {
          id: "v3",
          axisAlpha: 0,
          gridAlpha: 0,
          fontSize: chartValueFontSize,
          inside: true,
          position: "right",
          title: config.categoryAxisTitle,
        },
      ],
      allLabels: [],
      balloon: {},
      titles: [],
      dataProvider: data,
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var barAxis = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId
  ) {
    var chart = AmCharts.makeChart(container, {
      type: "serial",
      theme: "light",
      categoryField: config.xAxisName,
      rotate: config.isBar,
      startDuration: 0,
      sequencedAnimation: true,
      categoryAxis: {
        gridPosition: "start",
        position: "left",
      },
      trendLines: [],
      graphs: config.graphs,
      guides: [],
      valueAxes: [
        {
          id: "ValueAxis-1",
          position: "top",
          axisAlpha: 0,
        },
      ],
      allLabels: [],
      balloon: {},
      titles: [],
      dataProvider: data,
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var donut = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId,
    responseData
  ) {
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create(container, am4charts.PieChart);

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = config.yAxisName;
    pieSeries.dataFields.category = config.xAxisName;

    // Let's cut a hole in our Pie chart the size of 30% the radius
    chart.innerRadius = am4core.percent(50);
    pieSeries.ticks.template.disabled = true;
    pieSeries.labels.template.disabled = true;

    // Create a base filter effect (as if it's not there) for the hover to return to
    var shadow = pieSeries.slices.template.filters.push(
      new am4core.DropShadowFilter()
    );
    shadow.opacity = 0;

    // Create hover state
    var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

    // Slightly shift the shadow and make it more prominent on hover
    var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter());
    hoverShadow.opacity = 0.7;
    hoverShadow.blur = 5;

    // Add a legend
    chart.legend = new am4charts.Legend();
    chart.data = data;
  };
  // var donut = function (container, data, config, metaDataId, drillDown, mainMetaDataId, responseData) {
  //     var $color = '"#cc4748" "#fdd400" "#84b761" "#67b7dc" "#cd82ad" "#2f4074" "#448e4d" "#b7b83f" "#b9783f" "#b93e3d" "#913167"';
  //     var $isInlineLegend = (typeof responseData !== 'undefined' && typeof responseData.isInlineLegend !== 'undefined') ? responseData.isInlineLegend : 0;
  //     var legendFormat = (typeof responseData !== 'undefined' && typeof responseData.legendFormat !== 'undefined' && responseData.legendFormat) ? responseData.legendFormat : "[[title]] ([[percents]]%)";

  //     var chartConfigs = {
  //         "type": "pie",
  //         "theme": "light",
  //         "startDuration": 0,
  //         "addClassNames": true,
  //         "legend": {
  //             "enabled": $isInlineLegend == 1 ? false : true,
  //             "position": "right",
  //             "valueAlign": "left",
  //             "align": "center",
  //             "fontSize": chartValueFontSize,
  //             "markerType": "circle"
  //         },
  //         "dataProvider": data,
  //         "titleField": config.xAxisName,
  //         "valueField": config.yAxisName,
  //         "labelRadius": 5,
  //         "radius": "30%",
  //         "innerRadius": "68%",
  //         "startDuration": 0,
  //         "labelText": legendFormat,
  //         "sequencedAnimation": true,
  //         "labelsEnabled": $isInlineLegend == 1 ? true : false,
  //         exportConfig: {
  //             "menu": [{
  //                 "class": "export-main",
  //                 "format": "PRINT"
  //             }]
  //         }
  //     };
  //     data = getDataGrouping(data, config);

  //     if (config.addonSettings && config.addonSettings.centerlabelnumber) {
  //         chartConfigs['allLabels'] = [{
  //             "y": "50%",
  //             "align": "center",
  //             "size": 21,
  //             "bold": true,
  //             "text": data[0][config.addonSettings.centerlabelnumber],
  //             "color": "#555"
  //           }, {
  //             "y": "45%",
  //             "align": "center",
  //             "size": 15,
  //             "text": config.addonSettings.centerlabeltext ? config.addonSettings.centerlabeltext : '',
  //             "color": "#555"
  //         }]
  //     }

  //     if ((config['cTheme']).length > 1) {
  //         chartConfigs.colors = (config['cTheme']).split(' ');
  //     }

  //     var chart = AmCharts.makeChart(container, chartConfigs);

  //     chart.addListener("init", handleInit);

  //     chart.addListener("rollOverSlice", function (e) {
  //         handleRollOver(e);
  //     });

  //     function handleInit() {
  //         chart.legend.addListener("rollOverItem", handleRollOver);
  //     }

  //     function handleRollOver(e) {
  //         var wedge = e.dataItem.wedge.node;
  //         wedge.parentNode.appendChild(wedge);
  //     }

  //     if (parseFloat(drillDown) > 0) {
  //         if (typeof noDrillDown == 'undefined') {
  //             chart.addListener("clickSlice", function (event) {
  //                 isSubChart = 1;
  //                 selectedChartEvent = event;
  //                 ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
  //             });
  //         }
  //     }

  //     $('#dialog-dashboard-' + mainMetaDataId).height($('.amcharts-main-div').height() + 100);
  // }

  var pie = function (
    container,
    data,
    config,
    metaDataId,
    drillDown,
    mainMetaDataId,
    responseData
  ) {
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create(container, am4charts.PieChart);

    chart.data = data;

    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = config.yAxisName;
    pieSeries.dataFields.category = config.xAxisName;
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeOpacity = 1;

    // This creates initial animation
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    chart.hiddenState.properties.radius = am4core.percent(0);
  };
  // var pie = function (container, data, config, metaDataId, drillDown, mainMetaDataId, responseData) {
  //     data = getDataGrouping(data, config);
  //     var $color = '"#cc4748" "#fdd400" "#84b761" "#67b7dc" "#cd82ad" "#2f4074" "#448e4d" "#b7b83f" "#b9783f" "#b93e3d" "#913167"';
  //     var $color = $color.split(' ');
  //     var legendFormat = (typeof responseData !== 'undefined' && typeof responseData.legendFormat !== 'undefined' && responseData.legendFormat) ? responseData.legendFormat : "[[title]]: [[percents]]% ([[value]])";

  //     var chartOptions = {
  //         "type": "pie",
  //         "theme": "light",
  //         "dataProvider": data,
  //         "fontSize": chartValueFontSize,
  //         "startDuration": 0.1,
  //         "titleField": config.xAxisName,
  //         "valueField": config.yAxisName,
  //         "labelText": legendFormat,
  //         "sequencedAnimation": true,
  //         //"colors" : $color,
  //         "balloon": {
  //             "fixedPosition": true
  //         },
  //         "labelFunction": function (label, item, axis) {
  //             if (label.title.length > config.labelTextSubStr)
  //                 return label.title.substr(0, config.labelTextSubStr) + '... ' + Number(label.percents).toFixed(2).replace(/\.?0+$/, '') + '% (' + label.value + ')';

  //             return label.title + ': ' + Number(label.percents).toFixed(2).replace(/\.?0+$/, '') + '% (' + label.value + ')';
  //         },
  //         exportConfig: {
  //             "menu": [{
  //                     "class": "export-main",
  //                     "format": "PRINT"
  //                 }]
  //         }
  //     };

  //     if (!config.isShowTitle) {
  //         chartOptions['labelFunction'] = function() {
  //             return '';
  //         }
  //     }

  //     if (typeof config.realLegendPosition !== 'undefined' && config.realLegendPosition != null) {
  //         chartOptions['legend'] = {
  //             "position": config.realLegendPosition,
  //             "fontSize": chartValueFontSize
  //         };
  //     }
  //     var chart = AmCharts.makeChart(container, chartOptions);

  //     if (parseFloat(drillDown) > 0) {
  //         if (typeof noDrillDown == 'undefined') {
  //             chart.addListener("clickSlice", function (event) {
  //                 isSubChart = 1;
  //                 selectedChartEvent = event;
  //                 ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
  //             });
  //         }
  //     }

  //     $('#dialog-dashboard-' + mainMetaDataId).height($('.amcharts-main-div').height() + 100);
  // }

  var dual = function (
    container,
    data,
    config,
    metaDataId,
    dataChart,
    drillDown,
    mainMetaDataId
  ) {
    var chart = new AmCharts.makeChart(container, {
      type: "serial",
      addClassNames: true,
      theme: "light",
      autoMargins: false,
      width: "100%",
      marginLeft: 100,
      marginRight: 0,
      marginTop: 10,
      marginBottom: 26,
      sequencedAnimation: true,
      balloon: {
        adjustBorderColor: false,
        horizontalPadding: 10,
        verticalPadding: 8,
        color: "#ffffff",
      },
      dataProvider: data,
      valueAxes: [
        {
          axisAlpha: 0,
          position: "left",
          fontSize: chartCategoryAxisFontSize,
        },
      ],
      startDuration: 0,
      graphs: [],
      categoryField: config.xAxisName,
      categoryAxis: {
        gridPosition: "start",
        axisAlpha: 0,
        tickLength: 0,
        labelRotation: config.xLabelRotation,
        fontSize: chartCategoryAxisFontSize,
      },
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });
    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    $.each(config.graphs, function (i, dtl) {
      var graph = new AmCharts.AmGraph();
      graph.title = dtl.title;
      graph.valueField = dtl.valueField;
      if (typeof dtl.bullet != "undefined") {
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

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var clustered = function (
    container,
    data,
    config,
    metaDataId,
    dataChart,
    drillDown,
    mainMetaDataId
  ) {
    var chartOpt = {
      type: "serial",
      theme: "none",
      legend: {
        equalWidths: false,
        useGraphSettings: true,
        valueAlign: "left",
        fontSize: chartValueFontSize,
        valueWidth: 120,
      },
      autoMargins: false,
      rotate: false,
      width: "100%",
      marginLeft: 110,
      marginRight: 0,
      marginTop: 5,
      marginBottom: 73,
      sequencedAnimation: true,
      dataProvider: data,
      valueAxes: [
        {
          id: "distanceAxis",
          axisAlpha: 0,
          fontSize: chartValueFontSize,
          gridAlpha: 0,
          position: "left",
          title: config.valueAxisTitle,
        },
      ],
      graphs: [],
      chartCursor: {
        cursorAlpha: 0.1,
        cursorColor: "#000000",
        fullWidth: true,
        valueBalloonsEnabled: false,
        zoomable: false,
      },
      categoryField: config.xAxisName,
      categoryAxis: {
        gridPosition: "start",
        axisAlpha: 0,
        fontSize: chartValueFontSize,
        tickLength: 0,
        labelRotation: config.xLabelRotation,
        title: config.categoryAxisTitle,
      },
      export: {
        enabled: true,
      },
    };

    /**
     * Ulaankguu Ts
     * Legend textiig darj bsn tul comment hiilee
     * mdobject/dataview/1601860672946771&mmid=1601861470252750&mid=1601861470252750
     */
    // if (typeof config.realLegendPosition !== 'undefined' && config.realLegendPosition != null) {
    //     chartOpt.legend = {
    //         "position": config.realLegendPosition
    //     };
    // }

    var chart = new AmCharts.makeChart(container, chartOpt);

    if (config["cTheme"].length > 1) {
      chart.colors = config["cTheme"].split(" ");
    } else {
      chart.colors = "#8E44AD #3faba4".split(" ");
    }

    $.each(config.graphs, function (i, dtl) {
      var graph = new AmCharts.AmGraph();

      graph.lineAlpha = "0.2";
      graph.type = "column";
      graph.fillAlphas = "0.8";
      graph.dashLengthField = dtl.dashLengthField;
      graph.balloonText = dtl.balloonText;
      graph.title = dtl.title;
      graph.valueField = dtl.valueField;

      chart.addGraph(graph);
    });

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var clusteredHorizontal = function (
    container,
    data,
    config,
    metaDataId,
    dataChart,
    drillDown,
    mainMetaDataId
  ) {
    var categoryField = config.xAxisName;
    ///console.log(data);
    var chart = new AmCharts.makeChart(container, {
      type: "serial",
      addClassNames: true,
      theme: "light",
      // "legend": {
      //     "horizontalGap": 5,
      //     "position": "bottom",
      //     "useGraphSettings": true,
      //     "markerSize": 10
      // },
      rotate: true,
      columnSpacing: 0,
      marginTop: 5,
      marginBottom: 40,
      sequencedAnimation: true,
      balloon: {},
      dataProvider: data,
      valueAxes: [
        {
          axisAlpha: 0,
          position: "left",
          fontSize: chartValueFontSize,
          title: config.categoryAxisTitle,
        },
      ],
      startDuration: 0,
      graphs: [],
      categoryField: categoryField,
      categoryAxis: {
        gridPosition: "start",
        axisAlpha: 0,
        fontSize: chartValueFontSize,
        autoGridCount: false,
        gridCount: 50,
        tickLength: 0,
        labelRotation: config.xLabelRotation,
        title: config.categoryAxisTitle,
        labelFunction: function (label, item, axis) {
          if (label.length > config.labelTextSubStr)
            return label.substr(0, config.labelTextSubStr) + "...";

          return label;
        },
      },
      exportConfig: {
        menu: [
          {
            class: "export-main",
            format: "PRINT",
          },
        ],
      },
    });

    chart.colors = "#4CAF50 #8E44AD ".split(" ");

    $.each(config.graphs, function (i, dtl) {
      var graph = new AmCharts.AmGraph();

      graph.lineAlpha = "0.2";
      graph.type = "column";
      graph.fillAlphas = "0.8";
      graph.dashLengthField = dtl.dashLengthField;
      graph.balloonText = dtl.balloonText;
      graph.title = dtl.title;
      graph.fillColorsField = "red";
      graph.valueField = dtl.valueField;
      graph.labelText = "[[value]]";

      chart.addGraph(graph);
    });

    if (config["cTheme"].length > 1) chart.colors = config["cTheme"].split(" ");

    if (parseFloat(drillDown) > 0) {
      if (typeof noDrillDown == "undefined") {
        chart.addListener("clickGraphItem", function (event) {
          isSubChart = 1;
          selectedChartEvent = event;
          ChartsAmcharts.subChartInit(
            metaDataId,
            event,
            $(".dashboard-filter-form-" + mainMetaDataId).serialize(),
            mainMetaDataId
          );
        });
      }
    }

    $("#dialog-dashboard-" + mainMetaDataId).height(
      $(".amcharts-main-div").height() + 100
    );
  };

  var threeDStackedClustered = function (
    container,
    data,
    config,
    metaDataId,
    dataChart,
    drillDown,
    mainMetaDataId
  ) {
    if (typeof data !== "undefined") {
      var tmpData = {},
        isGrouped = false,
        tmpDataGrouped = [],
        configyAxisName = chartTitleSeparatorSplit(config.yAxisName);

      $.each(data, function (key, value) {
        if (typeof tmpData[value[config.xAxisName]] === "undefined") {
          tmpData[value[config.xAxisName]] = {};
          $.each(configyAxisName, function (splitedKey, splitedValue) {
            var yAxisNameSplitedMore = splitedValue.split("_");
            if (typeof yAxisNameSplitedMore[1] !== "undefined") {
              tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]] =
                !isNaN(value[yAxisNameSplitedMore[1]])
                  ? value[yAxisNameSplitedMore[1]]
                  : 0;
            }
          });
        } else {
          $.each(configyAxisName, function (splitedKey, splitedValue) {
            var yAxisNameSplitedMore = splitedValue.split("_");
            if (typeof yAxisNameSplitedMore[1] !== "undefined") {
              tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]] =
                parseFloat(
                  tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]]
                ) + parseFloat(value[yAxisNameSplitedMore[1]]);
            }
          });

          isGrouped = true;
        }
      });

      if (isGrouped) {
        var cnt = 0;
        $.each(tmpData, function (key, value) {
          tmpDataGrouped[cnt] = {};
          tmpDataGrouped[cnt][config.xAxisName] = key;
          $.each(configyAxisName, function (splitedKey, splitedValue) {
            var yAxisNameSplitedMore = splitedValue.split("_");
            if (typeof yAxisNameSplitedMore[1] !== "undefined") {
              tmpDataGrouped[cnt][yAxisNameSplitedMore[1]] = !isNaN(
                value[yAxisNameSplitedMore[1]]
              )
                ? parseFloat(value[yAxisNameSplitedMore[1]])
                : 0;
            }
          });

          cnt++;
        });

        data = tmpDataGrouped;
      } else {
        var cnt = 0;
        $.each(data, function (key, value) {
          tmpDataGrouped[cnt] = {};
          for (var i in value) {
            tmpDataGrouped[cnt][i] = value[i];
          }
          cnt++;
        });

        data = tmpDataGrouped;
      }
    }

    am4core.useTheme(am4themes_animated);
    var chart = am4core.create(container, am4charts.XYChart3D);
    chart.data = data;

    chart.legend = new am4charts.Legend();
    chart.legend.position = config.legendPosition;

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = config.xAxisName;
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "";
    valueAxis.renderer.labels.template.adapter.add("text", function (text) {
      return text;
    });

    // Create series
    for (var i = 0; i < config.graphs.length; i++) {
      var series = chart.series.push(new am4charts.ColumnSeries3D());
      series.dataFields.valueY = config.graphs[i]["valueField"];
      series.dataFields.categoryX = config.xAxisName;
      series.name = config.graphs[i]["title"];
      series.clustered = false;
      series.columns.template.tooltipText =
        config.graphs[i]["title"] + ": [bold]{valueY}[/]";
    }
  };

  // var threeDStackedClustered = function (container, data, config, metaDataId, dataChart, drillDown, mainMetaDataId) {

  //     if (typeof data !== "undefined") {
  //         var tmpData = {}, isGrouped = false, tmpDataGrouped = [],
  //             configyAxisName = chartTitleSeparatorSplit(config.yAxisName);

  //         $.each(data, function (key, value) {
  //             if (typeof tmpData[value[config.xAxisName]] === "undefined") {
  //                 tmpData[value[config.xAxisName]] = {};
  //                 $.each(configyAxisName, function (splitedKey, splitedValue) {
  //                     var yAxisNameSplitedMore = splitedValue.split('_');
  //                     if (typeof yAxisNameSplitedMore[1] !== "undefined") {
  //                         tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]] = !isNaN(value[yAxisNameSplitedMore[1]]) ? value[yAxisNameSplitedMore[1]] : 0;
  //                     }
  //                 });
  //             } else {
  //                 $.each(configyAxisName, function (splitedKey, splitedValue) {
  //                     var yAxisNameSplitedMore = splitedValue.split('_');
  //                     if (typeof yAxisNameSplitedMore[1] !== "undefined") {
  //                         tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]] = parseFloat(tmpData[value[config.xAxisName]][yAxisNameSplitedMore[1]]) + parseFloat(value[yAxisNameSplitedMore[1]]);
  //                     }
  //                 });

  //                 isGrouped = true;
  //             }
  //         });

  //         if (isGrouped) {
  //             var cnt = 0;
  //             $.each(tmpData, function (key, value) {
  //                 tmpDataGrouped[cnt] = {};
  //                 tmpDataGrouped[cnt][config.xAxisName] = key;
  //                 $.each(configyAxisName, function (splitedKey, splitedValue) {
  //                     var yAxisNameSplitedMore = splitedValue.split('_');
  //                     if (typeof yAxisNameSplitedMore[1] !== "undefined") {
  //                         tmpDataGrouped[cnt][yAxisNameSplitedMore[1]] = !isNaN(value[yAxisNameSplitedMore[1]]) ? parseFloat(value[yAxisNameSplitedMore[1]]) : 0;
  //                     }
  //                 });

<<<<<<< .mine
  //                 cnt++;
  //             });
||||||| .r13309
        switch (response.chartType) {
            case 'am_bar' :
            case 'am_bar_axis' :
            {
                isBar = true;
                if (response.chartType === 'am_bar_axis') {
                    var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
                    graphs = [{
                            "alphaField": "alpha",
                            "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                            "fillAlphas": 1,
                            "title": yAxisArray[0],
                            "type": "column",
                            "valueField": yAxisArray[0],
                            "fontSize": chartValueFontSize,
                            "dashLengthField": "dashLengthColumn"
                        }, {
                            "id": "graph2",
                            "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                            "bullet": "round",
                            "lineThickness": 3,
                            "bulletSize": 7,
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "useLineColorForBulletBorder": true,
                            "bulletBorderThickness": 3,
                            "fillAlphas": 0,
                            "fontSize": chartValueFontSize,
                            "lineAlpha": 1,
                            "title": yAxisArray[1],
                            "valueField": yAxisArray[1]
                        }]
                }
                break;
            }
            case 'am_dual' :
            case 'am_radar_chart' :
            {
                var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
                var yAxisArray2 = yAxisArray[0].split('_');
                var yAxisArray3 = yAxisArray[1].split('_');
                graphs = [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                        "fillAlphas": 1,
                        "title": yAxisArray2[0],
                        "type": "column",
                        "valueField": yAxisArray2[1],
                        "fontSize": chartValueFontSize,
                        "dashLengthField": "dashLengthColumn"
                    }, {
                        "id": "graph2",
                        "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                        "bullet": "round",
                        "lineThickness": 3,
                        "bulletSize": 7,
                        "bulletBorderAlpha": 1,
                        "bulletColor": "#FFFFFF",
                        "useLineColorForBulletBorder": true,
                        "bulletBorderThickness": 3,
                        "fillAlphas": 0,
                        "lineAlpha": 1,
                        "title": yAxisArray3[0],
                        "fontSize": chartValueFontSize,
                        "valueField": yAxisArray3[1]
                    }];
                break;
            }
            case 'clustered_bar_chart' :
            case 'clustered_bar_chart_horizontal' :
            case 'am_3d_stacked_column_chart' :
            {
                graphs = [];
                var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
=======
        switch (response.chartType) {
            case 'am_bar' :
            case 'am_bar_axis' :
            {
                isBar = true;
                if (response.chartType === 'am_bar_axis') {
                    var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
                    graphs = [{
                            "alphaField": "alpha",
                            "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                            "fillAlphas": 1,
                            "title": yAxisArray[0],
                            "type": "column",
                            "valueField": yAxisArray[0],
                            "fontSize": chartValueFontSize,
                            "dashLengthField": "dashLengthColumn"
                        }, {
                            "id": "graph2",
                            "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                            "bullet": "round",
                            "lineThickness": 3,
                            "bulletSize": 7,
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "useLineColorForBulletBorder": true,
                            "bulletBorderThickness": 3,
                            "fillAlphas": 0,
                            "fontSize": chartValueFontSize,
                            "lineAlpha": 1,
                            "title": yAxisArray[1],
                            "valueField": yAxisArray[1]
                        }]
                }
                break;
            }
            case 'am_dual' :
            case 'am_radar_chart' :
            {
                var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
                var yAxisArray2 = yAxisArray[0].split('_');
                var title = '', valueField = '';

                if (yAxisArray.length == 2 && typeof yAxisArray[1] !== 'undefinded' && (yAxisArray[1]).indexOf('_') !== -1) {
                    var yAxisArray3 = yAxisArray[1].split('_');
                    title = yAxisArray3[0];
                    valueField = yAxisArray3[1];
                }
                
                //
                graphs = [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                        "fillAlphas": 1,
                        "title": yAxisArray2[0],
                        "type": "column",
                        "valueField": yAxisArray2[1],
                        "fontSize": chartValueFontSize,
                        "dashLengthField": "dashLengthColumn"
                    }, {
                        "id": "graph2",
                        "balloonText": "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
                        "bullet": "round",
                        "lineThickness": 3,
                        "bulletSize": 7,
                        "bulletBorderAlpha": 1,
                        "bulletColor": "#FFFFFF",
                        "useLineColorForBulletBorder": true,
                        "bulletBorderThickness": 3,
                        "fillAlphas": 0,
                        "lineAlpha": 1,
                        "title": title,
                        "fontSize": chartValueFontSize,
                        "valueField": valueField
                    }];
                break;
            }
            case 'clustered_bar_chart' :
            case 'clustered_bar_chart_horizontal' :
            case 'am_3d_stacked_column_chart' :
            {
                graphs = [];
                var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
>>>>>>> .r13341

  //             data = tmpDataGrouped;
  //         } else {
  //             var cnt = 0;
  //             $.each(data, function (key, value) {
  //                 tmpDataGrouped[cnt] = {};
  //                 for (var i in value) {
  //                     tmpDataGrouped[cnt][i] = !isNaN(value[i]) && value[i] ? parseFloat(value[i]) : value[i];
  //                 }
  //                 cnt++;
  //             });

  //             data = tmpDataGrouped;
  //         }
  //     }

  //     $('#'+container).empty();

  //     var chart = AmCharts.makeChart(container, {
  //         "theme": "light",
  //         "type": "serial",
  //         "legend": {
  //             "horizontalGap": 10,
  //             "position": config.legendPosition,
  //             "useGraphSettings": true,
  //             "markerSize": 10,
  //             "fontSize": chartValueFontSize,
  //             "valueWidth": 140
  //         },
  //         "valueAxes": [{
  //             "stackType": "3d",
  //             "fontSize": chartValueFontSize,
  //             "position": "left"
  //         }],
  //         "startDuration": 1,
  //         "dataProvider": data,
  //         "graphs": config.graphs,
  //         "plotAreaFillAlphas": 0.1,
  //         "depth3D": 60,
  //         "angle": 30,
  //         "categoryField": config.xAxisName,
  //         "categoryAxis": {
  //             "gridPosition": "start",
  //             "fontSize": chartValueFontSize,
  //             "labelRotation": config.xLabelRotation
  //         },
  //         "chartCursor": {
  //             "enabled": true
  //         },
  //         "chartScrollbar": {
  //             "enabled": true
  //         },
  //         exportConfig: {
  //             "menu": [{
  //                 "class": "export-main",
  //                 "format": "PRINT"
  //             }]
  //         }
  //     });

  //     if ((config['cTheme']).length > 1) {
  //         chart.colors = (config['cTheme']).split(' ');
  //     }

  //     if (parseFloat(drillDown) > 0) {
  //         if (typeof noDrillDown == 'undefined') {
  //             chart.addListener("clickGraphItem", function (event) {
  //                 isSubChart = 1;
  //                 selectedChartEvent = event;
  //                 ChartsAmcharts.subChartInit(metaDataId, event, [], mainMetaDataId);
  //             });
  //         }
  //     }

  //     $('#dialog-dashboard-' + mainMetaDataId).height($('.amcharts-main-div').height() + 100);
  // };

  var drawChartAmchart = function (
    defaultCriteriaData,
    chartType,
    metaDataId,
    callback,
    workSpaceParams,
    workSpaceId,
    criteriaPosition
  ) {
    _defaultCriteriaData = defaultCriteriaData;
    _chartType = chartType;
    chartType;
    var metaDataIdSplited = metaDataId.split("_");
    _metaDataId = metaDataId;

    $(".back-btn-dashboard-" + metaDataId).addClass("hidden");
    $.ajax({
      type: "post",
      url: "mddashboard/getDataForAmchart",
      dataType: "json",
      data: {
        metaDataId: metaDataIdSplited[0],
        defaultCriteriaData: defaultCriteriaData,
        workSpaceParams: workSpaceParams,
        workSpaceId: workSpaceId,
        version: 1,
      },
      beforeSend: function () {
        Core.blockUI({ animate: true });
      },
      success: function (response) {
        if (response.error !== null) {
          Core.unblockUI();
          $(".dashboard-filter-form-" + metaDataId).html("");
          new PNotify({
            title: "Error",
            text: response.error,
            type: "error",
            sticker: false,
          });
          return;
        }
        $("#dashboard-" + metaDataId).width(response.width);
        $("#dashboard-title-" + metaDataId)
          .attr("title", response.title)
          .html(response.title);
        if (response.height) {
          $("#dashboard-" + metaDataId).height(response.height);
        }

        if (criteriaPosition !== "top") {
          $(".dashboard-filter-form-" + metaDataId).html(
            response.defaultCriteria
          );
        }
        var config = ChartsAmcharts.getConfig(response, metaDataId, chartType);
        ChartsAmcharts.init(
          chartType,
          "dashboard-" + metaDataId,
          config.data,
          config,
          response.linkedMetaDataId,
          response.DRILLDOWN,
          metaDataId,
          response
        );

        Core.initAjax($(".dashboard-container-" + metaDataId));
        if (typeof callback === "function") {
          callback();
        }
        Core.unblockUI();
      },
    }).done(function () {
      $("#dashboard-container-" + metaDataId)
        .find(".open")
        .removeClass("open");
    });
  };

  var getConfig = function (response, metaDataId, chartType) {
    var data = [];
    if (
      typeof response.series.data !== "undefined" &&
      Object.keys(response.series.data).length > 0
    ) {
      data = response.series.data;
    }

    var xAxisName = response.series.xAxisName;
    var yAxisName = response.series.yAxisName;
    var xLabelRotation = response.xLabelRotation;
    var cTheme = response.theme;
    var isBar = false;
    var title = "";
    var graphs = [];
    var trendLines = [];

    if (response.isTitle == 1) {
      title = response.title;
      $("#card-title-" + metaDataId).show();
      $("#dashboard-title-" + metaDataId).html(title);
      $("#dashboard-title-" + metaDataId).attr("title", title);
      if (
        typeof response.description != "undefined" &&
        response.description != "" &&
        response.description != null
      ) {
        $("#dashboard-helper-" + metaDataId).html(response.description);
        $("#dashboard-helper-" + metaDataId).attr(
          "title",
          response.description
        );
      } else {
        $("#dashboard-helper-" + metaDataId).html(title);
        $("#dashboard-helper-" + metaDataId).attr("title", title);
      }
    } else {
      $("#card-title-" + metaDataId).hide();
    }

    if (typeof response.series.xAxisGroupName !== "undefined") {
      $.each(response.series.xAxisName, function (key, value) {
        var tmpGraphs = {
          balloonText: "[[category]]<br>" + value + ": <b>[[value]]</b>",
          fillAlphas: 0.8,
          id: "xAxisItem" + key,
          lineAlpha: 0.2,
          type: "column",
          title: value,
          valueField: value,
        };
        graphs.push(tmpGraphs);
      });
      xAxisName = response.series.xAxisGroupName;
    } else {
      var legendFormat =
        typeof response.legendFormat !== "undefined" && response.legendFormat
          ? response.legendFormat
          : "[[category]]: <b>[[value]]</b>";
      var colorField =
        typeof response.colorField !== "undefined" && response.colorField
          ? response.colorField
          : "";
      graphs = [
        {
          balloonText: legendFormat,
          fillAlphas: 0.8,
          lineAlpha: 0.2,
          fontSize: chartValueFontSize,
          type: "column",
          valueField: response.series.yAxisName,
          colorField: colorField,
        },
      ];
    }

<<<<<<< .mine
    switch (response.chartType) {
      case "am_bar":
      case "am_bar_axis": {
        isBar = true;
        if (response.chartType === "am_bar_axis") {
          var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
          graphs = [
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              fillAlphas: 1,
              title: yAxisArray[0],
              type: "column",
              valueField: yAxisArray[0],
              fontSize: chartValueFontSize,
              dashLengthField: "dashLengthColumn",
            },
            {
              id: "graph2",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              bullet: "round",
              lineThickness: 3,
              bulletSize: 7,
              bulletBorderAlpha: 1,
              bulletColor: "#FFFFFF",
              useLineColorForBulletBorder: true,
              bulletBorderThickness: 3,
              fillAlphas: 0,
              fontSize: chartValueFontSize,
              lineAlpha: 1,
              title: yAxisArray[1],
              valueField: yAxisArray[1],
            },
          ];
||||||| .r13309
    var subChartInit = function (metaDataId, event, defaultCriteriaData, mainMetaDataId) {
        var eventData = !event.hasOwnProperty('item') ? event.dataItem.dataContext : event.item.dataContext;
        var postData = {metaDataId: metaDataId, subCriteriaData: eventData, defaultCriteriaData: defaultCriteriaData};
        
        if (event.graph.valueField && eventData && eventData.hasOwnProperty('yaxisNameConfig')) {
            
            var valueField = event.graph.valueField;
            var yaxisNameConfig = eventData.yaxisNameConfig;
            
            if (yaxisNameConfig.hasOwnProperty(valueField)) {
                postData.drillField = yaxisNameConfig[valueField];
            }
            
            delete postData.subCriteriaData.yaxisNameConfig;
=======
    var subChartInit = function (metaDataId, event, defaultCriteriaData, mainMetaDataId) {
        var eventData = !event.hasOwnProperty('item') ? event.dataItem.dataContext : event.item.dataContext;
        var postData = {metaDataId: metaDataId, subCriteriaData: eventData, defaultCriteriaData: defaultCriteriaData};
        
        if (event.hasOwnProperty('graph') 
            && (event.graph).hasOwnProperty('valueField') 
            && event.graph.valueField 
            && eventData 
            && eventData.hasOwnProperty('yaxisNameConfig')) {
            
            var valueField = event.graph.valueField;
            var yaxisNameConfig = eventData.yaxisNameConfig;
            
            if (yaxisNameConfig.hasOwnProperty(valueField)) {
                postData.drillField = yaxisNameConfig[valueField];
            }
            
            delete postData.subCriteriaData.yaxisNameConfig;
>>>>>>> .r13341
        }
        break;
      }
      case "am_dual":
      case "am_radar_chart": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        if (Object.keys(yAxisArray).length > 0) {
          var yAxisArray2 = yAxisArray[0].split("_");

          graphs = [
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              fillAlphas: 1,
              title: yAxisArray2[0],
              type: "column",
              valueField: yAxisArray2[1],
              fontSize: chartValueFontSize,
              dashLengthField: "dashLengthColumn",
            },
          ];

          if (yAxisArray[1]) {
            var yAxisArray3 = yAxisArray[1].split("_");
            graphs.push({
              id: "graph2",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              bullet: "round",
              lineThickness: 3,
              bulletSize: 7,
              bulletBorderAlpha: 1,
              bulletColor: "#FFFFFF",
              useLineColorForBulletBorder: true,
              bulletBorderThickness: 3,
              fillAlphas: 0,
              lineAlpha: 1,
              title: yAxisArray3[0],
              fontSize: chartValueFontSize,
              valueField: yAxisArray3[1],
            });
          }
        }
        break;
      }
      case "clustered_bar_chart":
      case "clustered_bar_chart_horizontal":
      case "am_3d_stacked_column_chart": {
        graphs = [];
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);

        $.each(yAxisArray, function (key, yAxisSingle) {
          var yAxisSingleSpited = yAxisSingle.split("_");
          var yAxisValueField = yAxisSingle.replace(
            yAxisSingleSpited[0] + "_",
            ""
          );
          tempObject = {
            lineAlpha: "0.2",
            type: "column",
            fillAlphas: 0.8,
            fontSize: chartValueFontSize,
            dashLengthField: "dashLengthColumn",
            balloonText:
              "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
            title: yAxisSingleSpited[0],
            valueField: yAxisValueField,
          };
          graphs.push(tempObject);
        });
        break;
      }
      case "am_serial": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        graphs = [
          {
            alphaField: "alpha",
            balloonText:
              "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
            fillAlphas: 1,
            title: yAxisArray[0],
            type: "column",
            valueField: yAxisArray[0],
            fontSize: chartValueFontSize,
            dashLengthField: "dashLengthColumn",
          },
          {
            id: "graph2",
            balloonText:
              "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
            bullet: "round",
            lineThickness: 3,
            bulletSize: 7,
            bulletBorderAlpha: 1,
            bulletColor: "#FFFFFF",
            useLineColorForBulletBorder: true,
            bulletBorderThickness: 3,
            fillAlphas: 0,
            lineAlpha: 1,
            fontSize: chartValueFontSize,
            title: yAxisArray[1],
            valueField: yAxisArray[1],
          },
        ];
        break;
      }
      case "am_threed_cylinder_chart": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        graphs = [
          {
            balloonText: "[[category]]: <b>[[value]]</b>",
            colorField: "color",
            fillAlphas: 0.85,
            lineAlpha: 0.1,
            type: "column",
            labelText: "[[value]]",
            fontSize: chartValueFontSize,
            valueField: yAxisArray[0],
          },
        ];
        break;
      }
      case "am_zoomable_value_axis": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        graphs = [
          {
            id: "g1",
            balloon: {
              drop: true,
              position: "left",
              adjustBorderColor: false,
              color: "#ffffff",
              type: "smoothedLine",
            },
            fillAlphas: 0.2,
            bullet: "round",
            bulletBorderAlpha: 1,
            bulletColor: "#FFFFFF",
            bulletSize: 5,
            hideBulletsCount: 50,
            lineThickness: 1,
            title: "red line",
            useLineColorForBulletBorder: true,
            valueField: yAxisArray,
            fontSize: chartValueFontSize,
            balloonText: "<span style='font-size:10px;'>[[value]]</span>",
          },
        ];
        break;
      }
      case "am_trend_lines": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        graphs = [
          {
            balloonText:
              "[[category]]<br><b><span style='font-size:10px;'>value:[[value]]</span></b>",
            bullet: "round",
            dashLength: 3,
            fontSize: chartValueFontSize,
            colorField: "color",
            valueField: yAxisArray,
          },
        ];
        if (
          typeof response.series.xAxisGroupValue != "undefined" &&
          response.series.xAxisGroupValue.length != 0
        )
          trendLines = response.series.xAxisGroupValue;
        break;
      }
      case "am_reversed": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        graphs = [];
        $.each(yAxisArray, function (i, dtl) {
          tempObject = {
            balloonText: "[[category]]: [[value]]",
            bullet: "round",
            fontSize: chartValueFontSize,
            labelText: "[[value]]",
            title: dtl,
            valueField: dtl,
            fillAlphas: 0,
          };
          graphs.push(tempObject);
        });
        break;
      }
      case "am_3d_stacked_column_chart_2": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        graphs = [];
        var themeColor = cTheme.split(" ");
        $.each(yAxisArray, function (i, dtl) {
          if (themeColor.length > 0) {
            tempObject = {
              balloonText:
                "[[title]]<br><span style='font-size:11px;'><b>[[value]]</b> ([[percents]]%)</span>",
              fillAlphas: 0.8,
              fillColors: themeColor[i],
              labelText: "[[percents]]% ([[value]])",
              showAllValueLabels: true,
              lineAlpha: 0.3,
              title: dtl,
              fontSize: chartValueFontSize,
              type: "column",
              color: "#000000",
              valueField: dtl,
            };
          } else {
            tempObject = {
              balloonText:
                "[[title]]<br><span style='font-size:11px;'><b>[[value]]</b> ([[percents]]%)</span>",
              fillAlphas: 0.8,
              labelText: "[[percents]]% ([[value]])",
              showAllValueLabels: true,
              lineAlpha: 0.3,
              title: dtl,
              type: "column",
              color: "#000000",
              fontSize: chartValueFontSize,
              valueField: dtl,
            };
          }
          graphs.push(tempObject);
        });
        break;
      }
      case "am_stacked_bar_chart": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);

        graphs = [];
        var themeColor = cTheme.split(" ");
        $.each(yAxisArray, function (i, dtl) {
          if (themeColor.length > 0) {
            tempObject = {
              balloonText:
                "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
              fillAlphas: 0.8,
              fillColors: themeColor[i],
              labelText: "[[value]]",
              lineAlpha: 0.3,
              title: dtl,
              fontSize: chartValueFontSize,
              type: "column",
              color: "#000000",
              valueField: dtl,
              labelFunction: function (item) {
                return rtrim(
                  rtrim(
                    rtrim(
                      number_format(Math.abs(item.values.value), "2", ".", ","),
                      "0"
                    ),
                    "0"
                  ),
                  "."
                );
              },
            };
          } else {
            tempObject = {
              balloonText:
                "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
              fillAlphas: 0.8,
              labelText: "[[value]]",
              lineAlpha: 0.3,
              title: dtl,
              type: "column",
              fontSize: chartValueFontSize,
              color: "#000000",
              valueField: dtl,
              labelFunction: function (item) {
                return rtrim(
                  rtrim(
                    rtrim(
                      number_format(Math.abs(item.values.value), "2", ".", ","),
                      "0"
                    ),
                    "0"
                  ),
                  "."
                );
              },
            };
          }
          graphs.push(tempObject);
        });

        if (
          response.addonSettings &&
          response.addonSettings.stackx &&
          response.addonSettings.stacky
        ) {
          var yAxisArray = chartTitleSeparatorSplit(response.series2.yAxisName);
          var themeColor = cTheme.split(" ");
          $.each(yAxisArray, function (i, dtl) {
            if (themeColor.length > 0) {
              tempObject = {
                balloonText:
                  "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                fillAlphas: 0.8,
                fillColors: themeColor[i],
                labelText: "[[value]]",
                lineAlpha: 0.3,
                title: dtl,
                fontSize: chartValueFontSize,
                type: "column",
                color: "#000000",
                valueField: dtl,
                labelFunction: function (item) {
                  return rtrim(
                    rtrim(
                      rtrim(
                        number_format(
                          Math.abs(item.values.value),
                          "2",
                          ".",
                          ","
                        ),
                        "0"
                      ),
                      "0"
                    ),
                    "."
                  );
                },
              };
            } else {
              tempObject = {
                balloonText:
                  "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                fillAlphas: 0.8,
                labelText: "[[value]]",
                lineAlpha: 0.3,
                title: dtl,
                type: "column",
                fontSize: chartValueFontSize,
                color: "#000000",
                valueField: dtl,
                labelFunction: function (item) {
                  return rtrim(
                    rtrim(
                      rtrim(
                        number_format(
                          Math.abs(item.values.value),
                          "2",
                          ".",
                          ","
                        ),
                        "0"
                      ),
                      "0"
                    ),
                    "."
                  );
                },
              };
            }
            if (i === 0) {
              tempObject["newStack"] = true;
            }
            graphs.push(tempObject);
          });
          console.log(`graphs`, graphs);
        }

        break;
      }
      case "am_cylinder_gauge": {
        var xAxisName = response.series.xAxisName;
        var yAxisName = response.series.yAxisName;

        graphs = [
          {
            type: "column",
            topRadius: 1,
            columnWidth: 1,
            showOnAxis: true,
            lineThickness: 2,
            fontSize: chartValueFontSize,
            lineAlpha: 0.5,
            lineColor: "#FFFFFF",
            fillColors: "#8d003b",
            fillAlphas: 0.8,
            valueField: xAxisName,
          },
          {
            type: "column",
            topRadius: 1,
            columnWidth: 1,
            showOnAxis: true,
            lineThickness: 2,
            lineAlpha: 0.5,
            fontSize: chartValueFontSize,
            lineColor: "#cdcdcd",
            fillColors: "#cdcdcd",
            fillAlphas: 0.5,
            valueField: yAxisName,
          },
        ];
        yAxisName = response.series.title;
        break;
      }
      case "am_combined_bullet": {
        graphs = [];
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        var tempObject = {};
        var c = 1;
        var themeColor = cTheme.split(" ");
        $.each(yAxisArray, function (i, dtl) {
          if (i % 2 == 1) {
            tempObject = {
              title: dtl,
              valueField: dtl,
              id: "g" + c,
              valueAxis: "v1",
              lineColor: themeColor[i],
              fillColors: themeColor[i],
              fillAlphas: 1,
              fontSize: chartValueFontSize,
              type: "column",
              clustered: false,
              columnWidth: 0.3,
              legendValueText: "$[[value]]",
              balloonText:
                "[[title]]<br/><b style='font-size: 130%'>$[[value]]</b>",
            };
          } else {
            tempObject = {
              title: dtl,
              valueField: dtl,
              id: "g" + c,
              valueAxis: "v2",
              bullet: "round",
              bulletBorderAlpha: 1,
              bulletColor: "#FFFFFF",
              bulletSize: 5,
              fontSize: chartValueFontSize,
              hideBulletsCount: 50,
              lineThickness: 2,
              lineColor: themeColor[i],
              type: "smoothedLine",
              dashLength: 5,
              useLineColorForBulletBorder: true,
              balloonText:
                "[[title]]<br/><b style='font-size: 130%'>[[value]]</b>",
            };
          }
          c++;
          graphs.push(tempObject);
        });
        break;
      }
      case "durarion_onvalue_axis": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        var valueTitle = response.valueAxisTitle
          ? response.valueAxisTitle.split("_")
          : "";
        var catTitle = response.categoryAxisTitle.split("_");
        valueTitle = valueTitle.length === 2 ? valueTitle[1] : valueTitle[0];
        catTitle = catTitle.length === 2 ? catTitle[1] : catTitle[0];

        if (yAxisArray.length === 2) {
          var getColors =
            "#8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613".split(
              " "
            );
          var getColor =
            getColors[Math.floor(Math.random() * getColors.length)];
          var getColor2 =
            getColors[Math.floor(Math.random() * getColors.length)];
          if (getColor2 == getColor) {
            getColor2 = getColors[Math.floor(Math.random() * getColors.length)];
          }

          graphs = [
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 0.7,
              lineColor: getColor,
              legendValueText: "[[value]]",
              title: valueTitle,
              type: "column",
              fontSize: chartValueFontSize,
              valueField: yAxisArray[0],
              valueAxis: "distanceAxis",
            },
            {
              bullet: "square",
              lineColor: getColor2,
              bulletBorderAlpha: 1,
              bulletBorderThickness: 1,
              dashLengthField: "dashLength",
              legendValueText: "[[value]]",
              fontSize: chartValueFontSize,
              title: catTitle,
              fillAlphas: 0,
              valueField: yAxisArray[1],
              valueAxis: "durationAxis",
            },
          ];
        } else {
          graphs = [
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 0.7,
              legendValueText: "[[value]]",
              title: yAxisArray[0],
              type: "column",
              valueField: yAxisArray[0],
              fontSize: chartValueFontSize,
              valueAxis: "distanceAxis",
            },
            {
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              bullet: "round",
              bulletBorderAlpha: 1,
              useLineColorForBulletBorder: true,
              bulletColor: "#FFFFFF",
              bulletSizeField: "townSize",
              dashLengthField: "dashLength",
              fontSize: chartValueFontSize,
              descriptionField: "townName",
              labelPosition: "right",
              labelText: "[[townName2]]",
              legendValueText: "[[value]]",
              title: yAxisArray[1],
              fillAlphas: 0,
              valueField: yAxisArray[1],
              valueAxis: "latitudeAxis",
            },
            {
              bullet: "square",
              bulletBorderAlpha: 1,
              bulletBorderThickness: 1,
              dashLengthField: "dashLength",
              legendValueText: "[[value]]",
              title: yAxisArray[2],
              fontSize: chartValueFontSize,
              fillAlphas: 0,
              valueField: yAxisArray[2],
              valueAxis: "durationAxis",
            },
          ];
        }
        break;
      }
      case "durarion_onvalue_axis2": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        var valueTitle = response.valueAxisTitle.split("_");
        var catTitle = response.categoryAxisTitle.split("_");
        valueTitle = valueTitle.length === 2 ? valueTitle[1] : valueTitle[0];
        catTitle = catTitle.length === 2 ? catTitle[1] : catTitle[0];
        var getColors =
          "#8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613".split(
            " "
          );
        var getColor = getColors[Math.floor(Math.random() * getColors.length)];
        var getColor2 = getColors[Math.floor(Math.random() * getColors.length)];
        if (getColor2 == getColor) {
          getColor2 = getColors[Math.floor(Math.random() * getColors.length)];
        }

        var yAxisSingleSpited = yAxisArray[0].split("_");
        var yAxisSingleSpited1 = yAxisArray[1].split("_");

        var yAxisValueField = yAxisArray[0].replace(
          yAxisSingleSpited[0] + "_",
          ""
        );
        var yAxisValueField1 = yAxisArray[1].replace(
          yAxisSingleSpited1[0] + "_",
          ""
        );

        if (yAxisArray.length === 2) {
          graphs = [
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 0.7,
              lineColor: getColor,
              legendValueText: "[[value]]",
              fontSize: chartValueFontSize,
              title: yAxisSingleSpited[0],
              type: "column",
              valueField: yAxisValueField,
              valueAxis: "distanceAxis",
            },
            {
              bullet: "square",
              lineColor: getColor2,
              showHandOnHover: true,
              labelText: "[[value]]",
              classNameField: "testclassadd",
              fontSize: chartValueFontSize,
              dashLengthField: "dashLength",
              legendValueText: "[[value]]",
              bulletBorderAlpha: 1,
              bulletBorderThickness: 1,
              dashLengthField: "dashLength",
              title: yAxisSingleSpited1[0],
              fillAlphas: 0,
              valueField: yAxisValueField1,
              valueAxis: "durationAxis",
            },
          ];
        } else if (yAxisArray.length === 4) {
          var yAxisSingleSpited2 = yAxisArray[2].split("_");
          var yAxisSingleSpited3 = yAxisArray[3].split("_");
          var yAxisValueField2 = yAxisArray[2].replace(
            yAxisSingleSpited2[0] + "_",
            ""
          );
          var yAxisValueField3 = yAxisArray[3].replace(
            yAxisSingleSpited3[0] + "_",
            ""
          );

          graphs = [
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 1,
              lineColor: getColor,
              fontSize: chartValueFontSize,
              legendValueText: "[[value]]",
              title: yAxisSingleSpited[0],
              type: "column",
              valueField: yAxisValueField,
              valueAxis: "distanceAxis",
            },
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 0.7,
              lineColor: getColor2,
              legendValueText: "[[value]]",
              fontSize: chartValueFontSize,
              title: yAxisSingleSpited1[0],
              type: "column",
              valueField: yAxisValueField1,
              valueAxis: "distanceAxis",
            },
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 0.7,
              fontSize: chartValueFontSize,
              lineColor: "#4e8539",
              legendValueText: "[[value]]",
              title: yAxisSingleSpited2[0],
              type: "column",
              valueField: yAxisValueField2,
              valueAxis: "distanceAxis",
            },
            {
              bullet: "square",
              bulletBorderAlpha: 1,
              bulletBorderThickness: 1,
              lineColor: "#8fff08",
              fontSize: chartValueFontSize,
              showHandOnHover: true,
              labelText: "[[value]]",
              classNameField: "testclassadd",
              dashLengthField: "dashLength",
              legendValueText: "[[value]]",
              title: yAxisSingleSpited3[0],
              fillAlphas: 0,
              valueField: yAxisValueField3,
              valueAxis: "durationAxis",
            },
          ];
        } else {
          var yAxisSingleSpited2 = yAxisArray[2].split("_");
          var yAxisValueField2 = yAxisArray[2].replace(
            yAxisSingleSpited2[0] + "_",
            ""
          );
          graphs = [
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 1,
              lineColor: getColor,
              legendValueText: "[[value]]",
              fontSize: chartValueFontSize,
              title: yAxisSingleSpited[0],
              type: "column",
              valueField: yAxisValueField,
              valueAxis: "distanceAxis",
            },
            {
              alphaField: "alpha",
              balloonText:
                "<span style='font-size:10px;'>[[title]]:<br><span style='font-size:10px;'>[[value]]</span> [[additional]]</span>",
              dashLengthField: "dashLength",
              fillAlphas: 0.7,
              lineColor: getColor2,
              fontSize: chartValueFontSize,
              legendValueText: "[[value]]",
              title: yAxisSingleSpited1[0],
              type: "column",
              valueField: yAxisValueField1,
              valueAxis: "distanceAxis",
            },
            {
              bullet: "square",
              bulletBorderAlpha: 1,
              bulletBorderThickness: 1,
              fontSize: chartValueFontSize,
              lineColor: "#8fff08",
              showHandOnHover: true,
              labelText: "[[value]]",
              classNameField: "testclassadd",
              dashLengthField: "dashLength",
              legendValueText: "[[value]]",
              title: yAxisSingleSpited2[0],
              fillAlphas: 0,
              valueField: yAxisValueField2,
              valueAxis: "durationAxis",
            },
          ];
        }
        break;
      }
      case "multiple_value_axis": {
        var yAxisArray = chartTitleSeparatorSplit(response.series.yAxisName);
        graphs = [
          {
            valueAxis: "v1",
            lineColor: "#FF6600",
            bullet: "round",
            bulletBorderThickness: 1,
            hideBulletsCount: 30,
            title: yAxisArray[0],
            valueField: yAxisArray[0],
            fillAlphas: 0,
          },
          {
            valueAxis: "v2",
            lineColor: "#FCD202",
            bullet: "square",
            bulletBorderThickness: 1,
            hideBulletsCount: 30,
            title: yAxisArray[1],
            valueField: yAxisArray[1],
            fillAlphas: 0,
          },
          {
            valueAxis: "v3",
            lineColor: "#B0DE09",
            bullet: "triangleUp",
            bulletBorderThickness: 1,
            hideBulletsCount: 30,
            title: yAxisArray[2],
            valueField: yAxisArray[2],
            fillAlphas: 0,
          },
          {
            valueAxis: "v3",
            lineColor: "#B0DE09",
            bullet: "triangleUp",
            bulletBorderThickness: 1,
            hideBulletsCount: 30,
            title: yAxisArray[3],
            valueField: yAxisArray[3],
            fillAlphas: 0,
          },
          {
            valueAxis: "v3",
            lineColor: "#B0DE09",
            bullet: "triangleUp",
            bulletBorderThickness: 1,
            hideBulletsCount: 30,
            title: yAxisArray[4],
            valueField: yAxisArray[4],
            fillAlphas: 0,
          },
        ];
        break;
      }
    }

    return {
      data: data,
      xAxisName: xAxisName,
      yAxisName: yAxisName,
      cTheme: cTheme,
      isBar: isBar,
      graphs: graphs,
      isTitle: response.isUseLegend,
      isShowTitle: response.isTitle,
      trendLines: trendLines,
      xLabelRotation: xLabelRotation,
      legendPosition: response.legendPosition,
      labelTextSubStr: response.labelTextSubStr,
      realLegendPosition: response.realLegendPosition,
      valueAxisTitle: response.valueAxisTitle,
      valueAxesMax: response.valueAxesMax,
      valueAxesMin: response.valueAxesMin,
      colorField: response.colorField,
      categoryAxisTitle: response.categoryAxisTitle,
      isLegend:
        typeof response.isLegend !== "undefined" ? response.isLegend : "1",
      colorsSet:
        typeof response.colorsSet !== "undefined" ? response.colorsSet : null,
      addonSettings: response.addonSettings,
    };
  };

  var subChartInit = function (
    metaDataId,
    event,
    defaultCriteriaData,
    mainMetaDataId
  ) {
    var eventData = !event.hasOwnProperty("item")
      ? event.dataItem.dataContext
      : event.item.dataContext;
    var postData = {
      metaDataId: metaDataId,
      subCriteriaData: eventData,
      defaultCriteriaData: defaultCriteriaData,
    };

    if (
      event.graph.valueField &&
      eventData &&
      eventData.hasOwnProperty("yaxisNameConfig")
    ) {
      var valueField = event.graph.valueField;
      var yaxisNameConfig = eventData.yaxisNameConfig;

      if (yaxisNameConfig.hasOwnProperty(valueField)) {
        postData.drillField = yaxisNameConfig[valueField];
      }

      delete postData.subCriteriaData.yaxisNameConfig;
    }

    $.ajax({
      type: "post",
      url: "mddashboard/getSubDataForAmchart",
      dataType: "json",
      data: postData,
      beforeSend: function () {
        Core.blockUI({ animate: true });
      },
      success: function (response) {
        if (response.status == "error") {
          Core.unblockUI();
          if (typeof response.message !== "undefined") {
            new PNotify({
              title: response.status,
              text: response.message,
              type: response.status,
              sticker: false,
            });
          }
          return;
        }

        if (
          typeof response.metaType !== "undefined" &&
          response.metaType != "metagroup"
        ) {
          if ($("[id^=dashboard-" + response.linkedMetaDataId + "_]").length) {
            var dashUniqId = $(
              "[id^=dashboard-" + response.linkedMetaDataId + "_]"
            )
              .eq(0)
              .attr("id")
              .split("_");
            mainMetaDataId = response.linkedMetaDataId + "_" + dashUniqId[1];
          }

          $("#dashboard-" + mainMetaDataId).width(response.width);
          $("#dashboard-title-" + mainMetaDataId)
            .attr("title", response.title)
            .html(response.title);

          $("#dashboard-" + mainMetaDataId).attr("style", "text-align: left;");
          if (response.error !== null) {
            Core.unblockUI();
            new PNotify({
              title: "Error",
              text: response.error,
              type: "error",
              sticker: false,
            });
          }

          $(".dashboard-filter-form-" + metaDataId).html(
            response.defaultCriteria
          );
          if (
            typeof response.series.yAxisName != "undefined" &&
            response.series.yAxisName != false
          ) {
          } else {
            ChartsAmcharts.drawChartAmchart(
              _defaultCriteriaData,
              _chartType,
              _metaDataId
            );
            Core.unblockUI();
            return false;
          }
          var subconfig = ChartsAmcharts.getConfig(
            response,
            metaDataId,
            response.chartType
          );
          ChartsAmcharts.init(
            response.chartType,
            "dashboard-" + mainMetaDataId,
            subconfig.data,
            subconfig,
            response.linkedMetaDataId,
            response.DRILLDOWN,
            mainMetaDataId,
            response
          );

          // $('#dashboard-' + mainMetaDataId).attr('style', 'text-align: left; overflow: hidden;');
          if (response.height) {
            $("#dashboard-" + mainMetaDataId).height(response.height);
          }
          $(".back-btn-dashboard-" + mainMetaDataId).removeClass("hidden");
        } else {
          if (
            response.hasOwnProperty("showType") &&
            response.showType == "tab"
          ) {
            appMultiTabByContent({
              metaDataId: response.linkMetaDataId,
              title: response.listName,
              type: "dataview",
              content: response.data,
            });
          } else {
            /* $('#dashboard-' + mainMetaDataId).html('<div class="col-md-12 main-dataview-container pl0 pr0" id="object-value-list-'+ response.linkMetaDataId +'">'+ response.data.Html + '</div>'); */
            $("#dashboard-" + mainMetaDataId).html(
              '<div class="col-md-12 main-dataview-container pl0 pr0" id="object-value-list-' +
                response.linkMetaDataId +
                '">' +
                response.data +
                "</div>"
            );
            $(".remove-type-" + response.linkMetaDataId).remove();
            $(".div-objectdatagrid-" + response.linkMetaDataId)
              .addClass("pl0 pr0")
              .attr("style", "height: 350px !important;");
            $("#objectdatagrid-" + response.linkMetaDataId)
              .attr("style", "height: 350px !important;")
              .attr("height", "350");
            /*dataViewReload(response.linkMetaDataId);*/
          }
        }

        Core.unblockUI();
      },
    }).done(function () {
      $("#dashboard-container-" + metaDataId)
        .find(".open")
        .removeClass("open");
    });
  };

  var gantt = function (
    container,
    data,
    config,
    metaDataId,
    dataChart,
    drillDown,
    mainMetaDataId,
    responseData
  ) {
    var chart = new AmCharts.makeChart(container, {
      type: "gantt",
      theme: "light",
      marginRight: 70,
      period: "DD",
      dataDateFormat: "YYYY-MM-DD",
      columnWidth: 0.5,
      valueAxis: {
        type: "date",
      },
      brightnessStep: 7,
      graph: {
        fillAlphas: 1,
        lineAlpha: 1,
        lineColor: "#fff",
        fillAlphas: 0.85,
        balloonText: "<b>[[count]]</b>",
      },
      rotate: true,
      categoryField: "category",
      segmentsField: "segments",
      colorField: "color",
      startDateField: "startdate",
      endDateField: "enddate",
      dataProvider: responseData.series,
      valueScrollbar: {
        autoGridCount: true,
      },
      chartCursor: {
        cursorColor: "#55bb76",
        valueBalloonsEnabled: false,
        cursorAlpha: 0,
        valueLineAlpha: 0.5,
        valueLineBalloonEnabled: true,
        valueLineEnabled: true,
        zoomable: false,
        valueZoomable: true,
      },
      export: {
        enabled: true,
      },
    });
  };

  //<editor-fold defaultstate="collapsed" desc="donut&pie chart value grouping. DEV-6710">

  var getDataGrouping = function (data, config) {
    if (typeof data !== "undefined") {
      var tmpData = {},
        isGrouped = false,
        tmpDataGrouped = [];

      $.each(data, function (key, value) {
        if (typeof tmpData[value[config.xAxisName]] === "undefined") {
          tmpData[value[config.xAxisName]] = !isNaN(value[config.yAxisName])
            ? value[config.yAxisName]
            : 0;
        } else {
          tmpData[value[config.xAxisName]] =
            parseFloat(tmpData[value[config.xAxisName]]) +
            value[config.yAxisName];
          isGrouped = true;
        }
      });

      if (isGrouped) {
        var cnt = 0;
        $.each(tmpData, function (key, value) {
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

  //</editor-fold>

  return {
    init: function (
      dataChart,
      container,
      data,
      config,
      metaDataId,
      drillDown,
      mainMetaDataId,
      responseData
    ) {
      switch (dataChart) {
        case "am_serial": {
          serial(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_radar_chart": {
          radar(container, data, config, metaDataId, drillDown, mainMetaDataId);
          break;
        }
        case "am_column": {
          column(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "pie_charts_bullets": {
          pie_charts_bullets(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "risk_heatmap": {
          risk_heatmap(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "animated_xy_bubble": {
          animated_xy_bubble(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "percent_stacked_area_chart": {
          percent_stacked_area_chart(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "variable_radius_radar": {
          variable_radius_radar(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_threed_cylinder_chart": {
          cylinder(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_bar": {
          bar(container, data, config, metaDataId, drillDown, mainMetaDataId);
          break;
        }
        case "am_bar_axis": {
          barAxis(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "durarion_onvalue_axis": {
          durationOnvalueAxis(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "durarion_onvalue_axis2": {
          durationOnvalueAxis2(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "multiple_value_axis": {
          multipleValueAxis(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_donut": {
          donut(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId,
            responseData
          );
          break;
        }
        case "am_pie": {
          pie(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId,
            responseData
          );
          break;
        }
        case "am_dual": {
          dual(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_gantt": {
          gantt(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId,
            responseData
          );
          break;
        }
        case "clustered_bar_chart": {
          clustered(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "clustered_bar_chart_horizontal": {
          clusteredHorizontal(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_stacked_bar_chart": {
          stacked(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_reversed": {
          reverse(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId,
            responseData
          );
          break;
        }
        case "am_zoomable_value_axis": {
          zoomable(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_trend_lines": {
          trendLine(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_threed_funnel": {
          treedFunnel(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_funnel": {
          funnel(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_cylinder_gauge": {
          cylinderGauge(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_angular_gauge": {
          angularGauge(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_combined_bullet": {
          combined(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_mixed_dialy_and_intra_day_chart": {
          mixedDialy(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_3d_stacked_column_chart": {
          threeDStackedClustered(
            container,
            data,
            config,
            metaDataId,
            dataChart,
            drillDown,
            mainMetaDataId
          );
          break;
        }
        case "am_3d_stacked_column_chart_2": {
          stackedColumn(
            container,
            data,
            config,
            metaDataId,
            drillDown,
            mainMetaDataId
          );
          break;
        }
      }
    },

    drawChartAmchart: function (
      defaultCriteriaData,
      chartType,
      metaDataId,
      callback,
      workSpaceParams,
      workSpaceId,
      criteriaPosition
    ) {
      drawChartAmchart(
        defaultCriteriaData,
        chartType,
        metaDataId,
        callback,
        workSpaceParams,
        workSpaceId,
        criteriaPosition
      );
    },

    getConfig: function (response, metaDataId, chartType) {
      return getConfig(response, metaDataId, chartType);
    },

    subChartInit: function (
      metaDataId,
      event,
      defaultCriteriaData,
      mainMetaDataId
    ) {
      subChartInit(metaDataId, event, defaultCriteriaData, mainMetaDataId);
    },
  };
})();
