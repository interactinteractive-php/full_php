<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-3">
        <?php if (count($this->metaDatasGroup) > 0) { ?>
            <p class="text-center">Групп баганууд</p>
            <div class="table-scrollable mb0" style="max-height: 400px; overflow-y: auto">
                <table class="table table-sm table-hover table-striped" id="fullExpressionPathList">
                    <tbody>
                        <?php                        
                        foreach ($this->metaDatasGroup as $meta) {
                            $meta['FIELD_PATH'] = strtolower($meta['FIELD_PATH']);
                            echo '<tr>';
                            echo '<td style="white-space: nowrap;">
                                    <i class="fa fa-clipboard tooltips" style="cursor: pointer" title="Path хуулах" data-clipboard-text="' . $meta['FIELD_PATH'] . '"></i> <span title="'.$this->lang->line($meta['LABEL_NAME']).'">' . $meta['FIELD_PATH'] . '</span>
                                  </td>
                                </tr>';
                        }      
                        ?>
                    </tbody>
                </table>
            </div>    
        <?php } ?>
        <div class="clearfix w-100"></div>
        <?php if (count($this->metaDatas) > 0) { ?>
            <p class="text-center">Детайл баганууд</p>
            <div class="table-scrollable mb0" style="max-height: 700px; overflow-y: auto">
                <table class="table table-sm table-hover table-striped" id="fullExpressionPathList">
                    <tbody>
                        <?php        
                        foreach ($this->metaDatas as $meta) {
                            $meta['FIELD_PATH'] = strtolower($meta['FIELD_PATH']);
                            echo '<tr>';
                            echo '<td style="white-space: nowrap;">
                                    <i class="fa fa-clipboard tooltips" style="cursor: pointer" title="Path хуулах" data-clipboard-text="' . $meta['FIELD_PATH'] . '"></i> <span title="'.$this->lang->line($meta['LABEL_NAME']).'">' . $meta['FIELD_PATH'] . '</span>
                                  </td>
                                </tr>';
                        }      
                        ?>
                    </tbody>
                </table>
            </div>    
        <?php } ?>
    </div>    
    <div class="col-md-9 pl0">
        <div class="tabbable-line">
            <ul class="nav nav-tabs statement-criteria-tabs">
                <li class="nav-item">
                    <a href="#set-reportexp-tab1" class="nav-link active" data-toggle="tab">Row Expression</a>
                </li>
                <li class="nav-item">
                    <a href="#set-reportexp-tab2" class="nav-link" data-toggle="tab">Group Expression</a>
                </li>
                <li class="nav-item">
                    <a href="#set-reportexp-tab3" class="nav-link" data-toggle="tab">Global Expression</a>
                </li>
                <li class="nav-item">
                    <a href="#set-reportexp-tab4" class="nav-link" data-toggle="tab">UI Expression</a>
                </li>
            </ul>
            <div class="tab-content pb0">
                <div class="tab-pane active" id="set-reportexp-tab1">
                    <div class="row">         
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'reportRowExpressionString_set',
                                    'id' => 'reportRowExpressionString_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->getStatementRow, 'ROW_EXPRESSION'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-reportexp-tab2">
                    <div class="row">         
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'reportGlobalExpressionString_set',
                                    'id' => 'reportGlobalExpressionString_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->getStatementRow, 'GLOBAL_EXPRESSION'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-reportexp-tab3">
                    <div class="row">         
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'reportSuperGlobalExpressionString_set',
                                    'id' => 'reportSuperGlobalExpressionString_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->getStatementRow, 'SUPER_GLOBAL_EXPRESSION'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-reportexp-tab4">
                    <div class="row">         
                        <div class="col-md-12">
                            
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs statement-sub-tabs">
                                    <li class="nav-item">
                                        <a href="#set-reportexp-tab5" class="nav-link active" data-toggle="tab">Header & Footer</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#set-reportexp-tab6" class="nav-link" data-toggle="tab">Group</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#set-reportexp-tab7" class="nav-link" data-toggle="tab">Detail</a>
                                    </li>
                                </ul>
                                <div class="tab-content pb0">
                                    <div class="tab-pane active" id="set-reportexp-tab5">
                                        <?php
                                        echo Form::textArea(
                                            array(
                                                'name' => 'uiExpressionHeaderFooter_set',
                                                'id' => 'uiExpressionHeaderFooter_set',
                                                'class' => 'form-control ace-textarea',
                                                'value' => Arr::get($this->getStatementRow, 'UI_EXPRESSION'),
                                                'spellcheck' => 'false',
                                                'style' => 'width: 100%;'
                                            )
                                        );
                                        ?>
                                    </div>   
                                    <div class="tab-pane" id="set-reportexp-tab6">
                                        <?php
                                        echo Form::textArea(
                                            array(
                                                'name' => 'uiExpressionGroup_set',
                                                'id' => 'uiExpressionGroup_set',
                                                'class' => 'form-control ace-textarea',
                                                'value' => Arr::get($this->getStatementRow, 'UI_GROUP_EXPRESSION'),
                                                'spellcheck' => 'false',
                                                'style' => 'width: 100%;'
                                            )
                                        );
                                        ?>
                                    </div> 
                                    <div class="tab-pane" id="set-reportexp-tab7">
                                        <?php
                                        echo Form::textArea(
                                            array(
                                                'name' => 'uiExpressionDetail_set',
                                                'id' => 'uiExpressionDetail_set',
                                                'class' => 'form-control ace-textarea',
                                                'value' => Arr::get($this->getStatementRow, 'UI_DETAIL_EXPRESSION'),
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
        </div>
    </div>
</div>

<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>
<script type="text/javascript">
    var reportlExpressionRowEditor = CodeMirror.fromTextArea(document.getElementById("reportRowExpressionString_set"), {
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
    var reportlExpressionGlobalEditor = CodeMirror.fromTextArea(document.getElementById("reportGlobalExpressionString_set"), {
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
    var reportlExpressionSuperGlobalEditor = CodeMirror.fromTextArea(document.getElementById("reportSuperGlobalExpressionString_set"), {
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
    var headerFooterExpressionUIEditor = CodeMirror.fromTextArea(document.getElementById("uiExpressionHeaderFooter_set"), {
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
    var groupExpressionUIEditor = CodeMirror.fromTextArea(document.getElementById("uiExpressionGroup_set"), {
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
    var detailExpressionUIEditor = CodeMirror.fromTextArea(document.getElementById("uiExpressionDetail_set"), {
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
    
    $(function() {
    
        $('.statement-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            reportlExpressionRowEditor.refresh();
            reportlExpressionGlobalEditor.refresh();
            reportlExpressionSuperGlobalEditor.refresh();
            headerFooterExpressionUIEditor.refresh();
        });
        
        $('.statement-sub-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            headerFooterExpressionUIEditor.refresh();
            groupExpressionUIEditor.refresh();
            detailExpressionUIEditor.refresh();
        });
        
        $('.tooltips').tooltip();
        new ClipboardJS('.fa-clipboard');

        $("table#fullExpressionPathList").on("dblclick", '.fa-clipboard', function() {
            var _this = $(this);
            var path = _this.attr('data-clipboard-text');
            var activeTab = $('.statement-criteria-tabs li a.active').attr('href').replace('#', '');

            if (activeTab == 'set-reportexp-tab1') {
                insertTextAtCursor(reportlExpressionRowEditor, path);
            } else if (activeTab == 'set-reportexp-tab2') {
                insertTextAtCursor(reportlExpressionGlobalEditor, path);
            } else if (activeTab == 'set-reportexp-tab3') {
                insertTextAtCursor(reportlExpressionSuperGlobalEditor, path);
            } 
        });

        $("tbody tr", "table#fullExpressionPathList").on("click", function() {
            var $this = $(this);
            $("table#fullExpressionPathList tbody tr").removeClass("selected");
            $this.addClass("selected");        
        });
    });

function insertTextAtCursor(editor, text) {
    var doc = editor.getDoc();
    var cursor = doc.getCursor();
    if (cursor.ch != 0 || cursor.line != 0) {
        doc.replaceRange(text, cursor);
    }
}
</script>