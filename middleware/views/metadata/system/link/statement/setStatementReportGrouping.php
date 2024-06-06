<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="table-scrollable">
            <table class="table report-grouping-configs">
                <thead>
                    <tr>
                        <th>Field path</th>
                        <th>Дараалал</th>
                        <th class="text-center" style="width: 10%">Header / Bg Color</th>
                        <th class="text-center" style="width: 10%">Footer / Bg Color</th>
                        <th class="text-center" style="width: 5%">Is default</th>
                        <th class="text-center" style="width: 5%">Is user option</th>
                        <th style="width: 10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($this->groupingList)) {
                        foreach ($this->groupingList as $row) {
                            
                            $isDefault = $isUserOption = '';
                            
                            if ($row['IS_USER_OPTION'] == '' || $row['IS_USER_OPTION'] == '0') {
                                $isDefault = 1;
                                $isUserOption = '';
                            } elseif ($row['IS_USER_OPTION'] == '1') {
                                $isDefault = '';
                                $isUserOption = 1;
                            } elseif ($row['IS_USER_OPTION'] == '2') {
                                $isDefault = 1;
                                $isUserOption = 1;
                            }
                    ?>
                    <tr>
                        <td>
                            <?php 
                            echo Form::select(
                                array(
                                    'name' => 'groupFieldPath[]',
                                    'class' => 'form-control form-control-sm', 
                                    'data' => $this->paramList,
                                    'op_value' => 'FIELD_NAME',
                                    'op_text' => 'FIELD_NAME| |-| |META_DATA_NAME', 
                                    'value' => $row['GROUP_FIELD_PATH']
                                )
                            ); 
                            ?>
                        </td>
                        <td>
                            <?php echo Form::text(array('name' => 'groupOrderNum[]','class' => 'form-control form-control-sm longInit', 'value' => $row['GROUP_ORDER'])); ?>
                        </td>
                        <td class="middle text-center">
                            <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'groupHeader', 'value' => '...', 'onclick' => 'setTmceReportGroupingEditor(this);')); ?>
                            <textarea name="groupHeader[]" id="groupHeader" class="display-none"><?php echo $row['GROUP_HEADER']; ?></textarea>
                            <?php echo Form::text(array('name' => 'groupHdrBgColor[]','class' => 'form-control form-control-sm gr-colorpicker d-inline', 'value' => $row['HEADER_BG_COLOR'], 'style'=>'width:60px')); ?>
                        </td>
                        <td class="middle text-center">
                            <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'groupFooter', 'value' => '...', 'onclick' => 'setTmceReportGroupingEditor(this);')); ?>
                            <textarea name="groupFooter[]" id="groupFooter" class="display-none"><?php echo $row['GROUP_FOOTER']; ?></textarea>
                            <?php echo Form::text(array('name' => 'groupFtrBgColor[]','class' => 'form-control form-control-sm gr-colorpicker d-inline', 'value' => $row['FOOTER_BG_COLOR'], 'style'=>'width:60px')); ?>
                        </td>
                        <td class="middle text-center">
                            <?php 
                            echo Form::checkbox(array('class' => 'groupIsDefault', 'value' => 1, 'saved_val' => $isDefault)); 
                            echo Form::hidden(array('name' => 'groupIsDefault[]', 'value' => $isDefault));
                            ?>
                        </td>
                        <td class="middle text-center">
                            <?php 
                            echo Form::checkbox(array('class' => 'groupIsUserOption', 'value' => 1, 'saved_val' => $isUserOption)); 
                            echo Form::hidden(array('name' => 'groupIsUserOption[]', 'value' => $isUserOption));
                            ?>
                        </td>
                        <td class="middle text-center">
                            <a href="javascript:;" class="btn red btn-xs" onclick="reportGroupingRemoveRow(this);">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <a href="javascript:;" class="btn green btn-xs" onclick="addStatementReportGrouping(this);">
                                <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>     
    </div>
</div>
<div id="dialog-editGroupEditor" class="display-none"></div>
<?php echo Form::hidden(array('name' => 'reportGroupingLoad', 'value' => '1')); ?>

<script type="text/javascript">
$(function(){
    $('.report-grouping-configs').on('click', 'input.groupIsDefault', function(){
        var $this = $(this);
        var $row = $this.closest('tr');
        if ($this.is(':checked')) {
            $row.find('input[name="groupIsDefault[]"]').val('1');
        } else {
            $row.find('input[name="groupIsDefault[]"]').val('0');
        }
    });
    $('.report-grouping-configs').on('click', 'input.groupIsUserOption', function(){
        var $this = $(this);
        var $row = $this.closest('tr');
        if ($this.is(':checked')) {
            $row.find('input[name="groupIsUserOption[]"]').val('1');
        } else {
            $row.find('input[name="groupIsUserOption[]"]').val('0');
        }
    }); 
});    
function addStatementReportGrouping(elem){
    var $this = $(elem);
    var $parentTbl = $this.closest('table');
    $parentTbl.find('tbody').append('<tr>'+
        '<td><?php echo Form::select(array('name' => 'groupFieldPath[]','class' => 'form-control form-control-sm', 'data'=>$this->paramList,'op_value'=>'FIELD_NAME','op_text'=>'FIELD_NAME| |-| |META_DATA_NAME')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'groupOrderNum[]','class' => 'form-control form-control-sm longInit')); ?></td>'+
        '<td class="middle text-center"><?php echo  Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'groupHeader', 'value' => '...', 'onclick' => 'setTmceReportGroupingEditor(this);'));?><textarea name="groupHeader[]" id="groupHeader" class="display-none"></textarea></td>'+
        '<td class="middle text-center"><?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'groupFooter', 'value' => '...', 'onclick' => 'setTmceReportGroupingEditor(this);')); ?><textarea name="groupFooter[]" id="groupFooter" class="display-none"></textarea></td>'+
        '<td class="middle text-center"><?php echo Form::checkbox(array('class' => 'groupIsDefault', 'value' => 1, 'checked' => 'checked')); ?><input type="hidden" name="groupIsDefault[]"></td>'+
        '<td class="middle text-center"><?php echo Form::checkbox(array('class' => 'groupIsUserOption', 'value' => 1)); ?><input type="hidden" name="groupIsUserOption[]"></td>'+
        '<td class="middle text-center"><a href="javascript:;" class="btn red btn-xs" onclick="reportGroupingRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
    '</tr>');
    Core.initUniform($parentTbl.find('tbody > tr:last'));
}    
function reportGroupingRemoveRow(elem){
    var $parentRow = $(elem).closest('tr');
    $parentRow.remove();
}
function setTmceReportGroupingEditor(elem) {
    var textAreaName = $(elem).attr('name');

    var previewWidth, defaultTableWidth, pageInnerHeight;
    var pageSize = $("#pageSize").val();
    var pageOrientation = $("#pageOrientation").val();
    
    var pageMarginLeftLower = $("#pageMarginLeft").val().toLowerCase();
    var pageMarginRightLower = $("#pageMarginRight").val().toLowerCase();
    var pageMarginTopLower = $("#pageMarginTop").val().toLowerCase();
    var pageMarginBottomLower = $("#pageMarginBottom").val().toLowerCase();

    var pageMarginLeft = pageMarginLeftLower;
    var pageMarginRight = pageMarginRightLower;
    var pageMarginTop = pageMarginTopLower;
    var pageMarginBottom = pageMarginBottomLower;
    
    if (pageMarginLeftLower.indexOf('cm') !== -1) {
        pageMarginLeft = parseFloat(pageMarginLeftLower.replace('cm', '')) * 37.795275591;
    }

    if (pageMarginLeftLower.indexOf('mm') !== -1) {
        pageMarginLeft = parseFloat(pageMarginLeftLower.replace('mm', '')) * 3.7795275591;
    }

    if (pageMarginLeftLower.indexOf('px') !== -1) {
        pageMarginLeft = parseFloat(pageMarginLeftLower.replace('px', ''));
    }

    if (pageMarginRightLower.indexOf('cm') !== -1) {
        pageMarginRight = parseFloat(pageMarginRightLower.replace('cm', '')) * 37.795275591;
    }

    if (pageMarginRightLower.indexOf('mm') !== -1) {
        pageMarginRight = parseFloat(pageMarginRightLower.replace('mm', '')) * 3.7795275591;
    }

    if (pageMarginRightLower.indexOf('px') !== -1) {
        pageMarginRight = parseFloat(pageMarginRightLower.replace('px', ''));
    }

    if (pageMarginTopLower.indexOf('cm') !== -1) {
        pageMarginTop = parseFloat(pageMarginTopLower.replace('cm', '')) * 37.795275591;
    }

    if (pageMarginTopLower.indexOf('mm') !== -1) {
        pageMarginTop = parseFloat(pageMarginTopLower.replace('mm', '')) * 3.7795275591;
    }

    if (pageMarginTopLower.indexOf('px') !== -1) {
        pageMarginTop = parseFloat(pageMarginTopLower.replace('px', ''));
    }

    if (pageMarginBottomLower.indexOf('cm') !== -1) {
        pageMarginBottom = parseFloat(pageMarginBottomLower.replace('cm', '')) * 37.795275591;
    }

    if (pageMarginBottomLower.indexOf('mm') !== -1) {
        pageMarginBottom = parseFloat(pageMarginBottomLower.replace('mm', '')) * 3.7795275591;
    }

    if (pageMarginBottomLower.indexOf('px') !== -1) {
        pageMarginBottom = parseFloat(pageMarginBottomLower.replace('px', ''));
    }

    if (pageSize === 'a4') {
            
        var marginLeft = pageMarginLeft;
        var marginRight = pageMarginRight;

        if (pageOrientation === 'portrait') {
            var width = parseFloat(1000 + 15);
            defaultTableWidth = 1000 - marginLeft - marginRight;
            pageInnerHeight = 1310 - pageMarginTop - pageMarginBottom;
        } else {
            var width = parseFloat(1210 + 15);
            defaultTableWidth = 1210 - marginLeft - marginRight;
            pageInnerHeight = 900 - pageMarginTop - pageMarginBottom;
        }

        previewWidth = width - marginLeft - marginRight;
    }

    var $dialogName = 'dialog-editGroupEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    var htmlContent = '';
    if ($(elem).closest('td').find("textarea#" + textAreaName).length) {
        htmlContent = base64_encode($(elem).closest('td').find("textarea#" + textAreaName).val());
    }
    
    $.ajax({
        type: 'post',
        url: 'mdmetadata/setTmceStatementEditor',
        data: {
            dialogName: $dialogName, 
            htmlContent: htmlContent, 
            editorName: textAreaName, 
            reportType: $('#reportType').val(), 
            metaDataId: '<?php echo $this->dataViewId; ?>', 
            pageSize: pageSize,
            pageOrientation: pageOrientation, 
            pageMarginLeft: pageMarginLeft, 
            pageMarginRight: pageMarginRight, 
            pageMarginTop: pageMarginTop, 
            pageMarginBottom: pageMarginBottom, 
            pageInnerHeight: pageInnerHeight
        },
        dataType: "json",
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                appendTo: "form#<?php echo $this->formId; ?>",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                minWidth: 1200,
                height: 600,
                modal: false,
                open: function(){
                    var _tinymceHeight = $(window).height() - 250;
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
                        toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen | code',
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
                            "<?php echo Mdcommon::addCustomFonts('editorFamily'); ?>",
                        table_default_styles: {
                            width: defaultTableWidth + 'px'
                        }, 
                        table_class_list: [
                            {title: 'None', value: ''}, 
                            {title: 'No border', value: 'pf-report-table-none'}, 
                            {title: 'Dotted', value: 'pf-report-table-dotted'}, 
                            {title: 'Dashed', value: 'pf-report-table-dashed'},  
                            {title: 'Solid', value: 'pf-report-table-solid'}
                        ], 
                        object_resizing: 'img',
                        paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font,page",
                        mentions: {
                            delimiter: '#',
                            delay: 0, 
                            queryBy: 'META_DATA_CODE', 
                            source: function (query, process, delimiter) {
                                $.ajax({
                                    type: "post",
                                    url: "mdstatement/getAllVariablesByJson",
                                    data: {reportType: $("#reportType").val(), dataViewId: $("#dataViewId").val()}, 
                                    dataType: 'json', 
                                    success: function(data){
                                        process(data);
                                    }
                                });
                            }, 
                            render: function(item) {
                                return '<li>' +
                                           '<a href="javascript:;">' + item.META_DATA_CODE + ' - '+item.META_DATA_NAME+'</a>' +
                                       '</li>';
                            },
                            insert: function(item) {
                                return '#'+item.meta_data_code+'#';
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
                        plugin_preview_width: previewWidth,
                        document_base_url: URL_APP, 
                        content_css: [
                            URL_APP+'assets/custom/css/print/tinymce_statement.css', 
                            <?php echo Mdcommon::addCustomFonts('jsCommaPath'); ?>
                        ]
                    });
                }, 
                close: function () {
                    tinymce.remove('textarea');
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                        tinymce.triggerSave();
                        
                        var reportValueHtml = tinymce.get('tempEditor').getContent();
                        var $html = $('<div />', {html: reportValueHtml});

                        var reportValue = $html.find('.tinymce-page-border').html();
                        
                        if (reportValue == '&nbsp;') {
                            reportValue = '';
                        }
                            
                        if ($(elem).closest('td').find("textarea#" + textAreaName).length) {
                            $(elem).closest('td').find("textarea#" + textAreaName).val(reportValue);
                        } else {
                            $(elem).closest('td').append('<textarea name="' + textAreaName + '[]" id="' + textAreaName + '" class="display-none"></textarea>');
                            $(elem).closest('td').find("textarea#" + textAreaName).val(reportValue);
                        }
                            
                        $dialog.dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
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
                    var dialogHeight = $dialog.height();
                    $dialog.find("div.report-tags").css("height", dialogHeight+'px');
                    $dialog.find("div.report-tags").css("max-height", dialogHeight+'px');
                }, 
                "restore": function() { 
                    var dialogHeight = $dialog.height();
                    $dialog.find("div.report-tags").css("height", dialogHeight+'px');
                    $dialog.find("div.report-tags").css("max-height", dialogHeight+'px');
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend('maximize');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }); 
}
</script>