<div class="panel panel-default bg-inverse">
    <?php echo Form::hidden(array('name' => 'reportType','id' => 'reportType','value' => 'dataview')); ?>      
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px;" class="left-padding">Тайлангийн нэр:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'reportName',
                            'id' => 'reportName',
                            'class' => 'form-control globeCodeInput'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td class="left-padding">Data View:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="dataViewId" name="dataViewId" type="hidden">
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
            <tr class="system-meta-group-id">
                <td class="left-padding">Group Data View:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="groupDataViewId" name="groupDataViewId" type="hidden" value="">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="" title="" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="" title="" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>      
                </td>
            </tr>
            <tr>
                <td class="left-padding"><label for="isCalcProcess">Бодолттой эсэх:</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isCalcProcess',
                                'id' => 'isCalcProcess',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                    <?php echo Form::hidden(array('name' => 'calcProcessId', 'value' => '1489048875531')); ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Бодолтын процесс:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="calcProcessId" name="calcProcessId" type="hidden">
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
                <td style="width: 170px;" class="left-padding">Page header</td>
                <td colspan="2">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'pageHeader', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                </td>
            </tr>            
            <tr>
                <td style="width: 170px;" class="left-padding">Page header</td>
                <td colspan="2">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'pageHeader', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Report header</td>
                <td colspan="2">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'reportHeader', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                </td>
            </tr>
            <tr class="dataview">
                <td style="width: 170px;" class="left-padding">Report Grouping</td>
                <td colspan="2">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setReportGrouping(this);')); ?>
                    <div id="dialog-report-grouping"></div>
                </td>
            </tr>
            <tr class="dataview">
                <td style="width: 170px;" class="left-padding">Report detail</td>
                <td colspan="2">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'reportDetail', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Report footer</td>
                <td colspan="2">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'reportFooter', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Page footer</td>
                <td colspan="2">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'pageFooter', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Page size</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'pageSize',
                            'id' => 'pageSize',
                            'data' => array(
                                array(
                                    'id' => 'a4',
                                    'name' => 'A4'
                                ),
                                array(
                                    'id' => 'a3',
                                    'name' => 'A3'
                                ),
                                array(
                                    'id' => 'custom',
                                    'name' => 'Custom'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => 'a4',
                            'class' => 'form-control select2', 
                            'text' => 'notext'
                        )
                    );
                    ?>     
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Page orientation</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'pageOrientation',
                            'id' => 'pageOrientation',
                            'data' => array(
                                array(
                                    'id' => 'portrait',
                                    'name' => 'Босоо'
                                ),
                                array(
                                    'id' => 'landscape',
                                    'name' => 'Хэвтээ'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'class' => 'form-control select2',
                            'text' => 'notext'
                        )
                    );
                    ?>     
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin top</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginTop',
                            'id' => 'pageMarginTop',
                            'class' => 'form-control', 
                            'value' => '40px'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin left</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginLeft',
                            'id' => 'pageMarginLeft',
                            'class' => 'form-control', 
                            'value' => '90px'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin right</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginRight',
                            'id' => 'pageMarginRight',
                            'class' => 'form-control', 
                            'value' => '60px'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Margin bottom</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageMarginBottom',
                            'id' => 'pageMarginBottom',
                            'class' => 'form-control', 
                            'value' => '90px'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr class="statement-page-width">
                <td style="width: 170px;" class="left-padding">Page width</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageWidth',
                            'id' => 'pageWidth',
                            'class' => 'form-control'
                        )
                    );
                    ?>  
                </td>
            </tr>            
            <tr class="statement-page-height">
                <td style="width: 170px;" class="left-padding">Page height</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'pageHeight',
                            'id' => 'pageHeight',
                            'class' => 'form-control'
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Font family</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'fontFamily',
                            'id' => 'fontFamily',
                            'data' => array(
                                array(
                                    'id' => 'arial, helvetica, sans-serif',
                                    'name' => 'Arial'
                                ),
                                array(
                                    'id' => "'times new roman', times, serif",
                                    'name' => 'Times new roman'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => 'Arial, Helvetica, sans-serif',
                            'class' => 'form-control select2', 
                            'text' => 'notext'
                        )
                    );
                    ?>     
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding">Render Type</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'renderType',
                            'id' => 'renderType',
                            'data' => array(
                                array(
                                    'id' => 'list',
                                    'name' => 'List'
                                ),
                                array(
                                    'id' => 'card',
                                    'name' => 'Card'
                                ), 
                                array(
                                    'id' => 'notloop',
                                    'name' => 'Not loop'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'class' => 'form-control select2',
                            'text' => 'notext'
                        )
                    );
                    ?>     
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isHdrRepeatPage">Is Header Repeat Page</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isHdrRepeatPage',
                            'id' => 'isHdrRepeatPage',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isNotPageBreak">Not PageBreak</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isNotPageBreak',
                            'id' => 'isNotPageBreak',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isArchive">Архивлах эсэх</label></td>
                <td colspan="2"> 
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
                <td style="width: 170px;" class="left-padding"><label for="isBlank">Is blank</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isBlank',
                            'id' => 'isBlank',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isShowDvBtn">Is show dv button</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isShowDvBtn',
                            'id' => 'isShowDvBtn',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isAutoFilter">Is auto filter</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isAutoFilter',
                            'id' => 'isAutoFilter',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isExportNoFooter">Is export no footer</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isExportNoFooter',
                            'id' => 'isExportNoFooter',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isGroupMerge">Груп нийлүүлэх эсэх</label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isGroupMerge',
                            'id' => 'isGroupMerge',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px;" class="left-padding"><label for="isTimetable"><?php echo Lang::line('isTimetable') ?></label></td>
                <td colspan="2"> 
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isTimetable',
                            'id' => 'isTimetable',
                            'value' => '1'
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {
        visibleCheck();
    });
    function visibleCheck() {
        var pageSize = $("#pageSize");
        if (pageSize.val() === 'custom') {
            $(".statement-page-width, .statement-page-height").show();
        } else {
            $(".statement-page-width, .statement-page-height").hide();
        }
        pageSize.on("change", function() {
            if ($(this).val() == 'custom') {
                $(".statement-page-width, .statement-page-height").show();
            } else {
                $(".statement-page-width, .statement-page-height").hide();
            }
        });
    }
    
    function setTmceReportEditor(elem) {
        var textAreaName = $(elem).attr('name');
        
        var previewWidth;
        var pageSize = $("#pageSize").val();
        var pageOrientation = $("#pageOrientation").val();
        var pageMarginLeft = $("#pageMarginLeft").val();
        var pageMarginRight = $("#pageMarginRight").val();
        
        if (pageSize === 'a4') {
            if (pageOrientation === 'portrait') {
                var width = parseFloat(1000 + 15);
            } else {
                var width = parseFloat(1210 + 15);
            }
            
            pageMarginLeft = parseFloat(pageMarginLeft.replace(/\D/g, ''));
            pageMarginRight = parseFloat(pageMarginRight.replace(/\D/g, ''));
            
            previewWidth = width - pageMarginLeft - pageMarginRight;
        }
        
        var $dialogName = 'dialog-tmceTemplateEditor';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $("#"+$dialogName);

        var htmlContent = "";
        if ($("form#addMetaSystemForm").find("textarea#" + textAreaName).length) {
            htmlContent = base64_encode($("form#addMetaSystemForm").find("textarea#" + textAreaName).val());
        }
        
        $.ajax({
            type: 'post',
            url: 'mdmetadata/setTmceStatementEditor',
            data: {
                dialogName: $dialogName, 
                htmlContent: htmlContent, 
                editorName: textAreaName, 
                reportType:$('#reportType').val(), 
                metaDataId: $('#dataViewId').val()
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    appendTo: "form#addMetaSystemForm",
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
                            table_class_list: [
                                {title: 'None', value: ''}, 
                                {title: 'No border', value: 'pf-report-table-none'}, 
                                {title: 'Dotted', value: 'pf-report-table-dotted'}, 
                                {title: 'Dashed', value: 'pf-report-table-dashed'},  
                                {title: 'Solid', value: 'pf-report-table-solid'}
                            ], 
                            paste_word_valid_elements: 'b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font,page',
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
                            var reportValue = tinymce.get('tempEditor').getContent();
                            if ($("form#addMetaSystemForm").find("textarea#" + textAreaName).length) {
                                $("form#addMetaSystemForm").find("textarea#" + textAreaName).val(reportValue);
                            } else {
                                $("form#addMetaSystemForm").append('<textarea name="' + textAreaName + '" id="' + textAreaName + '" class="display-none"></textarea>');
                                $("form#addMetaSystemForm").find("textarea#" + textAreaName).val(reportValue);
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
                    "maximize" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find("div.report-tags").css("height", dialogHeight+'px');
                        $dialog.find("div.report-tags").css("max-height", dialogHeight+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find("div.report-tags").css("height", dialogHeight+'px');
                        $dialog.find("div.report-tags").css("max-height", dialogHeight+'px');
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
            $(this).html("#" + constValue + "#");
        });
        return newDocument.getElementById("editorContent").innerHTML;
    }
    function toEditorHtml(htmlContent) {
        var newHtml = '<div id="editorContent">' + htmlContent + '</div>';
        newDocument = new DOMParser().parseFromString(newHtml, "text/html");
        $(newDocument).find(".tag-meta").each(function() {
            var metaData = $(this).text().slice(1, -1);
            $(this).html('<span>' + metaData + '</span>' + '<a href="#" title="Remove">x</a>');
        });
        $(newDocument).find(".tag-const").each(function() {
            var constValue = $(this).text().slice(1, -1);
            $(this).html('<span>' + constValue + '</span>' + '<a href="#" title="Remove">x</a>');
        });
        return newDocument.getElementById("editorContent").innerHTML;
    }
    function setReportGrouping(elem) {
        var $dialogName = 'dialog-report-grouping';

        if ($("#" + $dialogName).children().length > 0) {
            $("#" + $dialogName).dialog({
                appendTo: "form#addMetaSystemForm",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Report Grouping',
                width: 950,
                minWidth: 950,
                height: "auto",
                modal: true,
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                        $("#" + $dialogName).dialog('close');
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    }},
                    {text: "<?php echo $this->lang->line('META_00002'); ?>", class: 'btn btn-sm red', click: function () {
                        $("#" + $dialogName).empty().dialog('close');
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
            $("#" + $dialogName).dialog('open');
        } else {
            $.ajax({
                type: 'post',
                url: 'mdmeta/setStatementReportGrouping',
                data: {
                    metaDataId: null,
                    dataViewId: $("input#dataViewId").val(),
                    editMode: false
                },
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {
                    $("#" + $dialogName).empty().html(data.html);
                    $("#" + $dialogName).dialog({
                        appendTo: "form#addMetaSystemForm",
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 950,
                        minWidth: 950,
                        height: "auto",
                        modal: true,
                        buttons: [
                            {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }},
                            {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                                $("#" + $dialogName).empty().dialog('close');
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
                    $("#" + $dialogName).dialog('open');
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initNumber($("#" + $dialogName));
            });
        }
    }
</script>