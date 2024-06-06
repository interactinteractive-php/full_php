
<script type="text/javascript">

   // feather.replace();
    
    var $data_<?php echo $this->uniqId ?> = [];
    var doubleClick_<?php echo $this->uniqId; ?> = false;
    var today<?php echo $this->uniqId; ?> = false;
    var $mainSelector<?php echo $this->uniqId; ?> = $('.dashboard<?php echo $this->uniqId ?>');
    
    $(document).ready(function() {
    });
    
    $('.news-post').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        autoplayTimeout:5000,
        autoplay:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:4
            }
        }
    });

    function apiChatSendMessagev1<?php echo $this->uniqId ?>(userId, text, type) {
        apiChatSendMessage(userId, text, type);
        PNotify.removeAll();
        new PNotify({
            title: 'Success',
            text: 'Амжилттай илгээгдлээ',
            type: 'success', 
            sticker: false
        });
    }
    
    function changeDateTimeFormat<?php echo $this->uniqId; ?>(date) {
        var yyyy = date.getFullYear();
        var MM = date.getMonth() + 1;
        var dd = date.getDate();

        if (MM < 10) {
            MM = '0' + MM
        }
        if (dd < 10) {
            dd = '0' + dd
        }
        /*date = MM + "/" + dd + "/" + yyyy;        */
        return yyyy + "-" + MM + "-" + dd;
    }


    function hrmTimesheetEditProcess<?php echo $this->uniqId ?>(elem, startDate, id) {
        var $dialogName = 'dialog-hrm-timesheet-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        var fillDataParams = '';

        if (id) {
            fillDataParams = 'id='+id+'&defaultGetPf=1';
        } else {
            fillDataParams = 'startDate='+startDate+'&endDate='+startDate;
        }

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: '1548836365306', 
                isDialog: true, 
                isSystemMeta: false, 
                fillDataParams: fillDataParams,  
                callerType: 'hrmTimesheet', 
                openParams: '{"callerType":"hrmTimesheet"}'
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $('#wsForm', '#' + $dialogName), 
                    processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            $processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr('id');
                                            $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });

                            var isValidPattern = initBusinessProcessMaskEvent($processForm);

                            if ($processForm.valid() && isValidPattern.length === 0) {
                                $processForm.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdwebservice/runProcess',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({
                                            boxed: true, 
                                            message: 'Түр хүлээнэ үү'
                                        });
                                    },
                                    success: function (responseData) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: responseData.status,
                                            text: responseData.message,
                                            type: responseData.status, 
                                            sticker: false
                                        });

                                        if (responseData.status === 'success') {
                                            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
                                            $dialog.dialog('close');
                                            
                                            var $html = '';
                                            var $responseData = responseData.resultData;
                                            var $startDate = ($responseData['startdate']).substr(0, 10) + ' ' +($responseData['starttime']).substr(11, 5);
                                            var $endDate = ($responseData['enddate']).substr(0, 10)+ ' ' + ($responseData['endtime']).substr(11, 5);
                                            
                                            $html += '<div class="d-flex flex-column justify-content-center mr-3" style="width:120px;">';
                                                $html += '<span class="font-size-14">&nbsp;'+ $startDate +'&nbsp;<br>&nbsp;'+ $endDate +'</span>';
                                            $html += '</div>';
                                            $html += '<div style="width:140px;">';
                                                $html += '<h5 class="mb-0 font-weight-normal font-size-14 no-border">'+ $responseData['booktypename'] +'</h5>';
                                            $html += '</div>';
                                            $html += '<div style="width:525px;">';
                                                $html += '<h5 class="mb-0 font-weight-normal font-size-14 ml10 no-border">'+ $responseData['description'] +'</h5>';
                                            $html += '</div>';
                                            
                                            $(elem).empty().append($html);
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
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';
                }

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
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
                    $dialog.dialogExtend("maximize");
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }
    
    function drilldownLink_news2<?php echo $this->uniqId; ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
       
        gridDrillDownLink(element, 'inOpenJobList', 'metagroup', '1', '',  '1577260423821868', '','', '', true, true)
    }
    function drilldownLink_news<?php echo $this->uniqId; ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        console.log(row);
        var $dialogName = 'dialog-news-'+row.id;
        $html = '';
        var $dialog = $('#' + $dialogName);
        $html += '<div class="feature0-img" style="width:100%;">';
                $html += '<div class = "f-image mb20 d-flex justify-content-center" ><img src="'+URL_APP+''+row.picture+'"/ style="max-width:100%"></div>'
                $html += '<span class="font-size-11">'+ row.booktypename +'</span>';
                $html += '<h3>'+ row.title +'</h3>';
                $html += '<p class="font-size-13 desc">'+ row.dim1 +'</p>';
            $html += '</div>';
      

        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none">'+ $html +'</div>').appendTo('body');
        }

        $("#" + $dialogName).empty().append($html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Мэдээлэл',
                width: 720,
                minWidth: 750,
                height: "auto",
                modal: true,
                close: function() {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: 'Хаах',
                    class: 'btn btn-sm blue-madison',
                    click: function() {
                        $("#" + $dialogName).dialog('close');
                    }
                }]
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
            $("#" + $dialogName).dialog('open');
            $("#" + $dialogName).dialogExtend("restore");
            Core.unblockUI();
         

        console.log(row);
        //gridDrillDownLink(element, 'HR_NEWS_LIST', 'metagroup', '1', '',  '1548219753368', '','', '', true, true)
    }
    
    function blockContent_<?php echo $this->uniqId; ?>(mainSelector) {
        $(mainSelector).block({
            message: '<i class="icon-spinner4 spinner"></i>',
            centerX: 0,
            centerY: 0,
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                width: 16,
                top: '15px',
                left: '',
                right: '15px',
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }
    function updateEvento(elem, id) {
        var processId = '1585648529412';
        var $dialogName = 'dialog-businessprocess-' + processId;
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        var fillDataParams = 'id='+id+'';

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: processId, 
                isDialog: true, 
                isSystemMeta: false, 
                fillDataParams: fillDataParams
            },
            dataType: 'json',
            beforeSend: function () {
                /*
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });*/
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $('#wsForm', '#' + $dialogName), 
                    processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            $processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr('id');
                                            $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });

                            var isValidPattern = initBusinessProcessMaskEvent($processForm);

                            if ($processForm.valid() && isValidPattern.length === 0) {
                                $processForm.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdwebservice/runProcess',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        /*
                                        Core.blockUI({
                                            boxed: true, 
                                            message: 'Түр хүлээнэ үү'
                                        });*/
                                    },
                                    success: function (responseData) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: responseData.status,
                                            text: responseData.message,
                                            type: responseData.status, 
                                            sticker: false
                                        });

                                        if (responseData.status === 'success') {
                                            $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                            $dialog.dialog('close');
                                            $('#subject_'+sid).trigger('click');
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
                        $dialog.dialog('close');
                    }}
                ];
                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';
                }          

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: false,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
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
                    $dialog.dialogExtend("maximize");
                }

                setTimeout(function() {
                    $dialog.dialog('open');
                    Core.unblockUI();
                }, 1);
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initBPAjax($dialog);
        });
    }

    function updateEvent(elem, did) {
        var datarow = JSON.parse($(elem).attr('data-row'));
        console.log(datarow);
        var processId = '1585648529412';
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }

            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: true,
                    dmMetaDataId: 1585127775592,
                    isSystemMeta: false,
                    oneSelectedRow: datarow
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName),
                            processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.validate({
                                        ignore: '',
                                        highlight: function (element) {
                                            $(element).addClass('error');
                                            $(element).parent().addClass('error');
                                            if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                    var tabId = $(tab).attr('id');
                                                    $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function (element) {
                                            $(element).removeClass('error');
                                            $(element).parent().removeClass('error');
                                        },
                                        errorPlacement: function () {}
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {
                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    boxed: true,
                                                    message: 'Түр хүлээнэ үү'
                                                });
                                            },
                                            success: function (responseData) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: responseData.status,
                                                    text: responseData.message,
                                                    type: responseData.status,
                                                    sticker: false
                                                });

                                                if (responseData.status === 'success') {
                                                    $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="' + responseData.resultData.description + '">' + responseData.resultData.description + '</div>');
                                                    $dialog.dialog('close');
                                                    reload_<?php echo $this->uniqId ?>();
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
                                $dialog.dialog('close');
                            }}
                    ];
                    var dialogWidth = data.dialogWidth,
                            dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: 500,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape),
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
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
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);

                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }
    
    
    
</script>