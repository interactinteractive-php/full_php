<?php
$bgImage = null;

if ($this->bgImage && file_exists($this->bgImage)) {
    $bgImage = $this->bgImage;
}
?>
<style type="text/css">
.kl-layout {
    margin: -15px -15px -2px -15px;
    padding: 15px;
    background-color: #ebebeb;
    min-height: calc(100vh - 85px);
}    
.workspace-main-container .workspace-part .kl-layout {
    margin: 0;
}
#kl-layout-<?php echo $this->uniqId; ?> {
    background-image: url("<?php echo $bgImage; ?>"); 
    background-repeat: no-repeat; 
    background-position: top center;
    background-attachment: fixed;
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
.kl-layout-filter {
    position: fixed;
    right: 0;
    z-index: 97;
    width: 280px;
    border: 1px #eee solid;
    background-color: #fff;
    box-shadow: 0 0.5mm 2mm rgb(0 0 0 / 30%);
    padding: 10px;
    border-radius: 6px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.kl-layout-filter .kpi-dashboard-collapse-btn {
    position: absolute;
    top: 0;
    left: -31px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.kl-layout-filter .kl-layout-filter-body .list-group-item {
    padding: 0.28rem 2px;
}
.kl-layout-filter .kl-layout-filter-body .list-group {
    border: none;
    padding: 0;
    overflow: auto;
    overflow-x: hidden;
}
.kl-layout-filter.kl-layout-filter-closed {
    width: 0;
    border: none;
    box-shadow: none;
    padding: 0;
}
</style>

<div class="kl-layout" id="kl-layout-<?php echo $this->uniqId; ?>">
    
    <?php
    if (isset($this->filterForm) && $this->filterForm) {
        $isFilterForm = true;
    ?>
        <div class="kl-layout-filter kl-layout-filter-closed">
            <button type="button" class="btn btn-light bg-gray bg-grey-c0 border-0 p-1 pl-2 pr-2 text-white kpi-dashboard-collapse-btn">
                <i class="far fa-arrow-alt-to-left"></i>
            </button>

            <div class="kl-layout-filter-body">
                <?php echo $this->filterForm; ?>
            </div>

            <div class="kl-layout-filter-footer filter-right-btn">
                <button type="button" class="btn btn-circle blue-madison btn-block kpi-dashboard-filter-btn">
                    <i class="far fa-search"></i> <?php echo $this->lang->line('do_filter'); ?>
                </button>
            </div>
        </div>
    <?php 
    }
    
    echo $this->layout; 
    ?>
</div>

<script type="text/javascript">
var kpiDashboardRefreshTimer = 60 * 1000;
if (typeof isKpiIndicatorEchartsScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/echartsBuilder.js'); ?>').done(function() {      
        if (typeof isKpiIndicatorScript === 'undefined') {
            $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>').done(function() {
                kpiDashboardLoad_<?php echo $this->uniqId; ?>();
            });
        } else {
            kpiDashboardLoad_<?php echo $this->uniqId; ?>();
        }
    });
} else {
    if (typeof isKpiIndicatorScript === 'undefined') {
        $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>').done(function() {
            kpiDashboardLoad_<?php echo $this->uniqId; ?>();
        });
    } else {
        kpiDashboardLoad_<?php echo $this->uniqId; ?>();
    }
}


function kpiDashboardLoad_<?php echo $this->uniqId; ?>() {

    setTimeout(function() {
        var $parent = $('#kl-layout-<?php echo $this->uniqId; ?>'); 

        if ($parent.is(':visible')) {
            var $sections = $parent.find('[data-kpis-indicatorid]');
            var $dashboardFilter = $parent.find('.kl-layout-filter').find('.kl-layout-filter-body');
            var dashboardFilter = {};
            
            if ($dashboardFilter.length) {
                var getDashboardFilterData = getKpiIndicatorFilterData('', $dashboardFilter);
                if (Object.keys(getDashboardFilterData.filterData).length) {
                    dashboardFilter = getDashboardFilterData.filterData;
                }
            }
                
            $sections.each(function() {

                var $this = $(this), 
                    indicatorId = $this.attr('data-src-indicatorid'), 
                    $script = $this.find('script[data-id]'), 
                    chartId = $script.attr('data-id'), 
                    scriptJson = $script.text();
                    
                if (scriptJson != '') {
                    
                    var jsonObj = JSON.parse(scriptJson), chartConfig = jsonObj.chartConfig;
                
                    var postData = {
                        indicatorId: indicatorId, 
                        chartConfig: chartConfig
                    };

                    if (jsonObj.hasOwnProperty('chartFilterCriteria')) {

                        var loopFilterData = JSON.parse(html_entity_decode(jsonObj.chartFilterCriteria, 'ENT_QUOTES'));

                        if (Object.keys(loopFilterData).length > 0) {
                            postData.filterData = loopFilterData;
                        }
                    }
                    
                    <?php if (issetParam($this->isBuild) === '1') {   ?>
                        postData = {...postData, isBuild: '1'};
                    <?php } ?>
                    if (Object.keys(dashboardFilter).length) {
                        postData.dashboardFilter = {
                            indicatorId: '<?php echo $this->indicatorId; ?>', 
                            filterData: dashboardFilter
                        };
                    }

                    $.ajax({
                        type: 'post',
                        url: 'mdform/filterKpiIndicatorValueChart',
                        data: postData,
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 'success') {
                                if (typeof chartConfig.chartMainType !== 'undefined' && chartConfig.chartMainType === 'echart') {
                                    var columnsConfig = chartConfig;
                                    $('#' + chartId).closest('.card-body').css('background', chartConfig['backgroundColor']);

                                    kpiChartObj_<?php echo $this->uniqId; ?> = {
                                        elemId: chartId, 
                                        chartConfig: chartConfig, 
                                        data: data.data, 
                                        dataXaxis: data.dataXaxis, 
                                        columnsConfig: data.columnsConfig,
                                        useData: '3',
                                    }
                                    EchartBuilder.chartRender(kpiChartObj_<?php echo $this->uniqId; ?>);
                                    
                                } else {
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
                }    
            });
        }
    }, 50);
}

<?php
if (isset($isFilterForm)) {
?>
$(function() {
    
    var $kpiDashboardFilter_<?php echo $this->uniqId; ?> = $('#kl-layout-<?php echo $this->uniqId; ?>');
    
    Core.initNumberInput($kpiDashboardFilter_<?php echo $this->uniqId; ?>);
    Core.initLongInput($kpiDashboardFilter_<?php echo $this->uniqId; ?>);
    Core.initDateInput($kpiDashboardFilter_<?php echo $this->uniqId; ?>);
                    
    $kpiDashboardFilter_<?php echo $this->uniqId; ?>.on('click', 'button.kpi-dashboard-collapse-btn', function() {
        
        var $this = $(this), $parent = $this.closest('.kl-layout-filter');
        
        if ($parent.hasClass('kl-layout-filter-closed')) {
            $this.find('i').removeClass('fa-arrow-alt-to-left').addClass('fa-arrow-alt-to-right');
            $parent.removeClass('kl-layout-filter-closed');
        } else {
            $this.find('i').removeClass('fa-arrow-alt-to-right').addClass('fa-arrow-alt-to-left');
            $parent.addClass('kl-layout-filter-closed');
        }
    });
    
    $kpiDashboardFilter_<?php echo $this->uniqId; ?>.on('click', 'button.kpi-dashboard-filter-btn', function() {
        
        var $this = $(this), 
            $collapseBtn = $this.closest('.kl-layout-filter').find('.kpi-dashboard-collapse-btn');
        
        $collapseBtn.click();
        
        kpiDashboardLoad_<?php echo $this->uniqId; ?>();
    });
    
    $kpiDashboardFilter_<?php echo $this->uniqId; ?>.find('.kl-layout-filter-body .list-group').css({'height': $(window).height() - 250});
});
<?php
}
?>
</script>    