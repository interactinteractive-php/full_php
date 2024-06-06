// 1/5
function checkCertificateInformation(dialogName) {
    var requestDtlId = "";
    var commonData = {organization: '', organizationUnit: '', positionName: '', givenName: '', lastName: '', email: '', commonName: ''};
    $('#wsForm').find(":hidden").each(function (k, v) {
        var tmp_name = $(v).attr('name');
        if (tmp_name === 'param[id]') {
            requestDtlId = $(v).val();
        }
    });

    $('#wsForm').find(":text").each(function (k, v) {
        var tmp_name = $(v).attr('name');
        switch (tmp_name) {
            case 'customerId_nameField' :
            {
                if (commonData['organization'].length === 0) {
                    commonData['organization'] = $(v).val();
                }
                break;
            }
            case 'param[departmentName]' :
            {
                if (commonData['organizationUnit'].length === 0) {
                    commonData['organizationUnit'] = $(v).val();
                }
                break;
            }
            case 'param[positionName]' :
            {
                if (commonData['positionName'].length === 0) {
                    commonData['positionName'] = $(v).val();
                }
                break;
            }
            case 'param[firstName]' :
            {
                if (commonData['givenName'].length === 0) {
                    commonData['givenName'] = $(v).val();
                }
                break;
            }
            case 'param[lastName]' :
            {
                if (commonData['lastName'].length === 0) {
                    commonData['lastName'] = $(v).val();
                }
                break;
            }
            case 'param[firstEmail]' :
            {
                if (commonData['email'].length === 0) {
                    commonData['email'] = $(v).val();
                }
                break;
            }
        }
    });

    commonData['commonName'] = commonData['lastName'] + " " + commonData['givenName'].toUpperCase();

    $.ajax({
        type: 'POST',
        url: 'ra/validateRequestDtlBeforeIssue',
        data: {
            requestDtlId: requestDtlId
        },
        dataType: "json",
        beforeSend: function () {
            $('.loading-message-boxed').find('span').html(' Мэдээлэл баталгаажуулж байна, түр хүлээнэ үү ... 1/5');
        },
        success: function (data) {
            if (data.status === 'success') {
                getSerial(data.data, requestDtlId, dialogName, commonData);
            }
            if (data.status === 'error') {

                PNotify.removeAll();
                new PNotify({
                    type: data.status,
                    title: data.title,
                    text: data.text,
                    sticker: false
                });

                Metronic.unblockUI();
            }
        }
    });
}

// 2/5
function getSerial(name, requestDtlId, dialogName, commonData) {
    var serial_number = "";

    $('.loading-message').find('span').html(' Сериал дугаар авч байна ... 2/5');

    if ("WebSocket" in window) {

        var ws = new WebSocket("wss://local.monpass.mn:43971/socket");

        ws.onopen = function () {
            //yyyy/MM/dd HH:mmss
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"get_certificate_sn", "dateTime":"' + currentDateTime + '"}');
        };
        ws.onmessage = function (evt) {
            console.log(4);
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            if (jsonData.status == 'success') {
                for (var i = 0; i < jsonData.details.length; i++) {

                    if (jsonData.details[i].key == 'serial_number') {
                        serial_number = jsonData.details[i].value;
                        checkIsValidateCeritificate(name, requestDtlId, serial_number, dialogName, commonData);
                    }
                }
            } else {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: jsonData.message,
                    sticker: false
                });
                Metronic.unblockUI();
            }
        };
        ws.onclose = function () {
            if (serial_number == "") {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: "Token - ээ залгаад, Monpass client tool програмыг эхлүүлэнэ үү.",
                    sticker: false
                });

                Metronic.unblockUI();
            }
        };

        ws.onerror = function (evt) {
            console.log("Error: " + evt.data);
        }
    } else {
        PNotify.removeAll();
        new PNotify({
            type: 'error',
            title: 'Алдаа гарлаа',
            text: "WebSocket NOT supported by your Browser!",
            sticker: false
        });

        Metronic.unblockUI();
    }
}

// 3/5
function checkIsValidateCeritificate(name, requestDtlId, serial_number, dialogName, commonData) {

    $.ajax({
        type: 'POST',
        url: 'ra/getTokenPinBySerialNumber',
        data: {
            tokenSerialNumber: serial_number
        },
        dataType: "json",
        beforeSend: function () {
            $('.loading-message').find('span').html(' Бүртгэлтэй эсэхийг шалгаж байна, түр хүлээнэ үү ... 3/5');
        },
        success: function (data) {
            if (data.status === 'success') {
                var pinCode = data.data;
                $('#wsForm').find(":checkbox").each(function (k, v) {
                    var tmp_name = $(v).attr('name');
                    if (tmp_name === 'param[isToken]') {
                        if ($(v).attr('checked') !== "checked") {
                            pinCode = '';
                        }
                    }
                });

                generateCsr(name, requestDtlId, serial_number, pinCode, dialogName, commonData);
            }

            if (data.status === 'error') {

                PNotify.removeAll();
                new PNotify({
                    type: data.status,
                    title: data.title,
                    text: data.text,
                    sticker: false
                });

                Metronic.unblockUI();
            }
        }
    });

}

// 4/5
function generateCsr(name, requestDtlId, tokenSerialNumber, pinCode, dialogName, commonData) {
    var csr_data = "";
    var commonName = commonData['commonName'];
    var organization = commonData['organization'];
    var organizationUnit = commonData['organizationUnit']; // хэлтэс
    var position = commonData['positionName'];
    var email = commonData['email'];
    var lang = "MN";
    var givenName = commonData['givenName'];
    $('.loading-message').find('span').html(' CSR үүсгэж байна, түр хүлээнэ үү ... 4/5');

    if ("WebSocket" in window) {

        var ws = new WebSocket("wss://local.monpass.mn:43971/socket");

        ws.onopen = function () {
            //yyyy/MM/dd HH:mmss
            var currentDateTime = GetCurrentDateTime();
            if (givenName.length > 16) {
                ws.send('{"command":"generate_csr", "dateTime":"' + currentDateTime + '", details: [{"key": "subject", "value": "CN=' + commonName + ',O=' + organization + ',OU=' + organizationUnit + ',T=' + position + ',E=' + email + ',C=' + lang + '"},{"key": "pin_code", "value": "' + pinCode + '"}]}');
            } else {
                ws.send('{"command":"generate_csr", "dateTime":"' + currentDateTime + '", details: [{"key": "subject", "value": "CN=' + commonName + ',O=' + organization + ',OU=' + organizationUnit + ',T=' + position + ',givenName=' + givenName + ',E=' + email + ',C=' + lang + '"},{"key": "pin_code", "value": "' + pinCode + '"}]}');
            }
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            if (jsonData.status == 'success') {
                for (var i = 0; i < jsonData.details.length; i++) {

                    if (jsonData.details[i].key == 'csr_data') {
                        csr_data = jsonData.details[i].value;
                        issueCertificate(requestDtlId, tokenSerialNumber, csr_data, dialogName);
                    }
                }
            } else {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: jsonData.message,
                    sticker: false
                });

                Metronic.unblockUI();
            }
        };

        ws.onclose = function () {
            if (csr_data == "") {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: "client not running",
                    sticker: false
                });

                Metronic.unblockUI();
            }
        };
    } else {
        PNotify.removeAll();
        new PNotify({
            type: 'error',
            title: 'Алдаа гарлаа',
            text: "WebSocket NOT supported by your Browser!",
            sticker: false
        });

        Metronic.unblockUI();
    }
}

// 5/5
function issueCertificate(requestDtlId, tokenSerialNumber, csr_data, dialogName) {
    $.ajax({
        type: 'POST',
        url: 'ra/issueCertificate',
        data: {
            requestDtlId: requestDtlId,
            tokenSerialNumber: tokenSerialNumber,
            csrRequest: csr_data
        },
        dataType: "json",
        beforeSend: function () {
            $('.loading-message').find('span').html(' Олголт хийгдэж эхэллээ, түр хүлээнэ үү ... 5/5');
        },
        success: function (data) {
            if (data.status === 'success') {
                PNotify.removeAll();
                new PNotify({
                    type: data.status,
                    title: 'Таны тоон гарын үсэг',
                    text: 'Амжилттай үүсгэлээ.',
                    sticker: false
                });

                $("#" + dialogName).dialog('close');
            }

            if (data.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    type: data.status,
                    title: data.title,
                    text: data.text,
                    sticker: false
                });
            }

            Metronic.unblockUI();
        }
    });

}
// ChangePin
// 1/4
function checkCertificatePinChange(dialogName) {
    var requestDtlId = "";
    var adminPin = "";
    var tokenSerialNumber = "";
    var newPinCode = "";
    var isPinGenerate = "";

    requestDtlId = $('#wsForm').find("[name='param[id]']").val();
    adminPin = $('#wsForm').find("[name='param[adminPin]']").val();
    tokenSerialNumber = ($('#wsForm').find("[name='param[tokenSerialNumber]']").val()).toLowerCase();
    newPinCode = $('#wsForm').find("[name='param[newPinCode]']").val();
    isPinGenerate = $('#wsForm').find("[name='param[isPinGenerate]']").is(':checked');

    $('.loading-message-boxed').find('span').html(' Мэдээлэл баталгаажуулж байна, түр хүлээнэ үү ... 1/4');

    getSerialPinChange(requestDtlId, tokenSerialNumber, adminPin, dialogName, newPinCode, isPinGenerate);
}

// 2/4
function getSerialPinChange(requestDtlId, tokenSerialNumber, adminPin, dialogName, newPinCode, isPinGenerate) {

    var serial_number = "";

    $('.loading-message').find('span').html(' Сериал дугаар авч байна ... 2/4');

    if ("WebSocket" in window) {

        var ws = new WebSocket("wss://local.monpass.mn:43971/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"get_certificate_sn", "dateTime":"' + currentDateTime + '"}');
        };
        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            if (jsonData.status == 'success') {
                for (var i = 0; i < jsonData.details.length; i++) {

                    if (jsonData.details[i].key == 'serial_number') {
                        if (tokenSerialNumber == jsonData.details[i].value) {
                            changePin(requestDtlId, tokenSerialNumber, adminPin, dialogName, newPinCode, isPinGenerate);
                            serial_number = jsonData.details[i].value;
                        }
                    }
                }
            } else {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: jsonData.message,
                    sticker: false
                });
                Metronic.unblockUI();
            }
        };
        ws.onclose = function () {
            if (serial_number == "") {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: "Token - ээ залгаад, Monpass client tool програмыг эхлүүлэнэ үү.",
                    sticker: false
                });

                Metronic.unblockUI();
            }
        };

        ws.onerror = function (evt) {
            console.log("Error: " + evt.data);
        }
    } else {
        PNotify.removeAll();
        new PNotify({
            type: 'error',
            title: 'Алдаа гарлаа',
            text: "WebSocket NOT supported by your Browser!",
            sticker: false
        });

        Metronic.unblockUI();
    }
}

// 3/4
function changePin(requestDtlId, tokenSerialNumber, adminPin, dialogName, newPinCode, isPinGenerate) {

    var gen_code = '';

    $('.loading-message').find('span').html(' Нууц үгийг шинэчилж байна ... 3/4');

    if ("WebSocket" in window) {

        var ws = new WebSocket("wss://local.monpass.mn:43971/socket");

        ws.onopen = function () {
            if (isPinGenerate) {
                $.ajax({
                    type: 'POST',
                    url: 'ra/generateUID',
                    dataType: "json",
                    beforeSend: function () {
//                        $('.loading-message').find('span').html(' Баталгаажуулж байна, түр хүлээнэ үү ... 4/4');
                    },
                    success: function (data) {
                        if (data !== null) {
                            ws.send('{"command":"change_pin", details: [{"key": "encrypted_admin_pin", "value": "' + adminPin + '"}, {"key": "new_pin", "value": "' + data + '"}, {"key": "token_serial_number", "value": "' + tokenSerialNumber + '"}]}');
                        } else {
                            PNotify.removeAll();
                            new PNotify({
                                type: 'error',
                                title: 'Алдаа',
                                text: 'Нууц үг системээс үүсгэхэд алдаа гарлаа',
                                sticker: false
                            });

                            Metronic.unblockUI();
                        }
                    }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                    }

                });
            } else {
                ws.send('{"command":"change_pin", details: [{"key": "encrypted_admin_pin", "value": "' + adminPin + '"}, {"key": "new_pin", "value": "' + newPinCode + '"}, {"key": "token_serial_number", "value": "' + tokenSerialNumber + '"}]}');
            }
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            if (jsonData.status == 'success') {
                for (var i = 0; i < jsonData.details.length; i++) {

                    if (jsonData.details[i].key == 'encryptedNewPin') {
                        gen_code = jsonData.details[i].value;
                        callWSChangePin(requestDtlId, tokenSerialNumber, adminPin, dialogName, newPinCode, isPinGenerate, gen_code);
                    }
                }
            } else {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: jsonData.message,
                    sticker: false
                });

                Metronic.unblockUI();
            }
        };

        ws.onclose = function () {
            if (gen_code == "") {
                PNotify.removeAll();
                new PNotify({
                    type: 'error',
                    title: 'Алдаа гарлаа',
                    text: "client not running",
                    sticker: false
                });

                Metronic.unblockUI();
            }
        };
    } else {
        PNotify.removeAll();
        new PNotify({
            type: 'error',
            title: 'Алдаа гарлаа',
            text: "WebSocket NOT supported by your Browser!",
            sticker: false
        });

        Metronic.unblockUI();
    }
}

// 4/4
function callWSChangePin(requestDtlId, tokenSerialNumber, adminPin, dialogName, newPinCode, isPinGenerate, gen_code) {
    $.ajax({
        type: 'POST',
        url: 'ra/confirmPasswordChange',
        data: {
            requestDtlId: requestDtlId,
            tokenSerialNumber: tokenSerialNumber,
            isPinGenerate: isPinGenerate,
            gen_code: gen_code
        },
        dataType: "json",
        beforeSend: function () {
            $('.loading-message').find('span').html(' Баталгаажуулж байна, түр хүлээнэ үү ... 4/4');
        },
        success: function (data) {
            console.log('4/4 ra/confirmPasswordChange : ' + data);
            if (data.status === 'success') {
                PNotify.removeAll();
                new PNotify({
                    type: data.status,
                    title: 'Таны нууц үг',
                    text: 'амжилттай шинэчлэгдлээ.',
                    sticker: false
                });

                $("#" + dialogName).dialog('close');
            }

            if (data.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    type: data.status,
                    title: data.title,
                    text: data.text,
                    sticker: false
                });
            }

            Metronic.unblockUI();
        }
    });

}


function GetCurrentDateTime() {
    var today = new Date();
    var dd = today.getDate();
    var MM = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear;
    var HH = today.getHours();
    var mm = today.getMinutes();
    var ss = today.getSeconds();

    if (dd < 10) {
        dd = '0' + dd
    }
    if (MM < 10) {
        MM = '0' + MM
    }
    if (HH < 10) {
        HH = '0' + HH
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    if (ss < 10) {
        ss = '0' + ss
    }

    var datetime = yyyy + "/" + MM + "/" + dd + " " + HH + ":" + mm + ":" + ss;
    return datetime;
}