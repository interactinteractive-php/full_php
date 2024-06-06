<div class="content hr-head">
    <div class="container-fluid">

        <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
        <!-- top cards -->
        <div class="row navlistTop">
            <?php
                $color1 = array(
                    0 => 'gradient-deepblue', 
                    1 => 'gradient-ibiza', 
                    2 => 'gradient-scooter', 
                    3 => 'gradient-ohhappiness'
                );
                $i = 0;
                foreach ($this->layoutPositionArr['main1_1'] as $k => $data1) {
                    if (++$i == 5) break;
                    $rowJson = htmlentities(json_encode($data1), ENT_QUOTES, 'UTF-8');
                ?>
                <div class="col">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                    <?php if(isset($data1['dvid'])){?>
                        <a href="javascript:;" class="drill"  data-row="<?php echo $rowJson; ?>"  onclick="drilldownHrList(this, '','<?php echo isset($data1['dvid']) ? $data1['dvid'] : '' ?>')" ></a>
                        <?php } ?>
                        <div class="d-flex align-items-center text-white">
                            <div class=" w-100 desc row justify-content-around">
                                <div class="col-auto"> <p class="text-uppercase"><?php echo $data1['name']; ?></p></div>
                                <div class="col"> <span  class="count-value"><?php echo $data1['val1']; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
        <!-- end top cards -->
        <!-- body -->
        <div class="row">
            <div class="box-shadow">
                <div class="card-header">
                    <?php  
                        $p3 = reset($this->layoutPositionArr['ceo_2']);
                        $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                        if($p3['dvid']){
                    ?>
                    <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')">
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section2'); ?></h6>
                    </a>
                    <?php } else{?>
                        <h6 class="mb-0"><?php echo $this->lang->line('p6section2'); ?></h6>
                    <?php } ?>
                </div>
                <div class="card-body">
                    <div class="row mt-4 mb-4">
                        <?php 
                            $icon = array(
                                0 => 'alarm', 
                                1 => 'paperplane', 
                                2 => 'calendar52', 
                                3 => 'd-none',
                                4 => 'd-none',
                                5 => 'd-none'
                            );
                            foreach ($this->layoutPositionArr['ceo_2'] as $k => $data1) { ?>
                            <div class="col codate ">
                                <div class="media align-items-center color<?php echo $k; ?>">
                                    <div class="mr-3">
                                        <a href="javascript:;" class="btn bg-icon p-3 border-0 opacity-05 text-teal btn-icon-medium">
                                            <?php if(isset($data1['icon'])){?>
                                                <?php echo $data1['icon']; ?>
                                            <?php }else{ ?>
                                                <i class="icon-calendar font-size-22"></i>
                                            <?php } ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="text-uppercase font-size-12 text-muted">
                                        <?php echo $data1['name']; ?>
                                    </div>
                                    <div class="font-size-22 line-height-normal color5">
                                        <?php echo $data1['val1']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row top">
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_2']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section2'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section2'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_3']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section3'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section3'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_4']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section4'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section4'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section4" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_5']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section5'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section5'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section5" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row middle">
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_6']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section6'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section6'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section6" class="chart">Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_7']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section7'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section7'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section7" class="chart">Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_8']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section8'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section8'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section8" class="chart">Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-3 ">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_9']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section9'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section9'); ?></h6>
                        <?php } ?>
                        <h6 class="mb-0"><?php echo $this->lang->line('p1section9'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="p1section9" class="chart">Дашбоард тохируулах</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bottom">
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_10']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section10'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section10'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section10" class="chart"></div>
                    </div> 
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_11']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section11'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section11'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section11" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_12']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section12'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section12'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section12" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['main1_13']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')"><h6 class="mb-0"><?php echo $this->lang->line('p1section13'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p1section13'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p1section13" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end body -->
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">
    var p1section2 = <?php echo json_encode($this->layoutPositionArr['main1_2']); ?>;
    var p1section3 = <?php echo json_encode($this->layoutPositionArr['main1_3']); ?>;
    var p1section4 = <?php echo json_encode($this->layoutPositionArr['main1_4']); ?>;
    var p1section5 = <?php echo json_encode($this->layoutPositionArr['main1_5']); ?>;
    var p1section6 = <?php echo json_encode($this->layoutPositionArr['main1_6']); ?>;
    var p1section7 = <?php echo json_encode($this->layoutPositionArr['main1_7']); ?>;
    var p1section8 = <?php echo json_encode($this->layoutPositionArr['main1_8']); ?>;
    var p1section9 = <?php echo json_encode($this->layoutPositionArr['main1_9']); ?>;
    var p1section10 = <?php echo json_encode($this->layoutPositionArr['main1_10']); ?>;
    var p1section11 = <?php echo json_encode($this->layoutPositionArr['main1_11']); ?>;
    var p1section12 = <?php echo json_encode($this->layoutPositionArr['main1_12']); ?>;
    var p1section13 = <?php echo json_encode($this->layoutPositionArr['main1_13']); ?>;

 
    var chart = AmCharts.makeChart("p1section5", {
        "type": "serial",
        "theme": "light",
        "marginRight": 10,
        // "legend": { 
        //     "generateFromData": false //custom property for the plugin

        // },
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['main1_4']); ?>,
        "startDuration": 1,
        "graphs": [{
            "balloonText": "<b>[[category]]: [[value]]</b>",
            "fillColorsField": "color",
            "fillAlphas": 0.9,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val1"
        }],
        "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
        },
        "categoryField": "name",
        "categoryAxis": {
    	 	"axisAlpha": 0,
        	"autoGridCount": false,
            "gridPosition": "middle",
            "labelRotation": 35,
            "autoWrap":true, 
            "gridAlpha": 0.1,
	        "gridPosition": "middle",
	        "labelFrequency": 1,
	        "tickLength": 0,
	        "maxSeries": 70,
        },
        "export": {
            "enabled": true
        }

    });

    AmCharts.makeChart("p1section3", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left"
        },
        "colors": [
            "#FA9500",
            "#1D1128", 
            "#6D72C3"
        ],
    
        "graphs": [{
            "balloonText": "[[val1]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "value",
            "labelPosition": "top",
            "labelRotation":0,
            "labelText": "  [[val1]]",
            "type": "column",
            "valueField": "val1",
            "y": 20
            }
            // {
            //     "balloonText": "[[val2]] / [[value]]",
            //     "fillAlphas": 1,
            //     "id": "AmGraph-2",
            //     "title": "[[val2]]",
            //     "labelPosition": "top",
            //     "labelRotation":0,
            //     "labelText": "  [[val2]]",
            //     "type": "column",
            //     "valueField": "val2"
            // },
            // {
            //     "balloonText": "[[val3]] / [[value]]",
            //     "fillAlphas": 1,
            //     "id": "AmGraph-3",
            //     "title": "[[val2]]",
            //     "labelPosition": "top",
            //     "labelRotation":0,
            //     "labelText": "  [[val1]]",
            //     "type": "column",
            //     "valueField": "val3"
            // },
        
        ],
        "dataProvider": p1section3
    });
    AmCharts.makeChart("p1section4", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "light",
        "rotate": true,
        "categoryAxis": {
            "position":"left",
        },
        "colors": [
            "#6D72C3",
            "#1D1128", 
            "#6D72C3"
        ],
    
        "graphs": [
            {
                "balloonText": "[[name]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "[[val2]]",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "[[val1]]",
                "type": "column",
                "valueField": "val1"
            },
            // {
            //     "balloonText": "[[val3]] / [[value]]",
            //     "fillAlphas": 1,
            //     "id": "AmGraph-3",
            //     "title": "[[val2]]",
            //     "labelPosition": "top",
            //     "labelRotation":0,
            //     "labelText": "  [[val1]]",
            //     "type": "column",
            //     "valueField": "val3"
            // },
        
        ],
        "dataProvider": p1section4
    });
    AmCharts.makeChart("p1section6", {
        "type": "pie",
        "balloonText": "[[name]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
        "innerRadius": 0,
        "colors": [
            "#a4e063",
            "#b2bece",
            "#f2b8ff",
            "#fec85e",
            "#4cebb5",
            "#a5d7fd",
            "#b2bece",
            "#00b09b",
            "#1992fe",
            "#ee0979",
        ],
        "labelsEnabled": false,
        "labelColorField": "#fff",
        "labelTickAlpha": 0,
        "outlineAlpha": 1,
        "outlineThickness": 0,
        "titleField": "name",
        "valueField": "val1",
        "allLabels": [],
        "balloon": {},
        "legend": {
            "enabled": true,
            "maxColumns":2,
            "position":'right',		
            "useGraphSettings": false,
            "align": "center",
            "markerType": "circle"
        },
        "titles": [],
        "dataProvider": p1section6
    });
    AmCharts.makeChart("p1section8", {
        "type": "pie",
        "balloonText": "[[name]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
        "innerRadius": 0,
        "colors": [
            "#a4e063",
            "#b2bece",
            "#f2b8ff",
            "#fec85e",
            "#4cebb5",
            "#a5d7fd",
            "#b2bece",
            "#a4e063"
        ],
        "labelsEnabled": false,
        "labelColorField": "#fff",
        "labelTickAlpha": 0,
        "outlineAlpha": 1,
        "outlineThickness": 2,
        "titleField": "name",
        "valueField": "val1",
        "allLabels": [],
        "balloon": {},
        "legend": {
            "enabled": true,
            "useGraphSettings": false,
            "align": "center",
            "markerType": "circle"
        },
        "titles": [],
        "dataProvider": p1section8
    });

    var data1 = groupBy(p1section2, 'category');
    var data2 = groupBy(p1section7, 'category');
    var data3 = groupBy(p1section9, 'category');
    var data4 = groupBy(p1section10, 'category');

    var dataTemp = [];
    var dataTemp1 = [];
    var dataTemp2 = [];
    var dataTemp3 = [];

    for(var key in data1){
        dataTemp[key] = {};
        for(var i=0; i < data1[key].length; i++) {
            dataTemp[key][data1[key][i]['expense']] = Number(data1[key][i]['expense']);
        }
    }

    for(var key in data2){
        dataTemp1[key] = {};
        for(var i=0; i < data2[key].length; i++) {
            dataTemp1[key][data2[key][i]['name']] = Number(data2[key][i]['val1']);
        }
    }

    for(var key in data3){
        dataTemp2[key] = {};
        for(var i=0; i < data3[key].length; i++) {
            dataTemp2[key][data3[key][i]['name']] = Number(data3[key][i]['val1']);
        }
    }

    for(var key in data4){
        dataTemp3[key] = {};
        for(var i=0; i < data4[key].length; i++) {
            dataTemp3[key][data4[key][i]['name']] = Number(data4[key][i]['val1']);
        }
    }

    //start p1section11
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Create chart instance
        var chart = am4core.create("p1section11", am4charts.XYChart);
            chart.logo.height = -120;
            // Export
           // chart.exporting.menu = new am4core.ExportMenu();

        // Data for both series
        var data = p1section11
        
        /* Create axes */
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            categoryAxis.renderer.minGridDistance = 30;

        /* Create value axis */
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        /* Create series */
        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
            columnSeries.name = "Income";
            columnSeries.dataFields.valueY = "val1";
            columnSeries.dataFields.categoryX = "name";

            columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
            columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
            columnSeries.columns.template.propertyFields.stroke = "stroke";
            columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
            columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
            columnSeries.tooltip.label.textAlign = "middle";

        var lineSeries = chart.series.push(new am4charts.LineSeries());
            lineSeries.name = "Expenses";
            lineSeries.dataFields.valueY = "val2";
            lineSeries.dataFields.categoryX = "name";

            lineSeries.stroke = am4core.color("#fdd400");
            lineSeries.strokeWidth = 3;
            lineSeries.propertyFields.strokeDasharray = "lineDash";
            lineSeries.tooltip.label.textAlign = "middle";

        var bullet = lineSeries.bullets.push(new am4charts.Bullet());
            bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
            bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        var circle = bullet.createChild(am4core.Circle);
            circle.radius = 4;
            circle.fill = am4core.color("#fff");
            circle.strokeWidth = 3;

            chart.data = data;

    }); 
    // p1section12
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Create chart instance
        var chart = am4core.create("p1section12", am4charts.XYChart);
        chart.logo.height = -120;
        // Export
        //chart.exporting.menu = new am4core.ExportMenu();

        // Data for both series
        var data =  p1section12;
        
        /* Create axes */
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            categoryAxis.renderer.minGridDistance = 30;

        /* Create value axis */
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        /* Create series */
        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
            columnSeries.name = "Income";
            columnSeries.dataFields.valueY = "val1";
            columnSeries.dataFields.categoryX = "name";

            columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
            columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
            columnSeries.columns.template.propertyFields.stroke = "stroke";
            columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
            columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
            columnSeries.tooltip.label.textAlign = "middle";

        var lineSeries = chart.series.push(new am4charts.LineSeries());
            lineSeries.name = "Expenses";
            lineSeries.dataFields.valueY = "val2";
            lineSeries.dataFields.categoryX = "name";

            lineSeries.stroke = am4core.color("#fdd400");
            lineSeries.strokeWidth = 3;
            lineSeries.propertyFields.strokeDasharray = "lineDash";
            lineSeries.tooltip.label.textAlign = "middle";

        var bullet = lineSeries.bullets.push(new am4charts.Bullet());
            bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
            bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        var circle = bullet.createChild(am4core.Circle);
            circle.radius = 4;
            circle.fill = am4core.color("#fff");
            circle.strokeWidth = 3;

            chart.data = data;

    }); 

    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Create chart instance
        var chart = am4core.create("p1section13", am4charts.XYChart);
        chart.logo.height = -120;
        // Export
        //chart.exporting.menu = new am4core.ExportMenu();

        // Data for both series
        var data = p1section13;
        
        /* Create axes */
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "name";
        categoryAxis.renderer.minGridDistance = 30;

        /* Create value axis */
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        /* Create series */
        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "Income";
        columnSeries.dataFields.valueY = "val1";
        columnSeries.dataFields.categoryX = "name";

        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";

        var lineSeries = chart.series.push(new am4charts.LineSeries());
        lineSeries.name = "Expenses";
        lineSeries.dataFields.valueY = "val2";
        lineSeries.dataFields.categoryX = "name";

        lineSeries.stroke = am4core.color("#fdd400");
        lineSeries.strokeWidth = 3;
        lineSeries.propertyFields.strokeDasharray = "lineDash";
        lineSeries.tooltip.label.textAlign = "middle";

        var bullet = lineSeries.bullets.push(new am4charts.Bullet());
        bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
        bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        var circle = bullet.createChild(am4core.Circle);
        circle.radius = 4;
        circle.fill = am4core.color("#fff");
        circle.strokeWidth = 3;

        chart.data = data;

    }); 

    //p1section2
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p1section2", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();

            // will use this to store colors of the same items
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){

                return categoryAxis.tooltipDataItem.dataContext.realName;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
                columnSeries.dataFields.categoryX = "category";
                columnSeries.dataFields.valueY = "value";

            // second value axis for quantity
            var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis2.renderer.opposite = true;
                valueAxis2.syncWithAxis = valueAxis;
                valueAxis2.tooltip.disabled = true;

            // quantity line series
            var lineSeries = chart.series.push(new am4charts.LineSeries());
                lineSeries.tooltipText = "{valueY}";
                lineSeries.dataFields.categoryX = "category";
                lineSeries.dataFields.valueY = "quantity";
                lineSeries.yAxis = valueAxis2;
                lineSeries.bullets.push(new am4charts.CircleBullet());
                lineSeries.stroke = chart.colors.getIndex(13);
                lineSeries.fill = lineSeries.stroke;
                lineSeries.strokeWidth = 2;
                lineSeries.snapTooltip = true;

            // when data validated, adjust location of data item based on count
            lineSeries.events.on("datavalidated", function(){
            lineSeries.dataItems.each(function(dataItem){
            // if count divides by two, location is 0 (on the grid)
            if(dataItem.dataContext.count / 2 == Math.round(dataItem.dataContext.count / 2)){
            dataItem.setLocation("categoryX", 0);
            }
            // otherwise location is 0.5 (middle)
            else{
                dataItem.setLocation("categoryX", 0.5);
            }
            })
            })

            // fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
            columnSeries.columns.template.adapter.add("fill", function(fill, target) {
            var name = target.dataItem.dataContext.realName;
            if (!colors[name]) {
            colors[name] = chart.colors.next();
            }
            target.stroke = colors[name];
            return colors[name];
            })


            var rangeTemplate = categoryAxis.axisRanges.template;
                rangeTemplate.tick.disabled = false;
                rangeTemplate.tick.location = 0;
                rangeTemplate.tick.strokeOpacity = 0.6;
                rangeTemplate.tick.length = 30;
                rangeTemplate.grid.strokeOpacity = 0.5;
                rangeTemplate.label.tooltip = new am4core.Tooltip();
                rangeTemplate.label.tooltip.dy = -10;
                rangeTemplate.label.cloneTooltip = false;

            ///// DATA
            var chartData = [];
            var lineSeriesData = [];
            var data = dataTemp;
           
            for (var providerName in data) {
            var providerData = data[providerName];
            var tempArray = [];
            var count = 0;
            // add items
            for (var itemName in providerData) {
            if(itemName != "quantity"){
            count++;
            // we generate unique category for each column (providerName + "_" + itemName) and store realName
            tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
            }
            }
         
            // sort temp array
            tempArray.sort(function(a, b) {
            if (a.value > b.value) {
            return 1;
            }
            else if (a.value < b.value) {
            return -1
            }
            else {
            return 0;
            }
            })

            // add quantity and count to middle data item (line series uses it)
            var lineSeriesDataIndex = Math.floor(count / 2);
                tempArray[lineSeriesDataIndex].quantity = providerData.quantity;
                tempArray[lineSeriesDataIndex].count = count;
            // push to the final data
            am4core.array.each(tempArray, function(item) {
            chartData.push(item);
            })

            // create range (the additional label at the bottom)
            var range = categoryAxis.axisRanges.create();
                range.category = tempArray[0].category;
                range.endCategory = tempArray[tempArray.length - 1].category;
                range.label.text = tempArray[0].provider;
                range.label.dy = 10;
                range.label.truncate = true;
                range.label.fontWeight = "bold";
                range.label.tooltipText = tempArray[0].provider;

            range.label.adapter.add("maxWidth", function(maxWidth, target){
            var range = target.dataItem;
            var startPosition = categoryAxis.categoryToPosition(range.category, 0);
            var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
            var startX = categoryAxis.positionToCoordinate(startPosition);
            var endX = categoryAxis.positionToCoordinate(endPosition);
            return endX - startX;
            })
            }

                 
            chart.data = chartData;

            var legend = new am4charts.Legend();
                legend.parent = chart.chartContainer;
                legend.itemContainers.template.togglable = false;
                legend.itemContainers.template.paddingTop = 2;
                legend.itemContainers.template.paddingBottom = 2;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.realName,
                        fill: column.fill
                        });
                    }
                });
                legend.data = legenddata;
            });
            // last tick
            var range = categoryAxis.axisRanges.create();
                range.category = chart.data[chart.data.length - 1].category;
                range.label.disabled = true;
                range.tick.location = 1;
                range.grid.location = 1;

    });

    //p1section7
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p1section7", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();

            // will use this to store colors of the same items
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){

                return categoryAxis.tooltipDataItem.dataContext.realName;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
                columnSeries.dataFields.categoryX = "category";
                columnSeries.dataFields.valueY = "value";

            // second value axis for quantity
            var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis2.renderer.opposite = true;
                valueAxis2.syncWithAxis = valueAxis;
                valueAxis2.tooltip.disabled = true;

            // quantity line series
            var lineSeries = chart.series.push(new am4charts.LineSeries());
                lineSeries.tooltipText = "{valueY}";
                lineSeries.dataFields.categoryX = "category";
                lineSeries.dataFields.valueY = "quantity";
                lineSeries.yAxis = valueAxis2;
                lineSeries.bullets.push(new am4charts.CircleBullet());
                lineSeries.stroke = chart.colors.getIndex(13);
                lineSeries.fill = lineSeries.stroke;
                lineSeries.strokeWidth = 2;
                lineSeries.snapTooltip = true;

            // when data validated, adjust location of data item based on count
            lineSeries.events.on("datavalidated", function(){
            lineSeries.dataItems.each(function(dataItem){
            // if count divides by two, location is 0 (on the grid)
            if(dataItem.dataContext.count / 2 == Math.round(dataItem.dataContext.count / 2)){
            dataItem.setLocation("categoryX", 0);
            }
            // otherwise location is 0.5 (middle)
            else{
                dataItem.setLocation("categoryX", 0.5);
            }
            })
            })

            // fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
            columnSeries.columns.template.adapter.add("fill", function(fill, target) {
            var name = target.dataItem.dataContext.realName;
            if (!colors[name]) {
            colors[name] = chart.colors.next();
            }
            target.stroke = colors[name];
            return colors[name];
            })


            var rangeTemplate = categoryAxis.axisRanges.template;
                rangeTemplate.tick.disabled = false;
                rangeTemplate.tick.location = 0;
                rangeTemplate.tick.strokeOpacity = 0.6;
                rangeTemplate.tick.length = 30;
                rangeTemplate.grid.strokeOpacity = 0.5;
                rangeTemplate.label.tooltip = new am4core.Tooltip();
                rangeTemplate.label.tooltip.dy = -10;
                rangeTemplate.label.cloneTooltip = false;

            ///// DATA
            var chartData = [];
            var lineSeriesData = [];
            var data = dataTemp1;
           
            for (var providerName in data) {
            var providerData = data[providerName];
            var tempArray = [];
            var count = 0;
            // add items
            for (var itemName in providerData) {
            if(itemName != "quantity"){
            count++;
            // we generate unique category for each column (providerName + "_" + itemName) and store realName
            tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
            }
            }
         
            // sort temp array
            tempArray.sort(function(a, b) {
            if (a.value > b.value) {
            return 1;
            }
            else if (a.value < b.value) {
            return -1
            }
            else {
            return 0;
            }
            })

            // add quantity and count to middle data item (line series uses it)
            var lineSeriesDataIndex = Math.floor(count / 2);
                tempArray[lineSeriesDataIndex].quantity = providerData.quantity;
                tempArray[lineSeriesDataIndex].count = count;
            // push to the final data
            am4core.array.each(tempArray, function(item) {
            chartData.push(item);
            })

            // create range (the additional label at the bottom)
            var range = categoryAxis.axisRanges.create();
                range.category = tempArray[0].category;
                range.endCategory = tempArray[tempArray.length - 1].category;
                range.label.text = tempArray[0].provider;
                range.label.dy = 10;
                range.label.truncate = true;
                range.label.fontWeight = "bold";
                range.label.tooltipText = tempArray[0].provider;

            range.label.adapter.add("maxWidth", function(maxWidth, target){
            var range = target.dataItem;
            var startPosition = categoryAxis.categoryToPosition(range.category, 0);
            var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
            var startX = categoryAxis.positionToCoordinate(startPosition);
            var endX = categoryAxis.positionToCoordinate(endPosition);
            return endX - startX;
            })
            }

                 
            chart.data = chartData;

            var legend = new am4charts.Legend();
                legend.parent = chart.chartContainer;
                legend.itemContainers.template.togglable = false;
                legend.itemContainers.template.paddingTop = 2;
                legend.itemContainers.template.paddingBottom = 2;
                legend.maxHeight = 80;
				legend.scrollable = true;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.realName,
                        fill: column.fill
                        });
                    }
                });
                legend.data = legenddata;
            });
            // last tick
            var range = categoryAxis.axisRanges.create();
                range.category = chart.data[chart.data.length - 1].category;
                range.label.disabled = true;
                range.tick.location = 1;
                range.grid.location = 1;

    });

    //p1section9
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p1section9", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();

            // will use this to store colors of the same items
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){

                return categoryAxis.tooltipDataItem.dataContext.realName;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
                columnSeries.dataFields.categoryX = "category";
                columnSeries.dataFields.valueY = "value";

            // second value axis for quantity
            var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis2.renderer.opposite = true;
                valueAxis2.syncWithAxis = valueAxis;
                valueAxis2.tooltip.disabled = true;

            // quantity line series
            var lineSeries = chart.series.push(new am4charts.LineSeries());
                lineSeries.tooltipText = "{valueY}";
                lineSeries.dataFields.categoryX = "category";
                lineSeries.dataFields.valueY = "quantity";
                lineSeries.yAxis = valueAxis2;
                lineSeries.bullets.push(new am4charts.CircleBullet());
                lineSeries.stroke = chart.colors.getIndex(13);
                lineSeries.fill = lineSeries.stroke;
                lineSeries.strokeWidth = 2;
                lineSeries.snapTooltip = true;

            // when data validated, adjust location of data item based on count
            lineSeries.events.on("datavalidated", function(){
            lineSeries.dataItems.each(function(dataItem){
            // if count divides by two, location is 0 (on the grid)
            if(dataItem.dataContext.count / 2 == Math.round(dataItem.dataContext.count / 2)){
            dataItem.setLocation("categoryX", 0);
            }
            // otherwise location is 0.5 (middle)
            else{
                dataItem.setLocation("categoryX", 0.5);
            }
            })
            })

            // fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
            columnSeries.columns.template.adapter.add("fill", function(fill, target) {
            var name = target.dataItem.dataContext.realName;
            if (!colors[name]) {
            colors[name] = chart.colors.next();
            }
            target.stroke = colors[name];
            return colors[name];
            })


            var rangeTemplate = categoryAxis.axisRanges.template;
                rangeTemplate.tick.disabled = false;
                rangeTemplate.tick.location = 0;
                rangeTemplate.tick.strokeOpacity = 0.6;
                rangeTemplate.tick.length = 30;
                rangeTemplate.grid.strokeOpacity = 0.5;
                rangeTemplate.label.tooltip = new am4core.Tooltip();
                rangeTemplate.label.tooltip.dy = -10;
                rangeTemplate.label.cloneTooltip = false;

            ///// DATA
            var chartData = [];
            var lineSeriesData = [];
            var data = dataTemp2;
           
            for (var providerName in data) {
            var providerData = data[providerName];
            var tempArray = [];
            var count = 0;
            // add items
            for (var itemName in providerData) {
            if(itemName != "quantity"){
            count++;
            // we generate unique category for each column (providerName + "_" + itemName) and store realName
            tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
            }
            }
         
            // sort temp array
            tempArray.sort(function(a, b) {
            if (a.value > b.value) {
            return 1;
            }
            else if (a.value < b.value) {
            return -1
            }
            else {
            return 0;
            }
            })

            // add quantity and count to middle data item (line series uses it)
            var lineSeriesDataIndex = Math.floor(count / 2);
                tempArray[lineSeriesDataIndex].quantity = providerData.quantity;
                tempArray[lineSeriesDataIndex].count = count;
            // push to the final data
            am4core.array.each(tempArray, function(item) {
            chartData.push(item);
            })

            // create range (the additional label at the bottom)
            var range = categoryAxis.axisRanges.create();
                range.category = tempArray[0].category;
                range.endCategory = tempArray[tempArray.length - 1].category;
                range.label.text = tempArray[0].provider;
                range.label.dy = 10;
                range.label.truncate = true;
                range.label.fontWeight = "bold";
                range.label.tooltipText = tempArray[0].provider;

            range.label.adapter.add("maxWidth", function(maxWidth, target){
            var range = target.dataItem;
            var startPosition = categoryAxis.categoryToPosition(range.category, 0);
            var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
            var startX = categoryAxis.positionToCoordinate(startPosition);
            var endX = categoryAxis.positionToCoordinate(endPosition);
            return endX - startX;
            })
            }

                 
            chart.data = chartData;

            var legend = new am4charts.Legend();
                legend.parent = chart.chartContainer;
                legend.itemContainers.template.togglable = false;
                legend.itemContainers.template.paddingTop = 2;
                legend.itemContainers.template.paddingBottom = 2;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.realName,
                        fill: column.fill
                        });
                    }
                });
                legend.data = legenddata;
            });
            // last tick
            var range = categoryAxis.axisRanges.create();
                range.category = chart.data[chart.data.length - 1].category;
                range.label.disabled = true;
                range.tick.location = 1;
                range.grid.location = 1;

    }); // end am4core.ready()

    //p1section10
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("p1section10", am4charts.XYChart);
            chart.zoomOutButton.disabled = true;
            // some extra padding for range labels
            chart.paddingBottom = 10;
            chart.logo.height = -120;

            chart.cursor = new am4charts.XYCursor();
            //chart.scrollbarX = new am4core.Scrollbar();

            // will use this to store colors of the same items
            var colors = {};

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.minGridDistance = 5;
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataItems.template.text = "";
                categoryAxis.adapter.add("tooltipText", function(tooltipText, target){

                return categoryAxis.tooltipDataItem.dataContext.realName;
            })

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.min = 0;

            // single column series for all data
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
                columnSeries.columns.template.width = am4core.percent(80);
                columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
                columnSeries.dataFields.categoryX = "category";
                columnSeries.dataFields.valueY = "value";

            // second value axis for quantity
            var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis2.renderer.opposite = true;
                valueAxis2.syncWithAxis = valueAxis;
                valueAxis2.tooltip.disabled = true;

            // quantity line series
            var lineSeries = chart.series.push(new am4charts.LineSeries());
                lineSeries.tooltipText = "{valueY}";
                lineSeries.dataFields.categoryX = "category";
                lineSeries.dataFields.valueY = "quantity";
                lineSeries.yAxis = valueAxis2;
                lineSeries.bullets.push(new am4charts.CircleBullet());
                lineSeries.stroke = chart.colors.getIndex(13);
                lineSeries.fill = lineSeries.stroke;
                lineSeries.strokeWidth = 2;
                lineSeries.snapTooltip = true;

            // when data validated, adjust location of data item based on count
            lineSeries.events.on("datavalidated", function(){
            lineSeries.dataItems.each(function(dataItem){
            // if count divides by two, location is 0 (on the grid)
            if(dataItem.dataContext.count / 2 == Math.round(dataItem.dataContext.count / 2)){
            dataItem.setLocation("categoryX", 0);
            }
            // otherwise location is 0.5 (middle)
            else{
                dataItem.setLocation("categoryX", 0.5);
            }
            })
            })

            // fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
            columnSeries.columns.template.adapter.add("fill", function(fill, target) {
            var name = target.dataItem.dataContext.realName;
            if (!colors[name]) {
            colors[name] = chart.colors.next();
            }
            target.stroke = colors[name];
            return colors[name];
            })


            var rangeTemplate = categoryAxis.axisRanges.template;
                rangeTemplate.tick.disabled = false;
                rangeTemplate.tick.location = 0;
                rangeTemplate.tick.strokeOpacity = 0.6;
                rangeTemplate.tick.length = 30;
                rangeTemplate.grid.strokeOpacity = 0.5;
                rangeTemplate.label.tooltip = new am4core.Tooltip();
                rangeTemplate.label.tooltip.dy = -10;
                rangeTemplate.label.cloneTooltip = false;

            ///// DATA
            var chartData = [];
            var lineSeriesData = [];
            var data = dataTemp3;
           
            for (var providerName in data) {
            var providerData = data[providerName];
            var tempArray = [];
            var count = 0;
            // add items
            for (var itemName in providerData) {
            if(itemName != "quantity"){
            count++;
            // we generate unique category for each column (providerName + "_" + itemName) and store realName
            tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
            }
            }
         
            // sort temp array
            tempArray.sort(function(a, b) {
            if (a.value > b.value) {
            return 1;
            }
            else if (a.value < b.value) {
            return -1
            }
            else {
            return 0;
            }
            })

            // add quantity and count to middle data item (line series uses it)
            var lineSeriesDataIndex = Math.floor(count / 2);
                tempArray[lineSeriesDataIndex].quantity = providerData.quantity;
                tempArray[lineSeriesDataIndex].count = count;
            // push to the final data
            am4core.array.each(tempArray, function(item) {
            chartData.push(item);
            })

            // create range (the additional label at the bottom)
            var range = categoryAxis.axisRanges.create();
                range.category = tempArray[0].category;
                range.endCategory = tempArray[tempArray.length - 1].category;
                range.label.text = tempArray[0].provider;
                range.label.dy = 10;
                range.label.truncate = true;
                range.label.fontWeight = "bold";
                range.label.tooltipText = tempArray[0].provider;

            range.label.adapter.add("maxWidth", function(maxWidth, target){
            var range = target.dataItem;
            var startPosition = categoryAxis.categoryToPosition(range.category, 0);
            var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
            var startX = categoryAxis.positionToCoordinate(startPosition);
            var endX = categoryAxis.positionToCoordinate(endPosition);
            return endX - startX;
            })
            }

                 
            chart.data = chartData;

            var legend = new am4charts.Legend();
                legend.parent = chart.chartContainer;
                legend.itemContainers.template.togglable = false;
                legend.itemContainers.template.paddingTop = 2;
                legend.itemContainers.template.paddingBottom = 2;
                legend.marginTop = 20;
                columnSeries.events.on("ready", function(ev) {
                var legenddata = [];
                columnSeries.columns.each(function(column,key) {
                   // console.log(column);
                    if(key < count ){
                        legenddata.push({
                        name: column.dataItem._dataContext.realName,
                        fill: column.fill
                        });
                    }
                });
                legend.data = legenddata;
            });
            // last tick
            var range = categoryAxis.axisRanges.create();
                range.category = chart.data[chart.data.length - 1].category;
                range.label.disabled = true;
                range.tick.location = 1;
                range.grid.location = 1;

    }); // end am4core.ready()

</script>