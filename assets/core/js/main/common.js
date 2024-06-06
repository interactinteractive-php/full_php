var sysConfigTmpObj = {};

function getConfigValue(key, criteria) {
    var key = key.toLowerCase();
    var postData = {key: key}, tmpCriteria = '';
    
    if (typeof criteria != 'undefined' && criteria) {
        postData.criteria = criteria;
        tmpCriteria = criteria.toLowerCase();
    }
    
    var tmpKey = key + '' + tmpCriteria;
    
    if (sysConfigTmpObj.hasOwnProperty(tmpKey)) {
        
        var result = sysConfigTmpObj[tmpKey];
        
    } else {
        var response = $.ajax({
            type: 'post',
            url: 'mdconfig/getConfigValueFromCache', 
            data: postData,
            dataType: 'json',
            async: false
        });
        var result = response.responseJSON;
        sysConfigTmpObj[tmpKey] = result;
    }

    return result;
}

function ShowTokenLoginWin() {

    var mapForm = document.createElement('form');
    var monpassServerAddress = getConfigValue('monpassServerAddress');
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
    var monpassServerAddress = getConfigValue('monpassServerAddress');
    
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
                        Metronic.blockUI({
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
                        Metronic.unblockUI();
                    },
                    error: function () {
                        alert('Error');
                        Metronic.unblockUI();
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
            Metronic.blockUI({
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
        Metronic.initAjax();
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
            {text: save_btn, class: 'btn green-meadow btn-sm'+hideSaveButton, click: function () {
                $dialog.dialog('close');
            }},
            {text: close_btn, class: 'btn blue-madison btn-sm', click: function () {
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
    
    Metronic.initBPInputType($dialog);
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

        Metronic.initAjax();
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

function ShowFingerLogin() {
    var $dialogConfirm = 'dialog-confirm-' + danUniqId(1);
    if (!$("#" + $dialogConfirm).length) {
        $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
    }
    
    var $uniqId = danUniqId(1);
    $("#" + $dialogConfirm).empty().append(
            '<div class="input-group input-group-criteria" id="bp-window-'+ $uniqId +'" style="float: left;">' + 
                '<input type="text" name="temp-stateRegNumber" class="form-control form-control-sm stringInit" data-path="temp-stateRegNumber" data-field-name="stateRegNumber" value="" data-isclear="0" placeholder="Нэвтрэх нэр оруулна уу" data-regex="^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ]{2}[0-9]{8}$" data-regex-message="" data-inputmask-regex="^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ]{2}[0-9]{8}$">' + 
            '</div>');
    $("#" + $dialogConfirm).dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Нэвтрэх хэсэг',
        width: 450,
        height: "auto",
        modal: true,
        close: function() {
            $("#" + $dialogConfirm).empty().dialog('destroy').remove();
        },
        buttons: [{
                text: 'Нотариатч хурууны хээ уншуулах',
                class: 'btn btn-primary btn-sm',
                click: function() {
                    var $stateRegNumber = $("#" + $dialogConfirm).find('input[data-path="temp-stateRegNumber"]').val();
                    if (!$stateRegNumber) {
                        new PNotify({
                            title: 'Warning',
                            text: 'Хэрэглэгчийн мэдээллийг гүйцэт оруулна уу?',
                            type: 'warning',
                            sticker: false
                        });
                        return;
                    }

                    if ("WebSocket" in window) {
                        console.log("WebSocket is supported by your Browser!");
                        // Let us open a web socket
                        var ws = new WebSocket("ws://localhost:58324/socket");

                        ws.onopen = function() {
                            var currentDateTime = GetCurrentDateTime();
                            ws.send('{"command":"finger_image", "dateTime":"' + currentDateTime + '", details: []}');
                        };

                        ws.onmessage = function(evt) {
                            var received_msg = evt.data;
                            var jsonData = JSON.parse(received_msg);
                            if (jsonData.status == 'success') {

                                var $fingerBase = jsonData.details[0].value;
                                $.ajax({
                                    type: 'post',
                                    url: 'login/runFinger',
                                    data: { 
                                        operatorFinger: $fingerBase, 
                                        registerNumber: $stateRegNumber,
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({target: '#' + $dialogConfirm, message: 'Loading...', boxed: true});
                                    },
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            var $form = $('.login-form');
                                            $form.find('input[name="user_name"]').val(response.username);
                                            $form.find('input[name="pass_word"]').val(response.pass_word);
                                            $form.find('input[name="isHash"]').val('1');
                                            $form.find('button[type="submit"]').trigger('click');
                                        } else {
                                            Core.unblockUI('#' + $dialogConfirm);
                                            new PNotify({
                                                title: response.status,
                                                text: response.message,
                                                type: response.status,
                                                sticker: false
                                            });
                                        }
                                    },
                                    error: function(jqXHR, exception) {
                                        Core.unblockUI('#' + $dialogConfirm);
                                        Core.showErrorMessage(jqXHR, exception);
                                    }
                                });

                            } else {
                                var resultJson = {
                                    Status: 'Error',
                                    Error: jsonData.message
                                }

                                new PNotify({
                                    title: jsonData.status,
                                    text: (jsonData.description !== 'undefined') ? jsonData.description : 'Амжилтгүй боллоо',
                                    type: jsonData.status,
                                    sticker: false
                                });
                                console.log(JSON.stringify(resultJson));
                            }
                        };

                        ws.onerror = function(event) {
                            var resultJson = {
                                Status: 'Error',
                                Error: 'Клент ажиллуулахад алдаа гарлаа.'
                            }
                            PNotify.removeAll();
                            new PNotify({
                                title: 'warning',
                                text: 'Клент ажиллуулахад алдаа гарлаа.',
                                type: 'warning',
                                sticker: false
                            });
                            console.log(JSON.stringify(resultJson));
                        };

                        ws.onclose = function() {
                            console.log("Connection is closed...");
                        };
                    } else {
                        var resultJson = {
                            Status: 'Error',
                            Error: "WebSocket NOT supported by your Browser!"
                        }

                        console.log(JSON.stringify(resultJson));
                    }

                }
            }
        ]
    });
    $("#" + $dialogConfirm).dialog('open');
}

function b64DecodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}

function ShowDanLogin() {
    var state = danUniqId(1);
    var new_window = window.open('/token/dan?state=' + state, 'Dan', 'width=600,height=600');

    localStorage.setItem('dan-event', "call");
    localStorage.setItem('dan_state', state);

    if (localStorage.getItem('dan-event') == 'call') {
        window.addEventListener('storage', function (e) {
            localStorage.setItem('dan-event', true);
            state = localStorage.getItem('dan_state');
            if (typeof localStorage[state] !== 'undefined') {
                if (localStorage[state]) {
                    $.ajax({
                        type: 'post',
                        url: 'login/runDan',
                        data: {
                            code: localStorage.getItem(state)
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({ message: 'Loading...', boxed: true });
                        },
                        success: function (response) {
                            location.reload();
                        },
                        error: function (jqXHR, exception) {
                            location.reload();
                            Core.unblockUI();
                        }
                    }).done(function () {
                        localStorage.clear();
                    });
                }
            }
        });
    }
}

function ShowEhalamjLogin() {
    var state = danUniqId(1);
    var new_window = window.open('/token/ekhalamj?state=' + state, 'Dan', 'width=600,height=600');

    localStorage.setItem('dan-event', "call");
    localStorage.setItem('dan_state', state);

    if (localStorage.getItem('dan-event') == 'call') {
        window.addEventListener('storage', function (e) {
            localStorage.setItem('dan-event', true);
            state = localStorage.getItem('dan_state');
            if (typeof localStorage[state] !== 'undefined') {
                if (localStorage[state]) {
                    $.ajax({
                        type: 'post',
                        url: 'login/runKhalamj',
                        data: {
                            code: localStorage.getItem(state)
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({ message: 'Loading...', boxed: true });
                        },
                        success: function (response) {
                            location.reload();
                        },
                        error: function (jqXHR, exception) {
                            location.reload();
                            Core.unblockUI();
                        }
                    }).done(function () {
                        localStorage.clear();
                    });
                }
            }
        });
    }
}

function ShowDanLoginCustomer() {
    var state = danUniqId(1);
    var new_window = window.open('/token/dan?state=' + state, 'Dan', 'width=600,height=600');

    localStorage.setItem('dan-event', "call");
    localStorage.setItem('dan_state', state);

    if (localStorage.getItem('dan-event') == 'call') {
        window.addEventListener('storage', function (e) {
            localStorage.setItem('dan-event', true);
            state = localStorage.getItem('dan_state');
            if (typeof localStorage[state] !== 'undefined') {
                if (localStorage[state]) {
                    danData = JSON.parse(b64DecodeUnicode(localStorage.getItem(state)));
                    if (typeof danData !== 'undefined' &&
                        typeof danData['1'] !== 'undefined' &&
                        typeof danData['1']['services'] !== 'undefined' &&
                        typeof danData['1']['services']['WS100101_getCitizenIDCardInfo'] !== 'undefined' &&
                        typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['response'] !== 'undefined' &&
                        typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['regnum'] !== 'undefined') {
                        $.ajax({
                            type: 'post',
                            url: 'login/runDanCustomer',
                            data: {
                                registerNumber: danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['regnum'],
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                Core.blockUI({ message: 'Loading...', boxed: true });
                            },
                            success: function (response) {
                                location.reload();
                            },
                            error: function (jqXHR, exception) {
                                location.reload();
                                Core.unblockUI();
                            }
                        }).done(function () {
                            localStorage.clear();
                        });
                    }
                }
            }
        });
    }
}

function GetCurrentDateTime() {
    var today = new Date();
    var dd = today.getDate();
    var MM = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear;
    var HH = today.getHours();
    var mm = today.getMinutes();
    var ss = today.getSeconds();

    if (dd < 10) { dd = '0' + dd }
    if (MM < 10) { MM = '0' + MM }
    if (HH < 10) { HH = '0' + HH }
    if (mm < 10) { mm = '0' + mm }
    if (ss < 10) { ss = '0' + ss }

    var datetime = yyyy + "/" + MM + "/" + dd + " " + HH + ":" + mm + ":" + ss;
    return datetime;
}

function danUniqId(prefix) {
    var d = new Date().getTime();
    d += (parseInt(Math.random() * 100)).toString();
    if (undefined === prefix) {
        prefix = 'uid-';
    } else if (prefix === 'no') {
        prefix = '';
    }
    d = prefix + d;
    return d;
}

function ShowPhonenumberLoginWin(elem) {
    var isCheck = $(elem).is(":checked");
    $('.sign-user').hide();
    $('.sign-phone').hide();
    $('.sign-user').find('input[name="user_name"], input[name="pass_word"]').val('');
    if (isCheck) {
        $('.sign-user').find('input[name="user_name"], input[name="pass_word"]').val('123');
        $('.sign-phone').show();
    } else {
        $('.sign-user').show();
    }
}