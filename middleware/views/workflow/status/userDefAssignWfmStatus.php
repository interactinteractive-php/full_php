<form method="post" id="form-userdefassignwfmstatus">
    <button type="button" class="btn btn-sm green-meadow" onclick="addMultiWfmStatusAssigmentClick(this);">
        <i class="icon-plus2"></i> Нэмэх
    </button>
    <div class="table-responsive">
        <table class="table table-hover" id="tbl-userdefassignwfmstatus">
            <thead>
                <tr>
                    <th class="font-weight-bold" style="width: 300px;">Хэрэглэгч</th>
                    <th class="font-weight-bold" style="width: 75px;">Дараалал</th>
                    <th class="font-weight-bold text-center" style="width: 110px;">Харах / Засах</th>
                    <th class="font-weight-bold" style="width: 90px;">Батлах хувь</th>
                    <th class="font-weight-bold" style="min-width: 200px;">Тайлбар</th>
                    <th class="font-weight-bold" style="width: 190px;">Огноо</th>
                    <th style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <?php
    echo Form::hidden(array('name' => 'refStructureId', 'value' => $this->refStructureId)); 
    echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->dataViewId)); 
    ?>
</form>

<script type="text/javascript">
$(function() {
    
    $(document.body).on('click', '.assign-isedit', function() {
        var $this = $(this);
        var $row = $this.closest('tr');
        var isEdit = $this.val();
        
        if (isEdit == '2') {
            $row.find('input[name="weight[]"]').prop('readonly', false);
        } else {
            $row.find('input[name="weight[]"]').prop('readonly', true);
        }
    });
}); 

function addMultiWfmStatusAssigmentClick(elem) {
    var params = '', rows = getDataViewSelectedRows('<?php echo $this->dataViewId; ?>');
    
    if (rows.length && rows[0].hasOwnProperty('filterwfmdepartmentid') && rows[0]['filterwfmdepartmentid']) {
        params = 'param[filterWfmDepartmentId]=' + rows[0]['filterwfmdepartmentid'];
    }
    
    wfmUserMetaDataGrid('multi', '', 'param[wfmRuleId]=<?php echo $this->ruleId; ?>', 'addMultiWfmStatusAssigment', elem);
}
function addMultiWfmStatusAssigment(metaDataCode, chooseType, elem, rows) {
    var $tbl = $('#tbl-userdefassignwfmstatus > tbody'), 
        joinRows = [], 
        length = $tbl.find('> tr').length, 
        view_btn = plang.get('view_btn'), 
        edit_btn = plang.get('edit_btn');

    var mandatoryCriteria = $('#wfmuser-metadata-search-form'), ruleHtml = '', 
    $wfmRuleId = mandatoryCriteria.find('select[name="param[wfmRuleId]"]'), 
    $wfmWaitTime = mandatoryCriteria.find('input[name="waitTime"]'),
    $wfmWaitStatusId = mandatoryCriteria.find('select[name="waitStatusId"]');        
    ruleHtml += '<input type="hidden" name="wfmRuleId" value="'+ $wfmRuleId.val() +'"/>';
    ruleHtml += '<input type="hidden" name="waitTime" value="'+ $wfmWaitTime.val() +'"/>';
    ruleHtml += '<input type="hidden" name="waitStatusId" value="'+ $wfmWaitStatusId.val() +'"/>';    
    $('#tbl-userdefassignwfmstatus').parent().append('<span id="hiddeninputswfm"></span>');
    $('#tbl-userdefassignwfmstatus').parent().find('#hiddeninputswfm').empty().append(ruleHtml);
        
    if ($('#dialog-wfmusermetadata').length) {
        $('#dialog-wfmusermetadata').dialog('close');
    }
    
    for (var key in rows) {
        
        var row = rows[key], isAddRow = true, weight = '';
        
        $tbl.find('> tr').each(function() {
            if ($(this).attr('data-id') == row.id) {
                isAddRow = false;
            }
        });
        
        if (isAddRow) {
            
            if (row.hasOwnProperty('weight') && row.weight) {
                weight = row.weight;
            }
            
            joinRows.push('<tr data-id="'+row.id+'">');

                joinRows.push('<td>');
                    joinRows.push('<input type="hidden" name="assigmentUserId[]" value="'+row.id+'">');
                    joinRows.push('<div class="d-flex align-items-center">');
                        joinRows.push('<div class="mr-2">');
                            joinRows.push('<img class="rounded-circle" src="api/image_thumbnail?width=40&src='+row.picture+'" onerror="onUserLogoError(this);" width="40" height="40">');
                        joinRows.push('</div>'); 
                        joinRows.push('<div>');
                            joinRows.push('<label class="text-default font-weight-bold">'+row.userfullname+'</label>');
                            joinRows.push('<div>');
                                joinRows.push('<label class="text-muted font-size-11 mt-1">'+row.departmentname+'</label>');
                                joinRows.push('<label class="text-muted font-size-11 ml-1">'+row.positionname+'</label>');
                            joinRows.push('</div>');
                        joinRows.push('</div>');
                    joinRows.push('</div>');
                joinRows.push('</td>');
                
                joinRows.push('<td>');
                    joinRows.push('<input type="text" name="order[]" class="form-control longInit" value="'+(++length)+'">');
                joinRows.push('</td>');

                joinRows.push('<td>');
    
                    joinRows.push('<div class="form-check form-check-inline mr10">');
                        joinRows.push('<label class="form-check-label" title="'+view_btn+'">');
                            joinRows.push('<input type="radio" name="isEdit['+row.id+']" class="form-check-input assign-isedit" value="1">');
                            joinRows.push('<i class="icon-eye ml5 mt2"></i>');
                        joinRows.push('</label>');
                    joinRows.push('</div>');

                    joinRows.push('<div class="form-check form-check-inline">');
                        joinRows.push('<label class="form-check-label" title="'+edit_btn+'">');
                            joinRows.push('<input type="radio" name="isEdit['+row.id+']" class="form-check-input assign-isedit" value="2" checked>');
                            joinRows.push('<i class="icon-pencil6 ml5 mt2"></i>');
                        joinRows.push('</label>');
                    joinRows.push('</div>');
                    
                joinRows.push('</td>');
                
                joinRows.push('<td>');
                    joinRows.push('<input type="text" name="weight[]" class="form-control bigdecimalInit" data-v-max="100" data-mdec="2" value="'+weight+'">');
                joinRows.push('</td>');

                joinRows.push('<td>');
                    joinRows.push('<textarea class="form-control" name="descriptionAssign[]" rows="2"></textarea>');
                joinRows.push('</td>');

                joinRows.push('<td>');
                    joinRows.push('<div class="dateElement input-group" style="max-width: 175px !important;">');
                    joinRows.push('<input type="text" name="dueDate[]" class="form-control form-control-sm dateInit" style="width: 52px !important;">');
                    joinRows.push('<span class="input-group-btn"><button tabindex="-1" class="btn" onclick="return false;"><i class="fa fa-calendar"></i></button></span>');
                    joinRows.push('<input type="text" name="dueTime[]" class="form-control form-control-sm ml10 timeInit" value="00:00">');
                    joinRows.push('</div>');
                joinRows.push('</td>');
                
                joinRows.push('<td>');
                joinRows.push('<button type="button" class="btn btn-xs red mr0" onclick="removeWfmStatusAssigment(this);"><i class="fa fa-trash"></i></button>');
                joinRows.push('</td>');

            joinRows.push('</tr>');
        }
    }
    
    $tbl.append(joinRows.join(''));
    
    Core.initNumberInput($tbl);
    Core.initDateInput($tbl);
    Core.initTimeInput($tbl);
    Core.initUniform($tbl);
    
    $('#dialog-userdefassignwfmstatus').dialog('option', 'position', {my: 'center', at: 'center', of: window});
}    
function removeWfmStatusAssigment(elem) {
    $(elem).closest('tr').remove();
}
function wfmUserMetaDataGrid(chooseType, elem, params, funcName, _this) {
        var funcName = typeof funcName === 'undefined' ? 'selectableCommonMetaDataGrid' : funcName;
        var _this = typeof _this === 'undefined' ? '' : _this;
        var $dialogName = 'dialog-wfmusermetadata';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);
        var rows = getDataViewSelectedRows('<?php echo $this->dataViewId; ?>');

        $.ajax({
            type: 'post',
            url: 'mdmetadata/wfmUserDataSelectableGrid',
            data: { 
                chooseType: chooseType, 
                params: params,
                selectedRow: rows[0],
                dataViewId: '<?php echo $this->dataViewId; ?>',
                refStructureId: '<?php echo $this->refStructureId; ?>'
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [{
                            text: data.addbasket_btn,
                            class: 'btn green-meadow btn-sm float-left',
                            click: function() {
                                basketWfmUserMetaDataGrid();
                            }
                        },
                        {
                            text: data.choose_btn,
                            class: 'btn blue btn-sm datagrid-common-choose-btn',
                            click: function() {
                                if (typeof(window[funcName]) === 'function') {
                                    var countBasketList = $('#wfmUserBasketMetaDataGrid').datagrid('getData').total;
                                    if (countBasketList > 0) {
                                        var rows = $('#wfmUserBasketMetaDataGrid').datagrid('getRows');
                                        window[funcName](chooseType, params, _this, rows);
                                    }                                                                
                                } else {
                                    alert('Function undefined error: ' + funcName);
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-hoki btn-sm',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        }).done(function() {
            Core.initAjax($dialog);
        });
    }    
</script>