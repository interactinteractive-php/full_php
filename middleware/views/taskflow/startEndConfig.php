<div id="startEndConfigWindow" class="xs-form">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <form id="startEndConfigForm" name="startEndConfigForm">
        <div class="card light">
            <div class="card-body">

                <fieldset class="collapsible" data-initialized="1">

                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <div class="row">
                                <?php echo Form::label(array('text' => 'Current lifeCycle', 'for' => 'currentLifeCycle', 'class' => 'col-md-4 col-form-label')); ?>
                                <?php
                                echo Form::hidden(array('name' => 'currentLifeCycle', 'id' => 'currentLifeCycle', 'value' => $this->lifeCycleId));
                                echo Form::hidden(array('name' => 'currentProcessMetaDataId', 'id' => 'currentProcessMetaDataId', 'value' => $this->processMetaDataId));
                                ?>
                                <label class="col-md-8 col-form-label text-align-left"><strong><?php echo $this->getCurrentLifeCycleName; ?></strong></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <div class="row">
                                <?php echo Form::label(array('text' => 'Current process', 'for' => 'getCurrentProcessName', 'class' => 'col-md-4 col-form-label')); ?>
                                <label class="col-md-8 col-form-label text-align-left"><strong><?php echo $this->getCurrentProcessName; ?></strong></label>
                            </div>
                        </div>    
                    </div>
                    <div class="clearfix w-100"></div>
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <div class="row">
                                <?php echo Form::label(array('text' => 'Done LifeCycle', 'for' => 'doneLifeCycle', 'class' => 'col-md-4 col-form-label')); ?>
                                <div class="col-md-8">
                                    <?php
                                    echo Form::select(array(
                                        'name' => 'doneLifeCycle',
                                        'id' => 'doneLifeCycle',
                                        'class' => 'form-control select2me',
                                        'data' => $this->getDoneMetaDmLifeCycle,
                                        'op_value' => 'LIFECYCLE_ID',
                                        'op_text' => 'LIFECYCLE_NAME',
                                        'data-path' => 'LIFECYCLE_NAME',
                                        'onchange' => 'getLastProcess(this)',
                                        'required' => 'required'
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <div class="row">
                                <?php echo Form::label(array('text' => 'Done Process', 'for' => 'doneProcess', 'class' => 'col-md-4 col-form-label')); ?>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <?php
                                            echo Form::select(array(
                                                'name' => 'doneProcess',
                                                'id' => 'doneProcess',
                                                'class' => 'form-control select2me',
                                                'required' => 'required'
                                            ));
                                            ?>    
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="padding-top-30 btn btn-sm green-haze addStartEndProcess"><i class="icon-plus3 font-size-12"></i></button>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix w-100"></div>



                </fieldset>

                <div class="clearfix w-100 mb30"></div>


                <table class="table table-striped table-bordered table-hover" id="startEndDatatable">
                    <thead>
                        <tr>
                            <th style="width: 30px;"></th>
                            <th>Done LifeCycle</th>
                            <th>Done Process</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $this->startEndConfigList; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </form>
</div>

<script type="text/javascript">
    var startEndConfigWindowId = '#startEndConfigWindow';
    var startEndConfigForm = '#startEndConfigForm';
    var dataTableConfig;
    $(function () {
        $(".addStartEndProcess", startEndConfigWindowId).on("click", function () {
            var doneLifeCycle = $("#doneLifeCycle", startEndConfigWindowId).select2('data');
            var doneProcess = $("#doneProcess", startEndConfigWindowId).select2('data');
            var table = $("table#startEndDatatable tbody", startEndConfigWindowId);
            var addRow = true;
            if (doneLifeCycle.id != "" && doneProcess.id != "") {
                table.find('tr').each(function () {
                    var _this = $(this);
                    var rowLifeCycleId = _this.find('input[name="LIFECYCLE_ID[]"]').val();
                    var rowPrevProcessId = _this.find('input[name="PREV_PROCESS_ID[]"]').val();
                    if (rowLifeCycleId == doneLifeCycle.id && doneProcess.id == rowPrevProcessId) {
                        addRow = false;
                        return;
                    }
                });
                var row = '';
                if (addRow) {
                    if (doneLifeCycle.id != "" && doneProcess.id != "") {
                        $.ajax({
                            type: 'post',
                            url: 'mdtaskflow/updateStartEndConfig',
                            dataType: 'json',
                            data: $("#startEndConfigForm", "#startEndConfigWindow").serialize(),
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
                                    var counter = Number($("#rowCounter", startEndConfigWindowId).val()) + 1;
                                    $("#rowCounter", startEndConfigWindowId).val(counter);
                                    row += '<tr class="gradeX">';
                                    row += '<td>' + counter + '</td>';
                                    row += '<td>' + doneLifeCycle.text + '<input type="hidden" id="LIFECYCLE_ID' + counter + '" name="LIFECYCLE_ID[]" value="' + doneLifeCycle.id + '"></td>';
                                    row += '<td>' + doneProcess.text + '<input type="hidden" id="PREV_PROCESS_ID' + counter + '" name="PREV_PROCESS_ID[]" value="' + doneProcess.id + '">' + '</td>';
                                    row += '<th><div class="btn btn-sm red" onclick="removeStartEndConfig(this)"><i class="fa fa-trash"></i></div></th>';
                                    row += '</tr>';

                                    $("table#startEndDatatable tbody", startEndConfigWindowId).prepend(row);
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
                    Core.initAjax();
                } else {
                    var dialogName = '#deleteConfirm';
                    if (!$(dialogName).length) {
                        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                    }
                    $(dialogName).html('Холболт хийсэн процесс байна');
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
                            {text: 'Хаах', class: 'btn blue-madison btn-sm', click: function () {
                                    $(dialogName).dialog('close');
                                }}
                        ]
                    });
                    $(dialogName).dialog('open');
                }
            } else {
                $("#doneLifeCycle", startEndConfigWindowId).addClass('error');
                $("#doneProcess", startEndConfigWindowId).addClass('error');
            }
        });
    });
    function removeStartEndConfig(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var currentLifeCycle = $("#currentLifeCycle", startEndConfigWindowId).val();
        var currentProcessMetaDataId = $("#currentProcessMetaDataId", startEndConfigWindowId).val();
        var doneLifeCycle = row.find('input[name="LIFECYCLE_ID[]"]').val();
        var doneProcess = row.find('input[name="PREV_PROCESS_ID[]"]').val();
        var dialogName = '#deleteConfirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('Энэ холбоосыг устгахдаа итгэлтэй байна');
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
                        if (doneLifeCycle != '' || doneProcess != '') {
                            $.ajax({
                                type: 'post',
                                url: 'mdtaskflow/removeStartEndConfig',
                                dataType: 'json',
                                data: {nextLifeCycleId: currentLifeCycle, nextProcessId: currentProcessMetaDataId, previewLifeCycleId: doneLifeCycle, previewProcessId: doneProcess},
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
                                        row.remove();
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
                                    alert("Error lifeCycle");
                                }
                            }).done(function () {
                                Core.initAjax();
                            });
                        } else {
                            row.remove();
                        }
                        $(dialogName).dialog('close');
                    }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
            ]
        });
        $(dialogName).dialog('open');

    }
    function drawDoneLifeCycleList(lcBookId, contorlName, lifeCycleId) {
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/getDoneMetaDmLifeCycle',
            dataType: 'json',
            data: {lcBookId: lcBookId, lifeCycleId: lifeCycleId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                //console.log('lcBookId=' + lcBookId + ' | contorlName=' + contorlName + ' | selectId=' + selectId);
                var _cellSelect = $('#' + contorlName, startEndConfigWindowId);
                Core.initSelect2();
                _cellSelect.select2('val', '');
                $("option:gt(0)", _cellSelect).remove();
                $.each(data, function () {
                    _cellSelect.append($("<option />").val(this.LIFECYCLE_ID).text(this.LIFECYCLE_NAME));
                });
                Core.initSelect2();
                Core.unblockUI();
            },
            error: function () {
                alert("Error lifeCycle");
            }
        }).done(function () {
            Core.initAjax();
        });
    }
    function getLastProcess(elem) {
        var _this = $(elem);
        var lifeCycleId = _this.select2('val');
        var _cellSelect = $('#doneProcess', startEndConfigWindowId);
        _cellSelect.select2('val', '');
        _cellSelect.empty();
        if (lifeCycleId != '') {

            $("#doneLifeCycle", startEndConfigWindowId).removeClass('error');
            $("#doneProcess", startEndConfigWindowId).removeClass('error');

            $.ajax({
                type: 'post',
                url: 'mdtaskflow/getDoneLastProcess',
                dataType: 'json',
                data: {lifeCycleId: lifeCycleId},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    if (data.status === 'success') {
                        Core.initSelect2();

                        $("option:gt(0)", _cellSelect).remove();
                        $.each(data.result, function () {
                            _cellSelect.append($("<option />").val(this.id).text(this.name));
                        });
                        Core.initSelect2();
                        Core.unblockUI();
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.text,
                            type: 'error',
                            sticker: false
                        });
                    }
                    Core.initSelect2();
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error lifeCycle");
                }
            }).done(function () {
                Core.initAjax();
            });
        } else {

        }

    }
</script>