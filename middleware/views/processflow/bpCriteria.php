<style type="text/css">
    .CodeMirror-line{
        margin-bottom: 0 !important;
    }
</style>
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'brcriteria-form', 'method' => 'post')); ?>
<div class="col-md-12">
    <?php 
    echo Form::hidden(array('name' => 'sourceId', 'id' => 'sourceId', 'value' => $this->sourceId)); 
    echo Form::hidden(array('name' => 'targetId', 'id' => 'targetId', 'value' => $this->targetId)); 
    echo Form::textArea(array('name' => 'criteria', 'id' => 'criteria', 'class' => 'form-control', 'value' => $this->criteria, 'style'=>'min-height:300px;'));
    ?>
</div>
<?php echo Form::close(); ?>
<script type="text/javascript">
    var bpCriteriaEditorParam = CodeMirror.fromTextArea(document.getElementById("criteria"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 1,
        theme: "material"
    });
</script>