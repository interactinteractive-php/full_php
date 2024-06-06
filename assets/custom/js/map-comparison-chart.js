/* global Ereport, Highcharts, compareSumDuuregCode, layoutLocation, comparisonByLocation, sessionAimagKhotList, locale, sessionSumDuuregList */

var MapComparisonChart=function(){
  Highcharts.setOptions({
    chart: {
      style: {
        font: '400 14px "Pt Sans Narrow", Arial, sans-serif'
      }
    }
  });

  var sumDuuregMap=$("#sumDuuregLayoutMap");
  var aimagKhotMap=$("#aimagKhotLayoutMap");
  var aimagLicenseArray={};
  var sumDuuregValArray={};
  var sumDuuregNameArray={};
  var sumDuuregIdArray={},
          chart,
          tmpData,
          tmpSumId,
          prevMaxColorAxis=50,
          maxColorAxis=50,
          aimagName='',
          tmpWidth,
          tmpHeight;

  var callAimagLicense=function(){
    if(locale === 'mn'){
      aimagName='NAME';
      propertiesName='{point.NAME}';
    } else if(locale === 'en'){
      aimagName='NAME_EN';
      propertiesName='{point.NAME_EN}';
    }

    $.ajax({
      url: "/getAllAimagLicense/",
      type: "POST",
      dataType: "json",
      success: function(data){
        if(typeof data[0] !== "undefined"){
          maxColorAxis=data[0].total;
          prevMaxColorAxis=maxColorAxis;
        }
        $.each(data, function(key, value){
          aimagLicenseArray[value['aimag_khot_id']]=value['total'];
        });
        var isLoaded=false;
        $("#tab_sum_duureg").click(function(){
          if(!isLoaded){
            isLoaded=true;
            sumDuuregMap.highcharts().setSize(tmpWidth - 10, tmpHeight - 10);
          }
        });
        HandleMapSumDuureg();
        HandleMapAimagKhot();
      },
      error: function(jqXHR, exception){
        Ereport.showErrorMessage(jqXHR);
      }
    });
  };

  var HandleMapSumDuureg=function(){
    response={"childVal": aimagLicenseArray, "childId": {"MN-SL": 43, "MN-ER": 61, "MN-GA": 82,
        "MN-OG": 46,
        "MN-DG": 48, "MN-DD": 21, "MN-UB": 11, "MN-SB": 22, "MN-AR": 65, "MN-OH": 62, "MN-BO": 83,
        "MN-HG": 67, "MN-DZ": 81, "MN-DU": 48, "MN-GS": 42, "MN-BH": 64, "MN-BU": 63, "MN-TO": 41,
        "MN-UV": 85, "MN-DA": 45, "MN-HD": 84, "MN-HN": 23}};

    var data=Highcharts.maps['aimag/mn-all'];

    respon=response;

    small=sumDuuregMap.width() < 400;

    $.each(data, function(){
      tmpData=this;
      if(typeof tmpData['CODE'] !== "undefined"){
        tmpData.drilldown=tmpData['CODE'];
        tmpData.code=tmpData['CODE'];
        tmpData.id=response['childId'][tmpData['CODE']];
      }
      tmpData.name=tmpData[aimagName];
      tmpData.value=response['childVal'][tmpData.id];
    });

    sumDuuregMap.highcharts('Map', {
      credits: {
        enabled: false
      },
      lang: {
        drillUpText: Ereport.translateMsg('Буцах')
      },
      chart: {
        backgroundColor: 'transparent',
        "marginTop": 0,
        "marginBottom": 90,
        "marginLeft": 0,
        "marginRight": 0,
        events: {
          drilldown: function(e){
            if(!e.seriesOptions){
              aimagId=e.point.id;
              sumId=null;
              tmpSumId=null;
              chart=this;
              mapKey='/assets/ereport/js/chart/aimag/subregion_' + e.point.drilldown;
              // Handle error, the timeout is cleared on success
              fail=setTimeout(function(){
                if(!Highcharts.maps[mapKey]){
                  chart.showLoading('<i class="icon-frown"></i> Ачааллаж байна...');

                  fail=setTimeout(function(){
                    chart.hideLoading();
                  }, 1000);
                }
              }, 3000);


              $('#location').html(e.point.name);
              // Show the spinner
              chart.showLoading(
                      '<i class="icon-spinner icon-spin icon-3x"></i>'); // Font Awesome spinner

              // Load the drilldown map
              $.getScript(mapKey + '.js', function(){
//                                            alert('it is working!');
                data=Highcharts.geojson(Highcharts.maps[mapKey]);
                // Set a non-random bogus value
                $.ajax({
                  url: "/getAllSumDuuregLicense/",
                  type: "POST",
                  dataType: "json",
                  data: {aimagId: aimagId},
                  success: function(result){

                    if(typeof result[0] !== "undefined" && typeof chart.axes[2] !== "undefined"){
                      maxColorAxis=result[0].total;
                      chart.axes[2].setExtremes(0, maxColorAxis);
                    }

                    $.each(result, function(key, value){
                      sumDuuregNameArray[value['CHART_CODE']]=value['NAME'];
                      sumDuuregValArray[value['CHART_CODE']]=value['total'];
                      sumDuuregIdArray[value['CHART_CODE']]=value['sum_duureg_id'];
                    });

                    var sumDuuregResponse=[];
                    response={"childVal": sumDuuregValArray,
                      "childId": sumDuuregIdArray};

                    $('.aimag-title').empty().text(e.point.name);
                    sumDuuregResponse=response['childVal'];
                    $.each(data, function(i){
                      tmpData=this;
                      tmpData.name=sumDuuregNameArray[data[i].properties.CODE];
                      tmpData.code=data[i].properties.CODE;
                      tmpData.id=response['childId'][data[i].properties.CODE];
                      tmpData.value=sumDuuregResponse[data[i].properties.CODE];
                      $.each(comparisonByLocation.getSelectedSumDuureg(), function(key, value){
                        if(tmpData.id == value){
                          tmpData.color="#F3B48D";
                        }
                      });
                    });

                    chart.hideLoading();
                    clearTimeout(fail);
                    sumData=data;
                    chart.addSeriesAsDrilldown(e.point, {
                      name: Ereport.translateMsg('Тусгай зөвшөөрлийн тоо'),
                      data: data,
                      dataLabels: {
                        enabled: true,
                        format: propertiesName
                      },
                      cursor: 'pointer',
                      events: {
                        click: function(e){
                          sumId=e.point.code;
                          tmpSumId=e.point.id;
                          layoutLocation.addToSoumDuuregBasket(sumId, tmpSumId);
                          comparisonByLocation.getSumDuuregData(tmpSumId);
                          chart.setTitle(null, {text: e.point.name});
                          e.point.update({color: '#F3B48D'});
                        }
                      }
                    });

                    data=null;
                  },
                  error: function(jqXHR, exception){
                    Ereport.showErrorMessage(jqXHR);
                  }
                });
              });
            }
            chart.setTitle(null, {text: e.point.name});
          },
          drillup: function(){
            chart.setTitle(null, {text: Ereport.translateMsg('Монгол улс')});
            aimagId=null;
            sumId=null;
            tmpSumId=null;

            if(typeof chart.axes[2] !== "undefined"){
              maxColorAxis=prevMaxColorAxis;
              chart.axes[2].setExtremes(0, maxColorAxis);
            }

            chart="undefined";
          }
        }
      },
      title: {
        text: ''
      },
      subtitle: {
        text: Ereport.translateMsg('Монгол улс'),
        floating: true,
        align: 'right',
        y: 50,
        style: {
          fontSize: '16px',
          color: (Highcharts.theme && Highcharts.theme.textColor) || '#333'
        }
      },
      legend: small ? {} : {
        title: {
          text: Ereport.translateMsg('Тусгай зөвшөөрлийн тоо'),
          style: {
            color: (Highcharts.theme && Highcharts.theme.textColor) || '#333'
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
          data: data,
          name: Ereport.translateMsg('Тусгай зөвшөөрлийн тоо'),
          dataLabels: {
            enabled: true,
            format: propertiesName
          },
          borderWidth: 2,
          borderColor: 'white'
        }],
      drilldown: {
        //series: drilldownSeries,
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

  var HandleMapAimagKhot=function(){
    response={"childVal": aimagLicenseArray, "childId": {"MN-SL": 43, "MN-ER": 61, "MN-GA": 82,
        "MN-OG": 46,
        "MN-DG": 44, "MN-DD": 21, "MN-UB": 11, "MN-SB": 22, "MN-AR": 65, "MN-OH": 62, "MN-BO": 83,
        "MN-HG": 67, "MN-DZ": 81, "MN-DU": 48, "MN-GS": 42, "MN-BH": 64, "MN-BU": 63, "MN-TO": 41,
        "MN-UV": 85, "MN-DA": 45, "MN-HD": 84, "MN-HN": 23}};

    var data=Highcharts.maps['aimag/mn-all'];

    respon=response;

    small=sumDuuregMap.width() < 400;

    $.each(data, function(){
      tmpData=this;
      if(typeof tmpData['CODE'] !== "undefined"){
        tmpData.drilldown=tmpData['CODE'];
        tmpData.code=tmpData['CODE'];
        tmpData.id=response['childId'][tmpData['CODE']];
      }
      tmpData.name=tmpData[aimagName];
      tmpData.value=response['childVal'][tmpData.id];
      $.each(sessionAimagKhotList, function(key, value){
        if(tmpData.id == value['ID']){
          tmpData.color="#F3B48D";
        }
      });
    });


    aimagData=data;
    // Instanciate the map
    mapChart=aimagKhotMap.highcharts('Map', {
      credits: {
        enabled: false
      },
      lang: {
        drillUpText: Ereport.translateMsg('Буцах')
      },
      chart: {
        backgroundColor: 'transparent',
        events: {
          drilldown: function(e){
            var aimagKhotId=e.point.id;
            layoutLocation.addToAimagKhotBasket(aimagKhotId);
            comparisonByLocation.getAimagKhotData(aimagKhotId);
            e.point.update({color: '#F3B48D'});
          }
        }
      },
      title: {
        text: ''
      },
      subtitle: {
        text: Ereport.translateMsg('Монгол улс'),
        floating: true,
        align: 'right',
        y: 50,
        style: {
          fontSize: '16px',
          color: (Highcharts.theme && Highcharts.theme.textColor) || '#333'
        }
      },
      legend: small ? {} : {
        title: {
          text: Ereport.translateMsg('Тусгай зөвшөөрлийн тоо'),
          style: {
            color: (Highcharts.theme && Highcharts.theme.textColor) || '#333'
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
          data: data,
          name: Ereport.translateMsg('Тусгай зөвшөөрлийн тоо'),
          dataLabels: {
            enabled: true,
            format: propertiesName
          },
          borderWidth: 2,
          borderColor: 'white'
        }],
      drilldown: {
        //series: drilldownSeries,
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
    }).highcharts();

    tmpWidth=aimagKhotMap.outerWidth();
    tmpHeight=aimagKhotMap.outerHeight();
  };

  return {
    init: function(){
      callAimagLicense();
    },
    getChart: function(){
      return chart;
    }
  };
}();