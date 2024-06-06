<?php $renderAtom = new Mdwidget(); ?>

<div class="w-full h-full false" style="grid-gap:2%">
    <div class=" ">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-4 ">
        <?php 
        if ($this->datasrc) {
            foreach($this->datasrc as $key => $row) { ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-citizen p-4">
                    <div class="flex items-center justify-between w-full sm:w-full">
                        <div class="flex items-center">
                            <div>
                                <span class="text-sm lg:text-lg text-base text-gray-600 font-bold block"><?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig) ?></span>
                                <span class="text-sm lg:text-2xl text-base text-gray-600 font-bold block mt-2"><?php echo Str::formatMoney($renderAtom->renderAtom($row, "position40", $this->positionConfig), false) ?></span>
                                <p class="focus:outline-none text-sm leading-3 font-medium text-gray-400 pt-1"><?php echo $renderAtom->renderAtom($row, "position41", $this->positionConfig) ?>: <?php echo $renderAtom->renderAtom($row, "position42", $this->positionConfig) ?></p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-full h-full">
                                <div class="flex items-center h-full justify-center">
                                    <canvas  id="myChart<?php echo $key ?>" width="120" height="85" role="img" aria-label="chart" tabindex="0" class="focus:outline-none relative z-10"></canvas>
                                    <div class="absolute flex items-center z-0 justify-center">
                                        <p style="padding-top: 12px;" class="focus:outline-none text-2xl text-center text-black"><?php echo $renderAtom->renderAtom($row, "position90", $this->positionConfig) ?>%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.0/Chart.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript">
Chart.pluginService.register({
    afterUpdate: function (chart) {
        if (chart.config.options.elements.arc.roundedCornersFor !== undefined) {
            var arc = chart.getDatasetMeta(0).data[chart.config.options.elements.arc.roundedCornersFor];
            arc.round = {
                x: (chart.chartArea.left + chart.chartArea.right) / 2,
                y: (chart.chartArea.top + chart.chartArea.bottom) / 2,
                radius: (chart.outerRadius + chart.innerRadius) / 2,
                thickness: (chart.outerRadius - chart.innerRadius) / 2 - 1,
                backgroundColor: arc._model.backgroundColor,
            };
        }
    },

    afterDraw: function (chart) {
        if (chart.config.options.elements.arc.roundedCornersFor !== undefined) {
            var ctx = chart.chart.ctx;
            var arc = chart.getDatasetMeta(0).data[chart.config.options.elements.arc.roundedCornersFor];
            var startAngle = Math.PI / 2 - arc._view.startAngle;
            var endAngle = Math.PI / 2 - arc._view.endAngle;

            ctx.save();
            ctx.translate(arc.round.x, arc.round.y);
            ctx.fillStyle = arc.round.backgroundColor;
            ctx.beginPath();
            ctx.arc(arc.round.radius * Math.sin(startAngle), arc.round.radius * Math.cos(startAngle), arc.round.thickness, 0, 2 * Math.PI);
            ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round.radius * Math.cos(endAngle), arc.round.thickness, 0, 2 * Math.PI);
            ctx.closePath();
            ctx.fill();
            ctx.restore();
        }
    },
});

var chartData = [];
var positionConfig = <?php echo json_encode($this->positionConfig) ?>;

<?php if ($this->datasrc) { ?>
var chartData = <?php echo json_encode($this->datasrc) ?>;
<?php } ?>

for (var i = 0; i < chartData.length; i++) {
    var config = {
        type: "doughnut",
        data: {
            labels: ["", ""],
            datasets: [
                {
                    data: [renderAtom(chartData[i], 'position90', positionConfig), 100 - renderAtom(chartData[i], 'position91', positionConfig)],
                    backgroundColor: [renderAtom(chartData[i], 'position44', positionConfig) ? renderAtom(chartData[i], 'position44', positionConfig) : "#3B82F6", renderAtom(chartData[i], 'position43', positionConfig) ? renderAtom(chartData[i], 'position43', positionConfig) : "#EFF6FF"],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            hover: { mode: null },
            elements: {
                arc: {
                    roundedCornersFor: 0,
                },
            },
            legend: {
                display: false,
            },
            cutoutPercentage: 85,
        },
    };

    var ctx = document.getElementById("myChart"+i).getContext("2d");
    var myChart = new Chart(ctx, config);
}
</script>