<div class="jeasyuiTheme3 web-dataview" id="removedlogs-datagrid-panel">
    <table id="removedlogs-datagrid" style="width: 100%; height: 450px;"></table>
</div>

<script type="text/javascript">
var moreLabel = plang.get('more');

$(function(){
    
    $('#removedlogs-datagrid').datagrid({
        url: 'mdobject/dataViewDataGrid',
        queryParams: {
            metaDataId: '<?php echo $this->metaDataId; ?>', 
            defaultCriteriaData: 'criteriaCondition[filtermetagroupid]==&param[filtermetagroupid]=<?php echo $this->refStructureId; ?>',  
            ignorePermission: '1'
        }, 
        rownumbers: true,
        singleSelect: true,
        pagination: true,
        pageSize: 100,
        pageList: [10,20,50,100], 
        fitColumns: true,
        nowrap: false,
        remoteFilter: true,
        filterDelay: 10000000000, 
        columns: <?php echo $this->columns; ?>,
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            $(this).datagrid('selectRow', index);

            $.contextMenu({
                selector: "#removedlogs-datagrid-panel .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, #removedlogs-datagrid-panel .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'detail') {
                        var row = $('#removedlogs-datagrid').datagrid('getSelected');
                        //bpRecordRemovedLogDetail(this, row.id);
                        bpRecordLogDetail({elem: this, isRemovedLog: 1}, row.id, <?php echo $this->isLogRecover; ?>, '<?php echo $this->dvId; ?>');
                    }
                },
                items: {
                    "detail": {name: moreLabel}
                }
            });
        },
        onLoadSuccess:function(data) {
            if (data.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }
            var _thisGrid = $('#removedlogs-datagrid');
            showGridMessage(_thisGrid);   
            
            var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
            var $panelFilterRow = $panelView.find('.datagrid-filter-row');
            
            <?php 
            echo Arr::get($this->dataGridHeader, 'filterCenterInit');
            echo Arr::get($this->dataGridHeader, 'filterDateInit');
            echo Arr::get($this->dataGridHeader, 'filterDateTimeInit');
            echo Arr::get($this->dataGridHeader, 'filterTimeInit');
            echo Arr::get($this->dataGridHeader, 'filterBigDecimalInit');
            echo Arr::get($this->dataGridHeader, 'filterNumberInit');
            ?>

            if ($panelFilterRow.length) {
                Core.initNumberInput($panelFilterRow);
                Core.initDateInput($panelFilterRow);
                Core.initDateTimeInput($panelFilterRow);
                Core.initDateMinuteMaskInput($panelFilterRow);
                Core.initDateMaskInput($panelFilterRow);
                Core.initDateMinuteInput($panelFilterRow);
                Core.initTimeInput($panelFilterRow);
            }
        }
    });
    
    $('#removedlogs-datagrid').datagrid('enableFilter', [{field: 'id', type: 'label'}]);
});

function gridRemoveLogDetailMoreRow(val,row) {
    return '<button type="button" class="btn btn-xs bg-teal-400" onclick="bpRecordLogDetail({elem: this, isRemovedLog: 1}, \''+val+'\', <?php echo $this->isLogRecover; ?>, \'<?php echo $this->dvId; ?>\');">'+moreLabel+'</button>';
}
</script>