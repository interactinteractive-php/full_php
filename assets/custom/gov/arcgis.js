var isArcgisCalled = true;

function blockContentGovernment(mainSelector) {
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
    
function callArcgisForm (element, callback) {
    if (isArcgisPlugnCalled) {
        arcgisForm (element, callback);
    } else { 
        $.getScript('assets/custom/addon/plugins/arcgis/4.13/dojo/dojo.js').done(function() {
            isArcgisPlugnCalled = true;
            arcgisForm (element, callback);
        });
    }
}

function arcgisForm (element, callback) {
    $.ajax({
        type: 'post',
        url: 'contentui/arcgisForm',
        dataType: 'json',
        beforeSend: function () {

            if (!$("link[href='assets/custom/addon/plugins/arcgis/4.13/esri/themes/light/main.css']").length) {
                $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/arcgis/4.13/esri/themes/light/main.css"/>');
            }

            /*
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });*/
        },
        success: function (data) {

            if (data.status !== 'success') {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                return;
            }

            var $dialogConfirm = 'dialog-confirm-' + data.uniqId;
            if (!$("#" + $dialogConfirm).length) {
                $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogConfirm);

            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                height: $(window).height() - 300,
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('choose_address_btn'), class: 'btn blue-madison btn-sm addressChooseBtn-'+ data.uniqId, click: function () {
                        var $this = $('.addressChooseBtn-' + data.uniqId);
                        var bpUniqId = $(element).closest('div.xs-form').attr('data-bp-uniq-id');
                        if (typeof window['attributes' + data.uniqId] !== 'undefined' && window['attributes' + data.uniqId]) {

                            var $dataRow = window['attributes' + data.uniqId]; //JSON.parse($this.attr('data-row'));
                            delete window['attributes' + data.uniqId];

                            if (typeof callback === 'function') {
                                callback($dataRow);
                                $dialog.dialog('close');
                            } else if (typeof(window[callback + '_' + bpUniqId]) === 'function') {
                                window[callback + '_' + bpUniqId]($dataRow);
                                $dialog.dialog('close');
                            } else  {
                                alert('function олдсонгүй');
                                console.log(callback, bpUniqId);
                            }
                        } else {
                            PNotify.removeAll();
                            new PNotify({
                                title: plang.get('warning'),
                                text: plang.get('no_record_address'),
                                type: 'warning',
                                sticker: false
                            });
                            return;
                        }
                    }}
                ]
            }).dialogExtend({
                'closable': true,
                'maximizable': true,
                'minimizable': true,
                'collapsable': true,
                'dblclick': 'maximize',
                'minimizeLocation': 'left',
                'icons': {
                    'close': 'ui-icon-circle-close',
                    'maximize': 'ui-icon-extlink',
                    'minimize': 'ui-icon-minus',
                    'collapse': 'ui-icon-triangle-1-s',
                    'restore': 'ui-icon-newwin'
                }
            });

            $dialog.dialogExtend("maximize");
            $dialog.dialog('open');
        }
    });
}