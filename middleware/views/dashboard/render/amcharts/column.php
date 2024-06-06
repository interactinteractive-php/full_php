<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8"/>
<title><?php echo Config::getFromCache('TITLE'); echo (isset($this->title)) ? ' - ' . $this->title : ''; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<base href="<?php echo URL; ?>">
        <link href="assets/custom/addon/plugins/pnotify/pnotify.custom.min.css" rel="stylesheet" type="text/css"/>
        
        <script src="assets/custom/addon/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/custom/addon/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <script src="assets/custom/addon/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/custom/addon/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/custom/addon/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/custom/addon/plugins/jquery.cokie.min.js" type="text/javascript"></script>
        <script src="assets/custom/addon/plugins/pnotify/pnotify.custom.min.js" type="text/javascript"></script>
        <script src="assets/core/global/scripts/Core.js" type="text/javascript"></script>
        <script src="assets/custom/addon/admin/layout4/scripts/layout.js" type="text/javascript"></script>
        <script type="text/javascript">
            var URL_APP = '<?php echo URL; ?>';    
        </script>
        <?php
        if (isset($this->js)) {
            foreach ($this->js as $js) {
                echo '<script src="assets/core/' . $js . '" type="text/javascript"></script>' . "\n";
            }
        }
        if (isset($this->fullUrlJs)) {
            foreach ($this->fullUrlJs as $fullUrlJs) {
                echo '<script src="' . $fullUrlJs . '" type="text/javascript"></script>' . "\n";
            }
        } 
        ?>
</head>
<div class="col-md-12 mt10 pl0 pr0" >
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container">        
        <div class="card-body" id="dashboard-<?php echo $this->metaDataId; ?>">
            <img src="assets/core/global/img/loading.gif" />
        </div>
    </div>
</div>
<script type="text/javascript">
    var isSubChart = 0;
    var noDrillDown = true;
    var selectedMetaDataId_<?php echo $this->metaDataId; ?> = '<?php echo $this->metaDataId; ?>';
    var selectedChartEvent = [];
    
    jQuery(document).ready(function () {
        amChartMinify.init();
        isSubChart = 0;
        ChartsAmcharts.drawChartAmchart($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
    });
    
    $("#search-btn-<?php echo $this->metaDataId; ?>").on("click", function () {
        var $dialogName = 'dialog-search-dashboard-<?php echo $this->metaDataId;  ?>-divid';
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $("#" + $dialogName).empty().html($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').html());
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
              { html: '<i class="fa fa-search"></i> <?php echo $this->lang->line('do_filter') ?>', class:'btn btn-sm blue-madison', click: function() {
                  if (isSubChart === 0)
                      ChartsAmcharts.drawChartAmchart($("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
                  else 
                      ChartsAmcharts.subChartInit(selectedMetaDataId_<?php echo $this->metaDataId; ?>, selectedChartEvent, $("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize());
                }
              },
              { html: '<?php echo $this->lang->line('clear_btn') ?>', class:'btn btn-sm grey-cascade', click: function() {
                    if (isSubChart === 0)
                        ChartsAmcharts.drawChartAmchart([], '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
                    else 
                        ChartsAmcharts.subChartInit(selectedMetaDataId_<?php echo $this->metaDataId; ?>, selectedChartEvent, []);
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
        Core.initAjax();
        $("#" + $dialogName).dialog('open');
    });
    
    $("#graph-btn-<?php echo $this->metaDataId; ?>").on("click", function () {
        if (isSubChart === 0)
            ChartsAmcharts.drawChartAmchart($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
        else 
            ChartsAmcharts.subChartInit(selectedMetaDataId_<?php echo $this->metaDataId; ?>, selectedChartEvent, $("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize());
        
    });
    
    $('.back-btn-dashboard-<?php echo $this->metaDataId; ?>').on("click", function() {
        isSubChart = 0;
        ChartsAmcharts.drawChartAmchart($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
    });
    
    
</script>
</html>

