<?php 
    $mapLayer = array(); 
    $mapLayerSelectedData = ''; 

    if (issetParam($this->mapLayers['0']['ID'])) {
        $mapLayer = Arr::groupByArrayOnlyRow($this->mapLayers, 'ID', false);
        $mapLayerSelectedData = Arr::implode_key(',', $this->mapLayers, 'ID', true);
    }
?>
<div class="center-sidebar overflow-hidden content">
    <div class="row">
        <div class="col right-sidebar-content-for-resize content-wrapper pl-0 pr-0 overflow-hidden">
            <div class="row md-map-container">
                <div class="col-md-12">
                    <div class="text-uppercase font-weight-bold mt-0 mb-2">
                        <?php echo $this->title; ?>
                    </div>
                    
                </div>
                <div class="md-map-filter-panel md-map-filter-panel-<?php echo $this->indicatorId; ?> md-map-filter-panel-indicator left" style="margin-top: 68px; display: none">
                    <div class="md-map-selector-toggle">
                        <i class="fa fa-angle-double-right"></i>
                    </div>
                    <div class="md-map-filter-container">
                        <div class="col-md-auto px-0 pt-1">
                            <div class="kpidv-data-filter-col pr-1 border-none"></div>
                        </div>
                        <div class="list-group border-none" style="width: 240px;">
                            <div class="px-0 py-1 mt10 list-group-item font-weight-bold font-size-13 line-height-normal justify-content-between kpi-indicator-filter-collapse-btn opened">Төрөл <i class="icon-arrow-right13 ml-1"></i></div>
                            <div class="list-group-body col-md-auto px-0 ">
                                <?php 
                                echo Form::multiselect(
                                    array(
                                        'class' => 'form-control form-control-sm select2 data-indicator-id pr-1 data-indicator-id' . $this->indicatorId,
                                        'name' => 'mayLayers', 
                                        'data' => $this->mapLayers, 
                                        'multiple' => 'multiple', 
                                        'op_value' => 'ID', 
                                        'op_text' => 'NAME',
                                        'value' => $mapLayerSelectedData, 
                                    )
                                ); 
                                ?> 
                            </div>
                        </div>
                        <div class="mt10 "><h5>Бүсчлэл</h5></div>
                        <div class="indicator-polygon-data" style=""></div>
                        <div class="mb10 mr-3">
                            <button class="indicator_save_polygon btn btn-light bg-primary btn-sm btn-block">Бүс хадгалах</button>
                        </div>
                    </div>        
                </div>                
                <div class="col-md-12 div-objectdatagrid-<?php echo $this->indicatorId; ?>">
                    <div id="md-map-canvas-<?php echo $this->indicatorId; ?>"></div>
                </div>
            </div>    
        </div>     
    </div>    
</div>

<style type="text/css">
    .polygon-row {
        font-size: 13px;
    }
</style>

<script type="text/javascript">

var objectdatagrid_<?php echo $this->indicatorId; ?> = $('#objectdatagrid<?php echo (isset($this->isBasket) ? '_' : '-') . $this->indicatorId; ?>');
var filterSearchType_<?php echo $this->indicatorId; ?> = '<?php echo issetParam($this->row['SEARCH_TYPE']); ?>';
var filterSearchType_<?php echo $this->indicatorId; ?> = '<?php echo issetParam($this->row['SEARCH_TYPE']); ?>';
var dynamicHeight = 350;
var mapLayersData<?php echo $this->indicatorId ?> = <?php echo json_encode($mapLayer) ?>;

var drawingManager, markerRowData = [], currentPolygon, currentSegmentObj = {}, currentPolygonIndicatorId, savedPolygon = {};
var strokeOpacity = 0.8;
var strokeWeight = 2;
var fillOpacity = 0.5;
var shapeOptions = {
    strokeColor: '#1e90ff',
    strokeOpacity: strokeOpacity,
    strokeWeight: strokeWeight,
    fillColor: '#1e90ff',
    fillOpacity: fillOpacity,
    editable: true
};       
var sessionRole = '<?php echo Ue::sessionRoleCode(); ?>';
var polygonZIndex = 1;
window['kpiMarkerObject'] = [];

$(function() {
    var $gmap = $('#md-map-canvas-<?php echo $this->indicatorId; ?>');
    $gmap.css('height', ($(window).height() - $gmap.offset().top - 40)); 
    
    Core.initSelect2($('.data-indicator-id<?php echo $this->indicatorId ?>').parent());

    if (typeof isKpiIndicatorScript === 'undefined') {
        $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>').done(function() {      
            kpiIndicatorGmapLoad_<?php echo $this->indicatorId; ?>();
        });
    } else {
        kpiIndicatorGmapLoad_<?php echo $this->indicatorId; ?>();
    }  

    mapToggleBtn();
    
    $('.md-map-filter-panel-<?php echo $this->indicatorId; ?>').on('click', '.edit_polygon_btn', function() {
        var $this = $(this), id = $this.closest('div.polygon-row').attr('data-id'), 
        indicatorId = $this.closest('div.indicator-polygon-data').attr('data-indicator-id'), coordinate;
        currentPolygon = window['kpiMapLayer_' + indicatorId][id];
        currentPolygonIndicatorId = indicatorId;
        googleMapSetSelection(currentPolygon);
        currentPolygon.setOptions({ zIndex: polygonZIndex++ });
        const vertices = currentPolygon.getPath();
        for (let i = 0; i < vertices.getLength(); i++) {
            const xy = vertices.getAt(i);
            if (i == '0') {
                coordinate = '{"lat": ' + xy.lat() + ', "lng": ' + xy.lng() + '}';
            } else {
                coordinate = coordinate + ', {"lat": ' + xy.lat() + ', "lng": ' + xy.lng() + '}';
            }    
        }        
        googleMapDataList = '{"drawType": "polyline", "color": "' + currentPolygon.get('strokeColor') + '", "center": {"lat": ' + map.getCenter().lat() + ', "lng": ' + map.getCenter().lng() + '}, "coordinates": [' + coordinate + ']}';    
        
        var rowData = JSON.parse(decodeURIComponent($this.closest('div.polygon-row').attr('data-rowdata')));
        rowData = Object.fromEntries(Object.entries(rowData).map(([key, val]) => [key.toLowerCase(), val]));
        currentSegmentObj = rowData;
    });
    
    $('.md-map-filter-panel-<?php echo $this->indicatorId; ?>').on('click', '.show_polygon_marker_btn', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active').css('font-size', '13px');
            for (var i = 0; i < window['kpiMarkerObject'].length; i++) {
                window['kpiMarkerObject'][i].setVisible(true);
            }             
            return;
        } else {
            $('.show_polygon_marker_btn').removeClass('active').css('font-size', '13px');
            $(this).addClass('active').css('font-size', '18px');
        }
        var response = $.ajax({
          type: "post",
          url: "api/callProcess",
          data: {
            processCode: "NEW_CUSTOMER_REGION_NewGet_004",
            paramData: { 
                segmentationId: $(this).closest(".polygon-row").attr("data-id") 
            }
          },
          dataType: "json",
          async: false
        });
        var responseParam = response.responseJSON;
        if (responseParam.status == "success" && responseParam.result.crmsegmentationdtl) {
            var getBpMarkers = responseParam.result.crmsegmentationdtl, lat, lng, hideShow;
            for (var i = 0; i < window['kpiMarkerObject'].length; i++) {
                lat = window['kpiMarkerObject'][i].getPosition().lat();
                lng = window['kpiMarkerObject'][i].getPosition().lng();
                hideShow = false;
                for (var ii = 0; ii < getBpMarkers.length; ii++) {
                    if (getBpMarkers[ii].coordinate == lat+'|'+lng || getBpMarkers[ii].coordinate == lng+'|'+lat) {
                        console.log('OK');
                        console.log(getBpMarkers[ii].coordinate);
                        window['kpiMarkerObject'][i].setVisible(true);
                        hideShow = true;
                    }
                }
                if (!hideShow) {
                    window['kpiMarkerObject'][i].setVisible(false);
                }
            }            
        }
    });
    
    $('.md-map-filter-panel-<?php echo $this->indicatorId; ?>').on('click', '.visible_polygon_btn', function() {
        var $this = $(this), id = $this.closest('div.polygon-row').attr('data-id'), indicatorId = $this.closest('div.indicator-polygon-data').attr('data-indicator-id');
        if (!$this.is(':checked')) {
            window['kpiMapLayer_' + indicatorId][id].setVisible(false);
        } else {
            window['kpiMapLayer_' + indicatorId][id].setVisible(true);
        }
    });
    
    $('.md-map-filter-panel-<?php echo $this->indicatorId; ?>').on('click', '.indicator_save_polygon', function() {
        var $filterIndicator = $('.md-map-filter-panel-<?php echo $this->indicatorId; ?>').find('.indicator-polygon-data').attr('data-indicator-id');
        if (googleMapDataList) {            
            $.ajax({
                type: 'post',
                url: 'mdform/indicatorMapProcess',
                data: {
                    indicatorId: $filterIndicator
                },
                dataType: 'json',
                success: function(data) {
                    createPolygonForIndicator(data.META_DATA_ID, JSON.parse(data.DATA));
                }
            }); 
            return;
        } else {
            alert('Polygon-оо зурна уу.');
            return;
        }
    });
    
    $('#md-map-canvas-<?php echo $this->indicatorId; ?>').on('click', 'div[role="menubar"]', function(e) {
        currentPolygon = '';
        googleMapDataList = '';
        currentSegmentObj = {};
        googleMapClearSelection();
    });

});  

$('body').on('change', 'select.data-indicator-id<?php echo $this->indicatorId ?>', function () {
    var _this = $(this);
    kpiIndicatorGmapLoad_<?php echo $this->indicatorId; ?>();
});

function mapToggleBtn() {
    var $mapFilter = $("div.md-map-container div.md-map-filter-panel"), 
        $leftToggleBtn = $("div.md-map-filter-panel.left .md-map-selector-toggle i.fa"), 
        $rightToggleBtn = $("div.md-map-filter-panel.right .md-map-selector-toggle i.fa");

    $leftToggleBtn.removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
    $rightToggleBtn.removeClass("fa-angle-double-right").addClass("fa-angle-double-left");

    $("div.md-map-selector-toggle").on('click', function () {
        var $mapToggleBtn = $(this);
        $mapFilter.toggleClass("open");
        if ($mapToggleBtn.toggleClass("open").hasClass("open")) {
            $leftToggleBtn.removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
            $rightToggleBtn.removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
        } else {
            $leftToggleBtn.removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
            $rightToggleBtn.removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
        }
    });
}

function kpiIndicatorGmapLoad_<?php echo $this->indicatorId; ?>() {
    $.getScript('https://maps.googleapis.com/maps/api/js?sensor=true&libraries=drawing&key=' + gmapApiKey + '&language='+sysLangCode).done(function() {
        
        var mapProp = {
            center: new google.maps.LatLng(47.919128, 106.917609),
            zoom: <?php echo $this->indicatorId; ?> == 16835210915019 ? 13 : 6,
            mapTypeControl: true,
            disableDefaultUI: false,
            mapTypeControlOptions: {
                position: google.maps.ControlPosition.TOP_LEFT, 
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            panControl: !0,
            panControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoomControl: !0,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            streetViewControl: !0,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: !0,
            scaleControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM
            }
        };

        map = new google.maps.Map(document.getElementById('md-map-canvas-<?php echo $this->indicatorId; ?>'), mapProp);
        var $layers = $('.div-objectdatagrid-<?php echo $this->indicatorId; ?> button[data-indicator-id]');    
        var $parentFilter = $('.list-group[data-indicatorid="<?php echo $this->indicatorId; ?>"]'), 
            $this = $('.list-group[data-indicatorid="<?php echo $this->indicatorId; ?>"]').find('input:eq(0)');      

        var getFilterData = getKpiIndicatorFilterData($this, $parentFilter);
        var $mapFilter = $('.md-map-filter-panel-<?php echo $this->indicatorId; ?>');
        
        $.each($mapFilter.find('.data-indicator-id').select2('val'), function(i, r) {

            var $this = $(this), 
                indicatorId = r, 
                color = (typeof mapLayersData<?php echo $this->indicatorId ?>[r] !== 'undefined' && typeof mapLayersData<?php echo $this->indicatorId ?>[r]['COLOR'] !== 'undefined') ? mapLayersData<?php echo $this->indicatorId ?>[r]['COLOR'] : '', 
                icon =  (typeof mapLayersData<?php echo $this->indicatorId ?>[r] !== 'undefined' && typeof mapLayersData<?php echo $this->indicatorId ?>[r]['ICON'] !== 'undefined') ? mapLayersData<?php echo $this->indicatorId ?>[r]['ICON'] : '';

            var dvSearchParam = {
                indicatorId: indicatorId,
                isGoogleMap: 1,
                filterData: getFilterData.filterData,
                drillDownCriteria: '<?php echo $this->drillDownCriteria; ?>', 
                page: 1, 
                rows: 50000
            };    

            $.ajax({
                type: 'post',
                url: 'mdform/indicatorDataGrid',
                data: dvSearchParam,
                dataType: 'json',
                success: function(data) {

                    if (data.status == 'success') {
                        data.color = color;
                        data.icon = icon;
                        kpiIndicatorGoogleMapViewLoad(indicatorId, data, map);
                    } else {
                        console.log(data);
                    }
                }
            });

        });
    });
}

function parseDrillCriteriaQuery(queryString) {
    var query = {};
    var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split('=');
        query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
    }
    return query;
}
 
function createPolygonForIndicator(metaId, data) {
  var $dialogName = "dialog-indicatormap-polygon-bp";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo("body");
  } 
  var $dialog = $("#" + $dialogName),
    jsonParam, headerPath = data.RELATION_DTL[0]['SRC'];
    
    const vertices = currentPolygon.getPath();
    for (let i = 0; i < vertices.getLength(); i++) {
        const xy = vertices.getAt(i);
        if (i == '0') {
            coordinate = '{"lat": ' + xy.lat() + ', "lng": ' + xy.lng() + '}';
        } else {
            coordinate = coordinate + ', {"lat": ' + xy.lat() + ', "lng": ' + xy.lng() + '}';
        }    
    }        
    googleMapDataList = '{"drawType": "polyline", "color": "' + currentPolygon.get('strokeColor') + '", "center": {"lat": ' + map.getCenter().lat() + ', "lng": ' + map.getCenter().lng() + '}, "coordinates": [' + coordinate + ']}';    
    
    jsonParam = JSON.stringify({ 
       ...currentSegmentObj,
        [headerPath]: googleMapDataList,
       ...parseDrillCriteriaQuery('<?php echo $this->drillDownCriteria; ?>')
    });

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: metaId,
      isDialog: true,
      isSystemMeta: false,
      fillJsonParam: jsonParam
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true
      });
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var processForm = $("#wsForm", "#" + $dialogName);
      var processUniqId = processForm.parent().attr("data-bp-uniq-id");

      var buttons = [
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
              processForm.validate({
                ignore: "",
                highlight: function (element) {
                  $(element).addClass("error");
                  $(element).parent().addClass("error");
                  if (processForm.find("div.tab-pane:hidden:has(.error)").length) {
                    processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                        var tabId = $(tab).attr("id");
                        processForm
                          .find('a[href="#' + tabId + '"]')
                          .tab("show");
                    });
                  }
                },
                unhighlight: function (element) {
                  $(element).removeClass("error");
                  $(element).parent().removeClass("error");
                },
                errorPlacement: function () { }
              });

              var isValidPattern = initBusinessProcessMaskEvent(processForm);

              if (processForm.valid() && isValidPattern.length === 0) {
                processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: plang.get("POS_0040")
                    });
                  },
                  success: function (responseData) {
                    if (responseData.status === "success") {
                        new PNotify({
                          title: "Success",
                          text: plang.get("msg_save_success"),
                          type: "success",
                          sticker: false,
                          addclass: "pnotify-center"
                        });                        
                        window['processAfterSave_' + processUniqId]($(e.target), responseData.status, responseData);
                        currentPolygon = '';
                        googleMapDataList = '';
                        currentSegmentObj = {};
                        googleMapClearSelection();
                      $dialog.dialog("close");
                    }
                    Core.unblockUI();
                  },
                  error: function () {
                    alert("Error");
                  },
                });
              }
            }
          },
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        }
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape:
            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons,
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

<?php if (!isset($this->isIgnoreFilter)) { ?>
    filterKpiIndicatorValueForm(<?php echo $this->indicatorId; ?>);
<?php } ?>

function filterKpiIndicatorValueForm(indicatorId) {
    var drillDownCriteria = window['drillDownCriteria_' + indicatorId];
    
    $.ajax({
        type: 'post',
        url: 'mdform/filterKpiIndicatorValueForm',
        data: {
            indicatorId: indicatorId, 
            drillDownCriteria: drillDownCriteria, 
            filterPosition: '<?php echo issetParam($this->row['SEARCH_TYPE']); ?>', 
            filterColumnCount: '<?php echo issetParam($this->row['SEARCH_COLUMN_NUMBER']); ?>',
            isGoogleMap: '1'
        },
        dataType: 'json',
        success: function(data) {
                        
            if (filterSearchType_<?php echo $this->indicatorId; ?> === 'top') {
                var $filterCol = $('#object-value-list-' + indicatorId + ' .kpidv-data-top-filter-col').last();
            } else {
                var $filterCol = $('#object-value-list-' + indicatorId + ' .kpidv-data-filter-col').last();
            }
            
            if (data.status == 'success' && data.html != '') {
                
                if ($filterCol.length) {
                    /* if (filterSearchType_<?php echo $this->indicatorId; ?> !== 'top') {
                        $filterCol.css('height', dynamicHeight + 100);
                    } */
                                
                    $filterCol.closest('.mv-datalist-container').addClass('mv-datalist-show-filter');
                    $filterCol.closest('.ws-page-content').removeClass('mt-2');
                
                    $filterCol.append(data.html).promise().done(function() {
                        Core.initNumberInput($filterCol);
                        Core.initLongInput($filterCol);
                        Core.initDateInput($filterCol);
                        Core.initSelect2($filterCol);

                        if ($('#object-value-list-' + indicatorId).find('.mv-indicator-filter-tree-open-btn').length) {
                            $('#object-value-list-' + indicatorId).find('.mv-indicator-filter-tree-open-btn').trigger('click');
                        }                        
                    });
                }
                
            } else {
                $filterCol.closest('.col-md-auto').remove();
            }
        }
    });
}
</script>