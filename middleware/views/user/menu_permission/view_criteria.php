<div class="tabbable-line">
    <ul class="nav nav-tabs param-criteria-tabs">
        <li class="nav-item">
            <a href="#field" class="nav-link active" data-toggle="tab">Field criteria</a>
        </li>
        <li class="nav-item">
            <a href="#record" class="nav-link" data-toggle="tab">Record criteria</a>
        </li>
    </ul>
</div>
<div class="tab-content">
    <div class="tab-pane active" id="field">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo Form::textArea(
                        array(
                            'name' => 'field_criteria',
                            'id' => 'field_criteria',
                            'class' => 'form-control',
                            'spellcheck' => 'false',
                            'rows' => 4,
                            'value'=>$this->fieldCriteria
                        )
                );
                ?>
            </div>
        </div>  
    </div>
    <div class="tab-pane" id="record">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo Form::textArea(
                        array(
                            'name' => 'record_criteria',
                            'id' => 'record_criteria',
                            'class' => 'form-control',
                            'spellcheck' => 'false',
                            'rows' => 4,
                            'value'=>$this->recordCriteria
                        )
                );
                ?>
            </div>
        </div>  
    </div>
</div>  
<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
    .CodeMirror-cursor { display: none !important }
</style>
<script type="text/javascript">
    var viewfieldCriteriaEditor = CodeMirror.fromTextArea(document.getElementById("field_criteria"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material",
        readOnly: true
    });
    var viewrecordCriteriaEditor = CodeMirror.fromTextArea(document.getElementById("record_criteria"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material",
        readOnly: true
    });
    $(function() {
        $('.param-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function() {
            viewfieldCriteriaEditor.refresh();
            viewrecordCriteriaEditor.refresh();
        });
    });
</script>    