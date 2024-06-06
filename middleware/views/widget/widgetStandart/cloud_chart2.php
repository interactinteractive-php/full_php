<?php $renderAtom = new Mdwidget(); ?>
<div class="w-full rounded-xl bg-white shadow-citizen">
    <div style="font-size:20px;color:#585858;font-weight: 500;padding-bottom: 0px !important;" class="p-4"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div class="p-2" style="height:470px">
        <canvas id="myChart2<?php echo $this->uniqId; ?>"></canvas>
    </div>
</div>

<?php
    $amount = $pservice1 = [];
    if ($this->datasrc) {
        $pservice1 = Arr::groupByArray($this->datasrc, Str::lower($this->positionConfig["position1"]["row"]["fieldpath"]));
        foreach ($pservice1 as $row) {
            $sum = 0;
            foreach ($row['rows'] as $row2) {
                $sum += $row2[Str::lower($this->positionConfig["position2"]["row"]["fieldpath"])];
            }
            array_push($amount, $sum);
        }
    }
?>

<script>
    $.getScript("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js").done(function() {
        $.getScript('https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.2.0/dist/chartjs-plugin-datalabels.min.js').done(function() {        
            setTimeout(function() {
                const colorScheme = [
                    "#25CCF7","#FD7272","#54a0ff","#00d2d3",
                    "#1abc9c","#2ecc71","#3498db","#9b59b6","#34495e",
                    "#16a085","#27ae60","#2980b9","#8e44ad","#2c3e50",
                    "#f1c40f","#e67e22","#e74c3c","#ecf0f1","#95a5a6",
                    "#f39c12","#d35400","#c0392b","#bdc3c7","#7f8c8d",
                    "#55efc4","#81ecec","#74b9ff","#a29bfe","#dfe6e9",
                    "#00b894","#00cec9","#0984e3","#6c5ce7","#ffeaa7",
                    "#fab1a0","#ff7675","#fd79a8","#fdcb6e","#e17055",
                    "#d63031","#feca57","#5f27cd","#54a0ff","#01a3a4"
                ]        
                var ctx = document.getElementById("myChart2<?php echo $this->uniqId; ?>").getContext('2d');
                var myChart2 = new Chart(ctx, {
                    type: 'pie',
                    "data": {
                        "labels": <?php echo json_encode(array_keys($pservice1)); ?>,
                        "datasets": [{
                            "label": "",
                            "data": <?php echo json_encode($amount); ?>,
                            "backgroundColor": colorScheme
                        }]
                    },
                    options: {
                        responsive: true, // Instruct chart js to respond nicely.
                        maintainAspectRatio: false,
                        legend: {
                            labels: {
                                position: 'right',
                                boxWidth: 15
                            }                  
                        },
                        plugins: {
                            datalabels: {
                                color: '#fff',
                                formatter: function(value, context) {
                                    let sum = 0;
                                    let dataArr = context.chart.data.datasets[0].data;
                                    dataArr.map(data => {
                                        sum += data;
                                    });                               
                                    let percentage = (value*100 / sum).toFixed(2);
                                    if (percentage > 30)
                                        return pureNumberFormat(value) + " ("+percentage+'%)';
                                    else
                                        return "";
                                }
                            }
                        }           
                    }               
                });  
            }, 2000);
        });
    });
</script>