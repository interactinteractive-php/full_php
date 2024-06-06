/* global Highcharts, cardChart, Ereport, groupedBarChart, fieChart, dynamicDashboard, portalMain, companies, plang.get('Тусгай зөвшөөрөлгүй газар нутаг'), plang.get('Ашигт малтмалын'), plang.get('Газрын тосны'), plang.get('Монгол улс'), locale, plang.get('Компанийн жагсаалт'), plang.get('Тусгай зөвшөөрлийн тоо') */

var MapInvitationChart = function () {
    Highcharts.setOptions({
        chart: {
            style: {
                font: '400 14px "Pt Sans Narrow", Arial, sans-serif'
            }
        }
    });

    var mapEl = $("#layoutMap");
    var aimagLicenseArray = {};
    var sumDuuregValArray = {};
    var sumDuuregNameArray = {};
    var sumDuuregIdArray = {},
            chart,
            tmpData,
            $panelCompaniesList = $("#panelCompaniesList"),
            $companyListSearchForm = $("#companyListSearchForm"),
            $layoutSections = $("#layoutLocation"),
            tmpSumId,
            searchBtn = $(".search-company-bn"),
            prevMaxColorAxis = 50,
            maxColorAxis = 50,
            tmpAimagName = '',
            currentLocationName = $("#currentLocationName"),
            currentLocationTxt = '';
    
    var initEvent = function ($mainData, uniqId, dataId) {
        HandleMap($mainData, uniqId, dataId);
    };

    var HandleMap = function ($mainData, uniqId, dataId) {
        var aimagLicenseArray = {};
        
        $.each($mainData, function(key, value){
            aimagLicenseArray[value['cityid']]= value['value'];
            aimagLicenseArray[value['cityid'] + '_']= value['value2'];
            aimagLicenseArray[value['cityid'] + '__']= value['value3'];
            aimagLicenseArray[value['cityid'] + '___']= value['value3'];
            if (maxColorAxis < value['value']) {
                maxColorAxis = value['value'];
            } 
        });

        var response = {
                    "childVal": aimagLicenseArray, "childId": 
                    {
                        "MN-SL": 43, 
                        "MN-ER": 61, 
                        "MN-GA": 82,
                        "MN-OG": 46,
                        "MN-DG": 44, 
                        "MN-DD": 21, 
                        "MN-UB": 11, 
                        "MN-SB": 22, 
                        "MN-AR": 65, 
                        "MN-OH": 62, 
                        "MN-BO": 83,
                        "MN-HG": 67, 
                        "MN-DZ": 81, 
                        "MN-DU": 48, 
                        "MN-GS": 42, 
                        "MN-BH": 64, 
                        "MN-BU": 63, 
                        "MN-TO": 41,
                        "MN-UV": 85, 
                        "MN-DA": 45, 
                        "MN-HD": 84, 
                        "MN-HN": 23
                    }
                };
                
        small = mapEl.width() < 400;
        
        var data = Highcharts.maps['aimag/mn-all'];
        $.each(data, function (index, row) {
            if (typeof $mainData[row['CODE']] !== 'undefined') {
                row['cityid'] = $mainData[row['CODE']]['cityid'];
                row['isdefaultdrill'] = $mainData[row['CODE']]['isdefaultdrill'];
                row['animalcount'] = $mainData[row['CODE']]['animalcount'];
                row['dataviewid'] = $mainData[row['CODE']]['dataviewid'];
                row['color'] = $mainData[row['CODE']]['color'];
            }
        });

        $.each(data, function () {
            tmpData = this;
            
            if (typeof tmpData['CODE'] !== "undefined") {
                tmpData.drilldown = tmpData['CODE'];
                tmpData.code = tmpData['CODE'];
                tmpData.id = response['childId'][tmpData['CODE']];
            }
            
            tmpData.name = tmpData['NAME'];
            tmpData.value = response['childVal'][tmpData.id];
            tmpData.value2 = response['childVal'][tmpData.id + '__'];
            tmpData.value3 = response['childVal'][tmpData.id + '__'];
            tmpData.value4 = response['childVal'][tmpData.id + '__'];
            
        });
        
        $("#layoutMap").highcharts('Map', {
            credits: {
                enabled: false
            },
            lang: {
                drillUpText: '◁ ' + plang.get('back_btn')
            },
            chart: {
                backgroundColor: 'transparent',
                "marginTop": 0,
                "marginBottom": 90,
                "marginLeft": 0,
                "marginRight": 0,
                events: {
                    drilldown: function (e) {
                        if (!e.seriesOptions) { //&& e.point.id == '62'
                            
                            refreshOtherCvDashboard(e.point.id, '');
                            $('#compareLocation').removeClass('hidden');
                            aimagId = e.point.id;
                            $('#compareLocation').attr('aimagKhotId', aimagId);
                            sumId = null;
                            tmpSumId = null;
                            chart = this;
                            mapKey = '/assets/custom/js/aimag/subregion_' + e.point.drilldown;
                            
                            fail = setTimeout(function () {
                                if (!Highcharts.maps[mapKey]) {
                                    chart.showLoading('<i class="icon-frown"></i> Ачааллаж байна...');

                                    fail = setTimeout(function () {
                                        chart.hideLoading();
                                    }, 1000);
                                }
                            }, 3000);
                            
                            $('#location').html(e.point.name);
                            chart.showLoading('<i class="icon-spinner icon-spin icon-3x"></i>');

                            $.getScript(mapKey + '.js', function (ji, jstatus) {
                                if (jstatus === 'success' && typeof Highcharts.maps[mapKey] !== 'undefined') {
                                    data = Highcharts.geojson(Highcharts.maps[mapKey]);
                                    chart.hideLoading();
                                    
                                    $.ajax({
                                        url: "mdasset/getAllSumDuuregLicenseCv/",
                                        type: "POST",
                                        dataType: "json",
                                        data: {aimagId: aimagId},
                                        success: function (result) {
                                            
                                            $.each(data, function (index, row) {
                                                if (typeof result[row['properties']['ID']] !== "undefined" ) {
                                                    row['color'] = result[row['properties']['ID']]['color'];
                                                } else {
                                                    row['color'] = '#7cb5ec';
                                                }
                                            });
                                            
                                            var maxColorAxis = 0;
                                            $.each(result, function (key, value) {
                                                sumDuuregNameArray[value['parentcode']] = value['name'];
                                                sumDuuregValArray[value['parentcode']] = value['value'];
                                                sumDuuregIdArray[value['parentcode']] = value['parentcode'];
                                                if (maxColorAxis < value['value']) {
                                                    maxColorAxis = value['value'];
                                                }
                                            });
                                            
                                            if (typeof result[0] !== "undefined" && typeof chart.axes[2] !== "undefined") {
                                                chart.axes[2].setExtremes(0, maxColorAxis);
                                            }
                                            
                                            var sumDuuregResponse = [];
                                            response = {"childVal": sumDuuregValArray, "childId": sumDuuregIdArray};

                                            sumDuuregResponse = response['childVal'];
                                            clearTimeout(fail);

                                            $.each(data, function (i) {
                                                tmpData = this;
                                                tmpData.name = sumDuuregNameArray[data[i].properties.ID];
                                                tmpData.code = data[i].properties.CODE;
                                                tmpData.id = data[i].properties.ID;
                                                tmpData.value = sumDuuregResponse[data[i].properties.ID];
                                            });

                                            chart.addSeriesAsDrilldown(e.point, {
                                                name: plang.get('Төрөлт'),
                                                data: data,
                                                dataLabels: {
                                                    enabled: true,
                                                    format: '{point.properties.NAME}'
                                                },/*
                                                tooltip: {
                                                    pointFormat: "{point.properties.NAME}",
                                                },*/
                                                cursor: 'pointer',
                                                events: {
                                                    click: function (e) {
                                                        refreshOtherCvDashboard(e.point.properties.AIMAG_KHOT, e.point.id);
                                                    }
                                                }
                                            });

                                            data = null;

                                        },
                                        error: function (jqXHR, exception) {
                                            Core.showErrorMessage(jqXHR);
                                            Core.unblockUI();
                                        }
                                    });
                                }
                            });
                        }
                        
                        tmpAimagName = e.point.name;
                        currentLocationTxt = tmpAimagName;
                        currentLocationName.text(currentLocationTxt);
                    },
                    drillup: function () {
                        currentLocationTxt = plang.get('CV_DELKHII_DAHIND');
                        currentLocationName.text(currentLocationTxt);
                        refreshOtherCvDashboard('', '');
                    },
                    load: function(e) {
                        var drillIndex;
                        $.each(this.series[0].data, function (ii, rr) {
                            if (rr['isdefaultdrill'] === '1') {
                                drillIndex = ii;
                            }
                        });
                        if (drillIndex) {
                            //this.series[0].data[drillIndex].doDrilldown();
                        }
                    }
                }
            },
            title: {
                text: ''
            },
            subtitle: {
                floating: true,
                align: 'right',
                y: 50,
                style: {
                    fontSize: '16px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'white'
                }
            },
            legend: {
                enabled: true,
                title: {
                    text: (dataId == '1599478648184' || dataId == '') ? plang.get('Сүүлийн 2 сард томуу, амьсгалын замын өвчний амбулаторийн үзлэг') : 'Амьсгалын аппарат',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.textColor) || '#FFF'
                    }
                },
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom'
            },
            colorAxis: {
                min: 1,
                lineColor: '#1a7cde',
                minColor: '#74c8fb',
                maxColor: '#0091ea',
                max: maxColorAxis
            },
            mapNavigation: {
                enabled: false,
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },
            plotOptions: {
                map: {
                    states: {
                        hover: {
                            color: '#4ee1e3'
                        }
                    }
                }
            },
            series: [{
                type: 'map',
                data: data,
                name: (dataId == '1599478648184' || dataId == '') ? plang.get('Томуу') : 'Амьсгалын аппарат',
                dataLabels: {
                    enabled: true,
                    format: '{point.NAME}'
                },/*
                tooltip: {
                    pointFormat: "{point.VALUE}",
                },*/
                borderWidth: 2,
                borderColor: 'white'
            }],
            drilldown: {
                activeDataLabelStyle: {
                    color: '#FFFFFF',
                    textDecoration: 'none',
                    textShadow: '0 0 3px #000000'
                },
                drillUpButton: {
                    relativeTo: 'spacingBox',
                    position: {
                        x: 0,
                        y: 60
                    }
                }
            }
        });
        
    };

    return {
        init: function ($mainData, uniqId, dataId) {
            initEvent($mainData, uniqId, dataId);
        }
    };
}();
