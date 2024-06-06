<?php
if (!$this->isAjax) {
?>
<div class="col-md-12" id="glEntry">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="caption buttons"> 
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                    <?php echo $this->title; ?>
                </span>
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
    <div class="caption buttons"> 
        <?php
        if ($this->isAjax) {
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10', 
                    'onclick' =>'backFirstContent(this);',
                    'style' => 'padding: 0px 5px; margin-top: -5px;', 
                    'data-dm-id' => $this->dataViewId
                ), 
                '<i class="icon-arrow-left7"></i>', 
                $this->isDataView
            );
        }
        ?>
        <span class="caption-subject font-weight-bold uppercase card-subject-blue glheader">
            <?php echo $this->title; ?>
        </span>
        <!--<div class="float-right">
            <div class="btn-group btn-group-xs">
                <button type="button" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Өмнөх</button>
                <button type="button" class="btn btn-secondary">Дараах <i class="fa fa-arrow-right"></i></button>
            </div>
        </div>-->
    </div>
<?php    
    }
}
?>            
        <form id="glEntryForm" class="form-horizontal" method="post">
            <div class="hide mt10" id="boot-fileinput-error-wrap"></div>
            <div class="clearfix w-100"></div>
            <?php echo $this->editForm; ?>

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
                                'class' => 'btn purple-plum btn-circle mr5', 
                                'onclick' => 'glPrintPreview_'.$this->uniqId.'(\''.Mdgl::$glMainDvId.'\', this);'
                            ), 
                            '<i class="far fa-print"></i> '.$this->lang->line('print_btn'), 
                            isset($this->isPrint) ? $this->isPrint : false     
                        ); 
                        echo html_tag('button', 
                            array(
                                'type' => 'button', 
                                'class' => 'btn btn-circle bg-yellow-gold mr5', 
                                'onclick' => 'bankChargeORBillRateFromGL_'.$this->uniqId.'(\'billrate\');'
                            ), 
                            '<i class="fa fa-bars"></i> Тооцооны ханш', 
                            isset($this->isNotButton) ? $this->isNotButton : true   
                        ); 
                        echo html_tag('button', 
                            array(
                                'type' => 'button', 
                                'class' => 'btn btn-circle bg-yellow-gold mr5',
                                'onclick' => 'bankChargeORBillRateFromGL_'.$this->uniqId.'(\'bankcharge\');'
                            ), 
                            '<i class="fa fa-bell"></i> Bank charge', 
                            isset($this->isNotButton) ? $this->isNotButton : true       
                        ); 
                        echo html_tag('button', 
                            array(
                                'type' => 'button', 
                                'class' => 'btn btn-circle green-meadow bp-btn-save updateGlEntry', 
                                'data-dm-id' => $this->dataViewId
                            ), 
                            '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'), 
                            isset($this->isNotButton) ? $this->isNotButton : true       
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
var isglcopy = '<?php echo issetParam($this->isglcopy); ?>';
$(function() {
    $(".updateGlEntry").on("click", function() {
        var _this = $(this);
        var _form = _this.closest('form');

        PNotify.removeAll();

        _form.validate({errorPlacement: function() {}});
        var actionUrl = isglcopy ? 'mdgl/createGlEntry' : 'mdgl/updateGlEntry';

        if (_form.valid()) {
            var validGl = validateGlBook('#glTemplateSectionStatic_<?php echo $this->uniqId; ?>');

            if (validGl.status == 'success') {
                _form.ajaxSubmit({
                    type: 'post',
                    url: actionUrl,
                    dataType: "json",
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function(data) {
                        Core.unblockUI();
                        
                        if (data.status === 'success') {
                            new PNotify({
                                title: 'Success',
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                            
                            if (typeof _this.attr('data-dm-id') !== 'undefined') {
                                var mainMetaDataId = _this.attr('data-dm-id');

                                if (mainMetaDataId !== '') {
                                    backFirstContent(_this);
                                    
                                    _isRunAfterProcessSave = true;
                                    var dataGrid = $('#objectdatagrid_' + mainMetaDataId);
                                    if ($('#objectdatagrid_' + mainMetaDataId).length === 0) {
                                        dataGrid = window['objectdatagrid_' + mainMetaDataId];
                                    }

                                    var op = dataGrid.datagrid('options');
                                    if (op.idField === null) {
                                        dataGrid.datagrid('reload');
                                    } else {
                                        dataGrid.treegrid('reload');
                                    }
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
    $(".deleteGlEntry", "#glEntry").on("click", function() {
        var dialogName = '#removeDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('<?php echo $this->lang->line('msg_delete_confirm'); ?>').dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            width: 'auto',
            height: 'auto',
            modal: true,
            buttons: [
                {text: '<?php echo $this->lang->line('yes_btn'); ?>', class: 'btn blue', click: function() {
                    $.ajax({
                        type: 'post',
                        url: 'mdgl/deleteGlEntryWithBook',
                        data: {id: $("#glBookId").val()},
                        dataType: "json",
                        success: function(data) {
                            if (data.status === 'success') {
                                new PNotify({
                                    title: 'Success',
                                    text: 'Амжилттай устгагдлаа.',
                                    type: 'success',
                                    sticker: false
                                });
                            } else {
                                new PNotify({
                                    title: 'Error',
                                    text: 'Алдаа гарлаа',
                                    type: 'error',
                                    sticker: false
                                });
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                    $(dialogName).dialog('close');
                }},
                {text: '<?php echo $this->lang->line('no_btn'); ?>', class: 'btn', click: function() {
                    $(dialogName).dialog('close');
                }}]
        }).dialog('open');
    });
    $(".cancelGlEntry").on("click", function() {
        PNotify.removeAll();
        var dialogName = '#cancelDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('Та баримтыг цуцлахдаа итгэлтэй байна уу').dialog({
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
                }},
                {text: '<?php echo $this->lang->line('no_btn'); ?>', class: 'btn grey-cascade btn-sm', click: function() {
                    $(dialogName).dialog('close');
                }}
            ]
        }).dialog('open');
    });
});

<?php
if (isset($this->isPrint) && $this->isPrint) {
?>
function glPrintPreview_<?php echo $this->uniqId; ?>(dvId, elem) {
        
    var rows = [];
    rows[0] = JSON.parse('<?php echo Json::encode($this->printRowJson); ?>');
    
    $.ajax({
        type: 'post',
        url: 'mdtemplate/checkCriteria',
        data: {metaDataId: dvId, dataRow: rows, isProcess: false},
        dataType: 'json', 
        beforeSend: function(){
            Core.blockUI({message: 'Loading...', boxed: true});
        },
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
                    $dialog.dialog('destroy').remove();
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
<?php
}
?>
</script>