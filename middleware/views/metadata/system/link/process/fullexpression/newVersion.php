<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'new-version-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Гарчиг', 'for' => 'title', 'class' => 'col-form-label col-md-2', 'required'=>'required')); ?>
        <div class="col-md-10">
            <?php echo Form::text(array('name' => 'title', 'id' => 'title', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00007'), 'for' => 'description', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-10">
            <?php echo Form::textArea(array('name' => 'description', 'id' => 'description', 'class'=>'form-control form-control-sm', 'rows' => 6)); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Default эсэх', 'for' => 'isDefault', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-10">
            <?php echo Form::checkbox(array('name' => 'isDefault', 'id' => 'isDefault', 'value' => 1)); ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>