<div class="row dataViewUseBasketViewWrap" data-basketid="<?php echo $this->uniqId; ?>" id="object-value-list-<?php echo $this->uniqId; ?>">
    <?php 
    $commandBtn = $this->dataViewProcessCommand['commandBtn']; 
    $commandBtnPosition = $this->dataViewProcessCommand['commandBtnPosition'];    
    
    if ($commandBtnPosition) {
        foreach ($commandBtnPosition as $commandBtnPositionRow) {
            $commandBtn .= $commandBtnPositionRow['html'];
        }
    }

    if ($commandBtn) {
        $commandBtn = str_replace('class="btn-group btn-group-devided pr4"', 'class="btn-group btn-group-devided col-md-12 pl0"', $commandBtn);
        $commandBtn = str_replace('d-flex', '', $commandBtn);
    } 
    ?>
    <div class="col-md-12 div-objectdatagrid-<?php echo $this->uniqId; ?> jeasyuiTheme_basketView">
        <table id="objectdatagrid-<?php echo $this->uniqId; ?>"></table>
    </div>
    <div class="dvbasket-footer-panel w-100 mr-2 ml-2">
        <div class="d-flex justify-content-between w-100 mt-0 mb-0" style="border-top: 1px solid #E6E6E6;padding-top: 10px;">
            <div style="color:#585858;font-size:16px;">
                <?php echo $this->lang->line('quantity'); ?>
            </div>
            <div style="color:#585858;font-size:16px;" class="item-basket-total-qty">
                <?php echo count(json_decode($this->selectedBasketRows, true)); ?>
            </div>
        </div>
        <div class="d-flex justify-content-between w-100 mt-0">
            <div style="color:#585858;font-size:18px;font-weight: bold;margin-top: 16px;">
                <?php echo $this->lang->line('FIN_01055'); ?>
            </div>
            <div style="color:#585858;font-size:38px;font-weight: bold;" class="item-basket-total-sum">
                0
            </div>
        </div>
        <div class="w-100 mt-0 mb-0 dvbasket-footer-btns">
            <?php echo $commandBtn; ?>
        </div>
    </div>
</div>

<?php 
echo Form::hidden(array('id' => 'cardViewerFieldPath')); 
echo Form::hidden(array('id' => 'cardViewerValue')); 
echo Form::hidden(array('id' => 'treeFolderValue')); 
echo Form::hidden(array('id' => 'currentSelectedRowIndex')); 
echo Form::hidden(array('id' => 'refStructureId', 'value' => $this->refStructureId)); 

$typeRow = $this->row['dataViewLayoutTypes']['ecommerce_basket'];
$photoField = '';
$defaultImage = 'assets/core/global/img/metaicon/big/'.$typeRow['DEFAULT_IMAGE'];

if (isset($typeRow['fields']['photo']) && $typeRow['fields']['photo'] != '') {
    $photoField = "'+rowData.".strtolower($typeRow['fields']['photo'])."+'";
}

$name2Field = strtolower(issetParam($typeRow['fields']['name2']));
$name3Field = strtolower(issetParam($typeRow['fields']['name3']));
$name5Field = strtolower(issetParam($typeRow['fields']['name5']));
?>

<style type="text/css">
#object-value-list-<?php echo $this->uniqId; ?> .btn-group, 
#object-value-list-<?php echo $this->uniqId; ?> .dvbasket-footer-btns > a.btn {
    width: 100%;
    display: block;
}
#object-value-list-<?php echo $this->uniqId; ?> .btn-group a, 
#object-value-list-<?php echo $this->uniqId; ?> .dvbasket-footer-btns > a.btn {
    width: 100%;
    padding-top: 11px;
    padding-bottom: 11px;
    border-radius: 25px;
    font-size: 16px;        
    margin-top: 5px;
}
#object-value-list-<?php echo $this->uniqId; ?> .btn-group a i, 
#object-value-list-<?php echo $this->uniqId; ?> .dvbasket-footer-btns > a.btn > i {
    display: none;
}
</style>
    
<script type="text/javascript">
    var dataGridTypeBtn_<?php echo $this->uniqId; ?> = 'datagrid';
    var objectdatagrid_<?php echo $this->uniqId; ?> = $('#objectdatagrid-<?php echo $this->uniqId; ?>');
    var windowId_<?php echo $this->uniqId; ?> = 'div#object-value-list-<?php echo $this->uniqId; ?>';
    var rows_<?php echo $this->uniqId; ?> = <?php echo $this->selectedBasketRows ?>;
    var commonSelectableGridName_<?php echo $this->metaDataId; ?> = 'objectdatagrid-<?php echo $this->metaDataId; ?>';
    var $window = $(windowId_<?php echo $this->uniqId; ?>);
    var footerPanel = $window.find('.dvbasket-footer-panel').height() + 90;
    var dynamicHeight = $(window).height() - footerPanel;
    var delete_btn = plang.get('delete_btn');
    
    objectdatagrid_<?php echo $this->uniqId; ?>.css('max-height', dynamicHeight);
    $(".div-objectdatagrid-<?php echo $this->uniqId; ?>").css('height', dynamicHeight);
    
    var ecommerce_basket_cardview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
        render: function (target, container, frozen) {
            var data = $.data(target, "datagrid");
            var rows = data.data.rows;
            var fields = $(target).datagrid("getColumnFields", frozen);
            var cls = "class=\"datagrid-row\"";
            var table = [];
            
            table.push('<table class="datagrid-btable w-100" cellpadding="0" cellpadding="0" border="0"><tbody>');
            
            for (var i = 0; i < rows.length; i++) {
                table.push('<tr data-item-id="'+rows[i]['id']+'" datagrid-row-index="' + i + '" ' + cls + '>');
                table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
                table.push("</tr>");
            }
            
            table.push('</tbody></table>');
            
            $(container).html(table.join(''));
        },
        renderRow: function (target, fields, frozen, rowIndex, rowData) {
            var cc = [];
            cc.push('<td style="border-width:0;border-bottom-width: 1px;">');
                cc.push('<div class="card-one" style="margin-top: 10px; margin-bottom: 10px">');
                    cc.push('<div class="card-wrapper-eui d-flex align-items-center">');
                        cc.push('<div class="image-wrapper-eui">');  
                            cc.push('<img src="<?php echo $photoField; ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>');
                        cc.push('</div>');
                        cc.push('<div class="content-actions-wrapper-eui mt-0 mb-0 w-100" style="margin-left: 10px;margin-right: 4px;">');
                            cc.push('<div class="content-eui">');
                                cc.push('<div class="d-flex justify-content-between">'); 
                                cc.push('<div class="name-wrapper-eui code" style="color:#585858;font-weight: bold;" title="'+detectHtmlStr(rowData.<?php echo $name3Field; ?>)+'">');
                                
                                    cc.push(rowData.<?php echo $name3Field; ?>);
                                    
                                cc.push('</div>');
                                cc.push('<div title="'+delete_btn+'" style="cursor:pointer" onclick="deleteSelectableBasketWindow_<?php echo $this->metaDataId; ?>(this);"><i class="far fa-times" style="color:#C8C8C8;font-size:18px"></i>'); 
                                cc.push('</div>');
                                cc.push('</div>');
                                
                                <?php
                                if ($name5Field) {
                                ?>
                                cc.push('<div class="name-wrapper-eui mt-0" style="color:#9FA2B4" title="'+detectHtmlStr(rowData.<?php echo $name5Field; ?>)+'">');
                                    cc.push(rowData.<?php echo $name5Field; ?>);
                                cc.push('</div>'); 
                                <?php
                                }
                                ?>
                                            
                                cc.push('<div class="d-flex justify-content-between">'); 
                                    
                                    <?php
                                    if ($name2Field) {
                                    ?>
                                    cc.push('<div class="position-wrapper-eui" style="color:#9FA2B4" title="'+detectHtmlStr(rowData.<?php echo $name2Field; ?>)+'">');
                                        cc.push(rowData.<?php echo $name2Field; ?>);
                                    cc.push('</div>'); 
                                    <?php
                                    }
                                    ?>
                                                
                                    cc.push('<div style="color:#1F95EE;font-weight: bold;font-size:14px;">');
                                        cc.push(gridAmountField(rowData.basketunitprice));
                                    cc.push('</div>'); 
                                cc.push('</div>'); 

                                cc.push('<div class="d-flex jeasyuiThemeecommerce_basketView justify-content-between">'); 
                                    cc.push('<div class="position-wrapper-eui mt-1 footer-name3 addedbasket" style="color:#9FA2B4" title="">');
                                        cc.push('<span class="add-to-basket-dvtheme d-flex align-items-center addbasket-opened" style="padding: 1px 12px 0px 12px;"><i class="fa fa-minus minus-basket-dvtheme" style="font-size:14px;opacity: .75;"></i><input type="text" maxlength="3" value="'+(rowData.basketqty ? rowData.basketqty : 1)+'" style="font-size:15px"><i class="fa fa-plus plus-basket-dvtheme" style="font-size:14px;opacity: .75;"></i></span>');
                                    cc.push('</div>'); 
                                    cc.push('<div style="color:#585858;font-weight:bold;font-size:18px;margin:15px;margin-right:0;margin-bottom:0;">');
                                        cc.push(gridAmountField(rowData.baskettotalprice ? rowData.baskettotalprice : rowData.basketunitprice));
                                    cc.push('</div>'); 
                                cc.push('</div>'); 

                            cc.push('</div>');         
                        cc.push('</div>');     
                    cc.push('</div>');    
                cc.push('<div>');
            cc.push('</td>');
            
            return cc.join('');
        }
    });

    $(function() {
        
        $(windowId_<?php echo $this->uniqId; ?>).find('a[data-actiontype="insert"]').remove();        
        
        <?php
        $layoutType = ($this->isTreeGridData ? '' : 'view: ecommerce_basket_cardview_'.$this->metaDataId.",\n");
        $options = $frozenColumns = '';
        
        foreach ($this->dataGridDefaultOption as $k => $row) {
            if ($k == 'resizeHandle') {
                $options .= "resizeHandle: '" . $this->dataGridOptionData['RESIZEHANDLE'] . "',";
            } elseif ($k == 'fitColumns') {
                $options .= "fitColumns: " . $this->dataGridOptionData['FITCOLUMNS'] . ",";
            } elseif ($k == 'autoRowHeight') {
                $options .= "autoRowHeight: " . $this->dataGridOptionData['AUTOROWHEIGHT'] . ",";
            } elseif ($k == 'striped') {
                $options .= "striped: " . $this->dataGridOptionData['STRIPED'] . ",";
            } elseif ($k == 'method') {
                $options .= "method: '" . $this->dataGridOptionData['METHOD'] . "',";
            } elseif ($k == 'nowrap') {
                $options .= "nowrap: " . $this->dataGridOptionData['NOWRAP'] . ",";
            } elseif ($k == 'pagination') {
                $options .= "pagination: false,";
            } elseif ($k == 'rownumbers') {
                $options .= "rownumbers: false,";
            } elseif ($k == 'singleSelect') {
                $options .= "singleSelect: false,";
            } elseif ($k == 'ctrlSelect') {
                $options .= "ctrlSelect: true,";
            } elseif ($k == 'checkOnSelect') {
                $options .= "checkOnSelect: true,";
            } elseif ($k == 'selectOnCheck') {
                $options .= "selectOnCheck: true,";
            } elseif ($k == 'pagePosition') {
                $options .= "pagePosition: '" . $this->dataGridOptionData['PAGEPOSITION'] . "',";
            } elseif ($k == 'pageNumber') {
                $options .= "pageNumber: " . $this->dataGridOptionData['PAGENUMBER'] . ",";
            } elseif ($k == 'pageSize') {
                $options .= "pageSize: " . $this->dataGridOptionData['PAGESIZE'] . ",";
            } elseif ($k == 'pageList') {
                $options .= "pageList: " . $this->dataGridOptionData['PAGELIST'] . ",";
            } elseif ($k == 'sortName') {
                if (!empty($this->dataGridOptionData['SORTNAME'])) {
                    $options .= "sortName: '" . strtolower($this->dataGridOptionData['SORTNAME']) . "',";
                    $options .= "sortOrder: '" . $this->dataGridOptionData['SORTORDER'] . "',";
                }
            } elseif ($k == 'multiSort') {
                $options .= "multiSort: " . $this->dataGridOptionData['MULTISORT'] . ",";
            } elseif ($k == 'remoteSort') {
                $options .= "remoteSort: false,";
            } elseif ($k == 'showHeader') {
                $options .= "showHeader: false,";
            } elseif ($k == 'showFooter') {
                $options .= "showFooter: " . $this->dataGridOptionData['SHOWFOOTER'] . ",";
            } elseif ($k == 'scrollbarSize') {
                $options .= "scrollbarSize: " . $this->dataGridOptionData['SCROLLBARSIZE'] . ",";
            } elseif ($k == 'mergeCells') {
                if ($this->dataGridOptionData['MERGECELLS'] == 'true') {
                    $isMergeCells = true;
                }
            } elseif ($k == 'showFileicon') {
                if ($this->dataGridOptionData['SHOWFILEICON'] == 'false') {
                    $options .= 'fileIconclass: "hidden",';
                }
            }
        } 

        $frozenColumns = 'frozenColumns: '.((isset($this->dataGridColumnData['freeze'])) ? $this->dataGridColumnData['freeze'] : '').','."\n";
        ?>
        
        objectdatagrid_<?php echo $this->uniqId; ?>.<?php echo $this->isGridType; ?>({
                <?php echo $layoutType; ?> 
                data: rows_<?php echo $this->uniqId; ?>, 
                <?php 
                if ($this->isTreeGridData) {
                    parse_str($this->isTreeGridData, $isTreeGridData);
                    echo "idField: '".$isTreeGridData['id']."',"."\n"; 
                    echo "treeField: '".$isTreeGridData['name']."',"."\n";
                }                
                echo $options;        
                
                if ($this->isRowColor || $this->isTextColor) {
                    echo 'rowStyler: function(index, row){'."\n";
                ?>
                    var rowStyleStr = '';
                    if (typeof row.rowcolor !== 'undefined' && row.rowcolor != '') {
                        rowStyleStr += 'background-color:'+row.rowcolor+';';
                    }
                    if (typeof row.textcolor !== 'undefined' && row.textcolor != '') {
                        rowStyleStr += 'color:'+row.textcolor+';';                        
                    }
                    return rowStyleStr;
                },             
                <?php            
                }
                ?>     
                columns: <?php echo ((isset($this->dataGridColumnData['header'])) ? $this->dataGridColumnData['header'] : ''); ?>,
                onClickRow: function(index, row) {
                    $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->uniqId; ?>").val(index);
                    
                    if ($('.workflow-btn-<?php echo $this->metaDataId ?>', "#object-value-list-<?php echo $this->uniqId; ?>").length) {
                        $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
                    }
                }, 
                onLoadSuccess: function(data) { 
                    
                if (data.status == 'error') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                }
                var _thisGrid = objectdatagrid_<?php echo $this->uniqId; ?>;
                <?php 
                echo 'showGridMessage(_thisGrid);'."\n";
                echo 'var currentSelectedRowIndex = $("#currentSelectedRowIndex", "#object-value-list-'.$this->uniqId.'").val();'."\n";
                echo 'if (currentSelectedRowIndex != "") {'."\n";
                    echo "_thisGrid.datagrid('selectRow', currentSelectedRowIndex);"."\n";
                echo '}'."\n";
                ?>
                            
                _thisGrid.datagrid('resize'); 
                var $panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");
                
                if (_thisGrid.datagrid('getRows').length == 0) {
                    var tr = $panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                    $(tr).find('td').find('div').find('span').each(function () {
                        this.remove();
                    });
                }
                
                if ($('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').length) {
                    $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').remove();
                }
                
                $('#object-value-list-<?php echo $this->uniqId; ?>').find(".item-basket-total-qty").text(rows_<?php echo $this->uniqId; ?>.length);
                calcSumBasketWindow_<?php echo $this->metaDataId ?>();
                
                var dgRows = _thisGrid.datagrid('getRows');
                for (var key in dgRows) {
                    if (!Object.keys(dgRows[key]).length) {
                        _thisGrid.datagrid('deleteRow', key);
                        
                        if ($('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').length) {
                            $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').remove();
                        } 
                    }
                }         
                
                objectdatagrid_<?php echo $this->uniqId; ?>.closest(".datagrid-wrap").css("border", "none");
            }
        });

    });
    
    function deleteSelectableBasketWindow_<?php echo $this->metaDataId ?>(target) {
        
        setTimeout(function(){
            var basketRows = objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getSelections');
            var selectedRow = basketRows[0], rowId = selectedRow.id; 
            
            for (var key in rows_<?php echo $this->uniqId; ?>) {
                var row = rows_<?php echo $this->uniqId; ?>[key], childId = row.id;
                
                if (rowId == childId) {

                    var index = objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getRowIndex', row);
                    if (index < 0) {
                        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('deleteRow', 0);
                        rows_<?php echo $this->uniqId; ?>.splice(key, 1);
                    } else {
                        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('deleteRow', index);
                    }

                    _selectedRows_<?php echo $this->metaDataId; ?>.splice(key, 1);
                    
                    break;
                } 
            }
            
            objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('loadData', rows_<?php echo $this->uniqId; ?>);
            $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').remove();
            $('#object-value-list-<?php echo $this->uniqId; ?>').find(".item-basket-total-qty").text(rows_<?php echo $this->uniqId; ?>.length);
            $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').find('[data-row-id="'+rowId+'"]').find('.addedbasket, .addbasket-opened').removeClass('addedbasket addbasket-opened');
            
            $('.save-database-<?php echo $this->metaDataId; ?>').text(_selectedRows_<?php echo $this->metaDataId; ?>.length);
            calcSumBasketWindow_<?php echo $this->metaDataId ?>();
        }, 5);
    }
    function calcSumBasketWindow_<?php echo $this->metaDataId ?>() {
        var rows = objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getRows'), sum = 0;
        
        for (var i in rows) {
            
            var basketTotalPrice = Number((rows[i]['baskettotalprice'] ? rows[i]['baskettotalprice'] : rows[i]['basketunitprice']));
            sum += basketTotalPrice;
            
            var updateRow = {};
            
            updateRow['basketqty'] = Number((rows[i]['basketqty'] ? rows[i]['basketqty'] : 1));
            updateRow['baskettotalprice'] = basketTotalPrice;
            
            objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('updateRow', {
                index: i,
                row: updateRow
            });
        }
        
        $('#object-value-list-<?php echo $this->uniqId; ?>').find(".item-basket-total-sum").text(gridAmountField(sum));
    }
</script>