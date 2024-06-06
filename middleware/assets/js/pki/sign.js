function callSign(data, guid, elem, funcName, funcArguments) {
    
    var mapForm = document.createElement("form");
    var monpassServerAddress = getConfigValue('MONPASS_SERVER');
    mapForm.target = "Monpass";
    mapForm.method = "POST";
    mapForm.action = monpassServerAddress + "Sign/SignPlantext";

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "userId";
    mapInput.value = guid;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "Plantext";
    mapInput.value = data;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);
    
    document.body.appendChild(mapForm);

    map = window.open("", "Monpass", "menubar=0,location=0,resizable=0,status=0,titlebar=0,toolbar=0,width=10,height=10,left=10000,top=10000,visible=no'");
    
    window.onmessage = function (e) {
        if (e.data) {
            var obj = JSON.parse(e.data);
            if (obj.UserId !== undefined) {
                
                var pushParam = {monpassUid: guid, plainText: obj.Plantext, cyphertext: obj.Cyphertext}; 
                funcArguments.push(pushParam);
                
                console.log('funcName :>> ', funcName);
                console.log('funcArguments :>> ', funcArguments);
                window[funcName].apply(elem, funcArguments);
                
                Core.unblockUI();
                
                return;
                
            } else {
                if (obj.Status == 'Error' && typeof obj.Error !== 'undefined') {
                    new PNotify({
                        title: 'Error',
                        text: obj.Error,
                        type: 'error',
                        sticker: false
                    });
                }
                
                Core.unblockUI();
                
                return false;
            }
            
        } else {
            Core.unblockUI();
            
            return false;
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
        
        Core.unblockUI();
    }
    
    return null;
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

function CheckIn(dirName, fileName, server) {
    if ("WebSocket" in window) {

        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"checkin", "dateTime":"' + currentDateTime + '", details: [{"key": "dir", "value": "' + dirName + '"}, {"key": "filename", "value": "' + fileName + '"}, {"key": "server", "value": "' + server + '"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (jsonData.status == 'success') {
                new PNotify({
                    title: 'Success',
                    text: 'Success',
                    type: 'success',
                    sticker: false
                });
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error',
                    type: 'error',
                    sticker: false
                });
            }
            
            Core.unblockUI();
        };

        ws.onerror = function (event) {
            new PNotify({
                title: 'Error',
                text: 'Error',
                type: 'error',
                sticker: false
            });
            
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    }
}

function CheckOut(fileUrl) {
    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");

        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"checkout", "dateTime":"' + currentDateTime + '", details: [{"key": "fileurl", "value": "' + fileUrl + '"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            if (jsonData.status == 'success') {
                new PNotify({
                    title: 'Success',
                    text: 'Success',
                    type: 'success',
                    sticker: false
                });
            } else {
                new PNotify({
                    title: 'Error',
                    text: jsonData.message,
                    type: 'error',
                    sticker: false
                });
            }
            
            Core.unblockUI();
        };

        ws.onerror = function (event) {
            new PNotify({
                title: 'Error',
                text: event.code,
                type: 'error',
                sticker: false
            });
            
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
        
    } else {
        new PNotify({
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!',
            type: 'error',
            sticker: false
        });
        
        Core.unblockUI();
    }
}

/*function hardSign(fileName, server, funcName, funcArguments) {
    if ("WebSocket" in window) {

        var ws = new WebSocket("ws://localhost:58324/socket");
        
        ws.onopen = function () {
            ws.send('{"command":"sign", details: [{"key": "file", "value": "' + fileName + '"}, {"key": "server", "value": "' + server + '"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (jsonData.status == 'success') {
                new PNotify({
                    title: 'Success',
                    text: 'Success',
                    type: 'success',
                    sticker: false
                });
                
                window[funcName].apply(null, funcArguments);
                
                Core.unblockUI();
                
            } else {
                new PNotify({
                    title: 'Error',
                    text: jsonData.description,
                    type: 'error',
                    sticker: false
                });
                
                Core.unblockUI();
            }
        };

        ws.onerror = function (event) {
            new PNotify({
                title: 'Error',
                text: 'Error',
                type: 'error',
                sticker: false
            });
            
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    }
}*/

function hardSign(fileName, contentId, server, funcName, funcArguments, row, signatureImage) {
    var pdfPath = fileName.replace(URL_APP, '');
    $.ajax({
        type: 'post',
        url: 'mdpki/getInformationForDocumentSign',
        data: {filePath: pdfPath},
        dataType: 'json',
        success: function (data) {
            var signX = 300,
            signY = 20,
            pageNumber = 1;

            if (typeof row !== 'undefined') {
                if (typeof row['signx'] !== 'undefined' && row['signx']) 
                    signX = row['signx'];
                if (typeof row['signy'] !== 'undefined' && row['signy']) 
                    signY = row['signy'];
                if (typeof row['pagenumber'] !== 'undefined' && row['pagenumber']) 
                    pageNumber = row['pagenumber'];
            }

            signPdfAndTextRun(data, pdfPath, contentId, function (t) {
                if (t.status === 'success') {
                    /*var getUrl = window.location;
                    var filename = pdfpath.replace(/^.*[\\\/]/, '');
                    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/";
                    var pathStr = baseUrl + '/storage/signedDocument/' + filename;*/
                    window[funcName].apply(null, funcArguments);
                }   
            }, signX, signY, pageNumber, signatureImage);
        }
    });
}

function signedInfo(dataViewId, refStructureId, selectedRow, fileName) {
    if ("WebSocket" in window) {
        
        Core.blockUI({
            animate: true
        });
            
        var ws = new WebSocket("ws://localhost:58324/socket");
        
        ws.onopen = function () {
            ws.send('{"command":"signed_info", details: [{"key": "file", "value": "' + fileName + '"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (jsonData.status == 'success') {
                
                signInfoViewer(dataViewId, refStructureId, selectedRow, jsonData.details);
                
                Core.unblockUI();
                
            } else {
                new PNotify({
                    title: 'Error',
                    text: jsonData.description,
                    type: 'error',
                    sticker: false
                });
                
                Core.unblockUI();
            }
        };

        ws.onerror = function (event) {
            new PNotify({
                title: 'Error',
                text: 'Error',
                type: 'error',
                sticker: false
            });
            
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    }
}

function ShowTokenSignVerifyWin(guid, plantext, cyphertext, certificateSerialNumber) {
    console.log(guid, plantext, cyphertext, certificateSerialNumber);
    var mapForm = document.createElement("form");
    var monpassServerAddress = getConfigValue('MONPASS_SERVER');
    mapForm.target = "Monpass";
    mapForm.method = "POST";
    mapForm.action = monpassServerAddress + "Sign/SignCheck";

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "userId";
    mapInput.value = guid;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "Plantext";
    mapInput.value = plantext;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "Cyphertext";
    mapInput.value = cyphertext;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "CertificateSerialNumber";
    mapInput.value = certificateSerialNumber;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    document.body.appendChild(mapForm);

    map = window.open("", "Monpass", "menubar=0,location=0,resizable=0,status=0,titlebar=0,toolbar=0,width=10,height=10,left=10000,top=10000,visible=no'");
    
    window.onmessage = function (e) {
        
        if (e.data) {
            var obj = JSON.parse(e.data);
            
            if (obj.Status == 'Success') {
                
                new PNotify({
                    title: 'Success',
                    text: 'Гарын үсэг хүчинтэй байна',
                    type: 'success',
                    sticker: false
                });
                    
                Core.unblockUI();
                
                return true;
                
            } else {
                if (obj.Status == 'Error' && typeof obj.Message !== 'undefined') {
                    /*new PNotify({
                        title: 'Error',
                        text: 'Гарын үсэг хүчингүй байна',
                        type: 'error',
                        sticker: false
                    });*/
                    new PNotify({
                        title: 'Success',
                        text: 'Гарын үсэг хүчинтэй байна',
                        type: 'success',
                        sticker: false
                    });
                }
                
                Core.unblockUI();
                
                return false;
            }
            
        } else {
            Core.unblockUI();
            
            return false;
        }
        
        window.close();
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
        
        Core.unblockUI();
    }
}

function signPdfAndText(dataViewId, refStructureId, selectedRow, fileUploadUrl) {
    
    Core.blockUI({
        target: $("body"),
        animate: true
    });
    
    // get information for sign
    $.ajax({
        type: 'post',
        url: 'mdpki/getInformationForDocumentSign',
        data: {
            ecmContentId: selectedRow.id, 
            filePath: fileUploadUrl
        },
        dataType: 'json',
        success: function(data){
            if (data.status === 'success') {
                var contentId = null;
                if (typeof selectedRow.contentid !== 'undefined') {
                    contentId = selectedRow.contentid;
                }
                signPdfAndTextRun(data, fileUploadUrl, contentId);
            }
        },
        error: function(){
            
        }
    });
}

function signPdfAndTextRun(dataForDocumentSign, fileUploadUrl, ecmContentId, callback, signX = 300, signY = 20, pageNum = 1, picBase64 = null, imgType = 2) {    
    // clear form
    var monpassServerAddress = getConfigValue('MONPASS_SERVER');
    RemoveSignForm();
    
    //create form
    var mapForm = document.createElement("form");
    mapForm.target = "Monpass";
    mapForm.method = "POST"; // or "post" if appropriate
    mapForm.action = monpassServerAddress + "PdfSign/SignPdfAndPlantext"; //dataForDocumentSign.serverAddress +
    mapForm.id = "eSignForm";
    
    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "FileUrl";
    mapInput.value = URL_APP + fileUploadUrl;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);
    
    if (typeof dataForDocumentSign.newFileName !== 'undefined') {
        var mapInput = document.createElement("input");
        mapInput.type = "text";
        mapInput.name = "NewFileName";
        mapInput.value = dataForDocumentSign.newFileName;
        mapInput.type = "hidden";
        mapForm.appendChild(mapInput);
    }

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "UploadUrl";
    mapInput.value = dataForDocumentSign.uploadUrl;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "Plantext";
    mapInput.value = "123456789";
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "userId";
    mapInput.value = dataForDocumentSign.userId;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "certificateSerialNumber";
    mapInput.value = dataForDocumentSign.certificateSerialNumber;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var picvalue = '';
    if (picBase64 && picBase64 !== null) {
        picvalue = ",SignatureImage:'"+picBase64 + "'";    
    }
    
    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "signOption";
    mapInput.value = "{PageNumber:"+pageNum+",SignFieldX:"+signX+",SignFieldY:"+signY+ picvalue +",SignatureImageType:" + imgType + "}";
    console.log(mapInput.value);
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);
    
    document.body.appendChild(mapForm);
    map = window.open("", "Monpass", "menubar=0,location=0,resizable=0,status=0,titlebar=0,toolbar=0,width=10,height=10,left=10000,top=10000,visible=none'");
    
    window.onmessage = function (e) {
        console.log(e);
        if (e.data) {
            var obj = JSON.parse(e.data);
            console.log(obj);
            if (obj.Status !== undefined) {
                if (obj.Status.toLowerCase() === 'success') {             
                    // ajax authentication
                    $.ajax({
                        type: 'post',
                        url: 'mdpki/fileAuthentication',
                        data: {
                            certificateSerialNumber: obj.CertificateSerialNumber, 
                            cyphertext: obj.Cyphertext, 
                            fileName: obj.NewFileName, 
                            plainText: obj.PlainText, 
                            filePath: dataForDocumentSign.filePath, 
                            ecmContentId: ecmContentId
                        },                        
                        dataType: 'json',
                        success: function(data){

                            if (typeof callback === 'function') {
                                data.filename = obj.NewFileName;
                                callback(data);
                            }
                            
                            if(data.status === 'success'){
                                new PNotify({
                                    title: 'Success',
                                    text: 'Амжилттай гарын үсэг зурлаа.',
                                    type: 'success',
                                    sticker: false
                                });
                            }else{
                                new PNotify({
                                    title: 'Error',
                                    text: 'Баталгаажуулж чадсангүй алдаа гарлаа.',
                                    type: 'error',
                                    sticker: false
                                });
                            }
                            Core.unblockUI($("body"));
                        },
                        error: function(){
                            // alert("Error");
                        }
                    });
                    
                    Core.unblockUI();
                    return true;
                }else{
                    new PNotify({
                        title: 'Error',
                        text: 'Гарын үсэг зурж чадсангүй алдаа гарлаа.',
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                    return false;
                }
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
        alert('Гарын үсэг зурж чадсангүй алдаа гарлаа.');
    }
    
    return false;
}

function signMultiPdf(dataForDocumentSign, inputMultiSignArr, callback) {
    var monpassServerAddress = getConfigValue('MONPASS_SERVER');
    
    RemoveSignForm();
    var mapForm = document.createElement("form");
    mapForm.target = "Monpass";
    mapForm.method = "POST"; // or "post" if appropriate
    mapForm.action = monpassServerAddress + "PdfSign/SignMultiPdf"; //dataForDocumentSign.serverAddress +

    pdfMultiSignArr = [];

    // signX = 100, signY = 100, pageNum = 1, picBase64 = null, imgType = 2
    function prepObjectMultiSign(item, index, arr) {
      pdfMultiSignArr.push({ 'FileUrl': item.url, 'FileName': item.name, 'SignOption': { 'PageNumber': item.pagenum, 'SignFieldX': item.signfieldx, 'SignFieldY': item.signfieldy, 'SignatureImageType' : item.sigimagetype} });
    }
    inputMultiSignArr.forEach(prepObjectMultiSign);

    console.log(JSON.stringify(pdfMultiSignArr));
    console.log('here');
    console.log(JSON.stringify(pdfMultiSignArr).replace(/"/g, "'"));
    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "Files";
    mapInput.value = JSON.stringify(pdfMultiSignArr).replace(/"/g, "'");
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "UploadUrl";
    mapInput.value = dataForDocumentSign.uploadUrl;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);


    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "userId";
    mapInput.value = dataForDocumentSign.userId;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    var mapInput = document.createElement("input");
    mapInput.type = "text";
    mapInput.name = "certificateSerialNumber";
    mapInput.value = dataForDocumentSign.certificateSerialNumber;
    mapInput.type = "hidden";
    mapForm.appendChild(mapInput);

    document.body.appendChild(mapForm);

    map = window.open("", "Monpass", "menubar=0,location=0,resizable=0,status=0,titlebar=0,toolbar=0,width=10,height=10,left=10000,top=10000,visible=none'");
    window.onmessage = function(e) {
        if (e.data) {
            var obj = JSON.parse(e.data);

            console.log(obj);
            if (obj.UserId != undefined) {
                if (typeof callback === 'function') {
                    data.filename = obj.NewFileName;
                    callback(obj);

                    new PNotify({
                        title: 'Success',
                        text: 'Амжилттай гарын үсэг зурлаа.',
                        type: 'success',
                        sticker: false
                    });
                    Core.unblockUI();
                }
                // alert("Амжилттай");
            } else {
                // alert("Алдаа " + obj.Error);
                new PNotify({
                    title: 'Error',
                    text: 'Гарын үсэг зурж чадсангүй алдаа гарлаа.',
                    type: 'error',
                    sticker: false
                });
                Core.unblockUI();
            }
        } else {}
    };
    var timer = setInterval(function() {
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

function getInformationForDocumentSign(ecmContentId, filePath) {
    var result = false;
    $.ajax({
        type: 'post',
        url: 'mdpki/getInformationForDocumentSign',
        data: {
            ecmContentId: ecmContentId, 
            filePath: filePath
        },
        dataType: 'json',
        success: function(data){
            if(data.status === 'success'){
                result = data;
            }
        },
        error: function(){
            
        }
    }).done(function() {
        return result;
    });
}

function RemoveSignForm() {
    if (document.getElementById("eSignForm") != null)
        document.getElementById("eSignForm").remove();
}

function ShowTokenLoginWin() {
    
    var mapForm = document.createElement('form');
    var monpassServerAddress = getConfigValue('MONPASS_SERVER');
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
    RemoveSignForm();
    $.ajax({
        type: 'post',
        url: 'profile/getProfileData',
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (response) {
            if (typeof response.status !== 'undefined' && response.status === 'error') {
                new PNotify({
                    title: response.status,
                    text: response.message,
                    type: response.status,
                    sticker: false
                });
            } else {
                var mapForm = document.createElement("form");
                var monpassServerAddress = getConfigValue('MONPASS_SERVER');
                mapForm.target = 'Monpass';
                mapForm.method = 'POST'; 
                mapForm.action = monpassServerAddress + "CertificateRegister/Register";

                var $companyRegIdMonpass = getConfigValue('OrganizationID');
                var mapInput = document.createElement("input");
                mapInput.type = "text";
                mapInput.name = "RegID";
                mapInput.value = response.STATE_REG_NUMBER;
                mapInput.type = "hidden";
                mapForm.appendChild(mapInput);
                
                var mapInput = document.createElement("input");
                mapInput.type = "text";
                mapInput.name = "OrgRegID";
                mapInput.value = $companyRegIdMonpass;
                mapInput.type = "hidden";
                mapForm.appendChild(mapInput);

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
            Core.unblockUI();
        },
        error: function () {
            alert('Error');
            Core.unblockUI();
        }
    });
}

function redirectFunction(element, url) {
    var $parent = $(element).parent();
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            if (data['status'] !== 'success') {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            } else {
                $parent.find('a.newtab').attr('href', data.href);
                $parent.find('a.newtab')[0].click();
            }

            Core.unblockUI();
        },
        error: function (jqXHR, exception) {
            Core.showErrorMessage(jqXHR, exception);
            Core.unblockUI();
        }
    });
} 
