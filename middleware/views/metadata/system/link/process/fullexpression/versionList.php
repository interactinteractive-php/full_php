<div class="row">
    <div class="col-md-12">
        
        <a href="javascript:;" class="btn btn-xs green" onclick="addBpFullExpression('<?php echo $this->metaDataId; ?>');"><i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?></a>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 5px">№</th>
                    <th><?php echo $this->lang->line('MET_330477'); ?></th>
                    <th><?php echo $this->lang->line('META_00007'); ?></th>
                    <th class="text-center">Default эсэх</th>
                    <th style="width: 115px"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($this->versionList) {
                    $n = 1;
                    foreach ($this->versionList as $row) {
                ?>
                <tr>
                    <td><?php echo $n++; ?>.</td>
                    <td><?php echo $row['TITLE']; ?></td>
                    <td><?php echo $row['DESCRIPTION']; ?></td>
                    <td class="text-center middle"><?php echo ($row['IS_DEFAULT'] == '1' ? '<i class="fa fa-check-circle font-size-18"></i>' : ''); ?></td>
                    <td class="text-center">
                        <a href="javascript:;" class="btn btn-xs blue" title="<?php echo $this->lang->line('META_00058'); ?>" onclick="editVersionBpFullExpression('<?php echo $row['ID']; ?>', '<?php echo $this->metaDataId; ?>');"><i class="fa fa-edit"></i></a>
                        <a href="javascript:;" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>" onclick="deleteVersionBpFullExpression('<?php echo $row['ID']; ?>', '<?php echo $this->metaDataId; ?>');"><i class="fa fa-trash"></i></a>
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
function addBpFullExpression(metaDataId) {
    var $dialogName = 'dialog-fullExpcriteria-'+metaDataId;
        
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setProcessFullExpressionCriteria',
        data: {metaDataId: metaDataId, addVersion: 'true'},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
            if (typeof CodeMirror === 'undefined') {
                $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                });
            }
        },
        success: function(data) {
            $("#" + $dialogName).empty().append('<form id="fullExpression-form" class="form-horizontal" method="post">' + data.Html + '</form>');
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                minWidth: 1200,
                height: "auto",
                modal: false,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                         
                        $("#" + $dialogName).find("form#fullExpression-form").validate({errorPlacement: function () {}});

                        if ($("#" + $dialogName).find("form#fullExpression-form").valid()) { 
                            
                            fullExpressionEditor.save();
                            fullExpressionOpenEditor.save();
                            fullExpressionVarFncEditor.save();
                            fullExpressionSaveEditor.save();
                            fullExpressionAfterSaveEditor.save();

                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/saveNewVersionFullExpression',
                                dataType: 'json',
                                data: $("#" + $dialogName).find("form#fullExpression-form").serialize(),
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {

                                    PNotify.removeAll();

                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $("#" + $dialogName).dialog('close');
                                        $("#dialog-fullExpList").dialog('close');
                                        bpFullExpressionList(metaDataId);
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        }
                    }},
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
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function(){
        Core.initAjax($("#" + $dialogName));
    });
}
function editVersionBpFullExpression(versionId, metaDataId) {
    var $dialogName = 'dialog-fullExpcriteria-'+metaDataId;
        
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setProcessFullExpressionCriteria',
        data: {metaDataId: metaDataId, editVersion: 'true', versionId: versionId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
            if (typeof CodeMirror === 'undefined') {
                $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                });
            }
        },
        success: function(data) {
            $("#" + $dialogName).empty().append('<form id="fullExpression-form" class="form-horizontal" method="post">' + data.Html + '</form>');
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                minWidth: 1200,
                height: "auto",
                modal: false,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                         
                        $("#" + $dialogName).find("form#fullExpression-form").validate({errorPlacement: function () {}});

                        if ($("#" + $dialogName).find("form#fullExpression-form").valid()) { 
                            
                            fullExpressionEditor.save();
                            fullExpressionOpenEditor.save();
                            fullExpressionVarFncEditor.save();
                            fullExpressionSaveEditor.save();
                            fullExpressionAfterSaveEditor.save();

                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/saveUpdateVersionFullExpression',
                                dataType: 'json',
                                data: $("#" + $dialogName).find("form#fullExpression-form").serialize(),
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {

                                    PNotify.removeAll();

                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $("#" + $dialogName).dialog('close');
                                        $("#dialog-fullExpList").dialog('close');
                                        bpFullExpressionList(metaDataId);
                                    } else {
                                        if (data.status === 'locked') {
                                            lockedRequestMeta(data);
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                    }
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        }
                    }},
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
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function(){
        Core.initAjax($("#" + $dialogName));
    });
}
function deleteVersionBpFullExpression(versionId, metaDataId) {
    var $dialogName = 'dialog-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

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
            $("#" + $dialogName).empty().append(data.Html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 330,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function () {
                        if (versionId !== '') {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/deleteBpFullExpressionVersion',
                                data: {metaDataId: metaDataId, versionId: versionId},
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (dataSub) {
                                    PNotify.removeAll();
                                    if (dataSub.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: dataSub.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $("#" + $dialogName).dialog('close');
                                        $("#dialog-fullExpList").dialog('close');
                                        bpFullExpressionList(metaDataId);
                                    } else {
                                        if (dataSub.status === 'locked') {
                                            lockedRequestMeta(dataSub);
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: dataSub.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                        $("#" + $dialogName).dialog('close');
                                    }
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        }
                    }},
                    {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}
</script>