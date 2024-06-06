<div class="table-toolbar">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <?php
                echo Html::anchor(
                    'javascript:;', '<i class="far fa-plus"></i> ' . $this->lang->line('add_btn'), 
                    array(
                        'class' => 'btn green btn-sm',
                        'onclick' => 'addConfigValue(this);'
                    ), $this->isAdd
                );
                echo Html::anchor(
                    'javascript:;', '<i class="far fa-edit"></i> ' . $this->lang->line('edit_btn'), 
                    array(
                        'class' => 'btn blue btn-sm',
                        'onclick' => 'editConfigValue(this);'
                    ), $this->isEdit
                );
                echo Html::anchor(
                    'javascript:;', '<i class="far fa-trash"></i> ' . $this->lang->line('delete_btn'), 
                    array(
                        'class' => 'btn red btn-sm',
                        'onclick' => 'deleteConfigValue(this);'
                    ), $this->isDelete
                );
                ?>  
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 jeasyuiTheme3">
        <table id="configValueDataGrid"></table>
    </div>
</div>

<script type="text/javascript">
var mdConfigWindowId = "#md-config";
var configValueDataGrid = $('#configValueDataGrid', mdConfigWindowId);

$(function() {
    var gridHeight = elemHeight(mdConfigWindowId, 120, 0);

    configValueDataGrid.datagrid({
        url: 'mdconfig/configValueDataGrid',
        queryParams: {params: '<?php echo $this->params; ?>'},
        rownumbers: true,
        singleSelect: true,
        pagination: true,
        pageSize: 20,
        height: gridHeight,
        striped: false,
        remoteFilter: true,
        filterDelay: 10000000000,
        columns: [[
            {field: 'CODE', title: 'Түлхүүр', align: 'left', sortable: true},
            {field: 'DESCRIPTION', title: 'Тайлбар', align: 'left', width: '200', sortable: true}, 
            {field: 'CONFIG_VALUE', title: 'Утга', align: 'left', width: '200', sortable: true},
            {field: 'CRITERIA', title: 'Нөхцөл', align: 'left', sortable: true},
        ]],
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            $(this).datagrid('selectRow', index);
            $.contextMenu({
                selector: "#config_tab_2 .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'edit') {
                        editConfigValue(this);
                    } else if (key === 'delete') {
                        deleteConfigValue(this);
                    }
                },
                items: {
                    "edit": {name: "Засах", icon: "edit"}, 
                    "delete": {name: "Устгах", icon: "trash"}
                }
            });
        },
        onLoadSuccess: function(){
            showGridMessage(configValueDataGrid);
            configValueDataGrid.datagrid('resize');
        }
    });
    configValueDataGrid.datagrid('enableFilter');
    $(window).bind('resize', function(){
        configValueDataGrid.datagrid('resize'); 
    });
});

function dialogEncryptionForm() {
    var $dialogEncryptName = 'dialog-config-encrypted';
    if (!$("#" + $dialogEncryptName).length) {
        $('<div id="' + $dialogEncryptName + '"></div>').appendTo('body');
    }
    var $dialogEncrypt = $('#' + $dialogEncryptName), encryptTbl = [];

    encryptTbl.push('<div class="form-group row">');
        encryptTbl.push('<div class="col-lg-12">');
            encryptTbl.push('<div class="input-group">');
                encryptTbl.push('<input type="password" class="form-control config-value-encryption" placeholder="Encryption data">');
                encryptTbl.push('<span class="input-group-append">');
                    encryptTbl.push('<button type="button" class="btn btn-light btn-icon" onclick="configValueEncryptionShowText(this);"><i class="far fa-eye"></i></button>');
                encryptTbl.push('</span>');
            encryptTbl.push('</div>');
        encryptTbl.push('</div>');
    encryptTbl.push('</div>');
    encryptTbl.push('<div class="form-group row">');
        encryptTbl.push('<div class="col-lg-12">');
            encryptTbl.push('<button type="button" onclick="configValueEncryption(this);" class="btn btn-primary rounded-round">Encrypt</button>');
        encryptTbl.push('</div>');
    encryptTbl.push('</div>');
    encryptTbl.push('<div class="form-group row">');
        encryptTbl.push('<div class="col-lg-12">');
        encryptTbl.push('<input type="text" class="form-control config-value-encryption-print" readonly="readonly" style="font-size: 15px;font-weight: 700;">');
        encryptTbl.push('</div>');
    encryptTbl.push('</div>');

    $dialogEncrypt.empty().append(encryptTbl.join(''));  
    $dialogEncrypt.dialog({
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Get encrypted data',
        width: 550,
        height: 'auto',
        modal: false,
        close: function () {
            $dialogEncrypt.empty().dialog('destroy').remove();
        },
        buttons: [ 
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialogEncrypt.dialog('close');
            }}
        ]
    });
    $dialogEncrypt.dialog('open');
}
function addConfigValue(elem){
    var $dialogName = 'dialog-addconfigvalue';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdconfig/addConfigValue',
        data: {params: '<?php echo $this->params; ?>'},
        dataType: "json",
        beforeSend: function(){
            Core.blockUI({animate: true});
        },
        success: function(data){
            $("#" + $dialogName).empty().append(data.Html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 850,
                minWidth: 850,
                height: "auto",
                modal: true,
                close: function(){
                    $("#"+$dialogName).empty().dialog('destroy').remove();
                    $('#dialog-config-encrypted').empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: 'Get encrypted data', class: 'btn btn-sm purple-plum float-left', click: function () {
                        dialogEncryptionForm();
                    }},
                    {text: data.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                        $("#addConfigValue-form").validate({errorPlacement: function(){}});
                        if ($("#addConfigValue-form").valid()) {
                            $("#addConfigValue-form").ajaxSubmit({
                                type: 'post',
                                url: 'mdconfig/createConfigValue',
                                dataType: 'json',
                                beforeSend: function(){
                                    Core.blockUI({message: 'Хадгалж байна...', boxed: true});
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
                                        $("#"+$dialogName).dialog('close');
                                        configValueDataGrid.datagrid('reload');
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                        $("#"+$dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function(){
            alert("Error");
        }
    }).done(function(){
        Core.initAjax($("#" + $dialogName));
    });
}
function configValueEncryptionShowText(elem) {
    var $this = $(elem), $icon = $this.find('i');

    if ($icon.hasClass('fa-eye')) {
        $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        $('.config-value-encryption').attr('type', 'text');
    } else {
        $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        $('.config-value-encryption').attr('type', 'password');
    }
}
function configValueEncryption(elem) {
    var $valueEncryption = $('.config-value-encryption');
    var valueEncryption = ($valueEncryption.val()).trim();
    
    $valueEncryption.removeClass('error');
    
    if (valueEncryption != '') {
        
        $.ajax({
            type: 'post',
            url: 'mdconfig/valueEncryption',
            data: {valueEncryption: valueEncryption},
            success: function(data) {
                $('.config-value-encryption-print').val(data);
            },
            error: function() { alert('Error'); }
        });
            
    } else {
        $valueEncryption.addClass('error');
    }
}
function editConfigValue(elem){
    var $dialogName = 'dialog-addconfigvalue-edit';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var row = configValueDataGrid.datagrid('getSelected');
    if (row) {
        if (row.ID) {
            $.ajax({
                type: 'post',
                url: 'mdconfig/editConfigValue',
                data: {id: row.ID}, 
                dataType: 'json',
                beforeSend:function(){
                    Core.blockUI({
                        animate: true
                    });
                },
                success:function(data){
                    $("#"+$dialogName).empty().html(data.Html);  
                    $("#"+$dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: 850,
                        minWidth: 850,
                        height: "auto",
                        modal: true, 
                        close:function(){
                            $("#"+$dialogName).empty().dialog('destroy').remove();
                            $('#dialog-config-encrypted').empty().dialog('destroy').remove();
                        }, 
                        buttons: [
                            {text: 'Get encrypted data', class: 'btn btn-sm purple-plum float-left', click: function () {
                                dialogEncryptionForm();
                            }},
                            {   
                                text: data.save_btn,
                                class: 'btn btn-sm green-meadow', 
                                click: function(){
                                    $("#editConfigValue-form").validate({ errorPlacement: function(){} });
                                    if ($("#editConfigValue-form").valid()) {
                                        $("#editConfigValue-form").ajaxSubmit({
                                            type: 'post',
                                            url: 'mdconfig/updateConfigValue',
                                            dataType: "json",
                                            beforeSend: function(){
                                                Core.blockUI({
                                                    message: 'Хадгалж байна...',
                                                    boxed: true
                                                });
                                            },
                                            success: function(data) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false
                                                });
                                                if (data.status === 'success') {
                                                    configValueDataGrid.datagrid('reload');
                                                    $("#"+$dialogName).dialog('close');
                                                }
                                                Core.unblockUI();
                                            },
                                            error: function(){
                                                alert("Error");
                                            }
                                        });
                                    }
                                }
                            },
                            {
                                text: data.close_btn, 
                                class: 'btn btn-sm blue-hoki',
                                click: function(){
                                    $("#"+$dialogName).dialog('close');
                                }
                            }
                        ]        
                    });
                    $("#"+$dialogName).dialog('open');
                    Core.unblockUI();
                },
                error:function(){
                    alert("Error");
                }
            }).done(function(){
                Core.initAjax($("#" + $dialogName));
            });
        } else {
            alert("Та жагсаалтаас сонгоно уу!");
        }
    } else {
        alert("Та жагсаалтаас сонгоно уу!");
    }
}

function deleteConfigValue(elem){
    var row = configValueDataGrid.datagrid('getSelected');
    if (row) {
        var $dialogName = 'dialog-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdcommon/deleteConfirm',
            dataType: "json",
            beforeSend: function(){
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data){
                $("#" + $dialogName).empty().append(data.Html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 330,
                    height: "auto",
                    modal: true,
                    close: function(){
                        $("#"+$dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function(){
                            $.ajax({
                                type: 'post',
                                url: 'mdconfig/deleteConfigValue',
                                data: {id: row.ID},
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (dataSub) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: dataSub.status,
                                        text: dataSub.message,
                                        type: dataSub.status,
                                        sticker: false
                                    });
                                    if (dataSub.status === 'success') {
                                        configValueDataGrid.datagrid('reload');
                                    } 
                                    $("#"+$dialogName).dialog('close');
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        }},
                        {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function(){
                            $("#"+$dialogName).dialog('close');
                        }}
                    ]
                });
                $("#"+$dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax($("#"+$dialogName));
        });
    } else {
        alert("Та жагсаалтаас сонгоно уу!");
    }
}
</script>