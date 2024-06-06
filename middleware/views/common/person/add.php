<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'add-person-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
<div class="row xs-form">
    <div class="col-md-3">
        <div class="person-photo-wrap">
            <img src="assets/core/global/img/images.jpg" class="img-fluid rounded-circle img-border">
        </div>
        <a href="javascript:;" class="btn btn-circle btn-block default btn-sm fileinput-button mt10">
            Файл сонгох<input name="person_attach" onchange="personAttach(this);" type="file">
        </a>
        <button type="button" class="btn btn-circle btn-block default btn-sm" onclick="personScanner(this);">Сканнер</button>
        <button type="button" class="btn btn-circle btn-block default btn-sm" onclick="personWebCamera(this);">Вэбкамер</button>
        <input name="person_attach_photo" type="hidden">
        <input name="person_attach_photo_thumb" type="hidden">
        <input name="person_attach_photo_extension" type="hidden">
        <input name="person_attach_url" type="hidden">
    </div>
    <div class="col-md-9">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Регистр №', 'for' => 'registerNumber', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-4">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'registerNumber', 
                        'id' => 'registerNumber', 
                        'class' => 'form-control form-control-sm', 
                        'required' => 'required', 
                        'data-inputmask-regex' => '^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ]{2}[0-9]{8}$'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Овог', 'for' => 'lastName', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-8">
                <?php echo Form::text(array('name' => 'lastName', 'id' => 'lastName', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Нэр', 'for' => 'firstName', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-8">
                <?php echo Form::text(array('name' => 'firstName', 'id' => 'firstName', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Хүйс', 'for' => 'gender', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-8">
                <div class="radio-list">
                    <label class="radio-inline pt0">
                        <input type="radio" name="gender" value="0" checked="checked"> Эрэгтэй
                    </label>
                    <label class="radio-inline pt0">
                        <input type="radio" name="gender" value="1"> Эмэгтэй
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Ургийн овог', 'for' => 'familyName', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-8">
                <?php echo Form::text(array('name' => 'familyName', 'id' => 'familyName', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Төрсөн огноо', 'for' => 'birthDate', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-8">
                <div class="dateElement input-group">
                    <?php echo Form::text(array('name' => 'birthDate', 'id' => 'birthDate', 'class' => 'form-control form-control-sm dateInit', 'required' => 'required')); ?>
                    <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                </div>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Аймаг/Хот', 'for' => 'city', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-8">
                <?php echo Form::text(array('name' => 'city', 'id' => 'city', 'class'=>'form-control form-control-sm')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Сум/Дүүрэг', 'for' => 'district', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-8">
                <?php echo Form::text(array('name' => 'district', 'id' => 'district', 'class'=>'form-control form-control-sm')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Баг/Хороо', 'for' => 'street', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-8">
                <?php echo Form::text(array('name' => 'street', 'id' => 'street', 'class'=>'form-control form-control-sm')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Гудамж/Байр', 'for' => 'addressDetail', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-9">
                <?php echo Form::text(array('name' => 'addressDetail', 'id' => 'addressDetail', 'class'=>'form-control form-control-sm')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Хурууны хээ', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-8">
                <button type="button" class="btn grey-cascade btn-xs" onclick="importFingerPrint(this);"><i class="fa fa-hand-o-up"></i></button>
                <button type="button" class="btn btn-danger btn-xs finger-print-remove display-none"><i class="fa fa-times"></i> Устгах</button>
                <?php echo Form::hidden(array('name' => 'fingerPrint')); ?>
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function(){
    $('.finger-print-remove').on('click', function(){
        $('input[name=fingerPrint]').val('');
        $('.finger-print-remove').hide();
    });
});    
function personAttach(input) {
    if ($(input).hasExtension(['png','gif','jpeg','pjpeg','jpg','x-png','bmp'])) {
        $('#add-person-form').ajaxSubmit({
            type: 'post',
            url: 'mdcommon/addPersonUploadPhoto',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                
                PNotify.removeAll();
                
                if (data.status === 'success') {
                    
                    var imageData = data.imageData;
                    var img = '';
                    
                    img += '<a href="data:'+imageData.mimeType+';base64,'+imageData.origBase64Data+'" class="fancybox-button main" data-rel="fancybox-button">';
                        img += '<img src="data:'+imageData.mimeType+';base64,'+imageData.thumbBase64Data+'" class="img-fluid rounded-circle img-border"/>';
                    img += '</a>';
                        
                    $('.person-photo-wrap').html(img);
                    
                    $("input[name='person_attach_photo']").val(imageData.origBase64Data);    
                    $("input[name='person_attach_photo_thumb']").val(imageData.thumbBase64Data);
                    $("input[name='person_attach_photo_extension']").val(imageData.extension);
                    $("input[name='person_attach']").val('');
                    $("input[name='person_attach_url']").val('');

                    Core.initFancybox($('.person-photo-wrap'));
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });
        
    } else {
        alert(plang.get('msg_select_image'));
        $("input[name='person_attach']").val('');
    }
}
function personScanner(elem) {
    
    Core.blockUI({
        boxed: true, 
        message: 'Loading...'
    });
    
    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        var ws = new WebSocket("ws://localhost:58324/socket");
        var uniqueId = getUniqueId();

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"get_scan_image", "dateTime":"' + currentDateTime + '", details: [{"key": "filename", "value": "' + uniqueId + '"}, {"key": "server", "value": "' + URL_APP+'mddoceditor/vrClientScannerUpload' + '"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();

            if (jsonData.status == 'success') {
                
                var savedImg = 'storage/uploads/metavalue/photo_temp/original/'+uniqueId+'.jpeg';
                var img = '';
                
                img += '<a href="'+savedImg+'" class="fancybox-button main" data-rel="fancybox-button">';
                    img += '<img src="'+savedImg+'" class="img-fluid rounded-circle img-border"/>';
                img += '</a>';

                $('.person-photo-wrap').html(img);
                
                $("input[name='person_attach']").val('');
                $("input[name='person_attach_photo']").val('');    
                $("input[name='person_attach_photo_thumb']").val('');
                $("input[name='person_attach_photo_extension']").val('jpeg');
                $("input[name='person_attach_url']").val(savedImg);
                
                Core.initFancybox($('.person-photo-wrap'));
        
            } else {
                if (jsonData.description != null) {
                    new PNotify({
                        title: 'Error',
                        text: jsonData.description, 
                        type: 'error',
                        sticker: false
                    });
                }
            }
            
            Core.unblockUI();
        };

        ws.onerror = function (event) {
            if (event.code != null) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: event.code, 
                    type: 'error',
                    sticker: false
                });
            }
            
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Core.unblockUI();
        };
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'error',
            sticker: false
        });
        
        Core.unblockUI();
    }
}
function personWebCamera(elem) {
    
    $.getScript(URL_APP+"assets/custom/addon/plugins/swfobject/swfobject.js").done(function() {
        $.getScript(URL_APP+'assets/custom/addon/plugins/webcam/scriptcam/scriptcam.js').done(function() {
            
            var dialogName = '#dialog-person-photo-webcam';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
        
            $.ajax({
                type: 'post',
                url: 'mdprocess/bpTmpAddPhotoFromWebcam',
                dataType: 'json',
                beforeSend: function(){
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function(data) {
                    $(dialogName).html(data.html);
                    $(dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 800,
                        height: 550,
                        modal: true, 
                        close: function () { 
                            $(dialogName).empty().dialog('destroy').remove(); 
                        },
                        buttons: [
                            {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                                    
                                var savedImg = $('form#bpWebcam-form').find("input[name='base64Photo']").val();
                                var img = '';

                                img += '<a href="data:image/png;base64,'+savedImg+'" class="fancybox-button main" data-rel="fancybox-button">';
                                    img += '<img src="data:image/png;base64,'+savedImg+'" class="img-fluid rounded-circle img-border"/>';
                                img += '</a>';
                                
                                $("input[name='person_attach']").val('');
                                $("input[name='person_attach_photo']").val(savedImg);    
                                $("input[name='person_attach_photo_thumb']").val(savedImg);
                                $("input[name='person_attach_photo_extension']").val('png');
                                $("input[name='person_attach_url']").val(savedImg);
                                
                                $('.person-photo-wrap').html(img).promise().done(function(){
                                    Core.initFancybox($('.person-photo-wrap'));
                                });
                                
                                $(dialogName).dialog('close');
                            }},
                            {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $(dialogName).dialog('close');
                            }}
                        ]
                    });
                    $(dialogName).dialog('open');    
                    
                    Core.unblockUI();
                },
                error: function() {
                    alert("Error");
                }
            });
        });
    });
}
function importFingerPrint(elem) {
    var _this = $(elem);
    var _parent = _this.closest('.bp-signature');
    
    Core.blockUI({
        boxed: true, 
        message: 'Loading...'
    });
    
    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"fingerprint_register", "dateTime":"' + currentDateTime + '"}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();

            if (jsonData.status == 'success' && 'details' in Object(jsonData)) {
                
                var fingerPrintObj = convertDataElementToArray(jsonData.details);
                
                $('input[name=fingerPrint]').val(fingerPrintObj.fingerTemplate);
                
                $('.finger-print-remove').show();
                
            } else {
                new PNotify({
                    title: 'Error',
                    text: jsonData.description, 
                    type: 'error',
                    sticker: false
                });
                $('input[name=fingerPrint]').val('');
                $('.finger-print-remove').hide();
            }
            
            Core.unblockUI();
        };

        ws.onerror = function (event) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: event.code, 
                type: 'error',
                sticker: false
            });
            
            $('input[name=fingerPrint]').val('');
            $('.finger-print-remove').hide();
            
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Core.unblockUI();
        };
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'error',
            sticker: false
        });
        
        Core.unblockUI();
    }
}
</script>