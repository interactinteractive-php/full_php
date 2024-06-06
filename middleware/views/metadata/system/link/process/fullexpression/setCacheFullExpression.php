<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'cache-version-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Run mode', 'for' => 'runMode', 'class' => 'col-form-label col-md-2', 'required'=>'required')); ?>
        <div class="col-md-3">
            <?php 
            echo Form::select(
                array(
                    'name' => 'runMode', 
                    'id' => 'runMode', 
                    'class' => 'form-control form-control-sm select2', 
                    'required' => 'required', 
                    'data' => $this->runModeData, 
                    'op_value' => 'code', 
                    'op_text' => 'name', 
                    'value' => $this->runMode
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'for' => 'code', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-6">
            <?php echo Form::text(array('name' => 'code', 'id' => 'code', 'value' => $this->code, 'class'=>'form-control form-control-sm', 'required' => 'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Group path', 'for' => 'groupPath', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-6">
            <?php echo Form::text(array('name' => 'groupPath', 'id' => 'groupPath', 'value' => $this->groupPath, 'class'=>'form-control form-control-sm', 'required' => 'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00007'), 'for' => 'description', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-10">
            <?php echo Form::textArea(array('name' => 'description', 'id' => 'description', 'value' => $this->descr, 'class'=>'form-control form-control-sm', 'rows' => 2)); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <div class="col-md-12">
            <?php echo Form::textArea(array('name' => 'cacheExpression', 'id' => 'cacheExpression', 'value' => $this->expression)); ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<style type="text/css">
#cache-version-form .CodeMirror {
    height: 400px;
}    
#cache-version-form .CodeMirror-line {
    margin-bottom: 0 !important;
}
</style>

<script type="text/javascript">
var cacheExpressionEditor = CodeMirror.fromTextArea(document.getElementById('cacheExpression'), {
    mode: 'javascript',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: 'material', 
    extraKeys: {
        "Alt-F": "findPersistent", 
        "F11": function(cm) {
            cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
    }
});
</script>