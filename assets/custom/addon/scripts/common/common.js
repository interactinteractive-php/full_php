function ShowTokenLoginWin() {

    var mapForm = document.createElement('form');
    mapForm.target = 'Monpass';
    mapForm.method = 'POST'; 
    mapForm.action = monpassServerAddress + 'TokenLogin/Login';

    document.body.appendChild(mapForm);

    map = window.open("", "Monpass", "menubar=0,location=0,resizable=0,status=0,titlebar=0,toolbar=0,width=10,height=10,left=10000,top=10000,visible=none'");
    
    window.onmessage = function (e) {
        if (e.data) {
            var obj = JSON.parse(e.data);
            
            if (obj.Status == 'Success') {
                
                document.getElementById('seasonId').value = obj.SeasonId; 
                document.getElementById('etoken-form').submit(); 
                
            } else if (obj.Error !== undefined && obj.Error == '0') {
                alert("Бүртгэгдээгүй гэрчилгээ сонгосон байна");
            }
        } 
    };
    var timer = setInterval(function () {
        if (window.closed) {
            clearInterval(timer);
        }
    }, 1000);

    if (map) {
        mapForm.submit();
    } else {
        alert('You must allow popups for this map to work.');
    }
}
function ShowRegisterWin() {
    var mapForm = document.createElement("form");
    
    mapForm.target = 'Monpass';
    mapForm.method = 'POST'; 
    mapForm.action = monpassServerAddress + "CertificateRegister/Register";

    document.body.appendChild(mapForm);

    map = window.open("", "Monpass", "menubar=0,location=0,resizable=0,status=0,titlebar=0,toolbar=0,width=10,height=10,left=10000,top=10000,visible=none'");
    window.onmessage = function (e) {
        
        if (e.data) {
            
            var obj = JSON.parse(e.data);    
            PNotify.removeAll();
            
            if (obj.Status == 'Success') {
                $.ajax({
                    type: 'post',
                    url: 'token/registerMonpassUser',
                    data: {
                        monpassUserId: obj.UserId, 
                        certificateSerialNumber: obj.CertificateSerialNumber, 
                        tokenSerialNumber: obj.TokenSerialNumber
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        Core.unblockUI();
                    },
                    error: function () {
                        alert('Error');
                        Core.unblockUI();
                    }
                });
                
            } else if (obj.Error !== undefined) {
                new PNotify({
                    title: 'Error',
                    text: 'Error: ' + obj.Error,
                    type: 'error',
                    sticker: false
                });
            }
        } 
    };
    var timer = setInterval(function () {
        if (window.closed) {
            clearInterval(timer);
        }
    }, 1000);

    if (map) {
        mapForm.submit();
    } else {
        alert('You must allow popups for this map to work.');
    }
}

var separation = '';
function InitTree(data, parent_id, separator) {
    var tree = '';
    for (var i = 0; i < data.length; i++) {
        if (data[i]['PARENT_DEPARTMENT_ID'] === parent_id) {
            childNode = InitTree(data, data[i]['DEPARTMENT_ID'], (separation + '--- '));
            tree += '<option value="' + data[i]['DEPARTMENT_ID'] + '">' + separator + data[i]['DEPARTMENT_NAME'] + '</option>' + childNode;
        }
        separation = separator;
    }
    return tree;
}

function commonSelectableGrid(metaCode, chooseType, elem, params, funcName) {
    var funcName = typeof funcName === 'undefined' ? 'selectableCommonDataGrid' : funcName;
    var $dialogName = 'dialog-commonselectable';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'mdmetadata/commonSelectableGrid',
        data: {metaCode: metaCode, chooseType: chooseType, params: params},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                target: 'body',
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
                width: 1100,
                height: "auto",
                modal: true,
                position: {my: 'top', at: 'top+50'},
                close: function () {
                    $("#" + $dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.addbasket_btn, class: 'btn green-meadow btn-sm pull-left', click: function () {
                            basketCommonSelectableDataGrid();
                        }},
                    {text: data.choose_btn, class: 'btn blue btn-sm', click: function () {
                            if (typeof (window[funcName]) === 'function') {
                                window[funcName](metaCode, chooseType, elem, params);
                            } else {
                                alert('Function undefined error: ' + funcName);
                            }
                            var countBasketList = $('#commonSelectableBasketDataGrid').datagrid('getData').total;
                            if (countBasketList > 0) {
                                $("#" + $dialogName).dialog('close');
                            }
                        }},
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            $.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax();
    });
}

function proccessRenderPopup(windowId, elem) {
    var $parent = $(windowId).parent();
    var $processPopupDtlTd = $(elem).parent();
    $parent.css('position', 'static');
    $parent.find("div.center-sidebar").css('position', 'static');
    $parent.parent().css('overflow', 'inherit');
    
    var $processChildDtlTd = $(elem).closest('td');
    $processChildDtlTd.closest("div.col-md-12").css('position', 'static');
    
    var hideSaveButton = '';
    if (typeof $(elem).closest('table.bprocess-table-dtl').attr('data-popup-ignore-save-button') !== 'undefined' 
        && $(elem).closest('table.bprocess-table-dtl').attr('data-popup-ignore-save-button') == '1') {
        hideSaveButton = ' hide';
    }
    
    var $dialogName = 'div.sidebarDetailSection';
    var $dialog = $($dialogName, $processPopupDtlTd);
    
    $dialog.dialog({
        cache: false,
        resizable: true,
        appendTo: $processPopupDtlTd,
        bgiframe: true,
        autoOpen: false,
        title: 'More',
        width: 550,
        height: 'auto',
        maxHeight: 650,
        modal: true,
        closeOnEscape: isCloseOnEscape, 
        buttons: [
            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm'+hideSaveButton, click: function () {
                $dialog.dialog('close');
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": false,
        "collapsable": false,
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
    $dialog.dialog('open');
    
    /*var $dropDown = $dialog.find('select.dropdownInput');
    $dropDown.select2({
        allowClear: true,
        dropdownAutoWidth: true, 
        closeOnSelect: false, 
        escapeMarkup: function(markup) {
            return markup;
        }
    });
    
    Core.initBPInputType($dialog);
    */
}

function proccessRenderSidebar(windowId, elem) {
    var selectedTR = elem;

    if (selectedTR.find("td").length > 1) {
//        var offSet = selectedTR.closest("table").offset();
//        if(selectedTR.closest("fieldset").length > 0) {
//            offSet = offSet.top - 298;
//        } else
//            offSet = offSet.top - 225;
//          css("margin-top", Math.ceil(offSet)+"px")

        $(".right-sidebar-content", windowId).find("div.sidebarDetailSection").empty();
        selectedTR.find('td:last-child').find('span.sidebar_content_group').each(function () {
            var _this = $(this);
            var sidebarTR = '';

            selectedTR.find('td:last-child').find('span.sidebar_content_data_' + _this.attr("id")).each(function () {
                var _thisChild = $(this), trHide = '';

                if (_thisChild.hasClass('found_disable'))
                    $(this).find('.input_html').find('input').prop("readonly", true);
                if (_thisChild.hasClass('found_hide'))
                    trHide = " class='hide'";

                sidebarTR += "<tr" + trHide + ">" +
                        "<td style='width: 150px;' class='left-padding'>" + $(this).find('.input_label_txt').html() + "</td>" +
                        "<td>" + $(this).find('.input_html').html() + "</td>" +
                        "</tr>";
            });
            _this.find(".grid-row-content").find("table tbody").empty().append(sidebarTR);
            $(".right-sidebar-content", windowId).find("div.sidebarDetailSection").append(_this.html());
        });

        Core.initAjax();
    }
}
function showRenderSidebar(windowId, dataTable) {
    $(".stoggler", windowId).on("click", function () {
        var dataTableCheck = typeof dataTable;
        var _thisToggler = $(this);
        var centersidebar = $(".center-sidebar", windowId);
        var rightsidebar = $(".right-sidebar", windowId);
        var rightsidebarstatus = rightsidebar.attr("data-status");
        if (rightsidebarstatus === "closed") {
            centersidebar.removeClass("col-md-12").addClass("col-md-8");
            rightsidebar.addClass("col-md-4").css("margin-top: 18px;");
            rightsidebar.find(".glyphicon-chevron-right").parent().hide();
            rightsidebar.find(".glyphicon-chevron-left").hide();
            rightsidebar.find(".right-sidebar-content").show();
            rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn();
            rightsidebar.find(".glyphicon-chevron-right").fadeIn();
            if (dataTableCheck !== 'undefined')
                dataTable.fnAdjustColumnSizing();
            rightsidebar.attr('data-status', 'opened');
            _thisToggler.addClass("sidebar-opened");
        } else {
            rightsidebar.find(".glyphicon-chevron-right").hide();
            rightsidebar.find(".glyphicon-chevron-right").parent().hide();
            rightsidebar.find(".right-sidebar-content").hide();
            centersidebar.removeClass("col-md-8").addClass("col-md-12");
            rightsidebar.removeClass("col-md-4");
            rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn();
            rightsidebar.find(".glyphicon-chevron-left").fadeIn();
            if (dataTableCheck !== 'undefined')
                dataTable.fnAdjustColumnSizing();
            rightsidebar.attr('data-status', 'closed');
            _thisToggler.removeClass("sidebar-opened");
        }
    });
//    $(".stoggler", windowId).trigger('click');
    $(".stoggler", windowId).on("mouseover", function () {
        $(this).css({
            "background-color": "rgba(230, 230, 230, 0.80)",
            "border-right": "1px solid rgba(230, 230, 230, 0.80)"
        });
    });
    $(".stoggler", windowId).on("mouseleave", function () {
        $(this).css({
            "background-color": "#FFF",
            "border-right": "#FFF"
        });
    });
}
function showRenderSidebarNoTrigger(windowId, dataTable) {
    var dataTableCheck = typeof dataTable;
    var _thisToggler = $(".stoggler", windowId);
    var centersidebar = $(".center-sidebar", windowId);
    var rightsidebar = $(".right-sidebar", windowId);
    var rightsidebarstatus = rightsidebar.attr("data-status");
    if (rightsidebarstatus === "closed") {
        centersidebar.removeClass("col-md-12").addClass("col-md-9");
        rightsidebar.addClass("col-md-3").css("margin-top: 18px;");
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".glyphicon-chevron-left").hide();
        rightsidebar.find(".right-sidebar-content").show();
        rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn();
        rightsidebar.find(".glyphicon-chevron-right").fadeIn();
        if (dataTableCheck !== 'undefined')
            dataTable.fnAdjustColumnSizing();
        rightsidebar.attr('data-status', 'opened');
        _thisToggler.addClass("sidebar-opened");
    } else {
        rightsidebar.find(".glyphicon-chevron-right").hide();
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".right-sidebar-content").hide();
        centersidebar.removeClass("col-md-9").addClass("col-md-12");
        rightsidebar.removeClass("col-md-3");
        rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn();
        rightsidebar.find(".glyphicon-chevron-left").fadeIn();
        if (dataTableCheck !== 'undefined')
            dataTable.fnAdjustColumnSizing();
        rightsidebar.attr('data-status', 'closed');
        _thisToggler.removeClass("sidebar-opened");
    }
    $(".stoggler", windowId).on("mouseover", function () {
        $(this).css({
            "background-color": "rgba(230, 230, 230, 0.80)",
            "border-right": "1px solid rgba(230, 230, 230, 0.80)"
        });
    });
    $(".stoggler", windowId).on("mouseleave", function () {
        $(this).css({
            "background-color": "#FFF",
            "border-right": "#FFF"
        });
    });
}

function ShowFingerLoginWin(elem) {
    Core.blockUI({
        boxed: true,
        message: 'Loading...'
    });

    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        // Let us open a web socket
        var ws = new WebSocket("ws://localhost:2801/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"finger_scan", "dateTime":"' + currentDateTime + '"}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (jsonData.status == 'success') {
                var $details = jsonData.details;
                if ($details.length > 0) {
                    $.each($details, function (i, r) {
                        switch (r['key']) {
                            case 'registernum':
                                $.ajax({
                                    type: 'post',
                                    url: 'login/runCustom',
                                    data: {
                                        registerNumber: r['value'],
                                    },
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({ message: 'Loading...', boxed: true });
                                    },
                                    success: function (response) {
                                        if (typeof response['status'] !== 'undefined' && response['status'] === 'success') {
                                            var $fingerForm = $('#fingerSignForm');
                                            $fingerForm.find('input[name="user_name"]').val(response.user_name);
                                            $fingerForm.find('input[name="pass_word"]').val(response.pass_word);
                                            $fingerForm.find('input[name="csrf_token"]').val(response.csrf_token);
                                            $fingerForm.find('.btn').trigger('click');
                                        } else {

                                            Core.unblockUI();
                                            new PNotify({
                                                title: "Error",
                                                text: response.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                    },
                                    error: function (jqXHR, exception) {
                                        Core.showErrorMessage(jqXHR, exception);
                                        Core.unblockUI();
                                    }
                                });
                            break;
                        }
                    });
                } else {
                    new PNotify({
                        title: "Error",
                        text: 'Өгөгдөл олдсонгүй.',
                        type: 'error',
                        sticker: false
                    });

                    return false;
                }
            } else {
                
                var resultJson = {
                    Status: 'Error',
                    Error: (jsonData.message !== 'undefined') ? jsonData.message : 'Амжилтгүй боллоо',
                }
                
                new PNotify({
                    title: (jsonData.status !== 'undefined') ? jsonData.status : 'Error',
                    text: (jsonData.description !== 'undefined') ? jsonData.description : 'Амжилтгүй боллоо',
                    type: (jsonData.status !== 'undefined') ? jsonData.status : 'error',
                    sticker: false
                });

                console.log(JSON.stringify(resultJson));
            }
        };

        ws.onerror = function (event) {
            console.log(event);
            var resultJson = {
                type: 'Error',
                message: plang.get('client_not_working'),
                description: plang.get('client_not_working'),
                error: event.code
            }
                        
            if (typeof saveFptLog !== 'undefined' && saveFptLog === '1') {
                var bpUniq = $(elem).closest('div.xs-form').attr('data-bp-uniq-id');
                if (typeof window['bpSaveFptLog_' + bpUniq] === 'function') {
                    window['bpSaveFptLog_' + bpUniq](resultJson);
                }
            }

            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: plang.get('client_not_working'),
                type: 'error',
                sticker: false
            });
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    } else {

        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }

        new PNotify({
            title: "Error",
            text: 'WebSocket NOT supported by your Browser!',
            type: 'error',
            sticker: false
        });
        
    }

    Core.unblockUI();
}