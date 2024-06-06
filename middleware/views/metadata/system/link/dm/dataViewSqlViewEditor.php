<div class="tabbable-line">
    <ul class="nav nav-tabs dbdriver-tabs">
        <li class="nav-item">
            <a href="#default-tab" data-toggle="tab" class="nav-link active" aria-expanded="true">Default</a>
        </li>
    </ul>
    <div class="tab-content pb0">
        <div class="tab-pane active" id="default-tab">
            <?php
            echo Form::textArea(
                array(
                    'id' => 'dvQuery_set',
                    'class' => 'form-control ace-textarea',
                    'value' => $this->query,
                    'spellcheck' => 'false',
                    'style' => 'width: 100%;'
                )
            );
            ?>
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

    var dvSqlQueryEditor = CodeMirror.fromTextArea(document.getElementById('dvQuery_set'), {
        mode: 'text/x-plsql',
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        autofocus: true, 
        indentUnit: 2,
        tabSize: 2, 
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
        dvSqlQueryEditor.refresh();
    }, 1);
    
    $(function() {
        
        $('.dbdriver-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            dvSqlQueryEditor.refresh();
        });
    });
</script>