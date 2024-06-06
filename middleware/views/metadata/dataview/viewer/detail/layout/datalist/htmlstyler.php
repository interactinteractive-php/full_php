<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['datalist'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['datalist'];
    
    $name = strtolower(issetParam($typeRow['fields']['name1']));
?>
var htmlstylerview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [];

        for (var i = 0; i < rows.length; i++) {
            table.push('<table class="datagrid-btable" cellpadding="0" cellpadding="0" border="0"><tbody>');
            table.push("<tr datagrid-row-index=\"" + i + "\" " + cls + ">");
            table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
            table.push("</tr>");
            table.push('</tbody></table>');
        }
        
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
        cc.push('<td>');
        
        cc.push(dvHtmlStylerRenderRow(rowData));

        cc.push('</td>');
        return cc.join('');
    }
});

function dvHtmlStylerRenderRow(rowData) {
    
    var cc = [], style = '';
    
    if (rowData.hasOwnProperty('jsonconfig') && rowData.jsonconfig) {
    
        var jsonConfig = JSON.parse(rowData.jsonconfig);
        
        if (jsonConfig['font-align']) {
            style += 'text-align: ' + jsonConfig['font-align'] + ';';
        }

        if (jsonConfig['font-size']) {
            style += 'font-size: ' + jsonConfig['font-size'] + ';';
        }

        if (jsonConfig['font-style']) {
            style += jsonConfig['font-style'] + ';';
        }

        if (jsonConfig['font-weight']) {
            style += 'font-weight: ' + jsonConfig['font-weight'] + ';';
        }

        if (jsonConfig['padding-top']) {
            style += 'padding-top: ' + jsonConfig['padding-top'] + ';';
        }
        
        if (jsonConfig['padding-left']) {
            style += 'padding-left: ' + jsonConfig['padding-left'] + ';';
        }
        
        if (jsonConfig['padding-right']) {
            style += 'padding-right: ' + jsonConfig['padding-right'] + ';';
        }
        
        if (jsonConfig['padding-bottom']) {
            style += 'padding-bottom: ' + jsonConfig['padding-bottom'] + ';';
        }
    }

    cc.push('<div style="'+style+'">');

        cc.push(html_entity_decode(convertNlToBr(rowData.<?php echo $name; ?>), 'ENT_QUOTES'));

    cc.push('</div>');

    return cc.join('');
}
<?php
}
?>