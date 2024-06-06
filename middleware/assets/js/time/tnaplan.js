//  Global variable defination
var MESSSAGE_WARNING_TITLE = 'Анхааруулга';
var MESSSAGE_SESSION_FULL = 'Өөрчлөлт хийх хязгаар хэтэрсэн байна.';
var MESSSAGE_STATUS_ERROR = 'Удирдлага баталгаажуулсан байна. Өөрчлөх шаардлагатай бол удирдлагаар цуцлуулна уу';
var MESSSAGE_VERIFY_NO_CELL = 'Батагаажуулах нүд сонгоогүй байна';
var MESSSAGE_IS_LOCK = 'Түгжсэн нүдэнд өөрчлөлт орох боломжгүй. Түгжсэн ажилтнаар түгжээг гаргах';

var tnaTimeEmployeePlanFormId = "#tnaTimeEmployeePlanForm";
var tnaTimeEmployeePlanWindowId = '#tnaTimeEmployeePlanWindow';
var currentDate, currentUserId;
var tnaTimeEmployeePlanData = [];

var cntrlIsPressed = false;
var dyn = [];

var TNA_TEMPED = [];
var TNA_NEEDLE = [];

var TNA_NEEDLE_CHECKED_TOTAL_TIME = [];

var TEMP_DEPARTMENT_TIME  = 0;
var TEMP_EMPLOYEE_KEY_ID  = 0;
var TEMP_EMPLOYEE_ID      = 0;
var TEMP_DEPARTMENT_ID    = 0;
var TEMP_DEPARTMENT_TT    = 0;

var planyear = 0;
var WFM_STATUS_ID = 0;
var REAL_WFM_STATUS_ID = 1449651227727954;
var planmonth = 0;

var DAY_ = 'DAY_';
var isWorkingDays = false;
var isPositionWorkingDays = false;
var rowData, tbl, selectedCell, selectedCellCount, allCell = '';

$(function () {
    
    $('body').on('change', '#departmentId', function() {
        $.ajax({
            type: 'post',
            url: 'mdtime/getPositionList',
            dataType: "json",
            data: {"param": $(this).val()},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $('#positionId').empty();
                $('#positionId').append("<option/>", {value: '', text: '- Сонгох -'});
                for(var i = 0; i < data.length; i++){
                    var dlist = $("<option/>", {value: data[i].POSITION_ID, text: data[i].POSITION_NAME});
                    $('#positionId').append(dlist);
                }
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initSelect2();
        });
    });
    
    $('body').on('change', '#positionId', function() {
        var $thisVal = $(this).val();
        $('.positionGroup').hide();
        if ($thisVal.length == 0) {
            $('#positionGroupId').empty();
            return;
        }
        $.ajax({
            type: 'post',
            url: 'mdtime/getGroupList',
            dataType: "json",
            data: {"param": $thisVal},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                if (data) {
                    $('#positionGroupId').empty();
                    $('#positionGroupId').append("<option/>", {value: '', text: '- Сонгох -'});
                    if (data.length !== 0) {
                        for(var i = 0; i < data.length; i++) {
                            var dlist = $("<option/>", {value: data[i].ID, text: data[i].POSITION_NAME + ' ' + data[i].NAME});
                            $('#positionGroupId').append(dlist);
                        }
                        $('.positionGroup').show();
                    }
                }
                Core.unblockUI();
                return;
                
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initSelect2();
        });
    });
    
    $("body").on("click", tnaTimeEmployeePlanWindowId + " .balanceReload", function (e) {
        e.stopImmediatePropagation();
        var timePlanUniqId = $(this).attr('data-view-id');
        
        getEmployeePlanList(timePlanUniqId);
        getEmptySideBar(timePlanUniqId);
        
        $('body').find('#tnaTimeEmployeePlanWindow').find('.stoggler').trigger('click');
    });
    
    $("body").on('change', tnaTimeEmployeePlanWindowId + ' #isWorkingDays', function(e) {
        e.stopImmediatePropagation();
        var _this = $(this);
        
        var tbl = $("#tnaBalanceGrid").find("table");
        var tblHeader = tbl.find('tr.tablesorter-headerRow th');
        var tblFilterHeader = tbl.find('tr.tablesorter-ignoreRow td');
        var _tblColSpan = tbl.find('tbody.tablesorter-no-sort tr').find('td.departmentTitle');
        var $bcolspan = _tblColSpan.attr('colspan');
        isWorkingDays = false;
        if (_this.is(':checked')) {
            isWorkingDays = true;
            $('#onlyWorkingDay').val('1');
            tbl.find('td.weekday').removeClass('tbl-cell').hide();
            tbl.find('th.weekday').removeClass('tbl-cell').hide();
            var $colspan = 1;
            for( var i = 0; i <= tblHeader.length; i++) {
                if ($(tblHeader[i]).attr('data-isworking') == '7' || $(tblHeader[i]).attr('data-isworking') == '6') {
                    $(tblFilterHeader[i]).hide();
                    $colspan++;
                }
            }
            _tblColSpan.attr('colspan', parseInt($bcolspan) - parseInt($colspan));
        } else {
            $('#onlyWorkingDay').val('0');
            tbl.find('td.weekday').addClass('tbl-cell').show();
            tbl.find('th.weekday').addClass('tbl-cell').show();
            $("#tnaBalanceGrid").find(".tablesorter-filter-row").show();
            var $colspan = 1;
            for( var i = 0; i <= tblHeader.length; i++) {
                if ($(tblHeader[i]).attr('data-isworking') == '7' || $(tblHeader[i]).attr('data-isworking') == '6') {
                    $(tblFilterHeader[i]).show();
                    $colspan++;
                }
            }
            _tblColSpan.attr('colspan',  parseInt($bcolspan) + parseInt($colspan));
        }
    });
    
    $("body").on('change', tnaTimeEmployeePlanWindowId + ' #employeeCurrentMonthTime', function(e) {
        e.stopImmediatePropagation();
        var thisval = $(this).val();
        var DEPARTMENT_TOTAL_TIME = $('#departmentCurrentMonthTime').val();
        var SUM_DEPARTMENT_TIME = 0;
        $('#employeeCurrentMonthTime').removeClass('error');

        if (parseFloat(TEMP_DEPARTMENT_TIME) < parseFloat(thisval)) {
          PNotify.removeAll(); 
          new PNotify({
              title: 'Warning',
              text: 'Алба хэлтэсийн нийт фонт цагаас хэтэрсэн байна!',
              type: 'warning',
              sticker: false
          });

          $('#employeeCurrentMonthTime').val('');
          $('#employeeCurrentMonthTime').val('');
          $('#employeeCurrentMonthTime').addClass('error');
          return false;
        }
        else {
          $.each(TNA_NEEDLE, function(nKey, nRow) {
            if (TEMP_DEPARTMENT_ID == nRow.DEPARTMENT_ID  &&
                TEMP_EMPLOYEE_KEY_ID === nRow.EMPLOYEE_KEY_ID &&
                TEMP_EMPLOYEE_ID === nRow.EMPLOYEE_ID) {
              nRow.DEPARTMENT_TIME = thisval;
              $('#employeeCurrentMonthTime').val(nRow.DEPARTMENT_TIME);
            }
          });
          $.each(TNA_TEMPED, function(nKey, nRow) {
            if (TEMP_DEPARTMENT_ID == nRow.DEPARTMENT_ID &&
                TEMP_EMPLOYEE_KEY_ID === nRow.EMPLOYEE_KEY_ID &&
                TEMP_EMPLOYEE_ID === nRow.EMPLOYEE_ID) {
              nRow.DP_TEMP_TIME = thisval;
            }
          });
        }


        $.each(TNA_NEEDLE, function(nKey, nRow) {
          if (TEMP_DEPARTMENT_ID == nRow.DEPARTMENT_ID) {
            if (!isNaN(parseFloat(nRow.DEPARTMENT_TIME))) {
              SUM_DEPARTMENT_TIME = SUM_DEPARTMENT_TIME + parseFloat(nRow.DEPARTMENT_TIME);
            }
          }
        });

        if (SUM_DEPARTMENT_TIME > DEPARTMENT_TOTAL_TIME) {
          PNotify.removeAll(); 
          new PNotify({
              title: 'Warning',
              text: 'Алба хэлтэсийн нийт фонт цагаас хэтэрсэн байна!',
              type: 'warning',
              sticker: false
          });

          $('#employeeCurrentMonthTime').val('');
          $('#employeeCurrentMonthTime').val('');
          $('#employeeCurrentMonthTime').addClass('error');

          $.each(TNA_NEEDLE, function(nKey, nRow) {
            if (TEMP_DEPARTMENT_ID == nRow.DEPARTMENT_ID &&
                TEMP_EMPLOYEE_KEY_ID === nRow.EMPLOYEE_KEY_ID &&
                TEMP_EMPLOYEE_ID === nRow.EMPLOYEE_ID) {
              nRow.DEPARTMENT_TIME = 0;
            }
          });

          $.each(TNA_TEMPED, function(nKey, nRow) {
            if (TEMP_DEPARTMENT_ID == nRow.DEPARTMENT_ID &&
                TEMP_EMPLOYEE_KEY_ID === nRow.EMPLOYEE_KEY_ID &&
                TEMP_EMPLOYEE_ID === nRow.EMPLOYEE_ID) {
              nRow.DP_TEMP_TIME = 0;
            }
          });
          return false;
        }

        balanceTime();
      
    });
    
    $("body").on('change', tnaTimeEmployeePlanWindowId + ' #isPositionWorkingDays', function(e) {
        e.stopImmediatePropagation();
        PNotify.removeAll();
        if(userSessionIsFull()) {
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_SESSION_FULL,
                type: 'warning',
                sticker: false
            });
        } else {
            var _this = $(this);
            if (_this.is(':checked')) {
                var status = $(tnaTimeEmployeePlanWindowId).find('.statusApproveBtn');
                if (isWorkFlowStatus(status.attr('data-status-code'))) {
                    getEmployeePositionPlanList(1); /*Insert*/
                } else {/*-*/
                    new PNotify({
                        title: MESSSAGE_WARNING_TITLE,
                        text: MESSSAGE_STATUS_ERROR,
                        type: 'warning',
                        sticker: false
                    });
                }
            } else {
                var status = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn');
                if (isWorkFlowStatus(status.attr('data-status-code'))) {
                    getEmployeePositionPlanList(0); /*Delete*/
                } else {
                    new PNotify({
                        title: MESSSAGE_WARNING_TITLE,
                        text: MESSSAGE_STATUS_ERROR,
                        type: 'warning',
                        sticker: false
                    });
                }
            }
        }
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balanceExportExcel', function(e) {
        e.stopImmediatePropagation();
        $.blockUI(".block-ui");
        $.download('mdtime/timeEmployeePlanExportExcel/1', 'form', $("#tnaTimeEmployeePlanForm").serialize(), 'post', '');
        $.unblockUI(".block-ui");
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balancePlanCountExportExcel', function(e) {
        e.stopImmediatePropagation();
        $.blockUI(".block-ui");
        $.download('mdtime/timeEmployeePlanExportExcel/2', 'form', $("#tnaTimeEmployeePlanForm").serialize(), 'post', '');
        $.unblockUI(".block-ui");
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balancePaste', function(e) {
        e.stopImmediatePropagation();
        var cPlanYear   = $('#copy_planYear option:selected', tnaTimeEmployeePlanWindowId).val();
        var cPlanMonth  = $('#copy_planMonth option:selected', tnaTimeEmployeePlanWindowId).val();
      
        var planYear   = $('#planYear option:selected', tnaTimeEmployeePlanWindowId).val();
        var planMonth  = $('#planMonth option:selected', tnaTimeEmployeePlanWindowId).val();
        
        var cDepartmentId  = $('#departmentId', tnaTimeEmployeePlanWindowId).val();
        if (cPlanYear.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Warning',
                text: 'Оноо сонгоно уу?',
                type: 'warning',
                sticker: false
            });
            return false;
        }
        if (cPlanMonth.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Warning',
                text: 'Сараа сонгоно уу?',
                type: 'warning',
                sticker: false
            });
            return false;
        }
        if (planYear.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Warning',
                text: 'Оноо сонгоно уу?',
                type: 'warning',
                sticker: false
            });
            return false;
        }
        if (planMonth.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Warning',
                text: 'Сараа сонгоно уу?',
                type: 'warning',
                sticker: false
            });
            return false;
        }
        
        if(userSessionIsFull()) {
            PNotify.removeAll(); 
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_SESSION_FULL,
                type: 'warning',
                sticker: false
            });
        } else {
            var status = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-code');
            if (isWorkFlowStatus(status)) {
                if (isLockDate()){
                    PNotify.removeAll(); 
                    new PNotify({
                        title: MESSSAGE_WARNING_TITLE,
                        text: MESSSAGE_IS_LOCK,
                        type: 'warning',
                        sticker: false
                    });                
                } else {
                    copyPasteBalanceDate(planYear, planMonth, cPlanYear, cPlanMonth, cDepartmentId);
                }
                
            } else {
                PNotify.removeAll(); 
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_STATUS_ERROR,
                    type: 'warning',
                    sticker: false
                });    
            }
            
        }
      
    });

    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balanceVerifyItem', function(e) {
        e.stopImmediatePropagation();
        
        var _this = $(this);
        var wfmStatusCode = _this.attr('data-status-code');
        var planYear   = $('#planYear option:selected').val();
        var planMonth  = $('#planMonth option:selected').val();
        var departmentId  = $('#departmentId').val();
        var wfmStatusId = $(this).attr('id');
        if (planYear.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Error',
                text: 'Оноо сонгоно уу?',
                type: 'error',
                sticker: false
            });
            return false;
        }
        if (planMonth.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Warning',
                text: 'Сараа сонгоно уу?',
                type: 'warning',
                sticker: false
            });
            return false;
        }
        if(userSessionIsFull()) {
            PNotify.removeAll(); 
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_SESSION_FULL,
                type: 'warning',
                sticker: false
            });                
        } else {
            setSelectedCellValue();
            if (wfmStatusCode == 'confirmedByExecutive') {
                var dialogName = '#confirmDateDialog';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                $.ajax({
                    type: 'post',
                    url: 'mdtime/confirmLastDate',
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
                            width: '250',
                            height: 'auto',
                            modal: true,
                            close: function () {
                                $(dialogName).empty().dialog('destroy').remove();
                            },
                            buttons: [
                                {text: data.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                                    var approveLastDate = $("#approveLastDate", dialogName).val();
                                    if (approveLastDate.length > 0) {
                                        $("#approveLastDateForm").validate({errorPlacement: function () {}});
                                        if ($("#approveLastDateForm").valid()) {
                                            verifyDayPlanDate();
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                }},
                                {text: data.close_btn, class: 'btn btn-sm blue-madison', click: function () {
                                    $(dialogName).dialog('close');
                                }}
                            ]
                        });
                        $(dialogName).dialog('open');
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                }).done(function () {
                    Core.initAjax();
                });
            } else {
                verifyDayPlanDate();
            }
        }
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balanceVerify', function(e) {
        e.stopImmediatePropagation();
        var _this = $(this);
        var wfmStatusCode = _this.attr('data-status-code');
        var planYear   = $('#planYear option:selected').val();
        var planMonth  = $('#planMonth option:selected').val();
        var departmentId  = $('#departmentId').val();
        var wfmStatusId = $(this).attr('id');
        if (planYear.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Error',
                text: 'Оноо сонгоно уу?',
                type: 'error',
                sticker: false
            });
            return false;
        }
        if (planMonth.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Warning',
                text: 'Сараа сонгоно уу?',
                type: 'warning',
                sticker: false
            });
            return false;
        }
        if(userSessionIsFull()) {
            PNotify.removeAll(); 
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_SESSION_FULL,
                type: 'warning',
                sticker: false
            });                
        } else {
            if (_this.attr('data-status-code') == 'confirmedByExecutive') {
                var dialogName = '#confirmDateDialog';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                $.ajax({
                    type: 'post',
                    url: 'mdtime/confirmLastDate',
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
                            width: '250',
                            height: 'auto',
                            modal: true,
                            close: function () {
                                $(dialogName).empty().dialog('destroy').remove();
                            },
                            buttons: [
                                {text: data.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                                    var approveLastDate = $("#approveLastDate", dialogName).val();
                                    if (approveLastDate.length > 0) {
                                        $("#approveLastDateForm").validate({errorPlacement: function () {}});
                                        if ($("#approveLastDateForm").valid()) {
                                            verifyPlanDate(planYear, planMonth, departmentId, approveLastDate, wfmStatusId, wfmStatusCode, '');
                                            $(dialogName).dialog('close');
                                        }
                                    }
                                }},
                                {text: data.close_btn, class: 'btn btn-sm blue-madison', click: function () {
                                    $(dialogName).dialog('close');
                                }}
                            ]
                        });
                        $(dialogName).dialog('open');
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                }).done(function () {
                    Core.initAjax();
                });
            } else {
                verifyPlanDate(planYear, planMonth, departmentId, '', wfmStatusId, wfmStatusCode, '');
            }            
        }
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balanceOk', function(e) {
        e.stopImmediatePropagation();
      
        var planYear   = $('#planYear option:selected').val();
        var planMonth  = $('#planMonth option:selected').val();
        var departmentId  = $('#departmentId').val();

        if (planYear.length == 0) {
              PNotify.removeAll(); 
              new PNotify({
                  title: 'Error',
                  text: 'Оноо сонгоно уу?',
                  type: 'error',
                  sticker: false
              });
              return false;
        }
        if (planMonth.length == 0) {
              PNotify.removeAll(); 
              new PNotify({
                  title: 'Warning',
                  text: 'Сараа сонгоно уу?',
                  type: 'warning',
                  sticker: false
              });
              return false;
        }
        
      
        var dialogname=$('#dialogDescription');
        var $dialogname = 'dialogDescription';
        dialogname.empty().html('Баталгаажуулахдаа итгэлтэй байна уу?');
        dialogname.dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: 'Анхааруулга',
          width: 300,
          height: 'auto',
          modal: true,
          open: function() {
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs blue');
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn btn-xs green');
          },
          close: function() {
            dialogname.empty().dialog('close');
          },
          buttons: [
            { text: plang.get('yes_btn'), click: function() {
                $.ajax({
                  type: 'post',
                  url: 'Mdtime/okEmployeePlanMonth',
                  data: {"planYear": planYear, "planMonth": planMonth, "departmentId": departmentId},
                  dataType: "json",
                  beforeSend: function () {
                      Core.blockUI({
                          message: "Түр хүлээнэ үү!!!",
                          boxed: true
                      });
                  },
                  success: function (data) {
                    $.unblockUI();
                    PNotify.removeAll(); 
                    new PNotify({
                      title: data.status,
                      text: data.message,
                      type: data.status,
                      sticker: false
                    });
                    reloadEmployeePlanList();
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
                dialogname.dialog('close');
              } 
            },
            { text: plang.get('no_btn'), click: function() {
                dialogname.dialog('close');
              }
            }
          ]
        });
        dialogname.dialog('open');
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balanceNo', function(e) {
        e.stopImmediatePropagation();
        var planYear   = $(tnaTimeEmployeePlanWindowId).find('#planYear option:selected').val();
        var planMonth  = $(tnaTimeEmployeePlanWindowId).find('#planMonth option:selected').val();
        var departmentId  = $(tnaTimeEmployeePlanWindowId).find('#departmentId').val();
        var status = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-code');
        var wfmStatusId = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-id');
        
        if (planYear.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Error',
                text: 'Оноо сонгоно уу?',
                type: 'error',
                sticker: false
            });
            return false;
        }
        if (planMonth.length == 0) {
            PNotify.removeAll(); 
            new PNotify({
                title: 'Warning',
                text: 'Сараа сонгоно уу?',
                type: 'warning',
                sticker: false
            });
            return false;
        }
        if(userSessionIsFull()) {
            PNotify.removeAll(); 
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_SESSION_FULL,
                type: 'warning',
                sticker: false
            });                
        } else {
            if (isWorkFlowStatus(status)) {
                
                if (isLockDate()) {
                    PNotify.removeAll(); 
                    new PNotify({
                        title: MESSSAGE_WARNING_TITLE,
                        text: MESSSAGE_IS_LOCK,
                        type: 'warning',
                        sticker: false
                    }); 
                } else {
                    var $dialogName = 'dialog-fillInFor-employee';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    $("#" + $dialogName).empty().html('Цуцлахдаа итгэлтэй байна уу?');
                    $("#" + $dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Анхааруулга',
                        width: 300,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $("#" + $dialogName).empty().dialog('destroy');
                        },
                        buttons: [
                            {text: plang.get('yes_btn'), class: 'btn btn-sm green-meadow', click: function () {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdtime/okEmployeePlanMonth',
                                    data: {"planYear": planYear, "planMonth": planMonth, "departmentId": departmentId, "wfmStatusId": wfmStatusId},
                                    dataType: "json",
                                    beforeSend: function () {
                                        Core.blockUI({
                                            message: "Түр хүлээнэ үү!!!",
                                            boxed: true
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
                                        reloadEmployeePlanList();
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
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }}
                        ]
                    });
                    $("#" + $dialogName).dialog('open');
                }
                
            } else {
                PNotify.removeAll(); 
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_STATUS_ERROR,
                    type: 'warning',
                    sticker: false
                });                
            }
        }
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balanceChoseDelete', function(e) {
        e.stopImmediatePropagation();
        
        if (checkSelectedCell()) {
            if(userSessionIsFull()) {
                PNotify.removeAll(); 
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_SESSION_FULL,
                    type: 'warning',
                    sticker: false
                });                
            } else {
                var wfmStatusCode = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-code');
                var wfmStatusId = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-id');
                setSelectedCellValue();
                
                var $dialogName = 'dialog-confirm';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
               
                var _selectedPlanMonth = $('#planMonth option:selected', tnaTimeEmployeePlanWindowId).text();
                $("#" + $dialogName).empty().html(_selectedPlanMonth + 'ын сонгогдсон хэлтсүүдийн төлөвлөгөөг устгахдаа итгэлтэй байна уу?');
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Анхааруулга',
                    width: 530,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdtime/deleteEmployeePlan',
                                data: $("#tnaBalancePlanGridForm").serialize() + $("#tnaTimeEmployeePlanForm").serialize() + '&userWfmStatusCode=' + wfmStatusCode + '&userWfmStatusId=' + wfmStatusId,
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    /* getEmployeePlanList(); */
                                    timePlanGotoPage($('#tnatimePlanPage', tnaTimeEmployeePlanWindowId).val());
                                    
                                    PNotify.removeAll(); 
                                    new PNotify({
                                        title: data.title,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                },
                                error: function () {
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
                        {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                   
            }
        } else {
            if(userSessionIsFull()) {
                PNotify.removeAll(); 
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: MESSSAGE_SESSION_FULL,
                    type: 'warning',
                    sticker: false
                });                
            } else {
                allCellsDelete();
            }
        }
        
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .balanceDelete', function(e) {
        e.stopImmediatePropagation();
        
        if(userSessionIsFull()) {
            PNotify.removeAll(); 
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_SESSION_FULL,
                type: 'warning',
                sticker: false
            });                
        } else {
            allCellsDelete();
        }
    });
    
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' .stoggler', function (e) {
        e.stopImmediatePropagation();
        
        var _thisTogglerTimePlan = $(this);
        var _centersidebar = $(".center-sidebar", tnaTimeEmployeePlanWindowId);
        var _rightsidebar = $(".right-sidebar", tnaTimeEmployeePlanWindowId);
        var _rightsidebarstatus = _rightsidebar.attr("data-status");
        if (_rightsidebarstatus === "closed") {
          _centersidebar.removeClass("col-md-12").addClass("col-md-9");
          _rightsidebar.addClass("col-md-3");
          _rightsidebar.find(".glyphicon-chevron-right").parent().hide();
          _rightsidebar.find(".glyphicon-chevron-left").hide();
          _rightsidebar.find(".right-sidebar-content").show(
                  "slide", {direction: "right"}, 1200,
                  function () {
                      _rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn("slow");
                      _rightsidebar.find(".glyphicon-chevron-right").fadeIn("slow");
                  }
          );
          // faIncomeDtlTable.fnAdjustColumnSizing();
          _rightsidebar.attr('data-status', 'opened');
          _thisTogglerTimePlan.addClass("sidebar-opened");
        } 
        else {
          _rightsidebar.find(".glyphicon-chevron-right").hide();
          _rightsidebar.find(".glyphicon-chevron-right").parent().hide();
          _rightsidebar.find(".right-sidebar-content").hide(
                  "slide", {direction: "right"}, 1200,
                  function () {
                      _centersidebar.removeClass("col-md-9").addClass("col-md-12");
                      _rightsidebar.removeClass("col-md-3");
                      _rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");
                      _rightsidebar.find(".glyphicon-chevron-left").fadeIn("slow");
                      //               faIncomeDtlTable.fnAdjustColumnSizing();
                  }
          );
          _rightsidebar.attr('data-status', 'closed');
          _thisTogglerTimePlan.removeClass("sidebar-opened");
        }
    });
    
    $("body").on("mouseover", tnaTimeEmployeePlanWindowId + ' .stoggler',  function (e) {
        e.stopImmediatePropagation();
        $(this).css({
          "background-color": "rgba(230, 230, 230, 0.80)",
          "border-right": "1px solid rgba(230, 230, 230, 0.80)"
        });
    });
    
    $("body").on("mouseleave", tnaTimeEmployeePlanWindowId + ' .stoggler', function (e) {
        e.stopImmediatePropagation();
        $(this).css({
          "background-color": "#FFF",
          "border-right": "#FFF"
        });
    });
    
    changeEmployeePlanTime();
    
});

function verifyPlanDate(planYear, planMonth, departmentId, approveLastDate, wfmStatusId, wfmStatusCode, confirmDescription) {
    $.ajax({
        type: 'post',
        url: 'mdtime/verifyEmployeePlanMonth',
        data: {"planYear": planYear, "planMonth": planMonth, "departmentId": departmentId, approveLastDate: approveLastDate, "wfmStatusId": wfmStatusId, "wfmStatusCode": wfmStatusCode, "confirmDescription": confirmDescription},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            Core.unblockUI();
            PNotify.removeAll(); 
            if (data.status == 'success') {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                addUserSessionCount();
                getEmployeePlanList();
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
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

function verifyDayPlanDate(confirmDescription) {
    $("#wfmStatusCodeSet").val($("#setSideBarDefaultContent").find(".balanceVerifyItem").attr('data-status-code'));
    $("#wfmStatusIdSet").val($("#setSideBarDefaultContent").find(".balanceVerifyItem").attr('data-status-id'));
    if (typeof confirmDescription == 'undefined') {
        confirmDescription = '';
    }
    $.ajax({
        type: 'post',
        url: 'mdtime/verifyEmployeePlanDay',
        data: $("#tnaBalancePlanGridForm").serialize() + '&confirmDescription=' + confirmDescription,
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            Core.unblockUI();
            PNotify.removeAll(); 
            if (data.status == 'success') {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                addUserSessionCount();
                getEmployeePlanList();
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
            
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

function allCellsDelete() {
    var $dialogName = 'dialog-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var _selectedPlanMonth = $('#planMonth option:selected', tnaTimeEmployeePlanWindowId).text();
    $("#" + $dialogName).empty().html(_selectedPlanMonth + 'ын сонгогдсон хэлтсүүдийн төлөвлөгөөг устгахдаа итгэлтэй байна уу?');
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Анхааруулга',
        width: 530,
        height: "auto",
        modal: true,
        close: function () {
            $("#" + $dialogName).empty().dialog('close');
        },
        buttons: [
            {text: 'Тйим', class: 'btn green-meadow btn-sm', click: function () {
                $("#tnaTimeEmployeePlanForm").validate({errorPlacement: function () {}});
                if ($("#tnaTimeEmployeePlanForm").valid()) {
                    var wfmStatusCode = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-code');
                    var wfmStatusId = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-id');
                    $.ajax({
                        type: 'post',
                        url: 'mdtime/deleteEmployeePlanMonth',
                        data: $("#tnaBalancePlanGridForm").serialize() + $("#tnaTimeEmployeePlanForm").serialize() + '&userWfmStatusCode=' + wfmStatusCode + '&userWfmStatusId=' + wfmStatusId,
                        dataType: "json",
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function (data) {
                            Core.unblockUI();
                            if(data.status == 'success') {
                                getEmployeePlanList();
                            }
                            PNotify.removeAll(); 
                            new PNotify({
                                title: data.title,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
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
                else {
                  $('html, body').animate({
                        scrollTop: 0
                    }, 0);
                }

                $("#" + $dialogName).dialog('close');
            }},
            {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');
}

function checkSelectedCell() {
    var selectedCell = false;
    var tbl = $('#tnaBalanceGrid', tnaTimeEmployeePlanWindowId).find('table tbody');
    tbl.find('td').each(function () {
        var _this = $(this);
        if (_this.hasClass('ui-selected') && _this.find('input[data-name="planTime"]').val() != '') {
            selectedCell = true;
        }
    });
    
    if (!selectedCell) {
        return false;
    } 
    return true;
}

function isApproveCeoCell() {
    var tbl = $('#tnaBalanceGrid', tnaTimeEmployeePlanWindowId).find('table tbody');
    var isStatus = false;
    tbl.find('.ui-selected').each(function () {
        var _this = $(this);
        if (_this.find('input[data-name="wfmStatusId"]').val() == '1450760377812532') {
            isStatus = true;
        }
    });
    return isStatus;
}

function copyPasteBalanceDate(planYear, planMonth, cPlanYear, cPlanMonth, cDepartmentId) {
    $.ajax({
            type: 'post',
            url: 'mdtime/saveEmployeePlanMonth',
            data: {"planYear": planYear, "planMonth": planMonth, "cPlanYear": cPlanYear, "cPlanMonth": cPlanMonth, "cDepartmentId": cDepartmentId},
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
                addUserSessionCount();
                $.ajax({
                    type: 'post',
                    url: 'mdtime/employeePlanListMainDataGrid',
                    data: { 
                      "params": $("#tnaTimeEmployeePlanForm").serialize()
                    },
                    dataType: "json",
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        Core.unblockUI();

                        $('#employeeCurrentMonthTime').val('');
                        $('#departmentCurrentTime').val('');
                        $('#departmentCurrentMonthTime').val('');
                        $('#employeeCurrentTime').val('');

                        planyear = $("#planYear").val();
                        planmonth = $("#planMonth").val();

                        reloadEmployeePlanList(data);
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
            },
            error: function () {
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

function daysInMonth(month, year) {
  return new Date(year, month, 0).getDate();
}

function removeEmployeePlan(elem) {
  var target_row = $(elem).closest("tr").get(0);
  var employeeId = $(target_row).find('#EMPLOYEE_ID').find('span').html();
  var dayString = $(target_row).find('.ui-selected').attr('id');
  if(dayString == undefined) {
    alert('odoroo songono uu ?');
    return false;
  }
  
  var daySplit = dayString.split('_');
  var day = parseInt(daySplit[1]);
  return false;
  var aPos = faIncomeDtlTable.fnGetPosition(target_row);
  $(".grid-row-content", faAssetIncomeWindowId).find("table tbody").empty();
}

function getEmployeePlanList(timePlanUniqId) {
    $.ajax({
        type: 'post',
        url: 'mdtime/employeePlanListMainDataGridNew',
        data: {"params": $("#tnaTimeEmployeePlanForm").serialize()},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: "Цагийн төлөвлөгөөг бэлтгэж байна",
                boxed: true
            });
        },
        success: function (data) {
            reloadEmployeePlanList(data);
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

function getEmployeePositionPlanList(isInsert) {
    $.ajax({
        type: 'post',
        url: 'mdtime/employeePositionPlanListMainDataGrid',
        data: {"params": $("#tnaTimeEmployeePlanForm").serialize(), "isInsert": isInsert},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            Core.unblockUI();
            if(data.status == 'success') {
                getEmployeePlanList();
                addUserSessionCount();
            } else {
                var _isPositionWorkingDays = $("#isPositionWorkingDays");
                if(_isPositionWorkingDays.is(':checked')) {
                     _isPositionWorkingDays.removeAttr('checked');
                      _isPositionWorkingDays.closest('span').removeClass('checked'); 
                }
            }
            PNotify.removeAll(); 
            new PNotify({
                title: data.title,
                text: data.message,
                type: data.status,
                sticker: false
            });
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

function deleteEmployeePositionPlanList() {
    $("#tnaTimeEmployeePlanForm").validate({
          errorPlacement: function () {}
    });
    if ($("#tnaTimeEmployeePlanForm").valid()) {
        $.ajax({
            type: 'post',
            url: 'Mdtime/deleteEmployeePositionPlanListMainDataGrid',
            data: { 
                "params": $("#tnaTimeEmployeePlanForm").serialize()
            },
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: "Түр хүлээнэ үү!!!",
                    boxed: true
                });
            },
            success: function (data) {
              $.unblockUI();
              if(data.status == 'success') {
                getEmployeePlanList();
              }
              PNotify.removeAll(); 
              new PNotify({
                title: data.title,
                text: data.message,
                type: data.status,
                sticker: false
              });
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
    else {
      $('html, body').animate({
            scrollTop: 0
        }, 0);
    }
}

function reloadEmployeePlanList(data) {
    if (data != undefined) {
        $("#tnaBalanceGrid", tnaTimeEmployeePlanWindowId).html(data).promise().done(function() {
            var pagerElement = $('.pf-custom-pager-tool', tnaTimeEmployeePlanWindowId);
            var totalRowNumber = $('#tnatimePlanTotalCount', tnaTimeEmployeePlanWindowId).val();
            var pageNumber = 1;
            var pageNumbers = Math.ceil(totalRowNumber / 50) || 1;
            var currentPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());
            
            pagerElement.find('.pf-custom-pager-total > span').text(totalRowNumber);
            pagerElement.find('input[data-gotopage]').val(pageNumber);
            pagerElement.find('span[data-pagenumber]').text(pageNumbers);

            if (currentPageNumber == 1) {
                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
            } else {

                if (pageNumber == currentPageNumber) {
                    pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                    pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                } else if (pageNumber == 1 && pageNumbers == 1) {
                    pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                    pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                } else if (pageNumber == 1) {
                    pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev').addClass('pf-custom-pager-disabled');
                    pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                } else {
                    pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                }
            }

            if ($().tableHeadFixer) {
                $('table#assetDtls', $('#tnaTimeEmployeePlanWindow')).tableHeadFixer({'head': true, 'foot': true, 'left': 3, 'z-index': 9}); 
                $('#fz-parent', $('#tnaTimeEmployeePlanWindow')).trigger('scroll');
            }
            
            timePlanResizeDtlTable();
        });
        
        var tbl = $("#tnaBalanceGrid").find("table");
        var tblHeader = tbl.find('tr.tablesorter-headerRow th');
        var tblFilterHeader = tbl.find('tr.tablesorter-ignoreRow td');
        var _tblColSpan = tbl.find('tbody.tablesorter-no-sort tr').find('td.departmentTitle');
        var $bcolspan = _tblColSpan.attr('colspan');

        if ($("#onlyWorkingDay").val() == '1') {
            tbl.find('td.weekday').removeClass('tbl-cell').hide();
            tbl.find('th.weekday').removeClass('tbl-cell').hide();
            var $colspan = 1;
            for ( var i = 0; i <= tblHeader.length; i++) {
                if ($(tblHeader[i]).attr('data-isworking') == '7' || $(tblHeader[i]).attr('data-isworking') == '6') {
                    $(tblFilterHeader[i]).hide();
                    $colspan++;
                }
            }
            _tblColSpan.attr('colspan', parseInt($bcolspan) - parseInt($colspan));
        }
        
        Core.unblockUI();
        var prev = -1;
        $("#tnaBalanceGrid table tbody").selectable({
            filter:'td.tbl-cell',
            stop : function (event, ui) {
                var _this = $(this);
                var planUniqId = _this.closest('div[id="tnaTimeEmployeePlanWindow"]').attr('timeplan-uniqid');
                var _cells = _this.find('td.tbl-cell.ui-selected');
                var planId, departmentId = 0;
                var row = _cells.closest('tr');
                if (_cells.length === 1) {
                    planId = _cells.find('input[data-name="planId"]').val();
                    departmentId = [row.find('input[data-name="departmentId"]').val()];
                    initTimePlanList({"PLAN_ID": planId, "DEPARTMENT_ID": departmentId}, planUniqId, _cells);
                } else if (row.length > 0) {
                    var department = [];
                    for(var i=0; i<row.length; i++) {
                        department.push($(row[i]).find('input[data-name="departmentId"]').val());
                    }
                    initTimePlanList({"PLAN_ID": planId, "DEPARTMENT_ID": department}, planUniqId);
                }
            },
            selecting: function(e, ui) {
                var curr = $(ui.selecting.tagName, e.target).index(ui.selecting);
                if(e.shiftKey && prev > -1) {
                    for (var i = prev; i <=  curr; i++) {
                        var _selectedCell = $(ui.selecting.tagName, e.target)[i];
                        if ($(_selectedCell).hasClass('tbl-cell')) {
                            $(_selectedCell).addClass('ui-selected');
                            prev = -1;
                        }
                    }
                } else {
                    prev = curr;
                }
            }
        });
    }
}

function changeEmployeePlanTime() {
    $("body").on('click', tnaTimeEmployeePlanWindowId + ' input:radio[name="plan"]', function(e){
        e.stopImmediatePropagation(); 
        $("#planIdSet").val($(this).val());
        $("#wfmStatusCodeSet").val($("#setSideBarDefaultContent").find(".balanceVerifyItem").attr('data-status-code'));
        /*if(userSessionIsFull()) {
            PNotify.removeAll(); 
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_SESSION_FULL,
                type: 'warning',
                sticker: false
            });
        } else { */
            setSelectedCellValue();
            $.ajax({
                type: 'post',
                url: 'mdtime/saveEmployeePlan',
                data: $("#tnaBalancePlanGridForm").serialize() + $("#tnaTimeEmployeePlanForm").serialize(),
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        message: "Түр хүлээнэ үү!!!",
                        boxed: true
                    });
                },
                success: function (data) {
                    PNotify.removeAll(); 
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    /* getEmployeePlanList(); */
                    timePlanGotoPage($('#tnatimePlanPage', tnaTimeEmployeePlanWindowId).val());
                },
                error: function () {
                  PNotify.removeAll(); 
                  new PNotify({
                      title: 'Error',
                      text: 'error',
                      type: 'error',
                      sticker: false
                  });
                }
            }).done(function(){
                Core.unblockUI();
            });
        /*}*/
    });
}

function setSelectedCellValue() {
    var tbl = $('#tnaBalanceGrid');
    tbl.find('input[data-name="isSelectedCell"]').val(0);
    var selectedCells = tbl.find('td.ui-selected');
    for(var i = 0; i < selectedCells.length; i++) {
        var _cell = $(selectedCells[i]);
        _cell.find('input[data-name="isSelectedCell"]').val(1);
    }
}

function saveEmployeeTimePlan(data, planId, planDescription) {
  
    var saveParams = [];

    $.each(TNA_NEEDLE_CHECKED_TOTAL_TIME, function(cKey, cRow) {
        $.each(data, function(dKey, dRow) {
            if (  dRow.departmentId == cRow.DEPARTMENT_ID   && 
                  dRow.employeeId == cRow.EMPLOYEE_ID   && 
                  dRow.employeeKeyId   == cRow.EMPLOYEE_KEY_ID &&
                  parseInt(cRow.IS_ACTIVE) == 1
            ) {
                saveParams.push({
                    id            : dRow.employeeId, 
                    date          : dRow.date, 
                    employeeKeyId : dRow.employeeKeyId
              });
            }
        });
    });
  
    if (saveParams.length == 0) {
        PNotify.removeAll(); 
        new PNotify({
          title: 'Warning',
          text: 'Ямар нэгэн бичилт хийгдсэнгүй',
          type: 'warning',
          sticker: false
        });
        return false;
    }
    var postParams = {"data": saveParams, "planId": planId, "planDescription": planDescription};
    $.ajax({
        type: 'post',
        url: 'mdtime/saveEmployeePlan',
        data: postParams,
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: "Түр хүлээнэ үү!!!",
                boxed: true
            });
        },
        success: function (data) {
            
            PNotify.removeAll(); 
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            /*reloadEmployeePlanList();*/
            timePlanGotoPage($('#tnatimePlanPage', tnaTimeEmployeePlanWindowId).val());
            
            $.each(TNA_NEEDLE, function(nKey, nRow) {
                if (  TEMP_DEPARTMENT_ID === nRow.DEPARTMENT_ID &&
                      TEMP_EMPLOYEE_KEY_ID === nRow.EMPLOYEE_KEY_ID &&
                      TEMP_EMPLOYEE_ID === nRow.EMPLOYEE_ID
                    ) {
                    $('#employeeCurrentMonthTime').val(decimalAdjust('round', nRow.DEPARTMENT_TIME, 2));
                }
            });
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

function decimalAdjust(type, value, exp) {
  // If the exp is undefined or zero...
  if (typeof exp === 'undefined' || +exp === 0) {
    return Math[type](value);
  }
  value = +value;
  exp = +exp;
  // If the value is not a number or the exp is not an integer...
  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
    return NaN;
  }
  // Shift
  value = value.toString().split('e');
  value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
}

function isDay(date) {
    var d = new Date(date);
    var day = d.getDay();
    switch(day) {
      case 0:
        return false;
        break;
      case 6:
        return false;
        break;
      default:
        return true;
    }
}

jQuery.download = function(url, type, data, method, target) {
  if(url && data) {
    var inputs = '';
    
    if(type === 'form') {
      jQuery.each(data.split('&'), function(){
        var pair = this.split('=');
        inputs   += '<input type="hidden" name="' + decodeURIComponent(pair[0]) + '" value="' + decodeURIComponent(pair[1].replace(/\+/g, ' ')) + '" />';
      });
    } 
    else {
      data = typeof data == 'string' ? data : jQuery.param(data);
      jQuery.each(data.split('&'), function() {
        var pair = this.split('=');
        inputs   += '<input type="hidden" name="' + decodeURIComponent(pair[0]) + '" value="' + decodeURIComponent(pair[1].replace(/\+/g, ' ')) + '" />';
      });
    }
    var targetAttr = "";
    
    if(target) {
      targetAttr = ' target="' + target + '"';
    }
    jQuery('<form action="' + url + '" method="' + method + '"' + targetAttr + '>' + inputs + '</form>').appendTo('body').submit().remove();
  }
};

function in_array(item,arr) {
    if (arr.length == 0) {
       return false;
    } 
    else {
      for (var p=0; p < arr.length; p++) {
       if (item == arr[p]) {
         return true;
       }
      }
      return false;
    }
}

function balanceTime () {
      
    var departmentCurrentMonthTime = ($('#departmentCurrentMonthTime').val()) ? parseFloat($('#departmentCurrentMonthTime').val()) : 0;
    var departmentCurrentMonthTemp = ($('#departmentCurrentMonthTime').val()) ? parseFloat($('#departmentCurrentMonthTime').val()) : 0;

    var departmentCurrentTime = (departmentCurrentMonthTime) ? parseFloat(departmentCurrentMonthTime) : 0;
    
    TEMP_DEPARTMENT_TT = 0;
    
    $.each(TNA_TEMPED, function(tKey, tRow) {
      if (TEMP_DEPARTMENT_ID == tRow.DEPARTMENT_ID) {
        var ttime = ((tRow.DP_TEMP_TIME.length == 0) ? 0 : parseFloat(tRow.DP_TEMP_TIME));
        TEMP_DEPARTMENT_TT = parseFloat(TEMP_DEPARTMENT_TT) + ttime;
      }
    });

    var departmentCurrentTemp = (departmentCurrentMonthTemp) ? parseFloat(departmentCurrentMonthTemp) - parseFloat(TEMP_DEPARTMENT_TT) : 0;
    if (departmentCurrentTemp < 0) {
      PNotify.removeAll(); 
      new PNotify({
          title: 'Warning',
          text: 'Үлдэгдэл хүрэлцэхгүй байна хэтэрхий их байна! Анхаарна уу?',
          type: 'warning',
          sticker: false
      });

      $('#employeeCurrentMonthTime').val('');
      $('#employeeCurrentMonthTime').val('');
      $('#employeeCurrentMonthTime').addClass('error');
      $('#departmentCurrentTime').addClass('error');
      return false;
    }

    var dT = departmentCurrentTime - parseFloat(TEMP_DEPARTMENT_TT);
    $('#departmentCurrentTime').val(dT);
  
}

function getEmptySideBar (timePlanUniqId) {
    $.ajax({
        type: 'post',
        url: 'mdtime/getEmptySideBar',
        dataType: "json",
        data: {"year": $("#planYear", '.tnaTimeEmployeePlan-'+ timePlanUniqId).val(), "month": $("#planMonth", '.tnaTimeEmployeePlan-'+ timePlanUniqId).val(), 'uniqId': timePlanUniqId},
        async: false,
        success: function (data) {
            $("#setSideBarDefaultContent", '.tnaTimeEmployeePlan-'+ timePlanUniqId).html(data);
        }
    }).done(function(){
        Core.initAjax($("#setSideBarDefaultContent"));
    });
}

function addUserSessionCount() {
    $.ajax({
        type: 'post',
        url: 'mdtime/addUserSessionCount',
        dataType: "json",
        async: false,
        success: function (data) {
            result = data;
            Core.initAjax();
        }
    });
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

function callCustomByMeta(metaDataId, isDialog, valuePackageId, isSystemMeta) {
    if (typeof (isDialog) === 'undefined') {
        isDialog = false;
    }
    if (typeof (valuePackageId) === 'undefined') {
        valuePackageId = '';
    }
    if (typeof (isSystemMeta) === 'undefined') {
        isSystemMeta = 'false';
    }
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: {metaDataId: metaDataId, isDialog: isDialog, valuePackageId: valuePackageId, isSystemMeta: isSystemMeta},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            if (data.mode === 'dialog') {
                var $dialogName = 'dialog-wsMethod-' + metaDataId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
                }
                $("#" + $dialogName).empty().html(data.Html);
                
                var processForm = $("#wsForm", "#" + $dialogName);
                var processUniqId = processForm.parent().attr('data-bp-uniq-id');
                var runModeButton = '';
                if (typeof data.run_mode !== 'undefined') {
                    runModeButton = data.run_mode;
                }
                
                if (data.isDefaultValue == true) {
                    var buttons = [
                        {text: 'Тусламж', class: 'btn btn-info btn-sm float-left', click: function () {
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm float-left', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }},
                        {text: data.run_btn, class: 'btn green-meadow btn-sm ' + runModeButton, click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     
                                processForm.validate({errorPlacement: function () {}});
                                if (processForm.valid()) {
                                    processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({
                                                message: 'Түр хүлээнэ үү',
                                                boxed: true
                                            });
                                        },
                                        success: function (responseData) {
                                            $("#responseMethod", "#" + $dialogName).empty().html(responseData.responseMethod);
                                            getEmployeePlanList();
                                            getEmptySideBar();
                                            $('body').find('#tnaTimeEmployeePlanWindow').find('.stoggler').trigger('click');
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                }
                            }    
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ];
                } else {
                    var buttons = [
                        {text: 'Тусламж', class: 'btn btn-info btn-sm float-left', click: function () {
                        }},
                        {text: data.run_btn, class: 'btn green-meadow btn-sm', click: function (e) {
                                
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     
                                
                                processForm.validate({errorPlacement: function () {}});
                                if (processForm.valid()) {
                                    processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({
                                                message: 'Түр хүлээнэ үү',
                                                boxed: true
                                            });
                                        },
                                        success: function (responseData) {
                                            PNotify.removeAll();
                                            if (responseData.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: responseData.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                                $("#" + $dialogName).dialog('close');
                                                getEmployeePlanList();
                                                return;
                                                if (metaDataId == '1457087017597211') {
                                                    getEmployeePlanList();
                                                } else {
                                                    initTimePlanList(rowData);
                                                }
                                                
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: responseData.message,
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
                            }    
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ];
                }

                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: data.dialogWidth,
                    height: data.dialogHeight,
                    modal: true,
                    closeOnEscape: isCloseOnEscape, 
                    //position: {my: 'top', at: 'top+50'},
                    close: function () {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
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
                    $("#" + $dialogName).dialogExtend("maximize");
                }
                $("#" + $dialogName).dialog('open');
            } else {
                $("#mainRenderWindow").show();
                if (!$("#viewFormMeta").length) {
                    newContainerAppend(data.Html);
                } else {
                    $("#viewFormMeta").empty().append(data.Html);
                    $("#renderMeta, #editFormGroup").hide();
                    $("#viewFormMeta").show();
                }
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
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

function initTimePlanList(rowData, timePlanUniqId, cell) {
    
    var html = '';
    var type = '';
    var sidebarContent = $("body").find(tnaTimeEmployeePlanWindowId + " .grid-row-content");
        sidebarContent.empty();
    var _this = cell;    
    if(typeof cell !== 'undefined') {
        var day = _this.find('input[data-name="day"]').val();
        if (day != '') {
            type = 'sidebarInfo';                      
        }        
    }
        
    $.ajax({
        type: 'post',
        url: 'mdtime/employeePlanList',
        dataType: "json",
        data: {
            "departmentId": rowData.DEPARTMENT_ID,
            "requestType": type,
            "year": $("#planYear", '.tnaTimeEmployeePlan-'+ timePlanUniqId).val(), 
            "month": $("#planMonth", '.tnaTimeEmployeePlan-'+ timePlanUniqId).val(),
            "uniqId": timePlanUniqId
        },
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            if(type !== '') {
                $("body").find("#setSideBarDefaultContent").html(data.employeeSidebar);
                var row = _this.closest("tr");
                var day = parseFloat(day);
                var employeeName = row.find('input[data-name="lastName"]').val() + ' ' +  row.find('input[data-name="firstName"]').val().toUpperCase();
                var selectedData = {
                    "EMPLOYEE_PICTURE": row.find('input[data-name="employeePicture"]').val(),
                    "EMPLOYEE_NAME": employeeName,
                    "EMPLOYEE_CODE": row.find('input[data-name="code"]').val(),
                    "EMPLOYEE_STATUS": row.find('input[data-name="statusName"]').val(),
                    "PLAN_DATE": _this.find('input[data-name="planDate"]').val(),
                    "POSITION_NAME": row.find('input[data-name="positionName"]').val()
                };
                
                $('#sidebar-user-logo').attr("src", selectedData.EMPLOYEE_PICTURE).attr('onerror', "onUserImageError(this);");
                $('#sidebar-user-name').html(selectedData.EMPLOYEE_NAME);
                $('#sidebar-user-code').html(selectedData.EMPLOYEE_CODE);
                $('#sidebar-user-status').html(selectedData.EMPLOYEE_STATUS);
                $('#sidebar-user-position').html(selectedData.POSITION_NAME);

                var planDate = selectedData.PLAN_DATE;
                $('#sidebar-user-date').html(planDate);
                $('#departmentCurrentMonthTime').val(selectedData.DEPARTMENT_TIME);                  
                Core.initAjax($("#setSideBarDefaultContent"));
            }
            
            var employeePlanCount = data.employeePlan.length;
            
            for(var i = 0; i < employeePlanCount; i++) {
                var row = data.employeePlan[i];

                var planStartAndEndTime = (row.START_TIME) ? row.START_TIME + '-' + row.END_TIME : '';
                html += '<tr>';
                html += '<td data-code="' + row.CODE + '">';
                    html += '<label>';
                        html += '<input class="ui-selected" name="plan" type="radio" ' + (rowData.PLAN_ID == row.PLAN_ID ? "checked" : "") + ' value="' + row.PLAN_ID + '" /> ';
                        html += row.NAME;
                    html += '</label>';
                html += '</td>';
                html += '<td>' + planStartAndEndTime + '</td>';
                html += '</tr>';
            }
            var addBtn = '';
                if (typeof data.isAdd !== 'undefined' && data.isAdd === 'true') {
                    /* if (_golomtViewEmployeePlan) { */
                        addBtn = '<button type="button"  class="btn btn-xs btn-success ml0 mr0 mb5" title="Цагийн төлөвлөгөө" onclick="callCustomByMeta(\'' + data.tnaTimePlanId + '\', true);"><i class="fa fa-clock-o"></i> Цагийн төлөвлөгөө оноох</button>';
                    /* } */
                }
                
                addBtn += '<div class="row">'
                            + '<div class="col-md-12">'
                                + '<input type="text" id="tnatimeplan" class="form-control form-control-sm stringInit mb5" placeholder="Хайх" style="width: 250px;" value="">'
                            + '</div>'
                        + '</div>'
                        ;
            sidebarContent.html(addBtn + '<div class="panel panel-default bg-inverse timePlanScroller" style="max-height: 210px;">'
                                            + '<table class="table sheetTable timePlanList" id="tna_timeplan_list">'
                                                + '<thead class="hidden">'
                                                    + '<tr>'
                                                        + '<th>Нэр</th>'
                                                        + '<th>Цаг</th>'
                                                    + '</tr>'
                                                + '</thead>'
                                                + '<tbody>' + html + '</tbody></table>'
                                        + '</div>');
                        
                        
            $.getScript('assets/custom/addon/plugins/datatables/all.min.js').done(function() {
                $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>');
                var table = $('#tna_timeplan_list').DataTable({
                    "paging": false,
                    "info":     false,
                    "ordering": false
                });
                $('#tnatimeplan').on( 'keyup', function () {
                    table.search( this.value ).draw();
                });
                
                $('#tna_timeplan_list_wrapper').find('.row').hide();
            });
        },
        error: function () {
            Core.unblockUI();
            new PNotify({
                title: 'Error',
                text: 'error',
                type: 'error',
                sticker: false
            });
        }
    }).done(function(){
        $('.timePlanScroller').slimScroll({
            height: 300,
            alwaysVisible: true
        });
        Core.initUniform($('#setSideBarAddTimePlan'));
        Core.unblockUI();
    });
}

function isWorkFlowStatus(wfmStatusCode, selectedClass) {
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

function isLockDate(selectedClass) {
    if (typeof selectedClass == 'undefined') {
        selectedClass = '';
    }
    var tbl = $("#tnaBalanceGrid").find("table tbody");
    var selectedCell = tbl.find('td.tbl-cell'+selectedClass);
    var selectedCellCount = selectedCell.length;
    var result = false;
    if (selectedCellCount > 0) {
        for(var i = 0; i < selectedCellCount; i++) {
            var _cell = $(selectedCell[i]);
            var _planDate = _cell.find('input[data-name="planDate"]').val();
            if (_planDate != '') {
                var isLock = _cell.find('input[data-name="isLock"]').val();
                var lockEndDate = _cell.find('input[data-name="lockEndDate"]').val();

                if (isLock == '1' && (lockEndDate != '' || lockEndDate != 'null')) {
                    lockEndDate = new Date(lockEndDate);
                    currentDate = new Date(currentDate);
                    var diff = (currentDate - lockEndDate);
                    if (diff <= 0) {
                        result = true;
                    }
                }
            }
        }
    }
    return result;
}

function sendArchiv(elem, tnaPlanUniqId) {
    var _this = $(elem);
    var _year = $("#planYear", ".tnaTimeEmployeePlan" + tnaPlanUniqId).val();
    var _month = $("#planMonth", ".tnaTimeEmployeePlan" + tnaPlanUniqId).val();
    var dialogName = '#sendArchivDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdtimestable/sendArchivForm',
        dataType: "json",
        data: {"year": _year, "month": _month, "tnaPlanUniqId": tnaPlanUniqId},
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
                width: '350',
                height: 'auto',
                modal: true,
                close: function () {
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                        $("#archivForm", dialogName).validate({errorPlacement: function () {}});
                        if ($("#archivForm", dialogName).valid()) {
                            Core.blockUI({
                                animate: true
                            });
                            var result = sendArchivAjax($("#tnaTimeEmployeePlanForm").serialize() + '&description=' + $("#description").val(), tnaPlanUniqId);
                            if (result.status === 'success') {
                                getEmployeePlanList();
                                $(dialogName).dialog('close');
                                new PNotify({
                                    title: 'Амжилттай',
                                    text: result.message,
                                    type: 'success',
                                    sticker: false
                                });
                            } else {
                                new PNotify({
                                    title: 'Тайлбар',
                                    text: result.message,
                                    type: 'warning',
                                    sticker: false
                                });
                            }
                            Core.unblockUI();
                        }
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-madison', click: function () {
                        $(dialogName).dialog('close');
                    }}
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}

function sendArchivAjax(param, tnaPlanUniqId) {
    var result = {"status": "error", "message": "Алдаа гарсан байна"};
    $.ajax({
        type: 'post',
        url: 'mdtimestable/sendArchiv',
        data: param,
        dataType: "json",
        async: false,
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            if (data.status === 'success') {
                setArchivComboData(param);
                result = {"status": data.status, "message": data.message};
            }
            if (typeof tnaPlanUniqId !== 'undefined' && typeof data.archivList !== 'undefined') {
                
                var tnaHtml = $('.tnaArchiveList-' + tnaPlanUniqId);
                tnaHtml.empty();
                var html = '<select id="archivId" name="archivId" class="form-control select2 form-control-sm input-xxlarge" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
                if (data.archivList.length > 0) {
                    $.each(data.archivList, function (key, row) {
                        html += '<option value="' + row.ID + '">' + row.VERSION + '-' + row.DESCRIPTION + '</option>';
                    });
                }
                html += '</select>'; 
                tnaHtml.html(html);
            }
        }
    }).done(function(){
        Core.unblockUI();
        Core.initInputType();
    });
    return result;
}

function getViewArchiv(elem, uniqId) {
    var _this = $(elem);
    var row = _this.closest("tr");
    var archivId = row.find("#archivId").val();
    var departmentIds = $("#departmentId").val();
    
    if(archivId.length > 0 && departmentIds != null) {
        $.ajax({
            type: 'post',
            url: 'mdtime/getArchivPlanListMainDataGrid',
            data: {"planLogId": archivId, "departmentId": departmentIds},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                timePlanResizeDtlTable();
                reloadEmployeePlanList(data);
                $.ajax({
                    type: 'post',
                    url: 'mdtime/getArchivPlanList',
                    data: {
                        "departmentId": departmentIds,
                        "year": $("#planYear").val(), 
                        "month": $("#planMonth").val()
                    },
                    dataType: "json",
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (response) {
                        console.log(response);
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
    } else {
        new PNotify({
            title: 'Тайлбар',
            text: 'Алба, хэлтэс эсвэл архив хувилбарыг сонгоогүй байна',
            type: 'warning',
            sticker: false
        });
    }
}

function enableOrDisableBackArchivBtn(elem) {
    var _this = $(elem);
    var row = _this.closest('tr');
    var archivBackBtn = row.find(".archivEnableOrDisableBtn");
    
    if (_this.val().length > 0) {
        archivBackBtn.removeClass('disabled');
    } else {
        archivBackBtn.addClass('disabled');
    }
}

function recoveryArchivDailog(elem) {
    var _this = $(elem);
    var row = _this.closest('tr');
    var archivId = row.find("#archivId").val();
    var departmentIds = $("#departmentId").val();
    if (archivId.length > 0 && departmentIds != null) {
        var _checkPlanCurrentMonthDay = checkPlanCurrentMonthDay();
        $.ajax({
            type: 'post',
            url: 'mdtime/recoveryArchivDialog',
            data: {"planLogId": archivId, "departmentId": departmentIds},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                var dialogName = '#recoveryArchivDialog';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                $(dialogName).empty().html('Одоо байгаа цагийн төлөвлөгөөг нөөцлөөд, архивласан бичлэг дарж орохыг анхаарна уу');
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: '350',
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $(dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.yes_btn, class: 'btn btn-sm green-meadow', click: function () {
                            Core.blockUI({
                                animate: true
                            });
                            if (_checkPlanCurrentMonthDay) {
                                sendArchivAjax($("#tnaTimeEmployeePlanForm").serialize() + '&description=Архив сэргээхэд архивласан&delete=1');
                            }
                            recoveryArchivData({"archivId":archivId, "departmentId": departmentIds});
                            $(dialogName).dialog('close');
                            Core.unblockUI();
                        }},
                        {text: data.no_btn, class: 'btn btn-sm blue-madison', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                });
                $(dialogName).dialog('open');
            },
            error: function () {
                new PNotify({
                    title: 'Error',
                    text: 'error',
                    type: 'error',
                    sticker: false
                });
            }
        }).done(function(){
            Core.unblockUI();
        });
    } else {
        new PNotify({
            title: 'Тайлбар',
            text: 'Архив сэргээх алба хэлтэсийг сонгоогүй байна',
            type: 'warning',
            sticker: false
        });
    }
}

function checkPlanCurrentMonthDay() {
    var response = false;
    $.ajax({
        type: 'post',
        url: 'mdtime/checkPlanCurrentMonthDay',
        data: {"planYear": $("#planYear").val(), "planMonth": $("#planMonth").val()},
        dataType: "json",
        async: false,
        success: function (data) {
            response = data;
        }
    });
    return response;
}

function recoveryArchivData(param) {
    var _result = false;
    $.ajax({
        type: 'post',
        url: 'mdtime/recoveryArchivData',
        data: param,
        dataType: "json",
        async: false,
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            if (data.status=='success') {
                getEmployeePlanList();
                _result = true;
            }
            new PNotify({
                title: data.title,
                text: data.message,
                type: data.status,
                sticker: false
            });
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
    return _result;
}

function saveFullTime(elem) {
    $.ajax({
        type: 'post',
        url: 'mdtime/saveFullTime',
        data: $("#tnaTimeEmployeePlanForm").serialize() + $("#tnaBalancePlanGridForm").serialize(),
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
            } else {
                new PNotify({
                    title: 'Алдаа',
                    text: data.message,
                    type: 'warning',
                    sticker: false
                });
            }
        },
        error: function () {
            new PNotify({
                title: 'Error',
                text: 'error',
                type: 'error',
                sticker: false
            });
        }
    }).done(function(){
        Core.unblockUI();
    });
}

function setArchivComboData(param) {
    $.ajax({
        type: 'post',
        url: 'mdtime/getArchivList',
        data: param,
        dataType: "json",
        async: false,
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $("#archivId").empty();
            for(var i = 0; i <= data.length; i++) {
                console.log(data[i]);
            }
        }
    }).done(function(){
        Core.unblockUI();
    });
}