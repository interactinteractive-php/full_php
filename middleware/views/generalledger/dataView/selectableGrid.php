<div class="row selectableGrid-<?php echo $this->uniqId; ?>">
    <div class="col-md-12">
        <?php echo isset($this->defaultCriteriaMandatory) ? $this->defaultCriteriaMandatory : ''; ?>
    </div>
    <div class="col-md-1 left-content-selectableGrid">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#commonSelectableTabFilter_<?php echo $this->uniqId; ?>" onclick="hiddenLeftContentSelectableGrid_<?php echo $this->uniqId ?>(this)" data-status="closed" data-toggle="tab" class="nav-link">Шүүлтүүр <!--<i class='fa fa-angle-left selectableGrid-li-<?php echo $this->uniqId; ?>'></i>--></a>
                </li>
            </ul>
            <div class="tab-content pt5 pb0">
                <div class="tab-pane in selectableGrid-tab-<?php echo $this->uniqId; ?>" id="commonSelectableTabFilter_<?php echo $this->uniqId; ?>">
                    <div id="commonSelectableSearchForm_<?php echo $this->uniqId ?>">
                        <div class="form-body">
                            <?php 
                            echo $this->dataGridSearchForm; 
                            echo Form::hidden(array('name' => 'folderId', 'id' => 'folderId')); 
                            ?>    
                        </div>    
                        <div class="form-actions float-right">
                            <?php echo Form::button(array('class' => 'btn blue btn-sm mr-1', 'onclick' => 'commonSelectableDataGridSearch_'. $this->uniqId . '()', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn grey-cascade btn-sm', 'onclick' => 'commonSelectableDataGridReset_'. $this->uniqId . '();', 'value' => $this->lang->line('clear_btn'))); ?>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 right-content-selectableGrid-<?php echo $this->uniqId; ?>">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item commonSelectableTabOrder-<?php echo $this->uniqId; ?>">
                    <a href="#commonSelectableTabOrder_<?php echo $this->uniqId; ?>" class="nav-link active" data-toggle="tab">Жагсаалт</a>
                </li>
                <li class="nav-item commonSelectableTabBasket_<?php echo $this->uniqId; ?>">
                    <a href="#commonSelectableTabBasket_<?php echo $this->uniqId; ?>" class="nav-link" data-toggle="tab"><?php echo $this->lang->line('basket'); ?> ( <span id="commonSelectedCount_<?php echo $this->uniqId; ?>" class="dv-basket-count">0</span> )</a>
                </li>
            </ul>
            <div class="tab-content pt5 pb0 tab-content-selectableGrid-<?php echo $this->uniqId; ?>">
                <div class="tab-pane active in disactive" id="commonSelectableTabOrder_<?php echo $this->uniqId; ?>">
                    <div class="row selectableGrid-row-<?php echo $this->uniqId; ?>">
                        <?php
                        if (isset($this->processButtons)) {
                        ?>
                        <div class="col-md-12 selectableGrid-datatable-process-btn-<?php echo $this->uniqId; ?>"  style="left:-90px; flex: 0 0 1092px; max-width: 1092px;">
                            <div class="table-toolbar">
                                <div class="row">
                                    <div class="col-md-10 staticWindowCommandBtn">
                                        <?php 
                                        if (isset($this->processButtons['commandBtn'])) {
                                            if ($this->processButtons['commandBtn'] != '') {
                                                echo $this->processButtons['commandBtn'];                  
                                            }
                                        } elseif (isset($this->processButtons['add_btn'])) {
                                            echo $this->processButtons['add_btn'];
                                        } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="col-md-12 jeasyuiTheme3 selectableGrid-datatable-<?php echo $this->uniqId; ?>" style="left:-90px; flex: 0 0 1092px; max-width: 1092px; <?php /*echo strlen($this->uniqId) === 16 ? 1168 : 1088 */?>">
                            <table id="objectdatagrid_<?php echo $this->uniqId; ?>" style="height: 380px"></table>
                        </div>    
                    </div>
                </div>
                <div class="tab-pane in disactive" id="commonSelectableTabBasket_<?php echo $this->uniqId; ?>">
                    <div class="table-toolbar"></div>
                    <div class="disactive row selectableGrid-row-<?php echo $this->uniqId; ?>">
                        <?php
                        if (isset($this->processButtons) && isset($this->processButtons['edit_btn'])) {
                        ?>
                        <div class="col-md-12 selectableGrid-datatable-process-btn-<?php echo $this->uniqId; ?>"  style="left:-90px; flex: 0 0 1092px; max-width: 1092px;">
                            <div class="table-toolbar">
                                <div class="row">
                                    <div class="col-md-10 staticWindowCommandBtn">
                                        <?php
                                        echo $this->processButtons['edit_btn'];
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="col-md-12 jeasyuiTheme3 selectableGrid-datatable-<?php echo $this->uniqId; ?>" style="left:-90px; flex: 0 0 1092px; max-width: 1092px; <?php /*echo strlen($this->uniqId) === 16 ? 1168 : 1088 */?>">    
                            <table id="commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
<style type="text/css">
#commonSelectableTreeView {
    overflow: auto;
    height: 350px !important;
}   
#commonSelectableSearchForm_<?php echo $this->uniqId ?> .form-group {
    margin-bottom: 5px !important;
}
#commonSelectableSearchForm_<?php echo $this->uniqId ?> .form-actions {
    margin-top: 15px !important;
}
#commonSelectableTabOrder_<?php echo $this->uniqId; ?> *::-moz-selection, #commonSelectableTabBasket_<?php echo $this->uniqId; ?> *::-moz-selection { background:transparent; }
#commonSelectableTabOrder_<?php echo $this->uniqId; ?> *::selection, #commonSelectableTabBasket_<?php echo $this->uniqId; ?> *::selection { background:transparent; }
.datagrid-header-row {
    height: 25px !important;
}
.tabbable-line > .nav-tabs > li > a {
    background-color: transparent;
}
</style>

<script type="text/javascript">
    var commonSelectableGridName_<?php echo $this->uniqId ?> = 'objectdatagrid_<?php echo $this->uniqId; ?>';
    var paramSelectedRow_<?php echo $this->uniqId ?> = <?php echo $this->selectedRow; ?>;
    var accountid = '<?php echo $this->accountId; ?>';
    var defaultCriteria = (accountid != '' ? 'param[filterStartDate]=fiscalperiodstartdate&param[filterEndDate]=fiscalperiodenddate&param[filterIsConnectGLString]=0&criteriaCondition[filterIsConnectGLString]==&param[accountid]='+accountid : '');
    var dvLoadSuccessData_<?php echo $this->uniqId; ?> = null;
    
    $(function() {
        
        $('.staticWindowCommandBtn',".selectableGrid-<?php echo $this->uniqId; ?>").children(".btn-group").css("display", "block");
        $('.staticWindowCommandBtn',".selectableGrid-<?php echo $this->uniqId; ?>").find("a").each(function() {
            var _this = $(this);
            var aOnclick = _this.attr("onclick");
            aOnclick = aOnclick.replace(/<?php echo $this->metaDataId; ?>/i, "<?php echo $this->metaDataId; ?>_<?php echo $this->uniqId; ?>");
            _this.attr("onclick", aOnclick);
        });
        
        $('#commonSelectableSearchForm_<?php echo $this->uniqId ?>').find('input, select').removeClass('input-medium').addClass('form-control-sm').removeAttr('required');
        
        /*$("#commonSelectableSearchForm_<?php echo $this->uniqId ?>").on("keydown", 'input', function(e) {
            if (e.which === 13) {
                commonSelectableDataGridSearch_<?php echo $this->uniqId ?>();
                return false;
            }
        });*/
        
        $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).<?php echo $this->isGridType; ?>({
            url: 'mdmetadata/commonSelectableDataGrid',
            queryParams: {
                metaDataId:'<?php echo $this->metaDataId; ?>', 
                processMetaDataId:'<?php echo $this->processMetaDataId; ?>', 
                paramMetaDataId:'<?php echo $this->paramMetaDataId; ?>', 
                paramRealPath:'<?php echo $this->paramRealPath; ?>', 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>'<?php echo $this->searchParams; ?> ,
                <?php if (isset($this->defaultCriteriaMandatory)) { ?>
                defaultCriteriaData: $("div.selectableGrid-<?php echo $this->uniqId; ?> form#default-criteria-form, div.selectableGrid-<?php echo $this->uniqId; ?> form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>, #commonSelectableSearchForm_<?php echo $this->metaDataId; ?>").find('input, select, textarea').serialize() + '&<?php echo $this->requestParams; ?>', 
                <?php } ?>
            },
            <?php
            if ($this->isTreeGridData) {
                parse_str($this->isTreeGridData, $isTreeGridData);
                echo "idField: '".$isTreeGridData['id']."',"."\n"; 
                echo "treeField: '".$isTreeGridData['name']."',"."\n";
            }
            ?>        
            rownumbers:true,
            singleSelect:<?php echo $this->singleSelect; ?>,
            ctrlSelect:true,
            pagination:true,
            pageSize:30,
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
                }
            }
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
                dblClickCommonSelectableDataGrid_<?php echo $this->uniqId ?>(row);
            },
            onRowContextMenu:function(e, index, row) {
                e.preventDefault();
                $(this).datagrid('selectRow', index);
                $.contextMenu({
                    selector: "#commonSelectableTabOrder_<?php echo $this->uniqId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    callback: function(key, opt) {
                        if (key === 'basket') {
                            basketCommonSelectableDataGrid_<?php echo $this->uniqId ?>();
                        }
                    },
                    items: {
                        "basket": {name: "Сагсанд нэмэх", icon: "plus-circle"}
                    }
                });
                $.uniform.update();
            },
            <?php 
            if ($this->isTreeGridData) {
            ?>
            onBeforeLoad: function(row, param) { 
                if (!row) {   
                    delete param.id;
                    <?php
                    if (Config::getFromCache('javaversion') >= 1) {
                    ?>
                    var _thisGrid = $(this);
                    param.pagingWithoutAggregate = 1;

                    setTimeout(function() {
                        $.ajax({
                            type: 'post',   
                            url: 'mdobject/dataViewAggregateData',
                            data: param,
                            dataType: 'json',
                            success: function(data) {
                                dvLoadSuccessData_<?php echo $this->uniqId; ?> = data;
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

                setTimeout(function() {
                    $.ajax({
                        type: 'post',   
                        url: 'mdobject/dataViewAggregateData',
                        data: param,
                        dataType: 'json',
                        success: function(data) {
                            dvLoadSuccessData_<?php echo $this->uniqId; ?> = data;
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
                if (data.status === 'error') {
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
                    echo 'showTreeGridMessage(_thisGrid);'."\n";
                } else {
                    echo 'showGridMessage(_thisGrid);'."\n";
                } 
                ?>

                var $dataView = $('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').find('.datagrid-view').height() - 15;
                var $panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');
                
                $('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').find('.datagrid-view').attr('style', 'height:'+ $dataView +'px');

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
                
                <?php if (isset($isMergeCells)) { ?>
                    var isMergeColumn = JSON.parse('<?php echo json_encode((isset($this->dataGridColumnData['isMergeColumn'])) ? $this->dataGridColumnData['isMergeColumn'] : array()); ?>');
                    _thisGrid.datagrid("autoMergeCells", isMergeColumn);
                <?php } ?>
                    
                _thisGrid.promise().done(function() {
                    _thisGrid.datagrid('resize');
                    $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('resize');
                });
                
                dvReloadFooterData(_thisGrid, dvLoadSuccessData_<?php echo $this->uniqId; ?>);
            }
        });
        $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).datagrid('enableFilter');
        
        $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid({
            url:'',
            rownumbers:true,
            singleSelect:true,
            pagination:false,
            remoteSort:false,
            height:380,
            fitColumn:true,
            showFooter:false,
            frozenColumns:<?php echo ((isset($this->dataGridHeader['freeze'])) ? $this->dataGridHeader['freeze'] : ''); ?>,
            columns:<?php echo ((isset($this->dataGridHeader['header'])) ? $this->dataGridHeader['header'] : $this->dataGridHead); ?>,
            onLoadSuccess:function(data) {
                
                var _thisGrid = $(this);
                var $panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");
                
                <?php echo ((isset($this->dataGridHeader['filterCenterInit'])) ? $this->dataGridHeader['filterCenterInit'] : ''); ?>
                <?php echo ((isset($this->dataGridHeader['filterDateInit'])) ? $this->dataGridHeader['filterDateInit'] : ''); ?>
                <?php echo ((isset($this->dataGridHeader['filterDateTimeInit'])) ? $this->dataGridHeader['filterDateTimeInit'] : ''); ?>
                <?php echo ((isset($this->dataGridHeader['filterBigDecimalInit'])) ? $this->dataGridHeader['filterBigDecimalInit'] : ''); ?>
                <?php echo ((isset($this->dataGridHeader['filterNumberInit'])) ? $this->dataGridHeader['filterNumberInit'] : ''); ?>                                
                
                Core.initDateInput($panelView);
                Core.initDateTimeInput($panelView);
                Core.initNumberInput($panelView);
                Core.initDateMinuteInput($panelView);
                Core.initDateMinuteMaskInput($panelView);
                Core.initAccountCodeMask($panelView);
                Core.initStoreKeeperKeyCodeMask($panelView);
            
                Core.initFancybox($panelView);
                
                _thisGrid.promise().done(function() {
                    _thisGrid.datagrid('resize');
                });
            }
        });
        
        $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('loadData', []);
        
        $("a[href=#commonSelectableTabBasket_<?php echo $this->uniqId; ?>]").on("click", function() {
            $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('resize');
        });
        $('a[href="#commonSelectableTabBasket_<?php echo $this->uniqId; ?>"]',".selectableGrid-<?php echo $this->uniqId; ?>").on('shown.bs.tab', function(e){
            $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('resize');
        });
        $('a[href="#commonSelectableTabOrder_<?php echo $this->uniqId; ?>"]', ".selectableGrid-<?php echo $this->uniqId; ?>").on('shown.bs.tab', function(e){
            $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).datagrid('resize');
        });
        
        $(window).bind('resize', function() {
            $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).datagrid('resize');
            $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('resize');
        });

        if ((paramSelectedRow_<?php echo $this->uniqId ?>.items).length != 0) {
            rows = paramSelectedRow_<?php echo $this->uniqId ?>.items;
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var isAddRow = true;
                var subrows = $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getRows');

                for (var j = 0; j < subrows.length; j++) {
                    var subrow = subrows[j];
                    if (subrow.<?php echo $this->primaryField; ?> === row.<?php echo $this->primaryField; ?>) {
                        isAddRow = false;
                    }
                }
                if (isAddRow) {
                  $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('appendRow', {
                      <?php echo $this->dataGridBody; ?>
                      action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php  echo $this->uniqId ?>(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>'
                  });
                }
            }  
            $("body").find("#commonSelectedCount_<?php echo $this->uniqId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getData').total);
        }
        
        if ($('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getRows').length > 0) {
            $('a[href="#commonSelectableTabBasket_<?php echo $this->uniqId; ?>"]', ".selectableGrid-<?php echo $this->uniqId; ?>").tab("show");
        }
        <?php if (isset($this->defaultCriteriaMandatory)) { ?>
            $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("change", '.popupInit, .dropdownInput', function (e) {
                var _this = $(this);
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div.selectableGrid-<?php echo $this->uniqId; ?>").serialize() + '&<?php echo $this->requestParams; ?>', 
                    workSpaceId: '', 
                    workSpaceParams: '', 
                    uriParams: '', 
                    drillDownDefaultCriteria: '', 
                    treeConfigs: ''
                };

                $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).<?php echo $this->isGridType; ?>('load', dvSearchParam);
            });
        <?php } ?>
    });
    
    function commonSelectableDataGridSearch_<?php echo $this->uniqId ?>() {
        $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).<?php echo $this->isGridType; ?>('load', {    
            metaDataId: '<?php echo $this->metaDataId; ?>', 
            processMetaDataId: '<?php echo $this->processMetaDataId; ?>', 
            paramMetaDataId: '<?php echo $this->paramMetaDataId; ?>', 
            paramRealPath: '<?php echo $this->paramRealPath; ?>',
            defaultCriteriaData: defaultCriteria + '&' + $("#commonSelectableSearchForm_<?php echo $this->uniqId ?>").find('input, select').serialize() + '&' + $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div.selectableGrid-<?php echo $this->uniqId; ?>").serialize() + '&<?php echo $this->requestParams; ?>'
        });
    }
    
    function commonSelectableDataGridReset_<?php echo $this->uniqId ?>() {
        var $searchForm = $("#commonSelectableSearchForm_<?php echo $this->uniqId ?>");
        $searchForm.find('input[type=text]').val('');
        $searchForm.find('select.select2:not([name*="criteriaCondition["])').select2('val', '');
    }

    function basketCommonSelectableDataGrid_<?php echo $this->uniqId ?>() {
        $('#commonSelectedCount_<?php echo $this->uniqId; ?>').pulsate({
            color: '#F3565D', 
            reach: 9,
            speed: 500,
            glow: false, 
            repeat: 1
        });            
        
        var rows = $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).datagrid('getSelections');
        <?php
        if ($this->chooseType == 'single') {
            echo 'if (rows.length > 0) {';
            echo "$('#commonSelectableBasketDataGrid_".$this->uniqId."').datagrid('loadData', []);";
            echo '}';
        }
        ?>
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var isAddRow = true;
            var subrows = $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getRows');
            for (var j = 0; j < subrows.length; j++) {
                var subrow = subrows[j];
                if (subrow.<?php echo $this->primaryField; ?> === row.<?php echo $this->primaryField; ?>) {
                    isAddRow = false;
                }
            }
            if (isAddRow) {
                $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('appendRow', {
                    <?php echo $this->dataGridBody; ?>
                    action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php  echo $this->uniqId ?>(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>'
                });
            }
        }
        $("body").find("#commonSelectedCount_<?php echo $this->uniqId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getData').total);
        Core.initNumberInput();
    }
    
    function dblClickCommonSelectableDataGrid_<?php echo $this->uniqId ?>(row) {
        var chooseTypeDataGrid = '<?php echo $this->chooseType; ?>';     
        var isAddRow = true;

        if (chooseTypeDataGrid == 'single') {
            $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId; ?>').datagrid('loadData', []);
        }         
        var rows = $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getRows');
        for (var j = 0; j < rows.length; j++) {
            var subrow = rows[j];
            if (subrow.<?php echo $this->primaryField; ?> === row.<?php echo $this->primaryField; ?>) {
                isAddRow = false;
            }
        }
        if (isAddRow) {
            $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('appendRow', {
                <?php echo $this->dataGridBody; ?>
                action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php  echo $this->uniqId ?>(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>'
            });
        }
        $("body").find("#commonSelectedCount_<?php echo $this->uniqId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getData').total);
        
        if (chooseTypeDataGrid == 'single') {
            $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).closest("div.ui-dialog").children("div.ui-dialog-buttonpane").find("button.datagrid-choose-btn").trigger('click');
        } else {
            $('#commonSelectedCount_<?php echo $this->uniqId; ?>').pulsate({
                color: '#F3565D', 
                reach: 9,
                speed: 500,
                glow: false, 
                repeat: 1
            });   
        }    
    }    
            
    function deleteCommonSelectableBasket_<?php echo $this->uniqId ?>(target) {
        $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('deleteRow', getRowIndex(target));
        $("body").find("#commonSelectedCount_<?php echo $this->uniqId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getData').total);
    }
    
    function hiddenLeftContentSelectableGrid_<?php echo $this->uniqId ?>(element) {
        var dataStatus = $(element).attr('data-status');
        var $width = $(element).width() + 8;
        
        var $dataView = $('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').find('.datagrid-view').height() - 15;
        $('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').find('.datagrid-view').attr('style', 'height:'+ $dataView +'px');
        
        if (dataStatus == 'open') {
            
            var widthPlus = $width + 12;
            
            var selectableGridWidth = $('.active', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-row-<?php echo $this->uniqId; ?>').width() + ($width*4) + 22 ;
            
            $(element).attr('data-status', 'closed');
            $('.left-content-selectableGrid', '.selectableGrid-<?php echo $this->uniqId; ?>').removeClass('col-md-4').addClass('col-md-1');
            $('.right-content-selectableGrid', '.selectableGrid-<?php echo $this->uniqId; ?>').removeClass('col-md-8').addClass('col-md-11');
            
            $('.selectableGrid-li-<?php echo $this->uniqId; ?>').removeClass('fa-angle-left').addClass('fa-angle-right');
            $('.selectableGrid-tab-<?php echo $this->uniqId; ?>').addClass('hidden');
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').attr('style', 'left:-' + $width + 'px; width:'+ selectableGridWidth +'px;');
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').addClass('pl0 pr0');
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-process-btn-<?php echo $this->uniqId; ?>').attr('style', 'left:-' + $width + 'px; width:'+ selectableGridWidth +'px;');
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-process-btn-<?php echo $this->uniqId; ?>').addClass('pl0 pr0');
            $('.active', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').attr('style', 'left:-' + $width + 'px; max-width:'+ selectableGridWidth +'px; flex: 0 0 '+ selectableGridWidth +'px');
            
            if (widthPlus < 110) {
                $(element).attr('style', 'width:'+($width + 20)+'px');
            }
        } else {
            
            $(element).attr('data-status', 'open');
            $('.left-content-selectableGrid', '.selectableGrid-<?php echo $this->uniqId; ?>').removeClass('col-md-1').addClass('col-md-4');
            $('.right-content-selectableGrid', '.selectableGrid-<?php echo $this->uniqId; ?>').removeClass('col-md-11').addClass('col-md-8');
            
            $('.selectableGrid-li-<?php echo $this->uniqId; ?>').removeClass('fa-angle-right').addClass('fa-angle-left');
            $('.selectableGrid-tab-<?php echo $this->uniqId; ?>').removeClass('hidden');
            
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').attr('style', '');
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').removeClass('pl0 pr0');
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-process-btn-<?php echo $this->uniqId; ?>').attr('style', '');
            $('.disactive', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-process-btn-<?php echo $this->uniqId; ?>').removeClass('pl0 pr0');
            
            $('.active', '.tab-content-selectableGrid-<?php echo $this->uniqId ?>').find('.selectableGrid-datatable-<?php echo $this->uniqId; ?>').attr('style', '');
            
            $(element).attr('style', '');
        }
        
        $('#'+commonSelectableGridName_<?php echo $this->uniqId ?>).datagrid('resize');
        $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('resize');
    }
    
    function basketAppendRow_<?php echo $this->uniqId ?>(row){
         $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('appendRow', {
            <?php echo $this->dataGridBody; ?>     
            action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->uniqId ?>(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>'
          });
        $("body").find("#commonSelectedCount_<?php echo $this->uniqId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getData').total);
    }
    
    function basketUpdateRow_<?php echo $this->uniqId ?>(row, rowIndex){
        $('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('updateRow', {
            index : rowIndex,
            row : {<?php echo $this->dataGridBody; ?>     
            action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_<?php echo $this->uniqId ?>(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>'
            }
        });
        $("body").find("#commonSelectedCount_<?php echo $this->uniqId; ?>").text($('#commonSelectableBasketDataGrid_<?php echo $this->uniqId ?>').datagrid('getData').total);
    }
    function dvReloadFooterData(grid, data) {
        <?php
        if (Config::getFromCache('javaversion') >= 1) {
        ?>
        if (data && data.hasOwnProperty('total')) {
            var opts = grid.datagrid('options');
            if (opts.idField) {
                opts = grid.treegrid('options');
            }
            grid.datagrid('getPager').pagination('refresh', {total: data.total, pageNumber: opts.pageNumber});
        } else {
            grid.datagrid('getPager').pagination('refresh', {total: 0});
        }
        
        if (data && data.hasOwnProperty('footer')) {
            grid.datagrid('reloadFooter', data.footer);
        } else {
            grid.datagrid('reloadFooter', []);
        }
        <?php
        }
        ?>
    }
</script>