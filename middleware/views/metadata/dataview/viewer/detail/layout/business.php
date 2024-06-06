<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['card_business'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['card_business'];
    
    $photoField = '';
    $groupName = '';
    $defaultImage = 'assets/core/global/img/metaicon/big/'.$typeRow['DEFAULT_IMAGE'];

    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "'+rowData.".strtolower($typeRow['fields']['photo'])."+'";
    }
    if (isset($typeRow['fields']['groupName']) && $typeRow['fields']['groupName'] != '') {
        $groupName = strtolower($typeRow['fields']['groupName']);
    }
?>
var businessview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [], grouped = [], dgindex = 0;

        <?php if (!empty($groupName)) { ?>
            for (var i = 0; i < rows.length; i++) {
                if (typeof grouped[rows[i]['<?php echo $groupName; ?>']] === 'undefined') {
                    grouped[rows[i]['<?php echo $groupName; ?>']] = [];
                    grouped[rows[i]['<?php echo $groupName; ?>']]['groupname'] = rows[i]['<?php echo $groupName; ?>'];
                    grouped[rows[i]['<?php echo $groupName; ?>']]['rows'] = [];
                    grouped[rows[i]['<?php echo $groupName; ?>']]['rows'].push(rows[i]);
                } else {
                    grouped[rows[i]['<?php echo $groupName; ?>']]['rows'].push(rows[i]);
                }
            }

            for (key in grouped) {
                table.push('<div class="clearfix"></div><div style="color: #318ccc; font-weight: 700; font-size: 18px; margin: 10px 0 10px 0; padding: 0 0 5px 5px; border-bottom: 1px #ccc solid;">'+grouped[key]['groupname']+'</div>');

                for (var ii = 0; ii < grouped[key]['rows'].length; ii++) {
                    table.push('<table class="datagrid-btable" cellpadding="0" cellpadding="0" border="0"><tbody>');
                    table.push("<tr datagrid-row-index=\"" + dgindex + "\" " + cls + ">");
                    table.push(this.renderRow.call(this, target, fields, frozen, dgindex, grouped[key]['rows'][ii]));
                    table.push("</tr>");
                    table.push('</tbody></table>');              
                    dgindex++;
                }
            }        
        <?php } else { ?>
            for (var i = 0; i < rows.length; i++) {
                table.push('<table class="datagrid-btable" cellpadding="0" cellpadding="0" border="0"><tbody>');
                table.push("<tr datagrid-row-index=\"" + i + "\" " + cls + ">");
                table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
                table.push("</tr>");
                table.push('</tbody></table>');
            }			        
        <?php } ?>

        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
        cc.push('<td>');
            
        cc.push('<div class="card-wrapper-eui business-card-wrapper-eui">'+(typeof rowData.isnew !== 'undefined' && rowData.isnew == '1' ? '<div class="p-ribbon right"><div class="product-badge new"><span style="background: '+(typeof rowData.labelcolor !== 'undefined' ? rowData.labelcolor : '#30954b')+'">'+(typeof rowData.labelname !== 'undefined' ? rowData.labelname : 'Шинэ')+'</span></div></div>': '')+'<div class="business-wrapper">');  
            <?php
            if (isset($typeRow['fields']['name2'])) {
                $name2 = strtolower($typeRow['fields']['name2']);
            ?>
            cc.push('<div class="code-wrapper-eui business-title-wrapper-eui" title="'+rowData.<?php echo $name2; ?>+'">');
                var codeCol = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                if (typeof codeCol !== 'undefined' && codeCol != null && codeCol.formatter) {
                    cc.push(codeCol.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name2; ?>);
                }
            cc.push('</div>'); 
            
            <?php
            }
            ?>
            cc.push('<div class="image-wrapper-eui business-image-wrapper-eui">');  
                cc.push('<img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');
            cc.push('</div>');  
            cc.push('<div class="content-actions-wrapper-eui business-content-actions-wrapper-eui">');  
                cc.push('<div class="content-eui">');
                    <?php
                    if (isset($typeRow['fields']['code'])) {
                        $code = strtolower($typeRow['fields']['code']);
                    ?>
                    cc.push('<div class="code-wrapper-eui business-code-wrapper-eui" title="'+rowData.<?php echo $code; ?>+'">');
                        var codeCol = $(target).datagrid('getColumnOption', '<?php echo $code; ?>');
                        if (typeof codeCol !== 'undefined' && codeCol != null && codeCol.formatter) {
                            cc.push(codeCol.formatter(rowData.<?php echo $code; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $code; ?>);
                        }
                    cc.push('</div>'); 
                    <?php
                    }
                    if (isset($typeRow['fields']['name1'])) {
                        $name1 = strtolower($typeRow['fields']['name1']);
                    ?>
                    cc.push('<div class="name-wrapper-eui business-name-wrapper-eui">');
                        var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                        if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                            cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name1; ?>);
                        }
                    cc.push('</div>'); 
                    <?php
                    }
                    if (isset($typeRow['fields']['name3'])) {
                        $name3 = strtolower($typeRow['fields']['name3']);
                    ?>
                    cc.push('<div class="name-wrapper-eui business-name-wrapper-eui footer-wrapper-eui business-footer-wrapper-eui footer-name1" title="'+rowData.<?php echo $name3; ?>+'"><span>');
                        var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                        if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                            cc.push(name3Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name3; ?>);
                        }
                        cc.push('</span></div>'); 
                    <?php
                    }
                    if (isset($typeRow['fields']['name4'])) {
                        $name4 = strtolower($typeRow['fields']['name4']);
                    ?>
                    cc.push('<div class="footer-wrapper-eui business-footer-wrapper-eui-right footer-name2" title="'+rowData.<?php echo $name4; ?>+'">');
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
                    cc.push('<div class="footer-wrapper-eui business-footer-wrapper-eui-right footer-name3" title="'+rowData.<?php echo $name5; ?>+'">');
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
        cc.push('</div></div>');    

        cc.push('</td>');
        return cc.join('');
    }
});
<?php
}
?>