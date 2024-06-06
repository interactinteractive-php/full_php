<div class="row">
    <div class="col-md-12 div-objectdatagrid-<?php echo $this->subUniqId; ?> <?php echo $this->dataGridOptionData['VIEWTHEME']; ?> data-path-uniqid" data-path-uniqid="<?php echo $this->subUniqId; ?>">
        <table id="objectdatagrid-<?php echo $this->subUniqId; ?>"></table>
    </div>
</div> 

<script type="text/javascript">
var objectdatagrid_<?php echo $this->subUniqId; ?> = $('#objectdatagrid-<?php echo $this->subUniqId; ?>');

$(function() {

    objectdatagrid_<?php echo $this->subUniqId; ?>.<?php echo $this->isGridType; ?>({
        <?php echo ($this->isTreeGridData ? '' : 'view: '.($this->subgrid ? 'detailview' : 'horizonscrollview').','."\n"); ?>
        url: 'mdobject/dataViewDataGrid',
        queryParams: {
            metaDataId: '<?php echo $this->metaDataId; ?>', 
            uriParams: '<?php echo $this->uriParams; ?>', 
            treeConfigs: '<?php echo $this->isTreeGridData; ?>',
            subUniqId: '<?php echo $this->subUniqId ?>', 
            srcDataViewId: '<?php echo issetParam($this->srcDataViewId); ?>'
        }, 
        <?php
        if ($this->isTreeGridData) {
            parse_str($this->isTreeGridData, $isTreeGridData);
            echo "idField: '".$isTreeGridData['id']."',"."\n"; 
            echo "treeField: '".$isTreeGridData['name']."',"."\n";
        }
        foreach ($this->dataGridDefaultOption as $k => $row) {
            if ($k == 'resizeHandle') {
                echo "resizeHandle: '" . $this->dataGridOptionData['RESIZEHANDLE'] . "',";
            } elseif ($k == 'fitColumns') {
                echo "fitColumns: " . $this->dataGridOptionData['FITCOLUMNS'] . ",";
            } elseif ($k == 'autoRowHeight') {
                echo "autoRowHeight: " . $this->dataGridOptionData['AUTOROWHEIGHT'] . ",";
            } elseif ($k == 'striped') {
                echo "striped: " . $this->dataGridOptionData['STRIPED'] . ",";
            } elseif ($k == 'method') {
                echo "method: '" . $this->dataGridOptionData['METHOD'] . "',";
            } elseif ($k == 'nowrap') {
                echo "nowrap: " . $this->dataGridOptionData['NOWRAP'] . ",";
            } elseif ($k == 'pagination') {
                echo "pagination: " . $this->dataGridOptionData['PAGINATION'] . ",";
            } elseif ($k == 'rownumbers') {
                echo "rownumbers: " . $this->dataGridOptionData['ROWNUMBERS'] . ",";
            } elseif ($k == 'singleSelect') {
                echo "singleSelect: " . $this->dataGridOptionData['SINGLESELECT'] . ",";
            } elseif ($k == 'ctrlSelect') {
                echo "ctrlSelect: " . $this->dataGridOptionData['CTRLSELECT'] . ",";
            } elseif ($k == 'checkOnSelect') {
                echo "checkOnSelect: " . $this->dataGridOptionData['CHECKONSELECT'] . ",";
            } elseif ($k == 'selectOnCheck') {
                echo "selectOnCheck: " . $this->dataGridOptionData['SELECTONCHECK'] . ",";
            } elseif ($k == 'pagePosition') {
                echo "pagePosition: '" . $this->dataGridOptionData['PAGEPOSITION'] . "',";
            } elseif ($k == 'pageNumber') {
                echo "pageNumber: " . $this->dataGridOptionData['PAGENUMBER'] . ",";
            } elseif ($k == 'pageSize') {
                echo "pageSize: " . $this->dataGridOptionData['PAGESIZE'] . ",";
            } elseif ($k == 'pageList') {
                echo "pageList: " . $this->dataGridOptionData['PAGELIST'] . ",";
            } elseif ($k == 'sortName') {
                if (!empty($this->dataGridOptionData['SORTNAME'])) {
                    echo "sortName: '" . Str::lower($this->dataGridOptionData['SORTNAME']) . "',";
                    echo "sortOrder: '" . $this->dataGridOptionData['SORTORDER'] . "',";
                }
            } elseif ($k == 'multiSort') {
                echo "multiSort: " . $this->dataGridOptionData['MULTISORT'] . ",";
            } elseif ($k == 'remoteSort') {
                echo "remoteSort: " . $this->dataGridOptionData['REMOTESORT'] . ",";
            } elseif ($k == 'showHeader') {
                echo "showHeader: " . $this->dataGridOptionData['SHOWHEADER'] . ",";
            } elseif ($k == 'showFooter') {
                echo "showFooter: " . $this->dataGridOptionData['SHOWFOOTER'] . ",";
            } elseif ($k == 'scrollbarSize') {
                echo "scrollbarSize: " . $this->dataGridOptionData['SCROLLBARSIZE'] . ",";
            } elseif ($k == 'mergeCells') {
                if ($this->dataGridOptionData['MERGECELLS'] == 'true') {
                    $isMergeCells = true;
                }
            }
        }
        ?>
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
        frozenColumns: <?php echo ((isset($this->dataGridColumnData['freeze'])) ? $this->dataGridColumnData['freeze'] : ''); ?>,
        columns: <?php echo ((isset($this->dataGridColumnData['header'])) ? $this->dataGridColumnData['header'] : ''); ?>,
        onClickRow: function(index, row) {
            <?php 
            if ($this->isUseSidebar == '1') {
                echo 'detailRightSidebar_' . $this->subUniqId . '();';
            }
            ?>
        },
        onResize: function() {
            subgridSetParentHeight(this);
        },
        <?php 
        echo $this->subgrid;

        if ($this->isTreeGridData) {
            echo 'onLoadSuccess: function(row, data){'."\n";
        } else {
            echo 'onLoadSuccess: function(data){'."\n";
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
            var _this = this;
            var _thisGrid = objectdatagrid_<?php echo $this->subUniqId; ?>;
            var $panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");
            var $panelFilterRow = $panelView.find('.datagrid-filter-row');

            if (_thisGrid.datagrid('getRows').length == 0) {
                $panelView.find('.datagrid-footer').hide();
            } else {
                $panelView.find('.datagrid-footer').show();
            }
            
            $('div.div-objectdatagrid-<?php echo $this->subUniqId; ?>').find("input.datagrid-filter[data-filter='1']").removeAttr('data-filter');

            <?php 
            echo Arr::get($this->dataGridColumnData, 'filterCenterInit');
            echo Arr::get($this->dataGridColumnData, 'filterDateInit');
            echo Arr::get($this->dataGridColumnData, 'filterDateTimeInit');
            echo Arr::get($this->dataGridColumnData, 'filterTimeInit');
            echo Arr::get($this->dataGridColumnData, 'filterBigDecimalInit');
            echo Arr::get($this->dataGridColumnData, 'filterNumberInit');
            ?>
                
            <?php if (isset($isMergeCells)) { ?>
                _thisGrid.datagrid('autoMergeCells', JSON.parse('<?php echo json_encode((isset($this->dataGridColumnData['isMergeColumn'])) ? $this->dataGridColumnData['isMergeColumn'] : array()); ?>'));
            <?php } ?>
            
            if ($panelFilterRow.length) {
                Core.initNumberInput($panelFilterRow);
                Core.initDateInput($panelFilterRow);
                Core.initDateTimeInput($panelFilterRow);
                Core.initDateMaskInput($panelFilterRow);
                Core.initDateMinuteInput($panelFilterRow);
                Core.initTimeInput($panelFilterRow);
                Core.initAccountCodeMask($panelFilterRow);
                Core.initStoreKeeperKeyCodeMask($panelFilterRow);
            }
        
            Core.initPulsate($panelView);
            Core.initFancybox($panelView);
            
            _thisGrid.datagrid('resize'); 
            
            <?php 
            if ($this->isTreeGridData) {
                echo "showTreeGridMessage(_thisGrid, '".issetParam($this->dataGridOptionData['MSGNORECORDFOUND'])."', true);"."\n";
            } else {
                echo "showGridMessage(_thisGrid, '".issetParam($this->dataGridOptionData['MSGNORECORDFOUND'])."', true);"."\n";
            } 
            ?>
                    
            <?php if (isset($this->srcDataViewId)) { ?>
            setTimeout(function() {  
                subgridSetParentHeight(_this);
            }, 0);
            <?php } ?>
        }
    });
    
    <?php if ($this->dataGridOptionData['ENABLEFILTER'] == 'true') { ?>
        objectdatagrid_<?php echo $this->subUniqId; ?>.datagrid('enableFilter');
    <?php } ?>
    
    $('body').on('keyup', 'div.div-objectdatagrid-<?php echo $this->subUniqId; ?> input.datagrid-filter', function(e){
        $(this).attr('data-filter', '1');
    });

    $('body').on('focusout', 'div.div-objectdatagrid-<?php echo $this->subUniqId; ?> input.datagrid-filter', function(e){
        
        var op = objectdatagrid_<?php echo $this->subUniqId; ?>.datagrid('options');
        
        if (typeof op.hasOwnProperty('filterOnlyEnterKey') == 'undefined' || (typeof op.hasOwnProperty('filterOnlyEnterKey') !== 'undefined' && !op.filterOnlyEnterKey)) {
            var $this = $(this);
            if (typeof $this.attr('data-filter') !== 'undefined') {
                $this.removeAttr('data-filter');
                customSearch_<?php echo $this->subUniqId; ?>(e, this, objectdatagrid_<?php echo $this->subUniqId; ?>);
            }
        }
    });

});

function customSearch_<?php echo $this->subUniqId; ?>(e, obj, grdId) {
    var fieldName = $(obj).attr('name');
    var rule = grdId.datagrid('getFilterRule', fieldName);
    var operator = rule == null ? 'contains' : rule.op;
    var code = (e.keyCode ? e.keyCode : e.which);

    if (code != 13) {
        if (obj.value != '') {
            grdId.datagrid('addFilterRule', { field: fieldName, op: operator, value: obj.value });
        } else {
            grdId.datagrid('removeFilterRule', fieldName);
        }
        grdId.datagrid('doFilter');
    }
}

function detailRightSidebar_<?php echo $this->subUniqId; ?>() {
    var rowData = getDataViewSelectedRows(<?php echo $this->subUniqId; ?>);
    if (typeof rowData !== 'undefined') {
        var jsonObj = rowData[0];

        $.ajax({
            type: 'post',
            url: 'mdobject/explorerSidebar',
            data: {
                dataViewId: '<?php echo $this->metaDataId; ?>', 
                refStructureId: '<?php echo $this->refStructureId; ?>', 
                selectedRow: jsonObj
            },
            success: function (data) {
                var $objectDataView = $("#" + objectdatagrid_<?php echo $this->subUniqId; ?>.closest('.main-dataview-container').attr('id'));
                var _thisToggler = $(".stoggler", $objectDataView);
                var centersidebar = $(".center-sidebar", $objectDataView);
                var rightsidebar = $(".right-sidebar", $objectDataView);
                centersidebar.removeClass("col-md-12").addClass("col-md-9");
                rightsidebar.addClass("col-md-3").css("margin-top: 18px;");
                rightsidebar.find(".glyphicon-chevron-right").parent().hide();
                rightsidebar.find(".glyphicon-chevron-left").hide();
                rightsidebar.find(".sidebar-right").show();
                rightsidebar.find(".right-sidebar-content").show().html(data);
                rightsidebar.find(".right-sidebar-content").find('.explorer-toggler').addClass('hidden');
                rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn();
                rightsidebar.find(".glyphicon-chevron-right").fadeIn();
                rightsidebar.attr('data-status', 'opened');
                _thisToggler.addClass("sidebar-opened");
                $(window).trigger("resize");
            },
            error: function () {
                alert("Error");
            }
        }).done(function(){
            Core.initFancybox($('.detail-sidebar-<?php echo $this->metaDataId; ?>'));
        });
    }
}
</script>