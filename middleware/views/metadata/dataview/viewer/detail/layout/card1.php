<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['card1'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['card1'];
    
    $photoField = '';
    $defaultImage = 'assets/core/global/img/metaicon/big/'.$typeRow['DEFAULT_IMAGE'];

    if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
        $photoField = "'+rowData.".strtolower($typeRow['fields']['photo'])."+'";
    }
?>
var card1view_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
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
        /*$(container).prev().remove();*/
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
        cc.push('<td>');
            
        cc.push('<div class="card-wrapper-eui">');  
            <?php
            if (isset($typeRow['fields']['name2'])) {
                $name2 = strtolower($typeRow['fields']['name2']);
                if ($name2 == 'wfmstatusname') {
            ?>
            cc.push('<div class="row" style="height: 1px; width: 1px; float: right; margin-right: 0;"><div class="col-md-12" style="top: -1px; right: 12px;"><div class="btn-group float-right">');
                cc.push('<button type="button" class="btn btn-circle btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" data-close-others="true" onclick="changeWfmStatusByRow_<?php echo $this->metaDataId; ?>(this);">');
                cc.push('</button>');
                cc.push('<ul class="dropdown-menu float-right" role="menu"></ul>');
            cc.push('</div></div></div>');
            
            cc.push('<div class="name2-wrapper-eui" title="'+rowData.wfmstatusname+'">');
                cc.push('<span class="badge label-sm cursor-pointer" style="background-color: '+rowData.wfmstatuscolor+'" onclick="dataViewWfmStatusFlowViewer(this, \''+rowData.id+'\', \''+rowData.wfmstatusid+'\', \''+rowData.wfmstatusname+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+rowData.wfmstatuscolor+'\');"><i class="far fa-cogs"></i> '+rowData.wfmstatusname+'</span>');
            cc.push('</div>'); 
            <?php
                } else {
            ?>
            cc.push('<div class="code-wrapper-eui" title="'+rowData.<?php echo $name2; ?>+'">');
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
            
            <?php
            }
            ?>
            cc.push('<div class="image-wrapper-eui">');  
                cc.push('<img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');
            cc.push('</div>');  
            cc.push('<div class="content-actions-wrapper-eui">');  
                cc.push('<div class="content-eui">');
                    <?php
                    if (isset($typeRow['fields']['code'])) {
                        $code = strtolower($typeRow['fields']['code']);
                    ?>
                    cc.push('<div class="code-wrapper-eui" title="'+rowData.<?php echo $code; ?>+'">');
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
                    cc.push('<div class="name-wrapper-eui code" title="'+rowData.<?php echo $name1; ?>+'">');
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
                    cc.push('<div class="name-wrapper-eui footer-wrapper-eui" title="'+rowData.<?php echo $name3; ?>+'"><i class="icon-calendar mr5"></i>');
                        var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                        if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                            cc.push(name3Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name3; ?>);
                        }
                        
                        <?php
                        if (isset($typeRow['fields']['name4'])) {
                            $name4 = strtolower($typeRow['fields']['name4']);
                        ?>
                        cc.push('<div class="footer-wrapper-eui-right" title="'+rowData.<?php echo $name4; ?>+'">');
                            var name4Col = $(target).datagrid('getColumnOption', '<?php echo $name4; ?>');
                            if (typeof name4Col !== 'undefined' && name4Col != null && name4Col.formatter) {
                                cc.push(name4Col.formatter(rowData.<?php echo $name4; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name4; ?>);
                            }
                        cc.push('</div>'); 
                        <?php
                        }
                        ?>
                    cc.push('</div>'); 
                    <?php
                    }
                    if (isset($typeRow['fields']['name5'])) {
                        $name1 = strtolower($typeRow['fields']['name5']);
                    ?>
                    cc.push('<div class="name5-wrapper-eui" title="'+rowData.<?php echo $name1; ?>+'">');
                        var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                        if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                            cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                        } else {
                            cc.push(rowData.<?php echo $name1; ?>);
                        }
                    cc.push('</div>'); 
                    <?php
                    }                    
                    if (isset($typeRow['fields']['name6'])) {
                        $name1 = strtolower($typeRow['fields']['name6']);
                        ?>
                        cc.push('<div class="name5-wrapper-eui" title="">');
                            var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                            if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                            } else {
                                cc.push(rowData.<?php echo $name1; ?>);
                            }
                            <?php
                            if (isset($typeRow['fields']['name7'])) {
                                $name1 = strtolower($typeRow['fields']['name7']);
                                ?>
                                cc.push('<span class="name5-wrapper-eui" title="">');
                                    var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                                    if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                        cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                                    } else {
                                        cc.push(rowData.<?php echo $name1; ?>);
                                    }
                                cc.push('</span>'); 
                                <?php
                            }                   
                            if (isset($typeRow['fields']['name8'])) {
                                $name1 = strtolower($typeRow['fields']['name8']);
                                ?>
                                cc.push('<span class="name5-wrapper-eui" title="">');
                                    var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                                    if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                                        cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                                    } else {
                                        cc.push(rowData.<?php echo $name1; ?>);
                                    }
                                cc.push('</span>'); 
                                <?php
                            } ?>                                  
                        cc.push('</div>'); 
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