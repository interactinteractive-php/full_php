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
    SELECTED_EMPLOYEE_DESCRIPTION = [],
    tnaSidebarWidth = 0,
    TIMEBALANCEV5 = {};

$(function () {

    $('body').on("change", "select[name='calcIdBalance']", function () {
        var $this = $(this);
        $this.closest('.form-body').find('#startDate').datepicker('update', date('Y-m-d', strtotime($this.find('option:selected').attr('startdate'))));
        $this.closest('.form-body').find('#endDate').datepicker('update', date('Y-m-d', strtotime($this.find('option:selected').attr('enddate'))));
    });
    
    $('body').on("click", ".confirmTimeBalance", function () {
        var _this = $(this);
        var datauniqId = _this.attr('data-uniqid');
        var wfmStatusId = _this.attr('data-status-id');
        var wfmStatusCode = _this.attr('data-status-code');
        var selectedDdv = _selectedDdv = '';
        var rows = [];
        var url = 'mdtimestable/multiChangeBalanceC';
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
            url = 'mdtimestable/multiChangeBalanceDeparment';
            return;
        }
        
        if (typeof _this.attr('data-all') !== 'undefined' && _this.attr('data-all') === 'month') {
            _selectedDdv = $(_this.attr('data-view-id'));
            rows = _selectedDdv.datagrid('getSelections');
            confirmData = '1';
            
            url = 'mdtimestable/multiChangeBalanceMonth';
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
    
     $('body').on("click", ".downloadData", function () {
         var uniqId = $(this).attr('data-uniqid');
         var dialogName = 'dialog-timeBalanceDownloadData';
         
         var employeesString = '', employees = [];
         
         try {
             employees = $('body #tna-balance-data-grid-' + uniqId).datagrid('getSelections');
         } catch(e){
         }
         
         if(employees.length > 0) {
             for(var ii = 0; ii < employees.length; ii++)
                 employeesString += employees[ii].EMPLOYEE_ID + ',';
         }
         
         $.ajax({
             type: 'post',
             url: 'mdtimestable/downloadProcedure',
             dataType: "json",
             data: {balanceParam: $('#tnaTimeBalanceForm'+uniqId).serialize() + '&employeesString=' + employeesString},
             beforeSend: function () {
                 Core.blockUI({
                     animate: true
                 });
             },
             success: function (data) {
                 new PNotify({
                     title: 'Success',
                     text: data.message,
                     type: 'success'
                 });
                 getBalanceList(uniqId);
 
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
     }); 
     

     $('body').on("click", ".balance-workflow-btn", function () {
         var $this = $(this);

        if ($this.parent().find('div.workflow-dropdown-balance > li').length > 1) {
            return;
        }
        
        $.ajax({
            type: 'post',
            url: 'mdtimestable/getWfmStatusData',
            dataType: "html",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $this.parent().find('div.workflow-dropdown-balance').prepend(data);
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

    });   
    
    $('body').on("click", ".downloadDataIO", function () {
        var uniqId = $(this).attr('data-uniqid');
        var dialogName = 'dialog-timeBalanceDownloadData';
        
        var employeesString = '', employees = [];
        
        try {
            employees = $('body #tna-balance-data-grid-' + uniqId).datagrid('getSelections');
        } catch(e){
        }
        
        if(employees.length > 0) {
            for(var ii = 0; ii < employees.length; ii++) {
                if (typeof employees[ii].EMPLOYEE_ID !== 'undefined') {
                    employeesString += employees[ii].EMPLOYEE_ID + ',';
                } else {
                    employeesString += employees[ii].employeeid + ',';
                }
            }
        }
        
        $.ajax({
            type: 'post',
            url: 'mdtimestable/downloadProcedureIO',
            dataType: "json",
            data: {balanceParam: $('#tnaTimeBalanceForm'+uniqId).serialize() + '&employeesString=' + employeesString},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.title,
                    text: data.message,
                    addclass: pnotifyPosition,
                    type: data.status,
                    hide: false
                });
                
                if(data.status === 'success') {
                    getBalanceList(uniqId);
                }

                Core.unblockUI();
            },
            error: function () {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: 'Сервертэй холбогдоход алдаа гарлаа!',
                    type: 'error',
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }
        });
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
        var description = cell.find('input[data-description]');
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
            title: 'Тайлбар оруулах',
            width: 400,
            height: 'auto',
            modal: true,
            close: function () {
                $("#" + $dialogName).empty().dialog('destroy');
            },
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                    description.val($('textarea[name="causeDescription"]').val());                    
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdtimestable/saveBalanceDescription',
                        data: { 
                            'balanceId': selectedDataRow.TIME_BALANCE_HDR_ID, 
                            'causeTypeId': _this.closest('tr').find('input[data-name="cause_type_id"]').val(), 
                            'description': $('textarea[name="causeDescription"]').val() 
                        },
                        dataType: "json",
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function (data) {
                            Core.unblockUI();
                            
                            if(data.status === 'empty')
                                return;
                            
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
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
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');
    });
    
    $(document).on("click", ".employeeBalanceDescriptionTime", function () {
        var _this = $(this);
        var cell = _this.closest('td');
        var description = cell.find('input[type="hidden"]');
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
            title: 'Тайлбар оруулах',
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
    
    /*$(document).on("hover", "#tnaTimeBalanceForm table tbody tr", function () {
        var _this = $(this);
        
        $("#tnaTimeBalanceForm table tbody tr").find('.employeeBalanceDescriptionContainer').hide();
        _this.find('.employeeBalanceDescriptionContainer').show();
    });*/
    
    $("body").on("click", "#tnaTimeBalanceForm .employeeBalanceDetail", function () {
        var _this = $(this);
        var _sideBarContent = _this.closest(".selectedRowDetail");
        var dialogName = '#employeeBalanceDetailDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdtimestable/getEmployeeTimeAttendance',
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
            $.download('mdtimestable/exportBalanceListMainDataGrid', 'form', $("#tnaTimeBalanceForm").serialize(), 'post', '');
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
            new PNotify({
                title: 'Warning',
                text: 'Таны оруулсан цаг зөрүү цагаас ялгаатай байгаа тул цагаа зөв оруулна уу. <strong><i>( ' + minutToTime(_sideBarContent.find('input[data-name="defferenceTime"]').val()) + ' )</i></strong>',
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
        var dataTimeConfirm = _this.hasAttr('data-time-confirm') ? '1' : '0';
        
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
                
                //if ((_sideBarContent.find('input.intime').val()).length > 0 || (_sideBarContent.find('input.outtime').val()) > 0) {
                    $ticket = true;
                //}
                
                if ($ticket) {
                    calculateTotal(this);

                    var _orginalDefferenceTime = parseFloat(_sideBarContent.find('input[data-name="originalDefferenceTime"]').val());
                    var allTr = _sideBarContent.find('tr'), _totalCauseTypeValue = 0;

                    if (_orginalDefferenceTime < 0) {
                        
                        $.each(allTr, function(key, val) {
                            var _this = $(this);
                            var _causeTypeId = _this.find('input[data-name="cause_type_id"]').val();
                            
                            if(_causeTypeId != '17' && _causeTypeId != '4') {
                                var _causeTypeValue = Number(Math.round(_this.find('input[data-name="cause_type_value"]').val() + 'e2') + 'e-2');
                                
                                if (!isNaN(parseFloat(_causeTypeValue))) {
                                    _totalCauseTypeValue = parseFloat(_totalCauseTypeValue) + parseFloat(_causeTypeValue);
                                }
                            }
                        });
                
                        var _dtime = _orginalDefferenceTime + parseFloat(_totalCauseTypeValue);

                        if((_dtime != 0) && _totalCauseTypeValue != 0) {
                            _dtime = _dtime.toString();

                            PNotify.removeAll(); 
                            new PNotify({
                                title: 'Warning',
                                text: 'Таны оруулсан цаг зөрүү цагаас ялгаатай байгаа тул цагаа зөв оруулна уу. <strong><i>( ' + minutToTime(_dtime) + ' )</i></strong>',
                                type: 'warning',
                                sticker: false
                            });
                            return false;        
                        }
                    }

                    var dialogName = '#dialog-fillInFor-employee';
                    if (!$(dialogName).length) {
                        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                    }
                    
                    var timeBalanceHdrId = selectedDataRow.TIME_BALANCE_ID;
                    if (typeof stimeBalanceHdrId === 'undefined') {
                        timeBalanceHdrId = selectedDataRow.TIME_BALANCE_HDR_ID;
                    }                
                    $.ajax({
                        type: 'post',
                        url: 'mdtimestable/getEmployeeConfirmDataV5',
                        data: _sideBarContent.find('input').serialize() + '&timeBalanceHdrId=' + timeBalanceHdrId + '&wfmStatusId='+wfmStatusId + '&wfmStatusCode=' + wfmStatusCode + '&timeConfirm=' + dataTimeConfirm,
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
                            Core.initAjax($("#tnaTimeBalanceWindow" + datauniqId));
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

                } else {
                    calculateTotal(this);
                    
                    if (parseFloat(_sideBarContent.find('input[data-name="defferenceTime"]').val()) != 0) {
                        new PNotify({
                            title: MESSSAGE_WARNING_TITLE,
                            text: 'Таны оруулсан цаг зөрүү цагаас ялгаатай байгаа тул цагаа зөв оруулна уу. <strong><i>( ' + minutToTime(_sideBarContent.find('input[data-name="defferenceTime"]').val()) + ' )</i></strong>',
                            type: 'warning',
                            sticker: false
                        });
                        return false;

                    } else {
                        var tickeet = false;

                        if (tickeet) {
                            return false;
                        }

                        if (parseFloat(_sideBarContent.find('input[data-name="defferenceTime"]').val()) === 0) {
                            
                            var timeBalanceHdrId = selectedDataRow.TIME_BALANCE_ID;
                            if (typeof stimeBalanceHdrId === 'undefined') {
                                timeBalanceHdrId = selectedDataRow.TIME_BALANCE_HDR_ID;
                            }

                            $.ajax({
                                type: 'post',
                                url: 'mdtimestable/getEmployeeConfirmDataV5',
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
                                    Core.initAjax($("#tnaTimeBalanceWindow" + datauniqId));
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
                                url: 'mdtimestable/getEmployeeCancelStatus',
                                data: 'timeBalanceHdrId=' + timeBalanceHdrId + '&wfmStatusId=' + wfmStatusId + '&employeeId=' + selectedDataRow.EMPLOYEE_ID + '&balanceDate=' + selectedDataRow.BALANCE_DATE,
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
    
    $("body").on("keydown", "#employeeTimeBalance input[name=\"stringValue\"]", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        
        if (keyCode == 13) {
            $(".search-tms-btn", "#employeeTimeBalance").click();
            e.preventDefault();
            return false;        
        }
    });    
    
});

function callIsLockBalanceDialog(selectedRows, selectedDdv, $uniqId) {
    var $dialogName = 'dialog-balance-isLock';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtimestable/isLockPlan',
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
                            //if ($("input[name='isLock']:checked", "#" + $dialogName).val() == '1') {
                                $("#isLockForm").validate({
                                    errorPlacement: function () {}
                                });
                                if ($("#isLockForm").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdtimestable/isLockBalanceQuery',
                                        dataType: "json",
                                        data: {"data": selectedRows, "lockEndDate": $("#lockEndDate", "#" + $dialogName).val()},
                                        beforeSend: function () {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function (data) {
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                            
                                            if (typeof selectedDdv != 'undefined' && typeof $uniqId != 'undefined') {
                                                selectedDdv.datagrid('reload');

                                                /*$(".right-sidebar-content-"+ $uniqId).empty();
                                                $(".right-sidebar-content-"+ $uniqId).empty().hide();
                                                $(".right-sidebar-"+ $uniqId).attr("data-status", "close");
                                                $(".right-sidebar-"+ $uniqId).find(".sidebar-right").removeClass("sidebar-opened-tna");
                                                $(".right-sidebar-"+ $uniqId).removeClass("col-md-3");
                                                $(".center-sidebar-"+ $uniqId).removeClass("col-md-9");
                                                $(".center-sidebar-"+ $uniqId).addClass("col-md-12");*/
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
                            /*} else {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdtimestable/isLockBalanceQuery',
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
                            }*/
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
        Core.initAjax($("#" + $dialogName));
    });
}

function getBalanceList($uniqId) {
    //$("body #tnaTimeBalanceForm" + $uniqId).validate({errorPlacement: function () {}});

    if ($("#tnaTimeBalanceForm" + $uniqId).find('select[name="groupId[]"]').val() == null && $("#tnaTimeBalanceForm" + $uniqId).find('input[name="newDepartmentId[]"]').val() == '') {
        PNotify.removeAll();
        new PNotify({
            title: 'Info',
            text: 'Салбар нэгж эсвэл Ээлжийн бүлэг заавал бөглөнө үү?',
            type: 'info',
            sticker: false
        });
        $('html, body').animate({
            scrollTop: 0
        }, 0);
        return;
    }    

    // if ($("body #tnaTimeBalanceForm" + $uniqId).valid()) {
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
            case 'merge':
                renderMergeBalanceV2SubList($uniqId);
                break;
            default: {
                renderBalanceList($uniqId, true);    
                break;
            }
        }
    // } else {

    // }
}

function multiChangeBalance(selectedBalance, selectedDdv, $uniqId) {
    var dialogName = '#dialog-timeBalanceMultiChanage';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    
    var wfmStatusId = 0;
    $.ajax({
        type: 'post',
        url: 'mdtimestable/getOneWfmStatus',
        dataType: "json",
        data: {"wfmStatusCode": "tnaNewBalance"},
        async: false,
        success: function (data) {
            wfmStatusId = data.WFM_STATUS_ID;
        }
    });
    $.ajax({
        type: 'post',
        url: 'mdtimestable/multiChangeBalanceV3',
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
                            var postData = {balanceHdr: selectedBalance, balanceDtl: $('#multiChangeBalanceForm', dialogName).serialize(), wfmStatusId: wfmStatusId};
                            
                            if(typeof isMod !== "undefined"){
                                $.extend(postData, {isModWindow: true});
                            }
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdtimestable/multiChangeBalanceQueryV3',
                                dataType: "json",
                                data: postData,
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    selectedDdv.datagrid('reload');
                                    
                                    /*$(".right-sidebar-content-"+ $uniqId).empty();
                                    $(".right-sidebar-content-"+ $uniqId).empty().hide();
                                    $(".right-sidebar-"+ $uniqId).attr("data-status", "close");
                                    $(".right-sidebar-"+ $uniqId).find(".sidebar-right").removeClass("sidebar-opened-tna");
                                    $(".right-sidebar-"+ $uniqId).removeClass("col-md-3");
                                    $(".center-sidebar-"+ $uniqId).removeClass("col-md-9");
                                    $(".center-sidebar-"+ $uniqId).addClass("col-md-12");*/
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
        Core.initDateTimeInput($(dialogName));
        Core.initUniform($(dialogName));
        $("input.timeInit", $(dialogName)).inputmask({
          mask: "s:s",
          placeholder: "__:__",
          alias: "datetime",
          hourFormat: "24"
        });        
    });
}

function multiRemoveBalance(selectedBalance, selectedDdv, $uniqId) {
    $.ajax({
        type: 'post',
        url: 'mdtimestable/getEmployeeCancelStatus',
        dataType: "json",
        data: {rows: selectedBalance},
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            selectedDdv.datagrid('reload');

            /*$(".right-sidebar-content-"+ $uniqId).empty();
            $(".right-sidebar-content-"+ $uniqId).empty().hide();
            $(".right-sidebar-"+ $uniqId).attr("data-status", "close");
            $(".right-sidebar-"+ $uniqId).find(".sidebar-right").removeClass("sidebar-opened-tna");
            $(".right-sidebar-"+ $uniqId).removeClass("col-md-3");
            $(".center-sidebar-"+ $uniqId).removeClass("col-md-9");
            $(".center-sidebar-"+ $uniqId).addClass("col-md-12");*/
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
                        url: 'mdtimestable/multiSendBalanceQuery',
                        dataType: "json",
                        data: {balanceHdr: selectedBalance},
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function (data) {
                            selectedDdv.datagrid('reload');

                            /*$(".right-sidebar-content-"+ $uniqId).empty();
                            $(".right-sidebar-content-"+ $uniqId).empty().hide();
                            $(".right-sidebar-"+ $uniqId).attr("data-status", "close");
                            $(".right-sidebar-"+ $uniqId).find(".sidebar-right").removeClass("sidebar-opened-tna");
                            $(".right-sidebar-"+ $uniqId).removeClass("col-md-3");
                            $(".center-sidebar-"+ $uniqId).removeClass("col-md-9");
                            $(".center-sidebar-"+ $uniqId).addClass("col-md-12");*/
                            
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

        /*tnaRenderSidebar(1, $(target_row), index);*/
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
            /*tnaRenderSidebar("#employeeTimeBalance", data);*/
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

function changeListenerIO(elem) {
    var _this = $(elem);
    var cell = _this.closest('td');
    cell.find('input:last').removeAttr('disabled');
}

function minutToTime(balanceTime) {
    var hour, minut;

    if (balanceTime != 0 && balanceTime !== null && balanceTime !== '') {
        balanceTime = Number(balanceTime);
        hour = Math.trunc(balanceTime / 60);
        minut = balanceTime % 60;
             
        if (hour < 10 && hour >= 0) {
            
            if(hour < 0) {
                hour = '-0' + (hour) * (-1);              
            } else {
                hour = '0' + hour;
            }
            
        } else {
            
            if(hour < 0 && hour > -10)
                hour = '-0' + (hour) * (-1);
            else
                hour = hour;
        }
        
        if(minut < 0)
            minut = minut * (-1);
        
        if (minut < 10) {
            minut = '0' + minut;
        } else {
            minut = minut;
        }

        return hour + ':' + minut;
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
                return '<a onclick="appMultiTab({weburl: \'mdtimestable/timeEmployeePlanV2\', metaDataId: \'mdtimetimeemployeeplan\', title: \'Ажилтны төлөвлөгөө\', type: \'selfurl\'})" href="javascript:;"> '+ statusText +'</a>';
            }
        }
        return '<a href="mdtimestable/timeEmployeePlanV2&mmid=144481286877121&mid=1450235706465243"> '+ statusText +'</a>';
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
        var hour = Number(_splitValue[0]);
        var min = Number(_splitValue[1]);
        
        return (hour * 60) + min;
    }
    return 0;
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
            //if (isTnaHishgarvinConfig && (_causeTypeId == '15' || _causeTypeId == '12' || _causeTypeId == '13' || _causeTypeId == '1' || _causeTypeId == '1487300613154' || _causeTypeId == '4')) {
            _totalCauseTypeValue = parseFloat(_totalCauseTypeValue) + parseFloat(_causeTypeValue);
            //}
        });
        
        var var1 = parseFloat(_orginalDefferenceTime) - parseFloat(_totalCauseTypeValue);
        var1 = var1.toFixed(2);
        
        if(_totalCauseTypeValue != 0) {
            _defferenceTime.val(var1);
        } else
            _defferenceTime.val(0);
        
    } else if (_orginalDefferenceTime < 0) {
        
        $.each(allTr, function(key, val) {
            var _this = $(this);
            var _causeTypeId = _this.find('input[data-name="cause_type_id"]').val();
            var _causeTypeValue = Number(Math.round(_this.find('input[data-name="cause_type_value"]').val() + 'e2') + 'e-2');
            
            if (!isNaN(parseFloat(_causeTypeValue))) {
                _totalCauseTypeValue = parseFloat(_totalCauseTypeValue) + parseFloat(_causeTypeValue);
            }
        });
        
        var var1 = parseFloat(_orginalDefferenceTime) + parseFloat(_totalCauseTypeValue);
        var1 = var1.toFixed(2);
        
        if((var1 < -0.02 || var1 > 0.02) && _totalCauseTypeValue != 0) {
            _defferenceTime.val(var1);
        } else
            _defferenceTime.val(0);
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
                    width: 500,
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

        /*tnaRenderSidebar(1, $(target_row), index);*/
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
                        /*$(".right-sidebar-content-" + datauniqId).empty();
                        $(".right-sidebar-content-" + datauniqId).empty().hide();
                        $(".right-sidebar-" + datauniqId).attr("data-status", "close");
                        $(".right-sidebar-" + datauniqId).find(".sidebar-right").removeClass("sidebar-opened-tna");
                        $(".right-sidebar-" + datauniqId).removeClass("col-md-3");
                        $(".center-sidebar-" + datauniqId).removeClass("col-md-9");
                        $(".center-sidebar-" + datauniqId).addClass("col-md-12");*/
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
                url: 'mdtimestable/multiChangeCancelBalanceQuery',
                dataType: "json",
                data: {balanceHdr: selectedBalance, balanceDtl: $('#tnaTimeBalanceForm').serialize(), wfmStatusId: statusId},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    selectedDdv.datagrid('reload');
                    /*$(".right-sidebar-content-" + datauniqId).empty();
                    $(".right-sidebar-content-" + datauniqId).empty().hide();
                    $(".right-sidebar-" + datauniqId).attr("data-status", "close");
                    $(".right-sidebar-" + datauniqId).find(".sidebar-right").removeClass("sidebar-opened-tna");
                    $(".right-sidebar-" + datauniqId).removeClass("col-md-3");
                    $(".center-sidebar-" + datauniqId).removeClass("col-md-9");
                    $(".center-sidebar-" + datauniqId).addClass("col-md-12");*/
                    
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
        url: 'mdtimestable/userSessionIsFull',
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
        centersidebar.removeClass("col-md-12").addClass("col-md-10");
        rightsidebar.addClass("col-md-2");
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
        _thisToggler.addClass("sidebar-opened-tna");
    } else {
        rightsidebar.find(".glyphicon-chevron-right").hide();
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".right-sidebar-content-"+ $uniqId).hide(
                "slide", {direction: "right"}, 0,
                function () {
                    centersidebar.removeClass("col-md-10").addClass("col-md-12");
                    rightsidebar.removeClass("col-md-2");
                    rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");
                    rightsidebar.find(".glyphicon-chevron-left").fadeIn("slow");
                    $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
                }
        );
        rightsidebar.attr('data-status', 'closed');
        _thisToggler.removeClass("sidebar-opened-tna");
    }
}

function showRightSidebarContent($uniqId) {
    $(".right-sidebar-content-" + $uniqId).show();
    $('.right-sidebar-' + $uniqId).attr("data-status", "opened");
    $('.right-sidebar-' + $uniqId).addClass("col-md-2");
    $('.center-sidebar-' + $uniqId).removeClass("col-md-12").addClass("col-md-10");
    
    $(".sidebar-right-" + $uniqId).addClass("sidebar-opened-tna");
}

function renderBalanceList($uniqId) {
    var $tnaTimeBalanceForm = $("body #tnaTimeBalanceForm" + $uniqId);
    var urlStr = 'mdtimestable/balanceListMainDataGridV5', colFreezeObj;
    var colObj = [
        {field: 'IS_CONFIRMED', title: '', sortable: true, width: '25px', align: 'right', halign: 'center', checkbox:true},
        {field: 'BALANCE_DATE', title: 'Огноо', sortable: true, halign: 'center', align: 'center', width: '80px', formatter: function(val,row,index){
            if (val == null) {
                val = $tnaTimeBalanceForm.find('#startDate').val() + '-' + $tnaTimeBalanceForm.find('#endDate').val();
            }

            if(row.IS_USER_CONFIRMED != '0' && row.IS_USER_CONFIRMED != '' && row.IS_USER_CONFIRMED != null) {
                val = '<i class="fa fa-pencil editLog-'+$uniqId+'" title="Засах үйлдэл хийсэн байна"></i> ' + val;
            }

            return val;
        }},
        {field: 'EMPLOYEE_NAME', title: 'Овог Нэр (Код)', width: '12%', halign: 'center', sortable: true, formatter: function(val,row,index){
            return '<a style="color:#333;font-weight:bold" href="javascript:;" title="'+capitalizeFirstLetter(row.LAST_NAME)+' '+capitalizeFirstLetter(row.FIRST_NAME)+' ('+row.EMPLOYEE_CODE+')'+'" onclick="tnaSubGrid(this, \''+row.EMPLOYEE_KEY_ID+'\', \''+$uniqId+'\', \''+index+'\', \''+row.BALANCEDATE+'\', \''+row.EMPLOYEE_ID+'\', \''+row.LAST_NAME+'\', \''+row.FIRST_NAME+'\', \''+row.EMPLOYEE_CODE+'\', \''+row.POSITION_NAME+'\');"><i class="icon-plus3 font-size-12"></i> '+val+'</a>';
        }},
        //{field: 'DEPARTMENT_NAME', title: 'Салбар нэгж', width: '18%', halign: 'center', sortable: true},
        {field: 'POSITION_NAME', title: 'Албан тушаал', width: '13%', halign: 'center', sortable: true}
    ];
    $(".color-description-"+$uniqId).removeClass('hide');
    /*var dynamicHeight = $(window).height() - 240;
    $('body #tna-balance-data-grid-' + $uniqId).attr('height', dynamicHeight);*/    
    
    $.ajax({
        type: 'post',
        url: 'mdtimestable/existMetaIdBalance',
        dataType: "json",
        async: false,
        success: function (data) {
            if (Object.keys(data).length) {
                urlStr = 'mdtimestable/balanceListMainDataGridV6';
                colObj = data.header;
                colFreezeObj = data.headerFreeze;

                for(var i = 0; i < colObj.length; i++) {                                
                    if(typeof colObj[i]['formatter'] !== 'undefined') {

                        colObj[i]['formatter'] = formatterMinutToTime;

                    }
                    if(colObj[i]['field'] === 'employeename') {
                        colObj[i]['formatter'] = function(val,row,index){
                            return '<a style="color:#333;font-weight:bold" href="javascript:;" title="'+capitalizeFirstLetter(row.lastname)+' '+capitalizeFirstLetter(row.firstname)+' ('+row.employeecode+')'+'" onclick="tnaSubGridFromDataview(this, \''+row.employeekeyid+'\', \''+$uniqId+'\', \''+index+'\', \''+row.filterstartdate+'\', \''+row.filterenddate+'\', \''+row.employeeid+'\', \''+row.lastname+'\', \''+row.firstname+'\', \''+row.employeecode+'\', \''+row.positionname+'\');"><i class="icon-plus3 font-size-12"></i> '+val+'</a>';
                        }
                    }
                    if(colObj[i]['field'] === 'salary') {
                        colObj[i]['formatter'] = function(val,row,index){
                            return pureNumberFormat(val);
                        }
                    }
                }
                
                colObj = [colObj];

                if (colFreezeObj.length) {
                    for(var i = 0; i < colFreezeObj.length; i++) {                                
                        if(typeof colFreezeObj[i]['formatter'] !== 'undefined') {

                            colFreezeObj[i]['formatter'] = formatterMinutToTime;

                        }
                        if(colFreezeObj[i]['field'] === 'employeename') {
                            colFreezeObj[i]['formatter'] = function(val,row,index){
                                return '<a style="color:#333;font-weight:bold" href="javascript:;" title="'+capitalizeFirstLetter(row.lastname)+' '+capitalizeFirstLetter(row.firstname)+' ('+row.employeecode+')'+'" onclick="tnaSubGridFromDataview(this, \''+row.employeekeyid+'\', \''+$uniqId+'\', \''+index+'\', \''+row.filterstartdate+'\', \''+row.filterenddate+'\', \''+row.employeeid+'\', \''+row.lastname+'\', \''+row.firstname+'\', \''+row.employeecode+'\', \''+row.positionname+'\');"><i class="icon-plus3 font-size-12"></i> '+val+'</a>';
                            }
                        }
                    }

                    colFreezeObj = [colFreezeObj];
                }

                /*colObj = [
                    {field: 'IS_CONFIRMED', title: '', sortable: true, width: '25px', align: 'right', halign: 'center', checkbox:true},
                    {field: 'BALANCE_DATE', title: 'Огноо', sortable: true, halign: 'center', align: 'center', width: '80px', formatter: function(val,row,index){
                        if (val == null) {
                            val = $tnaTimeBalanceForm.find('#startDate').val() + '-' + $tnaTimeBalanceForm.find('#endDate').val();
                        }

                        if(row.IS_USER_CONFIRMED != '0' && row.IS_USER_CONFIRMED != '' && row.IS_USER_CONFIRMED != null) {
                            val = '<i class="fa fa-pencil editLog-'+$uniqId+'" title="Засах үйлдэл хийсэн байна"></i> ' + val;
                        }

                        return val;
                    }},
                    {field: 'EMPLOYEE_NAME', title: 'Овог Нэр (Код)', width: '12%', halign: 'center', sortable: true, formatter: function(val,row,index){
                        return '<a style="color:#333;font-weight:bold" href="javascript:;" title="'+capitalizeFirstLetter(row.LAST_NAME)+' '+capitalizeFirstLetter(row.FIRST_NAME)+' ('+row.EMPLOYEE_CODE+')'+'" onclick="tnaSubGrid(this, \''+row.EMPLOYEE_KEY_ID+'\', \''+$uniqId+'\', \''+index+'\', \''+row.BALANCEDATE+'\', \''+row.EMPLOYEE_ID+'\', \''+row.LAST_NAME+'\', \''+row.FIRST_NAME+'\', \''+row.EMPLOYEE_CODE+'\', \''+row.POSITION_NAME+'\');"><i class="icon-plus3 font-size-12"></i> '+val+'</a>';
                    }},
                    //{field: 'DEPARTMENT_NAME', title: 'Салбар нэгж', width: '18%', halign: 'center', sortable: true},
                    {field: 'POSITION_NAME', title: 'Албан тушаал', width: '13%', halign: 'center', sortable: true},
                    {field: 'PLAN_TIME', title: 'Ажиллах цаг', sortable: true, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'CLEAR_TIME', title: 'Ажилласан<br> цаг', sortable: true, width: '95px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'LATE_TIME', title: 'Хоцорсон цаг', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'EARLY_TIME', title: 'Эрт<br> цаг', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'CAUSE3', title: 'Гадуур<br> ажилласан', sortable: false, width: '95px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'CAUSE8', title: 'Томилолт', sortable: false, width: '80px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'CAUSE20', title: 'Цалинтай чөлөө', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},         
                    {field: 'CAUSE6', title: 'Цалингүй чөлөө', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},               
                    {field: 'CAUSE5', title: 'Өвчтэй цаг', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'CAUSE13', title: 'Тасалсан цаг', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'CAUSE4', title: 'Илүү<br> цаг', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'CAUSE7', title: 'Ээлжийн<br> амралт', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: formatterMinutToTime},
                    {field: 'WFM_STATUS_NAME', title: 'Төлөв', sortable: false, width: '75px', align: 'center', halign: 'center', formatter: function(val,row,index){
                        if (val === null) {
                            return '';
                        }
                        return '<a href="javascript:;" onclick="dataViewTimeBalanceWfmLogGrid(this, \''+row.EMPLOYEE_ID+'\', \''+$uniqId+'\')"><span class="badge label-sm" style="background-color: ' + row.WFM_STATUS_COLOR + '">' + val + '</span></a>';
                    }}            
                ];*/      

            } else {

                $.ajax({
                    type: 'post',
                    url: 'mdtimestable/getCauseTypeHdr',
                    dataType: "json",
                    async: false,
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...', 
                            boxed: true 
                        });
                    },        
                    success: function (data) {
                        if (Object.keys(data).length) {
                            for(var i = 0; i < data.length; i++) {
                                
                                if(data[i].CODE.toUpperCase() === 'WFM_STATUS_NAME') {
            
                                    colObj.push(
                                        {field: 'WFM_STATUS_NAME', title: data[i].NAME, width: data[i].COLUMN_WIDTH, sortable: true, align: 'center', halign: 'center', 
                                            formatter: function(val,row,index){
                                                if (val === null) {
                                                    return '';
                                                }
                                                return '<a href="javascript:;" onclick="dataViewTimeBalanceWfmLogGrid(this, \''+row.EMPLOYEE_ID+'\', \''+$uniqId+'\')"><span class="badge label-sm" style="background-color: ' + row.WFM_STATUS_COLOR + '">' + val + '</span></a>';
                                            }
                                        }              
                                    ); 
            
                                } else {
            
                                    colObj.push({field: data[i].CODE.toUpperCase(), title: data[i].NAME, sortable: true, width: data[i].COLUMN_WIDTH, align: 'center', halign: 'center', formatter: formatterMinutToTime});
            
                                }
                            }
                        }
                        colObj = [colObj];
                        Core.unblockUI();
                    }    
                });                  

            }
        },
        error: function () {
        }
    });    

    var groupNamePath = urlStr === 'mdtimestable/balanceListMainDataGridV6' ? 'departmentname' : 'DEPARTMENT_NAME';

    if (isbalanceTableAutoHeight === '1') {
        $('body #tna-balance-data-grid-' + $uniqId).css('height', ($(window).height() - $('body #tna-balance-data-grid-' + $uniqId).offset().top - 80)+'px');
    }
    $('body #tna-balance-data-grid-' + $uniqId).datagrid({
        view: groupview,
        groupField: groupNamePath,
        groupFormatter:function(value, rows){
            return value + ' - ' + rows.length; 
        },
        fitColumns: true,
//        vrGroupSum: true,
        striped: false,
        method: 'post',
        nowrap: true,
        showFilterBar: true,
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        showFooter: true,
        pageNumber: 1,
        pageSize: tmsParentPageSize,        
        pageList: [10,20,30,40,50,100,200,300,500],
        url: urlStr,
        queryParams: {"params": $tnaTimeBalanceForm.serialize()},
        rowStyler:function(index, row) {
            if (urlStr === 'mdtimestable/balanceListMainDataGridV6') {
                return 'background-color:'+row.rowcolor+';';
            } else {
                return 'background-color:'+row.BACKGROUND_COLOR+';';
            }
        },
        frozenColumns: colFreezeObj,
        columns: colObj,
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
        /*detailFormatter: function(index, row) {
            return '<div style="padding:2px;" class="'+ $uniqId +'" data-children-class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" id="tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-' + index +'"><table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index +'" style="width:100%"></table></div>';
        },*/
        onClickRow: function(index, row) {
            $.uniform.update();
            $('button[data-uniqid="'+ $uniqId +'"]').attr('data-all', 'month').attr('data-view-id', '#tna-balance-data-grid-' + $uniqId);
            $('.context-menu-root').attr('style', 'display:none;');
        },
        /*onExpandRow: function(index, row) {
            $('#tna-subdataGrid-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index).empty().html('<table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index + '"></table>');
            renderMonthBalanceV5SubList($('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + row.EMPLOYEE_KEY_ID +'-'+ index), row, index, $uniqId);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', index);
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },*/
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
            
            /*$('.tna-balance-data-grid-div-' + $uniqId).on('hover', '.datagrid-cell', function() {
                var _this = $(this), _thisColField = _this.parent().attr('field');

                if(_thisColField === 'EMPLOYEE_NAME') {
                    $(".qtip").each(function(i, elm) {
                      $(elm).remove();
                    }).promise().done( function(){
                        _this.parent().qtip({
                            content: {
                                text: _this.children().data('title')
                            },
                            position: {
                                my: 'top center',
                                at: 'bottom center'
                            },
                            style: {
                                classes: 'qtip-blue qtip-rounded vr-qtip'
                            },
                            show: { ready: true, when: false }
                        });
                    });
                }
            });*/            
            
            var searchFooterCheckbox = $('.datagrid-footer table tbody tr', ".tna-balance-data-grid-div-" + $uniqId).find('td[field=employeename]');
            if (searchFooterCheckbox.length) {
                searchFooterCheckbox.find('a').remove();
            }                           

            var searchFooterCheckbox = $('.datagrid-footer table tbody tr', ".tna-balance-data-grid-div-" + $uniqId).find('td[field=EMPLOYEE_NAME]');
            if (searchFooterCheckbox.length) {
                searchFooterCheckbox.find('a').remove();
            }                           

            /*
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("dateInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=BALANCE_DATE]").addClass("text-center");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("timeInit");
            _thisGrid.datagrid("getPanel").children("div.datagrid-view").find(".datagrid-htable").find(".datagrid-filter-row").find("input[name=IN_TIME],input[name=OUT_TIME],input[name=PLAN_TIME],input[name=CLEAR_TIME],input[name=UNCLEAR_TIME],input[name=NIGHT_TIME],input[name=DEFFERENCE_TIME]").addClass("text-center");
            */
            
            Core.initInputType(panelView);  
            $('.mergeCelltnaEmployeeBalance').show();
            _thisGrid.datagrid('resize'); 
        }
    });
    
    $('body #tna-balance-data-grid-' + $uniqId).datagrid('enableFilter');
}

function formatterMinutToTime(val,row,index) {
    return minutToTime(val);
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
        url: 'mdtimestable/balanceListMainDataGridNew',
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
        url: 'mdtimestable/balanceListMainDataGridNew',
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
        url: 'mdtimestable/subBalanceListMainDataGridNew/',
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
            
            /*tnaRenderSidebar(row, index, $uniqId);*/
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
        url: 'mdtimestable/balanceListMainDataGridDepartmentGroup',
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
        url: 'mdtimestable/balanceListMainDataGridNew',
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

//<editor-fold defaultstate="collapsed" desc="-- Мэдэхгүй --">

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
        url: 'mdtimestable/balanceListMainDataGridMod',
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
        url: 'mdtimestable/subBalanceListMainDataGridMod/',
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
            
            /*tnaRenderSidebar(row, index, $uniqId);*/
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

function renderMonthBalanceV5SubList(ddv, parentRow, parentIndex, $uniqId, pparentRow, pparentIndex, pddv) {
    var timeFields = [
        {field: 'IS_CONFIRMED', title: '', sortable: true, align: 'right', halign: 'center', checkbox:true},
        {field: 'BALANCE_DATE1', title: 'Огноо', sortable: true, halign: 'center', align: 'left', styler: function(v, r, i) {return 'font-weight: bold';}, formatter: function(val,row,index) {
            if(row.IS_USER_CONFIRMED != '0' && row.IS_USER_CONFIRMED != '' && row.IS_USER_CONFIRMED != null) {
                return '<a href="javascript:;" onclick="tnaBalanceDrillProcess(this, \''+row.TIME_BALANCE_HDR_ID+'\');"><i class="fa fa-pencil editLog-'+$uniqId+'" title="Засах үйлдэл хийсэн байна"></i> ' + val + '</a>';
            }                
            return '<a href="javascript:;" onclick="tnaBalanceDrillProcess(this, \''+row.TIME_BALANCE_HDR_ID+'\');"><i class="fa fa-external-link-square"></i> ' + val + '</a>';
        }}
    ];
    
    $.ajax({
        type: 'post',
        url: 'mdtimestable/getCauseType',
        dataType: "json",
        async: false,
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...', 
                boxed: true 
            });
        },        
        success: function (data) {
            if (Object.keys(data).length) {
                for(var i = 0; i < data.length; i++) {
                    
                    if(data[i].CODE.toUpperCase() === 'STATUS_TEXT') {
                        timeFields.push(
                            {field: 'STATUS_TEXT', title: 'Төлөв', sortable: false, align: 'center', halign: 'center', 
                                formatter: function(val, row, index) {
                                    return onclickfnc(val, row, $uniqId, '1');
                                }, styler: function(val, row, index) {
                                    return 'color:'+row.FONT_COLOR+'; ';
                                }
                            }              
                        ); 

                    } else if (data[i].CODE.toUpperCase() === 'IN_TIME') {

                        timeFields.push({
                            field: data[i].CODE.toUpperCase(),
                            title: data[i].NAME,
                            sortable: false,
                            halign: 'center',
                            align: 'center',
                            width: '70',
                            formatter: function(val,row,index) {
                                if (tmsCustomerCode === 'gov') {
                                    return '<a href="javascript:;" class="io-time" title="'+row.IN_TIME_LONG+'">' + val + '</a>';
                                } else {
                                    return '<a href="javascript:;" class="io-time" title="'+row.IN_TIME_LONG+'" onclick="balanceGoogleMapView('+row.EMPLOYEE_ID+', \'' + row.BALANCE_DATE + ' ' + row.IN_TIME + '\')">' + val + '</a>';
                                }
                            }
                        });

                    } else if (data[i].CODE.toUpperCase() === 'PLAN_TIME') {                        
                        timeFields.push({
                            field: data[i].CODE.toUpperCase(),
                            title: data[i].NAME,
                            sortable: false,
                            halign: 'center',
                            align: 'center',
                            width: '85'
                        });
                    } else if (data[i].CODE.toUpperCase() === 'OUT_TIME') {

                        timeFields.push({
                            field: data[i].CODE.toUpperCase(),
                            title: data[i].NAME,
                            sortable: false,
                            halign: 'center',
                            align: 'center',
                            width: '70',
                            formatter: function(val,row,index) {
                                if (tmsCustomerCode === 'gov') {
                                    return '<a href="javascript:;" class="io-time" title="'+row.OUT_TIME_LONG+'">' + val + '</a>';
                                } else {
                                    return '<a href="javascript:;" class="io-time" title="'+row.OUT_TIME_LONG+'" onclick="balanceGoogleMapView('+row.EMPLOYEE_ID+', \'' + row.BALANCE_DATE + ' ' + row.OUT_TIME + '\')">' + val + '</a>';
                                }
                            }
                        });

                    } else {

                        timeFields.push({
                            field: data[i].CODE.toUpperCase(),
                            title: data[i].NAME,
                            sortable: false,
                            halign: 'center',
                            align: 'center',
                            width: '85',
                            formatter: function(val){
                                var className = '';
                                if(val != 0 && val !== null)
                                    className = 'io-time';
                                return '<span class="'+className+'">'+minutToTime(val)+'</span>';
                            }
                        });                        
                    }
                }
            }
            Core.unblockUI();
        }    
    });       
    
    timeFields.push(
        {field: 'IS_LOCK', title: '', sortable: false, align: 'center', halign: 'center', width: '35',
            formatter: function(val, row, index) {
                var isLockHtml = '<i class="fa fa-unlock" style="color:#000000b8" title="Түгжигдээгүй"></i>';
                if(val == '1') {
                    isLockHtml = '<i class="fa fa-lock" style="color:#000000b8" title="Түгжсэн"></i>';
                }
                return isLockHtml;
            }
        }              
    );        
    
    ddv.datagrid({
        view: horizonscrollview,
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize(), balanceDate: parentRow.BALANCEDATE, employeeId: parentRow.EMPLOYEE_ID, employeeKeyId: (typeof parentRow.EMPLOYEE_KEY_ID !== 'undefined' ? parentRow.EMPLOYEE_KEY_ID : '')},
        url: 'mdtimestable/subBalanceListMainDataGridV5',
        fitColumns: false,
        striped: false,
        method: 'post',
        nowrap: true,
        pagination: true,
        rownumbers: true,
        //height:tmsPageSubGridHeidght,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true, 
        selectOnCheck: true, 
        remoteFilter: true,
        height:'auto',
        filterDelay: 10000000000,
        pagePosition: 'bottom',
        pageNumber: 1,
        showFooter: true,
        pageSize: tmsPageSize,
        pageList: [10,15,20,25,30,35,40,50,60,100,200],
        rowStyler:function(index,row) {
            return 'background-color: '+ row.BACKGROUND_COLOR +';';
        },
        columns: [timeFields],
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
            $.uniform.update();
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
                        var rows = ddv.datagrid('getSelections');
                        if (rows.length > 0) {
                            
                            tnaBalanceDrillProcess(opt.$trigger, 'rows', rows, ddv);
                            
                        } else {
                            var dialogName = '#dialog-timeBalanceMultiChanage';
                            if (!$(dialogName).length) {
                                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            $(dialogName).empty().append(MESSSAGE_NO_SELECT_CONFIRM_OR_CANCEL_ROW);
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
                    if (key === 'removeMulti') {
                        var rows = ddv.datagrid('getSelections');
                        var dialogName = '#dialog-timeBalanceMultiRemove';
                        if (!$(dialogName).length) {
                            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                        }                        
                        
                        if (rows.length > 0) {
                            $(dialogName).empty().html('Статусыг цуцлахдаа итгэлтэй байна уу');
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
                                    {text: plang.get('yes_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            multiRemoveBalance(rows, ddv, $uniqId);
                                            $(dialogName).dialog('close');
                                        }
                                    },
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
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
                    if (key === 'send') {
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
                    if (key === 'lock') {
                        //if (typeof _isAdminApproved !== 'undefined' && _isAdminApproved === '1') {
                            var rows = ddv.datagrid('getSelections');
                            if (rows.length > 0) {
                                callIsLockBalanceDialog(rows, ddv, $uniqId);
                            } else {
                                var dialogName = '#dialog-timeBalance-lock';
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
                        /*} else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Та олноор түгжих эрхгүй байна.',
                                type: 'warning',
                                sticker: false
                            });
                        }*/
                    }                    
                },
                items: {
                    "remove": {name: "Олноор тохируулах", icon: "cogs"},
                    "removeMulti": {name: "Олноор устгах", icon: "trash"},
                    //"send": {name: "Мэйл илгээх", icon: "send"}
//                    "lock": {name: "Түгжих", icon: "key"}
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
            
            var searchFooterCheckbox = $('.datagrid-footer table tbody tr', "#dialog-tna-subgrid").find('td[field=BALANCE_DATE1]');
            if (searchFooterCheckbox.length) {
                searchFooterCheckbox.find('a').remove();
            }               
            var searchFooterCheckbox = $('.datagrid-footer table tbody tr', "#dialog-tna-subgrid").find('td[field=STATUS_TEXT]');
            if (searchFooterCheckbox.length) {
                searchFooterCheckbox.find('a').remove();
            }               
            var searchFooterIsLock = $('.datagrid-footer table tbody tr', "#dialog-tna-subgrid").find('td[field=IS_LOCK]');
            if (searchFooterIsLock.length) {
                searchFooterIsLock.find('i').remove();
            }               
                         
            if (typeof pparentIndex != 'undefined') {
                pddv.datagrid('fixDetailRowHeight', parentIndex);
                pddv.datagrid('resize'); 
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', pparentIndex);
            } else {
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', parentIndex);
            }
            
            ddv.datagrid('resize', {height: $("#dialog-tna-subgrid").height() - 15 + 'px'});
            setTimeout(function() {
                $(window).trigger("resize");
            }, 1);
        }
    });
    ddv.datagrid('enableFilter');
}

function renderMergeBalanceV2SubList($uniqId) {
    var dg = $('body #tna-balance-data-grid-' + $uniqId);
    dg.datagrid({
        view: horizonscrollview,
        queryParams: {"params": $("body #tnaTimeBalanceForm" + $uniqId).serialize()},
        url: 'mdtimestable/subMergeBalanceListMainDataGridV2',
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
        pageList: [10,20,30,40,50,60,100,200],
        rowStyler:function(index,row) {
            return 'background-color: '+ row.STATUS_COLOR +';';
        },
        columns: [[
            {field: 'IS_CONFIRMED', title: '', sortable: true, align: 'right', halign: 'center', checkbox:true},
            {field: 'EMPLOYEE_NAME', title: 'Овог Нэр (Код)', sortable: true, halign: 'center', align: 'center',
                formatter:function(value){
                    var opts = dg.datagrid('options');
                    opts.rowHeight = 32;
                    var style = 'height:'+opts.rowHeight+'px;line-height:12px;';
                    return '<div style="'+style+'">'+value+'</div>';
                }
            },
            {field: 'BALANCE_DATE1', title: 'Огноо', sortable: true, halign: 'center', align: 'center'},
            {field: 'IN_TIME', title: 'Ирсэн', sortable: false, halign: 'center', align: 'center'},
            {field: 'OUT_TIME', title: 'Явсан', sortable: false, align: 'center', halign: 'center'},
            {field: 'PLAN_TIME', title: 'Төлөвлөсөн цаг', sortable: false, align: 'center', halign: 'center'},
            {field: 'CLEAR_TIME', title: 'Ажилласан цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'UNCLEAR_TIME', title: 'Бохир цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index) {
                return minutToTime(val);
            }},
            {field: 'NIGHT_TIME', title: 'Шөнийн цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'LATE_TIME', title: 'Хоцорсон цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'EARLY_TIME', title: 'Эрт явсан цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'DEFFERENCE_TIME', title: 'Зөрүү цаг', sortable: false, align: 'center', halign: 'center', formatter: function(val,row,index){
                return minutToTime(val);
            }},
            {field: 'STATUS_TEXT', title: 'Төлөв', sortable: false, align: 'center', halign: 'center', 
                formatter: function(val, row, index) {
                    return onclickfnc(val, row, $uniqId, '1');
                }, styler: function(val, row, index) {
                    return 'color:'+row.FONT_COLOR+'; ';
                }
            }
        ]],
        onCheckAll: function() {
            $.uniform.update();
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val(dg.attr('class'));
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
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('input[id="selected-datagrid-'+ $uniqId +'"]').val(dg.attr('class'));
            $('.context-menu-root').attr('style', 'display:none;');
            
            var _thisGrid = $(this);
            var splitclass = (_thisGrid.attr('class')).split(" ");
            var closestDivid = _thisGrid.closest("div[data-children-class='"+ splitclass[0] +"']");
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-row-error');
            
            $("#tnaTimeBalanceWindow" + $uniqId).find('.datagrid-body tr').removeClass('datagrid-last-clicked-row');
            closestDivid.find('.datagrid-body tr[datagrid-row-index="'+index+'"]').addClass('datagrid-last-clicked-row');
            
            $("#currentSelectedRowIndex-" + $uniqId, "#tnaTimeBalanceWindow" + $uniqId).val(index);
            
            /*tnaRenderSidebar(row, index, $uniqId);*/
            $.uniform.update();
            _thisGrid.datagrid('resize'); 
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        },
        /*
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
                        var rows = dg.datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiChangeBalance(selectedBalance, dg, $uniqId);
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
                    if (key === 'removeMulti') {
                        var rows = dg.datagrid('getSelections');
                        var dialogName = '#dialog-timeBalanceMultiRemove';
                        if (!$(dialogName).length) {
                            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                        }                        
                        
                        if (rows.length > 0) {
                            $(dialogName).empty().html('Статусыг цуцлахдаа итгэлтэй байна уу');
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
                                    {text: plang.get('yes_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            multiRemoveBalance(rows, dg, $uniqId);
                                            $(dialogName).dialog('close');
                                        }
                                    },
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $(dialogName).dialog('close');
                                        }
                                    }
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
                        var rows = dg.datagrid('getSelections');
                        if (rows.length > 0) {
                            for (var i = 0; i < rows.length; i++) {
                                selectedBalance.push(rows[i]);
                            }
                            multiSendBalance(selectedBalance, dg, $uniqId);
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
                    "removeMulti": {name: "Олноор устгах", icon: "trash"},
                    "send": {name: "Мэйл илгээх", icon: "send"}
                }
            });
        },*/
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
            
            _thisGrid.datagrid("autoMergeCells", ['EMPLOYEE_NAME', 'DEPARTMENT_NAME', 'POSITION_NAME']);
            
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
            /*if (typeof pparentIndex != 'undefined') {
                dg.datagrid('fixDetailRowHeight', parentIndex);
                dg.datagrid('resize'); 
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', pparentIndex);
            } else {
                $('body #tna-balance-data-grid-' + $uniqId).datagrid('fixDetailRowHeight', parentIndex);
            }*/
            
            $('body #tna-balance-data-grid-' + $uniqId).datagrid('resize');
        }
    });
    dg.datagrid('enableFilter');
}

//</editor-fold>

function balanceGoogleMapView(empId, date) {
    var dialogName = '#dialog-timeBalanceGoogleMapView';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'mdtimestable/balanceGoogleMapView',
        data: {
            'empId': empId,
            'date': date
        },
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $(dialogName).empty().append(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: '800',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
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
    });
}
function tnaBalanceDrillProcess(elem, balanceHdrId, rows, dataGrid) {
    var $dialogName = 'dialog-businessprocess-1533978626221';
    if (!$('#' + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), fillDataParams = '', isMulti = true, 
        saveUrl = 'mdtimestable/saveBalanceByProcess';
    
    if (balanceHdrId != 'rows') {
        fillDataParams = 'id='+balanceHdrId+'&defaultGetPf=1';
        isMulti = false;
        saveUrl = 'mdwebservice/runProcess';
    }
    
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: {
            metaDataId: '1533978626221', 
            isDialog: true, 
            isSystemMeta: false, 
            fillDataParams: fillDataParams,  
            callerType: 'tnaBalance', 
            openParams: '{"callerType":"tnaBalance"}'
        },
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...', 
                boxed: true
            });
        },
        success: function (data) {

            $dialog.empty().append(data.Html);

            var $processForm = $dialog.find('#wsForm'), processUniqId = $processForm.parent().attr('data-bp-uniq-id');
            var buttons = [
                {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                    if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                        $processForm.validate({ 
                            ignore: '', 
                            highlight: function(element) {
                                $(element).addClass('error');
                                $(element).parent().addClass('error');
                                if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                    $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                        var tabId = $(tab).attr('id');
                                        $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                    });
                                }
                            },
                            unhighlight: function(element) {
                                $(element).removeClass('error');
                                $(element).parent().removeClass('error');
                            },
                            errorPlacement: function(){} 
                        });

                        var isValidPattern = initBusinessProcessMaskEvent($processForm);

                        if ($processForm.valid() && isValidPattern.length === 0) {
                            $processForm.ajaxSubmit({
                                type: 'post',
                                url: saveUrl,
                                dataType: 'json',
                                beforeSubmit: function (formData, jqForm, options) {
                                    if (isMulti) {
                                        formData.push({name: 'selectedRows', value: JSON.stringify(rows)});
                                    }
                                },
                                beforeSend: function () {
                                    Core.blockUI({
                                        boxed: true, 
                                        message: 'Түр хүлээнэ үү'
                                    });
                                },
                                success: function (responseData) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: responseData.status,
                                        text: responseData.message,
                                        type: responseData.status, 
                                        sticker: false
                                    });
                                        
                                    if (responseData.status === 'success') {
                                        if (isMulti) {
                                            dataGrid.datagrid('reload');
                                        } else {
                                            $(elem).closest('div.datagrid-view').children('table').datagrid('reload');
                                        }
                                        $dialog.dialog('close');
                                    } 
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                    Core.unblockUI();
                                }
                            });
                        }
                    }    
                }},
                {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ];

            var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

            if (data.isDialogSize === 'auto') {
                dialogWidth = 1200;
                dialogHeight = 'auto';
            }

            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: dialogWidth,
                height: dialogHeight,
                modal: true,
                closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: buttons
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
            if (data.dialogSize === 'fullscreen') {
                $dialog.dialogExtend('maximize');
            }
            $dialog.dialog('open');
        },
        error: function () {
            alert('Error');
            Core.unblockUI();
        }
    }).done(function () {
        Core.initBPAjax($dialog);
        Core.unblockUI();
    });
}

function changeWfmStatusTime(elem) {
    var $dialogName = 'dialog-changeWfmStatusTime', 
        html ='',
        $this = $(elem);
        
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }    

    var uniqId = $this.closest('.col-md-12').find('button.downloadDataIO').attr('data-uniqid');
    var employeesString = '', employees = [];
    
    try {
        employees = $('body #tna-balance-data-grid-' + uniqId).datagrid('getSelections');
    } catch(e){
    }
    
    if(employees.length > 0) {
        for(var ii = 0; ii < employees.length; ii++)
            employeesString += employees[ii].employeeid + ',';
    }    

    html = '<div class="row w-100"><form id="changeWfmStatusTimeForm" class="form-horizontal w-100" method="post"><div class="col-md-12"><div class="form-group row fom-row"><label class="col-form-label col-md-3">Сонгосон төлөв:</label><div class="col-md-8 pl0"><p class="form-control-plaintext"><span class="badge" style="background-color: ' + $this.data('color') + '">' + $this.text() + '</span></p></div></div><div class="form-group row fom-row"><label class="col-form-label col-md-3" style="margin-top:-8px">Бүгд:</label><div class="col-md-8 pl0"><div="form-control"><input type="checkbox" value="1" name="isTotal"></div></div><textarea name="description" id="description" placeholder="Тайлбар" class="form-control" style="height: 65px"></textarea></div></form><div class="clearfix w-100"></div></div>';

    $("#" + $dialogName).empty().append(html);
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Төлөв өөрчлөх',
        width: 800,
        height: 'auto',
        modal: true,
        close: function () {
            $("#" + $dialogName).empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                var $form = $this.closest('form');
                var $wfmParams = {
                    refId: $this.data('refid'),
                    wfmStatusId: $this.attr('id'),
                    wfmStatusCode: $this.data('code'),
                    description: $('#changeWfmStatusTimeForm').find('#description').val(),
                    startDate: $form.find('input[name="startDate"]').val(),
                    endDate: $form.find('input[name="endDate"]').val(),
                    isTotal: $('#changeWfmStatusTimeForm').find('input[name="isTotal"]').is(':checked') ? '1' : '0',
                    employeesString: employeesString,
                    wfmStatusText: $this.text()
                };

                $.ajax({
                    type: 'post',
                    url: 'mdtimestable/saveWfmStatusData',
                    data: $wfmParams,
                    dataType: "html",
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        PNotify.removeAll();

                        if (!data.length) {
                            new PNotify({
                                title: 'Success',
                                text: 'Төлөв амжилттай өөрчлөгдлөө.',
                                type: 'success',
                                sticker: false
                            });
                            $('body #tna-balance-data-grid-' + uniqId).datagrid('reload');
                        } else {
                            var $dialogName2 = 'dialog-confirm';
                            if (!$("#" + $dialogName2).length) {
                                $('<div id="' + $dialogName2 + '"></div>').appendTo('body');
                            }
                        
                            $("#" + $dialogName2).empty().append(data);
                            $("#" + $dialogName2).dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'Info',
                                width: 500,
                                height: "auto",
                                modal: true,
                                close: function () {
                                    $("#" + $dialogName2).empty().dialog('close');
                                },
                                buttons: [
                                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                        $wfmParams.isApprove = '1';
                                        $.ajax({
                                            type: 'post',
                                            url: 'mdtimestable/saveWfmStatusData',
                                            data: $wfmParams,
                                            dataType: "html",
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    animate: true
                                                });
                                            },     
                                            success: function (data) {                
                                                new PNotify({
                                                    title: 'Success',
                                                    text: 'Төлөв амжилттай өөрчлөгдлөө.',
                                                    type: 'success',
                                                    sticker: false
                                                });
                                                $('body #tna-balance-data-grid-' + uniqId).datagrid('reload');                                                
                                                Core.unblockUI();
                                                $("#" + $dialogName2).dialog('close');
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
                                        $("#" + $dialogName2).dialog('close');
                                    }}
                                ]
                            });
                            $("#" + $dialogName2).dialog('open');                                     
                        }

                        Core.unblockUI();
                        $("#" + $dialogName).dialog('close');
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
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');    
}

function dataViewTimeBalanceWfmLogGrid(elem, empId, uniqId) {
    var $this = $(elem), defaultCriteriaParams = {};
    defaultCriteriaParams.employeeId = empId;
    defaultCriteriaParams.filterStartDate = $('#tnaTimeBalanceForm'+uniqId).find('#startDate').val();
    defaultCriteriaParams.filterEndDate = $('#tnaTimeBalanceForm'+uniqId).find('#endDate').val();
    var $dialogName = 'dialog-dataview-salary-wfmlog';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdobject/dataview/1546957164905462/0/json',
        data: {
            uriParams: JSON.stringify(defaultCriteriaParams)
        },
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                boxed: true, 
                message: 'Loading...'
            });
        },
        success: function (data) {
            
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1100,
                height: $(window).height() - 90,
                modal: true,
                position: {my:'top', at:'top+50'},
                closeOnEscape: isCloseOnEscape, 
                close: function () {
                    $dialog.empty().dialog('close');
                },
                buttons: [
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert('Error');
        }
    }).done(function () {
        Core.initDVAjax($dialog);
    });    
}
function tnaSubGrid(elem, empKey, $uniqId, index, balanceDate, employeeId, lastName, firstName, employeeCode, positionName) {
    var $dialogName = 'dialog-tna-subgrid';
    if (!$('#' + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $dialog.empty().append('<div style="padding:2px;" class="jeasyuiTheme3 '+ $uniqId +'" data-children-class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + empKey +'-'+ index +'" id="tna-subdataGrid-'+ $uniqId + '-' + empKey +'-' + index +'"><table class="tna-balance-data-subgrid-new-'+ $uniqId + '-' + empKey +'-'+ index +'" style="width:100%;"></table></div>').promise().done(function () {
                    
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: capitalizeFirstLetter(lastName) + ' ' + capitalizeFirstLetter(firstName) + ' (' + employeeCode + ') - ' + positionName,
            modal: true,
            closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
            open: function () {
                var $subDataGrid = $('table.tna-balance-data-subgrid-new-'+ $uniqId + '-' + empKey +'-'+ index);
                renderMonthBalanceV5SubList($subDataGrid, {BALANCEDATE: balanceDate, EMPLOYEE_ID: employeeId, EMPLOYEE_KEY_ID: empKey}, index, $uniqId);                

                Core.initDVAjax($dialog);
            }, 
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
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
        $dialog.dialogExtend('maximize');
        $dialog.dialog('open'); 
    });
}

function tnaSubGridFromDataview(elem, empKey, $uniqId, index, filterStartDate, filterEndDate, employeeId, lastName, firstName, employeeCode, positionName) {
    var $dialogName = 'dialog-tnaSubGrid-dataview';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" style="padding: 0px 25px 0px 25px;"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
            
    $.ajax({
        type: 'post',
        url: 'mdobject/dataValueViewer',
        data: {
            metaDataId: '1584000186529228', 
            viewType: 'detail', 
            ignorePermission: 1, 
            dataGridDefaultHeight: $(window).height() - 110,
            uriParams: '{"employeeKeyId": "'+empKey+'","employeeId": "'+employeeId+'","filterStartDate": "'+filterStartDate+'","filterEndDate": "'+filterEndDate+'"}'
        },
        beforeSend: function() { Core.blockUI({animate: true}); },
        success: function(dataHtml) {
            
            $dialog.empty().append('<div class="row" id="object-value-list-1584000186529228">' + dataHtml + '</div>');
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                dialogClass: 'no-padding-dialog dialog-overflow-hidden',
                title: capitalizeFirstLetter(lastName) + ' ' + capitalizeFirstLetter(firstName) + ' (' + employeeCode + ') - ' + positionName,
                width: 1000,
                height: 'auto',
                modal: false,
                position: {my: 'top', at: 'top+37'},
                open: function() {
                    
                    // var $processButtons = $dialog.find('.dv-process-buttons > .btn-group > a.btn');
                    // var $filter = $dialog.find('.col-md-12.text-right.pr0');
                    // if ($processButtons.length) {
                    //     var $processClone = $processButtons.clone(true);
                    //     $filter.prepend($processClone);
                    // }
                    
                    // $dialog.find('.top-sidebar-content:eq(0)').attr('style', 'padding-left: 15px !important');
                    $dialog.find('.object-height-row2-minus-1584000186529228').remove();
                    // $dialog.find('.card-collapse').empty();
                    // $filter.removeClass('col-md-12').addClass('float-right').css('margin-top', '-25px');
                    // $dialog.find('.mb5.pb5').removeClass('mb5 pb5');
                    // $dialog.find('.xs-form.top-sidebar-content').css('padding-left', '').removeClass('mb10');
                    
                    // setTimeout(function() {
                    //     $dialog.find('input[type="text"]:eq(0)').focus();
                    // }, 5);
                }, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                }
            }).dialogExtend({
                "closable": true,
                "maximizable": false, 
                "minimizable": true,
                "collapsable": true,
                "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });
            
            $dialog.dialogExtend('maximize');
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); Core.unblockUI(); }
    });
}
