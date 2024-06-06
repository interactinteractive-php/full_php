<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="supply_widget_window_<?php echo $this->uniqId; ?>">
<div class="col-md-12">
    <div class="col-md-12 mt10" style="background-color:#fff;">
        <h3 style="margin-bottom: -6px;">Хамгийн их авч буй 5 бараа материал</h3>
        <hr style="border-color: #1bd9fb">
        <table class="table table-hover" id="top5sale">
            <thead>
                <tr>
                    <th>
                        <strong>Бүтээгдэхүүний нэр</strong>
                    </th>
                    <th class="text-right">
                        <strong>Тоо</strong>
                    </th>
                    <th class="">
                        <strong>Сүүлийн сард</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($this->getTopFiveSupplyItem) {
                    $sumVar = 0;
                    foreach ($this->getTopFiveSupplyItem as $key => $row) {
                        echo '<tr>';
                        echo '<td style="vertical-align: middle">' . $row['ITEM_NAME'] . '</td>';
                        echo '<td class="text-right" style="vertical-align: middle"><strong>' . $row['QUANTITY'] . '</strong></td>';
                        echo '<td data-chart-json=\'' . htmlentities(json_encode($row['itemDtl']), ENT_QUOTES, 'UTF-8') . '\'><div id="inline_widget_chart_' . $key . '" style="vertical-align: middle;display: inline-block; width: 160px; height: 40px;"></div></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>             
    </div>  
    <div class="col-md-12 mt10" style="background-color:#fff">
        <h3 style="margin-bottom: -6px;">Худалдан авалтын хэмжээ</h3>
        <hr style="border-color: #1bd9fb">
        <div id="serial1_dashboard_<?php echo $this->uniqId; ?>"></div>
    </div>    
    <div class="col-md-12 mt10" style="background-color:#fff">
        <h3 style="margin-bottom: -6px;">Топ 5 нийлүүлэгч</h3>
        <hr style="border-color: #1bd9fb">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        <strong>Нийлүүлэгч</strong>
                    </th>
                    <th class="text-right">
                        <strong>Дүн</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sumVar = 0;
                if($this->getDataTop5Supplier) {
                    foreach ($this->getDataTop5Supplier as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['CUSTOMER_NAME'] . '</td>';
                        echo '<td class="text-right"><strong>' . Str::formatMoney($row['UNIT_AMOUNT']) . '</strong></td>';
                        echo '</tr>';
                        $sumVar += (int) $row['UNIT_AMOUNT'];
                    }
                }
                ?>
            </tbody>
            <tfoot style="border-top: 2px solid #ddd;">
                <tr>
                    <td></td>
                    <td><strong>НИЙТ:<span class="float-right"><?php echo Str::formatMoney($sumVar); ?></span></strong></td>
                </tr>
            </tfoot>
        </table>        
    </div>
    <div class="col-md-12 mt10 mb10" style="background-color:#fff">         
        <h3 style="margin-bottom: -6px;">Худалдан авалтын буцаалт</h3>
        <hr style="border-color: #1bd9fb">
        <div id="supply_widget_chart_<?php echo $this->uniqId; ?>"></div>	        
    </div>
</div>
</div>

<style>
    #supply_widget_chart_<?php echo $this->uniqId; ?>, #serial1_dashboard_<?php echo $this->uniqId; ?> {
        width: 100%;
        height: 300px;
        font-size: 11px;
    }    
    .sales-card-title {
        font-size: 15px;
    }
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#supply_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();

    if($('#top5sale', widWindowId_<?php echo $this->uniqId; ?>).length) {
        $('#top5sale > tbody > tr', widWindowId_<?php echo $this->uniqId; ?>).each(function(){
            var chartContainer = $(this).find('td:last');
            if(chartContainer.attr('data-chart-json') != '')
                inlineWidgetRender_<?php echo $this->uniqId; ?>(chartContainer.children().attr('id'), chartContainer.attr('data-chart-json'));
        });
    }
    
    function inlineWidgetRender_<?php echo $this->uniqId; ?>($id, $dataJson) {
        AmCharts.makeChart($id, {
          "type": "serial",
          "dataProvider": JSON.parse($dataJson),
          "categoryField": "ORDER_DATE",
          "autoMargins": false,
          "marginLeft": 0,
          "marginRight": 0,
          "marginTop": 0,
          "marginBottom": 0,
          "graphs": [{
            "valueField": "QUANTITY",
            "type": "column",
            "fillAlphas": 1,
            "showBalloon": true,
            "lineColor": "#ffbf63",
            "balloonText": "[[category]]: <b>[[value]]</b>"
          }],
          "valueAxes": [{
            "gridAlpha": 0,
            "axisAlpha": 0
          }],
          "categoryAxis": {
            "gridAlpha": 0,
            "axisAlpha": 0
          }
        } );
    }
    
    AmCharts.makeChart("supply_widget_chart_<?php echo $this->uniqId; ?>", {
        "type": "serial",
        "theme": "dark",
        "dataProvider": <?php echo json_encode($this->getDataItemReturnSupply); ?>,
        "valueAxes": [ {
          "gridColor": "#FFFFFF",
          "gridAlpha": 0.2,
          "dashLength": 0
        } ],
        "gridAboveGraphs": true,
        "startDuration": 1,
        "graphs": [{
          "balloonText": "[[ITEM_NAME]]: <b>[[value]]</b>",
          "fillAlphas": 0.8,
          "lineAlpha": 0.2,
          "type": "column",
          "valueField": "RETURN_QTY"
        }],
        "chartCursor": {
          "categoryBalloonEnabled": false,
          "cursorAlpha": 0,
          "zoomable": false
        },
        "categoryField": "BOOK_DATE",
        "categoryAxis": {
          "gridPosition": "start",
          "gridAlpha": 0,
          "tickPosition": "start",
          "tickLength": 20
        },
        "export": {
            "enabled": false
        }
    });
    
    AmCharts.makeChart("serial1_dashboard_<?php echo $this->uniqId; ?>", {
        "type": "serial",
        "theme": "light",
        "marginRight": 80,
        "dataProvider": <?php echo json_encode($this->getDataSupplyByActivity); ?>,
        "graphs": [{
            "id": "g1",
            "fillAlphas": 0.4,
            "valueField": "UNIT_AMOUNT",
            "balloonText": "<div style='margin:5px;'>Худалдан авалт: <b>[[value]]</b></div>"
        }],
        "chartCursor": {
            "categoryBalloonDateFormat": "JJ:NN, DD MMMM",
            "cursorPosition": "mouse"
        },
        "categoryField": "BOOK_DATE",
        "categoryAxis": {
            "minPeriod": "mm",
            "parseDates": true
        },
        "export": {
            "enabled": false
        }
    });  
</script>