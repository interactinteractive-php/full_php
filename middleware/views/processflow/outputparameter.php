<form class="form-horizontal" role="form" method="post" id="inputParameter-form">
    <div class="col-md-10">
        <div class="form-group row fom-row">
            <label class="col-md-3 col-form-label">Main process: </label>
            <div class="col-md-9">
                <p class="form-control-plaintext">
                    <?php
                    echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->mainBpId));
                    echo Form::hidden(array('name' => 'doProcessId', 'id' => 'doProcessId', 'value' => $this->doProcessid));
                    echo Form::hidden(array('name' => 'doBpParamIsInput', 'id' => 'doBpParamIsInput', 'value' => 0));
                    foreach ($this->getMetaTypeProcessList as $value) {
                        if ($value['META_DATA_ID'] == $this->mainBpId) {
                            echo $value['META_DATA_CODE'] . ' - ' . $value['META_DATA_NAME'];
                        }
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#metaOutput" class="nav-link active" data-toggle="tab">Output</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active in" id="metaInput">

                    <input type="hidden" id="isCheck" value="0">
                    <table class="table table-sm table-bordered table-hover metaProcessParameter" id="bpChildParameterInput" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th style="width:20%;">Param_path</th>
                                <th>Done_BP</th>
                                <th class="text-center" style="width:50px;">IS_INPUT</th>
                                <th>Done_param_id</th>
                                <th style="width: 25%;">DONE_BP_PARAM_PATH</th>
                                <th style="width: 10%;">Default</th>
                                <th style="width: 20px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $this->getOutputParameterList; ?>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
    </div>
    <div class="clearfix w-100"></div>
</form>

<style type="text/css">
    .metaProcessParameter thead tr th:nth-child(1){width: 200px;}
    .metaProcessParameter thead tr th:nth-child(2),
    .metaProcessParameter thead tr th:nth-child(3),
    .metaProcessParameter thead tr th:nth-child(4),
    .metaProcessParameter thead tr th:nth-child(5){width: 180px;}
</style>

<script type="text/javascript">
    $(function () {
        $("#viewParameters").on('click', function () {
            var _this = $(this);
            var isShow = 0;
            if (_this.prop('checked')) {
                _this.attr("checked", true);
                _this.parents('span').addClass('checked');
                $("#isCheck").val(1);
                $("#bpChildParameterInput tbody tr").show();
            } else {
                _this.attr("checked", false);
                _this.parents('span').removeClass('checked');
                $("#bpChildParameterInput tbody tr[data-show='0']").hide();
                $("#isCheck").val(0);
            }

            //showList();
        });


        $(".metaProcessParameter").tabletree({
            initialState: 'collapsed',
            expanderExpandedClass: 'fa fa-minus',
            expanderCollapsedClass: 'icon-plus3 font-size-12'
        });

        $('#inputParameter-form').on('change', 'select[name="<?php echo $this->doProcessid; ?>inputDoneBpParamId[]"]', function () {
            var _this = $(this);
            var parent = _this.parents('tr');
            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val(_this.val());

        });

        $('#inputParameter-form').on('change', 'select[name="<?php echo $this->doProcessid; ?>inputDoneBpId[]"]', function () {
            var _this = $(this);
            var doneBpId = _this.val();
            var parent = _this.parents("tr");
            var isCheck = 0;
            var getIsInputCheckBox = parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInput[]"]');
            var inputDoBpParamPath = parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoBpParamPath[]"]').val();
            if (doneBpId === '<?php echo $this->mainBpId; ?>') {
                getIsInputCheckBox.prop('checked', true);
                getIsInputCheckBox.prop('disabled', true);
                isCheck = 1;
            } else {
                getIsInputCheckBox.prop('checked', false);
                getIsInputCheckBox.prop('disabled', false);
            }
            Core.updateUniform();

            var criteriaValueCombo = parent.find("select[name='<?php echo $this->doProcessid; ?>inputDoneBpParamId[]']");

            if (doneBpId != "") {
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/parameterListCheck',
                    data: {doneBpId: doneBpId, isCheck: isCheck},
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        criteriaValueCombo.find("option:gt(0)").remove();
                        var inputMetaDataName = parent.find('input[name="<?php echo $this->doProcessid; ?>inputMetaDataName[]"]').val()
                        var setInputDoneBpParamPath = 0;
                        
                        $.each(data, function(){
                            if (inputDoBpParamPath == this.META_DATA_CODE) {
                                criteriaValueCombo.append($("<option selected='selected' />").val(this.META_DATA_CODE).text(this.META_DATA_NAME));
                                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val(inputMetaDataName);
                                setInputDoneBpParamPath = 1;
                            } else {
                                criteriaValueCombo.append($("<option />").val(this.META_DATA_CODE).text(this.META_DATA_NAME));
                            }
                        });
                        
                        if (setInputDoneBpParamPath === 0) {
                            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
                        }
                        criteriaValueCombo.trigger('change');
                        
                        Core.initSelect2();
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            } else {
                parent.find('select[name="<?php echo $this->doProcessid; ?>inputDoneBpParamId[]"]').empty();
                parent.find('select[name="<?php echo $this->doProcessid; ?>inputDoneBpParamId[]"]').append('<option></option>').trigger('change');
                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
                criteriaValueCombo.find("option:gt(0)").remove();
                Core.initSelect2();
            }
        });

        $('#inputParameter-form').on('click', 'input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInput[]"]', function () {
            var _this = $(this);
            var parent = _this.parents("tr");
            var doneBpId = parent.find("select[name='<?php echo $this->doProcessid; ?>inputDoneBpId[]']").val();
            var isCheck = 0;
            if (doneBpId != '') {

                if (_this.is(":checked")) {
                    isCheck = 1;
                    parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
                }

                if (_this.is(":unchecked")) {
                    parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
                }
                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInputHidden[]"]').val(isCheck);

                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/parameterListCheck',
                    data: {doneBpId: doneBpId, isCheck: isCheck},
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        var criteriaValueCombo = parent.find("select[name='<?php echo $this->doProcessid; ?>inputDoneBpParamId[]']");
                        criteriaValueCombo.find("option:gt(0)").remove();
                        $.each(data, function () {
                            criteriaValueCombo.append($("<option />").val(this.META_DATA_CODE).text(this.META_DATA_NAME));
                        });
                        Core.initSelect2();
                        parent.find("select[name='<?php echo $this->doProcessid; ?>inputDoneBpParamId[]']").trigger('change');
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            } else {
                var dialogName = '#pleaseChooseDialog';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                $(dialogName).html('Done_Bp сонгоогүй байна').dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Сануулга',
                    width: '300',
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: '<?php echo $this->lang->line('close_btn'); ?>', class: 'btn blue-hoki btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                }).dialog('open');
                _this.attr("checked", false);
                _this.parents('span').removeClass('checked');
            }

        });
    });

    function showList() {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/getInputShowHideMetaParameterByProcess',
            data: {mainBpId: $("#metaDataId").val(), doProcessId: $("#doProcessId").val(), isShow: $("#isCheck").val(), isInputOutput: 0},
            beforeSend: function (data) {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#bpChildParameterInput tbody").html(data);
                Core.init();
                $(".metaProcessParameter").tabletree({
                    initialState: 'collapsed',
                    expanderExpandedClass: 'fa fa-minus',
                    expanderCollapsedClass: 'icon-plus3 font-size-12'
                });
                Core.unblockUI();
            },
            error: function () {
                Core.unblockUI();
            }
        });
    }

    function removeParameter(elem) {
        var row = $(elem).parents('tr');
        var doBpId = $("input[name='inputDoBpId']").val();
        var id = row.find("input[name='" + doBpId + "id[]']").val();
        if (id.length > 0) {
            $.ajax({
                type: 'post',
                url: 'mdprocessflow/deleteProcessParameter',
                data: {id: id},
                dataType: "json",
                beforeSend: function (data) {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: data.status,
                            text: 'Амжилттай хадгаллаа',
                            type: data.status,
                            sticker: false
                        });
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    showList();
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
        } else {
            row.find("select[name='" + doBpId + "inputDoneBpId[]']").val("");
            row.find("select[name='" + doBpId + "inputDoneBpParamId[]']").val("");
            row.find("input[name='" + doBpId + "inputDoneBpParamPath[]']").val("");
            row.find("input[name='" + doBpId + "inputDoneBpParamIsInput[]']").attr('checked', false);
            row.find("input[name='" + doBpId + "inputDoneBpParamIsInput[]']").parent().removeClass('checked');
            row.find("input[name='" + doBpId + "defaultValue[]']").val("");
        }
    }
</script>
