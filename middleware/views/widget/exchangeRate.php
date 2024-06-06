<div id="widgetExchangeRate_<?php echo $this->uniqId; ?>">
    <div class="bp-checklist-title">Ханшийн мэдээ (<strong><span id="currency-name"><?php echo $this->currencyName; ?></span></strong>)</div>
    
    <style type="text/css">
    #widgetChart_<?php echo $this->uniqId; ?> {
        width: 100%;
        height: 400px;
    }					
    </style>

    <script type="text/javascript">
    amChartMinify.init();
    var widgetChart_<?php echo $this->uniqId; ?> = AmCharts.makeChart("widgetChart_<?php echo $this->uniqId; ?>", {
        "type": "serial",
        "theme": "light",
        "marginRight": 40,
        "marginLeft": 40,
        "autoMarginOffset": 20,
        "mouseWheelZoomEnabled":true,
        "dataDateFormat": "YYYY-MM-DD",
        "valueAxes": [{
            "id": "v1",
            "axisAlpha": 0,
            "position": "left",
            "ignoreAxisWidth":true
        }],
        "balloon": {
            "borderThickness": 1,
            "shadowAlpha": 0
        },
        "graphs": [{
            "id": "g1",
            "balloon":{
              "drop":true,
              "adjustBorderColor":false,
              "color":"#ffffff"
            },
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "red line",
            "useLineColorForBulletBorder": true,
            "valueField": "value",
            "balloonText": "<span style='font-size:16px;'>[[value]]</span>"
        }],
        "chartScrollbar": {
            "graph": "g1",
            "oppositeAxis":false,
            "offset":30,
            "scrollbarHeight": 80,
            "backgroundAlpha": 0,
            "selectedBackgroundAlpha": 0.1,
            "selectedBackgroundColor": "#888888",
            "graphFillAlpha": 0,
            "graphLineAlpha": 0.5,
            "selectedGraphFillAlpha": 0,
            "selectedGraphLineAlpha": 1,
            "autoGridCount":true,
            "color":"#AAAAAA"
        },
        "chartCursor": {
            "pan": true,
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            "cursorAlpha":1,
            "cursorColor":"#258cbb",
            "limitToGraph":"g1",
            "valueLineAlpha":0.2,
            "valueZoomable":true
        },
        "valueScrollbar":{
          "oppositeAxis":false,
          "offset":50,
          "scrollbarHeight":10
        },
        "categoryField": "date",
        "categoryAxis": {
            "parseDates": true,
            "dashLength": 1,
            "minorGridEnabled": true
        },
        "export": {
            "enabled": true
        },
        "dataProvider": [
        <?php
        if ($this->rateData) {
            foreach ($this->rateData as $row) {
        ?>
        {"date": "<?php echo Date::formatter($row['BANK_DATE'], 'Y-m-d'); ?>", "value": <?php echo $row['RATE']; ?>},
        <?php
            }
        }
        ?>        
        ]
    });

    widgetChart_<?php echo $this->uniqId; ?>.addListener("rendered", zoomChart);

    zoomChart();

    function zoomChart() {
        widgetChart_<?php echo $this->uniqId; ?>.zoomToIndexes(widgetChart_<?php echo $this->uniqId; ?>.dataProvider.length - 40, widgetChart_<?php echo $this->uniqId; ?>.dataProvider.length - 1);
    }
    </script>

    <div id="widgetChart_<?php echo $this->uniqId; ?>"></div>
</div>

