<?php
if ($this->dataGridHeadData) {
    foreach ($this->dataGridHeadData as $row) {
?>
<div class="col-md-4">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>$row['LABEL_NAME'],'class'=>'col-form-label col-md-6')); ?>
        <div class="col-md-6">
            <?php echo Form::text(array('class'=>'form-control form-control-sm')); ?>
        </div>
    </div>    
</div>    
<?php
    }
?>
<div class="clearfix w-100"></div>
<?php
}
?>