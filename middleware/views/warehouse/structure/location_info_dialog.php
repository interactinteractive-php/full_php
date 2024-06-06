<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php 
/*
echo 'LOCATION_ID=' . $this->LOCATION_ID . '<br>';
echo 'ISWAREHOUSE=' . $this->ISWAREHOUSE . '<br>';
echo 'QUERYTYPE=' . $this->QUERYTYPE . '<br>';
echo 'COORDINATE_X=' . $this->COORDINATE_X . '<br>';
echo 'COORDINATE_Y=' . $this->COORDINATE_Y . '<br>';
echo 'OLD_COORDINATE_X=' . $this->OLD_COORDINATE_X . '<br>';
echo 'OLD_COORDINATE_Y=' . $this->OLD_COORDINATE_Y . '<br>';
 * */
 
?>
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'addWH-form', 'method' => 'post')); ?>
<?php
echo Form::hidden(array('value'=>$this->LOCATION_ID, 'id'=>'LOCATION_ID', 'name'=>'LOCATION_ID')); 
echo Form::hidden(array('value'=>$this->WAREHOUSE_ID, 'id'=>'WAREHOUSE_ID', 'name'=>'WAREHOUSE_ID')); 
?>
<div class="col-md-6">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Агуулах', 'for'=>'WAREHOUSE', 'class'=>'col-form-label col-md-4'));?>
        <div class="col-md-8">
        <?php echo Form::text(array('value'=>'Б агуулах', 'readonly'=>true, 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Нярав', 'for'=>'WAREHOUSE', 'class'=>'col-form-label col-md-4'));?>
        <div class="col-md-8">
        <?php echo Form::text(array('value'=>'А.Батбаатар', 'readonly'=>true, 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Байршил', 'for'=>'WAREHOUSE', 'class'=>'col-form-label col-md-4'));?>
        <div class="col-md-8">
        <?php echo Form::text(array('value'=>'Гутлын эгнээ', 'readonly'=>true, 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Үлдэгдэл', 'for'=>'WAREHOUSE', 'class'=>'col-form-label col-md-4'));?>
        <div class="col-md-8">
        <?php echo Form::text(array('value'=>'544000 хос өвлийн гутал', 'readonly'=>true, 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>