<div class="row">
    <div class="col-md-12">
        <a href="javascript:;" class="btn btn-xs green" onclick="addBpFullExpressionCache('<?php echo $this->metaDataId; ?>');"><i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?></a>
        <table class="table table-hover" id="cache-expression-tbl">
            <thead>
                <tr>
                    <th style="width: 5px">№</th>
                    <th>Run mode</th>
                    <th><?php echo $this->lang->line('META_00075'); ?></th>
                    <th>Group path</th>
                    <th><?php echo $this->lang->line('META_00007'); ?></th>
                    <th style="width: 90px"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($this->cacheExpressionList) {
                    $n = 1;
                    foreach ($this->cacheExpressionList as $row) {
                ?>
                <tr>
                    <td><?php echo $n++; ?>.</td>
                    <td data-field="runMode"><?php echo $row['RUN_MODE']; ?></td>
                    <td data-field="code"><?php echo $row['CODE']; ?></td>
                    <td data-field="groupPath"><?php echo $row['GROUP_PATH']; ?></td>
                    <td data-field="description"><?php echo $row['DESCRIPTION']; ?></td>
                    <td class="text-center">
                        <?php 
                        echo Form::hidden(array('name' => 'cacheId[]', 'value' => $row['ID'])); 
                        echo Form::hidden(array('name' => 'cacheRunMode[]', 'value' => $row['RUN_MODE'])); 
                        echo Form::hidden(array('name' => 'cacheGroupPath[]', 'value' => $row['GROUP_PATH'])); 
                        echo Form::hidden(array('name' => 'cacheCode[]', 'value' => $row['CODE'])); 
                        echo Form::hidden(array('name' => 'cacheDescr[]', 'value' => $row['DESCRIPTION'])); 
                        echo Form::hidden(array('name' => 'cacheRowDelete[]')); 
                        echo Form::textArea(array('name' => 'cacheExpression[]', 'value' => $row['EXPRESSION_STRING'], 'class' => 'd-none')); 
                        ?>
                        <a href="javascript:;" class="btn btn-xs blue" title="<?php echo $this->lang->line('META_00058'); ?>" onclick="editBpFullExpressionCache(this, '<?php echo $this->metaDataId; ?>');"><i class="fa fa-edit"></i></a>
                        <a href="javascript:;" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>" onclick="deleteBpFullExpressionCache(this, '<?php echo $this->metaDataId; ?>');"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>    

<script type="text/javascript">
function addBpFullExpressionCache(metaDataId) {
    var $dialogName = 'dialog-fullExpCache-'+metaDataId;
        
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setCacheFullExpression',
        data: {metaDataId: metaDataId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 900,
                minWidth: 900,
                height: 'auto',
                modal: true, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                        
                        var $cacheForm = $dialog.find("form#cache-version-form");
                        
                        $cacheForm.validate({errorPlacement: function () {}});

                        if ($cacheForm.valid()) { 
                            
                            cacheExpressionEditor.save();
                            var $cacheBody = $('#cache-expression-tbl > tbody');
                        
                            $cacheBody.append('<tr>'+
                                '<td>1.</td>'+
                                '<td data-field="runMode">'+$cacheForm.find('#runMode').select2('val')+'</td>'+
                                '<td data-field="code">'+$cacheForm.find('#code').val()+'</td>'+
                                '<td data-field="groupPath">'+$cacheForm.find('#groupPath').val()+'</td>'+
                                '<td data-field="description">'+$cacheForm.find('#description').val()+'</td>'+
                                '<td class="text-center">'+
                                    '<input type="hidden" name="cacheId[]" value="">'+
                                    '<input type="hidden" name="cacheRunMode[]" value="'+$cacheForm.find('#runMode').select2('val')+'">'+
                                    '<input type="hidden" name="cacheGroupPath[]" value="'+$cacheForm.find('#groupPath').val()+'">'+
                                    '<input type="hidden" name="cacheCode[]" value="'+$cacheForm.find('#code').val()+'">'+
                                    '<input type="hidden" name="cacheDescr[]" value="'+$cacheForm.find('#description').val()+'">'+
                                    '<input type="hidden" name="cacheRowDelete[]" value="">'+
                                    '<textarea name="cacheExpression[]" class="d-none">'+cacheExpressionEditor.getValue()+'</textarea>'+
                                    '<a href="javascript:;" class="btn btn-xs blue" title="<?php echo $this->lang->line('META_00058'); ?>" onclick="editBpFullExpressionCache(this, \'<?php echo $this->metaDataId; ?>\');"><i class="fa fa-edit"></i></a>'+
                                    '<a href="javascript:;" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>" onclick="deleteBpFullExpressionCache(this, \'<?php echo $this->metaDataId; ?>\');"><i class="fa fa-trash"></i></a>'+
                                '</td>'+
                            '</tr>');
                            
                            $dialog.dialog('close');
                        }
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
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
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function(){
        Core.initAjax($dialog);
    });
}
function editBpFullExpressionCache(elem, metaDataId) {
    var $dialogName = 'dialog-fullExpCache-'+metaDataId;
        
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $('#' + $dialogName);
    var $row = $(elem).closest('tr');
                        
    var postData = {
        metaDataId: metaDataId, 
        runMode: $row.find('input[name="cacheRunMode[]"]').val(), 
        groupPath: $row.find('input[name="cacheGroupPath[]"]').val(), 
        code: $row.find('input[name="cacheCode[]"]').val(), 
        descr: $row.find('input[name="cacheDescr[]"]').val(), 
        expression: $row.find('textarea[name="cacheExpression[]"]').val()
    };
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setCacheFullExpression',
        data: postData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 900,
                minWidth: 900,
                height: 'auto',
                modal: false,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                        
                        var $cacheForm = $dialog.find("form#cache-version-form");
                        
                        $cacheForm.validate({errorPlacement: function () {}});

                        if ($cacheForm.valid()) { 
                            
                            cacheExpressionEditor.save();
                            
                            var runMode = $cacheForm.find('#runMode').select2('val');
                            var groupPath = $cacheForm.find('#groupPath').val();
                            var code = $cacheForm.find('#code').val();
                            var description = $cacheForm.find('#description').val();
                            var expStr = cacheExpressionEditor.getValue();
                            
                            $row.find('input[name="cacheRunMode[]"]').val(runMode);
                            $row.find('input[name="cacheGroupPath[]"]').val(groupPath);
                            $row.find('input[name="cacheCode[]"]').val(code);
                            $row.find('input[name="cacheDescr[]"]').val(description);
                            $row.find('textarea[name="cacheExpression[]"]').val(expStr);
                                
                            $row.find('td[data-field="runMode"]').text(runMode);
                            $row.find('td[data-field="code"]').text(groupPath);
                            $row.find('td[data-field="groupPath"]').text(code);
                            $row.find('td[data-field="description"]').text(description);

                            $dialog.dialog('close');
                        }
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
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
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function(){
        Core.initAjax($dialog);
    });
}
function deleteBpFullExpressionCache(elem, metaDataId) {
    var $dialogName = 'dialog-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdcommon/deleteConfirm',
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 330,
                height: "auto",
                modal: true,
                close: function () {
                    $dialog.empty().dialog('close');
                },
                buttons: [
                    {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function () {
                            
                        var $this = $(elem);
                        var $row = $this.closest('tr');
                        var $rowId = $row.find('input[name="cacheId[]"]');
                        
                        if ($rowId !== '') {
                            $row.find('input[name="cacheRowDelete[]"]').val('deleted');
                            $row.addClass('removed-tr');
                        } else {
                            $row.remove();
                        }
                        
                        $dialog.dialog('close');
                    }},
                    {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}
</script>