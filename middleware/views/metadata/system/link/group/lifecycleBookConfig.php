<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<style type="text/css">
    .table-scrollable {
        min-height: 200px;
    }
    .lcbook {
        background-color: rgba(221,221,221,0.6);
        border-bottom-color: #000;
    }
</style>
<div class="col-md-12" id="lifecycle-book-list">
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-6">
                <div class="btn-group">
                    
                </div>
                <div class="btn-group">
                    <button class="btn green-meadow btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:;" onclick="addLifeCycleBook();"><i class="fa fa-recycle"></i> Lifecycle book нэмэх </a></li>
                        <li class="lifecycle-btn"><a href="javascript:;"><i class="fa fa-random"></i> <?php echo $this->lang->line('META_00010'); ?> </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="table-scrollable">
        <table class="table table-hover workFlowStatusTblList" id="lifeCycleBookTblList">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th style="width: 140px;"><?php echo $this->lang->line('META_00075'); ?></th>
                    <th><?php echo $this->lang->line('META_00125'); ?></th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        initLifeCycleBookList();
    });
    $.contextMenu({
        selector: '#lifeCycleBookTblList tbody tr.lifecycleBook',
        callback: function (key, opt) {
            if (key === 'edit') {
                var _this = this;
                var lcBookId = _this.find('input[name="lcBookId[]"]').val();
                editLifeCycleBook(lcBookId);
            }
        },
        items: {
            "edit": {name: "<?php echo $this->lang->line('META_00058'); ?>", icon: "edit"}
        }
    });
    $.contextMenu({
        selector: '#lifeCycleBookTblList tbody tr.lifecycle',
        callback: function (key, opt) {
            if (key === 'edit') {
                var _this = this;
                var lifecycleId = _this.find('input[name="lifecycleId[]"]').val();
                editLifeCycle(lifecycleId);
            }
            if (key === 'delete') {
                var _this = this;
                var lifecycleId = _this.find('input[name="lifecycleId[]"]').val();
                deleteLifecycle(lifecycleId);
            }
        },
        items: {
            "edit": {name: "<?php echo $this->lang->line('META_00058'); ?>", icon: "edit"},
            "delete": {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"}
        }
    });
    $.contextMenu({
        selector: '#lifeCycleBookTblList tbody tr.lcbook-lifecycle',
        callback: function (key, opt) {
            if (key === 'edit') {
                var _this = this;
                var lifecycleId = _this.find('input[name="lifecycleId[]"]').val();
                var lcBookId = _this.find('input[name="lcBookId[]"]').val();
                editLcBookLifecycle(lcBookId, lifecycleId);
            }
            if (key === 'delete') {
                var _this = this;
                var dataModelId = '<?php echo $this->metaDataId;?>';
                var lifecycleId = _this.find('input[name="lifecycleId[]"]').val();
                var lcBookId = _this.find('input[name="lcBookId[]"]').val();
                deleteLcBookLifecycle(dataModelId, lcBookId, lifecycleId);
            }
        },
        items: {
            "edit": {name: "<?php echo $this->lang->line('META_00058'); ?>", icon: "edit"},
            "delete": {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"}
        }
    });

    function initLifeCycleBookList() {
        var oTable = $("#lifeCycleBookTblList").find('tbody');
        $.ajax({
            type: 'post',
            url: 'mdmeta/lifecycleBookList',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId; ?>', metaDataName: '<?php echo $this->metaDataName; ?>'},
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                if (data.status === 'success') {
                    $("ul.dropdown-menu li.lifecycle-btn").removeClass("disabled");
                    $("ul.dropdown-menu li.lifecycle-btn").attr("onclick", 'addLifeCycle();');
                    oTable.empty().append(data.result);
                } else {
                    oTable.empty();
                    $("ul.dropdown-menu li.lifecycle-btn").addClass("disabled");
                    $("ul.dropdown-menu li.lifecycle-btn").attr("onclick", '');
                }
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            //oTable.empty().append(html);
            $("#lifeCycleBookTblList").tabletree({
                initialState: 'collapsed',
                expanderExpandedClass: 'fa fa-minus',
                expanderCollapsedClass: 'icon-plus3 font-size-12'
            });
            $(".tabletree-indent").hide();
        });
    }

    function addLifeCycleBook() {
        var $dialogName = 'dialog-add-lifecycleform';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/addLifeCycleBookForm',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
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
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                criteria.save();
                                $("#lifecyclebook-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#lifecyclebook-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/insertLifecycleBook',
                                        data: $("#lifecyclebook-form").serialize(),
                                        dataType: 'json',
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
                                                $("#" + $dialogName).empty().dialog('close');
                                                initLifeCycleBookList();
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    }).done(function () {
                                        Core.initAjax();
                                    });
                                }
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
            criteria.refresh();
            Core.initAjax();
        });
    }

    function editLifeCycleBook(lcBookId) {
        var $dialogName = 'dialog-edit-lifecycleform';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/editLifeCycleBookForm',
            dataType: "json",
            data: {lcBookId: lcBookId, metaDataId: '<?php echo $this->metaDataId; ?>'},
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
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                criteria.save();
                                $("#lifecyclebook-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#lifecyclebook-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/updateLifecycleBook',
                                        data: $("#lifecyclebook-form").serialize(),
                                        dataType: 'json',
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
                                                $("#" + $dialogName).empty().dialog('close');
                                                initLifeCycleBookList();
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    }).done(function () {
                                        Core.initAjax();
                                    });
                                }
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
            criteria.refresh();
            Core.initAjax();
        });
    }

    function editLifeCycle(lifecycleId) {
        var $dialogName = 'dialog-edit-lifecycleform';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/editLifeCycleForm',
            dataType: "json",
            data: {lifecycleId: lifecycleId},
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
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                $("#lifecycle-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#lifecycle-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/updateLifecycle',
                                        data: $("#lifecycle-form").serialize(),
                                        dataType: 'json',
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
                                                $("#" + $dialogName).empty().dialog('close');
                                                initLifeCycleBookList();
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    }).done(function () {
                                        Core.initAjax();
                                    });
                                }
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

    function editLcBookLifecycle(lcBookId, lifecycleId) {
        var $dialogName = 'dialog-edit-lifecycleform';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/editLcBookLifeCycleForm',
            dataType: "json",
            data: {lcBookId: lcBookId, lifecycleId: lifecycleId, metaDataId: '<?php echo $this->metaDataId; ?>'},
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
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                criteria.save();
                                $("#lcbook-lifecycle-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#lcbook-lifecycle-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/updateLcBookLifecycle',
                                        data: $("#lcbook-lifecycle-form").serialize(),
                                        dataType: 'json',
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
                                                $("#" + $dialogName).empty().dialog('close');
                                                initLifeCycleBookList();
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    }).done(function () {
                                        Core.initAjax();
                                    });
                                }
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
            criteria.refresh();
            Core.initAjax();
        });
    }

    function viewLifeCycle(metaDataId, metaDataName, lcBookId, lcBookName, lifecycleId, lifecycleName) {
        var $dialogName = 'dialog-lifecycle-editor';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/lifecycleEditor',
            dataType: "json",
            data: {metaDataId: metaDataId, metaDataName: metaDataName, lcBookId: lcBookId, lcBookName: lcBookName, lifecycleId: lifecycleId, lifecycleName: lifecycleName},
            beforeSend: function () {
                $.getScript('assets/custom/addon/plugins/jsplumb/css/style.css');
                $.getScript('assets/custom/addon/plugins/kwicks/step.css');
                $.getScript('assets/custom/addon/plugins/jsplumb/lib/jsBezier-0.6.js');

                $.getScript('middleware/assets/js/mdtaskflow.js');
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
                    width: 1100,
                    height: 1100,
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
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
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });
    }

    function deleteLifecycle(lifecycleId) {
        var $dialogName = 'dialog-lifecycle-delete';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdcommon/deleteConfirm',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.Html);
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
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/deleteLifecycle',
                                    data: {lifecycleId: lifecycleId},
                                    dataType: "json",
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
                                            initLifeCycleBookList();
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                        $("#" + $dialogName).dialog('close');
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
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
        }).done(function () {
            Core.initAjax();
        });
    }
    
    function deleteLcBookLifecycle(dataModelId, lcBookId, lifecycleId) {
        var $dialogName = 'dialog-lcBookLifecycle-delete';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdcommon/deleteConfirm',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.Html);
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
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/deleteLcBookLifecycle',
                                    data: {dataModelId: dataModelId, lcBookId: lcBookId, lifecycleId: lifecycleId},
                                    dataType: "json",
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
                                            initLifeCycleBookList();
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                        $("#" + $dialogName).dialog('close');
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
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
        }).done(function () {
            Core.initAjax();
        });
    }
    
    function addLifeCycle() {
        var $dialogName = 'dialog-add-lifecycleform';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/addLifeCycleForm',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
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
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                $("#lifecycle-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#lifecycle-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/insertLifecycle',
                                        data: $("#lifecycle-form").serialize(),
                                        dataType: 'json',
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
                                                $("#" + $dialogName).empty().dialog('close');
                                                initLifeCycleBookList();
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    }).done(function () {
                                        Core.initAjax();
                                    });
                                }
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
    
    function editLcBook(lcBookId) {
        var $dialogName = 'dialog-edit-lifecycle-book-form';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/editLcBookForm',
            dataType: "json",
            data: {lcBookId: lcBookId, metaDataId: '<?php echo $this->metaDataId; ?>'},
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
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                criteria.save();
                                $("#lifecyclebook-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#lifecyclebook-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/updateLifecycleBook',
                                        data: $("#lifecyclebook-form").serialize(),
                                        dataType: 'json',
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
                                                $("#" + $dialogName).empty().dialog('close');
                                                initLifeCycleBookList();
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    }).done(function () {
                                        Core.initAjax();
                                    });
                                }
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
            criteria.refresh();
            Core.initAjax();
        });
    }
</script>