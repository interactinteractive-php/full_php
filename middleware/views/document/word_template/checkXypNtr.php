<div class="window-<?php echo $this->uniqId ?>" id="xyp-check-<?php echo $this->uniqId ?>">
    <div class="row mb10">
        <div class="col-md-12" style="display: table;">
            <div class="col-md-3 col-sm-3 col-lg-3 select-item-<?php echo $this->uniqId ?>" data-id="1">
                <label>Иргэний мэдээлэл</label>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 select-item-<?php echo $this->uniqId ?>" data-id="2">
                <label>Үл хөдлөх хөрөнгө /хураангуй/</label>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 select-item-<?php echo $this->uniqId ?>" data-id="3">
                <label>Үл хөдлөх хөрөнгө /дэлгэрэнгүй/</label>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 select-item-<?php echo $this->uniqId ?>" data-id="4">
                <label>Хуулийн этгээд</label>
            </div>
    <!--        <div class="col-md-2 select-item-<?php echo $this->uniqId ?>" data-id="5">
                <label>Өмчлөх, эзэмших, ашиглах эрх</label>
            </div>-->
        </div>
    </div>
    <div class="col-md-12 stateRegNumber mt10">
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-4" for="temp-stateRegNumber" style="padding: 0; text-align: right">Регистрийн дугаар:</label>
            <div class="col-md-6">
                <div class="input-group input-group-criteria" id="bp-window-<?php echo $this->uniqId ?>" style="float: left;">

                    <input type="text" class="form-control form-control-sm stringInit" name="temp-stateRegNumber" placeholder="Регистрийн дугаар" id="temp-stateRegNumber" data-path="temp-stateRegNumber" data-regex="^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ]{2}[0-9]{8}$" data-inputmask-regex="^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ]{2}[0-9]{8}$" style="width: 140px;"/>
                    <input type="text" name="temp-civilNumber" class="form-control form-control-sm longInit civilNumber" data-path="temp-civilNumber" data-field-name="civilNumber" value="" data-isclear="0" placeholder="<?php echo Lang::lineCode('civilnumber_txt') ?>">
                    <input type="hidden" name="temp-fingerPrint" class="form-control form-control-sm" data-path="temp-fingerPrint" value="">
                    
                    <span class="input-group-btn ">
                        <button type="button" class="btn btn-sm dropdown-toggle criteria-condition-btn btn-info dropdown-none-arrow" data-toggle="dropdown" aria-expanded="true" tabindex="-1" onclick="bpFingerImageData(this, 'temp-fingerPrint', '<?php echo $this->uniqId ?>')" style="padding: 0px 5px 0px 5px;border-bottom-left-radius: 0; border-top-left-radius: 0; font-size: 13.5px;">Хурууны хээ уншуулах</button>
                    </span>
                </div>
                <div class="alert alert-primary border-0 alert-dismissible p-1 mb-1 w-100 pull-left mt-1">
                    <input type="checkbox" name="temp-civilnumber" class="form-control form-control-sm stringInit" data-path="temp-civilnumber" id="temp-civilnumber" value="1" > 
                    <label for="temp-civilnumber"><?php echo Lang::lineCode('is_civilnumber_txt') ?></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt10 hidden propertyNumber">
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-4" for="temp-propertyNumber" style="padding: 0; text-align: right"><?php echo $this->lang->line('Үл хөдлөхийн дугаар') . ':' ?></label>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm stringInit" name="temp-propertyNumber" placeholder="<?php echo $this->lang->line('Үл хөдлөхийн дугаар') ?>" id="temp-propertyNumber" style="width: 140px;"/>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt10 hidden legalEntityNumber">
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-4" for="temp-legalEntityNumber" style="padding: 0; text-align: right"><?php echo $this->lang->line('Байгууллагын регистрийн дугаар') . ':' ?></label>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm stringInit" name="temp-legalEntityNumber" placeholder="<?php echo $this->lang->line('Байгууллагын регистрийн дугаар') ?>" id="temp-legalEntityNumber" style="width: 140px;"/>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt10">
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-4" for="temp-stateRegNumber" style="padding: 0; text-align: right">Нотариатчийн мэдээлэл авах төрөл:</label>
            <div class="col-md-6">
                <select class="input-sm form-control form-control-sm select2" data-path="temp-check-type" name="check_type" style="width: 200px;">
                    <option value="1">Тоон гарын үсэг</option>
                    <option value="2">Хурууны хээ</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt10 render-html-data-<?php echo $this->uniqId ?>"></div>
</div>
<style type="text/css">

    .window-<?php echo $this->uniqId ?> .civilNumber{
        border-radius: 0.1875rem !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        display: none;
    }

    .window-<?php echo $this->uniqId ?> .criteria-condition-btn {
        padding: 0px 5px 0px 5px;
        border-bottom-left-radius: 0;
        border-top-left-radius: 0;
        font-size: 13.5px;
    }
</style>

<script type="text/javascript">
    
    $(function () {
        $('.select-item-<?php echo $this->uniqId ?>:eq(0)').trigger('click');
    });

    $('body').on('click', '.window-<?php echo $this->uniqId ?> input[data-path="temp-civilnumber"]', function () {
        var _this = $(this), 
        _parent = _this.closest('.window-<?php echo $this->uniqId ?>');
        
        if (_this.is(":checked")) {
            _parent.find('input[name="temp-stateRegNumber"]').val('');
            _parent.find('input[name="temp-stateRegNumber"]').hide();
            _parent.find('input[name="temp-civilNumber"]').show();
        } else {
            _parent.find('input[name="temp-civilNumber"]').val('');
            _parent.find('input[name="temp-civilNumber"]').hide();
            _parent.find('input[name="temp-stateRegNumber"]').show();
        }
    });

    $('body').on('click', '.select-item-<?php echo $this->uniqId ?>', function () {
        var $this = $(this);
        $('.select-item-<?php echo $this->uniqId ?>').removeClass('active');
        $this.addClass('active');

        $('#xyp-check-<?php echo $this->uniqId ?>').find('input').val('');
        
        $('#xyp-check-<?php echo $this->uniqId ?> .stateRegNumber').removeClass('hidden');
        $('#xyp-check-<?php echo $this->uniqId ?> .propertyNumber').addClass('hidden');
        $('#xyp-check-<?php echo $this->uniqId ?> .legalEntityNumber').addClass('hidden');

        $('.render-html-data-<?php echo $this->uniqId ?>').empty();
        
        switch ($this.attr('data-id')) {
            case '1':

                break;
            case '2':

                break;
            case '3':
                $('#xyp-check-<?php echo $this->uniqId ?> .propertyNumber').removeClass('hidden');
                break;
            case '4':
                //$('#xyp-check-<?php echo $this->uniqId ?> .stateRegNumber').addClass('hidden');
                $('#xyp-check-<?php echo $this->uniqId ?> .legalEntityNumber').removeClass('hidden');

                break;
            case '5':

                break;

        }
        
    });

    function checkXypFnc_<?php echo $this->uniqId ?>() {
        
        var $stateRegNumber = $('#xyp-check-<?php echo $this->uniqId ?>').find('input[data-path="temp-stateRegNumber"]').val();
        var $civilNumber = $('#xyp-check-<?php echo $this->uniqId ?>').find('input[data-path="temp-civilNumber"]').val();
        var $fingerPrint = $('#xyp-check-<?php echo $this->uniqId ?>').find('input[data-path="temp-fingerPrint"]').val();

        if ((!$stateRegNumber || !$civilNumber) && !$fingerPrint && $('#xyp-check-<?php echo $this->uniqId ?> .stateRegNumber').hasClass('hidden')) {
            new PNotify({
                title: 'Warning',
                text: 'Иргэний мэдээллийг гүйцэт оруулна уу?',
                type: 'warning',
                sticker: false
            });
            return;
        }
        
        if ("WebSocket" in window) {
            console.log("WebSocket is supported by your Browser!");
            // Let us open a web socket
            var ws = new WebSocket("ws://localhost:58324/socket");

            ws.onopen = function () {
                var currentDateTime = GetCurrentDateTime();
                ws.send('{"command":"finger_image", "dateTime":"' + currentDateTime + '", details: []}');
            };

            ws.onmessage = function (evt) {
                var received_msg = evt.data;
                var jsonData = JSON.parse(received_msg);
                if (jsonData.status == 'success') {

                    $.ajax({
                        type: 'post',
                        url: 'mddoc/saveFingerDataTemp',
                        data: {
                            operatorFinger: jsonData.details[0].value, 
                            finger: $fingerPrint, 
                            registerNumber: $stateRegNumber,
                            civilId: $civilNumber
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function (response) {
                            var data = response.data;
                            if (response.status === 'success') {
                                $("#" + $dialogName).empty().dialog('close');
                                if (typeof data.regnum !== 'undefined' && typeof data.lastname !== 'undefined' && data.lastname !== '' && data.regnum !== '') {
                                    bpIDCardFillWtemplate(elem, _parent, _process, mainWidgetExpression, data.regnum, data.lastname, data.firstname, data.gender, data.surname, data.birthdateastext, data.aimagcityname, data.soumdistrictname, data.bagkhorooname, data.passportaddress, data.image, grouPath, undefined, undefined, '');
                                } else {
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Мэдээлэл солилцоход шаардлагатай мэдээллийг авч чадсангүй.',
                                        type: 'warning',
                                        sticker: false
                                    });
                                }
                            } else {
                                new PNotify({
                                    title: response.status,
                                    text: response.message,
                                    type: response.status,
                                    sticker: false
                                });
                            }
                            Core.unblockUI();
                        },
                        error: function () {
                            alert('Error');
                        }
                    });

                } else {
                    console.log(jsonData);
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

            ws.onerror = function (event) {
                var resultJson = {
                    Status: 'Error',
                    Error: event.code
                }

                console.log(JSON.stringify(resultJson));
            };

            ws.onclose = function () {
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

    function fingerWithXypData<?php echo $this->uniqId ?>($selectedItem, uniqId, $propertyNumber, $stateRegNumber, $legalEntityNumber, $civilNumber) {
        
        var $check_type = $('#xyp-check-<?php echo $this->uniqId ?>').find('select[data-path="temp-check-type"]').val();
        var $stateRegNumber = $('#xyp-check-<?php echo $this->uniqId ?>').find('input[data-path="temp-stateRegNumber"]').val();
        var $fingerPrint = $('#xyp-check-<?php echo $this->uniqId ?>').find('input[data-path="temp-fingerPrint"]').val();
        
        if ($check_type == '1') {
        
            var date = new Date(); 
            var $timestamp = date.getTime();

            if ((!$stateRegNumber || !$civilNumber) && !$fingerPrint  && !$('#xyp-check-<?php echo $this->uniqId ?> .stateRegNumber').hasClass('hidden')) {
                new PNotify({
                    title: 'Warning',
                    text: 'Иргэний мэдээллийг гүйцэт оруулна уу?',
                    type: 'warning',
                    sticker: false
                });
                return;
            }

            var $signature =  '<?php echo issetParam($this->operator['STATE_REG_NUMBER']) ?>.' + $timestamp;
            var request = {
                type : 'e457cb50ed64bde0',
                data : $signature,
            };

            if( request.data.length > 131072 ) {
                alert('Хэмжээ хэтэрсэн.');
                return;
            }

            var ws = new WebSocket("ws://127.0.0.1:59001");

            ws.onopen = function() {

                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });

                ws.send( JSON.stringify( request ) );

            };

            ws.onmessage = function (evt) {
                var resultJson = JSON.parse(evt.data)

                if (resultJson.hasOwnProperty('status') && resultJson['status'] === 'success') {
                    callXypData<?php echo $this->uniqId ?>(resultJson['signature'], uniqId, $propertyNumber, $stateRegNumber, $civilNumber, $legalEntityNumber, $selectedItem.attr('data-id'), $fingerPrint, $timestamp);
                } else {
                    var $message = resultJson.hasOwnProperty('message') ? resultJson['message'] : "ESign Клэнтээ унтрааж асууна уу?";
                    new PNotify({
                        title: "Warning",
                        text: $message,
                        type: "warning",
                        sticker: false
                    });
                }
            }

            ws.onerror = function (event) {
                new PNotify({
                    title: "Warning",
                    text: "ESign Клэнтээ унтрааж асууна уу?",
                    type: "warning",
                    sticker: false
                });
            };

            ws.onclose = function(event) {
                Core.unblockUI();
                ws.close();
            };

        } else {
            
            if ("WebSocket" in window) {
                console.log("WebSocket is supported by your Browser!");
                // Let us open a web socket
                var ws = new WebSocket("ws://localhost:58324/socket");

                ws.onopen = function () {
                    var currentDateTime = GetCurrentDateTime();
                    ws.send('{"command":"finger_image", "dateTime":"' + currentDateTime + '", details: []}');
                };

                ws.onmessage = function (evt) {
                    var received_msg = evt.data;
                    var jsonData = JSON.parse(received_msg);
                    if (jsonData.status == 'success') {

                        callXypData<?php echo $this->uniqId ?>(jsonData.details[0].value, uniqId, $propertyNumber, $stateRegNumber, $civilNumber, $legalEntityNumber, $selectedItem.attr('data-id'), $fingerPrint);

                    } else {
                        console.log(jsonData);
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

                ws.onerror = function (event) {
                    var resultJson = {
                        Status: 'Error',
                        Error: event.code
                    }

                    console.log(JSON.stringify(resultJson));
                };

                ws.onclose = function () {
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
    
    function callXypData<?php echo $this->uniqId ?>($fingerValue, uniqId, $propertyNumber, $stateRegNumber, $civilNumber, $legalEntityNumber, $typeId, $fingerPrint, $timestamp) {
        $.ajax({
            type: 'post',
            url: 'mddoc/getXypInformationDataBySignature',
            data: {
                operatorFinger: $fingerValue,
                finger: $fingerPrint,
                propertyNumber: $propertyNumber,
                stateRegNumber: $stateRegNumber,
                civilId: $civilNumber,
                legalEntityNumber: $legalEntityNumber,
                typeId: $typeId,
                timestamp: $timestamp,
                methodCode: 'static'
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (response) {

                $('.render-html-data-' + uniqId).empty();

                if (response.status === 'success') {

                    var $html = '';
                    var $responseData = response.data;
                    
                    switch ($typeId) {
                        case '1':
                            $html += '<table class="table table-sm table-hover">';
                            $html += '<tbody>';
                            if (typeof $responseData.nationality !== 'undefined' && $responseData.nationality !== '') {
                                $html += '<tr>'
                                        + '<td>Үндэс</td>'
                                        + '<td>' + $responseData.nationality + '</td>';

                                if (typeof $responseData.nationality !== 'undefined' && $responseData.nationality !== '') {
                                    $html += '<td rowspan="6"><img src="' + $responseData.image + '" class="rounded-circle" style="width: 95px;"></td>';
                                }

                                $html += '</tr>';
                            }
                            if (typeof $responseData.surname !== 'undefined' && $responseData.surname !== '') {
                                $html += '<tr>'
                                        + '<td>Ургийн овог</td>'
                                        + '<td>' + $responseData.surname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.lastname !== 'undefined' && $responseData.lastname !== '') {
                                $html += '<tr>'
                                        + '<td>Овог</td>'
                                        + '<td>' + $responseData.lastname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.firstname !== 'undefined' && $responseData.firstname !== '') {
                                $html += '<tr>'
                                        + '<td>Нэр</td>'
                                        + '<td>' + $responseData.firstname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.passportaddress !== 'undefined' && $responseData.passportaddress !== '') {
                                $html += '<tr>'
                                        + '<td>Иргэний үнэмлэх дээрх хаяг</td>'
                                        + '<td>' + $responseData.passportaddress + '</td>'
                                        + '</tr>';
                            }

                            $html += '</tbody>';
                            $html += '</table>';
                            break;
                        case '2':
                            if (typeof $responseData.listdata !== 'undefined' && $responseData.listdata) {
                                $.each($responseData.listdata, function ($index, $row) {
                                    if (typeof $row.propertynationregisternumber !== 'undefined' && $row.propertynationregisternumber) {
                                        $html += '<tr>';
                                        $html += '<td>Үл хөдлөхийн дугаар: </td>';
                                        $html += '<td> ' + $row.propertynationregisternumber + '</td>';
                                        $html += '</tr>';
                                    }
                                });
                            }
                            break;
                        case '3':
                            $html += '<table class="table table-sm table-hover">';
                            $html += '<tbody>';
                            if (typeof $responseData.aimagcityname !== 'undefined' && $responseData.aimagcityname !== '') {
                                $html += '<tr>'
                                        + '<td>Аймаг, хотын нэр</td>'
                                        + '<td>' + $responseData.aimagcityname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.soumdistrictname !== 'undefined' && $responseData.soumdistrictname !== '') {
                                $html += '<tr>'
                                        + '<td>Сум, дүүргийн нэр</td>'
                                        + '<td>' + $responseData.soumdistrictname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.bagkhorooname !== 'undefined' && $responseData.bagkhorooname !== '') {
                                $html += '<tr>'
                                        + '<td>Баг, хорооны нэр</td>'
                                        + '<td>' + $responseData.bagkhorooname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.addressapartmentname !== 'undefined' && $responseData.addressapartmentname !== '') {
                                $html += '<tr>'
                                        + '<td>Байрны нэр</td>'
                                        + '<td>' + $responseData.addressapartmentname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.addressdetail !== 'undefined' && $responseData.addressdetail !== '') {
                                $html += '<tr>'
                                        + '<td>Хаяг дэлгэрэнгүй</td>'
                                        + '<td>' + $responseData.addressdetail + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.address !== 'undefined' && $responseData.address !== '') {
                                $html += '<tr>'
                                        + '<td>Хаягийн мэдээлэл</td>'
                                        + '<td>' + $responseData.address + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.addressregionname !== 'undefined' && $responseData.addressregionname !== '') {
                                $html += '<tr>'
                                        + '<td>Хорооллын нэр</td>'
                                        + '<td>' + $responseData.addressregionname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.addressstreetname !== 'undefined' && $responseData.addressstreetname !== '') {
                                $html += '<tr>'
                                        + '<td>Гудамжны нэр</td>'
                                        + '<td>' + $responseData.addressstreetname + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.intent !== 'undefined' && $responseData.intent !== '') {
                                $html += '<tr>'
                                        + '<td>Зориулалт</td>'
                                        + '<td>' + $responseData.intent + '</td>'
                                        + '</tr>';
                            }
                            if (typeof $responseData.square !== 'undefined' && $responseData.square !== '') {
                                $html += '<tr>'
                                        + '<td>Хэмжээний мэдээлэл</td>'
                                        + '<td>' + $responseData.square + '</td>'
                                        + '</tr>';
                            }
                            $html += '</tbody>';
                            $html += '</table>';

                            $html += '<table class="table table-sm table-hover">';
                            $html += '<thead>';
                            $html += '<tr>';
                            $html += '<th></th>';
                            $html += '<th style="font-weight: 700; font-size: 14px; ">Огноо</th>';
                            $html += '<th style="font-weight: 700; font-size: 14px; ">Эзэмшлийн хэлбэр</th>';
                            $html += '<th style="font-weight: 700; font-size: 14px; ">Эзэмшигч</th>';
                            $html += '</tr>';
                            $html += '</thead>';
                            $html += '<tbody>';
                            var $indexX = 1;
                            if (typeof $responseData.processlist !== 'undefined' && $responseData.processlist) {
                                $.each($responseData.processlist, function ($index, $row) {
                                    $html += '<tr>';
                                    $html += '<td>' + $indexX + '</td>';
                                    $html += '<td>' + $row.date + '</td>';
                                    $html += '<td>' + $row.servicename + '</td>';
                                    $html += '<td>';
                                    $html += '<ul>';
                                    if (typeof $row.ownerdatallist !== 'undefined' && $row.ownerdatallist) {

                                        $.each($row.ownerdatallist, function ($sindex, $srow) {
                                            $html += '<li>';
                                            if (typeof $srow.forename !== 'undefined' && $srow.forename !== '') {
                                                $html += $srow.forename + ' ургийн овогтой ';
                                            }
                                            if (typeof $srow.lastname !== 'undefined' && $srow.lastname !== '') {
                                                $html += $srow.lastname + ' овогтой ';
                                            }
                                            if (typeof $srow.firstname !== 'undefined' && $srow.firstname !== '') {
                                                $html += $srow.firstname + ' ';
                                            }
                                            if (typeof $srow.registernumber !== 'undefined' && $srow.registernumber !== '') {
                                                $html += ' ( ' + $srow.registernumber + ') ';
                                            }
                                            $html += '</li>';
                                        });

                                    }
                                    $html += '</ul>';
                                    $html += '</td>';
                                    $html += '</tr>';
                                    $indexX++;
                                });    
                            }
                            
                            $html += '</tbody>';
                            $html += '</table>';
                            break;
                        case '4':
                            var $ticket = false;
                            $.each($responseData, function ($index, $rowData) {
                                $html += '<div class="w-100">';
                                    if (typeof $rowData === 'object') {
                                        $ticket = true;
                                    } else {
                                        $html += '<p>' + plang.get($index) + ': ' +  $rowData +'</p>';
                                    }
                                $html += '</div>';
                            });
                            
                            if ($ticket) {
                                $.each($responseData, function ($index, $rowData) {
                                    $html += '<div class="w-100">';
                                        if (typeof $rowData === 'object') {
                                            $html += '<p><strong>' + plang.get($index) + '</strong></p>';
                                            $html += '<div class="w-100 row pl15 pr15">';
                                                $html += recursiveHtml($rowData, '');
                                            $html += '</div>';
                                        }
                                    $html += '</div>';
                                });
                            }
                            
                            break;
                        default:
                            new PNotify({
                                title: 'Анхааруулга',
                                text: 'Хөгжүүлэлт хийгдэж байна,',
                                type: 'warning',
                                sticker: false
                            });
                            return;
                            break;
                    }

                    $('.render-html-data-' + uniqId).empty().append($html);
                } else {
                    $('.render-html-data-' + uniqId).empty();
                    new PNotify({
                        title: response.status,
                        text: response.message,
                        type: response.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function(jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
            }
        });
    }
    
    function recursiveHtml($data, $html) {
        var $ticket = false;
        $.each($data, function ($index, $rowData) {
            if (typeof $rowData === 'object') {
                $ticket = true;
            } else {
                $html += '<span class="col">' + plang.get($index) + ': ' +  $rowData +'</span>';
            }
        });

        if ($ticket) {
            $.each($data, function ($index, $rowData) {
                if (typeof $rowData === 'object') {
                    $html += '<legend>' + plang.get($index) + '</legend>';
                    $html += '<div class="w-100 row">';
                        $html += recursiveHtml($rowData, '');
                    $html += '</div>';
                };
            });
        }
        return $html;
    }
    
</script>

<style type="text/css">

    .select-item-<?php echo $this->uniqId ?> {
        width: 25%;
        border: 1px solid #CCC;
        height: 90px;
        background: #4CAF50;
        display: flex;
        align-items: center;
        vertical-align: middle;
        padding-top: 10px;
        padding-bottom: 10px;
        float: left;
    }

    .select-item-<?php echo $this->uniqId ?> label {
        float:left;
        width: 100%;
        text-align: center;
        vertical-align: middle;
        color: #FFF;
    }

    .select-item-<?php echo $this->uniqId ?>:hover, .select-item-<?php echo $this->uniqId ?>.active {
        background: #2881a9;
    }

    .select-item-<?php echo $this->uniqId ?>.active:before {
        position: absolute;
        display: block;
        content: '\f058';
        font-family: "Font Awesome 5 Pro";
        color: #fff;
        right: 5px;
        bottom: 5px;
        font-size: 15px;
        z-index: 999;
    }

</style>