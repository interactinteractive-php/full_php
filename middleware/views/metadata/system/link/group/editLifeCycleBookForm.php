<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12">
    <?php 
    $row = $this->getlifeCycleBook;
    echo Form::create(array('class' => 'form-horizontal', 'id' => 'lifecyclebook-form', 'method' => 'post')); 
    echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->metaDataId)); 
    echo Form::hidden(array('name' => 'lcBookId', 'id' => 'lcBookId', 'value' => $row['ID'])); 
    ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'for' => 'lcBookCode', 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
        <div class="col-md-10">
            <?php
            echo Form::text(
                    array(
                        'name' => 'lcBookCode',
                        'id' => 'lcBookCode',
                        'class' => 'form-control',
                        'required' => 'required',
                        'value' => $row['LC_BOOK_CODE']
                    )
            );
            ?>
        </div>
    </div>

    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00125'), 'for' => 'lcBookName', 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
        <div class="col-md-10">
            <?php
            echo Form::text(
                array(
                    'name' => 'lcBookName',
                    'id' => 'lcBookName',
                    'class' => 'form-control',
                    'required' => 'required',
                    'value' => $row['LC_BOOK_NAME']
                )
            );
            ?>
        </div>
    </div>
    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Шалгуур', 'for' => 'isActive', 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
        <div class="col-md-10">
            <?php
            echo Form::textArea(
                array(
                    'name' => 'criteria',
                    'id' => 'criteria',
                    'class' => 'form-control',
                    'required' => 'required',
                    'value' => $row['CRITERIA']
                )
            );
            ?>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>
<script type="text/javascript">
    var criteria = CodeMirror.fromTextArea(document.getElementById("criteria"), {
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