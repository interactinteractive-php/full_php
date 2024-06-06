<?php 
$uid = getUID();
$renderAtom = new Mdwidget(); ?>
<section data-sectioncode="6" class="col-span-12">
    <div class="w-full h-full grid grid-cols-12 gap-5" style="">
    <div class="w-full col-span-12 lg:col-span-12">
        <div style="font-size:18px;color:#585858;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
            <div style="color:#BCB5C3;font-size: 14px;margin-bottom: <?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? '15' : '0'; ?>px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
            <div id="linechart<?php echo $uid ?>" style="height: 300px; width: 100%"></div>                 
    </div>
</section>
<style type="text/css">
    .cloud_list_widget_bgF9F9F9 {
        background-color: #F9F9F9;
    }
</style>
<script type="text/javascript">
    $(function() {
        $.cachedScript('<?php echo autoVersion('assets/custom/addon/plugins/echarts/echarts.js'); ?>', {async: false}).done(function() {
            var chartDom = document.getElementById('linechart<?php echo $uid ?>');
            var myChart = echarts.init(chartDom);
            var option;
            
            option = {
                title: {
                    text: 'Stacked Area Chart',
                    show:false,
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                    type: 'cross',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                    }
                },
                color: ['#734cea88', '#00d9d988'],
                legend: {
                    show:false,
                    data: ['Email', 'Union Ads', 'Video Ads', 'Direct', 'Search Engine']
                    
                },
                toolbox: {
                    feature: {
                    saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                    name: 'Email',
                    type: 'line',
                    stack: 'Total',
                    areaStyle: {},
                    emphasis: {
                        focus: 'series'
                    },
                    data: [120, 132, 101, 134, 90, 230, 210]
                    },
                    {
                    name: 'Union Ads',
                    type: 'line',
                    stack: 'Total',
                    areaStyle: {},
                    emphasis: {
                        focus: 'series'
                    },
                    data: [220, 182, 191, 234, 290, 330, 310]
                    }
                ]
            };

                option && myChart.setOption(option);
        });
    });
    
</script>