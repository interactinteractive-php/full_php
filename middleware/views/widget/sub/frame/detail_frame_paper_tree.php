<?php
$jsonConfig = issetParamArray($this->paramConfig['jsonConfig']);
?>
<div class="bpdtl-widget-detail_frame_paper_tree-frame d-flex">
    <?php 
    if ($jsonConfig) { 
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
        {customLocationAddBtn}
        <div class="dataviewtreeview_processdetail_filter"></div>
    </div>
    <?php 
    } 
    ?>
    <div class="bpdtl-widget-detail_frame_paper_tree-body" style="width: 100%;">
        {content}
    </div>
    <div class="dataviewtreeview_processdetail_filter_wrapper d-none" style="margin-right: 0px;margin-left: 20px;">
        <div style="float: right;">
            <a href="javascript:;" onClick="closeCommentSide()" title="Хаах"><i class="icon-cross3" style="color: #333;"></i></a>
        </div>
        <div class="row-comment mt-3"></div>
    </div>    
</div>

<style type="text/css">
.dataviewtreeview_processdetail_filter_wrapper .common-comment-action-btn {
    right: 58px !important;
}
.dataviewtreeview_processdetail_filter_wrapper {
    flex: 0 0 350px;
    margin-right: 20px;    
    background: #fff;
    box-shadow: 0 0.5mm 2mm rgb(0 0 0 / 30%);
    padding: 15px;    
    overflow: hidden;
}
.bpdtl-widget-detail_frame_paper_tree .form-control {
    border: none;
}
.bpdtl-widget-detail_frame_paper_tree-frame {
    padding: 15px; 
    background-color: #efefef;
}
.bpdtl-widget-detail_frame_paper_tree-body {
    background: white;
    box-shadow: 0 0.5mm 2mm rgb(0 0 0 / 30%);
    padding: 15px;
}
.bpdtl-widget-detail_frame_paper_tree-body table.bprocess-table-dtl, 
.bpdtl-widget-detail_frame_paper_tree-body table.kpi-dtl-table {
    table-layout: fixed;
}
.bpdtl-widget-detail_frame_paper_tree-body table.bprocess-table-dtl > thead {
    display: none;
}

.bpdtl-widget-detail_frame_paper_tree-body table.bprocess-table-dtl > tbody > tr > td:first-of-type, 
.bpdtl-widget-detail_frame_paper_tree-body table.kpi-dtl-table > tbody > tr > td:first-of-type {
    width: 30px;
    min-width: 30px;
}
.bpdtl-widget-detail_frame_paper_tree-body .bp-overflow-xy-auto {
    border: none !important;
}
.bpdtl-widget-detail_frame_paper_tree-body table.bprocess-table-dtl>tbody>tr.currentTarget>td {
    border-bottom: none !important;
}
.bpdtl-widget-detail_frame_paper_tree-body .table-bordered td {
    border: none !important;
}
.bpdtl-widget-detail_frame_paper_tree-body .table-bordered {
    border: none !important;
}
.bpdtl-widget-detail_frame_paper_tree-body .table-hover tbody tr:hover {
    color: inherit;
    background-color: inherit; 
}
.bpdtl-widget-detail_frame_paper_tree-body table.bprocess-theme1.table-hover>tbody>tr:hover>td {
    background-color: inherit;
}
.dataviewtreeview_processdetail_filter_wrapper .jstree-ocl {
    display: none;
}
.dataviewtreeview_processdetail_filter_wrapper .jstree-anchor {
    display: block;
    min-height: 25px!important;
    padding-top: 5px;
    padding-left: 4px;
}
.dataviewtreeview_processdetail_filter_wrapper .jstree-numbering-cls {
    padding-right: 10px;
}
.bpdtl-widget-detail_frame_paper_tree-placeholder {
    border: 1px dotted black;
    margin: 0 1em 1em 0;
    height: 40px;
}
.bpdtl-widget-detail_frame_paper_tree-dragicon {
    position: absolute;
    margin-top: 8px;
    margin-left: -2px;
    color: #555;
    cursor: move;
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
    $treeParentId = issetParam($jsonConfig['treeparentid']);
    $treeId = issetParam($jsonConfig['treeid']);
    $isComment = issetParam($jsonConfig['iscomment']);
?>
<script type="text/javascript">
    var _parentId = '<?php echo Str::lower($jsonConfig['parentid']) ?>';
    var editJsonSavedData = <?php echo json_encode($groupedData) ?>;
    var editJsonData = resolveParentChild_detail_frame_paper_tree(editJsonSavedData, '');
    var uniqId = $('#bp-window-<?php echo $this->methodId; ?>').attr('data-bp-uniq-id');
    var allSelectText = false;
    
    initJstree_widget_detail_frame_paper_tree(editJsonData);          

    if (Object.keys(editJsonSavedData).length) {
        var $table = $('#bp-window-<?php echo $this->methodId; ?> .bprocess-table-dtl'), 
            $tbody = $table.find('.tbody:eq(0)'), 
            $rows = $tbody.find('.bp-detail-row');
                    
        $rows.each(function() {
            var $thisRow = $(this);
            var paragraphNumberArr = $thisRow.find('input[data-field-name="<?php echo $numberingColumn; ?>"]');
            var ttt = paragraphNumberArr.val().split('.');
            if (Object.keys(ttt).length > 1) {
                paragraphNumberArr.before('<i class="fa fa-arrows-v bpdtl-widget-detail_frame_paper_tree-dragicon"></i>');
            }            
        });         
    };         

    $("#bp-window-<?php echo $this->methodId; ?> .bprocess-table-dtl > .tbody").sortable({
        cursor: 'move',
        connectWith: "#bp-window-<?php echo $this->methodId; ?> .bprocess-table-dtl > .tbody",
        handle: '.bpdtl-widget-detail_frame_paper_tree-dragicon',
        cancel: "",
        placeholder: "bpdtl-widget-detail_frame_paper_tree-placeholder",
        start: function(event, ui){
            var pval = $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['parentid']; ?>"]').val();
            var nval = $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['numberingcolumn']; ?>"]').val();            
            if (pval == '' && nval == '') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Заалтаа сонгоно уу!',
                    type: 'warning',
                    sticker: false
                });     
                return false;            
            } 
        },        
        stop: function(event, ui) {
            var $currElem = $(ui.item);
            var $prevElem = $currElem.prev();
            var pval = $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['parentid']; ?>"]').val();
            var nval = $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['numberingcolumn']; ?>"]').val();                  
            $currElem.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val($prevElem.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val());
            $currElem.closest('.tbody').find('> .bp-detail-row:not(.hidden)').find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val('');
            widget_detail_frame_paper_tree_numbering_fromexp_add(nval, pval);
        }
    });     
    
    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bp-detail-row .texteditor_clicktoedit_tinymceInit[contenteditable="true"]', function(e) {
        $('#bp-window-<?php echo $this->methodId; ?>').find('.temp_add_style_btn_html').remove();
        var $this = $(this);
        $this.parent().prepend('<div style="position: absolute;margin-top: -30px;background-color: #333;padding: 3px;border-radius: 5px;" class="temp_add_style_btn_html">'+
            '<button type="button" title="Bold" class="ml2 btn btn-xs green-meadow bpPaper001AddBoldRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#333333" onclick="javascript:;"><i class="fa fa-bold iconplus3-paper001" style="font-size:10px"></i></button>'+
            '<button type="button" title="Italic" class="ml2 btn btn-xs green-meadow bpPaper001AddItalicRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#333333" onclick="javascript:;"><i class="fa fa-italic iconplus3-paper001" style="font-size:10px"></i></button>'+
            '<button type="button" title="Underline" class="ml2 mr2 btn btn-xs green-meadow bpPaper001AddUnderlineRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#333333" onclick="javascript:;"><i class="fa fa-underline iconplus3-paper001" style="font-size:10px"></i></button>'+
            '<button type="button" data-refstructureid="<?php echo issetParam($jsonConfig['refstructureid']) ?>" title="Сэтгэгдэл" class="d-none mr2 ml2 btn btn-xs green-meadow bpPaper001AddCommentRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#333333" onclick="javascript:;"><i class="fa fa-comment iconplus3-paper001" style="font-size:10px"></i></button>'+
        '</div>');

        <?php if ($isComment) { ?>
            var $parent = $this.closest('.bp-detail-row');
            var sourceId = $parent.find('> div > input[data-field-name="id"]').val();        
            var refId = "<?php echo issetParam($jsonConfig['refstructureid']) ?>";

            $.ajax({
                    type: 'post',
                    url: 'mdwebservice/renderEditModeBpCommentTab',
                    data: {uniqId: getUniqueId(''), refStructureId: refId, sourceId: sourceId},
                    beforeSend: function() {
                        Core.blockUI({
                          message: "Loading...",
                          boxed: true
                        });
                    },
                    success: function(data) {
                        Core.unblockUI();
                        $('.dataviewtreeview_processdetail_filter_wrapper:last-child').removeClass('d-none');
                        $('#bp-window-<?php echo $this->methodId; ?>').find('.row-comment').empty().append(data);
                    },
                    error: function() {
                        alert('Error');
                    }
                });            
        <?php } ?>   
    });
    
    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bpPaper001AddBoldRow_<?php echo $this->methodId; ?>', function(e) {
        var $this = $(this);
        var $parent = $this.closest('.input-group')
        var selectionTxt = getSelectionText();
        pasteHtmlAtCaret('<strong>'+selectionTxt+'</strong>');        
        $parent.find('textarea').val($parent.find('.texteditor_clicktoedit_tinymceInit').html());
    });
    
    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bpPaper001AddItalicRow_<?php echo $this->methodId; ?>', function(e) {
        var $this = $(this);
        var $parent = $this.closest('.input-group')        
        var selectionTxt = getSelectionText();
        pasteHtmlAtCaret('<i>'+selectionTxt+'</i>');
        $parent.find('textarea').val($parent.find('.texteditor_clicktoedit_tinymceInit').html());        
    });
    
    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bpPaper001AddUnderlineRow_<?php echo $this->methodId; ?>', function(e) {
        var $this = $(this);
        var $parent = $this.closest('.input-group')        
        var selectionTxt = getSelectionText();
        pasteHtmlAtCaret('<u>'+selectionTxt+'</u>');
        $parent.find('textarea').val($parent.find('.texteditor_clicktoedit_tinymceInit').html());                    
    });

    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bp-detail-row', function(e) {
        var $this = $(this);
        $thisCurr = $(e.target);

        if ($thisCurr.hasClass('bpPaper001AddMainRow_<?php echo $this->methodId; ?>') || $thisCurr.hasClass('iconplus3-paper001')) {
            return;
        }

        $this.closest('.tbody').find('.temp_add_btn_html').remove();
        var paragraphNumberArr = $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val().split('.');
        if (paragraphNumberArr.length == 1) {
            return;
        }
        $this.find('div[data-cell-path="conContractTemplate.paragraphNumber"]').append('<div style="float:left;" class="temp_add_btn_html">'+
            '<button type="button" class="btn btn-xs green-meadow bpPaper001AddMainRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important" onclick="javascript:;"><i class="icon-plus3 iconplus3-paper001" style="font-size:9px"></i></button>'+
            '<button type="button" title="Дэд мөр нэмэх" class="ml2 btn btn-xs green-meadow bpPaper001AddMainSubRow bpPaper001AddMainRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#89d7c7" onclick="javascript:;"><i class="icon-plus3 iconplus3-paper001" style="font-size:9px"></i></button>'+
            '<button type="button" title="Дэд мөр шилжүүлэх" class="ml2 btn btn-xs green-meadow bpPaper001PrevMoveMainSubRow bpPaper001AddMainRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#89d7c7" onclick="javascript:;"><i class="fa fa-arrow-left iconplus3-paper001" style="font-size:9px"></i></button>'+
            '<button type="button" title="Дэд мөр шилжүүлэх" class="ml2 btn btn-xs green-meadow bpPaper001MoveMainSubRow bpPaper001AddMainRow_<?php echo $this->methodId; ?>" style="padding: 0px 3px !important;background-color:#89d7c7" onclick="javascript:;"><i class="fa fa-arrow-right iconplus3-paper001" style="font-size:9px"></i></button>'+
        '</div>');
    });       

    $('#bp-window-<?php echo $this->methodId; ?>').on('click', '.bpPaper001AddMainRow_<?php echo $this->methodId; ?>', function(e) {
        var $this = $(this), $parent;
        var isSubRow = $this.hasClass('bpPaper001AddMainSubRow') ? true : false;
        var isMoveRow = $this.hasClass('bpPaper001MoveMainSubRow') ? true : false;
        var isPrevMoveRow = $this.hasClass('bpPaper001PrevMoveMainSubRow') ? true : false;
        var pval = $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['parentid']; ?>"]').val();
        var nval = $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['numberingcolumn']; ?>"]').val();                    

        if (pval == '' && nval == '') {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Заалтаа сонгоно уу!',
                type: 'warning',
                sticker: false
            });     
            return;            
        }
        
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

        if (isMoveRow) {
            var $bpRowPaper001 = $this.closest('.bp-detail-row');

            widget_detail_frame_paper_tree_previtem($bpRowPaper001, $bpRowPaper001.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(), function(r) {            
                $bpRowPaper001.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val(r.find('input[data-field-name="<?php echo $treeId; ?>"]').val());
                $bpRowPaper001.closest('.tbody').find('> .bp-detail-row:not(.hidden)').find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val('');
                widget_detail_frame_paper_tree_numbering_fromexp_add(nval, pval);           
            });     
            return;
        }

        if (isPrevMoveRow) {
            var $bpRowPaper001 = $this.closest('.bp-detail-row');
            var getCharSplit = $bpRowPaper001.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val().split('.');
            var getCharSplitLen = getCharSplit[Object.keys(getCharSplit).length - 1];

            widget_detail_frame_paper_tree_previtem($bpRowPaper001, $bpRowPaper001.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val().slice(0, -(getCharSplitLen.length + 1)), function(r) {            
                $bpRowPaper001.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val(r.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val());
                $bpRowPaper001.closest('.tbody').find('> .bp-detail-row:not(.hidden)').find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val('');
                widget_detail_frame_paper_tree_numbering_fromexp_add(nval, pval);           
            });     
            return;
        }

        $.ajax({
            type: 'post',
            url: 'mdcommon/renderBpDtlRow',
            data: {processId: <?php echo $this->methodId; ?>, uniqId: uniqId, rowId: $getTable.attr('data-row-id')},
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
                $bpRowPaper001.after($html.html());
                
                var $lastRow = $getTableBody.find('> .bp-detail-row.addNewRowIndexPaper001_'+addNewRowIndexPaper001);
                Core.initBPDtlInputType($lastRow);
                $lastRow.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').before('<i class="fa fa-arrows-v bpdtl-widget-detail_frame_paper_tree-dragicon"></i>');

                $lastRow.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val(pval);

                if (isSubRow) {
                    $lastRow.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val($bpRowPaper001.find('input[data-field-name="<?php echo $treeId; ?>"]').val());
                } else {
                    $lastRow.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val($bpRowPaper001.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val());
                }                    
                $bpRowPaper001.closest('.tbody').find('> .bp-detail-row:not(.hidden)').find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val('');

                widget_detail_frame_paper_tree_numbering_fromexp_add(nval, pval);       
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
    
    function initJstree_widget_detail_frame_paper_tree(data) {
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

                var elem = $('.bpdtl-widget-detail_frame_paper_tree-body').find('div[data-bp-detail-container]');
                var treeChildDatas = data.node.original.childdata;
                
                $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['parentid']; ?>"]').val(nid);
                var getNumbering = $jsTreeFilter.find('li#'+nid).find('.jstree-numbering-cls').text().replace('.', '');
                $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['numberingcolumn']; ?>"]').val(getNumbering);
                
//                if (data.node.original.rowdata[_parentId] != null) {
//                }
                var $table = $('.bpdtl-widget-detail_frame_paper_tree-body').find('div.bprocess-table-dtl'), 
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
                
                $rows.addClass('hidden');

                if (nid === 'all') {
                    $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['parentid']; ?>"]').val('');
                    $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="<?php echo $jsonConfig['numberingcolumn']; ?>"]').val('');
                    
                    var tind = 0, rowDatas = [];
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

                                $filteredRows.removeClass('hidden');
                                $filteredRows.find('input[data-field-name="rowState"]').val('added');
                                

                            } 
                            
                            if (treeChildDatas.length) {

                                if (!$filteredRows.length) {
                                    rowDatas[tind] = editJsonData[ii].rowdata;
                                    tind++;
                                    
                                    for (var t in treeChildDatas) { 
                                        treeChildDatas[t]['rowdata']['id'] = treeChildDatas[t]['rowdata'][_parentId];
                                        rowDatas[tind] = treeChildDatas[t]['rowdata'];
                                        tind++;
                                    }
                                }
                            } else {                
                                rowDatas[tind] = editJsonData[ii].rowdata;
                                tind++;          
                            }                  
                        }
                    }             
                    allSelectText = true;
                    selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, '<?php echo $this->methodId; ?>', '<?php echo $detailPath; ?>', '<?php echo issetParam($jsonConfig['dvid']); ?>', rowDatas, 'autocomplete');
                    return;
                }                

                if ($filteredRows.length) {

                    $filteredRows.removeClass('hidden');
                    $filteredRows.find('input[data-field-name="rowState"]').val('added');

                }               
                
                if (treeChildDatas.length) {
        
                    if (!$filteredRows.length) {
                        var rowDatas = [data.node.original.rowdata], rKey = 1;
                        
                        for (var t in treeChildDatas) { 
                            treeChildDatas[t]['rowdata']['id'] = treeChildDatas[t]['rowdata'][_parentId];
                            rowDatas[rKey] = treeChildDatas[t]['rowdata'];
                            rKey++;
                        }
                        
                        selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, '<?php echo $this->methodId; ?>', '<?php echo $detailPath; ?>', '<?php echo issetParam($jsonConfig['dvid']); ?>', rowDatas, 'autocomplete');
                    }
                } else {
                    if (!$filteredRows.length) {
                        selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, '<?php echo $this->methodId; ?>', '<?php echo $detailPath; ?>', '<?php echo issetParam($jsonConfig['dvid']); ?>', [data.node.original.rowdata], 'autocomplete');                    
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
                    
                    widget_detail_frame_paper_tree_numbering($jstreeAnchor, true);
                    
                }, 1);
                
            }).on('move_node.jstree', function(e, data) {
                var $jstreeAnchor = $jsTreeFilter.find('.jstree-anchor');
                widget_detail_frame_paper_tree_numbering($jstreeAnchor, true);
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
                                    var $table = $('.bpdtl-widget-detail_frame_paper_tree-body').find('div.bprocess-table-dtl'), 
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
                                        $filteredRows.addClass('hidden');
                                        $filteredRows.find('input[data-field-name="rowState"]').val('removed');
                                    } else {
                                        if ($filteredRows) $filteredRows.remove();
                                    }

                                    var editJsonDataTemp2 = [], editJsonDataTempIndex = 0;
                                    for (let ii = 0; ii < editJsonData.length; ii++) { 
                                        if (editJsonData[ii] && editJsonData[ii]['id'] != liId) {
                                            editJsonDataTemp2[editJsonDataTempIndex] = editJsonData[ii];
                                            editJsonDataTempIndex++;
                                        }
                                    }                        
                                    editJsonData = editJsonDataTemp2;             
                                    
                                    $('div.dataviewtreeview_processdetail_filter').jstree().delete_node([liId]);
                                    opt.$trigger.remove();

                                    var $jstreeAnchor = $('div.dataviewtreeview_processdetail_filter').find('.jstree-anchor');
                                    widget_detail_frame_paper_tree_numbering($jstreeAnchor, true);     
                                    // initJstree_widget_detail_frame_paper_tree(editJsonData);                    
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
        // var $jsTreeFilter = $('div.dataviewtreeview_processdetail_filter');
        // var $jstreeAnchor = $jsTreeFilter.find('.jstree-anchor');
        // /widget_detail_frame_paper_tree_numbering($jstreeAnchor);                 

        // $('.bprocess-table-dtl .bp-detail-row').draggable({
        //     containment: '.bprocess-table-dtl',
        //     cursor: 'move',
        //     helper: 'clone',
        //     zIndex: 999,
        //     revert: "invalid",
        //     scroll: true,
        //     drag: function(event, ui) {
        //     },
        //     stop: function(event, ui) {
        //     }        
        // });    

        // $('.bprocess-table-dtl .tbody').droppable({
        //     accept: '.bp-detail-row',
        //     helper: 'clone',
        //     hoverClass: 'drop-hover',
        //     drop: function(event, ui) {
        //         var dthis = $(this);
        //         // var currStatusId = ui.draggable.attr('data-status-id');
        //         // var nextStatusId = dthis.attr('data-status-id');
        //         console.log(dthis)
        //         dthis.prepend(ui.draggable);
        //     }
        // });        
        var $table = $('#bp-window-<?php echo $this->methodId; ?> .bprocess-table-dtl'), 
            $tbody = $table.find('.tbody:eq(0)'), 
            $rows = $tbody.find('.bp-detail-row');

        $rows.each(function() {
            var $thisRow = $(this);
            var paragraphNumberArr = $thisRow.find('input[data-field-name="<?php echo $numberingColumn; ?>"]');
            var ttt = paragraphNumberArr.val().split('.');
            if (Object.keys(ttt).length > 1) {
                paragraphNumberArr.before('<i class="fa fa-arrows-v bpdtl-widget-detail_frame_paper_tree-dragicon"></i>');
            }            
        });              
        if (allSelectText) {
            var $jstreeAnchor = $('div.dataviewtreeview_processdetail_filter').find('.jstree-anchor');
            widget_detail_frame_paper_tree_numbering($jstreeAnchor, true);            
        }
        allSelectText = false;
    }
    function widget_detail_frame_paper_tree_numbering_fromexp(number, parentId, isSubRow) {
        var $table = $('.bpdtl-widget-detail_frame_paper_tree-body').find('div.bprocess-table-dtl'), 
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
    function widget_detail_frame_paper_tree_numbering_fromexp_add(number, parentId, number2, level = 0, isMove = false) {
        var $table = $('.bpdtl-widget-detail_frame_paper_tree-body').find('.bprocess-table-dtl'), 
            $tbody = $table.find('.tbody:eq(0)'), 
            $rows = $tbody.find('.bp-detail-row');

        if (isMove) {
            var $filteredRowsByFilter = $rows.filter(function() {
                var $thisRow = $(this), isDoneRows = false;
                var filterValue = $thisRow.find('input[data-field-name="<?php echo $filterColumn; ?>"]').val();

                if (filterValue == isMove) {
                    isDoneRows = true;
                }

                return isDoneRows;
            });        
            $rows = $filteredRowsByFilter;    
        }
    
        var $filteredRows = $rows.filter(function() {
            var $thisRow = $(this), isDoneRows = false;
            var filterValue = $thisRow.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val();
            var filterIdValue = $thisRow.find('input[data-field-name="<?php echo $treeId; ?>"]').val();

            if ((filterValue == parentId || filterValue == '') && $thisRow.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val() == '' && (!$thisRow.hasClass('hidden') || isMove)) {
            // if ((filterValue == parentId || filterIdValue == parentId) && $thisRow.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val() == '' && (!$thisRow.hasClass('hidden') || isMove)) {
            // if ((filterValue == parentId || filterIdValue == parentId) && (!$thisRow.hasClass('hidden') || isMove)) {
                isDoneRows = true;
            }

            return isDoneRows;
        });    
        

        $filteredRows.each(function(i) {
            var $this = $(this), ii = i + 1, paramNumber;
            if (!i) {
                if (level) {
                    $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number+'.'+ii).css("margin-left", (level * 10)+10+"px");
                    paramNumber = number+'.'+ii;
                } else {
                    $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number);
                    paramNumber = number;
                }
            } else {
                if (level) {
                    $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number + '.' + ii).css("margin-left", (level * 10)+10+"px");
                    paramNumber = number+'.'+ii;
                } else {
                    $this.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val(number + '.' + i).css("margin-left", (level * 10)+10+"px");
                    paramNumber = number+'.'+i;
                }                       
            }         

            var treeiId = $this.find('input[data-field-name="<?php echo $treeId; ?>"]').val();
            $rows.each(function(){
                var $thisRowChild = $(this), isDoneRows = false;
                if ($thisRowChild.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val() == '' && (!$thisRowChild.hasClass('hidden') || isMove)) {
                    var filterValue = $thisRowChild.find('input[data-field-name="<?php echo $treeParentId; ?>"]').val(),
                        treeiCurrentId = $thisRowChild.find('input[data-field-name="<?php echo $treeId; ?>"]').val();

                    if (filterValue == treeiId) {
                        widget_detail_frame_paper_tree_numbering_fromexp_add(paramNumber, treeiId, null, level+1, isMove);
                    }
                }
            });   
        });
        if (number2) {
            $('div[data-process-id="<?php echo $this->methodId; ?>"]').find('.bp-header-param').find('input[data-field-name="paragraphNumber2"]').val('');
        }
    }    
    function widget_detail_frame_paper_tree_numbering($jstreeAnchor, isMove) {
        if ($jstreeAnchor.length) {
                        
            var len = $jstreeAnchor.length, i = 0, editJsonDataTemp = [];
                editJsonDataTemp[0] = editJsonData[0];

            if (isMove) {
                var $table = $('.bpdtl-widget-detail_frame_paper_tree-body').find('.bprocess-table-dtl'), 
                    $tbody = $table.find('.tbody:eq(0)');                 
                $tbody.find('> .bp-detail-row').find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val('');
            }

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
                        if (isMove) {
                            var $node = $subElement.closest('li.jstree-node'), 
                                nodeId = $node.attr('id');                            
                            widget_detail_frame_paper_tree_setNumbering($subElement, n);

                            for (var loop = 0; loop < editJsonData.length; loop++) {            
                                if (loop &&  editJsonData[loop] && nodeId == editJsonData[loop]['id']) {
                                    editJsonDataTemp[n] = editJsonData[loop];
                                }
                            }                            
                        }
                    <?php
                    }
                    ?>
                }
            }
            if (isMove) {
                editJsonData = editJsonDataTemp;
            }
        }
    }
    function widget_detail_frame_paper_tree_setNumbering($element, n) {
        var $node = $element.closest('li.jstree-node'), 
            nodeId = $node.attr('id');
        
        widget_detail_frame_paper_tree_numbering_fromexp_add(n, nodeId, null, 0, nodeId);
    }
    function search_widget_detail_frame_paper_tree_treeview(valueInput) {
        var $table = $('.bpdtl-widget-detail_frame_paper_tree-body').find('div.bprocess-table-dtl'), 
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
    function detail_frame_paper_tree_basket_function(rows) {
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
            
            var resultTree = resolveParentChild_detail_frame_paper_tree(rowsWithChild, 'basket');  
            
            // if (Object.keys(editJsonSavedData).length) {
                editJsonData = editJsonData.concat(resultTree);
                initJstree_widget_detail_frame_paper_tree(editJsonData);
            // } else {                        
            //     initJstree_widget_detail_frame_paper_tree(resultTree);
            // }
        }
        
        return true;
    }
    function resolveParentChild_detail_frame_paper_tree(rows, basket) {
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
                        'childdata': resolveParentChild2_detail_frame_paper_tree(rows, rows[i]['<?php echo Str::lower($filterColumn); ?>'])
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
                            'childdata': resolveParentChild2_detail_frame_paper_tree(rows, rows[i]['id'])
                        };
                        n++;
                    }
                }
                /*
                data[n]['children'] = resolveParentChild2_detail_frame_paper_tree(rows, rows[i]['id']);
                */                
            }
        }    
        
        return data;    
    }
    function resolveParentChild2_detail_frame_paper_tree(rows, parentid) {
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
    function widget_detail_frame_paper_tree_remove_treeview() {
        $('div.dataviewtreeview_processdetail_filter').jstree("destroy").empty();
        var $table = $('.bpdtl-widget-detail_frame_paper_tree-body').find('.bprocess-table-dtl');
        $table.find('.tbody:eq(0)').empty();
        editJsonData = [];             
    } 
    var addNewRowIndexPaper001 = 1;  

    function widget_detail_frame_paper_tree_previtem($row, number, callback) {
        var $prevElem = $row.prev();
        var paragraphNumberArr = number.split('.');
        var paragraphNumberPrevArr = $prevElem.find('input[data-field-name="<?php echo $numberingColumn; ?>"]').val().split('.');
        if (paragraphNumberArr.length == paragraphNumberPrevArr.length) {
            callback($prevElem);
        } else {
            widget_detail_frame_paper_tree_previtem($prevElem, number, callback);
        }
    }    
    function getSelectionText() {
        var text = "";
        if (window.getSelection) {
            text = window.getSelection().toString();
        } else if (document.selection && document.selection.type != "Control") {
            text = document.selection.createRange().text;
        }
        return text;
    }   
    function pasteHtmlAtCaret(html) {
        var sel, range;
        if (window.getSelection) {
            // IE9 and non-IE
            sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0);
                range.deleteContents();

                // Range.createContextualFragment() would be useful here but is
                // non-standard and not supported in all browsers (IE9, for one)
                var el = document.createElement("div");
                el.innerHTML = html;
                var frag = document.createDocumentFragment(), node, lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);

                // Preserve the selection
                if (lastNode) {
                    range = range.cloneRange();
                    range.setStartAfter(lastNode);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            }
        } else if (document.selection && document.selection.type != "Control") {
            // IE < 9
            document.selection.createRange().pasteHTML(html);
        }
    }
    function closeCommentSide() {
        $('.dataviewtreeview_processdetail_filter_wrapper:last-child').addClass('d-none');
    }
</script>
<?php
}
?>