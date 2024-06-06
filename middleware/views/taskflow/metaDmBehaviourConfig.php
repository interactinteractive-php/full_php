<div class="row-fluid" id="metaDmBehaviourWindow">
    <div class="col-md-12">
        <form role="form" id="metaDmBehaviour-form" method="post">
            <?php 
            echo Form::hidden(array('name' => 'id', 'id' => 'id', 'value' => $this->row['id'])); 
            echo Form::hidden(array('name' => 'mainLifeCycle', 'id' => 'mainLifeCycle', 'value' => Input::post('mainLifeCycle'))); 
            echo Form::hidden(array('name' => 'mainProcess', 'id' => 'mainProcess', 'value' => Input::post('mainProcess'))); 
            echo Form::hidden(array('name' => 'doneLifeCycle', 'id' => 'doneLifeCycle', 'value' => Input::post('doneLifeCycle'))); 
            echo Form::hidden(array('name' => 'doneProcess', 'id' => 'doneProcess', 'value' => Input::post('doneProcess'))); 
            echo Form::hidden(array('name' => 'dtlRowId', 'id' => 'dtlRowId', 'value' => Input::post('dtlRowId'))); 
            echo Form::hidden(array('name' => 'batchNumber', 'id' => 'batchNumber', 'value' => Input::post('batchNumber'))); 
            ?>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group row fom-row">
                            <?php echo Form::label(array('text' => 'Main Process', 'for' => 'mainProcess', 'required' => 'required')); ?>
                            <br>
                            <label for="sourceProcess"> <?php echo $this->srcProcessName; ?></label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group row fom-row">
                            <?php echo Form::label(array('text' => 'Done Process', 'for' => 'doneProcess', 'required' => 'required')); ?>
                            <br>
                            <label for="targetProcess"> <?php echo $this->trgProcessName; ?></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group row fom-row">
                            <?php
                            echo Form::label(array('text' => 'Max repeat', 'for' => 'maxRepeatCount', 'required' => 'required'));
                            echo Form::text(
                                    array(
                                        'name' => 'maxRepeatCount',
                                        'id' => 'maxRepeatCount',
                                        'class' => 'form-control form-control-sm longInit text-right',
                                        'required' => 'required',
                                        'value' => Input::post('maxRepeatCount'),
                                        'data-v-min' => '-1'
                                    )
                            );
                            ?>
                        </div>
                    </div>
                    <div class="clearfix w-100"></div>
                    <div class="col-md-12">
                        <div class="form-group row fom-row">
                            <?php
                            //echo Form::label(array('text' => 'In param criteria', 'for' => 'inParamCriteria'));
                            $inParamCriteriaArr = array(
                                'name' => 'inParamCriteria',
                                'id' => 'inParamCriteria',
                                'class' => 'form-control ace-textarea',
                                'spellcheck' => 'false',
                                'style' => 'width: 100%; height: 200px;',
                                'value' => Input::post('inParamCriteria')
                            );
                            if ($this->criteriaDisable) {
                                $inParamCriteriaArr = array_merge($inParamCriteriaArr, array('disabled' => 'disabled'));
                            }
                            echo Form::textArea($inParamCriteriaArr);
                            ?>
                        </div>
                    </div>
                </div>
            </div>    
        </form>    
    </div>
</div>
<script type="text/javascript">
    var metaDmBehaviourWindowId = '#metaDmBehaviourWindow';
    var expressionEditorInParam = CodeMirror.fromTextArea(document.getElementById("inParamCriteria"), {
            mode: "javascript",
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: "material"
        });

</script>