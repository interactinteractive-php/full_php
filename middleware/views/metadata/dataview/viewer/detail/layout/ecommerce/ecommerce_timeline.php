<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['ecommerce'])) {
    $typeRow = $this->row['dataViewLayoutTypes']['ecommerce'];
    $photoField = '';
    $photoCircleField = '';
    $defaultImage = 'assets/core/global/img/metaicon/big/125.png';
    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "'+rowData.".strtolower($typeRow['fields']['photo'])."+'";
    }
    if (isset($typeRow['fields']['photocircle']) && $typeRow['fields']['photocircle'] != '') {
        $photoCircleField = "'+rowData.".strtolower($typeRow['fields']['photocircle'])."+'";
    } ?>
var ecommerceview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"\"";
        <!-- var cls = "class=\"datagrid-row\""; -->
        var table = [];

        for (var i = 0; i < rows.length; i++) {
            table.push("<div class='card'>");
            table.push('<table class="w-100" cellpadding="0" cellpadding="0" border="0"><tbody>');
            <!-- table.push("<tr datagrid-row-index=\"" + i + "\" " + cls + ">"); -->
            table.push("<tr datagrid-row-index=\"" + i + "\" data-rowdata=\"" + encodeURIComponent(JSON.stringify(rows[i])) + "\">");
            table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
            table.push("</tr>");
            table.push('</tbody></table>');
            table.push("</div> ");
        }
        /*$(container).prev().remove();*/
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
        cc.push('<td>');
        cc.push('<div class="card-content border-left-3 border-left-gray rounded-left-0 border-top-0 border-right-0 border-bottom-0" style="border-color:'+ (typeof rowData.rowcolor2 !== 'undefined' ? rowData.rowcolor2 : 'transparent') +'">');
        cc.push('<div class="card-body" style="background-color:'+ (typeof rowData.rowcolor !== 'undefined' ? rowData.rowcolor : 'transparent') +'">');
        cc.push('<div>');
        cc.push('<div>');
        cc.push('<div class="d-sm-flex justify-content-start align-items-sm-center">');
        cc.push('<img src="<?php echo $photoCircleField; ?>" class="rounded-circle mr-2" width="36" height="36" data-default-image="assets/custom/img/user.png" onerror="onDataViewImgError(this);" alt="">');
        cc.push('<div>');
        cc.push('<h6 class="mb0">');
        <?php
            if (isset($typeRow['fields']['name1'])) {
                $name1 = strtolower($typeRow['fields']['name1']);
            ?>
            cc.push('<a href="javascript:void(0);" title="'+detectHtmlStr(rowData.<?php echo $name1; ?>)+'">');
                var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                    cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name1; ?>);
                }
            cc.push('</a>'); 
        <?php } ?>
        cc.push('</h6>');
        <?php
            if (isset($typeRow['fields']['name5'])) {
                $name5 = strtolower($typeRow['fields']['name5']);
            ?>
            cc.push('<span class="text-muted ml-auto name3" title="'+detectHtmlStr(rowData.<?php echo $name5; ?>)+'"><strong>');
                var name5Col = $(target).datagrid('getColumnOption', '<?php echo $name5; ?>');
                if (typeof name5Col !== 'undefined' && name5Col != null && name5Col.formatter) {
                    cc.push(name5Col.formatter(rowData.<?php echo $name5; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name5; ?>);
                }
            cc.push('</strong> &#8226;</span> ');
        <?php } ?>
        <?php
            if (isset($typeRow['fields']['name3'])) {
                $name3 = strtolower($typeRow['fields']['name3']);
            ?>
            cc.push('<span class="text-muted ml-auto name3" title="'+detectHtmlStr(rowData.<?php echo $name3; ?>)+'">');
                var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                    cc.push(name3Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name3; ?>);
                }
            cc.push('</span>');
        <?php } ?>
        <?php
            if (isset($typeRow['fields']['name4'])) {
                $name4 = strtolower($typeRow['fields']['name4']);
            ?>
            cc.push('<span class="text-muted ml-auto name4" title="'+detectHtmlStr(rowData.<?php echo $name4; ?>)+'">');
                var name4Col = $(target).datagrid('getColumnOption', '<?php echo $name4; ?>');
                if (typeof name4Col !== 'undefined' && name4Col != null && name4Col.formatter) {
                    cc.push(name4Col.formatter(rowData.<?php echo $name4; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name4; ?>);
                }
            cc.push('</span>');
        <?php } ?>
        cc.push('</div>');
        <?php
            if (isset($typeRow['fields']['date'])) {
                $date = strtolower($typeRow['fields']['date']);
            ?>
            cc.push('<span class="text-muted ml-auto" title="'+detectHtmlStr(rowData.<?php echo $date; ?>)+'">');
                var dateCol = $(target).datagrid('getColumnOption', '<?php echo $date; ?>');
                if (typeof dateCol !== 'undefined' && dateCol != null && dateCol.formatter) {
                    cc.push(dateCol.formatter(rowData.<?php echo $date; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $date; ?>);
                }
            cc.push('</span>'); 
        <?php } ?>
        cc.push('</div>');
        <?php
            if (isset($typeRow['fields']['descr'])) {
                $descr = strtolower($typeRow['fields']['descr']);
            ?>
            cc.push('<p class="mt-2 descr" title="'+detectHtmlStr(rowData.<?php echo $descr; ?>)+'">');
                var descrCol = $(target).datagrid('getColumnOption', '<?php echo $descr; ?>');
                if (typeof descrCol !== 'undefined' && descrCol != null && descrCol.formatter) {
                    cc.push(descrCol.formatter(rowData.<?php echo $descr; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $descr; ?>);
                }
            cc.push('</p>');
        <?php } ?>
        cc.push('</div>');
        cc.push('</div>');
        cc.push('</div>');
        cc.push('<div class="card-footer d-sm-flex justify-content-sm-start align-items-sm-center" style="background-color:'+ (typeof rowData.rowcolor1 !== 'undefined' ? rowData.rowcolor1 : 'transparent') +'">');
        <?php if (isset($typeRow['fields']['name2'])) {
            $name2 = strtolower($typeRow['fields']['name2']); ?>
            cc.push('<div class="name-wrapper-eui" title="'+detectHtmlStr(rowData.<?php echo $name2; ?>)+'">');
                var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                    cc.push(name2Col.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                    <?php
                        if (isset($typeRow['fields']['name6'])) {
                            $name6 = strtolower($typeRow['fields']['name6']);
                        ?>             
                        cc.push('<button type="button" class="btn btn-xs">');
                        rowData.<?php echo $name6; ?> ? cc.push(decodeURIComponent(rowData.<?php echo $name6; ?>)) : '';
                        cc.push('</button>');
                    <?php } ?>
                } else {
                    cc.push(rowData.<?php echo $name2; ?>);
                }
            cc.push('</div>');
        <?php } ?>
        cc.push('</div>');
        cc.push('</div>');
        cc.push('</td>');
        return cc.join('');
    }});
<?php } ?>