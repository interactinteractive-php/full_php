<div id="kpi-datamart-chart-<?php echo $this->uniqId; ?>" class="row" data-kpidmchart-indicator="<?php echo $this->indicatorId; ?>">
    <div class="col-md-auto pl-0">
        <div class="kpidv-data-filter-col pr-1"></div>
    </div>
    <div class="col right-sidebar-content-for-resize content-wrapper pl-0 pr-0 position-relative">
        <?php
        foreach ($this->charts as $row) {
        ?>
        <div class="text-left font-weight-bold font-size-16"><?php echo $row['NAME']; ?></div>
        
        <div class="kpidm-chart-list-div mb-2 row ml-1" id="kpi-datamart-chart-render-<?php echo $this->uniqId.'-'.$row['ID']; ?>" style="width: 100%;height: 500px;min-height: max-content;"></div>
        <script type="text/template" data-id="kpi-datamart-chart-render-<?php echo $this->uniqId.'-'.$row['ID']; ?>">
            <?php 
            $row['GRAPH_JSON'] = str_replace('{"type":', '{"chartName": "'.$row['NAME'].'", "type":', $row['GRAPH_JSON']);
            echo $row['GRAPH_JSON']; 
            ?>
        </script>
        <?php
        }
        ?>
        <div class="col-md-4 position-absolute subcontent" style=" right: 0; display: none"></div>
    </div>
</div>

<script type="text/javascript">
    $('body').on('click', '#kpi-datamart-chart-<?php echo $this->uniqId; ?> .subcontent-collapse-btn', function () {
        var _this = $(this);
        _this.closest('.subcontent').toggle();
    });
</script>