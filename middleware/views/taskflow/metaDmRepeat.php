<div class="row-fluid" id="metaDmRepeatWindow">
    <div class="col-md-4">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-metadata-tab-folder" class="nav-link active" data-toggle="tab">Удирдлага</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active in" id="common-metadata-tab-folder">
                    <form role="form" id="metaDmRepeat-form" method="post">
                        <?php echo Form::hidden(array('name' => 'id', 'id' => 'id')); ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Source LifeCycle', 'for' => 'sourceLifeCycle', 'required' => 'required')); ?>
                                        <?php
                                        echo Form::select(
                                                array(
                                                    'name' => 'srcLifeCycleId',
                                                    'id' => 'srcLifeCycleId',
                                                    'class' => 'form-control form-control-sm select2',
                                                    'data' => $this->getMetaDmLifeCycle,
                                                    'op_value' => 'LIFECYCLE_ID',
                                                    'op_text' => 'LIFECYCLE_CODE|-|LIFECYCLE_NAME',
                                                    'onchange' => 'getMetaDmLifeCycleDtl($(this).val(), \'srcProcessId\', \'\');',
                                                    'data-path' => 'srcProcessId',
                                                    'required' => 'required'
                                                )
                                        );
                                        ?>
                                    </div>    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Source Process', 'for' => 'sourceProcess', 'required' => 'required')); ?>
                                        <?php
                                        echo Form::select(
                                                array(
                                                    'name' => 'srcProcessId',
                                                    'id' => 'srcProcessId',
                                                    'class' => 'form-control form-control-sm select2',
                                                    'required' => 'required'
                                                )
                                        );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Target LifeCycle', 'for' => 'trgLifeCycle', 'required' => 'required')); ?>
                                        <?php
                                        echo Form::select(
                                                array(
                                                    'name' => 'trgLifeCycleId',
                                                    'id' => 'trgLifeCycleId',
                                                    'class' => 'form-control form-control-sm select2',
                                                    'data' => $this->getMetaDmLifeCycle,
                                                    'op_value' => 'LIFECYCLE_ID',
                                                    'op_text' => 'LIFECYCLE_CODE|-|LIFECYCLE_NAME',
                                                    'onchange' => 'getMetaDmLifeCycleDtl($(this).val(), \'trgProcessId\', \'\');',
                                                    'data-path' => 'trgProcessId',
                                                    'required' => 'required'
                                                )
                                        );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Target Process', 'for' => 'trgProcess', 'required' => 'required')); ?>
                                        <?php
                                        echo Form::select(
                                                array(
                                                    'name' => 'trgProcessId',
                                                    'id' => 'trgProcessId',
                                                    'class' => 'form-control form-control-sm select2',
                                                    'required' => 'required'
                                                )
                                        );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Time period', 'for' => 'maxRepeatCount', 'required' => 'required')); ?>
                                        <?php
                                        echo Form::text(
                                                array(
                                                    'name' => 'maxRepeatCount',
                                                    'id' => 'maxRepeatCount',
                                                    'class' => 'form-control form-control-sm bigdecimalInit',
                                                    'required' => 'required'
                                                )
                                        );
                                        ?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>    
                        <div class="form-actions">
                            <?php echo Form::button(array('class' => 'btn green btn-sm', 'onclick' => 'repeatSaveForm();', 'value' => '<i class="fa fa-save"></i> ' . $this->lang->line('save_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn blue btn-sm', 'onclick' => 'repeatSearchForm();', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn grey-cascade btn-sm', 'onclick' => 'repeatResetForm();', 'value' => $this->lang->line('clear_btn'))); ?>
                        </div>
                    </form>    
                </div>
            </div>
        </div>    
    </div>
    <div class="col-md-8">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-metadata-tab-order" data-toggle="tab" class="nav-link active">Жагсаалт</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active in" id="common-metadata-tab-order">
                    <table id="metaDmRepeatTbl"></table>
                </div>
            </div>
        </div>    
    </div>
</div>
<script type="text/javascript">
    var metaDmRepeatWindowId = '#metaDmRepeatWindow';

    $(function () {
        $('#metaDmRepeatTbl').datagrid({
            url: 'mdtaskflow/metaDmRepeatDataGrid',
            rownumbers: true,
            singleSelect: true,
            ctrlSelect: true,
            pagination: true,
            pageSize: 20,
            width: 693,
            height: 380,
            fitColumn: true,
            nowrap: false,
            columns: [[
                    {field: 'SRC_PROCESS_NAME', title: 'Src process', sortable: true, width: 200},
                    {field: 'TRG_PROCESS_NAME', title: 'Trg process', sortable: true, width: 124},
                    {field: 'MAX_REPEAT_COUNT', title: 'Repeat count', sortable: true, width: 110},
                    {field: 'SRC_LIFECYCLE_NAME', title: 'Src lifeCycle', sortable: true, width: 140},
                    {field: 'TRG_LIFECYCLE_NAME', title: 'Trg lifeCycle', sortable: true, width: 105}
                ]],
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                $(this).datagrid('selectRow', index);
                $.contextMenu({
                    selector: "#common-metadata-tab-order .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, #common-metadata-tab-order .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    callback: function (key, opt) {
                        var row = opt.$trigger;
                        var rowIndex = row.index();
                        $('#metaDmRepeatTbl').datagrid('selectRow', rowIndex);
                        var elem = $('#metaDmRepeatTbl').datagrid('getSelected');
                        if (key === 'update') {
                            repeatSetFormValue(elem);
                        }
                        if (key === 'remove') {
                            repeatRemoveItem(elem);
                        }
                    },
                    items: {
                        "update": {name: "Засах", icon: "edit"},
                        "remove": {name: "Утгах", icon: "trash"}
                    }
                });
            },
            onDblClickRow: function (i, r) {
                repeatSetFormValue(r);
            },
            onLoadSuccess: function () {
                showGridMessage($(this));
            }
        });
    });

    function getMetaDmLifeCycleDtl(lifeCycleId, controlName, selectedId) {
        console.log(controlName);
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/getMetaDmLifeCycleDtl',
            dataType: 'json',
            data: {lifeCycleId: lifeCycleId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                console.log(data);
                var _cellSelect = $('#' + controlName, metaDmRepeatWindowId);
                _cellSelect.select2('val', '');
                $("option:gt(0)", _cellSelect).remove();
                $.each(data, function () {
                    _cellSelect.append($("<option />").val(this.PROCESS_META_DATA_ID).text(this.META_DATA_CODE + ' - ' + this.META_DATA_NAME));
                });
                _cellSelect.select2('val', selectedId);
                Core.unblockUI();
            }
        });
    }

    function repeatSaveForm() {
        $("#metaDmRepeat-form").validate({
            errorPlacement: function () {
            }
        });
        if ($("#metaDmRepeat-form").valid()) {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/saveMetaDmRepeat',
                data: $("#metaDmRepeat-form").serialize(),
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    if (data.status == 'success') {
                        new PNotify({
                            title: 'Амжилттай',
                            text: data.message,
                            type: 'success',
                            sticker: false
                        });
                        $('#metaDmRepeatTbl').datagrid('reload');
                    } else {
                        new PNotify({
                            title: 'Алдаа',
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
            }).done(function () {
                Core.initAjax();
            });
        }
    }

    function repeatSetFormValue(elem) {
        $('#id', metaDmRepeatWindowId).val(elem.ID);
        $('#srcLifeCycleId', metaDmRepeatWindowId).select2('val', elem.SRC_LIFECYCLE_ID);
        $('#trgLifeCycleId', metaDmRepeatWindowId).select2('val', elem.TRG_LIFECYCLE_ID);
        getMetaDmLifeCycleDtl(elem.SRC_LIFECYCLE_ID, 'srcProcessId', elem.SRC_PROCESS_ID);
        getMetaDmLifeCycleDtl(elem.TRG_LIFECYCLE_ID, 'trgProcessId', elem.TRG_PROCESS_ID);
        $('#maxRepeatCount', metaDmRepeatWindowId).val(elem.MAX_REPEAT_COUNT);
    }

    function repeatRemoveItem(row) {
        var $dialogName = 'dialog-confirm';
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
                                if (row.ID !== "") {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdtaskflow/removeMetaDmRepeat',
                                        data: {id: row.ID},
                                        dataType: "json",
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
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: dataSub.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $('#metaDmRepeatTbl').datagrid({
                                                url: 'mdtaskflow/metaDmRepeatDataGrid'});
                                            $("#" + $dialogName).dialog('close');
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
        }).done(function () {
            Core.initAjax();
        });
    }

    function repeatResetForm() {
        $('#metaDmRepeat-form').trigger("reset");
        $('.select2', metaDmRepeatWindowId).select2('val', '');
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/metaDmRepeatDataGrid',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $('#metaDmRepeatTbl').datagrid('loadData', []);
                $('#metaDmRepeatTbl').datagrid('loadData', data);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    function repeatSearchForm() {
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/metaDmRepeatDataGrid',
            data: $("#metaDmRepeat-form").serialize(),
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $('#metaDmRepeatTbl').datagrid('loadData', []);
                $('#metaDmRepeatTbl').datagrid('loadData', data);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    
</script>