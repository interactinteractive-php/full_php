<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['ecommerce'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['ecommerce'];
    
    $groupField = '';
    $photoField = 'assets/custom/addon/img/user.png';
    
    if (isset($typeRow['fields']['groupName']) && $typeRow['fields']['groupName'] != '') {
        
        $groupField = strtolower($typeRow['fields']['groupName']);
        
    } elseif (issetParam($this->dataGridOptionData['GROUPFIELD']) != '') {
        
        $groupField = strtolower($this->dataGridOptionData['GROUPFIELD']);
    }
    
    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo']) {
        $photoField = "api/image_thumbnail?src='+rowData.".strtolower($typeRow['fields']['photo'])."+'&width=138";
    }
?>
var ecommerceview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var rows2 = rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [], rowKey = 0, groupCheck = {};

        if ('<?php echo $groupField ?>' === '') {
            for (var ii = 0; ii < rows2.length; ii++) {
                table.push('<table class="datagrid-btable d-block float-left" cellpadding="0" cellpadding="0" border="0"><tbody>');
                table.push("<tr datagrid-row-index=\"" + rowKey + "\" " + cls + ">");
                table.push(this.renderRow.call(this, target, fields, frozen, rowKey, rows2[ii]));
                table.push("</tr>");
                table.push('</tbody></table>');
                rowKey++;
            }            
        } else {
            for (var i = 0; i < rows.length; i++) {
                if (typeof groupCheck[rows[i].id] === 'undefined') {
                    table.push('<div class="w-100 row" style="color:#318ccc;font-size:16px;font-weight:600;padding-left:.625rem">'+rows[i]['<?php echo $groupField ?>']+'</div>');
                }
                for (var ii = 0; ii < rows2.length; ii++) {
                    if (rows[i]['<?php echo $groupField ?>'] == rows2[ii]['<?php echo $groupField ?>'] && typeof groupCheck[rows2[ii].id] === 'undefined') {
                        groupCheck[rows2[ii].id] = true;
                        table.push('<table class="datagrid-btable d-block float-left" cellpadding="0" cellpadding="0" border="0"><tbody>');
                        table.push("<tr datagrid-row-index=\"" + rowKey + "\" " + cls + ">");
                        table.push(this.renderRow.call(this, target, fields, frozen, rowKey, rows2[ii]));
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
            cc.push('<div class="bigcard-wrapper-eui">');   
                cc.push('<div class="content-eui row">');
                    cc.push('<div class="dv-bigcard-left col">');
                        
                        cc.push('<div class="dv-bigcard-left-photo">');
                            cc.push('<div class="dv-bigcard-left-photo-wrap">');
                                cc.push('<img src="<?php echo $photoField; ?>" onerror="onUserLogoError(this);">');
                            cc.push('</div>');     
                        cc.push('</div>');   
                        
                        <?php
                        if (isset($typeRow['fields']['name1'])) {
                            $name1 = strtolower($typeRow['fields']['name1']);
                        ?>
                        cc.push('<div class="dv-bigcard-left-pos1">');
                        var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                        if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                            cc.push(name1Col.formatter(dvFieldValueShow(rowData.<?php echo $name1; ?>), rowData, rowIndex));
                        } else {
                            cc.push(dvFieldValueShow(rowData.<?php echo $name1; ?>));
                        }
                        cc.push('</div>');
                        <?php
                        }
                        ?>
                        
                        <?php
                        if (isset($typeRow['fields']['name2'])) {
                            $name2 = strtolower($typeRow['fields']['name2']);
                        ?>
                        cc.push('<div class="dv-bigcard-left-pos2">'+dvFieldValueShow(rowData.<?php echo $name2; ?>)+'</div>');
                        <?php
                        }
                        ?>
                        
                    cc.push('</div>');  
                    
                    cc.push('<div class="dv-bigcard-right col">');
                        
                        <?php
                        if (isset($typeRow['fields']['name3'])) {
                            $name3 = strtolower($typeRow['fields']['name3']);
                            $name3Label = Lang::line($typeRow['fields']['name3label']);
                        ?>
                        cc.push('<div class="dv-bigcard-left-pos3"><span class="font-weight-bold"><?php echo $name3Label; ?>:</span> '+dvFieldValueShow(rowData.<?php echo $name3; ?>)+'</div>');
                        <?php
                        }
                        ?>
                        
                        <?php
                        if (isset($typeRow['fields']['name4'])) {
                            $name4 = strtolower($typeRow['fields']['name4']);
                            $name4Label = Lang::line($typeRow['fields']['name4label']);
                        ?>
                        cc.push('<div class="dv-bigcard-left-pos4"><span class="font-weight-bold"><?php echo $name4Label; ?>:</span> '+dvFieldValueShow(rowData.<?php echo $name4; ?>)+'</div>');
                        <?php
                        }
                        ?>
                        
                        <?php
                        if (isset($typeRow['fields']['name5'])) {
                            $name5 = strtolower($typeRow['fields']['name5']);
                            $name5Label = Lang::line($typeRow['fields']['name5label']);
                        ?>
                        cc.push('<div class="dv-bigcard-left-pos3"><span class="font-weight-bold"><?php echo $name5Label; ?>:</span> '+dvFieldValueShow(rowData.<?php echo $name5; ?>)+'</div>');
                        <?php
                        }
                        ?>
                        
                        <?php
                        if (isset($typeRow['fields']['name6'])) {
                            $name6 = strtolower($typeRow['fields']['name6']);
                            $name6Label = Lang::line($typeRow['fields']['name6label']);
                        ?>
                        cc.push('<div class="dv-bigcard-left-pos3"><span class="font-weight-bold"><?php echo $name6Label; ?>:</span> '+dvFieldValueShow(rowData.<?php echo $name6; ?>)+'</div>');
                        <?php
                        }
                        ?>
                        
                        <?php
                        if (isset($typeRow['fields']['name7'])) {
                            $name7 = strtolower($typeRow['fields']['name7']);
                            $name7Label = Lang::line($typeRow['fields']['name7label']);
                        ?>
                        cc.push('<div class="dv-bigcard-left-pos3"><span class="font-weight-bold"><?php echo $name7Label; ?>:</span> '+dvFieldValueShow(rowData.<?php echo $name7; ?>)+'</div>');
                        <?php
                        }
                        ?>
                        
                    cc.push('</div>');   
                    
                cc.push('</div>');
            cc.push('</div>');
        cc.push('</td>');
        
        return cc.join('');
    }
});
<?php
}
?>