<form method="post" id="dataview-queryeditor-form">
<div class="tabbable-line">
    <ul class="nav nav-tabs dbdriver-tabs">
        <li class="nav-item">
            <a href="#default-tab" data-toggle="tab" class="nav-link active" aria-expanded="true">Default</a>
        </li>
        <li class="nav-item">
            <a href="#postgresql-tab" class="nav-link" data-toggle="tab">PostgreSQL</a>
        </li>
        <li class="nav-item">
            <a href="#mssql-tab" class="nav-link" data-toggle="tab">MSSQL</a>
        </li>
    </ul>
    <div class="tab-content pb0">
        <div class="tab-pane active" id="default-tab">
            <?php
            echo Form::textArea(
                array(
                    'id' => 'dvQuery_set',
                    'name' => 'dvQuery_set',
                    'class' => 'form-control ace-textarea',
                    'value' => $this->query,
                    'spellcheck' => 'false',
                    'style' => 'width: 100%;'
                )
            );
            ?>
        </div>
        <div class="tab-pane" id="postgresql-tab">
            <?php
            echo Form::textArea(
                array(
                    'id' => 'postgreSql_set',
                    'name' => 'postgreSql_set',
                    'class' => 'form-control ace-textarea',
                    'value' => $this->postgreSql,
                    'spellcheck' => 'false',
                    'style' => 'width: 100%;'
                )
            );
            ?>
        </div>
        <div class="tab-pane" id="mssql-tab">
            <?php
            echo Form::textArea(
                array(
                    'id' => 'msSql_set',
                    'name' => 'msSql_set',
                    'class' => 'form-control ace-textarea',
                    'value' => $this->msSql,
                    'spellcheck' => 'false',
                    'style' => 'width: 100%;'
                )
            );
            ?>
        </div>
    </div>
</div>  
<?php echo Form::hidden(array('name' => 'metaId', 'value' => issetParam($this->metaId))); ?>
</form>

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
            "Alt-F": "findPersistent", 
            "F11": function(cm) {
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
            },
            "Esc": function(cm) {
                if (cm.getOption("fullScreen")) {
                    cm.setOption("fullScreen", false);
                } else {
                    $('#dataview-queryeditor-form').closest('.ui-dialog-content').dialog('close');
                }
            }
        }
    });
    
    var postgreSqlEditor = CodeMirror.fromTextArea(document.getElementById('postgreSql_set'), {
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
            "Alt-F": "findPersistent", 
            "F11": function(cm) {
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
            },
            "Esc": function(cm) {
                if (cm.getOption("fullScreen")) {
                    cm.setOption("fullScreen", false);
                } else {
                    $('#dataview-queryeditor-form').closest('.ui-dialog-content').dialog('close');
                }
            }
        }
    });
    
    var msSqlEditor = CodeMirror.fromTextArea(document.getElementById('msSql_set'), {
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
            "Alt-F": "findPersistent", 
            "F11": function(cm) {
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
            },
            "Esc": function(cm) {
                if (cm.getOption("fullScreen")) {
                    cm.setOption("fullScreen", false);
                } else {
                    $('#dataview-queryeditor-form').closest('.ui-dialog-content').dialog('close');
                }
            }
        }
    });
    
    setTimeout(function() {
        dvSqlQueryEditor.refresh();
        dvSqlQueryEditor.focus();
    }, 1);
    
    $(function() {
        
        $('.dbdriver-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            dvSqlQueryEditor.refresh();
            postgreSqlEditor.refresh();
            msSqlEditor.refresh();
        });
    });
</script>