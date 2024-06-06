<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'expCriteria-form', 'method' => 'post')); ?>
    <div class="form-group row fom-row mb0">
        <?php echo Form::label(array('text' => 'Param name', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <p class="form-control-plaintext font-weight-bold"><?php echo $this->params['paramName'] . ' /' . $this->params['paramPath'] . '/'; ?></p>
        </div>
    </div>
    <div class="tabbable-line">
        <ul class="nav nav-tabs param-criteria-tabs">
            <li class="nav-item">
                <a href="#set-exp-tab1" class="nav-link active" data-toggle="tab">Тайлбар</a>
            </li>
            <li class="nav-item">
                <a href="#set-exp-tab2" class="nav-link" data-toggle="tab">Expression</a>
            </li>
            <li class="nav-item">
                <a href="#set-exp-tab3" class="nav-link" data-toggle="tab">Validation</a>
            </li>       
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="set-exp-tab1">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'analysisDescription_set',
                                'id' => 'analysisDescription_set',
                                'class' => 'form-control',
                                'value' => $this->params['analysisDescription'],
                                'spellcheck' => 'false',
                                'rows' => 4
                            )
                        );
                        ?>
                    </div>
                </div>    
            </div> 
            <div class="tab-pane" id="set-exp-tab2">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'analysisExpression_set',
                                'id' => 'analysisExpression_set',
                                'class' => 'form-control',
                                'value' => $this->params['analysisExpression'],
                                'spellcheck' => 'false',
                                'rows' => 4
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="set-exp-tab3">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'validationCriteria_set',
                                'id' => 'validationCriteria_set',
                                'class' => 'form-control',
                                'value' => $this->params['validationCriteria'],
                                'spellcheck' => 'false',
                                'rows' => 4
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>   
        </div>
    </div>
<?php echo Form::close(); ?>
<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>
<script type="text/javascript">
    var analysisDescriptionEditor = CodeMirror.fromTextArea(document.getElementById("analysisDescription_set"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    var analysisExpressionEditor = CodeMirror.fromTextArea(document.getElementById("analysisExpression_set"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    var validationCriteriaEditor = CodeMirror.fromTextArea(document.getElementById("validationCriteria_set"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    $(function() {
        $('.param-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function() {
            analysisDescriptionEditor.refresh();
            analysisExpressionEditor.refresh();
            validationCriteriaEditor.refresh();
        });
    });  
</script>