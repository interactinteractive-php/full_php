<?php $renderAtom = new Mdwidget(); ?>
<div class="w-full rounded-xl bg-white shadow-citizen">
    <div style="font-size:20px;color:#585858;font-weight: 500;padding-bottom: 0px !important;" class="p-4"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div class="p-2" style="height:470px">
    <canvas id="myChart<?php echo $this->uniqId; ?>"></canvas>
    </div>
</div>

<?php
$pservice1 = $pservice2 = $labels = [];
if ($this->datasrc) {
    foreach ($this->datasrc as $row) {
        array_push($labels, $row[Str::lower($this->positionConfig["position3"]["row"]["fieldpath"])]);
    }
    $pservice1 = Arr::groupByArray($this->datasrc, Str::lower($this->positionConfig["position1"]["row"]["fieldpath"]));
    $pservice2 = Arr::groupByArray($this->datasrc, Str::lower($this->positionConfig["position2"]["row"]["fieldpath"]));
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" type="text/javascript"></script>
<script>
    setTimeout(function() {
        var ctx = document.getElementById("myChart<?php echo $this->uniqId; ?>").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: '<?php echo $this->positionConfig["position1_label"]["row"]["fieldpath"]; ?>', // Name the series
                    data: <?php echo json_encode(array_keys($pservice1)); ?>,
                    fill: false,
                    borderColor: '#2196f3', // Add custom color border (Line)
                    backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                },{
                    label: '<?php echo $this->positionConfig["position2_label"]["row"]["fieldpath"]; ?>', // Name the series
                    data: <?php echo json_encode(array_keys($pservice2)); ?>,
                    fill: false,
                    borderColor: '#4CAF50', // Add custom color border (Line)
                    backgroundColor: '#4CAF50', // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                }]
            },
            options: {
                responsive: true, // Instruct chart js to respond nicely.
                maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
                legend: {
                    labels: {
                        position: 'right',
                        boxWidth: 15
                    }                  
                },
                plugins: {
                    datalabels: {
                        display: false
                    }
                }                         
            }
        });  
    }, 2000);
</script>