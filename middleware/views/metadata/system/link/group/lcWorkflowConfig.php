<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12" id="workFlowConfig">
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-6">
                <div class="btn-group">
                    <?php echo Form::button(array('class' => 'btn btn-xs green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'), 'onclick' => 'addWorkflowStatus();')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="table-scrollable">
        <table class="table table-hover workFlowStatusTblList" id="workFlowStatusTblList">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th style="width: 100px;"><?php echo $this->lang->line('META_00075'); ?></th>
                    <th><?php echo $this->lang->line('META_00125'); ?></th>
                    <th style="width: 100px;">Өнгө</th>
                    <th style="width: 70px;"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        initWorkFlowStatusTbl();
    });
    $.contextMenu({
        selector: '#workFlowStatusTblList tbody tr',
        callback: function(key, opt) {
            if (key === 'edit') {
                var wfmStatusId = $(this).find('input[name="wfmStatusId[]"]').val();
                editWorkFlowStatus(wfmStatusId);
            }
        },
        items: {
            "edit": {name: "<?php echo $this->lang->line('META_00058'); ?>", icon: "edit"}
        }
    });
    function initWorkFlowStatusTbl() {
        var oTable = $("#workFlowStatusTblList").find('tbody');
        var html = '';
        $.ajax({
            type: 'post',
            url: 'mdmeta/initWorkFlowStatus',
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                if (data.status === 'success') {
                    var i = 1;
                    $.each(data.result, function () {
                        html += '<tr>';
                        html += '<td>' + i + '<input type="hidden" value="' + this.WFM_STATUS_ID + '" name="wfmStatusId[]"></td>';
                        html += '<td>' + this.WFM_STATUS_CODE + '</td>';
                        html += '<td>' + this.WFM_STATUS_NAME + '</td>';
                        html += '<td><span class="badge label-sm" style="background-color:' + this.WFM_STATUS_COLOR + ';">' + this.WFM_STATUS_COLOR + '</span></td>';
                        html += '<td>';
                            html += '<a href="javascript:;" class="btn green btn-xs" onclick="editWorkFlowStatus(\'' + this.WFM_STATUS_ID + '\')"><i class="fa fa-edit"></i></a>';
                            html += '<a href="javascript:;" class="btn red btn-xs" onclick="deleteWorkFlowStatus(\'' + this.WFM_STATUS_ID + '\')"><i class="fa fa-trash"></i></a>';
                        html += '</td>';
                        html += '</tr>';
                        i++;
                    });
                }
                $.unblockUI();

            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            oTable.empty().append(html);
            Core.initAjax();
        });
    }
    function addWorkflowStatus() {
        var $dialogName = 'dialog-workflowstatusform';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/addWorkflowStatusForm',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId; ?>', metaDataCode: '<?php echo $this->metaDataCode; ?>', metaDataName: '<?php echo $this->metaDataName; ?>'},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
                if (!$().colorpicker) {
                    $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                    });
                } 
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 400,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                $("#saveworkflow-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#saveworkflow-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/saveWorkFlowStatus',
                                        data: $("#saveworkflow-form").serialize(),
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
                                                initWorkFlowStatusTbl();
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
    function editWorkFlowStatus(wfmStatusId){
        var $dialogName = 'dialog-workflowstatusform';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/editWorkflowStatusForm',
            dataType: "json",
            data: {wfmStatusId: wfmStatusId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
                if (!$().colorpicker) {
                    $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                    });
                } 
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 400,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                $("#saveworkflow-form").validate({
                                    errorPlacement: function () {
                                    }
                                });
                                if ($("#saveworkflow-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/updateWorkFlowStatus',
                                        data: $("#saveworkflow-form").serialize(),
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
                                                initWorkFlowStatusTbl();
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
    function deleteWorkFlowStatus(id) {
        var $dialogName = 'dialog-workflow-status-delete-confirm';
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
                                    url: 'mdmeta/deleteWorkFlowStatus',
                                    data: {id: id},
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
                                            initWorkFlowStatusTbl();
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
</script>

