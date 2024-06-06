<?php
$jsonConfig = issetParamArray($this->paramConfig['jsonConfig']);
?>
<div class="bpdtl-widget-detail_frame_paper_001 d-flex">
    <?php 
    if ($jsonConfig && issetParam($jsonConfig['parentid'])) { 
        $groupedData = [];
        $jsonConfig = Arr::changeKeyLower($jsonConfig);
        
        if ($this->fillParamData) {
            $detailData = $this->fillParamData[Str::lower($jsonConfig['dtlpath'])];
                        
            if ($detailData) {
                foreach ($detailData as $rowkey => $row) {
                    $groupedData[$rowkey] = $row;
                    $groupedData[$rowkey]['name'] = $row['paragraphtext'];
                }
            }
        }
    ?>
    <div class="dataviewtreeview_processdetail_filter_wrapper">
        <div class="dataviewtreeview_processdetail_filter"></div>
    </div>
    <?php 
    } 
    ?>
    <div class="bpdtl-widget-detail_frame_paper_001-body">
        {content}
    </div>
</div>

<style type="text/css">
.dataviewtreeview_processdetail_filter_wrapper {
    flex: 0 0 400px;
    margin-right: 20px;    
    background: #fff;
    box-shadow: 0 0.5mm 2mm rgb(0 0 0 / 30%);
    padding: 15px;    
    overflow: hidden;
}
.bpdtl-widget-detail_frame_paper_001 {
    padding: 20px 20px 20px 20px; 
    background-color: #efefef;
}
.bpdtl-widget-detail_frame_paper_001-body {
    background: white;
    box-shadow: 0 0.5mm 2mm rgb(0 0 0 / 30%);
    padding: 20px 35px;
}
.bpdtl-widget-detail_frame_paper_001-body table.bprocess-table-dtl, 
.bpdtl-widget-detail_frame_paper_001-body table.kpi-dtl-table {
    table-layout: fixed;
}
.bpdtl-widget-detail_frame_paper_001-body table.bprocess-table-dtl > thead {
    display: none;
}

.bpdtl-widget-detail_frame_paper_001-body table.bprocess-table-dtl > tbody > tr > td:first-of-type, 
.bpdtl-widget-detail_frame_paper_001-body table.kpi-dtl-table > tbody > tr > td:first-of-type {
    width: 30px;
    min-width: 30px;
}
.bpdtl-widget-detail_frame_paper_001-body .bp-overflow-xy-auto {
    border: none !important;
}
.bpdtl-widget-detail_frame_paper_001-body table.bprocess-table-dtl>tbody>tr.currentTarget>td {
    border-bottom: none !important;
}
.bpdtl-widget-detail_frame_paper_001-body .table-bordered td {
    border: none !important;
}
.bpdtl-widget-detail_frame_paper_001-body .table-bordered {
    border: none !important;
}
.bpdtl-widget-detail_frame_paper_001-body .table-hover tbody tr:hover {
    color: inherit;
    background-color: inherit; 
}
.bpdtl-widget-detail_frame_paper_001-body table.bprocess-theme1.table-hover>tbody>tr:hover>td {
    background-color: inherit;
}
div[data-process-id="<?php echo $this->methodId; ?>"] .bpdtl-widget-detail_frame_paper_001 .jstree-ocl {
    display: none;
}
div[data-process-id="<?php echo $this->methodId; ?>"] .bpdtl-widget-detail_frame_paper_001 .jstree-anchor {
    display: block;
    min-height: 25px!important;
    padding-top: 5px;
    padding-left: 4px;
}
.jstree-numbering-cls {
    padding-right: 10px;
}
</style>

<?php 
if ($jsonConfig) {   
    $numberingColumn = issetParam($jsonConfig['numberingcolumn']);
    $filterColumn = issetParam($jsonConfig['filtercolumn']);
    $filterColumn1 = issetParam($jsonConfig['filtercolumn1']);
    $childParentId = issetParam($jsonConfig['childparentid']);
    $dtlParentId = issetParam($jsonConfig['dtlparentid']);
    $filterHeaderColumn = issetParam($jsonConfig['filtergroupbydvheaderid']);
    $detailPath = issetParam($jsonConfig['dtlpath']);
    $parentId = issetParam($jsonConfig['parentid']);
?>
<script type="text/javascript">
    var _parentId = '<?php echo Str::lower($jsonConfig['parentid']) ?>';
    var editJsonSavedData = <?php echo isset($groupedData) ? json_encode($groupedData) : json_encode([]) ?>;
    var editJsonData = resolveParentChild_detail_frame_paper_001(editJsonSavedData, '');    
    
    initJstree_widget_detail_frame_paper_001(editJsonData);    

    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bp-detail-row', function(e) {
        var $this = $(this);
        $thisCurr = $(e.target);

        if ($thisCurr.hasClass('bpPaper001AddMainRow_<?php echo $this->methodId; ?>') || $thisCurr.hasClass('icon-plus3-paper001')) {
            return;
        }

        $this.closest('.tbody').find('.temp_add_btn_html').remove();
        if ($this.find('input[data-path="conContractTemplate.paragraphNumber"]').length) {
            var paragraphNumberArr = $this.find('input[data-path="conContractTemplate.paragraphNumber"]').val().split('.');
            if (paragraphNumberArr.length == 1) {
                return;
            }
            $this.find('td[data-cell-path="conContractTemplate.paragraphNumber"]').append('<div style="float:left;" class="temp_add_btn_html">'+
                '<button type="button" class="btn btn-xs green-meadow bpPaper001AddMainRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important" onclick="javascript:;"><i class="icon-plus3 icon-plus3-paper001" style="font-size:9px"></i></button>'+
                '<button type="button" class="ml2 btn btn-xs green-meadow bpPaper001AddMainSubRow bpPaper001AddMainRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#89d7c7" onclick="javascript:;"><i class="icon-plus3 icon-plus3-paper001" style="font-size:9px"></i></button>'+
            '</div>');
        }
    });       

    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bpPaper001AddMainRow_<?php echo $this->methodId; ?>', function(e) {
        var $this = $(this), $parent;
        var isSubRow = $this.hasClass('bpPaper001AddMainSubRow') ? true : false;
        
        if ($this.closest('div.theme-grid').length === 0) {
            $parent = $this.closest('fieldset');
            if ($parent.length === 0) {
                $parent = $this.closest('div[data-section-path]');
            } 
        } else {
            $parent = $this.closest('div.theme-grid');
        }
        
        var $getTable = $parent.find('.bprocess-table-dtl:eq(0)');
        var $getTableBody = $getTable.find('> .tbody');
        var $groupPath = $getTable.attr('data-table-path');
        var addRowNum = $this.prev('input.bp-add-one-row-num');

        $.ajax({
            type: 'post',
            url: 'mdcommon/renderBpDtlRow',
            data: {processId: <?php echo $this->methodId; ?>, uniqId: $('#bp-window-<?php echo $this->methodId; ?>').attr('data-bp-uniq-id'), rowId: $getTable.attr('data-row-id')},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (dataStr) {
                var $html = $('<div />', {html: dataStr});
                    $html.find('.bp-detail-row:eq(0)').addClass('display-none added-bp-row addNewRowIndexPaper001_'+addNewRowIndexPaper001);                    

                    if (isEditMode_<?php echo $this->methodId; ?>) {
                        $html.find("input[data-path*='rowState']").val('added');   
                    }

                    var $bpRowPaper001 = $this.closest('.bp-detail-row');
                    var paragraphNumber = $bpRowPaper001.find('input[data-path="conContractTemplate.paragraphNumber"]').val();
                    var conContractTemplateParentId = $bpRowPaper001.find('input[data-path="conContractTemplate.parentId"]').val() ? $bpRowPaper001.find('input[data-path="conContractTemplate.parentId"]').val() : $bpRowPaper001.find('input[data-path="conContractTemplate.id"]').val();
                    var conContractTemplateContractChapterId = $bpRowPaper001.find('input[data-path="conContractTemplate.contractChapterId"]').val();
                    var conContractTemplateContractChildParentId = $bpRowPaper001.find('input[data-path="conContractTemplate.childParentId"]').val();
                    var conContractTemplateContractParagraphTemplateId = $bpRowPaper001.find('input[data-path="conContractTemplate.paragraphTemplateId"]').val();
                    $bpRowPaper001.after($html.html());
                    
                    var $lastRow = $getTableBody.find('> .bp-detail-row.addNewRowIndexPaper001_'+addNewRowIndexPaper001);
                    Core.initBPDtlInputType($lastRow);
                    var paragraphNumberArr = paragraphNumber.split('.');
                    if (paragraphNumberArr[0]) {
                        var ppp = isSubRow ? paragraphNumberArr[0]+'.'+paragraphNumberArr[1] : paragraphNumberArr[0];
                        if (paragraphNumberArr.length == 2 && isSubRow) {
                            if (!editJsonSavedData.length) {
                                conContractTemplateContractChildParentId = conContractTemplateContractParagraphTemplateId;
                                $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="paragraphNumber2"]').val(ppp);
                            } else {
                                conContractTemplateParentId = $bpRowPaper001.find('input[data-path="conContractTemplate.id"]').val();
                            }
                        }
                        $lastRow.find('input[data-path="conContractTemplate.parentId"]').val(conContractTemplateParentId);
                        $lastRow.find('input[data-path="conContractTemplate.contractChapterId"]').val(conContractTemplateContractChapterId);          
                        $lastRow.find('input[data-path="conContractTemplate.childParentId"]').val(conContractTemplateContractChildParentId);          
                        $lastRow.find('input[data-path="conContractTemplate.paragraphTemplateId"]').val(conContractTemplateContractParagraphTemplateId);          
                        if (editJsonSavedData.length) {           
                            widget_detail_frame_paper_001_numbering_fromexp(ppp, conContractTemplateParentId, isSubRow);
                        }
                    }
                    addNewRowIndexPaper001++;
                    
                    if ($this.closest('.bprocess-table-dtl').hasClass('cool-row')) {
                        $lastRow.find('a.bp-remove-row').after($this.clone());
                        $this.remove();
                    }                    

                    if ($this.closest('.bp-template-wrap').length === 0) {

                        $lastRow.find('select.linked-combo').each(function () {
                            if ($(this).attr('data-out-param').indexOf('.') !== -1) {
                                $(this).trigger('change');
                            }
                        });
                        $lastRow.find('input.linked-combo').each(function () {
                            if ($(this).attr('data-out-param').indexOf('.') !== -1) {
                                $(this).trigger('change');
                            }
                        });
                        $lastRow.find('input[data-in-param]').each(function () {
                            var $thisLp = $(this);
                            var dataInParam = $thisLp.attr('data-in-param').split('|');
                            var dataInParamLength = dataInParam.length;
                            var linkedFieldIsEmpty = true;
                            for (var ip = 0; ip < dataInParamLength; ip++) {
                                if ($("input[data-path='"+dataInParam[ip]+"']", bp_window_<?php echo $this->methodId; ?>).val() === '') {
                                    linkedFieldIsEmpty = false;
                                }
                            }
                            if (linkedFieldIsEmpty) {
                                setBpRowParamEnable(bp_window_<?php echo $this->methodId; ?>, $thisLp, $thisLp.attr('data-path'));
                            }
                        });
                    }
            
                    partialExpressionStart_<?php echo $this->methodId; ?>($lastRow);
                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($lastRow, $groupPath, false, true);
                    dtlAggregateFunction_<?php echo $this->methodId; ?>();

                    var $el = $getTableBody.find('> .bp-detail-row:not(.removed-tr)'), len = $el.length, i = 0;
                    for (i; i < len; i++) { 
                        $($el[i]).find('td:first > span').text(i + 1);
                    }
                    bpSetRowIndex($parent);

                    $getTableBody.find('> .bp-detail-row.display-none').removeClass('display-none');
                    enableBpDetailFilterByElement($getTable);
                    bpDetailHideShowFields($getTable);
                    bpDetailFreeze($getTable);
                    $getTable.closest('.bp-overflow-xy-auto').animate({
                        scrollTop: 10000
                    }, 0);
                    
                    var $focusElement = $lastRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, select.select2):visible:first');
                    
                    if ($focusElement.length) {
                        $focusElement.focus().select();
                    } else {
                        $focusElement = $lastRow.find('textarea:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled):visible:first');
                        if ($focusElement.length) {
                            $focusElement.focus().select();
                        }
                    }                
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.unblockUI();
        });
    });      
    
    function initJstree_widget_detail_frame_paper_001(data) {
        var $jsTreeFilter = $('div.dataviewtreeview_processdetail_filter');
        if ($jsTreeFilter) {   
            $jsTreeFilter.jstree({
                "core": {
                    "themes": {
                        "responsive": true, 
                        "icons": false, 
                        "dots": false
                    },
                    "check_callback": function (op, node, par, pos, more) {
                        if (more && more.dnd) {
                            return more.pos !== "i" && par.id == node.parent;
                        }
                        return true;
                    },
                    "data": data
                },
                "types": {
                    "default": {
                        "icon": "icon-folder2 text-orange-300"
                    }
                },
                "plugins": ["types", "cookies", "dnd"]
                
            }).bind("select_node.jstree", function (e, data) {
                
                var nid = data.node.id === 'null' ? '' : data.node.id;

                var elem = $('.bpdtl-widget-detail_frame_paper_001-body').find('div[data-bp-detail-container]');
                var treeChildDatas = data.node.original.childdata;
                
                $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['parentid']; ?>"]').val(nid);
                var getNumbering = $jsTreeFilter.find('li#'+nid).find('.jstree-numbering-cls').text().replace('.', '');
                $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['numberingcolumn']; ?>"]').val(getNumbering);
                
//                if (data.node.original.rowdata[_parentId] != null) {
//                }
                var $table = $('.bpdtl-widget-detail_frame_paper_001-body').find('table.bprocess-table-dtl'), 
                    $tbody = $table.find('.tbody:eq(0)'), 
                    $rows = $tbody.find('.bp-detail-row');

                var $filteredRows = $rows.filter(function() {
                    var $thisRow = $(this), isDoneRows = false;
                    var filterValue = $thisRow.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val();

                    if (filterValue == nid) {
                        isDoneRows = true;
                    }

                    return isDoneRows;
                });
                
                $rows.css({display: 'none'});

                if (nid === 'all') {
                    $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['parentid']; ?>"]').val('');
                    $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['numberingcolumn']; ?>"]').val('');
                    
                    for (let ii = 0; ii < editJsonData.length; ii++) { 
                        if (editJsonData[ii] && editJsonData[ii]['id'] !== 'all') {
                            treeChildDatas = editJsonData[ii].childdata;

                            $filteredRows = $rows.filter(function() {
                                var $thisRow = $(this), isDoneRows = false;
                                var filterValue = $thisRow.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val();

                                if (filterValue == editJsonData[ii].id) {
                                    isDoneRows = true;
                                }

                                return isDoneRows;
                            });                            

                            if ($filteredRows.length) {

                                $filteredRows.css({display: ''});

                            } else {                
                                selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, '<?php echo $this->methodId; ?>', '<?php echo $detailPath; ?>', '<?php echo issetParam($jsonConfig['dvid']); ?>', [editJsonData[ii].rowdata], 'autocomplete');
                            }

                            if (treeChildDatas.length) {

                                if (!$filteredRows.length) {
                                    var rowDatas = [];
                                    
                                    for (var t in treeChildDatas) { 
                                        treeChildDatas[t]['rowdata']['id'] = treeChildDatas[t]['rowdata'][_parentId];
                                        rowDatas[t] = treeChildDatas[t]['rowdata'];
                                    }
                                    
                                    selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, '<?php echo $this->methodId; ?>', '<?php echo $detailPath; ?>', '<?php echo issetParam($jsonConfig['dvid']); ?>', rowDatas, 'autocomplete');
                                }
                            }
                        }
                    }
                    return;
                }                

                if ($filteredRows.length) {

                    $filteredRows.css({display: ''});

                } else {                
                    selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, '<?php echo $this->methodId; ?>', '<?php echo $detailPath; ?>', '<?php echo issetParam($jsonConfig['dvid']); ?>', [data.node.original.rowdata], 'autocomplete');
                }
                
                if (treeChildDatas.length) {
        
                    if (!$filteredRows.length) {
                        var rowDatas = [];
                        
                        for (var t in treeChildDatas) { 
                            treeChildDatas[t]['rowdata']['id'] = treeChildDatas[t]['rowdata'][_parentId];
                            rowDatas[t] = treeChildDatas[t]['rowdata'];
                        }
                        
                        selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, '<?php echo $this->methodId; ?>', '<?php echo $detailPath; ?>', '<?php echo issetParam($jsonConfig['dvid']); ?>', rowDatas, 'autocomplete');
                    }
                }
                
            }).bind('ready.jstree', function (e, data) {
                
                setTimeout(function() {
                    var $jstreeOpen = $jsTreeFilter.find('.jstree-open');
                    var $jstreeClicked = $jsTreeFilter.find('.jstree-clicked');
                    var $jstreeAnchor = $jsTreeFilter.find('.jstree-anchor');

                    if ($jstreeClicked.length) {
                        $jstreeClicked.focus();
                    }

                    if ($jstreeOpen.length) {
                        $jsTreeFilter.animate({
                            scrollTop: Number($jstreeOpen.offset().top) - 150
                        }, 1000);
                    }
                    
                    widget_detail_frame_paper_001_numbering($jstreeAnchor);
                    
                }, 1);
                
            }).on('move_node.jstree', function(e, data) {
                var $jstreeAnchor = $jsTreeFilter.find('.jstree-anchor');
                widget_detail_frame_paper_001_numbering($jstreeAnchor);
            });         

            $.contextMenu({
                selector: 'div.dataviewtreeview_processdetail_filter ul.jstree-container-ul li.jstree-node',
                callback: function (key, opt) {
                    if (key === 'delcontract') {
                        var dialogName='#deleteConfirmPdtl';
                        if(!$(dialogName).length){
                            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                        }
                        $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
                        $(dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Сануулах',
                            width: '350',
                            height: 'auto',
                            modal: true,
                            buttons: [
                                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function(){
                                    var liId = opt.$trigger.attr('id');
                                    var $table = $('.bpdtl-widget-detail_frame_paper_001-body').find('table.bprocess-table-dtl'), 
                                        $tbody = $table.find('.tbody:eq(0)'), 
                                        $rows = $tbody.find('.bp-detail-row');                            
                                    var $filteredRows = $rows.filter(function() {
                                        var $thisRow = $(this), isDoneRows = false;
                                        var filterValue = $thisRow.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val();

                                        if (filterValue == liId) {
                                            isDoneRows = true;
                                        }

                                        return isDoneRows;
                                    });                            

                                    if (Object.keys(editJsonSavedData).length) {
                                        $filteredRows.css({display: 'none'});
                                        $filteredRows.find('input[data-field-name="rowState"]').val('removed');
                                    } else {
                                        if ($filteredRows) $filteredRows.remove();
                                    }

                                    for (let ii = 0; ii < editJsonData.length; ii++) { 
                                        if (editJsonData[ii] && editJsonData[ii]['id'] == liId) {
                                            delete editJsonData[ii];
                                        }
                                    }                                    
                                    
                                    opt.$trigger.remove();

                                    var $jstreeAnchor = $('div.dataviewtreeview_processdetail_filter').find('.jstree-anchor');
                                    widget_detail_frame_paper_001_numbering($jstreeAnchor);                                    
                                    $(dialogName).dialog('close');
                                }},
                                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function(){
                                    $(dialogName).dialog('close');
                                }}
                            ]
                        });
                        $(dialogName).dialog('open');                        
                    }
                },
                items: {
                    "delcontract": {name: "Устгах", icon: "trash"}
                }
            });            
        }
    }
    function rowsFillAfterLoad_<?php echo $this->methodId; ?>_<?php echo $this->paramConfig['code']; ?>() {
        var $jsTreeFilter = $('div.dataviewtreeview_processdetail_filter');
        var $jstreeAnchor = $jsTreeFilter.find('.jstree-anchor');
        // /widget_detail_frame_paper_001_numbering($jstreeAnchor);
    }
    function widget_detail_frame_paper_001_numbering_fromexp(number, parentId, isSubRow) {
        var $table = $('.bpdtl-widget-detail_frame_paper_001-body').find('table.bprocess-table-dtl'), 
            $tbody = $table.find('.tbody:eq(0)'), 
            $rows = $tbody.find('.bp-detail-row');

        if (isSubRow) {
            var $filteredRows = $rows.filter(function() {
                var $thisRow = $(this), isDoneRows = false;
                var filterValue = $thisRow.find('input[data-field-name="parentId"]').val();

                if (filterValue == parentId) {
                    isDoneRows = true;
                }

                return isDoneRows;
            });              

            $filteredRows.each(function(i) {
                var $this = $(this);
                $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number + '.' + (++i));
            });

        } else {

            var $filteredRows = $rows.filter(function() {
                var $thisRow = $(this), isDoneRows = false;
                var filterValue = $thisRow.find('input[data-field-name="parentId"]').val();

                if (filterValue == parentId) {
                    isDoneRows = true;
                }

                return isDoneRows;
            });              
        
            $filteredRows.each(function(i) {
                var $this = $(this);
                $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number + '.' + (++i));
                var $filteredRowsChild = $rows.filter(function() {
                    var $thisRow = $(this), isDoneRows = false;
                    var filterValue = $thisRow.find('input[data-field-name="parentId"]').val();

                    if (filterValue == $this.find('input[data-field-name="id"]').val() && filterValue != '') {
                        isDoneRows = true;
                    }

                    return isDoneRows;
                });        
                if ($filteredRowsChild.length) {
                    $filteredRowsChild.each(function(ii) {
                        var $thisChild = $(this);
                        $thisChild.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number + '.' + i + '.' + (++ii));
                    });                    
                }
            });
        }
    }
    
    function widget_detail_frame_paper_001_numbering_fromexp_add(number, parentId, number2) {
        var $table = $('.bpdtl-widget-detail_frame_paper_001-body').find('table.bprocess-table-dtl'), 
            $tbody = $table.find('.tbody:eq(0)'), 
            $rows = $tbody.find('.bp-detail-row');
    
        var $filteredRows = $rows.filter(function() {
            var $thisRow = $(this), isDoneRows = false;
            var filterValue = $thisRow.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val();

            if (filterValue == parentId && ($thisRow.find('input[data-field-name="<?php echo $dtlParentId; ?>"]').val() != '' || $thisRow.find('input[data-field-name="<?php echo $filterColumn1; ?>"]').val() == '')) {
                isDoneRows = true;
            }

            return isDoneRows;
        });    
        
        console.log($filteredRows)
        $filteredRows.each(function(i) {
            var $this = $(this);
            if (!i) {
                $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number);
            } else {
                $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number + '.' + i);
                var $filteredRowsChild = $rows.filter(function() {
                    var $thisRow = $(this), isDoneRows = false;
                    var filterValue = $thisRow.find('input[data-field-name="<?php echo $childParentId; ?>"]').val();

                    if (filterValue == $this.find('input[data-field-name="<?php echo $filterColumn1; ?>"]').val()) {
                        isDoneRows = true;
                    }

                    return isDoneRows;
                });        
                if ($filteredRowsChild.length) {
                    $filteredRowsChild.each(function(ii) {
                        var $thisChild = $(this);
                        if (number2) {
                            $thisChild.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number2 + '.' + (++ii));
                        } else {
                            $thisChild.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number + '.' + i + '.' + (++ii));
                        }
                    });                    
                }
            }
        });
        if (number2) {
            $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="paragraphNumber2"]').val('');
        }
    }    
    function widget_detail_frame_paper_001_numbering($jstreeAnchor) {
        if ($jstreeAnchor.length) {
                        
            var len = $jstreeAnchor.length, i = 0;

            for (i; i < len; i++) { 

                if (i) {
                    var $subElement = $($jstreeAnchor[i]);
                    var $numberingElement = $subElement.find('span.jstree-numbering-cls');
                    var ii = i - 1;
                    var n = ii + 1;

                    if ($numberingElement.length) {
                        $numberingElement.text(n + '.');
                    } else {
                        $subElement.prepend('<span class="jstree-numbering-cls">'+n+'.</span>');
                    }
                    
                    <?php
                    if ($numberingColumn) {
                    ?>
                    widget_detail_frame_paper_001_setNumbering($subElement, n);
                    <?php
                    }
                    ?>
                }
            }
        }
    }
    function widget_detail_frame_paper_001_setNumbering($element, n) {
        var $table = $('.bpdtl-widget-detail_frame_paper_001-body').find('table.bprocess-table-dtl'), 
            $tbody = $table.find('.tbody:eq(0)'), 
            $rows = $tbody.find('.bp-detail-row'),
            $node = $element.closest('li.jstree-node'), 
            nodeId = $node.attr('id');
        
        var $filteredRows = $rows.filter(function() {
            var $thisRow = $(this), isDoneRows = false;
            var filterValue = $thisRow.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val();

            if (filterValue == nodeId) {
                isDoneRows = true;
            }

            return isDoneRows;
        });
        
//        $filteredRows.each(function(i) {
//            var $this = $(this);
//            if (!i) {
//                $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(n);
//            } else {
//                $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(n + '.' + i);
//            }
//        });
//            
//        if ($tbody.find('tr').length > 1) {                
//            var el = $rows, len = el.length, i = 0, $colIndex = $filteredRows.eq(0).find('input[data-field-name="<?php echo $numberingColumn; ?>"]').closest('td').index();            
//            var rows = $rows.toArray().sort(bpComparer($colIndex, 'text'));
//            
//            for (var i = 0; i < rows.length; i++) {
//                $tbody.append(rows[i]);
//            }                
//        }        
    }
    function search_widget_detail_frame_paper_001_treeview(valueInput) {
        var $table = $('.bpdtl-widget-detail_frame_paper_001-body').find('table.bprocess-table-dtl'), 
            $tbody = $table.find('tbody:eq(0)');
        var value = valueInput.trim().toLowerCase();

        $tbody.find("> tr").each(function(index) {
            var $row = $(this);
            if (valueInput == 'all') {
                $row.show();
            } else {
                var id = $row.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val();
                id = id.trim().toLowerCase();                

                if (id.indexOf(value) === -1) {
                    $row.hide();
                } else {
                    $row.show();
                }
            }
        });
    };    
    function detail_frame_paper_001_basket_function(rows) {
        if (rows) {
            var filterHeaderPathValue = $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('[data-field-name="<?php echo $filterHeaderColumn; ?>"]').val();
            var rowsWithChild = [];
            $('div.dataviewtreeview_processdetail_filter').jstree("destroy");
            
            rows.forEach(row => {
                $.ajax({
                    type: "post",
                    url: "api/callDataview",
                    data: {
                        dataviewId: "<?php echo issetParam($jsonConfig['dvid']) ?>",
                        criteriaData: {
                            filterId: [{operator: "=", operand: row.id}],
                            "<?php echo $filterHeaderColumn; ?>": [{operator: "=", operand: filterHeaderPathValue[0]}]
                        }
                    },
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        Core.blockUI({message: "Loading...", boxed: true});
                    },
                    success: function (dataSub) {
                        if (dataSub.status == 'success' && dataSub.result.length) {
                            rowsWithChild = rowsWithChild.concat(dataSub.result);
                        }
                        Core.unblockUI();
                    }
                });            
            });
            
            var resultTree = resolveParentChild_detail_frame_paper_001(rowsWithChild, 'basket');  
            
            // if (Object.keys(editJsonSavedData).length) {
                editJsonData = editJsonData.concat(resultTree);
                initJstree_widget_detail_frame_paper_001(editJsonData);
            // } else {                        
            //     initJstree_widget_detail_frame_paper_001(resultTree);
            // }
        }
        
        return true;
    }
    function resolveParentChild_detail_frame_paper_001(rows, basket) {
        var data = [], n = 0, isExist, isExistAll = false;

        if (editJsonData) {
            for (let ii = 0; ii < editJsonData.length; ii++) { 
                if (editJsonData[ii] && editJsonData[ii]['id'] === 'all') {
                    isExistAll = true;
                }
            }
        
            if (!isExistAll) {
                data[n] = {
                    'id': 'all', 
                    'text': 'Бүгд'
                };
                n++;
            }     
        }     
        
        if (Object.keys(editJsonSavedData).length && basket !== 'basket') {
            data[n] = {
                'id': 'all', 
                'text': 'Бүгд'
            };
            n++;            
        }

        for (let i = 0; i < rows.length; i++) { 
            isExist = false;       
            
            if (rows[i].hasOwnProperty(_parentId) && (rows[i][_parentId] == null || rows[i][_parentId] == '')) { 
                if (Object.keys(editJsonSavedData).length && basket !== 'basket') {         
                    data[n] = {
                        'id': rows[i]['<?php echo Str::lower($filterColumn); ?>'], 
                        'text': gridHtmlDecode(rows[i]['name']).replace(/<\/?[^>]+(>|$)/g, ""), 
                        'rowdata': rows[i], 
                        'childdata': resolveParentChild2_detail_frame_paper_001(rows, rows[i]['<?php echo Str::lower($filterColumn); ?>'])
                    };        
                    n++;                                
                } else {
                    
                    for (let ii = 0; ii < editJsonData.length; ii++) { 
                        if (editJsonData[ii] && editJsonData[ii]['id'] === rows[i]['id']) {
                            isExist = true;
                        }
                    }                    

                    if (!isExist) {
                        data[n] = {
                            'id': rows[i]['id'], 
                            'text': gridHtmlDecode(rows[i]['name']).replace(/<\/?[^>]+(>|$)/g, ""), 
                            'rowdata': rows[i], 
                            'childdata': resolveParentChild2_detail_frame_paper_001(rows, rows[i]['id'])
                        };
                        n++;
                    }
                }
                /*
                data[n]['children'] = resolveParentChild2_detail_frame_paper_001(rows, rows[i]['id']);
                */                
            }
        }    
        
        return data;    
    }
    function resolveParentChild2_detail_frame_paper_001(rows, parentid) {
        var data = [], n = 0;
        
        for (let i = 0; i < rows.length; i++) {
            
            if (rows[i][_parentId] == parentid) {
                data[n] = {
                    'id': rows[i]['id'], 
                    'text': gridHtmlDecode(rows[i]['name']), 
                    'rowdata': rows[i] 
                };
                n++;
            }
        }    
        
        return data;    
    }
    function widget_detail_frame_paper_001_remove_treeview() {
        $('div.dataviewtreeview_processdetail_filter').empty();
    } 
    var addNewRowIndexPaper001 = 1;  
</script>
<?php
}
?>