<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['ecommerce_basket'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['ecommerce_basket'];
    
    $photoField = '';
    $defaultImage = 'assets/core/global/img/metaicon/big/'.$typeRow['DEFAULT_IMAGE'];

    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "'+rowData.".strtolower($typeRow['fields']['photo'])."+'";
    }
?>

var ecommerce_basketview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [];

        for (var i = 0; i < rows.length; i++) {
            table.push('<table class="datagrid-btable" data-row-id="'+rows[i]['id']+'" cellpadding="0" cellpadding="0" border="0"><tbody>');
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
            
        cc.push('<div class="card-wrapper-eui business-card-wrapper-eui">');
            cc.push('<div class="business-wrapper">');
                
            <?php
            if (isset($typeRow['fields']['name1'])) {
                $name1 = strtolower($typeRow['fields']['name1']);
            ?>
            cc.push('<div class="code-wrapper-eui business-title-wrapper-eui" title="'+rowData.<?php echo $name1; ?>+'">');
                var codeCol = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                if (typeof codeCol !== 'undefined' && codeCol != null && codeCol.formatter) {
                    cc.push(codeCol.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name1; ?>);
                }
            cc.push('</div>');          
            <?php
            }
            ?>
            
            cc.push('<div class="code-wrapper-eui business-title2-wrapper-eui">');
                cc.push('<i class="fas fa-heart" style="font-size:16px"></i>');
            cc.push('</div>');   
            
            cc.push('<div class="image-wrapper-eui business-image-wrapper-eui">');  
                /*cc.push('<img src="https://i5.walmartimages.com/asr/a9d2d5df-6df6-41de-9c48-693487089f47_1.37f24f4e5945e54cd674169517939a8b.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');*/
                cc.push('<img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');
            cc.push('</div>');  
            cc.push('<div class="content-actions-wrapper-eui business-content-actions-wrapper-eui">');  
                cc.push('<div class="content-eui">');
                    <?php
                    if (isset($typeRow['fields']['name2'])) {
                        $name2 = strtolower($typeRow['fields']['name2']);
                    ?>
                    cc.push('<div class="code-wrapper-eui business-code-wrapper-eui" title="'+rowData.<?php echo $name2; ?>+'">');
                        var codeCol = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                        if (typeof codeCol !== 'undefined' && codeCol != null && codeCol.formatter) {
                            cc.push(codeCol.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name2; ?>);
                        }
                    cc.push('</div>'); 
                    <?php
                    }
                    if (isset($typeRow['fields']['name3'])) {
                        $name3 = strtolower($typeRow['fields']['name3']);
                    ?>
                    cc.push('<div class="name-wrapper-eui business-name-wrapper-eui">');
                        var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                        if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                            cc.push(name1Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name3; ?>);
                        }
                    cc.push('</div>'); 
                    <?php
                    }
                    if (isset($typeRow['fields']['name4'])) {
                        $name4 = strtolower($typeRow['fields']['name4']);
                    ?>
                    
                    cc.push('<div class="name-wrapper-eui business-name-wrapper-eui footer-wrapper-eui business-footer-wrapper-eui footer-name3 d-flex justify-content-between" title="'+rowData.<?php echo $name4; ?>+'">');
                        cc.push('<span>');
                            
                        var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name4; ?>');
                        if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                            cc.push(name3Col.formatter(rowData.<?php echo $name4; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name4; ?>);
                        }
                        cc.push('</span>');
                        
                        <?php
                        if (isset($typeRow['fields']['basketqty'])) {
                            $basketqty = strtolower($typeRow['fields']['basketqty']);
                        ?>
                        cc.push('<span class="add-to-basket-dvtheme d-flex align-items-center">'); 
                        cc.push('<span class="text-basket-dvtheme d-flex align-items-center"><i class="far fa-shopping-bag mr-1" style="font-size:16px"></i> Сагс</span><i class="fa fa-minus minus-basket-dvtheme" style="font-size:16px;opacity: .75;"></i><input type="text" maxlength="3" value="1"><i class="fa fa-plus plus-basket-dvtheme" style="font-size:16px;opacity: .75;"></i>');
                        cc.push('</span>');
                        <?php
                        }
                        ?>
                        
                    cc.push('</div>');
                    
                    <?php
                    }
                    ?>

                cc.push('</div>');         
            cc.push('</div>');     
        cc.push('</div>');
        cc.push('</div>');

        cc.push('</td>');
        
        return cc.join('');
    }
});

$(document).on("click", ".add-to-basket-dvtheme", function(e){

    var $this = $(this);
    
    if (!$this.hasClass('addbasket-opened')) {
        
        $this.addClass('addbasket-opened');
        $this.closest(".footer-name3").addClass("addedbasket");
        $this.closest(".footer-name3").find("input").val(1);
        
        dataViewToBasket_<?php echo $this->metaDataId ?>(e);

        var metaDataId = '<?php echo $this->metaDataId; ?>';
        var $dialogName = 'dataViewBasket-dialog-' + metaDataId;
        var $dialog = $("#" + $dialogName);
        
        if ($dialog.is(':visible')) { 
            
            var $basketDiv = $dialog.find('[data-basketid]');
            var uniqId = $basketDiv.attr('data-basketid');
            var rowIndex = $this.closest('[datagrid-row-index]').attr('datagrid-row-index');
            var appendRow = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getRows')[rowIndex];
            
            appendRow.basketqty = 1;
            appendRow.baskettotalprice = appendRow.basketunitprice;
            
            window['objectdatagrid_'+uniqId].datagrid('appendRow', appendRow);
            
            $('#object-value-list-'+uniqId).find('.item-basket-total-qty').text(_selectedRows_<?php echo $this->metaDataId; ?>.length);
            calcSumBasketWindow_<?php echo $this->metaDataId ?>();
        }
    }
});

$(document).on("click", ".plus-basket-dvtheme", function(){
    var $this = $(this).parent().find("input");
    var addnum = Number($this.val()) + 1;
    
    $this.val(addnum);
    
    dvBasketAction_<?php echo $this->metaDataId; ?>($this, addnum);
}); 

$(document).on("click", ".minus-basket-dvtheme", function(){
    var $this = $(this);
    var qty = Number($this.parent().find("input").val());
    
    if (qty == 1) {
        return false;
    }
    
    var basketNumber = qty - 1;
    
    $this.parent().find("input").val(basketNumber);
    dvBasketAction_<?php echo $this->metaDataId; ?>($this, basketNumber);
});

$(document).on("change", ".add-to-basket-dvtheme input", function(){
    var $this = $(this);
    var qty = Number($this.val());
    if (qty < 1) {
        $this.val(1);
    }
    
    var basketNumber = Number($this.val());
    dvBasketAction_<?php echo $this->metaDataId; ?>($this, basketNumber);
});

function dvBasketAction_<?php echo $this->metaDataId; ?>($this, addnum) {
    var $realParent = $this.closest('[data-row-id]');
    var selectedRows = _selectedRows_<?php echo $this->metaDataId; ?>;
    var rowIndex = $this.closest('[datagrid-row-index]').attr('datagrid-row-index');
    
    if ($realParent.length) {
        
        var rowId = $realParent.attr('data-row-id');
        var $dialog = $('#dataViewBasket-dialog-<?php echo $this->metaDataId; ?>');
        var datagrid = objectdatagrid_<?php echo $this->metaDataId; ?>;
        var row = datagrid.datagrid('getRows')[rowIndex];
        var unitPrice = Number(row.basketunitprice);
        var basketQty = addnum;
        var basketTotalPrice = addnum * unitPrice;
        
        if ($dialog.length) {
        
            var $basketDiv = $dialog.find('[data-basketid]');
            var uniqId = $basketDiv.attr('data-basketid');
            var basketRows = window['objectdatagrid_'+uniqId].datagrid('getRows');
            var updateRow = {};
            
            updateRow['basketqty'] = basketQty;
            updateRow['baskettotalprice'] = basketTotalPrice;
            
            for (var b in basketRows) {
                if (basketRows[b]['id'] == rowId) {
                    window['objectdatagrid_'+uniqId].datagrid('updateRow', {
                        index: b,
                        row: updateRow
                    });
                    break;
                }
            }
            
            calcSumBasketWindow_<?php echo $this->metaDataId ?>();
        }
        
    } else {
        var $basketDiv = $this.closest('[data-basketid]');
        var uniqId = $basketDiv.attr('data-basketid');
        var datagrid = window['objectdatagrid_'+uniqId];
        var basketRows = datagrid.datagrid('getRows');
        var $objectdatagrid = $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>');
        var row = basketRows[rowIndex];
        var rowId = row.id;
        var unitPrice = Number(row.basketunitprice);
        var basketQty = addnum;
        var basketTotalPrice = addnum * unitPrice;
        var updateRow = {};
            
        updateRow['basketqty'] = basketQty;
        updateRow['baskettotalprice'] = basketTotalPrice;

        window['objectdatagrid_'+uniqId].datagrid('updateRow', {
            index: rowIndex,
            row: updateRow
        });

        $objectdatagrid.find('[data-row-id="'+rowId+'"]').find('input').val(addnum);
        
        calcSumBasketWindow_<?php echo $this->metaDataId ?>();
    }
    
    for (var s in selectedRows) {
        if (selectedRows[s]['id'] == rowId) {
            _selectedRows_<?php echo $this->metaDataId; ?>[s]['basketqty'] = basketQty;
            _selectedRows_<?php echo $this->metaDataId; ?>[s]['baskettotalprice'] = basketTotalPrice;
            break;
        }
    }
}

<?php
}
?>