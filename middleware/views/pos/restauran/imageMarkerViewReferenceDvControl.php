<div class="row" style="background-color: #4D4D4D; height: 100%">
    <?php if ($this->isIpad) { ?>
        <div class="col-md-12">
            <div id="sidebardv-<?php echo $this->uniqId; ?>" class="d-flex" style="gap:5px">
                <div class="dv-process-buttons d-flex" style="gap:5px;z-index: 100;">
                    <a class="btn btn-secondary btn-circle btn-sm d-none" title="" onclick="" href="javascript:;"><i class="fa fa-print"></i></a>
                    <a style="padding: 10px;display: block;font-size: 13px;" class="mt12 btn green btn-circle btn-sm restChangeTable" title="" onclick="restChangeTable()" href="javascript:;"><i class="fa fa-exchange"></i> <span>Ширээ солих</span></a>
                    <div>
                        <a style="padding: 10px;display: block;font-size: 13px;" class="mt12 btn green btn-circle btn-sm restMergeTable" title="" onclick="restMergeTable()" href="javascript:;"><i class="fa fa-random"></i> <span>Ширээ нийлүүлэх</span></a>
                        <button type="button" class="d-none btn green btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" style="position: absolute;right: 0;margin-top: -42px;border: none" aria-expanded="false"></button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(163px, 36px, 0px);">
                            <a href="javascript:;" class="dropdown-item pr20 restPieceMergeTable pl20" onclick="restPieceMergeTable()"><h3 style="font-size:18px; margin-bottom: 0">Хэсэгчилж нийлүүлэх</h3></a>
                        </div>                
                    </div>                
                    <a style="padding: 10px;display: block;font-size: 13px;" class="mt12 btn green btn-circle btn-sm restSplitTable" title="" onclick="restSplitTable()" href="javascript:;"><i class="fa fa-expand"></i> <span>Тооцоо салгах</span></a>
                    <a style="padding: 10px;display: block;font-size: 13px;" class="mt12 btn green btn-circle btn-sm" title="" onclick="restNextBillTable()" href="javascript:;"><i class="fa fa-clipboard"></i> <span>Дараа тооцоо</span></a>
                    <a style="padding: 10px;display: block;font-size: 13px;" class="mt12 btn btn-danger btn-circle btn-sm" title="" onclick="restReturnTable(this)" href="javascript:;"><i class="fa fa-trash"></i> <span>Ширээ устгах</span></a>
                    <a style="padding: 10px;display: block;font-size: 13px;" class="mt12 btn btn-sm btn-circle green" title="" onclick="dataViewPrintPreview_<?php echo $this->uniqId; ?>('<?php echo $this->dataViewId; ?>', true, 'toolbar', this);" href="javascript:;"><i class="fa fa-print"></i> <span>Хэвлэх</span></a>
                </div>
                <div class="dv-process-buttons">
                    <?php echo $this->dataViewProcessCommand['commandBtn']; ?>
                </div>          
            </div>            
        </div>
        <div class="col-md-12">
            <div class="not-datagrid" id="objectdatagrid-<?php echo $this->dataViewId; ?>"></div>
            <div class="row imageMarkerReferenceViewContainer" data-main-locationId='<?php echo $this->locationId; ?>' id="windowid-<?php echo $this->uniqId; ?>"> 
                <div id="jcropDiv" class="pl10"></div>
            </div>
        </div>    
    <?php } else { ?>
    <div class="col-md-10">
        <div class="not-datagrid" id="objectdatagrid-<?php echo $this->dataViewId; ?>"></div>
        <div class="row imageMarkerReferenceViewContainer" data-main-locationId='<?php echo $this->locationId; ?>' id="windowid-<?php echo $this->uniqId; ?>"> 
            <div id="jcropDiv" class="pl10"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div id="sidebardv-<?php echo $this->uniqId; ?>" class="freeze-overflow-xy-auto" style="position:fixed;">
            <div class="dv-process-buttons">
                <a class="btn btn-secondary btn-circle btn-sm d-none" title="" onclick="" href="javascript:;"><i class="fa fa-print"></i></a>
                <a style="padding: 15px 25px 15px 25px; display: block; font-size: 18px;" class="mt12 btn green btn-circle btn-sm restChangeTable" title="" onclick="restChangeTable()" href="javascript:;"><i class="fa fa-exchange"></i> <span>Ширээ солих</span></a>
                <div>
                    <a style="padding: 15px 25px 15px 25px; display: block; font-size: 18px;" class="mt12 btn green btn-circle btn-sm restMergeTable" title="" onclick="restMergeTable()" href="javascript:;"><i class="fa fa-random"></i> <span>Ширээ нийлүүлэх</span></a>
                    <button type="button" class="d-none btn green btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" style="position: absolute;right: 0;margin-top: -42px;border: none" aria-expanded="false"></button>
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(163px, 36px, 0px);">
                        <a href="javascript:;" class="dropdown-item pr20 restPieceMergeTable pl20" onclick="restPieceMergeTable()"><h3 style="font-size:18px; margin-bottom: 0">Хэсэгчилж нийлүүлэх</h3></a>
                    </div>                
                </div>                
                <a style="padding: 15px 25px 15px 25px; display: block; font-size: 18px;" class="mt12 btn green btn-circle btn-sm restSplitTable" title="" onclick="restSplitTable()" href="javascript:;"><i class="fa fa-expand"></i> <span>Тооцоо салгах</span></a>
                <a style="padding: 15px 25px 15px 25px; display: block; font-size: 18px;" class="mt12 btn green btn-circle btn-sm" title="" onclick="restNextBillTable()" href="javascript:;"><i class="fa fa-clipboard"></i> <span>Дараа тооцоо</span></a>
                <a style="padding: 15px 25px 15px 25px; display: block; font-size: 18px;" class="mt12 btn btn-danger btn-circle btn-sm" title="" onclick="restReturnTable(this)" href="javascript:;"><i class="fa fa-trash"></i> <span>Ширээ устгах</span></a>
                <a style="padding: 15px 25px 15px 25px; display: block; font-size: 18px;" class="mt12 btn btn-sm btn-circle green" title="" onclick="dataViewPrintPreview_<?php echo $this->uniqId; ?>('<?php echo $this->dataViewId; ?>', true, 'toolbar', this);" href="javascript:;"><i class="fa fa-print"></i> <span>Хэвлэх</span></a>
                <a style="padding: 15px 25px 15px 25px; display: block; font-size: 18px;" class="mt12 btn green btn-circle btn-sm" title="" onclick="newOrder(this)" href="javascript:;"><i class="fa fa-plus"></i> <span>Захиалга бүртгэх</span></a>
            </div>
            <div class="dv-process-buttons">
                <?php echo $this->dataViewProcessCommand['commandBtn']; ?>
            </div>          
        </div>
    </div>
    <?php } ?>
</div>

<style type="text/css">
    .imageMarkerViewDivImage {
        cursor:pointer;
        position: absolute;
        z-index: 97;
        background-color: rgba(209, 210, 40, 0.58);
        border: 2px solid transparent;
    }
    .imageMarkerViewDivImage2 {
        cursor:pointer;
        position: absolute;
        z-index: 97;
        border: 2px solid transparent;
    }
    .imageMarkerViewDivImage2:hover, .imageMarkerViewDivImage2.selected-row {
        outline: black solid thick;
    }
    .imageMarkerViewDivImageExist {
        background: url("<?php echo URL; ?>assets/core/global/img/imageMarkerViewExist.gif");
        background-size: 28px 27px;
        background-repeat: no-repeat; 
        background-position: center center;
        background-color: rgba(209, 210, 40, 0.58);
    }
    .jcrop-tracker {
        z-index: 96 !important;
    }
    .fancyTable thead tr th, .fancyTable thead tr td {
        font-weight: normal;
    }    
    #sidebardv-<?php echo $this->uniqId; ?> .dropdown-toggle::after {
        font-size: 26px;
        font-weight: bold;
    }    
    .resttable-80:after,
    .resttable-81:after,
    .resttable-76:after,
    .resttable-77:after,
    .resttable-78:after,
    .resttable-75:after {
        border-style: solid;
        border-width: 0 26px 15px 26px;
        border-color: #4D4D4D #4D4D4D transparent #4D4D4D;
        content: "";
        position: absolute;
        left: -4px;
        top: -2px;        
    }
</style>

<script type="text/javascript">
    var windowId_<?php echo $this->uniqId; ?> = "#windowid-<?php echo $this->uniqId; ?>";
    var j = '', restPosEventType = {'event': '', data: []};
    var postParams = <?php echo json_encode($this->postParams); ?>;
    var objectdatagrid_<?php echo $this->dataViewId ?> = $("#objectdatagrid-<?php echo $this->dataViewId; ?>");
    
    $(function(){        
        $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).empty().append('<h1 style="position:absolute;color:#595959;font-size:130px;opacity:.5;">Veritech ERP</h1>');
            
        $('#sidebardv-<?php echo $this->uniqId; ?>').css('max-height', $(window).height() - 105 + 'px');
        $('#sidebardv-<?php echo $this->uniqId; ?>').css('width', $('#sidebardv-<?php echo $this->uniqId; ?>').parent().width() + 'px');
        
        $(document).on('click', '.multipleCheckLocation', function(e){
            var $this = $(this);
            if ($this.is(':checked')) {
                var locationId = $this.closest('.imageMarkerViewDivImage2').attr('data-locationId');
                $this.closest('.imageMarkerViewDivImage2').addClass('selected-row');
            } else {
                $this.closest('.imageMarkerViewDivImage2').removeClass('selected-row');
            }
        });        
        
        $(document).on('click', '.imageMarkerViewDivImage2', function(e){
            if ($(e.target)[0]['nodeName'] !== 'INPUT') {
                var $this = $(this);
                $this.parent().find('.selected-row').find('.multipleCheckLocation').prop('checked', false);
                $this.parent().find('.selected-row').removeClass('selected-row');
                $this.addClass('selected-row'); 
                $this.find('.multipleCheckLocation').prop('checked', true);
            }
        });               
        
        getLocationHtml();
        
//        $("#objectdatagrid-<?php echo $this->dataViewId; ?>").on('mouseenter mouseleave', '.imageMarkerViewDivImage2', function(e){
//            var _this = $(this);            
//            if (e.type === 'mouseleave' && !_this.find('.multipleCheckLocation').is(':checked')) {
//                _this.children('span:last').addClass('hidden');
//            } else {
//                _this.children('span:last').removeClass('hidden');
//            }
//        });  
//        
//        $("#objectdatagrid-<?php echo $this->dataViewId; ?>").on('mouseenter mouseleave', '.imageMarkerViewDivImage2', function(e){
//            var _this = $(this);            
//            if (e.type === 'mouseleave' && !_this.find('.multipleCheckLocation').is(':checked')) {           
//                _this.children('span:last').addClass('hidden');
//            } else {
//                _this.children('span:last').removeClass('hidden');
//            }
//        });  

        /**
         * remove config weblink
         */
        $('#sidebardv-<?php echo $this->uniqId; ?>').find("a[data-dvbtn-processcode='imagemarkerwithdv']").hide();        
    });
    
    function refreshLocationPhoto(rowId, photoUrl) {
        console.log(rowId);
        console.log(photoUrl);
        $('.imageMarkerReferenceViewContainer:visible:last').find('.imageMarkerViewDivImage[data-locationkeyid="'+rowId+'"]').css('background-image', 'url('+photoUrl+')');
    }
    
    function savePositionChair(elem, id) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdpos/saveLocationImage',
            data: {
                id: id,
                location: $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).attr('data-imagejsonstring')
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                
            },
            success: function (data) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Success',
                    text: 'Амжилттай хадгалагдлаа',
                    type: 'success', 
                    sticker: false
                });                
                getLocationHtml();                
                Core.unblockUI();
            },
            error: function(){
              alert("Error");
            }
        }).done(function(){
        });        
    }
    
    function getTables(stype, callback) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdpos/getTables',
            data: {
                stype: stype,
                dataViewId: '16116476587841'
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                
            },
            success: function (data) {
                callback(data);
            },
            error: function(){
              alert("Error");
              Core.unblockUI();
            }
        }).done(function(){
        });        
    }
    
    function getLocationHtml() {
        getTables('1', function(data){
            if (data && data.length) {
                var htmlLocation = '', position, additionalStyle = '';
                $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).empty().append('<img src="'+data[0]['planpicture']+'"/>');
                for (var i = 0; i < data.length; i++) {
                    if (data[i]['locationname'] && data[i]['position']) {
                        additionalStyle = additionalRestStyle(data[i]);
                        position = JSON.parse(html_entity_decode(data[i]['position']));
                        htmlLocation += '<div class="imageMarkerViewDivImage2 resttable-'+data[i]['locationcode']+'" data-additionalstyle="'+additionalStyle+'" data-row-data="'+htmlentities(JSON.stringify(data[i]), 'ENT_QUOTES')+'" data-locationId="'+data[i]['id']+'" data-locationName="'+data[i]['locationname']+'" style="'+additionalStyle+'left:'+(position['x']+11)+'px;top:'+(position['y']+1)+'px;height:'+position['h']+'px;width:'+position['w']+'px;background-color:'+data[i]['rowcolor']+'">'+
                                        "<span style='display:none;position: absolute;margin-top: -19px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;color: #ddd;'>"+data[i]['locationname']+"</span>"+
                                        "<span class='' style='position: absolute;bottom: -7px;left: -3px;'><input type='checkbox' style='height: 20px;width: 20px;' class='multipleCheckLocation'/></span>";
                        htmlLocation += "</div>";
                    }
                }
                $("#objectdatagrid-<?php echo $this->dataViewId; ?>").empty().append(htmlLocation);
                if ($('#posLocationId').val()) {
                    $('div[data-locationid="'+$('#posLocationId').val()+'"]').addClass('selected-row');
                    
                    /**
                     * Quick actions
                     */
                    if (postParams['openType'] == 'quick-change') {
                        restChangeTable();
                    } else if(postParams['openType'] == 'quick-merge') {
                        restMergeTable();
                    } else if(postParams['openType'] == 'quick-split') {
                        restSplitTable()
                    } else if(postParams['openType'] == 'quick-delete') {    
                        restReturnTable()
                    }                    
                }
            } else {
                $("#objectdatagrid-<?php echo $this->dataViewId; ?>").empty().html('<h3>План зургийн байршил тодорхойлж өгөөгүй байна!</h3>');
            }
            Core.unblockUI();
        });
    }
    
    function restChangeTable(elem) {
        var $this = $('#sidebardv-<?php echo $this->uniqId; ?>').find('.restChangeTable');
        if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length) {
            if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length > 1) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Info',
                    text: 'Нэг ширээ сонгоно уу!',
                    type: 'info', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });             
                return;
            }

            var selectedRow = JSON.parse(objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').attr('data-row-data'));
            
            if (typeof $this.attr('data-first-table') !== 'undefined') {                                
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                     
                
                var firstTable = JSON.parse($this.attr('data-first-table'));
                $.ajax({
                    type: 'post',
                    url: 'mdpos/changeTableRest',
                    data: {
                        firstTable: firstTable, 
                        secondTable: selectedRow
                    }, 
                    dataType: 'json',
                    success: function(data) {
                        PNotify.removeAll();            
                        $this.removeAttr('data-first-table');            
                        if (data.status === 'success') {
                            new PNotify({
                                title: 'Амжилттай',
                                text: firstTable['locationname']+' => '+selectedRow['locationname']+' амжилттай солигдлоо.',
                                type: 'success', 
                                sticker: false, 
                                addclass: 'pnotify-center'
                            });   
                            getLocationHtml();
                            restClears();                            
                        } else {
                            new PNotify({
                                title: 'Error',
                                text: data.message,
                                type: 'error', 
                                sticker: false, 
                                addclass: 'pnotify-center'
                            });                             
                        }
                        Core.unblockUI();
                    }
                });                
            } else {
                var $dialogName = 'dialog-talon-protect';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialogP = $('#' + $dialogName);

                $dialogP.empty().append('<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>');
                $dialogP.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Нууц үг оруулах', 
                    width: 400,
                    height: 'auto',
                    modal: true,
                    open: function () {
                        $(this).keypress(function (e) {
                            if (e.keyCode == $.ui.keyCode.ENTER) {
                                $(this).parent().find(".ui-dialog-buttonpane button:first").trigger('click');
                            }
                        });
                        $('input[name="talonListPass"]').on('keydown', function(e){
                            var keyCode = (e.keyCode ? e.keyCode : e.which);
                            if (keyCode == 13) {
                                $(this).closest('.ui-dialog').find(".ui-dialog-buttonpane button:first").trigger('click');
                            }
                        });
                    },
                    close: function () {
                        $dialogP.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('insert_btn'), class: 'btn btn-sm green-meadow', click: function () {

                            PNotify.removeAll();
                            var $form = $('#talonListPassForm');

                            $form.validate({errorPlacement: function () {}});

                            if ($form.valid()) {
                                if (posCheckZBpassword) {
                                    $.ajax({
                                        type: 'post',
                                        url: "api/callDataview",
                                        data: {
                                            dataviewId: "16237213033721",
                                            criteriaData: {
                                                pincode: [{
                                                        operator: "=",
                                                        operand: $form.find('input[name="talonListPass"]').val(),
                                                    }
                                                ],
                                            },
                                        },
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({
                                                message: 'Loading...',
                                                boxed: true
                                            });
                                        },
                                        success: function (dataSub) {
                                            if (dataSub.status == "success" && Object.keys(dataSub.result).length) {
                                                $dialogP.dialog('close');       
                                                /*objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').removeClass('selected-row');*/
                                                $this.attr('data-first-table', JSON.stringify(selectedRow));
                                                new PNotify({
                                                    title: 'Info',
                                                    text: 'Солих ширээгээ сонгоод DOUBLE CLICK дарна уу.', 
                                                    type: 'info', 
                                                    sticker: false,
                                                    addclass: 'pnotify-center'
                                                });                            
                                                restPosEventType['event'] = 'changeTable';
                                            } else {
                                                new PNotify({
                                                    title: 'warning',
                                                    text: 'ЗБ нууц үг буруу байна!',
                                                    type: 'warning',
                                                    sticker: false
                                                });
                                            }
                                            Core.unblockUI();
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdpos/checkTalonListPass', 
                                        data: $form.serialize(),
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Loading...',
                                                boxed: true
                                            });
                                        },
                                        success: function(dataSub) {
                                            if (dataSub.status == 'success') {
                                                $dialogP.dialog('close');       
                                                /*objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').removeClass('selected-row');*/
                                                $this.attr('data-first-table', JSON.stringify(selectedRow));
                                                new PNotify({
                                                    title: 'Info',
                                                    text: 'Солих ширээгээ сонгоод DOUBLE CLICK дарна уу.', 
                                                    type: 'info', 
                                                    sticker: false,
                                                    addclass: 'pnotify-center'
                                                });                            
                                                restPosEventType['event'] = 'changeTable';
                                            } else {
                                                new PNotify({
                                                    title: dataSub.status,
                                                    text: dataSub.message, 
                                                    type: dataSub.status, 
                                                    sticker: false
                                                });
                                            }
                                            Core.unblockUI();
                                        }
                                    });
                                }
                            }
                        }}, 
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                            $dialogP.dialog('close');
                        }}
                    ]
                });
                $dialogP.dialog('open');
            }
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Ширээгээ сонгоно уу!',
                type: 'info', 
                sticker: false, 
                addclass: 'pnotify-center'
            });              
        }
    }
    
    function newOrder(elem) {
        var $dialogNameWaterPin = "dialog-waiter-pincode";
        if (!$("#" + $dialogNameWaterPin).length) {
          $('<div id="' + $dialogNameWaterPin + '"></div>').appendTo("body");
        }
        var $dialogWaiterPin = $("#" + $dialogNameWaterPin);

        $dialogWaiterPin.empty().append(
            '<form method="post" autocomplete="off" id="waiterPassForm"><input type="password" name="waiterPinCode" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
        );
        $dialogWaiterPin.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "Зөөгчийн нууц үг оруулна уу",
          width: 400,
          height: "auto",
          modal: true,
          open: function () {
            $dialogWaiterPin.on(
              "keydown",
              'input[name="waiterPinCode"]',
              function (e) {
                var keyCode = e.keyCode ? e.keyCode : e.which;
                if (keyCode == 13) {
                  $(this).closest(".ui-dialog").find(".ui-dialog-buttonpane button:first").trigger("click");
                  return false;
                }
              }
            );
          },
          close: function () {
            $dialogWaiterPin.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("insert_btn"),
              class: "btn btn-sm green-meadow",
              click: function () {
                PNotify.removeAll();
                var $form = $("#waiterPassForm");

                $form.validate({ errorPlacement: function () {} });

                if ($form.valid()) {
                  var waiterObj = [];

                    $.ajax({
                        type: "post",
                        url: "api/callDataview",
                        data: {
                            dataviewId: "16207061606511",
                            criteriaData: {
                                pincode: [
                                {
                                    operator: "=",
                                    operand: $form
                                    .find('input[name="waiterPinCode"]')
                                    .val(),
                                },
                                ],
                            },
                        },
                        dataType: "json",
                        async: false,
                        beforeSend: function () {
                            Core.blockUI({
                                message: "Loading...",
                                boxed: true,
                            });
                        },
                        success: function (dataSub) {
                            if (
                                dataSub.status == "success" &&
                                dataSub.result.length
                            ) {
                                waiterObj = dataSub.result;

                                $("#posRestWaiterId").val(waiterObj[0]["id"]);
                                $("#posRestWaiter").val(waiterObj[0]["salespersonname"]);
                                $(".rest-table-btn").find("div").html($(".rest-table-btn").find("div").html() + "<div>[ Сонгосон зөөгч: <strong>" + waiterObj[0]["salespersonname"] + "</strong> ]</div>");                        
                                $dialogWaiterPin.dialog("close");
                                setTimeout(function () {
                                    $("#dialog-pos-rest-tables").dialog("close");
                                }, 300);                     
                            } else {
                                new PNotify({
                                title: "Анхааруулга",
                                text: "Нууц үг буруу байна!",
                                type: "warning",
                                sticker: false,
                                });    
                            }
                            Core.unblockUI();
                        },
                    });                                 
                }
              },
            },
            {
              text: plang.get("close_btn"),
              class: "btn btn-sm blue-madison",
              click: function () {
                $dialogWaiterPin.dialog("close");
              },
            },
          ],
        });
        $dialogWaiterPin.dialog("open");                
    }

    function restReturnTable(elem) {
        if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length) {
            var selectedRow = JSON.parse(objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').attr('data-row-data'));
            
            PNotify.removeAll();
            if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length > 1) {
                new PNotify({
                    title: 'Info',
                    text: 'Нэг ширээ сонгоно уу!',
                    type: 'info', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });             
                return;
            }            
            
            if (!selectedRow['salesorderid']) {
                new PNotify({
                    title: 'Info',
                    text: 'Хоосон ширээ байна буцаах боломжгүй!',
                    type: 'info', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });                   
                return;
            }
            
            var $dialogName = 'dialog-talon-protect';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialogP = $('#' + $dialogName);

            $dialogP.empty().append('<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>');
            $dialogP.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Нууц үг оруулах', 
                width: 400,
                height: 'auto',
                modal: true,
                open: function () {
                    $(this).keypress(function (e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger('click');
                        }
                    });
                    $('input[name="talonListPass"]').on('keydown', function(e){
                        var keyCode = (e.keyCode ? e.keyCode : e.which);
                        if (keyCode == 13) {
                            $(this).closest('.ui-dialog').find(".ui-dialog-buttonpane button:first").trigger('click');
                        }
                    });
                },
                close: function () {
                    $dialogP.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('insert_btn'), class: 'btn btn-sm green-meadow', click: function () {

                        PNotify.removeAll();
                        var $form = $('#talonListPassForm');

                        $form.validate({errorPlacement: function () {}});

                        if ($form.valid()) {
                            $.ajax({
                                type: 'post',
                                url: "api/callDataview",
                                data: {
                                    dataviewId: "16237213033721",
                                    criteriaData: {
                                        pincode: [{
                                            operator: "=",
                                            operand: $form.find('input[name="talonListPass"]').val(),
                                        }],
                                    },
                                },                                    
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({
                                        message: 'Loading...',
                                        boxed: true
                                    });
                                },
                                success: function(dataSub) {
                                    if (dataSub.status == "success" && Object.keys(dataSub.result).length) {       
                                        $dialogP.dialog('close');       
                                        $.ajax({
                                            type: 'post',
                                            url: 'mdpos/returnTableRest',
                                            data: {
                                                id: selectedRow['salesorderid']
                                            }, 
                                            beforeSend: function() {
                                                Core.blockUI({message: 'Loading...', boxed: true});
                                            },                                               
                                            dataType: 'json',
                                            success: function(data) {
                                                if (data.status === 'success') {
                                                    new PNotify({
                                                        title: 'Success',
                                                        text: 'Амжилттай',
                                                        type: 'success', 
                                                        sticker: false, 
                                                        addclass: 'pnotify-center'
                                                    });   
                                                    getLocationHtml();
                                                    restClears();
                                                } else {
                                                    new PNotify({
                                                        title: 'Error',
                                                        text: data.message,
                                                        type: 'error', 
                                                        sticker: false, 
                                                        addclass: 'pnotify-center'
                                                    });                             
                                                }
                                                Core.unblockUI();
                                            }
                                        });                                   
                                    } else {
                                        new PNotify({
                                            title: 'warning',
                                            text: 'ЗБ нууц үг буруу байна!', 
                                            type: 'warning', 
                                            sticker: false
                                        });
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                        $dialogP.dialog('close');
                    }}
                ]
            });
            $dialogP.dialog('open');
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Ширээгээ сонгоно уу!',
                type: 'info', 
                sticker: false, 
                addclass: 'pnotify-center'
            });              
        }
    }
    
    function restNextBillTable(elem) {
        if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length) {
            var selectedRow = JSON.parse(objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').attr('data-row-data'));
            
            PNotify.removeAll();
            if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length > 1) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Info',
                    text: 'Нэг ширээ сонгоно уу!',
                    type: 'info', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });             
                return;
            }

            if (!selectedRow['salesorderid']) {
                new PNotify({
                    title: 'Info',
                    text: 'Хоосон ширээ байна!',
                    type: 'info', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });                   
                return;
            }
            
            var $dialogName = 'dialog-talon-protect';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialogP = $('#' + $dialogName);

            $dialogP.empty().append('<form method="post" autocomplete="off" id="additionalDesc"><input type="text" name="name" class="form-control" placeholder="Нэр" autocomplete="off" required="required"><textarea type="text" name="description" placeholder="Тайлбар" class="form-control mt8" autocomplete="off" required="required"></textarea></form>');
            $dialogP.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Нэмэлт тайлбар', 
                width: 400,
                height: 'auto',
                modal: true,
                open: function () {
                    $(this).keypress(function (e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger('click');
                        }
                    });
                    $('input[name="talonListPass"]').on('keydown', function(e){
                        var keyCode = (e.keyCode ? e.keyCode : e.which);
                        if (keyCode == 13) {
                            $(this).closest('.ui-dialog').find(".ui-dialog-buttonpane button:first").trigger('click');
                        }
                    });
                },
                close: function () {
                    $dialogP.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('insert_btn'), class: 'btn btn-sm green-meadow', click: function () {

                        PNotify.removeAll();
                        var $form = $('#additionalDesc');

                        $form.validate({errorPlacement: function () {}});

                        if ($form.valid()) {
                            $dialogP.dialog('close');       
                            $.ajax({
                                type: 'post',
                                url: 'mdpos/nextBillTableRest',
                                data: 'id='+selectedRow['salesorderid']+'&'+$form.serialize(), 
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },                                
                                dataType: 'json',
                                success: function(data) {
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: 'Амжилттай',
                                            type: 'success', 
                                            sticker: false, 
                                            addclass: 'pnotify-center'
                                        });   
                                        getLocationHtml();
                                        restClears();
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error', 
                                            sticker: false, 
                                            addclass: 'pnotify-center'
                                        });                             
                                    }
                                    Core.unblockUI();
                                }
                            });              
                        }
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                        $dialogP.dialog('close');
                    }}
                ]
            });
            $dialogP.dialog('open');
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Ширээгээ сонгоно уу!',
                type: 'info', 
                sticker: false, 
                addclass: 'pnotify-center'
            });              
        }
    }
    
    function dataViewPrintPreview_<?php echo $this->uniqId; ?>(mainMetaDataId, isDialog, whereFrom, elem, isOneRow) {
        
        setTimeout(function () {        
            if (typeof isOneRow !== 'undefined' && isOneRow) {
                var _datagridRowIndex = $(elem).closest('tr').attr('datagrid-row-index');
                var getRows = getDataViewSelectedRows(mainMetaDataId);
                var rows = [];
                rows[0] = typeof getRows[_datagridRowIndex] === 'undefined' ? getRows[0] : getRows[_datagridRowIndex];
            } else {
                var rows = getDataViewSelectedRows(mainMetaDataId);
            }
            
            if (rows.length === 0) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Info',
                    text: plang.get('msg_pls_list_select'), 
                    type: 'info', 
                    sticker: false,
                    addclass: 'pnotify-center'
                });
                return;
            }

            var dvCriteria = [];

            var response = $.ajax({
                type: "post",
                url: "api/callProcess",
                data: {
                    processCode: "SOD_CUSTOMER_COUNT_004",
                    paramData: { 
                        salesorderid: rows[0]["salesorderid"]
                    },
                },
                dataType: "json",
                async: false,
            });
            var responseParam = response.responseJSON; 
            
            if (responseParam.status == "success" && responseParam.result.count > 1) {
                var $dialogName = "dialog-location-dataview";
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo("body");
                }
                var $dialog = $("#" + $dialogName);

                $.ajax({
                    type: "post",
                    url: "mdobject/dataValueViewer",
                    data: {
                        metaDataId: "1622644015973310",
                        viewType: "detail",
                        dataGridDefaultHeight: 400,
                        uriParams: '{"locationId": "' + rows[0]["id"] + '", "filterCashRegisterId": "<?php echo Session::get(SESSION_PREFIX.'cashRegisterId'); ?>"}',
                        ignorePermission: 1,
                    },
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true,
                        });
                    },
                    success: function (dataHtml) {
                    $dialog
                        .empty()
                        .append(
                        '<div class="row" id="object-value-list-1622644015973310">' +
                            dataHtml +
                            "</div>"
                        );
                    $dialog
                        .dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: "Харилцагчийн үйлчилгээнүүд",
                            position: { my: "top", at: "top+50" },
                            width: 1000,
                            height: "auto",
                            modal: true,
                            open: function () {
                                $dialog
                                .find(".top-sidebar-content:eq(0)")
                                .attr("style", "padding-left: 15px !important");
                                $dialog.find('a[onclick*="toQuickMenu"]').remove();
                            },
                            close: function () {
                                $dialog.empty().dialog("destroy").remove();
                            },
                            buttons: [{
                                text: "Хаах",
                                class: "btn blue-madison btn-sm",
                                click: function () {
                                    $dialog.dialog("close");
                                },
                            }],
                        })
                        .dialogExtend({
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
                        }
                        });

                    $dialog.dialog("open");

                    $dialog.bind("dialogextendminimize", function () {
                        $dialog
                        .closest(".ui-dialog")
                        .nextAll(".ui-widget-overlay:first")
                        .addClass("display-none");
                    });
                    $dialog.bind("dialogextendmaximize", function () {
                        $dialog
                        .closest(".ui-dialog")
                        .nextAll(".ui-widget-overlay:first")
                        .removeClass("display-none");
                    });
                    $dialog.bind("dialogextendrestore", function () {
                        $dialog
                        .closest(".ui-dialog")
                        .nextAll(".ui-widget-overlay:first")
                        .removeClass("display-none");
                    });

                    Core.unblockUI();
                    },
                    error: function () {
                    alert("Error");
                    },
                }).done(function () {
                    //Core.initDVAjax($dialog);
                });
                
            } else {            

                rows[0]["filterCashRegisterId"] = "<?php echo Session::get(SESSION_PREFIX.'cashRegisterId'); ?>";
                if (responseParam.status == "success" && responseParam.result.count == 1) {
                    rows[0]["customerId"] = responseParam.result.customerids;
                }

                $.ajax({
                    type: 'post',
                    url: 'mdtemplate/checkCriteria',
                    data: {metaDataId: '<?php echo $this->dataViewId; ?>', dataRow: rows, isProcess: false},
                    dataType: "json",
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(response) {
                        
                        PNotify.removeAll();
                        
                        if (response.hasOwnProperty('status') && response.status != 'success') {
                            Core.unblockUI();
                            new PNotify({
                                title: response.status,
                                text: response.message,
                                type: response.status,
                                addclass: pnotifyPosition,
                                sticker: false
                            });
                            return;
                        }
                        
                        if (typeof response.isSettingsDialog !== 'undefined' && response.isSettingsDialog === '1') {
                            if (typeof response.templateMetaId !== 'undefined' && response.templateMetaId) {
                                var print_options = {
                                    numberOfCopies: response.numberOfCopies,
                                    isPrintNewPage: response.isPrintNewPage,
                                    isSettingsDialog: response.isSettingsDialog,
                                    isShowPreview: response.isShowPreview,
                                    isPrintPageBottom: response.isPrintPageBottom,
                                    isPrintPageRight: response.isPrintPageRight,
                                    pageOrientation: response.pageOrientation,
                                    isPrintSaveTemplate: response.isPrintSaveTemplate,
                                    paperInput: response.paperInput,
                                    pageSize: response.pageSize,
                                    printType: response.printType,
                                    templates: response.templates, 
                                    templateMetaId: typeof response.templateMetaId !== 'undefined' ? response.templateMetaId : '', 
                                    templateIds: response.templateIds 
                                }; 
                            } else {
                                var print_options = {
                                    numberOfCopies: response.numberOfCopies,
                                    isPrintNewPage: response.isPrintNewPage,
                                    isSettingsDialog: response.isSettingsDialog,
                                    isShowPreview: response.isShowPreview,
                                    isPrintPageBottom: response.isPrintPageBottom,
                                    isPrintPageRight: response.isPrintPageRight,
                                    pageOrientation: response.pageOrientation,
                                    isPrintSaveTemplate: response.isPrintSaveTemplate,
                                    paperInput: response.paperInput,
                                    pageSize: response.pageSize,
                                    printType: response.printType,
                                    templates: response.templates, 
                                    templateIds: response.templateIds 
                                }; 
                            }
                            if (response.numberOfCopies != '' && response.numberOfCopies != '0' && response.templates != null) {
                                callTemplate(rows, '<?php echo $this->dataViewId; ?>', print_options);
                            } else {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү',
                                    type: 'warning',
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                            } 
                            
                        } else {
                        
                            var $dialogName = 'dialog-printSettings';
                            if (!$($dialogName).length) {
                                $('<div id="' + $dialogName + '"></div>').appendTo('body');
                            }
                            var $dialog = $('#' + $dialogName);
                            
                            $dialog.empty().append(response.html);
                            $dialog.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('MET_99990001'),
                                width: 500, 
                                minWidth: 400,
                                height: "auto",
                                maxHeight: $(window).height() - 25, 
                                modal: false,
                                open: function(){
                                    Core.initDVAjax($dialog);
                                },
                                close: function(){
                                    PNotify.removeAll();
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: plang.get('preview_btn'), class: 'btn btn-sm blue', click: function() {
                                        PNotify.removeAll();
                                        var numberOfCopies = $("#numberOfCopies").val(),
                                            isPrintNewPage = $("#isPrintNewPage").is(':checked') ? '1' : '0',
                                            isSettingsDialog = $("#isSettingsDialog").is(':checked') ? '1' : '0',
                                            isShowPreview = $("#isShowPreview").is(':checked') ? '1' : '0',
                                            isPrintPageBottom = $("#isPrintPageBottom").is(':checked') ? '1' : '0',
                                            isPrintPageRight = $("#isPrintPageRight").is(':checked') ? '1' : '0',
                                            isPrintSaveTemplate = $("#isPrintSaveTemplate").is(':checked') ? '1' : '0',
                                            pageOrientation = $("#pageOrientation").val(),
                                            paperInput = $("#paperInput").val(),
                                            pageSize = $("#pageSize").val(),
                                            templates = $("#printTemplate").val(),
                                            templateIds = $("#rtTemplateIds").val(), 
                                            templateMetaIds = $("#templateMetaIds").val(),
                                            printType = $("#printType").val();
                                        var print_options = {
                                            numberOfCopies: numberOfCopies,
                                            isPrintNewPage: isPrintNewPage,
                                            isSettingsDialog: isSettingsDialog,
                                            isShowPreview: isShowPreview,
                                            isPrintPageBottom: isPrintPageBottom,
                                            isPrintPageRight: isPrintPageRight,
                                            pageOrientation: pageOrientation,
                                            isPrintSaveTemplate: isPrintSaveTemplate,
                                            paperInput: paperInput,
                                            pageSize: pageSize,
                                            printType: printType,
                                            templates: templates, 
                                            templateIds: templateIds, 
                                            templateMetaIds: templateMetaIds 
                                        }; 
                                        if (numberOfCopies != '' && numberOfCopies != '0' && templateIds) {
                                            if (print_options.templates == '') {
                                                new PNotify({
                                                    title: 'Warning',
                                                    text: 'Загвараа сонгоно уу!',
                                                    type: 'warning',
                                                    sticker: false
                                                });  
                                                return;              
                                            }
                                            $dialog.dialog('close');
                                            callTemplate(rows, '<?php echo $this->dataViewId;?>', print_options);
                                        } else {
                                            new PNotify({
                                                title: 'Warning',
                                                text: plang.getDefault('PRINT_0019', 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү'),
                                                type: 'warning',
                                                addclass: pnotifyPosition,
                                                sticker: false
                                            });
                                        }
                                    }},
                                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            if ($dialog.find("#rtTemplateIds").val().length === 0) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Загвараа сонгоно уу!',
                                    type: 'warning',
                                    sticker: false
                                });                      
                                $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:eq(0)').prop('disabled', true);
                            }
                            $dialog.on('change', '#printTemplate', function(){
                                if ($dialog.find("#printTemplate").val().length === 0) {
                                    $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:eq(0)').prop('disabled', true);
                                } else {
                                    $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:eq(0)').prop('disabled', false);
                                }
                            });
                            $dialog.dialog('open');
                        }
                        
                        Core.unblockUI();
                    }
                });
            }
        }, 100);
    }
    
    function restMergeTable(elem) {
        var $this = $('#sidebardv-<?php echo $this->uniqId; ?>').find('.restMergeTable');
        if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length) {
            var selectedRow = JSON.parse(objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').attr('data-row-data'));            
            
            if (typeof $this.attr('data-first-table') !== 'undefined') {                                
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                     
                
                var firstTable = JSON.parse($this.attr('data-first-table')), idsString = '', nameString = '', waiterId = '';
                for (var i = 0; i < firstTable.length; i++) {
                    idsString += firstTable[i]['salesorderid'] + '|';
                    nameString += firstTable[i]['locationname'] + ', ';
                }
                
                if (selectedRow['salespersonid']) {
                    waiterId = selectedRow['salespersonid'];
                    $.ajax({
                        type: 'post',
                        url: 'mdpos/mergeTableRest',
                        data: {
                            /*firstTable: firstTable,*/ 
                            secondTable: selectedRow,
                            idsString: idsString.slice(0, -1),
                            waiterId: waiterId
                        }, 
                        dataType: 'json',
                        success: function(data) {
                            PNotify.removeAll();                        
                            if (data.status === 'success') {
                                new PNotify({
                                    title: 'Амжилттай',
                                    text: nameString.slice(0, -2)+' => '+selectedRow['locationname']+' амжилттай шилжлээ.',
                                    type: 'success', 
                                    sticker: false, 
                                    addclass: 'pnotify-center'
                                });   
                                getLocationHtml();
                                restClears();
                            } else {
                                new PNotify({
                                    title: 'Error',
                                    text: data.message,
                                    type: 'error', 
                                    sticker: false, 
                                    addclass: 'pnotify-center'
                                });                            
                            }
                            $this.removeAttr('data-first-table');
                            Core.unblockUI();
                        }
                    });                      
                } else {
                    var waiter = $.ajax({
                        type: 'post',
                        url: 'api/callDataview',
                        data: {dataviewId: '16138106168131'}, 
                        dataType: 'json',
                        async: false,
                        success: function(data) {                            
                            return data.result;
                        }
                    });               

                    var $dialogName = 'dialog-waiter-form';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }

                    var $dialogPWaiter = $('#' + $dialogName);
                    var selectHtml = '<div style="overflow:auto">';
                    for(var i = 0; i < waiter.responseJSON.result.length; i++) {
                        selectHtml += '<div data-id="'+waiter.responseJSON.result[i]['id']+'" data-code="'+waiter.responseJSON.result[i]['salespersoncode']+'" data-name="'+waiter.responseJSON.result[i]['salespersonname']+'" class="mb10 d-flex justify-content-start rest-choose-waiter" style="background: #FFFFFF;box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.08);border-radius: 10px;cursor: pointer">';
                        selectHtml += '<div style="padding:10px"><img class="rounded-circle" src="middleware/assets/img/pos/noprofile.png" width="36" height="36" alt=""></div>';
                        selectHtml += '<div style="padding:10px;font-size:14px"><div>'+waiter.responseJSON.result[i]['salespersonname']+'</div><div style="color:#A0A0A0;font-size:12px;" class="mt3">'+waiter.responseJSON.result[i]['salespersoncode']+'</div></div>';
                        selectHtml += '</div>';
                    }   
                    selectHtml += '</div>';

                    $dialogPWaiter.empty().append('<form method="post" autocomplete="off" id="waiterForm">'+selectHtml+'</form>');
                    $dialogPWaiter.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Зөөгч сонгох', 
                        width: 280,
                        height: 'auto',
                        maxHeight: 750,                        
                        modal: true,
                        open: function () {
                            Core.unblockUI();
                            $dialogPWaiter.css('background-color', '#F5F5F5');
                            $dialogPWaiter.on('click', '.rest-choose-waiter', function(e){
                                Core.blockUI({
                                    message: 'Loading...',
                                    boxed: true
                                });                                  
                                waiterId = $(this).data('id');
                                $dialogPWaiter.empty().dialog('destroy').remove();
                                
                                $.ajax({
                                    type: 'post',
                                    url: 'mdpos/mergeTableRest',
                                    data: {
                                        /*firstTable: firstTable,*/ 
                                        secondTable: selectedRow,
                                        idsString: idsString.slice(0, -1),
                                        waiterId: waiterId
                                    }, 
                                    dataType: 'json',
                                    success: function(data) {
                                        PNotify.removeAll();                        
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Амжилттай',
                                                text: nameString.slice(0, -2)+' => '+selectedRow['locationname']+' амжилттай шилжлээ.',
                                                type: 'success', 
                                                sticker: false, 
                                                addclass: 'pnotify-center'
                                            });   
                                            getLocationHtml();
                                            restClears();
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error', 
                                                sticker: false, 
                                                addclass: 'pnotify-center'
                                            });                             
                                        }
                                        $this.removeAttr('data-first-table');
                                        Core.unblockUI();
                                    }
                                });                                  
                            });
                        },
                        close: function () {
                            $dialogPWaiter.empty().dialog('destroy').remove();
                        },
                        buttons: []
                    });
                    Core.initSelect2($dialogPWaiter);
                    $dialogPWaiter.dialog('open');   
                }
                             
            } else {
                
                var selectedRow = [];

                objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').each(function(){
                    //if ($(this).find('.multipleCheckLocation').is(':checked')) {
                        var dataObj = JSON.parse($(this).attr('data-row-data'));
                        if (dataObj['salesorderid']) {
                            selectedRow.push(dataObj);
                        }
                    //}
                })

                if (!selectedRow.length) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Info',
                        text: 'Хоосон ширээ нийлүүлэх боломжгүй!',
                        type: 'info', 
                        sticker: false, 
                        addclass: 'pnotify-center'
                    });                  
                    return;
                }
            
                var $dialogName = 'dialog-talon-protect';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialogP = $('#' + $dialogName);

                $dialogP.empty().append('<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>');
                $dialogP.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Нууц үг оруулах', 
                    width: 400,
                    height: 'auto',
                    modal: true,
                    open: function () {
                        $(this).keypress(function (e) {
                            if (e.keyCode == $.ui.keyCode.ENTER) {
                                $(this).parent().find(".ui-dialog-buttonpane button:first").trigger('click');
                            }
                        });
                        $('input[name="talonListPass"]').on('keydown', function(e){
                            var keyCode = (e.keyCode ? e.keyCode : e.which);
                            if (keyCode == 13) {
                                $(this).closest('.ui-dialog').find(".ui-dialog-buttonpane button:first").trigger('click');
                            }
                        });
                    },
                    close: function () {
                        $dialogP.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('insert_btn'), class: 'btn btn-sm green-meadow', click: function () {

                            PNotify.removeAll();
                            var $form = $('#talonListPassForm');

                            $form.validate({errorPlacement: function () {}});

                            if ($form.valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: "api/callDataview",
                                    data: {
                                        dataviewId: "16237213033721",
                                        criteriaData: {
                                        pincode: [
                                            {
                                            operator: "=",
                                            operand: $form.find('input[name="talonListPass"]').val(),
                                            },
                                        ],
                                        },
                                    },                                    
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            message: 'Loading...',
                                            boxed: true
                                        });
                                    },
                                    success: function(dataSub) {
                                        if (dataSub.status == "success" && Object.keys(dataSub.result).length) {                                      
                                            $dialogP.dialog('close');       
                                            /*objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').removeClass('selected-row');*/
                                            $this.attr('data-first-table', JSON.stringify(selectedRow));
                                            new PNotify({
                                                title: 'Info',
                                                text: 'Нийлүүлэх ширээгээ сонгоод DOUBLE CLICK дарна уу.', 
                                                type: 'info', 
                                                sticker: false,
                                                addclass: 'pnotify-center'
                                            });                            
                                            restPosEventType['event'] = 'mergeTable';
                                        } else {
                                            new PNotify({
                                                title: 'warning',
                                                text: 'ЗБ нууц үг оруулна уу!', 
                                                type: 'warning', 
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }}, 
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                            $dialogP.dialog('close');
                        }}
                    ]
                });
                $dialogP.dialog('open');
            }
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Ширээгээ сонгоно уу!',
                type: 'info', 
                sticker: false, 
                addclass: 'pnotify-center'
            });              
        }
    }
    
    function additionalRestStyle(location) {
        var style;
        var code = location['locationcode'] ? location['locationcode'].toUpperCase() : '';
        
        switch (code) {
            case '50':
                style = 'border-top-left-radius: 35px;border-top-right-radius: 35px;';
                break;                
            case '49':
            case 'TBL01':
                style = 'border-top-left-radius: 38px;border-top-right-radius: 38px;';
                break;
            case 'TBL07':
                style = 'border-bottom-right-radius: 10px;';
                break;
            case '27':
            case '26':
            case '25':
            case '24':
                style = 'border-bottom-right-radius: 10px;';
                break;
            case '54':
            case '53':
            case '52':
                style = 'border-top-right-radius: 13px;';
                break;
            case '23':
            case '22':
            case '21':
            case '20':
            case '19':
            case '63':
            case '64':
            case '65':
            case '66':
            case '67':
            case '68':
            case '69':
            case '70':
            case '71':
            case '75':
                style = 'border-top-right-radius: 8px;';
                break;
            case '42':
            case '43':
            case '44':
            case '45':
            case '46':
            case '47':
                style = 'border-bottom-left-radius: 8px;';
                break;
            case '51':
                style = 'border-top-right-radius: 22px;';
                break;
            case '55':
                style = 'border-top-right-radius: 8px;';
                break;
            case '56':
            case '57':
                style = 'border-top-left-radius: 12px;';
                break;
            case '41':
            case '40':
                style = 'border-top-right-radius: 10px;';
                break;
            case '01':
            case '02':
            case '58':
            case '79':
            case '03':
                style = 'border-top-right-radius: 12px;';
                break;
            case '18':
            case '17':
            case '16':
            case '15':
            case '14':
            case '13':
            case '12':
            case '39':
            case '38':
            case '37':
            case '36':
            case '35':
            case '34':
                style = 'border-top-right-radius: 9px;';
                break;
            case '48':
                style = 'border-top-left-radius: 25px;';
                break;
            case '09':
            case '08':
            case '07':
            case '05':
            case '04':
            case '10':
            case '11':
                style = 'border-top-left-radius: 9px;';
                break;
            case '06':
                style = 'border-top-left-radius: 14px;';
                break;
            default:
                style = '';
                break;
        }       
        
        return style;
    }
    function restSplitTable(elem) {
        //var $this = $('#sidebardv-<?php echo $this->uniqId; ?>').find('.restChangeTable');
        if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length) {
            var selectedRow = JSON.parse(objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').attr('data-row-data'));

            if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length > 1) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Info',
                    text: 'Нэг ширээ сонгоно уу!',
                    type: 'info', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });             
                return;
            }            
            
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });                     
             
            posFillItemsByBasket('','','','',[{id: selectedRow['salesorderid']}], undefined, undefined, undefined, function(data){
                Core.unblockUI();
                
                if (data) {
                    var $dialogName = 'dialog-talon-splitcalc';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialogP = $('#' + $dialogName);
                    var itemList = data.orderData.data.pos_item_list_get, footerSum = 0;
                    var itemHtml = '<table class="fancyTable fht-table fht-table-init" cellpadding="0" cellspacing="0">'+
                        '<thead style=""><tr><th style="width: 70px;" class="d-none"><div class="fht-cell" style="width: 0px;"></div></th><th style="width: 140px; text-align: left;" class="d-none">Код<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 120px; text-align: left;" data-config-column="serialnumber" class="hide">Сериал<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 200px; text-align: left"><span style="">Барааны нэр</span><div class="fht-cell" style="width: 199px;"></div></th><th style="width: 100px; text-align: right;" class="d-none">Үнэ<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 100px; text-align: right;" data-config-column="unitreceivable" class="d-none hide">Даатгал<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 50px; text-align: center"><span class="infoShortcut" style="position: absolute;margin-left: 27px;font-size: 9px;"></span> Үнэ<div class="fht-cell" style="width: 76px;"></div></th><th style="width: 50px; text-align: center"><span class="infoShortcut" style="position: absolute;margin-left: 27px;font-size: 9px;"></span> Тоо<div class="fht-cell" style="width: 50px;"></div></th><th style="width: 94px; text-align: center">Салгах тоо<div class="fht-cell" style="width: 99px;"></div></th><th style="width: 80px; text-align: right">Нийт дүн<div class="fht-cell" style="width: 81px;"></div></th><th style="width: 20px; text-align: center;" data-config-column="delivery" class="hide"><i class="fa fa-truck" title="Хүргэлттэй эсэх"></i><div class="fht-cell" style="width: 24px;"></div></th><th style="width: 280px; text-align: center;" data-config-column="salesperson" class="hide">Худалдааны зөвлөх<div class="fht-cell" style="width: 131px;"></div></th></tr></thead><tbody>';
                    
                    for (var i = 0; i < itemList.length; i++) {
                        footerSum += Number(itemList[i]['linetotalprice']);
                        itemHtml += '<tr class=""><td data-field-name="gift" class="text-center d-none" style=""></td><td data-field-name="itemCode" class="text-left d-none" style="font-size: 14px;">8656170014839</td><td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left hide" style=""></td><td data-field-name="itemName" class="text-left pt10 pb10" title="" style="font-size: 14px; line-height: 15px;font-weight: normal;"><div class="item-code-mini" style="color:#A0A0A0;font-size: 12px;">'+itemList[i]['itemcode']+'</div><div class="mt3">'+itemList[i]['itemname']+'</div><div class="item-code-mini mt3" style="color:#A0A0A0;font-size: 12px;">'+(itemList[i]['customercode']?itemList[i]['customercode']:'Харилцагч сонгоогүй')+' - '+(itemList[i]['customername']?itemList[i]['customername']:'')+'</div><input type="hidden" data-name="salePrice" value="'+itemList[i]['saleprice']+'"></td><td data-field-name="salePrice" class="text-right bigdecimalInit d-none" style=""></td><td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit d-none hide" style=""></td><td style="height:28.8px;" class="pos-quantity-cell text-right">'+pureNumberFormat(itemList[i]['saleprice'])+'</td><td data-field-name="quantity" style="height:28.8px;" class="pos-quantity-cell text-right">'+itemList[i]['invoiceqty']+'</td><td class="text-right bigdecimalInit"><input type="'+(itemList[i]['isservicecharge'] == '1' ? 'hidden' : 'text')+'" name="splitInput[]" class="integerInit" style="border: 1px #ccc solid;width: 45px;height: 32px;text-align: right;padding: 3px;" data-mdec="3"></td><td data-field-name="totalPrice" class="text-right bigdecimalInit">'+pureNumberFormat(itemList[i]['linetotalprice'])+'</td><td data-field-name="delivery" class="text-center hide" data-config-column="delivery" style=""></td><td data-field-name="salesperson" class="text-center hide" data-config-column="salesperson" style=""></td></tr>';
                    }
                    itemHtml += '</tbody><tfoot><th colspan="5" style="text-align: right;border-bottom: none;" class="footer-sumamt-splitcalc">'+pureNumberFormat(footerSum)+'</th></tfoot></table>';

                    $dialogP.empty().append('<form method="post" autocomplete="off" id="talonSplitCalcForm">'+itemHtml+'<input type="hidden" name="isLastCalcSplit" value=""></form>');
                    $dialogP.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Тооцоо салгах', 
                        width: 620,
                        height: 'auto',
                        modal: true,
                        open: function () {                   
                            $(document.body).on('keydown', 'input[name="splitInput[]"]', function(e){
                                var keyCode = (e.keyCode ? e.keyCode : e.which);
                                if (keyCode == 13) {
                                    var $tr = $(this).closest('tr');
                                    $tr.next('tr').find('input[name="splitInput[]"]').focus().select().click();
                                    setTimeout(function(){
                                        $tr.next('tr').find('input[name="splitInput[]"]').focus().select().click();
                                    }, 100);
                                }
                            });                            
                            $(document.body).on('change', 'input[name="splitInput[]"]', function(){
                                var $tr = $(this).closest('tr');
                                if (pureNumber($tr.find('td[data-field-name="quantity"]').text()) < $(this).val()) {
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Хадгалсан тоо ширхэгээс их байна!',
                                        type: 'warning', 
                                        sticker: false,
                                        addclass: 'pnotify-center'
                                    });
                                    $(this).val('').trigger('change');
                                    return;
                                }
                                
                                if ($(this).val()) {
                                    $tr.find('td[data-field-name="totalPrice"]').text(pureNumberFormat(pureNumber($(this).val()) * Number($tr.find('input[data-name="salePrice"]').val())));                                
                                    var fSum = 0, isLastSplit = 0;
                                    $dialogP.find('table > tbody > tr').each(function(){
                                        if ($(this).find('input[name="splitInput[]"]').val()) {
                                            fSum += pureNumber($(this).find('td[data-field-name="totalPrice"]').text());
                                        }
                                        if ($(this).find('input[name="splitInput[]"]').val() == pureNumber($(this).find('td[data-field-name="quantity"]').text())) {
                                            isLastSplit++;
                                        }
                                    });
                                    if ($dialogP.find('table > tbody > tr').length == isLastSplit) {
                                        isLastSplit = 1;
                                    } else {
                                        isLastSplit = 0;
                                    }
                                    $dialogP.find('input[name="isLastCalcSplit"]').val(isLastSplit);
                                    $dialogP.find('.footer-sumamt-splitcalc').text(pureNumberFormat(fSum));
                                } else {
                                    $tr.find('td[data-field-name="totalPrice"]').text(pureNumberFormat(pureNumber($tr.find('td[data-field-name="quantity"]').text()) * Number($tr.find('input[data-name="salePrice"]').val())));
                                    $dialogP.find('.footer-sumamt-splitcalc').text(pureNumberFormat($dialogP.find('td[data-field-name="totalPrice"]').sum()));
                                }
                            });
                        },
                        close: function () {
                            $dialogP.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: 'Салгаж бодох', class: 'btn btn-sm green-meadow', click: function () {

                                PNotify.removeAll();
                                var checkcount = 0;
                                $dialogP.find('table > tbody > tr').each(function(){
                                    if ($(this).find('input[name="splitInput[]"]').val() == '') {
                                        checkcount++;
                                    }
                                });
                                if ($dialogP.find('table > tbody > tr').length == checkcount) {
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Салгаж бодох тоогоо оруулна уу!', 
                                        type: 'warning', 
                                        sticker: false,
                                        addclass: 'pnotify-center'
                                    });        
                                    return;
                                }
                                
                                var $form = $('#talonSplitCalcForm');
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdpos/splitCalculateRest', 
                                        data: {
                                            data: data,
                                            serialize: $form.serialize()
                                        },
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Loading...',
                                                boxed: true
                                            });
                                        },
                                        success: function(dataSub) {
                                            if (dataSub.status == 'success') {
                                                $dialogP.dialog('close');             
                                                restPosEventType['event'] = 'splitCalculate';
                                                restPosEventType['data'] = dataSub.data;
                                                objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').trigger('dblclick');
                                            } else {
                                                new PNotify({
                                                    title: dataSub.status,
                                                    text: dataSub.message, 
                                                    type: dataSub.status, 
                                                    sticker: false
                                                });
                                            }
                                            Core.unblockUI();
                                        }
                                    });
                            }}, 
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                                $dialogP.dialog('close');
                            }}
                        ]
                    });
                    $dialogP.dialog('open');     
                    Core.initLongInput($dialogP);
                }
            });
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Ширээгээ сонгоно уу!',
                type: 'info', 
                sticker: false, 
                addclass: 'pnotify-center'
            });              
        }
    }    
    function restPieceMergeTable(elem) {
        if (objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').length) {
            var selectedRow = JSON.parse(objectdatagrid_<?php echo $this->dataViewId ?>.find('.selected-row').attr('data-row-data'));
            
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });                     
             
            posFillItemsByBasket('','','','',[{id: selectedRow['salesorderid']}], undefined, undefined, undefined, function(data){
                Core.unblockUI();
                
                if (data) {
                    var $dialogName = 'dialog-talon-piececalc';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialogP = $('#' + $dialogName);
                    var itemList = data.orderData.data.pos_item_list_get, footerSum = 0;
                    var itemHtml = '<table class="fancyTable fht-table fht-table-init" cellpadding="0" cellspacing="0">'+
                        '<thead style=""><tr><th style="width: 70px;" class="d-none"><div class="fht-cell" style="width: 0px;"></div></th><th style="width: 140px; text-align: left;" class="d-none">Код<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 120px; text-align: left;" data-config-column="serialnumber" class="hide">Сериал<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 200px; text-align: left"><span style="">Барааны нэр</span><div class="fht-cell" style="width: 199px;"></div></th><th style="width: 100px; text-align: right;" class="d-none">Үнэ<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 100px; text-align: right;" data-config-column="unitreceivable" class="d-none hide">Даатгал<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 50px; text-align: center"><span class="infoShortcut" style="position: absolute;margin-left: 27px;font-size: 9px;"></span> Үнэ<div class="fht-cell" style="width: 76px;"></div></th><th style="width: 50px; text-align: center"><span class="infoShortcut" style="position: absolute;margin-left: 27px;font-size: 9px;"></span> Тоо<div class="fht-cell" style="width: 50px;"></div></th><th style="width: 94px; text-align: center">Нийлүүлэх тоо<div class="fht-cell" style="width: 99px;"></div></th><th style="width: 80px; text-align: right">Нийт дүн<div class="fht-cell" style="width: 81px;"></div></th><th style="width: 20px; text-align: center;" data-config-column="delivery" class="hide"><i class="fa fa-truck" title="Хүргэлттэй эсэх"></i><div class="fht-cell" style="width: 24px;"></div></th><th style="width: 280px; text-align: center;" data-config-column="salesperson" class="hide">Худалдааны зөвлөх<div class="fht-cell" style="width: 131px;"></div></th></tr></thead><tbody>';
                    
                    for (var i = 0; i < itemList.length; i++) {
                        footerSum += Number(itemList[i]['linetotalprice']);
                        itemHtml += '<tr class=""><td data-field-name="gift" class="text-center d-none" style=""></td><td data-field-name="itemCode" class="text-left d-none" style="font-size: 14px;">8656170014839</td><td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left hide" style=""></td><td data-field-name="itemName" class="text-left pt10 pb10" title="" style="font-size: 14px; line-height: 15px;font-weight: normal;"><div class="item-code-mini" style="color:#A0A0A0;font-size: 12px;">'+itemList[i]['itemcode']+'</div><div class="mt3">'+itemList[i]['itemname']+'</div><input type="hidden" data-name="salePrice" value="'+itemList[i]['saleprice']+'"></td><td data-field-name="salePrice" class="text-right bigdecimalInit d-none" style=""></td><td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit d-none hide" style=""></td><td style="height:28.8px;" class="pos-quantity-cell text-right">'+pureNumberFormat(itemList[i]['saleprice'])+'</td><td data-field-name="quantity" style="height:28.8px;" class="pos-quantity-cell text-right">'+itemList[i]['invoiceqty']+'</td><td class="text-right bigdecimalInit"><input type="text" name="splitInput[]" class="integerInit" style="border: 1px #ccc solid;width: 45px;height: 32px;text-align: right;padding: 3px;" data-mdec="3"></td><td data-field-name="totalPrice" class="text-right bigdecimalInit">'+pureNumberFormat(itemList[i]['linetotalprice'])+'</td><td data-field-name="delivery" class="text-center hide" data-config-column="delivery" style=""></td><td data-field-name="salesperson" class="text-center hide" data-config-column="salesperson" style=""></td></tr>';
                    }
                    itemHtml += '</tbody><tfoot><th colspan="5" style="text-align: right;border-bottom: none;" class="footer-sumamt-splitcalc">'+pureNumberFormat(footerSum)+'</th></tfoot></table>';

                    $dialogP.empty().append('<form method="post" autocomplete="off" id="talonPieceCalcForm">'+itemHtml+'</form>');
                    $dialogP.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Хэсэгчилж нийлүүлэх', 
                        width: 620,
                        height: 'auto',
                        modal: true,
                        open: function () {                   
                            $(document.body).on('keydown', 'input[name="splitInput[]"]', function(e){
                                var keyCode = (e.keyCode ? e.keyCode : e.which);
                                if (keyCode == 13) {
                                    var $tr = $(this).closest('tr');
                                    $tr.next('tr').find('input[name="splitInput[]"]').focus().select().click();
                                    setTimeout(function(){
                                        $tr.next('tr').find('input[name="splitInput[]"]').focus().select().click();
                                    }, 100);
                                }
                            });                            
                            $(document.body).on('change', 'input[name="splitInput[]"]', function(){
                                var $tr = $(this).closest('tr');
                                if (pureNumber($tr.find('td[data-field-name="quantity"]').text()) < $(this).val()) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Хадгалсан тоо ширхэгээс их байна!',
                                        type: 'warning', 
                                        sticker: false,
                                        addclass: 'pnotify-center'
                                    });
                                    $(this).val('').trigger('change');
                                    return;
                                }
                                
                                if ($(this).val()) {
                                    $tr.find('td[data-field-name="totalPrice"]').text(pureNumberFormat(pureNumber($(this).val()) * Number($tr.find('input[data-name="salePrice"]').val())));                                
                                    var fSum = 0, isLastSplit = 0;
                                    $dialogP.find('table > tbody > tr').each(function(){
                                        if ($(this).find('input[name="splitInput[]"]').val()) {
                                            fSum += pureNumber($(this).find('td[data-field-name="totalPrice"]').text());
                                        }
                                        if ($(this).find('input[name="splitInput[]"]').val() == pureNumber($(this).find('td[data-field-name="quantity"]').text())) {
                                            isLastSplit++;
                                        }
                                    });
                                    if ($dialogP.find('table > tbody > tr').length == isLastSplit) {
                                        isLastSplit = 1;
                                    } else {
                                        isLastSplit = 0;
                                    }
                                    $dialogP.find('.footer-sumamt-splitcalc').text(pureNumberFormat(fSum));
                                } else {
                                    $tr.find('td[data-field-name="totalPrice"]').text(pureNumberFormat(pureNumber($tr.find('td[data-field-name="quantity"]').text()) * Number($tr.find('input[data-name="salePrice"]').val())));
                                    $dialogP.find('.footer-sumamt-splitcalc').text(pureNumberFormat($dialogP.find('td[data-field-name="totalPrice"]').sum()));
                                }
                            });
                        },
                        close: function () {
                            $dialogP.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: 'Хэсэгчилж нийлүүлэх', class: 'btn btn-sm green-meadow', click: function () {

                                PNotify.removeAll();
                                var checkcount = 0;
                                $dialogP.find('table > tbody > tr').each(function(){
                                    if ($(this).find('input[name="splitInput[]"]').val() == '') {
                                        checkcount++;
                                    }
                                });
                                if ($dialogP.find('table > tbody > tr').length == checkcount) {
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Нийлүүлэх тоогоо оруулна уу!', 
                                        type: 'warning', 
                                        sticker: false,
                                        addclass: 'pnotify-center'
                                    });        
                                    return;
                                }
                                
                                
                                var $form = $('#talonPieceCalcForm');
                                $.ajax({
                                    type: 'post',
                                    url: 'mdpos/pieceCalculateRest', 
                                    data: {
                                        data: data,
                                        salesorderid: selectedRow['salesorderid'],
                                        serialize: $form.serialize()
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            message: 'Loading...',
                                            boxed: true
                                        });
                                    },
                                    success: function(dataSub) {
                                        if (dataSub) {
                                            restPosEventType['event'] = 'pieceCalculate';
                                            restPosEventType['data'] = dataSub;                                            
                                            $dialogP.dialog('close');
                                            new PNotify({
                                                title: 'Info',
                                                text: 'Нийлүүлэх ширээгээ сонгоод DOUBLE CLICK дарна уу.', 
                                                type: 'info', 
                                                sticker: false,
                                                addclass: 'pnotify-center'
                                            });                                                          
                                        } else {
                                            new PNotify({
                                                title: dataSub.status,
                                                text: dataSub.message, 
                                                type: dataSub.status, 
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }}, 
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                                $dialogP.dialog('close');
                            }}
                        ]
                    });
                    $dialogP.dialog('open');     
                    Core.initLongInput($dialogP);
                }
            });
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Ширээгээ сонгоно уу!',
                type: 'info', 
                sticker: false, 
                addclass: 'pnotify-center'
            });              
        }
    }    
    function restSavePieceMergeTable(data, selectedRow) {
        $.ajax({
            type: 'post',
            url: 'mdpos/pieceCalculateSaveRest', 
            data: {
                data: data,
                selectedRow: selectedRow
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(dataSub) {
                if (dataSub.status == 'success') {        
                    new PNotify({
                        title: 'Success',
                        text: 'Амжилттай', 
                        type: 'success', 
                        sticker: false,
                        addclass: 'pnotify-center'
                    });          
                    getLocationHtml();
                    restClears();                    
                } else {
                    new PNotify({
                        title: dataSub.status,
                        text: dataSub.message, 
                        type: dataSub.status, 
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });    
    }
</script>