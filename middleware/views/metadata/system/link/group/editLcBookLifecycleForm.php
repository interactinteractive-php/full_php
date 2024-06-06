<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="col-md-12 xs-form">
    <?php
    $row = $this->getLcBookLifecycle;
    echo Form::create(array('class' => 'form-horizontal', 'id' => 'lcbook-lifecycle-form', 'method' => 'post'));
    echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->metaDataId));
    echo Form::hidden(array('name' => 'lcBookId', 'id' => 'lcBookId', 'value' => $row['lcBook']['ID']));
    echo Form::hidden(array('name' => 'lifecycleId', 'id' => 'lifecycleId', 'value' => $row['lifecycle']['LIFECYCLE_ID']));
    ?>
    <fieldset class="collapsible mb20">
        <legend>Lifecycle Book</legend>
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
                            'value' => $row['lcBook']['LC_BOOK_CODE']
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
                            'value' => $row['lcBook']['LC_BOOK_NAME']
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
                            'value' => $row['lcBook']['CRITERIA']
                        )
                );
                ?>
            </div>
        </div>
    </fieldset>
    <fieldset class="collapsible">
        <legend>Lifecycle</legend>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'for' => 'lifecycleCode', 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
            <div class="col-md-10">
                <?php
                echo Form::text(
                        array(
                            'name' => 'lifecycleCode',
                            'id' => 'lifecycleCode',
                            'class' => 'form-control',
                            'required' => 'required',
                            'value' => $row['lifecycle']['LIFECYCLE_CODE']
                        )
                );
                ?>
            </div>
        </div>

        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('META_00125'), 'for' => 'lifecycleName', 'required' => 'required', 'class' => 'col-md-2 col-form-label')); ?>
            <div class="col-md-10">
                <?php
                echo Form::text(
                        array(
                            'name' => 'lifecycleName',
                            'id' => 'lifecycleName',
                            'class' => 'form-control',
                            'required' => 'required',
                            'value' => $row['lifecycle']['LIFECYCLE_NAME']
                        )
                );
                ?>
            </div>
        </div>
    </fieldset>
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