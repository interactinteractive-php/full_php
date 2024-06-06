<div class="center-sidebar overflow-hidden content" style="padding: 0 15px;">
    <div class="row">
        
        <?php
        if (!isset($this->isIgnoreFilter)) {
        ?>
        <div class="col-md-auto pl-0">
            <div class="kpidv-data-filter-col pr-1"></div>
        </div>
        <?php
        }
        ?>
        
        <div class="col right-sidebar-content-for-resize content-wrapper pl-0 pr-0 overflow-hidden">
            <div class="row">
                <div class="col-md-12 objectdatacustomgrid" id="objectdatacustomgrid-<?php echo $this->indicatorId; ?>">
                    <?php echo $this->renderGrid; ?>
                </div>
            </div>
        </div>    
        
    </div>
</div>
<style type="text/css">
.kpidv-data-filter-col {
    width: 240px;
    border-right: 1px solid #ddd;
    overflow-x: hidden;
    overflow-y: auto;
}
.kpidv-data-filter-col .list-group {
    border: none;
    padding: 0;
}
.kpidv-data-filter-col .list-group-item {
    padding: 0.28rem 0;
}
.kpidv-data-filter-col .list-group-item.active {
    color: rgba(51,51,51,.85);
    background-color: rgba(93, 173, 226, 0.3);
    border-color: rgba(93, 173, 226, 0.3);
}
</style>
<script type="text/javascript">
var dynamicHeight = 0;
var objectdatagrid_<?php echo $this->indicatorId; ?> = $('#objectdatacustomgrid-<?php echo $this->indicatorId; ?>');
var idField_<?php echo $this->indicatorId; ?> = '<?php echo $this->idField; ?>';
var _selectedRows_<?php echo $this->indicatorId; ?> = [];

if (typeof isKpiIndicatorScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>');
}

setTimeout(function() {
    
    dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->indicatorId; ?>.offset().top - 40;

    if (dynamicHeight < 230) {
        dynamicHeight = 350;
    }

    if (objectdatagrid_<?php echo $this->indicatorId; ?>.closest('.package-tab').length) {
        dynamicHeight = 'auto';
    }

    <?php
    if (!isset($this->isIgnoreFilter)) {
    ?>
    filterKpiIndicatorValueForm(<?php echo $this->indicatorId; ?>);
    <?php
    }
    ?>
    
}, 200);

<?php
if (!isset($this->isIgnoreFilter)) {
?>
function filterKpiIndicatorValueForm(indicatorId) {
    $.ajax({
        type: 'post',
        url: 'mdform/filterKpiIndicatorValueForm',
        data: {indicatorId: indicatorId},
        dataType: 'json',
        success: function(data) {
            
            var $filterCol = $('#object-value-list-' + indicatorId + ' .kpidv-data-filter-col');
            
            if (data.status == 'success' && data.html != '') {
                
                $filterCol.css('height', dynamicHeight + 47);
                
                $filterCol.append(data.html).promise().done(function() {
                    Core.initNumberInput($filterCol);
                    Core.initLongInput($filterCol);
                    Core.initDateInput($filterCol);
                });
                
            } else {
                $filterCol.closest('.col-md-auto').remove();
                console.log(data);
            }
        }
    });
}
function filterKpiIndicatorValueGrid(elem) {
    
    var getFilterData = getKpiIndicatorFilterData(elem);
    var indicatorId = getFilterData.indicatorId;
    var filterData = getFilterData.filterData;
    
    window['isFilterShowData_' + indicatorId] = false;
    
    var dvSearchParam = {
        indicatorId: indicatorId,
        filterData: filterData,
        isReloadDataWidget: 1
    };    
    
    $.ajax({
        type: 'post',
        url: 'mdform/indicatorList/<?php echo $this->indicatorId; ?>/1',
        data: dvSearchParam,
        success: function(data) {
            objectdatagrid_<?php echo $this->indicatorId; ?>.empty().append(data);
        }
    });
}
<?php
}
?>
</script>