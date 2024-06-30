<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'report-archive-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <?php
    if ($this->isContentCode) {
    ?>
    <div class="form-group row">
        <?php echo Form::label(['text' => 'Код', 'for' => 'contentCode', 'class' => 'col-form-label col-md-3 pt-1', 'required' => 'required']); ?>
        <div class="col-md-8">
            <?php 
            echo Form::text([
                'name' => 'contentCode', 
                'value' => $this->contentCode, 
                'id' => 'contentCode', 
                'class' => 'form-control form-control-sm', 
                'required' => 'required'
            ]); 
            ?>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="form-group row">
        <?php echo Form::label(['text' => 'Нэр', 'for' => 'contentName', 'class' => 'col-form-label col-md-3 pt-1', 'required' => 'required']); ?>
        <div class="col-md-8">
            <?php 
            echo Form::text([
                'name' => 'contentName', 
                'value' => $this->defaultName, 
                'id' => 'contentName', 
                'class' => 'form-control form-control-sm', 
                'required' => 'required'
            ]); 
            ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>