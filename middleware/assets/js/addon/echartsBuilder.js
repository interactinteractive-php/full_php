var isKpiIndicatorEchartsScript = true;
var googleMapActiveWindow = null;
var kpIndicatorChart = {};

var EchartBuilder = function() {

    var convertAmount = function (num) {
        if (num >= 1000000000000) {
            return Math.floor(num / 1000000000000, 2) + "t ";
        } else {
            if (num >= 1000000000) {
                return Math.floor(num / 1000000000, 2) + "b ";
            } else {
                if (num >= 1000000) {
                    return Math.floor(num / 1000000, 2) + "m ";
                } else {
                    if (num >= 1000) {
                        return Math.floor(num / 1000, 2) + "k ";
                    } else {
                        return num;
                    }
                }
            }
        }
    }

    var pushToAry = function (name, val) {
        var obj = {};
        obj[name] = val;
        
        return obj;
    }
     
    var clearOptions = function (options) {
        $.each(options, function (i, r) {
            if (typeof r === 'object') {
                clearOptions(r);
            }
            if (r === "{digital}") {
                var key = i.replace('_main', '');
                options[key] = function (value, index) {
                    return convertAmount (value);
                };
            }
        });
        return options;
    }
    
    var recursivePushObj = function (tmpObj, tmp, value) {

        if (typeof tmp[tmpObj[0]] === 'undefined') {
            tmp[tmpObj[0]] = {};
        }
        
        if (tmpObj.length === 3 ) {
            if (typeof tmp[tmpObj[0]][tmpObj[1]] === 'undefined') {
                tmp[tmpObj[0]][tmpObj[1]] = {};
            }

            if (value === '{digital}') {
                if (tmpObj[2] = 'formatter_main') {
                    tmp[tmpObj[0]][tmpObj[1]]['formatter'] = function (value, index) {
                        return convertAmount (value);
                    };
                    tmp[tmpObj[0]][tmpObj[1]]['formatter_main'] = value;    
                } else {
                    tmp[tmpObj[0]][tmpObj[1]][tmpObj[2]] = function (value, index) {
                        return convertAmount (value);
                    };
                    tmp[tmpObj[0]][tmpObj[1]][tmpObj[2]+'_main'] = value;
                }
            } else {
                
                switch (tmpObj[2]) {
                    case 'radius':
                    case 'data':
                        tmp[tmpObj[0]][tmpObj[1]][tmpObj[2]] = JSON.parse(value);
                        break;
                
                    default:
                        tmp[tmpObj[0]][tmpObj[1]][tmpObj[2]] = value;
                        break;
                }
                
            }
        } else {

            switch (tmpObj[1]) {
                case 'radius':
                case 'data':
                    tmp[tmpObj[0]][tmpObj[1]] = JSON.parse(value);
                    break;
            
                default:
                    tmp[tmpObj[0]][tmpObj[1]] = value;
                    break;
            }
            
        }
    
        return tmp;
    }
     
    var convertOptions = function  (chartConfig, option) {
        var tmp = {}
        $.each(chartConfig, function (index, row) {
            if (row) {
                if (isNumeric(row)) {
                    row = parseFloat(row);
                }
                if (index.indexOf('_') > -1) {
                    var tmpObj = index.split('_');
                    tmp = recursivePushObj(tmpObj, tmp, row);
                } else {
                    tmp[index] = row;
                }
    
                option = {
                    ...option,
                    ...tmp,
                }
            }
        });
    
        return option;
    }
     
    var chartRender = function (obj) {
         
        var elemId = obj.elemId;
        if (!elemId) return false;

        var $mainSelector = $('#' + elemId).closest('.theme-builder');
        var chartConfig = obj.chartConfig;
        var useData = obj.useData;
        var type = chartConfig.type;
        var chartName = (chartConfig.hasOwnProperty('chartName')) ? chartConfig.chartName : type + '_chart';
        var category = chartConfig.axisX;
        var value = chartConfig.axisY;
        var aggregate = chartConfig.aggregate;
        var columnsConfig = obj.columnsConfig;
        var data = obj.data;
        var isRunInterval = false;
        var isLineChartConfig = false;
        var kpiLayoutIndex = 0;
        obj.isLayoutBuilder = false;
        if (!data) {
            return false;
        }
        
        if (typeof $mainSelector.attr('data-kpi-layout') !== 'undefined' && $mainSelector.attr('data-kpi-layout') !== '0') {
            isLayoutBuilder = true;
            obj.isLayoutBuilder = true;
            var _parentSelector = $('#' + elemId).closest('.layout-builder-v0');
            kpiLayoutIndex = $mainSelector.attr('data-kpi-layout');
            elemId = _parentSelector.find('div.selected-box[data-kl-col="'+ kpiLayoutIndex +'"] [data-section-code="'+ kpiLayoutIndex +'"]').attr('id');
        }
    
        if (aggregate == 'COUNT' && (value == '' || value === null)) {
            value = 'COUNT_COL';
        }
                
        if (chartConfig.hasOwnProperty('lineChartConfig') && chartConfig.lineChartConfig) {
            isLineChartConfig = true;
        }
                
        if (obj.hasOwnProperty('isRunInterval') && obj.isRunInterval) {
            isRunInterval = true;
            if (kpIndicatorChart.hasOwnProperty(elemId)) {
                
                var prevChart = kpIndicatorChart[elemId];
                
                if (type == 'stacked_column') {
                    var stackedData = kpiDataMartStackedColumnData(data, category, value, chartConfig);
                    data = stackedData.data;
                }
                
                prevChart.data = data;
                return;
            }
        }
    
        var themeGroup;
    
        if ($mainSelector.find('.theme-plan-group.selected').attr('data-rowdata')) {
            themeGroup = JSON.parse($mainSelector.find('.theme-plan-group.selected').attr('data-rowdata'));
        }
        
        try {
            var chartDom = document.getElementById(elemId);
            var option;
            echarts.dispose(chartDom);
        } catch (error) {
            console.log(error);
            Core.unblockUI();
        }
        
        var myChart = echarts.init(chartDom);
        var dataSet = data;
        var grid = {containLabel: true};
        option = {
            themeCode: chartConfig.themeCode,
        };
        
        /* console.clear(); */
        option = convertOptions(chartConfig, option);
        
        var axisLabel = {},
            yAxis = {
                type: 'value',
            },
            xAxis = {
                type: 'category',
                label: axisLabel,
            };
        
        if (typeof option.xAxis !== 'undefined' && typeof option.xAxis.axisLabel !== 'undefined') {
            xAxis['axisLabel'] = option.xAxis.axisLabel;
        }
        
        var seriesLabel = {
            position: 'middle',
            formatter: '{c}'
        };
    
        if (typeof chartConfig.axisLabelShow !== 'undefined') {
            axisLabel = {...axisLabel,
                show: chartConfig.axisLabelShow
            };
    
            seriesLabel = {...seriesLabel,
                show: chartConfig.axisLabelShow
            };
        }
        
        if (axisLabel.length > 0) {
            xAxis = {
                ...xAxis,
                label: axisLabel,
            }
        }
        
        if (typeof chartConfig.xyReverse !== 'undefined' && chartConfig.xyReverse) {
            var xAxisTmp = xAxis;
            xAxis = yAxis;
            yAxis = xAxisTmp;
        }

        var series = {
            data: dataSet,
            type: type,
            name: "",
            backgroundStyle: {
                color: 'rgba(180, 180, 180, 0.2)'
            },
            label: seriesLabel,
        };

        if (typeof option.series !== 'undefined') {
            var series = {
                ...option.series,
                data: dataSet,
                type: type,
                name: "",
                /* showBackground: true, */
                backgroundStyle: {
                    color: 'rgba(180, 180, 180, 0.2)'
                },
                label: seriesLabel,
            };
        }

        switch (type) {
            case 'maps': 
                myChart.showLoading();
                myChart.hideLoading();
    
                /* $.getJSON(URL_APP + 'middleware/assets/js/addon/data.json', function (data) { */
                    function getAirportCoord(idx) {
                        console.log(data.airports[idx]);
                        if (data.airports[idx]) {
                            return [data.airports[idx][3], data.airports[idx][4]];
                        }
                    }
                    var routes = data.routes.map(function (airline) {
                        console.log(airline);
                        return [getAirportCoord(airline[1]), getAirportCoord(airline[2])];
                    });
                    
                    option = {
                        geo3D: {
                        map: 'world',
                        shading: 'realistic',
                        silent: true,
                        environment: '#333',
                        realisticMaterial: {
                            roughness: 0.8,
                            metalness: 0
                        },
                        postEffect: {
                            enable: true
                        },
                        groundPlane: {
                            show: false
                        },
                        light: {
                            main: {
                            intensity: 1,
                            alpha: 30
                            },
                            ambient: {
                            intensity: 0
                            }
                        },
                        viewControl: {
                            distance: 70,
                            alpha: 89,
                            panMouseButton: 'left',
                            rotateMouseButton: 'right'
                        },
                        itemStyle: {
                            color: '#000'
                        },
                        regionHeight: 0.5
                        },
                        series: [
                        {
                            type: 'lines3D',
                            coordinateSystem: 'geo3D',
                            effect: {
                            show: true,
                            trailWidth: 1,
                            trailOpacity: 0.5,
                            trailLength: 0.2,
                            constantSpeed: 5
                            },
                            blendMode: 'lighter',
                            lineStyle: {
                            width: 0.2,
                            opacity: 0.05
                            },
                            data: routes
                        }
                        ]
                    };
                    
                    option && myChart.setOption(option);
                    $mainSelector.find('.kpi-dm-chart-create').attr('data-config', JSON.stringify(option));
    
                    window.addEventListener("resize", myChart.resize);
                    window.addEventListener('keydown', function () {
                        myChart.dispatchAction({
                        type: 'lines3DToggleEffect',
                        seriesIndex: 0
                        });
                    });
                /* }); */
                return false;
            break;
            case 'card' :
            case 'card_vertical' :
                var $elem = $('#' + elemId);
                if ($elem.length) {
                    
                    var html = [], cardAmountValue = 0;
                    
                    if (data.hasOwnProperty(0)) {
                        var dataRow = data[0];
                        if (dataRow.hasOwnProperty(value)) {
                            cardAmountValue = number_format(dataRow[value], 2, '.', ',');
                        }
                    }
                    
                    if (isRunInterval) {
                        var $card = $elem.closest('.bl-section'), $amountValue = $card.find('.kpi-card-amount-value');
                        
                        if ($amountValue.length) {
                            $amountValue.text(cardAmountValue);
                            return;
                        }
                    }
                    
                    var labelText = chartConfig.labelText;
                    
                    if (columnsConfig.hasOwnProperty('labelText') && columnsConfig.labelText != '') {
                        labelText = columnsConfig.labelText;
                    }
    
                    var cardConfigStyle = 'style="';
    
                    if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                        cardConfigStyle += 'background-color: '+chartConfig.bgColor + '; ';
                    }
    
                    if (chartConfig.grid_width != null && chartConfig.grid_width) {
                        cardConfigStyle += 'min-width: ' + chartConfig.grid_width + 'px; max-width: max-content !important;';
                    }
    
                    if (chartConfig.grid_height != null && chartConfig.grid_height) {
                        cardConfigStyle += 'min-height: ' + chartConfig.grid_height + 'px; max-height: max-content !important;';
                    }
    
                    if (chartConfig.grid_borderradius != null && chartConfig.grid_borderradius) {
                        cardConfigStyle += 'border-radius: '+ chartConfig.grid_borderradius +'px !important; ';
                    }
    
                    if (chartConfig.grid_left != null && chartConfig.grid_left) {
                        cardConfigStyle += 'padding-left: '+ chartConfig.grid_left +'px !important; ';
                    }
    
                    if (chartConfig.grid_right != null && chartConfig.grid_right) {
                        cardConfigStyle += 'padding-right: '+ chartConfig.grid_right +'px !important; ';
                    }
    
                    if (chartConfig.grid_top != null && chartConfig.grid_top) {
                        cardConfigStyle += 'padding-top: '+ chartConfig.grid_top +'px !important; ';
                    }
    
                    if (chartConfig.grid_bottom != null && chartConfig.grid_bottom) {
                        cardConfigStyle += 'padding-bottom: '+ chartConfig.grid_bottom +'px !important; ';
                    }
    
                    if (chartConfig.grid_alignment != null && chartConfig.grid_alignment) {
                        cardConfigStyle += 'text-align: '+ chartConfig.grid_alignment +' !important; ';
                    }
    
                    cardConfigStyle += '"';
    
                    if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                        html.push('<div class="card card-body bg-blue-400 p-2 pl15 pl15" '+ cardConfigStyle +'>');
                    } else {
                        html.push('<div class="card card-body p-2 pl15 pl15" '+ cardConfigStyle +'>');
                    }
                    
                    if (type === 'card') {
                        html.push('<div class="media">');
                            html.push('<div class="media-body">');
                                html.push('<h3 class="mb-0 kpi-card-amount-value">'+cardAmountValue+'</h3>');
                                html.push('<span class="text-uppercase font-size-xs">'+labelText+'</span>');
                            html.push('</div>');
                            
                            if (chartConfig.iconName != null && chartConfig.iconName != '') {
                                
                                html.push('<div class="ml-3 align-self-center">');
                                
                                if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                                    html.push('<i class="far '+chartConfig.iconName+' opacity-75" style="font-size: 48px"></i>');
                                } else {
                                    html.push('<i class="far '+chartConfig.iconName+' text-indigo-400" style="font-size: 48px"></i>');
                                }
                                
                                html.push('</div>');
                            }
                        
                        html.push('</div>');
                    } else {
                        var iconTextStyle = 'style="',
                            footerTextStyle = 'style="',
                            headerTextStyle = 'style="';
    
                        /* icon text style begin */
                        if (chartConfig.iconFontSize != null && chartConfig.iconFontSize) {
                            iconTextStyle += 'font-size: '+ chartConfig.iconFontSize +'px; ';
                            
                        } else {
                            iconTextStyle += 'font-size: 48px;';
                        }
                        
                        if (chartConfig.iconLeftPadding != null && chartConfig.iconLeftPadding) {
                            iconTextStyle += 'padding-left: '+ chartConfig.iconLeftPadding +'px !important; ';
                        }
    
                        if (chartConfig.iconRightPadding != null && chartConfig.iconRightPadding) {
                            iconTextStyle += 'padding-right: '+ chartConfig.iconRightPadding +'px !important; ';
                        }
    
                        if (chartConfig.iconTopPadding != null && chartConfig.iconTopPadding) {
                            iconTextStyle += 'padding-top: '+ chartConfig.iconTopPadding +'px !important; ';
                        }
    
                        if (chartConfig.iconBottomPadding != null && chartConfig.iconBottomPadding) {
                            iconTextStyle += 'padding-bottom: '+ chartConfig.iconBottomPadding +'px !important; ';
                        }
    
                        if (chartConfig.iconAlignment != null && chartConfig.iconAlignment) {
                            iconTextStyle += 'text-align: '+ chartConfig.iconAlignment +' !important; ';
                        }
                        /* header text style end */
    
                        /* header text style begin */
                        if (chartConfig.headFontSize != null && chartConfig.headFontSize) {
                            headerTextStyle += 'font-size: '+ chartConfig.headFontSize +'px"';
                        }
                        
                        if (chartConfig.headLeftPadding != null && chartConfig.headLeftPadding) {
                            headerTextStyle += 'padding-left: '+ chartConfig.headLeftPadding +'px !important; ';
                        }
    
                        if (chartConfig.headRightPadding != null && chartConfig.headRightPadding) {
                            headerTextStyle += 'padding-right: '+ chartConfig.headRightPadding +'px !important; ';
                        }
    
                        if (chartConfig.headTopPadding != null && chartConfig.headTopPadding) {
                            headerTextStyle += 'padding-top: '+ chartConfig.headTopPadding +'px !important; ';
                        }
    
                        if (chartConfig.headBottomPadding != null && chartConfig.headBottomPadding) {
                            headerTextStyle += 'padding-bottom: '+ chartConfig.headBottomPadding +'px !important; ';
                        }
    
                        if (chartConfig.headAlignment != null && chartConfig.headAlignment) {
                            headerTextStyle += 'text-align: '+ chartConfig.headAlignment +' !important; ';
                        }
                        /* header text style end */
    
                        /* footer text style begin */
                        if (chartConfig.footFontSize != null && chartConfig.footFontSize) {
                            footerTextStyle += 'font-size: '+ chartConfig.footFontSize +'px"';
                        }
                        
                        if (chartConfig.footLeftPadding != null && chartConfig.footLeftPadding) {
                            footerTextStyle += 'padding-left: '+ chartConfig.footLeftPadding +'px !important; ';
                        }
    
                        if (chartConfig.footRightPadding != null && chartConfig.footRightPadding) {
                            footerTextStyle += 'padding-right: '+ chartConfig.footRightPadding +'px !important; ';
                        }
    
                        if (chartConfig.footTopPadding != null && chartConfig.footTopPadding) {
                            footerTextStyle += 'padding-top: '+ chartConfig.footTopPadding +'px !important; ';
                        }
    
                        if (chartConfig.footBottomPadding != null && chartConfig.footBottomPadding) {
                            footerTextStyle += 'padding-bottom: '+ chartConfig.footBottomPadding +'px !important; ';
                        }
    
                        if (chartConfig.footAlignment != null && chartConfig.footAlignment) {
                            footerTextStyle += 'text-align: '+ chartConfig.footAlignment +' !important; ';
                        }
                        /* footer text style end */
    
                        iconTextStyle += '"', footerTextStyle += '"', headerTextStyle += '"';
    
                        html.push('<div class="media">');
                            html.push('<div class="media-body" style="display: grid">');
                            
                                if (chartConfig.iconName != null && chartConfig.iconName != '') {
                                    
                                    html.push('<div class="align-self-center">');
                                    
                                    if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                                        html.push('<i class="far '+chartConfig.iconName+' opacity-75" '+ iconTextStyle +'></i>');
                                    } else {
                                        html.push('<i class="far '+chartConfig.iconName+' text-indigo-400" '+ iconTextStyle +'></i>');
                                    }
                                    
                                    html.push('</div>');
                                }
    
                                html.push('<span class="text-uppercase font-size-xs" '+ headerTextStyle +'>'+labelText+'</span>');
                                html.push('<h3 class="mb-0 kpi-card-amount-value" '+ footerTextStyle +'>'+cardAmountValue+'</h3>');
                            html.push('</div>');
                            
                        
                        html.push('</div>');
                    }
                    html.push('</div>');
                    
                    var cardHtml = html.join('');
                    
                    if ($elem.hasClass('card-body')) {
                        
                        var $card = $elem.closest('.card');
                        
                        $card.addClass('d-none');
                        $card.after(cardHtml);
                        
                    } else {
                        $elem.empty().append(cardHtml);
                    }
                }
                
                return false;
                break;
            case 'radar':
                option = {
                    ...option,
                    radar: {
                        /* indicator: [
                        { name: 'Sales', max: 6500 },
                        { name: 'Administration', max: 16000 },
                        { name: 'Information Technology', max: 30000 },
                        { name: 'Customer Support', max: 38000 },
                        { name: 'Development', max: 52000 },
                        { name: 'Marketing', max: 25000 }
                        ] */
                        indicator: dataSet
                    },
                    series: [{
                        name: 'Budget vs spending',
                        type: 'radar',
                        data: dataSet
                    }]
                }
                break;
            case 'pie':
                series['radius']= '50%';
                series['emphasis'] = {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                };

                series = {
                    ...series,
                    ...option.series
                };
                
                option['series'] = series; 
                break;
            case 'bar_polar':
                var polarSer = {
                    data: dataSet,
                    type: 'bar',
                    /* showBackground: true, */
                    coordinateSystem: 'polar',
                    backgroundStyle: {
                        color: 'rgba(180, 180, 180, 0.2)'
                    },
                    label: {
                        show: false,
                        position: 'middle',
                        formatter: '{c}'
                    }
                };
                
                option = {
                    polar: {
                        radius: [30, '80%']
                    },
                    radiusAxis: {
                        max: function (value) {
                            return value.max - 20;
                        }
                    },
                    angleAxis: {
                        type: 'category',
                        data: obj.dataXaxis,
                        startAngle: 75
                    },
                    tooltip: {},
                    series: {
                        data: dataSet,
                        type: 'bar',
                        /* showBackground: true, */
                        coordinateSystem: 'polar',
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            show: false,
                            position: 'middle',
                            formatter: '{c}'
                        },
                        ...option.series
                    },
                };
                
                break;
            case 'stacked_column':
            case 'clustered_column':
            case 'bar_stacked':
            case 'stacked_bar':
            case 'line_bar':
            case 'bar_radial':
            case 'bar_label_rotation':
                option.type = 'bar';
            case 'line_stacked':
                option.type = 'line';
                var axisXColumn = (chartConfig.axisX).toLowerCase(); 
                var axisXGroup = (chartConfig.axisXGroup).toLowerCase(); 

                var tmp = {};
                var xAxisData = [],
                    legendData = [];

                $.each(obj.dataXaxis, function (index, row) {
                    if ($.inArray(row, legendData) === -1) {
                        legendData.push(row);
                    }
                });
                
                $.each(obj.data, function (index, row) {
                    if ($.inArray(row[axisXGroup], xAxisData) === -1) {
                        xAxisData.push(row[axisXGroup]);
                    }
                });

                /* begin main constant */
                
                var app = {};
                posList = [
                    'left',
                    'right',
                    'top',
                    'bottom',
                    'inside',
                    'insideTop',
                    'insideLeft',
                    'insideRight',
                    'insideBottom',
                    'insideTopLeft',
                    'insideTopRight',
                    'insideBottomLeft',
                    'insideBottomRight'
                ];

                app.configParameters = {
                    rotate: {
                        min: -90,
                        max: 90
                    },
                    align: {
                        options: {
                            left: 'left',
                            center: 'center',
                            right: 'right'
                        }
                    },
                    verticalAlign: {
                        options: {
                            top: 'top',
                            middle: 'middle',
                            bottom: 'bottom'
                        }
                    },
                    position: {
                        options: posList.reduce(function (map, pos) {
                        map[pos] = pos;
                        return map;
                        }, {})
                    },
                    distance: {
                        min: 0,
                        max: 100
                    }
                };

                app.config = {
                    rotate: 90,
                    align: 'left',
                    verticalAlign: 'middle',
                    position: 'insideBottom',
                    distance: 15,
                    onChange: function () {
                        const labelOption = {
                            rotate: app.config.rotate,
                            align: app.config.align,
                            verticalAlign: app.config.verticalAlign,
                            position: app.config.position,
                            distance: app.config.distance
                        };
                        myChart.setOption({
                            series: [
                                {
                                label: labelOption
                                },
                                {
                                label: labelOption
                                },
                                {
                                label: labelOption
                                },
                                {
                                label: labelOption
                                }
                            ]
                        });
                    }
                };

                const labelOption = {
                    show: true,
                    position: app.config.position,
                    distance: app.config.distance,
                    align: app.config.align,
                    verticalAlign: app.config.verticalAlign,
                    rotate: app.config.rotate,
                    formatter: '{c}  {name|{a}}',
                    fontSize: 16,
                    rich: {
                        name: {}
                    }
                };
                /* end main constant */

                var seriesTmp = [];
                if (type === 'bar_stacked') {
                    tmp['stack'] = 'total';
                }

                $.each(xAxisData, function (x, xk) {
                    var tmp = {
                        name: xk,
                        type: 'bar',
                        barGap: 0,
                        /* label: labelOption, */
                        emphasis: {
                            focus: 'series'
                        },
                        data: []
                    };
                    
                    if (type === 'bar_stacked' || type === 'stacked_bar'|| type === 'line_bar') {
                        tmp = {
                            name: xk,
                            type: 'bar',
                            barGap: 0,
                            stack: 'total',
                            /* label: labelOption, */
                            emphasis: {
                                focus: 'series'
                            },
                            data: []
                        };
                    };

                    if (type === 'line_stacked') {
                        tmp = {
                            name: xk,
                            type: 'line',
                            barGap: 0,
                            stack: 'total',
                            /* label: labelOption, */
                            emphasis: {
                                focus: 'series'
                            },
                            data: []
                        };
                    };

                    var dataTmp = [];
                    $.each(legendData, function (l, lk) {
                        dataTmp.push(0);
                        $.each(obj.data, function (index, row) {
                            if (row[axisXGroup] === xk && row['name'] === lk) {
                                dataTmp[l] = row['value'];
                            }
                        });

                    });

                    tmp.data = dataTmp;
                    seriesTmp.push(tmp);
                });

                
                if ((type === 'stacked_bar' || type === 'line_bar') && typeof chartConfig.axisXGroup !== 'undefined' && chartConfig.axisXGroup !== '') {
                    tmp['series'] = dataSet;
                    xAxisData = obj.dataXaxis;
                } else {
                    tmp['series'] = seriesTmp;
                    tmp['legend'] = {
                        data: legendData
                    };
                }
                if (typeof option.xAxis !== 'undefined') {
                    
                    tmp['xAxis'] = {
                        ...option.xAxis,
                        type: 'category',
                        axisTick: { show: false },
                        data: xAxisData
                    };

                } else {
                    
                    tmp['xAxis'] = {
                        type: 'category',
                        axisTick: { show: false },
                        data: xAxisData
                    };
                }

                tmp['yAxis'] = [{
                    type: 'value'
                }];

                tmp['toolbox'] = {
                    show: true,
                    orient: 'vertical',
                    left: 'right',
                    top: 'center',
                    feature: {
                        mark: { show: true },
                        dataView: { show: true, readOnly: false },
                        magicType: { show: true, type: ['line', 'bar', 'stack'] },
                        restore: { show: true },
                        saveAsImage: { show: true }
                    }
                };

                if (type !== 'bar_label_rotation') {
                    tmp['toolbox']['show'] = false;
                }
                
                option = {
                    ...option,
                    ...tmp,
                };

                break;
            case 'treemap_disk':
                type = 'treemap';
                break;
            case 'tree_circle' : 
                option = {
                    series: [{
                        type: 'tree',
                        data: dataSet,
                        top: '18%',
                        bottom: '14%',
                        layout: 'radial',
                        symbol: 'emptyCircle',
                        symbolSize: 7,
                        initialTreeDepth: 3,
                        animationDurationUpdate: 750,
                        emphasis: {
                            focus: 'descendant'
                        }
                    }]
                };
                break;
            case 'tree' : 
                
                var legendData = [];
                var _seriesData = [];
                var plusPercent = 100/(dataSet.length);
                var bottomPer = 100; var topPer = 0;
                
                $.each(dataSet, function (_i, _r) {
                    bottomPer -= plusPercent;
                    legendData.push({
                        name: plang.get('tree_structure') + ': ' + _i,
                        icon: 'rectangle'
                    });
    
                    _seriesData.push({
                        type: 'tree',
                        name: 'tree-' + _i ,
                        data: [_r],
                        top: topPer + '%',
                        left: '10%',
                        bottom: bottomPer + '%',
                        right: '15%',
                        symbolSize: 7,
                        label: {
                            position: 'left',
                            verticalAlign: 'middle',
                            align: 'right'
                        },
                        leaves: {
                            label: {
                                position: 'right',
                                verticalAlign: 'middle',
                                align: 'left'
                            }
                        },
                        expandAndCollapse: true,
                        emphasis: {
                            focus: 'descendant'
                        },
                        animationDuration: 550,
                        animationDurationUpdate: 750,
                        triggerEvent: true
                    });
                    topPer += plusPercent;
                });

                option = {
                    legend: {
                        top: '2%',
                        left: '3%',
                        orient: 'vertical',
                        data: legendData,
                        borderColor: '#c23531'
                    },
                    series: _seriesData
                };
                
                break;
            default:
                if (typeof chartConfig.smooth !== 'undefined' && chartConfig.smooth) {
                    series = {
                        ...series,
                        smooth: true
                    };
                }
                xAxis = {
                    ...xAxis,
                    data: obj.dataXaxis,
                }
                
                if (type === 'line_race') {
                    /* series.name= name; */
                    if (option.dataZoom !== 'undefined') {
                        var tmpZoom = [
                            {
                                startValue: '2014-06-01'
                            },
                            {
                                type: 'inside'
                            }];

                        option.dataZoom  = {
                            ...option.dataZoom,
                            ...tmpZoom
                        }
                    } else {
                        option.dataZoom= [{
                            startValue: '2014-06-01'
                        },
                        {
                            type: 'inside'
                        }];
                    }

                    option.animationDuration = 10000;
                    if (typeof chartConfig.axisXGroup !== 'undefined' && chartConfig.axisXGroup !== '') {
                        series = dataSet;
                    } else {
                        series.type = 'line';
                        series.showSymbol= false;
                        series.endLabel= {
                            show: true,
                            formatter: function (params) {
                                return params.value;
                            }
                        };
                        series.labelLayout= {
                            moveOverlap: 'shiftY'
                        };
                        series.emphasis= {
                            focus: 'series'
                        };
                    }
                }

                switch (type) {
                    case 'treemap':
                        series = {
                            ...series,
                            ...option.series,
                        };

                        option = {
                            ...option,
                            series: series,
                        };
                        break;
                    case 'line_race':
                        /* option.grid= {
                            top: '12%',
                            left: '1%',
                            right: '10%',
                            containLabel: true
                        }; */
                        /* xAxis.axisLabel= {
                            rotate: 60,
                            overflow: "truncate",
                            width: 80,
                        }; */

                        if (typeof xAxis.axisLine !== 'undefined') {
                            /* xAxis.axisLine = {
                                symbolSize: [10, 15],
                                ...xAxis.axisLine
                            } */
                        } else {
                            xAxis.axisLine= {
                                symbolSize: [10, 15],
                            };
                        }

                    default:
                        option = {...option,
                            yAxis: yAxis,
                            xAxis: xAxis,
                            series: series,
                        };
                        break;
                }

                break;
        }
    
        if (chartConfig.bgColor) {
            option = {
                ...option,
                backgroundColor: chartConfig.bgColor,
            };
        }
        var tmpOption = option;
        switch (useData) {
            case '3':
            case '2':
                option = JSON.parse(chartConfig.buildCharConfig);
                option = clearOptions(option);
                if (useData == '3') {
                    if (type === 'stacked_bar' && typeof chartConfig.axisXGroup !== 'undefined' && chartConfig.axisXGroup !== '') {
                        option.series = data;
                        option.xAxis.data = obj.dataXaxis;
                        /* console.clear();
                        console.log('here --------------------------------- ');
                        console.log(obj.dataXaxis);
                        console.log(option); */
                    } else {
                        option.series.data = data;
                        if (typeof tmpOption.xAxis !== 'undefined' && typeof tmpOption.xAxis.data !== 'undefined')
                            option.xAxis.data = tmpOption.xAxis.data;
                    }
                }
                break;
                
            default:
                var itemStyle = {
                    textStyle: {
                        fontFamily: "Arial, Helvetica, sans-serif",
                        color : "#333",
                        fontSize: "0.9375rem"
                    }
                }
                option = {
                    ...option,
                    ...itemStyle,
                };

                break;
        }

        console.log(option);
        option && myChart.setOption(option);
        var jsonMinif = JSON.stringify(option);
        
        $mainSelector.find('.kpi-dm-chart-create').attr('data-config', jsonMinif);
        if (type === 'tree' || type === 'tree_circle') {
            var subContent = $('#' + elemId).parent().find('.subcontent');
            subContent.hide();
            myChart.on('click', params => {
                var _descriptionContent = '';
                _descriptionContent += '<table class="table table-bordered" style="background: #FFF;">';
                    _descriptionContent += '<tbody>';
                    _descriptionContent += '<tr><td style="background-color: #f5f5f5; width: 180px" colspan="2"><button type="button" class="btn btn-light bg-gray bg-grey-c0 border-0 p-1 pl-2 pr-2 text-white subcontent-collapse-btn"><i class="far fa-arrow-alt-to-right"></i></button></td></tr>';
                    $.each(params.data, function (n, o) {
                        var nlower = n.toLowerCase();
                        if (/* typeof o !== 'object' &&  */nlower.indexOf('id') === -1 && nlower !== 'name' && nlower !== 'children' && nlower !== 'value') {
                            _descriptionContent += '<tr data-cell-path="'+ n +'">';
                                _descriptionContent += '<td style="background-color: #f5f5f5; width: 180px">';
                                    _descriptionContent += '<label required="">'+ n + ' <span class="label-colon">:</span></label>';
                                _descriptionContent += '</td>';
                                _descriptionContent += '<td>'+ ((typeof o !== 'object') ? o : '') + '</td>';
                            _descriptionContent += '</tr>';
                        }
                    });
                    
                    _descriptionContent += '</tbody>';
                _descriptionContent += '</table>';
    
                subContent.empty().append(_descriptionContent).promise().done(function () {
                    subContent.show();
                });
            });
        }

        $mainSelector.find('textarea[name="addintionalConfig"]').val(JSON.stringify(option));

        if (typeof obj.isLayoutBuilder !== 'undefined' && obj.isLayoutBuilder) {
            obj.chartConfig = option;
            $('#' + elemId).attr('data-config', JSON.stringify(obj));
        }
        window.addEventListener("resize", myChart.resize);
    }

    var chartBuilderStatic = function (obj) {
         
        var elemId = obj.elemId;
        console.log(elemId);
        if (!elemId) return false;

        var $mainSelector = $('#' + elemId).closest('.theme-builder');
        var chartConfig = obj;
        var useData = obj.useData;
        var type = chartConfig.type;
        var chartName = (chartConfig.hasOwnProperty('chartName')) ? chartConfig.chartName : type + '_chart';
        var category = chartConfig.axisX;
        var value = chartConfig.axisY;
        var aggregate = chartConfig.aggregate;
        var columnsConfig = obj.columnsConfig;
        var data = obj.data;
        var isRunInterval = false;
        var isLineChartConfig = false;
        var kpiLayoutIndex = 0;
        obj.isLayoutBuilder = false;
    
        if (chartConfig.hasOwnProperty('lineChartConfig') && chartConfig.lineChartConfig) {
            isLineChartConfig = true;
        }
                
        if (obj.hasOwnProperty('isRunInterval') && obj.isRunInterval) {
    
            isRunInterval = true;
    
            if (kpIndicatorChart.hasOwnProperty(elemId)) {
                
                var prevChart = kpIndicatorChart[elemId];
                
                if (type == 'stacked_column') {
                    var stackedData = kpiDataMartStackedColumnData(data, category, value, chartConfig);
                    data = stackedData.data;
                }
                
                prevChart.data = data;
                return;
            }
        }
    
        var themeGroup;
    
        if ($mainSelector.find('.theme-plan-group.selected').attr('data-rowdata')) {
            themeGroup = JSON.parse($mainSelector.find('.theme-plan-group.selected').attr('data-rowdata'));
        }
        
        try {
            var chartDom = document.getElementById(elemId);
            var option;
            
        } catch (error) {
            console.log(error);
            Core.unblockUI();
        }
        
        echarts.dispose(chartDom);
        var myChart = echarts.init(chartDom);
        var dataSet = data;
        var grid = {containLabel: true};
        option = {
            themeCode: chartConfig.themeCode,
        };
        option = convertOptions(chartConfig, option);
    
        var axisLabel = {},
            yAxis = {
                type: 'value',
            },
            xAxis = {
                type: 'category',
                label: axisLabel,
            };
        
        var seriesLabel = {position: 'middle', formatter: '{c}'};
    
        if (typeof chartConfig.axisLabelShow !== 'undefined') {
            axisLabel = {...axisLabel,
                show: chartConfig.axisLabelShow
            };
    
            seriesLabel = {...seriesLabel,
                show: chartConfig.axisLabelShow
            };
        }
        
        if (axisLabel.length > 0) {
            xAxis = {
                ...xAxis,
                label: axisLabel,
            }
        }
        
        if (typeof chartConfig.xyReverse !== 'undefined' && chartConfig.xyReverse) {
            var xAxisTmp = xAxis;
            xAxis = yAxis;
            yAxis = xAxisTmp;
        }
    
        switch (type) {
            case 'pie': 
                break;
            default:
                
                option.series.data =  [150, 230, 224, 218, 135, 147, 260];
                option = {
                    xAxis: {
                        type: 'category',
                        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                    },
                    yAxis: {
                      type: 'value'
                    },
                    ...option
                }
                break;
        }
        if (typeof option.series !== 'undefined') {
            var series = {
                type: type,
                ...option.series
            };
            delete option.series;
            option = {
                ...option,
                series,
            };
        }
        
        if (chartConfig.bgColor) {
            option = {
                ...option,
                backgroundColor: chartConfig.bgColor,
            };
        }

        console.log(option);
        option && myChart.setOption(option);

        if (typeof $('#' + elemId).attr('data-block-uniqid') !== 'undefined') {
            
            var $parentSelector = $('#' + elemId).closest('div.layout-builder-v0');
            blockUniqId = $('#' + elemId).attr('data-block-uniqid'),
            configrationSelector = $parentSelector.find('div.item-config[data-item-cf="'+ blockUniqId +'"]');
            configrationSelector.find('textarea[data-path="addintionalConfig"]').val(JSON.stringify(option));
            
            console.log(configrationSelector.find('textarea[data-path="secNemgoo"]').val());

            var secNemgoo = configrationSelector.find('textarea[data-path="secNemgoo"]').val() ? JSON.parse(configrationSelector.find('textarea[data-path="secNemgoo"]').val()) : [];
            secNemgoo = {...secNemgoo, ...option};
            configrationSelector.find('textarea[data-path="secNemgoo"]').val(JSON.stringify(secNemgoo));
            
        }

        if (typeof obj.isLayoutBuilder !== 'undefined' && obj.isLayoutBuilder) {
            obj.chartConfig = option;
            $('#' + elemId).attr('data-config', JSON.stringify(obj));
        }
        window.addEventListener("resize", myChart.resize);
        setTimeout(() => {
            window.addEventListener("resize", myChart.resize); 
        }, '2000');
    }
    
    var chartConfigrationToggle = function ($mainSelector, _dataConfigStr) {
        $mainSelector.find('.chartTypesConfigration, .chartTypesConfigration .configration').hide();
        var _dataConfig = _dataConfigStr.split('##');
        $.each(_dataConfig, function (key, conf) {
            var __config = conf.replace('.', '_');
            $mainSelector.find('.configration.' + __config).show();
            $mainSelector.find('.chartTypesConfigration.conf_' + __config).show();
        });
    };
     
    var kpiDataMartEChartSetOption = function (obj) {
        var elemId = obj.elemId;
        var $mainSelector = $('#' + elemId).closest('.theme-builder');
        var chartConfig = obj.chartConfig;
        var useData = obj.useData;
        var type = chartConfig.type;
        var chartName = (chartConfig.hasOwnProperty('chartName')) ? chartConfig.chartName : type + '_chart';
        var category = chartConfig.axisX;
        var value = chartConfig.axisY;
        var aggregate = chartConfig.aggregate;
        var columnsConfig = obj.columnsConfig;
        var data = obj.data;
        var isRunInterval = false;
        var isLineChartConfig = false;
        /* var axisYdataType = columnsConfig[value]; */
        
        var isLayoutBuilder = false, kpiLayoutIndex = 0;
        if ($mainSelector.attr('data-kpi-layout') !== '0') {
            isLayoutBuilder = true;
            var _parentSelector = $('#' + elemId).closest('.layout-builder-v0');
            kpiLayoutIndex = $mainSelector.attr('data-kpi-layout');
            elemId = _parentSelector.find('div.selected-box[data-kl-col="'+ kpiLayoutIndex +'"] [data-section-code="'+ kpiLayoutIndex +'"]').attr('id');
        }
    
        if (aggregate == 'COUNT' && (value == '' || value === null)) {
            value = 'COUNT_COL';
        }
                
        if (chartConfig.hasOwnProperty('lineChartConfig') && chartConfig.lineChartConfig) {
            isLineChartConfig = true;
        }
                
        if (obj.hasOwnProperty('isRunInterval') && obj.isRunInterval) {
    
            isRunInterval = true;
    
            if (kpIndicatorChart.hasOwnProperty(elemId)) {
                
                var prevChart = kpIndicatorChart[elemId];
                
                if (type == 'stacked_column') {
                    var stackedData = kpiDataMartStackedColumnData(data, category, value, chartConfig);
                    data = stackedData.data;
                }
                
                prevChart.data = data;
                return;
            }
        }
    
        var themeGroup;
    
        if ($mainSelector.find('.theme-plan-group.selected').attr('data-rowdata')) {
            themeGroup = JSON.parse($mainSelector.find('.theme-plan-group.selected').attr('data-rowdata'));
        }
    
        if (chartConfig.chartType === 'card_vertical' || chartConfig.chartType === 'card') {
            kpiDataMartChartRender(obj)
            return false;
        }
    
        try {
            var chartDom = document.getElementById(elemId);
            var option;
            echarts.dispose(chartDom);
            
            var myChart = echarts.init(chartDom);
    
            option = JSON.parse(chartConfig.buildCharConfig);
            option && myChart.setOption(option);
            
            var jsonMinif = JSON.stringify(option);
            $mainSelector.find('.kpi-dm-chart-create').attr('data-config', jsonMinif);
    
            if (typeof obj.isLayoutBuilder !== 'undefined' && obj.isLayoutBuilder) {
                /* if (typeof chartConfig.grid.height !== 'undefined') {
                    myChart.resize({
                        width: chartConfig.grid.width,
                        height: chartConfig.grid.height
                    });
                } */
            }
            
            setInterval(() => {
                myChart.resize();
            }, 2000);
            /* window.addEventListener("resize", myChart.resize); */
        } catch (error) {
            console.log(error);
        }
    
    };
     
    return {
        init: function(uniqId) {
            /* init('#layout-builder'+ uniqId); */
        },
        chartRender: function (obj) {
            chartRender(obj);
        },
        chartBuilderStatic: function (obj) {
            chartBuilderStatic(obj);
        },
        chartConfigrationToggle: function ($mainSelector, _dataConfigStr) {
            chartConfigrationToggle($mainSelector, _dataConfigStr);
        }
    };

} ();