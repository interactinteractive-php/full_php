<form class="form-horizontal" role="form" method="post" id="metaProcessParameter-form">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row mb0">
                <label class="col-md-3 col-form-label">Main process:</label>
                <div class="col-md-9">
                    <p class="form-control-plaintext">
                        <?php
                        echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->mainBpId));
                        echo Form::hidden(array('name' => 'doProcessId', 'id' => 'doProcessId', 'value' => $this->doProcessid));
                        echo Form::hidden(array('name' => 'doBpParamIsInput', 'id' => 'doBpParamIsInput', 'value' => 1));

                        echo $this->mainBpName;
                        ?>
                    </p>
                </div>
            </div>
            <div class="form-group row mb0">
                <label class="col-md-3 col-form-label">Do process:</label>
                <div class="col-md-9">
                    <p class="form-control-plaintext">
                        <?php echo $this->doProcessName; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <label class="ml0 pl0 mb10 pt0">
                <input type="checkbox" id="viewParameters" value="0" class="notuniform mr-1"> <?php echo $this->lang->line('metadata_showallparameters'); ?> 
            </label>
            <input type="hidden" id="isCheck" value="0">
        </div>
        <div class="col-md-7 text-right">
            Path automap: 
            <?php
            echo Form::select(array(
                'class' => 'form-control form-control-sm d-inline-block mr-1 path-automap-bp',
                'style' => 'width: 250px;', 
                'data' => $this->getMetaDoneBpList,
                'op_value' => 'META_DATA_ID',
                'op_text' => 'META_DATA_NAME',
                'text' => '- Done BP сонгох -'
            ));
            ?>
            <label>
                <input type="checkbox" class="notuniform mr-1 path-automap-isinput"> Is input
            </label>
            <button type="button" class="btn btn-xs btn-primary ml-1 path-automap-set"><i class="far fa-sunset"></i></button>
        </div>
        <div class="col-md-12">
            <table class="table table-sm table-hover metaProcessParameter" id="bpChildParameterInput" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th style="min-width: 250px;">Param path</th>
                        <th class="pl30">Done BP</th>
                        <th class="text-center" style="width:40px;">Is input</th>
                        <th style="width: 20%;">Done param id</th>
                        <th style="width: 20%;">Done bp param path</th>
                        <th style="width: 10%;">Default value</th>
                        <th style="width: 20px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $this->getInputParameterList; ?>
                </tbody>
            </table>
        </div>
        <?php echo Form::hidden(array('id' => 'inputDoBpId', 'name' => 'inputDoBpId', 'value' => $this->doProcessid)) ?>
        <div class="clearfix w-100"></div>
    </div>
</form>

<style type="text/css">
    .metaProcessParameter thead tr th:nth-child(1){width: 200px;}
    .metaProcessParameter thead tr th:nth-child(2),
    .metaProcessParameter thead tr th:nth-child(3),
    .metaProcessParameter thead tr th:nth-child(4),
    .metaProcessParameter thead tr th:nth-child(5){width: 180px;}
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>

<script type="text/javascript">
    $(function () {
        
        /*$.getScript('assets/custom/addon/plugins/datatables/all.min.js').done(function() {
            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>');
            $('#bpChildParameterInput tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+ title +'" />' );
            });
            var table = $('#bpChildParameterInput').DataTable({
                "paging": false,
                "info":     false,
                "ordering": false
            });
            $('#myFilter_<?php echo $this->doProcessid ?>').on('keyup', function () {
                table.search(this.value).draw();
            } );
        });*/
        
        $('.path-automap-set').on('click', function() {
            var $bpCombo = $('.path-automap-bp');
            var bpComboId = $bpCombo.val();
            
            $bpCombo.removeClass('error');
            
            if (bpComboId) {
                
                var isInput = $('.path-automap-isinput').is(':checked') ? 1 : 0;
                
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/parameterListCheck',
                    data: {doneBpId: bpComboId, isCheck: isInput}, 
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        
                        if (Object.keys(data).length) {
                            
                            var $rows = $('#bpChildParameterInput > tbody > tr');
                            var options = [];
                            $.each(data, function(){
                                options.push('<option value="'+this.META_DATA_CODE+'">'+this.META_DATA_NAME + ' - ' + this.META_DATA_CODE+'</option>');
                            });
                            var optionsHtml = options.join('');

                            $rows.each(function() {
                                var $row = $(this);
                                var $doneBpId = $row.find('select[name*="inputDoneBpId[]"]');
                                var doBpParamPath = $row.find('input[name*="inputDoBpParamPath[]"]').val();
                                var $doneBpParamId = $row.find('select[name*="inputDoneBpParamId[]"]');
                                
                                $doneBpId.val(bpComboId);
                                
                                $doneBpParamId.find('option:gt(0)').remove();
                                $doneBpParamId.append(optionsHtml);
                                
                                $row.find('input[name*="inputDoneBpParamIsInput[]"]').prop('checked', (isInput == 1 ? true : false));
                                
                                if (!$doneBpParamId.data('select2')) {
                                    $doneBpParamId.val(doBpParamPath).trigger('change');
                                } else {
                                    $doneBpParamId.select2('val', doBpParamPath).trigger('change');
                                }
                            });
                        }

                        Core.unblockUI();
                    }
                });
                
            } else {
                $bpCombo.addClass('error');
            }
        });
        
        $("#viewParameters").on('click', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $("#isCheck").val(1);
                $(".metaProcessParameter").tabletree('expandAll');
            } else {
                $("#isCheck").val(0);
                $(".metaProcessParameter").tabletree('collapseAll');
            }
        });
        $(".metaProcessParameter").tabletree({
            initialState: 'collapsed',
            expanderExpandedClass: 'icon-minus3 font-size-12',
            expanderCollapsedClass: 'icon-plus3 font-size-12'
        });

        $('#metaProcessParameter-form').on('change', 'select[name="<?php echo $this->doProcessid; ?>inputDoneBpParamId[]"]', function () {
            var _this = $(this);
            var parent = _this.closest('tr');
            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val(_this.val());
        });
        
        $('#metaProcessParameter-form').on('focus', 'select[name="<?php echo $this->doProcessid; ?>inputDoneBpParamId[]"]', function () {
            var $this = $(this);
            if (!$this.data('select2')) {
                $this.select2({
                    allowClear: true,
                    dropdownAutoWidth: true
                });
                setTimeout(function() {
                    $this.select2('open');
                });
            }
        });

        $('#metaProcessParameter-form').on('change', 'select[name="<?php echo $this->doProcessid; ?>inputDoneBpId[]"]', function () {
            changeDoneBpParam(this);
        });

        $('#metaProcessParameter-form').on('click', 'input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInput[]"]', function () {
            var $this = $(this);
            var parent = $this.closest('tr');
            var doneBpId = parent.find("select[name='<?php echo $this->doProcessid; ?>inputDoneBpId[]']").val();
            var isCheck = 0;

            if (doneBpId != '') {

                if ($this.is(":checked")) {
                    isCheck = 1;
                    parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
                }
                if ($this.is(":unchecked")) {
                    parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
                }
                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInputHidden[]"]').val(isCheck);

                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/parameterListCheck',
                    data: {doneBpId: doneBpId, isCheck: isCheck},
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({animate: true});
                    },
                    success: function (data) {
                        var criteriaValueCombo = parent.find("select[name='<?php echo $this->doProcessid; ?>inputDoneBpParamId[]']");
                        var inputMetaDataName = parent.find('input[name="<?php echo $this->doProcessid; ?>inputMetaDataName[]"]').val();
                        var inputDoBpParamPath = parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoBpParamPath[]"]').val();
                        
                        criteriaValueCombo.find("option:gt(0)").remove();
                        
                        $.each(data, function () {
                            if (inputDoBpParamPath == this.META_DATA_CODE) {
                                criteriaValueCombo.append($("<option selected='selected' />").val(this.META_DATA_CODE).text(this.META_DATA_NAME + ' - ' + this.META_DATA_CODE));
                                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val(inputMetaDataName);
                            } else {
                                criteriaValueCombo.append($("<option />").val(this.META_DATA_CODE).text(this.META_DATA_NAME + ' - ' + this.META_DATA_CODE));
                            }
                        });
                        
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
                
                $this.prop('checked', false);
            }
        });
    });

    function changeDoneBpParam(elem) {
        var _this = $(elem);
        var doneBpId = _this.val();
        var parent = _this.closest('tr');
        var isCheck = 0;
        var inputDoBpParamPath = parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoBpParamPath[]"]').val();
        var criteriaValueCombo = parent.find("select[name='<?php echo $this->doProcessid; ?>inputDoneBpParamId[]']");
        
        if (doneBpId != '') {
            
            if (doneBpId == '<?php echo $this->mainBpId; ?>') {
                isCheck = 1;
                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInputHidden[]"]').val('1');
                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInput[]"]').prop('checked', true);
            } else {
                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInputHidden[]"]').val('0');
                parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInput[]"]').prop('checked', false);
            }
            
            $.ajax({
                type: 'post',
                url: 'mdprocessflow/parameterListCheck',
                data: {doneBpId: doneBpId, isCheck: isCheck}, 
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data){
                    criteriaValueCombo.find("option:gt(0)").remove();
                    
                    var inputMetaDataName = parent.find('input[name="<?php echo $this->doProcessid; ?>inputMetaDataName[]"]').val();
                    var setInputDoneBpParamPath = 0;
                    
                    $.each(data, function(){
                        if (inputDoBpParamPath == this.META_DATA_CODE) {
                            criteriaValueCombo.append($("<option selected='selected' />").val(this.META_DATA_CODE).text(this.META_DATA_NAME + ' - ' + this.META_DATA_CODE));
                            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val(inputMetaDataName);
                            setInputDoneBpParamPath = 1;
                        } else {
                            criteriaValueCombo.append($("<option />").val(this.META_DATA_CODE).text(this.META_DATA_NAME + ' - ' + this.META_DATA_CODE));
                        }
                    });
                    
                    if (setInputDoneBpParamPath === 0) {
                        parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
                    }
                    
                    criteriaValueCombo.trigger('change');
                    
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInputHidden[]"]').val(isCheck);
        } else {
            parent.find('select[name="<?php echo $this->doProcessid; ?>inputDoneBpParamId[]"]').empty();
            parent.find('select[name="<?php echo $this->doProcessid; ?>inputDoneBpParamId[]"]').append('<option></option>').trigger('change');
            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamPath[]"]').val('');
            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInputHidden[]"]').val(0);
            parent.find('input[name="<?php echo $this->doProcessid; ?>inputDoneBpParamIsInput[]"]').prop('checked', false);
            criteriaValueCombo.find("option:gt(0)").remove();
            Core.initSelect2(parent);
        }
    }
    
    function removeParameter(elem) {
        var dialogName = '#deleteConfirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);
        $dialog.html('Та мөрийн тохиргоог устгахдаа итгэлтэй байна уу?');
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Сануулах',
            width: '350',
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    var $row = $(elem).closest('tr');
                    var doBpId = $("input[name='inputDoBpId']").val();
                    var $inputDoneBpParamId = $row.find("select[name='" + doBpId + "inputDoneBpParamId[]']");
                    
                    $row.find("select[name='" + doBpId + "inputDoneBpId[]']").val('');
                    $row.find("input[name='" + doBpId + "inputDoneBpParamPath[]']").val('');
                    $row.find("input[name='" + doBpId + "inputDoneBpParamIsInput[]']").prop('checked', false);
                    $row.find("input[name='" + doBpId + "defaultValue[]']").val('');
                    
                    $inputDoneBpParamId.find('option:gt(0)').remove();
                    
                    if (!$inputDoneBpParamId.data('select2')) {
                        $inputDoneBpParamId.val('');
                    } else {
                        $inputDoneBpParamId.select2('val', '');
                    }
                    
                    $dialog.dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    }
</script>
