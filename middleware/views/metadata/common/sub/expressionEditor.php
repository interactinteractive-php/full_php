<div class="row">    
    <?php
    if (isset($this->paths) && $this->paths) {
    ?>
    <div class="col-md-4">
        <div class="table-scrollable" style="overflow-y: auto" id="expressionPathList-scroll">
            <table class="table table-sm table-hover table-striped" id="expressionPathList">
                <thead>
                    <tr>
                        <th>Path</th>
                        <th><?php echo $this->lang->line('META_00125'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->paths as $path) {
                    ?>
                    <tr data-meta-code="<?php echo $path['PATH']; ?>" data-clipboard-text="<?php echo $path['PATH']; ?>">
                        <td style="white-space: nowrap;">
                            <?php echo $path['PATH']; ?>
                        </td>
                        <td style="white-space: nowrap;">
                            <?php echo $path['LABEL_NAME']; ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>    
    </div>   
    <div class="col-md-8 pl0">
        <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fieldExpressionEditor');">
            <i class="far fa-expand"></i>
        </button>
        <?php
        echo Form::textArea(
            array(
                'id' => 'fieldExpression_set',
                'class' => 'form-control ace-textarea',
                'value' => $this->expression,
                'spellcheck' => 'false',
                'style' => 'width: 100%;'
            )
        );
        ?>
    </div>
    <?php
    } else {
    ?>
    <div class="col-md-12">
        <button type="button" class="btn btn-sm btn-light pf-fullexp-fullscreen-btn" onclick="pfFullExpFullScreenMode(this, 'fieldExpressionEditor');">
            <i class="far fa-expand"></i>
        </button>
        <?php
        echo Form::textArea(
            array(
                'id' => 'fieldExpression_set',
                'class' => 'form-control ace-textarea',
                'value' => $this->expression,
                'spellcheck' => 'false',
                'style' => 'width: 100%;'
            )
        );
        ?>
    </div>
    <?php
    }
    
    $mode = 'javascript'; 
    $lint = 'false';
    
    if ($this->isJson == 1) {
        $mode = 'application/json';
        $lint = 'true';
    }
    ?>
</div>   

<style type="text/css">
.CodeMirror .cm-error {
    background-color: transparent !important;
    color: #82b1ff !important;
}
.pf-fullexp-fullscreen-btn {
    position: absolute;
    z-index: 9999999;
    top: -0;
    right: 10px;
}
.pf-fullexp-fullscreen {
    position: fixed;
    top: 8px;
    right: 10px;
}
</style>

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

var fieldExpressionEditor = CodeMirror.fromTextArea(document.getElementById('fieldExpression_set'), {
    mode: '<?php echo $mode; ?>',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: 'material', 
    foldGutter: true,
    lint: <?php echo $lint; ?>, 
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter", "CodeMirror-lint-markers"], 
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
            var $parent = $focusedElem.closest('div');
            var $btn = $parent.find('.pf-fullexp-fullscreen-btn');
        
            pfFullExpFullScreenMode($btn, 'fieldExpressionEditor');
        },
        "Esc": function(cm) {
            if (cm.getOption('fullScreen')) { 
                var $focusedElem = $(document.activeElement);
                var $parent = $focusedElem.closest('div');
                var $btn = $parent.find('.pf-fullexp-fullscreen-btn');

                pfFullExpFullScreenMode($btn, 'fieldExpressionEditor');
            }
        }
    }
});

setTimeout(function() {
    fieldExpressionEditor.refresh();
}, 1);

$(function() {

    $('.tooltips').tooltip();
    new ClipboardJS('[data-clipboard-text]');
    
    $("tbody tr", "table#expressionPathList").on("dblclick", function() {
        var $this = $(this);
        var path = $this.attr('data-clipboard-text');

        insertTextAtCursor(fieldExpressionEditor, path);  
    });
    
    $("tbody tr", "table#expressionPathList").on("click", function() {
        var $this = $(this);
        $("table#expressionPathList tbody tr.selected").removeClass("selected");
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