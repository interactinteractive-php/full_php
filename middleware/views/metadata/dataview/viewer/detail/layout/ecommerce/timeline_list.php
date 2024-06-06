<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['ecommerce'])) {
    $typeRow = $this->row['dataViewLayoutTypes']['ecommerce'];

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
    var ecommerceview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen, rowData, rowKey) {

        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var rows2 = rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [], rowKey = 0, groupCheck = {};

        table.push('<table class="datagrid-btable w-100 card mb-1" cellpadding="0" cellpadding="0" border="0"><tbody>');

            for (var ii = 0; ii < rows2.length; ii++) {
                var $pNumber = $(target).closest('.jeasyuiThemeecommerceView').find('input[class="pagination-num"]').val();
                var $selectedPageList = $(target).closest('.jeasyuiThemeecommerceView').find('select[class="pagination-page-list"]').val();
                
                var $pagNumber = ($pNumber == 0) ? 1 : $pNumber;
                
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
    if (isset($typeRow['fields']['name8'])) {
        $name8 = strtolower($typeRow['fields']['name8']);
        ?>
            cc.push('<td>');
                cc.push('<input type="checkbox" class="" name="" value="">');
            cc.push('</td>');
    <?php }
    if (isset($typeRow['fields']['name6'])) {
        $name6 = strtolower($typeRow['fields']['name6']);
        ?>
            cc.push('<td style="background: '+detectHtmlStr(rowData.<?php echo $name6; ?>)+'50" class="rownumber">');
                cc.push($rowNumber + '.');
            cc.push('</td>');
    <?php }
    if (isset($typeRow['fields']['name6'])) {
        $name6 = strtolower($typeRow['fields']['name6']);
        ?>
            cc.push('<td style="width: 120px;">');
                cc.push('<div class="duedate" title="'+ rowData.<?php echo $name6; ?> +'">');
                    cc.push('<div class="year d-flex align-items-center justify-content-center">');
                    cc.push('<i class="icon-calendar mr-2"></i>');
                        cc.push('<span>'+ rowData.<?php echo $name6; ?> +'<span>');
                    cc.push('</div>');
                cc.push('</div>');
            cc.push('</td>');
    <?php }
    if (isset($typeRow['fields']['basketphoto'])) { ?>
            cc.push('<td class="basketphoto" style="width: 40px;">');
        <?php
        if (isset($typeRow['fields']['basketphoto'])) {
            $basketphoto = strtolower($typeRow['fields']['basketphoto']); ?>
            var basketphotoCol = $(target).datagrid('getColumnOption', '<?php echo $basketphoto; ?>');
            if (typeof basketphotoCol !== 'undefined' && basketphotoCol != null && basketphotoCol.formatter) {
                cc.push(basketphotoCol.formatter(rowData.<?php echo $basketphoto; ?>, rowData, rowIndex));
            } else {
                cc.push(rowData.<?php echo $basketphoto; ?>);
            }
        <?php } ?>
    <?php } ?>
    cc.push('<td style="width:500px;">');
        cc.push('<div class="font-weight-bold mb3">');
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
            cc.push('</div>');
        cc.push('<div class="name1">');
            <?php
            if (isset($typeRow['fields']['name2'])) {
                $name2 = strtolower($typeRow['fields']['name2']);
                ?>
                    var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                    if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                    cc.push(name2Col.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                    } else {
                    cc.push('<span class="badge bg-grey-400 mr-2" title="'+detectHtmlStr(rowData.<?php echo $name2; ?>)+'">');
                        cc.push(rowData.<?php echo $name2; ?>);
                    cc.push('</span>');
                    }
                    <?php
                    if (isset($typeRow['fields']['name9'])) {
                        $name9 = strtolower($typeRow['fields']['name9']);
                        ?>
                        var name9Col = $(target).datagrid('getColumnOption', '<?php echo $name9; ?>');
                        if (typeof name9Col !== 'undefined' && name9Col != null && name9Col.formatter) {
                        cc.push(name9Col.formatter(rowData.<?php echo $name9; ?>, rowData, rowIndex));
                        } else {
                        cc.push('<span class="font-weight-bold mr-2" title="'+detectHtmlStr(rowData.<?php echo $name9; ?>)+'">');
                            cc.push(rowData.<?php echo $name9; ?>);
                        cc.push('</span>');
                        }
                    <?php } ?>
            <?php } ?>
            <?php
            if (isset($typeRow['fields']['name13'])) {
                $name13 = strtolower($typeRow['fields']['name13']);
                ?>
                cc.push('<a href="javascript:void(0);" title="'+detectHtmlStr(rowData.<?php echo $name13; ?>)+'">');
                    var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name13; ?>');
                    if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                    
                        var searchTxt = html_entity_decode(rowData.<?php echo $name13; ?>);
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
                        cc.push(rowData.<?php echo $name13; ?>);
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
                        cc.push('</strong></a>');
            <?php } ?>
            cc.push('</div>');           
        cc.push('</td>');

        cc.push('<td class="name3" style="width:200px;">');
        <?php if (isset($typeRow['fields']['name3'])) {
            $name3 = strtolower($typeRow['fields']['name3']); ?>
            var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
            if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                cc.push(name3Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
            } else {
                cc.push(rowData.<?php echo $name3; ?>);
            }
        <?php } ?>
        <?php
        if (isset($typeRow['fields']['name12'])) {
            $name12 = strtolower($typeRow['fields']['name12']);
            ?>
                cc.push('<td style="width: 170px;">');
                    cc.push('<label class="radio-inline">');
                        cc.push('<div class="checker">');
                            cc.push('<span>');
                                cc.push('<input type="checkbox" class="" name="" value="">');
                            cc.push('</span>');
                        cc.push('</div>');
                    cc.push('Цалинтай эсэх');
                    cc.push('</label>');
                cc.push('</td>');
        <?php } ?>
        cc.push('</td>');
        
        <?php $name14 = strtolower($typeRow['fields']['name14']); ?>
        cc.push('<td class="name15" style="width:400px;">');
        cc.push('<div class="progress-bar-show-hide" data-progress-bar="'+detectHtmlStr(rowData.<?php echo $name14; ?>)+'">');
        <?php if (isset($typeRow['fields']['name15'])) {
            $name15 = strtolower($typeRow['fields']['name15']); ?>
            cc.push('<div class="d-flex flex-row mb-1">');
                cc.push('<div class="mt15 mr-2">');
                    cc.push('<i class="icon-office mr-2 text-green"></i>');
                cc.push('</div>');
                cc.push('<div class="w-100 d-flex flex-column align-items-center">');
                    cc.push('<div style="height:22px;">');
                        cc.push('<span class="text-green font-size-14">');
                            var name15Col = $(target).datagrid('getColumnOption', '<?php echo $name15; ?>');
                            if (typeof name15Col !== 'undefined' && name15Col != null && name15Col.formatter) {
                                cc.push(name15Col.formatter(rowData.<?php echo $name15; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name15; ?>);
                            }
                        cc.push('</span>');
                    cc.push('</div>');
                    cc.push('<div class="progress w-100 mr-2" style="height: 0.500rem;">');
                        cc.push('<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: '+ rowData.<?php echo $name15; ?> +';">');
                            cc.push('<span class="sr-only">');
                            cc.push('</span>');
                        cc.push('</div>');
                    cc.push('</div>');
                cc.push('</div>');
                cc.push('<div class="mt13 ml-2 font-weight-bold font-size-16">');
                    <?php
                    if (isset($typeRow['fields']['name4'])) {
                        $name4 = strtolower($typeRow['fields']['name4']);
                        ?>
                            var name7Col = $(target).datagrid('getColumnOption', '<?php echo $name4; ?>');
                            if (typeof name7Col !== 'undefined' && name7Col != null && name7Col.formatter) {
                            cc.push(name7Col.formatter(rowData.<?php echo $name4; ?>, rowData, rowIndex));
                            } else {
                            cc.push(rowData.<?php echo $name4; ?>);
                            }
                    <?php } ?>
                cc.push('</div>');
            cc.push('</div>');
            cc.push('<div class="d-flex flex-row align-items-center">');
                cc.push('<div>');
                    cc.push('<span class="d-flex flex-row align-items-center"><i class="icon-user mr-1 text-black mr-2"></i> <p class="mb-0 font-size-16 text-black font-weight-bold">');
                        <?php
                        if (isset($typeRow['fields']['name5'])) {
                            $name5 = strtolower($typeRow['fields']['name5']);
                            ?>
                                var name7Col = $(target).datagrid('getColumnOption', '<?php echo $name5; ?>');
                                if (typeof name7Col !== 'undefined' && name7Col != null && name7Col.formatter) {
                                cc.push(name7Col.formatter(rowData.<?php echo $name5; ?>, rowData, rowIndex));
                                } else {
                                cc.push(rowData.<?php echo $name5; ?>);
                                }
                        <?php } ?>
                        cc.push('</p></span>');
                cc.push('</div>');
            cc.push('</div>');
        <?php } ?>
    cc.push('</div>');
    cc.push('</td>');
    cc.push('</div>');
    cc.push('</td>');
    <?php
    if (isset($typeRow['fields']['name11'])) {
        $name11 = strtolower($typeRow['fields']['name11']);
        ?>
        cc.push('<td style="width:200px;" class="file-icons text-center">');
            var name7Col = $(target).datagrid('getColumnOption', '<?php echo $name11; ?>');
            if (typeof name7Col !== 'undefined' && name7Col != null && name7Col.formatter) {
            cc.push(name7Col.formatter(rowData.<?php echo $name11; ?>, rowData, rowIndex));
            } else {
            cc.push(rowData.<?php echo $name11; ?>);
            }
            cc.push('</td>');
    <?php } ?>
    <?php
    if (isset($typeRow['fields']['name10'])) {
        $name10 = strtolower($typeRow['fields']['name10']);
        ?>
        cc.push('<td style="width:200px;">');
            cc.push('<span class="badge" title="'+detectHtmlStr(rowData.<?php echo $name10; ?>)+'" style="background-color: #4B8DF8 !important;">');
                var name10Col = $(target).datagrid('getColumnOption', '<?php echo $name10; ?>');
                if (typeof name10Col !== 'undefined' && name10Col != null && name10Col.formatter) {
                cc.push(name10Col.formatter(rowData.<?php echo $name10; ?>, rowData, rowIndex));
                } else {
                cc.push(rowData.<?php echo $name10; ?>);
                }
                cc.push('</span>');
            cc.push('</td>');
    <?php } ?>           
    return cc.join('');
    }});
<?php } ?>