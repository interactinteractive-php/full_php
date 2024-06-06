<div class="row dataViewUseBasketViewWrap" data-basketid="<?php echo $this->uniqId; ?>" id="object-value-list-<?php echo $this->uniqId; ?>">
    <div class="col-md-12 object-height-row3-minus-<?php echo $this->uniqId ?>" >
        <div class="table-toolbar">
            <div class="row dv-button-style-<?php echo $this->buttonBarStyle; ?>">
                <div class="col-md-9">
                    <div class="dv-process-buttons dv-process-button-style-<?php echo $this->metaDataId; ?> d-flex">
                        <?php 
                        $commandBtn = $this->dataViewProcessCommand['commandBtn'];

                        if (is_array($this->dataViewProcessCommand['commandBtnPosition'])) {
                            foreach ($this->dataViewProcessCommand['commandBtnPosition'] as $posBtn) {
                                echo $posBtn['html'];
                            }
                        }                        
                        
                        if ($commandBtn) {                            
                            $commandBtn = str_replace('class="btn-group btn-group-devided pr4"', 'class="btn-group btn-group-devided col-md-12 pl0"', $commandBtn);
                            //$commandBtn = str_replace('<!--endbutton-->', $addonBtn, $commandBtn);
                            $commandBtn = str_replace('d-flex', '', $commandBtn);
                        }
                        
                        if ($this->isPrint) {

                            $invoicePrintBtn = html_tag('button', array(
                                'type' => 'button', 
                                'class' => 'btn btn-sm btn-circle green', 
                                'onclick' => 'dataViewPrintPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this, undefined, true);'
                            ), '<i class="far fa-print"></i> '.($this->lang->line('printTemplate'.$this->metaDataId) == 'printTemplate'.$this->metaDataId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->metaDataId)));

                            $commandBtn .= $invoicePrintBtn;
                        }                        
                        
                        echo $commandBtn;
                        
                        if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) { 
                            echo '<div class="btn-group workflow-btn-group-'.$this->uniqId.'">
                                <button type="button" class="btn btn-sm blue btn-circle dropdown-toggle workflow-btn-'.$this->uniqId.'" data-toggle="dropdown"><i class="far fa-cogs"></i> '.$this->lang->line('change_workflow').'</button>
                                <ul class="dropdown-menu workflow-dropdown-'.$this->uniqId.'" role="menu"></ul>
                            </div>';
                        } 
                        ?>
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <?php
                        if ($commandBtn) {
                            echo html_tag('button', array(
                                'type' => 'button', 
                                'class' => 'btn btn-sm btn-circle btn-danger float-right', 
                                'onclick' => 'basketDvAllRowsRemove_'.$this->uniqId.'(this);',
                                'style' => 'background-color: #FFF;
                                border: 1px solid #f44336 !important;
                                border-radius: 100px !important;
                                color: #f44336 !important;'
                            ), '<i class="fa fa-refresh"></i> '.$this->lang->line('META_00172'));
                        }                    
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 div-objectdatagrid-<?php echo $this->uniqId; ?> <?php echo $this->dataGridOptionData['VIEWTHEME']; ?>" >
        <table id="objectdatagrid-<?php echo $this->uniqId; ?>" style="width: 1170px !important; height: 500px"></table>
    </div>
</div>
<div id="objectDashboardView_<?php echo $this->uniqId; ?>"></div>
<div id="objectReportTemplateView_<?php echo $this->uniqId; ?>"></div>

<?php 
echo Form::hidden(array('id' => 'cardViewerFieldPath')); 
echo Form::hidden(array('id' => 'cardViewerValue')); 
echo Form::hidden(array('id' => 'treeFolderValue')); 
echo Form::hidden(array('id' => 'currentSelectedRowIndex')); 
echo Form::hidden(array('id' => 'refStructureId', 'value' => $this->refStructureId)); 
?>

<div class="clearfix w-100"></div>
    
<script type="text/javascript">
    var dataGridTypeBtn_<?php echo $this->uniqId; ?> = 'datagrid';
    var objectdatagrid_<?php echo $this->uniqId; ?> = $('#objectdatagrid-<?php echo $this->uniqId; ?>');
    var windowId_<?php echo $this->uniqId; ?> = 'div#object-value-list-<?php echo $this->uniqId; ?>';
    var modeType_<?php echo $this->uniqId; ?> = '<?php echo isset($this->modeType) ? $this->modeType : 'true' ?>';
    var dv_theme_<?php echo $this->uniqId; ?> = '<?php echo $this->dataGridOptionData['VIEWTHEME']; ?>';
    var rows_<?php echo $this->uniqId; ?> = <?php echo $this->selectedBasketRows ?>;
    var commonSelectableGridName_<?php echo $this->metaDataId; ?> = 'objectdatagrid-<?php echo $this->metaDataId; ?>';
    
    <?php echo $this->layoutTypes; ?>

    $(function() {
        
        $(windowId_<?php echo $this->uniqId; ?>).find('a[data-actiontype="insert"]').remove();        
        
        <?php
        $layoutType = ($this->isTreeGridData ? '' : 'view: '.($this->subgrid ? 'detailview,'."\n" : 'horizonscrollview,'."\n"));
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
                $options .= "rownumbers: true,";
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
                $options .= "showHeader: true,";
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
                echo $frozenColumns;
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
                
                <?php 
                echo Arr::get($this->dataGridColumnData, 'filterCenterInit');
                echo Arr::get($this->dataGridColumnData, 'filterDateInit');
                echo Arr::get($this->dataGridColumnData, 'filterDateTimeInit');
                echo Arr::get($this->dataGridColumnData, 'filterTimeInit');
                echo Arr::get($this->dataGridColumnData, 'filterBigDecimalInit');
                echo Arr::get($this->dataGridColumnData, 'filterNumberInit');
                ?>
                
                if ($('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').length) {
                    $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').remove();
                }
                $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').append('<span class="float-right basket-data-count-<?php echo $this->uniqId; ?>" style="font-size: 12px;">Нийт <strong>'+ rows_<?php echo $this->uniqId; ?>.length +'</strong> байна.</span>');
                
                var dgRows = _thisGrid.datagrid('getRows');
                for (var key in dgRows) {
                    if (!Object.keys(dgRows[key]).length) {
                        _thisGrid.datagrid('deleteRow', key);
                        
                        if ($('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').length) {
                            $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').find('.basket-data-count-<?php echo $this->uniqId; ?>').remove();
                        }                        
                        $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').append('<span class="float-right basket-data-count-<?php echo $this->uniqId; ?>" style="font-size: 12px;">Нийт <strong>'+ (rows_<?php echo $this->uniqId; ?>.length - 1) +'</strong> байна.</span>');
                    }
                }         
                
                selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>();
                  
                Core.initFancybox($panelView);
            }
        });
        
        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('enableFilter', [
            {field: 'action', type: 'label'}
        ]);
        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('hideColumn', 'ck');

        $('.workflow-btn-<?php echo $this->uniqId ?>').on('click', function () {
            $('.workflow-dropdown-<?php echo $this->uniqId ?>').empty();
            //var rows = getDataViewSelectedRows('<?php echo $this->uniqId ?>');
            var rows = getRowsDataView('<?php echo $this->uniqId ?>');
            if (rows.length === 0) {
                alert("Та мөр сонгоно уу!");
                return;
            }
            
            var row = rows[0], isManyRows = '';
            if (rows.length > 1) {
                row = rows;
                isManyRows = '1';
            }            
            
            $.ajax({
                type: 'post',
                url: 'mdobject/getWorkflowNextStatus',
                data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row, isManyRows: isManyRows},
                dataType: "json",
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.datastatus) {
                            var rowId = '';
                            if (typeof row.id !== 'undefined') {
                                rowId = row.id;
                            }
                            $.each(response.data, function (i, v) {
                                var advancedCriteria = '';
                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                    advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                                }
                                
                                if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                    $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-basketdvid="<?php echo $this->uniqId; ?>">'+ v.wfmstatusname +'</a></li>'); 
                                } else {
                                    var isIgnoreMultiRowRunBp = ('isignoremultirowrunbp' in Object(v) && v.isignoremultirowrunbp == '1' && isManyRows == '1') ? 1 : 0;
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && ((v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null) || isIgnoreMultiRowRunBp)) {
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-basketdvid="<?php echo $this->uniqId; ?>">'+ v.wfmstatusname +'</a></li>'); 
                                        }
                                    } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\', undefined, undefined, undefined, \'<?php echo $this->uniqId; ?>\');">'+v.wfmstatusname+'</a></li>');
                                        }
                                    }    
                                }
                            });    
                        } 
                        
                        $('.workflow-dropdown-<?php echo $this->uniqId ?>').append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\');">'+plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: response.message,
                            type: response.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                },
                error: function() {
                    alert("Error");
                }
            });
        });
        
        $(window).bind('resize', function() {
            if ($("body").find("#object-value-list-<?php echo $this->uniqId; ?>").length > 0 && $("body").find("#object-value-list-<?php echo $this->uniqId; ?>").is(':visible')) {
                var toolbarHeight = $("body").find("#object-value-list-<?php echo $this->uniqId; ?>").find('div.table-toolbar:first').width();
                var dataGridHeight = $("body").find("#object-value-list-<?php echo $this->uniqId; ?>").find('div.datagrid-wrap:first').width();
                if (toolbarHeight !== dataGridHeight) {
                    objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('resize');
                }
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
                    console.log(index);
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
            $('.div-objectdatagrid-<?php echo $this->uniqId; ?>').append('<span class="float-right basket-data-count-<?php echo $this->uniqId; ?>" style="font-size: 12px;">Нийт <strong>'+ rows_<?php echo $this->uniqId; ?>.length +'</strong> байна.</span>');
            
            $('.save-database-<?php echo $this->metaDataId; ?>').text(_selectedRows_<?php echo $this->metaDataId; ?>.length);
            
        }, 5);
    }
    
    function selectableBasketDataGridReloadFooter_<?php echo $this->metaDataId ?>() {
        var $commonSelectableTabBasket_<?php echo $this->uniqId ?> = $('.div-objectdatagrid-<?php echo $this->uniqId; ?>'),
            dataGridParamAttrLink_<?php echo $this->uniqId ?> = $('#'+commonSelectableGridName_<?php echo $this->metaDataId ?>).datagrid('getFooterRows'),
            footerJson__<?php echo $this->uniqId ?> = {};
        
        if (typeof dataGridParamAttrLink_<?php echo $this->uniqId ?> !== 'undefined' && dataGridParamAttrLink_<?php echo $this->uniqId ?>.length > 0) {
            dataGridParamAttrLink_<?php echo $this->uniqId ?> = dataGridParamAttrLink_<?php echo $this->uniqId ?>[0];
            $.each(dataGridParamAttrLink_<?php echo $this->uniqId ?>, function(paramName, paramVal){
                $.each($commonSelectableTabBasket_<?php echo $this->uniqId ?>.find('.datagrid-body table tbody tr'), function(idx, dgRows){
                    if ($(dgRows).find('td[field=' + paramName + ']').length > 0) {
                        var $cellField = $(dgRows).find('td[field=' + paramName + '] .datagrid-cell');
                        if (typeof footerJson__<?php echo $this->uniqId ?>[paramName] !== 'undefined') {
                            footerJson__<?php echo $this->uniqId ?>[paramName] = 
                                calculateFooterVal('sum', Number($cellField.text().replace(/,/g, '')), Number(footerJson__<?php echo $this->uniqId ?>[paramName]), $commonSelectableTabBasket_<?php echo $this->uniqId ?>.find('.datagrid-body table tbody').find('td[field=' + paramName + ']').length);
                        } else {                                
                            footerJson__<?php echo $this->uniqId ?>[paramName] = Number($cellField.text().replace(/,/g, ''));
                        }
                    }
                });
            });
            
            objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('reloadFooter', [footerJson__<?php echo $this->uniqId ?>]);
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
    
    function basketDvAllRowsRemove_<?php echo $this->uniqId; ?>(elem) {
        var $dialogName = 'dialog-basket-remove-confirm';
        
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');

            $.ajax({
                type: 'post',
                url: 'mdcommon/deleteConfirm',
                dataType: 'json',
                async: false, 
                success: function (data) {
                    $("#" + $dialogName).empty().append(data.Html);
                }
            });
        }

        var $dialog = $('#' + $dialogName);

        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 330,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    rows_<?php echo $this->uniqId; ?> = [];
                    _selectedRows_<?php echo $this->metaDataId; ?> = [];
                    $('.save-database-<?php echo $this->metaDataId; ?>').text('0');
                    $dialog.dialog('close');
                    $('#dataViewBasket-dialog-<?php echo $this->metaDataId; ?>').dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');

        return;
    }
</script>