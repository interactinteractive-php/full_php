<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-4">
        <div class="table-scrollable" style="max-height: 500px; overflow-y: auto; margin-bottom: 0 !important;">
            <table class="table table-sm table-hover table-striped" id="fullExpressionPathList-<?php echo $this->uniqId; ?>">
                <thead>
                    <tr>
                        <th>Path</th>
                        <th><?php echo $this->lang->line('META_00125'); ?></th>
                        <th><?php echo $this->lang->line('META_00145'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $this->metaDatas; ?>
                </tbody>
            </table>
        </div>    
    </div>    
    <div class="col-md-8 pl0">
        <div class="tabbable-line">
            <ul class="nav nav-tabs param-criteria-tabs-<?php echo $this->uniqId; ?>">
                <li class="nav-item">
                    <a href="#set-fullexp-<?php echo $this->uniqId; ?>-tab1" class="nav-link active" data-toggle="tab">With Event</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-<?php echo $this->uniqId; ?>-tab2" class="nav-link" data-toggle="tab">Without Event</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-<?php echo $this->uniqId; ?>-tab3" class="nav-link" data-toggle="tab">Variable & Function</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-<?php echo $this->uniqId; ?>-tab4" class="nav-link" data-toggle="tab">Before Save</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-<?php echo $this->uniqId; ?>-tab5" class="nav-link" data-toggle="tab">After Save</a>
                </li>
            </ul>
            <div class="tab-content pb0">
                <div class="tab-pane active" id="set-fullexp-<?php echo $this->uniqId; ?>-tab1">
                    <div class="row">                
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionString_set',
                                    'id' => 'fullExpressionString_set_'.$this->uniqId,
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'EVENT_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>    
                </div>
                <div class="tab-pane" id="set-fullexp-<?php echo $this->uniqId; ?>-tab2">
                    <div class="row">         
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionOpenCriteria_set',
                                    'id' => 'fullExpressionOpenCriteria_set_'.$this->uniqId,
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'LOAD_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-fullexp-<?php echo $this->uniqId; ?>-tab3">
                    <div class="row">         
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionStringVarFnc_set',
                                    'id' => 'fullExpressionStringVarFnc_set_'.$this->uniqId,
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'VAR_FNC_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-fullexp-<?php echo $this->uniqId; ?>-tab4">
                    <div class="row">         
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionStringSave_set',
                                    'id' => 'fullExpressionStringSave_set_'.$this->uniqId,
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'SAVE_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-fullexp-<?php echo $this->uniqId; ?>-tab5">
                    <div class="row">         
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionStringAfterSave_set',
                                    'id' => 'fullExpressionStringAfterSave_set_'.$this->uniqId,
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'AFTER_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
<?php echo Form::hidden(array('name' => 'bpExpKeyMetaId', 'value' => $this->metaDataId)); ?>

<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>
<script type="text/javascript">
    var fullExpressionEditor_<?php echo $this->uniqId; ?> = CodeMirror.fromTextArea(document.getElementById("fullExpressionString_set_<?php echo $this->uniqId; ?>"), {
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
    var fullExpressionOpenEditor_<?php echo $this->uniqId; ?> = CodeMirror.fromTextArea(document.getElementById("fullExpressionOpenCriteria_set_<?php echo $this->uniqId; ?>"), {
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
    var fullExpressionVarFncEditor_<?php echo $this->uniqId; ?> = CodeMirror.fromTextArea(document.getElementById("fullExpressionStringVarFnc_set_<?php echo $this->uniqId; ?>"), {
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
    var fullExpressionSaveEditor_<?php echo $this->uniqId; ?> = CodeMirror.fromTextArea(document.getElementById("fullExpressionStringSave_set_<?php echo $this->uniqId; ?>"), {
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
    var fullExpressionAfterSaveEditor_<?php echo $this->uniqId; ?> = CodeMirror.fromTextArea(document.getElementById("fullExpressionStringAfterSave_set_<?php echo $this->uniqId; ?>"), {
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
    
    var dialogId_<?php echo $this->uniqId; ?> = $("#dialog-bp-<?php echo $this->uniqId; ?>");
    
    $(function() {
        
        expDialogDraw_<?php echo $this->uniqId; ?>();
        
        $('.param-criteria-tabs-<?php echo $this->uniqId; ?> a[data-toggle="tab"]').on('shown.bs.tab', function() {
            fullExpressionEditor_<?php echo $this->uniqId; ?>.refresh();
            fullExpressionOpenEditor_<?php echo $this->uniqId; ?>.refresh();
            fullExpressionVarFncEditor_<?php echo $this->uniqId; ?>.refresh();
            fullExpressionSaveEditor_<?php echo $this->uniqId; ?>.refresh();
            fullExpressionAfterSaveEditor_<?php echo $this->uniqId; ?>.refresh();
        });
        
        $('.tooltips').tooltip();
        new ClipboardJS('.fa-clipboard');       
        
        $("tbody tr", "table#fullExpressionPathList-<?php echo $this->uniqId; ?>").on("click", function() {
            var $this = $(this);
            $("table#fullExpressionPathList-<?php echo $this->uniqId; ?> tbody tr").removeClass("selected");
            $this.addClass("selected");        
        });
        
        dialogId_<?php echo $this->uniqId; ?>.bind("dialogextendmaximize", function(){
            expDialogDraw_<?php echo $this->uniqId; ?>();
        });
        dialogId_<?php echo $this->uniqId; ?>.bind("dialogextendrestore", function(){
            expDialogDraw_<?php echo $this->uniqId; ?>();
        });
    });
    
    function expDialogDraw_<?php echo $this->uniqId; ?>() {
        var dialogHeight = dialogId_<?php echo $this->uniqId; ?>.height();
        dialogId_<?php echo $this->uniqId; ?>.find("div.table-scrollable").css("height", (dialogHeight - 60)+'px');
        dialogId_<?php echo $this->uniqId; ?>.find("div.table-scrollable").css("max-height", (dialogHeight - 60)+'px');
        dialogId_<?php echo $this->uniqId; ?>.find(".CodeMirror").css("height", (dialogHeight - 90)+'px');
    }
</script>