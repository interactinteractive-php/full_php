<div id="windowId-<?php echo $this->uniqId2 ?>">
    <div class="d-flex mt9" style="gap: 20px">            
    <?php 
        foreach ($this->relationList as $tabName => $row) {
            $kpiTypeId = $row['KPI_TYPE_ID'];
            $mapLabelName = $row['MAP_LABEL_NAME'];
            $class = $itemClass = '';

            if ($mapLabelName != '') {
                $name = $this->lang->line($mapLabelName);
            } else {
                if ($kpiTypeId == 2008) {
                    $name = $row['STRUCTURE_NAME'];
                } elseif ($row['META_DATA_ID']) {
                    $name = $this->lang->line($row['META_DATA_NAME']);
                } else {
                    $name = $row['NAME'];
                }
            }                
        ?>           
            <div data-id="<?php echo $row['ID'] ?>" style="border: 1px solid #B1B1B1;border-radius: 12px;padding: 10px 20px 10px 20px;cursor: pointer" class="button-html-link"><?php echo $name ?></div>
        <?php 
        }                    
        ?>        
    </div>
    <div class="render-page mt20"></div>
</div>

<style>
    #windowId-<?php echo $this->uniqId2 ?> .button-html-link.active {
        background-color: #25BCBD;
        color: #fff;
    }
</style>

<script type="text/javascript">
    var $checkList_<?php echo $this->uniqId2; ?> = $('#windowId-<?php echo $this->uniqId2; ?>');
    
    $checkList_<?php echo $this->uniqId2; ?>.on('click', '.button-html-link', function() {
        var $this = $(this);
        $this.parent().find('.active').removeClass('active');
        $this.addClass('active');
        
        $.ajax({
            url: "assets/custom/addon/plugins/echarts/echarts.js",
            dataType: "script",
            cache: true,
            async: false
        });                

        $.ajax({
            url: "middleware/assets/js/addon/echartsBuilder.js",
            dataType: "script",
            cache: true,
            async: false
        });                

        $.ajax({
            type: 'post',
            url: 'mdwidget/renderLayoutSection/' + $this.data('id'),
            data: '',
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {
                var html = [];

                html.push(dataHtml.html);

                $checkList_<?php echo $this->uniqId2; ?>.find('.render-page').empty().append(html.join('')).promise().done(function() {
                    Core.unblockUI();
                });                

                $.ajax({
                    type: 'post',
                    url: 'mdform/filterKpiIndicatorValueForm',
                    data: {indicatorId: $this.data('id'), drillDownCriteria: '', filterPosition: 'top', filterColumnCount: '3'},
                    dataType: 'json',
                    success: function(data) {
                        var $filterCol = $checkList_<?php echo $this->uniqId2; ?>.find('.render-page').find('.kpipage-data-top-filter-col').last();

                        if (data.status == 'success' && data.html != '') {

                            if ($filterCol.length) {

                                $filterCol.closest('.mv-datalist-container').addClass('mv-datalist-show-filter');
                                $filterCol.closest('.ws-page-content').removeClass('mt-2');

                                $filterCol.append(data.html).promise().done(function() {
                                    Core.initNumberInput($filterCol);
                                    Core.initLongInput($filterCol);
                                    Core.initDateInput($filterCol);
                                    Core.initSelect2($filterCol);         
                                });
                            }

                        }
                    }
                });                        
            }
        });                   
    });
    $checkList_<?php echo $this->uniqId2; ?>.find('.button-html-link').first().trigger('click');
</script>