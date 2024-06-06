<div class="relative w-full h-full rounded-2xl">
    <div class="w-full h-full">
        <div class="bg-white dark:bg-gray-800 rounded">
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
var data = {
    datasets: [{
        data: [
            11,
            16,
            7,
            3,
            14
        ],
        backgroundColor: [
            "#FF6384",
            "#4BC0C0",
            "#FFCE56",
            "#E7E9ED",
            "#36A2EB"
        ],
        label: 'My dataset' // for legend
    }],
    labels: [
        "Нийт ашгийн түвшин",
        "Өртөг",
        "Ү/А-ны зардал",
        "Цэвэр ашгийн түвшин",
        "Blue"
    ]
};

var pieOptions = {
  events: false,
  align: "center",
  animation: {
    duration: 500,
    easing: "easeOutQuart",
    onComplete: function () {
      var ctx = this.chart.ctx;
      ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
      ctx.textAlign = 'center';
      ctx.textBaseline = 'bottom';

      this.data.datasets.forEach(function (dataset) {

        for (var i = 0; i < dataset.data.length; i++) {
          var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
              total = dataset._meta[Object.keys(dataset._meta)[0]].total,
              mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
              start_angle = model.startAngle,
              end_angle = model.endAngle,
              mid_angle = start_angle + (end_angle - start_angle)/2;

          var x = mid_radius * Math.cos(mid_angle);
          var y = mid_radius * Math.sin(mid_angle);

          ctx.fillStyle = '#fff';
          if (i == 3){ // Darker text color for lighter background
            ctx.fillStyle = '#444';
          }
          var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
          ctx.fillText(dataset.data[i], model.x + x, model.y + y);
          // Display percent in another line, line break doesn't work for fillText
          ctx.fillText(percent, model.x + x, model.y + y + 15);
        }
      });               
    }
  }
};

var pieChartCanvas = $("#myChart");
var pieChart = new Chart(pieChartCanvas, {
  type: 'doughnut', // or doughnut
  data: data,
  options: pieOptions
});

</script>