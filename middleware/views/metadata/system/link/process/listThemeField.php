<div id="theme-position-manager">
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-6">
                <div class="btn-group">
                    <?php echo Form::button(array('class' => 'btn btn-xs green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'), 'onclick' => 'addThemeField();')); ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'process-content-form', 'method' => 'post')); ?>
    <?php echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); ?>
    <div class="table-scrollable" style="overflow: auto; height: 400px;">
        <table class="table table-hover" id="process-theme-manager-list">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th style="width: 150px;">Theme position</th>
                    <th style="width: 300px;">Process path</th>
                    <th style="width: 200px;">Tab name</th>
                    <th style="width: 30px;">Label</th>
                    <th style="width: 40px;">Эрэмбэ</th>
                    <th style="width: 30px;"></th>
                </tr>
            </thead>
            <tbody><?php echo $this->initProcessThemeField;?></tbody>
        </table>
    </div>
    <?php echo Form::close(); ?>
</div>
<script type="text/javascript">
    
    function addThemeField() {
        var $dialogName = 'dialog-theme-field-manager';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/addProcessThemeFiledFrom',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId;?>'},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/insertProcessThemeField',
                                dataType: "json",
                                data: $("#add-process-theme-form", "#" + $dialogName).serialize(),
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                    initThemePosition();
                                    $("#" + $dialogName).dialog('close');
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            }).done(function () {
                                Core.initAjax();
                            });
                            $("#" + $dialogName).dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
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
        }).done(function () {
            Core.initAjax();
        });
    }
    
    function deleteProcessThemeField(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var rowId = row.find('input[name="rowId[]"]').val();
        var dialogName = '#deleteConfirm';
        if (!$(dialogName).length) {
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
                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdmeta/deleteProcessThemeField',
                            dataType: "json",
                            data: {rowId: rowId},
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true
                                });
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        title: 'Success',
                                        text: data.message,
                                        type: 'success',
                                        sticker: false
                                    });
                                    row.hide();
                                } else {
                                    new PNotify({
                                        title: 'Error',
                                        text: data.message,
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                                $(dialogName).dialog('close');
                                Core.unblockUI();
                            },
                            error: function () {
                                alert("Error");
                            }
                        }).done(function () {
                            Core.initAjax();
                        });
                        $(dialogName).dialog('close');
                    }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
            ]
        });
        $(dialogName).dialog('open');

    }
    
    function initThemePosition() {
        $.ajax({
            type: 'post',
            url: 'mdmeta/initProcessThemeField',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId;?>'},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("table#process-theme-manager-list tbody").empty().append(data);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });
    }
    
    function isLabelField(elem) {
        var _this = $(elem);
        var tr = _this.closest('tr');
        var rowId = tr.find('input[name="rowId[]"]').val();
        var isLabel = '';
        if (_this.prop("checked")) {
            tr.find('div.checker span').addClass('checked');
            isLabel = 1;
        } else {
            tr.find('div.checker span').removeClass('checked');
            isLabel = 0;
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/updateProcessThemeFieldIsLabel',
            dataType: "json",
            data: {rowId: rowId, isLabel: isLabel},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                if (data.status === 'success') {
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success',
                        sticker: false
                    });
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
    
    function updateOrderNumField(elem) {
        var _this = $(elem);
        var tr = _this.closest('tr');
        var rowId = tr.find('input[name="rowId[]"]').val();
        var orderNum = _this.val();
        if (orderNum.length > 0) {
            $.ajax({
                type: 'post',
                url: 'mdmeta/updateProcessThemeFieldOrderNum',
                dataType: "json",
                data: {rowId: rowId, orderNum: orderNum},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            sticker: false
                        });
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
        } else {
            $.ajax({
                type: 'post',
                url: 'mdmeta/getProcessThemeFieldOrderNum',
                dataType: "json",
                data: {rowId: rowId},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    _this.val(data.value);
                    new PNotify({
                        title: 'Error',
                        text: 'Хоосон утга хадгалах боломжгүй',
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            });
            
        }
        
    }
</script>