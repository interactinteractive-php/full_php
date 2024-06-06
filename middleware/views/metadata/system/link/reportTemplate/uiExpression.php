<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-3">
        <?php if (count($this->metaDatas) > 0) { ?>
            <p class="text-center">Талбарууд</p>
            <div class="table-scrollable" style="max-height: 700px; overflow-y: auto" id="rtMetasScroll">
                <table class="table table-sm table-hover table-striped" id="fullExpressionPathList">
                    <tbody>
                        <?php        
                        foreach ($this->metaDatas as $meta) {
                            $meta['FIELD_PATH'] = strtolower($meta['FIELD_PATH']);
                            echo '<tr>
                                  <td style="white-space: nowrap;">
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
        <?php
        echo Form::textArea(
            array(
                'name' => 'reportTemplateUIExpression',
                'id' => 'reportTemplateUIExpression',
                'class' => 'form-control ace-textarea',
                'value' => $this->expression,
                'spellcheck' => 'false',
                'style' => 'width: 100%;'
            )
        );
        ?>
    </div>
</div>

<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>
<script type="text/javascript">
    var reportTemplateUIExpression = CodeMirror.fromTextArea(document.getElementById("reportTemplateUIExpression"), {
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
    
    setTimeout(function() {
        reportTemplateUIExpression.refresh();
    }, 1);
    
    $(function() {
        
        $('.tooltips').tooltip();
        new ClipboardJS('.fa-clipboard');

        $("table#fullExpressionPathList").on("dblclick", '.fa-clipboard', function() {
            var $this = $(this);
            var path = $this.attr('data-clipboard-text');

            insertTextAtCursor(reportTemplateUIExpression, path);
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