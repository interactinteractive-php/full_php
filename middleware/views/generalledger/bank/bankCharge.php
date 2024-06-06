<div class="row">
    <div class="col-md-12" id="bankCharge-<?php echo $this->metaDataId ?>">
        <?php if ($this->dialogMode !== 'popup') { ?>
            <a href="javascript:;" class="btn btn-circle btn-secondary card-subject-btn-border mr10" onclick="backFirstContent(this);" style="padding: 0px 5px; margin-top: -5px;"><i class="icon-arrow-left22"></i></a>
            <span class="caption-subject font-weight-bold uppercase card-subject-blue glheader">
                <?php echo $this->title; ?>
            </span>
        <?php } ?>
        <div class="xs-form bp-banner-container ">
        <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'saveBankCharge-form', 'name' => 'saveBankCharge-form', 'enctype' => 'multipart/form-data',  'method' => 'post', 'action' => '#')); ?>
            <div class="col-md-12 center-sidebar pl0 pr0">
                <div class="table-scrollable table-scrollable-borderless bp-header-param pb5">
                    <table class="table table-sm table-no-bordered bp-header-param">
                        <tbody>
                            <tr>
                                <td class="text-right middle" style="width: 10%">
                                    <?php echo Form::label(array('text' => 'Харилцагч', 'for' => 'customer', 'required' => 'required')); ?>
                                </td>
                                <td class="middle" style="width: 27%" colspan="3">
                                    <div data-section-path="customerId">
                                        <div class="meta-autocomplete-wrap" data-section-path="customerId">
                                            <div class="input-group double-between-input" data-section-path="<?php echo Mdgl::$customerListDataViewCode; ?>" style='width:50% !important;'>
                                                <?php echo Form::hidden(array('name' => 'customerId_valueField', 'id' => 'customerId_valueField', 'value' => $this->row['customerid'], 'required' => 'required')) ?>
                                                <?php echo Form::text(array('name' => 'customerCode_displayField', 'id' => 'customerCode_displayField', 'class' => 'form-control form-control-sm  glCode-autocomplete', 'placeholder' => 'кодоор хайх', 'value' => $this->row['customername'], 'required' => 'required')); ?>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('<?php echo Mdgl::$customerListDataViewCode; ?>', 'single', 'customerSelectableGrid', '', this);"><i class="fa fa-search"></i></button>
                                                </span>     
                                                <span class="input-group-btn">
                                                    <?php echo Form::text(array('name' => 'customerName_nameField', 'id' => 'customerName_nameField', 'class' => 'form-control form-control-sm  glName-autocomplete', 'placeholder' => 'нэрээр хайх', 'value' => $this->row['customername'], 'required' => 'required')); ?>    
                                                </span>     
                                            </div>
                                        </div>                                            
                                    </div>
                                </td>                                    
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <hr class="custom solid">
                                </td>
                            </tr>
                            <tr>         
                                <td class="text-right middle" style="width: 10%">
                                    <?php echo Form::label(array('text' => 'Огноо', 'for' => 'bookDate', 'required' => 'required')); ?>
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <div data-section-path="bookDate">
                                        <div class="dateElement input-group" data-section-path="bookDate">
                                          <?php echo Form::text(array('name' => 'bookDate', 'id' => 'bookDate', 'class' => 'form-control form-control-sm dateInit', 'value' => $this->row['bookdate'], 'required' => 'required')); ?>
                                          <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                        </div>
                                    </div>
                                </td>                                    
                                <td class="text-right middle" style="width: 10%">
                                    <?php echo Form::label(array('text' => 'Дүн', 'for' => 'amountBase', 'required' => 'required')); ?>
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <div data-section-path="amountTotal">
                                        <?php echo Form::text(array('name' => 'amountBase', 'id' => 'amountBase', 'class' => 'form-control form-control-sm bigdecimalInit meta-autocomplete dateElement', 'value' => '', 'required' => 'required')); ?>
                                    </div>
                                </td>                                    
                            </tr>
                            <tr>                               
                                <td class="text-right middle" style="width: 10%">
                                    <?php echo Form::label(array('text' => 'Дугаар', 'for' => 'bookNumber', 'required' => 'required')); ?>
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <div data-section-path="bookNumber">
                                        <?php echo Form::text(array('name' => 'bookNumber', 'id' => 'bookNumber', 'class' => 'form-control form-control-sm meta-autocomplete dateElement', 'value' => $this->row['booknumber'], 'required' => 'required')); ?> 
                                    </div>
                                </td>                                    
                                <td class="text-right middle" style="width: 10%">
                                    <?php echo Form::label(array('text' => 'Ханш', 'for' => 'rate', 'required' => 'required')); ?>
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <div data-section-path="rate">
                                        <?php echo Form::text(array('name' => 'rate', 'id' => 'rate', 'class' => 'form-control form-control-sm bigdecimalInit dateElement ', 'value' => $this->row['rate'], 'required' => 'required')); ?>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>                                         
                                <td class="text-right middle" style="width: 10%">
                                    <label for="chargeDescription" data-label-path="description"> <span class="required">*</span>Гүйлгээний утга:</label>
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <div data-section-path="description">
                                        <?php echo Form::textArea(array('name' => 'description', 'id' => 'description', 'class' => 'form-control form-control-sm', 'value' => 'Банкны шимтгэл', 'required' => 'required', 'style' => 'width:300px !important')); ?>
                                    </div>
                                </td>                                 
                                <td class="text-right middle" style="width: 10%">
                                    <?php echo Form::label(array('text' => 'Дүн (төгрөг)', 'for' => 'amount', 'required' => 'required')); ?>
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <div data-section-path="amount">
                                        <?php echo Form::text(array('name' => 'amount', 'id' => 'amount', 'class' => 'form-control form-control-sm bigdecimalInit dateElement ', 'value' => '', 'required' => 'required')); ?>                                          
                                    </div>
                                </td>                                       
                            </tr>
                            <tr>                                        
                                <td class="text-right middle" style="width: 10%">
                                    <label for="param[description]" data-label-path="description"></label>
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <div data-section-path="description">
                                        <a href="javascript:;" style='text-decoration: underline;' onclick="callBillRateMethod()">Харилцагчийн тооцооны жагсаалт</a>
                                    </div>
                                </td>                                    
                                <td class="text-right middle" style="width: 10%">
                                    <label for="isUsedGl" >Журналд холбох:</label>                                        
                                </td>
                                <td class="middle" style="width: 27%" colspan="">
                                    <input type="checkbox" id="isUsedGl" name="connectGl" onclick="callConnectGl(this)" class="form-control form-control-sm booleanInit" value="0"/>
                                </td>                                    
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 pl0 pr0">
                <div class="connect-gl-"></div>
            </div>
            <?php if ($this->dialogMode !== 'popup') { ?>
                <div class="form-actions mt15 form-actions-btn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-right">
                                <button type="button" class="btn btn-circle btn-sm blue" onclick="backFirstContent(this);"><i class="fa fa-reply"></i> Буцах</button>
                                <?php echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success', 'onclick' => 'saveBankCharge();', 'value' => '<i class="fa fa-save"></i> ' . $this->lang->line('save_btn'))); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php echo Form::close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var bankChargeWindowId = "#bankCharge-<?php echo $this->metaDataId ?>";
    
    $(function () {
        $('#amountBase', bankChargeWindowId).on('change', function () {
            var amountBase = $('#amountBase').autoNumeric("get");
            var amount = $('#amount').autoNumeric("get");
            var rate = $('#rate').autoNumeric("get");
            setBankRangeAmount(amountBase, amount, rate);
        });
        $('#rate', bankChargeWindowId).on('change', function () {
            var amountBase = $('#amountBase').autoNumeric("get");
            var amount = $('#amount').autoNumeric("get");
            var rate = $('#rate').autoNumeric("get");
            setBankRangeAmount(amountBase, amount, rate);
        });
        $('#amount', bankChargeWindowId).on('change', function () {
            var amountBase = $('#amountBase').autoNumeric("get");
            var amount = $('#amount').autoNumeric("get");
            var rate = $('#rate').autoNumeric("get");
            setBankRangeAmount(amountBase, amount, rate);
        });
        $('#amountBase', bankChargeWindowId).on('keypress', function (e) {
            e.preventDefault();
            if (e.keyCode === 13) {
                $('#rate', bankChargeWindowId).focus();
            }
        });
        $('#rate', bankChargeWindowId).on('keypress', function (e) {
            e.preventDefault();
            if (e.keyCode === 13) {
                $('#amount', bankChargeWindowId).focus();
            }
        });
        $('#amount', bankChargeWindowId).on('keypress', function (e) {
            e.preventDefault();
            if (e.keyCode === 13) {
                $('.btn-save').focus();
            }
        });
    });
    
    function callBillRateMethod () {
        $.ajax({
            type: 'POST',
            url: 'mdgl/callBillRateForm',
            data: {
                metaDataId : '<?php echo $this->metaDataId ?>',
                selectedRow: <?php echo $this->selectedRow ?>
            },
            dataType: "json",
            success: function(resp) {
                $.getScript("assets/custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js").done(function( script, textStatus ) {
                    $.getScript("assets/custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js").done(function( script, textStatus ) {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"/>');

                        var $dialogName = 'dialog-billRate-Form';
                        if (!$($dialogName).length) {
                            $('<div id="' + $dialogName + '"></div>').appendTo('body');
                        }

                        $("#" + $dialogName).empty().html(resp.Html);
                        $("#" + $dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: resp.Title,
                            width: 1200,
                            minWidth: 700,
                            height: "auto",
                            modal: true,
                            position: {
                                my: 'top', 
                                at: 'top'
                            },
                            close: function() {
                                $("#" + $dialogName).empty().dialog('destroy').remove();
                            },                        
                            buttons: [
                                {text: resp.save_btn, class: 'btn btn-sm blue', click: function() {
                                    var rows = $(bankChargeBillWindowId+" #bankChargeCustomerBillGrid").datagrid('getSelections');
                                    if (rows.length > 0) {
                                        var totalDebitBaseAmount = 0;
                                        var totalCreditBaseAmount = 0;
                                        
                                        $.each(rows, function(key, row) {
                                            totalDebitBaseAmount += parseFloat(row.DEBIT_AMOUNT_BASE);
                                            totalCreditBaseAmount += parseFloat(row.CREDIT_AMOUNT_BASE);
                                        });
                                        var rate = $('#rate', bankChargeWindowId).autoNumeric("get");
                                        var $total = parseFloat(totalDebitBaseAmount) - parseFloat(totalCreditBaseAmount);
                                        $('#amountBase', bankChargeWindowId).autoNumeric("set",$total);
                                        $('#amount', bankChargeWindowId).autoNumeric("set", parseFloat(rate*$total));

                                        $("#" + $dialogName).empty().dialog('destroy').remove();
                                    }
                                    else {
                                        alert("Та мөр сонгоно уу!");
                                        return;
                                    }
                                }},
                                {text: resp.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                    $("#" + $dialogName).empty().dialog('destroy').remove();
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
                        Core.initAjax();
                    });
                });
            },
            error: function() {
                alert("Failed Call BillRateForm!");
            }
        });
    }
    
    function saveBankCharge () {
        PNotify.removeAll();
        $("#saveBankCharge-form", bankChargeWindowId).validate({errorPlacement: function() {
        }});
        if ($("#saveBankCharge-form", bankChargeWindowId).valid()) {
            var _isUsedGl = $('#isUsedGl', bankChargeWindowId).val();
            if (_isUsedGl == '0') {
                new PNotify({
                    title: 'Warning',
                    text: 'Журналтай холбоно уу?',
                    type: 'warning',
                    sticker: false
                });
                return;
            }
            $("#saveBankCharge-form", bankChargeWindowId).ajaxSubmit({
                type: 'post',
                data: {
                    processMetaDataId: <?php echo empty($this->processMetaDataId) ? "''" : $this->processMetaDataId ?>,  
                    methodId: <?php echo empty($this->metaDataId) ? "''" : $this->metaDataId ?>,
                    selectedRow: <?php echo $this->selectedRow ?>, 
                    bookTypeId : '45',
                    param: $("#saveBankCharge-form", bankChargeWindowId).serialize()
                },
                url: 'mdgl/createGlEntry',
                dataType: "json",
                success: function(data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        $('#rate', bankChargeWindowId).autoNumeric("set", '0');
                        $('#amountBase', bankChargeWindowId).autoNumeric("set", '0');
                        $('#amount', bankChargeWindowId).autoNumeric("set", '0');
                        $('.connect-gl-', bankChargeWindowId).empty();
        
                        $('#uniform-isUsedGl', bankChargeWindowId).find('span.checked').removeClass('checked');
                        $('#isUsedGl', bankChargeWindowId).val('0');
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                    }
                }
            });
        } else {
            $('html, body').animate({
                scrollTop: 0
            }, 0);
        } 
    }
    
    function callConnectGl(element) {
        $('.connect-gl-').empty();
        if (element.checked) {
            $(element).val(1);
        }
        else {
            $(element).val(0);
            return false;
        }
        PNotify.removeAll();
        $("#saveBankCharge-form", bankChargeWindowId).validate({errorPlacement: function() {
        }});
        if ($("#saveBankCharge-form", bankChargeWindowId).valid()) {
            $.ajax({
                type: 'POST',
                data: {
                    processMetaDataId: <?php echo empty($this->processMetaDataId) ? "''" : $this->processMetaDataId ?>, 
                    methodId: <?php echo empty($this->metaDataId) ? "''" : $this->metaDataId ?>, 
                    selectedRow: <?php echo $this->selectedRow ?>, 
                    bookTypeId : '45',
                    param: $("#saveBankCharge-form", bankChargeWindowId).serialize()
                },
                url: 'mdgl/callBankRangeGlEntry',
                dataType: "json",
                success: function(resp) {
                    PNotify.removeAll();
                    if (resp.status === 'success') {
                        $('.connect-gl-').empty().html(
                        '<div class="tabbable-line">'
                            + '<ul class="nav nav-tabs ">'
                                + '<li>'
                                    + '<a href="#tab_15_1" class="active" data-toggle="tab">Журнал бичилт</a>'
                                + '</li>'
                            + '</ul>'
                            + '<div class="tab-content">'
                                + '<div class="tab-pane active" id="tab_15_1">'
                                    + resp.Html
                                + '</div>'
                            + '</div>'
                        + '</div>');
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: resp.message,
                            type: resp.status,
                            sticker: false
                        });
                        $('#uniform-isUsedGl').find('span.checked').removeClass('checked');
                        $('#isUsedGl').val('0');
                    }
                },
                error: function() {
                    alert("Failed Call BillRateForm!");
                }
            });
        }
        else {
            $('#uniform-isUsedGl', bankChargeWindowId).find('span.checked').removeClass('checked');
            $('#isUsedGl', bankChargeWindowId).val('0');
        }
    }
    
    function customerSelectableGrid(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        $("#customerId_valueField", bankChargeWindowId).val(row.id);
        $("#customerCode_displayField", bankChargeWindowId).val(row.customercode);
        $("#customerName_nameField", bankChargeWindowId).val(row.customername);
    }
    
    function setBankRangeAmount(amountBase, amount, rate) {
        if (rate == '0' && amountBase == '0' && amount == '0') {
            $('#rate', bankChargeWindowId).autoNumeric("set", 0);
            $('#amountBase', bankChargeWindowId).autoNumeric("set", 0);
            $('#amount', bankChargeWindowId).autoNumeric("set", 0);
        }
        else {
            if (rate == '0' && rate.length > 0) {
                if (amountBase == '0' && amountBase.length > 0) {
                    $('#amountBase', bankChargeWindowId).autoNumeric("set", '0'); 
                    $('#amount', bankChargeWindowId).autoNumeric("set", amount);
                    $('#rate', bankChargeWindowId).autoNumeric("set", '0');
                }
                else {
                    if (amountBase != '0') {
                        $('#amount', bankChargeWindowId).autoNumeric("set", amount);
                        $('#amountBase', bankChargeWindowId).autoNumeric("set", amountBase);
                        $('#rate', bankChargeWindowId).autoNumeric("set", amount/amountBase);
                    }
                }
            } else {
                if (rate != '0') {
                    if (amountBase == '0' && amountBase.length > 0) {
                        $('#amountBase', bankChargeWindowId).autoNumeric("set", amount/rate); 
                        $('#amount', bankChargeWindowId).autoNumeric("set", amount);
                        $('#rate', bankChargeWindowId).autoNumeric("set", rate);
                    }
                    else {
                        $('#amount', bankChargeWindowId).autoNumeric("set", (amountBase*rate));
                        $('#amountBase', bankChargeWindowId).autoNumeric("set", amountBase);
                        $('#rate', bankChargeWindowId).autoNumeric("set", rate);
                    }
                }
            }
        }
    }
</script>