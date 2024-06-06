<div class="row-fluid" id="metaDmPeriodicLimitWindow">
    <div class="col-md-4">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-metadata-tab-folder" class="nav-link active" data-toggle="tab">Удирдлага</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active in" id="common-metadata-tab-folder">
                    <form role="form" id="metaDmPeriodic-form" method="post">
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
                                                    'op_text' => 'LIFECYCLE_NAME',
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
                                                    'op_text' => 'LIFECYCLE_NAME',
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
                                        <?php echo Form::label(array('text' => 'Time period', 'for' => 'timePeriod', 'required' => 'required')); ?>                                        <?php
                                        echo Form::text(
                                            array(
                                                'name' => 'timePeriod',
                                                'id' => 'timePeriod',
                                                'class' => 'form-control form-control-sm longInit',
                                                'required' => 'required'
                                            )
                                        );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Time type', 'for' => 'timeTypeId', 'required' => 'required')); ?>
                                        <?php
                                        echo Form::select(
                                                array(
                                                    'name' => 'timeTypeId',
                                                    'id' => 'timeTypeId',
                                                    'class' => 'form-control form-control-sm select2',
                                                    'data' => $this->getRefTimeType,
                                                    'op_value' => 'TIME_TYPE_ID',
                                                    'op_text' => 'TIME_TYPE_NAME',
                                                    'text' => '- Сонгох -',
                                                    'required' => 'required'
                                                )
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <div class="form-actions">
                            <?php echo Form::button(array('class' => 'btn green btn-sm', 'onclick' => 'periodicLimitSaveForm();', 'value' => '<i class="fa fa-save"></i> ' . $this->lang->line('save_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn blue btn-sm', 'onclick' => 'periodicLimitSearchForm();', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn grey-cascade btn-sm', 'onclick' => 'periodicLimitResetForm();', 'value' => $this->lang->line('clear_btn'))); ?>
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
                    <table id="metaDmPeriodicLimitTbl"></table>
                </div>
            </div>
        </div>    
    </div>
</div>
<script type="text/javascript">
    var metaDmPeriodicLimitWindowId = '#metaDmPeriodicLimitWindow';

    $(function () {
        
        $("#srcLifeCycleId", metaDmPeriodicLimitWindowId).on('change', function(){
            getMetaDmLifeCycleProcess("<?php echo $this->dataModelId;?>", $(this).val(), "srcProcessId", "");
        });
        $("#trgLifeCycleId", metaDmPeriodicLimitWindowId).on('change', function(){
            getMetaDmLifeCycleProcess("<?php echo $this->dataModelId;?>", $(this).val(), "trgProcessId", "");
        });
        $('#metaDmPeriodicLimitTbl').datagrid({
            url: 'mdtaskflow/metaDmPeriodicLimitDataGrid',
            queryParams: {lcBookId: <?php echo $this->lcBookId;?>},
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
                    {field: 'TIME_PERIOD', title: 'Time period', sortable: true, width: 110, formatter: function (v, r, i) {
                            return v + ' ' + r.TIME_TYPE_NAME;
                        }},
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
                        $('#metaDmPeriodicLimitTbl').datagrid('selectRow', rowIndex);
                        var elem = $('#metaDmPeriodicLimitTbl').datagrid('getSelected');
                        if (key === 'update') {
                            periodicLimitSetFormValue(elem);
                        }
                        if (key === 'remove') {
                            periodicLimitRemoveItem(elem);
                        }
                    },
                    items: {
                        "update": {name: "Засах", icon: "edit"},
                        "remove": {name: "Утгах", icon: "trash"}
                    }
                });
            },
            onDblClickRow: function (i, r) {
                periodicLimitSetFormValue(r);
            },
            onLoadSuccess: function () {
                showGridMessage($(this));
            }
        });
    });

    function getMetaDmLifeCycleProcess(dataModelId, lifeCycleId, controlName, selectedId) {
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/getMetaDmLifeCycleProcess',
            dataType: 'json',
            data: {dataModelId: dataModelId, lifeCycleId: lifeCycleId},
            beforeSend: function (data) {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                var _cellSelect = $('#' + controlName, metaDmPeriodicLimitWindowId);
                _cellSelect.select2('val', '');
                $("option:gt(0)", _cellSelect).remove();
                $.each(data, function () {
                    _cellSelect.append($("<option />").val(this.PROCESS_META_DATA_ID).text(this.META_DATA_NAME));
                });
                _cellSelect.select2('val', selectedId);
                Core.unblockUI();
            }
        });
    }

    function periodicLimitSaveForm() {
        $("#metaDmPeriodic-form").validate({
            errorPlacement: function () {
            }
        });
        if ($("#metaDmPeriodic-form").valid()) {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/saveMetaDmPeriodicLimit',
                data: $("#metaDmPeriodic-form").serialize(),
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
                        $('#metaDmPeriodicLimitTbl').datagrid('reload');
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

    function periodicLimitSetFormValue(elem) {
        console.log(elem);
        $('#id', metaDmPeriodicLimitWindowId).val(elem.ID);
        $('#srcLifeCycleId', metaDmPeriodicLimitWindowId).select2('val', elem.SRC_LIFECYCLE_ID);
        $('#trgLifeCycleId', metaDmPeriodicLimitWindowId).select2('val', elem.TRG_LIFECYCLE_ID);

        getMetaDmLifeCycleProcess('<?php echo $this->dataModelId;?>', elem.SRC_LIFECYCLE_ID, 'srcProcessId', elem.SRC_PROCESS_ID);
        getMetaDmLifeCycleProcess('<?php echo $this->dataModelId;?>', elem.TRG_LIFECYCLE_ID, 'trgProcessId', elem.TRG_PROCESS_ID);

        $('#timePeriod', metaDmPeriodicLimitWindowId).val(elem.TIME_PERIOD);
        $('#timeTypeId', metaDmPeriodicLimitWindowId).select2('val', elem.TIME_TYPE_ID);
    }

    function periodicLimitRemoveItem(row) {
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
                                        url: 'mdtaskflow/removeMetaDmPeriodicLimit',
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
                                            $('#metaDmPeriodicLimitTbl').datagrid({
                                                url: 'mdtaskflow/metaDmPeriodicLimitDataGrid',
                                                queryParams: {lcBookId: <?php echo $this->lcBookId;?>}});
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

    function periodicLimitResetForm() {
        $('#metaDmPeriodic-form').trigger("reset");
        $('.select2', metaDmPeriodicLimitWindowId).select2('val', '');
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/metaDmPeriodicLimitDataGrid',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $('#metaDmPeriodicLimitTbl').datagrid('loadData', []);
                $('#metaDmPeriodicLimitTbl').datagrid('loadData', data);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    function periodicLimitSearchForm() {
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/metaDmPeriodicLimitDataGrid',
            data: $("#metaDmPeriodic-form").serialize(),
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $('#metaDmPeriodicLimitTbl').datagrid('loadData', []);
                $('#metaDmPeriodicLimitTbl').datagrid('loadData', data);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    function periodicLimitResetControlVal(){
        var sidebarContent = $(".taskFlowMainConfig", metaProcessWindowId).find("table tbody");
        sidebarContent.empty();
    }
</script>