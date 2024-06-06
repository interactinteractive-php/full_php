<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['card_business'])) {
    $typeRow = $this->row['dataViewLayoutTypes']['card_business'];
?>
var suggestlistview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [], grouped = [], dgindex = 0;
        table.push('<div class="row">');
            
        for (var i = 0; i < rows.length; i++) {
            table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
        }

        table.push('</div>');
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
            
        cc.push('<div class="col-6 col-sm-4 col-lg">');  
            <?php
            if (isset($typeRow['fields']['name1'])) {
                $name2 = strtolower($typeRow['fields']['name1']);
            ?>
            cc.push('<label class="card-label" title="'+rowData.<?php echo $name2; ?>+'">');
                var codeCol = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                if (typeof codeCol !== 'undefined' && codeCol != null && codeCol.formatter) {
                    cc.push(codeCol.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name2; ?>);
                }
            cc.push('</label>'); 
            
            <?php
            }
            ?>
            cc.push('<h6 class="card-value">');
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
            cc.push('</h6>');         
        cc.push('</div>');    
        return cc.join('');
    }
});
<?php
}
?>