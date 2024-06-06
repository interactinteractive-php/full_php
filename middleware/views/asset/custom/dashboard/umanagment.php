<div class="content hr-main1">
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
                foreach ($this->layoutPositionArr['p12section1'] as $k => $data1) {
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
        <div class="row top">
            <div class="col-sm-6 col-md-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['p12section2']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')">
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section2'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section2'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p12section2" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['p12section3']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')">
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section3'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section3'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p12section3" class="chart"></div>
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row middle">
            <div class="col-sm-4 col-md-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['p12section4']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')">
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section4'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section4'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p12section4" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['p12section5']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')">
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section5'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section5'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p12section5" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <?php  
                            $p3 = reset($this->layoutPositionArr['p12section6']);
                            $rowJson = htmlentities(json_encode($p3), ENT_QUOTES, 'UTF-8');
                            if($p3['dvid']){
                        ?>
                        <a href="javascript:;" data-row="<?php echo $rowJson; ?>" onclick="drilldownHrList(this, 'HRM_CAMPAIGN_KEY_NEED_LIST','<?php echo $p3['dvid']; ?>')">
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section6'); ?></h6></a>
                        <?php } else{?>
                            <h6 class="mb-0"><?php echo $this->lang->line('p12section6'); ?></h6>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div id="p12section6" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end body -->
    </div>
</div>
<!-- amCharts javascript code -->

<script type="text/javascript">
    var p12section2 = <?php echo json_encode($this->layoutPositionArr['p12section2']); ?>;
    var p12section3 = <?php echo json_encode($this->layoutPositionArr['p12section3']); ?>;
    var p12section4 = <?php echo json_encode($this->layoutPositionArr['p12section4']); ?>;
    var p12section5 = <?php echo json_encode($this->layoutPositionArr['p12section5']); ?>;
    var p12section6 = <?php echo json_encode($this->layoutPositionArr['p12section6']); ?>;
    var p12section7 = <?php echo json_encode($this->layoutPositionArr['p12section7']); ?>;

    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("p12section2", am4charts.XYChart);
            chart.logo.height = -120;
            chart.data = [{
                "year": "2016",
                "europe": 2.5,
                "namerica": 2.5,
                "asia": 2.1,
                "lamerica": 0.3,
                "meast": 0.2,
                "africa": 0.1
                }, {
                "year": "2017",
                "europe": 2.6,
                "namerica": 2.7,
                "asia": 2.2,
                "lamerica": 0.3,
                "meast": 0.3,
                "africa": 0.1
                }, {
                "year": "2018",
                "europe": 2.8,
                "namerica": 2.9,
                "asia": 2.4,
                "lamerica": 0.3,
                "meast": 0.3,
                "africa": 0.1
            }];
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "year";
            categoryAxis.renderer.grid.template.location = 0;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inside = true;
            valueAxis.renderer.labels.template.disabled = true;
            valueAxis.min = 0;
        // Create series
        function createSeries(field, name) {

        // Set up series
        var series = chart.series.push(new am4charts.ColumnSeries());
            series.name = name;
            series.dataFields.valueY = field;
            series.dataFields.categoryX = "year";
            series.sequencedInterpolation = true;

        // Make it stacked
        series.stacked = true;

        // Configure columns
        series.columns.template.width = am4core.percent(60);
        series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";

        // Add label
        var labelBullet = series.bullets.push(new am4charts.LabelBullet());
            labelBullet.label.text = "{valueY}";
            labelBullet.locationY = 0.5;
            labelBullet.label.hideOversized = true;

        return series;
        }

        createSeries("europe", "item 1");
        createSeries("namerica", "item 2");
        createSeries("asia", "item 3");
        createSeries("lamerica", "item 4");
        createSeries("meast", "item 5");
        createSeries("africa", "item 6");

        // Legend
        chart.legend = new am4charts.Legend();

    }); 
    var chart = AmCharts.makeChart( "p12section3", {
        "type": "radar",
        "theme": "light",
        "dataProvider": [ {
            "country": "Easy to Use",
            "litres": 3.8
        }, {
            "country": "Fun to Use",
            "litres": 3.3
        }, {
            "country": "User-friendly",
            "litres": 3.8
        }, {
            "country": "Helpful",
            "litres": 3.6
        }, {
            "country": "I felt satisfied.",
            "litres": 3.8
        }],
        "valueAxes": [ {
            "axisTitleOffset": 20,
            "minimum": 1,
            "maximum": 5,
            "axisAlpha": 0.15
        } ],
        "startDuration": 2,
        "graphs": [ {
            "balloonText": "[[value]] ",
            "bullet": "round",
            "lineThickness": 2,
            "valueField": "litres"
        } ],
        "categoryField": "country",
        "export": {
            "enabled": true
        }
    });

    var options1 = {
        
        xaxis: {
            name:'%',
            categories: ['1','2', '3', '4', '5'],
        },
        plotOptions: {
            series: {
                name:'percent',
            },
            line:{
                dataLabels:{
                    enabled:false
                }
            }
            
        },
        series: [{
            data: [20, 32, 10, 90, 50,]
        }],
        chart: {
            height: 380,
            type: 'line',
            toolbar: {
                show: false
            }
        },
        stroke: {
            width: 5,
            curve: 'smooth'
        },
        dataLabels: {
          enabled: true,
        },
        title: {
            align: 'left',
            style: {
                fontSize: "16px",
                color: '#666'
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: [ '#FDF835'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
            size: 4,
            colors: ["#FFA41B"],
            strokeColors: "#fff",
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        }
    };

    var options2 = {
        series: [{
            data: [20, 32, 10, 90, 50,]
        }],
        xaxis: {
            categories: ['1', '2', '3', '4', '5'],
        },
        dataLabels: {
          enabled: true,
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        chart: {
            height: 380,
            type: 'line',
            toolbar: {
                show: false
            }
        },
        stroke: {
            width: 5,
            curve: 'smooth'
        },
        title: {
            align: 'left',
            style: {
                fontSize: "16px",
                color: '#666'
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: [ '#FDD835'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
            size: 4,
            colors: ["#FFA41B"],
            strokeColors: "#fff",
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        }
    };

    var options3 = {
        series: [{
            name:'Хувь',
            data: [52, 10, 35, 62, 50,]
        }],
        xaxis: {
            categories: ['1', '2', '3', '4', '5'],
        },
        dataLabels: {
          enabled: true,
        },
        chart: {
            height: 380,
            type: 'line',
            toolbar: {
                show: false
            }
        },
        stroke: {
            width: 5,
            curve: 'smooth'
        },
        title: {
            align: 'left',
            style: {
                fontSize: "16px",
                color: '#666'
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: [ '#FDD835'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
            size: 4,
            colors: ["#FFA41B"],
            strokeColors: "#fff",
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#p12section4"), options1);
    var chart2 = new ApexCharts(document.querySelector("#p12section5"), options2);
    var chart3 = new ApexCharts(document.querySelector("#p12section6"), options3);

    chart1.render();
    chart2.render();
    chart3.render();

</script>