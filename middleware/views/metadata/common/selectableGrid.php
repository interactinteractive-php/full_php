<?php echo $this->gridLayout; ?>

<style type="text/css">  
#commonSelectableTreeView {
    overflow: auto;
    height: 350px !important;
}   
#commonSelectableSearchForm_<?php echo $this->metaDataId ?> .form-group {
    margin-bottom: 5px !important;
}
#commonSelectableSearchForm_<?php echo $this->metaDataId ?> .form-actions {
    margin-top: 15px !important;
}
#commonSelectableSearchForm_<?php echo $this->metaDataId ?> .form-body {
    overflow: auto;
    max-height: 331px !important;
}
#commonSelectableTabOrder_<?php echo $this->metaDataId; ?> .datagrid-view .datagrid-body *::-moz-selection { background:transparent; }
#commonSelectableTabOrder_<?php echo $this->metaDataId; ?> .datagrid-view .datagrid-body *::selection { background:transparent; }
.datagrid-header-row {
    height: 25px !important;
}
.tabbable-line > .nav-tabs > li > a {
    background-color: transparent;
}
.print-copies-control {
    width: 100%;
    padding: 0 5px;
    height: 20px;
}
.datagrid-cell .red.btn {
    padding: 0 5px !important;
    line-height: 14px !important;
}
</style>

<script type="text/javascript">    
    var jsonConfigGroup = '<?php echo html_entity_decode(Str::removeNL(issetParam($this->dataGridOptionData['JSON_CONFIG'])), ENT_QUOTES, 'UTF-8') ?>';
    jsonConfigGroup = jsonConfigGroup ? JSON.parse(jsonConfigGroup) : {};
    
    var selectableGrid_<?php echo $this->metaDataId; ?> = $('.selectableGrid-<?php echo $this->metaDataId; ?>');
    var commonSelectableGridName_<?php echo $this->metaDataId; ?> = 'objectdatagrid_<?php echo $this->metaDataId; ?>';
    var paramSelectedRow_<?php echo $this->metaDataId; ?> = <?php echo $this->selectedRow; ?>;
    var basketFirstLoad_<?php echo $this->metaDataId; ?> = false;
    var filterFieldList_<?php echo $this->metaDataId; ?> = JSON.parse('<?php echo json_encode($this->filterFieldList); ?>');
    var objectdatagrid_<?php echo $this->metaDataId; ?> = $('#objectdatagrid_<?php echo $this->metaDataId; ?>');
    var isTouch = (typeof isTouchEnabled === 'undefined') ? false : isTouchEnabled;
    var dvLoadSuccessData_<?php echo $this->metaDataId; ?> = null;
    var dvRequest_<?php echo $this->metaDataId; ?> = null;
    var isComboGrid_<?php echo $this->metaDataId; ?> = '<?php echo issetParam($this->isComboGrid); ?>';
    
    if (isComboGrid_<?php echo $this->metaDataId; ?> !== '1') disableScrolling();
    
    <?php
    if ($this->isTreeGridData) {
        
        $layoutType = '';
        
    } elseif ($this->subgrid) {
            
        $layoutType = 'view: detailview,'."\n";

    } elseif ($this->dataGridOptionData['GROUPFIELD']) {   
        
        $layoutType = 'view: groupview,'."\n";
        $layoutType .= 'showFilterBar: true,'."\n";
        $layoutType .= "groupField: '".strtolower($this->dataGridOptionData['GROUPFIELD'])."',"."\n";
        $layoutType .= 'groupFormatter: function(value, rows) { return '.Format::dataGridGroupFormatter($this->dataGridOptionData['GROUPFORMATTER']).'; },'."\n";
        
        if (isset($this->dataGridOptionData['GROUPFIELDSTYLER'])) {
            $layoutType .= 'groupStyler: function(value,rows){ return \''.$this->dataGridOptionData['GROUPFIELDSTYLER'].'\'; },';
        }
        
    } else {
        $layoutType = 'view: horizonscrollview,'."\n";
    }    
    ?>
    
    function commonSelectableGrid_<?php echo $this->metaDataId ?>() {
        $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).<?php echo $this->isGridType; ?>({
            <?php echo $layoutType; ?>
            url: 'mdmetadata/commonSelectableDataGrid',
            queryParams: {
                metaDataId: '<?php echo $this->metaDataId; ?>', 
                processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
                paramRealPath: '<?php echo $this->paramRealPath; ?>', 
                defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>").serialize()+'&'+$(".mandatory-criteria-form-<?php echo $this->metaDataId ?>").serialize(), 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>, 
                isClickFilter: 1
            },
            <?php
            if ($this->isTreeGridData) {
                parse_str($this->isTreeGridData, $isTreeGridData);
                echo "idField: '".$isTreeGridData['id']."',"."\n"; 
                echo "treeField: '".$isTreeGridData['name']."',"."\n";
            }
            ?>
            rownumbers: isComboGrid_<?php echo $this->metaDataId; ?> ? false : true,
            singleSelect: <?php echo $this->singleSelect; ?>,
            ctrlSelect: true,
            pagination: true,
            remoteFilter: true,
            filterDelay: 10000000000,
            <?php
            if ($this->isRowColor || $this->isTextColor) {
                if ($this->isTreeGridData) {
                    echo 'rowStyler: function(row){'."\n";
                } else {
                    echo 'rowStyler: function(index, row){'."\n";
                } 
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
            fitColumns: true,
            <?php
            foreach ($this->dataGridDefaultOption as $k => $row) {
                if ($k == 'pagePosition') {
                    echo "pagePosition: '" . $this->dataGridOptionData['PAGEPOSITION'] . "',";
                } elseif ($k == 'pageNumber') {
                    echo "pageNumber: " . $this->dataGridOptionData['PAGENUMBER'] . ",";
                } elseif ($k == 'pageSize') {
                    echo "pageSize: " . $this->dataGridOptionData['PAGESIZE'] . ",";
                } elseif ($k == 'pageList') {
                    echo "pageList: " . $this->dataGridOptionData['PAGELIST'] . ",";
                } elseif ($k == 'sortName') {
                    if (!empty($this->dataGridOptionData['SORTNAME'])) {
                        echo "sortName: '" . Str::lower($this->dataGridOptionData['SORTNAME']) . "',"."\n";
                        echo "sortOrder: '" . $this->dataGridOptionData['SORTORDER'] . "',"."\n";
                    }
                } elseif ($k == 'showFooter') {
                    echo "showFooter: " . $this->dataGridOptionData['SHOWFOOTER'] . ",";
                } elseif ($k == 'striped') {
                    echo "striped: " . $this->dataGridOptionData['STRIPED'] . ",";
                } elseif ($k == 'autoRowHeight') {
                    echo "autoRowHeight: " . $this->dataGridOptionData['AUTOROWHEIGHT'] . ",";
                } elseif ($k == 'nowrap') {
                    echo "nowrap: " . $this->dataGridOptionData['NOWRAP'] . ",";
                } elseif ($k == 'mergeCells' && $this->dataGridOptionData['MERGECELLS'] == 'true') {
                    $isMergeCells = true;
                    if (issetParam($this->dataGridOptionData['MERGECELLSKEYFIELD'])) {
                        $mergeCellsKeyField = $this->dataGridOptionData['MERGECELLSKEYFIELD'];
                    }
                }
            }
//            $replaceArr = array("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},");
//            if ($this->chooseType == 'single' || $this->chooseType == 'singlealways') {
//                array_push($replaceArr, "{field: 'ck', rowspan:1, checkbox: true },");
//            }
            ?>
            frozenColumns:<?php echo ((isset($this->dataGridHeader['freeze'])) ? str_replace(array("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},"), '', $this->dataGridHeader['freeze']) : ''); ?>,        
            columns:<?php echo ((isset($this->dataGridHeader['header'])) ? str_replace(array("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},"), '', $this->dataGridHeader['header']) : ''); ?>,
            <?php 
            if ($this->isTreeGridData) {
                echo 'onDblClickRow:function(row) {'."\n";
            } else {
                echo 'onDblClickRow:function(index, row) {'."\n";
            } 
            ?>
                dblClickCommonSelectableDataGrid_<?php echo $this->metaDataId ?>(row);
            },
            onCheck: function(index, row) {
                clickCommonSelectableDataGrid_<?php echo $this->metaDataId ?>(row, 'insert');
            }, 
            onUncheck: function(index, row) {
                clickCommonSelectableDataGrid_<?php echo $this->metaDataId ?>(row);
            },   
            onRowContextMenu:function(e, index, row){
                e.preventDefault();
                if (isComboGrid_<?php echo $this->metaDataId; ?> !== '1') {
                    $(this).datagrid('selectRow', index);
                    $.contextMenu({
                        selector: "#commonSelectableTabOrder_<?php echo $this->metaDataId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                        callback: function(key, opt) {
                            if (key === 'basket') {
                                basketCommonSelectableDataGrid_<?php echo $this->metaDataId ?>();
                            }
                        },
                        items: {
                            "basket": {name: "<?php echo $this->lang->line('META_00042'); ?>", icon: "plus-circle"}
                        }
                    });
                }
            },
            onCheckAll: function(){
                $(this).<?php echo $this->isGridType; ?>('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').focus();
            },
            onUncheckAll: function(){
                $(this).<?php echo $this->isGridType; ?>('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').focus();
            },
            <?php 
            echo $this->subgrid; 
            
            if ($this->isTreeGridData) {
            ?>
            onBeforeLoad: function(row, param) { 
                
                if (typeof _isRunAfterProcessSave !== 'undefined') {
                    delete param.isNotUseReport;

                    if (_isRunAfterProcessSave) {
                        param.isNotUseReport = 1;
                        _isRunAfterProcessSave = false;
                    }
                }
                    
                if (!row) {   
                    delete param.id;
                    <?php
                    if (Config::getFromCache('javaversion') >= 1) {
                    ?>
                    var _thisGrid = $(this);
                    param.pagingWithoutAggregate = 1;
                    param.pi = 1;

                    setTimeout(function() {
                        $.ajax({
                            type: 'post',   
                            url: 'mdobject/dataViewAggregateData',
                            data: param,
                            dataType: 'json',
                            success: function(data) {
                                dvLoadSuccessData_<?php echo $this->metaDataId; ?> = data;
                                dvReloadFooterData(_thisGrid, data);
                            },
                            error: function() { console.log('error: dataViewAggregateData'); }
                        });
                    }, 1);
                    <?php
                    }
                    ?>
                }
            },
            onLoadSuccess: function(row, data) {
            <?php    
            } else {
                if (Config::getFromCache('javaversion') >= 1) {
            ?>
            onBeforeLoad: function(param) {
                var _thisGrid = $(this);
                param.pagingWithoutAggregate = 1;
                param.pi = 1;
                
                if (typeof _isRunAfterProcessSave !== 'undefined') {
                    delete param.isNotUseReport;

                    if (_isRunAfterProcessSave) {
                        param.isNotUseReport = 1;
                        _isRunAfterProcessSave = false;
                    }
                }
                
                setTimeout(function() {
                    $.ajax({
                        type: 'post',   
                        url: 'mdobject/dataViewAggregateData',
                        data: param,
                        dataType: 'json',
                        success: function(data) {
                            dvLoadSuccessData_<?php echo $this->metaDataId; ?> = data;
                            dvReloadFooterData(_thisGrid, data);
                        },
                        error: function() { console.log('error: dataViewAggregateData'); }
                    });
                }, 1);
            },
            <?php
                }
            ?>
            onLoadSuccess: function(data) {                
            <?php
            } 
            ?>
                if (data.status == 'error') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                }        
                var _thisGrid = $(this);
                
                <?php 
                if ($this->isTreeGridData) {
                    echo "showTreeGridMessage(_thisGrid, '".issetParam($this->dataGridOptionData['MSGNORECORDFOUND'])."');"."\n";
                } else {
                    echo "showGridMessage(_thisGrid, '".issetParam($this->dataGridOptionData['MSGNORECORDFOUND'])."');"."\n";
                } 
                ?>                

//                var $dataView = $('.selectableGrid-datatable-<?php echo $this->metaDataId; ?>').find('.datagrid-view').height() - 15;
//                $('.selectableGrid-datatable-<?php echo $this->metaDataId; ?>').find('.datagrid-view').attr('style', 'height:'+ $dataView +'px');
                    
                if (isComboGrid_<?php echo $this->metaDataId; ?> !== '1') {
                    checkBeforeSelectedRows_<?php echo $this->metaDataId; ?>();
                }
                
                var $panelView = _thisGrid.<?php echo $this->isGridType; ?>("getPanel").children("div.datagrid-view");
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');
                
                <?php 
                echo Arr::get($this->dataGridHeader, 'filterCenterInit');
                echo Arr::get($this->dataGridHeader, 'filterDateInit');
                echo Arr::get($this->dataGridHeader, 'filterDateTimeInit');
                echo Arr::get($this->dataGridHeader, 'filterBigDecimalInit');
                echo Arr::get($this->dataGridHeader, 'filterNumberInit');
                echo Arr::get($this->dataGridHeader, 'filterTimeInit');  
                ?>

                Core.initNumberInput($panelFilterRow);
                Core.initDateInput($panelFilterRow);
                Core.initDateTimeInput($panelFilterRow);
                Core.initDateMaskInput($panelFilterRow);
                Core.initDateMinuteInput($panelFilterRow);
                Core.initTimeInput($panelFilterRow);
//                Core.initAccountCodeMask($panelFilterRow);
                Core.initStoreKeeperKeyCodeMask($panelFilterRow);                
                
                Core.initFancybox($panelView);
                
                <?php if (isset($isMergeCells)) { ?>
                    var isMergeColumn = JSON.parse('<?php echo json_encode(issetDefaultVal($this->dataGridColumnData['isMergeColumn'], array())); ?>');
                    <?php if (isset($mergeCellsKeyField)) { ?>
                        isMergeColumn.keyfield = '<?php echo $mergeCellsKeyField; ?>'; 
                    <?php } if ($this->isTreeGridData) { ?>
                    isMergeColumn.isTree = true;       
                    isMergeColumn.rows = data.rows;    
                    <?php } ?>
                    _thisGrid.datagrid("autoMergeCells", isMergeColumn);
                <?php } ?>
                    
                _thisGrid.promise().done(function() {
                    _thisGrid.<?php echo $this->isGridType; ?>('resize');
                    if (!basketFirstLoad_<?php echo $this->metaDataId; ?>) {
                        basketFirstLoad_<?php echo $this->metaDataId; ?> = true;
                        if (!isTouch && isComboGrid_<?php echo $this->metaDataId; ?> !== '1') {
                            $panelView.find('> .datagrid-view2 > .datagrid-header tr.datagrid-filter-row > td:eq(0) input[type=text]').focus();
                        }
                    }                                                
                });
                   
                if (typeof initDVClearColumnFilterBtn === 'function') { 
                    initDVClearColumnFilterBtn($panelView, $panelFilterRow);    
                }
                
                dvReloadFooterData(_thisGrid, dvLoadSuccessData_<?php echo $this->metaDataId; ?>);
                
                _thisGrid.datagrid('fixRownumber');
            }
        });
        
        if (isComboGrid_<?php echo $this->metaDataId; ?> !== '1') {
            $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('getPager').pagination({
                showPageList: true,
                layout: ['list','sep','first','prev','sep','manual','sep','next','last','sep','refresh','info'],    
                buttons: [{
                    iconCls: 'pagination-sum',
                    handler: function(){
                        dvSelectionRowsSumCount($('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>));
                    }
                }]
            });
        } else {
            $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('getPager').pagination({
                showPageList: true,
                layout: ['prev','manual','next']
            });
        }
        
        <?php if ($this->dataGridOptionData['ENABLEFILTER'] == 'true') { ?>
        $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('enableFilter');
        <?php } ?>
    }
    
    $(function() {
        
        $('#commonSelectableTabOrder_<?php echo $this->metaDataId; ?>').find('input[type=text][readonly]').removeAttr('readonly');        
        
        <?php
        if ($this->isGridShow) {
            echo 'commonSelectableGrid_'.$this->metaDataId.'();';
        }
        
        $frozenColumns = isset($this->dataGridHeader['freeze']) ? $this->dataGridHeader['freeze'] : '';
        
        if (isset($this->row['IS_PRINT_COPIES']) && $this->row['IS_PRINT_COPIES'] == '1') {
            $isPrintCopies = true;
            $frozenColumns = str_replace("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},{field:'printcopiesinput', rowspan:2, title:'Хэвлэх хувь', sortable:false, width:70, align:'center', formatter: function(v, r, i) {return '<input type=\"text\" class=\"print-copies-control longInit\" value=\"'+((typeof v !== 'undefined' && v != '0') ? v : '')+'\" maxlength=\"3\" onchange=\"printCopiesChange_".$this->metaDataId."(this, '+i+');\">'; },},", $frozenColumns);
            $frozenColumns = str_replace("{field:'action',  title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},{field:'printcopiesinput', title:'Хэвлэх хувь', sortable:false, width:70, align:'center', formatter: function(v, r, i) {return '<input type=\"text\" class=\"print-copies-control longInit\" value=\"'+((typeof v !== 'undefined' && v != '0') ? v : '')+'\" maxlength=\"3\" onchange=\"printCopiesChange_".$this->metaDataId."(this, '+i+');\">'; }, },", $frozenColumns);
        }
        ?>
        
        $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId; ?>').datagrid({
            url: '',
            rownumbers: true,
            singleSelect: false,
            ctrlSelect: true,
            pagination: false,
            remoteSort: false,
            height: <?php echo (isset($this->basketGridHeight) && $this->basketGridHeight && $this->basketGridHeight != 'auto') ? $this->basketGridHeight : '380'; ?>,
            fitColumns: true,
            showFooter: true,
            frozenColumns:<?php echo $frozenColumns; ?>, 
            columns:<?php echo ((isset($this->dataGridHeader['header'])) ? $this->dataGridHeader['header'] : ''); ?>,
            onRowContextMenu:function(e, index, row){
                e.preventDefault();
                $(this).datagrid('selectRow', index);
                $.contextMenu({
                    selector: "#commonSelectableTabBasket_<?php echo $this->metaDataId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    callback: function(key, opt) {
                        if (key === 'delete') {
                            multiDeleteCommonSelectableBasket_<?php echo $this->metaDataId ?>();
                        }
                    },
                    items: {
                        "delete": {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"}
                    }
                });
            },
            onLoadSuccess:function(){
            
                var _thisGrid = $(this);
                var $panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');
                
                <?php 
                echo Arr::get($this->dataGridHeader, 'filterCenterInit');
                echo Arr::get($this->dataGridHeader, 'filterDateInit');
                echo Arr::get($this->dataGridHeader, 'filterDateTimeInit');
                echo Arr::get($this->dataGridHeader, 'filterBigDecimalInit');
                echo Arr::get($this->dataGridHeader, 'filterNumberInit');
                echo Arr::get($this->dataGridHeader, 'filterTimeInit');  
                ?>
                    
                Core.initNumberInput($panelFilterRow);
                Core.initDateInput($panelFilterRow);
                Core.initDateTimeInput($panelFilterRow);
                Core.initDateMaskInput($panelFilterRow);
                Core.initDateMinuteInput($panelFilterRow);
                Core.initTimeInput($panelFilterRow);
                Core.initAccountCodeMask($panelFilterRow);
                Core.initStoreKeeperKeyCodeMask($panelFilterRow);
                
                Core.initLongInput($panelView);
                Core.initFancybox($panelView);                
                
                _thisGrid.promise().done(function() {
                    _thisGrid.datagrid('resize');
                });
            }
        });
        
        $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('loadData', []);
        
        $("a[href=#commonSelectableTabFilter_<?php echo $this->metaDataId; ?>]").on("click", function(e) {
            var $parent = $(this).closest('li');
            
            if ($parent.hasClass('disabled')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                return false;
            }
        });
        
        $("a[href=#commonSelectableTabOrder_<?php echo $this->metaDataId; ?>]").on("click", function(e) {
            var $parent = $(this).closest('li');
            
            if ($parent.hasClass('disabled')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                return false;
            }
        });
        
        $('a[href="#commonSelectableTabOrder_<?php echo $this->metaDataId; ?>"]', ".selectableGrid-<?php echo $this->metaDataId; ?>").on('shown.bs.tab', function(e){
            setTimeout(function() {
                
                $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).<?php echo $this->isGridType; ?>('resize');
                $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).<?php echo $this->isGridType; ?>('fixRowHeight');
                
                var $panelView = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).<?php echo $this->isGridType; ?>("getPanel").children("div.datagrid-view");
                
                <?php
                if ($this->isGridType == 'treegrid') {
                ?>      
                $panelView.find('> .datagrid-view2 > .datagrid-header tr.datagrid-filter-row input[type=text]').each(function() {
                    var $thisFilter = $(this), $thisFilterParent = $thisFilter.closest('.datagrid-filter-c');
                    $thisFilter.css('width', $thisFilterParent.width());
                });      
                <?php
                }
                ?>
                            
                if (!isTouch) {
                    $panelView.find('> .datagrid-view2 > .datagrid-header tr.datagrid-filter-row > td:eq(0) input[type=text]').focus();
                }
            }, 5);
        });

        if ((paramSelectedRow_<?php echo $this->metaDataId ?>.items).length != 0 && isComboGrid_<?php echo $this->metaDataId; ?> !== '1') {
            
            rows = paramSelectedRow_<?php echo $this->metaDataId ?>.items;
            <?php
            if (isset($this->printCopiesParams)) {
                echo 'var printCopiesParams = '.$this->printCopiesParams.';';
            }
            ?>

            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var isAddRow = true;
                var subrows = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('getRows');

                for (var j = 0; j < subrows.length; j++) {
                    var subrow = subrows[j];
                    if (subrow.<?php echo $this->primaryField; ?> === row.<?php echo $this->primaryField; ?>) {
                        isAddRow = false;
                    }
                }
                if (isAddRow) {
                    $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('appendRow', {
                        <?php 
                        echo $this->dataGridBody; 
                        if (isset($this->printCopiesParams) && isset($isPrintCopies)) {
                            echo 'printcopiesinput: (printCopiesParams.hasOwnProperty(row.'.$this->primaryField.') ? printCopiesParams[row.'.$this->primaryField.'] : \'\'),'; 
                            echo 'printcopies: (printCopiesParams.hasOwnProperty(row.'.$this->primaryField.') ? printCopiesParams[row.'.$this->primaryField.'] : \'\'),'; 
                        }
                        ?>
                        action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php  echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>'
                    });
                }
            }  
            
            if ('<?php echo $this->singleSelect; ?>' == 'true') {
                $('a[href="#commonSelectableTabBasket_<?php echo $this->metaDataId; ?>"]').tab('show');
                basketGridColumnToLabel_<?php echo $this->metaDataId ?>();
            }
            
            $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('getData').total);
        }
        
        $('a[href="#commonSelectableTabBasket_<?php echo $this->metaDataId; ?>"]', ".selectableGrid-<?php echo $this->metaDataId; ?>").on('shown.bs.tab', function(e){
            $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('resize');
        });
        
        $('div.selectableGrid-datatable-<?php echo $this->metaDataId; ?>').on('keyup', 'input.datagrid-filter', function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            
            if (keyCode == 40) { /* down */
                var $grid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);

                if ($grid.<?php echo $this->isGridType; ?>('getData').<?php echo ($this->isGridType == 'datagrid' ? 'total' : 'length'); ?>) {
                    
                    $grid.<?php echo $this->isGridType; ?>('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').focus(); 
                    <?php
                    if ($this->isGridType == 'datagrid') {
                    ?>
                    $grid.datagrid('selectRow', 0);
                    <?php
                    } else {
                    ?>
                    var gridData = $grid.treegrid('getData');        
                    $grid.treegrid('select', gridData[0]['<?php echo $isTreeGridData['id']; ?>']);
                    <?php
                    } 
                    ?>
                            
                    e.preventDefault();
                    return false;
		}
            } else if (!e.shiftKey && keyCode == 37) { /* left */
                
                var $this = $(this);
                var $row = $this.closest('tr');
                var $rowInput = $row.find('td:visible input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)');
                var $cellIndex = $rowInput.index($this), isLastFocus = false;
                
                if ($cellIndex == 0) {
                    var $focusThis = $rowInput.eq($rowInput.length - 1);
                    isLastFocus = true;
                } else {
                    var $focusThis = $rowInput.eq($cellIndex - 1);
                }
                
                var $dbody = $focusThis.closest('.datagrid-view2').find('.datagrid-body');
                
                if (isLastFocus) {
                    $dbody.get(0).scrollLeft = $focusThis.closest('tr').width();
                } else {
                    var $thisCell = $focusThis.closest('td'), $dbodyWidth = Number($dbody.width()), 
                        $rightSize = $dbodyWidth - Number($thisCell.position().left), 
                        $prevCells = $focusThis.closest('td').prevAll('td'), $cellWidth = 0;

                    $prevCells.each(function(){
                        $cellWidth += $(this).width();
                    });

                    if ($dbodyWidth < $rightSize) {
                        $dbody.get(0).scrollLeft = $cellWidth;
                    }
                }
                
                $focusThis.focus().select();
                
                e.preventDefault();
                return false;
                
            } else if (!e.shiftKey && keyCode == 39) { /* right */
                
                var $this = $(this);
                var $row = $this.closest('tr');
                var $rowInput = $row.find('td:visible input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)');
                var $cellIndex = $rowInput.index($this), isFirstFocus = false; 
                
                if ($rowInput.length == ($cellIndex + 1)) {
                    var $focusThis = $rowInput.eq(0);
                    isFirstFocus = true;
                } else {
                    var $focusThis = $rowInput.eq($cellIndex + 1);
                }
                
                var $dbody = $focusThis.closest('.datagrid-view2').find('.datagrid-body');
                
                if (isFirstFocus) {
                    $dbody.get(0).scrollLeft = 0;
                } else {
                    var $dbodyWidth = Number($dbody.width()), $leftSize = Number($focusThis.closest('td').position().left), 
                        $prevCells = $focusThis.closest('td').prevAll('td'), $cellWidth = 0;

                    $prevCells.each(function(){
                        $cellWidth += $(this).width();
                    });

                    if ($dbodyWidth < $leftSize) {
                        $dbody.get(0).scrollLeft = $cellWidth;
                    }
                }
                
                $focusThis.focus().select();
                
                e.preventDefault();
                return false;
                
            } else if (!e.shiftKey && keyCode == 9) { /* tab */
            
                var $this = $(this), $row = $this.closest('tr'), 
                    $rowInput = $row.find('td:visible input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)'), 
                    $cellIndex = $rowInput.index($this), isFirstFocus = false; 
                
                if ($rowInput.length == ($cellIndex + 1)) {
                    var $focusThis = $rowInput.eq(0);
                    isFirstFocus = true;
                } else {
                    var $focusThis = $this;
                }
                
                var $dbody = $focusThis.closest('.datagrid-view2').find('.datagrid-body');
                
                if (isFirstFocus) {
                    $dbody.get(0).scrollLeft = 0;
                } else {
                    var $dbodyWidth = Number($dbody.width()), $leftSize = Number($focusThis.closest('td').position().left), 
                        $prevCells = $focusThis.closest('td').prevAll('td'), $cellWidth = 0;

                    $prevCells.each(function(){
                        $cellWidth += $(this).width();
                    });
                    
                    if ($dbodyWidth < $cellWidth) { /* if ($dbodyWidth < $leftSize) {  */
                        $dbody.get(0).scrollLeft = $cellWidth;
                    }
                }
                
                $focusThis.select();
                
                e.preventDefault();
                return false;
            } 
        });
        
        <?php if (isset($isPrintCopies)) { ?>
        $(document.body).on('keydown', '#commonSelectableTabBasket_<?php echo $this->metaDataId; ?> input.print-copies-control', function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            
            if (keyCode == 38) { /* up */
                var $this = $(this);
                var $dtlTbl = $this.closest('table');
                var $row = $this.closest('tr');    
                var $grid = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>');
                
                $grid.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').focus();
                
                var selected = $grid.datagrid('getSelected');
                if (selected) {
                    
                    var $rowIndex = $row.index();
                    var index = $rowIndex;
                    var selectionIndex = index - 1;
                    var $panelView = $grid.datagrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                    var $dgRow = $panelView.find('tr[datagrid-row-index="'+selectionIndex+'"]');

                    $grid.datagrid('unselectRow', index);

                    if ($dgRow.length) {

                        $grid.datagrid('selectRow', selectionIndex);
                        var ypos = $dgRow.offset().top - $panelView.offset().top - $dgRow.height();

                        $panelView.animate({
                            scrollTop: $panelView.scrollTop() + ypos
                        }, 100);
                        
                        var $rowCell = $this.closest('td'); 
                        var $colIndex = $rowCell.index();
                        
                        $dtlTbl.find('tr:eq('+selectionIndex+')').find('td:eq('+$colIndex+') input:first').focus().select();
                        
                        return e.preventDefault();
                
                    } else {
                        var selectionIndex = $grid.datagrid('getRows').length - 1;
                        var $dgRow = $panelView.find('tr[datagrid-row-index="'+selectionIndex+'"]');
                        $grid.datagrid('selectRow', selectionIndex);
                        $panelView.scrollTop(1000);
                        
                        var $rowCell = $this.closest('td'); 
                        var $colIndex = $rowCell.index();

                        $dtlTbl.find('tr:eq('+selectionIndex+')').find('td:eq('+$colIndex+') input:first').focus().select();
                        
                        return e.preventDefault();
                    }

                } else {
                    $grid.datagrid('selectRow', 0);
                    
                    var $rowCell = $this.closest('td'); 
                    var $colIndex = $rowCell.index();
                    
                    $dtlTbl.find('tr:eq(0)').find('td:eq('+$colIndex+') input:first').focus().select();
                    
                    return e.preventDefault();
                }
                
            } else if (keyCode == 40) { /* down */
                
                var $this = $(this);
                var $dtlTbl = $this.closest('table');
                var $row = $this.closest('tr');    
                var $grid = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>');
                
                $grid.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').focus(); 
                
                var selected = $grid.datagrid('getSelected');
                if (selected) { 

                    var $rowIndex = $row.index();
                    /*var index = $grid.datagrid('getRowIndex', selected);*/
                    var index = $rowIndex;
                    
                    var selectionIndex = index + 1;
                    var $panelView = $grid.datagrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                    var $dgRow = $panelView.find('tr[datagrid-row-index="'+selectionIndex+'"]');

                    $grid.datagrid('unselectRow', index);

                    if ($dgRow.length) {

                        $grid.datagrid('selectRow', selectionIndex);
                        var ypos = $dgRow.offset().top - $panelView.offset().top - $dgRow.height();

                        $panelView.animate({
                            scrollTop: $panelView.scrollTop() + ypos
                        }, 100);
                        
                        var $rowCell = $this.closest('td'); 
                        var $colIndex = $rowCell.index();

                        $row.next('tr:visible').find('td:eq('+$colIndex+') input:first').focus().select();
                        
                        return e.preventDefault();

                    } else {
                        $grid.datagrid('selectRow', 0);
                        $panelView.scrollTop(0);
                        
                        var $rowCell = $this.closest('td'); 
                        var $colIndex = $rowCell.index();

                        $dtlTbl.find('tr:eq(0)').find('td:eq('+$colIndex+') input:first').focus().select();
                        
                        return e.preventDefault();
                    }

                } else {
                    $grid.datagrid('selectRow', 0);
                    
                    var $rowCell = $this.closest('td'); 
                    var $colIndex = $rowCell.index();

                    $dtlTbl.find('tr:eq(0)').find('td:eq('+$colIndex+') input:first').focus().select();
                    
                    return e.preventDefault();
                }
            }
        });
        <?php } ?>
        
        <?php
        if ($this->isGridType == 'datagrid' && isset($this->isGridShow) && $this->isGridShow) {
        ?>
        $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('getPanel').find('div.datagrid-view > .datagrid-view1 > .datagrid-body, div.datagrid-view > .datagrid-view2 > .datagrid-body').attr('tabindex', '-1').css('outline-style','none').bind('keydown',function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            var $grid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
            
            if (!e.ctrlKey && keyCode == 38) { /* up */
                var selected = $grid.datagrid('getSelected');
                if (selected) {

                    var index = $grid.datagrid('getRowIndex', selected);
                    var selectionIndex = index - 1;
                    var $panelView = $grid.datagrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                    var $dgRow = $panelView.find('tr[datagrid-row-index="'+selectionIndex+'"]');

                    $grid.datagrid('unselectRow', index);

                    if ($dgRow.length) {

                        $grid.datagrid('selectRow', selectionIndex);
                        var ypos = $dgRow.offset().top - $panelView.offset().top - $dgRow.height();

                        $panelView.animate({
                            scrollTop: $panelView.scrollTop() + ypos
                        }, 100);

                    } else {
                        $grid.datagrid('selectRow', $grid.datagrid('getRows').length - 1);
                        $panelView.scrollTop(1000);
                    }

                } else {
                    $grid.datagrid('selectRow', 0);
                }
            } else if (keyCode == 40) { /* down */
                var selected = $grid.datagrid('getSelected');
                if (selected) { 

                    var index = $grid.datagrid('getRowIndex', selected);
                    var selectionIndex = index + 1;
                    var $panelView = $grid.datagrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                    var $dgRow = $panelView.find('tr[datagrid-row-index="'+selectionIndex+'"]');

                    $grid.datagrid('unselectRow', index);

                    if ($dgRow.length) {

                        $grid.datagrid('selectRow', selectionIndex);
                        var ypos = $dgRow.offset().top - $panelView.offset().top - $dgRow.height();

                        $panelView.animate({
                            scrollTop: $panelView.scrollTop() + ypos
                        }, 100);

                    } else {
                        $grid.datagrid('selectRow', 0);
                        $panelView.scrollTop(0);
                    }

                } else {
                    $grid.datagrid('selectRow', 0);
                }
            } else if (keyCode == 13) { /* enter */
                <?php
                if ($this->chooseType == 'single' || $this->chooseType == 'singlealways') {
                ?>
                var selected = $grid.datagrid('getSelected');
                dblClickCommonSelectableDataGrid_<?php echo $this->metaDataId ?>(selected);
                e.preventDefault();
                return false;
                <?php
                } else {
                ?>
                basketCommonSelectableDataGrid_<?php echo $this->metaDataId ?>();
                <?php
                }
                ?>
            } else if (e.ctrlKey && keyCode == 38) {
                $('#commonSelectableTabOrder_<?php echo $this->metaDataId; ?>').find('.datagrid-view2 > .datagrid-header tr.datagrid-filter-row > td:eq(0) input[type=text]').focus().select();
                e.preventDefault();
                return false;
            }
        });
        <?php
        } elseif (isset($this->isGridShow) && $this->isGridShow) {
        ?>
        $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).treegrid('getPanel').find('div.datagrid-view > .datagrid-view1 > .datagrid-body, div.datagrid-view > .datagrid-view2 > .datagrid-body').attr('tabindex', '-1').css('outline-style','none').bind('keydown',function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            var $grid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
                    
            switch (keyCode) {
                case 38: // up
                    var row = $grid.treegrid('getSelected'),
                        gridData = $grid.treegrid('getData'),
                        i = 0,
                        found = false,
                        previousRowId = 0;
                    
                    if (row) {
                        
                        while ((i < gridData.length) && !found) {
                            var gridRow = gridData[i];
                            if (i === 0) {
                                previousRowId = gridRow.<?php echo $isTreeGridData['id']; ?>;
                            }
                            if (gridRow.children) {
                                var j = 0;
                                while (j < gridRow.children.length && !found) {
                                    child = gridRow.children[j];
                                    if (child.<?php echo $isTreeGridData['id']; ?> === row.<?php echo $isTreeGridData['id']; ?>) {
                                        found = true;
                                    } else {
                                        previousRowId = child.<?php echo $isTreeGridData['id']; ?>;
                                    }
                                    j++;
                                }
                            } else if (gridRow.<?php echo $isTreeGridData['id']; ?> === row.<?php echo $isTreeGridData['id']; ?>) {
                                found = true;
                            } else {
                                previousRowId = gridRow.<?php echo $isTreeGridData['id']; ?>;
                            }
                            i++;
                        }
                        if (found) {
                            var $panelView = $grid.treegrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                            var $dgRow = $panelView.find('tr[node-id="'+previousRowId+'"]');
                            var ypos = $dgRow.offset().top - $panelView.offset().top - $dgRow.height();
                            
                            $grid.treegrid('unselectAll');
                            $grid.treegrid('select', previousRowId);
                            
                            $panelView.animate({
                                scrollTop: $panelView.scrollTop() + ypos
                            }, 100);
                        }
                        
                    } else {
                        $grid.treegrid('unselectAll');
                        $grid.treegrid('select', gridData[0]['<?php echo $isTreeGridData['id']; ?>']);
                    }
                    
                break;
                case 40: // down
                    var row = $grid.treegrid('getSelected'), 
                        gridData = $grid.treegrid('getData'),
                        i = gridData.length - 1,
                        found = false,
                        previousRowId = 0;
                    
                    if (row) {
                        while ((i >= 0 ) && !found) {
                            var gridRow = gridData[i];
                            if (i === gridData.length - 1) {
                                previousRowId = gridRow.<?php echo $isTreeGridData['id']; ?>;
                            }
                            if (gridRow.children) {
                                var j = gridRow.children.length - 1;
                                while (j >= 0 && !found) {
                                    child = gridRow.children[j];
                                    if (child.<?php echo $isTreeGridData['id']; ?> === row.<?php echo $isTreeGridData['id']; ?>) {
                                        found = true;
                                    } else {
                                        previousRowId = child.<?php echo $isTreeGridData['id']; ?>;
                                    }
                                    j--;
                                }
                            } else if (gridRow.<?php echo $isTreeGridData['id']; ?> === row.<?php echo $isTreeGridData['id']; ?>) {
                                found = true;
                            } else {
                                previousRowId = gridRow.<?php echo $isTreeGridData['id']; ?>;
                            }
                            i--;
                        }
                        
                        if (found) {
                            var $panelView = $grid.treegrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                            var $dgRow = $panelView.find('tr[node-id="'+previousRowId+'"]');
                            var ypos = $dgRow.offset().top - $panelView.offset().top - $dgRow.height();
                            
                            $grid.treegrid('unselectAll');
                            $grid.treegrid('select', previousRowId);
                            
                            $panelView.animate({
                                scrollTop: $panelView.scrollTop() + ypos
                            }, 100);
                        }
                    } else {
                        $grid.treegrid('unselectAll');
                        $grid.treegrid('select', gridData[0]['<?php echo $isTreeGridData['id']; ?>']);
                    }
                    
                break;
                case 13: // enter
                    <?php
                    if ($this->chooseType == 'single' || $this->chooseType == 'singlealways') {
                    ?>
                    var selected = $grid.treegrid('getSelected');
                    dblClickCommonSelectableDataGrid_<?php echo $this->metaDataId ?>(selected);
                    e.preventDefault();
                    return false;
                    <?php
                    } else {
                    ?>
                    basketCommonSelectableDataGrid_<?php echo $this->metaDataId ?>();
                    <?php
                    }
                    ?>
                break;
            }
        });
        <?php
        } 
        ?>    
                
        $("#commonSelectableTabOrder_<?php echo $this->metaDataId; ?>").on('click', 'input[name="mandatoryNoSearch"]', function() {
            var $this = $(this);
            var $thisForm = $this.closest('form#default-mandatory-criteria-form');
            
            if ($this.is(':checked')) {
                
                $thisForm.find("input[type=text]").attr('readonly', 'readonly');
                $thisForm.find("select.select2").select2('readonly', true);
                $thisForm.find('button').attr('disabled', 'disabled');
                
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>', 
                    processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
                    paramRealPath: '<?php echo $this->paramRealPath; ?>', 
                    defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>").serialize(), 
                    treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
                };

                var $dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
                var $op = $dataGrid.datagrid('options');
                if ($op.idField === null) {
                    $dataGrid.datagrid('load', dvSearchParam);
                } else {
                    $dataGrid.treegrid('load', dvSearchParam);
                }
            
            } else {
                $thisForm.find("input[type=text]").removeAttr('readonly');
                $thisForm.find("button").removeAttr('disabled');
                $thisForm.find("select.select2").select2('readonly', false);
            }
        });        
        $('.combo-grid-autocomplete').on('keydown', function (e) {
            if (e.which === 13) {
                var $this = $(this),
                    codePath = $this.data('code-fieldpath'),
                    namePath = $this.data('name-fieldpath'),
                    dvSearchParam = {
                        metaDataId: '<?php echo $this->metaDataId; ?>', 
                        processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
                        paramRealPath: '<?php echo $this->paramRealPath; ?>', 
                        defaultCriteriaData: 'criteriaCondition['+namePath+']=like&param['+namePath+']='+$this.val(),
                        // filterRules: JSON.stringify([{"field": namePath, "value": $this.val()}]),
                        // defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize(), 
                        treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
                    };
                    
                var $dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
                var $op = $dataGrid.datagrid('options');
                if ($op.idField === null) {
                    $dataGrid.datagrid('load', dvSearchParam);
                } else {
                    $dataGrid.treegrid('load', dvSearchParam);
                }   
                e.preventDefault();
                return false;
            }   
        });
        $('.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').on('keydown', 'input:not(.meta-autocomplete, .dateInit, .meta-name-autocomplete)', function (e) {
            if (e.which === 13) {
                
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>', 
                    processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
                    paramRealPath: '<?php echo $this->paramRealPath; ?>', 
                    defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize(), 
                    treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
                };
                
                var $dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
                var $op = $dataGrid.datagrid('options');
                if ($op.idField === null) {
                    $dataGrid.datagrid('load', dvSearchParam);
                } else {
                    $dataGrid.treegrid('load', dvSearchParam);
                }
                
                e.preventDefault();
                return false;
            }
        });
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on('change', 'input.popupInit, .dropdownInput', function (e) {
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>', 
                processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
                paramRealPath: '<?php echo $this->paramRealPath; ?>', 
                defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize(), 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
            };   
            
            var $dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
            var $op = $dataGrid.datagrid('options');
            if ($op.idField === null) {
                $dataGrid.datagrid('load', dvSearchParam);
            } else {
                $dataGrid.treegrid('load', dvSearchParam);
            }
        });
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on('changeDate', 'input.dateInit', function (e) {
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>', 
                processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
                paramRealPath: '<?php echo $this->paramRealPath; ?>', 
                defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize(), 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
            };            
            
            var $dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
            var $op = $dataGrid.datagrid('options');
            if ($op.idField === null) {
                $dataGrid.datagrid('load', dvSearchParam);
            } else {
                $dataGrid.treegrid('load', dvSearchParam);
            }
        });
        $('.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').on('change', 'input.linked-combo', function(){
            var _this = $(this);
            var _outParam = _this.attr('data-out-param');
            var _outParamSplit = _outParam.split('|');

            for (var i = 0; i < _outParamSplit.length; i++) {
                var selfParam = _outParamSplit[i];
                var _cellSelect = $('.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').find("select[data-path='" + selfParam + "']");

                if (_cellSelect.length === 0) {
                    var _cellInp = $('.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').find("input[data-path='" + selfParam + "']");

                    if (_this.val().length > 0 && _cellInp.length > 0) {
                        _cellInp.closest('.meta-autocomplete-wrap').find('input').removeAttr('readonly disabled');
                        _cellInp.parent().find('button').removeAttr('disabled');
                    }

                } else {

                    var _inParams = '';

                    if (_cellSelect.length) {
                        if (typeof _cellSelect.attr("data-in-param") !== 'undefined' && _cellSelect.attr("data-in-param") !== '') {
                            var _inParam = _cellSelect.attr("data-in-param");
                            var _inParamSplit = _inParam.split("|");

                            for (var j = 0; j < _inParamSplit.length; j++) {
                                var _lastCombo = $('.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').find("input[data-path='" + _inParamSplit[j] + "']");
                                if (_lastCombo.length && _lastCombo.val() !== '') {
                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                } else {
                                    var _lastCombo = $('.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').find("input[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                }
                            }
                        }
                    }

                    if (_inParams !== '') {
                        $.ajax({
                            type: 'post',
                            url: 'mdobject/bpLinkedCombo',
                            data: {inputMetaDataId: '<?php echo $this->metaDataId; ?>', selfParam: selfParam, inputParams: _inParams},
                            dataType: "json",
                            async: false,
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true
                                });
                            },
                            success: function (dataStr) {
                                if (_cellSelect.hasClass("select2")) {
                                    _cellSelect.select2('val', '');
                                    _cellSelect.select2('enable');
                                } else {
                                    _cellSelect.val('');
                                    _cellSelect.removeAttr('disabled');
                                }
                                $("option:gt(0)", _cellSelect).remove();

                                var comboData = dataStr[selfParam];
                                _cellSelect.addClass("data-combo-set");

                                $.each(comboData, function () {
                                    _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                });
                                Core.initSelect2(_cellSelect);
                                Core.unblockUI();
                            },
                            error: function () {
                                alert("Error");
                            }
                        });

                    } else {
                        _cellSelect.select2('val', '');
                        _cellSelect.select2('disable');
                        $("option:gt(0)", _cellSelect).remove();
                        Core.initSelect2(_cellSelect);
                    }
                }
            }
        });
        
        setTimeout(function(){
            var $basketCriteriaForm = $('#commonSelectableSearchForm_<?php echo $this->metaDataId; ?>');

            if ($basketCriteriaForm.find('> .form-body > div:not(.d-none)').length == 0 && $('#commonSelectableTabTreeFilter_<?php echo $this->metaDataId; ?>').length == 0) {
                
                $('.selectableGrid-<?php echo $this->metaDataId; ?> .lefttab-content-selectableGrid').addClass('d-none')
                $('.selectableGrid-<?php echo $this->metaDataId; ?> .left-content-selectableGrid').addClass('d-none');
            }
        }, 0);
        
        <?php 
        if (issetParam($this->row['IS_CRITERIA_ALWAYS_OPEN']) == '1' || issetParam($this->row['IS_ENTER_FILTER']) == '1') { 
        ?>
        $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>").on('keydown', 'input[data-path]', function(e){
            var code = e.keyCode || e.which;

            if (code == '13') {
                commonSelectableDataGridSearch_<?php echo $this->metaDataId ?>();
            }
        });

        $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>").on('change', 'select[data-path], input.popupInit', function(){
            commonSelectableDataGridSearch_<?php echo $this->metaDataId ?>();
        });        
        <?php
        }
        if (issetParam($this->row['IS_CRITERIA_ALWAYS_OPEN']) == '1') {
        ?>
        $('a[href="#commonSelectableTabFilter_<?php echo $this->metaDataId ?>"]').click();
        <?php
        } 
        if (issetParam($this->dataGridOptionData['KEYUPSERVERSIDESEARCH']) == 'true') {
        ?>  
                        
        var dvRequestTimer;
        
        $('div.selectableGrid-datatable-<?php echo $this->metaDataId; ?>').on('keyup', 'input.datagrid-filter', function(e) {
            
            var $this = $(this), keyCode = (e.keyCode ? e.keyCode : e.which);
            
            eventDelay(function() {

                if (keyCode != 9 && keyCode != 37 && keyCode != 39) {

                    dvRequestTimer && clearTimeout(dvRequestTimer);

                    dvRequestTimer = setTimeout(function() {

                        var getFilterPath = $this.attr('name'), filterVal = trim($this.val());       
                        var $dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
                        var dataGridOpt = $dataGrid.datagrid('options');
                        
                        $dataGrid.datagrid('loading');

                        if (dvRequest_<?php echo $this->metaDataId; ?> != null) {
                            dvRequest_<?php echo $this->metaDataId; ?>.abort();
                        }

                        var getSortFields = getDataGridSortFields($("div.selectableGrid-datatable-<?php echo $this->metaDataId; ?>"));
                        
                        var postParams = {
                            metaDataId: '<?php echo $this->metaDataId; ?>', 
                            processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
                            paramRealPath: '<?php echo $this->paramRealPath; ?>', 
                            defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId; ?>").serialize(), 
                            filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                            sortFields: getSortFields, 
                            page: 1, 
                            rows: dataGridOpt.pageSize, 
                            treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
                        };

                        dvRequest_<?php echo $this->metaDataId; ?> = $.ajax({
                            type: 'post',
                            url: 'mdobject/dataViewDataGrid',
                            data: postParams, 
                            dataType: 'json', 
                            success: function(data) {

                                dvRequest_<?php echo $this->metaDataId; ?> = null;

                                if (dataGridOpt.idField === null) {
                                    $dataGrid.datagrid('loadData', data);
                                } else {
                                    $dataGrid.treegrid('loadData', data);
                                }

                                $dataGrid.datagrid('loaded');
                            }
                        });
                    }, 200);
                }
            
            }, 400);
        });
        <?php
        }
        ?>
    });
    
    function basketGridColumnToLabel_<?php echo $this->metaDataId ?>() {
        
        var $parent = $('.selectableGrid-<?php echo $this->metaDataId; ?>');
        var $basketTab = $parent.find('#commonSelectableTabBasket_<?php echo $this->metaDataId; ?>');
        
        $basketTab.find('.selectableGrid-nogrid-row-<?php echo $this->metaDataId; ?>').css({'display': 'none'});
        
        var $basketLabel = $parent.find('.grid-column-to-label');
        var $datagrid = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>');
        var $columns = $datagrid.datagrid('getColumnFields');
        var $vals = $datagrid.datagrid('getRows')[0];
        var $appendHtml = '<div class="panel panel-default bg-inverse overflowYauto" style="min-height: 374px;max-height: 374px;margin-bottom: 0 !important"><table class="table sheetTable"><tbody>';
        
        for (var key in $columns) {
            fieldName = $columns[key];
            if (fieldName != 'action') {
                var col = $datagrid.datagrid('getColumnOption', fieldName);
                value = $vals[fieldName] ? $vals[fieldName] : '';
                $appendHtml += '<tr><td style="width: 170px; height: 24px" class="left-padding">'+col.title+':</td><td style="padding-left: 5px">'+value+'</td></tr>';
            }
        }
        
        $appendHtml += '</tbody></table></div>';

        $basketLabel.html($appendHtml);
    }
    
    function commonSelectableDataGridSearch_<?php echo $this->metaDataId ?>() {
    
        var dvSearchParam = {
            metaDataId: '<?php echo $this->metaDataId; ?>', 
            processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
            paramRealPath: '<?php echo $this->paramRealPath; ?>', 
            defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>").serialize(), 
            treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
        };            
            
        var $dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);
        var $op = $dataGrid.datagrid('options');
        
        if ($op.idField === null) {
            $dataGrid.datagrid('load', dvSearchParam);
        } else {
            $dataGrid.treegrid('load', dvSearchParam);
        }
    }
    
    function commonSelectableDataGridReset_<?php echo $this->metaDataId ?>() {
        var $searchForm = $('#commonSelectableSearchForm_<?php echo $this->metaDataId ?>');
        $searchForm.find('input[type=text]').val('');
        $searchForm.find('select.select2:not([name*="criteriaCondition["])').select2('val', '');
    }

    function basketCommonSelectableDataGrid_<?php echo $this->metaDataId ?>() {
        
        $('#commonSelectedCount_<?php echo $this->metaDataId; ?>').pulsate({
            color: '#F3565D', 
            reach: 9,
            speed: 500,
            glow: false, 
            repeat: 1
        });    
            
        var $basketGrid = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>');
        var chooseType = '<?php echo $this->chooseType; ?>';
        
        if (chooseType == 'multi') {
            
            var basketRows = $basketGrid.datagrid('getRows');
            var dvRows = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('getSelections');
            
            setTimeout(function() {
                
                var basketData = [];
            
                for (var i = 0; i < dvRows.length; i++) {
                    var dvRow = dvRows[i];
                    var isAddRow = true;

                    for (var j = 0; j < basketRows.length; j++) {
                        var subrow = basketRows[j];
                        if (subrow.<?php echo $this->primaryField; ?> === dvRow.<?php echo $this->primaryField; ?>) {
                            isAddRow = false;
                        }
                    }

                    if (isAddRow) {

                        dvRow['action'] = '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>';
                        basketRows.push(dvRow);

                        if (jsonConfigGroup.hasOwnProperty('is_child_select') && jsonConfigGroup.is_child_select) {
                            var dvDefaultCriteria = {};        
                            var getPostData = $('form.mandatory-criteria-form-<?php echo $this->metaDataId ?>, form#commonSelectableSearchForm_<?php echo $this->metaDataId ?>').serializeArray();
                            
                            if (getPostData) {
                                for (var fdata = 0; fdata < getPostData.length; fdata++) {
                                    var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                                    if(mPath === null) continue;

                                    dvDefaultCriteria[mPath[1]] = [{operator: '=', operand: getPostData[fdata].value}];
                                }        
                            }                      
                            dvDefaultCriteria['parentid'] = [{operator: '=', operand: dvRow.<?php echo $this->primaryField; ?>}];

                            var getChildBasketData = $.ajax({
                                type: 'post',
                                url: 'api/callDataview',
                                data: {dataviewId: '<?php echo $this->metaDataId ?>', criteriaData: dvDefaultCriteria},
                                dataType: 'json',
                                async: false,
                                success: function(data) {                            
                                    return data.result;
                                }
                            });  
                            getChildBasketData = getChildBasketData.responseJSON.result;                    

                            if (getChildBasketData && getChildBasketData.length) {
                                for (var jj = 0; jj < getChildBasketData.length; jj++) {
                                    getChildBasketData[jj]['action'] = '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>';
                                    basketRows.push(getChildBasketData[jj]);
                                }                        
                            }                        
                        }                        
                    }
                }

                var obj = {'total': basketRows.length, 'rows': basketRows};  
                $basketGrid.datagrid('loadData', obj);
                
                $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($basketGrid.datagrid('getData').total);
            
                selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>(); 
                
            }, 5);
            
        } else {
            
            var rows = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('getSelections');
            
            if ((chooseType == 'single' || chooseType == 'singlealways') && rows.length > 0) {
                $basketGrid.datagrid('loadData', []);
            }
        
            var subrows = $basketGrid.datagrid('getRows');
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                for (var j = 0; j < subrows.length; j++) {
                    var subrow = subrows[j];
                    if (subrow.<?php echo $this->primaryField; ?> === row.<?php echo $this->primaryField; ?>) {
                        isAddRow = false;
                    }
                }
                if (isAddRow) {
                    $basketGrid.datagrid('appendRow', {
                        action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>', 
                        <?php echo $this->dataGridBody; ?>
                    });
                }
            }
            
            $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($basketGrid.datagrid('getData').total);
            
            basketGridColumnToLabel_<?php echo $this->metaDataId ?>();
            selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>(); 
        }       
    }
    
    function clickCommonSelectableDataGrid_<?php echo $this->metaDataId ?>(row, type) {   
        var chooseTypeDataGrid = '<?php echo $this->chooseType; ?>';
        
        if (isComboGrid_<?php echo $this->metaDataId; ?> && chooseTypeDataGrid != 'single' && chooseTypeDataGrid != 'singlealways') {
            
            if (Object.keys(row).length) {
                var rows = [row];
                if ($('.bp-combogrid-jsonrow-<?php echo $this->metaDataId; ?>').val()) {
                    var savedComboJson = JSON.parse($('.bp-combogrid-jsonrow-<?php echo $this->metaDataId; ?>').val()),
                        rows2 = [];
                    
                    savedComboJson = savedComboJson.filter(function(item) {
                        return item.id !== row.id
                    });
                    
                    rows = savedComboJson.concat(rows);
                    
                    if (type !== 'insert') {
                        rows = rows.filter(function(item) {
                            return item.id !== row.id
                        });
                    }
                }
                console.log(rows)
                var jsonStr = JSON.stringify(rows).replace(/&quot;/g, '\\&quot;');
                $('.bp-combogrid-jsonrow-<?php echo $this->metaDataId; ?>').val(jsonStr).attr('data-ismulti', '1').trigger('change');
            }
        }
    }
    function dblClickCommonSelectableDataGrid_<?php echo $this->metaDataId ?>(row) {   
        var $basketGrid = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId; ?>');
        var chooseTypeDataGrid = '<?php echo $this->chooseType; ?>';  
        var isAddRow = true, rows = [];
        
        if (isComboGrid_<?php echo $this->metaDataId; ?> && chooseTypeDataGrid != 'single' && chooseTypeDataGrid != 'singlealways') {
            return;
        } else if (isComboGrid_<?php echo $this->metaDataId; ?>) {
            rows[0] = row;
            var jsonStr = JSON.stringify(rows).replace(/&quot;/g, '\\&quot;');
            $('.bp-combogrid-jsonrow-<?php echo $this->metaDataId; ?>').val(jsonStr).trigger('change');
            return;
        }        
        
        if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
            $basketGrid.datagrid('loadData', []);
        } else {
            $('#commonSelectedCount_<?php echo $this->metaDataId; ?>').pulsate({
                color: '#F3565D', 
                reach: 9,
                speed: 500,
                glow: false, 
                repeat: 1
            });   
        }                
        var rows = $basketGrid.datagrid('getRows');

        for (var j = 0; j < rows.length; j++) {
            var subrow = rows[j];
            if (subrow.<?php echo $this->primaryField; ?> === row.<?php echo $this->primaryField; ?>) {
                isAddRow = false;
            }
        }
        if (isAddRow) {
            $basketGrid.datagrid('appendRow', {
                action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>', 
                <?php 
                echo $this->dataGridBody; 
                if (isset($isPrintCopies)) {
                    echo 'printcopies: 0,';
                }
                ?>
            });

            if (jsonConfigGroup.hasOwnProperty('is_child_select') && jsonConfigGroup.is_child_select) {
                var dvDefaultCriteria = {};        
                var getPostData = $('form.mandatory-criteria-form-<?php echo $this->metaDataId ?>, form#commonSelectableSearchForm_<?php echo $this->metaDataId ?>').serializeArray();
                
                if (getPostData) {
                    for (var fdata = 0; fdata < getPostData.length; fdata++) {
                        var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                        if(mPath === null) continue;

                        dvDefaultCriteria[mPath[1]] = [{operator: '=', operand: getPostData[fdata].value}];
                    }        
                }                      
                dvDefaultCriteria['parentid'] = [{operator: '=', operand: row.<?php echo $this->primaryField; ?>}];

                var getChildBasketData = $.ajax({
                    type: 'post',
                    url: 'api/callDataview',
                    data: {dataviewId: '<?php echo $this->metaDataId ?>', criteriaData: dvDefaultCriteria},
                    dataType: 'json',
                    async: false,
                    success: function(data) {                            
                        return data.result;
                    }
                });  
                getChildBasketData = getChildBasketData.responseJSON.result;                    

                if (getChildBasketData && getChildBasketData.length) {
                    for (var jj = 0; jj < getChildBasketData.length; jj++) {
                        row = getChildBasketData[jj];
                        $basketGrid.datagrid('appendRow', {
                            action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>', 
                            <?php 
                            echo $this->dataGridBody; 
                            if (isset($isPrintCopies)) {
                                echo 'printcopies: 0,';
                            }
                            ?>
                        });                        
                    }                        
                }                        
            }            
        }
        $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($basketGrid.datagrid('getData').total);        
        
        if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
            basketGridColumnToLabel_<?php echo $this->metaDataId ?>();
            $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).closest("div.ui-dialog").children("div.ui-dialog-buttonpane").find("button.datagrid-choose-btn").click();
        } else {
            selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>();
        }   
        return;
    }
            
    function deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(target) {
        var $rf = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>');
        $rf.datagrid('deleteRow', getRowIndex(target));
        $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($rf.datagrid('getData').total);
        selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>();        
    }
    
    function multiDeleteCommonSelectableBasket_<?php echo $this->metaDataId ?>() {
        var $rf = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>');
        var rows = $rf.datagrid('getSelections');
        var rowsLength = rows.length;
        var rr = [];
        
        for (i = 0; i < rowsLength; i++) {
            rr.push(rows[i]);
        }
        $.map(rr, function(row){
            var index = $rf.datagrid('getRowIndex', row);
            $rf.datagrid('deleteRow', index);
        });
        
        $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($rf.datagrid('getData').total);
        selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>();
    }
    
    function hiddenLeftContentSelectableGrid_<?php echo $this->metaDataId ?>(element) {
        var $this = $(element);
        var $parent = $this.closest('li');
        
        if ($parent.hasClass('disabled')) {
            return false;
        }
        
        var dataStatus = $this.attr('data-status');
        var $dataView = $('.selectableGrid-datatable-<?php echo $this->metaDataId; ?>').find('.datagrid-view').height() - 15;
        $('.selectableGrid-datatable-<?php echo $this->metaDataId; ?>').find('.datagrid-view').attr('style', 'height:'+ $dataView +'px');
                  
        if (dataStatus == 'open') {
            
            $this.attr('data-status', 'closed');
            
            $('.selectableGrid-li-<?php echo $this->metaDataId; ?>').removeClass('fa-angle-left').addClass('fa-angle-right');
            
            $('.selectableGrid-<?php echo $this->metaDataId; ?> .lefttab-content-selectableGrid').css({'-ms-flex': '0 0 120px', 'flex': '0 0 120px', 'max-width': '120px'});
            $('.selectableGrid-<?php echo $this->metaDataId; ?> .left-content-selectableGrid').addClass('d-none').css({'-ms-flex': '0 0 120px', 'flex': '0 0 120px', 'max-width': '120px'});
            
        } else {
            
            $this.attr('data-status', 'open');
            
            $('.selectableGrid-li-<?php echo $this->metaDataId; ?>').removeClass('fa-angle-right').addClass('fa-angle-left');
            $('.selectableGrid-<?php echo $this->metaDataId; ?> .lefttab-content-selectableGrid').css({'-ms-flex': '0 0 330px', 'flex': '0 0 330px', 'max-width': '330px'});
            $('.selectableGrid-<?php echo $this->metaDataId; ?> .left-content-selectableGrid').removeClass('d-none').css({'-ms-flex': '0 0 330px', 'flex': '0 0 330px', 'max-width': '330px'});
        }
        
        $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('resize');
        $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('resize');
    }
    
    function basketAppendRow_<?php echo $this->metaDataId ?>(row){
        $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('appendRow', {
            action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>', 
            <?php echo $this->dataGridBody; ?>       
        });
        $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('getData').total);
    }
    
    function basketUpdateRow_<?php echo $this->metaDataId ?>(row, rowIndex){
        $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('updateRow', {
                action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->metaDataId ?>(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>', 
                index: rowIndex,
                row: {<?php echo $this->dataGridBody; ?>
            }
        });
        $("#commonSelectedCount_<?php echo $this->metaDataId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>').datagrid('getData').total);
    }
    
    function selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>() {
        var $commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?> = $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>'),
            $commonSelectableTabBasket_<?php echo $this->metaDataId ?> = $('#commonSelectableTabBasket_<?php echo $this->metaDataId ?>'),
            dataGridParamAttrLink_<?php echo $this->metaDataId ?> = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('getFooterRows');
            $selectableGridDatatable_<?php echo $this->metaDataId ?> = $('.selectableGrid-datatable-<?php echo $this->metaDataId ?>'),
            footerJson__<?php echo $this->metaDataId ?> = {};
        
        if (typeof dataGridParamAttrLink_<?php echo $this->metaDataId ?> !== 'undefined' && dataGridParamAttrLink_<?php echo $this->metaDataId ?>.length > 0) {
            dataGridParamAttrLink_<?php echo $this->metaDataId ?> = dataGridParamAttrLink_<?php echo $this->metaDataId ?>[0];
            $.each(dataGridParamAttrLink_<?php echo $this->metaDataId ?>, function(paramName, paramVal){
                $.each($commonSelectableTabBasket_<?php echo $this->metaDataId ?>.find('.datagrid-body table tbody tr'), function(idx, dgRows){
                    if ($(dgRows).find('td[field=' + paramName + ']').length > 0) {
                        var $cellField = $(dgRows).find('td[field=' + paramName + '] .datagrid-cell span').length ? $(dgRows).find('td[field=' + paramName + '] .datagrid-cell span') : $(dgRows).find('td[field=' + paramName + '] .datagrid-cell');
                        if (typeof footerJson__<?php echo $this->metaDataId ?>[paramName] !== 'undefined') {
                            footerJson__<?php echo $this->metaDataId ?>[paramName] = 
                                calculateFooterVal('sum', Number($cellField.text().replace(/,/g, '')), Number(footerJson__<?php echo $this->metaDataId ?>[paramName]), $commonSelectableTabBasket_<?php echo $this->metaDataId ?>.find('.datagrid-body table tbody').find('td[field=' + paramName + ']').length);
                        } else {                                
                            footerJson__<?php echo $this->metaDataId ?>[paramName] = Number($cellField.text().replace(/,/g, ''));
                        }
                    }
                });
            });
            
            $commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>.datagrid('reloadFooter', [footerJson__<?php echo $this->metaDataId ?>]);
        }
    }
    
    function calculateFooterVal(columnAggregate, value, oldValue, len) {
        var tmpVal = 0;
        if (!isNaN(value) && !isNaN(oldValue)) {
            switch (columnAggregate) {
                case 'sum': 
                        tmpVal = value + oldValue;
                    break;
                case 'avg': 
                        tmpVal = (value + (oldValue * (len - 1) )) / len;
                    break;
                case 'max': 
                    tmpVal = Math.max(value, oldValue);
                    break;
                case 'min': 
                    tmpVal = Math.min(value, oldValue)
                    break;
            }
        }
        
        return tmpVal;
    }
    
    function checkBeforeSelectedRows_<?php echo $this->metaDataId ?>() {
        if ((paramSelectedRow_<?php echo $this->metaDataId ?>.items).length != 0) {
            var selectedRows = paramSelectedRow_<?php echo $this->metaDataId ?>.items;
            var $commonSelectableGridName_<?php echo $this->metaDataId ?> = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>);

            for (var i = 0; i < selectedRows.length; i++) {
                var row = selectedRows[i];
                var mainRows = $commonSelectableGridName_<?php echo $this->metaDataId ?>.datagrid('getRows');

                for (var j = 0; j < mainRows.length; j++) {
                    var mainRow = mainRows[j];
                    if (mainRow.<?php echo $this->primaryField; ?> === row.<?php echo $this->primaryField; ?>) {
                        $commonSelectableGridName_<?php echo $this->metaDataId ?>.datagrid('checkRow', j);
                    }
                }
            }  
        }
    }
    
    function printCopiesChange_<?php echo $this->metaDataId ?>(elem, rowIndex) {
        var $val = $(elem).val();
        $('#commonSelectableBasketDataGrid_<?php echo $this->metaDataId; ?>').datagrid('updateRow',{
            index: rowIndex,
            row: {
                printcopiesinput: $val, 
                printcopies: $val
            }
        });
    }
    
    <?php
    if ($this->isTree) {
    ?>
    lookupDrawTree_<?php echo $this->metaDataId; ?>();
    
    function lookupDrawTree_<?php echo $this->metaDataId; ?>() {
        
        $('#treeContainer', selectableGrid_<?php echo $this->metaDataId; ?>).html('<div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo" style="max-height: 380px; overflow: auto; overflow-x: hidden;"></div>');
        
        var dataViewStructureTreeView_<?php echo $this->metaDataId; ?> = $('div#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', selectableGrid_<?php echo $this->metaDataId; ?>);
        var dataViewId = '<?php echo $this->metaDataId; ?>';
        var metaDataId = '';
        var treeCategoryVal = $('#treeCategory', selectableGrid_<?php echo $this->metaDataId; ?>).val();
        
        if (treeCategoryVal > 0) {
            metaDataId = treeCategoryVal;
        }
        
        dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.jstree({
            "core": {
                "themes": {
                    "responsive": true
                },
                "check_callback": true,
                "data": {
                    "url": function (node) {
                        return 'mdobject/getAjaxTree';
                    },
                    "data": function (node) {
                        return {'parent': node.id, 'dataViewId' : dataViewId, 'structureMetaDataId': metaDataId};
                    }
                }
            },
            "types": {
                "default": {
                    "icon": "icon-folder2 text-orange-300"
                }
            },
            "plugins": ["types", "cookies"]
        }).bind("select_node.jstree", function (e, data) {
            var nid = data.node.id === 'null' ? '' : data.node.id;
            lookupSelectDataViewByCategory_<?php echo $this->metaDataId; ?>(nid);
        }).bind('loaded.jstree', function (e, data) {
            
            setTimeout(function(){
                var $jstreeOpen = dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('.jstree-open');
                var $jstreeClicked = dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('.jstree-clicked');
                
                if ($jstreeClicked.length) {
                    $jstreeClicked.focus();
                }
                
                if ($jstreeOpen.length) {
                    dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.animate({
                        scrollTop: Number($jstreeOpen.offset().top) - 150
                    }, 1000);
                }
            }, 1);
        });
    }

    function lookupSelectDataViewByCategory_<?php echo $this->metaDataId; ?>(folderId) {
        
        var dataGrid = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>), 
            op = dataGrid.datagrid('options');
        
        var dvSearchParam = {
            metaDataId: '<?php echo $this->metaDataId; ?>', 
            processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
            paramRealPath: '<?php echo $this->paramRealPath; ?>', 
            defaultCriteriaData: $("#commonSelectableSearchForm_<?php echo $this->metaDataId ?>").serialize(), 
            treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?>
        }; 
            
        if (folderId == 'all') {
            
            if (op.idField === null) {
                dataGrid.datagrid('load', dvSearchParam);
            } else {
                dataGrid.treegrid('load', dvSearchParam);
            }
            
        } else {
            
            var chosenCategory = $('#treeCategory', selectableGrid_<?php echo $this->metaDataId; ?>).val();
            var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
            
            dvSearchParam['cardFilterData'] = filtedField + '=' + folderId;
            
            if (op.idField === null) {
                dataGrid.datagrid('load', dvSearchParam);
            } else {
                dataGrid.treegrid('load', dvSearchParam);
            }
        }
    }
    
    <?php
    }
    ?>
    function dvReloadFooterData(grid, data) {
        <?php
        if (Config::getFromCache('javaversion') >= 1) {
        ?>
        var opts = grid.datagrid('options'), isTreegrid = false;
        if (opts.idField) {
            isTreegrid = true;
        }
        
        if (data && data.hasOwnProperty('footer')) {
            if (isTreegrid) {
                var footerData = data.footer;
                footerData[0][opts.treeField] = '';
                footerData[0]['iconCls'] = 'tree-file-hide-icon';
                grid.treegrid('reloadFooter', footerData);
            } else {
                grid.datagrid('reloadFooter', data.footer);
            }
        }
        
        setTimeout(function() {
            if (data && data.hasOwnProperty('total')) {
                if (isTreegrid) {
                    opts = grid.treegrid('options');
                }
                grid.datagrid('getPager').pagination('refresh', {total: data.total, pageNumber: opts.pageNumber});
            } else {
                grid.datagrid('getPager').pagination('refresh', {total: 0});
            }
            //grid.datagrid("getPanel").children("div.datagrid-pager").find('table tbody tr td:eq(3)').text(grid.datagrid("getPanel").children("div.datagrid-pager").find('table tbody tr td:eq(3)').text().replace('of', '/'));                    
        }, 0);
        <?php
        }
        ?>
    }
</script>