var MESSSAGE_WARNING_TITLE = 'Анхааруулга';
var MESSSAGE_SESSION_FULL = 'Өөрчлөлт хийх хязгаар хэтэрсэн байна';
var MESSSAGE_STATUS_ERROR = 'Удирдлага баталгаажуулсан байна. Өөрчлөх шаардлагатай бол удирдлагаар цуцлуулна уу';
var MESSSAGE_VERIFY_NO_CELL = 'Батагаажуулах нүд сонгоогүй байна';
var MESSSAGE_IS_LOCK = 'Түгжсэн нүдэнд өөрчлөлт орох боломжгүй. Түгжсэн ажилтнаар түгжээг гаргах';
var MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW = 'Мөр сонгоогүй байна';

var tnaTimeBalanceData = [],
    selectedDataRow = [],
    selectedDataRowIndex = '';

var TEMPED_ROWDATA = [],
    TEMPED_SELECTED_ROW = [],
    TEMPED_FILLINFORDATA = [],
    SELECTED_EMPLOYEE_DESCRIPTION = [];

$(function () {
    
    $('body').on("click", ".confirmTimeBalance", function () {
        var _this = $(this);
        var datauniqId = _this.attr('data-uniqid');
        var wfmStatusId = _this.attr('data-status-id');
        var wfmStatusCode = _this.attr('data-status-code');
        var selectedDdv = _selectedDdv = '';
        var rows = [];
        var url = 'mdtime/multiChangeBalanceC';
        var confirmData = '0';
        var ticket = true;
        if (typeof _this.attr('data-all') !== 'undefined' && _this.attr('data-all') === 'department') {
            
            new PNotify({
                title: 'Анхааруулга',
                text: 'Батлах боломжгүй',
                type: 'warning',
                sticker: false
            });
            ticket = false;
            _selectedDdv = $(_this.attr('data-view-id'));
            rows = _selectedDdv.datagrid('getSelections');
            confirmData = '1';
            url = 'mdtime/multiChangeBalanceDeparment';
            return;
        }
        
        if (typeof _this.attr('data-all') !== 'undefined' && _this.attr('data-all') === 'month') {
            _selectedDdv = $(_this.attr('data-view-id'));
            rows = _selectedDdv.datagrid('getSelections');
            confirmData = '1';
            
            url = 'mdtime/multiChangeBalanceMonth';
        } else {
            selectedDdv = $("#tnaTimeBalanceWindow" + datauniqId).find('input[id="selected-datagrid-'+ datauniqId +'"]').val();
            _selectedDdv = selectedDdv.split(' ');

            if (selectedDdv !== '0' && _selectedDdv.length > 0) {
                var _selectedDdv = $('.' + _selectedDdv[0]);
                rows = _selectedDdv.datagrid('getSelections');
            }
        }
        
        var isWorkFlowStatus = false;
        for (var i=0; i < rows.length; i++) {
            if (isOneWorkFlowStatus(rows[i], wfmStatusCode)) {
                isWorkFlowStatus = true;
                break;
            }
        }

        if (isWorkFlowStatus) {
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_STATUS_ERROR,
                type: 'warning',
                sticker: false
            });
        } 
        else {
            if (userSessionIsFull()) {
                PNotify.removeAll(); 
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_SESSION_FULL,
                    type: 'warning',
                    sticker: false
                });
            } 
            else {
                if (rows.length > 0) {
                    var dialogName = 'dialog-timeBalanceMultiConfirm';
                    if (!$('#' + dialogName).length) {
                        $('<div id="' + dialogName + '"></div>').appendTo('body');
                    }
                    $('#' + dialogName).empty().html('Сонгосон мөрийг баталгаажуулахдаа итгэлтэй байна уу');
                    $('#' + dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Сануулга',
                        width: '300',
                        height: 'auto',
                        modal: true,
                        close: function () {
                            $('#' + dialogName).dialog('close');
                        },
                        buttons: [
                            {text: plang.get('yes_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    confirmMultiTimeBalance(wfmStatusId, datauniqId, _selectedDdv, url, confirmData);
                                    $('#' + dialogName).dialog('close');
                                }
                            },
                            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $('#' + dialogName).dialog('close');
                                }
                            }
                        ]
                    });
                    $('#' + dialogName).dialog('open');
                } else {
                    var dialogName = 'dialog-timeBalanceMultiChanage';
                    if (!$('#' + dialogName).length) {
                        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                    }
                    $('#' + dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                    $('#' + dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Сануулга',
                        width: '300',
                        height: 'auto',
                        modal: true,
                        close: function () {
                            $('#' + dialogName).dialog('close');
                        },
                        buttons: [
                            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $('#' + dialogName).dialog('close');
                                }
                            }
                        ]
                    });
                    $('#' + dialogName).dialog('open');
                }
            }
        }
        
    });
    
    $('body').on("click", ".timeCancelBtn", function () {
        var _this = $(this);
        var selectedBalance = [];
        var datauniqId = _this.attr('data-uniqid');
        var wfmStatusId = _this.attr('data-status-id');
        var wfmStatusCode = _this.attr('data-status-code');
        
        var selectedDdv = $("#tnaTimeBalanceWindow" + datauniqId).find('input[id="selected-datagrid-'+ datauniqId +'"]').val();
        var _selectedDdv = selectedDdv.split(' ');
        
        if (selectedDdv !== '0' && _selectedDdv.length > 0) {
            var _selectedDdv = $('.' + _selectedDdv[0]);
            var rows = _selectedDdv.datagrid('getSelections');

            var isWorkFlowStatus = false;

            for(var i=0; i<rows.length; i++) {
                if (isOneWorkFlowStatus(rows[i], wfmStatusCode)) {
                    isWorkFlowStatus = true;
                    break;
                }
            }

            if (isWorkFlowStatus) {
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_STATUS_ERROR,
                    type: 'warning',
                    sticker: false
                });
            } else {
                if (userSessionIsFull()) {
                    PNotify.removeAll(); 
                    new PNotify({
                        title: MESSSAGE_WARNING_TITLE,
                        text: MESSSAGE_SESSION_FULL,
                        type: 'warning',
                        sticker: false
                    });
                } else {
                    if (rows.length > 0) {
                        var dialogName = '#dialog-employee-cancel-status';
                        if (!$(dialogName).length) {
                            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                        }

                        $(dialogName).empty().html("Статусыг цуцлахдаа итгэлтэй байна уу");
                        $(dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Сануулга',
                            width: 'auto',
                            height: 'auto',
                            modal: true,
                            close: function () {
                                $(dialogName).dialog('close');
                            },
                            buttons: [
                                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                    cancelMultiTimeBalance(wfmStatusId, datauniqId, _selectedDdv);
                                    $(dialogName).dialog('close');
                                }},
                                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $(dialogName).dialog('close');
                                }}
                            ]
                        });
                        $(dialogName).dialog('open');
                    } else {
                        var dialogName = '#dialog-timeBalanceMultiChanage';
                        if (!$(dialogName).length) {
                            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                        }
                        $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                        $(dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Сануулга',
                            width: '300',
                            height: 'auto',
                            modal: true,
                            close: function () {
                                $(dialogName).dialog('close');
                            },
                            buttons: [
                                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $(dialogName).dialog('close');
                                    }
                                }
                            ]
                        });
                        $(dialogName).dialog('open');
                    }
                }
            }
        }
        
    });
   
    $("body").on("click", '#employeeTimeBalance  .employeeFillInForBtn', function () {

        var dialogname = $('#dialog-fillInFor-employee');
        var $dialogname = 'dialog-fillInFor-employee';
        $.ajax({
            type: 'post',
            url: 'Mdtime/employeeFillInFor',
            data: {params: TEMPED_FILLINFORDATA},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $.unblockUI();
                if (data.status !== 'success') {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    return false;
                }

                dialogname.empty().html(data.html);
                dialogname.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: TEMPED_ROWDATA.BALANCE_DTL_NAME + ': ' + TEMPED_ROWDATA.EMPLOYEE_NAME,
                    width: 'auto',
                    height: 'auto',
                    modal: true,
                    open: function () {
                        $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs green');
                    },
                    close: function () {
                        dialogname.empty().dialog('close');
                    },
                    buttons: [
                        {text: plang.get('no_btn'), click: function () {
                                dialogname.dialog('close');
                            }
                        }
                    ]
                });
                dialogname.dialog('open');
            },
            error: function () {
                $.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: 'error',
                    type: 'error',
                    sticker: false
                });
            }
        });
    });
    
    $(document).on("click", "#tnaTimeBalanceForm  .employeeBalanceDescription", function () {
        var _this = $(this);
        var cell = _this.closest('td');
        var description = cell.find('input[data-description="description"]');
        var $dialogName = 'dialogCauseDescription';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var descHtml = '<textarea name="causeDescription" class="form-control">' + description.val() + '</textarea>';
        $("#" + $dialogName).empty().html(descHtml);
        $("#" + $dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Тайлбар орууна уу!!!',
            width: 400,
            height: 'auto',
            modal: true,
            close: function () {
                $("#" + $dialogName).empty().dialog('destroy');
            },
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                    description.val($('textarea[name="causeDescription"]').val());
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');
    });
    
    $("body").on("click", ".employeeBalanceDescriptionTime", function () {
        var _this = $(this);
        var cell = _this.closest('td');
        var $dialogName = 'dialogCauseDescriptionTime';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var descHtml = '<textarea name="causeDescription" class="form-control">' + cell.find("input").val() + '</textarea>';
        $("#" + $dialogName).empty().html(descHtml);
        $("#" + $dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Тайлбар оруулна уу!!!',
            width: 400,
            height: 'auto',
            modal: true,
            close: function () {
                $("#" + $dialogName).empty().dialog('destroy');
            },
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                    cell.find("input").val($('textarea[name="causeDescription"]').val());
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');
    });

    $("body").on("click", "#tnaTimeBalanceForm .employeeBalanceDetail", function () {
        var _this = $(this);
        var _sideBarContent = _this.closest(".selectedRowDetail");
        var dialogName = '#employeeBalanceDetailDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdtime/getEmployeeTimeAttendance',
            data: {"employeeKeyId": _sideBarContent.find('input[data-name="employeeKeyId"]').val(), "balanceDate": _sideBarContent.find('input[data-name="balanceDate"]').val()},
            dataType: "json",
            success: function(data) {
                if (data.status === 'success') {
                    $(dialogName).html(data.Html);
                    $(dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: '500',
                        height: 'auto',
                        modal: true,
                        close: function () {
                            $(dialogName).empty().dialog('destroy').remove();  
                        },
                        buttons: [
                            {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function() {
                                $(dialogName).empty().dialog('destroy').remove();
                            }}
                        ]
                    });
                    $(dialogName).dialog('open');
                } else {
                    $.unblockUI();
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                }
            },
            error: function() {
                alert("Error");
            }
        }).done(function(){
            
        });
    });

    $("body").on("click", "#tnaTimeBalanceForm  .employeeBalanceExportListExcel", function () {
        $("#tnaTimeBalanceForm").validate({
            errorPlacement: function () {
            }
        });
        if ($("#tnaTimeBalanceForm").valid()) {
            Core.blockUI({
                animate: true
            });
            $.download('mdtime/exportBalanceListMainDataGrid', 'form', $("#tnaTimeBalanceForm").serialize(), 'post', '');
            Core.unblockUI();
        }
        else {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Алба хэлтэс заавал бөглөнө үү?',
                type: 'warning',
                sticker: false
            });
            $('html, body').animate({
                scrollTop: 0
            }, 0);
        }
    });
    
    $("body").on("click", "#employeeTimeBalance .employeeBalanceDetailDescription", function () {
        var dialogname = $('#dialog-fillInFor-employee');
        var $dialogname = 'dialog-fillInFor-employee';

        var i = 1;
        var table = '';

        if (SELECTED_EMPLOYEE_DESCRIPTION.length !== 0) {
            table = '<div class="col-md-12">'
                    + '<table class="table table-hover">'
                    + '<thead>'
                    + '<tr>'
                    + '<th>№</th>'
                    + '<th>CAUSE</th>'
                    + '<th>VALUE</th>'
                    + '</tr>'
                    + '</thead>';
            table += '<tbody>';

            $.each(SELECTED_EMPLOYEE_DESCRIPTION, function (key, row) {
                table += '<tr>'
                        + '<td>' + i + '</td>'
                        + '<td>' + row.CAUSE_PARAM + '</td>'
                        + '<td>' + row.CAUSE_PARAM_VALUE + '</td>'
                        + '</tr>';
                i++;
            });

            table += '</tbody>';

            table += '</table>'
                    + '</div>';
        }

        dialogname.empty().html(table);
        dialogname.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Дэлгэрэнгүй CAUSE_TYPE: ' + TEMPED_ROWDATA.EMPLOYEE_NAME + ' ( ' + TEMPED_ROWDATA.BALANCE_DATE + ') ',
            width: 'auto',
            height: 'auto',
            modal: true,
            open: function () {
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs green');
            },
            close: function () {
                dialogname.empty().dialog('close');
            },
            buttons: [
                {text: plang.get('no_btn'), click: function () {
                        dialogname.dialog('close');
                    }
                }
            ]
        });
        dialogname.dialog('open');
    });

    $("body").on("click", "#employeeTimeBalance  .employeeSaveBtn", function () {
        var _this = $(this);
        calculateTotal(this);
        var _sideBarContent = _this.closest(".selectedRowDetail");
        var dialogName = '#dialog-fillInFor-employee';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        if (parseFloat(_sideBarContent.find('input[data-name="defferenceTime"]').val()) < 0) {
            var text = _sideBarContent.find('input[data-name="employeeName"]').val() + ' (' + _sideBarContent.find('input[data-name="balanceDate"]').val() + ') өдөр зөрчилтэй цаг: ' + _sideBarContent.find('input[data-name="defferenceTime"]').val() + ' байна. Анхаарна уу!';
            new PNotify({
                title: 'Warning',
                text: text,
                type: 'warning',
                sticker: false
            });
            return false;
        }


        var html = '<label style="margin:10px; font-size: 12px !important">' + _sideBarContent.find('input[data-name="employeeName"]').val() + ' (' + _sideBarContent.find('input[data-name="balanceDate"]').val() + ') өдрийн мэдээллийг хадгалалахдаа итгэлтэй байна уу?' + '</label>';

        $(dialogName).empty().html(html);
        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Сануулга',
            width: 'auto',
            height: 'auto',
            modal: true,
            close: function () {
                $(dialogName).dialog('close');
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        var ticket = true;
                        if (selectedDataRow.WFM_STATUS_ID == '1450760377812532') {
                            var balanceDtl = TEMPED_SELECTED_ROW.BALANCE_DTL;
                            /*
                            $.each(balanceDtl, function (key, row) {
                                var rowVtime = parseFloat(row.V_TIME);
                                if (rowVtime != 0) {
                                    if (typeof row.DESCRIPTION == 'undefined' || row.DESCRIPTION == undefined) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Warning',
                                            text: row['NAME'] + ' төрлийн тайлбараа бичнэ үү?',
                                            type: 'warning',
                                            sticker: false
                                        });
                                        ticket = false;
                                    }
                                    else {
                                        if ((row.DESCRIPTION).length == 0 || (row.DESCRIPTION).length == 1) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: 'Warning',
                                                text: row['NAME'] + ' төрлийн тайлбараа бичнэ үү?',
                                                type: 'warning',
                                                sticker: false
                                            });
                                            ticket = false;
                                        }
                                    }
                                }
                            });
                            */
                        }

                        if (ticket) {
                            $.ajax({
                                type: 'post',
                                url: 'Mdtime/getEmployeeSaveData',
                                data: _sideBarContent.find('input').serialize() + '&timeBalanceHdrId='+selectedDataRow.TIME_BALANCE_HDR_ID,
                                async: false,
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    Core.unblockUI();
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                    if (data.status === 'success') {
                                        $.ajax({
                                            type: 'post',
                                            url: 'Mdtime/getBalanceMainDataGrid',
                                            data: { params: $("#tnaTimeBalanceForm").serialize() + '&timeBalanceHdrId='+selectedDataRow.TIME_BALANCE_HDR_ID },
                                            dataType: "json",
                                            success: function (data) {
                                                if(Object.keys(data).length > 0) {
                                                    $('table.datagrid-btable > tbody > tr[datagrid-row-index="'+ _sideBarContent.find('input[name="activetrIndex"]').val() +'"] > td[field="DEFFERENCE_TIME"] > div', "#employeeTimeBalance").text(data.DEFFERENCE_TIME);
                                                    $('table.datagrid-btable > tbody > tr[datagrid-row-index="'+ _sideBarContent.find('input[name="activetrIndex"]').val() +'"] > td[field="NIGHT_TIME"] > div', "#employeeTimeBalance").text(data.NIGHT_TIME);
                                                }
                                            }    
                                        });                           
                                    }
                                    $(dialogName).dialog('close');
                                },
                                error: function () {
                                    Core.unblockUI();
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Error',
                                        text: 'error',
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                            });
                        }
                    }
                },
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }
                }
            ]
        });
        $(dialogName).dialog('open');
    });
    
    $("body").on("click", "#tnaTimeBalanceForm .employeeConfirmBtn", function () {
        var _this = $(this);
        var _sideBarContent = _this.closest(".selectedRowDetail");
        var datauniqId =_this.attr('data-uniqid');
        
        var wfmStatusId = _this.attr('data-status-id');
        var wfmStatusCode = _this.attr('data-status-code');
        if (isOneWorkFlowStatus(selectedDataRow, wfmStatusCode)) {
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_STATUS_ERROR,
                type: 'warning',
                sticker: false
            });
        } else {
            if (userSessionIsFull()) {
                PNotify.removeAll(); 
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_SESSION_FULL,
                    type: 'warning',
                    sticker: false
                });
            } else {
                var $ticket = false;
                calculateTotal(this);
                
                if ($ticket) {
                    var dialogName = '#dialog-fillInFor-employee';
                    if (!$(dialogName).length) {
                        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                    }

                    var html = '<label style="margin:10px; font-size: 12px !important; line-height: 18px;"><strong>' + _sideBarContent.find('input[data-name="employeeName"]').val() + '</strong> (' + _sideBarContent.find('input[data-name="balanceDate"]').val() + ') өдрийн мэдээллийг баталгаажуулахдаа итгэлтэй байна уу?' + '</label>';

                    $(dialogName).empty().html(html);
                    $(dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Сануулга',
                        width: '400',
                        height: 'auto',
                        modal: true,
                        close: function () {
                            $(dialogName).dialog('close');
                        },
                        buttons: [
                            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                var timeBalanceHdrId = selectedDataRow.TIME_BALANCE_ID;
                                if (typeof stimeBalanceHdrId === 'undefined') {
                                    timeBalanceHdrId = selectedDataRow.TIME_BALANCE_HDR_ID;
                                }

                                $.ajax({
                                    type: 'post',
                                    url: 'mdtime/getEmployeeConfirmData',
                                    data: _sideBarContent.find('input').serialize() + '&timeBalanceHdrId=' + timeBalanceHdrId + '&wfmStatusId='+wfmStatusId + '&wfmStatusCode=' + wfmStatusCode,
                                    dataType: "json",
                                    beforeSend: function () {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function (data) {
                                        Core.unblockUI();
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        $('.' + $('.' + datauniqId).find('.datagrid-last-clicked-row').closest('.' + datauniqId).attr('data-children-class')).datagrid('reload');
                                        Core.initAjax();
                                        $(dialogName).dialog('close');
                                    },
                                    error: function () {
                                        Core.unblockUI();
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Error',
                                            text: 'error',
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                });
                            }},
                            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                $(dialogName).dialog('close');
                            }}
                        ]
                    });
                    $(dialogName).dialog('open');         
                } else {
                    _sideBarContent.find('#14').removeClass('btn-danger');
                    var _thisSideBarCauseType = _sideBarContent.find('.causeClassName_14').find('input[data-name="cause_type_value"]').val();
                    var _thisSideBarCauseTypeDescription = _sideBarContent.find('.causeClassName_14').find('.causeDescriptionClassName_14').val();
                    if (parseFloat(_thisSideBarCauseType) > 0) {
                        if (_thisSideBarCauseTypeDescription.length == 0) {
                            _sideBarContent.find('#14').addClass('btn-danger');
                            PNotify.removeAll(); 
                            new PNotify({
                                title: 'Warning',
                                text: 'Баталсан илүү цаг оруулсан тул тайлбарыг бөглөнө үү?',
                                type: 'warning',
                                sticker: false
                            });
                            return false;
                        }
                    }

                    if (parseFloat(_sideBarContent.find('input[data-name="defferenceTime"]').val()) != 0) {

                        var text = _sideBarContent.find('input[data-name="employeeName"]').val() + ' (' + _sideBarContent.find('input[data-name="balanceDate"]').val() + ') өдөр зөрчилтэй цаг: ' + minutToTimeBalance(_sideBarContent.find('input[data-name="defferenceTime"]').val()) + ' байна. Анхаарна уу!';
                        new PNotify({
                            title: MESSSAGE_WARNING_TITLE,
                            text: text,
                            type: 'warning',
                            sticker: false
                        });
                        return false;

                    } else {
                        var tickeet = false;
                        $('input[data-name="cause_type_value"]', _sideBarContent).each(function (sin, srow) {

                            var _causeTypeName = $('td[class="left-padding"]', _sideBarContent)[sin];
                            var _desciption = $('input[data-description="description"]', _sideBarContent)[sin];
                        });

                        if (tickeet) {
                            return false;
                        }

                        if (parseFloat(_sideBarContent.find('input[data-name="defferenceTime"]').val()) === 0) {

                            var dialogName = '#dialog-fillInFor-employee';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }

                            var html = '<label style="margin:10px; font-size: 12px !important; line-height: 18px;"><strong>' + _sideBarContent.find('input[data-name="employeeName"]').val() + '</strong> (' + _sideBarContent.find('input[data-name="balanceDate"]').val() + ') өдрийн мэдээллийг баталгаажуулахдаа итгэлтэй байна уу?' + '</label>';

                            $(dialogName).empty().html(html);
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулга',
                                width: '400',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $(dialogName).dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                        var timeBalanceHdrId = selectedDataRow.TIME_BALANCE_ID;
                                        if (typeof stimeBalanceHdrId === 'undefined') {
                                            timeBalanceHdrId = selectedDataRow.TIME_BALANCE_HDR_ID;
                                        }

                                        $.ajax({
                                            type: 'post',
                                            url: 'mdtime/getEmployeeConfirmData',
                                            data: _sideBarContent.find('input').serialize() + '&timeBalanceHdrId=' + timeBalanceHdrId + '&wfmStatusId='+wfmStatusId + '&wfmStatusCode=' + wfmStatusCode,
                                            dataType: "json",
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    animate: true
                                                });
                                            },
                                            success: function (data) {
                                                var updateRow = data.result;
                                                Core.unblockUI();
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false
                                                });
                                                $('.' + $('.' + datauniqId).find('.datagrid-last-clicked-row').closest('.' + datauniqId).attr('data-children-class')).datagrid('reload');
                                                Core.initAjax();
                                                $(dialogName).dialog('close');
                                            },
                                            error: function () {
                                                Core.unblockUI();
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: 'Error',
                                                    text: 'error',
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                        });
                                    }},
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $(dialogName).dialog('close');
                                    }}
                                ]
                            });
                            $(dialogName).dialog('open');                    
                        } else {
                            new PNotify({
                                title: MESSSAGE_WARNING_TITLE,
                                text: 'Цагийн мэдээлэл алдаатай байна',
                                type: 'warning',
                                sticker: false
                            });
                        }
                    }
                }
            }
        }
    });
    
    $("body").on("click", "#tnaTimeBalanceForm .employeeCancelBtn", function () {
        var _this = $(this);
        var datauniqId = _this.attr('data-uniqid');
        var wfmStatusId = _this.attr('data-status-id');
        var wfmStatusCode = _this.attr('data-status-code');
        if (isOneWorkFlowStatus(selectedDataRow, wfmStatusCode)) {
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_STATUS_ERROR,
                type: 'warning',
                sticker: false
            });
        } else {
            if (userSessionIsFull()) {
                PNotify.removeAll(); 
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_SESSION_FULL,
                    type: 'warning',
                    sticker: false
                });
            } else {
                
                var dialogName = '#dialog-employee-cancel-status';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }

                $(dialogName).empty().html("Статусыг цуцлахдаа итгэлтэй байна уу");
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Сануулга',
                    width: 'auto',
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $(dialogName).dialog('close');
                    },
                    buttons: [
                        {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            var timeBalanceHdrId = selectedDataRow.TIME_BALANCE_ID;
                            if (typeof stimeBalanceHdrId === 'undefined') {
                                timeBalanceHdrId = selectedDataRow.TIME_BALANCE_HDR_ID;
                            }

                            $.ajax({
                                type: 'post',
                                url: 'mdtime/getEmployeeCancelStatus',
                                data: 'timeBalanceHdrId=' + timeBalanceHdrId + '&wfmStatusId=' + wfmStatusId,
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    Core.unblockUI();
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                    $('.' + $('.' + datauniqId).find('.datagrid-last-clicked-row').closest('.' + datauniqId).attr('data-children-class')).datagrid('reload');
                                    Core.initAjax();
                                    $(dialogName).dialog('close');
                                },
                                error: function () {
                                    Core.unblockUI();
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Error',
                                        text: 'error',
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                            });

                        }},
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                });
                $(dialogName).dialog('open');
                
            }
            
        }
        
    });
    
});

function callIsLockBalanceDialog(selectedRows, selectedDdv, $uniqId) {
    var $dialogName = 'dialog-isLock';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtime/isLockPlan',
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
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 400,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                            if ($("input[name='isLock']:checked", "#" + $dialogName).val() == '1') {
                                $("#isLockForm").validate({
                                    errorPlacement: function () {}
                                });
                                if ($("#isLockForm").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdtime/isLockBalanceQuery',
                                        dataType: "json",
                                        data: {"data": selectedRows, "lockEndDate": $("#lockEndDate", "#" + $dialogName).val(), "isLock": $("input[name='isLock']:checked", "#" + $dialogName).val()},
                                        beforeSend: function () {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function (data) {
                                            new PNotify({
                                                title: 'Амжилттай',
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                            
                                            if (typeof selectedDdv != 'undefined' && typeof $uniqId != 'undefined') {
                                                selectedDdv.datagrid('reload');

                                                $(".right-sidebar-content-"+ $uniqId).empty();
                                                $(".right-sidebar-content-"+ $uniqId).empty().hide();
                                                $(".right-sidebar-"+ $uniqId).attr("data-status", "close");
                                                $(".right-sidebar-"+ $uniqId).find(".sidebar-right").removeClass("sidebar-opened");
                                                $(".right-sidebar-"+ $uniqId).removeClass("col-md-3");
                                                $(".center-sidebar-"+ $uniqId).removeClass("col-md-9");
                                                $(".center-sidebar-"+ $uniqId).addClass("col-md-12");
                                            } else {
                                                $('body #tna-balance-data-grid').datagrid('reload');
                                            }
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                }
                            } else {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdtime/isLockBalanceQuery',
                                    dataType: "json",
                                    data: {"data": selectedRows, "lockEndDate": '', "isLock": $("input[name='isLock']:checked", "#" + $dialogName).val()},
                                    beforeSend: function () {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function (data) {
                                        new PNotify({
                                            title: 'Амжилттай',
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        $('body #tna-balance-data-grid').datagrid('reload');
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                            }
                            $("#" + $dialogName).dialog('close');
                        }},
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
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

function getBalanceList($uniqId) {
    $("body #tnaTimeBalanceForm" + $uniqId).validate({errorPlacement: function () {}});
    if ($("body #tnaTimeBalanceForm" + $uniqId).valid()) {
        $('.mergeCelltnaEmployeeBalance').hide();
        $('.tna-balance-data-grid-div-' + $uniqId).html('<table id="tna-balance-data-grid-'+ $uniqId +'" style="width: 100%;"></table>');
        var balanceType = $('#balanceType-'+$uniqId).val();
        switch (balanceType) {
            case 'golomtNew': 
                renderGroupDeparmentBalanceList($uniqId, false);    
                break;
            case 'golomtOld':
                renderGolomtGroupMonthBalanceList($uniqId, false);    
                break;
            case 'new':
                renderGroupMonthBalanceList($uniqId, true);    
                break;
            case 'mod':
                renderModBalanceList($uniqId, true);    
                break;
            case 'old':
                renderBalanceList($uniqId, true);       
                break;
            default: {
                renderBalanceList($uniqId, true);    
                break;
            }
        }
    } else {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Алба хэлтэс заавал бөглөнө үү?',
            type: 'warning',
            sticker: false
        });
        $('html, body').animate({
            scrollTop: 0
        }, 0);
    }
}

function multiChangeBalance(selectedBalance, selectedDdv, $uniqId) {
    var dialogName = '#dialog-timeBalanceMultiChanage';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    
    var wfmStatusId = 0;
    $.ajax({
        type: 'post',
        url: 'mdtime/getOneWfmStatus',
        dataType: "json",
        data: {"wfmStatusCode": "tnaNewBalance"},
        async: false,
        success: function (data) {
            wfmStatusId = data.WFM_STATUS_ID;
        }
    });
    $.ajax({
        type: 'post',
        url: 'mdtime/multiChangeBalance',
        data: {balanceHdr: selectedBalance},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $(dialogName).empty().html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: '430',
                height: 'auto',
                modal: true,
                close: function () {
                    $(dialogName).dialog('close');
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                            // if (typeof isDescriptionReq !== 'undefined' && isDescriptionReq == true && golomtView == '0' && $("input[name='inTime']:not('.readonly-inTime')", dialogName).length > 0 && $("input[name='inTime']:not('.readonly-inTime')", dialogName).val().length > 0 && $("input[name='descriptionIn']", dialogName).val().length === 0) {
                            //     PNotify.removeAll();
                            //     new PNotify({
                            //         title: 'Warning',
                            //         text: 'Тайлбараа оруулна уу!',
                            //         type: 'warning',
                            //         sticker: false
                            //     });                              
                            //     $("input[name='descriptionIn']", dialogName).parent().find('button').addClass('red');
                            //     return false;
                            // } else {
                            // }
                            $("input[name='descriptionIn']", dialogName).parent().find('button').removeClass('red');                                
                            // if(typeof isDescriptionReq !== 'undefined' &&  isDescriptionReq == true && golomtView == '0' && $("input[name='outTime']", dialogName).val().length > 0 && $("input[name='descriptionOut']", dialogName).val().length === 0) {
                            //     PNotify.removeAll();
                            //     new PNotify({
                            //         title: 'Warning',
                            //         text: 'Тайлбараа оруулна уу!',
                            //         type: 'warning',
                            //         sticker: false
                            //     });                              
                            //     $("input[name='descriptionOut']", dialogName).parent().find('button').addClass('red');
                            //     return false;
                            // } else {
                            // }
                            $("input[name='descriptionOut']", dialogName).parent().find('button').removeClass('red');
                            
                            var postData = {balanceHdr: selectedBalance, balanceDtl: $('#multiChangeBalanceForm', dialogName).serialize(), wfmStatusId: wfmStatusId};
                            
                            if(typeof isMod !== "undefined") {
                                $.extend(postData, {isModWindow: true});
                            }
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdtime/multiChangeBalanceQuery',
                                dataType: "json",
                                data: postData,
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    selectedDdv.datagrid('reload');
                                    
                                    $(".right-sidebar-content-"+ $uniqId).empty();
                                    $(".right-sidebar-content-"+ $uniqId).empty().hide();
                                    $(".right-sidebar-"+ $uniqId).attr("data-status", "close");
                                    $(".right-sidebar-"+ $uniqId).find(".sidebar-right").removeClass("sidebar-opened");
                                    $(".right-sidebar-"+ $uniqId).removeClass("col-md-3");
                                    $(".center-sidebar-"+ $uniqId).removeClass("col-md-9");
                                    $(".center-sidebar-"+ $uniqId).addClass("col-md-12");
                                    new PNotify({
                                        title: 'Success',
                                        text: data.message,
                                        type: 'success'
                                    });
                                    Core.unblockUI();
                                    $(dialogName).dialog('close');
                                },
                                error: function () {
                                    Core.unblockUI();
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Error',
                                        text: 'error',
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                            });
                        }
                    },
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }
                    }
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            Core.unblockUI();
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: 'error',
                type: 'error',
                sticker: false
            });
        }
    }).done(function(){
        Core.initInputType($(dialogName));
    });
}

function multiSendBalance(selectedBalance, selectedDdv, $uniqId) {
    var dialogName = '#dialog-timeBalanceMultiSend';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    
    $(dialogName).empty().html('Илгээхдээ итгэлтэй байна уу?');
    $(dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Анхааруулга',
        height: 'auto',
        modal: true,
        close: function () {
            $(dialogName).empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    $.ajax({
                        type: 'post',
                        url: 'mdtime/multiSendBalanceQuery',
                        dataType: "json",
                        data: {balanceHdr: selectedBalance},
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function (data) {
                            selectedDdv.datagrid('reload');

                            $(".right-sidebar-content-"+ $uniqId).empty();
                            $(".right-sidebar-content-"+ $uniqId).empty().hide();
                            $(".right-sidebar-"+ $uniqId).attr("data-status", "close");
                            $(".right-sidebar-"+ $uniqId).find(".sidebar-right").removeClass("sidebar-opened");
                            $(".right-sidebar-"+ $uniqId).removeClass("col-md-3");
                            $(".center-sidebar-"+ $uniqId).removeClass("col-md-9");
                            $(".center-sidebar-"+ $uniqId).addClass("col-md-12");
                            
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status
                            });
                            
                            Core.unblockUI();
                            $(dialogName).empty().dialog('destroy').remove();
                        },
                        error: function () {
                            Core.unblockUI();
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Error',
                                text: 'error',
                                type: 'error',
                                sticker: false
                            });
                        }
                    });
                }
            },
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $(dialogName).empty().dialog('destroy').remove();
                }
            }
        ]
    });
    $(dialogName).dialog('open');
}

function tna_is_confirmed_event(target) {
    var target_row = $(target).closest("tr").get(0);
    var span = $(target).closest("span");
    var index = $(target_row).find("#index").html();
    if (index != undefined) {
        var dataIndex = parseFloat(index) - 1;

        if ($(target).is(':checked')) {
            span.addClass("checked");
            tnaTimeBalanceData.rows[dataIndex].IS_CONFIRMED = 1;
        }
        else {
            span.removeClass("checked");
            tnaTimeBalanceData.rows[dataIndex].IS_CONFIRMED = 0;
        }

        tnaRenderSidebar(1, $(target_row), index);
    }
}

function changeInOutTimeData(row) {
    var _sideBarContent = $('.right-sidebar-content', "#employeeTimeBalance");
    
    $.ajax({
        type: 'post',
        url: 'Mdtime/deleteInsertTimeBalanceHdrDtl',
        data: _sideBarContent.find('#' + row.TIME_BALANCE_HDR_ID).find('input').serialize() + '&timeBalanceHdrId='+row.TIME_BALANCE_HDR_ID + '&timeBalanceHdr='+JSON.stringify(row),
        dataType: "json",
        beforeSend: function () {},
        success: function (data) {
            tnaRenderSidebar("#employeeTimeBalance", data);
        },
        error: function () {
            new PNotify({
                title: 'Error',
                text: 'error',
                type: 'error',
                sticker: false
            });
        }
    });
}

function changeInTime(elem, timeBalanceHdrId) {
    var row = $('#tna-balance-data-grid').datagrid('getSelected');
    if (row != null) {
        $('.right-sidebar-content', "#employeeTimeBalance").find('input[name="inTime['+timeBalanceHdrId+']"]').val($(elem).val());
        changeInOutTimeData(row);
    } else {
        $(elem).val($(elem).attr('data-value'));
        new PNotify({
            title: 'Анхааруулга',
            text: 'Cонгосон мөрөө чеклээгүй байна',
            type: 'error',
            sticker: false
        });
    }
    
}

function changeOutTime(elem, timeBalanceHdrId) {
    var row = $('#tna-balance-data-grid').datagrid('getSelected');
    if (row != null) {
        $('.right-sidebar-content', "#employeeTimeBalance").find('input[name="outTime['+timeBalanceHdrId+']"]').val($(elem).val());
        changeInOutTimeData(row);
    } else {
        $(elem).val($(elem).attr('data-value'));
        new PNotify({
            title: 'Анхааруулга',
            text: 'Cонгосон мөрөө чеклээгүй байна',
            type: 'error',
            sticker: false
        });
    }
    
}

function setMinut(elem) {
    var _this = $(elem);
    var cell = _this.closest('td');        
    cell.find('input[data-name="cause_type_value"]').val(timeToMinut(_this.val()));
}

function minutToTime(balanceTime) {
    var hour, minut, temdeg;

  if (typeof balanceTime != 'undefined' && balanceTime !==null && balanceTime.length > 0) {
        temdeg = '';
        var temp = parseFloat(balanceTime);
        if (temp < 0) {
            temdeg = '-';
            balanceTime = balanceTime.substring(1, balanceTime.length);
        }
        
        var _splitValue = balanceTime.split('.');
        
        hour = _splitValue[0];
        if (parseInt(hour) < 10 && parseInt(hour) > 0) {
            hour = '0' + hour;
        } else if(parseInt(hour) > 9) {
            hour = hour;
        } else {
            hour = '00';
        }
        if (_splitValue.length > 1) {
            minut = _splitValue[1];
            if (typeof minut != 'undefined') {
                var _quel = (minut.length == 1) ? 10 : 100;
                minut = (minut * 60) / _quel;
                minut = Math.round(parseFloat(minut));
                if (parseInt(minut) < 10) {
                    minut = '0' + minut;
                } else {
                    minut = minut.toString();
                    minut = minut.substring(0, 2);
                }
            } else {
                minut = '00';
            }
        } else {
            minut = '00';
        }
        
        return temdeg + hour + ':' + minut;
    }
    return '00:00';
}

function minutToTimeBalance(balanceTime) {
    var hour, minut, temdeg;
    
    if (typeof balanceTime != 'undefined' && balanceTime.length > 0) {
        temdeg = '';
        var temp = parseFloat(balanceTime);
        if (temp < 0) {
            temdeg = '-';
            balanceTime = balanceTime.substring(1, balanceTime.length);
        }
        
        var _splitValue = balanceTime.split('.');
        
        hour = _splitValue[0];
        if (parseInt(hour) < 10 && parseInt(hour) > 0) {
            hour = '0' + hour;
        } else if(parseInt(hour) > 9) {
            hour = hour;
        } else {
            hour = '00';
        }
        if (_splitValue.length > 1) {
            minut = _splitValue[1];
            if (typeof minut != 'undefined') {
                minut = minut.substring(0, 2);
                var _quel = (minut.length == 1) ? 10 : 100;
                
                minut = (minut * 60) / _quel;
                minut = Math.round(parseFloat(minut));
                
                if (parseInt(minut) < 10) {
                    minut = '0' + minut;
                } else {
                    minut = minut.toString();
                    minut = minut.substring(0, 2);
                }
            } else {
                minut = '00';
            }
        } else {
            minut = '00';
        }
        
        return temdeg + hour + ':' + minut;
    }
    return '00:00';
}

function onclickfnc(statusText, row, uniqId, type) {
    if (statusText == 'Төлөвлөгөөгүй') {
        if (typeof isAppMultiTab !== 'undefined') {
            if (isAppMultiTab) { 
                return '<a onclick="appMultiTab({weburl: \'mdtime/timeEmployeePlan\', metaDataId: \'mdtimetimeemployeeplan\', title: \'Ажилтны төлөвлөгөө\', type: \'selfurl\'})" href="javascript:;"> '+ statusText +'</a>';
            }
        }
        return '<a href="mdtime/timeEmployeePlan&mmid=144481286877121&mid=1450235706465243"> '+ statusText +'</a>';
    }
    else {
        if (typeof type !== 'undefined' && row.IS_LOG !== '0') {
            return statusText + '<a class="btn btn-xs btn-secondary" title="Өөрчлөлтийн түүх харах" href="javascript:;" onclick="getLogTimeAttendanceData_'+ uniqId +'(this, '+ row.TIME_BALANCE_ID +')"><i style="color:#ff2929;" class="fa fa-history"></i></a>';
        }
        else {
            return statusText;
        }
    }
}

function urlTimeplanFnc() {
    
}

function getMinutToTime(balanceTime) {
    var hour, minut, temdeg;
    
    if (balanceTime.length > 0) {
        temdeg = '';
        var temp = parseFloat(balanceTime);
        if (temp < 0) {
            temdeg = '-';
            balanceTime = balanceTime.substring(1, balanceTime.length);
        }
        
        balanceTime = balanceTime/60;
        var _splitValue = (balanceTime).toString().split('.');
        
        hour = _splitValue[0];
        if (parseInt(hour) < 10 && parseInt(hour) > 0) {
            hour = '0' + hour;
        } else if(parseInt(hour) > 9) {
            hour = hour;
        } else {
            hour = '00';
        }
        if (_splitValue.length > 1) {
            minut = _splitValue[1];
            if (typeof minut != 'undefined') {
                minut = (minut * 60) / 100;
                minut = (Math.round((minut * 100) / 60)  + "e-2")*100;
                if (parseInt(minut) < 10) {
                    minut = '0' + minut;
                } else {
                    minut = minut.toString();
                    minut = minut.substring(0, 2);
                }
            } else {
                minut = '00';
            }
        } else {
            minut = '00';
        }
        return temdeg + hour + ':' + minut;
    }
    return '00:00';
}

function timeToMinut(time) {
    if (time.length > 0) {
        var _splitValue = time.split(':');
        var hour = parseInt(_splitValue[0]);
        var splitDouble = _splitValue[1].split("");
        var ticket = false;
        if (parseInt(_splitValue[1]) < '6') {
            ticket = true;
        }
        if (ticket) {
            var minut = parseInt(splitDouble[1]);
            var min = (Math.round((minut * 100) / 60)  + "e-2")*100;
            min = '0.' + splitDouble[0] + min;
        } else {
            var minut = parseInt(_splitValue[1]);
            var min = (Math.round((minut * 100) / 60)  + "e-2")*100;
            min = '0.' + min;
        }
        
        return parseFloat(hour) + parseFloat(min);
    }
    return 0;
}

function planTimeMore(rowData) {
    var sidebarPlanTimeMoreContent = $("#employeeTimeBalance" + " .grid-plan-time-more");
    $.ajax({
        type: 'post',
        url: 'mdtime/planTimeMore',
        data: {employeeId: rowData['EMPLOYEE_ID'], blanceDate: rowData['BALANCE_DATE']},
        dataType: "json",
        beforeSend: function () {},
        success: function (result) {
            if (result.status == 'success') {
                var html = '<table class="table table-bordered table-hover">';
                html += '<thead>';
                html += '<tr>';
                html += '<th>Цаг/хуваарь</th>';
                html += '<th>Төлөв</th>';
                html += '<th>Эхлэх</th>';
                html += '<th>Дуусах</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                html += '<tr>';
                html += '<td rowspan="2" class="middle">' + result.data['0']['TYPENAME'] + '</td>';
                html += '<td>' + result.data['0']['NAME'] + '</td>';
                html += '<td>' + result.data['0']['START_HOUR'] + '</td>';
                html += '<td>' + result.data['0']['END_HOUR'] + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td>' + result.data['1']['NAME'] + '</td>';
                html += '<td>' + result.data['1']['START_HOUR'] + '</td>';
                html += '<td>' + result.data['1']['END_HOUR'] + '</td>';
                html += '</tr>';
                html += '</tbody>';
                html += '</table>';
                sidebarPlanTimeMoreContent.html(html);
            }
        },
        error: function () {
            $.unblockUI();
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: 'error',
                type: 'error',
                sticker: false
            });
        }
    });
}

function calculateTotal(elem) {
    
    var _this = $(elem);
    var _sideBarContent = _this.closest(".selectedRowDetail");
    var _orginalDefferenceTime = parseFloat(_sideBarContent.find('input[data-name="originalDefferenceTime"]').val());
    var _defferenceTime = _sideBarContent.find('input[data-name="defferenceTime"]');
    var allTr = _sideBarContent.find('tr');
    
    var _totalCauseTypeValue = 0;
    
    if (_orginalDefferenceTime > 0) {
        $.each(allTr, function(key, val){
            var _this = $(this);
            var _causeTypeId = _this.find('input[data-name="cause_type_id"]').val();
            var _causeTypeValue = Number(Math.round(_this.find('input[data-name="cause_type_value"]').val() + 'e2') + 'e-2');
            if (
                    _causeTypeId == '14'
                ) {
                if (!isNaN(parseFloat(_causeTypeValue))) {
                    _totalCauseTypeValue = parseFloat(_totalCauseTypeValue) - parseFloat(_causeTypeValue);
                }
            }
            
            if (_causeTypeId == '1' || 
                    _causeTypeId == '4') {
                _totalCauseTypeValue = parseFloat(_totalCauseTypeValue) + parseFloat(_causeTypeValue);
            }
            if (golomtView == 0 && (
                    _causeTypeId == '15' || 
                    _causeTypeId == '12' || 
                    _causeTypeId == '20' || 
                    _causeTypeId == '13' ||
                    _causeTypeId == '19' ||
                    _causeTypeId == '9' ||
                    _causeTypeId == '1490071083076' ||
                    _causeTypeId == '1487300613154')) {
                _totalCauseTypeValue = parseFloat(_totalCauseTypeValue) + parseFloat(_causeTypeValue);
            }
            
        });
        /* _defferenceTime.val(Math.round(parseFloat(_orginalDefferenceTime) - parseFloat(_totalCauseTypeValue))); */
        _defferenceTime.val(parseFloat(_orginalDefferenceTime) - parseFloat(_totalCauseTypeValue));
    } else if (_orginalDefferenceTime < 0) {
        $.each(allTr, function(key, val) {
            var _this = $(this);
            var _causeTypeId = _this.find('input[data-name="cause_type_id"]').val();
            var _causeTypeValue = Number(Math.round(_this.find('input[data-name="cause_type_value"]').val() + 'e2') + 'e-2');
            if (
                _causeTypeId == '1' || 
                _causeTypeId == '2' || 
                _causeTypeId == '3' || 
                _causeTypeId == '5' || 
                _causeTypeId == '6' || 
                _causeTypeId == '7' || 
                _causeTypeId == '8' ||
                _causeTypeId == '20'|| 
                _causeTypeId == '18'|| 
                _causeTypeId == '19'|| 
                _causeTypeId == '23'|| 
                _causeTypeId == '9' ||
                _causeTypeId == '8059828' ||
                _causeTypeId == '1487300613154' ||
                _causeTypeId == '6604353' ||
                _causeTypeId == '8060108' ||
                _causeTypeId == '10' ||
                _causeTypeId == '12' ||
                _causeTypeId == '13' ||
                _causeTypeId == '19' ||
                _causeTypeId == '1490071083076' ||
                _causeTypeId == '15' ||
                _causeTypeId == '11'
            ) {
                if (!isNaN(parseFloat(_causeTypeValue))) {
                    _totalCauseTypeValue = parseFloat(_totalCauseTypeValue) + parseFloat(_causeTypeValue);
                }
            }
        });
        
        _defferenceTime.val(parseFloat(_orginalDefferenceTime) + parseFloat(_totalCauseTypeValue));
    }
}

function clickCallDialogOpen(elem) {
    var _this = $(elem);
    var sideBarContent = _this.closest('.selectedRowDetail');
    var $dialogName = 'dialog-fillInFor-employee';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'Mdtime/getEmployeeCauseData',
        data: sideBarContent.find('input').serialize()+'&oneCauseType='+_this.closest('tr').find("input[data-name='cause_type_id']").val(),
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                target: '.right-sidebar-content',
                animate: true
            });
        },
        success: function (data) {
            if(data.status === 'info') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Info',
                    text: data.message,
                    type: data.status,
                    sticker: false
                });               
                Core.unblockUI('.right-sidebar-content');
            } else {
                $("#" + $dialogName).empty().html(data.html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('destroy');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-madison', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI('.right-sidebar-content');
            }
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}

function tnaIsConfirmedEvent(target) {
    var target_row = $(target).closest("tr").get(0);
    var index = $(target_row).find("#index").html();
    if (index != undefined) {
        var dataIndex = parseFloat(index) - 1;
        if ($(target).is(':checked')) {
            tnaTimeBalanceData.rows[dataIndex].IS_CONFIRMED = 1;
        }
        else {
            tnaTimeBalanceData.rows[dataIndex].IS_CONFIRMED = 0;
        }

        tnaRenderSidebar(1, $(target_row), index);
    }
}

function confirmMultiTimeBalance(statusId, datauniqId, selectedDdv, url, confirmData) {
    var selectedBalance = [];
    var errorBalance = [];
    var rows = selectedDdv.datagrid('getSelections');
    if (rows.length > 0) {
        selectedDdv.find('.datagrid-body tr').removeClass('datagrid-row-error');
        for(var i = 0; i < rows.length; i++){
            var row = rows[i];

            if (row.DEFFERENCE_TIME != '0' && confirmData === '0') {
                errorBalance.push(row);
                var rowIndex = selectedDdv.datagrid("getRowIndex", row);
                selectedDdv.find('.datagrid-body tr[datagrid-row-index="'+rowIndex+'"]').addClass('datagrid-row-error');
            } else {
                selectedBalance.push(row);
            }
        }
        
        if (errorBalance.length > 0) {
            PNotify.removeAll();
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: 'Зөрчилтэй цаг байгаа тул дахин шалгана уу',
                type: 'warning',
                sticker: false
            });
        } else {
            if (selectedBalance.length > 0) {
                PNotify.removeAll();
                /*
                new PNotify({
                    title: 'Анхааруулга',
                    text: 'Түгжсэн мөрүүдийн мэдээлэл өөрчлөлт орохгүй болохыг анхаарна уу.',
                    type: 'warning',
                    sticker: false
                }); */
                
                $.ajax({
                    type: 'post',
                    url: url,
                    dataType: "json",
                    data: {balanceHdr: selectedBalance, balanceDtl: $('#tnaTimeBalanceForm').serialize(), wfmStatusId: statusId},
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        selectedDdv.datagrid('reload');
                        $(".right-sidebar-content-" + datauniqId).empty();
                        $(".right-sidebar-content-" + datauniqId).empty().hide();
                        $(".right-sidebar-" + datauniqId).attr("data-status", "close");
                        $(".right-sidebar-" + datauniqId).find(".sidebar-right").removeClass("sidebar-opened");
                        $(".right-sidebar-" + datauniqId).removeClass("col-md-3");
                        $(".center-sidebar-" + datauniqId).removeClass("col-md-9");
                        $(".center-sidebar-" + datauniqId).addClass("col-md-12");
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });
                        Core.unblockUI();
                    },
                    error: function () {
                        Core.unblockUI();
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: 'error',
                            type: 'error',
                            sticker: false
                        });
                    }
                });
            }    
        }
    } else {
        PNotify.removeAll();
        new PNotify({
            title: MESSSAGE_WARNING_TITLE,
            text: MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW,
            type: 'warning',
            sticker: false
        });
    }
}

function cancelMultiTimeBalance(statusId, datauniqId, selectedDdv) {
    var selectedBalance = [];
    var errorBalance = [];
    var rows = selectedDdv.datagrid('getSelections');
    
    if (rows.length > 0) {
        selectedDdv.find('.datagrid-body tr').removeClass('datagrid-row-error');
        for(var i=0; i < rows.length; i++){
            selectedBalance.push(rows[i]);
        }
        if (selectedBalance.length > 0) {
            $.ajax({
                type: 'post',
                url: 'mdtime/multiChangeCancelBalanceQuery',
                dataType: "json",
                data: {balanceHdr: selectedBalance, balanceDtl: $('#tnaTimeBalanceForm').serialize(), wfmStatusId: statusId},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    selectedDdv.datagrid('reload');
                    $(".right-sidebar-content-" + datauniqId).empty();
                    $(".right-sidebar-content-" + datauniqId).empty().hide();
                    $(".right-sidebar-" + datauniqId).attr("data-status", "close");
                    $(".right-sidebar-" + datauniqId).find(".sidebar-right").removeClass("sidebar-opened");
                    $(".right-sidebar-" + datauniqId).removeClass("col-md-3");
                    $(".center-sidebar-" + datauniqId).removeClass("col-md-9");
                    $(".center-sidebar-" + datauniqId).addClass("col-md-12");
                    
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success'
                    });
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: 'error',
                        type: 'error',
                        sticker: false
                    });
                }
            });
        }
    } else {
        PNotify.removeAll();
        new PNotify({
            title: MESSSAGE_WARNING_TITLE,
            text: MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW,
            type: 'warning',
            sticker: false
        });
    }
}

function isOneWorkFlowStatus(selectedRow, wfmStatusCode) {
    if (typeof wfmStatusCode == 'undefined') {
        return false;
    }
    wfmStatusCode = wfmStatusCode.toLowerCase();
    var result = true;
    if (wfmStatusCode == '002' || wfmStatusCode == '003') {
        return false;
    }
    return result;
}

function userSessionIsFull() {
    var result;
    $.ajax({
        type: 'post',
        url: 'mdtime/userSessionIsFull',
        dataType: "json",
        async: false,
        success: function (data) {
            result = data;
        }
    });
    return result;
}
    
function tnaTimeBalanceStoggler(element, $uniqId) {
    
    var _thisToggler = $(element);
    
    var centersidebar = $(".center-sidebar-" + $uniqId);
    var rightsidebar = $(".right-sidebar-" + $uniqId);
    var rightsidebarstatus = rightsidebar.attr("data-status");
    if (rightsidebarstatus === "closed") {
        centersidebar.removeClass("col-md-12").addClass("col-md-9");
        rightsidebar.addClass("col-md-3");
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".glyphicon-chevron-left").hide();
        rightsidebar.find(".right-sidebar-content-"+ $uniqId).show(
                "slide", {direction: "right"}, 0,
                function () {
                    rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn("slow");
                    rightsidebar.find(".glyphicon-chevron-right").fadeIn("slow");
                    $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
                }
        );
        rightsidebar.attr('data-status', 'opened');
        _thisToggler.addClass("sidebar-opened");
    } else {
        rightsidebar.find(".glyphicon-chevron-right").hide();
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".right-sidebar-content-"+ $uniqId).hide(
                "slide", {direction: "right"}, 0,
                function () {
                    centersidebar.removeClass("col-md-9").addClass("col-md-12");
                    rightsidebar.removeClass("col-md-3");
                    rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");
                    rightsidebar.find(".glyphicon-chevron-left").fadeIn("slow");
                    $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
                }
        );
        rightsidebar.attr('data-status', 'closed');
        _thisToggler.removeClass("sidebar-opened");
    }
}

function showRightSidebarContent($uniqId) {
    $(".right-sidebar-content-" + $uniqId).show();
    $('.right-sidebar-' + $uniqId).attr("data-status", "opened");
    $('.right-sidebar-' + $uniqId).addClass("col-md-3");
    $('.center-sidebar-' + $uniqId).addClass("col-md-9");
    
    $(".sidebar-right-" + $uniqId).addClass("sidebar-opened");
}

function renderBalanceList($uniqId) {
    $('body #tna-balance-data-grid-' + $uniqId).datagrid({
        fitColumn: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 30,
        pageList: [10,20,30,40,50,100,200],
        url: 'mdtime/balanceListMainDataGrid',
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize()},
        rowStyler:function(index,row) {
            return 'background-color:'+row.STATUS_COLOR+';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, width: '5%', align: 'right', halign: 'center', checkbox:true},
            {field: 'BALANCE_DATE1', title: 'Огноо', sortable: true, halign: 'center', align: 'center', width: '8%'},
            {field: 'EMPLOYEE_NAME', title: 'Овог Нэр (Код)', width: '15%', halign: 'center', sortable: true},
            {field: 'IN_TIME', title: 'Ирсэн', sortable: false, width: '5%', halign: 'center', align: 'center'},
            {field: 'OUT_TIME', title: 'Явсан', sortable: false, align: 'center', halign: 'center', width: '6%'},
            {field: 'PLAN_TIME', title: 'Төлөвлөсөн цаг', sortable: false, width: '6%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'UNCLEAR_TIME', title: 'Ажилласан цаг', sortable: false, width: '6%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'CLEAR_TIME', title: 'Цэвэр цаг', sortable: false, width: '6%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'NIGHT_TIME', title: 'Шөнийн цаг', sortable: false, width: '6%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, width: '6%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'STATUS_TEXT', title: 'Төлөв', sortable: false, width: '13%', align: 'center', halign: 'center', 
                formatter: function(val, row,index){
                    return onclickfnc(val, row, $uniqId);
                }, styler: function(val, row, index){
                    return 'color:'+row.FONT_COLOR+'; ';
                }
            },
            {field: 'WFM_STATUS_NAME', title: 'Шийдвэрлэлтийн төлөв', sortable: false, width: '15%', align: 'center', halign: 'center'},
        ]],
        onClickRow: function (index, row) {
            $.uniform.update();
            var _thisGrid = $(this);
            $("#currentSelectedRowIndex-"+ $uniqId, "#tnaTimeBalanceWindow").val(index);
            $('body').find('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
            
            var splitclass = (_thisGrid.attr('class')).split(" ");
            var closestDivid = _thisGrid.closest("div[data-children-class='"+ splitclass[0] +"']");
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-last-clicked-row');
            closestDivid.find('.datagrid-body tr[datagrid-row-index="'+index+'"]').addClass('datagrid-last-clicked-row');
            
            tnaRenderSidebar(row, index, $uniqId);
            $('body #tna-balance-data-grid-'+ $uniqId).datagrid('resize');
        },
        onCheckAll: function() {
            console.log('echo 6');
            $.uniform.update();
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function() {
            console.log('echo 6');
            $.uniform.update();
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },            
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
            $.contextMenu({
                selector: '.datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function(key, opt) {
                    if (key === 'remove') {
                        var selectedBalance = [];
                        var rows = $('#tna-balance-data-grid-'+ $uniqId).datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiChangeBalance(selectedBalance, $('body #tna-balance-data-grid-' + $uniqId), $uniqId);
                        } else {
                            var dialogName = '#dialog-timeBalanceMultiChanage';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулга',
                                width: '400',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $(dialogName).dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                ]
                            });
                            $(dialogName).dialog('open');
                        }
                    }
                    if(key === 'send') {
                        var selectedBalance = [];
                        var rows = ddv.datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiSendBalance(selectedBalance, $('body #tna-balance-data-grid-' + $uniqId), $uniqId);
                        } else {
                            var dialogName = '#dialog-timeBalanceMultiChanage';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулга',
                                width: '400',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $(dialogName).dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                ]
                            });
                            $(dialogName).dialog('open');
                        }
                    }
                },
                items: {
                    "remove": {name: "Олноор тохируулах", icon: "cogs"},
                    "send": {name: "Мэйл илгээх", icon: "send"},
                }
            });
        },
        onLoadSuccess: function (data) {      
            var _thisGrid = $(this);

            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");

            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }
            var currentSelectedRowIndex = $("#currentSelectedRowIndex-"+ $uniqId, "#tnaTimeBalanceWindow"+$uniqId).val();
            if (currentSelectedRowIndex != "") {
                _thisGrid.datagrid('selectRow', currentSelectedRowIndex);
                var row = $('body #tna-balance-data-grid-'+ $uniqId).datagrid('getSelected');
                tnaRenderSidebar(row, currentSelectedRowIndex, $uniqId);
            }

            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE1]").addClass("dateInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE1]").addClass("text-center");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("timeInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("text-center");

            Core.initInputType(panelView);  
            $('.mergeCelltnaEmployeeBalance').show();
        }
    });
    $('body #tna-balance-data-grid-' + $uniqId).datagrid('enableFilter');
}

function renderGroupMonthBalanceList($uniqId, hidden) {
    $('body #tna-balance-data-grid-' + $uniqId).datagrid({
        view: detailview,
        fitColumns: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 30,
        pageList: [10,20,30,40,50,100,200],
        url: 'mdtime/balanceListMainDataGridNew',
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize()},
        rowStyler:function(index, row) {
            return 'background-color:'+row.BACKGROUND_COLOR+';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, width: '10%', align: 'right', halign: 'center', checkbox:true},
            {field: 'BALANCE_DATE', title: 'Огноо', sortable: true, halign: 'center', align: 'center', width: '13%'},
            {field: 'EMPLOYEE_NAME', title: 'Овог Нэр (Код)', width: '20%', halign: 'center', sortable: true},
            {field: 'DEPARTMENT_NAME', title: 'Салбар нэгж', width: '10%', halign: 'center', sortable: true},
            {field: 'PLAN_TIME', title: 'Төлөвлөсөн цаг', sortable: false, width: '12%', align: 'center', halign: 'center'},
            {field: 'UNCLEAR_TIME', title: 'Ажилласан цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'CLEAR_TIME', title: 'Цэвэр цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'LATE_TIME', title: 'Хоцорсон цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'STATUS', title: 'Төлөв', sortable: false, width: '16%', align: 'center', halign: 'center', 
                formatter: function(val, row,index) {
                    return onclickfnc(val, row, $uniqId);
                }, styler: function(val, row, index) {
                    return 'color:'+row.FONT_COLOR+'; ';
                }
            },
            {field: 'SALARY_TYPE_NAME', title: 'Цалинжих төрөл', sortable: false, width: '12%', align: 'center', halign: 'center', hide:golomtView}
        ]],
        onCheckAll: function() {
            $.uniform.update();
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'month').attr('data-view-id', '#tna-balance-data-grid-' + $uniqId);
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function() {
            $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all').removeAttr('data-view-id');
            $.uniform.update();
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        detailFormatter: function(index, row) {
            return '<div style="padding:2px;" class="'+ $uniqId +'" data-children-class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" id="tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-' + index +'"><table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" style="width:100%"></table></div>';
        },
        onClickRow: function(index, row) {
            $.uniform.update();
            /* $('body #tna-balance-data-grid-' + $uniqId).datagrid('unselectAll'); */
            
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'month').attr('data-view-id', '#tna-balance-data-grid-' + $uniqId);
            $('.context-menu-root').attr('style', 'display:none;');
        },
        onExpandRow: function(index, row) {
            $('#tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index).empty().html('<table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index + '"></table>');
            renderMonthBalanceSubList($('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index), row, index, $uniqId);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', index);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        onLoadSuccess: function (data) {
            $('.context-menu-root').attr('style', 'display:none;');
            var _thisGrid = $(this);

            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");

            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }

            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("dateInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("text-center");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("timeInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("text-center");

            Core.initInputType(panelView);  
            $('.mergeCelltnaEmployeeBalance').show();
            _thisGrid.datagrid('resize'); 
        }
    });
    
    $('body #tna-balance-data-grid-' + $uniqId).datagrid('enableFilter');
}

function renderGolomtGroupMonthBalanceList($uniqId, hidden) {
    $('body #tna-balance-data-grid-' + $uniqId).datagrid({
        view: detailview,
        fitColumns: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 30,
        pageList: [10,20,30,40,50,100,200],
        url: 'mdtime/balanceListMainDataGridNew',
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize()},
        rowStyler:function(index, row) {
            return 'background-color:'+row.BACKGROUND_COLOR+';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, align: 'right', halign: 'center', checkbox:true},
            {field: 'BALANCE_DATE', title: 'Огноо', sortable: true, halign: 'center', align: 'center'},
            {field: 'EMPLOYEE_CODE', title: _employeeCodeBalanceWindow, halign: 'center', sortable: true},
            {field: 'LAST_NAME', title: 'Ажилтны овог', halign: 'center', sortable: true},
            {field: 'FIRST_NAME', title: 'Ажилтны нэр', halign: 'center', sortable: true},
            {field: 'DEPARTMENT_NAME', title: 'Салбар нэгж', width: '10%', halign: 'center', sortable: true},
            {field: 'POSITION_NAME', title: 'Албан тушаал', halign: 'center', sortable: true},
            {field: 'STATUS_NAME', title: 'Ажилтны төлөв', halign: 'center', sortable: true},
            {field: 'SALARY_TYPE_NAME', title: 'Цалинжих төрөл', sortable: false, align: 'center', halign: 'center', hide:golomtView},
            {field: 'PLAN_TIME', title: 'Төлөвлөсөн цаг', sortable: false, align: 'center', halign: 'center'},
            {field: 'CLEAR_TIME', title: 'Цэвэр цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'UNCLEAR_TIME', title: 'Ажилласан цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'LATE_TIME', title: 'Хоцорсон цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'STATUS', title: 'Төлөв', sortable: false,  align: 'center', halign: 'center', 
                formatter: function(val, row, index) {
                    return onclickfnc(val, row, $uniqId);
                }, styler: function(val, row, index) {
                    return 'color:' + row.FONT_COLOR + ';';
                }
            }
        ]],
        onCheckAll: function() {
            $.uniform.update();
            
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'month').attr('data-view-id', '#tna-balance-data-grid-' + $uniqId);
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function() {
            $.uniform.update();
            
            $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all').removeAttr('data-view-id');
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },      
        detailFormatter: function(index, row) {
            return '<div style="padding:2px;" class="'+ $uniqId +'" data-children-class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" id="tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-' + index +'"><table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" style="width:100%"></table></div>';
        },
        onClickRow: function(index, row) {
            $.uniform.update();
            
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'month').attr('data-view-id', '#tna-balance-data-grid-' + $uniqId);
            $('.context-menu-root').attr('style', 'display:none;');
        },
        onExpandRow: function(index, row) {
            $('#tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index).empty().html('<table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index + '"></table>');
            renderMonthBalanceSubList($('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index), row, index, $uniqId);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', index);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        onLoadSuccess: function (data) {      
            $('.context-menu-root').attr('style', 'display:none;');
            var _thisGrid = $(this);

            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");

            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }

            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("monthInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("text-center");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("timeInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("text-center");

            Core.initInputType(panelView);  
            Core.initDateMonth(panelView); 
            $('.mergeCelltnaEmployeeBalance').show();
            _thisGrid.datagrid('resize'); 
        }
    });
    $('body #tna-balance-data-grid-' + $uniqId).datagrid('enableFilter');
}

function renderMonthBalanceSubList(ddv, parentRow, parentIndex, $uniqId, pparentRow, pparentIndex, pddv) {
    ddv.datagrid({
        view: horizonscrollview,
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize(), balanceDate: parentRow.BALANCE_DATE, employeeId: parentRow.EMPLOYEE_ID},
        url: 'mdtime/subBalanceListMainDataGridNew/',
        fitColumns: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 30,
        pageList: [10,20,30,40,50,100,200],
        rowStyler:function(index,row) {
            return 'background-color: '+ row.STATUS_COLOR +';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, align: 'right', halign: 'center', checkbox:true},
            {field: 'BALANCE_DATE1', title: 'Огноо', sortable: true, halign: 'center', align: 'center'},
            {field: 'IN_TIME', title: 'Ирсэн', sortable: false, halign: 'center', align: 'center'},
            {field: 'OUT_TIME', title: 'Явсан', sortable: false, align: 'center', halign: 'center'},
            {field: 'PLAN_TIME', title: 'Төлөвлөсөн цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return val;
            }},
            {field: 'CLEAR_TIME', title: 'Цэвэр цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'UNCLEAR_TIME', title: 'Ажилласан цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'NIGHT_TIME', title: 'Шөнийн цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'LATE_TIME', title: 'Хоцорсон цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'ABSENTEE_TIME', title: 'Тасалсан цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }, hidden: golomtView},
            {field: 'OVERTIME', title: 'Илүү цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }, hidden: golomtView},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'STATUS_TEXT', title: 'Төлөв', sortable: false, align: 'center', halign: 'center', 
                formatter: function(val, row, index) {
                    return onclickfnc(val, row, $uniqId, '1');
                }, styler: function(val, row, index) {
                    return 'color:'+row.FONT_COLOR+'; ';
                }
            },
        ]],
        onCheckAll: function() {
            $.uniform.update();
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val(ddv.attr('class'));
            $('.context-menu-root').attr('style', 'display:none;');
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
                $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all', 'child').removeAttr('data-view-id');
            }
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function(){
            $.uniform.update();
            
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
                $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all', 'child').removeAttr('data-view-id');
            }
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val('0');
            $('.context-menu-root').attr('style', 'display:none;');
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
        },            
        onClickRow: function(index, row) {
            
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
                $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all', 'child').removeAttr('data-view-id');
            }
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val(ddv.attr('class'));
            $('.context-menu-root').attr('style', 'display:none;');
            var _thisGrid = $(this);
            var splitclass = (_thisGrid.attr('class')).split(" ");
            var closestDivid = _thisGrid.closest("div[data-children-class='"+ splitclass[0] +"']");
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-last-clicked-row');
            closestDivid.find('.datagrid-body tr[datagrid-row-index="'+index+'"]').addClass('datagrid-last-clicked-row');
            
            $("#currentSelectedRowIndex-" + $uniqId, "#tnaTimeBalanceWindow" + $uniqId).val(index);
            
            tnaRenderSidebar(row, index, $uniqId);
            $.uniform.update();
            _thisGrid.datagrid('resize'); 
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        onRowContextMenu: function (e, index, row) {
            setTimeout(function () {
                $('.context-menu-root').each(function(i, position) {
                    if (i == $('.context-menu-root').length-1) {
                        $(this).attr('style', 'width: 204px; top: '+ e.pageY +'px; left: '+ e.pageX +'px; z-index: 1;');
                    } else {
                        $(this).attr('style', 'display:none;');
                    }
                });
            }, 400);
            
            e.preventDefault();
            $.contextMenu({
                selector: 'div#tna-subdataGrid-'+ $uniqId + '-' + parentRow.EMPLOYEE_KEY_ID +'-'+ parentIndex +' .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function(key, opt) {
                    if (key === 'remove') {
                        var selectedBalance = [];
                        var rows = ddv.datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiChangeBalance(selectedBalance, ddv, $uniqId);
                        } else {
                            var dialogName = '#dialog-timeBalanceMultiChanage';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулга',
                                width: '400',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $(dialogName).dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                ]
                            });
                            $(dialogName).dialog('open');
                        }
                    }
                    if(key === 'send') {
                        var selectedBalance = [];
                        var rows = ddv.datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiSendBalance(selectedBalance, ddv, $uniqId);
                        } else {
                            var dialogName = '#dialog-timeBalanceMultiChanage';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулга',
                                width: '400',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $(dialogName).dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                ]
                            });
                            $(dialogName).dialog('open');
                        }
                    }
                    if(key === 'lock') {
                        if (typeof _isAdminApproved !== 'undefined' && _isAdminApproved === '1') {
                            var rows = ddv.datagrid('getSelections');
                            if (rows.length > 0) {
                                callIsLockBalanceDialog(rows, ddv, $uniqId);
                            } else {
                                var dialogName = '#dialog-timeBalanceMultiChanage';
                                if (!$(dialogName).length) {
                                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                                }
                                $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                                $(dialogName).dialog({
                                    cache: false,
                                    resizable: true,
                                    bgiframe: true,
                                    autoOpen: false,
                                    title: 'Сануулга',
                                    width: '400',
                                    height: 'auto',
                                    modal: true,
                                    close: function () {
                                        $(dialogName).dialog('close');
                                    },
                                    buttons: [
                                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                                $(dialogName).dialog('close');
                                            }
                                        }
                                    ]
                                });
                                $(dialogName).dialog('open');
                            }
                        } else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Та олноор түгжих эрхгүй байна.',
                                type: 'warning',
                                sticker: false
                            });
                        }
                    }
                },
                items: {
                    "remove": {name: "Олноор тохируулах", icon: "cogs"},
//                    "lock": {name: "Олноор түгжих", icon: "cogs"},
                    "send": {name: "Мэйл илгээх", icon: "send"},
                }
            });
        },
        onLoadSuccess: function(data) {
            if (data.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
            var _thisGrid = $(this);
            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view"); 
            
            showGridMessage(_thisGrid);
            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = _thisGrid.datagrid('getPanel').children('div.datagrid-view')
                          .find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table")
                          .find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }
            _thisGrid.datagrid("getPanel").children("div.datagrid-view")
                        .find(".datagrid-htable")
                        .find(".datagrid-filter-row")
                        .find("input[name=workstartdate]").addClass("dateMaskInit");                                                    
            Core.initInputType(panelView); 
            
            _thisGrid.datagrid('resize'); 
            if (typeof pparentIndex != 'undefined') {
                pddv.datagrid('fixDetailRowHeight', parentIndex);
                pddv.datagrid('resize'); 
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', pparentIndex);
            } else {
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', parentIndex);
            }
            
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        }
    });
    ddv.datagrid('enableFilter');
}

function renderGroupDeparmentBalanceList($uniqId, hidden) {
    $('body #tna-balance-data-grid-' + $uniqId).datagrid({
        view: detailview,
        fitColumns: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 30,
        pageList: [10,20,30,40,50,100,200],
        url: 'mdtime/balanceListMainDataGridDepartmentGroup',
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize()},
        rowStyler:function(index, row) {
            return 'background-color:'+row.BACKGROUND_COLOR+';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, width: '10%', align: 'right', halign: 'center', checkbox:true},
            {field: 'DEPARTMENT_NAME', title: 'Албан тушаал', sortable: true, halign: 'left', align: 'left', width: '93%'},
        ]],
        onCheckAll: function() {
            $.uniform.update();
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
            }
            
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'deparment').attr('data-view-id', 'body #tna-balance-data-grid-' + $uniqId);
            
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function() {
            
            $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all').removeAttr('data-view-id');
            $.uniform.update();
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },      
        detailFormatter: function(index, row) {
            return '<div style="padding:2px;" class="'+ $uniqId +'" data-children-class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.DEPARTMENT_ID +'-'+ index +'" id="tna-subdataGrid-'+ $uniqId + '-' + row.DEPARTMENT_ID +'-' + index +'"><table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.DEPARTMENT_ID +'-'+ index +'" style="width:100%"></table></div>';
        },
        onClickRow: function(index, row) {
            
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
            }
            
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'deparment').attr('data-view-id', 'body #tna-balance-data-grid-' + $uniqId);
            
            $.uniform.update();
            $('.context-menu-root').attr('style', 'display:none;');
        },
        onExpandRow: function(index, row) {
            renderDepartmentBalanceSubList($('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.DEPARTMENT_ID +'-'+ index), row, index, $uniqId, hidden);
            $('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.DEPARTMENT_ID +'-'+ index).datagrid('fixDetailRowHeight', index);
            $('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.DEPARTMENT_ID +'-'+ index).datagrid('resize');
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', index);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        onCollapseRow: function(index, row) {
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('reload');
        },
        onLoadSuccess: function (data) {      
            $('.context-menu-root').attr('style', 'display:none;');
            var _thisGrid = $(this);

            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");

            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }

            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("dateInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("text-center");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("timeInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("text-center");

            Core.initInputType(panelView);  
            $('.mergeCelltnaEmployeeBalance').show();
            _thisGrid.datagrid('resize'); 
        }
    });
    $('body #tna-balance-data-grid-' + $uniqId).datagrid('enableFilter');
}

function renderDepartmentBalanceSubList(ddv, parentRow, parentIndex, $uniqId, hidden) {
    ddv.datagrid({
        view: detailview,
        fitColumns: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 30,
        pageList: [10,20,30,40,50,100,200],
        url: 'mdtime/balanceListMainDataGridNew',
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize()},
        columns: [[
            {field: 'BALANCE_DATE', title: 'Огноо', sortable: true, halign: 'center', align: 'center', width: '13%'},
            {field: 'EMPLOYEE_CODE', title: _employeeCodeBalanceWindow, width: '10%', halign: 'center', sortable: true},
            {field: 'LAST_NAME', title: 'Ажилтны овог', width: '10%', halign: 'center', sortable: true},
            {field: 'FIRST_NAME', title: 'Ажилтны нэр', width: '10%', halign: 'center', sortable: true},
            {field: 'POSITION_NAME', title: 'Албан тушаал', width: '10%', halign: 'center', sortable: true},
            {field: 'STATUS_NAME', title: 'Ажилтны төлөв', width: '10%', halign: 'center', sortable: true},
            {field: 'SALARY_TYPE_NAME', title: 'Цалинжих төрөл', sortable: false, width: '12%', align: 'center', halign: 'center', hide:golomtView},
            {field: 'DEPARTMENT_NAME', title: 'Салбар нэгж', width: '10%', halign: 'center', sortable: true},
            {field: 'PLAN_TIME', title: 'Төлөвлөсөн цаг', sortable: false, width: '12%', align: 'center', halign: 'center'},
            {field: 'CLEAR_TIME', title: 'Цэвэр цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'UNCLEAR_TIME', title: 'Ажилласан цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'LATE_TIME', title: 'Хоцорсон цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'STATUS', title: 'Төлөв', sortable: false, width: '16%', align: 'right', halign: 'right', 
                formatter: function(val,row,index) {
                    return onclickfnc(val, row, $uniqId);
                }, styler: function(val, row, index) {
                    return 'color:'+row.FONT_COLOR+'; ';
                }
            }
        ]],
        onCheckAll: function() {
            $.uniform.update();
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function() {
            $.uniform.update();
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },      
        detailFormatter: function(index, row) {
            return '<div style="padding:2px;" class="'+ $uniqId +'" data-children-class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" id="tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-' + index +'"><table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" style="width:100%"></table></div>';
        },
        onClickRow: function(index, row) {
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all').removeAttr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
            }
            
            $.uniform.update();
            $('.context-menu-root').attr('style', 'display:none;');
        },
        onExpandRow: function(index, row) {
            var count = ddv.datagrid('getRows').length;
            for (var i = 0; i < count; i++) {
                if (index != i) {
                    ddv.datagrid('collapseRow', i);
                }
            }
            
            $('#tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index).empty().html('<table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index + '"></table>');
            renderMonthBalanceSubList($('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index), row, index, $uniqId, parentRow, parentIndex, ddv);
            ddv.datagrid('fixDetailRowHeight', index);
            ddv.datagrid('resize');
            
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', parentIndex);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        onCollapseRow: function(index, row) {
            ddv.datagrid('reload');
        },
        onLoadSuccess: function (data) {      
            $('.context-menu-root').attr('style', 'display:none;');
            var _thisGrid = $(this);

            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");

            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }

            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("monthInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("text-center");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("timeInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("text-center");

            Core.initInputType(panelView);  
            Core.initDateMonth(panelView); 
            $('.mergeCelltnaEmployeeBalance').show();
            
            _thisGrid.datagrid('resize'); 
            
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', parentIndex);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        }
    });
    ddv.datagrid('enableFilter');
}

//<editor-fold defaultstate="collapsed" desc="">
function renderModBalanceList($uniqId, hidden) {
    var $tnaTimeBalanceForm = $("body #tnaTimeBalanceForm" + $uniqId);
    $('body #tna-balance-data-grid-' + $uniqId).datagrid({
        view: detailview,
        fitColumns: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 100,
        pageList: [10,20,30,40,50,100,200],
        url: 'mdtime/balanceListMainDataGridMod',
        queryParams: {"params": $tnaTimeBalanceForm.serialize()},
        rowStyler:function(index, row) {
            return 'background-color:'+row.BACKGROUND_COLOR+';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, width: '10%', align: 'right', halign: 'center', checkbox:true},
            {field: 'BALANCE_DATE', title: 'Огноо', sortable: true, halign: 'center', align: 'center', width: '13%', formatter: function(val,row,index){
                if (val == null) {
                    val = $tnaTimeBalanceForm.find('#startDate').val() + '-' + $tnaTimeBalanceForm.find('#endDate').val();
                }

                return val;
            }},
            {field: 'EMPLOYEE_NAME', title: 'Овог Нэр (Код)', width: '20%', halign: 'center', sortable: true},
            {field: 'DEPARTMENT_NAME', title: 'Салбар нэгж', width: '10%', halign: 'center', sortable: true},
            {field: 'POSITION_NAME', title: 'Албан тушаал', width: '10%', halign: 'center', sortable: true},
            {field: 'CLEAR_TIME', title: 'Ажилласан цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, width: '12%', align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'STATUS', title: 'Төлөв', sortable: false, width: '16%', align: 'center', halign: 'center', 
                formatter: function(val, row,index) {
                    return onclickfnc(val, row, $uniqId);
                }
            }
        ]],
        onCheckAll: function() {
            $.uniform.update();
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'month').attr('data-view-id', '#tna-balance-data-grid-' + $uniqId);
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function() {
            $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all').removeAttr('data-view-id');
            $.uniform.update();
            $('#tnaTimeBalanceWindow').find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        detailFormatter: function(index, row) {
            return '<div style="padding:2px;" class="'+ $uniqId +'" data-children-class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" id="tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-' + index +'"><table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" style="width:100%"></table></div>';
        },
        onClickRow: function(index, row) {
            $.uniform.update();
            /* $('body #tna-balance-data-grid-' + $uniqId).datagrid('unselectAll'); */
            
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'month').attr('data-view-id', '#tna-balance-data-grid-' + $uniqId);
            $('.context-menu-root').attr('style', 'display:none;');
        },
        onExpandRow: function(index, row) {
            $('#tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index).empty().html('<table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index + '"></table>');
            renderMonthBalanceModSubList($('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index), row, index, $uniqId);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', index);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        onLoadSuccess: function (data) {
            $('.context-menu-root').attr('style', 'display:none;');
            var _thisGrid = $(this);

            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");

            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }

            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("dateInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("text-center");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("timeInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("text-center");

            Core.initInputType(panelView);  
            $('.mergeCelltnaEmployeeBalance').show();
            _thisGrid.datagrid('resize'); 
        }
    });
    
    $('body #tna-balance-data-grid-' + $uniqId).datagrid('enableFilter');
}

function renderMonthBalanceModSubList(ddv, parentRow, parentIndex, $uniqId, pparentRow, pparentIndex, pddv) {
    ddv.datagrid({
        view: horizonscrollview,
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize(), balanceDate: parentRow.BALANCE_DATE, employeeId: parentRow.EMPLOYEE_ID},
        url: 'mdtime/subBalanceListMainDataGridMod/',
        fitColumns: true,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 60,
        pageList: [10,20,30,40,50,60,100,200],
        rowStyler:function(index,row) {
            return 'background-color: '+ row.STATUS_COLOR +';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, align: 'right', halign: 'center', checkbox:true},
            {field: 'BALANCE_DATE1', title: 'Огноо', sortable: true, halign: 'center', align: 'center'},
            {field: 'IN_TIME', title: 'Ирсэн', sortable: false, halign: 'center', align: 'center'},
            {field: 'OUT_TIME', title: 'Явсан', sortable: false, align: 'center', halign: 'center'},
            {field: 'CLEAR_TIME', title: 'Ажилласан цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'NIGHT_TIME', title: 'Шөнийн цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'STATUS_TEXT', title: 'Төлөв', sortable: false, align: 'center', halign: 'center', 
                formatter: function(val, row, index) {
                    return onclickfnc(val, row, $uniqId, '1');
                }
            }
        ]],
        onCheckAll: function() {
            $.uniform.update();
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val(ddv.attr('class'));
            $('.context-menu-root').attr('style', 'display:none;');
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
                $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all', 'child').removeAttr('data-view-id');
            }
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
        },
        onUncheckAll: function(){
            $.uniform.update();
            
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
                $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all', 'child').removeAttr('data-view-id');
            }
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val('0');
            $('.context-menu-root').attr('style', 'display:none;');
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
        },            
        onClickRow: function(index, row) {
            
            var bActiveTnaBalanceId = $('button[data-uniqid="'+ $uniqId +'"]').attr('data-view-id');
            if (typeof bActiveTnaBalanceId !== 'undefined' && bActiveTnaBalanceId !== '') {
                $(bActiveTnaBalanceId).datagrid('uncheckAll');
                $('button[data-uniqid="'+ $uniqId +'"]').removeAttr('data-all', 'child').removeAttr('data-view-id');
            }
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val(ddv.attr('class'));
            $('.context-menu-root').attr('style', 'display:none;');
            var _thisGrid = $(this);
            var splitclass = (_thisGrid.attr('class')).split(" ");
            var closestDivid = _thisGrid.closest("div[data-children-class='"+ splitclass[0] +"']");
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-last-clicked-row');
            closestDivid.find('.datagrid-body tr[datagrid-row-index="'+index+'"]').addClass('datagrid-last-clicked-row');
            
            $("#currentSelectedRowIndex-" + $uniqId, "#tnaTimeBalanceWindow" + $uniqId).val(index);
            
            tnaRenderSidebar(row, index, $uniqId);
            $.uniform.update();
            _thisGrid.datagrid('resize'); 
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        onRowContextMenu: function (e, index, row) {
            setTimeout(function () {
                $('.context-menu-root').each(function(i, position) {
                    if (i == $('.context-menu-root').length-1) {
                        $(this).attr('style', 'width: 204px; top: '+ e.pageY +'px; left: '+ e.pageX +'px; z-index: 1;');
                    } else {
                        $(this).attr('style', 'display:none;');
                    }
                });
            }, 400);
            
            e.preventDefault();
            $.contextMenu({
                selector: 'div#tna-subdataGrid-'+ $uniqId + '-' + parentRow.EMPLOYEE_KEY_ID +'-'+ parentIndex +' .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function(key, opt) {
                    if (key === 'remove') {
                        var selectedBalance = [];
                        var rows = ddv.datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiChangeBalance(selectedBalance, ddv, $uniqId);
                        } else {
                            var dialogName = '#dialog-timeBalanceMultiChanage';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулга',
                                width: '400',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $(dialogName).dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                ]
                            });
                            $(dialogName).dialog('open');
                        }
                    }
                    if(key === 'send') {
                        var selectedBalance = [];
                        var rows = ddv.datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiSendBalance(selectedBalance, ddv, $uniqId);
                        } else {
                            var dialogName = '#dialog-timeBalanceMultiChanage';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).empty().html(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
                            $(dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Сануулга',
                                width: '400',
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $(dialogName).dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                ]
                            });
                            $(dialogName).dialog('open');
                        }
                    }
                },
                items: {
                    "remove": {name: "Олноор тохируулах", icon: "cogs"},
                    "send": {name: "Мэйл илгээх", icon: "send"}
                }
            });
        },
        onLoadSuccess: function(data) {
            if (data.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
            var _thisGrid = $(this);
            var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view"); 
            
            showGridMessage(_thisGrid);
            if (_thisGrid.datagrid('getRows').length == 0) {
                var tr = _thisGrid.datagrid('getPanel').children('div.datagrid-view')
                          .find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table")
                          .find("tbody tr");
                $(tr).find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            }
            _thisGrid.datagrid("getPanel").children("div.datagrid-view")
                        .find(".datagrid-htable")
                        .find(".datagrid-filter-row")
                        .find("input[name=workstartdate]").addClass("dateMaskInit");                                                    
            Core.initInputType(panelView); 
            
            _thisGrid.datagrid('resize'); 
            if (typeof pparentIndex != 'undefined') {
                pddv.datagrid('fixDetailRowHeight', parentIndex);
                pddv.datagrid('resize'); 
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', pparentIndex);
            } else {
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', parentIndex);
            }
            
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        }
    });
    ddv.datagrid('enableFilter');
}
//</editor-fold>
