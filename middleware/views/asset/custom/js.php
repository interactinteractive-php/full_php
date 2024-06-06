<script type="text/javascript">

    var locale = 'mn';
    var aimagId = null,
        sumId = null;

    $(document).ready(function () {
        $('.child__').hide();
        
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/1599278565732898',
            data: {},
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            dataType: 'html',
            success: function(response) {
                $('#tab-light-123').empty().append('<div class="col-md-12 p-2">' + response + '</div>');
            }
        }).complete(function() {
            Core.unblockUI();
        });
        
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/1600054347325457',
            data: {},
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            dataType: 'html',
            success: function(response) {
                $('#tab-light-1231').empty().append('<div class="col-md-12 p-2">' + response + '</div>');
            }
        }).complete(function() {
            Core.unblockUI();
        });
        
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/1600165019028106',
            data: {},
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            dataType: 'html',
            success: function(response) {
                $('#tab-light-1232').empty().append('<div class="col-md-12 p-2">' + response + '</div>');
            }
        }).complete(function() {
            Core.unblockUI();
        });
        
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/1599544292917',
            data: {},
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            dataType: 'html',
            success: function(response) {
                $('#tab-light-1233').empty().append('<div class="col-md-12 p-2">' + response + '</div>');
            }
        }).complete(function() {
            Core.unblockUI();
        });
        
        MapInvitationChart.init(<?php echo json_encode(issetParamArray($this->map)); ?>, '<?php echo issetParam($this->uniqId); ?>');

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.XYChart);

        //

        // Increase contrast by taking evey second color
        chart.colors.step = 2;

        // Add data
        //chart.data = generateChartData();
        chart.data = <?php echo json_encode($this->cchartData) ?>;
        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;

        // Create series
        function createAxisAndSeries(field, name, opposite, bullet) {
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            if (chart.yAxes.indexOf(valueAxis) != 0) {
                valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
            }

            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = field;
            series.dataFields.dateX = "date";
            series.strokeWidth = 2;
            series.yAxis = valueAxis;
            series.name = name;
            series.tooltipText = "{name}: [bold]{valueY}[/]";
            series.tensionX = 0.8;
            series.showOnInit = true;

            var interfaceColors = new am4core.InterfaceColorSet();

            switch (bullet) {
                case "triangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 12;
                    bullet.height = 12;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var triangle = bullet.createChild(am4core.Triangle);
                    triangle.stroke = interfaceColors.getFor("background");
                    triangle.strokeWidth = 2;
                    triangle.direction = "top";
                    triangle.width = 12;
                    triangle.height = 12;
                    break;
                case "rectangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 10;
                    bullet.height = 10;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var rectangle = bullet.createChild(am4core.Rectangle);
                    rectangle.stroke = interfaceColors.getFor("background");
                    rectangle.strokeWidth = 2;
                    rectangle.width = 10;
                    rectangle.height = 10;
                    break;
                default:
                    var bullet = series.bullets.push(new am4charts.CircleBullet());
                    bullet.circle.stroke = interfaceColors.getFor("background");
                    bullet.circle.strokeWidth = 2;
                    break;
            }

            valueAxis.renderer.line.strokeOpacity = 1;
            valueAxis.renderer.line.strokeWidth = 2;
            valueAxis.renderer.line.stroke = series.stroke;
            valueAxis.renderer.labels.template.fill = series.stroke;
            valueAxis.renderer.opposite = opposite;
        }

        createAxisAndSeries("china", "China", false, "circle");
        createAxisAndSeries("korea", "Korea", true, "triangle");
        createAxisAndSeries("mon", "Mon", true, "rectangle");
        createAxisAndSeries("russia", "Russia", true, "rectangle");

        // Add legend
        chart.legend = new am4charts.Legend();

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        
        // Create chart instance
        var chart = am4core.create("chartdiv7", am4charts.XYChart);

        // Add data
        chart.data = <?php echo json_encode($this->negdsen); ?>;
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "name";
        categoryAxis.renderer.opposite = false;

        // Create value axis
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.inversed = false;
        valueAxis.title.text = " ";
        valueAxis.renderer.minLabelPosition = 0.01;

        var series1 = chart.series.push(new am4charts.LineSeries());
        series1.dataFields.valueY = "value";
        series1.dataFields.categoryX = "name";
        series1.name = "Шинээр хэвтсэн АЗЦХХ";
        series1.bullets.push(new am4charts.CircleBullet());
        series1.tooltipText = "Шинээр хэвтсэн АЗЦХХ : {valueY}";
        series1.legendSettings.valueText = "{valueY}";
        series1.visible = false;

        var series2 = chart.series.push(new am4charts.LineSeries());
        series2.dataFields.valueY = "value2";
        series2.dataFields.categoryX = "name";
        series2.name = 'Нас баралт АЗЦХХ';
        series2.bullets.push(new am4charts.CircleBullet());
        series2.tooltipText = "Нас баралт АЗЦХХ: {valueY}";
        series2.legendSettings.valueText = "{valueY}";

        var series3 = chart.series.push(new am4charts.LineSeries());
        series3.dataFields.valueY = "value3";
        series3.dataFields.categoryX = "name";
        series3.name = 'Түргэн тусламжийн дуудлага';
        series3.bullets.push(new am4charts.CircleBullet());
        series3.tooltipText = "Түргэн тусламжийн дуудлага: {valueY}";
        series3.legendSettings.valueText = "{valueY}";

        var series4 = chart.series.push(new am4charts.LineSeries());
        series4.dataFields.valueY = "value4";
        series4.dataFields.categoryX = "name";
        series4.name = 'ТТӨ-ийн шалтгаантай';
        series4.bullets.push(new am4charts.CircleBullet());
        series4.tooltipText = "ТТӨ-ийн шалтгаантай: {valueY}";
        series4.legendSettings.valueText = "{valueY}";

        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "zoomY";
        
        let hs1 = series1.segments.template.states.create("hover")
        hs1.properties.strokeWidth = 5;
        series1.segments.template.strokeWidth = 1;

        let hs2 = series2.segments.template.states.create("hover")
        hs2.properties.strokeWidth = 5;
        series2.segments.template.strokeWidth = 1;

        let hs3 = series3.segments.template.states.create("hover")
        hs3.properties.strokeWidth = 5;
        series3.segments.template.strokeWidth = 1;
        
        let hs4 = series4.segments.template.states.create("hover")
        hs4.properties.strokeWidth = 5;
        series4.segments.template.strokeWidth = 1;
    
        /*****************************************************/
    
        var chart = am4core.create("licenseAreaOfArea", am4charts.PieChart);
        chart.data = [{
                "country": plang.get("eh_orondoo_butsah"),
                "litres": <?php echo issetParamZero($this->covidDataFromMn['ehorondoobustah']) ?>
            }, {
                "country": plang.get("gadaad_ulsruu_butsah"),
                "litres": <?php echo issetParamZero($this->covidDataFromMn['gadaadulsruuz']) ?>
            }, {
                "country": plang.get("busad_huselt"),
                "litres": <?php echo issetParamZero($this->covidDataFromMn['busadorgodol']) ?>
            }
        ];
        
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "litres";
        
        pieSeries.ticks.template.disabled = true;
        pieSeries.alignLabels = false;
        pieSeries.labels.template.text = "{value.percent.formatNumber('#.0')}%";
        pieSeries.labels.template.radius = am4core.percent(-40);
        pieSeries.labels.template.fill = am4core.color("white");

        pieSeries.dataFields.category = "country";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeOpacity = 1;
        
        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;

        chart.hiddenState.properties.radius = am4core.percent(0);
        
        // Create chart instance
        var chart = am4core.create("chartdiv1", am4charts.XYChart);
        chart.scrollbarX = new am4core.Scrollbar();
        chart.scrollbarX.disabled = true;
        chart.scrollbarX.startGrip.disabled = true;
        chart.scrollbarX.endGrip.disabled = true;

        // Add data
        chart.data = <?php echo json_encode($this->stockData) ?>;
        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "country";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;
        categoryAxis.renderer.labels.template.horizontalCenter = "right";
        categoryAxis.renderer.labels.template.verticalCenter = "middle";
        categoryAxis.renderer.labels.template.rotation = 45;
        categoryAxis.tooltip.disabled = false;
        categoryAxis.renderer.minHeight = 0;
        categoryAxis.renderer.grid.template.disabled = true;
        categoryAxis.renderer.labels.template.disabled = true;
        
        
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.minWidth = 50;

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.sequencedInterpolation = true;
        series.dataFields.valueY = "visits";
        series.dataFields.categoryX = "country";
        series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
        series.columns.template.strokeWidth = 0;

        series.tooltip.pointerOrientation = "vertical";
        
        series.columns.template.column.cornerRadiusTopLeft = 10;
        series.columns.template.column.cornerRadiusTopRight = 10;
        series.columns.template.column.fillOpacity = 0.8;

        // on hover, make corner radiuses bigger
        var hoverState = series.columns.template.column.states.create("hover");
        hoverState.properties.cornerRadiusTopLeft = 0;
        hoverState.properties.cornerRadiusTopRight = 0;
        hoverState.properties.fillOpacity = 1;

        series.columns.template.adapter.add("fill", function(fill, target) {
          return chart.colors.getIndex(target.dataItem.index);
        });

        // Cursor
        chart.cursor = new am4charts.XYCursor();
        
        //chartdiv2
        
        // Themes end

        /**
         * Chart design taken from Samsung health app
         */

        var chart = am4core.create("chartdiv2", am4charts.XYChart);
        chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

        chart.paddingBottom = 30;
/*
        chart.data = [{
            "name": plang.get('ongots'),
            "steps": 2,
            "href": "<?php echo URL ?>assets/covid/img/portal/plane.png"
        }, {
            "name": plang.get("cv_bus"),
            "steps": 2,
            "href": "<?php echo URL ?>assets/covid/img/portal/bus.png"
        }, {
            "name": plang.get("cv_car"),
            "steps": 2,
            "href": "<?php echo URL ?>assets/covid/img/portal/car.png"
        }, {
            "name": plang.get("cv_passenger"),
            "steps": 2466,
            "href": "<?php echo URL ?>assets/covid/img/portal/passenger.png"
        }, {
            "name": plang.get("cv_galttereg"),
            "steps": 25,
            "href": "<?php echo URL ?>assets/covid/img/portal/train.png"
        }, {
            "name": plang.get("cv_achaanii_tereg"),
            "steps": 2072,
            "href": "<?php echo URL ?>assets/covid/img/portal/truck.png"
        }];
        */
        chart.data = <?php echo json_encode($this->frontier) ?>;
        console.log(chart.data);
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "name";
        categoryAxis.renderer.grid.template.strokeOpacity = 0;
        categoryAxis.renderer.minGridDistance = 10;
        categoryAxis.renderer.labels.template.dy = 35;
        categoryAxis.renderer.tooltip.dy = 35;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.inside = true;
        valueAxis.renderer.labels.template.fillOpacity = 0.3;
        valueAxis.renderer.grid.template.strokeOpacity = 0;
        valueAxis.min = 0;
        valueAxis.cursorTooltipEnabled = false;
        valueAxis.renderer.baseGrid.strokeOpacity = 0;

        var series = chart.series.push(new am4charts.ColumnSeries);
        series.dataFields.valueY = "steps";
        series.dataFields.categoryX = "name";
        series.tooltipText = "{valueY.value}";
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.dy = - 6;
        series.columnsContainer.zIndex = 100;

        var columnTemplate = series.columns.template;
        columnTemplate.width = am4core.percent(50);
        columnTemplate.maxWidth = 66;
        columnTemplate.column.cornerRadius(60, 60, 10, 10);
        columnTemplate.strokeOpacity = 0;

        series.heatRules.push({ target: columnTemplate, property: "fill", dataField: "valueY", min: am4core.color("#e5dc36"), max: am4core.color("#5faa46") });
        series.mainContainer.mask = undefined;

        var cursor = new am4charts.XYCursor();
        chart.cursor = cursor;
        cursor.lineX.disabled = true;
        cursor.lineY.disabled = true;
        cursor.behavior = "none";

        var bullet = columnTemplate.createChild(am4charts.CircleBullet);
        bullet.circle.radius = 30;
        bullet.valign = "bottom";
        bullet.align = "center";
        bullet.isMeasured = true;
        bullet.mouseEnabled = false;
        bullet.verticalCenter = "bottom";
        bullet.interactionsEnabled = false;

        var hoverState = bullet.states.create("hover");
        var outlineCircle = bullet.createChild(am4core.Circle);
        outlineCircle.adapter.add("radius", function (radius, target) {
            var circleBullet = target.parent;
            return circleBullet.circle.pixelRadius + 10;
        })

        var image = bullet.createChild(am4core.Image);
        image.width = 60;
        image.height = 60;
        image.horizontalCenter = "middle";
        image.verticalCenter = "middle";
        image.propertyFields.href = "href";

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
                    hs.properties.dy = -bullet.parent.pixelHeight + 30;
                    bullet.isHover = true;

                    previousBullet = bullet;
                }
            }
        });
        
        var chart = am4core.create("chartdiv3", am4charts.XYChart);

        // Add data
        chart.data = <?php echo json_encode(issetParamArray($this->a119Caller)) ?>;

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.grid.template.location = 0;
        dateAxis.renderer.minGridDistance = 50;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.logarithmic = true;
        valueAxis.renderer.minGridDistance = 20;

        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "name";
        series.tensionX = 0.8;
        series.strokeWidth = 3;

        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.fill = am4core.color("#fff");
        bullet.circle.strokeWidth = 3;

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.fullWidthLineX = true;
        chart.cursor.xAxis = dateAxis;
        chart.cursor.lineX.strokeWidth = 0;
        chart.cursor.lineX.fill = am4core.color("#000");
        chart.cursor.lineX.fillOpacity = 0.1;

        // Add scrollbar
        chart.scrollbarX = new am4core.Scrollbar();
        
        var chart = am4core.create("chartdiv4", am4charts.XYChart);

        // Add data
        chart.data = <?php echo json_encode(issetParamArray($this->huleenawah)) ?>;

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.grid.template.location = 0;
        dateAxis.renderer.minGridDistance = 50;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.logarithmic = true;
        valueAxis.renderer.minGridDistance = 20;

        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "name";
        series.tensionX = 0.8;
        series.strokeWidth = 3;

        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.fill = am4core.color("#fff");
        bullet.circle.strokeWidth = 3;

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.fullWidthLineX = true;
        chart.cursor.xAxis = dateAxis;
        chart.cursor.lineX.strokeWidth = 0;
        chart.cursor.lineX.fill = am4core.color("#000");
        chart.cursor.lineX.fillOpacity = 0.1;

        // Add scrollbar
        chart.scrollbarX = new am4core.Scrollbar();
        
        var chart = am4core.create("chartdiv6", am4charts.XYChart);

        // Add data
        chart.data = <?php echo json_encode(issetParamArray($this->khalamj)) ?>;

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.grid.template.location = 0;
        dateAxis.renderer.minGridDistance = 50;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.logarithmic = true;
        valueAxis.renderer.minGridDistance = 20;

        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "name";
        series.tensionX = 0.8;
        series.strokeWidth = 3;

        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.fill = am4core.color("#fff");
        bullet.circle.strokeWidth = 3;

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.fullWidthLineX = true;
        chart.cursor.xAxis = dateAxis;
        chart.cursor.lineX.strokeWidth = 0;
        chart.cursor.lineX.fill = am4core.color("#000");
        chart.cursor.lineX.fillOpacity = 0.1;

        // Add scrollbar
        chart.scrollbarX = new am4core.Scrollbar();
        
        var chart = am4core.create("chartdiv5", am4charts.XYChart);

        // Add data
        chart.data = <?php echo json_encode(issetParamArray($this->grippe)) ?>;

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "name";
        series.strokeWidth = 2;
        series.minBulletDistance = 10;
        series.tooltipText = "{valueY}";
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.background.cornerRadius = 20;
        series.tooltip.background.fillOpacity = 0.5;
        series.tooltip.label.padding(12,12,12,12)

        // Add scrollbar
        chart.scrollbarX = new am4charts.XYChartScrollbar();
        chart.scrollbarX.series.push(series);

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.xAxis = dateAxis;
        chart.cursor.snapToSeries = series;
        
        
        var chart = am4core.create("chartdiv8", am4charts.XYChart);
        // Increase contrast by taking evey second color
        chart.colors.step = 2;

        // Add data
        //chart.data = generateChartData();
        chart.data = <?php echo json_encode($this->a11Treatment) ?>;
        console.log(chart.data);
        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;

        // Create series
        function createAxisAndSeries3(field, name, opposite, bullet) {
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            if (chart.yAxes.indexOf(valueAxis) != 0) {
                valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
            }

            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = field;
            series.dataFields.dateX = "name";
            series.strokeWidth = 2;
            series.yAxis = valueAxis;
            series.name = name;
            series.tooltipText = "{name}: [bold]{valueY}[/]";
            series.tensionX = 0.8;
            series.showOnInit = true;

            var interfaceColors = new am4core.InterfaceColorSet();

            switch (bullet) {
                case "triangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 12;
                    bullet.height = 12;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var triangle = bullet.createChild(am4core.Triangle);
                    triangle.stroke = interfaceColors.getFor("background");
                    triangle.strokeWidth = 2;
                    triangle.direction = "top";
                    triangle.width = 12;
                    triangle.height = 12;
                    break;
                case "rectangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 10;
                    bullet.height = 10;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var rectangle = bullet.createChild(am4core.Rectangle);
                    rectangle.stroke = interfaceColors.getFor("background");
                    rectangle.strokeWidth = 2;
                    rectangle.width = 10;
                    rectangle.height = 10;
                    break;
                default:
                    var bullet = series.bullets.push(new am4charts.CircleBullet());
                    bullet.circle.stroke = interfaceColors.getFor("background");
                    bullet.circle.strokeWidth = 2;
                    break;
            }

            valueAxis.renderer.line.strokeOpacity = 1;
            valueAxis.renderer.line.strokeWidth = 2;
            valueAxis.renderer.line.stroke = series.stroke;
            valueAxis.renderer.labels.template.fill = series.stroke;
            valueAxis.renderer.opposite = opposite;
        }

        createAxisAndSeries2("value", "Том хүн", false, "circle");
        createAxisAndSeries2("value2", "Хүүхэд", false, "circle");
//        createAxisAndSeries("korea", "Korea", true, "triangle");
//        createAxisAndSeries("mon", "Mon", true, "rectangle");
//        createAxisAndSeries("russia", "Russia", true, "rectangle");

        // Add legend
        chart.legend = new am4charts.Legend();

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        var chart = am4core.create("chartdiv9", am4charts.XYChart);
        // Increase contrast by taking evey second color
        chart.colors.step = 2;

        // Add data
        //chart.data = generateChartData();
        chart.data = <?php echo json_encode($this->a11Urgent) ?>;

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;

        // Create series
        function createAxisAndSeries2(field, name, opposite, bullet) {
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            if (chart.yAxes.indexOf(valueAxis) != 0) {
                valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
            }

            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = field;
            series.dataFields.dateX = "name";
            series.strokeWidth = 2;
            series.yAxis = valueAxis;
            series.name = name;
            series.tooltipText = "{name}: [bold]{valueY}[/]";
            series.tensionX = 0.8;
            series.showOnInit = true;

            var interfaceColors = new am4core.InterfaceColorSet();

            switch (bullet) {
                case "triangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 12;
                    bullet.height = 12;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var triangle = bullet.createChild(am4core.Triangle);
                    triangle.stroke = interfaceColors.getFor("background");
                    triangle.strokeWidth = 2;
                    triangle.direction = "top";
                    triangle.width = 12;
                    triangle.height = 12;
                    break;
                case "rectangle":
                    var bullet = series.bullets.push(new am4charts.Bullet());
                    bullet.width = 10;
                    bullet.height = 10;
                    bullet.horizontalCenter = "middle";
                    bullet.verticalCenter = "middle";

                    var rectangle = bullet.createChild(am4core.Rectangle);
                    rectangle.stroke = interfaceColors.getFor("background");
                    rectangle.strokeWidth = 2;
                    rectangle.width = 10;
                    rectangle.height = 10;
                    break;
                default:
                    var bullet = series.bullets.push(new am4charts.CircleBullet());
                    bullet.circle.stroke = interfaceColors.getFor("background");
                    bullet.circle.strokeWidth = 2;
                    break;
            }

            valueAxis.renderer.line.strokeOpacity = 1;
            valueAxis.renderer.line.strokeWidth = 2;
            valueAxis.renderer.line.stroke = series.stroke;
            valueAxis.renderer.labels.template.fill = series.stroke;
            valueAxis.renderer.opposite = opposite;
        }

        createAxisAndSeries2("value", "Том хүн", false, "circle");
        createAxisAndSeries2("value2", "Хүүхэд", false, "circle");
//        createAxisAndSeries("korea", "Korea", true, "triangle");
//        createAxisAndSeries("mon", "Mon", true, "rectangle");
//        createAxisAndSeries("russia", "Russia", true, "rectangle");

        // Add legend
        chart.legend = new am4charts.Legend();

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        
        
        $('[shape-rendering="auto"]').remove();
    });
    
    $('body').on('click', '.search-company-bn', function () {
        $('html,body').animate({scrollTop: 110}, 'slow');
            
        $('#panelCompaniesList').show('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 0.3);
        
        /*
        var dvSearchParam = {
            metaDataId: '1600054347325457',
            defaultCriteriaData: 'inputMetaDataId=1600054347325457' + (aimagId ? '&departmentcode=' + aimagId : ''), 
            workSpaceId: '', 
            workSpaceParams: '', 
            uriParams: '', 
            drillDownDefaultCriteria: ''
        };
        
        dataViewLoadByElement(objectdatagrid_1599278565732898, dvSearchParam);*/
        dataViewReload('1599278565732898');
    });
    
    $('body').on('click', '.close-companies-panel', function () {
        $('#panelCompaniesList').hide('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 1);
    });
    
    $('body').on('click', '.search-company-bn1', function () {
        $('html,body').animate({scrollTop: 110}, 'slow');
            
        $('#panelCompaniesList1').show('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 0.3);
        
        var dvSearchParam = {
            metaDataId: '1600054347325457',
            defaultCriteriaData: 'inputMetaDataId=1600054347325457' + (aimagId ? '&departmentcode=' + aimagId : ''), 
            workSpaceId: '', 
            workSpaceParams: '', 
            uriParams: '', 
            drillDownDefaultCriteria: ''
        };
        
        dataViewLoadByElement(objectdatagrid_1600054347325457, dvSearchParam);
    });
    
    $('body').on('click', '.close-companies-panel2', function () {
        $('#panelCompaniesList1').hide('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 1);
    });
    
    $('body').on('click', '.search-company-bn2', function () {
        $('html,body').animate({scrollTop: 110}, 'slow');
            
        $('#panelCompaniesList2').show('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 0.3);
        
        var dvSearchParam = {
            metaDataId: '1600165019028106',
            defaultCriteriaData: 'inputMetaDataId=1600165019028106' + (aimagId ? '&departmentcode=' + aimagId : ''), 
            workSpaceId: '', 
            workSpaceParams: '', 
            uriParams: '', 
            drillDownDefaultCriteria: ''
        };
        
        dataViewLoadByElement(objectdatagrid_1600165019028106, dvSearchParam);
    });
    
    $('body').on('click', '.close-companies-panel3', function () {
        $('#panelCompaniesList2').hide('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 1);
    });
    
    $('body').on('click', '.search-company-bn4', function () {
        $('html,body').animate({scrollTop: 110}, 'slow');
            
        $('#panelCompaniesList4').show('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 0.3);
        
        var dvSearchParam = {
            metaDataId: '1599544292917',
            defaultCriteriaData: 'inputMetaDataId=1599544292917' + (aimagId ? '&departmentcode=' + aimagId : ''), 
            workSpaceId: '', 
            workSpaceParams: '', 
            uriParams: '', 
            drillDownDefaultCriteria: ''
        };
        
        dataViewLoadByElement(objectdatagrid_1599544292917, dvSearchParam);
    });
    
    $('body').on('click', '.close-companies-panel4', function () {
        $('#panelCompaniesList4').hide('slide', {direction: 'right'}, 400);
        $("#layoutLocation").fadeTo('slow', 1);
    });
    
    function refreshOtherCvDashboard (aimagId, id) {
        $('.parent__').hide();
        $('.child__').hide();
        $('.other_dtl').empty();
        if (typeof aimagId !== 'undefined' && aimagId !== '') {
            $('.child__').show();
            $('#value').empty().append('0');
            $('#value2').empty().append('0');
            $('#value3').empty().append('0');
            $('#value4').empty().append('0');
            $('#value5').empty().append('0');
            $('#value6').empty().append('0');
            $('#value7').empty().append('0');
            $('#value8').empty().append('0');
            $('#value9').empty().append('0');
            $('#1value').empty().append('0');
            
            $.ajax({
                url: "mdasset/getAimagData/",
                type: "POST",
                dataType: "json",
                data: {
                    aimagId: aimagId, 
                },
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '.airs_dashboard'
                    });
                },
                success: function (response) {
                    var $data1 = response.data1,
                        $data2 = response.data2;
                    
                    if ($data1.value) {
                        $('#value').empty().append($data1.value);
                    }
                    if ($data1.value2) {
                        $('#value2').empty().append($data1.value2);
                    }
                    if ($data1.value3) {
                        $('#value3').empty().append($data1.value3);
                    }
                    if ($data1.value4) {
                        $('#value4').empty().append($data1.value4);
                    }
                    if ($data1.value5) {
                        $('#value5').empty().append($data1.value5);
                    }
                    if ($data1.value6) {
                        $('#value6').empty().append($data1.value6);
                    }
                    if ($data1.value7) {
                        $('#value7').empty().append($data1.value7);
                    }
                    if ($data1.value8) {
                        $('#value8').empty().append($data1.value8);
                    }
                    if ($data1.value9) {
                        $('#value9').empty().append($data1.value9);
                    }
                    if ($data1.value10) {
                        $('.other_dtl').empty().append($data1.value10);
                    }
                    
                    /*
                     * 
                     * @return {undefined}
                     * 
                     */
                    
                    if ($data2.value) {
                        $('#1value').empty().append($data2.value);
                    }
                    if ($data2.value1) {
                        $('#1value1').empty().append($data2.value1);
                    }
                    if ($data2.value2) {
                        $('#1value2').empty().append($data2.value2);
                    }
                    if ($data2.value3) {
                        $('#1value3').empty().append($data2.value3);
                    }
                    if ($data2.value4) {
                        $('#1value4').empty().append($data2.value4);
                    }
                    if ($data2.value5) {
                        $('#1value5').empty().append($data2.value5);
                    }
                    if ($data2.value6) {
                        $('#1value6').empty().append($data2.value6);
                    }
                    if ($data2.value7) {
                        $('#1value7').empty().append($data2.value7);
                    }
                    if ($data2.value8) {
                        $('#1value8').empty().append($data2.value8);
                    }
                    if ($data2.value9) {
                        $('#1value9').empty().append($data2.value9);
                    }
                    if ($data2.value10) {
                        $('#1value10').empty().append($data2.value10);
                    }
                    
                    if ($data2.cvalue) {
                        $('#2value').empty().append($data2.cvalue);
                    }
                    if ($data2.cvalue1) {
                        $('#2value1').empty().append($data2.cvalue1);
                    }
                    if ($data2.cvalue2) {
                        $('#2value2').empty().append($data2.cvalue2);
                    }
                    if ($data2.cvalue3) {
                        $('#2value3').empty().append($data2.cvalue3);
                    }
                    if ($data2.cvalue4) {
                        $('#2value4').empty().append($data2.cvalue4);
                    }
                    if ($data2.cvalue5) {
                        $('#2value5').empty().append($data2.cvalue5);
                    }
                    if ($data2.cvalue6) {
                        $('#2value6').empty().append($data2.cvalue6);
                    }
                    
                    Core.unblockUI('.airs_dashboard');
                    Core.initAjax($('.airs_dashboard'));
                },
                error: function (jqXHR, exception) {
                    Core.showErrorMessage(jqXHR);
                    Core.unblockUI('.airs_dashboard');
                }
            });
        } else {
            $('.parent__').show();
        }
    }
    
    function dataviewCon123(response, tag) {
        $(tag).empty().append('<div class="col-md-12 p-2">' + response + '</div>');
    }
    
    $('body').on('change', 'select[name="stockType"]', function () {
        var $this = $(this);
        
        $.ajax({
            url: "mdasset/stockDataList/",
            type: "POST",
            dataType: "json",
            data: {stockTypeId: $this.val(), dataViewId: $this.val()},
            beforeSend: function () {
                Core.blockUI({
                    animate: true,
                    target: '.airs_dashboard'
                });
            },
            success: function (response) {
                
                $('#chartdiv1').empty();
                // Create chart instance
                var chart = am4core.create("chartdiv1", am4charts.XYChart);
                chart.scrollbarX = new am4core.Scrollbar();
                chart.scrollbarX.disabled = true;
                chart.scrollbarX.startGrip.disabled = true;
                chart.scrollbarX.endGrip.disabled = true;

                // Add data
                chart.data = response.stockData;
                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "country";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 30;
                categoryAxis.renderer.labels.template.horizontalCenter = "right";
                categoryAxis.renderer.labels.template.verticalCenter = "middle";
                categoryAxis.renderer.labels.template.rotation = 45;
                categoryAxis.renderer.grid.template.disabled = true;
                categoryAxis.renderer.labels.template.disabled = true;
                
                categoryAxis.tooltip.disabled = false;
                categoryAxis.renderer.minHeight = 0;


                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.minWidth = 50;

                // Create series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.sequencedInterpolation = true;
                series.dataFields.valueY = "visits";
                series.dataFields.categoryX = "country";
                series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
                series.columns.template.strokeWidth = 0;

                series.tooltip.pointerOrientation = "vertical";

                series.columns.template.column.cornerRadiusTopLeft = 10;
                series.columns.template.column.cornerRadiusTopRight = 10;
                series.columns.template.column.fillOpacity = 0.8;

                // on hover, make corner radiuses bigger
                var hoverState = series.columns.template.column.states.create("hover");
                hoverState.properties.cornerRadiusTopLeft = 0;
                hoverState.properties.cornerRadiusTopRight = 0;
                hoverState.properties.fillOpacity = 1;

                series.columns.template.adapter.add("fill", function(fill, target) {
                  return chart.colors.getIndex(target.dataItem.index);
                });

                // Cursor
                chart.cursor = new am4charts.XYCursor();
                setTimeout(function() {
                    $('[shape-rendering="auto"]').remove();
                }, 1000);
                
                Core.unblockUI('.airs_dashboard');
                
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR);
                Core.unblockUI('.airs_dashboard');
            }
        });
    });
    
    $('body').on('change', 'select[name="mapFilter"]', function () {
        var $this = $(this);
        
        $.ajax({
            url: "mdasset/getMapFilterTypeData/",
            type: "POST",
            dataType: "json",
            data: {id: $this.val()},
            beforeSend: function () {
                $('#layoutMap').empty();
                $('.other_dtl').empty();
                
                Core.blockUI({
                    animate: true,
                    target: '.airs_dashboard'
                });
            },
            success: function (response) {
                MapInvitationChart.init(response.map, '<?php echo issetParam($this->uniqId); ?>', $this.val());
                Core.unblockUI('.airs_dashboard');
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR);
                Core.unblockUI('.airs_dashboard');
            }
        });
        
    });
    
    $('body').on('change', 'select[name="filteryear"], select[name="filtermonth"]', function () {
        var $this = $(this);
        var $parent = $this.closest('.dataview');
        var dataViewId = $this.closest('.dataview').attr('data-viewid');
        var $dataTag = $this.closest('.dataview').attr('data-tag');
        var $filteryear = $parent.find('select[name="filteryear"]').val();
        var $filtermonth = $parent.find('select[name="filtermonth"]').val();
        
        $.ajax({
            url: "mdasset/aa119DataList/",
            type: "POST",
            dataType: "json",
            data: {
                dataViewId: dataViewId, 
                filteryear: $filteryear,
                filtermonth: $filtermonth, 
            },
            beforeSend: function () {
                Core.blockUI({
                    animate: true,
                    target: '.airs_dashboard'
                });
            },
            success: function (response) {
                $('#' + $dataTag).empty();
                
                if ($dataTag !== 'chartdiv8' || $dataTag !== 'chartdiv9') {
                    var chart = am4core.create($dataTag, am4charts.XYChart);
                    // Increase contrast by taking evey second color
                    chart.colors.step = 2;

                    // Add data
                    //chart.data = generateChartData();
                    chart.data = response.a119Caller;

                    // Create axes
                    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                    dateAxis.renderer.minGridDistance = 50;

                    // Create series
                    function createAxisAndSeries2(field, name, opposite, bullet) {
                        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                        if (chart.yAxes.indexOf(valueAxis) != 0) {
                            valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
                        }

                        var series = chart.series.push(new am4charts.LineSeries());
                        series.dataFields.valueY = field;
                        series.dataFields.dateX = "name";
                        series.strokeWidth = 2;
                        series.yAxis = valueAxis;
                        series.name = name;
                        series.tooltipText = "{name}: [bold]{valueY}[/]";
                        series.tensionX = 0.8;
                        series.showOnInit = true;

                        var interfaceColors = new am4core.InterfaceColorSet();

                        switch (bullet) {
                            case "triangle":
                                var bullet = series.bullets.push(new am4charts.Bullet());
                                bullet.width = 12;
                                bullet.height = 12;
                                bullet.horizontalCenter = "middle";
                                bullet.verticalCenter = "middle";

                                var triangle = bullet.createChild(am4core.Triangle);
                                triangle.stroke = interfaceColors.getFor("background");
                                triangle.strokeWidth = 2;
                                triangle.direction = "top";
                                triangle.width = 12;
                                triangle.height = 12;
                                break;
                            case "rectangle":
                                var bullet = series.bullets.push(new am4charts.Bullet());
                                bullet.width = 10;
                                bullet.height = 10;
                                bullet.horizontalCenter = "middle";
                                bullet.verticalCenter = "middle";

                                var rectangle = bullet.createChild(am4core.Rectangle);
                                rectangle.stroke = interfaceColors.getFor("background");
                                rectangle.strokeWidth = 2;
                                rectangle.width = 10;
                                rectangle.height = 10;
                                break;
                            default:
                                var bullet = series.bullets.push(new am4charts.CircleBullet());
                                bullet.circle.stroke = interfaceColors.getFor("background");
                                bullet.circle.strokeWidth = 2;
                                break;
                        }

                        valueAxis.renderer.line.strokeOpacity = 1;
                        valueAxis.renderer.line.strokeWidth = 2;
                        valueAxis.renderer.line.stroke = series.stroke;
                        valueAxis.renderer.labels.template.fill = series.stroke;
                        valueAxis.renderer.opposite = opposite;
                    }

                    createAxisAndSeries2("value", "Том хүн", false, "circle");
                    createAxisAndSeries2("value2", "Хүүхэд", false, "circle");
            //        createAxisAndSeries("korea", "Korea", true, "triangle");
            //        createAxisAndSeries("mon", "Mon", true, "rectangle");
            //        createAxisAndSeries("russia", "Russia", true, "rectangle");

                    // Add legend
                    chart.legend = new am4charts.Legend();

                    // Add cursor
                    chart.cursor = new am4charts.XYCursor();
                    setTimeout(function() {
                        $('[shape-rendering="auto"]').remove();
                    }, 1000);
                    Core.unblockUI('.airs_dashboard');
                    return;
                } 
                
                // Create chart instance
                var chart = am4core.create($dataTag, am4charts.XYChart);

                // Add data
                chart.data = response.a119Caller;

                // Create axes
                var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.renderer.grid.template.location = 0;
                dateAxis.renderer.minGridDistance = 50;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.logarithmic = true;
                valueAxis.renderer.minGridDistance = 20;

                // Create series
                var series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value";
                series.dataFields.dateX = "name";
                series.tensionX = 0.8;
                series.strokeWidth = 3;

                var bullet = series.bullets.push(new am4charts.CircleBullet());
                bullet.circle.fill = am4core.color("#fff");
                bullet.circle.strokeWidth = 3;

                // Add cursor
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.fullWidthLineX = true;
                chart.cursor.xAxis = dateAxis;
                chart.cursor.lineX.strokeWidth = 0;
                chart.cursor.lineX.fill = am4core.color("#000");
                chart.cursor.lineX.fillOpacity = 0.1;
                
                if ($dataTag == 'chartdiv4') {
                    $('#chartdiv5').empty();
                    // Add scrollbar
                    chart.scrollbarX = new am4core.Scrollbar();

                    var chart = am4core.create("chartdiv5", am4charts.XYChart);

                    // Add data
                    chart.data = response.grippe;

                    // Create axes
                    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                    dateAxis.renderer.minGridDistance = 50;

                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

                    // Create series
                    var series = chart.series.push(new am4charts.LineSeries());
                    series.dataFields.valueY = "value";
                    series.dataFields.dateX = "name";
                    series.strokeWidth = 2;
                    series.minBulletDistance = 10;
                    series.tooltipText = "{valueY}";
                    series.tooltip.pointerOrientation = "vertical";
                    series.tooltip.background.cornerRadius = 20;
                    series.tooltip.background.fillOpacity = 0.5;
                    series.tooltip.label.padding(12,12,12,12)

                    // Add scrollbar
                    chart.scrollbarX = new am4charts.XYChartScrollbar();
                    chart.scrollbarX.series.push(series);

                    // Add cursor
                    chart.cursor = new am4charts.XYCursor();
                    chart.cursor.xAxis = dateAxis;
                    chart.cursor.snapToSeries = series;
                } else {
                    
                }
                
                // Add a guide
                
                setTimeout(function() {
                    $('[shape-rendering="auto"]').remove();
                }, 1000);
                Core.unblockUI('.airs_dashboard');
                
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR);
                Core.unblockUI('.airs_dashboard');
            }
        });
        
    });
    
</script>