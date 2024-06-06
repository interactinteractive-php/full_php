<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['ecommerce'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['ecommerce'];
    
    $photoField = '';
    $groupField = '';
    $photoCircleField = '';
    $defaultImage = 'assets/core/global/img/metaicon/big/125.png';

    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "'+rowData.".strtolower($typeRow['fields']['photo'])."+'";
    }
    if (isset($typeRow['fields']['photocircle']) && $typeRow['fields']['photocircle'] != '') {
        $photoCircleField = "'+rowData.".strtolower($typeRow['fields']['photocircle'])."+'";
    }
    if (isset($typeRow['fields']['groupName']) && $typeRow['fields']['groupName'] != '') {
        $groupField = strtolower($typeRow['fields']['groupName']);
    } elseif (issetParam($this->dataGridOptionData['GROUPFIELD']) != '') {
        $groupField = strtolower($this->dataGridOptionData['GROUPFIELD']);
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
            
            cc.push('<div class="card-wrapper-eui card-wrapper-eui-locker">');   
            cc.push('<div class="content-actions-wrapper-eui">');
                cc.push('<div class="content-eui">');
                    <?php
                    if (isset($typeRow['fields']['name6'])) {
                        $name6 = strtolower($typeRow['fields']['name6']);
                    ?>
                    cc.push('<div class="name-wrapper-eui right-13 name6" title="'+detectHtmlStr(rowData.<?php echo $name6; ?>)+'">');
                        var name6Col = $(target).datagrid('getColumnOption', '<?php echo $name6; ?>');
                        if (typeof name6Col !== 'undefined' && name6Col != null && name6Col.formatter) {
                            cc.push(name6Col.formatter(rowData.<?php echo $name6; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name6; ?>);
                        }
                    cc.push('</div>'); 
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($typeRow['fields']['name1'])) {
                        $name1 = strtolower($typeRow['fields']['name1']);
                    ?>
                    cc.push('<div class="name-wrapper-eui" title="'+detectHtmlStr(rowData.<?php echo $name1; ?>)+'">');
                        var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                        if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                            cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name1; ?>);
                        }
                    cc.push('</div>'); 
                    <?php
                    } 
                    if (empty($photoCircleField) && $photoField) {
                    ?>
                        cc.push('<div class="image-wrapper-eui">');  
                            var photoCol = $(target).datagrid('getColumnOption', '<?php echo strtolower($typeRow['fields']['photo']); ?>');
                            if (typeof photoCol !== 'undefined' && photoCol != null && photoCol.formatter) {                                
                                cc.push(photoCol.formatter('<img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>', rowData, rowIndex, 'ecommerce_cart'));
                            } else {                    
                                cc.push('<img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');
                            }
                        cc.push('</div>');                      
                    <?php
                    } elseif ($photoCircleField) { 
                    ?>
                        cc.push('<div class="image-wrapper-eui">');  
                            var photoCol = $(target).datagrid('getColumnOption', '<?php echo strtolower($typeRow['fields']['photocircle']); ?>');
                            if (typeof photoCol !== 'undefined' && photoCol != null && photoCol.formatter) {
                                cc.push(photoCol.formatter('<img src="<?php echo $photoCircleField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>', rowData, rowIndex));
                            } else {                        
                                cc.push('<img src="<?php echo $photoCircleField; ?>" class="rounded-circle" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');
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
                    cc.push('<div class="" title="'+detectHtmlStr(rowData.<?php echo $name3; ?>)+'">');
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
                    cc.push('<div class="" title="'+detectHtmlStr(rowData.<?php echo $name4; ?>)+'">');
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
                    cc.push('<div class="position-wrapper-eui name5 tuluv" title="'+detectHtmlStr(rowData.<?php echo $name5; ?>)+'">');
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
                    cc.push('<div class="basket-push" title=""><a href="javascript:;" onclick="pushCommerceBasket<?php echo $this->metaDataId; ?>(this)" data-row-data="' + encodeURIComponent(JSON.stringify(rowData)) + '" class="btn btn-icon-only purple"><span aria-hidden="true" class="icon-bag"></span></a>');
                    cc.push('</div>');
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