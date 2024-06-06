var isPosAddonScript = true;
var posNfcCheckInterval;

function posApiSendData(dataViewId, row) {
    var $dialogName = 'dialog-posapi-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(plang.get('POS_0001'));
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Confirm',
        width: 400,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                $.ajax({
                    type: 'post',
                    url: 'mdpos/posApiSendDataByStore',
                    data: {storeId: row.storeid}, 
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function(data) {
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Info',
                            text: data.message, 
                            type: 'info', 
                            sticker: false, 
                            hide: true, 
                            addclass: pnotifyPosition,
                            delay: 1000000000
                        });
                        
                        dataViewReload(dataViewId);
                        $dialog.dialog('close');
                        Core.unblockUI();
                    },
                    error: function() {
                        alert('Error');
                    }
                });
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });

    $dialog.dialog('open');
}

function posDiscountDrugImport(dataViewId) {
    var $dialogName = 'dialog-pos-discountdrug';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(plang.get('POS_0002'));
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Confirm',
        width: 420,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('view_btn'), class: 'btn btn-sm', click: function () {
                $.ajax({
                    type: 'post',
                    url: 'mdpos/posDiscountDrugImportView',
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function(data) {

                        var $emdList = '<table class="table"><thead><tr>';
                        $emdList += '<td>№</td>';
                        $emdList += '<td id>Хөнгөлөлтийн дугаар</td>';
                        $emdList += '<td packgroup>Эмийн багцын дугаар</td>';
                        $emdList += '<td tbltnamemon>Монгол нэршил</td>';
                        $emdList += '<td tbltnameinter>ОУ-ын нэршил</td>';
                        $emdList += '<td tbltnamesales>Худалдааны нэршил</td>';
                        $emdList += '<td tbltsizemixture>Хэмжээ</td>';
                        $emdList += '<td tblttypename>Хэлбэрийн нэр</td>';
                        $emdList += '<td tbltbarcode>Баркод</td>';
                        $emdList += '<td tbltpackingcnt>Савлалтын тоо</td>';
                        $emdList += '<td tbltunitprice>Эмийн ширхгийн дээд үнэ</td>';
                        $emdList += '<td tbltunitdisamt>Эмийн ширхгийн хөнгөлөх дүн</td>';
                        $emdList += '<td tbltdiscountamt>Хөнгөлөлтийн дүн</td></tr></thead><tbody>';

                        if (data.status == 'success') {
                            for (var i = 0; i < data.data.length; i++) {
                                $emdList += '<tr><td>'+(i + 1)+'</td>';
                                $emdList += '<td>'+data.data[i]['id']+'</td>';
                                $emdList += '<td packgroup>'+data.data[i]['packGroup']+'</td>';
                                $emdList += '<td tbltnamemon>'+data.data[i]['tbltNameMon']+'</td>';
                                $emdList += '<td tbltnameinter>'+data.data[i]['tbltNameInter']+'</td>';
                                $emdList += '<td tbltnamesales>'+data.data[i]['tbltNameSales']+'</td>';
                                $emdList += '<td tbltsizemixture>'+data.data[i]['tbltSizeMixture']+'</td>';
                                $emdList += '<td tblttypename>'+data.data[i]['tbltTypeName']+'</td>';
                                $emdList += '<td tbltbarcode>'+data.data[i]['tbltBarCode']+'</td>';
                                $emdList += '<td tbltpackingcnt>'+data.data[i]['tbltPackingCnt']+'</td>';
                                $emdList += '<td tbltunitprice>'+data.data[i]['tbltUnitPrice']+'</td>';
                                $emdList += '<td tbltunitdisamt>'+data.data[i]['tbltUnitDisAmt']+'</td>';
                                $emdList += '<td tbltdiscountamt>'+data.data[i]['tbltDiscountAmt']+'</td></tr>';
                            }
                            $emdList += '</tbody></table>';

                            var $dialogNameView = 'dialog-pos-discountdrug-view';
                            if (!$("#" + $dialogNameView).length) {
                                $('<div id="' + $dialogNameView + '"></div>').appendTo('body');
                            }
                            var $dialogView = $('#' + $dialogNameView);
                        
                            $dialogView.empty().append($emdList);
                            $dialogView.dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: true,
                                autoOpen: false,
                                title: 'ЭМД жагсаалт',
                                width: 550,
                                height: 'auto',
                                modal: true,
                                close: function () {
                                    $dialogView.empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialogView.dialog('close');
                                    }}
                                ]
                              }).dialogExtend({
                                closable: true,
                                maximizable: true,
                                minimizable: true,
                                collapsable: true,
                                dblclick: "maximize",
                                minimizeLocation: "left",
                                icons: {
                                close: "ui-icon-circle-close",
                                maximize: "ui-icon-extlink",
                                minimize: "ui-icon-minus",
                                collapse: "ui-icon-triangle-1-s",
                                restore: "ui-icon-newwin",
                                },
                              });
                                        
                                            $dialogView.dialog('open');                            
                              $dialogView.dialogExtend("maximize");                     
                        }
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Info',
                            text: data.message, 
                            type: 'info', 
                            sticker: false, 
                            hide: true,
                            addclass: pnotifyPosition,
                            delay: 1000000000
                        });
                        $dialog.dialog('close');
                        Core.unblockUI();
                    },
                    error: function() {
                        alert('Error');
                    }
                });
            }},
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                $.ajax({
                    type: 'post',
                    url: 'mdpos/posDiscountDrugImport',
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function(data) {
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Info',
                            text: data.message, 
                            type: 'info', 
                            sticker: false, 
                            hide: true,
                            addclass: pnotifyPosition,
                            delay: 1000000000
                        });
                        
                        dataViewReload(dataViewId);
                        $dialog.dialog('close');
                        Core.unblockUI();
                    },
                    error: function() {
                        alert('Error');
                    }
                });
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });

    $dialog.dialog('open');
}

function posTalonNotLotteryPrint(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    
    paramData['noLotteryNumber'] = 1;
    
    $.ajax({
        type: 'post',
        url: 'mdpos/printInvoice',
        data: paramData, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Printing...',
                boxed: true
            });
        },
        success: function(data) {
            
            PNotify.removeAll();
            
            if (data.status == 'success') {
                
                var posPrintElementClass = 'pos-nolottery-print';
                if (!$('.' + posPrintElementClass).length) {
                    $('<div class="' + posPrintElementClass + ' display-none"></div>').appendTo('body');
                }
                var $posPrintElement = $('.' + posPrintElementClass);
        
                $posPrintElement.html(data.printData).promise().done(function() {
                    $posPrintElement.printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: data.css,
                        removeInline: false
                    });
                });
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status, 
                    sticker: false
                });
            }
            
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
            Core.unblockUI();
        }
    });
}

function printPosByInvoiceId(invoiceId, response, isMulti, reportTemplateId) {
    var isMultiData = '';
    if (typeof isMulti !== 'undefined') {
        isMultiData = 'multiple'
    }
    if (typeof reportTemplateId === 'undefined') {
        reportTemplateId = ''
    }
    
    $.ajax({
        type: 'post',
        url: 'mdpos/printInvoiceResponse',
        data: {
            id: invoiceId, 
            noLotteryNumber: 0, 
            responseData: response, 
            multi: isMultiData, 
            reportTemplateId: reportTemplateId
        }, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: isMultiData ? 'Мэйл илгээж байна...' : 'Printing...',
                boxed: true
            });
        },
        success: function(data) {
            PNotify.removeAll();
            Core.unblockUI();
            
            if (isMultiData) {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status, 
                    sticker: false
                });                
                return;
            }
            
            if (data.status == 'success') {
                
                var posPrintElementClass = 'pos-lottery-print';
                if (!$('.' + posPrintElementClass).length) {
                    $('<div class="' + posPrintElementClass + ' display-none"></div>').appendTo('body');
                }
                var $posPrintElement = $('.' + posPrintElementClass);
        
                $posPrintElement.html(data.printData).promise().done(function() {
                    $posPrintElement.printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: data.css,
                        removeInline: false
                    });
                });
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status, 
                    sticker: false
                });
            }
            
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
            Core.unblockUI();
        }
    });
}

function posPrintSocialPaySetlement(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    $.ajax({
        type: 'post',
        url: 'mdpos/socialPaySetlement',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...', 
                boxed: true
            });            
        },
        success: function(data) {

            PNotify.removeAll();
            if (data.status == 'success') {
                var $dialogName = 'pos-preview-print-socialpay-setlement';

                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);                        
                
                $dialog.html('<table border="0" style="width: 100%; font-family: Tahoma; font-size: 12px;">'+
                    '<tbody>'+
                      '<tr>'+
                        '<td colspan="2" style="width: 100%; text-align: center;">====== Social Pay ======<br/><br/></td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td style="width:50%; text-align: right;">Гүйлгээний тоо:</td>'+
                        '<td style="width:50%">'+data.message.response.count+'</td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td style="width:50%; text-align: right;">Нийт дүн:</td>'+
                        '<td style="width:50%">'+pureNumberFormat(data.message.response.amount)+'</td>'+
                      '</tr>'+
                    '</tbody></table>').promise().done(function() {

                    $dialog.printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: data.css,
                        removeInline: false
                    });
                });

            } else {

                new PNotify({
                    title: 'Warning',
                    text: data.message,
                    type: 'warning',
                    sticker: false, 
                    addclass: 'pnotify-center'
                });
            }
            Core.unblockUI();
        }
    });         
}

function posPrintSetlement(elem, processMetaDataId, dataViewId, selectedRow, paramData) {

    if ("WebSocket" in window) {
        $.ajax({
            type: 'post',
            data: {bankType:'glmt'},
            url: 'mdpos/getPosTerminalId',
            success: function(data) {
                
                if (data === 'empty') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Bank terminal error',
                        text: 'Terminal ID хоосон байна!',
                        type: 'error', 
                        sticker: false, 
                        addclass: 'pnotify-center'
                    });     
                    return;
                }
                                
                console.log("WebSocket is supported by your Browser!");
                // Let us open a web socket
                var ws = new WebSocket("ws://localhost:58324/socket");

                ws.onopen = function() {
                    var currentDateTime = GetCurrentDateTime();
                    ws.send('{"command":"bank_terminal_pos_settlement", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "glmt"},{"key": "terminalid", "value": "' + data + '"}]}');
                };

                ws.onmessage = function(evt) {
                    var received_msg = evt.data;
                    var jsonData = JSON.parse(received_msg);

                    if (jsonData.status == 'success') {
                        var getParse = JSON.parse(jsonData.details[0].value);
                        var $dialogName = 'pos-preview-print-setlement';
                        
                        if (getParse.ReceiptData == '') {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Нэгтгэл хийх гүйлгээ байхгүй',
                                type: 'warning', 
                                sticker: false, 
                                addclass: 'pnotify-center'
                            });       
                            return;
                        }
                        
                        if (!$("#" + $dialogName).length) {
                            $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo('body');
                        }
                        var $dialog = $('#' + $dialogName);
                        $dialog.html(getParse.ReceiptData.replace(/(?:\r\n|\r|\n)/g, '<br>')).promise().done(function() {

                            $dialog.printThis({
                                debug: false,
                                importCSS: false,
                                printContainer: false,
                                dataCSS: data.css,
                                removeInline: false
                            });
                        });
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Bank terminal error',
                            text: jsonData.description,
                            type: 'error', 
                            sticker: false, 
                            addclass: 'pnotify-center'
                        });                        
                    }
                };

                ws.onerror = function(event) {
                    var resultJson = {
                        Status: 'Error',
                        Error: event.code
                    }
                    console.log(JSON.stringify(resultJson));
                };

                ws.onclose = function() {
                    console.log("Connection is closed...");
                };
            }
        });           
    } else {
        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }

        console.log(JSON.stringify(resultJson));
    }

}

function printsettlementkhaan(elem, processMetaDataId, dataViewId, selectedRow, paramData) {

    if ("WebSocket" in window) {
        $.ajax({
            type: 'post',
            data: {bankType:'khaan'},
            url: 'mdpos/getPosTerminalId',
            success: function(data) {
                
                if (data === 'empty') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Bank terminal error',
                        text: 'Terminal ID хоосон байна!',
                        type: 'error', 
                        sticker: false, 
                        addclass: 'pnotify-center'
                    });     
                    return;
                }
                                
                console.log("WebSocket is supported by your Browser!");
                // Let us open a web socket
                var ws = new WebSocket("ws://localhost:58324/socket");

                ws.onopen = function() {
                    var currentDateTime = GetCurrentDateTime();
                    ws.send('{"command":"bank_terminal_pos_settlement", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "databank"},{"key": "terminalid", "value": "' + data + '"}]}');
                };

                ws.onmessage = function(evt) {
                    var received_msg = evt.data;
                    var jsonData = JSON.parse(received_msg);

                    if (jsonData.status == 'success') {
                        var getParse = JSON.parse(jsonData.details[0].value);
                        var $dialogName = 'pos-preview-print-setlement';
                        
                        if (getParse.response.response_code == '330') {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Нэгтгэл хийх гүйлгээ байхгүй',
                                type: 'warning', 
                                sticker: false, 
                                addclass: 'pnotify-center'
                            });       
                            return;
                        }
                        
                        var setHtml = '<h3 style="margin-top:15px"><center>ХААН БАНК</center></h3>';
                        setHtml += '<div style="margin-top:15px">Terminal ID: '+data+'</div>';
                        setHtml += '<div style="margin-bottom:15px">Огноо: '+getParse.response.date+' '+getParse.response.time+'</div>';
                        setHtml += '<hr style="border-top: dotted 1px;"/>';
                        setHtml += '<div>Сеттлемент</div>';
                        setHtml += '<hr style="border-top: dotted 1px;"/>';
                        setHtml += '<table border="0" style="width: 100%;margin-top:15px;border-collapse: collapse">';
                        setHtml += '<tr style="">';
                        setHtml += '<td style="width:50%;text-align: left;padding:8px">Борлуулалт<br> Тоо</td>';
                        setHtml += '<td style="width:50%;text-align: right;padding:8px">Дүн</td></tr>';                        
                        setHtml += '<tr>';
                        setHtml += '<td style="width:50%;text-align: left;padding:8px">'+pureNumberFormat(getParse.response.sale_count)+'</td>';
                        setHtml += '<td style="width:50%;text-align: right;padding:8px">'+pureNumberFormat(getParse.response.sale_total.substr(0,10))+'₮</td></tr>';
                        setHtml += '</table>';                        
                        setHtml += '<hr style="border-top: dotted 1px;margin-top:10px"/>';
                        setHtml += '<table border="0" style="width: 100%;margin-top:10px;border-collapse: collapse">';
                        setHtml += '<tr><td style="width:50%;padding:8px">Буцаалт<br> Тоо</td>';
                        setHtml += '<td style="width:50%;text-align: right;padding:8px">Дүн</td></tr>';                        
                        setHtml += '<td style="width:50%;text-align: left;padding:8px">'+pureNumberFormat(getParse.response.void_count)+'</td>';
                        setHtml += '<td style="width:50%;text-align: right;padding:8px">'+pureNumberFormat(getParse.response.void_total.substr(0,10))+'₮</td></tr>';                        
                        setHtml += '</table>';                        
                        
                        if (!$("#" + $dialogName).length) {
                            $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo('body');
                        }
                        var $dialog = $('#' + $dialogName);
                        $dialog.html(setHtml).promise().done(function() {

                            $dialog.printThis({
                                debug: false,
                                importCSS: false,
                                printContainer: false,
                                dataCSS: "@page{margin-top: 0.5cm;margin-right: 0.5cm;margin-bottom: 0.5cm;margin-left: 0.5cm;size: A4 portrait;width: 100%;orientation: portrait}*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}*:before,*:after{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}body{margin: 0;padding: 0;line-height: 1.4em;font-size: 12px;font-family: \"Times Roman\", sans-serif;color: #000;width: 100%;-webkit-print-color-adjust: exact}table{border-collapse: collapse !important;font-size: 12px;border-color: grey;line-height: 1em}tr{page-break-inside:avoid;page-break-after:auto}td{page-break-inside:avoid;page-break-after:auto}thead{display: table-header-group}tbody{display: table-row-group}tfoot{display: table-footer-group}table thead th, table thead td, table tbody td, table tfoot td{padding: 2px 3px}table tbody td.bold{font-weight: bold}",
                                removeInline: false
                            });
                        });
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Bank terminal error',
                            text: jsonData.description,
                            type: 'error', 
                            sticker: false, 
                            addclass: 'pnotify-center'
                        });                        
                    }
                };

                ws.onerror = function(event) {
                    var resultJson = {
                        Status: 'Error',
                        Error: event.code
                    }
                    console.log(JSON.stringify(resultJson));
                };

                ws.onclose = function() {
                    console.log("Connection is closed...");
                };
            }
        });           
    } else {
        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }

        console.log(JSON.stringify(resultJson));
    }

}

function posPrintSetlementXac(elem, processMetaDataId, dataViewId, selectedRow, paramData) {

    if ("WebSocket" in window) {    
        console.log("WebSocket is supported by your Browser!");
        // Let us open a web socket
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function() {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"bank_terminal_pos_settlement", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "khas_paxA35"},{"key": "terminalid", "value": "123"}]}');
        };

        ws.onmessage = function(evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (jsonData.status == 'success') {
                var getParse = JSON.parse(jsonData.details[0].value);
                var $dialogName = 'pos-preview-print-setlement';

                if (getParse.Code != '0') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Нэгтгэл хийх гүйлгээ байхгүй',
                        type: 'warning', 
                        sticker: false, 
                        addclass: 'pnotify-center'
                    });       
                    return;
                }

                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                
                var setHtml = '<div>ОГНОО: '+getParse.SettlementDate+' '+getParse.SettlementTime+'</div>';
                    setHtml += '<h3 style="margin-top:15px"><center>XAC setlement report</center></h3>';
                setHtml += '<table border="1" style="width: 100%;margin-top:15px;border-collapse: collapse">';
                setHtml += '<tr style="background:#E5E5E5"><td style="width:40%;padding:8px">ГҮЙЛГЭЭНИЙ ТӨРӨЛ</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">ТОО</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">ДҮН</td></tr>';                        
                setHtml += '<tr><td style="width:40%;padding:8px">ХУДАЛДАН АВАЛТ</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(getParse.TotalCount)+'</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(getParse.TotalAmount)+'</td></tr>';                        
                setHtml += '<tr><td style="width:40%;padding:8px">QR</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(getParse.TotalQRCount)+'</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(getParse.TotalQRAmount)+'</td></tr>';                        
                setHtml += '<tr><td style="width:40%;padding:8px">БУЦААЛТ</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(getParse.TotalVoidCount)+'</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(getParse.TotalVoidAmount)+'</td></tr>';                        
                setHtml += '</table>';

                $dialog.html(setHtml).promise().done(function() {

                    $dialog.printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: "@page{margin-top: 0.5cm;margin-right: 0.5cm;margin-bottom: 0.5cm;margin-left: 0.5cm;size: A4 portrait;width: 100%;orientation: portrait}*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}*:before,*:after{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}body{margin: 0;padding: 0;line-height: 1.4em;font-size: 12px;font-family: \"Times Roman\", sans-serif;color: #000;width: 100%;-webkit-print-color-adjust: exact}table{border-collapse: collapse !important;font-size: 12px;border-color: grey;line-height: 1em}tr{page-break-inside:avoid;page-break-after:auto}td{page-break-inside:avoid;page-break-after:auto}thead{display: table-header-group}tbody{display: table-row-group}tfoot{display: table-footer-group}table thead th, table thead td, table tbody td, table tfoot td{padding: 2px 3px}table tbody td.bold{font-weight: bold}",
                        removeInline: false
                    });
                });
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Bank terminal error',
                    text: jsonData.description,
                    type: 'error', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });                        
            }
        };

        ws.onerror = function(event) {
            var resultJson = {
                Status: 'Error',
                Error: event.code
            }
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

function GetCurrentDateTimeTdb() {
    var today = new Date();
    var dd = today.getDate();
    var MM = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
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

function posPrintSetlementTDB(elem, processMetaDataId, dataViewId, selectedRow, paramData) {

    $.ajax({
        type: 'post',
        data: {bankType:'tdb'},
        url: 'mdpos/getPosTerminalId',
        success: function(data) {

            if (data === 'empty') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Bank terminal error',
                    text: 'Terminal ID хоосон байна!',
                    type: 'error', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });     
                return;
            }
            
            var response = $.ajax({
                type: 'post',
                url: 'http://127.0.0.1:8088/ecrt1000',
                data: {
                    hostIndex: 0,
                    operation: "BatchTotal"
                },
                dataType: 'json',
                async: false
            });
            var resultBatch = response.responseJSON;
            resultBatch = resultBatch.ecrResult;            

            if (!Number(resultBatch.BatchTotal)) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Нэгтгэл хийх гүйлгээ байхгүй',
                    type: 'warning', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });       
                return;
            }
            
            var response = $.ajax({
                type: 'post',
                url: 'http://127.0.0.1:8088/ecrt1000',
                data: {
                    hostIndex: 0,
                    operation: "Settlement"
                },
                dataType: 'json',
                async: false
            });
            var result = response.responseJSON;
            Core.unblockUI();

            if (result.ecrResult['RespCode'] == 00) {
                var $dialogName = 'pos-preview-print-tdbsetlement';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                var setHtml = '<div>ОГНОО: '+GetCurrentDateTimeTdb()+'</div>';
                    setHtml += '<h3 style="margin-top:15px"><center>TDB setlement report</center></h3>';
                setHtml += '<table border="1" style="width: 100%;margin-top:15px;border-collapse: collapse">';
                setHtml += '<tr style="background:#E5E5E5"><td style="width:40%;padding:8px">ГҮЙЛГЭЭНИЙ ТӨРӨЛ</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">ТОО</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">ДҮН</td></tr>';                        
                setHtml += '<tr><td style="width:40%;padding:8px">ХУДАЛДАН АВАЛТ</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(resultBatch.BatchTotal.substr(0,4))+'</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(resultBatch.BatchTotal.substr(4,10))+'</td></tr>';                        
                setHtml += '<tr><td style="width:40%;padding:8px">БУЦААЛТ</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(resultBatch.BatchTotal.substr(16,4))+'</td>';
                setHtml += '<td style="width:30%;text-align: right;padding:8px">'+pureNumberFormat(resultBatch.BatchTotal.substr(20,10))+'</td></tr>';                        
                setHtml += '</table>';

                $dialog.html(setHtml).promise().done(function() {

                    $dialog.printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: "@page{margin-top: 0.5cm;margin-right: 0.5cm;margin-bottom: 0.5cm;margin-left: 0.5cm;size: A4 portrait;width: 100%;orientation: portrait}*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}*:before,*:after{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}body{margin: 0;padding: 0;line-height: 1.4em;font-size: 12px;font-family: \"Times Roman\", sans-serif;color: #000;width: 100%;-webkit-print-color-adjust: exact}table{border-collapse: collapse !important;font-size: 12px;border-color: grey;line-height: 1em}tr{page-break-inside:avoid;page-break-after:auto}td{page-break-inside:avoid;page-break-after:auto}thead{display: table-header-group}tbody{display: table-row-group}tfoot{display: table-footer-group}table thead th, table thead td, table tbody td, table tfoot td{padding: 2px 3px}table tbody td.bold{font-weight: bold}",
                        removeInline: false
                    });
                });
            } else {
                PNotify.removeAll();
                new PNotify({
                  title: "Bank terminal error [TDB]",
                  text: "НЭГТГЭЛ ГҮЙЛГЭЭ АМЖИЛТГҮЙ: [" + result.ecrResult['RespCode'] + "]",
                  type: "error",
                  sticker: false,
                  addclass: "pnotify-center",
                });
            }      

//                console.log("WebSocket is supported by your Browser!");
//                // Let us open a web socket
//                var ws = new WebSocket("ws://localhost:58324/socket");
//
//                ws.onopen = function() {
//                    var currentDateTime = GetCurrentDateTime();
//                    ws.send('{"command":"bank_terminal_pos_settlement", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "tdb_paxs300"},{"key": "terminalid", "value": "' + data + '"}]}');
//                };
//
//                ws.onmessage = function(evt) {
//                    var received_msg = evt.data;
//                    var jsonData = JSON.parse(received_msg);
//
//                    if (jsonData.status == 'success') {
//                        var getParse = JSON.parse(jsonData.details[0].value);
//                        var $dialogName = 'pos-preview-print-setlement';
//                        
//                        if (getParse.code != '0') {
//                            PNotify.removeAll();
//                            new PNotify({
//                                title: 'Warning',
//                                text: 'Нэгтгэл хийх гүйлгээ байхгүй',
//                                type: 'warning', 
//                                sticker: false, 
//                                addclass: 'pnotify-center'
//                            });       
//                            return;
//                        }
//                        
//                        if (!$("#" + $dialogName).length) {
//                            $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo('body');
//                        }
//                        var transCount = 0;
//                        var $dialog = $('#' + $dialogName);
//                        var setHtml = '<div>ОГНОО: '+GetCurrentDateTimeTdb()+'</div>';
//                            setHtml += '<h3 style="margin-top:15px"><center>TDB setlement report</center></h3>';
//
//                        for (var i = 0; i < getParse.data.data.length; i++) {
//                            if (getParse.data.data[i].amount) {
//                                setHtml += '<table border="0" style="width: 100%;border-collapse: collapse;margin-top:7px">';
//                                setHtml += '<tr colspan="3"><td style="width:100%"><strong>ТӨРӨЛ: '+getParse.data.data[i].cardType+'</strong></td></tr>';
//                                setHtml += '<tr><td style="width:40%">ГҮЙЛГЭЭ</td>';
//                                setHtml += '<td style="width:30%;text-align: right;">ТОО</td>';
//                                setHtml += '<td style="width:30%;text-align: right;">ДҮН</td></tr>';
//                                setHtml += '<tr><td style="width:40%">ХУДАЛДАН АВАЛТ</td>';
//                                setHtml += '<td style="width:30%;text-align: right;">'+getParse.data.data[i].count+'</td>';
//                                setHtml += '<td style="width:30%;text-align: right;">'+(getParse.data.data[i].amount ? pureNumberFormat(getParse.data.data[i].amount) : 0)+'</td></tr>';
//                                setHtml += '</table><hr style="margin-top:7px" />';
//                                transCount += getParse.data.data[i].count;
//                            }
//                        }
//                        setHtml += '<h3 style="margin-top:15px">ЕРӨНХИЙ ДҮН</h3>';
//                        setHtml += '<table border="0" style="width: 100%;border-collapse: collapse;margin-top:7px">';
//                        setHtml += '<tr><td style="width:40%">ГҮЙЛГЭЭ</td>';
//                        setHtml += '<td style="width:30%;text-align: right;">ТОО</td>';
//                        setHtml += '<td style="width:30%;text-align: right;">ДҮН</td></tr>';                        
//                        setHtml += '<tr><td style="width:40%">ХУДАЛДАН АВАЛТ</td>';
//                        setHtml += '<td style="width:30%;text-align: right;">'+transCount+'</td>';
//                        setHtml += '<td style="width:30%;text-align: right;">'+pureNumberFormat(getParse.data.settledAmnt)+'</td></tr>';                        
//                        setHtml += '</table>';
//
//                        $dialog.html(setHtml).promise().done(function() {
//
//                            $dialog.printThis({
//                                debug: false,
//                                importCSS: false,
//                                printContainer: false,
//                                dataCSS: "@page{margin-top: 0.5cm;margin-right: 0;margin-bottom: 0.5cm;margin-left: 0;size: A4 portrait;width: 100%;orientation: portrait}*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}*:before,*:after{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}body{margin: 0;padding: 0;line-height: 1.4em;font-size: 12px;font-family: \"Times Roman\", sans-serif;color: #000;width: 100%;-webkit-print-color-adjust: exact}table{border-collapse: collapse !important;font-size: 12px;border-color: grey;line-height: 1em}tr{page-break-inside:avoid;page-break-after:auto}td{page-break-inside:avoid;page-break-after:auto}thead{display: table-header-group}tbody{display: table-row-group}tfoot{display: table-footer-group}table thead th, table thead td, table tbody td, table tfoot td{padding: 2px 3px}table tbody td.bold{font-weight: bold}",
//                                removeInline: false
//                            });
//                        });
//                    } else {
//                        PNotify.removeAll();
//                        new PNotify({
//                            title: 'Bank terminal error',
//                            text: jsonData.description,
//                            type: 'error', 
//                            sticker: false, 
//                            addclass: 'pnotify-center'
//                        });                        
//                    }
//                };
//
//                ws.onerror = function(event) {
//                    var resultJson = {
//                        Status: 'Error',
//                        Error: event.code
//                    }
//                    console.log(JSON.stringify(resultJson));
//                };
//
//                ws.onclose = function() {
//                    console.log("Connection is closed...");
//                };
        }
    });    

}

function posLockerUnilock(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    if (_isReadLocker) {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Locker уншигч <strong>нээгдсэн</strong> байна! <br>(Alt+tab дарж гаргана уу)',
            type: 'warning',
            sticker: false,
            addclass: 'pnotify-center'
        });        
        return;
    }

    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        // Let us open a web socket
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function() {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"locker_key_read", "dateTime":"' + currentDateTime + '", details: []}');
        };
        _isReadLocker = true;

        ws.onmessage = function(evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            _isReadLocker = false;

            if (jsonData.status == 'success') {
                var getLockerCode = jsonData.details[0].value;

                $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
                if ($tabMainContainer.find("a[href='#app_tab_mdposLocker_1566556713853_1991']").length) {
                    $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div#app_tab_mdposLocker_1566556713853_1991').empty().remove();
                    $tabMainContainer.find("a[href='#app_tab_mdposLocker_1566556713853_1991']").closest('li').remove();
                }

                var paramsData = { weburl: 'mdpos', metaDataId: 'mdposLocker_1566556713853_1991', title: 'POS', type: 'selfurl', recordId: getLockerCode, selectedRow: { keycode: getLockerCode, id: '', typeid: '5' }};
                $.ajax({
                    type: 'post',
                    url: 'mdpos/checkLoadLocker',
                    data: paramsData,
                    dataType: 'json',
                    success: function(data) {
                        if (typeof data.message !== 'undefined') {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: data.message,
                                type: 'warning', 
                                sticker: false, 
                                addclass: 'pnotify-center'
                            });
                            return;
                        }
                        
                        appMultiTab({ weburl: 'mdpos', metaDataId: 'mdposLocker_1566556713853_1991', title: 'POS', type: 'selfurl', recordId: getLockerCode, selectedRow: { keycode: getLockerCode, id: '', typeid: '5' } }, this, function(elem, param, data) {
                            if (typeof data.chooseCashier === 'undefined') {
                                elem.find('.pos-wrap').css({"margin-left":"-15px", "margin-right":"-16px", "margin-top":"-9px"});
                                if (typeof checkInitPosJS === 'undefined') {
                                    $.ajax({
                                        url: "middleware/assets/js/pos/pos.js",
                                        dataType: "script",
                                        cache: false,
                                        async: false
                                    });
                                } else {
                                    setTimeout(function() {
                                        Core.initDecimalPlacesInput();
                                        posConfigVisibler($('body'));
                                        posPageLoadEndVisibler();
                                        posItemCombogridList('');
                                        $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();

                                        var $tbody = $('#posTable').find('> tbody');

                                        if ($tbody.find('> tr').length) {

                                            Core.initLongInput($tbody);
                                            Core.initUniform($tbody);

                                            posGiftRowsSetDelivery($tbody);

                                            var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
                                            $firstRow.click();

                                            posCalcTotal();
                                        }                                    
                                    }, 300);
                                }
                                setTimeout(function() {
                                    posTableSetHeight();
                                    posFixedHeaderTable();
                                }, 300);
                            }
                        });                        
                        
                        /*paramsData.content = data.html;
                        appMultiTabByContent(paramsData, function(elem) {
                            if (typeof data.chooseCashier === 'undefined') {
                                elem.find('.pos-wrap').css('margin-left', '0');
                                if (typeof checkInitPosJS === 'undefined') {
                                    $.ajax({
                                        url: "middleware/assets/js/pos/pos.js",
                                        dataType: "script",
                                        cache: true,
                                        async: false
                                    });
                                } else {
                                    setTimeout(function() {
                                        Core.initDecimalPlacesInput();
                                        posConfigVisibler($('body'));
                                        posPageLoadEndVisibler();
                                        posItemCombogridList('');
                                        $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();

                                        var $tbody = $('#posTable').find('> tbody');

                                        if ($tbody.find('> tr').length) {

                                            Core.initLongInput($tbody);
                                            Core.initUniform($tbody);

                                            posGiftRowsSetDelivery($tbody);

                                            var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
                                            $firstRow.click();

                                            posCalcTotal();
                                        }                                    
                                    }, 300);
                                }
                                setTimeout(function() {
                                    posTableSetHeight();
                                    posFixedHeaderTable();
                                }, 300);
                            }
                        });*/
                    }
                });                

            } else {
                var resultJson = {
                    Status: 'Error',
                    Error: jsonData.message
                };
                console.log(JSON.stringify(resultJson));
            }
        };

        ws.onerror = function(event) {
            var resultJson = {
                Status: 'Error',
                Error: event.code
            }
            _isReadLocker = false;
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

$(document.body).on("keydown", "#newLockerNfcCartReader", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    if (keyCode == 13) {
        posLockerUnilockCheckNew($(this).val());
    }    
});

function posLockerUnilockNew(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    
  var $dialogName = "dialog-pos-nfc-reader";
  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append('<div style="text-align: center;">'+
    '<input type="text" id="newLockerNfcCartReader" class="form-control">'+
    '<img style="height: 250px;" alt="nfc reader" src="middleware/assets/img/pos/nfc-reader.jpg" />'+
    '</div>');

  $dialog.dialog({
    cache: false,
    resizable: false,
    bgiframe: true,
    autoOpen: false,
    title: "Түлхүүрээ NFC уншигч дээр байрлуулна уу",
    width: 450,
    height: "auto",
    modal: true,
    closeOnEscape: true,
    close: function () {
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: []
  });
  $dialog.dialog("open");    
}

function posLockerUnilockCheckNew(getLockerCode) {
    
    if (getLockerCode) {        

        $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
        if ($tabMainContainer.find("a[href='#app_tab_mdposLocker_1566556713853_1992']").length) {
            $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div#app_tab_mdposLocker_1566556713853_1992').empty().remove();
            $tabMainContainer.find("a[href='#app_tab_mdposLocker_1566556713853_1992']").closest('li').remove();
        }

        var paramsData = { weburl: 'mdpos', metaDataId: 'mdposLocker_1566556713853_1992', title: 'POS', type: 'selfurl', recordId: getLockerCode, selectedRow: { keycode: getLockerCode, id: '', typeid: '5' }};
        $.ajax({
        type: 'post',
        url: 'mdpos/checkLoadLocker',
        data: paramsData,
        dataType: 'json',
        success: function(data) {
            if (typeof data.message !== 'undefined') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: data.message,
                    type: 'warning', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });
                return;
            }
            
            $('#dialog-pos-nfc-reader').dialog("close");

            appMultiTab({ weburl: 'mdpos', metaDataId: 'mdposLocker_1566556713853_1992', title: 'POS', type: 'selfurl', recordId: getLockerCode, selectedRow: { keycode: getLockerCode, id: '', typeid: '5' } }, this, function(elem, param, data) {
                if (typeof data.chooseCashier === 'undefined') {
                    elem.find('.pos-wrap').css({"margin-left":"-15px", "margin-right":"-16px", "margin-top":"-9px"});
                    if (typeof checkInitPosJS === 'undefined') {
                        $.ajax({
                            url: "middleware/assets/js/pos/pos.js",
                            dataType: "script",
                            cache: false,
                            async: false
                        });
                    } else {
                        setTimeout(function() {
                            Core.initDecimalPlacesInput();
                            posConfigVisibler($('body'));
                            posPageLoadEndVisibler();
                            posItemCombogridList('');
                            $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();

                            var $tbody = $('#posTable').find('> tbody');

                            if ($tbody.find('> tr').length) {

                                Core.initLongInput($tbody);
                                Core.initUniform($tbody);

                                posGiftRowsSetDelivery($tbody);

                                var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
                                $firstRow.click();

                                posCalcTotal();
                            }                                    
                        }, 300);
                    }
                    setTimeout(function() {
                        posTableSetHeight();
                        posFixedHeaderTable();
                    }, 300);
                }
            });                        

            /*paramsData.content = data.html;
            appMultiTabByContent(paramsData, function(elem) {
                if (typeof data.chooseCashier === 'undefined') {
                    elem.find('.pos-wrap').css('margin-left', '0');
                    if (typeof checkInitPosJS === 'undefined') {
                        $.ajax({
                            url: "middleware/assets/js/pos/pos.js",
                            dataType: "script",
                            cache: true,
                            async: false
                        });
                    } else {
                        setTimeout(function() {
                            Core.initDecimalPlacesInput();
                            posConfigVisibler($('body'));
                            posPageLoadEndVisibler();
                            posItemCombogridList('');
                            $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();

                            var $tbody = $('#posTable').find('> tbody');

                            if ($tbody.find('> tr').length) {

                                Core.initLongInput($tbody);
                                Core.initUniform($tbody);

                                posGiftRowsSetDelivery($tbody);

                                var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
                                $firstRow.click();

                                posCalcTotal();
                            }                                    
                        }, 300);
                    }
                    setTimeout(function() {
                        posTableSetHeight();
                        posFixedHeaderTable();
                    }, 300);
                }
            });*/
        }
    });       
    }
}

function readMagCard($elem, path) {
    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        // Let us open a web socket
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function() {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"mag_card_read", "dateTime":"' + currentDateTime + '", details: []}');
        };

        ws.onmessage = function(evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (jsonData.status == 'success') {
                
                if ($elem.closest('form').find('input[data-path="'+path+'"]').length) {
                    $elem.closest('form').find('input[data-path="'+path+'"]').val(jsonData.details[0].value).trigger('change');
                }

            } else {
                var resultJson = {
                    Status: 'Error',
                    Error: jsonData.message
                };
                if ($elem.closest('form').find('input[data-path="'+path+'"]').length) {
                    $elem.closest('form').find('input[data-path="'+path+'"]').val('');
                }
            }
        };

        ws.onerror = function(event) {
            var resultJson = {
                Status: 'Error',
                Error: event.code
            }
            _isReadLocker = false;
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

function printTemplatePosByInvoiceId(invoiceId, response, templateId) {
    $.ajax({
        type: 'post',
        url: 'mdpos/printInvoiceResponseTemplate',
        data: {id: invoiceId, noLotteryNumber: 0, responseData: response, templateId: templateId}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Printing...',
                boxed: true
            });
        },
        success: function(data) {
            
            PNotify.removeAll();
            
            if (data.status == 'success') {
        
                var tempMetaId = data.templateId;
                var print_options = {
                    numberOfCopies: 1,
                    isPrintNewPage: 1,
                    isShowPreview: 0,
                    isPrintPageBottom: 0,
                    isPrintPageRight: 0,
                    isSettingsDialog: '0',
                    isPrintSaveTemplate: '0',
                    pageOrientation: 'portrait',
                    paperInput: 'portrait',
                    pageSize: 'a4',
                    printType: '1col',
                    isMergeDataRow: '1',
                    templateMetaId: tempMetaId
                }; 
                                        
                var selectedRows = []
                selectedRows[0] = data.data;
                callTemplate(selectedRows, tempMetaId, print_options);
                return;                
                
                var $dialogName = 'dialog-printSettings';
                if (!$($dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
                var $dialog = $('#' + $dialogName);
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('POS_0055'),
                    width: 500, 
                    minWidth: 400,
                    height: 'auto',
                    modal: false,
                    open: function(){
                        Core.initDVAjax($dialog); 
                    },
                    close: function(){
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: 'Хэвлэх', class: 'btn btn-sm blue', click: function() {
                                
                            PNotify.removeAll();
                            
                            var numberOfCopies = $("#numberOfCopies").val(),
                                isPrintNewPage = $("#isPrintNewPage").is(':checked') ? '1' : '0',
                                isShowPreview = $("#isShowPreview").is(':checked') ? '1' : '0',
                                isPrintPageBottom = $("#isPrintPageBottom").is(':checked') ? '1' : '0',
                                isPrintPageRight = $("#isPrintPageRight").is(':checked') ? '1' : '0',
                                pageOrientation = $("#pageOrientation").val(),
                                paperInput = $("#paperInput").val(),
                                pageSize = $("#pageSize").val(),
                                printType = $("#printType").val();
                        
                            var tempMetaId = data.templateId;
                            var print_options = {
                                numberOfCopies: numberOfCopies,
                                isPrintNewPage: isPrintNewPage,
                                isShowPreview: isShowPreview,
                                isPrintPageBottom: isPrintPageBottom,
                                isPrintPageRight: isPrintPageRight,
                                isSettingsDialog: '0',
                                pageOrientation: pageOrientation,
                                paperInput: paperInput,
                                pageSize: pageSize,
                                printType: printType,
                                templateMetaId: tempMetaId
                            }; 
                                                    
                            if (numberOfCopies != '' && numberOfCopies != '0') {
                                $dialog.dialog('close');
                                var selectedRows = []
                                selectedRows[0] = response;
                                callTemplate(selectedRows, tempMetaId, print_options);
                            } else {
                                new PNotify({
                                    title: 'Warning',
                                    text: plang.get('POS_0056'),
                                    type: 'warning',
                                    sticker: false
                                });
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');                
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status, 
                    sticker: false
                });
            }
            
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
            Core.unblockUI();
        }
    });
}