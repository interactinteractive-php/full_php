var isEcmContentScript = true;

function ecmContentHtmlEditorInit(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    if (typeof tinymce === 'undefined') {
        $.cachedScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function() { 
            ecmContentHtmlEditorTinymce(elem, processMetaDataId, dataViewId, selectedRow, paramData);
        });
    } else {
        setTimeout(function(){
            ecmContentHtmlEditorTinymce(elem, processMetaDataId, dataViewId, selectedRow, paramData);
        }, 50);
    }
}

function ecmContentHtmlEditorTinymce(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    var $dialogName = 'dialog-ecmcontent-html';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdcontentui/htmlEditor', 
        data: paramData, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 950,
                    height: 'auto',
                    modal: true,
                    open: function () {
                        
                        var windowHeight = $(window).height();
                        var _tinymceHeight = windowHeight - 250;
                        _tinymceHeight = (_tinymceHeight <= 100) ? '400px' : _tinymceHeight+ 'px';
                        
                        tinymce.dom.Event.domLoaded = true;
                        tinymce.baseURL = URL_APP+'assets/custom/addon/plugins/tinymce';
                        tinymce.suffix = ".min";

                        tinymce.init({
                            selector: 'textarea#ecmContentBody',
                            height: _tinymceHeight,
                            plugins: [
                                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                'searchreplace wordcount visualblocks visualchars code fullscreen',
                                'insertdatetime media nonbreaking save table contextmenu directionality importcss codemirror',
                                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager lineheight'
                            ],
                            toolbar1: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                            toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen | code | customEnterSpacer',
                            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt', 
                            lineheight_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 1.0 1.15 1.5 2.0 2.5 3.0',
                            image_advtab: true, 
                            force_br_newlines: true,
                            force_p_newlines: false, 
                            apply_source_formatting: true, 
                            remove_linebreaks: false,
                            forced_root_block: '', 
                            paste_data_images: true, 
                            importcss_append: true, 
                            table_toolbar: '', 
                            table_class_list: [
                                {title: 'None', value: ''}, 
                                {title: 'No border', value: 'pf-report-table-none'}, 
                                {title: 'Dotted', value: 'pf-report-table-dotted'}, 
                                {title: 'Dashed', value: 'pf-report-table-dashed'},  
                                {title: 'Solid', value: 'pf-report-table-solid'}
                            ], 
                            object_resizing: 'img',
                            paste_word_valid_elements: 'b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font,page',
                            font_formats: "Andale Mono=andale mono,monospace;"+
                                "Arial=arial,helvetica,sans-serif;"+
                                "Arial Black=arial black,sans-serif;"+
                                "Book Antiqua=book antiqua,palatino,serif;"+
                                "Comic Sans MS=comic sans ms,sans-serif;"+
                                "Courier New=courier new,courier,monospace;"+
                                "Georgia=georgia,palatino,serif;"+
                                "Helvetica=helvetica,arial,sans-serif;"+
                                "Impact=impact,sans-serif;"+
                                "Symbol=symbol;"+
                                "Tahoma=tahoma,arial,helvetica,sans-serif;"+
                                "Terminal=terminal,monaco,monospace;"+
                                "Times New Roman=times new roman,times,serif;"+
                                "Calibri=Calibri, sans-serif;"+
                                "Trebuchet MS=trebuchet ms,geneva,sans-serif;"+
                                "Verdana=verdana,geneva,sans-serif;"+
                                "Webdings=webdings;"+
                                "Wingdings=wingdings,zapf dingbats;"+
                                "Next Museo=Next_MuseoSansCyrl;",
                            table_default_attributes: {
                                'border': '0'
                            },
                            table_default_styles: {
                                'border-collapsed': 'collapse',
                                'width': '100%'
                            },
                            codemirror: {
                                indentOnInit: true, 
                                fullscreen: false,   
                                path: 'codemirror', 
                                config: {           
                                    mode: 'text/html',
                                    styleActiveLine: true,
                                    lineNumbers: true, 
                                    lineWrapping: true,
                                    matchBrackets: true,
                                    autoCloseBrackets: true,
                                    indentUnit: 2, 
                                    foldGutter: true,
                                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
                                    extraKeys: {
                                        "F11": function(cm) {
                                            cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                                        },
                                        "Esc": function(cm) {
                                            if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                                        }, 
                                        "Ctrl-Q": function(cm) { 
                                            cm.foldCode(cm.getCursor()); 
                                        }, 
                                        "Ctrl-Space": "autocomplete"
                                    }
                                },
                                width: ($(window).width() - 20),        
                                height: ($(window).height() - 120),        
                                saveCursorPosition: false,    
                                jsFiles: [          
                                    'mode/clike/clike.js',
                                    'mode/htmlmixed/htmlmixed.js', 
                                    'mode/css/css.js', 
                                    'mode/xml/xml.js', 
                                    'addon/fold/foldcode.js', 
                                    'addon/fold/foldgutter.js', 
                                    'addon/fold/brace-fold.js', 
                                    'addon/fold/xml-fold.js', 
                                    'addon/fold/indent-fold.js', 
                                    'addon/fold/comment-fold.js', 
                                    'addon/hint/show-hint.js', 
                                    'addon/hint/xml-hint.js', 
                                    'addon/hint/html-hint.js', 
                                    'addon/hint/css-hint.js'
                                ]
                            },
                            setup: function(editor) {
                                editor.addButton('customEnterSpacer', {
                                    type: 'menubutton',
                                    text: 'Line spacer',
                                    icon: false,
                                    menu: [
                                        {icon: false, text: '4pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:4pt"></div>');
                                        }},
                                        {icon: false, text: '5pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:5pt"></div>');
                                        }},
                                        {icon: false, text: '6pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:6pt"></div>');
                                        }},
                                        {icon: false, text: '7pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:7pt"></div>');
                                        }},
                                        {icon: false, text: '8pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:8pt"></div>');
                                        }},
                                        {icon: false, text: '9pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:9pt"></div>');
                                        }},
                                        {icon: false, text: '10pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:10pt"></div>');
                                        }},
                                        {icon: false, text: '11pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:11pt"></div>');
                                        }},
                                        {icon: false, text: '12pt', onclick: function() {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="height:12pt"></div>');
                                        }}
                                    ]
                                });           
                                editor.on('keydown', function(evt) {    
                                    if (evt.keyCode == 9) {
                                        editor.execCommand('mceInsertContent', false, '&emsp;&emsp;');
                                        evt.preventDefault();
                                        return false;
                                    }
                                });
                            },  
                            table_responsive_width: true,  
                            plugin_preview_width: 1015, 
                            document_base_url: URL_APP, 
                            content_css: [
                                URL_APP+'assets/custom/css/print/tinymce.css', 
                                URL_APP+'assets/custom/webfonts/nextmuseo/font.css'
                            ]
                        });
                    }, 
                    close: function () {
                        tinymce.remove('textarea#ecmContentBody');
                        $dialog.empty().dialog('destroy').remove();
                    }, 
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {

                            tinymce.triggerSave();

                            $("#ecmcontent-form").validate({errorPlacement: function () {}});

                            if ($("#ecmcontent-form").valid()) {

                                $('#ecmcontent-form', '#' + $dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mdcontentui/saveHtmlEditor',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function (data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                            dataViewReload(dataViewId);
                                        } 
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }}, 
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
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
                $dialog.dialogExtend('maximize');
                $dialog.dialog('open');
                
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
            
            Core.unblockUI();
        },
        error: function () {
            alert('Error');
            Core.unblockUI();
        }
    });
}
function ecmContentHtmlDiffViewer(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    var postData = paramDataToObject(paramData);
    var $dialogName = 'dialog-ecmcontent-html';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdcontentui/ecmContentHtmlDiffViewer', 
        data: postData, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                var html = [], setHeight = $(window).height() - 130;
                
                html.push('<link href="assets/core/css/htmldiff.css" rel="stylesheet"/>');
                
                html.push('<div class="row">');
                    html.push('<div class="col-md-6 font-weight-bold text-center" style="background-color: #eee;padding-top: 5px;padding-bottom: 3px;text-transform: uppercase;">');
                        html.push('Өмнөх файл');
                    html.push('</div>');
                    html.push('<div class="col-md-6 font-weight-bold text-center" style="background-color: #94efb0;padding-top: 5px;padding-bottom: 3px;text-transform: uppercase;">');
                        html.push('Зассан файл');
                    html.push('</div>');
                html.push('</div>');
                
                html.push('<div class="row">');
                    html.push('<div class="col-md-6" style="height:'+setHeight+'px; overflow: auto; border: 1px #eee solid;">');
                        html.push('<div class="col-md-12">');
                            html.push(data.prevHtmlFile);
                        html.push('</div>');
                    html.push('</div>');
                    html.push('<div class="col-md-6" style="height:'+setHeight+'px; overflow: auto; border: 1px #eee solid;">');
                        html.push('<div class="col-md-12">');
                            html.push(data.nextHtmlFile);
                        html.push('</div>');
                    html.push('</div>');
                html.push('</div>');
                
                $dialog.empty().append(html.join(''));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Diff viewer',
                    width: 950,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    }, 
                    buttons: [ 
                        {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
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
                $dialog.dialogExtend('maximize');
                $dialog.dialog('open');
                
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
            
            Core.unblockUI();
        },
        error: function () {
            alert('Error');
            Core.unblockUI();
        }
    });
}