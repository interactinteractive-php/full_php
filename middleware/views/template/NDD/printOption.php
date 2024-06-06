<div class="form-xs" id="nddWSPrintSettings">
    <table class="table table-sm table-no-bordered">
        <tr class="hide">
            <td class="text-right middle" style="width: 40%"><label for="isConfig">Тохиргооноос:</label></td>
            <td class="middle" style="width: 60%">
                <input type="radio" class="form-control-sm notuniform" name="isConfig" id="isConfig" value="1" checked />
            </td>
        </tr>
        <tr class="hide">
            <td class="text-right middle" style="width: 40%"><label for="isCustom">Шинээр:</label></td>
            <td class="middle" style="width: 60%">
                <input type="radio" class="form-control-sm notuniform" name="isCustom" id="isCustom" value="1" />
            </td>
        </tr>
        <tr>
            <td class="middle" colspan="2">
                <div class="col-md-6">
                    <span class="col-md-12"> 
                        Жил:
                    </span>
                    <div class="col-md-12">
                        <?php 
                            echo Form::select(
                                array(
                                    'name' => 'nddYear',
                                    'id' => 'nddYear',
                                    'class' => 'form-control form-control-sm',
                                    'data'=> $this->getNDDprintYear,
                                    'op_value' => 'id',
                                    'op_text' => 'code',
                                    'value' => Date::currentDate('Y')
                                )
                            );
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <span class="col-md-12"> 
                        Хуудасны тоо:
                    </span>
                    <div class="col-md-12">
                        <?php 
                            echo Form::text(
                                array(
                                    'name' => 'nddPageNum',
                                    'id' => 'nddPageNum',
                                    'class' => 'form-control form-control-sm',
                                    'maxlength' => 2,
                                    'value' => issetVar($this->getEmployeePrintConfig['PAGE_NUMBER'])
                                )
                            );
                        ?>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="middle" colspan="2">
                <div class="col-md-6">
                    <span class="col-md-12"> 
                        Эхлэх сар:
                    </span>
                    <div class="col-md-12">
                        <?php
                        $months = array();
                        $savedVal = isset($this->getEmployeePrintConfig['ROW_NUMBER']) ? (int) $this->getEmployeePrintConfig['ROW_NUMBER'] : '';
                        /*$monthIndex = $savedVal === '' ? 1 : $savedVal;*/
                        $monthIndex = 1;
                        for ($mon = $monthIndex; $mon <= 12; $mon++)
                            array_push($months, array('MONTH' => $mon));

                        echo Form::select(
                            array(
                                'name' => 'nddMonthPrev',
                                'id' => 'nddMonthPrev',
                                'class' => 'form-control form-control-sm',
                                'data'=> $months,
                                'op_value' => 'MONTH',
                                'op_text' => 'MONTH',
                                'value' => $savedVal
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <span class="col-md-12"> 
                        Дуусах сар:
                    </span>
                    <div class="col-md-12">
                        <?php
                        echo Form::select(
                            array(
                                'name' => 'nddMonthNext',
                                'id' => 'nddMonthNext',
                                'class' => 'form-control form-control-sm'
                            )
                        );
                        ?>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div class="float-right">
        <button type="button" class="btn btn-circle blue btn-sm" id="nddWSpringBtn">Хэвлэх</button>
        <button type="button" class="btn btn-circle blue-madison btn-sm bp-close-btn">Хаах</button>        
    </div>
</div>  

<style type="text/css">
    .ui-dialog-buttonpane {
        display: none;
    }
</style>

<script type="text/javascript">
    var empKeyId = '<?php echo $this->employeeKeyId; ?>';
    $(function() {
        $(".bp-close-btn", "#nddWSPrintSettings").on("click", function(){
            var dialogId = $(this).closest(".ui-dialog-content");
            dialogId.empty().dialog('destroy').remove();
        });
        $("#nddWSpringBtn", "#nddWSPrintSettings").on("click", function(){
            var dialogId = $(this).closest(".ui-dialog-content");
//            var isConfig = $("#isConfig:checked", "#nddWSPrintSettings").val();
            var isConfig = false;
            var printOptions = {};
            if(isConfig === false) {
                printOptions = {
                    isType: 'custom',
                    nddYear: $("#nddYear", "#nddWSPrintSettings").val(),
                    nddYearCode: $("#nddYear option:selected", "#nddWSPrintSettings").text(),
                    nddPageNum: $("#nddPageNum", "#nddWSPrintSettings").val(),
                    nddMonthPrev: $("#nddMonthPrev", "#nddWSPrintSettings").val(),
                    nddMonthNext: $("#nddMonthNext", "#nddWSPrintSettings").val(), 
                    bookTypeId: '<?php echo $this->bookTypeId; ?>'
                };          
                if (printOptions.nddYear === '') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Жилээ сонгоно уу!',
                        type: 'warning',
                        sticker: false
                    });               
                    return;
                } else if(printOptions.nddPageNum === ''){
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Хуудасны дугаараа оруулна уу!',
                        type: 'warning',
                        sticker: false
                    });                      
                    return;
                } else if(printOptions.nddMonthPrev === ''){
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Эхлэх сараа сонгоно уу!',
                        type: 'warning',
                        sticker: false
                    });    
                    return;
                } else if(printOptions.nddMonthNext === ''){
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Дуусах сараа сонгоно уу!',
                        type: 'warning',
                        sticker: false
                    });    
                    return;
                }
                
                alertNddTemplate(empKeyId, printOptions, dialogId);
                return;
            }
            
            dialogId.empty().dialog('destroy').remove();
            nddTemplate(empKeyId, printOptions);
        });
        $("#isCustom", "#nddWSPrintSettings").on("click", function(){
            $("#isConfig", "#nddWSPrintSettings").prop("checked", false);
            $("table tr", "#nddWSPrintSettings").filter(":eq(2), :eq(3)").removeClass("hide");
        });
        $("#isConfig", "#nddWSPrintSettings").on("click", function(){
            $("#isCustom", "#nddWSPrintSettings").prop("checked", false);
            $("table tr", "#nddWSPrintSettings").filter(":eq(2), :eq(3)").addClass("hide");
        });
        $("#nddMonthPrev", "#nddWSPrintSettings").on("change", function(){
            var _thisVal = $(this).val();
            
            if (_thisVal === '') {
                $("#nddMonthNext", "#nddWSPrintSettings").prop('disabled', true);
            } else {
                $("#nddMonthNext", "#nddWSPrintSettings").prop('disabled', false).empty();
                for (var i = _thisVal; i <= 12; i++) {
                    $("#nddMonthNext", "#nddWSPrintSettings").append("<option value='"+i+"'>"+i+"</option>");
                }
            }
        });
        $("#nddMonthPrev", "#nddWSPrintSettings").trigger('change');
    });
    
    function nddTemplate(empKeyId, print_options) {
        var $dialogName = 'dialog-ndd-template';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/getNDDprintTemplateCtrl',
            data: { empKeyId: empKeyId, print_options: print_options },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'НДД-ийн загварын тохиргоо хайж байна...',
                    boxed: true
                });
            },
            success: function(data) {
                if (typeof data.status !== "undefined") {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });    
                    Core.unblockUI();
                    return;                    
                }
                $("#" + $dialogName).empty().html(data.html);
                $("#" + $dialogName).dialog({
                    appendTo: "body",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 940,
                    minWidth: 600,
                    height: 'auto',
                    modal: true,
                    close: function(){
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    },                        
                    buttons: [
                        {text: data.print_btn, class: 'btn btn-sm blue', click: function() {                            
                            printNDDTemplate(nddPrintPosition);
                            $("#" + $dialogName).dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": true,
                    "dblclick": "maximize", "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }        
    function alertNddTemplate(empKeyId, print_options, dialogId) {
        var $dialogName = 'dialog-ndd-alert';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/alertNDDTemplateCtrl',
            data: { empKeyId: empKeyId, print_options: print_options },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    boxed: true
                });
            },
            success: function(data) {
                if(data.status === 'found') {
                    $("#" + $dialogName).empty().html(data.html);
                    $("#" + $dialogName).dialog({
                        appendTo: "body",
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 480,
                        minHeight: 75,
                        height: 'auto',
                        modal: true,
                        close: function(){
                            $("#" + $dialogName).empty().dialog('destroy').remove();
                        },                        
                        buttons: [
                            {text: data.yes_btn, class: 'btn btn-sm blue', click: function() {
                                $("#" + $dialogName).empty().dialog('destroy').remove();
                                dialogId.empty().dialog('destroy').remove();
                                nddTemplate(empKeyId, print_options);
                            }},
                            {text: data.no_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $("#" + $dialogName).empty().dialog('destroy').remove();
                            }}
                        ]
                    });
                    $("#" + $dialogName).dialog('open');
                    $("#" + $dialogName).parent().find(".ui-dialog-buttonpane").show();
                } else {
                    dialogId.empty().dialog('destroy').remove();
                    nddTemplate(empKeyId, print_options);                    
                }
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }        
    function printNDDTemplate(rows) {
        /*$.each(rows, function(key, value) {
            $("body").append("<div class='nddContents'>"+
                    "<div style='margin-top: "+value.top+"mm; position: absolute;'>"+
                        "<div class='nddCol' style='margin-left: "+value.colOneLeft+"mm'>"+value.col1Data+"</div><div class='nddCol' style='margin-left: "+value.colTwoLeft+"mm'>"+value.col2Data+"</div><div class='nddCol' style='margin-left: "+value.colThreeLeft+"mm'>"+value.col3Data+"</div>"+
                    "</div>"+
                "</div>"
            );
        });*/
        var topSize = Number($("#nddContentsPrintPrev").css('top').replace('px', ''));
        var leftSize = Number($("#nddContentsPrintPrev").css('left').replace('px', ''));
        var defaultMarginSize = 8;
        
        $("body").append("<div class='nddContents'><div style='position: absolute;padding: 0; top: "+(topSize + defaultMarginSize)+"px; left: "+(leftSize + defaultMarginSize)+"px;'>"+$("#nddContentsPrintPrev").html()+"</div></div>");
    
        $(".nddContents").promise().done(function() {
            
            $(".nddContents").printThis({
                debug: false,             
                importCSS: false,           
                printContainer: false,      
                dataCSS: "@page{size: A4 portrait;margin-top: 0;margin-right: 0;margin-bottom: 0;margin-left: 0;margin: 0;padding: 0;}*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}*:before,*:after{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}html, body{margin: 0;padding: 0;-webkit-print-color-adjust: exact;font-family: Arial, sans-serif;font-size: 11px;color: #000;font-weight: bold}.nddContents, #nddContentsPrintPrev{margin: 0;padding: 0;display: table}.nddColPre,.nddCol{display: inline-block;position: absolute;padding: 0;}.right-rotate{-webkit-transform: rotate(90deg);-moz-transform: rotate(90deg);-ms-transform: rotate(90deg);-o-transform: rotate(90deg);transform: rotate(90deg)}.left-rotate{-webkit-transform: rotate(270deg);-moz-transform: rotate(270deg);-ms-transform: rotate(270deg);-o-transform: rotate(270deg);transform: rotate(270deg)}table.pf-report-table-none, table.pf-report-table-none td, table.pf-report-table-none th{border: 0px #fff solid}table.pf-report-table-dotted, table.pf-report-table-dotted td, table.pf-report-table-dotted th{border: 1px #000 dotted}table.pf-report-table-dashed, table.pf-report-table-dashed td, table.pf-report-table-dashed th{border: 1px #000 dashed}table.pf-report-table-solid, table.pf-report-table-solid td, table.pf-report-table-solid th{border: 1px #000 solid}#nddContentsPrintPrev{border:0;}",
                removeInline: false        
            });

            if ($("body").find(".nddContents").length > 0) {
                $("body").find(".nddContents").remove();
            }                 
            if ($('#dialog-ndd-template').length) {
                $("#dialog-ndd-template").empty().dialog('destroy').remove();
            }            
        });
    }        
</script>    