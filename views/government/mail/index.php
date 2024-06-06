<div class="intranet intranet-<?php echo $this->uniqId ?> mt0">
    <div class="page-content">
        <div class="sidebar v2 sidebar-light sidebar-main sidebar-expand-md" style="min-height: inherit;">
            <div class="sidebar-mobile-toggler text-center">
                <a href="javascript:void(0);" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                Navigation
                <a href="javascript:void(0);" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <div class="sidebar-content">
                <div class="card card-sidebar-mobile">
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="height-scroll tab-pane fade show active intranet-file-<?php echo $this->uniqId ?>" id="intranet-file">
                                <?php echo isset($this->menu) ? $this->menu : ''; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md file" style="width:18.875rem;">
            <div class="sidebar-mobile-toggler text-center">
                <a href="javascript:void(0);" class="sidebar-mobile-secondary-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                <span class="font-weight-semibold">Secondary sidebar</span>
                <a href="javascript:void(0);" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <div class="sidebar-content">
                <div class="card cardlist-<?php echo $this->uniqId ?>">
                    <div class="card-header bg-white header-elements-inline p-0 pr-2 mail-inb">
                        <ul class="nav nav-tabs nav-tabs-highlight mb-0 border-0 all-unread-tabs">
                            <li class="nav-item border-top-1 border-gray">
                                <a href="#mail-all" class="nav-link pl-4 pr-4 pt11 pb11 active show" data-toggle="tab">Бүгд</a>
                            </li>
                            <li class="nav-item border-top-1 border-right-1 border-gray">
                                <a href="#mail-unread" class="nav-link pl-4 pr-4 pt11 pb11" data-toggle="tab">Уншаагүй</a>
                            </li>
                        </ul>
                        <div class="second-sidebar-search-box d-none">
                            <input id="search_<?php echo $this->uniqId ?>" name="search" type="mail-search-input" class="form-control" placeholder="Хайх..." style="width:198px;">
                        </div>
                        <a href="javascript:;" data-toggle="tooltip" data-placement="bottom" title="Хайх" class="mail-search ml-auto mr6">
                            <button type="button" class="btn btn-light bg-gray border-0 p-1 pl-2 pr-2" style="background:#c0c0c0;">
                                <i class="icon-search4"></i>
                            </button>
                        </a>
                        <a href="javascript:;" id="order" data-toggle="tooltip" data-placement="bottom" title="Эрэмбэлэх" onclick="mailOrder(this)" data-status="desc" title="Огноогоор эрэмбэлэх">
                            <button type="button" class="btn btn-light bg-primary border-0 p-1 pl-2 pr-2 text-white">
                                <i class="icon-sort"></i>
                            </button>
                        </a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="mail-all">
                            <ul class="media-list media-list-linked height-scroll file-list list-<?php echo $this->uniqId ?>">
                                <?php echo isset($this->list) ? $this->list : '' ?>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="mail-unread">
                            <ul class="media-list media-list-linked height-scroll file-list list-<?php echo $this->uniqId ?> unreadlist-<?php echo $this->uniqId ?>">
                                <?php echo isset($this->unreadlist) ? $this->unreadlist : '' ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="int-main-content w-100 bg-white contentdata-<?php echo $this->uniqId ?>">
            <div class="content-<?php echo $this->uniqId ?>">
                <?php echo isset($this->content) ? $this->content : '' ?>
            </div>
        </div>
    </div>
</div>
<input name="image" type="file" id="upload_<?php echo $this->uniqId ?>" class="d-none" onchange="">
    
<style type="text/css">
    .hidden{display:none;}
    .intranet-<?php echo $this->uniqId ?> .spinner,
    #modal_theme_primary_<?php echo $this->uniqId ?> .spinner
    {
        width: initial !important;
        height: initial !important;
    }
    
    .ui-widget[aria-describedby="dialog-dataview-selectable-1585648701083"],
    .ui-widget[aria-describedby="dialog-confirm-<?php echo $this->uniqId ?>"]
    {
        z-index: 1052 !important; 
    }
    
    .ui-widget-overlay[aria-describedby="dialog-dataview-selectable-1585648701083"],
    .ui-widget-overlay[aria-describedby="dialog-confirm-<?php echo $this->uniqId ?>"] {
        z-index: 1051 !important; 
    }
    
    #sentmail_<?php echo $this->uniqId ?> .mce-wordcount {
        display: none !important;
    }
    
    #sentmail_<?php echo $this->uniqId ?> .select2-choices {
        /* height: 38px !important; */
    }
</style>

<script type="text/javascript">
    
    var isforward_<?php echo $this->uniqId ?> = '0';
    var $timeout_<?php echo $this->uniqId ?> = '0';
    
    $(function () {
        
        $.getScript(URL_APP + 'assets/custom/addon/plugins/pdfobject/pdfobject.min.js', function(){});
        
        $("button.cc").click(function() {
            if ($(this).attr('show-status') === 'undefined' || $(this).attr('show-status') !== '0') {
                $(this).closest('.mailcontainer_<?php echo $this->uniqId ?>').find(".inputcc").show();
                $(this).attr('show-status', '0');
            } else {
                $(this).closest('.mailcontainer_<?php echo $this->uniqId ?>').find(".inputcc").hide();
                $(this).attr('show-status', '1');
            }
        });
        
        $("button.bcc").click(function() {
            if ($(this).attr('show-status') === 'undefined' || $(this).attr('show-status') !== '0') {
                $(this).closest('.mailcontainer_<?php echo $this->uniqId ?>').find(".inputbcc").show();
                $(this).attr('show-status', '0');
            } else {
                $(this).closest('.mailcontainer_<?php echo $this->uniqId ?>').find(".inputbcc").hide();
                $(this).attr('show-status', '1');
            }
        });
        
        if ($('body').find('.list-<?php echo $this->uniqId ?> li:not(".unread"):eq(0)').length > 0) {
            $('.list-<?php echo $this->uniqId ?>').find('li[data-status="active"]').removeAttr('data-status');
            $(this).attr('data-status', 'active');
            returncontent_<?php echo $this->uniqId ?> ($('body').find('.list-<?php echo $this->uniqId ?> li:not(".unread"):eq(0)'));
        }
        
    });
    
    $('body').on('click', '.intranet-file-<?php echo $this->uniqId ?> li.nav-item', function () {
        var $this = $(this);
        var $dataRow = JSON.parse($this.attr('data-rowdata'));
        var $parent = $this.closest('ul');
        
        if ($dataRow['typeid'] == '0') {
            return;
        }
        
        $parent.find('a.active').removeClass('active');
        $this.find('a.nav-link').addClass('active');
        $('#search_<?php echo $this->uniqId ?>').val('');
        
        Core.blockUI({
            target: $('.cardlist-<?php echo $this->uniqId ?>'),
            animate: true,
            textOnly: '<i class="icon-spinner4 spinner"></i>'
        });
        
        returncontentlist_<?php echo $this->uniqId ?> ($dataRow, $dataRow['dataviewid']);
    });
    
    $('body').on('click', '.list-<?php echo $this->uniqId ?> li', function () {
        $('.list-<?php echo $this->uniqId ?>').find('li[data-status="active"]').removeAttr('data-status');
        $(this).attr('data-status', 'active');
        returncontent_<?php echo $this->uniqId ?> (this);
    });
    
    function send_<?php echo $this->uniqId ?>($type, element, otherUniqId) {
        var uniqId = (typeof otherUniqId !== 'undefined') ? otherUniqId : '<?php echo $this->uniqId ?>';
        if ($type == '1') {
           $("#sentmail_" +  uniqId).validate({ errorPlacement: function() {} });

            if ($("#sentmail_" +  uniqId).valid()) {
                submitsent_<?php echo $this->uniqId ?>($type, undefined, element, uniqId);
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'warning',
                    text: "Шаардлагатай талбаруудыг бөглөнө үү",
                    type: 'warning',
                    sticker: false
                });
            } 
        } else {
            submitsent_<?php echo $this->uniqId ?>($type, undefined, element, uniqId);
        }
    }
    
    function submitsent_<?php echo $this->uniqId ?>($type, $dialogConfirm, element, uniqId) {
        $("#sentmail_" + uniqId).ajaxSubmit({
            type: 'post',
            url: "government/sendmail/" + $type,
            dataType: 'json',
            beforeSend: function() {
                if (typeof element !== 'undefined') {
                    blockContent_<?php echo $this->uniqId ?> (element);
                } else {
                    Core.blockUI({
                        message: 'Түр хүлээнэ үү',
                        boxed: true
                    });
                }
            },
            success: function(response) {

                new PNotify({
                    title: response.status,
                    text: response.text,
                    type: response.status,
                    sticker: false
                });

                if (response.status === 'success') {
                    
                    if (typeof $dialogConfirm !== 'undefined') {
                        $("#" + $dialogConfirm).dialog('close');
                    }
                    
                    fieldclear_<?php echo $this->uniqId ?>(undefined, uniqId);
                    
                    if (element == '#modal_theme_primary_<?php echo $this->uniqId ?> .modal-content') {
                        $('.intranet-file-<?php echo $this->uniqId ?> ul').find('li:eq(2)').trigger('click');
                    } else {
                        $('.list-<?php echo $this->uniqId ?>').find('li[data-status="active"]').trigger('click');
                    }
                }
                
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } 
                else {
                    Core.unblockUI();
                }
            },
            error: function(jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } else {
                    Core.unblockUI();
                }
            }
        });
    }
    
    function initTinyMceEditor_<?php echo $this->uniqId ?>($elementSelector, otherheight) {
        tinymce.dom.Event.domLoaded=true;
        tinymce.baseURL=URL_APP + 'assets/custom/addon/plugins/tinymce';
        tinymce.suffix=".min";
        tinymce.init({
            selector: $elementSelector,
            // height: $(window).height() - otherheight + 'px',
            height: '180px',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight fullpage'
            ],
            toolbar1: 'fontselect fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | customInsertButton currentdate',
//            toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen',
            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt', 
            lineheight_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 1.0 1.15 1.5 2.0 2.5 3.0',
            image_advtab: true, 
            setup: function(editor) {
                function insertImageCustom_<?php echo $this->uniqId ?>() {
                    $('#upload_<?php echo $this->uniqId ?>').trigger('click');
                    $('#upload_<?php echo $this->uniqId ?>').on('change', function() {
                        var file = this.files[0];
                        if (file) {
                            $(this).val('');
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                editor.insertContent('<img src="'+e.target.result+'" />');
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                editor.on('change', function() {
                    tinymce.triggerSave();
                });

                editor.addButton('currentdate', {
                  icon: 'image',
                //   text: 'Image',
                  tooltip: "Insert image",
                  onclick: insertImageCustom_<?php echo $this->uniqId ?>
                });
            },
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            paste_data_images: true,
            menubar: false,
            statusbar: true,
            resize: true,
            theme_advanced_toolbar_location : "bottom",
            theme_modern_toolbar_location : "bottom",
            paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
            mentions: {
                delimiter: '#'
            },
            document_base_url: URL_APP,
            content_css: URL_APP + 'assets/custom/css/print/tinymce.css'
        });
    }
    
    function modalshow_<?php echo $this->uniqId ?> () {
    
        $('.modal').modal({
            show: false,
            keyboard: false,
            backdrop: 'static'
        });
        
        $('.modal').draggable({
            handle: ".modal-header"
        });

        if (typeof tinymce === 'undefined') {
            $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function(){
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function(script, textStatus){
                    initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '320');
                    $('#modal_theme_primary_<?php echo $this->uniqId ?>').modal('show');
                });
            });
        } else {
            tinymce.remove('textarea#body_<?php echo $this->uniqId ?>');
            setTimeout(function(){
                initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '320');
                $('#modal_theme_primary_<?php echo $this->uniqId ?>').modal('show');
            }, 100);
        }

        Core.initUniform($("#modal_theme_primary_<?php echo $this->uniqId ?>"));
        $("#modal_theme_primary_<?php echo $this->uniqId ?>").find('.modal-backdrop').remove();
        
    }
    
    function close_<?php echo $this->uniqId ?>() {
        var $body = $('textarea[name="body"]').val();
        $body = $body.replace(/<[^>]*>?/gm, '');
        $body = escape($body).replace(new RegExp("%0A", 'g'), "");
        
        if ($body.length) {
            var $html = 'Ноорог үүсгэх үү?';
            dialogConfirm_<?php echo $this->uniqId ?> ('submitsent_<?php echo $this->uniqId ?>', $html, '1', '', '');
        } else {
            fieldclear_<?php echo $this->uniqId ?>('1')
        }
    }
    
    function dialogConfirm_<?php echo $this->uniqId ?> (callbackFunction, $html, data, $dialogName, $element) {

        var $dialogConfirm = 'dialog-confirm-<?php echo $this->uniqId ?>';
        if (!$("#" + $dialogConfirm).length) {
            $('<div id="' + $dialogConfirm + '"></div>').appendTo('.intranet-<?php echo $this->uniqId ?>');
        }
        var $dialog = $("#" + $dialogConfirm);

        $dialog.empty().append($html);
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Баталгаажуулалт',
            width: 400,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('close');
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    window[callbackFunction](data, $dialogConfirm, $dialogName, $element);
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                    fieldclear_<?php echo $this->uniqId ?>();
                }}
            ]
        });
        $dialog.dialog('open');

    }
    
    function fieldclear_<?php echo $this->uniqId ?>(noreload, uniqId) {
        var otherUniqId = (typeof uniqId !== 'undefined') ? uniqId : '<?php echo $this->uniqId ?>';
        
        tinymce.remove('textarea#body_<?php echo $this->uniqId ?>');
                    
        $("#modal_theme_primary_" + otherUniqId).modal('hide');
        $("#sentmail_" + otherUniqId).find('input').val('');
        $("#sentmail_" + otherUniqId).find('textarea').val('<?php echo $this->signature; ?>');
        $("#sentmail_" + otherUniqId).find('select').select2('val', '');

        $("button.cc").removeAttr('show-status');
        $("button.bcc").removeAttr('show-status');
        
        $('.mailcontainer_' + otherUniqId).find(".inputcc").hide();
        $('.mailcontainer_' + otherUniqId).find(".inputbcc").hide();
        $('#modal_theme_primary_' + otherUniqId + ' .list-view-file-new').empty();
        
        $('#modal_theme_primary_' + otherUniqId).find('.receiverId<?php echo $this->uniqId ?>').find('button[data-paramcode="receiverId"]').html('..');
        $('#modal_theme_primary_' + otherUniqId).find('.receiverId<?php echo $this->uniqId ?>').find('button.removebtn').hide();
        
        if (typeof noreload !== 'undefined') {
            var $activeelement = $('.intranet-file-<?php echo $this->uniqId ?> li.nav-item').find('a.active');
            reloadmenu_<?php echo $this->uniqId ?> ($activeelement.closest('li').index());
        }
    }
    
    function trash_<?php echo $this->uniqId ?> ($element) {
        var $html = 'Устгахдаа итгэлтэй байна уу?';
        dialogConfirm_<?php echo $this->uniqId ?> ('trashmail_<?php echo $this->uniqId ?>', $html, '', '', $element);
    }
    
    function trashmail_<?php echo $this->uniqId ?> ($trashId, $dialogConfirm, $dialogName, $element) {
        var $this = $($element);
        var $dataRow = JSON.parse($this.attr('data-rowdata'));
        
        $.ajax({
            type: 'post',
            url: 'government/runProcess', 
            data: {
                dataRow: $dataRow
            }, 
            dataType: 'json',
            async: false, 
            beforeSend: function () {
                if (typeof $dialogConfirm !== 'undefined') {
                    $("#" + $dialogConfirm).dialog('close');
                }
            },
            success: function (response) {
                
                new PNotify({
                    title: response.status,
                    text: response.text,
                    type: response.status,
                    sticker: false
                });
                
                if (response.status === 'success') {
                    
                    if (typeof $dialogConfirm !== 'undefined') {
                        $("#" + $dialogConfirm).dialog('close');
                    }
                    
                    $('.content-<?php echo $this->uniqId ?>').empty();
                    var $activeelement = $('.intranet-file-<?php echo $this->uniqId ?> li.nav-item').find('a.active');
                    
                    reloadmenu_<?php echo $this->uniqId ?> ($activeelement.closest('li').index());
                }
                
            },
            error: function() {
                
            }
        });
    }
    
    function reloadmenu_<?php echo $this->uniqId ?> ($activeindex) {
        $.ajax({
            type: 'post',
            url: 'government/reloadmenu', 
            data: {
                uniqId: '<?php echo $this->uniqId ?>'
            }, 
            dataType: 'json',
            async: false, 
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?> ('.intranet-file-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                $('.intranet-file-<?php echo $this->uniqId ?>').empty().append(data.Html).promise().done(function () {
                    Core.unblockUI('.intranet-file-<?php echo $this->uniqId ?>');
                    
                    if (typeof $activeindex !== 'undefined') {
                        $('.intranet-file-<?php echo $this->uniqId ?> li.nav-item:eq('+ $activeindex +') > a').trigger('click');
                        $('.intranet-file-<?php echo $this->uniqId ?> li.nav-item:eq('+ $activeindex +') > a');
                    }
                });
            },
            error: function() {
                Core.unblockUI('.intranet-file-<?php echo $this->uniqId ?>');
            }
        });
    }
    
    function printMail_<?php echo $this->uniqId ?> () {
        var $mainSelectorBody = $('.main-body-<?php echo $this->uniqId ?>');
    }
    
    function replyforward_<?php echo $this->uniqId ?> ($element, $type) {
        var $this = $($element);
        var $dataRow = JSON.parse($this.attr('data-rowdata'));
        
        $.ajax({
            type: 'post',
            url: 'government/replyForward', 
            data: {
                uniqId: '<?php echo $this->uniqId ?>',
                dataRow: $dataRow,
                id: $this.attr('data-id'),
                type: $type,
                processCode: $this.attr('data-getprocesscode'),
            }, 
            dataType: 'json',
            async: false, 
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?> ('.contentdata-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                
                $('.content-<?php echo $this->uniqId ?>').empty().append(data.Html).promise().done(function () {
                    
                    Core.initAjax($(".mail_forward_<?php echo $this->uniqId ?>"));
                    setTimeout(function() {
                        Core.unblockUI('.contentdata-<?php echo $this->uniqId ?>');
                    }, $timeout_<?php echo $this->uniqId ?>);
                    
                    
                });
            },
            error: function() {
                Core.unblockUI('.contentdata-<?php echo $this->uniqId ?>');
            }
        });
    }
    
    function returncontent_<?php echo $this->uniqId ?> ($element) {
        var $this = $($element);
        var $dataRow = JSON.parse($this.attr('data-rowdata'));
        
        $.ajax({
            type: 'post',
            url: 'government/mailFolderData', 
            data: {
                getprocesscode: $this.attr('data-getprocesscode'),
                dataRow: $dataRow,
                uniqId: '<?php echo $this->uniqId ?>',
                isforward: '0'
            }, 
            dataType: 'json',
            async: false, 
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?> ('.contentdata-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                $('.content-<?php echo $this->uniqId ?>').empty().append(data.Html).promise().done(function () {
                    
                    setTimeout(function() {
                        Core.unblockUI('.contentdata-<?php echo $this->uniqId ?>');
                    }, $timeout_<?php echo $this->uniqId ?>);
                    
                    var $thisSelector = $('.list-<?php echo $this->uniqId ?>').find('li[data-mailid="'+ $this.attr('data-mailid') +'"]');
                    if ($thisSelector.hasClass('unread')) {
                        $thisSelector.removeClass('unread');
                        $thisSelector.find('.font-weight-bold').removeClass('font-weight-bold');
                    }
                    
                    $('.unreadlist-<?php echo $this->uniqId ?>').find('li[data-mailid="'+ $this.attr('data-mailid') +'"]').remove();
                    
                    if (typeof data.isforward !== 'undefined' && data.isforward === '0') {
                        Core.initAjax($(".mail_forward_<?php echo $this->uniqId ?>"));
                    }
                    
                });
            },
            error: function() {
                setTimeout(function(){
                    Core.unblockUI('.contentdata-<?php echo $this->uniqId ?>');
                }, $timeout_<?php echo $this->uniqId ?>);
            }
        });
    }
    
    function returncontentlist_<?php echo $this->uniqId ?> ($dataRow, dataViewId, searchText, orderBy) {
        var $mainSelector = $('.intranet-<?php echo $this->uniqId ?>');
        
        $.ajax({
            type: 'post',
            url: 'government/mailFolder', 
            data: {
                dataviewid: dataViewId,
                uniqId: '<?php echo $this->uniqId ?>',
                search: (typeof searchText !== 'undefined') ? searchText : '',
                orderBy: (typeof orderBy !== 'undefined') ? orderBy : ''
            }, 
            dataType: 'json',
            async: false, 
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?> ('.cardlist-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                $('.list-<?php echo $this->uniqId ?>').empty().append(data.Html).promise().done(function () {
                    var $this = $(this);
                    setTimeout(function(){
                        Core.unblockUI('.cardlist-<?php echo $this->uniqId ?>');
                    }, $timeout_<?php echo $this->uniqId ?>);
                    
                    isforward_<?php echo $this->uniqId ?> = (typeof data.isforward !== 'undefined' && data.isforward === '1') ? '1' : '0';
                    /*
                    
                    if (typeof $dataRow.typeid !== 'undefined' && $dataRow.typeid != '1') {
                        $mainSelector.find(".second-sidebar-search-box").removeClass('d-none').addClass('d-flex');
                        $mainSelector.find(".all-unread-tabs").addClass('d-none').removeClass('d-flex');
                    } else if ($mainSelector.find(".second-sidebar-search-box").hasClass('d-flex') && (typeof searchText === 'undefined' && typeof orderBy === 'undefined')) {
                        $mainSelector.find(".second-sidebar-search-box").addClass('d-none').removeClass('d-flex');
                        $mainSelector.find(".all-unread-tabs").addClass('d-flex').removeClass('d-none');
                    }
    
                    */
                    
                    if ($this.find('li:not(".unread"):eq(0)').length > 0) {
                        $('.list-<?php echo $this->uniqId ?>').find('li[data-status="active"]').removeAttr('data-status');
                        $this.find('li:not(".unread"):eq(0)').attr('data-status', 'active');
                        returncontent_<?php echo $this->uniqId ?> ($this.find('li:not(".unread"):eq(0)'));
                    } else {
                        $('.content-<?php echo $this->uniqId ?>').empty();
                    }
                });
                
                $('.unreadlist-<?php echo $this->uniqId ?>').empty().append(data.Html2).promise().done(function () {});
            },
            error: function() {
                
                setTimeout(function() {
                    Core.unblockUI('.cardlist-<?php echo $this->uniqId ?>');
                }, $timeout_<?php echo $this->uniqId ?>);
            }
        });
    }
    
    function fileview_<?php echo $this->uniqId ?>(element, $contentId) {
        var $this = $(element);
        var $mainSelector = $this.closest('div#filelibrarybody');
        var $cpContent = $mainSelector.find('.fileviewcontent_' + $contentId).html();
        
        $mainSelector.find('.fileviewer').empty().append($mainSelector.find('.fileviewcontent_' + $contentId).html());
        
        $.ajax({
            url: 'government/contentViewUser',
            type: 'post',
            data: {contentId: $contentId, type: '1'},
            dataType: 'JSON',
            beforeSend: function () {},
            success: function (data) {}
        });
        
    }
    
    var interval<?php echo $this->uniqId ?> = 10000 * 1; //1 minute
    
    setInterval(function () {
        <?php if (defined('CONFIG_IS_NO_RELOAD') && CONFIG_IS_NO_RELOAD) { ?>
            console.log('CONFIG_IS_NO_RELOAD');
            return false;
        <?php } ?>
            
        if (!$('.intranet-file-<?php echo $this->uniqId ?>').is(":visible")) {
            console.log('no visible mailtab');
            return;
        }
        
        reloadmenu_<?php echo $this->uniqId ?>();
        getLeftMenuCount(true);
    }, interval<?php echo $this->uniqId ?>);
    
</script>

<div id="modal_theme_primary_<?php echo $this->uniqId ?>" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Захидал бичих</h6>
                <button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>()">&times;</button>
                <!--class="close" data-dismiss="modal"-->
            </div>
            <div class="modal-body">
                <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'sentmail_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                    <div class="d-flex flex-row align-items-center mb-2">
                        <div class="col-1 p-0">
                            <span class="mr-2">Хэрэглэгч</span>
                        </div>
                        <div class="col-11 p-0 receiverId<?php echo $this->uniqId ?>">
                            <div class="input-group">
                                <select id="receiverId" name="receiverId[]" class="form-control form-control-sm dropdownInput select2 bp-field-with-popup-combo select2-offscreen" data-path="receiverId" data-field-name="receiverId" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1585648701083&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;MM_RECIPIENT_DV.receiverId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1571133709810&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Сонгох -" tabindex="-1"></select> 
                                <span class="input-group-append"> 
                                    <button class="btn btn-primary mr0" type="button" data-lookupid="1585648701083" data-paramcode="receiverId" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="name" onclick="bpBasketDvWithPopupCombo(this);">..</button>
                                    <button class="btn btn-danger mr0 removebtn" type="button" data-lookupid="1585648701083" onclick="bpRemoveAllBasketWithPopupCombo(this);" style="display: none;"><i class="fa fa-trash"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-2">
                        <div class="col-1 p-0">
                            <span class="mr-2">Гарчиг</span>
                        </div>
                        <div class="col-11 p-0">
                            <input type="text" name="subject" placeholder="" class="form-control">
                        </div>
                    </div>

                    <textarea rows="12" class="form-control" id="body_<?php echo $this->uniqId ?>" name="body" placeholder="Захидал"><?php echo $this->signature; ?></textarea>
                    <div class="mt-2">
                        <a href="javascript:;" class="btn btn-sm btn-outline bg-primary border-primary text-primary-800 btn-icon border-1 btn fileinput-button" title="Файл нэмэх">
                            <i class="icon-attachment mr-1"></i> Файл нэмэх
                            <input type="file" name="bp_file[]" class="" multiple onchange="onChangeAttachFIleAddMode(this)" />
                        </a>
                    </div>
                    <div class="card-body">
                        <ul class="row list-inline mb-0 list-view-file-new filelist-<?php echo $this->uniqId ?>"></ul>
                    </div>
                <?php echo Form::close(); ?>
            </div>
            <div class="modal-footer" id='block<?php echo $this->uniqId ?>'>
                <button type="button" class="btn btn-sm btn-link bg-teal-400 dismiss-btn-<?php echo $this->uniqId ?>" onclick="send_<?php echo $this->uniqId ?>('1', '#modal_theme_primary_<?php echo $this->uniqId ?> .modal-content')">Ноороглох</button>
                <button type="button" class="btn btn-sm send-btn-<?php echo $this->uniqId ?>" onclick="send_<?php echo $this->uniqId ?>('0', '#modal_theme_primary_<?php echo $this->uniqId ?> .modal-content')">Илгээх</button>
                <button type="button" class="btn btn-link close-btn-<?php echo $this->uniqId ?>" data-dismiss="modal">Хаах</button>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
</div>

<style type="text/css">
    .intranet-<?php echo $this->uniqId ?> .nav-sidebar {
        padding-left: 10px;
    }
    .intranet-<?php echo $this->uniqId ?> .float-right>.dropdown-menu {
        right: auto;
    }
    .intranet-<?php echo $this->uniqId ?> .dropdown > .dropdown-menu.float-left:before, 
    .intranet-<?php echo $this->uniqId ?> .dropdown-toggle > .dropdown-menu.float-left:before, 
    .intranet-<?php echo $this->uniqId ?> .btn-group > .dropdown-menu.float-left:before {
        left: 9px;
        right: auto;
    }
    
    .intranet-<?php echo $this->uniqId ?> .fileinput-button .big {
        font-size: 70px;
        line-height: 112px;
        text-align: center;
        color: #ddd;
    }
  
    #modal_theme_primary_<?php echo $this->uniqId ?> .list-view-file-new::-webkit-scrollbar,
    .mail_forward_<?php echo $this->uniqId ?> .list-view-file-new::-webkit-scrollbar
    {
        width: 4px !important;
    }
    
    #modal_theme_primary_<?php echo $this->uniqId ?> .list-view-file-new::-webkit-scrollbar-thumb,
    .mail_forward_<?php echo $this->uniqId ?> .list-view-file-new::-webkit-scrollbar-thumb {
        background: #4b76a5 !important;
    }
    
    #modal_theme_primary_<?php echo $this->uniqId ?> .list-view-file-new,
    .mail_forward_<?php echo $this->uniqId ?> .list-view-file-new {
        max-height: 168.2px;
        overflow: auto; 
        margin: 0;
    }
    
    #modal_theme_primary_<?php echo $this->uniqId ?> .fancybox-button,
    .mail_forward_<?php echo $this->uniqId ?> .fancybox-button{
        background: none;
        height: initial;
        width: initial;
        color: #2196f3;
        padding: 0;
    }
    #modal_theme_primary_<?php echo $this->uniqId ?> .mce-edit-area {
        border-width: 1px 0px 0px 1px !important;
    }
    
    
    .mail_forward_<?php echo $this->uniqId ?> .select2-container-multi .select2-choices .select2-search-field input,
    #modal_theme_primary_<?php echo $this->uniqId ?> .select2-container-multi .select2-choices .select2-search-field input
    {
        padding: 2px !important;
    }
    
    .mail_forward_<?php echo $this->uniqId ?> .select2-container-multi .select2-choices,
    #modal_theme_primary_<?php echo $this->uniqId ?> .select2-container-multi .select2-choices
    {
        /* min-height: 40px !important; */
    }
    
</style>

<script type="text/javascript">
    
    $(function () {
        if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
        }
                
        $('#search_<?php echo $this->uniqId ?>').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                search_<?php echo $this->uniqId ?>();
            }
        });
    });
    
    $('body').on('click', '.close-btn-<?php echo $this->uniqId ?>', function () {
        fieldclear_<?php echo $this->uniqId ?>();
    });
    
    function onChangeAttachFIleAddMode(input, $type) {
        if ($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx",
            "zip", "rar", "mp3", "mp4"])) {
            var ext = input.value.match(/\.([^\.]+)$/)[1],
                i = 0;
            if (typeof ext !== "undefined") {

                for (i; i < input.files.length; i++) {
                    ext = input.files[i].name.match(/\.([^\.]+)$/)[1];
                    
                    var li = '',
                            fileImgUniqId = Core.getUniqueID('file_img'),
                            fileAUniqId = Core.getUniqueID('file_a'),
                            extension = ext.toLowerCase();
                    var $icon = 'icon-file-pdf';
                    switch (extension) {
                        case 'png':
                        case 'gif':
                        case 'jpeg':
                        case 'pjpeg':
                        case 'jpg':
                        case 'x-png':
                        case 'bmp':
                            $icon = "icon-file-picture text-danger-400";
                            break;
                        case 'zip':
                        case 'rar':
                            $icon = "icon-file-zip text-danger-400";
                            break;
                        case 'mp3':
                            $icon = "icon-file-music text-danger-400";
                            break;
                        case 'mp4':
                            $icon = "icon-file-video text-danger-400";
                            break;
                        case 'doc':
                        case 'docx':
                            $icon = "icon-file-word text-blue-400";
                            break;
                        case 'pdf':
                            $icon = "icon-file-pdf text-danger-400";
                            break;
                        case 'ppt':
                        case 'pptx':
                            $icon = "icon-file-presentation text-danger-400";
                            break;
                        case 'xls':
                        case 'xlsx':
                            $icon = "icon-file-excel text-green-400";
                            break;
                        default:
                            $icon = "icon-file-empty text-danger-400";
                            break;
                    }
                    
                    var $liAfter = '<div class="col-4 pl-0">' +
                                        '<li class="list-inline-item w-100">' + 
                                            '<div class="card bg-light py-1 px-2 mt-2 mb-0">' +
                                                '<div class="media my-1">' +
                                                    '<div class="mr-3 align-self-center">' +
                                                        '<i class="'+ $icon +' icon-2x top-0"></i>' +
                                                    '</div>' +
                                                    '<div class="media-body">' +
                                                        '<div class="font-weight-semibold">'+ input.files[i].name +'</div>' +
                                                        '<ul class="list-inline list-inline-condensed mb-0">' +
                                                            '<li class="list-inline-item text-muted">'+ formatSizeUnits(input.files[i].size) +'</li>' +
                                                            '<li class="list-inline-item">' + 
                                                                '<input type="hidden" name="bp_file_name['+ i +']" class="form-control col-md-12 bp_file_name" placeholder="Тайлбар"/>' +
                                                                '<a href="javascript:void(0);" id="' + fileAUniqId + '" class="fancybox-button main" data-rel="fancybox-button">' + 
                                                                    'Харах'+ 
                                                                '</a>' + 
                                                            '</li>' +
                                                        '</ul>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</li>' +
                                    '</div>';
                            
                    var $listViewFile = (typeof $type !== 'undefined') ? $('.mail_forward_<?php echo $this->uniqId ?> .list-view-file-new') : $('#modal_theme_primary_<?php echo $this->uniqId ?> .list-view-file-new');
                    
//                    $listViewFile.append(li);
                    
                    $listViewFile.append($liAfter);
                    Core.initFancybox($listViewFile);

                    previewPhotoAddMode(input.files[i], $listViewFile.find('#' + fileImgUniqId), $listViewFile.find('#' + fileAUniqId));

                    initFileContentMenuAddMode();
                }
                var $this = $(input), $clone = $this.clone();
                $this.after($clone).appendTo($('.hiddenFileDiv'));

            }
        } else {
            alert('Файл сонгоно уу.');
            $(input).val('');
        }
    }

    function previewPhotoAddMode(input, $targetImg, $targetAnchor) {
        if (input) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $targetImg.attr('src', e.target.result);
                $targetAnchor.attr('href', e.target.result);
            };
            reader.readAsDataURL(input);
        }
    }

    function initFileContentMenuAddMode() {
        $.contextMenu({
            selector: '#modal_theme_primary_<?php echo $this->uniqId ?> ul.list-view-file-new li',
            callback: function (key, opt) {
                if (key === 'delete') {
                    deleteBpTabFileAddMode(opt.$trigger);
                }
            },
            items: {
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
    }

    function deleteBpTabFileAddMode(li) {
        var dialogName = '#deleteConfirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Сануулах',
            width: '350',
            height: 'auto',
            modal: true,
            buttons: [
                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                        li.remove();
                        $(dialogName).dialog('close');
                    }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
            ]
        });
        $(dialogName).dialog('open');
    }
    
    function formatSizeUnits(bytes) {
        if      (bytes >= 1073741824) { bytes = (bytes / 1073741824).toFixed(2) + " gb"; }
        else if (bytes >= 1048576)    { bytes = (bytes / 1048576).toFixed(2) + " mb"; }
        else if (bytes >= 1024)       { bytes = (bytes / 1024).toFixed(2) + " kb"; }
        else if (bytes > 1)           { bytes = bytes + " bytes"; }
        else if (bytes == 1)          { bytes = bytes + " byte"; }
        else                          { bytes = "0 bytes"; }
        return bytes;
    }
    
    function search_<?php echo $this->uniqId ?>(orderby) {
        
        var $activeElement = $('.intranet-file-<?php echo $this->uniqId ?> li.nav-item').find('a.active').closest('li');
        var $dataRow = JSON.parse($activeElement.attr('data-rowdata'));
        
        returncontentlist_<?php echo $this->uniqId ?> ($dataRow, $dataRow['dataviewid'], $("#search_<?php echo $this->uniqId ?>").val(), orderby);
    }
    
    function mailOrder(element) {
        var status = $(element).attr('data-status');
        if (status === 'desc') {
            $(element).attr('data-status', 'asc');
            search_<?php echo $this->uniqId ?>('asc');
        } 
        else {
            $(element).attr('data-status', 'desc');
            search_<?php echo $this->uniqId ?>('desc');
        }
    }
    
    function blockContent_<?php echo $this->uniqId ?> (mainSelector) {
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

    $('.cardlist-<?php echo $this->uniqId ?> a.mail-search').click(function() {
    
        var $mainSelector = $('.cardlist-<?php echo $this->uniqId ?> .all-unread-tabs');
        
        if ($mainSelector.hasClass('d-none')) {
            $mainSelector.removeClass('d-none').addClass('d-flex');
            $(".cardlist-<?php echo $this->uniqId ?> .second-sidebar-search-box").removeClass('d-flex').addClass('d-none');
        } 
        else {
            $mainSelector.addClass('d-none').removeClass('d-flex');
            $(".cardlist-<?php echo $this->uniqId ?> .second-sidebar-search-box").addClass('d-flex').removeClass('d-none');
        }
        
    });
    
</script>