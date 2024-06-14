<ul class="nav nav-tabs nav-tabs-bottom">
    <li class="nav-item">
        <a href="#kpibasket-list-<?php echo $this->indicatorId; ?>" class="nav-link pb8 active" data-toggle="tab">
        <?php echo $this->lang->line('META_00062'); ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="#kpibasket-basket-<?php echo $this->indicatorId; ?>" class="nav-link pb8" data-toggle="tab">
            <?php echo $this->lang->line('basket'); ?> ( <span id="commonSelectedCount_<?php echo $this->indicatorId; ?>" class="dv-basket-count">0</span> )
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade show active" id="kpibasket-list-<?php echo $this->indicatorId; ?>">
        <div class="col-md-12">
            <?php echo $this->mainList; ?>
        </div>
    </div>
    <div class="tab-pane fade" id="kpibasket-basket-<?php echo $this->indicatorId; ?>">
        <div class="jeasyuiTheme3 selectableGrid-datatable-<?php echo $this->indicatorId; ?>">
            <table id="commonSelectableBasketDataGrid_<?php echo $this->indicatorId ?>"></table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    
    $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId; ?>').datagrid({
        url: '',
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        pagination: false,
        remoteSort: false,
        height: 480,
        fitColumns: true,
        showFooter: true,
        frozenColumns: [[
            {field: 'ck', rowspan:1, checkbox: true }, 
            {field:'action', title:'', sortable:false, width:40, align:'center'}
        ]],
        columns: [[<?php echo $this->columns['columnsRender']; ?>]],
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            $(this).datagrid('selectRow', index);
            $.contextMenu({
                selector: "#kpibasket-basket-<?php echo $this->indicatorId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'delete') {
                        multiDeleteCommonSelectableBasket_<?php echo $this->indicatorId ?>();
                    }
                },
                items: {
                    "delete": {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"}
                }
            });
        },
        onLoadSuccess:function(){

            var $thisGrid = $(this);
            var $panelView = $thisGrid.datagrid("getPanel").children("div.datagrid-view");
            var $panelFilterRow = $panelView.find('.datagrid-filter-row');

            Core.initNumberInput($panelFilterRow);
            Core.initDateInput($panelFilterRow);
            Core.initDateTimeInput($panelFilterRow);
            Core.initDateMaskInput($panelFilterRow);
            Core.initDateMinuteInput($panelFilterRow);
            Core.initTimeInput($panelFilterRow);
            Core.initAccountCodeMask($panelFilterRow);
            Core.initStoreKeeperKeyCodeMask($panelFilterRow);

            Core.initLongInput($panelView);
            Core.initFancybox($panelView);

            $thisGrid.promise().done(function() {
                $thisGrid.datagrid('resize');
            });
        }
    });

    $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId ?>').datagrid('loadData', []);
    
    $('a[href="#kpibasket-list-<?php echo $this->indicatorId; ?>"]').on('shown.bs.tab', function(){
        $('#objectdatagrid_<?php echo $this->indicatorId; ?>').datagrid('resize');
    });
    
    $('a[href="#kpibasket-basket-<?php echo $this->indicatorId; ?>"]').on('shown.bs.tab', function(){
        setTimeout(function() {
            $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId; ?>').datagrid('resize');
            $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId; ?>').datagrid('fixRowHeight');
        }, 5);
    });
});

function dblClickCommonSelectableDataGrid_<?php echo $this->indicatorId ?>(row) {   
    var $basketGrid = $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId; ?>');
    var chooseTypeDataGrid = '<?php echo $this->chooseType; ?>';  
    var isAddRow = true;

    if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
        $basketGrid.datagrid('loadData', []);
    } else {
        $('#commonSelectedCount_<?php echo $this->indicatorId; ?>').pulsate({
            color: '#F3565D', 
            reach: 9,
            speed: 500,
            glow: false, 
            repeat: 1
        });   
    }                
    var rows = $basketGrid.datagrid('getRows');

    for (var j = 0; j < rows.length; j++) {
        var subrow = rows[j];
        if (subrow.<?php echo $this->idField; ?> === row.<?php echo $this->idField; ?>) {
            isAddRow = false;
        }
    }
    if (isAddRow) {
        
        $basketGrid.datagrid('appendRow', {
            action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->indicatorId; ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="far fa-trash"></i></a>', 
            <?php 
            echo $this->dataGridBody; 
            ?>
        });            
    }
    
    $("#commonSelectedCount_<?php echo $this->indicatorId; ?>").text($basketGrid.datagrid('getData').total);
    
    if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
        $("#commonSelectedCount_<?php echo $this->indicatorId; ?>").closest('div.ui-dialog').children('div.ui-dialog-buttonpane').find('button.datagrid-choose-btn').click();
    }
    
    return;
}
function basketCommonSelectableDataGrid_<?php echo $this->indicatorId; ?>() {
        
    $('#commonSelectedCount_<?php echo $this->indicatorId; ?>').pulsate({
        color: '#F3565D', 
        reach: 9,
        speed: 500,
        glow: false, 
        repeat: 1
    });    

    var $basketGrid = $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId; ?>');
    var chooseType = '<?php echo $this->chooseType; ?>';

    if (chooseType == 'multi') {

        var basketRows = $basketGrid.datagrid('getRows');
        var dvRows = $('#objectdatagrid_<?php echo $this->indicatorId; ?>').datagrid('getSelections');

        setTimeout(function() {

            for (var i = 0; i < dvRows.length; i++) {
                var dvRow = dvRows[i];
                var isAddRow = true;

                for (var j = 0; j < basketRows.length; j++) {
                    var subrow = basketRows[j];
                    if (subrow.<?php echo $this->idField; ?> === dvRow.<?php echo $this->idField; ?>) {
                        isAddRow = false;
                    }
                }

                if (isAddRow) {

                    dvRow['action'] = '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->indicatorId; ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="far fa-trash"></i></a>';
                    basketRows.push(dvRow);                     
                }
            }

            var obj = {'total': basketRows.length, 'rows': basketRows};  
            $basketGrid.datagrid('loadData', obj);

            $("#commonSelectedCount_<?php echo $this->indicatorId; ?>").text($basketGrid.datagrid('getData').total);

        }, 5);

    } else {

        var rows = $('#objectdatagrid_<?php echo $this->indicatorId; ?>').datagrid('getSelections');

        if ((chooseType == 'single' || chooseType == 'singlealways') && rows.length > 0) {
            $basketGrid.datagrid('loadData', []);
        }

        var subrows = $basketGrid.datagrid('getRows');

        for (var i = 0; i < rows.length; i++) {

            var row = rows[i];
            var isAddRow = true;

            for (var j = 0; j < subrows.length; j++) {
                var subrow = subrows[j];
                if (subrow.<?php echo $this->idField; ?> === row.<?php echo $this->idField; ?>) {
                    isAddRow = false;
                }
            }
            if (isAddRow) {
                $basketGrid.datagrid('appendRow', {
                    action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->indicatorId; ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="far fa-trash"></i></a>', 
                    <?php echo $this->dataGridBody; ?>
                });
            }
        }

        $("#commonSelectedCount_<?php echo $this->indicatorId; ?>").text($basketGrid.datagrid('getData').total);
    }       
}
function deleteCommonSelectableBasket_<?php echo $this->indicatorId; ?>(target) {
    var $rf = $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId; ?>');
    $rf.datagrid('deleteRow', getRowIndex(target));
    $("#commonSelectedCount_<?php echo $this->indicatorId; ?>").text($rf.datagrid('getData').total);      
}
function multiDeleteCommonSelectableBasket_<?php echo $this->indicatorId; ?>() {
    var $rf = $('#commonSelectableBasketDataGrid_<?php echo $this->indicatorId; ?>');
    var rows = $rf.datagrid('getSelections');
    var rowsLength = rows.length;
    var rr = [];

    for (var i = 0; i < rowsLength; i++) {
        rr.push(rows[i]);
    }
    
    $.map(rr, function(row) {
        var index = $rf.datagrid('getRowIndex', row);
        $rf.datagrid('deleteRow', index);
    });

    $("#commonSelectedCount_<?php echo $this->indicatorId; ?>").text($rf.datagrid('getData').total);
}
</script>