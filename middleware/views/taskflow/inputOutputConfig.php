<form class="form-horizontal" role="form" method="post" id="metaProcessParameter-form">
    <div class="col-md-10">
        <div class="form-group row fom-row mb0">
            <label class="col-md-3 col-form-label">Main lifeCycle: </label>
            <div class="col-md-9">
                <p class="form-control-plaintext"><?php echo $this->data['LIFECYCLE_NAME']; ?></p>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-md-3 col-form-label">Main process: </label>
            <div class="col-md-9">
                <p class="form-control-plaintext">
                    <?php echo $this->data['META_DATA_NAME']; ?>
                </p>
            </div>
        </div>
    </div>
    <?php
    echo Form::hidden(array('name' => 'doLcDtlId', 'id' => 'doLcDtlId', 'value' => $this->data['LIFECYCLE_DTL_ID']));
    echo Form::hidden(array('name' => 'doLcId', 'id' => 'doLcId', 'value' => $this->data['LIFECYCLE_ID']));
    echo Form::hidden(array('name' => 'doBpId', 'id' => 'doBpId', 'value' => $this->data['META_DATA_ID']));
    echo Form::hidden(array('name' => 'entityId', 'id' => 'entityId', 'value' => $this->entityId));
    ?>

    <div class="col-md-12">
        <label class="checkbox-inline ml0 pl0 mb20">
            <div class="checker" id="uniform-inlineCheckbox21"><span><input type="checkbox" id="showParameters" value="0"></span></div> Бүх параметрийг харуулах 
        </label>
        <input type="hidden" id="isCheck" value="0">
        <table class="table table-sm table-bordered table-hover parametersConfigTbl" cellspacing="0" width="100%" id="parametersConfigTbl">
            <thead>
                <tr>
                    <th class="middle">Parameter</th>
                    <th class="middle" style="min-width: 250px;">Param path</th>
                    <th class="text-center">Done lifeCycle</th>
                    <th>Done process</th>
                    <th class="text-center" style="width:50px;">IS_INPUT</th>
                    <th>Done parameter</th>
<!--                    <th style="min-width: 250px; display: none;">Done param path</th>-->
                    <th style="min-width: 150px;">Default value</th>
                    <th style="width: 20px;"></th>
                </tr>
            </thead>
            <tbody><?php echo $this->parameterList; ?></tbody>
        </table>

    </div>
    <div class="clearfix w-100"></div>
</form>

<script type="text/javascript">
    var formId = "#metaProcessParameter-form";
    $(function () {
        $("#showParameters", formId).on('click', function () {
            var _this = $(this);
            var isShow = 0;
            if (_this.prop('checked')) {
                _this.attr("checked", true);
                _this.parents('span').addClass('checked');
                $("#isCheck", formId).val(1);
                $("#parametersConfigTbl tbody tr", formId).show();
                $(".parametersConfigTbl", formId).tabletree({
                    initialState: 'collapsed',
                    expanderExpandedClass: 'fa fa-minus',
                    expanderCollapsedClass: 'icon-plus3 font-size-12'
                });
            } else {
                _this.attr("checked", false);
                _this.parents('span').removeClass('checked');
                $("#parametersConfigTbl tbody tr[data-show='0']", formId).hide();
                $("#isCheck", formId).val(0);
            }
        });
        $(".parametersConfigTbl", formId).tabletree({
            initialState: 'collapsed',
            expanderExpandedClass: 'fa fa-minus',
            expanderCollapsedClass: 'icon-plus3 font-size-12'
        });

        $("#parametersConfigTbl", formId).find('tr[data-show="0"]').hide();
    });
    function changeLifeCycle(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var entityId = $('input[name="entityId"]', formId).val();
        var doneLifeCycleData = _this.select2('data');
        var doneLcDtlId = row.find('input[name="doneLcDtlId[]"]');
        var doneLifeCycle = row.find('select[name="doneLifeCycleId[]"]');
        var doneProcess = row.find('select[name="doneProcessId[]"]');
        var doneProcessParam = row.find('select[name="doneProcessParam[]"]');
        var doneProcessParamPath = row.find('input[name="doneProcessParamPath[]"]');
        var doneIsInput = row.find('input[name="doneIsInput[]"]');
        var doneIsInputCheckBox = row.find('input[type="checkbox"]');
        var doneRemoveBtn = row.find('.remove-btn');
        
        var childRow = $('.tabletree-parent-' + row.attr('data-row'));
        var childDoneLcDtlId = childRow.find('input[name="doneLcDtlId[]"]');
        var childDoneLifeCycle = childRow.find('select[name="doneLifeCycleId[]"]');
        var childDoneProcess = childRow.find('select[name="doneProcessId[]"]');
        var childDoneIsInput = childRow.find('input[name="doneIsInput[]"]');
        var childDoneIsInputCheckBox = childRow.find('input[type="checkbox"]');
        var childDoneProcessParam = childRow.find('select[name="doneProcessParam[]"]');
        var childDoneProcessParamPath = childRow.find('input[name="doneProcessParamPath[]"]');
        var childDoneRemoveBtn = childRow.find('.remove-btn');
        if (doneLifeCycleData.id != "") {
            if (entityId === doneLifeCycleData.id) {
                $.ajax({
                    type: 'post',
                    url: 'mdtaskflow/initDataViewParameters',
                    dataType: 'json',
                    data: {entityId: doneLifeCycleData.id},
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        doneLcDtlId.val('');
                        doneIsInputCheckBox.attr('disabled', true);
                        doneIsInputCheckBox.parent().removeClass('checked');
                        doneIsInput.val(0);
                        doneProcessParamPath.val('');
                        $("option:gt(0)", doneProcessParam).remove();
                        $("option:gt(0)", doneProcess).remove();
                        doneProcess.append($("<option />").val(doneLifeCycleData.id).text(doneLifeCycleData.text));
                        doneProcess.select2('val', doneLifeCycleData.id);
                        doneRemoveBtn.attr('disabled', false);
                        doneProcessParam.select2('val', '');
                        
                        childDoneLifeCycle.select2('val', doneLifeCycleData.id);
                        childDoneLcDtlId.val('');
                        childDoneIsInputCheckBox.attr('disabled', true);
                        childDoneIsInputCheckBox.attr('checked', false);
                        childDoneIsInputCheckBox.parent().removeClass('checked');
                        childDoneIsInput.val(0);
                        childDoneProcessParamPath.val('');
                        $("option:gt(0)", childDoneProcessParam).remove();
                        $("option:gt(0)", childDoneProcess).remove();
                        childDoneProcess.select2('val', '');
                        childDoneProcess.append($("<option />").val(doneLifeCycleData.id).text(doneLifeCycleData.text));
                        childDoneProcess.select2('val', doneLifeCycleData.id);
                        childDoneProcessParam.select2('val', '');
                        childDoneRemoveBtn.attr('disabled', false);
                        
                        $.each(data, function () {
                            doneProcessParam.append($("<option />").val(this.PARAM_PATH).text(this.PARAM_PATH));//META_DATA_NAME
                            childDoneProcessParam.append($("<option />").val(this.PARAM_PATH).text(this.PARAM_PATH));//META_DATA_NAME
                            
                        });
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                }).done(function () {
                    doneLifeCycle.select2("readonly", false);
                    doneProcess.select2("readonly", true);
                    doneIsInputCheckBox.attr('readonly', true);
                    childDoneLifeCycle.select2("readonly", true);
                    childDoneProcess.select2("readonly", true);
                    childDoneIsInputCheckBox.attr('readonly', true);
                    doneRemoveBtn.find('button.remove-btn').attr('disabled', false);
                    
                    $('table.parametersConfigTbl tbody tr').each(function(){
                        var rowPath = $(this).attr('data-row');
                        rowPath = rowPath.replace("-", ".");
                        rowPath = rowPath.substring(0,(rowPath.length-1));
                        
                        var doneProcessParam = $(this).find('select[name="doneProcessParam[]"]');
                        $.each($(this).find('select[name="doneProcessParam[]"] option'), function(){
                            if (rowPath == $(this).attr('value')) {
                                doneProcessParam.select2('val', rowPath);
                                $(this).find('input[name="doneProcessParamPath[]"]').val(rowPath);
                                //enable remove button
                                $(this).find('button.remove-btn').attr('disabled', false);
                            }
                        });
                    });
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: 'mdtaskflow/getProcessList',
                    dataType: 'json',
                    data: {lifeCycleId: doneLifeCycleData.id, mainBpId: $("#mainBpId", formId).val()},
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        doneLcDtlId.val('');
                        doneIsInputCheckBox.attr('disabled', false);
                        doneIsInputCheckBox.attr('checked', false);
                        doneIsInputCheckBox.parent().removeClass('checked');
                        doneIsInput.val(0);
                        doneProcessParamPath.val('');
                        $("option:gt(0)", doneProcessParam).remove();
                        $("option:gt(0)", doneProcess).remove();
                        doneProcess.select2('val', '');
                        doneProcessParam.select2('val', '');
                        doneRemoveBtn.attr('disabled', true);

                        childDoneLifeCycle.select2('val', doneLifeCycleData.id);
                        childDoneLcDtlId.val('');
                        childDoneIsInputCheckBox.attr('disabled', false);
                        childDoneIsInputCheckBox.attr('checked', false);
                        childDoneIsInputCheckBox.parent().removeClass('checked');
                        childDoneIsInput.val(0);
                        childDoneProcessParamPath.val('');
                        $("option:gt(0)", childDoneProcessParam).remove();
                        $("option:gt(0)", childDoneProcess).remove();
                        childDoneProcess.select2('val', '');
                        childDoneProcessParam.select2('val', '');
                        childDoneRemoveBtn.attr('disabled', true);
                        $.each(data, function () {
                            childDoneProcess.append($("<option />").val(this.PROCESS_META_DATA_ID).text(this.META_DATA_NAME));
                            doneProcess.append($("<option />").val(this.PROCESS_META_DATA_ID).text(this.META_DATA_NAME));
                        });
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                }).done(function () {
                    doneLifeCycle.select2("readonly", false);
                    doneProcess.select2("readonly", false);
                    doneIsInputCheckBox.attr('readonly', false);
                    childDoneLifeCycle.select2("readonly", true);
                    childDoneProcess.select2("readonly", false);
                    childDoneIsInputCheckBox.attr('readonly', false);
                });
            }

        } else {
            doneProcess.select2('val', '');
            doneProcess.select2('readonly', true);
            doneProcessParam.select2('val', '');
            doneProcessParam.select2('readonly', true);
            doneProcessParamPath.val('');

            childDoneLifeCycle.select2('val', '');
            childDoneLifeCycle.select2("readonly", true);
            childDoneLcDtlId.val('');
            childDoneIsInputCheckBox.attr('disabled', false);
            childDoneIsInputCheckBox.attr('checked', false);
            childDoneIsInputCheckBox.parent().removeClass('checked');
            childDoneIsInput.val(0);

            childDoneProcess.select2('val', '');
            childDoneProcess.select2('readonly', true);
            childDoneProcessParam.select2('val', '');
            childDoneProcessParam.select2('readonly', true);
            childDoneProcessParamPath.val('');

        }
    }
    function changeProcess(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var selectRowPath = row.attr('data-row');
        var selectDataPath = row.attr('data-path');
        var selectDoneProcessId = _this.val();
        var doneLcDtlId = row.find('input[name="doneLcDtlId[]"]');
        var doneLifeCycle = row.find('select[name="doneLifeCycleId[]"]');
        var doneProcessParam = row.find('select[name="doneProcessParam[]"]');
        var doneProcessParamPath = row.find('input[name="doneProcessParamPath[]"]');
        doneProcessParamPath.val('');
        var doneIsInput = row.find('input[name="doneIsInput[]"]');
        var doneIsInputCheckBox = row.find('input[type="checkbox"]');
        var removeBtn = row.find('.remove-btn');
        removeBtn.attr('disabled', true);
        var doneProcessData = _this.select2('data');
        var doneLifeCycleData = doneLifeCycle.select2('data');

        if ( doneProcessData.id != '') {
            //Dataview uyd ajillana
            if (doneProcessData.id === doneLifeCycleData.id) {
                $.ajax({
                    type: 'post',
                    url: 'mdtaskflow/initDataViewParameters',
                    dataType: 'json',
                    data: {entityId: doneLifeCycleData.id},
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        doneLcDtlId.val('');
                        doneProcessParam.select2('readonly', false);
                        doneIsInputCheckBox.attr('disabled', false);
                        doneIsInputCheckBox.attr('checked', false);
                        doneIsInputCheckBox.parent().removeClass('checked');
                        doneIsInput.val(0);
                        doneProcessParamPath.val('');
                        $("option:gt(0)", doneProcessParam).remove();
                        $.each(data, function () {
                            doneProcessParam.append($("<option />").val(this.PARAM_PATH).text(this.META_DATA_NAME));
                        });
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: 'mdtaskflow/getLifeCycleDtlId',
                    dataType: 'json',
                    data: {doneLifeCycleId: doneLifeCycleData.id, doneProcessId: doneProcessData.id},
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        console.log(data);
                        doneLcDtlId.val(data);
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
                changeParameterValue(elem, '');
                
                $('table.parametersConfigTbl tbody tr td select[name="doneProcessId[]"]').each(function(){
                    var _this = $(this);
                    var row = _this.parents('tr');
                    var rowParentPath = row.attr('data-row-parent');
                    var rowPath = row.attr('data-row');
                    if (selectRowPath === rowParentPath) {
                        _this.select2('val', selectDoneProcessId);
                        rowPath = rowPath.replace("-", ".");
                        rowPath = rowPath.substring(0,(rowPath.length-1));
                        changeParameterValue(_this, rowPath);
                        //enable remove button
                        row.find('button.remove-btn').attr('disabled', false);
                    }
                });
            }

        } else {
            doneLcDtlId.val('');
            doneProcessParam.select2('val', '');
            doneProcessParamPath.val('');
            doneIsInput.val('0');
            doneIsInputCheckBox.attr('checked', false);
            doneIsInputCheckBox.parent().removeClass('checked');
            $("option:gt(0)", doneProcessParam).remove();
            doneProcessParam.select2('readonly', true);
        }
    }
    function clickDoneIsInput(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var doneLifeCycleId = row.find('select[name="doneLifeCycleId[]"]');
        var doneProcessId = row.find('select[name="doneProcessId[]"]');
        var doneIsInput = row.find('input[name="doneIsInput[]"]');
        var doneProcessParamPath = row.find('input[name="doneProcessParamPath[]"]');
        var doneIsInputCheckBox = row.find('input[type="checkbox"]');
        var removeBtn = row.find('.remove-btn');
        removeBtn.attr('disabled', true);
        if (_this.prop('checked')) {
            doneIsInput.val(1);
        } else {
            doneIsInput.val(0);
        }
        if (doneLifeCycleId.select2('val') != '' && doneProcessId.select2('val')) {
            doneProcessParamPath.val('');
            changeParameterValue(elem, '');
        } else {
            doneIsInput.val('0');
            doneIsInputCheckBox.attr('checked', false);
            doneIsInputCheckBox.parent().removeClass('checked');
            var dialogName = '#emptyDialog';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            $(dialogName).html('Та өмнөх lifeCycle, process-н аль нэгийг сонгоогүй байна');
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
                        }
                    }
                ]
            });
            $(dialogName).dialog('open');
        }
    }
    function changeParameterValue(elem, selectPath) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var processMetaDataId = row.find('select[name="doneProcessId[]"]').select2('val');
        var doneProcessParam = row.find('select[name="doneProcessParam[]"]');
        var doneProcessParamPath = row.find('input[name="doneProcessParamPath[]"]');
        var isInput = 0;
        if (_this.prop('checked')) {
            isInput = 1;
        }
        doneProcessParam.select2('readonly', true);
        $.ajax({
            type: 'post',
            url: 'mdtaskflow/getProcessParameterList',
            dataType: 'json',
            data: {isInput: isInput, processMetaDataId: processMetaDataId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("option:gt(0)", doneProcessParam).remove();
                doneProcessParam.select2('val', '');
                $.each(data, function () {
                    doneProcessParam.append($("<option />").val(this.PARAM_PATH).text(this.PARAM_PATH));
                });
                Core.unblockUI();
                doneProcessParam.select2('readonly', false);
            },
            error: function () {
                alert("Error");
            }
        }).done(function(data){
            if (selectPath != '' && data.length > 0) {
                doneProcessParam.select2('val', selectPath);
                doneProcessParamPath.val(selectPath);
            }
            
        });

    }
    function changeProcessParamValue(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var doneProcessParam = row.find('select[name="doneProcessParam[]"]');
        var doneProcessParamPath = row.find('input[name="doneProcessParamPath[]"]');
        var removeBtn = row.find('.remove-btn');
        if (_this.select2('val') != '') {
            removeBtn.attr('disabled', false);
            doneProcessParamPath.val(doneProcessParam.select2('val'));
        } else {
            doneProcessParamPath.val('');
            removeBtn.attr('disabled', true);
        }
    }
    function removeParameter(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var dataRowPath = row.attr('data-row');
        var childTr = _this.parents('table').find('tbody tr[data-row-parent="'+dataRowPath+'"]');
        var lcBpParamLinkId = row.find('input[name="lcBpParamLinkId[]"]').val();
        var doneLifeCycleId = row.find('select[name="doneLifeCycleId[]"]');
        var doneProcessId = row.find('select[name="doneProcessId[]"]');
        var doneIsInput = row.find('select[name="doneIsInput[]"]');
        var doneIsInputCheckBox = row.find('input[type="checkbox"]');
        var doneProcessParam = row.find('select[name="doneProcessParam[]"]');
        var doneProcessParamPath = row.find('select[name="doneProcessParamPath[]"]');
        var dialogName = '#removeDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('Та тохиргоог устгахдаа итгэлтэй байна уу?');
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
                {text: 'Тийм', class: 'btn green btn-sm', click: function () {
                    Core.blockUI({
                        animate: true
                    });
                    removeRequest(lcBpParamLinkId);
                    row.find('input[name="lcBpParamLinkId[]"]').val('');
                    doneLifeCycleId.select2('val', '').trigger('change');
                    doneProcessId.select2('val', '').trigger('change');
                    doneIsInput.val('0');
                    doneIsInputCheckBox.attr('checked', false);
                    doneIsInputCheckBox.parent().removeClass('checked');
                    doneProcessParam.select2('val', '').trigger('change');
                    doneProcessParamPath.val('');    
                    if (childTr.length > 0) {
                        $.each(childTr, function(){
                            var _childRow = $(this);
                            removeRequest(_childRow.find('input[name="lcBpParamLinkId[]"]').val());
                            _childRow.find('input[name="lcBpParamLinkId[]"]').val('');
                            _childRow.find('select[name="doneLifeCycleId[]"]').select2('val', '').trigger('change');
                            _childRow.find('select[name="doneProcessId[]"]').select2('val', '').trigger('change');
                            _childRow.find('select[name="doneIsInput[]"]').val('0');
                            _childRow.find('input[type="checkbox"]').attr('checked', false);
                            _childRow.find('input[type="checkbox"]').parent().removeClass('checked');
                            _childRow.find('select[name="doneProcessParam[]"]').select2('val', '').trigger('change');
                            _childRow.find('select[name="doneProcessParamPath[]"]').val('');    
                        });
                    }
                    new PNotify({
                        title: 'Success',
                        text: 'Амжилттай устгалаа',
                        type: 'success',
                        sticker: false
                    });
                    Core.unblockUI();
                    $(dialogName).dialog('close');
                }},
                {text: 'Хаах', class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }
                }
            ]
        });
        $(dialogName).dialog('open');
    }
    
    function removeRequest(lcBpParamLinkId) {
        if (lcBpParamLinkId.length > 0) {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/deleteBpParamLink',
                dataType: 'json',
                async: false,
                data: {lcBpParamLinkId: lcBpParamLinkId},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    result = data;
                    Core.unblockUI();
                    
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    }
</script>