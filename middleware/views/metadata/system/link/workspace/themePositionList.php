<div class="row">
    <div class="col-md-12" id="theme-position-manager">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <?php echo Form::button(array('class' => 'btn btn-xs green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'), 'onclick' => 'addThemePosition();')); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'process-content-form', 'method' => 'post')); ?>
        <?php echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); ?>
        <div class="table-scrollable">
            <table class="table table-hover" id="theme-position-manager-list">
                <thead>
                    <tr>
                        <th style="width: 15px;">#</th>
                        <th style="width: 120px;">Байршил</th>
                        <th style="width: 140px;">DV field</th>
                        <th style="width: 140px;">Label name</th>
                        <th style="width: 64px;"></th>
                    </tr>
                </thead>
                <tbody><?php echo $this->initThemePositionList; ?></tbody>
            </table>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>

<script type="text/javascript">
    function addThemePosition() {
        PNotify.removeAll();
        var $dialogName = 'dialog-banner-manager';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdworkspace/addThemePositionFrom',
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
                    width: 500,
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.add_btn, class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdworkspace/insertThemePosition',
                                dataType: 'json',
                                data: $("#add-theme-position-form", "#" + $dialogName).serialize(),
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
                                    
                                    initThemePosition();
                                    $dialog.dialog('close');
                                    
                                    Core.unblockUI();
                                },
                                error: function () { alert("Error"); }
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
            error: function () { alert("Error"); }
        }).done(function () {
            Core.initAjax($dialog);
        });
    }
    
    function editThemePosition(elem) {
        PNotify.removeAll();
        var $this = $(elem);
        var id = $this.closest('tr').find("input[name='rowId[]']").val();
        var $dialogName = 'dialog-ws-position';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdworkspace/editThemePositionFrom',
            dataType: "json",
            data: {id: id, metaDataId: '<?php echo $this->metaDataId; ?>', groupMetaDataId: '<?php echo $this->groupMetaDataId;?>'},
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
                    width: 500,
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.add_btn, class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdworkspace/updateThemePosition',
                                dataType: 'json',
                                data: $("#edit-theme-position-form", "#" + $dialogName).serialize(),
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
                                    
                                    initThemePosition();
                                    $dialog.dialog('close');
                                    
                                    Core.unblockUI();
                                },
                                error: function () { alert("Error"); }
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
            error: function () { alert("Error"); }
        }).done(function () {
            Core.initSelect2($dialog);
        });
    }
    
    function deleteThemePosition(elem) {
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
                        error: function () { alert("Error"); }
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
    
    function initThemePosition() {
        $.ajax({
            type: 'post',
            url: 'mdworkspace/initThemePositionList',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId; ?>', groupMetaDataId: '<?php echo $this->groupMetaDataId;?>'},
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {
                $("table#theme-position-manager-list tbody").empty().append(data);
                Core.unblockUI();
            },
            error: function () { alert("Error"); }
        }).done(function () {
            Core.initAjax($("table#theme-position-manager-list tbody"));
        });
    }
</script>