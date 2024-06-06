<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['datalist'])) {
    $typeRow = $this->row['dataViewLayoutTypes']['datalist'];

    $photoField = '';
    $groupField = '';
    $photoCircleField = '';
    $name6Field = '';
    $defaultImage = 'assets/core/global/img/metaicon/big/125.png';

    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "'+rowData." . strtolower($typeRow['fields']['photo']) . "+'";
    }
    if (isset($typeRow['fields']['photocircle']) && $typeRow['fields']['photocircle'] != '') {
        $photoCircleField = "'+rowData." . strtolower($typeRow['fields']['photocircle']) . "+'";
    }
    if (isset($typeRow['fields']['groupName']) && $typeRow['fields']['groupName'] != '') {
        $groupField = strtolower($typeRow['fields']['groupName']);
    }
    ?>

    var $rowNumber = 1, $counter = 2;
    var datalistview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen, rowData, rowKey) {

    var data = $.data(target, "datagrid");
    var rows = data.data.rows;
    var rows2 = rows;
    var fields = $(target).datagrid("getColumnFields", frozen);
    var cls = "class=\"datagrid-row\"";
    var table = [], rowKey = 0, groupCheck = {};

    table.push('<table class="datagrid-btable w-100 card mb-1" cellpadding="0" cellpadding="0" border="0"><tbody>');
            for (var ii = 0; ii < rows2.length; ii++) {
            var $pagNumber = $(target).closest('.jeasyuiThemeecommerceView').find('input[class="pagination-num"]').val();
            var $selectedPageList = $(target).closest('.jeasyuiThemeecommerceView').find('select[class="pagination-page-list"]').val();

            if ($counter > $pagNumber && ii == 0) {
            $rowNumber = ($pagNumber - 1) * $selectedPageList +1;    
            $counter++;
            }
            else {
            if ($counter == $pagNumber) {
            $rowNumber = ($pagNumber - 1) * $selectedPageList +1;    
            $counter++;
            }
            }

            table.push("<tr datagrid-row-index=\"" + ii + "\" " + cls + ">");
                            table.push(this.renderRow.call(this, target, fields, frozen, rowKey, rows2[ii]));
                            table.push("</tr>");            

            $rowNumber++;
            rowKey++;
            }
            table.push('</tbody></table>');

    $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData, rowKey) {
    var cc = [];
    <?php
    if (isset($typeRow['fields']['name6'])) {
        $name6 = strtolower($typeRow['fields']['name6']);
        ?>
    <?php } ?>

    <?php
    if (isset($typeRow['fields']['name6'])) {
        $name6 = strtolower($typeRow['fields']['name6']);
        ?>
        cc.push('<td style="width: 100px;text-align:center;font-size: 15px;background: '+detectHtmlStr(rowData.<?php echo $name6; ?>)+'50" class="font-weight-bold rownumber">');
            cc.push($rowNumber + '.');
            cc.push('</td>');
    <?php } ?>
    cc.push('<td>');
        cc.push('<div class="font-weight-bold">');
            <?php
            if (isset($typeRow['fields']['name1'])) {
                $name1 = strtolower($typeRow['fields']['name1']);
                ?>
                cc.push('<a href="javascript:void(0);" style="text-transform: none;font-size: 15px;" title="'+detectHtmlStr(rowData.<?php echo $name1; ?>)+'">');
                    var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                    if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                    cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                    } else {
                    cc.push(rowData.<?php echo $name1; ?>);
                    }
                    cc.push('</a>');
            <?php } ?>
            cc.push('</div>');
        cc.push('<div class="name1">');
            <?php
            if (isset($typeRow['fields']['name2'])) {
                $name2 = strtolower($typeRow['fields']['name2']);
                ?>
                cc.push('<div href="javascript:void(0);" title="'+detectHtmlStr(rowData.<?php echo $name2; ?>)+'">');
                    var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                    if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                    cc.push(name2Col.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                    } else {
                    cc.push(rowData.<?php echo $name2; ?>);
                    }
                    cc.push(' - <b>');
                        <?php
                        if (isset($typeRow['fields']['name9'])) {
                            $name9 = strtolower($typeRow['fields']['name9']);
                            ?>
                            var name9Col = $(target).datagrid('getColumnOption', '<?php echo $name9; ?>');
                            if (typeof name9Col !== 'undefined' && name9Col != null && name9Col.formatter) {
                            cc.push(name9Col.formatter(rowData.<?php echo $name9; ?>, rowData, rowIndex));
                            } else {
                            cc.push(rowData.<?php echo $name9; ?>);
                            }
                        <?php } ?>
                        cc.push('</b></a>');
            <?php } ?>
            <?php
            if (isset($typeRow['fields']['name13'])) {
                $name2 = strtolower($typeRow['fields']['name13']);
                ?>
                cc.push('<a href="javascript:void(0);" title="'+htmlToStr(rowData.<?php echo $name2; ?>)+'" style="float: left;width: 88%;">');
                    var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                    if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                    
                        var searchTxt = html_entity_decode(rowData.<?php echo $name2; ?>);
                        searchTxt = searchTxt.replace(/<\/?[^>]+(>|$)/g, "");
                        var sText = $('input[name="param[highlightSearch]"]').length ? $('input[name="param[highlightSearch]"]').val() : '',
                            n = searchTxt.toLowerCase().search(sText.toLowerCase());
                        
                        if (sText != '' && n > 0) {                            
                            var lastText = searchTxt.substr(n + sText.length, 80), 
                                prevText = '';

                            if (n > 80) {
                                prevText = searchTxt.substr(n - 80, n);
                            } else {
                                prevText = searchTxt.substr(0, n).trim();
                            }
                            searchTxt = prevText + ' ' + sText + ' ' + lastText;                        
                        }
                        
                        if (sText == '' || n === -1) {
                            searchTxt = searchTxt.substr(0, 200);
                        } else {
                            var re = new RegExp("(" + sText + ")", "gi")
                            searchTxt = searchTxt.replace(re, '<span style="font-weight: 600; background-color: #12ff51; color: #000">' + sText + '</span>');
                        }
                        
                        cc.push(name2Col.formatter('<span class="label-sm" style="color:#545454; font-weight: 400; font-size: 0.720rem; font-family: Roboto Condensed">'+searchTxt+'</span>', rowData, rowIndex));
                    } else {    
                        var searchTxt = html_entity_decode(rowData.<?php echo $name2; ?>);
                        searchTxt = searchTxt.replace(/<\/?[^>]+(>|$)/g, "");
                        cc.push('<span class="label-sm" style="color:#545454;font-weight: 400;font-size: 0.720rem;font-family: Roboto Condensed;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;overflow: hidden;">'+searchTxt+'</span>');
                    }
                    cc.push(' - <strong>');
                        <?php
                        if (isset($typeRow['fields']['name9'])) {
                            $name9 = strtolower($typeRow['fields']['name9']);
                            ?>
                            var name9Col = $(target).datagrid('getColumnOption', '<?php echo $name9; ?>');
                            if (typeof name9Col !== 'undefined' && name9Col != null && name9Col.formatter) {
                            cc.push(name9Col.formatter(rowData.<?php echo $name9; ?>, rowData, rowIndex));
                            } else {
                            cc.push(rowData.<?php echo $name9; ?>);
                            }
                        <?php } ?>
                        cc.push('</strong>');
                        cc.push('</div>');
            <?php } ?>
            <?php
                if (isset($typeRow['fields']['name10'])) {
                    $name10 = strtolower($typeRow['fields']['name10']);
                    ?>
                    cc.push('<div class="custom_file" style="float: left; width: 12%; ">');
                        var name10Col = $(target).datagrid('getColumnOption', '<?php echo $name10; ?>');
                        if (typeof name10Col !== 'undefined' && name10Col != null && name10Col.formatter) {
                        cc.push(name10Col.formatter(rowData.<?php echo $name10; ?>, rowData, rowIndex));
                        } else {
                        cc.push(rowData.<?php echo $name10; ?>);
                        }
                        cc.push('</div>');
                <?php } ?> 
            cc.push('</div>');
                   
        cc.push('</td>');
        <?php
        if (isset($typeRow['fields']['name5'])) {
            $name5 = strtolower($typeRow['fields']['name5']);
            ?>
        cc.push('<td style="width:150px;">');    
            cc.push('<a href="javascript:void(0);" style="text-transform: uppercase;font-size:12px;color:#000;" class="font-weight-bold">');
                var name5Col = $(target).datagrid('getColumnOption', '<?php echo $name5; ?>');
                if (typeof name5Col !== 'undefined' && name5Col != null && name5Col.formatter) {
                cc.push(name5Col.formatter(rowData.<?php echo $name5; ?>, rowData, rowIndex));
                } else {
                cc.push(rowData.<?php echo $name5; ?>);
                }
                cc.push('</a>');
        cc.push('</td>');        
        <?php } ?>
    cc.push('</div>');
    cc.push('</td>');
        <?php
        if (isset($typeRow['fields']['photo'])) {
            $photo = strtolower($typeRow['fields']['photo']); 
        ?>
        cc.push('<td class="photo" style="width:130px;">');
            var photoCol = $(target).datagrid('getColumnOption', '<?php echo $photo; ?>');
            if (typeof photoCol !== 'undefined' && photoCol != null && photoCol.formatter) {
                cc.push(photoCol.formatter(rowData.<?php echo $photo; ?>, rowData, rowIndex));
            } else {
                cc.push(rowData.<?php echo $photo; ?>);
            }
        cc.push('</td>');    
    <?php } ?>
    
    <?php
    if (isset($typeRow['fields']['name11'])) {
        $name11 = strtolower($typeRow['fields']['name11']);
        ?>
        cc.push('<td style="width:130px;" class="pl-0 pr-0">');
            var name7Col = $(target).datagrid('getColumnOption', '<?php echo $name11; ?>');
            if (typeof name7Col !== 'undefined' && name7Col != null && name7Col.formatter) {
            cc.push(name7Col.formatter(rowData.<?php echo $name11; ?>, rowData, rowIndex));
            } else {
            cc.push(rowData.<?php echo $name11; ?>);
            }
            cc.push('</td>');
    <?php } ?>
    
    <?php
    if (isset($typeRow['fields']['name12'])) {
        $name12 = strtolower($typeRow['fields']['name12']);
        ?>
        cc.push('<td style="width:55px;" class="pl-0 pr-0">');
            var name12Col = $(target).datagrid('getColumnOption', '<?php echo $name12; ?>');
            if (typeof name12Col !== 'undefined' && name12Col != null && name12Col.formatter) {
            cc.push(name12Col.formatter(rowData.<?php echo $name12; ?>, rowData, rowIndex));
            } else {
            cc.push(rowData.<?php echo $name12; ?>);
            }
            cc.push('</td>');
    <?php } ?>

    <?php
    if (isset($typeRow['fields']['name7'])) {
        $name7 = strtolower($typeRow['fields']['name7']);
        ?>
        cc.push('<td style="width:55px;" class="pl-0 pr-0">');
            var name7Col = $(target).datagrid('getColumnOption', '<?php echo $name7; ?>');
            if (typeof name7Col !== 'undefined' && name7Col != null && name7Col.formatter) {
            cc.push(name7Col.formatter(rowData.<?php echo $name7; ?>, rowData, rowIndex));
            } else {
            cc.push(rowData.<?php echo $name7; ?>);
            }
            cc.push('</td>');
    <?php } ?>
    <?php
    if (isset($typeRow['fields']['name8'])) {
        $name8 = strtolower($typeRow['fields']['name8']);
        ?>
        cc.push('<td style="width:55px;" class="pl-0 pr-0">');
            var name8Col = $(target).datagrid('getColumnOption', '<?php echo $name8; ?>');
            if (typeof name8Col !== 'undefined' && name8Col != null && name8Col.formatter) {
            cc.push(name8Col.formatter(rowData.<?php echo $name8; ?>, rowData, rowIndex));
            } else {
            cc.push(rowData.<?php echo $name8; ?>);
            }
            cc.push('</td>');
    <?php } ?>
    return cc.join('');
    }});
<?php } ?>