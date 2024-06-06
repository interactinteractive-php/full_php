<style type="text/css">
.kl-layout-filter {
    position: fixed;
    right: 0;
    z-index: 98;
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
                <div class="row">
                <?php 
                if (Mdform::$isRawDataMart && !Config::getFromCache('IS_IGNORE_MV_GENERATE_DATAMART')) {
                    
                    echo '<div class="col pr0">'
                            .Form::button(array(
                                'class' => 'btn btn-circle purple-plum btn-block kpi-datamart-generate-btn', 
                                'value' => '<i class="far fa-cogs"></i> '.$this->lang->line('MET_99990770')
                            ))
                        .'</div>';
                }
                
                echo '<div class="col">'
                        .Form::button(array(
                            'class' => 'btn btn-circle blue-madison btn-block kpi-dashboard-filter-btn', 
                            'value' => '<i class="far fa-search"></i> '.$this->lang->line('do_filter')
                        ))
                    .'</div>';
                ?>
                </div>
            </div>
        </div>
    <?php 
    } elseif (Mdstatement::$isAutoSearch) {
        
        echo Form::button(array('class' => 'd-none kpi-dashboard-filter-btn', 'value' => $this->lang->line('do_filter')));
    }
    ?>
    
    <div class="row viewer-container">
        <?php echo $this->reportViewer; ?>
    </div>
</div>

<script type="text/javascript">
if (typeof isKpiIndicatorScript === 'undefined') {    
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>');
}

<?php
if (isset($isFilterForm) || Mdstatement::$isAutoSearch) {
?>
$(function() {
    
    var $kpiDashboardFilter_<?php echo $this->uniqId; ?> = $('#kl-layout-<?php echo $this->uniqId; ?>');
    
    Core.initNumberInput($kpiDashboardFilter_<?php echo $this->uniqId; ?>);
    Core.initLongInput($kpiDashboardFilter_<?php echo $this->uniqId; ?>);
    Core.initDateInput($kpiDashboardFilter_<?php echo $this->uniqId; ?>);
    Core.initSelect2($kpiDashboardFilter_<?php echo $this->uniqId; ?>);
                    
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
    
    $kpiDashboardFilter_<?php echo $this->uniqId; ?>.on('click', 'button.kpi-dashboard-filter-btn', function(e, isTriggered) {
        
        var $this = $(this); 

        if (typeof isTriggered === 'undefined') {
            var $collapseBtn = $this.closest('.kl-layout-filter').find('.kpi-dashboard-collapse-btn');
            $collapseBtn.click();
        }
        
        setTimeout(function() {
            var $parent = $('#kl-layout-<?php echo $this->uniqId; ?>'); 

            if ($parent.is(':visible')) {
                var $dashboardFilter = $parent.find('.kl-layout-filter').find('.kl-layout-filter-body');
                var $form = $('#dataview-search-form', dataview_statement_search_<?php echo $this->mainIndicatorId.$this->indicatorId; ?>);
                var statementFilterString = '';
                
                if ($dashboardFilter.length) {
                    
                    var getDashboardFilterData = getKpiIndicatorFilterData('', $dashboardFilter);
                    
                    if (Object.keys(getDashboardFilterData.filterData).length) {
                        var filterData = {filterData: getDashboardFilterData.filterData};
                        statementFilterString = $.param(filterData);
                    }
                    
                    if (Object.keys(getDashboardFilterData.groupingColumn).length) {
                        
                        var groupingColumn = {tempGroupingColumn: getDashboardFilterData.groupingColumn};
                        
                        if (statementFilterString != '') {
                            statementFilterString += '&'+$.param(groupingColumn);
                        } else {
                            statementFilterString = $.param(groupingColumn);
                        }
                    }
                }

                $.ajax({
                    type: 'post',
                    url: 'mdstatement/renderDataModelByFilter',
                    data: $form.serialize()+'&isKpiIndicator=1&'+statementFilterString<?php if(isset($this->pageProperties)){ ?>+'&pageProperties=<?php echo Json::encode($this->pageProperties); ?>'<?php } ?>,
                    dataType: 'json', 
                    beforeSend: function () {
                        Core.blockUI({message: 'Тайлан бэлдэж байна...', boxed: true});
                    },
                    success: function (data) {
                        PNotify.removeAll();

                        if (data.status == 'success') {

                            var $statementContent = $('#statement-form-<?php echo $this->mainIndicatorId; ?>').find('div.report-preview-print')[0];
                            $statementContent.innerHTML = data.htmlData;
                            
                            statementStyleResolver_<?php echo $this->mainIndicatorId; ?>(data.childCount, data.freezeNumberOfColumn);
                            
                        } else {
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false, 
                                hide: true,  
                                delay: 1000000000
                            });
                        }
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                        Core.unblockUI();
                    }
                }).done(function () {
                    $('.removeColGroup').find('colgroup').remove();
                });
            }
        }, 50);
    });
    
    <?php
    if (Mdform::$isRawDataMart) {
    ?>
    $kpiDashboardFilter_<?php echo $this->uniqId; ?>.on('click', 'button.kpi-datamart-generate-btn', function() {
        PNotify.removeAll();
    
        $.ajax({
            type: 'post',
            url: 'mdform/generateKpiDataMartFromStatement',
            data: {
                mainIndicatorId: '<?php echo $this->mainIndicatorId; ?>', 
                dataIndicatorId: '<?php echo $this->indicatorId; ?>', 
                isThroughCalculate: 1
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Бодолт хийгдэж байна...', boxed: true});
            },
            success: function(data) {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
                Core.unblockUI();
            }
        });
    });
    <?php
    }
    ?>
    
    $kpiDashboardFilter_<?php echo $this->uniqId; ?>.find('.kl-layout-filter-body .list-group').css({'height': $(window).height() - 250});
    
    <?php
    if (Mdstatement::$isAutoSearch) {
    ?>
    setTimeout(function() {
       $kpiDashboardFilter_<?php echo $this->uniqId; ?>.find('button.kpi-dashboard-filter-btn').trigger('click', [true]); 
    }, 100);
    <?php
    }
    ?>
});
<?php
}
?>
</script>