<script type="text/javascript">
    var dv_search_<?php echo $this->metaDataId; ?> = $("div#dv-search-<?php echo $this->metaDataId; ?>");
    var dataGridTypeBtn_<?php echo $this->metaDataId; ?> = 'datagrid';
    var objectdatagrid_<?php echo $this->metaDataId; ?> = $('#objectdatagrid-<?php echo $this->metaDataId; ?>');
    var windowId_<?php echo $this->metaDataId; ?> = 'div#object-value-list-<?php echo $this->metaDataId; ?>';
    var filterFieldList_<?php echo $this->metaDataId; ?> = JSON.parse('<?php echo json_encode($this->filterFieldList); ?>');
    var modeType_<?php echo $this->metaDataId; ?> = '<?php echo isset($this->modeType) ? $this->modeType : 'true' ?>';
    var dv_theme_<?php echo $this->metaDataId; ?> = '<?php echo $this->dataGridOptionData['VIEWTHEME']; ?>';
    var _selectedRows_<?php echo $this->metaDataId; ?> = [];
    var dvFirstLoad_<?php echo $this->metaDataId; ?> = false;
    var dvIgnoreFirstLoad_<?php echo $this->metaDataId; ?> = <?php echo (isset($this->dataGridOptionData['isIgnoreFirstLoad']) ? json_encode($this->dataGridOptionData['isIgnoreFirstLoad']) : 'false'); ?>;
    var isIgnoreWfmHistory_<?php echo $this->metaDataId; ?> = <?php echo (issetParam($this->row['IS_IGNORE_WFM_HISTORY']) == '1' ? 'true' : 'false'); ?>;
    var on_click_<?php echo $this->metaDataId; ?> = false;
    var timer_<?php echo $this->metaDataId; ?>;
    var datagridGroupHide_<?php echo $this->metaDataId; ?> = false;
    var dvRequest_<?php echo $this->metaDataId; ?> = null;
    var isTouch = (typeof isTouchEnabled === 'undefined') ? false : isTouchEnabled;
    var dvLoadSuccessData_<?php echo $this->metaDataId; ?> = null;
    var dvFilterValues_<?php echo $this->metaDataId; ?> = {};
    var dvFilterParamValues_<?php echo $this->metaDataId; ?> = [];
    <?php echo $this->layoutTypes; ?>        
        
    $(function() {

        $(windowId_<?php echo $this->metaDataId; ?>).on('keyup paste cut', "form .bigdecimalInit", function(e){
            var code = e.keyCode || e.which;
            if (code == 9 || code == 27 || code == 37 || code == 38 || code == 39 || code == 40) return false;
            var $this = $(this);
            var $thisVal = $this.val() != '' ? pureNumber($this.val()) : '';
            $this.next('input[type=hidden]').val($thisVal);
        });                       
        
        dv_search_<?php echo $this->metaDataId; ?>.on('keydown', "input[type='text'][class]:visible, input[type='checkbox']:not([data-all='1'], [data-isdisabled])", function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            var $this = $(this);

            if (keyCode === 13) { /* enter */
                
                <?php
                if (issetParam($this->row['IS_ENTER_FILTER']) != '1') {
                ?>
                var $form = $this.closest('form');
                var $formInput = $form.find('input:visible:not([data-all="1"], [data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser)');
                var $cellIndex = $formInput.index($this);

                if ($formInput.length == ($cellIndex + 1)) {
                    $formInput.eq(0).focus().select();
                } else {
                    if ($formInput.eq($cellIndex + 1).length) {
                        $formInput.eq($cellIndex + 1).focus().select();
                    }
                }
                <?php
                } else {
                ?>
                $this.select();
                <?php
                }
                ?>

                e.preventDefault();
            }
        });    
        
        dv_search_<?php echo $this->metaDataId; ?>.find('input[type=text][readonly]').removeAttr('readonly');        
        
        <?php echo $this->dvScripts['scripts']; ?>        
        
        dvFilterDateCheckInterval(dv_search_<?php echo $this->metaDataId; ?>);

        var dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->metaDataId; ?>.offset().top - 20 - 25;

        <?php
        if (($this->dataViewCriteriaType !== 'left web' && $this->dataViewCriteriaType !== 'left web civil') || issetParam($this->dataGridOptionData['PAGESTYLE']) == 'lazy_load') {
            
            if (isset($this->dataGridDefaultHeight) && $this->dataGridDefaultHeight == 'auto') {
                $setGridHeight = true;
        ?>
            if (dynamicHeight < 230) {
                dynamicHeight = 350;
            }

            if (!modeType_<?php echo $this->metaDataId; ?>) {
                dynamicHeight = dynamicHeight - 130;
            }

            objectdatagrid_<?php echo $this->metaDataId; ?>.css('max-height', dynamicHeight);
            <?php
            } else {
            ?>

            if ('<?php echo $this->isDynamicHeight ?>' != '0') {

                if (dynamicHeight < 230) {
                    dynamicHeight = 350;
                }

                if (!modeType_<?php echo $this->metaDataId; ?>) {
                    dynamicHeight = dynamicHeight - 130;
                }

                if (objectdatagrid_<?php echo $this->metaDataId; ?>.closest('.package-tab').length) {
                    dynamicHeight = 'auto';
                }

                <?php 
                if ((isset($this->dataGridDefaultHeight) && $this->dataGridDefaultHeight == 'relative') == false) { 
                    $setGridHeight = true;
                ?>
                    objectdatagrid_<?php echo $this->metaDataId; ?>.attr('height', <?php echo isset($this->dataGridDefaultHeight) ? ($this->dataGridDefaultHeight != '' ? "'".$this->dataGridDefaultHeight."'" : 'dynamicHeight') : 'dynamicHeight'; ?>);        
                <?php    
                } 
                ?>
            }
        <?php
            }
        }
        
        $autoHeight = issetParam($this->dataGridOptionData['AUTOHEIGHT']);        
        ?>

        if ($(".ecommerce-criteria-wrap-<?php echo $this->metaDataId; ?>").length) {
            $(".ecommerce-criteria-wrap-<?php echo $this->metaDataId; ?>").css('height', dynamicHeight-46+'px');
        }        

        <?php
        if ($autoHeight == 'false') {
        ?>
                
        objectdatagrid_<?php echo $this->metaDataId; ?>.attr('height', dynamicHeight);         
        $(".ecommerce-criteria-wrap-<?php echo $this->metaDataId; ?>").css('height', dynamicHeight-46+'px');
        <?php        
        } elseif ($autoHeight == 'true') {
        ?>      
        objectdatagrid_<?php echo $this->metaDataId; ?>.removeAttr('height');        
        $(".ecommerce-criteria-wrap-<?php echo $this->metaDataId; ?>").css('height', '100%');
        <?php
        }
        ?>       
            
        if ($(".height-dynamic", windowId_<?php echo $this->metaDataId; ?>).length) {
            $(".height-dynamic", windowId_<?php echo $this->metaDataId; ?>).css({
                'max-height': $(window).height() - $(".height-dynamic", windowId_<?php echo $this->metaDataId; ?>).offset().top - 50 - 34,
                'overflow-y': 'auto'
            });
        }
        
        <?php
        if (isset($this->metaLayoutBtn) && $this->metaLayoutBtn) {
        ?>
        $('.callLayoutDataView_<?php echo $this->metaDataId; ?>').show();
        $('.callDataView_<?php echo $this->metaDataId; ?>').hide();
        <?php
        }
        if (issetParam($this->useBasket) == false) { 
        ?>
        Core.initInputType($(windowId_<?php echo $this->metaDataId; ?>));
        <?php 
        } 
        if (issetParam($this->dataGridOptionData['MERGECELLS']) == 'true') {
        ?>
        $(".value-grid-merge-cell", "#object-value-list-<?php echo $this->metaDataId; ?>").removeClass('disabled');
        <?php
        }
        ?>
        
        <?php
        if ($this->isTreeGridData) {
            
            $layoutType = '';
            
        } elseif ($this->subgrid) {
            
            $layoutType = 'view: detailview,'."\n";
            
        } elseif ($this->dataGridOptionData['GROUPFIELD']) {
            
            $layoutType = 'view: groupview,'."\n";
            $layoutType .= 'showFilterBar: true,'."\n";
            $layoutType .= "groupField: '".strtolower($this->dataGridOptionData['GROUPFIELD'])."',"."\n";
            if ($this->dataGridOptionData['GROUPSUM'] == 'true') {
                $layoutType .= 'vrGroupSum: true,'."\n";
            }
            if (issetParam($this->dataGridOptionData['GROUPTITLEFREEZE']) == 'true') {
                $layoutType .= 'vrGroupFreeze: true,'."\n";
            }
            if ($this->dataGridOptionData['GROUPFIELDEXPAND'] == 'false') {
                $layoutType .= 'groupExpand: false,'."\n";
            }
            $layoutType .= 'groupFormatter: function(value, rows) { return '.Format::dataGridGroupFormatter($this->dataGridOptionData['GROUPFORMATTER']).'; },'."\n";
            
            if (isset($this->dataGridOptionData['GROUPFIELDSTYLER'])) {
                $layoutType .= 'groupStyler: function(value,rows){ return \''.$this->dataGridOptionData['GROUPFIELDSTYLER'].'\'; },';
            }
            
        } else {
            $layoutType = 'view: horizonscrollview,'."\n";
        }
        
        if (issetParam($this->dataGridOptionData['PAGESTYLE']) == 'lazy_load') {
            $layoutType = 'view: scrollview,'."\n";
            $this->dataGridOptionData['PAGESTYLE'] = '';
        }
        
        $layoutTypeMainGrid = $layoutType;
        $options = $frozenColumns = '';
        
        if (($this->defaultViewer == 'datalist' || $this->defaultViewer == 'card' || $this->defaultViewer == 'card1' || $this->defaultViewer == 'card_business' || $this->defaultViewer == 'card_collaterial' || $this->defaultViewer == 'card_collaterial_w' || $this->defaultViewer == 'card_detail' || $this->defaultViewer == 'ecommerce' || $this->defaultViewer == 'ecommerce_nofilter' || $this->defaultViewer == 'ecommerce_basket') && $this->layoutType != '') {
            
            echo '$(".div-objectdatagrid-'.$this->metaDataId.'").removeClass(dv_theme_'.$this->metaDataId.');
            $(".div-objectdatagrid-'.$this->metaDataId.'").addClass(\'jeasyuiTheme'.$this->layoutType.'View\');';
            
            echo 'var dvLayoutBtn = $(".dv-layout-type-'.$this->metaDataId.'"); ';
            
            echo 'dvLayoutBtn.html(\'<i class="icon-list"></i>\');
            dvLayoutBtn.attr(\'data-old-type\', \''.$this->layoutType.'\');    
            dvLayoutBtn.attr(\'data-view-type\', \'list\');'."\n"; 
            
            $layoutType = 'view: '.$this->layoutType.'view_'.$this->metaDataId.','."\n";
            
            $options .= 'showHeader: false, 
                        showFooter: true, 
                        rownumbers: false, 
                        nowrap: false, 
                        autoRowHeight: false,'."\n";
            foreach ($this->dataGridDefaultOption as $k => $row) {
                if ($k == 'pagination') {
                    $options .= "pagination: " . $this->dataGridOptionData['PAGINATION'] . ",";
                } elseif ($k == 'singleSelect') {
                    $options .= "singleSelect: " . $this->dataGridOptionData['SINGLESELECT'] . ",";
                } elseif ($k == 'ctrlSelect') {
                    $options .= "ctrlSelect: " . $this->dataGridOptionData['CTRLSELECT'] . ",";
                } elseif ($k == 'checkOnSelect') {
                    $options .= "checkOnSelect: true,";
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
                } elseif ($k == 'remoteSort') {
                    $options .= "remoteSort: " . $this->dataGridOptionData['REMOTESORT'] . ",";
                } 
            } 
            $frozenColumns = 'frozenColumns: [],'."\n";
            
            if ($this->defaultViewer == 'card' || $this->defaultViewer == 'card1' || $this->defaultViewer == 'datalist' || $this->defaultViewer == 'card_business' || $this->defaultViewer == 'ecommerce') {
                $this->dataGridOptionData['ENABLEFILTER'] = 'false';
            }
            
        } else {
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
                    
                    if ($this->dataGridOptionData['PAGINATION'] == 'false' && $this->dataGridOptionData['SHOWFOOTER'] == 'false') {
                        $options .= "pagination: false,";
                    } else {
                        $options .= "pagination: true,";
                    }
                    
                } elseif ($k == 'rownumbers') {
                    $options .= "rownumbers: " . $this->dataGridOptionData['ROWNUMBERS'] . ",";
                } elseif ($k == 'singleSelect') {
                    $options .= "singleSelect: " . $this->dataGridOptionData['SINGLESELECT'] . ",";
                } elseif ($k == 'ctrlSelect') {
                    $options .= "ctrlSelect: " . $this->dataGridOptionData['CTRLSELECT'] . ",";
                } elseif ($k == 'checkOnSelect') {
                    $options .= "checkOnSelect: true,";
                } elseif ($k == 'selectOnCheck') {
                    $options .= "selectOnCheck: true,";
                } elseif ($k == 'pagePosition') {
                    $options .= "pagePosition: '" . $this->dataGridOptionData['PAGEPOSITION'] . "',";
                } elseif ($k == 'pageNumber') {
                    $options .= "pageNumber: " . $this->dataGridOptionData['PAGENUMBER'] . ",";
                } elseif ($k == 'pageSize') {
                    
                    if ($this->dataGridOptionData['PAGINATION'] == 'false') {
                        $options .= "pageSize: 3000,";
                    } else {
                        $options .= "pageSize: " . $this->dataGridOptionData['PAGESIZE'] . ",";
                    }
                    
                } elseif ($k == 'pageList') {
                    
                    if ($this->dataGridOptionData['PAGINATION'] == 'false') {
                        $options .= "pageList: [3000],";
                        $options .= "remoteFilter: true,";
                    } else {
                        $options .= "pageList: " . $this->dataGridOptionData['PAGELIST'] . ",";
                        $options .= "remoteFilter: true,";
                    }
                    
                } elseif ($k == 'sortName') {
                    if (!empty($this->dataGridOptionData['SORTNAME'])) {
                        $options .= "sortName: '" . strtolower($this->dataGridOptionData['SORTNAME']) . "',";
                        $options .= "sortOrder: '" . $this->dataGridOptionData['SORTORDER'] . "',";
                    }
                } elseif ($k == 'multiSort') {
                    $options .= "multiSort: " . $this->dataGridOptionData['MULTISORT'] . ",";
                } elseif ($k == 'remoteSort') {
                    $options .= "remoteSort: " . $this->dataGridOptionData['REMOTESORT'] . ",";
                } elseif ($k == 'showHeader') {
                    $options .= "showHeader: " . $this->dataGridOptionData['SHOWHEADER'] . ",";
                } elseif ($k == 'showFooter') {
                    $options .= "showFooter: " . $this->dataGridOptionData['SHOWFOOTER'] . ",";
                } elseif ($k == 'scrollbarSize') {
                    $options .= "scrollbarSize: " . $this->dataGridOptionData['SCROLLBARSIZE'] . ",";
                } elseif ($k == 'loadMsg') {
                    $options .= "loadMsg: '" . $this->lang->line($this->dataGridOptionData['LOADMSG'] ? $this->dataGridOptionData['LOADMSG'] : 'pl_0217') . "',";
                } elseif ($k == 'mergeCells' && $this->dataGridOptionData['MERGECELLS'] == 'true') {
                    $isMergeCells = true;
                    if (issetParam($this->dataGridOptionData['MERGECELLSKEYFIELD'])) {
                        $mergeCellsKeyField = $this->dataGridOptionData['MERGECELLSKEYFIELD'];
                    }
                } elseif ($k == 'showFileicon') {
                    if ($this->dataGridOptionData['SHOWFILEICON'] == 'false') {
                        $options .= 'fileIconclass: "hidden",';
                    }
                } elseif ($k == 'firstRowSelect' && issetParam($this->dataGridOptionData['FIRSTROWSELECT']) == 'true') {
                    $isFirstRowSelect = true;
                }
            } 
            
            $frozenColumns = 'frozenColumns: '.((isset($this->dataGridColumnData['freeze'])) ? $this->dataGridColumnData['freeze'] : '[]').','."\n";
        }
        if (isset($this->row['IS_COUNTCARD_OPEN']) && $this->row['IS_COUNTCARD_OPEN'] == '1' && $this->layoutType !== 'ecommerce') {
            echo 'dataViewFilterCardViewForm_'.$this->metaDataId.'();';
        }
        ?>
        
        <?php
        if (issetParam($this->callerType) == 'package') {
        ?>
        var $packageTab = objectdatagrid_<?php echo $this->metaDataId; ?>.closest('div.package-meta-tab');
        if ($packageTab.length && $packageTab.attr('data-realpack-id')) {
            var packageId = $packageTab.attr('data-realpack-id');
            var $packageMeta = $('#package-meta-' + packageId);
            var defaultCriteriaData = $packageMeta.find("form.package-criteria-form-" + packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
        } else {
            var defaultCriteriaData = $(windowId_<?php echo $this->metaDataId; ?>).find("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
        }
        <?php
        } else {
        ?>
        var defaultCriteriaData = $(windowId_<?php echo $this->metaDataId; ?>).find("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
        <?php
        }
        ?>
                
        if (window.location.hash !== '' && false) {
            var parsedHash = queryString.parse(window.location.hash);
            
            if (typeof parsedHash.dvFilterValues !== 'undefined' && parsedHash.dvFilterValues !== '') {
                var urlDvFilters = JSON.parse(parsedHash.dvFilterValues);
                dvFilterValues_<?php echo $this->metaDataId; ?> = urlDvFilters;
                for (var k in urlDvFilters) {
                    dvFilterParamValues_<?php echo $this->metaDataId; ?>.push({field: k, value: urlDvFilters[k]});
                }
            }                 
        }                       
        
        objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>({
                <?php echo $layoutType; ?>
                url: 'mdobject/dataViewDataGrid',
                queryParams: {
                    metaDataId: '<?php echo $this->metaDataId; ?>', 
                    defaultCriteriaData: defaultCriteriaData, 
                    workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                    workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                    uriParams: '<?php echo $this->uriParams; ?>', 
                    treeConfigs: '<?php echo $this->isTreeGridData; ?>',
                    dvDefaultCriteria: '<?php echo isset($this->dvDefaultCriteria) ? json_encode($this->dvDefaultCriteria) : ''; ?>', 
                    drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
                    ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                    kpiIndicatorMapConfig: '<?php echo isset($this->kpiIndicatorMapConfig) ? $this->kpiIndicatorMapConfig : ''; ?>', 
                    subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
                    ignoreFirstLoad: dvIgnoreFirstLoad_<?php echo $this->metaDataId; ?> 
                }, 
                <?php
                if ($this->isTreeGridData) {
                    parse_str($this->isTreeGridData, $isTreeGridData);
                    echo "idField: '".$isTreeGridData['id']."',"."\n"; 
                    echo "treeField: '".$isTreeGridData['name']."',"."\n";
                }
                echo $options; 
                ?>
                filterDelay: 10000000000,
                clickToEdit: false, 
                <?php
                if ($this->isRowColor || $this->isTextColor) {
                    if ($this->isTreeGridData) {
                        echo 'rowStyler: function(row){'."\n";
                    } else {
                        echo 'rowStyler: function(index, row){'."\n";
                    } 
                ?>
                    var rowStyleStr = '';
                    
                    if (typeof row !== 'undefined') {
                        if (typeof row.rowcolor !== 'undefined' && row.rowcolor != '') {
                            rowStyleStr += 'background-color:'+row.rowcolor+';';
                        }
                        if (typeof row.textcolor !== 'undefined' && row.textcolor != '') {
                            rowStyleStr += 'color:'+row.textcolor+';';                        
                        }
                        if (typeof row.fontweight !== 'undefined' && row.fontweight != '') {
                            rowStyleStr += 'font-weight: ' + row.fontweight + ';';       
                        }
                        if (typeof row.textdecoration !== 'undefined' && row.textdecoration != '') {
                            rowStyleStr += 'text-decoration: ' + row.textdecoration + ';';       
                        }
                    }
                    
                    return rowStyleStr;
                },             
                <?php            
                }
                echo $frozenColumns;
                ?>
                columns: <?php echo ((isset($this->dataGridColumnData['header'])) ? $this->dataGridColumnData['header'] : '[]'); ?>,
                <?php 
                if (isset($this->calendarParams) && !is_null($this->calendarParams)) { 
                    $explodedCalParam = explode('_', $this->calendarParams); 
                ?>
                    filterRules:[
                        {field: '<?php echo $explodedCalParam[0]; ?>', value: '<?php echo substr(date('Y-m-d h:m:s', $explodedCalParam[1]), 0, 10); ?>'}
                    ],
                <?php 
                } else { ?>
                    filterRules: dvFilterParamValues_<?php echo $this->metaDataId; ?>,
                <?php } 
                ?>                        
                onSelectAll: function() {
                    dvSelectionCountToFooter_<?php echo $this->metaDataId; ?>();
                }, 
                onUnselectAll: function() {
                    dvSelectionCountToFooter_<?php echo $this->metaDataId; ?>();
                }, 
                onUnselect: function() {
                    dvSelectionCountToFooter_<?php echo $this->metaDataId; ?>();
                },
                <?php
                if (issetParam($this->dataGridOptionData['TOGGLESELECT']) == 'true') {
                ?>     
                onBeforeSelect: function(index, row) {
                    var getSelected = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getSelected');
                    var selectedIndex = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getRowIndex', getSelected);

                    if (selectedIndex !== index) {
                        var rows = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getRows');
                        var rowsLength = rows.length;
                        var rowR = [];

                        for (var i = 0; i < rowsLength; i++) {

                            rowR = rows[i];
                            delete rowR.isPfSelected;

                            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('updateRow', {
                                index: i,
                                row: rowR
                            });
                        }
                    }

                    if (row && !row.hasOwnProperty('isPfSelected')) {
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('updateRow', {
                            index: index,
                            row: {isPfSelected: 1}
                        });
                    } 
                },
                onSelect: function(index, row) {
                    
                    var $panelView = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').children('div.datagrid-view');
                    
                    if (row && row.hasOwnProperty('isPfSelected') && row.isPfSelected == 1) {
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('updateRow', {
                            index: index,
                            row: {isPfSelected: 0}
                        });
                    } else {
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('unselectRow', index);
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('uncheckRow', index);

                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('updateRow', {
                            index: index,
                            row: {isPfSelected: 1}
                        });

                        setTimeout(function(){
                            var $checkbox = $panelView.find('> .datagrid-view1 > .datagrid-body').find('tr[datagrid-row-index="'+index+'"] > td[field="ck"]');
                            if ($checkbox.length) {
                                $checkbox.find('input[type="checkbox"]').prop('checked', false);
                            }
                        }, 20);
                    }
                    
                    dvSelectionCountToFooter_<?php echo $this->metaDataId; ?>();
                },
                <?php 
                } else {
                ?>
                onSelect: function(index, row) {
                    $('#currentSelectedRowIndex', '#object-value-list-<?php echo $this->metaDataId; ?>').val(index);
                    dvSelectionCountToFooter_<?php echo $this->metaDataId; ?>();
                },   
                <?php
                }
                if (count($this->dataViewProcessCommand['commandContext']) > 0) { 
                    
                    if ($this->isTreeGridData) {
                ?>
                    onContextMenu: function (e, row) {
                        e.preventDefault();
                        $(this).treegrid('unselectAll');
                        $(this).treegrid('select', row.id);   
                    <?php
                    } else {
                    ?>    
                    onRowContextMenu: function (e, index, row) {
                        e.preventDefault();
                        $(this).datagrid('unselectAll');
                        $(this).datagrid('selectRow', index);
                        $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val(index);
                        
                    <?php
                    }
                        if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) { 
                    ?>
                        if ('<?php echo $this->layoutType ?>' === 'ecommerce') {
                            wfmstatusRender_<?php echo $this->metaDataId ?>();
                        } else {
                            
                            if ($('.workflow-btn-<?php echo $this->metaDataId ?>', "#object-value-list-<?php echo $this->metaDataId; ?>").length) {
                                $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
                                if (!$('.workflow-btn-<?php echo $this->metaDataId ?>').is(':visible')) {
                                    setTimeout(function() {
                                        $('.workflow-btn-<?php echo $this->metaDataId ?>').trigger('click', [true]);
                                    }, 300);
                                }
                            }                        
                            
                            renderContextMenuDv_<?php echo $this->metaDataId; ?>(undefined, row);
                        }
                    <?php
                        } else {
                    ?>
                        renderContextMenuDv_<?php echo $this->metaDataId; ?>(undefined, row);
                    <?php
                        }
                    ?>    
                    },
                <?php 
                } 
                
                if ($this->isTreeGridData) {
                    echo 'onClickRow: function(row) { ';
                } else {
                    echo 'onClickRow: function(index, row) { ';
                    echo '$("#currentSelectedRowIndex", "#object-value-list-'.$this->metaDataId.'").val(index); ';
                    if (Config::getFromCache('isCivilUseFullSearch') == '1') { ?>
                        if (typeof row.additionalsearch !== 'undefined' && row.additionalsearch) {
                            addinProcessCriteria_<?php echo $this->metaDataId; ?>(index, row);
                        }
                    <?php }
                } ?>
                    var $checkopenbp = $("#objectDataView_<?php echo $this->metaDataId; ?>").find('.is-open-bp-<?php echo $this->metaDataId; ?>');
                    if ($checkopenbp.length && $checkopenbp.data('dvbtn-position') == 'right') {
                        var $bpOpenSelector = $checkopenbp;
                        $bpOpenSelector.eq(0).trigger('click');
                    }                 
                <?php
                    if ($this->dataViewProcessCommand['isShowRowSelect']) {
                ?>
                    
                    if (typeof dvProcessButtonsShow === 'function') {
                        dvProcessButtonsShow('<?php echo $this->metaDataId ?>', row);
                    }
                    
                    <?php 
                    }
                    if (isset($this->checklist)) { echo $this->checklist; } 
                    ?>
                    
                    <?php
                    if (issetParam($this->dataGridOptionData['SHOWCHECKBOX']) != 'true') {
                    ?>
                    dvRowSelector_<?php echo $this->metaDataId ?>(); 
                    <?php
                    }
                    ?>

                    if (typeof row.postreeid !== 'undefined' && typeof row.poshistoryid !== 'undefined') {          
                        var cpRow = row, dvSearchParamAddon = '';

                        for (var key in cpRow) {
                            if (cpRow[key] !== null && cpRow[key] !== '')
                                dvSearchParamAddon += 'param['+key+']='+cpRow[key]+'&criteriaCondition['+key+']==&';
                        }
                        if (typeof window['dvSearchParamData_'+row.postreeid] === 'function') {
                            window['dvSearchParamData_'+row.postreeid](dvSearchParamAddon);
                            window['dvSearchParamData_'+row.poshistoryid](dvSearchParamAddon);
                        }
                    }
                },
                <?php
                if ($this->isTreeGridData && isset($isMergeCells)) {
                ?>
                onBeforeExpand: function(row) {
                    var rowId = row.<?php echo $isTreeGridData['id']; ?>;
                    var $panelView = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                    var $currRow = $panelView.find('tr[node-id="'+rowId+'"]');
                    var $nextRow = $currRow.nextAll('tr:not(.treegrid-tr-tree):first');
                    var $nextCells = $nextRow.find('td.datagrid-td-merged-hidden:hidden');
                    var $currCells = $currRow.find('td.datagrid-td-merged-hidden:hidden');
                    var $nextAllRows = $nextRow.nextAll('tr');
                    var $prevAllRows = $currRow.prevAll('tr');
                    
                    if ($currCells.length) {
                        
                        $currCells.each(function() {
                            var $this = $(this), fieldName = $this.attr('field');
                            var $prevRows = $this.closest('tr');
                            var fieldAttr = getRangeRowSpan($prevRows, fieldName, 1);
                            var r = 1;
                            
                            $prevAllRows.each(function() {
                                var $nextThisRow = $(this), 
                                    $nextThisCell = $nextThisRow.find('> td[field="'+fieldName+'"][rowspan]');

                                if ($nextThisCell.length) {
                                    return false;
                                }
                                
                                r++;
                            });

                            $panelView.find('tr[node-id="'+fieldAttr.rowId+'"] td[field="'+fieldName+'"]').attr({'rowspan': r + 1, 'dddd': '555'});

                            if ($nextRow.find('td[field="'+fieldName+'"].datagrid-td-merged-hidden').length) {
                                var c = 1;
                                $nextAllRows.each(function() {
                                    var $nextThisRow = $(this), 
                                        $nextThisCell = $nextThisRow.find('> td[field="'+fieldName+'"]');
                                    c++;
                                    
                                    if ($nextThisCell.length && $nextThisCell.css('display') != 'none') {
                                        return false;
                                    }
                                });
                                $nextRow.find('td[field="'+fieldName+'"].datagrid-td-merged-hidden').attr('rowspan', c - 1).show();
                            }
                        });
                    }
                    
                    if ($nextCells.length) {
                        
                        var $rowSpan = $currRow.find('> td[rowspan]');
                        var $nextTree = $currRow.next('tr.treegrid-tr-tree');
                        
                        $nextRow.show().attr('data-visible', 1);
                        $nextTree.removeClass('d-none');
                        
                        if ($rowSpan.length) {
                            $rowSpan.each(function() {
                                var $this = $(this), rowSpan = Number($this.attr('rowspan'));
                                if (rowSpan > 1) {
                                    var fieldName = $this.attr('field'), 
                                        $nextRowSpanCell = $nextRow.find('> td[field="'+fieldName+'"]');
                                    $this.attr('prev-rowspan', rowSpan);
                                    $this.attr('rowspan', 1);
                                    
                                    var c = 1;
                                    $nextAllRows.each(function() {
                                        var $nextThisRow = $(this), 
                                            $nextThisCell = $nextThisRow.find('> td[field="'+fieldName+'"]');

                                        if ($nextThisCell.length && $nextThisCell.css('display') != 'none') {
                                            return false;
                                        }
                                        
                                        c++;
                                    });

                                    $nextRowSpanCell.attr('rowspan', c);

                                    if ($nextRowSpanCell.hasClass('datagrid-td-merged-hidden')) {
                                        $nextRowSpanCell.show();
                                    } 
                                }
                                var $nextTreeField = $nextTree.find('td[field="'+fieldName+'"]');
                                if ($nextTreeField.hasClass('datagrid-td-merged-hidden')) {
                                    $nextTreeField.show();
                                }
                            });
                        } else {
                            if ($currCells.length) {
                                $currCells.show();
                                $nextTree.find('td.datagrid-td-merged-hidden').show();
                            }
                        }
                    }
                    
                    var $nextTreeRow = $currRow.next('tr.treegrid-tr-tree');
                    var $nodeTr = $nextTreeRow.find('tr[node-id]');
                    var $nodeCell = $nextTreeRow.find('td.datagrid-td-merged-hidden');
                    
                    if ($nodeTr.length == $nodeCell.length) {
                        $nodeCell.show();
                    }
                    
                    function getRangeRowSpan($row, field, index) {
                        var $rows = $row.prevAll('tr:not(.treegrid-tr-tree):first');
                        var $cell = $rows.find('td[field="'+field+'"][rowspan]');

                        if ($cell.length) {
                            return {index: index, rowId: $rows.attr('node-id'), rowspan: $cell.attr('rowspan')};
                        } else {
                            return getRangeRowSpan($rows, field, index + 1);
                        }
                    }
                },
                onCollapse: function(row) {
                    var rowId = row.<?php echo $isTreeGridData['id']; ?>;
                    var $panelView = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                    var $currRow = $panelView.find('tr[node-id="'+rowId+'"]');
                    var $nextRow = $currRow.nextAll('tr:not(.treegrid-tr-tree):first').find('td[data-visible="1"]');
                    
                    if ($nextRow.length) {
                        $nextRow.hide().removeAttr('data-visible');
                        $currRow.next('tr.treegrid-tr-tree').addClass('d-none');
                    }
                },
                <?php 
                }
                
                if ($this->layoutType === 'ecommerce' && issetParam($this->dataGridOptionData['SHOWCHECKBOX']) == 'true') { 
                ?>        
                onCheck: function(index, row) {
                    dvRowSelector_<?php echo $this->metaDataId ?>(undefined, undefined, true);
                }, 
                onUncheck: function(index, row) {
                    dvRowSelector_<?php echo $this->metaDataId ?>(undefined, undefined, true);
                }, 
                <?php 
                }          
                if ($this->metaDataId == '1528440649852') { 
                ?>
                onClickCell: function (index, field, value) {
                    
                    var mainBodyDataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').find('tr[datagrid-row-index="'+index+'"] > td[field="'+field+'"]'), 
                        dgRowspan = 0, dgChecked = false;

                    if (mainBodyDataGrid.length) {
                        dgRowspan = mainBodyDataGrid.attr('rowspan');
                        dgChecked = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').find('tr[datagrid-row-index="'+index+'"]').hasClass('datagrid-row-selected');
                    } else {
                        dgRowspan = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find('div.datagrid-view > .datagrid-view1 > .datagrid-body').find('tr[datagrid-row-index="'+index+'"] > td[field="'+field+'"]').attr('rowspan');
                        dgChecked = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find('div.datagrid-view > .datagrid-view1 > .datagrid-body').find('tr[datagrid-row-index="'+index+'"]').hasClass('datagrid-row-selected');
                    }

                    dgRowspan = Number(dgRowspan);
                    if (dgRowspan > 1) {
                        var dgRowspanFinal = index + dgRowspan;
                        index++;
                        if (!dgChecked) {
                            for (var ri = index; ri < dgRowspanFinal; ri++) {
                                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('selectRow', ri);
                            }
                        } else {
                            for (var ri = index; ri < dgRowspanFinal; ri++) {
                                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('unselectRow', ri);
                            }   
                        }
                    }
                }, 
                <?php 
                }
                if (issetParam($this->dataGridOptionData['GROUPFIELD'])) { 
                ?>
                    onBeforeSortColumn: function (sort, order) {
                        var sortTitle = $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").find('tr.datagrid-header-row:not(.datagrid-filter-row) > td[field="'+ sort +'"] > div.datagrid-cell > span:eq(0)').text();
                        var addSortHtml = '<a class="btn btn-default btn-sm dvColumnSort-<?php echo $this->metaDataId; ?>" title="'+ sortTitle +'" onclick="dvClearColumnSort(this, \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->dataGridOptionData['GROUPFIELD'] ?>\');" href="javascript:;" style="background: none; border: 1px solid #CCC; margin: 0;"><i class="fa fa-times"></i> '+ sortTitle +'</a>'
                        $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").closest('div.p-2').find('a.dvColumnSort-<?php echo $this->metaDataId; ?>').remove();
                        $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").closest('div.p-2').prepend(addSortHtml);
                    },
                <?php 
                }
                if (issetParam($this->dataGridOptionData['ONRESIZECOLUMN']) == 'true') {
                ?> 
                    onResizeColumn: function (field, width) {
                        $.ajax({
                            type: 'post',
                            url: 'mdobject/saveMetaGroupConfigUser',
                            data: {
                                metaDataId: '<?php echo $this->metaDataId; ?>',
                                field: field,
                                width: width
                            },
                            dataType: "json",
                            beforeSend: function() {
                                Core.blockUI({
                                    animate: true
                                });
                            },
                            success: function(data) {
                                Core.unblockUI();
                            },
                            error: function (jqXHR, exception) {
                                var msg = '';
                                if (jqXHR.status === 0) {
                                    msg = 'Not connect.\n Verify Network.';
                                } else if (jqXHR.status == 404) {
                                    msg = 'Requested page not found. [404]';
                                } else if (jqXHR.status == 500) {
                                    msg = 'Internal Server Error [500].';
                                } else if (exception === 'parsererror') {
                                    msg = 'Requested JSON parse failed.';
                                } else if (exception === 'timeout') {
                                    msg = 'Time out error.';
                                } else if (exception === 'abort') {
                                    msg = 'Ajax request aborted.';
                                } else {
                                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                                }

                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Error',
                                    text: msg,
                                    type: 'error',
                                    sticker: false
                                });
                                Core.unblockUI();
                            }
                        });
                   },
                <?php  
                } ?>
                onDblClickRow: function(index, row) {
                    if (typeof dataviewHandlerDblClickRow<?php echo $this->metaDataId ?> === 'function') {
                        dataviewHandlerDblClickRow<?php echo $this->metaDataId ?>(row);
                    }
                    <?php
                    if ($this->dataGridOptionData['DRILLDBLCLICKROW'] == 'true' && $this->dataGridOptionData['DRILL_CLICK_FNC'] && $this->dataGridOptionData['INLINEEDIT'] != 'true') {
                        echo $this->dataGridOptionData['DRILL_CLICK_FNC'];
                    }
                    ?>                    
                },                
                <?php
                if (isset($this->useBasket) && $this->useBasket || isset($this->useBasketBtn) && $this->useBasketBtn) { 
                    if ($this->isTreeGridData) {
                        echo 'onDblClickRow: function(row) { ';
                    } else {
                        echo 'onDblClickRow: function(index, row) { ';
                    }
                    
                if (isset($this->row['uniqueField'])) {
                    $primaryField = $this->row['uniqueField'];
                } elseif (isset($this->row['idField'])) {
                    $primaryField = $this->row['idField'];
                } else {
                    $primaryField = 'id';
                }
                ?>
                    var isAdded = false, rowId = row['<?php echo $primaryField; ?>']; 
                    
                    for (var key in _selectedRows_<?php echo $this->metaDataId; ?>) {
                        var basketRow = _selectedRows_<?php echo $this->metaDataId; ?>[key], 
                            childId = basketRow['<?php echo $primaryField; ?>'];

                        if (rowId == childId) {
                            isAdded = true;
                            break;
                        } 
                    }

                    <?php if ($this->layoutType === 'ecommerce' && isset($this->useBasket) && $this->useBasket) { 
                        $typeRow = $this->row['dataViewLayoutTypes']['ecommerce'];
                        ?>
                        var basketPhoto = '<?php echo issetParam($typeRow['fields']['basketphoto']); ?>'.toLowerCase();
                        var chooseTypeDataGrid = '<?php echo $this->chooseTypeBasket; ?>';

                        if (!isAdded) {
                            _selectedRows_<?php echo $this->metaDataId; ?>.push(row); 
                            basketPhoto = '<span class="tree-icon tree-file "></span>'; 

                            <?php if (issetParam($typeRow['fields']['basketphoto']) !== '') { ?>
                                basketPhoto = '<img src="'+row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>']+'" width="25" height="25" class="rounded-circle" alt="" onerror="onUserImgError(this);">'; 
                            <?php } ?>
                                
                            <?php if (issetParam($typeRow['fields']['basketname'])) { ?>
                                var $appendBasketHtml = '';
                                    $appendBasketHtml += '<li data-index="'+ _selectedRows_<?php echo $this->metaDataId; ?>.length +'" class="datagrid-row media p-1 border-bottom-1 border-gray"style="height: 43px;">' 
                                        + basketPhoto;
                                        $appendBasketHtml += '<div class="media-body <?php echo issetParam($typeRow['fields']['basketcode']) == '' ? 'one-row' : '' ?>">';
                                            $appendBasketHtml += '<div class="line-height-normal d-flex align-items-center">';
                                                $appendBasketHtml += '<span>' + row['<?php echo Str::lower($typeRow['fields']['basketname']); ?>'] + '</span>';
                                            $appendBasketHtml += '</div>';
                                            $appendBasketHtml += '<?php if (issetParam($typeRow['fields']['basketcode'])) { ?>';
                                                $appendBasketHtml += '<span class="memberposition" style="font-size: 10px;color: #999;text-transform: uppercase;">' + row['<?php echo Str::lower($typeRow['fields']['basketcode']); ?>'] + '</span>';
                                            $appendBasketHtml += '<?php } ?>';
                                        $appendBasketHtml += '</div>';
                                        $appendBasketHtml += '<div class="ml10 mr10 align-self-center">';
                                            $appendBasketHtml += '<a href="javascript:;" class="position-relative" onclick="removeCommerceBasket<?php echo $this->metaDataId; ?>(this)"><i class="fa fa-close basket-choose-icon"></i></a>';
                                        $appendBasketHtml += '</div>';
                                    $appendBasketHtml += '</li>';
                                $("#basket_ecommerce_<?php echo $this->metaDataId; ?>").append($appendBasketHtml);

                                $('.basket_ecommerce_counter_<?php echo $this->metaDataId; ?>').text('('+_selectedRows_<?php echo $this->metaDataId; ?>.length+')');
                                
                                if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
                                    $('#objectdatagrid-<?php echo $this->metaDataId ?>').closest("div.ui-dialog").children("div.ui-dialog-buttonpane").find("button.datagrid-choose-btn").click();
                                }                                
                            <?php } ?>
                                
                        }
                    <?php } else { ?>
                        if (!isAdded) {
                            _selectedRows_<?php echo $this->metaDataId; ?>.push(row);
                            $('.save-database-<?php echo $this->metaDataId; ?>').text(_selectedRows_<?php echo $this->metaDataId; ?>.length).pulsate({
                                color: '#F3565D', 
                                reach: 9,
                                speed: 500,
                                glow: false, 
                                repeat: 1
                            });   
                        } else {
                            $('.save-database-<?php echo $this->metaDataId; ?>').pulsate({
                                color: '#4caf50', 
                                reach: 9,
                                speed: 500,
                                glow: false, 
                                repeat: 1
                            });   
                        }                        
                    <?php } ?>
                }, 
                <?php 
                }

                echo $this->subgrid;

                if ($this->dataGridOptionData['INLINEEDIT'] == 'true') { 
                ?>                          
                onDblClickRow: function(index, row) {
                    if ('<?php echo $this->isGridType; ?>' === 'treegrid') {
                        index = index.id;
                    }
                    objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('beginEdit', index);          

                    var getField = $('input.textbox-text').parents('td.datagrid-row-selected').attr('field');
                    var ed = objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('getEditor', {index:index,field:getField});

                    $(windowId_<?php echo $this->metaDataId; ?>).on('keyup', ed.target, function(e) {
                        var code = e.keyCode || e.which;
                        if(code == 13) {
                            objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('endEdit', index);
                        }
                    });                    
                },
                onAfterEdit: function(index,row,changes){
                    if ('<?php echo $this->isGridType; ?>' === 'treegrid') {
                        row = index;
                    }
                    var actionType = 'update';
                    
                    if (row.hasOwnProperty('ck')) {
                        actionType = 'insert';
                    }
                    runUpdateInlineEditDataView_<?php echo $this->metaDataId; ?>(row, actionType);
                },
                <?php 
                }                

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
                        if (Config::getFromCache('javaversion') >= 1 && !issetParam($this->useBasket)) {
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
                    if (Config::getFromCache('javaversion') >= 1 && !issetParam($this->useBasket)) {
                ?>
                onBeforeLoad: function(param) {
                    var _thisGrid = $(this);
                    
                    if (typeof _isRunAfterProcessSave !== 'undefined') {
                        delete param.isNotUseReport;
                        
                        if (_isRunAfterProcessSave) {
                            param.isNotUseReport = 1;
                            _isRunAfterProcessSave = false;
                        }
                    }
                    
                    param.pagingWithoutAggregate = 1;
                    
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
                if ($this->metaDataId === '1573467098601412' && Config::getFromCache('isCivilUseFullSearch') == '1') { ?>
                    if (typeof data['rows'] !== 'undefined'&& typeof data['rows']['0'] !== 'undefined') {
                        addinProcessCriteria_<?php echo $this->metaDataId; ?>('', data['rows']['0']);
                    }
                <?php 
                } 
                ?>
                    
                if (data.status === 'error') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
                var _thisGrid = objectdatagrid_<?php echo $this->metaDataId; ?>;
                
                <?php 
                if (Config::getFromCache('dataviewDocumentCriteria-' . $this->metaDataId)) { 
                ?>
                    var _thisGridClass = $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>");
                    var criteriaDataArr = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serializeArray();
                <?php 
                    echo (Config::getFromCache('dataviewDocumentCriteria-' . $this->metaDataId)) ? html_entity_decode(Config::getFromCache('dataviewDocumentCriteria-' . $this->metaDataId)) : ''; 
                } 
                
                if ($this->isTreeGridData) {
                    echo "showTreeGridMessage(_thisGrid, '".issetParam($this->dataGridOptionData['MSGNORECORDFOUND'])."');"."\n";
                } else {
                    echo "showGridMessage(_thisGrid, '".issetParam($this->dataGridOptionData['MSGNORECORDFOUND'])."');"."\n";
                    
                    if (issetParam($this->dataGridOptionData['LOADAFTERUNCHECK']) != 'true') {
                        echo 'var currentSelectedRowIndex = $("#currentSelectedRowIndex", "#object-value-list-'.$this->metaDataId.'").val();'."\n";
                        echo 'if (currentSelectedRowIndex != "") {'."\n";
                            echo "_thisGrid.datagrid('selectRow', currentSelectedRowIndex);"."\n";
                        echo '}'."\n";
                    }
                } 
                ?>                        
                                       
                <?php if (!is_null($this->refreshTimer)) { ?>
                    if ($("#objectDataView_<?php echo $this->metaDataId; ?>").is(":visible")) {
                        window.clearTimeout(timer_<?php echo $this->metaDataId; ?>);
                        timer_<?php echo $this->metaDataId; ?> = window.setTimeout(function(){
                            objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('reload'); 
                            ecommerceReloadFilter('<?php echo $this->metaDataId; ?>');
                        }, <?php echo $this->refreshTimer; ?> * 1000);
                    }
                <?php } ?>                                           
            
                var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');

                <?php
                $dragSort = issetParam($this->dataGridOptionData['DRAGSORT']);
                if ($dragSort == 'true') { 
                ?>
                    $panelView.find('> .datagrid-view2 > .datagrid-body > table > tbody').sortable({
                        start: function(e, ui){
                            ui.placeholder.height(ui.item.outerHeight());
                        },
                        stop: function(e, ui){
                            var currentIndex = ui.item.attr('datagrid-row-index');
                            var getRowsSort = _thisGrid.datagrid('getRows'), prevOrder;
                            
                            if (typeof ui.item.prev().attr('datagrid-row-index') === 'undefined') {
                                prevOrder = ui.item.closest('.card').prev('table').find('tbody:first > tr').attr('datagrid-row-index');
                            } else {
                                prevOrder = ui.item.prev().attr('datagrid-row-index');
                            }
                            $.ajax({
                                type: 'post',
                                url: 'mdobject/dataViewSetOrder',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },                            
                                data: {
                                    dataViewId: '<?php echo $this->metaDataId ?>', 
                                    currentOrder: currentIndex,
                                    prevOrder: prevOrder,
                                    rows: getRowsSort
                                },
                                success: function (control) {
                                    objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('reload');                                
                                    Core.unblockUI();
                                }
                            });                        
                        }
                    });    
                <?php
                }
                ?>
                if (_thisGrid.datagrid('getRows').length == 0) {
                    var $tr = $panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                    $tr.find('td').find('div').find('span').each(function () {
                        this.remove();
                    });
                    
                    <?php if ($this->subgrid !== '') { ?>
                        var $bodytr = $panelView.find(".datagrid-view2").find(".datagrid-body").find('.datagrid-btable tbody'), joinTd = '';

                        $tr.find('td').each(function () {
                            joinTd += '<td><div style="width: '+ $(this).width() +'px; height: 20px;"></div></td>';
                        });
                        $bodytr.append('<tr>'+joinTd+'</tr>');
                    <?php } ?>
                } 
                <?php if (isset($isFirstRowSelect)) { ?>
                else {
                    if (!dvFirstLoad_<?php echo $this->metaDataId; ?>) {
                        dvFirstLoad_<?php echo $this->metaDataId; ?> = true;
                        var $panelViewR = $panelView.find('> .datagrid-view2 > .datagrid-body');
                        var $dgRow = $panelViewR.find('tr[datagrid-row-index="0"]');
                        if (getConfigValue('allMetaFirstRowDblSelect') === '1') {
                            $dgRow.dblclick();
                        }
                        $dgRow.click();
                    }
                }
                <?php } ?>
                $('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').find("input.datagrid-filter[data-filter='1']").removeAttr('data-filter');               
                                
                <?php 
                echo Arr::get($this->dataGridColumnData, 'filterCenterInit');
                echo Arr::get($this->dataGridColumnData, 'filterDateInit');
                echo Arr::get($this->dataGridColumnData, 'filterDateTimeInit');
                echo Arr::get($this->dataGridColumnData, 'filterTimeInit');
                echo Arr::get($this->dataGridColumnData, 'filterBigDecimalInit');
                echo Arr::get($this->dataGridColumnData, 'filterNumberInit');
                ?>
                    
                <?php if (isset($isMergeCells)) { ?>
                    var $mergeCellBtn = $(windowId_<?php echo $this->metaDataId; ?>).find('.value-grid-merge-cell');
                    if (!$mergeCellBtn.hasClass('init-merge-cell') || $mergeCellBtn.hasClass('active')) {
                        $mergeCellBtn.addClass('init-merge-cell active');
                        var isMergeColumn = JSON.parse('<?php echo json_encode(issetDefaultVal($this->dataGridColumnData['isMergeColumn'], array())); ?>');
                        <?php if (isset($mergeCellsKeyField)) { ?>
                            isMergeColumn.keyfield = '<?php echo $mergeCellsKeyField; ?>'; 
                        <?php } if ($this->isTreeGridData) { ?>
                        isMergeColumn.isTree = true;       
                        isMergeColumn.rows = data.rows;    
                        <?php } ?>
                        _thisGrid.datagrid('autoMergeCells', isMergeColumn);
                    }
                <?php } ?>
                
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

                <?php if ($this->layoutType === 'ecommerce') { ?>
                    if ($('.workflow-btn-<?php echo $this->metaDataId; ?>', "#object-value-list-<?php echo $this->metaDataId; ?>").length && !$('.workflow-btn-<?php echo $this->metaDataId; ?>').is(':visible')) {
                        if (typeof dvReSelectionRows === 'function') {
                            dvReSelectionRows(_thisGrid, $panelView);
                        }
                        var rows = getDataViewSelectedRowsByElement(_thisGrid);
                        if (rows.length > 0) {
                            <?php
                            if ($this->layoutType === 'ecommerce' && issetParam($this->dataGridOptionData['SHOWCHECKBOX']) == 'true') { 
                            ?>
                            console.log('test wfm status');
                            <?php
                            } else {
                            ?>
                            wfmstatusRender_<?php echo $this->metaDataId ?>();
                            <?php
                            }
                            ?>
                        } else {
                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
                        }
                    }                
                <?php } ?>
                
                Core.initFancybox($panelView);
                Core.initPulsate($panelView);
                $panelView.find('[data-popup="tooltip"]').tooltip();
                
                <?php if ($this->subgrid !== '') { ?>
                initExpandCollapseAllRowEvent('<?php echo $this->metaDataId; ?>');
                <?php } ?>
                    
                <?php if (isset($this->layoutType) && ($this->layoutType === 'newswidget' || $this->layoutType === 'filewidget')) { ?>
                    var $appendTitle = '<div class="list-icons">';
                        $appendTitle += '<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">'+ ((typeof data.total !== 'undefined') ? data.total : '0') +'</a>';
                    $appendTitle += '</div>';
                    
                    $(windowId_<?php echo $this->metaDataId; ?>).closest('div.layout-cart').find('span.caption-subject:eq(0)').append($appendTitle);
                    
                <?php 
                }
                if ($this->dataGridOptionData['ENABLEFILTER'] == 'true') { 
                ?>    
                
                initDVClearColumnFilterBtn($panelView, $panelFilterRow);    
                
                setTimeout(function() {
                    if ($(".multiple_filter_values", "#object-value-list-<?php echo $this->metaDataId; ?>").length) {
                        $(".multiple_filter_values", "#object-value-list-<?php echo $this->metaDataId; ?>").each(function(){
                            $(windowId_<?php echo $this->metaDataId; ?> + ' .datagrid-htable .datagrid-filter-c').find('input[name="'+$(this).attr('data-field')+'"]').css('width', $(windowId_<?php echo $this->metaDataId; ?> + ' .datagrid-htable .datagrid-filter-c').find('input[name="'+$(this).attr('data-field')+'"]').outerWidth() - 15 + 'px');
                        });
                    }   
                }, 0); 
                
                <?php } ?>    
                
                dvReloadFooterData(_thisGrid, dvLoadSuccessData_<?php echo $this->metaDataId; ?>);
                _thisGrid.datagrid('resize'); 
                
                if (typeof dvTreeGridOpenMode_<?php echo $this->metaDataId; ?> != 'undefined' && dvTreeGridOpenMode_<?php echo $this->metaDataId; ?>) {
                    _thisGrid.treegrid(dvTreeGridOpenMode_<?php echo $this->metaDataId; ?>);
                    delete dvTreeGridOpenMode_<?php echo $this->metaDataId; ?>;
                }
                
                _thisGrid.datagrid('fixRownumber');
                
                var tmpDvOpt = _thisGrid.datagrid('options'), tmpDvQueryParams = tmpDvOpt.queryParams;
                delete tmpDvQueryParams.isClickFilter;
                    
                dvDraggableRowsToFilter_<?php echo $this->metaDataId; ?>($panelView, _thisGrid);
            }            
        });
        
        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPager').pagination({
            showPageList: true,
            <?php
            if ($this->dataGridOptionData['PAGINATION'] == 'false') {
            ?>
            layout: ['<?php echo empty($this->dataGridOptionData['PAGESTYLE']) ? 'manual' : $this->dataGridOptionData['PAGESTYLE']; ?>','refresh','info'],
            <?php
            } else {
            ?>
            layout: ['list','sep','first','prev','sep','<?php echo empty($this->dataGridOptionData['PAGESTYLE']) ? 'manual' : $this->dataGridOptionData['PAGESTYLE']; ?>','sep','next','last','sep','refresh','info'],    
            <?php
            } 
            ?>    
            buttons: [{
                iconCls: 'pagination-sum',
                handler: function(){
                    dvSelectionRowsSumCount(objectdatagrid_<?php echo $this->metaDataId; ?>);
                }
            }]
        });
        
        <?php if (isset($this->viewType) && $this->viewType === 'gmap') { ?>
            if ($('.googleMapBtnByDataView_<?php echo $this->metaDataId; ?>').length) {
                $('.googleMapBtnByDataView_<?php echo $this->metaDataId; ?>').trigger('click');
            }
        <?php } ?>        
        
        <?php if ($this->dataGridOptionData['ENABLEFILTER'] == 'true') { ?>
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('enableFilter');
        <?php } ?>
            
        <?php if ($this->dataGridOptionData['INLINEEDIT'] == 'true') { ?>
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('enableCellEditing');
        <?php } ?>
            
        dvColumnShowCriteria_<?php echo $this->metaDataId; ?>();
            
        <?php
        if (issetParam($this->dataGridOptionData['KEYUPCLIENTSEARCH']) == 'true' 
            && issetParam($this->dataGridOptionData['KEYUPSERVERSIDESEARCH']) != 'true') {
        ?>
        
        var dvOrigRows_<?php echo $this->metaDataId; ?> = [];
        
        $('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').on('keyup', 'input.datagrid-filter', function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            
            if (keyCode != 13 && keyCode != 16 && keyCode != 17 && keyCode != 18) {
                var $this = $(this), colName = $this.attr('name'), rows = [], q = $this.val(),  
                    regExp = new RegExp(q, 'i'); // i - makes the search case-insensitive. 

                if (!dvOrigRows_<?php echo $this->metaDataId; ?>.length) {
                    dvOrigRows_<?php echo $this->metaDataId; ?> = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getRows');
                } 

                var data = dvOrigRows_<?php echo $this->metaDataId; ?>;
                
                if (typeof data !== 'undefined' && data.length) {
                    for (var k in data) {
                        if (regExp.test(data[k][colName])) {
                            rows.push(data[k]);
                        }
                    }
                    objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('loadData', rows);
                }
            }
        });
        <?php
        } 
        if (issetParam($this->dataGridOptionData['KEYUPSERVERSIDESEARCH']) == 'true') {
        ?>
        var dvRequestTimer;
        
        $('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').on('keyup', 'input.datagrid-filter', function(e) {
            
            var $this = $(this), keyCode = (e.keyCode ? e.keyCode : e.which);
            
            eventDelay(function() {

                if (keyCode != 9 && keyCode != 37 && keyCode != 39) {

                    dvRequestTimer && clearTimeout(dvRequestTimer);

                    dvRequestTimer = setTimeout(function() {

                        var getFilterPath = $this.attr('name'), filterVal = trim($this.val());

                        if (filterVal != '') {
                            dvFilterColumnColor('<?php echo $this->metaDataId; ?>', getFilterPath);
                        } else {
                            $('style#<?php echo $this->metaDataId; ?>-'+getFilterPath).remove();
                        }

                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('loading');

                        if (dvRequest_<?php echo $this->metaDataId; ?> != null) {
                            dvRequest_<?php echo $this->metaDataId; ?>.abort();
                        }

                        var getSortFields = getDataGridSortFields($("div#object-value-list-<?php echo $this->metaDataId; ?>"));
                        
                        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
                        } else {
                            var $packageTab = $("div#object-value-list-<?php echo $this->metaDataId; ?>").closest('div.package-meta-tab');
                            if ($packageTab.length) {
                                var $packageId = $packageTab.attr('data-realpack-id');
                                var defaultCriteriaData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
                            } else {
                                var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
                            }
                        }

                        var dataGridOpt = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options');
                        var queryParams = dataGridOpt.queryParams;
                        var postParams = {
                            metaDataId: '<?php echo $this->metaDataId; ?>',
                            defaultCriteriaData: defaultCriteriaData,
                            filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                            cardFilterData: $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val() + '=' + $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(),
                            workSpaceId: '<?php echo $this->workSpaceId; ?>',
                            workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                            uriParams: '<?php echo $this->uriParams; ?>', 
                            drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
                            subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
                            sortFields: getSortFields, 
                            page: 1, 
                            rows: dataGridOpt.pageSize
                        };

                        if (queryParams.hasOwnProperty('drillDownDefaultCriteria')) {
                            postParams['drillDownDefaultCriteria'] = queryParams.drillDownDefaultCriteria;
                        }

                        if (queryParams.hasOwnProperty('treeConfigs')) {
                            postParams['treeConfigs'] = queryParams.treeConfigs;
                        }

                        dvRequest_<?php echo $this->metaDataId; ?> = $.ajax({
                            type: 'post',
                            url: 'mdobject/dataViewDataGrid',
                            data: postParams, 
                            dataType: 'json', 
                            success: function(data) {

                                dvRequest_<?php echo $this->metaDataId; ?> = null;

                                if (dataGridOpt.idField === null) {
                                    objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('loadData', data);
                                } else {
                                    objectdatagrid_<?php echo $this->metaDataId; ?>.treegrid('loadData', data);
                                }

                                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('loaded');
                            }
                        });
                    }, 200);
                }
            
            }, 400);
        });
        <?php
        }
        ?>
        
        $('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').on('keydown', 'input.datagrid-filter', function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            
            if (keyCode == 40) { /* down */
                var $grid = objectdatagrid_<?php echo $this->metaDataId; ?>;
                
                if ($grid.<?php echo $this->isGridType; ?>('getData').<?php echo ($this->isGridType == 'datagrid' ? 'total' : 'length'); ?>) {

                    $grid.<?php echo $this->isGridType; ?>('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').focus(); 
                    
                    <?php
                    if ($this->isGridType == 'datagrid') {
                    ?>
                    $grid.datagrid('selectRow', 0);
                    $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val('0');
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
                var $rowInput = $row.find('td:visible input:visible:not([data-all="1"], [data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)');
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
                
            } else if ((!e.shiftKey && keyCode == 39) || (!e.shiftKey && keyCode == 9)) { /* right OR tab */
                
                var $this = $(this), $row = $this.closest('tr'), 
                    $rowInput = $row.find('td:visible input:visible:not([data-all="1"], [data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)'), 
                    $cellIndex = $rowInput.index($this), isFirstFocus = false; 
                
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
                
            } else if (keyCode == 13) { 
            
                var $this = $(this), getFilterPath = $this.attr('name'), filterVal = trim($this.val());

                if (filterVal != '') {
                    
                    if ($('head').find('style#<?php echo $this->metaDataId; ?>-'+getFilterPath).length == 0) {
                        
                        $('head').append('<style type="text/css" id="<?php echo $this->metaDataId; ?>-'+getFilterPath+'">'+
                        '.div-objectdatagrid-<?php echo $this->metaDataId; ?> .datagrid-header td[field="'+getFilterPath+'"] span { color: #00139a } '+
                        '.div-objectdatagrid-<?php echo $this->metaDataId; ?> .datagrid-header td[field="'+getFilterPath+'"],'+
                        '.div-objectdatagrid-<?php echo $this->metaDataId; ?> .datagrid-body td[field="'+getFilterPath+'"] {'+
                            'background-color: rgb(166, 233, 255);'+
                        '}</style>');      
                    }
                    
                } else {
                    $('style#<?php echo $this->metaDataId; ?>-'+getFilterPath).remove();
                }
                
                <?php
                if (issetParam($this->dataGridOptionData['KEYUPCLIENTSEARCH']) == 'true' 
                    && issetParam($this->dataGridOptionData['KEYUPSERVERSIDESEARCH']) != 'true') {
                ?>
                dvOrigRows_<?php echo $this->metaDataId; ?> = [];
                <?php
                }
                ?>
                            
                dvMultipleFilterValuesRemove_<?php echo $this->metaDataId; ?>();
                
            } else {
                $(this).attr('data-filter', '1');
            }
        });
        
        $('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').on('focusout', 'input.datagrid-filter', function(e){
            
            var $this = $(this), getFilterPath = $this.attr('name'), filterVal = trim($this.val());
            var op = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options');            

            if (filterVal == '') {
                $('style#<?php echo $this->metaDataId; ?>-'+getFilterPath).remove();
                delete dvFilterValues_<?php echo $this->metaDataId; ?>[getFilterPath];
            } else {
                dvFilterValues_<?php echo $this->metaDataId; ?>[getFilterPath] = filterVal;                
            }                                     
            
            if (Object.keys(dvFilterValues_<?php echo $this->metaDataId; ?>).length) {
                window.location.hash = 'dvFilterValues='+JSON.stringify(dvFilterValues_<?php echo $this->metaDataId; ?>);
            } else {
                window.location.hash = '';
            }
            
            if (typeof op.hasOwnProperty('filterOnlyEnterKey') == 'undefined' || (typeof op.hasOwnProperty('filterOnlyEnterKey') !== 'undefined' && !op.filterOnlyEnterKey)) {
                
                if (typeof $this.attr('data-filter') !== 'undefined') {
                    
                    if (filterVal != '') {
                
                        if ($('head').find('style#<?php echo $this->metaDataId; ?>-'+getFilterPath).length == 0) {

                            $('head').append('<style type="text/css" id="<?php echo $this->metaDataId; ?>-'+getFilterPath+'">'+
                                '.div-objectdatagrid-<?php echo $this->metaDataId; ?> .datagrid-header td[field="'+getFilterPath+'"] span { color: #00139a } '+
                                '.div-objectdatagrid-<?php echo $this->metaDataId; ?> .datagrid-header td[field="'+getFilterPath+'"],'+
                                '.div-objectdatagrid-<?php echo $this->metaDataId; ?> .datagrid-body td[field="'+getFilterPath+'"] {'+
                                    'background-color: rgb(166, 233, 255);'+
                                '}</style>');   
                        }
                    }
            
                    $this.removeAttr('data-filter');                    
                    customSearch_<?php echo $this->metaDataId; ?>(e, this, objectdatagrid_<?php echo $this->metaDataId; ?>);
                }
            }
        });
        
        setTimeout(function(){
            var $mandatoryCriteriaForm = $('#object-value-list-<?php echo $this->metaDataId; ?>').find('form#default-mandatory-criteria-form'); 
            if ($mandatoryCriteriaForm.length) {
                $mandatoryCriteriaForm.validate({ 
                    ignore: '', 
                    highlight: function(label) {
                        $(label).addClass('error');
                    },
                    unhighlight: function(label) {
                        $(label).removeClass('error');
                    },
                    errorPlacement: function(){} 
                });
                $mandatoryCriteriaForm.valid();
                
                if (!isTouch) {
                    $mandatoryCriteriaForm.find('input:visible:not([data-all="1"], [data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, select.select2, .select2-focusser):first').focus().select();
                }
                
            } else if (!isTouch && objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find('> div.datagrid-view').find('> .datagrid-view2 > .datagrid-header tr.datagrid-filter-row > td:eq(0) input[type=text]').length) {
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find('> div.datagrid-view').find('> .datagrid-view2 > .datagrid-header tr.datagrid-filter-row > td:eq(0) input[type=text]').focus();
            } else if (!isTouch) {
                $('#object-value-list-<?php echo $this->metaDataId; ?>').find('form#default-criteria-form').find('input:visible:not([data-all="1"], [data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, select.select2, .select2-focusser):first').focus().select();
            }
        }, 120);
        
        <?php
        if ($this->isGridType == 'datagrid') {
            if (!isset($this->useBasket) || (isset($this->useBasket) && !$this->useBasket)) { ?>
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').attr('tabindex','-1').css('outline-style','none').bind('keydown', function(e){
                    var keyCode = (e.keyCode ? e.keyCode : e.which);
                    var $grid = objectdatagrid_<?php echo $this->metaDataId; ?>;
                    var isOpenContextMenu = false;

                    if ($('body').find('.context-menu-root').length > 0 && $('body').find('.context-menu-root').is(':visible')) {
                        isOpenContextMenu = true;
                    } 

                    $('body').find('.btn-group.open').removeClass('open');

                    if (!e.ctrlKey && keyCode == 38 && !isOpenContextMenu) { // up

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

                                $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val(selectionIndex);

                            } else {
                                var sIndex = $grid.datagrid('getRows').length - 1;
                                $grid.datagrid('selectRow', sIndex);
                                $panelView.scrollTop(1000);

                                $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val(sIndex);
                            }

                        } else {
                            $grid.datagrid('selectRow', 0);
                            $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val('0');
                        }

                    } else if (keyCode == 40 && !isOpenContextMenu) { // down

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

                                $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val(selectionIndex);

                            } else {
                                $grid.datagrid('selectRow', 0);
                                $panelView.scrollTop(0);

                                $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val('0');
                            }

                        } else {
                            $grid.datagrid('selectRow', 0);
                            $("#currentSelectedRowIndex", "#object-value-list-<?php echo $this->metaDataId; ?>").val('0');
                        }

                    } else if (keyCode === 13) { // enter

                        var $focusedElem = $(document.activeElement);
                        if ($focusedElem.length && $focusedElem.prop('tagName') == 'INPUT') {
                            return false;
                        }

                        var selected = $grid.datagrid('getSelected'), 
                            $objectPanel = $('#object-value-list-<?php echo $this->metaDataId; ?>');

                        if ($objectPanel.find('a[data-actiontype]').length == 1 
                            && ($objectPanel.find('a[data-actiontype="update"]').length == 1 || $objectPanel.find('a[data-actiontype="delete"]').length == 1)
                            ) {
                            $objectPanel.find('a[data-actiontype]:visible:first').click();
                        } else if ($objectPanel.find('a[data-actiontype="update"]').length == 1 && $objectPanel.find('a[data-actiontype="delete"]').length == 0) {
                            $objectPanel.find('a[data-actiontype="update"]:visible:first').click();
                        } else if ($objectPanel.find('a[data-actiontype="update"]').length == 0 && $objectPanel.find('a[data-actiontype="delete"]').length == 1) {
                            $objectPanel.find('a[data-actiontype="delete"]:visible:first').click();
                        } else {
                            var index = $grid.datagrid('getRowIndex', selected); 
                            var $panelView = $grid.datagrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                            var $dgRow = $panelView.find('tr[datagrid-row-index="'+index+'"]');

                            var eContextMenu = jQuery.Event('contextmenu');
                            eContextMenu.pageX = $panelView.offset().left + 300;
                            eContextMenu.pageY = $dgRow.offset().top;
                            $dgRow.trigger(eContextMenu);
                        }

                    } else if (!e.shiftKey && !e.ctrlKey && keyCode === 46) { // delete

                        var $objectPanel = $('#object-value-list-<?php echo $this->metaDataId; ?>');

                        if ($objectPanel.find('a[data-actiontype="delete"]:eq(0)').length > 0 && $objectPanel.find('a[data-actiontype="delete"]:eq(0)').is(':visible')) {
                            $objectPanel.find('a[data-actiontype="delete"]:visible:first').click();
                        } else if ($objectPanel.find("a:contains('стгах'):eq(0)").length > 0 && $objectPanel.find("a:contains('стгах'):eq(0)").is(':visible')) {
                            $objectPanel.find("a:contains('стгах'):visible:first").click();
                        }

                    } else if (keyCode === 109 && !isOpenContextMenu) { // -
                        var selected = $grid.datagrid('getSelected');
                        if (selected) { 
                            var index = $grid.datagrid('getRowIndex', selected);
                            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('expandRow', index);
                        }
                    } else if (keyCode === 107 && !isOpenContextMenu) { // +
                        var selected = $grid.datagrid('getSelected');
                        if (selected) { 
                            var index = $grid.datagrid('getRowIndex', selected);
                            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('collapseRow', index);
                        }
                    } else if (e.ctrlKey && keyCode == 38) {
                        $grid.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-header tr.datagrid-filter-row > td:eq(0) input[type=text]').focus().select();
                        e.preventDefault();
                        return false;
                    }
            });
            <?php } ?>
        <?php
        } else {
        ?>
        objectdatagrid_<?php echo $this->metaDataId; ?>.treegrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').attr('tabindex','-1').css('outline-style','none').bind('keydown', function(e){
        
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            var $grid = objectdatagrid_<?php echo $this->metaDataId; ?>;
            var isOpenContextMenu = false;
            
            if ($('body').find('.context-menu-root').length > 0 && $('body').find('.context-menu-root').is(':visible')) {
                isOpenContextMenu = true;
            } 
            
            $('body').find('.btn-group.open').removeClass('open');
            
            if (keyCode == 38 && !isOpenContextMenu) { // up
                
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
                
            } else if (keyCode == 40 && !isOpenContextMenu) { // down
                
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
                
            } else if (keyCode === 13) { // enter
                
                var $focusedElem = $(document.activeElement);
                if ($focusedElem.length && $focusedElem.prop('tagName') == 'INPUT') {
                    return false;
                }
                
                var selected = $grid.treegrid('getSelected');
                var index = selected.<?php echo $isTreeGridData['id']; ?>; 
                var $panelView = $grid.treegrid('getPanel').find("div.datagrid-view > .datagrid-view2 > .datagrid-body");
                var $dgRow = $panelView.find('tr[node-id="'+index+'"]');
                
                var eContextMenu = jQuery.Event('contextmenu');
                eContextMenu.pageX = $panelView.offset().left + 300;
                eContextMenu.pageY = $dgRow.offset().top;
                $dgRow.trigger(eContextMenu);
                
            } else if (!e.shiftKey && !e.ctrlKey && keyCode === 46) { // delete
                    
                if ($('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-actiontype="delete"]:eq(0)').length > 0 
                    && $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-actiontype="delete"]:eq(0)').is(':visible')) {
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-actiontype="delete"]:visible:first').click();
                } else if ($('#object-value-list-<?php echo $this->metaDataId; ?>').find("a:contains('стгах'):eq(0)").length > 0 
                    && $('#object-value-list-<?php echo $this->metaDataId; ?>').find("a:contains('стгах'):eq(0)").is(':visible')) {
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find("a:contains('стгах'):visible:first").click();
                }
            }
        });    
        <?php
        }
        ?>    
        
        $(".value-grid-merge-cell", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() {
            var $mergeBtn = $(this);
            if ($mergeBtn.hasClass("active")) {
                $mergeBtn.removeClass("active").addClass("init-merge-cell");
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('reload');
            } else {
                var isMergeColumn = JSON.parse('<?php echo json_encode((isset($this->dataGridColumnData['isMergeColumn'])) ? $this->dataGridColumnData['isMergeColumn'] : array()); ?>');
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid("autoMergeCells", isMergeColumn);
                $mergeBtn.addClass("active").removeClass("init-merge-cell");
            }
        });
        
        if (dataGridTypeBtn_<?php echo $this->metaDataId; ?> == 'googlemap') {
          
            if ('<?php echo $this->metaDataId; ?>' == '1529727324487237' || '<?php echo $this->metaDataId; ?>' == '1539681311719') {
                
                $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").on('change', 'input:not([data-all="12"]), select', function(){
                    $(".datagrid", "#object-value-list-<?php echo $this->metaDataId; ?>").hide();
                    $("#md-map-canvas-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").css('height', $(window).height() - 225);
                    $("#md-map-canvas-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").show().parent().removeClass('hidden');
                    $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").parent().parent().addClass('hidden');
                    dataGridTypeBtn_<?php echo $this->metaDataId; ?> = 'googlemap';
                    googleMapByDataView_<?php echo $this->metaDataId; ?>();
                });       
                 
                dv_search_<?php echo $this->metaDataId; ?>.css({'max-height': dynamicHeight, 'overflow': 'auto'});
            }
            $("button.dataview-default-filter-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() {
                googleMapByDataView_<?php echo $this->metaDataId; ?>();
            });
        }
        
        $('.caption.card-collapse.dataview', "#object-value-list-<?php echo $this->metaDataId; ?>").off('click').on('click', function(e) {
            var $this = $(this);
            var $thisParent = $this.parent();
            var $el = $this.closest(".card").children(".card-body");
            var $element = $thisParent.find("a.tool-collapse");
            if ($element.hasClass("collapse")) {
                $element.removeClass("collapse").addClass("expand");
                $el.css({'display': 'none'}).addClass("display-none");
            } else {
                $element.removeClass("expand").addClass("collapse");
                $el.css({'display': ''}).removeClass("display-none");
            }
        });           
        
        $("button.dataview-default-filter-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() {
            var $this = $(this);
            dvIgnoreFirstLoad_<?php echo $this->metaDataId; ?> = false;
            kpiDvSearchInline_<?php echo $this->metaDataId ?>("#object-value-list-<?php echo $this->metaDataId; ?>");   
            
            dvColumnShowCriteria_<?php echo $this->metaDataId; ?>();

            var $criTemSelector = $('input[name="criteriaTemplateName"]', windowId_<?php echo $this->metaDataId; ?>);
            if ($criTemSelector.length && $criTemSelector.is(':visible')) {
                if ($criTemSelector.val() === '') {
                    $criTemSelector.addClass('border-danger')
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Загварын нэр оруулна уу!',
                        type: 'warning',
                        addclass: pnotifyPosition,
                        sticker: false
                    });                            
                    return;
                } else { 
                    $criTemSelector.removeClass('border-danger')
                    $('select[name="criteriaTemplates"]', windowId_<?php echo $this->metaDataId; ?>).removeClass('data-combo-set');
                }
            }                             
            
            if ('1' != '<?php echo issetParam($this->row['IS_CRITERIA_ALWAYS_OPEN']); ?>') {
                $this.closest('.card').find('> .card-header > .caption.card-collapse').trigger('click');
            }
            
            if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
            } else {
                var $packageTab = $this.closest('div.package-meta-tab');
                if ($packageTab.length && $packageTab.attr('data-realpack-id')) {
                    var $packageId = $packageTab.attr('data-realpack-id');
                    var $packageMeta = $('#package-meta-' + $packageId);
                    var defaultCriteriaData = $packageMeta.find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
                    packageCountRefresh($packageMeta, '<?php echo $this->metaDataId; ?>', defaultCriteriaData);
                } else {
                    var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
                }
            }

            dvMultipleFilterValuesRemove_<?php echo $this->metaDataId; ?>();       
            
            if ($('body').find('form.adv-criteria-form-<?php echo $this->metaDataId; ?>').length) {
                defaultCriteriaData += '&' + $('body').find('form.adv-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
            }

            var $packageTab = $("div#object-value-list-<?php echo $this->metaDataId; ?>").closest('div.package-meta-tab');

            if ($(".bp-icon-selection", "#object-value-list-<?php echo $this->metaDataId; ?>").length || $packageTab.length  > 0) {
                var getPostData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serializeArray();
                var $packageId = $packageTab.attr('data-realpack-id');
                if ($packageTab.length > 0) {
                    getPostData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serializeArray();
                }
                
                var dvDefaultCriteria = {}, dvDefaultCriteriaCondition = {};       
                if (getPostData) {
                    for (var fdata = 0; fdata < getPostData.length; fdata++) {
                        var mPath1 = /criteriaCondition\[([\w.]+)\]/g.exec(getPostData[fdata].name);                    
                        if (mPath1 != null) {
                            dvDefaultCriteriaCondition[mPath1[1]] = getPostData[fdata].value;
                        }
                    }        
                    for (var fdata = 0; fdata < getPostData.length; fdata++) {
                        var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                        if(mPath === null) continue;                    

                        dvDefaultCriteria[mPath[1]] = [{operator: dvDefaultCriteriaCondition[mPath[1]] ? dvDefaultCriteriaCondition[mPath[1]] : '=', operand:getPostData[fdata].value}];
                    }        
                }             

                var $iconWrapCombo = $(".bp-icon-selection", "#object-value-list-<?php echo $this->metaDataId; ?>");
                if ($packageTab.length > 0) {
                    $iconWrapCombo = $('#package-meta-' + $packageId).find(".<?php echo $this->metaDataId; ?>_default_criteria .bp-icon-selection");
                }
                var lookUpMetaId = $iconWrapCombo.attr('data-metagroupid');

                $.ajax({
                    type: 'post',
                    url: 'api/callDataview',
                    data: {dataviewId: lookUpMetaId, criteriaData: dvDefaultCriteria}, 
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success' && data.result[0]) {
                            for (var ici = 0; ici < data.result.length; ici++) {
                                $iconWrapCombo.find('> li[data-id="'+data.result[ici]['id']+'"]').find('span.badge-pill').attr('title', data.result[ici]['count']).text(data.result[ici]['count']);
                            }
                        }
                    }
                });     
            }
            
            var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
                $op = $dataGrid.datagrid('options'), 
                queryParams = $op.queryParams;
            
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: defaultCriteriaData, 
                workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
                ignoreFirstLoad: dvIgnoreFirstLoad_<?php echo $this->metaDataId; ?>, 
                isClickFilter: 1
            };         
            
            if (queryParams.hasOwnProperty('drillDownDefaultCriteria')) {
                dvSearchParam.drillDownDefaultCriteria = queryParams.drillDownDefaultCriteria;
            }
            
            if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length) {
                var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                    var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory], 
                        cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                    dvSearchParam['cardFilterData'] = cardFilterDataVar;
                }
            }
            
            if ($('.div-ganttLayout-<?php echo $this->metaDataId; ?>').is(":visible")) {
                gantt.clearAll();
                gantt.ajax.post({
                    url:"Mdwidget/getEvents?metaDataId=<?php echo $this->metaDataId; ?>",
                    method:"POST",
                    data: dvSearchParam
                }).then(function(response){
                    gantt.parse(response.responseText);
                });
            } else {
            
                <?php
                if (isset($this->row['IS_COUNTCARD_OPEN']) && $this->row['IS_COUNTCARD_OPEN'] == '1' && $this->layoutType !== 'ecommerce') {
                    echo 'dataViewFilterCardViewForm_'.$this->metaDataId.'(this, true);';
                }
                ?>
                
                if ($op.idField === null) {
                    $dataGrid.datagrid('load', dvSearchParam);
                } else {
                    $dataGrid.treegrid('load', dvSearchParam);
                }
                
                $(".left-stoggler", "#object-value-list-<?php echo $this->metaDataId; ?>").trigger('click');
                $(".search-sidebar-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").trigger('click');
                
                lookupCriteriaRefresh_<?php echo $this->metaDataId; ?>();
            }                        

            /*if (dataGridTypeBtn_<?php echo $this->metaDataId; ?> == 'googlemap') {                
                var coor = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").find('[data-path="coordinateString"]').val().split('|');
                var bounds = new google.maps.LatLngBounds();

                gotoLocation = new google.maps.LatLng(coor[1], coor[0]);
                bounds.extend(gotoLocation);                
                map.fitBounds(bounds);
            }*/
        });
        
        $("button.dataview-default-filter-reset-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() {
            
            var $this = $(this), $thisForm = $this.closest("form#default-criteria-form");
                
            $thisForm.find("input[type=text], input[type=hidden], textarea").not("input[name='inputMetaDataId'], select.right-radius-zero, input[name*='criteriaCondition[']").val('');
            $thisForm.find("input[type=radio], input[type=checkbox]").removeAttr('checked');
            $thisForm.find("input[type=radio], input[type=checkbox]").closest('span.checked').removeClass('checked');
            $thisForm.find("select.select2").select2('val', '');
            $thisForm.find('.bp-icon-selection > li.active').removeClass('active');
            $thisForm.find('.btn.removebtn[data-lookupid]').hide();
            $thisForm.find('.btn[data-lookupid][data-choosetype][data-idfield][onclick]').text('..');
            $thisForm.find('input[name*="idWithComma["], button[onclick*="dvOnlySearchFormReset"]').remove();
        
            var $thisFormAdv = $("body").find("div#dialog-dvecommerce-criteria-<?php echo $this->metaDataId; ?> form#adv-criteria-form");
            
            if ($thisFormAdv) {
                $thisFormAdv.find("input[type=text], input[type=hidden], textarea").not("input[name='inputMetaDataId'], select.right-radius-zero, input[name*='criteriaCondition[']").val('');
                $thisFormAdv.find("input[type=radio], input[type=checkbox]").removeAttr('checked');
                $thisFormAdv.find("input[type=radio], input[type=checkbox]").closest('span.checked').removeClass('checked');
                $thisFormAdv.find("select.select2").select2('val', '');
                $thisFormAdv.find('.bp-icon-selection > li.active').removeClass('active');
                $thisFormAdv.find('.btn.removebtn[data-lookupid]').hide();
                $thisFormAdv.find('.btn[data-lookupid][data-choosetype][data-idfield][onclick]').text('..');
            }

            <?php if (issetParam($this->row['IS_CLEAR_DRILL_CRITERIA']) == '1') { ?>
                var queryParams = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options').queryParams;
                if (queryParams.hasOwnProperty('drillDownDefaultCriteria')) {
                    queryParams.drillDownDefaultCriteria = '';
                }
            <?php } ?>        
            
            dvMultipleFilterValuesRemove_<?php echo $this->metaDataId; ?>();
            
            <?php
            if (issetParam($this->row['IS_IGNORE_CLEAR_FILTER']) != '1') {
            ?>
            $thisForm.append('<input type="hidden" name="isFilterReset" value="1">');        
            $("button.dataview-default-filter-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").trigger('click');
            $thisForm.find('input[name="isFilterReset"]').remove();        
            
            if (dataGridTypeBtn_<?php echo $this->metaDataId; ?> == 'googlemap') {
                if ('<?php echo $this->metaDataId; ?>' == '1529727324487237' || '<?php echo $this->metaDataId; ?>' == '1539681311719') {
                    googleMapByDataView_<?php echo $this->metaDataId; ?>();
                }
            }
            <?php
            }
            ?>
        });
        
        $("input[name='mandatoryNoSearch']", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() {
            var $this = $(this);
            var $thisForm = $this.closest("form#default-mandatory-criteria-form");
            
            if ($this.is(':checked')) {
                
                $thisForm.find("input[type=text]").attr('readonly', 'readonly');
                $thisForm.find("select.select2").select2('readonly', true);
                $thisForm.find("button").attr('disabled', 'disabled');
                
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    defaultCriteriaData: $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize(), 
                    workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                    workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                    uriParams: '<?php echo $this->uriParams; ?>', 
                    drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>', 
                    treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                    filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                    ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                    subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
                };
                if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length > 0) {
                    var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                    if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                        var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory], 
                            cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                        dvSearchParam['cardFilterData'] = cardFilterDataVar;
                    }
                }   

                var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
                    $op = $dataGrid.datagrid('options');
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
        
        $(".left-stoggler", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function () {
            var _thisToggler = $(this);
            var leftsidebar = $(".left-sidebar", "#object-value-list-<?php echo $this->metaDataId; ?>");
            var leftsidebarstatus = leftsidebar.attr("data-status");
            if (leftsidebarstatus === "closed") {
                leftsidebar.find(".glyphicon-chevron-left").parent().hide();
                leftsidebar.find(".glyphicon-chevron-right").hide();
                leftsidebar.find(".left-sidebar-content").show();
                leftsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");
                leftsidebar.find(".glyphicon-chevron-left").fadeIn("slow");
                leftsidebar.attr('data-status', 'opened');
                _thisToggler.addClass("sidebar-opened");
            } else {
                leftsidebar.find(".glyphicon-chevron-left").hide();
                leftsidebar.find(".glyphicon-chevron-left").parent().hide();
                leftsidebar.find(".left-sidebar-content").hide();
                leftsidebar.find(".glyphicon-chevron-right").parent().fadeIn("slow");
                leftsidebar.find(".glyphicon-chevron-right").fadeIn("slow");
                leftsidebar.attr('data-status', 'closed');
                _thisToggler.removeClass("sidebar-opened");
            }
        });
        
        $(".search-sidebar-<?php echo $this->metaDataId; ?>").on("click", function () {
            var $thisToggler = $(this);
            var $topsidebar = $('#object-value-list-<?php echo $this->metaDataId; ?>').find('.top-sidebar');
            var topsidebarstatus = $topsidebar.attr('data-status');
            var $overflowHiddenParent = $thisToggler.closest('.overflow-hidden');
            
            if (!$topsidebar.hasAttr('data-status') || topsidebarstatus === 'closed') {
                var thisHeight = $thisToggler.height();
                var scrollHeight = $(window).height() - $thisToggler.offset().top - thisHeight - 150;
                
                $topsidebar.find('.height-dynamic').css('max-height', scrollHeight);
                
                $topsidebar.find(".glyphicon-chevron-left").parent().hide();
                $topsidebar.find(".glyphicon-chevron-right").hide();
                $topsidebar.css('top', (thisHeight + 20) + 'px;');
                $topsidebar.find(".top-sidebar-content").show();
                $topsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");
                $topsidebar.find(".glyphicon-chevron-left").fadeIn("slow");
                $topsidebar.attr('data-status', 'opened');
                $thisToggler.addClass("sidebar-opened");
                
                if ($overflowHiddenParent.length) {
                    $overflowHiddenParent.addClass('overflow-visible');
                }
                
            } else {
                $topsidebar.find(".glyphicon-chevron-left").hide();
                $topsidebar.find(".glyphicon-chevron-left").parent().hide();
                $topsidebar.find(".top-sidebar-content").hide();
                $topsidebar.find(".glyphicon-chevron-right").parent().fadeIn("slow");
                $topsidebar.find(".glyphicon-chevron-right").fadeIn("slow");
                $topsidebar.attr('data-status', 'closed');
                $thisToggler.removeClass("sidebar-opened");
                
                if ($overflowHiddenParent.length) {
                    $overflowHiddenParent.removeClass('overflow-visible');
                }
            }
        });
        
        $('.workflow-btn-<?php echo $this->metaDataId ?>').on('click', function (e, type) {
            wfmstatusRender_<?php echo $this->metaDataId ?> (e, type);
        });
        
        $('.workflow-btn-group-<?php echo $this->metaDataId ?>').on('show.bs.dropdown', function() {
            $(this).closest('.center-sidebar.overflow-hidden.content').removeClass('overflow-hidden');
        });
        
        $('.workflow-btn-group-<?php echo $this->metaDataId ?>').on('hidden.bs.dropdown', function() {
            $(this).closest('.center-sidebar.content').addClass('overflow-hidden');
        });
        
        $(window).bind('resize', function() {
            var $dvElem = $("body").find("#object-value-list-<?php echo $this->metaDataId; ?>");
            if ($dvElem.length > 0 && $dvElem.is(':visible') && $dvElem.find('.panel-eui').length) {
        
                var toolbarHeight = $("body").find("#object-value-list-<?php echo $this->metaDataId; ?>").find('div.table-toolbar:first').width();
                var dataGridHeight = $("body").find("#object-value-list-<?php echo $this->metaDataId; ?>").find('div.datagrid-wrap:first').width();
                    
                if ($("#isDynamicHeightDatagrid", "#object-value-list-<?php echo $this->metaDataId; ?>").val() == '1') {
                    var defHe = 18;
                    var dynamicHeight = $(window).height() - $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").offset().top - defHe;

                    if (dynamicHeight < 230) {
                        dynamicHeight = 350;
                    }

                    if (!modeType_<?php echo $this->metaDataId; ?>) {
                        dynamicHeight = dynamicHeight - 130;
                    }
                    <?php if ((isset($this->dataGridDefaultHeight) && $this->dataGridDefaultHeight == 'relative') == false) { ?>
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('resize');
                    <?php } else { ?>
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('resize', {
                            height: dynamicHeight
                        });
                    <?php } ?>

                } else {
                    objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('resize');
                }
            }
        });
        
        <?php
        if ($this->isTree) {
        ?>
        drawTree_<?php echo $this->metaDataId; ?>();
        <?php
        }
        ?>
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("keydown", 'input:not(.meta-autocomplete, .dateInit, .meta-name-autocomplete)', function (e) {
            if (e.which === 13) {
                
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div#object-value-list-<?php echo $this->metaDataId; ?>").serialize(), 
                    workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                    workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                    uriParams: '<?php echo $this->uriParams; ?>', 
                    treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                    filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                    ignorePermission: '<?php echo issetParam($this->ignorePermission); ?>', 
                    subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
                };
                
                if ($('.div-ganttLayout-<?php echo $this->metaDataId; ?>').is(":visible")) {
                    gantt.clearAll();
                    gantt.ajax.post({ 
                        url:"Mdwidget/getEvents?metaDataId=<?php echo $this->metaDataId; ?>",
                        method:"POST",
                        data: dvSearchParam
                    }).then(function(response){
                        gantt.parse(response.responseText)
                    });
                } else {
                
                    if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length) {
                        var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                        if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                            var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory], 
                                cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                            dvSearchParam['cardFilterData'] = cardFilterDataVar;
                        }
                    }
                    
                    var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
                        $op = $dataGrid.datagrid('options');
                    if ($op.idField === null) {
                        $dataGrid.datagrid('load', dvSearchParam);
                    } else {
                        $dataGrid.treegrid('load', dvSearchParam);
                    }
                }
            }
        });
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("changeDate", 'input.dateInit', function (e) {
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div#object-value-list-<?php echo $this->metaDataId; ?>").serialize(), 
                workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>', 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
            };
                
            if ($('.div-ganttLayout-<?php echo $this->metaDataId; ?>').is(":visible")) {
                gantt.clearAll();
                gantt.ajax.post({ 
                    url:"Mdwidget/getEvents?metaDataId=<?php echo $this->metaDataId; ?>",
                    method:"POST",
                    data: dvSearchParam
                }).then(function(response){
                    gantt.parse(response.responseText)
                });
            } else {

                if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length) {
                    var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                    if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                        var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory], 
                            cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                        dvSearchParam['cardFilterData'] = cardFilterDataVar;
                    }
                }            

                <?php
                if (isset($this->row['IS_COUNTCARD_OPEN']) && $this->row['IS_COUNTCARD_OPEN'] == '1' && $this->layoutType !== 'ecommerce') {
                    echo 'dataViewFilterCardViewForm_'.$this->metaDataId.'(this, true);';
                }
                ?>
                
                var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
                    $op = $dataGrid.datagrid('options');
                if ($op.idField === null) {
                    $dataGrid.datagrid('load', dvSearchParam);
                } else {
                    $dataGrid.treegrid('load', dvSearchParam);
                }
                
                lookupCriteriaRefresh_<?php echo $this->metaDataId; ?>();
            }
        });
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on('change', 'input.popupInit, .dropdownInput', function (e) {

            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div#object-value-list-<?php echo $this->metaDataId; ?>").serialize(), 
                workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>', 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
            };
                
            if ($('.div-ganttLayout-<?php echo $this->metaDataId; ?>').is(":visible")) {
                gantt.clearAll();
                gantt.ajax.post({ 
                    url:"Mdwidget/getEvents?metaDataId=<?php echo $this->metaDataId; ?>",
                    method:"POST",
                    data: dvSearchParam
                }).then(function(response){
                    gantt.parse(response.responseText)
                });
                
            } else {
                if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length) {
                    var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                    if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                        var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory], 
                            cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                        dvSearchParam['cardFilterData'] = cardFilterDataVar;
                    }
                }      
                
                <?php
                if (isset($this->row['IS_COUNTCARD_OPEN']) && $this->row['IS_COUNTCARD_OPEN'] == '1' && $this->layoutType !== 'ecommerce') {
                    echo 'dataViewFilterCardViewForm_'.$this->metaDataId.'(this, true);';
                }
                ?>
                
                var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
                    $op = $dataGrid.datagrid('options');
                if ($op.idField === null) {
                    $dataGrid.datagrid('load', dvSearchParam);
                } else {
                    $dataGrid.treegrid('load', dvSearchParam);
                }
                
                lookupCriteriaRefresh_<?php echo $this->metaDataId; ?>();
            }
        });
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("click", 'a.dv-button-inline', function (e) {
            var $this = $(this), $thisClosest = $this.closest('.button-list');
            
            $thisClosest.find('input[type="hidden"]').val($this.attr('data-criteria'));
            $thisClosest.find('.bg-primary-300').removeClass('bg-primary-300').addClass('bg-primary-600');
            $this.removeClass('bg-primary-600').addClass('bg-primary-300');
            
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div#object-value-list-<?php echo $this->metaDataId; ?>").serialize(), 
                workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>', 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
            };
                
            if ($('.div-ganttLayout-<?php echo $this->metaDataId; ?>').is(":visible")) {
                gantt.clearAll();
                gantt.ajax.post({ 
                    url:"Mdwidget/getEvents?metaDataId=<?php echo $this->metaDataId; ?>",
                    method:"POST",
                    data: dvSearchParam
                }).then(function(response){
                    gantt.parse(response.responseText)
                });
                
            } else {

                if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length) {
                    var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                    if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                        var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory], 
                            cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                        dvSearchParam['cardFilterData'] = cardFilterDataVar;
                    }
                }   
                
                <?php
                if (isset($this->row['IS_COUNTCARD_OPEN']) && $this->row['IS_COUNTCARD_OPEN'] == '1' && $this->layoutType !== 'ecommerce') {
                    echo 'dataViewFilterCardViewForm_'.$this->metaDataId.'(this, true);';
                }
                ?>
                
                var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>;
                var $op = $dataGrid.datagrid('options');
                if ($op.idField === null) {
                    $dataGrid.datagrid('load', dvSearchParam);
                } else {
                    $dataGrid.treegrid('load', dvSearchParam);
                }
                
                lookupCriteriaRefresh_<?php echo $this->metaDataId; ?>();
            }
        });

        if ($("#objectDataView_<?php echo $this->metaDataId; ?>").find('.left-sidebar-content').length > 0) {
            var $objectDataView = $("#objectDataView_<?php echo $this->metaDataId; ?>");
            var $leftSidebarContent = $objectDataView.find('.left-sidebar-content'),
                $rightSidebarContent = $objectDataView.find('.right-sidebar-content-for-resize'),
                totalContentWidth = $objectDataView.width();
            <?php if ($this->dataViewCriteriaType !== 'left web' && $this->dataViewCriteriaType !== 'left web civil') { ?>
            if (!$leftSidebarContent.hasClass("ui-resizable") && $().resizable) {
                $leftSidebarContent.resizable({
                    autoHide: true,
                    start: function (event, ui) {
                        $(this).addClass("highliteShape");
                    },
                    resize: function (event, ui) {
                        var sidebarWidth = ui.size.width;
                        var rightSideWidth = totalContentWidth - sidebarWidth - 8;
                        
                        $(event.target).css({'width': sidebarWidth + 'px', 'min-width': sidebarWidth + 'px', 'max-width': sidebarWidth + 'px'});
                        $rightSidebarContent.css({'width': rightSideWidth + 'px', 'min-width': rightSideWidth + 'px', 'max-width': rightSideWidth + 'px'});
                        objectdatagrid_<?php echo $this->metaDataId; ?>.attr('width', rightSideWidth + 'px');
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('resize');
                    }, 
                    stop: function (event, ui) {
                        $(this).removeClass("highliteShape");
                    }
                });
            }
            <?php } ?>
        }
        
        $('#object-value-list-<?php echo $this->metaDataId; ?>').on('change', 'select.quicksearch-combo', function(e){
            var $this = $(this);
            var $parent = $this.closest('.dv-quicksearch');
            var $quickSearchControl = $parent.find('.quicksearch-control');
            var $dataViewElement = $parent.closest('.main-dataview-container');
            var $dataViewElementArr = $dataViewElement.attr('id').split('-');
            var $dataViewId = $dataViewElementArr[3];
            var $rowData = $this.find('option:selected').attr('param');

            $.ajax({
                type: 'post',
                url: 'mdwebservice/responseRenderParamControl',
                data: {dataViewId: $dataViewId, rowData: JSON.parse($rowData)},
                success: function (control) {
                    $quickSearchControl.empty().append(control);
                    $quickSearchControl.find('input').focus();
                    Core.initBPInputType($quickSearchControl);
                }
            });
        });
        
        $('#object-value-list-<?php echo $this->metaDataId; ?>').on('change', '.quicksearch-control select, .quicksearch-control input.popupInit', function(e){
            var $this = $(this), dvSearchParamAddon = '';

            if ($this.val() != '') {
                var paramPath = $this.attr('data-path');
                dvSearchParamAddon = 'param['+paramPath+']='+$this.val()+'&criteriaCondition['+paramPath+']==';
            } 

            dvSearchParamData_<?php echo $this->metaDataId; ?>(dvSearchParamAddon);
        });
        
        $('#object-value-list-<?php echo $this->metaDataId; ?>').on('keydown', '.quicksearch-control input[data-path]', function(e){
            var code = e.keyCode || e.which;
            
            if (code == '13') {
                var $this = $(this), dvSearchParamAddon = '';
                
                if ($this.val() != '') {
                    var paramPath = $this.attr('data-path');
                    dvSearchParamAddon = 'param['+paramPath+']='+$this.val()+'&criteriaCondition['+paramPath+']=like';
                } 
                
                dvSearchParamData_<?php echo $this->metaDataId; ?>(dvSearchParamAddon);
            }
        });
        
        <?php
        if (isset($this->row['subQuery']) && $this->row['subQuery']) {
        ?>
        $('#object-value-list-<?php echo $this->metaDataId; ?>').on('change', 'select.subquery-combo', function(e){
            dvSearchParamData_<?php echo $this->metaDataId; ?>('');
        });
        <?php
        }
        
        if (issetParam($this->dataGridOptionData['GROUPFIELDUSER']) == 'true') {
        ?>
        $('#object-value-list-<?php echo $this->metaDataId; ?>').on('change', 'select.groupfield-combo', function(){
            var groupField = $(this).val();
            var opt = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options');
            
            if (groupField) {
                opt.view = groupview;
                opt.groupField = groupField;
                opt.groupFormatter = function(value, rows) {
                    return <?php echo Format::dataGridGroupFormatter($this->dataGridOptionData['GROUPFORMATTER']); ?>;
                };
            } else {
                opt.groupField = null;
                opt.view = horizonscrollview;
            }
            
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid(opt);
            <?php if ($this->dataGridOptionData['ENABLEFILTER'] == 'true') { ?>
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('disableFilter');
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('enableFilter');
            <?php } ?>
        });
        <?php
        }
        ?>
    
        if (dvIgnoreFirstLoad_<?php echo $this->metaDataId; ?> || '1' == '<?php echo issetParam($this->row['IS_CRITERIA_ALWAYS_OPEN']); ?>') {
            <?php if ($this->dataViewCriteriaType == 'button') { ?>
            $('.search-sidebar-<?php echo $this->metaDataId; ?>').click();        
            <?php } else { ?>        
            var $mandatoryCriteriaForm = $('#object-value-list-<?php echo $this->metaDataId; ?>').find('form#default-mandatory-criteria-form'); 
            if ($mandatoryCriteriaForm.length == 0) {
                $('.object-height-row2-minus-<?php echo $this->metaDataId; ?>').find('> .card > .card-header > .caption.card-collapse').click();
            }
            <?php } ?>    
        }        
        
        $('#object-value-list-<?php echo $this->metaDataId; ?>').on('change', 'input[data-path], select[data-path]', function(e){
            var $this = $(this);
            if ($this.prop('tagName') === 'SELECT') {
                if ($this.val() == '') {
                    $this.parent().find('.select2-container').css('border', '');
                } else {
                    $this.parent().find('.select2-container').css('border', '1px solid #00ace6');
                }
            } else {
                if ($this.val() == '') {
                    $this.css('border', '');
                } else {
                    $this.css('border', '1px solid #00ace6');
                }
            }
        });
        
        setTimeout(function(){
            if ($("#objectDataView_<?php echo $this->metaDataId; ?>").find('.is-open-bp-default-<?php echo $this->metaDataId; ?>').length) {
                var $bpOpenSelector = $("#objectDataView_<?php echo $this->metaDataId; ?>").find('.is-open-bp-default-<?php echo $this->metaDataId; ?>');

                if ($bpOpenSelector.length > 1) {
                    var rowBpDefault = getDataViewSelectedRowsByElement(objectdatagrid_<?php echo $this->metaDataId; ?>);

                    if (rowBpDefault.length) {
                        $("#objectDataView_<?php echo $this->metaDataId; ?>").find('.is-open-bp-default-<?php echo $this->metaDataId; ?>[data-actiontype="update"]:first').trigger('click');
                    } else {
                        $("#objectDataView_<?php echo $this->metaDataId; ?>").find('.is-open-bp-default-<?php echo $this->metaDataId; ?>[data-actiontype="insert"]:first').trigger('click');
                    }

                } else {
                    $bpOpenSelector.trigger('click');
                }
            } 
        }, 1000);

        $(document).on('change', 'select.dataview-select2-<?php echo $this->metaDataId; ?>', function(e) {
            var $this = $(this);
            var currIndex = $this.closest('tr').attr('datagrid-row-index');
            var rows = getRowsDataView('<?php echo $this->metaDataId; ?>');
            var selectedRow = rows[currIndex];    
            selectedRow[$this.closest('td').attr('field')] = $this.val();

            $.ajax({
                type: 'post',
                async: false,
                url: 'mdwebservice/saveComboDataView',
                data: {
                    processId: $this.data('process-id'),
                    selectedRow: selectedRow
                },
                dataType: 'json',
                success: function(data) {
                }
            });
        });
        
        $('#object-value-list-<?php echo $this->metaDataId; ?>').on("click", ".sidebar-secondary-toggle, .sidebar-right-toggle", function() {
            setTimeout(function() {
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('resize');
            }, 1);
        });
        
        var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery-<?php echo $this->metaDataId; ?>:last-child'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });        
        
        $('#criteriaTemplates', windowId_<?php echo $this->metaDataId; ?>).on('change', function(){
            var $this = $(this);
            
            if ($this.hasAttr('data-field-name')) {
                
                $.ajax({
                    type: 'post',
                    url: 'mdobject/getDataviewCriteriaTemplate',
                    data: {metaDataId: '<?php echo $this->metaDataId; ?>', id: $this.val(), viewtype: $this.attr('data-field-name')},
                    dataType: 'html',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {    
                        if (data !== '') {        
                            var $html = $('<div />', {html: data});
                            var $formBody = $(windowId_<?php echo $this->metaDataId; ?>).find('.filter-form-body');
                            $formBody.empty().append($html.find('.filter-form-body').html());                      
                            Core.initDVAjax($formBody);
                        }
                        Core.unblockUI();
                    },
                    error: function() { alert('Error'); }   
                });
                
            } else {
                
                var $lastCriteriaRow = $('#default-criteria-form', windowId_<?php echo $this->metaDataId; ?>);
            
                $.ajax({
                    type: 'post',
                    url: 'mdobject/getDataviewCriteriaTemplate',
                    data: {'metaDataId': '<?php echo $this->metaDataId; ?>', 'id': $(this).val()},
                    dataType: 'html',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {    
                        if (data !== '') {                        
                            $('.dv-criteria-row', windowId_<?php echo $this->metaDataId; ?>).remove();
                            $lastCriteriaRow.prepend(data);                        
                            Core.initDVAjax($lastCriteriaRow);
                        }
                        Core.unblockUI();
                    },
                    error: function() { alert('Error'); }   
                });
            }
        });
        
        $('#criteriaTemplatesEcommerce', windowId_<?php echo $this->metaDataId; ?>).on('change', function(){
            var $lastCriteriaRow = $('#default-criteria-form', windowId_<?php echo $this->metaDataId; ?>);
            
            $.ajax({
                type: 'post',
                url: 'mdobject/getDataviewCriteriaTemplate',
                data: {'metaDataId': '<?php echo $this->metaDataId; ?>', 'id': $(this).val(), 'viewtype': 'ecommerce'},
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {  
                    if (data !== '') { 
                        $('.dv-criteria-row', windowId_<?php echo $this->metaDataId; ?>).remove();
                        $lastCriteriaRow.prepend(data);                        
                        Core.initDVAjax($lastCriteriaRow);
                    }
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }   
            });
        });
        
        $('#criteriaTemplatesLeftWeb', windowId_<?php echo $this->metaDataId; ?>).on('change', function(){
            var $lastCriteriaRow = $('#default-criteria-form', windowId_<?php echo $this->metaDataId; ?>);
            
            $.ajax({
                type: 'post',
                url: 'mdobject/getDataviewCriteriaTemplate',
                data: {'metaDataId': '<?php echo $this->metaDataId; ?>', 'id': $(this).val(), 'viewtype': 'leftweb'},
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {    
                    if (data !== '') {                        
                        $('.dv-criteria-row', windowId_<?php echo $this->metaDataId; ?>).remove();
                        $lastCriteriaRow.prepend(data);                        
                        Core.initDVAjax($lastCriteriaRow);
                    }
                    Core.unblockUI();
                },
                error: function() {
                    alert('Error');
                }   
            });
        });
        
        $('.form-check-input-switchery-<?php echo $this->metaDataId; ?>').on('change', function(){
            if ($(this).is(':checked')) {
                $('.criteria-template-hidden-<?php echo $this->metaDataId ?>').removeClass('hidden');
            } else {
                $('.criteria-template-hidden-<?php echo $this->metaDataId ?>').addClass('hidden');
            }
        });        
        
        $(document.body).on('select2-opening', 'select.select2-criteria-template-<?php echo $this->metaDataId ?>', function(e, isTrigger) {
            var $this = $(this), $relateElement = $this.prev('.select2-container:eq(0)');

            if (!$this.hasClass('data-combo-set')) {
                
                var select2 = $this.data('select2');

                $this.addClass('data-combo-set');
                Core.blockUI({target: $relateElement, animate: false, icon2Only: true});

                var comboDatas = [];
                $.ajax({
                    type: 'post',
                    async: false,
                    url: 'mdobject/getDataviewTemplateData',
                    data: {'metaDataId': '<?php echo $this->metaDataId ?>'},
                    dataType: 'json',
                    success: function(data) {
                        if (Object.keys(data).length) { 
                            $this.empty();
                            $this.append($('<option />').val('').text(plang.get('choose')));  

                            $.each(data, function() {
                                $this.append($("<option />").val(this.ID).text(this.NAME));
                                comboDatas.push({id: this.ID, text: this.NAME});                     
                            });
                        }
                    },
                    error: function () { alert("Ajax Error!"); } 
                }).done(function(){
                    Core.unblockUI($relateElement);
                    $this.select2({results: comboDatas, closeOnSelect: false});
                    if (typeof isTrigger === 'undefined' && !select2.opened()) {
                        $this.select2('open');
                    }
                });
            }
        });
        
        $(document.body).on('click', '.criteria-template-delete-list-<?php echo $this->metaDataId ?>', function(e) {
            var listTemp = '';
            $.ajax({
                type: 'post',
                async: false,
                url: 'mdobject/getDataviewTemplateData',
                data: {'metaDataId': '<?php echo $this->metaDataId ?>'},
                dataType: 'json',
                beforeSend: function () {
                  Core.blockUI({
                    message: "Loading ...",
                    boxed: true,
                  });
                },                
                async: false,
                success: function(data) {
                    if (Object.keys(data).length) {
                        $.each(data, function() {   
                            listTemp += '<div class="d-flex justify-content-between"><div class="my-1 ml-2" style="font-size: 15px;">'+this.NAME+'</div><a data-id="'+this.ID+'" href="javascrip:;" style="color:#ff2444;font-size: 15px;" title="Загвар устгах" class="mt7 ml9 criteria-template-delete-btn-<?php echo $this->metaDataId ?>"><i class="far fa-trash"></i></a></div>';
                        });
                        
                        var $dialogName = "dialog-dv-criteria-template";
                        if (!$("#" + $dialogName).length) {
                          $('<div id="' + $dialogName + '"></div>').appendTo("body");
                        }
                        var $dialog = $("#" + $dialogName);

                        $dialog.empty().append('<div class="mb-1">'+listTemp+'</div>');
                        $dialog.dialog({
                          cache: false,
                          resizable: false,
                          bgiframe: true,
                          autoOpen: false,
                          title: "Хадгалсан загвар",
                          width: 400,
                          height: "auto",
                          modal: true,
                          close: function () {
                            $dialog.empty().dialog("destroy").remove();
                          },
                          buttons: [{
                              text: "Хаах",
                              class: "btn blue-madison btn-sm",
                              click: function () {
                                $dialog.dialog("close");
                              },
                            },
                          ],
                        });
                        $dialog.dialog("open");            
                        Core.unblockUI();
                    } else {
                        new PNotify({
                          title: "Warning",
                          text: 'Загвар хадгалаагүй байна!',
                          type: "warning",
                          sticker: false
                        });                   
                        Core.unblockUI();
                    }
                },
                error: function () { alert("Ajax Error!"); } 
            }).done(function(){
            });
        });
        
        $(document.body).on('click', '.criteria-template-delete-btn-<?php echo $this->metaDataId ?>', function(e) {
            var $dialogNameD = "dialog-dv-criteria-template-delete-confirm";
            if (!$("#" + $dialogNameD).length) {
              $('<div id="' + $dialogNameD + '"></div>').appendTo("body");
            }
            var $dialogD = $("#" + $dialogNameD);
            var $self = $(this);
            var tempId = $self.data('id');

            $dialogD.empty().append('<div class="mb-1">Та устгахдаа итгэлтэй байна уу?</div>');
            $dialogD.dialog({
              cache: false,
              resizable: false,
              bgiframe: true,
              autoOpen: false,
              title: "Confirm",
              width: 400,
              height: "auto",
              modal: true,
              close: function () {
                $dialogD.empty().dialog("destroy").remove();
              },
              buttons: [{
                  text: "Тийм",
                  class: "btn green-meadow btn-sm",
                  click: function () {
                    $.ajax({
                        type: 'post',
                        async: false,
                        url: 'mdobject/deleteDataviewTemplateData',
                        data: {'metaDataId': '<?php echo $this->metaDataId ?>', 'templateId': tempId},
                        dataType: 'json',
                        beforeSend: function () {
                          Core.blockUI({
                            message: "Loading ...",
                            boxed: true,
                          });
                        },                
                        async: false,
                        success: function(data) {
                            new PNotify({
                              title: "Success",
                              text: 'Success',
                              type: "success",
                              sticker: false
                            });             
                            $self.closest('.d-flex').remove();
                            $('select.select2-criteria-template-<?php echo $this->metaDataId ?>').removeClass('data-combo-set');
                            $dialogD.dialog("close");
                            Core.unblockUI();
                        },
                        error: function () { alert("Ajax Error!"); } 
                    }).done(function(){
                    });                                          
                  },
                },
                {
                  text: "Хаах",
                  class: "btn blue-madison btn-sm",
                  click: function () {
                    $dialogD.dialog("close");
                  },
                },
              ],
            });
            $dialogD.dialog("open");       
        });

        var timerFilterHover;
        $(document.body).on('mouseenter', windowId_<?php echo $this->metaDataId; ?> + ' .datagrid-htable .datagrid-filter-c', function() {    
            var $this = $(this);
            timerFilterHover = setTimeout(function() {
                if (!$this.find('.dataview-multivalue-filter').length) {
                    $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() - 15 + 'px');
                    $this.append('<a href="javascript:;" title="Олон утгаар хайх" class="dataview-multivalue-filter"><i class="icon-filter4"></i></a>');
                }
            }, 100);
        });

        $(document.body).on('mouseleave', windowId_<?php echo $this->metaDataId; ?> + ' .datagrid-htable .datagrid-filter-c', function(e) {
            var $this = $(this);
            if (timerFilterHover) {
                clearTimeout(timerFilterHover);
                if ($this.find('.dataview-multivalue-filter').length && !$this.hasClass('dataview-multivalue-filter-sticky')) {
                    $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() + 15 + 'px');
                    $this.find('.dataview-multivalue-filter').remove();
                }
            }
        });         
        
        $(windowId_<?php echo $this->metaDataId; ?>).on('click', '.datagrid-htable .datagrid-filter-c a.dataview-multivalue-filter', function(e){
            var $target = $(this).closest('.datagrid-filter-c');
            dvMultiValueFilter_<?php echo $this->metaDataId; ?>($target);
        });
        
        $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').on('click', function (e) {

            var $selector = $('#tab-lookupdata-<?php echo $this->metaDataId; ?>'),
                $this = $(this),
                $div = $('.div-ecommercelayoutmeta-<?php echo $this->metaDataId; ?>');

            $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').removeClass('active');
            $this.addClass('active');

            if (typeof $this.attr('data-layoutid') !== 'undefined') {
                $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').hide();
                $div.show();
                $selector.hide().empty();
                $this.closest('.topdpbutton').find('.btnsearch').hide();
                var metadataid = $this.data('layoutid'), metaTypeId;

                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/getMetaTypeById/' + metadataid,
                    async: false,
                    success: function (data) {
                        metaTypeId = data;
                    }
                });

                if (metaTypeId == '<?php echo Mdmetadata::$metaGroupMetaTypeId ?>') {
                    if (!$div.find('.main-dataview-container').length) {


                        $.ajax({
                            type: 'post',
                            url: 'mdobject/dataview/' + metadataid,
                            beforeSend: function () {
                                Core.blockUI({
                                    message: 'Loading...',
                                    boxed: true
                                });
                            },
                            success: function (data) {
                                $div.empty().append(data + '<div class="clearfix w-100"/>');
                            }
                        }).done(function () {
                            Core.unblockUI();
                        });
                    }

                } else if (metaTypeId == '<?php echo Mdmetadata::$layoutMetaTypeId ?>') {

                    if (!$div.find('.layout-theme').length) {
                        $.ajax({
                            type: 'post',
                            url: 'mdlayoutrender/index/' + metadataid,
                            beforeSend: function () {
                                Core.blockUI({
                                    message: 'Loading...',
                                    boxed: true
                                });
                            },
                            success: function (data) {
                                var jsonObj = JSON.parse(data);
                                if ('Html' in Object(jsonObj)) {
                                    $div.empty().append(jsonObj.Html + '<div class="clearfix w-100"/>');
                                } else {
                                    $div.empty().append(data + '<div class="clearfix w-100"/>');
                                }
                            }
                        }).done(function () {
                            Core.unblockUI();
                        });
                    }
                }

                return;
            } else {
                $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').show();
                $div.hide();
                $selector.show();
                $this.closest('.topdpbutton').find('.btnsearch').show();
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid("resize");
            }

            $selector.empty().append('<img src="assets/core/global/img/input-spinner.gif" class="ml5"> Loading...');
            
            if ($this.closest('div.package-meta-tab').attr('data-realpack-id')) {
                var $packageId = $this.closest('div.package-meta-tab').attr('data-realpack-id');
                var defaultCriteriaData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
            } else {
                var defaultCriteriaData = null;
                if ($this.closest('.package-meta').attr('data-packageid')) {
                    var $packageId = $this.closest('div.package-meta').attr('data-packageid');
                    defaultCriteriaData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
                } 
                
                if (!defaultCriteriaData) {
                    defaultCriteriaData = $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", 'div#object-value-list-<?php echo $this->metaDataId; ?>').serialize();
                }
            }

            $.ajax({
                type: 'post',
                url: 'mdwebservice/loadTabComboData',
                data: {
                    metas: $this.data('metas'),
                    type: $this.data('type'),
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    fieldPath: $this.data('path'),
                    defaultCriteriaData: defaultCriteriaData
                },
                success: function (data) {
                    $selector.empty().append(data);
                },
                error: function () {
                    alert("Error");
                }
            });
        });
        
        $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').first().click();
      
    });
    
    function dvSearchParamData_<?php echo $this->metaDataId; ?>(dvSearchParamAddon) {

        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
        } else {
            var $packageTab = $("div#object-value-list-<?php echo $this->metaDataId; ?>").closest('div.package-meta-tab');
            if ($packageTab.length) {
                var $packageId = $packageTab.attr('data-realpack-id');
                var defaultCriteriaData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
            } else {
                var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
            }
        }
            
        var dvSearchParam = {
            metaDataId: '<?php echo $this->metaDataId; ?>',
            defaultCriteriaData: defaultCriteriaData + '&' + dvSearchParamAddon, 
            workSpaceId: '<?php echo $this->workSpaceId; ?>', 
            workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
            uriParams: '<?php echo $this->uriParams; ?>', 
            drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>', 
            treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
            filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
            ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
            subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
        };
        if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length) {
            var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val()
            if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory], 
                    cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                dvSearchParam['cardFilterData'] = cardFilterDataVar;
            }
        }   

        var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
            $op = $dataGrid.datagrid('options');
            
        if ($op.idField === null) {
            $dataGrid.datagrid('load', dvSearchParam);
        } else {
            $dataGrid.treegrid('load', dvSearchParam);
        }
    }
    
    function customSearch_<?php echo $this->metaDataId; ?>(e, obj, grdId) {
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

    <?php
    if (!empty($this->layoutTypes)) {
    ?>
    function renderCardView_<?php echo $this->metaDataId; ?>(elem){
        var $this = $(elem), viewType = $this.attr('data-view-type');
        
        if (!$this.hasAttr('data-old-type')) {
            $this.attr('data-old-type', viewType);
        }
        
        var viewOldType = $this.attr('data-old-type');
        var op = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options');
        var listRownumbers = op.rownumbers;
        var listNowrap = op.nowrap;
        var listCheckOnSelect = op.checkOnSelect;
        var listSelectOnCheck = op.selectOnCheck;

        $("#md-map-canvas-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").hide().parent().addClass('hidden');                                        

        if (viewType != 'list') {            
                                                    
            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").removeClass(dv_theme_<?php echo $this->metaDataId; ?>);
            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").addClass('jeasyuiTheme'+viewOldType+'View');

            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid({
                view: window[viewOldType+'view_<?php echo $this->metaDataId; ?>'],
                showHeader: false, 
                showFooter: false, 
                rownumbers: false, 
                nowrap: false, 
                checkOnSelect: false, 
                autoRowHeight: false,
                frozenColumns: [],
                onLoadSuccess: function(row, data) {
                <?php if ($this->dataGridOptionData['ENABLEFILTER'] == 'true') { ?>
                    objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('enableFilter');
                <?php } ?>                    
                }                 
            });

            $this.html('<i class="icon-list"></i>');
            $this.attr('data-view-type', 'list');
            
        } else {

            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").addClass('data-cart-grid-loading');
            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").removeClass('jeasyuiThemecardView jeasyuiThemecard1View');
            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").addClass(dv_theme_<?php echo $this->metaDataId; ?>);
          
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid({
                <?php echo $layoutTypeMainGrid; ?>
                showHeader: true,
                showFooter: <?php echo $this->dataGridOptionData['SHOWFOOTER']; ?>,
                rownumbers: listRownumbers, 
                nowrap: listNowrap, 
                checkOnSelect: true,  
                selectOnCheck: true, 
                remoteFilter: true,
                frozenColumns: <?php echo ((isset($this->dataGridColumnData['freeze'])) ? $this->dataGridColumnData['freeze'] : ''); ?>,
                columns: <?php echo ((isset($this->dataGridColumnData['header'])) ? $this->dataGridColumnData['header'] : ''); ?>,
                onLoadSuccess: function(row, data) {
                    $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").removeClass('data-cart-grid-loading');
                <?php if ($this->dataGridOptionData['ENABLEFILTER'] == 'true') { ?>
                    objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('enableFilter');
                <?php } ?>                    
                }
            });

            $this.html('<i class="icon-grid ecommercegridicon"></i>');
            $this.attr('data-view-type', viewOldType);
        }
    }
    <?php
    }
    ?>
    
    function dataViewExportToExcel_<?php echo $this->metaDataId; ?>() {
        Core.blockUI({message: 'Exporting...', boxed: true});
        
        var getSortFields = getDataGridSortFields($("div#object-value-list-<?php echo $this->metaDataId; ?>"));
        
        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
        } else {
            var $packageTab = $("div#object-value-list-<?php echo $this->metaDataId; ?>").closest('div.package-meta-tab');
            if ($packageTab.length) {
                var $packageId = $packageTab.attr('data-realpack-id');
                var defaultCriteriaData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
            } else {
                var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
            }
        }
        
        var $dvMultiFilterCriteria = $(windowId_<?php echo $this->metaDataId; ?>).find('.multiple_filter_values');
        if ($dvMultiFilterCriteria.length) {
            defaultCriteriaData += '&' + $dvMultiFilterCriteria.find('textarea').serialize();
        }                       
        
        var opt = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options');
        var paginationOpt = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPager').pagination('options');
        var queryParams = opt.queryParams;
        var postParams = {
            metaDataId: '<?php echo $this->metaDataId; ?>',
            defaultCriteriaData: defaultCriteriaData,
            filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
            cardFilterData: $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val() + '=' + $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(),
            workSpaceId: '<?php echo $this->workSpaceId; ?>',
            workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
            uriParams: '<?php echo $this->uriParams; ?>', 
            drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
            subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
            sortFields: getSortFields, 
            total: paginationOpt.total
        };
        
        if (queryParams.hasOwnProperty('drillDownDefaultCriteria')) {
            postParams.drillDownDefaultCriteria = queryParams.drillDownDefaultCriteria;
        }

        if (opt.hasOwnProperty('groupField') && opt.groupField) {
            postParams.groupField = opt.groupField;
        }
        
        if (opt.idField) {
            postParams.treeField = opt.treeField;
        } 
            
        $.fileDownload(URL_APP + 'mdobject/dataViewExcelExport', {
            httpMethod: 'POST',
            data: postParams 
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response){
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                addclass: pnotifyPosition,
                sticker: false
            });
            Core.unblockUI();
        });
    }
    
    function dataViewExportToPrint_<?php echo $this->metaDataId; ?>() {
    
        var $dialogName = 'dialog-dvprint-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        $dialog.empty().append('Та нэмэлт текст бичиж хэвлэхүү?');
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Асуулга',
            width: 370,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        
                    $dialog.dialog('close');
                    
                    var getSortFields = getDataGridSortFields($("div#object-value-list-<?php echo $this->metaDataId; ?>"));

                    if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                        var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
                    } else {
                        var defaultCriteriaData = $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").serialize();
                    }

                    var queryParams = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options').queryParams;
                    var postParams = {
                        metaDataId: '<?php echo $this->metaDataId; ?>',
                        defaultCriteriaData: defaultCriteriaData,
                        filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                        cardFilterData: $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val() + '=' + $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(),
                        workSpaceId: '<?php echo $this->workSpaceId; ?>',
                        workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                        uriParams: '<?php echo $this->uriParams; ?>', 
                        drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
                        subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
                        sortFields: getSortFields, 
                        total: objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getData').total
                    };

                    if (queryParams.hasOwnProperty('drillDownDefaultCriteria')) {
                        postParams['drillDownDefaultCriteria'] = queryParams.drillDownDefaultCriteria;
                    }
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdobject/dataViewPrintPopup',
                        data: postParams,
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            
                            if (data.status == 'success') {
                                
                                var $dialogSubName = 'dialog-dvprint-preview';
                                if (!$("#" + $dialogSubName).length) {
                                    $('<div id="' + $dialogSubName + '"></div>').appendTo('body');
                                }
                                var $dialogSub = $('#' + $dialogSubName);
                                $dialogSub.empty().append('<div class="col-md-12" id="statement-form-<?php echo $this->metaDataId; ?>">'+
                                    '<div class="row">'+
                                        '<div class="col-md-12 statement-preview dialog-no-padding">'+
                                            data.html+
                                        '</div>'+
                                    '</div>'+
                                '</div>');
                                
                                $dialogSub.dialog({
                                    cache: false,
                                    resizable: false,
                                    bgiframe: true,
                                    autoOpen: false,
                                    title: plang.get('print_btn'),
                                    width: 1000,
                                    height: 'auto',
                                    modal: true,
                                    close: function () {
                                        $dialogSub.empty().dialog('destroy').remove();
                                    },
                                    buttons: [
                                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $dialogSub.dialog('close');
                                        }}
                                    ]
                                }).dialogExtend({
                                    "closable": true,
                                    "maximizable": true,
                                    "minimizable": true,
                                    "collapsable": true,
                                    "dblclick": "maximize",
                                    "minimizeLocation": "left",
                                    "icons": {
                                        "close": "ui-icon-circle-close",
                                        "maximize": "ui-icon-extlink",
                                        "minimize": "ui-icon-minus",
                                        "collapse": "ui-icon-triangle-1-s",
                                        "restore": "ui-icon-newwin"
                                    }
                                });
                                $dialogSub.dialogExtend('maximize');
                                $dialogSub.dialog('open');
                            }
                            
                            Core.unblockUI();
                        }
                    });                    
                }},
                {text: plang.get('Шууд хэвлэх'), class: 'btn blue-madison btn-sm', click: function () {
                        
                    $dialog.dialog('close');
                    
                    Core.blockUI({message: 'Printing...', boxed: true});
                    var getSortFields = getDataGridSortFields($("div#object-value-list-<?php echo $this->metaDataId; ?>"));

                    if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                        var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
                    } else {
                        var defaultCriteriaData = $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").serialize();
                    }

                    var queryParams = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('options').queryParams;
                    var postParams = {
                        metaDataId: '<?php echo $this->metaDataId; ?>',
                        defaultCriteriaData: defaultCriteriaData,
                        filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                        cardFilterData: $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val() + '=' + $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(),
                        workSpaceId: '<?php echo $this->workSpaceId; ?>',
                        workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                        uriParams: '<?php echo $this->uriParams; ?>', 
                        drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
                        subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
                        sortFields: getSortFields, 
                        total: objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getData').total
                    };

                    if (queryParams.hasOwnProperty('drillDownDefaultCriteria')) {
                        postParams['drillDownDefaultCriteria'] = queryParams.drillDownDefaultCriteria;
                    }

                    $.ajax({
                        type: 'post',
                        url: 'mdobject/dataViewDirectPrint',
                        data: postParams,
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {    

                            PNotify.removeAll();

                            if (data.status == 'success') {

                                $("body").append('<div id="dataviewprintexportdiv" class="hide">'+data.html+'</div>');  

                                $("div#dataviewprintexportdiv").promise().done(function() {
                                    $("#dataviewprintexportdiv").printThis({
                                        debug: false,             
                                        importCSS: false,           
                                        printContainer: false,      
                                        removeInline: false, 
                                        dataCSS: data.css
                                    });
                                });
                                if ($("body").find("#dataviewprintexportdiv").length > 0) {
                                    $("body").find("#dataviewprintexportdiv").remove();
                                }      

                            } else {
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                            }  

                            Core.unblockUI();
                        },
                        error: function() { alert('Error'); }   
                    });
                    
                }}
            ]
        });
        $dialog.dialog('open');
    }
    
    <?php
    if (isset($this->isExportText) && $this->isExportText) {
    ?>
    function dataViewExportToText_<?php echo $this->metaDataId; ?>() {
        Core.blockUI({message: 'Exporting...', boxed: true});
        
        var getSortFields = getDataGridSortFields($("div#object-value-list-<?php echo $this->metaDataId; ?>"));
        
        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
        } else {
            var defaultCriteriaData = $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").serialize()
        }
            
        $.fileDownload(URL_APP + 'mdobject/dataViewTextExport', {
            httpMethod: 'POST',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: defaultCriteriaData,
                filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                cardFilterData: $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val() + '=' + $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(),
                workSpaceId: '<?php echo $this->workSpaceId; ?>',
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
                sortFields: getSortFields
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response){
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                addclass: pnotifyPosition,
                sticker: false
            });
            Core.unblockUI();
        });
    }
    <?php
    }
    ?>
    
    function dataViewFilterCardViewForm_<?php echo $this->metaDataId; ?>(elem, isSearch) {
        
        var $div = $("div.dataview-search-filter", "#object-value-list-<?php echo $this->metaDataId; ?>");
        
        if ($div.hasClass("display-none") || (!$div.hasClass('display-none') && typeof isSearch !== 'undefined' && isSearch)) {
            
            if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
            } else {
                var defaultCriteriaData = $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").serialize();
            }
        
            $.ajax({
                type: 'post',
                url: 'mdobject/dataViewSearchFilterForm',
                data: {
                    metaDataId: '<?php echo $this->metaDataId; ?>', 
                    defaultCriteriaData: defaultCriteriaData,
                    workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                    workSpaceParams: '<?php echo $this->workSpaceParams; ?>'
                },
                success: function(data) {
                    $div.empty().append(data);
                    $div.removeClass('display-none');
                },
                error: function() {
                    alert("Error");
                }
            });
        } else {
            $div.addClass("display-none");
            if ($("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val() != '') {
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('load', {
                    metaDataId: '<?php echo $this->metaDataId; ?>'
                });
            }
            $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val('');
            $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val('');
        }
    }
    
    function dataViewFilterCardFieldPath_<?php echo $this->metaDataId; ?>(fieldPath, fieldValue, elem) {
        if ($('.div-ganttLayout-<?php echo $this->metaDataId; ?>').is(":visible")) {
            gantt.clearAll();
            gantt.ajax.post({ 
                url:"Mdwidget/getEvents?metaDataId=<?php echo $this->metaDataId; ?>",
                method:"POST",
                data: dvSearchParam
            }).then(function(response){
                gantt.parse(response.responseText)
            });
        } else {
            if ($('#md-map-canvas-<?php echo $this->metaDataId; ?>').is(":visible")) {
                $.ajax({ 
                    type: 'post',
                    url: 'mdobject/googleMapDataGrid',
                    data: {
                        metaDataId: '<?php echo $this->metaDataId; ?>', 
                        defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", windowId_<?php echo $this->metaDataId; ?>).serialize(), 
                        workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                        workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                        uriParams: '<?php echo $this->uriParams; ?>', 
                        drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
                        subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(),
                        cardFilterData: fieldPath + '=' + fieldValue
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({animate: true});
                    },
                    success: function(data) {
                        googleMapInitialze({'result': data, 'metaDataId': '<?php echo $this->metaDataId; ?>'});
                        Core.unblockUI();
                    },
                    error: function() { alert("Error"); }
                });
            } else {
                if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                    var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
                } else {
                    if (typeof elem !== 'undefined') {
                        var $this = $(elem), $packageForm = $this.closest('form[class*="package-criteria-form-"]');
                        if ($packageForm.length) {
                            var defaultCriteriaData = $packageForm.serialize();
                        } else {
                            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
                        }
                    } else {
                        var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
                    }
                }
                
                var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
                    $op = $dataGrid.datagrid('options'); 
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    defaultCriteriaData: defaultCriteriaData,
                    workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                    workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                    treeConfigs: '<?php echo $this->isTreeGridData; ?>'
                };
                
                var $packageTab = $("div#object-value-list-<?php echo $this->metaDataId; ?>").closest('div.package-meta-tab');
    
                if ($(".bp-icon-selection", "#object-value-list-<?php echo $this->metaDataId; ?>").length || $packageTab.length  > 0) {
                    var getPostData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serializeArray();
                    var $packageId = $packageTab.attr('data-realpack-id');
                    if ($packageTab.length > 0) {
                        getPostData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serializeArray();
                    }
    
                    var dvDefaultCriteria = {}, dvDefaultCriteriaCondition = {};       
                    if (getPostData) {
                        for (var fdata = 0; fdata < getPostData.length; fdata++) {
                            var mPath1 = /criteriaCondition\[([\w.]+)\]/g.exec(getPostData[fdata].name);                    
                            if (mPath1 != null) {
                                dvDefaultCriteriaCondition[mPath1[1]] = getPostData[fdata].value;
                            }
                        }        
                        for (var fdata = 0; fdata < getPostData.length; fdata++) {
                            var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                            if(mPath === null) continue;                    
    
                            dvDefaultCriteria[mPath[1]] = [{operator: dvDefaultCriteriaCondition[mPath[1]] ? dvDefaultCriteriaCondition[mPath[1]] : '=', operand:getPostData[fdata].value}];
                        }        
                    }             
                    dvDefaultCriteria[fieldPath] = [{operator: '=', operand: fieldValue}];
    
                    var $iconWrapCombo = $(".bp-icon-selection", "#object-value-list-<?php echo $this->metaDataId; ?>");
                    if ($packageTab.length > 0) {
                        $iconWrapCombo = $('#package-meta-' + $packageId).find(".<?php echo $this->metaDataId; ?>_default_criteria .bp-icon-selection");
                    }
                    
                    var lookUpMetaId = $iconWrapCombo.attr('data-metagroupid');
                    $.ajax({
                        type: 'post',
                        url: 'api/callDataview',
                        data: {dataviewId: lookUpMetaId, criteriaData: dvDefaultCriteria}, 
                        dataType: 'json',
                        success: function(data) {
                            if (data.status === 'success' && data.result[0]) {
                                for (var ici = 0; ici < data.result.length; ici++) {
                                    $iconWrapCombo.find('> li[data-id="'+data.result[ici]['id']+'"]').find('span.badge-pill').attr('title', data.result[ici]['count']).text(data.result[ici]['count']);
                                }
                            }
                        }
                    });     
                }            
                    
                if (fieldPath == 'all' && fieldValue == 'all') {
                    
                    if ($op.idField === null) {
                        $dataGrid.datagrid('load', dvSearchParam);
                    } else {
                        $dataGrid.treegrid('load', dvSearchParam);
                    }
                
                    $("#object-value-list-<?php echo $this->metaDataId; ?>").find('input#cardViewerFieldPath, input#cardViewerValue').val('');
                    
                } else {
                    
                    dvSearchParam['cardFilterData'] = fieldPath + '=' + fieldValue;
                    
                    if ($op.idField === null) {
                        $dataGrid.datagrid('load', dvSearchParam);
                    } else {
                        $dataGrid.treegrid('load', dvSearchParam);
                    }
                    
                    $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val(fieldPath);
                    $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(fieldValue);
                }
            }
        }
    }
    
    function dataViewPrintPreview_<?php echo $this->metaDataId; ?>(mainMetaDataId, isDialog, whereFrom, elem, isOneRow, isbasket) {
        
        setTimeout(function () {        
            if (typeof isOneRow !== 'undefined' && isOneRow) {
                var _datagridRowIndex = $(elem).closest('tr').attr('datagrid-row-index');
                var getRows = getDataViewSelectedRows(mainMetaDataId);
                var rows = [];
                rows[0] = typeof getRows[_datagridRowIndex] === 'undefined' ? getRows[0] : getRows[_datagridRowIndex];
            } else {
                var rows = getDataViewSelectedRows(mainMetaDataId);
                if (typeof isbasket !== 'undefined' && isbasket) {
                    rows = _selectedRows_<?php echo $this->metaDataId; ?>;
                }                
            }
            
            if (rows.length === 0 && typeof isbasket === 'undefined' && !isbasket) {
                alert(plang.get('msg_pls_list_select'));
                return;
            }            
            
            $.ajax({
                type: 'post',
                url: 'mdtemplate/checkCriteria',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>', dataRow: rows, isProcess: false},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(response) {
                    
                    PNotify.removeAll();
                    
                    if (response.hasOwnProperty('status') && response.status != 'success') {
                        Core.unblockUI();
                        new PNotify({
                            title: response.status,
                            text: response.message,
                            type: response.status,
                            addclass: pnotifyPosition,
                            sticker: false
                        });
                        return;
                    }
                    
                    if (typeof response.isSettingsDialog !== 'undefined' && response.isSettingsDialog === '1') {
                        if (typeof response.templateMetaId !== 'undefined' && response.templateMetaId) {
                            var print_options = {
                                numberOfCopies: response.numberOfCopies,
                                isPrintNewPage: response.isPrintNewPage,
                                isSettingsDialog: response.isSettingsDialog,
                                isShowPreview: response.isShowPreview,
                                isPrintPageBottom: response.isPrintPageBottom,
                                isPrintPageRight: response.isPrintPageRight,
                                pageOrientation: response.pageOrientation,
                                isPrintSaveTemplate: response.isPrintSaveTemplate,
                                paperInput: response.paperInput,
                                pageSize: response.pageSize,
                                printType: response.printType,
                                templates: response.templates, 
                                templateMetaId: typeof response.templateMetaId !== 'undefined' ? response.templateMetaId : '', 
                                templateIds: response.templateIds ,
                                marginConfig: typeof response.marginConfig !== 'undefined' ? response.marginConfig : '',
                                top: typeof response.top !== 'undefined' ? response.top : '',
                                left: typeof response.left !== 'undefined' ? response.left : '',
                                bottom: typeof response.bottom !== 'undefined' ? response.bottom : '',
                                right: typeof response.right !== 'undefined' ? response.right : '',
                            }; 
                        } else {
                            var print_options = {
                                numberOfCopies: response.numberOfCopies,
                                isPrintNewPage: response.isPrintNewPage,
                                isSettingsDialog: response.isSettingsDialog,
                                isShowPreview: response.isShowPreview,
                                isPrintPageBottom: response.isPrintPageBottom,
                                isPrintPageRight: response.isPrintPageRight,
                                pageOrientation: response.pageOrientation,
                                isPrintSaveTemplate: response.isPrintSaveTemplate,
                                paperInput: response.paperInput,
                                pageSize: response.pageSize,
                                printType: response.printType,
                                templates: response.templates, 
                                templateIds: response.templateIds,
                                marginConfig: typeof response.marginConfig !== 'undefined' ? response.marginConfig : '',
                                top: typeof response.top !== 'undefined' ? response.top : '',
                                left: typeof response.left !== 'undefined' ? response.left : '',
                                bottom: typeof response.bottom !== 'undefined' ? response.bottom : '',
                                right: typeof response.right !== 'undefined' ? response.right : '',
                            }; 
                        }
                        if (response.numberOfCopies != '' && response.numberOfCopies != '0' && response.templates != null) {
                            callTemplate(rows, '<?php echo $this->metaDataId; ?>', print_options);
                        } else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Тохиргооны мэдээллийг бүрэн бөглөнө үү',
                                type: 'warning',
                                addclass: pnotifyPosition,
                                sticker: false
                            });
                        } 
                        
                    } else {
                    
                        var $dialogName = 'dialog-printSettings';
                        if (!$($dialogName).length) {
                            $('<div id="' + $dialogName + '"></div>').appendTo('body');
                        }
                        var $dialog = $('#' + $dialogName);
                        
                        $dialog.empty().append(response.html);
                        $dialog.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: plang.get('MET_99990001'),
                            width: 500, 
                            minWidth: 400,
                            height: "auto",
                            maxHeight: $(window).height() - 25, 
                            modal: false,
                            open: function(){
                                Core.initDVAjax($dialog);
                            },
                            close: function(){
                                PNotify.removeAll();
                                $dialog.empty().dialog('destroy').remove();
                            },
                            buttons: [
                                {text: plang.get('print_btn'), class: 'btn btn-sm blue bp-btn-print d-none', click: function() {
                                    dataViewReportTemplateExport_<?php echo $this->metaDataId; ?>('preview', $dialog, rows);
                                }},
                                {text: plang.get('pdf_export_btn'), class: 'btn btn-sm blue bp-btn-pdf-export d-none', click: function() {
                                    dataViewReportTemplateExport_<?php echo $this->metaDataId; ?>('pdf', $dialog, rows);
                                }},
                                {text: plang.get('word_export_btn'), class: 'btn btn-sm blue bp-btn-word-export d-none', click: function() {
                                    dataViewReportTemplateExport_<?php echo $this->metaDataId; ?>('word', $dialog, rows);
                                }},
                                {text: plang.get('preview_btn'), class: 'btn btn-sm blue bp-btn-preview', click: function() {
                                    dataViewReportTemplateExport_<?php echo $this->metaDataId; ?>('preview', $dialog, rows);
                                }},
                                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function() {
                                    $dialog.dialog('close');
                                }}
                            ]
                        });
                        if ($dialog.find("#rtTemplateIds").val().length === 0) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Загвараа сонгоно уу!',
                                type: 'warning',
                                sticker: false
                            });                      
                            $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:not(.bp-btn-close)').prop('disabled', true);
                        }
                        $dialog.on('change', '#printTemplate', function(){
                            if ($dialog.find("#printTemplate").val().length === 0) {
                                $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:not(.bp-btn-close)').prop('disabled', true);
                            } else {
                                $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:not(.bp-btn-close)').prop('disabled', false);
                            }
                        });
                        $dialog.dialog('open');
                    }
                    
                    Core.unblockUI();
                }
            });
        }, 100);
    }
    
    function dataViewReportTemplateExport_<?php echo $this->metaDataId; ?>(buttonMode, $dialog, rows) {
        PNotify.removeAll();
        var numberOfCopies = $("#numberOfCopies").val(),
            isPrintNewPage = $("#isPrintNewPage").is(':checked') ? '1' : '0',
            isSettingsDialog = $("#isSettingsDialog").is(':checked') ? '1' : '0',
            isShowPreview = $("#isShowPreview").is(':checked') ? '1' : '0',
            isPrintPageBottom = $("#isPrintPageBottom").is(':checked') ? '1' : '0',
            isPrintPageRight = $("#isPrintPageRight").is(':checked') ? '1' : '0',
            isPrintSaveTemplate = $("#isPrintSaveTemplate").is(':checked') ? '1' : '0',
            pageOrientation = $("#pageOrientation").val(),
            paperInput = $("#paperInput").val(),
            pageSize = $("#pageSize").val(),
            templates = $("#printTemplate").val(),
            templateIds = $("#rtTemplateIds").val(), 
            templateMetaIds = $("#templateMetaIds").val(),
            printType = $("#printType").val();
        var print_options = {
            numberOfCopies: numberOfCopies,
            isPrintNewPage: isPrintNewPage,
            isSettingsDialog: isSettingsDialog,
            isShowPreview: isShowPreview,
            isPrintPageBottom: isPrintPageBottom,
            isPrintPageRight: isPrintPageRight,
            pageOrientation: pageOrientation,
            isPrintSaveTemplate: isPrintSaveTemplate,
            paperInput: paperInput,
            pageSize: pageSize,
            printType: printType,
            templates: templates, 
            templateIds: templateIds, 
            templateMetaIds: templateMetaIds 
        }; 
        if (numberOfCopies != '' && numberOfCopies != '0' && templateIds) {
            if (print_options.templates == '') {
                new PNotify({
                    title: 'Warning',
                    text: 'Загвараа сонгоно уу!',
                    type: 'warning',
                    sticker: false
                });  
                return;              
            }
            $dialog.dialog('close');
            
            if (buttonMode == 'pdf' || buttonMode == 'word') {
                print_options.exportMode = buttonMode;
            }
            
            callTemplate(rows, '<?php echo $this->metaDataId;?>', print_options);
        } else {
            new PNotify({
                title: 'Warning',
                text: plang.getDefault('PRINT_0019', 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү'),
                type: 'warning',
                addclass: pnotifyPosition,
                sticker: false
            });
        }
    }
    
    function dataViewStatementPreview_<?php echo $this->metaDataId; ?>(mainMetaDataId, isDialog, whereFrom, elem) {
        var $dialogName = 'dialog-printSettings';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mdstatement/checkCriteria',
            data: {metaDataId: '<?php echo $this->metaDataId; ?>', isProcess: false},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(response) {
                $dialog.empty().append(response.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Хэвлэх тохиргоо',
                    width: 500, 
                    minWidth: 400,
                    height: "auto",
                    modal: false,
                    close: function(){
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('print_btn'), class: 'btn btn-sm blue', click: function() {
                            var numberOfCopies = $("#numberOfCopies").val();
                            var isPrintNewPage = $("#isPrintNewPage").val();
                            var isShowPreview = $("#isShowPreview").val();
                            var statementMetaDataId = $("#printStatement").val();

                            var defaultCriteriaData = $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").serialize();
                            defaultCriteriaData = defaultCriteriaData + getDataViewFilterRules('<?php echo $this->metaDataId; ?>', true);
                            defaultCriteriaData = defaultCriteriaData + '&dataViewId=<?php echo $this->metaDataId; ?>&statementId='+statementMetaDataId;
                            defaultCriteriaData += '&print_options[numberOfCopies]='+numberOfCopies+'&print_options[isPrintNewPage]='+isPrintNewPage;
                            defaultCriteriaData += '&print_options[isShowPreview]='+isShowPreview+'&print_options[statementId]='+statementMetaDataId;
                            defaultCriteriaData += '&print_options[metaDataId]=<?php echo $this->metaDataId; ?>&print_options[dataViewId]=<?php echo $this->metaDataId; ?>';

                            var print_options = {
                                numberOfCopies: numberOfCopies,
                                isPrintNewPage: isPrintNewPage,
                                isShowPreview: isShowPreview,
                                statementId: statementMetaDataId
                            }; 
                            if (numberOfCopies != '' && numberOfCopies != '0' && statementMetaDataId != null && (statementMetaDataId).length != 0){
                                $dialog.dialog('close');
                                callStatement_<?php echo $this->metaDataId; ?>(defaultCriteriaData, '<?php echo $this->metaDataId; ?>', print_options);
                            } else {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү',
                                    type: 'warning',
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        }).done(function() {
            Core.initDVAjax($dialog);
        });
    }
    
    function callStatement_<?php echo $this->metaDataId; ?>(defaultCriteriaData, metadataId, print_options) {
        var $dialogName = 'dialog-printOption';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        
        $.ajax({
            type: 'post',
            url: 'mdstatement/printOption',
            data: defaultCriteriaData,
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if (print_options.isShowPreview === '1') {  
                    $("#" + $dialogName).empty().append(data.Html);
                    $("#" + $dialogName).dialog({
                        appendTo: "body",
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: 900,
                        minWidth: 900,
                        height: 800,
                        modal: false,
                        close: function() {
                            $("#" + $dialogName).empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $("#" + $dialogName).dialog('close');
                            }}
                        ]
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize", 
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });
                    $("#" + $dialogName).dialog('open');
                    $("#" + $dialogName).dialogExtend("maximize");
                } else {
                    var copies = print_options.numberOfCopies
                    var isNewPage = print_options.isPrintNewPage;
                    $.each(data.Html, function( key, value ) {
                        $("body").append('<page size="A4" class="hide"><div id="externalContent">'+value+'</div></page>');             
                    });
                    $("body").append('<div id="contentRepeat" class="hide"></div>');                
                    if (copies >= 1) {
                        $("page").each(function(j) {
                            for (var i = 0; i < copies; i++) {
                                if(isNewPage == '1'){
                                    $("#contentRepeat").append($(this).find("#externalContent").get(0).outerHTML);
                                    $("#contentRepeat").find("#externalContent").attr('style', 'page-break-after: always;');
                                } else {
                                    $("#contentRepeat").append($(this).find("#externalContent").get(0).outerHTML);
                                }
                            }
                            $("#contentRepeat").find("#externalContent").last().attr('style', 'page-break-after: always;');
                        });
                        $("div#contentRepeat").find("#externalContent").last().removeAttr('style');
                        $("div#contentRepeat").promise().done(function() {
                            $("#contentRepeat").printThis({
                                debug: false,             
                                importCSS: true,           
                                printContainer: false,      
                                loadCSS: URL_APP+"assets/custom/css/print/print.css",
                                removeInline: false        
                            });
                        });
                    }
                    if ($("body").find("#contentRepeat").length > 0) {
                        $("body").find("#contentRepeat").remove();
                        $("body").find("page").remove();
                    }
                }
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        }).done(function() {
            Core.initDVAjax($("#" + $dialogName));
        });
    }

    function drawTree_<?php echo $this->metaDataId; ?>() {

        $('#treeContainer', windowId_<?php echo $this->metaDataId; ?>).html('<div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>');
        
        var dataViewStructureTreeView_<?php echo $this->metaDataId; ?> = $('div#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>);
        var dataViewId = '<?php echo $this->metaDataId; ?>';
        var metaDataId = '';
        if ($('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val() > 0) {
            metaDataId = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
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
            selectDataViewByCategory_<?php echo $this->metaDataId; ?>(nid);
        }).bind('loaded.jstree', function (e, data) {
            
            setTimeout(function(){
                var $jstreeOpen = dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('.jstree-open');
                var $jstreeClicked = dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('.jstree-clicked');
                
                if ($jstreeOpen.length) {
                    $('.dynamic-heigth-<?php echo $this->metaDataId; ?>').animate({
                        scrollTop: Number($jstreeOpen.offset().top) - 200
                    }, 1000);
                }
                
                if ($jstreeClicked.length) {
                    $jstreeClicked.focus();
                    $jstreeClicked.trigger('click');
                }
            }, 1);
        });
    }

    function selectDataViewByCategory_<?php echo $this->metaDataId; ?>(folderId) {
        
        var dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
            op = dataGrid.datagrid('options');
        
        var dvSearchParam = {
            metaDataId: '<?php echo $this->metaDataId; ?>',
            defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div#object-value-list-<?php echo $this->metaDataId; ?>").serialize(), 
            workSpaceId: '<?php echo $this->workSpaceId; ?>', 
            workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
            uriParams: '<?php echo $this->uriParams; ?>', 
            drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>', 
            treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
            ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
            subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
        }; 
            
        if (folderId == 'all') {
            
            if (op.idField === null) {
                dataGrid.datagrid('load', dvSearchParam);
            } else {
                dataGrid.treegrid('load', dvSearchParam);
            }
            
            $("input#cardViewerFieldPath, input#cardViewerValue, input#treeFolderValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val('');
            
        } else {
            
            var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
            var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
            
            dvSearchParam['cardFilterData'] = filtedField + '=' + folderId;
            
            if (op.idField === null) {
                dataGrid.datagrid('load', dvSearchParam);
            } else {
                dataGrid.treegrid('load', dvSearchParam);
            }

            $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val(filtedField);
            $("input#cardViewerValue, input#treeFolderValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(folderId);
        }
    }

    function dataViewAdvancedConfig_<?php echo $this->metaDataId; ?>(elem) {
        PNotify.removeAll();
        var metaDataId = '<?php echo $this->metaDataId; ?>';

        $.ajax({
            type: 'post',
            url: 'mdobject/dataViewAdvancedConfigForm',
            data: {metaDataId: metaDataId},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                var $dialogName = 'dataViewAdvancedConfig-dialog-' + metaDataId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
        
                var buttons = [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {
                        $("#advancedConfig-form-" + metaDataId).ajaxSubmit({
                            type: 'post',
                            url: 'mdobject/saveMetaGroupConfigUser',
                            dataType: 'json',
                            beforeSend: function() {
                                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                            },
                            success: function(data) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                                    
                                if (data.status === 'success') { 
                                    <?php if (!isset($this->drillDownPopupWindow)) { ?>
                                        dataViewer_<?php echo $this->metaDataId; ?>($("a[data-value='"+ data.objectValueViewType +"']", objectWindow_<?php echo $this->metaDataId; ?>), data.objectValueViewType, metaDataId);
                                    <?php } else { ?>
                                        objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('reload');
                                    <?php } ?>

                                    $dialog.dialog('close');
                                } 
                                
                                Core.unblockUI();
                            }
                        });
                    }},
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function() {
                        $dialog.dialog('close');
                    }}
                ];
                
                if (data.hasOwnProperty('isUserConfig') && data.isUserConfig) {
                    buttons.splice(0, 0, {
                        text: plang.get('dv_config_reset'),
                        class: 'btn btn-sm btn-info float-left',
                        click: function() {
                            
                            var $confirmDialogName = 'dialog-adv-config-confirm';
                            if (!$("#" + $confirmDialogName).length) {
                                $('<div id="' + $confirmDialogName + '"></div>').appendTo('body');
                            }
                            var $confirmDialog = $('#' + $confirmDialogName);

                            $confirmDialog.empty().append(plang.get('dv_config_reset_approve'));  
                            $confirmDialog.dialog({
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('msg_title_confirm'),
                                width: 350,
                                height: 'auto',
                                modal: true,
                                buttons: [
                                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {

                                        $.ajax({
                                            type: 'post', 
                                            url: 'mdobject/resetDataViewUserConfig', 
                                            data: {metaDataId: metaDataId}, 
                                            dataType: 'json',
                                            success: function(dataSub) {

                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: dataSub.status,
                                                    text: dataSub.message, 
                                                    type: dataSub.status,
                                                    sticker: false
                                                });
                                                    
                                                if (dataSub.status == 'success') {
                                                    <?php if (!isset($this->drillDownPopupWindow)) { ?>
                                                        dataViewer_<?php echo $this->metaDataId; ?>($("a[data-value='"+ data.objectValueViewType +"']", objectWindow_<?php echo $this->metaDataId; ?>), data.objectValueViewType, metaDataId);
                                                    <?php } else { ?>
                                                        objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('reload');
                                                    <?php } ?>
                                        
                                                    $confirmDialog.dialog('close');
                                                    $dialog.dialog('close');
                                                } 
                                            }
                                        });
                                    }}, 
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $confirmDialog.dialog('close');
                                    }}
                                ]
                            });
                            $confirmDialog.dialog('open');
                        }
                    });
                }
        
                $dialog.append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('META_00112'),
                    width: 'auto',
                    height: 'auto',
                    modal: true, 
                    closeOnEscape: isCloseOnEscape, 
                    close: function() {
                        $dialog.dialog('destroy').remove();
                    },
                    buttons: buttons
                });
                $dialog.dialog('open');
                Core.initDVAjax($dialog);
                
                Core.unblockUI();
            },
            error: function() { alert('Error'); }
        });
    }

    function dataViewHelp_<?php echo $this->metaDataId; ?>(elem) {
        var metaDataId = '';
        var $dialogName = 'dataViewHelp';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdobject/dataViewHelp',
            data: {
                metaDataId: metaDataId
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Тусламж',
                    width: '1000',
                    height: "700",
                    modal: true, 
                    closeOnEscape: isCloseOnEscape, 
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        }).done(function() {
            Core.initDVAjax($dialog);
        });
    }
    
    function dataViewUseBasketView_<?php echo $this->metaDataId; ?>(elem) {
        
        PNotify.removeAll();
        var selectedRows = _selectedRows_<?php echo $this->metaDataId; ?>;
        
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
        
        <?php
        if ($this->layoutType == 'ecommerce_basket') {
        ?>
            
        var metaDataId = '<?php echo $this->metaDataId; ?>';
        var $dialogName = 'dataViewBasket-dialog-' + metaDataId;
        var $dialog = $("#" + $dialogName);

        if ($dialog.is(':visible')) {
            return;
        }

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $dialog = $("#" + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdobject/dataViewUseBasketView',
            data: {metaDataId: metaDataId, selectedRows: _selectedRows_<?php echo $this->metaDataId; ?>},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {  
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    appendTo: '#object-value-list-<?php echo $this->metaDataId; ?>', 
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('basket'),
                    width: '420',
                    height: $(window).height() - 28,
                    position: {my: 'right', at: 'right top', of: window},                          
                    modal: false,                  
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    }
                });
                $dialog.dialog('open');

                Core.unblockUI();
            },
            error: function() { alert("Error"); Core.unblockUI(); }
        });
        
        <?php
        } else {
        ?>    
            
        var metaDataId = '<?php echo $this->metaDataId; ?>';
        var $dialogName = 'dataViewBasket-dialog-' + metaDataId;
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdobject/dataViewUseBasketView',
            data: {metaDataId: metaDataId, selectedRows: selectedRows},
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
        
        <?php
        }
        ?>
    }
    
    function googleMapBtnByDataView_<?php echo $this->metaDataId; ?>() {
        if (dataGridTypeBtn_<?php echo $this->metaDataId; ?> == 'datagrid') {
            $(".datagrid", "#object-value-list-<?php echo $this->metaDataId; ?>").hide();
            $("#md-map-canvas-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").css('height', $(window).height() - 175);
            $("#md-map-canvas-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").show().parent().removeClass('hidden');
            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").parent().parent().addClass('hidden');
            dataGridTypeBtn_<?php echo $this->metaDataId; ?> = 'googlemap';
            googleMapByDataView_<?php echo $this->metaDataId; ?>();
        } else {
            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").show();
            $(".datagrid", "#object-value-list-<?php echo $this->metaDataId; ?>").show();
            $("#md-map-canvas-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").hide().parent().addClass('hidden');
            $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").parent().parent().removeClass('hidden');
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid("resize");
            dataGridTypeBtn_<?php echo $this->metaDataId; ?> = 'datagrid';
        }
    }

    function googleMapByDataView_<?php echo $this->metaDataId; ?>() {
        $.ajax({ 
            type: 'post',
            url: 'mdobject/googleMapDataGrid',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>', 
                defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", windowId_<?php echo $this->metaDataId; ?>).serialize(), 
                workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
                subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function(data) {
                googleMapInitialze({'result': data, 'metaDataId': '<?php echo $this->metaDataId; ?>'});
                Core.unblockUI();
            },
            error: function() { alert("Error"); }
        });
    }
    
    function callLayoutDataView_<?php echo $this->metaDataId; ?>(metaLayoutLinkId, element) {
        $.ajax({
            type: 'post',
            url: 'mdlayoutrender/index/' + metaLayoutLinkId,
            data: {metaDataId: '<?php echo $this->metaDataId; ?>', metaLayoutLinkId: metaLayoutLinkId},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function(data) {
                $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').hide();
                $('.div-dataGridLayout-<?php echo $this->metaDataId; ?>').append('<div class="objectLayout-<?php echo $this->metaDataId; ?>">'+ data.Html+ '</div>');
                $('.callLayoutDataView_<?php echo $this->metaDataId; ?>').hide();
                $('.callDataView_<?php echo $this->metaDataId; ?>').show();
                Core.unblockUI();
            },
            error: function() { alert("Error"); }
        });
    }
    
    function callGanttView_<?php echo $this->metaDataId; ?>(metaDataId, element) {
        if ($('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').is(":visible")){
            $('div[id="objectDataView_<?php echo $this->metaDataId; ?>"]').find('.render-process-page').hide();
            $(element).find('i').removeClass('icon-stats-bars').addClass('icon-list');
            $(element).attr('title', plang.get('list'));
            $.ajax({
                type: 'post',
                url: 'Mdwidget/gantt',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                dataType: "json",
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').hide();
                    $('.div-ganttLayout-<?php echo $this->metaDataId; ?>').empty().append(data.Html).show();
                    Core.unblockUI();
                },
                error: function() { alert("Error"); }
            });
        } else {
            $('div[id="objectDataView_<?php echo $this->metaDataId; ?>"]').find('.render-process-page').show();
            $(element).find('i').removeClass('icon-list').addClass('icon-stats-bars');
            $(element).attr('title', plang.get('Gantt'));
            
            $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').show();
            $('.div-ganttLayout-<?php echo $this->metaDataId; ?>').hide();
            dataViewReload(<?php echo $this->metaDataId; ?>);
        }
    }
    
    function callDataView_<?php echo $this->metaDataId; ?>(metaDataId, element) {
        $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').show();
        $('.objectLayout-<?php echo $this->metaDataId; ?>').remove();
        $('.callLayoutDataView_<?php echo $this->metaDataId; ?>').show();
        $('.callDataView_<?php echo $this->metaDataId; ?>').hide();
    }
    
    function objectDataView_<?php echo $this->metaDataId; ?>() {
        $("#objectDataView_<?php echo $this->metaDataId; ?>").show();
        $("#objectDashboardView_<?php echo $this->metaDataId; ?>").hide();
        $("#objectReportTemplateView_<?php echo $this->metaDataId; ?>").hide();
        $(".viewer-dashboard-<?php echo $this->metaDataId; ?>").remove();
    }
    
    function objectDashboardView_<?php echo $this->metaDataId; ?>() {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mddashboard/dashboardValueViewer',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>'
            },
            beforeSend: function() {
                Core.blockUI({animate: true});
                $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/css/fileexplorer.css"/>');
                $.getScript("assets/custom/addon/plugins/amcharts/amcharts/amcharts.js");
                $.getScript("assets/custom/addon/plugins/amcharts/amcharts/serial.js");
                $.getScript("assets/custom/addon/plugins/amcharts/amcharts/gauge.js");
                $.getScript("assets/custom/addon/plugins/amcharts/amcharts/pie.js");
                $.getScript("assets/custom/addon/plugins/amcharts/amcharts/funnel.js");
                $.getScript("assets/custom/addon/plugins/amcharts/amcharts/themes/light.js");
                
                $.getScript("assets/custom/addon/plugins/highstock/js/highstock.js");
                $.getScript("assets/custom/addon/plugins/highstock/js/modules/exporting.js");
                $.getScript("middleware/assets/js/dashboard/charts_amcharts.js");
            },
            success: function(data) {
                if (data.status === 'success') {
                    $("#objectDataView_<?php echo $this->metaDataId; ?>").hide();
                    $("#objectReportTemplateView_<?php echo $this->metaDataId; ?>").hide();
                    $("#objectDashboardView_<?php echo $this->metaDataId; ?>").show();
                    $("#objectDashboardView_<?php echo $this->metaDataId; ?>").html('<div class="viewer-dashboard-<?php echo $this->metaDataId; ?>">'+ data.Html +'</div>');
//                    $(".viewer-dashboard-<?php echo $this->metaDataId; ?>").find('.remove-type-<?php echo $this->metaDataId; ?>').hide();
                }
                Core.unblockUI();
            }
        }).done(function() {
            Core.initAjax($("#objectDashboardView_<?php echo $this->metaDataId; ?>"));
        });
    }
    
    function objectReportTemplateView_<?php echo $this->metaDataId; ?>() {
        
        $.getScript(URL_APP+'middleware/assets/js/mdtemplate.js', function(){
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'mdtemplate/reportTemplateViewer',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                beforeSend: function() {
                    $("link[href='assets/custom/css/fileexplorer.css']").remove();
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/css/fileexplorer.css"/>');
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    if (data.status === 'success') {
                        $("#objectDataView_<?php echo $this->metaDataId; ?>").hide();
                        $("#objectDashboardView_<?php echo $this->metaDataId; ?>").hide();
                        $("#objectReportTemplateView_<?php echo $this->metaDataId; ?>").show();
                        $("#objectReportTemplateView_<?php echo $this->metaDataId; ?>").html('<div class="viewer-report-template-<?php echo $this->metaDataId; ?>">'+ data.html +'</div>');
                    }
                    Core.unblockUI();
                }
            });
        });
    }

    function detailRightSidebar_<?php echo $this->metaDataId; ?>() {
        var rowData = getDataViewSelectedRows(<?php echo $this->metaDataId; ?>);
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
                    var _thisToggler = $(".stoggler", windowId_<?php echo $this->metaDataId; ?>);
                    var centersidebar = $(".center-sidebar", windowId_<?php echo $this->metaDataId; ?>);
                    var rightsidebar = $(".right-sidebar", windowId_<?php echo $this->metaDataId; ?>);
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
                error: function () { alert("Error"); }
            }).done(function(){
                Core.initFancybox($('.detail-sidebar-<?php echo $this->metaDataId; ?>'));
            });
        }
    }
    
    function changeWfmStatusByRow_<?php echo $this->metaDataId ?>(elem) {
        
        var $this = $(elem);
        var row = getDataViewSelectedRowByIndex('<?php echo $this->metaDataId ?>', elem);

        $.ajax({
            type: 'post',
            url: 'mdobject/getWorkflowNextStatus',
            data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(response) {
                if (response.status === 'success') {
                    
                    var $dropdownMenu = $this.next('ul.dropdown-menu:eq(0)');
                    
                    $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
                    $dropdownMenu.empty();
                    
                    if (response.datastatus) {
                        var rowId = '';
                        if (typeof row.id !== 'undefined') {
                            rowId = row.id;
                        }
                        $.each(response.data, function (i, v) {
                            var advancedCriteria = '';
                            if (typeof v.advancedCriteria !== 'undefined' && v.advancedCriteria !== null) {
                                advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                            }

                            if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a></li>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                    } else if (v.wfmisneedsign == '3') {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                    } else {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a></li>'); 
                                    }
                                } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    if (v.wfmisneedsign == '1') {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                    } else if (v.wfmisneedsign == '2') {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                    } else {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+'</a></li>');
                                    }
                                }    
                            }
                        });    
                    } 
                    
                    if (!isIgnoreWfmHistory_<?php echo $this->metaDataId; ?>) {
                        $dropdownMenu.append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\');">'+plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
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
            error: function() {
                alert("Error");
            }
        });
    }
    
    function dataViewToBasket_<?php echo $this->metaDataId ?>(elem) {
        var rows = window['objectdatagrid_<?php echo $this->metaDataId ?>'].datagrid('getSelections');
        
        if (rows.length === 0) {
            alert(plang.get('msg_pls_list_select'));
            return;
        }
        
        var isAdded = false,
            isGlConnected = false; 
    
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
        } elseif (isset($this->row['idField'])) {
            $primaryField = $this->row['idField'];
        } else {
            $primaryField = 'id';
        }                   
        ?>
        
        for (var key in rows) {
            var row = rows[key], rowId = row['<?php echo $primaryField; ?>'], isAddedChild = false;             
            
            for (var key in _selectedRows_<?php echo $this->metaDataId; ?>) {
                var basketRow = _selectedRows_<?php echo $this->metaDataId; ?>[key], childId = basketRow['<?php echo $primaryField; ?>'];

                if (rowId == childId) {
                    isAddedChild = true;
                    break;
                } 
            }    
            
            if (!isAddedChild) {
                isAdded = true; 
                row.basketqty = 1;
                _selectedRows_<?php echo $this->metaDataId; ?>.push(row);
            }
        }

        if (isAdded) {
            $('.save-database-<?php echo $this->metaDataId; ?>').text(_selectedRows_<?php echo $this->metaDataId; ?>.length).pulsate({
                color: '#F3565D', 
                reach: 9,
                speed: 500,
                glow: false, 
                repeat: 1
            });   
        } else {
            $('.save-database-<?php echo $this->metaDataId; ?>').pulsate({
                color: '#4caf50', 
                reach: 9,
                speed: 500,
                glow: false, 
                repeat: 1
            });   
        }
        
        return;
    }
    
    function runUpdateInlineEditDataView_<?php echo $this->metaDataId; ?>(row, actionType) {
        $.ajax({
            type: 'post',
            url: 'mdobject/dataViewInlineEditProcess',
            data: {metaDataId: '<?php echo $this->metaDataId; ?>', actionType: actionType, row: row},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function(data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
                Core.unblockUI();
                if (data.status === 'success') {
                    objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('reload');
                }
            },
            error: function() {
                alert("Error");
                Core.unblockUI();
            }
        });
    }    
    
    function insertRowInlineEditDataView_<?php echo $this->metaDataId; ?>(elem) {
        var index = 0;
        objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('insertRow', {index: index, row: {ck:''}});
        objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('beginEdit', index);
    }
    
    function deleteRowInlineEditDataView_<?php echo $this->metaDataId; ?>(elem) {
        var _thisGrid = objectdatagrid_<?php echo $this->metaDataId; ?>;
        var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');        
        var $bodytr = $panelView.find(".datagrid-view2").find('.datagrid-body > .datagrid-btable > tbody > tr.datagrid-row-editing'),
            $thisGrid, trIndex;
        
        if ($bodytr.length) {
            $bodytr.each(function(){
                $thisGrid = $(this);
                if ($thisGrid.hasClass('datagrid-row-selected')) {
                    trIndex = parseInt($thisGrid.attr('datagrid-row-index'), 10);
                    objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('deleteRow', trIndex);
                }
            });
        }
    }
    
    function saveRowInlineEditDataView_<?php echo $this->metaDataId; ?>(elem) {
        var _thisGrid = objectdatagrid_<?php echo $this->metaDataId; ?>;
        var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
        var $bodytr = $panelView.find(".datagrid-view2").find('.datagrid-body > .datagrid-btable > tbody > tr.datagrid-row-editing'),
            $thisGrid, trIndex;
        
        if ($bodytr.length) {
            $bodytr.each(function(){
                $thisGrid = $(this);
                trIndex = parseInt($thisGrid.attr('datagrid-row-index'), 10);
                objectdatagrid_<?php echo $this->metaDataId; ?>.<?php echo $this->isGridType; ?>('endEdit', trIndex);
            });
        }
    }
    
    function renderContextMenuDv_<?php echo $this->metaDataId; ?>(wfmActions, rows) {
        
        $.contextMenu({
            selector: "div#object-value-list-<?php echo $this->metaDataId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->metaDataId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
            build: function($trigger, e) {
                var selectedRows = getDataViewSelectedRows('<?php echo $this->metaDataId; ?>');
                var rows = selectedRows[0];
                
                var contextMenuData = {
                    <?php 
                    $commandContextArray = Arr::sortBy('ORDER_NUM', $this->dataViewProcessCommand['commandContext'], 'asc');
                    $contentMenuRender = array();
                    
                    foreach ($commandContextArray as $cm => $row) {
                        
                        $contextMenuIcon = str_replace('fa-', '', $row['ICON_NAME']);
                        
                        if (isset($row['STANDART_ACTION'])) {
                            
                            if ($row['STANDART_ACTION'] == 'criteria') {
                                
                                $contentMenuRender[] = '"' . $cm . '": {';
                                    $contentMenuRender[] = 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", ';
                                    $contentMenuRender[] = 'icon: "' . $contextMenuIcon . '", ';

                                    if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                        $contentMenuRender[] = '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                    }

                                    $contentMenuRender[] = 'callback: function(key, options) {';
                                        $contentMenuRender[] = 'transferProcessCriteria(\'' . $this->metaDataId . '\', \'' . $row['BATCH_NUMBER'] . '\', \'context\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'});';
                                    $contentMenuRender[] = '}';
                                $contentMenuRender[] = '},';
                                
                            } elseif ($row['STANDART_ACTION'] == 'processCriteria') {
                                
                                $contentMenuRender[] = '"' . $cm . '": {';
                                    $contentMenuRender[] = 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", ';
                                    $contentMenuRender[] = 'icon: "' . $contextMenuIcon . '", ';
                                        
                                    if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                        $contentMenuRender[] = '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                    }
                                
                                    $contentMenuRender[] = 'callback: function(key, options) {';
                                
                                    if ($row['ADVANCED_CRITERIA'] != '') {
                                        $contentMenuRender[] = '_dvAdvancedCriteria = "'.$row['ADVANCED_CRITERIA'].'";';
                                    }
                                        $contentMenuRender[] = 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');';
                                    $contentMenuRender[] = '}';
                                    
                                $contentMenuRender[] = '},';
                                
                            } else {
                                
                                $contentMenuRender[] = '"' . $cm. '": {';
                                    $contentMenuRender[] = 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", ';
                                    $contentMenuRender[] = 'icon: "' . $contextMenuIcon . '", ';

                                    if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                        $contentMenuRender[] = '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                    }

                                    $contentMenuRender[] = 'callback: function(key, options) {';
                                        $contentMenuRender[] = 'transferProcessAction(\'\', \'' . $this->metaDataId . '\', \'' . $row['STANDART_ACTION'] . '\', \'' . Mdmetadata::$businessProcessMetaTypeId . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');';
                                    $contentMenuRender[] = '}';
                                $contentMenuRender[] = '},';
                            }
                            
                        } else {
                            
                            $contentMenuRender[] = '"' . $cm. '": {';
                                $contentMenuRender[] = 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", ';
                                $contentMenuRender[] = 'icon: "' . $contextMenuIcon . '", ';
                                        
                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    $contentMenuRender[] = '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }
                                
                                $contentMenuRender[] = 'callback: function(key, options) {';
                                    $contentMenuRender[] = 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');';
                                $contentMenuRender[] = '}';
                            $contentMenuRender[] = '},';
                        }
                    }
                    
                    if ($this->isPrint) { 
                        $contentMenuRender[] = '"9999999": {';
                            $contentMenuRender[] = 'name: "' . $this->lang->line('printTemplate') . '", ';
                            $contentMenuRender[] = 'icon: "print", ';
                            $contentMenuRender[] = 'callback: function(key, options) {';
                                $contentMenuRender[] = 'dataViewPrintPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);';
                            $contentMenuRender[] = '}';
                        $contentMenuRender[] = '},';
                    }
                    
                    echo implode('', $contentMenuRender);
                    ?>
                };
                
                if ($('.div-objectdatagrid-<?php echo $this->metaDataId ?>').hasClass('ecommerce_timeline_zasag')) {
                    var $selectedRows = getDataViewSelectedRows('<?php echo $this->metaDataId ?>');
                    rows = typeof $selectedRows[0] !== 'undefined' ? $selectedRows[0] : rows;
                }
                
                if (typeof wfmActions !== 'undefined') {
                    var $wfmLi = $('.workflow-dropdown-<?php echo $this->metaDataId ?>').find('li'), $thisli;

                    $wfmLi.each(function() {
                        $thisli = $(this);

                        contextMenuData[$thisli.find('a').attr('onclick')] = {
                            name: $thisli.find('a').text(),
                            icon: 'icon-shuffle'
                        };
                    });
                };
                
                $.each(contextMenuData, function ($indexCn, $contextR) {
                    if (typeof $contextR['_dvSimpleCriteria'] !== 'undefined' && $contextR['_dvSimpleCriteria']) {
                        var evalcriteria = ($contextR['_dvSimpleCriteria']).toLowerCase();
                        
                        if (evalcriteria.indexOf('#') > -1) {
                            var criteriaSplit = evalcriteria.split('#');
                            evalcriteria = (criteriaSplit[0]).trim();
                        }
                
                        $.each(rows, function(index, row) {
                            if (evalcriteria.indexOf(index) > -1) {
                                row = (row === null) ? '' : row.toLowerCase();
                                var regex = new RegExp('\\b' + index + '\\b', 'g');
                                evalcriteria = evalcriteria.replace(regex, "'" + row.toString() + "'");
                            }
                        });

                        try {
                            if (!eval(evalcriteria)) {
                                ticket = false;
                                delete contextMenuData[$indexCn];
                            }
                        } catch (err) {
                            console.log(err);
                        }
                    }
                });
                
                <?php
                if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true && issetParam($this->dataGridOptionData['CONTEXTMENUWFMSTATUS']) == 'true') {
                ?>
                contextMenuData['sep1'] = "---------";
                
                var isSuccessNextStatus = false;
                
                $.ajax({
                    type: 'post',
                    url: 'mdobject/getWorkflowNextStatus',
                    data: {metaDataId: '<?php echo $this->metaDataId; ?>', dataRow: rows},
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if (response.status === 'success' && response.datastatus && response.data) {

                            var rowId = '', realWfmName = '', advancedCriteria = '', wfmIcon = '';

                            if (typeof rows.id !== 'undefined') {
                                rowId = rows.id;
                            }

                            $.each(response.data, function (i, v) {
                                
                                isSuccessNextStatus = true;
                                if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                                    v.wfmstatusname = plang.get(v.processname);
                                }

                                if (v.wfmstatusicon) {
                                    wfmIcon = '<i class="fa '+v.wfmstatusicon+'"></i> ';
                                }

                                if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {

                                    contextMenuData[v.wfmstatusid] = {
                                        name: wfmIcon + v.wfmstatusname, 
                                        isHtmlName: true,  
                                        callback: function(key, options) {

                                            var $el = $('<span />', {text: v.wfmstatusname});

                                            if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                            }

                                            changeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->metaDataId; ?>', '<?php echo $this->refStructureId; ?>', v.wfmstatuscolor, v.wfmstatusname, '', '', '');
                                        }
                                    };

                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {

                                        if (v.wfmisneedsign == '1') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname + ' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = $('<span />', {text: v.wfmstatusname});
                                                    $el.attr('id', v.wfmstatusid);

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    beforeSignChangeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->metaDataId; ?>', '<?php echo $this->refStructureId; ?>', v.wfmstatuscolor, v.wfmstatusname);
                                                }
                                            };

                                        } else if (v.wfmisneedsign == '2') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = $('<span />', {text: v.wfmstatusname});
                                                    $el.attr('id', v.wfmstatusid);

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    beforeHardSignChangeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->metaDataId; ?>', '<?php echo $this->refStructureId; ?>', v.wfmstatuscolor, v.wfmstatusname);
                                                }
                                            };

                                        } else {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname, 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = $('<span />', {text: v.wfmstatusname});

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    changeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->metaDataId; ?>', '<?php echo $this->refStructureId; ?>', v.wfmstatuscolor, v.wfmstatusname);
                                                }
                                            };

                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {

                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';

                                        if (v.wfmisneedsign == '1') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = options.$trigger;

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    transferProcessAction('signProcess', '<?php echo $this->metaDataId; ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode; ?>', isWorkFlow: true, wfmStatusId: v.wfmstatusid, wfmStatusCode: wfmStatusCode, wfmStatusName: v.wfmstatusname}, 'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                                }
                                            };

                                        } else if (v.wfmisneedsign == '2') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = options.$trigger;

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    transferProcessAction('hardSignProcess', '<?php echo $this->metaDataId; ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode; ?>', isWorkFlow: true, wfmStatusId: v.wfmstatusid, wfmStatusCode: wfmStatusCode, wfmStatusName: v.wfmstatusname}, 'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                                }
                                            };

                                        } else {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname, 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = options.$trigger;

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    transferProcessAction('', '<?php echo $this->metaDataId; ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode; ?>', isWorkFlow: true, wfmStatusId: v.wfmstatusid, wfmStatusCode: wfmStatusCode, wfmStatusName: v.wfmstatusname}, 'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                                }
                                            };

                                        }
                                    }    
                                }

                            });
                        }
                        
                        if (!isIgnoreWfmHistory_<?php echo $this->metaDataId; ?>) {
                    
                            if (isSuccessNextStatus) {
                                contextMenuData['sep2'] = "---------";
                            }

                            if (response.hasOwnProperty('getUseAssignRuleId')) {
                                contextMenuData['wfmUserAssign'] = {
                                    name: plang.get('MET_99990846'), 
                                    isHtmlName: true,  
                                    callback: function(key, options) {
                                        userDefAssignWfmStatus(this, response.getUseAssignRuleId, '<?php echo $this->metaDataId; ?>');
                                    }
                                };
                            }

                            contextMenuData['wfmHistory'] = {
                                name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах'), 
                                isHtmlName: true, 
                                callback: function(key, options) {
                                    seeWfmStatusForm(this, '<?php echo $this->metaDataId ?>');
                                }
                            };
                        }
                    }
                });
                <?php
                }
                ?>
                
                var options =  {
                    callback: function (key, opt) {
                        eval(key);
                    },
                    items: contextMenuData
                };
                
                return options;
            }
        });  
    }
    
    function changeDirectWfmAssign_<?php echo $this->metaDataId; ?>(metaDataCode, chooseType, elem, rows) {
        var assigmentUserId = [], order = [], lock = [];
        var rows = getDataViewSelectedRows('<?php echo $this->metaDataId ?>')

        for(var i = 0; i < rows.length; i++) {
            assigmentUserId.push(rows[i].id);
            order.push(i);
            lock.push('0');
        }
        _wfmParams = {
            metaDataId: '<?php echo $this->metaDataId; ?>', 
            refStructureId: '1475634691559', 
            description: '', 
            wfmStatusId: rows[0].wfmstatusid,
            assigmentUserId: assigmentUserId,
            ruleId: '1',
            recordId: rows[0].id,
            order: order,
            lock: lock
        };
        
        changeWfmStatusAjax(_wfmParams, window['objectdatagrid_<?php echo $this->metaDataId; ?>'], '', '', elem, '');
    }    
    
    function wfmstatusRender_<?php echo $this->metaDataId ?> (e, type, isIgnoreAlert) {
        var $workflowDropdown = $('.workflow-dropdown-<?php echo $this->metaDataId ?>');
        $workflowDropdown.empty();
        
        var rows = getDataViewSelectedRows('<?php echo $this->metaDataId ?>');

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
            data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row, isManyRows: isManyRows},
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
                                    $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\');">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                    wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\')', name: v.wfmstatusname});
                                } else {
                                    var isIgnoreMultiRowRunBp = ('isignoremultirowrunbp' in Object(v) && v.isignoremultirowrunbp == '1') ? 1 : 0;
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && ((v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null) || isIgnoreMultiRowRunBp)) {
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '3') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '7') { /* pdf watermark */
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\');">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\')', name: v.wfmstatusname});
                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'pinCode\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'pinCode\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'otp\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'otp\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '7') { /* pdf watermark */
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon + v.wfmstatusname+'</a></li>');
                                            wfmActions.push({icon: wfmIcon, action:'transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        }
                                    }    
                                }
                            } else {
                                if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {
                                    $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\');">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                    wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\')', name: v.wfmstatusname});
                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '3') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '7') { /* pdf watermark */
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\');">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'pinCode\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'pinCode\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'otp\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'otp\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '7') { /* pdf watermark */
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeWaterMarkChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+wfmIcon+v.wfmstatusname+'</a></li>');
                                            wfmActions.push({icon: wfmIcon, action:'transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
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
                    
                    if (response.hasOwnProperty('getUseAssignRuleId')) {
                        $workflowDropdown.append('<li><a href="javascript:;" onclick="userDefAssignWfmStatus(this, \''+response.getUseAssignRuleId+'\', \'<?php echo $this->metaDataId ?>\');">'+plang.get('MET_99990846')+'</a></li>');
                    }
                    
                    if (!isIgnoreWfmHistory_<?php echo $this->metaDataId; ?>) {
                        wfmIcon = '';
                        if (typeof type !== 'undefined') {
                            wfmIcon = '<i class="icon-history"></i> ';
                        }
                        $workflowDropdown.append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\');">'+wfmIcon + plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
                        wfmActions.push({icon: wfmIcon, action:'seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\')', name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')});                  
                    }

                } else {
                    new PNotify({
                        title: 'Error',
                        text: response.message,
                        type: response.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }

                renderContextMenuDv_<?php echo $this->metaDataId; ?>(wfmActions, row);
                Core.unblockUI();
            },
            error: function() { alert("Error"); }
        });
    }
    
    if (typeof dv_no_resizable !== 'undefined' && dv_no_resizable && !$('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').hasClass("ui-resizable") && $().resizable) {

        $('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').resizable({
            autoHide: true,
            maxWidth: $(window).width()-84,
            minWidth: $(window).width()-84,
            start: function (event, ui) {
                $(this).addClass("highliteShape");
            },
            stop: function (event, ui) {
                $(this).removeClass("highliteShape");
            }
        });

        $('div.div-objectdatagrid-<?php echo $this->metaDataId; ?>').on("resizestop", function( event, ui ) {
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('resize', {
                height: $(event.target).height()
            });
        });
    }
    
    function filtersavedCriteria_<?php echo $this->metaDataId; ?> (element) {
        var $this = $(element);
        var $dataRow = JSON.parse($this.attr('data-rowdata'));
        
        $.ajax({
            type: 'post',
            url: 'mdobject/filtersavedCriteria',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                dataRow: $dataRow
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data) {
                
                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                Core.unblockUI();
            }
        });
    }
    
    function dvPopupCriteria<?php echo $this->metaDataId; ?>(elem) {
        var $dialogname = '.search-topsidebar-popup-<?php echo $this->metaDataId; ?>',
            dialogname = $($dialogname+':last');
    
        dialogname.dialog({
            appendTo: windowId_<?php echo $this->metaDataId; ?>,
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('filter'),
            height: 'auto',
            maxHeight: 700,
            width: '650',
            modal: true,
            open: function () {
                $(this).css({'min-width': 650, 'max-width': $(window).width() - 100});
            },
            close: function (elem) {
                dialogname.dialog('close');
            },
            buttons: [
                {text: plang.get('do_filter'), class: 'btn green-meadow btn-sm', click: function () {
                        
                    var $criTemSelector = $('input[name="criteriaTemplateName"]', windowId_<?php echo $this->metaDataId; ?>);
                    
                    if ($criTemSelector.length && $criTemSelector.is(':visible')) {
                        if ($criTemSelector.val() === '') {
                            $criTemSelector.addClass('border-danger')
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Загварын нэр оруулна уу!',
                                type: 'warning',
                                addclass: pnotifyPosition,
                                sticker: false
                            });                            
                            return;
                        } else {
                            $criTemSelector.removeClass('border-danger')
                            $('select[name="criteriaTemplates"]', windowId_<?php echo $this->metaDataId; ?>).removeClass('data-combo-set');
                        }
                    }                        
                    
                    dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                    dialogname.dialog('close');
                }},
                {text: plang.get('clear_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                    dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-reset-btn').click();
                }},
                {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                    dialogname.dialog('close');
                }}
            ]
        }).dialogExtend({
            'closable': true,
            'maximizable': false,
            'minimizable': false,
            'collapsable': false,
            'dblclick': 'maximize',
            'minimizeLocation': 'left',
            'icons': {
                'close': 'ui-icon-circle-close',
                'maximize': 'ui-icon-extlink',
                'minimize': 'ui-icon-minus',
                'collapse': 'ui-icon-triangle-1-s',
                'restore': 'ui-icon-newwin'
            }
        });
        dialogname.dialog('open');
        dialogname.parent().css("top", "0");
        Core.initDVAjax(dialogname);
    }    
    
    function addinProcessCriteria_<?php echo $this->metaDataId; ?> (index, row) {
        
        if (
            typeof row['statustextshow'] !== 'undefined' &&
            typeof row['statustitle'] !== 'undefined' &&
            typeof row['statustext'] !== 'undefined' &&
            typeof row['statustype'] !== 'undefined' &&
            row['statustextshow'] === '1' &&
            row['statustitle'] !== '' &&
            row['statustext'] !== '' &&
            row['statustype'] !== '' 
        ) {
            PNotify.removeAll();
            new PNotify({
                title: row['statustitle'],
                text: row['statustext'],
                type: row['statustype'],
                addclass: pnotifyPosition,
                sticker: false
            });
        }
        
        $.ajax({
            type: 'post',
            url: 'mdobject/addinProcessCriteria',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                dataRow: row
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data) {
                $('#md-map-civil-<?php echo $this->metaDataId; ?>').empty().append(data.Html).promise().done(function () {
					if(0 < $("#dialog-compare-pic").length && typeof comparePic === 'function'){
						comparePic(row);
					} else {
						Core.unblockUI();
					}
				});
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                Core.unblockUI();
            }
        });
    }
    
    function lookupCriteriaRefresh_<?php echo $this->metaDataId; ?>() {
        if ($('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').length) {
            $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').first().click();
        }
    }
    
    function dvRowSelector_<?php echo $this->metaDataId ?>(e, type, isIgnoreAlert) {
        
        if ($('.workflow-btn-<?php echo $this->metaDataId ?>', "#object-value-list-<?php echo $this->metaDataId; ?>").length) {
            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
            if (!$('.workflow-btn-<?php echo $this->metaDataId ?>').is(':visible')) {
                setTimeout(function() {
                    if ('<?php echo $this->layoutType ?>' == 'ecommerce') {
                        wfmstatusRender_<?php echo $this->metaDataId ?>(e, type, isIgnoreAlert);
                    } else {
                        $('.workflow-btn-<?php echo $this->metaDataId ?>').trigger('click', [true]);
                    }
                }, 300);
            }
        }
        
        <?php
        if ($this->isUseSidebar == '1' && !$this->layoutType === 'ecommerce') {
            echo 'detailRightSidebar_' . $this->metaDataId . '();';
        }
        if ($this->isUseSidebar === '0' && $this->layoutType === 'ecommerce') { 
        ?>
            if (!on_click_<?php echo $this->metaDataId; ?>) {
                on_click_<?php echo $this->metaDataId; ?> = true;
                $('.ecommerce_<?php echo $this->metaDataId ?>').find('.sidebar-right-toggle').trigger('click');
            }
        <?php 
        } 
        ?>
    }

    function kpiDvSearchInline_<?php echo $this->metaDataId ?>(elem) {
        var $kpiSections = $(elem).find('.dv-kpiform-criteria'), kpiCriteria = {}, 
        i = 0, isEmpty = true;

        $kpiSections.each(function() {
            var $kpiSection = $(this), $indicators = $kpiSection.find('[data-formkpi-row="1"]');
            
            if ($indicators.length) {
                
                var kpiIndicators = {}, kpiRow = {};
                
                $indicators.each(function(index) {
                    
                    var rowObj = {}, $indRow = $(this), $input = $indRow.find('[data-col-path]');
                    
                    rowObj['id'] = $indRow.find('[data-field-name="indicatorId"]').val();
                    rowObj['operator'] = '=';
                    rowObj['operand'] = '';
                    
                    if ($input.hasClass('decimalInit')) {
                        
                        rowObj['operand'] = $indRow.find('[data-col-path]').autoNumeric('get');
                        
                    } else if ($input.hasClass('md-check')) {
                        
                        var $checkInit = $input.closest('.checkInit').find('input[type="checkbox"]:checked');
                        
                        if ($checkInit.length) {
                            
                            rowObj['operator'] = 'IN';
                            rowObj['operand'] = $checkInit.map(function() { return this.value; }).get().join(',');
                        } 
                        
                    } else if ($input.hasClass('md-radio')) {
                        
                        var $radioInit = $input.closest('.radioInit').find('input[type="radio"]:checked');
                        if ($radioInit.length) {
                            rowObj['operand'] = $radioInit.val();
                        }
                        
                    } else {
                        rowObj['operand'] = $indRow.find('[data-col-path]').val();
                    }
                    
                    if (rowObj['operand'] == '' || rowObj['operand'] === null) {
                        return;
                    } else {
                        isEmpty = false;
                        kpiIndicators[index] = rowObj;
                    }
                });
                
                if (Object.keys(kpiIndicators).length) {
                
                    kpiRow['templateId'] = $kpiSection.find('input[name="param[pfKpiTemplateId][]"]').val();
                    kpiRow['indicators'] = kpiIndicators;

                    kpiCriteria[i] = kpiRow;
                    i++;
                }
            }
        });

        var $kpiTextarea = $(elem).find('#default-criteria-form').find('textarea.dv-kpi-criteria');

        if (Object.keys(kpiCriteria).length && !isEmpty) {
            var fieldPath = $kpiSections.closest('.input-group').find('[data-path]').attr('data-path');
            
            if ($kpiTextarea.length) {
                $kpiTextarea.val(JSON.stringify(kpiCriteria));
            } else {
                $(elem).find('#default-criteria-form').append('<textarea name="criteriaKpi['+fieldPath+']" class="d-none dv-kpi-criteria">'+JSON.stringify(kpiCriteria)+'</textarea>');
            }
            
        } else {
            $kpiTextarea.remove();
        }    
    }    

    function dvMultiValueFilter_<?php echo $this->metaDataId ?>(elem) {
        
        var $fieldName = elem.closest('td').attr('field'), $this = elem;
        var $dialogname = 'dialog-multiple-filter_<?php echo $this->metaDataId ?>' + '_' + $fieldName;
        var data = '<div class="row"><div class="col-md-12">'+
            '<input type="text" name="dvMultipleFilterString" class="form-control form-control-sm" placeholder="Хайх">'+
        '</div>'+
        '<div class="dv-multi-filter-datas mt15 col-md-12"></div></div>';
        
        var $dataGrid = objectdatagrid_<?php echo $this->metaDataId; ?>, 
            $op = $dataGrid.datagrid('options');

        if (!$($dialogname).length) {
            $('<div id="' + $dialogname + '" data-dv-multi-filter="<?php echo $this->metaDataId; ?>"></div>').appendTo('body');
        }
        
        dialogname = $('#'+$dialogname);
        
        var filterBtns = [
            {text: plang.get('search_btn'), class: 'btn btn-sm green-meadow', click: function () {
                var multiInputVal = '';
                dialogname.find('table > tbody').find('input[type="checkbox"]').each(function() {
                    var _thisV = $(this), filterVal2 = {};
                    if (_thisV.is(':checked')) {
                        multiInputVal += '<textarea class="d-none" name="param['+$fieldName+'][]">'+_thisV.val().replace(/#dblquote#/g, '"')+'</textarea>';
                    }
                });      
                
                if (!$('#multiple_filter_values_<?php echo $this->metaDataId ?>' + '_' + $fieldName).length && multiInputVal) {
                    $('<div class="multiple_filter_values" data-field="' + $fieldName + '" data-dialog-id="'+$dialogname+'" id="multiple_filter_values_<?php echo $this->metaDataId ?>' + '_' + $fieldName + '"></div>').appendTo($("div#object-value-list-<?php echo $this->metaDataId; ?>"));
                }
                
                if (multiInputVal) {

                    if (!$this.find('.dataview-multivalue-filter').length) {
                        $this.addClass('dataview-multivalue-filter-sticky');
                        $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() - 15 + 'px').attr('data-multifilter', 1);
                        $this.append('<a href="javascript:;" title="Олон утгаар хайх" class="dataview-multivalue-filter"><i class="icon-filter3"></i></a>');
                    }                            

                    $('#multiple_filter_values_<?php echo $this->metaDataId ?>' + '_' + $fieldName).empty().append(multiInputVal);

                    var dvMultiFilterCriteria = '';
                    if ($(".multiple_filter_values", "#object-value-list-<?php echo $this->metaDataId; ?>").length) {
                        dvMultiFilterCriteria = $(".multiple_filter_values", "#object-value-list-<?php echo $this->metaDataId; ?>").find('textarea').serialize();
                    }                    
                    dvSearchParamData_<?php echo $this->metaDataId; ?>(dvMultiFilterCriteria);                      
                    dialogname.dialog('close');
                } else {
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').trigger('click');
                }                
            }},
            {text: plang.get('clear_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                    
                if ($this.find('.dataview-multivalue-filter').length) {
                    $this.removeClass('dataview-multivalue-filter-sticky');
                    $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() + 15 + 'px').removeAttr('data-multifilter');
                    $this.find('.dataview-multivalue-filter').remove();
                }    
                
                if ($('#multiple_filter_values_<?php echo $this->metaDataId ?>' + '_' + $fieldName).length) {
                    $('#multiple_filter_values_<?php echo $this->metaDataId ?>' + '_' + $fieldName).remove();

                    var dvMultiFilterCriteria = '';
                    if ($(".multiple_filter_values", "#object-value-list-<?php echo $this->metaDataId; ?>").length) {
                        dvMultiFilterCriteria = $(".multiple_filter_values", "#object-value-list-<?php echo $this->metaDataId; ?>").find('textarea').serialize();
                    }                    
                    dvSearchParamData_<?php echo $this->metaDataId; ?>(dvMultiFilterCriteria);                      
                }
                dialogname.dialog('close');
                dialogname.empty().dialog('destroy').remove();
            }},                    
            {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                dialogname.dialog('close');
            }}
        ];

        if (dialogname.children().length > 0) {
            dialogname.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Олон утгаар хайх',
                width: 380,
                height: 'auto',
                modal: true,
                close: function () {
                    dialogname.dialog('close');
                },
                buttons: filterBtns
            });
            dialogname.dialog('open');
            
            dialogname.on('keyup', 'input[name="dvMultipleFilterString"]', function(){
                var $self = $(this);
                $self.closest('.ui-dialog-content').find('table > tbody > tr').each(function(){
                    var $this = $(this);
                    if ($self.val() == '') {
                        $this.show();
                    } else {
                        if ($this.find('label').text().toLowerCase().search($self.val().toLowerCase()) === -1) {
                            $this.hide();
                        } else {
                            $this.show();
                        }
                    }
                });
            }); 
            
        } else {

            if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
            } else {
                if ($this.closest('div.package-meta-tab').attr('data-realpack-id')) {
                    var $packageId = $this.closest('div.package-meta-tab').attr('data-realpack-id');
                    var defaultCriteriaData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
                } else {
                    var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
                }
            }
            var queryParams = $op.queryParams;
            var $dvMultiFilterCriteria = $(windowId_<?php echo $this->metaDataId; ?>).find('.multiple_filter_values');
            if ($dvMultiFilterCriteria.length) {
                defaultCriteriaData += '&' + $dvMultiFilterCriteria.find('textarea').serialize();
            } 
            
            if ($('body').find('form.adv-criteria-form-<?php echo $this->metaDataId; ?>').length) {
                defaultCriteriaData += '&' + $('body').find('form.adv-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
            }
            
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: defaultCriteriaData, 
                workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val(), 
                ignoreFirstLoad: false,
                filterColumn: $fieldName
            };
            
            if (queryParams.hasOwnProperty('drillDownDefaultCriteria')) {
                dvSearchParam.drillDownDefaultCriteria = queryParams.drillDownDefaultCriteria;
            }
            
            $.ajax({
                type: 'post',
                url: 'mdobject/dataViewDataGrid',
                data: dvSearchParam,
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({boxed: true, message: 'Loading...'});  
                },
                success: function(resp) {
                    
                    if (resp.status === 'success') {
                        
                        dialogname.empty().append(data);
                        
                        var filterHtml = [];
                        
                        filterHtml.push('<div class="ml-1 mb-1"><label><input type="checkbox" value="" name="dv_multifilter_select_all" id="dv_multifilter_select_all" class="notuniform mr9"/> ' + plang.get('select_all') + '</label></div>');
                        filterHtml.push('<div style="border: 1px solid #ddd; overflow-y: auto; max-height: 280px;">');
                        filterHtml.push('<table class="table table-sm table-hover mb0"><tbody>');
                        
                        $.each(resp.rows[$fieldName], function(k, v){
                            if (v == null || v == '') {
                                filterHtml.push('<tr><td style="width: 25px;"><input type="checkbox" value="@empty@" id="filter_<?php echo $this->metaDataId; ?>_" class="notuniform"/></td><td><label for="filter_<?php echo $this->metaDataId; ?>_">Хоосон мөр</label></td></tr>');
                            } else {
                                filterHtml.push('<tr><td style="width: 25px;"><input type="checkbox" value="' + v.replace(/"/g, '#dblquote#') + '" id="filter_<?php echo $this->metaDataId; ?>_'+$fieldName+'_'+k+'" class="notuniform"/></td><td class="text-break"><label for="filter_<?php echo $this->metaDataId; ?>_'+$fieldName+'_'+k+'">' + v + '</label></td></tr>');
                            }
                        });
                        
                        filterHtml.push('</tbody></table></div>');
                        
                        $('#'+$dialogname).find('.dv-multi-filter-datas').empty().append(filterHtml.join(''));
                        
                        dialogname.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Олон утгаар хайх',
                            width: 380,
                            height: 'auto',
                            modal: true,
                            close: function () {
                                dialogname.dialog('close');
                            },
                            buttons: filterBtns
                        });
                        dialogname.dialog('open');

                        dialogname.on('keyup', 'input[name="dvMultipleFilterString"]', function(){
                            var $self = $(this);
                            $self.closest('.ui-dialog-content').find('table > tbody > tr').each(function(k, v){

                                var $this = $(this);

                                if ($self.val() == '') {
                                    $this.show();
                                } else {
                                    if ($this.find('label').text().toLowerCase().search($self.val().toLowerCase()) === -1) {
                                        $this.hide();
                                    } else {
                                        $this.show();
                                    }
                                }
                            });
                        });
                        dialogname.on('click', '#dv_multifilter_select_all', function(){
                            var $self = $(this);
                            $self.closest('.ui-dialog-content').find('table > tbody > tr').each(function() {
                                var $this = $(this);
                                if ($this.is(':visible')) {
                                    if ($self.is(':checked')) {
                                        $this.find('input[type="checkbox"]').prop('checked', true);
                                    } else {
                                        $this.find('input[type="checkbox"]').prop('checked', false);
                                    }
                                }
                            });
                        });
                        
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: resp.status,
                            text: resp.message,
                            type: 'warning',
                            sticker: false
                        });           
                    }
                    
                    Core.unblockUI();
                }
            });        
        }
    }    
    function dvSelectionCountToFooter_<?php echo $this->metaDataId; ?>() {
        var $panelView = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getPanel');    
        if ($panelView.find(".datagrid-pager").length) {
            if ($panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').length) {
                $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').remove();
            }
            var rows = window['objectdatagrid_<?php echo $this->metaDataId ?>'].datagrid('getSelections');
            $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').append('<td class="custom-selected-counter"><div class="pagination-btn-separator"></div></td><td class="custom-selected-counter pl6">'+plang.get('has_chosen')+': '+rows.length+'</td>');
        }
    }
    function dvReloadFooterData(grid, data) {
        <?php
        if (Config::getFromCache('javaversion') >= 1 && !issetParam($this->useBasket)) {
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
        } /*else {
            if (isTreegrid) {
                grid.treegrid('reloadFooter', []);
            } else {
                grid.datagrid('reloadFooter', []);
            }
        }*/
        
        setTimeout(function() {
            if (data && data.hasOwnProperty('total')) {
                if (isTreegrid) {
                    opts = grid.treegrid('options');
                }
                grid.datagrid('getPager').pagination('refresh', {total: data.total, pageNumber: opts.pageNumber});
            } else {
                grid.datagrid('getPager').pagination('refresh', {total: 0});
            }
        }, 0);
        <?php
        }
        ?>
    }
    function dvColumnShowCriteria_<?php echo $this->metaDataId; ?>() {
        var criteria = <?php echo (isset($this->row['columnShowCriteria']) && $this->row['columnShowCriteria']) ? json_encode($this->row['columnShowCriteria'], JSON_UNESCAPED_UNICODE) : '[]'; ?>;
        
        if (criteria.length) {
            
            $.fn.serializeAll = function() {
                var data = $(this).serializeArray();
                $(':disabled[name]', this).each(function() { 
                    data.push({ name: this.name, value: $(this).val() });
                });
                return data;
            };
                    
            var $packageTab = objectdatagrid_<?php echo $this->metaDataId; ?>.closest('div.package-meta-tab');
            if ($packageTab.length && $packageTab.attr('data-realpack-id')) {
                var packageId = $packageTab.attr('data-realpack-id');
                var $packageMeta = $('#package-meta-' + packageId);
                var defaultCriteriaData = $packageMeta.find("form.package-criteria-form-" + packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serializeAll();
            } else {
                var defaultCriteriaData = $(windowId_<?php echo $this->metaDataId; ?>).find("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serializeAll();
            }
            
            var $subQueryIdElem = $('#subQueryId-<?php echo $this->metaDataId; ?>'), pfSubQueryCode = '';
            
            if ($subQueryIdElem.length && $subQueryIdElem.val() != '' && $subQueryIdElem.find('option:selected').hasAttr('data-code')) {
                pfSubQueryCode = $subQueryId.find('option:selected').attr('data-code');
            }
            
            var checkData = {'pfsubquerycode': pfSubQueryCode};
            
            if (defaultCriteriaData) {
                for (var c in defaultCriteriaData) {
                    if ((defaultCriteriaData[c]['name']).indexOf('param[') !== -1) {
                        var matches = (defaultCriteriaData[c]['name']).match(/\[(.*?)\]/g);
                        var colName = (matches[0]).replace('[', '').replace(']', '').toLowerCase();
                        checkData[colName] = defaultCriteriaData[c]['value'];
                    }
                }
            }
            
            for (var i in criteria) {
                var columnCriteria = (criteria[i]['CRITERIA']).toLowerCase();
                
                $.each(checkData, function(index, row) {
                    if (columnCriteria.indexOf(index) > -1) {
                        row = (row === null) ? '' : row;
                        var regex = new RegExp('\\b' + index + '\\b', 'g');
                        columnCriteria = columnCriteria.replace(regex, "'" + row.toString().toLowerCase() + "'");
                    }
                });
                
                try {
                    if (eval(columnCriteria)) {
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('showColumn', criteria[i]['FIELD_PATH']);
                    } else {
                        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('hideColumn', criteria[i]['FIELD_PATH']);
                    }
                } catch (err) {
                    console.log(err);
                }
            }
            
            objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('resize');
        }
    }
    
    function dvMultipleFilterValuesRemove_<?php echo $this->metaDataId; ?>() {
        var $multiple_filter_values = $(".multiple_filter_values", "#object-value-list-<?php echo $this->metaDataId; ?>");
        if ($multiple_filter_values.length) {
            $multiple_filter_values.each(function(){
                $('#'+$(this).attr('data-dialog-id')).empty().dialog('destroy').remove();
            });
            $multiple_filter_values.remove();
            var $dvMultiValueFilter = $("#object-value-list-<?php echo $this->metaDataId; ?>").find('.dataview-multivalue-filter');
            if ($dvMultiValueFilter.length) {
                $dvMultiValueFilter.closest('.dataview-multivalue-filter-sticky').removeClass('dataview-multivalue-filter-sticky');
                $dvMultiValueFilter.remove();
            }
        } 
        var $multiValueFilterDialog = $('div[data-dv-multi-filter="<?php echo $this->metaDataId; ?>"]');

        if ($multiValueFilterDialog.length) {
            $multiValueFilterDialog.each(function() {
                var $this = $(this);
                if ($this.data('ui-dialog')) {
                    $this.dialog('destroy').remove();
                } else {
                    $this.empty();
                }
            });
        }
    }
    
    function dvDraggableRowsToFilter_<?php echo $this->metaDataId; ?>($panelView, _thisGrid) {
        <?php
        if (issetParam($this->row['DRAG_ROWS_RUN_PROCESS_ID'])) {
        ?>
        var dvId = '<?php echo $this->metaDataId; ?>';
        $panelView.find(".datagrid-view2 tr").draggable({
            appendTo: "body", 
            zIndex: 1000, 
            cursor: "pointer", 
            cursorAt: {top: 15, left: -15}, 
            /*drag: function() { return false; },*/
            helper: function() {
                var $selectedRows = $("<table>", {class: "table table-xs table-bordered bg-white", style: 'position: fixed;'});
                var $cloneRow = $(this).clone();
                $selectedRows.append($cloneRow);
                return $selectedRows;
            }, 
            start: function(event, ui) {
                $('#dataViewStructureTreeView_' + dvId).find("a.jstree-anchor").droppable({
                    hoverClass: "ui-state-highlight",
                    tolerance: "pointer",
                    drop: function(e, ui) {
                        var $this = $(this), $parent = $this.parent('li');
                        var parentId = $parent.attr('id');

                        if (parentId != 'all' && parentId != 'null') {
                            var $rows = $(ui.helper).find('> tr');
                            var dataRows = _thisGrid.datagrid('getRows');
                            var dtlIds = [];
                            $rows.each(function() {
                                var $row = $(this);
                                dtlIds.push({
                                    'sourceId': parentId, 
                                    'targetId': dataRows[$row.attr('datagrid-row-index')]['id']
                                });
                            });

                            $.ajax({
                                type: 'post',
                                url: 'mdobject/draggableRowsToFilter',
                                data: {metaDataId: dvId, headerId: parentId, dtlIds: dtlIds},
                                dataType: 'json',
                                success: function(data) {
                                    if (data.status === 'success') {
                                        dataViewReload(<?php echo $this->metaDataId; ?>);
                                    }
                                }
                            });
                        }

                        $(ui.helper).remove();
                    }
                });
            }
        });
        <?php
        }
        ?>
        return;
    }
</script>
<?php 
include_once 'criteriaScripts.php';

if (issetParam($this->dataGridOptionData['NOWRAP']) == 'false' && isset($isTreeGridData['name'])) {
    echo '<style type="text/css">
        .div-objectdatagrid-'.$this->metaDataId.' .tree-title {
            padding-top: 0;
            word-break: break-word;
            word-wrap: break-word;
            white-space: pre-wrap;
            height: auto;
            white-space: inherit;
            flex: 1;
        }
        .div-objectdatagrid-'.$this->metaDataId.' .datagrid-btable td[field="'.$isTreeGridData['name'].'"] .datagrid-cell {
            display: flex;
        }
    </style>';
}
?>
