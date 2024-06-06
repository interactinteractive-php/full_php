<div class="row">
    <div class="col-md-12 output-params-new-config-parent">
        
        <div class="table-toolbar xs-form mb5">
            <div class="row">
                <div class="col-md-5">
                    <div class="input-group quick-item float-left">
                        <div class="form-group-feedback form-group-feedback-left">
                            <?php echo Form::text(array('id' => 'processParamAddCode', 'class' => 'form-control process-output-param-add-code', 'placeholder' => $this->lang->line('META_00154'))); ?>
                            <div class="form-control-feedback form-control-feedback-lg">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                        <span class="input-group-append">
                            <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'commonMetaDataGrid(\'multi\', \'group\', \'autoSearch=1&metaTypeId='.Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$fieldMetaTypeId.'\', \'processOutputParamAddRows\', this);')); ?>
                        </span>
                    </div>
                    <?php echo Form::button(array('class' => 'btn red-sunglo btn-xs float-left ml50', 'value' => '<i class="fa fa-trash"></i> '.$this->lang->line('META_00002'), 'onclick' => 'deleteProcessOutputParamRows(this);')); ?>
                </div>
            </div>
        </div>
        
        <table style="width: 100%; table-layout: fixed">
            <tr>
                <td style="width: 100%; vertical-align: top;">
                    <div id="fz-process-output-params-option" class="freeze-overflow-xy-auto" style="border: 1px solid #dddddd;">
                        <table class="table table-sm table-hover output-params-new-config output-param-link-tree">
                            <thead>
                                <tr>
                                    <th class="middle text-left" style="min-width: 55px;max-width: 55px;width: 55px;"><input type="checkbox" class="notuniform param-check-all"></th>
                                    <th class="middle" style="min-width: 200px;">Path</th>
                                    <th style="min-width: 200px;"><?php echo $this->lang->line('META_00075'); ?></th>
                                    <th style="min-width: 130px;"><?php echo $this->lang->line('META_00145'); ?></th>
                                    <th style="min-width: 150px;"><?php echo $this->lang->line('META_00076'); ?></th>
                                    <th style="min-width: 62px; vertical-align: top;"><label><input type="checkbox" class="notuniform param-check-all"><br /><?php echo $this->lang->line('META_00003'); ?></label></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $this->paramsRender; ?>
                            </tbody>
                        </table>  
                    </div>   
                </td>
            </tr>
        </table>
         
    </div>
</div>

<style type="text/css">
table.output-params-new-config thead tr th {
    background: #E7E7E7;
    font-size: 12px !important;
    height: 23px !important;
    vertical-align: middle;
    line-height: 13px;
    text-align: center;
    border-bottom-width: 0;
}
table.output-params-new-config thead tr th label {
    font-weight: normal; 
    font-size: 12px;
    margin-top: 0;
}
table.output-params-new-config {
    color: #444 !important;
}
table.output-params-new-config > tbody > tr > td {
    font-size: 12px;
    line-height: 13px;
    padding: 4px;
    vertical-align: middle;
}
table.output-params-new-config > tbody > tr > td.stretchInput {
    padding: 0 !important;
}
table.output-params-new-config td .btn, 
table.output-params-new-config th .btn {
    margin-left: 3px;
    margin-right: 0;
    padding: 2px 5px;
}
table.output-params-new-config > tbody > tr.currentTarget > td {
    border-bottom: 1px solid #888;
}
.depth-pl0 {
    padding-left: 10px !important;
}
.depth-padding-left-1 {
    padding-left: 25px !important;
}
.depth-padding-left-2 {
    padding-left: 40px !important;
}
.depth-padding-left-3 {
    padding-left: 56px !important;
}
.depth-padding-left-4 {
    padding-left: 85px !important;
}
.depth-pl5 {
    padding-left: 105px !important;
}
.depth-padding-left-6 {
    padding-left: 120px !important;
}
.deleted-row > td {
    background-color: #e67171 !important;
}
.param-row-up-down {
    cursor: move !important;
}
</style>

<script type="text/javascript">
$(function(){
    
    var $dialogId = $('.output-params-new-config-parent');
    var lastChecked = null;
    
    processOutputParamInitFreeze();
    
    $.contextMenu({
        selector: 'table.output-param-link-tree > tbody > tr:not(.deleted-row)',
        callback: function(key, opt) {
            processOutputParamDeleteRow(opt.$trigger); 
        },
        items: {
            "delete": {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"} 
        }
    });
    
    $.contextMenu({
        selector: 'table.output-param-link-tree > tbody > tr.deleted-row',
        callback: function(key, opt) {
            processOutputParamRefreshRow(opt.$trigger);
        },
        items: {
            "refresh": {name: "<?php echo $this->lang->line('META_00020'); ?>", icon: "refresh"} 
        }
    });
    
    $('.param-check-all').on('click', function() {
        var $this = $(this);
        var $paramTable = $this.closest('table');
        var $paramCol = $this.closest('tr').children().index($this.closest('th'));
        var $paramIndex = $paramCol + 1;
        $paramTable.find('td:nth-child(' + $paramIndex + ') input:checkbox').prop('checked', $this.is(':checked'));
    });
    
    $('table.output-param-link-tree > tbody').on('click', 'tr', function(){
        var $this = $(this);
        $('table.output-param-link-tree tbody > tr.selected').removeClass('selected');
        $this.addClass('selected');
    });
    
    $('.output-param-link-tree > tbody').on('click', 'tr > td > span.tabletree-expander', function(){
    
        var $this = $(this);
        var $thisRow = $this.closest('tr');
        var rowId = $thisRow.attr('data-id');
            
        if ($this.hasClass('fa-plus')) {
            
            if ($thisRow.closest('tbody').find('tr.tabletree-parent-'+rowId).length === 0) {
                
                var outputPostData = {
                    processMetaDataId: '<?php echo $this->metaDataId; ?>', 
                    paramPath: $thisRow.find('input.process-param-path').val(), 
                    rowId: rowId, 
                    depth: Number($thisRow.attr('data-depth')) + 1, 
                    dataType: $thisRow.find('select.process-param-datatype').val(), 
                    isNew: $thisRow.find('input.process-param-isnew').val() 
                };
                
                if ($thisRow.find('input.process-param-newrowid').length) {
                    outputPostData['newRowId'] = $thisRow.find('input.process-param-newrowid').val();
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/getChildProcessOutputParam',
                    data: outputPostData,
                    beforeSend: function(){
                        $this.removeClass('fa-plus').addClass('fa-spinner');
                    },
                    success: function (dataHtml) {
                        if ($.trim(dataHtml) !== '') {
                            $thisRow.after(dataHtml);
                        }
                    }
                }).done(function(){
                    processOutputParamInitFreeze();
                    $thisRow.find('td:eq(0), td:eq(1)').css('background-color', 'white');
                    $this.removeClass('fa-spinner').addClass('fa-minus');
                });
                
            } else {
                $thisRow.closest('tbody').find('tr.tabletree-parent-'+rowId).css({display: ''});
                $this.removeClass('fa-plus').addClass('fa-minus');
            }  
            
        } else {
            
            processOutputParamCollapseRows($thisRow.closest('tbody'), rowId);
            $this.removeClass('fa-minus').addClass('fa-plus');
        }
    });
    
    $dialogId.on('keydown', 'input.process-output-param-add-code', function (e) {
        if (e.which === 13) {
            
            Core.blockUI({message: 'Loading...', boxed: true});
            var $this = $(this); 
            
            setTimeout(function () {
                
                var _value = $this.val(); 
                var _isName = false; 
                var $tbody = $this.closest('.output-params-new-config-parent').find('table.output-params-new-config > tbody');

                if (typeof $this.attr('data-ac-id') !== 'undefined') {
                    _isName = 'idselect';
                    _value = $this.attr('data-ac-id');
                }

                var isLast = false, isEmpty = false;

                if ($tbody.find('> tr').length === 0) {

                    var paramGroupPath = '';
                    var depth = 0;
                    var parentId = '';
                    isEmpty = true;

                } else {

                    if ($tbody.find('> tr.selected').length) {

                        var $addRow = $tbody.find('> tr.selected');
                        $addRow.removeClass('selected');

                    } else {
                        var $addRow = $tbody.find('> tr:last');
                        isLast = true;
                    }

                    var paramGroupPath = '';
                    var depth = $addRow.attr('data-depth');
                    var parentId = $addRow.attr('data-parent-id');

                    if (depth !== '0') {
                        paramGroupPath = $addRow.find('.process-path-name').text();
                    }

                    if ($addRow.attr('data-row-type') === 'group' && $addRow.find('.fa-plus').length) {

                        var plusDepth = Number(depth) + 1;
                        var paramPath = $addRow.attr('data-path');

                        if ($tbody.find("tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']").length) {
                            $addRow = $tbody.find("> tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']:last");
                        }              

                    } else if ($addRow.attr('data-row-type') === 'group' && $addRow.find('.fa-minus').length) {

                        var plusDepth = Number(depth) + 1;
                        var paramPath = $addRow.attr('data-path');

                        if ($addRow.hasAttr('data-id')) {
                            parentId = $addRow.attr('data-id');
                        } else {
                            parentId = $addRow.find('.process-param-rowid').val();
                        }

                        if ($tbody.find("tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']").length) {
                            $addRow = $tbody.find("> tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']:last");
                        }
                        
                        depth = plusDepth;
                        paramGroupPath = paramPath + '.' + _value;    
                    }
                }
                
                _value = _value.trim();
                
                if (_value !== '') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdmetadata/processOutputParamAddCode',
                        data: {
                            code: _value,
                            isName: _isName, 
                            depth: depth, 
                            parentId: parentId, 
                            paramGroupPath: paramGroupPath 
                        },
                        async: false,
                        dataType: 'json', 
                        success: function(jsonData){

                            $this.removeAttr('data-ac-id');
                            $this.val('');

                            if (jsonData.hasOwnProperty('status')) {
                                
                                Core.unblockUI();
                                PNotify.removeAll();
                                new PNotify({
                                    title: jsonData.status,
                                    text: jsonData.message, 
                                    type: jsonData.status,
                                    sticker: false
                                });

                            } else {

                                if ($tbody.find('input.process-param-path:attrNoCase("value","'+jsonData.path+'")').length) {

                                    Core.unblockUI();
                                    PNotify.removeAll();

                                    new PNotify({
                                        title: 'Анхааруулга',
                                        text: 'Уг ('+jsonData.path+') path өмнө нь үүссэн байна!', 
                                        type: 'info',
                                        sticker: false
                                    });

                                    return;

                                } else {

                                    var dataRow = jsonData.html;

                                    if (isEmpty) {

                                        $tbody.html(dataRow);
                                        Core.unblockUI();
                                        
                                        processOutputParamInitFreeze();

                                        var $addedRow = $tbody.find('> tr:eq(0)');
                                        isLast = true;

                                    } else {

                                        $addRow.after(dataRow);
                                        Core.unblockUI();

                                        processOutputParamInitFreeze();

                                        var $addedRow = $addRow.next();

                                        if ($addedRow.is(':last-child')) {
                                            isLast = true;
                                        }
                                    }
                                    
                                    var $parentScrollDiv = $('div#fz-process-output-params-option');
                                    var scrollTopSize = 0;
                                    
                                    if (isLast) {
                                        scrollTopSize = 4000;
                                    }

                                    if (scrollTopSize > 0) {
                                        $parentScrollDiv.scrollTop(scrollTopSize);
                                    }

                                    $addedRow.trigger('click');
                                }
                            }
                        },
                        error: function () {
                            alert("Error");
                        }
                    });
                
                } else {
                    Core.unblockUI();
                }
            }, 25);
        }
    });
    $dialogId.on('focus', 'input.process-output-param-add-code', function(e){
        processOutputParamMetaDataAutoComplete($(this));
    });
    $dialogId.on('keydown', 'input.process-output-param-add-code', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data('ui-autocomplete')) {
                $this.autocomplete('destroy');
            }
            return false;
        } else {
            if (!$this.data('ui-autocomplete')) {
                processOutputParamMetaDataAutoComplete($this);
            }
        }
    });
    
    $('.output-params-new-config').on('focus', 'select.process-param-datatype', function(){
    
        var $ddl = $(this);
        $ddl.data('previous', $ddl.val());
        
    }).on('change', function(){
        
        var $this = $(this);
        
        if ($this.prop('tagName') !== 'SELECT') {
            return;
        }
        
        var $previous = $this.data('previous');
        var $row = $this.closest('tr');
        var $value = $this.val();

        if ($value == 'row' || $value == 'rows') {
            
            if ($previous != 'row' && $previous != 'rows') {
            
                if ($row.find('.tabletree-expander').length === 0) {
                    $row.find('.process-path-name').before('<span class="tabletree-expander fa fa-plus"></span>');
                }

                $row.attr('data-fieldtogroup', '1');
            }
            
        } else {
            $row.find('.tabletree-expander').remove();
        }
    });
    
    $('.output-params-new-config').on('change', 'input.process-param-name', function() {
        var $this = $(this);
        var $parentRow = $this.closest('tr');
        var selfParamName = $this.val();
        var oldParamName = $parentRow.find('.process-param-oldparamname').val();
        
        if (selfParamName !== oldParamName) {
            $parentRow.find('.process-param-ispathchange').val('1');
        } else {
            $parentRow.find('.process-param-ispathchange').val('0');
        }
    });
    
    $('.output-params-new-config').on('keydown', 'input[type="text"]:visible', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        switch (code) {
            case 38: // <Up>   
                var $this = $(this);
                var $rowCell = $this.closest('td'); 
                var $row = $this.closest('tr');
                var $colIndex = $rowCell.index();
                var $prevRow = $row.prev('tr:visible');
                
                $prevRow.find('td:eq('+$colIndex+') input[type=text]:eq(0)').focus().select();
                
                $('table.output-param-link-tree tbody > tr.selected').removeClass('selected');
                $prevRow.addClass('selected');

                //scrollInView();
                return e.preventDefault();
            break;
            case 40: // <Down>
                var $this = $(this);
                var $rowCell = $this.closest('td'); 
                var $row = $this.closest('tr');
                var $colIndex = $rowCell.index();
                var $nextRow = $row.next('tr:visible');
                
                $nextRow.find('td:eq('+$colIndex+') input[type=text]:eq(0)').focus().select();
                
                $('table.output-param-link-tree tbody > tr.selected').removeClass('selected');
                $nextRow.addClass('selected');
                
                return e.preventDefault();
            break;
        } 
    });
    $('table.output-param-link-tree > tbody').sortable({
        items: 'tr', 
        cursor: 'move',
        handle: 'td:first > button.param-row-up-down', 
        cancel: '', 
        connectWith: 'table.output-param-link-tree > tbody', 
        placeholder: 'bg-yellow', 
        helper: fixOutputRowDragHelper, 
        stop: function(event, ui) { 
            var $currElem = $(ui.item);
            var $prevElem = $currElem.prev();
            var $nextElem = $currElem.next();
            var currDepth = $currElem.attr('data-depth');
            var prevDepth = $prevElem.attr('data-depth');
            var nextDepth = $nextElem.attr('data-depth');
            
            if (currDepth == '0') {
                if (typeof prevDepth !== 'undefined' && prevDepth !== currDepth) {
                    return false;
                } else if (typeof nextDepth !== 'undefined' && nextDepth !== currDepth) {
                    return false;
                }
            } else {
                if (typeof prevDepth !== 'undefined') {
                    currDepth = Number(currDepth);
                    prevDepth = Number(prevDepth);
                    
                    if (currDepth == (prevDepth + 1) && $prevElem.attr('data-row-type') == 'group' 
                        && $prevElem.find('.fa-minus').length == 0) {
                        return false;
                    } else if (currDepth == (prevDepth + 1) && $prevElem.attr('data-row-type') == 'field' 
                        && $currElem.attr('data-row-type') == 'field') {
                        return false;
                    }
                }
            }
            
            if ($currElem.attr('data-row-type') == 'group') {
                var $currElem = $(ui.item);
                var $rowId = $currElem.attr('data-id');
                var $tbody = $currElem.closest('tbody');

                if ($tbody.find('tr.tabletree-parent-'+$rowId).length) {
                    $currElem.after($tbody.find('tr.tabletree-parent-'+$rowId));
                }
            }
        }
    });
    
    $dialogId.on('click', 'input.process-param-isdeletecheck', function(e) {
        if (!lastChecked) {
            lastChecked = this;
            return;
        }
        
        if (e.shiftKey) {
            var $this = $(this);
            var $chkboxes = $this.closest('tbody').find('input.process-param-isdeletecheck');
            var start = $chkboxes.index(this);
            var end = $chkboxes.index(lastChecked);

            $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
        }

        lastChecked = this;
    });
});
function fixOutputRowDragHelper(e, ui) {
    var $helper = ui.clone();
    $helper.css({'width': '850px', 'background-color': '#8775a7'});
    $helper.children().css({'background-color': '#8775a7'});
    return $helper;
}
function scrollInView() {
    var $target = $('table.output-param-link-tree tbody > tr.selected');
    if ($target.length) {
        var scrollTopSize = $target.offset().top;
        $('div#fz-process-output-params-option').scrollTop(scrollTopSize);  
        
        return false;
    }
}
function processOutputParamInitFreeze() {
    $('table', 'div#fz-process-output-params-option').tableHeadFixer({'head': true, 'left': 2, 'z-index': 9}); 
}

function processOutputParamCollapseRows($tbody, rootId) {
    var $rows = $tbody.find('tr.tabletree-parent-'+rootId);

    $rows.each(function(){
        var $thisRow = $(this);
        var $rowId = $thisRow.attr('data-id');
        $thisRow.css({display: 'none'});

        if ($tbody.find('tr.tabletree-parent-'+$rowId+':visible').length) {
            $thisRow.find('.tabletree-expander').removeClass('fa-minus').addClass('fa-plus');
            processOutputParamCollapseRows($tbody, $rowId);
        }
    });
}
function processOutputParamMetaDataAutoComplete(elem) {
    var $this = elem;
    var isHoverSelect = false;

    $this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: 'lookup-ac-highlight', 
        appendTo: 'body',
        position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
        autoSelect: false,
        source: function(request, response) {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/metaDataAutoComplete',
                dataType: 'json',
                data: { 
                    q: request.term, 
                    type: 'codename' 
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        var code = item.split('|');
                        return {
                            value: code[1], 
                            label: code[1],
                            name: code[2], 
                            id: code[0]
                        };
                    }));
                }
            });
        },
        focus: function(event, ui) {
            if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                isHoverSelect = false;
            } else {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    isHoverSelect = true;
                }
            }
            return false;
        },
        open: function() {
            /*$(this).autocomplete('widget').zIndex(99999999999999);*/
            return false;
        },
        close: function() {
            $(this).autocomplete('option', 'appendTo', 'body'); 
        }, 
        select: function(event, ui) {
            var origEvent = event;	
            
            if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                $this.val(ui.item.label);
                $this.attr('data-ac-id', ui.item.id);
            } else {
                if (ui.item.label === $this.val()) {
                    $this.val(ui.item.label);
                } else {
                    event.preventDefault();
                }
            }

            while (origEvent.originalEvent !== undefined){
                origEvent = origEvent.originalEvent;
            }

            if (origEvent.type === 'click') {
                var e = jQuery.Event("keydown");
                e.keyCode = e.which = 13;
                $this.trigger(e);
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        ul.addClass('lookup-ac-render');
        
        var re = new RegExp("(" + this.term + ")", "gi"),
            cls = this.options.highlightClass,
            template = "<span class='" + cls + "'>$1</span>",
            label = item.label.replace(re, template);

        return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
    };
}
function processOutputParamAddRows(chooseType, elem, params, _this) {
    
    var $commonBasketMetaDataGrid = $('#commonBasketMetaDataGrid');
    var metaBasketNum = $commonBasketMetaDataGrid.datagrid('getData').total;
    
    if (metaBasketNum > 0) {
        
        var rows = $commonBasketMetaDataGrid.datagrid('getRows');
        
        Core.blockUI({message: 'Loading...', boxed: true});
        
        setTimeout(function () {
            
            var $this = $(_this);
            var $tbody = $this.closest('.output-params-new-config-parent').find('table.output-params-new-config > tbody');
            var isLast = false, isEmpty = false;
            
            if ($tbody.find('> tr').length === 0) {
                
                var paramGroupPath = '';
                var depth = 0;
                var parentId = '';
                isEmpty = true;
                
            } else {
                
                if ($tbody.find('> tr.selected').length) {

                    var $addRow = $tbody.find('> tr.selected');
                    //$addRow.find('> td:eq(0), > td:eq(1)').css('background-color', 'white');
                    $addRow.removeClass('selected');

                } else {
                    var $addRow = $tbody.find('> tr:last');
                    isLast = true;
                }

                var paramGroupPath = '';
                var depth = $addRow.attr('data-depth');
                var parentId = $addRow.attr('data-parent-id');

                if (depth !== '0') {
                    paramGroupPath = $addRow.find('.process-path-name').text();
                }

                if ($addRow.attr('data-row-type') === 'group' && $addRow.find('.fa-minus').length) {

                    var plusDepth = Number(depth) + 1;
                    var paramPath = $addRow.attr('data-path');

                    if ($tbody.find("tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']")) {
                        $addRow = $tbody.find("> tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']:last");
                        depth = plusDepth;
                    }

                    paramGroupPath = paramPath;                
                } 
            }

            $.ajax({
                type: 'post',
                url: 'mdmetadata/processOutputParamAddMulti', 
                data: {
                    selectedRows: rows, 
                    paramGroupPath: paramGroupPath, 
                    depth: depth, 
                    parentId: parentId 
                },
                async: false, 
                success: function(dataRow) {
                    
                    $this.val('');
                    
                    if (isEmpty) {
                        
                        $tbody.html(dataRow);
                        processOutputParamInitFreeze();
                        
                        var $addedRow = $tbody.find('> tr:eq(0)');
                        
                    } else {
                        
                        $addRow.after(dataRow);

                        processOutputParamInitFreeze();
                        //$addRow.find('td:eq(0), td:eq(1)').css('background-color', 'white');

                        var $addedRow = $addRow.next();
                    }

                    if (isLast) {
                        var scrollTopSize = $addedRow.offset().top;
                        $('div#fz-process-output-params-option').scrollTop(scrollTopSize);
                    }

                    $addedRow.find('td:eq(0), td:eq(1)').css('background-color', 'white');
                    
                    Core.unblockUI();
                }
            });
            
        }, 25);
    }
    
    return;
}
function deleteProcessOutputParamRows(elem) {
    var $this = $(elem);
    var $checkList = $this.closest('.output-params-new-config-parent').find('table.output-params-new-config > tbody input.process-param-isdeletecheck:checked');
    
    if ($checkList.length) {
        
        $checkList.each(function(){
            
            var $check = $(this);
            var $thisRow = $check.closest('tr');
            var isNew = $thisRow.find('.process-param-isnew').val();
    
            if (isNew === '1') {
                $thisRow.remove();
            } else {
                if ($thisRow.hasClass('deleted-row')) {
                    $thisRow.removeClass('deleted-row');
                    $thisRow.find('input.process-param-isdelete').val('0');
                } else {
                    $thisRow.addClass('deleted-row');
                    $thisRow.find('input.process-param-isdelete').val('1');
                }
            }
        });
    }
    return;
}
function processOutputParamDeleteRow(elem) {
    var isNew = elem.find('.process-param-isnew').val();
    
    if (isNew === '1') {
        var rowId = elem.attr('data-id');
        processOutputParamRemoveRows(elem.closest('tbody'), rowId);
        elem.remove();
    } else {
        elem.addClass('deleted-row');
        elem.find('input.process-param-isdelete').val('1');
    }
}
function processOutputParamRemoveRows($tbody, rootId) {
    var $rows = $tbody.find('tr.tabletree-parent-'+rootId);

    $rows.each(function(){
        var $thisRow = $(this);
        var $rowId = $thisRow.attr('data-id');
        
        $thisRow.remove();

        if ($tbody.find('tr.tabletree-parent-'+$rowId).length) {
            processOutputParamRemoveRows($tbody, $rowId);
        }
    });
}
function processOutputParamRefreshRow(elem) {
    elem.removeClass('deleted-row');
    elem.find('input.process-param-isdelete').val('0');
}
</script>