<?php
if ($this->isAjax == false) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li>
                    <a href="#app_tab_<?php echo $this->uniqId; ?>" class="active" data-toggle="tab">
                        <i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span>
                    </a>
                </li>
            </ul>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_<?php echo $this->uniqId; ?>">
                    <?php echo $this->renderChart; ?> 
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
    echo $this->renderChart;    
}
?>

<script type="text/javascript">
var kpiDMChart_<?php echo $this->uniqId; ?> = $('#kpi-datamart-chart-<?php echo $this->uniqId; ?>');
var kpiChartObj_<?php echo $this->uniqId; ?> = {};
var kpiChartOption_<?php echo $this->uniqId; ?> = {};

var dynamicHeight = $(window).height() - kpiDMChart_<?php echo $this->uniqId; ?>.offset().top - 20;

if (dynamicHeight < 230) {
    dynamicHeight = 350;
}

if (kpiDMChart_<?php echo $this->uniqId; ?>.closest('.package-tab').length) {
    dynamicHeight = 'auto';
}

if (typeof isKpiIndicatorScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>');
}

if (typeof isKpiIndicatorEchartsScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/echartsBuilder.js'); ?>').done(function() {
        
    });
}

filterKpiIndicatorValueChart(<?php echo $this->uniqId; ?>, <?php echo $this->indicatorId; ?>);

function filterKpiIndicatorValueChart(uniqId, indicatorId) {
    $.ajax({
        type: 'post',
        url: 'mdform/filterKpiIndicatorValueForm',
        data: {uniqId: uniqId, indicatorId: indicatorId},
        dataType: 'json',
        success: function(data) {
            var $filterCol = $('#kpi-datamart-chart-' + uniqId + ' .kpidv-data-filter-col');
            
            if (data.status == 'success' && data.html != '') {
                $filterCol.css('height', dynamicHeight - 20);
                $filterCol.css('minHeight', '100%');
                $filterCol.append(data.html).promise().done(function() {
                    Core.initNumberInput($filterCol);
                    Core.initLongInput($filterCol);
                    Core.initDateInput($filterCol);
                    
                    <?php
                    if (isset($this->graphJsonConfig['chartFilterCriteria']) && $this->graphJsonConfig['chartFilterCriteria']) {
                    ?>
                    var chartFilterCriteria = <?php echo html_entity_decode($this->graphJsonConfig['chartFilterCriteria'], ENT_QUOTES, 'UTF-8'); ?>;
                    if (chartFilterCriteria) {
                        for (var f in chartFilterCriteria) {
                            var $prevFilteredElem = $filterCol.find('[data-filter-type="checkbox"][data-filter-column="'+f+'"]');
                            if ($prevFilteredElem.length) {
                                var filterValues = chartFilterCriteria[f];
                                for (var v in filterValues) {
                                    var $detectText = $prevFilteredElem.find('span[data-value-mode]').filter(function(){ return ($(this).text() == filterValues[v]); });
                                    if ($detectText.length) {
                                        var $activeItem = $detectText.closest('.list-group-item');

                                        $activeItem.addClass('active');
                                        $activeItem.find('i').removeClass('far fa-square').addClass('fas fa-check-square');
                                    }
                                }
                            }
                        }
                    }
                    <?php
                    }
                    ?>
                });
            } else {
                $filterCol.closest('.col-md-auto').remove();
            }
        }
    });
}

function filterKpiIndicatorValueChartLoad(elem) {
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
    
    window['kpiDataMartLoadChart_' + uniqId](true);
}

function kpiDataMartLoadChart_<?php echo $this->uniqId; ?>(isClick) {
    
    var _chartType = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartType"]');
        kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartType"]').val(_chartType.find('option[value="'+ _chartType.val() +'"]').attr('data-value'));
        
    var chartType = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartType"]').val(),
        chartMainType = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartMainType"]').val(),
        chartCategory = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartCategory"]').val(), 
        chartCategoryGroup = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartCategoryGroup"]').val(), 
        chartValue = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartValue"]').val(), 
        chartAggregate = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartAggregate"]').val();
    
    if (isArray(chartValue)) {
        chartValue = chartValue.join(',');
    }
    
    if (chartType != '' 
        && (
            ((chartType != 'card' && chartType != 'card_vertical') && chartCategory != '') || (chartType == 'card' || chartType == 'card_vertical')
        ) 
        && (chartValue != '' || (chartValue == '' && chartAggregate == 'COUNT') || (chartType === 'tree'|| chartType === 'tree_circle')) 
        ) {
            
        if (chartType == 'stacked_column' && chartCategoryGroup == '') {
            return false;
        }
        
        var chartValueSortType = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartValueSortType"]').val(), 
            chartRowNum = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartRowNum"]').val(),
            chartLabelText = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartLabelText"]').val(),
            chartBgColor = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartBgColor"]').val(), 
            chartIconName = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartIconName"]').val(), 
            chartLineColumn = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartLineChartColumn"]').val(), 
            chartLineAggregate = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartLineChartAggregate"]').val();
        
        kpiDMChart_<?php echo $this->uniqId; ?>.find('option[data-type]').hide();
        kpiDMChart_<?php echo $this->uniqId; ?>.find('option[data-type="'+  chartMainType +'"]').show();

        kpiDMChart_<?php echo $this->uniqId; ?>.find('div.echart').hide();
        if (chartMainType === 'echart') {
            kpiDMChart_<?php echo $this->uniqId; ?>.find('div.echart').show();
            kpiDMChart_<?php echo $this->uniqId; ?>.find('.chartTypesConfigration, .chartTypesConfigration .configration').hide();
            
            if (typeof _chartType.find('option[value="'+ _chartType.val() +'"]').attr('data-config') !== 'undefined') {
                var _dataConfigStr = _chartType.find('option[value="'+ _chartType.val() +'"]').attr('data-config');

                if (_dataConfigStr && typeof window['chartConfigrationToggle'] !== 'undefined') {
                    EchartBuilder.chartConfigrationToggle(kpiDMChart_<?php echo $this->uniqId; ?>, _dataConfigStr);
                }
            }
        }
        
        var chartConfig = {
            mainType: chartMainType, 
            type: chartType, 
            axisX: chartCategory, 
            axisXGroup: chartCategoryGroup, 
            axisY: chartValue, 
            aggregate: chartAggregate, 
            axisYSortType: chartValueSortType, 
            rowNum: chartRowNum, 
            labelText: chartLabelText, 
            bgColor: chartBgColor, 
            iconName: chartIconName
        };    

        if (chartType !== 'card' && chartType != 'card_vertical') {
            var themeGroup = JSON.parse(kpiDMChart_<?php echo $this->uniqId; ?>.find('.theme-plan-group.selected').attr('data-rowdata'));
            chartConfig['bgColor'] = themeGroup['bgColor'];
            chartConfig['color'] = themeGroup['themeColor'];
        }

        kpiDMChart_<?php echo $this->uniqId; ?>.find('.type-config<?php echo $this->uniqId ?> input, .type-config<?php echo $this->uniqId ?> select').each(function (iConfig, rConfig) {
            var $r = $(rConfig);
            if ($r && typeof $r.attr('data-path') !== 'undefined') {
                var _code = $r.attr('data-path').replaceAll('.', '_');
                delete kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code];
                if ($r.attr('type') === 'checkbox') {
                    kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code] = $r.is(':checked');
                    chartConfig[_code] = $r.is(':checked');
                } else {
                    if ($r.val()) {
                        chartConfig[_code] = $r.val();
                        kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code] = $r.val();
                    }
                }
            }
        });
        
        if (chartLineColumn != '' && chartLineAggregate != '') {
            chartConfig.lineChartConfig = {column: chartLineColumn, aggregate: chartLineAggregate};
        }
        
        if (chartType == 'maps') {
            var chartMapCountry = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartMapCountry"]').val();
            if (chartMapCountry != '') {
                chartConfig.mapsChartConfig = {country: chartMapCountry};
            } else {
                return false;
            }
        }
        
        if (typeof isClick !== 'undefined' && isClick) {
            var filterData = getChartFilterData_<?php echo $this->uniqId; ?>();   
        } else {
            var filterData = {};
            <?php
            if (isset($this->graphJsonConfig['chartFilterCriteria']) && $this->graphJsonConfig['chartFilterCriteria']) {
            ?>
            var chartFilterCriteria = <?php echo html_entity_decode($this->graphJsonConfig['chartFilterCriteria'], ENT_QUOTES, 'UTF-8'); ?>;
            if (chartFilterCriteria) {
                for (var f in chartFilterCriteria) {
                    filterData[f] = chartFilterCriteria[f];
                }
            }
            <?php
            }
            ?>
        }
        
        if (!filterData.length) {
            filterData = getChartFilterData_<?php echo $this->uniqId; ?>();   
        }
        
        $.ajax({
            type: 'post',
            url: 'mdform/filterKpiIndicatorValueChart',
            data: {
                indicatorId: '<?php echo $this->indicatorId; ?>', 
                chartConfig: chartConfig, 
                filterData: filterData,
                isBuild: '1'
            },
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    
                    $('#kpi-datamart-chart-render-<?php echo $this->uniqId; ?>').empty().append('').promise().done(function () {

                        if (chartMainType === 'echart') {
                            kpiChartObj_<?php echo $this->uniqId; ?> = {
                                elemId: 'kpi-datamart-chart-render-<?php echo $this->uniqId; ?>', 
                                chartConfig: chartConfig, 
                                data: data.data, 
                                dataXaxis: data.dataXaxis, 
                                columnsConfig: data.columnsConfig,
                                useData: '1',
                            }
                            EchartBuilder.chartRender(kpiChartObj_<?php echo $this->uniqId; ?>);
    
                        } else {
                            kpiDataMartChartRender({
                                elemId: 'kpi-datamart-chart-render-<?php echo $this->uniqId; ?>', 
                                chartConfig: chartConfig, 
                                data: data.data, 
                                columnsConfig: data.columnsConfig
                            });
                        }
                    });

                } else {
                    console.log(data);
                }
            }
        });
    }
}

function getChartFilterData_<?php echo $this->uniqId; ?>() {
    var $col = $('#kpi-datamart-chart-<?php echo $this->uniqId; ?>').find('.kpidv-data-filter-col');
    
    var getFilterData = getKpiIndicatorFilterData('', $col);
    var filterData = getFilterData.filterData;
    
    return filterData;
}

$(function() {
    
    $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
        
        kpiDMChart_<?php echo $this->uniqId; ?>.find('button[role="iconpicker"]').iconpicker({
            arrowPrevIconClass: 'fa fa-arrow-left',
            arrowNextIconClass: 'fa fa-arrow-right'
        });
        
        kpiDMChart_<?php echo $this->uniqId; ?>.on('change', '#menu-iconpicker', function(e){ 
            if (e.icon === 'empty' || e.icon === 'fa-empty') {
                $("input[name='kpiDMChartIconName']").val('').trigger('change');
            } else {
                $("input[name='kpiDMChartIconName']").val(e.icon).trigger('change');
            }
        });
    });

    /* 
    1. select[name="kpiDMChartType"]
    2. select[name="kpiDMChartCategory"]
    3. select[name="kpiDMChartValue"]
    4. select[name="kpiDMChartCategoryGroup"] 
    5. select[name="kpiDMChartAggregate"]
    6. select[name="kpiDMChartValueSortType"]
    7. input[name="kpiDMChartRowNum"]
    8. select[name="kpiDMChartLineChartColumn"]
    9. select[name="kpiDMChartLineChartAggregate"]
    10.select[name="kpiDMChartBgColor"]
    11.select[name="kpiDMChartMapCountry"] 
    12.input[name="kpiDMChartLabelText"]
    13.input[name="kpiDMChartIconName"]
    */
   
    kpiDMChart_<?php echo $this->uniqId; ?>.on('change', '.chartRootConfigrations select, .chartRootConfigrations input, .chartRootConfigrations textarea', function() {
        
        var $this = $(this), name = $this.attr('name'),
            parentSelector = $this.closest('.chartRootConfigrations');
        
        var _chartType = parentSelector.find('select[name="kpiDMChartType"]');
        var $chartValue = parentSelector.find('select[name="kpiDMChartValue"]');

        var $chartType = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartType"]');
        var $chartMainType = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartMainType"]');
        
        kpiDMChart_<?php echo $this->uniqId; ?>.find('div.echart').hide();
        kpiDMChart_<?php echo $this->uniqId; ?>.find('option[data-type]').hide();
        kpiDMChart_<?php echo $this->uniqId; ?>.find('option[data-type="'+  $chartMainType.val() +'"]').show();

        $chartType.val(_chartType.find('option[value="'+ _chartType.val() +'"]').attr('data-value'));
        $chartMainType.val('amchart');

        var _chartMainType = _chartType.find('option[value="'+ _chartType.val() +'"]').attr('data-maintype');
        if (_chartMainType) {
            $chartMainType.val(_chartMainType);
        }
        
        if (name == 'kpiDMChartType') {
            
            var chartTypeVal = $chartType.val();
            
            var $kpiDMChartCategoryGroupRow = $('.kpiDMChartCategoryGroup-row');
            var $kpiDMChartCategoryRow = $('.kpiDMChartCategory-row');
            var $kpiDMChartLabelTextRow = $('.kpiDMChartLabelText-row');
            var $kpiDMChartBgColorRow = $('.kpiDMChartBgColor-row');
            var $kpiDMChartIconNameRow = $('.kpiDMChartIconName-row');
            var $kpiDMChartLineChartColumnRow = $('.kpiDMChartLineChartColumn-row');
            var $kpiDMChartLineChartAggregateRow = $('.kpiDMChartLineChartAggregate-row');
            var $kpiDMChartMapCountryRow = $('.kpiDMChartMapCountry-row');
            
            $chartValue.find('option[value=""]').remove();
            
            $kpiDMChartCategoryGroupRow.addClass('d-none');
            $kpiDMChartCategoryRow.removeClass('d-none');
            
            $kpiDMChartLabelTextRow.addClass('d-none');
            $kpiDMChartBgColorRow.addClass('d-none');
            $kpiDMChartIconNameRow.addClass('d-none');
            $kpiDMChartLineChartColumnRow.addClass('d-none');
            $kpiDMChartLineChartAggregateRow.addClass('d-none');
            $kpiDMChartMapCountryRow.addClass('d-none');
            
            switch (chartTypeVal) {
                case 'clustered_column':
                    if (!$chartValue.hasAttr('multiple')) {
                        $chartValue.prop('multiple', true);
                    }
                    $kpiDMChartLineChartColumnRow.removeClass('d-none');
                    $kpiDMChartLineChartAggregateRow.removeClass('d-none');
                    break;
                case 'column':
                    $kpiDMChartLineChartColumnRow.removeClass('d-none');
                    $kpiDMChartLineChartAggregateRow.removeClass('d-none');
                    break;
                case 'stacked_column':
                case 'bar_stacked':
                case 'stacked_bar':
                case 'line_race':
                case 'bar_label_rotation':
                case 'line_stacked':
                case 'line_bar':

                    $kpiDMChartCategoryGroupRow.removeClass('d-none');
                
                    $chartValue.prepend('<option value="">- '+plang.get('select_btn')+' -</option>');
                    $chartValue.prop('multiple', false);
                    break;
                case 'card':
                case 'card_vertical':

                    $kpiDMChartCategoryRow.addClass('d-none');
                    $kpiDMChartLabelTextRow.removeClass('d-none');
                    $kpiDMChartBgColorRow.removeClass('d-none');
                    $kpiDMChartIconNameRow.removeClass('d-none');
                
                    $chartValue.prepend('<option value="">- '+plang.get('select_btn')+' -</option>');
                    $chartValue.prop('multiple', false);
                    break;
                case 'maps':

                    $kpiDMChartMapCountryRow.removeClass('d-none');
                    break;
                
                default:
                    $chartValue.prepend('<option value="">- '+plang.get('select_btn')+' -</option>');
                    $chartValue.prop('multiple', false);
                    break;
            }

            try {
                $chartValue.select2('destroy');
            } catch (error) {
                console.log(error);                
            }

            Core.initSelect2(parentSelector);
            
            kpyInidicatorTypeData<?php echo $this->uniqId ?>($this);
        } else if (name == 'kpiDMChartValue') {
            
            var valueShowType = $chartValue.find('option:selected').attr('data-showtype');
            
            if (valueShowType == 'text') {
                $('select[name="kpiDMChartAggregate"]').val('COUNT');
            }
        }
        
        kpiDataMartLoadChart_<?php echo $this->uniqId; ?>();
    });
    
    kpiDMChart_<?php echo $this->uniqId; ?>.on('click', '.kpi-dm-chart-create', function() {
        
        var _chartType = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartType"]');
        kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartType"]').val(_chartType.find('option[value="'+ _chartType.val() +'"]').attr('data-value'));

        var chartType = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartType"]').val(),
            chartMainType = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartMainType"]').val(),
            chartAggregate = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartAggregate"]').val(),
            chartCategory = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartCategory"]').val(), 
            chartCategoryGroup = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartCategoryGroup"]').val(), 
            chartValue = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartValue"]').val(), 
            chartMapCountry = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartMapCountry"]').val(),
            $this = $(this);
        
        if (isArray(chartValue)) {
            chartValue = chartValue.join(',');
        }

        /* select[name="kpiDMChartType"]
        select[name="kpiDMChartAggregate"]
        select[name="kpiDMChartCategory"]
        select[name="kpiDMChartValue"]
        select[name="kpiDMChartCategoryGroup"]
        select[name="kpiDMChartMapCountry"] */

        kpiDMChart_<?php echo $this->uniqId; ?>.find('.chartRootConfigrations select').removeClass('error');
        console.log(chartType);
        if (chartType == '' || ((chartType != 'card' && chartType != 'card_vertical') && chartCategory == '') || (chartValue == '' && chartAggregate != 'COUNT' && chartType != 'tree' && chartType != 'tree_circle') || chartAggregate == '') {
            
            if (chartType == '') {
                kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartType"]').addClass('error');
            }
            
            if ((chartType != 'card' && chartType != 'card_vertical') && chartCategory == '') {
                kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartCategory"]').addClass('error');
            }
            
            if (chartValue == '') {
                kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartValue"]').addClass('error');
            }
            
            if (chartAggregate == '' && chartAggregate != 'COUNT') {
                kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartAggregate"]').addClass('error');
            }
            
            return false;
        }
        
        if (chartType == 'stacked_column' && chartCategoryGroup == '') {
            kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartCategoryGroup"]').addClass('error');
            return false;
        }
        
        if (chartType == 'maps' && chartMapCountry == '') {
            kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartMapCountry"]').addClass('error');
            return false;
        }

        var html = '<form>'+
            '<div class="col-md-12 xs-form">'+ 
                '<div class="form-group row">'+
                    '<label class="col-form-label col-md-4 text-right pr0" for="dmart_label_name"><span class="required">*</span>Чартын нэр:</label>'+
                    '<div class="col-md-8">'+
                        '<input type="text" name="chartTitle" class="form-control form-control-sm mt-0" placeholder="Чартын нэр" required="required" value="<?php echo issetParam($this->chartName); ?>"/>'+
                    '</div>'+
                '</div>'+
                '<div class="form-group row">'+
                    '<label class="col-form-label col-md-4 text-right pr0" for="dmart_iscreate_widget">Widget үүсгэх эсэх:</label>'+
                    '<div class="col-md-8">'+
                        '<input type="checkbox" class="booleanInit" name="dmart_iscreate_widget" value="1" saved_val="" id="dmart_iscreate_widget">'+
                    '</div>'+
                '</div>'+
                '<div class="form-group row">'+
                    '<label class="col-form-label col-md-4 text-right pr0" for="dmart_widget_file">Widget зураг:</label>'+
                    '<div class="col-md-8">'+
                        '<input type="file" id="dmart_widget_file" name="dmart_widget_file" value=""/>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</form>';
        
        var $dialogName = 'dialog-kpidmart-createchart';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialog = $('#' + $dialogName);
    
        $dialog.html(html);
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: '<?php echo isset($this->chartIndicatorId) ? 'Чарт засах' : 'Чарт үүсгэх'; ?>',
            width: 600,
            height: 'auto',
            modal: true,
            close: function () {
                $dialog.dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                    PNotify.removeAll();
                    
                    var $form = $dialog.find('form');
                    $form.validate({errorPlacement: function () {}});

                    if ($form.valid()) {
                        
                        $form.ajaxSubmit({
                            type: 'post',
                            url: 'mdform/createKpiDmChart',
                            dataType: 'json',
                            beforeSubmit: function(formData, jqForm, options) {
                                
                                var chartValueSortType = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartValueSortType"]').val(), 
                                    chartRowNum = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartRowNum"]').val(), 
                                    chartLabelText = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartLabelText"]').val(),
                                    chartBgColor = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartBgColor"]').val(), 
                                    chartIconName = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartIconName"]').val();
                                
                                formData.push(
                                    {name: 'indicatorId', value: '<?php echo $this->indicatorId; ?>'}, 
                                    {name: 'chartType', value: chartType}, 
                                    {name: 'chartMainType', value: chartMainType}, 
                                    {name: 'chartCategory', value: chartCategory}, 
                                    {name: 'chartCategoryGroup', value: chartCategoryGroup}, 
                                    {name: 'chartValue', value: chartValue}, 
                                    {name: 'chartAggregate', value: chartAggregate}, 
                                    {name: 'chartValueSortType', value: chartValueSortType}, 
                                    {name: 'chartRowNum', value: chartRowNum}, 
                                    {name: 'chartLabelText', value: chartLabelText}, 
                                    {name: 'chartBgColor', value: chartBgColor}, 
                                    {name: 'chartIconName', value: chartIconName}, 
                                    {name: 'chartMapCountry', value: chartMapCountry},
                                );
                                formData.push({name: 'buildCharConfig', value: $this.attr('data-config')});
                                kpiDMChart_<?php echo $this->uniqId; ?>.find('.chartTypesConfigration input, .chartTypesConfigration select').each(function (i, r) {
                                    
                                    var _selectedAttr= $(r), _tagName = _selectedAttr.prop('tagName').toLowerCase();
                                    if (_selectedAttr.attr('type') === 'checkbox') {
                                        if (_selectedAttr.is(':checked')) {
                                            formData.push({name: _selectedAttr.attr('name'), value: '1'});
                                        }
                                    } else {
                                        formData.push({name: _selectedAttr.attr('name'), value: _selectedAttr.val()});
                                    }
                                    
                                });
                                

                                if (chartType == 'clustered_column' || chartType == 'column') {
                                    
                                    var chartLineColumn = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartLineChartColumn"]').val(), 
                                        chartLineAggregate = kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartLineChartAggregate"]').val();
                            
                                    if (chartLineColumn != '' && chartLineAggregate != '') {
                                        formData.push({name: 'chartLineColumn', value: chartLineColumn});
                                        formData.push({name: 'chartLineAggregate', value: chartLineAggregate});
                                    }
                                }
                        
                                var isFilterSave = kpiDMChart_<?php echo $this->uniqId; ?>.find('input[name="kpiDMChartIsFilterSave"]').is(':checked');
                                
                                if (isFilterSave) {
                                    var filterData = getChartFilterData_<?php echo $this->uniqId; ?>();   
                                    formData.push({name: 'chartFilterCriteria', value: JSON.stringify(filterData)});
                                }
                                
                                <?php
                                if (isset($this->chartIndicatorId)) {
                                ?>
                                    formData.push({name: 'chartIndicatorId', value: '<?php echo $this->chartIndicatorId; ?>'});
                                <?php
                                }
                                ?>
                            },
                            beforeSend: function () {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function (data) {

                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false, 
                                    hide: true,  
                                    addclass: pnotifyPosition
                                });

                                if (data.status == 'success') {
                                    $dialog.dialog('close');
                                } 

                                Core.unblockUI();
                            }
                        });
                    }
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() { 
                    $dialog.dialog('close'); 
                }}
            ]
        });
        $dialog.dialog('open');
    });
    
    kpiDMChart_<?php echo $this->uniqId; ?>.on('change', 'select[name="kpiDMChartBgColor"]', function() {
        var $this = $(this);
        
        if ($this.val() != '') {
            $this.css('background-color', $this.val());
        } else {
            $this.css('background-color', '');
        }
    });

    <?php if (issetParam($this->graphJsonConfig)) { ?>
        kpiDMChart_<?php echo $this->uniqId; ?>.find('select[name="kpiDMChartType"]').trigger('change');
    <?php } ?>
        // Core.initAjax($('#kpi-datamart-chart-<?php echo $this->uniqId; ?> > .booleanInit'));

});
    
    kpiDMChart_<?php echo $this->uniqId; ?>.on('click', '.theme-plan-group', function () {
        var $this = $(this),
            themeGroup = JSON.parse($this.attr('data-rowdata')),
            $form = $this.closest('form');
            $form.find('.theme-plan-group').removeClass('selected');
            $this.addClass('selected');
        
        kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig']['themeCode'] = themeGroup.code;
        kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig']['bgColor'] = themeGroup.bgColor;
        kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig']['color'] = themeGroup.themeColor;

        EchartBuilder.chartRender(kpiChartObj_<?php echo $this->uniqId; ?>);
    });
    
    kpiDMChart_<?php echo $this->uniqId; ?>.on('change', '.type-config<?php echo $this->uniqId ?> input, .type-config<?php echo $this->uniqId ?> select', function () {
        var $this = $(this),
            _tagName = $this.prop('tagName').toLowerCase();

        if (typeof kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'] ==='undefined') {
            kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'] = {};
        }
        
        $this.closest('.type-config<?php echo $this->uniqId ?>').find('input, select').each(function (i, r) {
            var $r = $(r);
            if ($r && typeof $r.attr('data-path') !== 'undefined') {
                var _code = $r.attr('data-path').replaceAll('.', '_');
                delete kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code];
                if ($r.attr('type') === 'checkbox') {
                    kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code] = $r.is(':checked');
                } else {
                    if ($r.val())
                        kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code] = $r.val();
                }
            }
        });

        EchartBuilder.chartRender(kpiChartObj_<?php echo $this->uniqId; ?>);
    });

    kpiDMChart_<?php echo $this->uniqId; ?>.find('.type-config<?php echo $this->uniqId ?> input, .type-config<?php echo $this->uniqId ?> select').trigger('change');
    

    function kpyInidicatorTypeData<?php echo $this->uniqId ?>(element) {
        var kpiTypeId = element.val(),
            parentSelector = element.closest('.chartRootConfigrations');
        if (!kpiTypeId) return;
        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: "mdform/paramConfigForm",
            data: {
                uniqId: '<?php echo $this->uniqId ?>', 
                kpiTypeId: kpiTypeId, 
                metaTypeCode: 'echart', 
            },
            beforeSend: function() {
                Core.blockUI();
            },
            success: function(response) {
                blockUniqId = kpiTypeId;
                var __html = '<div class="item-config" data-item-cf="' + blockUniqId +'" style="display: none">';
                    __html += '<input type="hidden" name="blockUniqId[]" value="'+ blockUniqId +'">';
                    /* __html += '<input type="hidden" name="widgetId['+ blockUniqId +']" data-path="widgetId" value="">'; */
                    if (response.data) {
                        __html += '<div class="card-group-control card-group-control-left">';
                            $.each(response.data, function (i, r) {
                                __html += '<div class="card rounded-top-0 p-0 mb-0 border-top-0 border-bottom-1 conf_'+ r.ID +'">';
                                    __html += '<div class="card-header h-auto m-0 px-1 py-2 bg-root-color">';
                                        __html += '<h6 class="card-title pull-left w-100">';
                                            __html += '<a class="text-white w-100 pull-left collapsed" data-toggle="collapse" href="#collapsible-'+ r.ID +'-group" aria-expanded="true">'+ r.LABEL_NAME +'</a>';
                                        __html += '</h6>';
                                    __html += '</div>';
                                    __html += '<div id="collapsible-'+ r.ID +'-group" class="collapse">';
                                        __html += '<div class="card-body">';
                                            __html += '<div class="col-md-12">';
                                                if (typeof r.children !== 'undefined' && r.children) {
                                                    __html += buildHtmlByIndicators<?php echo $this->uniqId ?>(r.children, '');
                                                } else {
                                                    switch (r.CODE) {
                                                        case 'sectionCol':
                                                            __html += '<div class="form-group row">';
                                                                __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ r.LABEL_NAME +' :</label>';
                                                                __html += '<div class="col-md-8">';
                                                                    __html += '<select class="form-control form-control-sm mt-0" name="sectionCol['+ blockUniqId +']" data-path="'+ r.CODE +'">';
                                                                        __html += '<option value="">- Сонгох -</option>';
                                                                        for (var i = 1; i <= 12; i++) {
                                                                            __html += '<option value="'+ i +'">col-'+ i +'</option>';
                                                                        }
                                                                    __html += '</select>';
                                                                __html += '</div>';
                                                            __html += '</div>';
                                                            break;
                                                        case 'series.data':
                                                        case 'json':
                                                            html += '<div class="form-group row">';
                                                                html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1" for="sectionCol">'+ r.LABEL_NAME +' :</label>';
                                                                html += '<div class="col-md-8">';
                                                                    html += '<div class="input-group">';
                                                                        html += '<textarea tabindex="" class="form-control form-control-sm mt-0 expression_editorInit" disabled="disabled" data-path="'+ r.CODE +' data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder=""></textarea>';
                                                                        html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpExpressionEditor(this);"><i class="far fa-code"></i></button></span> ';
                                                                    html += '</div>';
                                                                html += '</div>';
                                                            html += '</div>';
                                                            break;
                                                        case 'html':
                                                            __html += '<div class="form-group row">';
                                                                __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ r.LABEL_NAME +' :</label>';
                                                                __html += '<div class="col-md-8">';
                                                                    __html += '<div class="input-group">';
                                                                        __html += '<textarea tabindex="" class="form-control form-control-sm mt-0 " data-path="html" disabled="disabled" data-path="'+ r.CODE +' data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder=""></textarea>';
                                                                        __html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpFieldTextEditorClickToEdit(this, \'1\');"><i class="far fa-code"></i></button></span> ';
                                                                    __html += '</div>';
                                                                __html += '</div>';
                                                            __html += '</div>';
                                                            break;
                                                        default:
                                                            __html += '<div class="form-group row">';
                                                                __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ r.LABEL_NAME +' :</label>';
                                                                __html += '<div class="col-md-8">';
                                                                    __html += '<div class="input-group">';
                                                                        __html += '<input type="text" class="form-control form-control-sm mt-0" data-path="'+ r.CODE +'" />';
                                                                        __html += '<span class="input-group-append">';
                                                                            __html += '<button class="btn grey-cascade" type="button" onclick="clearKpiTypeAttr<?php echo $this->uniqId ?>(this);"><i class="far fa-trash"></i></button>';
                                                                        __html += '</span> ';
                                                                    __html += '</div>';
                                                                __html += '</div>';
                                                            __html += '</div>';
                                                            break;
                                                    }
                                                }
                                            __html += '</div>';
                                        __html += '</div>';
                                    __html += '</div>';
                                __html += '</div>';
                            });
                            __html += '<div class="card-group-control card-group-control-left">';
                                __html += '<div class="card rounded-top-0 p-0 mb-0 border-top-0 border-bottom-1 conf_'+ blockUniqId +'">';
                                    __html += '<div class="card-header h-auto m-0 px-1 py-2 bg-root-color">';
                                        __html += '<h6 class="card-title pull-left w-100">';
                                            __html += '<a class="text-white w-100 pull-left" data-toggle="collapse" href="#collapsible-'+ blockUniqId +'-group" aria-expanded="true">'+ plang.get('other_config_collapse') +'</a>';
                                        __html += '</h6>';
                                    __html += '</div>';
                                    __html += '<div id="collapsible-'+ blockUniqId +'-group" class="collapse show">';
                                        __html += '<div class="col-md-12">';
                                            __html += '<div class="form-group row -item-addinconfig mt-2">';
                                                __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1" for="widgetId">Нэмэлт тохиргоо :</label>';
                                                __html += '<div class="col-md-8">';
                                                    __html += '<div class="input-group">';
                                                        __html += '<textarea name="addintionalConfig" tabindex="" class="form-control form-control-sm expression_editorInit" data-path="addintionalConfig" data-field-name="addintionalConfig" data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder="Нэмэлт тохиргоо"></textarea>';
                                                        __html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpExpressionEditor(this);"><i class="far fa-code"></i></button></span> ';
                                                    __html += '</div>';
                                                __html += '</div>';
                                            __html += '</div>';
                                        __html += '</div>';
                                    __html += '</div>';
                                __html += '</div>';
                            __html += '</div>';
                        __html += '</div>';
                    }
                __html += '</div>';
        
                var _itemFounder = kpiDMChart_<?php echo $this->uniqId; ?>.find('.type-config<?php echo $this->uniqId ?>');
                _itemFounder.find('.item-config').hide();
        
                _itemFounder.empty().append(__html).promise().done(function () {
                    _itemFounder.find('.item-config[data-item-cf="' + blockUniqId +'"]').show();
                    _itemFounder.find('input').trigger('change');
                });
                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
                Core.showErrorMessage(jqXHR, exception);
            }
        });
    }
    
    function buildHtmlByIndicators<?php echo $this->uniqId ?>(kpiTypeIndicators, html) {
        var defaultVal = '';
        $.each(kpiTypeIndicators, function (i, r) {
            defaultVal = (typeof r.DEFAULT_VALUE && r.DEFAULT_VALUE) ? r.DEFAULT_VALUE : '';
            /* if (defaultVal && typeof eval(defaultVal) == 'object') {
                defaultVal = JSON.stringify(eval(defaultVal));
            } */

            if (typeof r.children !== 'undefined' && r.children) {
                html = buildHtmlByIndicators<?php echo $this->uniqId ?>(r.children, html);
            } else {
                switch (r.CODE) {
                    case 'sectionCol':
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ r.LABEL_NAME +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<select class="form-control form-control-sm mt-0" name="sectionCol['+ blockUniqId +']" data-path="'+ r.CODE +'">';
                                    html += '<option value="">- Сонгох -</option>';
                                    for (var i = 1; i <= 12; i++) {
                                        html += '<option value="'+ i +'">col-'+ i +'</option>';
                                    }
                                html += '</select>';
                            html += '</div>';
                        html += '</div>';
                        break;
                    case 'series.data':
                    case 'json':
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1" for="sectionCol">'+ r.LABEL_NAME +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<div class="input-group">';
                                    html += '<textarea tabindex="" class="form-control form-control-sm mt-0 expression_editorInit" disabled="disabled" data-path="'+ r.CODE +' data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder="">'+ defaultVal +'</textarea>';
                                    html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpExpressionEditor(this);"><i class="far fa-code"></i></button></span> ';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        break;
                    case 'html':
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1" for="sectionCol">'+ r.LABEL_NAME +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<div class="input-group">';
                                    html += '<textarea tabindex="" class="form-control form-control-sm mt-0 " data-path="html" disabled="disabled" data-path="'+ r.CODE +' data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder="">'+ defaultVal +'</textarea>';
                                    html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpFieldTextEditorClickToEdit(this, \'1\');"><i class="far fa-code"></i></button></span> ';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        break;
                    default:
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ r.LABEL_NAME +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<div class="input-group">';
                                    html += '<input type="text" class="form-control form-control-sm mt-0" data-path="'+ r.CODE +'" value="'+ defaultVal +'" />';
                                    html += '<span class="input-group-append">';
                                        html += '<button class="btn grey-cascade" type="button" onclick="clearKpiTypeAttr<?php echo $this->uniqId ?>(this);"><i class="far fa-trash"></i></button>';
                                    html += '</span> ';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        break;
                }
            }
        });
        return html;
    }

    function clearKpiTypeAttr<?php echo $this->uniqId ?>(element) {
        var $this = $(element),
            $parent = $this.closest('.input-group');

        $parent.find('input, textarea').val('');
        $this.closest('.type-config<?php echo $this->uniqId ?>').find('input, select').each(function (i, r) {
            var $r = $(r);
            if ($r && typeof $r.attr('data-path') !== 'undefined') {
                var _code = $r.attr('data-path').replaceAll('.', '_');
                delete kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code];
                if ($r.attr('type') === 'checkbox') {
                    kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code] = $r.is(':checked');
                } else {
                    if ($r.val()) 
                        kpiChartObj_<?php echo $this->uniqId; ?>['chartConfig'][_code] = $r.val();
                }
            }
        });

        EchartBuilder.chartRender(kpiChartObj_<?php echo $this->uniqId; ?>);
    }

</script>