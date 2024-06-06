<div class="row">
    <div class="col-md-12" id="theme-process-manager">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <?php echo Form::button(array('class' => 'btn btn-xs green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'), 'onclick' => 'addWorkSpaceProcess();')); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        echo Form::create(array('class' => 'form-horizontal', 'id' => 'process-content-form', 'method' => 'post'));
        echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); 
        ?>
        <div id="fz-ws-parammap" class="freeze-overflow-xy-auto" style="height: 512px;">
            <table class="table table-sm table-hover bprocess-table-dtl" id="theme-process-manager-list" style="table-layout: fixed !important;">
                <thead>
                    <tr>
                        <th style="width: 29px; max-width: 29px;">#</th>
                        <th style="width: 180px;">Field path</th>
                        <th style="width: 180px;">Param path</th>
                        <th style="width: 50%;">Target meta name</th>
                        <th style="width: 50%;">Target indicator name</th>
                        <th style="width: 80px;"></th>
                    </tr>
                    <tr class="bp-filter-row">
                        <th></th>
                        <th><input type="text" data-type-code="text" data-path-code="fieldPath"></th>
                        <th><input type="text" data-type-code="text" data-path-code="paramPath"></th>
                        <th><input type="text" data-type-code="text" data-path-code="targetMetaName"></th>
                        <th><input type="text" data-type-code="text" data-path-code="targetIndicatorName"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody><?php echo $this->initWorkSpaceProcessList; ?></tbody>
            </table>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>

<script type="text/javascript">
    
$(function() {
    freezeWsParamMap();
});
    
function freezeWsParamMap() {
    $('table', 'div#fz-ws-parammap').tableHeadFixer({'head': true});
}

function addWorkSpaceProcess() {
    PNotify.removeAll();
    var $dialogName = 'dialog-banner-manager';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdworkspace/addWorkSpaceProcessFrom',
        dataType: "json",
        data: {metaDataId: '<?php echo $this->metaDataId; ?>', groupMetaDataId: '<?php echo $this->groupMetaDataId;?>'},
        beforeSend: function () {
            Core.blockUI({animate: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 600,
                modal: true,
                close: function () {
                    $dialog.empty().dialog('close');
                },
                buttons: [
                    {text: plang.get('save_btn_add'), class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdworkspace/insertWorkSpaceProcess',
                            dataType: "json",
                            data: $("#add-workspace-process-form", "#" + $dialogName).serialize(),
                            beforeSend: function () {
                                Core.blockUI({animate: true});
                            },
                            success: function (data) {

                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });

                                initWorkSpaceProcess();
                                Core.unblockUI();
                            },
                            error: function () {
                                alert("Error");
                            }
                        });
                    }},
                    {text: data.add_btn, class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdworkspace/insertWorkSpaceProcess',
                            dataType: "json",
                            data: $("#add-workspace-process-form", "#" + $dialogName).serialize(),
                            beforeSend: function () {
                                Core.blockUI({animate: true});
                            },
                            success: function (data) {
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });
                                initWorkSpaceProcess();
                                Core.unblockUI();
                            },
                            error: function () {
                                alert("Error");
                            }
                        });
                        $dialog.dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
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
    }).done(function () {
        Core.initSelect2($dialog);
    });
}
function editWorkSpaceProcess(elem) {
    PNotify.removeAll();
    var $row = $(elem).closest('tr');
    var $dialogName = 'dialog-banner-manager';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdworkspace/addWorkSpaceProcessFrom',
        dataType: 'json',
        data: {
            rowId: $row.find('input[name="rowId[]"]').val(), 
            metaDataId: '<?php echo $this->metaDataId; ?>', 
            groupMetaDataId: '<?php echo $this->groupMetaDataId;?>'
        },
        beforeSend: function () {
            Core.blockUI({animate: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 600,
                modal: true,
                close: function () {
                    $dialog.empty();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdworkspace/updateWorkSpaceProcessMap',
                            dataType: "json",
                            data: $("#add-workspace-process-form", "#" + $dialogName).serialize(),
                            beforeSend: function () {
                                Core.blockUI({animate: true});
                            },
                            success: function (data) {
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });
                                initWorkSpaceProcess();
                                Core.unblockUI();
                            },
                            error: function () { alert("Error"); }
                        });
                        $dialog.dialog('close');
                    }},
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
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
    }).done(function () {
        Core.initSelect2($dialog);
    });
}

function deleteWorkSpaceProcess(elem) {
    PNotify.removeAll();
    var $this = $(elem);
    var $row = $this.parents('tr');
    var rowId = $row.find('input[name="rowId[]"]').val();
    var dialogName = '#deleteConfirm';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName);
    $dialog.html(plang.get('msg_delete_confirm'));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'),
        width: '350',
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                $.ajax({
                    type: 'post',
                    url: 'mdworkspace/deleteThemePosition',
                    dataType: "json",
                    data: {rowId: rowId},
                    beforeSend: function () {
                        Core.blockUI({animate: true});
                    },
                    success: function (data) {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        if (data.status === 'success') {
                            $row.hide();
                        } 
                        $dialog.dialog('close');
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
                $dialog.dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}

function initWorkSpaceProcess() {
    $.ajax({
        type: 'post',
        url: 'mdworkspace/initWorkSpaceProcessList',
        dataType: "json",
        data: {metaDataId: '<?php echo $this->metaDataId; ?>', groupMetaDataId: '<?php echo $this->groupMetaDataId;?>'},
        beforeSend: function () {
            Core.blockUI({animate: true});
        },
        success: function (data) {
            $("table#theme-process-manager-list tbody").empty().append(data).promise().done(function() {
                freezeWsParamMap();
            });
            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });
}
</script>