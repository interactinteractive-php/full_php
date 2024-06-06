<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="table-scrollable">
            <table class="table report-grouping-configs">
                <thead>
                    <tr>
                        <th style="width: 120px"><?php echo $this->lang->line('code'); ?></th>
                        <th><?php echo $this->lang->line('MET_330477'); ?></th>
                        <th><?php echo $this->lang->line('META_00007'); ?></th>
                        <th class="text-center" style="width: 5%">Sql</th>
                        <th style="width: 5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($this->paramList)) {
                        foreach ($this->paramList as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php echo Form::text(array('name' => 'dvSubSqlCode[]','class' => 'form-control form-control-sm', 'value' => $row['CODE'])); ?>
                        </td>
                        <td>
                            <?php echo Form::text(array('name' => 'dvSubSqlTitle[]','class' => 'form-control form-control-sm', 'value' => $row['GLOBE_CODE'])); ?>
                        </td>
                        <td>
                            <?php echo Form::text(array('name' => 'dvSubSqlDescr[]','class' => 'form-control form-control-sm', 'value' => $row['DESCRIPTION'])); ?>
                        </td>
                        <td class="middle text-center">
                            <?php echo Form::button(array('class' => 'btn btn-sm blue', 'name' => 'dvSubSqlTableName', 'value' => '<i class="fa fa-edit"></i>', 'onclick' => 'setDvSubQueryEditor(this);')); ?>
                            <textarea name="dvSubSqlTableName[]" id="dvSubSqlTableName" class="display-none"><?php echo (new Mdmetadata())->objectDeCompress($row['TABLE_NAME']); ?></textarea>
                        </td>
                        <td class="middle text-center">
                            <a href="javascript:;" class="btn red btn-xs" onclick="removeDvSubQueryEditor(this);">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <a href="javascript:;" class="btn green btn-xs" onclick="addDvSubQueryEditor(this);">
                                <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>     
    </div>
</div>
<div id="dialog-dvSubQueryEditor" class="display-none"></div>
<?php echo Form::hidden(array('name' => 'dvSubQueryLoad', 'value' => '1')); ?>

<script type="text/javascript">   
function addDvSubQueryEditor(elem){
    var $this = $(elem);
    var $parentTbl = $this.closest('table');
    $parentTbl.find('tbody').append('<tr>'+
        '<td><?php echo Form::text(array('name' => 'dvSubSqlCode[]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'dvSubSqlTitle[]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'dvSubSqlDescr[]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td class="middle text-center">'+
            '<?php echo Form::button(array('class' => 'btn btn-sm blue', 'name' => 'dvSubSqlTableName', 'value' => '<i class="fa fa-edit"></i>', 'onclick' => 'setDvSubQueryEditor(this);')); ?>'+
            '<textarea name="dvSubSqlTableName[]" id="dvSubSqlTableName" class="display-none"></textarea>'+
        '</td>'+
        '<td class="middle text-center">'+
            '<a href="javascript:;" class="btn red btn-xs" onclick="removeDvSubQueryEditor(this);">'+
                '<i class="fa fa-trash"></i>'+
            '</a>'+
        '</td>'+
    '</tr>');
    Core.initUniform($parentTbl.find('tbody > tr:last'));
}    
function removeDvSubQueryEditor(elem){
    var $parentRow = $(elem).closest('tr');
    $parentRow.remove();
}
function setDvSubQueryEditor(elem) {

    var $dialogName = 'dialog-dvSqlSubEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName),
        $parent = $(elem).closest('td');
    
    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $.ajax({
            type: 'post',
            url: 'mdmeta/dvSqlViewEditor',
            data: {query: $parent.find('textarea').val(), dialogId: $dialogName},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
                if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length == 0) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                }
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    minWidth: 1000,
                    height: 600,
                    modal: false,
                    open: function() {
                        disableScrolling();
                    }, 
                    close: function() {
                        enableScrolling();
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.format_btn, class: 'btn btn-sm purple-plum float-left', click: function() {
                            dvSqlQueryEditor.save();
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/sqlFormatting',
                                data: {query: dvSqlQueryEditor.getValue()},
                                beforeSend: function() {
                                    Core.blockUI({
                                        message: 'Formatting...',
                                        boxed: true
                                    });
                                },
                                success: function(content) {
                                    dvSqlQueryEditor.setValue(content);
                                    dvSqlQueryEditor.focus();
                                    Core.unblockUI();
                                }
                            });
                        }}, 
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {

                            dvSqlQueryEditor.save();
                            $parent.find('textarea').val(dvSqlQueryEditor.getValue());
                            
                            $dialog.dialog('close');
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
                    }, 
                    "maximize" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    });
}
</script>