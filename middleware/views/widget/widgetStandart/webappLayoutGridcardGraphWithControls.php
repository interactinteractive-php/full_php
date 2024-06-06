<?php $renderAtom = new Mdwidget(); ?>

<div class="bg-white dark:bg-gray-800 w-full h-full rounded-2xl">

    <div class="">
        <div class="lg:flex justify-between w-full items-center">
            <div class="flex items-center">
                <p class="text-lg text-gray-600">Борлуулалт</p>
            </div>
            <div class="flex items-center">
                <div class="flex items-center">
                    <div aria-label="pink dot" role="img" tabindex="0" class="focus:outline-none w-4 h-4 bg-pink-300 rounded-lg"></div>
                    <p tabindex="0" class="focus:outline-none w-20 text-xs leading-none ml-1.5 mt-2 text-gray-600"><?php echo $renderAtom->renderAtom([], "position2_text", $this->positionConfig) ?></p>
                </div>
                <div class="flex items-center ml-4">
                    <div aria-label="yellow dot" role="img" tabindex="0" class="focus:outline-none w-4 h-4 bg-yellow-300 rounded-lg"></div>
                    <p tabindex="0" class="focus:outline-none w-20 text-xs leading-none ml-1.5 mt-2 text-gray-600"><?php echo $renderAtom->renderAtom([], "position3_text", $this->positionConfig) ?></p>
                </div>
            </div>
        </div>
        <div class="mt-8">
            <div class="chartjs-size-monitor">
                <div class="chartjs-size-monitor-expand"><div class=""></div></div>
                <div class="chartjs-size-monitor-shrink"><div class=""></div></div>
            </div>
            <canvas  id="line_chart_<?php echo $this->uniqId; ?>" height="130px" style="display: block; height: 264px; width: 880px" aria-label="chart" role="img" tabindex="0" class="focus:outline-none chartjs-render-monitor line_chart"></canvas>
        </div>
    </div>      
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.0/Chart.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript">
var chartData = [];
var positionConfig = <?php echo json_encode($this->positionConfig) ?>;

<?php if ($this->datasrc) { ?>
    chartData = <?php echo json_encode($this->datasrc) ?>;
<?php } ?>    

var labels = [], dataColumn = [], dataColumn1 = [];
var yLabels = {
    0: "$0",
    6: "$6K",
    10: "$10K",
    14: "$15K",
    20: "$20K",
    40: "$40K",
};
for (var i = 0; i < chartData.length; i++) {
    labels.push(renderAtom(chartData[i], 'position1', positionConfig));
}
for (var i = 0; i < chartData.length; i++) {
    dataColumn.push(renderAtom(chartData[i], 'position2_value', positionConfig));
}
for (var i = 0; i < chartData.length; i++) {
    dataColumn1.push(renderAtom(chartData[i], 'position3_value', positionConfig));
}

var line_chart = new Chart(document.getElementById("line_chart_<?php echo $this->uniqId; ?>"), {
    type: "line",
    data: {
        labels: labels,
        datasets: [
            {
                data: dataColumn,

                borderColor: "#F0ABFC",
                fill: false,
            },
            {
                data: dataColumn1,
                borderColor: "#FDBA74",
                fill: false,
            },
        ],
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [
                {
                    ticks: {
                        min: 0,
                    },
                    gridLines: {
                        display: false,
                    },
                },
            ],
        },
    },
});

</script>    