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
<div class="col-md-12 mt10 pl0 pr0">
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container">        
        <!-- BEGIN PORTLET-->
        <div class="card light bordered mb0 pb5 pt2">
            <div class="card-body" id="dashboard-<?php echo $this->metaDataId; ?>">
                <img src="assets/core/global/img/loading.gif" />
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
<!-- Заавал ID аар хэмжээс авч байна. -->
<style>
    #dashboard-<?php echo $this->metaDataId; ?> {    
        width: 600px;
        height: 400px;        
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function () {
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
    
</script>
</html>

