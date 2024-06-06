<style type="text/css">
.kl-layout {
    margin: -15px -15px -0 -15px;
    padding: 15px;
    background-color: #ebebeb;
    min-height: calc(100vh - 85px);
}    
.kl-layout .col-form {
    padding-bottom: 15px;
}
.kl-layout .card {
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    -ms-border-radius: 6px;
    -o-border-radius: 6px;
    border-radius: 6px;
}
.kl-layout .card {
    margin-bottom: 0;
}
.kl-layout .card > .card-header:not(.invisible) {
    margin-top: -14px;
    margin-bottom: 13px;
    border-bottom: 1px #ddd solid;
    padding-bottom: 5px;
    height: auto;
}
.kl-layout .card > .card-header > .card-title {
    color: #333;
}
.kl-layout .card > .card-body > div[data-section-path]:last-of-type {
    margin-bottom: 0!important;
} 
.kl-layout .card.bg-warning .card-title, 
.kl-layout .card.bg-dark .card-title, 
.kl-layout .card.bg-primary .card-title, 
.kl-layout .card.bg-secondary .card-title, 
.kl-layout .card.bg-danger .card-title, 
.kl-layout .card.bg-success .card-title, 
.kl-layout .card.bg-info .card-title, 
.kl-layout .card[class*=bg-purple] .card-title, 
.kl-layout .card[class*=bg-grey] .card-title {
    color: #fff;
}
.kl-layout .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .card-header {
    border-bottom-color: rgba(255,255,255,.4);
}
.kl-layout .kpidv-data-filter-col {
    border-right: none;
}
.kl-layout .kpi-indicator-filter-title {
    margin-top: -8px!important;
}
</style>

<div class="kl-layout">
    <div class="row kpi-layout-chart" id="<?php echo $this->uniqId; ?>">
        
        <div class="col-md-auto pr0 d-none">
            <div class="card p-3 kl-sectioncode1-card kpidv-data-filter-col">
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col col-form bl-section" data-kl-col="1" data-kpis-indicatorid="<?php echo $this->indicatorId; ?>" data-src-indicatorid="<?php echo $this->row['SRC_INDICATOR_ID']; ?>">
                    <div class="card p-3 h-100 kl-sectioncode1-card">

                        <div class="card-header">
                            <h6 class="card-title"><?php echo $this->row['NAME']; ?></h6>
                        </div>

                        <script type="text/template" data-id="kpi-datamart-chart-render-<?php echo $this->uniqId; ?>">
                            <?php 
                            $this->row['GRAPH_JSON'] = str_replace('{"type":', '{"chartName": "'.$this->row['NAME'].'", "type":', $this->row['GRAPH_JSON']);
                            echo $this->row['GRAPH_JSON']; 
                            ?>
                        </script>

                        <div class="card-body" id="kpi-datamart-chart-render-<?php echo $this->uniqId; ?>" style="height: 500px">
                        </div>
                    </div>
                </div>

                <?php
                if (isset($this->form) && $this->form) {
                ?>
                <div class="col-md-12 col-form bl-section">
                    <div class="card p-3 h-100 kl-sectioncode1-card">
                        <div class="card-body">
                            <?php echo $this->form; ?>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var kpiDMChart_<?php echo $this->uniqId; ?> = $('#<?php echo $this->uniqId; ?>');
var dynamicHeight_<?php echo $this->uniqId; ?> = $(window).height() - kpiDMChart_<?php echo $this->uniqId; ?>.offset().top - 110;
var kpiChartRefreshTimer = 60 * 1000;

if (dynamicHeight_<?php echo $this->uniqId; ?> < 230) {
    dynamicHeight_<?php echo $this->uniqId; ?> = 350;
}
if (typeof isKpiIndicatorEchartsScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/echartsBuilder.js'); ?>').done(function() {      
        if (typeof isKpiIndicatorScript === 'undefined') {
            $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>').done(function() {
                kpiChartLoad_<?php echo $this->uniqId; ?>();
            setInterval(function() {
                kpiChartLoad_<?php echo $this->uniqId; ?>();
            }, kpiChartRefreshTimer);
            });
        } else {
            kpiChartLoad_<?php echo $this->uniqId; ?>();
            setInterval(function() {
                kpiChartLoad_<?php echo $this->uniqId; ?>();
            }, kpiChartRefreshTimer);
        }
    });
} else {
    if (typeof isKpiIndicatorScript === 'undefined') {
        $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>').done(function() {
            kpiChartLoad_<?php echo $this->uniqId; ?>();
            setInterval(function() {
                kpiChartLoad_<?php echo $this->uniqId; ?>();
            }, kpiChartRefreshTimer);
        });
    } else {
        kpiChartLoad_<?php echo $this->uniqId; ?>();
        setInterval(function() {
            kpiChartLoad_<?php echo $this->uniqId; ?>();
        }, kpiChartRefreshTimer);
    }
}


function kpiChartLoad_<?php echo $this->uniqId; ?>(elem) {

    setTimeout(function() {
        var $parent = $('#<?php echo $this->uniqId; ?>');

        if ($parent.is(':visible')) {

            var $sections = $parent.find('[data-kpis-indicatorid]');

            $sections.each(function() {

                var $this = $(this), 
                    indicatorId = $this.attr('data-src-indicatorid'), 
                    $script = $this.find('script[data-id]'), 
                    chartId = $script.attr('data-id'), 
                    scriptJson = $script.text(), 
                    jsonObj = JSON.parse(scriptJson), chartConfig = jsonObj.chartConfig;

                var $col = $parent.find('.kpidv-data-filter-col');
                
                var getFilterData = getKpiIndicatorFilterData('', $col);
                var filterData = getFilterData.filterData;

                var postData = {
                    indicatorId: indicatorId, 
                    chartConfig: chartConfig, 
                    filterData: filterData
                };
                
                if (jsonObj.hasOwnProperty('chartFilterCriteria')) {
                    
                    var loopFilterData = JSON.parse(html_entity_decode(jsonObj.chartFilterCriteria, 'ENT_QUOTES'));
                    
                    if (Object.keys(loopFilterData).length > 0 && Object.keys(postData.filterData).length == 0) {
                        postData.filterData = loopFilterData;
                    }
                }
                
                if (typeof elem == 'undefined') {
                    postData.isFirstLoad = 1;
                }

                $.ajax({
                    type: 'post',
                    url: 'mdform/filterKpiIndicatorValueChart',
                    data: postData,
                    dataType: 'json',
                    success: function(data) {
                        console.log(chartConfig);
                        if (data.status == 'success') {
                            if (typeof chartConfig.mainType !== 'undefined' && chartConfig.mainType === 'echart') {
                                var kpiChartObj = {
                                    elemId: chartId, 
                                    chartConfig: chartConfig, 
                                    data: data.data, 
                                    dataXaxis: data.dataXaxis, 
                                    columnsConfig: data.columnsConfig,
                                    useData: '3',
                                }
                                EchartBuilder.chartRender(kpiChartObj);
                            }  else {
                                kpiDataMartChartRender({
                                    isRunInterval: true, 
                                    elemId: chartId, 
                                    chartConfig: chartConfig, 
                                    data: data.data, 
                                    columnsConfig: data.columnsConfig
                                });
                            }
                        } else {
                            console.log(data);
                        }
                    }
                });
            });
        }
    }, 50);
}

filterKpiIndicatorValueChartList(<?php echo $this->uniqId; ?>, <?php echo $this->row['SRC_INDICATOR_ID'] ?>);

function filterKpiIndicatorValueChartList(uniqId, indicatorId) {
    
    var postData = {uniqId: uniqId, indicatorId: indicatorId, isChartList: 1};
    var $script = $('#'+uniqId+' div[data-src-indicatorid="'+indicatorId+'"] script[data-id]');
    var scriptJson = $script.text(), 
        jsonObj = JSON.parse(scriptJson);
    
    if (jsonObj.hasOwnProperty('chartFilterCriteria')) {
        postData.filterData = JSON.parse(html_entity_decode(jsonObj.chartFilterCriteria, 'ENT_QUOTES'));
    }
            
    $.ajax({
        type: 'post',
        url: 'mdform/filterKpiIndicatorValueForm',
        data: postData,
        dataType: 'json',
        success: function(data) {
            var $filterCol = $('#' + uniqId + ' .kpidv-data-filter-col');
            var $filterColAuto = $filterCol.closest('.col-md-auto');
            
            if (data.status == 'success' && data.html != '') {
                
                $filterCol.css('height', window['dynamicHeight_' + uniqId]);
                
                $filterCol.append(data.html).promise().done(function() {
                    Core.initNumberInput($filterCol);
                    Core.initLongInput($filterCol);
                    Core.initDateInput($filterCol);
                });
                
                $filterColAuto.removeClass('d-none');
            } else {
                $filterColAuto.remove();
            }
        }
    });
}
function filterKpiIndicatorValueChartListLoad(elem) {
    var $this = $(elem), 
        $parentFilter = $this.closest('.list-group'), 
        uniqId = $parentFilter.attr('data-uniqid');
    
    if ($this.hasClass('active')) {
        $this.removeClass('active');
        $this.find('i').removeClass('fas fa-check-square').addClass('far fa-square');
    } else {
        $this.addClass('active');
        $this.find('i').removeClass('far fa-square').addClass('fas fa-check-square');
    }
    
    var getFilterData = getKpiIndicatorFilterData(elem, $parentFilter);
    var indicatorId = getFilterData.indicatorId;
    var filterData = getFilterData.filterData;
    
    mvFilterRelationLoadData(elem, indicatorId, filterData);
    
    window['kpiChartLoad_' + uniqId](elem);
}
</script>    