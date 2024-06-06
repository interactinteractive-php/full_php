<?php
if (!$this->isAjax) {
?>
<div class="col-md-12" id="glEntry">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="caption buttons d-flex align-items-center"> 
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                    <?php echo Lang::line('FIN_1000'); ?>
                </span>
                <?php echo Mduser::linkAnchorIconQuickMenu('mdgl/entry', Lang::line('FIN_1000')); ?>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
<?php
} else {
    if (!$this->isPopup) {
?>
    <div class="caption buttons d-flex align-items-center mt5 mb10">
        <div class="dv-right-tools-btn">
            <?php
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10', 
                    'onclick' => 'backFirstContent(this);',
                    'data-dm-id' => $this->dataViewId
                ), 
                '<i class="icon-arrow-left8"></i>', 
                $this->isDataView 
            );
            ?>
        </div>
        <div class="caption-subject font-weight-bold uppercase card-subject-blue glheader">
            <?php echo Lang::line('FIN_1000'); ?>
        </div>
        <div class="ml-auto">
            <?php echo Mduser::linkAnchorIconQuickMenu('mdgl/entry', Lang::line('FIN_1000')); ?>
        </div>
    </div>
<?php    
    }
}
?>
        <form id="glEntryForm" class="form-horizontal" method="post">
            <div class="hide mt10" id="boot-fileinput-error-wrap"></div>
            <div class="clearfix w-100"></div>
            <?php echo $this->addForm; ?>
            
            <?php
            if (!$this->isPopup) {
            ?>
            <div class="form-actions mt15 form-actions-btn">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <?php 
                        if ($this->isAjax && $this->isDataView) {
                            echo html_tag('button', 
                                array(
                                    'type' => 'button', 
                                    'class' => 'btn btn-circle blue mr5', 
                                    'onclick' => 'backFirstContent(this);',
                                    'data-dm-id' => $this->dataViewId
                                ), 
                                '<i class="fa fa-reply"></i> ' . $this->lang->line('back_btn')  
                            );
                        } 
                        echo Mdcommon::redirectHelpContentButton('FIN_GL_ENTRY_HELP_CONTENT');
                        echo html_tag('button', 
                            array(
                                'type' => 'button', 
                                'class' => 'btn btn-circle green-meadow bp-btn-save createGlEntry', 
                                'data-dm-id' => $this->dataViewId
                            ), 
                            '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn') 
                        ); 
                        echo html_tag('button', 
                            array(
                                'type' => 'button', 
                                'class' => 'btn btn-circle purple-plum createGlEntry ml5', 
                                'data-print' => 'true', 
                                'data-dm-id' => $this->dataViewId
                            ), 
                            '<i class="far fa-print"></i> ' . $this->lang->line('Хадгалаад хэвлэх'), 
                            isset($this->isSavePrint) ? $this->isSavePrint : false      
                        ); 
                        echo Form::button(
                            array(
                                'class' => 'btn btn-circle blue-madison cancelGlEntry ml5', 
                                'value' => $this->lang->line('clear_btn')
                            )
                        ); 
                        ?>    
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </form>
    </div>
<?php
if (!$this->isAjax) {
?>        
    </div>
</div>
<?php
}
?>

<script type="text/javascript">
    $(function() {
        $(".createGlEntry").on("click", function() {
            var _this = $(this);
            var _form = _this.closest('form');
            
            PNotify.removeAll();
            _form.validate({errorPlacement: function() { }});
            
            if (_form.valid()) {
                
                var validGl = validateGlBook('#glTemplateSectionStatic_<?php echo $this->uniqId; ?>');
                
                if (validGl.status === 'success') {
                    
                    var isSavePrint = false;
                    
                    if (typeof _this.attr('data-print') !== 'undefined') {
                        isSavePrint = true;
                    }
                                
                    _form.ajaxSubmit({
                        type: 'post',
                        url: 'mdgl/createGlEntry',
                        beforeSubmit: function (formData, jqForm, options) {
                            if (isSavePrint) {
                                formData.push({name: 'isSavePrint', value: 1});
                            }
                        },
                        beforeSend: function() {
                            Core.blockUI({animate: true});
                        },
                        dataType: 'json',
                        success: function(data) {
                            
                            if (data.status === 'success') {
                                
                                new PNotify({
                                    title: 'Success',
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });
                                clearForm_<?php echo $this->uniqId; ?>(); 
                                
                                if (isSavePrint) {
                                    glPrintPreview(data.dvId, data.mainGlRow, _this);
                                }
                                
                                if (typeof _this.attr('data-dm-id') !== 'undefined') {
                                    var mainMetaDataId = _this.attr('data-dm-id');
                                    if (mainMetaDataId !== '') {
                                        dataViewReload(mainMetaDataId);
                                    } 
                                }
                                
                            } else {
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });
                            }
                            Core.unblockUI();
                        }
                    });
                } else {
                    new PNotify({
                        title: 'Error',
                        text: validGl.text,
                        type: 'error',
                        sticker: false
                    });
                }
            } else {
                $('html, body').animate({
                    scrollTop: 0
                }, 0);
            }
        });
        $(".cancelGlEntry").on("click", function() {
            PNotify.removeAll();
            
            var dialogName = '#cancelDialog';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            
            $(dialogName).html('Та баримтыг цуцлахдаа итгэлтэй байна уу?').dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Сануулга',
                width: 'auto',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: '<?php echo $this->lang->line('yes_btn'); ?>', class: 'btn blue btn-sm', click: function() {
                        $(dialogName).dialog('close');
                        clearForm_<?php echo $this->uniqId; ?>();
                    }},
                    {text: '<?php echo $this->lang->line('no_btn'); ?>', class: 'btn grey-cascade btn-sm', click: function() {
                        $(dialogName).dialog('close');
                    }}
                ]
            }).dialog('open');
        });      
    });
    function glPrintPreview(dvId, row, elem) {
        
        Core.blockUI({message: 'Loading...', boxed: true});
                
        var rows = [];
        rows[0] = row;
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/checkCriteria',
            data: {metaDataId: dvId, dataRow: rows, isProcess: false},
            dataType: "json", 
            async: false, 
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
            
                var $dialogName = 'dialog-gl-print';
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
                    modal: false,
                    open: function(){
                        Core.initDVAjax($dialog);
                    },
                    close: function(){
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('print_btn'), class: 'btn btn-sm blue', click: function() {
                            PNotify.removeAll();
                            var numberOfCopies = $("#numberOfCopies").val();
                            var isPrintNewPage = $("#isPrintNewPage").is(':checked') ? '1' : '0';
                            var isShowPreview = $("#isShowPreview").is(':checked') ? '1' : '0';
                            var isPrintPageBottom = $("#isPrintPageBottom").is(':checked') ? '1' : '0';
                            var isPrintPageRight = $("#isPrintPageRight").is(':checked') ? '1' : '0';
                            var pageOrientation = $("#pageOrientation").val();
                            var paperInput = $("#paperInput").val();
                            var pageSize = $("#pageSize").val();
                            var templates = $("#printTemplate").val();
                            var templateIds = $("#rtTemplateIds").val();
                            var printType = $("#printType").val();
                            var print_options = {
                                numberOfCopies: numberOfCopies,
                                isPrintNewPage: isPrintNewPage,
                                isShowPreview: isShowPreview,
                                isPrintPageBottom: isPrintPageBottom,
                                isPrintPageRight: isPrintPageRight,
                                isSettingsDialog: '0',
                                pageOrientation: pageOrientation,
                                paperInput: paperInput,
                                pageSize: pageSize,
                                printType: printType,
                                templates: templates, 
                                templateIds: templateIds 
                            }; 
                            if (numberOfCopies != '' && numberOfCopies != '0' && templates != null) {
                                $dialog.dialog('close');
                                callTemplate(rows, dvId, print_options);
                            } else {
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү',
                                    type: 'warning',
                                    sticker: false
                                });
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }
    function clearForm_<?php echo $this->uniqId; ?>() {
        var glEntryWindow = $("#glEntryWindow_<?php echo $this->uniqId; ?>");
        
        <?php
        if (defined('CONFIG_GL_IGNORE_SET_PREV_VALUE') && CONFIG_GL_IGNORE_SET_PREV_VALUE) {
        ?>
        glEntryWindow.find("input:not([name='glBookTypeId']), textarea").val('');        
        $("#glEntryWindow_<?php echo $this->uniqId; ?>").find("#glbookDate").val('<?php echo Date::currentDate('Y-m-d'); ?>');        
        <?php
        } else {
        ?>
        glEntryWindow.find("input:not([id='glbookDate'], .secondaryCurrencyCode, [name='secondaryCurrencyId'], [name='secondaryRate'], [id='gl_description_id'], [id='gl_description_code'], [id='gl_description_name'], [id='gldescription'], [name='glBookTypeId']), textarea").val('');
        <?php
        }
        ?>        
                
        glEntryWindow.find("table#glDtl tbody").empty();
        glEntryWindow.find("table#glDtl tfoot").find('td.foot-sum-debitamount, td.foot-sum-debitamountbase, td.foot-sum-creditamount, td.foot-sum-creditamountbase').text('0');
        glEntryWindow.find(".generalledger-header-sum-price").find("#headerDebitTotal").text('<?php echo Lang::lineDefault('DT', 'ДТ'); ?>: 0.00');
        glEntryWindow.find(".generalledger-header-sum-price").find("#headerCreditTotal").text('<?php echo Lang::lineDefault('KT', 'КТ'); ?>: 0.00');
        glEntryWindow.find('ul.list-view-file-new').find('li.meta:not([data-attach-id])').remove();
        
        getBookNumber(glEntryWindow);
    }
</script>