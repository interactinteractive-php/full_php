<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="supplyManagement_widget_window_<?php echo $this->uniqId; ?>">
    <div class="col-md-12">
        <br>
        <div>
            <h2 style="margin: 0 auto; display: table">Ривер</h2>
            <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table;" class="mt5">ХУДАЛДАН АВАЛТЫН МЭДЭЭ</p>
            <p style="font-size: 15px; margin: 0 auto; display: table;" class="mt5"><?php echo Date::beforeDate('Y.m.d', '-1 day'); ?></p>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 mt20">
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt10" style="font-size: 15px;">Нийт захиалгын тоо</p>
                <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->sumaCart1); ?></p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt10" style="font-size: 15px;">Нийт захиалгын дүн</p>
                <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->sumaCart2); ?></p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt10" style="font-size: 15px;">Нийт гүйцэтгэлийн тоо</p>
                <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->sumaCart3, true); ?></p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3" style="padding: 4px;">
            <div style="background-color: rgb(204,255,102); height: 105px; padding-left: 8px; padding-top: 1px;padding-right: 8px;">
                <p class="mt10" style="font-size: 15px;">Нийт гүйцэтгэлийн дүн</p>
                <p style="font-size: 18px; font-weight: bold;margin-top: 48px;text-align: right;"><?php echo Str::formatMoney($this->sumaCart4, true); ?></p>
            </div>
        </div>     
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="col-md-6 col-sm-6 mt20 no-padding" style="background-color:#fff;">
            <p style="font-size: 15px; font-weight: bold">Захиалсан бараа төрлөөр</p>
            <div id="supplyManagement2_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>	
        </div>    
        <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff; padding-right: 0px">
            <p style="font-size: 15px; font-weight: bold;">Захиалсан бараа нийлүүлэгчээр</p>
            <div id="supplyManagement_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(155,187,89);"></div>
        </div>
    </div>
</div>

<style>
    #serial1_dashboard_<?php echo $this->uniqId; ?>, #supplyManagement2_widget_chart_<?php echo $this->uniqId; ?>, #supplyManagement_widget_chart_<?php echo $this->uniqId; ?> {
        height: 420px;
    }
    .supplyManagement-card-title {
        font-size: 15px;
    }    
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#supplyManagement_widget_window_<?php echo $this->uniqId; ?>';
    amChartMinify.init();

    AmCharts.makeChart("supplyManagement2_widget_chart_<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 15,
        "labelText": "[[percents]]%",
        "allLabels": [{
          "y": "45%",
          "align": "center",
          "size": 18,
          "bold": true,
          "text": '',
          "color": "#555"
        }, {
          "y": "54%",
          "align": "center",
          "size": 12,
          "text": "",
          "color": "#555"
        }],              
        "legend": {
            "position": "right",
            "marginRight": 100,
            "valueWidth": 120,
            "autoMargins": false,
            "markerType": "circle"
        },
        "radius": "33%",
        "innerRadius": "75%",
        "dataProvider": <?php echo json_encode($this->sumaPie5); ?>,
        "valueField": "TOTAL_AMOUNT",
        "titleField": "ITEM_CATEGORY_NAME",         
        "export": {
            "enabled": false
        }
    });   
    
    AmCharts.makeChart("supplyManagement_widget_chart_<?php echo $this->uniqId; ?>", {
        "type": "pie",
        "startDuration": 0,
        "outlineColor": "",
        "theme": "light",
        "fontSize": 15,
        "labelText": "[[percents]]%",
        "allLabels": [{
          "y": "45%",
          "align": "center",
          "size": 18,
          "bold": true,
          "text": '',
          "color": "#555"
        }, {
          "y": "54%",
          "align": "center",
          "size": 12,
          "text": "",
          "color": "#555"
        }],              
        "legend": {
            "position": "right",
            "marginRight": 100,
            "valueWidth": 120,
            "autoMargins": false,
            "markerType": "circle"
        },
        "radius": "33%",
        "innerRadius": "75%",
        "dataProvider": <?php echo json_encode($this->sumaPie6); ?>,
        "valueField": "TOTAL_AMOUNT",
        "titleField": "CUSTOMER_NAME",         
        "export": {
            "enabled": false
        }
    });   
</script>