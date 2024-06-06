<div class="center-sidebar overflow-hidden content mv-datalist-container<?php echo isset($this->row['gridOption']['theme']) ? ' '.$this->row['gridOption']['theme'] : ''; ?>">
    <?php
    if (!isset($this->isIgnoreFilter) && issetParam($this->row['SEARCH_TYPE']) === 'top') {
    ?>
        <div class="row">        
            <div class="col pl-0 pr-0">
                <div class="kpidv-data-top-filter-col pr-1 mt10"></div>
            </div>
        </div>
    <?php
    }
    ?>  
    <div class="row">        
        <?php
        if (!isset($this->isIgnoreFilter) && issetParam($this->row['SEARCH_TYPE']) !== 'top') {
        ?>
            <div class="col-md-auto pl-0 pr-0">
                <div class="kpidv-data-filter-col pr-1"></div>
            </div>
        <?php
        }
        ?>
        <div class="col right-sidebar-content-for-resize content-wrapper pl-2 pr-2 overflow-hidden">
            <div class="row">
                <div class="col-md-12">
                    
                    <?php
                    if (!isset($this->isBasket) && Input::numeric('isIgnoreTitle') != '1') {
                    ?>
                    <div class="text-uppercase font-weight-bold mt-0 mb-2">
                        <?php echo $this->title; ?>
                    </div>
                    <?php
                    }
                    ?>
                    
                    <div class="table-toolbar">
                        <div class="d-flex">
                            <div class="col p-0">
                                <div class="dv-process-buttons">
                                    <div class="btn-group btn-group-devided">
                                        <?php echo implode('', $this->actions['buttons']); ?>
                                    </div>
                                </div>
                            </div>
                            <?php    
                            if (Input::numeric('isIgnoreRightTools') != 1) {
                            ?>
                            <div class="dv-right-tools-btn ml-2 text-right">
                                <div class="btn-group btn-group-devided">
                                    <?php if ($this->relationComponentsOther) { ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="icon-stack2"></i></button>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-75px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <?php echo Html::anchor(
                                                        'javascript:;', '<i class="far fa-calendar"></i> Calendar', array(
                                                        'class' => 'dropdown-item',
                                                        'title' => 'Calendar',     
                                                        'onclick' => 'kpiIndicatorViewCalendar_'.$this->indicatorId.'(this, \''.$this->indicatorId.'\');'
                                                    ), true  
                                                );  ?>
                                            </div>
                                        </div>
                                    <?php } elseif (isset($this->relationComponentsWidget)) { ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="icon-stack2"></i></button>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-75px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <?php 
                                                if ($this->viewType !== 'list') {
                                                    echo Html::anchor(
                                                            'javascript:;', 'Жагсаалт', array(
                                                            'class' => 'dropdown-item',
                                                            'onclick' => 'kpiIndicatorViewList_'.$this->indicatorId.'(this, \''.$this->indicatorId.'\');'
                                                        ), true  
                                                    );  
                                                } else {
                                                    echo Html::anchor(
                                                            'javascript:;', 'Card', array(
                                                            'class' => 'dropdown-item',
                                                            'onclick' => 'kpiIndicatorViewCalendar_'.$this->indicatorId.'(this, \''.$this->indicatorId.'\');'
                                                        ), true  
                                                    );                                                     
                                                }
                                                ?>
                                            </div>
                                        </div>                                    
                                    <?php
                                    }
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-file-excel"></i>', array(
                                            'class' => 'btn btn-secondary btn-circle btn-sm default',
                                            'title' => 'Excel гаргах',     
                                            'onclick' => 'excelExportKpiIndicatorValue(this, \''.$this->indicatorId.'\');'
                                        ), true  
                                    ); 
                                    
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-cube"></i>', array(
                                            'class' => 'btn btn-secondary btn-circle btn-sm default',
                                            'title' => 'Pivot view',     
                                            'onclick' => 'pivotKpiIndicatorValue(this, \''.$this->indicatorId.'\');'
                                        ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)  
                                    ); 
                                    
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-map-marker"></i>', array(
                                            'class' => 'btn btn-secondary btn-circle btn-sm default',
                                            'title' => 'Google map',
                                            'onclick' => 'googleMapKpiIndicatorValue(this, \''.$this->indicatorId.'\', \''.$this->coordinateField.'\');'
                                        ), $this->coordinateField ? true : false
                                    );
                                    
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-shopping-cart"></i> <span class="save-database-'. $this->indicatorId .'">0</span>', array(
                                            'class' => 'btn btn-secondary btn-sm btn-circle default',
                                            'onclick' => 'dataListUseBasketView_' . $this->indicatorId . '(this);',
                                            'title' => $this->lang->line('META_00113'),
                                        ), true
                                    );                                    
                                    ?>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>    
                <div class="col-md-12 div-objectdatagrid-<?php echo $this->indicatorId; ?> jeasyuiTheme3">
                    
                    <?php
                    if (isset($this->isBasket)) {
                    ?>
                    <table id="objectdatagrid_<?php echo $this->indicatorId; ?>" style="height: 400px"></table>
                    <?php
                    } else {
                    ?>
                    <table id="objectdatagrid-<?php echo $this->indicatorId; ?>"></table>
                    <?php
                    }
                    ?>
                    <div id="md-map-canvas-<?php echo $this->indicatorId; ?>" style="display: none"></div>
                </div>
            </div>    
        </div>     
    </div>    
</div>  

<style type="text/css">
.kpidv-data-filter-col {
    width: 240px;
    border-right: 1px solid #ddd;
    overflow-x: hidden;
    overflow-y: auto;
}
.kpidv-data-filter-col .list-group {
    border: none;
    padding: 0;
}
.kpidv-data-filter-col .list-group-item {
    padding: 0.35rem 0;
}
.kpidv-data-filter-col .list-group-item.opened i {
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);        
}
.kpidv-data-filter-col .list-group-item i {
    min-width: 8px;
}
.kpidv-data-filter-col .list-group-item.active {
    color: rgba(51,51,51,.85);
    background-color: transparent;
    border-color: rgba(93, 173, 226, 0.3);
}
.mv-datalist-show-filter .kpidv-data-filter-col {
    padding-top: 0.625rem !important;
}
.mv-datalist-show-filter .col.content-wrapper {
    padding: 0.625rem !important;
    background-color: #F9F9F9;
}
.mv-datalist-container .jeasyuiTheme3 .datagrid-header .datagrid-cell span, 
.mv-datalist-container .jeasyuiTheme3 .datagrid-view .datagrid-cell-group {
    font-size: 12px;
    font-weight: 700;
    color: #99A1B7;
}
.mv-datalist-container .jeasyuiTheme3 .datagrid-header td {
    background: #eee !important;
    border-style: solid;
}
.mv-datalist-container.no-border .datagrid-header td, 
.mv-datalist-container.no-border .datagrid-body td, 
.mv-datalist-container.no-border .datagrid-footer td {
    border-color: transparent;
}
.mv-datalist-container.no-border .panel-header-eui, 
.mv-datalist-container.no-border .panel-body-eui {
    border-color: transparent;
}
.mv-datalist-container.no-border .datagrid-pager {
    border-color: transparent;
}
.mv-datalist-container.no-border .jeasyuiTheme3 .datagrid-header td {
    border-color: transparent;
}
.kpidv-data-filter-col .jstree-default .jstree-custom-folder-icon.jstree-closed>.jstree-ocl, 
.kpidv-data-filter-col .jstree-default .jstree-custom-folder-icon.jstree-open>.jstree-ocl {
    -webkit-font-smoothing: antialiased;
    background-color: transparent;
    background-image: none;
    background-position: 0 0;
    background-repeat: no-repeat;
    font: normal normal normal 15px/1 icomoon;
    color: #333;
    text-rendering: auto;
    right: 0;
    top: -6px;
}
.kpidv-data-filter-col .mv-tree-filter-icon {
    color:#1B84FF;
    font-size: 15px;
}
.kpidv-data-filter-col .jstree-default .jstree-custom-folder-icon.jstree-closed>.jstree-ocl:before {
    content: "\e9c3";
}
.kpidv-data-filter-col .jstree-default .jstree-custom-folder-icon.jstree-open>.jstree-ocl:before {
    content: "\e9c1";
}
.kpidv-data-filter-col .jstree-default .jstree-custom-folder-icon.green.jstree-closed>.jstree-ocl, 
.kpidv-data-filter-col .jstree-default .jstree-custom-folder-icon.green.jstree-open>.jstree-ocl {
    color: #41c7ae;
}
.kpidv-data-filter-col .jstree-default .jstree-node, 
.kpidv-data-filter-col .jstree-default .jstree-icon {
    background-image: none !important;
}
.kpidv-data-filter-col .jstree-default .jstree-clicked {
    background-color: transparent;
    box-shadow: none;
}
.filter-top-form-wrapper .list-group-item.active {
    background-color: transparent;
    color: #333;
}
.filter-top-form-wrapper .list-group-item {
    padding-left: 0;
    padding-right: 0;
}
.filter-top-form-wrapper .list-group-body {
    max-height: 270px;
    overflow-y: auto;
    overflow-x: hidden;
}
</style>

<script type="text/javascript">
var isIgnoreWfmHistory_<?php echo $this->indicatorId; ?> = false;
var isGoogleMapView_<?php echo $this->indicatorId; ?> = false;
var isFilterShowData_<?php echo $this->indicatorId; ?> = <?php echo ($this->isFilterShowData == '1' ? 'true' : 'false'); ?>;
var idField_<?php echo $this->indicatorId; ?> = '<?php echo $this->idField; ?>';
var indicatorName_<?php echo $this->indicatorId; ?> = '<?php echo Str::nlTobr($this->title); ?>';
var objectdatagrid_<?php echo $this->indicatorId; ?> = $('#objectdatagrid<?php echo (isset($this->isBasket) ? '_' : '-') . $this->indicatorId; ?>');
var drillDownCriteria_<?php echo $this->indicatorId; ?> = '<?php echo $this->drillDownCriteria; ?>';
var filterSearchType_<?php echo $this->indicatorId; ?> = '<?php echo issetParam($this->row['SEARCH_TYPE']); ?>';
var dynamicHeight = 0;
var _selectedRows_<?php echo $this->indicatorId; ?> = [];

if (typeof isKpiIndicatorScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>');
}

setTimeout(function() {

dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->indicatorId; ?>.offset().top - 40;

if (dynamicHeight < 230) {
    dynamicHeight = 350;
}

if (objectdatagrid_<?php echo $this->indicatorId; ?>.closest('.package-tab').length) {
    dynamicHeight = 'auto';
}

<?php
if (Input::numeric('isDrilldown') == '1') {
?>
dynamicHeight = dynamicHeight - 50;
<?php
} elseif ($dynamicHeight = Input::numeric('dynamicHeight')) {
?>
dynamicHeight = <?php echo $dynamicHeight; ?>;
<?php
}
?>

objectdatagrid_<?php echo $this->indicatorId; ?>.attr('height', dynamicHeight); 

<?php
if (!isset($this->isIgnoreFilter)) {
?>
filterKpiIndicatorValueForm(<?php echo $this->indicatorId; ?>);
<?php
}
?>

$(function() {
    
    var gridUrl = 'indicatorDataGrid';
    var queryParams = {
        indicatorId: '<?php echo $this->indicatorId; ?>', 
        treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
        ignoreFirstLoad: isFilterShowData_<?php echo $this->indicatorId; ?>, 
        drillDownCriteria: drillDownCriteria_<?php echo $this->indicatorId; ?>, 
        postHiddenParams: '<?php echo $this->postHiddenParams; ?>', 
        hiddenParams: '<?php echo $this->hiddenParams; ?>', 
        filter: '<?php echo $this->filter; ?>',
        isSqlResult: '<?php echo Input::numeric('isSqlResult'); ?>'
    };
    if (queryParams.isSqlResult) {
        gridUrl = 'generateKpiDataMartByPostNew';
    }
    
    var $checkListParent = objectdatagrid_<?php echo $this->indicatorId; ?>.closest('.mv-checklist-render-parent');
    
    if ($checkListParent.length) {
        var $checkListActive = $checkListParent.find('ul.nav-sidebar a.nav-link.active[data-json]');
        var checkListRowJson = JSON.parse(html_entity_decode($checkListActive.attr('data-json'), 'ENT_QUOTES'));
        
        queryParams.mapSrcMapId = checkListRowJson.mapId;
        queryParams.mapSelectedRow = $checkListParent.find('input[data-path="headerParams"]').val();
    }
    
    var $workspaceParent = objectdatagrid_<?php echo $this->indicatorId; ?>.closest('div.ws-area');
    
    if ($workspaceParent.length) {
        var workSpaceIdAttr = $workspaceParent.attr('id').split('-');
        queryParams.workSpaceId = workSpaceIdAttr[2];
        queryParams.workSpaceParams = decodeURIComponent($workspaceParent.find('div.ws-hidden-params input[type=hidden]').serialize());
    }
        
    objectdatagrid_<?php echo $this->indicatorId; ?>.<?php echo $this->isGridType; ?>({
        <?php
        if (!$this->isTreeGridData && !$this->subgrid) {
        ?>
            view: horizonscrollview,
        <?php
        } elseif ($this->subgrid) { 
        ?>
            view: detailview,
        <?php
        }
        ?>
        url: 'mdform/'+gridUrl,
        method: 'post',
        queryParams: queryParams, 
        <?php
        echo $this->subgrid;
        if ($this->isTreeGridData) {
            echo "idField: '".$this->idField."',"."\n"; 
            echo "treeField: '".$this->nameField."',"."\n";
        }
        ?>
        resizeHandle: 'right',
        fitColumns: false,
        autoRowHeight: true,
        striped: false,
        nowrap: true,
        showHeader: true,
        showFooter: true,
        loadMsg: 'Түр хүлээнэ үү',
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true,
        selectOnCheck: true,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: queryParams.isSqlResult ? 100 : 50,
        pageList: [50,100,200,300,500], 
        remoteFilter: true,
        multiSort: false,
        remoteSort: true,
        scrollbarSize: 18,
        filterDelay: 10000000000,
        clickToEdit: false, 
        <?php
        if (isset($this->row['gridOption'])) {
            foreach ($this->row['gridOption'] as $optName => $optVal) {
                
                if ($optName == 'nowrap') { 
                    echo $optName.': ' . (is_bool($optVal) ? json_encode($optVal) : $optVal) . ', ';
                } elseif ($optName == 'multisort') { 
                    echo 'multiSort: ' . (is_bool($optVal) ? json_encode($optVal) : $optVal) . ', ';
                }
            }
        }
        ?> 
        frozenColumns: [
            <?php echo !$this->isHideCheckBox ? "" : "[{field: 'ck', rowspan:1, checkbox: true }]" ?>
        ],
        columns: [
            <?php echo $this->columns['comboColumnsRender']; ?> 
            [<?php echo $this->columns['columnsRender']; ?>]
        ],
        onSelectAll: function() {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        }, 
        onUnselectAll: function() {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        }, 
        onUnselect: function() {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        },
        onSelect: function(index, row) {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        },   
        <?php
        if (isset(Mdform::$gridStyler['row'])) {
            
            if ($this->isTreeGridData) {
                echo 'rowStyler: function(row) {'."\n";
            } else {
                echo 'rowStyler: function(index, row) {'."\n";
            } 
        ?>
            <?php echo Mdform::$gridStyler['row']; ?>
        },        
        <?php 
        }
        if (isset($this->isBasket)) {
            
            if ($this->isTreeGridData) {
                echo 'onDblClickRow:function(row) {'."\n";
            } else {
                echo 'onDblClickRow:function(index, row) {'."\n";
            } 
            ?>
            dblClickCommonSelectableDataGrid_<?php echo $this->indicatorId; ?>(row);
        },       
                
        <?php
        } else { 
            if ($this->isTreeGridData) {
                echo 'onDblClickRow:function(row) {'."\n";
            } else {
                echo 'onDblClickRow:function(index, row) {'."\n";
            } 
                if (isset($this->row['uniqueField'])) {
                    $primaryField = $this->row['uniqueField'];
                } elseif ($this->idField) {
                    $primaryField = $this->idField;
                } else {
                    $primaryField = 'id';
                }                   
                ?>                        
                var isAdded = false, rowId = row['<?php echo $primaryField; ?>']; 

                for (var key in _selectedRows_<?php echo $this->indicatorId; ?>) {
                    var basketRow = _selectedRows_<?php echo $this->indicatorId; ?>[key], 
                        childId = basketRow['<?php echo $primaryField; ?>'];

                    if (rowId == childId) {
                        isAdded = true;
                        break;
                    } 
                }                            
                if (!isAdded) {
                    _selectedRows_<?php echo $this->indicatorId; ?>.push(row);
                    $('.save-database-<?php echo $this->indicatorId; ?>').text(_selectedRows_<?php echo $this->indicatorId; ?>.length).pulsate({
                        color: '#F3565D', 
                        reach: 9,
                        speed: 500,
                        glow: false, 
                        repeat: 1
                    });   
                } else {
                    $('.save-database-<?php echo $this->indicatorId; ?>').pulsate({
                        color: '#4caf50', 
                        reach: 9,
                        speed: 500,
                        glow: false, 
                        repeat: 1
                    });   
                }  
            },      
        <?php }
        if ($this->isTreeGridData) {
        ?>
        onContextMenu: function (e, row) {
            e.preventDefault();
            
            <?php
            if (!isset($this->isBasket)) {
            ?>
            $(this).treegrid('unselectAll');
            <?php
            }
            ?>
            
            $(this).treegrid('select', row.<?php echo $this->idField; ?>);   
        <?php
        } else {
        ?>
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
            
            <?php
            if (!isset($this->isBasket)) {
            ?>
            $(this).datagrid('unselectAll');
            <?php
            }
            ?>
            
            $(this).datagrid('selectRow', index);
        <?php
        }
        ?>
            <?php
            if (!isset($this->isBasket) && !$this->isDataMart && isset($this->actions['contextMenu']) && $this->actions['contextMenu']) {
                
                $menuCallBack = $menuItems = '';
                
                foreach ($this->actions['contextMenu'] as $menu) {
                    
                    $menu['onClick'] = str_replace('this', '$a', $menu['onClick']);
                    
                    $menuCallBack .= 'if (key === \''.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'\') { ';
                        
                        $menuCallBack .= 'var $a = $(\'<a />\'); ';
                        $menuCallBack .= '$a.attr(\'data-actiontype\', \''.$menu['data-actiontype'].'\')';
                        $menuCallBack .= '.attr(\'data-main-indicatorid\', \''.$menu['data-main-indicatorid'].'\')';
                        $menuCallBack .= '.attr(\'data-structure-indicatorid\', \''.$menu['data-structure-indicatorid'].'\')';
                        $menuCallBack .= '.attr(\'data-crud-indicatorid\', \''.$menu['data-crud-indicatorid'].'\')';
                        $menuCallBack .= '.attr(\'data-mapid\', \''.$menu['data-mapid'].'\'); ';
                        
                        $menuCallBack .= $menu['onClick'];
                    $menuCallBack .= '} ';
                    
                    $menuItems .= '"'.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'": {name: \''.$menu['labelName'].'\', icon: \''.$menu['iconName'].'\'}, ';
                }
            ?>
            $.contextMenu({
                selector: 'div#object-value-list-<?php echo $this->indicatorId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->indicatorId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function (key, opt) {
                    <?php echo $menuCallBack; ?>
                },
                items: {
                    <?php echo $menuItems; ?> 
                }
            });
            <?php
            } elseif (isset($this->isBasket)) {
            ?>
            $.contextMenu({
                selector: 'div#object-value-list-<?php echo $this->indicatorId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->indicatorId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function(key, opt) {
                    if (key === 'basket') {
                        basketCommonSelectableDataGrid_<?php echo $this->indicatorId; ?>();
                    }
                },
                items: {
                    "basket": {name: "<?php echo $this->lang->line('META_00042'); ?>", icon: "plus-circle"}
                }
            });
            <?php
            }
            ?>
        },       
        <?php
        if ($this->isTreeGridData) {
        ?>
        onBeforeLoad: function(row, param) { 
            if (!row) {   
                delete param.id;
            }
        },
        onLoadSuccess: function(row, data) {
        <?php
        } else {
        ?>
        onBeforeLoad: function(param) { 
            <?php
            if (isset($this->isImportManage) && $this->isImportManage) {
            ?>
            var $panelView = objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('getPanel').children('div.datagrid-view');
            Core.initSelect2($panelView.find('.datagrid-view2 .datagrid-header-row:eq(0)'));
            <?php
            }
            ?>
        },
        onLoadSuccess: function(data) {
        <?php
        }
        ?>

            var _thisGrid = objectdatagrid_<?php echo $this->indicatorId; ?>;

            if (data.status === 'error' && data.message != '') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }
            
            <?php
            if ($this->isTreeGridData) {
            ?>
            showTreeGridMessage(_thisGrid, '');
            <?php
            } else {
            ?>
            showGridMessage(_thisGrid, '');
            <?php
            }
            ?>

            var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
            var $panelFilterRow = $panelView.find('.datagrid-filter-row');

            if (_thisGrid.datagrid('getRows').length == 0) {
                var $tr = $panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $tr.find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            } else {
                <?php
                if ($this->columns['mergeColumns']) {
                ?>
                var isMergeColumn = <?php echo json_encode($this->columns['mergeColumns']); ?>;        
                _thisGrid.datagrid('autoMergeCells', isMergeColumn);
                <?php
                }
                ?>
            }

            $('div.div-objectdatagrid-<?php echo $this->indicatorId; ?>').find("input.datagrid-filter[data-filter='1']").removeAttr('data-filter');
                                    
            if ($panelFilterRow.length) {
                Core.initNumberInput($panelFilterRow);
                Core.initDateInput($panelFilterRow);
                Core.initDateTimeInput($panelFilterRow);
                Core.initDateMaskInput($panelFilterRow);
                Core.initDateMinuteMaskInput($panelFilterRow);
                Core.initTimeInput($panelFilterRow);
                Core.initAccountCodeMask($panelFilterRow);
                Core.initStoreKeeperKeyCodeMask($panelFilterRow);
            }

            initDVClearColumnFilterBtn($panelView, $panelFilterRow);    

            /*dvReloadFooterData(_thisGrid, dvLoadSuccessData_1642386237438218);*/
            _thisGrid.datagrid('resize'); 
        }
    });
    
    objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('getPager').pagination({
        showPageList: true,
        layout: ['list','sep','first','prev','sep','manual','sep','next','last','sep','refresh','info'],
    });

    objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('enableFilter');
    
    $(window).bind('resize', function() {
        var $dvElem = $('body').find('#object-value-list-<?php echo $this->indicatorId; ?>');
        if ($dvElem.length > 0 && $dvElem.is(':visible') && $dvElem.find('.panel-eui').length) {
            objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('resize');
        }
    });
    
    $('.workflow-btn-<?php echo $this->indicatorId ?>').on('click', function (e, type) {
        wfmstatusRender_<?php echo $this->indicatorId ?>(e, type);
    });
    
});

}, 200);
        
function dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>() {
    var $panelView = objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('getPanel');    
    if ($panelView.find(".datagrid-pager").length) {
        if ($panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').length) {
            $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').remove();
        }
        var rows = window['objectdatagrid_<?php echo $this->indicatorId ?>'].datagrid('getSelections');
        $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').append('<td class="custom-selected-counter"><div class="pagination-btn-separator"></div></td><td class="custom-selected-counter pl6">'+plang.get('has_chosen')+': '+rows.length+'</td>');
    }
}

function filterKpiIndicatorValueForm(indicatorId) {
    var drillDownCriteria = window['drillDownCriteria_' + indicatorId];
    
    $.ajax({
        type: 'post',
        url: 'mdform/filterKpiIndicatorValueForm',
        data: {indicatorId: indicatorId, drillDownCriteria: drillDownCriteria, filterPosition: '<?php echo issetParam($this->row['SEARCH_TYPE']); ?>', filterColumnCount: '<?php echo issetParam($this->row['SEARCH_COLUMN_NUMBER']); ?>'},
        dataType: 'json',
        success: function(data) {
                        
            if (filterSearchType_<?php echo $this->indicatorId; ?> === 'top') {
                var $filterCol = $('#object-value-list-' + indicatorId + ' .kpidv-data-top-filter-col').last();
            } else {
                var $filterCol = $('#object-value-list-' + indicatorId + ' .kpidv-data-filter-col').last();
            }
            
            if (data.status == 'success' && data.html != '') {
                
                if ($filterCol.length) {
                    if (filterSearchType_<?php echo $this->indicatorId; ?> !== 'top') {
                        $filterCol.css('height', dynamicHeight + 100);
                    }
                                
                    $filterCol.closest('.mv-datalist-container').addClass('mv-datalist-show-filter');
                    $filterCol.closest('.ws-page-content').removeClass('mt-2');
                
                    $filterCol.append(data.html).promise().done(function() {
                        Core.initNumberInput($filterCol);
                        Core.initLongInput($filterCol);
                        Core.initDateInput($filterCol);
                        Core.initSelect2($filterCol);

                        if ($('#object-value-list-' + indicatorId).find('.mv-indicator-filter-tree-open-btn').length) {
                            $('#object-value-list-' + indicatorId).find('.mv-indicator-filter-tree-open-btn').trigger('click');
                        }                        
                    });
                }
                
            } else {
                $filterCol.closest('.col-md-auto').remove();
                objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('resize');
            }
        }
    });
}
function filterKpiIndicatorValueGrid(elem) {
    
    var getFilterData = getKpiIndicatorFilterData(elem);
    var indicatorId = getFilterData.indicatorId;
    var filterData = getFilterData.filterData;
    var forceFilterData = getFilterData.forceFilterData;
    
    window['isFilterShowData_' + indicatorId] = false; 
    
    var op = objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('options');
    var queryParams = op.queryParams;
    
    queryParams.filterData = filterData;
    queryParams.filter = JSON.stringify(forceFilterData);
    queryParams.ignoreFirstLoad = window['isFilterShowData_' + indicatorId];
    
    if (op.idField === null) {
        objectdatagrid_<?php echo $this->indicatorId; ?>.datagrid('load', queryParams);
    } else {
        objectdatagrid_<?php echo $this->indicatorId; ?>.treegrid('load', queryParams);
    }
    
    mvFilterRelationLoadData(elem, indicatorId, filterData);
}

function wfmstatusRender_<?php echo $this->indicatorId ?>(e, type, isIgnoreAlert) {
    var $workflowDropdown = $('.workflow-dropdown-<?php echo $this->indicatorId ?>');
    $workflowDropdown.empty();

    var rows = getDataViewSelectedRows('<?php echo $this->indicatorId ?>');

    if (rows.length === 0) {
        if (typeof isIgnoreAlert == 'undefined') {
            $workflowDropdown.dropdown('toggle');
            alert(plang.get('msg_pls_list_select'));
        }
        return;
    }

    var row = rows[0], wfmActions = [], isManyRows = '';

    if (rows.length > 1) {
        row = rows;
        isManyRows = '1';
    } 

    $.ajax({
        type: 'post',
        url: 'mdobject/getWorkflowNextStatus',
        data: {metaDataId: '<?php echo $this->indicatorId; ?>', dataRow: row, isManyRows: isManyRows, isIndicator: 1},
        dataType: 'json',
        async: false,
        success: function(response) {
            PNotify.removeAll();
            
            if (response.status === 'success') {

                if (response.datastatus && response.data) {
                    var rowId = '', realWfmName = '', advancedCriteria = '', wfmIcon = '';

                    if (typeof row.id !== 'undefined') {
                        rowId = row.id;
                    }

                    $.each(response.data, function (i, v) {

                        advancedCriteria = '';
                        wfmStatusIcon = '';

                        if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                            advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                        }

                        realWfmName = v.wfmstatusname;
                        if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                            v.wfmstatusname = v.processname;
                        }

                        if (v.wfmstatusicon) {
                            wfmIcon = '<i class="fa '+v.wfmstatusicon+'"></i> ';
                        }

                        if (isManyRows !== '') {

                            if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {
                                $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\');">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\')', name: v.wfmstatusname});
                            } else {
                                var isIgnoreMultiRowRunBp = ('isignoremultirowrunbp' in Object(v) && v.isignoremultirowrunbp == '1') ? 1 : 0;
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && ((v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null) || isIgnoreMultiRowRunBp)) {
                                    if (v.wfmisneedsign == '1') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '2') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '3') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '4') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '6') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\')', name: v.wfmstatusname});
                                    }
                                } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                    if (v.wfmisneedsign == '1') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId; ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '2') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '4') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '6') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+'</a></li>');
                                        wfmActions.push({icon: wfmIcon, action:'transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    }
                                }    
                            }
                            
                        } else {
                            
                            if (v.hasOwnProperty('indicatorid') && v.indicatorid != '' && v.indicatorid != null) {
                                var jsonStr = JSON.stringify(v).replace(/&quot;/g, '\\&quot;').replace(/"/g, '&quot;');
                                $workflowDropdown.append('<li><a href="javascript:;" onclick="mvChangeWfmStatus(this, \'<?php echo $this->indicatorId; ?>\');" data-statusconfig="'+jsonStr+'">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                            } else {
                                
                                if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {
                                    $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                    wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\')', name: v.wfmstatusname});
                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '3') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+'</a></li>');
                                            wfmActions.push({icon: wfmIcon, action:'transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        }
                                    }    
                                }
                            }
                        }
                    });    

                    $workflowDropdown.append('<div class="dropdown-divider"></div>');
                    
                } else if (response.hasOwnProperty('isShowMsgNotNextStatus') && response.isShowMsgNotNextStatus == '1') {
                    $workflowDropdown.dropdown('toggle');
                    new PNotify({
                        title: 'Info',
                        text: plang.get('wfm_permission_info'),
                        type: 'info',
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    Core.unblockUI();
                    return;
                } 

                if (!isIgnoreWfmHistory_<?php echo $this->indicatorId; ?>) {
                    wfmIcon = '';
                    if (typeof type !== 'undefined') {
                        wfmIcon = '<i class="icon-history"></i> ';
                    }
                    $workflowDropdown.append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->indicatorId ?>\');" data-isindicator="1">'+wfmIcon + plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
                    wfmActions.push({icon: wfmIcon, action:'seeWfmStatusForm(this, \'<?php echo $this->indicatorId ?>\')', name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')});                  
                }

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    type: response.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }

            Core.unblockUI();
        },
        error: function() { alert("Error"); }
    });
}

function dataListToBasket_<?php echo $this->indicatorId ?>(elem) {
    var rows = window['objectdatagrid_<?php echo $this->indicatorId ?>'].datagrid('getSelections');

    if (rows.length === 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }

    var isAdded = false, isGlConnected = false; 

    for (var key in rows) {
        var row = rows[key]

        /**
         * Журналд холбогдсон бол сагсанд нэмэхгүй гэсэн учраас ингэж шалгалаа.
         * @author Ulaankhuu Ts
         */
        if (row.hasOwnProperty('filterisconnectglstring') && row.filterisconnectglstring == '1' && !isGlConnected) {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Журналд холбогдсон баримт байна.<br> Сагсанд нэмэх боломжгүй!',
                type: 'warning',
                addclass: pnotifyPosition,
                sticker: false
            });

            isGlConnected = true;
        }            
    }

    if (isGlConnected) return;

    <?php
    if (isset($this->row['uniqueField'])) {
        $primaryField = $this->row['uniqueField'];
    } elseif ($this->idField) {
        $primaryField = $this->idField;
    } else {
        $primaryField = 'id';
    }                   
    ?>

    for (var key in rows) {
        var row = rows[key], rowId = row['<?php echo $primaryField; ?>'], isAddedChild = false;             

        for (var key in _selectedRows_<?php echo $this->indicatorId; ?>) {
            var basketRow = _selectedRows_<?php echo $this->indicatorId; ?>[key], childId = basketRow['<?php echo $primaryField; ?>'];

            if (rowId == childId) {
                isAddedChild = true;
                break;
            } 
        }    

        if (!isAddedChild) {
            isAdded = true; 
            row.basketqty = 1;
            _selectedRows_<?php echo $this->indicatorId; ?>.push(row);
        }
    }

    if (isAdded) {
        $('.save-database-<?php echo $this->indicatorId; ?>').text(_selectedRows_<?php echo $this->indicatorId; ?>.length).pulsate({
            color: '#F3565D', 
            reach: 9,
            speed: 500,
            glow: false, 
            repeat: 1
        });   
    } else {
        $('.save-database-<?php echo $this->indicatorId; ?>').pulsate({
            color: '#4caf50', 
            reach: 9,
            speed: 500,
            glow: false, 
            repeat: 1
        });   
    }

    return;
}

function dataListUseBasketView_<?php echo $this->indicatorId; ?>(elem) {

    PNotify.removeAll();
    var selectedRows = _selectedRows_<?php echo $this->indicatorId; ?>;

    if (selectedRows.length == 0) {
        new PNotify({
            title: 'Info',
            text: plang.get('msg_pls_list_select'),
            type: 'info',
            addclass: pnotifyPosition,
            sticker: false
        });
        return;
    }

    var indicatorDataId = '<?php echo $this->indicatorId; ?>';
    var $dialogName = 'dataViewBasket-dialog-' + indicatorDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdform/dataListUseBasketView',
        data: {indicatorDataId: indicatorDataId, selectedRows: selectedRows},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {  
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                position: { my: "top", at: "top+50" },
                width: '1200',
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function() { alert("Error"); Core.unblockUI(); }
    }); 

}

function deleteSelectableBasketWindow_<?php echo $this->indicatorId ?>(target) {

    setTimeout(function(){
        var basketRows = $('#dataListSelectableBasketDataGrid_<?php echo $this->indicatorId ?>').datagrid('getSelections');
        var selectedRow = basketRows[0], rowId = selectedRow.id; 

        for (var key in _selectedRows_<?php echo $this->indicatorId; ?>) {
            var row = _selectedRows_<?php echo $this->indicatorId; ?>[key], childId = row.id;

            if (rowId == childId) {

                var index = $('#dataListSelectableBasketDataGrid_<?php echo $this->indicatorId ?>').datagrid('getRowIndex', row);
                if (index < 0) {
                    $('#dataListSelectableBasketDataGrid_<?php echo $this->indicatorId ?>').datagrid('deleteRow', 0);
                    _selectedRows_<?php echo $this->indicatorId; ?>.splice(key, 1);
                } else {
                    $('#dataListSelectableBasketDataGrid_<?php echo $this->indicatorId ?>').datagrid('deleteRow', index);
                }

                _selectedRows_<?php echo $this->indicatorId; ?>.splice(key, 1);

                break;
            } 
        }

        $('#dataListSelectableBasketDataGrid_<?php echo $this->indicatorId ?>').datagrid('loadData', _selectedRows_<?php echo $this->indicatorId; ?>);

        $('.save-database-<?php echo $this->indicatorId; ?>').text(_selectedRows_<?php echo $this->indicatorId; ?>.length);

    }, 5);
}
    
</script>