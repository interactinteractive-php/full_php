<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['card_collaterial'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['card_collaterial'];
    
    $photoField = '';
    $photoPath = '';
    $defaultImage = 'assets/core/global/img/noimage.png';

    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "'+URL_APP+rowData.".strtolower($typeRow['fields']['photo'])."+'";
        $photoPath = strtolower($typeRow['fields']['photo']);
    }
?>
var business_collaterialview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
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
            
        cc.push('<div class="card-collaterial-wrapper-eui business-collaterial-card-collaterial-wrapper-eui"><div class="">');  
            cc.push('<div class="image-wrapper-eui business-image-wrapper-eui" style="height:<?php echo isset($typeRow['fields']['code']) || isset($typeRow['fields']['name1']) ? '130px' : '280px'; ?>">');  
            <?php if (!empty($photoPath)) { ?>
                var lowerExtension = rowData.<?php echo $photoPath; ?> ? rowData.<?php echo $photoPath; ?>.split('.').pop().toLowerCase() : '';
                if (['pdf', 'doc', 'docx', 'xls', 'xlsx'].indexOf(lowerExtension) !== -1) {
                    var iconClass = '';
                    if (lowerExtension == 'pdf') {
                        iconClass = 'assets/core/global/img/filetype/64/pdf.png';
                    } else if (lowerExtension == 'xls' || lowerExtension == 'xlsx') {
                        iconClass = 'assets/core/global/img/filetype/64/xls.png';
                    } else if (lowerExtension == 'ppt' || lowerExtension == 'pptx') {
                        iconClass = 'assets/core/global/img/filetype/64/ppt.png';
                    } else if (lowerExtension == 'doc' || lowerExtension == 'docx') {
                        iconClass = 'assets/core/global/img/filetype/64/doc.png';
                    }
                    cc.push('<a href="javascript:;" onclick="dataViewFileViewer(this, \'' + 1 + '\', \'' + lowerExtension + '\', \'<?php echo $photoField; ?>\', \'<?php echo $photoField; ?>\', \'\');"><img class="mt88" src="'+iconClass+'" onerror="onDataViewImgError(this);"/></a>');
                } else {
                    cc.push('<a href="<?php echo $photoField; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" width="210px" height="<?php echo isset($typeRow['fields']['code']) || isset($typeRow['fields']['name1']) ? '130px' : '280px'; ?>" onerror="onDataViewImgError(this);"/></a>');
                }                
            <?php } else { ?>            
                cc.push('<a href="<?php echo $photoField; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" width="210px" height="<?php echo isset($typeRow['fields']['code']) || isset($typeRow['fields']['name1']) ? '130px' : '280px'; ?>" onerror="onDataViewImgError(this);"/></a>');
            <?php } ?>
            cc.push('</div>');  
            <?php if (isset($typeRow['fields']['code']) || isset($typeRow['fields']['name1']) || isset($typeRow['fields']['name5'])) { ?>
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
                    ?>
                    
                    cc.push('<div class="date-wrapper-eui mt10">');
                        <?php
                        if (isset($typeRow['fields']['name1'])) {
                            $name1 = strtolower($typeRow['fields']['name1']);
                        ?>
                        cc.push('<span class="float-left" title="'+rowData.<?php echo $name1; ?>+'"><i class="fa fa-calendar"></i> ');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name1; ?>);
                            }
                        cc.push('</span>'); 
                        <?php
                        }
                        if (isset($typeRow['fields']['name3'])) {
                            $name3 = strtolower($typeRow['fields']['name3']);
                        ?>
                        cc.push('<span class="float-right ml10" title="'+rowData.<?php echo $name3; ?>+'"><i class="fa fa-eye"></i> ');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name3; ?>);
                            }
                        cc.push('</span>'); 
                        <?php
                        }
                        if (isset($typeRow['fields']['name2'])) {
                            $name2 = strtolower($typeRow['fields']['name2']);
                        ?>
                        cc.push('<span class="float-right" title="'+rowData.<?php echo $name2; ?>+'"><i class="fa fa-comment"></i> ');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name2; ?>);
                            }
                        cc.push('</span>'); 
                        <?php
                        }
                        ?>
                    cc.push('</div>'); 
                    
                    cc.push('<div class="clearfix w-100"></div><div class="name4-5-wrapper-eui" title="">');
                        <?php
                        if (isset($typeRow['fields']['name4'])) {
                            $name4 = strtolower($typeRow['fields']['name4']);
                        ?>
                        cc.push('<div class="name4-wrapper-eui" title="'+rowData.<?php echo $name4; ?>+'">');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name4; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name4; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name4; ?>);
                            }
                        cc.push('</div>'); 
                        <?php
                        }
                        if (isset($typeRow['fields']['name5'])) {
                            $name5 = strtolower($typeRow['fields']['name5']);
                        ?>
                        cc.push('<div class="clearfix w-100"></div><div class="name5-wrapper-eui" title="'+rowData.<?php echo $name5; ?>+'">');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name5; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name5; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name5; ?>);
                            }
                        cc.push('</div>'); 
                        <?php
                        }
                        ?>                   
                    cc.push('</div>'); 
                    
                    cc.push('<div class="clearfix w-100"></div><div class="date-wrapper-eui float-right" style="margin-top: 25px;">');
                        <?php
                        if (isset($typeRow['fields']['name6'])) {
                            $name6 = strtolower($typeRow['fields']['name6']);
                        ?>
                        cc.push('<span class="" title="'+rowData.<?php echo $name6; ?>+'">');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name6; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name6; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name6; ?>);
                            }
                        cc.push('</span>'); 
                        <?php
                        }
                        if (isset($typeRow['fields']['name7'])) {
                            $name7 = strtolower($typeRow['fields']['name7']);
                        ?>
                        cc.push('<span class="ml5" title="'+rowData.<?php echo $name7; ?>+'">');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name7; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name7; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name7; ?>);
                            }
                        cc.push('</span>'); 
                        <?php
                        }
                        if (isset($typeRow['fields']['name8'])) {
                            $name8 = strtolower($typeRow['fields']['name8']);
                        ?>
                        cc.push('<span class="ml15" title="'+rowData.<?php echo $name8; ?>+'">');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name8; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name8; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name8; ?>);
                            }
                        cc.push('</span>'); 
                        <?php
                        }
                        if (isset($typeRow['fields']['name9'])) {
                            $name9 = strtolower($typeRow['fields']['name9']);
                        ?>
                        cc.push('<span class="ml5" title="'+rowData.<?php echo $name9; ?>+'">');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name9; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name9; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name9; ?>);
                            }
                        cc.push('</span>'); 
                        <?php
                        }
                        ?>
                    cc.push('</div>');                     
                    
                cc.push('</div>');         
            cc.push('</div>');    
            <?php } ?> 
        cc.push('</div></div>');    

        cc.push('</td>');
        return cc.join('');
    }
});
<?php
}
?>