<div id="window-word-template-<?php echo $this->uniqId ?>">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-template-form', 'method' => 'post')); ?>
        <div class="col-md-12">
            <div class="col-md-6 xs-form">
                <div class="form-group row fom-row">
                    <div class="col-md-2">
                        <span class="btn btn-xs green template-preview-btn hidden float-right">Темплейт харах</span>
                    </div>
                </div>
            </div>
            <div class="clearfix w-100"></div>    
            <div class="row">
                <div class="col-md-12 center-sidebar mt10">
                    <div class="bp-template-wrap">
                        <div class="bp-template-table">
                            <div class="bp-template-table-row">
                                <div class="bp-template-table-cell-left template-preview-wrap">
                                </div>
                                <div class="bp-template-table-cell-right" style="background-color: #fff;position: relative;">
                                    <div class="bp-template-table-cell-right-inside aa"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="templateId" value="<?php echo $this->templateId; ?>">
    <?php echo Form::close(); ?>
</div>

<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>

<script type="text/javascript">
var savedTaxonomyConfig = <?php echo $this->getTaxonomyConfig ? json_encode($this->getTaxonomyConfig) : json_encode(array()); ?>;
var savedTaxonomyWidget = <?php echo $this->getTaxonomyWidget ? json_encode($this->getTaxonomyWidget) : json_encode(array()); ?>;

$(function() {
    $('input[name="templateWidget[]"]').on('change', function() {
        var _this = $(this);
        
        if (_this.is(':checked')) {
            _this.closest('label').children(':last-child').val(_this.val());
        } else {
            _this.closest('label').children(':last-child').val('');
        }
    });
    
    $('.template-preview-btn', '#window-word-template-<?php echo $this->uniqId ?>').on('click', function(){
        $.ajax({
            type: 'post',
            url: 'mddoc/templatePreview',
            data: {
                templateId: '<?php echo $this->templateId ?>'
            },
            dataType: 'json',
            beforeSend: function() {
                setTimeout(function() {
                    Core.blockUI({
                        message: 'Темплейт харахаар дуудаж байна, Түр хүлээнэ үү...',
                        boxed: true
                    });
                }, 0);
            },
            success: function(data) {
                PNotify.removeAll();

                if (typeof data.Html !== 'undefined') {
                    var dynamicHeight = $(window).height() - 198;
                    $('.template-preview-wrap', '#window-word-template-<?php echo $this->uniqId ?>').attr('style', "background-color: rgba(236, 236, 236, 0.73); border: 1px dashed #ccc;").html('<div style="overflow-y: auto; overflow-y: auto; height: '+dynamicHeight+'px !important;"><span style="position: absolute;font-size: 32px;margin-top: 15px;color: #d4d4d4; z-index: -1;">Template Preview</span>' + data.Html + '</div>').find('.meta-toolbar').remove();
                    $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').css({
                        'overflow-y': 'auto',
                        'overflow-x': 'hidden',
                        'height': dynamicHeight + 'px'
                    });
                    
                    $('.detail-template-body', '#window-word-template-<?php echo $this->uniqId ?>').each(function(){
                        var _this = $(this);
                        if (!_this.parent().hasClass('detail-template-body')) {
                            $(_this).parent().css({'border': '2px dotted rgb(62, 183, 172)', 'cursor': 'pointer'});
                        }
                        else {
                            $(_this).css({'background-color': 'rgb(187, 234, 230, 0.7)', 'cursor': 'pointer'});
                        }
                    })
                }
                else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    $('.template-preview-wrap', '#window-word-template-<?php echo $this->uniqId ?>').removeAttr('style').html('');
                }
                Core.unblockUI();
            },
            error: function() {
                alert("Template Preview Error");
            }
        });
    });
    
    $('.template-preview-btn', '#window-word-template-<?php echo $this->uniqId ?>').trigger('click');
    
    $('#window-word-template-<?php echo $this->uniqId ?>').on('click', 'p', function() {
        var _this = $(this);
        
        if(_this.children('.detail-template-body').length) {
            _this = _this.children('.detail-template-body');
            
            var groupPath = _this.attr('title'), 
                groupPathSelector = groupPath.replace('&', '\\&'), 
                groupPathReal = _this.attr('data-path-realname'),
                savedExpression = '',
                savedExpressionDtl = '',
                savedExpressionDtl_2 = '',
                savedTaxoConfigId = '',
                savedAddBtn = ' checked',
                savedAddFollowBtn = ' checked',
                savedAddIsMultiBtn = '',
                savedAddIsHighlightBtn = '',
                savedAddIsCopyBtn = '',
                savedAddPictureBtn = '';
                
            if (savedTaxonomyConfig.hasOwnProperty(groupPathReal)) {
                savedExpression = savedTaxonomyConfig[groupPathReal].row.EXPRESSION != null ? savedTaxonomyConfig[groupPathReal].row.EXPRESSION : '';
                savedExpressionDtl = savedTaxonomyConfig[groupPathReal].row.EXPRESSION_DTL != null ? savedTaxonomyConfig[groupPathReal].row.EXPRESSION_DTL : '';
                savedExpressionDtl_2 = savedTaxonomyConfig[groupPathReal].row.EXPRESSION_DTL_KEY != null ? savedTaxonomyConfig[groupPathReal].row.EXPRESSION_DTL_KEY : '';
                savedTaxoConfigId = savedTaxonomyConfig[groupPathReal].row.ID;
                savedAddBtn = savedTaxonomyConfig[groupPathReal].row.IS_ADD_BUTTON == '1' ? ' checked' : '';
                savedAddFollowBtn = savedTaxonomyConfig[groupPathReal].row.IS_ADD_FOLLOW == '1' ? ' checked' : '';
                savedAddPictureBtn = savedTaxonomyConfig[groupPathReal].row.IS_PICTURE == '1' ? ' checked' : '';
                savedAddIsMultiBtn = savedTaxonomyConfig[groupPathReal].row.IS_MULTI == '1' ? ' checked' : '';
                savedAddIsHighlightBtn = savedTaxonomyConfig[groupPathReal].row.IS_HIGHLIGHT == '1' ? ' checked' : '';
                savedAddIsCopyBtn = savedTaxonomyConfig[groupPathReal].row.IS_COPY_BUTTON == '1' ? ' checked' : '';
            }
            
            $('.detail-template-body', '#window-word-template-<?php echo $this->uniqId ?>').parent().removeAttr('style');
            $('.hiddenTemplateInputs', '#window-word-template-<?php echo $this->uniqId ?>').removeClass('hidden');
            $('.detail-template-body', '#window-word-template-<?php echo $this->uniqId ?>').parent().css({'border': '2px dotted rgb(62, 183, 172)', 'cursor': 'pointer'});
            _this.parent().css({'border': '2px dotted #008c00', 'background-color': 'rgba(0, 140, 0, 0.17)'});

            //var getProccesField = _this.html().match(/(\#)(.*?)(\#)/g);
            var getProccesField = _this.children('.metagroup-fields-list').html().split(' ');
            
            $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div').hide();
            if ($('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div.'+groupPathSelector).length === 0) {
                $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').append('<div class="'+groupPath+'"></div>');
            }

            if ($('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div.'+groupPathSelector).html().length === 0) {
                var $tagId = _this.attr('data-template-tagid');

                var $taxConfigHtml = '';
                
                $taxConfigHtml += '<table class="w-100 pull-left">';
                    $taxConfigHtml += '<tbody>';

                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateTaxonamyId">Taxonamy сонгох</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<select id="bpTemplateTaxonamyId" class="select2 form-control form-control-sm bpTemplateGroupPath" name="bpTemplateTaxonamyId-'+groupPath+'">';
                                $taxConfigHtml += '<option value="">- Сонгох -</option>';
                                <?php foreach($this->taxonomyList as $row) {  ?>
                                    $taxConfigHtml += '<option value="<?php echo $row['ID']; ?>"><?php echo $row['TAG']; ?></option>';
                                <?php } ?>
                                $taxConfigHtml += '</select>';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';
                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateTaxonamyId">Виджет сонгох</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<select id="bpTemplateWidgetCode" class="select2 form-control form-control-sm bpTemplateWidgetCode" name="bpTemplateWidgetCode-'+groupPath+'"></select>';
                                $taxConfigHtml += '<input type="hidden" placeholder="Widget Field" name="bpTemplateGroups[]" value="'+groupPathReal+'" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';
                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateTaxonamyExpression">Taxonamy expression</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'id' => 'bpTemplateTaxonamyExpression', 'name' => 'pageHeader', 'value' => '...', 'onclick' => 'taxonamyExpressionCode(this);')); ?>';
                                $taxConfigHtml += '<input type="hidden" placeholder="" name="bpTemplateTaxonamyExpression[]" value="'+savedExpression+'" />';
                                $taxConfigHtml += '<input type="hidden" name="bpTemplateTaxonomyConfig-'+groupPath+'" value="'+savedTaxoConfigId+'" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';

                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateTaxonamyExpressionDtl">Taxonamy expression DTL</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<?php echo Form::button(array('class' => 'btn btn-sm purple-plum',  'id' => 'bpTemplateTaxonamyExpressionDtl', 'name' => 'pageHeader', 'value' => '...', 'onclick' => 'taxonamyExpressionDtlCode(this);')); ?>';
                                $taxConfigHtml += '<input type="hidden" placeholder="" name="bpTemplateTaxonamyExpressionDtl[]" value="'+savedExpressionDtl+'" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';
                        
                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateTaxonamyExpressionDtl_1">Taxonamy expression Key</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<?php echo Form::button(array('class' => 'btn btn-sm purple-plum',  'id' => 'bpTemplateTaxonamyExpressionDtl_1', 'name' => 'pageHeader', 'value' => '...', 'onclick' => 'taxonamyExpressionDtl_1Code(this);')); ?>';
                                $taxConfigHtml += '<input type="hidden" placeholder="" name="bpTemplateTaxonamyExpressionDtl_1[]" value="'+savedExpressionDtl_2+'" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';

                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateIsAddBtn-'+groupPath+'">Нэмэх товч харуулах</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<input type="checkbox" placeholder=""'+savedAddBtn+' name="bpTemplateIsAddBtn-'+groupPath+'" id="bpTemplateIsAddBtn-'+groupPath+'" value="1" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';

                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateIsAddFollowBtn-'+groupPath+'">Дагаж нэмэх</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<input type="checkbox" placeholder=""'+savedAddFollowBtn+' name="bpTemplateIsAddFollowBtn-'+groupPath+'" id="bpTemplateIsAddFollowBtn-'+groupPath+'"  value="1" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';

                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateIsPictureBtn-'+groupPath+'">Зураг харуулах</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<input type="checkbox" placeholder=""'+savedAddPictureBtn+' name="bpTemplateIsPictureBtn-'+groupPath+'" id="bpTemplateIsPictureBtn-'+groupPath+'"  value="1" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';
                        
                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateIsMultiBtn-'+groupPath+'">Mулть сонголттой эсэх</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<input type="checkbox" placeholder=""'+savedAddIsMultiBtn+' name="bpTemplateIsMultiBtn-'+groupPath+'" id="bpTemplateIsMultiBtn-'+groupPath+'"  value="1" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';
                        
                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateIsHighlightBtn-'+groupPath+'">Тодотгох эсэх</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<input type="checkbox" placeholder=""'+savedAddIsHighlightBtn+' name="bpTemplateIsHighlightBtn-'+groupPath+'" id="bpTemplateIsHighlightBtn-'+groupPath+'"  value="1" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';
                        
                        $taxConfigHtml += '<tr>';
                            $taxConfigHtml += '<td class="w-50"><label for="bpTemplateIsCopyBtn-'+groupPath+'">Хуулах товч</label></td>';
                            $taxConfigHtml += '<td>';
                                $taxConfigHtml += '<input type="checkbox" placeholder=""'+savedAddIsCopyBtn+' name="bpTemplateIsCopyBtn-'+groupPath+'" id="bpTemplateIsCopyBtn-'+groupPath+'"  value="1" />';
                            $taxConfigHtml += '</td>';
                        $taxConfigHtml += '</tr>';
                        
                        

                    $taxConfigHtml += '</tbody>';
                $taxConfigHtml += '</table>';
                
                $taxConfigHtml += '<hr class="w-100 pull-left" />';

                $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div.'+groupPathSelector).append($taxConfigHtml);
                        
                generateSelectCombo(groupPathSelector, groupPathReal);
                $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div.'+groupPathSelector).find('select#bpTemplateTaxonamyId').select2('val', $tagId);
                
                if (savedTaxonomyConfig.hasOwnProperty(groupPathReal)) {
                    if (savedTaxonomyConfig[groupPathReal].row.TAXONOMY_ID != null || savedTaxonomyConfig[groupPathReal].row.TAXONOMY_ID != '') {
                        $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div.'+groupPathSelector).find('select#bpTemplateTaxonamyId').select2('val', savedTaxonomyConfig[groupPathReal].row.TAXONOMY_ID);
                    }
                }
            
                var metaCode = '';
                var $table = '';
                $table += '<table class="w-100 pull-left">';
                    $table += '<tbody>';

                    for (var i = 0; i < getProccesField.length; i++) {
                        metaCode = getProccesField[i].replace(/#/g, '');
                        var metaCodeSavedExp = '', metaCodeSavedId = '';
                        
                        if (savedTaxonomyConfig.hasOwnProperty(groupPathReal)) {
                            if (savedTaxonomyWidget.hasOwnProperty(savedTaxonomyConfig[groupPathReal].row.ID)) {
                                for(var iii = 0; iii < savedTaxonomyWidget[savedTaxonomyConfig[groupPathReal].row.ID].rows.length; iii++) {
                                    if (savedTaxonomyWidget[savedTaxonomyConfig[groupPathReal].row.ID].rows[iii].FIELD == metaCode) {
                                        metaCodeSavedExp = savedTaxonomyWidget[savedTaxonomyConfig[groupPathReal].row.ID].rows[iii].EXPRESSION;
                                        metaCodeSavedId = savedTaxonomyWidget[savedTaxonomyConfig[groupPathReal].row.ID].rows[iii].ID;
                                    }
                                }
                            }
                        }

                        $table += '<tr>';
                            $table += '<td class="w-50"><label for="'+ metaCode +'">'+ metaCode +'</label></td>';
                            $table += '<td>';
                                $table += '<input type="text" id="'+ metaCode +'" placeholder="Expression" name="expression-'+groupPath+'[]" value="'+metaCodeSavedExp+'" class="form-control-sm form-control" />';
                                $table += '<input type="hidden" placeholder="" name="metacode-'+groupPath+'[]" value="'+metaCode+'" />';
                                $table += '<input type="hidden" placeholder="" name="metacodeTaxonomyWidgetId-'+groupPath+'[]" value="'+metaCodeSavedId+'" />';
                            $table += '</td>';
                        $table += '</tr>';

                    }

                    $table += '</tbody>';
                $table += '</table>';
                $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div.'+groupPathSelector).append($table);
            } 
            else {
                $('.bp-template-table-cell-right-inside', '#window-word-template-<?php echo $this->uniqId ?>').children('div.'+groupPathSelector).show();
            }
        }
    });
});

function taxonamyExpressionCode(elem) {

    var $dialogName = 'dialog-taxonamyExpcriteria-<?php echo $this->uniqId; ?>';

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('#window-word-template-<?php echo $this->uniqId ?>');
    }

    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');
        var $parent = $(elem).closest('tr');

        $("#" + $dialogName).empty().html(
            '<div class="row">'+
                '<div class="col-md-12">'+
                '<div class="tabbable-line">'+
                    '<ul class="nav nav-tabs statement-criteria-tabs">'+
                        '<li>'+
                            '<a href="#set-reportexp-tab1" class="active" data-toggle="tab">Expression</a>'+
                        '</li>'+           
                    '</ul>'+
                '<div class="tab-content">'+
                    '<div class="tab-pane active" id="set-reportexp-tab1">'+
                        '<div class="row">'+
                            '<div class="col-md-12">'+
                            '<?php
                            echo Form::textArea(
                                array(
                                    'name' => 'taxonamyRowExpressionString_set',
                                    'id' => 'taxonamyRowExpressionString_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => '',
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
            '</div>'
        );

        $("#" + $dialogName).find('#taxonamyRowExpressionString_set').val($parent.find('input[name="bpTemplateTaxonamyExpression[]"]').val());

        $("#" + $dialogName).dialog({
            appendTo: '#window-word-template-<?php echo $this->uniqId ?>',
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Taxonamy Expression',
            width: 900,
            minWidth: 900,
            height: "auto",
            modal: false,
            position: {my:'top', at:'top+30'},
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green', click: function() {
                    taxonamyExpressionRowEditor.save();
                    
                    $parent.find('input[name="bpTemplateTaxonamyExpression[]"]').val($('#taxonamyRowExpressionString_set', '#window-word-template-<?php echo $this->uniqId ?>').val());
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
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

        var taxonamyExpressionRowEditor = CodeMirror.fromTextArea(document.getElementById("taxonamyRowExpressionString_set"), {
            mode: 'javascript',
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: 'material', 
            extraKeys: {
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });
        taxonamyExpressionRowEditor.refresh();

        $("#" + $dialogName).dialog('open');
    });
}

function taxonamyExpressionDtlCode(elem) {

    var $dialogName = 'dialog-taxonamyExpcriteriaDtl-<?php echo $this->uniqId; ?>';
    var $parent = $(elem).closest('tr');

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('#window-word-template-<?php echo $this->uniqId ?>');
    }

    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');

        $("#" + $dialogName).empty().html(
            '<div class="row">'+
                '<div class="col-md-12">'+
                '<div class="tabbable-line">'+
                    '<ul class="nav nav-tabs statement-criteria-tabs">'+
                        '<li>'+
                            '<a href="#set-reportexp-tab11" class="active" data-toggle="tab">Expression</a>'+
                        '</li>'+           
                    '</ul>'+
                '<div class="tab-content">'+
                    '<div class="tab-pane active" id="set-reportexp-tab11">'+
                        '<div class="row">'+
                            '<div class="col-md-12">'+
                            '<?php
                            echo Form::textArea(
                                array(
                                    'name' => 'taxonamyRowExpressionDtlString_set',
                                    'id' => 'taxonamyRowExpressionDtlString_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => '',
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
            '</div>'
        );

        $("#" + $dialogName).find('#taxonamyRowExpressionDtlString_set').val($parent.find('input[name="bpTemplateTaxonamyExpressionDtl[]"]').val());

        $("#" + $dialogName).dialog({
            appendTo: '#window-word-template-<?php echo $this->uniqId ?>',
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Taxonamy Expression DTL',
            width: 900,
            minWidth: 900,
            height: "auto",
            modal: false,
            position: {my:'top', at:'top+30'},
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green', click: function() {
                    taxonamyExpressionDtlRowEditor.save();
                    
                    $parent.find('input[name="bpTemplateTaxonamyExpressionDtl[]"]').val($('#taxonamyRowExpressionDtlString_set', '#window-word-template-<?php echo $this->uniqId ?>').val());
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
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

        var taxonamyExpressionDtlRowEditor = CodeMirror.fromTextArea(document.getElementById("taxonamyRowExpressionDtlString_set"), {
            mode: 'javascript',
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: 'material', 
            extraKeys: {
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });
        taxonamyExpressionDtlRowEditor.refresh();

        $("#" + $dialogName).dialog('open');
    });
}

function taxonamyExpressionDtl_1Code(elem) {

    var $dialogName = 'dialog-taxonamyExpcriteriaDtl-1-<?php echo $this->uniqId; ?>';
    var $parent = $(elem).closest('tr');

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('#window-word-template-<?php echo $this->uniqId ?>');
    }

    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');

        $("#" + $dialogName).empty().html(
            '<div class="row">'+
                '<div class="col-md-12">'+
                '<div class="tabbable-line">'+
                    '<ul class="nav nav-tabs statement-criteria-tabs">'+
                        '<li>'+
                            '<a href="#set-reportexp-tab11" class="active" data-toggle="tab">Expression</a>'+
                        '</li>'+           
                    '</ul>'+
                '<div class="tab-content">'+
                    '<div class="tab-pane active" id="set-reportexp-tab11">'+
                        '<div class="row">'+
                            '<div class="col-md-12">'+
                            '<?php
                            echo Form::textArea(
                                array(
                                    'name' => 'taxonamyRowExpressionDtl_1String_set',
                                    'id' => 'taxonamyRowExpressionDtl_1String_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => '',
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
            '</div>'
        );

        $("#" + $dialogName).find('#taxonamyRowExpressionDtl_1String_set').val($parent.find('input[name="bpTemplateTaxonamyExpressionDtl_1[]"]').val());

        $("#" + $dialogName).dialog({
            appendTo: '#window-word-template-<?php echo $this->uniqId ?>',
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Taxonamy Expression DTL',
            width: 900,
            minWidth: 900,
            height: "auto",
            modal: false,
            position: {my:'top', at:'top+30'},
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green', click: function() {
                    taxonamyExpressionDtlRowEditor.save();
                    
                    $parent.find('input[name="bpTemplateTaxonamyExpressionDtl_1[]"]').val($('#taxonamyRowExpressionDtl_1String_set', '#window-word-template-<?php echo $this->uniqId ?>').val());
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
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

        var taxonamyExpressionDtlRowEditor = CodeMirror.fromTextArea(document.getElementById("taxonamyRowExpressionDtl_1String_set"), {
            mode: 'javascript',
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: 'material', 
            extraKeys: {
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });
        taxonamyExpressionDtlRowEditor.refresh();

        $("#" + $dialogName).dialog('open');
    });
}

function chooseNotaryBpMeta(metaDataCode, chooseType, elem, rows) {
    var row = rows[0];
    var _parent = $(elem).closest('.meta-autocomplete-wrap');
    _parent.find('#metaDataId_valueField').val(row.id);
    _parent.find('#metaDataId_displayField').val(row.code);
    _parent.find('#metaDataId_nameField').val(row.name);
}   

function chooseNotaryContent(metaDataCode, chooseType, elem, rows) {
    var row = rows[0];
    
    PNotify.removeAll();
    if(typeof row.filename === 'undefined') {
        new PNotify({
            title: 'Warning',
            text: 'DataView-ээс <strong>filename</strong> багана олдсонгүй!',
            type: 'warning',
            sticker: false
        });        
        return;
    }    
    
    var _parent = $(elem).closest('.meta-autocomplete-wrap');
    _parent.find('#metaDataId_valueField_2').val(row.id);
    _parent.find('#metaDataId_displayField_2').val(row.filename);
    _parent.find('#metaDataId_nameField_2').val(row.filename);
    _parent.find('#content_file_path').val(row.physicalpath);
    //$(elem).closest('form').find('.contentIconViewer').html('<img class="directory-img" src="'+row.picture+'">');
} 

function generateSelectCombo(groupPath, groupPathReal) {
    var groupSelect2 = $('div.'+groupPath, '#window-word-template-<?php echo $this->uniqId ?>').find('.bpTemplateWidgetCode');
    var groupSelect = $('div.'+groupPath, '#window-word-template-<?php echo $this->uniqId ?>').find('.bpTemplateGroupPath');
    groupSelect2.append($('<option />').val('').text('- Cонгох -'));
    
    var widgetArray = <?php echo json_encode($this->widgetConfigPath) ?>;
    
    for (var ii = 0; ii < widgetArray.length; ii++){    
        if (savedTaxonomyConfig.hasOwnProperty(groupPathReal)) {
            if (savedTaxonomyConfig[groupPathReal].row.WIDGET_CODE == widgetArray[ii].id) {
                groupSelect2.append($("<option />")
                    .val(widgetArray[ii].id)
                    .text(widgetArray[ii].text)
                    .attr("selected", "selected"));
            } 
            else {
                groupSelect2.append($("<option />")
                    .val(widgetArray[ii].id)
                    .text(widgetArray[ii].text));
            }
        } 
        else {
            groupSelect2.append($("<option />")
                .val(widgetArray[ii].id)
                .text(widgetArray[ii].text));
        }
    }
    groupSelect2.select2();
    groupSelect.select2();
}

function mainTaxonamyWidget() {
    
}
</script>

<style type="text/css">
    .w-45 {
        width: 45%;
    }
    </style>