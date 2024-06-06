<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['card_business'])) {
    $typeRow = $this->row['dataViewLayoutTypes']['card_business'];
?>
var cardlistview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [], grouped = [], dgindex = 0;
        table.push('<div class="row">');
        if (rows.length > 0) {
            container.closest('div.card-dashboard-five-none').addClass('show-title');
        }
        for (var i = 0; i < rows.length; i++) {
            table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
        }
        
        table.push('</div>');
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
            
        cc.push('<div class="col"><div class="card bg-primary p-3 border-left-danger rounded-left-0">');
            <?php
            if (isset($typeRow['fields']['name1'])) {
                $name2 = strtolower($typeRow['fields']['name1']);
            ?>
            cc.push('<div class="card-header h-auto" title="'+rowData.<?php echo $name2; ?>+'"><h6 class="card-title">');
                var codeCol = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                if (typeof codeCol !== 'undefined' && codeCol != null && codeCol.formatter) {
                    cc.push(codeCol.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name2; ?>);
                }
            cc.push('</h6></div>'); 
            
            <?php
            }
            ?>
            cc.push('<div class="card-body p-0"><h6 class="card-value text-white">');
                <?php
                if (isset($typeRow['fields']['name2'])) {
                    $name1 = strtolower($typeRow['fields']['name2']); ?>
                    var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                    if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                        cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                    } else {
                        cc.push(rowData.<?php echo $name1; ?>);
                    }
                    <?php
                }
            ?>
            cc.push('</h6></div>');         
        cc.push('</div></div>');    
        return cc.join('');
    }
});
<?php
}
?>