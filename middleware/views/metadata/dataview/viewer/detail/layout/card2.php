<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['card2'])) {
    
    $fields = $this->row['dataViewLayoutTypes']['card2']['fields'];
    
    $logo = strtolower(issetParam($fields['photo']));
    $name1Field = strtolower(issetParam($fields['name1']));
    $name2Field = strtolower(issetParam($fields['name2']));
    $name3Field = strtolower(issetParam($fields['name3']));
    $name4Field = strtolower(issetParam($fields['name4']));
    $name6Field = strtolower(issetParam($fields['name6']));
    $name7Field = strtolower(issetParam($fields['name7']));
    $name8Field = strtolower(issetParam($fields['name8']));
    $name9Field = strtolower(issetParam($fields['name9']));
                    
    $name3_labelname = Lang::line(issetParam($fields['name3_labelname']));
    $name4_labelname = Lang::line(issetParam($fields['name4_labelname']));
                
    $drillCount = 0;
                
    for ($i = 6; $i <= 10; $i++) {

        if (isset(${'name'.$i.'Field'}) 
            && ${'name'.$i.'Field'} 
            && issetParam($fields['name'.$i.'_labelname']) 
            && isset($this->drillDownLink[${'name'.$i.'Field'}])) {

            $drillCount ++;
        }
    }
    
    $nameFieldLink = $nameFieldDropLink = '';
                
    for ($i = 6; $i <= 10; $i++) {

        if (isset(${'name'.$i.'Field'}) 
            && ${'name'.$i.'Field'} 
            && issetParam($fields['name'.$i.'_labelname']) 
            && isset($this->drillDownLink[${'name'.$i.'Field'}])) {

            $drillLabelName = Lang::line($fields['name'.$i.'_labelname']);

            if ($i >= 8 && $drillCount > 3) {

                $nameFieldDropItem = html_tag('a', array(
                    'href' => 'javascript:;', 
                    'class' => 'dropdown-item', 
                    'onclick' => $this->drillDownLink[${'name'.$i.'Field'}]['link']
                ), '<i class="far fa-chevron-circle-right"></i>' . $drillLabelName);

                $nameFieldDropItem = str_replace("'", "\'", $nameFieldDropItem);
                $nameFieldDropItem = str_replace("$\\'", "'", $nameFieldDropItem);
                $nameFieldDropLink .= $nameFieldDropItem;

                $isNameFieldDrop = true;

                continue;
            }

            $nameFieldBtn = html_tag('button', array(
                'type' => 'button', 
                'class' => 'btn rounded-round ' . $this->drillDownLink[${'name'.$i.'Field'}]['linkStyle'], 
                'onclick' => $this->drillDownLink[${'name'.$i.'Field'}]['link']
            ), $drillLabelName);

            $nameFieldBtn = str_replace("'", "\'", $nameFieldBtn);
            $nameFieldLink .= $nameFieldBtn . '<div class="clearfix"></div>';

            $isNameFieldLink = true;
        }
    } 
?>

var card2view_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
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
            
        cc.push('<div class="card-wrapper-eui">');  
            
            cc.push('<div class="media">');
                cc.push('<div class="mr-2">');
                    <?php
                    if ($logo) {
                    ?>
                    cc.push('<img src="'+rowData.<?php echo $logo; ?>+'" onerror="onUserImageError(this);" class="rounded-circle">');
                    <?php
                    } else {
                    ?>
                    cc.push('<img src="" onerror="onUserImageError(this);" class="rounded-circle">');
                    <?php
                    }
                    ?>
                cc.push('</div>');
                
                cc.push('<div class="media-body align-self-center">');
                    cc.push('<div class="bank-card-name1">'+rowData.<?php echo $name1Field; ?>+'</div>');
                    cc.push('<div class="bank-card-name2">'+rowData.<?php echo $name2Field; ?>+'</div>');
                cc.push('</div>');
                
            cc.push('</div>'); 
            
            cc.push('<div class="d-flex media-info justify-content-between" style="height: 115px;">');
                
                cc.push('<div class="mt-auto">');
                    cc.push('<div class="card2-row-info">');
                        cc.push('<div class="card2-row-info-val">'+rowData.<?php echo $name3Field; ?>+'</div>');
                        cc.push('<div class="card2-row-info-label"><?php echo $name3_labelname; ?></div>');
                    cc.push('</div>');
                    cc.push('<div class="card2-row-info">');
                        cc.push('<div class="card2-row-info-val">'+rowData.<?php echo $name4Field; ?>+'</div>');
                        cc.push('<div class="card2-row-info-label"><?php echo $name4_labelname; ?></div>');
                    cc.push('</div>');
                cc.push('</div>');
                    
                cc.push('<div class="card2-drill-part d-flex align-items-center flex-column">');
                    cc.push('<div class="mt-auto">');
                        
                        <?php
                        if (isset($isNameFieldLink)) {
                            $nameFieldLink = str_replace(
                                array("$\'.", '$recordRow', "[$\'", "$\']", ".$\'"), 
                                array("'+",   'rowData',    "['",   "']",   "+'"), 
                                $nameFieldLink
                            );
                        ?>
                        cc.push('<?php echo $nameFieldLink; ?>');
                        <?php
                        }
                        ?>
                        
                        /*cc.push('<button type="button" class="btn rounded-round btn-secondary" onclick="">Бараа</button>');
                        cc.push('<div class="clearfix"></div>');
                        cc.push('<button type="button" class="btn rounded-round btn-secondary" onclick="">Захиалга</button>');
                        cc.push('<div class="clearfix"></div>');*/
                        
                        <?php
                        if (isset($isNameFieldDrop)) {
                            
                            $nameFieldDropLink = str_replace(
                                array("$\'.", '$recordRow', "[$\'", "$\']", ".$\'"), 
                                array("'+",   'rowData',    "['",   "']",   "+'"), 
                                $nameFieldDropLink
                            );
                        ?>
                        cc.push('<div class="btn-group dv-bank-card-dropdown">');
                            cc.push('<button type="button" class="btn btn-danger rounded-round dropdown-toggle" data-toggle="dropdown" onclick="dvCard2DropDownClick(this);">');
                                cc.push('<i class="far fa-ellipsis-h"></i>');
                            cc.push('</button>');
                            cc.push('<div class="dropdown-menu">');
                                
                                cc.push('<?php echo $nameFieldDropLink; ?>');
                                
                                /*cc.push('<a href="javascript:;" class="dropdown-item" onclick=""><i class="far fa-chevron-circle-right"></i>Баримт</a>');
                                cc.push('<a href="javascript:;" class="dropdown-item" onclick=""><i class="far fa-chevron-circle-right"></i>Тайлан</a>');*/
                                
                            cc.push('</div>');
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

function dvCard2DropDownClick(elem) {
    $(elem).closest('[datagrid-row-index]').click();
}
<?php
}
?>