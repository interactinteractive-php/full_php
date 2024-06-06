<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['card'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['card'];
    
    $idField = $this->row['idField'] ? $this->row['idField'] : 'id';
    $photoField = '';
    $groupField = '';
    $defaultImage = 'assets/core/global/img/metaicon/big/'.$typeRow['DEFAULT_IMAGE'];
    
    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "api/image_thumbnail?width=70&src='+rowData.".strtolower($typeRow['fields']['photo'])."+'";
    }
    
    if (isset($typeRow['fields']['groupName']) && $typeRow['fields']['groupName'] != '') {
        $groupField = strtolower($typeRow['fields']['groupName']);
    } elseif (issetParam($this->dataGridOptionData['GROUPFIELD']) != '') {
        $groupField = strtolower($this->dataGridOptionData['GROUPFIELD']);
    }
?>
var cardview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var rowsLength = rows.length;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [];
        
        if ('<?php echo $groupField ?>' === '') {
            for (var i = 0; i < rowsLength; i++) {
                table.push('<table class="datagrid-btable" cellpadding="0" cellpadding="0" border="0"><tbody>');
                table.push("<tr datagrid-row-index=\"" + i + "\" " + cls + ">");
                table.push(this.renderRow.call(this, target, fields, frozen, rowKey, rows[i]));
                table.push("</tr>");
                table.push('</tbody></table>');
            }
        } else {
        
            var rowKey = 0, groupCheck = {};
            
            for (var i = 0; i < rowsLength; i++) {
                if (typeof groupCheck[rows[i].<?php echo $idField; ?>] === 'undefined') {
                    table.push('<div class="datagrid-group-name">'+rows[i]['<?php echo $groupField ?>']+'</div>');
                }
                for (var ii = 0; ii < rowsLength; ii++) {
                    if (rows[i]['<?php echo $groupField ?>'] == rows[ii]['<?php echo $groupField ?>'] && typeof groupCheck[rows[ii].<?php echo $idField; ?>] === 'undefined') {
                        groupCheck[rows[ii].<?php echo $idField; ?>] = true;
                        table.push('<table class="datagrid-btable" cellpadding="0" cellpadding="0" border="0"><tbody>');
                        table.push("<tr datagrid-row-index=\"" + rowKey + "\" " + cls + ">");
                        table.push(this.renderRow.call(this, target, fields, frozen, rowKey, rows[ii]));
                        table.push("</tr>");
                        table.push('</tbody></table>');
                        rowKey++;
                    }
                }
            }
        }
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
        cc.push('<td>');
            cc.push('<div class="card-one">');
                cc.push('<div class="card-wrapper-eui">');   
                    cc.push('<div class="image-wrapper-eui">');  
                        cc.push('<img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');
                    cc.push('</div>');
                    cc.push('<div class="content-actions-wrapper-eui">');
                        cc.push('<div class="content-eui">');
                            <?php
                            if (isset($typeRow['fields']['name1'])) {
                                $name1 = strtolower($typeRow['fields']['name1']);
                            ?>
                            cc.push('<div class="name-wrapper-eui code" title="'+detectHtmlStr(rowData.<?php echo $name1; ?>)+'">');
                                var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                                if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                    cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                                } else {
                                    cc.push(rowData.<?php echo $name1; ?>);
                                }
                            cc.push('</div>');
                            <?php
                            }
                            if (isset($typeRow['fields']['name2'])) {
                                $name2 = strtolower($typeRow['fields']['name2']);
                            ?>
                            cc.push('<div class="name-wrapper-eui" title="'+detectHtmlStr(rowData.<?php echo $name2; ?>)+'">');
                                var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                                if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                                    cc.push(name2Col.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                                } else {
                                    cc.push(rowData.<?php echo $name2; ?>);
                                }
                            cc.push('</div>'); 
                            <?php
                            }
                            if (isset($typeRow['fields']['name3'])) {
                                $name3 = strtolower($typeRow['fields']['name3']);
                            ?>
                            cc.push('<div class="position-wrapper-eui" title="'+detectHtmlStr(rowData.<?php echo $name3; ?>)+'">');
                                var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                                if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                                    cc.push(name3Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                                } else {
                                    cc.push(rowData.<?php echo $name3; ?>);
                                }
                            cc.push('</div>'); 
                            <?php
                            }
                            if (isset($typeRow['fields']['name4'])) {
                                $name4 = strtolower($typeRow['fields']['name4']);
                            ?>
                            cc.push('<div class="position-wrapper-eui" title="'+detectHtmlStr(rowData.<?php echo $name4; ?>)+'">');
                                var name4Col = $(target).datagrid('getColumnOption', '<?php echo $name4; ?>');
                                if (typeof name4Col !== 'undefined' && name4Col != null && name4Col.formatter) {
                                    cc.push(name4Col.formatter(rowData.<?php echo $name4; ?>, rowData, rowIndex));
                                } else {
                                    cc.push(rowData.<?php echo $name4; ?>);
                                }
                            cc.push('</div>'); 
                            <?php
                            }
                            if (isset($typeRow['fields']['name5'])) {
                                $name5 = strtolower($typeRow['fields']['name5']);
                            ?>
                            cc.push('<div class="position-wrapper-eui" title="'+detectHtmlStr(rowData.<?php echo $name5; ?>)+'">');
                                var name5Col = $(target).datagrid('getColumnOption', '<?php echo $name5; ?>');
                                if (typeof name5Col !== 'undefined' && name5Col != null && name5Col.formatter) {
                                    cc.push(name5Col.formatter(rowData.<?php echo $name5; ?>, rowData, rowIndex));
                                } else {
                                    cc.push(rowData.<?php echo $name5; ?>);
                                }
                            cc.push('</div>'); 
                            <?php
                            }
                            ?>
                        cc.push('</div>');         
                    cc.push('</div>');     
                cc.push('</div>');    
            cc.push('<div>');
        cc.push('</td>');
        return cc.join('');
    }
});
<?php
}
?>