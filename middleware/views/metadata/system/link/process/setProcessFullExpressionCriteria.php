<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-4">
        <div class="table-scrollable" style="max-height: 500px; overflow-y: auto">
            <table class="table table-sm table-hover table-striped" id="fullExpressionPathList">
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
            <ul class="nav nav-tabs param-criteria-tabs">
                <?php
                if ($this->isVersionForm) {
                ?>
                <li class="nav-item">
                    <a href="#set-fullexp-tab5" class="nav-link active" data-toggle="tab">Version</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-tab1" class="nav-link" data-toggle="tab">With Event</a>
                </li>
                <?php
                } else {
                ?>
                <li class="nav-item">
                    <a href="#set-fullexp-tab1" class="nav-link active" data-toggle="tab" data-visited="1">With Event</a>
                </li>
                <?php
                }
                ?>
                <li class="nav-item">
                    <a href="#set-fullexp-tab2" class="nav-link" data-toggle="tab">Without Event</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-tab3" class="nav-link" data-toggle="tab">Variable & Function</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-tab4" class="nav-link" data-toggle="tab">Before Save</a>
                </li>
                <li class="nav-item">
                    <a href="#set-fullexp-tab6" class="nav-link" data-toggle="tab">After Save</a>
                </li>
                <?php
                if (!isset($this->ignoreCacheForm)) {
                ?>
                <li class="nav-item">
                    <a href="#set-fullexp-tab7" class="nav-link" data-toggle="tab">Cache</a>
                </li>
                <?php
                }
                ?>
            </ul>
            <div class="tab-content pb0">
                <?php
                if ($this->isVersionForm) {
                ?>
                <div class="tab-pane active" id="set-fullexp-tab5">
                    <?php echo $this->versionForm; ?> 
                </div>
                <div class="tab-pane" id="set-fullexp-tab1">
                    <div class="row">                
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fullExpressionEditor');">
                                <i class="far fa-expand"></i>
                            </button>
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionString_set',
                                    'id' => 'fullExpressionString_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'EVENT_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;',
                                    'data-editor' => 'event'
                                )
                            );
                            ?>
                        </div>
                    </div>    
                </div>
                <?php
                } else {
                ?>
                <div class="tab-pane active" id="set-fullexp-tab1">
                    <div class="row">                
                        <div class="col-md-12">
                            
                            <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fullExpressionEditor');">
                                <i class="far fa-expand"></i>
                            </button>
                            
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionString_set',
                                    'id' => 'fullExpressionString_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'EVENT_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;', 
                                    'data-editor' => 'event'
                                )
                            );
                            ?>
                        </div>
                    </div>    
                </div>
                <?php
                }
                ?>
                <div class="tab-pane" id="set-fullexp-tab2">
                    <div class="row">         
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fullExpressionOpenEditor');">
                                <i class="far fa-expand"></i>
                            </button>
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionOpenCriteria_set',
                                    'id' => 'fullExpressionOpenCriteria_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'LOAD_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;', 
                                    'data-editor' => 'load'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-fullexp-tab3">
                    <div class="row">         
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fullExpressionVarFncEditor');">
                                <i class="far fa-expand"></i>
                            </button>
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionStringVarFnc_set',
                                    'id' => 'fullExpressionStringVarFnc_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'VAR_FNC_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;', 
                                    'data-editor' => 'varfnc'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-fullexp-tab4">
                    <div class="row">         
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fullExpressionSaveEditor');">
                                <i class="far fa-expand"></i>
                            </button>
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionStringSave_set',
                                    'id' => 'fullExpressionStringSave_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'SAVE_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;', 
                                    'data-editor' => 'beforesave'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="set-fullexp-tab6">
                    <div class="row">         
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fullExpressionAfterSaveEditor');">
                                <i class="far fa-expand"></i>
                            </button>
                            <?php
                            echo Form::textArea(
                                array(
                                    'name' => 'fullExpressionStringAfterSave_set',
                                    'id' => 'fullExpressionStringAfterSave_set',
                                    'class' => 'form-control ace-textarea',
                                    'value' => Arr::get($this->expRow, 'AFTER_EXPRESSION_STRING'),
                                    'spellcheck' => 'false',
                                    'style' => 'width: 100%;',
                                    'data-editor' => 'aftersave'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if (!isset($this->ignoreCacheForm)) {
                ?>
                <div class="tab-pane" id="set-fullexp-tab7">
                    <?php echo $this->cacheExpressionForm; ?>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>    
<?php 
echo Form::hidden(array('name' => 'bpExpKeyMetaId', 'value' => $this->metaDataId)); 
echo Form::hidden(array('name' => 'configId', 'value' => $this->configId)); 
?>

<style type="text/css">
.CodeMirror .cm-error {
    background-color: transparent !important;
    color: #82b1ff !important;
}
.pf-fullexp-fullscreen-btn {
    position: absolute;
    z-index: 9999999;
    top: -29px;
    right: 10px;
}
.pf-fullexp-fullscreen {
    position: fixed;
    top: 8px;
    right: 10px;
}
</style>

<?php require BASEPATH . 'middleware/views/metadata/system/link/process/fullexpression/autocomplete_script.php'; ?>

<script type="text/javascript">
var formatExpOpts = {
    indent_size: 4,
    indent_char: ' ',
    max_preserve_newlines: 5,
    preserve_newlines: true,
    keep_array_indentation: false,
    break_chained_methods: false,
    indent_scripts: 'normal',
    brace_style: 'collapse',
    space_before_conditional: true, 
    unescape_strings: false, 
    jslint_happy: false,
    end_with_newline: false,
    wrap_line_length: 0,
    indent_inner_html: false,
    comma_first: false,
    e4x: false,
    indent_empty_lines: false
};
var fullExpCommentTxt = '/*'+"\n"+'Author: <?php echo Ue::getSessionPersonName(); ?>'+"\n"+'Description: '+"\n"+'*/';

var fullExpressionEditor = CodeMirror.fromTextArea(document.getElementById("fullExpressionString_set"), {
    mode: 'javascript',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: 'material', 
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
    extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }, 
        "Ctrl-Y": function(cm){ CodeMirror.commands.foldAll(cm); }, 
        "Ctrl-I": function(cm){ CodeMirror.commands.unfoldAll(cm); }, 
        "Alt-F": "findPersistent",
        "Alt-T": function(cm){ 
            var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
            cm.setValue(formattedExpression);
        }, 
        "Alt-C": function(cm){ 
            insertTextAtCursor(cm, fullExpCommentTxt);
        }, 
        "F11": function(cm) {
            var $focusedElem = $(document.activeElement);
            var $parent = $focusedElem.closest('.tab-pane');
            var $btn = $parent.find('.pf-fullexp-fullscreen-btn');
        
            pfFullExpFullScreenMode($btn, 'fullExpressionEditor');
            /*cm.setOption("fullScreen", !cm.getOption("fullScreen"));*/
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) { 
                var $focusedElem = $(document.activeElement);
                var $parent = $focusedElem.closest('.tab-pane');
                var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

                pfFullExpFullScreenMode($btn, 'fullExpressionEditor');
                /*cm.setOption("fullScreen", false);*/
            }
        }
    }, 
    hintOptions: {hint: bpEventCompleteHint}
});

/*fullExpressionEditor.on("keydown", function (cm, event) {
    if (cm.state.completionActive && event.keyCode === 13) {
        var cur = cm.getCursor();

        var doc = fullExpressionEditor.getDoc();
        var fullLine = doc.getLine(cur.line);
        console.log(fullLine);
    }
});*/

var fullExpressionOpenEditor = CodeMirror.fromTextArea(document.getElementById("fullExpressionOpenCriteria_set"), {
    mode: 'javascript',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: 'material', 
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
    extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }, 
        "Ctrl-Y": function(cm){ CodeMirror.commands.foldAll(cm); }, 
        "Ctrl-I": function(cm){ CodeMirror.commands.unfoldAll(cm); }, 
        "Alt-F": "findPersistent", 
        "Alt-T": function(cm){ 
            var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
            cm.setValue(formattedExpression);
        },
        "Alt-C": function(cm){ 
            insertTextAtCursor(cm, fullExpCommentTxt);
        }, 
        "F11": function(cm) {
            var $focusedElem = $(document.activeElement);
            var $parent = $focusedElem.closest('.tab-pane');
            var $btn = $parent.find('.pf-fullexp-fullscreen-btn');
        
            pfFullExpFullScreenMode($btn, 'fullExpressionOpenEditor');
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) { 
                var $focusedElem = $(document.activeElement);
                var $parent = $focusedElem.closest('.tab-pane');
                var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

                pfFullExpFullScreenMode($btn, 'fullExpressionOpenEditor');
            }
        }
    }, 
    hintOptions: {hint: bpEventCompleteHint}
});
var fullExpressionVarFncEditor = CodeMirror.fromTextArea(document.getElementById("fullExpressionStringVarFnc_set"), {
    mode: 'javascript',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: 'material', 
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
    extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }, 
        "Ctrl-Y": function(cm){ CodeMirror.commands.foldAll(cm); }, 
        "Ctrl-I": function(cm){ CodeMirror.commands.unfoldAll(cm); }, 
        "Alt-F": "findPersistent", 
        "Alt-T": function(cm){ 
            var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
            cm.setValue(formattedExpression);
        },
        "Alt-C": function(cm){ 
            insertTextAtCursor(cm, fullExpCommentTxt);
        }, 
        "F11": function(cm) {
            var $focusedElem = $(document.activeElement);
            var $parent = $focusedElem.closest('.tab-pane');
            var $btn = $parent.find('.pf-fullexp-fullscreen-btn');
        
            pfFullExpFullScreenMode($btn, 'fullExpressionVarFncEditor');
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) {
                
                var $focusedElem = $(document.activeElement);
                var $parent = $focusedElem.closest('.tab-pane');
                var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

                pfFullExpFullScreenMode($btn, 'fullExpressionVarFncEditor');
            }
        }
    }, 
    hintOptions: {hint: bpEventCompleteHint}
});
var fullExpressionSaveEditor = CodeMirror.fromTextArea(document.getElementById("fullExpressionStringSave_set"), {
    mode: 'javascript',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: 'material', 
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
    extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }, 
        "Ctrl-Y": function(cm){ CodeMirror.commands.foldAll(cm); }, 
        "Ctrl-I": function(cm){ CodeMirror.commands.unfoldAll(cm); }, 
        "Alt-F": "findPersistent", 
        "Alt-T": function(cm){ 
            var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
            cm.setValue(formattedExpression);
        },
        "Alt-C": function(cm){ 
            insertTextAtCursor(cm, fullExpCommentTxt);
        }, 
        "F11": function(cm) {
            var $focusedElem = $(document.activeElement);
            var $parent = $focusedElem.closest('.tab-pane');
            var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

            pfFullExpFullScreenMode($btn, 'fullExpressionSaveEditor');
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) {
                var $focusedElem = $(document.activeElement);
                var $parent = $focusedElem.closest('.tab-pane');
                var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

                pfFullExpFullScreenMode($btn, 'fullExpressionSaveEditor');
            }
        }
    }, 
    hintOptions: {hint: bpEventCompleteHint}
});
var fullExpressionAfterSaveEditor = CodeMirror.fromTextArea(document.getElementById("fullExpressionStringAfterSave_set"), {
    mode: 'javascript',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: 'material', 
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
    extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }, 
        "Ctrl-Y": function(cm){ CodeMirror.commands.foldAll(cm); }, 
        "Ctrl-I": function(cm){ CodeMirror.commands.unfoldAll(cm); }, 
        "Alt-F": "findPersistent", 
        "Alt-T": function(cm){ 
            var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
            cm.setValue(formattedExpression);
        },
        "Alt-C": function(cm){ 
            insertTextAtCursor(cm, fullExpCommentTxt);
        }, 
        "F11": function(cm) {
            var $focusedElem = $(document.activeElement);
            var $parent = $focusedElem.closest('.tab-pane');
            var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

            pfFullExpFullScreenMode($btn, 'fullExpressionAfterSaveEditor');
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) {
                
                var $focusedElem = $(document.activeElement);
                var $parent = $focusedElem.closest('.tab-pane');
                var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

                pfFullExpFullScreenMode($btn, 'fullExpressionAfterSaveEditor');
            }
        }
    }, 
    hintOptions: {hint: bpEventCompleteHint}
});

setTimeout(function() {
    fullExpressionEditor.refresh();
}, 1);

$(function() {

    $('.param-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        
        var $this = $(this), href = $this.attr('href');
        
        if (!$this.hasAttr('data-visited')) {
            
            if (href == '#set-fullexp-tab1') {
                fullExpressionEditor.refresh();
            } else if (href == '#set-fullexp-tab2') {
                fullExpressionOpenEditor.refresh();
            } else if (href == '#set-fullexp-tab3') {
                fullExpressionVarFncEditor.refresh();
            } else if (href == '#set-fullexp-tab4') {
                fullExpressionSaveEditor.refresh();
            } else if (href == '#set-fullexp-tab6') {
                fullExpressionAfterSaveEditor.refresh();
            }
            
            $this.attr('data-visited', 1);
        }
    });

    $('.tooltips').tooltip();      
    new ClipboardJS('[data-clipboard-text]');
    
    $("tbody tr", "table#fullExpressionPathList").on("dblclick", function() {
        var $this = $(this);
        var path = $this.attr('data-clipboard-text');
        var activeTab = $('.param-criteria-tabs li a.active').attr('href').replace('#', '');

        if (activeTab == 'set-fullexp-tab1') {
            insertTextAtCursor(fullExpressionEditor, path);      
        } else if (activeTab == 'set-fullexp-tab2') {
            insertTextAtCursor(fullExpressionOpenEditor, path);      
        } else if (activeTab == 'set-fullexp-tab3') {
            insertTextAtCursor(fullExpressionVarFncEditor, path);      
        } else if (activeTab == 'set-fullexp-tab4') {
            insertTextAtCursor(fullExpressionSaveEditor, path);      
        } else if (activeTab == 'set-fullexp-tab6') {
            insertTextAtCursor(fullExpressionAfterSaveEditor, path);      
        }
    });
    
    $("tbody tr", "table#fullExpressionPathList").on("click", function() {
        var $this = $(this);
        $("table#fullExpressionPathList tbody tr.selected").removeClass("selected");
        $this.addClass("selected");     
    });
});

function insertTextAtCursor(editor, text) {
    var doc = editor.getDoc();
    var cursor = doc.getCursor();
    var currLine = cursor.line;
    var currCh = cursor.ch;
        
    if (currLine != 0 || currCh != 0) {
        
        doc.replaceRange(text, cursor);
        editor.focus();
        
        setTimeout(function() {
            cursor.ch += text.length;
            editor.setCursor(cursor);
        }, 0);
    }
}
function pfFullExpFullScreenMode(elem, editorName) {
    var $this = $(elem), cm = window[editorName];
    
    if ($this.hasClass('pf-fullexp-fullscreen')) {
        cm.setOption('fullScreen', false);
        $this.removeClass('pf-fullexp-fullscreen');
        $this.find('i').removeClass('fa-compress').addClass('fa-expand');
    } else {
        cm.setOption('fullScreen', true);
        $this.addClass('pf-fullexp-fullscreen');
        $this.find('i').removeClass('fa-expand').addClass('fa-compress');
    }
}
</script>