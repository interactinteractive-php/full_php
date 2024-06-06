<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px;" class="left-padding"><?php echo $this->lang->line('metadata_data_model'); ?>:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="metaGroupId" name="metaGroupId" type="hidden">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><?php echo $this->lang->line('metadata_form_html'); ?></td>
                <td>
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum disabled', 'id' => 'setEditor', 'value' => '...', 'onclick' => 'setEditorForMetaGroup(this);')); ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Paging config</td>
                <td>
                <?php 
                echo Form::text(
                    array(
                        'name' => 'pagingConfig',
                        'id' => 'pagingConfig', 
                        'class' => 'form-control'
                    )
                ); 
                ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin top</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginTop',
                            'id' => 'pageMarginTop',
                            'class' => 'form-control'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin left</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginLeft',
                            'id' => 'pageMarginLeft',
                            'class' => 'form-control'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin right</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginRight',
                            'id' => 'pageMarginRight',
                            'class' => 'form-control'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin bottom</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginBottom',
                            'id' => 'pageMarginBottom',
                            'class' => 'form-control'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Archive status code</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'archiveWfmStatusCode',
                            'id' => 'archiveWfmStatusCode',
                            'class' => 'form-control'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isReport"><?php echo $this->lang->line('metadata_is_Report'); ?></label></td>
                <td> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isReport',
                            'id' => 'isReport',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isArchive"><?php echo $this->lang->line('metadata_is_archive'); ?></label></td>
                <td> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isArchive',
                            'id' => 'isArchive',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isAutoArchive"><?php echo $this->lang->line('metadata_is_auto_archive'); ?></label></td>
                <td> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isAutoArchive',
                            'id' => 'isAutoArchive',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isEmail"><?php echo $this->lang->line('metadata_is_email'); ?></label></td>
                <td> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isEmail',
                            'id' => 'isEmail',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">
                    <label for="isTableLayoutFixed">Table Layout Fixed</label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isTableLayoutFixed',
                                'id' => 'isTableLayoutFixed',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">
                    <label for="isIgnorePrint">Is ignore print</label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnorePrint',
                                'id' => 'isIgnorePrint',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">
                    <label for="isIgnoreExcel">Is ignore excel</label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnoreExcel',
                                'id' => 'isIgnoreExcel',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">
                    <label for="isIgnorePdf">Is ignore pdf</label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnorePdf',
                                'id' => 'isIgnorePdf',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">
                    <label for="isIgnoreWord">Is ignore word</label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnoreWord',
                                'id' => 'isIgnoreWord',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">
                    <label for="configStr">Template тохиргоо</label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'configStr',
                            'id' => 'configStr',
                            'class' => 'form-control',
                            'value' => ''
                        )
                    );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {
        $('#metaGroupId').on("change", function() {
            if ($('#metaGroupId').val() !== '') {
                if ($("#setEditor").hasClass("disabled")) {
                    $("#setEditor").removeClass("disabled");
                }
            } else {
                if (!$("#setEditor").hasClass("disabled")) {
                    $("#setEditor").addClass("disabled");
                }
            }
        });
    });
    
    function setEditorForMetaGroup(elem) {
        var metaGroupId = $("#metaGroupId").val();

        var htmlContent = '';
        if ($("form#addMetaSystemForm").find("textarea#htmlContent").length) {
            htmlContent = $("form#addMetaSystemForm").find("textarea#htmlContent").val();
        }
        
        var $dialogName = 'dialog-tmceTemplateEditor';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mdmetadata/setTemplateEditor',
            data: {metaGroupId: metaGroupId, htmlContent: htmlContent},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1200,
                    minWidth: 1200,
                    height: 600,
                    modal: false,
                    open: function(){
                        var _tinymceHeight = $(window).height() - 270;
                        _tinymceHeight = (_tinymceHeight <= 100) ? '400px' : _tinymceHeight+ 'px';
                        
                        tinymce.dom.Event.domLoaded = true;
                        tinymce.baseURL = URL_APP+'assets/custom/addon/plugins/tinymce';
                        tinymce.suffix = ".min";
                        tinymce.init({
                            selector: 'textarea#tempEditor',
                            height: _tinymceHeight,
                            plugins: [
                                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                'searchreplace wordcount visualblocks visualchars code fullscreen',
                                'insertdatetime media nonbreaking save table contextmenu directionality importcss codemirror',
                                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight'
                            ],
                            toolbar1: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                            toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen | code | customEnterSpacer',
                            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt',
                            lineheight_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 1.0 1.15 1.5 2.0 2.5 3.0",
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
                            mentions: {
                                delimiter: '#',
                                delay: 0, 
                                queryBy: 'FIELD_PATH', 
                                source: function (query, process, delimiter) {
                                    $.ajax({
                                        type: "post",
                                        url: "mdtemplate/getAllVariablesByJson",
                                        data: {dataViewId: $("#metaGroupId").val()}, 
                                        dataType: 'json', 
                                        success: function(data){
                                            process(data);
                                        }
                                    });
                                }, 
                                render: function(item) {
                                    return '<li>' +
                                               '<a href="javascript:;">' + item.FIELD_PATH + ' - '+item.META_DATA_NAME+'</a>' +
                                           '</li>';
                                },
                                insert: function(item) {
                                    return '#'+item.field_path+'#';
                                }
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
                                        "Ctrl-S": function(cm) { 
                                            if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
                                                var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
                                                if (!$buttonElement.is(':disabled')) {
                                                    $buttonElement.click();
                                                }
                                            }
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
                                editor.on('init', function() {
                                    $('textarea#tempEditor').prev('.mce-container').find('.mce-edit-area')
                                    .droppable({
                                        drop: function(event, ui) {
                                            tinymce.activeEditor.execCommand('mceInsertContent', false, '#'+ui.draggable.text()+'#');
                                        }
                                    });
                                });
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
                                editor.shortcuts.add('ctrl+s', '', function() { 
                                    if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
                                        var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
                                        if (!$buttonElement.is(':disabled')) {
                                            $buttonElement.click();
                                            return false;
                                        }
                                    }
                                    
                                    if ($('body').find('button.bp-btn-subsave').length > 0 && $('body').find('button.bp-btn-subsave').is(':visible')) {
                                        var $buttonElement = $('body').find('button.bp-btn-subsave:visible:last');
                                        if (!$buttonElement.is(':disabled')) {
                                            $buttonElement.click();
                                        }
                                    }
                                    return false;
                                });
                            },  
                            contextmenu: 'link image inserttable | tableprops cell row column deletetable',
                            plugin_preview_width: 1200,
                            document_base_url: URL_APP, 
                            content_css: [
                                URL_APP+'assets/custom/css/print/tinymce.css', 
                                URL_APP+'assets/custom/webfonts/nextmuseo/font.css'
                            ]
                        });
                    }, 
                    close: function () {
                        tinymce.remove('textarea');
                        $dialog.dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki bp-btn-subsave', click: function() {
                            tinymce.triggerSave();

                            var reportValueHtml = tinymce.get('tempEditor').getContent();
                            var $html = $('<div />', {html: reportValueHtml});
                            var editorElement = $html.find('.tinymce-page-border');
                            var reportValue = '';
                            
                            if (editorElement.length) {
                                reportValue = editorElement.html();
                            } else {
                                reportValue = $html.html();
                            }
                            
                            if (reportValue == '&nbsp;') {
                                reportValue = '';
                            }
                            
                            if ($("form#addMetaSystemForm").find("textarea#htmlContent").length) {
                                $("form#addMetaSystemForm").find("textarea#htmlContent").val(reportValue);
                            } else {
                                $("form#addMetaSystemForm").append('<textarea name="htmlContent" id="htmlContent" class="display-none"></textarea>');
                                $("form#addMetaSystemForm").find("textarea#htmlContent").val(reportValue);
                            }
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
                    }, 
                    "maximize": function() { 
                        var $tmpPageOption = $('#tmpPageOption');
                        var dialogHeight = $dialog.height() - 2;
                        $dialog.find('#childList').css({"height": dialogHeight+'px', "max-height": dialogHeight+'px'});

                        if ($tmpPageOption.length) {
                            $dialog.find('#childConstants').css({"height": (dialogHeight - 190)+'px', "max-height": (dialogHeight - 190)+'px'});
                        } else {
                            $dialog.find('#childConstants').css({"height": dialogHeight+'px', "max-height": dialogHeight+'px'});
                        }
                    }, 
                    "restore": function() { 
                        var $tmpPageOption = $('#tmpPageOption');
                        var dialogHeight = $dialog.height() - 10;
                        $dialog.find('#childList').css({"height": dialogHeight+'px', "max-height": dialogHeight+'px'});

                        if ($tmpPageOption.length) {
                            $dialog.find('#childConstants').css({"height": (dialogHeight - 190)+'px', "max-height": (dialogHeight - 190)+'px'});
                        } else {
                            $dialog.find('#childConstants').css({"height": dialogHeight+'px', "max-height": dialogHeight+'px'});
                        }
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend("maximize");
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }

    function fromEditorHtml(htmlContent) {
        var newHtml = '<div id="editorContent">' + htmlContent + '</div>';
        newDocument = new DOMParser().parseFromString(newHtml, "text/html");
        $(newDocument).find(".tag-meta").each(function() {
            var metaData = $(this).find("span").text();
            $(this).html("#" + metaData + "#");
        });
        $(newDocument).find(".tag-const").each(function() {
            var constValue = $(this).find("span").text();
            $(this).html("*" + constValue + "*");
        });
        $(newDocument).find(".tag-method").each(function() {
            var method = $(this).find("span").text();
            $(this).html("{" + method + "}");
        });
        return newDocument.getElementById("editorContent").innerHTML;
    }
    function toEditorHtml(htmlContent) {
        var newHtml = '<div id="editorContent">' + htmlContent + '</div>';
        newDocument = new DOMParser().parseFromString(newHtml, "text/html");
        $(newDocument).find(".tag-meta").each(function() {
            var metaData = $(this).text().slice(1, -1);
            $(this).html('<span>' + metaData + '</span><a href="#" title="Remove">x</a>');
        });
        $(newDocument).find(".tag-const").each(function() {
            var constValue = $(this).text().slice(1, -1);
            $(this).html('<span>' + constValue + '</span><a href="#" title="Remove">x</a>');
        });
        $(newDocument).find(".tag-method").each(function() {
            var method = $(this).text().slice(1, -1);
            $(this).html('<span>' + method + '</span><a href="#" title="Remove">x</a>');
        });
        return newDocument.getElementById("editorContent").innerHTML;
    }
</script>