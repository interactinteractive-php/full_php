<div class="col-md-12 mt10 pl0 pr0">
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container">        
        <div class="card light bordered mb0 pb5 mddashboard-card">
            <div class="card-title mddashboard-card-title" id="card-title-<?php echo $this->metaDataId; ?>">
                <div class="caption mddashboard-caption">
                    <span class="caption-subject font-weight-bold mddashboard-title" title="" id="dashboard-title-<?php echo $this->metaDataId; ?>" style="float: left; width: 100%; word-wrap: break-word; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 20px"></span>
                    <span class="caption-helpe mddashboard-helper" id="dashboard-helper-<?php echo $this->metaDataId; ?>"></span>
                </div>
                <div class="actions mddashboard-actions">
                    <a href="javascript:;" class="btn btn-sm blue-madison"  style="color:#EEEEEE !important"  id="search-btn-<?php echo $this->metaDataId; ?>"><i class="fa fa-search"></i> Хайлт</a>
                </div>
            </div>
            <div class="card-body" id="dashboard-<?php echo $this->metaDataId; ?>">
                <img src="assets/core/global/img/loading.gif" />
            </div>
        </div>
    </div>
</div>
<div id="dashboard-filter-div-<?php echo $this->metaDataId; ?>" class="hidden">
    <form id="dashboard-filter-form-<?php echo $this->metaDataId; ?>">
        <?php echo $this->defaultCriteria; ?>
    </form>
</div>
<style>
    #dashboard-<?php echo $this->metaDataId; ?> {    
        width: 600px;
        height: 400px;        
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function () {
        amChartMinify.init();
        drawChart($('#dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
        
        $("#dashboard-filter-form-<?php echo $this->metaDataId; ?> .dataview-default-filter-btn").on("click", function () {
            drawChart($('#dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
        });
    });

    function drawChart(defaultCriteriaData, chartType, metaDataId) {
        // get data
        $.ajax({
            type: 'post',
            url: URL_APP + 'mddashboard/getColumnOneDiagramData',
            dataType: 'json',
            data: {metaDataId: metaDataId, defaultCriteriaData: defaultCriteriaData},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (response) {
                $('#dashboard-'+metaDataId).width(response.width);
                // get config
                var config = getConfig(response, metaDataId, chartType);
                ChartsAmcharts.init(chartType, 'dashboard-'+metaDataId, config.data, config);

                Core.unblockUI();
            }
        }).done(function () {
            $('#dashboard-container-'+metaDataId).find('.open').removeClass('open');
        });
    }
    
    function getConfig(response, metaDataId, chartType) {
        var data = response.series.data;
        var xAxisName = response.series.xAxisName;
        var yAxisName = response.series.yAxisName;
        // bar эсэх
        var isBar = false;
        if (chartType === 'am_bar') {
            isBar = true;
        }

        var title = '';
        if (response.isTitle === 1) {
            title = response.title;
            $('#card-title-'+metaDataId).show();
            $('#dashboard-title-'+metaDataId).html(title);
        } else {
            $('#card-title-'+metaDataId).hide();
        }
        
        var graphs = [];
        if (typeof response.series.xAxisGroupName !== 'undefined') {
            $.each(response.series.xAxisName, function(key, value){
                var tmpGraphs = {
                    "balloonText": value+": <b>[[value]]</b>",
                    "fillAlphas": 0.8,
                    "id": "xAxisItem"+key,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "valueField": value
                };
                graphs.push(tmpGraphs);
            });
            
            xAxisName = response.series.xAxisGroupName;
        }else{
            graphs = [{
                    "balloonText": "[[category]]: <b>[[value]]</b>",
                    "fillAlphas": 0.8,
                    "lineAlpha": 0.2,                   
                    "type": "column",
                    "valueField": response.series.yAxisName
                }]
        }
        
        return {
            "data": data,
            "xAxisName": xAxisName,
            "yAxisName": yAxisName,
            "isBar": isBar,
            "graphs": graphs
        };
    }
    
    $("#search-btn-<?php echo $this->metaDataId; ?>").on("click", function () {
        var $dialogName = 'dialog-search-dashboard-<?php echo $this->metaDataId;  ?>-divid';
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $("#" + $dialogName).empty().html($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').html());
        Core.initAjax();
        $("#" + $dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: '<?php echo $this->diagram['TITLE']; ?> хайлт',
            width: 'auto',
            height: 'auto',
            modal: true,
            close: function() {
              $("#" + $dialogName).empty().dialog('destroy').remove();
            },
            buttons: [
              { html: '<i class="fa fa-search"></i> Хайх', class:'btn btn-sm blue-madison', click: function() {
                  drawChart($('#dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
                }
              },
              { html: 'Хаах', class: 'btn btn-sm default', click: function() {
                  $("#" + $dialogName).empty().dialog('destroy').remove();
                }
              }
            ]
        }).dialogExtend({
              "closable": true,
              "maximizable": true,
              "minimizable": true,
              "collapsable": true,
              "dblclick": "maximize",
              "minimizeLocation": "left",
              "icons": {
                  "close": "ui-icon-circle-close",
                  "maximize": "ui-icon-extlink",
                  "minimize": "ui-icon-minus",
                  "collapse": "ui-icon-triangle-1-s",
                  "restore": "ui-icon-newwin"
              }
          });
        $("#" + $dialogName).dialog('open');
    });
</script>


