<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php
$chart1_V = 33242.5;
$chart1_EV = 22954.5;
$chart1Sum = $chart1_V + $chart1_EV;
$percentHeight = $chart1_EV * 100 / $chart1Sum;

$chart2_V = 39736.4;
$chart2_EV = 11142.6;
$chart2Sum = $chart1_V + $chart2_EV;
$chart2PercentHeight = $chart2_EV * 100 / $chart2Sum;
?>

<div id="main_window_<?php echo $this->uniqId; ?>">
    <div class="col-md-12 col-sm-12" style="">    
        <div class="col-md-6 col-sm-6" style="background-color:#fff;">
            <div class="water_gauge_title_<?php echo $this->uniqId; ?>">
                <p style="font-size: 15px; font-weight: bold;" class="float-left">01</p>
                <p style="font-size: 15px; font-weight: bold;" class="float-right">DT</p>
            </div>
            <div id="water_gauge1_<?php echo $this->uniqId; ?>" class="">
                <div class="vertical-line-<?php echo $this->uniqId; ?>"></div>
            </div>	
            <ul class="label-<?php echo $this->uniqId; ?>">
                <li>P: <span class="bold">1549.5</span></li>
                <li>W: <span class="bold">0.0</span></li>
                <li>T: <span class="bold">0.0</span></li>
                <li>D: <span class="bold">0.0</span></li>
                <li>V: <span class="bold">22954.5</span></li>
                <li>EV: <span class="bold">33242.5</span></li>
                <li>Weight: <span class="bold">0.0</span></li>
            </ul>
        </div>
        <div class="col-md-6 col-sm-6" style="background-color:#fff;">
            <div class="water_gauge_title_<?php echo $this->uniqId; ?>">
                <p style="font-size: 15px; font-weight: bold;" class="float-left">02</p>
                <p style="font-size: 15px; font-weight: bold;" class="float-right">92#</p>
            </div>
            <div id="water_gauge2_<?php echo $this->uniqId; ?>" class="">
                <div class="vertical-line-<?php echo $this->uniqId; ?>"></div>
            </div>	
            <ul class="label-<?php echo $this->uniqId; ?>">
                <li>P: <span class="bold">1549.5</span></li>
                <li>W: <span class="bold">0.0</span></li>
                <li>T: <span class="bold">0.0</span></li>
                <li>D: <span class="bold">0.0</span></li>
                <li>V: <span class="bold">22954.5</span></li>
                <li>EV: <span class="bold">33242.5</span></li>
                <li>Weight: <span class="bold">0.0</span></li>
            </ul>            
        </div>
    </div>
</div>

<style>
    #water_gauge1_<?php echo $this->uniqId; ?> {
        height: 320px;
        width: 320px;
        -moz-border-radius: 50%; 
        -webkit-border-radius: 50%; 
        border-radius: 50%;
        border: 2px solid #000;
        background: linear-gradient(to top, #79a6d2 <?php echo $percentHeight; ?>%, #fff <?php echo $percentHeight; ?>%, #fff 100%);
    }
    #water_gauge2_<?php echo $this->uniqId; ?> {
        height: 320px;
        width: 320px;
        -moz-border-radius: 50%; 
        -webkit-border-radius: 50%; 
        border-radius: 50%;
        border: 2px solid #000;
        background: linear-gradient(to top, #ff4d4d <?php echo $chart2PercentHeight; ?>%, #fff <?php echo $chart2PercentHeight; ?>%, #fff 100%);
    }
    .vertical-line-<?php echo $this->uniqId; ?> {
        width: 2px;
        background-color: black;
        height: 100%;
        margin: 0 auto;
    }
    .label-<?php echo $this->uniqId; ?> {
        position: absolute;
        top: 40px;
        left: 400px;
        list-style: none;
        font-size: 15px;
        line-height: 2.5;
    }
    .water_gauge_title_<?php echo $this->uniqId; ?> {
        height: 35px;
        border: 1px solid #b7b7b7;
        background-color: #ccc;
        padding: 8px;
        margin-bottom: 18px;        
    }
</style>