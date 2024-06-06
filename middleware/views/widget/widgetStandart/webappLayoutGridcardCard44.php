<div class="relative w-full h-full rounded-2xl">
    <div class="w-full h-full">
        <div class="md:w-96 w-80 bg-white dark:bg-gray-800 rounded">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <p tabindex="0" class="focus:outline-none text-lg text-gray-600 pr-3">Үйл ажиллагаа</p>
                </div>
            </div>
            <div role="img" aria-label="chart" tabindex="0" class="focus:outline-none h-full w-full mt-10 relative flex items-center justify-center">
                <canvas id="myChart" width="" height="130px"></canvas>
            </div>
        </div>
    </div>      
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.0/Chart.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript">
var ctx = document.getElementById("myChart").getContext("2d");
var purple_orange_gradient = ctx.createLinearGradient(0, 0, 0, 300);
purple_orange_gradient.addColorStop(0, "#FDA4AF");
purple_orange_gradient.addColorStop(1, "#8B5CF6");

var red_orange_gradient = ctx.createLinearGradient(0, 0, 0, 300);
red_orange_gradient.addColorStop(0, "#F87171");
red_orange_gradient.addColorStop(1, "#FDBA74");

var chartData = [];
var positionConfig = <?php echo json_encode($this->positionConfig) ?>;

<?php if ($this->datasrc) { ?>
var chartData = <?php echo json_encode($this->datasrc) ?>;
<?php } ?>

var labels = [], dataColumn = [], dataColumn1 = [];

for (var i = 0; i < chartData.length; i++) {
    labels.push(renderAtom(chartData[i], 'position1', positionConfig));
}
for (var i = 0; i < chartData.length; i++) {
    dataColumn.push(renderAtom(chartData[i], 'position2_value', positionConfig));
}
for (var i = 0; i < chartData.length; i++) {
    dataColumn1.push(renderAtom(chartData[i], 'position3_value', positionConfig));
}

var data = {
    labels: labels,
    datasets: [
        {
            label: renderAtom(chartData[0], 'position2_text', positionConfig),
            backgroundColor: purple_orange_gradient,
            data: dataColumn,
        },
        {
            label: renderAtom(chartData[0], 'position3_text', positionConfig),
            backgroundColor: red_orange_gradient,
            data: dataColumn1,
        },
    ],
};

var myBarChart = new Chart(ctx, {
    type: "bar",
    data: data,
    options: {
        legend: {
            display: false,
        },
        barValueSpacing: 0,
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
            xAxes: [
                {
                    barPercentage: 1.0,
                    gridLines: {
                        display: false,
                    },
                },
            ],
        },
    },
});

</script>