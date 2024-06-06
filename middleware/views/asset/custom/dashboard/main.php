<div class="content">
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
                foreach ($this->topCards as $k => $data1) {
                    if (++$i == 5) break;
                ?>

                <div class="col">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                        <div class="d-flex align-items-center text-white">
                            <div class=" w-100 desc row justify-content-around">
                                <div class="col-md-8"> <p class="text-uppercase"><?php echo $data1['name']; ?></p></div>
                                <div class="col"> <span  class="count-value"><?php echo $data1['val']; ?></span></div>
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
            <div class="col-sm-3 left-dash">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('position2-hrSummaryDashboard'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="chartdiv_pie1" class="topchart"></div>
                    </div>
                </div>
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('position5-hrSummaryDashboard'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="movingbullets" class="chart-box"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="lh-5"><?php echo $this->lang->line('position3-hrSummaryDashboard'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="topchart" id="longColumn"></div> 
                    </div>
                </div>

                <div class="box-shadow">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo $this->lang->line('position6-hrSummaryDashboard'); ?></h6>
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
                                foreach ($this->AgregatedChart as $k => $data1) { ?>
                                <div class="col codate ">
                                    <div class="media align-items-center color<?php echo $k; ?>">
                                        <div class="mr-3">
                                            <a href="javascript:;" class="btn bg-icon p-3 border-0 opacity-05 text-teal btn-icon-medium">
                                                <?php if($data1['icon']){?>
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
                                            <?php echo $data1['val']; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="box-shadow mt-n10">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo $this->lang->line('position8-hrSummaryDashboard'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="w-100 newslider">
                            <?php if (issetParam($this->news)) { ?>
                                <div class="owl-carousel news-post">
                                <?php 
                                    foreach ($this->news as $key => $row) { 
                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                        ?>
                                    <div class="item">
                                        <?php 
                                            if ($row['picture']) { 
                                                $img = $row['picture'];
                                            }else{
                                                $img =  URL.'assets/custom/img/noimage.png';
                                            }
                                            if($row['picture']){
                                        ?>
                                        <div class="post-feature" style="background-image: url('<?php echo $img; ?>');"> </div> <?php } ?>
                                        <div class="card-body">
                                            <a href="javascript:void(0);"><h4 class="card-title"><?php echo $row['title'] ?></h4><span><?php echo $row['createddate'] ?> / <?php echo $row['author'] ?></span></a>
                                            <p class="card-text"><?php echo $row['description'] ?></p>
                                        </div>
                                      
                                    </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <div class="card card-hover card-prompt">
                                    <div class="card-header bg-transparent">
                                        <h6 class="card-title mg-b-0 text-danger" style="color: #CC0000 !important;">Мэдээлэл бүртгэгдээгүй байна</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled media-list mg-b-0"></ul>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 right-dash">
                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('position4-hrSummaryDashboard'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div class="chart topchart" id="tornado_negative_stack"></div>
                        </div>
                    </div>
                </div>

                <div class="box-shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('position7-hrSummaryDashboard'); ?></h6>
                    </div>
                    <div id="transArchiv" class="chart-box">
                        <ul  class="list-group  bg-slate-800 border-0">
                        <?php
                            if ($this->trasGroup) {
                                foreach ($this->trasGroup as $key => $grouped) { ?>
                                    <div class="theader p30">
                                        <?php echo $key; ?>
                                    </div>
                                    <ul class="list-group-child border-0">
                                        <?php foreach ($grouped as $item) {  ?>
                                            <li class="list-group-item"> <?php echo $item['name']; ?> <span class="badge bg-pink-400 ml-auto"><?php echo $item['val']; ?></span></li>
                                        <?php 
                                        } ?>
                                    </ul>
                                <?php
                                }
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- end body -->
        <!-- start bottom cards -->
        <div class="row navlistBottom">
            <?php
                $color1 = array(
                    0 => 'd-none', 
                    1 => 'd-none',
                    2 => 'd-none',
                    3 => 'd-none',
                    4 => 'gradient-ohhappiness', 
                    5 => 'gradient-scooter', 
                    6 => 'gradient-ibiza', 
                    7 => 'gradient-deepblue'
                );
                $i = 0;
                foreach ($this->topCards as $k => $data1) {
                    if (++$i == 9) break;
                ?>

                <div class="col card-<?php echo $color1[$k]; ?>">
                    <div class="box-shadow cardlist <?php echo $color1[$k]; ?> ">
                        <div class="d-flex align-items-center text-white">
                            <div class=" w-100 desc row justify-content-around">
                                <div class="col-md-8"> <p class="text-uppercase"><?php echo $data1['name']; ?></p></div>
                                <div class="col"> <span  class="count-value"><?php echo $data1['val']; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
            ?>
        </div>
        <!-- end bottom cards -->
    </div>
</div>
<!-- amCharts javascript code -->
<script type="text/javascript">
  
  $('.news-post').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        autoplayTimeout:3000,
        autoplay:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
    
    AmCharts.makeChart("chartdiv_pie1", {
        "type": "pie",
        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
        "innerRadius": 50,
        "colors": [
            "#7ebcff",
            "#f77eb9"
        ],
        "labelsEnabled": false,
        "labelColorField": "#FF5F5F",
        "labelTickAlpha": 0,
        "outlineAlpha": 1,
        "outlineThickness": 2,
        "titleField": "name",
        "valueField": "val",
        "allLabels": [],
        "balloon": {},
        "legend": {
            "enabled": true,
            "useGraphSettings": false,
            "align": "center",
            "markerType": "circle"
        },
        "titles": [],
        "dataProvider": <?php echo json_encode($this->piechart); ?>
    });

    AmCharts.makeChart("longColumn", {
        "type": "serial",
        "categoryField": "name",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#EABD38", 
            "#EE9A3A",
            "#EF7E32",
        ],
        // 'legend': {
        //     spacing: 0,
        //     position: 'top',
        //     align: 'right',
        //     'markerType': 'circle',
        //     'periodValueText': 'Нийт: [[value.sum]]',
        //     // 'labelText': '[[title]] ',
        //     // 'valueText': '[[value]]',
        //     'valueWidth': 60
        // },
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
            },
            {
                "balloonText": "[[val2]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "[[val2]]",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[val2]]",
                "type": "column",
                "valueField": "val2"
            },
            {
                "balloonText": "[[val3]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-3",
                "title": "[[val2]]",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[val1]]",
                "type": "column",
                "valueField": "val3"
            },
         
        ],
        "dataProvider": <?php echo json_encode($this->longColumn); ?>
    });
    
    var chart = AmCharts.makeChart("tornado_negative_stack", {
        "type": "serial",
        "theme": "light",
        "colors": [
            "#f77eb9",
            "#7ebcff"
        ],
        "rotate": true,
        "marginBottom": 50,
        "dataProvider": <?php echo json_encode($this->twowayColumn); ?>,
        "startDuration" : 1,
        "graphs": [{
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val1",
            "title": "<?php echo $this->lang->line('tr_label_name'); ?>",
            "labelText": "[[val1]]",
            "clustered": false,
            "labelFunction": function(item) {
                return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
                return item.category + " : " + Math.abs(item.values.value);
            }
        }, {
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "val2",
            "title": "Эмэгтэй",
            "labelText": "[[val2]]",
            "clustered": false,
            "labelFunction": function(item) {
                return Math.abs(item.values.value);
            },
            "balloonFunction": function(item) {
                return item.category + ": " + Math.abs(item.values.value);
            }
        }],
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "gridAlpha": 0.2,
            "axisAlpha": 0
        },
        "valueAxes": [{
            "gridAlpha": 0,
            "ignoreAxisWidth": true,
            "labelFunction": function(value) {
                return Math.abs(value);
            },
            "guides": [{
                "value": 0,
                "lineAlpha": 0.2
            }]
        }],
        "balloon": {
            "fixedPosition": true
        },
        "chartCursor": {
            "valueBalloonsEnabled": false,
            "cursorAlpha": 0.05,
            "fullWidth": true
        },
        "allLabels": [{
            "text": "<?php echo $this->lang->line('tr_label_name1'); ?>",
            "x": "28%",
            "y": "95%",
            "bold": true,
            "align": "middle"
        }, {
            "text": "<?php echo $this->lang->line('tr_label_name2'); ?>",
            "x": "75%",
            "y": "95%",
            "bold": true,
            "align": "middle"
        }]

    });

    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("movingbullets", am4charts.XYChart);

            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
            chart.paddingRight = 35;
            chart.data = <?php echo json_encode($this->HorizantalColumn); ?>;
            chart.logo.height = -80;

        var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            categoryAxis.renderer.grid.template.strokeOpacity = 0;
            categoryAxis.renderer.minGridDistance = 10;
            categoryAxis.renderer.labels.template.dx = -15;
            categoryAxis.renderer.minWidth = 5;
            categoryAxis.renderer.tooltip.dx = -15;

        var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inside = true;
            valueAxis.renderer.labels.template.fillOpacity = 0.3;
            valueAxis.renderer.grid.template.strokeOpacity = 0;
            valueAxis.min = 0;
            valueAxis.cursorTooltipEnabled = false;
            valueAxis.renderer.baseGrid.strokeOpacity = 0;
            valueAxis.renderer.labels.template.dy = 100;

        var series = chart.series.push(new am4charts.ColumnSeries);
            series.dataFields.valueX = "steps";
            series.dataFields.categoryY = "name";
            series.tooltipText = "{valueX.value}";
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.dy = - 30;
            series.columnsContainer.zIndex = 1;

        var columnTemplate = series.columns.template;
            columnTemplate.height = am4core.percent(20);
            columnTemplate.maxHeight = 20;
            columnTemplate.column.cornerRadius(60, 10, 60, 10);
            columnTemplate.strokeOpacity = 0;

            series.heatRules.push({ target: columnTemplate, property: "fill", dataField: "valueX", min: am4core.color("#e5dc36"), max: am4core.color("#5faa46") });
            series.mainContainer.mask = undefined;

        var cursor = new am4charts.XYCursor();
            chart.cursor = cursor;
            cursor.lineX.disabled = true;
            cursor.lineY.disabled = true;
            cursor.behavior = "none";

        var bullet = columnTemplate.createChild(am4charts.CircleBullet);
            bullet.circle.radius = 15;
            bullet.valign = "middle";
            bullet.align = "left";
            bullet.isMeasured = true;
            bullet.interactionsEnabled = false;
            bullet.horizontalCenter = "right";
            bullet.interactionsEnabled = false;

        var hoverState = bullet.states.create("hover");
        var outlineCircle = bullet.createChild(am4core.Circle);
            outlineCircle.adapter.add("radius", function (radius, target) {
                var circleBullet = target.parent;
                return circleBullet.circle.pixelRadius + 5;
            })

        var image = bullet.createChild(am4core.Image);
            image.width = 30;
            image.height = 30;
            image.horizontalCenter = "middle";
            image.verticalCenter = "middle";
            image.propertyFields.href ="href";

            image.adapter.add("mask", function (mask, target) {
                var circleBullet = target.parent;
                return circleBullet.circle;
            })

        var previousBullet;
                chart.cursor.events.on("cursorpositionchanged", function (event) {
        var dataItem = series.tooltipDataItem;

            if (dataItem.column) {
                var bullet = dataItem.column.children.getIndex(1);

                if (previousBullet && previousBullet != bullet) {
                    previousBullet.isHover = false;
                }

                if (previousBullet != bullet) {

                    var hs = bullet.states.getKey("hover");
                    hs.properties.dx = dataItem.column.pixelWidth;
                    bullet.isHover = true;

                    previousBullet = bullet;
                }
            }
        })

    }); // end am4core.ready()
    
</script>